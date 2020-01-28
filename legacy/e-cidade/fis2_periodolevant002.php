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

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_levanta_classe.php"));
db_postmemory($HTTP_POST_VARS);
$cllevanta      = new cl_levanta;

$sqlreceit = "select y32_receit,y32_receitexp from parfiscal";
$result_receit = db_query($sqlreceit);
db_fieldsmemory($result_receit,0);
$anousu = db_getsession("DB_anousu");

// nao pode ustilizar data do php e sim da db_getsession PAULO
//$dtoper = date("Y-m-d");

$dtoper = date("Y-m-d",db_getsession("DB_datausu"));
//echo ($dtoper); exit;
$sql = "  select  y60_codlev,
                  q02_inscr,
                  case when c.z01_numcgm is null then cgm.z01_nome else c.z01_nome end as nome,
		  y60_dtini as dataini,
		  y60_dtfim as datafim,
		  y60_data as data,
		  sum(y63_bruto) as y63_bruto,
		  sum(liquido) as imposto,
		  sum(y63_pago) as y63_pago,
		  sum(liquido) - sum(y63_pago) as apagar,
		  sum(round(vlr_corrigido,2)) as corrigido,
		  sum(round(juros * vlr_corrigido,2)) as juros,
      sum(round(multa * vlr_corrigido,2)) as multa
	   from(  select  y60_codlev,
                          y63_bruto,
			  y60_dtini,
			  y60_dtfim,
	                  y63_bruto * y63_aliquota / 100 as liquido,
	                  y63_pago,
			  y60_data,
	                  round(fc_corre(case when y60_espontaneo then $y32_receitexp else $y32_receit end,y63_dtvenc,y63_saldo,'$dtoper',$anousu,y63_dtvenc),2) as vlr_corrigido,
	                  round(fc_juros(case when y60_espontaneo then $y32_receitexp else $y32_receit end,y63_dtvenc,'$dtoper',y63_dtvenc,'f',$anousu),2) as juros,
	                  round(fc_multa(case when y60_espontaneo then $y32_receitexp else $y32_receit end,y63_dtvenc,'$dtoper',y63_dtvenc,$anousu),2) as multa
	          from levanta
	               inner join levvalor on y60_codlev = y63_codlev
	               where y60_data between '$data1' and  '$data2') as x

	               left join levinscr on y62_codlev = y60_codlev
	               left join issbase on q02_inscr = y62_inscr
	               left join cgm on q02_numcgm = cgm.z01_numcgm
                   left join levcgm on y93_codlev = y60_codlev
	               left join cgm c on y93_numcgm = c.z01_numcgm

	    group by y60_codlev,q02_inscr,nome,dataini,datafim,data";

//die($sql);
$result  = $cllevanta->sql_record($sql);
$numrows = $cllevanta->numrows;
if($numrows>0){
  db_fieldsmemory($result,0,true);
}
if($numrows==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.");
}

//db_criatabela($result);
//exit;
$head1 = "Relatório dos Levantamentos por período";
$head6 = "Período: $data1 a $data2";
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$alt = 4;
$pri = true;

//Zerando Valores

 $vtot_bruto   = 0;
 $vtot_imposto = 0;
 $vtot_pago    = 0;
 $vtot_saldo   = 0;
 $vtot_correcao= 0;
 $vtot_multa   = 0;
 $vtot_juros   = 0;
 $vtot_total   = 0;
 $vtot_apagar  = 0;



