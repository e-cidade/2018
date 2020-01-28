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
 * Classe representa um lote de registros ponto.
 * 
 * @package Pessoal
 * @author $Author: dbiuri $
 * @version $Revision: 1.20 $
 */

class LoteRegistrosPonto {

  const MENSAGEM   = 'recursoshumanos.pessoal.LoteRegistrosPonto.';

  const ABERTO     = 'A';
  const FECHADO    = 'F';
  const CONFIRMADO = 'C';

  const SUBSTITUIR_RUBRICA = TRUE;
  const SOMAR_RUBRICA      = FALSE;

  /**
   * Representa o identificador único do lote.
   * 
   * @var Integer
   */
  private $iSequencial;

  /**
   * Representa a competência do lote.
   * 
   * @var DBCompetencia
   */
  private $oCompetencia;

  /**
   * Representa a instituicao do lote.
   * 
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Representa a descrição do lote.
   * 
   * @var String
   */
  private $sDescricao;

  /**
   * Representa os registros dos pontos do lote. 
   * 
   * @var RegistroPonto[]
   */
  private $aRegistroPonto = array();

  /**
   * Representa a situação do lote.
   * 
   * @var String
   */
  private $sSituacao;

  /**
   * Representa o usuário responsávelo pelo lote.
   *
   * @var UsuarioSistema
   */
  private $oUsuario;

  /**
   * Representa em qual ponto deve ser lançado os 
   * dados do registro do Lote
   *
   * @var String
   */
  private $sTipoPonto;

  /**
   * Informa que o tipo de lançamento que deve ser feito, se substitui a rubrica ou se soma
   * 
   * @var Integer
   */
  private $lTipoLancamentoPonto = LoteRegistrosPonto::SUBSTITUIR_RUBRICA;

  /**
   * Construtor da classe.
   * 
   * @param Integer $iSequencial
   */
  function __construct($iSequencial = null) {
    $this->setSequencial($iSequencial);
  }

  /**
   * Retorna o identificador único do lote.
   * 
   * @access public
   * @return Integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Retorna a competência do lote.
   * 
   * @access public
   * @return DBCompetencia
   */
  public function getCompetencia() {
    return $this->oCompetencia;
  }

  /**
   * Retorna a instituição do lote.
   * 
   * @access public
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Retorna a descrição do lote.
   * 
   * @access public
   * @return String
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Retorna a coleção de registros do lote.
   * 
   * @access public
   * @return RegistroPonto[]
   */
  public function getRegistroPonto() {
    return $this->aRegistroPonto;
  }

  /**
   * Retorna a coleção de registros do lote por servidor.
   * @access public
   * @return RegistroPonto[][]
   */
  public function getRegistroPontoServidor(){

    $aServidorRegistrosPonto = array();
    
    foreach ($this->getRegistroPonto() as $oRegistro) {
      $aServidorRegistrosPonto[$oRegistro->getServidor()->getMatricula()][] = $oRegistro;
    }

    return $aServidorRegistrosPonto;
  }

  /**
   * Retorna a situação do lote.
   * 
   * @access public
   * @return String
   */
  public function getSituacao() {
    return $this->sSituacao;
  }

  /**
   * Retorna o usuário do lote
   *
   * @access public
   * @return UsuarioSistema
   */
  public function getUsuario() {
    return $this->oUsuario;
  }

  /**
   * Retorna o ponto que o lote deve ser lançado.
   * @return String
   */
  public function getTipoPonto(){
    return $this->sTipoPonto;
  }

  /**
   * Retorna o tipo de lancamento que deve ser feito na rubrica, substituindo ou somando o valor
   * @return Boolean
   */
  public function getTipoLancamentoPonto() {
    return $this->lTipoLancamentoPonto;
  }

  /**
   * Seta o identitifcador único do lote.
   * 
   * @access public
   * @param Integer $iSequencial
   */
  public function setSequencial($iSequencial) {
    $this->iSequencial = $iSequencial;
  }

  /**
   * Seta a competência do lote.
   * 
   * @access public
   * @param DBCompetencia $oCompetencia
   */
  public function setCompetencia(DBCompetencia $oCompetencia) {
    $this->oCompetencia = $oCompetencia;
  }

  /**
   * Seta a instituição do lote.
   * 
   * @access public
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Seta a descrição do lote.
   * 
   * @access public
   * @param String $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Seta uma coleção de registros do ponto do lote.
   * 
   * @access public
   * @param RegistroPonto[][] $aRegistroPonto
   */
  public function setRegistroPonto($aRegistroPonto) {
    $this->aRegistroPonto = $aRegistroPonto;
  }

  /**
   * Seta a situação do lote.
   * 
   * @access public
   * @param String $sSituacao
   */
  public function setSituacao($sSituacao) {
    $this->sSituacao = $sSituacao;
  }

  /**
   * Define o usuário do lote
   *
   * @access public
   * @param UsuarioSistema
   */
  public function setUsuario(UsuarioSistema $oUsuario) {
    $this->oUsuario = $oUsuario;
  }

  /**
   * Define o ponto ao qual o lote deve ser lançado.
   *
   * @access public
   * @param String $sTipoPonto
   */
  public function setTipoPonto($sTipoPonto) {
    $this->sTipoPonto = $sTipoPonto;
  }

