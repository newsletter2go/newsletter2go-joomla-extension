<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class Newsletter2GoViewWidget extends JViewLegacy
{
    public function display($tpl = null)
    {
        $this->widget = urldecode(JFactory::getApplication()->input->get('widgetSource', null, 'raw'));
        parent::display($tpl);
    }
}

