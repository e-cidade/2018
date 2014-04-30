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

require_once "fpdf151/pdf.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "libs/db_conecta.php";
require_once "libs/db_utils.php";
require_once "classes/db_recibopaga_classe.php";

ini_set("error_reporting","E_ALL & ~NOTICE");

$oGet          = db_utils::postMemory($_GET,0); 
$iInstit       = db_getsession('DB_instit');

$sSqlDbConfig  = " select db21_regracgmiss,
                          db21_regracgmiptu 
                     from db_config 
                    where codigo = {$iInstit} ";
                    
$rsSqlDbConfig = db_query($sSqlDbConfig);
$oDbConfig     = db_utils::fieldsMemory($rsSqlDbConfig,0);
$iRegra        = $oDbConfig->db21_regracgmiptu;

if($oGet->seltipo == "s"){
  $cabTipo = "Sintético";	
} else {
  $cabTipo = "Analítico";
}

$oDaoReciboPaga = new cl_recibopaga();
$sSqldescontoConcedUnica = $oDaoReciboPaga->sql_query_descontoConced_cotaUnica($iRegra, 
                                                                               $oGet->anoexe,
                                                                               $oGet->datai,
                                                                               $oGet->dataf);

$rsSql         = db_query($sSqldescontoConcedUnica);
$iNumRownsSql  = pg_num_rows($rsSql);

if ($iNumRownsSql == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$aDadosDesconto  = array();
$aResumoDesconto = array();

  for ( $iInd = 0; $iInd  < $iNumRownsSql; $iInd++ ) {
      
    $oDadosDesc = db_utils::fieldsMemory($rsSql,$iInd);

    if ( !isset($aDadosDescontoSintetico[$oDadosDesc->receita]) ) {
      $aDadosDescontoSintetico[$oDadosDesc->receita]['sDescricao'] = $oDadosDesc->descricao;
      $aDadosDescontoSintetico[$oDadosDesc->receita]['VlrCalc']    = $oDadosDesc->vlrcalculado;
      $aDadosDescontoSintetico[$oDadosDesc->receita]['VlrDesc']    = $oDadosDesc->vlrdesconto;
      $aDadosDescontoSintetico[$oDadosDesc->receita]['VlrPago']    = $oDadosDesc->vlrpago;
    } else {
      $aDadosDescontoSintetico[$oDadosDesc->receita]['VlrCalc']    += $oDadosDesc->vlrcalculado; 
      $aDadosDescontoSintetico[$oDadosDesc->receita]['VlrDesc']    += $oDadosDesc->vlrdesconto;
      $aDadosDescontoSintetico[$oDadosDesc->receita]['VlrPago']    += $oDadosDesc->vlrpago;
    }
    
    if ( !isset($aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]) ) {
    	$aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['Matricula']    = $oDadosDesc->matricula;
      $aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['Contribuinte'] = $oDadosDesc->contribuinte; 
      $aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['Receita']      = $oDadosDesc->receita;
      $aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['sDescricao']   = $oDadosDesc->descricao;
      $aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['VlrCalc']      = $oDadosDesc->vlrcalculado;
      $aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['VlrDesc']      = $oDadosDesc->vlrdesconto;
      $aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['VlrPago']      = $oDadosDesc->vlrpago;
      $aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['PersentDesc']  = $oDadosDesc->qtd;
    } else {
      $aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['VlrCalc']     += $oDadosDesc->vlrcalculado; 
      $aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['VlrDesc']     += $oDadosDesc->vlrdesconto;
      $aDadosDescontoAnalitico[$oDadosDesc->matricula][$oDadosDesc->receita]['VlrPago']     += $oDadosDesc->vlrpago;
    }
    
    if ( !isset($aResumoDesconto[$oDadosDesc->receita][$oDadosDesc->qtd]) ) {
      $aResumoDesconto[$oDadosDesc->receita][$oDadosDesc->qtd]['VlrDesc']    = $oDadosDesc->vlrdesconto;
      $aResumoDesconto[$oDadosDesc->receita][$oDadosDesc->qtd]['sDescricao'] = $oDadosDesc->descricao;
    } else {
      $aResumoDesconto[$oDadosDesc->receita][$oDadosDesc->qtd]['VlrDesc']   += $oDadosDesc->vlrdesconto;
    }
    
  }

$head2 = "RELATÓRIO DE DESCONTOS CONCEDIDOS";
$head4 = "EXERCÍCIO : ".$oGet->anoexe;
$head6 = "PERÍODO : ".db_formatar($oGet->datai,'d')." á ".db_formatar($oGet->dataf,'d');
$head8 = "TIPO : ".$cabTipo;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

