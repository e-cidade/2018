<?
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

require_once("libs/db_sql.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");

$oGet  = db_utils::postMemory($_GET);
$oJson = new services_json();

$aDadosSimulacao = unserialize($_SESSION["aListaSimulacao"]);
$oTotal          = $oJson->decode(str_replace("\\","",$oGet->oTotal));

$head2 = "SIMULAÇÃO DA ANULAÇÃO DE PARCELAMENTO";
$head3 = "PARCELAMENTO Nº : {$oGet->parcel}";
$head4 = "Data da Simulação: ".date("d-m-Y",db_getsession("DB_datausu"));

$oPdf = new pdf();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(220);

$iAlt  = 5;
$iFont = 8;
$iCor  = 1;

$oTotalizador = new stdClass();
$oTotalizador->valor_historico_origem   = 0;
$oTotalizador->valor_corrigido_origem   = 0;
$oTotalizador->valor_juros_origem       = 0;
$oTotalizador->valor_multa_origem       = 0;
$oTotalizador->valor_total_origem       = 0;
$oTotalizador->v23_vlrabatido           = 0;
$oTotalizador->valor_historico_retorno  = 0;
$oTotalizador->valor_corrigido_retorno  = 0;
$oTotalizador->valor_juros_retorno      = 0;
$oTotalizador->valor_multa_retorno      = 0;
$oTotalizador->valor_total_retorno      = 0;

$oPdf->AddPage("L");

$oPdf->Ln();
$oPdf->SetFont("Arial","B",$iFont+2);
$oPdf->Cell(40,$iAlt,"SIMULAÇÃO DA ANULAÇÃO DE PARCELAMENTO Nº {$oGet->parcel}",0,1,"L",0);
$oPdf->Ln();


$oPdf->SetFont("Arial","B",$iFont);
$oPdf->Cell(90,$iAlt,"Posição das Dividas Parceladas"  ,1,0,"C",1);
$oPdf->Cell(90,$iAlt,"Posição dos Valores Pagos"       ,1,0,"C",1);
$oPdf->Cell(90,$iAlt,"Posição dos Valores de Retorno"  ,1,1,"C",1);

//Linha 1 
//Coluna 1
$oPdf->Cell(50,$iAlt,"Valor Historico"                                      ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_historico_origem,"f") ,1,0,"R",0);
//Coluna 2
$oPdf->Cell(50,$iAlt,"Valor das Parcelas Pagas"                             ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_parcelas_pagas,"f")         ,1,0,"R",0);
//Coluna 3
$oPdf->Cell(50,$iAlt,"Valor Histórico"                                      ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_historico_retorno,"f"),1,1,"R",0);


//Linha 2
//Coluna 1
$oPdf->Cell(50,$iAlt,"Valor Corrigido"                                      ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_corrigido_origem,"f") ,1,0,"R",0);
//Coluna 2
$oPdf->Cell(50,$iAlt,"Valor das Parcelas não Pagas"                         ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_parcelas_abertas,"f")       ,1,0,"R",0);
//Coluna 3
$oPdf->Cell(50,$iAlt,"Valor Corrigido"                                      ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_corrigido_retorno,"f"),1,1,"R",0);


//Linha 3
//Coluna 1
$oPdf->Cell(50,$iAlt,"Valor Juros"                                          ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_juros_origem,"f")     ,1,0,"R",0);
//Coluna 2
$oPdf->Cell(50,$iAlt,"% Abatimento"                                         ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->perc_abatimento,"f")              ,1,0,"R",0);
//Coluna 3
$oPdf->Cell(50,$iAlt,"Valor Juros"                                          ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_juros_retorno,"f")    ,1,1,"R",0);
                                                     
                                                     
//Linha 4 
//Coluna 1                                           
$oPdf->Cell(50,$iAlt,"Valor Multa"                                          ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_multa_origem,"f")     ,1,0,"R",0);
//Coluna 2
$oPdf->Cell(50,$iAlt,"% Retorno"                                            ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->perc_retorno,"f")                 ,1,0,"R",0);
//Coluna 3
$oPdf->Cell(50,$iAlt,"Valor Multa"                                          ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_multa_retorno,"f")    ,1,1,"R",0);
                                                     
                                                     
//Linha 5  
//Coluna 1                                          
$oPdf->Cell(50,$iAlt,"Valor Desconto"                                       ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_desconto_origem,"f")  ,1,0,"R",0);
//Coluna 2
$oPdf->Cell(50,$iAlt,"Total de Parcelas (Qtd)"                              ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,$oTotal->qtd_total_parcelas                            ,1,0,"R",0);
//Coluna 3
$oPdf->Cell(50,$iAlt,"Valor Desconto"                                       ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,"0.00"                                                 ,1,1,"R",0);
                                                     
                                                     
//Linha 6 
//Coluna 1                                           
$oPdf->Cell(50,$iAlt,"Valor Total"                                          ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_geral_origem,"f")     ,1,0,"R",0);
//Coluna 2
$oPdf->Cell(50,$iAlt,"Total de Parcelas Pagas (Qtd)"                        ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,str_pad($oTotal->qtd_parcelas_pagas,0,"0")       ,1,0,"R",0);
//Coluna 3
$oPdf->Cell(50,$iAlt,"Valor Total"                                          ,1,0,"L",1);
$oPdf->Cell(40,$iAlt,db_formatar($oTotal->valor_total_geral_retorno,"f")    ,1,1,"R",0);


