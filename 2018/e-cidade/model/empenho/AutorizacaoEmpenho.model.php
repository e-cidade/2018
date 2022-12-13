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

require_once(modification("std/DBDate.php"));

/**
 * Model responsável pelas autorizações de empenhos
 * @package empenho
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @version $Revision: 1.40 $
 */
class AutorizacaoEmpenho {

  /**
   * Código da Autorização
   *
   * @var integer
   */
  protected $iAutorizacao;

  /**
   * Código da Dotação
   *
   * @var integer
   */
  protected $iDotacao;

  /**
   * Código da Reserva
   *
   * @var integer
   */
  protected $iCodigoReserva;

  /**
   * Array de Itens de uma Autorização
   *
   * @var array
   */
  protected $aItens;

  /**
   * Desdobramento
   *
   * @var integer
   */
  protected $iDesdobramento;

  /**
   * Valor de uma autorização
   *
   * @var integer
   */
  protected $nValor;

  /**
   * Resumo da Autorização
   *
   * @var string
   */
  protected $sResumo;

  /**
   * Código do Tipo de Compra
   *
   * @var integer
   */
  protected $iTipoCompra;

  /**
   * Código do Tipo de Empenho
   *
   * @var integer
   */
  protected $iTipoEmpenho;

  /**
   * Objeto Fornecedor
   *
   * @var cgm_base
   */
  protected $oFornecedor;

  /**
   * Destino da Autorização
   *
   * @var string
   */
  protected $sDestino;

  /**
   * Ano da Autorização
   *
   * @var integer
   */
  protected $iAno;

  /**
   * Código da Característica Peculiar
   *
   * @var string
   */
  protected $sCaracteristicaPeculiar;

  /**
   * Codigo da Contrapartida da Autorizacao
   */
  protected $iContraPartida;

  /**
   * Condição de pagamento
   * @var string
   */
  protected $sCondicaoPagamento;

  /**
   * dados do prazo de entrega de icitação
   * @var string
   */
  protected $sPrazoEntrega;

  /**
   * Numero da licitação
   * @var string
   */
  protected $sNumeroLicitacao;

  /**
   * tipo da licitação
   * @var string
   */
  protected $sTipoLicitacao;

  /**
   * Telefone para contato
   * @var String
   * @access protected
   */
  protected $sTelefone;

  /**
   * Contato da autorização
   * @var String
   * @access protected
   */
  protected $sContato;

  /**
   * Outras condições para a autorização
   * @var String
   * @access protected
   */
  protected $sOutrasCondicoes;

  /**
   * @var string
   */
  protected $sProcessoAdministrativo;

  /**
   * @type Dotacao
   */
  protected $oDotacaoOrcamentaria;

  /**
   * @var TipoPrestacaoConta
   */
  protected $oTipoPrestacaoConta;

