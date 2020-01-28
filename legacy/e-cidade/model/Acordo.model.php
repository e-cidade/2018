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

require_once(modification('model/empenho/AutorizacaoEmpenho.model.php'));

/**
 * controle de acordos/contratos
 * @package Contratos
 */
class Acordo  {

  const ORIGEM_PROCESSO_COMPRAS          = 1;
  const ORIGEM_LICITACAO                 = 2;
  const ORIGEM_MANUAL                    = 3;
  const ORIGEM_EMPENHO                   = 6;
  const TIPO_ADITAMENTO_REEQUILIBRIO     = 2;
  const TIPO_ADITAMENTO_QUANTIDADE_VALOR = 4;

  const CONTRATO_HOMOLOGADO              = 4;
  const CONTRATO_PARALISADO              = 5;

  const TIPO_INSTRUMENTO_TERMO_ADESAO         = 1;
  const TIPO_INSTRUMENTO_CONTRATO             = 2;
  const TIPO_INSTRUMENTO_TERMO_FOMENTO        = 3;
  const TIPO_INSTRUMENTO_TERMO_PARCERIA       = 4;
  const TIPO_INSTRUMENTO_TERMO_CREDENCIAMENTO = 5;
  const TIPO_INSTRUMENTO_TERMO_COLABORACAO    = 6;

  /**
   * Caminho das mensagens do model
   */
  const MENSAGENS = "patrimonial.contratos.Acordo.";

  /**
   * Codigo do acordo;
   *
   * @var integer
   */
  protected $iCodigoAcordo;

  /**
   * Data inicial do acordo acordo.ac16_datainicio
   *
   * @var string
   */
  protected $sDataInicial;

  /**
   * Data final do acordo acordo.ac16_datatermino
   *
   * @var string
   */
  protected $sDataFinal;

  /**
   * Contratado (numcgm) ac16_contratado
   *
   * @var integer
   */
  protected $oContratado;

  /**
   * Penalidades prevista no contrato
   *
   * @var acordoPenalidade collection
   */

  protected $aPenalidades = array();

  /**
   * Empenhos vinculados ao acordo
   * @var array
   * @access protected
   */
  protected $aEmpenhos    = array();

  /**
   * Licitacoes vinculadas ao acordo
   * @var array
   * @access protected
   */
  protected $aLicitacoes  = array();


  /**
   * Processos de compras vinculados ao acordo
   * @var array
   * @access protected
   */
  protected $aProcessosDeCompras = array();

  /**
   * garantias previstas no contrato
   *
   * @var acordoGarantia collection
   */
  protected $aGarantias   = array();

  /**
   * Codigo do grupo de contrato ac16_acordogrupo
   *
   * @var integer
   */
  protected $iGrupo;

  /**
   * Codigo do departamento que gerou o contrato
   *
   * @var integer
   */
  protected $iDepartamento;

  /**
   * instituicao que gerou o contrato
   *
   * @var integer
   */
  protected $iInstit;

  /**
   * Ano de origem do contrato;
   *
   * @var integer
   */
  protected $iAno;

  /**
   * Data de assinatura do contrato.
   *
   * @var string
   */
  protected $sDataAssinatura;

  /**
   * texto com o objeto do contrato
   *
   * @var string
   *
   */
  protected $sObjeto;

  /**
   * resumo do cobjeto do contrato
   *
   * @var string
   */
  protected $sResumoObjeto;

  /**
   * Comissão de vistoria do contrato
   *
   * @var acordoComissão
   */
  protected $oComissao;

  /**
   * Departamento Responsável pelo contrato
   *
   * @var integer
   */
  protected $iDepartamentoResponsavel;

  /**
   * numero da lei
   *
   * @var string
   */
  protected $sLei;

  /**
   * número do processo
   *
   * @var string
   */
  protected $sProcesso;

  /**
   * situação do contrato
   *
   * @var integer
   */
  protected $iSituacao;

  /**
   * Numero do contrato no grupo
   *
   * @var integer
   */
  protected $iNumero;

  /**
   * Numero anual do contrato
   *
   * @var integer
   */
  protected $iNumeroAcordo;

  /**
   * Origem do contrato
   *
   * @var integer
   */
  protected $iOrigem;

  /**
   * dotacoes do contrato
   *
   * @var array
   */
  protected $aDotacoes = array();

  /**
   * Quantidade de renovacoes em dias/meses
   *
   * @var integer
   */
  protected $iQuantidadeRenovacao;

  /**
   * Tipo da renovacao 1 = Meses 2 Dias
   *
   * @var integer
   */
  protected $iTipoRenovacao;

  protected $oUltimaPosicao;

  /**
   * contrato em caracter emergencial
   *
   * @var bool
   */
  protected $lEmergencial;

  protected $aPosicoes = array();
  /**
   * Retorna Descricao do tipo
   */
  protected $sDesricaoTipo;
  /**
   * Retorna Descricao da Situacao
   */
  protected $sDesricaoSituacao;

  /**
   * @var array
   */
  protected $aDocumento = array();

  /**
   * se o acordo tera periodos de mes comercial
   */
  protected $lPeriodoComercial;

  /**
   * Valor referente ao campo ac16_qtdperiodo
   * Unidade em (dia/mês) de acordo com $iTipoUnidadeTempoVigencia
   * @var integer
   */
  private $iQtdPeriodoVigencia;

  /**
   * Represta o campo ac16_tipounidtempoperiodo que define:
   * 1 - Mês
   * 2 - Dia
   * @var integer
   */
  private $iTipoUnidadeTempoVigencia;

  /**
   * Categoria do acordo
   * @var integer
   */
  private $iCategoriaAcordo;

  /**
   * Data inicial do periodo de vigencia original
   * @var DBDate
   */
  private $dtDataInicialVigenciaOriginal;

  /**
   * Data Final do periodo de vigencia original
   * @var DBDate
   */
  private $dtDataFinalVigenciaOriginal;

  /**
   * Paralizacoes do Contrato
   * @var AcordoParalisacao[]
   */
  private $aParalisacoes = array();

  /**
   * Código da classificacao do acordo
   */
  private $iClassificacao;

  /**
   * Classificacao do acordo
   * @var AcordoClassificacao
   */
  private $oClassificacao;

  /**
   * valor do contrato
   * @var number
   */
  private $nValorContrato;

  /**
   * Tipo de Instrumento
   * @var integer
   */
  private $iTipoInstrumento;

  /**
   * Indica se o acordo depende da ordem de início
   * @var boolean
   */
  private $lDependeOrdemInicio;


  /**
   * @var AcordoEvento[]
   */
  private $aEventos = array();
  /**
   * Construtor
   * @param integer $iCodigoAcordo
   */
  public function __construct($iCodigoAcordo = null) {

    if (!empty($iCodigoAcordo)) {

      $this->iCodigoAcordo = $iCodigoAcordo;

      $oDaoAcordo = new cl_acordo;
      $sSqlAcordo = $oDaoAcordo->sql_query_completo($iCodigoAcordo, "acordo.*, ac02_descricao");
      $rsAcordo   = $oDaoAcordo->sql_record($sSqlAcordo);

      if ($oDaoAcordo->numrows > 0) {

        $oDadosAcordo  = db_utils::fieldsMemory($rsAcordo, 0);
        $this->setAno($oDadosAcordo->ac16_anousu);
        $this->setInstit($oDadosAcordo->ac16_instit);
        $this->setComissao(new AcordoComissao($oDadosAcordo->ac16_acordocomissao));
        $oContratado = CgmFactory::getInstanceByCgm($oDadosAcordo->ac16_contratado);
        $this->setContratado($oContratado);
        $iDepartamentoResponsavel = $oDadosAcordo->ac16_deptoresponsavel;
        $this->setDepartamentoResponsavel($iDepartamentoResponsavel);
        $this->setOrigem($oDadosAcordo->ac16_origem);
        $this->setNumero($oDadosAcordo->ac16_numero);
        $this->setGrupo($oDadosAcordo->ac16_acordogrupo);
        $this->setDataAssinatura(db_formatar($oDadosAcordo->ac16_dataassinatura, "d"));
        $this->setDataInicial(db_formatar($oDadosAcordo->ac16_datainicio, "d"));
        $this->setDataFinal(db_formatar($oDadosAcordo->ac16_datafim, "d"));
        $this->setDepartamento($oDadosAcordo->ac16_coddepto);
        $this->setLei($oDadosAcordo->ac16_lei);
        $this->setProcesso($oDadosAcordo->ac16_numeroprocesso);
        $this->setObjeto($oDadosAcordo->ac16_objeto);
        $this->setResumoObjeto($oDadosAcordo->ac16_resumoobjeto);
        $this->setSituacao($oDadosAcordo->ac16_acordosituacao);
        $this->setProcesso($oDadosAcordo->ac16_numeroprocesso);
        $this->setQuantidadeRenovacao($oDadosAcordo->ac16_qtdrenovacao);
        $this->setTipoRenovacao($oDadosAcordo->ac16_tipounidtempo);
        $this->setValorContrato($oDadosAcordo->ac16_valor);
        $this->setTipoInstrumento($oDadosAcordo->ac16_tipoinstrumento);
        $this->setDependeOrdemInicio($oDadosAcordo->ac16_dependeordeminicio == 't');

        $this->iQtdPeriodoVigencia       = $oDadosAcordo->ac16_qtdperiodo;
        $this->iTipoUnidadeTempoVigencia = $oDadosAcordo->ac16_tipounidtempoperiodo;
        $this->iCategoriaAcordo          = $oDadosAcordo->ac16_acordocategoria;
        $this->iClassificacao            = $oDadosAcordo->ac16_acordoclassificacao;
        $this->iNumeroAcordo             = $oDadosAcordo->ac16_numeroacordo;

        $lComercial = false;
        if ($oDadosAcordo->ac16_periodocomercial == 't') {
          $lComercial = true;
        }

        $this->setPeriodoComercial($lComercial);

        unset($oDadosAcordo);
      }
    }
  }

  /**retorna a descrição da situação
   * return string
   */
  public function getDescricaoSituacao() {

    $this->sDesricaoSituacao = "";
    $oDaoAcordo = db_utils::getDao("acordo");
    $sSql       = $oDaoAcordo->sql_query_completo($this->getCodigoAcordo(),"ac17_descricao");
    $rsSql      = $oDaoAcordo->sql_record($sSql);
    if ($oDaoAcordo->numrows > 0) {

      $this->sDesricaoSituacao = db_utils::fieldsMemory($rsSql,0)->ac17_descricao;
    }
    return $this->sDesricaoSituacao;
  }

  /**retorna a descrição do tipo
   * return string
   */
  public function getDescricaoTipo() {

    $this->sDesricaoTipo = "";
    $oDaoAcordo = db_utils::getDao("acordo");
    $sSql       = $oDaoAcordo->sql_query_completo($this->getCodigoAcordo(),"ac04_descricao");
    $rsSql      = $oDaoAcordo->sql_record($sSql);
    if ($oDaoAcordo->numrows > 0) {

      $this->sDesricaoTipo = db_utils::fieldsMemory($rsSql,0)->ac04_descricao;
    }
    return $this->sDesricaoTipo;
  }

  /**
   * @return acordoGarantia
   */
  public function getGarantias() {

    if (count($this->aGarantias) == 0) {

      $oDaoGarantias =  db_utils::getDao("acordoacordogarantia");
      $sSqlGarantias = $oDaoGarantias->sql_query(null,
                                                      "ac12_acordogarantia,
                                                       ac12_texto",
                                                      "ac12_sequencial",
                                                      "ac12_acordo={$this->getCodigoAcordo()}"
                                                     );
      $rsGarantias   =  $oDaoGarantias->sql_record($sSqlGarantias);
      if ($oDaoGarantias->numrows > 0) {

        for ($i = 0; $i < $oDaoGarantias->numrows; $i++) {

          $oGarantiaPadrao    = db_utils::fieldsMemory($rsGarantias, $i);
          $oGarantia          = new AcordoGarantia($oGarantiaPadrao->ac12_acordogarantia);
          $oGarantia->setTextoPadrao($oGarantiaPadrao->ac12_texto);
          $this->aGarantias[] = $oGarantia;
        }
      }
    }
    return $this->aGarantias;
  }

  /**
   * retorna as penalidades do acorsdo
   * @return acordoPenalidade
   */
  public function getPenalidades() {

    if (count($this->aPenalidades) == 0) {

      $oDaoPenalidades =  db_utils::getDao("acordoacordopenalidade");
      $sSqlPenalidades = $oDaoPenalidades->sql_query(null,
                                                      "ac15_acordopenalidade,
                                                       ac15_texto",
                                                      "ac15_sequencial",
                                                      "ac15_acordo={$this->getCodigoAcordo()}"
                                                     );
      $rsPenalidades   =  $oDaoPenalidades->sql_record($sSqlPenalidades);
      if ($oDaoPenalidades->numrows > 0) {

        for ($i = 0; $i < $oDaoPenalidades->numrows; $i++) {

          $oPenalidadePadrao    = db_utils::fieldsMemory($rsPenalidades, $i);
          $oPenalidade          = new AcordoPenalidade($oPenalidadePadrao->ac15_acordopenalidade);
          $oPenalidade->setTextoPadrao($oPenalidadePadrao->ac15_texto);
          $this->aPenalidades[] = $oPenalidade;
        }
      }
    }
    return $this->aPenalidades;
  }


  /**
   * @return integer
   */
  public function getAno() {

    return $this->iAno;
  }

  /**
   * @param integer $iAno
   * @return Acordo
   */
  public function setAno($iAno) {

    $this->iAno = $iAno;
    return $this;
  }

  /**
   * retorna o codigo do acordo
   * @return integer
   */
  public function getCodigoAcordo() {
    return $this->iCodigoAcordo;
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigoAcordo;
  }

  /**
   * define o codigo do acordo
   * @param integer $iCodigoAcordo
   */
  protected  function setCodigoAcordo($iCodigoAcordo) {
    $this->iCodigoAcordo = $iCodigoAcordo;
  }

  /**
   * @return CgmBase
   */
  public function getContratado() {

    return $this->oContratado;
  }

