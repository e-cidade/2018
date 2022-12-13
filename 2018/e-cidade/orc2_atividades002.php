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


include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$classinatura = new cl_assinatura;


//$tipo_agrupa = substr($nivel,0,1);

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
      $descr_inst .= $xvirg.$nomeinstabrev ;
        $xvirg = ', ';
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
// pesquisa a conta mae da receita
$head3 = "LISTAGEM DE ATIVIDADES/PROJETOS";
$head4 = "EXERCICIO: ".db_getsession("DB_anousu")." - ".$xtipo;
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$xcampos = split("-",$orgaos);


if(substr($nivel,0,1) == '1'){
  $xwhere1 = " trim(to_char(o58_orgao,'99')) in (";
}elseif(substr($nivel,0,1) == '2'){
  $xwhere1 = " trim(to_char(o58_orgao,'99'))||'.'||trim(to_char(o58_unidade,'99')) in (";
}elseif(substr($nivel,0,1) == '6'){
  $xwhere1 = " trim(to_char(o58_projativ,'9999999999999')) in (";
}
$virgula1 = ' ';
for($i=0;$i < sizeof($xcampos);$i++){
   $xxcampos = split("_",$xcampos[$i]);
   $virgula = '';
   $where  = "'";
   $where1 = "'";
   for($ii=0;$ii<sizeof($xxcampos);$ii++){
      if($ii > 0){
        $where  .= $virgula.$xxcampos[$ii];
        $where1 .= $virgula.$xxcampos[$ii];
        $virgula = '.';
      }
   }
   $xwhere1 .= $virgula1.$where1."'";
   $virgula1 = ', ';
   
}


$xwhere1 .= ") and o58_instit in (".str_replace('-',', ',$db_selinstit).")";



$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

$qorgao = 0;
$qunidade = 0;

$xordem = " ";       
if($tipo_agrupa ==1){
  $xxnivel = " 0 as o58_orgao, ''::varchar as o40_descr, 0 as o58_unidade, ''::varchar as o41_descr ";       
  $inicial = 0;
}elseif($tipo_agrupa==2){
  $xxnivel = " o58_orgao, o40_descr, 0 as o58_unidade, ''::varchar as o41_descr ";       
  $inicial = 1;
  $xordem = "  o58_orgao, o40_descr,";       
}elseif($tipo_agrupa==3){
  $xxnivel = " o58_orgao, o40_descr, o58_unidade, o41_descr";       
  $inicial = 2;
$xordem = "  o58_orgao, o40_descr, o58_unidade,o41_descr,";       
}

$teste = db_dotacaosaldo(8,1,3,true,$xwhere1,$anousu,$dataini,$datafin,$inicial,6,true);
if ($origem =="O"){
   $sql = "select distinct $xxnivel , o58_projativ,o55_descr,o58_elemento,o56_descr,
  		   (case when o58_projativ < 2000 and o58_projativ > 0     then dot_ini else 0 end) as proj,
		   (case when o58_projativ > 1999 and  o58_projativ < 3000 then dot_ini else 0 end) as ativ,
		   (case when o58_projativ > 2999 then dot_ini else 0 end) as oper
	   from ( $teste ) as fff 
	   where o58_coddot = 0
	   order by $xordem o58_projativ,o55_descr,o58_elemento,o56_descr
	   ";
}else{
   $sql = "select distinct $xxnivel , o58_projativ,o55_descr,o58_elemento,o56_descr,
  		   (case when o58_projativ < 2000 and o58_projativ > 0     then (empenhado-anulado) else 0 end) as proj,
		   (case when o58_projativ > 1999 and  o58_projativ < 3000 then (empenhado-anulado) else 0 end) as ativ,
		   (case when o58_projativ > 2999 then (empenhado-anulado) else 0 end) as oper
	   from ( $teste ) as fff
	   order by $xordem o58_projativ,o55_descr,o58_elemento,o56_descr
	   ";

}


$result = db_query($sql);
//db_criatabela($result);exit;


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca    = 1;
$alt      = 4;
$qualu    = 0;
$qualo    = 0;
$pagina   = 1;
$totprojo = 0;
$totativo = 0;
$totopero = 0;
$totproju = 0;
$totativu = 0;
$totoperu = 0;
$totprojg = 0;
$totativg = 0;
$totoperg = 0;
$qualpa   = 0;
		
