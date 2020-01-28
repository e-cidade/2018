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
 * @deprecated
 * Model em refatoração, não utilizar nada deste model, preferencialmente criar os métodos no model model/empenho/AutorizacaoEmpenho.model.php
 */
class AutorizacaoEmpenho {

  protected $iCodigo;

  protected $oCredor;

  protected $oTipoCompra;

  protected $sDestino;

  protected $sCodigoLicitacao;

  protected $aItens = array();

  protected $dtEmissao;

  protected $iInstit;

  protected $iAnoUsu;

  protected $iDepartamento;

  protected $sResumo;

  protected $iTipoAutorizacao;

  protected $iTipoLicitacao;

  protected $iCaracteristica;

  protected $iElemento;

  protected $oDotacao;

  protected $nValorAutoriza;

  protected $iEmpenho;

  protected $dtAnulacao;

  /**
   * @var string
   */
  protected $sProcessoAdministrativo;

  /**
   *
   */
  function __construct($iCodigoAutorizacao = null) {

    if (!empty($iCodigoAutorizacao)) {

      $oDaoEmpAutoriza      = db_utils::getDao("empautoriza");
      $sSqlDadosAutorizacao = $oDaoEmpAutoriza->sql_query_empenho($iCodigoAutorizacao);
      $rsDadosAutorizacao   = $oDaoEmpAutoriza->sql_record($sSqlDadosAutorizacao);
      if ($oDaoEmpAutoriza->numrows == 1) {

        $oDadosAutorizacao = db_utils::fieldsMemory($rsDadosAutorizacao, 0);

        $this->iCodigo     = $oDadosAutorizacao->e54_autori;
        $this->setCaracteristica($oDadosAutorizacao->e54_concarpeculiar);
        $this->setCodigoLicitacao($oDadosAutorizacao->e54_numerl);
        $this->setDestino($oDadosAutorizacao->e54_destin);
        $this->setResumo($oDadosAutorizacao->e54_resumo);
        if ($oDadosAutorizacao->e56_coddot && $oDadosAutorizacao->e56_anousu) {
          $this->setDotacao(new Dotacao($oDadosAutorizacao->e56_coddot, $oDadosAutorizacao->e56_anousu));
        }
        $this->setEmissao(db_formatar($oDadosAutorizacao->e54_emiss, "d"));
        $this->setTipoAutorizacao($oDadosAutorizacao->e54_codtipo);
        $this->setTipoCompra($oDadosAutorizacao->e54_codcom);
        $this->setTipoLicitacao($oDadosAutorizacao->e54_tipol);
        $this->setValorAutoriza($oDadosAutorizacao->e54_valor);
        $this->setProcessoAdministrativo($oDadosAutorizacao->e150_numeroprocesso);
        $this->iDepartamento = $oDadosAutorizacao->e54_depto;
        $this->iAnoUsu       = $oDadosAutorizacao->e54_anousu;
        $this->iInstit       = $oDadosAutorizacao->e54_instit;
        $oCredor = CgmFactory::getInstanceByCgm($oDadosAutorizacao->e54_numcgm);
        $this->setCredor($oCredor);
        $this->iEmpenho = $oDadosAutorizacao->e60_numemp;
        unset($oDadosAutorizacao);
      }
    }
  }

  /**
   * @param string $sProcessoAdministrativo
   */
  public function setProcessoAdministrativo($sProcessoAdministrativo) {
    $this->sProcessoAdministrativo = $sProcessoAdministrativo;
  }

  /**
   * @return string
   */
  public function getProcessoAdministrativo() {
    return $this->sProcessoAdministrativo;
  }

  /**
   * @return itens da autorizacao
   */
  public function getItens() {

    return $this->aItens;
  }

  /**
   * retorna a data de emissão da autorizacao
   * @return data de emissao da autorizacao
   */
  public function getEmissao() {

    return $this->dtEmissao;
  }

  /**
   * @param string $dtEmissao data de emissao (formado dd/mm/YYYY)
   */
  public function setEmissao($dtEmissao) {

    $this->dtEmissao = $dtEmissao;
  }

  /**
   * retorna o exercicio da autorizacao de empenho
   * @return integer
   */
  public function getAnoUsu() {

    return $this->iAnoUsu;
  }

  /**
   * define a caracteristica peculiar da autorizacao
   * @return integer
   */
  public function getCaracteristica() {

    return $this->iCaracteristica;
  }

