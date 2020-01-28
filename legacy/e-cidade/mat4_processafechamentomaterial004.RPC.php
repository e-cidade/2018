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
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php"); 
require_once ("model/patrimonio/material/PosicaoEstoque.model.php");
require_once ("model/patrimonio/material/PosicaoEstoqueProcessamento.model.php");
require_once ("std/DBDate.php");
require_once ("model/configuracao/Instituicao.model.php");

define ("URL_PROCESSO_FECHAMENTO_MATERIAL", "patrimonial.material.mat4_processafechamentomaterial004.");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';


try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    case "processarFechamentoMaterial":

      db_putsession("DB_desativar_account", true);
      $oInstituicao                 = new Instituicao(db_getsession("DB_instit"));
      $dtUltimoProcessamento        = PosicaoEstoqueProcessamento::getDataUltimoProcessamento($oInstituicao);
      $oDataProcessamentoAtual      = new DBDate($oParam->dtProcessamento);
      $oPosicaoEstoqueProcessamento = PosicaoEstoqueProcessamento::getInstanciaPorData($oDataProcessamentoAtual, $oInstituicao);

      $oPosicaoEstoqueProcessamento->setCodigoUsuario(db_getsession("DB_id_usuario"));
      $oPosicaoEstoqueProcessamento->setDataProcessamento($oDataProcessamentoAtual);
      $oPosicaoEstoqueProcessamento->setCodigoInstituicao($oInstituicao->getSequencial());
      $oPosicaoEstoqueProcessamento->salvar();
      $oRetorno->sMessage = urlencode( _M(URL_PROCESSO_FECHAMENTO_MATERIAL."sucesso_processamento"));

      db_destroysession("DB_desativar_account");
      
      break;

    case "getDataUltimoProcessamento":

      $oInstituicao             = new Instituicao(db_getsession("DB_instit"));
      $oDataUltimoProcessamento = PosicaoEstoqueProcessamento::getDataUltimoProcessamento($oInstituicao);

      $dtRetorno = "";
      if ($oDataUltimoProcessamento) {
        $dtRetorno = $oDataUltimoProcessamento->getDate(DBDate::DATA_PTBR);
      }

      $oRetorno->dtUltimoProcessamento = $dtRetorno;
  }
  
  db_fim_transacao(false);
    
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
?>