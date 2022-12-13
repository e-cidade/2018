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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

define("FONTE_MSG", "patrimonial.licitacao.lic4_credenciamentofornecedorRPC.");

$oParam             = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "buscarDadosLicitacao":

      if ( empty($oParam->iLicitacao) ) {
        throw new ParameterException( _M(FONTE_MSG . "licitacao_nao_informado") );
      }

      $oRetorno->aFornecedores   = array();
      $oRetorno->aItensLicitacao = array();

      $oLicitacao    = new licitacao($oParam->iLicitacao);
      $aFornecedores = $oLicitacao->getFornecedor();

      $oRetorno->iOrcamento = null;
      foreach ($aFornecedores as $oDadoFornecedor) {

        $oFornecedor              = new stdClass();
        $oFornecedor->iCgm        = $oDadoFornecedor->z01_numcgm;
        $oFornecedor->sNome       = $oDadoFornecedor->z01_nome;
        $oFornecedor->iFornecedor = $oDadoFornecedor->pc21_orcamforne;

        $oFornecedor->aItens = licitacao::getItensPorFornecedor(array($oParam->iLicitacao), $oDadoFornecedor->z01_numcgm, false, false);

        $oRetorno->iOrcamento      = $oDadoFornecedor->pc21_codorc;
        $oRetorno->aFornecedores[] = $oFornecedor;
      }

      $aItens = $oLicitacao->getItens();

      foreach ($aItens as $oItemLicitacao) {

        $oSolicitacao = $oItemLicitacao->getItemSolicitacao();

        $oItem                  = new stdClass();
        $oItem->iLicLicitem     = $oItemLicitacao->getCodigo();
        $oItem->sItem           = urldecode( $oSolicitacao->getDescricaoMaterial() );
        $oItem->iQuantidadeItem = $oSolicitacao->getQuantidade();
        $oItem->nVlrUnitario    = $oSolicitacao->getValorUnitario();
        $oItem->nVlrTotal       = $oSolicitacao->getValorTotal();
        $oItem->sObservacao     = "";

        $oRetorno->aItensLicitacao[] = $oItem;
      }

      break;

    case "adicionarFornecedor" :

      if ( empty($oParam->iLicitacao) ) {
        throw new ParameterException( _M(FONTE_MSG . "licitacao_nao_informado") );
      }
      if ( empty($oParam->iCgm) ) {
        throw new ParameterException( _M(FONTE_MSG . "cgm_nao_informado") );
      }

      if ( empty($oParam->iOrcamento) ) {
        $oParam = incluirOrcamento($oParam);
      }

      $oDaoPcOrcamForne = new cl_pcorcamforne();
      $sWhere           = " pc21_codorc = {$oParam->iOrcamento} and pc21_numcgm = {$oParam->iCgm}";
      $sSqlOrcamForn    = $oDaoPcOrcamForne->sql_query_file(null, "pc21_orcamforne", null, $sWhere );
      $rsOrcamForn      = db_query($sSqlOrcamForn);

      if (!$rsOrcamForn) {
        throw new Exception("Error Processing Request", 1);
      }

      if (pg_num_rows($rsOrcamForn) == 0) {

        $oDaoPcOrcamForne->pc21_orcamforne = null;
        $oDaoPcOrcamForne->pc21_codorc     = $oParam->iOrcamento;
        $oDaoPcOrcamForne->pc21_numcgm     = $oParam->iCgm;
        $oDaoPcOrcamForne->pc21_importado  = 'false';
        $oDaoPcOrcamForne->pc21_prazoent   = 'null';
        $oDaoPcOrcamForne->pc21_validadorc = 'null';
        $oDaoPcOrcamForne->incluir(null);

        if ( $oDaoPcOrcamForne->erro_status == 0 ) {
          throw new Exception( _M( FONTE_MSG . "erro_incluir_fornecedor" ) );
        }

        $oParam->iFornecedor = $oDaoPcOrcamForne->pc21_orcamforne;

        $oDaoPcOrcamForneLic                            = new cl_pcorcamfornelic();
        $oDaoPcOrcamForneLic->pc31_orcamforne           =

        $oDaoPcOrcamForneLic->pc31_nomeretira           = '';
        $oDaoPcOrcamForneLic->pc31_dtretira             = 'null';
        $oDaoPcOrcamForneLic->pc31_horaretira           = 'null';
        $oDaoPcOrcamForneLic->pc31_liclicitatipoempresa = 1;
        $oDaoPcOrcamForneLic->pc31_tipocondicao         = 1;

        $oDaoPcOrcamForneLic->incluir($oParam->iFornecedor);

        if ( $oDaoPcOrcamForneLic->erro_status == 0 ) {
          throw new Exception( $oDaoPcOrcamForneLic->erro_msg);
        }
      } else {
        $oParam->iFornecedor = db_utils::fieldsMemory($rsOrcamForn, 0)->pc21_orcamforne;
      }

      $oRetorno->iOrcamento               = $oParam->iOrcamento;
      $oRetorno->oFornecedor              = new stdClass();
      $oRetorno->oFornecedor->iCgm        = $oParam->iCgm;
      $oRetorno->oFornecedor->sNome       = $oParam->sNome;
      $oRetorno->oFornecedor->iFornecedor = $oParam->iFornecedor;
      $oRetorno->oFornecedor->aItens      = licitacao::getItensPorFornecedor(array($oParam->iLicitacao), $oParam->iCgm, false, false);
      break;

    case "removerFornecedor":

      if ( empty($oParam->oFornecedor) or empty($oParam->oFornecedor->iFornecedor) ) {
        throw new ParameterException( _M(FONTE_MSG . "fornecedor_nao_infomado") );
      }

      if ( !permiteRemoverFornecedor($oParam->iLicitacao, $oParam->oFornecedor->iCgm)) {
        throw new Exception( _M(FONTE_MSG . "valida_exclusao_profissional") );
      }

      removerPropostas($oParam->oFornecedor->iFornecedor);

      $oDaoPcOrcamForneLic  = new cl_pcorcamfornelic();
      $oDaoPcOrcamForneLic->excluir($oParam->oFornecedor->iFornecedor);

      if($oDaoPcOrcamForneLic->erro_status == 0){
        throw new Exception(_M( FONTE_MSG . "erro_excluir_fornecedor" ));
      }

      $oDaoPcOrcamForne = new cl_pcorcamforne();
      $oDaoPcOrcamForne->excluir($oParam->oFornecedor->iFornecedor);

      if($oDaoPcOrcamForne->erro_status == 0){
        throw new Exception(_M( FONTE_MSG . "erro_excluir_fornecedor" ));
      }

      $oRetorno->sMessage = _M(FONTE_MSG . "excluido_sucesso");
      break;

    case "vincularItemFornecedor" :

      if ( empty($oParam->aItens) ) {
        throw new ParameterException( _M(FONTE_MSG . "itens_nao_informado") );
      }

      $oDaoPcOrcamVal  = new cl_pcorcamval();
      $oDaopcOrcamJulg = new cl_pcorcamjulg();

      removerPropostas($oParam->iFornecedor);

      $oDaoPcOrcamItemLic = new cl_pcorcamitemlic();
      foreach ($oParam->aItens as $oItem) {

        $sWhere           = "pc26_liclicitem = ". $oItem->iLicLicitem;
        $sSqlDadosItemLic = $oDaoPcOrcamItemLic->sql_query_file(null, 'pc26_orcamitem', null, $sWhere);
        $rsDadosItemLic   = db_query($sSqlDadosItemLic);

        if (pg_num_rows($rsDadosItemLic) > 0) {
          $oDadosOrcamItem = db_utils::fieldsMemory($rsDadosItemLic, 0);
        }

        $oDaoPcOrcamVal->pc23_orcamforne = $oParam->iFornecedor;
        $oDaoPcOrcamVal->pc23_orcamitem  = $oDadosOrcamItem->pc26_orcamitem;
        $oDaoPcOrcamVal->pc23_valor      = $oItem->nVlrTotal;
        $oDaoPcOrcamVal->pc23_quant      = $oItem->iQuantidadeItem;
        $oDaoPcOrcamVal->pc23_vlrun      = $oItem->nVlrUnitario;
        $oDaoPcOrcamVal->pc23_percentualdesconto = 0;
        $oDaoPcOrcamVal->incluir($oParam->iFornecedor, $oDadosOrcamItem->pc26_orcamitem);

        if ( $oDaoPcOrcamVal->erro_status == 0 ) {
          throw new Exception( $oDaoPcOrcamVal->erro_msg);
        }

        $oDaopcOrcamJulg->pc24_orcamforne = $oParam->iFornecedor;
        $oDaopcOrcamJulg->pc24_orcamitem  = $oDadosOrcamItem->pc26_orcamitem;
        $oDaopcOrcamJulg->pc24_pontuacao  = 1;
        $oDaopcOrcamJulg->incluir( $oDadosOrcamItem->pc26_orcamitem, $oParam->iFornecedor);

        if ( $oDaopcOrcamJulg->erro_status == 0 ) {
          throw new Exception( $oDaopcOrcamJulg->erro_msg);
        }
      }

      $oDaoLicitacao                  = new cl_liclicita();
      $oDaoLicitacao->l20_codigo      = $oParam->iLicitacao;
      $oDaoLicitacao->l20_licsituacao = 1;
      $oDaoLicitacao->alterar($oParam->iLicitacao);
      if ( $oDaoLicitacao->erro_status == 0 ) {
        throw new Exception( _M(FONTE_MSG . "erro_alterar_licitacao") );

      }



      $oRetorno->sMessage = _M( FONTE_MSG . "itens_salvo" );
      $oRetorno->aItens   = licitacao::getItensPorFornecedor(array($oParam->iLicitacao), $oParam->iCgm, false, false);

      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = $eErro->getMessage();
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);