  /**
   * define a caracteristica peculiar da autorizacao
   * @param integer $iCaracteristica
   */
  public function setCaracteristica($iCaracteristica) {
    $this->iCaracteristica = $iCaracteristica;
  }

  /**
   * retorna o codigo da autorizacao
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * retorna o departamento da autorização
   * @return integer
   *
   */
  public function getDepartamento() {

    return $this->iDepartamento;
  }

  /**
   * retorna o codigo da instituição que a autorizacao foi criada
   * @return integer
   */
  public function getInstit() {

    return $this->iInstit;
  }

  /**
   * @return integer tipo da autorizacao
   */
  public function getTipoAutorizacao() {

    return $this->iTipoAutorizacao;
  }

  /**
   * @param integer $iTipoAutorizacao
   */
  public function setTipoAutorizacao($iTipoAutorizacao) {

    $this->iTipoAutorizacao = $iTipoAutorizacao;
  }

  /**
   * @return credor da autorizacao
   */
  public function getCredor() {

    return $this->oCredor;
  }

  /**
   * @param cgmbase $oCredor credor da autorizacao
   */
  public function setCredor(CgmBase $oCredor) {

    $this->oCredor = $oCredor;
  }

  /**
   * @return object
   */
  public function getTipoCompra() {
    return $this->oTipoCompra;
  }

  /**
   * @param object $oTipoCompra objeto com o tipo da compra
   */
  public function setTipoCompra($oTipoCompra) {

    $this->oTipoCompra = $oTipoCompra;
  }

  /**
   * @return string
   */
  public function getCodigoLicitacao() {

    return $this->sCodigoLicitacao;
  }

  /**
   * @param string $sCodigoLicitacao
   */
  public function setCodigoLicitacao($sCodigoLicitacao) {

    $this->sCodigoLicitacao = $sCodigoLicitacao;
  }

  /**
   * @return string
   */
  public function getDestino() {

    return $this->sDestino;
  }

  /**
   * @param string $sDestino
   */
  public function setDestino($sDestino) {

    $this->sDestino = $sDestino;
  }

  /**
   * @return string
   */
  public function getResumo() {

    return $this->sResumo;
  }

  /**
   * define o resumo da autorizacao
   * @param string $sResumo
   */
  public function setResumo($sResumo) {

    $this->sResumo = $sResumo;
  }
  /**
   * retorna o elemento da autorizacao
   * @return integer
   */
  public function getElemento() {

    return $this->iElemento;
  }

  /**
   * @param define o elemento da autorizacao $iElemento
   */
  public function setElemento($iElemento) {

    $this->iElemento = $iElemento;
  }
  /**
   * retorna a dotacao da autorizacao
   * @return Dotacao
   */
  public function getDotacao() {

    return $this->oDotacao;
  }

  /**
   * Seta a dotacao da Autorizacao
   * @param Dotacao $iDotacao
   */
  public function setDotacao(Dotacao $oDotacao) {

    $this->oDotacao = $oDotacao;
  }
  /**
   * @return unknown
   */
  public function getTipoLicitacao() {

    return $this->iTipoLicitacao;
  }

  /**
   * @param unknown_type $iTipoLicitacao
   */
  public function setTipoLicitacao($iTipoLicitacao) {

    $this->iTipoLicitacao = $iTipoLicitacao;
  }
  /**
   * @return unknown
   */
  public function getValorAutoriza() {

    return $this->nValorAutoriza;
  }

  /**
   * @param float $nValorAutoriza
   */
  public function setValorAutoriza($nValorAutoriza) {

    $this->nValorAutoriza = $nValorAutoriza;
  }

