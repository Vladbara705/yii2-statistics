<?php

namespace vladbara705\statistic;

use Yii;
use yii\base\Module as BaseModule;

/**
 * Class Module
 * @package vladbara705\statistic
 */
class Module extends BaseModule
{
    public $controllerNamespace = 'vladbara705\statistic\controllers';

    public function init()
    {
        parent::init();

        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'vladbara705\\' . $this->id . '\commands';
            $this->defaultRoute = 'init';
        }
    }
}