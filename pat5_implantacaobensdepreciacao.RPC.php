<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("model/patrimonio/Bem.model.php");
require_once("model/patrimonio/BemCedente.model.php");
require_once("model/patrimonio/BemClassificacao.model.php");
require_once("model/patrimonio/PlacaBem.model.php");
require_once("model/patrimonio/BemHistoricoMovimentacao.model.php");
require_once("model/patrimonio/BemDadosImovel.model.php");
require_once("model/patrimonio/BemTipoAquisicao.php");
require_once("model/patrimonio/BemTipoDepreciacao.php");
require_once("model/CgmFactory.model.php");

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

switch ($oParam->exec) {
	
	case 'getBensClassificacao':
		
		$oDaoClaBens       = db_utils::getDao('clabens');
		$aClassificacaoBem = array();
		$sCamposBens       = " t64_class, t64_codcla, t64_descr, t52_descr, t52_bem, t52_ident, t44_vidautil, t46_descricao, t46_sequencial ";
		$sWhereBens        = " t64_codcla BETWEEN {$oParam->t64_codcla_ini} ";
		$sWhereBens       .= "                AND {$oParam->t64_codcla_fin} ";
		$sWhereBens       .= " AND (SELECT count(*) from benshistoricocalculobem where t58_bens = t52_bem) = 0 ";
		$sWhereBens       .= " ORDER BY t64_class, t52_bem ";
		$sSqlBuscaBens     = $oDaoClaBens->sql_query_itensporclassificacao(null, $sCamposBens, null, $sWhereBens);
		$rsBuscaBens       = $oDaoClaBens->sql_record($sSqlBuscaBens);
		$iLinhasBens       = $oDaoClaBens->numrows;
		
		for ($iRow = 0; $iRow < $iLinhasBens; $iRow++) {
		  
		  $oDadoBem                      = db_utils::fieldsMemory($rsBuscaBens, $iRow);
		  if (!isset($aClassificacaoBem[$oDadoBem->t64_codcla])) {

		    $oClassificacao                = new stdClass();
		    $oClassificacao->codigo        = $oDadoBem->t64_codcla;
		    $oClassificacao->classificacao = $oDadoBem->t64_class;
		    $oClassificacao->descricao     = $oDadoBem->t64_descr;
		    $oClassificacao->itens         = array();
		    $aClassificacaoBem[$oDadoBem->t64_codcla] = $oClassificacao;
		  }
		  $oDadoBem->t46_descricao         = urlencode($oDadoBem->t46_descricao);
		  $aClassificacaoBem[$oDadoBem->t64_codcla]->itens[] = $oDadoBem;
		}
		$oRetorno->aDados = $aClassificacaoBem;
		break;
	
	case 'processarImplementacao':
		
	  try {
	    
	    db_inicio_transacao();
	    
  	  foreach ($oParam->aItens as $oItem) {
  	    
  	    $oBem             = new Bem($oItem->iBem);
  	    $oTipoAquisicao   = new BemTipoAquisicao($oParam->iTipoAquisicao);
  	    $oBem->setTipoAquisicao($oTipoAquisicao);
  	    $oTipoDepreciacao = new BemTipoDepreciacao($oParam->iTipoDepreciacao);
  	    $oBem->setTipoDepreciacao($oTipoDepreciacao);
  	    $oBem->setVidaUtil($oParam->iVidaUtil);
  	    $oBem->setValorResidual(0);
  	    $oBem->setValorAtual($oBem->getValorAquisicao() - $oBem->getValorResidual());
  	    $oBem->salvar();
  	  }
  	  db_fim_transacao(false);
  	  
	  } catch (Exception $eErro) {
	    
	    db_fim_transacao(true);
	    $oRetorno->status = 2;
	    $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
	  }
		break;
		
	case 'validarCalculosEfetuados':
	  
	  $oDaoBensHistoricoCalculo     = db_utils::getDao("benshistoricocalculo");
	  $sWhere                       = "t57_ativo is true ";
	  $sWhere                      .= " and t57_instituicao = ".db_getsession("DB_instit");
	  $sSqlVerificaCalculoExistente = $oDaoBensHistoricoCalculo->sql_query_file(null,
	                                                                           "t57_mes",
	                                                                           "t57_sequencial desc limit 1",
	                                                                           $sWhere
	                                                                          );
    $rsVerificaCalculoExistente   = $oDaoBensHistoricoCalculo->sql_record($sSqlVerificaCalculoExistente);
    if ($oDaoBensHistoricoCalculo->numrows > 0) {

      $oRetorno->status   = 2;
      $sMessage   = "Já existem calculos de depreciação para os bens.\n";
      $sMessage  .= "Os Bens/Classificações deverão ser alteradas individualmente.";
      $oRetorno->message  = urlencode($sMessage);  
      
    }
	  break;
}

echo $oJson->encode($oRetorno);