for($i=0;$i<pg_numrows($result);$i++){

  db_fieldsmemory($result,$i);

  if(empty($o58_projativ)){
    continue;
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
  
  if ($qualu != $o58_orgao.$o58_unidade && $tipo_agrupa == 3 ){
     $pagina = 1;
     $qualu = $o58_orgao.$o58_unidade;
     $pdf->setfont('arial','B',6);
     $pdf->ln(3);
     $pdf->cell(25,'',0,0,"R",0);
     $pdf->cell(80,$alt,'TOTAL DA UNIDADE',0,0,"L",0);
     $pdf->setfont('arial','',6);
     $pdf->cell(25,$alt,db_formatar($totproju+$totativu+$totoperu,'f'),0,1,"R",0);
     $totproju  = 0;
     $totativu  = 0;
     $totoperu  = 0;
		     
  }
  if ($qualo != $o58_orgao && $tipo_agrupa != 1 ){
     $pagina = 1;
     $qualo = $o58_orgao;
     $pdf->setfont('arial','B',6);
     $pdf->ln(3);
     $pdf->cell(25,'',0,0,"R",0);
     $pdf->cell(80,$alt,'TOTAL DO ÓRGÃO',0,0,"L",0);
     $pdf->setfont('arial','',6);
     $pdf->cell(25,$alt,db_formatar($totprojo+$totativo+$totopero,'f'),0,1,"R",0);
     $totprojo  = 0;
     $totativo  = 0;
     $totopero  = 0;
		     
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
    $pdf->cell(80,$alt,"E S P E C I F I C A Ç Ã O",0,0,"L",0);
    $pdf->cell(25,$alt,"TOTAL",0,1,"R",0);
    $pdf->cell(130,$alt,'',"T",1,"C",0);
  }
 
  $pdf->setfont('arial','',6);
//  if(empty($o56_elemento)){
  if($qualpa != $o58_projativ){
     $qualpa = $o58_projativ;
     $pdf->setfont('arial','u',6);
     $pdf->cell(25,$alt,db_formatar($o58_projativ,'atividade'),0,0,"R",0);
     $pdf->cell(80,$alt,$o55_descr,0,0,"L",0);
     $pdf->setfont('arial','',6);
     $pdf->cell(25,$alt,db_formatar($proj+$ativ+$oper,'f'),0,1,"R",0);
     $totprojo  += $proj;
     $totativo  += $ativ;
     $totopero  += $oper;
     $totproju  += $proj;
     $totativu  += $ativ;
     $totoperu  += $oper;
     $totprojg  += $proj;
     $totativg  += $ativ;
     $totoperg  += $oper;
		     
  }else{
     $pdf->setfont('arial','',6);
     $pdf->cell(25,$alt,db_formatar($o58_elemento,'elemento'),0,0,"L",0);
     $pdf->cell(80,$alt,$o56_descr,0,0,"L",0);
     $pdf->cell(25,$alt,db_formatar($proj+$ativ+$oper,'f'),0,1,"R",0);

  }
}
if ($tipo_agrupa == 3 ){
   $pagina = 1;
   $qualu = $o58_orgao.$o58_unidade;
   $pdf->setfont('arial','B',6);
   $pdf->ln(3);
   $pdf->cell(25,'',0,0,"R",0);
   $pdf->cell(80,$alt,'TOTAL DA UNIDADE',0,0,"L",0);
   $pdf->setfont('arial','',6);
   $pdf->cell(25,$alt,db_formatar($totproju+$totativu+$totoperu,'f'),0,1,"R",0);
   $totproju  = 0;
   $totativu  = 0;
   $totoperu  = 0;
      	     
}
if ($tipo_agrupa != 1 ){
   $pagina = 1;
   $qualo = $o58_orgao;
   $pdf->setfont('arial','B',6);
   $pdf->ln(3);
   $pdf->cell(25,'',0,0,"R",0);
   $pdf->cell(80,$alt,'TOTAL DO ÓRGÃO',0,0,"L",0);
   $pdf->setfont('arial','',6);
   $pdf->cell(25,$alt,db_formatar($totprojo+$totativo+$totopero,'f'),0,1,"R",0);
   $totprojo  = 0;
   $totativo  = 0;
   $totopero  = 0;
      	     
}

$pdf->ln(3);
$pdf->cell(25,'',0,0,"R",0);
$pdf->cell(80,$alt,'TOTAL GERAL',0,0,"L",0);
$pdf->setfont('arial','',6);
$pdf->cell(25,$alt,db_formatar($totprojg+$totativg+$totoperg,'f'),0,1,"R",0);



$pdf->ln(14);

if($origem != "O"){

   assinaturas($pdf, $classinatura,'BG');

}

$pdf->Output();
