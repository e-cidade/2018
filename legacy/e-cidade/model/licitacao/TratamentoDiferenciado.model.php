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

class TratamentoDiferenciado {

	const TIPO_BENEFICIO_TRATAMENTO_DIFERENCIADO = 'T';
	const TIPO_BENEFICIO_COTAS_MEEPP             = 'C';

  const TIPO_EMPRESA_MICRO = 2;

  const TIPO_EMPRESA_PEQUENO_PORTE = 3;

  const PREFIXO_LOTE_AUTOMATICO = "LOTE_AUTOITEM_";

  const SUFIXO_LOTE_RESERVA = "_RESERVA";

  /**
   * @var licitacao
   */
  private $oLicitacao;

  /**
   * @var integer
   */
  private $iCodigoOrcamento;

  /**
   * @var LicitacaoAtributosDinamicos
   */
  private $oAtributosLicitacao;

  /**
   * @var array
   */
  private $aItens = array();

  /**
   * @param licitacao $oLicitacao
   */
  public function __construct(licitacao $oLicitacao) {

    $this->oLicitacao = $oLicitacao;

    /**
     * Verifica se o objeto da licitação está com os dados carregados
     */
    if (!$this->oLicitacao->getCodigo() || !$this->oLicitacao->getAno()) {
      throw new ParameterException("Licitação não encontrada.");
    }

    $this->oAtributosLicitacao = new LicitacaoAtributosDinamicos();
    $this->oAtributosLicitacao->setCodigoLicitacao($oLicitacao->getCodigo());
    $this->iCodigoOrcamento = $this->oLicitacao->getCodigoOrcamento();
  }

  /**
   * Retorna se o "Tipo de Beneficio à Microempresa e Empresa de Pequeno Porte" é Tratamento Diferenciado/Simplificado
   *
   * @return boolean
   */
  public function temBeneficio() {

  	$sTipoBeneficio = $this->oAtributosLicitacao->getAtributo('tipobeneficiomicroepp');
    if (in_array($sTipoBeneficio, array(self::TIPO_BENEFICIO_TRATAMENTO_DIFERENCIADO, self::TIPO_BENEFICIO_COTAS_MEEPP))) {
      return true;
    }

    return false;
  }

  /**
   * @param ItemTratamentoDiferenciado $oItem
   */
  public function adicionarItem(ItemTratamentoDiferenciado $oItem) {
    $this->aItens[$oItem->getCodigoItemLicitacao()] = $oItem;
  }

  /**
   * Retorna se a licitação tem algum fornecedor ME/EPP
   *
   * @throws DBException
   * @return boolean
   */
  private function temEmpresaComTratamentoDiferenciado() {

    $oDaoLicitacao = new cl_liclicita;
    $sCampos = 'count(*) as resultado';
    $sWhere  = 'pc31_liclicitatipoempresa in('. self::TIPO_EMPRESA_MICRO . ', '. self::TIPO_EMPRESA_PEQUENO_PORTE . ')';
    $sWhere  .= " and l20_codigo = {$this->oLicitacao->getCodigo()}";
    $sSql    = $oDaoLicitacao->sql_query_licitantes($sCampos, $sWhere);
    $rsFornecedores = db_query($sSql);

    if ($rsFornecedores === false) {
      throw new DBException("Não foi possível buscar os fornecedores.");
    }

    if (pg_num_rows($rsFornecedores) === 0) {
      return false;
    }

    return db_utils::fieldsMemory($rsFornecedores, 0)->resultado > 0;
  }

  /**
   * Verifica se existe lançamentos de propostas para os Items da Licitação
   *
   * @throws DBException
   * @return boolean
   */
  private function existeLancamentoPropostas() {

    $oDaoLiclicita = new cl_liclicita;
    $sWhere = "l20_codigo = {$this->oLicitacao->getCodigo()}";
    $sSqlPropostas = $oDaoLiclicita->sql_query_propostas("count(pcorcamval.*) resultado", $sWhere);
    $rsPropostas = db_query($sSqlPropostas);

    if ($rsPropostas === false) {
      throw new DBException("Não foi possível verificar se a licitação possui lançamento de propostas.");
    }

    if (pg_num_rows($rsPropostas) === 0) {
      return false;
    }

    return db_utils::fieldsMemory($rsPropostas, 0)->resultado > 0;
  }

