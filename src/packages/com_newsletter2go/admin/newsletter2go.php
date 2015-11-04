<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by Newsletter2Go
$controller = JControllerLegacy::getInstance('Newsletter2Go');
 
// Perform the Request task
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
