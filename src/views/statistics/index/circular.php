<?php

/* @var $statistic */

use wdmg\widgets\ChartJS;
use yii\helpers\ArrayHelper;

?>

<div class="title-diagramm"><?= $statistic[0]['type'] ?></div>
<?= ChartJS::widget([
    'type' => 'pie',
    'options' => [
        'id' => $statistic[0]['type'],
        'width' => 640,
        'height' => 260
    ],
    'data' => [
        'labels' => array_column($statistic, 'extraType'),
        'datasets' => [
            [
                'data' => array_column($statistic, 'count'),
                'backgroundColor' => ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],
                'borderColor' => [
                    'rgba(54, 162, 235, 1)'
                ],
                'borderWidth' => 1
            ]
        ]
    ]
]);
?>
