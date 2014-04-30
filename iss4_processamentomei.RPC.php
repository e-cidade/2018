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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_meiimporta_classe.php");
require_once("classes/db_meiimportamei_classe.php");
require_once("classes/db_parissqn_classe.php");
require_once("model/meiArquivo.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMsg    = "";

$clMeiImporta    = new cl_meiimporta();
$clMeiImportaMei = new cl_meiimportamei();
$clParIssqn      = new cl_parissqn();

try {
	
  if ( $oParam->sMethod == "consultaCompetencias" )  {

  	if ( isset($_SESSION['oMeiArquivo']) ) {
	    unset($_SESSION['oMeiArquivo']);
  	}
  	
    $sCampoImporta  = " distinct extract( month from q111_data ) as mes, "; 
    $sCampoImporta .= "          extract( year  from q111_data ) as ano  ";
    $sWhereImporta  = " q112_sequencial is null ";
    $sOrderImporta  = " ano,mes ";
    $sSubSqlImporta = $clMeiImporta->sql_query_reg(null,$sCampoImporta,$sOrderImporta,$sWhereImporta);
                      
    $sSqlImporta    = " select lpad(mes::text,2,'0'::text)||'/'::text||ano::text as competencia "; 
    $sSqlImporta   .= "  from  ( {$sSubSqlImporta} ) as x               ";
    
    $rsImporta      = $clMeiImporta->sql_record($sSqlImporta);
      
    $aCompetencias  = db_utils::getColectionByRecord($rsImporta,false,false,true);
    
    $oRetorno->aCompetencias = $aCompetencias;

  } else if ( isset($oParam->sCompetencia) ) {
	  	
   	if ( isset($_SESSION['sCompetenciaMEI']) && $_SESSION['sCompetenciaMEI'] == $oParam->sCompetencia && isset($_SESSION['oMeiArquivo'])) {
	  		
	 		$oMeiArquivoSessao = unserialize($_SESSION['oMeiArquivo']);
	  		
	 	} else {
	  		
	  	$_SESSION['sCompetenciaMEI'] = $oParam->sCompetencia;
	  	
      if ( isset($_SESSION['oMeiArquivo']) ) {
	      unset($_SESSION['oMeiArquivo']);
	    }		  	
		  	
	    list($iMes,$iAno) = explode("/",$oParam->sCompetencia);
	          
	    $sWhereImporta   = "     extract( year  from q111_data) = {$iAno} ";
	    $sWhereImporta  .= " and extract( month from q111_data) = {$iMes} ";
	    $sWhereImporta  .= " and meiprocessareg.q112_sequencial is null   ";
		      
	    $oMeiArquivoSessao = new MeiArquivo($sWhereImporta);
		    
	 	}
	    
	} else {
	 	throw new Exception("Competência não informada!");
	}
	  
	
 	if ( $oParam->sMethod == "consultaRegistros" ) {


    $dtDataImpMei = $oMeiArquivoSessao->getDataImpMEI();
    
    list($iAnoDataImpMei,$iMesDataImpMei,$iDiaDataImpMei) = explode("-",$dtDataImpMei);
    $iAnoComp = $iAnoDataImpMei;
    $iMesComp = $iMesDataImpMei;
    
    list($iMes,$iAno) = explode("/",$oParam->sCompetencia);
    $dtDataCompFim = "{$iAno}-{$iMes}-".ultimo_dia_mes($iMes,$iAno);
                          
    try {
      $aDadosImporta = $oMeiArquivoSessao->getCompetencias($dtDataImpMei,$dtDataCompFim);
    } catch (Exception $eException) {
    	throw new Exception($eException->getMessage());
    }
    
    foreach ( $aDadosImporta as $oImporta ) {
    
    	if (    $iAnoComp != $oImporta->q104_anousu 
    	     || $iMesComp != $oImporta->q104_mesusu ) {
    	  throw new Exception("Competência ".str_pad($iMesComp,2,'0',STR_PAD_LEFT)."/{$iAnoComp} não importada!");	
    	}
    	
    	if ( $iMesComp == 12 ) {
    		$iMesComp = 1;
    		$iAnoComp++;
    	} else {
        $iMesComp++;
    	}
      
    }
   
    $sWhereImporta   = "     extract( year  from q111_data) = {$iAno} ";
    $sWhereImporta  .= " and extract( month from q111_data) = {$iMes} ";
    $sWhereImporta  .= " and meiprocessareg.q112_sequencial is null   ";    
    
	  $sSqlImporta     = $clMeiImporta->sql_query_reg(null,"*","q111_data",$sWhereImporta);
	  $rsDadosImporta  = $clMeiImporta->sql_record($sSqlImporta);
	  $aDadosImporta   = db_utils::getColectionByRecord($rsDadosImporta,false,false,true); 
		
	  $aDadosAgrupados = array();
    $aRetornoImporta = array();
    
	  foreach ( $aDadosImporta as $oDadosImporta ) {
		
	    $iCnpj       = $oDadosImporta->q105_cnpj;
	    $sCodEvento  = $oDadosImporta->q101_codigo;
		
	    if ( !isset($aDadosAgrupados[$iCnpj]) ) {
		      
	      $sCampoNomeMei     = " case                                         ";
	      $sCampoNomeMei    .= "    when trim(q107_nome) != '' then q107_nome "; 
	      $sCampoNomeMei    .= "    else z01_nome                             ";
	      $sCampoNomeMei    .= " end as nomemei                               ";
		      
	      $sWhereNomeMei     = "     q105_cnpj = '{$iCnpj}'                   ";
	      $sWhereNomeMei    .= " and (    trim(q107_nome) != ''               ";
	      $sWhereNomeMei    .= "       or trim(z01_nome)  != '' )             ";
		      
	      $sSqlNomeMei       = $clMeiImportaMei->sql_query_nomemei(null,$sCampoNomeMei,null,$sWhereNomeMei);
	      $rsConsultaNomeMei = db_query($sSqlNomeMei); 
		      
	      if ( pg_num_rows($rsConsultaNomeMei) > 0 ) {
	        $sNomeMei = db_utils::fieldsMemory($rsConsultaNomeMei,0,false,false,true)->nomemei;
	      } else {
	        $sNomeMei = '';
	      }
		      
	      $oDadosMei = new stdClass();
	      $oDadosMei->iCnpj      = $iCnpj;
	      $oDadosMei->sNome      = $sNomeMei;
	      $oDadosMei->lBloqueado = false;
		      
	      $aDadosAgrupados[$iCnpj]['oMei'] = $oDadosMei;
		      
	    }
		    
	    if ( !isset($aDadosAgrupados[$iCnpj]['aEventos'][$sCodEvento]) ) {
		    	
	      $oEvento = new stdClass();
	      $oEvento->sCodEvento  = $sCodEvento; 
	      $oEvento->sDescricao  = $oDadosImporta->q101_descricao;
	      $oEvento->dtData      = $oDadosImporta->q111_data;
	      $oEvento->iCnpj       = $iCnpj;
       	$oEvento->lVinculaMEI = false;
	      
	      $sWhereEventoProc  = "     q105_cnpj = '{$iCnpj}'                          ";
        $sWhereEventoProc .= " and q112_sequencial is not null                     ";
			  $sWhereEventoProc .= " and q111_data > '{$oDadosImporta->q111_data}'::date ";
        
        $sSqlEventoProc    = $clMeiImporta->sql_query_reg(null,"*",null,$sWhereEventoProc);
        $rsEventoProc      = $clMeiImporta->sql_record($sSqlEventoProc); 
        $iRowsEventoProc   = $clMeiImporta->numrows;
        
        if ( $iRowsEventoProc > 0 ) {
          $oEvento->lInconsistente = true;
          $oEvento->sMsgSituacao   = urlencode("Registros processados com data superior a do evento informado!");
          $aDadosAgrupados[$iCnpj]['oMei']->lBloqueado = true;          
        } else {
          $oEvento->lInconsistente = false;
        }	      

        if ( !$oEvento->lInconsistente ) {
        	
		      try {
		        $aMsgVerifica   = $oMeiArquivoSessao->validaEventoMEI($sCodEvento,$oDadosImporta->q105_cnpj);
		        $iCountVerifica = count($aMsgVerifica);
		      } catch (Exception $eException){
		      	throw new Exception($eException->getMessage());
		      }
			      
		      if ( $iCountVerifica > 0 ) {
		      	
		      	$sMsgSituacao = "";
		      	
		      	foreach ($aMsgVerifica as $iInd => $aDadosVerifica ) {
			      	foreach ( $aDadosVerifica as $iCodMsg => $sMsgVerifica ) {
			      		 
			      		if ( $iCodMsg == "10" && count($aMsgVerifica) == 1 ) {
			      			$oEvento->lVinculaMEI = true;
			      		}
			      		
			      		$sMsgSituacao .= " - {$sMsgVerifica}<br>";
			      	}
		      	}
		      	
		        $oEvento->lInconsistente = true;
		        $oEvento->sMsgSituacao   = urlencode($sMsgSituacao);
		        $aDadosAgrupados[$iCnpj]['oMei']->lBloqueado = true;
		      } else {
		        $oEvento->lInconsistente = false;
		        $oEvento->sMsgSituacao   = '';
		      }
		      
        } 
        
	      $aDadosAgrupados[$iCnpj]['aEventos'][$sCodEvento] = $oEvento ;
	          
	    }
		    
	  }
		
	  
	  foreach ( $aDadosAgrupados as $iCnpj => $aTipoDados ) {
		    
	    $oDadosMei = $aTipoDados['oMei'];
	    $oDadosMei->aEventos = array();
		        
	    foreach ( $aTipoDados['aEventos'] as $oEvento ) {
	      $oDadosMei->aEventos[] = $oEvento;
	    }
		    
	    $aRetornoImporta[] = $oDadosMei;
		    
	  }
		  
	  $oRetorno->aDadosImporta = $aRetornoImporta;  		
  		
	  
  	
 	} else if ( $oParam->sMethod == "consultaDetalheEvento" ) {

 		
    $oDaoMeiEvento   = db_utils::getDao('meievento');
    $sSqlDadosEvento = $oDaoMeiEvento->sql_query_file(null,"*",null,"q101_codigo = '{$oParam->sCodEvento}'"); 
    $rsDadosEvento   = $oDaoMeiEvento->sql_record($sSqlDadosEvento);
	      
    if ( pg_num_rows($rsDadosEvento) > 0 ) {
      $oDadosEvento = db_utils::fieldsMemory($rsDadosEvento,0,false,false,true);
    } else {
      throw new Exception("{$sMsgErro}, {$oDaoMeiEvento->erro_msg}");
    }  	
  	
 	  try {
 	  	$sTelaDetalhe = $oMeiArquivoSessao->getTelaDetalhesEvento($oParam->sCodEvento,$oParam->iCnpj);
 	  } catch (Exception $eException) {
 	  	throw new Exception($eException->getMessage());
 	  }
      
    $oRetorno->sTelaDetalhe = urlencode($sTelaDetalhe);
    $oRetorno->oEvento      = $oDadosEvento;

    
  } else if ( $oParam->sMethod == "consultaDetalheInconsistencias" ) {

    try {
      $sTelaDetalhe = $oMeiArquivoSessao->getTelaInconsistenciaMEI($oParam->sCodEvento,$oParam->iCnpj);
    } catch (Exception $eException) {
      throw new Exception($eException->getMessage());
    }
  	
  	$oRetorno->sTelaDetalhe = urlencode($sTelaDetalhe);
  	
 	} else if ( $oParam->sMethod == "processaArquivoMEI" )  {

 		
 	  db_inicio_transacao();
	   	 
    try {
    	
  	  if ( count($oParam->aListaProcessar) > 0 ) {
		    $oMeiArquivoSessao->processaMeiArquivoLote($oParam->aListaProcessar);
  	  }
  	  
    } catch (Exception $eException) {
      db_fim_transacao(true);
      throw new Exception($eException->getMessage());
    }  	     
  	   
 	  db_fim_transacao(false);
  	 
 	  $oRetorno->sMsg = urlencode('Arquivos Processados com Sucesso!');

 	  
  } else if ( $oParam->sMethod == "vinculaMeiCgm" )  {

    
    db_inicio_transacao();
       
    try {
      $oMeiArquivoSessao->vinculaMeiCgm($oParam->iCnpj,$oParam->sCodEvento);
    } catch (Exception $eException) {
      db_fim_transacao(true);
      throw new Exception($eException->getMessage());
    }        
       
    db_fim_transacao(false);
     
    $oRetorno->sMsg = urlencode('MEI vinculado com sucesso!');

    
    
 	  
  } else if ( $oParam->sMethod == "alterarInconsistencias" )  {

    try {
    	
    	if ( isset($oParam->iCodLogradouro) ) {
		    $oMeiArquivoSessao->setCodRuaMEI($oParam->iCnpj,
		                                     $oParam->sCodEvento,
		                                     $oParam->iCodLogradouro);
    	}

    	if ( isset($oParam->iCodBairro) ) {
		    $oMeiArquivoSessao->setCodBairroMEI($oParam->iCnpj,
		                                        $oParam->sCodEvento,
		                                        $oParam->iCodBairro);
    	}
    	
      if ( isset($oParam->lEmpresaCadastrada) ) {
        $oMeiArquivoSessao->setEmpresaCadastrada($oParam->iCnpj,
                                                 $oParam->sCodEvento);
      }    	
    	
      if ( isset($oParam->lResponsavelCadastrado) ) {
        $oMeiArquivoSessao->setResponsavelCadastrado($oParam->iCnpj,
                                                     $oParam->sCodEvento);
      }           
      
      if ( isset($oParam->aListaAtividades) && count($oParam->aListaAtividades) > 0 ) {

      	foreach ( $oParam->aListaAtividades as $aDadosAtividade ) {
      		
          $oMeiArquivoSessao->setCodAtividade($oParam->iCnpj,
                                              $oParam->sCodEvento,
                                              $aDadosAtividade[0],
                                              $aDadosAtividade[1]);
      	}
      }
	                                        
    } catch ( Exception $eException ) {
    	throw new Exception($eException->getMessage());
    }
       
    $oRetorno->sMsg = urlencode('MEI vinculado com sucesso!');

  }
  
  if ( isset($oMeiArquivoSessao) ) {
	  $_SESSION['oMeiArquivo'] = serialize($oMeiArquivoSessao);
  }
  
} catch ( Exception $eException ) {

	$oRetorno->iStatus = 2;
	$oRetorno->sMsg    = urlencode(str_replace("\\n","\n",$eException->getMessage()));
	
}

echo $oJson->encode($oRetorno);

?>