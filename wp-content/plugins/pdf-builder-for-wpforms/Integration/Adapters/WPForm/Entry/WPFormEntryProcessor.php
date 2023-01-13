<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:03 AM
 */

namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry;


use DateTime;
use DateTimeZone;
use Exception;
use rednaoformpdfbuilder\htmlgenerator\generators\FileManager;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormAddressEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormDateTimeEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormFileUploadEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormNameEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\EntryItemBase;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\MultipleSelectionEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\SimpleTextEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryProcessorBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\FormSettings;
use stdClass;

class WPFormEntryProcessor extends EntryProcessorBase
{
    public function __construct($loader)
    {
        parent::__construct($loader);
        \error_reporting(\E_ERROR);

        \add_action('wpforms_post_insert_',array($this,'UpdateOriginalEntryId'),10,2);
        \add_action('wpforms_process_entry_save',array($this,'SaveEntry'),10,4);

            \add_action('wpforms_email_attachments',array($this,'AddAttachment'),10,2);



        \add_filter(
            'wpforms_tasks_entry_emails_trigger_send_same_process',array($this,'SendSameProcess'));


    }

    public function SendSameProcess($sameProcess)
    {
        return true;
    }
    public function UpdateOriginalEntryId($entryId,$formData)
    {
        if(!isset($formData['fields']))
            return;
        global $RNWPCreatedEntry;
        if(!isset($RNWPCreatedEntry)||!isset($RNWPCreatedEntry['Entry']))
            return;

        global $wpdb;
        $wpdb->update($this->Loader->RECORDS_TABLE,array(
            'original_id'=>$entryId
        ),array('id'=>$RNWPCreatedEntry['EntryId']));

    }

    public function SaveLittleEntry($fields,$entry,$formId,$formData,$entryId=0)
    {
        $this->SaveEntry($fields,$entry,$formId,$formData,0);
    }

