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


include("libs/db_liborcamento.php");
$qorgao = 0;
$qunidade = 0;

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);



$clselorcdotacao = new cl_selorcdotacao();
$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
// $sele_work = $clselorcdotacao->getDados(false)." and o40_instit in (".str_replace("-",",",$db_selinstit).")";
$sele_work =" 1=1 ";
if ($clselorcdotacao->orgao!="")
  $sele_work .=" and o23_orgao in  ".$clselorcdotacao->orgao;

if ($clselorcdotacao->unidade!="")  
  $sele_work .=" and o23_unidade in  ".$clselorcdotacao->unidade;

if ($clselorcdotacao->funcao!="")  
  $sele_work .=" and o23_funcao in  ".$clselorcdotacao->funcao;

if ($clselorcdotacao->subfuncao!="")  
  $sele_work .=" and o23_subfuncao in  ".$clselorcdotacao->subfuncao;

if ($clselorcdotacao->programa!="")  
  $sele_work .=" and o23_programa in  ".$clselorcdotacao->programa;

if ($clselorcdotacao->projativ!="")  
  $sele_work .=" and o23_acao in  ".$clselorcdotacao->projativ;

if ($clselorcdotacao->elemento!="")  
  $sele_work .=" and o25_codele in  ".$clselorcdotacao->elemento;

if ($clselorcdotacao->recurso!="")  
  $sele_work .=" and o26_codigo in  ".$clselorcdotacao->recurso;


$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    $descr_inst .= $xvirg.$nomeinst ;
    $xvirg = ', ';
}
if($o55_tipo == 'p'){
  $sele_work .= ' and o55_projativ between 1000 and 1999 ';
}elseif($o55_tipo == 'a'){
  $sele_work .= ' and o55_projativ between 2000 and 2999 ';
}elseif($o55_tipo == 'o'){
  $sele_work .= ' and o55_projativ between 3000 and 3999 ';
}

$xtipo = 0;
if($origem == "O"){
  $xtipo = "ORÇAMENTO";
}else{
  $xtipo = "BALANÇO";
  if($opcao == 3)
    $head6 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
  else
    $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}
$head2 = "RELATÓRIO DO PPA";
$head3 = "ORGÃO/UNIDADE/FUNCÇÃO/SUBFUNÇÃO ";
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

if($tipo_agrupa == 1){
  $grupoini = 0;
  $grupofin = 3;
}elseif($tipo_agrupa == 2){
  $grupoini = 1;
  $grupofin = 3;
}else{
  $grupoini = 8;
  $grupofin = 0;
}

$sql = "
select 
       o58_orgao,
       o40_descr,
       o58_unidade,
       o41_descr,
       o58_funcao,
       o52_descr ,
       o58_subfuncao,
       o53_descr,
       o58_programa,
       o54_descr,
       o58_projativ,
       o55_descr,
       o58_elemento,
       o56_descr,
       o58_coddot,
       o58_codigo,
       o15_descr,
       max(pri) as pri,
       max(seg) as seg,
       max(ter) as ter,
       max(qua) as qua
