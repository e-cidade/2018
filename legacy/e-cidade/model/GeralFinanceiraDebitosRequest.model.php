<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

/**
 * Classe de captura e processamento da requisiчуo de debitos da CGF
 */
final class GeralFinanceiraDebitosRequest {

  private $sIndex;

  private $sConteudoParcelar;

  private $sNumpres;

  private $sNumpars;

  private $sNumpresString;

  public function __construct(){
    $this->sIndex = 'geralFinanceiraDebitosRequest';
  }

  public function clearDebitos(){
    db_destroysession($this->sIndex);
  }

  public function setDebitos($aDebitos){
    db_putsession($this->sIndex, $aDebitos);
  }

  public function getDebitos(){

    $aDebitos = array();

    if(array_key_exists($this->sIndex, $_SESSION) and !empty($_SESSION[$this->sIndex])){
      $aDebitos = db_getsession($this->sIndex);
    }

    return $aDebitos;
  }

  public function setIndex($sIndex){
    $this->sIndex = $sIndex;
  }

  public function getIndex(){
    return $this->sIndex;
  }

  public function setConteudoParcelar($sConteudoParcelar){
    $this->sConteudoParcelar = $sConteudoParcelar;
  }

  public function getConteudoParcelar(){
    return $this->sConteudoParcelar;
  }

  public function setNumpres($sNumpres){
    $this->sNumpres = $sNumpres;
  }

  public function getNumpres(){
    return $this->sNumpres;
  }

  public function setNumpars($sNumpars){
    $this->sNumpars = $sNumpars;
  }

  public function getNumpars(){
    return $this->sNumpars;
  }

  public function setNumpresString($sNumpresString){
    $this->sNumpresString = $sNumpresString;
  }

  public function getNumpresString(){
    return $this->sNumpresString;
  }

  /**
   * Processa array em sessуo para propriedades utilizadas
   * @param  boolean $lInicial
   * @return GeralFinanceiraDebitosRequest
   */
  public function processDebitosRequest($lInicial = false){

    $aDebitos = $this->getDebitos();

    $sConteudoParcelar = "";
    $sNumpresString    = "";
    $sNumpres          = "";
    $sNumpars          = "";
    $sVirgula          = "";
    $sNumparAux        = "";

    foreach($aDebitos as $i => $oDebito){

      if($oDebito->lChecked){

        $sNumpresString    .= "N".$oDebito->sNumpres;
        $sConteudoParcelar .= "XXX";

        $sAux = "NUMPRE";
        if($lInicial){
          $sAux = "INICIAL";
        }

        $sConteudoParcelar .= $sAux.$oDebito->sNumpres;

        $aNumpres = split("N", $oDebito->sNumpres);

        foreach($aNumpres as $j => $sNumpre){

          if(empty($sNumpre)){
            continue;
          }

          $aNumpre    = split("P", $sNumpre);
          $sNumpreAux = $aNumpre[0];

          $aNumpar    = split("P", strstr($sNumpre, "P"));

          if($lInicial == false){

            $aNumpar    = split("R", $aNumpar[1]);
            $sNumparAux = $aNumpar[0];
          }

          $sNumpres .= $sVirgula.$sNumpreAux;
          $sNumpars .= $sVirgula.$sNumparAux;
          $sVirgula  = ",";
        }
      }
    }

    $this->sConteudoParcelar = $sConteudoParcelar;
    $this->sNumpres          = $sNumpres;
    $this->sNumpars          = $sNumpars;
    $this->sNumpresString    = $sNumpresString;

    return $this;
  }
}

?>