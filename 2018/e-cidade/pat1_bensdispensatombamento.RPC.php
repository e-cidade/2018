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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("model/contabilidade/ParametroIntegracaoPatrimonial.model.php"));

define("ENTRADA_DISPENSA_TOMBAMENTO", 25);
define("ANULACAO_ENTRADA_DISPENSA_TOMBAMENTO", 26);

$oJson       = new services_json();
$oParametros = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus   = "1";
$oRetorno->sMensagem = "";

$oDaoEmpnotaitembenspendente = new cl_empnotaitembenspendente;
$oDaoBensDispensaTombamento  = new cl_bensdispensatombamento;

/**
 * Caminho das mensagens do programa
 */
define('MENSAGENS', 'patrimonial.patrimonio.pat1_bensdispensatombamento.');

try {

  db_inicio_transacao();

  switch ( $oParametros->sExecucao ) {

    /**
     * Estonar
     * - documento 210
     */
    case 'processar':

      $oParametros->sJustificativa = db_stdClass::normalizeStringJsonEscapeString($oParametros->sJustificativa);
      $iCodigoDocumento = 210;

      $sWhere               = "e137_empnotaitem = {$oParametros->iCodigoEmpNotaItem}";
      $sSqlBensNotaPendente = $oDaoEmpnotaitembenspendente->sql_query_file(null,"*",null,$sWhere);
      $rsBensNotaPendente   = db_query($sSqlBensNotaPendente);
      if (!$rsBensNotaPendente || pg_num_rows($rsBensNotaPendente) == 0 ) {
        throw new Exception (_M(MENSAGENS."nota_item_nao_encontrado"));
      }
      $oDadosEmpnotaitem = db_utils::fieldsMemory($rsBensNotaPendente,0);

      $oEmpFinanceiro = new EmpenhoFinanceiro($oParametros->iNumeroEmpenho);
      if($oEmpFinanceiro->isRestoAPagar(db_getsession('DB_anousu'))) {
        $iCodigoDocumento = 212;
      }

      /**
       * Cria o movimento no estoque antes de excluir o registro na empnotaitembenspendente,
       * pois a função consulta o registro na tabela.
       */
      $oItemEstoque = new MaterialEstoqueItem($oDadosEmpnotaitem->e137_matestoqueitem);
      $oItem = processarMovimentoEstoque($oDadosEmpnotaitem->e137_empnotaitem, $oItemEstoque, false);
      $oDaoEmpnotaitembenspendente->excluir($oDadosEmpnotaitem->e137_sequencial);
      if ($oDaoEmpnotaitembenspendente->erro_status == "0") {
        throw new Exception (_M(MENSAGENS."nota_item_nao_excluindo"));
      }

      $oDaoBensDispensaTombamento->e139_empnotaitem    = $oDadosEmpnotaitem->e137_empnotaitem;
      $oDaoBensDispensaTombamento->e139_matestoqueitem = $oItem->getCodigo();
      $oDaoBensDispensaTombamento->e139_justificativa  = $oParametros->sJustificativa;
      $oDaoBensDispensaTombamento->incluir(null);
      if ($oDaoBensDispensaTombamento->erro_status == "0") {

        $oDadosErro = (object) array('sErroBanco' => $oDaoBensDispensaTombamento->erro_banco);
        throw new Exception (_M(MENSAGENS."dispensa_tombamento_nao_incluido", $oDadosErro));
      }

      processarLancamento($iCodigoDocumento, $oDadosEmpnotaitem->e137_matestoqueitem, $oDadosEmpnotaitem->e137_empnotaitem, $oParametros);

      $oRetorno->sMensagem = _M(MENSAGENS . 'processamento_efetuado_sucesso');
    break;

    /**
     * Estonar
     * - documento 211
     */
    case 'estornar':

      $oParametros->sJustificativa = db_stdClass::normalizeStringJsonEscapeString($oParametros->sJustificativa);
      $sWhere = "e139_empnotaitem = {$oParametros->iCodigoEmpNotaItem}";
      $sSqlBensDispensaTombamento = $oDaoBensDispensaTombamento->sql_query_file(null,"*",null,$sWhere);
      $rsBensDispensaTombamento   = db_query($sSqlBensDispensaTombamento);
      $iCodigoDocumento = 211;

      if (!$rsBensDispensaTombamento || pg_num_rows($rsBensDispensaTombamento) == 0 ){

        $oDadosErro = (object) array('sErroBanco' => pg_last_error());
        throw new Exception (_M(MENSAGENS."dispensa_tombamento_nao_encontrado", $oDadosErro));
      }

      $oDadosTombamento = db_utils::fieldsMemory($rsBensDispensaTombamento,0);
      $oDaoBensDispensaTombamento->excluir($oDadosTombamento->e139_sequencial);

      if ($oDaoBensDispensaTombamento->erro_status == 0) {

        $oDadosErro = (object) array('sErroBanco' => $oDaoBensDispensaTombamento->erro_banco);
        throw new Exception (_M(MENSAGENS."dispensa_tombamento_nao_excluido", $oDadosErro));
      }

      $oDaoEmpnotaitembenspendente->e137_empnotaitem    = $oDadosTombamento->e139_empnotaitem;
      $oDaoEmpnotaitembenspendente->e137_matestoqueitem = $oDadosTombamento->e139_matestoqueitem;
      $oDaoEmpnotaitembenspendente->incluir(null);

      if ( $oDaoEmpnotaitembenspendente->erro_status == "0" ) {

        $oDadosErro = (object) array('sErroBanco' => pg_last_error());
        throw new Exception (_M(MENSAGENS . "nota_item_nao_incluido", $oDadosErro));
      }

      $oEstoqueItem = new MaterialEstoqueItem($oDadosTombamento->e139_matestoqueitem);
      if ($oEstoqueItem->getQuantidadeAtendida() > 0) {
        throw new Exception("A entrada para esse item já possuí quantidades atendidas. Procedimento abortado.");
      }

      $oEmpFinanceiro = new EmpenhoFinanceiro($oParametros->iNumeroEmpenho);
      if($oEmpFinanceiro->isRestoAPagar(db_getsession('DB_anousu'))) {
        $iCodigoDocumento = 213;
      }

      $oMovimento = processarMovimentoEstoque($oDadosTombamento->e139_empnotaitem, $oEstoqueItem, true);
      processarLancamento($iCodigoDocumento, $oMovimento->getCodigo(), $oDadosTombamento->e139_empnotaitem, $oParametros);
      $oRetorno->sMensagem = _M(MENSAGENS . 'estorno_efetuado_sucesso');

      break;

    default :
      throw new Exception('Parâmetro inválido');
      break;
  }

  db_fim_transacao(false);

} catch ( Exception $eErro ) {

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = $eErro->getMessage();
  db_fim_transacao(true);
}

