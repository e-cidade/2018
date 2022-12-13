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
 * Model para controle do Empenho
 * @author  Matheus Felini <matheus.felini@dbseller.com.br>
 * @package empenho
 * @version $Revision: 1.66 $
 */
class EmpenhoFinanceiro {

  /**
   * Numero - Sequencial da Tabela
   * @var integer
   */
  protected $iNumero;

  /**
   * Código do Empenh
   * @var string
   */
  protected $sCodigo;

  /**
   * Ano Uso
   * @var integer
   */
  protected $iAnoUso;

  /**
   * Dotacao
   * @var Dotacao
   */
  protected $oDotacao;

  /**
   * CGM - Fornecedor
   * @var CgmFactory
   */
  protected $oCgm;

  /**
   * Data de Emissão
   * @var string
   */
  protected $dtEmissao;

  /**
   * Data de Vencimento
   * @var string
   */
  protected $dtVencimento;

  /**
   * Valor Orcado
   * @var float
   */
  protected $nValorOrcamento;

  /**
   * Valor do Empenho
   * @var float
   */
  protected $nValorEmpenho;

  /**
   * Valor Liquidado
   * @var float
   */
  protected $nValorLiquidado;

  /**
   * Valor Anulado
   * @var float
   */
  protected $nValorAnulado;

  /**
   * Valor Pago
   * @var float
   */
  protected $nValorPago;

  /**
   * Tipo de Empenho (emptipo)
   * @var integer
   */
  protected $iTipoEmpenho;

  /**
   * Resumo
   * @var string
   */
  protected $sResumo;

  /**
   * Destino
   * @var string
   */
  protected $sDestino;

  /**
   * Saldo anterior
   * @var float
   */
  protected $nSaldoAnterior;

  /**
   * Instituição
   * @var Instituicao
   */
  protected $oInstituicao;

  /**
   * Tipo de Compra
   * @var integer
   */
  protected $iTipoCompra;

  /**
   * Característica Peculiar
   * @var string
   */
  protected $sCaracteristicaPeculiar;

  /**
   * Autorização de Empenho
   * @var AutorizacaoEmpenho
   */
  protected $oAutorizacaoEmpenho;

  /**
   * Coleção de Itens
   * @var array EmpenhoItem
   */
  protected $aItens = array();

  /**
   * Tipo do Evento (empprestatip)
   * @var integer
   */
  protected $iTipoEvento;

  /**
   * Código do Fornecedor
   * @var integer
   */
  private $iCodigoFornecedor;

  /**
   * Objeto FinalidadePagamentoFundeb
   * @var FinalidadePagamentoFundeb
   */
  private $oFinalidadePagamentoFundeb;

  /**
   * Código da Instituição
   * @var integer
   */
  private $iCodigoInstituicao;

  /**
   * Código da Dotação Orçamentária
   * @var integer
   */
  private $iCodigoDotacao;

  /**
   * Tipo de Licitação (caso houver)
   * @type integer
   */
  private $iTipoLicitacao;

  /**
   * Cotas de pagamento Mensal
   * @var EmpenhoCotaMensal[]
   */
  private $aCotasMensais  = array();

  /**
   * @type NotaLiquidacao[]
   */
  private $aNotasLiquidadas = array();

  private $iNumeroLicitacao = null;

  /**
   * @var integer
   */
  private $iCodigoListaClassificacaoCredor;

  /**
   * @var ListaClassificacaoCredor
   */
  private $oListaClassificacaoCredor;

  /**
   * @var TipoPrestacaoConta
   */
  private $oTipoPrestacaoConta;

  /**
   * Carrega as propriedades de um empenho
   * @param integer  - Numero do Empenho
   * @return EmpenhoFinanceiro
   * @throws BusinessException
   */
  public function __construct($iNumero = null) {

    $this->setNumero($iNumero);
    if (!empty($iNumero)) {

      $oDaoEmpEmpenho   = new cl_empempenho();
      $sSqlBuscaEmpenho = $oDaoEmpEmpenho->sql_query_file($iNumero);
      $rsBuscaEmpenho   = $oDaoEmpEmpenho->sql_record($sSqlBuscaEmpenho);

      if ($oDaoEmpEmpenho->numrows == 0) {
        throw new BusinessException("Empenho {$iNumero} não localizado.");
      }

      $oDadoEmpenho = db_utils::fieldsMemory($rsBuscaEmpenho, 0);
      $this->setAnoUso($oDadoEmpenho->e60_anousu);
      $this->setCaracteristicaPeculiar($oDadoEmpenho->e60_concarpeculiar);
      $this->iCodigoFornecedor = $oDadoEmpenho->e60_numcgm;
      $this->setCodigo($oDadoEmpenho->e60_codemp);
      $this->setDataEmissao($oDadoEmpenho->e60_emiss);
      $this->setDataVencimento($oDadoEmpenho->e60_vencim);
      $this->setDestino($oDadoEmpenho->e60_destin);
      $this->iCodigoInstituicao = $oDadoEmpenho->e60_instit;
      $this->iCodigoDotacao     = $oDadoEmpenho->e60_coddot;
      $this->setNumero($oDadoEmpenho->e60_numemp);
      $this->setResumo($oDadoEmpenho->e60_resumo);
      $this->setSaldoAnterior($oDadoEmpenho->e60_salant);
      $this->setTipoCompra($oDadoEmpenho->e60_codcom);
      $this->setTipoLicitacao($oDadoEmpenho->e60_tipol);
      $this->setTipoEmpenho($oDadoEmpenho->e60_codtipo);
      $this->setValorAnulado($oDadoEmpenho->e60_vlranu);
      $this->setValorEmpenho($oDadoEmpenho->e60_vlremp);
      $this->setValorLiquidado($oDadoEmpenho->e60_vlrliq);
      $this->setValorOrcamento($oDadoEmpenho->e60_vlrorc);
      $this->setValorPago($oDadoEmpenho->e60_vlrpag);
      $this->setNumeroDaLicitacao($oDadoEmpenho->e60_numerol);
      unset($oDadoEmpenho);
    }
  }


