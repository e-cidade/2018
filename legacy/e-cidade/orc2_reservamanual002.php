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


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$sql = "
select o80_codres,
       o40_orgao,
       o40_descr,
       o41_unidade,
       o41_descr,
       o58_coddot,
       o56_descr,
       o56_elemento,
       o80_descr,
       o80_dtini,
       o80_dtfim,
       o80_anousu,
       o80_valor,
       nomeinst
from orcreserva
     inner join orcdotacao   on o58_coddot  = o80_coddot
                            and o58_anousu  = o80_anousu
     inner join orcorgao     on o40_orgao   = o58_orgao
                            and o40_anousu  = o58_anousu
     inner join orcunidade   on o41_unidade = o58_unidade
                            and o41_orgao   = o58_orgao
                            and o41_anousu  = o58_anousu
     inner join orcelemento  on o56_codele  = o58_codele
                            and o56_anousu  = o58_anousu
     inner join db_config    on codigo      = o58_instit
where o80_codres = $res
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existe Cadastrada ('.$o80_codres.'). Contate suporte.');

}

$head3 = "RELATÓRIO DE RESERVA DE SALDO";
//$head5 = "PERÍODO : ".$mes." / ".$ano;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->Addpage(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 6;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   
   $pdf->ln(10);
   $pdf->setfont('arial','b',8);
   $pdf->cell(40,$alt,'Reserva ',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(30,$alt,':  '.$o80_codres.'/'.$o80_anousu,0,1,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(40,$alt,'Instituição',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(30,$alt,':  '.$nomeinst,0,1,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(40,$alt,'Dotação Orçmentária',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(30,$alt,':  '.$o58_coddot.' - '.$o56_elemento.'-'.$o56_descr,0,1,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(40,$alt,'Órgão',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(30,$alt,':  '.db_formatar($o40_orgao,'orgao').'-'.$o40_descr,0,1,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(40,$alt,'Unidade',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(30,$alt,':  '.db_formatar($o41_unidade,'orgao').'-'.$o41_descr,0,1,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(40,$alt,'Motivo da Reserva',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->multicell(0,$alt,':  '.strtoupper($o80_descr));
   $pdf->setfont('arial','b',8);
   $pdf->cell(40,$alt,'Valor',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(30,$alt,':  R$ '.trim(db_formatar($o80_valor,'f')),0,1,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(40,$alt,'Validade',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(30,$alt,':  '.db_formatar($o80_dtini,'d').' até '.db_formatar($o80_dtfim,'d'),0,1,"L",0);


   $pdf->setfont('arial','b',8);

}
//$pdf->setfont('arial','b',8);
//$pdf->cell(80,$alt,'TOTAL DO BANCO',"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>