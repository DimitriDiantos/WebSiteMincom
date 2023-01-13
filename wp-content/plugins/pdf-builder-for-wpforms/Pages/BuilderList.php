<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/25/2019
 * Time: 5:09 AM
 */

use rednaoformpdfbuilder\core\Loader;

if(!current_user_can('administrator'))
    die('Forbidden');
global $rninstance;
/** @var Loader $loader */
$loader=$rninstance;

if(isset($_GET['template_id']))
{
    require_once $loader->DIR.'Pages/PDFBuilder.php';
    return;
}


wp_enqueue_script('jquery');
$loader->AddScript('builder','js/dist/templateList_bundle.js',array('jquery', 'wp-element'));


global $wpdb;
$templates=$wpdb->get_results("select template.id Id,template.name TemplateName,config.name FormName from ".$loader->TEMPLATES_TABLE." template
left join  ".$loader->FormConfigTable." config
on config.id=template.form_id
");

$loader->LocalizeScript('rednaoVarTemplateList','builder','template_list',array(
    'URL'=>$rninstance->URL,
    'IsPr'=>$rninstance->IsPR(),
     'Templates'=>$templates,
    'AdminUrl'=>admin_url().'?page='.$loader->Prefix.'_pdf_builder'



));


?>

<div id="AppRoot"></div>
