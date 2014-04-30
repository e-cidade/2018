<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_meiimporta_classe.php");
require_once("classes/db_meiimportasemmov_classe.php");
require_once("classes/db_parissqn_classe.php");

$oJson             = new services_json();
$oRetorno          = new stdClass();
$oMeiImporta       = new cl_meiimporta();
$oMeiImportasemMov = new cl_meiimportasemmov();
$oParIssqn         = new cl_parissqn();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $_POST["json"])));

$oRetorno->status  = 1;

if (isset($oParam->motivo)) {
  $sMotivo = utf8_decode($oParam->motivo);
}

$sMsgErro          = '';
$iAnoArquivo       = $oParam->ano;
$iMesArquivo       = $oParam->mes;
$iIdUsuario        = db_getsession('DB_id_usuario');

switch($oParam->exec) {
  
  /*
   * Incluir competencia sem movimento
   */
  case "incluirCompetenciaSemMovimentacao":
      
      try {
        
        db_inicio_transacao();
		    
		   /**
		    *  Verifica se já foi processado arquivo da competencia atual
		    */
		    $sWhereUltimaImp  = "     q104_anousu    = {$iAnoArquivo}";
		    $sWhereUltimaImp .= " and q104_mesusu    = {$iMesArquivo}";
		    $sWhereUltimaImp .= " and q104_cancelado = false";
		    $sSqlUltimaImp    = $oMeiImporta->sql_query_file(null, "*", null, $sWhereUltimaImp);
		    $rsUltimaImp      = $oMeiImporta->sql_record($sSqlUltimaImp);     
		    if ( $oMeiImporta->numrows > 0 ) {
		      $sMsgErro .= "\nArquivo de competência {$iMesArquivo}/{$iAnoArquivo} já processado!";
		      throw new Exception($sMsgErro);           
		    }
        
		    $sSqlParIssqn = $oParIssqn->sql_query_file(null,"q60_dataimpmei",null,"q60_dataimpmei is not null");
		    $rsParIssqn   = $oParIssqn->sql_record($sSqlParIssqn);
		      
		    if ( $oParIssqn->numrows > 0 ) {
		        
		      $dtDataImpMei = db_utils::fieldsMemory($rsParIssqn,0)->q60_dataimpmei;
		      list($iAnoDataImpMei,$iMesDataImpMei,$iDiaDataImpMei) = explode("-",$dtDataImpMei);
		
		      if (   $iAnoArquivo < $iAnoDataImpMei || ( $iMesArquivo < $iMesDataImpMei && $iAnoArquivo == $iAnoDataImpMei) ) {
		        $sMsgErro .= "\nCompetência informada menor que da implantação do MEI!";
		        throw new Exception($sMsgErro);         
		      }         
		        
		    } else {
		      throw new Exception("{$sMsgErro},\nParâmentros de ISSQN não configurados!");
		    }    
		    
		    $oMeiImporta->q104_id_usuario  = $iIdUsuario;
		    $oMeiImporta->q104_anousu      = $iAnoArquivo;
		    $oMeiImporta->q104_mesusu      = $iMesArquivo;
		    $oMeiImporta->q104_tipoimporta = 2;
		    $oMeiImporta->q104_cancelado   = 'false';
		    $oMeiImporta->incluir(null);
		    if ($oMeiImporta->erro_status == 0) {
		    	throw new Exception($oMeiImporta->erro_msg);
		    }
		    
		    $oMeiImportasemMov->q114_meiimporta = $oMeiImporta->q104_sequencial;
		    $oMeiImportasemMov->q114_motivo     = $sMotivo;
		    $oMeiImportasemMov->incluir(null);
		    if ($oMeiImportasemMov->erro_status == 0) {
		    	throw new Exception($oMeiImportasemMov->erro_msg);
		    }
		    
        db_fim_transacao(false);
      } catch (Exception $eExeption){
        
        db_fim_transacao(true);
        $oRetorno->status = 2;
        $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
      }
      
      break;
      
  /*
   * Cancelar competencia sem movimento
   */
  case "cancelarCompetenciaSemMovimentacao":
      
      try {
        
        db_inicio_transacao();
    
       /**
        *  Verifica se possui arquivo da competencia sem movimento ja cancelada
        */
        $sWhereUltimaImp  = "     q104_anousu      = {$iAnoArquivo}  ";
        $sWhereUltimaImp .= " and q104_mesusu      = {$iMesArquivo}  ";
        $sWhereUltimaImp .= " and q104_cancelado   = false           "; 
        $sWhereUltimaImp .= " and q104_tipoimporta = 2               ";
        $sSqlUltimaImp    = $oMeiImporta->sql_query_file(null, "*", null, $sWhereUltimaImp);
        $rsUltimaImp      = $oMeiImporta->sql_record($sSqlUltimaImp);     
        if ( $oMeiImporta->numrows == 0 ) {
          $sMsgErro .= "\nArquivo de competência {$iMesArquivo}/{$iAnoArquivo} não processado!";
          throw new Exception($sMsgErro);           
        }
        
       /**
        *  Caso exista um arquivo de competencia sem movimento atual e que não foram cancelados, então é verificado
        *  todos os processamentos já feitos ordenado por competência de forma decrescente por competencia que não estão
        *  canceladas.
        */
        $sSqlUltComp      = "  select max((q104_anousu||'-'||q104_mesusu||'-01')::date )                              "; 
        $sSqlUltComp     .= "    from meiimporta                                                                      ";
        $sSqlUltComp     .= "   where q104_cancelado is false                                                         ";
        $sSqlMeiImporta   = "  select *                                                                               ";
        $sSqlMeiImporta  .= "     from meiimporta                                                                     ";
        $sSqlMeiImporta  .= "   where q104_tipoimporta = 2                                                            ";
        $sSqlMeiImporta  .= "    and q104_cancelado is false                                                          ";
        $sSqlMeiImporta  .= "    and (q104_anousu||'-'||q104_mesusu||'-01')::date = ( {$sSqlUltComp} )                ";
        $rsMeiImporta     = $oMeiImporta->sql_record($sSqlMeiImporta);  
        $iNumRows         = $oMeiImporta->numrows;
	          
	      /**
	       *  Verifica se o último processamento é diferente da competência anterior
	       */       
        if ($iNumRows == 0) {
          
          $sMsgErro  = "\nCancelamento abortado!\nExistem registros lançados com competência superior ao informado.";
          throw new Exception($sMsgErro);
        } 
	      
        $oDadosImp                    = db_utils::fieldsMemory($rsMeiImporta,0);
        $oMeiImporta->q104_sequencial = $oDadosImp->q104_sequencial; 
        $oMeiImporta->q104_cancelado  = 'true';
        $oMeiImporta->alterar($oMeiImporta->q104_sequencial);
        if ($oMeiImporta->erro_status == 0) {
          throw new Exception($oMeiImporta->erro_msg);
        }

        db_fim_transacao(false);
      } catch (Exception $eExeption){
        
        db_fim_transacao(true);
        $oRetorno->status = 2;
        $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
      }
      
      break;
}

echo $oJson->encode($oRetorno);   
?>