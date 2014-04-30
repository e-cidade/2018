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

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_empresto_classe.php");
include("classes/db_orcparamseq_classe.php");

$orcparamrel   = new cl_orcparamrel;
$classinatura  = new cl_assinatura;
$clempresto    = new cl_empresto;
$clorcparamseq = new cl_orcparamseq;

$oGet = db_utils::postMemory($_POST);

$iUsuInstit  = db_getsession('DB_instit');
$rsUsuInstit = db_query(" select codigo from db_config where ( db21_tipoinstit in (5,6) or prefeitura is true ) and codigo = ".$iUsuInstit);
$iNumRowsUsuInstit = pg_num_rows($rsUsuInstit);

if($iNumRowsUsuInstit == 0){
	    db_redireciona('db_erros.php?fechar=true&db_erro=O usuário deve ser da instituição RPPS ou Prefeitura para visualizar o relatório');
}

$rsInstit = db_query(" select codigo,nomeinst from db_config where db21_tipoinstit in (5,6) ");

if (pg_num_rows($rsInstit) == 0){
	  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe Instituição RPPS.');
}else{
	  $oInstit  = db_utils::fieldsMemory($rsInstit,0);
}



$head2 =  $oInstit->nomeinst;
$head3 = "BALANÇO FINANCEIRO DO REGIME PRÓPRIO DE PREVIDÊNCIA SOCIAL";
if($oGet->mes == 1){
	$head4 = "JANEIRO DE ".db_getsession("DB_anousu");
}else{
	$head4 = "JANEIRO A ".strtoupper(db_mes($oGet->mes))." DE ".db_getsession("DB_anousu");
}
$anousu  = db_getsession("DB_anousu");
$dataini = db_getsession("DB_anousu").'-'.'01'.'-'.'01';
$datafin = db_getsession("DB_anousu").'-'.$oGet->mes.'-'.date('t',mktime(0,0,0,$oGet->mes,'01',db_getsession("DB_anousu")));


$aTransfRecebidas   = $orcparamrel->sql_parametro_instit("55","1","f",$oInstit->codigo,db_getsession("DB_anousu"));
$aIngressos					= $orcparamrel->sql_parametro_instit("55","2","f",$oInstit->codigo,db_getsession("DB_anousu"));
$aTranfConcedidas   = $orcparamrel->sql_parametro_instit("55","3","f",$oInstit->codigo,db_getsession("DB_anousu"));
$aDespendidos				= $orcparamrel->sql_parametro_instit("55","4","f",$oInstit->codigo,db_getsession("DB_anousu"));


$aOrcParametro = array_merge( $aTransfRecebidas, 
                              $aIngressos,				
                              $aTranfConcedidas, 
                              $aDespendidos );
$somador_receita = 0;
$somador_despesa = 0;

$db_filtro  = 'e60_instit = '.$oInstit->codigo;
$sele_work1 = '';
$sSqlPeriodo = $clempresto->sql_rp(db_getsession("DB_anousu"), $db_filtro, $dataini, $datafin, $sele_work1);
$rsDespesaRp = db_query($sSqlPeriodo);
//db_criatabela($rsDespesaRp);exit;

$db_filtro = ' o70_instit = '.$oInstit->codigo;
$rsReceita = db_receitasaldo(3,1,3,true,$db_filtro,$anousu,$dataini,$datafin);
//db_criatabela($rsReceita);exit;

$sele_work = ' w.o58_instit = '.$oInstit->codigo;
$rsDespesa = db_dotacaosaldo(8,2,4,true,$sele_work,$anousu,$dataini,$datafin);
//db_criatabela($rsDespesa);exit;

$where = " c61_instit = ".$oInstit->codigo;
$rsBalancete = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where,'','true','false','',$aOrcParametro);
//db_criatabela($rsBalancete);exit;

$iRecCorr			 		 = 0;
$iRecCap			 		 = 0;
$iRecCorrIntr  		 = 0;
$iRecCapIntr   		 = 0;
$iOrcTransfRec 		 = 0; 
$iExtraOrcTransRec = 0;
$iExtraOrcTransCon = 0;
$iExtraOrcIng			 = 0;
$iExtraOrcDisp		 = 0;
$iDispExeAnt 			 = 0;
$iDispExeSeg 			 = 0;
$iDespCap					 = 0;
$iDespCorr				 = 0;

