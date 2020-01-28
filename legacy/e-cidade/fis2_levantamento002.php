<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("dbforms/db_funcoes.php");

$cllevanta      = new cl_levanta;
$clparfiscal    = new cl_parfiscal;
$cllevvalor     = new cl_levvalor;
$cllevantanotas = new cl_levantanotas;
$cllevinscr     = new cl_levinscr;
$clativprinc    = new cl_ativprinc;
$cllevusu       = new cl_levusu;
$cllevcgm       = new cl_levcgm;

$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");
$clrotulo->label("y63_aliquota");
$cllevantanotas->rotulo->label();

db_postmemory($HTTP_POST_VARS);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;
$alt = 4;

$vtot_bruto   = 0;
$vtot_imposto = 0;
$vtot_pago    = 0;
$vtot_saldo   = 0;

//valor a pagar
$vtot_correcao = 0;
$vtot_multa    = 0;
$vtot_juro     = 0;
$vtot_total    = 0;

$result  = $cllevanta->sql_record($cllevanta->sql_query_file($codlev));
$numrows = $cllevanta->numrows;
if ($numrows>0) {
  db_fieldsmemory($result,0,true);
}
if ($numrows==0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.");
}

$result  = $cllevinscr->sql_record($cllevinscr->sql_query($codlev,"q02_inscr,z01_nome,z01_ender"));
$numrows = $cllevinscr->numrows;
if ($numrows>0) {

  db_fieldsmemory($result,0);

  $result  = $clativprinc->sql_record($clativprinc->sql_query_compl($q02_inscr,"q03_descr"));
  $numrows = $clativprinc->numrows;
  if ($numrows>0) {
    db_fieldsmemory($result,0);
  }
  $head1 = "Inscrição: $q02_inscr";
  $head2 = "Nome: $z01_nome";
  $head3 = "Endereço: $z01_ender";
  $head4 = "Ativ. Princ: $q03_descr";

} else {

  $result  = $cllevcgm->sql_record($cllevcgm->sql_query($codlev,"z01_nome,z01_ender"));
  db_fieldsmemory($result,0);

  $head2 = "Nome: $z01_nome";
  $head3 = "Endereço: $z01_ender";
}

$head5 = "Levantamento fiscal: $codlev Data:$y60_data";
$head6 = "Período: $y60_dtini a $y60_dtfim";

$pdf->addpage("L");

$result01  =  $cllevvalor->sql_record($cllevvalor->sql_query_file(null,"distinct y63_mes,y63_ano","y63_ano asc, y63_mes asc","y63_codlev=$codlev"));
$numrows01 =  $cllevvalor->numrows;

for ($x=0; $x<$numrows01; $x++) {

  db_fieldsmemory($result01,$x);
  $pdf->cell(50,$alt,"Competência: $y63_mes/$y63_ano",1,1,"C",1);

  $sql  = $cllevvalor->sql_query_notas(null,"y79_documento,y79_codigo,y79_data,y79_valor,levvalor.*","","y63_mes=$y63_mes and y63_ano=$y63_ano and y63_codlev =$codlev");
  $result  = $cllevvalor->sql_record($sql);
  $numrows = $cllevvalor->numrows;

  if ($numrows < 1) {
    continue;
  }
  $tot_valor   = 0;
  $tot_imposto = 0;

  for ($i=0; $i<$numrows; $i++) {

    db_fieldsmemory($result,$i);
    if ($y79_codigo=="") {
      $y79_valor = $y63_bruto;
    }

    $imposto = ($y63_aliquota*$y79_valor)/100;
    $pdf->setfont('arial','b',8);
    if ($pdf->gety() > $pdf->h - 30 || $i==0) {

      if ($pdf->gety() > $pdf->h - 30) {
        $pdf->addpage("L");
      }

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
    $pdf->cell(20,$alt,db_formatar($y79_data,"d"),"T",0,"L",0);
    $pdf->cell(20,$alt,db_formatar($y79_valor,"f"),"T",0,"L",0);
    $pdf->cell(20,$alt,"$y63_aliquota","T",0,"L",0);
    $pdf->cell(20,$alt,db_formatar($imposto,"p"),"T",1,"L",0);

    $tot_imposto +=$imposto;
    $tot_valor   +=$y79_valor;
  }

  //correções
  if ($numrows>0) {
    $result66=$clparfiscal->sql_record($clparfiscal->sql_query_file(db_getsession('DB_instit') ,"*"));
    db_fieldsmemory($result66,0);

    $dtoper = date("Y-m-d",db_getsession("DB_datausu"));

    $aData = split("-", $y63_dtvenc);
    $dDtOperData = $aData[0] . "-" . $aData[1] . "-01";

    $result = db_query("select round(fc_corre(".($y60_espontaneo=='t'?$y32_receitexp:$y32_receit).",'".$y63_dtvenc."',".$y63_saldo.",'".$dtoper."',".db_getsession("DB_anousu").",'$y63_dtvenc'),2) as correcao");
    db_fieldsmemory($result,0);

    $result = db_query("select fc_juros(".($y60_espontaneo=='t'?$y32_receitexp:$y32_receit).",'".$y63_dtvenc."','".$dtoper."','".$dDtOperData."','f',".db_getsession("DB_anousu").") as juro");
    db_fieldsmemory($result,0);
    $juro = round($correcao * $juro,2);

    $result = db_query("select fc_multa(".($y60_espontaneo=='t'?$y32_receitexp:$y32_receit).",'".$y63_dtvenc."','".$dtoper."','".$dDtOperData."',".db_getsession("DB_anousu").") as multa");
    db_fieldsmemory($result,0);

    $multa = round($correcao * $multa,2);

    $total = round($correcao + $juro + $multa,2);

  } else {

    $multa     = '0.00';
    $correcao  = '0.00';
    $juro      = '0.00';
    $total     = '0.00';
    $y63_saldo = '0.00';
  }

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

  $vtot_bruto   += $tot_valor;
  $vtot_imposto += $tot_imposto;
  $vtot_pago    += $y63_pago;
  $vtot_saldo   += $y63_saldo;

  //valor a pagar
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
if ($numrows11>0) {

  $pdf->setfont('arial','b',8);
  $pdf->cell(100,$alt,"FISCAIS:",1,1,"L",1);
  $pdf->setfont('arial','',8);

  for ($i=0; $i<$numrows11; $i++) {

    db_fieldsmemory($result11,$i);
    $pdf->cell(100,$alt,$nome,1,1,"L",0);
  }
}
$pdf->Output();