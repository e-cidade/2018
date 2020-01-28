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
include("classes/db_seguradoras_classe.php");

$clseguradoras = new cl_seguradoras;

$clrotulo = new rotulocampo;
$clrotulo->label('t80_segura');
$clrotulo->label('t80_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('t80_contato');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == "a") {
$desc_ordem = "Alfebética";
$order_by = "z01_nome";
}
else {
$desc_ordem = "Numérica";
$order_by = "t80_segura";
}
 

$head3 = "CADASTRO DAS SEGURADORAS";
$head5 = "ORDEM $desc_ordem";

$result = $clseguradoras->sql_record($clseguradoras->sql_query("","*",$order_by));
//echo $sql ; exit;

if ($clseguradoras->numrows == 0){
  
   $sMsg = _M('patrimonial.patrimonio.pat2_seguradoras002.nao_existem_seguradaras');
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

for($x = 0; $x < $clseguradoras->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLt80_segura,1,0,"C",1);
      $pdf->cell(15,$alt,$RLt80_numcgm,1,0,"C",1);
      $pdf->cell(85,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(70,$alt,$RLt80_contato,1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$t80_segura,0,0,"C",0);
   $pdf->cell(15,$alt,$t80_numcgm,0,0,"C",0);
   $pdf->cell(85,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(70,$alt,$t80_contato,0,1,"L",0);
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,'TOTAL DE SEGURADORAS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>