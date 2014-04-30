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

require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("model/patrimonio/depreciacao/BemDepreciacao.model.php");

$oDaoNotaItemBensPendentes = db_utils::getDao('empnotaitembenspendente');
$oDaoBensEmpNotaItem       = db_utils::getDao('bensempnotaitem');

$oPost = db_utils::postMemory($_POST);

$oJson            = new services_json();
$oParam           = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno         = new stdClass();
$oRetorno->status = 1;

switch ($oParam->exec) {

	case "getNotasPendentes":

	  $dtImplantacaoDepreciacao = BemDepreciacao::retornaDataImplantacaoDepreciacao(db_getsession("DB_instit"));

		$oRetorno->aNotasPendentes = array();
		$sCamposBensPendentes  = "distinct ";
    $sCamposBensPendentes .= "m52_codordem as codigonota,";
    $sCamposBensPendentes .= "e69_numemp as numeroempenho,";
		$sCamposBensPendentes .= "o56_elemento as desdobramento,";
		$sCamposBensPendentes .= "e72_qtd as quantidade,";
		$sCamposBensPendentes .= "pc01_descrmater as descricao,";
		$sCamposBensPendentes .= "e137_sequencial,";
    $sCamposBensPendentes .= "e72_sequencial as codigoitemnota,";
    $sCamposBensPendentes .= "e60_codemp as codigoempenho,";
    $sCamposBensPendentes .= "e72_valor as valornota,";
    $sCamposBensPendentes .= "e60_anousu as anoempenho";

    $sWhereBensPendentes   = "     bensempnotaitem.e136_empnotaitem is null";
    /**
     * Conforme acertado com Henrique, nao mostrar notas liquidadas
     */
    $sWhereBensPendentes  .= " and empnotaitem.e72_vlrliq = 0 ";
    $sWhereBensPendentes  .= " and empempenho.e60_anousu = ".db_getsession("DB_anousu");
    if (!empty($dtImplantacaoDepreciacao)) {

      $sWhereBensPendentes  .= " and empempenho.e60_emiss >= '{$dtImplantacaoDepreciacao}' ";

      if (!USE_PCASP) {
       $sWhereBensPendentes  .="  and o56_elemento in (select distinct e135_desdobramento from configuracaodesdobramentopatrimonio)";
      }

    }

    $iInstituicaoSessao = db_getsession("DB_instit");
    if (USE_PCASP) {

	    $sNomeTabelaPlanoConta      = "conplanoorcamento";
	    $sNomeTabelaGrupoPlanoConta = "conplanoorcamentogrupo";
      $sWhereBensPendentes  .= " and exists ( select 1
                                                from orcelemento oe
                                                     inner join {$sNomeTabelaPlanoConta}      on {$sNomeTabelaPlanoConta}.c60_codcon = oe.o56_codele
                                                                                             and {$sNomeTabelaPlanoConta}.c60_anousu = oe.o56_anousu
                                                     inner join {$sNomeTabelaGrupoPlanoConta} on {$sNomeTabelaGrupoPlanoConta}.c21_codcon = {$sNomeTabelaPlanoConta}.c60_codcon
                                                                                             and {$sNomeTabelaGrupoPlanoConta}.c21_anousu = {$sNomeTabelaPlanoConta}.c60_anousu
                                                                                             and {$sNomeTabelaGrupoPlanoConta}.c21_instit = {$iInstituicaoSessao}
                                               where {$sNomeTabelaGrupoPlanoConta}.c21_congrupo = 9
                                                 and oe.o56_codele = orcelemento.o56_codele
                                                 and oe.o56_anousu = orcelemento.o56_anousu )";
    }
    
    $sWhereBensPendentes .= " and e60_instit = {$iInstituicaoSessao} "; 
    $sWhereBensPendentes .= " and m53_codordem is null ";
    $sWhereBensPendentes .= " and e70_vlranu = 0 ";
    
    $sSqlDadosBensPendentes = $oDaoNotaItemBensPendentes->sql_query_bens_nota(null, $sCamposBensPendentes, null, $sWhereBensPendentes);
    $sSqlBensPendentes      = "select *                                                ";              
    $sSqlBensPendentes     .= "  from ($sSqlDadosBensPendentes) as xx                  ";
    $sSqlBensPendentes     .= " order by anoempenho, codigoempenho::int                ";
    $rsBensPendentes        = $oDaoNotaItemBensPendentes->sql_record($sSqlBensPendentes);
		if ($oDaoNotaItemBensPendentes->numrows > 0) {
			$oRetorno->aNotasPendentes = db_utils::getCollectionByRecord($rsBensPendentes, false, false, true);
		}

  break;

	case "getBensPorCodigoNota":


		$oRetorno->aNotasPendentes = array();
		$sCamposBensNotaPendentes  = "distinct e69_codnota as codigonota,";
		$sCamposBensNotaPendentes .= "         e69_numemp as numeroempenho,";
		$sCamposBensNotaPendentes .= "         o56_elemento as desdobramento,";
		$sCamposBensNotaPendentes .= "         e72_qtd as quantidade,";
		$sCamposBensNotaPendentes .= "         pc01_descrmater as descricao,";
		$sCamposBensNotaPendentes .= "         0 as e137_sequencial,";
		$sCamposBensNotaPendentes .= "         e72_valor as valornota,";
		$sCamposBensNotaPendentes .= "         e60_codemp as codigoempenho,";
		$sCamposBensNotaPendentes .= "         e60_anousu as anoempenho,";
		$sCamposBensNotaPendentes .= "         e72_sequencial as codigoitemnota";
		$sWhereBensNotaPendente    = "e69_codnota in (". implode(",", $oParam->aCodigoNota). ")";
    $sSqlBensNotaPendente      = $oDaoNotaItemBensPendentes->sql_query_bens(null, $sCamposBensNotaPendentes, null, $sWhereBensNotaPendente);
		$rsBensNotaPendente        = $oDaoNotaItemBensPendentes->sql_record($sSqlBensNotaPendente);
		if ($oDaoNotaItemBensPendentes->numrows > 0) {
			$oRetorno->aNotasPendentes = db_utils::getCollectionByRecord($rsBensNotaPendente, false, false, true);
		}
  break;

}
echo $oJson->encode($oRetorno);
?>