  /**
   * Define o tipo de lancamento da rubrica no ponto, se substitui a rubrica ou se soma ao valor da rubrica
   * 
   * @param Boolean $lTipoLancamentoPonto
   */
  public function setTipoLancamentoPonto($lTipoLancamentoPonto) {
    $this->lTipoLancamentoPonto = $lTipoLancamentoPonto;
  }

  /**
   * Responsável por adicionar um registro do ponto no lote.
   * 
   * @access public
   * @param RegistroPonto $oRegistroPonto
   */
  public function adicionarRegistroPonto(RegistroPonto $oRegistroPonto) {

    $iMatricula = $oRegistroPonto->getServidor()->getMatricula();
    $iRubrica   = $oRegistroPonto->getRubrica()->getCodigo();

    $this->aRegistroPonto[$iMatricula][$iRubrica] = $oRegistroPonto;    
  }

  /**
   * Responsável por remover um registro do ponto no lote.
   * 
   * @access public
   * @param RegistroPonto $oRegistroPonto
   */
  public function removerRegistroPonto(RegistroPonto $oRegistroPonto){

    $iMatricula = $oRegistroPonto->getServidor()->getMatricula();
    $iRubrica   = $oRegistroPonto->getRubrica()->getCodigo();

    unset($this->aRegistroPonto[$iMatricula][$iRubrica]);

    if (empty($this->aRegistroPonto[$iMatricula])) {
      unset($this->aRegistroPonto[$iMatricula]);
    }
  }

  /**
   * Responsável por confirmar o lote e lançar os registros no ponto de salário
   *
   * @return bool
   * @throws \BusinessException
   */
  public function confirmarLote () {

    if ( $this->getSituacao() == self::CONFIRMADO ) {
      throw new BusinessException(_M(self::MENSAGEM . 'erro_confirmar_lote_confirmado'));
    }

    $aRegistrosLoteRegistrosPonto = $this->getRegistroPonto();
    if ( count($this->getRegistroPonto()) == 0 ) {
      throw new BusinessException(_M(self::MENSAGEM . 'erro_confirmar_lote_vazio'));
    }

    $this->setSituacao(self::CONFIRMADO);
    LoteRegistrosPontoRepository::persist($this);

    return true;
  }

  /**
   * Responsável por desfazer a confirmação do lote dos registros do ponto.
   * 
   * @throws Exception
   */
  public function cancelarConfirmacao(){
    if ($this->getSituacao() == LoteRegistrosPonto::CONFIRMADO) {

      LoteRegistrosPontoRepository::cancelarConfirmacao($this);
      $this->setSituacao(LoteRegistrosPonto::FECHADO);
      LoteRegistrosPontoRepository::persist($this);

    } else {
      throw new BusinessException(_M(self::MENSAGEM . 'lote_nao_confirmado'));
    }
    
    return true;
  }

  /**
   * Cria uma representação no formato stdClass do OBJETO
   * 
   * @return String 
   */
  public function toStdClass() {

    $oJson    = new Services_JSON();
    $oRetorno = new stdClass();
    $sSituacao = '';

    switch( $this->getSituacao() ) {
    case self::CONFIRMADO: 
      $sSituacao = "Confirmado";
      break;
    case self::ABERTO    : 
      $sSituacao ="Aberto";
      break;
    case self::FECHADO   :
      $sSituacao = "Fechado";
      break;
    } 

    $oRetorno->iCodigo         = $this->iSequencial;
    $oRetorno->sSituacao       = $sSituacao;
    $oRetorno->sDescricao      = $this->getDescricao();
    $oRetorno->sUsuario        = $this->getUsuario()->getNome();
    $oRetorno->sMesCompetencia = $this->getCompetencia()->getMes();
    $oRetorno->sAnoCompetencia = $this->getCompetencia()->getAno();
    return $oRetorno;
  }

  /**
   * Retorna a folha de pagamento ao qual o lote está vinculado ou false se nenhuma folha
   * @return bool|FolhaPagamento
   * @throws \DBException
   */
  public function getFolhaPagamento() {

    if($this->getSequencial() != null && trim($this->getSequencial()) != "") {

      if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

        $oDaoLoteFolhaPagamento   = new cl_loteregistropontorhfolhapagamento();
        $sWhereLoteFolhaPagamento = " rh162_loteregistroponto = {$this->getSequencial()}";
        $rsLoteFolhaPagamento     = db_query($oDaoLoteFolhaPagamento->sql_query_join_folha_pagamento(null, 
                                                                                                     "rh141_sequencial, rh141_tipofolha", 
                                                                                                     null, 
                                                                                                     $sWhereLoteFolhaPagamento));

        if (!$rsLoteFolhaPagamento) {
          throw new DBException("Erro ao Buscar FOlha de Pagamento");
        }

        if(pg_num_rows($rsLoteFolhaPagamento) > 0) {

          $oStdLoteFolha = db_utils::fieldsMemory($rsLoteFolhaPagamento, 0);

          switch ($oStdLoteFolha->rh141_tipofolha) {
            case FolhaPagamento::TIPO_FOLHA_SALARIO:
                return new FolhaPagamentoSalario($oStdLoteFolha->rh141_sequencial);
              break;
            case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
                return new FolhaPagamentoSuplementar($oStdLoteFolha->rh141_sequencial);
              break;
            case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
                return new FolhaPagamentoComplementar($oStdLoteFolha->rh141_sequencial);
              break;
          }
        }
      } else { //Bases que não está com parâmetro de suplementar ativo retorna folha de salário

        return new FolhaPagamentoSalario();
      }
    }
    return false;
  }

}
