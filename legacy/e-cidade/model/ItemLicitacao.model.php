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


class ItemLicitacao {

  /**
   *
   * @var integer
   */
  protected $iCodigo;

  /**
   *
   * @var integer
   */
  protected $iProcessoCompras;

  /**
   *
   * @var integer
   */
  protected $iOrdem;

  /**
   *
   * @var itemSolicitacao
   */
  protected $oItemSolicitacao;

  /**
   *
   * @var integer
   */
  protected $iItemProcessoCompras;

  /**
   *
   * @var LoteLicitacao
   */
  private $oLoteLicitacao;

  const SITUACAO_ANULADA = '1';
  const SITUACAO_REGULAR = '0';

  /**
   * @var integer
   */
  private $iCodigoLicitacao;

  /**
   * @var licitacao
   */
  private $oLicitacao;

  public function __construct($iCodigo=null) {

    if (!empty($iCodigo)) {

      $oDaoLiclicitem = new cl_liclicitem;
      $sSqlDadosItem  = $oDaoLiclicitem->sql_query($iCodigo);
      $rsDadosItem    = $oDaoLiclicitem->sql_record($sSqlDadosItem);
      if ($oDaoLiclicitem->numrows  == 1) {

        $oDadosItem = db_utils::fieldsMemory($rsDadosItem, 0);
        $this->setCodigo($oDadosItem->l21_codigo);
        $this->oItemSolicitacao     = new itemSolicitacao($oDadosItem->pc81_solicitem);
        $this->setItemProcessoCompras($oDadosItem->pc81_codprocitem);
        $this->iOrdem               = $oDadosItem->l21_ordem;
        $this->iProcessoCompras     = $oDadosItem->pc81_codproc;
        $this->iItemProcessoCompras = $oDadosItem->pc81_codprocitem;
        $this->setCodigoLicitacao($oDadosItem->l20_codigo);
        unset($oDadosItem);
        unset($oDaoLiclicitem);
      }
    }
  }

  /**
   * Exclui os itens de uma licitação.
   *
   * @param  integer $iItemLicitacao
   * @return bool
   * @throws Exception
   */
  public function remover($iItemLicitacao) {

    if (empty($iItemLicitacao)) {
      throw new Exception("Código do item da licitação não informado.");
    }
    $oDaoLiclicitem     = db_utils::getDao("liclicitem");
    $oDaoLiclicitemLote = db_utils::getDao("liclicitemlote");
    $oDaoLiclicitemAnu  = db_utils::getDao("liclicitemanu");

    $oDaoLiclicitemLote->excluir(null, "l04_liclicitem = {$iItemLicitacao}");
    $oDaoLiclicitemAnu->excluir(null, "l07_liclicitem = {$iItemLicitacao}");
    $oDaoLiclicitem->excluir($iItemLicitacao);

    if ($oDaoLiclicitem->erro_status == "0" || $oDaoLiclicitemLote->erro_status == "0") {
      throw new Exception("Não foi possível excluir os itens da licitação.");
    }
    return true;
  }

  /**
   * @param integer $iCodigoLicitacao
   */
  public function setCodigoLicitacao($iCodigoLicitacao) {
    $this->iCodigoLicitacao = $iCodigoLicitacao;
  }

  /**
   * @return integer
   */
  public function getCodigoLicitacao() {
    return $this->iCodigoLicitacao;
  }

  /**
   * @return licitacao
   */
  public function getLicitacao() {

    if (empty($this->oLicitacao) && $this->getCodigoLicitacao()) {
      $this->oLicitacao = LicitacaoRepository::getByCodigo($this->getCodigoLicitacao());
    }

    return $this->oLicitacao;
  }

  /**
   * Setter Codigo Item
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Getter Codigo Item
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  public function setItemProcessoCompras($iItemProcessoCompras) {

    if ($this->oItemSolicitacao == null) {

      $oDaoPcProcitem    = new cl_pcprocitem;
      $sSqlDadosProcesso = $oDaoPcProcitem->sql_query_file($iItemProcessoCompras);
      $rsDadosProcesso   = $oDaoPcProcitem->sql_record($sSqlDadosProcesso);
      if ($oDaoPcProcitem->numrows == 1) {

        $oDadosProcesso             = db_utils::fieldsMemory($rsDadosProcesso, 0);
        $this->iProcessoCompras     = $oDadosProcesso->pc81_codproc;
        $this->iItemProcessoCompras = $oDadosProcesso->pc81_codprocitem;
        unset($oDadosProcesso);
        unset($oDaoPcProcitem);
      }
    }
  }

  /**
   * @param $iProcessoCompras
   */
  public function setProcessoCompra($iProcessoCompras) {
    $this->iProcessoCompras = $iProcessoCompras;
  }

  /**
   * @return mixed
   */
  public function getProcessoCompra() {
    return $this->iProcessoCompras;
  }

  /**
   * @return itemSolicitacao
   */
  public function getItemSolicitacao() {
    return $this->oItemSolicitacao;
  }

