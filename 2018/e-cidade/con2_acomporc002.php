<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_libcontabilidade.php"));
include(modification("libs/db_liborcamento.php"));

$oGet = db_utils::postMemory($_GET);

$sAgrupador1 = substr($oGet->sAgrupador1,0,1);
$sAgrupador2 = substr($oGet->sAgrupador2,0,1);

if ($sAgrupador1 > $sAgrupador2) {
	$iNivel = $sAgrupador1;
} else {
	$iNivel = $sAgrupador2;
}


switch ($sAgrupador1) {
	case "1":
		$sNivelAgrupa1 = "o58_orgao";
		$sDescrNivel1	 = "o40_descr";
		$sCabNivel1	   = "Orgão"; 
	break;
	case "2":
		$sNivelAgrupa1 = "o58_unidade";
		$sDescrNivel1  = "o41_descr";
		$sCabNivel1	   = "Unidade"; 
	break;
	case "3":
		$sNivelAgrupa1 = "o58_funcao";
		$sDescrNivel1	 = "o52_descr";
		$sCabNivel1	   = "Função"; 
	break;
	case "4":
		$sNivelAgrupa1 = "o58_subfuncao";
		$sDescrNivel1	 = "o53_descr";
		$sCabNivel1	   = "Subfunção"; 
	break;
	case "5":
		$sNivelAgrupa1 = "o58_programa";
		$sDescrNivel1	 = "o54_descr";
		$sCabNivel1	   = "Programa"; 
	break;
	case "6":
		$sNivelAgrupa1 = "o58_projativ";
		$sDescrNivel1	 = "o55_descr";
		$sCabNivel1	   = "Proj/Ativ"; 
	break;
	case "7":
		$sNivelAgrupa1 = "o58_elemento";
		$sDescrNivel1	 = "o56_descr";
		$sCabNivel1	   = "Elemento"; 
	break;
	case "8":
		$sNivelAgrupa1 = "o58_codigo";
		$sDescrNivel1	 = "o15_descr";
		$sCabNivel1	   = "Recurso"; 
	break;

}


switch ($sAgrupador2) {
	case "1":
		$sNivelAgrupa2 = "o58_orgao";
		$sDescrNivel2	 = "o40_descr";
		$sCabNivel2	   = "Orgão"; 
	break;
	case "2":
		$sNivelAgrupa2 = "o58_unidade";
		$sDescrNivel2	 = "o41_descr";
		$sCabNivel2	   = "Unidade"; 
	break;
	case "3":
		$sNivelAgrupa2 = "o58_funcao";
		$sDescrNivel2	 = "o52_descr";
		$sCabNivel2	   = "Função"; 
	break;
	case "4":
		$sNivelAgrupa2 = "o58_subfuncao";
		$sDescrNivel2	 = "o53_descr";
		$sCabNivel2	   = "Subfunção"; 
	break;
	case "5":
		$sNivelAgrupa2 = "o58_programa";
		$sDescrNivel2	 = "o54_descr";
		$sCabNivel2	   = "Programa"; 
	break;
	case "6":
		$sNivelAgrupa2 = "o58_projativ";
		$sDescrNivel2	 = "o55_descr";
		$sCabNivel2	   = "Proj/Ativ"; 
	break;
	case "7":
		$sNivelAgrupa2 = "o58_elemento";
		$sDescrNivel2	 = "o56_descr";
		$sCabNivel2	   = "Elemento"; 
	break;
	case "8":
		$sNivelAgrupa2 = "o58_codigo";
		$sDescrNivel2	 = "o15_descr";
		$sCabNivel2	   = "Recurso"; 
	break;

}

$aCodAgrupa1 = explode("-",str_replace("pai_","",$oGet->sOrgaos1)); 
$aCodAgrupa2 = explode("-",str_replace("pai_","",$oGet->sOrgaos2)); 


// Caso o Nível1 seja tipo Unidade a string "$oGet->sOrgaos1" retorna junto o orgão da unidade, então haverá mais um explode

if ($sAgrupador1 == "2") {
	foreach ($aCodAgrupa1 as $ChaveUnidade){	
		$aUnidade1[]	= explode("_",$ChaveUnidade);
	}
	$aCodAgrupa1 = $aUnidade1;
}


// Caso o Nível2 seja tipo Unidade a string "$oGet->sOrgaos2" retorna junto o orgão da unidade, então haverá mais um explode

