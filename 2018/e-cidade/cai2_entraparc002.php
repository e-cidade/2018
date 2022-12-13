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
include("classes/db_termo_classe.php");

$cltermo = new cl_termo;

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$head3 = "PARCELAMENTO";
$head4 = "PAGAMENTO DE PRIMEIRA PARCELA NÃO EFETUADO";

$result=$cltermo->sql_record($cltermo->sql_query_arre(null," v07_parcel,v07_numcgm as numcgm,z01_nome,k00_numpre,k00_numpar,sum(k00_valor)as k00_valor",null,"k00_numpar = 1 group by  v07_parcel,v07_numcgm,z01_nome,k00_numpre,k00_numpar "));

if ($cltermo->numrows==0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
for($x = 0; $x < $cltermo->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(30,$alt,"Parcelamento",1,0,"C",1);
      $pdf->cell(30,$alt,"NUMCGM",1,0,"C",1);
      $pdf->cell(100,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(30,$alt,"Valor",1,1,"C",1); 
      $troca = 0;
      $p=0;
   }   
   $pdf->setfont('arial','',7);
   $pdf->cell(30,$alt,$v07_parcel,0,0,"C",$p);
   $pdf->cell(30,$alt,$numcgm,0,0,"C",$p);
   $pdf->cell(100,$alt,$z01_nome,0,0,"L",$p);
   $pdf->cell(30,$alt,db_formatar($k00_valor,'f'),0,1,"R",$p);
   if ($p==0){
   	$p=1;
   }else{
   	$p=0;
   }   
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(140,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$pdf->Output();
?>