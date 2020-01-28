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
  include("model/linhaRelatorioContabil.model.php");
  include ("model/relatorioContabil.model.php");
  $oRelatorio = new relatorioContabil(67);
  
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

$codigo = db_getsession("DB_instit");
$resultinst = db_query("select munic from db_config where codigo = $codigo");
db_fieldsmemory($resultinst,0);
$anousu     = db_getsession("DB_anousu");
$anobase		= $anousu + 1;
$oGet  = db_utils::postMemory($_GET);
/*
echo "<pre>";
print_r($oRelatorio->getLinhas());
echo "</pre>";
*/
//exit;

$aRelatorio = $oRelatorio->getLinhas();
/*
 * validação da opção ldo ou loa, para imprimir no head3.
 */
if ($oGet->sModelo == 'ldo') {
  $sModelo = 'LEI DE DIRETRIZES ORÇAMENTÁRIAS';
} else {
  $sModelo = 'LEI ORÇAMENTÁRIA ANUAL';
}

$head2 = "MUNICÍPIO DE ".$munic;
$head3 = $sModelo;
$head4 = "ANEXO DE METAS FISCAIS";
$head5 = $anobase;
$head6 = "EVOLUÇÂO DO PATRIMÔNIO LÍQUIDO";

  $pdf = new PDF("P", "mm", "A4"); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $pdf->setfont('arial','',7);
  $pdf->cell(165,$alt,'AMF - Demonstrativo IV(LRF, art. 4°,'.chr(167).' 2°, inciso III)','B',0,"L",0);
  $pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);
  //$pdf->cell(100,$alt,"",'RT',0,"C",0);
  //$pdf->cell(90,$alt,"VALOR",'LTB',1,"C",0);
  $pdf->cell(70,$alt,"",0,0,"C",0);
  $pdf->cell(20,$alt,"",'LR',0,"C",0);
  $pdf->cell(20,$alt,"",'LR',0,"C",0);
  $pdf->cell(20,$alt,"",'LR',0,"C",0);
  $pdf->cell(20,$alt,"",'LR',0,"C",0);
  $pdf->cell(20,$alt,"",'LR',0,"C",0);
  $pdf->cell(20,$alt,"",'L',1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(70,$alt,"PATRIMÔNIO LÍQUIDO",'R',0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$anobase-2,'LR',0,"C",0);
  $pdf->cell(20,$alt,"%",'LR',0,"C",0);
  $pdf->cell(20,$alt,$anobase-3,'LR',0,"C",0);
  $pdf->cell(20,$alt,"%",'LR',0,"C",0);
  $pdf->cell(20,$alt,$anobase-4,'LR',0,"C",0);
  $pdf->cell(20,$alt,"%",'L',1,"C",0);
  $pdf->cell(70,$alt,"",'RB',0,"C",0);
  $pdf->cell(20,$alt,"",'LBR',0,"C",0);
  $pdf->cell(20,$alt,"",'LBR',0,"C",0);
  $pdf->cell(20,$alt,"",'LBR',0,"C",0);
  $pdf->cell(20,$alt,"",'LBR',0,"C",0);
  $pdf->cell(20,$alt,"",'LBR',0,"C",0);
  $pdf->cell(20,$alt,"",'LB',1,"C",0);
  
  //leitura das 3 primeira linhas do array
	$aLinhasRelatorio = array();
	//Variáveis para armazenar o total da coluna da linha
	$totalcoluna_c1 = 0;
	$totalcoluna_c2 = 0;
	$totalcoluna_c3 = 0;
	
	for($i=1;$i<4;$i++){
		$total_c1 = 0;
		$total_c2 = 0;
		$total_c3 = 0;
		$objeto = $aRelatorio[$i];
		
		$aLinhasRelatorio[$i]['label'] = $aRelatorio[$i]->o69_labelrel;
		
		foreach ($aRelatorio[$i]->valoresVariaveis as $oValores){
			
			$total_c1 += $oValores->colunas[0]->o117_valor;  
			$total_c2 += $oValores->colunas[1]->o117_valor;  
			$total_c3 += $oValores->colunas[2]->o117_valor;
			
		}
		//Acumula o total de linha repetidas da coluna
		$aLinhasRelatorio[$i]['coluna'][0] = $total_c1;
		$aLinhasRelatorio[$i]['coluna'][1] = $total_c2;
		$aLinhasRelatorio[$i]['coluna'][2] = $total_c3;
		//Acumula o total da coluna de todas as linhas
		$totalcoluna_c1 += $total_c1;
		$totalcoluna_c2 += $total_c2;
		$totalcoluna_c3 += $total_c3;
	}
  
  for($i=1;$i<4;$i++){
    
    if ($totalcoluna_c1 == 0 || $totalcoluna_c2 == 0 || $totalcoluna_c3 == 0) {
      continue;
    }
		$pdf->cell(70,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
	  $pdf->cell(20,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][0],'f'),'LR',0,"R",0);
	  $pdf->cell(20,$alt,db_formatar(($aLinhasRelatorio[$i]["coluna"][0]*100)/$totalcoluna_c1,'f'),'LR',0,"R",0);
	  $pdf->cell(20,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][1],'f'),'LR',0,"R",0);
	  $pdf->cell(20,$alt,db_formatar(($aLinhasRelatorio[$i]["coluna"][1]*100)/$totalcoluna_c2,'f'),'LR',0,"R",0);
	  $pdf->cell(20,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][2],'f'),'L',0,"R",0);
	  $pdf->cell(20,$alt,db_formatar(($aLinhasRelatorio[$i]["coluna"][2]*100)/$totalcoluna_c3,'f'),'L',1,"R",0);
  }
  
  //Linha do Total
  $pdf->cell(70,$alt,"TOTAL",'T',0,"L",0);
  $pdf->cell(20,$alt,db_formatar($totalcoluna_c1,'f'),'TLR',0,"R",0);
  $pdf->cell(20,$alt,"100",'TLR',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($totalcoluna_c2,'f'),'TLR',0,"R",0);
  $pdf->cell(20,$alt,"100",'TLR',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($totalcoluna_c3,'f'),'TL',0,"R",0);
  $pdf->cell(20,$alt,"100",'TL',1,"R",0);
    
  $pdf->cell(190,$alt,"",'TB',1,"L",0);

  $pdf->cell(190,$alt,"",0,1,"C",0);
  $pdf->cell(190,$alt,"REGIME PREVIDENCIÁRIO",0,1,"C",0);
  $pdf->cell(190,$alt,"",'B',1,"C",0);
  
  
  $pdf->cell(70,$alt,"",0,0,"C",0);
  $pdf->cell(20,$alt,"",'LR',0,"C",0);
  $pdf->cell(20,$alt,"",'LR',0,"C",0);
  $pdf->cell(20,$alt,"",'LR',0,"C",0);
  $pdf->cell(20,$alt,"",'LR',0,"C",0);
  $pdf->cell(20,$alt,"",'LR',0,"C",0);
  $pdf->cell(20,$alt,"",'L',1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(70,$alt,"PATRIMÔNIO LÍQUIDO",'R',0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$anobase-2,'LR',0,"C",0);
  $pdf->cell(20,$alt,"%",'LR',0,"C",0);
  $pdf->cell(20,$alt,$anobase-3,'LR',0,"C",0);
  $pdf->cell(20,$alt,"%",'LR',0,"C",0);
  $pdf->cell(20,$alt,$anobase-4,'LR',0,"C",0);
  $pdf->cell(20,$alt,"%",'L',1,"C",0);
  $pdf->cell(70,$alt,"",'RB',0,"C",0);
  $pdf->cell(20,$alt,"",'LBR',0,"C",0);
  $pdf->cell(20,$alt,"",'LBR',0,"C",0);
  $pdf->cell(20,$alt,"",'LBR',0,"C",0);
  $pdf->cell(20,$alt,"",'LBR',0,"C",0);
  $pdf->cell(20,$alt,"",'LBR',0,"C",0);
  $pdf->cell(20,$alt,"",'LB',1,"C",0);
  
  //leitura das 3 ultimas linhas do array
	$aLinhasRelatorio = array();
	$totalcoluna_c1 = 0;
	$totalcoluna_c2 = 0;
	$totalcoluna_c3 = 0;
	
	for($i=4;$i<7;$i++){
		//Variáveis para armazenar o total da coluna da linha
		$total_c1 = 0;
		$total_c2 = 0;
		$total_c3 = 0;
		
		$objeto = $aRelatorio[$i];
		
		$aLinhasRelatorio[$i]['label'] = $aRelatorio[$i]->o69_labelrel;
		
		foreach ($aRelatorio[$i]->valoresVariaveis as $oValores){
			
			$total_c1 += $oValores->colunas[0]->o117_valor;  
			$total_c2 += $oValores->colunas[1]->o117_valor;  
			$total_c3 += $oValores->colunas[2]->o117_valor;
			
		}
		//Acumula o total de linha repetidas da coluna
		$aLinhasRelatorio[$i]['coluna'][0] = $total_c1;
		$aLinhasRelatorio[$i]['coluna'][1] = $total_c2;
		$aLinhasRelatorio[$i]['coluna'][2] = $total_c3;
		
		//Acumula o total da coluna de todas as linhas
		$totalcoluna_c1 += $total_c1;
		$totalcoluna_c2 += $total_c2;
		$totalcoluna_c3 += $total_c3;
	}
  
  for ($i = 4; $i < 7; $i++) {
    
    if ($totalcoluna_c1 == 0 || $totalcoluna_c2 == 0 || $totalcoluna_c3 == 0) {
      continue;
    }
    
		$pdf->cell(70,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
	  $pdf->cell(20,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][0],'f'),'LR',0,"R",0);
	  $pdf->cell(20,$alt,db_formatar(($aLinhasRelatorio[$i]["coluna"][0]*100)/$totalcoluna_c1,'f'),'LR',0,"R",0);
	  $pdf->cell(20,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][1],'f'),'LR',0,"R",0);
	  $pdf->cell(20,$alt,db_formatar(($aLinhasRelatorio[$i]["coluna"][1]*100)/$totalcoluna_c2,'f'),'LR',0,"R",0);
	  $pdf->cell(20,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][2],'f'),'L',0,"R",0);
	  $pdf->cell(20,$alt,db_formatar(($aLinhasRelatorio[$i]["coluna"][2]*100)/$totalcoluna_c3,'f'),'L',1,"R",0);
  } 
 
  //Linha total da segunda parte
 
  $pdf->cell(70,$alt,"TOTAL",'T',0,"L",0);
  $pdf->cell(20,$alt,db_formatar($totalcoluna_c1,'f'),'TLR',0,"R",0);
  $pdf->cell(20,$alt,"100",'TLR',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($totalcoluna_c2,'f'),'TLR',0,"R",0);
  $pdf->cell(20,$alt,"100",'TLR',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($totalcoluna_c3,'f'),'TL',0,"R",0);
  $pdf->cell(20,$alt,"100",'TL',1,"R",0);
 
  $pdf->cell(190,$alt,"",'T',1,"L",0);
  
  $pdf->ln();
  // ----------------------------------------------------------------
  $oRelatorio->getNotaExplicativa($pdf,1); 
  //$pdf->Ln(5);
//  
//  // assinaturas
//  $pdf->setfont('arial','',5);
//  $pdf->ln(20);
//  
//  assinaturas(&$pdf,&$classinatura,'GF');
  
  $pdf->Output();
  exit();
?>