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

    const N2GO_INTEGRATION_URL = 'https://www.newsletter2go.com/integrations/#/integration/JO';

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
        $this->formUniqueCode = $model->getOption('formUniqueCode');
        $this->configFormStyles = $model->getOption('configFormStyles');
        $this->authKey = $model->getOption('authKey');
        $lang = JFactory::getLanguage();
        $langCode = current(explode("-", $lang->getDefault()));
        $version = $model->getVersion();
        $baseUrl = urlencode(JURI::root());
        $callbackUrl = urlencode(JURI::root()."index.php?option=com_newsletter2go&task=n2goCallback");
        $this->apiKeyConnectUrl = self::N2GO_INTEGRATION_URL."?version=". $version ."&apiKey=". $this->apiKey ."&language=". $langCode ."&url=". $baseUrl ."&callback=". $callbackUrl;

        $this->forms = $model->getForms($this->authKey);

        $this->widget = $model->getOption('widget');

        if (!strlen(trim($this->formUniqueCode)) > 0 || $this->formUniqueCode === null) {
            $this->_errors['formId'] = "Please, enter unique form id!";
        }

        $this->groups = $this->get('groups');
        $this->previewUrl = 'https://subscribe.newsletter2go.com/?n2g=' . $this->formUniqueCode;
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
