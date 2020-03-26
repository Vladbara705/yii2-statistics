<?php

namespace vladbara705\statistics\behaviors;

use vladbara705\statistics\models\Statistic;
use yii\base\Behavior;
use yii\web\Controller;
use Yii;

/**
 * Class BasicAuth
 * @package vladbara705\statistics\behaviors
 */
class BasicAuth extends Behavior
{
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
        $params = Statistic::getParams();
        $authentication = $params['authentication'];
        $authData = $params['authData'];
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