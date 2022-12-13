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
 * Controle de estoque
 *
 * @package estoque
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class ControleEstoque {

  /**
   * Itens do esque
   *
   * @var Item[]
   * @access private
   */
  private $aItens = array();

  /**
   * Almoxarifados
   *
   * @var Almoxarifado[]
   * @access private
   */
  private $aAlmoxarifados = array();

  /**
   * Periodo inicial
   *
   * @var DBDate
   * @access private
   */
  private $oPeriodoInicial;

  /**
   * Periodo final
   *
   * @var DBDate
   * @access private
   */
  private $oPeriodoFinal;

  /**
   * Adiciona item
   *
   * @param Item $oItem
   * @access public
   * @return void
   */
  public function adicionarItem(Item $oItem) {
    $this->aItens[$oItem->getCodigo()] = $oItem;
  }

  /**
   * Adiciona itens
   *
   * @param Item[] $aItens
   * @access public
   * @return void
   */
  public function adicionarItens( Array $aItens) {

    foreach ($aItens as $oItem) {
      $this->aItens[$oItem->getCodigo()] = $oItem;
    }
  }

  /**
   * Adiciona almoxarifado
   *
   * @param Almoxarifado $oAlmoxarifado
   * @access public
   * @return void
   */
  public function adicionarAlmoxarifado(Almoxarifado $oAlmoxarifado) {
    $this->aAlmoxarifados[$oAlmoxarifado->getCodigo()] = $oAlmoxarifado;
  }

  /**
   * Define o perido
   *
   * @param DBDate $oPeriodoInicial
   * @param DBDate $oPeriodoFinal
   * @access public
   * @return void
   */
  public function setPeriodo(DBDate $oPeriodoInicial = null, DBDate $oPeriodoFinal = null) {

    $this->oPeriodoInicial = $oPeriodoInicial;
    $this->oPeriodoFinal   = $oPeriodoFinal;
  }


  /**
   * Retorna a movimentacao do item com seus saldos iniciais calculados
   * @param MovimentacaoItem $oMovimentacaoItem
   * @param Almoxarifado     $oAlmoxarifado
   * @return MovimentacaoItem
   */
  public function getSaldoAnteriorDoItem(MovimentacaoItem $oMovimentacaoItem, Almoxarifado $oAlmoxarifado) {

    $oDataUltimoCalculo  = null;
    $oItem               = $oMovimentacaoItem->getItem();
    $nValorAnterior      = 0;
    $nQuantidadeAnterior = 0;

    if (!empty($this->oPeriodoInicial)) {

      $oUltimaPosicaoItem = PosicaoEstoque::getUltimaPosicaoDoItemNoEstoque($oMovimentacaoItem->getItem(),
                                                                            $oAlmoxarifado,
                                                                            $this->oPeriodoInicial);

      if ($oUltimaPosicaoItem) {

       $nValorAnterior      = $oUltimaPosicaoItem->getValor();
       $nQuantidadeAnterior = $oUltimaPosicaoItem->getQuantidade();
       $oDataUltimoCalculo  = $oUltimaPosicaoItem->getDataDaPosicao();
      }
    }

    /**
     * Calculamos os saldo anterior restante do item, Caso ainda exista datas a serem calculadas
     */
    $sCampos  = "sum(case when m81_tipo = 1 then m82_quant when m81_tipo = 2 then m82_quant * -1 end) as quantidade,";
    $sCampos .= "sum(case when m81_tipo = 1 then m89_valorfinanceiro when m81_tipo = 2 then m89_valorfinanceiro * -1 end) as valor";

    $sWhereSaldos  = "m70_codmatmater   = {$oItem->getCodigo()}";
    $sWhereSaldos .= " and m70_coddepto = {$oAlmoxarifado->getCodigo()}";

    if (!empty($this->oPeriodoInicial)) {
      $sWhereSaldos .= " and m80_data < '{$this->oPeriodoInicial->getDate()}'";
    }

    if (!empty($oDataUltimoCalculo)) {
      $sWhereSaldos .= " and m80_data > '{$oDataUltimoCalculo->getDate()}'";
    }

    $oDaoMatEstoqueIni = new cl_matestoqueini();
    $sSqlSaldoAnterior = $oDaoMatEstoqueIni->sql_query_movimentacoes(null, $sCampos, null, $sWhereSaldos);
    $rsSaldoAnterior   = $oDaoMatEstoqueIni->sql_record($sSqlSaldoAnterior);

    if ($rsSaldoAnterior && $oDaoMatEstoqueIni->numrows > 0) {

      $oStdSaldoAnterior    = db_utils::fieldsMemory($rsSaldoAnterior, 0);
      $nValorAnterior      += $oStdSaldoAnterior->valor;
      $nQuantidadeAnterior += $oStdSaldoAnterior->quantidade;
    }

    $oMovimentacaoItem->setQuantidadeAnterior($nQuantidadeAnterior);
    $oMovimentacaoItem->setValorAnterior(round($nValorAnterior, 2));
    return $oMovimentacaoItem;
  }

  /**
   * Busca os dados da movimentação
   * @return MovimentacaoItem[]
   */
  public function getMovimentacaoEstoqueSintetica () {

    $sCampos  = "coalesce(sum(case when matestoquetipo.m81_tipo = 1 then m82_quant end), 0) as quantidade_entrada,";
    $sCampos .= "coalesce(sum(case when matestoquetipo.m81_tipo = 2 then m82_quant end), 0) as quantidade_saida,";
    $sCampos .= "sum(case when m81_tipo = 1 then m89_valorfinanceiro else 0 end) as valor_entrada,";
    $sCampos .= "sum(case when m81_tipo = 2 then m89_valorfinanceiro else 0 end) as valor_saida";

    $sWhereSaldos  = "     matestoque.m70_codmatmater = $1";
    $sWhereSaldos .= " and matestoque.m70_coddepto    = $2";
    $sWhereSaldos .= " and m71_servico is false           ";

    if (!empty($this->oPeriodoInicial)) {
      $sWhereSaldos .= " and matestoqueini.m80_data between $3 and $4";
    } else {
      $sWhereSaldos .= " and matestoqueini.m80_data <= $3";
    }

    $sWhereSaldos .= " group by m70_codmatmater";

    $oDaoMatEstoqueIni = new cl_matestoqueini();
    $sSqlSaldoAtual    = $oDaoMatEstoqueIni->sql_query_movimentacoes(null, $sCampos, null, $sWhereSaldos);
    $rsPrepararQuery   = pg_prepare("busca_movimentacao_material", $sSqlSaldoAtual);

    $aMovimentacoesItem = array();
    foreach ($this->aAlmoxarifados as $oAlmoxarifado) {

      foreach ($this->aItens as $oItem) {

        $aParametrosQuery = array($oItem->getCodigo(), $oAlmoxarifado->getCodigo());

        if (!empty($this->oPeriodoInicial)) {
          $aParametrosQuery[] = $this->oPeriodoInicial->getDate();
        }

        $aParametrosQuery[] = $this->oPeriodoFinal->getDate();

        $rsBuscaMovimentacaoDoItem = pg_execute("busca_movimentacao_material", $aParametrosQuery);
        $iTotalMovimentacao        = pg_num_rows($rsBuscaMovimentacaoDoItem);

        /**
         * Nao encontrou movimentacao
         */

        $oStdMovimentacao = db_utils::fieldsMemory($rsBuscaMovimentacaoDoItem, 0);
        $oMovimentacaoItem = new MovimentacaoItem($oItem);

        if (!empty($this->oPeriodoInicial)) {
          $this->getSaldoAnteriorDoItem($oMovimentacaoItem, $oAlmoxarifado);
        }

        if ($iTotalMovimentacao > 0) {

          $oMovimentacaoItem->setQuantidadeEntrada($oStdMovimentacao->quantidade_entrada);
          $oMovimentacaoItem->setQuantidadeSaida($oStdMovimentacao->quantidade_saida);
          $oMovimentacaoItem->setValorEntrada($oStdMovimentacao->valor_entrada);
          $oMovimentacaoItem->setValorSaida($oStdMovimentacao->valor_saida);
        }
        $oMovimentacaoItem->setAlmoxarifado($oAlmoxarifado);
        $aMovimentacoesItem[] = $oMovimentacaoItem;
        unset($oStdMovimentacao);
      }
    }

    return $aMovimentacoesItem;
  }

  /**
   * Calcula o saldo final do item dentro do Almoxarifado Indicado
   * @param MaterialAlmoxarifado $oMaterial
   * @param Almoxarifado         $oAlmoxarifado
   * @return float
   */
  public static function getSaldoAtualDoItemNoEstoque(MaterialAlmoxarifado $oMaterial, Almoxarifado $oAlmoxarifado) {

    $nSaldo          = 0;
    $oDaoMatEstoqueIni = new cl_matestoqueini();
    $sWhere          = "m70_coddepto   = {$oAlmoxarifado->getCodigo()}";
    $sWhere         .= "and m70_codmatmater = {$oMaterial->getCodigo()}";
    $sCampos         = "coalesce(sum(case when m81_tipo = 1 then m82_quant * 1 ";
    $sCampos        .= "         when m81_tipo = 2 then m82_quant * -1 ";
    $sCampos        .= " else 0 end), 0) as saldofinal";
    $sSqlSaldoAtual = $oDaoMatEstoqueIni->sql_query_movimentacoes(null, $sCampos, null, $sWhere);
    $rsSaldoAtual   = $oDaoMatEstoqueIni->sql_record($sSqlSaldoAtual);

    if ($oDaoMatEstoqueIni->erro_status == '0') {
      throw new BusinessException("Erro ao buscar saldo do item: {$oMaterial->getDescricao()}.");
    }

    $nSaldo = db_utils::fieldsMemory($rsSaldoAtual, 0)->saldofinal;
    return $nSaldo;
  }

  /**
   * Retorna se o item está no Ponto de Pedido
   * @param MaterialAlmoxarifado $oMaterial
   * @param Almoxarifado         $oAlmoxarifad
   * @return bool
   */
  public static function itemEstaNoPontoPedido(MaterialAlmoxarifado $oMaterial, Almoxarifado $oAlmoxarifado) {

    $nSaldoFinal  = self::getSaldoAtualDoItemNoEstoque($oMaterial, $oAlmoxarifado);
    $nPontoPedido = $oMaterial->getPontoDePedidoNoAlmoxarifado($oAlmoxarifado);
    return $nSaldoFinal <= $nPontoPedido;
  }

}