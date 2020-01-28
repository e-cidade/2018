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
 * @fileoverview - Classe de controle dos itens de estiomativa
 * preve controle de saldos e movimentações do item
 * @package   Compras
 * @revision  $Author: dbjeferson.belmiro $
 * @version   $Revision: 1.15 $
 */

require_once ('model/itemSolicitacao.model.php');
final class ItemEstimativa extends itemSolicitacao {


   /**
   * Código do item no registro de preço
   *
   * @var integer;
   */
  private $iCodigoRegistro = null;

  protected $nQuantidadeExecedente = 0;

  /**
   *
   * @param integer $iItemSolicitacao Codigo do item na solicitaÃ§Ã£o de Compras
   * @param integer $iMaterial        Codifgo do material (pcmater.pc01_codmater)
   * @return \ItemEstimativa
   */
  function __construct($iItemSolicitacao = null, $iMaterial = null) {

    parent::__construct($iItemSolicitacao, $iMaterial);
    if (!empty($this->iCodigoItemSolicitacao)) {

      /**
       * Consultamos as informacoes do registro do preco
       */
      $oDaoSolicitemRegistro = db_utils::getDao("solicitemregistropreco");
      $sSqlRegistro          = $oDaoSolicitemRegistro->sql_query_file(null,"*",
                                                                      null,
                                                                      "pc57_solicitem={$this->iCodigoItemSolicitacao}"
                                                                      );
      $rsRegistro            = $oDaoSolicitemRegistro->sql_record($sSqlRegistro);
      if ($oDaoSolicitemRegistro->numrows > 0) {

         $oItemRegistro               = db_utils::fieldsMemory($rsRegistro, 0);
         $this->nQuantidadeExecedente = $oItemRegistro->pc57_quantidadeexecedente;
         $this->iCodigoRegistro       = $oItemRegistro->pc57_sequencial;
      }
    }
  }

  /**
   * Salva os dados do item
   *
   * @param integer $iSolicitacao Codigo da solicitação do item
   * @throws Exception
   * @return ItemEstimativa
   */
  public function save($iSolicitacao) {

    parent::save($iSolicitacao);
    /**
     * Salvamos a informacao do registro de compras
     */
    $oDaoSolicitemRegistro = db_utils::getDao("solicitemregistropreco");
    $oDaoSolicitemRegistro->pc57_ativo                = "true";
    $oDaoSolicitemRegistro->pc57_itemorigem           = $this->iOrigem;
    $oDaoSolicitemRegistro->pc57_quantmax             = "{$this->getQuantidade()}";
    $oDaoSolicitemRegistro->pc57_quantmin             = "0";
    $oDaoSolicitemRegistro->pc57_solicitem            = $this->getCodigoItemSolicitacao();
    $oDaoSolicitemRegistro->pc57_quantidadeexecedente = $this->getQuantidadeExecedente();

    if ($this->getCodigoRegistro() != "") {

      $oDaoSolicitemRegistro->pc57_sequencial = $this->getCodigoRegistro();
      $oDaoSolicitemRegistro->alterar($this->getCodigoRegistro());
    } else {

      $oDaoSolicitemRegistro->incluir(null);
      $this->iCodigoRegistro = $oDaoSolicitemRegistro->pc57_sequencial;
    }
    if ($oDaoSolicitemRegistro->erro_status == 0) {

      $sErroMsg = "Erro ao salvar item {$this->getCodigoMaterial()}!\n";
      $sErroMsg.= "Erro Retornado:{$oDaoSolicitemRegistro->erro_msg}";
      throw new Exception($sErroMsg);
    }
    return $this;
  }

  /**
   * Define a quantidade do item na estimativa
   * @param float $nQuantidade Quantidade do item
   * @return ItemEstimativa
   */
  public function setQuantidade($nQuantidade) {

    $nPercentual                 = ParametroRegistroPreco::getPercentualExecedente();
    $this->nQuantidade           = $nQuantidade;
    $this->nQuantidadeExecedente = round((($this->nQuantidade * $nPercentual)/100));
    return $this;
  }

