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
//include(modification("fpdf151/fpdf.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_sql.php"));
require_once(modification("classes/db_ppadotacao_classe.php"));
require_once(modification("classes/db_ppaestimativa_classe.php"));
require_once(modification("model/ppaVersao.model.php"));

$oGet = db_utils::postMemory($_GET);
$iModelo = $oGet->iModelo;
$clppadotacao    = new cl_ppadotacao();
$clppaestimativa = new cl_ppaestimativa();
$oDaoPPALei = db_utils::getDao("ppalei");
$oPPAVersao      = new ppaVersao($oGet->ppaversao);
$sListaInstit    = str_replace("-", ",", $oGet->sListaInstit);
$sWhere  = "      o05_ppaversao = {$oGet->ppaversao}	 						"; 
$sWhere .= "  and o05_anoreferencia between {$oGet->anoini} and {$oGet->anofin} ";																	
$sWhereAcao = $sWhere;
$sWherePrograma  = "";
$sWhereOrgao     = "";
if (isset($oGet->programa) && $oGet->programa != "") {
  $sWherePrograma = " and o08_programa in ({$oGet->programa})"; 
}

if (isset($oGet->orgao) && $oGet->orgao != "") {
  $sWhereOrgao = " and o08_orgao in({$oGet->orgao}) ";  
}

$sSqlEstimativa  = " select distinct o08_orgao, o08_programa, o40_descr,		  "; // Programa
$sSqlEstimativa .= " 		o54_descr 			   "; // Descrição Programa
$sSqlEstimativa .= "  from ppadotacao 																				   	    	         ";
$sSqlEstimativa .= " 	   inner join ppaestimativadespesa 	  on o08_sequencial = o07_coddot			   		 	 ";
$sSqlEstimativa .= "	   inner join ppaestimativa        	  on o07_ppaestimativa = o05_sequencial		    	 	 ";
$sSqlEstimativa .= "       inner join orcprograma   	  	  on orcprograma.o54_anousu = ".db_getsession('DB_anousu');
$sSqlEstimativa .= "      						   			 and orcprograma.o54_programa = ppadotacao.o08_programa  	 	 ";
$sSqlEstimativa .= "       left  join orcindicaprograma 	  on orcindicaprograma.o18_orcprograma = orcprograma.o54_programa 	 	 ";
$sSqlEstimativa .= "      						   		 	 and orcindicaprograma.o18_anousu = ".db_getsession('DB_anousu');
$sSqlEstimativa .= "       inner join orcorgao on o40_orgao  = o08_orgao ";
$sSqlEstimativa .= "                          and o40_anousu = o08_ano   "; 
$sSqlEstimativa .= " where $sWhere";
$sSqlEstimativa .= "   and o08_instit in({$sListaInstit}) {$sWherePrograma} {$sWhereOrgao}";
$sSqlEstimativa .= " order by o08_orgao";  

$rsEstimativa 	   = pg_query($sSqlEstimativa); 
$iLinhasEstimativa = pg_num_rows($rsEstimativa);
$aEstimativa	   = array();	 