  /**
   *
   */
  function __construct($iAutorizacao = null) {

    if (!empty($iAutorizacao)) {

      $oDaoEmpAutoriza = db_utils::getDao("empautoriza");
      $this->iAutorizacao = $iAutorizacao;
      $sSqlDadosAutorizacao = $oDaoEmpAutoriza->sql_query_deptoautori($this->iAutorizacao);
      $rsDadosAutorizacao = $oDaoEmpAutoriza->sql_record($sSqlDadosAutorizacao);
      if ($oDaoEmpAutoriza->numrows > 0) {

        $oDadosAutorizacao = db_utils::fieldsMemory($rsDadosAutorizacao, 0);
        $oCgm = CgmFactory::getInstanceByCgm($oDadosAutorizacao->e54_numcgm);
        $this->iAno = $oDadosAutorizacao->e54_anousu;
        $this->iCodigoReserva = $oDadosAutorizacao->o80_codres;
        $this->sTelefone = $oDadosAutorizacao->e54_telef;
        $this->sContato = $oDadosAutorizacao->e54_contat;
        $this->sOutrasCondicoes = $oDadosAutorizacao->e54_codout;
        $this->iDotacao = $oDadosAutorizacao->e56_coddot;

        $this->setFornecedor($oCgm);
        $this->setValor($oDadosAutorizacao->e54_valor);
        $this->setResumo($oDadosAutorizacao->e54_resumo);
        $this->setDestino($oDadosAutorizacao->e54_destin);
        $this->setDotacao($oDadosAutorizacao->e56_coddot);
        $this->setTipoCompra($oDadosAutorizacao->e54_codcom);
        $this->setTipoEmpenho($oDadosAutorizacao->e54_codtipo);
        $this->setTipoLicitacao($oDadosAutorizacao->e54_tipol);
        $this->setPrazoEntrega($oDadosAutorizacao->e54_praent);
        $this->setNumeroLicitacao($oDadosAutorizacao->e54_numerl);
        $this->setCondicaoPagamento($oDadosAutorizacao->e54_conpag);
        $this->setContraPartida($oDadosAutorizacao->e56_orctiporec);
        $this->setCaracteristicaPeculiar($oDadosAutorizacao->e54_concarpeculiar);
        $this->setProcessoAdministrativo($oDadosAutorizacao->e150_numeroprocesso);
      }
    }
  }
  /**
   * Retorna um array de itens
   * @return array
   */
  public function getItens() {

    if (count($this->aItens) == 0 && !empty($this->iAutorizacao)) {

      $oDaoEmpAutItem = db_utils::getDao("empautitem");
      $sSqlItensAutorizacao = $oDaoEmpAutItem->sql_query_file($this->iAutorizacao, null, "*", 'e55_sequen');
      $rsItensAutorizacao = $oDaoEmpAutItem->sql_record($sSqlItensAutorizacao);
      if ($oDaoEmpAutItem->numrows > 0) {

        for ($iItem = 0; $iItem < $oDaoEmpAutItem->numrows; $iItem++) {

          $oDadosItem = db_utils::fieldsMemory($rsItensAutorizacao, $iItem);
          $oItem = new stdClass();
          $oItem->codigomaterial = $oDadosItem->e55_item;
          $oItem->quantidade = $oDadosItem->e55_quant;
          $oItem->valortotal = $oDadosItem->e55_vltot;
          $oItem->observacao = $oDadosItem->e55_descr;
          $oItem->codigoelemento = $oDadosItem->e55_codele;
          $oItem->valorunitario = $oDadosItem->e55_vlrun;
          $oItem->sequencial = $oDadosItem->e55_sequen;
          $this->aItens[] = $oItem;
        }
      }
    }

    return $this->aItens;
  }

  /**
   * Seta o número do telefone para a autorização
   * @param String $sTelefone
   * @access public
   */
  public function setTelefone($sTelefone) {
    $this->sTelefone = $sTelefone;
  }

  /**
   * Retorna o número do telefone da autorização
   * @access public
   */
  public function getTelefone() {
    return $this->sTelefone;
  }

  /**
   * Seta o contato para a autorização
   * @param String $sContato
   * @access public
   */
  public function setContato($sContato) {
    $this->sContato = $sContato;
  }

  /**
   * Retorna o contato da autorização
   * @access public
   */
  public function getContato() {
    return $this->sContato;
  }

  /**
   * Seta as outras condições da autorização
   * @param mixed $sOutrasCondicoes
   * @access public
   * @return void
   */
  public function setOutrasCondicoes($sOutrasCondicoes) {
    $this->sOutrasCondicoes = $sOutrasCondicoes;
  }

  /**
   * Retorna as outras condições da autorização
   * @access public
   */
  public function getOutrasCondicoes() {
    return $this->sOutrasCondicoes;
  }

  /**
   * Retorna o ano setado
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Retorna o ID da Autorização
   * @return integer
   */
  public function getAutorizacao() {
    return $this->iAutorizacao;
  }

