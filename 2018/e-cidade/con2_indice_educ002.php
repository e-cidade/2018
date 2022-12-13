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

include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_conrelinfo_classe.php");
include("classes/db_empresto_classe.php");

$orcparamrel = new cl_orcparamrel;
$clconrelinfo = new cl_conrelinfo;
$clempresto = new cl_empresto;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

// db_postmemory($HTTP_POST_VARS,2); exit;


// estrutural
$rec["1"]["estrut"] = $orcparamrel->sql_parametro('50', '1'); // receits tributarias
$rec["2"]["estrut"] = $orcparamrel->sql_parametro('50', '2'); // transferencias correntes
$rec["3"]["estrut"] = $orcparamrel->sql_parametro('50', '3'); // outras receitas correntes
// descrição
$rec["1"]["descr"] = "RECEITA TRIBUTARIA";
$rec["2"]["descr"] = "TRANSFERENCIAS CORRENTES";
$rec["3"]["descr"] = "OUTRAS RECEITAS CORRENTES";
// valores
$rec["1"]["valor"] = 0;
$rec["2"]["valor"] = 0;
$rec["3"]["valor"] = 0;


$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst,uf from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst;
  $xvirg = ', ';
}
$iTipoAcao        = 0;
$iTipoAcaoEstorno = 0;

if ($modelo == 2) {
  
  $xtipo            = "EMPENHADA";
  $iTipoAcao        = 10;
  $iTipoAcaoEstorno = 11;
    
} else if( $modelo == 3 ) {

  $xtipo = "LIQUIDADA";
  $iTipoAcao        = 20;
  $iTipoAcaoEstorno = 21;
    
} else {
  
  $xtipo            = "PAGA";
  $iTipoAcao        = 30;
  $iTipoAcaoEstorno = 31;
  
}  

$anousu  = db_getsession("DB_anousu");
$dataini = $DBtxt21_ano.'-'.$DBtxt21_mes.'-'.$DBtxt21_dia;
$datafin = $DBtxt22_ano.'-'.$DBtxt22_mes.'-'.$DBtxt22_dia;


$head2 = "DEMONSTRATIVO DO ÍNDICE CONSTITUCIONAL DA EDUCAÇÃO";
$head3 = "RECURSO: MDE E FUNDEF/FUNDEB";
$head4 = "PERÍODO: ".db_formatar($dataini,'d')." A ".db_formatar($datafin,'d');
$head5 = "INSTITUIÇÕES : ".$descr_inst;
$head7 = "DESPESA: ".$xtipo;


// seleciona dados da receita
$db_filtro='';
$result_receita = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dataini,$datafin);
@db_query("drop table work_receita");

for ($i = 0; $i < pg_numrows($result_receita); $i ++) {
  db_fieldsmemory($result_receita, $i);
  $estrutural = $o57_fonte;

  if ($o70_concarpeculiar == 105) {
    continue;
  }

  for ($p=1;$p<=3;$p++) { 
    if (in_array($estrutural, $rec[$p]['estrut'])) {
      $rec[$p]['valor'] += $saldo_arrecadado_acumulado;     
    }
  }
}

if ($uf == 'RO') {
  $db_filtro=' o70_codigo in (20) ';
} else {
  $db_filtro=' o70_codigo in (20,30,31) ';
}
$result_deducao = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dataini,$datafin);
//db_criatabela($result_deducao); exit;
/**
*  calcula valores da receita apresentados no quadro de dedução ( ultimo quadro )
*/
$plus_fundef            = 0;
$plus_fundeb            = 0;
$rendimentos_mde_fundef = 0; 

// echo "<br>".$dataini;
// echo "<br>".$datafin;

//db_criatabela($result_deducao);
for ($x=0;$x < pg_numrows($result_deducao);$x++){
  db_fieldsmemory($result_deducao,$x);
  
//  if ($o57_fonte == '417240100000000'){
  if (db_conplano_grupo($anousu,$o57_fonte,9005) == true){
    if ($o70_codrec > 0){
//      echo "9005 ".$o70_codrec." => ".db_formatar($saldo_arrecadado,"f")."<br>";
      $plus_fundef += $saldo_arrecadado;
    }
//     echo "<br> aumenta ".db_formatar($plus_fundef,"f")." => ".$o57_fonte."<br>";
  }    
//  if ($o57_fonte == '497000000000000'){  // este estrutural representa uma conta de dedução ( negativa )
  if (db_conplano_grupo($anousu,$o57_fonte,9001) == true){  // este estrutural representa uma conta de dedução ( negativa )
    if ($o70_codrec > 0 and $o70_concarpeculiar == 105){
//      echo "9001 - codrec: ".$o70_codrec." - carpeculiar: $o70_concarpeculiar => ".db_formatar($saldo_arrecadado,"f")."<br>";
      $plus_fundeb += abs($saldo_arrecadado); 
      $plus_fundeb  = abs($plus_fundeb);
    }
//     echo "<br> diminui ".db_formatar($plus_fundeb,"f")." => ".$o57_fonte."<br>";
  }    
  
//  if ($o57_fonte == '413250100000000'){
  if (db_conplano_grupo($anousu,$o57_fonte,9003) == true){
    $rendimentos_mde_fundef += $saldo_arrecadado;  
  }        
  
}  

