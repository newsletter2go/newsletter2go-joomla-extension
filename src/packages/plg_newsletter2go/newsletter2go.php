<?php

defined('_JEXEC') or die;

class plgContentNewsletter2Go extends JPLugin
{

    const DELAY_DEFAULT = 5; //default delay value for popup/modal type

    /**
     * @params $context, $article, &params, $limitstart
     *
     * Short-code syntax:
     * embedded default {newsletter2go}, {newsletter2go type=plugin},
     * modal/pop-up type {newsletter2go type=popup}, {newsletter2go type=popup delay=5}
     */
    function onContentPrepare($context, &$article, &$params, $limitstart)
    {
        $regex		= '/{newsletter2go(.*?)}/i';
        preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

        if ($matches)
        {
            foreach ($matches as $match)
            {
                $n2gParams = NULL;
                $code = NULL;
                $delay = array();
                if (isset($match[1])) {
                    $options = explode(" ", trim($match[1]));
                    $type = explode("=", trim($options[0]));
                    if(isset($options[1])) {
                        $delay = explode("=", trim($options[1]));
                    }
                    $formStyle = self::getOption('configFormStyles');
                    if (isset($type[1]) && $type[1] == 'popup') {
                        if (!empty($delay) && $delay[0] == 'delay'){
                            $n2gParams = '\'subscribe:createPopup\', ' .$formStyle->value. ', '.$delay[1];
                        } else {
                            $n2gParams = '\'subscribe:createPopup\', ' .$formStyle->value. ', '.self::DELAY_DEFAULT;
                        }
                    } else if ((isset($type[1]) && $type[1] == 'plugin') || empty($type[0])){
                        $n2gParams = '\'subscribe:createForm\', ' .$formStyle->value;
                    }
                }

                if (JComponentHelper::isEnabled('com_newsletter2go', true)){
                    $code = self::getOption('formUniqueCode');
                }

                if (isset($code->value) && isset($n2gParams) && !empty($code->value)) {
                    $form = '<script id="n2g_script">
                !function(e,t,n,c,r,a,i){e.Newsletter2GoTrackingObject=r,e[r]=e[r]||function(){(e[r].q=e[r].q||[]).push(arguments)},e[r].l=1*new Date,a=t.createElement(n),i=t.getElementsByTagName(n)[0],a.async=1,a.src=c,i.parentNode.insertBefore(a,i)}(window,document,"script","//static-staging.newsletter2go.com/utils.js","n2g");
                n2g(\'create\',\'' . $code->value . '\');
                n2g(' . $n2gParams . ');
                        </script>';
                } else if (!isset($code)) {
                    $form = "Please install/enable Newsletter2Go component in order to use this plugin.";
                } else if (empty($code->value)) {
                    $form = "Please select form in Newsletter2Go component settings.";
                } else {
                    $form = "Short-code ".$match[0]." isn't valid. Please refer to plugin documentation for valid short-code.";
                }

                $article->text = preg_replace("|$match[0]|", addcslashes($form, '\\$'), $article->text, 1);
            }
        }

    }


    /**
     * @param string name
     * @return mixed
     * Get option from newsletter2go table
     */
    public static function getOption($name)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`value`')->from('#__newsletter2go')->where("`name`= '$name'");
        $db->setQuery((string) $query);

        return $db->loadObject();
    }

}