    public function SaveEntry($fields,$entry,$formId,$formData,$entryId=0){
        $formProcessor=new WPFormFormProcessor($this->Loader);
        $formSettings=$formProcessor->SerializeForm(array(
            "ID"=>$formData['id'],
            'post_title'=>'',
            'post_content'=>\json_encode(array('fields'=>$formData['fields']))
        ));
        global $wpdb;
        $formSettings->Id=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->FormConfigTable." where original_id=%d",$formSettings->OriginalId));
        if($formSettings->Id==null)
            return;

        $entry=$this->SerializeEntry($fields,$formSettings);

        $pdfTemplates=array();
        if(isset($formData['meta']['pdfTemplates']))
            $pdfTemplates=$formData['meta']['pdfTemplates'];



        $entryId=$this->SaveEntryToDB($formData['id'],$entry,$entryId,array('Fields'=>$fields));

        $pdfTemplates[]=array('EntryId'=>$entryId);
        $formData['meta']['pdfTemplates']=$pdfTemplates;
        global $RNWPCreatedEntry;
        $RNWPCreatedEntry=array(
            'Entry'=>$entry,
            'FormId'=>$formData['id'],
            'EntryId'=>$entryId,
            'Raw'=>json_decode( \json_encode(array('Fields'=>$fields)))
        );
    }

    public function AddAttachmentNew($emailData,$wpform)
    {
        $emailData['attachments']=$this->AddAttachment($emailData['attachments'],null);
        return $emailData;
    }

    public function AddAttachment($attachment,$target)
    {
        global $RNWPCreatedEntry;
        if(!isset($RNWPCreatedEntry)||!isset($RNWPCreatedEntry['Entry']))
            return $attachment;

        $fm=new FileManager($this->Loader);
        $fm->RemoveTempFolders();

        $entryRetriever=new WPFormEntryRetriever($this->Loader);
        $entryRetriever->InitializeByEntryItems($RNWPCreatedEntry['Entry'],$RNWPCreatedEntry['Raw']);

        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare(
            "select template.id Id,template.pages Pages, template.document_settings DocumentSettings,styles Styles,form_id FormId
                    from ".$this->Loader->FormConfigTable." form
                    join ".$this->Loader->TEMPLATES_TABLE." template
                    on form.id=template.form_id
                    where original_id=%s"
            ,$RNWPCreatedEntry['FormId']));
        $files=[];
        if(!isset($RNWPCreatedEntry['CreatedDocuments'])){
            $RNWPCreatedEntry['CreatedDocuments']=[];
        }
        foreach($result as $templateSettings)
        {
            $templateSettings->Pages=\json_decode($templateSettings->Pages);
            $templateSettings->DocumentSettings=\json_decode($templateSettings->DocumentSettings);

            $generator=(new PDFGenerator($this->Loader,$templateSettings,$entryRetriever));
            $path=$generator->SaveInTempFolder();

            $RNWPCreatedEntry['CreatedDocuments'][]=array(
                'TemplateId'=>$generator->options->Id,
                'Name'=>$generator->options->DocumentSettings->FileName
            );
            $attachment[]=$path;

        }

        return $attachment;

    }

    public function SerializeEntry($entry, $formSettings)
    {
        /** @var EntryItemBase $entryItems */
        $entryItems=array();
        foreach($entry as $key=>$value)
        {
            $currentField=null;
            foreach($formSettings->Fields as $field)
            {
                if($field->Id==$key)
                {
                    $currentField=$field;
                    break;
                }
            }

            if($currentField==null)
                continue;

            switch($currentField->SubType)
            {
                case 'text':
                case 'email':
                case 'password':
                case "phone":
                case "hidden":
                case 'textarea':
                case 'url':
                case 'number':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue($value['value']);

                    break;
                case 'payment-single':
                case 'payment-total':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue($value['amount']);
                    break;
                case 'radio':
                case 'checkbox':
                case 'select':
                    $value=$value['value'];
                    $value=\explode("\n",$value);
                    $entryItems[]=(new MultipleSelectionEntryItem())->Initialize($currentField)->SetValue($value);

                    break;
                case 'payment-select':
                case 'payment-multiple':
                    if(!\is_array($value))
                    {
                        $value=[$value];
                    }
                    $amount=0;
                    if(isset($value['amount']))
                        $amount=$value['amount'];
                    $entryItems[]=(new MultipleSelectionEntryItem())->Initialize($currentField)->SetValue($value['value_choice'],$amount);
                    break;

                case 'credit-card':

                    break;
                case 'name':
                    if(isset($value['first'])&&$value['first']!='')
                        $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['first'],$value['last']);
                    else
                        $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['value'],'');
                    break;
                case 'address':
                    $entryItems[]=(new WPFormAddressEntryItem())->InitializeWithValues($currentField,$value['address1'],
                        $value['address2'],$value['city'],$value['state'],$value['postal']);
                    break;
                case 'date-time':

                    $time='';
                    $date='';

                    if(isset($value['time'])&&$value['time']!='')
                    {
                        $time=$value['time'];
                        $dateObject=DateTime::createFromFormat('m/d/Y '.$currentField->TimeFormat,'1/1/1970 ' .$time,new DateTimeZone('UTC'));
                        $unix=$value['unix'];

                    }else{
                        $time='';
                    }
                    if(isset($value['date'])&&$value['date']!='')
                    {
                        $date=$value['date'];
                        $dateObject=DateTime::createFromFormat($currentField->DateFormat.' H:i:s:u',$value['date'] . "0:00:00:0",new DateTimeZone('UTC'));
                        if($dateObject!=false)
                        {
                            $unix+=$dateObject->getTimestamp();
                        }

                        $unix=$value['unix'];

                    }else{
                        $date='';
                    }

                    $entryItems[]=(new WPFormDateTimeEntryItem())->InitializeWithValues($currentField,$value['value'],$date,$time,$unix);


                    break;
                case 'file-upload':
                    $mime='';
                    $entryItems[]=(new WPFormFileUploadEntryItem())->InitializeWithValues($currentField, $value['value'],$value['file'],$value['ext'],$value['file_original']);
                    break;
            }
        }


        return $entryItems;

    }

    public function InflateEntryItem(FieldSettingsBase $field,$entryData)
    {
        $entryItem=null;
        switch($field->SubType)
        {
            case 'text':
            case 'email':
            case 'password':
            case "phone":
            case "hidden":
            case 'payment-single':
            case 'textarea':
            case 'payment-total':
            case 'url':
            case 'number':
                $entryItem= new SimpleTextEntryItem();
                break;
            case 'radio':
            case 'checkbox':
            case 'payment-multiple':
            case 'select':
            case 'payment-select':
                $entryItem= new MultipleSelectionEntryItem();
                break;
            case 'credit-card':
                break;
            case 'name':
                $entryItem= new WPFormNameEntryItem();
                break;
            case 'address':
                $entryItem=  new WPFormAddressEntryItem();
                break;

            case 'date-time':
                $entryItem= new WPFormDateTimeEntryItem();
                break;
            case 'file-upload':
                $entryItem= new WPFormFileUploadEntryItem();
                break;
        }

        if($entryItem==null)
            throw new Exception("Invalid entry sub type ".$field->SubType);
        $entryItem->InitializeWithOptions($field,$entryData);
        return $entryItem;
    }


}