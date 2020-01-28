<?
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

  require_once("fpdf151/pdf.php");
  require_once("libs/db_utils.php");
  require_once("std/db_stdClass.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_liborcamento.php");
  require_once("model/ppa.model.php");
  require_once("model/ppaVersao.model.php");
  
  $oGet = db_utils::postMemory($_GET);
  $oPPA = new ppa($oGet->ppalei,1, $oGet->ppaversao);
  $oPPA->setInstituicoes($oGet->sInstit); 
  $oPPAVersao     = new ppaVersao($oGet->ppaversao);
  $lImprimeReceitaCorrente 		 = true;
  $lImprimeReceitaCapital 		 = true;
  $lImprimeReceitaCorrenteIntra  = true;
  $lImprimeReceitaCapitalIntra   = true;
  
  $lImprimeDeducoesCorrente 	 = true;
  $lImprimeDeducoesCapital 		 = true;
  $lImprimeDeducoesCorrenteIntra = true;
  $lImprimeDeducoesCapitalIntra  = true;  
  
  try { 
    $aReceitasCorrente	     = $oPPA->getQuadroEstimativas(41);
  } catch (Exception $eException ) {
  	$lImprimeReceitaCorrente = false;
  }
  try {   
    $aReceitasCapital	    = $oPPA->getQuadroEstimativas(42);
  } catch (Exception $eException ) {
  	$lImprimeReceitaCapital = false;
  }    
  try {     
    $aReceitasCorrenteIntra = $oPPA->getQuadroEstimativas(47);
  } catch (Exception $eException ) {
	$lImprimeReceitaCorrenteIntra = false;
  }
      
  try {     
    $aReceitasCapitalIntra  = $oPPA->getQuadroEstimativas(48);
  } catch (Exception $eException ) {
	$lImprimeReceitaCapitalIntra  = false;
  }
  
  
  try {  
    $aDeducoesCorrente = $oPPA->getQuadroEstimativas(91);
  } catch (Exception $eException ) {
    $lImprimeDeducoesCorrente  = false;
  }

  try {
    $aDeducoesCapital	      = $oPPA->getQuadroEstimativas(92);
  } catch (Exception $eException ) {
    $lImprimeDeducoesCapital = false;

  }
      
  try {
    $aDeducoesCorrenteIntra = $oPPA->getQuadroEstimativas(97);
  } catch (Exception $eException ) {
    $lImprimeDeducoesCorrenteIntra = false;
  }
      
  try {
    $aDeducoesCapitalIntra  = $oPPA->getQuadroEstimativas(98);
  } catch (Exception $eException ) {
    $lImprimeDeducoesCapitalIntra  = false;  
  }      

  $aAno = array();
  
  for ( $iInd = $oGet->anoini; $iInd <= $oGet->anofin; $iInd++ ) {
  	$aAno[] = $iInd;
  }
  
  $iAno1 = $aAno[0];
  $iAno2 = $aAno[1];
  $iAno3 = $aAno[2];
  $iAno4 = $aAno[3];

  
  $nTotalAno1 = 0;
  $nTotalAno2 = 0;
  $nTotalAno3 = 0;
  $nTotalAno4 = 0;
  
  
  $head2 = "RESUMO GERAL DA PREVISÃO DAS RECEITAS";
  $head3  = "PPA - {$oGet->anoini} - {$oGet->anofin}";
  $head4  = "Perspectiva: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  
  $alt = 5;
  $pdf->setfont('arial','B',8);
  $pdf->addpage();
  
  $pdf->cell(70 ,$alt,"Origens"				 	 ,"TBR",0,"C",0);
  $pdf->cell(120,$alt,"Previsão até o Término de","TBL",1,"C",0);
  
  $pdf->cell(70,$alt,""	    ,"TBR",0,"C",0);
  $pdf->cell(30,$alt,$iAno1	,"TBR",0,"C",0);  
  $pdf->cell(30,$alt,$iAno2	,"TBR",0,"C",0);
  $pdf->cell(30,$alt,$iAno3	,"TBR",0,"C",0);
  $pdf->cell(30,$alt,$iAno4	,"TBL",1,"C",0);
  
  $iGetYCabecalho = $pdf->GetY();
  
  $pdf->Ln();
  
  $pdf->setfont('arial','',8);

  if ( $lImprimeReceitaCorrente ) {
    foreach ( $aReceitasCorrente as $iInd => $oDados ){
      if ( substr($oDados->iEstrutural,3,14) == "0000000000000" ) {
    	
        if ($oDados->iEstrutural{2} == 0) {
      	  $sDescrReceita = " ".urldecode($oDados->sDescricao);
      	  
  		  $nTotalAno1 += $oDados->aEstimativas[$iAno1];
  		  $nTotalAno2 += $oDados->aEstimativas[$iAno2];
  		  $nTotalAno3 += $oDados->aEstimativas[$iAno3];
  		  $nTotalAno4 += $oDados->aEstimativas[$iAno4];      	  
        } else {
      	  $sDescrReceita = "   ".ucwords(strtolower(urldecode($oDados->sDescricao)));	
        }
      
        $pdf->cell(70,$alt,$sDescrReceita								,"TBR",0,"L",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno1],"f"),"TBR",0,"R",0);  
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno2],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno3],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno4],"f"),"TBL",1,"R",0);
        
      }
    }
  }
  
  if ( $lImprimeReceitaCapital ) {
    foreach ( $aReceitasCapital as $iInd => $oDados ){
      if ( substr($oDados->iEstrutural,3,14) == "0000000000000" ) {
    	
        if ($oDados->iEstrutural{2} == 0) {
      	  $sDescrReceita = " ".urldecode($oDados->sDescricao);
      	  
  		  $nTotalAno1 += $oDados->aEstimativas[$iAno1];
  		  $nTotalAno2 += $oDados->aEstimativas[$iAno2];
  		  $nTotalAno3 += $oDados->aEstimativas[$iAno3];
  		  $nTotalAno4 += $oDados->aEstimativas[$iAno4];      	  
      	  
        } else {
          $sDescrReceita = "   ".ucwords(strtolower(urldecode($oDados->sDescricao)));	
        }
      
        $pdf->cell(70,$alt,$sDescrReceita								 ,"TBR",0,"L",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno1],"f"),"TBR",0,"R",0);  
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno2],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno3],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno4],"f"),"TBL",1,"R",0);        
        
      }
    }
  }
  
  insereLinhaBranco($pdf,$alt);
  
  if ( $lImprimeReceitaCorrenteIntra ) {
    foreach ( $aReceitasCorrenteIntra as $iInd => $oDados ){
      if ( substr($oDados->iEstrutural,2,14) == "00000000000000" ) {
      	
        $pdf->cell(70,$alt," ".urldecode($oDados->sDescricao)		     ,"TBR",0,"L",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno1],"f"),"TBR",0,"R",0);  
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno2],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno3],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno4],"f"),"TBL",1,"R",0);        
        
  		$nTotalAno1 += $oDados->aEstimativas[$iAno1];
  		$nTotalAno2 += $oDados->aEstimativas[$iAno2];
  		$nTotalAno3 += $oDados->aEstimativas[$iAno3];
 	    $nTotalAno4 += $oDados->aEstimativas[$iAno4];
  		        
      }
    }
  }
  
  if ( $lImprimeReceitaCapitalIntra ) {
    foreach ( $aReceitasCapitalIntra as $iInd => $oDados ){
      if ( substr($oDados->iEstrutural,2,14) == "0000000000000" ) {
      	
        $pdf->cell(70,$alt," ".urldecode($oDados->sDescricao)            ,"TBR",0,"L",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno1],"f"),"TBR",0,"R",0);  
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno2],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno3],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno4],"f"),"TBL",1,"R",0);        
        
  		$nTotalAno1 += $oDados->aEstimativas[$iAno1];
  		$nTotalAno2 += $oDados->aEstimativas[$iAno2];
  		$nTotalAno3 += $oDados->aEstimativas[$iAno3];
 	    $nTotalAno4 += $oDados->aEstimativas[$iAno4];
 	            
      }
    }  
  }

  insereLinhaBranco($pdf,$alt);


  if ( $lImprimeDeducoesCorrente ) {
    foreach ( $aDeducoesCorrente as $iInd => $oDados ){
      if ( substr($oDados->iEstrutural,3,14) == "0000000000000" ) {
    	
        if ($oDados->iEstrutural{2} == 0) {
      	  $sDescrReceita = " ".urldecode($oDados->sDescricao);
      	  
  		  $nTotalAno1 += $oDados->aEstimativas[$iAno1];
  		  $nTotalAno2 += $oDados->aEstimativas[$iAno2];
  		  $nTotalAno3 += $oDados->aEstimativas[$iAno3];
  		  $nTotalAno4 += $oDados->aEstimativas[$iAno4];      	  
              	  
        } else {
       	  $sDescrReceita = "   ".ucwords(strtolower(urldecode($oDados->sDescricao)));	
        }

        $pdf->cell(70,$alt,$sDescrReceita								 ,"TBR",0,"L",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno1],"f"),"TBR",0,"R",0);  
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno2],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno3],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno4],"f"),"TBL",1,"R",0);        

      }
    }
  }
  
  if ( $lImprimeDeducoesCapital ) {
    foreach ( $aDeducoesCapital as $iInd => $oDados ){
      if ( substr($oDados->iEstrutural,3,14) == "0000000000000" ) {
    	
        if ($oDados->iEstrutural{2} == 0) {
      	  $sDescrReceita = " ".urldecode($oDados->sDescricao);
      	  
  		  $nTotalAno1 += $oDados->aEstimativas[$iAno1];
  		  $nTotalAno2 += $oDados->aEstimativas[$iAno2];
  		  $nTotalAno3 += $oDados->aEstimativas[$iAno3];
  		  $nTotalAno4 += $oDados->aEstimativas[$iAno4];
  		    		        	  
        } else {
      	  $sDescrReceita = "   ".ucwords(strtolower(urldecode($oDados->sDescricao)));	
        }
      
        $pdf->cell(70,$alt,$sDescrReceita								 ,"TBR",0,"L",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno1],"f"),"TBR",0,"R",0);  
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno2],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno3],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno4],"f"),"TBL",1,"R",0);
                
      }
    }  
  }
  
  insereLinhaBranco($pdf,$alt);
  
  if ( $lImprimeDeducoesCorrenteIntra ) {  
    foreach ( $aDeducoesCorrenteIntra as $iInd => $oDados ){
      if ( substr($oDados->iEstrutural,2,14) == "0000000000000" ) {
        $pdf->cell(70,$alt," ".urldecode($oDados->sDescricao)            ,"TBR",0,"L",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno1],"f"),"TBR",0,"R",0);  
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno2],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno3],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno4],"f"),"TBL",1,"R",0);              

  		$nTotalAno1 += $oDados->aEstimativas[$iAno1];
  		$nTotalAno2 += $oDados->aEstimativas[$iAno2];
  		$nTotalAno3 += $oDados->aEstimativas[$iAno3];
  		$nTotalAno4 += $oDados->aEstimativas[$iAno4];        
  		                
      }
    }
  }
  
  if ( $lImprimeDeducoesCapitalIntra ) {
    foreach ( $aDeducoesCapitalIntra as $iInd => $oDados ){
      if ( substr($oDados->iEstrutural,2,14) == "0000000000000" ) {
        $pdf->cell(70,$alt," ".urldecode($oDados->sDescricao)            ,"TBR",0,"L",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno1],"f"),"TBR",0,"R",0);  
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno2],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno3],"f"),"TBR",0,"R",0);
        $pdf->cell(30,$alt,db_formatar($oDados->aEstimativas[$iAno4],"f"),"TBL",1,"R",0);              

  		$nTotalAno1 += $oDados->aEstimativas[$iAno1];
  		$nTotalAno2 += $oDados->aEstimativas[$iAno2];
  		$nTotalAno3 += $oDados->aEstimativas[$iAno3];
  		$nTotalAno4 += $oDados->aEstimativas[$iAno4];        
  		        
      }
    }  
  }
  
  $pdf->SetY($iGetYCabecalho);
  $pdf->setfont('arial','B',8);
  
  $pdf->cell(70,$alt,"RECEITA ORÇAMENTÁRIA"		 ,"TBR",0,"L",0);
  $pdf->cell(30,$alt,db_formatar($nTotalAno1,"f"),"TBR",0,"R",0);  
  $pdf->cell(30,$alt,db_formatar($nTotalAno2,"f"),"TBR",0,"R",0);
  $pdf->cell(30,$alt,db_formatar($nTotalAno3,"f"),"TBR",0,"R",0);
  $pdf->cell(30,$alt,db_formatar($nTotalAno4,"f"),"TBL",1,"R",0);  
  
  
  
  $pdf->Output();

  
  function insereLinhaBranco($oPdf,$iAlt){
  	
    $oPdf->cell(70,$iAlt,"","TBR",0,"L",0);
    $oPdf->cell(30,$iAlt,"","TBR",0,"C",0);  
    $oPdf->cell(30,$iAlt,"","TBR",0,"C",0);
    $oPdf->cell(30,$iAlt,"","TBR",0,"C",0);
    $oPdf->cell(30,$iAlt,"","TBL",1,"C",0);
      	
  }

?>