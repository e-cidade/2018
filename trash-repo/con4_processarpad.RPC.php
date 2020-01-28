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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_libpostgres.php");
require_once("libs/db_sessoes.php");
require_once("model/padArquivoEscritorXML.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$oRetorno->itens   = array();
switch($oParam->exec) {
  
  case "processarSigap":
    
    $sDataInicial = db_getsession("DB_anousu").'-01-01';
    $iUltimoDia   = cal_days_in_month(CAL_GREGORIAN, $oParam->iPeriodo, db_getsession("DB_anousu"));
    $sDataFinal   = db_getsession("DB_anousu")."-".str_pad($oParam->iPeriodo, 2, "0",STR_PAD_LEFT)."-{$iUltimoDia}";
    if (count($oParam->aArquivos) > 0) {
      
      $oEscritorXML = new padArquivoEscritorXML();
      $otxtLogger   = fopen("tmp/SIGAP.log", "w");
      foreach ($oParam->aArquivos as $sArquivo) {
        
        if (file_exists("model/PadArquivoSigap{$sArquivo}.model.php")) {
          
          require_once("model/PadArquivoSigap{$sArquivo}.model.php");
          $sNomeClasse = "PadArquivoSigap{$sArquivo}"; 
          
          $oArquivo    = new $sNomeClasse;
          $oArquivo->setDataInicial($sDataInicial);
          $oArquivo->setDataFinal($sDataFinal);
          $oArquivo->setCodigoTCE($oParam->iCodigoTCE);
          $oArquivo->setTXTLogger($otxtLogger);
          if ($sArquivo == 'Ppa') {
            $oArquivo->setCodigoVersao($oParam->iPerspectivaPPa);
          }
        if ($sArquivo == 'LoaDespesa' || $sArquivo == 'LoaReceita') {
            $oArquivo->setCodigoVersao($oParam->iPerspectivaCronograma);
          }
          try {
            
            $oArquivo->gerarDados();
            $oEscritorXML->adicionarArquivo($oEscritorXML->criarArquivo($oArquivo), $oArquivo->getNomeArquivo());
          } catch (Exception $eErro) {
          	
            $oRetorno->status  = 2;
            $sGetMessage       = "Arquivo:{$oArquivo->getNomeArquivo()} retornou com erro: \\n \\n {$eErro->getMessage()}";
            $oRetorno->message = urlencode(str_replace("\\n", "\n",$sGetMessage));
          }
        }
      }
      
      $oEscritorXML->zip("SIGAP");
      $oEscritorXML->adicionarArquivo("tmp/SIGAP.log", "SIGAP.log");
      $oEscritorXML->adicionarArquivo("tmp/SIGAP.zip", "SIGAP.zip");
      $oRetorno->itens  = $oEscritorXML->getListaArquivos();
      fclose($otxtLogger);
    }
    break;
}

echo $oJson->encode($oRetorno);