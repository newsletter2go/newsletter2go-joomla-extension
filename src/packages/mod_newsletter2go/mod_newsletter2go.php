<?php

/**
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$widgetConfig = ModNewsletter2GoHelper::getOption('configFormStyles', '{}');
$formUniqueCode = ModNewsletter2GoHelper::getOption('formUniqueCode', '');
$widget = get_object_vars($module);
$uniqueId = uniqid();

if (isset($widget['params'])){

    $data = json_decode($widget['params']);
    $formType = get_object_vars($data);
    if (isset($formType['title'])) {
        $n2gParams = $formType['title'].':createForm';
    } else {
       $n2gParams = "subscribe:createForm";
   }

}

require( JModuleHelper::getLayoutPath('mod_newsletter2go'));