for ($i = 0;$i < $numrows;$i++){
 db_fieldsmemory($result,$i);

 if($i%2){
   $cor = 1;
 }else $cor = 0;

  //cabeçalho
  if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
      $pdf->addpage("L");
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',6);
      $pdf->cell(14,4,"N° Levant.",1,0,"L",1);
      $pdf->cell(14,4,"Inscrição",1,0,"C",1);
      $pdf->cell(65,4,"Nome/Razão Social",1,0,"L",1);
      $pdf->cell(20,4,"Data",1,0,"C",1);
      $pdf->cell(26,4,"Período",1,0,"C",1);
      $pdf->cell(20,4,"VLR Bruto",1,0,"C",1);
      $pdf->cell(20,4,"Imposto",1,0,"C",1);
      $pdf->cell(20,4,"VLR Pago",1,0,"C",1);
      $pdf->cell(20,4,"VLR à Pagar",1,0,"C",1);
      $pdf->cell(20,4,"VLR Corrigido",1,0,"C",1);
      $pdf->cell(14,4,"Multa",1,0,"C",1);
      $pdf->cell(14,4,"Juros",1,0,"C",1);
      $pdf->cell(15,4,"Total",1,1,"C",1);
      $pri = false;
  }
      $total         = 0;
      $vtot_bruto   += $y63_bruto;
      $vtot_imposto += $imposto;
      $vtot_pago    += $y63_pago;
      $vtot_apagar  += $apagar;
      $vtot_correcao+= $corrigido;
      $vtot_multa   += $multa;
      $vtot_juros   += $juros;
      $total         = round($corrigido + $multa + $juros,2);
      $vtot_total   += $total;

      $dataini   = db_formatar($dataini,"d");
      $datafim   = db_formatar($datafim,"d");
      $data      = db_formatar($data,"d");
      $bruto     = db_formatar($y63_bruto,"f");
      $imposto   = db_formatar($imposto,"f");
      $pago      = db_formatar($y63_pago,"f");
      $apagar    = db_formatar($apagar,"f");
      $corrigido = db_formatar($corrigido,"f");
      $juros     = db_formatar($juros,"f");
      $multa     = db_formatar($multa,"f");
      $total     = db_formatar($total,"f");

      $pdf->setfont('arial','',6);
      $pdf->cell(14,4,"$y60_codlev",1,0,"L",$cor);
      $pdf->cell(14,4,"$q02_inscr",1,0,"C",$cor);
      $pdf->cell(65,4,"$nome",1,0,"L",$cor);
      $pdf->cell(20,4,"$data",1,0,"C",$cor);
      $pdf->cell(26,4,"$dataini A $datafim",1,0,"C",$cor);
      $pdf->cell(20,4,"$bruto",1,0,"R",$cor);
      $pdf->cell(20,4,"$imposto",1,0,"R",$cor);
      $pdf->cell(20,4,"$pago",1,0,"R",$cor);
      $pdf->cell(20,4,"$apagar",1,0,"R",$cor);
      $pdf->cell(20,4,"$corrigido",1,0,"R",$cor);
      $pdf->cell(14,4,"$juros",1,0,"R",$cor);
      $pdf->cell(14,4,"$multa",1,0,"R",$cor);
      $pdf->cell(15,4,"$total",1,1,"R",$cor);

}
if($cor%1){
  $cor = 0;
}else $cor = 1;

$pdf->setfont('arial','b',8);
$pdf->cell(65,4,"TOTAL GERAL:","T",0,"L",$cor);
$pdf->setfont('arial','b',6);
$pdf->cell(14,4,"","T",0,"C",$cor);
$pdf->cell(14,4,"","T",0,"C",$cor);
$pdf->cell(20,4,"","T",0,"C",$cor);
$pdf->cell(26,4,"","T",0,"C",$cor);
$pdf->cell(20,4,db_formatar($vtot_bruto,"f"),"T",0,"R",$cor);
$pdf->cell(20,4,db_formatar($vtot_imposto,"f"),"T",0,"R",$cor);
$pdf->cell(20,4,db_formatar($vtot_pago,"f"),"T",0,"R",$cor);
$pdf->cell(20,4,db_formatar($vtot_apagar,"f"),"T",0,"R",$cor);
$pdf->cell(20,4,db_formatar($vtot_correcao,"f"),"T",0,"R",$cor);
$pdf->cell(14,4,db_formatar($vtot_juros,"f"),"T",0,"R",$cor);
$pdf->cell(14,4,db_formatar($vtot_multa,"f"),"T",0,"R",$cor);
$pdf->cell(15,4,db_formatar($vtot_total,"f"),"T",1,"R",$cor);

$pdf->Output();

?>