/*
if ( $plus_fundef <  0 ){
  $plus_fundef = 0;
}
*/

//exit;

if ($uf == 'RO') {
  $sele_work = ' o58_codigo in (20) ';
} else {
  $sele_work = ' o58_codigo in (20,30,31) ';
}
$result_despesa = db_dotacaosaldo(4,3,4,true,$sele_work,$anousu,$dataini,$datafin);
//db_criatabela($result_despesa);exit;

$sqlperiodo = $clempresto->sql_rp2($anousu, " e60_instit in (" . str_replace('-',', ',$db_selinstit) . ")",$dataini,$datafin, " and $sele_work");
//die($sqlperiodo);

$sqlperiodo = " select e60_instit, nomeinst, o58_subfuncao, o53_descr,
                sum(case when e60_anousu < " . db_getsession("DB_anousu") . "
                then e91_vlrliq-e91_vlrpag else 0 end ) as inscricao_ant,
                sum(case when e60_anousu = " . db_getsession("DB_anousu") . "
                then e91_vlrliq-e91_vlrpag else 0 end ) as  valor_processado,  
                sum(coalesce(canc_proc+canc_nproc,0)) as empenhado, 
                sum(coalesce(vlrliq,0)) as liquidado,
                sum(coalesce(vlrpag,0)) as pago
                from ($sqlperiodo) as x
                group by e60_instit, nomeinst, o58_subfuncao, o53_descr";
$resultado_rp = db_query($sqlperiodo) or die($sqlperiodo);
//db_criatabela($resultado_rp);exit;

// pega os parametros selecionados para as contas de contribuições ( interferencias ) grupo 5xx
$m_contas = $orcparamrel->sql_parametro('50','4');
$where = '';
$result_plano = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where);
$nValorCP502  = 0;
/**
 * Calculamos o total da deducao do fundeb (Caracteristica Peculiar 502
 */
$sSqlValorCP502  = "SELECT round(sum(case when c53_tipo = {$iTipoAcao}"; 
$sSqlValorCP502 .= "                      then c70_valor "; 
$sSqlValorCP502 .= "                      when c53_tipo = {$iTipoAcaoEstorno} then c70_valor *-1 end ),2) as valor";
$sSqlValorCP502 .= "  from conlancam ";
$sSqlValorCP502 .= "       inner join conlancamdoc on c70_codlan = c71_codlan";
$sSqlValorCP502 .= "       inner join conhistdoc   on c71_coddoc = c53_coddoc";
$sSqlValorCP502 .= "       inner join conlancamemp on c75_codlan = c70_codlan";
$sSqlValorCP502 .= "       inner join empempenho   on c75_numemp = e60_numemp";
$sSqlValorCP502 .= " where c53_tipo in ({$iTipoAcao}, {$iTipoAcaoEstorno})";
$sSqlValorCP502 .= "   and c70_data between '{$dataini}' and '{$datafin}'";
$sSqlValorCP502 .= "   and e60_concarpeculiar = '502';";
$rsSsqlValorCP502 = db_query($sSqlValorCP502);
//echo $sSqlValorCP502;
$nValorCP502 = db_utils::fieldsMemory($rsSsqlValorCP502, 0)->valor;

////////////////////////////////////////////////////

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','',10);
$pdf->AddPage(); 

$alt = 4;

/// receitas 

$pdf->setfont('arial','',12);
$pdf->cell(100,$alt,"Base de Cálculo Constitucional da Receita da Educação",0,1,"L");
$pdf->ln(2);

$pdf->setfont('arial','b',9);
$pdf->setX(20);
$pdf->cell(130,$alt,"Especificação da Receita",0,0,"C",1);
$pdf->cell(40,$alt,"Valor",0,1,"C",1);

$soma_receitas = 0;

$pdf->setfont('arial','',8);
$pdf->setX(20);
$pdf->cell(130,$alt,$rec['1']['descr'],0,0,"L");
$pdf->cell(40,$alt,db_formatar($rec['1']['valor'],'f'),0,1,"R");

$pdf->setfont('arial','',8);
$pdf->setX(20);
$pdf->cell(130,$alt,$rec['2']['descr'],0,0,"L");
$pdf->cell(40,$alt,db_formatar($rec['2']['valor'],'f'),0,1,"R");

