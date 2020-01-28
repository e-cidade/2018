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

require_once("std/DBDate.php");

/**
 * Model respons�vel pelas autoriza��es de empenhos
 * @package empenho
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @version $Revision: 1.30 $
 */
class AutorizacaoEmpenho {

  /**
   * C�digo da Autoriza��o
   *
   * @var integer
   */
  protected $iAutorizacao;

  /**
   * C�digo da Dota��o
   *
   * @var integer
   */
  protected $iDotacao;

  /**
   * C�digo da Reserva
   *
   * @var integer
   */
  protected $iCodigoReserva;

  /**
   * Array de Itens de uma Autoriza��o
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
   * Valor de uma autoriza��o
   *
   * @var integer
   */
  protected $nValor;

  /**
   * Resumo da Autoriza��o
   *
   * @var string
   */
  protected $sResumo;

  /**
   * C�digo do Tipo de Compra
   *
   * @var integer
   */
  protected $iTipoCompra;

  /**
   * C�digo do Tipo de Empenho
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
   * Destino da Autoriza��o
   *
   * @var string
   */
  protected $sDestino;

  /**
   * Ano da Autoriza��o
   *
   * @var integer
   */
  protected $iAno;

  /**
   * C�digo da Caracter�stica Peculiar
   *
   * @var string
   */
  protected $sCaracteristicaPeculiar;

  /**
   * Codigo da Contrapartida da Autorizacao
   */
  protected $iContraPartida;

  /**
   * Condi��o de pagamento
   * @var string
   */
  protected $sCondicaoPagamento;

  /**
   * dados do prazo de entrega de icita��o
   * @var string
   */
  protected $sPrazoEntrega;

  /**
   * Numero da licita��o
   * @var string
   */
  protected $sNumeroLicitacao;

  /**
   * tipo da licita��o
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
   * Contato da autoriza��o
   * @var String
   * @access protected
   */
  protected $sContato;

  /**
   * Outras condi��es para a autoriza��o
   * @var String
   * @access protected
   */
  protected $sOutrasCondicoes;

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
   * Seta o n�mero do telefone para a autoriza��o
   * @param String $sTelefone 
   * @access public
   * @return void
   */
  public function setTelefone($sTelefone) {
    $this->sTelefone = $sTelefone;
  }

  /**
   * Retorna o n�mero do telefone da autoriza��o 
   * @access public
   * @return void
   */
  public function getTelefone() {
    return $this->sTelefone;
  }

  /**
   * Seta o contato para a autoriza��o
   * @param String $sContato 
   * @access public
   * @return void
   */
  public function setContato($sContato) {
    $this->sContato = $sContato;
  }

  /**
   * Retorna o contato da autoriza��o
   * @access public
   * @return void
   */
  public function getContato() {
    return $this->sContato;
  }

  /**
   * Seta as outras condi��es da autoriza��o
   * @param mixed $sOutrasCondicoes 
   * @access public
   * @return void
   */
  public function setOutrasCondicoes($sOutrasCondicoes) {
    $this->sOutrasCondicoes = $sOutrasCondicoes;
  }

  /**
   * Retorna as outras condi��es da autoriza��o
   * @access public
   * @return void
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
   * Retorna o ID da Autoriza��o
   * @return integer
   */
  public function getAutorizacao() {
    return $this->iAutorizacao;
  }

  /**
   * Retorna o C�digo da Reserva
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
   * Seta o c�digo do desdobramento
   * @param integer $iDesdobramento
   */
  public function setDesdobramento($iDesdobramento) {
    $this->iDesdobramento = $iDesdobramento;
  }

  /**
   * Retorna a Dota��o setada
   * @return integer
   */
  public function getDotacao() {
    return $this->iDotacao;
  }

  /**
   * Seta a Dota��o
   * @param integer $iDotacao
   */
  public function setDotacao($iDotacao) {
    $this->iDotacao = $iDotacao;
  }

