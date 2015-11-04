<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Newsletter2Go View
 */
class Newsletter2GoViewNewsletter2Go extends JViewLegacy
{

    /**
     * Display method of Newsletter2Go view
     *
     * @param $tpl
     * @return void
     */
    public function display($tpl = null)
    {
        $model = $this->getModel();

        $this->apiKey = $model->getOption('apiKey');
        $this->doiCode = $model->getOption('doiCode');

        $response = $model->checkDoiCode($this->doiCode);
        $this->doiSuccess = $response['success'];
        $this->doiHost = $response['host'];

        $this->widget = $model->getOption('widget');

        $this->fields = $model->getOption('fields', '[]', true);
        $this->texts = $model->getOption('texts', '[]', true);
        $this->colors = $model->getOption('colors', '[]', true);

        $this->groups = $this->get('groups');
        $this->attributes = $this->get('attributes');
        $this->previewUrl = JURI::root() . 'index.php?option=com_newsletter2go&widgetSource=';
        JToolbarHelper::title(JText::_('COM_NEWSLETTER2GO_VIEW_NEWSLETTER2GO_TITLE'), 'Newsletter2Go');
        parent::display();
        $this->setDocument();
    }

    protected function setDocument()
    {
        JToolbarHelper::apply('newsletter2go.save');
        $document = JFactory::getDocument();

        //loads scripts before jQuery in tmpl/default.php....
        $document->addStyleSheet(JURI::root() . "administrator/components/com_newsletter2go/views/newsletter2go/farbtastic/farbtastic.css");
        $document->addStyleSheet(JURI::root() . "administrator/components/com_newsletter2go/views/newsletter2go/newsletter2go.css");
        JText::script('COM_NEWSLETTER2GO_NEWSLETTER2GO_ERROR_UNACCEPTABLE');
    }

}
