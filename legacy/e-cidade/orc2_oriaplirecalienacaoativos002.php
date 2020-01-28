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
  include ("model/relatorioContabil.model.php");
  include("model/linhaRelatorioContabil.model.php");
  
  $oRelatorio = new relatorioContabil(70);
  
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

/*
echo "<pre style=''>";
print_r($oRelatorio->getLinhas());
echo "</pre>";
exit;
*/

$codigo = db_getsession("DB_instit");
$resultinst = db_query("select munic from db_config where codigo = $codigo");
db_fieldsmemory($resultinst,0);
$anousu     = db_getsession("DB_anousu");
$anobase		= $anousu + 1;
$oGet  = db_utils::postMemory($_GET);
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
$head6 = "ORIGEM E APLICAÇÂO DOS RECURSOS OBTIDOS COM A ALIENAÇÃO DE ATIVOS";

	$pdf = new PDF("P", "mm", "A4"); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $pdf->setfont('arial','',7);
  $pdf->cell(165,$alt,'AMF - Demonstrativo V(LRF, art. 4°,'.chr(167).' 2°, inciso III)','B',0,"L",0);
  $pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);
  $pdf->cell(85,$alt,"",0,0,"C",0);
  $pdf->cell(35,$alt,"",'LR',0,"C",0);
  $pdf->cell(35,$alt,"",'LR',0,"C",0);
  $pdf->cell(35,$alt,"",'L',1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(85,$alt,"RECEITAS REALIZADAS",'R',0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(35,$alt,$anobase-2,'LR',0,"C",0);
  $pdf->cell(35,$alt,$anobase-3,'LR',0,"C",0);
  $pdf->cell(35,$alt,$anobase-4,'L',1,"C",0);
  $pdf->cell(85,$alt,"",'RB',0,"C",0);
  $pdf->cell(35,$alt,"(a)",'LBR',0,"C",0);
  $pdf->cell(35,$alt,"(b)",'LBR',0,"C",0);
  $pdf->cell(35,$alt,"(c)",'LB',1,"C",0);
  
  
   //leitura das 3 primeira linhas do array
	$aLinhasRelatorio = array();
	//Variáveis para armazenar o total da coluna da linha
	$totalReceitasCapital_c1 = 0;
	$totalReceitasCapital_c2 = 0;
	$totalReceitasCapital_c3 = 0;
	
	for($i=1;$i<3;$i++){
		
		$total_c1 = 0;
		$total_c2 = 0;
		$total_c3 = 0;
		$objeto = $aRelatorio[$i];
		
		$aLinhasRelatorio[$i]['label'] = "  ".$aRelatorio[$i]->o69_labelrel;
		
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
		$totalReceitasCapital_c1 += $total_c1;
		$totalReceitasCapital_c2 += $total_c2;
		$totalReceitasCapital_c3 += $total_c3;
	}
  
	$pdf->cell(85,$alt,"RECEITAS DE CAPITAL - ALIENAÇÃO DE ATIVOS(I)",'R',0,"L",0);
	$pdf->cell(35,$alt,db_formatar($totalReceitasCapital_c1,'f'),'LR',0,"R",0);
	$pdf->cell(35,$alt,db_formatar($totalReceitasCapital_c2,'f'),'LR',0,"R",0);
	$pdf->cell(35,$alt,db_formatar($totalReceitasCapital_c3,'f'),'L',1,"R",0);
	
  for($i=1;$i<3;$i++){
		$pdf->cell(85,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
	  $pdf->cell(35,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][0],'f'),'LR',0,"R",0);
	  $pdf->cell(35,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][1],'f'),'LR',0,"R",0);
	  $pdf->cell(35,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][2],'f'),'L',1,"R",0);
  }  
  //Separa a primeira parte do relatorio
  $pdf->cell(190,$alt,"",'TB',1,"L",0);
  //Inicio da segunda parte do reltorio
  $pdf->cell(85,$alt,"",'R',0,"C",0);
  $pdf->cell(35,$alt,"",'R',0,"C",0);
  $pdf->cell(35,$alt,"",'R',0,"C",0);
  $pdf->cell(35,$alt,"",0,1,"C",0);
 
  $pdf->setfont('arial','b',7);
  $pdf->cell(85,$alt,"DESPESAS EXECUTADAS",'R',0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(35,$alt,$anobase-2,'LR',0,"C",0);
  $pdf->cell(35,$alt,$anobase-3,'LR',0,"C",0);
  $pdf->cell(35,$alt,$anobase-4,'L',1,"C",0);
  $pdf->cell(85,$alt,"",'RB',0,"C",0);
  $pdf->cell(35,$alt,"(d)",'LBR',0,"C",0);
  $pdf->cell(35,$alt,"(e)",'LBR',0,"C",0);
  $pdf->cell(35,$alt,"(f)",'LB',1,"C",0);
  
  $aLinhasRelatorio1 = array();
	//Variáveis para armazenar o total da coluna da linha
	$totalDespesasCapital_c1 = 0;
	$totalDespesasCapital_c2 = 0;
	$totalDespesasCapital_c3 = 0;
	
	for($i=3;$i<6;$i++){
		$total_c1 = 0;
		$total_c2 = 0;
		$total_c3 = 0;
		$objeto = $aRelatorio[$i];
		
		$aLinhasRelatorio1[$i]['label'] = "    ".$aRelatorio[$i]->o69_labelrel;
		
		foreach ($aRelatorio[$i]->valoresVariaveis as $oValores){
			
			$total_c1 += $oValores->colunas[0]->o117_valor;  
			$total_c2 += $oValores->colunas[1]->o117_valor;  
			$total_c3 += $oValores->colunas[2]->o117_valor;
			
		}
		//Acumula o total de linha repetidas da coluna
		$aLinhasRelatorio1[$i]['coluna'][0] = $total_c1;
		$aLinhasRelatorio1[$i]['coluna'][1] = $total_c2;
		$aLinhasRelatorio1[$i]['coluna'][2] = $total_c3;
		//Acumula o total da coluna de todas as linhas
		$totalDespesasCapital_c1 += $total_c1;
		$totalDespesasCapital_c2 += $total_c2;
		$totalDespesasCapital_c3 += $total_c3;
	}
	 
  $aLinhasRelatorio2 = array();
	//Variáveis para armazenar o total da coluna da linha
	$totalDespesasCorrentes_c1 = 0;
	$totalDespesasCorrentes_c2 = 0;
	$totalDespesasCorrentes_c3 = 0;
	
	for($i=6;$i<8;$i++){
		$total_c1 = 0;
		$total_c2 = 0;
		$total_c3 = 0;
		$objeto = $aRelatorio[$i];
		
		$aLinhasRelatorio2[$i]['label'] = "    ".$aRelatorio[$i]->o69_labelrel;
		
		foreach ($aRelatorio[$i]->valoresVariaveis as $oValores){
			
			$total_c1 += $oValores->colunas[0]->o117_valor;  
			$total_c2 += $oValores->colunas[1]->o117_valor;  
			$total_c3 += $oValores->colunas[2]->o117_valor;
			
		}
		//Acumula o total de linha repetidas da coluna
		$aLinhasRelatorio2[$i]['coluna'][0] = $total_c1;
		$aLinhasRelatorio2[$i]['coluna'][1] = $total_c2;
		$aLinhasRelatorio2[$i]['coluna'][2] = $total_c3;
		//Acumula o total da coluna de todas as linhas
		$totalDespesasCorrentes_c1 += $total_c1;
		$totalDespesasCorrentes_c2 += $total_c2;
		$totalDespesasCorrentes_c3 += $total_c3;
	}
 
  $pdf->cell(85,$alt,"APLICAÇÕES DOS RECURSOS DA ALIENAÇÃO DE ATIVOS(II)",'R',0,"L",0);
	$pdf->cell(35,$alt,db_formatar($totalDespesasCapital_c1+$totalDespesasCorrentes_c1,'f'),'LR',0,"R",0);
	$pdf->cell(35,$alt,db_formatar($totalDespesasCapital_c2+$totalDespesasCorrentes_c2,'f'),'LR',0,"R",0);
	$pdf->cell(35,$alt,db_formatar($totalDespesasCapital_c3+$totalDespesasCorrentes_c3,'f'),'L',1,"R",0);
	
	$pdf->cell(85,$alt,"  DESPESAS DE CAPITAL",'R',0,"L",0);
	$pdf->cell(35,$alt,db_formatar($totalDespesasCapital_c1,'f'),'LR',0,"R",0);
	$pdf->cell(35,$alt,db_formatar($totalDespesasCapital_c2,'f'),'LR',0,"R",0);
	$pdf->cell(35,$alt,db_formatar($totalDespesasCapital_c3,'f'),'L',1,"R",0);
	
  for($i=3;$i<6;$i++){
		$pdf->cell(85,$alt,$aLinhasRelatorio1[$i]["label"],'R',0,"L",0);
	  $pdf->cell(35,$alt,db_formatar($aLinhasRelatorio1[$i]["coluna"][0],'f'),'LR',0,"R",0);
	  $pdf->cell(35,$alt,db_formatar($aLinhasRelatorio1[$i]["coluna"][1],'f'),'LR',0,"R",0);
	  $pdf->cell(35,$alt,db_formatar($aLinhasRelatorio1[$i]["coluna"][2],'f'),'L',1,"R",0);
  }  
  
  $pdf->cell(85,$alt,"DESPESAS CORRENTES DOS REGIMES DE PREVIDÊNCIA",'R',0,"L",0);
	$pdf->cell(35,$alt,db_formatar($totalDespesasCorrentes_c1,'f'),'LR',0,"R",0);
	$pdf->cell(35,$alt,db_formatar($totalDespesasCorrentes_c2,'f'),'LR',0,"R",0);
	$pdf->cell(35,$alt,db_formatar($totalDespesasCorrentes_c3,'f'),'L',1,"R",0);
	
  for($i=6;$i<8;$i++){
		$pdf->cell(85,$alt,$aLinhasRelatorio2[$i]["label"],'R',0,"L",0);
	  $pdf->cell(35,$alt,db_formatar($aLinhasRelatorio2[$i]["coluna"][0],'f'),'LR',0,"R",0);
	  $pdf->cell(35,$alt,db_formatar($aLinhasRelatorio2[$i]["coluna"][1],'f'),'LR',0,"R",0);
	  $pdf->cell(35,$alt,db_formatar($aLinhasRelatorio2[$i]["coluna"][2],'f'),'L',1,"R",0);
  } 
  
  $pdf->cell(190,$alt,"",'TB',1,"L",0);
  
  $pdf->cell(85,$alt,"",'RT',0,"C",0);
  $pdf->cell(35,$alt,"",'RT',0,"C",0);
  $pdf->cell(35,$alt,"",'RT',0,"C",0);
  $pdf->cell(35,$alt,"",'T',1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(85,$alt,"SALDO FINANCEIRO",'R',0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(35,$alt,$anobase-2,'LR',0,"C",0);
  $pdf->cell(35,$alt,$anobase-3,'LR',0,"C",0);
  $pdf->cell(35,$alt,$anobase-4,'L',1,"C",0);
  $pdf->cell(85,$alt,"",'RB',0,"C",0);
  $pdf->cell(35,$alt,"(g)=((Ia-IId)+IIIh)",'LBR',0,"C",0);
  $pdf->cell(35,$alt,"(h)=((Ib-IIe)+IIIi)",'LBR',0,"C",0);
  $pdf->cell(35,$alt,"(i)=(Ic-IIf)",'LB',1,"C",0);
  
  $valor_i = ($totalReceitasCapital_c3-($totalDespesasCapital_c3+$totalDespesasCorrentes_c3));
  $valor_h = ($totalReceitasCapital_c2-($totalDespesasCapital_c2+$totalDespesasCorrentes_c2))+$valor_i;
  $valor_g = ($totalReceitasCapital_c1-($totalDespesasCapital_c1+$totalDespesasCorrentes_c1))+$valor_h;
  
  $pdf->cell(85,$alt,"VALOR(III)",'RB',0,"L",0);
  $pdf->cell(35,$alt,db_formatar($valor_g,'f'),'LBR',0,"R",0);
  $pdf->cell(35,$alt,db_formatar($valor_h,'f'),'LBR',0,"R",0);
  $pdf->cell(35,$alt,db_formatar($valor_i,'f'),'LB',1,"R",0);
  //------------------------------------------------------------------
 	$pdf->ln();
  $oRelatorio->getNotaExplicativa($pdf,1);

  /*
  $pdf->Ln(5);
  
	//assinaturas
  $pdf->setfont('arial','',5);
  $pdf->ln(20);
  
 	assinaturas(&$pdf,&$classinatura,'GF');
 	*/
  $pdf->Output();

?>