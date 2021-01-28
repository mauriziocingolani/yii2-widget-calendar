<?php

namespace mauriziocingolani\calendar;

use yii\web\AssetBundle;

/**
 * Asset bundle per il calendario.
 * Di default viene utilizzata la versione 4.x di Boostrap, 
 * a meno di diversa indicazione tramite il metodo SetBsVersion.
 * 
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.0.1
 */
class CalendarAsset extends AssetBundle {

    private static $_bsVersion = '4';

    public function init() {
        $this->sourcePath = __DIR__ . '/assets';
        $this->css = ['css/mauriziocingolani-calendar.css'];
        $this->depends = [
            'yii\web\YiiAsset',
            self::$_bsVersion[0] == '4' ? 'yii\bootstrap4\BootstrapPluginAsset' : 'yii\bootstrap\BootstrapPluginAsset',
            '\rmrevin\yii\fontawesome\AssetBundle',
        ];
        parent::init();
    }

    /**
     * Imposta la versione di Boostrap di cui verrà caricato l'Asset.
     * Viene preso in considerazione soltanto il primo carattere della stringa che deve indicare la major release (3, 4, ...).
     * @param string $version Versione Bootstrap (3.x, 4.x, ...)
     */
    public static function SetBsVersion($version) {
        self::$_bsVersion = ($version ? (string) $version : '4')[0];
    }

}
