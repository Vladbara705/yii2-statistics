<?php

namespace vladbara705\statistics\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use \vladbara705\statistics\models\Statistic;

/**
 * Class StatisticsController
 * @package vladbara705\statistics\controllers
 */
class StatisticsController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['basicAuth'] = [
            'class' => \vladbara705\statistics\behaviors\BasicAuth::class
        ];

        return $behaviors;
    }
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }
        return parent::beforeAction($action);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $statistics = Statistic::getAll();
        return $this->render('index', [
            'statistics' => $statistics
        ]);
    }

    /**
     * @return array
     */
    public function actionRemove()
    {
        try {
            $request = Yii::$app->request->post();
            Statistic::deleteAll(['type' => $request['type']]);

            return [
                'success' => true
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'alert' => $e->getMessage()
            ];
        }
    }

    /**
     * @return array
     */
    public function actionShow()
    {
        try {
            $request = Yii::$app->request->get();
            $statistic = Statistic::getStatisticByType($request);
            !empty($statistic) ? $groupedStatistics[current($statistic)['type']] = $statistic : $groupedStatistics = null;

            Yii::$app->assetManager->bundles = [
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\web\JqueryAsset' => false,
                'wdmg\widgets\ChartJSAsset' => false
            ];

            return [
                'success' => true,
                'data' => $this->renderAjax('/statistics/index/directions', [
                    'groupedStatistics' => $groupedStatistics,
                    'type' => $request['type'],
                    'toDate' => $request['toDate'],
                    'fromDate' => $request['fromDate']
                ]),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'alert' => $e->getMessage()
            ];
        }
    }
}