if($oGet->seltipo == "s"){
	// Sintetico 
	
	$lRel = 'P';
	$iTamCellRes  = array();
	$iTamCellRes[0] = 19;
	$iTamCellRes[1] = 62;
	$iTamCellRes[2] = 46;
	
  $iTam = array();
  $iTam[0] = 110;
  $iTam[1] = 17;
  $iTam[2] = 35;
  $iTam[3] = 30;
	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(235);
	
	$nTotalVlrCalc = 0;
	$nTotalVlrDesc = 0;
	$nTotalVlrPago = 0;
	$cor           = 0;
	$prenc         = 0;
	$lImprime      = true;
	
	foreach ( $aDadosDescontoSintetico as $iReceita => $aDados ) {

	  if ($pdf->gety() > $pdf->h - 30 || $lImprime ){
	  	
      $lImprime = false;
      	  	
      $pdf->addpage($lRel);
	  
	    $pdf->SetY(38);
	    $pdf->SetFont('Arial','B',8);
	    $pdf->Cell(0,5,"Relatório Sintético de Desconto Concedidos ",0,1,"C",0);
	    $pdf->Cell(0,5,"Exercício ".$oGet->anoexe                   ,0,1,"C",0);
	        
	    $pdf->SetFont('Arial','B',8);
	    $pdf->SetY(50);
	    $pdf->Cell(30,5,"Receita"      ,1,0,"C",1);
	    $pdf->Cell(62,5,"Descrição"    ,1,0,"C",1);
	    $pdf->Cell(35,5,"Vlr Calculado",1,0,"C",1);
	    $pdf->Cell(35,5,"Vlr Desconto" ,1,0,"C",1);
	    $pdf->Cell(30,5,"Vlr Pago"     ,1,1,"C",1);
	    $pdf->SetY(56);
	        
	    $cor    = 0;
	    $prenc  = 0;
	  
	  }
	      
    if ($prenc == 0){
      $cor   = 0;
      $prenc = 1;
    } else {
      $cor   = 1;
      $prenc = 0;
    }
	
    $pdf->SetFont('Arial','',6);    
    $pdf->Cell(30,5,$iReceita                           ,0,0,"C",$cor);
    $pdf->Cell(62,5,$aDados['sDescricao']               ,0,0,"L",$cor);
    $pdf->Cell(35,5,db_formatar($aDados['VlrCalc'],'f') ,0,0,"R",$cor);
    $pdf->Cell(35,5,db_formatar($aDados['VlrDesc'],'f') ,0,0,"R",$cor);
	  $pdf->Cell(30,5,db_formatar($aDados['VlrPago'],'f') ,0,1,"R",$cor);
	      
	  $nTotalVlrCalc = $nTotalVlrCalc + $aDados['VlrCalc'];
	  $nTotalVlrDesc = $nTotalVlrDesc + $aDados['VlrDesc'];
	  $nTotalVlrPago = $nTotalVlrPago + $aDados['VlrPago'];
	      
	}

} else {
	// Analitico
	
	$lRel = 'L';
	$iTamCellRes   = array();
  $iTamCellRes[0] = 60;
  $iTamCellRes[1] = 67;
  $iTamCellRes[2] = 46;
  
  $iTam = array();
  $iTam[0] = 195;
  $iTam[1] = 24;
  $iTam[2] = 30;
  $iTam[3] = 30;
  
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(235);
  
  $nTotalVlrCalc = 0;
  $nTotalVlrDesc = 0;
  $nTotalVlrPago = 0;
  $cor           = 0;
  $prenc         = 0;
  $lImprime      = true;

  foreach ( $aDadosDescontoAnalitico as $iMatricula => $aDadosMatricula ) {
  	
	  	foreach ( $aDadosMatricula as $iReceita => $aDados ) {
	  		
			    if ($pdf->gety() > $pdf->h - 30 || $lImprime ){
			      
			      $lImprime = false;
			            
			      $pdf->addpage($lRel);
			          
						$pdf->SetFont('Arial','B',8);
						$pdf->SetY(35);
						$pdf->Cell(20,5,"Matrícula",1,0,"C",1);
						$pdf->Cell(60,5,"Contribuinte",1,0,"C",1);
						$pdf->Cell(30,5,"Receita",1,0,"C",1);
						$pdf->Cell(49,5,"Descrição",1,0,"C",1);
						$pdf->Cell(30,5,"Quant. Desconto",1,0,"C",1);
						$pdf->Cell(30,5,"Vlr Calculado",1,0,"C",1);
						$pdf->Cell(30,5,"Vlr Desconto",1,0,"C",1);
						$pdf->Cell(30,5,"Vlr Pago",1,1,"C",1);
			      
			      $cor    = 0;
			      $prenc  = 0;
			    
			    }
			        
			    if ($prenc == 0){
			      $cor   = 0;
			      $prenc = 1;
			    } else {
			      $cor   = 1;
			      $prenc = 0;
			    }
			
			    $pdf->SetFont('Arial','',6);
			    $pdf->Cell(20,5,$iMatricula                          ,0,0,"C",$cor);
			    $pdf->Cell(60,5,$aDados['Contribuinte']              ,0,0,"L",$cor);
			    $pdf->Cell(30,5,$aDados['Receita']                   ,0,0,"C",$cor);
			    $pdf->Cell(49,5,$aDados['sDescricao']                ,0,0,"C",$cor);
			    $pdf->Cell(30,5,$aDados['PersentDesc']."%"           ,0,0,"C",$cor);
			    $pdf->Cell(30,5,db_formatar($aDados['VlrCalc'],'f')  ,0,0,"R",$cor);
			    $pdf->Cell(30,5,db_formatar($aDados['VlrDesc'],'f')  ,0,0,"R",$cor);
			    $pdf->Cell(30,5,db_formatar($aDados['VlrPago'],'f')  ,0,1,"R",$cor);
			    
			    $nTotalVlrCalc = $nTotalVlrCalc + $aDados['VlrCalc'];
			    $nTotalVlrDesc = $nTotalVlrDesc + $aDados['VlrDesc'];
			    $nTotalVlrPago = $nTotalVlrPago + $aDados['VlrPago'];
			        
			  }
			  
	  }
	  
}

  $pdf->Cell(190,5,"",0,1,"R",0);
  $pdf->SetFont('Arial','B',6);
    
  $pdf->Cell($iTam[0],5,"TOTAIS: ",0,0,"R",0);
  $pdf->Cell($iTam[1],5,db_formatar($nTotalVlrCalc,'f'),0,0,"R",0);
  $pdf->Cell($iTam[2],5,db_formatar($nTotalVlrDesc,'f'),0,0,"R",0);
  $pdf->Cell($iTam[3],5,db_formatar($nTotalVlrPago,'f'),0,1,"R",0);

  // Resumo 
  $lImprime      = true;
  $nTotalVlrDesc = 0;
  
  foreach ( $aResumoDesconto as $iReceita => $aQtdDesc ) {
    foreach ( $aQtdDesc as $iQtdDesc => $aDados ) {
      
      if ($pdf->gety() > $pdf->h - 30  || $lImprime  ){
    
         $lImprime = false;
         
         $pdf->addpage($lRel);
        
         $pdf->SetFont('Arial','B',8);
         $pdf->SetY(38);
         $pdf->Cell(0,5,"Resumo de Descontos Concedidos em Cota Única ",0,1,"C",0);
         $pdf->Cell(0,5,"Exercício ".$oGet->anoexe                     ,0,1,"C",0);
            
         $pdf->SetFont('Arial','B',8);
         $pdf->SetY(51);
         $pdf->Cell($iTamCellRes[0],5,""                             ,0,0,"C",0);
         $pdf->Cell($iTamCellRes[1],5,"Código e Descrição da Receita",1,0,"C",1);
         $pdf->Cell($iTamCellRes[1],5,"Quant. Desconto"              ,1,0,"C",1);
         $pdf->Cell($iTamCellRes[2],5,"Valor do Desconto"            ,1,0,"C",1);
         $pdf->Cell($iTamCellRes[0],5,""                             ,0,1,"C",0);
              
         $cor    = 0;
         $prenc  = 0;
        
      }
            
      if ($prenc == 0){
        $cor   = 0;
        $prenc = 1;
      } else {
        $cor   = 1;
        $prenc = 0;
      }
      
      $pdf->SetFont('Arial','',7);
      $pdf->Cell($iTamCellRes[0],5,""                                    ,0,0,"C",0);
      $pdf->Cell($iTamCellRes[1],5,$iReceita." - ".$aDados['sDescricao'] ,1,0,"L",0);
      $pdf->Cell($iTamCellRes[1],5,$iQtdDesc."%"                         ,1,0,"C",0);
      $pdf->Cell($iTamCellRes[2],5,db_formatar($aDados['VlrDesc'],'f')   ,1,0,"R",0);
      $pdf->Cell($iTamCellRes[0],5,""                                    ,0,1,"C",0);

      $nTotalVlrDesc += $aDados['VlrDesc'];
      
    }
  }
  
  $pdf->SetFont('Arial','B',8);
    
  $pdf->Cell($iTamCellRes[0],5,""                             ,0,0,"C",0); 
  $pdf->Cell($iTamCellRes[1],5,"Total"                        ,1,0,"L",0);
  $pdf->Cell($iTamCellRes[1],5,""                             ,1,0,"L",0);
  $pdf->Cell($iTamCellRes[2],5,db_formatar($nTotalVlrDesc,'f'),1,0,"R",0);
  $pdf->Cell($iTamCellRes[0],5,""                             ,0,1,"C",0);

$pdf->output();
?>