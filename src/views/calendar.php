<?php

use mauriziocingolani\calendar\Calendar;
use mauriziocingolani\yii2fmwkphp\Html;

/* @var $this \mauriziocingolani\yii2fmwkphp\View */
/* @var $calendar Calendar */
$calendar = $this->context;
?>

<div class="row mb-3">

    <!-- MODE -->
    <div class="calendar-mode col-sm-4 text-center">
        <div class="btn-group" role="group" aria-label="...">
            <?php if (array_search(Calendar::MODE_MONTH, $calendar->modes) !== false) : ?>
                <a href="/" class="btn btn-default btn-xs <?= $calendar->mode == Calendar::MODE_MONTH ? 'btn-primary' : null; ?>">Mese</a>
            <?php endif; ?>
            <?php if (array_search(Calendar::MODE_WEEK, $calendar->modes) !== false) : ?>
                <a href="/" class="btn btn-default btn-xs <?= $calendar->mode == Calendar::MODE_WEEK ? 'btn-primary' : null; ?>">Settimana</a>
            <?php endif; ?>
            <?php if (array_search(Calendar::MODE_DAY, $calendar->modes) !== false) : ?>
                <a href="/" class="btn btn-default btn-xs <?= $calendar->mode == Calendar::MODE_DAY ? 'btn-primary' : null; ?>">Giorno</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- TITLE -->
    <div class="calendar-title col-sm-4 text-center">
        <?php
        if ($calendar->mode == Calendar::MODE_MONTH) :
            echo Html::tag('h4', "$calendar->monthOrWeek $calendar->year");
        elseif ($calendar->mode == Calendar::MODE_WEEK) :
            echo Html::tag('h4', "settimana $calendar->monthOrWeek del $calendar->year");
        endif;
        ?>
    </div>

    <!-- CONTROLS -->
    <div class="calendar-controls col-sm-4 text-center">
        <div class="btn-group" role="group" aria-label="...">
            <?=
            Html::fasa('step-backward', null, ["/"], [
                'class' => 'btn btn-xs btn-default',
                'title' => '...',
            ]) .
            Html::a('Oggi', ["/"], [
                'class' => 'btn btn-xs btn-default',
            ]) .
            Html::fasa('step-forward', null, ["/"], [
                'class' => 'btn btn-xs btn-default',
                'title' => '...',
            ]);
            ?>
        </div>
    </div>

</div>

<div class="overflow-auto">
    <table class="calendar mb-3">

    </table>
</div>