  /**
   * @return mixed
   */
  public function getItemProcessoCompras() {
    return $this->iItemProcessoCompras;
  }

  /**
   * Retorna  o lote do Item da licitacao
   * @todo implementar, e retornar uma instancia de lote. Hoje é apenas utilizado para nao quebrar a interface no julgamento do orcamento
   * @return
   */
  public function getLote() {
    return null;
  }

  /**
   * Retorna o lote da licitação
   *
   * @todo verificar se passar essa implementação para o método getLote não vai impactar no julgamento
   * @return LoteLicitacao
   */
  public function getLoteLicitacao() {

    if ($this->oLoteLicitacao === null && !empty($this->iCodigo)) {
      $this->oLoteLicitacao = new LoteLicitacao($this->iCodigo);
    }

    return $this->oLoteLicitacao;
  }

  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * Define o número da ordem da licitação
   * @param integer
   */
  public function setOrdem($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * Retorna o próximo número de ordem por licitação
   * @return integer
   */
  public function getProximoNumeroOrdem() {

    if (!$this->iCodigoLicitacao) {
      throw new Exception("Não foi possível pegar o próximo número de ordem. O código da licitação não foi informado.");
    }

    $oDaoItemLicitacao = new cl_liclicitem;
    $sCampos           = 'coalesce(max(l21_ordem), 0) + 1 as proximo';
    $sWhere            = "l21_codliclicita = {$this->iCodigoLicitacao}";
    $sSqlProximaOrdem  = $oDaoItemLicitacao->sql_query_file(null, $sCampos, null, $sWhere);
    $rsProximaOrdem    = db_query($sSqlProximaOrdem);

    if (!$rsProximaOrdem || pg_num_rows($rsProximaOrdem) === 0) {
      throw new DBException('Não foi possível buscar o próximo número da ordem.');
    }

    return db_utils::fieldsMemory($rsProximaOrdem, 0)->proximo;
  }

  /**
   * Salva o vínculo entre o item do processo de compra com a licitação
   * @return integer Código do item da licitação
   */
  public function salvar() {

    if (!$this->iCodigoLicitacao) {
      throw new ParameterException("Codigo da licitação não informado.");
    }

    if (!$this->iItemProcessoCompras) {
      throw new ParameterException("Codigo do Item no Processo de Compras não informado.");
    }

    $oDaoItemLicitacao = new cl_liclicitem;
    $oDaoItemLicitacao->l21_codigo        = $this->iCodigo;
    $oDaoItemLicitacao->l21_codliclicita  = $this->iCodigoLicitacao;
    $oDaoItemLicitacao->l21_codpcprocitem = $this->iItemProcessoCompras;
    $oDaoItemLicitacao->l21_situacao      = self::SITUACAO_REGULAR;
    $oDaoItemLicitacao->l21_ordem         = empty($this->iOrdem) ? $this->getProximoNumeroOrdem() : $this->iOrdem;

    if (empty($this->iCodigo)) {

      $oDaoItemLicitacao->incluir(null);
      $this->iCodigo = $oDaoItemLicitacao->l21_codigo;
    } else {
      $oDaoItemLicitacao->alterar($this->iCodigo);
    }

    if ($oDaoItemLicitacao->erro_status == '0') {
      throw new DBException("Não foi possível salvar o Item da Licitação.");
    }

    return $this->iCodigo;
  }


  /**
   * Calcula o valor e a quantidade do item executado em um ou mais contrato(s)
   * - Usado para controle de saldo do item ao gerar autorização de empenho de um Acordo. Somente quando licitação
   *   é um chamamento público / credenciamento
   * @return stdClass
   */
  public function saldoExecutadoEmContratos() {

    $sSql  = "  select sum (ac29_quantidade) as qtd_executado, sum(ac29_valor ) as vlr_executado ";
    $sSql .= "   from liclicitem ";
    $sSql .= "   join acordoliclicitem on acordoliclicitem.ac24_liclicitem = liclicitem.l21_codigo ";
    $sSql .= "   join acordoitem       on acordoitem.ac20_sequencial = acordoliclicitem.ac24_acordoitem ";
    $sSql .= "   join acordoitemexecutado on acordoitemexecutado.ac29_acordoitem = acordoitem.ac20_sequencial ";
    $sSql .= "  where l21_codigo = {$this->iCodigo} ";
    $rs    = db_query($sSql);

    if ( !$rs ) {
      throw new Exception("Erro ao verificar o saldo.");
    }
    $oTotalExecutado = db_utils::fieldsMemory($rs, 0);
    $oTotalExecutado->qtd_executado = empty($oTotalExecutado->qtd_executado) ? 0 : $oTotalExecutado->qtd_executado ;
    $oTotalExecutado->vlr_executado = empty($oTotalExecutado->vlr_executado) ? 0 : $oTotalExecutado->vlr_executado  ;

    return $oTotalExecutado;
  }
}