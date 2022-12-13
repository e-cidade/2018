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

require_once("libs/db_stdlib.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conn.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("classes/db_pcfornetipoidentificacaocredorgenerica_classe.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

$oDaoCredorGenerica = new cl_pcfornetipoidentificacaocredorgenerica();

switch ($oParam->exec) {
  
  case "salvarInscricaoFornecedor":

    db_inicio_transacao();
    try {
      
      /*
       * Valida se a inscriзгo digitada pelo usuбrio jб estб cadastrada para o fornecedor. 
       */
      $sWhereInscricaoCredor     = "    c26_tipoidentificacaocredorgenerica = {$oParam->c25_sequencial}";
      $sWhereInscricaoCredor    .= "and c26_pcforne = {$oParam->pc60_numcgm}";
      $sSqlBuscaInscricaoCredor  = $oDaoCredorGenerica->sql_query_file(null, "*", null, $sWhereInscricaoCredor);
      $rsBuscaInscricaoCredor    = $oDaoCredorGenerica->sql_record($sSqlBuscaInscricaoCredor);
      if ($oDaoCredorGenerica->numrows > 0) {
        throw new Exception("Inscriзгo jб cadastrada para este fornecedor.");
      }
      
      $oDaoCredorGenerica->c26_sequencial                      = null;
      $oDaoCredorGenerica->c26_tipoidentificacaocredorgenerica = $oParam->c25_sequencial;
      $oDaoCredorGenerica->c26_pcforne                         = $oParam->pc60_numcgm;
      $oDaoCredorGenerica->incluir(null);
      if ($oDaoCredorGenerica->erro_status == 0) {
        
        $sMsgErroInclusao = "Ocorreu um erro ao incluir a inscriзгo genйrica para o fornecedor.\n\n{$oDaoCredorGenerica->erro_msg}";
        throw new Exception($sMsgErroInclusao);
      }
      
      $oRetorno->message = urlencode("Inscriзгo incluнda com sucesso!");
      db_fim_transacao(false);
      
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 2;
    }
    
  break;
  
  case "getInscricoesCadastradas":
    
    $sCamposInscricoes     = "c26_sequencial, c25_sequencial, c25_descricao";
    $sWhereInscricoes      = "c26_pcforne = {$oParam->pc60_numcgm}";
    $sSqlBuscaInscricoes   = $oDaoCredorGenerica->sql_query(null, $sCamposInscricoes, "c26_sequencial", $sWhereInscricoes);
    $rsBuscaInscricao      = $oDaoCredorGenerica->sql_record($sSqlBuscaInscricoes);
    $aColecaoInscricoes    = db_utils::getCollectionByRecord($rsBuscaInscricao);
    $oRetorno->aInscricoes = $aColecaoInscricoes;
    
  break;
  
  case "excluirInscricao":
    
    $oDaoCredorGenerica->excluir($oParam->c26_sequencial);
    if ($oDaoCredorGenerica->erro_status == 0) {
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode("Ocorreu um erro ao excluir incriзгo do fornecedor.\n\n{$oDaoCredorGenerica->erro_msg}");
    }
    $oRetorno->message = urlencode("Inscriзгo excluнda com sucesso.");
  break;
}
echo $oJson->encode($oRetorno);
?>