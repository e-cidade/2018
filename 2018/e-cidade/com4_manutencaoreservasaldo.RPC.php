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

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("model/itemSolicitacao.model.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/Dotacao.model.php"));
require_once(modification("model/empenho/AutorizacaoEmpenho.model.php"));
db_app::import("CgmFactory");


$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";

switch($oParam->exec) {

  case 'pesquisarSolicitacao' :

   $sWhereSolicita    = "pc10_depto = ".db_getsession("DB_coddepto");
   $sWhereSolicita   .= " and pc10_solicitacaotipo in(1, 2, 5)";
   $sWhereSolicita   .= " and pc13_coddot is not null";
   $sWhereSolicita    .= " and not exists(select 1 from solicitaanulada where pc67_solicita = pc10_numero)";
   $iSolicitacaoDe    = $oParam ->iSolicitacaoDe  ;
   $iSolicitacaoAte   = $oParam ->iSolicitacaoAte ;
   $sDataIni          = implode("-", array_reverse(explode("/", $oParam ->sDataIni))) ;
   $sDataFim          = implode("-", array_reverse(explode("/", $oParam ->sDataFim)));
   $iDotacao          = $oParam ->iDotacao ;
   $aDadosSolicitacao = array();
   $aSolicitacoes     = array();

   if ($sDataIni != '' && $sDataFim == '') {
     $sWhereSolicita .= " and pc10_data >= '{$sDataIni}'";
   } else if ($sDataIni == '' && $sDataFim != '') {
     $sWhereSolicita .= " and pc10_data <= '{$sDataFim}'";
   } else if ($sDataIni != '' && $sDataFim != '') {
     $sWhereSolicita .= " and pc10_data between '{$sDataIni}' and '{$sDataFim}'";
   }
   if ($iSolicitacaoDe  != "" && $iSolicitacaoAte == '') {
     $sWhereSolicita .= " and pc10_numero >= {$iSolicitacaoDe}";
   } else if ($iSolicitacaoDe  != "" && $iSolicitacaoAte == '') {
     $sWhereSolicita .= " and pc10_numero <= {$iSolicitacaoAte}";
   } else if ($iSolicitacaoDe  != "" && $iSolicitacaoAte != '') {
     $sWhereSolicita .= " and pc10_numero between {$iSolicitacaoDe} and {$iSolicitacaoAte}";
   }
   if ($iDotacao != '') {
     $sWhereSolicita .= " and pc13_coddot = {$iDotacao} and pc13_anousu = ".db_getsession("DB_anousu");
   }
   $oDaoSolicita     = new cl_solicita;
   $sCamposSolicita  = "pc10_numero,          ";
	 $sCamposSolicita .= "pc10_data,            ";
	 $sCamposSolicita .= "pc10_resumo,          ";
	 $sCamposSolicita .= "pc10_solicitacaotipo, ";
	 $sCamposSolicita .= "array_to_string(array_accum(distinct pc13_coddot),', ')  as pc13_coddot";

   $sWhereSolicita  .= " group by pc10_numero, pc10_data, pc10_resumo, pc10_solicitacaotipo";
   $sSqlSolicitacoes = $oDaoSolicita->sql_query_reserv(null,
                                                        $sCamposSolicita,
                                                        "pc10_numero",
                                                        $sWhereSolicita
                                                        );
   $rsSolicitacoes    = db_query($sSqlSolicitacoes);
   $aSolicitacoes    =  db_utils::getCollectionByRecord($rsSolicitacoes, false, false, false);

   foreach ($aSolicitacoes as $iIndSolicitacoes => $oValorSolicitacoes){

   	$oDados              = new stdClass();
    $sResumo             = $oValorSolicitacoes->pc10_resumo;
   	$oDados->solicitacao = $oValorSolicitacoes->pc10_numero;
   	$oDados->dtEmis      = db_formatar($oValorSolicitacoes->pc10_data, "d");
   	$oDados->dotacoes    = $oValorSolicitacoes->pc13_coddot;
   	$oDados->resumo      = urlencode(substr($oValorSolicitacoes->pc10_resumo, 0, 100));
   	$aDadosSolicitacao[] = $oDados;
   }

   $oRetorno->dados      = $aDadosSolicitacao;

  break;

  case 'pesquisarSolicitacaoDetalhes' :

    $oSolicitem    = db_utils::getDao('solicitem');
    $iSolicitacao  = $oParam->iSolicitacao;
    $oRetorno->iCodigoSolicitacao = $oParam->iSolicitacao;
    $aDadosItens   = array();
    $oItens        = array();
    $sSqlItens     = $oSolicitem->sql_query_file(null, "*", "pc11_seq", "pc11_numero = {$iSolicitacao} ");
    $rsItens       = $oSolicitem->sql_record($sSqlItens);
    $oItens        = db_utils::getCollectionByRecord($rsItens, false, false, true);
    $aItensRetorno = array();

    foreach ($oItens as $iIndiceItem => $oValorItem){


       $oItemSolicitacao          = new itemSolicitacao($oValorItem->pc11_codigo, null);
   	   $oItemRetorno              = new stdClass();

   	   $nValorItemSolicitacao     = ($oItemSolicitacao->getQuantidade() * $oItemSolicitacao->getValorUnitario());

   	   //$oItemSolicitacao->getValorOrcadoSolicitacao();

   	   //die();
   	   if ($oItemSolicitacao->getValorUnitario() == 0) {
   	     $nValorItemSolicitacao = $oItemSolicitacao->getValorOrcadoItemSolicitacao();
   	   }
   	   $oItemRetorno->item        = $oItemSolicitacao->getCodigoItemSolicitacao();
   	   $oItemRetorno->ordem       = $oItemSolicitacao->getOrdem();
   	   $oItemRetorno->descricao   = $oItemSolicitacao->getDescricaoMaterial();
   	   $oItemRetorno->dotacoes    = array();
	  	 $oItemRetorno->nValorTotal = $nValorItemSolicitacao;
   	   $aDotacoes               = $oItemSolicitacao->getDotacoes();
   	   foreach ($aDotacoes as $oDotacao) {

   	  	 $oItemDotacao = new stdClass();
   	  	 $oItemDotacao->codigo        = $oDotacao->oDotacao->getCodigo();
   	  	 $oItemDotacao->saldofinal    = $oDotacao->oDotacao->getSaldoAtualMenosReservado();
   	  	 $oItemDotacao->codigoreserva = $oDotacao->iCodigoReserva;
   	  	 $oItemDotacao->nValorReserva = $oDotacao->nValorReservado;
   	  	 $oItemDotacao->nValorDotacao = $oDotacao->nValorDotacao;
   	  	 $oItemRetorno->dotacoes[]    = $oItemDotacao;

   	   }
   	   $aItensRetorno[]        = $oItemRetorno;
    }
    $oRetorno->dados      = $aItensRetorno;
    break;

  case 'modificarReservas':

    try {

      db_inicio_transacao();
      foreach ($oParam->aItens as $oItem) {

        $oItemSolicitacao = new itemSolicitacao($oItem->iCodigoItem);
        foreach ($oItem->aDotacoesReserva as $oReserva) {

          if ($oReserva->iCodigoReserva != '') {
            $oItemSolicitacao->excluiReservaSaldo($oReserva->iCodigoReserva);
          }
          if ($oReserva->nValorReserva > 0) {

            $oDotacao = new Dotacao($oReserva->iCodigoDotacao, db_getsession("DB_anousu"));
            $oItemSolicitacao->incluirReservaSaldo($oDotacao, $oReserva->nValorReserva);
          }
        }
      }
      $oRetorno->iCodigoSolicitacao = $oParam->iSolicitacaoAtiva;
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;

  case 'pesquisarAutorizacao' :


   $sWhereAutorizacao  = " e54_depto = ".db_getsession("DB_coddepto");
   $sWhereAutorizacao .= " and extract(year from e54_emiss) = ".db_getsession("DB_anousu");
   $sWhereAutorizacao .= " and e60_numemp is null and orcreserva.o80_codres is not null";

   $iAutorizacao1      = $oParam ->iAutorizacao1  ;
   $iAutorizacao2      = $oParam ->iAutorizacao2 ;
   $sDataIni           = implode("-", array_reverse(explode("/", $oParam ->sDataIni))) ;
   $sDataFim           = implode("-", array_reverse(explode("/", $oParam ->sDataFim)));
   $iDotacao           = $oParam ->iDotacao ;
   $aDadosAutorizacao  = array();
   $aAutorizacoes      = array();
   if ($sDataIni != '' && $sDataFim == '') {
     $sWhereAutorizacao .= " and e54_emiss >= '{$sDataIni}'";
   } else if ($sDataIni == '' && $sDataFim != '') {
     $sWhereAutorizacao .= " and e54_emiss <= '{$sDataFim}'";
   } else if ($sDataIni != '' && $sDataFim != '') {

     $sWhereAutorizacao .= " and e54_emiss between '{$sDataIni}' and '{$sDataFim}'";
   }

   if ($iAutorizacao1  != "" && $iAutorizacao2 == '') {
     $sWhereAutorizacao .= " and e54_autori >= {$iAutorizacao1}";
   } else if ($iAutorizacao1  != "" && $iAutorizacao2 == '') {
     $sWhereAutorizacao .= " and e54_autori <= {$iAutorizacao2}";
   } else if ($iAutorizacao2  != "" && $iAutorizacao2 != '') {
     $sWhereAutorizacao .= " and e54_autori between {$iAutorizacao1} and {$iAutorizacao2}";
   }

   if ($iDotacao != '') {
     $sWhereAutorizacao .= " and e56_coddot = {$iDotacao} and e56_anousu = ".db_getsession("DB_anousu");
   }
   $oDaoAutorizacao     = db_utils::getDao("empautoriza");
   $sCamposAutorizacao  = "e54_autori,                           ";
	 $sCamposAutorizacao .= "e54_emiss,                            ";
	 $sCamposAutorizacao .= "z01_numcgm||' - '||z01_nome as credor,";
	 $sCamposAutorizacao .= "e54_resumo,                           ";
	 $sCamposAutorizacao .= "e54_codcom,                           ";
	 $sCamposAutorizacao .= "e54_valor,                            ";
	 $sCamposAutorizacao .= "array_to_string(array_accum(distinct e56_coddot),', ')  as e56_coddot";

   $sWhereAutorizacao  .= " group by e54_autori,e54_emiss,z01_numcgm,z01_nome,e54_resumo,e54_codcom,e54_valor";
   $sSqlAutorizacao = $oDaoAutorizacao->sql_query_deptoautori(null,
                                                        $sCamposAutorizacao,
                                                        "e54_autori",
                                                        $sWhereAutorizacao
                                                        );
   $rsAutorizacao    = db_query($sSqlAutorizacao);
   $aAutorizacao     = db_utils::getCollectionByRecord($rsAutorizacao, false, false, false);

   foreach ($aAutorizacao as $iIndAutorizacao => $oValorAutorizacao){

   	$oDados              = new stdClass();
    $sResumo             = $oValorAutorizacao->e54_resumo;
   	$oDados->autorizacao = $oValorAutorizacao->e54_autori;
   	$oDados->dtEmis      = db_formatar($oValorAutorizacao->e54_emiss, "d");
   	$oDados->credor      = $oValorAutorizacao->credor;
   	$oDados->dotacoes    = $oValorAutorizacao->e56_coddot;
   	$oDados->resumo      = urlencode(substr($oValorAutorizacao->e54_resumo, 0, 100));
   	$oDados->valor       = $oValorAutorizacao->e54_valor;
   	$aDadosAutorizacao[] = $oDados;
   }

   $oRetorno->dados      = $aDadosAutorizacao;

  break;

  case 'removerReservaAutorizacao':

    try {

      db_inicio_transacao();
      if (is_array($oParam->aAutorizacoes) && count($oParam->aAutorizacoes) > 0) {

        foreach ($oParam->aAutorizacoes as $iAutorizacao) {

          $oAutorizacao = new AutorizacaoEmpenho($iAutorizacao);
          $oAutorizacao->excluirReservaSaldo();
        }
      }

      db_fim_transacao(false);
    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);
    }
    break;

}

echo $oJson->encode($oRetorno);
