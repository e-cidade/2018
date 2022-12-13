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
 * NotaPlanilhaRetencao 
 * 
 * @package 
 * @version $id$
 * @author Rafael Nery       <rafael.nery@dbseller.com.br> 
 * @author Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br> 
 *
 */
class NotaPlanilhaRetencao {

  const SERVICO_TOMADO           = 1;
  const SERVICO_PRESTADO         = 2;

  const STATUS_ATIVO             = 1;
  const STATUS_INATIVO_ALTERACAO = 2;
  const STATUS_INATIVO_EXCLUSAO  = 3;

  /**
   * @var unknown_type
   */
  private $oPlanilhaRetencao = null;
  
  /**
   * Código da planilha de retencao
   * 
   * @var integer
   * @access private
   */
  private $iCodigoPlanilha;

  /**
   * Data da operacao 
   * 
   * @var DBDate
   * @access private
   */
  private $oDataOperacao;

  /**
   * Hora da operacao 
   * 
   * @var string
   * @access private
   */
  private $sHoraOperacao;

  /**
   * Tipo de lancamento 
   * 
   * @var integer
   * @access private
   */
  private $iTipoLancamento;
  
  /**
   * Valida se esta retido 
   * 
   * @var boolean
   * @access private
   */
  private $lRetido;  

  /**
   * status da nota 
   * 
   * @var integer
   * @access private
   */
  private $iStatus;   

  /**
   * codigo da situacao 
   * 
   * @var integer
   * @access private
   */
  private $iSituacao; 
  
  /**
   * Data da nota fiscal 
   * 
   * @var DBDate
   * @access private
   */
  private $oDataNota;
  
  /**
   * CNPJ da nota fiscal 
   * 
   * @var string
   * @access private
   */
  private $sCNPJ;                    

  /**
   * Serie da nota fiscal 
   * 
   * @var string
   * @access private
   */
  private $sSerie;       

  /**
   * Nome na nota fiscal 
   * 
   * @var string
   * @access private
   */
  private $sNome;

  /**
   * Numero da Nota fiscal 
   * 
   * @var string
   * @access private
   */
  private $iNumeroNota;       

  /**
   * Valor do Serviço
   * 
   * @var numeric
   * @access private
   */
  private $nValorServico;

  /**
   * Valor Retido na Nota
   * 
   * @var numeric
   * @access private
   */
  private $nValorRetencao;

  /**
   * Valor Aliquota
   *
   * @var    numeric
   * @access private
   */
  private $nAliquota;       
  
  /**
   * Valor da Deducao 
   * 
   * @var    numeric
   * @access private
   */
  private $nValorDeducao;

  /**
   * Valor Base da Nota 
   * 
   * @var    numeric
   * @access private
   */
  private $nValorBase;  

  /**
   * Valor do Imposto 
   * 
   * @var numeric
   * @access private
   */
  private $nValorImposto;

  /**
   * Descricao do Serviço 
   * 
   * @var srting
   * @access private
   */
  private $sServico;    

  /**
   * Observacoes da Nota Fiscal 
   * 
   * @var mixed
   * @access private
   */
  private $sObservacoes;        
  
  /**
   * Codigo da Nota de Liquidação 
   * @var mixed
   * @access private
   */
  private $iCodigoNotaLiquidacao = null;
  
  /**
   * Codigo da Nota PLanilha
   * @var integer
   * @access private
   */
  private $iCodigoNotaPlanilha = null;
  