for ( $iInd=0; $iInd < $iLinhasEstimativa; $iInd++ ) {

  $oDadosEstimativa = db_utils::fieldsMemory($rsEstimativa,$iInd);	

  $oDados = new stdClass();
  
  $oDados->iPrograma     	 = $oDadosEstimativa->o08_programa;
  $oDados->sPrograma     	 = $oDadosEstimativa->o54_descr;
  $oDados->iOrgao    	     = $oDadosEstimativa->o08_orgao;
  $oDados->sOrgao					 = $oDadosEstimativa->o40_descr;
  
  $sWhereAcao .= " and o08_orgao = $oDados->iOrgao ";
  
  $sSqlAcoes  = "   select distinct o08_projativ, 																		 ";
  $sSqlAcoes .= "          o55_descr, 																				     ";
  $sSqlAcoes .= "          o05_anoreferencia,																		     ";
  $sSqlAcoes .= "          ( select sum(o28_valor) 
  							   from orcprojativprogramfisica 
  							  where o28_orcprojativ = ppadotacao.o08_projativ  
  							    and o28_anoref		= o05_anoreferencia ) as o28_valor, 																				     ";
  $sSqlAcoes .= "          o22_descrprod, 																			     ";
  $sSqlAcoes .= "          o55_descrunidade, 																		     ";
  $sSqlAcoes .= "          o55_valorunidade, 																	  	     ";
  $sSqlAcoes .= "          o20_descricao,																  	    		 ";
  $sSqlAcoes .= "          case when o55_tipo = 1 then 'Projeto'														 ";
  $sSqlAcoes .= "               when o55_tipo = 2 then 'Atividade'														 ";
  $sSqlAcoes .= "               when o55_tipo = 3 then 'Operações Especiais' end as tipo,								     ";
  $sSqlAcoes .= "          round(sum(o05_valor),0) as valor				 ";
  $sSqlAcoes .= "     from ppadotacao 												    	       					     		   ";
  $sSqlAcoes .= " 	       inner join ppaestimativadespesa 	       on o08_sequencial   		    = o07_coddot	   		           ";
  $sSqlAcoes .= "	       inner join ppaestimativa        	  	   on o07_ppaestimativa 	    = o05_sequencial 		     	   ";
  $sSqlAcoes .= "          inner join orcprojativ   	   	   	   on orcprojativ.o55_projativ  = ppadotacao.o08_projativ   	   ";
  $sSqlAcoes .= "         								  	 	  and orcprojativ.o55_anousu    = o08_ano";
  $sSqlAcoes .= "		   left join orcproduto          		   on orcproduto.o22_codproduto = orcprojativ.o55_orcproduto 	   ";  
  $sSqlAcoes .= "		   inner join orctiporec         	 	   on orctiporec.o15_codigo     = ppadotacao.o08_recurso	 	   ";
  $sSqlAcoes .= "		   left  join orcprojativunidaderesp       on o13_orcprojativ  			= o55_projativ ";
  $sSqlAcoes .= "		                                          and o13_anousu     			= o55_anousu ";
  $sSqlAcoes .= "		   left join unidaderesp                   on o13_unidaderesp 			= o20_sequencial ";
  $sSqlAcoes .= "    where $sWhere	 and o08_orgao = $oDados->iOrgao							 								 ";
  $sSqlAcoes .= "      and o08_programa  = {$oDados->iPrograma}    ";	
  $sSqlAcoes .= " and o08_instit in({$sListaInstit})";
  $sSqlAcoes .= " group by o08_projativ, 													 ";
  $sSqlAcoes .= " 		   o15_tipo,																 ";
  $sSqlAcoes .= " 		   o55_tipo,																 ";
  $sSqlAcoes .= "          o55_descr, 														 ";	
  $sSqlAcoes .= "          o22_descrprod, 												 ";
  $sSqlAcoes .= "          o20_descricao, 												 ";
  $sSqlAcoes .= "          o55_descrunidade,  										 ";
  $sSqlAcoes .= "          o55_valorunidade, 											 ";
  $sSqlAcoes .= "          o05_anoreferencia,											 ";
  $sSqlAcoes .= "          o28_valor		 													 ";
  //$sSqlAcoes .= " order by o08_orgao,                             ";
  $sSqlAcoes .= " order by o08_projativ,											     ";
  $sSqlAcoes .= "          o05_anoreferencia;											 ";

  $rsConsultaAcoes 	= pg_query($sSqlAcoes);  
  $iLinhasAcoes    	= pg_num_rows($rsConsultaAcoes);
  $aAcoes 			= array();
  
  if ( $iLinhasAcoes > 0 ) {

  	for ( $iIndAcao=0; $iIndAcao < $iLinhasAcoes; $iIndAcao++ ) {
  		
  	  $oDadosAcao = db_utils::fieldsMemory($rsConsultaAcoes,$iIndAcao);

  	  $oAcao = new stdClass();
  	  $aAcoes[$oDadosAcao->o08_projativ]['iAcao']        = str_pad($oDadosAcao->o08_projativ, 4, '0', STR_PAD_LEFT);
  	  $aAcoes[$oDadosAcao->o08_projativ]['sDescricao']   = $oDadosAcao->o55_descr;
  	  $aAcoes[$oDadosAcao->o08_projativ]['sProduto']     = $oDadosAcao->o22_descrprod;
  	  $aAcoes[$oDadosAcao->o08_projativ]['sUnidade']     = $oDadosAcao->o20_descricao;
  		if ($iModelo == 2) {
  	    
  	    if ($oDadosAcao->o05_anoreferencia != $oGet->iAno) {
  	      
  	      $oDadosAcao->vinculado = "";
  	      $oDadosAcao->valor     = "";
  	      $oDadosAcao->o28_valor = "";
  	      
  	    }
  	  }
  	  $aAcoes[$oDadosAcao->o08_projativ]['sUnidadeMed']  = $oDadosAcao->o55_descrunidade;
  	  $aAcoes[$oDadosAcao->o08_projativ]['sTipo']     = $oDadosAcao->tipo;
  	  $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nQuantFisica'] = $oDadosAcao->o28_valor;
  	  $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nValor']       = $oDadosAcao->valor;

  	}

  }
  
  
  $oDados->iLinhasAcoes = $iLinhasAcoes;
  $oDados->aAcoes 		= $aAcoes;   
  $aEstimativa[] = $oDados;
  
  
}

