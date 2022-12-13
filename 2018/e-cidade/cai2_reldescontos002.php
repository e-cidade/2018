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

require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("fpdf151/pdf.php");

$oGet         = db_utils::postMemory($_GET);
$iInstit      = db_getsession("DB_instit");
$lDebugSQL    = false; 

$iAnoUsu      = db_getsession('DB_anousu');
$dtDataIni    = $oGet->datai;
$dtDataFim    = $oGet->dataf;

$aAgrupador['receita']     = 'receit';
$aAgrupador['tipo_debito'] = 'tipo';

//$aAgrupador['proced']      = 'proced';
//$aAgrupador['tipo_proced'] = 'proced';

$oDaoArrepaga  = db_utils::getDao('arrepaga');

$sSqlDescontos = $oDaoArrepaga->sql_queryDescontoConcedidoPorRegra($dtDataIni, $dtDataFim);

$rsDescontos   = $oDaoArrepaga->sql_record($sSqlDescontos);

if ( $oDaoArrepaga->numrows ==  0 ) {
	db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!");
}
 
for ( $iInd = 0; $iInd < $oDaoArrepaga->numrows; $iInd++ ) {
	
	$oDesconto = db_utils::fieldsMemory($rsDescontos, $iInd);
	
  $aAnalitico[] = $oDesconto; 
	
	if ( isset($aSintetico[$oDesconto->receit][$oDesconto->receitorc]) ) {
		
		$aSintetico[$oDesconto->receit][$oDesconto->receitorc]['nValorPagar'] += $oDesconto->valor_pagar; 
		$aSintetico[$oDesconto->receit][$oDesconto->receitorc]['nValorPago']  += $oDesconto->valor_pago; 
		$aSintetico[$oDesconto->receit][$oDesconto->receitorc]['nDesconto']   += $oDesconto->desconto;
		
	} else {
		
		$aSintetico[$oDesconto->receit][$oDesconto->receitorc]['sDescricao']  = $oDesconto->descrreceit;
    $aSintetico[$oDesconto->receit][$oDesconto->receitorc]['nValorPagar'] = $oDesconto->valor_pagar; 
    $aSintetico[$oDesconto->receit][$oDesconto->receitorc]['nValorPago']  = $oDesconto->valor_pago;  
    $aSintetico[$oDesconto->receit][$oDesconto->receitorc]['nDesconto']   = $oDesconto->desconto;		
		
	}

  foreach ( $aAgrupador as $sDescrAgrupa => $sCampo ) {
    
    if ( $sDescrAgrupa == 'proced' ) {
      $sDescricao = $oDesconto->descrproced;
    } else if ( $sDescrAgrupa == 'tipo_proced' ) {
      $sDescricao = $oDesconto->descrtipoproced;
    } else if ( $sDescrAgrupa == 'receita' ) {
      $sDescricao = $oDesconto->descrreceit;
    } else {
      $sDescricao = $oDesconto->descrtipo;
    }   

    if ( isset($aResumos[$sDescrAgrupa][$oDesconto->$sCampo]) ) {
      /*$aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nVlrHist'] += $oDesconto->vlrhist;
      $aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nVlrCorr'] += $oDesconto->vlrcorr;
      $aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nMulta']   += $oDesconto->multa;
      $aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nJuros']   += $oDesconto->juros;
      $aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nTotal']   += $oDesconto->total;*/
    	$aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nValorPagar'] += $oDesconto->valor_pagar;
    	$aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nValorPago']  += $oDesconto->valor_pago;
    	$aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nDesconto']   += $oDesconto->desconto;
    } else {
    	
    	$aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['sDescricao']  = $sDescricao;
    	$aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nValorPagar'] = $oDesconto->valor_pagar;
    	$aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nValorPago']  = $oDesconto->valor_pago;
    	$aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nDesconto']   = $oDesconto->desconto;
    	
      /*$aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['sDescricao'] = $sDescricao;
      $aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nVlrHist']   = $oDesconto->vlrhist;
      $aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nVlrCorr']   = $oDesconto->vlrcorr;
      $aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nMulta']     = $oDesconto->multa;
      $aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nJuros']     = $oDesconto->juros;
      $aResumos[$sDescrAgrupa][$oDesconto->$sCampo]['nTotal']     = $oDesconto->total;*/    
    }
  }	
}

