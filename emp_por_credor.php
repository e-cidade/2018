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

$clrotulo = new rotulocampo;
$clrotulo->label('r13_codigo');
$clrotulo->label('r13_descr');
$clrotulo->label('r13_descro');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "RELAÇÃO DE EMPENHOS POR CREDOR";
//$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = "
select conlancam.*,
       e60_numcgm,
       z01_nome,
       e60_numemp,
       e60_anousu,
       e60_codemp,
       c71_coddoc,
       c53_descr,
       o56_elemento, 
       o56_descr  
from conlancam 
     inner join conlancamemp 	on c70_codlan = c75_codlan 
     inner join conlancamdoc 	on c71_codlan = c70_codlan 
     inner join conhistdoc 	on c53_coddoc = c71_coddoc 
     inner join empempenho 	on e60_numemp = c75_numemp 
     inner join orcdotacao 	on o58_coddot = e60_coddot 
     				and e60_anousu = o58_anousu 
     inner join orcelemento 	on o58_codele = o56_codele
                               and o58_anousu = o56_anousu
     inner join cgm 		on z01_numcgm = e60_numcgm
where e60_numcgm = 15264
order by e60_anousu,e60_codemp,c70_codlan
";

//echo $sql ; exit;

$result = pg_exec($sql);

//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Movimentações para este credor.');

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 5;
$total_liq = 0;
$total_anuliq = 0;
$total_pago = 0;
$total_anupago = 0;
$cor = 0;
$emp = 0;

   for($x=0;$x<pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > ($pdf->h - 30) || $troca ==1){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->multicell(0,4,'Credor : '.$e60_numcgm.' - '.$z01_nome,0,"L");
      $pdf->ln(3);
      $pdf->cell(23,$alt,'EMPENHO',1,0,"C",1);
      $pdf->cell(23,$alt,'DATA',1,0,"C",1);
      $pdf->cell(50,$alt,'MOVIMENTAÇÃO',1,0,"C",1);
      $pdf->cell(30,$alt,'VALOR',1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   if($emp != $e60_codemp){
     $emp = $e60_codemp;
     if($cor == 1)
       $cor = 0;
     else
       $cor = 1;
   }
   $pdf->setfont('arial','',8);
//   $dots = $pdf->preenchimento($r13_descr,60);
   $pdf->cell(23,$alt,$e60_codemp.'/'.$e60_anousu,0,0,"C",$cor);
   $pdf->cell(23,$alt,db_formatar($c70_data,'d'),0,0,"C",$cor);
   $pdf->cell(50,$alt,$c53_descr,0,0,"L",$cor);
   $pdf->cell(30,$alt,db_formatar($c70_valor,'f'),0,1,"R",$cor);
   }
   
//$pdf->setfont('arial','b',8);
//$pdf->cell(80,$alt,'TOTAL DO BANCO',"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>