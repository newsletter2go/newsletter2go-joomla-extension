<?php

jimport('joomla.application.component.model');

class Newsletter2GoModelNewsletter2Go extends JModelList
{

    /**
     * Fetches option from database and json_decodes it if flag $decode is true
     *
     * @param string $name
     * @param string $default
     * @param bool|false $decode
     * @return mixed
     */
    public function getOption($name, $default = '', $decode = false)
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

    public function setOption($name, $value)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__newsletter2go')->where("`name` = '$name'");
        $db->setQuery($query);
        $db->execute();

        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        $values = array($db->quote($name), $db->quote($value));
        $columns = array('name', 'value');
        $query->insert($db->quoteName('#__newsletter2go'))
            ->columns($columns)
            ->values(implode(',', $values));
        $db->setQuery($query);
        $db->execute();
    }

    public function getGroups()
    {
        $apiKey = $this->getOption('apiKey');
        return $this->executeN2Go('get/groups', array('key' => $apiKey));
    }

    public function getAttributes()
    {
        $attributes = array();
        $allAttributes = 4;
        $selected = $this->getOption('fields', '[]', true);
        $titles = $this->getOption('titles', '[]', true);
        $apiKey = $this->getOption('apiKey');

        $attributesApi = $this->executeN2Go('get/attributes', array('key' => $apiKey));
        if ($attributesApi['success']) {
            $allAttributes += count($attributesApi['value']);
            foreach ($attributesApi['value'] as $atr) {
                $tmpId = strtolower(str_replace(' ', '', $atr));
                $attributes[] = array(
                    'id' => $tmpId,
                    'label' => $atr,
                    'checked' => isset($selected[$tmpId]) ? 'checked' : '',
                    'sort' => isset($selected[$tmpId]) ? $selected[$tmpId]['sort'] : $allAttributes,
                    'disabled' => '',
                    'title' => isset($titles[$tmpId]) ? $titles[$tmpId] : $atr,
                    'required' => isset($selected[$tmpId]) ? $selected[$tmpId]['required'] : false,
                );
            }
        }

        $attributes[] = array(
            'id' => 'email',
            'label' => 'E-mail',
            'title' => isset($titles['email']) ? $titles['email'] : 'E-mail address',
            'checked' => 'checked',
            'disabled' => 'disabled="true"',
            'sort' => isset($selected['email']) ? $selected['email']['sort'] : $allAttributes,
            'required' => true,
        );
        $attributes[] = array(
            'id' => 'firstname',
            'label' => 'First name',
            'title' => isset($titles['firstname']) ? $titles['firstname'] : 'First name',
            'disabled' => '',
            'checked' => isset($selected['firstname']) ? 'checked' : '',
            'sort' => isset($selected['firstname']) ? $selected['firstname']['sort'] : $allAttributes,
            'required' => isset($selected['firstname']) ? $selected['firstname']['required'] : false,
        );
        $attributes[] = array(
            'id' => 'lastname',
            'label' => 'Last name',
            'title' => isset($titles['lastname']) ? $titles['lastname'] : 'Last name',
            'disabled' => '',
            'checked' => isset($selected['lastname']) ? 'checked' : '',
            'sort' => isset($selected['lastname']) ? $selected['lastname']['sort'] : $allAttributes,
            'required' => isset($selected['lastname']) ? $selected['lastname']['required'] : false,
        );
        $attributes[] = array(
            'id' => 'gender',
            'label' => 'Gender',
            'title' => isset($titles['gender']) ? $titles['gender'] : 'Gender',
            'disabled' => '',
            'checked' => isset($selected['gender']) ? 'checked' : '',
            'sort' => isset($selected['gender']) ? $selected['gender']['sort'] : $allAttributes,
            'required' => isset($selected['gender']) ? $selected['gender']['required'] : false,
        );

        usort($attributes, array('Newsletter2GoModelNewsletter2Go', 'attributesCmp'));

        return $attributes;
    }

    /**
     * @param string $doiCode
     * @return array
     */
    public function checkDoiCode($doiCode = '')
    {
        $result = array(
            'success' => false,
            'host' => '',
        );

        if (strlen($doiCode) > 0) {
            $apiKey = $this->getOption('apiKey');
            $doi = $this->executeN2Go('get/form', array('key' => $apiKey, 'doicode' => $doiCode));
            if ($doi['success']) {
                $code = rawurldecode($doi['value']['code']);
                if (strpos($code, '"' . $doiCode . '"') !== false) {
                    $result['success'] = true;
                    $result['host'] = $doi['value']['host'];
                }
            }
        }

        return $result;
    }

    public static function attributesCmp($a, $b)
    {
        if ($a['sort'] == $b['sort']) {
            return 0;
        }

        return $a['sort'] < $b['sort'] ? -1 : 1;
    }

    /**
     * Creates request and returns response.
     *
     * @param string $action
     * @param $post
     * @return array
     * @internal param mixed $params
     */
    function executeN2Go($action, $post)
    {
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, "https://www.newsletter2go.com/en/api/$action/");
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);

        $postData = '';
        foreach ($post as $k => $v) {
            $postData .= urlencode($k) . '=' . urlencode($v) . '&';
        }
        $postData = substr($postData, 0, -1);

        curl_setopt($cURL, CURLOPT_POST, 1);
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($cURL);
        curl_close($cURL);

        return json_decode($response, true);
    }

}
