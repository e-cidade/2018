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

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
      $descr_inst .= $xvirg.$nomeinstabrev ;
        $xvirg = ', ';
}


  $nivela = substr($nivel,0,1);
  $sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
  if ($nivela >= 1) {
    $sele_work .= " and exists (select 1 from t where t.o58_orgao = w.o58_orgao) ";
  }
  if ($nivela >= 2) {
    $sele_work .= " and exists (select 1 from t where t.o58_unidade = w.o58_unidade) ";
	}
  if ($nivela >= 3) {
    $sele_work .= " and exists (select 1 from t where t.o58_funcao = w.o58_funcao) ";
  }
  if ($nivela >= 4) {
    $sele_work .= " and exists (select 1 from t where t.o58_subfuncao = w.o58_subfuncao) ";
  }
  if ($nivela >= 5) {
    $sele_work .= " and exists (select 1 from t where t.o58_programa = w.o58_programa) ";
  }
  if ($nivela >= 6) {
    $sele_work .= " and exists (select 1 from t where t.o58_projativ = w.o58_projativ) ";
  }
  if ($nivela >= 7) {
    $sele_work .= " and exists (select 1 from t where t.o58_elemento = e.o56_elemento) ";
  }
  if ($nivela >= 8) {
    $sele_work .= " and exists (select 1 from t where t.o58_codigo = w.o58_codigo) ";
  }
 
  db_query("begin");
  db_query("create temp table t(o58_orgao int8,o58_unidade int8,o58_funcao int8,o58_subfuncao int8,o58_programa int8,o58_projativ int8,o58_elemento int8,o58_codigo int8)");
    
  $xcampos = split("-",$orgaos);
  
  for($i=0;$i < sizeof($xcampos);$i++){
     $where = '';
     $virgula = ''; 
     $xxcampos = split("_",$xcampos[$i]);
     for($ii=0;$ii<sizeof($xxcampos);$ii++){
        if($ii > 0){
          $where .= $virgula.$xxcampos[$ii];
    $virgula = ', ';
  }
     }
     if($nivela == 1)
       $where .= ",0,0,0,0,0,0,0";
     if($nivela == 2)
       $where .= ",0,0,0,0,0,0";
     if($nivela == 3)
       $where .= ",0,0,0,0,0";
     if($nivela == 4)
       $where .= ",0,0,0,0";
     if($nivela == 5)
       $where .= ",0,0,0";
     if($nivela == 6)
       $where .= ",0,0";
     if($nivela == 7)
       $where .= ",0";
     db_query("insert into t values($where)");
  }

db_query("commit");

$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

/*
 * Conforme o tipo de agrupamento definimos uma variavel sCampos e sOrdem, com os determinados campos
 * para mater a ordem correta dos codigos, e exibir os nomes do orgao e unidade
 */

if ($tipo_agrupa == 1) {
  
  $xagrupa = "Geral";
  $grupoini = 0;
  $grupofin = 3;
  $sCampo = "";
  $sOrdem = "";  
} else if ($tipo_agrupa == 2) {
  
  $xagrupa  = "Órgão";
  $grupoini = 1;
  $grupofin = 3;
  $sCampo = "o58_orgao, o40_descr, ";
  $sOrdem = "o58_orgao, o40_descr, ";  
} else {
  
  $xagrupa = "Unidade";
  $grupoini = 8;
  $grupofin = 0;
  
  $sCampo = " o58_orgao, o58_unidade, o41_descr,  o40_descr, ";
  $sOrdem = " o58_orgao, o58_unidade, o41_descr,  o40_descr, ";
}

$xtipo = 0;
if($origem == "O"){
  $xtipo = "ORÇAMENTO";
}else{
  $xtipo = "BALANÇO";
  if($opcao == 3)
    $head7 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
  else
    $head7 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}


$head2 = "DEMONSTRATIVO DA DESPESA POR";
$head3 = "FUNÇÃO/SUBFUNÇÃO/PROGRAMA";
$head4 = "ANEXO (8) EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;
$head5 = "INSTITUIÇÕES : ".$descr_inst;
$head6 = "AGRUPAMENTO : ".$xagrupa;

