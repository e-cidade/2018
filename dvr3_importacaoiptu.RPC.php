<?
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
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_app.utils.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$lErro             = false;
$sMsg              = '';
$dtHoje            = date("Y-m-d", db_getsession("DB_datausu"));

db_app::import('diversos.ImportacaoDiversos');
db_app::import('diversos.ImportacaoGeralDiversos');
db_app::import('exceptions.*');

switch ($oParam->sExec) {
  
  case 'getDebitosImportados' :
    
    $oDaoDiverImporta    = new cl_diverimporta();
    
    if ($oParam->iTipoPesquisa == 5) {
      
      $sSqlDaoDiverImporta = $oDaoDiverImporta->sql_queryDebitosImportadosAlvara($oParam->iChavePesquisa);
    } else {
      
      $sSqlDaoDiverImporta = $oDaoDiverImporta->sql_query_debitos_importados($oParam->iTipoPesquisa, $oParam->iChavePesquisa);
    }
    
    $rsDaoDiverImporta   = $oDaoDiverImporta->sql_record($sSqlDaoDiverImporta);
    
    if ($oDaoDiverImporta->numrows > 0) {
    
      $oRetorno->aDebitos = db_utils::getCollectionByRecord($rsDaoDiverImporta, false, false, true);
      $oDaoProcdiver      = new cl_procdiver();
    } else {
    
      $oRetorno->status  = 2;
      $oRetorno->message = 'Nenhum registro encontrado.';
    }
    
    break;

  /**
   * Cancelamento de importação de diversos
   */  
  case 'cancelaImportacao':

    try {
      
      db_inicio_transacao();
      
      $sMensagem = 'cancelamento_sucesso';
      
      foreach ( $oParam->aCodigosImportacao as $iCodigoImportacao ) {
      	
      	$oImportacaoDiversos = new ImportacaoDiversos($iCodigoImportacao);
      	if ( $oImportacaoDiversos->cancelar() == ImportacaoDiversos::CANCELAMENTO_PARCIAL ) {
      	  $sMensagem = "cancelamento_parcial";
      	}
      }
      
      db_fim_transacao();
      $oRetorno->status  = 1;
      $oRetorno->message = urlencode(_M('tributario.diversos.ImportacaoDiversos.' . $sMensagem ));
      
    } catch (Exception $sException) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($sException->getMessage()); 
    }
    
    break;

  //lista debitos da matricula
  case 'getDebitos':

    $oDaoDiverImporta    = new cl_diverimporta();

    if ($oParam->iTipoPesquisa == 5) {
      
      $sSqlDaoDiverImporta = $oDaoDiverImporta->sql_queryImportacaoAlvara( $oParam->aChavePesquisa );
      
    } else {
      
      $sSqlDaoDiverImporta = $oDaoDiverImporta->sql_query_importa_iptu( $oParam->iTipoPesquisa, $oParam->aChavePesquisa );
      
    }
    
    $rsDaoDiverImporta   = $oDaoDiverImporta->sql_record( $sSqlDaoDiverImporta );

    if ($oDaoDiverImporta->numrows > 0) {

      $oRetorno->aDebitos = db_utils::getCollectionByRecord($rsDaoDiverImporta, false, false, true);
        
      $oDaoProcdiver  = new cl_procdiver();
      
      $sSqlProcDiver  = $oDaoProcdiver->sql_query_file(null,
                                                       "dv09_procdiver, dv09_descra, dv09_descr, dv09_tipo",
                                                       "dv09_procdiver,dv09_descra",
                                                       "(dv09_dtlimite is null or dv09_dtlimite >= '{$dtHoje}') and dv09_instit = ". db_getsession("DB_instit"));
      
      $rsDAOProcdiver = $oDaoProcdiver->sql_record($sSqlProcDiver);
        
      if ($oDaoProcdiver->numrows > 0) {
        $oRetorno->aProcdiver = db_utils::getCollectionByRecord($rsDAOProcdiver, false, false, true);
      }

    } else {
        
      $oRetorno->status  = 2;
        
      $oRetorno->message = 'Nenhum registro encontrado.';
        
    }

    break;
    

  /**
   * Importação de diversos
   */
  case 'processarDebitos':
		
	  $oDadosProcessamento = $oParam->oDadosProcessamento;
    $lDesativarAccount   = db_getsession("DB_desativar_account", false);
    
    try {

      db_putsession("DB_desativar_account", false);
      
      db_inicio_transacao();
      
      $oProcedenciaDiversos = new ImportacaoDiversos(null);
      
      /**
       * Tipo de pesquisa
       * 1 - CODIGO IMPORTACAO
       * 2 - MATRICULA
       * 3 - CGM
       * 4 - Debitos (vindos da CGF)
       * 5 - Inscrição
       */
      $sObservacao = "";
      $oProcedenciaDiversos->setTipoOrigem($oDadosProcessamento->iTipoPesquisa);
      $oProcedenciaDiversos->setCodigoOrigem($oDadosProcessamento->aChavePesquisa[0]);

      
      
      $sObservacao = $oDadosProcessamento->sObservacoes;
      
      foreach ( $oDadosProcessamento->aDebitos as $oDebito ) {
        
        $oProcedenciaDiversos->importarDiversos($oDebito->iCodigoProcedencia, 
                                                $oDebito->iNumpre,
                                                $oDebito->iNumpar, 
                                                $oDebito->iReceita);
      }
      $oProcedenciaDiversos->processar($sObservacao);
      
      db_fim_transacao();
      
      
      $oRetorno->status  = 1;
      $oRetorno->message = urlencode("Débitos processados com sucesso.");
      
    } catch (Exception $sException) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($sException->getMessage());
    }
    
    db_putsession("DB_desativar_account", $lDesativarAccount);
    
    break;
    
    /**
     * Busca Receitas
     */
    case 'getReceitasProcedencias':
    	
    	try{
	    	
	    	$oDaoProcdiver  = new cl_procdiver();
	    	$oDaoArrecad    = new cl_arrecad();
	    	
	    	$sSqlProcDiver  = $oDaoProcdiver->sql_query_file(null,
																							    			 "dv09_procdiver, dv09_descra",
																							    			 "dv09_descra",
																							    			 "(dv09_dtlimite is null or dv09_dtlimite >= '{$dtHoje}') and dv09_instit = ". db_getsession("DB_instit"));
	    	
	    	$sSqlArrecad    = $oDaoArrecad->sql_query_getReceitasTipo($oParam->iCadTipo);
	    	
	    	$rsDAOProcdiver = db_query($sSqlProcDiver);
	    	
	    	if(!$rsDAOProcdiver){
	    		
	    		$oMensagem = (object)array('sErro'=>pg_last_error());
	    		throw new DBException(_M('tributario.diversos.dvr3_importacaoiptu.erro_buscar_dados_procedencia',$oMensagem));
	    	}
	    	
	    	if(pg_num_rows($rsDAOProcdiver) == 0){
	    		throw new BusinessException(_M('tributario.diversos.dvr3_importacaoiptu.nenhuma_procedencia_encontrada'));
	    	}
	    	
	    	$rsDAOArrecad = db_query($sSqlArrecad);
	    	
	    	if(!$rsDAOArrecad){
	    		
	    		$oMensagem = (object)array('sErro'=>pg_last_error());
	    		throw new DBException(_M('tributario.diversos.dvr3_importacaoiptu.erro_buscar_dados_receitas',$oMensagem));
	    	}
	    	
	    	if(pg_num_rows($rsDAOArrecad) == 0){
	    		throw new BusinessException(_M('tributario.diversos.dvr3_importacaoiptu.nenhuma_receita_encontrada'));
	    	}
	    	
	    	$oRetorno->aReceitas     = db_utils::getCollectionByRecord($rsDAOArrecad,   false, false, true);
	    	$oRetorno->aProcedencias = db_utils::getCollectionByRecord($rsDAOProcdiver, false, false, true);
	    		    	
	    } catch (Exception $oException) {
	    	
	    	$oRetorno->status  = 2;
	    	$oRetorno->message = urlencode($oException->getMessage());
	    	echo $oJson->encode($oRetorno);
	    	exit;
	    }
	    
    break;
    
    /**
     * Busca Receitas
     */
    case 'getImportacaoGeral':
    	 
    	try{
    
    		$oDaoDiverimporta  = new cl_diverimporta();
    		
    		$sWhere				  = '     exists (select 1 from diverimportareg where dv12_diverimporta = dv11_sequencial)';
    		$sWhere				 .= ' and (extract(year from dv11_data) >= '.db_getsession('DB_anousu');
    		$sWhere        .= ' and dv11_instit = '. db_getsession("DB_instit") .')';
    		$sSqlDiverimporta  = $oDaoDiverimporta->sql_query_file( null,
																										    				"dv11_sequencial, dv11_data, dv11_hora, dv11_tipo, dv11_obs",
																										    				"dv11_sequencial",
																										    				$sWhere );
    		
    		$rsDAODiverimporta = db_query($sSqlDiverimporta);
    
    		if(!$rsDAODiverimporta){
    	   
    			$oMensagem = (object)array('sErro'=>pg_last_error());
    			throw new DBException(_M('tributario.diversos.dvr3_importacaoiptu.erro_buscar_importacoes',$oMensagem));
    		}
    
    		if(pg_num_rows($rsDAODiverimporta) == 0){
    			throw new BusinessException(_M('tributario.diversos.dvr3_importacaoiptu.nenhuma_importacao_geral_encontrada'));
    		}
    
    		$oRetorno->aImportacoes  = db_utils::getCollectionByRecord($rsDAODiverimporta,   false, false, true);
    
    	} catch (Exception $oException) {
    
    		$oRetorno->status  = 2;
    		$oRetorno->message = urlencode($oException->getMessage());
    		echo $oJson->encode($oRetorno);
    		exit;
    	}
    	 
    break;
    
    /**
     * Importação geral de diversos
     */
    case 'importacaoGeralDiversos':
    	 
    	try {
    	
    		$aDadosImportacao    = $oParam->aDados;
    		$iQuantidadeParcelas = $oParam->iQuantidadeParcelas;
    		$sObservacoes				 = $oParam->sObservacoes;
    		
   		  $oImportacao = new ImportacaoGeralDiversos(null);
   		  
    		foreach ( $aDadosImportacao as $aDadoImportacao ){
    			
    			if(empty($aDadoImportacao->iCodigoProcedencia)){
    				continue;
    			}
    			$oData = $aDadoImportacao->iVencimento == '' ? null : new DBDate($aDadoImportacao->iVencimento);
    		  $oImportacao->adicionarReceita( $aDadoImportacao->iCodigoReceita, $oData, new ProcedenciaDiversos($aDadoImportacao->iCodigoProcedencia) );
    	  }
        
        if ( !empty($iQuantidadeParcelas) ) {
         $oImportacao->setQuantidadeParcelas( $iQuantidadeParcelas );
        }

    		$oImportacao->setObservacoes( $sObservacoes );
    		
    		db_inicio_transacao();
    		$oImportacao->processar(true);
    		db_fim_transacao();
    		
    		$oRetorno->status  = 1;
    		$oRetorno->message = urlencode(_M('tributario.diversos.dvr3_importacaoiptu.sucesso_importacao_geral'));
    		echo $oJson->encode($oRetorno);
    		exit;
    	} catch (Exception $oException) {
	    	
    		db_fim_transacao(true);
	    	$oRetorno->status  = 2;
	    	$oRetorno->message = urlencode($oException->getMessage());
	    	echo $oJson->encode($oRetorno);
	    	exit;
	    }
    	
    break;
}

echo $oJson->encode($oRetorno);