  /**
   * Este metodo salva os dados do empenho em 'empempenho', salva os itens do empenho em 'empempitem'
   * salva tambem o vinculo com a autorizacao de empenho e o vínculo com o elemento dos itens
   *
   * @return bool true
   * @throws BusinessException
   */
  public function salvar() {

    $oDaoEmpEmpenho                     = db_utils::getDao('empempenho');
    $oDaoEmpEmpenho->e60_numemp         = $this->getNumero();
    $oDaoEmpEmpenho->e60_codemp         = $this->getProximoCodigoEmpenho();    //$this->getCodigo();
    $oDaoEmpEmpenho->e60_anousu         = db_getsession("DB_anousu");
    $oDaoEmpEmpenho->e60_coddot         = $this->getDotacao()->getCodigo();
    $oDaoEmpEmpenho->e60_numcgm         = $this->getCgm()->getCodigo();
    $oDaoEmpEmpenho->e60_emiss          = $this->getDataEmissao();
    $oDaoEmpEmpenho->e60_vencim         = $this->getDataVencimento();
    $oDaoEmpEmpenho->e60_vlrorc         = $this->getValorOrcamento();
    $oDaoEmpEmpenho->e60_vlremp         = $this->getValorEmpenho();
    $oDaoEmpEmpenho->e60_vlrliq         = $this->getValorLiquidado();
    $oDaoEmpEmpenho->e60_vlrpag         = $this->getValorPago();
    $oDaoEmpEmpenho->e60_vlranu         = $this->getValorAnulado();
    $oDaoEmpEmpenho->e60_codtipo        = $this->getTipoEmpenho();
    $oDaoEmpEmpenho->e60_resumo         = $this->getResumo();
    $oDaoEmpEmpenho->e60_destin         = $this->getDestino();
    $oDaoEmpEmpenho->e60_salant         = $this->getSaldoAnterior();
    $oDaoEmpEmpenho->e60_instit         = $this->getInstituicao()->getSequencial();
    $oDaoEmpEmpenho->e60_codcom         = $this->getTipoCompra();
    $oDaoEmpEmpenho->e60_concarpeculiar = $this->getCaracteristicaPeculiar();
    $oDaoEmpEmpenho->e60_numerol        = $this->getAutorizacaoEmpenho()->getNumeroLicitacao();
    $oDaoEmpEmpenho->e60_tipol          = $this->getAutorizacaoEmpenho()->getTipoLicitacao();

    if ($this->getNumero() == "") {

      $oDaoEmpEmpenho->incluir(null);
      $this->setNumero($oDaoEmpEmpenho->e60_numemp);
      $this->setCodigo($oDaoEmpEmpenho->e60_codemp);

      $oDaoEmpenhoNotaLiquidacao                 = db_utils::getDao('empempenhonl');
      $oDaoEmpenhoNotaLiquidacao->e68_sequencial = null;
      $oDaoEmpenhoNotaLiquidacao->e68_numemp     = $this->getNumero();
      $oDaoEmpenhoNotaLiquidacao->e68_data       = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoEmpenhoNotaLiquidacao->incluir(null);

      if ($oDaoEmpenhoNotaLiquidacao->erro_status == 0) {

        $sMsgErro  = "Impossível criar o vínculo da nota de liquidação.";
        $sMsgErro .= "\n\nErro Técnico: {$oDaoEmpenhoNotaLiquidacao->erro_msg}";
        throw new BusinessException($sMsgErro);
      }

    } else {
      $oDaoEmpEmpenho->alterar($this->getNumero());
    }

    if ($oDaoEmpEmpenho->erro_status == 0) {

      $sMsgErro = "Não foi possível incluir os dados do empenho.\n\nErro Técnico: {$oDaoEmpEmpenho->erro_msg}";
      throw new BusinessException($sMsgErro);
    }

    /*
     * Verifico se o tipo do evento nao e empenho normal. Caso nao seja,
     * e incluido vinculo para futura prestação de contas
     */
    $oDaoEmPrestaTipo = new cl_empprestatip();
    if ($this->getTipoEvento() != '') {

      $sSqlTipoEvento = $oDaoEmPrestaTipo->sql_query_file($this->getTipoEvento());
      $rsTipoEvento   = $oDaoEmPrestaTipo->sql_record($sSqlTipoEvento);
      if (!$rsTipoEvento || $oDaoEmPrestaTipo->numrows == 0) {
        throw new BusinessException("Tipo de Evento informado não possui cadastro.");
      }
      $oDadosEvento = db_utils::fieldsMemory($rsTipoEvento, 0);
      if ($oDadosEvento->e44_obriga != 0) {

        $oDaoEmpPresta             = db_utils::getDao('emppresta');
        $oDaoEmpPresta->e45_numemp = $this->getNumero();
        $oDaoEmpPresta->e45_data   = date("Y-m-d",db_getsession("DB_datausu"));
        $oDaoEmpPresta->e45_tipo   = $this->getTipoEvento();
        $oDaoEmpPresta->incluir($this->getNumero());
        if ($oDaoEmpPresta->erro_status == 0) {
          throw new BusinessException("Impossível incluir vínculo para prestação de contas.");
        }
      }
    }

    /*
     * Variavel que soma o valor total do item, para salvar associando ao elemento em 'empelemento'
     */
    $nValorTotalEmpenho = 0;

    /*
     * CODELE do item. Como serão iguais apenas sobescrevo o valor enquanto estou salvando os itens
     */
    $iElementoItens = 0;

    /*
     * Salvamos os itens referente ao empenho
     */
    $aItensEmpenho = $this->getItens();
    foreach ($aItensEmpenho as $oEmpenhoItem) {

      $oEmpenhoItem->setNumeroEmpenho($oDaoEmpEmpenho->e60_numemp);
      $oEmpenhoItem->salvar();
      $nValorTotalEmpenho += $oEmpenhoItem->getValorTotal();
      $iElementoItens      = $oEmpenhoItem->getCodigoElemento();
    }


    /*
     * Vinculamos o empenho a uma autorizacao de empenho (empempaut)
     * Para isso, excluimos o vínculo existente
     */
    $oDaoEmpEmpAut             = db_utils::getDao('empempaut');
    $oDaoEmpEmpAut->e61_numemp = $this->getNumero();
    $oDaoEmpEmpAut->e61_autori = $this->getAutorizacaoEmpenho()->getAutorizacao();
    $oDaoEmpEmpAut->incluir($this->getNumero());

    if ($oDaoEmpEmpAut->erro_status == 0) {

      $sMsgErroVinculo  = "Impossível criar o vínculo entre o empenho e a autorização.\n\n";
      $sMsgErroVinculo .= "Erro Técnico: {$oDaoEmpEmpAut->erro_msg}";
      throw new BusinessException($sMsgErroVinculo);
    }

    /*
     * Incluimos os registros na empelemento
     */
    $oDaoEmpElemento             = db_utils::getDao('empelemento');
    $oDaoEmpElemento->e64_numemp = $this->getNumero();
    $oDaoEmpElemento->e64_codele = $iElementoItens;
    $oDaoEmpElemento->e64_vlremp = $nValorTotalEmpenho;
    $oDaoEmpElemento->e64_vlrliq = "0";
    $oDaoEmpElemento->e64_vlranu = "0";
    $oDaoEmpElemento->e64_vlrpag = "0";
    $oDaoEmpElemento->incluir($this->getNumero(), $iElementoItens);

    if ($oDaoEmpElemento->erro_status == 0) {

      $sErroMsg = "Não foi possível vincular o elemento ao empenho.\n\nErro Técnico: {$oDaoEmpElemento->erro_msg}";
      throw new BusinessException($sErroMsg);
    }

    return true;
  }


