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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_solicitem_classe.php");
include("libs/db_liborcamento.php");
$clsolicitem = new cl_solicitem;
$clrotulo = new rotulocampo;
$clrotulo->label('pc11_numero');
$clrotulo->label('pc11_codigo');
$clrotulo->label('pc13_coddot');
$clrotulo->label('pc13_anousu');
$clrotulo->label('pc01_codmater');
$clrotulo->label('pc01_descrmater');
$clrotulo->label('pc13_valor');
$clrotulo->label('o80_valor');
$clrotulo->label('o56_codele');
$clrotulo->label('o56_elemento');
$clrotulo->label('o56_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$dbwhere = "";
if(trim($pc10_numero) != ""){
  $dbwhere .= " pc10_numero=".$pc10_numero." and ";
  $head5 = "Solicitação: ".$pc10_numero;
}
$head6 = "Valores a suplementar sem pesquisar valores orçados por fornecedores";
if(isset($comorcam)){
  if($comorcam == 's'){
    $head6 = "Valores a suplementar pesquisando orçamentos de solicitação";
  }else{
    $head6 = "Valores a suplementar pesquisando orçamentos de processo de compras";
  }
}

if(!isset($comorcam)){
  $dbwhere.= "
              case when o82_codres is not null then o80_codres is not null else o80_codres is null end
              and pc13_valor-(case when o80_codres is not null then o80_valor else 0 end)>0
              and ((e54_autori is not null and e54_anulad is not null) or e54_autori is null)
	      and o56_anousu = ".db_getsession("DB_anousu");

  $result_solicitem = $clsolicitem->sql_record($clsolicitem->sql_query_valsuplem(
       null,
       "distinct 
        pc11_numero,
        pc11_codigo,
        pc13_coddot,
        pc13_anousu,
        o56_codele,
        o56_elemento,
        o56_descr,
        pc01_codmater,
        pc01_descrmater,
        pc13_valor,
        case when o80_codres is not null then o80_valor else 0 end as o80_valor,
        pc13_valor-(case when o80_codres is not null then o80_valor else 0 end) as suplementar",
        "pc11_numero,pc11_codigo,pc13_coddot,pc01_descrmater",
        $dbwhere));
}else if($comorcam == "s"){
  $dbwhere.= "
              case when o82_codres is not null then o80_codres is not null else o80_codres is null end
              and g.pc23_valor-(case when o80_codres is not null then o80_valor else 0 end)>0
              and ((e54_autori is not null and e54_anulad is not null) or e54_autori is null) 
	      and e.pc24_pontuacao = 1
	      and o56_anousu = ".db_getsession("DB_anousu");

  $result_solicitem = $clsolicitem->sql_record($clsolicitem->sql_query_valsuplemorcam(
       null,
       "distinct
        pc11_numero,
        pc11_codigo,
        pc13_coddot,
        pc13_anousu,
        o56_codele,
        o56_elemento,
        o56_descr,
        pc01_codmater,
        pc01_descrmater,
        g.pc23_valor as pc13_valor,
        case when o80_codres is not null then o80_valor else 0 end as o80_valor,
        g.pc23_valor-(case when o80_codres is not null then o80_valor else 0 end) as suplementar",
        "pc11_numero,pc11_codigo,pc13_coddot,pc01_descrmater",
        $dbwhere));
}else if($comorcam == "p"){
  $dbwhere.= "
              case when o82_codres is not null then o80_codres is not null else o80_codres is null end
              and h.pc23_valor-(case when o80_codres is not null then o80_valor else 0 end)>0
              and ((e54_autori is not null and e54_anulad is not null) or e54_autori is null)
	      and f.pc24_pontuacao = 1
	      and o56_anousu = ".db_getsession("DB_anousu");

  $result_solicitem = $clsolicitem->sql_record($clsolicitem->sql_query_valsuplemorcam(
       null,
       "distinct
        pc11_numero,
        pc11_codigo,
        pc13_coddot,
        pc13_anousu,
        o56_codele,
        o56_elemento,
        o56_descr,
        pc01_codmater,
        pc01_descrmater,
        h.pc23_valor as pc13_valor,
        case when o80_codres is not null then o80_valor else 0 end as o80_valor,
        h.pc23_valor-(case when o80_codres is not null then o80_valor else 0 end) as suplementar",
        "pc11_numero,pc11_codigo,pc13_coddot,pc01_descrmater",
        $dbwhere));
}

if ($clsolicitem->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existe valor a suplementar.');
}
 
$head3 = "RELATÓRIO DE VALORES A SUPLEMENTAR";

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;

$dot = 0;
$res = 0;
$sup = 0;

$contagem = 0;

$arr_dotacoes_saldo = Array();

for($i = 0; $i < $clsolicitem->numrows; $i++){
   db_fieldsmemory($result_solicitem,$i);

	 $result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=".$pc13_coddot, db_getsession("DB_anousu"));
   db_fieldsmemory($result, 0);

   $arr_dotacoes_saldo[$pc13_coddot] = $atual_menos_reservado;
   $atual_menos_reservado += $o80_valor;
   $suplementar = $pc13_valor - $atual_menos_reservado;
   if($suplementar <= 0){
 	   continue;
   }

   $contagem++;

   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(13,$alt,"Solicit.",1,0,"C",1);
      $pdf->cell(13,$alt,"Item sol.",1,0,"C",1);
      $pdf->cell(13,$alt,"Dotação",1,0,"C",1);
      $pdf->cell(15,$alt,"Código ele.",1,0,"C",1);
      $pdf->cell(30,$alt,$RLo56_elemento,1,0,"C",1);
      $pdf->cell(60,$alt,$RLo56_descr,1,0,"C",1);
      $pdf->cell(15,$alt,$RLpc01_codmater,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc01_descrmater,1,0,"C",1);
      $pdf->cell(20,$alt,$RLpc13_valor,1,0,"C",1);
      $pdf->cell(20,$alt,"Reservado",1,0,"C",1);
      $pdf->cell(20,$alt,"Suplementar",1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',6);
   $pdf->cell(13,$alt,$pc11_numero,1,0,"C",0);
   $pdf->cell(13,$alt,$pc11_codigo,1,0,"C",0);
   $pdf->cell(13,$alt,$pc13_coddot,1,0,"C",0);
   $pdf->cell(15,$alt,$o56_codele,1,0,"C",0);
   $pdf->cell(30,$alt,$o56_elemento,1,0,"C",0);
   $pdf->cell(60,$alt,$o56_descr,1,0,"L",0);
   $pdf->cell(15,$alt,$pc01_codmater,1,0,"C",0);
   $pdf->cell(60,$alt,$pc01_descrmater,1,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($pc13_valor,"f"),1,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($o80_valor,"f"),1,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($suplementar,"f"),1,1,"R",0);
   $total++;
   $dot += $pc13_valor;
   $res += $o80_valor;
   $sup += $suplementar;
}

if($contagem == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe valor a suplementar.');
}

$pdf->setfont('arial','b',8);
$pdf->cell(84,$alt,'',"T",0,"R",0);
$pdf->cell(75,$alt,'TOTAIS',"T",0,"R",0);
$pdf->cell(60,$alt,$total." registro(s)","T",0,"R",0);
$pdf->cell(20,$alt,db_formatar($dot,"f"),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($res,"f"),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($sup,"f"),0,1,"R",0);

$total = 0;
$troca = 1;
reset($arr_dotacoes_saldo);
for($i=0; $i<count($arr_dotacoes_saldo); $i++){
	$dotacao = key($arr_dotacoes_saldo);
	$saldodt = $arr_dotacoes_saldo[$dotacao];

  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
  	 if($pdf->gety() > $pdf->h - 30){
       $pdf->addpage("L");
  	 }
     $pdf->setfont('arial','b',8);
     $pdf->cell(20,$alt,"Dotações",1,0,"C",1);
     $pdf->cell(30,$alt,"Disponível",1,1,"C",1);
     $troca = 0;
  }
  $pdf->setfont('arial','',6);
  $pdf->cell(20,$alt,$dotacao,1,0,"C",0);
  $pdf->cell(30,$alt,db_formatar($saldodt,"f"),1,1,"R",0);
  $total++;

	next($arr_dotacoes_saldo);
}
$pdf->setfont('arial','b',8);
$pdf->cell(50,$alt,'DOTAÇÕES ENCONTRADAS '.$total,"T",0,"L",0);

$pdf->Output();   
?>