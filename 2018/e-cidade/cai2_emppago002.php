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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("classes/db_coremp_classe.php");
require_once("classes/db_pagordemnota_classe.php");

$clcoremp = new cl_coremp;
$clpagordemnota = new cl_pagordemnota;

$clrotulo = new rotulocampo;
$clrotulo->label('e50_codord ');
$clrotulo->label('e60_numemp ');
$clrotulo->label('e60_codemp ');
$clrotulo->label('e60_numcgm ');
$clrotulo->label('z01_nome   ');
$clrotulo->label('e60_vlremp ');
$clrotulo->label('k12_valor  ');
$clrotulo->label('k12_cheque ');
$clrotulo->label('e60_anousu ');
$clrotulo->label('k12_empen');
$clrotulo->label('k12_data');
$clrotulo->label('k12_autent');
$clrotulo->label('k13_conta');
$clrotulo->label('k13_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem = 'e'){
  if($quebra == 's'){
    $order_by = "order by k13_conta,tipo,k12_data, e60_codemp ";
  }else{
    $order_by = "order by tipo,k12_data, e60_codemp ";
  }
}else{
  if($quebra == 's'){
    $order_by = "order by k13_conta,tipo,k12_data, k12_autent ";
  }else{
    $order_by = "order by tipo, k12_data , k12_autent";
  }
}


$head3 = "EMPENHOS PAGOS ";


$where3 = "";
$where = "where 1=1";


if ($cod!="") {
   $where3 = " and  k13_conta = $cod";
}

$result1 = db_query("select max(coremp.k12_data) as maior, min(coremp.k12_data) as menor from coremp
			   inner join corrente on corrente.k12_id = coremp.k12_id and corrente.k12_data=coremp.k12_data  and corrente.k12_autent= coremp.k12_autent and k12_instit = " . db_getsession("DB_instit") . "
			   inner join saltes on saltes.k13_conta = corrente.k12_conta $where $where3 ");

db_fieldsmemory($result1,0,true);

if (($data!="--")&&($data1!="--")) {
    $where ="where coremp.k12_data  between '$data' and '$data1'   ";
    $data = db_formatar($data,'d');
    $data1 = db_formatar($data1,'d');
    $head5 = "Ordem de $data ate $data1";
    }else if ($data!="--"){
	$where="where coremp.k12_data >= '$data'     ";
        $data = db_formatar($data,'d');
	$head5 = "Ordem apartir de $data  ";
	}else if ($data1!="--"){
	   $where="where coremp.k12_data <= '$data1'      ";
           $data1 = db_formatar($data1,'d');
	   $head5 = "Ordem ate $data1 ";
	   }else $head5 = "Ordem de $menor ate $maior";

if ($z01_numcgm!="") {
   $where3 .= " and  e60_numcgm = $z01_numcgm";
}


$anousu = db_getsession("DB_anousu");

$sql="select * from (
	select      coremp.k12_empen,
                           e60_numemp,
			   e60_codemp,
               case  when e49_numcgm is null then e60_numcgm else e49_numcgm end as e60_numcgm,
			   k12_codord as e50_codord,
			   case  when e49_numcgm is null then cgm.z01_nome else cgmordem.z01_nome end as z01_nome,
		           k12_valor,
		           k12_cheque,
		           e60_anousu,
			   coremp.k12_autent,
			   coremp.k12_data,
			   k13_conta,
			   k13_descr,
			   case when e60_anousu < $anousu then 'RP' else 'Emp' end as tipo
		    from coremp
               inner join empempenho on e60_numemp = k12_empen and e60_instit = " . db_getsession("DB_instit") . "
               inner join pagordem   on e50_codord = k12_codord
               left  join pagordemconta   on e50_codord = e49_codord
			   inner join corrente on corrente.k12_id     = coremp.k12_id and corrente.k12_data=coremp.k12_data  and corrente.k12_autent= coremp.k12_autent
			   inner join cgm on cgm.z01_numcgm           = e60_numcgm
			   left  join cgm cgmordem on cgmordem.z01_numcgm = e49_numcgm
			   inner join saltes on saltes.k13_conta      = corrente.k12_conta
                    $where $where3
		    $order_by) as xxxxx
where " . ($filtraemp == 0?"1 = 1":" tipo = '" . ($filtraemp == 1?"Emp":"RP") . "'"  ) . "
";


