<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_liborcamento.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("fpdf151/pdf.php");
require_once modification("fpdf151/assinatura.php");

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clempresto      = new cl_empresto;
$clorcelemento   = new cl_orcelemento;
$clorcprojativ   = new cl_orcprojativ;
$clselorcdotacao = new cl_selorcdotacao();
$oClassinatura   = new cl_assinatura();

$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php

$sql_filtro = $clselorcdotacao->getDados(false);

//função para retornar desdobramento
function retorna_desdob($elemento,$e64_codele,$clorcelemento){
  return  pg_query($clorcelemento->sql_query_file(null,null,"o56_elemento as estrutural,o56_descr as descr",null,"o56_codele = $e64_codele and o56_elemento like '$elemento%'"));
}

$troca = 1;

function cabecalho($pdf, $troca){

  if ($pdf->gety() > $pdf->h - 35 || $troca != 0 ){

    $tam  = "10";
    $tam2 = "5";
    $pdf->addpage("L");
    $pdf->SetFont('Arial', 'B',7);
    $pdf->Cell(80, $tam, "Dados cadastrais dos empenhos", 1, 0, "C", 1);
    $pdf->Cell(40, $tam, "Saldos a pagar anteriores", 1, 0, "C", 1);
    $alturacabecalho    = $pdf->gety();
    $distanciacabecalho = $pdf->getx();
    $pdf->Cell(100, $tam2, "Movimentação dos restos a pagar no período", 1, 1, "C", 1);
    $pdf->setxy($distanciacabecalho, $alturacabecalho+5);
    $pdf->Cell(40, $tam2, "Anulação", 1, 0 , "C", 1);
    $alturacabecalho2    = $pdf->gety();
    $distanciacabecalho2 = $pdf->getx();
    $pdf->Cell(20, $tam, "Liquidação", 1, "TLR" , "C", 1);
    $pdf->setxy($distanciacabecalho2+20,$alturacabecalho2);
    $pdf->Cell(40, $tam2, "Pagamento", 1, "TLR" , "C", 1);
    $pdf->setxy($distanciacabecalho+100,$alturacabecalho);
    $pdf->Cell(60, $tam, "Saldo a pagar finais", 1, 1, "C", 1);

    $pdf->Cell(15, $tam2, "Empenho", 1, 0, "C", 1);
    $pdf->Cell(15, $tam2, "Emissão", 1, 0, "C", 1);
    $pdf->Cell(50, $tam2, "Credor",  1, 0, "C", 1);

    $pdf->Cell(20, $tam2, "RP não proc", 1, 0, "C", 1);
    $pdf->Cell(20, $tam2, "RP proc",     1, 0, "C", 1);

    $pdf->Cell(20, $tam2, "RP não proc", 1, 0, "C", 1);
    $pdf->Cell(20, $tam2, "RP proc",     1, 0, "C", 1);
    $pdf->setx($pdf->getx()+20);
    $pdf->Cell(20, $tam2, "RP não proc", 1, 0, "C", 1);
    $pdf->Cell(20, $tam2, "RP proc",     1, 0, "C", 1);

    $pdf->Cell(20, $tam2, "A liquidar ", 1, 0, "C", 1);
    $pdf->Cell(20, $tam2, "Liquidados ",  1, 0, "C", 1);
    $pdf->Cell(20, $tam2, "Geral ",      1, 1, "C", 1);

    $pdf->SetFont('Arial', '',7);
    $troca = 0;
    $iYlinha = $pdf->getY();

  }


}

$xinstit = split("-", $db_selinstit);
$resultinst = pg_exec("select codigo,nomeinstabrev from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
  db_fieldsmemory($resultinst, $xins);
  $descr_inst .= $xvirg.$nomeinstabrev;
  $xvirg = ', ';
}

$sele_work = ' e60_instit in ('.str_replace('-', ', ', $db_selinstit).') ';
$sele_work1 = '';//tipo de recurso
$anoatual=db_getsession("DB_anousu");
if ($tipo=="or"){
  $tipofiltro="Órgão";
}

