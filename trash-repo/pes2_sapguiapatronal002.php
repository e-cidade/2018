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

include("fpdf151/impcarne.php");
include("fpdf151/scpdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$sql_inst = "select * from db_config where codigo = ".db_getsession("DB_instit");
$result_inst = pg_exec($sql_inst);

db_fieldsmemory($result_inst,0);

//$ano = 2005;
//$mes = 7;
//$previdencia = 2;

if ( $previdencia == 1 ){
  $prev      = " and r01_tbprev = $previdencia ";
  $xbases    = " ('R992') ";
  $xrubricas = " ('R901','R902','R903','1360') ";
  $xdeducao  = " ('R919','0255') ";
  $devolucao = " ('0505')";
  $cod_pagto = 2402;
}elseif ( $previdencia == 2){
  $prev      = " and r01_tbprev = $previdencia ";
  $xbases    = " ('R992') ";
  $xdeducao  = " ('0255') ";	
  $xrubricas = " ('R904','R905','R906','1350') ";
  $devolucao = " ('0501') ";	
  $cod_pagto = '';
}elseif ( $previdencia == 3){
  $prev      = " and r01_tbprev = $previdencia ";
  $xdeducao  = " ('0255') ";	
  $xbases    = " ('R992') ";
  $xrubricas = " ('R907','R908','R909') ";
  $devolucao = " ('')";
  $cod_pagto = 2402;
}elseif ( $previdencia == 0){  /// FGTS
  $prev      = " ";
  $xdeducao  = " ('') ";	
  $xbases    = " ('R991') ";
  $xrubricas = " ('') ";
  $cod_pagto =  '';
}

if($previdencia != 0 ){
  $sql1 = "SELECT * 
           FROM inssirf 
	   WHERE r33_anousu = $ano 
	     and r33_mesusu = $mes
	     and r33_codtab = $previdencia+2 limit 1
	  ";
  $res1 = pg_query($sql1);
  db_fieldsmemory($res1,0);
  $perc_patro = $r33_ppatro;
}else{
  $perc_patro = 8;
  $r33_nome   = 'fgts';
}

//echo $perc_patro;exit;

$head3 = "EMPENHOS DO INSS";
$head5 = "PERÍODO : ".$mes." / ".$ano;

if($tipo == 's'){

$sql = "
select count(soma) as soma,
       round(sum(base),2)       as base,
       round(sum(ded),2)        as ded,
       round(sum(dev),2)        as dev,
       round(sum(desco),2)      as desco,
       round(sum(base)/100*$perc_patro,2) as patronal
from 
(
select r01_regist as soma ,
       sum(base)       as base,
       sum(ded)        as ded,
       sum(dev)        as dev,
       sum(desco)      as desco
from 
(
select 
       r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       sum(case when r01_tpvinc = 'A' and r14_rubric in ".$xrubricas." then r14_valor else 0 end) as desco,
       sum(case when r01_tpvinc = 'A' and r14_rubric in ".$xdeducao." then r14_valor else 0 end) as ded ,
       sum(case when r01_tpvinc = 'A' and r14_rubric in ".$devolucao." then r14_valor else 0 end) as dev ,
       sum(case when r14_rubric in ".$xbases."    then r14_valor else 0 end) as base
from gerfsal 
     inner join pessoal on r01_anousu = r14_anousu 
                       and r01_mesusu = r14_mesusu 
		       and r01_regist = r14_regist 
     inner join cgm on r01_numcgm = z01_numcgm  
where r14_anousu = $ano 
  and r14_mesusu = $mes
  $prev
  and ( r14_rubric in ".$xrubricas." 
     or r14_rubric in ".$xdeducao." 
     or r14_rubric in ".$devolucao." 
     or r14_rubric in ".$xbases.")
  group by 
       r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao

union

select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       sum(case when r01_tpvinc = 'A' and r48_rubric in ".$xrubricas." then r48_valor else 0 end) as desco,
       sum(case when r01_tpvinc = 'A' and r48_rubric in ".$xdeducao."  then r48_valor else 0 end) as ded ,
       sum(case when r01_tpvinc = 'A' and r48_rubric in ".$devolucao." then r48_valor else 0 end) as dev ,
       sum(case when r48_rubric in ".$xbases."    then r48_valor else 0 end) as base
from gerfcom
     inner join pessoal on r01_anousu = r48_anousu and r01_mesusu = r48_mesusu and r01_regist = r48_regist
     inner join cgm on r01_numcgm = z01_numcgm
where r48_anousu = $ano
  and r48_mesusu = $mes
  $prev
  and ( r48_rubric in ".$xrubricas." 
     or r48_rubric in ".$xdeducao." 
     or r48_rubric in ".$devolucao." 
     or r48_rubric in ".$xbases." )
  group by 
       r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao
						     
union

select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       sum(case when r01_tpvinc = 'A' and r20_rubric in ".$xrubricas." then r20_valor else 0 end) as desco,
       sum(case when r01_tpvinc = 'A' and r20_rubric in ".$xdeducao."  then r20_valor else 0 end) as ded ,
       sum(case when r01_tpvinc = 'A' and r20_rubric in ".$devolucao." then r20_valor else 0 end) as dev ,
       sum(case when r20_rubric in ".$xbases."    then r20_valor else 0 end) as base
from gerfres
     inner join pessoal on r01_anousu = r20_anousu and r01_mesusu = r20_mesusu and r01_regist = r20_regist
     inner join cgm on r01_numcgm = z01_numcgm
where r20_anousu = $ano
  and r20_mesusu = $mes
  $prev
  and ( r20_rubric in ".$xrubricas." 
     or r20_rubric in ".$xdeducao." 
     or r20_rubric in ".$devolucao." 
     or r20_rubric in ".$xbases.")
  group by 
       r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao
) as xx group by r01_regist
) as xxx
						     
       ";
}else{
$sql = "
select count(soma) as soma,
       round(sum(base),2)       as base,
       round(sum(ded),2)        as ded,
       round(sum(dev),2)        as dev,
       round(sum(desco),2)      as desco,
       round(sum(base)/100*$perc_patro,2) as patronal
from 
(
select r01_regist as soma ,
       sum(base)       as base,
       sum(ded)        as ded,
       sum(dev)        as dev,
       sum(desco)      as desco
from 
(
select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       case when r01_tpvinc = 'A' and r35_rubric in ".$xrubricas." then r35_valor else 0 end as desco,
       case when r01_tpvinc = 'A' and r35_rubric in ".$xdeducao."  then r35_valor else 0 end as ded ,
       case when r01_tpvinc = 'A' and r35_rubric in ".$devolucao." then r35_valor else 0 end as dev ,
       case when r35_rubric in ".$xbases."    then r35_valor else 0 end as base
from gerfs13 
     inner join pessoal on r01_anousu = r35_anousu and r01_mesusu = r35_mesusu and r01_regist = r35_regist 
     inner join cgm on r01_numcgm = z01_numcgm  
where r35_anousu = $ano 
  and r35_mesusu = $mes
  $prev
  and ( r35_rubric in ".$xrubricas." 
     or r35_rubric in ".$xdeducao." 
     or r35_rubric in ".$devolucao." 
     or r35_rubric in ".$xbases.")

) as xx group by r01_regist
) as xxx
						     
       ";
}
//echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);

}
db_fieldsmemory($result,0);
global $pdf;
$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,25);
//$pdf1->modelo     	= 25;
$pdf1->logo             = $logo;
$pdf1->prefeitura       = $nomeinst;
$pdf1->enderpref        = $ender.', '.$numero;
$pdf1->cgcpref          = $cgc;
$pdf1->cep              = $cep;
$pdf1->ufpref           = $uf;
$pdf1->cgcpref          = $cgc;
$pdf1->municpref        = $munic;
$pdf1->telefpref        = $telef;
$pdf1->emailpref        = $email;
$pdf1->ano              = $ano;
$pdf1->mes              = $mes;
$pdf1->func             = $soma;
$pdf1->base             = $base;
$pdf1->deducao          = $ded;
$pdf1->desconto         = $desco - $dev;
$pdf1->patronal         = $patronal;
$pdf1->cod_pagto        = $cod_pagto;
$pdf1->terceiros        = 0;
$pdf1->atu_monetaria    = 0;
$pdf1->juros            = 0;
$pdf1->previdencia      = strtoupper($r33_nome);
$pdf1->imprime();
//$pdf1->mensagem         = $msg;
 

$pdf1->objpdf->output();
   
?>