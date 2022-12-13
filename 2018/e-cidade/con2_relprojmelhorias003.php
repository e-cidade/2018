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

include("libs/db_sql.php");
include("fpdf151/pdf.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if ( $d40_codigo == null ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Código da lista nao preenchido!');
}
$pdf = new pdf("P");
$largura = 6;
if($opcao==3){
  $dbwhere=" and  d41_pgtopref='f'   ";
  $tipo="Pagos ao empreiteiro";
}else if($opcao==2){
  $dbwhere=" and  d41_pgtopref='t'   ";
  $tipo="Lançamento em Contribuição de Melhoria";
}else{
  $tipo="";
  $dbwhere="";
}
db_postmemory($_SESSION);

$sql="select ruas.j14_nome, case when ruas.j14_tipo = 'R' then 'RUA' else case when ruas.j14_tipo = 'A' then 'AVENIDA' else 'TRAVESSA' end end as j14_tipo, z01_nome, z01_ender, cgm.z01_telef, d40_trecho, d40_profun from projmelhorias inner join ruas on ruas.j14_codigo = projmelhorias.d40_codlog left outer join projmelhoriasresp on projmelhoriasresp.d42_codigo = projmelhorias.d40_codigo left outer join cgm on cgm.z01_numcgm = projmelhoriasresp.d42_numcgm where d40_codigo = $d40_codigo";
$result = db_query($sql);
db_fieldsmemory($result,0);

$pdf->Open();
$pdf->AliasNbPages();
$head3 = "RELATÓRIO FINANCEIRO DA LISTA $d40_codigo";
//$head5 = $j14_tipo.' '.$j14_nome;
$head5 = $tipo;
$pdf->AddPage();

$sql = "select munic from db_config where codigo = ".db_getsession('DB_instit');
$result = db_query($sql);
db_fieldsmemory($result,0);
if ( pg_numrows($result) == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Lista nao cadastrada!');
}
$pdf->SetFont('Arial','',8);
$pdf->SetFillColor(235);
$sql="select
          distinct proprietario,d41_pgtopref, j39_numero, z01_nome, j01_matric, j40_refant, d41_testada, d41_eixo, d41_obs
	  from projmelhoriasmatric
	  inner join proprietario on proprietario.j01_matric = projmelhoriasmatric.d41_matric
	  where d41_codigo = $d40_codigo $dbwhere order by j40_refant";
$result = db_query($sql);

if ( pg_numrows($result) == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Lista nao cadastrada!');
}

$pdf->SetFont('Arial','B',13);
$pdf->Cell(167,10,$j14_tipo.' '.$j14_nome,1,1,"C",1);
$pdf->ln(5);

