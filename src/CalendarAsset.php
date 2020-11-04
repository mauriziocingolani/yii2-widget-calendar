<?php

namespace mauriziocingolani\calendar;

use yii\web\AssetBundle;

class CalendarAsset extends AssetBundle {

    public function init() {
        $this->sourcePath = __DIR__ . '/assets';
        $this->css = ['css/mauriziocingolani-calendar.css'];
        parent::init();
    }

}
