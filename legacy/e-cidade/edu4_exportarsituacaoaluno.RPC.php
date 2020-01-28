<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_layouttxt.php");
require_once ("libs/JSON.php");  

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    case "exportar":
      
      $oRetorno->sMessage = "Arquivo gerado com sucesso.";
      
      $sArquivoLog = "tmp/exportacao_situacao_aluno_{$oParam->iAnoCenso}.json";
      
      $oLog = new DBLogJSON($sArquivoLog);
      
      $oExportacao = new ExportacaoSituacaoAlunoCenso2013( $oLog, $oParam->iAnoCenso );
      $oExportacao->setEscola(EscolaRepository::getEscolaByCodigo(db_getsession("DB_coddepto")));
      $oRetorno->sArquivoCenso = urlencode( $oExportacao->getNomeArquivoCenso() );
      
      if (!$oExportacao->gerarArquivo()) {
        
        $oRetorno->iStatus     = 2;
        $oRetorno->sMessage    = urlencode( "Falha ao gerar o arquivo. Verifique as inconsistências." );
        $oRetorno->sArquivoLog = $sArquivoLog;      
      }
        
    break;
  }
  
  db_fim_transacao(false);
    
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
?>