if($oGet->seltipo == "s"){
  $sCabTipo = " SINTÉTICO";
}else{
  $sCabTipo = " ANALÍTICO";
}

$head2 = "RELATÓRIO DE DESCONTOS CONCEDIDOS";
$head3 = "PERÍODO DE PAGAMENTO: ".db_formatar($dtDataIni,"d")." À ".db_formatar($dtDataFim,"d");
$head4 = "TIPO : {$sCabTipo}";


$oPdf = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetTextColor(0,0,0);
$oPdf->SetFillColor(220);

$iFonte    = 7;
$iAlt      = 5 ;
$iPreeche  = 1;

$nTotalPago     = 0;
$nTotalPagar    = 0;
$nTotalDesconto = 0;

if( $oGet->seltipo == "s" ){

	$lPrimeiro = true;
	
  foreach ( $aSintetico as $iReceit => $aDadosReceit  ) {
  	
  	foreach ( $aDadosReceit as $iReceitOrc => $aDadosSintetico  ) {
    
	    if ( $lPrimeiro || $oPdf->gety() > $oPdf->h - 30 ) {
	      
	      $oPdf->AddPage('L');
	      $oPdf->SetFont('Arial','B',$iFonte);
	      $oPdf->Cell(35 ,$iAlt,'Receita Tesouraria'  ,1,0,'C',1);
	      $oPdf->Cell(35 ,$iAlt,'Receita Orçamento'   ,1,0,'C',1);
	      $oPdf->Cell(105,$iAlt,'Descrição Orçamento' ,1,0,'C',1);
	      $oPdf->Cell(35 ,$iAlt,'Valor a Pagar'       ,1,0,'C',1);
	      $oPdf->Cell(35 ,$iAlt,'Valor Pago'          ,1,0,'C',1);
	      $oPdf->Cell(35 ,$iAlt,'Desconto Concedido'  ,1,1,'C',1);
	
	      $lPrimeiro = false;
	    }
	
	    if ( $iPreeche == 0 ) {
	      $iPreeche = 1;
	    } else  {
	      $iPreeche = 0;
	    }
	    
	    $oPdf->SetFont('Arial','',$iFonte);   
	    $oPdf->Cell(35 ,$iAlt,$iReceit                                        ,0,0,'C',$iPreeche);
	    $oPdf->Cell(35 ,$iAlt,$iReceitOrc                                     ,0,0,'C',$iPreeche);
	    $oPdf->Cell(105,$iAlt,$aDadosSintetico['sDescricao']                  ,0,0,'L',$iPreeche);
	    $oPdf->Cell(35 ,$iAlt,db_formatar($aDadosSintetico['nValorPagar'],"f"),0,0,'R',$iPreeche);
	    $oPdf->Cell(35 ,$iAlt,db_formatar($aDadosSintetico['nValorPago'] ,"f"),0,0,'R',$iPreeche);
	    $oPdf->Cell(35 ,$iAlt,db_formatar($aDadosSintetico['nDesconto']  ,"f"),0,1,'R',$iPreeche);   
	    
	    $nTotalPagar    += $aDadosSintetico['nValorPagar'];
	    $nTotalPago     += $aDadosSintetico['nValorPago'];
	    $nTotalDesconto += $aDadosSintetico['nDesconto'];
	    
  	}
  }
  
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(175,$iAlt,'TOTAIS : '                      ,'T',0,'R',0);
  $oPdf->Cell(35 ,$iAlt,db_formatar($nTotalPagar   ,"f") ,'T',0,'R',0);
  $oPdf->Cell(35 ,$iAlt,db_formatar($nTotalPago    ,"f") ,'T',0,'R',0);
  $oPdf->Cell(35 ,$iAlt,db_formatar($nTotalDesconto,"f") ,'T',1,'R',0);	
	

} else {

	foreach ( $aAnalitico as $iInd => $oDadosAnalitico ) {
		
  
		if ( $iInd == 0 || $oPdf->gety() > $oPdf->h - 30 ) {
			
			$oPdf->AddPage('L');
			$oPdf->SetFont('Arial','B',$iFonte);
			$oPdf->Cell(20,$iAlt,'Numpre'                         ,1,0,'C',1);
			$oPdf->Cell(80,$iAlt,'Contribuinte'                   ,1,0,'C',1);
			$oPdf->Cell(30,$iAlt,'Origem'                         ,1,0,'C',1);
			$oPdf->Cell(15,$iAlt,'Rec Tes'                        ,1,0,'C',1);
			$oPdf->Cell(15,$iAlt,'Rec Orc'                        ,1,0,'C',1);
			$oPdf->Cell(60,$iAlt,'Descrição Receita ( Orçamento )',1,0,'C',1);
			$oPdf->Cell(20,$iAlt,'Valor a Pagar'                  ,1,0,'C',1);
			$oPdf->Cell(20,$iAlt,'Valor Pago'                     ,1,0,'C',1);
			$oPdf->Cell(20,$iAlt,'Desconto'                       ,1,1,'C',1);

		}

		if ( $iPreeche == 0 ) {
			$iPreeche = 1;
		} else  {
			$iPreeche = 0;
		}
		
  	$oPdf->SetFont('Arial','',$iFonte);		
    $oPdf->Cell(20,$iAlt,$oDadosAnalitico->numpre                      ,0,0,'C',$iPreeche);
    $oPdf->Cell(80,$iAlt,$oDadosAnalitico->contribuinte                ,0,0,'L',$iPreeche);
    $oPdf->Cell(30,$iAlt,$oDadosAnalitico->origem                      ,0,0,'C',$iPreeche);
    $oPdf->Cell(15,$iAlt,$oDadosAnalitico->receit                      ,0,0,'C',$iPreeche);
    $oPdf->Cell(15,$iAlt,$oDadosAnalitico->receitorc                   ,0,0,'C',$iPreeche);
    $oPdf->Cell(60,$iAlt,$oDadosAnalitico->descrreceit                 ,0,0,'L',$iPreeche);
    $oPdf->Cell(20,$iAlt,db_formatar($oDadosAnalitico->valor_pagar,"f"),0,0,'R',$iPreeche);
    $oPdf->Cell(20,$iAlt,db_formatar($oDadosAnalitico->valor_pago ,"f"),0,0,'R',$iPreeche);
    $oPdf->Cell(20,$iAlt,db_formatar($oDadosAnalitico->desconto   ,"f"),0,1,'R',$iPreeche);		
		
    $nTotalPagar    += $oDadosAnalitico->valor_pagar;
    $nTotalPago     += $oDadosAnalitico->valor_pago;
    $nTotalDesconto += $oDadosAnalitico->desconto;
    
	}
	
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(220,$iAlt,'TOTAIS : '                      ,'T',0,'R',0);
  $oPdf->Cell(20 ,$iAlt,db_formatar($nTotalPagar   ,"f") ,'T',0,'R',0);
  $oPdf->Cell(20 ,$iAlt,db_formatar($nTotalPago    ,"f") ,'T',0,'R',0);
  $oPdf->Cell(20 ,$iAlt,db_formatar($nTotalDesconto,"f") ,'T',1,'R',0);	
	
}

