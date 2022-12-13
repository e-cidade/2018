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

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "CONFERÊNCIA DE RECEITAS";
$head5 = "PERÍODO : ".$mes." / ".$ano;
$xwhere = '';
if($recurso != ''){
  $xwhere = ' and rh25_recurso in ('."$recurso".')';
}


if($ponto == 's'){
  $arquivo = 'gerfsal';
  $sigla   = 'r14_';
  $head7   = 'PONTO : SALÁRIO';
}elseif($ponto == 'c'){
  $arquivo = 'gerfcom';
  $sigla   = 'r48_';
  $head7   = 'PONTO : COMPLEMENTAR';
  if(isset($comp) && $comp != 0){
    $xwhere.= " and r48_semest = ".$comp;
  }
}elseif($ponto == 'a'){
  $arquivo = 'gerfadi';
  $sigla   = 'r22_';
  $head7   = 'PONTO : ADIANTAMENTO';
}elseif($ponto == 'r'){
  $arquivo = 'gerfres';
  $sigla   = 'r20_';
  $head7   = 'PONTO : RESCISÃO';
}elseif($ponto == 'd'){
  $arquivo = 'gerfs13';
  $sigla   = 'r35_';
  $head7   = 'PONTO : 13o. SALÁRIO';
}

$head3 = "CONFERÊNCIA DAS RECEITAS";
$head5 = "PERÍODO : ".$mes." / ".$ano;


$sql = "

select 
       rh26_orgao,
       o40_descr,
       rh26_unidade,
       o41_descr,
       rh25_projativ,
       o55_descr,
       rh25_recurso,
       o15_descr,
       ".$sigla."rubric as rubric,
       rh27_descr,
       round(sum(".$sigla."valor),2) as valor
from $arquivo
     inner join rhrubricas         on rh27_rubric  = ".$sigla."rubric
		                              and rh27_instit  = ".$sigla."instit
     left  join rhrubelemento      on rh23_rubric  = rh27_rubric 
		                              and rh23_instit  = ".$sigla."instit
     inner join rhpessoal          on rh01_regist  = ".$sigla."regist
     inner join rhpessoalmov       on rh02_regist  = rh01_regist
                                  and rh02_anousu  = $ano
                  			          and rh02_mesusu  = $mes
		                              and rh02_instit  = ".$sigla."instit
     inner join rhlota       	     on r70_codigo   = rh02_lota
		                              and r70_instit   = ".$sigla."instit
     inner join rhlotaexe    	     on rh26_anousu  = $ano 
                            	    and rh26_codigo  = r70_codigo  
     inner join (select distinct rh25_codigo,rh25_anousu,rh25_projativ, rh25_recurso 
                 from rhlotavinc 
		             where rh25_anousu = ".$ano.") 
                   as rhlotavinc   on rh25_codigo  = rh26_codigo 
                            	    and rh25_anousu  = rh26_anousu
     inner join orcorgao           on o40_orgao    = rh26_orgao
                         				  and o40_anousu   = rh26_anousu
		                              and o40_instit   = ".$sigla."instit
     inner join orcunidade	       on o41_orgao    = rh26_orgao
     				                      and o41_unidade  = rh26_unidade
                       			      and o41_anousu   = rh26_anousu
     inner join orcprojativ        on o55_projativ = rh25_projativ
        			                    and o55_anousu   = rh25_anousu
		                              and o55__instit  = ".$sigla."instit
     inner join orctiporec         on o15_codigo   = rh25_recurso

where ".$sigla."anousu = $ano
  and ".$sigla."mesusu = $mes
	and ".$sigla."instit = ".db_getsession("DB_instit")."
  and ".$sigla."pd     = 2 
  and rh23_rubric is null
  and ".$sigla."pd != 3
  $xwhere
group by 
       rh26_orgao,
       o40_descr,
       rh26_unidade,
       o41_descr,
       rh25_projativ,
       o55_descr,
       rh25_recurso,
       o15_descr,
       ".$sigla."rubric,
       rh27_descr
order by 
       rh26_orgao,
       o40_descr,
       rh26_unidade,
       o41_descr,
       rh25_projativ,
       o55_descr,
       rh25_recurso,
       o15_descr,
       ".$sigla."rubric,
       rh27_descr
    
                             
       ";

// echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem receitas no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$qorgao    = 0;
$torgao    = 0;
$qunidade  = 0;
$tunidade  = 0;
$qprojativ = 0;
$tprojativ = 0;
$qrecusro  = 0;
$trecurso  = 0;
$total     = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
      $pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(60,$alt,'RECURSO',1,0,"C",1);
      $pdf->cell(25,$alt,'VALOR',1,1,"C",1);
      $quebra = '';
      $troca = 0;
   }
   if($qorgao != $rh26_orgao){
     $qorgao = $rh26_orgao;
     $qprojativ = 0;
     $pdf->setfont('arial','b',8);
     
     $pdf->cell(0,$alt,db_formatar($rh26_orgao,'orgao').' - '.$o40_descr,0,1,"L",0);
     
   }
   if($qunidade != $rh26_orgao.$rh26_unidade){
     $qunidade = $rh26_orgao.$rh26_unidade;
     $qprojativ = 0;
     $pdf->setfont('arial','b',8);
     $pdf->cell(0,$alt,db_formatar($rh26_orgao,'orgao').db_formatar($rh26_unidade,'orgao').' - '.$o41_descr,0,1,"L",0);
     
   }
   if($qprojativ != $rh25_projativ){
     $qprojativ = $rh25_projativ;
     $pdf->setfont('arial','',8);
     $pdf->cell(0,$alt,$rh25_projativ.' - '.$o55_descr,0,1,"L",0);
     
   }
   
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$rubric,0,0,"C",0);
   $pdf->cell(80,$alt,$rh27_descr,0,0,"L",0);
   $pdf->cell(60,$alt,$rh25_recurso.'-'.$o15_descr,0,0,"L",0);
   $pdf->cell(25,$alt,db_formatar($valor,'f'),0,1,"R",0);
   $total += $valor;
}
$pdf->ln(3);
$pdf->setfont('arial','B',8);
$pdf->cell(155,$alt,'Total das Receitas :  ',"T",0,"L",0);
$pdf->cell(25,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
?>