  /**
   * Retorna o Código da Reserva
   * @return integer
   */
  public function getCodigoReserva() {

    return $this->iCodigoReserva;
  }

  public function setCodigoReserva($iCodigoReserva) {
    $this->iCodigoReserva = $iCodigoReserva;
  }

  /**
   * Retorna o Desdobramento
   * @return integer
   */
  public function getDesdobramento() {
    return $this->iDesdobramento;
  }

  /**
   * Seta o código do desdobramento
   * @param integer $iDesdobramento
   */
  public function setDesdobramento($iDesdobramento) {
    $this->iDesdobramento = $iDesdobramento;
  }

  /**
   * Retorna a Dotação setada
   * @return integer
   */
  public function getDotacao() {
    return $this->iDotacao;
  }

  /**
   * Seta a Dotação
   * @param integer $iDotacao
   */
  public function setDotacao($iDotacao) {
    $this->iDotacao = $iDotacao;
  }

  /**
   * Define a contrapartida da Dotacao
   * Define a contrapartida da Dotacao, que deve sempre ser um código de Recurso.
   * @param integer $iContraPartida Código da Contrapartida
   */
  public function setContraPartida($iContraPartida) {
    $this->iContraPartida = $iContraPartida;
  }

  /**
   * Retorna a contrapartida do recurso
   * @return integer codigo da contrapartida
   */
  public function getContraPartida() {
    return $this->iContraPartida;
  }
  /**
   * Retorna o código do tipo de compra
   * @return integer
   */
  public function getTipoCompra() {
    return $this->iTipoCompra;
  }

  /**
   * Seta o código do tipo de compra
   * @param integer $iTipoCompra
   */
  public function setTipoCompra($iTipoCompra) {
    $this->iTipoCompra = $iTipoCompra;
  }

  /**
   * Retorna o código do tipo de empenho
   * @return integer
   */
  public function getTipoEmpenho() {
    return $this->iTipoEmpenho;
  }

  /**
   * Seta o código do tipo de empenho
   * @param integer $iTipoEmpenho
   */
  public function setTipoEmpenho($iTipoEmpenho) {
    $this->iTipoEmpenho = $iTipoEmpenho;
  }

  /**
   * Retorna o valor setado
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Seta o valor
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * Retorna um objeto com os dados do fornecedor
   * @return CgmBase
   */
  public function getFornecedor() {
    return $this->oFornecedor;
  }

  /**
   * Seta o fornecedor
   * @param object $oFornecedor
   */
  public function setFornecedor(cgmbase $oFornecedor) {
    $this->oFornecedor = $oFornecedor;
  }

  /**
   * Retorna o Destino setado
   * @return string
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
   * Retorna o Resumo
   * @return string
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
   * Retorna o prazo de entregas
   * @return string
   */
  public function getPrazoEntrega() {

    return $this->sPrazoEntrega;
  }

  /**
   * Define o prazo da entrega dos itens da Autorizacao
   * @param string $sPrazoEntrega
   */
  public function setPrazoEntrega($sPrazoEntrega) {
    $this->sPrazoEntrega = $sPrazoEntrega;
  }

  /**
   * retorna o numero da licitação.
   * @return string
   */
  public function getNumeroLicitacao() {

    return $this->sNumeroLicitacao;
  }

  /**
   * Define o Número da Licitaççao ue gerou a autorização
   * @param string $sNumeroLicitacao
   */
  public function setNumeroLicitacao($sNumeroLicitacao) {
    $this->sNumeroLicitacao = $sNumeroLicitacao;
  }

  /**
   * retorna o tipo da licitação.
   * @return string
   */
  public function getTipoLicitacao() {
    return $this->sTipoLicitacao;
  }

  /**
   * Define o tipo da Licitaçao ue gerou a autorização
   * @param string $sNumeroLicitacao
   */
  public function setTipoLicitacao($sNumeroLicitacao) {
    $this->sTipoLicitacao = $sNumeroLicitacao;
  }
  /**
   * Adiciona um item a Autorizacao
   *
   * @param stdClass $oItem objeto com os dados do item
   */
  public function addItem($oItem) {
    $this->aItens[] = $oItem;
  }