if ($tipo=="un"){
  $tipofiltro="Unidade";
}

if ($tipo=="fu"){
  $tipofiltro="Função";
}

if ($tipo=="su"){
  $tipofiltro="Subfunção";
}
if ($tipo=="pr"){
  $tipofiltro="Programa";
}

if ($tipo=="pa"){
  $tipofiltro="Projeto Atividade";
}

if ($tipo=="el"){
  $tipofiltro="Elemento";
}

if ($tipo=="de"){
  $tipofiltro="Desdobramento";
}
if ($tipo=="re"){
  $tipofiltro="Recurso";
}

if ($tipo=="tr"){
  $tipofiltro="Tipo de Resto";
}

if ($tipo=="cr"){
  $tipofiltro="Credor";
}

if ($tipo == "ex") {
  $tipofiltro = "Exercício";
}

if ($commov=="0"){
  $commovfiltro= "Todos";
}

if ($commov=="1"){
  $commovfiltro= "Com movimento até a data";
}

if ($commov=="2"){
  $commovfiltro= "Com saldo a pagar";
}

if ($commov=="3"){
  $commovfiltro= "Liquidados";
}
if ($commov=="4"){
  $commovfiltro= "Anulados";
}
if ($commov=="5"){
  $commovfiltro= "Pagos";
}

if ($commov=="6"){
  $commovfiltro= "Não liquidados";
}


/*
 * Acrescentado restantes das opçoes de impressao
 */
if ($impressao == 0) {
  $sOpImpressao = 'Analítico';
} else {
  $sOpImpressao = 'Sintético';
}
if ($exercicio == 0) {
  $sExercicio = 'Todos';
}else {
  $sExercicio = $exercicio;
}
$head1 = "INSTITUIÇÕE(S): ".$descr_inst. "\nPosição: $dtini até $dtfim - Agrupado por: ".$tipofiltro. "\nRestos a pagar:".
            $commovfiltro. "\nExercicio: ".$sExercicio. " - Opção de Impressão: ".$sOpImpressao;

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$pdf->setAutoPageBreak(false);

$tam="10";
$tam2="5";

//filtro por posição
//$dtini = db_getsession("DB_anousu").'-01-01';
$dtini = $dtini_ano."-".$dtini_mes."-".$dtini_dia;
$dtfim = $dtfim_ano."-".$dtfim_mes."-".$dtfim_dia;



//filtro por agrupamento
$sql_order = "";
if ($tipo=="or"){// órgão - tabela orcdotacao
  $sql_order = " order by o58_orgao,e60_anousu,e60_codemp::integer";
}

if ($tipo=="un"){// unidade - tabela orcdotacao
  $sql_order = " order by  o58_orgao,o58_unidade,e60_anousu,e60_codemp::integer ";
}

if ($tipo=="fu"){//função  - tabela orcdotacao
  $sql_order = " order by o58_funcao,e60_anousu,e60_codemp::integer";
}

if ($tipo=="su"){//subfunção - tabela orcdotacao
  $sql_order = " order by o58_subfuncao,e60_anousu,e60_codemp::integer";
}

if ($tipo=="pr"){//programa - tabela orcdotacao
  $sql_order = " order by o58_programa,e60_anousu,e60_codemp::integer";
}

if ($tipo=="pa"){//projeto atividade - tabela orcdotacao
  $sql_order = " order by o58_projativ,e60_anousu,e60_codemp::integer";
}

if ($tipo=="el"){//elemento - tabela orcdotacao
  $sql_order = " order by o58_codele,e60_anousu,e60_codemp::integer";
}


if ($tipo=="de"){//desdobramento-tabela empelemento
  $sql_order = " order by e64_codele,e60_anousu,e60_codemp::integer";
}

if ($tipo=="re"){//recurso - tabela empresto
  $sql_order = " order by e91_recurso,e60_anousu,e60_codemp::integer";
}


