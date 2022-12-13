<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/ordemCompra.model.php"));
require_once(modification("model/compras/ConfiguracaoDesdobramentoPatrimonio.model.php"));
require_once(modification("model/contabilidade/GrupoContaOrcamento.model.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("model/empenho/EmpenhoFinanceiroItem.model.php"));
require_once(modification("model/empenho/EmpenhoFinanceiro.model.php"));
require_once(modification("model/configuracao/DBDepartamento.model.php"));
require_once(modification("model/configuracao/Instituicao.model.php"));
require_once(modification("model/Dotacao.model.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("model/MaterialCompras.model.php"));
require_once(modification("model/estoque/MaterialGrupo.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));

db_app::import('contabilidade.*');
db_app::import('contabilidade.lancamento.*');
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("configuracao.DBRegistry");

$post         = db_utils::postmemory($_POST);
$json         = new services_json();
$objJson      = $json->decode(str_replace("\\","",$_POST["json"]));
$oOrdemCompra = new ordemCompra($objJson->m51_codordem);
$method       = $objJson->method;
$oOrdemCompra->setEncodeOn();
if ($method == "getDados") {

  echo $oOrdemCompra->ordem2Json($objJson->e69_codnota);
} else if ($method == "anularEntradaOrdem") {


  try {
    $oOrdemCompra->anularEntradaNota($objJson->e69_codnota);
    $status   = 1;
    $mensagem = "Entrada de Ordem de Compra anulada com Sucesso.";
    $load     = 1;

  } catch (BusinessException $oErro) {

    $status   = 2;
    $mensagem = $oErro->getMessage();
    $load     = 2;

  } catch (Exception $eErro) {

  	$status   = 2;
  	$mensagem = $eErro->getMessage();
  	$load     = 2;
  }


  echo $json->encode(array("mensagem" => urlencode($mensagem), "status" => $status, "load" => $load ));
} else if ($method == "getOrdem"){

   $oOrdemCompra->setEncodeOn();
   $oOrdemCompra->getDados();
   if ($oOrdemCompra->getItensSaldo()){
      echo $json->encode($oOrdemCompra->dadosOrdem);
   }
} else if ($method == "anularOrdem"){

   $oOrdemCompra->anularOrdem($objJson->itensAnulados, db_stdclass::normalizeStringJsonEscapeString($objJson->sMotivo),$objJson->empanula);
   $mensagem = '';
   $status   = 1;
   if ($oOrdemCompra->lSqlErro){

     $mensagem = urlencode($oOrdemCompra->sErroMsg);
     $status   = 2;
   }
   echo $json->encode(array("mensagem" => $mensagem, "status" => $status));
} else if ($method == "getInfoEntrada") {

  try {

    $oOrdemCompra->destroySession();
    $oOrdemCompra->getInfoEntrada();
    $oOrdemCompra->dadosOrdem->status = 1;
    $oOrdemCompra->dadosOrdem->itens  = $oOrdemCompra->getDadosEntrada();

    $oListaClassificacaoCredor = $oOrdemCompra->getEmpenhoFinanceiro()->getListaClassificacaoCredor();
    $oOrdemCompra->dadosOrdem->iClassificacao = '';
    $oOrdemCompra->dadosOrdem->sClassificacao = '';

    $sDataVencimento = '';
    if ($oListaClassificacaoCredor) {

      $oOrdemCompra->dadosOrdem->iClassificacao = $oListaClassificacaoCredor->getCodigo();
      $oOrdemCompra->dadosOrdem->sClassificacao = urlencode($oListaClassificacaoCredor->getDescricao());
    }
    if (!empty($oListaClassificacaoCredor)) {

      $sData           = date("d/m/Y", db_getsession("DB_datausu"));
      $oDataVencimento = $oListaClassificacaoCredor->getDataVencimentoPorData(new DBDate($sData));
      $sDataVencimento = $oDataVencimento->getDate(DBDate::DATA_PTBR);
    }
    $oOrdemCompra->dadosOrdem->sDataVencimento = urlencode($sDataVencimento);

    echo $json->encode($oOrdemCompra->dadosOrdem);

  } catch (Exception $eErro) {

    echo $json->encode(array("mensagem" => urlencode($eErro->getMessage()),
                             "status"   => 2
                            )
                      );

  }

} else if ($method == "getInfoItem") {

  try {
    echo $json->encode($oOrdemCompra->getInfoItem($objJson->iCodLanc, $objJson->iIndice));
  } catch (Exception $oErro) {

    echo $json->encode(
      array(
        "mensagem" => urlencode($oErro->getMessage()),
        "status"   => 2
      )
    );
  }

} else if ($method == "saveMaterial") {

  try {

     $oOrdemCompra->saveMaterial($objJson->iCodLanc, $objJson->oMaterial);
     echo $json->encode(array("mensagem"  => "ok",
                              "status"    => 1,
                              "lFraciona" => $objJson->oMaterial->fraciona,
                              "iCodLanc"  => $objJson->iCodLanc));
  } catch (Exception $eErro){

     echo $json->encode(array("mensagem" => urlEncode($eErro->getMessage()),
                              "status"   => 2,
                              "lfraciona" => false,
                            )
                       );
  }
} else if ($method == "getDadosEntrada") {

  $aRetorno = array("aItens" => $oOrdemCompra->getDadosEntrada(),"marcar" => $objJson->marcar);
  echo $json->encode($aRetorno);

} else if ($method == "cancelarFracionamento") {

   if ($oOrdemCompra->cancelarFracionamento($objJson->iCodLanc, $objJson->iIndice)) {

     echo $json->encode($oOrdemCompra->getDadosEntrada());

   }

}  else if ($method == "desmarcarItem") {

  $_SESSION["matordem{$objJson->m51_codordem}"][$objJson->iCodLanc][$objJson->iIndice]->checked = "";

} else if ($method == "confirmarEntrada") {

  try {

    db_inicio_transacao();

    /**
     validação realizada pela retaguarda, para verificar se algum item esta sem vinculo com grupo e subgrupo.
     */
    $sComprasSemMaterial = '';

    foreach ($_SESSION["matordem{$objJson->m51_codordem}"] as $iCodLanc => $oItem) {

      foreach ($oItem as $iIndice => $oItemFilho) {
        if ($_SESSION["matordem{$objJson->m51_codordem}"][$iCodLanc][$iIndice]->m63_codmatmater == ''){
          $sComprasSemMaterial .= "\n".$_SESSION["matordem{$objJson->m51_codordem}"][$iCodLanc][$iIndice]->pc01_codmater .' - '.
              $_SESSION["matordem{$objJson->m51_codordem}"][$iCodLanc][$iIndice]->pc01_descrmater;
        }
      }
    }
    if (!empty($sComprasSemMaterial)){
      throw new Exception("Itens sem vínculo com Material de Entrada:\n" . urldecode($sComprasSemMaterial) . "\n ");
    }

    $sObservacao       = addslashes(db_stdClass::normalizeStringJsonEscapeString($objJson->sObs));
    $sNota             = addslashes(db_stdClass::normalizeStringJsonEscapeString($objJson->sNumero));
    $sNumeroProcesso   = addslashes(db_stdClass::normalizeStringJsonEscapeString($objJson->e04_numeroprocesso));
    $sLocalRecebimento = addslashes(db_stdClass::normalizeStringJsonEscapeString($objJson->sLocalRecebimento));

    $oOrdemDeCompra      = new OrdemDeCompra($objJson->m51_codordem);
    $oEmpenhoFinanceiro  = $oOrdemDeCompra->getEmpenhoFinanceiro();



    $oDataVencimento  = null;
    if (!empty($objJson->dtVencimento)) {
      $oDataVencimento = new DBDate($objJson->dtVencimento);
    }

    $oListaClassificacaoCredor = $oEmpenhoFinanceiro->getListaClassificacaoCredor();
    if ($oListaClassificacaoCredor) {

      $oListaClassificacaoCredor->validarParametros($objJson->dtDataNota,
                                                    $objJson->dtRecebeNota,
                                                    $objJson->dtVencimento,
                                                    $sLocalRecebimento);

      $oDataRecebimento = null;
      if (!empty($objJson->dtRecebeNota)) {
        $oDataRecebimento = new DBDate($objJson->dtRecebeNota);
      }
      $sMensagem = "data_vencimento_invalida_ordem_compra";
      $oListaClassificacaoCredor->validarDatas(new DBDate($objJson->dtDataNota),
                                               $oDataRecebimento,
                                               $oDataVencimento,
                                               $sMensagem);
    }

    if (empty($sNota)) {
      $sNota = "S/N";
    }
    $oOrdemCompra->confirmaEntrada( $sNota,
                                    $objJson->dtDataNota,
                                    $objJson->dtRecebeNota,
                                    $objJson->nValorNota,
                                    $objJson->aItens,
                                    $objJson->oInfoNota,
                                    $sObservacao,
                                    $sNumeroProcesso,
                                    $oDataVencimento,
                                    $sLocalRecebimento
                                  );

    /** [PAD/RS] Vinculo Numero de Serie */

    db_fim_transacao(false);
    echo $json->encode(array("mensagem" => "Entrada da ordem de compra efetuada com sucesso.", "status" => 1, "erro" => false));
  } catch (Exception $eError) {

    db_fim_transacao(true);
    echo $json->encode(array("mensagem" => urlencode($eError->getMessage()), "status" => 2, "erro" => true));

  }
} else if ($method == "marcarItensSession") {

  if ($objJson->lMarcar) {
    $sChecked = "checked";
  } else {
    $sChecked = "";
  }
  foreach ($_SESSION["matordem{$objJson->m51_codordem}"] as $iCodLanc => $oItem) {

    foreach ($oItem as $iIndice => $oItemFilho) {
      echo $_SESSION["matordem{$objJson->m51_codordem}"][$iCodLanc][$iIndice]->checked."\n";
      $_SESSION["matordem{$objJson->m51_codordem}"][$iCodLanc][$iIndice]->checked = $sChecked;
    }
  }
} else if ($method == "verificaBensBaixado") {

  $aDocumentos = array(200 => 7,
  		                 201 => 7,
  		                 208 => 9,
  		                 209 => 9,
  		                 210 => 8,
  		                 211 => 8);
  $status           = 1;
  $aGrupos          = array();
  $iCodigoNota      = $objJson->iCodigoNota;
  $iInstituicao     = db_getsession('DB_instit');
  $iGrupoEmpenho    = 0;
  $oDaoConLanCamEmp = db_utils::getDao("conlancamemp");

  $sCamposConLanCamEmp  = " c71_coddoc, ";
  $sCamposConLanCamEmp .= " e60_numemp  ";
  $sWhereConLanCamEmp   = "     conlancamnota.c66_codnota = {$iCodigoNota}                ";
  $sWhereConLanCamEmp  .= " and conlancamdoc.c71_coddoc in (200, 201, 208, 209, 210, 211) ";
  $sSqlConLanCamEmp     = $oDaoConLanCamEmp->sql_query_verificaBensBaixados(null, $sCamposConLanCamEmp, null, $sWhereConLanCamEmp);
  $rsConLanCamEmp       = $oDaoConLanCamEmp->sql_record($sSqlConLanCamEmp);

  if ($oDaoConLanCamEmp->numrows > 0) {

    $iDocumento         = db_utils::fieldsMemory($rsConLanCamEmp, 0)->c71_coddoc;
    $oDadosConLanCamEmp = db_utils::fieldsMemory($rsConLanCamEmp, 0);
    $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oDadosConLanCamEmp->e60_numemp);
    $oContaOrcamento    = new ContaOrcamento($oEmpenhoFinanceiro->getDesdobramentoEmpenho(),
                                             $oEmpenhoFinanceiro->getAnoUso(),
                                             null,
                                             $oEmpenhoFinanceiro->getInstituicao());

    if ($oContaOrcamento->getGruposContas() !== false) {

      $aGrupos       = $oContaOrcamento->getGruposContas();
      $iGrupoEmpenho = $aGrupos[0]->c20_sequencial;
    }

    $iGrupoDeveEstar    = $aDocumentos[$iDocumento];
    if ( ($iGrupoDeveEstar != $iGrupoEmpenho)  && ($iGrupoEmpenho != 9) ) {

      $oDaoConGrupo = db_utils::getDao("congrupo");
      $sSQLConGrupo = $oDaoConGrupo->sql_query_file($iGrupoDeveEstar);
      $rsConGrupo   = $oDaoConGrupo->sql_record($sSQLConGrupo);
      $oConGrupo    = db_utils::fieldsMemory($rsConGrupo, 0);


      $sMensagemErro  = "A operação não poderá ser realizada porque esta ordem de compra pertence ao empenho ". $oEmpenhoFinanceiro->getCodigo() . "/" . $oEmpenhoFinanceiro->getAnoUso();
      $sMensagemErro .= " e sua conta de despesa não esta configurada no grupo de contas do plano orçamentário de origem, ";
      $sMensagemErro .= "o que compromete o fechamento contábil. Solicite ao responsável pela ";
      $sMensagemErro .= "Contabilidade a inclusão da conta " .  $oContaOrcamento->getCodigoConta() . " - " . $oContaOrcamento->getDescricao() ;
      $sMensagemErro .= " no grupo de contas {$oConGrupo->c20_sequencial} - {$oConGrupo->c20_descr} ";

      $status   = '3';
      echo $json->encode(array("status" => $status, "sMensagem" => urlencode($sMensagemErro)));
      return false;
    }
  }

  if ($oOrdemCompra->getBensAtivoNota($objJson->iCodigoNota) != false) {
    $status   = 2;
  }

  if ($oOrdemCompra->houveDispensaTombamentoNoPatrimonio($objJson->iCodigoNota)) {
    $status   = 4;
  }

  $oNotaLiquidacao = new NotaLiquidacao($objJson->iCodigoNota);
  if ($oNotaLiquidacao->getValorLiquidado() > 0) {
    $status = 5;
  }

  echo $json->encode(array("status" => $status, "iCodigoNota" => $iCodigoNota));

}else if ($method == "verificaNota") {

  $status        = 0;
  $sEmpenho      = "";
  $sNota         = addslashes(db_stdClass::normalizeStringJson($objJson->sNota));
  $iCgmFornecedor= $objJson->iCgmFornecedor;
  $oDaoEmpNota   = db_utils::getDao("empnota");

  $iInstituicao  = db_getsession('DB_instit');
  $sWhereEmpNota = "e69_numero ilike '{$sNota}' and e60_instit = {$iInstituicao} and e60_numcgm = {$iCgmFornecedor}";
  $sSqlEmpNota   = $oDaoEmpNota->sql_query(null,
  		                                     "distinct e60_codemp, e69_anousu",
  		                                     "e60_codemp, e69_anousu",
  		                                     $sWhereEmpNota);
  $rsEmpNota     = $oDaoEmpNota->sql_record($sSqlEmpNota);

  if ($oDaoEmpNota->numrows > 0) {

		$status   = 1;
		$aEmpenhos = array();
    foreach (db_utils::getCollectionByRecord($rsEmpNota) as $oEmpNota) {
    	$aEmpenhos[] = "\n" . $oEmpNota->e60_codemp . '/' . $oEmpNota->e69_anousu;
    }
    $sEmpenho    = implode(", ", $aEmpenhos);

  }
  echo $json->encode(array("status" => $status, "sEmpenho" => $sEmpenho));
} else if ($method == 'anularEntradaOrdemEmpenhoMaterialPermanente') {

  try {

    db_inicio_transacao();

    $oDaoOrdemItemOC = new cl_matestoqueitemoc();
    $aWhere = array(
      "m74_codempnota = {$objJson->e69_codnota}",
      "m52_codordem = {$objJson->m51_codordem}"
    );
    $sSqlBuscaItens   = $oDaoOrdemItemOC->sql_query_nota_ordem('matestoqueitemoc.*', implode(" and ",$aWhere));
    $rsBuscaItens     = $oDaoOrdemItemOC->sql_record($sSqlBuscaItens);
    if ($oDaoOrdemItemOC->erro_status == "0") {
      throw new Exception("Ocorreu um erro na busca dos itens da ordem de compra.");
    }

    for ($iRow = 0; $iRow < $oDaoOrdemItemOC->numrows; $iRow++) {

      $oStdDadosItem = db_utils::fieldsMemory($rsBuscaItens, $iRow);
      $oStdDadosItem->m73_cancelado = $oStdDadosItem->m73_cancelado == "t";
      if ($oStdDadosItem->m73_cancelado) {
        throw new Exception("Ordem de compra já encontra-se cancelada.");
      }

      $oDaoBensDispensaTombamento = new cl_bensdispensatombamento();
      $sSqlBuscaTombamento = $oDaoBensDispensaTombamento->sql_query_file(null, "*", null, "e139_matestoqueitem = {$oStdDadosItem->m73_codmatestoqueitem}");
      $rsBuscaTombamento   = db_query($sSqlBuscaTombamento);
      if (pg_num_rows($rsBuscaTombamento) > 0) {

        $sMensagem = "Alguns itens da nota encontram-se com dispensa de tombamento no patrimonio. É necessário estornar para que seja possível anular a entrada da ordem de compra.";
        throw new Exception($sMensagem);
      }

      $oDaoOrdemItemOCAlterar = new cl_matestoqueitemoc();
      $oDaoOrdemItemOCAlterar->m73_cancelado         = 'true';
      $oDaoOrdemItemOCAlterar->m73_codmatordemitem   = $oStdDadosItem->m73_codmatordemitem;
      $oDaoOrdemItemOCAlterar->m73_codmatestoqueitem = $oStdDadosItem->m73_codmatestoqueitem;
      $oDaoOrdemItemOCAlterar->alterar($oStdDadosItem->m73_codmatestoqueitem, $oStdDadosItem->m73_codmatordemitem);
      if ($oDaoOrdemItemOCAlterar->erro_status == "0") {
        throw new Exception("Não foi possível alterar a situação da entrada da nota.");
      }
    }

    $oDaoBensPendente = new cl_empnotaitembenspendente();
    $oDaoBensPendente->excluir(null, "e137_empnotaitem in (select e72_sequencial from empnotaitem where e72_codnota = {$objJson->e69_codnota})");
    if ($oDaoBensPendente->erro_status == "0") {
      throw new Exception("Não foi possível excluir a pendência do bem no módulo patrimonial.");
    }

    $oDaoEmpNotaEle = new cl_empnotaele();
    $sSqlBuscaNotaEle = $oDaoEmpNotaEle->sql_query_file($objJson->e69_codnota, null, "*");
    $rsBuscaValorNota = $oDaoEmpNotaEle->sql_record($sSqlBuscaNotaEle);
    if (!$rsBuscaValorNota) {
      throw new Exception("Não foi possível buscar os dados financeiros da nota de liquidação.");
    }

    $oStdNota = db_utils::fieldsMemory($rsBuscaValorNota, 0);
    if ($oStdNota->e70_vlrliq > 0) {
      throw new Exception("Nota de Liquidação {$objJson->e69_codnota} com valor já liquidado. Procedimento abortado.");
    }

    $oDaoEmpNotaEle->e70_codnota = $oStdNota->e70_codnota;
    $oDaoEmpNotaEle->e70_codele  = $oStdNota->e70_codele;
    $oDaoEmpNotaEle->e70_vlranu  = $oStdNota->e70_valor;
    $oDaoEmpNotaEle->alterar($oStdNota->e70_codnota);
    if ($oDaoEmpNotaEle->erro_status == "0") {
      throw new Exception("Não foi possível anular o valor da nota de liquidação gerada.");
    }


    $mensagem = "Entrada da Ordem de Compra anulada com sucesso.";
    $status   = 1;
    $load     = 1;
    db_fim_transacao(false);


  } catch (Exception $e) {

    db_fim_transacao(true);
    $status   = 2;
    $load     = 2;
    $mensagem = $e->getMessage();
  }

  echo $json->encode(array("mensagem" => urlencode($mensagem), "status" => $status, "load" => $load ));
}
