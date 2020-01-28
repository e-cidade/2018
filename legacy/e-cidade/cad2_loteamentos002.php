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

require_once("libs/db_utils.php");
require_once("fpdf151/pdf.php");

$oGet   = db_utils::postmemory($_GET);

$lLista = false;

if ( isset($oGet->ordem) ) {
	
	if($oGet->ordem == "a"){
	  $sOrder = "j34_descr ";
	  $sOrdem = "Alfabética";
	} else {
	  $sOrder = "j34_loteam ";
	  $sOrdem = "Numérica";
	}
}

if ( isset($oGet->lista) ) {

  if($oGet->lista == "s"){
  	
    $sLista = 'Sim';
    $lLista = true;
  } else {
  	
    $sLista = 'Não';
    $lLista = false;
  }
}

if ( $lLista ) {

	$sSql   = "    select loteam.*,                                                                  ";
  $sSql  .= "           loteamcgm.*,                                                               ";	
  $sSql  .= "           cgm.z01_numcgm,                                                            ";  
  $sSql  .= "           cgm.z01_nome                                                               ";  
	$sSql  .= "      from loteam                                                                     ";
	$sSql  .= "           left  join loteamcgm  on loteamcgm.j120_loteam = loteam.j34_loteam         ";
  $sSql  .= "           left  join cgm        on cgm.z01_numcgm        = loteamcgm.j120_cgm        ";
	$sSql  .= "  order by {$sOrder}                                                                  ";	
} else {

	$sSql   = "    select *                                                                          ";
	$sSql  .= "      from loteam                                                                     ";
	$sSql  .= "  order by {$sOrder}                                                                  "; 
}

$rsSql        = db_query($sSql);
$iNumRownsSql = pg_num_rows($rsSql);

if ($iNumRownsSql == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$head2 = "RELATÓRIO DE LOTEAMENTOS";
$head4 = "LISTA DE RESPONSÁVEIS: {$sLista}";
$head6 = "ORDEM: {$sOrdem}";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);

$lImprime = true;
$sLetra   = 'arial';