/**
 * Inclui um orçamento para a licitação
 * @param  stdClass $oParam
 * @return stdClass atualizada com o código do orçamento
 */
function incluirOrcamento($oParam) {

  $oLicitacao = new licitacao ($oParam->iLicitacao);
  $aItens     = $oLicitacao->getItens();

  if ( empty($aItens) ) {
    throw new Exception( _M( FONTE_MSG . "licitacao_sem_itens" ) );
  }

  $oDaoOrcamento = new cl_pcorcam();
  $oDaoOrcamento->pc20_codorc            = null;
  $oDaoOrcamento->pc20_dtate             = $oLicitacao->getDataAbertura()->getDate();
  $oDaoOrcamento->pc20_hrate             = $oLicitacao->getHoraAbertura();
  $oDaoOrcamento->pc20_obs               = "";
  $oDaoOrcamento->pc20_prazoentrega      = '0';
  $oDaoOrcamento->pc20_validadeorcamento = '0';
  $oDaoOrcamento->pc20_cotacaoprevia     = '0';
  $oDaoOrcamento->incluir(null);

  if ( $oDaoOrcamento->erro_status == 0 ) {
    throw new Exception( _M( FONTE_MSG . "erro_incluir_orcamento" ) );
  }

  $oParam->iOrcamento = $oDaoOrcamento->pc20_codorc;

  foreach ($aItens as $oItemLicitacao) {

    $oDaoPcOrcamItem              = new cl_pcorcamitem();
    $oDaoPcOrcamItem->pc22_codorc = $oParam->iOrcamento;
    $oDaoPcOrcamItem->incluir(null);

    if ( $oDaoPcOrcamItem->erro_status == 0 ) {
      throw new Exception( _M( FONTE_MSG . "erro_incluir_orcamento_item" ) );
    }

    $oDaoPcOrcamItemlic                  = new cl_pcorcamitemlic();
    $oDaoPcOrcamItemlic->pc26_orcamitem  = $oDaoPcOrcamItem->pc22_orcamitem;
    $oDaoPcOrcamItemlic->pc26_liclicitem = $oItemLicitacao->getCodigo();
    $oDaoPcOrcamItemlic->incluir($oDaoPcOrcamItem->pc22_orcamitem);

    if ( $oDaoPcOrcamItemlic->erro_status == 0 ) {
      throw new Exception( _M( FONTE_MSG . "erro_vincular_item_orcamento_licitacao" ) );
    }
  }

  return $oParam;
}

