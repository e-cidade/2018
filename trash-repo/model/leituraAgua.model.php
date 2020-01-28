<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Manipula leituras de consumo de agua  
 * @package Agua
 */
class leituraAgua {
  
  /**
   * Codigo da matricula
   *
   * @var integer
   */
  protected $iMatricula;
   
  /**
   * Codigo do hidrometro
   *
   * @var inteiro
   */
  protected $iCodigoHidrometro;
   
  /**
   * Mes da leitura
   *
   * @var inteiro
   */
  protected $iMes;
  
  /**
   * Exercicio da leitura
   *
   * @var inteiro
   */
  protected $iExercicio;
  
  /**
   * Codigo Sequencial da leitura
   *
   * @var inteiro
   */
  protected $iCodigoLeitura;
  
  /**
   * Consumo de agua no mкs
   *
   * @var float
   */
  protected $nConsumoAgua;
  
  /**
   * Situacao da leitura
   *
   * @var inteiro
   */
  protected $iSituacao;
  
  /**
   * Numero cgm do leiturista
   *
   * @var inteiro
   */
  protected $iLeiturista;
  
  /**
   * Data que foi feita a leitura
   *
   * @var date
   */
  protected $dDtLeitura;
  
  /**
   * Data de registro da leitura
   *
   * @var date
   */
  protected $dDtInc;
  
  /**
   * Valor Leitura
   *
   * @var float
   */
  protected $nLeitura;
  
  /**
   * Valor Consumo
   *
   * @var float
   */
  protected $nConsumo;
  
  /**
   * Valor Excesso
   *
   * @var float
   */
  protected $nExcesso;
  
  /**
   * Valor Virou
   *
   * @var boolean
   */
  protected $lVirou;
  /**
   * Dias Ultima Leitura
   *
   * @var integer
   */
  protected $iDias;  

  
  public function __construct($iCodigoHidrometro, $iMes, $iAno) {
    
     if (empty($iCodigoHidrometro)) {

       throw new Exception('Hidrфmetro nгo informado'); 
     }
     
     if (empty($iMes)) {

       throw new Exception('Mкs de referкncia nгo informado'); 
     }
     
     if (empty($iAno)) {

       throw new Exception('Ano de referкncia nгo informado'); 
     }

     $this->iCodigoHidrometro = $iCodigoHidrometro;
     $this->iMes              = $iMes;
     $this->iExercicio        = $iAno;
      
     require_once ('classes/db_agualeitura_classe.php');
     $oDaoAguaLeitura         = new cl_agualeitura();
     $sWhere                  = "x21_codhidrometro = '$iCodigoHidrometro' and x21_mes = '$iMes' and x21_exerc= '$iAno'";
     $campos                  = "x21_codleitura, x21_codhidrometro, x21_exerc, x21_mes, x21_situacao,  x21_numcgm, x21_dtleitura, ";
     $campos                 .= "x21_dtinc, x21_leitura, x21_consumo, x21_excesso, x21_virou";
     
     $sSqlLeituraHidrometro   = $oDaoAguaLeitura->sql_query('', $campos, '',  $sWhere);
     
     $rsLeituraHidrometro     = $oDaoAguaLeitura->sql_record($sSqlLeituraHidrometro);
     
     if ($oDaoAguaLeitura->numrows > 0) {
       
       $oLeitura             = db_utils::fieldsMemory($rsLeituraHidrometro, 0, '', false);
   
       $this->iCodigoLeitura       = $oLeitura->x21_codleitura;
       $this->iCodigoHidrometro    = $oLeitura->x21_codhidrometro;
       $this->iExercicio           = $oLeitura->x21_exerc;
       $this->iMes                 = $oLeitura->x21_mes;
       $this->iSituacao            = $oLeitura->x21_situacao; 
       $this->iLeiturista          = $oLeitura->x21_numcgm;
       $this->dDtLeitura           = $oLeitura->x21_dtleitura;
       $this->dDtInc               = $oLeitura->x21_dtinc; 
       $this->nLeitura             = $oLeitura->x21_leitura;
       $this->nConsumo             = $oLeitura->x21_consumo;
       $this->nExcesso             = $oLeitura->x21_excesso;
       $this->lVirou               = $oLeitura->x21_virou=='t'?true:false; 
                        
     }
  }
  
