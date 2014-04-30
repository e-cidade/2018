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
include("classes/db_itbitransacao_classe.php");

$clitbitransacao = new cl_itbitransacao;

$clrotulo = new rotulocampo;
$clrotulo->label('it04_codigo');
$clrotulo->label('it04_descr');
$clrotulo->label('it04_desconto');
$clrotulo->label('it04_obs');


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == "a") {
$desc_ordem = "Alfebética";
$order_by = "it04_descr";
}
else {
$desc_ordem = "Numérica";
$order_by = "it04_codigo";
}
 

$head3 = "CADASTRO DE TIPO DE TRANSAÇÕES";
$head5 = "ORDEM $desc_ordem";

$result = $clitbitransacao->sql_record($clitbitransacao->sql_query("","*",$order_by));
//echo $clitbitransacao->sql_query("","*",$order_by); exit;
//db_criatabela($result);

if ($clitbitransacao->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem tipo de transações cadastradas.');

}
      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

for($x = 0; $x < $clitbitransacao->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLit04_codigo,1,0,"C",1);
      $pdf->cell(115,$alt,$RLit04_descr,1,0,"C",1); 
      $pdf->cell(30,$alt,$RLit04_desconto,1,0,"C",1);

      $pdf->cell(115,$alt,$RLit04_obs,1,1,"C",1);
  
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$it04_codigo,0,0,"C",0);
   $pdf->cell(115,$alt,$it04_descr,0,0,"L",0);  
   $pdf->cell(30,$alt,$it04_desconto,0,0,"C",0);
   $pdf->multicell(115,$alt,$it04_obs,0,"L","",0);
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(260,$alt,'TOTAL DE TIPO DE TRANSAÇÕES  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>