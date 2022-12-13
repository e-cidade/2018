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

//echo "aqui"; exit();

if (!isset($arqinclude)){
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_utils.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_liborcamento.php");
  include("classes/db_orcparamrel_classe.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_orcparamrelopcre_classe.php");
  
  $classinatura = new cl_assinatura;
  $orcparamrel  = new cl_orcparamrel;
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
}

include_once("classes/db_conrelinfo_classe.php");
include_once("classes/db_conrelvalor_classe.php");
include_once("classes/db_orcparamrelopcre_classe.php");
include_once("classes/db_orcparamelemento_classe.php");
include_once("libs/db_utils.php");

//$clconrelinfo      = new cl_conrelinfo;
//$clconrelvalor     = new cl_conrelvalor;
//$oOrcParamRelopcre = new cl_orcparamrelopcre;
//$clorcparamelemento = new cl_orcparamelemento();
//$xinstit = split("-",$db_selinstit);
//$resultinst = pg_exec("select codigo,munic,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
//$descr_inst = '';
//$xvirg = '';
//$flag_abrev = false;
//$nTotalRcl = 0;
////******************************************************************
//for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
//  db_fieldsmemory($resultinst,$xins);
//  if (strlen(trim($nomeinstabrev)) > 0){
//       $descr_inst .= $xvirg.$nomeinstabrev;
//       $flag_abrev  = true;
//  }else{
//       $descr_inst .= $xvirg.$nomeinst;
//  }
//
//  $xvirg = ', ';
//}
//
//if ($flag_abrev == false){
//     if (strlen($descr_inst) > 42){
//          $descr_inst = substr($descr_inst,0,100);
//     }
//}

//$anousu     = db_getsession("DB_anousu");
//$anousu_ant = $anousu - 1;

$head2 = "<ENTE DA FEDERAÇÃO>";
$head3 = "LEI DE DIRETIZES ORÇAMENTÁRIAS";
$head4 = "ANEXO DE METAS FISCAIS";
$head5 = "RECEITAS E DESPEZAS PREVIDENCIÁRIAS DO REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES";
$head6 = "<ANO DE REFERÊNCIA>";
//$period = '';
//if ($periodo=="1Q"){
//  $period = "JANEIRO A ABRIL DE {$anousu}";
//}elseif($periodo=="2Q"){  
//  $period = "JANEIRO A AGOSTO DE {$anousu}";
//}elseif($periodo=="3Q"){  
//  $period = "JANEIRO A DEZEMBRO DE {$anousu}";
//}elseif($periodo=="1S"){
//  $period = "JANEIRO A JUNHO DE {$anousu}";
//}elseif($periodo=="2S"){
//  $period = "JANEIRO A DEZEMBRO DE {$anousu}";
//}
//$head6 = "$period";

/**
 * Linhas do relatorio
 */
