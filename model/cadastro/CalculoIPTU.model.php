<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

class CalculoIPTU {
  //iptucalc
  
  /**
   * Ano do cálculo 
   * @var integer
   */
  private $iAnoUsu;
  
  /**
  * Matrícula do cálculo
  * @var integer
  */
  private $iMatricula;
  
  /**
  * Testada gerada para o cálculo
  * @var number
  */
  private $nTestada;
  
  /**
  *
  * Área calculada
  * @var number
  */
  private $nAreaCalculo;
  
  /**
   * Área fracionada do calculo
   * @var number
   */
  private $nAreaFracionada;
  
  /**
   * 
   * Área de construção
   * @var number
   */
  private $nAreaEdificada;
  
  /**
   * Valor m2 
   * @var number
   */
  private $nValorM2Terreno;
  
  /**
   * Valor venal do terreno
   * @var number
   */
  private $nValorVenalTerreno;
  
  /**
   * Valor alíquota
   * @var number
   */
  private $nAliquota;
  
  /**
   * Valor isencao
   * @var number
   */
  private $nValorIsencao;
  
  /**
   * Tipo de imposto
   * @var string
   */
  private $sTipoImposto;
  
  /**
   * Manual
   * @var string
   */
  private $sManual;

  /**
   * Tipo de calculo
   * @var integer
   */
  private $iTipoCalculo;
  
  //iptucalv
  /**
   * Codigo da receita do calculo
   * @var integer
   */
  private $iCodigoReceita;
  
  /**
   * Valor da receita do calculo
   * @var number
   */
  private $nValorReceita;
  
  /**
   * Quantidade
   * @var integer
   */
  private $iQuantidade;
  
  /**
   * Código histórico da receita
   * @var integer
   */
  private $iCodigoHistorico;
  
  //iptunump
  
  private $iNumpre;
  
  private $aConstrucoes = array();
  
  public function __construct($iMatricula, $iAnousu) {
    
    if (empty($iMatricula)) {
      throw new Exception('Erro [1] - Matrícula não informada para o cálculo');
    }

    if (empty($iAnousu)) {
      throw new Exception('Erro [2] - Ano da seção não informado para o cálculo');
    }    
    
    $this->setMatricula($iMatricula);
    
    $this->setAnousu($iAnousu);
    
  }
  
  public function getCalculoReceita() {
    
    $oDaoIptuCalv = db_utils::getDao('iptucalv');
    
    $sWhere       = "j21_anousu = {$this->iAnousu} and j21_matric = {$this->iMatricula} and j21_codhis = 1";
    
    $sSqlIptuCalv = $oDaoIptuCalv->sql_query_file(null, '*', null, $sWhere);
    
    $rsIptuCalv   = $oDaoIptuCalv->sql_record($sSqlIptuCalv);
    
    
    return db_utils::getCollectionByRecord($rsIptuCalv);
    
  }
  
  public function getCalculoValorIptu() {

    $oDaoIptuCalc = db_utils::getDao('iptucalc');
    
    $sCampos  = "j23_anousu                         ,";
    $sCampos .= "j23_matric                         ,";
    $sCampos .= "j23_testad                         ,";
    $sCampos .= "j23_arealo                         ,";
    $sCampos .= "j23_areafr                         ,";
    $sCampos .= "j23_areaed                         ,";
    $sCampos .= "j23_m2terr                         ,";
    $sCampos .= "round(j23_vlrter, 2) as j23_vlrter ,";
    $sCampos .= "round(j23_aliq, 2)   as j23_aliq   ,";
    $sCampos .= "j23_vlrisen                        ,";
    $sCampos .= "j23_tipoim                         ,";
    $sCampos .= "j23_manual                         ,";
    $sCampos .= "j23_tipocalculo                     ";
    
    $sSqlIptuCalc = $oDaoIptuCalc->sql_query_file($this->iAnousu, $this->iMatricula, $sCampos);
    
    $rsIptuCalc   = $oDaoIptuCalc->sql_record($sSqlIptuCalc);
    
    if(!$rsIptuCalc || $oDaoIptuCalc->numrows == 0) {
      return false;
    }
    
    $oIptucalc    = db_utils::fieldsMemory($rsIptuCalc, 0);
    
    $oCalculoValorIptu = new stdClass();
    
    $oCalculoValorIptu->iAnousu         = $oIptucalc->j23_anousu     ;
    $oCalculoValorIptu->iMatricula      = $oIptucalc->j23_matric     ;
    $oCalculoValorIptu->nTestada        = $oIptucalc->j23_testad     ;
    $oCalculoValorIptu->nAreaCalculo    = $oIptucalc->j23_arealo     ;
    $oCalculoValorIptu->nAreaFracionada = $oIptucalc->j23_areafr     ;
    $oCalculoValorIptu->nAreaConstruida = $oIptucalc->j23_areaed     ;
    $oCalculoValorIptu->nValorM2        = $oIptucalc->j23_m2terr     ;
    $oCalculoValorIptu->nValorTerreno   = $oIptucalc->j23_vlrter     ;
    $oCalculoValorIptu->nAliquota       = $oIptucalc->j23_aliq       ;
    $oCalculoValorIptu->nValorIsencao   = $oIptucalc->j23_vlrisen    ;
    $oCalculoValorIptu->sTipoImposto    = $oIptucalc->j23_tipoim     ;
    $oCalculoValorIptu->sLogCalculo     = $oIptucalc->j23_manual     ;
    $oCalculoValorIptu->iTipoCalculo    = $oIptucalc->j23_tipocalculo;
    
    return $oCalculoValorIptu;
    
  }
  
