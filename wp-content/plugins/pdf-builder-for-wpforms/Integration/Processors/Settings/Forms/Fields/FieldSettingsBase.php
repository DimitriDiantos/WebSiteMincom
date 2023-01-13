<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 4:25 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields;
use stdClass;

abstract class FieldSettingsBase
{
    public $Id;
    public $Label;
    public $Type;
    public $SubType;
    public function Initialize($FieldId,$Label,$SubType){
        $this->Id=$FieldId;
        $this->Label=$Label;
        $this->Type=$this->GetType();
        $this->SubType=$SubType;
        return $this;
    }

    public function GetOptions(){
        $options=new stdClass();

    }

    public function InitializeFromOptions($options)
    {
        $this->Id=$options->Id;
        $this->Label=$options->Label;
        $this->Type=$options->Type;
        $this->SubType=$options->SubType;
    }

    public abstract function GetType();
}