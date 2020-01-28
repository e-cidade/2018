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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/JSON.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

define( "MENSAGENS_SAU4_TRIAGEMAGRAVO_RPC", 'saude.ambulatorial.sau4_triagemagravo_RPC.' );

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->dados   = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try{

  db_inicio_transacao();

  switch ($oParam->exec) {

    case 'salvarAgravo':

      $oDate = new DBDate( $oParam->dtSintoma );
      $sData = $oDate->convertTo( DBDate::DATA_EN );

      $oDaoTriagemAgravo = new cl_sau_triagemavulsaagravo();
      $oDaoTriagemAgravo->s167_sequencial        = null;
      $oDaoTriagemAgravo->s167_sau_triagemavulsa = $oParam->iTriagemAvulsa;
      $oDaoTriagemAgravo->s167_sau_cid           = $oParam->iCid;
      $oDaoTriagemAgravo->s167_datasintoma       = $sData;
      $oDaoTriagemAgravo->s167_gestante          = $oParam->lGestante == 't' ? 'true' : 'false';

      if ( $oParam->iTriagemAgravo == '' ) {
        $oDaoTriagemAgravo->incluir(null);
      } else {

        $oDaoTriagemAgravo->s167_sequencial = $oParam->iTriagemAgravo;
        $oDaoTriagemAgravo->alterar( $oParam->iTriagemAgravo );
      }

      $oRetorno->message        = _M( MENSAGENS_SAU4_TRIAGEMAGRAVO_RPC . 'agravo_salvo' );
      $oRetorno->iTriagemAgravo = $oDaoTriagemAgravo->s167_sequencial;

      break;

    case 'buscarAgravo':

      $sWhere               = "s167_sau_triagemavulsa = " . $oParam->iTriagemAvulsa;
      $oDaoTriagemAgravo    = new cl_sau_triagemavulsaagravo();
      $sSqlTriagemAgravo    = $oDaoTriagemAgravo->sql_query_file(null, "*", null, $sWhere);
      $rsTriagemAgravo      = db_query( $sSqlTriagemAgravo );
      $oRetorno->lTemAgravo = false;

      if ( pg_num_rows($rsTriagemAgravo) > 0 ) {

        $oDados = db_utils::fieldsMemory($rsTriagemAgravo, 0);
        $oData  = new DBDate($oDados->s167_datasintoma);
        $sData  = $oData->convertTo( DBDate::DATA_PTBR );

        $oCid = new CID( $oDados->s167_sau_cid );
        $sCid = $oCid->getNome();
        $oRetorno->iTriagemAgravo = $oDados->s167_sequencial;
        $oRetorno->iTriagemAvulsa = $oDados->s167_sau_triagemavulsa;
        $oRetorno->iCid           = $oDados->s167_sau_cid;
        $oRetorno->sCid           = urlencode($sCid);
        $oRetorno->dtSintoma      = urlencode($sData);
        $oRetorno->lGestante      = urlencode($oDados->s167_gestante);
        $oRetorno->lTemAgravo     = true;
      }

      break;
  }

  db_fim_transacao();
} catch(Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);