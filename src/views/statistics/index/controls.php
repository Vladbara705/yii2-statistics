<?php

/* @var $type */
/* @var $toDate */
/* @var $fromDate */

use wdmg\widgets\DatePicker;

?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding button-control">
    <form action="/statistics/show" method="get" class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-offset-2 no-padding js_show_statistics">
        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 form-group no-padding">
            <input class="form-control custom-input js_datepicker" name="toDate" value="<?= $toDate ?>" placeholder="Начальная дата">
        </div>
        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 form-group no-padding form-input">
            <input class="form-control custom-input js_datepicker" name="fromDate" value="<?= $fromDate ?>" placeholder="Конечная дата">
        </div>
        <input class="hidden" name="type" value="<?= $type ?>" placeholder="Конечная дата">
        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span></button>
    </form>
    <div class="controls">
        <a href="#" class="btn btn-danger btn-sm js_remove" data-type="<?= $type ?>">
            <span class="glyphicon glyphicon-trash"></span>
        </a>
    </div>
</div>
