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

/**
* Carregamos as libs necessárias
*/
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

/***
 * Instânciamos o objeto que retorna se há erros ou não na operação
 */
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->aDados  = null;

/**
 * Instanciamos um objeto com todos os dados enviados pelo formulário por JSON
 */
$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

switch ($oParam->sExec) {
  
  /**
   * Buscamos os elementos já cadastrados
   */
  case 'getElementosConfigurados':
      
      $oDaoElementosConfigurados    = db_utils::getDao('configuracaodesdobramentopatrimonio');
      $sCamposElementosConfigurados = " distinct e135_sequencial, o56_elemento, c61_reduz, o56_descr ";
      $sWhereElementosConfigurados  = " c61_instit = ".db_getsession("DB_instit");
      $sSqlElementosConfigurados    = $oDaoElementosConfigurados->sql_query_buscaelementosconfigurados(null, $sCamposElementosConfigurados, 
                                                                                                       null, $sWhereElementosConfigurados);
      $rsElementosConfigurados      = $oDaoElementosConfigurados->sql_record($sSqlElementosConfigurados);
      $aElementosConfigurados       = db_utils::getCollectionByRecord($rsElementosConfigurados);
      foreach ($aElementosConfigurados as $oElemento) {
        
        $oDadosElemento                  = new stdClass();
        $oDadosElemento->e135_sequencial = $oElemento->e135_sequencial;
        $oDadosElemento->o56_elemento    = $oElemento->o56_elemento;
        $oDadosElemento->c61_reduz       = $oElemento->c61_reduz;
        $oDadosElemento->o56_descr       = $oElemento->o56_descr;
        $oRetorno->aDados[]              = $oDadosElemento;
      }
      break;
  
  /**
   * Incluímos um novo elemento
   */
  case 'configurarElemento':
      
      $oDaoElementosConfigurados                     = db_utils::getDao('configuracaodesdobramentopatrimonio');
      $sWhereElementosConfigurados                   = " e135_desdobramento = '{$oParam->sElemento}' ";
      $sSqlElementosConfigurados                     = $oDaoElementosConfigurados->sql_query_file(null, "*", null,
                                                                                                  $sWhereElementosConfigurados);
      $rsElementosConfigurados                       = $oDaoElementosConfigurados->sql_record($sSqlElementosConfigurados);
      if ($oDaoElementosConfigurados->numrows == 0) {
        
        $oDaoElementosConfigurados->e135_sequencial    = null;
        $oDaoElementosConfigurados->e135_desdobramento = $oParam->sElemento;
        $oDaoElementosConfigurados->incluir(null);
        if ($oDaoElementosConfigurados->erro_status == "0") {
          
          $oRetorno->status  = 2;
          $oRetorno->message = $oDaoElementosConfigurados->erro_msg;
        }
      } else {
        
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode('Elemento já configurado.');
      }
    break;
    
  /**
   * Excluímos um elemento existente
   */
  case 'deletarConfiguracaoElemento':
    
      $oDaoElementosConfigurados = db_utils::getDao('configuracaodesdobramentopatrimonio');
      $oDaoElementosConfigurados->excluir($oParam->iSequencial);
      if ($oDaoElementosConfigurados->erro_status == "0") {
      
        $oRetorno->status = 2;
        $oRetorno->message = $oDaoElementosConfigurados->erro_msg;
      }
    break;
}

echo $oJson->encode($oRetorno);