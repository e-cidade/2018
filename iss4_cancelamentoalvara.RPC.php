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

require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("model/issqn/alvara/MovimentacaoAlvaraFactory.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

switch ($oParam->exec) {

	/**
	 * Faz a consulta do último movimento do alvará e o retorna para o formulário
	 */
	case "buscaUltimaMovimentacao" :

		try {

      $oAlvara        = new Alvara($oParam->q120_issalvara);
      $aMovimentacoes = $oAlvara->getMovimentacoes();

      if ( count($aMovimentacoes) == 0 ) {
        throw new Excption("Não existem movimentações para este alvara");
      }

      /**
       * Ultima movimentacao
       */
      $oMovimentacao        = $aMovimentacoes[0];
      $oDaoisstipomovalvara = db_utils::getDao('issmovalvara');
      $sSqlTipoMovimentacao = $oDaoisstipomovalvara->sql_query($oMovimentacao->getCodigo(), 'q121_descr');
      $rsTipoMovimentacao   = $oDaoisstipomovalvara->sql_record($sSqlTipoMovimentacao);

      if ( $oDaoisstipomovalvara->erro_status == "0" ) {
        throw new DBException('Erro ao bucar tipo de movimentação.');
      }

      $oDadosTipoMovimentacao     = db_utils::fieldsMemory($rsTipoMovimentacao, 0);
      $sDescricaoTipoMovimentacao = $oDadosTipoMovimentacao->q121_descr;

      $oRetorno->aUltimaMovimentacao = array(
        "q121_descr"            => $sDescricaoTipoMovimentacao,
        "q120_dtmov"            => $oMovimentacao->getDataMovimentacao(),
        "q120_sequencial"       => $oMovimentacao->getCodigo(),
        "q120_isstipomovalvara" => $oMovimentacao->getTipoMovimentacao()
      );

    } catch (ErrorException $eErro){

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }

  break;

	/**
	 * Efetua o cancelamento do alvará
	 */
	case "cancelaUltimaMovimentacao" :

	  db_inicio_transacao();

	  try {

	    $aTransformacoesDeTipos[1] = 6;
	    $aTransformacoesDeTipos[2] = 7;
      $aTransformacoesDeTipos[4] = 8;

	    if ( !array_key_exists($oParam->q120_isstipomovalvara, $aTransformacoesDeTipos) ) {
        throw new Exception('O tipo do alvará não pode ser cancelado.');
      } 

      $oAlvara = new Alvara($oParam->q120_issalvara);

      $oCancelamentoAlvara = $oAlvara->incluirMovimentacao( $aTransformacoesDeTipos[$oParam->q120_isstipomovalvara] );
      $oCancelamentoAlvara->setUsuario( new UsuarioSistema(db_getsession('DB_id_usuario')) );
      $oCancelamentoAlvara->setValidadeAlvara(0);
	    $oCancelamentoAlvara->setDataMovimentacao(date('Y-m-d', db_getsession('DB_datausu')));
      $oCancelamentoAlvara->setObservacao($oParam->q120_obs);

	    foreach ($oParam->aDocumentos as $iDoc) {
	   	  $oAlvara->addDocumento($iDoc);
	    }

      $oCancelamentoAlvara->processar();
      $oRetorno->message = urlencode("Cancelamento de alvará efetuado com sucesso.");

	 	  db_fim_transacao(false);

	   } catch (Exception $eErro){

	     $oRetorno->status  = 2;
	     $oRetorno->message = urlencode($eErro->getMessage());
	     db_fim_transacao(true);
     }

  break;

}

echo $oJson->encode($oRetorno);
?>