from
(select 
       o23_codppa,
       o23_anoexe,
       o23_orgao as o58_orgao,
       o40_descr,
       o23_unidade as o58_unidade,
       o41_descr,
       o23_funcao as o58_funcao,
       o52_descr ,
       o23_subfuncao as o58_subfuncao,
       o53_descr,
       o23_programa as o58_programa,
       o54_descr,
       o23_acao as o58_projativ,
       o55_descr,
       o56_elemento as o58_elemento,
       o56_descr,
       0 as o58_coddot,
       o15_codigo as o58_codigo,
       o15_descr,
       o40_instit,
       case when o24_exercicio = 2006 then o24_valor else 0 end as pri,
       case when o24_exercicio = 2007 then o24_valor else 0 end as seg,
       case when o24_exercicio = 2008 then o24_valor else 0 end as ter,
       case when o24_exercicio = 2009 then o24_valor else 0 end as qua
from orcppa 
     inner join orcppaval 	on o23_codppa = o24_codppa
     inner join orcppatiporec 	on o24_codseqppa = o26_codseqppa
     inner join orcppavalele  	on o24_codseqppa = o25_codseqppa
     inner join orcorgao	on o23_orgao = o40_orgao
     				and o23_anoexe = o40_anousu
     inner join orcunidade	on o23_unidade = o41_unidade
     				and o23_orgao  = o41_orgao
				and o23_anoexe = o41_anousu
     inner join orcfuncao	on o23_funcao  = o52_funcao
     inner join orcsubfuncao	on o23_subfuncao = o53_subfuncao
     inner join orcprograma	on o23_programa  = o54_programa
     				and o23_anoexe   = o54_anousu
     inner join orcprojativ	on o23_acao      = o55_projativ
     				and o23_anoexe   = o55_anousu
     inner join orcelemento     on o25_codele    = o56_codele
                                and o56_anousu   = o55_anousu
     inner join orctiporec      on o26_codigo    = o15_codigo
     inner join orcproduto 	on o22_codproduto = o23_produto
   where $sele_work
 ) as w

 group by 
       o58_orgao,
       o40_descr,
       o58_unidade,
       o41_descr,
       o58_funcao,
       o52_descr ,
       o58_subfuncao,
       o53_descr,
       o58_programa,
       o54_descr,
       o58_projativ,
       o55_descr,
       o58_elemento,
       o56_descr,
       o58_coddot,
       o58_codigo,
       o15_descr,
       o40_instit
order by 
       o58_orgao,
       o40_descr,
       o58_unidade,
       o41_descr,
       o58_funcao,
       o52_descr ,
       o58_subfuncao,
       o53_descr,
       o58_programa,
       o54_descr,
       o58_projativ,
       o55_descr,
       o58_elemento,
       o56_descr,
       o58_coddot,
       o58_codigo,
       o15_descr
       
";

// echo $sql;
// exit;
//$result = db_dotacaosaldo(8,1,$opcao,true,$sele_work,$anousu,$dataini,$datafin,$grupoini,$grupofin);
$result = pg_query($sql);
//db_criatabela($result);exit;


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$troca     =  1;
$alt       = 4;
$pagina    = 1;
$qualu     = 0;
$qualo     = 0;
$totpri   = 0;
$totseg   = 0;
$totter   = 0;
$totqua   = 0;
$totprio  = 0;
$totsego  = 0;
$tottero  = 0;
$totquao  = 0;
$totpriu  = 0;
$totsegu  = 0;
$totteru  = 0;
$totquau  = 0;

$funcao    = 0;
$subfuncao = 0;
$programa  = 0;
$projativ  = 0;
$elemento  = 0;
$recurso   = 0;