for($i = 0; $i < pg_num_rows($rsReceita);$i++){
	
	$oReceitaSaldo = db_utils::fieldsMemory($rsReceita,$i);
 	
  if (substr($oReceitaSaldo->o57_fonte,0,3) == "410" && $oReceitaSaldo->o70_codigo == 0){
    $iRecCorr += $oReceitaSaldo->saldo_arrecadado_acumulado;
  }
  
  /*
   * Melhoria acrescentada
   * conta  913281000000  - essa conta ira diminuir o saldo porque e uma conta dedutora.
   * essa alteracao so deve valer para 2010 em diante 
   */
  if ($anousu >= 2010) {
    
    if (substr($oReceitaSaldo->o57_fonte, 0, 3) == "910") {
      $iRecCorr += $oReceitaSaldo->saldo_arrecadado_acumulado;
    }
  }
  
  if (substr($oReceitaSaldo->o57_fonte,0,3) == "420" && $oReceitaSaldo->o70_codigo == 0){
    $iRecCap  += $oReceitaSaldo->saldo_arrecadado_acumulado;
  }

  if ($anousu >= 2010) {
    
    if (substr($oReceitaSaldo->o57_fonte, 0, 3) == "920") {
      $iRecCap  += $oReceitaSaldo->saldo_arrecadado_acumulado;
    }
  }
  
  if (substr($oReceitaSaldo->o57_fonte,0,3) == "470" && $oReceitaSaldo->o70_codigo == 0){
    $iRecCorrIntr += $oReceitaSaldo->saldo_arrecadado_acumulado;
  }
  
  if ($anousu >= 2010) {
    
    if (substr($oReceitaSaldo->o57_fonte, 0, 3) == "970") {
      $iRecCorrIntr += $oReceitaSaldo->saldo_arrecadado_acumulado;
    }
  }
  
  
  if (substr($oReceitaSaldo->o57_fonte,0,3) == "480" && $oReceitaSaldo->o70_codigo == 0){
    $iRecCapIntr  += $oReceitaSaldo->saldo_arrecadado_acumulado;
  }
  
  if ($anousu >= 2010) {
    
    if (substr($oReceitaSaldo->o57_fonte, 0, 3) == "980") {
      $iRecCapIntr  += $oReceitaSaldo->saldo_arrecadado_acumulado;
    }
  }

}


for($i = 0; $i < pg_num_rows($rsBalancete); $i++){

	$oBalancete = db_utils::fieldsMemory($rsBalancete,$i);
 
  $aElementos = array($oBalancete->estrutural,$oInstit->codigo);
	
	if (substr($oBalancete->estrutural,0,3) == "612" && $oBalancete->c61_codcon != 0){
 		$iOrcTransfRec  += $oBalancete->saldo_final;
  }

	if(in_array($aElementos,$aTransfRecebidas)){
		$iExtraOrcTransRec += $oBalancete->saldo_final;
	}

	if(in_array($aElementos,$aIngressos)){
		$iExtraOrcIng      += $oBalancete->saldo_anterior_credito;
	}

	if(in_array($aElementos,$aTranfConcedidas)){
		$iExtraOrcTransCon += $oBalancete->saldo_final;
	}

	if(in_array($aElementos,$aDespendidos)){
		$iExtraOrcDisp     += $oBalancete->saldo_anterior_debito;
	}

	if((substr($oBalancete->estrutural,0,3) == "111" || substr($oBalancete->estrutural,0,3) == "115") && $oBalancete->c61_codcon != 0 ){ 
		if($oBalancete->sinal_anterior == "C"){
			$iDispExeAnt -= $oBalancete->saldo_anterior; 
	  }else if($oBalancete->sinal_anterior == "D"){
			$iDispExeAnt += $oBalancete->saldo_anterior;
		}
	}  

	if((substr($oBalancete->estrutural,0,3) == "111" || substr($oBalancete->estrutural,0,3) == "115") && $oBalancete->c61_codcon != 0 ){
		if($oBalancete->sinal_final == "C"){
			$iDispExeSeg -= $oBalancete->saldo_final;
	  }else if($oBalancete->sinal_final == "D"){
			$iDispExeSeg += $oBalancete->saldo_final;
		}
	}

}


for($i = 0; $i < pg_num_rows($rsDespesa); $i++){
	
	$oDespesa = db_utils::fieldsMemory($rsDespesa,$i);
	
	if(substr($oDespesa->o58_elemento,0,2)== "33" ){
		 $iDespCorr += $oDespesa->liquidado_acumulado;
	}
	if(substr($oDespesa->o58_elemento,0,2)== "34" ){
		 $iDespCap  += $oDespesa->liquidado_acumulado;
	}

	$iExtraOrcIng += $oDespesa->liquidado_acumulado - $oDespesa->pago_acumulado; 	

}

for($i = 0; $i< pg_num_rows($rsDespesaRp); $i++){
	
	$oDespesaRp = db_utils::fieldsMemory($rsDespesaRp,$i);
  
	$iExtraOrcDisp     += $oDespesaRp->vlrpag + $oDespesaRp->vlranu;

}

