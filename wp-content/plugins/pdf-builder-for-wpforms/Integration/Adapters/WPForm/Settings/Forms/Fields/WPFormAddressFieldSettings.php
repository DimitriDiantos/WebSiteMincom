<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 6:15 AM
 */

namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;

class WPFormAddressFieldSettings extends FieldSettingsBase
{

    public function GetType()
    {
        return 'Address';
    }
}