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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
require_once("classes/db_meiimporta_classe.php");
require_once("classes/db_meiimportamei_classe.php");
require_once("model/meiArquivo.model.php");

$oGet = db_utils::postMemory($_GET);

$clMeiImporta    = new cl_meiimporta();
$clMeiImportaMei = new cl_meiimportamei();


/**
 *  Consulta os dados referente a competкncia passada por parвmetro
 */
$iMesCompetencia = $oGet->iMesCompetencia;
$iAnoCompetencia = $oGet->iAnoCompetencia; 
$sWhereImporta   = "     extract( year  from q111_data) = {$iAnoCompetencia} ";
$sWhereImporta  .= " and extract( month from q111_data) = {$iMesCompetencia} ";
  
/**
 *  Parвmetro $oGet->iPosicaoAtual :
 *     1 - Todos Registros
 *     2 - Somente Registros Pendentes para Processamento
 *     3 - Somente Registros Processados
 */
if ( $oGet->iPosicaoAtual == 2 ) {
	$sWhereImporta  .= " and meiprocessareg.q112_sequencial is null ";
	$sCabPosicaoAtual    = " Somente Registros Pendentes para Processamento ";  
} else if ( $oGet->iPosicaoAtual == 3 ) {
  $sWhereImporta  .= " and meiprocessareg.q112_sequencial is not null ";
  $sCabPosicaoAtual    = " Somente Registros Processados ";
} else {
	$sCabPosicaoAtual    = " Todos Registros";
}


/**
 *  Parвmetro $oGet->iSituacao :
 *     1 - Todos Registros
 *     2 - Somente Registros Processados
 *     3 - Somente Registros Descartados
 */
if ( $oGet->iSituacao == 2 ) {
	$sWhereImporta  .= " and meiprocessareg.q112_sequencial is not null ";
	$sWhereImporta  .= " and meiprocessareg.q112_descarta is false      ";
	$sCabSituacao    = " Somente Registros Processados ";
} else if ( $oGet->iSituacao == 3 ) {
	$sWhereImporta  .= " and meiprocessareg.q112_sequencial is not null ";
	$sWhereImporta  .= " and meiprocessareg.q112_descarta is true       ";
	$sCabSituacao    = " Somente Registros Descartados ";
} else {
	$sCabSituacao    = " Todos Registros";
}


$sSqlImporta     = $clMeiImporta->sql_query_reg(null,"*","q111_data",$sWhereImporta);
$rsDadosImporta  = $clMeiImporta->sql_record($sSqlImporta);

if ( $clMeiImporta->numrows == 0  ) {
	db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!");
}

$aDadosImporta   = db_utils::getColectionByRecord($rsDadosImporta); 
    
try {
	$oMeiArquivo     = new MeiArquivo($sWhereImporta);
} catch (Exception $eException) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
}

$aDadosAgrupados = array();
$aRetornoImporta = array();
    


foreach ( $aDadosImporta as $oDadosImporta ) {
    
   $iCnpj       = $oDadosImporta->q105_cnpj;
   $sCodEvento  = $oDadosImporta->q101_codigo;
    
   if ( !isset($aDadosAgrupados[$iCnpj]['aEventos'][$sCodEvento]) ) {
          
     $oEvento = new stdClass();
     $oEvento->sCodEvento     = $sCodEvento; 
     $oEvento->sDescricao     = $oDadosImporta->q101_descricao;
     $oEvento->dtData         = $oDadosImporta->q111_data;
     $oEvento->lInconsistente = false;
     $oEvento->sMsgPosicaoAtual   = "";
        
     if ( trim($oDadosImporta->q112_sequencial) != '' ) {
     	 
     	 $oEvento->lProcessado = true;
     	 
     	 if ( isset($oDadosImporta->q112_descarta) and $oDadosImporta->q112_descarta == 't' ) {
     	 	 $oEvento->lDescartado = true;
     	 } else {
     	 	 $oEvento->lDescartado = false;
     	 }
     	 
     } else {
     	 $oEvento->lProcessado = false;
     	 $oEvento->lDescartado = false;
     }
     
     $sWhereEventoProc  = "      q105_cnpj = '{$iCnpj}'                         ";
     $sWhereEventoProc .= " and q112_sequencial is not null                     ";
     $sWhereEventoProc .= " and q111_data > '{$oDadosImporta->q111_data}'::date ";
        
     $sSqlEventoProc    = $clMeiImporta->sql_query_reg(null,"*",null,$sWhereEventoProc);
     $rsEventoProc      = $clMeiImporta->sql_record($sSqlEventoProc); 
     $iRowsEventoProc   = $clMeiImporta->numrows;
        
     if ( $iRowsEventoProc > 0 ) {
       $oEvento->lInconsistente = true;
       $oEvento->sMsgPosicaoAtual   = "Registros processados com data superior a do evento informado!";
     }       

     if ( !$oEvento->lInconsistente ) {
          
      try {
        $aMsgVerifica = $oMeiArquivo->validaEventoMEI($sCodEvento,$oDadosImporta->q105_cnpj);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }
            
      if ( count($aMsgVerifica) > 0 ) {
        $oEvento->lInconsistente = true;
        $oEvento->sMsgPosicaoAtual   = $aMsgVerifica;
      }
    } 

    if ( $oEvento->lInconsistente && $oGet->iTipoReg == 3 ) {
    	continue;
    } else if ( !$oEvento->lInconsistente && $oGet->iTipoReg == 2 ) {
    	continue;
    }

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
        $sNomeMei = db_utils::fieldsMemory($rsConsultaNomeMei,0)->nomemei;
      } else {
        $sNomeMei = '';
      }
          
      $oDadosMei = new stdClass();
      $oDadosMei->iCnpj = $iCnpj;
      $oDadosMei->sNome = $sNomeMei;
           
      $aDadosAgrupados[$iCnpj]['oMei'] = $oDadosMei;
    }    
    
    $aDadosAgrupados[$iCnpj]['aEventos'][$sCodEvento] = $oEvento ;
    
  }
}

