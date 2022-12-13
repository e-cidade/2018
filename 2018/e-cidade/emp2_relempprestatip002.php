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
/*
$dataini = '2005-09-01';
$datafin = '2005-09-30';
*/

if($tipoemp == 'p'){
  $coddoc = ' (30,31) ';
  $head7 = 'PAGAMENTOS';
}else if($tipoemp == 'e'){
  $coddoc = ' (10,11) ';
  $head7 = 'EMPENHADOS';
}else{
  $coddoc = ' (20,21) ';
  $head7 = 'LIQUIDADOS';
}

$head3 = "RELAÇÃO DE TIPOS DE EMPENHOS";
$head5 = "PERÍODO : ".db_formatar($dataini,'d')." A ".db_formatar($datafin,'d');

$sql = "
select  
       e60_numemp,
       e60_codemp,
       e60_anousu,
       c75_data,
       z01_nome,
       o41_orgao,
       o41_unidade,
       e60_resumo,
       o58_codigo,
       o58_projativ,
       o56_elemento,
       sum(case when c53_tipo in (10,20,30) then c70_valor else c70_valor * (-1) end) as c70_valor
from empempenho 
     inner join empemphist  on e60_numemp = e63_numemp
     inner join orcdotacao  on e60_coddot = o58_coddot and o58_anousu = e60_anousu
     inner join orcunidade  on o41_unidade = o58_unidade and o41_orgao = o58_orgao and o41_anousu = e60_anousu
     inner join orcelemento on o56_codele = o58_codele and o56_anousu = e60_anousu
     inner join conlancamemp on e60_numemp = c75_numemp
     inner join conlancam on c70_codlan = c75_codlan 
     inner join conlancamdoc on c71_codlan = c75_codlan 
     inner join conhistdoc on c71_coddoc = c53_coddoc  
     inner join cgm on z01_numcgm = e60_numcgm
where c53_tipo in $coddoc
  and e63_codhist in ($dados )
  and c75_data between '$dataini' and '$datafin' 
group by
       e60_numemp,
       e60_codemp,
       e60_anousu,
       c75_data,
       z01_nome,
       o41_orgao,
       o41_unidade,
       o58_codigo,
       o58_projativ,
       o56_elemento,
       e60_resumo
       ";
//  and e60_anousu = e60_anousu
//  and c60_codcon in (1201,1212,1215)

//echo $sql ; exit;

$result = pg_exec($sql);

//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem empenhos do tipo selecionado no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total_liq = 0;
$total_anuliq = 0;
$total_pago = 0;
$total_anupago = 0;

      $total = 0;
      $troca = 0;
   $pdf->ln(2);
   for($x=0;$x<pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
     if($pdf->gety() + 30 > $pdf->h  || $troca == 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(23,$alt,'UNIDADE',1,0,"C",1);
      $pdf->cell(70,$alt,'NOME',1,0,"C",1);
      $pdf->cell(15,$alt,'EMPENHO',1,0,"C",1);
      $pdf->cell(15,$alt,'DATA',1,0,"C",1);
      $pdf->cell(20,$alt,'RECURSO',1,0,"C",1);
      $pdf->cell(20,$alt,'PROJATIV',1,0,"C",1);
      $pdf->cell(25,$alt,'ELEMENTO',1,0,"C",1);
      $pdf->cell(30,$alt,'VALOR',1,1,"C",1);
      $pdf->cell(0,$alt,'HISTORICO',1,1,"C",1);
      $troca = 1;
      $pre = 1;
     }
     if($pre == 1)
       $pre = 0;
     else
       $pre = 1;
   $pdf->setfont('arial','',8);
//   $dots = $pdf->preenchimento($r13_descr,60);
   $pdf->cell(23,$alt,db_formatar($o41_orgao,'orgao').db_formatar($o41_unidade,'orgao'),0,0,"C",$pre);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(15,$alt,$e60_codemp.'/'.$e60_anousu,0,0,"L",$pre);
   $pdf->cell(15,$alt,db_formatar($c75_data,'d'),0,0,"L",$pre);
   $pdf->cell(20,$alt,$o58_codigo,0,0,"C",$pre);
   $pdf->cell(20,$alt,$o58_projativ,0,0,"C",$pre);
   $pdf->cell(25,$alt,$o56_elemento,0,0,"C",$pre);
   $pdf->cell(30,$alt,db_formatar($c70_valor,'f'),0,1,"R",$pre);
   $pdf->multicell(0,$alt,$e60_resumo,0,"L",$pre);
   $pdf->ln(4);
   $total_liq += $c70_valor;
   }
   $pdf->cell(23,$alt,'TOTAL',0,0,"L",0);
   $pdf->cell(100,$alt,'',0,0,"R",0);
   $pdf->cell(30,$alt,db_formatar($total_liq,'f'),0,1,"R",0);
   

$pdf->Output();
   
?>