  /**
   * Seta um objeto do tipo AutorizacaoEmpenho
   * @param AutorizacaoEmpenho $oAutorizacaoEmpenho
   */
  public function setAutorizacaoEmpenho(AutorizacaoEmpenho $oAutorizacaoEmpenho) {
    $this->oAutorizacaoEmpenho = $oAutorizacaoEmpenho;
  }

  /**
   * Retorna um objeto da AutorizacaoEmpenho
   * Caso o objeto ainda não exista em memoria, ele é criado e setado na propriedade $oAutorizacaoEmpenho
   * @return AutorizacaoEmpenho
   */
  public function getAutorizacaoEmpenho() {

    if (empty($this->oAutorizacaoEmpenho)) {

      $oDaoEmpEmpAut        = db_utils::getDao("empempaut");
      $sSqlBuscaAutorizacao = $oDaoEmpEmpAut->sql_query_file($this->getNumero(), "e61_autori");
      $rsBuscaAutorizacao   = $oDaoEmpEmpAut->sql_record($sSqlBuscaAutorizacao);
      $iCodigoAutorizacao   = db_utils::fieldsMemory($rsBuscaAutorizacao, 0)->e61_autori;
      $this->setAutorizacaoEmpenho(new AutorizacaoEmpenho($iCodigoAutorizacao));
    }
    return $this->oAutorizacaoEmpenho;
  }

  /**
   * Este metodo verifica se o empenho é um empenho originado de inscricao passivo
   * @return boolean
   */
  public function isEmpenhoPassivo() {

    $oDaoEmpEmpAut    = db_utils::getDao("empempaut");
    $sSqlBuscaEmpenho = $oDaoEmpEmpAut->sql_query_empenho_inscricaopassivo($this->getNumero());
    $rsBuscaEmpenho   = $oDaoEmpEmpAut->sql_record($sSqlBuscaEmpenho);
    if ($oDaoEmpEmpAut->numrows == 1) {
      return true;
    }
    return false;
  }


  /**
   * Retorna uma coleção de objeto do tipo EmpenhoItem
   * @return EmpenhoFinanceiroItem[]
   */
  public function getItens() {

    if (count($this->aItens) == 0 && !empty($this->iNumero)) {

      $oDaoEmpEmpItem    = db_utils::getDao("empempitem");
      $sSqlBuscaItem     = $oDaoEmpEmpItem->sql_query_file($this->getNumero(), null, "e62_sequencial");
      $rsBuscaItem       = $oDaoEmpEmpItem->sql_record($sSqlBuscaItem);
      $iLinhasRetornadas = $oDaoEmpEmpItem->numrows;

      if ($iLinhasRetornadas > 0) {

        for ($iRowItem = 0; $iRowItem < $iLinhasRetornadas; $iRowItem++) {

          $iCodigoItem  = db_utils::fieldsMemory($rsBuscaItem, $iRowItem)->e62_sequencial;
          $oEmpenhoItem = new EmpenhoFinanceiroItem($iCodigoItem);
          $this->adicionarItem($oEmpenhoItem);
        }
      }
    }
    return $this->aItens;
  }

