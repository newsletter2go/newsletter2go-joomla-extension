<?php

defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$widget = ModNewsletter2GoHelper::getWidget();
$widget = ($widget ? $widget->value : '');

require( JModuleHelper::getLayoutPath('mod_newsletter2go'));