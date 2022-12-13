<?php

require_once ("fpdf151/scpdf.php");
require_once ("libs/db_sql.php");
require_once ('libs/db_utils.php');
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlib.php");


$oGet                  = db_utils::postMemory($_GET);
$iIntituicao           = db_getsession("DB_instit");
$iAnoSessao            = db_getsession("DB_anousu");
$oDadosBasicos         = dadosBasicos($oGet);
$aCheques              = listaDeCheques($oGet->iConta, $oDadosBasicos->conciliacao);
$aMovimentosTesouraria = movimentosTesouraria($oGet->iConta, $oDadosBasicos->conciliacao);
$aMovimentosExtrato    = movimentosExtrato($oDadosBasicos->conciliacao);

// Tipo: 1
$nDepositosAnexoI      = 0;
// Tipo 2
$nDebitosAnexoII       = 0;
// Tipo 3
$nChequesNApresentados = 0;
//Tipo 4
$nCreditoAnexoIII      = 0;

$iUltimoDiaMes         = cal_days_in_month(CAL_GREGORIAN, $oGet->iMes, $oGet->iAno);
$sDataRelatorio        = "{$iUltimoDiaMes}/{$oGet->iMes}/{$oGet->iAno}";

if ((int)$oGet->iAno < (int)$iAnoSessao) {
  $sDataRelatorio = "{$iUltimoDiaMes}/{$oGet->iMes}/{$oGet->iAno}";
}

$nSaldoTesouraria      = saldoTesouraria($oGet, $oDadosBasicos->data, $iIntituicao);

foreach ($aMovimentosTesouraria as $oTesouraria) {

  switch ($oTesouraria->tipo) {

    case "debito" :
      $nDepositosAnexoI += $oTesouraria->valor;
      break;
    case "credito":
      $nDepositosAnexoI -= $oTesouraria->valor;
      break;
    case "cheque":
      $nChequesNApresentados = $oTesouraria->valor;
      break;
  }
}

foreach ($aMovimentosExtrato as $oExtrato) {

  switch ($oExtrato->tipo) {

    case "outros":

      if ($oExtrato->k86_tipo == "D") {
        $nDebitosAnexoII += $oExtrato->valor;
      } else  {
        $nCreditoAnexoIII += $oExtrato->valor;
      }

      break;
    case "debito":
      $nDebitosAnexoII += $oExtrato->valor;
      break;

    case "credito":
      $nCreditoAnexoIII += $oExtrato->valor;
      break;
  }
}


/**
 * Variaveis de configuracao do fpdf
 */
$iAltLinha            = 4;
$iAltRetanguloInicial = 7;
$iAltRetanguloFinal   = 22;
$lQuebrouPagina       = false;
$lImprimiuCheque      = false;



$oPdf = new scpdf();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->AddPage();

/**
 * Rect: lateral, altura_inicial, largura, altura
 */

/**
 * Quadro 1
 */
$oPdf->Rect(10, $iAltRetanguloInicial, 190, $iAltRetanguloFinal);
$oPdf->SetFont('Arial','B',10);
$oPdf->SetX(30);
$oPdf->Cell(150,$iAltLinha, "ESTADO DO RIO DE JANEIRO", 0, 0);
$oPdf->SetFont('Arial','',5);
$oPdf->Cell(20,$iAltLinha, "Página: " . count($oPdf->pages) . "/{$oPdf->AliasNbPages}", 0, 1, "R");
$oPdf->SetFont('Arial','B',10);
$oPdf->SetX(30);
$oPdf->Cell(190,$iAltLinha, "{$oDadosBasicos->nomeinst}", 0, 1);
$oPdf->SetX(30);
$oPdf->Cell(190,$iAltLinha, "CONTABILIDADE", 0, 1);

/**
 * Qaudro 2
 */
$iAltRetanguloInicial += $iAltRetanguloFinal;
$iAltRetanguloFinal    = 12;
$oPdf->Rect(10, $iAltRetanguloInicial , 190, $iAltRetanguloFinal);
$oPdf->SetY($iAltRetanguloInicial+1);
$oPdf->Cell(190,$iAltLinha, "CONCILIAÇÃO BANCÁRIA", 0, 1, "C");
$oPdf->Cell(190,$iAltLinha, "MODELO 6 - DELIBERAÇÃO 200/96", 0, 1, "C");

/**
 * Quadro 3
 */

