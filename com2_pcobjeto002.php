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
include("classes/db_pcobjeto_classe.php");

$pcobjeto = new cl_pcobjeto;

$clrotulo = new rotulocampo;
$clrotulo->label('pc02_codobjeto');
$clrotulo->label('pc02_descrobjeto');
$clrotulo->label('pc02_complobjeto');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == "a") {
$desc_ordem = "Alfebética";
$order_by = "pc02_descrobjeto";
}
else {
$desc_ordem = "Numérica";
$order_by = "pc02_codobjeto";
}
 
$head3 = "RELATÓRIO DOS OBJETOS DE CONTROLE DE COMPRAS";
$head5 = "ORDEM $desc_ordem";

$result = $pcobjeto->sql_record($pcobjeto->sql_query("","*",$order_by));
//db_criatabela($result);exit;

if ($pcobjeto->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem objetos de controle de compras cadastrados.');

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

for($x = 0; $x < $pcobjeto->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLpc02_codobjeto,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc02_descrobjeto,1,0,"C",1);
      $pdf->multicell(0,$alt,$RLpc02_complobjeto,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$pc02_codobjeto,0,0,"C",0);
   $pdf->cell(60,$alt,$pc02_descrobjeto,0,0,"L",0);
   $pdf->multicell(0,$alt,$pc02_complobjeto,0,"L");
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>