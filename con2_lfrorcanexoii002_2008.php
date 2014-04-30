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
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$anousu  = db_getsession("DB_anousu");
$instit  = db_getsession("DB_instit");
$xinstit = split("-",$db_selinstit);

$resultinst = pg_exec("select munic from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");

db_fieldsmemory($resultinst,0);
$descr_inst = "MUNICÍPIO DE ".$munic;
$classinatura = new cl_assinatura;

  $nivela = substr($nivel,0,1);
  $sele_work = ' o58_instit in ('.str_replace('-',', ',$db_selinstit).')';
  
	$sql_orgaos = "select distinct o41_orgao
		             from orcunidade 
			                inner join orcorgao on o41_orgao = o40_orgao and o40_anousu = $anousu
		             where o41_anousu = $anousu and o41_instit  in (".str_replace('-', ',', $db_selinstit).")";
  $res_orgaos  = @pg_query($sql_orgaos);
  $numrows     = @pg_numrows($res_orgaos);
  $orgaos      = "";
  $separador   = "";
  
  if ($numrows != false){
    for($i = 0; $i < $numrows; $i++){
      db_fieldsmemory($res_orgaos,$i);
      $orgaos   .= $separador."pai_".$o41_orgao;
      $separador = "-";
    }
  }

  pg_exec("begin");
  pg_exec("create temp table t(o58_orgao int8,o58_unidade int8,o58_funcao int8,o58_subfuncao int8,o58_programa int8,o58_projativ int8,o58_elemento int8,o58_codigo int8)");
    
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
     pg_exec("insert into t values($where)");
  }

pg_exec("commit");

$dados = data_periodo($anousu,$bimestre);

$periodo  = strtoupper($dados["periodo"]);
$perini   = split("-",$dados[0]);
$perfin   = split("-",$dados[1]);

$mesini   = strtoupper(db_mes($perini[1]));
$mesfin   = strtoupper(db_mes($perfin[1]));

$dataini  = $dados[0];
$datafin  = $dados[1];

$grupoini = 1;
$grupofin = 3;

$xtipo  = "BALANÇO";
$origem = "B";

$head2 = $descr_inst;
$head3 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head4 = "DEMONSTRATIVO DA EXECUÇÃO DAS DESPESAS POR FUNÇÃO/SUBFUNÇÃO";
$head5 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$head6 = "JANEIRO A ".$mesfin."/".$anousu." - ".$periodo." ".$mesini."-".$mesfin;
$result_dot = db_dotacaosaldo(4, 2 , 4  ,true ,$sele_work." and substr(o56_elemento,4,2) != '91'",$anousu,$dataini,$datafin,8,0,true,1,false);
$sql = " select
            o58_funcao,
	    o52_descr,
	    o58_subfuncao,
	    o53_descr,
	    sum(dot_ini) as dot_ini_p,
	    sum(suplementado_acumulado) as suplementado_p,
	    sum(reduzido_acumulado) as reduzir_p,
	    sum(empenhado) as empenhado_p,
	    sum(anulado) as anulado_p,
	    sum(empenhado_acumulado) as empenhado_acumulado_p,
	    sum(anulado_acumulado) as anulado_acumulado_p,
	    sum(liquidado) as liquidado_p,
	    sum(liquidado_acumulado) as liquidado_acumulado_p,
	    sum(empenhado_acumulado-anulado_acumulado-liquidado_acumulado) as inscrito_p
	 from ($result_dot) as x
	 group by
	     o58_subfuncao,o53_descr,o58_funcao,o52_descr
	 order by 
	    o58_funcao,
	    o58_subfuncao
       ";

$sql_grup = " select
            o58_funcao,
	    o52_descr,
	    sum(dot_ini)             as dot_ini_s,
	    sum(suplementado_acumulado)        as suplementado_s,
	    sum(reduzido_acumulado)            as reduzir,
	    sum(empenhado)           as empenhado_s,
	    sum(anulado)             as anulado_s,
	    sum(empenhado_acumulado) as empenhado_acumulado_s,
	    sum(anulado_acumulado)   as anulado_acumulado_s,
	    sum(liquidado)           as liquidado_s,
	    sum(liquidado_acumulado) as liquidado_acumulado_s
	 from ($result_dot) as x
	 group by
	     o58_funcao, o52_descr
	 order by 
	    o58_funcao
       ";
      
$result_grup = pg_exec($sql_grup);
$result = pg_exec($sql);
//db_criatabela($result_grup);
//db_criatabela($result);exit;


// despesa intraorcamentaria
$result_dot_intra = db_dotacaosaldo(4, 2 , 4  ,true ,$sele_work." and substr(o56_elemento,4,2) = '91'",$anousu,$dataini,$datafin,8,0,true,1,false);

