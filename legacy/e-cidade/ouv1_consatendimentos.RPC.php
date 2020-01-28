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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", '', $_POST['dados']));
$oRetorno = new stdClass();
$oRetorno->status = 0;
$oRetorno->message = '';

switch ($oParam->acao) {

  case 'pesquisa':
  	
  	$oDaoOuvidoriaAtendimento = db_utils::getDao('ouvidoriaatendimento');
  	
  	$sCampos  = " DISTINCT ov01_sequencial,                           "; 
    $sCampos .= " fc_numeroouvidoria(ov01_sequencial) as ov01_numero, ";
    $sCampos .= " p51_descr,                                          "; 
    $sCampos .= " ov01_requerente,                                    "; 
    $sCampos .= " descrdepto,                                         ";
    $sCampos .= " ov01_dataatend                                      ";
    
  	$sWhereBuscaAtendimentos = "";
  	if (trim($oParam->data_inicial) != "") {
  		$sWhereBuscaAtendimentos .=  " AND ov01_dataatend > '{$oParam->data_inicial}' ";
  	}
  	if (trim($oParam->data_final) != "") {
      $sWhereBuscaAtendimentos .=  " AND ov01_dataatend < '{$oParam->data_final}' ";
    }
    if (trim($oParam->tipoProcesso) != "") {
    	$sWhereBuscaAtendimentos .= " AND ov01_tipoprocesso = {$oParam->tipoProcesso} ";
    }
    $iAno = db_getsession("DB_anousu");
    if (trim($oParam->anoAtendimento) != "" && !empty($oParam->anoAtendimento)) {
      $iAno = $oParam->anoAtendimento;
    }
    if (trim($oParam->numeroAtendimento) != "") {
      
      $sWhereBuscaAtendimentos .= " AND ov01_numero = {$oParam->numeroAtendimento} ";
      $sWhereBuscaAtendimentos .= "AND ov01_anousu = {$iAno}";
    }
    if (trim($oParam->numeroProcesso) != "") {
    	$sWhereBuscaAtendimentos .= " AND ov09_protprocesso = {$oParam->numeroProcesso} ";
    }
  
    
    $sWhereBuscaAtendimentos = substr($sWhereBuscaAtendimentos, 5, (strlen($sWhereBuscaAtendimentos) - 5));
    $sOrderBy                = "ov01_numero asc";
    $sSqlBuscaAtendimentos   = $oDaoOuvidoriaAtendimento->sql_query_consultaatendimentos(null, $sCampos, 
                                                                                         $sOrderBy, $sWhereBuscaAtendimentos);
    $rsBuscaAtendimentos     = $oDaoOuvidoriaAtendimento->sql_record($sSqlBuscaAtendimentos);
    
    if ($oDaoOuvidoriaAtendimento->numrows > 0){
    	
    	$oRetorno->status  = 1;
    	$oRetorno->message = 'Houve retorno na consulta.';
    } else {
    	
    	$oRetorno->status  = 0;
    	$oRetorno->message = utf8_encode('Não houve retorno na consulta.');
    }
    
    $oRetorno->resultados = db_utils::getCollectionByRecord($rsBuscaAtendimentos, false, false, true);
  break;
   
}

echo $oJson->encode($oRetorno);
?>