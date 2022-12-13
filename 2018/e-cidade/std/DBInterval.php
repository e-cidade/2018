<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


/**
 * Cria uma instancia do tipo de dados interval
 * Class DBInterval
 */
class DBInterval {

  private $iYears = 0;

  private $iDays = 0;

  private $iMonth = 0;

  private $sInterval = null;

  protected static $aYearsString = array("year", "ano", "years", "anos");

  protected static $aMonthsString = array("mon", "mons", "month", "months", "meses", "mes");

  protected static $aDaysString = array("day", "days", "dias", "dia");

  public function __construct($sInterval = null) {

    $this->sInterval = trim(strtolower($sInterval));
    if ($this->validate()) {
      $this->createInterval();
    }
  }

  /**
   * Analisa a intervalo passado o cria os dados necessarios
   */
  protected function createInterval() {


    $sString        = $this->sInterval;
    $aPartsInterval = explode(" ", $sString);
    foreach ($aPartsInterval as $iIndex => $sInterval) {

      if (in_array($sInterval, DBInterval::$aYearsString)) {
       $this->setYear($aPartsInterval[$iIndex -1]);
      }

      if (in_array($sInterval, DBInterval::$aMonthsString)) {
        $this->setMonths($aPartsInterval[$iIndex -1]);
      }

      if (in_array($sInterval, DBInterval::$aDaysString)) {
        $this->setDays($aPartsInterval[$iIndex -1]);
      }
    }

  }

  /**
   * Valida se a string passada � um intervalo valido
   * @return bool
   */
  public function validate() {

    $aParts = explode(" ", $this->sInterval);
    return count($aParts) > 1 && $this->hasStringInterval($aParts);

  }

  /**
   * Verifica se o intervalo passado � valido
   * @param $aParts
   * @return bool
   */
  protected function hasStringInterval($aParts) {

    $aValidStrings = array_merge(
                                 DBInterval::$aDaysString,
                                 DBInterval::$aMonthsString,
                                 DBInterval::$aYearsString
                                );
    $lHasStringInverval = false;
    foreach ($aParts as $sPartString) {

      if (is_int($sPartString)) {
        continue;
      }
      $lHasStringInverval  = in_array($sPartString, $aValidStrings);
    }
    return $lHasStringInverval;
  }

  /**
   * Define os anos do intervalo
   * @param $iYears
   */
  public function setYear($iYears) {
    $this->iYears = (int)$iYears;
  }

  /**
   * Retorna os anos do intervalo
   * @return integer
   */
  public function getYears() {
    return $this->iYears;
  }

  /**
   * Define os meses do intevalo
   * @param $iMonths
   */
  public function setMonths($iMonths) {
    $this->iMonth = (int)$iMonths;
  }

  /**
   * Retorna os meses do intervalo
   * @return integer
   */
  public function getMonths() {
    return $this->iMonth;
  }

  /**
   * Define os dias do intervalo
   * @param $iDays
   */
  public function setDays($iDays) {
    $this->iDays = (int)$iDays;
  }

  /**
   * Retorna os dias do intervalo
   * @return integer
   */
  public function getDays() {
    return $this->iDays;
  }

  /**
   * Retorna o Intervalo
   * @return null|string
   */
  public function getInterval() {

    $this->sInterval = $this->getYears(). " years ". $this->getMonths()." mons ".$this->getDays()." days ";
    return $this->sInterval;
  }

  /**
   * Retorna o intervalor em numero de dias
   * @return int
   */
  public function intervalToDays()  {

    $iDiasDoAno = $this->getYears() * 365;
    $iDiasNoMes = DBNumber::truncate($this->getMonths() * 30.41667, 0);
    $iDias      = $this->getDays();
    return $iDiasDoAno + $iDiasNoMes + $iDias;
  }

  /**
   * Verifica se o intervalo � maior que o outro passado
   *
   * @param DBInterval $oIntervalo
   * @return bool
   */
  public function greaterThan(DBInterval $oIntervalo) {

    if ($this->intervalToDays() > $oIntervalo->intervalToDays()) {
      return true;
    }
    return false;
  }
}