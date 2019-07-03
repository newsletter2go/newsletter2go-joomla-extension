<?php

/**
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class ModNewsletter2GoHelper
{

    /**
     * Fetches option from database and json_decodes it if flag $decode is true
     *
     * @param string $name
     * @param string $default
     * @param bool|false $decode
     * @return mixed
     */
    public static function getOption($name, $default = '', $decode = false)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`value`');
        $query->from('#__newsletter2go');
        $query->where("`name` = '$name'");
        $db->setQuery($query);

        $object = $db->loadObject();
        $value = $object ? $object->value : $default;

        return $decode === true ? json_decode($value, true) : $value;
    }

}