  /**
   * define o cgm que foi contratado
   * @param integer $iContratato
   * @return Acordo
   */
  public function setContratado(CgmBase $oContratato) {

    $this->oContratado = $oContratato;
    return $this;
  }

  /**
   * @return integer
   */
  public function getDepartamento() {

    return $this->iDepartamento;
  }

  /**
   * Define o departamento do contrato
   * @param integer $iDepartamento
   * @return Acordo
   */
  public function setDepartamento($iDepartamento) {

    $this->iDepartamento = $iDepartamento;
    return $this;
  }

  /**
   * Retorna o Departamento do responsavel pelo contrato.
   * @return integer
   */
  public function getDepartamentoResponsavel() {

    return $this->iDepartamentoResponsavel;
  }

  /**
   * Define o departamento responsável pela gestao do contrato.
   * @param integer $iDepartamentoResponsavel
   * @return Acordo
   */
  public function setDepartamentoResponsavel($iDepartamentoResponsavel) {

    $this->iDepartamentoResponsavel = $iDepartamentoResponsavel;
    return $this;
  }

  /**
   * @return integer
   */
  public function getGrupo() {

    return $this->iGrupo;
  }

  /**
   * define do grupo do contrato
   * @param integer $iGrupo
   * @return Acordo
   */
  public function setGrupo($iGrupo) {

    $this->iGrupo = $iGrupo;
    return $this;
  }

  /**
   * retorna a instituição do contrato
   * @return integer
   */
  public function getInstit() {

    return $this->iInstit;
  }

  /**
   * @param integer $iInstit
   * @return Acordo
   */
  public function setInstit($iInstit) {

    $this->iInstit = $iInstit;
    return $this;
  }

  /**
   * retorna a situacao atual do contrato
   * @return integer
   */
  public function getSituacao() {

    return $this->iSituacao;
  }

  /**
   * Define a situacao do contrato
   * @param integer $iSituacao
   * @return Acordo
   */
  public function setSituacao($iSituacao) {

    $this->iSituacao = $iSituacao;
    return $this;
  }

  /**
   * @return acordoComissao
   */
  public function getComissao() {

    return $this->oComissao;
  }

  /**
   * define a comissao de vistoria do acordo
   * @param acordoComissao $oComissao
   * @return Acordo
   */
  public function setComissao(AcordoComissao $oComissao) {

    $this->oComissao = $oComissao;
    return $this;
  }

  /**
   * @return string
   */
  public function getDataAssinatura() {

    return $this->sDataAssinatura;
  }

  /**
   * @param string $sDataAssinatura
   * @return Acordo
   */
  public function setDataAssinatura($sDataAssinatura) {

    $this->sDataAssinatura = $sDataAssinatura;
    return $this;
  }

  /**
   * @return string
   */
  public function getDataFinal() {

    return $this->sDataFinal;
  }

  /**
   * @param string $sDataFinal
   * @return Acordo
   */
  public function setDataFinal($sDataFinal) {

    $this->sDataFinal = $sDataFinal;
    return $this;
  }

  /**
   * @return string
   */
  public function getDataInicial() {

    return $this->sDataInicial;
  }

  /**
   * @param string $sDataInicial
   * @return Acordo
   */
  public function setDataInicial($sDataInicial) {

    $this->sDataInicial = $sDataInicial;
    return $this;
  }

  /**
   * retorna a lei do contrato
   * @return string
   */
  public function getLei() {

    return $this->sLei;
  }

  /**
   * define a lei do contrato
   * @param string $sLei
   * @return Acordo
   */
  public function setLei($sLei) {

    $this->sLei = $sLei;
    return $this;
  }

  /**
   * retorna o processo do contrato
   *
   * @return string
   */
  public function getProcesso() {
    return $this->sProcesso;
  }

  /**
   * define o processo do contrato
   *
   * @param string $sProcesso
   * @return Acordo
   */
  public function setProcesso($sProcesso) {

    $this->sProcesso = $sProcesso;
    return $this;
  }

  /**
   * @return string
   */
  public function getObjeto() {
    return $this->sObjeto;
  }

  /**
   * define o objeto do contrato
   * @param string $sObjeto
   * @return Acordo
   */
  public function setObjeto($sObjeto) {

    $this->sObjeto = $sObjeto;
    return $this;
  }

  /**
   * resumo do objeto
   * @return string
   */
  public function getResumoObjeto() {

    return $this->sResumoObjeto;
  }

  /**
   * Define o resumo do contrato
   * @param string $sResumoObjeto
   * @return Acordo
   */
  public function setResumoObjeto($sResumoObjeto) {

    $this->sResumoObjeto = $sResumoObjeto;
    return $this;
  }
  /**
   * @return integer
   */
  public function getOrigem() {

    return $this->iOrigem;
  }

  /**
   * @param integer $iOrigem
   * @return Acordo
   */
  public function setOrigem($iOrigem) {

    $this->iOrigem = $iOrigem;
    return $this;
  }

  /**
   * @return integer
   */
  public function getQuantidadeRenovacao() {

    return $this->iQuantidadeRenovacao;
  }

  /**
   * @param integer $iQuantidadeRenovacao
   * @return Acordo
   */
  public function setQuantidadeRenovacao($iQuantidadeRenovacao) {

    $this->iQuantidadeRenovacao = $iQuantidadeRenovacao;
    return $this;
  }

  /**
   * @return integer
   */
  public function getTipoRenovacao() {
    return $this->iTipoRenovacao;
  }

  /**
   * @param integer $iTipoRenovacao
   * @return Acordo
   */
  public function setTipoRenovacao($iTipoRenovacao) {

    $this->iTipoRenovacao = $iTipoRenovacao;
    return $this;
  }
  /**
   * @param bool $lEmergencial
   * @return Acordo
   */
  public function setEmergencial($lEmergencial) {

    $this->lEmergencial = $lEmergencial;
    return $this;
  }

  /**
   * verifica se o contrato tem caracter emergencial
   *
   * @return unknown
   */
  function isEmergencial() {

    return $this->lEmergencial;
  }

  /**
   * @return boolean
   */
  public function getPeriodoComercial() {
    return $this->lPeriodoComercial;
  }

  /**
   * Seta se o os períodos do acordo vão ser com base em meses comerciais
   * @param boolean $lPeriodoComercial
   * @return Acordo
   */
  public function setPeriodoComercial($lPeriodoComercial) {

    $this->lPeriodoComercial = $lPeriodoComercial;
    return $this;
  }

  /**
   * Retorna a data inicial do periodo de vigencia original
   * @return DBDate
   */
  public function getDataInicialVigenciaOriginal () {

    if (empty($this->DataInicialVigenciaOriginal)) {
      $this->buscaVigenciaOriginal();
    }
    return $this->DataInicialVigenciaOriginal;
  }

  /**
   * Retorna a data final do periodo de vigencia original
   * @return DBDate
   */
  public function getDataFinalVigenciaOriginal () {

    if (empty($this->DataInicialVigenciaOriginal)) {
      $this->buscaVigenciaOriginal();
    }
    return $this->DataFinalVigenciaOriginal;
  }

  /**
   * adiciona uma penalidade ao contrato
   * @param acordoPenalidade $aPenalidades
   * @param string $sTexto texto da penalidade
   *
   * @return Acordo
   */
  public function adicionarPenalidades(acordoPenalidade $oPenalidade, $sTexto = '') {

    $lAlterar  = false;
    foreach ($this->getPenalidades() as $oPenalidadeCadastrada) {

      if ($oPenalidadeCadastrada->getCodigo() == $oPenalidade->getCodigo()) {
        $oPenalidade = $oPenalidadeCadastrada;
        $lAlterar = true;
      }
    }
    if ($sTexto != '') {
      $oPenalidade->setTextoPadrao($sTexto);
    }
    if (!$lAlterar) {
      $this->aPenalidades[] = $oPenalidade;
    }
    return $this;
  }

  /**
   * @param acordoGarantia $aGarantias
   * @return Acordo
   */
  public function adicionarGarantias(acordoGarantia $oGarantia, $sTexto = '') {


   $lAlterar  = false;
   foreach ($this->getGarantias() as $oGarantiaCadastrada) {

      if ($oGarantiaCadastrada->getCodigo() == $oGarantia->getCodigo()) {

        $oGarantia = $oGarantiaCadastrada;
        $lAlterar  = true;
      }
    }
   if ($sTexto != '') {
      $oGarantia->setTextoPadrao($sTexto);
    }
    if (!$lAlterar) {
      $this->aGarantias[$oGarantia->getCodigo()] = $oGarantia;
    }
    return $this;
  }

  public function removerGarantia($iGarantia) {

    if (empty($iGarantia)) {

      throw new Exception("Garantia não informada");
    }
    $aGarantias = $this->getGarantias();
    $iTotalGarantias = count($aGarantias);
    for ($i = 0; $i < $iTotalGarantias; $i++) {

      if ($this->aGarantias[$i]->getCodigo() == $iGarantia) {
        array_splice($this->aGarantias, $i, 1);
        break;
      }
    }
    return $this;
  }

  /**
   * retorna o numero do contrato no grupo
   *
   * @return integer
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * Seta o numero do contrato no grupo
   *
   * @param integer $iNumero
   * @return Acordo
   */
  public function setNumero($iNumero) {

    $this->iNumero = $iNumero;
    return $this;
  }

  /**
   * remove a penalidade do acordo
   *
   * @param integer $iPenalidade codigo da penalidade
   */
  public function removerPenalidade($iPenalidade) {

    if (empty($iPenalidade)) {

      throw new Exception("penalidade não informada");
    }
    $aPenalidades = $this->getPenalidades();
    $iTotalPenalidades = count($aPenalidades);
    for ($i = 0; $i < $iTotalPenalidades; $i++) {

      if ($this->aPenalidades[$i]->getCodigo() == $iPenalidade) {

        array_splice($this->aPenalidades, $i, 1);
        break;
      }
    }
    return $this;
  }

  /**
     * Método que verifica se existe um acordo de mesmo número/tipo de instrumento/ano.
     * @return boolean true
     * @throws DBException|BusinessException
     */
    protected function verificarChave()
    {
        $where = array(
            "ac16_numero = '{$this->iNumero}'",
            'ac16_anousu = ' . $this->iAno,
            'ac16_tipoinstrumento = ' . $this->iTipoInstrumento,
            'ac16_instit=' . $this->iInstit
        );

        if ($this->iCodigoAcordo) {
            $where[] = 'ac16_sequencial <> ' . $this->iCodigoAcordo;
        }

        $where = implode(' AND ', $where);
        $daoAcordo = new cl_acordo;
        $sqlBuscaAcordo = $daoAcordo->sql_query_file(null, '*', null, $where);
        $resBuscaAcordo = db_query($sqlBuscaAcordo);

        if (!$resBuscaAcordo) {
            throw new DBException('Ocorreu um erro ao consultar os dados do acordo.');
        }

        if (pg_num_rows($resBuscaAcordo)) {
            throw new BusinessException('Existe um contrato com o mesmo número, ano e tipo de instrumento. Para incluir um contrato, algum destes dados informados deve ser modificado.');
        }

        return true;
    }

    /**
   * Salva Contrato na Inclusão ou em suas Alterações
   */
  public function save() {

        $this->verificarChave();

    $this->salvarAlteracoesContrato();
    $dtDataInicial = $this->getDataInicial();
    $dtDataFinal   = $this->getDataFinal();
    $oPosicao      = $this->getUltimaPosicao();

    $oDataInicial  = new DBDate($dtDataInicial);
    $oDataFinal    = new DBDate($dtDataFinal);
    $this->salvarVigencia($oPosicao, $oDataInicial, $oDataFinal);
  }

  /**
   * Método que inclui vigencia para posição atual do acordo
     * @param $oPosicao
     * @param $oDataInicio
     * @param $oDataFim
     * @throws Exception
   */
  private function salvarVigencia($oPosicao, $oDataInicio, $oDataFim) {

    $oDaoAcordoVigencia = db_utils::getDao("acordovigencia");
    $oDaoAcordoVigencia->excluir(null, "ac18_acordoposicao={$oPosicao->getCodigo()}");

    if ($oDaoAcordoVigencia->erro_status == 0) {
      throw new Exception("Erro ao definir vigência do contrato.\n{$oDaoAcordoVigencia->erro_msg}");
    }

    $oDaoAcordoVigencia->ac18_acordoposicao = $oPosicao->getCodigo();
    $oDaoAcordoVigencia->ac18_ativo         = "true";
    $oDaoAcordoVigencia->ac18_datainicio    = $oDataInicio->getDate(DBDate::DATA_EN);
    $oDaoAcordoVigencia->ac18_datafim       = $oDataFim->getDate(DBDate::DATA_EN);
    $oDaoAcordoVigencia->incluir(null);

    if ($oDaoAcordoVigencia->erro_status == 0) {
      throw new Exception("Erro ao definir vigência do contrato.\n{$oDaoAcordoVigencia->erro_msg}");
    }
  }

