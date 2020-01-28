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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisArquivoPlanoConta.model.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisVinculoConta.model.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisVinculoRecurso.model.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisVinculoReceita.model.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisVinculoDespesa.model.php");
require_once ("model/PadArquivoEscritorTXT.model.php");

db_app::import("exceptions.*");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->lista   = array();
switch ($oParam->exec) {

  case "processarSigfis":

    try {

      $oEscritor     = new PadArquivoEscritorTXT();
      $iAnoUsu       = db_getsession("DB_anousu");
      $sMesFinal     = str_pad($oParam->iPeriodo, 2, "0", STR_PAD_LEFT);
      $sDataInicial  = "{$iAnoUsu}-{$sMesFinal}-01";
      $iUltimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $sMesFinal, $iAnoUsu);
      $sDataFinal    = "{$iAnoUsu}-{$sMesFinal}-{$iUltimoDiaMes}";
      $otxtLogger    = fopen("tmp/SIGFIS.log", "w");

      if (count($oParam->aArquivos) > 0) {

        foreach ($oParam->aArquivos as $sArquivo) {

          /**
           * Verifica dinamicamente se a classe existe, se Existe cria uma instancia da classe
           */
          if (file_exists("model/contabilidade/arquivos/sigfis/SigfisArquivo{$sArquivo}.model.php")) {

            require_once("model/contabilidade/arquivos/sigfis/SigfisArquivo{$sArquivo}.model.php");

            $sNomeClasse = "SigfisArquivo{$sArquivo}";
            $oArquivo    = new $sNomeClasse;
            $oArquivo->setDataInicial($sDataInicial);
            $oArquivo->setDataFinal($sDataFinal);
            $oArquivo->setCodigoTribunal(urldecode($oParam->sCodigoTribunal));
            $oArquivo->setTXTLogger($otxtLogger);
            $oArquivo->gerarDados();
            $oEscritor->adicionarArquivo($oEscritor->criarArquivo($oArquivo), $oArquivo->getNomeArquivo());
          }
        }
        $oEscritor->zip("SIGFIS");
        $oEscritor->adicionarArquivo("tmp/SIGFIS.zip", "SIGFIS.zip");
        $oEscritor->adicionarArquivo("tmp/SIGFIS.log", "SIGFIS.LOG");
        $oRetorno->lista = $oEscritor->getListaArquivos();
       }
    } catch (Exception $eErro) {

      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 0;
    }
    break;
}

echo $oJson->encode($oRetorno);