  public function getCalculoConstrucao($iCodigoConstrucao) {
    
    if (empty($iCodigoConstrucao)) {
      throw new Exception('Código da construção não informado');
    }

    $oDaoIptuCale = db_utils::getDao('iptucale');
    
    $sCampos  = "j22_anousu                      ,";
    $sCampos .= "j22_matric                      ,";
    $sCampos .= "j22_idcons                      ,";
    $sCampos .= "j22_areaed                      ,";
    $sCampos .= "j22_vm2                         ,";
    $sCampos .= "j22_pontos                      ,";
    $sCampos .= "round(j22_valor, 2) as j22_valor ";
    
    $sSqlIptuCale = $oDaoIptuCale->sql_query_file($this->iAnousu, $this->iMatricula, $iCodigoConstrucao, $sCampos);
    
    $rsIptuCale   = $oDaoIptuCale->sql_record($sSqlIptuCale);
    
    if (!$rsIptuCale || $oDaoIptuCale->numrows == 0) {
      return false;
    }
    
    $oIptucale    = db_utils::fieldsMemory($rsIptuCale, 0);
    
    $oCalculoConstrucao = new stdClass();
    
    $oCalculoConstrucao->iAnousu           = $oIptucale->j22_anousu;
    $oCalculoConstrucao->iMatricula        = $oIptucale->j22_matric;
    $oCalculoConstrucao->iCodigoConstrucao = $oIptucale->j22_idcons;
    $oCalculoConstrucao->nAreaConstruida   = $oIptucale->j22_areaed;
    $oCalculoConstrucao->nValorM2          = $oIptucale->j22_vm2   ;
    $oCalculoConstrucao->iPontos           = $oIptucale->j22_pontos;
    $oCalculoConstrucao->nValor            = $oIptucale->j22_valor ;
    
    return $oCalculoConstrucao;
  } 
  
  public function getCalculoNumpre () {

    $oDaoIptuNump = db_utils::getDao('iptunump');
    
    $sSqlIptuNump = $oDaoIptuNump->sql_query_file($this->iAnousu, $this->iMatricula);
    
    $rsIptuNump   = $oDaoIptuNump->sql_record($sSqlIptuNump);
    
    return db_utils::getCollectionByRecord($rsIptuNump);
    
  }
  
  
  
  /**
   * retorna o ano de processamento
   * @return integer $iAnousu
   */
  public function getAnousu() {
    return $this->iAnousu;
  }

  /**
   * define o ano de processamento
   * @param $iAnousu
   */
  public function setAnousu($iAnousu) {
    $this->iAnousu = $iAnousu;
  }

  /**
   * retorna o codigo da matricula
   * @return integer $imatricula
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * define o numero da matricula
   * @param $iMatricula
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * retorna a testada gerada
   * @return number
   */
  public function getTestada() {
    return $this->nTestada;
  }

  /**
   * define testada
   * @param $nTestada
   */
  public function setTestada($nTestada) {
    $this->nTestada = $nTestada;
  }

  /**
   * retorna area
   * @return number
   */
  public function getArea() {
    return $this->nArea;
  }

  /**
   * define a area
   * @param $nArea
   */
  public function setArea($nArea) {
    $this->nArea = $nArea;
  }

  /**
   * retorna area fracionada
   * @return number
   */
  public function getAreaFracionada() {
    return $this->nAreaFracionada;
  }

  /**
   * define a área fracionada
   * @param $nAreaFracionada
   */
  public function setAreaFracionada($nAreaFracionada) {
    $this->nAreaFracionada = $nAreaFracionada;
  }

  /**
   * retorna a área edificada
   * @return number
   */
  public function getAreaEdificada() {
    return $this->nAreaEdificada;
  }

  /**
   * define a area edificada
   * @param $nAreaEdificada
   */
  public function setAreaEdificada($nAreaEdificada) {
    $this->nAreaEdificada = $nAreaEdificada;
  }

  /**
   * retorna o valor m2
   * @return number
   */
  public function getValorM2Terreno() {
    return $this->nValorM2Terreno;
  }

  /**
   * define o valor m2
   * @param $nValorM2
   */
  public function setValorM2Terreno($nValorM2) {
    $this->nValorM2Terreno = $nValorM2;
  }

  /**
   * retorna o valor venal do terreno
   * @return number
   */
  public function getValorVenalTerreno() {
    return $this->nValorVenalTerreno;
  }

