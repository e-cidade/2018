<?
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/ordemCompra.model.php");
require_once ("model/compras/ConfiguracaoDesdobramentoPatrimonio.model.php");
require_once ("model/contabilidade/GrupoContaOrcamento.model.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("model/empenho/EmpenhoFinanceiroItem.model.php");
require_once ("model/empenho/EmpenhoFinanceiro.model.php");
require_once ("model/configuracao/DBDepartamento.model.php");
require_once ("model/configuracao/Instituicao.model.php");
require_once ("model/Dotacao.model.php");
require_once ("model/CgmFactory.model.php");
require_once ("model/MaterialCompras.model.php");
require_once ("model/estoque/MaterialGrupo.model.php");
require_once ("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");
require_once ("model/contabilidade/planoconta/ContaPlano.model.php");

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
$oORdemCompra = new ordemCompra($objJson->m51_codordem);
$method       = $objJson->method;
$oORdemCompra->setEncodeOn();
if ($method == "getDados") {

  echo $oORdemCompra->ordem2Json($objJson->e69_codnota);
} else if ($method == "anularEntradaOrdem") {


  try {

    $oORdemCompra->anularEntradaNota($objJson->e69_codnota);

    $status   = 1;
    $mensagem = "Entrada de Ordem de Compra anulada com Sucesso.";
    $load     = 1;

  } catch (BusinessException $oErro) {

    $status   = 2;
    $mensagem = $oErro->getMessage();
    $load     = 2;

  } catch (Exception $eErro) {

  	$status   = 2;
  	$mensagem = $oErro->getMessage();
  	$load     = 2;
  }


  echo $json->encode(array("mensagem" => urlencode($mensagem), "status" => $status, "load" => $load ));
} else if ($method == "getOrdem"){

   $oORdemCompra->setEncodeOn();
   $oORdemCompra->getDados();
   if ($oORdemCompra->getItensSaldo()){
      echo $json->encode($oORdemCompra->dadosOrdem);
   }
} else if ($method == "anularOrdem"){

   $oORdemCompra->anularOrdem($objJson->itensAnulados, $objJson->sMotivo,$objJson->empanula);
   $mensagem = '';
   $status   = 1;
   if ($oORdemCompra->lSqlErro){

     $mensagem = urlencode($oORdemCompra->sErroMsg);
     $status   = 2;
   }
   echo $json->encode(array("mensagem" => $mensagem, "status" => $status));
} else if ($method == "getInfoEntrada") {

  try {

    $oORdemCompra->destroySession();
    $oORdemCompra->getInfoEntrada();
    $oORdemCompra->dadosOrdem->status  = 1;
    $oORdemCompra->dadosOrdem->itens   = $oORdemCompra->getDadosEntrada();
    echo $json->encode($oORdemCompra->dadosOrdem);

  } catch (Exception $eErro) {

    echo $json->encode(array("mensagem" => urlencode($eErro->getMessage()),
                             "status"   => 2
                            )
                      );

  }

} else if ($method == "getInfoItem") {

  echo $json->encode($oORdemCompra->getInfoItem($objJson->iCodLanc, $objJson->iIndice));

} else if ($method == "saveMaterial") {

  try {

     $oORdemCompra->saveMaterial($objJson->iCodLanc, $objJson->oMaterial);
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

  $aRetorno = array("aItens" => $oORdemCompra->getDadosEntrada(),"marcar" => $objJson->marcar);
  echo $json->encode($aRetorno);

} else if ($method == "cancelarFracionamento") {

   if ($oORdemCompra->cancelarFracionamento($objJson->iCodLanc, $objJson->iIndice)) {

     echo $json->encode($oORdemCompra->getDadosEntrada());

   }

}  else if ($method == "desmarcarItem") {

  $_SESSION["matordem{$objJson->m51_codordem}"][$objJson->iCodLanc][$objJson->iIndice]->checked = "";

} else if ($method == "confirmarEntrada") {

  try {

    db_inicio_transacao();

    $sObservacao = addslashes(db_stdClass::normalizeStringJson($objJson->sObs));
    $sNota       = addslashes(db_stdClass::normalizeStringJson($objJson->sNumero));
    
    $oORdemCompra->confirmaEntrada( $sNota,
                                    $objJson->dtDataNota,
                                    $objJson->dtRecebeNota,
                                    $objJson->nValorNota,
                                    $objJson->aItens,
                                    $objJson->oInfoNota,
                                    $sObservacao
                                  );
    db_fim_transacao(false);
    echo $json->encode(array("mensagem" => "Entrada da ordem de compra efetuada com sucesso.", "status" => 1));
  }
  catch (Exception $eError) {

    db_fim_transacao(true);
    echo $json->encode(array("mensagem" => urlencode($eError->getMessage()), "status" => 2));

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
  

  if ($oORdemCompra->getBensAtivoNota($objJson->iCodigoNota) != false) {
    $status   = 2;
  }

  if ($oORdemCompra->houveDispensaTombamentoNoPatrimonio($objJson->iCodigoNota)) {
    $status   = 4;
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

    //$iAnoEmpenho = $oEmpenho->getAnoUso();
    
    $sEmpenho    = implode(", ", $aEmpenhos);
  	    
  }
  echo $json->encode(array("status" => $status, "sEmpenho" => $sEmpenho));

}