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
        $model->setOption('formUniqueCode', $input->getString('formUniqueCode'));
        $model->setOption('configFormStyles', $input->getString('widgetStyleConfig'));
        $model->setOption('widget', $input->get('widgetSourceCode', null, 'raw'));

        $this->display();
    }

    /**
     * Method to reset api key
     * @return  void
     */
    public function resetApiKey()
    {
        $model = $this->getModel('newsletter2go');
        $model->setOption('apiKey', $this->generateRandomString());
        $this->display();
    }

    /**
     * Generates random string with $length characters
     *
     * @param int $length
     * @return string
     */
    private function generateRandomString($length = 40)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

}
