<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/19/2019
 * Time: 11:39 AM
 */

namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\FormProcessor;



use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormDateFieldSettings;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormNameFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\FormProcessor\FormProcessorBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FileUploadFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\NumberFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\TextFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\FormSettings;
use Svg\Tag\Text;

class WPFormFormProcessor extends FormProcessorBase
{
    public function __construct($loader)
    {
        parent::__construct($loader);
        \add_action('wpforms_save_form',array($this,'FormIsSaving'),10,2);
    }

    public function FormIsSaving($formId,$forms){
        $forms['post_content']=\stripslashes($forms['post_content']);
        $forms=$this->SerializeForm($forms);
        $this->SaveOrUpdateForm($forms);
    }


    public function SerializeForm($forms){
        $fieldList=\json_decode( ($forms['post_content']));
        if(isset($fieldList->fields))
            $fieldList=$fieldList->fields;
        else
            $fieldList=array();

        $formSettings=new FormSettings();
        $formSettings->OriginalId=$forms['ID'];
        $formSettings->Name=$forms['post_title'];
        $formSettings->Fields=$this->SerializeFields($fieldList);


        return $formSettings;
    }

    public function SerializeFields($fieldList)
    {
        /** @var FieldSettingsBase[] $fieldSettings */
        $fieldSettings=array();
        foreach($fieldList as $field)
        {
            switch($field->type)
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
                $fieldSettings[]=(new TextFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'radio':
                case 'checkbox':
                case 'payment-multiple':
                case 'select':
                case 'payment-select':
                    $settings=(new MultipleOptionsFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    foreach($field->choices as $choice)
                    {
                        $settings->AddOption($choice->label,$choice->value);
                    }
                $fieldSettings[]=$settings;
                    break;
                case 'number':
                    $fieldSettings[]=(new NumberFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'name':
                    $fieldSettings[]=(new WPFormNameFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'address':
                    $fieldSettings[]=(new WPFormAddressFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'date-time':
                    $fieldSettings[]=(new WPFormDateFieldSettings())->Initialize($field->id,$field->label,$field->type)
                        ->SetDateFormat($field->date_format)
                        ->SetTimeFormat($field->time_format);
                    break;
                case 'file-upload':
                    $fieldSettings[]=(new FileUploadFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
            }
        }

        return $fieldSettings;
    }

    public function SyncCurrentForms()
    {
        global $wpdb;
        $results=$wpdb->get_results("select id ID, post_title,post_content from ".$wpdb->posts." where post_type='wpforms'",'ARRAY_A');
        foreach($results as $form)
        {
            $form=$this->SerializeForm($form);
            $this->SaveOrUpdateForm($form);
        }
    }

    public function GetFormList()
    {
        global $wpdb;

        $rows= $wpdb->get_results("select form.id Id, post.post_title Name, form.fields Fields,original_id OriginalId from ".$wpdb->posts. " post join ". $this->Loader->FormConfigTable." form on post.id=form.original_id");
        return $rows;
    }
}