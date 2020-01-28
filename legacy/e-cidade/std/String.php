<?php
/**
 * Class String
 */
class String {

  /**
   * @type string
   */
  private $sValue = "";

  /**
   * @param string $sValor
   */
  public function __construct($sValor) {
    $this->sValue = (string)$sValor;
  }

  /**
   * @param string $sTarget - Procurar por
   * @param string $sValue - Alterar por
   * @return $this
   */
  public function replace($sTarget, $sValue) {
    $this->sValue = str_replace($sTarget, $sValue, $this->sValue);
    return $this;
  }

  /**
   * @return $this
   */
  public function trim() {
    $this->sValue = trim($this->sValue);
    return $this;
  }

  /**
   * @return $this
   */
  public function rTrim() {
    $this->sValue = rtrim($this->sValue);
    return $this;
  }

  /**
   * @return $this
   */
  public function lTrim() {
    $this->sValue = ltrim($this->sValue);
    return $this;
  }

  /**
   * @param $sString
   * @return $this
   */
  public function concatenate($sString) {

    $this->sValue .= $sString;
    return $this;
  }

  /**
   * @param     $sSearch
   * @param int $iOffSet
   * @return bool
   */
  public function contains($sSearch, $iOffSet = 0) {

    $iPosition = strpos($this->sValue, $sSearch, $iOffSet);
    return ($iPosition !== false);
  }

  /**
   * @return $this
   */
  public function toUpperCase() {
    $this->sValue = mb_strtoupper($this->sValue);
    return $this;
  }

  /**
   * @return $this
   */
  public function toLowerCase() {
    $this->sValue = mb_strtolower($this->sValue);
    return $this;
  }

  /**
   * @param int  $iStart
   * @param null $iLength
   *
   * @return String
   */
  public function subString($iStart, $iLength = null) {

    $sNewString = empty($iLength) ? substr($this->sValue, $iStart) : substr($this->sValue, $iStart, $iLength);
    if (!$sNewString) {
      return "";
    }
    return new String($sNewString);
  }

  /**
   * @param $sLocale
   * @param $iLimit
   * @return String[]
   */
  public function explode($sLocale, $iLimit = null) {

    $aExplode = empty($iLimit) ? explode($sLocale, $this->sValue) : explode($sLocale, $this->sValue, $iLimit);
    $aReturn  = array();
    foreach ($aExplode as $sString) {
      array_push($aReturn, new String($sString));
    }
    return $aReturn;
  }

  /**
   * @return bool
   */
  public function isEmpty() {
    return $this->length() == 0;
  }

  /**
   * @return int
   */
  public function length() {
    return strlen($this->sValue);
  }

  /**
   * @return string
   */
  public function __toString() {
    return $this->sValue;
  }
}