$iAltRetanguloInicial += $iAltRetanguloFinal;
$iAltRetanguloFinal    = 12;
$oPdf->Rect(10, $iAltRetanguloInicial , 190, $iAltRetanguloFinal);
$oPdf->SetY($iAltRetanguloInicial+1);
$oPdf->SetFont('Arial','',7);
$oPdf->Cell(140,$iAltLinha, "Órgão / Entidade / Fundo", 0, 0);
$oPdf->Cell(50,$iAltLinha, "Município", 0, 1);

$oPdf->SetFont('Arial','',8);
$oPdf->SetX(20);
$oPdf->Cell(120,$iAltLinha, "{$oDadosBasicos->nomeinst}", 0, 0);
$oPdf->SetX($oPdf->GetX()+15);
$oPdf->Cell(40,$iAltLinha, "{$oDadosBasicos->munic}", 0, 1);

$oPdf->Line(150, $iAltRetanguloInicial, 150, $iAltRetanguloInicial+$iAltRetanguloFinal);

/**
 * Quadro 3
 */
$iAltRetanguloInicial += $iAltRetanguloFinal;
$iAltRetanguloFinal    = 18;
$oPdf->Rect(10, $iAltRetanguloInicial , 190, $iAltRetanguloFinal);
$oPdf->SetY($iAltRetanguloInicial+1);
$oPdf->SetX(120);
$oPdf->Cell(10,$iAltLinha, "MÊS: ", 0, 0);
if ($oGet->iAno == 2013) {
  $oPdf->Cell(20,$iAltLinha, "Dezembro", 0, 0);
} else {
  $oPdf->Cell(20,$iAltLinha, "{$oGet->sMes}", 0, 0);
}
$oPdf->Cell(10,$iAltLinha, "DE: ", 0, 0);
$oPdf->Cell(10,$iAltLinha, "{$oGet->iAno}", 0, 1);

$iContaDigito = "{$oDadosBasicos->conta}-{$oDadosBasicos->digito_conta}";
$oPdf->SetX(120);
$oPdf->Cell(33,$iAltLinha, "CONTA BANCÁRIA Nº: ", 0, 0);
$oPdf->Cell(20,$iAltLinha, "{$iContaDigito}", 0, 1);

$oPdf->SetX(120);
$oPdf->Cell(43,$iAltLinha, "DEMONSTRAÇÃO DA CONTA: ", 0, 0);
$oPdf->Cell(20,$iAltLinha, "{$oGet->iConta}", 0, 1);

$oPdf->SetX(120);
$oPdf->Cell(40,$iAltLinha, "{$oDadosBasicos->descricao_conta}", 0, 1);


/**
 * Quadro 4 - Procedimentos e Sugestões
 */
$sProcedimento           = "PROCEDIMENTO DE CONCILIAÇÃO";
$sDescricaoProcedimento  = "1 - Determinar os depósitos ainda não creditados pelo Banco.\n";
$sDescricaoProcedimento .= "2 - Determinar os débitos vários efetuados pelo Banco e ainda não contabilizados na escrita. \n";
$sDescricaoProcedimento .= "3 - No quadro reservado anotar os cheques emitidos e ainda não apresentados no Banco. \n";
$sDescricaoProcedimento .= "4 - Determinar os créditos vários efetuados pelo Banco e não contabilizados na escrita. ";

$sSugestao           = "SUGESTÃO PARA ENCONTRAR DIFERENÇAS";
$sDescricaoSugestao  = "Determinar o valor da diferença.\n";
$sDescricaoSugestao .= "Revisar as somas, subtrações e correções, neste formulário e em seus registros.\n";
$sDescricaoSugestao .= "Assegurar-se de que tenha anotado em seus registros os débitos em funções do cheque e outros ";
$sDescricaoSugestao .= "débitos e créditos recebidos do Banco.\n";
$sDescricaoSugestao .= "Verificar os transportes das somas e saldo em seus registros.";


$iAltRetanguloInicial += $iAltRetanguloFinal;
$iAltRetanguloFinal    = 33;
$oPdf->Rect(10, $iAltRetanguloInicial , 190, $iAltRetanguloFinal);

$oPdf->SetY($iAltRetanguloInicial+1);

$oPdf->SetFont('Arial','B',6);

$iY = $oPdf->GetY();
$oPdf->SetX(15);
$oPdf->Cell(90,$iAltLinha, "{$sProcedimento}", 0, 1, "C");
$oPdf->SetX(15);
$oPdf->MultiCell(90, $iAltLinha, $sDescricaoProcedimento);

