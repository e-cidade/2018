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
require_once("model/pessoal/std/DBPessoal.model.php");

define('MENSAGENS', 'recursoshumanos.pessoal.pes1_faixafaltasperiodoaquisitivo.');

$oJson                = new services_json();
$oParametros          = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->iStatus    = 1;
$oRetorno->sMensagem  = '';
$oHoje                = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));
$aRegistros           = array();

$oDaoFaixaFaltas      = new cl_rhcadregimefaltasperiodoaquisitivo();

try {

  db_inicio_transacao();

  if( !db_utils::inTransaction() ){
    throw new DBException(_M( MENSAGENS . 'sem_transacao_ativa'));
  }
  switch ($oParametros->sExecucao) {

    /**
     * Altera faixas de periodo aquisitivo
     */
    case 'alterar' :
    case 'incluir' :

      $oDaoFaixaFaltas->rh125_sequencial   = $oParametros->rh125_sequencial;   
      $oDaoFaixaFaltas->rh125_rhcadregime  = $oParametros->rh52_regime;   
      $oDaoFaixaFaltas->rh125_faixainicial = $oParametros->rh125_faixainicial; 
      $oDaoFaixaFaltas->rh125_faixafinal   = $oParametros->rh125_faixafinal;   
      $oDaoFaixaFaltas->rh125_diasdesconto = $oParametros->rh125_diasdesconto; 
      if ( $oParametros->sExecucao == 'alterar' ) {
        $oDaoFaixaFaltas->alterar( $oParametros->rh125_sequencial ); 
      } else {
        $oDaoFaixaFaltas->incluir( null ); 
      }

    break;
    case 'excluir' : 
         
      $sSqlFaixaFaltas  = $oDaoFaixaFaltas->sql_query_faixas_periodos( $oParametros->rh125_sequencial );
      $rsDAOFaixaFaltas = db_query( $sSqlFaixaFaltas );
      
      if(!$rsDAOFaixaFaltas){
      	 
      	$oMensagem = (object)array('sErro'=>pg_last_error());
      	throw new DBException( _M( MENSAGENS . 'erro_buscar_dados_faixas') );
      }
      
      $aRegistros = db_utils::getCollectionByRecord($rsDAOFaixaFaltas, false, false, true);
      
      foreach ($aRegistros as $aRegistro){
      	
        if( $aRegistro->total_registros > 0 ){
        	throw new BusinessException( _M( MENSAGENS . 'faixa_periodo_com_servidores_cadastrados') );
        }
      }
      $oDaoFaixaFaltas->rh125_sequencial = $oParametros->rh125_sequencial;
      $oDaoFaixaFaltas->excluir( $oParametros->rh125_sequencial );   
    break;
  }


  if ( $oDaoFaixaFaltas->erro_status == 0 ) {
    throw new BusinessException( $oDaoFaixaFaltas->erro_msg );
  }

  db_fim_transacao(false);
  echo $oJson->encode($oRetorno);
  exit;
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);