  public function save() {

    $oDaoAutori = db_utils::getDao("empautoriza");

    $oDaoAutori->e54_destin         = $this->getDestino();
    $oDaoAutori->e54_anulad         = '';
    $oDaoAutori->e54_codcom         = $this->getTipoCompra();
    $oDaoAutori->e54_codtipo        = $this->getTipoAutorizacao();
    $oDaoAutori->e54_concarpeculiar = "{$this->getCaracteristica()}";
    if ($oDaoAutori->e54_concarpeculiar == 0) {

      $oDaoAutori->e54_concarpeculiar = '000';
    }


    $oDaoAutori->e54_conpag         = "";
    $oDaoAutori->e54_resumo         = $this->getResumo();
    $oDaoAutori->e54_numcgm         = $this->getCredor()->getCodigo();
    $oDaoAutori->e54_tipol          = $this->getTipoLicitacao();
    $oDaoAutori->e54_valor          = "0";

    //echo ("<pre>".print_r($oDaoAutori, 1)."</pre>"); die();

    if (empty($this->iCodigo)) {

      $this->iAnoUsu = db_getsession("DB_anousu");
      $oDaoAutori->e54_instit = db_getsession("DB_instit");
      $oDaoAutori->e54_anousu = db_getsession("DB_anousu");
      $oDaoAutori->e54_depto  = db_getsession("DB_coddepto");
      $oDaoAutori->e54_login  = db_getsession("DB_id_usuario");
      $oDaoAutori->e54_emiss  = implode("-", array_reverse(explode("/", $this->dtEmissao)));
      $oDaoAutori->incluir(null);
      $this->iCodigo = $oDaoAutori->e54_autori;
    } else {

      $oDaoAutori->e54_autori = $this->iCodigo;
      $oDaoAutori->alterar($this->iCodigo);
    }
    if ($oDaoAutori->erro_status == 0) {

      $sErro = "[ 1 ] - Erro ao salvar dados da autorizacao!\n";
      $sErro .= $oDaoAutori->erro_msg;
      throw new Exception($sErro);
    }

    /**
     * incluimos a dotacao
     */

    $oDaoEmpAutorizaDotacao = db_utils::getDao("empautidot");
    //$oDaoEmpAutorizaDotacao->excluir($this->iCodigo);
    $oDaoEmpAutorizaDotacao->e56_anousu = db_getsession("DB_anousu");
    $oDaoEmpAutorizaDotacao->e56_autori = $this->iCodigo;
    $oDaoEmpAutorizaDotacao->e56_coddot = $this->getDotacao()->getCodigo();
    $oDaoEmpAutorizaDotacao->e56_orctiporec = '';
    $oDaoEmpAutorizaDotacao->incluir($this->iCodigo);
    if ($oDaoEmpAutorizaDotacao->erro_status == 0) {

      $sErro = "[ 2 ] - Erro ao salvar dados da autorizacao!\n";
      $sErro .= $oDaoEmpAutorizaDotacao->erro_msg;
      throw new Exception($sErro);
    }
  }

  public function adicionarItens(ItemAutorizacao $oItemAutorizacao) {

    if ($oItemAutorizacao->isSaved()) {

    }
  }

  public function reservarSaldo() {

    if ($this->getDotacao()->getSaldoAtual() < $this->getValorAutoriza()) {
      throw new Exception("Dotação {$this->getDotacao()->getCodigo()} sem saldo!");
    }
    $oDaorcReserva = db_utils::getDao("orcreserva");
    $oDaorcReserva->o80_anousu = $this->getAnoUsu();
    $oDaorcReserva->o80_coddot = $this->getDotacao()->getCodigo();
    $oDaorcReserva->o80_dtini  = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaorcReserva->o80_dtfim  = "{$this->getAnoUsu()}-12-31";
    $oDaorcReserva->o80_descr  = "Reserva autorizacao {$this->iCodigo} ";
    $oDaorcReserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaorcReserva->o80_valor  = $this->getValorAutoriza();
    $oDaorcReserva->incluir(null);
    if ($oDaorcReserva->erro_status == 0) {

      $sErro = "Erro ao reservar o saldo para autorização (dotação {$this->getDotacao()->getCodigo()})!\n";
      $sErro .= $oDaorcReserva->erro_msg;
      throw new Exception($sErro);
    }

    /**
     * vincula a reserva a autorizacao
     */
    $oDaoOrcReservaAut = db_utils::getDao("orcreservaaut");
    $oDaoOrcReservaAut->o83_autori = $this->getCodigo();
    $oDaoOrcReservaAut->o83_codres = $oDaorcReserva->o80_codres;
    $oDaoOrcReservaAut->incluir($oDaorcReserva->o80_codres);
    if ($oDaoOrcReservaAut->erro_status == 0) {

      $sErro = "Erro ao reservar o saldo para autorização (dotação {$this->getDotacao()->getCodigo()})!\n";
      $sErro .= $oDaoOrcReservaAut->erro_msg;
      throw new Exception($sErro);
    }
  }