  /**
   * Construtor da Classe
   * @access public
   * @return void
   */
  public function __construct( $iCodigoNotaPlanilha = null ) {

    if ( !empty($iCodigoNotaPlanilha) ) {
      
      $oDadosPlanilha = db_utils::getDao('issplanit');
      $sSql = $oDadosPlanilha->sql_query_file($iCodigoNotaPlanilha);
      $rsDadosPLanilha = $oDadosPlanilha->sql_record($sSql);
      $oDadosPlanilha = db_utils::fieldsMemory($rsDadosPLanilha, 0);
      
      $this->setCodigoPlanilha  ($oDadosPlanilha->q21_planilha            );
      $this->setDataOperacao    (new DBDate($oDadosPlanilha->q21_dataop)  );
      $this->setHoraOperacao    ($oDadosPlanilha->q21_horaop              );
      $this->setTipoLancamento  ($oDadosPlanilha->q21_tipolanc            );
      $this->setRetido          ($oDadosPlanilha->q21_retido              );
      $this->setStatus          ($oDadosPlanilha->q21_status              );
      $this->setSituacao        ($oDadosPlanilha->q21_situacao            );
      $this->setDataNota        (new DBDate($oDadosPlanilha->q21_datanota));
      $this->setCNPJ            ($oDadosPlanilha->q21_cnpj                );
      $this->setSerie           ($oDadosPlanilha->q21_serie               );
      $this->setNome            ($oDadosPlanilha->q21_nome                );
      $this->setNumeroNota      ($oDadosPlanilha->q21_nota                );
      $this->setValorServico    ($oDadosPlanilha->q21_valorser            );
      $this->setValorRetencao   ($oDadosPlanilha->q21_valor               );
      $this->setAliquota        ($oDadosPlanilha->q21_aliq                );
      $this->setValorDeducao    ($oDadosPlanilha->q21_valordeducao        );
      $this->setValorBase       ($oDadosPlanilha->q21_valorbase           );
      $this->setValorImposto    ($oDadosPlanilha->q21_valorimposto        );
      $this->setDescricaoServico($oDadosPlanilha->q21_servico             );
      $this->setObservacoes     ($oDadosPlanilha->q21_obs                 );
    }
  }
  
  /**
   * Salva os Dados da Nota da Planilha de Retencao 
   * 
   * @access public
   * @return void
   */
  public function salvar($iCodigoNotaPlanilha = null) {
    
    if (!db_utils::inTransaction()) {
      throw new Exception('Erro [0] - Não Existe transação ativa');
    }
    
    $oDaoNotas = new cl_issplanit();
    
    $oDaoNotas->q21_planilha     = $this->getCodigoPlanilha();          
    $oDaoNotas->q21_dataop       = $this->getDataOperacao()->getDate(); 
    $oDaoNotas->q21_horaop       = $this->getHoraOperacao();            
    $oDaoNotas->q21_tipolanc     = $this->getTipoLancamento();          
    $oDaoNotas->q21_retido       = $this->isRetido() ? 'true' : 'false';
    $oDaoNotas->q21_situacao     = $this->getSituacao();
    $oDaoNotas->q21_status       = $this->getStatus();
    $oDaoNotas->q21_datanota     = $this->getDataNota()->getDate();     
    $oDaoNotas->q21_cnpj         = $this->getCNPJ();                    
    $oDaoNotas->q21_serie        = $this->getSerie();                   
    $oDaoNotas->q21_nome         = $this->getNome();                    
    $oDaoNotas->q21_nota         = $this->getNumeroNota();              
    $oDaoNotas->q21_valorser     = $this->getValorServico();            
    $oDaoNotas->q21_valor        = $this->getValorRetencao();           
    $oDaoNotas->q21_aliq         = $this->getAliquota();                
    $oDaoNotas->q21_valordeducao = $this->getValorDeducao();            
    $oDaoNotas->q21_valorbase    = $this->getValorBase();               
    $oDaoNotas->q21_valorimposto = $this->getValorImposto();            
    $oDaoNotas->q21_servico      = $this->getDescricaoServico();        
    $oDaoNotas->q21_obs          = $this->getObservacoes();             
    
    if ($iCodigoNotaPlanilha == null) {
      $oDaoNotas->incluir(null);
    } else {
      
      $oDaoNotas->q21_sequencial = $iCodigoNotaPlanilha;
      $oDaoNotas->alterar($iCodigoNotaPlanilha);
    }
    
    $this->iCodigoNotaPlanilha = $oDaoNotas->q21_sequencial;
    
    if ($oDaoNotas->erro_status == 0) {
      throw new Exception("Erro ao Lançar nota na planilha.\n{$oDaoNotas->erro_msg}"); 
    }

    if ( !empty($this->iCodigoNotaLiquidacao) ) {

      $oDaoIssPlanOp = db_utils::getDao('issplanitop');
      $oDaoIssPlanOp->q96_issplanit = $this->getCodigoPlanilha();
      $oDaoIssPlanOp->q96_pagordem  = $this->getCodigoNotaLiquidacao();
      $oDaoIssPlanOp->incluir(null);
      
      if ($oDaoIssPlanOp->erro_status == 0) {
        throw new Exception('Erro ao incluir nota na planilha.'); 
      }
    }

    return true;
  }

