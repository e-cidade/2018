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
 * Model para controle do Empenho
 * @author  Matheus Felini <matheus.felini@dbseller.com.br>
 * @package empenho
 * @version $Revision: 1.29 $
 */
class EmpenhoFinanceiro {

  /**
   * Numero - Sequencial da Tabela
   * @var integer
   */
  protected $iNumero;

  /**
   * C�digo do Empenh
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
   * Data de Emiss�o
   * @var date
   */
  protected $dtEmissao;

  /**
   * Data de Vencimento
   * @var date
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
   * Institui��o
   * @var Instituicao
   */
  protected $oInstituicao;

  /**
   * Tipo de Compra
   * @var integer
   */
  protected $iTipoCompra;

  /**
   * Caracter�stica Peculiar
   * @var string
   */
  protected $sCaracteristicaPeculiar;

  /**
   * Autoriza��o de Empenho
   * @var AutorizacaoEmpenho
   */
  protected $oAutorizacaoEmpenho;

  /**
   * Cole��o de Itens
   * @var array EmpenhoItem
   */
  protected $aItens = array();

  /**
   * Tipo do Evento (empprestatip)
   * @var integer
   */
  protected $iTipoEvento;

  /**
   * C�digo do Fornecedor
   * @var integer
   */
  private $iCodigoFornecedor;

  /**
   * Objeto FinalidadePagamentoFundeb
   * @var FinalidadePagamentoFundeb
   */
  private $oFinalidadePagamentoFundeb;

  /**
   * C�digo da Institui��o
   * @var integer
   */
  private $iCodigoInstituicao;

  /**
   * C�digo da Dota��o Or�ament�ria
   * @var integer
   */
  private $iCodigoDotacao;

