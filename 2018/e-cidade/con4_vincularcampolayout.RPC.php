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
require_once("std/db_stdClass.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_avaliacaoperguntaopcaolayoutcampo_classe.php");

include("libs/JSON.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$oDaoAvaliacaoPerguntaOpcaoLayoutCampo = db_utils::getDao('avaliacaoperguntaopcaolayoutcampo');

switch ($oParam->exec) {
  
  /*
   * Incluir novo Vínculo
   */
  case 'incluirVinculo':
        
    db_inicio_transacao();
    try {
      
      $oDaoAvaliacaoPerguntaOpcaoLayoutCampo->ed313_db_layoutcampo         = $oParam->dados->iLayoutCampo;
      $oDaoAvaliacaoPerguntaOpcaoLayoutCampo->ed313_avaliacaoperguntaopcao = $oParam->dados->iResposta;
      $oDaoAvaliacaoPerguntaOpcaoLayoutCampo->ed313_ano                    = $oParam->dados->iAno;
      $oDaoAvaliacaoPerguntaOpcaoLayoutCampo->ed313_layoutvalorcampo       = $oParam->dados->sValor;
      
      $oDaoAvaliacaoPerguntaOpcaoLayoutCampo->incluir(null);
      
      if($oDaoAvaliacaoPerguntaOpcaoLayoutCampo->erro_status == 0) {
        
        throw new Exception($oDaoAvaliacaoPerguntaOpcaoLayoutCampo->erro_msg);
      } else {
        
        $oRetorno->status = 1;
        db_fim_transacao(false);
        
      }
      
    } catch (Exception $oExcecao) {
      
      $oRetorno->status = 2;
      db_fim_transacao(true);
      $oRetorno->message = urlencode(str_replace('"', '\"', $oExcecao->getMessage()));
      
    }
    
    break;
  
  /*
   * Listar todos os vínculos
   */
  case 'listarVinculos':

    db_inicio_transacao();
    $sCamposBuscaVinculos  = "db_layoutcampos.db52_nome, avaliacaoperguntaopcao.db104_descricao, ";
    $sCamposBuscaVinculos .= "db_layoutlinha.db51_descr, db_layouttxt.db50_descr, ";
    $sCamposBuscaVinculos .= " avaliacaoperguntaopcaolayoutcampo.ed313_sequencial, ";
    $sCamposBuscaVinculos .= " avaliacaoperguntaopcaolayoutcampo.ed313_layoutvalorcampo";
    $sWhereBuscaVinculos   = "";
    $sSqlBuscaVinculos     = $oDaoAvaliacaoPerguntaOpcaoLayoutCampo->sql_query_campo (
                                                                                      null,
                                                                                      $sCamposBuscaVinculos,
                                                                                      "ed313_sequencial desc",
                                                                                      $sWhereBuscaVinculos
                                                                                     );
    
    $rsBuscaVinculos       = $oDaoAvaliacaoPerguntaOpcaoLayoutCampo->sql_record ($sSqlBuscaVinculos);
    $aBuscaVinculos        = db_utils::getCollectionByRecord ($rsBuscaVinculos, false, false, true);
    $oRetorno->aDados      = $aBuscaVinculos;
    db_fim_transacao();
    
    break;
    
  /*
   * Excluir vínculo
   */ 
  case 'excluirVinculo':

    db_inicio_transacao();
    try {

      $oDaoAvaliacaoPerguntaOpcaoLayoutCampo->excluir($oParam->iCodigoVinculo);
      if ($oDaoAvaliacaoPerguntaOpcaoLayoutCampo->erro_status == 0) {
        throw new Exception($oDaoAvaliacaoPerguntaOpcaoLayoutCampo->erro_msg);
      }
      db_fim_transacao();
      
    } catch (Exception $eErro) {

      $oRetorno->status = 2;
      db_fim_transacao();
      $oRetorno->message = urlencode($eErro->getMessage());
      
    }
    
    break;
}
echo $oJson->encode($oRetorno);
?>