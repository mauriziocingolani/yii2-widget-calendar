<?php

namespace mauriziocingolani\calendar;

use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;
use mauriziocingolani\yii2fmwkphp\DateTime;

/**
 * Getters
 * @property string $mode
 */
class Calendar extends Widget {

    const MODE_MONTH = 'month';
    const MODE_WEEK = 'week';
    const MODE_DAY = 'day'; # non implementato al momento

    /** Array di modalità consentite (default: month, week) */
    public $modes = [self::MODE_MONTH, self::MODE_WEEK];

    /** Anno */
    public $year;

    /** Nome del mese (tutto minuscolo) oppure numero della settimana */
    public $monthOrWeek;

    /** Percorso base per gli url delle modalità e dei pulsanti. Se specificato deve avere slash alla fine ma non all'inizio (es. "site/calendario/"). */
    public $route;

    /** Array dei dati. Le chiavi devono essere i giorni (formato 'Y-m-d') e i valori il contenuto html delle celle. */
    public $data;

    /**
     * Inizializza il widget pubblicando gli asset.
     */
    public function init() {
        CalendarAsset::register($this->view);
        parent::init();
    }

    /**
     * Esegue il render del widget dopo aver verificato che la configurazione sia corretta.
     */
    public function run(): string {
        $this->_checkConfig();
        return $this->render('calendar');
    }

    /**
     * Verifica che la configurazione sia corretta. Anomalie attuali:
     * <ul>
     * <li>impostata la modalità giorno (non ancora suportata);</li>
     * <li>modalità non prevista nella proprietà $modes;</li>
     * <li>paramentri $year e $monthOrWeek non impostati in modalità mese o settimana.</li>
     * </ul>
     * @throws InvalidConfigException 
     */
    private function _checkConfig() {
        if ($this->mode == self::MODE_DAY) :
            throw new InvalidConfigException(self::class . ': "day" mode not supported yet.');
        elseif (!in_array($this->mode, $this->modes)) :
            throw new InvalidConfigException(self::class . ': you should add the $mode you selected (' . $this->mode . ') in the $modes attribute array.');
        elseif (in_array($this->mode, [self::MODE_MONTH, self::MODE_WEEK]) && !($this->year && $this->monthOrWeek)) :
            throw new InvalidConfigException(self::class . ': you must set the $year and $monthOrWeek attributes.');
        endif;
    }

    /**
     * Restituisce la modalità in cui attualmente si trova il calendario.
     * @return string Modalità attuale
     */
    public function getMode() {
        if ((int) $this->monthOrWeek > 0)
            return self::MODE_WEEK;
        return self::MODE_MONTH;
    }

    /**
     * @return string Url per il pulsante di modalità mese.
     */
    public function getMonthModeUrl() {
        if ($this->mode == self::MODE_MONTH) :
            return null;
        elseif ($this->mode == self::MODE_WEEK) :
            # mese della settimana attuale
            $firstDayTime = strtotime("$this->year-W$this->monthOrWeek-1");
            return '/' . $this->route . $this->year . '/' . DateTime::GetMonth((int) date("m", $firstDayTime));
        endif;
    }

    /**
     * @return string Url per il pulsante di modalità settimana.
     */
    public function getWeekModeUrl() {
        if ($this->mode == self::MODE_MONTH) :
            $today = new \DateTime;
            $month = DateTime::GetMonthNumber($this->monthOrWeek);
            if ($today->format('Y') == (int) $this->year && $today->format('m') == $month) : # oggi è nel mese
                return '/' . $this->route . $today->format('Y/W');
            else : # oggi non è nel mese
                $firstDayTime = strtotime("$this->year-$month-1");
                return '/' . $this->route . date('Y/W', $firstDayTime);
            endif;
        elseif ($this->mode == self::MODE_WEEK) :
            return null;
        endif;
    }

}
