<?php

namespace mauriziocingolani\calendar;

use yii\web\AssetBundle;

class CalendarAsset extends AssetBundle {

    public function init() {
        $this->sourcePath = __DIR__ . '/assets';
        $this->css = ['css/mauriziocingolani-calendar.css'];
        $this->depends = [
            'yii\web\YiiAsset',
            'app\assets\Bootstrap4',
            'rmrevin\yii\fontawesome\AssetBundle',
        ];
        parent::init();
    }

}