  /**
   * Insere o item da licitação no orçamento
   *
   * @param  ItemLicitacao $oItemLicitacao
   * @param  integer       $iCodigoOrcamento
   * @throws DBException
   * @return integer
   */
  private function insereItemOrcamento(ItemLicitacao $oItemLicitacao, $iCodigoOrcamento) {

    $oDaoOrcamentoItem = new cl_pcorcamitem;
    $oDaoOrcamentoItem->pc22_codorc = $iCodigoOrcamento;
    $oDaoOrcamentoItem->incluir(null);

    if ($oDaoOrcamentoItem->erro_status == '0') {
      throw new DBException("Não foi possível salvar item do orçamento.");
    }

    $oDaoOrcamentoItemLicitacao = new cl_pcorcamitemlic;
    $oDaoOrcamentoItemLicitacao->pc26_orcamitem = $oDaoOrcamentoItem->pc22_orcamitem;
    $oDaoOrcamentoItemLicitacao->pc26_liclicitem = $oItemLicitacao->getCodigo();
    $oDaoOrcamentoItemLicitacao->incluir(null);

    if ($oDaoOrcamentoItemLicitacao->erro_status == '0') {
      throw new DBException("Não foi possível salvar o item do orçamento da licitação.");
    }

    return $oDaoOrcamentoItem->pc22_orcamitem;
  }

  /**
   * Remove o item da licitação do orçamento
   * @param  ItemLicitacao $oItemLicitacao
   * @throws DBException
   * @return boolean
   */
  private function removeItemOrcamento(ItemLicitacao $oItemLicitacao) {

    $oDaoOrcamentoItemLicitacao = new cl_pcorcamitemlic;

    $sWhereItemOrcamento = "pc26_liclicitem = {$oItemLicitacao->getCodigo()}";
    $sSqlItemOrcamento   = $oDaoOrcamentoItemLicitacao->sql_query_file(null, 'pc26_orcamitem', null, $sWhereItemOrcamento);
    $rsItemOrcamento     = db_query($sSqlItemOrcamento);
    if ($rsItemOrcamento === false || pg_num_rows($rsItemOrcamento) === 0) {
      throw new DBException('Não foi possível buscar o item do orçamento para realizar a exclusão.');
    }
    $iCodigoItemOrcamento = db_utils::fieldsMemory($rsItemOrcamento, 0)->pc26_orcamitem;

    $oDaoOrcamentoItemLicitacao->excluir(null, "pc26_liclicitem = {$oItemLicitacao->getCodigo()}");
    if ($oDaoOrcamentoItemLicitacao->erro_status == '0') {
      throw new DBException('Não foi possível excluir o item do orçamento da licitação.');
    }

    $oDaoOrcamentoItem = new cl_pcorcamitem;
    $oDaoOrcamentoItem->excluir($iCodigoItemOrcamento);
    if ($oDaoOrcamentoItem->erro_status == '0') {
      throw new DBException('Não foi possível excluir o item do orçamento.');
    }

    return true;
  }

  /**
   * Insere o vínculo entre o item de origem da licitação com o item da reserva de quantidades
   *
   * @param  ItemLicitacao $oItemReserva
   * @param  ItemLicitacao $oItemOrigem
   * @throws DBException
   * @return integer Código do registro de vínculo
   */
  private function insereVinculoReserva(ItemLicitacao $oItemReserva, ItemLicitacao $oItemOrigem) {

    $oDaoLicitacaoReservaCotas = new cl_licitacaoreservacotas;
    $oDaoLicitacaoReservaCotas->l19_liclicitemorigem  = $oItemOrigem->getCodigo();
    $oDaoLicitacaoReservaCotas->l19_liclicitemreserva = $oItemReserva->getCodigo();
    $oDaoLicitacaoReservaCotas->incluir(null);
    if ($oDaoLicitacaoReservaCotas->erro_status == '0') {
      throw new DBException('Não foi possível salvar o vínculo entre o item da reserva de cotas e o item original da licitação.');
    }

    return $oDaoLicitacaoReservaCotas->l19_sequencial;
  }

  /**
   * Verificar se já existe reserva de quantidades para algum item da licitação
   *
   * @throws DBException
   * @return boolean
   */
  public function existeReservaQuantidade() {

    $oDaoLicitacaoReservaCotas = new cl_licitacaoreservacotas;
    $sWhere    = "l20_codigo = {$this->oLicitacao->getCodigo()}";
    $sSql      = $oDaoLicitacaoReservaCotas->sql_query(null, 'count(*) as resultado', null, $sWhere);
    $rsReserva = db_query($sSql);
    if (!$rsReserva || pg_num_rows($rsReserva) === 0) {
      throw new DBException('Não foi possível verificar se existe reserva.');
    }

    return db_utils::fieldsMemory($rsReserva, 0)->resultado > 0;
  }