$oPdf->AddPage('L');

/*foreach ( $aAgrupador as $sTipoAgrupa => $sCampo ) {
  
  $nTotalHistResumo  = 0;
  $nTotalCorrResumo  = 0;
  $nTotalMultaResumo = 0;
  $nTotalJurosResumo = 0;
  $nTotalResumo      = 0;  
  
  if ( $sTipoAgrupa == "receita" ) {
    $sTituloAgrupa = "Receita";
  } else {
    $sTituloAgrupa = "Tipo de Débito";
  }  
  
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(190,$iAlt,"Resumo por {$sTituloAgrupa}",1,1,'L',1);
  $oPdf->Cell(10,$iAlt,'Código'                      ,1,0,'C',1);
  $oPdf->Cell(80,$iAlt,'Descrição'                   ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Histórico'               ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Corrigido'               ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Multa'                   ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Juros'                   ,1,0,'C',1); 
  $oPdf->Cell(20,$iAlt,'Total'                       ,1,1,'C',1);  
  
  foreach ( $aResumos[$sTipoAgrupa] as $iCodResumo => $aValoresResumo ) {

    $oPdf->SetFont('Arial','',$iFonte);
    $oPdf->Cell(10,$iAlt,$iCodResumo                                 ,1,0,'C',0);
    $oPdf->Cell(80,$iAlt,$aValoresResumo['sDescricao']               ,1,0,'L',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresResumo['nVlrHist'],'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresResumo['nVlrCorr'],'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresResumo['nMulta']  ,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresResumo['nJuros']  ,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresResumo['nTotal']  ,'f'),1,1,'R',0);    
  
    $nTotalHistResumo  += $aValoresResumo['nVlrHist'];
    $nTotalCorrResumo  += $aValoresResumo['nVlrCorr'];
    $nTotalMultaResumo += $aValoresResumo['nMulta'];
    $nTotalJurosResumo += $aValoresResumo['nJuros'];
    $nTotalResumo      += $aValoresResumo['nTotal'];    
  
  }
  
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(10,$iAlt,'Total:'                           ,1,0,'R',0);
  $oPdf->Cell(80,$iAlt,''                                 ,1,0,'L',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalHistResumo ,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalCorrResumo ,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalMultaResumo,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalJurosResumo,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalResumo     ,'f'),1,1,'R',0);
    
  $oPdf->Ln(6);    
  
} */

