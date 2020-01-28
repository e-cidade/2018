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

include("libs/db_sql.php");
include("fpdf151/scpdf.php");
include("classes/db_db_docparag_classe.php");
include("classes/db_levanta_classe.php");
include("classes/db_levantanotas_classe.php");
include("classes/db_levvalor_classe.php");
include("classes/db_levinscr_classe.php");
include("classes/db_ativprinc_classe.php");
include("classes/db_ativid_classe.php");
include("classes/db_tabativ_classe.php");
include("classes/db_parissqn_classe.php");
include("classes/db_levusu_classe.php");
include("classes/db_cgm_classe.php");
//include("debug.php");
$clativid          = new cl_ativid;
$cltabativ         = new cl_tabativ;
$cllevanta         = new cl_levanta;
$clcgm             = new cl_cgm;
$clparissqn        = new cl_parissqn;
$cllevvalor        = new cl_levvalor;
$cllevantanotas    = new cl_levantanotas;
$cllevinscr        = new cl_levinscr;
$clativprinc       = new cl_ativprinc;
$cllevusu          = new cl_levusu;
$cldb_docparag     = new cl_db_docparag;
$clrotulo          = new rotulocampo;
$cllevantanotas->rotulo->label();
$clrotulo->label();
$clrotulo->label("q02_inscr");
$clrotulo->label("y63_aliquota");
//debug($cllevanta, true);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->Addpage();
/////////////////// CABEÇALHO ///////////////////////////////////

$pdf->SetLeftMargin(10);
//$pdf->SetMargins(10,10,10);
//$pdf->settopmargin(1);
$pdf->SetTextColor(0,0,0);
$pdf->Image('imagens/files/Brasao.png',90,5,35,50);
$pdf->Text(80,10,"PREFEITURA MUNICIPAL DE CHARQUEADAS");

/////////////////////////////////////////////////////////////////
$pdf->setfont('arial','b',8);


//------------------ Busca informações do levantamento ----------------------------------------------------------

$where = "";
$and = "";
if (isset($numcgm) && $numcgm != ""){
  $where .= " $and z01_numcgm = $numcgm ";
  $and = " and ";
}
if (isset($codlev) && $codlev != ""){
  $where .= " $and y60_codlev = $codlev ";
  $and = " and ";
}
if (isset($codinscr) && $codinscr != ""){
  $where .= " $and q02_inscr = $codinscr ";
  $and = " and ";
}
//die($cllevanta->sql_querylev("",'*',"",$where));
$resultlev = $cllevanta->sql_record($cllevanta->sql_querylev("",'*',"","$where"));
$xxnum = pg_numrows($resultlev);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem unidades cadastrados.');
   exit;
}
db_fieldsmemory($resultlev,0);

$dataini = db_formatar($y60_dtini,'d','/');
$datafim = db_formatar($y60_dtfim,'d','/');
$aliquota = $y63_aliquota."%";

//---------------------------------------------------------------------------------------------------------------

$linha=60;
$coluna=10;
$pdf->SetXY($coluna,$linha);
$pdf->SetFont('Arial','b',14);
$pdf->cell(0,10,"AUTO DE LANÇAMENTO",0,1,"C",0);
$pdf->cell(0,10,"",0,1,"R",0);
$pdf->cell(0,10,"",0,1,"R",0);
$linha+=20;
$pdf->SetXY($coluna,$linha);

$pdf->SetFont('Arial','b',12);
$pdf->cell(55,5,"CONTRIBUINTE : ",0,0,"L",0);
$pdf->SetFont('Arial','',12);
$pdf->cell(120,5,$z01_nome,0,1,"L",0);
$pdf->SetFont('Arial','b',12);
$pdf->cell(55,5,"ENDEREÇO : ",0,0,"L",0);
$pdf->SetFont('Arial','',12);
$pdf->cell(120,5,$j14_nome,0,1,"L",0);
$pdf->SetFont('Arial','b',12);
$pdf->cell(55,5,"BAIRRO : ",0,0,"L",0);
$pdf->SetFont('Arial','',12);
$pdf->cell(120,5,$j13_descr,0,1,"L",0);
$pdf->SetFont('Arial','b',12);
$pdf->cell(55,5,"LEVANTAMENTO FISCAL : ",0,0,"L",0);
$pdf->SetFont('Arial','',12);
$pdf->cell(120,5,$y60_codlev,0,1,"L",0);
$pdf->SetFont('Arial','b',12);
$pdf->cell(55,5,"CNPJ/MF : ",0,0,"L",0);
$pdf->SetFont('Arial','',12);
$pdf->cell(120,5,$z01_cgccpf,0,1,"L",0);
$pdf->SetFont('Arial','b',12);
$pdf->cell(55,5,"ATIVIDADE : ",0,0,"L",0);
$pdf->SetFont('Arial','',12);
$pdf->cell(120,5,$q03_descr,0,1,"L",0);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);

$linha+=40;
$pdf->SetXY($coluna,$linha);
$result = $cldb_docparag->sql_record($cldb_docparag->sql_query(33,"","db_docparag.*,db02_texto,db02_espaca,db02_alinha,db02_inicia","db04_ordem"));
$numrows = $cldb_docparag->numrows;
   
