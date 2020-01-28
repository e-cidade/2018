<?
/*
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

include("fpdf151/pdf.php");
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("libs/db_sql.php");

$cldesdobramento = new cl_desdobramento();

//db_postmemory($HTTP_POST_VARS,2);exit;
db_postmemory($HTTP_POST_VARS);

$dtini = implode("-",array_reverse(explode("/",$DBtxt21)));
$dtfim = implode("-",array_reverse(explode("/",$DBtxt22)));

//---------------------------------------------------------------  
$clselorcdotacao = new cl_selorcdotacao();

$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
// $instits = $clselorcdotacao->getInstit();

$instits = ' ('.str_replace('-',', ',$db_selinstit).') ';

$w_elemento = $clselorcdotacao->getElemento();
//@ recupera as informações fornecidas para gerar os dados
//---------------------------------------------------------------  

$head1 = "DESPESA POR DESDOBRAMENTO";
$head2 = "EXERCÍCIO: ".db_getsession("DB_anousu");
$d1    = $DBtxt21;
$d2    = $DBtxt22;
$head3 = "Período selecionado: $d1 à $d2  "; 

$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in $instits");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinstabrev ; 
  $xvirg = ', ';
}
$head6 = "INSTITUIÇÕES : ".$descr_inst;

/////////////////////////////////////////////////////////

$anousu = db_getsession("DB_anousu");
$sele_work = $clselorcdotacao->getDados(false,true)." and o58_instit in $instits and  o58_anousu=$anousu  ";
if ($w_elemento !=""){
    $w_elemento = " and o58_codele in  ({$w_elemento}) ";
}
//echo $cldesdobramento->sql($sele_work,$dtini,$dtfim,$instits);    
    
$result = db_query($cldesdobramento->sql($sele_work,$dtini,$dtfim,$instits));
$rows = pg_numrows($result);
//  db_criatabela($result);exit;
 /*
 if (pg_numrows($result)>0){
   // monta relatorio
 } else {
   // db_redireciona("con2_estruturarel.php?relatorio=4");
   echo "erro";
 }    
 */

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt            = 4;
$pagina         = 1;

$pdf->addpage();

$tot_empenhado = 0;
$tot_liquidado = 0;
$tot_pago=0;

$tg_empenhado = 0;
$tg_liquidado = 0;
$tg_pago=0;

$estrut_elemento ="";

for($x=0;$x< $rows;$x++){
   db_fieldsmemory($result,$x);

   if ($estrut_elemento =="" || $estrut_elemento !=$c60_estrut){
     if ($estrut_elemento !=""){
        $pdf->setX(55); 
	$pdf->cell(80,$alt,"SUBTOTAL",'B',0,"L",0);
        $pdf->cell(20,$alt,db_formatar($tot_empenhado,'f'),'B',0,"R",0);
        $pdf->cell(20,$alt,db_formatar($tot_liquidado,'f'),'B',0,"R",0);
        $pdf->cell(20,$alt,db_formatar($tot_pago,'f'),'B',1,"R",0);
	$tot_empenhado = 0;
	$tot_liquidado = 0;
        $tot_pago=0;
     }  
     $pdf->Ln(3);	   
     $estrut_elemento = $c60_estrut;
     $pdf->cell(25,$alt,"$c60_estrut",'B',0,"L",0);
     $pdf->cell(100,$alt,"$c60_descr",'B',0,"L",0);
     $pdf->cell(20,$alt,"Empenhado",'B',0,"R",0);
     $pdf->cell(20,$alt,"Liquidado",'B',0,"R",0);
     $pdf->cell(20,$alt,"Pago",'B',1,"R",0);
   }
   $pdf->setX(30); 
   $pdf->cell(25,$alt,"$o56_elemento",'0',0,"L",0);
   $pdf->cell(80,$alt,"$o56_descr",'0',0,"L",0);
   $pdf->cell(20,$alt,db_formatar($empenhado - $empenhado_estornado,'f'),'0',0,"R",0);
   $pdf->cell(20,$alt,db_formatar($liquidado - $liquidado_estornado,'f'),'0',0,"R",0);
   $pdf->cell(20,$alt,db_formatar($pagamento - $pagamento_estornado,'f'),'0',0,"R",0);
   $pdf->Ln();
   $tot_empenhado += ($empenhado - $empenhado_estornado);
   $tot_liquidado += ($liquidado - $liquidado_estornado);
   $tot_pago      += ($pagamento - $pagamento_estornado);
   
   // se for o ultimo elemento imprime total também 
   if ($x == $rows -1 ){
        $pdf->setX(55); 
	$pdf->cell(80,$alt,"SUBTOTAL",'B',0,"L",0);
        $pdf->cell(20,$alt,db_formatar($tot_empenhado,'f'),'B',0,"R",0);
        $pdf->cell(20,$alt,db_formatar($tot_liquidado,'f'),'B',0,"R",0);
        $pdf->cell(20,$alt,db_formatar($tot_pago,'f'),'B',1,"R",0);
   }
   $tg_empenhado += ($empenhado - $empenhado_estornado); 
   $tg_liquidado += ($liquidado - $liquidado_estornado); 
   $tg_pago      += ($pagamento - $pagamento_estornado);
}

// imprime total geral   
$pdf->Ln(5);
$pdf->setX(55); 
$pdf->cell(80,$alt,"TOTAL GERAL",'B',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tg_empenhado,'f'),'B',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tg_liquidado,'f'),'B',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tg_pago,'f'),'B',1,"R",0);

//-- imprime parametros
if (isset($imprime_filtro)){
   if (($pdf->getY()+44) > 170){
       $pdf->AddPage();  
   } else {
      $pdf->setY(130);
   }  
   $pdf->Ln(10);
   $parametros = $clselorcdotacao->getParametros();
   $pdf->multicell(170,$alt,$parametros,1,1,"R",0);
}   




$pdf->Output();