  /**
   * Retorna a Característica Peculiar
   * @return string
   */
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }

  /**
   * Seta a Característica Peculiar
   * @param string $sCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar;
  }

  /**
   * Seta valor em Condição de Pagamento
   * @param string $sCondicaoPagamento
   */
  public function setCondicaoPagamento($sCondicaoPagamento) {
    $this->sCondicaoPagamento = $sCondicaoPagamento;
  }

  /**
   * Retorna a condição de pagamento.
   * @return string
   */
  public function getCondicaoPagamento() {
    return $this->sCondicaoPagamento;
  }

  public static function getServicoControladoQuantidade($iSolicitem) {

    if (empty($iSolicitem)) {
      return 'false';
    }

    $oDaoSolicitem = db_utils::getDao("solicitem");

    $sSqlServico = $oDaoSolicitem->sql_query_file(null, "pc11_servicoquantidade", null, "pc11_codigo = {$iSolicitem}");
    $rsServico = $oDaoSolicitem->sql_record($sSqlServico);
    $sControleServico = db_utils::fieldsMemory($rsServico, 0)->pc11_servicoquantidade;

    if ($sControleServico == 'f') {

      $sControleServico = 'false';
    }
    if ($sControleServico == 't') {

      $sControleServico = 'true';
    }

    return $sControleServico;

  }

  /**
   * Salva os dados de uma autorização
   */
  public function salvar() {

    /**
     * Geramos a autorizacao de empenho
     */
    AutorizacaoEmpenho::bloqueioTabela();
    $this->iAno                   = db_getsession("DB_anousu");
    $oDaoEmpAutoriza              = db_utils::getDao("empautoriza");
    $oDaoEmpAutoriza->e54_anousu  = db_getsession("DB_anousu");
    $oDaoEmpAutoriza->e54_valor   = $this->getValor();
    $oDaoEmpAutoriza->e54_concarpeculiar = $this->getCaracteristicaPeculiar();
    $oDaoEmpAutoriza->e54_codtipo = $this->getTipoEmpenho();
    $oDaoEmpAutoriza->e54_codcom  = $this->getTipoCompra();
    $oDaoEmpAutoriza->e54_destin  = $this->getDestino();
    $oDaoEmpAutoriza->e54_tipol   = $this->getTipoLicitacao();
    $oDaoEmpAutoriza->e54_numerl  = $this->getNumeroLicitacao();
    $oDaoEmpAutoriza->e54_emiss   = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoEmpAutoriza->e54_instit  = db_getsession("DB_instit");
    $oDaoEmpAutoriza->e54_depto   = db_getsession("DB_coddepto");
    $oDaoEmpAutoriza->e54_praent  = $this->getPrazoEntrega();
    $oDaoEmpAutoriza->e54_entpar  = '';
    $oDaoEmpAutoriza->e54_conpag  = $this->getCondicaoPagamento();
    $oDaoEmpAutoriza->e54_codout  = $this->sOutrasCondicoes;
    $oDaoEmpAutoriza->e54_contat  = $this->sContato;
    $oDaoEmpAutoriza->e54_telef   = $this->sTelefone;
    $oDaoEmpAutoriza->e54_numsol  = '';
    $oDaoEmpAutoriza->e54_resumo  = $this->getResumo();
    $oDaoEmpAutoriza->e54_numcgm  = $this->getFornecedor()->getCodigo();
    $oDaoEmpAutoriza->e54_login   = db_getsession("DB_id_usuario");
    $oDaoEmpAutoriza->e54_anulad  = null;

    /**
     * Verifica se a propriedade iAutorizacao está setada. Se não estiver
     * é incluido um novo registro, do contrários é feita a alteração
     */
    if ($this->iAutorizacao == "") {

      $oDaoEmpAutoriza->incluir(null);
      $this->iAutorizacao = $oDaoEmpAutoriza->e54_autori;
    } else {

      $oDaoEmpAutoriza->e54_autori = $this->iAutorizacao;
      $oDaoEmpAutoriza->alterar($this->iAutorizacao);
    }

    if ($oDaoEmpAutoriza->erro_status == 0) {

      $sMsgErro = "Não foi possivel gerar Autorização de empenho.\n";
      $sMsgErro .= $oDaoEmpAutoriza->erro_msg;
      throw new Exception($sMsgErro);
    }

    /**
     * Inclui dados da Dotacao
     */
    $oDaoAutorizaDotacao                 = db_utils::getDao("empautidot");
    $oDaoAutorizaDotacao->e56_anousu     = $this->getAno();
    $oDaoAutorizaDotacao->e56_autori     = $this->getAutorizacao();
    $oDaoAutorizaDotacao->e56_coddot     = $this->getDotacao();
    $oDaoAutorizaDotacao->e56_orctiporec = $this->getContraPartida();
    $oDaoAutorizaDotacao->incluir($this->getAutorizacao());

    if ($oDaoAutorizaDotacao->erro_status == 0) {

      $sMsgErro = "Não foi possível inserir os dados da Dotação.\n\n";
      $sMsgErro .= $oDaoAutorizaDotacao->erro_msg;
      throw new Exception($sMsgErro);
    }

    /**
     * incluir os itens
     */
    $iContaAutItem          = 1;
    $oDaoEmpAutItem         = new cl_empautitem;
    $oDaoEmpAutItemProcesso = new cl_empautitempcprocitem;

    foreach ($this->getItens() as $oItem) {

      if (empty($oItem->solicitem)) {

        $sWhere = "";
        if (!empty($oItem->liclicitem)) {
          $sWhere = "l21_codigo = {$oItem->liclicitem}";
        }

        if (!empty($oItem->empempitem)) {
          $sWhere = "e62_sequencial = {$oItem->empempitem}";
        }

        if (!empty($oItem->pcprocitem)) {
          $sWhere = "pc81_codprocitem = {$oItem->pcprocitem}";
        }

        if (!empty($sWhere)) {

          $sSqlBuscaSolicitem = "select pc11_codigo
                                   from solicitem
                                        left join pcprocitem   on  pc81_solicitem   = pc11_codigo
                                        left join empautitempcprocitem on e73_pcprocitem = pcprocitem.pc81_codprocitem
                                        left join empautitem   on e73_autori        = empautitem.e55_autori
                                                              and e73_sequen        = empautitem.e55_sequen
                                        left join empautoriza  on e55_autori        = empautoriza.e54_autori
                                        left join empempaut    on e61_autori        = empautoriza.e54_autori
                                        left join empempitem   on e61_numemp        = empempitem.e62_numemp
                                        left join liclicitem   on l21_codpcprocitem = pc81_codprocitem
                                  where {$sWhere}";

          $rsBuscaSolicitem = db_query($sSqlBuscaSolicitem);
          if (!$rsBuscaSolicitem) {
            throw new Exception("Não localizou o código do item na solicitação de compras.");
          }
          $oItem->solicitem = db_utils::fieldsMemory($rsBuscaSolicitem, 0)->pc11_codigo;
        }
      }
      if (!isset($oItem->solicitem)) {
        $oItem->solicitem = '';
      }
      $lServicoQuantidade = AutorizacaoEmpenho::getServicoControladoQuantidade($oItem->solicitem);

      $oDaoEmpAutItem->e55_autori = $this->getAutorizacao();
      $oDaoEmpAutItem->e55_item   = $oItem->codigomaterial;
      $oDaoEmpAutItem->e55_sequen = $iContaAutItem;
      $oDaoEmpAutItem->e55_quant  = $oItem->quantidade;
      $oDaoEmpAutItem->e55_vltot  = number_format((float) $oItem->valortotal, 2, '.', '');
      $oDaoEmpAutItem->e55_descr  = pg_escape_string($oItem->observacao);
      $oDaoEmpAutItem->e55_codele = $oItem->codigoelemento;
      $oDaoEmpAutItem->e55_vlrun  = $oItem->valorunitario;
      $oDaoEmpAutItem->e55_servicoquantidade = $lServicoQuantidade;

      $oItem->sequencial = $iContaAutItem;
      $oDaoEmpAutItem->incluir($this->getAutorizacao(), $iContaAutItem);

      if ($oDaoEmpAutItem->erro_status == 0) {

        $sMsgErro = "ERRO [ 1 ] - Não foi possivel incluir item {$oItem->codigomaterial} para a autorização ";
        $sMsgErro .= "{$this->getAutorizacao()}.\n";
        $sMsgErro .= "Erro Técnico:{$oDaoEmpAutItem->erro_msg}";
        throw new Exception($sMsgErro);
      }

      /**
       * vinculamos o item do processo de compras a autorizacao
       */
      if (isset($oItem->codigoprocesso) && $oItem->codigoprocesso != '') {

        $oDaoEmpAutItemProcesso->e73_autori = $this->getAutorizacao();
        $oDaoEmpAutItemProcesso->e73_sequen = $iContaAutItem;
        $oDaoEmpAutItemProcesso->e73_pcprocitem = $oItem->codigoprocesso;
        $oDaoEmpAutItemProcesso->incluir(null);
        if ($oDaoEmpAutItemProcesso->erro_status == 0) {

          $sMsgErro = "ERRO [ 2 ] - Não foi possivel incluir item {$oItem->codigomaterial} para a autorização ";
          $sMsgErro .= "{$this->getAutorizacao()}.";
          $sMsgErro .= "Erro Técnico:{$oDaoEmpAutItemProcesso->erro_msg}";
          throw new Exception($sMsgErro);
        }
      }
      $iContaAutItem++;
    }

    /**
     * reservar saldo dotacao
     * TODO fazer método na classe Dotacao para reservar saldo
     */
    if (empty($this->iCodigoReserva)) {
      $this->reservarSaldo();
    }
  }

  /**
   * Método responsável por reservar saldo de uma dotação.
   * @return true
   */
  public function reservarSaldo() {

    $oDaoOrcReserva = db_utils::getDao("orcreserva");
    $oDaoOrcReserva->o80_anousu = db_getsession("DB_anousu");
    $oDaoOrcReserva->o80_coddot = $this->getDotacao();
    $oDaoOrcReserva->o80_dtfim = db_getsession("DB_anousu") . "-12-31";
    $oDaoOrcReserva->o80_dtini = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoOrcReserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoOrcReserva->o80_valor = $this->getValor();
    $oDaoOrcReserva->o80_descr = "Reserva item Solicitacao";
    $oDaoOrcReserva->incluir(null);

    if ($oDaoOrcReserva->erro_status == 0) {

      $sMsgErro = "Não foi possivel gerar reserva para a autorização, na dotação {$this->getDotacao()}.\n";
      $sMsgErro .= str_replace("\\n", "\n", $oDaoOrcReserva->erro_msg);
      throw new Exception($sMsgErro);
    }

    $oDaoOrcReservaAut = db_utils::getDao("orcreservaaut");
    $oDaoOrcReservaAut->o83_autori = $this->getAutorizacao();
    $oDaoOrcReservaAut->o83_codres = $oDaoOrcReserva->o80_codres;
    $oDaoOrcReservaAut->incluir($oDaoOrcReserva->o80_codres);

    if ($oDaoOrcReservaAut->erro_status == 0) {

      $sMsgErro = "Não foi possivel gerar reserva para a autorização, na dotação {$this->getDotacao()}.\n";
      $sMsgErro .= $oDaoOrcReservaAut->erro_msg;
      throw new Exception($sMsgErro);
    }
    $this->iCodigoReserva = $oDaoOrcReserva->o80_codres;
    return true;
  }

  /**
   * Remove a reserva de saldo da Autorização
   * @return AutorizacaoEmpenho
   */
  public function excluirReservaSaldo() {

    if (!db_utils::inTransaction()) {
      throw new Exception('Operação cancelada. Não existe transação com o banco de dados.');
    }
    if ($this->getCodigoReserva() != null) {

      $oDaoOrcReserva = db_utils::getDao("orcreserva");
      $oDaoOrcReservaAut = db_utils::getDao("orcreservaaut");
      $oDaoOrcReservaAut->excluir($this->getCodigoReserva());

      if ($oDaoOrcReservaAut->erro_status == 0) {
        throw new Exception("Erro ao cancelar Reserva de saldo da Autorizacao {$this->iAutorizacao}");
      }

      $oDaoOrcReserva->excluir($this->getCodigoReserva());
      if ($oDaoOrcReserva->erro_status == 0) {
        throw new Exception( "Erro ao cancelar Reserva de saldo da Autorizacao {$this->iAutorizacao}");
      }
    }
    $this->iCodigoReserva = null;
    return true;
  }

  /**
   * Método que altera os parâmetros da autoriza anulando a autorização de empenho
   * Ex: $oAutorizacao = new AutorizacaoEmpenho(1234);
   *     $oAutorizacao->excluirReservaSaldo();
   *     $oAutorizacao->anularAutorizacaoEmpenho(new DBDate('19/02/2013'));
   * @param  DBDate $oDataAnulacao
   * @return boolean true
   */
  public function anularAutorizacaoEmpenho(DBDate $oDataAnulacao) {

    $oDaoOrcReservaAut = db_utils::getDao("orcreservaaut");
    $sSqlBuscaReserva  = $oDaoOrcReservaAut->sql_query_file(null, "1", null, "o83_autori = {$this->iAutorizacao}");
    $rsBuscaReserva    = $oDaoOrcReservaAut->sql_record($sSqlBuscaReserva);
    if ($oDaoOrcReservaAut->numrows != 0) {
      throw new BusinessException("Não foi feita a exclusão da reserva de saldo.");
    }

    $oDaoAutoriza             = db_utils::getDao("empautoriza");
    $oDaoAutoriza->e54_anulad = $oDataAnulacao->getDate();
    $oDaoAutoriza->e54_autori = $this->iAutorizacao;
    $oDaoAutoriza->alterar($this->iAutorizacao);
    if ($oDaoAutoriza->erro_status == 0) {

      $sErro  = "Erro ao anular autorizacao {$this->getCodigo()}\n";
      $sErro .= "{$oDaoAutoriza->erro_msg}";
    }
    return true;
  }

  /**
   * Método para bloquear a tabela empautoriza
   * Bloqueio necessário para, por exemplo, não gerar duas autorizações para mesma licitação de forma simultanea,
   * o que poderia acarretar erros na base de dados.
   * @throws BusinessException
   * @throws DBException
   */
  public static function bloqueioTabela() {

    if (!db_utils::inTransaction()) {

      $sMensagem  = 'Para utilizar o método bloqueioTabela, o bloco de código ';
      $sMensagem .= ' deve estar em transação.';
      throw new BusinessException($sMensagem);
    }

    $sSQL        = " LOCK TABLE empautoriza in SHARE ROW EXCLUSIVE MODE ";
    $rsResultado = db_query($sSQL);

    if (!$rsResultado) {
      throw new DBException('Erro ao bloquear Autorização de Empenho');
    }
  }

  /**
   * Retorna a licitação de origem
   * @return \licitacao
   */
  public function getLicitacao() {

    if (empty($this->iAutorizacao)) {
      return null;
    }

    $oDaoEmpautitem = new cl_empautitem();
    $sSqlLicitacao  = $oDaoEmpautitem->sql_query_lic($this->getAutorizacao(), null, "l21_codliclicita");
    $rsLicitacao    = $oDaoEmpautitem->sql_record( "{$sSqlLicitacao} limit 1" );

    if ($oDaoEmpautitem->numrows > 0) {
      return new licitacao( db_utils::fieldsMemory($rsLicitacao, 0)->l21_codliclicita );
    }

    return null;
  }

  /**
   * Retorna o processo de compras de origem
   * @return \ProcessoCompras
   */
  public function getProcessoCompras() {

    if (empty($this->iAutorizacao)) {
      return null;
    }

    $oDaoEmpautitem = new cl_empautitem();
    $sSqlProcesso   = $oDaoEmpautitem->sql_query_processocompras($this->getAutorizacao(), null, "pc81_codproc");
    $rsProcesso     = $oDaoEmpautitem->sql_record( "{$sSqlProcesso} limit 1" );

    if ($oDaoEmpautitem->numrows > 0) {
      return new ProcessoCompras( db_utils::fieldsMemory($rsProcesso, 0)->pc81_codproc );
    }

    return null;
  }

  /**
   * @return string
   */
  public function getProcessoAdministrativo() {
    return $this->sProcessoAdministrativo;
  }

  /**
   * @param string $sProcessoAdministrativo
   */
  public function setProcessoAdministrativo($sProcessoAdministrativo) {
    $this->sProcessoAdministrativo = $sProcessoAdministrativo;
  }

  /**
   * @return Dotacao
   */
  public function getDotacaoOrcamentaria() {

    if (!empty($this->iDotacao) && empty($this->oDotacaoOrcamentaria)) {
      $this->oDotacaoOrcamentaria = DotacaoRepository::getDotacaoPorCodigoAno($this->iDotacao, $this->iAno);
    }
    return $this->oDotacaoOrcamentaria;
  }

  /**
   * @param TipoPrestacaoConta $oPrestacaoConta
   */
  public function setTipoPrestacaoConta(TipoPrestacaoConta $oPrestacaoConta) {
    $this->oTipoPrestacaoConta = $oPrestacaoConta;
  }

  /**
   * Retorna o tipo de prestação de conta do autorização, caso possua.
   * @return TipoPrestacaoConta
   * @throws DBException
   */
  public function getTipoPrestacaoConta() {

    if (empty($this->oTipoPrestacaoConta)) {

      $sCampos = "e58_tipo";
      $sWhere  = " e58_autori = {$this->iAutorizacao} ";

      $oDaoAutPresta = new cl_empautpresta();
      $sSqlAutPresta = $oDaoAutPresta->sql_query_file(null, $sCampos, null, $sWhere);
      $rsAutPresta   = db_query($sSqlAutPresta);

      if (!$rsAutPresta) {
        throw new DBException("Houve um erro ao buscar as informações referente a autorização de empenho.");
      }

      if (pg_num_rows($rsAutPresta) > 0) {
        $this->oTipoPrestacaoConta = new TipoPrestacaoConta(db_utils::fieldsMemory($rsAutPresta, 0)->e58_tipo);
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

  /**
   * Retorna o código sequencial do contrato vinculado, se houver
   *
   * @return integer|null
   */
  public function getContrato() {

    $oDAOContratoAutorizacao = new cl_acordoitemexecutadoempautitem;

    $sCampos = "ac16_sequencial";
    $sOrder  = "ac16_sequencial limit 1";
    $sWhere  = "ac19_autori = {$this->getAutorizacao()}";
    $sSql    = $oDAOContratoAutorizacao->sql_query_contrato(null, $sCampos, $sOrder, $sWhere);

    $rsContrato = db_query($sSql);
    if ($rsContrato && pg_num_rows($rsContrato) == 1) {
      return db_utils::fieldsMemory($rsContrato, 0)->ac16_sequencial;
    }

    return null;
  }

}