  /**
   * define o valor venal do terreno
   * @param $nValorVenalTerreno
   */
  public function setValorVenalTerreno($nValorVenalTerreno) {
    $this->nValorVenalTerreno = $nValorVenalTerreno;
  }

  /**
   * retorna a aliquota
   * @return number
   */
  public function getAliquota() {
    return $this->nAliquota;
  }

  /**
   * define a aliquota
   * @param $nAliquota
   */
  public function setAliquota($nAliquota) {
    $this->nAliquota = $nAliquota;
  }

  /**
   * retorna o valor da isencao
   * @return number
   */
  public function getValorIsencao() {
    return $this->nValorIsencao;
  }

  /**
   * define o valor da isencao
   * @param $nValorIsencao
   */
  public function setValorIsencao($nValorIsencao) {
    $this->nValorIsencao = $nValorIsencao;
  }

  /**
   * define o tipo de imposto
   * @return string
   */
  public function getTipoImposto() {
    return $this->sTipoImposto;
  }

  /**
   * define o tipo de imposto
   * @param $sTipoImposto
   */
  public function setTipoImposto($sTipoImposto) {
    $this->sTipoImposto = $sTipoImposto;
  }

  /**
   * retorna manual
   * @return string
   */
  public function getManual() {
    return $this->sManual;
  }

  /**
   * define manual
   * @param $sManual
   */
  public function setManual($sManual) {
    $this->sManual = $sManual;
  }

  /**
   * return tipo de calculo
   * @return integer
   */
  public function getTipoCalculo() {
    return $this->iTipoCalculo;
  }

  /**
   * define o tipo de calculo
   * @param $iTipoCalculo
   */
  public function setTipoCalculo($iTipoCalculo) {
    $this->iTipoCalculo = $iTipoCalculo;
  }

  /**
   * retorna o codigo da receita
   * @return integer
   */
  public function getCodigoReceita() {
    return $this->iCodigoReceita;
  }

  /**
   * define o código da receita
   * @param $iCodigoReceita
   */
  public function setCodigoReceita($iCodigoReceita) {
    $this->iCodigoReceita = $iCodigoReceita;
  }

  /**
   * retorna o valor da receita
   * @return number
   */
  public function getValorReceita() {
    return $this->nValorReceita;
  }

  /**
   * define o valor da receita
   * @param $VnalorReceita
   */
  public function setValorReceita($nValorReceita) {
    $this->nValorReceita = $nValorReceita;
  }

  /**
   * retorna quantidade
   * @return integer
   */
  public function getQuantidade() {
    return $this->iQuantidade;
  }

  /**
   * define quantidade
   * @param $iQuantidade
   */
  public function setQuantidade($iQuantidade) {
    $this->iQuantidade = $iQuantidade;
  }

  /**
   * define código do histórico
   * @return integer
   */
  public function getCodigoHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   * define codigo do historico
   * @param $iCodigoHistorico
   */
  public function setCodigoHistorico($iCodigoHistorico) {
    $this->iCodigoHistorico = $iCodigoHistorico;
  }

  /**
   * define codigo construção
   * @return integer
   */
  public function getCodigoConstrucao() {
    return $this->iCodigoConstrucao;
  }

  /**
   * define codigo da construção
   * @param $iCodigoConstrucao
   */
  public function setCodigoConstrucao($iCodigoConstrucao) {
    $this->iCodigoConstrucao = $iCodigoConstrucao;
  }

  /**
   * retorna area da construcao
   * @return number
   */
  public function getAreaConstrucao() {
    return $this->nAreaConstrucao;
  }

  /**
   * define area da construção
   * @param $nAreaConstrucao
   */
  public function setAreaConstrucao($nAreaConstrucao) {
    $this->nAreaConstrucao = $nAreaConstrucao;
  }

  /**
   * retorna valor m2
   * @return number
   */
  public function getValorM2Construcao() {
    return $this->nValorM2Construcao;
  }

  /**
   * define valor m2 da construção
   * @param $nValorM2Construcao
   */
  public function setValorM2Construcao($nValorM2Construcao) {
    $this->nValorM2Construcao = $nValorM2Construcao;
  }

  /**
   * retorna numero de pontos
   * @return integer
   */
  public function getNumeroPontos() {
    return $this->iNumeroPontos;
  }

  /**
   * define numero de pontos
   * @param $iNumeroPontos
   */
  public function setNumeroPontos($iNumeroPontos) {
    $this->iNumeroPontos = $iNumeroPontos;
  }

  /**
   * retorna valor venal construção
   * @return number
   */
  public function getValorVenalConstrucao() {
    return $this->nValorVenalConstrucao;
  }

  /**
   * define valor venal construção
   * @param $nValorVenalConstrucao
   */
  public function setValorVenalConstrucao($nValorVenalConstrucao) {
    $this->nValorVenalConstrucao = $nValorVenalConstrucao;
  }

  /**
   * retorna numpre calculo
   * @return integer
   */
  public function getNumpre() {
    return $this->iNumpre;
  }

  /**
   * define o numero de numpre
   * @param $iNumpre
   */
  public function setNumpre($iNumpre) {
    $this->iNumpre = $iNumpre;
  }
}