if ($tipo=="tr"){//resto - tabela empresto
  $sql_order = "order by e91_codtipo,e60_anousu,e60_codemp::integer";
}


if ($tipo=="cr"){//credor - tabela cgm
  $sql_order = " order by z01_nome,e60_anousu,e60_codemp::integer ";
}

if ($tipo == "ex") {
  $sql_order = " order by e60_anousu, e60_codemp::integer ";
}


//filtro por restos a pagar
$sql_where_externo = " ";
if ($commov=="0"){//geral
  $sql_where_externo .= "  ";
}

if ($commov=="1"){//com movimento até a data
  $sql_where_externo = "and (round(vlranu,2) + round(vlrliq,2) + round(vlrpag,2)) > 0 and $sele_work";
}

if ($commov=="2"){//com saldo a pagar ok
  $sql_where_externo .="and (((round(round(e91_vlremp, 2) - (round(e91_vlranu, 2) + round(vlranu, 2)), 2)) - ( round(e91_vlrpag, 2) + round(vlrpag, 2) + round(vlrpagnproc,2) )) > 0)";
}

if ($commov=="3"){//liquidados
  $sql_where_externo .= "and (round(vlrliq,2)) > 0 ";
}

if ($commov=="4"){//anulados
  $sql_where_externo .= " and (round(vlranu,2)) > 0";

}


if ($commov=="5"){//pagos
  $sql_where_externo .= "and (round(vlrpag,2) > 0 or round(vlrpagnproc,2)  > 0)";

}

if ($commov=="6"){//não liquidados
  $sql_where_externo .= "and (((round(round(e91_vlremp, 2) - (round(e91_vlranu, 2) + round(vlranu, 2)), 2)) - (round(e91_vlrliq, 2) + round(vlrliq, 2))) > 0) ";

}

//filtro por exercicio
if ($exercicio!=0){
  $sql_where_externo.=' and e60_anousu = '.$exercicio;
}

if ($listacredor != "") {
	if (isset ($vercredor) and $vercredor == "com") {
		$sql_where_externo .= " and e60_numcgm in  ($listacredor)";
	} else {
		$sql_where_externo .= " and e60_numcgm not in  ($listacredor)";
	}
}

$sql_where_externo .= " and ".$sql_filtro;

$sqlempresto = $clempresto->sql_rp_novo(db_getsession("DB_anousu"), $sele_work, $dtini, $dtfim, $sele_work1, $sql_where_externo, "$sql_order ");

$res = $clempresto->sql_record($sqlempresto);

if ($clempresto->numrows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Sem movimentação de restos a pagar.");
  exit;
}

$rows = $clempresto->numrows;

//variaveis agrupamentos
$vnumcgm=null;
$vorgao=null;
$vunidade=null;
$vfuncao=null;
$vsubfuncao=null;
$vprojativ=null;
$velemento=null;
$vdesdobramento=null;
$vrecurso=null;
$vprograma=null;
$vtiporesto=null;
$vanousu=null;

$mValorAgrupamento = null;

$subtotal_rp_n_proc         = 0;
$subtotal_rp_proc           = 0;
$subtotal_anula_rp_n_proc   = 0;
$subtotal_anula_rp_proc     = 0;
$subtotal_mov_liquida       = 0;
$subtotal_mov_pagmento      = 0;
$subtotal_mov_pagnproc      = 0;
$subtotal_aliquidar_finais  = 0;
$subtotal_liquidados_finais = 0;
$subtotal_geral_finais      = 0;


//total
$total_rp_n_proc         =0;
$total_rp_proc           =0;

$total_anula_rp_n_proc   =0;
$total_anula_rp_proc     =0;


$total_mov_liquida       =0;
$total_mov_pagmento      =0;
$total_mov_pagnproc      =0;

$total_aliquidar_finais  =0;
$total_liquidados_finais =0;
$total_geral_finais      =0;
//