$iTotRecOrc   = $iRecCorr + $iRecCap + $iRecCorrIntr + $iRecCapIntr + $iOrcTransfRec;
$iTotRecExtr  = $iExtraOrcTransRec + $iExtraOrcIng;
$iTotDespOrc  = $iDespCorr + $iDespCap;
$iTotDespExtr = $iExtraOrcTransCon + $iExtraOrcDisp;

$iSomaRec  = $iTotRecOrc  + $iTotRecExtr;
$iSomaDesp = $iTotDespOrc + $iTotDespExtr;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt            = 4;
$pagina         = 1;

$pdf->addpage();

$pdf->setfont('arial','',6);

$pdf->cell(0,$alt,"ART. 103 DA LEI 4.320/1964.","T",1,"L",0);

$pdf->setfont('arial','b',7);

$pdf->cell(95,$alt,"RECEITA","TBR",0,"C",1);
$pdf->cell(95,$alt,"DESPESA","TBL",1,"C",1);

$pdf->Line(80 ,$pdf->getY(),80 ,133);
$pdf->Line(105,$pdf->getY(),105,133);
$pdf->Line(175,$pdf->getY(),175,133);

$pdf->cell(70,$alt,"TÍTULOS","TBR" ,0,"C",1);
$pdf->cell(25,$alt,"R$"			,"TBRL",0,"C",1);
$pdf->cell(70,$alt,"TÍTULOS","TBRL",0,"C",1);
$pdf->cell(25,$alt,"R$"		  ,"TBL" ,1,"C",1);
$pdf->ln();

$pdf->setfont('arial','b',7);

$pdf->cell(5 ,$alt,""							        									,0,0,"L",0);
$pdf->cell(65,$alt,"ORÇAMENTÁRIA"	 	 	 	 		 		 		 		 		,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotRecOrc,'f')							,0,1,"R",0);
$pdf->ln();

$pdf->setfont('arial','',7);

$pdf->cell(10,$alt,""																				,0,0,"L",0);
$pdf->cell(60,$alt,"Receitas Correntes"			                ,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iRecCorr,'f')               ,0,1,"R",0);
$pdf->cell(10 ,$alt,""								  		                ,0,0,"L",0);
$pdf->cell(60,$alt,"Receitas de Capital "		                ,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iRecCap ,'f')               ,0,1,"R",0);
$pdf->cell(10,$alt,""																			  ,0,0,"L",0);
$pdf->cell(60,$alt,"Receitas Correntes Intra-Orçamentárias" ,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iRecCorrIntr,'f')					  ,0,1,"R",0);
$pdf->cell(10 ,$alt,""																			,0,0,"L",0);
$pdf->cell(60,$alt,"Receitas de Capital Intra-Orçamentárias",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iRecCapIntr,'f')					  ,0,1,"R",0);
$pdf->cell(10 ,$alt,""																			,0,0,"L",0);
$pdf->cell(60,$alt,"Transferências Recebidas"								,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iOrcTransfRec,'f')				  ,0,1,"R",0);
$pdf->ln();

$pdf->cell(0,$alt,"","T",1,"R",0);

$pdf->setfont('arial','b',7);
$pdf->cell(5 ,$alt,""																				,0,0,"L",0);
$pdf->cell(65,$alt,"EXTRA-ORÇAMENTÁRIA"											,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotRecExtr,'f')  					,0,1,"R",0);
$pdf->ln();
$pdf->setfont('arial','',7);

$pdf->cell(10 ,$alt,""																			,0,0,"L",0);
$pdf->cell(60,$alt,"Transferências Recebidas"								,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iExtraOrcTransRec,'f')			,0,1,"R",0);
$pdf->cell(10 ,$alt,""																			,0,0,"L",0);
$pdf->cell(60,$alt,"Ingressos"															,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iExtraOrcIng,'f')					  ,0,1,"R",0);
$pdf->ln();

$pdf->cell(0,$alt,"","T",1,"R",0);

$pdf->setfont('arial','b',7);
$pdf->cell(5 ,$alt,""						                            ,0,0,"L",0);
$pdf->cell(65,$alt,"SOMA"				                            ,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iSomaRec,'f')								,0,1,"R",0);
$pdf->ln();

$pdf->cell(0,$alt,"","T",1,"R",0);

$pdf->cell(5 ,$alt,""																				,0,0,"L",0);
$pdf->cell(65,$alt,"DISPONIBILIDADE DO EXERCÍCIO ANTERIOR	" ,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iDispExeAnt,'f')						,0,1,"R",0);
$pdf->ln();

$pdf->setY(51);

$iSetX = 105; 