for($i=0; $i<$numrows; $i++){
   db_fieldsmemory($result,$i);
   $pdf->SetFont('Arial','',12);
   $pdf->SetX($db02_alinha);
   $texto=db_geratexto($db02_texto);
   $pdf->SetFont('Arial','',12);
   $pdf->MultiCell("0",4+$db02_espaca,$texto,"0","J",0,$db02_inicia+0);
   $pdf->cell(0,6,"",0,1,"R",0);
}


/////////////////////  RELATORIO DE LEVANTAMENTO //////////////////////////////////////////////


$total = 0;
$alt = 4;

$vtot_bruto   = 0;
$vtot_imposto = 0;
$vtot_pago    = 0;
$vtot_saldo   = 0; //valor a pagar
$vtot_correcao = 0;
$vtot_multa    = 0;
$vtot_juro  = 0;
$vtot_total = 0;
$result  = $cllevanta->sql_record($cllevanta->sql_query_file($codlev));
$numrows = $cllevanta->numrows;
if($numrows>0){
  db_fieldsmemory($result,0,true);
}
if($numrows==0){ 
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.");
}

$result  = $cllevinscr->sql_record($cllevinscr->sql_query($codlev,"q02_inscr,z01_nome,z01_ender"));
$numrows = $cllevanta->numrows;
if($numrows>0){
  db_fieldsmemory($result,0);
}

$result  = $clativprinc->sql_record($clativprinc->sql_query_compl($q02_inscr,"q03_descr"));
$numrows = $clativprinc->numrows;
if($numrows>0){
  db_fieldsmemory($result,0);
}

//$pdf->addpage("L");
  