  /**
   * Define a contrapartida da Dotacao
   * Define a contrapartida da Dotacao, que deve sempre ser um c�digo de Recurso.
   * @param integer $iContraPartida C�digo da Contrapartida
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
   * Retorna o c�digo do tipo de compra
   * @return integer
   */
  public function getTipoCompra() {
    return $this->iTipoCompra;
  }

  /**
   * Seta o c�digo do tipo de compra
   * @param integer $iTipoCompra
   */
  public function setTipoCompra($iTipoCompra) {
    $this->iTipoCompra = $iTipoCompra;
  }

  /**
   * Retorna o c�digo do tipo de empenho
   * @return integer
   */
  public function getTipoEmpenho() {
    return $this->iTipoEmpenho;
  }

  /**
   * Seta o c�digo do tipo de empenho
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
   * retorna o numero da licita��o.
   * @return string
   */
  public function getNumeroLicitacao() {

    return $this->sNumeroLicitacao;
  }

  /**
   * Define o N�mero da Licita��ao ue gerou a autoriza��o
   * @param string $sNumeroLicitacao
   */
  public function setNumeroLicitacao($sNumeroLicitacao) {
    $this->sNumeroLicitacao = $sNumeroLicitacao;
  }

  /**
   * retorna o tipo da licita��o.
   * @return string
   */
  public function getTipoLicitacao() {
    return $this->sTipoLicitacao;
  }