if ($sAgrupador2 == "2") {
	foreach ($aCodAgrupa2 as $ChaveUnidade){	
		$aUnidade2[]	= explode("_",$ChaveUnidade);
	}
	$aCodAgrupa2 = $aUnidade2;
}


$iInstit = str_replace("-",",",$oGet->instit);

$iAnoUsu = db_getsession('DB_anousu');
$dataini = db_getsession('DB_anousu')."-01-01"; 

$iDia		 = substr($oGet->dataf,0,2);
$iMes		 = substr($oGet->dataf,3,2);
$iAno		 = substr($oGet->dataf,6,4);


// Caso seja o último dia do mês a variável "$PrevPerVal" será multiplicada pelo mês da data selecionada, caso contrário multiplicará pelo mês anterior

$lUltimoDia = verifica_ultimo_dia_mes($oGet->dataf);

if ($lUltimoDia) {
	$iMesPrev = $iMes;
}else{
	$iMesPrev = ($iMes-1);
}

$datafin = "{$iAno}-{$iMes}-{$iDia}";
$sWhere  = " w.o58_instit in ({$iInstit}) ";

$rsDotacaoSaldo = db_dotacaosaldo($iNivel,1,4,true,$sWhere,$iAnoUsu,$dataini,$datafin,8,0,false); 
//db_criatabela($rsDotacaoSaldo);exit;

$aGrupo 	 = array();
$aTotGrupo = array();