  /**
   * Remove vínvulo de reserva de cotas
   *
   * @param  integer $iCodigoVinculo Código do vínculo
   * @throws DBException
   * @return boolean
   */
  private function removeVinculoReserva($iCodigoVinculo) {

    $oDaoLicitacaoReservaCotas = new cl_licitacaoreservacotas;
    $oDaoLicitacaoReservaCotas->excluir($iCodigoVinculo);

    if ($oDaoLicitacaoReservaCotas->erro_status == '0') {
      throw new DBException('Não foi possível apagar o vínculo da reserva com o item original.');
    }

    return true;
  }

  /**
   * Insere vínculo com o elemento
   *
   * @param itemSolicitacao $oItemOrigem
   * @param itemSolicitacao $oItemReserva
   * @throws DBException
   * @return boolean
   */
  private function insereVinculoElemento(itemSolicitacao $oItemOrigem, itemSolicitacao $oItemReserva) {

    $oDaoSolicitacaoElemento = new cl_solicitemele;
    $sSql = $oDaoSolicitacaoElemento->sql_query_file($oItemOrigem->getCodigoItemSolicitacao());
    $rsElemento = db_query($sSql);

    if (!$rsElemento) {
      throw new DBException("Não foi possível encontrar o elemento do item de origem.");
    }

    if (pg_num_rows($rsElemento) == 0) {
      return true;
    }

    $iCodigoElemento = db_utils::fieldsMemory($rsElemento, 0)->pc18_codele;
    $iCodigoItem     = $oItemReserva->getCodigoItemSolicitacao();
    $oDaoSolicitacaoElemento->pc18_solicitem = $iCodigoItem;
    $oDaoSolicitacaoElemento->pc18_codele    = $iCodigoElemento;
    $oDaoSolicitacaoElemento->incluir($iCodigoItem, $iCodigoElemento);

    if ($oDaoSolicitacaoElemento->erro_status == '0') {
      throw new DBException("Não foi possível incluir o vínculo do elemento com o item da reserva.");
    }

    return true;
  }

  /**
   * Remove Vínvulo do Elemento e Item da Solicitação
   *
   * @param  itemSolicitacao $oItemSolicitacao
   * @throws DBException
   * @return boolean
   */
  private function removeVinculoElemento(itemSolicitacao $oItemSolicitacao) {

    $oDaoSolicitacaoElemento = new cl_solicitemele;
    $oDaoSolicitacaoElemento->excluir($oItemSolicitacao->getCodigoItemSolicitacao());

    if ($oDaoSolicitacaoElemento->erro_status == '0') {
      throw new DBException("Não foi possível excluir o elemento do item de solicitação");
    }

    return true;
  }