$sql = " select
            o58_funcao,
	    o52_descr,
	    o58_subfuncao,
	    o53_descr,
	    sum(dot_ini) as dot_ini_p,
	    sum(suplementado_acumulado) as suplementado_p,
	    sum(reduzido_acumulado) as reduzir_p,
	    sum(empenhado) as empenhado_p,
	    sum(anulado) as anulado_p,
	    sum(empenhado_acumulado) as empenhado_acumulado_p,
	    sum(anulado_acumulado) as anulado_acumulado_p,
	    sum(liquidado) as liquidado_p,
	    sum(liquidado_acumulado) as liquidado_acumulado_p,
	    sum(empenhado_acumulado-anulado_acumulado-liquidado_acumulado) as inscrito_p
	 from ($result_dot_intra) as x
	 group by
	     o58_subfuncao,o53_descr,o58_funcao,o52_descr
	 order by 
	    o58_funcao,
	    o58_subfuncao
       ";

$sql_grup = " select
            o58_funcao,
	    o52_descr,
	    sum(dot_ini)             as dot_ini_s,
	    sum(suplementado_acumulado)        as suplementado_s,
	    sum(reduzido_acumulado)            as reduzir,
	    sum(empenhado)           as empenhado_s,
	    sum(anulado)             as anulado_s,
	    sum(empenhado_acumulado) as empenhado_acumulado_s,
	    sum(anulado_acumulado)   as anulado_acumulado_s,
	    sum(liquidado)           as liquidado_s,
	    sum(liquidado_acumulado) as liquidado_acumulado_s,
	    sum(empenhado_acumulado-anulado_acumulado-liquidado_acumulado) as inscrito_s
	 from ($result_dot_intra) as x
	 group by
	     o58_funcao, o52_descr
	 order by 
	    o58_funcao
       ";
      
$result_grup_intra = pg_exec($sql_grup);
$result_intra = pg_exec($sql);



$soma1 = 0;   
$soma2 = 0;
$soma3 = 0;
$soma4 = 0;
$soma5 = 0;
$soma6 = 0;
$totalae = 0;
$y =0;
db_fieldsmemory($result,0);
$func_muda = $o58_funcao;
$total_e = 0;
for($y=0;$y<pg_numrows($result_grup);$y++){
   db_fieldsmemory($result_grup,$y);
   $soma_dot[$y]       = $dot_ini_s;
   $soma_dot_at[$y]    = $dot_ini_s + $suplementado_s - $reduzir;//
   $soma_emp[$y]       = $empenhado_s - $anulado_s;
   $soma_liq[$y]       = $liquidado_s;
   $soma_emp_ac[$y]    = $empenhado_acumulado_s - $anulado_acumulado_s;
   $soma_liq_ac[$y]    = $liquidado_acumulado_s;
   if ($bimestre == "6B") {
     $soma_inscritos[$y] = abs($empenhado_acumulado_s - $anulado_acumulado_s - $liquidado_acumulado_s);
   } else {
     $soma_inscritos[$y] = 0;
   }
   $total_e += $liquidado_acumulado_s+$soma_inscritos[$y];  
}

$total_e_intra = 0;
for($y=0;$y<pg_numrows($result_grup_intra);$y++){
   db_fieldsmemory($result_grup_intra,$y);
   $soma_dot_intra[$y]       = $dot_ini_s;
   $soma_dot_at_intra[$y]    = $dot_ini_s + $suplementado_s - $reduzir;//
   $soma_emp_intra[$y]       = $empenhado_s - $anulado_s;
   $soma_liq_intra[$y]       = $liquidado_s;
   $soma_emp_ac_intra[$y]    = $empenhado_acumulado_s - $anulado_acumulado_s;
   $soma_liq_ac_intra[$y]    = $liquidado_acumulado_s;
   if ($bimestre == "6B") {
     $soma_inscritos_intra[$y] = abs($empenhado_acumulado_s - $anulado_acumulado_s - $liquidado_acumulado_s);
   } else {
     $soma_inscritos_intra[$y] = 0;
   }
   $total_e_intra += $liquidado_acumulado_s+$soma_inscritos_intra[$y];  
}


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->SetAutoPageBreak(0);
$pdf->setfillcolor(235);

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

$pdf->addpage();
/*
 * Definimos os tamanhos das celulas
 */

