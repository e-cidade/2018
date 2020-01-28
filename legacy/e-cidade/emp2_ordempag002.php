<?
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_pagordem_classe.php");

$clpagordem = new cl_pagordem;

$clrotulo = new rotulocampo;
$clrotulo->label('e50_codord');
$clrotulo->label('e50_data');
$clrotulo->label('e50_obs');
$clrotulo->label('e50_numemp');
$clrotulo->label('e53_valor');
$clrotulo->label('e60_numcgm');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$ordem = "e50_codord";
$where = "1=1";
$where1 = "";
$where2 = "";
if (($data!="--")&&($data1!="--")) {
  $where ="    e50_data  between '$data' and '$data1'  ";
}else if ($data!="--"){
  $where="    e50_data >= '$data'  ";
}else if ($data1!="--"){
  $where="    e50_data <= '$data1'   ";
}


if (($codini!="")&&($codfim!="")) {
  $where1=" and  e50_codord between $codini and $codfim  ";
}else if ($codini!=""){
  $where1=" and  e50_codord >= $codini  ";
}else if ($codfim!=""){
  $where1=" and  e50_codord <= $codfim   ";
}



if (($numempini!="")&&($numempfim!="")) {
  $where2=" and  e50_numemp  between $numempini and $numempfim  ";
}else if ($numempini!=""){
  $where2=" and  e50_numemp >= $numempini  ";
}else if ($numempfim!=""){
  $where2=" and  e50_numemp <= $numempfim   ";
}

$head5 = "ORDEM por cod. da Ordem";
if ((isset($numempini)||isset($numempfim))&&(!isset($codini)&&!isset($codfim))){
  $ordem = "e50_numemp";
  $head5 = "ORDEM por n° do empenho";
}



$head3 = "ORDENS DE PAGAMENTO";


//die($clpagordem->sql_query_pagordemele(null,"e50_codord,e50_numemp,e60_numcgm,e50_obs,e50_data,z01_nome,sum(e53_valor)","e50_codord","  $where $where1 $where2 group by e50_codord,e50_numemp,e60_numcgm,e50_obs,e50_data,z01_nome"));
$result=$clpagordem->sql_record($clpagordem->sql_query_pagordemele(null,"e50_codord,e50_numemp,e60_numcgm,e50_obs,e50_data,z01_nome,sum(e53_valor-e53_vlranu) as e53_valor ","$ordem"," e60_instit = " . db_getsession("DB_instit") .  " and $where $where1 $where2 group by e50_codord,e50_numemp,e60_numcgm,e50_obs,e50_data,z01_nome"));
if($clpagordem->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Ordem de pagamento não Encontrada.');
  exit;
}







$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$prenc = 0;
$alt = 4;
$total = 0;
$totalvalor = 0;

for($x = 0; $x < pg_numrows($result);$x++){
  db_fieldsmemory($result,$x,true);

  if ($e53_valor == 0) continue;

  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage("L");
    $pdf->setfont('arial','b',8);
    $pdf->cell(20,$alt,$RLe50_codord,1,0,"C",1);
    $pdf->cell(20,$alt,$RLe50_numemp,1,0,"C",1);
    $pdf->cell(20,$alt,$RLe60_numcgm,1,0,"C",1);
    $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
    $pdf->cell(20,$alt,$RLe50_data,1,0,"C",1);
    $pdf->cell(20,$alt,$RLe53_valor,1,0,"C",1);
    $pdf->cell(100,$alt,$RLe50_obs,1,1,"C",1);

    $troca = 0;
    $prenc = 1;
  }
  if ($prenc == 0){
    $prenc = 1;
  }else $prenc = 0;

  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$e50_codord,0,0,"C",$prenc);
  $pdf->cell(20,$alt,$e50_numemp,0,0,"C",$prenc);
  $pdf->cell(20,$alt,$e60_numcgm,0,0,"C",$prenc);
  $pdf->cell(60,$alt,$z01_nome,0,0,"L",$prenc);
  $pdf->cell(20,$alt,$e50_data,0,0,"C",$prenc);
  $pdf->cell(20,$alt,$e53_valor,0,0,"R",$prenc);
  $pdf->multicell(100,$alt,$e50_obs,0,"L",$prenc);

  //     if ($prenc == 0){
  //        $prenc = 1;
  //       }else $prenc = 0;
  $total++;
  $totalvalor += $e53_valor;

}

$pdf->setfont('arial','b',8);

$pdf->cell(140,$alt,'TOTAL DE ORDENS DE PAGAMENTO  :  '.$total,"T",0,"L",0);
$pdf->cell(80,$alt,db_formatar($totalvalor,'f'),"T",0,"L",0);

$pdf->Output();

?>