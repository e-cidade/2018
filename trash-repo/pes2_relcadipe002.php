<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head2 = "RELATORIO DO CADASTRO DO IPE";

if($ordem == 'a'){
  $xordem = ' order by z01_nome';
  $head3 = "ORDEM ALFABETICA";
}elseif($ordem == 'n'){
  $xordem = ' order by rh62_regist';
  $head3 = "ORDEM REGISTRO";
}elseif($ordem == 'v'){
  $xordem = ' order by rh14_valor';
  $head3 = "ORDEM VALOR";
}elseif($ordem == 'c'){
  $xordem = ' order by rh63_numcgm';
  $head3 = "ORDEM CGM";
}elseif($ordem == 'i'){
  $xordem = ' order by rh14_matipe';
  $head3 = "ORDEM MATRICULA IPE";
}


$sql = "

select rh62_regist,
       rh63_numcgm,
       z01_nome,
       rh14_matipe,
       rh14_dtvinc,
       rh14_estado,
       rh14_valor 
from rhipe 
     left join rhiperegist on rh14_sequencia = rh62_sequencia 
     left join rhipenumcgm on rh63_sequencia = rh14_sequencia 
     left join cgm on z01_numcgm = rh63_numcgm 
$xordem
"; 
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if($xxnum == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não nenhum registro encontrado no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfont('arial','b',8);
$troca = 1;
$total = 0;
$alt = 4;
$xsec = 0;
$pdf->setfillcolor(235);
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'NUMCGM',1,0,"C",1);
      $pdf->cell(75,$alt,'NOME',1,0,"C",1);
      $pdf->cell(15,$alt,'REGISTRO',1,0,"C",1);
      $pdf->cell(25,$alt,'MAT. IPE',1,0,"C",1);
      $pdf->cell(20,$alt,'DT.VINCULO',1,0,"C",1);
      $pdf->cell(20,$alt,'ESTADO',1,0,"C",1);
      $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$rh63_numcgm,1,0,"R",1);
   $pdf->cell(75,$alt,$z01_nome,1,0,"L",1);
   $pdf->cell(15,$alt,$rh62_regist,1,0,"R",1);
   $pdf->cell(25,$alt,$rh14_matipe,1,0,"R",1);
   $pdf->cell(20,$alt,db_formatar($rh14_dtvinc,'d'),1,0,"C",1);
   $pdf->cell(20,$alt,$rh14_estado,1,0,"C",1);
   $pdf->cell(20,$alt,db_formatar($rh14_valor,'f'),1,1,"R",1);
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS '.$total,"T",0,"R",0);
$pdf->Output();
?>