$oPdf->SetY($iY);
$oPdf->SetX(110);
$oPdf->Cell(80,$iAltLinha, "{$sSugestao}", 0, 1, "C");
$oPdf->SetX(110);
$oPdf->MultiCell(80, $iAltLinha, $sDescricaoSugestao);


/**
 * Quadro 5 - Cheques
 */
$iAltRetanguloInicial += $iAltRetanguloFinal;
$iAltRetanguloFinal    = 6;
$oPdf->Rect(10, $iAltRetanguloInicial , 190, $iAltRetanguloFinal);
$oPdf->SetY($iAltRetanguloInicial+1);

$oPdf->SetFont('Arial','',6);
$oPdf->Cell(190, $iAltLinha, "CHEQUES EMITIDOS E AINDA NÃO APRESENTADOS", 0, 1, "C");
$oPdf->SetY($oPdf->GetY()+1);
$oPdf->Cell(63, $iAltLinha, "Data",     1, 0, "C");
$oPdf->Cell(64, $iAltLinha, "Número",   1, 0, "C");
$oPdf->Cell(63, $iAltLinha, "Valor R$", 1, 1, "C");

if (count($aCheques) > 0) {

  foreach ($aCheques as $oCheque) {

    if ($oPdf->GetY() > $oPdf->h - 15) {
      $lQuebrouPagina = quebraCheque($oPdf, $iAltLinha);
    }

    $oPdf->Cell(63, $iAltLinha, db_formatar($oCheque->data, "d"),     1, 0, "C");
    $oPdf->Cell(64, $iAltLinha, $oCheque->cheque,                     1, 0, "C");
    $oPdf->Cell(63, $iAltLinha, db_formatar($oCheque->valor, "f"),    1, 1, "C");

  }

} else {

	$oPdf->Cell(63, $iAltLinha, "",     1, 0, "C");
	$oPdf->Cell(64, $iAltLinha, "",                     1, 0, "C");
	$oPdf->Cell(63, $iAltLinha, "",    1, 1, "C");

}

/**
 * Antes de imprimir o quadro 6, validamos se ha espaco para imprimi-lo.
 * Se nao houver quebramos a pagina
 */
if ($oPdf->GetY() > $oPdf->h - 45) {
  $lQuebrouPagina = quebraPagina($oPdf, $iAltLinha);
}

/**
 * Quadro 6 - Somar os Saldos dos itens
 */
$iAltRetanguloInicial  = $oPdf->GetY();
$iAltRetanguloFinal    = 30;
$oPdf->Rect(10, $iAltRetanguloInicial , 190, $iAltRetanguloFinal);
$oPdf->Rect(95, $iAltRetanguloInicial , 105, 10);
$oPdf->Line(40, $iAltRetanguloInicial + 20, 200, $iAltRetanguloInicial + 20);
/**
 * linhas horizontais
 */
$oPdf->Line(40,  $iAltRetanguloInicial,  40, $iAltRetanguloInicial + $iAltRetanguloFinal);
$oPdf->Line(165, $iAltRetanguloInicial, 165, $iAltRetanguloInicial + $iAltRetanguloFinal);


$oPdf->SetY($iAltRetanguloInicial+1);
$oPdf->SetX(96);
$sSaldoExtrato  = "SALDO DO EXTRATO DE CONTA NO\n";
$sSaldoExtrato .= "ULTIMO DIA DO MÊS {$sDataRelatorio}";
$oPdf->MultiCell(50, $iAltLinha, $sSaldoExtrato);

$oPdf->SetY($iAltRetanguloInicial+2);
$oPdf->SetX(175);
$oPdf->Cell(63, $iAltLinha, "R$ " . db_formatar($oDadosBasicos->saldoextrato, "f"),    0, 1, "L");

$oPdf->SetY($iAltRetanguloInicial+5);
$oPdf->SetX(15);
$oPdf->MultiCell(20, $iAltLinha, "SOMAR OS\nSALDOS DOS ITENS\n1 E 2", 0, "C");

// Imprime itens
$oPdf->SetY($iAltRetanguloInicial+15);
$oPdf->SetX(42);
$oPdf->SetFont('Arial','B',6);
$oPdf->Cell(123, $iAltLinha, "1- Depósitos ainda não creditados no extrato - ANEXO I",  0, 0, "L");
$oPdf->SetFont('Arial','',6);
$oPdf->SetX(175);
$oPdf->Cell(63,  $iAltLinha, "R$ " . db_formatar($nDepositosAnexoI, "f"),              0, 1, "L");

