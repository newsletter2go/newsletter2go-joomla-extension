<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class Newsletter2GoController extends JControllerLegacy
{

    /**
     * err-number, that should be pulled, whenever credentials are missing
     */
    const ERRNO_PLUGIN_CREDENTIALS_MISSING = 'int-1-404';
    /**
     *err-number, that should be pulled, whenever credentials are wrong
     */
    const ERRNO_PLUGIN_CREDENTIALS_WRONG = 'int-1-403';
    /**
     * err-number for all other (intern) errors. More Details to the failure should be added to error-message
     */
    const ERRNO_PLUGIN_OTHER = 'int-1-600';

    public function display($cacheable = false, $urlparams = [])
    {
        $view = $this->getView('widget', 'html');
        $view->display();
    }


    /**
     * Post export API endpoint
     */
    public function getPost()
    {
        try {
            if (!$this->authenticate()) {
                echo json_encode(['success' => 0, 'message' => 'Authentication failed!']);
                return;
            }

            $id = JFactory::getApplication()->input->getInt('postId');
            $lang = JFactory::getApplication()->input->getString('lang');

            if (!$id || !$lang) {
                echo json_encode(['success' => 0, 'message' => 'Parameters missing!']);
                return;
            }

            $model = $this->getModel('newsletter2go');
            $post = $model->getPost($id, $lang);

            echo json_encode(
                [
                    'success' => $post ? true : false,
                    'message' => $post ? 'OK' : "Post with id '$id' and lang '$lang' not found.",
                    'post' => $post,
                ]
            );
        } catch (\Exception $exc) {
            echo json_encode(['success' => false, 'message' => $exc->getMessage(), 'errorcode' => self::ERRNO_PLUGIN_OTHER]);
        }
    }

    /**
     * Post export API endpoint
     */
    public function getLanguages()
    {
        try {
            if (!$this->authenticate()) {
                echo json_encode(['success' => 0, 'message' => 'Authentication faild!']);
                return;
            }

            $model = $this->getModel('newsletter2go');

            echo json_encode(['success' => 1, 'message' => 'OK!', 'languages' => $model->getLanguages()]);
        } catch (\Exception $exc) {
            echo json_encode(['success' => false, 'message' => $exc->getMessage(), 'errorcode' => self::ERRNO_PLUGIN_OTHER]);
        }
    }

    /**
     * Plugin version API endpoint
     */
    public function getPluginVersion()
    {
        try {
            if (!$this->authenticate()) {
                echo json_encode(['success' => 0, 'message' => 'Authentication faild!']);
                return;
            }

            $model = $this->getModel('newsletter2go');
            $obj = json_decode($model->getExtensionVersion()->manifest_cache);

            echo json_encode(['success' => 1, 'message' => 'OK!', 'version' => str_replace('.', '', $obj->version)]);
        } catch (\Exception $exc) {
            echo json_encode(['success' => false, 'message' => $exc->getMessage(), 'errorcode' => self::ERRNO_PLUGIN_OTHER]);
        }
    }

    /**
     * Test connection API endpoint
     */
    public function test()
    {
        try {
            if (!$this->authenticate()) {
                echo json_encode(['success' => false, 'message' => 'Authentication faild!', 'errorcode' => self::ERRNO_PLUGIN_CREDENTIALS_WRONG]);
                return;
            }

            echo json_encode(['success' => true, 'message' => 'OK!']);
        } catch (\Exception $exc) {
            echo json_encode(['success' => false, 'message' => $exc->getMessage(), 'errorcode' => self::ERRNO_PLUGIN_OTHER]);
        }
    }

    /**
     * Ajax endpoint for subscribing new customer over widget
     *
     * @throws Exception
     */
    public function ajaxSubscribe()
    {
        $noValidEmail = false;
        $notFound = false;
        $post = [];
        $model = $this->getModel('newsletter2go');
        $attributes = $model->getOption('fields','[]', true);
        $texts = $model->getOption('texts', '[]', true);
        $input = JFactory::getApplication()->input;

        foreach ($attributes as $k => $v) {
            $val = $input->getString($k);
            if (!empty($v['required']) && empty($val)) {
                $notFound = true;
                break;
            }

            if ($k == 'email') {
                if (!filter_var($input->getString($k), FILTER_VALIDATE_EMAIL)) {
                    $noValidEmail = true;
                }
            }

            $post[$k] = $input->getString($k);
        }

        if ($notFound) {
            $result = ['success' => 0, 'message' => $texts['failureRequired']];
            echo json_encode($result);
            return;
        }

        if ($noValidEmail) {
            $result = ['success' => 0, 'message' => $texts['failureEmail']];
            echo json_encode($result);
            return;
        }

        $post['key'] = $model->getOption('apiKey');
        $post['doicode'] = $model->getOption('doiCode');
        $response = $this->executeN2Go('recipient', $post);

        $result = ['success' => $response['success']];
        if (!$response) {
            $result['message'] = $texts['failureEmail'];
        } else {
            switch ($response['status']) {
                case 200:
                    $result['message'] = $texts['success'];
                    break;
                case 441:
                    $result['message'] = $texts['failureSubsc'];
                    break;
                case 434:
                case 429:
                    $result['message'] = $texts['failureEmail'];
                    break;
                default:
                    $result['message'] = $texts['failureError'];
                    break;
            }
        }

        echo json_encode($result);
    }

    /**
     * Checks if apiKey is valid
     *
     * @return bool
     * @throws Exception
     */
    private function authenticate()
    {
        $apiKey = JFactory::getApplication()->input->server->getString('PHP_AUTH_USER');
        $apiKey = $apiKey ? $apiKey : JFactory::getApplication()->input->getString('apiKey');

        if (strlen($apiKey) == 0) {
            echo json_encode(['success' => false, 'message' => 'api-key is missing', 'errorcode' => self::ERRNO_PLUGIN_CREDENTIALS_MISSING]);
            exit;
        }

        $model = $this->getModel('newsletter2go');
        return $model->getOption('apiKey') === $apiKey;
    }

    /**
     * Executes call to Newsletter2Go API to create new subscriber
     *
     * @param string $action
     * @param array $post
     * @return mixed
     */
    private function executeN2Go($action, $post)
    {
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, "https://www.newsletter2go.com/en/api/create/$action/");
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
     * Ajax endpoint for subscribing new customer over widget
     *
     * @throws Exception
     */
    public function n2goCallback()
    {
        $model = $this->getModel('newsletter2go');
        $input = JFactory::getApplication()->input;
        $model->setOption('authKey', $input->getString('auth_key').':foo');
        $model->setOption('accessToken', $input->getString('access_token'));
        $model->setOption('refreshToken', $input->getString('refresh_token'));

        $result = ['success' => true];
        echo json_encode($result);
        jexit();

    }
}