/**
 * Exclui o julgamento e as propostas do fornecedor
 * @param  integer $iFornecedor
 */
function removerPropostas ($iFornecedor) {

  $oDaoPcOrcamVal  = new cl_pcorcamval();
  $oDaopcOrcamJulg = new cl_pcorcamjulg();
  $oDaopcOrcamJulg->excluir(null, null, " pc24_orcamforne = {$iFornecedor} ");
  if ( $oDaopcOrcamJulg->erro_status == 0 ) {
    throw new Exception( _M(FONTE_MSG . "atualizar_fornecedor") );
  }

  $oDaoPcOrcamVal->excluir(null, null, " pc23_orcamforne = {$iFornecedor} ");
  if ( $oDaopcOrcamJulg->erro_status == 0 ) {
    throw new Exception( _M(FONTE_MSG . "atualizar_fornecedor") );
  }
}

/**
 * Permite a exclusao de Fornecedores apenas se nao tiver contrato ou autorização de empenho
 * @param  integer $iLicitacao  código da licitacao
 * @param  integer $iCgm        código do cgm do forecedor
 * @return boolean
 */
function permiteRemoverFornecedor($iLicitacao, $iCgm) {

  $aItens      = licitacao::getItensPorFornecedor(array($iLicitacao), $iCgm, false, false);
  $aCodLiclicitem = array();

  if (empty($aItens)) {
    return true;
  }

  foreach ($aItens as $oItem) {
    $aCodLiclicitem[] = $oItem->codigo;
  }

  $sCodLicLicitemfiltro = implode(",", $aCodLiclicitem);

  $sSQL  = " SELECT 1 ";
  $sSQL .= "  FROM  acordoliclicitem";
  $sSQL .= "  JOIN liclicitem ON liclicitem.l21_codigo = acordoliclicitem.ac24_liclicitem";
  $sSQL .= " WHERE liclicitem.l21_codigo IN ({$sCodLicLicitemfiltro})";
  $sSQL .= " UNION ";
  $sSQL .= " SELECT 1";
  $sSQL .= "  FROM liclicitem";
  $sSQL .= "  JOIN pcprocitem           ON pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem";
  $sSQL .= "  JOIN empautitempcprocitem ON empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
  $sSQL .= "  JOIN empautitem           ON empautitem.e55_autori               = empautitempcprocitem.e73_autori";
  $sSQL .= "                           AND empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
  $sSQL .= " WHERE liclicitem.l21_codigo IN ({$sCodLicLicitemfiltro})";

  $rsResource = db_query($sSQL);

  if (pg_num_rows($rsResource) == 0) {
    return true;
  }

  return false;
}