for ($i = 0; $i < pg_num_rows($rsDotacaoSaldo); $i++ ) {
 
	$oDotacaoSaldo = db_utils::fieldsMemory($rsDotacaoSaldo,$i);
	
	foreach ( $aCodAgrupa1 as $iCodAgrupa1 ) {

		// Caso o Nível1 seja tipo Unidade será testado antes se o Orgão correspondente 
		
		if ( $sAgrupador1 == "2") {
			$lContinua = false;
			if ($oDotacaoSaldo->o58_orgao == $iCodAgrupa1[0]){
				$iCodAgrupa1 = $iCodAgrupa1[1];
				$lContinua 	 = true;
			}	
			if (!$lContinua){
				continue;
			}		
		}	

		
		if ( $oDotacaoSaldo->$sNivelAgrupa1 ==  $iCodAgrupa1 )   {
			
			foreach ( $aCodAgrupa2 as $iCodAgrupa2 ) {
				
				// Caso o Nível2 seja tipo Unidade será testado antes se o Orgão correspondente 
				
				if ( $sAgrupador2 == "2") {
					$lContinua = false;
					if ($oDotacaoSaldo->o58_orgao == $iCodAgrupa2[0]){
						$iCodAgrupa2 = $iCodAgrupa2[1];
						$lContinua 	 = true;
					}	
          if (!$lContinua){
						continue;
					}		
				}
				
				if ( $oDotacaoSaldo->$sNivelAgrupa2 ==  $iCodAgrupa2 ) {
					
					switch ($oGet->iTipoDespesa) {
						case "1":
							$RealPeriodoVal = $oDotacaoSaldo->empenhado_acumulado - $oDotacaoSaldo->anulado_acumulado;
						break;
						case "2":
							$RealPeriodoVal = $oDotacaoSaldo->liquidado_acumulado;
						break;
						case "3":
							$RealPeriodoVal = $oDotacaoSaldo->pago_acumulado;
						break;
					}
					
					$PrevAtuVal = $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;				

					$PrevPerVal = ( $PrevAtuVal/12 ) * $iMesPrev;
          
					

					if (!isset($aGrupo[$iCodAgrupa1][$oDotacaoSaldo->$sDescrNivel1][$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2])){
						$aGrupo[$iCodAgrupa1][$oDotacaoSaldo->$sDescrNivel1][$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['prevAtualizada'] = $PrevAtuVal;
						$aGrupo[$iCodAgrupa1][$oDotacaoSaldo->$sDescrNivel1][$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['realPeriodo']		= $RealPeriodoVal;
						$aGrupo[$iCodAgrupa1][$oDotacaoSaldo->$sDescrNivel1][$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['prevPeriodo']		= $PrevPerVal;
						$aGrupo[$iCodAgrupa1][$oDotacaoSaldo->$sDescrNivel1][$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['desvio']					= $PrevPerVal - $RealPeriodoVal ;
					} else {
						$aGrupo[$iCodAgrupa1][$oDotacaoSaldo->$sDescrNivel1][$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['prevAtualizada'] += $PrevAtuVal;
						$aGrupo[$iCodAgrupa1][$oDotacaoSaldo->$sDescrNivel1][$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['realPeriodo']		+= $RealPeriodoVal;
						$aGrupo[$iCodAgrupa1][$oDotacaoSaldo->$sDescrNivel1][$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['prevPeriodo']		+= $PrevPerVal;
						$aGrupo[$iCodAgrupa1][$oDotacaoSaldo->$sDescrNivel1][$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['desvio']					+= $PrevPerVal - $RealPeriodoVal ;

					}
			  	
					if (!isset($aTotGrupo[$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2])){
						$aTotGrupo[$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['prevAtualizada'] = $PrevAtuVal;
						$aTotGrupo[$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['realPeriodo']		= $RealPeriodoVal;
						$aTotGrupo[$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['prevPeriodo']		= $PrevPerVal;
						$aTotGrupo[$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['desvio']					= $PrevPerVal - $RealPeriodoVal ;
					} else {
						$aTotGrupo[$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['prevAtualizada'] += $PrevAtuVal;
						$aTotGrupo[$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['realPeriodo']		+= $RealPeriodoVal;
						$aTotGrupo[$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['prevPeriodo']		+= $PrevPerVal;
						$aTotGrupo[$iCodAgrupa2][$oDotacaoSaldo->$sDescrNivel2]['desvio']					+= $PrevPerVal - $RealPeriodoVal ;

					}
				}
			}
		}
	}
}


if ($oGet->iTipoDespesa == "1") {
	$sCabDespesa = "Empenhada";
} else if ($oGet->iTipoDespesa == "2") {
	$sCabDespesa = "Liquidada";
} else {
	$sCabDespesa = "Paga";
}

$head2 = "ACOMPANHAMENTO ORÇAMENTÁRIO";
$head4 = "Posição até: {$oGet->dataf}";
$head6 = "Detalhamento da Despesa {$sCabDespesa} por:"; 
$head7 = "Nível 1 - ".$sCabNivel1;
$head8 = "Nível 2 - ".$sCabNivel2;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->addpage();
$iAlt 	= 4;
$iFonte = 6;

ksort($aGrupo);
ksort($aTotGrupo);

foreach ($aGrupo as $CodGrupo1 => $aChaveDescr1){
	foreach ($aChaveDescr1 as $DescrGrupo1 => $aChaveCod2){
		
		$TotPrevAtualizada = 0;
		$TotRealPeriodo		 = 0;
		$TotPrevPeriodo		 = 0;
		$TotDesvio         = 0;
		
		$pdf->setfont('arial','b',$iFonte);
		
	  $pdf->ln(10);
		$pdf->cell(0, $iAlt,"AGRUPAMENTO DE NÍVEL 1 :  {$CodGrupo1} - {$DescrGrupo1}",0,1,"L",0);
	  $pdf->ln(2);
	
		$pdf->cell(80,$iAlt,"Agrupamento de Nível 2"  ,"TB" ,0,"L",1);
		$pdf->cell(30,$iAlt,"Previsão Atualizada"     ,"LTB",0,"C",1);
		$pdf->cell(30,$iAlt,"Realizado Até o Período" ,"LTB",0,"C",1);
		$pdf->cell(30,$iAlt,"Previsão Até o Período"  ,"LTB",0,"C",1);
		$pdf->cell(25,$iAlt,"Desvio" 								  ,"LTB",1,"C",1);
		$pdf->setfont('arial','',$iFonte);
		
    ksort($aChaveCod2);
		
		foreach ($aChaveCod2 as $CodGrupo2 => $aChaveDescr2){
			foreach ($aChaveDescr2 as $DescrGrupo2 => $aChaveVal2){
				
				$pdf->cell(80,$iAlt,"{$CodGrupo2} - {$DescrGrupo2}" 							,"TB" ,0,"L",0);
				$pdf->cell(30,$iAlt,db_formatar($aChaveVal2['prevAtualizada'],"f"),"LTB",0,"R",0);
				$pdf->cell(30,$iAlt,db_formatar($aChaveVal2['realPeriodo'],"f")  	,"LTB",0,"R",0);
				$pdf->cell(30,$iAlt,db_formatar($aChaveVal2['prevPeriodo'],"f")  	,"LTB",0,"R",0);
				$pdf->cell(25,$iAlt,db_formatar($aChaveVal2['desvio'],"f") 				,"LTB",1,"R",0);
			
				$TotPrevAtualizada += $aChaveVal2['prevAtualizada'];			
				$TotRealPeriodo		 +=	$aChaveVal2['realPeriodo'];
				$TotPrevPeriodo		 +=	$aChaveVal2['prevPeriodo'];
				$TotDesvio         +=	$aChaveVal2['desvio'];
			
			}
		}
		
		$pdf->setfont('arial','b',$iFonte);
		$pdf->cell(80,$iAlt,"Totalizador do Agrupamento de Nível 1","TB" ,0,"L",1);
		$pdf->cell(30,$iAlt,db_formatar($TotPrevAtualizada,"f")		 ,"LTB",0,"R",1);
		$pdf->cell(30,$iAlt,db_formatar($TotRealPeriodo,"f")			 ,"LTB",0,"R",1);
		$pdf->cell(30,$iAlt,db_formatar($TotPrevPeriodo,"f")			 ,"LTB",0,"R",1);
		$pdf->cell(25,$iAlt,db_formatar($TotDesvio,"f")						 ,"LTB",1,"R",1);
	
	}
}


$TotPrevAtualizada = 0;
$TotRealPeriodo		 = 0;
$TotPrevPeriodo		 = 0;
$TotDesvio         = 0;

$pdf->ln(10);
$pdf->setfont('arial','b',$iFonte);
$pdf->cell(0, $iAlt,"TOTALIZADOR GERAL",0,1,"L",0);
$pdf->ln(2);

$pdf->cell(80,$iAlt,"Agrupamento de Nível 2"  ,"TB" ,0,"L",1);
$pdf->cell(30,$iAlt,"Previsão Atualizada"     ,"LTB",0,"C",1);
$pdf->cell(30,$iAlt,"Realizado Até o Período" ,"LTB",0,"C",1);
$pdf->cell(30,$iAlt,"Previsão Até o Período"  ,"LTB",0,"C",1);
$pdf->cell(25,$iAlt,"Desvio" 								  ,"LTB",1,"C",1);
$pdf->setfont('arial','',$iFonte);


foreach ($aTotGrupo as $CodTotGrupo => $aChaveTotDescr){
	foreach ($aChaveTotDescr as $TotDescrGrupo => $aChaveValTot){
		
		$pdf->cell(80,$iAlt,"{$CodTotGrupo} - {$TotDescrGrupo}"							,"TB" ,0,"L",0);
		$pdf->cell(30,$iAlt,db_formatar($aChaveValTot['prevAtualizada'],"f"),"LTB",0,"R",0);
		$pdf->cell(30,$iAlt,db_formatar($aChaveValTot['realPeriodo'],"f")  	,"LTB",0,"R",0);
		$pdf->cell(30,$iAlt,db_formatar($aChaveValTot['prevPeriodo'],"f")  	,"LTB",0,"R",0);
		$pdf->cell(25,$iAlt,db_formatar($aChaveValTot['desvio'],"f") 				,"LTB",1,"R",0);
		
		$TotPrevAtualizada += $aChaveValTot['prevAtualizada'];			
		$TotRealPeriodo		 +=	$aChaveValTot['realPeriodo'];
		$TotPrevPeriodo		 +=	$aChaveValTot['prevPeriodo'];
		$TotDesvio         +=	$aChaveValTot['desvio'];
	
	}
}

$pdf->setfont('arial','b',$iFonte);
$pdf->cell(80,$iAlt,"Totalizador Geral"									,"TB" ,0,"L",1);
$pdf->cell(30,$iAlt,db_formatar($TotPrevAtualizada,"f")	,"LTB",0,"R",1);
$pdf->cell(30,$iAlt,db_formatar($TotRealPeriodo,"f")		,"LTB",0,"R",1);
$pdf->cell(30,$iAlt,db_formatar($TotPrevPeriodo,"f")		,"LTB",0,"R",1);
$pdf->cell(25,$iAlt,db_formatar($TotDesvio,"f")					,"LTB",1,"R",1);

notasExplicativas($pdf,57,$iMes,190);

$pdf->Output();

?>
