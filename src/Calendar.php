<?php

namespace mauriziocingolani\calendar;

use yii\bootstrap\Widget;

class Calendar extends Widget {

    public function init() {
        CalendarAsset::register($this->view);
        parent::init();
    }

    public function run(): string {
        return $this->render('calendar');
    }

}