if ( $lLista ) {
	
  $aDadosLoteamento      = array();
  $aDadosCgm             = array();
  $iTotalGeralAreaConstr = 0;
  $iTotalGeralAreaPublic = 0;
  $iTotalGeralAreaTotal  = 0;
  $iTotalReg             = 0;
  $lFundo                = false;
  
  for ( $iInd = 0; $iInd  < $iNumRownsSql; $iInd++ ) {
    
      $oDadosLoteAm = db_utils::fieldsMemory($rsSql,$iInd);

      $oDadosLoteamento = new stdClass();
      $oDadosLoteamento->Codigo          = $oDadosLoteAm->j34_loteam;
      $oDadosLoteamento->Descricao       = $oDadosLoteAm->j34_descr;
      $oDadosLoteamento->AreaConstr      = $oDadosLoteAm->j34_areacc;
      $oDadosLoteamento->AreaPublic      = $oDadosLoteAm->j34_areapc;
      $oDadosLoteamento->AreaTotal       = $oDadosLoteAm->j34_areato;      
      
     if ( !isset($aDadosLoteamento[$oDadosLoteAm->j34_loteam]) ) {
       $aDadosLoteamento[$oDadosLoteAm->j34_loteam]['oDadosLoteamento'] = $oDadosLoteamento;
       $aDadosLoteamento[$oDadosLoteAm->j34_loteam]['oDadosCgm'] = array();
     }  
     
     if ( !empty($oDadosLoteAm->z01_numcgm) ) {
        
       $oDadosCGM = new stdClass();
       $oDadosCGM->NumCgm          = $oDadosLoteAm->z01_numcgm;
       $oDadosCGM->Nome            = $oDadosLoteAm->z01_nome;
       
       $aDadosLoteamento[$oDadosLoteAm->j34_loteam]['oDadosCgm'][] = $oDadosCGM;      
     }
  }

  foreach ( $aDadosLoteamento as $iCodigo => $aDadosListaResp ) {
    
    $sDescr = substr($aDadosListaResp['oDadosLoteamento']->Descricao,0,35);
    
    if ($pdf->gety() > $pdf->h - 30  || $lImprime  ) {
      
      $lImprime = false;
      $lFundo   = false;
      $pdf->addpage();
      
      $pdf->SetFont($sLetra,'B',8);
      $pdf->Cell(20,5,"Código",1,0,"C",1);   
      $pdf->Cell(68,5,"Descrição",1,0,"C",1); 
      $pdf->Cell(35,5,"Área Construída",1,0,"C",1);   
      $pdf->Cell(35,5,"Área Pública",1,0,"C",1);   
      $pdf->Cell(35,5,"Área Total",1,1,"C",1); 

      $pdf->Cell(88,5,"",0,0,"C",0);
      $pdf->Cell(30,5,"CGM",1,0,"C",1);   
      $pdf->Cell(75,5,"Nome Responsável",1,1,"C",1); 
    }
    
    if ( $lFundo ) {
      
      $lFundo     = false;
      $iPreencher = 1;
    } else {
      
      $lFundo     = true;
      $iPreencher = 0;
    }
    
    $pdf->SetFont($sLetra,'B',8);
    $pdf->Cell(20,5,$aDadosListaResp['oDadosLoteamento']->Codigo,0,0,"C",$iPreencher);   
    $pdf->Cell(68,5,$sDescr,0,0,"L",$iPreencher); 
    $pdf->Cell(35,5,$aDadosListaResp['oDadosLoteamento']->AreaConstr,0,0,"R",$iPreencher);   
    $pdf->Cell(35,5,$aDadosListaResp['oDadosLoteamento']->AreaPublic,0,0,"R",$iPreencher);   
    $pdf->Cell(35,5,$aDadosListaResp['oDadosLoteamento']->AreaTotal,0,1,"R",$iPreencher);   

    foreach ( $aDadosListaResp['oDadosCgm'] as $iInd => $aDadosCGM ) {
    	
      $pdf->Cell(88,5,""                            ,0,0,"C",$iPreencher);
      $pdf->Cell(30,5,$aDadosCGM->NumCgm            ,0,0,"C",$iPreencher);   
      $pdf->Cell(75,5,substr($aDadosCGM->Nome,0,35) ,0,1,"L",$iPreencher);  
    }
    
    $iTotalGeralAreaConstr += $aDadosListaResp['oDadosLoteamento']->AreaConstr;
    $iTotalGeralAreaPublic += $aDadosListaResp['oDadosLoteamento']->AreaPublic;
    $iTotalGeralAreaTotal  += $aDadosListaResp['oDadosLoteamento']->AreaTotal;
    $iTotalReg++;
  }
  
$pdf->Cell(192,2,""                                                     ,0,1,0,0);
$pdf->Cell(192,0,""                                                     ,"T",1,0,0);
$pdf->ln(0);
$pdf->SetFont($sLetra,"B",5);
$pdf->cell(22,3,'Total Registros:  '                                   ,0,0,"R",0);
$pdf->cell(22,3,$iTotalReg                                             ,0,0,"L",0);
$pdf->cell(44,3,'Total Geral:  '                                       ,0,0,"R",0);
$pdf->Cell(35,5,$iTotalGeralAreaConstr                                 ,0,0,"R",0);   
$pdf->Cell(35,5,$iTotalGeralAreaPublic                                 ,0,0,"R",0);   
$pdf->Cell(35,5,$iTotalGeralAreaTotal                                  ,0,1,"R",0);
} else {
	
	$aDadosLoteamento      = array();
	$iTotalGeralAreaConstr = 0;
	$iTotalGeralAreaPublic = 0;
	$iTotalGeralAreaTotal  = 0;
	$lFundo                = false;
	$iTotalReg             = $iNumRownsSql;
	
	for ( $iInd = 0; $iInd  < $iNumRownsSql; $iInd++ ) {
		
	    $oDadosLoteAm = db_utils::fieldsMemory($rsSql,$iInd);

      $oDadosLoteamento = new stdClass();
      $oDadosLoteamento->Codigo          = $oDadosLoteAm->j34_loteam;
      $oDadosLoteamento->Descricao       = $oDadosLoteAm->j34_descr;
      $oDadosLoteamento->AreaConstr      = $oDadosLoteAm->j34_areacc;
      $oDadosLoteamento->AreaPublic      = $oDadosLoteAm->j34_areapc;
      $oDadosLoteamento->AreaTotal       = $oDadosLoteAm->j34_areato;
                               
     if ( !isset($aDadosLoteamento[$oDadosLoteAm->j34_loteam]) ) {
       $aDadosLoteamento[$oDadosLoteAm->j34_loteam]['oDadosLoteamento']  = $oDadosLoteamento;
     }
	}
	
	foreach ( $aDadosLoteamento as $iCodigo => $aDadosListaResp ) {
		
		$sDescr = substr($aDadosListaResp['oDadosLoteamento']->Descricao,0,35);
		
    if ($pdf->gety() > $pdf->h - 30  || $lImprime  ) {
      
      $lImprime = false;
      $lFundo   = false;
      $pdf->addpage();
      
      $pdf->SetFont($sLetra,'B',8);
      $pdf->Cell(20,5,"Código",1,0,"C",1);   
      $pdf->Cell(68,5,"Descrição",1,0,"C",1); 
      $pdf->Cell(35,5,"Área Construída",1,0,"C",1);   
      $pdf->Cell(35,5,"Área Pública",1,0,"C",1);   
      $pdf->Cell(35,5,"Área Total",1,1,"C",1);  
    }
    
    if ( $lFundo ) {
    	
    	$lFundo     = false;
    	$iPreencher = 1;
    } else {
    	
    	$lFundo     = true;
    	$iPreencher = 0;
    }
    
    $pdf->SetFont($sLetra,'B',8);
    $pdf->Cell(20,5,$aDadosListaResp['oDadosLoteamento']->Codigo,0,0,"C",$iPreencher);   
    $pdf->Cell(68,5,$sDescr,0,0,"L",$iPreencher); 
    $pdf->Cell(35,5,$aDadosListaResp['oDadosLoteamento']->AreaConstr,0,0,"R",$iPreencher);   
    $pdf->Cell(35,5,$aDadosListaResp['oDadosLoteamento']->AreaPublic,0,0,"R",$iPreencher);   
    $pdf->Cell(35,5,$aDadosListaResp['oDadosLoteamento']->AreaTotal,0,1,"R",$iPreencher); 	

    $iTotalGeralAreaConstr += $aDadosListaResp['oDadosLoteamento']->AreaConstr;
    $iTotalGeralAreaPublic += $aDadosListaResp['oDadosLoteamento']->AreaPublic;
    $iTotalGeralAreaTotal  += $aDadosListaResp['oDadosLoteamento']->AreaTotal;
	}
	
$pdf->Cell(192,2,""                                                     ,0,1,0,0);
$pdf->Cell(192,0,""                                                     ,"T",1,0,0);
$pdf->ln(0);
$pdf->SetFont($sLetra,"B",5);
$pdf->cell(22,3,'Total Registros:  '                                   ,0,0,"R",0);
$pdf->cell(22,3,$iTotalReg                                             ,0,0,"L",0);
$pdf->cell(44,3,'Total Geral:  '                                       ,0,0,"R",0);
$pdf->Cell(35,5,$iTotalGeralAreaConstr                                 ,0,0,"R",0);   
$pdf->Cell(35,5,$iTotalGeralAreaPublic                                 ,0,0,"R",0);   
$pdf->Cell(35,5,$iTotalGeralAreaTotal                                  ,0,1,"R",0);
}

$pdf->output();
?>