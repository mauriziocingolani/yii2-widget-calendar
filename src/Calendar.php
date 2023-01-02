<?php

namespace mauriziocingolani\calendar;

use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap4\Widget;
use mauriziocingolani\yii2fmwkphp\DateTime;

/**
 * Getters
 * @property string $mode
 * 
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version 1.1.1
 */
class Calendar extends Widget {

    const MODE_MONTH = 'month';
    const MODE_WEEK = 'week';
    const MODE_DAY = 'day';

    /** Array di modalità consentite (default: month, week) */
    public $modes = [self::MODE_MONTH, self::MODE_WEEK];

    /** Anno */
    public $year;

    /** Nome del mese (tutto minuscolo) oppure numero della settimana */
    public $monthOrWeek;

    /** Giorno */
    public $day;

    /** Percorso base per gli url delle modalità e dei pulsanti. Se specificato deve avere slash alla fine ma non all'inizio (es. "site/calendario/"). */
    public $route;

    /** Array dei dati. Le chiavi devono essere i giorni (formato 'Y-m-d') e i valori il contenuto html delle celle. */
    public $data;
    private $_mode;

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
        if (!in_array($this->mode, $this->modes)) :
            throw new InvalidConfigException(self::class . ': you should add the $mode you selected (' . $this->mode . ') in the $modes attribute array.');
        elseif (in_array($this->mode, [self::MODE_MONTH, self::MODE_WEEK]) && !($this->year && $this->monthOrWeek)) :
            throw new InvalidConfigException(self::class . ': you must set the $year and $monthOrWeek attributes.');
        elseif ($this->mode == self::MODE_DAY && !($this->year && $this->monthOrWeek && $this->day)) :
            throw new InvalidConfigException(self::class . ': you must set the $year, $monthOrWeek and $day attributes.');
        endif;
    }

    /**
     * Restituisce la modalità in cui attualmente si trova il calendario.
     * @return string Modalità attuale
     */
    public function getMode(): string {
        if (!$this->_mode) :
            if ($this->day > 0) :
                $this->_mode = self::MODE_DAY;
            elseif ((int) $this->monthOrWeek > 0) :
                $this->_mode = self::MODE_WEEK;
            else :
                $this->_mode = self::MODE_MONTH;
            endif;
        endif;
        return $this->_mode;
    }

    /**
     * @return string Url per il pulsante di modalità mese.
     */
    public function getMonthModeUrl(): string {
        if ($this->mode == self::MODE_MONTH) :
            return '';
        elseif ($this->mode == self::MODE_WEEK) :
            # mese della settimana attuale
            $firstDayTime = strtotime("$this->year-W$this->monthOrWeek-1");
            return $this->_getUrlWithParams('/' . $this->route . $this->year . '/' . DateTime::GetMonth((int) date("m", $firstDayTime)));
        elseif ($this->mode == self::MODE_DAY) :
            # mese del giorno attuale
            $dayTime = strtotime("$this->year-$this->monthOrWeek-$this->day");
            return $this->_getUrlWithParams('/' . $this->route . $this->year . '/' . DateTime::GetMonth((int) date("m", $dayTime)));
        endif;
    }

    /**
     * @return string Url per il pulsante di modalità settimana.
     */
    public function getWeekModeUrl(): string {
        if ($this->mode == self::MODE_MONTH) :
            $today = new \DateTime;
            $month = DateTime::GetMonthNumber($this->monthOrWeek);
            if ($today->format('Y') == (int) $this->year && $today->format('m') == $month) : # oggi è nel mese
                return $this->_getUrlWithParams('/' . $this->route . $today->format('Y/W'));
            else : # oggi non è nel mese
                $firstDayTime = strtotime("$this->year-$month-1");
                return $this->_getUrlWithParams('/' . $this->route . date('Y/W', $firstDayTime));
            endif;
        elseif ($this->mode == self::MODE_WEEK) :
            return '';
        elseif ($this->mode == self::MODE_DAY) :
            # settimana del giorno attuale
            $dayTime = strtotime("$this->year-$this->monthOrWeek-$this->day");
            return $this->_getUrlWithParams('/' . $this->route . $this->year . '/' . date('W', $dayTime));
        endif;
    }

    /**
     * @return string Url per il pulsante di modalità giorno.
     */
    public function getDayModeUrl() {
        if ($this->mode == self::MODE_MONTH) :
            # primo giorno del mese
            return $this->_getUrlWithParams("/$this->route$this->year/" . sprintf('%02d', DateTime::GetMonthNumber($this->monthOrWeek)) . "/01");
        elseif ($this->mode == self::MODE_WEEK) :
            # primo giorno della settimana
            $firstDayTime = strtotime("$this->year-W$this->monthOrWeek-1");
            return $this->_getUrlWithParams("/$this->route" . date('Y/m/d', $firstDayTime));
        elseif ($this->mode == self::MODE_DAY) :
            return '';
        endif;
    }

    /**
     * @return string Url del pulsante "precedente"
     */
    public function getPreviousButtonUrl(): string {
        if ($this->mode == self::MODE_MONTH) :
            $prev = new \DateTime($this->year . '-' . DateTime::GetMonthNumber($this->monthOrWeek) . '-1');
            $prev->modify('-1 month');
            return $this->_getUrlWithParams('/' . $this->route . $prev->format('Y') . '/' . DateTime::GetMonth((int) $prev->format('m')));
        elseif ($this->mode == self::MODE_WEEK) :
            $prev = new \DateTime("$this->year-W$this->monthOrWeek-1");
            $prev->modify('-1 week');
            return $this->_getUrlWithParams('/' . $this->route . $prev->format('Y/W'));
        elseif ($this->mode == self::MODE_DAY) :
            $prev = new \DateTime("$this->year-$this->monthOrWeek-$this->day");
            $prev->modify('-1 day');
            return $this->_getUrlWithParams('/' . $this->route . $prev->format('Y/m/d'));
        endif;
    }

    /**
     * @return string Title del pulsante "precedente"
     */
    public function getPreviousButtonTitle(): string {
        if ($this->mode == self::MODE_MONTH) :
            return 'Vai al mese precedente';
        elseif ($this->mode == self::MODE_WEEK) :
            return 'Vai alla settimana precedente';
        elseif ($this->mode == self::MODE_DAY) :
            return 'Vai al giorno successivo';
        endif;
    }

    /**
     * @return string Url del pulsante "oggi".
     */
    public function getTodayButtonUrl(): string {
        if ($this->mode == self::MODE_MONTH) :
            return $this->_getUrlWithParams('/' . $this->route . date('Y') . '/' . DateTime::GetMonth((int) date('m')));
        elseif ($this->mode == self::MODE_WEEK) :
            # Attenzione! Usare anno ISO 8601, altrimenti nell'ultima settimana dell'anno possono esserci problemi
            return $this->_getUrlWithParams('/' . $this->route . date('o/W'));
        elseif ($this->mode == self::MODE_DAY) :
            return $this->_getUrlWithParams('/' . $this->route . date('Y/m/d'));
        endif;
    }

    /**
     * @return string Url del pulsante "prossimo"
     */
    public function getNextButtonUrl(): string {
        if ($this->mode == self::MODE_MONTH) :
            $next = new \DateTime($this->year . '-' . DateTime::GetMonthNumber($this->monthOrWeek) . '-1');
            $next->modify('+1 month');
            return $this->_getUrlWithParams('/' . $this->route . $next->format('Y') . '/' . DateTime::GetMonth((int) $next->format('m')));
        elseif ($this->mode == self::MODE_WEEK) :
            $next = new \DateTime("$this->year-W$this->monthOrWeek-1");
            $next->modify('+1 week');
            return $this->_getUrlWithParams('/' . $this->route . $next->format('Y/W'));
        elseif ($this->mode == self::MODE_DAY) :
            $next = new \DateTime("$this->year-$this->monthOrWeek-$this->day");
            $next->modify('+1 day');
            return $this->_getUrlWithParams('/' . $this->route . $next->format('Y/m/d'));
        endif;
    }

    /**
     * @return string Title del pulsante "prossimo"
     */
    public function getNextButtonTitle(): string {
        if ($this->mode == self::MODE_MONTH) :
            return 'Vai al mese successivo';
        elseif ($this->mode == self::MODE_WEEK) :
            return 'Vai alla settimana successiva';
        elseif ($this->mode == self::MODE_DAY) :
            return 'Vai al giorno successivo';
        endif;
    }

    /**
     * @return array Oggetti \DateTime che rappresentano i giorni da mostrare sul calendario.
     */
    public function getDays(): array {
        if ($this->mode == self::MODE_MONTH) :
            $days = [];
            $m = DateTime::GetMonthNumber($this->monthOrWeek);
            $firstday = new \DateTime("$this->year-$m-1"); # primo giorno del mese
            $dayInWeek = (int) $firstday->format('N'); # posizione del primo giorno del mese (1 per lunedì)
            if ($dayInWeek > 1) # se la settimana non inizia di lunedì sottraggo i giorni per arrivare al vero giorno
                $firstday->sub(new \DateInterval("P" . ($dayInWeek - 1) . "D"));
            $firstday = $firstday->modify("-1 days");
            $weekIteration = 0;
            do {
                for ($i = 0; $i < 7; $i++) :
                    $firstday = $firstday->modify("+1 days");
                    # controllo per il caso in cui l'ultimo giorno del mese è anche l'ultimo della settimana
                    if ($weekIteration > 0 && $i == 0 && $firstday->format('m') != $m)
                        break 2;
                    $days[$firstday->format('W')][] = clone $firstday;
                endfor;
                $weekIteration++;
            } while ($firstday->format('m') == $m);
            return $days;
        elseif ($this->mode == self::MODE_WEEK) :
            $first = new \DateTime("$this->year-W$this->monthOrWeek-1");
            $days = [$first];
            for ($i = 1; $i <= 6; $i++) :
                $d = clone $first;
                $days[] = $d->modify("+$i day");
            endfor;
            return $days;
        elseif ($this->mode == self::MODE_DAY) :
            return [new \DateTime("$this->year/$this->monthOrWeek/$this->day")];
        endif;
    }

    private function _getUrlWithParams($url) {
        if (Yii::$app->request->isGet && count($_REQUEST) > 0) :
            return $url . '?' . http_build_query($_REQUEST);
        endif;
        return $url;
    }

}