  /**
   * Retorna o codigo da planilha 
   * @return integer 
   */
  public function getCodigoPlanilha() {
    return $this->iCodigoPlanilha;
  }

  /**
   * Define o codigo da planilha 
   * @param $iCodigoPlanilha
   */
  public function setCodigoPlanilha($iCodigoPlanilha) {
    $this->iCodigoPlanilha = $iCodigoPlanilha;
  }

  /**
   * Retorna data de operacao 
   * @return DBDate 
   */
  public function getDataOperacao() {
    return $this->oDataOperacao;
  }

  /**
   * Define data de operacao 
   * @param $oDataOperacao
   */
  public function setDataOperacao($oDataOperacao) {
    $this->oDataOperacao = $oDataOperacao;
  }

  /**
   * Retorna hora de operacao 
   * @return string 
   */
  public function getHoraOperacao() {
    return $this->sHoraOperacao;
  }

  /**
   * Define hora de operacao
   * @param $sHoraOperacao
   */
  public function setHoraOperacao($sHoraOperacao) {
    $this->sHoraOperacao = $sHoraOperacao;
  }

  /**
   * Retorna o tipo de lancamento
   * @return integer 
   */
  public function getTipoLancamento() {
    return $this->iTipoLancamento;
  }

  /**
   * Define o tipo de lancamento 
   * @param $iTipoLancamento
   */
  public function setTipoLancamento($iTipoLancamento) {
    $this->iTipoLancamento = $iTipoLancamento;
  }

  /**
   * Valida se esta retido 
   * @return boolean
   */
  public function isRetido() {
    return $this->lRetido;
  }

  /**
   * Define se esta retido
   * @param $lRetido
   */
  public function setRetido($lRetido) {
    $this->lRetido = $lRetido;
  }

  /**
   * Retorna o Status da nota da PLanilha
   * @return 
   */
  public function getStatus() {
    return $this->iStatus;
  }

  /**
   * Define o Status da Nota
   * @param $iStatus integer
   */
  public function setStatus($iStatus) {
    $this->iStatus = $iStatus;
  }
  
  /**
   * Retorna a Situacao
   * @return 
   */
  public function getSituacao() {
    return $this->iSituacao;
  }

  /**
   * Define a Situacao da Nota
   * @param $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Retorna a Data da NOta
   * @return DBDate
   */
  public function getDataNota() {
    return $this->oDataNota;
  }

  /**
   * Data da Nota Fiscal
   * @param $oDataNota
   */
  public function setDataNota(DBDate $oDataNota) {
    $this->oDataNota = $oDataNota;
  }

  /**
   * Retorna o CNPJ da NOta
   * @return 
   */
  public function getCNPJ() {
    return $this->sCNPJ;
  }

  /**
   * Define o CNPJ da Nota
   * @param $sCNPJ
   */
  public function setCNPJ($sCNPJ) {
    $this->sCNPJ = $sCNPJ;
  }

  /**
   * Retorna a Serie da Nota Fisca
   * @return 
   */
  public function getSerie() {
    return $this->sSerie;
  }

  /**
   * Define a Serie da Nota Fiscal
   * @param $sSerie string
   */
  public function setSerie($sSerie) {
    $this->sSerie = $sSerie;
  }

