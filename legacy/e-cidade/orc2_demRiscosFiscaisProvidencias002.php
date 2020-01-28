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
  include("model/relatorioContabil.model.php");
  include("model/linhaRelatorioContabil.model.php");
  
  $oRelatorio = new relatorioContabil(68);
  
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
$oGet  = db_utils::postMemory($_GET);
$codigo = db_getsession("DB_instit");
$resultinst = db_query("select munic from db_config where codigo = $codigo");
db_fieldsmemory($resultinst,0);


$anousu     = db_getsession("DB_anousu");
$anobase		= $anousu + 1;

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
$head4 = "ANEXO DE RISCOS FISCAIS";
$head5 = $anobase;
$head6 = "DEMONSTRATIVO DE RISCOS FISCAIS E PROVIDÊNCIAS";
	
  $pdf = new PDF("P", "mm", "A4"); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $pdf->setfont('arial','b',7);
  $sFundamentacao = "AMF -Demonstrativo 7 (LRF, art. 4º,".chr(167)." 2º, inciso V)"; 
  $pdf->cell(165,$alt,$sFundamentacao,'B',0,"L",0);
  $pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);
  //$pdf->cell(100,$alt,"",'RT',0,"C",0);
  //$pdf->cell(90,$alt,"VALOR",'LTB',1,"C",0);
  $pdf->cell(95,$alt,"RISCOS FISCAIS",0,0,"C",0);
  $pdf->cell(95,$alt,"PROVIDÊNCIAS",'L',1,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->setfont('arial','b',7);
  $pdf->cell(70,$alt,"Descrição",'TBR',0,"C",0);
  $pdf->cell(25,$alt,"Valor",'TBR',0,"C",0);
  $pdf->cell(70,$alt,"Descrição",'TBR',0,"C",0);
  $pdf->cell(25,$alt,"Valor",'TB',1,"C",0);
  

$iYInicio = $pdf->getY();  
$iMaxLinha = $iYInicio;
$iYLinha =  $iMaxLinha;  
  //leitura das 3 primeira linhas do array
	$aLinhasRelatorio = array();
	//Variáveis para armazenar o total da coluna da linha
	$totalReceitasCapital_c1 = 0;
	$totalReceitasCapital_c2 = 0;
	$totalReceitasCapital_c3 = 0;
	//Variáveis para controle e armazenar o total das colunas
	$iIndice = 0;		
	$total_c2 = 0;
	$total_c4 = 0;
		
	foreach ($aRelatorio[1]->valoresVariaveis as $oValores){
		
		$total_c2 += $oValores->colunas[1]->o117_valor;  
		$total_c4 += $oValores->colunas[3]->o117_valor;
		$pdf->setfont('arial','',7);
		$aLinhasRelatorio[$iIndice]['coluna'][0] = $oValores->colunas[0]->o117_valor;
		$pdf->setfont('arial','',6);
		$aLinhasRelatorio[$iIndice]['coluna'][1] = $oValores->colunas[1]->o117_valor;
		$pdf->setfont('arial','',7);
		$aLinhasRelatorio[$iIndice]['coluna'][2] = $oValores->colunas[2]->o117_valor;
		$pdf->setfont('arial','',6);
		$aLinhasRelatorio[$iIndice]['coluna'][3] = $oValores->colunas[3]->o117_valor;
		$iIndice++;
		
	}
	//-------------------
  $pdf->SetY($iMaxLinha);
  $iMaxLinha = $pdf->getY();

  for($i=0;$i<count($aLinhasRelatorio);$i++){
  //for($i=0;$i<2;$i++){
  	$iYLinha =  $iMaxLinha;
  	$pdf->SetY($iMaxLinha);
  	$iMaxLinha = $pdf->getY();  
		$pdf->MultiCell(70,$alt,trim($aLinhasRelatorio[$i]["coluna"][0]),'T',"J",0);
		if ($iMaxLinha < $pdf->GetY()) {
	    $iMaxLinha = $pdf->GetY();
	  }
	  $pdf->SetXY(80,$iYLinha);
	  $pdf->Cell(25,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][1],'f'),'TL',0,"R",0);
	  if ($iMaxLinha < $pdf->GetY()) {
	    $iMaxLinha = $pdf->GetY();
	  }
	  $pdf->SetXY(105,$iYLinha);	  
	  $pdf->MultiCell(70,$alt,trim($aLinhasRelatorio[$i]["coluna"][2]),'TL',"L",0);
	  if ($iMaxLinha < $pdf->GetY()) {
	    $iMaxLinha = $pdf->GetY();
	  }
	  $pdf->SetXY(175,$iYLinha);
	  $pdf->Cell(25,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][3],'f'),'TL',1,"R",0);
	  if ($iMaxLinha < $pdf->GetY()) {
    	$iMaxLinha = $pdf->GetY();
	  }
	  
  }
  /*
  for($i=0;$i<count($aLinhasRelatorio);$i++){
		$pdf->Cell(60,$alt,$aLinhasRelatorio[$i]["coluna"][0],'R',0,"L",0);
	  $pdf->Cell(35,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][1],'f'),'LR',0,"R",0);
	  $pdf->Cell(60,$alt,$aLinhasRelatorio[$i]["coluna"][2],'LR',0,"L",0);
	  $pdf->Cell(35,$alt,db_formatar($aLinhasRelatorio[$i]["coluna"][3],'f'),'L',1,"R",0);
  }  
 	*/
  $pdf->SetY($iMaxLinha);
  $pdf->Line(80,$iYInicio,80,$pdf->GetY());
  $pdf->Line(105,$iYInicio,105,$pdf->GetY());
  $pdf->Line(175,$iYInicio,175,$pdf->GetY());
  $pdf->cell(70,$alt,"TOTAL",'TBR',0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_c2,'f'),'TBR',0,"R",0);
  $pdf->cell(70,$alt,"TOTAL",'TBR',0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_c4,'f'),'TB',1,"R",0);
	
  $pdf->ln();
	$oRelatorio->getNotaExplicativa($pdf,1);
	/* 
  $pdf->Ln(5);
  
	assinaturas
  $pdf->setfont('arial','',5);
  $pdf->ln(20);
  
  assinaturas(&$pdf,&$classinatura,'GF');
  */
  $pdf->Output();

?>