function exibirSubtotal($oPdf, $oSubtotalizador) {

  $oPdf->Cell(80, 5, "Subtotal", "TBR", 0, "C", 1);
  $oPdf->Cell(20, 5, db_formatar(abs($oSubtotalizador->rp_n_proc),'f'), 1, 0, "R", 1);
  $oPdf->Cell(20, 5, db_formatar(abs($oSubtotalizador->rp_proc),'f'), 1, 0, "R", 1);
  $oPdf->Cell(20, 5, db_formatar(abs($oSubtotalizador->anula_rp_n_proc),'f'), 1, 0, "R", 1);
  $oPdf->Cell(20, 5, db_formatar(abs($oSubtotalizador->anula_rp_proc),'f'), 1, 0, "R", 1);
  $oPdf->Cell(20, 5, db_formatar(abs($oSubtotalizador->mov_liquida),'f'), 1, 0, "R", 1);
  $oPdf->Cell(20, 5, db_formatar(abs($oSubtotalizador->mov_pagnproc),'f'), 1, 0, "R", 1);
  $oPdf->Cell(20, 5, db_formatar(abs($oSubtotalizador->mov_pagmento),'f'), 1, 0, "R", 1);
  $oPdf->Cell(20, 5, db_formatar(abs($oSubtotalizador->aliquidar_finais) ,'f'), 1, 0, "R", 1);
  $oPdf->Cell(20, 5, db_formatar(abs($oSubtotalizador->liquidados_finais),'f'), 1, 0, "R", 1);
  $oPdf->Cell(20, 5, db_formatar(abs($oSubtotalizador->geral_finais),'f'), "TBL", 1, "R", 1);
}

$verifica=true;
$estrutura="";
$projativ="";
$o55anousu="";
$vprojativ="";