$mostra = "";
$Ativs  = array();
 /*********
 *Faz a verificação das atividades que poderao ser mostradas
 * - Deverá  ter valor
 * - Deverão ter ações
 **********/
foreach ( $aEstimativa as $iInd => $oEstimativa ) { 

  foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ) { 

    foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
      if ($aDadosExerc['nQuantFisica']+$aDadosExerc['nValor'] > 0 && !in_array($iProjAtiv,$Ativs)) {
        array_push($Ativs,$iProjAtiv);
      }
    }
  }
}
 
//echo "<pre>";
//var_dump($aEstimativa);
//echo "</pre>";
//exit;

$sSqlPPALei  = $oDaoPPALei->sql_query($oGet->ppalei);
$rsPPALei    = $oDaoPPALei->sql_record($sSqlPPALei);
$oLeiPPA     = db_utils::fieldsMemory($rsPPALei, 0);

//$head2  = "ANEXO DE OBJETIVOS , DIRETRIZES E METAS";
//$head3  = "PPA - {$oGet->anoini} - {$oGet->anofin}";
$head4  = "Lei {$oLeiPPA->o01_numerolei} - {$oLeiPPA->o01_descricao}";
if ( $oGet->selforma == "s" ) {
	$head4 = "Forma de Emissão: Sintético";
}elseif ($oGet->selforma == "a"){
	$head4 = "Forma de Emissão: Analítico";
}
if ($iModelo == 1) {
  
  $head2  = "ANEXO DE OBJETIVOS , DIRETRIZES E METAS";
  $head3  = "PPA - {$oGet->anoini} - {$oGet->anofin}";

  //Modificação T25780
//  $head4  = "Versão: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
  //
  
} else {
  $head2  = "LEI DE DIRETRIZES ORÇAMENTÁRIAS - EXERCÍCIO DE $oGet->iAno";
}

$head5 = "Perspectiva: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false,1);
//$pdf->AddPage("L");
$pdf->setfillcolor(244);

if ( $oGet->imprimerodape == "n" ) {
  $pdf->imprime_rodape = false;
}

$alt = "4";
/*
 * Este if teste se o relatório vai ser impresso na forma sintética
 * como estava padrão no sistema
 */
$iOrgaoAtual = $aEstimativa[0]->iOrgao;

