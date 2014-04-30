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
include("libs/db_sql.php");
include("classes/db_bensmotbaixa_classe.php");

$clmotbaixa = new cl_bensmotbaixa;

$clrotulo = new rotulocampo;
$clrotulo->label('t51_motivo');
$clrotulo->label('t51_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == "a") {
$desc_ordem = "Alfebética";
$order_by = "t51_descr";
}
else {
$desc_ordem = "Numérica";
$order_by = "t51_motivo";
}
 

$head3 = "CADASTRO DOS TIPOS DE BAIXA";
$head5 = "ORDEM $desc_ordem";

$result = $clmotbaixa->sql_record($clmotbaixa->sql_query("","*",$order_by));
//echo $sql ; exit;

if ($clmotbaixa->numrows == 0){
  
   $sMsg = _M('patrimonial.patrimonio.pat2_tipobaixa002.nao_existem_motivos');
   db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
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

for($x = 0; $x < $clmotbaixa->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLt51_motivo,1,0,"C",1);
      $pdf->cell(70,$alt,$RLt51_descr,1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$t51_motivo,0,0,"C",0);
   $pdf->cell(70,$alt,$t51_descr,0,1,"L",0);
   $total++;
   
}

$pdf->setfont('arial','b',8);
$pdf->cell(85,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();

?>