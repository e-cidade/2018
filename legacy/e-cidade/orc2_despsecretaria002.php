<?php
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

include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("fpdf151/pdf.php");
include("libs/db_sql.php");

$clselorcdotacao = new cl_selorcdotacao();
$opcao = 2;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$sInstit = str_replace("-",",",$db_selinstit);


$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in ({$sInstit}) ");
$descr_inst = '';
$xvirg      = '';

for ($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
  
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinstabrev;
  $xvirg = ',';
}

if ($tipo_balanco == 2) {
  $xtipo = "-EMPENHADO";
} else if($tipo_balanco == 3) {
  $xtipo = "-LIQUIDADO";
} else {
  $xtipo = "-PAGO";
}  

$head3 = "RESUMO DA DESPESA - $xtipo";
$head4 = "EXERCÍCIO: ".db_getsession("DB_anousu");
$head5 = "INSTITUIÇÕES : ".$descr_inst;

// funcao para gerar work

$nivel       = substr($nivelele,0,1);

$tipo_rel    = 2;
$tipoagrupar = 1;

switch ($tipoagrupa) {
  
  case '1': // Não

    $tipo_rel    = 1;
    $tipoagrupar = 1;
    $tipo_agrupa = 1;
    break;

  case '2': // Orgão
    
    $tipoagrupar = 2;
    $tipo_agrupa = 1;
    break;
  
  case '3': // Unidade
    
    $tipo_agrupa = 2;
    $tipoagrupar = 2;
    break;
}

if ($nivelele == '0') {
  
  $nivelele = '1';
  if ($tipoagrupa == '3') {
    
    $nivelele = '2';
    $tipo_rel    = 1;
  }
}
switch ($nivelele) {
  
  case '1':
    
    $ccampo  = "o58_orgao";
    $qcampo  = "o58_orgao";
    $cabec   = "Orgão";
    $ccampod = " o40_descr ";
    $relac   = " ";
    break;
    
  case '2':  
    $ccampo  = "o58_unidade";
    $qcampo  = "o58_unidade";
    $cabec   = "Unidade";
    $ccampod = " o41_descr ";
    $relac   = " ";
    break;
    
  case '3':
    
    $ccampo  = "o58_funcao";
    $qcampo  = "o58_funcao";
    $cabec   = "Função";
    $ccampod = " o52_descr ";
    $relac   = " inner join orcfuncao on orcdotacao.o58_funcao = orcfuncao.o52_funcao ";
    break;
  
  case '4':
    
    $ccampo  = "o58_subfuncao";
    $qcampo  = "o58_subfuncao";
    $cabec   = "Sub-Função";
    $ccampod = " o53_descr ";
    $relac   = " inner join orcsubfuncao on orcdotacao.o58_subfuncao = orcsubfuncao.o53_subfuncao ";
    break;
  
  case '5':
    
    $ccampo  = "o58_programa";
    $qcampo  = "o58_programa";
    $cabec   = "Programa";
    $ccampod = " o54_descr ";
    $relac   = "  inner join orcprograma on orcdotacao.o58_programa = orcprograma.o54_programa and orcprograma.o54_anousu = ".db_getsession("DB_anousu");
    break;
  
  case '6':
    
    $ccampo  = "o58_projativ";
    $qcampo  = "o58_projativ";
    $cabec   = "Proj/Ativ";
    $ccampod = " o55_descr ";
    $relac   = " inner join orcprojativ on orcdotacao.o58_projativ = orcprojativ.o55_projativ and orcprojativ.o55_anousu = ".db_getsession("DB_anousu");
    break;
  
  case '7':
    
    $ccampo  = "o56_elemento";
    $qcampo  = "o58_elemento";
    $cabec   = "Elemento";
    $ccampod = " o56_descr ";
    $relac   = "  inner join orcelemento on orcdotacao.o58_codele = orcelemento.o56_codele and orcdotacao.o58_anousu = orcelemento.o56_anousu  ";
  break;
  
  case '8':
    
    $ccampo  = "o58_codigo";
    $qcampo  = "o58_codigo";
    $cabec   = "Recurso";
    $ccampod = " o15_descr ";
    $relac   = "  inner join orctiporec on orcdotacao.o58_codigo = orctiporec.o15_codigo  ";
    break;
    
  case '9': 
    
   $ccampo  = "o56_elemento";
   $qcampo  = "o56_elemento";
   $cabec   =   "Desdobramento";
   $ccampod = " o56_descr";
   $relac   = "  inner join orcelemento on orcdotacao.o58_codele = orcelemento.o56_codele ";
   $relac  .= "  and orcdotacao.o58_anousu = orcelemento.o56_anousu ";
   break; 
}