for ($x = 0; $x < $rows; $x ++) {
  db_fieldsmemory($res, $x);

  cabecalho($pdf,$troca);
  $troca=0;

  $oSubtotalizador = (object) array(
      'rp_n_proc' => $subtotal_rp_n_proc,
      'rp_proc' => $subtotal_rp_proc,
      'anula_rp_n_proc' => $subtotal_anula_rp_n_proc,
      'anula_rp_proc' => $subtotal_anula_rp_proc,
      'mov_liquida' => $subtotal_mov_liquida,
      'mov_pagnproc' => $subtotal_mov_pagnproc,
      'mov_pagmento' => $subtotal_mov_pagmento,
      'aliquidar_finais' => $subtotal_aliquidar_finais,
      'liquidados_finais' => $subtotal_liquidados_finais,
      'geral_finais' => $subtotal_geral_finais
    );

  $lZerarSubtotalizador = false;

  if ($mValorAgrupamento != $o58_orgao && $tipo == "or") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $o58_orgao;
  }

  if ($mValorAgrupamento != $o58_unidade && $tipo == "un") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $o58_unidade;
  }

  if ($mValorAgrupamento != $o58_funcao && $tipo == "fu") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $o58_funcao;
  }

  if ($mValorAgrupamento != $o58_subfuncao && $tipo == "su") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $o58_subfuncao;
  }


  if ($mValorAgrupamento != $o58_programa && $tipo == "pr") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $o58_programa;
  }


  if ($mValorAgrupamento != $o58_projativ && $tipo == "pa") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $o58_projativ;
  }

  if ($mValorAgrupamento != $o56_elemento && $tipo == "el") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $o56_elemento;
  }


  if ($mValorAgrupamento != $e64_codele && $tipo == "de") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $e64_codele;
  }

  if ($mValorAgrupamento != $e91_recurso && $tipo == "re") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $e91_recurso;
  }

  if ($mValorAgrupamento != $e91_codtipo && $tipo == "tr") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $e91_codtipo;
  }

  if ($mValorAgrupamento != $z01_numcgm && $tipo == "cr") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $z01_numcgm;
  }

  if ($mValorAgrupamento != $e60_anousu && $tipo == "ex") {

    if ($mValorAgrupamento !== null) {
      exibirSubtotal($pdf, $oSubtotalizador);
      $lZerarSubtotalizador = true;
    }

    $mValorAgrupamento = $e60_anousu;
  }

  if ($lZerarSubtotalizador) {

    $subtotal_rp_n_proc         = 0;
    $subtotal_rp_proc           = 0;
    $subtotal_anula_rp_n_proc   = 0;
    $subtotal_anula_rp_proc     = 0;
    $subtotal_mov_liquida       = 0;
    $subtotal_mov_pagmento      = 0;
    $subtotal_mov_pagnproc      = 0;
    $subtotal_aliquidar_finais  = 0;
    $subtotal_liquidados_finais = 0;
    $subtotal_geral_finais      = 0;
  }

  //filtro por órgão
  if ($tipo=="or" and $vorgao!=$o58_orgao){//orgão
    if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
    }

    $pdf->SetFont('Arial', 'B',7);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0, 5,"Orgão: $o58_orgao $o40_descr ", 0, 1, "L", 0);
    $vorgao=$o58_orgao;
    $verifica=false;
  }

  if ($tipo=="un" and  $vunidade!=$o58_unidade){//unidade
    if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
    }

    $pdf->SetFont('Arial', 'B',7);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0, 5,"Órgão:$o58_orgao $o40_descr  ", 0, 1, "L", 0);
    $pdf->cell(0, 5,"Unidade:$o58_unidade $o41_descr  ", 0, 1, "L", 0);
    $vunidade=$o58_unidade;
    $verifica=false;
  }

  if ($tipo=="fu" and $vfuncao!=$o58_funcao){//função
    if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
    }

    $pdf->SetFont('Arial', 'B',7);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0, 5,"Função:$o58_funcao $o52_descr", 0, 1, "L", 0);
    $vfuncao=$o58_funcao;
    $verifica=false;
  }

  if ($tipo=="su" and  $vsubfuncao!=$o58_subfuncao){//subfuncao
    if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
    }
    $pdf->SetFont('Arial', 'B',7);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0, 5,"Subfunção:$o58_subfuncao $o53_descr  ", 0, 1, "L", 0);
    $vsubfuncao=$o58_subfuncao;
    $verifica=false;
  }

  if ($tipo=="pr" and $vprograma!=$o58_programa){//programa
    if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
    }
    $pdf->SetFont('Arial', 'B',7);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0,5,"Programa:$o58_programa $o54_descr ", 0, 1, "L", 0);
    $vprograma=$o58_programa;
    $verifica=false;
  }

  if ($tipo=="pa" and $vprojativ!=$o58_projativ ){//projetto atividade
    if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
    }
    if ($vprojativ!=$o58_projativ or $o55anousu!=$e60_anousu){


      $pdf->SetFont('Arial', 'B',7);
      $pdf->cell(0, 2,"", 0, 1, "", 0);
      $pdf->cell(0, 5,"Projeto/atividade:$o58_projativ $o55_descr", 0, 1, "L", 0);
      $projativ=$o58_projativ;
      $vprojativ=$o58_projativ;
      $o55anousu=$e60_anousu;
    }

    $verifica=false;
  }
  if ($tipo=="el"  and $velemento!=$o56_elemento){//elemento
    if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
    }
    $pdf->SetFont('Arial', 'B',7);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0, 5,"Elemento:$o56_elemento  $o56_descr  ", 0, 1, "L", 0);
    $velemento=$o56_elemento;
    $verifica=false;
  }

  if ($tipo=="de" ){//desdobramento


    $resdesdob = retorna_desdob(substr($o56_elemento,0,7),$e64_codele,$clorcelemento);
    $numrows   = pg_numrows($resdesdob);

    for ($i = 0; $i < $numrows; $i ++) {
      db_fieldsmemory($resdesdob,$i);
      if ($estrutural!=$estrutura){
        if (isset($quebradepagina) and $verifica==false){
          $troca=1;
          cabecalho($pdf,$troca);
        }

        $pdf->SetFont('Arial', 'B',7);
        $pdf->cell(0, 3,"", 0, 1, "L", 0);
        $pdf->cell(0, 5,"Desdobramento:" .$estrutural." ".$descr, 0, 1, "L", 0);
        $estrutura=$estrutural;
        $verifica=false;

      }

    }

  }
  if ($tipo=="re" and $vrecurso!=$e91_recurso){//recurso
    if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
    }
    $pdf->SetFont('Arial', 'B',7);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0, 5,"Recurso:$e91_recurso $o15_descr  ", 0, 1, "L", 0);
    $vrecurso=$e91_recurso;
    $verifica=false;
  }

  if ($tipo=="tr" and $vtiporesto!=$e91_codtipo ){//tipo resto
    if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
    }
    $pdf->SetFont('Arial', 'B',7);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0, 5,"Tipo de resto: $e91_codtipo $e90_descr   ", 0, 1, "L", 0);
    $vtiporesto=$e91_codtipo;
    $verifica=false;

  }

  if ($tipo=="cr" and $vnumcgm!=$z01_numcgm){//credor
    if ((isset($quebradepagina) and $verifica==false)) {
      $troca=1;
      cabecalho($pdf,$troca);
    }
    $pdf->SetFont('Arial', 'B',7);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0, 5,"Credor:".$z01_numcgm." CNPJ:".db_formatar($z01_cgccpf,'cnpj')." ".substr($z01_nome,0,100), 0, 1, "L", 0);
    $vnumcgm=$z01_numcgm;
    $verifica=false;

  }

  if ($tipo == "ex" && $vanousu != $e60_anousu) {

    if ((isset($quebradepagina) && $verifica==false)) {
      $troca = 1;
      cabecalho($pdf, $troca);
    }

    $pdf->SetFont('Arial', 'B',7);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0, 5,"Exercício: " . $e60_anousu, 0, 1, "L", 0);

    $vanousu = $e60_anousu;
    $verifica = false;
  }

  //dados do relatório
  $pdf->SetFont('Arial', '',7);
  $tam="5";

  $total_rp_n_proc += ($e91_vlremp - $e91_vlranu - $e91_vlrliq);
  $total_rp_proc += ($e91_vlrliq - $e91_vlrpag);
	$total_anula_rp_n_proc += $vlranuliqnaoproc;
	$total_anula_rp_proc += $vlranuliq;
	$total_mov_liquida += ($vlrliq);
	$total_mov_pagmento += $vlrpag;
	$total_mov_pagnproc += $vlrpagnproc;
	$liquidado_anterior = ($e91_vlremp - $e91_vlranu - $e91_vlrliq) + ($e91_vlrliq - $e91_vlrpag);
  $apagargeral=( $liquidado_anterior -$vlranu - $vlrpag - $vlrpagnproc);
  $aliquidargeral=$e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq - $vlranuliq));
  $liquidados=($apagargeral-$aliquidargeral);
	$total_aliquidar_finais = $total_aliquidar_finais + $aliquidargeral;
	$total_liquidados_finais = $total_liquidados_finais + abs($liquidados);
	$total_geral_finais = ($total_geral_finais + $apagargeral);

  if($impressao == '0'){
	  //dados cadastrais dos empenhos
	  $pdf->Cell(15, $tam, ($e60_codemp. "/" .$e60_anousu),"TBR", 0,"R", 0);//empenho
	  $pdf->Cell(15,$tam, db_formatar($e60_emiss, 'd'), 1, 0, "C", 0);//emissao
	  $pdf->Cell(50, $tam, substr($z01_nome, 0,25), 1, 0, "L", 0);//credor

	  //saldos a pagar anteriores
	  $pdf->Cell(20, $tam, db_formatar(abs($e91_vlremp - $e91_vlranu - $e91_vlrliq), 'f'), 1, 0, "R", 0);// rp nao proc

	  $pdf->Cell(20, $tam, db_formatar(abs($e91_vlrliq - $e91_vlrpag), 'f'), 1, 0, "R", 0);//rp proc

	  //movimentação dos restos a pagar no período
	  $pdf->Cell(20, $tam, db_formatar(abs($vlranuliqnaoproc), 'f'), 1, 0, "R", 0);//anulacao -> rp nao proc

	  $pdf->Cell(20, $tam, db_formatar(abs($vlranuliq), 'f'), 1, 0, "R", 0);//anulacao -> rp proc

	  if ($c70_anousu == $anoatual ){
	    $pdf->Cell(20, $tam, db_formatar(abs($vlrliq), 'f'), 1, 0, "R", 0);//liquidado=rpproc

	  } else  {
	    $pdf->Cell(20, $tam, db_formatar("0", 'f'), 1, 0, "R", 0);//liquidado=rpproc
	  }

	  $pdf->Cell(20, $tam, db_formatar(abs($vlrpagnproc), 'f'), 1, 0, "R", 0);//pagamento
	  $pdf->Cell(20, $tam, db_formatar(abs($vlrpag), 'f'), 1, 0, "R", 0);//pagamento

	  // a liquidar
	  $pdf->Cell(20, $tam, db_formatar(abs($aliquidargeral), 'f'), 1, 0, "R", 0);

	  // liquidados
	  $pdf->Cell(20, $tam, db_formatar(abs($liquidados), 'f'), 1, 0, "R", 0);

	  // a pagar
	  $pdf->Cell(20, $tam, db_formatar(abs($apagargeral),'f'), "TBL", 1, "R", 0);

  }
  //subtotal
  $subtotal_rp_n_proc         += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
  $subtotal_rp_proc           += $e91_vlrliq - $e91_vlrpag;
  $subtotal_anula_rp_n_proc   += $vlranuliqnaoproc;
  $subtotal_anula_rp_proc     += $vlranuliq;
  $subtotal_mov_liquida       += $vlrliq;
  $subtotal_mov_pagmento      += $vlrpag;
  $subtotal_mov_pagnproc      += $vlrpagnproc;
  $subtotal_aliquidar_finais  += $aliquidargeral;
  $subtotal_liquidados_finais += abs($liquidados);
  $subtotal_geral_finais      += $apagargeral;
}