foreach ( $aAgrupador as $sTipoAgrupa => $sCampo ) {

	$nTotalValorPagar = 0;
	$nTotalValorPago  = 0;
	$nTotalDesconto   = 0;

	if ( $sTipoAgrupa == "receita" ) {
		$sTituloAgrupa = "Receita";
	} else {
		$sTituloAgrupa = "Tipo de Débito";
	}

	$oPdf->SetFont('Arial','B',$iFonte);
	$oPdf->Cell(190, $iAlt, "Resumo por {$sTituloAgrupa}", 1, 1, 'L', 1);
	$oPdf->Cell(10,  $iAlt, 'Código'                     , 1, 0, 'C', 1);
	$oPdf->Cell(80,  $iAlt, 'Descrição'                  , 1, 0, 'C', 1);
	$oPdf->Cell(33,  $iAlt, 'Valor a Pagar'              , 1, 0, 'C', 1);
	$oPdf->Cell(33,  $iAlt, 'Valor Pago'                 , 1, 0, 'C', 1);
	$oPdf->Cell(34,  $iAlt, 'Valor Desconto'             , 1, 1, 'C', 1);

	foreach ( $aResumos[$sTipoAgrupa] as $iCodResumo => $aValoresResumo ) {

		$oPdf->Cell(10, $iAlt, $iCodResumo                   , 1, 0, 'C', 0);
		$oPdf->Cell(80, $iAlt, 						 $aValoresResumo['sDescricao']       , 1, 0, 'C', 0);
		$oPdf->Cell(33, $iAlt, db_formatar($aValoresResumo['nValorPagar'], 'f'), 1, 0, 'C', 0);
		$oPdf->Cell(33, $iAlt, db_formatar($aValoresResumo['nValorPago'] , 'f'), 1, 0, 'C', 0);
		$oPdf->Cell(34, $iAlt, db_formatar($aValoresResumo['nDesconto']  , 'f'), 1, 1, 'C', 0);
		
		$nTotalValorPagar += $aValoresResumo['nValorPagar'];
		$nTotalValorPago  += $aValoresResumo['nValorPago'] ;
		$nTotalDesconto   += $aValoresResumo['nDesconto']  ;
		
	}
	
	$oPdf->Cell(10, $iAlt, ''               , 1, 0, 'C', 0);
	$oPdf->Cell(80, $iAlt, 'Total'          , 1, 0, 'C', 0);
	$oPdf->Cell(33, $iAlt, db_formatar($nTotalValorPagar, 'f'), 1, 0, 'C', 0);
	$oPdf->Cell(33, $iAlt, db_formatar($nTotalValorPago , 'f'), 1, 0, 'C', 0);
	$oPdf->Cell(34, $iAlt, db_formatar($nTotalDesconto  , 'f'), 1, 1, 'C', 0);
	
	$oPdf->Ln(6);
	
}

$oPdf->Output();

?>