// o contador disse que deve sair o empenhado quando emitir a posição contábil
$tipo_balanco = 2; // empenhado
if ($origem == "O"){
  $tipo_balanco = 1;
}  

$sSql    = db_dotacaosaldo(5,1,3,true,$sele_work,$anousu,$dataini,$datafin,$grupoini,$grupofin,true,$tipo_balanco);

$sSql = "select o58_funcao,
                {$sCampo}
                o52_descr,
                o58_subfuncao,
                coalesce(trim(o53_descr),'') as o53_descr,
                o58_programa,
                coalesce(trim(o54_descr),'') as o54_descr,
                o58_projativ,
                trim(o55_descr) as o55_descr,
                o55_finali,
                o58_elemento,
                trim(o56_descr) as o56_descr,
                o58_coddot,
                o58_codigo,
                trim(o15_descr) as o15_descr,
                dot_ini,
                saldo_anterior,
                empenhado,
                anulado,
                liquidado,
                pago,
                suplementado,
                reduzido,
                atual,
                reservado,
                atual_menos_reservado,
                atual_a_pagar,
                atual_a_pagar_liquidado,
                empenhado_acumulado,
                anulado_acumulado,   
                liquidado_acumulado,   
                pago_acumulado,  
                suplementado_acumulado,  
                reduzido_acumulado,  
                proj,  
                ativ,  
                oper,  
                ordinario,   
                vinculado,
                suplemen,  
                suplemen_acumulado,  
                especial,
                especial_acumulado 
           from ({$sSql}) as final 
          order by {$sOrdem}
                   o58_funcao,
                   o52_descr,
                   o58_subfuncao,
                   o53_descr,
                   o58_programa,
                   o54_descr,
                   o58_projativ,
                   o55_descr,
                   o55_finali,
                   o58_elemento,
                   o56_descr,
                   o58_codigo,
                   o15_descr,
                   o58_coddot ";


$result = db_query($sSql);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);