if ( count($aDadosAgrupados) == 0  ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!");
}


switch ($oGet->iTipoReg) {
	
	case 1 :
	  $sCabTipoReg = " Todos Registros ";
	break;
  case 2 :
    $sCabTipoReg = " Somente Registros com Inconsistкncia";
  break;
  case 3 :
  	$sCabTipoReg = " Somente Registros sem Inconsistкncia";
  break;  	
		
}


$head2 = " Competкncia : ".str_pad($iMesCompetencia,2,'0',STR_PAD_LEFT)."/{$iAnoCompetencia}";
$head5 = " Tipo Registros : $sCabTipoReg ";
$head4 = " Situaзгo : $sCabSituacao";
$head3 = " Posiзгo Atual : $sCabPosicaoAtual";

$oPdf = new PDF();
$oPdf->Open();

$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);


$iFonte    = 9;
$iAlt      = 5;
$iPreenche = 1;

imprimeCab($oPdf,$iAlt,$iFonte,true);

foreach ( $aDadosAgrupados as $iCnpjMEI => $aDadosMei ) {
	
	imprimeCab($oPdf,$iAlt,$iFonte);
	
	$oDadosMei = $aDadosMei['oMei'];
	
	if ( $iPreenche == 1 ) {
		$iPreenche = 0;
	} else {
		$iPreenche = 1;
	}
	
  $oPdf->Cell(40 ,$iAlt,db_formatar($iCnpjMEI,'cnpj'),'T',0,'C',$iPreenche);
  $oPdf->Cell(235,$iAlt,$oDadosMei->sNome            ,'T',1,'L',$iPreenche);	
	
  foreach ( $aDadosMei['aEventos'] as $sCodEvento => $oDadosEvento ) {
  	
  	imprimeCab($oPdf,$iAlt,$iFonte);
  	
	  if ( $oDadosEvento->lInconsistente ) {
	  	$sTipoRegistro = 'Inconsistente';
	  } else {
	  	$sTipoRegistro = 'Consistente';
	  }
  	
	  if ( $oDadosEvento->lProcessado ) {
	  	
	  	$sPosicaoAtual = "Processado";
	  	
	    if ( $oDadosEvento->lDescartado ) {
	      $sSituacao = 'Descartado';
	    } else {
	      $sSituacao = 'Processado';
	    }
	  } else {
	  	$sPosicaoAtual = "Nгo Processado";
	  	$sSituacao = '';
	  }
	  
	  $oPdf->Cell(40 ,$iAlt,""                                    ,0,0,'C',$iPreenche);
	  $oPdf->Cell(25 ,$iAlt,$sCodEvento                           ,0,0,'C',$iPreenche);
	  $oPdf->Cell(95 ,$iAlt,$oDadosEvento->sDescricao             ,0,0,'L',$iPreenche);
	  $oPdf->Cell(25 ,$iAlt,db_formatar($oDadosEvento->dtData,'d'),0,0,'C',$iPreenche);
	  $oPdf->Cell(30 ,$iAlt,$sTipoRegistro                        ,0,0,'C',$iPreenche);
	  $oPdf->Cell(30 ,$iAlt,$sSituacao                            ,0,0,'C',$iPreenche);
	  $oPdf->Cell(30 ,$iAlt,$sPosicaoAtual                        ,0,1,'C',$iPreenche);  	
  	
	  
  }
  
  
}

$oPdf->Output();

function imprimeCab($oPdf,$iAlt,$iFonte,$lImprime=false){

  if ($oPdf->gety() > $oPdf->h - 30 || $lImprime ){
  	
		$oPdf->AddPage("L");
		$oPdf->SetFont('Arial','b',$iFonte);
		$oPdf->Cell(40 ,$iAlt,"CNPJ MEI"           ,1,0,'C',1);
		$oPdf->Cell(235,$iAlt,"Nome MEI"           ,1,1,'C',1);
		  
		$oPdf->Cell(40 ,$iAlt,""                   ,1,0,'C',1);
		$oPdf->Cell(25 ,$iAlt,"Cуdigo Evento"      ,1,0,'C',1);
		$oPdf->Cell(95 ,$iAlt,"Descriзгo Evento"   ,1,0,'C',1);
		$oPdf->Cell(25 ,$iAlt,"Data Evento"        ,1,0,'C',1);
		$oPdf->Cell(30 ,$iAlt,"Tipo Registro"      ,1,0,'C',1);
		$oPdf->Cell(30 ,$iAlt,"Situaзгo"           ,1,0,'C',1);
		$oPdf->Cell(30 ,$iAlt,"Posiзгo Atual"      ,1,1,'C',1);
		$oPdf->SetFont('Arial','',$iFonte);
			
  }
	
}
?>