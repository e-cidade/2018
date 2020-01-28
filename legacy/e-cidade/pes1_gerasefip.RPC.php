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
require_once("libs/db_sql.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oJson        = new services_json();
$oParam       = $oJson->decode((str_replace("\\","",$_POST["json"])));

$oRetorno     = new stdClass();
$oRetorno->status           = 1;
$oRetorno->message          = '';
$oRetorno->aListaMatriculas = array();

$iInstit      = db_getsession('DB_instit');
$iAnoFolha    = db_anofolha();
$iMesFolha    = db_mesfolha();
$iAnoUsu      = $oParam->anousu;
$iMesUsu      = $oParam->mesusu;
$iNumCgm      = $oParam->r70_numcgm;
$aCheckBoxes  = $oParam->checkboxes;
$sWhereRHLota = "";

switch ($oParam->exec) {
  
  case "getMatriculas":
    
    try {
    	
      db_inicio_transacao();
      
      $oDaoDbConfig     = db_utils::getDao("db_config");
      $oDaoRhLota       = db_utils::getDao("rhlota");
      $oDaoCfPess       = db_utils::getDao("cfpess");
      $oDaoInssIrf      = db_utils::getDao("inssirf");
      $oDaoRhPessoalMov = db_utils::getDao("rhpessoalmov");
      $oDaoRhPessoal    = db_utils::getDao("rhpessoal");
      
	    if ($iNumCgm == 0) {
	      
	      $sSql         = $oDaoDbConfig->sql_query_file($iInstit);
	      $rsDBConfig   = $oDaoDbConfig->sql_record($sSql);
	    } else {
	      
	      $sWhereConfig  = "     r70_numcgm = {$iNumCgm} "; 
	      $sWhereConfig .= " and r70_instit= {$iInstit}  ";
	      $sSqlDBConfig  = $oDaoRhLota->sql_query_lota_cgm(null, "*", null, $sWhereConfig);
	      $rsDBConfig    = $oDaoRhLota->sql_record($sSqlDBConfig);
	      
	      $sWhereRHLota  = " and rh02_lota in ( select r70_codigo                ";
        $sWhereRHLota .= "                      from rhlota                    ";
        $sWhereRHLota .= "                     where r70_instit = {$iInstit}   ";
        $sWhereRHLota .= "                       and r70_numcgm = {$iNumCgm} ) ";
	    }
    
	    if ($iNumCgm == 0) {
	    	
	      if ($oDaoDbConfig->numrows == 0) {
	         throw new Exception("ERRO: Instituição não encontrada. Arquivo não poderá gerado.");
	      }
	    } else {
	    	
	      if ($oDaoRhLota->numrows == 0) {
	         throw new Exception("ERRO: CGM não encontrado. Arquivo não poderá gerado.");
	      }
	    }
	    
	    $sSqlCfPessPrev = $oDaoCfPess->sql_query_file($iAnoFolha, $iMesFolha, $iInstit, "r11_mes13");
	    $rsCfPessPrev   = $oDaoCfPess->sql_record($sSqlCfPessPrev);
	    if ($oDaoCfPess->numrows > 0) {   
	      $sMesPag13 = db_utils::fieldsMemory($rsCfPessPrev,0)->r11_mes13;  
	    } else {
	      $sMesPag13 = 12;
	    }
	    
	    $lMes13 = false;
	    if ($iMesUsu == 13) {
	      
	      $iMesUsu = $sMesPag13;
	      $lMes13  = true;
	    }
	    
	    $sSqlCfPess = $oDaoCfPess->sql_query_file($iAnoUsu, $iMesUsu, $iInstit);
      $rsCfPess   = $oDaoCfPess->sql_record($sSqlCfPess);
	    if ($oDaoCfPess->numrows == 0) {
	    	
	    	$sMensagem  = "ERRO: Configuração da folha não encontrada para o Ano/Mês ({$iAnoUsu}/{$iMesUsu}). "; 
	    	$sMensagem .= "Arquivo não poderá ser gerado. ";
	      throw new Exception($sMensagem);    
	    } else {
	  
	      $oConfig = db_utils::fieldsMemory($rsDBConfig, 0);
	      $oCfPess = db_utils::fieldsMemory($rsCfPess, 0);
	      
	      $iMesAnt = (int)$iMesUsu - 1;
	      $iAnoAnt = (int)$iAnoUsu;
	      if ( $iMesAnt == 0 ) {
	        
	        $iMesAnt = 12;
	        $iAnoAnt-= 1;
	      }
	      
	      $iAnoAnt = db_formatar($iAnoAnt, "s", "0", 4, "e", 0);
	      $iMesAnt = db_formatar($iMesAnt, "s", "0", 2, "e", 0);
	  
	      $sWherePrev  = "     r33_anousu = {$iAnoUsu}                 "; 
	      $sWherePrev .= " and r33_mesusu = {$iMesUsu}                 "; 
	      $sWherePrev .= " and r33_codtab = {$oCfPess->r11_tbprev} + 2 "; 
	      $sWherePrev .= " and r33_instit = {$iInstit}                 ";
	                      
	      $sSqlPrev    = $oDaoInssIrf->sql_query_file(null, null, "r33_rubmat", "r33_nome limit 1", $sWherePrev);                                          
	      $rsPrev      = $oDaoInssIrf->sql_record($sSqlPrev);
	      
	      $oPrev       = db_utils::fieldsMemory($rsPrev, 0);   
	  
	      $clgera_sql_folha = new cl_gera_sql_folha();
	      $clgera_sql_folha->usar_res  = true;
	      $clgera_sql_folha->usar_doc  = true;
	      $clgera_sql_folha->usar_cgm  = true;
	      $clgera_sql_folha->usar_fgt  = true;
	      $clgera_sql_folha->usar_fun  = true;
	      $clgera_sql_folha->usar_ins  = true;
	      $clgera_sql_folha->usar_tpc  = true;
	      $clgera_sql_folha->usar_atv  = true;
	      $clgera_sql_folha->inner_ins = false;
	      $clgera_sql_folha->inner_doc = false;
	      $clgera_sql_folha->inner_fgt = false;
	      $clgera_sql_folha->inner_atv = false;
	  
	      $sWhereRescisao  = "      rh02_anousu = {$iAnoAnt}  "; 
	      $sWhereRescisao .= "  and rh02_mesusu = {$iMesAnt}  "; 
	      $sWhereRescisao .= "  and rh02_regist = rh01_regist ";
	      $sWhereRescisao .= "  and rh02_instit = {$iInstit}  ";
	      $sWhereRescisao .= "  and rh05_recis is not null    ";
	      $sSubSqlRescisao = $oDaoRhPessoalMov->sql_query_rescisao(null, "rh02_regist", null, $sWhereRescisao);
	      
	      $sCamposSqlFolha  = "rh01_regist, z01_nome, rh16_pis";
	      $sWhereSqlFolha   = "     rh02_tbprev in ({$aCheckBoxes})                               ";
        $sWhereSqlFolha  .= " and ( rh05_recis is null                                          ";
        $sWhereSqlFolha  .= "       or ( rh05_recis is not null                                 ";
        $sWhereSqlFolha  .= "            and ( ( cast(extract(year from rh05_recis) as integer) = {$iAnoUsu}       ";
        $sWhereSqlFolha  .= "                  and extract(month from rh05_recis) as integer) = {$iMesUsu} )   ";
        $sWhereSqlFolha  .= "             or ( cast(extract(year from rh05_recis) as integer) = {$iAnoAnt}         ";
        $sWhereSqlFolha  .= "                  and cast(extract(month from rh05_recis) as integer) = {$iMesAnt}     ";
        $sWhereSqlFolha  .= "                  and rh01_regist not in ({$sSubSqlRescisao}) )))) ";
        $sWhereSqlFolha  .= " {$sWhereRHLota}                                                   ";
	      
	      $sSqlDadosFolha   = $clgera_sql_folha->gerador_sql("", 
	                                                         $iAnoUsu, 
	                                                         $iMesUsu, 
	                                                         null, 
	                                                         null, 
	                                                         $sCamposSqlFolha, 
	                                                         "", 
	                                                         $sWhereSqlFolha, 
	                                                         $iInstit);
	                                                  
	      $sCamposAutonomos    = "rh89_codord as rh01_regist, z01_nome, cast(z01_pis as varchar) as rh16_pis";
	      $sSqlAutonomos       = "select {$sCamposAutonomos}                        ";                                                      
	      $sSqlAutonomos      .= "  from rhautonomolanc                             ";
	      $sSqlAutonomos      .= "       inner join cgm on rh89_numcgm = z01_numcgm ";                                                      
	      $sSqlAutonomos      .= " where rh89_anousu = {$iAnoUsu}                   ";                                                      
	      $sSqlAutonomos      .= "   and rh89_mesusu = {$iMesUsu}                   ";
	      $sSqlAutonomos      .= " order by rh16_pis                                ";
	      
	      $sSqlDadosFolhaUnion = "{$sSqlDadosFolha} union {$sSqlAutonomos}";
	      $rsDadosFolha = $oDaoRhPessoal->sql_record($sSqlDadosFolhaUnion);
	      if ($oDaoRhPessoal->numrows == 0) {
	      	
	      	$sMensagem  = "Nenhum registro encontrado no Ano/Mês ({$iAnoUsu}/{$iMesUsu}). "; 
	      	$sMensagem .= "Arquivo não poderá ser gerado. ";
	        throw new Exception($sMensagem);
	      } else {
	      	
	      	for ($i = 0; $i < $oDaoRhPessoal->numrows; $i++) {
	      		
	      		$oDadosFolha = db_utils::fieldsMemory($rsDadosFolha, $i);
	      		
	      		$oListaMatriculas = new stdClass();
	      		$oListaMatriculas->rh01_regist = $oDadosFolha->rh01_regist;
	      		$oListaMatriculas->z01_nome    = $oDadosFolha->z01_nome;
	      			      		
	      	  $oRetorno->aListaMatriculas[]  = $oListaMatriculas;
	      	}
	      }
      }
	    
      db_fim_transacao(true);
    } catch (Exception $oErro) {
    	
    	db_fim_transacao(false);
    	$oRetorno->status  = 2;
    	$oRetorno->message = urlencode(str_replace("\\n", "\n", $oErro->getMessage()));
    }
    break;
}

echo $oJson->encode($oRetorno);