  /**
   * Retorna as Quantidades Solicitadas do Item
   * @return float
   */
  public function getQuantidadesSolicitadas() {

    $oDaoSolicitem              = db_utils::getDao("solicitem");
    $sCampos                    = "coalesce(sum(registropreco.pc11_quant), 0) as total";
    $sWhere                     = "vincest.pc55_solicitempai = {$this->getCodigoItemSolicitacao()}";
    $sWhere                    .= "and pc10_depto  = (select pc10_depto ";
    $sWhere                    .= "                     from solicita ";
    $sWhere                    .= "                     where pc10_numero  =  itemestimativa.pc11_numero) ";
    $sWhere                    .= "and pc67_solicita is null ";
    $sSqlQuantidadesSolicitadas = $oDaoSolicitem->sql_query_compilacao_estimativa_rp(null, $sCampos, null, $sWhere);
    $rsQuantidades              = $oDaoSolicitem->sql_record($sSqlQuantidadesSolicitadas);
    $nTotalSolicitado           = 0;
    if ($oDaoSolicitem->numrows == 1) {
      $nTotalSolicitado = db_utils::fieldsMemory($rsQuantidades, 0)->total;
    }
    return $nTotalSolicitado;
  }

  /**
   * Retorna os Valores Solicitados do Item
   * @return float
   */
  public function getValorSolicitado() {

    $oDaoSolicitem              = db_utils::getDao("solicitem");
    $sCampos                    = "coalesce(sum(registropreco.pc11_quant * registropreco.pc11_vlrun), 0) as total";
    $sWhere                     = "vincest.pc55_solicitempai = {$this->getCodigoItemSolicitacao()} ";
    $sWhere                    .= "and pc67_solicita is null ";
    $sSqlQuantidadesSolicitadas = $oDaoSolicitem->sql_query_compilacao_estimativa_rp(null, $sCampos, null, $sWhere);
    $rsQuantidades              = $oDaoSolicitem->sql_record($sSqlQuantidadesSolicitadas);
    $nTotalSolicitado           = 0;

    if ($oDaoSolicitem->numrows == 1) {
      $nTotalSolicitado = db_utils::fieldsMemory($rsQuantidades, 0)->total;
    }
    return $nTotalSolicitado;
  }

  /**
   * Verifica a quantidade de itens cedidos/Recebidos.
   * @return stdClass com as quantidades cedidas/recebidas
   */
  public function  getQuantidadesCedencias() {

  	$oDaoCedenciaItem            = db_utils::getDao("registroprecocedenciaitem");
  	$sCampos                     = "coalesce(sum(case when pc36_solicitemorigem = {$this->getCodigoItemSolicitacao()} ";
  	$sCampos                    .= " then pc36_quantidade else 0 end), 0) as cedidas,                                                                 ";
  	$sCampos                    .= "coalesce(sum(case when pc36_solicitemdestino = {$this->getCodigoItemSolicitacao()} ";
  	$sCampos                    .= "  then pc36_quantidade else 0 end), 0) as recebidas                                                                ";
  	$sWhere                      = "pc36_solicitemorigem     = {$this->getCodigoItemSolicitacao()} ";
  	$sWhere                     .= "or pc36_solicitemdestino = {$this->getCodigoItemSolicitacao()} ";
  	$sSqlDadosCedencia           = $oDaoCedenciaItem->sql_query_file(null, $sCampos, null, $sWhere);
  	$rsDadosCedencia             = $oDaoCedenciaItem->sql_record($sSqlDadosCedencia);
    $oItemQuantidades            = new stdClass();
    $oDadosCedencia              = db_utils::fieldsMemory($rsDadosCedencia, 0);
    $oItemQuantidades->cedidas   = $oDadosCedencia->cedidas;
    $oItemQuantidades->recebidas = $oDadosCedencia->recebidas;
    unset($oDadosCedencia);
    return $oItemQuantidades;

  }