  /**
   * Vincula Dotação ao Item Reserva e manipula as reservas de saldo
   *
   * @param itemSolicitacao $oItemSolicitacaoReserva
   * @param itemSolicitacao $oItemSolicitacaoOrigem
   * @throws DBException
   */
  private function vinculaDotacaoItemReserva(itemSolicitacao $oItemSolicitacaoReserva, itemSolicitacao $oItemSolicitacaoOrigem) {

    $aDotacoes = $oItemSolicitacaoOrigem->getDotacoes();

    $iQuantidadeRestante = $oItemSolicitacaoReserva->getQuantidade();

    foreach ($aDotacoes as $oDotacao) {

      if ($oDotacao->nQuantidade <= $iQuantidadeRestante) {

        $iQuantidadeRestante -= $oDotacao->nQuantidade;

        if ($oDotacao->iCodigoReserva) {

          /**
           * Aponta reserva de saldo do item de origem para o item das cotas de reservas
           */
          $sSql = "update orcreservasol set o82_solicitem = {$oItemSolicitacaoReserva->getCodigoItemSolicitacao()}";
          $sSql .= " where o82_solicitem = {$oItemSolicitacaoOrigem->getCodigoItemSolicitacao()} and ";
          $sSql .= " o82_pcdotac = {$oDotacao->iCodigoDotacaoItem}";
          $rsReservaSolicitacao = db_query($sSql);

          if ($rsReservaSolicitacao === false || pg_affected_rows($rsReservaSolicitacao) !== 1) {
            throw new DBException("Não foi possível apontar reserva de saldo do item de origem.");
          }
        }

        $oDaoDotacaoSolicitacao = new cl_pcdotac;
        $oDaoDotacaoSolicitacao->pc13_sequencial = $oDotacao->iCodigoDotacaoItem;
        $oDaoDotacaoSolicitacao->pc13_codigo     = $oItemSolicitacaoReserva->getCodigoItemSolicitacao();
        $oDaoDotacaoSolicitacao->alterar($oDotacao->iCodigoDotacaoItem);

        if ($oDaoDotacaoSolicitacao->erro_status == '0') {
          throw new DBException('Não foi possível alterar o vínculo entre a dotação e o item de origem.');
        }
      } else {

        /**
         * Decrementa quantidade reservada do vínculo da dotação
         */
        $nQuantidadeAtualizada = $oDotacao->nQuantidade - $iQuantidadeRestante;

        /**
         * Calcula o valor unitário proporcional a reserva de saldo
         */
        $nValorProporcional = $oDotacao->nValorDotacao / $oDotacao->nQuantidade;

        $oDaoDotacaoSolicitacao = new cl_pcdotac;
        $oDaoDotacaoSolicitacao->pc13_sequencial = $oDotacao->iCodigoDotacaoItem;
        $oDaoDotacaoSolicitacao->pc13_quant      = $nQuantidadeAtualizada;
        $oDaoDotacaoSolicitacao->pc13_valor      = round($nValorProporcional * $nQuantidadeAtualizada, 2);
        $oDaoDotacaoSolicitacao->alterar($oDotacao->iCodigoDotacaoItem);

        if ($oDaoDotacaoSolicitacao->erro_status == '0') {
          throw new DBException('Não foi possível alterar o vínculo entre a dotação e o item de origem.');
        }

        $oDaoDotacaoSolicitacao->pc13_sequencial = null;
        $oDaoDotacaoSolicitacao->pc13_anousu     = $oDotacao->iAno;
        $oDaoDotacaoSolicitacao->pc13_coddot     = $oDotacao->oDotacao->getCodigo();
        $oDaoDotacaoSolicitacao->pc13_codele     = $oDotacao->iCodigoElemento;
        $oDaoDotacaoSolicitacao->pc13_depto      = $oDotacao->iDepartamento;
        $oDaoDotacaoSolicitacao->pc13_quant      = $iQuantidadeRestante;
        $oDaoDotacaoSolicitacao->pc13_valor      = round($iQuantidadeRestante * $nValorProporcional, 2);
        $oDaoDotacaoSolicitacao->pc13_codigo     = $oItemSolicitacaoReserva->getCodigoItemSolicitacao();
        $oDaoDotacaoSolicitacao->incluir(null);

        if ($oDaoDotacaoSolicitacao->erro_status == '0') {
          throw new DBException('Não foi possível incluir o vínculo entre a dotação e o item da reserva.');
        }

        /**
         * Cria Nova Reserva de Saldo para o Item Reserva e Atualiza a Reserva de Saldo do Item de Origem
         */
        if ($oDotacao->iCodigoReserva) {

          $oDaoDadosReserva = new cl_orcreservasol;
          $sWhere  = "o80_coddot = {$oDotacao->oDotacao->getCodigo()} and ";
          $sWhere .= "o80_anousu = {$oDotacao->oDotacao->getAno()} and ";
          $sWhere .= "pc11_codigo = {$oDotacao->iCodigoItemSolicitacao}";
          $sSqlReserva = $oDaoDadosReserva->sql_query(null, '*', null, $sWhere);
          $rsReserva = db_query($sSqlReserva);

          if ($rsReserva === false && pg_num_rows($rsReserva) === 0) {
            throw new DBException("Não foi possível encontrar as informações da Reserva de Saldo.");
          }

          $oDadosReserva = db_utils::fieldsMemory($rsReserva, 0);
          /**
           * Atualiza Reserva do Item Origem
           */
          $oDaoReservaOrigem = new cl_orcreserva;
          $oDaoReservaOrigem->o80_codres = $oDadosReserva->o80_codres;
          $oDaoReservaOrigem->o80_valor  = round($nQuantidadeAtualizada * $nValorProporcional, 2);
          $oDaoReservaOrigem->alterar($oDadosReserva->o80_codres);

          if ($oDaoReservaOrigem->erro_status == '0') {
            throw new DBException("Não foi possível atualizar a Reserva de Saldo no Orçamento para o Item  de Origem.");
          }

          $oDaoReserva = new cl_orcreserva;
          $oDaoReserva->o80_codres = null;
          $oDaoReserva->o80_anousu = $oDadosReserva->o80_anousu;
          $oDaoReserva->o80_coddot = $oDadosReserva->o80_coddot;
          $oDaoReserva->o80_dtfim  = $oDadosReserva->o80_dtfim;
          $oDaoReserva->o80_dtini  = $oDadosReserva->o80_dtini;
          $oDaoReserva->o80_dtlanc = $oDadosReserva->o80_dtlanc;
          $oDaoReserva->o80_valor  = round($iQuantidadeRestante * $nValorProporcional, 2);
          $oDaoReserva->o80_descr  = empty($oDadosReserva->o80_descr) ? "Reserva para Solicitação {$oItemSolicitacaoReserva->getCodigoItemSolicitacao()}": $oDadosReserva->o80_descr;
          $oDaoReserva->incluir(null);

          if ($oDaoReserva->erro_status == '0') {
            throw new DBException("Não foi possível criar a reserva de saldo no orçamento para o item.");
          }

          $oDaoReservaSol = new cl_orcreservasol;
          $oDaoReservaSol->o82_sequencial = null;
          $oDaoReservaSol->o82_codres     = $oDaoReserva->o80_codres;
          $oDaoReservaSol->o82_solicitem  = $oItemSolicitacaoReserva->getCodigoItemSolicitacao();
          $oDaoReservaSol->o82_pcdotac    = $oDaoDotacaoSolicitacao->pc13_sequencial;
          $oDaoReservaSol->incluir(null);

          if ($oDaoReservaSol === false) {
            throw new DBException("Não foi possível criar o vínculo da reserva de saldo no orçamento para o item.");
          }
        }

        $iQuantidadeRestante = 0;
      }

      if ($iQuantidadeRestante == 0) {
        break;
      }
    }
  }

