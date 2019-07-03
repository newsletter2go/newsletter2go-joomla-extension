<?php

/**
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Newsletter2Go View
 */
class Newsletter2GoViewNewsletter2Go extends JViewLegacy
{

    const N2GO_INTEGRATION_URL = 'https://ui.newsletter2go.com/integrations/connect/JO/';

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
        $queryParams['version'] = $model->getVersion();
        $queryParams['apiKey'] = $model->getOption('apiKey');
        if($queryParams['apiKey']==''){
            $model->setOption('apiKey',$this->generateRandomString());
            $queryParams['apiKey'] = $model->getOption('apiKey');
        }

        $lang = JFactory::getLanguage();
        $queryParams['language'] = current(explode("-", $lang->getDefault()));
        $queryParams['url'] = JURI::root();
        $queryParams['callback'] = $queryParams['url']."index.php?option=com_newsletter2go&task=n2goCallback";

        $this->apiKeyConnectUrl = self::N2GO_INTEGRATION_URL . '?' . http_build_query($queryParams);
        $this->forms = $model->getForms($this->authKey);

        $this->widget = $model->getOption('widget');

        if (!strlen(trim($this->formUniqueCode)) > 0 || $this->formUniqueCode === null) {
            $this->_errors['formId'] = "Please connect to Newsletter2Go by clicking on \"Login or Create Account\" button";
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
        $document->addStyleSheet(JURI::root() . "administrator/components/com_newsletter2go/views/newsletter2go/bootstrap-colorpicker-master/bootstrap-colorpicker.min.css");
        $document->addStyleSheet(JURI::root() . "administrator/components/com_newsletter2go/views/newsletter2go/font-awesome.css");
        $document->addStyleSheet(JURI::root() . "administrator/components/com_newsletter2go/views/newsletter2go/newsletter2go.css");
        JText::script('COM_NEWSLETTER2GO_NEWSLETTER2GO_ERROR_UNACCEPTABLE');
    }

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