  /**
   * Carrega as propriedades de um empenho
   * @param integer  - Numero do Empenho
   * @return EmpenhoFinanceiro
   */
  public function __construct($iNumero = null) {

    $this->setNumero($iNumero);
    if (!empty($iNumero)) {

      $oDaoEmpEmpenho   = db_utils::getDao('empempenho');
      $sSqlBuscaEmpenho = $oDaoEmpEmpenho->sql_query_file($iNumero);
      $rsBuscaEmpenho   = $oDaoEmpEmpenho->sql_record($sSqlBuscaEmpenho);

      if ($oDaoEmpEmpenho->numrows == 0) {
        throw new BusinessException("Empenho {$iNumero} n�o localizado.");
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
      $this->setTipoEmpenho($oDadoEmpenho->e60_codtipo);
      $this->setValorAnulado($oDadoEmpenho->e60_vlranu);
      $this->setValorEmpenho($oDadoEmpenho->e60_vlremp);
      $this->setValorLiquidado($oDadoEmpenho->e60_vlrliq);
      $this->setValorOrcamento($oDadoEmpenho->e60_vlrorc);
      $this->setValorPago($oDadoEmpenho->e60_vlrpag);
      unset($oDadoEmpenho);
     
    }
    return true;
  }


  /**
   * Este metodo salva os dados do empenho em 'empempenho', salva os itens do empenho em 'empempitem'
   * salva tambem o vinculo com a autorizacao de empenho e o v�nculo com o elemento dos itens
   *
   * @return boolean true
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

        $sMsgErro  = "Imposs�vel criar o v�nculo da nota de liquida��o.";
        $sMsgErro .= "\n\nErro T�cnico: {$oDaoEmpenhoNotaLiquidacao->erro_msg}";
        throw new BusinessException($sMsgErro);
      }

    } else {
      $oDaoEmpEmpenho->alterar($this->getNumero());
    }

    if ($oDaoEmpEmpenho->erro_status == 0) {

      $sMsgErro = "N�o foi poss�vel incluir os dados do empenho.\n\nErro T�cnico: {$oDaoEmpEmpenho->erro_msg}";
      throw new BusinessException($sMsgErro);
    }

    /*
     * Verifico se o tipo do evento nao e empenho normal. Caso nao seja,
     * e incluido vinculo para futura presta��o de contas
     */
    if ($this->getTipoEvento() != 3) {

      $oDaoEmpPresta             = db_utils::getDao('emppresta');
      $oDaoEmpPresta->e45_numemp = $this->getNumero();
      $oDaoEmpPresta->e45_data   = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoEmpPresta->e45_tipo   = $this->getTipoEvento();
      $oDaoEmpPresta->incluir($this->getNumero());
      if ($oDaoEmpPresta->erro_status == 0) {
        throw new BusinessException("Imposs�vel incluir v�nculo para presta��o de contas.");
      }
    }

    /*
     * Variavel que soma o valor total do item, para salvar associando ao elemento em 'empelemento'
     */
    $nValorTotalEmpenho = 0;

    /*
     * CODELE do item. Como ser�o iguais apenas sobescrevo o valor enquanto estou salvando os itens
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
     * Para isso, excluimos o v�nculo existente
     */
    $oDaoEmpEmpAut             = db_utils::getDao('empempaut');
    $oDaoEmpEmpAut->e61_numemp = $this->getNumero();
    $oDaoEmpEmpAut->e61_autori = $this->getAutorizacaoEmpenho()->getAutorizacao();
    $oDaoEmpEmpAut->incluir($this->getNumero());

    if ($oDaoEmpEmpAut->erro_status == 0) {

      $sMsgErroVinculo  = "Imposs�vel criar o v�nculo entre o empenho e a autoriza��o.\n\n";
      $sMsgErroVinculo .= "Erro T�cnico: {$oDaoEmpEmpAut->erro_msg}";
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

      $sErroMsg = "N�o foi poss�vel vincular o elemento ao empenho.\n\nErro T�cnico: {$oDaoEmpElemento0->erro_msg}";
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
   * Caso o objeto ainda n�o exista em memoria, ele � criado e setado na propriedade $oAutorizacaoEmpenho
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
   * Este metodo verifica se o empenho � um empenho originado de inscricao passivo
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
   * Retorna uma cole��o de objeto do tipo EmpenhoItem
   * @return EmpenhoFinanceiroItem
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
   * @param EmpenhoItem $oEmpenhoItem
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
   * Retorna o C�digo
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
   */
  public function getAnoUso() {
    return $this->iAnoUso;
  }

  /**
   * Seta o Ano
   * @param integer $iAnoUso
   */
  public function setAnoUso($iAnoUso) {
    $this->iAnoUso = $iAnoUso;
  }

  /**
   * Retorna a dotacao
   */
  public function getDotacao() {

    if (empty($this->oDotacao)) {
      $this->setDotacao(new Dotacao($this->iCodigoDotacao, $this->iAnoUso));
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
   * @return CgmBase
   */
  public function getCgm() {
    return $this->oCgm = CgmFactory::getInstanceByCgm($this->iCodigoFornecedor);
  }

  /**
   * Retorna o fornecedor do empenho 
   *
   * @access public
   * @return CgmBase
   */
  public function getFornecedor() {
    return $this->getCgm();
  }

  /**
   * Seta o CGM
   * @param CGM $oCgm
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
   * @param date $dtEmissao
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
   * @param date $dtVencimento
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
   * Seta a finalidade de pagamento
   * S� ir� existir caso a dota��o possua recurso fundeb
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
   * Retorna se o desobramento do empenho pertence ao grupo $iGrupo passado como par�metro
   *
   * @param integer $iGrupo Codigo do grupo que se deseja verificar
   * @return Ambigous <GrupoContaOrcamento, boolean>
   */
  protected function verificaGrupoDoDesdobramento($iGrupo) {

    $iCodigoConta = $this->getDesdobramentoEmpenho();
    $oGrupo       = GrupoContaOrcamento::getGrupoConta($iCodigoConta, $this->getAnoUso());

    if ($oGrupo instanceof GrupoContaOrcamento && $oGrupo->getCodigo() == $iGrupo) {
      return true;
    }
    return false;
  }

  /**
   * Verifica se o empenho financeiro se tornou um resto a pagar na virada do exerc�cio.
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
      throw new BusinessException("O objeto FinalidadePagamentoFundeb n�o foi setado.");
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
   * @todo - Metodo implementado mas nao testado
   */   
  public static function getInstanceByCodigo($iCodigo, $iAno) {
    
    $oDaoEmpEmpenho   = db_utils::getDao('empempenho');
    $sWhere           = "e60_codemp = '$iCodigo' and e60_anousu = $iAno";
    $sSqlDadosEmpenho = $oDaoEmpEmpenho->sql_query_file(null, 'e60_numemp', null, $sWhere);
    $rsDadosEmpenho   = $oDaoEmpEmpenho->sql_record($sSqlDadosEmpenho);

    if ( $oDaoEmpEmpenho->erro_status == "0" ) {
      throw new Exception(_M( 'financeiro.empenho.EmpenhoFinanceiro.empenho_pelo_codigo_nao_encontrado' ));
    }

    return new EmpenhoFinanceiro(db_utils::fieldsMemory($rsDadosEmpenho,0)->e60_numemp);
  }
  
  /**
   * metodo que retorna a conta do plano or�amentario
   * @return object ContaOrcamento
   */ 
  public function getContaOrcamento() {
  
  	$oContaOrcamento = new ContaOrcamento($this->getDesdobramentoEmpenho(), $this->getAnoUso(), null, null);
  	return $oContaOrcamento;
  }



}