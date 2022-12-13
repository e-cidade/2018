<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->itens   = "";
 
$oClassesPit50          = new stdClass;
$oClassesPit50->arquivo = "arquivoPit50";
$aClasses[50]           = $oClassesPit50;
if (!isset($aClasses[$oParam->tipodocumento])) {
    
  $oRetorno->status  = 2; 
  $oRetorno->message = urlencode("Tipo de documento {$oParam->tipodocumento} nao implementado para o pit.");
  echo $oJson->encode($oRetorno);
  exit; 
  
}
  
switch ($oParam->exec) {
  
  case "getNotas";
    
    require_once("model/{$aClasses[$oParam->tipodocumento]->arquivo}.model.php");
    $oArquivoPit = new arquivoPit50();
    $oArquivoPit->setEncode(true);
    $dtInicial   = implode("-", array_reverse(explode("/", $oParam->datainicial)));  
    $dtFinal     = implode("-", array_reverse(explode("/", $oParam->datafinal)));
    $aNotas      = $oArquivoPit->getNotasPorPeriodo($dtInicial, $dtFinal);
    $oRetorno->itens = $aNotas;
    break;
    
  case "getArquivos";
    
    $dtInicial        = implode("-", array_reverse(explode("/", $oParam->datainicial)));  
    $dtFinal          = implode("-", array_reverse(explode("/", $oParam->datafinal)));
    $oDaoEmpArquivos  = db_utils::getDao("emparquivopit");
    $sSqlArquivos     = "select distinct e14_sequencial,";
    $sSqlArquivos    .= "                e14_nomearquivo,";
    $sSqlArquivos    .= "                nome,";
    $sSqlArquivos    .= "                e14_dtarquivo,";
    $sSqlArquivos    .= "                e14_hora,";
    $sSqlArquivos    .= "                (case when e14_situacao = 1 then 'Ativo' else 'Cancelado' end) as situacao";
    $sSqlArquivos    .= "  from emparquivopit ";
    $sSqlArquivos    .= "       inner join  emparquivopitnotas    on e14_sequencial      = e15_emparquivopit";
    $sSqlArquivos    .= "       inner join  empnotadadospit       on e15_empnotadadospit = e11_sequencial ";
    $sSqlArquivos    .= "       inner join  empnotadadospitnotas  on e11_sequencial      = e13_empnotadadospit ";
    $sSqlArquivos    .= "       inner join  empnota               on e13_empnota         = e69_codnota ";
    $sSqlArquivos    .= "       inner join  empempenho            on e69_numemp          = e60_numemp ";
    $sSqlArquivos    .= "       inner join  db_usuarios           on e14_idusuario       = id_usuario ";
    $sSqlArquivos    .= " where e60_instit = ".db_getsession("DB_instit");
    $sSqlArquivos    .= "   and e14_dtarquivo between '{$dtInicial}' and '{$dtFinal}'";
    if (isset($oParam->situacao)) {
      $sSqlArquivos    .= "   and e14_situacao = {$oParam->situacao}";
    }
    $rsArquivos      = $oDaoEmpArquivos->sql_record($sSqlArquivos);
    $oRetorno->itens = db_utils::getColectionByRecord($rsArquivos, false, false, true);
    break;
    
  case "anularArquivos" :

    if (count($oParam->aArquivos) > 0) {
      
      require_once("model/{$aClasses[$oParam->tipodocumento]->arquivo}.model.php");
      try {
        
        db_inicio_transacao(); 
        foreach ($oParam->aArquivos as $oArquivo) {
          
          $oArquivoPit = new arquivoPit50($oArquivo->idArquivo);
          $oArquivoPit->anularArquivo(addslashes(utf8_decode(urldecode($oArquivo->sMotivo))));
                  
        }
        db_fim_transacao(false);
      } catch (Exception $eErro) {
        
        db_fim_transacao(true);
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
        
      }
      
    }
    break;
    
  case "pesquisaNotasArquivo": 
    
    require_once("model/{$aClasses[$oParam->tipodocumento]->arquivo}.model.php");
    $oArquivoPit = new arquivoPit50($oParam->iArquivo);
    $oArquivoPit->setEncode(true);
    $aNotas      = $oArquivoPit->getNotasArquivo();
    $oRetorno->itens = $aNotas;
    break;

  case "getArqReemitir":
  	$e14_sequencial   = $oParam->e14_sequencial;
  	if(trim($oParam->datainicial) != "" && trim($oParam->datafinal) != ""){
  	 $dtInicial        = implode("-", array_reverse(explode("/", $oParam->datainicial)));  
     $dtFinal          = implode("-", array_reverse(explode("/", $oParam->datafinal)));
  	}
    $oDaoEmpArquivos  = db_utils::getDao("emparquivopit");
    $sSqlArquivos     = "select distinct e14_sequencial,";
    $sSqlArquivos    .= "                e14_nomearquivo,";
    $sSqlArquivos    .= "                e14_dtarquivo";
    $sSqlArquivos    .= "  from emparquivopit ";
    $sSqlArquivos    .= "       inner join  emparquivopitnotas    on e14_sequencial      = e15_emparquivopit";
    $sSqlArquivos    .= "       inner join  empnotadadospit       on e15_empnotadadospit = e11_sequencial ";
    $sSqlArquivos    .= "       inner join  empnotadadospitnotas  on e11_sequencial      = e13_empnotadadospit ";
    $sSqlArquivos    .= "       inner join  empnota               on e13_empnota         = e69_codnota ";
    $sSqlArquivos    .= "       inner join  empempenho            on e69_numemp          = e60_numemp ";
    $sSqlArquivos    .= "       inner join  db_usuarios           on e14_idusuario       = id_usuario ";
    $sSqlArquivos    .= "       left  join  emparquivopitanulado  on  e16_emparquivopit  = e14_sequencial";
    $sSqlArquivos    .= " where e60_instit = ".db_getsession("DB_instit");
    $sSqlArquivos    .= "   and e16_sequencial is null ";
    if($e14_sequencial == 0){
      $sSqlArquivos    .= "   and e14_dtarquivo between '{$dtInicial}' and '{$dtFinal}'";
    }else{
    	
    	$sSqlArquivos    .= "   and e14_sequencial = ".$e14_sequencial;
    
    }
    //echo $sSqlArquivos;
    $rsArquivos      = $oDaoEmpArquivos->sql_record($sSqlArquivos);
    $oRetorno->itens = db_utils::getColectionByRecord($rsArquivos, false, false, true);
  	
  	break;
}
echo $oJson->encode($oRetorno);
?>