  /**
   * Retorna o Nome da Nota Fiscal
   * @return 
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Define o Nome da Nota Fiscal
   * @param $sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Retorna o Numero da Nota Fiscal
   * @return 
   */
  public function getNumeroNota() {
    return $this->iNumeroNota;
  }

  /**
   * Define o Numero da Nota Fiscal
   * @param $iNumeroNota
   */
  public function setNumeroNota($iNumeroNota) {
    $this->iNumeroNota = $iNumeroNota;
  }

  /**
   * Retorna o Valor do Serviço
   * @return 
   */
  public function getValorServico() {
    return $this->nValorServico;
  }

  /**
   * Define o Valor do Serviço
   * @param $nValorServico
   */
  public function setValorServico($nValorServico) {
    $this->nValorServico = $nValorServico;
  }

  /**
   * Retorna o Valor da Retenção na Nota Fiscal
   * @return 
   */
  public function getValorRetencao() {
    return $this->nValorRetencao;
  }

  /**
   * Define o Valor Retido na Nota Fiscal
   * @param $nValorRetencao
   */
  public function setValorRetencao($nValorRetencao) {
    $this->nValorRetencao = $nValorRetencao;
  }

  /**
   * Retorna o Valor da Aliquota da Nota
   * @return 
   */
  public function getAliquota() {
    return $this->nAliquota;
  }

  /**
   * Define o Valor da Aliquota da NOta Fiscal
   * @param $nAliquota
   */
  public function setAliquota($nAliquota) {
    $this->nAliquota = $nAliquota;
  }

  /**
   * Retorna o Valor deuzido da NOta
   * @return 
   */
  public function getValorDeducao() {
    return $this->nValorDeducao;
  }

  /**
   * DEfine o Valor deduzido da NOta
   * @param $nValorDeducao
   */
  public function setValorDeducao($nValorDeducao) {
    $this->nValorDeducao = $nValorDeducao;
  }

  /**
   * Retorna o Valor Base da Nota 
   * @return 
   */
  public function getValorBase() {
    return $this->nValorBase;
  }

  /**
   * Define o Valor Base da Nota
   * @param $nValorBase
   */
  public function setValorBase($nValorBase) {
    $this->nValorBase = $nValorBase;
  }

  /**
   * Retorna o Valor do imposto
   * @return 
   */
  public function getValorImposto() {
    return $this->nValorImposto;
  }

  /**
   * Define o Valor do Imposto
   * @param $nValorImposto
   */
  public function setValorImposto($nValorImposto) {
    $this->nValorImposto = $nValorImposto;
  }

  /**
   * Retorna a Descriacao do serviço da Nota
   * @return 
   */
  public function getDescricaoServico() {
    return $this->sServico;
  }

  /**
   * Define a Descricao do Servico da NOta
   * @param $sServico
   */
  public function setDescricaoServico($sServico) {
    $this->sServico = $sServico;
  }

  /**
   * Retorna as Observações da Nota
   * @return 
   */
  public function getObservacoes() {
    return $this->sObservacoes;
  }

  /**
   * Define as Observações da Nota
   * @param $sObservacoes
   */
  public function setObservacoes($sObservacoes) {
    $this->sObservacoes = $sObservacoes;
  }
  
  /**
   * Retorna o Codigo da Nota (sequencial)
   *
   * @access public
   * @return integer
   */
  public function getCodigoNotaPlanilha () {
    return  $this->iCodigoNotaPlanilha;
  }
   
  /**
   * Retorna do Código da Liquidacao
   * 
   * @access public
   * @return integer
   */
  public function getCodigoNotaLiquidacao() {
    return  $this->iCodigoNotaLiquidacao;
  }

  /**
   * Define o Código da Liquidacao
   * 
   * @param  integer $iNotaLiquidacao 
   * @access public
   * @return void
   */
  public function setCodigoNotaLiquidacao( $iNotaLiquidacao ) {
    $this->iCodigoNotaLiquidacao = $iNotaLiquidacao;
  }
}