  /**
   * Remove Dotação do Item Reserva e manipula as reservas de saldo
   *
   * @param itemSolicitacao $oItemSolicitacaoReserva
   * @param itemSolicitacao $oItemSolicitacaoOrigem
   * @throws DBException
   */
  private function removeDotacaoItemReserva(itemSolicitacao $oItemSolicitacaoReserva, itemSolicitacao $oItemSolicitacaoOrigem) {

    $aDotacoesOrigem = array_map(function($oDotacaoOrigem) {
      return $oDotacaoOrigem->oDotacao->getCodigo();
    }, $oItemSolicitacaoOrigem->getDotacoes());

    $aDotacoesReserva = $oItemSolicitacaoReserva->getDotacoes();
    foreach ($aDotacoesReserva as $oDotacaoReserva) {

      if (in_array($oDotacaoReserva->oDotacao->getCodigo(), $aDotacoesOrigem)) {

        /**
         * Transfere a Reserva de Saldo para os Itens de Origem
         */
        if ($oDotacaoReserva->iCodigoReserva) {

          /**
           * Pega Informações da Reserva de Saldo do Item de Reserva
           */
          $oDaoDadosReserva = new cl_orcreservasol;
          $sWhere  = "pc13_coddot = {$oDotacaoReserva->oDotacao->getCodigo()} and ";
          $sWhere .= "pc11_codigo = {$oItemSolicitacaoReserva->getCodigoItemSolicitacao()}";
          $sSqlDadosReserva      = $oDaoDadosReserva->sql_query(null, 'o80_codres', null, $sWhere);
          $rsDadosReservaReserva = db_query($sSqlDadosReserva);
          if ($rsDadosReservaReserva === false && pg_num_rows($rsDadosReservaReserva) === 0) {
            throw new DBException("Não foi possível encontrar as informações de reserva de saldo do item.");
          }

          $iCodigoReservaReserva = db_utils::fieldsMemory($rsDadosReservaReserva, 0)->o80_codres;

          /**
           * Pega Informações da Reserva de Saldo do Item de Origem
           */
          $oDaoDadosReserva = new cl_orcreservasol;
          $sWhere  = "pc13_coddot = {$oDotacaoReserva->oDotacao->getCodigo()} and ";
          $sWhere .= "pc11_codigo = {$oItemSolicitacaoOrigem->getCodigoItemSolicitacao()}";
          $sSqlDadosReserva     = $oDaoDadosReserva->sql_query(null, '*', null, $sWhere);
          $rsDadosReservaOrigem = db_query($sSqlDadosReserva);
          if ($rsDadosReservaOrigem === false && pg_num_rows($rsDadosReservaOrigem) === 0) {
            throw new DBException("Não foi possível encontrar as informações de reserva de saldo do item de origem.");
          }

          $oDadosReservaOrigem = db_utils::fieldsMemory($rsDadosReservaOrigem, 0);

          /**
           * Transfere valor da Reserva de Saldo para a Reserva de Saldo do Item de Origem
           */
          $nValorTotal        = $oDadosReservaOrigem->o80_valor + $oDotacaoReserva->nValorDotacao;
          $nQuantidadeTotal   = $oDotacaoReserva->nQuantidade + $oDadosReservaOrigem->pc13_quant;
          $nValorProporcional = $nValorTotal / $nQuantidadeTotal;
          $nValorAtualizado   = $nValorProporcional * $nQuantidadeTotal;

          $sSqlAtualizaReserva   = "update orcreserva set o80_valor = {$nValorAtualizado}";
          $sSqlAtualizaReserva  .= "where o80_codres = {$oDadosReservaOrigem->o80_codres}";
          $rsAtualizaReserva     = db_query($sSqlAtualizaReserva);
          if ($rsAtualizaReserva === false) {
            throw new DBException("Não foi possível atualizar a reserva de saldo do item de origem.");
          }

          /**
           * Apaga reserva de saldo do item de reserva
           */
          $oItemSolicitacaoReserva->excluiReservaSaldo($iCodigoReservaReserva);
        }

        /**
         * Transfere quantidades reservadas para o item de origem e atualiza os valor total
         */
        $sSqlAtualizaDotacao  = "update pcdotac set pc13_quant = (pc13_quant + {$oDotacaoReserva->nQuantidade}), ";
        $sValorProporcional   = "( (pc13_valor + {$oDotacaoReserva->nValorDotacao}) / (pc13_quant + {$oDotacaoReserva->nQuantidade}) ) ";
        $sSqlAtualizaDotacao .= "pc13_valor = round( (pc13_quant + {$oDotacaoReserva->nQuantidade}) * {$sValorProporcional}, 2) ";
        $sSqlAtualizaDotacao .= "where pc13_coddot = {$oDotacaoReserva->oDotacao->getCodigo()} and pc13_codigo = {$oItemSolicitacaoOrigem->getCodigoItemSolicitacao()} ";
        $rsAtualizaDotacao = db_query($sSqlAtualizaDotacao);
        if ($rsAtualizaDotacao === false) {
          throw new DBException("Não foi possível atualizar a dotação de origem.");
        }

        /**
         * Exclui dotação do item reserva
         */
        $sSqlExcluiReserva = "delete from pcdotac where pc13_coddot = {$oDotacaoReserva->oDotacao->getCodigo()} and pc13_codigo = {$oItemSolicitacaoReserva->getCodigoItemSolicitacao()} ";
        $rsExcluiReserva = db_query($sSqlExcluiReserva);

        if ($rsExcluiReserva === false) {
          throw new DBException("Não foi possível excluir dotação do item reservado.");
        }
      } else {

        if ($oDotacaoReserva->iCodigoReserva) {

          /**
           * Pega dados da Reserva de Saldo do Item de Reserva
           */
          $oDaoDadosReserva = new cl_orcreservasol;
          $sWhere  = "pc13_dotac = {$oDotacaoReserva->oDotacao->getCodigo()} and ";
          $sWhere .= "pc11_codigo = {$oItemSolicitacaoReserva->getCodigoItemSolicitacao()}";
          $sSqlDadosReserva = $oDaoDadosReserva->sql_query(null, '*', null, $sWhere);

          /**
           * Transfere reserva de saldo para o item de origem
           */
          $sSqlAtualizaReservaSaldo  = "update orcreservasol set o82_solicitem = {$oItemSolicitacaoOrigem->getCodigoItemSolicitacao()} ";
          $sSqlAtualizaReservaSaldo .= "where o82_solicitem = {$oItemSolicitacaoReserva->getCodigoItemSolicitacao()}";
          $rsReservaSaldo = db_query($sSqlAtualizaReservaSaldo);

          if ($rsReservaSaldo === false) {
            throw new DBException("Não foi possível atualizar reserva de saldo para o item de origem.");
          }
        }

        /**
         * Atualiza dotações de item reserva não excluido para as dotações de origem
         */
        $sSqlAtualizaDotacao  = "update pcdotac set pc13_codigo = {$oItemSolicitacaoOrigem->getCodigoItemSolicitacao()} ";
        $sSqlAtualizaDotacao .= "where pc13_coddot = {$oDotacaoReserva->oDotacao->getCodigo()} and pc13_codigo = {$oItemSolicitacaoReserva->getCodigoItemSolicitacao()}";
        $rsAtualizaDotacao = db_query($sSqlAtualizaDotacao);

        if ($rsAtualizaDotacao === false) {
          throw new DBException("Não foi possível atualizar dotação do item origem.");
        }
      }
    }
  }

