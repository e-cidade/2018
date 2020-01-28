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

require_once ("std/db_stdClass.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_arrecad_classe.php");
require_once ("classes/db_arrecant_classe.php");
require_once ("classes/db_arrehist_classe.php");
require_once ("libs/JSON.php");

db_postmemory($_POST);

$clArrecad         = new cl_arrecad;
$clArrecant        = new cl_arrecant;
$clArrehist        = new cl_arrehist;

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;

switch ($oParam->exec){
  
  case "cancela": 
    
    $lErro = false;
    $sMsg  = "";
    
    db_inicio_transacao();
    try {
      
      foreach ($oParam->dados as $iInd => $oDados) {
        
        //Tipo "r" é do tipo arrecant tipo "a" é do tipo arrecad
        if ($oDados->tipo == "r") {
          
          $clArrecant->excluir($oDados->oid, null);
          if ($clArrecant->numrows_excluir == 0) {
            
            $lErro = true;
            $sMsg  = $clArrecant->erro_msg;
          }
          
          $clArrecant->excluir_arrecant($oDados->k00_numpre, $oDados->k00_numpar);
          if ($clArrecant->erro_status == 0) {
            
            $lErro = true;
            $sMsg  = $clArrecant->erro_msg;
          }
        } else { 
          
          $clArrecad->excluir($oDados->oid, null);
          if ($clArrecad->numrows_excluir == 0) {
            
            $lErro = true;
            $sMsg  = $clArrecad->erro_msg ."\n" .$clArrecad->erro_sql;
          }
        }
        
        // Setando Variaveis na classe        
        $clArrehist->k00_numpre     = $oDados->k00_numpre;
        $clArrehist->k00_numpar     = $oDados->k00_numpar;
        $clArrehist->k00_hist       = 999;
        $clArrehist->k00_dtoper     = date("Y-m-d",db_getsession("DB_datausu"));
        $clArrehist->k00_hora       = date("H:i");
        $clArrehist->k00_id_usuario = db_getsession("DB_id_usuario");
        $clArrehist->k00_histtxt    = "Valor = {$oDados->k00_valor}";
        $clArrehist->k00_limithist  = null;
        $clArrehist->incluir(null);
        if ($clArrehist->numrows_incluir == 0) {
          
          $lErro = true;
          $sMsg  = $clArrecant->erro_msg;
        } 
        
        // Se houver algum erro no processo, lança uma exeção.
        if ($lErro) {
          throw new Exception($sMsg);
        } else {
          $oRetorno->message = $sMsg;
        }
      }
      db_fim_transacao(false);
      
    }  catch (Exception $oException) {
      db_fim_transacao(true);
      $oRetorno->message = $oException->getMessage(); 
      $oRetorno->status  = 2;
    } 
    
  break ;
    
  case 'buscaDados': 
    
    $oRetorno->aDados = array();  
    $sSqlDados        = $clArrecad->sql_query_buscaDesconto($oParam->k00_numpre);
    $rsDados          = $clArrecad->sql_record($sSqlDados);

    if ($clArrecad->numrows == 0) {
      
      $oRetorno->message = "Sem débitos a serem cancelados";
      $oRetorno->status  = 2;
      
    } else {
      
      for ($ind = 0; $ind < $clArrecad->numrows; $ind++) {
        
        $oDados             = db_utils::fieldsMemory($rsDados,$ind);
        $oRetorno->aDados[] = $oDados; 
      }
    }
  break;
  
}

echo $oJson->encode($oRetorno);
?>