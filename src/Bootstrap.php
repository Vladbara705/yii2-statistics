<?php

namespace vladbara705\statistic;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        $statisticsRoute = isset($app->params['statistics']['statisticsRoute']) ? $app->params['statistics']['statisticsRoute'] : 'statistics';

        $app->getUrlManager()->addRules([
            $statisticsRoute => 'statistic/statistics/index',
            'statistics/remove' => 'statistic/statistics/remove',
            'statistics/show' => 'statistic/statistics/show',
        ], false);

        $app->setModule('statistic', 'vladbara705\statistic\Module');
    }
}