$oPdf->SetY($iAltRetanguloInicial+23);
$oPdf->SetX(42);
$oPdf->SetFont('Arial','B',6);
$oPdf->Cell(123, $iAltLinha, "2- Débitos vários não contabilizados - ANEXO II",  0, 0, "L");
$oPdf->SetFont('Arial','',6);
$oPdf->SetX(175);
$oPdf->Cell(63,  $iAltLinha, "R$ " . db_formatar($nDebitosAnexoII, "f"),              0, 1, "L");


/**
 * Antes de imprimir o quadro 7, validamos se ha espaco para imprimi-lo.
 * Se nao houver quebramos a pagina
 */
if ($oPdf->GetY() > $oPdf->h - 45) {
  $lQuebrouPagina = quebraPagina($oPdf, $iAltLinha);
}

if ($lQuebrouPagina) {

  $iAltRetanguloInicial = 3;
  $iAltRetanguloFinal   = $oPdf->GetY();
  $lQuebrouPagina       = false;
}

/**
 * Quadro 7 - Subtrair do Subtotal
 */
$iAltRetanguloInicial += $iAltRetanguloFinal;
$iAltRetanguloFinal    = 30;
$oPdf->Rect(10,  $iAltRetanguloInicial, 190, $iAltRetanguloFinal);
$oPdf->Line(40,  $iAltRetanguloInicial + 19, 200, $iAltRetanguloInicial + 19);
$oPdf->Rect(95,  $iAltRetanguloInicial + 20,  70, 10);
$oPdf->Rect(120, $iAltRetanguloInicial +  7,  45, 12);
$oPdf->Line(120, $iAltRetanguloInicial + 13, 165, $iAltRetanguloInicial + 13);

/**
 * linhas horizontais
 */
$oPdf->Line(40,  $iAltRetanguloInicial,  40, $iAltRetanguloInicial + $iAltRetanguloFinal);
$oPdf->Line(165, $iAltRetanguloInicial, 165, $iAltRetanguloInicial + $iAltRetanguloFinal);

// Imprime as strings do quadro 7
$oPdf->SetY($iAltRetanguloInicial+5);
$oPdf->SetX(15);
$oPdf->MultiCell(20, $iAltLinha, "SUBTRAIR DO\nSUBTOTAL A\nSOMA DOS\nITENS 3 E 4", 0, "C");
$oPdf->SetY($iAltRetanguloInicial+1);
$oPdf->SetX(130);
$oPdf->SetFont('Arial','',5);
$oPdf->Cell(15,  3, "Subtotal",              0, 1, "L");


$oPdf->SetFont('Arial','B',6);
$sIten3 = "3- Cheques emitidos e ainda não apresentados";
$sIten4 = "4- Créditos vários não contabilizados ANEXO III";
$oPdf->SetY($iAltRetanguloInicial+8);
$oPdf->SetX(42);
$oPdf->MultiCell(90, 3, $sIten3, 0, "L");

$oPdf->SetY($iAltRetanguloInicial+14);
$oPdf->SetX(42);
$oPdf->MultiCell(90, 3, $sIten4, 0, "L");


$oPdf->SetY($iAltRetanguloInicial+21);
$oPdf->SetX(96);
$sSaldoExtrato  = "SALDO QUE TEM QUE COINCIDIR COM\n";
$sSaldoExtrato .= "OS REGISTROS CONTÁBEIS";
$oPdf->MultiCell(50, $iAltLinha, $sSaldoExtrato);


//Valores
$oPdf->SetY($iAltRetanguloInicial+8);
$oPdf->SetFont('Arial','',6);
$oPdf->SetX(130);
$oPdf->Cell(63,  $iAltLinha, "R$ " . db_formatar($nChequesNApresentados, "f"),              0, 1, "L");
$oPdf->SetY($iAltRetanguloInicial+14);
$oPdf->SetX(130);
$oPdf->Cell(63,  $iAltLinha, "R$ " . db_formatar($nCreditoAnexoIII, "f"),              0, 1, "L");

// Valor do Subtotal
$nValorSubtotal = $nChequesNApresentados - $nCreditoAnexoIII;
$oPdf->SetY($iAltRetanguloInicial+8);
$oPdf->SetFont('Arial','',6);
$oPdf->SetX(175);
$oPdf->Cell(63,  $iAltLinha, "R$ " . db_formatar($nValorSubtotal, "f"),              0, 1, "L");

$oPdf->SetY($iAltRetanguloInicial+21);
$oPdf->SetX(175);
$oPdf->Cell(63, $iAltLinha, "R$ " . db_formatar($nSaldoTesouraria, "f"),    0, 1, "L");

