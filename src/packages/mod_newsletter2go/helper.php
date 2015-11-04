<?php

defined('_JEXEC') or die;

class ModNewsletter2GoHelper
{

    public static function getWidget()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`value`')->from('#__newsletter2go')->where("`name`= 'widget'");
        $db->setQuery((string) $query);

        return $db->loadObject();
    }

}
