<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of Newsletter2Go component
 */
class Com_Newsletter2GoInstallerScript
{
    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent)
    {
        self::setApiKey($this->generateRandomString());
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

    /**
     * @return mixed
     * Update apiKey value in newsletter2go table
     */
    private function setApiKey($apiKey)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update('#__newsletter2go')->set("`value`= '".$apiKey."'")->where("`name`= 'apiKey'");
        $db->setQuery((string) $query);

        return $db->loadObject();
    }

}