if ( $oGet->selforma == "s" ) {
	//$head5 = "Orgao: $oDados->sOrgao";
	$pdf->AddPage("L");
	$pdf->setfont('arial','B', 8);
  $pdf->cell(18,$alt, "Orgão:",0,0,"L");
  $pdf->cell(10,$alt, $aEstimativa[0]->iOrgao, 0, 0, "R"); 
  $pdf->cell(100,$alt, $aEstimativa[0]->sOrgao, 0, 1, "L"); 
	
  foreach ( $aEstimativa as $iInd => $oEstimativa ) {
  	
    /********
 *Faz a verificação dos registros que poderão ser mostrados	
 * - Deverá  ter valor
 * - Deverão ter ações 
 *********/
 /*
 foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ) { 
   
   foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
     //$mostra += $aDadosExerc['nQuantFisica']+$aDadosExerc['nValor'];
     $mostra += $aDadosExerc['nValor'];
   }
   
  }
  
  if($mostra == "") {
  	continue;
  } else {
  	$mostra == "";
  }
  */
  	   	
  	
		if ($iOrgaoAtual != $oEstimativa->iOrgao){
	  	$pdf->AddPage("L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(18,$alt, "Orgão:",0,0,"L");
	  	$pdf->cell(10,$alt, $oEstimativa->iOrgao, 0, 0, "R"); 
	  	$pdf->cell(100,$alt, $oEstimativa->sOrgao, 0, 1, "L"); 
	  	$iOrgaoAtual = $oEstimativa->iOrgao;
		} else {	
			validaNovaPagina($pdf, 40); 
		}
	  $pdf->setfont('arial','B', 8);
	  $pdf->cell(18,$alt, "Programa:",0,0,"L");
	  $pdf->cell(10,$alt, str_pad($oEstimativa->iPrograma, 4, '0', STR_PAD_LEFT), 0, 0, "R"); 
	  $pdf->cell(100,$alt, $oEstimativa->sPrograma, 0, 1, "L"); 
	  $iPosYDepois = $pdf->GetY();
	  $iPosXIndicador = 162;
	  $pdf->ln();
	  if ( $oEstimativa->iLinhasAcoes > 0 ) {
	  	
	  	foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ){
	  		
	  		$nTotalGeral     = 0;	
	  	  foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ){
	  	    $nTotalRecurso    = $aDadosExerc['nValor'];
	   	    $nTotalGeral	 += $nTotalRecurso;
	  	  }
  	    
	  	  if ($nTotalGeral == 0) {
	  	  	continue;
	  	  }
	     
	  	  validaNovaPagina($pdf, 35);
	  	  $pdf->setfont('arial','B', 8);
	  	  $pdf->Cell(62,$alt,"Ação"	  							,"TBR",0,"C",1);
	      $pdf->Cell(26,$alt,"Unidade"		 					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Classificação"					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Produto"  					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Unidade Medida"  					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Ano"    					,"TRBL" ,0,"C",1);
	      $pdf->Cell(40,$alt,"Metas"    					,"TRL" ,0,"C",1);
        if ($imprimevalores == 1) {
          $pdf->Cell(40,$alt,"Valor R$"    					,"TL" ,0,"C",1);
        }
        $pdf->ln();
	      $iPosYAntes  = $pdf->GetY();
	    	
	      $pdf->setfont('arial','', 8);
	      $iAltDescr = ($alt);
	  	
	  	  $pdf->setfont('arial','', 8);
	  	  //$pdf->multicell(62,$iAltDescr,str_pad(substr($aDadosAcoes["iAcao"]."-".$aDadosAcoes['sDescricao'],0,90),90," ",STR_PAD_RIGHT),"TR","L",0);
	  	  $pdf->multicell(62,$iAltDescr,str_pad(substr($aDadosAcoes["iAcao"]."-".$aDadosAcoes['sDescricao'],0,90),4," ",STR_PAD_LEFT),"TR","L",0);
	  	  $pdf->setfont('arial','', 8);
	  	  $pdf->SetXY(72,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sUnidade'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	  	  $pdf->SetXY(98,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sTipo'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	  	  $pdf->SetXY(124,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sProduto'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	  	  $pdf->SetXY(150,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sUnidadeMed'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	   	  $nTotalLivre     = 0;
	  	  $nTotalVinculado = 0;
	  	  $nTotalMetas     = 0;
	  	  $nTotalGeral     = 0;	
	  	  $iLinhasExerc	 = 0;
	  	  $pdf->SetY($iPosYAntes);
	      foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ){
	  	  	
	  	    $nTotalRecurso    = $aDadosExerc['nValor'];
	   	    $nTotalGeral	 += $nTotalRecurso;
	   	    $nTotalMetas     += $aDadosExerc['nQuantFisica'];
	     		$tipo = "f";
	        if ($iModelo == 2) {
 	    
			 	    if ($iExercicio != $oGet->iAno) {
			 	      $iExercicio = "";
			 	      $tipo = "";
			 	    }
			 	  }
	  	    $pdf->SetX(176);
	  	    $pdf->Cell(26,$alt,$iExercicio  			     			,"BR" ,0,"C",0);
	  	    $pdf->Cell(40,$alt,$aDadosExerc['nQuantFisica']	,"TBR",0,"R",0);
          if ($imprimevalores == 1) {
	  	      $pdf->Cell(40,$alt,db_formatar($aDadosExerc['nValor'],$tipo)	,"TBL",0,"R",0);
          }
          $pdf->ln();
	  	    $iLinhasExerc++;
	  
	      }
	  	  $pdf->SetY($iPosYAntes);
	  	  for ($iSeq=0; $iSeq < 4; $iSeq++ ) {
	
	  	    $sBorda = "R";
	  	    if ($iSeq == 3) {
	  	      $sBorda = "RB";
	  	    }
	  	    $pdf->Cell(62,$alt,"","{$sBorda}" ,0);
	  	    $pdf->Cell(26,$alt,"",$sBorda ,0);
	  	    $pdf->Cell(26,$alt,"",$sBorda ,0);
	  	    $pdf->Cell(26,$alt,"",$sBorda ,0);
          if ($imprimevalores == 1) {
	          $pdf->Cell(26,$alt,"",$sBorda ,0);
          }
          $pdf->ln();
	      }	
	  	  $pdf->setfont('arial','B', 8);	
	  	  $pdf->Cell(192,$alt,"Total da ação para os exercícios" ,"TBR",0,"R",0);
	  	  $pdf->setfont('arial','' , 8);
	  	  $pdf->Cell(40 ,$alt, $nTotalMetas ,"TBR",0,"R",0);
        if ($imprimevalores == 1) {
	  	    $pdf->Cell(40 ,$alt,db_formatar($nTotalGeral,"f")	   ,"TBL",1,"R",0);  	
        }
	      $pdf->setfont('arial','B', 8);
	      $pdf->ln();
	  	}
	  }
	  $pdf->ln();
	}
/*
 * Este parte do if tem a parte nova onde imprimi o relatorio analitico 
 * detalhando o Programa e a Ação
 */	
} else if ( $oGet->selforma == "a" ) {
	$pdf->addpage("L");
  $pdf->setfont('arial','B', 8);
  $pdf->cell(18,$alt, "Orgão:",0,0,"L");
  $pdf->cell(10,$alt, $aEstimativa[0]->iOrgao, 0, 0, "R"); 
  $pdf->cell(100,$alt, $aEstimativa[0]->sOrgao, 0, 1, "L");
  $controle = true;
  
  foreach ( $aEstimativa as $iInd => $oEstimativa ) {
  	
   /********
 *Faz a verificação dos registros que poderão ser mostrados	
 * - Deverá  ter valor
 * - Deverão ter ações 
 *********/
 foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ) { 
   
   foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
     $mostra += $aDadosExerc['nQuantFisica']+$aDadosExerc['nValor'];
   }
   
  }
  
  if($mostra == "") {
  	continue;
  } else {
  	$mostra == "";
  }
  	
		if ($iOrgaoAtual != $oEstimativa->iOrgao){
	  	$pdf->AddPage("L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(18,$alt, "Orgão:",0,0,"L");
	  	$pdf->cell(10,$alt, $oEstimativa->iOrgao, 0, 0, "R"); 
	  	$pdf->cell(100,$alt, $oEstimativa->sOrgao, 0, 1, "L"); 
	  	$iOrgaoAtual = $oEstimativa->iOrgao;
		} else {
	  //validaNovaPagina(&$pdf, 40);
	  	if($controle){
	  		$controle = false;
	  	}else {
				$pdf->addpage("L");
	  	}
		} 
	  $pdf->setfont('arial','B', 8);
	  $pdf->cell(18,$alt, "Programa:",0,0,"L");
	  $pdf->cell(10,$alt, str_pad($oEstimativa->iPrograma, 4, '0', STR_PAD_LEFT), 0, 0, "R"); 
	  $pdf->cell(100,$alt, $oEstimativa->sPrograma, 0, 1, "L"); 
	  $iPosYDepois = $pdf->GetY();
	  $iPosXIndicador = 162;
	  
	  //Busca as informções detalhadas do programa e da ação
	  $sSqlPrograma  = "select case when o54_tipoprograma = 1 then 'Programas Finalísticos' ";
	  $sSqlPrograma .= "            when o54_tipoprograma = 2 then 'Programas de Apoio as Políticas Públicas e Áreas Especiais' ";
	  $sSqlPrograma .= "       end as tipo, ";
	  $sSqlPrograma .= "       o54_problema as problema,";
	  $sSqlPrograma .= "       o54_finali as finalidade,";
	  $sSqlPrograma .= "       o54_publicoalvo as alvo, ";
	  $sSqlPrograma .= "       o17_dataini as dataini, ";
	  $sSqlPrograma .= "       o17_datafin as datafin, ";
	  $sSqlPrograma .= "       o54_justificativa as justificativa,";
	  $sSqlPrograma .= "       o54_objsetorassociado as associado,";
	  $sSqlPrograma .= "       o54_estrategiaimp as estrategia    ";
	  $sSqlPrograma .= "  from orcprograma ";
	  $sSqlPrograma .= "  left join orcprogramahorizontetemp on  o54_programa = o17_programa ";
	  $sSqlPrograma .= "  and  o17_anousu = ".db_getsession('DB_anousu');
	  $sSqlPrograma .= "where o54_anousu 	 = ".db_getsession('DB_anousu');
	  $sSqlPrograma .= "  and o54_programa = $oEstimativa->iPrograma";
	  //die($sSqlPrograma);
	  $resSqlPrograma	= pg_query($sSqlPrograma);  
  	$iLinhaPrograma	= pg_num_rows($resSqlPrograma);
  	
  	if ( $iLinhaPrograma > 0 ){
  		$oDadosPrograma = db_utils::fieldsMemory($resSqlPrograma,0);
  	}else{
  		$oDadosPrograma = new	stdClass();
  		$oDadosPrograma->tipo						= "";
  		$oDadosPrograma->problema				= "";
  		$oDadosPrograma->finalidade			= "";
  		$oDadosPrograma->alvo						= "";
  		$oDadosPrograma->justificativa	= "";
  		$oDadosPrograma->associado			= "";
  		$oDadosPrograma->estrategia			= "";
  		$oDadosPrograma->dataini			= "";
  		$oDadosPrograma->datafin			= "";
  	}
  		
			//$pdf->ln();
			$pdf->setfont('arial','B', 8);
  		$pdf->cell(60,$alt, "Tipo de Programa:",0,0,"L");
  		$pdf->setfont('arial','', 8);
	  	$pdf->multicell(210,$alt, $oDadosPrograma->tipo, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "Horizontel Temporal:",0,0,"L");
  		$pdf->setfont('arial','', 8);
  		$pdf->cell(20,$alt, "Data Início:",0,0,"L");
  		$pdf->cell(20,$alt, db_formatar($oDadosPrograma->dataini,'d'),0,0,"L");
  		$pdf->cell(20,$alt, "Data Término:",0,0,"L");
  		$pdf->cell(20,$alt, db_formatar($oDadosPrograma->datafin,'d'),0,1,"L");
	  	$pdf->setfont('arial','B', 8);
			$pdf->cell(60,$alt, "Problema:",0,0,"L");
  		$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->problema),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->problema, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->problema, 0,"L");
	  	$pdf->setfont('arial','B', 8); 
	  	$pdf->cell(60,$alt, "Finalidade:",0,0,"L");
	  	$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->finalidade),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->finalidade, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->finalidade, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "Público Alvo:",0,0,"L");
	  	$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->alvo),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->alvo, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->alvo, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "Justificativa:",0,0,"L");
	  	$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->justificativa),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->justificativa, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->justificativa, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "Objetivo Setor Associado:",0,0,"L");
	  	$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->associado),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->associado, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->associado, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "Estratégia de Implementação do Programa:",0,0,"L");
	  	$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->estrategia),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->estrategia, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->estrategia, 0,"L");     
  	 	
	  	  
	  $pdf->ln();
	  if ( $oEstimativa->iLinhasAcoes > 0 ) {
	  	
	  	foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ){
	  			  		
	  		$sSqlAcao  = "select o55_finali as finalidade,    ";
	  		$sSqlAcao .= "       o55_especproduto as produto, ";
	  		$sSqlAcao .= "       case when o55_tipoacao = 1 then 'Orçamentária' ";
	  		$sSqlAcao .= "            when o55_tipoacao = 2 then 'Não-Orçamentária' ";
	  		$sSqlAcao .= "       end as tipo, ";
	  		$sSqlAcao .= "       case when o55_formaimplementacao = 1 then 'Direta' ";
	  		$sSqlAcao .= "            when o55_formaimplementacao = 2 then 'Descentralizada' ";
	  		$sSqlAcao .= "            when o55_formaimplementacao = 3 then 'Transferência Obrigatória' ";
	  		$sSqlAcao .= "            when o55_formaimplementacao = 4 then 'Transferência Voluntária' ";
	  		$sSqlAcao .= "            when o55_formaimplementacao = 5 then 'Transferência em Linha de Crédito'";
	  		$sSqlAcao .= "       end as forma, ";
	  		$sSqlAcao .= "       o55_detalhamentoimp as detalhamento, ";
	  		$sSqlAcao .= "       o55_origemacao as origem, ";
	  		$sSqlAcao .= "       o55_baselegal as base ";
	  		$sSqlAcao .= "  from orcprojativ ";
	  		$sSqlAcao .= " where o55_projativ = {$aDadosAcoes["iAcao"]}";
	  		$sSqlAcao .= "  and  o55_anousu   = ".db_getsession('DB_anousu');
	  		//$sSqlAcao .= "  and  o55_instit   = ".db_getsession('DB_instit');
	  		
	  		$resSqlAcao	= pg_query($sSqlAcao);  
  	    $iLinhaAcao	= pg_num_rows($resSqlAcao);
  	    $pdf->Ln();
  	    validaNovaPagina($pdf, 35);
  	    $iAltDescr = ($alt);
  	    $pdf->setfont('arial','B', 8);
  	    
  	    //verifica a soma se for zero não mostra
  	    
  	   	$nTotalGeral     = 0;	
	  	  foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ){
	  	    $nTotalRecurso    = $aDadosExerc['nValor'];
	   	    $nTotalGeral	 += $nTotalRecurso;
	  	  }
  	    
	  	  if ($nTotalGeral == 0) {
	  	  	continue;
	  	  }
				
  	    
  	    $pdf->cell(18,$alt, "Ação:",0,0,"L");
	  		$pdf->cell(10,$alt, $aDadosAcoes["iAcao"], 0, 0, "R"); 
	  		$pdf->cell(200,$alt, $aDadosAcoes['sDescricao'], 0, 1, "L"); 
  	    
  	    
		  	//$pdf->Cell(25,$alt,"Ação:"	  							,"",0,"L",0);
		  	//$pdf->multicell(200,$iAltDescr,str_pad(substr($aDadosAcoes["iAcao"]."-".$aDadosAcoes['sDescricao'],0,90),90," ",STR_PAD_RIGHT),"","L",0);
  	    
		  	if ( $iLinhaAcao > 0 ){
		  		$oDadosAcao1 = db_utils::fieldsMemory($resSqlAcao,0);
		  	}else{
		  		$oDadosAcao1 = new stdClass();
		  		$oDadosAcao1->finalidade	= "";	
		  		$oDadosAcao1->produto 	 	= "";
		  		$oDadosAcao1->tipo				= "";
		  		$oDadosAcao1->forma				= "";
		  		$oDadosAcao1->detalhamento= "";
		  		$oDadosAcao1->origem			= "";
		  		$oDadosAcao1->base				= "";
		  	}
					//$pdf->ln();
					$pdf->setfont('arial','B', 8);
		  		$pdf->cell(60,$alt, "Finalidade:",0,0,"L");
		  		$pdf->setfont('arial','', 8);
	  			$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->finalidade),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->finalidade, 0,"L");
          }
			  	//$pdf->multicell(210,$alt, $oDadosAcao1->finalidade, 0,"L");
			  	$pdf->setfont('arial','B', 8); 
			  	$pdf->cell(60,$alt, "Especificação do Produto:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
	  			$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->produto),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->produto, 0,"L");
          }
			  	//$pdf->multicell(210,$alt, $oDadosAcao1->produto, 0,"L");
			  	$pdf->setfont('arial','B', 8); 
			  	$pdf->cell(60,$alt, "Tipo de Ação:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
	  			$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->tipo),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->tipo, 0,"L");
          }
			  	//$pdf->multicell(210,$alt, $oDadosAcao1->tipo, 0,"L");
			  	$pdf->setfont('arial','B', 8);
			  	$pdf->cell(60,$alt, "Forma de Implementação:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
	  			$pdf->multicell(210,$alt, $oDadosAcao1->forma, 0,"L");
			  	$pdf->setfont('arial','B', 8);
			  	$pdf->cell(60,$alt, "Detalhamento da Implementação:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
			  	$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->detalhamento),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->detalhamento, 0,"L");
          }
			  	//$pdf->setfont('arial','', 8);
			  	//$pdf->multicell(210,$alt, $oDadosAcao1->detalhamento, 0,"L");
			  	$pdf->setfont('arial','B', 8);
			  	$pdf->cell(60,$alt, "Origem da Ação:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
	  			$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->origem),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->origem, 0,"L");
          }
			  	//$pdf->multicell(210,$alt, $oDadosAcao1->origem, 0,"L");
			  	$pdf->setfont('arial','B', 8);
			  	$pdf->cell(60,$alt, "Base Legal:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
	  			$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->base),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->base, 0,"L");
          }
			  	$pdf->multicell(210,$alt, $oDadosAcao1->base, 0,"L");     
	  		  
	     
	  		//$pdf->Ln();
	  	  validaNovaPagina($pdf, 35);
	  	  $pdf->setfont('arial','B', 8);
	  	  //$pdf->Cell(62,$alt,"Ação"	  							,"TBR",0,"C",1);
	      $pdf->Cell(88,$alt,"Unidade"		 					,"TR" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Classificação"					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Produto"  					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Unidade Medida"  					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Ano"    					,"TRBL" ,0,"C",1);
	      $pdf->Cell(40,$alt,"Metas"    					,"TRL" ,0,"C",1);
	      $pdf->Cell(40,$alt,"Valor R$"    					,"TL" ,1,"C",1);
	      $iPosYAntes  = $pdf->GetY();
	    	
	      $pdf->setfont('arial','', 8);
	      $iAltDescr = ($alt);
	      	  	
	  	  $pdf->setfont('arial','', 8);
	  	  //$pdf->multicell(62,$iAltDescr,str_pad(substr($aDadosAcoes["iAcao"]."-".$aDadosAcoes['sDescricao'],0,90),90," ",STR_PAD_RIGHT),"TR","L",0);
	  	  $pdf->setfont('arial','', 8);
	  	  $pdf->SetXY(10,$iPosYAntes);
	  	  $pdf->multicell(88,$iAltDescr,str_pad(substr($aDadosAcoes['sUnidade'],0,40),40," ",STR_PAD_RIGHT),"TR","L",0);
	  	  $pdf->SetXY(98,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sTipo'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	  	  $pdf->SetXY(124,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sProduto'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	  	  $pdf->SetXY(150,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sUnidadeMed'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	   	  $nTotalLivre     = 0;
	  	  $nTotalVinculado = 0;
	  	  $nTotalMetas     = 0;
	  	  $nTotalGeral     = 0;	
	  	  $iLinhasExerc	 = 0;
	  	  $pdf->SetY($iPosYAntes);
	      foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ){
	  	  	
	  	    $nTotalRecurso    = $aDadosExerc['nValor'];
	   	    $nTotalGeral	 += $nTotalRecurso;
	   	    $nTotalMetas     += $aDadosExerc['nQuantFisica'];
	   	    $tipo = "f";
	        if ($iModelo == 2) {
 	    
			 	    if ($iExercicio != $oGet->iAno) {
			 	      $iExercicio = "";
			 	      $tipo = "";
			 	    }
			 	  }
	  	    $pdf->SetX(176);
	  	    $pdf->Cell(26,$alt,$iExercicio  			     			,"BR" ,0,"C",0);
	  	    $pdf->Cell(40,$alt,$aDadosExerc['nQuantFisica']	,"TBR",0,"R",0);
	  	    
	  	    $pdf->Cell(40,$alt,db_formatar($aDadosExerc['nValor'],$tipo)	,"TBL",1,"R",0);
	  	    
	  	    $iLinhasExerc++;
	  
	      }
	  	  $pdf->SetY($iPosYAntes);
	  	  for ($iSeq=0; $iSeq < 4; $iSeq++ ) {
	
	  	    $sBorda = "R";
	  	    if ($iSeq == 3) {
	  	      $sBorda = "RB";
	  	    }
	  	    //$pdf->Cell(62,$alt,"","{$sBorda}" ,0);
	  	    $pdf->Cell(88,$alt,"",$sBorda ,0);
	  	    $pdf->Cell(26,$alt,"",$sBorda ,0);
	  	    $pdf->Cell(26,$alt,"",$sBorda ,0);
	        $pdf->Cell(26,$alt,"",$sBorda ,1);
	      }	
	  	  $pdf->setfont('arial','B', 8);	
	  	  $pdf->Cell(192,$alt,"Total da ação para os exercícios" ,"TBR",0,"R",0);
	  	  $pdf->setfont('arial','' , 8);
	  	  $pdf->Cell(40 ,$alt, $nTotalMetas ,"TBR",0,"R",0);
	  	  $pdf->Cell(40 ,$alt,db_formatar($nTotalGeral,"f")	   ,"TBL",1,"R",0);  	
	      $pdf->setfont('arial','B', 8);
	      //$pdf->addpage("L");
	      //$pdf->ln();
	  	}
	  }
	  $pdf->ln();
	}
	
}

$pdf->Output();

function validaNovaPagina($pdf, $iAltura){
	
 if ($pdf->getY() > $pdf->h - $iAltura){
	
    $alt = 4;
    $pdf->addpage("L");

  }	
	
}

?>