$oRetorno->sMensagem = urlEncode($oRetorno->sMensagem);

echo $oJson->encode($oRetorno);

/**
 * Processa lancamento
 *
 * @param integer $iCodigoDocumento
 * @param integer $iCodigoItemEstoque
 * @param integer $iCodigoItemNota
 * @param stdClass $oParametros
 * @access public
 * @throws Exception|DBException
 * @return true
 */
function processarLancamento($iCodigoDocumento, $iCodigoItemEstoque, $iCodigoItemNota, $oParametros) {

  $oDataImplantacao  = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
  $oInstituicao      = new Instituicao(db_getsession('DB_instit'));

  /**
   * Verifica se para data atual existe integracao com financeiro para efetuar lancamento
   */
  if ( !ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataImplantacao, $oInstituicao) ) {
    return false;
  }

  if ( empty($oParametros->iNumeroEmpenho) ) {
    throw new Exception('Número de empenho não informado.');
  }

  /**
   * Busca codigo da nota de liquidacao pelo codigo do item
   */
  $oDaoEmpnotaitem    = db_utils::getDao('empnotaitem');
  $sSqlNotaLiquidacao = $oDaoEmpnotaitem->sql_query_file($iCodigoItemNota);
  $rsNotaLiquidacao   = $oDaoEmpnotaitem->sql_record($sSqlNotaLiquidacao);

  if ( $oDaoEmpnotaitem->erro_status == "0" ) {
    throw new DBException("Erro ao buscar item da nota, item não encontrado: $iCodigoItemNota.");
  }

  $iCodigoNotaLiquidacao    = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_codnota;
  $oDaoMaterialEstoqueGrupo = db_utils::getDao('materialestoquegrupo');
  $sWhere                   = "m71_codlanc = {$iCodigoItemEstoque}";
  $sSqlMaterialGrupo        = $oDaoMaterialEstoqueGrupo->sql_query_grupoitem(null, 'm65_sequencial', null, $sWhere);
  $rsMaterialGrupo          = $oDaoMaterialEstoqueGrupo->sql_record($sSqlMaterialGrupo);

  if ($oDaoMaterialEstoqueGrupo->erro_status == "0") {

    $oDadosErro = (object) array('sErroBanco' => $oDaoMaterialEstoqueGrupo->erro_banco);
    throw new Exception (_M(MENSAGENS . "grupo_material_nao_encontrado", $oDadosErro));
  }

  $oDadosEstoqueGrupo = db_utils::fieldsMemory($rsMaterialGrupo, 0);
  $iCodigoGrupo       = $oDadosEstoqueGrupo->m65_sequencial;

  $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oParametros->iNumeroEmpenho);
  $aItensEmpenho      = $oEmpenhoFinanceiro->getItens();

  $oEventoContabil = new EventoContabil($iCodigoDocumento, db_getsession("DB_anousu"));

  $nValorNota = $oParametros->nValorNota;
  $nValorNota = str_replace('.', '', $nValorNota);
  $nValorNota = str_replace(',', '.', $nValorNota);

  $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmpenhoEmLiquidacaoMaterialAlmoxarifado();
  $oLancamentoAuxiliarEmLiquidacao->setGrupoMaterial(new MaterialGrupo($iCodigoGrupo));
  $oLancamentoAuxiliarEmLiquidacao->setObservacaoHistorico($oParametros->sJustificativa);
  $oLancamentoAuxiliarEmLiquidacao->setFavorecido($oEmpenhoFinanceiro->getFornecedor()->getCodigo());
  $oLancamentoAuxiliarEmLiquidacao->setCodigoElemento($aItensEmpenho[0]->getCodigoElemento());
  $oLancamentoAuxiliarEmLiquidacao->setNumeroEmpenho($oParametros->iNumeroEmpenho);
  $oLancamentoAuxiliarEmLiquidacao->setCodigoDotacao($oEmpenhoFinanceiro->getDotacao()->getCodigo());
  $oLancamentoAuxiliarEmLiquidacao->setCodigoNotaLiquidacao($iCodigoNotaLiquidacao);
  $oLancamentoAuxiliarEmLiquidacao->setValorTotal($nValorNota);
  $oLancamentoAuxiliarEmLiquidacao->setSaida( ( $iCodigoDocumento == 211 ? true : false) );

  $oEventoContabil->executaLancamento($oLancamentoAuxiliarEmLiquidacao);

  return true;
}

