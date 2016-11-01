<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

require_once JPATH_ADMINISTRATOR.'/components/com_newsletter2go/models/newsletter2go.php';

class JFormFieldFormtype extends JFormFieldList {

    protected $type = 'Formtype';

    public function getInput() {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.value')->from('`#__newsletter2go` AS a')->where('a.name = "formUniqueCode" ');
        $rows = $db->setQuery($query)->loadObjectlist();

        foreach ($rows as $row){
            $formUniqueCode = $row->value;
        }

        $model = new Newsletter2GoModelNewsletter2Go();
        $forms = $model->getForms();

        if ($forms !== false){

            foreach ($forms as $form){
                if($formUniqueCode == $form['hash']){
                    $subscribe = $form['type_subscribe'];
                    $unsubscribe = $form['type_unsubscribe'];
                }
            }

            $subscribe == true ? $option1 = '<option value="subscribe" >'. JText::_('MOD_NEWSLETTER2GO_WIDGET_PREVIEW_SUBSCRIPTION_FORM') .'</option>' : $option1 = '';
            $unsubscribe == true ? $option2 = '<option value="unsubscribe" >'. JText::_('MOD_NEWSLETTER2GO_WIDGET_PREVIEW_UNSUBSCRIPTION_FORM') .'</option>' : $option2 = '';

            return '<select id="'.$this->id.'" name="'.$this->name.'">'.$option1.$option2.'</select>';
			
        } else {
			
            return '<select id="'.$this->id.'" name="'.$this->name.'"></select>';
			
        }
    }

}






