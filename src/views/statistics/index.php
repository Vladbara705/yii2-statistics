<?php

/* @var $statistics */

\vladbara705\statistics\AssetsBundle::register($this);

$this->params['breadcrumbs'][] = 'Статистика';
$this->params['breadcrumbs'][] = $this->title;

array_walk($statistics, function ($item, $key) use (&$groupedStatistics) {
    $groupedStatistics[$item['type']][] = $item;
});

?>

<?php if (!empty($groupedStatistics)) { ?>
<div class="container-fluid no-padding">
    <div class="row">
            <?= $this->render('index/directions', [
                'groupedStatistics' => $groupedStatistics
            ]); ?>
    </div>
</div>
<?php } ?>