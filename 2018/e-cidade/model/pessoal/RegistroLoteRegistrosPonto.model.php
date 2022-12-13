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
 * Classe que representa um registro da tabela preponto
 * 
 * @package folha
 * @author  Renan Silva  <renan.silva@dbseller.com.br> 
 */
class RegistroLoteRegistrosPonto extends RegistroPonto{

  /**
   * Sequencial da tabela
   *
   * @var Codigo
   * @access private
   * 
   */
  private $iCodigo;

  /**
   * Sequencial do lote de registros do ponto
   *
   * @var CodigoLote
   * @access private
   * 
   */
  private $iCodigoLote;

  /**
   * Instituição do registro
   * @var Instituicao
   * @access private
   */
  private $oInstituicao;

  /**
   * Tipo de folha de pagamento do registro do ponto
   * @var FolhaPagamento
   * @access private
   */
  private $oFolhaPagamento;

  /**
   * Representa a competência limite do registro do ponto
   * @var String
   * @access private
   */
  private $sCompetencia;
  
  /**
   * Define o codigo sequencial do registro do ponto
   *
   * @param Integer $iCodigo
   * @access public
   * @return void
   */
  public function setCodigo ($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Define o codigo sequencial do lote de registros do ponto
   *
   * @param Integer $iCodigoLote
   * @access public
   * @return void
   */
  public function setCodigoLote ($iCodigoLote) {
    $this->iCodigoLote = $iCodigoLote;
  }

  /**
   * Define a instituicao do registro do lote de registros do ponto
   * @param Instituicao $oInstituicao
   * @access public
   * @return void
   */
  public function setInstituicao ( $oInstituicao ) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Define o tipo de folha de pagamento
   *
   * @param FolhaPagamento
   * @access public
   * @return void
   */
  public function setFolhaPagamento ( $oFolhaPagamento ) {
    $this->oFolhaPagamento = $oFolhaPagamento;
  }

  /**
   * Define a competência limite do registro do ponto.
   *  
   * @access public
   * @param String $sCompetencia
   */
  public function setCompetencia($sCompetencia) {
    $this->sCompetencia = $sCompetencia;
  } 
  
  /**
   * Retorna o codigo sequencial do registro
   *
   * @access public
   * @return Integer
   */
  public function getCodigo () {
    return $this->iCodigo;
  }

  /**
   * Retorna o codigo sequencial do lote de registros do ponto
   *
   * @access public
   * @return Integer
   */
  public function getCodigoLote () {
    return $this->iCodigoLote;
  }

  /**
   * Retorna a instituicao do registro do lote de registros do ponto
   *
   * @access public
   * @return Instituicao
   */
  public function getInstituicao () {
    return $this->oInstituicao;
  }

  /**
   * Retorna o tipo de folha do registro do ponto
   *
   * @access public
   * @return FolhaPagamento
   */
  public function getFolhaPagamento() {
    return $this->oFolhaPagamento;
  }

  /**
   * Retorna a competência limite do registro do ponto.
   *  
   * @access public
   * @return String
   */
  public function getCompetencia() {
    return $this->sCompetencia;
  }

  /**
   * Retorna o objeto em uma classe stdClass para fazer parse para JSON
   *
   * @access public
   * @return StdClass
   */
  public function toStdClass(){

    $oStdClass                = new stdClass();
    $oStdClass->iCodigo       = $this->getCodigo();
    $oStdClass->iCodigoLote   = $this->getCodigoLote();
    $oStdClass->sRubrica      = $this->getRubrica()->getCodigo();
    $oStdClass->sNomeRubrica  = $this->getRubrica()->getDescricao();
    $oStdClass->sMatricula    = $this->getServidor()->getMatricula();
    $oStdClass->sNome         = $this->getServidor()->getCgm()->getNome();
    $oStdClass->iQuantidade   = $this->getQuantidade();
    $oStdClass->nValor        = $this->getValor();
    $oStdClass->sCompetencia  = $this->getCompetencia();

    return $oStdClass;
  }

}