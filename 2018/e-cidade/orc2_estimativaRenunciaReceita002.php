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
include_once("model/linhaRelatorioContabil.model.php");
include_once("model/relatorioContabil.model.php");
//$clconrelinfo      = new cl_conrelinfo;
//$clconrelvalor     = new cl_conrelvalor;
//$oOrcParamRelopcre = new cl_orcparamrelopcre;
//$clorcparamelemento = new cl_orcparamelemento();
$oGet  = db_utils::postMemory($_GET);
$xinstit = db_getsession("DB_instit");
$resultinst = db_query("select codigo,munic,nomeinst,nomeinstabrev from db_config where codigo in ($xinstit) ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
$nTotalRcl = 0;
////******************************************************************
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0){
       $descr_inst .= $xvirg.$nomeinstabrev;
       $flag_abrev  = true;
//  }else{
       $descr_inst .= $xvirg.$nomeinst;
  }
//
  $xvirg = ', ';
}
//
if ($flag_abrev == false){

  if (strlen($descr_inst) > 42){
    $descr_inst = substr($descr_inst,0,100);
  }
}
$oRelatorio  = new relatorioContabil(66);

$oLinhaRel   = new linhaRelatorioContabil(66,1);
$aValores    = $oLinhaRel->getValoresColunas(null,null,$xinstit,db_getsession("DB_anousu"));

$nTotalAnoRef = 0;
$nTotalAno1   = 0;
$nTotalAno2   = 0;
$iAnoUsu      = db_getsession("DB_anousu")+1;
$iAnoUsu_1    = $iAnoUsu + 1;
$iAnoUsu_2    = $iAnoUsu + 2;
/*
 * validação da opção ldo ou loa, para imprimir no head3.
 */
if ($oGet->sModelo == 'ldo') {
  $sModelo = 'LEI DE DIRETRIZES ORÇAMENTÁRIAS';
} else {
  $sModelo = 'LEI ORÇAMENTÁRIA ANUAL';
}
$head2 = "MUNICÍPIO DE {$munic}";
$head3 = $sModelo;
$head4 = "ANEXO DE METAS FISCAIS";
$head5 = $iAnoUsu;
$head6 = "ESTIMATIVA E COMPENSAÇÃO DA RENÚNCIA DE RECEITA";

$pdf = new PDF("P", "mm", "A4");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt            = 4;
$pagina         = 1;
$pdf->addpage();
$pdf->setfont('arial','',7);
$pdf->cell(165,$alt,'AMF - Demonstrativo 7(LRF, art.4°,'.chr(167).'2° inciso V)','B',0,"L",0);
$pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);
//$pdf->cell(100,$alt,"",'RT',0,"C",0);
//$pdf->cell(90,$alt,"VALOR",'LTB',1,"C",0);
$pdf->cell(30,$alt,"",0,0,"C",0);
$pdf->cell(30,$alt,"",'L',0,"C",0);
$pdf->cell(30,$alt,"SETORES/",'L',0,"C",0);
$pdf->cell(60,$alt,"",'L',0,"C",0);
$pdf->cell(40,$alt,"",'L',1,"C",0);

$pdf->cell(30,$alt,"TRIBUTO",0,0,"C",0);
$pdf->cell(30,$alt,"MODALIDADE",'L',0,"C",0);
$pdf->cell(30,$alt,"PROGRAMAS/",'L',0,"C",0);
$pdf->cell(60,$alt,"RENÚNCIA DE RECEITA PREVISTA",'LB',0,"C",0);
$pdf->cell(40,$alt,"COMPENSAÇÃO",'L',1,"C",0);

