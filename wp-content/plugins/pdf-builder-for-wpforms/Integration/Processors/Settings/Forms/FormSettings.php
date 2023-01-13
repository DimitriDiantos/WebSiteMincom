<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 7:16 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Settings\Forms;


use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;

class FormSettings
{
    public $Id;
    public $OriginalId;
    public $Name;
    /** @var FieldSettingsBase []*/
    public $Fields;

    public function __construct()
    {
        $this->Fields=[];
    }

    public function AddFields($field)
    {
        $this->Fields[]=$field;
    }


}