<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:02 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry;


use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\EntryItemBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;

abstract class EntryProcessorBase
{
    /** @var Loader */
    public $Loader;
    public abstract  function InflateEntryItem(FieldSettingsBase $field,$entryData);
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }


    /**
     * @param $entryItems EntryItemBase []
     */
    public function SaveEntryToDB($originalFormId,&$entryItems,$originalEntryId,$raw=null){

        $itemsToSave=array();
        foreach($entryItems as $item)
        {
            $itemsToSave[]=$item->GetObjectToSave();
        }


        global $wpdb;
        $id=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->FormConfigTable.' where original_id=%d',$originalFormId));
        if($id===false)
            return 0;

        $seqKey= $this->Loader->Prefix.'_seq_'.$id.'seq';
        $seqId=\get_option($seqKey,1);
        $date= \date('c');

        $wpdb->insert($this->Loader->RECORDS_TABLE,array(
            'form_id'=>$id,
            'original_id'=>$originalEntryId,
            'date'=> $date,
            'user_id'=>\get_current_user_id(),
            'entry'=>\json_encode($itemsToSave),
            'seq_num'=>$seqId,
            'raw'=>\json_encode($raw)
        ));
        $factory=$this->Loader->CreateEntryRetriever()->GetFieldSettingsFactory();

        $entryItems[]=$this->InflateEntryItem($factory->GetFieldByOptions((object)array(
            'Id'=>'_seq_num',
            'Label'=>'Number',
            'Type'=>'Text',
            'SubType'=>'number'
        )),(object)array(
            'Value'=>$seqId,
            '_fieldId'=>'_seq_num'
        ));

        $entryItems[]=$this->InflateEntryItem($factory->GetFieldByOptions((object)array(
            'Id'=>'_creation_date',
            'Label'=>'Creation Date',
            'TimeFormat'=>"g:i A",
            'DateFormat'=>'m/d/Y',
            'Type'=>'Date',
            'SubType'=>'date-time'
        )),(object)array(
            'Value'=>date('m/d/Y',strtotime($date)),
            'Date'=>date('m/d/Y',strtotime($date)),
            'Time'=>'',
            'Unix'=>strtotime($date),
            '_fieldId'=>'_creation_date'
        ));



        $seqId++;
        \update_option($seqKey,$seqId);
        return $wpdb->insert_id;
    }

    /**
     * @param $entryData
     * @param $fields FieldSettingsBase[]
     * @return EntryItemBase[]
     */
    public function InflateEntry($entryData,  $fields)
    {
        $entryItemList=array();
        foreach($entryData as $entryDataItem)
        {
            foreach($fields as $fieldItem)
            {
                if($fieldItem->Id==$entryDataItem->_fieldId)
                {
                    $entryItemList[]=$this->InflateEntryItem($fieldItem,$entryDataItem);
                }
            }

        }

        return $entryItemList;

    }




}