  /**
   * persite os dados do acordo na base
   * @return Acordo
   */
  private function salvarAlteracoesContrato() {

    $oDaoAcordo = new cl_acordo;
    $oDaoAcordo->ac16_acordogrupo          = $this->getGrupo();
    $oDaoAcordo->ac16_instit               = $this->getInstit();
    $oDaoAcordo->ac16_coddepto             = $this->getDepartamento();
    $oDaoAcordo->ac16_contratado           = $this->getContratado()->getCodigo();
    $oDaoAcordo->ac16_acordosituacao       = $this->getSituacao();
    $oDaoAcordo->ac16_deptoresponsavel     = $this->getDepartamentoResponsavel();
    $oDaoAcordo->ac16_dataassinatura       = "".implode("-", array_reverse(explode("/", $this->getDataAssinatura())));
    $oDaoAcordo->ac16_datainicio           = "".implode("-", array_reverse(explode("/", $this->getDataInicial())));
    $oDaoAcordo->ac16_datafim              = "".implode("-", array_reverse(explode("/", $this->getDataFinal())));
    $oDaoAcordo->ac16_lei                  = $this->getLei();
    $oDaoAcordo->ac16_numeroprocesso       = $this->getProcesso();
    $oDaoAcordo->ac16_numero               = $this->getNumero();
    $oDaoAcordo->ac16_objeto               = pg_escape_string($this->getObjeto());
    $oDaoAcordo->ac16_resumoobjeto         = pg_escape_string($this->getResumoObjeto());
    $oDaoAcordo->ac16_acordocomissao       = $this->getComissao()->getCodigo();
    $oDaoAcordo->ac16_origem               = $this->getOrigem();
    $oDaoAcordo->ac16_qtdrenovacao         = $this->getQuantidadeRenovacao();
    $oDaoAcordo->ac16_tipounidtempo        = $this->getTipoRenovacao();
    $oDaoAcordo->ac16_periodocomercial     = $this->getPeriodoComercial();
    $oDaoAcordo->ac16_acordocategoria      = $this->getCategoriaAcordo();
    $oDaoAcordo->ac16_tipounidtempoperiodo = $this->getTipoUnidadeTempoVigencia();
    $oDaoAcordo->ac16_qtdperiodo           = $this->getQtdPeriodoVigencia();
    $oDaoAcordo->ac16_acordoclassificacao  = $this->getClassificacao()->getCodigo();
    $oDaoAcordo->ac16_valor                = $this->getValorContrato();
    $oDaoAcordo->ac16_numeroacordo         = $this->getNumeroAcordo();
    $oDaoAcordo->ac16_tipoinstrumento      = $this->getTipoInstrumento();
    $oDaoAcordo->ac16_dependeordeminicio   = $this->getDependeOrdemInicio() ? 't' : 'f';
    $iCodigoAcordo                         = $this->getCodigoAcordo();

    /**
     * Alteracao
     */
    if (!empty($iCodigoAcordo)) {

      $oDaoAcordo->ac16_sequencial   = $this->getCodigoAcordo();
      $oDaoAcordo->alterar($this->getCodigoAcordo());
      $oPosicao = $this->getUltimaPosicao();
      $oPosicao->setPosicaoPeriodo($this->getDataInicial(), $this->getDataFinal(), $this->getPeriodoComercial())->save();

    } else {


      /**
       * Inclusao
       */
      $oDaoAcordo->ac16_anousu = $this->getAno();
      /**
       * validamos a númeracao do acordo.
       * não podera ser um numero menor ou igual ao maior numero de contrato do grupo,
       * dentro da instiuição.
       */
      $sWhereNumeracao  = "cast(ac16_numero as integer) = {$this->getNumero()} ";
      $sWhereNumeracao .= " and ac16_acordogrupo  = {$this->getGrupo()}  ";
      $sWhereNumeracao .= " and ac16_instit       = {$this->getInstit()} ";
      $sWhereNumeracao .= " and ac16_anousu       = {$this->getAno()} ";
      $sSqlValidaNumero = $oDaoAcordo->sql_query_file(null,
                                                      "ac16_numero,
                                                      ac16_sequencial",
                                                      null,
                                                      $sWhereNumeracao
                                                     );
      $rsValidaNumeracao = $oDaoAcordo->sql_record($sSqlValidaNumero);
      if ($oDaoAcordo->numrows > 0) {

        $sErroMensagem  = "A númeração informada para esse contrato é inválida.\n";
        $sErroMensagem .= "Já existem contratos com essa númeração.\n";
        $sErroMensagem .= "Número sugerido para esse contrato: ".$this->getProximoNumeroContrato($this->getGrupo());
        throw new Exception($sErroMensagem);
      }

      $oDaoAcordo->ac16_numeroacordo = Acordo::getProximoNumeroDoAno($this->iAno, $this->iInstit);
      $oDaoAcordo->incluir(null);
      $this->setCodigoAcordo($oDaoAcordo->ac16_sequencial);

      if ($oDaoAcordo->erro_status == 0) {
        throw  new Exception("Erro ao salvar acordo.\nErro: {$oDaoAcordo->erro_msg}");
      }
      /**
       * Atualizamos o número do contrato no grupo
       */
      $oDaoGrupoContrato  = db_utils::getDao("acordogruponumeracao");
      $sWhere             = "ac03_anousu = {$this->getAno()} and ac03_instit = {$this->getInstit()} ";
      $sWhere            .= "and ac03_acordogrupo={$this->getGrupo()}";
      $sSqlNumeracao      = $oDaoGrupoContrato->sql_query_file(null, "*", null, $sWhere);
      $rsNumeracao        = $oDaoGrupoContrato->sql_record($sSqlNumeracao);
      if ($oDaoGrupoContrato->numrows == 0) {

        $sMensagem = "Númeraçao para o grupo {$this->getGrupo()} não foi encontrado.\nInclusão do contrato abortada";
        throw new Exception($sMensagem);
      }

      $oNumeracaoGrupo = db_utils::fieldsMemory($rsNumeracao, 0);
      $oDaoGrupoContrato->ac03_sequencial = $oNumeracaoGrupo->ac03_sequencial;
      $oDaoGrupoContrato->ac03_numero     = $this->getNumero();
      $oDaoGrupoContrato->alterar($oNumeracaoGrupo->ac03_sequencial);
      if ($oDaoGrupoContrato->erro_status == 0) {

        $sMensagem  = "Houve um erro ao atualizar númeração.\nVerifique o cadastro do grupo escolhido para esse contrato ";
        $sMensagem .= "e sua numeração\nErro Técnico: {$oDaoGrupoContrato->erro_msg} ";
        throw new Exception($sMensagem);
      }
      /**
       * incluimos uma movimentação para o contrato do tipo 1 - Inclusão do Contrato
       */
      $oDaoAcordoMovimentacao  = db_utils::getDao("acordomovimentacao");
      $oDaoAcordoMovimentacao->ac10_datamovimento          = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoAcordoMovimentacao->ac10_acordomovimentacaotipo = 1;
      $oDaoAcordoMovimentacao->ac10_acordo                 = $this->getCodigoAcordo();
      $oDaoAcordoMovimentacao->ac10_hora                   = db_hora();
      $oDaoAcordoMovimentacao->ac10_id_usuario             = db_getsession("DB_id_usuario");
      $oDaoAcordoMovimentacao->ac10_obs                    = "Inclusão do contrato";
      $oDaoAcordoMovimentacao->incluir(null);
      if ($oDaoAcordoMovimentacao->erro_status == 0) {

        $sMensagem  = "Houve um erro ao inicializar movimentação do contrato.\n inclusão do contrato cancelada";
        $sMensagem .= "\nErro Técnico: {$oDaoGrupoContrato->erro_msg} ";
        throw new Exception($sMensagem);
      }
      if ($this->getDataAssinatura() != "") {

         require_once(modification("model/AcordoAssinatura.model.php"));
         $oAssinatura  = new AcordoAssinatura();
         $oAssinatura->setDataMovimento(implode("-", array_reverse(explode("/", $this->getDataAssinatura()))));
         $oAssinatura->setAcordo($this->getCodigoAcordo());
         $oAssinatura->setObservacao('Assinado em '.$this->getDataAssinatura());
         $oAssinatura->save();
      }

      /**
       * incluimos uma posição inicial para o contrato:
       */
      $oPosicao = new AcordoPosicao();
      $oPosicao->setPosicaoPeriodo($this->getDataInicial(), $this->getDataFinal(), $oDaoAcordo->ac16_periodocomercial);
      $oPosicao->setAcordo($this->iCodigoAcordo);
      $oPosicao->setNumero(1);
      $oPosicao->setTipo(AcordoPosicao::TIPO_INCLUSAO);
      $oPosicao->setData(date("Y-m-d", db_getsession("DB_datausu")));
      $oPosicao->setSituacao(AcordoPosicao::SITUACAO_ATIVO);
      $oPosicao->setEmergencial($this->isEmergencial());
      $oPosicao->setPosicaoPeriodo($this->getDataInicial(), $this->getDataFinal(), $oDaoAcordo->ac16_periodocomercial);
      $oPosicao->save();

      if ($oDaoAcordo->erro_status == 0) {
        throw  new Exception("Erro ao salvar acordo.\nErro: {$oDaoAcordo->erro_msg}");
      }
    }
    /**
     * Salvamos todos as garantias/penalidades vinculadas ao acordo
     */
    $oDaoAcordoPenalidade = db_utils::getDao("acordoacordopenalidade");
    $oDaoAcordoPenalidade->excluir(null, "ac15_acordo={$this->getCodigoAcordo()}");
    foreach ($this->getPenalidades() as $oPenalidade) {

      $oDaoPenalidade                        = db_utils::getDao("acordoacordopenalidade");
      $oDaoPenalidade->ac15_acordo           = $this->getCodigoAcordo();
      $oDaoPenalidade->ac15_texto            = addslashes($oPenalidade->getTextoPadrao());
      $oDaoPenalidade->ac15_acordopenalidade = $oPenalidade->getCodigo();
      $oDaoPenalidade->incluir(null);
      if ($oDaoPenalidade->erro_status == 0) {
        throw new Exception("Erro ao incluir penalidade.\n{$oDaoPenalidade->erro_msg}");
      }
    }

    $oDaoAcordoGarantia = db_utils::getDao("acordoacordogarantia");
    $oDaoAcordoGarantia->excluir(null, "ac12_acordo={$this->getCodigoAcordo()}");
    foreach ($this->getGarantias()as $oGarantia) {

      $oDaoGarantia                      = db_utils::getDao("acordoacordogarantia");
      $oDaoGarantia->ac12_acordo         = $this->getCodigoAcordo();
      $oDaoGarantia->ac12_texto          = $oGarantia->getTextoPadrao();
      $oDaoGarantia->ac12_acordogarantia = $oGarantia->getCodigo();
      $oDaoGarantia->incluir(null);

      if ($oDaoGarantia->erro_status == 0) {
        throw new Exception("Erro ao incluir garantia.\n{$oDaoGarantia->erro_msg}");
      }
    }
    return $this;
  }

  /**
   * retorna o proximo numero de contrato a ser utiliziado pelo grupo.
   *
   * @param integer $iGrupoContrato codigo do grupo
   * @return  integer - Codigo do contrato
   */
  public static function getProximoNumeroContrato($iGrupoContrato) {


    $sWhere   = " ac03_acordogrupo = {$iGrupoContrato}";
    $sWhere  .= " and ac03_anousu  = ".db_getsession("DB_anousu");
    $sWhere  .= " and ac03_instit  = ".db_getsession("DB_instit");
    $oDaoGrupoContrato  = db_utils::getDao("acordogruponumeracao");
    $iNumero = 0;
    $sSqlNumeroContrato = $oDaoGrupoContrato->sql_query_file(null,
                                                             "ac03_numero",
                                                             null,
                                                             $sWhere
                                                             );
    $rsNumeroContrato = $oDaoGrupoContrato->sql_record($sSqlNumeroContrato);
    if ($oDaoGrupoContrato->numrows == 0) {
      throw new Exception("Não existe numeração cadastrada para o grupo {$iGrupoContrato}");
    }

    $iNumero = db_utils::fieldsMemory($rsNumeroContrato, 0)->ac03_numero+1;
    return $iNumero;
  }

  /**
   * Função usada pelo Lazy Load para buscar a vigencia original do contrato
   * preenchendo as propriedades referentes
   * @throws DBException
   */
  private function buscaVigenciaOriginal () {

    $oDaoAcordoVigencia = db_utils::getDao("acordovigencia");
    $sWhere             = "ac26_acordo  = {$this->getCodigoAcordo()}";
    $sOrder             = "ac26_sequencial asc";
    $sSql               = $oDaoAcordoVigencia->sql_query(null,"*", $sOrder, $sWhere );

    $rsResultado        = $oDaoAcordoVigencia->sql_record($sSql);

    if ($oDaoAcordoVigencia->numrows == 0) {
      throw new DBException("Erro Técnico: erro ao buscar dados vigência original do contrato");
    }
    $oStdDados   = db_utils::fieldsMemory($rsResultado, 0);
    $this->DataInicialVigenciaOriginal = new DBDate($oStdDados->ac18_datainicio);
    $this->DataFinalVigenciaOriginal   = new DBDate($oStdDados->ac18_datafim);

  }


  /**
   * Retorna a ultima posicao do acordo
   * @return AcordoPosicao
   */
  function getUltimaPosicao() {


    if ($this->oUltimaPosicao ==  null) {

      $oDaoPosicao = db_utils::GetDao("acordoposicao");
      $sWhere       = "ac26_acordo       = {$this->getCodigoAcordo()}";
      $sWhere      .= "and ac26_situacao = " . AcordoPosicao::SITUACAO_ATIVO;
      $sSqlultimaPosicao = $oDaoPosicao->sql_query_file(null,
                                                        "ac26_sequencial",
                                                        'ac26_numero desc limit 1',
                                                        $sWhere
                                                        );
      $rsPosicao   = $oDaoPosicao->sql_record($sSqlultimaPosicao);
      if ($oDaoPosicao->numrows == 0) {
        throw new Exception("Acordo sem posições definidas.");
      }

      $iCodigoPosicao = db_utils::fieldsMemory($rsPosicao, 0)->ac26_sequencial;
      $this->oUltimaPosicao = new AcordoPosicao($iCodigoPosicao);
    }
    return  $this->oUltimaPosicao;
  }