$oPdf->SetY($iAltRetanguloInicial + $iAltRetanguloFinal);
$oPdf->Cell(190, 6, "" ,    1, 1);


/** *************************************************
 *                     ASSINATURAS
 ****************************************************/
$sSqlParag  = "select db02_texto                                             ";
$sSqlParag .= "  from db_documento                                           ";
$sSqlParag .= "       inner join db_docparag  on db03_docum   = db04_docum   ";
$sSqlParag .= "       inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
$sSqlParag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
$sSqlParag .= " where db03_tipodoc = 1036 and db03_instit = {$iIntituicao} order by db04_ordem ";
$rsParag    = db_query($sSqlParag);
if ($rsParag && pg_numrows($rsParag) > 0) {

  $oParagrafo = db_utils::fieldsMemory($rsParag, 0);
  eval($oParagrafo->db02_texto);
} else {
  $sSqlParagPadrao  = "select db61_texto ";
  $sSqlParagPadrao .= "  from db_documentopadrao ";
  $sSqlParagPadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
  $sSqlParagPadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
  $sSqlParagPadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
  $sSqlParagPadrao .= " where db60_tipodoc = 1036 and db60_instit = {$iIntituicao} order by db62_ordem";

  $rsParagPadrao    = db_query($sSqlParagPadrao);
  if ($rsParagPadrao && pg_numrows($rsParagPadrao) > 0) {

    $oParagrafo = db_utils::fieldsMemory($rsParagPadrao, 0);
    eval($oParagrafo->db61_texto);
  }
}


$oPdf->Output();

/**
 * Busca a conciliacao e os dados da conta bancaria
 * @param object $oGet $_GET
 * @return stdClass
 */
