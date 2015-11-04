<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * The Menu Type Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class Newsletter2GoControllerNewsletter2Go extends JControllerForm
{

    /**
     * Dummy method to redirect back to standard controller
     *
     * @param   boolean $cachable - If true, the view output will be cached
     * @param   boolean $urlparams - An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController        This object to support chaining.
     * @since   1.5
     */
    public function display($cachable = false, $urlparams = false)
    {
        $this->setRedirect(JRoute::_('index.php?option=com_newsletter2go', false));
    }

    /**
     * Method to apply changes
     * @param $key
     * @param $urlVar
     * @return  void
     */
    public function save($key = null, $urlVar = null)
    {
        $model = $this->getModel('newsletter2go');
        $input = JFactory::getApplication()->input;
        $model->setOption('apiKey', $input->getString('apiKey'));
        $model->setOption('doiCode', $input->getString('doiCode'));
        $checks = $input->getArray();
        $attributes = array('email' => array(
            'sort' => $input->getInt('emailSort'),
            'required' => true,
        ));
        for ($i = 0; $i < count($checks['attributes']); $i++) {
            $tmpName = $checks['attributes'][$i];
            $attributes[$tmpName] = array(
                'sort' => $input->getInt($tmpName . 'Sort'),
                'required' => $input->getBool($tmpName . 'Required'),
            );
        }

        $model->setOption('fields', $attributes);
        $model->setOption('titles', $checks['fieldTitles']);

        $general = array();
        $general['success'] = $checks['success'];
        $general['failureSubsc'] = $checks['failureSubsc'];
        $general['failureEmail'] = $checks['failureEmail'];
        $general['failureRequired'] = $checks['failureRequired'];
        $general['failureError'] = $checks['failureError'];
        $general['buttonText'] = $checks['buttonText'];
        $model->setOption('texts', $general);

        $colors = array();
        $colors['textColor'] = $checks['textColor'];
        $colors['borderColor'] = $checks['borderColor'];
        $colors['backgroundColor'] = $checks['backgroundColor'];
        $colors['btnTextColor'] = $checks['btnTextColor'];
        $colors['btnBackgroundColor'] = $checks['btnBackgroundColor'];
        $colors['formBackgroundColor'] = $checks['formBackgroundColor'];
        $model->setOption('colors', $colors);
        $widget = $input->get('widgetSourceCode', null, 'raw');
        $model->setOption('widget', $widget);

        $this->display();
    }

}
