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
include("classes/db_conhist_classe.php");

$conhist = new cl_conhist;

$clrotulo = new rotulocampo;
$clrotulo->label('c50_codhist');
$clrotulo->label('c50_compl');
$clrotulo->label('c50_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == "a") {
$desc_ordem = "Alfeb�tica";
$order_by = "c50_descr";
}
else {
$desc_ordem = "Num�rica";
$order_by = "c50_codhist";
}
 
$head3 = "RELAT�RIO DE HIST�RICO DE LAN�AMENTOS";
$head5 = "ORDEM $desc_ordem";

$result = $conhist->sql_record($conhist->sql_query("","*","$order_by"));
//db_criatabela($result);exit;

if ($conhist->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem itens cadastrados para fazer a consulta.');
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

for($x = 0; $x < $conhist->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLc50_codhist,1,0,"C",1);
      $pdf->cell(25,$alt,$RLc50_compl,1,0,"C",1);
      $pdf->cell(60,$alt,$RLc50_descr,1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$c50_codhist,0,0,"C",0);
   $pdf->cell(25,$alt,$c50_compl,0,0,"C",0);
   $pdf->cell(60,$alt,$c50_descr,0,1,"L",0);
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(100,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>