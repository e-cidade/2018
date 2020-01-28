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
 * Model Cr�dito
 * 
 * Classe que manipula os cr�ditos lan�ados no sistema
 * 
 * @author alberto <alberto@dbseller.com.br>
 * @author robson.silva <robson.silva@dbseller.com.br>
 * @package Arrecadacao
 * 
 * @version $ 
 *
 */
abstract class Credito {
  
  /**
   * C�digo do cr�dito no sistema
   * @var integer
   */
  private $iCodigoCredito;
  
  /**
   * Tipo do abatimento
   * 3 = Cr�dito
   * @var integer
   */
  private $iTipoAbatimento = 3;
  
  /**
   * Data do lan�amento do cr�dito
   * @var object DBDate
   */
  private $oDataLancamento;
  
  /**
   * Hora do lan�amento do cr�dito
   * @var string
   */
  private $sHora;
  
  /**
   * C�digo do usu�rio que lan�ou o cr�dito
   * @var integer
   */
  private $iUsuario;
  
  /**
   * Institui��o do cr�dito
   * @var integer
   */
  private $iInstituicao;
  
  /**
   * Valor do cr�dito
   * @var numeric
   */
  private $nValor;
  
  /**
   * Percentual do cr�dito
   * @var integer
   */
  private $nPercentual;
  
  /**
   * Valor Disponivel para utiliza��o do cr�dito
   * @var numeric
   */
  private $nValorDisponivel;
  
  
  /**
   * Caso seja informado o c�digo do cr�dito, este � carregado em mem�ria
   * @param string $iCodigoCredito
   * @throws Exception
   * @return boolean
   */
  public function __construct($iCodigoCredito = null) {
    
    if (!empty($iCodigoCredito)) {
      
      $oDaoAbatimento = db_utils::getDao('abatimento');
      
      $sSqlAbatimento = $oDaoAbatimento->sql_query($iCodigoCredito, $cCampos);
      
      $rsAbatimento   = $oDaoAbatimento->sql_record($sSqlAbatimento);
      
      if ($oDaoAbatimento->numrows == 0) {
        
        throw new Exception("Nenhum cr�dito encontrado com o c�digo: {$iCodigoCredito}");
      }
      
      $oAbatimento = db_utils::fieldsMemory($rsAbatimento, 0);
      
      $this->setCodigoCredito        ($oAbatimento->k125_sequencial);
      $this->setTipoAbatimento       ($oAbatimento->k125_tipoabatimento);
      $this->setDataLancamento       (new DBDate($oAbatimento->k125_datalanc));
      $this->setHora                 ($oAbatimento->k125_hora);
      $this->setUsuario              ($oAbatimento->k125_usuario);
      $this->setInstituicao          ($oAbatimento->k125_instit);
      $this->setValor                ($oAbatimento->k125_valor);
      $this->setPercentual           ($oAbatimento->k125_perc);
      $this->setValorDisponivel      ($oAbatimento->k125_valordisponivel);
      
    }
  }
  

  /**
   * Retorna o c�digo do cr�dito
   * @return integer
   */
  public function getCodigoCredito() {
  	
    return $this->iCodigoCredito;
  }
  
  /**
   * Define o c�digo do cr�dito
   * @param $iCodigoCredito
   */
  public function setCodigoCredito($iCodigoCredito) {
  	
    $this->iCodigoCredito = $iCodigoCredito;
  }
  
  /**
   * Retorna o Tipo de Abatimento
   * @return integer
   */
  public function getTipoAbatimento() {
  	
    return $this->iTipoAbatimento;
  }
  
  /**
   * Define o Tipo de Abatimento
   * @param $iTipoAbatimento
   */
  public function setTipoAbatimento($iTipoAbatimento) {
  	
    $this->iTipoAbatimento = $iTipoAbatimento;
  }
  
  /**
   * Retorna a data de lan�amento
   * @return DBDate
   */
  public function getDataLancamento() {
  	
    return $this->oDataLancamento;
  }
  
  /**
   * Define a data de lan�amento
   * @param $oDataLancamento
   */
  public function setDataLancamento(DBDate $oDataLancamento) {
  	
    $this->oDataLancamento = $oDataLancamento;
  }
  
  /**
   * Retorna a hora em que foi gerado o cr�dito
   * @return string
   */
  public function getHora() {
  	
    return $this->sHora;
  }
  
  /**
   * Define a hora em que foi gerado o cr�dito
   * @param $sHora
   */
  public function setHora($sHora) {
  	
    $this->sHora = $sHora;
  }
  
  /**
   * Retorna o usu�rio
   * @return integer
   */
  public function getUsuario() {
  	
    return $this->iUsuario;
  }
  
  /**
   * Define o usu�rio
   * @param $iUsuario
   */
  public function setUsuario($iUsuario) {
  	
    $this->iUsuario = $iUsuario;
  }
  
  /**
   * Retorna a institui��o
   * @return integer
   */
  public function getInstituicao() {
  	
    return $this->iInstituicao;
  }
  
  /**
   * Define a institui��o
   * @param $iInstituicao
   */
  public function setInstituicao($iInstituicao) {
  	
    $this->iInstituicao = $iInstituicao;
  }
  
  /**
   * Retorna o valor do cr�dito
   * @return float
   */
  public function getValor() {
  	
    return $this->nValor;
  }
  
  /**
   * Define o valor do cr�dito
   * @param $nValor
   */
  public function setValor($nValor) {
  	
    $this->nValor = $nValor;
  }
  
  /**
   * Retorna o percentual de abatimento do cr�dito
   * @return float
   */
  public function getPercentual() {
  	
    return $this->nPercentual;
  }
  
  /**
   * Define o percentual de abatimento do cr�dito
   * @param $nPercentual
   */
  public function setPercentual($nPercentual) {
  	
    $this->nPercentual = $nPercentual;
  }
  
  /**
   * Retorna o valor disponivel do cr�dito
   * @return float
   */
  public function getValorDisponivel() {
  	
    return $this->nValorDisponivel;
  }
  
  /**
   * Define o valor disponivel do cr�dito
   * @param $nValorDisponivel
   */
  public function setValorDisponivel($nValorDisponivel) {
  	
    $this->nValorDisponivel = $nValorDisponivel;
  }
}