  /**
   * retorna o numero de empenho que foi gerado para essa autorizacao
   *
   * @return unknown
   */
  public function getNumeroEmpenho() {
    return $this->iEmpenho;
  }

  /**
   * verifica se já foi empenhado a autorizacao
   * @return boolean
   */
  public function isEmpenhado() {

    $lEmpenhado = false;
    if (!empty($this->iEmpenho)) {
      $lEmpenhado = true;
    }
    return $lEmpenhado;
  }

  public function anular($dtAnulação) {


    /**
     * Cancelamos as reservas de saldo, caso exista
     */
    $oDaoOrcReservaAut = db_utils::getDao("orcreservaaut");
    $oDaorcReserva = db_utils::getDao("orcreserva");
    $sSqlDadosReserva  = $oDaoOrcReservaAut->sql_query_file(null, "*",
                                                            null,
                                                            "o83_autori = {$this->iCodigo}"
                                                            );
    $rsDadosReserva   = $oDaoOrcReservaAut->sql_record($sSqlDadosReserva);
    if ($oDaoOrcReservaAut->numrows > 0) {

      $oDadosReserva  = db_utils::fieldsMemory($rsDadosReserva, 0);
      $oDaoOrcReservaAut->excluir($oDadosReserva->o83_codres);
      if ($oDaoOrcReservaAut->erro_status == 0) {

        $sErro  = "[1] - Erro ao cancelar a reserva de saldo para autorização {$this->getCodigo()})!\n";
        $sErro .= $oDaoOrcReservaAut->erro_msg;
        throw new Exception($sErro);
      }

      $oDaorcReserva->excluir($oDadosReserva->o83_codres);
      if ($oDaorcReserva->erro_status == 0) {

        $sErro  = "[2] - Erro ao cancelar a reserva de saldo para autorização {$this->getCodigo()})!\n";
        $sErro .= $oDaorcReserva->erro_msg;
        throw new Exception($sErro);
      }
    }
    /**
     * Altera a data de anulacao para false
     */
    $oDaoAutoriza = db_utils::getDao("empautoriza");
    $oDaoAutoriza->e54_anulad = implode("-", array_reverse(explode("/", $dtAnulação)));
    $oDaoAutoriza->e54_autori = $this->getCodigo();
    $oDaoAutoriza->alterar($this->getCodigo());
    if ($oDaoAutoriza->erro_status == 0) {

      $sErro  = "Erro ao anular autorizacao {$this->getCodigo()}\n";
      $sErro .= "{$oDaoAutoriza->erro_msg}";
    }
  }

  public function getContrato() {

    $iContrato          = null;
    $oDaoitemContrato   = db_utils::getDao("acordoitemexecutadoempautitem");
    $sSqlNumeroContrato = $oDaoitemContrato->sql_query_contrato(null,
                                                                "ac16_sequencial",
                                                                 "ac16_sequencial limit 1",
                                                                 "ac19_autori = {$this->getCodigo()}"
                                                               );
    $rsContrato         = $oDaoitemContrato->sql_record($sSqlNumeroContrato);
    if ($oDaoitemContrato->numrows == 1) {

      $iContrato = db_utils::fieldsMemory($rsContrato, 0)->ac16_sequencial;
    }
    return $iContrato;
  }

  /**
   * metodo que retorna se o item da dotacao possui uma autorizacao
   * @param integer codigo do material
   * @return boolean
   *
   */
  public static function verificaItemAutorizado($iCodigoItem, $iDotacao = null, $iSolicitacao) {

    // e55_item = $iCodigoItem
    // e56_coddot = $iDotacao
    // pc11_numero = $iSolicitacao

    if (!isset($iDotacao) || empty($iDotacao)) {

      return false;
    }

    $oDaoSolicitem = db_utils::getDao("solicitem");

    $sWhereSolicitem  = "    pc11_codigo = {$iCodigoItem}  ";
    $sWhereSolicitem .= "and e56_coddot  = {$iDotacao}     ";
    $sWhereSolicitem .= "and pc11_numero = {$iSolicitacao} ";
    $sWhereSolicitem .= "and e54_anulad is null            ";

    $sSqlEmpAutItem = $oDaoSolicitem->sql_query_verificaItemAutorizado( null, "e55_autori", null, $sWhereSolicitem);

    $rsEmpAutItem   = db_query($sSqlEmpAutItem);
    if (pg_numrows($rsEmpAutItem) > 0) {
      return true;
    }
    return false;

  }
}