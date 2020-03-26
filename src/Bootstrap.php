<?php

namespace vladbara705\statistics;

use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package vladbara705\statistics
 */
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

        $app->setModule('statistic', 'vladbara705\statistics\Module');
    }
}