$result = db_query($sql);
//db_criatabela($result);
if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem empenhos pagos.');

}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$tipo = "";
//echo db_getsession();
$soma = 0;
db_fieldsmemory($result,0);
$xtipo = $tipo;
$banco = $k13_conta;
$tot_valor = 0;
$tot_geral = 0;
$tot_banco = 0;
$geral     = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x,true);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,'Data Autent',1,0,"C",1);
      $pdf->cell(15,$alt,'Cod.Aut.',1,0,"C",1);
      $pdf->cell(15,$alt,'Cod.Con.',1,0,"C",1);
      $pdf->cell(40,$alt,$RLk13_descr,1,0,"C",1);
      $pdf->cell(18,$alt,$RLe60_numemp,1,0,"C",1);
      $pdf->cell(15,$alt,$RLe60_codemp,1,0,"C",1);
      $pdf->cell(15,$alt,$RLe50_codord,1,0,"C",1);
      $pdf->cell(28,$alt,'Notas Fiscais',1,0,"C",1);
      $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(20,$alt,'Vlr Autent',1,0,"C",1);
      $pdf->cell(20,$alt,'N° Cheque',1,0,"C",1);
      $pdf->cell(15,$alt,'Tipo',1,1,"C",1);
      $troca = 0;
      $iYlinha = $pdf->getY();
   }
//   if ($anousu==$e60_anousu){
//     $tipo="Emp";
//     }else if ($anousu>$e60_anousu ){
//       $tipo="RP";
//     }else $tipo="";

   if($xtipo != $tipo){
     $pdf->cell(280,$alt,'TOTAL DE REGISTROS  :  '.$total."   VALOR TOTAL  :R$ ".db_formatar($soma,"f"),"T",1,"L",0);
     $pdf->ln(3);
     $total = 0;
     $soma  = 0;
     $xtipo = $tipo;
   }
   if($banco != $k13_conta && $quebra == 's'){
     $pdf->setfont('arial','B',9);
     $pdf->cell(280,$alt,"TOTAL DO BANCO  :  R$ ".db_formatar($tot_banco,"f"),"T",1,"L",1);
     $pdf->ln(5);
     $tot_banco = 0;
     $banco = $k13_conta;
     $soma  = 0;
     $xtipo = $tipo;
   }
   $notas    = "";
   $sepnotas = "";
   $pdf->setfont('arial','',7);
   //$pdf->sety($iYlinha);
   $pdf->cell(20,$alt,$k12_data,0,0,"C",0);
   $pdf->cell(15,$alt,$k12_autent,0,0,"C",0);
   $pdf->cell(15,$alt,$k13_conta,0,0,"C",0);
   $pdf->cell(40,$alt,substr($k13_descr,0,25),0,0,"L",0);
   $pdf->cell(18,$alt,$k12_empen,0,0,"C",0);
   $pdf->cell(15,$alt,trim($e60_codemp).'/'.$e60_anousu,0,0,"C",0);
   $pdf->cell(15,$alt,$e50_codord,0,0,"C",0);
   if( $e50_codord > 0 ){
     $res = $clpagordemnota->sql_record($clpagordemnota->sql_query($e50_codord,null,'e69_numero'));
     if( $clpagordemnota->numrows > 0){
       $notas = "";
       for( $ord = 0; $ord < $clpagordemnota->numrows; $ord ++){
       	 db_fieldsmemory($res,$ord);
       	 $notas .= $sepnotas.trim($e69_numero);
       	 $sepnotas = " - ";
       }
     }
   }
   $iYold = $pdf->getY();
   $iXold = $pdf->getx();
   $pdf->multicell(28,3,$notas,0,"L",0);
   $iYlinha = $pdf->gety();
   $pdf->setxy($iXold+28,$iYold);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(20,$alt,$k12_valor,0,0,"R",0);
   $pdf->cell(20,$alt,$k12_cheque,0,0,"R",0);
   $pdf->cell(15,$alt,$tipo,0,1,"C",0);
   $pdf->sety($iYlinha+1);
   $soma += $k12_valor+0;
   $total++;
   $tot_banco += $k12_valor;
   $tot_valor += $k12_valor;
   $geral++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(280,$alt,'TOTAL DE REGISTROS  :  '.$total."   VALOR TOTAL  :R$ ".db_formatar($soma,"f"),"T",1,"L",0);
$pdf->ln(3);
   if($quebra == 's'){
     $pdf->setfont('arial','B',9);
     $pdf->cell(280,$alt,"TOTAL DO BANCO  :  R$ ".db_formatar($tot_banco,"f"),"T",1,"L",1);
     $pdf->ln(5);
     $tot_banco = 0;
     $banco = $k13_conta;
     $soma  = 0;
     $xtipo = $tipo;
   }
$pdf->cell(280,$alt,'TOTAL DE GERAL      :  '.$geral."   VALOR GERAL  :R$ ".db_formatar($tot_valor,"f"),"T",0,"L",0);

$pdf->Output();

?>