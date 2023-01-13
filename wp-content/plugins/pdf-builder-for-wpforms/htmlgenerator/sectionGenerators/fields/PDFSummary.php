<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */
namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;

use rednaoformpdfbuilder\DTO\FieldSummaryOptions;
use rednaoformpdfbuilder\htmlgenerator\tableCreator\HTMLTableCreator;

class PDFSummary extends PDFFieldBase
{

    /** @var FieldSummaryOptions */
    public $options;

    protected function InternalGetHTML()
    {
       $creator=new HTMLTableCreator('SummaryTable');
       $creator->CreateTBody('');

       foreach($this->options->Fields as $field)
       {
           $value='';
           if($this->entryRetriever==null)
                $value='Value not available in preview';
           else
           {
               $value = $this->entryRetriever->GetHtmlByFieldId($field->Id);
               if ($value==null||$value->IsEmpty())
                   continue;
           }

           $creator->CreateRow('');


           $subCreator=new HTMLTableCreator('');
           $subCreator->CreateTHead('');
           $subCreator->CreateRow('');
           $subCreator->CreateTextColumn($field->Label,'FieldLabel');

           $subCreator->CreateTBody('');
           $subCreator->CreateRow('');
           $subCreator->CreateRawColumn($value,'FieldValue');



           $creator->CreateRawColumn($subCreator->GetHTML(),'');
       }

       return $creator->GetHTML();

    }
}