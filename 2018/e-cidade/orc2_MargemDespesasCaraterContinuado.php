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
$head5 = "MARGEM DE EXPANSÃO DAS DESPESAS OBRIGATÓRIAS DE CARÁTER CONTINUADO";
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
	$aLinhasRelatorio              = array();
	$aLinhasRelatorio[0]["label"]  = "Aumento Permanente da Receita";    
	$aLinhasRelatorio[1]["label"]  = "(-) Transferências Constitucionais";    
	$aLinhasRelatorio[2]["label"]  = "(-) Transferências FUNDEB";    
	$aLinhasRelatorio[3]["label"]  = "Redução Permanente de Despesa (II)";    
	$aLinhasRelatorio[4]["label"]  = "  Novas DOCC";    
	$aLinhasRelatorio[5]["label"]  = "  Novas DOCC geradas por PPP";    
	
	for ($i = 0; $i < count($aLinhasRelatorio); $i++) {
  
	  $aLinhasRelatorio[$i]["ano2"] = 0;
  
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
  $pdf->cell(165,$alt,'AMF - Tabela 9(LRF, art.4°,'.chr(167).' inciso V)','B',0,"L",0);
  $pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);
  //$pdf->cell(100,$alt,"",'RT',0,"C",0);
  //$pdf->cell(90,$alt,"VALOR",'LTB',1,"C",0);
  $pdf->cell(95,$alt,"EVENTOS",'B',0,"C",0);
  $pdf->cell(95,$alt,"Valor Previsto para <Ano de Referencia>",'LB',1,"C",0);
   
  for($i=0;$i<3;$i++){
		$pdf->cell(95,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
	  $pdf->cell(95,$alt,$aLinhasRelatorio[$i]["ano2"],0,1,"C",0);
	   
  }

  $pdf->cell(95,$alt,"Saldo Final do Aumento Permanente de Receita (I)",'RBT',0,"L",0);
  $pdf->cell(95,$alt,"Linha 12-13-14",'BT',1,"L",0);
  
  $pdf->cell(95,$alt,$aLinhasRelatorio[3]["label"],'BR',0,"L",0);
  $pdf->cell(95,$alt,"",'B',1,"C",0);
  
  $pdf->cell(95,$alt,"Margem Bruta de Despesa (II)",'RBT',0,"L",0);
  $pdf->cell(95,$alt,"Linha 15+16",'BT',1,"L",0);
  
  $pdf->cell(95,$alt,"Saldo Utilizado da Margem Bruta (IV)",'RT',0,"L",0);
  $pdf->cell(95,$alt,"Linha 19+20",'T',1,"L",0);
  
  for($i=4;$i<6;$i++){
		$pdf->cell(95,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
	  $pdf->cell(95,$alt,$aLinhasRelatorio[$i]["ano2"],0,1,"C",0);
	   
  }
  
  $pdf->cell(95,$alt,"Margem Líquida de Expansão de DOCC(V)=(III-IV)",'RBT',0,"L",0);
  $pdf->cell(95,$alt,"Linha 17-18",'BT',1,"L",0);
  /*
  $pdf->cell(85,$alt,"",'RB',0,"C",0);
  $pdf->cell(35,$alt,"",'LBR',0,"C",0);
  $pdf->cell(35,$alt,"",'LBR',0,"C",0);
  $pdf->cell(35,$alt,"",'LB',1,"C",0);
  
  for($i=3;$i<11;$i++){
		$pdf->cell(85,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
	  $pdf->cell(35,$alt,$aLinhasRelatorio[$i]["ano2"],'LR',0,"C",0);
	  $pdf->cell(35,$alt,$aLinhasRelatorio[$i]["ano3"],'LR',0,"C",0);
	  $pdf->cell(35,$alt,$aLinhasRelatorio[$i]["ano4"],'L',1,"C",0);
  
  }
  
  //Remover depois
  $pdf->cell(190,$alt,"",'TB',1,"L",0);
  
  $pdf->cell(85,$alt,"",'RT',0,"C",0);
  $pdf->cell(35,$alt,"",'RT',0,"C",0);
  $pdf->cell(35,$alt,"",'RT',0,"C",0);
  $pdf->cell(35,$alt,"",'T',1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(85,$alt,"SALDO FINANCEIRO",'R',0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(35,$alt,"<Ano-2>",'LR',0,"C",0);
  $pdf->cell(35,$alt,"<Ano-3>",'LR',0,"C",0);
  $pdf->cell(35,$alt,"<Ano-4>",'L',1,"C",0);
  $pdf->cell(85,$alt,"",'RB',0,"C",0);
  $pdf->cell(35,$alt,"",'LBR',0,"C",0);
  $pdf->cell(35,$alt,"",'LBR',0,"C",0);
  $pdf->cell(35,$alt,"",'LB',1,"C",0);
  
  for($i=11;$i<12;$i++){
		$pdf->cell(85,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
	  $pdf->cell(35,$alt,$aLinhasRelatorio[$i]["ano2"],'LR',0,"C",0);
	  $pdf->cell(35,$alt,$aLinhasRelatorio[$i]["ano3"],'LR',0,"C",0);
	  $pdf->cell(35,$alt,$aLinhasRelatorio[$i]["ano4"],'L',1,"C",0);
  
  }
  $pdf->cell(190,$alt,"",'T',1,"L",0);
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