$t="0";
$f="0";
$totalsao1 = 0;
if($opcao!=3){
  $pdf->SetFont('Arial','B',13);
  $pdf->Cell(167,10,($opcao == 1?"Lançamento em Contribuição de Melhoria":"ANEXO II"),1,1,"C",1);
  $numrows03=pg_numrows($result);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(70,$largura,'PROPRIETÁRIO',1,0,"C",1);
  $pdf->Cell(20,$largura,'NUMERO',1,0,"C",1);
  $pdf->Cell(15,$largura,'MATRIC',1,0,"C",1);
  $pdf->Cell(20,$largura,'REFER *',1,0,"C",1);
  $pdf->Cell(14,$largura,'TESTADA',1,0,"C",1);
  $pdf->Cell(14,$largura,'EIXO **',1,0,"C",1);
  $pdf->Cell(14,$largura,'TOTAL',1,1,"C",1);

  $somatestadas=0;
  for($s=0;$s<$numrows03;$s++){
    db_fieldsmemory($result,$s);
    if($d41_pgtopref=="t"){
      if ($pdf->gety() > ($pdf->h-40)) {
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(167,10,($opcao == 1?"Lançamento em Contribuição de Melhoria":"ANEXO II"),1,1,"C",1);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(70,$largura,'PROPRIETÁRIO',1,0,"C",1);
	$pdf->Cell(20,$largura,'NUMERO',1,0,"C",1);
	$pdf->Cell(15,$largura,'MATRIC',1,0,"C",1);
	$pdf->Cell(20,$largura,'REFER *',1,0,"C",1);
	$pdf->Cell(14,$largura,'TESTADA',1,0,"C",1);
	$pdf->Cell(14,$largura,'EIXO **',1,0,"C",1);
	$pdf->Cell(14,$largura,'TOTAL',1,1,"C",1);
      }
      $pdf->Cell(70,$largura,$proprietario,1,0,"L",0);
      $pdf->Cell(20,$largura,$j39_numero,1,0,"L",0);
      $pdf->Cell(15,$largura,$j01_matric,1,0,"L",0);
      $pdf->Cell(20,$largura,$j40_refant,1,0,"L",0);
      $pdf->Cell(14,$largura,db_formatar($d41_testada,'f',' ',10),1,0,"L",0);
      $pdf->Cell(14,$largura,db_formatar($d41_eixo,'f',' ',10),1,0,"L",0);
      $pdf->Cell(14,$largura,db_formatar($d41_testada+$d41_eixo,'f',' ',10),1,1,"L",0);
      $t++;
      $somatestadas+=$d41_testada+$d41_eixo;
    }
  }
  $pdf->Cell(95,$largura,'',0,0,"C",0);
  $pdf->Cell(58,$largura,'SOMA DAS TESTADAS EM METROS',1,0,"L",0);
  $pdf->Cell(14,$largura,db_formatar($somatestadas,'f',' ',10),1,1,"C",0);

  $pdf->Cell(95,$largura,'',0,0,"C",0);
  $pdf->Cell(58,$largura,'SUB-TOTAL DA ÁREA DO SERVIÇO EM M2',1,0,"L",0);
  $pdf->Cell(14,$largura,db_formatar($somatestadas * $d40_profun,'f',' ',10),1,1,"C",0);
  $totalsao1 = $somatestadas * $d40_profun;
}
$totalsao2 = 0;
if($opcao!=2){
  $pdf->ln(5);
  $pdf->SetFont('Arial','B',13);
  $pdf->Cell(167,10,($opcao == 1?"Pago ao empreiteiro":"ANEXO II"),1,1,"C",1);
  $numrows03=pg_numrows($result);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(70,$largura,'PROPRIETÁRIO',1,0,"C",1);
  $pdf->Cell(20,$largura,'NUMERO',1,0,"C",1);
  $pdf->Cell(15,$largura,'MATRIC',1,0,"C",1);
  $pdf->Cell(20,$largura,'REFER *',1,0,"C",1);
  $pdf->Cell(14,$largura,'TESTADA',1,0,"C",1);
  $pdf->Cell(14,$largura,'EIXO **',1,0,"C",1);
  $pdf->Cell(14,$largura,'TOTAL',1,1,"C",1);
  $somatestadas=0;
  for($s=0;$s<$numrows03;$s++){
    db_fieldsmemory($result,$s);
    if($d41_pgtopref=="f"){
      if ($pdf->gety() > ($pdf->h-40)) {
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(167,10,($opcao == 1?"Pago ao empreiteiro":"ANEXO II"),1,1,"C",1);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(70,$largura,'PROPRIETÁRIO',1,0,"C",1);
	$pdf->Cell(20,$largura,'NUMERO',1,0,"C",1);
	$pdf->Cell(15,$largura,'MATRIC',1,0,"C",1);
	$pdf->Cell(20,$largura,'REFER *',1,0,"C",1);
	$pdf->Cell(14,$largura,'TESTADA',1,0,"C",1);
	$pdf->Cell(14,$largura,'EIXO **',1,0,"C",1);
	$pdf->Cell(14,$largura,'TOTAL',1,1,"C",1);
      }
      $pdf->Cell(70,$largura,$proprietario,1,0,"L",0);
      $pdf->Cell(20,$largura,$j39_numero,1,0,"L",0);
      $pdf->Cell(15,$largura,$j01_matric,1,0,"L",0);
      $pdf->Cell(20,$largura,$j40_refant,1,0,"L",0);
      $pdf->Cell(14,$largura,db_formatar($d41_testada,'f',' ',10),1,0,"L",0);
      $pdf->Cell(14,$largura,db_formatar($d41_eixo,'f',' ',10),1,0,"L",0);
      $pdf->Cell(14,$largura,db_formatar($d41_testada+$d41_eixo,'f',' ',10),1,1,"L",0);
      $f++;
      $somatestadas+=$d41_testada+$d41_eixo;
    }
  }
  $pdf->Cell(95,$largura,'',0,0,"C",0);
  $pdf->Cell(58,$largura,'SOMA DAS TESTADAS EM METROS',1,0,"L",0);
  $pdf->Cell(14,$largura,db_formatar($somatestadas,'f',' ',10),1,1,"C",0);

  $pdf->Cell(95,$largura,'',0,0,"C",0);
  $pdf->Cell(58,$largura,'SUB-TOTAL DA ÁREA DO SERVIÇO EM M2',1,0,"L",0);
  $pdf->Cell(14,$largura,db_formatar($somatestadas * $d40_profun,'f',' ',10),1,1,"C",0);
  $totalsao2 = $somatestadas * $d40_profun;
}
$pdf->ln(5);
$pdf->Cell(95,$largura,'',0,0,"C",0);
$pdf->Cell(58,$largura,'TOTAL DA ÁREA DO SERVIÇO EM M2',1,0,"L",0);
$pdf->Cell(14,$largura,db_formatar($totalsao1 + $totalsao2,'f',' ',10),1,1,"C",0);
$pdf->ln(5);

if($opcao!=1){

  $pdf->Cell(100,$largura,"Total de registros de " . ($opcao==2?"lançamento em contribuição de melhoria":"pagos ao empreiteiro") . ": $numrows03",1,1,"L",0);

  $sql="select
          count(d41_pgtopref)
	  from projmelhoriasmatric
	  where d41_codigo = $d40_codigo and d41_pgtopref is " . ($opcao==2?"false":"true");
  $result = db_query($sql);
  db_fieldsmemory($result,0);
  $pdf->Cell(100,$largura,"Total de registros de " . ($opcao==3?"lançamento em contribuição de melhoria":"pagos ao empreiteiro") . ": $count",1,1,"L",0);
  $pdf->Cell(100,$largura,"Total do lançamento: " . ($count + $numrows03),1,1,"L",0);

}else{
  $pt=($t*100)/$numrows03;
  $pf=($f*100)/$numrows03;
  $pdf->Cell(120,$largura,"Lançamento em Contribuição de Melhoria: $t (".number_format($pt,2)."%)     Pagos ao empreiteiro: $f (".number_format($pf,"2")."%)",1,0,"L",0);

}
$pdf->Output();