  /**
   * retorna o proximo numero de empenho
   * tabela de parametros
   * @return integer
   */
  public function getProximoCodigoEmpenho() {


    $oDaoEmpparametroNumero    = db_utils::getDao("empparamnum");
    $sSqlEmpparametroNumeracao = $oDaoEmpparametroNumero->sql_query_file($this->getAnoUso(),
                                                                         db_getsession("DB_instit"),"
                                                                         (e29_codemp + 1) as e60_codemp"
                                                                        );
    $rsNumeracao               = $oDaoEmpparametroNumero->sql_record($sSqlEmpparametroNumeracao);
    if ($oDaoEmpparametroNumero->numrows == 0) {

      $oDaoEmpparametro  = db_utils::getDao('empparametro');
      $sSqlProximoCodigo = $oDaoEmpparametro->sql_query($this->getAnoUso());
      $rsProximoCodigo   = $oDaoEmpparametro->sql_record($sSqlProximoCodigo);
      $iCodigoEmpenho    = db_utils::fieldsMemory($rsProximoCodigo, 0)->e30_codemp + 1;

      $oDaoEmpparametro->e39_anousu = $this->getAnoUso();
      $oDaoEmpparametro->e30_codemp = $iCodigoEmpenho;
      $oDaoEmpparametro->alterar($oDaoEmpparametro->e39_anousu);
      if ($oDaoEmpparametro->erro_status == "0") {

        throw new BusinessException($oDaoEmpparametro->erro_msg);
      }
    } else {

      $iCodigoEmpenho                     = db_utils::fieldsMemory($rsNumeracao, 0)->e60_codemp;
      $oDaoEmpparametroNumero->e29_anousu = $this->getAnoUso();
      $oDaoEmpparametroNumero->e29_instit = db_getsession('DB_instit');
      $oDaoEmpparametroNumero->e29_codemp = $iCodigoEmpenho;
      $oDaoEmpparametroNumero->alterar($this->getAnoUso(), db_getsession('DB_instit'));
      if ($oDaoEmpparametroNumero->erro_status == 0) {
        throw new BusinessException($oDaoEmpparametroNumero->erro_msg);
      }
    }

    return $iCodigoEmpenho;
  }

  /**
   * Adiciona um objeto do tipo EmpenhoItem ao array
   * @param EmpenhoFinanceiroItem $oEmpenhoItem
   */
  public function adicionarItem(EmpenhoFinanceiroItem $oEmpenhoItem) {
    $this->aItens[] = $oEmpenhoItem;
  }

  /**
   * Retorna o Sequencial
   * @return integer
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * Seta o Sequencial
   * @param integer $iNumero
   */
  public function setNumero($iNumero) {
    $this->iNumero = $iNumero;
  }

  /**
   * Retorna o Código
   * @return string
   */
  public function getCodigo() {
    return $this->sCodigo;
  }

  /**
   * Seta o Codigo
   * @param string $sCodigo
   */
  public function setCodigo($sCodigo) {
    $this->sCodigo = $sCodigo;
  }

  /**
   * Retorna o ano
   * @deprecated
   * @see getAno()
   */
  public function getAnoUso() {
    return $this->iAnoUso;
  }

  /**
   * @return int
   */
  public function getAno() {
    return $this->iAnoUso;
  }

  /**
   * Seta o Ano
   * @param integer $iAnoUso
   * @deprecated
   * @see setAno
   */
  public function setAnoUso($iAnoUso) {
    $this->iAnoUso = $iAnoUso;
  }

  /**
   * @param $iAno
   */
  public function setAno($iAno) {
    $this->iAnoUso = $iAno;
  }

  /**
   * Retorna a dotacao
   * @return Dotacao
   */
  public function getDotacao() {

    if (empty($this->oDotacao)) {
      $this->setDotacao(DotacaoRepository::getDotacaoPorCodigoAno($this->iCodigoDotacao, $this->iAnoUso));
    }
    return $this->oDotacao;
  }

  /**
   * Seta a Dotacao
   * @param Dotacao $oDotacao
   */
  public function setDotacao(Dotacao $oDotacao) {
    $this->oDotacao = $oDotacao;
  }

  /**
   * Retorna o Fornecedor do Empenho
   * @return CgmBase|CgmFisico|CgmJuridico
   */
  public function getCgm() {
    return $this->oCgm = CgmFactory::getInstanceByCgm($this->iCodigoFornecedor);
  }

  /**
   * Retorna o fornecedor do empenho
   *
   * @access public
   * @return CgmFisico|CgmJuridico
   */
  public function getFornecedor() {
    return $this->getCgm();
  }

  /**
   * Seta o CGM
   * @param CgmBase $oCgm
   */
  public function setCgm($oCgm) {

    $this->iCodigoFornecedor = $oCgm->getCodigo();
    $this->oCgm              = $oCgm;
  }

  /**
   * Retorna o data emissao
   */
  public function getDataEmissao() {
    return $this->dtEmissao;
  }

  /**
   * Seta a Data de Emissao
   * @param string $dtEmissao
   */
  public function setDataEmissao($dtEmissao) {
    $this->dtEmissao = $dtEmissao;
  }
  /**
   * Retorna o data vencimento
   */
  public function getDataVencimento() {
    return $this->dtVencimento;
  }

  /**
   * Seta o Data Vencimento
   * @param string $dtVencimento
   */
  public function setDataVencimento($dtVencimento) {
      $this->dtVencimento = $dtVencimento;
  }

  /**
   * Retorna o valor orcado
   */
  public function getValorOrcamento() {
    return $this->nValorOrcamento;
  }

  /**
   * Seta o Valor Orcado
   * @param float $nValorOrcamento
   */
  public function setValorOrcamento($nValorOrcamento) {
    $this->nValorOrcamento = $nValorOrcamento;
  }

  /**
   * Retorna o valor empenho
   */
  public function getValorEmpenho() {
    return $this->nValorEmpenho;
  }

  /**
   * Seta o Valor Empenho
   * @param float $nValorEmpenho
   */
  public function setValorEmpenho($nValorEmpenho) {
    $this->nValorEmpenho = $nValorEmpenho;
  }
  /**
   * Retorna o valor liquidado
   */
  public function getValorLiquidado() {
    return $this->nValorLiquidado;
  }