if ($tipo_agrupa == 1) {
  
  $sql = "create temp table work as 
          select distinct o58_orgao as orgao, 0 as unidade, $ccampo as campo,$ccampod as descr,
	         0::float8 as vlr1,
	         0::float8 as vlr2,
	         0::float8 as vlr3,
	         0::float8 as vlr4,
	         0::float8 as vlr5,
	         0::float8 as vlr6,
	         0::float8 as vlr7,
	         0::float8 as vlr8,
	         0::float8 as vlr9,
	         0::float8 as vlr10,
	         0::float8 as vlr11,
		 0::float8 as vlr12
  	  from orcdotacao
	       inner join orcorgao on orcdotacao.o58_orgao = orcorgao.o40_orgao and orcorgao.o40_anousu = ".db_getsession("DB_anousu")." 
	       $relac
	  where o58_anousu = ".db_getsession("DB_anousu");
 if ($nivel == '9') {
      
      $sql  = " create temp table work as ";
      $sql .= "         select distinct o58_orgao as orgao, 0 as unidade, $ccampo as campo,$ccampod as descr,"; 
      $sql .= "                0::float8 as vlr1,";
      $sql .= "                0::float8 as vlr2,";
      $sql .= "                0::float8 as vlr3,";
      $sql .= "                0::float8 as vlr4,";
      $sql .= "                0::float8 as vlr5,";
      $sql .= "                0::float8 as vlr6,";
      $sql .= "                0::float8 as vlr7,";
      $sql .= "                0::float8 as vlr8,";
      $sql .= "                0::float8 as vlr9,";
      $sql .= "                0::float8 as vlr10,";
      $sql .= "                0::float8 as vlr11, ";
      $sql .= "                0::float8 as vlr12 "; 
      $sql .= "           from conlancamele";
      $sql .= "                inner join conlancam on c67_codlan=c70_codlan";
      $sql .= "                inner join conlancamemp on c75_codlan = c70_codlan";
      $sql .= "                inner join empempenho on e60_numemp = c75_numemp and e60_anousu=".db_getsession("DB_anousu");
      $sql .= "                inner join orcdotacao on o58_coddot = empempenho.e60_coddot  and o58_anousu = e60_anousu";
      $sql .= "                inner join conplano on c60_codcon = orcdotacao.o58_codele and c60_anousu=".db_getsession("DB_anousu");
      $sql .= "                inner join conlancamdoc on c71_codlan=c70_codlan";
      $sql .= "                inner join conhistdoc on c71_coddoc=c53_coddoc";
      $sql .= "                inner join orcelemento ele on ele.o56_codele=conlancamele.c67_codele "; 
      $sql .= "                                          and ele.o56_anousu = o58_anousu ";
      $sql .= "   where ";           
      $sql .= "         empempenho.e60_instit in ({$sInstit})";
      $sql .= "     and e60_anousu = ".db_getsession("DB_anousu");
      $sql .= "     and conhistdoc.c53_tipo in (10,11,20,21,30,31)";
      $sql .= "group by c60_estrut,";
      $sql .= "         c60_descr,";
      $sql .= "         o56_elemento,";
      $sql .= "         o56_descr,";    
      $sql .= "         o58_orgao,";    
      $sql .= "         o58_unidade ";    
      $sql .= "order by o56_elemento";
    }
} else {
  $sql = "create temp table work as 
          select distinct o58_orgao as orgao, o58_unidade as unidade, $ccampo as campo,$ccampod as descr, 
	         0::float8 as vlr1,
	         0::float8 as vlr2,
	         0::float8 as vlr3,
	         0::float8 as vlr4,
	         0::float8 as vlr5,
	         0::float8 as vlr6,
	         0::float8 as vlr7,
	         0::float8 as vlr8,
	         0::float8 as vlr9,
	         0::float8 as vlr10,
	         0::float8 as vlr11,
		 0::float8 as vlr12
  	  from orcdotacao 
	       inner join orcunidade on  orcdotacao.o58_orgao = orcunidade.o41_orgao and orcdotacao.o58_unidade = orcunidade.o41_unidade and orcunidade.o41_anousu = orcdotacao.o58_anousu 
	       $relac
	  where o58_anousu = ".db_getsession("DB_anousu");
	  
	  if ($nivel == '9') {
	    
	    $sql  = " create temp table work as ";
      $sql .= "         select distinct o58_orgao as orgao, o58_unidade as unidade, $ccampo as campo,$ccampod as descr,"; 
      $sql .= "                0::float8 as vlr1,";
      $sql .= "                0::float8 as vlr2,";
      $sql .= "                0::float8 as vlr3,";
      $sql .= "                0::float8 as vlr4,";
      $sql .= "                0::float8 as vlr5,";
      $sql .= "                0::float8 as vlr6,";
      $sql .= "                0::float8 as vlr7,";
      $sql .= "                0::float8 as vlr8,";
      $sql .= "                0::float8 as vlr9,";
      $sql .= "                0::float8 as vlr10,";
      $sql .= "                0::float8 as vlr11, ";
      $sql .= "                0::float8 as vlr12 "; 
      $sql .= "           from conlancamele";
      $sql .= "                inner join conlancam on c67_codlan=c70_codlan";
      $sql .= "                inner join conlancamemp on c75_codlan = c70_codlan";
      $sql .= "                inner join empempenho on e60_numemp = c75_numemp and e60_anousu=".db_getsession("DB_anousu");
      $sql .= "                inner join orcdotacao on o58_coddot = empempenho.e60_coddot  and o58_anousu = e60_anousu";
      $sql .= "                inner join conplano on c60_codcon = orcdotacao.o58_codele and c60_anousu=".db_getsession("DB_anousu");
      $sql .= "                inner join conlancamdoc on c71_codlan=c70_codlan";
      $sql .= "                inner join conhistdoc on c71_coddoc=c53_coddoc";
      $sql .= "                inner join orcelemento ele on ele.o56_codele=conlancamele.c67_codele "; 
      $sql .= "                                          and ele.o56_anousu = o58_anousu ";
      $sql .= "   where ";           
      $sql .= "         empempenho.e60_instit in ({$sInstit})";
      $sql .= "     and e60_anousu = ".db_getsession("DB_anousu");
      $sql .= "     and conhistdoc.c53_tipo in (10,11,20,21,30,31)";
      $sql .= "group by c60_estrut,";
      $sql .= "         c60_descr,";
      $sql .= "         o56_elemento,";
      $sql .= "         o56_descr,";    
      $sql .= "         o58_orgao,";    
      $sql .= "         o58_unidade ";    
      $sql .= "order by o56_elemento";
	  }
}
$result = db_query($sql);

