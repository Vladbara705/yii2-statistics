<?php

/* @var $statistic */

use wdmg\widgets\ChartJS;
use yii\helpers\ArrayHelper;

$counterUsers = array_column($statistic, 'count');
array_push($counterUsers, 0);

?>

<div class="title-diagramm"><?= $statistic[0]['type'] ?></div>
<?= ChartJS::widget([
    'type' => 'horizontalBar',
    'options' => [
        'id' => $statistic[0]['type'],
        'width' => 640,
        'height' => 260,
        'scales' => [
            'xAxes' => [
                'ticks' => [
                    'beginAtZero' => true
                ]
            ]
        ]
    ],
    'data' => [
        'labels' => array_column($statistic, 'date'),
        'datasets' => [
            [
                'label' => '',
                'data' => $counterUsers,
                'backgroundColor' => [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                'borderColor' => [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                'borderWidth' => 1
            ],
        ]
    ],
]);
?>
