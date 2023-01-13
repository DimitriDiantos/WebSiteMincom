<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/16/2019
 * Time: 5:47 AM
 */

namespace rednaoformpdfbuilder\ajax;



use rednaoformpdfbuilder\DTO\DocumentOptions;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\FieldFactory;
use rednaoformpdfbuilder\Utils\ImportExport\Importer;



class DesignerAjax extends AjaxBase
{

    public function __construct($core, $prefix)
    {
        parent::__construct($core, $prefix, 'builder');
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('execute_preview','ExecutePreview');
        $this->RegisterPrivate('save_template','SaveTemplate');
        $this->RegisterPrivate('generate_local_template','GenerateLocalTemplate');

        $this->RegisterPrivate('qrcode_preview','QRCodePreview');
    }

    public function GenerateLocalTemplate(){
        $id=$this->GetRequired('Id');
        $isPR=$this->GetRequired('IsPR');
        $url='';

        if(\file_exists($this->Loader->DIR."templates/$id/export.json"))
        {
            $code=\json_decode(\file_get_contents($this->Loader->DIR."templates/$id/export.json"));
            $this->SendSuccessMessage(array('data'=>$code));
        }

        if($isPR)
        {
            $url='';
            $url=\apply_filters($this->Loader->Prefix.'_download_pr_template',$url,$id);
            if($url=='')
                $this->SendErrorMessage('Could not download the template files, please make sure your site can connect with pdfbuilder.rednao.com');
        }else{
            if(!\file_exists($this->Loader->DIR."templates/$id/data.zip"))
            {
                $this->SendErrorMessage('Invalid template');
            }
            $url=$this->Loader->DIR."templates/$id/data.zip";
        }

        $zipArchive=new \ZipArchive();
        if($zipArchive->open($url)!==true)
            $this->SendErrorMessage('Could not open template file');

        $importer = new Importer($this->Loader, $zipArchive);
        $code = $importer->GetTemplateDocumentOptions();

        $this->SendSuccessMessage(array('data'=>$code));

    }

    public function QRCodePreview(){
        $data=$this->GetRequired('options');
        /** @var PDFQRCode $field */
        $field=FieldFactory::GetField($this->Loader,null, $data,null);
        $this->SendSuccessMessage($field->GetImage());
    }




    public function ExecutePreview(){

        $generator=(new PDFGenerator($this->Loader,$this->GetRequired('PageOptions'),null));
        $generator->GeneratePreview();
    }

    public function SaveTemplate(){
        /** @var DocumentOptions $data */
        $data=$this->GetData();
        global $wpdb;
        $id=0;
        $result=false;

        if(trim($data->Name)=='')
            $this->SendErrorMessage('Template name is mandatory');
        if($data->Id==0)
        {
            $count=$wpdb->get_var($wpdb->prepare('select count(*) from '.$this->Loader->TEMPLATES_TABLE.' where name=%s',$data->Name));
            if($count>0)
                $this->SendErrorMessage('Template name already in use, please define another.');
            $result=$wpdb->insert($this->Loader->TEMPLATES_TABLE,array(
               'form_id'=>$data->FormId,
               'pages'=>\json_encode($data->Pages),
                'styles'=>$data->Styles,
                'document_settings'=>\json_encode($data->DocumentSettings),
                'name'=>$data->Name
            ));
            $id=$wpdb->insert_id;

        }else{
            $count=$wpdb->get_var($wpdb->prepare('select count(*) from '.$this->Loader->TEMPLATES_TABLE.' where name=%s',$data->Name));
            if($count>1)
                $this->SendErrorMessage('Template name already in use, please define another.');

            $result=$wpdb->update($this->Loader->TEMPLATES_TABLE,array(
                'form_id'=>$data->FormId,
                'pages'=>\json_encode($data->Pages),
                'styles'=>$data->Styles,
                'document_settings'=>\json_encode($data->DocumentSettings),
                'name'=>$data->Name
            ),array(
                'id'=>$data->Id,
            ));

            $id=$data->Id;
        }

        if($result===false)
            $this->SendErrorMessage('An error ocurred, please try again');

        $this->SendSuccessMessage(array('id'=>$id));




    }
}