  /**
   * Reserva as quantidades informadas de cada item para ME ou EPP
   *
   * @throws DBException
   * @throws BusinessException
   * @return array Código dos registros criados na solicitem.
   */
  public function reservarQuantidades() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Nenhuma transação com o banco de dados.");
    }

    if ($this->oLicitacao->hasJulgamento()) {
      throw new BusinessException('A licitação já foi julgada. Não é possível fazer reserva de itens.');
    }

    if (!$this->temBeneficio()) {
      throw new BusinessException('A licitação informada não possui tratamento diferenciado para ME/EPP.');
    }

    if (!$this->temEmpresaComTratamentoDiferenciado()) {
      throw new BusinessException('A licitação informada possui tratamento diferenciado, mas não foi encontrada nenhuma ME/EPP.');
    }

    if ($this->existeLancamentoPropostas()) {
      throw new BusinessException("Não foi possível reservar itens. A Licitação já possui propostas.");
    }

    if ($this->iCodigoOrcamento === null) {
      throw new BusinessException('Não existe orçamento para a licitação.');
    }

    if ($this->existeReservaQuantidade()) {
      $this->cancelarReservas();
    }

    foreach ($this->oLicitacao->getItens() as $oItemLicitacaoOrigem) {


      $oItemReserva = null;
      if (isset($this->aItens[$oItemLicitacaoOrigem->getCodigo()])) {
        $oItemReserva = $this->aItens[$oItemLicitacaoOrigem->getCodigo()];
      }

      /**
       * Caso não tenha sido reservada quantidade para o item
       */
      if ($oItemReserva === null || $oItemReserva->getQuantidade() == 0) {
        continue;
      }

      if (!is_integer($oItemReserva->getQuantidade())) {
        throw new BusinessException('Não é possível reservar quantidades decimais.');
      }

      $oItemSolicitacaoOrigem = $oItemLicitacaoOrigem->getItemSolicitacao();
      $iQuantidadeAtualizada  = $oItemSolicitacaoOrigem->getQuantidade() - $oItemReserva->getQuantidade();
      if ($iQuantidadeAtualizada <= 0) {

        $sMensagem = "A quantidade reservada para o item {$oItemReserva->getCodigoItemLicitacao()} é inválida.";
        throw new BusinessException($sMensagem);
      }

      /**
       * Subtraí a quantidade reservada do item original
       */
      $oItemSolicitacaoOrigem->setQuantidade($iQuantidadeAtualizada);
      $oItemSolicitacaoOrigem->save();

      /**
       * Cria o novo item com as quantidades reservadas
       */
      $oItemSolicitacaoReserva = clone $oItemSolicitacaoOrigem;
      $oItemSolicitacaoReserva->setQuantidade($oItemReserva->getQuantidade());
      $oItemSolicitacaoReserva->save();

      /**
       * Cria vínculo do item da reserva com o elemento
       */
      $this->insereVinculoElemento($oItemSolicitacaoOrigem, $oItemSolicitacaoReserva);

      /**
       * Faz o vínculo entre o novo item criado para a reserva do tratamento diferenciado com o processo
       * de compras do item original
       */
      $oItemProcessoCompraReserva = new ItemProcessoCompra;
      $oItemProcessoCompraReserva->setItemSolicitacao($oItemSolicitacaoReserva);
      $oItemProcessoCompraReserva->setCodigoProcesso($oItemLicitacaoOrigem->getProcessoCompra());
      $oItemProcessoCompraReserva->salvar();

      /**
       * Vincula o item com as quantidades reservadas na licitação
       */
      $oItemLicitacaoReserva = new ItemLicitacao;
      $oItemLicitacaoReserva->setCodigoLicitacao($oItemLicitacaoOrigem->getCodigoLicitacao());
      $oItemLicitacaoReserva->setItemProcessoCompras($oItemProcessoCompraReserva->getCodigo());
      $oItemLicitacaoReserva->salvar();

      /**
       * Se o julgamento é por lote cria com o mesmo nome original e adiciona o sufixo "_RESERVA",
       * caso contário cria lote automático
       */
      $oLoteItemOrigem  = $oItemLicitacaoOrigem->getLoteLicitacao();
      $oLoteItemReserva = new LoteLicitacao;
      $oLoteItemReserva->setDescricao($oLoteItemOrigem->getDescricao() . self::SUFIXO_LOTE_RESERVA);
      $oLoteItemReserva->setItem($oItemLicitacaoReserva);
      if ($oLoteItemOrigem->automatico()) {

        $sDescricaoLote = self::PREFIXO_LOTE_AUTOMATICO . $oItemSolicitacaoReserva->getCodigoItemSolicitacao();
        $oLoteItemReserva->setDescricao($sDescricaoLote);
      }
      $oLoteItemReserva->salvar();

      $this->insereItemOrcamento($oItemLicitacaoReserva, $this->iCodigoOrcamento);
      $this->insereVinculoReserva($oItemLicitacaoReserva, $oItemLicitacaoOrigem);
      $this->vinculaDotacaoItemReserva($oItemSolicitacaoReserva, $oItemSolicitacaoOrigem);

      if($this->oLicitacao->usaRegistroDePreco()) {
        $this->incluirItemRegistroPreco($oItemSolicitacaoReserva, $oItemSolicitacaoOrigem);
      }
    }
  }

  /**
   * Desfaz as reservas de quantidades de ME ou EPP, agrupando por código do material
   */
  public function cancelarReservas() {

    $oDaoLicitacaoReservaCotas = new cl_licitacaoreservacotas;
    $sCampos = 'l19_sequencial, l19_liclicitemorigem, l19_liclicitemreserva';
    $sWhere  = "l20_codigo = {$this->oLicitacao->getCodigo()}";
    $sSql    = $oDaoLicitacaoReservaCotas->sql_query(null, $sCampos, null, $sWhere);
    $rsItens = db_query($sSql);

    $iQuantidadeRegistros = pg_num_rows($rsItens);
    if ($rsItens === false || $iQuantidadeRegistros === 0) {
      throw new DBException('Não foi possível buscar as reservas existentes.');
    }

    for ($iRegistro = 0; $iRegistro < $iQuantidadeRegistros; $iRegistro++) {

      $oStdDadosReserva = db_utils::fieldsMemory($rsItens, $iRegistro);

      $oItemReserva = new ItemLicitacao($oStdDadosReserva->l19_liclicitemreserva);
      $oItemOrigem  = new ItemLicitacao($oStdDadosReserva->l19_liclicitemorigem);

      $oItemSolicitacaoOrigem  = $oItemOrigem->getItemSolicitacao();
      $oItemSolicitacaoReserva = $oItemReserva->getItemSolicitacao();
      $iQuantidadeAtualizada   = $oItemSolicitacaoOrigem->getQuantidade() + $oItemSolicitacaoReserva->getQuantidade();
      $oItemSolicitacaoOrigem->setQuantidade($iQuantidadeAtualizada);
      $oItemSolicitacaoOrigem->save();
      
      $this->removeVinculoElemento($oItemSolicitacaoReserva);
      $this->removeItemOrcamento($oItemReserva);
      $this->removeDotacaoItemReserva($oItemSolicitacaoReserva, $oItemSolicitacaoOrigem);
      $this->removeVinculoReserva($oStdDadosReserva->l19_sequencial);

      if($this->oLicitacao->usaRegistroDePreco()) {
        $this->removerItemRegistroPrecoReserva($oItemSolicitacaoReserva, $oItemSolicitacaoOrigem);
      }
      
      $oItemReserva->remover($oStdDadosReserva->l19_liclicitemreserva);

      $oItemProcessoComprasReserva = new ItemProcessoCompra();
      $oItemProcessoComprasReserva->setCodigo($oItemReserva->getItemProcessoCompras());
      $oItemProcessoComprasReserva->excluir();

      $oItemReserva->getItemSolicitacao()->remover();
    }
  }

  /**
   * @param \itemSolicitacao $oItemSolicitacaoReserva
   * @param \itemSolicitacao $oItemSolicitacaoOrigem
   * @throws \DBException
   */
  private function incluirItemRegistroPreco(itemSolicitacao $oItemSolicitacaoReserva, itemSolicitacao $oItemSolicitacaoOrigem) {
    
    $oDaoSolicitemRegistroPreco = new cl_solicitemregistropreco();
    $oDaoSolicitemRegistroPreco->pc57_ativo      = 'true';
    $oDaoSolicitemRegistroPreco->pc57_itemorigem = $oItemSolicitacaoOrigem->getCodigoOrigem();
    $oDaoSolicitemRegistroPreco->pc57_quantmax   = $oItemSolicitacaoReserva->getQuantidade();
    $oDaoSolicitemRegistroPreco->pc57_quantmin   = 1;
    $oDaoSolicitemRegistroPreco->pc57_solicitem  = $oItemSolicitacaoReserva->getCodigoItemSolicitacao();
    $oDaoSolicitemRegistroPreco->incluir(null);
    if ($oDaoSolicitemRegistroPreco->erro_status == 0) {
      throw new DBException('Não foi possível salvar dados da reserva');
    }
    
    /**
     * Alterar a quantidade do item original 
     */
    $oItemCompilacao = new itemCompilacao($oItemSolicitacaoOrigem->getCodigoItemSolicitacao());
    $oItemCompilacao->setQuantidadeMaxima($oItemSolicitacaoOrigem->getQuantidade());
    $oItemCompilacao->save();
  }
  
  private function removerItemRegistroPrecoReserva(itemSolicitacao $oItemSolicitacaoReserva, itemSolicitacao $oItemSolicitacaoOrigem) {
    
    $oDaoSolicitemRegistroPreco = new cl_solicitemregistropreco();    
    $oDaoSolicitemRegistroPreco->excluir(null, "pc57_solicitem={$oItemSolicitacaoReserva->getCodigoItemSolicitacao()}");
    if ($oDaoSolicitemRegistroPreco->erro_status == 0) {
      throw new DBException('Não foi possível salvar dados da reserva');
    }
    
    $oItemCompilacao = new itemCompilacao($oItemSolicitacaoOrigem->getCodigoItemSolicitacao());
    $oItemCompilacao->setQuantidadeMaxima($oItemSolicitacaoOrigem->getQuantidade());
    $oItemCompilacao->save();
  }
}
