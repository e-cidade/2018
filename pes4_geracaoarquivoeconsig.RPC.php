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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");

define('MENSAGENS', 'recursoshumanos.pessoal.pes4_geracaoarquivoeconsig.');

$oJson                = new services_json();
$oParametros          = $oJson->decode(utf8_decode(str_replace("\\", "", urldecode($_POST["json"]))));
$oRetorno             = new stdClass();
$oRetorno->iStatus    = 1;
$oRetorno->sMensagem  = '';

try {

  switch ($oParametros->sExecucao) {

    case 'gerarArquivoMargem' :

      $oDaoArquivoEconsig = new GeracaoArquivoEconsig( $oParametros->iAnoUsu, $oParametros->iMesUsu );
      $sArquivoEconsig    = $oDaoArquivoEconsig->gerarArquivoMargem();

      if( empty($sArquivoEconsig) ){
        throw new BusinessException( _M( MENSAGENS . 'erro_gerar_arquivo' ) );
      }

      $oRetorno->sArquivoEconsig = $sArquivoEconsig;
      $aArquivo                  = explode('/', $sArquivoEconsig);
      $oRetorno->sNomeArquivo    = $aArquivo[sizeof($aArquivo)-1];
    break;

  }

} catch (Exception $oErro) {

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);