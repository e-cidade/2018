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
/**
 * Carregamos a DAO necessária para efetuar as operações do RPC
 */
$oDaoSolicitaPendencia  = db_utils::getDao('solicitapendencia');
/**
 * Instânciamos o ojeto que retorna se há erros ou não na operação
 */
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->aDados  = null;
/**
 * Instanciamos um objeto com todos os dados enviados pelo formulário através do JSON
 */
$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

switch ($oParam->sExec) {
  
  /**
   * Busca todas as pendências da solicitação passada por parâmetro (pc10_numero)
   */
  case 'getPendenciasSolicitacao':
    
    $sCamposBuscaPendencias  = " solicitapendencia.pc91_sequencial, solicitapendencia.pc91_pendencia, ";
    $sCamposBuscaPendencias .= " solicitapendencia.pc91_datainclusao, db_usuarios.nome ";
    $sWhereBuscaPendencias   = " pc91_solicita = {$oParam->iSolicitacao} ";
    $sSqlBuscaPendencias     = $oDaoSolicitaPendencia->sql_query(null, $sCamposBuscaPendencias, 
                                                                 null, $sWhereBuscaPendencias);
    $rsBuscaPendencias       = $oDaoSolicitaPendencia->sql_record($sSqlBuscaPendencias);
    if ($oDaoSolicitaPendencia->numrows > 0) {
      
      $aPendenciasRetornadas = db_utils::getCollectionByRecord($rsBuscaPendencias);
      foreach ($aPendenciasRetornadas as $oPendencia) {
        
        $oDadosPendencia = new stdClass();
        $oDadosPendencia->pc91_sequencial   = $oPendencia->pc91_sequencial;
        $oDadosPendencia->pc91_pendencia    = substr($oPendencia->pc91_pendencia, 0, 30);
        $oDadosPendencia->pc91_datainclusao = db_formatar($oPendencia->pc91_datainclusao, 'd');
        $oDadosPendencia->nome              = substr($oPendencia->nome, 0, 30);
        $oRetorno->aDados[] = $oDadosPendencia;
      }
    } else {
      
      $oRetorno->status = 2;
      $oRetorno->message = urlencode('Não há pendencias para essa solicitação.');
    }
    break;
    
  /**
   * Busca apenas uma pendência para ser exibida individualmente
   */
  case 'buscaPendenciaUnica':
    
    $sCamposBuscaPendencias  = " solicitapendencia.pc91_sequencial, solicitapendencia.pc91_pendencia, ";
    $sCamposBuscaPendencias .= " solicitapendencia.pc91_solicita, solicitapendencia.pc91_datainclusao ";
    $sWhereBuscaPendencias   = " pc91_sequencial = {$oParam->iIdPendencia} ";
    $sSqlBuscaPendencias     = $oDaoSolicitaPendencia->sql_query(null, $sCamposBuscaPendencias,
                                                                 null, $sWhereBuscaPendencias);
    $rsBuscaPendencias       = $oDaoSolicitaPendencia->sql_record($sSqlBuscaPendencias);
    if ($oDaoSolicitaPendencia->numrows > 0) {
      
      $oPendencia                         = db_utils::fieldsMemory($rsBuscaPendencias, 0);
      $oDadosPendencia                    = new stdClass();
      $oDadosPendencia->pc91_sequencial   = $oPendencia->pc91_sequencial;
      $oDadosPendencia->pc91_pendencia    = $oPendencia->pc91_pendencia;
      $oDadosPendencia->pc91_solicita     = $oPendencia->pc91_solicita;
      $oDadosPendencia->pc91_datainclusao = db_formatar($oPendencia->pc91_datainclusao, 'd');
      $oRetorno->aDados[]                 = $oDadosPendencia;
    }
    break;
    
  /**
   * Inclui pendência à solicitação passada por parâmetro (pc10_numero)
   */
  case 'incluirPendencia':
    
    $oDaoSolicitaPendencia->pc91_solicita     = $oParam->iSolicitacao;
    $oDaoSolicitaPendencia->pc91_usuario      = db_getsession('DB_id_usuario');
    $oDaoSolicitaPendencia->pc91_pendencia    = $oParam->sPendencia;
    $oDaoSolicitaPendencia->pc91_datainclusao = $oParam->sDataInclusao;
    $oDaoSolicitaPendencia->incluir(null);
    
    if ($oDaoSolicitaPendencia->erro_status == 0) {
      
      $oRetonro->status  = 2;
      $oRetorno->message = "Erro ao salvar dados da Pendência.\n{$oDaoSolicitaPendencia->erro_msg}";
    } else {
      
      $oRetorno->status = 1;
      /**
       * Buscamos os itens da solicitação para alterar o campo pc11_libera para falso
       */
      $sCamposBuscaItensSolicitacao = " distinct solicitem.pc11_codigo ";
      $sWhereBuscaItensSolicitacao  = " pc91_solicita = {$oParam->iSolicitacao} ";
      $sSqlBuscaItensSolicitacao    = $oDaoSolicitaPendencia->sql_query_itens_solicitacao(null, $sCamposBuscaItensSolicitacao,
                                                                                          null, $sWhereBuscaItensSolicitacao);
      $rsBuscaItensSolicitacao      = $oDaoSolicitaPendencia->sql_record($sSqlBuscaItensSolicitacao);
      if ($oDaoSolicitaPendencia->numrows > 0) {
        
        $aItensSolicita = db_utils::getCollectionByRecord($rsBuscaItensSolicitacao);
        $oDaoSolicitem  = db_utils::getDao('solicitem');
        foreach ($aItensSolicita as $oItemSolicita) {
          
          $oDaoSolicitem->pc11_codigo   = $oItemSolicita->pc11_codigo;
          $oDaoSolicitem->pc11_liberado = 'false';
          $oDaoSolicitem->alterar($oItemSolicita->pc11_codigo);
        }
      } 
    }
    break;
  
  /**
   * Altera a pendência passada por parâmetro (pc91_sequencial)
   */
  case 'alterarPendencia':
    
    $oDaoSolicitaPendencia->pc91_sequencial   = $oParam->iCodigoPendencia; 
    $oDaoSolicitaPendencia->pc91_pendencia    = $oParam->sPendencia;
    $oDaoSolicitaPendencia->alterar($oParam->iCodigoPendencia);
    
    if ($oDaoSolicitaPendencia->erro_status == 0) {
    
      $oRetorno->status  = 2;
      $oRetorno->message = "Erro ao salvar dados da Pendência.\n{$oDaoSolicitaPendencia->erro_msg}";
    } else {
      $oRetorno->status = 1;
    }
    break;
  
  /**
   * Exclui a pendência passada por parâmetro (pc91_sequencial)
   */
  case 'excluirPendencia':
    
    $oDaoSolicitaPendencia->excluir($oParam->iIdPendencia);
    if ($oDaoSolicitaPendencia->numrows_excluir == 0) {
      
      $oRetorno->status = 2;
      $oRetorno->message = $oDaoSolicitaPendencia->erro_msg;
    }
    break;
}

echo $oJson->encode($oRetorno);