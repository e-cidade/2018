<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clrotulo->label('r14_rubric');
$clrotulo->label('z01_nome');
$clrotulo->label('r01_regist');
$clrotulo->label('r14_quant');
$clrotulo->label('r14_valor');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$ano = 2005;
$mes = 7;
$ponto = 's';

if($ponto == 's'){
  $arquivo = 'gerfsal';
  $sigla   = 'r14_';
  $head5   = 'PONTO : SALÁRIO';
}elseif($ponto == 'c'){
  $arquivo = 'gerfcom';
  $sigla   = 'r48_';
  $head5   = 'PONTO : COMPLEMENTAR';
}elseif($ponto == 'a'){
  $arquivo = 'gerfadi';
  $sigla   = 'r22_';
  $head5   = 'PONTO : ADIANTAMENTO';
}elseif($ponto == 'r'){
  $arquivo = 'gerfres';
  $sigla   = 'r20_';
  $head5   = 'PONTO : RESCISÃO';
}elseif($ponto == 'd'){
  $arquivo = 'gerfs13';
  $sigla   = 'r35_';
  $head5   = 'PONTO : 13o. SALÁRIO';
}

$sql = "
        select ".$sigla."rubric as rubric,
	       ".$sigla."regist as regist,
	       r01_tpvinc,
	       r01_tbprev,
	       ".$sigla."pd,
	       ".$sigla."quant as quant,
	       to_number(".$sigla."lotac,'99999') as lotacao,
	       ".$sigla."valor as valor
	from ".$arquivo." 
	     inner join pessoal on ".$sigla."regist = r01_regist 
	                       and r01_anousu = ".$sigla."anousu 
			       and r01_mesusu = ".$sigla."mesusu 
	where ".$sigla."anousu = $ano 
	  and ".$sigla."mesusu = $mes
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Cálculo no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$troca = 1;
$alt   = 4;
$xvalor = 0;
$xquant = 0;
$total = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      if($xtotal == 'a'){
        $pdf->setfont('arial','b',8);
        $pdf->cell(15,$alt,$RLr01_regist,1,0,"C",1);
        $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
        $pdf->cell(15,$alt,'LOTAÇÃO',1,0,"C",1);
        $pdf->cell(15,$alt,'QUANT',1,0,"C",1);
        $pdf->cell(25,$alt,'VALOR',1,1,"C",1);
      }
      $troca = 0;
      $pre = 1;
   }
   if($xtotal == 'a'){
     if($pre == 1)
       $pre = 0;
     else
       $pre = 1;
     $pdf->setfont('arial','',7);
     $pdf->cell(15,$alt,$regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
     $pdf->cell(15,$alt,$lotacao,0,0,"C",$pre);
     $pdf->cell(15,$alt,db_formatar($quant,'f'),0,0,"R",$pre);
     $pdf->cell(25,$alt,db_formatar($valor,'f'),0,1,"R",$pre);
   }
   $xvalor += $valor;
   $xquant += $quant;
   $total  += 1;
}
$pdf->setfont('arial','b',8);
$pdf->cell(90,$alt,'TOTAL  :  '.$total.'  FUNCIONÁRIOS',"T",0,"C",0);
$pdf->cell(15,$alt,db_formatar($xquant,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($xvalor,'f'),"T",1,"R",0);

$pdf->Output();
   
?>