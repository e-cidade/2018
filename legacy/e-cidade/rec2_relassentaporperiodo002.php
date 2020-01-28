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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$where = 'where 1 = 1';
if($regist != ''){
  $where .= " and rh01_regist = $regist";
}

$sql_ano = "select fc_anofolha(".db_getsession('DB_instit').") as anousu, fc_mesfolha(".db_getsession('DB_instit').") as mesusu";
$res_ano = db_query($sql_ano);
db_fieldsmemory($res_ano,0);

if($perinicial != '--'){
  if($perfinal == '--'){
    $final = date("Y-m-d");
  }else{
    $final = $perfinal;
  }
  $where .= " and h16_dtconc between '{$perinicial}' and '{$final}'";
}

if($tipos != ''){
  $where .= " and h12_codigo in ($tipos)";
}
$xordem = 'order by ';
if($ordem == 'a'){
  $xordem .= " z01_nome, rh37_descr, h12_descr, h16_dtconc ";
}elseif($ordem == 'n'){
  $xordem .= " h16_regist, h16_dtconc ";
}elseif($ordem == 'c'){
  $xordem .= " rh37_descr, z01_nome, h16_dtconc ";
}elseif($ordem == 'd'){
  $xordem .= " h16_dtconc ";
}


$where .= " and h16_regist in (select distinct rh02_regist from rhpessoalmov 
                                where rh02_anousu = ". DBPessoal::getAnoFolha() ."
                                  and rh02_mesusu = ". DBPessoal::getMesFolha() ."
                                  and rh02_lota in (select distinct rh157_lotacao
                                                      from db_usuariosrhlota
                                                     where rh157_usuario = ". db_getsession("DB_id_usuario") ."))";
$head3 = "RELATORIO DE ASSENTAMENTOS";
$head5 = "PERIODO : ".db_formatar($perinicial,'d')." a ".db_formatar($final,'d');

$sql = "
 select h16_regist, 
        z01_nome, 
        rh37_descr,
        h12_assent, 
        h12_descr, 
        h16_dtconc, 
        h16_dtterm, 
        h16_quant,
        h16_histor,
        h16_hist2,
        h16_dtlanc
 from assenta 
      inner join assentamentofuncional on rh193_assentamento_funcional = h16_codigo
      inner join rhpessoal             on h16_regist                   = rh01_regist
      inner join rhpessoalmov          on rh02_regist                  = rh01_regist
                                      and rh02_anousu                  = $anousu
                                      and rh02_mesusu                  = $mesusu
                                      and rh02_instit                  = ".db_getsession('DB_instit')."
      inner join rhfuncao              on rh37_funcao                  = rh02_funcao
                                      and rh37_instit                  = ".db_getsession('DB_instit')."
      inner join cgm                   on rh01_numcgm                  = z01_numcgm 
      inner join tipoasse              on h16_assent                   = h12_codigo 
 $where
 $xordem
       ";
// kill_sql($sql);

$result = db_query($sql);
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   
            db_redireciona('db_erros.php?fechar=true&db_erro=Não existem assentamentos cadastrados para o período informado');

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca      = 1;
$alt        = 4;
$total      = 0;
$total_dias = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(60,$alt,'CARGO',1,0,"C",1);
      $pdf->cell(60,$alt,'ASSENTAMENTO',1,0,"C",1);
      $pdf->cell(20,$alt,'INICIAL',1,0,"C",1);
      $pdf->cell(20,$alt,'FINAL',1,0,"C",1);
      $pdf->cell(24,$alt,'LANCAMENTO',1,0,"C",1);
      $pdf->cell(15,$alt,'DIAS',1,1,"C",1);
      if($descr == 's'){
        $pdf->cell(250,$alt,'OBSERVACAO',1,1,"C",1);
      }
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$h16_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(60,$alt,$rh37_descr,0,0,"L",$pre);
   $pdf->cell(60,$alt,$h12_assent.'-'.$h12_descr,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($h16_dtconc,'d'),0,0,"C",$pre);
   $pdf->cell(20,$alt,db_formatar($h16_dtterm,'d'),0,0,"C",$pre);
   $pdf->cell(24,$alt,db_formatar($h16_dtlanc,'d'),0,0,"C",$pre);
   $pdf->cell(15,$alt,$h16_quant,0,1,"C",$pre);
   if($descr == 's'){
     $pdf->multicell(250,$alt,$h16_histor.' '.$h16_hist2,0,"L",$pre);
   }
   $total      += 1;
   $total_dias += $h16_quant;
//   $pdf->SetXY($pdf->lMargin,$pdf->gety() + $alt);
}
$pdf->setfont('arial','b',8);
$pdf->cell(239,$alt,'TOTAL DE LANCAMENTOS :  '.$total,"T",0,"C",0);
$pdf->cell(15,$alt,$total_dias,"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>