  /**
   * Retorna o proximo número do aditamento
   * @return mixed
   * @throws Exception
   */
  public function getProximoNumeroAditamento() {

    $oDaoPosicao  = new cl_acordoposicao;
    $sWhere       = "ac26_acordo       = {$this->getCodigoAcordo()}";
    $sSqlultimaPosicao = $oDaoPosicao->sql_query_file(null,
      "coalesce(max(ac26_numero), 0) + 1 as proximo_numero",
      null,
      $sWhere
    );

    $rsPosicao   = $oDaoPosicao->sql_record($sSqlultimaPosicao);
    if ($oDaoPosicao->numrows == 0) {
      throw new Exception("Acordo sem posições definidas.");
    }

    $iProximoNumero = db_utils::fieldsMemory($rsPosicao, 0)->proximo_numero;
    return $iProximoNumero;
  }
  /**
   * retorna todas as posicões do acordo
   *@return AcordoPosicao[]
   */
  function getPosicoes() {

    if (count($this->aPosicoes) == 0) {

      $oDaoAcordoPosicao = db_utils::getDao("acordoposicao");
      $sSqlPosicao       = $oDaoAcordoPosicao->sql_query_file(null,
                                                         "ac26_sequencial",
                                                         'ac26_numero',
                                                         'ac26_acordo='.$this->getCodigoAcordo()
                                                         );

      $rsPosicao  = $oDaoAcordoPosicao->sql_record($sSqlPosicao);
      for ($i = 0; $i < $oDaoAcordoPosicao->numrows; $i++) {

        $oPos = db_utils::fieldsMemory($rsPosicao, $i);
        $oPosicao = new AcordoPosicao($oPos->ac26_sequencial);
        $this->aPosicoes[] = $oPosicao;
        unset($oPos);
      }
    }
    return $this->aPosicoes;
  }

  /**
   * retorna a posicao pelo codigo de cadastro
   *@return AcordoPosicao
   */
  function getPosicaoByCodigo($iCodigo) {

    $oPosicao = false;
    foreach ($this->getPosicoes() as $oPosicao) {

      if ($oPosicao->getCodigo() == $iCodigo){
       break;
      }
    }
    return $oPosicao;
  }

  /**
   * Processa as autorizacoes de empenho do contrato
   *
   * @param array     $aItens
   * @param bool      $lProcessar
   * @param stdClass  $oDadosAutorizacao
   * @return array
   * @throws Exception
   */
  public function processarAutorizacoes (array $aItens, $lProcessar=false, $oDadosAutorizacao = null) {

    $aAutorizacoes   = array();

    foreach ($aItens as $oItem) {

       $oItemContrato = $this->getPosicaoByCodigo($oItem->posicao)->getItemByCodigo($oItem->codigo);

       $aDadosDotacao = $oItemContrato->getDotacoes();
       foreach ($oItem->dotacoes as $oDotacao) {

         $oDotacaoItem = null;
         foreach ($aDadosDotacao as $oDotacaoItem) {
           if ($oDotacaoItem->dotacao == $oDotacao->dotacao) {
             break;
           }
         }

         if ($oDotacao->valorexecutar == 0) {
           continue;
         }

         if ( !isset($aAutorizacoes[ $oDotacao->dotacao.$oItemContrato->getElemento() ] ) ) {

           $oAutorizacao           = new stdClass();
           $oAutorizacao->dotacao  = $oDotacao->dotacao;
           $oAutorizacao->valor    = $oDotacao->valorexecutar;
           $oAutorizacao->elemento = $oItemContrato->getElemento();
           $oAutorizacao->aItens   = array();

           $oItemAut                       = new stdClass();
           $oItemAut->descricao            = $oItemContrato->getMaterial()->getDescricao();
           $oItemAut->codigo               = $oItem->codigo;
           $oItemAut->elemento             = $oItemContrato->getElemento();
           $oItemAut->resumo               = urldecode($oItemContrato->getResumo());
           $oItemAut->valor                = $oDotacao->valorexecutar;
           $oItemAut->reserva              = $oDotacaoItem->reserva;
           $oItemAut->codigomaterial       = $oItemContrato->getMaterial()->getMaterial();
           $oItemAut->iCodigoItemLicitacao = $oItemContrato->getCodigoItemLicitacao();
           $oItemAut->iCodigoItemProcesso  = $oItemContrato->getCodigoItemProcessoCompras();
           $oItemAut->iCodigoItemEmpenho   = $oItemContrato->getCodigoItemEmpenho();

           if ($oItemContrato->getMaterial()->isServico() && $oItemContrato->getControlaQuantidade() == 'f') {

             $oItemAut->quantidade = 1;
             $oItemAut->valorunitario = $oDotacao->valorexecutar;

           } else if ($oItemContrato->getMaterial()->isServico() && $oItemContrato->getControlaQuantidade() == 't') {

              $oItemAut->quantidade    = round(($oItem->quantidade * $oDotacao->valorexecutar) / $oItem->valor, 10);
              $oItemAut->valor         = ($oItem->valor * $oItemAut->quantidade) / $oItem->quantidade;
              $oItemAut->valorunitario = $oItemContrato->getValorUnitario();

           } else {

             $oItemAut->quantidade    = round(($oItem->quantidade * $oDotacao->valorexecutar) / $oItem->valor, 10);
             $oItemAut->valorunitario = $oItemContrato->getValorUnitario();
           }

           $oAutorizacao->aItens[] = $oItemAut;
           $aAutorizacoes[$oDotacao->dotacao.$oItemContrato->getElemento()] = $oAutorizacao;
         } else {

           $aAutorizacoes[$oDotacao->dotacao.$oItemContrato->getElemento()]->valor += $oDotacao->valor;
           $oItemAut = new stdClass();

           $oItemAut->descricao      = $oItemContrato->getMaterial()->getDescricao();
           $oItemAut->codigomaterial = $oItemContrato->getMaterial()->getMaterial();
           $oItemAut->codigo         = $oItem->codigo;
           $oItemAut->resumo         = urldecode($oItemContrato->getResumo());
           $oItemAut->elemento       = $oItemContrato->getElemento();
           $oItemAut->valor          = $oDotacao->valorexecutar;
           $oItemAut->reserva        = $oDotacaoItem->reserva;
           $oItemAut->iCodigoItemLicitacao = $oItemContrato->getCodigoItemLicitacao();
           $oItemAut->iCodigoItemProcesso  = $oItemContrato->getCodigoItemProcessoCompras();
           $oItemAut->iCodigoItemEmpenho   = $oItemContrato->getCodigoItemEmpenho();


           if ($oItemContrato->getMaterial()->isServico() && $oItemContrato->getControlaQuantidade() == 'f') {

             $oItemAut->quantidade    = 1;
             $oItemAut->valorunitario = $oDotacao->valorexecutar;
           } else if ($oItemContrato->getMaterial()->isServico() && $oItemContrato->getControlaQuantidade() == 't') {

              $oItemAut->quantidade    = round(($oItem->quantidade * $oDotacao->valorexecutar) / $oItem->valor, 10);
              $oItemAut->valor         = ($oItem->valor * $oItemAut->quantidade) / $oItem->quantidade;
              $oItemAut->valorunitario = $oItemContrato->getValorUnitario();
           } else {

             $oItemAut->quantidade    = round(($oItem->quantidade * $oDotacao->valorexecutar) / $oItem->valor, 10);
             $oItemAut->valorunitario = $oItemContrato->getValorUnitario();
           }
           $aAutorizacoes[$oDotacao->dotacao.$oItemContrato->getElemento()]->aItens[] = $oItemAut;
         }
      }
    }

    /*
     * implementada logica para excluir a reserva anterior, para que seja recriadda.
    */
    if ($lProcessar) {

      foreach ($aItens as $oStdItem) {

        foreach ($oStdItem->dotacoes as $oStdDotacaoItem) {

          if ($oItemAut->reserva != "") {

            $oDaoOrcReserva   = new cl_orcreserva();
            $sSqlDadosReserva = $oDaoOrcReserva->sql_query_file($oStdDotacaoItem->reserva);
            $rsDadosReserva   = $oDaoOrcReserva->sql_record($sSqlDadosReserva);
            if ($oDaoOrcReserva->numrows > 0) {

              $oDadosReserva     = db_utils::fieldsMemory($rsDadosReserva, 0);
              $nValorReserva     = $oDadosReserva->o80_valor;
              $nValorNovaReserva = round($nValorReserva - $oStdDotacaoItem->valorexecutar, 2);

              if (empty($oStdDotacaoItem->reserva)) {
                continue;
              }

              if ($nValorNovaReserva <= 0) {

                $oDaoOrcReservaItem = new cl_orcreservaacordoitemdotacao();
                $oDaoOrcReservaItem->excluir(null, "o84_orcreserva = {$oStdDotacaoItem->reserva}");
                $oDaoOrcReserva->excluir($oStdDotacaoItem->reserva);

              } else {

                $oDaoOrcReserva   = new cl_orcreserva();
                $oDaoOrcReserva->o80_codres = $oStdDotacaoItem->reserva;
                $oDaoOrcReserva->o80_valor  = $nValorNovaReserva;
                $oDaoOrcReserva->alterar($oDaoOrcReserva->o80_codres);
              }

              if ($oDaoOrcReserva->erro_status == 0) {

                $sErro = "Erro ao alterar dados da reserva do item do contrato!\n";
                $sErro .= $oDaoOrcReserva->erro_msg;
                throw new Exception($sErro);
              }
            }

          }
        }
      }

      foreach ($aAutorizacoes as $oAutorizacaoItens) {

        $nValorAutorizacao = 0;
        foreach ($oAutorizacaoItens->aItens as $oItemAutorizacao) {
          $nValorAutorizacao += round($oItemAutorizacao->valorunitario*$oItemAutorizacao->quantidade, 2);
        }

        /**
         * Gera a autorização de empenho
         */
        $oAutorizacaoEmpenho = new AutorizacaoEmpenho();
        $oAutorizacaoEmpenho->setCaracteristicaPeculiar($oDadosAutorizacao->iCaracteristicaPeculiar);
        $oAutorizacaoEmpenho->setFornecedor($this->getContratado());
        $oAutorizacaoEmpenho->setDestino(utf8_decode($oDadosAutorizacao->destino));
        //$oAutorizacaoEmpenho->setResumo(utf8_decode($oDadosAutorizacao->resumo));
        $oAutorizacaoEmpenho->setResumo($oDadosAutorizacao->resumo);
        $oAutorizacaoEmpenho->setNumeroLicitacao($oDadosAutorizacao->licitacao);
        $oAutorizacaoEmpenho->setDotacao($oAutorizacaoItens->dotacao);
        $oAutorizacaoEmpenho->setTipoEmpenho($oDadosAutorizacao->tipoempenho);
        $oAutorizacaoEmpenho->setTipoCompra($oDadosAutorizacao->tipocompra);
        $oAutorizacaoEmpenho->setTipoLicitacao($oDadosAutorizacao->tipolicitacao);
        $oAutorizacaoEmpenho->setValor($nValorAutorizacao);
        $iSeq               = 1;
        $nValorTotal        = 0;



        foreach ($oAutorizacaoItens->aItens as $oStdItemAutorizacao) {

          $oItem = new stdClass();
          $oItem->codigomaterial   = $oStdItemAutorizacao->codigomaterial;
          $oItem->quantidade       = $oStdItemAutorizacao->quantidade;
          $oItem->valortotal       = $oStdItemAutorizacao->valorunitario * $oStdItemAutorizacao->quantidade;
          $oItem->observacao       = $oStdItemAutorizacao->resumo;
          $oItem->codigoelemento   = $oStdItemAutorizacao->elemento;
          $oItem->valorunitario    = $oStdItemAutorizacao->valorunitario;
          $oItem->acordoitem       = $oStdItemAutorizacao->codigo;
          $oItem->liclicitem       = $oStdItemAutorizacao->iCodigoItemLicitacao;
          $oItem->empempitem       = $oStdItemAutorizacao->iCodigoItemEmpenho;
          $oItem->pcprocitem       = $oStdItemAutorizacao->iCodigoItemProcesso;
          $oItem->reserva          = $oStdItemAutorizacao->reserva;

          $oAutorizacaoEmpenho->addItem($oItem);
        }
        $oAutorizacaoEmpenho->salvar();
        $iCodigoAutorizacao = $oAutorizacaoEmpenho->getAutorizacao();

        /**
         * Para cada item autorizado, gerar vinculo com acordo
         */
        foreach ($oAutorizacaoEmpenho->getItens() as $oItemAutorizacao) {

          $iCodigoItem = $oItem->sequencial;

          /**
           * incluirmos na tabela acordoitemexecutado
           */
          $oDaoAcordoItemExecutado = new cl_acordoitemexecutado;
          $oDaoAcordoItemExecutado->ac29_acordoitem = $oItemAutorizacao->acordoitem;
          $oDaoAcordoItemExecutado->ac29_automatico = 'true';
          $oDaoAcordoItemExecutado->ac29_quantidade = $oItemAutorizacao->quantidade;
          $oDaoAcordoItemExecutado->ac29_valor      = round($oItemAutorizacao->valorunitario *
                                                           $oItemAutorizacao->quantidade,2);
          $oDaoAcordoItemExecutado->ac29_tipo        = 1;
          $oDaoAcordoItemExecutado->ac29_datainicial = date("Y-m-d", db_getsession("DB_datausu"));
          $oDaoAcordoItemExecutado->ac29_datafinal   = date("Y-m-d", db_getsession("DB_datausu"));
          $oDaoAcordoItemExecutado->incluir(null);
          if ($oDaoAcordoItemExecutado->erro_status == 0) {
            throw new Exception("Erro ao salvar movimetanção do acordo!\nErro:{$oDaoAcordoItemExecutado->erro_msg}");
          }

          /**
           * Vinculamos a autorizacao ao item do acordo
           */
          $oDaoAcordoItemExecutadoAut = db_utils::getDao("acordoitemexecutadoempautitem");
          $oDaoAcordoItemExecutadoAut->ac19_acordoitemexecutado = $oDaoAcordoItemExecutado->ac29_sequencial;
          $oDaoAcordoItemExecutadoAut->ac19_autori              = $iCodigoAutorizacao;
          $oDaoAcordoItemExecutadoAut->ac19_sequen              = $iCodigoItem;
          $oDaoAcordoItemExecutadoAut->incluir(null);
          if ($oDaoAcordoItemExecutadoAut->erro_status == 0) {
            throw new Exception("Erro ao salvar movimentação do acordo!\nErro:{$oDaoAcordoItemExecutadoAut->erro_msg}");
          }

          /**
           * inclui saldo executado da dotacao
           */
          $oDaoExecucaoDotacao                  = db_utils::getDao("acordoitemexecutadodotacao");
          $oDaoExecucaoDotacao->ac32_acordoitem = $oItemAutorizacao->acordoitem;
          $oDaoExecucaoDotacao->ac32_anousu     = db_getsession("DB_anousu");
          $oDaoExecucaoDotacao->ac32_coddot     = $oAutorizacaoItens->dotacao;
          $oDaoExecucaoDotacao->ac32_valor      = $oDaoAcordoItemExecutado->ac29_valor;
          $oDaoExecucaoDotacao->incluir(null);
          if ($oDaoAcordoItemExecutadoAut->erro_status == 0) {
            throw new Exception("Erro ao salvar movimentação do acordo!\nErro:{$oDaoExecucaoDotacao->erro_msg}");
          }

          $iSeq++;
          $nValorTotal +=  $oDaoAcordoItemExecutado->ac29_valor;
        }

        $aAutorizacoesRetorno[] = $oAutorizacaoEmpenho->getAutorizacao();
        $oDaoAcordoEmpautoriza = db_utils::getDao("acordoempautoriza");
        $oDaoAcordoEmpautoriza->ac45_acordo      = $this->getCodigoAcordo();
        $oDaoAcordoEmpautoriza->ac45_empautoriza = $oAutorizacaoEmpenho->getAutorizacao();
        $oDaoAcordoEmpautoriza->incluir(null);

        if ($oDaoAcordoEmpautoriza->erro_status == 0) {

          $sMensagemErro  = "[ 1 ] - Erro ao vincular a autorização ao acordo.\n\n";
          $sMensagemErro .= $oDaoAcordoEmpautoriza->erro_msg;
          throw new Exception($sMensagemErro);
        }
      }
    }

    if ($lProcessar) {

      return $aAutorizacoesRetorno;
    } else {
      return $aAutorizacoes;
    }
  }

