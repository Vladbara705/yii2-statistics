<?php

/* @var $groupedStatistics */
/* @var $type */
/* @var $toDate */
/* @var $fromDate */

?>

<?php if (!empty($groupedStatistics)) { ?>
    <?php foreach ($groupedStatistics as $statistic) { ?>
        <div class="js_statistic_container">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 block-diagramm">
                    <?= $this->render('controls', [
                        'type' => !empty($statistic[0]['type']) ? $statistic[0]['type'] : $type,
                        'toDate' => !empty($statistic[0]['toDate']) ? $statistic[0]['toDate'] : $toDate,
                        'fromDate' => !empty($statistic[0]['fromDate']) ? $statistic[0]['fromDate'] : $fromDate
                    ]); ?>
                    <div class="diagramm">
                        <?php if (!empty(current($statistic)['extraType'])) { ?>
                            <?= $this->render('circular', [
                                'statistic' => $statistic
                            ]); ?>
                        <?php } else { ?>
                            <?= $this->render('columnar', [
                                'statistic' => $statistic
                            ]); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } else { ?>
    <div class="js_statistic_container">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 block-diagramm">
                <?= $this->render('controls', [
                    'type' => $type,
                    'toDate' => $toDate,
                    'fromDate' => $fromDate
                ]); ?>
                <div class="diagramm">
                    <div class="empty-diagramm-text">Статистика отсутствует</div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