  /**
   * retorna a leitura anterior
   *
   * @return leituraAgua
   */
  public function getLeituraAnterior () {
    
    $this->iMes = $this->iMes - 1;
    
    if ($this->iMes == 0) {
      
      $this->iMes = 12;
      $this->iExercicio = $this->iExercicio - 1; 
    }
    return new leituraAgua($this->iCodigoHidrometro, $this->iMes, $this->iExercicio);
    
  }

  /**
   * Retorna Exercicio da leitura
   *
   * @return integer
   */
  public function getExercicio() {
    return $this->iExercicio;
  }
  
  /**
   * Retorna Mes da leitura
   *
   * @return integer
   */
  public function getMes() {
    return $this->iMes;
  }
  
  /**
   * Retorna o cуdigo da leitura
   *
   * @return integer
   */
  public function getCodigoLeitura() {
    return $this->iCodigoLeitura;
  }
  
	/**
   * Retorna o cуdigo da leitura
   *
   * @return integer
   */
  public function setCodigoLeitura($iCodigoLeitura) {
    $this->iCodigoLeitura = $iCodigoLeitura;
  }
  
  /**
   * Retorna matricula da leitura
   *
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }
  
  /**
   * Define matricula da leitura
   *
   * @param integer $iMatricula Matricula da leitura
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }
  
  /**
   * Retorna situaзгo da leitura
   *
   * @return integer
   */
  public function getSituacao() {
    return $this->iSituacao;
  }
  
  /**
   * Define valor situacao
   *
   * @param integer $iSituacao Situaзгo da leitura
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }
  
  /**
   * Retorna cgm Leiturista
   *
   * @return integer
   */
  public function getLeiturista() {
    return $this->iLeiturista;
  }
  
  /**
   * Define leiturista
   *
   * @param integer $iLeiturista
   */
  public function setLeiturista($iLeiturista) {
    $this->iLeiturista = $iLeiturista;
  }
  
  /**
   * Retorna data leitura
   *
   * @return date
   */
  public function getDtLeitura() {
    return $this->dDtLeitura;
  }
  
  /**
   * Define data leitura
   *
   * @param date $dDtLeitura Data Leitura
   */
  public function setDtLeitura($dDtLeitura) {
    $this->dDtLeitura = $dDtLeitura;
  }
  
  /**
   * Retorna data de registro 
   *
   * @return date
   */
  public function getDtInc() {
    return $this->dDtInc; 
  }
  
  /**
   * Define data registro
   *
   * @param date $dDtInc Data Registro
   */
  public function setDtInc($dDtInc) {
    $this->dDtInc = $dDtInc;
  }
  
  /**
   * Retorna valor da leitura
   *
   * @return float
   */
  public function getLeitura() {
    return $this->nLeitura;
  }
  
  /**
   * Define valor da leitura
   *
   * @param float $nLeitura Valor da leitura
   */
  public function setLeitura($nLeitura) {
    $this->nLeitura = $nLeitura;
  }
  
  /**
   * Retorna valor consumo
   *
   * @return float
   */
  public function getConsumo() {
    return $this->nConsumo;
  }
  
  /**
   * Define valor consumo
   *
   * @param float $nConsumo Valor do Consumo
   */
  public function setConsumo($nConsumo) {
    $this->nConsumo = $nConsumo;
  }
  
  /**
   * Retorna valor do excesso
   *
   * @return float
   */
  public function getExcesso() {
    return $this->nExcesso;
  }
  
  /**
   * Define valor excesso
   *
   * @param float $nExcesso Valor Excesso
   */
  public function setExcesso($nExcesso) {
    $this->nExcesso = $nExcesso;
  }
  
  /**
   * Retorna valor virou (t ou f)
   *
   * @return boolean
   */
  public function getVirou() {
    return $this->lVirou;
  }
  
  /**
   * Define valor virou (t ou f)
   *
   * @param boolean $Virou
   */
  public function setVirou($Virou) {
    $this->lVirou = $Virou;
  }
  
/**
   * Retorna dias ultima leitura
   *
   * @return integer
   */
  public function getDias() {
    return $this->iDias;
  }
  
  /**
   * Define Numero dias ultima leitura
   *
   * @param integer $iDias
   */
  public function setDias($iDias) {
    $this->lVirou = $iDias;
  }
}

?>