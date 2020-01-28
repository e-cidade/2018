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
include("classes/db_diversos_classe.php");
include("classes/db_procdiver_classe.php");
include("libs/db_sql.php");

db_postmemory($_GET);
//exit;

$clrotulo = new rotulocampo;
$cldiversos = new cl_diversos;
$clprocdiver = new cl_procdiver;
$clrotulo->label("dv09_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("k00_matric");
$clrotulo->label("k00_inscr");
$cldiversos->rotulo->label();
//descrproced
$dbwhere = " 1=1 ";
$dati    = "";
$sFiltroOrigem = "";

$lProcessaNormal        = false;
$lProcessaParcelado     = false;
$lProcessaImportado     = false;
$lProcessaInconsistente = false;
$lProcessaPago          = false;
$lProcessaNaoPago       = false;
$lProcessaCancelado     = false;

$aOpcoesDebito  = explode("|",$situacaodebito);
foreach($aOpcoesDebito as $i => $sOpcao ) {
  switch ($sOpcao) {
    case 'pago':
      $lProcessaPago = true;
      break;
    case 'naopago': 
      $lProcessaNaoPago = true;
      break;
    case 'cancelado': 
      $lProcessaCancelado = true;
      break;
    case 'parcelado': 
      $lProcessaParcelado = true;
      break;
    case 'importado': 
      $lProcessaImportado = true;
      break;
    case 'inconsistente': 
      $lProcessaInconsistente = true;
      break;    
  }
}

if ( $situacaodebito == "" ) {
  $lProcessaParcelado = true;
  $lProcessaImportado = true;
  $lProcessaPago      = true;
  $lProcessaNaoPago   = true;
  $lProcessaCancelado = true;  
}

if(isset($cgm) && $cgm != "") {
  $dbwhere .= " and k00_numcgm = {$cgm} ";
  $sFiltroOrigem = "CGM : {$cgm}";
} else if(isset($inscr) && $inscr != "") {
  $dbwhere .= " and ( select k00_inscr from arreinscr where k00_numpre = diversos.dv05_numpre ) = {$inscr} ";
  $sFiltroOrigem = "INSCR : {$inscr}";
}else if(isset($matric) && $matric != ""){
  $dbwhere .= " and ( select k00_matric from arrematric where k00_numpre = diversos.dv05_numpre ) = {$matric} ";
  $sFiltroOrigem = "MATRIC : {$matric}";
}  

if(isset($coddiver) && $coddiver != ""){
  $dbwhere .= " and dv05_coddiver = $coddiver "; 
}

if(isset($proced) && $proced != ""){
  $dbwhere .= " and dv05_procdiver = $proced "; 
}

if( (isset($dataini) && $dataini != "--") && (isset($datafim) && $datafim != "--") ){
  $dbwhere .= " and dv05_dtinsc >= '".$dataini."' and dv05_dtinsc <= '".$datafim."'";
  $peri="PERÍODO: ".db_formatar($dataini,'d')." até ".db_formatar($datafim,'d');
}else if(isset($dataini) && $dataini != "--"){
  $dbwhere .= " and dv05_dtinsc >= '".$dataini."'";
}else if(isset($datafim) && $datafim != "--"){
  $dbwhere .= " and dv05_dtinsc <= '".$datafim."'";
}else{
  $peri="PERÍODO: GERAL";
}

$head1 = "RELATÓRIO DE DIVERSOS";
$head2 = $peri;
if ($sFiltroOrigem != "") {
  $head3 = $sFiltroOrigem;
}
if ($descrproced != "" && trim($descrproced) != '-') {  
  $head4 = $descrproced;
}
if ($coddiver != "") {
  $head5 = "DIVERSOS : $coddiver";
}

if($tipovenc == 'v'){
  $dbwhere .= "	and dtvenc < '".date("Y-m-d",db_getsession("DB_datausu"))."'";
}elseif($tipovenc == 'n'){
  $dbwhere .= "	and dtvenc > '".date("Y-m-d",db_getsession("DB_datausu"))."'";
}
$sSqlDiversosPorTipo = "";
$sUnionAll = "";
if ($lProcessaInconsistente) {
  $sSqlDiversosPorTipo .= "           select  cast('Inconsistente' as varchar) as situacao_debito, ";
  $sSqlDiversosPorTipo .= "                   diversos.dv05_coddiver, ";
  $sSqlDiversosPorTipo .= "                   diversos.dv05_valor, ";
  $sSqlDiversosPorTipo .= "                   diversos.dv05_numpre, ";
  $sSqlDiversosPorTipo .= "                   diversos.dv05_numcgm, ";
  $sSqlDiversosPorTipo .= "                   diversos.dv05_procdiver, ";
  $sSqlDiversosPorTipo .= "                   diversos.dv05_dtinsc, ";    
  $sSqlDiversosPorTipo .= "                   diversos.dv05_privenc as dtvenc ";
  $sSqlDiversosPorTipo .= "              from diversos ";
  $sSqlDiversosPorTipo .= "                   left join arrepaga on arrepaga.k00_numpre = diversos.dv05_numpre ";
  $sSqlDiversosPorTipo .= "                   left join ( select * ";
  $sSqlDiversosPorTipo .= "                                 from termodiver  ";
  $sSqlDiversosPorTipo .= "                                      inner join termo on v07_parcel = dv10_parcel  ";
  $sSqlDiversosPorTipo .= "                                                      and v07_situacao <> 3  ";
  $sSqlDiversosPorTipo .= "                        ) as termodiver         on termodiver.dv10_coddiver = diversos.dv05_coddiver ";
  $sSqlDiversosPorTipo .= "                   left join arrecad            on arrecad.k00_numpre = diversos.dv05_numpre ";
  $sSqlDiversosPorTipo .= "                   left join cancdebitosreg     on cancdebitosreg.k21_numpre    = diversos.dv05_numpre ";
  $sSqlDiversosPorTipo .= "                   left join cancdebitosprocreg on cancdebitosreg.k21_sequencia = cancdebitosprocreg.k24_cancdebitosreg   ";
  $sSqlDiversosPorTipo .= "                   left join divold  on divold.k10_numpre  = diversos.dv05_numpre ";
  $sSqlDiversosPorTipo .= "             where arrepaga.k00_numpre is null ";
  $sSqlDiversosPorTipo .= "               and termodiver.dv10_coddiver is null ";
  $sSqlDiversosPorTipo .= "               and arrecad.k00_numpre is null ";
  $sSqlDiversosPorTipo .= "               and cancdebitosprocreg.k24_cancdebitosreg is null ";
  $sSqlDiversosPorTipo .= "               and divold.k10_numpre is null ";
  $sSqlDiversosPorTipo .= "               and dv05_instit = ".db_getsession('DB_instit');

} else {

  if ($lProcessaPago) {
    $sSqlDiversosPorTipo .= "           select  cast('Pago' as varchar) as situacao_debito, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_coddiver, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_valor, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_numpre, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_numcgm, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_procdiver, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_dtinsc, ";    
    $sSqlDiversosPorTipo .= "                   diversos.dv05_privenc as dtvenc ";
    $sSqlDiversosPorTipo .= "              from diversos ";
    $sSqlDiversosPorTipo .= "                   inner join arrepaga on arrepaga.k00_numpre = diversos.dv05_numpre ";
    $sSqlDiversosPorTipo .= "             where dv05_instit = ".db_getsession('DB_instit');
    $sUnionAll = "union all";
  }
  if ($lProcessaNaoPago) {
    $sSqlDiversosPorTipo .= "          $sUnionAll ";
    $sSqlDiversosPorTipo .= "            select cast('Devido' as varchar) as situacao_debito, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_coddiver, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_valor, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_numpre, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_numcgm, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_procdiver, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_dtinsc, ";    
    $sSqlDiversosPorTipo .= "                   diversos.dv05_privenc as dtvenc ";
    $sSqlDiversosPorTipo .= "              from diversos ";
    $sSqlDiversosPorTipo .= "                   inner join arrecad on arrecad.k00_numpre = diversos.dv05_numpre ";
    $sSqlDiversosPorTipo .= "             where dv05_instit = ".db_getsession('DB_instit');
    $sUnionAll = "union all";
  }
  if ($lProcessaCancelado) {
    $sSqlDiversosPorTipo .= "          $sUnionAll ";
    $sSqlDiversosPorTipo .= "            select cast('Cancelado' as varchar) as situacao_debito, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_coddiver, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_valor, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_numpre, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_numcgm, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_procdiver, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_dtinsc, ";    
    $sSqlDiversosPorTipo .= "                   diversos.dv05_privenc as dtvenc ";
    $sSqlDiversosPorTipo .= "              from diversos ";
    $sSqlDiversosPorTipo .= "                   inner join cancdebitosreg     on cancdebitosreg.k21_numpre    = diversos.dv05_numpre ";
    $sSqlDiversosPorTipo .= "                   inner join cancdebitosprocreg on cancdebitosreg.k21_sequencia = cancdebitosprocreg.k24_cancdebitosreg   ";
    $sSqlDiversosPorTipo .= "             where dv05_instit = ".db_getsession('DB_instit'); 
    $sUnionAll = "union all";
  }
  if ($lProcessaImportado) {
    $sSqlDiversosPorTipo .= "          $sUnionAll ";
    $sSqlDiversosPorTipo .= "            select cast('Importado' as varchar) as situacao_debito, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_coddiver, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_valor, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_numpre, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_numcgm, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_procdiver, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_dtinsc, ";    
    $sSqlDiversosPorTipo .= "                   diversos.dv05_privenc as dtvenc ";
    $sSqlDiversosPorTipo .= "              from diversos ";
    $sSqlDiversosPorTipo .= "                   inner join divold     on divold.k10_numpre        = diversos.dv05_numpre ";
    $sSqlDiversosPorTipo .= "                   left  join termodiver on termodiver.dv10_coddiver = diversos.dv05_coddiver ";
    $sSqlDiversosPorTipo .= "             where termodiver.dv10_coddiver is null ";
    $sSqlDiversosPorTipo .= "               and dv05_instit = ".db_getsession('DB_instit'); 
    $sUnionAll = "union all";
  }
  if ($lProcessaParcelado) {
    $sSqlDiversosPorTipo .= "          $sUnionAll ";
    $sSqlDiversosPorTipo .= "            select cast('Parcelado' as varchar) as situacao_debito, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_coddiver, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_valor, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_numpre, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_numcgm, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_procdiver, ";
    $sSqlDiversosPorTipo .= "                   diversos.dv05_dtinsc, ";    
    $sSqlDiversosPorTipo .= "                   diversos.dv05_privenc as dtvenc ";
    $sSqlDiversosPorTipo .= "              from diversos ";
    $sSqlDiversosPorTipo .= "                   inner join termodiver on termodiver.dv10_coddiver = diversos.dv05_coddiver ";
    $sSqlDiversosPorTipo .= "                   inner join termo      on v07_parcel = dv10_parcel  ";
    $sSqlDiversosPorTipo .= "                                        and v07_situacao <> 3  ";
    $sSqlDiversosPorTipo .= "             where dv05_instit = ".db_getsession('DB_instit'); 
  }
}

$sSqlDiversos  = " select distinct on (dv05_coddiver)  ";
$sSqlDiversos .= "        dv05_numpre,  ";
$sSqlDiversos .= "        dv05_coddiver, ";
$sSqlDiversos .= "        dv05_valor, ";
$sSqlDiversos .= "        z01_nome, ";
$sSqlDiversos .= "        ( select k00_matric from arrematric where k00_numpre = diversos.dv05_numpre ) as k00_matric, ";
$sSqlDiversos .= "        ( select k00_inscr  from arreinscr  where k00_numpre = diversos.dv05_numpre ) as k00_inscr, ";
$sSqlDiversos .= "        dv05_numcgm, ";
$sSqlDiversos .= "        dtvenc, ";
$sSqlDiversos .= "        situacao_debito, ";
$sSqlDiversos .= "        case  ";
$sSqlDiversos .= "          when situacao_debito = 'Parcelado' ";
$sSqlDiversos .= "            then 'Sim'  ";
$sSqlDiversos .= "          else 'Nao'  ";
$sSqlDiversos .= "        end as parcelamento,  ";
$sSqlDiversos .= "        case  ";
$sSqlDiversos .= "          when ( select k00_numpre from arrematric where k00_numpre = diversos.dv05_numpre ) is not null  ";
$sSqlDiversos .= "            then 'MATRICULA'  ";
$sSqlDiversos .= "          when ( select k00_numpre from arreinscr where k00_numpre = diversos.dv05_numpre ) is not null  ";
$sSqlDiversos .= "            then 'INSCRICAO'  ";
$sSqlDiversos .= "          else 'CGM'  ";
$sSqlDiversos .= "        end as k00_tipo ";
$sSqlDiversos .= "   from (  $sSqlDiversosPorTipo ) as diversos  ";
$sSqlDiversos .= "        inner join procdiver  on dv09_procdiver = dv05_procdiver  ";
$sSqlDiversos .= "        inner join cgm        on z01_numcgm     = dv05_numcgm ";
$sSqlDiversos .= "        inner join arrenumcgm on diversos.dv05_numpre = arrenumcgm.k00_numpre  ";
$sSqlDiversos .= "  where {$dbwhere} ";

// die($sSqlDiversos);

$rsDiversos  = $cldiversos->sql_record( $sSqlDiversos );
$numrows01 = $cldiversos->numrows;

if ($numrows01 == 0) {
  
  $sErro = _M("tributario.diversos.dvr2_reldiversos002.sem_registro");
  db_redireciona("db_erros.php?fechar=true&db_erro={$sErro}");
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->SetFont('Arial','',11);
$pdf->SetFillColor(250);

$pripag="true";
$pago="";
$devido="";

$imprimerelatorio = 't';
$total_ant = 0;
$sd        = "0"; 
$total     = 0;

//db_criatabela($rsDiversos);exit;
$aTotalizadores = array();
$aTotalizadores['Inconsistente']['total'] = 0;
$aTotalizadores['Pago']['total']          = 0;
$aTotalizadores['Devido']['total']        = 0;
$aTotalizadores['Cancelado']['total']     = 0;
$aTotalizadores['Importado']['total']     = 0; 
$aTotalizadores['Parcelado']['total']     = 0; 

$aTotalizadores['Inconsistente']['qtde']  = 0;
$aTotalizadores['Pago']['qtde']           = 0;
$aTotalizadores['Devido']['qtde']         = 0;
$aTotalizadores['Cancelado']['qtde']      = 0;
$aTotalizadores['Importado']['qtde']      = 0; 
$aTotalizadores['Parcelado']['qtde']      = 0; 

for($i=0;$i<$numrows01;$i++) {

  db_fieldsmemory($rsDiversos,$i);

  $result02 = debitos_numpre($dv05_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0);
  if($result02 != false && pg_numrows($result02) > 0){
    $total_ant = 0;
    for($j = 0;$j < pg_numrows($result02);$j++) {

      $k00_dtvenc =  pg_result($result02,$j,"k00_dtvenc");
      $total_ant  += pg_result($result02,$j,"total");

    }
    $total = $total_ant;
  }

  $result03= db_query("select count(distinct k00_numpar) as parcelaspagas, sum(k00_valor) from arrepaga where k00_numpre = $dv05_numpre");
  if(pg_numrows($result03)>0){
    db_fieldsmemory($result03,0);
  }else{
    $sum=0; 	
  }

  $total = ( $total - $sum );

  if($k00_tipo=="CGM"){
    $val = $dv05_numcgm;
  }elseif($k00_tipo=="INSCRICAO"){
    $val = $k00_inscr;
  }elseif($k00_tipo=="MATRICULA"){
    $val = $k00_matric;
  }

  $y=$pdf->GetY();

  if($y>170 || $pripag=="true"){
    $pripag="false";
    $pdf->AddPage("L");
    $propag="false";
    $pdf->SetFont('Arial','B',8);
    $pdf->SetFillColor(215);
    $pdf->Cell(20,5,"Cód. diversos"      ,'BTR',0,"C",1); // 1
    $pdf->Cell(80,5,"Nome/Razão Social"  ,1,0,"C",1); // 2
    $pdf->Cell(25,5,"Origem"             ,1,0,"C",1); // 1
    $pdf->Cell(25,5,"Valor lançado"      ,1,0,"C",1); // 1
    $pdf->Cell(25,5,"Valor devido"       ,1,0,"C",1); // 5
    $pdf->Cell(25,5,"Valor pago"         ,1,0,"C",1); // 5
    $pdf->Cell(25,5,"Parcelas pagas"     ,1,0,"C",1); // 6
    $pdf->Cell(30,5,"Código Arrecadação" ,1,0,"C",1); // 1
    $pdf->Cell(25,5,"Situação"           ,'BTL',1,"C",1); // 1

    $pdf->SetFont('Arial','',7);
  }

  if($sd=="0"){
    $pdf->SetFillColor(235);
    $sd="1";
  }else{
    $sd="0";
    $pdf->SetFillColor(255);
  }  

  $pdf->Cell("20",5,"$dv05_coddiver",'BTR',0,"C",1);
  $pdf->Cell("80",5,substr($z01_nome,0,55),1,0,"L",1);
  $pdf->Cell("25",5,"$k00_tipo $val",1,0,"L",1);
  $pdf->Cell("25",5,db_formatar($dv05_valor,'f'),1,0,"R",1);
  $pdf->Cell("25",5,db_formatar($total_ant,'f'),1,0,"R",1);
  $pdf->Cell("25",5,db_formatar($sum,'f'),1,0,"R",1);

  $pdf->Cell("25",5,$parcelaspagas,1,0,"C",1); 
  $pdf->Cell("30",5,$dv05_numpre,1,0,"C",1);
  $pdf->Cell("25",5,$situacao_debito,'BTL',1,"C",1);

  $pago   += $sum;
  $devido += $total_ant;

  if ($situacao_debito == 'Pago') {
    $aTotalizadores[$situacao_debito]['total'] += $sum;
    $aTotalizadores[$situacao_debito]['qtde']++;
  } else {
    $aTotalizadores[$situacao_debito]['total'] += $dv05_valor;
    $aTotalizadores[$situacao_debito]['qtde']++;  	
  }

  $total=0;
  $total_ant=0;

}

/*
echo "<pre>";
var_dump($aTotalizadores);
echo "</pre>";
exit;
*/

$nTotalGeral = 0;
$iQtdeGeral  = 0;

$pdf->SetFont('Arial','B',8);
$pdf->ln(2);

$pdf->SetFillColor(215);
$pdf->Cell("90",5,"Totais por Situação/Quantidade ",1,1,"C",1);
$pdf->Cell("30",5,"Descrição"         ,1,0,"C",1);
$pdf->Cell("30",5,"Valor"             ,1,0,"C",1);
$pdf->Cell("30",5,"Quantidade"        ,1,1,"C",1);

$pdf->SetFont('Arial','',7);
if ($lProcessaPago) {
  $pdf->SetFillColor(255);
  $pdf->Cell("30",5,"Pago "                                           ,1,0,"L",1);
  $pdf->Cell("30",5,db_formatar($aTotalizadores['Pago']['total'],'f') ,1,0,"R",1);
  $pdf->Cell("30",5,$aTotalizadores['Pago']['qtde']                   ,1,1,"C",1);
  $nTotalGeral += $aTotalizadores['Pago']['total'];
  $iQtdeGeral  += $aTotalizadores['Pago']['qtde'];
}
if ($lProcessaNaoPago) {
  $pdf->SetFillColor(235);
  $pdf->Cell("30",5,"Devido "                                           ,1,0,"L",1);
  $pdf->Cell("30",5,db_formatar($aTotalizadores['Devido']['total'],'f') ,1,0,"R",1);
  $pdf->Cell("30",5,$aTotalizadores['Devido']['qtde']                   ,1,1,"C",1);
  $nTotalGeral += $aTotalizadores['Devido']['total'];
  $iQtdeGeral  += $aTotalizadores['Devido']['qtde'];
}
if ($lProcessaCancelado) {
  $pdf->SetFillColor(255);
  $pdf->Cell("30",5,"Cancelado"                                            ,1,0,"L",1);
  $pdf->Cell("30",5,db_formatar($aTotalizadores['Cancelado']['total'],'f') ,1,0,"R",1);
  $pdf->Cell("30",5,$aTotalizadores['Cancelado']['qtde']                   ,1,1,"C",1);
  $nTotalGeral += $aTotalizadores['Cancelado']['total'];
  $iQtdeGeral  += $aTotalizadores['Cancelado']['qtde'];
}
if ($lProcessaImportado) {
  $pdf->SetFillColor(235);
  $pdf->Cell("30",5,"Importado "                                           ,1,0,"L",1);
  $pdf->Cell("30",5,db_formatar($aTotalizadores['Importado']['total'],'f') ,1,0,"R",1);
  $pdf->Cell("30",5,$aTotalizadores['Importado']['qtde']                   ,1,1,"C",1);
  $nTotalGeral += $aTotalizadores['Importado']['total'];
  $iQtdeGeral  += $aTotalizadores['Importado']['qtde'];
}
if ($lProcessaParcelado) {
  $pdf->SetFillColor(235);
  $pdf->Cell("30",5,"Parcelado "                                           ,1,0,"L",1);
  $pdf->Cell("30",5,db_formatar($aTotalizadores['Parcelado']['total'],'f') ,1,0,"R",1);
  $pdf->Cell("30",5,$aTotalizadores['Parcelado']['qtde']                   ,1,1,"C",1);
  $nTotalGeral += $aTotalizadores['Parcelado']['total'];
  $iQtdeGeral  += $aTotalizadores['Parcelado']['qtde'];
}
if ($lProcessaInconsistente) {
  $pdf->SetFillColor(255);
  $pdf->Cell("30",5,"Inconsistente "                                           ,1,0,"L",1);
  $pdf->Cell("30",5,db_formatar($aTotalizadores['Inconsistente']['total'],'f') ,1,0,"R",1);
  $pdf->Cell("30",5,$aTotalizadores['Inconsistente']['qtde']                   ,1,1,"C",1);
  $nTotalGeral += $aTotalizadores['Inconsistente']['total'];
  $iQtdeGeral  += $aTotalizadores['Inconsistente']['qtde'];
}

$pdf->SetFillColor(235);
$pdf->Cell("30",5,"Total Geral : "              ,1,0,"L",1);
$pdf->Cell("30",5,db_formatar($nTotalGeral,'f') ,1,0,"R",1);
$pdf->Cell("30",5,$iQtdeGeral                   ,1,1,"C",1);

$pdf->Ln(2);

$pdf->Output();

?>