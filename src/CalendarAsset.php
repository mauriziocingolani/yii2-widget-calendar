<?php

namespace mauriziocingolani\calendar;

use yii\web\AssetBundle;

/**
 * Asset bundle per il calendario.
 * 
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0
 */
class CalendarAsset extends AssetBundle {

    public function init() {
        $this->sourcePath = __DIR__ . '/assets';
        $this->css = ['css/mauriziocingolani-calendar.css'];
        $this->depends = [
            'yii\web\YiiAsset',
            'yii\bootstrap4\BootstrapPluginAsset',
            '\rmrevin\yii\fontawesome\AssetBundle',
        ];
        parent::init();
    }

}
