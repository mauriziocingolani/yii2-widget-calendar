<?php

namespace mauriziocingolani\calendar;

use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;

class Calendar extends Widget {

    const MODE_MONTH = 'month';
    const MODE_WEEK = 'week';
    const MODE_DAY = 'day';

    public $modes = [self::MODE_MONTH, self::MODE_WEEK, self::MODE_DAY];
    public $mode;
    public $year;
    public $monthOrWeek;
    public $day;
    public $data;

    public function init() {
        CalendarAsset::register($this->view);
        parent::init();
    }

    public function run(): string {
        $this->_checkConfig();
        return $this->render('calendar');
    }

    public function getTitle() {
        return "{titolo}";
    }

    private function _checkConfig() {
        if (is_null($this->mode)) :
            throw new InvalidConfigException(self::class . ': you must set the $mode attribute.');
        elseif (!in_array($this->mode, $this->modes)) :
            throw new InvalidConfigException(self::class . ': you should add the $mode you selected (' . $this->mode . ') in the $modes attribute array.');
        endif;
    }

}
