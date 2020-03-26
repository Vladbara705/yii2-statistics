<?php

namespace vladbara705\statistic;

use yii\web\AssetBundle;

class AssetsBundle extends AssetBundle
{
    /** @var string  */
    public $sourcePath = '@vendor/vladbara705/yii2-statistic/assets';

    /** @var array  */
    public $css = [
        'css/index.css',
        'css/controls.css',
        "css/bootstrap-datepicker.css",
    ];
    public $js = [
        'js/components/ajax.js',
        'js/statistics.js',
        'js/vendor/bootstrap-datapicker/bootstrap-datepicker.js',
        "js/vendor/bootstrap-datapicker/i18n/ru.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