$pdf->setfont('arial','',8);
$pdf->setX(20);
$pdf->cell(130,$alt,$rec['3']['descr'],0,0,"L");
$pdf->cell(40,$alt,db_formatar($rec['3']['valor'],'f'),0,1,"R");

$soma_receitas = $rec['1']['valor'] + $rec['2']['valor'] +$rec['3']['valor'];

$pdf->setfont('arial','b',9);
$pdf->setX(20);
$pdf->cell(130,$alt,"Total",'B',0,"C",0);
$pdf->cell(40,$alt,db_formatar($soma_receitas,'f'),'B',1,"R",0);

$pdf->ln();

/// despesas por subfunção

$pdf->setfont('arial','',12);
$pdf->cell(100,$alt,"Cálculo da Despesa Constitucional com Educação",0,1,"L");
$pdf->ln(2);

$pdf->setfont('arial','b',9);

$pdf->setX(20);
$pdf->cell(130,$alt,"Despesa por Subfunção do Exercício Corrente",0,1,"L",0);

$pdf->setX(20);
$pdf->cell(130,$alt,"Especificação da Subfunção",0,0,"C",1);
$pdf->cell(40,$alt,"Valor",0,1,"C",1);

$soma_subfuncao_exe = 0;
$array_subfuncao = array();

for ($x=0;$x < pg_numrows($result_despesa);$x++) {
  db_fieldsmemory($result_despesa,$x);
  
  // se valor zerado continua na proxima
  $valor = 0;
  if ($modelo==2) { // empenhado
    if ($empenhado==0)
    continue;
    
    $soma_subfuncao_exe +=  $empenhado - $anulado;
    $valor = $empenhado;
    
    
  }elseif ($modelo==3){ // liquidado
    if ($liquidado==0)
    continue;
    
    $soma_subfuncao_exe +=  $liquidado;
    $valor = $liquidado;
    
    
  }elseif ($modelo==4){ // pago
    if ($pago==0) 
    continue;
    
    $soma_subfuncao_exe +=  $pago;
    $valor = $pago;      
  }
  
  $array_subfuncao[$o53_descr][0] = $valor;
  
}

$pdf->setfont('arial','',8);
foreach ($array_subfuncao as $k => $v) {
  $pdf->setX(20);
  $pdf->cell(130,$alt,$k,0,0,"L");
  $pdf->cell(40,$alt,db_formatar($v[0],'f'),0,1,"R");
}

$pdf->setfont('arial','b',9);
$pdf->setX(20);
$pdf->cell(130,$alt,"Total",'B',0,"C",0);
$pdf->cell(40,$alt,db_formatar($soma_subfuncao_exe,'f'),'B',1,"R",0);

// rps

$pdf->setfont('arial','b',9);

$pdf->ln(3);
$pdf->setX(20);
$pdf->cell(130,$alt,"Despesa por Subfunção de Exercícios Anteriores",0,1,"L",0);

$pdf->setX(20);
$pdf->cell(130,$alt,"Especificação da Subfunção",0,0,"C",1);
$pdf->cell(40,$alt,"Valor",0,1,"C",1);

$soma_subfuncao_rp = 0;
$array_subfuncao = array();
for ($x=0;$x < pg_numrows($resultado_rp);$x++) {
  db_fieldsmemory($resultado_rp,$x);
  
  // se valor zerado continua na proxima
  $valor = 0;
  if ($modelo==2) { // empenhado
    if ($empenhado==0)
    continue;
    
    $soma_subfuncao_rp +=  $empenhado;
    $valor = $empenhado;
    
  }elseif ($modelo==3){ // liquidado
    if ($liquidado==0)
    continue;
    
    $soma_subfuncao_rp +=  $liquidado;
    $valor = $liquidado;
    
  }elseif ($modelo==4){ // pago
    if ($pago==0) 
    continue;
    
    $soma_subfuncao_rp +=  $pago;
    $valor = $pago;
    
  }
  
  if (!isset($array_subfuncao[$o53_descr][0])) {
    $array_subfuncao[$o53_descr][0] = $valor;
  } else {
    $array_subfuncao[$o53_descr][0] += $valor;
  }
  
}

$pdf->setfont('arial','',8);
foreach ($array_subfuncao as $k => $v) {
  $pdf->setX(20);
  $pdf->cell(130,$alt,$k,0,0,"L");
  $pdf->cell(40,$alt,db_formatar($v[0],'f'),0,1,"R");
}

$pdf->setfont('arial','b',9);
$pdf->setX(20);
$pdf->cell(130,$alt,"Total",'B',0,"C",0);
$pdf->cell(40,$alt,db_formatar($soma_subfuncao_rp,'f'),'B',1,"R",0);

$pdf->ln();

