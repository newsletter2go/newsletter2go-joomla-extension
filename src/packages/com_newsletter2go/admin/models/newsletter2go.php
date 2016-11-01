<?php

jimport('joomla.application.component.model');

class Newsletter2GoModelNewsletter2Go extends JModelList
{

    const N2GO_API_URL = 'https://api-staging.newsletter2go.com/';
    const N2GO_REFRESH_GRANT_TYPE = 'https://nl2go.com/jwt_refresh';

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

    /**
     * @return boolean|array
     */
    public function getForms()
    {
        $authKey = $this->getOption('authKey');

        $result = false;

        if (strlen($authKey) > 0) {
            $form = $this->execute('forms/all?_expand=1', array());
            if (isset($form['status']) && $form['status'] >= 200 && $form['status'] < 300) {
                $result = array();
                foreach ($form['value'] as $value){
                    $key = $value['hash'];
                    $result[$key]['name'] = $value['name'];
                    $result[$key]['hash'] = $value['hash'];
                    $result[$key]['type_subscribe'] = $value['type_subscribe'];
                    $result[$key]['type_unsubscribe'] = $value['type_unsubscribe'];
                }
            }
        }

        return $result;
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

    /**
     * Creates request and returns response. New API and access token
     *
     * @param string $action
     * @param $post
     * @return array
     * @internal param mixed $params
     */
    private function execute($action, $post)
    {
        $access_token = $this->getOption('accessToken');
        $responseJson = $this->executeRequest($action, $access_token, $post);

        //access_token is deprecated
        if ($responseJson['status_code'] == 403 || $responseJson['status_code'] == 401 ){

            $this->refreshTokens();
            $access_token = $this->getOption('accessToken');
            $responseJson = $this->executeRequest($action, $access_token, $post);
        }

        return $responseJson;

    }

    private function executeRequest($action, $access_token, $post){

        $apiUrl = self::N2GO_API_URL;

        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $apiUrl.$action);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$access_token));

        if(!empty($post)) {
            $postData = '';
            foreach ($post as $k => $v) {
                $postData .= urlencode($k) . '=' . urlencode($v) . '&';
            }
            $postData = substr($postData, 0, -1);

            curl_setopt($cURL, CURLOPT_POST, 1);
            curl_setopt($cURL, CURLOPT_POSTFIELDS, $postData);
        }
        
        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($cURL);
        $response = json_decode($response, true);
        $status = curl_getinfo($cURL);
        $response['status_code'] = $status['http_code'];

        curl_close($cURL);

        return $response;

    }

    /**
     * Get plugin version from extensions table
     *
     * @return string
     */
    public function getVersion()
    {
        $default = array('version' => '3.0.00');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`manifest_cache`');
        $query->from('#__extensions');
        $query->where("`element` = 'com_newsletter2go'");
        $db->setQuery($query);

        $object = $db->loadObject();

        $value = $object ? $object->manifest_cache : json_encode($default);
        $manifest = json_decode($value, true);

        return str_replace('.', '', $manifest['version']);
    }

    /**
     * Creates request and returns response, refresh access token
     *
     * @return bool
     */
    function refreshTokens() {

        $authKey = $this->getOption('authKey');
        $auth = base64_encode($authKey);
        $refreshToken = $this->getOption('refreshToken');
        $refreshPost = array(
            'refresh_token' => $refreshToken,
            'grant_type' => self::N2GO_REFRESH_GRANT_TYPE
        );
        $post = http_build_query($refreshPost);

        $url = self::N2GO_API_URL.'oauth/v2/token';

        $header = array('Authorization: Basic '.$auth, 'Content-Type: application/x-www-form-urlencoded');

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $json_response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($json_response);

        if(isset($response->access_token) && !empty($response->access_token)){
            $this->setOption('accessToken', $response->access_token);
        }
        if(isset($response->refresh_token) && !empty($response->refresh_token)) {
            $this->setOption('refreshToken', $response->refresh_token);
        }

        return true;
    }

}
