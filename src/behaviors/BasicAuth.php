<?php

namespace vladbara705\statistic\behaviors;

use yii\base\Behavior;
use yii\web\Controller;
use Yii;

/**
 * Class BasicAuth
 * @package vladbara705\statistic\behaviors
 */
class BasicAuth extends Behavior
{
    private $params;

    /**
     * BasicAuth constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->params = isset(Yii::$app->params['statistics']) ? Yii::$app->params['statistics'] : null;
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION  => 'onBeforeAction',
        ];
    }

    /**
     * @return bool
     */
    public function onBeforeAction()
    {
        $authentication = isset($this->params['authentication']) ? $this->params['authentication'] : false;
        $authData = isset($this->params['authData']) ? $this->params['authData'] : null;
        if (empty($authentication)) {
            return true;
        } elseif (!empty($authentication) && empty($authData)) {
            return true;
        }
        
        $validUser = array_keys($authData);

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm=""');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Авторизация отменена';
            exit;
        } else {
            $user = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            $validated = (in_array($user, $validUser)) && ($pass == $authData[$user]);
            if (!$validated) {
                header('WWW-Authenticate: Basic realm=""');
                header('HTTP/1.0 401 Unauthorized');
                echo 'Пара логин/пароль указана не верно';
                exit;
            }
        }
    }
}