// despesa em contas patrimoniais

$pdf->setfont('arial','b',9);

$pdf->setX(20);
$pdf->cell(130,$alt,"Contabilizadas em Contas Patrimoniais",0,1,"L",0);

$pdf->setX(20);
$pdf->cell(40,$alt,"Estrutural",0,0,"C",1);
$pdf->cell(90,$alt,"Descrição",0,0,"C",1);
$pdf->cell(40,$alt,"Valor",0,1,"C",1);

$soma_patrimonial = 0;
for ($x=0;$x < pg_numrows($result_plano);$x++){
  db_fieldsmemory($result_plano,$x);
  
  if (in_array($estrutural,$m_contas)){
    
    $pdf->setfont('arial','',8);
    $pdf->setX(20);
    $pdf->cell(40,$alt,"$estrutural",0,0,"L");
    $pdf->cell(90,$alt,"$c60_descr",0,0,"L");
    $pdf->cell(40,$alt,db_formatar($saldo_final,'f'),0,1,"R");
    
    $soma_patrimonial += $saldo_final;
    
  }            
}  
$pdf->setfont('arial','b',9);
$pdf->setX(20);
$pdf->cell(130,$alt,"Total",'B',0,"C",0);
$pdf->cell(40,$alt,db_formatar($soma_patrimonial,'f'),'B',1,"R",0);

$pdf->ln();



// deduções da despesa

$pdf->setfont('arial','b',9);

$pdf->setX(20);
$pdf->cell(130,$alt,"Deduções da Despesa",0,1,"L",0);

$pdf->setX(20);
$pdf->cell(130,$alt,"Especificação",0,0,"C",1);
$pdf->cell(40,$alt,"Valor",0,1,"C",1);

///
$pdf->setfont('arial','',8);
$pdf->setX(20);
$pdf->cell(130,$alt,"(-) Despesa Liquidada com Recursos do PLUS do FUNDEF/FUNDEB",0,0,"L");
$pdf->cell(40,$alt,db_formatar(($plus_fundef-$plus_fundeb),'f'),0,1,"R");


$pdf->setfont('arial','',8);
$pdf->setX(20);
$pdf->cell(130,$alt,"(-) Despesa Liquidada com Rendimentos da MDE e FUNDEF/FUNDEB",0,0,"L");
$pdf->cell(40,$alt,db_formatar($rendimentos_mde_fundef,'f'),0,1,"R");

$pdf->setX(20);
$pdf->cell(130,$alt,"(-) Despesas Liquidadas com Recursos do Superávit do FUNDEB - CP 502",0,0,"L");
$pdf->cell(40,$alt,db_formatar($nValorCP502,'f'),0,1,"R");

/// 

$pdf->setfont('arial','b',9);
$pdf->setX(20);
$pdf->cell(130,$alt,"Total",'B',0,"C",0);
#$pdf->cell(40,$alt,db_formatar((($plus_fundef - $plus_fundeb)+ $rendimentos_mde_fundef),'f'),'B',1,"R",0);

$total_deducoes=($plus_fundef - $plus_fundeb)+ $rendimentos_mde_fundef+$nValorCP502;
$pdf->cell(40,$alt,db_formatar(($total_deducoes),'f'),'B',1,"R",0);

$pdf->ln();
$pdf->ln();
$pdf->ln();
$pdf->ln();



$pdf->setfont('arial','b',12);
$pdf->setX(80);
$pdf->cell(110,$alt+2,"Total dos Gastos Constitucionais com Educação",'0',1,"C",1);

#$total_deducoes = $soma_patrimonial + $soma_subfuncao_exe + $soma_subfuncao_rp - $plus_fundef - $plus_fundeb - $rendimentos_mde_fundef;

$total_gastos = ($soma_patrimonial + $soma_subfuncao_exe + $soma_subfuncao_rp) - $total_deducoes;

$pdf->setX(80);
$pdf->cell(70,$alt+2,"Valor Aplicado",'0',0,"R",1);
$pdf->cell(40,$alt+2,db_formatar($total_gastos,'f'),'0',1,"R",1);

// $soma_receitas  = 100% 
// $total_deducoes = ? 

$pdf->setX(80);
$pdf->cell(70,$alt+2,"% de Aplicação",'0',0,"R",1);
#$pdf->cell(40,$alt+2,db_formatar(@(($total_deducoes * 100 ) / $soma_receitas),'f'),'0',1,"R",1);

$aplicacao = 0;
if ($total_gastos > 0 && $soma_receitas > 0) {
	$aplicacao = ($total_gastos/$soma_receitas) *100;
}

$pdf->cell(40,$alt+2,db_formatar(@($aplicacao),'f'),'0',1,"R",1);

//include("fpdf151/geraarquivo.php");
$pdf->Output();
?>