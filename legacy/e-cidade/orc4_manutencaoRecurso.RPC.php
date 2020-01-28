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
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_app::import("configuracao.DBEstrutura");
db_app::import("orcamento.TribunalEstrutura");
db_app::import("orcamento.Recurso");

$oPost             = db_utils::postMemory($_POST);
$oGet              = db_utils::postMemory($_GET);

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $oPost->json)));
$iAnoUsu           = db_getsession('DB_anousu');

$oRetorno          = new stdClass;
$oRetorno->status  = 1;
$oRetorno->message = "";

if (isset($oParam->finalidaderecurso)) {
  $sFinalidadeRecurso = utf8_decode($oParam->finalidaderecurso);
}

switch ($oParam->exec) {
	
  case "getDadosMascara":
    
  	$oDaoOrcParametro  = db_utils::getDao("orcparametro");
  	$sSqlOrcParametro  = $oDaoOrcParametro->sql_query_file($iAnoUsu, "o50_estruturarecurso", null, null);  	
  	$rsSqlOrcParametro = $oDaoOrcParametro->sql_record($sSqlOrcParametro);
  	if ($oDaoOrcParametro->numrows > 0) {
  		
  		$iCodigoEstrutura  = db_utils::fieldsMemory($rsSqlOrcParametro, 0)->o50_estruturarecurso;
  		
  	  $oEstrutura = new DBEstrutura((int)$iCodigoEstrutura);
  	  $oRetorno->mascara = $oEstrutura->getMascara();
      $oRetorno->niveis  = count($oEstrutura->getNiveis());	
  	} else {
  		
  		$oRetorno->status  = 2;
      $oRetorno->message = urlencode("Nenhuma estrutura cadastrada! Verifique.");
  	}
    break;
  
  case "getDadosRecurso":
      	
    $oRecurso = new Recurso((int)$oParam->codigorecurso);
    $oRetorno->codigorecurso         = $oRecurso->getCodigoRecurso();
    $oRetorno->descricaorecurso      = urlencode($oRecurso->getEstruturaValor()->getDescricao());
    $oRetorno->codigotribunalrecurso = urlencode($oRecurso->getEstruturaValor()->getEstrutural());
    $oRetorno->finalidaderecurso     = urlencode($oRecurso->getFinalidadeRecurso());
    $oRetorno->tipo                  = $oRecurso->getEstruturaValor()->getTipoConta();
    $oRetorno->tiporecurso           = $oRecurso->getTipoRecurso();
    $oRetorno->datalimiterecurso     = $oRecurso->getDataLimiteRecurso();
    break;

  case "getRecursos":

    $sCamposRecurso = " o15_codigo as codigo, o15_descr as descricao";
    $sOrderBy       = " o15_codigo ";
    $sWhere         = " o15_codigo <> 0 ";

    $oDaoRecursos = new cl_orctiporec();
    $sSqlRecursos = $oDaoRecursos->sql_query(null, $sCamposRecurso, $sOrderBy, $sWhere);
    $rsRecursos   = $oDaoRecursos->sql_record($sSqlRecursos);

    if (!$rsRecursos || $oDaoRecursos->numrows == 0) {

      $oRetorno->status   = 2;
      $oRetorno->erro     = true;
      $oRetorno->mensagem = "Recursos não encontrados.";
      break;
    }

    $aRecursos = array();
    for ($iRecurso = 0; $iRecurso < $oDaoRecursos->numrows; $iRecurso++) {

      $oRecurso    = db_utils::fieldsMemory($rsRecursos, $iRecurso);
      $oRecurso->descricao = urlencode($oRecurso->descricao);
      $aRecursos[] = $oRecurso;
    }

    $oRetorno->erro      = false;
    $oRetorno->aRecursos = $aRecursos;
    break;

  case "salvarRecurso":
   
    try {
       
      db_inicio_transacao();
    
	    $oDaoOrcParametro  = db_utils::getDao("orcparametro");
	    $sSqlOrcParametro  = $oDaoOrcParametro->sql_query_file($iAnoUsu, "o50_estruturarecurso", null, null);
	    $rsSqlOrcParametro = $oDaoOrcParametro->sql_record($sSqlOrcParametro);
	    
	    $iCodigoEstrutura  = '';
	    if ($oDaoOrcParametro->numrows > 0) {
	      $iCodigoEstrutura  = db_utils::fieldsMemory($rsSqlOrcParametro, 0)->o50_estruturarecurso;
	    }
      
	    if ($oParam->modo == 1) {
        $oRecurso = new Recurso();
	    } else {
	      $oRecurso = new Recurso((int)$oParam->codigorecurso);
	    }
	    
	    $oDaoEstruturaValor = db_utils::getDao("db_estruturavalor");
	    $sWhere  = "     db121_estrutural = '".db_stdClass::normalizeStringJson($oParam->codigotribunalrecurso)."'";
	    $sWhere .= " and db121_db_estrutura = '{$iCodigoEstrutura}'";
	    
	    $sSqlEstruturaValor = $oDaoEstruturaValor->sql_query_file(null, "*", null, $sWhere);
	    $rsEstruturaValor   = $oDaoEstruturaValor->sql_record($sSqlEstruturaValor);

	    $oTribunalEstrutura = null;
	    if ($oDaoEstruturaValor->numrows > 0) {
	      
	      $iSequencialEstruturaValor = db_utils::fieldsMemory($rsEstruturaValor, 0)->db121_sequencial;
	      $oTribunalEstrutura = new TribunalEstrutura($iSequencialEstruturaValor);
	      $oTribunalEstrutura->setDescricao(db_stdClass::normalizeStringJson($oParam->descricaorecurso));
	    } else {
	      
  	    $oTribunalEstrutura = new TribunalEstrutura();
  	    $oTribunalEstrutura->setEstrutura((int)$iCodigoEstrutura);
  	    $oTribunalEstrutura->setDescricao(db_stdClass::normalizeStringJson($oParam->descricaorecurso));
  	    $oTribunalEstrutura->setTipoConta($oParam->tipo);
  	    $oTribunalEstrutura->setEstrutural(db_stdClass::normalizeStringJson($oParam->codigotribunalrecurso));
	    }
	    $oTribunalEstrutura->salvar();
	    
      $oRecurso->setCodigoRecurso((int)$oParam->codigorecurso)
               ->setTipoRecurso($oParam->tiporecurso)
               ->setDataLimiteRecurso(implode("-", array_reverse(explode("/", $oParam->datalimiterecurso))))
               ->setFinalidadeRecurso(db_stdClass::normalizeStringJson($oParam->finalidaderecurso))
               ->setEstruturaValor($oTribunalEstrutura)
               ->salvar();
       
      $oRetorno->message = urlencode("Recurso {$oParam->codigorecurso} salvo com sucesso.");
               
      db_fim_transacao(false);
    } catch (Exception $eErro) {
       
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
    }
    break;
    
  case "removerRecurso":
   
    try {
       
      db_inicio_transacao();
      
      $oRecurso = new Recurso((int)$oParam->codigorecurso);
      $oRecurso->remover();

      $oRetorno->message = urlencode("Recurso {$oParam->codigorecurso} excluído com sucesso.");
      
      db_fim_transacao(false);
    } catch (Exception $eErro) {
       
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
    }
    break;
}

echo $oJson->encode($oRetorno);
?>