$result = db_query("select * from work");
//db_criatabela($result);
//exit;

$clselorcdotacao->setDados($filtra_despesa);
$sele_work = str_replace("w.", "", $clselorcdotacao->getDados());


$anousu  = db_getsession("DB_anousu");

$where = "{$sele_work} and o58_instit in ({$sInstit})";


for($mes=1;$mes<13;$mes++){

  db_query("begin");
  $result_ultdia = db_query("select fc_ultimodiames(". db_getsession("DB_anousu") . ",$mes) as ultimodiames");
  db_fieldsmemory($result_ultdia,0,true);
  
  if ($nivel != 9) {
    
    $result_rec = db_dotacaosaldo(8, 2, $opcao, true, $where, $anousu, 
                                  db_getsession("DB_anousu")."-{$mes}-01",
                                  db_getsession("DB_anousu")."-{$mes}-$ultimodiames",
                                  2, $nivelele, null, $tipo_balanco, false);
  } else {
    
    $dtInicial = db_getsession("DB_anousu")."-$mes-01";
    $dtFinal   = db_getsession("DB_anousu")."-$mes-$ultimodiames";
    
    $sSqlDesdobramento  = "  select conplano.c60_estrut,";
    $sSqlDesdobramento .= "         conplano.c60_descr,";
    $sSqlDesdobramento .= "         orcdotacao.o58_orgao,";
    $sSqlDesdobramento .= "         orcdotacao.o58_unidade,";
    $sSqlDesdobramento .= "         ele.o56_elemento as o56_elemento,";
    $sSqlDesdobramento .= "         ele.o56_descr,";
    $sSqlDesdobramento .= "         sum(case when c53_tipo = 10  then c70_valor else 0 end ) as empenhado,";
    $sSqlDesdobramento .= "         sum(case when c53_tipo = 11  then c70_valor else 0 end ) as empenhado_estornado,";
    $sSqlDesdobramento .= "         sum(case when c53_tipo = 20  then c70_valor else 0 end ) as liquidado,";
    $sSqlDesdobramento .= "         sum(case when c53_tipo = 21  then c70_valor else 0 end ) as liquidado_estornado,";
    $sSqlDesdobramento .= "         sum(case when c53_tipo = 30  then c70_valor else 0 end ) as pagamento,";
    $sSqlDesdobramento .= "         sum(case when c53_tipo = 31  then c70_valor else 0 end ) as pagamento_estornado";              
    $sSqlDesdobramento .= "    from conlancamele";
    $sSqlDesdobramento .= "         inner join conlancam on c67_codlan=c70_codlan";
    $sSqlDesdobramento .= "         inner join conlancamemp on c75_codlan = c70_codlan";
    $sSqlDesdobramento .= "         inner join empempenho on e60_numemp = c75_numemp and e60_anousu=".db_getsession("DB_anousu")."";
    $sSqlDesdobramento .= "         inner join orcdotacao on o58_coddot = empempenho.e60_coddot  and o58_anousu = e60_anousu";
    $sSqlDesdobramento .= "         inner join conplano on c60_codcon = orcdotacao.o58_codele and c60_anousu=".db_getsession("DB_anousu")."";
    $sSqlDesdobramento .= "         inner join conlancamdoc on c71_codlan=c70_codlan";
    $sSqlDesdobramento .= "         inner join conhistdoc on c71_coddoc=c53_coddoc";
    $sSqlDesdobramento .= "         inner join orcelemento ele on ele.o56_codele=conlancamele.c67_codele "; 
    $sSqlDesdobramento .= "                                   and ele.o56_anousu = o58_anousu ";
    $sSqlDesdobramento .= "   where {$where} and ";           
    $sSqlDesdobramento .= "         empempenho.e60_instit in ({$sInstit})";
    $sSqlDesdobramento .= "     and ( conlancam.c70_data >='$dtInicial' and conlancam.c70_data <='$dtFinal' )";
    $sSqlDesdobramento .= "     and conhistdoc.c53_tipo in (10,11,20,21,30,31)";
    $sSqlDesdobramento .= "group by c60_estrut,";
    $sSqlDesdobramento .= "         c60_descr,";
    $sSqlDesdobramento .= "         o56_elemento,";
    $sSqlDesdobramento .= "         o56_descr,";    
    $sSqlDesdobramento .= "         o58_orgao,";    
    $sSqlDesdobramento .= "         o58_unidade ";    
    $sSqlDesdobramento .= "order by o56_elemento";
    
    $result_rec = db_query(analiseQueryPlanoOrcamento($sSqlDesdobramento));
  }
  db_query("rollback");
  $valor = 0;

  $iNumRows = pg_num_rows($result_rec);
  for ($i = 0; $i < $iNumRows; $i++) {
    
    db_fieldsmemory($result_rec, $i);
    if ($tipo_balanco == 1) {
      $valor = $dot_ini;
    } else if ($tipo_balanco == 2) {
      
      if ($nivel == '9') {
        $valor = $empenhado - $empenhado_estornado;
      } else {
        $valor = $empenhado - $anulado;
      }
    } else if ($tipo_balanco == 3) {
      
      if ($nivel == '9') {
        $valor = $liquidado - $liquidado_estornado;
      } else {
        $valor = $liquidado;
      }
    } else {
      
    if ($nivel == '9') {
        $valor = $pagamento - $pagamento_estornado;
      } else {
        $valor = $pago;
      }
    }
     
    if ($tipo_agrupa == 1 ) {
      $sql = "update work set vlr$mes = vlr$mes+$valor where work.campo = '".$$qcampo."' and work.orgao = ".$o58_orgao;
    } else {
      $sql = "update work set vlr$mes = vlr$mes+$valor where work.campo = '".$$qcampo."' and work.orgao = ".$o58_orgao." and work.unidade = ".$o58_unidade;
    }
    $result = db_query($sql);
  }  
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$qorgao = "";
$qunidade = "";

$rsWork = db_query("select * from work");
//db_criatabela($rsWork);exit;

if($tipo_rel==2) {
   if ($nivelele == 7 and $tipoagrupar == 1) {
     $sql = "select campo, descr,
                  sum(vlr1) as vlr1,
                  sum(vlr2) as vlr2,
                  sum(vlr3) as vlr3,
                  sum(vlr4) as vlr4,
                  sum(vlr5) as vlr5,
                  sum(vlr6) as vlr6,
                  sum(vlr7) as vlr7,
                  sum(vlr8) as vlr8,
                  sum(vlr9) as vlr9,
                  sum(vlr10) as vlr10,
                  sum(vlr11) as vlr11,
                  sum(vlr12) as vlr12
		from work 
		group by campo, descr";
     $result = db_query($sql);
   } else {
     $sql = "select * from work order by orgao,unidade,campo";
     $result = db_query($sql);
     $qorgao = pg_result($result,0,'orgao');
     $qunidade = pg_result($result,0,'unidade');
   }
} else {

  $sCampos = 'campo, descr';
  if ($tipoagrupa  == 3) {
    $sCampos = "orgao, unidade, descr";
  }
  $sql = "select {$sCampos},
                  sum(vlr1) as vlr1,
                  sum(vlr2) as vlr2,
                  sum(vlr3) as vlr3,
                  sum(vlr4) as vlr4,
                  sum(vlr5) as vlr5,
                  sum(vlr6) as vlr6,
                  sum(vlr7) as vlr7,
                  sum(vlr8) as vlr8,
                  sum(vlr9) as vlr9,
                  sum(vlr10) as vlr10,
                  sum(vlr11) as vlr11,
                  sum(vlr12) as vlr12
           from work 
	   group by $sCampos
	   order by $sCampos";
    $result = db_query($sql);
}


//db_criatabela($result);exit;
//aqui

$pagina = 1;

$qualou = "$qorgao$qunidade";

$totoper  = 0;

$ttvlr1 = 0;
$ttvlr2 = 0;
$ttvlr3 = 0;
$ttvlr4 = 0;
$ttvlr5 = 0;
$ttvlr6 = 0;
$ttvlr7 = 0;
$ttvlr8 = 0;
$ttvlr9 = 0;
$ttvlr10 = 0;
$ttvlr11 = 0;
$ttvlr12 = 0;

$tvlr1 = 0;
$tvlr2 = 0;
$tvlr3 = 0;
$tvlr4 = 0;
$tvlr5 = 0;
$tvlr6 = 0;
$tvlr7 = 0;
$tvlr8 = 0;
$tvlr9 = 0;
$tvlr10 = 0;
$tvlr11 = 0;
$tvlr12 = 0;

if ($tipo_rel==2) {

  $troca_secretaria = false;
  for ($i=0; $i < pg_num_rows($result); $i++) {
    
    db_fieldsmemory($result,$i);
    if ($vlr1+$vlr2+$vlr3+$vlr4+$vlr5+$vlr6+$vlr7+$vlr8+$vlr9+$vlr10+$vlr11+$vlr12 == 0){
      continue;
    }
    if (!($nivelele == 7 && $tipoagrupar == 1)) {
      
      if (("$qualou" != "$orgao$unidade") ) {
        
    	 $troca_secretaria = true;
    	 $qualou = "$orgao$unidade";
    	 $pdf->setfont('arial','B',6);
    	 $pdf->cell(75,$alt,"Total ",0,0,"L",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr1,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr2,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr3,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr4,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr5,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr6,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr7,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr8,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr9,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr10,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr11,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr12,'f'),0,0,"R",0);
    	 $pdf->cell(15,$alt,db_formatar($tvlr1+$tvlr2+$tvlr3+$tvlr4+$tvlr5+$tvlr6+$tvlr7+$tvlr8+$tvlr9+$tvlr10+$tvlr11+$tvlr12,'f'),0,1,"R",0);
    	 $tvlr1 = 0;
    	 $tvlr2 = 0;
    	 $tvlr3 = 0;
    	 $tvlr4 = 0;
    	 $tvlr5 = 0;
    	 $tvlr6 = 0;
    	 $tvlr7 = 0;
    	 $tvlr8 = 0;
    	 $tvlr9 = 0;
    	 $tvlr10 = 0;
    	 $tvlr11 = 0;
    	 $tvlr12 = 0;
      }
    }
    if($pdf->gety()>$pdf->h-30 || $pagina ==1){
      $pagina = 0;
      $pdf->addpage("L");
      $troca_secretaria = true;
    }
    if($troca_secretaria == true) {
      $troca_secretaria = false;

      if ($tipoagrupar == 2) {
        $pdf->setfont('arial','b',7);
        $sql  = "select o40_descr 
  	            	 from orcorgao 
		              where o40_anousu = ".db_getsession("DB_anousu")." 
		                and o40_orgao = ".$orgao;
        $resorg = db_query($sql);
        db_fieldsmemory($resorg,0);

        $pdf->cell(0,0.5,'',"TB",1,"C",0);
        $pdf->cell(10,$alt,db_formatar($orgao,'orgao'),0,0,"L",0);
        $pdf->cell(50,$alt,$o40_descr,0,1,"L",0);
        $pdf->setfont('arial','',6);

        if($tipo_agrupa=='2'){
	  $sql  = "select o41_descr 
		           from orcunidade 
		          where o41_anousu = ".db_getsession("DB_anousu")." 
			          and o41_orgao = ".$orgao." and o41_unidade = ".$unidade;
	  $resorg = db_query($sql);
	  db_fieldsmemory($resorg,0);
	  $pdf->cell(10,$alt,db_formatar($orgao,'orgao').db_formatar($unidade,'orgao'),0,0,"L",0);
	  $pdf->cell(50,$alt,$o41_descr,0,1,"L",0);
	  $pdf->cell(0,0.5,'',"TB",1,"C",0);
        }

      }

      $xx= 20;
      $pdf->setfont('arial','B',8);
      $pdf->cell($xx,$alt,$cabec,0,0,"R",0);
      $pdf->cell(55,$alt,"Descrição",0,0,"L",0);
      $pdf->cell(15,$alt,"Janeiro",0,0,"R",0);
      $pdf->cell(15,$alt,"Fevereiro",0,0,"R",0);
      $pdf->cell(15,$alt,"Março",0,0,"R",0);
      $pdf->cell(15,$alt,"Abril",0,0,"R",0);
      $pdf->cell(15,$alt,"Maio",0,0,"R",0);
      $pdf->cell(15,$alt,"Junho",0,0,"R",0);
      $pdf->cell(15,$alt,"Julho",0,0,"R",0);
      $pdf->cell(15,$alt,"Agosto",0,0,"R",0);
      $pdf->cell(15,$alt,"Setembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Outubro",0,0,"R",0);
      $pdf->cell(15,$alt,"Novembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Dezembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Total",0,1,"R",0);
    }
    $pdf->setfont('arial','',6);
    $pdf->cell(20,$alt,$campo,0,0,"R",0);
    $pdf->cell(55,$alt,substr($descr,0,40),0,0,"L",0);
    $pdf->cell(15,$alt,db_formatar($vlr1,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr2,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr3,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr4,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr5,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr6,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr7,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr8,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr9,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr10,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr11,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr12,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr1+$vlr2+$vlr3+$vlr4+$vlr5+$vlr6+$vlr7+$vlr8+$vlr9+$vlr10+$vlr11+$vlr12,'f'),0,1,"R",0);

    $tvlr1 += $vlr1;
    $tvlr2 += $vlr2;
    $tvlr3 += $vlr3;
    $tvlr4 += $vlr4;
    $tvlr5 += $vlr5;
    $tvlr6 += $vlr6;
    $tvlr7 += $vlr7;
    $tvlr8 += $vlr8;
    $tvlr9 += $vlr9;
    $tvlr10 += $vlr10;
    $tvlr11 += $vlr11;
    $tvlr12 += $vlr12;

    $ttvlr1 += $vlr1;
    $ttvlr2 += $vlr2;
    $ttvlr3 += $vlr3;
    $ttvlr4 += $vlr4;
    $ttvlr5 += $vlr5;
    $ttvlr6 += $vlr6;
    $ttvlr7 += $vlr7;
    $ttvlr8 += $vlr8;
    $ttvlr9 += $vlr9;
    $ttvlr10 += $vlr10;
    $ttvlr11 += $vlr11;
    $ttvlr12 += $vlr12;

    
  }
   $pdf->setfont('arial','',6);
   $pdf->cell(75,$alt,"Total ",0,0,"L",0);
   $pdf->cell(15,$alt,db_formatar($tvlr1,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr2,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr3,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr4,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr5,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr6,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr7,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr8,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr9,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr10,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr11,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr12,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr1+$tvlr2+$tvlr3+$tvlr4+$tvlr5+$tvlr6+$tvlr7+$tvlr8+$tvlr9+$tvlr10+$tvlr11+$tvlr12,'f'),0,1,"R",0);

} else {

  $troca_secretaria = false;
  for($i=0;$i<pg_numrows($result);$i++){

    db_fieldsmemory($result,$i);
    $pdf->setfont('arial','',6);
    if($pdf->gety()>$pdf->h-30 || $pagina ==1){
      $pagina = 0;
      $pdf->addpage("L");
      $pdf->setfont('arial','',6);
      $pdf->cell(20,$alt,"$cabec",0,0,"L",0);
      $pdf->cell(55,$alt,"Descrição",0,0,"L",0);
      $pdf->cell(15,$alt,"Janeiro",0,0,"R",0);
      $pdf->cell(15,$alt,"Fevereiro",0,0,"R",0);
      $pdf->cell(15,$alt,"Março",0,0,"R",0);
      $pdf->cell(15,$alt,"Abril",0,0,"R",0);
      $pdf->cell(15,$alt,"Maio",0,0,"R",0);
      $pdf->cell(15,$alt,"Junho",0,0,"R",0);
      $pdf->cell(15,$alt,"Julho",0,0,"R",0);
      $pdf->cell(15,$alt,"Agosto",0,0,"R",0);
      $pdf->cell(15,$alt,"Setembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Outubro",0,0,"R",0);
      $pdf->cell(15,$alt,"Novembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Dezembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Total",0,1,"R",0);
   }
   
   if ($tipoagrupa == 3) {
     $campo = "{$orgao}.{$unidade}";
   }
   $pdf->setfont('arial','b',6);
   $pdf->cell(20,$alt, $campo,0,0,"L",0);
   $pdf->cell(55,$alt,substr($descr, 0, 40),0,0,"L",0);
   $pdf->setfont('arial','',6);
   $pdf->cell(15,$alt,db_formatar($vlr1,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr2,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr3,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr4,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr5,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr6,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr7,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr8,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr9,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr10,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr11,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr12,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr1+$vlr2+$vlr3+$vlr4+$vlr5+$vlr6+$vlr7+$vlr8+$vlr9+$vlr10+$vlr11+$vlr12,'f'),0,1,"R",0);


      $ttvlr1 += $vlr1;
      $ttvlr2 += $vlr2;
      $ttvlr3 += $vlr3;
      $ttvlr4 += $vlr4;
      $ttvlr5 += $vlr5;
      $ttvlr6 += $vlr6;
      $ttvlr7 += $vlr7;
      $ttvlr8 += $vlr8;
      $ttvlr9 += $vlr9;
      $ttvlr10 += $vlr10;
      $ttvlr11 += $vlr11;
      $ttvlr12 += $vlr12;


  }

}
$pdf->setfont('arial','',6);
$pdf->cell(75,$alt,"Total Geral ",1,0,"L",0);
$pdf->cell(15,$alt,db_formatar($ttvlr1,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr2,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr3,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr4,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr5,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr6,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr7,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr8,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr9,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr10,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr11,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr12,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr1+$ttvlr2+$ttvlr3+$ttvlr4+$ttvlr5+$ttvlr6+$ttvlr7+$ttvlr8+$ttvlr9+$ttvlr10+$ttvlr11+$ttvlr12,'f'),1,1,"R",0);


//include("fpdf151/geraarquivo.php");
$pdf->Output();