$pdf->cell(30,$alt,"",'B',0,"C",0);
$pdf->cell(30,$alt,"",'BL',0,"C",0);
$pdf->cell(30,$alt,"BENEFICIÁRIO",'LB',0,"C",0);
$pdf->cell(20,$alt,$iAnoUsu,'LB',0,"C",0);
$pdf->cell(20,$alt,$iAnoUsu_1,'LB',0,"C",0);
$pdf->cell(20,$alt,$iAnoUsu_2,'LB',0,"C",0);
$pdf->cell(40,$alt,"",'LB',1,"C",0);
$iYInicio = $pdf->getY();
$iMaxLinha = $iYInicio;
foreach ($aValores as $oValorlinha) {

  $iYLinha =  $iMaxLinha;
  //echo $iYLinha ."---".($pdf->h -50)."<br>";
  if (($iYLinha > $pdf->h -60)) {

    $pdf->Line(40,$iYInicio,40,$iMaxLinha);
    $pdf->Line(70,$iYInicio,70,$iMaxLinha);
    $pdf->Line(100,$iYInicio,100,$iMaxLinha);
    $pdf->Line(120,$iYInicio,120,$iMaxLinha);
    $pdf->Line(140,$iYInicio,140,$iMaxLinha);
    $pdf->Line(160,$iYInicio,160,$iMaxLinha);
    $pdf->Line(10,$iMaxLinha,200,$iMaxLinha);
    $pdf->AddPage();
    $pdf->cell(30,$alt,"",0,0,"C",0);
    $pdf->cell(30,$alt,"",'L',0,"C",0);
    $pdf->cell(30,$alt,"SETORES/",'L',0,"C",0);
    $pdf->cell(60,$alt,"",'L',0,"C",0);
    $pdf->cell(40,$alt,"",'L',1,"C",0);

    $pdf->cell(30,$alt,"TRIBUTO",0,0,"C",0);
    $pdf->cell(30,$alt,"MODALIDADE",'L',0,"C",0);
    $pdf->cell(30,$alt,"PROGRAMAS/",'L',0,"C",0);
    $pdf->cell(60,$alt,"RENÚNCIA DE RECEITA PREVISTA",'LB',0,"C",0);
    $pdf->cell(40,$alt,"COMPENSAÇÃO",'L',1,"C",0);

    $pdf->cell(30,$alt,"",'B',0,"C",0);
    $pdf->cell(30,$alt,"",'BL',0,"C",0);
    $pdf->cell(30,$alt,"BENEFICIÁRIO",'LB',0,"C",0);
    $pdf->cell(20,$alt,$iAnoUsu,'LB',0,"C",0);
    $pdf->cell(20,$alt,$iAnoUsu_1,'LB',0,"C",0);
    $pdf->cell(20,$alt,$iAnoUsu_2,'LB',0,"C",0);
    $pdf->cell(40,$alt,"",'LB',1,"C",0);
    $iMaxLinha = $pdf->getY();
    $iYLinha   = $iMaxLinha;
  }
  $pdf->SetY($iMaxLinha);
  $iMaxLinha = $pdf->getY();
  $pdf->multiCell(30,$alt,trim($oValorlinha->colunas[0]->o117_valor),'T',"J",0);
  if ($iMaxLinha < $pdf->GetY()) {
    $iMaxLinha = $pdf->GetY();
  }
  $pdf->SetXY(40,$iYLinha);
  $pdf->MultiCell(30,$alt,trim($oValorlinha->colunas[1]->o117_valor),'TL',"L",0);
  if ($iMaxLinha < $pdf->GetY()) {
    $iMaxLinha = $pdf->GetY();
  }
  $pdf->SetXY(70,$iYLinha);
  $pdf->MultiCell(30,$alt,trim($oValorlinha->colunas[2]->o117_valor),'LT',"L",0);
  if ($iMaxLinha < $pdf->GetY()) {
    $iMaxLinha = $pdf->GetY();
  }
  $pdf->SetXY(100,$iYLinha);
  $pdf->cell(20,$alt,db_formatar($oValorlinha->colunas[3]->o117_valor,"f"),'LT',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($oValorlinha->colunas[4]->o117_valor,"f"),'LT',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($oValorlinha->colunas[5]->o117_valor,"f"),'LT',0,"R",0);
  $pdf->SetXY(160,$iYLinha);
  $pdf->MultiCell(40,$alt,trim($oValorlinha->colunas[6]->o117_valor),'LT',"J",0);

  if ($iMaxLinha < $pdf->GetY()) {
    $iMaxLinha = $pdf->GetY();
  }

  $nTotalAnoRef +=  $oValorlinha->colunas[3]->o117_valor;
  $nTotalAno1   +=  $oValorlinha->colunas[4]->o117_valor;
  $nTotalAno2   +=  $oValorlinha->colunas[5]->o117_valor;

}
$pdf->SetY($iMaxLinha);
$pdf->Line(40,$iYInicio,40,$pdf->GetY());
$pdf->Line(70,$iYInicio,70,$pdf->GetY());
$pdf->Line(100,$iYInicio,100,$pdf->GetY());
$pdf->Line(120,$iYInicio,120,$pdf->GetY());
$pdf->Line(140,$iYInicio,140,$pdf->GetY());
$pdf->Line(160,$iYInicio,160,$pdf->GetY());
$pdf->cell(90,$alt,"TOTAL",'TBR',0,"L",0);
$pdf->cell(20,$alt,db_formatar($nTotalAnoRef,"f"),'LTB',0,"R",0);
$pdf->cell(20,$alt,db_formatar($nTotalAno1,"f"),'LTB',0,"R",0);
$pdf->cell(20,$alt,db_formatar($nTotalAno2,"f"),'LTB',0,"R",0);
$pdf->cell(40,$alt,"-",'TLB',1,"C",0);

  $pdf->ln();
// ----------------------------------------------------------------
$oRelatorio->getNotaExplicativa($pdf,1);
  $pdf->Ln(5);
//
//  // assinaturas
  $pdf->setfont('arial','',5);
  $pdf->ln(20);
//
//  assinaturas(&$pdf,&$classinatura,'GF');

  $pdf->Output();

?>