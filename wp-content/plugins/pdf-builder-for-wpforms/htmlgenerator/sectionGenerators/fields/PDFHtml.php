<?php

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;


/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFHtml extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        return $this->options->HTML;
    }
}