  /**
   * Seta o Valor Liquidado
   * @param float $nValorLiquidado
   */
  public function setValorLiquidado($nValorLiquidado) {
    $this->nValorLiquidado = $nValorLiquidado;
  }
  /**
   * Retorna o valor anulado
   */
  public function getValorAnulado() {
    return $this->nValorAnulado;
  }

  /**
   * Seta o Valor Anulado
   * @param float $nValorAnulado
   */
  public function setValorAnulado($nValorAnulado) {
    $this->nValorAnulado = $nValorAnulado;
  }

  /**
   * Retorna o tipo de empenho
   */
  public function getTipoEmpenho() {
    return $this->iTipoEmpenho;
  }

  /**
   * @return string
   */
  public function getDescricaoTipoEmpenho() {

    if (empty($this->iTipoEmpenho)) {
      return '';
    }

    $oDaoEmptipo = new cl_emptipo();
    $sSqlEmptipo = $oDaoEmptipo->sql_query_file($this->getTipoEmpenho(), "e41_descr");
    $rsEmptipo   = $oDaoEmptipo->sql_record( $sSqlEmptipo );

    if ($oDaoEmptipo->numrows > 0) {
      return db_utils::fieldsMemory($rsEmptipo, 0)->e41_descr;
    }

    return '';
  }

  /**
   * Seta o Tipo
   * @param integer $iTipoEmpenho
   */
  public function setTipoEmpenho($iTipoEmpenho) {
    $this->iTipoEmpenho = $iTipoEmpenho;
  }

  /**
   * Retorna o resumo
   */
  public function getResumo() {
    return $this->sResumo;
  }

  /**
   * Seta o Resumo
   * @param string $sResumo
   */
  public function setResumo($sResumo) {
      $this->sResumo = $sResumo;
  }

  /**
   * Retorna o destino
   */
  public function getDestino() {
    return $this->sDestino;
  }

  /**
   * Seta o Destino
   * @param string $sDestino
   */
  public function setDestino($sDestino) {
    $this->sDestino = $sDestino;
  }

  /**
   * Retorna o saldo anterior
   */
  public function getSaldoAnterior() {
    return $this->nSaldoAnterior;
  }

  /**
   * Seta o Saldo Anterior
   * @param float $nSaldoAnterior
   */
  public function setSaldoAnterior($nSaldoAnterior) {
    $this->nSaldoAnterior = $nSaldoAnterior;
  }

  /**
   * Retorna a Instituicao
   * @return Instituicao
   */
  public function getInstituicao() {

    if (empty($this->oInstituicao)) {
      $this->setInstituicao(new Instituicao($this->iCodigoInstituicao));
    }
    return $this->oInstituicao;
  }

  /**
   * Seta a Instituicao
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Retorna o tipo de compra
   */
  public function getTipoCompra() {
    return $this->iTipoCompra;
  }

  /**
   * Seta o Tipo de Compra
   * @param integer $iTipoCompra
   */
  public function setTipoCompra($iTipoCompra) {
    $this->iTipoCompra = $iTipoCompra;
  }