$total    = 0;
$troca    = 1;
$alt      = 4;
$qualo    = 0;
$qualu    = 0;
$totproj  = 0;
$totativ  = 0;
$totprojo = 0;
$totativo = 0;
$totproju = 0;
$totativu = 0;
$pagina = 1;
for($i=0;$i<pg_numrows($result);$i++){

  db_fieldsmemory($result,$i);

  if(empty($o58_funcao)){
    continue;
  }
     
  if ($tipo_agrupa == 3){
    if ( $qualu != @$o58_orgao.@$o58_unidade ){
      $pagina = 1;
      $qualu = @$o58_orgao.@$o58_unidade;
      $pdf->setfont('arial','B',6);
      $pdf->ln(3);
      $pdf->cell(105,$alt,'TOTAL DA UNIDADE',0,0,"L",0);
      $pdf->setfont('arial','',6);
      $pdf->cell(25,$alt,db_formatar($totproju,'f'),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($totativu,'f'),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($totproju+$totativu,'f'),0,0,"R",0);
      $totproju = 0;
      $totativu = 0;
    }
  }
  if ($tipo_agrupa != 1){
    if ( $qualo != @$o58_orgao ){
      $pagina = 1;
      $qualo = @$o58_orgao;
      $pdf->setfont('arial','B',6);
      $pdf->ln(3);
      $pdf->cell(105,$alt,'TOTAL DO ÓRGÃO',0,0,"L",0);
      $pdf->setfont('arial','',6);
      $pdf->cell(25,$alt,db_formatar($totprojo,'f'),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($totativo,'f'),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($totprojo+$totativo,'f'),0,0,"R",0);
      $totprojo = 0;
      $totativo = 0;
    }
  }


  if ($pdf->gety()>$pdf->h-30 || $pagina ==1) {
     $pagina = 0;
     $pdf->addpage();
     $pdf->setfont('arial','b',7);
     if ($tipo_agrupa!=1) {
       $pdf->cell(0,0.5,'',"TB",1,"C",0);
       $pdf->cell(10,$alt,"ÓRGÃO   -  ".db_formatar(@$o58_orgao,'orgao')."  -  ".@$o40_descr,0,1,"L",0);
       if ($tipo_agrupa==2) {
         $pdf->cell(0,0.5,'',"TB",1,"C",0);
       }
     }
     if ($tipo_agrupa==3) {
       $pdf->cell(10,$alt,"UNIDADE ORÇAMENTÁRIA  -  ".db_formatar(@$o58_orgao,'orgao').db_formatar(@$o58_unidade,'orgao').'  -  '.@$o41_descr,0,1,"L",0);
       $pdf->cell(0,0.5,'',"TB",1,"C",0);
     }
     
     $pdf->ln(2);
     $pdf->cell(25,$alt,"CÓDIGO",0,0,"L",0);
     $pdf->cell(80,$alt,"E S P E C I F I C A Ç Ã O",0,0,"L",0);
     $pdf->cell(25,$alt,"ORDINÁRIO",0,0,"R",0);
     $pdf->cell(25,$alt,"VINCULADO",0,0,"R",0);
     $pdf->cell(25,$alt,"TOTAL",0,1,"R",0);
     $pdf->cell(0,$alt,'',"T",1,"C",0);
  }
  $pdf->setfont('arial','',6);
  
  if (empty($o56_projativ)) {
      if ($o58_programa == 0 && $o58_subfuncao == 0 ) {
         $pdf->setfont('arial','b',7);
         $descr = $o52_descr;
         $pdf->cell(25,$alt,db_formatar($o58_funcao,'funcao'),0,0,"L",0);
         $pdf->cell(80,$alt,$descr,0,0,"L",0);
         $pdf->setfont('arial','',6);
         $totproj  += $ordinario;
         $totativ  += $vinculado;
         $totprojo += $ordinario;
         $totativo += $vinculado;
         $totproju += $ordinario;
         $totativu += $vinculado;
      } elseif ($o54_descr=="") {
         $descr = $o53_descr;
         $pdf->cell(25,$alt,db_formatar($o58_funcao,'funcao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
         $pdf->cell(80,$alt,$descr,0,0,"L",0);
      } else {
        $descr = $o54_descr;
        $pdf->cell(25,$alt,db_formatar($o58_funcao,'funcao').".".db_formatar($o58_subfuncao,'s','0',3,'e').'.'.db_formatar($o58_programa,'s','0',4,'e'),0,0,"L",0);
        $pdf->cell(80,$alt,$descr,0,0,"L",0);
      }
       $pdf->cell(25,$alt,db_formatar($ordinario,'f'),0,0,"R",0);
       $pdf->cell(25,$alt,db_formatar($vinculado,'f'),0,0,"R",0);
       $pdf->cell(25,$alt,db_formatar($ordinario+$vinculado,'f'),0,1,"R",0);
     }
 }

if ($tipo_agrupa == 3){
    $pagina = 1;
    $qualu = @$o58_orgao.@$o58_unidade;
    $pdf->setfont('arial','B',6);
    $pdf->ln(3);
    $pdf->cell(105,$alt,'TOTAL DA UNIDADE',0,0,"L",0);
    $pdf->cell(25,$alt,db_formatar($totproju,'f'),0,0,"R",0);
    $pdf->cell(25,$alt,db_formatar($totativu,'f'),0,0,"R",0);
    $pdf->cell(25,$alt,db_formatar($totproju+$totativu,'f'),0,0,"R",0);
}
if ($tipo_agrupa != 1){
    $pagina = 1;
    $qualo = @$o58_orgao;
    $pdf->setfont('arial','B',6);
    $pdf->ln(3);
    $pdf->cell(105,$alt,'TOTAL DO ÓRGÃO',0,0,"L",0);
    $pdf->cell(25,$alt,db_formatar($totprojo,'f'),0,0,"R",0);
    $pdf->cell(25,$alt,db_formatar($totativo,'f'),0,0,"R",0);
    $pdf->cell(25,$alt,db_formatar($totprojo+$totativo,'f'),0,0,"R",0);
}
$pdf->ln(3);
$pdf->setfont('arial','B',6);
$pdf->cell(105,$alt,'TOTAL GERAL',0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($totproj,'f'),0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($totativ,'f'),0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($totproj+$totativ,'f'),0,0,"R",0);

$pdf->ln(14);

if ($origem != "O") {
  assinaturas($pdf, $classinatura,'BG');
}
$pdf->Output();
