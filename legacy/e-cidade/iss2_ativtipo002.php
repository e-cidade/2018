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

require_once (modification("fpdf151/pdf.php"));
require_once (modification("libs/db_sql.php"));
require_once (modification("classes/db_ativtipo_classe.php"));
require_once (modification("classes/db_ativid_classe.php"));
require_once (modification("classes/db_tipcalc_classe.php"));
$clativtipo = new cl_ativtipo;
$clativid   = new cl_ativid;
$cltipcalc  = new cl_tipcalc;
$clrotulo   = new rotulocampo;
$clativtipo->rotulo->label();
$clrotulo->label("q03_ativ"); //classificação
$clrotulo->label("q03_descr"); //classificação
db_postmemory($_POST);

//db_postmemory($HTTP_SERVER_VARS,2);exit;
$msg="";
$param="";
$param2="";
$passou=false;
if(isset($order)){
  if($order=="num"){
    $order="q03_ativ";
  }else if($order=="alf"){
    $order="q03_descr";
  }
}
if(isset($lista) && trim($lista)!=""){
  $passou=true;
  if($param_where=="S"){
    $msg=" com o(s) código(s) a seguir ($lista)";
    $param = " in ";
  }else if($param_where=="N"){
    $msg=" sem o(s) código(s) a seguir ($lista)";
    $param = " not in ";
  }
//  die($clativtipo->sql_query_file(null,null," distinct q80_tipcal","q80_tipcal"," q80_tipcal $param ($lista)"));
  $result = $clativtipo->sql_record($clativtipo->sql_query_file(null,null," distinct q80_tipcal","q80_tipcal"," q80_tipcal $param ($lista)"));
}else{
  $result = $clativtipo->sql_record($clativtipo->sql_query_file(null,null," distinct q80_tipcal","q80_tipcal"));
}
$numrows=$clativtipo->numrows;
if($numrows==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum tipo de cálculo encontrado$msg.");
}
$pdf = new PDF();
$pdf->Open();
$head5 = "TIPO DE CÁLCULO POR ATIVIDADE";
$pdf->addpage();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$alt = 4;
$lig2="";
$novo_CS="";
for($i = 0; $i<$numrows; $i++) {
  if($i!=0){
    $pdf->ln(10);
  }
  db_fieldsmemory($result,$i);
  $result_tipo = $cltipcalc->sql_record($cltipcalc->sql_query_file($q80_tipcal,"q81_abrev"));
  db_fieldsmemory($result_tipo,0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,"Código: $q80_tipcal", "LTB",0,"L",1);
  $pdf->cell(160,$alt,"Descrição: $q81_abrev", "RTB",1,"L",1);

  $sSql = $clativid->sql_query_file(null,"q03_ativ,q03_descr","$order","q03_ativ in (select q80_ativ from ativtipo where q80_tipcal=$q80_tipcal)");
  $result_dados = $clativid->sql_record($sSql);
  $numrows2=$clativid->numrows;
  $c=1;
  $total=0;
  for($ii = 0; $ii<$numrows2; $ii++) {

    if($c==1){
      $c=0;
    }else{
      $c=1;
    }
    db_fieldsmemory($result_dados,$ii);
    if($pdf->gety() > $pdf->h - 30) {

      $pdf->addpage();
      $pdf->cell(15,$alt,"Código: $q80_tipcal", "LTB",0,"L",1);
      $pdf->cell(175,$alt,"Descrição: $q81_abrev", "RTB",1,"L",1);
      $c=0;
    }
    $pdf->setfont('arial','',7);
    $pdf->cell(15,$alt,$q03_ativ,   "L",0,"C",$c);
    $pdf->cell(175,$alt,$q03_descr,  "R",1,"L",$c);
    $total++;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(190,$alt,"TOTAL DE ATIVIDADES  :  ".$total,"T",1,"L",0);
  if($param!=""){
    $param2=" in ";
  }
}
  $pdf->ln(10);
  if((($param_where=="S") || ($param_where=="N")) && $passou==true){
    if($param_where=="S"){
      $pdf->cell(190,$alt,"TIPOS SELECIONADOS",  1,1,"L",1);
    }else if($param_where=="N"){
      $pdf->cell(190,$alt,"TIPOS NÃO SELECIONADOS",  1,1,"L",1);
    }
    $result_tipo2 = $cltipcalc->sql_record($cltipcalc->sql_query_file(null,"q81_codigo,q81_abrev","q81_codigo"," q81_codigo $param2 ($lista)"));
    $numrows_tipo=$cltipcalc->numrows;
    for($i=0;$i<$numrows_tipo;$i++){
      db_fieldsmemory($result_tipo2,$i);
      $pdf->setfont('arial','',7);
      $pdf->cell(30,$alt,"Código: $q81_codigo", "L",0,"L",0);
      $pdf->cell(160,$alt,"Descrição: $q81_abrev", "R",1,"L",0);
    }
    $pdf->cell(190,0.2,"", "T",1,"L",1);
  }else{
    $pdf->cell(190,$alt,"TODOS OS TIPOS SELECIONADOS",  0,1,"L",0);
  }
$pdf->Output();
?>