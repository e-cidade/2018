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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("classes/db_convenio_classe.php");
require_once("classes/db_relac_classe.php");
require_once("classes/db_movrel_classe.php");
require_once("classes/db_pontofs_classe.php");
require_once("classes/db_rhpessoal_classe.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";
$clpontofs         = new cl_pontofs;

switch($oParam->exec) {


  case 'processarDadosConvenio' :
  	
  	$aDadosRubricas         = array();
		$clconvenio             = new cl_convenio;
		$clrelac                = new cl_relac;
		$clmovrel               = new cl_movrel;
		$clrhpessoal            = new cl_rhpessoal;
		$aRubricasCadastradas   = array();   
		$aRubricas              = array();
		$aErrosRhPessoalRecisao = array();
		$iInstituicao           = db_getsession("DB_instit");
		
    $iAno            = $oParam->iAno;
    $iMes            = $oParam->iMes;
    $iConvenio       = $oParam->iConvenio;
    $iServidor       = $oParam->iServidor;
    $iRelacionamento = $oParam->iRelacionamento;
    $iAcaoConflito   = $oParam->iAcaoConflito;
    
	  $sDbwhere = "r54_anomes='".$iAno.$iMes."' and r54_lancad='f' and r54_instit = ".db_getsession('DB_instit');
	  $sQueryst = "&ano=".$iAno."&mes=".$iMes;
	  
	  if (isset($iConvenio) && $iConvenio != "") {
	  	
	    $sDbwhere .= " and r54_codrel = '".$iConvenio."' and r54_lancad='f' and r54_instit = ".db_getsession('DB_instit');
	    $sQueryst .= "&r54_codrel=".$iConvenio;
	  }
	  
	  if (isset($iServidor) && $iServidor != "") {
	  	
	    $sDbwhere .= " and r54_regist = ".$iServidor."  and r54_instit = ".db_getsession('DB_instit');
	    $sQueryst .= "&r54_regist=".$iServidor;
	  }
	  
	  if (isset($iRelacionamento) && $iRelacionamento != "") {
	  	
	    $sDbwhere .= " and r54_codeve = '".$iRelacionamento."' and r54_instit = ".db_getsession('DB_instit') ;
	    $sQueryst .= "&r54_codeve=".$iRelacionamento;
	  }
	
	  $sCampos    = "r54_codrel as codrel, 
						       r54_regist as regist, 
						       r54_codeve as codeve, 
						       sum(r54_quant1) as quant1, 
						       sum(r54_quant2) as quant2, 
						       sum(r54_quant3) as quant3 ";
	  
	  $sSqlMovRel = $clmovrel->sql_query_file(null, $sCampos, "", $sDbwhere);
	  
	  $sSqlMovRel = $sSqlMovRel . " group by r54_codrel, r54_regist, r54_codeve ";
	  
	  $rsDadosImportados = $clmovrel->sql_record($sSqlMovRel);
	  $sqlerro = false;
	
	  $sErro_msgRPESi = "";
	  $sErro_msgRPESn = "";
	
	  $erro_cntRPESi = 0;
	  $erro_cntRPESn = 0;
	   
	  if ($clmovrel->numrows > 0) {
	  	
	    db_inicio_transacao();
	    
	    for ($iClmovrelRow = 0; $iClmovrelRow < $clmovrel->numrows; $iClmovrelRow++) {
	    	
	      //db_fieldsmemory($rsDadosImportados,$i);
	      $oRsImportados = db_utils::fieldsMemory($rsDadosImportados, $iClmovrelRow);
	      $iCodrel = $oRsImportados->codrel;
	      $iRegist = $oRsImportados->regist;
	      $iCodeve = $oRsImportados->codeve;
	      $iQuant1 = $oRsImportados->quant1;
	      $iQuant2 = $oRsImportados->quant2;
	      $iQuant3 = $oRsImportados->quant3;

	      $r1 = false;
	      $r2 = false;
	      $r3 = false;
	
	      $sSqlConvenio = $clconvenio->sql_query_file($iCodrel, db_getsession('DB_instit'), "r56_vq01 as vq01, 
	                                                                                         r56_vq02 as vq02, 
	                                                                                         r56_vq03 as vq03");
	      $rsDadosConvenio = $clconvenio->sql_record($sSqlConvenio);
	      
	      if ($clconvenio->numrows == 0) {
	      	
	        $sqlerro   = true;
	        $sErro_msg = "Usuário:\\nConvênio não encontrado.\\nAdministrador:";
	        break;
	      }
	      //db_fieldsmemory($rsDadosConvenio,0);
	      $oRsConvenio = db_utils::fieldsMemory($rsDadosConvenio, 0);
	      $lVq01 = $oRsConvenio->vq01;
	      $lVq02 = $oRsConvenio->vq02;
	      $lVq03 = $oRsConvenio->vq03;
	      
	      $sSqlRelac = $clrelac->sql_query_rubricas($iCodeve,"a.rh27_rubric as rub01,
                                                            b.rh27_rubric as rub02,
                                                            c.rh27_rubric as rub03");
	      
	      $rsRubricaInclui01 = $clrelac->sql_record($sSqlRelac);
	      
	      if ($clrelac->numrows == 0) {
	      	
	        $sqlerro   = true;
	        $sErro_msg = "Usuário:\\nRelacionamento não encontrado.\\nAdministrador:";
	        break;
	      }
	     // db_fieldsmemory($rsRubricaInclui01,0);
	      $oRsRubricaInclui = db_utils::fieldsMemory($rsRubricaInclui01, 0);
	      $rub01 = $oRsRubricaInclui->rub01;
	      $rub02 = $oRsRubricaInclui->rub02;
	      $rub03 = $oRsRubricaInclui->rub03;
	      
	      /**
	       * Valida dados da Rubrica 1
	       */
	      if (trim($rub01) != "" && $rub01 > 0) {
	        
	        if ($iQuant1 > 0) {
	          $r1 = true;
	        }
	        
	        if ($lVq01 == "t") {
	        	
	          $iQuant1 = $iQuant1;
	          $iValor1 = 0;
	        } else {
	        	
	          $iValor1 = $iQuant1;
	          $iQuant1 = 0;
	        }
	        
	      }
        /**
         * Valida dados da Rubrica 2
         */
	      if (trim($rub02) != "" && $rub02 > 0) {
	        
	        if ($iQuant2 > 0) {
	          $r2      = true;
	        }
	        if ($lVq02 == "t") {
	        	
	          $iQuant2 = $iQuant2;
	          $iValor2 = 0;
	        } else {
	        	
	          $iValor2 = $iQuant2;
	          $iQuant2 = 0;
	        }
	        
	      }
        /**
         * Valida dados da Rubrica 3
         */
	      if (trim($rub03) != "" && $rub03 > 0) {

	        if ($iQuant3 > 0) {
	          $r3 = true;
	        }
	        
	        if ($lVq03 == "t") {
	        	
	          $iQuant3 = $iQuant3;
	          $iValor3 = 0;
	        } else {
	        	
	          $iValor3 = $iQuant3;
	          $iQuant3 = 0;
	        }
	        
	      }
	
	      $sWhereRhPessoalRecisao = "rh01_regist = ".$iRegist." and rh05_recis is null and rh02_anousu = $iAno and rh02_mesusu = $iMes";
	      $sSqlRhPessoalRecisao   = $clrhpessoal->sql_query_rescisao(null, "z01_nome as nome, 
	                                                                       rh02_lota as lotaca", 
	                                                                       "",
	                                                                       $sWhereRhPessoalRecisao);
	      $rsLotacao = $clrhpessoal->sql_record($sSqlRhPessoalRecisao);
	       
	      if ($clrhpessoal->numrows == 0) {
	        
	        $erro_cntRPESn++;
	        $aErrosRhPessoalRecisao[] = $iRegist;
	        continue;
	      }
	      $oLotacao = db_utils::fieldsMemory($rsLotacao, 0);
	      $lotaca = $oLotacao->lotaca;
	      
	      if ($r1 == true) {
	        
	        $sCampoPontoFS     = "r10_valor as valsoma01,r10_quant as qtdsoma01, r10_datlim";
	        $sWherePontoFS     = "r10_anousu=$iAno and r10_mesusu=$iMes and r10_regist=$iRegist and r10_rubric='$rub01'";
	                           
	        $sSqlPontoFS       = $clpontofs->sql_query_file(null, null, null, null,$sCampoPontoFS, "", $sWherePontoFS);
	        $rsPontoFS01       = $clpontofs->sql_record($sSqlPontoFS);

	        
	        if ($clpontofs->numrows > 0 && $iAcaoConflito == 3) {
	        	
	          $oPontoFS01            = db_utils::fieldsMemory($rsPontoFS01, 0);
	          $valsoma01             = $oPontoFS01->valsoma01;
	          $qtdsoma01             = $oPontoFS01->qtdsoma01;
	          
	          $iValor1              += $valsoma01;
	          $iQuant1              += $qtdsoma01;
	          
	          $oRubricas             = new stdClass();
            $oRubricas->rubrica    = $rub01;
            $oRubricas->matricula  = $iRegist;
            $oRubricas->nome       = $oLotacao->nome;
            $oRubricas->valorponto = db_formatar($oPontoFS01->valsoma01,'f');
            $oRubricas->qtdeponto  = $oPontoFS01->qtdsoma01;
            $oRubricas->vrconvenio = db_formatar($oRsImportados->quant1,'f');
            $aRubricas[] = $oRubricas;
            $erro_cntRPESn++;
            
	        } elseif ($clpontofs->numrows > 0 && ($iAcaoConflito == 1 || $iAcaoConflito == 2) ) {
	           
	          $oDadosRubrica         = db_utils::fieldsMemory($rsPontoFS01, 0);
	          $erro_cntRPESi++;
	          $soma = $oDadosRubrica->valsoma01 + $iValor1;
	          
	          if ($iAcaoConflito == 1) {
	          	$iValor1 = $iValor1 + $oDadosRubrica->valsoma01;
	          } 
		        $clpontofs->r10_valor  = "round($iValor1,2)";
	          $clpontofs->r10_quant  = "$iQuant1";
		        $clpontofs->r10_anousu = $iAno;
		        $clpontofs->r10_mesusu = $iMes;
		        $clpontofs->r10_regist = $iRegist;
		        $clpontofs->r10_rubric = $rub01;
	          $clpontofs->r10_lotac  = $lotaca;
	          $clpontofs->r10_datlim = $oDadosRubrica->r10_datlim;
	          $clpontofs->r10_instit = $iInstituicao;
	          
	          $clpontofs->alterar("",
	          										"",
	          										"",
	          										"",
	          										"    r10_anousu =  {$clpontofs->r10_anousu} 
	          										 and r10_mesusu =  {$clpontofs->r10_mesusu}
	      		        						 and r10_regist =  {$clpontofs->r10_regist} 
	      		        						 and r10_rubric = '{$clpontofs->r10_rubric}'");
	          
	          
	          if ($clpontofs->erro_status == 0) {
            
	            $sErro_msg = $clpontofs->erro_msg;
	            $sqlerro=true;
	            break;
	          }          
	          
	        } else {
	           
		        $clpontofs->r10_valor  = "round($iValor1,2)";
		        $clpontofs->r10_quant  = "$iQuant1";
		        $clpontofs->r10_anousu = $iAno;
		        $clpontofs->r10_mesusu = $iMes;
		        $clpontofs->r10_regist = $iRegist;
		        $clpontofs->r10_rubric = $rub01;
	          $clpontofs->r10_lotac  = $lotaca;
	          $clpontofs->r10_datlim = "";
	          $clpontofs->r10_instit = $iInstituicao;
	          $clpontofs->incluir($iAno, $iMes, $iRegist, $rub01);
	          $erro_cntRPESi++;
	          
            if ($clpontofs->erro_status == 0) {
            
	            $sErro_msg = $clpontofs->erro_msg;
	            $sqlerro=true;
	            break;
	          }	          
	        }
	        
	      }
	      
	      if ($r2 == true) {
	        
	        $sSqlPontoFS02     = $clpontofs->sql_query_file(null, null, null, null, 
	                                                        "r10_valor as valsoma02,r10_quant as qtdsoma02, r10_datlim", 
	                                                        "", 
	                                                        "r10_anousu = {$iAno}    and r10_mesusu = {$iMes} and 
	                                                         r10_regist = {$iRegist} and r10_rubric ='{$rub02}'");
	        $rsPontoFS02       = $clpontofs->sql_record($sSqlPontoFS02);
	        if ($clpontofs->numrows > 0  && $iAcaoConflito == 3) {

	          $oPontoFS02            = db_utils::fieldsMemory($rsPontoFS02, 0);
	          $valsoma02             = $oPontoFS02->valsoma02;
	          $qtdsoma02             = $oPontoFS02->qtdsoma02;
	          
	          $iValor2              += $valsoma02;
	          $iQuant2              += $qtdsoma02;
	          
            $oRubricas             = new stdClass();
            $oRubricas->rubrica    = $rub02;
            $oRubricas->matricula  = $iRegist;
            $oRubricas->nome       = $oLotacao->nome;
            $oRubricas->valorponto = db_formatar($oRsPontofs2->valsoma02,'f');
            $oRubricas->qtdeponto  = $oRsPontofs2->qtdsoma02;
            $oRubricas->vrconvenio = db_formatar($oRsImportados->quant2,'f');
            $aRubricas[]           = $oRubricas;	     
            $erro_cntRPESn++;
            
	        } elseif ($clpontofs->numrows > 0 && ($iAcaoConflito == 1 || $iAcaoConflito == 2) ) {
	           
	          $oDadosRubrica         = db_utils::fieldsMemory($rsPontoFS02, 0);
	          $erro_cntRPESi++;
	          
	          $soma = $oDadosRubrica->valsoma01 + $iValor1;
	          
	          if ($iAcaoConflito == 1) {
	          	$iValor2 = $iValor2 + $oDadosRubrica->valsoma02;
	          } 
	          
		        $clpontofs->r10_valor  = "round($iValor2,2)";
	          $clpontofs->r10_quant  = "$iQuant2";
		        $clpontofs->r10_anousu = $iAno;
		        $clpontofs->r10_mesusu = $iMes;
		        $clpontofs->r10_regist = $iRegist;
		        $clpontofs->r10_rubric = $rub02;
	          $clpontofs->r10_lotac  = $lotaca;
	          $clpontofs->r10_datlim = $oDadosRubrica->r10_datlim;
	          $clpontofs->r10_instit = $iInstituicao;
	          
	          $clpontofs->alterar("",
	          										"",
	          										"",
	          										"",
	          										"    r10_anousu =  {$clpontofs->r10_anousu} 
	          										 and r10_mesusu =  {$clpontofs->r10_mesusu}
	      		        						 and r10_regist =  {$clpontofs->r10_regist} 
	      		        						 and r10_rubric = '{$clpontofs->r10_rubric}'");
	          
	          if ($clpontofs->erro_status == 0) {
	            $sErro_msg = $clpontofs->erro_msg;
	            $sqlerro=true;
	            break;
	          }          
	          
	        } else {
	           
		        $clpontofs->r10_valor  = "round($iValor2,2)";
		        $clpontofs->r10_quant  = "$iQuant2";
		        $clpontofs->r10_anousu = $iAno;
		        $clpontofs->r10_mesusu = $iMes;
		        $clpontofs->r10_regist = $iRegist;
		        $clpontofs->r10_rubric = $rub02;
	          $clpontofs->r10_lotac  = $lotaca;
	          $clpontofs->r10_datlim = "";
	          $clpontofs->r10_instit = $iInstituicao;
	          $clpontofs->incluir($iAno, $iMes, $iRegist, $rub02);
	          $erro_cntRPESi++;

	          if ($clpontofs->erro_status == 0) {
            
	            $sErro_msg = $clpontofs->erro_msg;
	            $sqlerro=true;
	            break;
	          }	          
	        }
	      }
	      
	      if ($r3 == true) {
	      	$sSqlPontoFS03     = $clpontofs->sql_query_file(null, 
	                                                        null, 
	                                                        null, 
	                                                        null,
	                                                        "r10_valor as valsoma03, 
	                                                         r10_quant as qtdsoma03, 
	                                                         r10_datlim", 
	                                                        "", 
	                                                        "r10_anousu=$iAno and 
	                                                         r10_mesusu=$iMes and 
	                                                         r10_regist=$iRegist and 
	                                                         r10_rubric='$rub03'");
	        $rsPontoFS03       = $clpontofs->sql_record($sSqlPontoFS03);
	        
	        if ($clpontofs->numrows > 0 && $iAcaoConflito == 3) {
	          
	          $oPontoFS03          = db_utils::fieldsMemory($rsPontoFS03, 0);
            $valsoma03             = $oPontoFS03->valsoma03;
            $qtdsoma03             = $oPontoFS03->qtdsoma03;	          
	          
	          $iValor3              += $valsoma03;
	          $iQuant3              += $qtdsoma03;
	          
            $oRubricas             = new stdClass();
            $oRubricas->rubrica    = $rub03;
            $oRubricas->matricula  = $iRegist;
            $oRubricas->nome       = $oLotacao->y;
            $oRubricas->valorponto = db_formatar($oRsPontofs3->valsoma03,'f');
            $oRubricas->qtdeponto  = $oRsPontofs3->qtdsoma03;
            $oRubricas->vrconvenio = db_formatar($oRsImportados->quant3,'f');
            $aRubricas[]           = $oRubricas;	 
	          $erro_cntRPESn++;
	          
	        } elseif ($clpontofs->numrows > 0 && ($iAcaoConflito == 1 || $iAcaoConflito == 2) ) {
	           
	          $oDadosRubrica         = db_utils::fieldsMemory($rsPontoFS03, 0);
	          $erro_cntRPESi++;
	          
	          $soma = $oDadosRubrica->valsoma01 + $iValor1;
	          
	          if ($iAcaoConflito == 1) {
	          	$iValor3 = $iValor3 + $oDadosRubrica->valsoma03;
	          }
		        
						$clpontofs->r10_valor  = "round($iValor3,2)";
	          $clpontofs->r10_quant  = "$iQuant3";
		        $clpontofs->r10_anousu = $iAno;
		        $clpontofs->r10_mesusu = $iMes;
		        $clpontofs->r10_regist = $iRegist;
		        $clpontofs->r10_rubric = $rub03;
	          $clpontofs->r10_lotac  = $lotaca;
	          $clpontofs->r10_datlim = $oDadosRubrica->r10_datlim;
	          $clpontofs->r10_instit = $iInstituicao;
	          
	          $clpontofs->alterar("",
	          										"",
	          										"",
	          										"",
	          										"    r10_anousu =  {$clpontofs->r10_anousu} 
	          										 and r10_mesusu =  {$clpontofs->r10_mesusu}
	      		        						 and r10_regist =  {$clpontofs->r10_regist} 
	      		        						 and r10_rubric = '{$clpontofs->r10_rubric}'");
	          
	          if ($clpontofs->erro_status == 0) {
            
	            $sErro_msg = $clpontofs->erro_msg;
	            $sqlerro=true;
	            break;
	          }          
	          
	        } else {
	           
	          $erro_cntRPESi++;
		        $clpontofs->r10_valor  = "round($iValor3,2)";
		        $clpontofs->r10_quant  = "$iQuant3";
		        $clpontofs->r10_anousu = $iAno;
		        $clpontofs->r10_mesusu = $iMes;
		        $clpontofs->r10_regist = $iRegist;
		        $clpontofs->r10_rubric = $rub03;
	          $clpontofs->r10_lotac  = $lotaca;
	          $clpontofs->r10_datlim = "";
	          $clpontofs->r10_instit = $iInstituicao;
	          $clpontofs->incluir($iAno, $iMes, $iRegist, $rub03);
	          
	          if ($clpontofs->erro_status == 0) {
	            $sErro_msg = $clpontofs->erro_msg;
	            $sqlerro=true;
	            break;
	          }          
	          
	        }
	        
	      }
	
	      $sWhere = "r54_anomes='".$iAno.$iMes."' and r54_instit = ".db_getsession('DB_instit');
	      if (isset($iCodrel) && $iCodrel != "") {
	        $sWhere .= " and r54_codrel = '".$iCodrel."' ";
	      }
	      
	      if (isset($iRegist) && $iRegist != "") {
	        $sWhere .= " and r54_regist = ".$iRegist;
	      }
	      
	      if (isset($iCodeve) && $iCodeve != "") {
	        $sWhere .= " and r54_codeve = '".$iCodeve."' ";
	      }
	
	      if ($sqlerro == false) {

	        if ($r1 == true || $r2 == true || $r3 == true) {
	        	
	          $clmovrel->r54_anomes = $iAno.$iMes;
	          $clmovrel->r54_codrel = $iCodrel;
	          $clmovrel->r54_regist = $iRegist;
	          $clmovrel->r54_codeve = $iCodeve;
	          $clmovrel->r54_instit = db_getsession('DB_instit');
	          $clmovrel->r54_lancad = "true";
	          $clmovrel->alterar(null, $sWhere);
	          
	          if ($clmovrel->erro_status == 0) {
	            $sErro_msg = $clmovrel->erro_msg;
	            $sqlerro=true;
	            break;
	          }
	        }
	      }
	        
	    
	    }
	
	    $sErro_msg  =        "Registros incluídos no Ponto de salário: ".$erro_cntRPESi;
	    $sErro_msg .= "\n"  ."Registros não incluídos no Ponto de salário: ".$erro_cntRPESn;
	    $sErro_msg .= "\n\n"."Total de registros: ".($erro_cntRPESn+$erro_cntRPESi);
	    
	    if ($erro_cntRPESn != 0) {
        $sErro_msg .= " \n \n Os seguintes dados não foram processados. \n Rubricas em conflito no sistema. ";
        $sErro_msg .= " \n Selecione uma ação e clique em salvar.";
      }	    
	    $sErro_msg .= "\n \n \n Registros não incluídos referem-se a matrículas não encontradas no cadastro de pessoal.";
	
	    db_fim_transacao($sqlerro);
	    
	  } else {
	  	
	    $sqlerro = true;
	    $sErro_msg = "Usuário: \n Nenhum registro encontrado com os dados informados ou já lançados no ponto de salário. \n Administrador:";
	  }  	
	  
    foreach ($aRubricas as $oIndiceRubrica => $oValorRubrica){
  
      $oDados                = new stdClass(); 
      $oDados->matricula     = $oValorRubrica->matricula;
      $oDados->nome          = $oValorRubrica->nome;
      $oDados->rubrica       = $oValorRubrica->rubrica;
      $oDados->valorponto    = $oValorRubrica->valorponto;
      $oDados->qtdeponto     = $oValorRubrica->qtdeponto;
      $oDados->valorconvenio = $oValorRubrica->vrconvenio;
          
      $aDadosRubricas[]      = $oDados;  	
    }
    
    $oRetorno->mensagem           = urlencode($sErro_msg);
    $oRetorno->dados              = $aDadosRubricas;
    $oRetorno->erroPessoalRecisao = $aErrosRhPessoalRecisao;

  break;  

  
  case 'processarRubricas' :
    
  	/**
  	 * Recebemos o objeto com as rubricas nao processadas em conflito
  	 * conforme opção do usuario tomamos 2 tipos de ação
  	 */
    foreach ($oParam->oDados as $oIndiceDadosRubrica => $oValorDadosRubrica) {
    	
    	
      // a cada objeto dentro do array verificamos a propriedade acao
    	switch ($oValorDadosRubrica->acao) {
      	
      	case  '1' :   // 1 = ALTERAR o REGISTRO EXISTENTE
      		
      			db_inicio_transacao();
            $iAnoFolha             = db_anofolha();
            $iMesFolha             = db_mesfolha();
            $iMatricula            = $oValorDadosRubrica->matricula;
            $iRubrica              = $oValorDadosRubrica->rubrica;
      			                       
            $sSqlPontofs           = $clpontofs->sql_query_file($iAnoFolha, $iMesFolha, $iMatricula, $iRubrica, '*');
            $rsPontofs             = $clpontofs->sql_record($sSqlPontofs);
            $oRsPontofs            = db_utils::fieldsMemory($rsPontofs, 0);

            $clpontofs->r10_anousu = $oRsPontofs->r10_anousu;
            $clpontofs->r10_mesusu = $oRsPontofs->r10_mesusu;
            $clpontofs->r10_regist = $oRsPontofs->r10_regist;
            $clpontofs->r10_rubric = "$oRsPontofs->r10_rubric"; 
            $clpontofs->r10_datlim = "$oRsPontofs->r10_datlim";
            $clpontofs->r10_lotac  = "$oRsPontofs->r10_lotac";
            $clpontofs->r10_quant  = $oRsPontofs->r10_quant;
            $clpontofs->r10_valor  = $oValorDadosRubrica->valor;           
	      		
	      		$clpontofs->alterar("","","",""," r10_anousu = {$clpontofs->r10_anousu} and r10_mesusu = {$clpontofs->r10_mesusu}
	      		        and r10_regist = {$clpontofs->r10_regist} and r10_rubric = '{$clpontofs->r10_rubric}'");
      		  db_fim_transacao(false);
            
            if ($clpontofs->erro_status == 0) {
              
              $oRetorno->status  = 2; 
              $oRetorno->message = "Erro \n {$clpontofs->erro_msg}";              
              db_fim_transacao(true);
            }       		
    
      	break;

      	case '2' :   // 2 : ADICIONAR SOMANDO OS VALORES
      		
            db_inicio_transacao();
            
          	$iAnoFolha             = db_anofolha();
          	$iMesFolha             = db_mesfolha();
          	$iMatricula            = $oValorDadosRubrica->matricula;
          	$iRubrica              = $oValorDadosRubrica->rubrica;
          	$sSqlPontofs           = $clpontofs->sql_query_file($iAnoFolha, $iMesFolha, $iMatricula, $iRubrica, '*');
          	$rsPontofs             = $clpontofs->sql_record($sSqlPontofs);
          	$oRsPontofs            = db_utils::fieldsMemory($rsPontofs, 0);
          	$iValorRubrica         = $oValorDadosRubrica->valor;
          	$iValorAtual           = $oRsPontofs->r10_valor;
          	
          	// somamos o valor da rubrica em conflito, com o da lançada atualmente no sistema
          	$iValorRubricaSomado   = $iValorRubrica + $iValorAtual;
          	
            $clpontofs->r10_anousu = $oRsPontofs->r10_anousu;
            $clpontofs->r10_mesusu = $oRsPontofs->r10_mesusu;
            $clpontofs->r10_regist = $oRsPontofs->r10_regist;
            $clpontofs->r10_rubric = "{$oRsPontofs->r10_rubric}"; 
            $clpontofs->r10_datlim = "{$oRsPontofs->r10_datlim}";
            $clpontofs->r10_lotac  = "{$oRsPontofs->r10_lotac}";
            $clpontofs->r10_quant  =  $oRsPontofs->r10_quant;
          	$clpontofs->r10_valor  = $iValorRubricaSomado;
          	
          	$clpontofs->alterar("","","",""," r10_anousu = {$iAnoFolha} and r10_mesusu = {$iMesFolha} and 
          	                                               r10_regist = {$iMatricula} and r10_rubric = '{$iRubrica}'");
          	db_fim_transacao(false);
          	
          	if ($clpontofs->erro_status == 0) {
              $oRetorno->status  = 2; 
              $oRetorno->message = "Erro \n {$clpontofs->erro_msg}";           		
          		db_fim_transacao(true);
          	}
      		
      	break;	
      	
      }
    	
    }	
    
  break;   
  
  
}
  
echo $oJson->encode($oRetorno);   

?>