  public function anularAutorizacao($iAutorizacao) {

    if (empty($iAutorizacao)) {
      throw new Exception("Codigo da autorização não informado.");
    }
    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta.\nProcessamento cancelado.");
    }
    /*
     * Verifica se a autorizacao é do contrato,
     */
    $aAutorizacoes = $this->getAutorizacoes($iAutorizacao);
    if (count($aAutorizacoes) == 1) {

      $oAutorizacao = new AutorizacaoEmpenho($iAutorizacao);
      $oAutorizacao->excluirReservaSaldo();
      $oAutorizacao->anularAutorizacaoEmpenho(new DBDate(date("d/m/Y", db_getsession("DB_datausu"))));


      /**
       * Buscamos todos os itens que são da autorizacao
       */
      $aItens = $this->getItensAcordoNaAutorizacao($iAutorizacao);
      /**
       * incluimos um saldo executado negativo, informando que houve um estorno
       */
      foreach ($aItens as $oItem) {

        /**
         * incluirmos na tabela acordoitemexecutado
         */
        $oDaoAcordoItemExecutado = db_utils::getDao("acordoitemexecutado");
        $oDaoAcordoItemExecutado->ac29_acordoitem = $oItem->codigo;
        $oDaoAcordoItemExecutado->ac29_automatico = 'true';
        $oDaoAcordoItemExecutado->ac29_quantidade = $oItem->quantidade * -1;
        $oDaoAcordoItemExecutado->ac29_valor      = $oItem->valor * -1;
        $oDaoAcordoItemExecutado->ac29_tipo       = 1;
        $oDaoAcordoItemExecutado->ac29_datainicial = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoAcordoItemExecutado->ac29_datafinal   = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoAcordoItemExecutado->incluir(null);
        if ($oDaoAcordoItemExecutado->erro_status == 0) {
          throw new Exception("Erro ao salvar movimentação do acordo!\nErro:{$oDaoAcordoItemExecutado->erro_msg}");
        }
        /**
         * Vinculamos a autorizacao ao item do acordo
         */
        $oDaoAcordoItemExecutadoAut = db_utils::getDao("acordoitemexecutadoempautitem");
        $oDaoAcordoItemExecutadoAut->ac19_acordoitemexecutado = $oDaoAcordoItemExecutado->ac29_sequencial;
        $oDaoAcordoItemExecutadoAut->ac19_autori              = $iAutorizacao;
        $oDaoAcordoItemExecutadoAut->ac19_sequen              = $oItem->itemautorizacao;
        $oDaoAcordoItemExecutadoAut->incluir(null);
        if ($oDaoAcordoItemExecutadoAut->erro_status == 0) {
          throw new Exception("Erro ao salvar movimentação do acordo!\nErro:{$oDaoAcordoItemExecutadoAut->erro_msg}");
        }

        $oDaoExecucaoDotacao                  = db_utils::getDao("acordoitemexecutadodotacao");
        $oDaoExecucaoDotacao->ac32_acordoitem = $oItem->codigo;
        $oDaoExecucaoDotacao->ac32_anousu     = $oItem->anodotacao;
        $oDaoExecucaoDotacao->ac32_coddot     = $oItem->dotacao;
        $oDaoExecucaoDotacao->ac32_valor      = $oDaoAcordoItemExecutado->ac29_valor;
        $oDaoExecucaoDotacao->incluir(null);
        if ($oDaoAcordoItemExecutadoAut->erro_status == 0) {
          throw new Exception("Erro ao salvar movimentação do acordo!\nErro:{$oDaoExecucaoDotacao->erro_msg}");
        }

        //$oItemContrato = $this->getUltimaPosicao()->getItemByCodigo($oItem->codigo);
        $aDotacoes     = array();
        $oDotacaoItem = null;
        foreach ($aDotacoes as $oDotacaoItem) {
          if ($oDotacaoItem->dotacao == $oItem->dotacao) {
            break;
          }
        }
        if ($oDotacaoItem != null) {

          if ($oDotacaoItem->reserva != "") {

            /**
             * Verifica se a dotacao possui saldo para reservar o valor do item novamente:
             */
            $oDotacaoSaldo = new Dotacao($oDotacaoItem->dotacao, $oDotacaoItem->ano);
            $oDaoOrcReserva   = db_utils::getDao("orcreserva");
            $sSqlDadosReserva = $oDaoOrcReserva->sql_query_file($oDotacaoItem->reserva);
            $rsDadosReserva   = $oDaoOrcReserva->sql_record($sSqlDadosReserva);
            if ($oDaoOrcReserva->numrows > 0) {

              $nValorAcrescentar = $oItem->valor;
              if ($oDotacaoSaldo->getSaldoFinal() < $oItem->valor) {
                $nValorAcrescentar = $oDotacaoSaldo->getSaldoFinal();
              }
              $oDadosReserva     = db_utils::fieldsMemory($rsDadosReserva, 0);
              $nValorReserva     = $oDadosReserva->o80_valor;
              $nValorNovaReserva = round($nValorReserva + $nValorAcrescentar, 2);
              $oDaoOrcReserva   = db_utils::getDao("orcreserva");
              $oDaoOrcReserva->o80_codres = $oDotacaoItem->reserva;
              $oDaoOrcReserva->o80_valor  = $nValorNovaReserva;
              $oDaoOrcReserva->alterar($oDotacaoItem->reserva);
              if ($oDaoOrcReserva->erro_status == 0) {

                $sErro = "Erro ao alterar dados da reserva do item do contrato!\n";
                $sErro .= $oDaoOrcReserva->erro_msg;
                throw new Exception($sErro);
              }
            }
          }
        }
      }
    }
    return true;
  }

  public function getLicitacoes() {

    $oDaoAcordo        = db_utils::getDao("acordo");
    $sCamposLicitacoes = " liclicita.l20_codigo ";
    $sSqlLicitacoes    = $oDaoAcordo->sql_queryLicitacoesVinculadas($this->iCodigoAcordo, $sCamposLicitacoes);
    $rsLicitacoes      = $oDaoAcordo->sql_record($sSqlLicitacoes);

    if ($oDaoAcordo->numrows > 0) {

      for ($iLicitacao = 0; $iLicitacao < $oDaoAcordo->numrows; $iLicitacao++) {

        $iCodigoLicitacao = db_utils::fieldsMemory($rsLicitacoes, $iLicitacao)->l20_codigo;
        $this->aLicitacoes[] = new licitacao($iCodigoLicitacao);
      }
    }

    return $this->aLicitacoes;
  }

  public function getProcessosDeCompras() {

    $oDaoAcordo              = db_utils::getDao("acordo");
    $sCamposProcesso         = " pc80_codproc ";
    $sSqlProcessosVinculados = $oDaoAcordo->sql_queryProcessosVinculados($this->iCodigoAcordo, $sCamposProcesso);
    $rsProcessosVinculados   = $oDaoAcordo->sql_record($sSqlProcessosVinculados);

    if ($oDaoAcordo->numrows > 0) {

      for ($iProcesso = 0; $iProcesso < $oDaoAcordo->numrows; $iProcesso++) {

        $iCodigoProcesso = db_utils::fieldsMemory($rsProcessosVinculados, $iProcesso)->pc80_codproc;
        $this->aProcessosDeCompras[] = new ProcessoCompras($iCodigoProcesso);
      }
    }

    return $this->aProcessosDeCompras;
  }


  /**
   * Retorna os empenhos vinculados ao acordo
   * @access public
   * @return EmpenhoFinanceiro[]
   */
  public function getEmpenhos() {

    $oDaoAcordo             = db_utils::getDao("acordo");
    $sCamposEmpenho         = " e100_numemp ";
    $sSqlEmpenhosVinculados = $oDaoAcordo->sql_queryEmpenhosVinculados($this->iCodigoAcordo, $sCamposEmpenho);
    $rsEmpenhosVinculados   = $oDaoAcordo->sql_record($sSqlEmpenhosVinculados);

    if ($oDaoAcordo->numrows > 0) {

      for ($iEmpenho = 0; $iEmpenho < $oDaoAcordo->numrows; $iEmpenho++) {

        $iNumeroEmpenho    = db_utils::fieldsMemory($rsEmpenhosVinculados, $iEmpenho)->e100_numemp;
        $this->aEmpenhos[] = new EmpenhoFinanceiro($iNumeroEmpenho);
      }
    }

    return $this->aEmpenhos;
  }

  /**
   * Retorna as autorizacoes realizadas para o acordo
   * @param integer [$iAutoriza] codigo da Autorizacao
   * @return array
   */
   public function getAutorizacoes($iAutoriza = '') {

    $sSqlAutorizacoes  =  "select  e54_autori as codigo,";
    $sSqlAutorizacoes .=  "        sum(e54_valor) as valor,e54_valor,";
    $sSqlAutorizacoes .=  "        e54_emiss as dataemissao,";
    $sSqlAutorizacoes .=  "        e54_anulad as dataanulacao,";
    $sSqlAutorizacoes .=  "        e60_codemp||'/'||e60_anousu as empenho, ";
    $sSqlAutorizacoes .=  "        e60_numemp as codigoempenho";
    $sSqlAutorizacoes .=  "   from acordoposicao ";
    $sSqlAutorizacoes .=  "        inner join acordoitem          on ac20_acordoposicao = ac26_sequencial ";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutado on ac20_sequencial    = ac29_acordoitem and ac29_quantidade >= 0 and ac29_valor >= 0 ";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutadoempautitem on ac29_sequencial = ac19_acordoitemexecutado ";
    $sSqlAutorizacoes .=  "        inner join empautitem on e55_sequen = ac19_sequen and ac19_autori = e55_autori ";
    $sSqlAutorizacoes .=  "        inner join empautoriza on e54_autori = e55_autori ";
    $sSqlAutorizacoes .=  "        left join empempaut on e61_autori = e54_autori ";
    $sSqlAutorizacoes .=  "        left join empempenho on e61_numemp = e60_numemp ";
    $sSqlAutorizacoes .=  "  where ac26_acordo =  {$this->getCodigoAcordo()} ";
    if ($iAutoriza != '') {
      $sSqlAutorizacoes .= " and e54_autori = {$iAutoriza}";
    }
    $sSqlAutorizacoes .=  "  group by e54_autori,";
    $sSqlAutorizacoes .=  "  e54_emiss,  ";
    $sSqlAutorizacoes .=  "  e60_codemp, ";
    $sSqlAutorizacoes .=  "  e60_anousu, ";
    $sSqlAutorizacoes .=  "  e60_numemp,  ";
    $sSqlAutorizacoes .=  "  e54_anulad";

    /**
     * pesquisa os empenhos vicnulados por baixa Manual
     */
    $sSqlAutorizacoes .=  " UNION ";
    $sSqlAutorizacoes .=  "select  distinct e54_autori as codigo,";
    $sSqlAutorizacoes .=  "        e54_valor as valor,e54_valor,";
    $sSqlAutorizacoes .=  "        e54_emiss as dataemissao,";
    $sSqlAutorizacoes .=  "        e54_anulad as dataanulacao,";
    $sSqlAutorizacoes .=  "        e60_codemp||'/'||e60_anousu as empenho, ";
    $sSqlAutorizacoes .=  "        e60_numemp as codigoempenho";
    $sSqlAutorizacoes .=  "   from acordoposicao ";
    $sSqlAutorizacoes .=  "        inner join acordoitem          on ac20_acordoposicao = ac26_sequencial ";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutado on ac20_sequencial    = ac29_acordoitem and ac29_quantidade >= 0 and ac29_valor >= 0  ";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutadoperiodo on ac29_sequencial = ac38_acordoitemexecutado";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutadoempenho on  ac38_sequencial = ac39_acordoitemexecutadoperiodo";
    $sSqlAutorizacoes .=  "        inner join empempenho    on ac39_numemp = e60_numemp ";
    $sSqlAutorizacoes .=  "        left join empempaut      on e60_numemp  = e61_numemp ";
    $sSqlAutorizacoes .=  "        inner join empautoriza   on e54_autori  = e61_autori ";
    $sSqlAutorizacoes .=  "  where ac26_acordo =  {$this->getCodigoAcordo()} ";
    $sSqlAutorizacoes .=  "  order by codigo";
    $rsAutorizacoes    = db_query($sSqlAutorizacoes);

    return db_utils::getCollectionByRecord($rsAutorizacoes);
  }

  /**
   * retorna os itens do acordo que estão na autorizacao passada por parametro
   *
   * @param integer $iAutoriza codigo do autorizacao
   * @return array
   */
  public function getItensAcordoNaAutorizacao($iAutoriza) {


    $sSqlAutorizacoes  =  "select  e54_autori as autorizacao,";
    $sSqlAutorizacoes .=  "        ac29_valor as valor,";
    $sSqlAutorizacoes .=  "        ac29_acordoitem as codigo,";
    $sSqlAutorizacoes .=  "        ac29_quantidade as quantidade,";
    $sSqlAutorizacoes .=  "        e56_coddot as dotacao,";
    $sSqlAutorizacoes .=  "        e56_anousu as anodotacao,";
    $sSqlAutorizacoes .=  "        e55_sequen as itemautorizacao";
    $sSqlAutorizacoes .=  "   from acordoposicao ";
    $sSqlAutorizacoes .=  "        inner join acordoitem          on ac20_acordoposicao = ac26_sequencial ";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutado on ac20_sequencial    = ac29_acordoitem ";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutadoempautitem on ac29_sequencial = ac19_acordoitemexecutado ";
    $sSqlAutorizacoes .=  "        inner join empautitem on e55_sequen = ac19_sequen and ac19_autori = e55_autori ";
    $sSqlAutorizacoes .=  "        inner join empautoriza on e54_autori = e55_autori ";
    $sSqlAutorizacoes .=  "        inner join empautidot on e56_autori = e54_autori ";
    $sSqlAutorizacoes .=  "  where ac26_acordo = {$this->getCodigoAcordo()} ";
    $sSqlAutorizacoes .= "     and e54_autori = {$iAutoriza}";
    $sSqlAutorizacoes .=  "  order by e54_autori, ac29_acordoitem";
    $rsAutorizacoes    = db_query($sSqlAutorizacoes);
    return db_utils::getCollectionByRecord($rsAutorizacoes);
  }

  /**
   *
   */

  public function getRecisoes() {

    $oDaoAcordoMovimentacao      = db_utils::getDao('acordomovimentacao');
    $ac10_acordomovimentacaotipo = 6;
    $sCampos  = "*";
    $sWhere   = " ac10_acordomovimentacaotipo = ".$ac10_acordomovimentacaotipo;
    $sWhere  .= " and ac10_acordo = ".$this->getCodigoAcordo();
    $sSqlAcordoMovimentacao  = $oDaoAcordoMovimentacao->sql_query(null,$sCampos,null,$sWhere);
    $rsSqlAcordoMovimentacao = $oDaoAcordoMovimentacao->sql_record($sSqlAcordoMovimentacao);
    return db_utils::getCollectionByRecord($rsSqlAcordoMovimentacao);

  }

  public function getAnulacoes() {

    $oDaoAcordoMovimentacao      = db_utils::getDao('acordomovimentacao');
    $ac10_acordomovimentacaotipo = 8;
    $sCampos  = "*";
    $sWhere   = " ac10_acordomovimentacaotipo = ".$ac10_acordomovimentacaotipo;
    $sWhere  .= " and ac10_acordo = ".$this->getCodigoAcordo();
    $sSqlAcordoMovimentacao  = $oDaoAcordoMovimentacao->sql_query(null,$sCampos,null,$sWhere);
    $rsSqlAcordoMovimentacao = $oDaoAcordoMovimentacao->sql_record($sSqlAcordoMovimentacao);
    return db_utils::getCollectionByRecord($rsSqlAcordoMovimentacao);

  }
  /**
   * Mostra os valores contrato
   *@return objeto com o total atual, e total original do contrato
   */
  public function getValoresItens() {

    $oValores = new stdClass();
    $oValores->valororiginal     = $this->getValorContrato();
    $oValores->valoratual        = 0;
    $oValores->valoraditado      = 0;
    $oValores->percentualaditado = 0;

    foreach ($this->getItens() as $oItem) {
      $oValores->valoratual += $oItem->getValorTotal();
    }

    $oValores->valoraditado += $oValores->valoratual - $oValores->valoratual;

    /**
     * o valor aditado deve ser a diferenca do valor aditado do valor original
     */
    if ($oValores->valoraditado < 0) {
      $oValores->valoraditado = 0;
    }

    return $oValores;
  }


  /**
   * funcao para atualizar o valor do contrato, baseado na soma dos itens
   * @return bool
   */
  public function atualizaValorContratoPorTotalItens() {

    $oValorContrato = $this->getValoresItens();
    $nValorAtual    = $oValorContrato->valoratual;

    $oDaoAcordo = new cl_acordo();
    $oDaoAcordo->ac16_sequencial = $this->getCodigoAcordo();
    $oDaoAcordo->ac16_valor      = $nValorAtual ;
    $oDaoAcordo->alterar($oDaoAcordo->ac16_sequencial);
    if ($oDaoAcordo->erro_status == 0) {

      throw DBException($oDaoAcordo->erro_msg);
    }

    return true;
  }


  /**
   * Realiza um aditamento no contrato
   * Os tipos de aditamento são os listados abaixo:
   * @param $aItens
   * @param $iTipoAditamento
   * @param $dtVigenciaInicial
   * @param $dtVigenciaFinal
   * @param null $sNumeroAditamento
   * @return $this
   * @throws Exception
   */
  public function aditar($aItens, $iTipoAditamento, $dtVigenciaInicial, $dtVigenciaFinal, $sNumeroAditamento = null, $sObservacao = '', $iTipoOperacao = null) {

    $nValorItens = 0;

    foreach ($aItens as $oItem) {
      $nValorItens += round($oItem->valorunitario * $oItem->quantidade, 2);
    }

    $iSituacao = AcordoPosicao::SITUACAO_ATIVO;
    if (in_array($iTipoAditamento, array(AcordoPosicao::TIPO_ALTERACAO_DOTACAO, AcordoPosicao::TIPO_SUPRESSAO))) {
      $iSituacao = AcordoPosicao::SITUACAO_CANCELADO;
    }

    /**
     * cancelamos a última posição do acordo.
     */
    if (!in_array($iTipoAditamento, array(AcordoPosicao::TIPO_RENOVACAO, AcordoPosicao::TIPO_ALTERACAO_DOTACAO, AcordoPosicao::TIPO_SUPRESSAO))) {
      $this->getUltimaPosicao()->setSituacao(AcordoPosicao::SITUACAO_CANCELADO);
    }

    $this->getUltimaPosicao()->save();

    $oNovaPosicao = new AcordoPosicao(null);
    $oNovaPosicao->setData(date("Y-m-d", db_getsession("DB_datausu")));
    $oNovaPosicao->setAcordo($this->getCodigoAcordo());
    $oNovaPosicao->setEmergencial(false);
    $oNovaPosicao->setNumero($this->getProximoNumeroAditamento());
    $oNovaPosicao->setNumeroAditamento($sNumeroAditamento);
    $oNovaPosicao->setSituacao($iSituacao);
    $oNovaPosicao->setTipo($iTipoAditamento);
    $oNovaPosicao->setVigenciaInicial($dtVigenciaInicial);
    $oNovaPosicao->setVigenciaFinal($dtVigenciaFinal);
    $oNovaPosicao->setPosicaoPeriodo($dtVigenciaInicial, $dtVigenciaFinal, $this->getPeriodoComercial());
    $oNovaPosicao->setObservacao($sObservacao);
    $oNovaPosicao->setTipoOperacao($iTipoOperacao);
    $oNovaPosicao->save();

    $oNovaPosicao->salvarSaldoAditamento($nValorItens);

    $this->setDataInicial($dtVigenciaInicial);
    $this->setDataFinal($dtVigenciaFinal);
    $this->salvarAlteracoesContrato();

    $oAcordoEvento = new AcordoEvento();
    $oAcordoEvento->setTipoEvento(AcordoEvento::TIPO_EVENTO_TERMO_ADITIVIVO);
    $oAcordoEvento->setAcordo($this);
    $oAcordoEvento->setData(new DBDate(date("Y-m-d", db_getsession("DB_datausu"))));
    $oAcordoEvento->salvar();
    $oAcordoEvento->adicionarAcordoPosicaoEvento($oNovaPosicao);

    $lAlterouDotacao = false;
    foreach ($aItens as $oItem) {

      $oItemContrato = $this->getUltimaPosicao()->getItemByCodigo($oItem->codigo);
      $oNovoItem     = new AcordoItem(null);
      $oNovoItem->setCodigoPosicao($oNovaPosicao->getCodigo());

      if ($oItemContrato) {

        $oOrigemItem = $oItemContrato->getOrigem();
        $oNovoItem->setElemento($oItemContrato->getElemento());
        $oNovoItem->setMaterial($oItemContrato->getMaterial());
        $oNovoItem->setResumo($oItemContrato->getResumo());
        $oNovoItem->setOrigem($oOrigemItem->codigo, $oOrigemItem->tipo, $oOrigemItem->codigoorigem);
        $oNovoItem->setUnidade($oItemContrato->getUnidade());
        $oNovoItem->setTipoControle($oItemContrato->getTipocontrole());
        $oNovoItem->setItemVinculo($oItemContrato->getCodigo());

        $aPeriodosItem = $oItemContrato->getPeriodosItem();
        if (!empty($aPeriodosItem)) {
          $oNovoItem->setPeriodos($oItemContrato->getPeriodosItem());
        }

        if (!$lAlterouDotacao && $iTipoAditamento != AcordoPosicao::TIPO_SUPRESSAO) {

          $aDotacoesItem = array_map(function ($oDotacaoItem) {
            return $oDotacaoItem->dotacao;
          }, $oItem->dotacoes);

          $aDotacoesItemContrato = array_map(function ($oDotacaoItem) {
            return $oDotacaoItem->dotacao;
          }, $oItemContrato->getDotacoes());

          if (count(array_diff($aDotacoesItemContrato, $aDotacoesItem)) > 0) {
            $lAlterouDotacao = true;
          }
        }
      } else {

        $oNovoItem->setElemento($oItem->codigoelemento);
        $oNovoItem->setMaterial(new MaterialCompras($oItem->codigoitem));
        $oNovoItem->setResumo(utf8_decode(db_stdClass::db_stripTagsJson($oItem->resumo)));
        $oNovoItem->setUnidade($oItem->unidade);
        $oNovoItem->setTipoControle(AcordoItem::CONTROLE_DIVISAO_QUANTIDADE);

        if (!empty($oItem->aPeriodos)) {
          $oNovoItem->setPeriodos($oItem->aPeriodos);
        }
      }

      if ($iTipoAditamento == AcordoPosicao::TIPO_SUPRESSAO) {
        $oItem->quantidade = (float) $oItem->quantidade * -1;
      }

      $oNovoItem->setQuantidade((float) $oItem->quantidade);
      $oNovoItem->setValorUnitario((float) $oItem->valorunitario);
      $oNovoItem->setValorTotal( round($oItem->valorunitario * $oItem->quantidade, 2) );

      if ($iTipoAditamento != AcordoPosicao::TIPO_SUPRESSAO) {

        foreach ($oItem->dotacoes as $oDotacao) {

          $oDotacao->ano = db_getsession("DB_anousu");

          $nValor      = (float) $oItem->valorunitario;
          $nQuantidade = (float) $oItem->quantidade;
          if (empty($nValor) && empty($nQuantidade)) {

            foreach ($oItemContrato->getDotacoes() as $oDotacaoAnterior) {

              if ($oDotacaoAnterior->dotacao == $oDotacao->dotacao) {
                $oDotacao->ano = $oDotacaoAnterior->ano;
              }
            }
          }

          $oNovoItem->adicionarDotacoes($oDotacao);
        }
      }

      $oNovoItem->save( $oNovoItem->getValorTotal() != 0 );

      if ($iTipoAditamento == AcordoPosicao::TIPO_SUPRESSAO
            && $iTipoOperacao != AcordoPosicao::OPERACAO_REDUCAO_VALOR_SUPRESSAO_ITENS
            && $oNovoItem->getMaterial()->isServico()
            && ($oNovoItem->getControlaQuantidade() == 'f' || $oNovoItem->getControlaQuantidade() == null)) {
        $oNovoItem->zerarQuantidade();
      }
    }

    /**
     * Cria um aditamento de Alteração de Dotações caso as novas dotações de algum item sejam diferentes da posição anterior
     */
    if ($lAlterouDotacao) {

      $oPosicaoDotacao = new AcordoPosicao(null);
      $oPosicaoDotacao->setData(date("Y-m-d", db_getsession("DB_datausu")));
      $oPosicaoDotacao->setAcordo($this->getCodigoAcordo());
      $oPosicaoDotacao->setEmergencial(false);
      $oPosicaoDotacao->setNumero($this->getProximoNumeroAditamento());
      $oPosicaoDotacao->setNumeroAditamento($sNumeroAditamento);
      $oPosicaoDotacao->setSituacao(AcordoPosicao::SITUACAO_CANCELADO);
      $oPosicaoDotacao->setTipo(AcordoPosicao::TIPO_ALTERACAO_DOTACAO);
      $oPosicaoDotacao->setVigenciaInicial($dtVigenciaInicial);
      $oPosicaoDotacao->setVigenciaFinal($dtVigenciaFinal);
      $oPosicaoDotacao->setPosicaoPeriodo($dtVigenciaInicial, $dtVigenciaFinal, $this->getPeriodoComercial());
      $oPosicaoDotacao->save();

      $oAcordoEvento = new AcordoEvento();
      $oAcordoEvento->setTipoEvento(AcordoEvento::TIPO_EVENTO_TERMO_ADITIVIVO);
      $oAcordoEvento->setAcordo($this);
      $oAcordoEvento->setData(new DBDate(date("Y-m-d", db_getsession("DB_datausu"))));
      $oAcordoEvento->salvar();
      $oAcordoEvento->adicionarAcordoPosicaoEvento($oPosicaoDotacao);
    }

    return $this;
  }

  /**
   * retorna as movimentações realizas pelo acordo.
   *
   * @return array
   */
  public function getMovimentacoes() {

    $oDaoAcordoMovimentacao = db_utils::getDao("acordomovimentacao");
    $sSqlMov = $oDaoAcordoMovimentacao->sql_query(null,
                                                  "ac09_descricao as descricao,
                                                   ac10_hora as hora,
                                                   ac10_datamovimento,
                                                   ac10_obs as observacao",
                                                  "ac10_datamovimento",
                                                  "ac10_acordo = {$this->getCodigoAcordo()}"
                                                 );
   $rsMovimentos = $oDaoAcordoMovimentacao->sql_record($sSqlMov);
   return db_utils::getCollectionByRecord($rsMovimentos);
  }

  /**
   *
   * Retorna todos os documentos para o acordo selecionado
   */
  public function getDocumentos() {

    $sCampos = "ac40_sequencial ";
    $sWhere  = " ac40_acordo = {$this->getCodigoAcordo()}";

    $oDaoAcordoDocumento = db_utils::getDao("acordodocumento");
    $sSqlDocumentos      = $oDaoAcordoDocumento->sql_query_file(null, $sCampos, 'ac40_sequencial', $sWhere);
    $rsAcordoDocumento   = $oDaoAcordoDocumento->sql_record($sSqlDocumentos);
    if ($oDaoAcordoDocumento->numrows > 0) {

      for ($i = 0; $i < $oDaoAcordoDocumento->numrows; $i++) {

        $this->aDocumento[] = new AcordoDocumento(db_utils::fieldsMemory($rsAcordoDocumento, $i)->ac40_sequencial);
      }
    }

    return $this->aDocumento;

  }

  /**
   *
   * Adiciona um Documento para o Acordo selecionado
   * @param String $sDescricao
   * @param Arquivo $sArquivo (caminho/nome do arquivo)
   */
  public function adicionarDocumento($sDescricao, $sArquivo) {

    $oAcordoDocumento = new AcordoDocumento();
    $oAcordoDocumento->setArquivo($sArquivo);
    $oAcordoDocumento->setDescricao($sDescricao);
    $oAcordoDocumento->setCodigoAcordo($this->getCodigoAcordo());

    $aNomeArquivo = explode("/", $sArquivo);
    $sNomeArquivo = str_replace(" ", "_", $aNomeArquivo[1]);
    $oAcordoDocumento->setNomeArquivo($sNomeArquivo);
    $oAcordoDocumento->salvar();

  }

  /**
   *
   * Remove o Documento para o Acordo selecionado
   * @param integer $iCodigoDocumento
   */
  public function removeDocumento($iCodigoDocumento) {

    $oAcordoDocumento = new AcordoDocumento($iCodigoDocumento);
    $oAcordoDocumento->remover();

  }


  /**
   * Seta a quantidade ( em dia/mes) referente ao periodo de vigendia
   * @param integer $iQtdPeriodoVigencia
   */
  public function setQtdPeriodoVigencia ($iQtdPeriodoVigencia) {

    $this->iQtdPeriodoVigencia = $iQtdPeriodoVigencia;
  }

  /**
   * Seta a unidade de tempo (dia/mes) referente ao período de vigência
   * @param integer $iTipoUnidadeTempoVigencia
   */
  public function setTipoUnidadeTempoVigencia ($iTipoUnidadeTempoVigencia) {

    $this->iTipoUnidadeTempoVigencia = $iTipoUnidadeTempoVigencia;
  }

  /**
   * Seta a categoria do Acordo
   * @param integer $iCategoriaAcordo
   */
  public function setCategoriaAcordo ($iCategoriaAcordo) {

    $this->iCategoriaAcordo = $iCategoriaAcordo;
  }

  /**
   * Retorna a quantidade ( em dia/mes) referente ao periodo de vigendia
   * @param integer $iQtdPeriodoVigencia
   */
  public function getQtdPeriodoVigencia () {

    return $this->iQtdPeriodoVigencia;
  }

  /**
   * Retorna a unidade de tempo (dia/mes) referente ao período de vigência
   * @param integer $iTipoUnidadeTempoVigencia
   */
  public function getTipoUnidadeTempoVigencia () {

    return $this->iTipoUnidadeTempoVigencia;
  }

  /**
   * Retorna a categoria do Acordo
   * @param integer $iCategoriaAcordo
   */
  public function getCategoriaAcordo () {

    return $this->iCategoriaAcordo;
  }

  /**
   * retorna a descricao da categoria do acordo
   * @param integer categoria
   * @return string sCategoria
   */
  public static function getDescricaoCategoriaAcordo($iCategoria = 0){

    $sCategoria    = 'Não Informado';
    $oDaoCategoria = db_utils::getDao("acordocategoria");
    $sSqlCategoria = $oDaoCategoria->sql_query_file(null, "*", null, "ac50_sequencial = {$iCategoria}");


    $rsCategoria   = $oDaoCategoria->sql_record($sSqlCategoria);
    if ($oDaoCategoria->numrows > 0) {

      $sCategoria    = db_utils::fieldsMemory($rsCategoria, 0)->ac50_descricao;
    }
    return $sCategoria;
  }


  /**
   * Valida se periodo tem execucao
   *
   * @param integer $iCodigoPeriodo
   * @param DBDate $oDataInicial
   * @param DBDate $oDataFinal
   * @access public
   * @return boolean
   */
  public function verificaSeTemExecucaoPeriodo( $iCodigoPeriodo = null, DBDate $oDataInicial, DBDate $oDataFinal ) {

    $oDaoAcordoposicao = db_utils::GetDao('acordoposicao');
    $sDataInicial      = $oDataInicial->getDate();
    $sDataFinal        = $oDataFinal->getDate();

    $sCampos = "ac37_sequencial";
    $sWhere  = "  ac26_acordo = {$this->getCodigoAcordo()}";
    $sWhere .= "  and ('$sDataInicial', '$sDataFinal') overlaps (ac37_datainicial, ac37_datafinal)                                    ";
    $sWhere .= "  and ac37_quantidadeprevista <= (select sum(aie.ac29_quantidade)                                                     ";
    $sWhere .= "                                    from acordoitemexecutadoperiodo                                                   ";
    $sWhere .= "                                         inner join acordoitemexecutado aie on aie.ac29_sequencial = acordoitemexecutadoperiodo.ac38_acordoitemexecutado";
    $sWhere .= "                                   where ac38_acordoitemprevisao = aip.ac37_sequencial )            ";

    if ( !empty( $iCodigoPeriodo ) ) {

      $sWhere .= " and acordoitem.ac20_sequencial = (select ac41_acordoitem                      ";
      $sWhere .= "                                     from acordoitemperiodo                    ";
      $sWhere .= "                                    where ac41_sequencial = {$iCodigoPeriodo}) ";
    }

    $sSqlVerificaPeriodo = $oDaoAcordoposicao->sql_query_periodo_execucao(null, $sCampos, null, $sWhere);

    $rsVerificaPeriodo   = $oDaoAcordoposicao->sql_record($sSqlVerificaPeriodo);

    if ( $oDaoAcordoposicao->numrows > 0 ) {
      return false;
    }

    return true;
  }

  /**
   * Remove um acordo e seus dependentes
   *
   * @throws DBException
   * @throws BusinessException
   */
  public function remover() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M( self::MENSAGENS."sem_transacao_ativa" ) );
    }

    if ( $this->getCodigoAcordo() == null ) {
      throw new BusinessException( _M( self::MENSAGENS."sequencial_nao_existente" ) );
    }

    /**
     * Valida se o contrato está homologado
     */
    if ( $this->getSituacao() != 1 ) {
      throw new BusinessException( _M( self::MENSAGENS."contrato_homologado" ) );
    }

    if ($this->possuiLancamentoContabil()) {
      throw new BusinessException("O acordo {$this->getCodigo()} possui lançamento contábil vinculado. Procedimento abortado.");
    }

    $oDataInicial = new DBDate( $this->getDataInicial() );
    $oDataFinal   = new DBDate( $this->getDataFinal() );

    /**
     * Valida se existe execução dentro do período do contrato, não permitindo a remoção
     */
    if (!$this->verificaSeTemExecucaoPeriodo( null, $oDataInicial, $oDataFinal)) {

      $oDados               = new stdClass();
      $oDados->sDataInicial = $oDataInicial->getDate( DBDate::DATA_PTBR );
      $oDados->sDataFinal   = $oDataFinal->getDate( DBDate::DATA_PTBR );
      throw new BusinessException( _M( self::MENSAGENS."periodo_com_execucao", $oDados ) );
    }

    $oDAoAcordoParalisacao             = new cl_acordoparalisacao();
    $oDaoAcordoParalisacaoMovimentacao = new cl_acordoparalisacaoacordomovimentacao();
    $oDaoAcordoParalisacaoPeriodo      = new cl_acordoparalisacaoperiodo();

    $sSqlParalisacao       = $oDAoAcordoParalisacao->sql_query_file(null, "*", null, "ac47_acordo={$this->getCodigoAcordo()}");
    $rsParalizacao         = db_query($sSqlParalisacao);
    if (!$rsParalizacao) {
      throw new DBException("Erro ao pesquisar paralizações do acordo");
    }
    $iTotalParalizacoes = pg_num_rows($rsParalizacao);
    for ($iParalizacoes= 0; $iParalizacoes < $iTotalParalizacoes; $iParalizacoes++) {

      $dadosParalizacao = db_utils::fieldsMemory($rsParalizacao, $iParalizacoes);
      $oDaoAcordoParalisacaoMovimentacao->excluir(null, 'ac48_acordoparalisacao='.$dadosParalizacao->ac47_sequencial);
      if ($oDaoAcordoParalisacaoMovimentacao->erro_status == 0) {
        throw new DBException("Erro ao remover paralizações do acordo {$this->getCodigo()}");
      }
      $oDaoAcordoParalisacaoPeriodo->excluir(null, 'ac49_acordoparalisacao='.$dadosParalizacao->ac47_sequencial);
      if ($oDaoAcordoParalisacaoPeriodo->erro_status == 0) {
        throw new DBException("Erro ao remover períodos paralizados do acordo {$this->getCodigo()}");
      }
      $oDAoAcordoParalisacao->excluir($dadosParalizacao->ac47_sequencial);
      if ($oDAoAcordoParalisacao->erro_status == 0) {
        throw new DBException("Erro ao remover paralização do acordo {$this->getCodigo()}");
      }
    }


    /**
     * Busca as movimentações canceladas de um acordo, removendo-as
     */
    $oDaoAcordoMovimentacaoCancela   = new cl_acordomovimentacaocancela();
    $sWhereAcordoMovimentacaoCancela = "ac10_acordo = {$this->getCodigoAcordo()}";
    $sSqlAcordoMovimentacaoCancela   = $oDaoAcordoMovimentacaoCancela->sql_query(
                                                                                  null,
                                                                                  "ac25_sequencial",
                                                                                  null,
                                                                                  $sWhereAcordoMovimentacaoCancela
                                                                                );
    $rsAcordoMovimentacaoCancela     = $oDaoAcordoMovimentacaoCancela->sql_record( $sSqlAcordoMovimentacaoCancela );
    $iTotalAcordoMovimentacaoCancela = $oDaoAcordoMovimentacaoCancela->numrows;

    if ( $iTotalAcordoMovimentacaoCancela > 0 ) {

      for ( $iContador = 0; $iContador < $iTotalAcordoMovimentacaoCancela; $iContador++ ) {

        $iAcordoMovimentacaoCancela = db_utils::fieldsMemory( $rsAcordoMovimentacaoCancela, $iContador )->ac25_sequencial;
        $oDaoAcordoMovimentacaoCancela->excluir( $iAcordoMovimentacaoCancela, null );

        if ( $oDaoAcordoMovimentacaoCancela->erro_status == 0 ) {
          throw new BusinessException( $oDaoAcordoMovimentacaoCancela->erro_msg );
        }
      }
    }

    /**
     * Remove as movimentações vinculadas ao acordo
     */
    $oDaoAcordoMovimentacao   = new cl_acordomovimentacao();
    $sWhereACordoMovimentacao = "ac10_acordo = {$this->getCodigoAcordo()}";
    $oDaoAcordoMovimentacao->excluir( null, $sWhereACordoMovimentacao );

    if ( $oDaoAcordoMovimentacao->erro_status == 0 ) {
      throw new BusinessException( $oDaoAcordoMovimentacao->erro_msg );
    }

    /**
     * Percorre as posições do acordo, removendo as mesmas
     */
    foreach ( $this->getPosicoes() as $oAcordoPosicao ) {
      $oAcordoPosicao->remover();
    }

    /**
     * Percorre as penalidades do acordo, removendo as mesmas
     */
    $oDaoAcordoPenalidade     = new cl_acordoacordopenalidade;
    $sWhereExclusaoPenalidade = " ac15_acordo = {$this->getCodigoAcordo()}";
    $oDaoAcordoPenalidade->excluir(null, $sWhereExclusaoPenalidade);
    if ($oDaoAcordoPenalidade->erro_status == 0) {
      throw new BusinessException($oDaoAcordoPenalidade->erro_msg );
    }

    /**
     * Percorre as garantias do acordo, removendo as mesmas
     */
    $sWhereGarantias    = " ac12_acordo = {$this->getCodigoAcordo()}";
    $oDaoAcordoGarantia = new cl_acordoacordogarantia();
    $oDaoAcordoGarantia->excluir(null, $sWhereGarantias);
    if ($oDaoAcordoGarantia->erro_status == 0) {
      throw new BusinessException($oDaoAcordoGarantia->erro_msg );
    }

    /**
     * Percorre os eventos, removendo os mesmos
     */
    foreach ($this->getEventos() as $oEvento) {
      $oEvento->remover();
    }

    /**
     * Percorre os documentos do acordo, removendo os mesmos
     */
    foreach ($this->getDocumentos() as $oAcordoDocumento) {
      $oAcordoDocumento->remover();
    }

    /**
     * Remove o vínculo entre o acordo e todos os empenhos vinculados através da rotina
     * Acordo -> Procedimentos -> Vincular Contrato com Empenhos
     */
    $sSqlVinculoEmpenhoLicitaCon = "delete from acordoempempenho where ac54_acordo = {$this->getCodigoAcordo()}";
    $rsVinculoEmpenhoLicitaCon   = db_query($sSqlVinculoEmpenhoLicitaCon);
    if (!$rsVinculoEmpenhoLicitaCon) {
      throw new DBException('Não foi possível remover os vínculos entre o acordo e os empenhos.');
    }

    /**
     * Remover dos vinculos com empemeho
     */
    $oDaoEmpenhoContrato = new cl_empempenhocontrato();
    $oDaoEmpenhoContrato->excluir(null, "e100_acordo = {$this->getCodigoAcordo()}");
    if ($oDaoEmpenhoContrato->erro_status == 0) {
      throw new BusinessException($oDaoEmpenhoContrato->erro_msg);
    }

    /**
     * Remover dos vinculos com as autorizações
     */
    $oDaoAcordoAutorizacao = new cl_acordoempautoriza();
    $oDaoAcordoAutorizacao->excluir(null, "ac45_acordo = {$this->getCodigoAcordo()}");
    if ($oDaoAcordoAutorizacao->erro_status == 0) {
      throw new BusinessException($oDaoAcordoAutorizacao->erro_msg);
    }

    /**
     * Remove o acordo
     */
    $oDaoAcordo = new cl_acordo();
    $oDaoAcordo->excluir($this->getCodigoAcordo());

    if ($oDaoAcordo->erro_status == 0) {
      throw new BusinessException( $oDaoAcordo->erro_msg );
    }
  }

  /**
   * Realiza a paralisação de um Contrato
   *
   * @param DBDate $oDataInicio data de inicio da Paralisação
   * @param string $sObervacao  Observação
   * @throws BusinessException contrato já esta paralizado
   * @throws DBException Não existe transação ativa
   * @return AcordoParalisacao
   */
  public function paralisar(DBDate $oDataInicio, $sObervacao) {

    if ( $this->getSituacao() == self::CONTRATO_PARALISADO ) {
       throw new BusinessException( _M( self::MENSAGENS . 'contrato_ja_paralisado' ) );
    }

    if (!db_utils::inTransaction()) {
      throw new DBException(_M(self::CAMINHO_MENSAGEM . 'sem_transacao_ativa'));
    }

    $oParalisacao = new AcordoParalisacao();
    $oParalisacao->setAcordo($this);
    $oParalisacao->setDataInicio($oDataInicio);
    $oParalisacao->setObservacao($sObervacao);
    $oParalisacao->salvar();
    return $oParalisacao;
  }

   /**
   * Retorna todas as paralisações do contrato
   * @return AcordoParalisacao[]
   * @throws DBException
   */
  public function getParalisacoes() {

    if (count($this->aParalisacoes) == 0 && !empty($this->iCodigoAcordo)) {

      $oDaoAcordoParalisacao = new cl_acordoparalisacao();

      $sWhereParalisacao = "ac47_acordo = {$this->getCodigoAcordo()} ";
      $sSqlParalisacoes  = $oDaoAcordoParalisacao->sql_query_file(null,
                                                                  "ac47_sequencial",
                                                                  "ac47_sequencial",
                                                                  $sWhereParalisacao
                                                                 );
      $rsParalisacao = $oDaoAcordoParalisacao->sql_record($sSqlParalisacoes);
      for ($iParalisacao = 0; $iParalisacao < $oDaoAcordoParalisacao->numrows; $iParalisacao++ ) {

        $iCodigoParalisacao    = db_utils::fieldsMemory($rsParalisacao, $iParalisacao)->ac47_sequencial;
        $this->aParalisacoes[] = new AcordoParalisacao($iCodigoParalisacao);
      }
    }

    return $this->aParalisacoes;
  }

  /**
   * Retorna a última paralisação do acordo
   * @return AcordoParalisacao|null
   */
  public function getUltimaParalisacao() {

    $aParalisacoes = $this->getParalisacoes();
    if (count($aParalisacoes) == 0) {
      return null;
    }
    return $aParalisacoes[count($aParalisacoes) - 1];
  }

  /**
   * Define a classificado do acordo
   * @param AcordoClassificacao $oClassificacao
   */
  public function setClassificacao(AcordoClassificacao $oClassificacao) {
    $this->oClassificacao = $oClassificacao;
  }

  /**
   * Retorna a cassificacao do acordo
   * @return AcordoClassificacao
   */
  public function getClassificacao() {

    if (!$this->oClassificacao instanceof AcordoClassificacao) {
      $this->oClassificacao = new AcordoClassificacao($this->iClassificacao);
    }

    return $this->oClassificacao;
  }

  /**
   * retorna o valor do contrato / acordo
   * @return number $this->nValorContrato
   */
  public function getValorContrato(){
    return $this->nValorContrato;
  }

  /**
   * define o valor contrato / acordo
   * @param number $nValorContrato
   */
  public function setValorContrato( $nValorContrato ){
    $this->nValorContrato = $nValorContrato;
  }

  /**
   * Retorna o numero anual do acordo
   *
   * @return integer $iNumeroEmpenho
   */
  public function getNumeroAcordo() {
    return $this->iNumeroAcordo;
  }

  /**
   *
   * função para reativar um contrato apos um periodo de paralisação
   *
   * @param array          $aPeriodos periodos a serem recriados
   * @param \DBDate|object $oDataAtivacao data da reativacao
   * @param string         $sObservacao observacao do movimento de reativacao
   * @return bool
   * @throws \BusinessException
   */
  public function reativar(array $aPeriodos,  DBDate $oDataAtivacao, $sObservacao) {

    $oUltimaParalisacao   = $this->getUltimaParalisacao();
    if (empty($oUltimaParalisacao)) {
      throw new BusinessException( _M( self::MENSAGENS . ('contrato_nao_paralisado') ) );
    }
    $dtTermino = $oUltimaParalisacao->getDataTermino();

    if ( !empty($dtTermino)){

      throw new BusinessException( _M( self::MENSAGENS . ('paralisacao_ja_retomada') ) );
    }

    $oReativacaoAcordo = new AcordoReativacao($oUltimaParalisacao);
    $oReativacaoAcordo->setDataReativacao($oDataAtivacao);
    $oReativacaoAcordo->setPeriodosParalizados($aPeriodos);
    $oReativacaoAcordo->setObservacao($sObservacao);

    $oReativacaoAcordo->salvar();
  }

  public function cancelarReativacao(array $aPeriodos, $sObservacao){

    $oUltimaParalisacao = $this->getUltimaParalisacao();
    $oReativacaoAcordo  = new AcordoReativacao($oUltimaParalisacao);
    $oReativacaoAcordo->setPeriodosParalizados($aPeriodos);
    $oReativacaoAcordo->setObservacao($sObservacao);
    $oReativacaoAcordo->cancelar();
  }

  /**
   * Retorna proxima numeracao anual do acordo
   *
   * @return integer
   */
  public static function getProximoNumeroDoAno($iAno, $iInstituicao) {

    $oDaoAcordo  = new cl_acordo();
    $sWhere  = "ac16_anousu = {$iAno} and ac16_instit = {$iInstituicao}";
    $sCampo =  "(coalesce(max(ac16_numeroacordo), 0) + 1) as numero";
    $sSqlNumeroAnual = $oDaoAcordo->sql_query_file(null, $sCampo, null, $sWhere);
    $rsNumeroAnual = db_query($sSqlNumeroAnual);

    if (!$rsNumeroAnual) {
      throw new Exception("Erro ao buscar próximo número do acordo.");
    }

    return (int) db_utils::fieldsMemory($rsNumeroAnual, 0)->numero;
  }

  /**
   * Método que verifica se o acordo possui algum lançamento contábil vinculado.
   * @return bool
   */
  public function possuiLancamentoContabil() {

    $oDaoConlancamAcordo = new cl_conlancamacordo();
    $sSqlBuscaAcordo = $oDaoConlancamAcordo->sql_query_file(null, "*", null, "c87_acordo = {$this->iCodigoAcordo}");
    $rsBuscaAcordo   = $oDaoConlancamAcordo->sql_record($sSqlBuscaAcordo);
    if ($oDaoConlancamAcordo->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Retorna os itens do Acordo, com a posicao Atualizada do item
   * @return AcordoItem[]
   */
  public function getItens($tipoItem = 1) {

    $sCampoCodigoItem = ' max(ac20_sequencial) ';
    if ($tipoItem != 1) {
      $sCampoCodigoItem = ' min(ac20_sequencial) ';
    }
    $aItens          = array();
    $oDaoAcordoitem  = new cl_acordoitem;
    $sCampos         = "ac20_ordem, ac20_resumo, sum(case when ac26_acordoposicaotipo <> ".AcordoPosicao::TIPO_REEQUILIBRIO." then ac20_quantidade else 0 end) as quantidade, ";
    $sCampos        .= "sum(ac20_valortotal) as valortotal, ";
    $sCampos        .= "pc01_descrmater, pc01_codmater, {$sCampoCodigoItem} as codigo, max(ac20_acordoposicao) as posicao, ";
    $sCampos        .= "m61_codmatunid, m61_abrev ";
    $sWhere          = "ac16_sequencial = {$this->getCodigo()} ";
    $sGroup          = "group by ac20_ordem, ac20_resumo, pc01_descrmater, pc01_codmater, m61_codmatunid, m61_abrev ";
    $sSqlItens       = $oDaoAcordoitem->sql_query_transparencia( $sCampos, "ac20_ordem", $sWhere.$sGroup);
    $rsItem = $oDaoAcordoitem->sql_record($sSqlItens);
    $iTotalLinhas = $oDaoAcordoitem->numrows;
    for ($iItem = 0; $iItem < $iTotalLinhas;$iItem++) {

      $oDadosItem = db_utils::fieldsMemory($rsItem, $iItem);
      $oItem      = new AcordoItem();
      $oItem->setCodigoPosicao($oDadosItem->posicao);
      $oItem->setCodigo($oDadosItem->codigo);
      $oItem->setMaterial(MaterialComprasRepository::getByCodigo($oDadosItem->pc01_codmater));
      $oItem->setQuantidade($oDadosItem->quantidade);
      $oItem->setResumo($oDadosItem->ac20_resumo);
      $oItem->setValorTotal($oDadosItem->valortotal);
      $oItem->setUnidade($oDadosItem->m61_codmatunid);
      $oItem->setDescricaoUnidade($oDadosItem->m61_abrev);
      $oItem->setOrdem($oDadosItem->ac20_ordem);
      $aItens[] = $oItem;
    }
    return $aItens;
  }

  /**
   * Retorna as opções para o campo Tipo de Instrumento
   *
   * @return array
   */
  public static function getTiposInstrumento() {

    return array(
      self::TIPO_INSTRUMENTO_CONTRATO             => 'Contrato',
      self::TIPO_INSTRUMENTO_TERMO_ADESAO         => 'Termo de Adesão',
      self::TIPO_INSTRUMENTO_TERMO_FOMENTO        => 'Termo de Fomento',
      self::TIPO_INSTRUMENTO_TERMO_PARCERIA       => 'Termo de Parceria',
      self::TIPO_INSTRUMENTO_TERMO_CREDENCIAMENTO => 'Termo de Credenciamento',
      self::TIPO_INSTRUMENTO_TERMO_COLABORACAO    => 'Termo de Colaboração',
    );
  }

  /**
   * @param integer $iTipoInstrumento
   */
  public function setTipoInstrumento($iTipoInstrumento) {
    $this->iTipoInstrumento = $iTipoInstrumento;
  }

  /**
   * @return integer
   */
  public function getTipoInstrumento() {
    return $this->iTipoInstrumento;
  }

  /**
   * @return boolean
   */
  public function getDependeOrdemInicio() {
    return $this->lDependeOrdemInicio;
  }

  /**
   * @param boolean $lDependeOrdemInicio
   */
  public function setDependeOrdemInicio($lDependeOrdemInicio) {
    $this->lDependeOrdemInicio = $lDependeOrdemInicio;
  }

  /**
   * @return AcordoEvento[]
   * @throws DBException
   */
  public function getEventos() {

    if (count($this->aEventos) > 0){
      return $this->aEventos;
    }

    $oDaoEventos = new cl_acordoevento();
    $sSqlEventos = $oDaoEventos->sql_query_file(null, "*", "ac55_data", "ac55_acordo = {$this->getCodigo()}");
    $rsEventos   = db_query($sSqlEventos);
    if (!$rsEventos) {
      throw  new DBException("Erro ao consultar eventos do contrato {$this->getCodigo()}");
    }
    $iTotalLinhas = pg_num_rows($rsEventos);
    for ($iEvento = 0; $iEvento < $iTotalLinhas; $iEvento++) {

      $oDadosEvento = db_utils::fieldsMemory($rsEventos, $iEvento);
      $oEvento = new AcordoEvento();
      $oEvento->setCodigo($oDadosEvento->ac55_sequencial);
      $oEvento->setAcordo($this);
      $oEvento->setData(new DBDate($oDadosEvento->ac55_data));
      $oEvento->setTipoEvento($oDadosEvento->ac55_tipoevento);
      $oEvento->setVeiculoComunicacao($oDadosEvento->ac55_veiculocomunicacao);
      $oEvento->setDescricaoVeiculo($oDadosEvento->ac55_descricaopublicacao);
      $oEvento->setProcesso($oDadosEvento->ac55_numeroprocesso);
      $oEvento->setAnoProcesso($oDadosEvento->ac55_anoprocesso);
      $this->aEventos[] = $oEvento;
    }
    return $this->aEventos;
  }

  /**
   * Retorna o valor liquidado do contrato
   * @return float
   * @throws \DBException
   */
  public function getValorLiquidado() {

    $oDaoAcordoEmpenho = new cl_empempenhocontrato();

    $where           = "e100_acordo = {$this->getCodigo()}";
    $sSqlLiquidacoes = $oDaoAcordoEmpenho->sql_query(null, "coalesce(sum(e60_vlrliq), 0) as totalLiquidado", null, $where);
    $rsLiquidacoes = db_query($sSqlLiquidacoes);
    if (!$rsLiquidacoes) {
      throw new DBException("Não foi possível pesquisar o valor liquidado do acordo {$this->getCodigo()}.");
    }
    $totalLiquidado = db_utils::fieldsMemory($rsLiquidacoes, 0)->totalliquidado;
    return $totalLiquidado;
  }
}