for($i=0;$i<pg_numrows($result);$i++){

  db_fieldsmemory($result,$i);
  
  if($o58_funcao == 0 && $o58_subfuncao == 0 && $o58_programa == 0 && $o58_projativ == 0){
    continue;
  }
  if (($tipo_agrupa == 3) ){
     if (($qualu != $o58_orgao.$o58_unidade)){
         $pagina = 1;
         $qualu = $o58_orgao.$o58_unidade;
         $pdf->setfont('arial','B',6);
  	 $pdf->ln(3);
         $pdf->cell(25,$alt,'',0,0,"R",0);
         $pdf->cell(80,$alt,'TOTAL DA UNIDADE',0,0,"L",0);
         $pdf->setfont('arial','',6);
         $pdf->cell(18,$alt,db_formatar($totpriu,'f'),0,0,"R",0);
         $pdf->cell(18,$alt,db_formatar($totsegu,'f'),0,0,"R",0);
         $pdf->cell(18,$alt,db_formatar($totteru,'f'),0,0,"R",0);
         $pdf->cell(18,$alt,db_formatar($totquau,'f'),0,0,"R",0);
         $pdf->cell(18,$alt,db_formatar($totpriu+$totsegu+$totteru+$totquau,'f'),0,1,"R",0);
	 $totpriu  = 0;
	 $totsegu = 0;
	 $totteru = 0;
	 $totquau = 0;
    }
  }
  if ($tipo_agrupa != 1){
     if($qualo != $o58_orgao){
       $pagina = 1;
       $qualo = $o58_orgao;
       $pdf->setfont('arial','b',6);
       $pdf->ln(3);
       $pdf->cell(25,$alt,'',0,0,"r",0);
       $pdf->cell(80,$alt,'TOTAL DO ÓRGÃO',0,0,"L",0);
       $pdf->setfont('arial','',6);
       $pdf->cell(18,$alt,db_formatar($totprio,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar($totsego,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar($tottero,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar($totquao,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar($totprio+$totsego+$tottero+$totquao,'f'),0,1,"R",0);
       $totprio  = 0;
       $totsego  = 0;
       $tottero  = 0;
       $totquao  = 0;
     }
  }
		


  if(!empty($qorgao)){
    if($tipo_agrupa==2){
      if($orgao != $qorgao){
        continue;
      }
    }
  }
  if(!empty($qunidade)){
    if($tipo_agrupa==3){
      if($orgao != $qorgao){
        continue;
      }
      if($unidade != $qunidade){
        continue;
      }
    }
  }
  
  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);

    if($tipo_agrupa!=1){
      $pdf->cell(0,0.5,'',"TB",1,"C",0);
      $pdf->cell(10,$alt,"ÓRGÃO  -  ".db_formatar($o58_orgao,'orgao').'  -  '.$o40_descr,0,1,"L",0);
      if($tipo_agrupa==2)
        $pdf->cell(0,0.5,'',"TB",1,"C",0);
    }
    if($tipo_agrupa==3){
      $pdf->cell(10,$alt,"UNIDADE ORÇAMENTÁRIA  -  ".db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao')."  -  ".$o41_descr,0,1,"L",0);
      $pdf->cell(0,0.5,'',"TB",1,"C",0);
    }
    $pdf->ln(2);
    $pdf->cell(25,$alt,"CÓDIGO",0,0,"L",0);
    $pdf->cell(80,$alt,"E S P E C I F I C A Ç Ã O",0,0,"C",0);
    $pdf->cell(18,$alt,"2006",0,0,"R",0);
    $pdf->cell(18,$alt,"2007",0,0,"R",0);
    $pdf->cell(18,$alt,"2008",0,0,"R",0);
    $pdf->cell(18,$alt,"2009",0,0,"R",0);
    $pdf->cell(18,$alt,'TOTAL',0,1,"R",0);
  }
 
  $pdf->setfont('arial','',6);
      if($o58_funcao != $funcao ){
	$funcao = $o58_funcao;
        $pdf->setfont('arial','',6);
        $descr = $o52_descr;
        $pdf->cell(25,$alt,db_formatar($o58_funcao,'orgao'),0,0,"L",0);
        $pdf->cell(80,$alt,$descr,0,1,"L",0);
      } 
      if($o58_subfuncao != $subfuncao){
	$subfuncao = $o58_subfuncao;
        $pdf->setfont('arial','',6);
        $descr = $o53_descr;
        $pdf->cell(25,$alt,db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
        $pdf->cell(80,$alt,$descr,0,1,"L",0);
      }
      if($o58_programa != $programa){
	$programa = $o58_programa;
        $pdf->setfont('arial','',6);
        $descr = $o54_descr;
        $pdf->cell(25,$alt,db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').'.'.db_formatar($o58_programa,'s','0',4,'e'),0,0,"L",0);
        $pdf->cell(80,$alt,$descr,0,1,"L",0);
      }
      if($o58_projativ != $projativ){
	$projativ = $o58_projativ;
        $pdf->setfont('arial','',6);
        $pdf->cell(25,$alt,db_formatar($o58_projativ,'atividade'),0,0,"R",0);
        $pdf->cell(80,$alt,$o55_descr,0,1,"L",0);
        $pdf->setfont('arial','',6);
      }
      if($o58_elemento != $elemento){
	$elemento = $o58_elemento;
        $pdf->setfont('arial','',6);
        $pdf->cell(25,$alt,db_formatar($o58_elemento,'elemento'),0,0,"L",0);
        $pdf->cell(80,$alt,$o56_descr,0,1,"L",0);
      }
    $pdf->cell(25,$alt,'',0,0,"L",0);
    $pdf->cell(80,$alt,$o58_codigo.'     '.$o15_descr,0,0,"L",0);
    $pdf->cell(18,$alt,db_formatar($pri,'f'),0,0,"R",0);
    $pdf->cell(18,$alt,db_formatar($seg,'f'),0,0,"R",0);
    $pdf->cell(18,$alt,db_formatar($ter,'f'),0,0,"R",0);
    $pdf->cell(18,$alt,db_formatar($qua,'f'),0,0,"R",0);
    $pdf->cell(18,$alt,db_formatar($pri+$seg+$ter+$qua,'f'),0,1,"R",0);
	$totpri   += $pri;
        $totseg   += $seg;
        $totter   += $ter;
        $totqua   += $qua;
	
	$totprio  += $pri;
        $totsego  += $seg;
        $tottero  += $ter;
        $totquao  += $qua;
	
	$totpriu  += $pri;
        $totsegu  += $seg;
        $totteru  += $ter;
        $totquau  += $qua;
}
if (($tipo_agrupa == 3) ){
   $pagina = 1;
   $qualu = $o58_orgao.$o58_unidade;
   $pdf->setfont('arial','B',6);
   $pdf->ln(3);
   $pdf->cell(25,$alt,'',0,0,"R",0);
   $pdf->cell(80,$alt,'TOTAL DA UNIDADE',0,0,"L",0);
   $pdf->setfont('arial','',6);
   $pdf->cell(18,$alt,db_formatar($totpriu,'f'),0,0,"R",0);
   $pdf->cell(18,$alt,db_formatar($totsegu,'f'),0,0,"R",0);
   $pdf->cell(18,$alt,db_formatar($totteru,'f'),0,0,"R",0);
   $pdf->cell(18,$alt,db_formatar($totquau,'f'),0,0,"R",0);
   $pdf->cell(18,$alt,db_formatar($totpriu+$totsegu+$totteru+$totquau,'f'),0,1,"R",0);
}
if ($tipo_agrupa != 1){
   $pagina = 1;
   $qualo = $o58_orgao;
   $pdf->setfont('arial','b',6);
   $pdf->ln(3);
   $pdf->cell(25,$alt,'',0,0,"r",0);
   $pdf->cell(80,$alt,'TOTAL DO ÓRGÃO',0,0,"l",0);
   $pdf->setfont('arial','',6);
   $pdf->cell(18,$alt,db_formatar($totprio,'f'),0,0,"R",0);
   $pdf->cell(18,$alt,db_formatar($totsego,'f'),0,0,"R",0);
   $pdf->cell(18,$alt,db_formatar($tottero,'f'),0,0,"R",0);
   $pdf->cell(18,$alt,db_formatar($totquao,'f'),0,0,"R",0);
   $pdf->cell(18,$alt,db_formatar($totprio+$totsego+$tottero+$totquao,'f'),0,1,"R",0);
  }
$pdf->ln(3);
$pdf->cell(25,$alt,'',0,0,"R",0);
$pdf->cell(80,$alt,'TOTAL GERAL',0,0,"L",0);
$pdf->setfont('arial','',6);
$pdf->cell(18,$alt,db_formatar($totpri,'f'),0,0,"R",0);
$pdf->cell(18,$alt,db_formatar($totseg,'f'),0,0,"R",0);
$pdf->cell(18,$alt,db_formatar($totter,'f'),0,0,"R",0);
$pdf->cell(18,$alt,db_formatar($totqua,'f'),0,0,"R",0);
$pdf->cell(18,$alt,db_formatar($totpri+$totseg+$totter+$totqua,'f'),0,1,"R",0);

// include("fpdf151/geraarquivo.php");

$pdf->Output();


?>