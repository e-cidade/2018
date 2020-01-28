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
include("classes/db_conhistdoc_classe.php");

$conhistdoc = new cl_conhistdoc;

$clrotulo = new rotulocampo;
$clrotulo->label('c53_coddoc');
$clrotulo->label('c53_descr');
$clrotulo->label('c53_tipo');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == "a") {
$desc_ordem = "Alfebética";
$order_by = "c53_descr";
}
elseif($ordem == "b") {
$desc_ordem = "Numérica";
$order_by = "c53_coddoc";
}else {
$desc_ordem = "de Tipo";
$order_by = "c53_tipo";
}
 
$head3 = "RELATÓRIO DE DOCUMENTOS DOS LANÇAMENTOS";
$head5 = "ORDEM $desc_ordem";

$result = $conhistdoc->sql_record($conhistdoc->sql_query("","*","$order_by"));
//db_criatabela($result);exit;

if ($conhistdoc->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem itens cadastrados para fazer a consulta.');
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

for($x = 0; $x < $conhistdoc->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLc53_coddoc,1,0,"C",1);
      $pdf->cell(60,$alt,$RLc53_descr,1,0,"C",1);
      $pdf->cell(40,$alt,$RLc53_tipo,1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$c53_coddoc,0,0,"C",0);
   $pdf->cell(60,$alt,$c53_descr,0,0,"L",0);
   $pdf->cell(40,$alt,$c53_tipo,0,1,"C",0);
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(120,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>