function dadosBasicos($oGet) {

  $iInstituicao = db_getsession("DB_instit");

  $sSqlBasico  = " select k68_sequencial  as conciliacao,                                 ";
  $sSqlBasico .= "        db83_descricao  as descricao_conta,                             ";
  $sSqlBasico .= "        db89_db_bancos  as codigo_banco,                                ";
  $sSqlBasico .= "        db83_conta      as conta,                                       ";
  $sSqlBasico .= "        db83_dvconta    as digito_conta,                                ";
  $sSqlBasico .= "        db89_codagencia as agencia,                                     ";
  $sSqlBasico .= "        db89_digito     as digito_agencia,                              ";
  $sSqlBasico .= "        case                                                            ";
  $sSqlBasico .= "          when k97_saldofinal is not null then k97_saldofinal           ";
  $sSqlBasico .= "          else (select k97_saldofinal                                   ";
  $sSqlBasico .= "                  from extratosaldo                                     ";
  $sSqlBasico .= "                 where k97_contabancaria = {$oGet->iConta}              ";
  $sSqlBasico .= "                   and k97_dtsaldofinal <= k68_data                     ";
  //$sSqlBasico .= "                 order by k97_dtsaldofinal desc, k97_sequencial desc limit 1) ";
  $sSqlBasico .= "                 order by k97_dtsaldofinal desc, k97_extrato desc limit 1) ";
  $sSqlBasico .= "        end as saldoextrato,                                            ";
  $sSqlBasico .= "        nomeinst,                                                       ";
  $sSqlBasico .= "        munic,                                                          ";
  $sSqlBasico .= "        k68_data as data                                                ";
  $sSqlBasico .= "  from concilia                                                         ";
  $sSqlBasico .= "  inner join contabancaria on k68_contabancaria = db83_sequencial       ";
  $sSqlBasico .= "  inner join bancoagencia  on db83_bancoagencia = db89_sequencial       ";
  $sSqlBasico .= "  left join extratosaldo  on k97_contabancaria = db83_sequencial       ";
  $sSqlBasico .= "                          and k97_dtsaldofinal  = k68_data              ";
  $sSqlBasico .= "  inner join db_config     on codigo            = {$iInstituicao}";
  $sSqlBasico .= "  where k68_contabancaria = {$oGet->iConta}                              ";
  $sSqlBasico .= "  and k68_data = (select max(k68_data)                                 ";
  $sSqlBasico .= "                     from concilia                                      ";
  $sSqlBasico .= "                    where k68_contabancaria = {$oGet->iConta}           ";
  $sSqlBasico .= "                      and extract(month from k68_data) = {$oGet->iMes}  ";
  $sSqlBasico .= "                      and extract(year from k68_data)  = {$oGet->iAno}) ";
  $sSqlBasico .= "  order by k97_dtsaldofinal desc, k97_sequencial desc limit 1           ";
//die( $sSqlBasico );

  $rsDadosBasico = db_query($sSqlBasico);

  if (pg_numrows($rsDadosBasico) == 0) {
    $sSqlBasico  = " select k68_sequencial  as conciliacao,                                 ";
    $sSqlBasico .= "        db83_descricao  as descricao_conta,                             ";
    $sSqlBasico .= "        db89_db_bancos  as codigo_banco,                                ";
    $sSqlBasico .= "        db83_conta      as conta,                                       ";
    $sSqlBasico .= "        db83_dvconta    as digito_conta,                                ";
    $sSqlBasico .= "        db89_codagencia as agencia,                                     ";
    $sSqlBasico .= "        db89_digito     as digito_agencia,                              ";
    $sSqlBasico .= "        case                                                            ";
    $sSqlBasico .= "          when k97_saldofinal is not null then k97_saldofinal           ";
    $sSqlBasico .= "          else (select k97_saldofinal                                   ";
    $sSqlBasico .= "                  from extratosaldo                                     ";
    $sSqlBasico .= "                 where k97_contabancaria = {$oGet->iConta}              ";
    $sSqlBasico .= "                   and k97_dtsaldofinal <= k68_data                     ";
    //$sSqlBasico .= "                 order by k97_dtsaldofinal desc, k97_sequencial desc limit 1) ";
    $sSqlBasico .= "                 order by k97_dtsaldofinal desc, k97_extrato desc limit 1) ";
    $sSqlBasico .= "        end as saldoextrato,                                            ";
    $sSqlBasico .= "        nomeinst,                                                       ";
    $sSqlBasico .= "        munic,                                                          ";
    $sSqlBasico .= "        k68_data as data                                                ";
    $sSqlBasico .= "  from concilia                                                         ";
    $sSqlBasico .= "  inner join contabancaria on k68_contabancaria = db83_sequencial       ";
    $sSqlBasico .= "  inner join bancoagencia  on db83_bancoagencia = db89_sequencial       ";
    $sSqlBasico .= "  left join extratosaldo  on k97_contabancaria = db83_sequencial        ";
    $sSqlBasico .= "                          and k97_dtsaldofinal  = k68_data              ";
    $sSqlBasico .= "  inner join db_config     on codigo            = {$iInstituicao}";
    $sSqlBasico .= "  where k68_contabancaria = {$oGet->iConta}                             ";
    $sSqlBasico .= "  and k68_data = (select max(k68_data)                                  ";
    $sSqlBasico .= "                     from concilia                                      ";
    $sSqlBasico .= "                    where k68_contabancaria = {$oGet->iConta})          ";
//    $sSqlBasico .= "                      and extract(year from k68_data)  = {$oGet->iAno}) ";
    $sSqlBasico .= "  order by k97_dtsaldofinal desc, k97_sequencial desc limit 1                ";
//die( $sSqlBasico );

    $rsDadosBasico = db_query($sSqlBasico);

  }

  return db_utils::fieldsMemory($rsDadosBasico, 0);
}

/**
 * Lista os cheques de uma conciliacao
 * @param integer $iConta codigo da conta (contabancaria)
 * @param integer $iConciliacao codigo da conciliacao
 * @return array de stdClass
 */
function listaDeCheques($iConta, $iConciliacao) {

  $iInstituicao = db_getsession("DB_instit");

  $sSqlCheques  = " select ridata as data,                                                                               ";
  $sSqlCheques .= "        richeque as cheque,                                                                           ";
  $sSqlCheques .= "        case                                                                                          ";
  $sSqlCheques .= "          when richeque       is not null                                                             ";
  $sSqlCheques .= "           and richeque       <> 0                                                                    ";
  $sSqlCheques .= "           and rivalorcredito <> 0                                                                    ";
  $sSqlCheques .= "          then 'cheque'                                                                               ";
  $sSqlCheques .= "        end as tipo,                                                                                  ";
  $sSqlCheques .= "        case                                                                                          ";
  $sSqlCheques .= "          when rnvalordebito  is not null                                                             ";
  $sSqlCheques .= "           and rnvalordebito <> 0                                                                     ";
  $sSqlCheques .= "          then rnvalordebito                                                                          ";
  $sSqlCheques .= "          else rivalorcredito                                                                         ";
  $sSqlCheques .= "        end as valor                                                                                  ";
  $sSqlCheques .= "   from conciliapendcorrente                                                                          ";
  $sSqlCheques .= "  inner join fc_extratocaixa({$iInstituicao}, {$iConta}, null, null, false ) on ricaixa  = k89_id     ";
  $sSqlCheques .= "                                                                            and riautent = k89_autent ";
  $sSqlCheques .= "                                                                            and ridata   = k89_data   ";
  $sSqlCheques .= "  where k89_concilia = {$iConciliacao}                                                                ";
  $sSqlCheques .= "    and richeque       is not null                                                                    ";
  $sSqlCheques .= "    and richeque       <> 0                                                                           ";
  $sSqlCheques .= "    and rivalorcredito <> 0                                                                           ";
  $sSqlCheques .= " order by ridata                                                                                      ";

  $rsCheques    = db_query($sSqlCheques);

  return db_utils::getCollectionByRecord($rsCheques);
}

