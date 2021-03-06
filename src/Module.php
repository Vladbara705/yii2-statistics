<?php

namespace vladbara705\statistics;

use Yii;
use yii\base\Module as BaseModule;

/**
 * Class Module
 * @package vladbara705\statistics
 */
class Module extends BaseModule
{
    public $controllerNamespace = 'vladbara705\statistics\controllers';
    /**
     * @var string
     */
    public $defaultRoute;

    public function init()
    {
        parent::init();

        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'vladbara705\\' . $this->id . '\commands';
            $this->defaultRoute = 'init';
        }
    }
}
