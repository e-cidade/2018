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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_layouttxt.php");
require_once("dbforms/db_funcoes.php");
require_once('std/DBLargeObject.php');
require_once("model/arrecadacao/ArquivoCobrancaRegistrada.model.php");

$oJson                      = new services_json();
$oPost                      = db_utils::postMemory($_POST);
$oParam                     = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$oPost->json)));
                            
$oRetorno                   = new stdClass();

$oArquivoCobrancaRegistrada = new ArquivoCobrancaRegistrada();
 
switch($oParam->exec) {
    
  // Gerar arquivo para o banco
  case "geraArqBanco" :

    try {
      
      $sNomeArq       = "arqremessabanco_cobrancaregistrada_".str_replace("/","",$oParam->dtProc).".txt";
      $dtProc = db_formatar($oParam->dtProc,"xxxv");
      
      
      if (!$oParam->lReemitir) {
        
        db_inicio_transacao();      
        $oArquivoCobrancaRegistrada->geraArquivoRemessaBanco($dtProc, $sNomeArq);
        db_fim_transacao(false);        
        
        $oRetorno->arquivo[] = urlEncode("tmp/".$sNomeArq);
        
      } else {
        
        db_inicio_transacao();
        
        $iNomeAuxiliar = 0;
        foreach ($oParam->aSelecionados as $iSelecionado) {
          $iNomeAuxiliar++;
          $sLocalArquivo = $oArquivoCobrancaRegistrada->reemiteArquivoRemessaBanco($iSelecionado, $iNomeAuxiliar.'-');
          $oRetorno->arquivo[] = urlEncode($sLocalArquivo);
        }
        
        db_fim_transacao(false);
              
      }
      
      $oRetorno->status      = 1;
      $oRetorno->message     = 1;
      
    } catch (Exception $eException) {
             
      db_fim_transacao(true);
      $oRetorno->status      = 2;
      $oRetorno->arquivo     = "";
      $oRetorno->message     = urlencode($eException->getMessage());
        
    }
    
  break;

  // Gerar arquivo para o TJ  
  case "geraArqTj" :

    try {
      
      $sNomeArq       = "arqremessatj_".str_replace("/","",$oParam->dtProc).".txt";
      $dtProc = db_formatar($oParam->dtProc,"xxxv");
      
      db_inicio_transacao();
      $oArquivoCobrancaRegistrada->geraArquivoRemessaTj($dtProc,$sNomeArq);
      db_fim_transacao(false);
      
      $oRetorno->status      = 1;
      $oRetorno->message     = 1;
      $oRetorno->arquivo = urlEncode("tmp/".$sNomeArq);
    
    } catch (Exception $eException) {
             
      db_fim_transacao(true);
      $oRetorno->status      = 2;
      $oRetorno->arquivo     = "";      
      $oRetorno->message     = urlencode($eException->getMessage());      
        
    }
    
  break;

  case 'pesquisaArquivos' :
    
    try {
      
      $dtProc = db_formatar($oParam->dtProc,"xxxv");
      
      $oDaoPartilhaArquivo = db_utils::getDao('partilhaarquivo');
      
      $sWhere = "v78_dtgeracao = '{$dtProc}' and v78_arquivo is not null";
      
      $sSql = $oDaoPartilhaArquivo->sql_query_file(null, '*', null, $sWhere);
      
      $rsPartilhaArquivo = $oDaoPartilhaArquivo->sql_record($sSql);
      
      $aPartilhaArquivo = array();
      
      for ($iContador = 0; $iContador < $oDaoPartilhaArquivo->numrows; $iContador++) {

        $oPartilhaArquivo = db_utils::fieldsMemory($rsPartilhaArquivo, $iContador);
        
        $aPartilhaArquivo[] = $oPartilhaArquivo;
        
      }
            
      
      $oRetorno->status      = 1;
      $oRetorno->message     = 1;
      $oRetorno->aRegistros   = $aPartilhaArquivo;
      
      
    } catch (Exception $eException) {
      $oRetorno->status      = 2;
      $oRetorno->arquivo     = "";
      $oRetorno->message     = urlencode($eException->getMessage());
    }
    
  break;
  
}   

echo $oJson->encode($oRetorno);
   
?>