foreach ( $aDadosSimulacao as $iInd => $oSimulacao ) {
	
  if( $oPdf->gety() > $oPdf->h-30 || $iInd == 0 ) {
    
  	if ( $iInd != 0 ) {
  	  $oPdf->AddPage('L');
  	}
		
    $oPdf->Ln();
		$oPdf->SetFont("Arial","B",$iFont);
		
		$oPdf->Cell(83,$iAlt,''                                         ,1,0,"C",1);
		$oPdf->Cell(89,$iAlt,'Origens ( até a data do termo) '          ,1,0,"C",1);
		$oPdf->Cell(17,$iAlt,'Abatimento'                              ,1,0,"C",1);
		$oPdf->Cell(89,$iAlt,'Retorno ( até a data corrente) '          ,1,1,"C",1);
		
		$oPdf->Cell(18,$iAlt,'Numpre'          ,1,0,"C",1);
		$oPdf->Cell(10,$iAlt,'Parc.'         ,1,0,"C",1);
		$oPdf->Cell(25,$iAlt,'Receita'         ,1,0,"C",1);
		$oPdf->Cell(15,$iAlt,'Dt. Oper'        ,1,0,"C",1);
		$oPdf->Cell(15,$iAlt,'Vencto.'         ,1,0,"C",1);
		$oPdf->Cell(17,$iAlt,'Vlr. Hist'       ,1,0,"C",1);
		$oPdf->Cell(18,$iAlt,'Vlr. Corr'       ,1,0,"C",1);
		$oPdf->Cell(18,$iAlt,'Vlr. Juros'      ,1,0,"C",1);
		$oPdf->Cell(18,$iAlt,'Vlr. Multa'      ,1,0,"C",1);
		$oPdf->Cell(18,$iAlt,'Vlr. Total'      ,1,0,"C",1);
    $oPdf->Cell(17,$iAlt,'Vlr. Abatido'    ,1,0,"C",1);		
		$oPdf->Cell(17,$iAlt,'Vlr. Hist'       ,1,0,"C",1);
		$oPdf->Cell(18,$iAlt,'Vlr. Corr'       ,1,0,"C",1);
		$oPdf->Cell(18,$iAlt,'Vlr. Juros'      ,1,0,"C",1);
		$oPdf->Cell(18,$iAlt,'Vlr. Multa'      ,1,0,"C",1);
		$oPdf->Cell(18,$iAlt,'Vlr. Total'      ,1,1,"C",1);		
	  	  
	}
	  		
	$oPdf->SetFont("Arial","",$iFont);
		    
	if ( $iCor == 1 ) {
	  $iCor = 0;
	} else {
	  $iCor = 1;
	}
	
	$oPdf->Cell(18,$iAlt,$oSimulacao->v23_numpre                                   ,0,0,"C",$iCor);
	$oPdf->Cell(10,$iAlt,$oSimulacao->v23_numpar                                   ,0,0,"C",$iCor);
	$oPdf->Cell(25,$iAlt,db_stdClass::normalizeStringJson($oSimulacao->v23_receit) ,0,0,"C",$iCor);
	$oPdf->Cell(15,$iAlt,db_formatar($oSimulacao->v23_dtoper,'d')                  ,0,0,"C",$iCor);
	$oPdf->Cell(15,$iAlt,db_formatar($oSimulacao->v23_dtvenc,'d')                  ,0,0,"C",$iCor);
	$oPdf->Cell(17,$iAlt,db_formatar($oSimulacao->v23_valor,'f')                   ,0,0,"R",$iCor);
	$oPdf->Cell(18,$iAlt,db_formatar($oSimulacao->grid_valor_corrigido_origem,'f')      ,0,0,"R",$iCor);
	$oPdf->Cell(18,$iAlt,db_formatar($oSimulacao->grid_valor_juros_origem,'f')          ,0,0,"R",$iCor);
	$oPdf->Cell(18,$iAlt,db_formatar($oSimulacao->grid_valor_multa_origem,'f')          ,0,0,"R",$iCor);
	$oPdf->Cell(18,$iAlt,db_formatar($oSimulacao->grid_valor_total_origem,'f')          ,0,0,"R",$iCor);
	$oPdf->Cell(17,$iAlt,db_formatar($oSimulacao->v23_vlrabatido,'f')              ,0,0,"R",$iCor);
	$oPdf->Cell(17,$iAlt,db_formatar($oSimulacao->grid_valor_historico_retorno,'f')     ,0,0,"R",$iCor);
	$oPdf->Cell(18,$iAlt,db_formatar($oSimulacao->grid_valor_corrigido_retorno,'f')     ,0,0,"R",$iCor);
  $oPdf->Cell(18,$iAlt,db_formatar($oSimulacao->grid_valor_juros_retorno,'f')         ,0,0,"R",$iCor);
	$oPdf->Cell(18,$iAlt,db_formatar($oSimulacao->grid_valor_multa_retorno,'f')         ,0,0,"R",$iCor);
	$oPdf->Cell(18,$iAlt,db_formatar($oSimulacao->grid_valor_total_retorno,'f')         ,0,1,"R",$iCor);

	
	$oTotalizador->valor_historico_origem   += $oSimulacao->v23_valor;         
  $oTotalizador->valor_corrigido_origem   += $oSimulacao->grid_valor_corrigido_origem;
  $oTotalizador->valor_juros_origem       += $oSimulacao->grid_valor_juros_origem;
  $oTotalizador->valor_multa_origem       += $oSimulacao->grid_valor_multa_origem;
  $oTotalizador->valor_total_origem       += $oSimulacao->grid_valor_total_origem;
  $oTotalizador->valor_abatido            += $oSimulacao->v23_vlrabatido;    
  $oTotalizador->valor_historico_retorno  += $oSimulacao->grid_valor_historico_retorno;
  $oTotalizador->valor_corrigido_retorno  += $oSimulacao->grid_valor_corrigido_retorno;
  $oTotalizador->valor_juros_retorno      += $oSimulacao->grid_valor_juros_retorno;
  $oTotalizador->valor_multa_retorno      += $oSimulacao->grid_valor_multa_retorno;
  $oTotalizador->valor_total_retorno      += $oSimulacao->grid_valor_total_retorno;
	
}

  $oPdf->SetFont("Arial","B",$iFont);
	$oPdf->Cell(83,$iAlt,"Totais"                                                ,1,0,"C",1);
	$oPdf->Cell(17,$iAlt,db_formatar($oTotalizador->valor_historico_origem,'f')  ,1,0,"R",1);
	$oPdf->Cell(18,$iAlt,db_formatar($oTotalizador->valor_corrigido_origem,'f')  ,1,0,"R",1);
	$oPdf->Cell(18,$iAlt,db_formatar($oTotalizador->valor_juros_origem,'f')      ,1,0,"R",1);
	$oPdf->Cell(18,$iAlt,db_formatar($oTotalizador->valor_multa_origem,'f')      ,1,0,"R",1);
	$oPdf->Cell(18,$iAlt,db_formatar($oTotalizador->valor_total_origem,'f')      ,1,0,"R",1);
	$oPdf->Cell(17,$iAlt,db_formatar($oTotalizador->valor_abatido,'f')           ,1,0,"R",1);
	$oPdf->Cell(17,$iAlt,db_formatar($oTotalizador->valor_historico_retorno,'f') ,1,0,"R",1);
	$oPdf->Cell(18,$iAlt,db_formatar($oTotalizador->valor_corrigido_retorno,'f') ,1,0,"R",1);
  $oPdf->Cell(18,$iAlt,db_formatar($oTotalizador->valor_juros_retorno,'f')     ,1,0,"R",1);
	$oPdf->Cell(18,$iAlt,db_formatar($oTotalizador->valor_multa_retorno,'f')     ,1,0,"R",1);
	$oPdf->Cell(18,$iAlt,db_formatar($oTotalizador->valor_total_retorno,'f')     ,1,1,"R",1);


$oPdf->Output();
?>