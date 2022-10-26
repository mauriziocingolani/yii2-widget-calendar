<?php

use mauriziocingolani\calendar\Calendar;
use mauriziocingolani\yii2fmwkphp\{
    DateTime,
    Html
};

/* @var $this \mauriziocingolani\yii2fmwkphp\View */
/* @var $calendar Calendar */
$calendar = $this->context;
?>

<div class="row mb-3">

    <!-- MODE -->
    <div class="calendar-mode col-sm-4 text-center">
        <div class="btn-group" role="group" aria-label="...">
            <?php if (array_search(Calendar::MODE_MONTH, $calendar->modes) !== false) : ?>
                <a href="<?= $calendar->getMonthModeUrl(); ?>" class="btn btn-default btn-xs <?= $calendar->mode == Calendar::MODE_MONTH ? 'btn-primary disabled' : null; ?>">Mese</a>
                <?php
            endif;
            if (array_search(Calendar::MODE_WEEK, $calendar->modes) !== false) :
                ?>
                <a href="<?= $calendar->getWeekModeUrl(); ?>" class="btn btn-default btn-xs <?= $calendar->mode == Calendar::MODE_WEEK ? 'btn-primary disabled' : null; ?>">Settimana</a>
                <?php
            endif;
            if (array_search(Calendar::MODE_DAY, $calendar->modes) !== false) :
                ?>
                <a href="<?= $calendar->getDayModeUrl(); ?>" class="btn btn-default btn-xs <?= $calendar->mode == Calendar::MODE_DAY ? 'btn-primary disabled' : null; ?>">Giorno</a>
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
        elseif ($calendar->mode == Calendar::MODE_DAY) :
            echo Html::tag('h4', "giorno $calendar->day/$calendar->monthOrWeek/$calendar->year");
        endif;
        ?>
    </div>

    <!-- CONTROLS -->
    <div class="calendar-controls col-sm-4 text-center">
        <div class="btn-group" role="group" aria-label="...">
            <?=
            Html::fasa('step-backward', null, [$calendar->getPreviousButtonUrl()], [
                'class' => 'btn btn-xs btn-default',
                'title' => $calendar->getPreviousButtonTitle(),
            ]) .
            Html::a('Oggi', [$calendar->getTodayButtonUrl()], [
                'class' => 'btn btn-xs btn-default',
            ]) .
            Html::fasa('step-forward', null, [$calendar->getNextButtonUrl()], [
                'class' => 'btn btn-xs btn-default',
                'title' => $calendar->getNextButtonTitle(),
            ]);
            ?>
        </div>
    </div>

</div>

<div class="overflow-auto">
    <table class="calendar <?= $calendar->mode; ?> mb-3">

        <?php $days = $calendar->getDays(); ?>

        <?php if ($calendar->mode == Calendar::MODE_MONTH) : ?>

            <!-- MONTH TABLE -->
            <?php foreach ($days as $week => $wds) : $m = DateTime::GetMonthNumber($calendar->monthOrWeek); ?>
                <tr>
                    <td class="week" rowspan="2">w <?= $week; ?></td>
                    <?php foreach ($wds as $day) : $inMonth = $day->format('m') == $m; ?>
                        <td class="day <?= $inMonth ? null : 'not-in-month'; ?>">
                            <?= $inMonth ? DateTime::GetDay($day->format('N')) . ' <strong>' . $day->format('d') . '</strong>' : $day->format('d/m/Y'); ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php foreach ($wds as $day) : $inMonth = (int) $day->format('m') == (int) $m; ?>
                        <td class="eventi <?= $inMonth ? null : 'not-in-month'; ?>" style="vertical-align: top;">
                            <div>
                                <!-- DATA -->
                                <?= $calendar->data[$day->format('Y-m-d')] ?? null; ?>
                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

        <?php elseif (in_array($calendar->mode, [Calendar::MODE_WEEK, Calendar::MODE_DAY])) : ?>

            <!-- WEEK and DAY TABLE -->
            <tr>
                <?php foreach ($days as $day) : ?>
                    <td class="day d-table-cell d-sm-none">
                        <?= DateTime::GetDay($day->format('N'), true) . ' <strong>' . $day->format('d') . '</strong> ' . DateTime::GetMonth($day->format('m'), true); ?>
                    </td>
                    <td class="day d-none d-sm-table-cell">
                        <?= DateTime::GetDay($day->format('N')) . ' <strong>' . $day->format('d') . '</strong> ' . DateTime::GetMonth($day->format('m')); ?>
                    </td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <?php foreach ($days as $day) : ?>
                    <td class="eventi" style="vertical-align: top;">
                        <div>
                            <!-- DATA -->
                            <?= $calendar->data[$day->format('Y-m-d')] ?? null; ?>
                        </div>
                    </td>
                <?php endforeach; ?>
            </tr>

        <?php endif; ?>

    </table>
</div>