$iTamanhoFonte = 5;
$iTamanhoDotacaoInicial       = 20;
$iTamanhoDotacaoAtualizado    = 20;
$iTamanhoEmpenhadoNoBimestre  = 20;
$iTamanhoEmpenhadoAteBimestre = 20;
$iTamanhoLiquidadoNoBimestre  = 17;
$iTamanhoLiquidadoAteBimestre = 17;
$iTamanhoInscritos            = 0;
$iTamanhoTotalSobreB          = 8;
$iTamanhoTotalBSobreA         = 8;
$iTamanhoSaldoLiquidar        = 20;

if ($bimestre == "6B") {
   
  $iTamanhoDotacaoInicial       = 16;
  $iTamanhoDotacaoAtualizado    = 16;
  $iTamanhoEmpenhadoNoBimestre  = 16;
  $iTamanhoEmpenhadoAteBimestre = 16;
  $iTamanhoLiquidadoNoBimestre  = 16;
  $iTamanhoLiquidadoAteBimestre = 16;
  $iTamanhoTotalSobreB          = 10;
  $iTamanhoInscritos            = 18;
  $iTamanhoSaldoLiquidar        = 18;

}

$pdf->setfont('arial','',4);
$pdf->ln(2);
$pdf->cell(1,$alt,'RREO - Anexo II (LRF, Art. 52, inciso II, alínea "c")',"B",0,"L",0);
$pdf->cell(190,$alt,'R$ 1,00',"B",1,"R",0);
cabecalho($bimestre,&$pdf); 
db_fieldsmemory($result,0);
$funcao = 0;
$soma_dot_ini = 0;
$soma_atualizada = 0;
$soma_nobempenhado = 0;
$soma_aempenhado = 0;
$soma_nobliquidado = 0;
$soma_aliquidado = 0;
$soma_inscrito   = 0;
$ae = 0;
$soma_totalae =0;
$func_muda = 0; //$o58_funcao;
$subfunc_muda = $o58_subfuncao;   
$y = 0; 
$dot_inis             = 0;
$suplementados        = 0;
$empenhados           = 0;
$anulados             = 0;
$empenhado_acumulados = 0;
$anulado_acumulados   = 0;
$liquidados           = 0;
$liquidado_acumulados = 0;

//Variaveis para os totalizadores das despesas intraorçamentarias
$xsoma_dot_ini = 0;
$xsoma_atualizada = 0;
$xsoma_nobempenhado = 0;
$xsoma_aempenhado = 0;
$xsoma_nobliquidado = 0;
$xsoma_aliquidado = 0;
$xsoma_inscritos  = 0;
$soma_inscrito          = 0;
$pdf->setfont('arial','B',5);
$pos_exceto_intra = $pdf->getY();
 