  /**
   * Retorna a caracteristica peculiar
   */
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }

  /**
   * Seta a Caracteristica Peculiar
   * @param string $sCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar;
  }

  /**
   * Retorna o valor pago
   */
  public function getValorPago() {
    return $this->nValorPago;
  }

  /**
   * Seta o Valor Pago
   * @param float $nValorPago
   */
  public function setValorPago($nValorPago) {
    $this->nValorPago = $nValorPago;
  }

  /**
   * Retorna o tipo do evento
   * @return integer
   */
  public function getTipoEvento() {
    return $this->iTipoEvento;
  }
  /**
   * Seta o tipo do evento
   * @param integer
   */
  public function setTipoEvento($iTipoEvento) {
    $this->iTipoEvento = $iTipoEvento;
  }

  /**
   * Verifica se o empenho e uma prestacao de contas
   * Se o empenho esta na emppresta ele eh uma prestacao de contas
   * @return boolean
   */
  public function isPrestacaoContas() {

    $oDaoPrestacaContas = db_utils::getDao('emppresta');
    $sSqlPrestacaContas = $oDaoPrestacaContas->sql_query_file(null, "1", null, "e45_numemp = {$this->iNumero}");
    $rsPrestacaContas   = $oDaoPrestacaContas->sql_record($sSqlPrestacaContas);
    if ($oDaoPrestacaContas->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Busca os dados da prestacao de contas do empenho.
   * OBS.: Se o empenho nao esta na emppresta, ele nao eh uma prestacao de contas
   * @return boolean | stdClass
   */
  public function getDadosPrestacaoContas() {

    if ($this->isPrestacaoContas()) {

      $oDaoPrestacaContas = db_utils::getDao('emppresta');
      $sSqlPrestacaContas = $oDaoPrestacaContas->sql_query_file(null, "*", null, "e45_numemp = {$this->iNumero}");
      $rsPrestacaContas   = $oDaoPrestacaContas->sql_record($sSqlPrestacaContas);
      return db_utils::fieldsMemory($rsPrestacaContas, 0);
    }
    return false;
  }

  /**
   * Retorna o codigo do elemento do empenho
   * @return integer (e64_codele - empelemento)
   */
  public function getDesdobramentoEmpenho() {

    $oDaoEmpElemento   = db_utils::getDao('empelemento');
    $sSqlBuscaElemento = $oDaoEmpElemento->sql_query_file($this->getNumero());
    $rsBuscaElemento   = $oDaoEmpElemento->sql_record($sSqlBuscaElemento);
    return db_utils::fieldsMemory($rsBuscaElemento, 0)->e64_codele;
  }

  /**
   * verifica grupo do desdobramento do empenho  se pertence ao grupo provisao ferias
   * @return bool retorna true se o desdobramento previsao de ferias
   */
  public function isProvisaoFerias() {
    return $this->verificaGrupoDoDesdobramento(12);
  }

  /**
   * verifica grupo do desdobramento do empenho  se pertence ao grupo provisao decimoterceiro
   * @return bool
   */
  public function isProvisaoDecimoTerceiro() {
    return $this->verificaGrupoDoDesdobramento(13);
  }

  /**
   * Verifica se empenho e do grupo de Amortizacao de Divida
   * @return boolean
   */
  public function isAmortizacaoDivida() {
    return $this->verificaGrupoDoDesdobramento(10);
  }

  /**
   * Verifica se o empenho e do grupo de precatorias
   * @return boolean
   */
  public function isPrecatoria() {
    return $this->verificaGrupoDoDesdobramento(15);
  }

  /**
   * @return int
   */
  public function getTipoLicitacao() {
    return $this->iTipoLicitacao;
  }

  /**
   * @param integer $iTipoLicitacao
   */
  public function setTipoLicitacao($iTipoLicitacao) {
    $this->iTipoLicitacao = $iTipoLicitacao;
  }

  /**
   * Seta a finalidade de pagamento
   * Só irá existir caso a dotação possua recurso fundeb
   * @param FinalidadePagamentoFundeb $oFinalidadePagamentoFundeb
   */
  public function setFinalidadePagamentoFundeb(FinalidadePagamentoFundeb $oFinalidadePagamentoFundeb) {
    $this->oFinalidadePagamentoFundeb = $oFinalidadePagamentoFundeb;
  }

  /**
   * Retorna a finalidade de pagamento do fundeb
   * @return FinalidadePagamentoFundeb
   */
  public function getFinalidadePagamentoFundeb() {

    if (empty($this->oFinalidadePagamentoFundeb)) {

      $oDaoFinalidadePagamento      = db_utils::getDao('empempenhofinalidadepagamentofundeb');
      $sSqlBuscaFinalidadePagamento = $oDaoFinalidadePagamento->sql_query_file(null,
                                                                               "e152_finalidadepagamentofundeb",
                                                                               null,
                                                                               "e152_numemp = {$this->getNumero()}");
      $rsBuscaFinalidadePagamento = $oDaoFinalidadePagamento->sql_record($sSqlBuscaFinalidadePagamento);
      if ($oDaoFinalidadePagamento->numrows == 1) {

        $iFinalidadePagamento = db_utils::fieldsMemory($rsBuscaFinalidadePagamento, 0)->e152_finalidadepagamentofundeb;
        $this->oFinalidadePagamentoFundeb = new FinalidadePagamentoFundeb($iFinalidadePagamento);
      }
    }
    return $this->oFinalidadePagamentoFundeb;
  }

  /**
   * Verifica se desobramento do empenho pertencao ao grupo
   * Retorna se o desobramento do empenho pertence ao grupo $iGrupo passado como parâmetro
   *
   * @param integer $iGrupo Codigo do grupo que se deseja verificar
   * @return boolean
   */
  public function verificaGrupoDoDesdobramento($iGrupo) {

    $iCodigoConta = $this->getDesdobramentoEmpenho();
    $oGrupo       = GrupoContaOrcamento::getGrupoConta($iCodigoConta, $this->getAnoUso());

    if ($oGrupo instanceof GrupoContaOrcamento && $oGrupo->getCodigo() == $iGrupo) {
      return true;
    }
    return false;
  }

  /**
   * Verifica se o empenho financeiro se tornou um resto a pagar na virada do exercício.
   * @param integer $iAno ano da verificacao do resto a pagar
   * @return boolean
   */
  public function isRestoAPagar($iAno) {

    $oDaoEmpPresta        = db_utils::getDao('empresto');
    $sSqlBuscaRestosPagar = $oDaoEmpPresta->sql_query_file($iAno, $this->getNumero(), "e91_anousu");
    $rsBuscaRestosPagar   = $oDaoEmpPresta->sql_record($sSqlBuscaRestosPagar);
    if ($oDaoEmpPresta->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Retorna o codigo do contrato vinculado ao empenho
   * @return integer
   */
  public function getCodigoContrato() {

    $oDaoEmpenhoContrato = db_utils::getDao("empempenhocontrato");
    $sSqlContrato        = $oDaoEmpenhoContrato->sql_query_file(null,
                                                                "e100_acordo",
                                                                null,
                                                                "e100_numemp = {$this->getNumero()}"
                                                                );

    $rsContrato = $oDaoEmpenhoContrato->sql_record($sSqlContrato);
    if ($oDaoEmpenhoContrato->numrows > 0) {
      return db_utils::fieldsMemory($rsContrato, 0)->e100_acordo;
    }
  }

  /**
   * Salva a finalidade de pagamento
   * @throws BusinessException
   * @return boolean
   */
  public function salvarFinalidadePagamentoFundeb() {

    if (empty($this->oFinalidadePagamentoFundeb)) {
      throw new BusinessException("O objeto FinalidadePagamentoFundeb não foi setado.");
    }

    $oDaoEmpenhoFinalidadePagamentoExcluir = db_utils::getDao('empempenhofinalidadepagamentofundeb');
    $oDaoEmpenhoFinalidadePagamentoExcluir->excluir(null, "e152_numemp = {$this->getNumero()}");

    $oDaoEmpenhoFinalidadePagamento = db_utils::getDao('empempenhofinalidadepagamentofundeb');
    $oDaoEmpenhoFinalidadePagamento->e152_sequencial                = null;
    $oDaoEmpenhoFinalidadePagamento->e152_numemp                    = $this->getNumero();
    $oDaoEmpenhoFinalidadePagamento->e152_finalidadepagamentofundeb = $this->oFinalidadePagamentoFundeb->getCodigoSequencial();
    $oDaoEmpenhoFinalidadePagamento->incluir(null);

    if ($oDaoEmpenhoFinalidadePagamento->erro_status == "0") {

      $sLocalizacao = "financeiro.empenho.EmpenhoFinanceiro.vinculo_empenho_finalidadepagamentofundeb";
      throw new BusinessException(_M($sLocalizacao));
    }
    return true;
  }

  /**
   * metodo que retorna a conta do plano orçamentario
   * @return ContaOrcamento
   */
  public function getContaOrcamento() {
  	return ContaOrcamentoRepository::getContaByCodigo($this->getDesdobramentoEmpenho(), db_getsession("DB_anousu"), null, null);
  }


  /**
   * funcao para verificar se trata de um RP apartir de um documento especifico,
   * para mudarmos os coddoc para 39 / 40
   * se for, retorna true
   * @param  $iCodDoc, codigo do documento de RP , 212 vira 39 , 213 vira 40
   * @return boolean
   *
   */
  public function empenhoRestosPagarPorDocumento($iCodDoc, $iAnoUsu = null){

    $lRestoPagar  = false;
    $iNumEmp      = $this->getNumero();

    $sWhere       = "     c71_coddoc = {$iCodDoc} ";
    $sWhere      .= " and c75_numemp = {$iNumEmp} ";
    if (!empty($iAnoUsu)) {
      $sWhere    .= " and c70_anousu = {$iAnoUsu} ";
    }

    $oDaoRp = new cl_conlancamdoc();
    $sSqlRp = $oDaoRp->sql_queryEmpenhoRP(null, "*", null, $sWhere);
    $rsRP   = $oDaoRp->sql_record($sSqlRp);
    if ( $oDaoRp->numrows > 0 ) {
      $lRestoPagar = true;
    }

    return $lRestoPagar;
  }

  /**
   * Verifica se o empenho possui vínculo com alguma conta corrente
   * @return TipoCompra
   */
  public function getTipoDeCompra() {
    return new TipoCompra($this->getTipoCompra());
  }

  /**
   * Verifica se o empenho possui vínculo com alguma conta corrente
   * @return bool
   * @throws BusinessException
   */
  public function temContaCorrente() {

    $sWhere                   = "c19_numemp = {$this->getNumero()}";
    $sCampos                  = "count(*) as resultados";
    $oDaoContaCorrenteDetalhe = new cl_contacorrentedetalhe();
    $sSql                     = $oDaoContaCorrenteDetalhe->sql_query_lancamentos(null, $sCampos, null, $sWhere);
    $rsContagem               = $oDaoContaCorrenteDetalhe->sql_record($sSql);
    if ($oDaoContaCorrenteDetalhe->numrows == 0 || !$rsContagem) {
      throw new BusinessException("Erro ao verificar contas correntes do empenho {$this->getCodigo()}/{$this->getAnoUso()}.");
    }
    return (db_utils::fieldsMemory($rsContagem, 0)->resultados > 0);
  }

  /**
   * Retorna as Cotas Mensais do Empenho
   *
   * @return EmpenhoCotaMensal[]
   * @throws BusinessException
   */
  public function getCotasMensais() {

    if (count($this->aCotasMensais) > 0) {
      return $this->aCotasMensais;
    }

    $oDaoEmpenhoCotaMensal = new cl_empenhocotamensal();
    $sSqlCotasMensais      = $oDaoEmpenhoCotaMensal->sql_query_file(null, "*", "e05_mes", "e05_numemp = {$this->getNumero()}");
    $rsCotasMensais        = db_query($sSqlCotasMensais);

    if (!$rsCotasMensais) {
      throw new BusinessException((_M("financeiro.empenho.EmpenhoFinanceiro.erro_retorno_cotas_mensais")));
    }
    $aCotas       = array();
    $iTotalLinhas = pg_num_rows($rsCotasMensais);
    for ($iCota = 0; $iCota < $iTotalLinhas; $iCota++) {

      $oDadosCota = db_utils::fieldsMemory($rsCotasMensais, $iCota);
      $oCota      = new EmpenhoCotaMensal();

      $oCota->setValor($oDadosCota->e05_valor);
      $oCota->setMes($oDadosCota->e05_mes);
      $aCotas[$oDadosCota->e05_mes] = $oCota;


    }
    /**
     * Adicionamos os meses nao lançados
     */
    for ($iMes = 1; $iMes <= 12; $iMes++) {

      if (isset($aCotas[$iMes])) {
        continue;
      }
      $oCota = new EmpenhoCotaMensal();
      $oCota->setMes($iMes);
      $oCota->setValor(0);
      $aCotas[] = $oCota;
    }

    uasort($aCotas, function($oMes , $oProximoMes) {
      return ($oProximoMes->getMes() > $oMes->getMes()) ? -1 : 1;
    });
    return $aCotas;

  }

  /**
   * @param EmpenhoCotaMensal[] $aCotasMensais
   * @throws BusinessException
   * @throws ParameterException
   */
  public function adicionarCotas(array $aCotasMensais) {

    if (!is_array($aCotasMensais)) {
      throw new ParameterException(_M("financeiro.empenho.EmpenhoFinanceiro.parametro_cotas_nao_array"));
    }

    $nValorCotas = 0;
    foreach ($aCotasMensais as $oCota) {
      $nValorCotas += $oCota->getValor();
    }

    if ($nValorCotas != 0 && round($nValorCotas, 2) != round($this->getValorEmpenho(), 2)) {
      throw new BusinessException(_M("financeiro.empenho.EmpenhoFinanceiro.valor_total_cotas"));
    }

    $oDaoEmpenhoCotaMensal = new cl_empenhocotamensal();
    $oDaoEmpenhoCotaMensal->excluir(null, "e05_numemp={$this->getNumero()}");
    if ($oDaoEmpenhoCotaMensal->erro_status == "0") {
      throw new BusinessException(_M("financeiro.empenho.EmpenhoFinanceiro.erro_manuntecao_cotas"));
    }

    foreach ($aCotasMensais as $oCota) {

      $oDaoEmpenhoCotaMensal = new cl_empenhocotamensal();
      $oDaoEmpenhoCotaMensal->e05_mes    = $oCota->getMes();
      $oDaoEmpenhoCotaMensal->e05_valor  = "'{$oCota->getValor()}'";
      $oDaoEmpenhoCotaMensal->e05_numemp = $this->getNumero();
      $oDaoEmpenhoCotaMensal->e05_sequencial = null;
      $oDaoEmpenhoCotaMensal->incluir(null);
      if ($oDaoEmpenhoCotaMensal->erro_status == "0") {
        throw new BusinessException(_M("financeiro.empenho.EmpenhoFinanceiro.erro_manuntecao_cotas"));
      }
    }
  }

  public function temCotaMensais() {

    if ($this->getNumero() == null) {
      return false;
    }
    $oDaoEmpenhoCotaMensal = new cl_empenhocotamensal();
    $sSqlCotasMensais      = $oDaoEmpenhoCotaMensal->sql_query_file(null, "count(*) as cotas", "", "e05_numemp = {$this->getNumero()}");
    $rsCotasMensais        = db_query($sSqlCotasMensais);
    if (!$rsCotasMensais) {
      return false;
    }
    return db_utils::fieldsMemory($rsCotasMensais, 0)->cotas > 0;
  }

  /**
   * @return int|null
   * @throws DBException
   */
  public function getClassificacaoCredor() {

    $oDaoClassificacao = new cl_classificacaocredoresempenho();
    $sSqlBuscaClassificacao = $oDaoClassificacao->sql_query_file(null, "*",null, "cc31_empempenho = {$this->iNumero}");
    $rsBuscaClassificacao   = db_query($sSqlBuscaClassificacao);

    if (!$rsBuscaClassificacao) {
      throw new DBException("Houve um erro ao buscar a Lista de Classificação de Credores do Empenho.");
    }
    if (pg_num_rows($rsBuscaClassificacao) == 0) {
      return null;
    }
    return db_utils::fieldsMemory($rsBuscaClassificacao, 0)->cc31_classificacaocredores;
  }

  /**
   * @return NotaLiquidacao[]
   */
  public function getNotasDeLiquidacao() {

    if (count($this->aNotasLiquidadas) == 0) {
      $this->aNotasLiquidadas = NotaLiquidacao::getNotaLiquidacaoPorEmpenho($this);
    }
    return $this->aNotasLiquidadas;
  }

  /**
   * @return null
   */
  public function getNumeroDaLicitacao() {
    return $this->iNumeroLicitacao;
  }

  /**
   * @param null $iNumeroLicitacao
   */
  public function setNumeroDaLicitacao($iNumeroLicitacao) {
    $this->iNumeroLicitacao = $iNumeroLicitacao;
  }

  /**
   * Retorna o codigo da lista de classificaçao de credor vinculada ao empenho.
   * @return int|null
   * @throws DBException
   */
  public function getCodigoListaClassificacaoCredor() {

    if (empty($this->iCodigoListaClassificacaoCredor) || !empty($this->iNumero)) {
      $this->iCodigoListaClassificacaoCredor = $this->getClassificacaoCredor();
    }

    return $this->iCodigoListaClassificacaoCredor;
  }

  /**
   * Retorna a lista de classificaçao de credor vinculada ao empenho.
   * @return ListaClassificacaoCredor
   * @throws BusinessException
   */
  public function getListaClassificacaoCredor() {

    $iCodigoLista = $this->getCodigoListaClassificacaoCredor();
    if (empty($this->oListaClassificacaoCredor) && !empty($iCodigoLista)) {
      $this->oListaClassificacaoCredor = ListaClassificacaoCredorRepository::getPorCodigo($iCodigoLista);
    }
    return $this->oListaClassificacaoCredor;
  }

  /**
   * @return TipoPrestacaoConta
   * @throws DBException
   */
  public function getTipoPrestacaoConta() {

    if (empty($this->oTipoPrestacaoConta)) {

      $sCampos = "e45_tipo";
      $sWhere  = " e45_numemp = {$this->iNumero} ";

      $oDaoEmpPresta = new cl_emppresta();
      $sSqlEmpPresta = $oDaoEmpPresta->sql_query_file(null, $sCampos, null, $sWhere);
      $rsEmpPresta   = db_query($sSqlEmpPresta);

      if (!$rsEmpPresta) {
        throw new DBException("Houve um erro ao buscar as informações do tipo de prestação de contas do empenho.");
      }

      if (pg_num_rows($rsEmpPresta) > 0) {
        $this->oTipoPrestacaoConta = new TipoPrestacaoConta(db_utils::fieldsMemory($rsEmpPresta, 0)->e45_tipo);
      } else {

        $oDaoBuscaPrestacao = new cl_empprestatip();
        $sSqlBuscaPrestacao = $oDaoBuscaPrestacao->sql_query_file(null, 'e44_tipo', "e44_tipo", "e44_obriga = '0'");
        $rsBuscaPrestacao   = db_query($sSqlBuscaPrestacao);
        if (!$rsBuscaPrestacao) {
          throw new DBException("Houve um erro ao buscar as informações do tipo de prestação de contas.");
        }
        $this->oTipoPrestacaoConta = new TipoPrestacaoConta(db_utils::fieldsMemory($rsBuscaPrestacao, 0)->e44_tipo);
      }
    }
    return $this->oTipoPrestacaoConta;
  }

}
