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

require_once ("std/db_stdClass.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_caddocumento_classe.php");
require_once ("classes/db_ativid_classe.php");
require_once ("classes/db_clasativ_classe.php");
require_once ("classes/db_atividcnae_classe.php");
require_once ("classes/db_atividcbo_classe.php");
require_once ("classes/db_ativtipo_classe.php");
require_once ("classes/db_tabativ_classe.php");
require_once ("classes/db_issatividconfdocumento_classe.php");
require_once ("classes/db_cnaeanalitica_classe.php");
require_once ("classes/db_saniatividade_classe.php");
require_once ("classes/db_issgruposervicoativid_classe.php");
require_once ("libs/JSON.php");

db_postmemory($_POST);

$clCadDocumento              = new cl_caddocumento;
$clAtivid                    = new cl_ativid;
$clAtivTipo                  = new cl_ativtipo;
$clClasAtiv                  = new cl_clasativ;
$clTabAtiv                   = new cl_tabativ;
$clSaniAtividade             = new cl_saniatividade;
$clCnaeAnalitica             = new cl_cnaeanalitica;
$clAtividCbo                 = new cl_atividcbo;
$clAtividCnae                = new cl_atividcnae;
$clIssAtividConfDoc          = new cl_issatividconfdocumento;
$clIssGrupoServicoAtivid     = new cl_issgruposervicoativid;

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
switch ($oParam->exec){
  
  case "buscaDocs": 
    $oRetorno->aDocs = array();  
    $sWhere          = "db44_cadtipodocumento = 2";
    $sCampos         = "db44_sequencial, db44_descricao ";
    $sSql            = $clCadDocumento->sql_query("", $sCampos, "", $sWhere);
    $rsDocs          = $clCadDocumento->sql_record($sSql);

    if ($clCadDocumento->numrows == '0') {
      
      $oRetorno->message = "Sem Documentos Cadastrados";
      $oRetorno->status  = 2;
      
    } else {
      // Se o campo Código da Atividade q03_ativ estiver setado entra em modo alteração 
      if (isset($oParam->iCodAtividade) && $oParam->iCodAtividade != 0 ) {
        
        $sWhereIssAtividConf = "q119_ativid = {$oParam->iCodAtividade}"; 
        $sSqlIssAtividConf   = $clIssAtividConfDoc->sql_query("", "q119_caddocumento", "", $sWhereIssAtividConf);
        $rsIssAtividConf     = $clIssAtividConfDoc->sql_record($sSqlIssAtividConf);
        
        for ($ind = 0; $ind < $clCadDocumento->numrows; $ind++) {
        
          $oDocs             = db_utils::fieldsMemory($rsDocs,$ind);
          
          if ($clIssAtividConfDoc->numrows > 0) {
          
            for ($i = 0; $i < $clIssAtividConfDoc->numrows; $i++) {
                           
              $oDados             = new stdClass();
              $oDados             = db_utils::fieldsMemory($rsIssAtividConf,$i);
            
              if ($oDocs->db44_sequencial == $oDados->q119_caddocumento){
                $oDocs->checked = 1;
              }
            }
          }
          $oRetorno->aDocs[] = $oDocs;
        }
        
      } else {
        
        for ($ind = 0; $ind < $clCadDocumento->numrows; $ind++) {
          
          $oDocs             = db_utils::fieldsMemory($rsDocs,$ind);
          $oDocs->checked    = 0;
          $oRetorno->aDocs[] = $oDocs;
          $oRetorno->message = "Busca de Documentos Completa"; 
        }
      }
    }
    break;
    
  case "processaDados":
    
    db_inicio_transacao();
    try {
      
      /**
       * Inclui ou altera dependendo da opção do formulário
       */
      
      $oParam->sDescricao    = utf8_decode(db_stdClass::db_stripTagsJson($oParam->sDescricao));
      $oParam->sObservacao   = utf8_decode(db_stdClass::db_stripTagsJson($oParam->sObservacao));
      
      $clAtivid->q03_descr   = strtoupper(addslashes($oParam->sDescricao));
      $clAtivid->q03_atmemo  = ($oParam->sObservacao != ""?$oParam->sObservacao:null);
      $clAtivid->q03_limite  = ($oParam->dtDataLimit != ""?$oParam->dtDataLimit:null);
      $clAtivid->q03_horaini = ($oParam->sHoraIni != ""?$oParam->sHoraIni:null);
      $clAtivid->q03_horafim = ($oParam->sHoraFim != ""?$oParam->sHoraFim:null) ;
      
      if ($oParam->db_opcao == 1) {
        $clAtivid->incluir($oParam->iCodAtividade);
      } else if ($oParam->db_opcao == 2 || ($oParam->db_opcao == 22)) {
        
        $clAtivid->q03_ativ = $oParam->iCodAtividade;
        $clAtivid->alterar_alterado($oParam->iCodAtividade);
      } 
      
      if ($clAtivid->erro_status == '0') {
        throw new Exception($clAtivid->erro_msg);
      }
      
      //Exclui Classe da entidade clasativ "Classe Ativa"
      if ($oParam->db_opcao == 2 || ($oParam->db_opcao == 22)) {
        $clClasAtiv->excluir($oParam->iCodAtividade, null, null);
        
        if ($clClasAtiv->erro_status == '0') {
          throw new Exception($clClasAtiv->erro_msg);
        }
      }
      
      // Inclui Classe na entidade clasativ "Classe Ativa"
      $clClasAtiv->q82_ativ   = $oParam->iCodAtividade;
      $clClasAtiv->q82_classe = $oParam->iClasse;
      $clClasAtiv->incluir($oParam->iCodAtividade, $oParam->iClasse);
      
      if ($clClasAtiv->erro_status == '0') {
        throw new Exception($clClasAtiv->erro_msg);
      }
      
      //Verifica o Tipo da Pessoa e Inclui/Altera o tipo selecionado
      if ($oParam->sTipoPessoa == 'F') {
        
        $clAtividCbo->excluir($oParam->iCodAtividade, null);
        $clAtividCnae->excluir(null, $oParam->iCodAtividade);
        
        if (isset($oParam->iCBO) && $oParam->iCBO != "" || $oParam->iCBO != 0) {
        
          $clAtividCbo->q75_rhcbo  = $oParam->iCBO;
          $clAtividCbo->q75_ativid = $oParam->iCodAtividade;
          
          $clAtividCbo->incluir($oParam->iCodAtividade, $oParam->iCBO);
          
          if ($clAtividCbo->erro_status == '0') {
            throw new Exception($clAtividCbo->erro_msg);
          } 
        }       
      } else if ($oParam->sTipoPessoa == 'J') {
        
        $clAtividCbo->excluir($oParam->iCodAtividade, null);
        $clAtividCnae->excluir(null, $oParam->iCodAtividade);
        
        if (isset($oParam->iCNAE) && $oParam->iCNAE != "" || $oParam->iCNAE != 0) {
          $clAtividCnae->q74_cnaeanalitica = $oParam->iCNAE;
          $clAtividCnae->q74_ativid        = $oParam->iCodAtividade;
          
          $clAtividCnae->incluir($oParam->iCNAE, $oParam->iCodAtividade);
          
          if ($clAtividCnae->erro_status == '0' ) {
            throw new Exception($clAtividCnae->erro_msg);
          }
        }
      } else {
        
         $clAtividCbo->excluir($oParam->iCodAtividade, null);
         $clAtividCnae->excluir(null, $oParam->iCodAtividade);
      }
      
      //Percorre o array de documentos selecionados e inclui os vinculos
      $sWhereIssAtividConf = "q119_ativid = {$oParam->iCodAtividade}";
      if (isset($oParam->docs) && count($oParam->docs) > 0) {
        
        //Antes de Incluir exclui de todos registros para a atividade 
        $clIssAtividConfDoc->excluir("", $sWhereIssAtividConf);
        
        foreach ($oParam->docs as $iInd => $oDados) {
          
          $clIssAtividConfDoc->q119_caddocumento = $oParam->docs[$iInd]->db44_sequencial;
          $clIssAtividConfDoc->q119_ativid       = $oParam->iCodAtividade;
          
          $clIssAtividConfDoc->incluir(null);
          
          if ($clIssAtividConfDoc->erro_status == '0') {
            throw new Exception($clIssAtividConfDoc->erro_msg);
          }
        }
      } else {
        $clIssAtividConfDoc->excluir("", $sWhereIssAtividConf);
      }
      
      if (isset($oParam->lCalCiss) && $oParam->lCalCiss == 1 || $oParam->lCalCiss == "t") {

        $sWhereIssGrupoServicoAtivid = "q127_ativid = {$oParam->iCodAtividade}";
        $clIssGrupoServicoAtivid->excluir("",$sWhereIssGrupoServicoAtivid);
        
        if ($oParam->sServico != 0) {
        
          $clIssGrupoServicoAtivid->q127_issgruposerviso= $oParam->sServico;
          $clIssGrupoServicoAtivid->q127_ativid= $oParam->iCodAtividade;
          $clIssGrupoServicoAtivid->incluir($clIssGrupoServicoAtivid->q127_sequencial);
          
          if ($clIssGrupoServicoAtivid->erro_status == "0") {
            throw new Exception($clIssGrupoServicoAtivid->erro_msg);
          }
        } 
      } else {
        
        $sWhereGrpServAtiv = "q127_ativid =  {$oParam->iCodAtividade}";
        $clIssGrupoServicoAtivid->excluir('',$sWhereGrpServAtiv);
        if ($clIssGrupoServicoAtivid->erro_status == "0") {
          throw new Exception($clIssGrupoServicoAtivid->erro_msg);
        }
      }
      
      if ($oParam->db_opcao == 1) {
        $oRetorno->message = "Incluido Com Sucesso";  
      } else {
        $oRetorno->message = "Alterado Com Sucesso";
      }
      $oRetorno->altera = 1;

      db_fim_transacao(false);
      
    } catch (Exception $ex) {
      
      db_fim_transacao(true);
      $oRetorno->message = $ex->getMessage(); 
      $oRetorno->status  = 2;
    }
     
    break;
    
  case "processaExclusao":

    db_inicio_transacao();
    try {
      
      /**
       * Verifica se esta sendo utilizada na saniatividade se não exclui
       */
      $sWhereSaniAtividade = "y83_ativ = {$oParam->iCodAtividade}";
      $sSqlSaniAtividade = $clSaniAtividade->sql_query_file("", "", "y83_ativ", "", $sWhereSaniAtividade);
      $clSaniAtividade->sql_record($sSqlSaniAtividade);
      
      if($clSaniAtividade->numrows > 0) {
        throw new Exception("Você não pode excluir esta atividade pois ela esta sendo utiliada.");        
        
      } else {
        $clSaniAtividade->excluir(null, null, $sWhereSaniAtividade);
        if ($clSaniAtividade->erro_status == '0') {
          throw new Exception($clSaniAtividade->erro_msg);
        }
      }
      
      /**
       * Verifica se esta sendo utilizada na tabativ se não exclui
       */
      $sWhereTabAtiv = "q07_ativ = {$oParam->iCodAtividade}";
      $sSqlTabAtiv = $clTabAtiv->sql_query_file("", "", "q07_ativ", "", $sWhereTabAtiv);
      $clTabAtiv->sql_record($sSqlTabAtiv); 
      
      if($clTabAtiv->numrows > 0) {
          throw new Exception("Você não pode excluir esta atividade pois ela esta sendo utiliada.");        
      } else {
        $clTabAtiv->excluir(null, null, $sWhereTabAtiv);
        if ($clTabAtiv->erro_status == '0') {
          throw new Exception($clTabAtiv->erro_msg);
        }
      }
      
      //Exclui Classe da entidade clasativ "Classe Ativa"
      $clClasAtiv->excluir($oParam->iCodAtividade, null, null);
      
      if ($clClasAtiv->erro_status == '0') {
        throw new Exception($clClasAtiv->erro_msg);
      }

      // Exclui da atividcbo
      $clAtividCbo->excluir($oParam->iCodAtividade, null);
      
      if ($clAtividCbo->erro_status == '0') {
        throw new Exception($clAtividCbo->erro_msg );
      }
      // Exclui da atividcnae       
      $clAtividCnae->excluir(null, $oParam->iCodAtividade);
    
      if ($clAtividCnae->erro_status == '0' ) {
        throw new Exception($clAtividCnae->erro_msg );
      }

       //Exclui da entidade ativtipo
      $sWhereAtivTipo = "q80_ativ = {$oParam->iCodAtividade}";
      $clAtivTipo->excluir(null, null, $sWhereAtivTipo);
      
      if ($clAtivTipo->erro_status == '0') {
        throw new Exception($clAtivTipo->erro_msg );
      }
      
      // Exclui da tabela issatividconfdocumento
      $sWhereIssAtividConf = "q119_ativid = {$oParam->iCodAtividade}";
      $clIssAtividConfDoc->excluir("", $sWhereIssAtividConf);
      
      
      // Exclui da entidade issgruposervicoativid
      
      $sWhereGrpServAtiv = " q127_ativid = {$oParam->iCodAtividade} ";
      $clIssGrupoServicoAtivid->excluir("", $sWhereGrpServAtiv);
        
      if ($clIssGrupoServicoAtivid->erro_status = "0") {
        throw new Exception($clIssGrupoServicoAtivid->erro_msg);
      }
      
      // Exclui da entidade ativid
      $clAtivid->excluir($oParam->iCodAtividade);
      
      if ($clAtivid->erro_status == '0') {
        throw new Exception($clAtivid->erro_msg );
      }
      
      $oRetorno->message = "Excluido com Sucesso";
      $oRetorno->altera = 0;
      db_fim_transacao(false);
      
    } catch (Exception $ex) {
      
      db_fim_transacao(true);
      $oRetorno->message = $ex->getMessage(); 
      $oRetorno->status  = 2;
      
    }
    break;  
    
}
$oRetorno->message = urlencode($oRetorno->message);
echo  $oJson->encode($oRetorno);


?>