/**
 * Traz os movimentos da tesouraria de uma conciliacao
 * No relatorio o campo debito e credito serao impresos no item:
 * 1 - Debitos ainda nao creditados no extrato - ANEXO I
 *     onde: valor = (tipo = debito) - (tipo = cretito)
 * o campo cheque eh referente ao item:
 * 3- Cheques emitidos e ainda nao apresentados
 *    onde: valor = (tipo = cheque)
 *
 * @param integer $iConta codigo da conta (contabancaria)
 * @param integer $iConciliacao codigo da conciliacao
 * @return array de stdClass
 */
function movimentosTesouraria($iConta, $iConciliacao) {

  $iInstituicao = db_getsession("DB_instit");

  $sSqlTesouraria  = " select case                                                                                         ";
  $sSqlTesouraria .= "          when richeque       is not null                                                            ";
  $sSqlTesouraria .= "           and richeque <> 0                                                                         ";
  $sSqlTesouraria .= "           and rivalorcredito <> 0                                                                   ";
  $sSqlTesouraria .= "          then 'cheque'                                                                              ";
  $sSqlTesouraria .= "          when rnvalordebito  is not null                                                            ";
  $sSqlTesouraria .= "           and rnvalordebito <> 0                                                                    ";
  $sSqlTesouraria .= "            or richeque is not null                                                                  ";
  $sSqlTesouraria .= "           and richeque <> 0                                                                         ";
  $sSqlTesouraria .= "           and rnvalordebito <> 0                                                                    ";
  $sSqlTesouraria .= "          then 'debito'                                                                              ";
  $sSqlTesouraria .= "          when rivalorcredito is not null                                                            ";
  $sSqlTesouraria .= "           and rivalorcredito <> 0                                                                   ";
  $sSqlTesouraria .= "          then 'credito'                                                                             ";
  $sSqlTesouraria .= "        end as tipo,                                                                                 ";
  $sSqlTesouraria .= "        case                                                                                         ";
  $sSqlTesouraria .= "          when rnvalordebito  is not null                                                            ";
  $sSqlTesouraria .= "           and rnvalordebito <> 0                                                                    ";
  $sSqlTesouraria .= "          then 'D'                                                                                   ";
  $sSqlTesouraria .= "          else 'C'                                                                                   ";
  $sSqlTesouraria .= "        end as tipomov,                                                                              ";
  $sSqlTesouraria .= "        sum(case                                                                                     ";
  $sSqlTesouraria .= "              when rnvalordebito  is not null and rnvalordebito <> 0                                 ";
  $sSqlTesouraria .= "              then rnvalordebito                                                                     ";
  $sSqlTesouraria .= "              else rivalorcredito                                                                    ";
  $sSqlTesouraria .= "            end ) as valor,                                                                          ";
  $sSqlTesouraria .= "       'tesouraria' as movimento                                                                     ";
  $sSqlTesouraria .= "  from conciliapendcorrente                                                                          ";
  $sSqlTesouraria .= " inner join fc_extratocaixa({$iInstituicao}, {$iConta}, null, null, false ) on ricaixa  = k89_id     ";
  $sSqlTesouraria .= "                                                                           and riautent = k89_autent ";
  $sSqlTesouraria .= "                                                                           and ridata   = k89_data   ";
  $sSqlTesouraria .= " where k89_concilia = {$iConciliacao}                                                                ";
  $sSqlTesouraria .= "   and not exists (select 1
                                           from corgrupocorrente
                                          where k105_autent = k89_autent
                                            and k105_id     = k89_id
                                            and k105_data   = k89_data
                                            and k105_corgrupotipo in (2,3,5,6)
                                            and extract(year from k105_data) <= 2012 )  ";
  $sSqlTesouraria .= " group by tipo, tipomov, movimento;                                                                  ";
  $rsTesouraria  = db_query($sSqlTesouraria);

  return db_utils::getCollectionByRecord($rsTesouraria);
}

