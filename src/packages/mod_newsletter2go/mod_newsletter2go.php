<?php

defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$widgetConfig = ModNewsletter2GoHelper::getOption('configFormStyles', '{}');
$formUniqueCode = ModNewsletter2GoHelper::getOption('formUniqueCode', '');

require( JModuleHelper::getLayoutPath('mod_newsletter2go'));