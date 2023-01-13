<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 5:15 AM
 */

namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems;


use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\EntryItemBase;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\LinkFormatter;

class WPFormFileUploadEntryItem extends EntryItemBase
{
    public $URL;
    public $FileName;
    public $Ext;
    public $OriginalName;


    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->URL,
            'FileName'=>$this->FileName,
            'Ext'=>$this->Ext,
            'OriginalName'=>$this->OriginalName
        );
    }


    public function InitializeWithValues($field,$url,$fileName,$ext,$originalName)
    {
        $this->Initialize($field);
        $this->URL=$url;
        $this->FileName=$fileName;
        $this->Ext=$ext;
        $this->OriginalName=$originalName;

        return $this;
    }

    public function InitializeWithOptions($field,$options){
        $this->Field=$field;
        if(isset($options->Value))
            $this->URL=$options->Value;
        if(isset($options->FileName))
            $this->FileName=$options->FileName;
        if(isset($options->Ext))
            $this->Ext=$options->Ext;
        if(isset($options->OriginalName))
            $this->OriginalName=$options->OriginalName;
    }

    public function GetHtml()
    {
        return new LinkFormatter($this->URL,$this->OriginalName);
    }
}