  /**
   * Verifica a Quantidade de Itens Que já foram Empenhadas pelo Departamento
   * @return float
   */
  public function getQuantidadesEmpenhadas() {

    $oDaoSolicitem              = db_utils::getDao("solicitem");
    $sCampos                    = "coalesce(sum(e62_quant), 0) as total";
    $sWhere                     = "vincest.pc55_solicitempai = {$this->getCodigoItemSolicitacao()} ";
    $sWhere                    .= "and pc10_depto  = (select pc10_depto ";
    $sWhere                    .= "                     from solicita ";
    $sWhere                    .= "                     where pc10_numero  =  itemestimativa.pc11_numero)";
    $sSqlQuantidadesSolicitadas = $oDaoSolicitem->sql_query_compilacao_estimativa_empenhado(null, $sCampos, null, $sWhere);
    $rsQuantidades              = $oDaoSolicitem->sql_record($sSqlQuantidadesSolicitadas);
    $nTotalEmpenhado            = 0;
    if ($oDaoSolicitem->numrows == 1) {
      $nTotalEmpenhado = db_utils::fieldsMemory($rsQuantidades, 0)->total;
    }
    return $nTotalEmpenhado;
  }

  /**
   * Verifica o Valor de Itens Que já foram Empenhados
   * @return float
   */
  public function getValoresEmpenhados() {

    $oDaoSolicitem              = db_utils::getDao("solicitem");
    $sCampos                    = "coalesce(sum(e62_vltot), 0) as total";
    $sWhere                     = "vincest.pc55_solicitempai = {$this->getCodigoItemSolicitacao()} ";
    $sSqlQuantidadesSolicitadas = $oDaoSolicitem->sql_query_compilacao_estimativa_empenhado(null, $sCampos, null, $sWhere);
    $rsQuantidades              = $oDaoSolicitem->sql_record($sSqlQuantidadesSolicitadas);
    $nTotalEmpenhado            = 0;
    if ($oDaoSolicitem->numrows == 1) {
      $nTotalEmpenhado = db_utils::fieldsMemory($rsQuantidades, 0)->total;
    }
    return $nTotalEmpenhado;
  }

  /**
   * Retorna os dados da movimentação do item
   * Retorna as quantidades empenhadas, solicitadas e transferidas do material.
   * @return Object stdClass
   */
  public function getMovimentacao() {

    /**
     * Verifica a forma de controle do regiostro de preço
     */
    $iFormaControle       = aberturaRegistroPreco::CONTROLA_QUANTIDADE;

    $oDaoSolicitavinculo  = new cl_solicitavinculo();
    $sWhereFormaControle  = " abertura.pc10_solicitacaotipo = 3 ";
    $sWhereFormaControle .= " and estcedente.pc53_solicitafilho = {$this->iSolicitacao}";
    $sSqlSolicitavinculo  = $oDaoSolicitavinculo->sql_query_abertura(null, "pc54_formacontrole", null, $sWhereFormaControle);
    $rsSolicitaAbertura   = $oDaoSolicitavinculo->sql_record($sSqlSolicitavinculo);

    if ($oDaoSolicitavinculo->numrows > 0) {
      $iFormaControle = db_utils::fieldsMemory($rsSolicitaAbertura, 0)->pc54_formacontrole;
    }

    if ($iFormaControle == aberturaRegistroPreco::CONTROLA_QUANTIDADE) {
      $oMovimentacao = $this->getMovimentacaoQuantidade();
    } else {
      $oMovimentacao = $this->getMovimentacaoValor();
    }

    $oMovimentacao->saldo = ($oMovimentacao->quantidade + $oMovimentacao->recebidas + $oMovimentacao->execedente) -
                            ($oMovimentacao->solicitada + $oMovimentacao->cedidas);

    return $oMovimentacao;
  }

  /**
   * Traz as movimentações em valor
   */
  private function getMovimentacaoValor() {

    $oMovimentacao = new stdClass();

    $oMovimentacao->quantidade = $this->getValorTotal();
    $oMovimentacao->solicitada = $this->getValorSolicitado();
    $oMovimentacao->empenhada  = $this->getValoresEmpenhados();
    $oMovimentacao->execedente = 0;
    $oMovimentacao->cedidas    = 0;
    $oMovimentacao->recebidas  = 0;

    return $oMovimentacao;
  }