for ($i=0;$i<pg_numrows($result);$i++) {
  
  db_fieldsmemory($result,$i);
  $coltotal = $liquidado_acumulado_p;
  $soma_dot_ini       = $soma_dot_ini      + $dot_ini_p;
  $soma_atualizada    = $soma_atualizada   + (($dot_ini_p + $suplementado_p) - $reduzir_p);
  $soma_nobempenhado  = $soma_nobempenhado + ($empenhado_p - $anulado_p);
  $soma_aempenhado    = $soma_aempenhado   + ($empenhado_acumulado_p - $anulado_acumulado_p);
  $soma_nobliquidado  = $soma_nobliquidado + $liquidado_p;
  $soma_aliquidado    = $soma_aliquidado   + $liquidado_acumulado_p;
  if ($bimestre == "6B") {
    $soma_inscrito     += abs($empenhado_acumulado_p - $anulado_acumulado_p - $liquidado_acumulado_p);
  }
}
for($i=0;$i<pg_numrows($result_intra);$i++){
  
  db_fieldsmemory($result_intra,$i);
  $coltotal = $liquidado_acumulado_p;
  $xsoma_dot_ini      = $xsoma_dot_ini      + $dot_ini_p;
  $xsoma_atualizada   = $xsoma_atualizada   + (($dot_ini_p + $suplementado_p) - $reduzir_p);
  $xsoma_nobempenhado = $xsoma_nobempenhado + ($empenhado_p - $anulado_p);
  $xsoma_aempenhado   = $xsoma_aempenhado   + ($empenhado_acumulado_p - $anulado_acumulado_p);
  $xsoma_nobliquidado = $xsoma_nobliquidado + $liquidado_p;
  $xsoma_aliquidado   = $xsoma_aliquidado   + $liquidado_acumulado_p;
  if ($bimestre == "6B") {
    $xsoma_inscritos   += abs($empenhado_acumulado_p - $anulado_acumulado_p - $liquidado_acumulado_p);
  }
}
$nTotalDespesasLiquidadas = ($xsoma_inscritos+$soma_inscrito+$xsoma_aliquidado+$soma_aliquidado);
$pdf->cell(40,$alt,"DESPESAS(EXCETO INTRA-ORÇAM.)(I)","R",0,"L",0);
$pdf->setfont('arial','B',5);
$pdf->cell($iTamanhoDotacaoInicial      , $alt, db_formatar($soma_dot_ini,'f'),"LRT",0,"R",0);
$pdf->cell($iTamanhoDotacaoAtualizado   , $alt, db_formatar($soma_atualizada,'f'),"LRT",0,"R",0);
$pdf->cell($iTamanhoEmpenhadoNoBimestre , $alt, db_formatar($soma_nobempenhado,'f'),"LRT",0,"R",0);
$pdf->cell($iTamanhoEmpenhadoAteBimestre, $alt, db_formatar($soma_aempenhado,'f') ,"LRT",0,"R",0);
$pdf->cell($iTamanhoLiquidadoNoBimestre , $alt, db_formatar($soma_nobliquidado,'f'),"LRT",0,"R",0);
$pdf->cell($iTamanhoLiquidadoAteBimestre, $alt, db_formatar($soma_aliquidado,'f'),"LRT",0,"R",0);
if ($bimestre == "6B") {
  $pdf->cell($iTamanhoInscritos, $alt, db_formatar($soma_inscrito,'f'),"LRT",0,"R",0);
}
$nTotalLinha    = ($soma_aliquidado+$soma_inscrito);
$nTotalDespesas = ($soma_inscrito+$xsoma_inscritos)+($xsoma_aliquidado+$soma_aliquidado);
$nPercentual    = (($nTotalLinha)/($nTotalDespesas))*100;
$pdf->cell($iTamanhoTotalSobreB, $alt, db_formatar($nPercentual,'f'),"LRT",0,"R",0);
@$ttotalae = (($soma_inscrito+$soma_aliquidado)/$soma_atualizada)*100;
$pdf->cell($iTamanhoTotalBSobreA , $alt, db_formatar($ttotalae,"f"),"LRT",0,"R",0);
$pdf->cell($iTamanhoSaldoLiquidar, $alt, db_formatar($soma_atualizada - ($soma_inscrito+$soma_aliquidado),'f'),"LT",1,"R",0);
/*
$soma_dot_ini = 0;
$soma_atualizada = 0;
$soma_nobempenhado = 0;
$soma_aempenhado = 0;
$soma_nobliquidado = 0;
$soma_aliquidado = 0;

*/  
for($i=0;$i<pg_numrows($result);$i++) {
  
  $ae = 0;
  $atotal =0;
  db_fieldsmemory($result,$i);
  if($pdf->gety() > $pdf->h-35) {
    
     $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","T",1,"R",0); 
     $pdf->cell(190,$alt,'',"T",1,"L",0);
     
     $pdf->addpage();
     $pdf->ln(2);
     $pdf->cell(190,$alt,'Continuação '.($pdf->pageNo()-1)."/{nb}","B",1,"R",0);
     $pdf->cell(1,$alt,'RREO - Anexo II (LRF, Art. 52, inciso II, alínea "c")',"B",0,"L",0);
     $pdf->cell(190,$alt,'R$ 1,00',"B",1,"R",0);
     cabecalho($bimestre,&$pdf); 
    #funcao....
  
  }

  if ($o58_funcao != $func_muda) {
   
    $pdf->setfont('arial','B',5);
    $pdf->cell(40,$alt,$o52_descr,"R",0,"L",0);
    $pdf->cell($iTamanhoDotacaoInicial      , $alt, db_formatar($soma_dot[$y],'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoDotacaoAtualizado   , $alt, db_formatar($soma_dot_at[$y],'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoEmpenhadoNoBimestre , $alt, db_formatar($soma_emp[$y],'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoEmpenhadoAteBimestre, $alt, db_formatar($soma_emp_ac[$y],'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoLiquidadoNoBimestre , $alt, db_formatar($soma_liq[$y],'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoLiquidadoAteBimestre, $alt, db_formatar($soma_liq_ac[$y],'f'),"LR",0,"R",0); 
    if ($bimestre == "6B") {
      $pdf->cell($iTamanhoInscritos, $alt, db_formatar($soma_inscritos[$y],'f'),"LR",0,"R",0);
    }
   
    @$etotal = (($soma_inscritos[$y]+$soma_liq_ac[$y])/($nTotalDespesasLiquidadas))*100;
    $pdf->cell($iTamanhoTotalSobreB,$alt,db_formatar($etotal,'f'),"LR",0,"R",0);
    @$ae = (($soma_inscritos[$y]+$soma_liq_ac[$y])/$soma_dot_at[$y])*100;
    $func_muda = $o58_funcao;
    $pdf->cell($iTamanhoTotalBSobreA,$alt,db_formatar($ae,'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoSaldoLiquidar,$alt,db_formatar($soma_dot_at[$y]-($soma_inscritos[$y]+$soma_liq_ac[$y]),'f'),0,1,"R",0);
    $y++;

  }
  $pdf->setfont('arial','',5);
  $pdf->cell(40,$alt,"   ".substr($o53_descr,0,32),"R",0,"L",0);
  $pdf->cell($iTamanhoDotacaoInicial      , $alt,db_formatar($dot_ini_p,'f'),"LR",0,"R",0);
  $pdf->cell($iTamanhoDotacaoAtualizado   , $alt,db_formatar($dot_ini_p + $suplementado_p - $reduzir_p,'f'),"LR",0,"R",0);
  $pdf->cell($iTamanhoEmpenhadoNoBimestre , $alt,db_formatar($empenhado_p - $anulado_p,'f'),"LR",0,"R",0);
  $pdf->cell($iTamanhoEmpenhadoAteBimestre, $alt,db_formatar($empenhado_acumulado_p - $anulado_acumulado_p,'f'),"LR",0,"R",0);
  $pdf->cell($iTamanhoLiquidadoNoBimestre , $alt,db_formatar($liquidado_p,'f'),"LR",0,"R",0);
  $pdf->cell($iTamanhoLiquidadoAteBimestre, $alt,db_formatar($liquidado_acumulado_p,'f'),"LR",0,"R",0);
  if ($bimestre == "6B") {
    $pdf->cell($iTamanhoInscritos, $alt, db_formatar(abs($inscrito_p),'f'),"LR",0,"R",0);
  } else {
    $inscrito_p = 0; 
  }
  @$etotal = (($inscrito_p+$liquidado_acumulado_p)/$nTotalDespesasLiquidadas)*100;
  $pdf->cell($iTamanhoTotalSobreB,$alt,db_formatar($etotal,'f'),"LR",0,"R",0);
  if (($dot_ini_p + $suplementado_p - $reduzir_p) != 0) {
    $ae = (($inscrito_p+$liquidado_acumulado_p)/($dot_ini_p + $suplementado_p - $reduzir_p))*100;
  } else {
    $ae =0;
  }
  $pdf->cell($iTamanhoTotalBSobreA,$alt,db_formatar($ae,'f'),"LR",0,"R",0);
  $pdf->cell($iTamanhoSaldoLiquidar,$alt,db_formatar(($dot_ini_p + $suplementado_p - $reduzir_p)-($inscrito_p+$liquidado_acumulado_p),'f'),0,1,"R",0);
  
  $coltotal          = $liquidado_acumulado_p;
  /*$soma_dot_ini      = $soma_dot_ini      + $dot_ini_p;
  $soma_atualizada   = $soma_atualizada   + (($dot_ini_p + $suplementado_p) - $reduzir_p);
  $soma_nobempenhado = $soma_nobempenhado + ($empenhado_p - $anulado_p);
  $soma_aempenhado   = $soma_aempenhado   + ($empenhado_acumulado_p - $anulado_acumulado_p);
  $soma_nobliquidado = $soma_nobliquidado + $liquidado_p;
  $soma_aliquidado   = $soma_aliquidado   + $liquidado_acumulado_p;
  $soma_inscrito     += $empenhado_acumulado_p -$anulado_acumulado_p - $liquidado_acumulado_p ;
*/
  
}

// intra orcamentaria
$pdf->setfont('arial','B',5);



$pdf->cell(40,$alt,"DESPESAS (INTRA-ORÇAMENTÁRIA)(I)","R",0,"L",0);
$pdf->setfont('arial','B',5);
$pdf->cell($iTamanhoDotacaoInicial      , $alt, db_formatar($xsoma_dot_ini,'f'),"LR",0,"R",0);
$pdf->cell($iTamanhoDotacaoAtualizado   , $alt, db_formatar($xsoma_atualizada,'f'),"LR",0,"R",0);
$pdf->cell($iTamanhoEmpenhadoNoBimestre , $alt, db_formatar($xsoma_nobempenhado,'f'),"LR",0,"R",0);
$pdf->cell($iTamanhoEmpenhadoAteBimestre, $alt, db_formatar($xsoma_aempenhado,'f') ,"LR",0,"R",0);
$pdf->cell($iTamanhoLiquidadoNoBimestre , $alt, db_formatar($xsoma_nobliquidado,'f'),"LR",0,"R",0);
$pdf->cell($iTamanhoLiquidadoAteBimestre, $alt, db_formatar($xsoma_aliquidado,'f'),"LR",0,"R",0);
if ($bimestre == "6B") {
  $pdf->cell($iTamanhoInscritos, $alt, db_formatar($xsoma_inscritos,'f'),"LR",0,"R",0);
}
$nPercentualIntra = (($xsoma_aliquidado+$xsoma_inscritos)/$nTotalDespesasLiquidadas)*100;
$pdf->cell($iTamanhoTotalSobreB, $alt, db_formatar($nPercentualIntra,'f'),"LR",0,"R",0);
@$ttotalae = (($xsoma_inscritos+$xsoma_aliquidado)/$xsoma_atualizada)*100;
$pdf->cell($iTamanhoTotalBSobreA , $alt, db_formatar($ttotalae,"f"),"LR",0,"R",0);
$pdf->cell($iTamanhoSaldoLiquidar, $alt, db_formatar($xsoma_atualizada - ($xsoma_inscritos+$xsoma_aliquidado),'f'),"L",1,"R",0);

$y = 0; 
for($i=0;$i<pg_numrows($result_intra);$i++) {
  
  $ae = 0;
  $atotal =0;
  db_fieldsmemory($result_intra,$i);
  
  if ($pdf->gety() > $pdf->h-35) {
    
     $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","T",1,"R",0); 
     $pdf->cell(190,$alt,'',"T",1,"L",0);
     
     $pdf->addpage();
     $pdf->ln(2);
     $pdf->cell(190,$alt,'Continuação '.($pdf->pageNo()-1)."/{nb}","B",1,"R",0);
     $pdf->cell(1,$alt,'RREO - Anexo II (LRF, Art. 52, inciso II, alínea "c")',"B",0,"L",0);
     $pdf->cell(190,$alt,'R$ 1,00',"B",1,"R",0);
     cabecalho($bimestre,&$pdf); 
  
  }

  if ($o58_funcao != $func_muda) {
    
    $pdf->setfont('arial','B',5);
    $pdf->cell(40,$alt,$o52_descr,"R",0,"L",0);
    $pdf->cell($iTamanhoDotacaoInicial      , $alt, db_formatar($soma_dot_intra[$y],'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoDotacaoAtualizado   , $alt, db_formatar($soma_dot_at_intra[$y],'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoEmpenhadoNoBimestre , $alt, db_formatar($soma_emp_intra[$y],'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoEmpenhadoAteBimestre, $alt, db_formatar($soma_emp_ac_intra[$y],'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoLiquidadoNoBimestre , $alt, db_formatar($soma_liq_intra[$y],'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoLiquidadoAteBimestre, $alt, db_formatar($soma_liq_ac_intra[$y],'f'),"LR",0,"R",0); 
    if ($bimestre == "6B") {
      $pdf->cell($iTamanhoInscritos, $alt, db_formatar($soma_inscritos_intra[$y],'f'),"LR",0,"R",0);
    }
    
    @$etotal = (($soma_inscritos_intra[$y]+$soma_liq_ac_intra[$y])/$nTotalDespesasLiquidadas)*100;
    $pdf->cell($iTamanhoTotalSobreB,$alt,db_formatar($etotal,'f'),"LR",0,"R",0);
    @$ae = (($soma_inscritos_intra[$y]+$soma_liq_ac[$y])/$soma_dot_at_intra[$y])*100;
    $func_muda = $o58_funcao;
    $pdf->cell($iTamanhoTotalBSobreA,$alt,db_formatar($ae,'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoSaldoLiquidar,$alt,db_formatar($soma_dot_at_intra[$y]-($soma_inscritos_intra[$y]+$soma_liq_ac_intra[$y]),'f'),"L",1,"R",0);
    $y++;
  
  }
  
    $pdf->setfont('arial','',5);
    $pdf->cell(40,$alt,"   ".substr($o53_descr,0,32),"R",0,"L",0);
    $pdf->cell($iTamanhoDotacaoInicial,$alt,db_formatar($dot_ini_p,'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoDotacaoAtualizado,$alt,db_formatar($dot_ini_p + $suplementado_p - $reduzir_p,'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoEmpenhadoNoBimestre,$alt,db_formatar($empenhado_p - $anulado_p,'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoEmpenhadoAteBimestre,$alt,db_formatar($empenhado_acumulado_p - $anulado_acumulado_p,'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoLiquidadoNoBimestre,$alt,db_formatar($liquidado_p,'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoLiquidadoAteBimestre,$alt,db_formatar($liquidado_acumulado_p,'f'),"LR",0,"R",0);
    if ($bimestre == "6B") {
      $pdf->cell($iTamanhoInscritos, $alt, db_formatar($inscrito_p,'f'),"LR",0,"R",0);
    }
     
    @$etotal = (($inscrito_p+$liquidado_acumulado_p)/$nTotalDespesasLiquidadas)*100;
    $pdf->cell($iTamanhoTotalSobreB,$alt,db_formatar($etotal,'f'),"LR",0,"R",0);
    if (($dot_ini_p + $suplementado_p) != 0) {
        $ae = (($inscrito_p+$liquidado_acumulado_p)/($dot_ini_p + $suplementado_p))*100;
    }else{
        $ae =0;
    }
    $pdf->cell($iTamanhoTotalBSobreA,$alt,db_formatar($ae,'f'),"LR",0,"R",0);
    $pdf->cell($iTamanhoSaldoLiquidar,$alt,db_formatar(($dot_ini_p + $suplementado_p)-($inscrito_p+$liquidado_acumulado_p),'f'),0,1,"R",0);
  
  $coltotal = $liquidado_acumulado_p;
  /*$soma_dot_ini      = $soma_dot_ini      + $dot_ini_p;
  $soma_atualizada   = $soma_atualizada   + (($dot_ini_p + $suplementado_p) - $reduzir_p);
  $soma_nobempenhado = $soma_nobempenhado + ($empenhado_p - $anulado_p);
  $soma_aempenhado   = $soma_aempenhado   + ($empenhado_acumulado_p - $anulado_acumulado_p);
  $soma_nobliquidado = $soma_nobliquidado + $liquidado_p;
  $soma_aliquidado   = $soma_aliquidado   + $liquidado_acumulado_p;
  $soma_inscrito   += ($empenhado_acumulado_p - $anulado_acumulado_p - $liquidado_acumulado_p);*/

}
$soma_dot_ini      += $xsoma_dot_ini;
$soma_atualizada   += $xsoma_atualizada;
$soma_nobempenhado += $xsoma_nobempenhado;
$soma_aempenhado   += $xsoma_aempenhado;
$soma_nobliquidado += $xsoma_nobliquidado;
$soma_aliquidado   += $xsoma_aliquidado;
$soma_inscrito     += $xsoma_inscritos;
$pdf->setfont('arial','B',6);
$pdf->cell(40,$alt,"Total","RTB",0,"L",0);
$pdf->cell($iTamanhoDotacaoInicial      , $alt, db_formatar($soma_dot_ini,'f'),"LRTB",0,"R",0);
$pdf->cell($iTamanhoDotacaoAtualizado   , $alt, db_formatar($soma_atualizada,'f'),"LRTB",0,"R",0);
$pdf->cell($iTamanhoEmpenhadoNoBimestre , $alt, db_formatar($soma_nobempenhado,'f'),"LRTB",0,"R",0);
$pdf->cell($iTamanhoEmpenhadoAteBimestre, $alt, db_formatar($soma_aempenhado,'f') ,"LRTB",0,"R",0);
$pdf->cell($iTamanhoLiquidadoNoBimestre , $alt, db_formatar($soma_nobliquidado,'f'),"LRTB",0,"R",0);
$pdf->cell($iTamanhoLiquidadoAteBimestre, $alt, db_formatar($soma_aliquidado,'f'),"LRTB",0,"R",0);
if ($bimestre == "6B") {
  $pdf->cell($iTamanhoInscritos, $alt, db_formatar($soma_inscrito,'f'),"LRTB",0,"R",0);
}
@$totalae = (($soma_aliquidado+$soma_inscrito)/($soma_inscrito+$soma_aliquidado))*100;
$pdf->cell($iTamanhoTotalSobreB, $alt, db_formatar($totalae,'f'),"LRTB",0,"R",0);
@$ttotalae = (($soma_inscrito+$soma_aliquidado)/$soma_atualizada)*100;
$pdf->cell($iTamanhoTotalBSobreA , $alt, db_formatar($ttotalae,"f"),"LRTB",0,"R",0);
$pdf->cell($iTamanhoSaldoLiquidar, $alt, db_formatar($soma_atualizada - ($soma_inscrito+$soma_aliquidado),'f'),"LTB",1,"R",0);

notasExplicativas(&$pdf,52,"{$bimestre}",190);

$pdf->ln(20);

// assinaturas
assinaturas(&$pdf,&$classinatura,'LRF');

$pdf->Output();

function cabecalho($bimestre, $pdf) {

  global $alt; 
  if ($bimestre != "6B") {
   
    $pdf->setfont('arial','',6);
    $pdf->cell(40,$alt,"",0,0,"C",0);
    $pdf->cell(20,$alt,"DOTAÇÃO","LR",0,"C",0);
    $pdf->cell(20,$alt,"DOTAÇÃO","LR",0,"C",0);
    $pdf->cell(40,$alt,"DESPESAS EMPENHADAS","LR",0,"C",0);
    $pdf->cell(50,$alt,"DESPESAS LIQUIDADAS","LR",0,"C",0);
    $pdf->cell(20,$alt,"SALDO A",0,1,"C",0);

    $pdf->cell(40,$alt,"FUNÇÃO/SUBFUNÇÃO",0,0,"C",0);
    $pdf->cell(20,$alt,"INICIAL","LR",0,"C",0);
    $pdf->cell(20,$alt,"ATUALIZADA","LR",0,"C",0);
    $pdf->cell(20,$alt,"No","TLR",0,"C",0);
    $pdf->cell(20,$alt,"Até o","TLR",0,"C",0);
    $pdf->cell(17,$alt,"No","TLR",0,"C",0);
    $pdf->cell(17,$alt,"Até o","TLR",0,"C",0);
    $pdf->cell(8,$alt,"%","TLR",0,"C",0);
    $pdf->cell(8,$alt,"%","TLR",0,"C",0);
    $pdf->cell(20,$alt,"LIQUIDAR",0,1,"C",0);

    $pdf->cell(40,$alt,"","BR",0,"C",0);
    $pdf->cell(20,$alt,"","BLR",0,"C",0);
    $pdf->cell(20,$alt,"(a)","BLR",0,"C",0);
    $pdf->cell(20,$alt,"Bimestre","BLR",0,"C",0);
    $pdf->cell(20,$alt,"Bimestre","BLR",0,"C",0);
    $pdf->cell(17,$alt,"Bimestre","BLR",0,"C",0);
    $pdf->cell(17,$alt,"Bimestre(b)","BLR",0,"C",0);
    $pdf->setfont('arial','',5);
    $pdf->cell(8,$alt,"(b/total b)","BLR",0,"C",0);
    $pdf->setfont('arial','',6);
    $pdf->cell(8,$alt,"(b/a)","BLR",0,"C",0);
    $pdf->cell(20,$alt,"(a-b)","B",1,"C",0);
    
  } else {
    
    $pdf->setfont('arial','',6);
    $pdf->cell(40,$alt,"",0,0,"C",0);
    $pdf->cell(16,$alt,"DOTAÇÃO","LR",0,"C",0);
    $pdf->cell(16,$alt,"DOTAÇÃO","LR",0,"C",0);
    $pdf->cell(32,$alt,"DESPESAS EMPENHADAS","LR",0,"C",0);
    $pdf->cell(68,$alt,"DESPESAS LIQUIDADAS","LR",0,"C",0);
    $pdf->cell(18,$alt,"SALDO A",0,1,"C",0);
    
    $pdf->cell(40,$alt,"FUNÇÃO/SUBFUNÇÃO",0,0,"C",0);
    $pdf->cell(16,$alt,"INICIAL","LR",0,"C",0);
    $pdf->cell(16,$alt,"ATUALIZADA","LR",0,"C",0);
    $pdf->cell(16,$alt,"No","TLR",0,"C",0);
    $pdf->cell(16,$alt,"Até o","TLR",0,"C",0);
    $pdf->cell(16,$alt,"No","TLR",0,"C",0);
    $pdf->cell(16,$alt,"Até o","TLR",0,"C",0);
    $pdf->cell(18,$alt,"INSCRITAS EM ","TLR",0,"C",0);
    $pdf->cell(10,$alt,"%((b+c)/","TLR",0,"C",0);
    $pdf->cell(8,$alt,"%","TLR",0,"C",0);
    $pdf->cell(18,$alt,"LIQUIDAR",0,1,"C",0);

    $pdf->cell(40,$alt,"","BR",0,"C",0);
    $pdf->cell(16,$alt,"","BLR",0,"C",0);
    $pdf->cell(16,$alt,"(a)","BLR",0,"C",0);
    $pdf->cell(16,$alt,"Bimestre","BLR",0,"C",0);
    $pdf->cell(16,$alt,"Bimestre","BLR",0,"C",0);
    $pdf->cell(16,$alt,"Bimestre","BLR",0,"C",0);
    $pdf->cell(16,$alt,"Bimestre(b)","BLR",0,"C",0);
    $pdf->cell(18,$alt,"RP NP (c)","BLR",0,"C",0);
    
    $pdf->setfont('arial','',5);
    $pdf->cell(10,$alt,"total (b+c))","BLR",0,"C",0);
    $pdf->setfont('arial','',6);
    $pdf->cell(8,$alt,"(b+c/a)","BLR",0,"C",0);
    $pdf->cell(18,$alt,"(a-(b+c))","B",1,"C",0);
    
  }
  
  
}
?>