  /**
   * Define o tipo da Licita�ao ue gerou a autoriza��o
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
   * Retorna a Caracter�stica Peculiar
   * @return string
   */
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }

  /**
   * Seta a Caracter�stica Peculiar
   * @param string $sCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar;
  }

  /**
   * Seta valor em Condi��o de Pagamento
   * @param string $sCondicaoPagamento
   */
  public function setCondicaoPagamento($sCondicaoPagamento) {
    $this->sCondicaoPagamento = $sCondicaoPagamento;
  }

  /**
   * Retorna a condi��o de pagamento.
   * @return string
   */
  public function getCondicaoPagamento() {
    return $this->sCondicaoPagamento;
  }

  public static function getServicoControladoQuantidade($iSolicitem) {

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
   * Salva os dados de uma autoriza��o
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
     * Verifica se a propriedade iAutorizacao est� setada. Se n�o estiver
     * � incluido um novo registro, do contr�rios � feita a altera��o
     */
    if ($this->iAutorizacao == "") {

      $oDaoEmpAutoriza->incluir(null);
      $this->iAutorizacao = $oDaoEmpAutoriza->e54_autori;
    } else {

      $oDaoEmpAutoriza->e54_autori = $this->iAutorizacao;
      $oDaoEmpAutoriza->alterar($this->iAutorizacao);
    }

    if ($oDaoEmpAutoriza->erro_status == 0) {

      $sMsgErro = "N�o foi possivel gerar Autoriza��o de empenho.\n";
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

      $sMsgErro = "N�o foi poss�vel inserir os dados da Dota��o.\n\n";
      $sMsgErro .= $oDaoAutorizaDotacao->erro_msg;
      throw new Exception($sMsgErro);
    }

    /**
     * incluir os itens
     */
    $iContaAutItem          = 1;
    $oDaoEmpAutItem         = db_utils::getDao("empautitem");
    $oDaoEmpAutItemProcesso = db_utils::getDao("empautitempcprocitem");

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
          throw new Exception("N�o localizou o c�digo do item na solicita��o de compras.");
        }
        $oItem->solicitem = db_utils::fieldsMemory($rsBuscaSolicitem, 0)->pc11_codigo;
      }

      $lServicoQuantidade = AutorizacaoEmpenho::getServicoControladoQuantidade($oItem->solicitem);

      $oDaoEmpAutItem->e55_autori = $this->getAutorizacao();
      $oDaoEmpAutItem->e55_item   = $oItem->codigomaterial;
      $oDaoEmpAutItem->e55_sequen = $iContaAutItem;
      $oDaoEmpAutItem->e55_quant  = $oItem->quantidade;
      $oDaoEmpAutItem->e55_vltot  = number_format((float) $oItem->valortotal, 2, '.', '');
      $oDaoEmpAutItem->e55_descr  = $oItem->observacao;
      $oDaoEmpAutItem->e55_codele = $oItem->codigoelemento;
      $oDaoEmpAutItem->e55_vlrun  = $oItem->valorunitario;
      $oDaoEmpAutItem->e55_servicoquantidade = $lServicoQuantidade;

      $oItem->sequencial = $iContaAutItem;
      $oDaoEmpAutItem->incluir($this->getAutorizacao(), $iContaAutItem);

      if ($oDaoEmpAutItem->erro_status == 0) {

        $sMsgErro = "ERRO [ 1 ] - N�o foi possivel incluir item {$oItem->codigomaterial} para a autoriza��o ";
        $sMsgErro .= "{$this->getAutorizacao()}.\n";
        $sMsgErro .= "Erro T�cnico:{$oDaoEmpAutItem->erro_msg}";
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

          $sMsgErro = "ERRO [ 2 ] - N�o foi possivel incluir item {$oItem->codigomaterial} para a autoriza��o ";
          $sMsgErro .= "{$this->getAutorizacao()}.";
          $sMsgErro .= "Erro T�cnico:{$oDaoEmpAutItemProcesso->erro_msg}";
          throw new Exception($sMsgErro);
        }
      }
      $iContaAutItem++;
    }

    /**
     * reservar saldo dotacao
     * TODO fazer m�todo na classe Dotacao para reservar saldo 
     */
    if (empty($this->iCodigoReserva)) {
      $this->reservarSaldo();
    }
  }

  /**
   * M�todo respons�vel por reservar saldo de uma dota��o.
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

      $sMsgErro = "N�o foi possivel gerar reserva para a autoriza��o, na dota��o {$this->getDotacao()}.\n";
      $sMsgErro .= str_replace("\\n", "\n", $oDaoOrcReserva->erro_msg);
      throw new Exception($sMsgErro);
    }
    
    $oDaoOrcReservaAut = db_utils::getDao("orcreservaaut");
    $oDaoOrcReservaAut->o83_autori = $this->getAutorizacao();
    $oDaoOrcReservaAut->o83_codres = $oDaoOrcReserva->o80_codres;
    $oDaoOrcReservaAut->incluir($oDaoOrcReserva->o80_codres);
    
    if ($oDaoOrcReservaAut->erro_status == 0) {

      $sMsgErro = "N�o foi possivel gerar reserva para a autoriza��o, na dota��o {$this->getDotacao()}.\n";
      $sMsgErro .= $oDaoOrcReservaAut->erro_msg;
      throw new Exception($sMsgErro);
    }
    $this->iCodigoReserva = $oDaoOrcReserva->o80_codres;
    return true;
  }
  
  /**
   * Remove a reserva de saldo da Autoriza��o
   * @return AutorizacaoEmpenho
   */
  public function excluirReservaSaldo() {

    if (!db_utils::inTransaction()) {
      throw new Exception('Opera��o cancelada. N�o existe transa��o com o banco de dados.');
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
   * M�todo que altera os par�metros da autoriza anulando a autoriza��o de empenho
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
      throw new BusinessException("N�o foi feita a exclus�o da reserva de saldo.");
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
   * M�todo para bloquear a tabela empautoriza
   * Bloqueio necess�rio para, por exemplo, n�o gerar duas autoriza��es para mesma licita��o de forma simultanea,
   * o que poderia acarretar erros na base de dados.
   * @throws BusinessException
   * @throws DBException
   */
  public static function bloqueioTabela() {
    
    if (!db_utils::inTransaction()) {
    
      $sMensagem  = 'Para utilizar o m�todo bloqueioTabela, o bloco de c�digo ';
      $sMensagem .= ' deve estar em transa��o.';
      throw new BusinessException($sMensagem);
    }
    
    $sSQL        = " LOCK TABLE empautoriza in SHARE ROW EXCLUSIVE MODE ";
    $rsResultado = db_query($sSQL);
    
    if (!$rsResultado) {
      throw new DBException('Erro ao bloquear Autoriza��o de Empenho');
    }
  }
  
}
?>