  /**
   * Traz as movimentações em quantidade
   */
  private function getMovimentacaoQuantidade() {

    $oMovimentacao             = new stdClass();
    $oMovimentacao->quantidade = $this->getQuantidade();
    $oMovimentacao->solicitada = $this->getQuantidadesSolicitadas();
    $oMovimentacao->empenhada  = $this->getQuantidadesEmpenhadas();
    $oMovimentacao->execedente = $this->getQuantidadeExecedente();
    $oCedencias                = $this->getQuantidadesCedencias();
    $oMovimentacao->cedidas    = $oCedencias->cedidas;
    $oMovimentacao->recebidas  = $oCedencias->recebidas;

    return $oMovimentacao;
  }

  /**
   * Cedencias que foram feitas pelo item
   * Retorna todas as cedencias que o item cedeu
   * @return Array
   */
  public function getCedenciasRealizadas() {

    $aCedencias        = array();
    $oDaoCedenciaItens = db_utils::getDao("registroprecocedenciaitem");
    $sCampos           = "pc37_sequencial as codigocedencia, pc37_data as datacedencia, pc37_resumo as resumo,";
    $sCampos          .= "solicitadestino.pc10_depto as coddpto, pc36_quantidade as quantidade,";
    $sCampos          .= "deptodestino.descrdepto as descrdepto";
    $sWhere            = "pc36_solicitemorigem = {$this->getCodigoItemSolicitacao()}";
    $sSqlCedencia      = $oDaoCedenciaItens->sql_query_cedencia(null, $sCampos, "pc37_data", $sWhere);
    $rsCedencia        = $oDaoCedenciaItens->sql_record($sSqlCedencia);
    $aCedencias        = db_utils::getCollectionByRecord($rsCedencia);
    return $aCedencias;
  }

  /**
   * Cedencias que foram recebidas pelo item
   * Retorna todas as cedencias que o item recebe
   * @return Array
   */
  public function getCedenciasRecebidas() {

    $aCedencias        = array();
    $oDaoCedenciaItens = db_utils::getDao("registroprecocedenciaitem");
    $sCampos           = "pc37_sequencial as codigocedencia, pc37_data as datacedencia, pc37_resumo as resumo,";
    $sCampos          .= "solicitaorigem.pc10_depto as coddpto, pc36_quantidade as quantidade,";
    $sCampos          .= "deptoorigem.descrdepto as descrdepto";
    $sWhere            = "pc36_solicitemdestino = {$this->getCodigoItemSolicitacao()}";
    $sSqlCedencia      = $oDaoCedenciaItens->sql_query_cedencia(null, $sCampos, "pc37_data", $sWhere);
    $rsCedencia        = $oDaoCedenciaItens->sql_record($sSqlCedencia);
    $aCedencias        = db_utils::getCollectionByRecord($rsCedencia);
    return $aCedencias;

  }

  /**
   * retorna a quantidade execedente do item
   */
  public function getQuantidadeExecedente() {
    return $this->nQuantidadeExecedente;
  }

  /**
   * retorna o codigo do item no registro de preco
   * @return integer
   */
  public function getCodigoRegistro() {
    return $this->iCodigoRegistro;
  }

  public function remover() {

    /**
     * excluimos o vinculo do item na solicitacao;
     */
    $oDaoSolicitemVinculo = db_utils::getDao("solicitemvinculo");
    $oDaoSolicitemVinculo->excluir(null,"pc55_solicitemfilho = {$this->getCodigoItemSolicitacao()}");
    if ($oDaoSolicitemVinculo->erro_status == 0) {
      throw new Exception("Erro ao excluir item da Estimativa.");
    }

    $oDaoSolicitemRegistroPreco = db_utils::getDao('solicitemregistropreco');
    $oDaoSolicitemRegistroPreco->excluir(null, "pc57_solicitem = {$this->getCodigoItemSolicitacao()}");
    if ($oDaoSolicitemRegistroPreco->erro_status == 0) {
      throw new Exception("Erro ao excluir Registro de preço do item.");
    }
    parent::remover();
  }
}