if ($subtotal_rp_n_proc        !=0 ||
    $subtotal_rp_proc          !=0 ||
    $subtotal_anula_rp_n_proc  !=0 ||
    $subtotal_anula_rp_proc    !=0 ||
    $subtotal_mov_liquida      !=0 ||
    $subtotal_mov_pagmento     !=0 ||
    $subtotal_mov_pagnproc     !=0 ||
    $subtotal_aliquidar_finais !=0 ||
    $subtotal_liquidados_finais!=0 ||
    $subtotal_geral_finais     !=0 ) {

  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc),'f'), 1, 0, "R", 1);
  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais),'f'), 1, 0, "R", 1);
  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);

}

$pdf->ln(2);
$pdf->Cell(80, $tam, "Total", "TBR", 0 , "C", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_rp_n_proc),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_rp_proc),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_anula_rp_n_proc),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_anula_rp_proc),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_mov_liquida),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_mov_pagnproc),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_mov_pagmento),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_aliquidar_finais),'f'), 1, 0, "R",1);
$pdf->Cell(20, $tam, db_formatar(abs($total_liquidados_finais),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_geral_finais),'f'), "TBL", 1, "R", 1);

/*
 *Melhoria para imprecao dos filtros selecionados
 */
$pdf->SetAutoPageBreak(true, 20);
$pdf->widths = array(200);
//-- imprime parametros
$imprime_filtro = $_POST['imprimefiltros'];
if (isset($imprime_filtro) && ($imprime_filtro == 'sim')) {
  $pdf->AddPage('L');
  $pdf->SetFont("Arial", "", 6);
  $pdf->Ln(10);
  $sParametros = $clselorcdotacao->getParametros();
  $aParametros = array($sParametros);

  $resto = $pdf->multicell(270, $tam, $sParametros, 1);
}

$pdf->Ln(17);
assinaturas($pdf, $oClassinatura, 'LRF', true, false);

$pdf->output();