// fechado ate a linha 360
	$aLinhasRelatorio              	= array();
	$aLinhasRelatorio[0]["label"]  	= "RECEITAS PREVIDENCIÁRIAS - RPPS (EXCETO INTRA-ORÇAMENTÁRIAS)(I)";    
	$aLinhasRelatorio[1]["label"]  	= "	 RECEITAS CORRENTES";    
	$aLinhasRelatorio[2]["label"]  	= "    Receita de Contribuíções dos Segurados";    
	$aLinhasRelatorio[3]["label"]  	= "      Pessoa Cívil";    
	$aLinhasRelatorio[4]["label"]  	= "      Pessoa Militar";    
	$aLinhasRelatorio[5]["label"]  	= "    Outras Receitas de Contribuíções";    
	$aLinhasRelatorio[6]["label"]  	= "    Receita Patrimonial";    	
	$aLinhasRelatorio[7]["label"]  	= "    Receita de Serviços";    	
	$aLinhasRelatorio[8]["label"]  	= "    Outras Receitas Correntes";
	$aLinhasRelatorio[9]["label"]  	= "      Compensação Previdenciária do RGPS para o RPPS";    
	$aLinhasRelatorio[10]["label"]  = "     Outras Receitas Correntes";    
	$aLinhasRelatorio[11]["label"]  = "  RECEITAS DE CAPITAL";
	$aLinhasRelatorio[12]["label"]  = "    Alienação de Bens, Direitos e Ativos";
	$aLinhasRelatorio[13]["label"]  = "    Amortização de Empréstimos";
	$aLinhasRelatorio[14]["label"]  = "    Outras Receitas de Capital";
	$aLinhasRelatorio[15]["label"]  = "  (-)DEDUÇÕES DA RECEITA";
	$aLinhasRelatorio[16]["label"]  = "RECEITAS PREVIDENCIÁRIAS - RPPS(INTRA-ORÇAMENTÁRIAS)(II)";
	$aLinhasRelatorio[17]["label"]  = "  RECEITAS CORRENTES";
	$aLinhasRelatorio[18]["label"]  = "    Receita de Contribuíções";
	$aLinhasRelatorio[19]["label"]  = "      Patronal";
	$aLinhasRelatorio[20]["label"]  = "        Pessoa Civil";
	$aLinhasRelatorio[21]["label"]  = "        Pessoa Militar";
	$aLinhasRelatorio[22]["label"]  = "      Cobertura de Déficit Atuarial";
	$aLinhasRelatorio[23]["label"]  = "      Regime de Débitos e Parcelamentos";
	$aLinhasRelatorio[24]["label"]  = "    Receita Patrimonial";
	$aLinhasRelatorio[25]["label"]  = "    Receita de Serviços";
	$aLinhasRelatorio[26]["label"]  = "    Outras Receitas Correntes";
	$aLinhasRelatorio[27]["label"]  = "  RECEITAS DE CAPITAL";
	$aLinhasRelatorio[28]["label"]  = "  (-) DEDUÇÕES DA RECEITA";
	$aLinhasRelatorio[29]["label"]  = "DESPESAS PREVIDENCIÁRIAS - RPPS(EXCETO INTRA-ORÇAMENTÁRIAS)(IV)";
	$aLinhasRelatorio[30]["label"]  = "  ADMINISTRAÇÃO";
	$aLinhasRelatorio[31]["label"]  = "    Despesas Correntes";
	$aLinhasRelatorio[32]["label"]  = "    Despesas de Capital";
	$aLinhasRelatorio[33]["label"]  = "  PREVIDÊNCIA";
	$aLinhasRelatorio[34]["label"]  = "    Pessoal Civil";
	$aLinhasRelatorio[35]["label"]  = "    Pessoal Militar";
	$aLinhasRelatorio[36]["label"]  = "    Outras Despesas Previdenciárias";
	$aLinhasRelatorio[37]["label"]  = "      Compensação Previdenciária do RPPS para o RGPS";
	$aLinhasRelatorio[38]["label"]  = "      Demais Despesas Previdenciárias";
	$aLinhasRelatorio[39]["label"]  = "DESPESAS PREVIDENCIÁRIAS - RPPS(INTRA-ORÇAMENTÁRIAS)(V)";
	$aLinhasRelatorio[40]["label"]  = "  ADMINISTRAÇÃO";
	$aLinhasRelatorio[41]["label"]  = "    Despesas Correntes";
	$aLinhasRelatorio[42]["label"]  = "    Despesas de Capital";
	$aLinhasRelatorio[43]["label"]  = "TOTAL DOS APORTES PARA O RPPS";
	$aLinhasRelatorio[44]["label"]  = "  Plano Financeiro";
	$aLinhasRelatorio[45]["label"]  = "    Recursos para Cobertura de Insuficiências Financeiras";
	$aLinhasRelatorio[46]["label"]  = "    Recursos para Formação de Reserva";
	$aLinhasRelatorio[47]["label"]  = "    Outros Aportes para o RPPS";
	$aLinhasRelatorio[48]["label"]  = "  Plano Previdênciário";
	$aLinhasRelatorio[49]["label"]  = "    Recursos para Cobertura de Déficit Financeiro";
	$aLinhasRelatorio[50]["label"]  = "    Recursos para Cobertura de Déficit Autuarial";
	$aLinhasRelatorio[51]["label"]  = "    Outros Aportes para o RPPS";
	
	for ($i = 0; $i < count($aLinhasRelatorio); $i++) {
  
	  $aLinhasRelatorio[$i]["ano2"] = 0;
	  $aLinhasRelatorio[$i]["ano3"]  = 0;
	  $aLinhasRelatorio[$i]["ano4"]  = 0;
  
	}
	
	
	
  $pdf = new PDF("P", "mm", "A4"); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $pdf->setfont('arial','',7);
  $pdf->cell(165,$alt,'AMF - Demonstrativo VI(LRF, art.4°,'.chr(167).' 2°, inciso IV,alíne "a")','B',0,"L",0);
  $pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);
  //$pdf->cell(100,$alt,"",'RT',0,"C",0);
  //$pdf->cell(90,$alt,"VALOR",'LTB',1,"C",0);
  $pdf->cell(100,$alt,"",0,0,"C",0);
  $pdf->cell(30,$alt,"",'LR',0,"C",0);
  $pdf->cell(30,$alt,"",'LR',0,"C",0);
  $pdf->cell(30,$alt,"",'L',1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(100,$alt,"RECEITAS",'R',0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,"<Ano-2>",'LR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-3>",'LR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-4>",'L',1,"C",0);
  $pdf->cell(100,$alt,"",'RB',0,"C",0);
  $pdf->cell(30,$alt,"",'LBR',0,"C",0);
  $pdf->cell(30,$alt,"",'LBR',0,"C",0);
  $pdf->cell(30,$alt,"",'LB',1,"C",0);
  
  for($i=0;$i<29;$i++){
		$pdf->cell(100,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
	  $pdf->cell(30,$alt,$aLinhasRelatorio[$i]["ano2"],'LR',0,"C",0);
	  $pdf->cell(30,$alt,$aLinhasRelatorio[$i]["ano3"],'LR',0,"C",0);
	  $pdf->cell(30,$alt,$aLinhasRelatorio[$i]["ano4"],'L',1,"C",0);
  
  }
  
  //$pdf->cell(190,$alt,"",'TB',1,"L",0);
  
  
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(100,$alt,"TOTAL DAS RECEITAS PREVIDENCIÁRIAS (III)=(I+II)",'TR',0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,"<Ano-2>",'LTR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-3>",'LTR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-4>",'LT',1,"C",0);
  
  $pdf->cell(190,$alt,"",'TB',1,"L",0);
  
  $pdf->cell(100,$alt,"",'R',0,"C",0);
  $pdf->cell(30,$alt,"",'R',0,"C",0);
  $pdf->cell(30,$alt,"",'R',0,"C",0);
  $pdf->cell(30,$alt,"",0,1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(100,$alt,"DESPESAS",'R',0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,"<Ano-2>",'LR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-3>",'LR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-4>",'L',1,"C",0);
  $pdf->cell(100,$alt,"",'RB',0,"C",0);
  $pdf->cell(30,$alt,"",'LBR',0,"C",0);
  $pdf->cell(30,$alt,"",'LBR',0,"C",0);
  $pdf->cell(30,$alt,"",'LB',1,"C",0);
  
  
  for($i=29;$i<43;$i++){
		$pdf->cell(100,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
	  $pdf->cell(30,$alt,$aLinhasRelatorio[$i]["ano2"],'LR',0,"C",0);
	  $pdf->cell(30,$alt,$aLinhasRelatorio[$i]["ano3"],'LR',0,"C",0);
	  $pdf->cell(30,$alt,$aLinhasRelatorio[$i]["ano4"],'L',1,"C",0);
  
  }
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(100,$alt,"TOTAL DAS RECEITAS PREVIDENCIÁRIAS (VI)=(IV+V)",'TR',0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,"<Ano-2>",'LTR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-3>",'LTR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-4>",'LT',1,"C",0);
  
  $pdf->cell(190,$alt,"",'TB',1,"L",0);
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(100,$alt,"RESULTADO PREVIDENCIÁRIO (VII)=(III-VI)",'TR',0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,"<Ano-2>",'LTR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-3>",'LTR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-4>",'LT',1,"C",0);
  
  $pdf->cell(190,$alt,"",'T',1,"L",0);
  
  $pdf->addpage();
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(100,$alt,"APORTES DE RECURSOS PARA O REGIME PRÓPRIO",'TR',0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,"",'TR',0,"C",0);
  $pdf->cell(30,$alt,"",'TR',0,"C",0);
  $pdf->cell(30,$alt,"",'T',1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(100,$alt,"DE PREVIDÊNCIA DO SERVIDOR",'R',0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,"<Ano-2>",'LR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-3>",'LR',0,"C",0);
  $pdf->cell(30,$alt,"<Ano-4>",'L',1,"C",0);
  $pdf->cell(100,$alt,"",'RB',0,"C",0);
  $pdf->cell(30,$alt,"",'LBR',0,"C",0);
  $pdf->cell(30,$alt,"",'LBR',0,"C",0);
  $pdf->cell(30,$alt,"",'LB',1,"C",0);
  
  for($i=42;$i<52;$i++){
		$pdf->cell(100,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
	  $pdf->cell(30,$alt,$aLinhasRelatorio[$i]["ano2"],'LR',0,"C",0);
	  $pdf->cell(30,$alt,$aLinhasRelatorio[$i]["ano3"],'LR',0,"C",0);
	  $pdf->cell(30,$alt,$aLinhasRelatorio[$i]["ano4"],'L',1,"C",0);
  
  }
  $pdf->cell(190,$alt,"",'T',1,"L",0);
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(100,$alt,"RESERVA ORÇAMENTÁRIA DO RPPS",'TR',0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,"",'TR',0,"C",0);
  $pdf->cell(30,$alt,"",'TR',0,"C",0);
  $pdf->cell(30,$alt,"",'T',1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(100,$alt,"BENS E DIREITOS DO RPPS",'TR',0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,"",'TR',0,"C",0);
  $pdf->cell(30,$alt,"",'TR',0,"C",0);
  $pdf->cell(30,$alt,"",'T',1,"C",0);
  $pdf->cell(190,$alt,"",'T',1,"L",0);
  /*
  
  $pdf->ln();
  // ----------------------------------------------------------------
  //notasExplicativas(&$pdf, 63, $periodo,190); 
//  $pdf->Ln(5);
//  
//  // assinaturas
//  $pdf->setfont('arial','',5);
//  $pdf->ln(20);
//  
//  assinaturas(&$pdf,&$classinatura,'GF');
  */
  $pdf->Output();

?>