$result01  =  $cllevvalor->sql_record($cllevvalor->sql_query_file(null,"distinct y63_mes,y63_ano","y63_ano","y63_codlev=$codlev")); 
$numrows01 =  $cllevvalor->numrows; 
for($x=0;$x<$numrows01;$x++){
  db_fieldsmemory($result01,$x,true);
  $pdf->cell(50,$alt,"Competência: $y63_mes/$y63_ano",1,1,"C",1);
  $sql  = $cllevvalor->sql_query_notas(null,"y79_documento,y79_codigo,y79_data,y79_valor,levvalor.*","","y63_mes=$y63_mes and y63_ano=$y63_ano and y63_codlev =$codlev");

    
    $result  = $cllevvalor->sql_record($sql);
    $numrows = $cllevvalor->numrows;
    if($numrows<1){
      continue;
    }
    $tot_valor   = 0;
    $tot_imposto = 0;
    for($i=0;$i<$numrows;$i++){
      db_fieldsmemory($result,$i,true);
      if($y79_codigo==""){
	$y79_valor = $y63_bruto;
      }

      
      $imposto = ($y63_aliquota*$y79_valor)/100;
      $pdf->setfont('arial','b',8);
      if($pdf->gety() > $pdf->h - 30 || $i==0){
	if($pdf->gety() > $pdf->h - 30){
	  $pdf->addpage();
	}    
//  $pdf->cell(20,$alt,,1,0,"C",1);
    $pdf->cell(20,$alt,"$RLy79_documento","T",0,"L",0);
    $pdf->cell(20,$alt,"$RLy79_data","T",0,"L",0);
	$pdf->cell(20,$alt,"Valor bruto","T",0,"L",0);
	$pdf->cell(20,$alt,"$RLy63_aliquota","T",0,"L",0);
	$pdf->cell(20,$alt,"Imposto","T",0,"L",0);
	$pdf->cell(20,$alt,"Vencimento","T",0,"L",0);
	$pdf->cell(20,$alt,"Valor pago","T",0,"C",0);
	$pdf->cell(20,$alt,"Valor à pagar","T",0,"C",0);
	$pdf->cell(25,$alt,"Valor corrigido","T",0,"C",0);
	$pdf->cell(20,$alt,"Multa","T",0,"C",0);
	$pdf->cell(20,$alt,"Juros","T",0,"C",0);
	$pdf->cell(20,$alt,"Valor total","T",1,"C",0);
      }
      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,"$y79_documento","T",0,"L",0);
      $pdf->cell(20,$alt,"$y79_data","T",0,"L",0);
      $pdf->cell(20,$alt,"$y79_valor","T",0,"L",0);
      $pdf->cell(20,$alt,"$y63_aliquota","T",0,"L",0);
      $pdf->cell(20,$alt,db_formatar($imposto,"p"),"T",1,"L",0);

      $tot_imposto +=$imposto;
      $tot_valor   +=$y79_valor;
    }

    //correções
    if($numrows>0){ 
	   $result66=$clparissqn->sql_record($clparissqn->sql_query_file(null,"q60_receit as  receit"));
	   db_fieldsmemory($result66,0);


        $dtoper = date("Y-m-d",db_getsession("DB_datausu"));
      
	$result = pg_query("select fc_corre(".$receit.",'".$y63_dtvenc."',".$y63_saldo.",'".$dtoper."',".db_getsession("DB_anousu").",'$y63_dtvenc') as correcao");
	db_fieldsmemory($result,0,true);

	$result = pg_query("select fc_juros(".$receit.",'".$y63_dtvenc."','".$dtoper."','".$y63_dtvenc."','f',".db_getsession("DB_anousu").") as juro");
	db_fieldsmemory($result,0,true);
	$juro = $correcao * $juro;

	$result = pg_query("select fc_multa(".$receit.",'".$y63_dtvenc."','".$dtoper."','".$y63_dtvenc."',".db_getsession("DB_anousu").") as multa");
	db_fieldsmemory($result,0,true);
	
	$multa = $correcao * $multa;

	$total = $correcao + $juro + $multa;

	//$correcao = $correcao - $y63_saldo;
     //-------------------------------------------- 
    
    }else{
      $multa = '0.00';
      $correcao = '0.00'; 
      $juro = '0.00';
      $total='0.00';
      $y63_saldo='0.00';
     }

    //$pdf->cell(260,$alt,"TOTAL DE REGISTROS  : ".$total,"T",1,"L",0);
      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,"","T",0,"C",0);

      $pdf->cell(20,$alt,"","T",0,"C",0);
      $pdf->cell(20,$alt,db_formatar($tot_valor,"p"),"T",0,"C",0);
      $pdf->cell(20,$alt,"","T",0,"C",0);
      $pdf->cell(20,$alt,db_formatar($tot_imposto,"p"),"T",0,"C",0);
      $pdf->cell(20,$alt,$y63_dtvenc,"T",0,"C",0);
      $pdf->cell(20,$alt,db_formatar($y63_pago,"p"),"T",0,"C",0);
      $pdf->cell(20,$alt,$y63_saldo,"T",0,"C",0);
      $pdf->cell(25,$alt,db_formatar($correcao,"p"),"T",0,"C",0);
      $pdf->cell(20,$alt,db_formatar($multa,"p"),"T",0,"C",0);
      $pdf->cell(20,$alt,db_formatar($juro,"p"),"T",0,"C",0);
      $pdf->cell(20,$alt,db_formatar($total,"p"),"T",1,"C",0);

      $vtot_bruto    += $tot_valor;
      $vtot_imposto  += $tot_imposto;
      $vtot_pago     += $y63_pago;
      $vtot_saldo    += $y63_saldo; //valor a pagar
      $vtot_correcao += $correcao;
      $vtot_multa    += $multa;
      $vtot_juro     += $juro;
      $vtot_total    += $total;

}


  $pdf->Ln(5);
  // imprime o total geral
   $pdf->setfont('arial','b',8);
   $pdf->cell(20,$alt,"TOTAL GERAL","T",0,"C",0);
   $pdf->cell(20,$alt,"","T",0,"C",0);
   $pdf->cell(20,$alt,db_formatar($vtot_bruto,"p"),"T",0,"C",0);
   $pdf->cell(20,$alt,"","T",0,"C",0);
   $pdf->cell(20,$alt,db_formatar($vtot_imposto,"p"),"T",0,"C",0);
   $pdf->cell(20,$alt,"","T",0,"C",0);
   $pdf->cell(20,$alt,db_formatar($vtot_pago,"p"),"T",0,"C",0);
   $pdf->cell(20,$alt,db_formatar($vtot_saldo,"p"),"T",0,"C",0);
   $pdf->cell(25,$alt,db_formatar($vtot_correcao,"p"),"T",0,"C",0);
   $pdf->cell(20,$alt,db_formatar($vtot_multa,"p"),"T",0,"C",0);
   $pdf->cell(20,$alt,db_formatar($vtot_juro,"p"),"T",0,"C",0);
   $pdf->cell(20,$alt,db_formatar($vtot_total,"p"),"T",1,"C",0);

   $pdf->ln(3);
 
  $result11  = $cllevusu->sql_record($cllevusu->sql_query($codlev,null,"nome"));
  $numrows11 = $cllevusu->numrows;
  if($numrows11>0){
    $pdf->setfont('arial','b',8);
    $pdf->cell(100,$alt,"FISCAIS:",1,1,"L",1);
    $pdf->setfont('arial','',8);
    
    for($i=0; $i<$numrows11; $i++){
      db_fieldsmemory($result11,$i);	
      $pdf->cell(100,$alt,$nome,1,1,"L",0);
    }
  }

////////////////// FIM RELATORIO /////////////////////////////////////////////////////////////////////////////

$pdf->cell(0,10,"",0,1,"R",0);
$pdf->SetFont('Arial','b',12);
$pdf->cell(90,4,"___________________________",0,0,"C",0);
$pdf->cell(90,4,"___________________________",0,1,"C",0);
$pdf->cell(90,4,"Fiscal",0,0,"C",0);
$pdf->cell(90,4,"Autuado",0,1,"C",0);
$pdf->cell(0,10,"",0,1,"R",0);
$pdf->cell(0,4,"Data:___/___/_____ ",0,1,"R",0);
$pdf->Output();
?>