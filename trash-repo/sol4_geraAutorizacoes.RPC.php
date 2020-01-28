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
require_once("libs/db_app.utils.php");
require_once("libs/db_conn.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("std/db_stdClass.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_solicitem_classe.php");
require_once("classes/db_solicita_classe.php");
require_once("model/itemSolicitacao.model.php");
require_once("model/Dotacao.model.php");
require_once("model/licitacao.model.php");
require_once("classes/solicitacaocompras.model.php");
require_once("model/empenho/AutorizacaoEmpenho.model.php");
require_once("model/CgmFactory.model.php");
require_once("model/ProcessoCompras.model.php");
require_once("classes/db_pcproc_classe.php");


$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

switch ($oParam->exec) {

  case "getTipoLicitacao":

    $oDaoCfgLiclicita          = db_utils::getDao("cflicita");
    $sSqlTipoLicitacao         = $oDaoCfgLiclicita->sql_query_file(null,"l03_tipo, l03_descr", '', "l03_codcom = {$oParam->iTipoCompra}");
    $rsTipoLicitacao           = $oDaoCfgLiclicita->sql_record($sSqlTipoLicitacao);
    $oRetorno->aTiposLicitacao = array();

    if ($oDaoCfgLiclicita->numrows > 0) {

      for ($iTipoLicitacao = 0; $iTipoLicitacao < $oDaoCfgLiclicita->numrows; $iTipoLicitacao++) {
        $oRetorno->aTiposLicitacao[] = db_utils::fieldsMemory($rsTipoLicitacao, $iTipoLicitacao);
      }
    }

  break;
  
  /**
   * Busca os itens de uma solicitação de compra para que seja feita a geração de empenho
   */
  case "getItensParaAutorizacao":
    try {
      
      $oSolicitacao     = new solicitacaoCompra($oParam->iCodigo);
      $oRetorno->aItens = $oSolicitacao->getItensParaAutorizacao();
      
    } catch (Exception $eErro) {
      
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
  break;
  
  /**
   * Gera autorização de empenho para os itens selecionados
   */
  case "gerarAutorizacoes":
  
    try {

      /**
       * corrigimos as strings antes de salvarmos os dados
       */
      foreach ($oParam->aAutorizacoes as $oAutorizacao) {
      	 
        $oAutorizacao->destino           = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->destino))));
        $oAutorizacao->sContato          = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->sContato))));
        $oAutorizacao->sOutrasCondicoes  = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->sOutrasCondicoes))));
        $oAutorizacao->condicaopagamento = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->condicaopagamento))));
        $oAutorizacao->prazoentrega      = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->prazoentrega))));
        $oAutorizacao->resumo            = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->resumo))));
        
        foreach ($oAutorizacao->itens as $oItem) {
          $oItem->observacao = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oItem->observacao))));
        }
      }

      db_inicio_transacao();
      $oSolicitacaoCompra     = new solicitacaoCompra($oParam->iCodigo);
      $oRetorno->autorizacoes = $oSolicitacaoCompra->gerarAutorizacoes($oParam->aAutorizacoes);
      db_fim_transacao(false);
      
      $oRetorno->status  = 1;
      $oRetorno->message = urlencode("Autorização efetuada com sucesso.");
      
    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);
    }
    
  break;
}


echo $oJson->encode($oRetorno);
?>