$pdf->setX($iSetX+5);
$pdf->cell(65,$alt,"ORÇAMENTÁRIA"	 	 	 	 		 		 		 		 		,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotDespOrc,'f')  					,0,1,"R",0);
$pdf->ln();

$pdf->setfont('arial','',7);

$pdf->setX($iSetX+10);
$pdf->cell(60,$alt,"Despesas Correntes"			                ,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iDespCorr,'f')              ,0,1,"R",0);
$pdf->setX($iSetX+10);
$pdf->cell(60,$alt,"Despesas de Capital "		                ,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iDespCap ,'f')              ,0,1,"R",0);
$pdf->setX($iSetX+10);
$pdf->cell(60,$alt,"" ,0,0,"L",0);
$pdf->cell(25,$alt,"" ,0,1,"R",0);
$pdf->setX($iSetX+10);
$pdf->cell(60,$alt,"",0,0,"L",0);
$pdf->cell(25,$alt,"",0,1,"R",0);
$pdf->setX($iSetX+10);
$pdf->cell(60,$alt,"",0,0,"L",0);
$pdf->cell(25,$alt,"",0,1,"R",0);
$pdf->ln();

$pdf->ln();
$pdf->setfont('arial','b',7);
$pdf->setX($iSetX+5);
$pdf->cell(65,$alt,"EXTRA-ORÇAMENTÁRIA"											,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotDespExtr,'f')						,0,1,"R",0);
$pdf->ln();
$pdf->setfont('arial','',7);

$pdf->setX($iSetX+10);
$pdf->cell(60,$alt,"Transferências Concedidas"							,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iExtraOrcTransCon,'f')	 		,0,1,"R",0);
$pdf->setX($iSetX+10);
$pdf->cell(60,$alt,"Dispêndios"															,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iExtraOrcDisp,'f')					,0,1,"R",0);
$pdf->ln();

$pdf->ln();
$pdf->setfont('arial','b',7);
$pdf->setX($iSetX+5);
$pdf->cell(65,$alt,"SOMA"				                            ,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iSomaDesp,'f')							,0,1,"R",0);
$pdf->ln();

$pdf->ln();
$pdf->setX($iSetX+5);
$pdf->cell(65,$alt,"DISPONIBILIDADE PARA O EXERCÍCIO SEGUINTE	",0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($iDispExeSeg,'f')						   ,0,1,"R",0);
$pdf->ln();

$pdf->setfont('arial','b',8);
$pdf->cell(70,$alt,'TOTAL'															 			 ,"TBR" ,0,"L",1);
$pdf->cell(25,$alt,db_formatar(($iSomaRec + $iDispExeAnt),'f') ,"TBRL",0,"R",1);
$pdf->cell(70,$alt,'TOTAL'																		 ,"TBRL",0,"L",1);
$pdf->cell(25,$alt,db_formatar(($iSomaDesp + $iDispExeSeg),'f'),"TBL" ,1,"R",1);
$pdf->Ln();
$pdf->setfont('arial','',5);

notasExplicativas(&$pdf,55,($oGet->mes>9?$oGet->mes:"0".$oGet->mes),190);

$pdf->Ln(25);
$pdf->setfont('arial','',8);

$sAssPref     = $classinatura->assinatura(1000,"","0");
$sAssPrefFunc = $classinatura->assinatura(1000,"","1");
$sAssPrefCPF  = $classinatura->assinatura(1000,"","2");

$sAssCont     = $classinatura->assinatura(1005,"","0");
$sAssContFunc = $classinatura->assinatura(1005,"","1");
$sAssContCPF  = $classinatura->assinatura(1005,"","2");

$sAssSecretFazenda	= $classinatura->assinatura(1002,"","0");
$sAssSecretFazendaFunc  = $classinatura->assinatura(1002,"","1");
$sAssSecretFazendaCPF   = $classinatura->assinatura(1002,"","2");

$pdf->cell(65,$alt,$sAssPref          ,0,0,"C",0);
$pdf->cell(65,$alt,$sAssCont          ,0,0,"C",0);
$pdf->cell(65,$alt,$sAssSecretFazenda ,0,1,"C",0);

$pdf->cell(65,$alt,$sAssPrefFunc         ,0,0,"C",0);
$pdf->cell(65,$alt,$sAssContFunc         ,0,0,"C",0);
$pdf->cell(65,$alt,$sAssSecretFazendaFunc,0,1,"C",0);

$pdf->cell(65,$alt,$sAssPrefCPF         ,0,0,"C",0);
$pdf->cell(65,$alt,$sAssContCPF         ,0,0,"C",0);
$pdf->cell(65,$alt,$sAssSecretFazendaCPF,0,1,"C",0);

$pdf->Output();
   
?>