/**
 * @param      $iEmpnotaitem
 * @param null|MaterialEstoqueItem $oItemEstoque
 *
 * @return MaterialEstoqueItem|null
 * @throws BusinessException
 * @throws DBException
 * @throws Exception
 */
function processarMovimentoEstoque($iEmpnotaitem, MaterialEstoqueItem $oEstoqueItem, $lEstorno = false) {

  /**
   * Pega os dados necessários para criar o movimento no estoque
   */
  $oDaoEmpnotaitembenspendente = new cl_empnotaitembenspendente;
  $sCamposMovimento     = 'm70_codmatmater, m71_quantatend, m71_quant, m71_valor';
  $sWhereDadosMovimento = "e137_empnotaitem = {$iEmpnotaitem}";
  $sSqlDadosMovimento   = $oDaoEmpnotaitembenspendente->sql_query(null, $sCamposMovimento, null, $sWhereDadosMovimento);
  $rsDadosMovimento     = $oDaoEmpnotaitembenspendente->sql_record($sSqlDadosMovimento);
  if (!$rsDadosMovimento || $oDaoEmpnotaitembenspendente->numrows == 0) {
    throw new BusinessException('Não foi possível criar o movimento no estoque.');
  }
  $oDadosMovimento = db_utils::fieldsMemory($rsDadosMovimento, 0);

  $oDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo(db_getsession('DB_coddepto'));
  $oDepartamentoEntrada = $oEstoqueItem->getEstoque()->getDepartamento();

  /**
   * Compara o departamento do usuário logado com o departamento onde foi dada a Entrada da Ordem de Compra
   */
  $sNomeRotina = "a Dispensa de Tombamento";
  if ($lEstorno) {
    $sNomeRotina = "o Estorno da Dispensa de Tombamento";
  }
  if ($oDepartamento->getCodigo() != $oDepartamentoEntrada->getCodigo()) {

    $sMensagem  = "Para efetuar {$sNomeRotina} é necessário estar no departamento ";
    $sMensagem .= "{$oDepartamentoEntrada->getCodigo()} - {$oDepartamentoEntrada->getNomeDepartamento()}, ";
    $sMensagem .= "onde foi feita a Entrada da Ordem de Compra.";
    throw new BusinessException($sMensagem);
  }

  $iDataHoraAgora  = time();

  $oMovimentacao = new MaterialEstoqueMovimentacao(null);
  $oMovimentacao->setUsuario(new UsuarioSistema(db_getsession('DB_id_usuario')));
  $oMovimentacao->setData(new DBDate(date('Y-m-d', $iDataHoraAgora)));
  $oMovimentacao->setHora(date('H:i:s', $iDataHoraAgora));
  $oMovimentacao->setDepartamento($oDepartamento);
  $oMovimentacao->setObservacao("ENTRADA DE M.P. POR DISPENSA DE TOMBAMENTO");
  $oMovimentacao->setMovimento(new TipoMovimentacaoEstoque(25));
  if ($lEstorno) {

    $oMovimentacao->setMovimento(new TipoMovimentacaoEstoque(26));
    $oMovimentacao->setObservacao("ANULAÇÃO DA ENTRADA DE M.P. POR DISPENSA DE TOMBAMENTO");
    $oEstoqueItem->setQuantidadeAtendida($oEstoqueItem->getQuantidade());
    $oEstoqueItem->salvar();
  }
  $oMovimentacao->salvar();

  if ( !$lEstorno) {

    $oMaterial = new MaterialAlmoxarifado($oDadosMovimento->m70_codmatmater);
    $oEstoque = MaterialEstoqueAlmoxarifado::getEstoquePorMaterialDepartamento($oMaterial, $oDepartamento);
    $oEstoque->getCodigo();
    $oItemEstoque = new MaterialEstoqueItem(null);
    $oItemEstoque->setEstoque($oEstoque);
    $oItemEstoque->setData(new DBDate(date('Y-m-d', $iDataHoraAgora)));
    $oItemEstoque->setQuantidade($oEstoqueItem->getQuantidade());
    $oItemEstoque->setValor($oEstoqueItem->getValor());
    $oItemEstoque->setQuantidadeAtendida(0);
    $oItemEstoque->setServico(false);
    $oItemEstoque->salvar();
    $oEstoqueItem = $oItemEstoque;
  }
  MaterialEstoqueItem::vincularMovimentacaoComItem($oEstoqueItem, $oMovimentacao, $oEstoqueItem->getQuantidade());
  return $oEstoqueItem;
}
