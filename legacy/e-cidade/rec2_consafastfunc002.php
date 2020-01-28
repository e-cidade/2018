<?php
/**
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$classenta = new cl_assenta;

$dbwhere  = "";
$z01_nome = "";

if ( isset( $codMatri ) && trim( $codMatri ) != "") {
  
  $dbwhere  = " h16_regist = ".$codMatri;
  $res_nome = db_query("select z01_nome from rhpessoal inner join cgm on rh01_numcgm = z01_numcgm where rh01_regist = ".$codMatri);
  db_fieldsmemory($res_nome,0);
}

if ( isset( $codAssen ) && trim( $codAssen) != "") {
  $dbwhere.= " and h16_assent = ".$codAssen;
}

if ( isset( $dataIni ) && trim( $dataIni ) != "") {
  
  if ( isset( $dataFim ) && trim( $dataFim ) != "") {
    $dbwhere.= " and h16_dtconc between '".$dataIni."' and '".$dataFim."' ";
  } else{
    $dbwhere.= " and h16_dtconc >= '".$dataIni."' ";
  }
}

$dbwhere.= " and h16_codigo in (select distinct rh193_assentamento_funcional from assentamentofuncional)";
$sCampos = "h12_assent, h12_descr, h16_dtconc, h16_dtterm, h16_quant, h16_nrport, h16_atofic, h16_histor, h16_hist2";
$sql     = $classenta->sql_query_tipo(null, $sCampos, "h16_dtconc desc ", $dbwhere);

$head2 = "ASSENTAMENTOS CADASTRADOS";
$head4 = "PERÍODO : ".db_formatar( $dataIni, 'd' )." até ".date( 'd/m/Y', db_getsession("DB_datausu") );

if ( ( isset($dataFim) && !empty($dataFim) ) ) {
  $head4 = "PERÍODO : ".db_formatar( $dataIni, 'd' )." até ".db_formatar( $dataFim, 'd' );
}

$head6 = "NOME: ".$z01_nome;

$result = db_query($sql);
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);

if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
for($x = 0; $x < $xxnum;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'ASSENT.',1,0,"C",1);
      $pdf->cell(60,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(15,$alt,'INÍCIO',1,0,"C",1);
      $pdf->cell(15,$alt,'FIM',1,0,"C",1);
      $pdf->cell(15,$alt,'NR.ATO',1,0,"C",1);
      $pdf->cell(20,$alt,'TIPO ATO',1,0,"C",1);
      $pdf->cell(0,$alt,'HISTÓRICO',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$h12_assent,0,0,"C",$pre);
   $pdf->cell(60,$alt,$h12_descr,0,0,"L",$pre);
   $pdf->cell(15,$alt,db_formatar( $h16_dtconc, "d"),0,0,"C",$pre);
   $pdf->cell(15,$alt,db_formatar( $h16_dtterm, "d"),0,0,"C",$pre);
   $pdf->cell(15,$alt,$h16_nrport,0,0,"C",$pre);
   $pdf->cell(20,$alt,$h16_atofic,0,0,"L",$pre);
   $pdf->multicell(0,$alt,$h16_histor.' '.$h16_hist2,0,"L",$pre);
   $total += 1;
//   $pdf->SetXY($pdf->lMargin,$pdf->gety() + $alt);
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>