/**
 * Retorna os movimentos do extrato
 * Referencia no relatorio:
 * Item: 2 - Debitos varios nao contabilizados - ANEXO II
 *    onde: valor = (tipo = debitos) + (tipo = outros do k86_tipo (D))
 * Item: 4 - Credito varios nao contabilizados - ANEXO III
 *    onde: valor = (tipo = credito) + (tipo = outros do k86_tipo (C))
 *
 * @param integer $iConciliacao
 * @return array de stdClass
 */
function movimentosExtrato($iConciliacao) {

  $sSqlExtrato  = " select sum(k86_valor) as valor,                                 ";
  $sSqlExtrato .= "        k86_tipo ,                                               ";
  $sSqlExtrato .= "        case                                                     ";
  $sSqlExtrato .= "          when k85_tipoinclusao = 2   then 'outros'              ";
  $sSqlExtrato .= "          when k86_tipo         = 'D' then 'debito'              ";
  $sSqlExtrato .= "          when k86_tipo         = 'C' then 'credito'             ";
  $sSqlExtrato .= "        end as tipo                                              ";
  $sSqlExtrato .= "   from conciliapendextrato                                      ";
  $sSqlExtrato .= "  inner join extratolinha on k86_sequencial = k88_extratolinha   ";
  $sSqlExtrato .= "  inner join extrato      on k85_sequencial = k86_extrato        ";
  $sSqlExtrato .= "  where k88_concilia = {$iConciliacao}                           ";
  $sSqlExtrato .= "  group by k86_tipo, tipo;                                       ";

  $rsExtrato   = db_query($sSqlExtrato);

  return db_utils::getCollectionByRecord($rsExtrato);
}


/**
 * @param $oGet
 * @param $sData
 * @param $iIntituicao
 * @return mixed
 */
function saldoTesouraria($oGet, $sData, $iInstituicao) {

  $sSaldo  = " select sum(saldocontacaixa) as saldocontacaixa                                                                      ";
  $sSaldo .= " from ( select substr(fc_saltessaldo(c61_reduz, '{$sData}', '{$sData}', null, $iInstituicao) ,43, 13)::float as saldocontacaixa  ";
  $sSaldo .= "     from ( select distinct c61_reduz                                                                                ";
  $sSaldo .= "         from contabancaria                                                                                          ";
  $sSaldo .= "         inner join conplanocontabancaria on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial ";
  $sSaldo .= "         inner join conplanoreduz         on conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon             ";
  $sSaldo .= "         and conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu                                             ";
  $sSaldo .= "         and conplanoreduz.c61_anousu = {$oGet->iAno}                                                                ";
  $sSaldo .= "         and conplanoreduz.c61_instit = {$iInstituicao}                                                               ";
  $sSaldo .= "         where contabancaria.db83_sequencial = {$oGet->iConta}                                                       ";
  $sSaldo .= "     ) as x                                                                                                          ";
  $sSaldo .= " ) as y ;                                                                                                            ";

  $rsSaldo = db_query($sSaldo);

  return db_utils::fieldsMemory($rsSaldo, 0)->saldocontacaixa;

}
/**
 * Quebra a pagina e adiciona o cabecalho dos cheques
 * @param FPDF $oPdf
 * @param integer $iAltLinha
 */
function quebraCheque($oPdf, $iAltLinha) {

  quebraPagina($oPdf, $iAltLinha);
  $oPdf->Cell(190, 5, "CHEQUES EMITIDOS E AINDA NÃO APRESENTADOS", 1, 1, "C");
  $oPdf->Cell(63, $iAltLinha, "Data",     1, 0, "C");
  $oPdf->Cell(64, $iAltLinha, "Número",   1, 0, "C");
  $oPdf->Cell(63, $iAltLinha, "Valor R$", 1, 1, "C");

  return true;
}

/**
 * Quebra a pagina adicionando o numero de paginas
 * @param FPDF $oPdf
 * @param integer $iAltLinha
 */
function quebraPagina($oPdf, $iAltLinha) {

  $oPdf->AddPage();
  $oPdf->SetFont('Arial','',5);
  $oPdf->SetXY(170, 7);
  $oPdf->Cell(20,$iAltLinha, "Página: {$oPdf->AliasNbPages}/" . count($oPdf->pages), 0, 1, "R");
  return true;
}
