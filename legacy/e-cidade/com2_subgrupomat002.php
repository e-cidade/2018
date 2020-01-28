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
include("classes/db_pcmater_classe.php");

$clpcmater = new cl_pcmater;
$clpcmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$head3 = "";
$head5 = "";
$result=$clpcmater->sql_record($clpcmater->sql_query(null,"*",null,"pc01_codsubgrupo>499"));
if ($clpcmater->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem subgrupo cadastrados.');
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
$subgrupo="";
for($x = 0; $x < $clpcmater->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,"",1,0,"C",1);
      $pdf->cell(20,$alt,$RLpc01_codmater,1,0,"C",1);
      $pdf->cell(70,$alt,$RLpc01_descrmater,1,0,"C",1); 
      $pdf->cell(20,$alt,"Ativo",1,1,"C",1); 
      $troca = 0;
   }
   if ($pc01_ativo==true){
     $ativo="Sim";
   }else{
     $ativo="Não";
   }
   if ($pc01_codsubgrupo!=$subgrupo){
     $pdf->setfont('arial','b',9);
     $pdf->cell(20,$alt,$pc01_codsubgrupo,0,0,"C",0);
     $pdf->cell(70,$alt,$pc04_descrsubgrupo,0,1,"L",0);
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,"",0,0,"L",0);
   $pdf->cell(20,$alt,$pc01_codmater,0,0,"C",0);
   $pdf->cell(70,$alt,$pc01_descrmater,0,0,"L",0);
   $pdf->cell(20,$alt,$ativo,0,1,"L",0);
   $subgrupo = $pc01_codsubgrupo;
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->Output();
?>