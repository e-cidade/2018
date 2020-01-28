<?php
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$classinatura   = new cl_assinatura;

$tipo_mesini    = 1;
$tipo_mesfim    = 1;
$tipo_impressao = $origem;
$iAnoUsu        = db_getsession("DB_anousu");

$sSqlInst     = " select codigo,                                            ";
$sSqlInst    .= "        nomeinstabrev                                      ";
$sSqlInst    .= "   from db_config                                          ";
$sSqlInst    .= "  where codigo in(".str_replace('-',', ',$db_selinstit).") ";
$rsSqlInst    = db_query($sSqlInst);
$iNumRowsInst = pg_numrows($rsSqlInst);

$sDescrInst   = '';
$sVirgula     = '';
for ($iIndInst = 0; $iIndInst < $iNumRowsInst; $iIndInst++) {

  $oInstituicao = db_utils::fieldsMemory($rsSqlInst, $iIndInst);
  $sDescrInst  .= $sVirgula.$oInstituicao->nomeinstabrev;
  $sVirgula     = ', ';
}

$sTipo = '';
if ($origem == "O") {
  $sTipo = "ORÇAMENTO";
} else {

  $sTipo = "BALANÇO";
  if ($tipo_balanco == 2) {
    $sTipo .= "-EMPENHADO";
  } else if ($tipo_balanco == 3) {
    $sTipo .= "-LIQUIDADO";
  } else {
    $sTipo .= "-PAGO";
  }

  if ($opcao == 3) {
    $head6 = "PERÍODO : ".db_formatar($perini, 'd')." A ".db_formatar($perfin, 'd') ;
  } else {
    $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini, 5, 2)))." A ".strtoupper(db_mes(substr($perfin, 5, 2)));
  }
}

$head2 = "DEMONSTRATIVO DA RECEITA/DESPESA";
$head3 = "SEGUNDO A CATEGORIA ECONÔMICA";
$head4 = "ANEXO (1) EXERCÍCIO: {$iAnoUsu} - {$sTipo}";
$head5 = "INSTITUIÇÕES: {$sDescrInst}";

db_inicio_transacao();

$sSqlWork1  = " create temp table work1 as                ";
$sSqlWork1 .= " select o56_elemento||'00' as elemento,    ";
$sSqlWork1 .= "       o56_descr                 as descr, ";
$sSqlWork1 .= "       0::float8                 as valor  ";
$sSqlWork1 .= "  from orcelemento                         ";
$sSqlWork1 .= " where o56_anousu = {$iAnoUsu}             ";
$sSqlWork1 .= " union                                     ";
$sSqlWork1 .= " select o57_fonte,                         ";
$sSqlWork1 .= "        o57_descr,                         ";
$sSqlWork1 .= "        0::float8 as valor                 ";
$sSqlWork1 .= "   from orcfontes                          ";
$sSqlWork1 .= "  where o57_anousu = {$iAnoUsu}            ";
$rsSqlWork1 = db_query($sSqlWork1);

$sWhere     = " o70_instit in (".str_replace('-',', ',$db_selinstit).")";
$dataini    = $perini;
$datafin    = $perfin;
$result_rec = db_receitasaldo(11, 1, 3, true, $sWhere, $iAnoUsu, $dataini, $datafin);

$valor = 0;
for ($i = 0; $i < pg_numrows($result_rec); $i++) {

  db_fieldsmemory($result_rec, $i);
  if ($tipo_impressao == "O") {
    $valor = $saldo_inicial;
  } else {
    $valor = $saldo_arrecadado;
  }

  $sSqlwork1Update  = "update work1 set valor = valor+{$valor} where work1.elemento = '{$o57_fonte}'";
  $rsSqlwork1Update = db_query($sSqlwork1Update);
  $executa          = true;
  $conta            = 0;
}

$sWhere     = " o58_instit in (".str_replace('-',', ',$db_selinstit).") ";
$result_rec = db_dotacaosaldo(7, 3, 3, true, $sWhere, $iAnoUsu, $dataini, $datafin, null, null, null, $tipo_balanco);
$valor      = 0;
if ($tipo_impressao == "O") {
  $tipo_balanco = 1;
}

for ($i = 0; $i < pg_numrows($result_rec); $i++) {

  db_fieldsmemory($result_rec, $i);
  if ($tipo_balanco == 1) {
    $valor = $dot_ini;
  } else if ($tipo_balanco == 2) {
    $valor = $empenhado-$anulado;
  } else if ($tipo_balanco == 3) {
    $valor = $liquidado;
  } else {
    $valor = $pago;
  }

  $sSqlwork1Update  = "update work1 set valor = valor+{$valor} where work1.elemento = '{$o58_elemento}00'";
  $rsSqlwork1Update = db_query($sSqlwork1Update);

  $conta            = 0;
  $executa          = true;
  while ($executa == true) {

    $o58_elemento     = db_le_mae($o58_elemento, false);
    $sSqlwork1Update  = "update work1 set valor = valor+{$valor} where work1.elemento = '{$o58_elemento}00'";
    $rsSqlwork1Update = db_query($sSqlwork1Update);

    if (substr($o58_elemento, 2, 13) == "0000000000000") {
      $executa = false;
    }

    $conta ++;
    if ($conta > 10) {
      $executa = false;
    }
  }
}

db_fim_transacao(false);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);

$total  = 0;
$troca  = 1;
$alt    = 4;
$a      = 0;
$b      = 0;
$valora = 0;
$valorb = 0;

$descra = array();
$vlra   = array();
$descrb = array();
$vlrb   = array();

$pdf->addpage();
$pdf->setfont('arial', 'b', 8);
$pdf->cell(95, $alt, "R E C E I T A", 0, 0, "C", 0);
$pdf->cell(95, $alt, "D E S P E S A", 0, 1, "C", 0);
$pdf->ln(5);

if ($iAnoUsu < 2008) {

   $sSqlWork1  = "   select *                                     ";
   $sSqlWork1 .= "     from work1                                 ";
   $sSqlWork1 .= "    where elemento::bigint in(411000000000000,  ";
   $sSqlWork1 .= "                              412000000000000,  ";
   $sSqlWork1 .= "                              413000000000000,  ";
   $sSqlWork1 .= "                              414000000000000,  ";
   $sSqlWork1 .= "                              415000000000000,  ";
   $sSqlWork1 .= "                              416000000000000,  ";
   $sSqlWork1 .= "                              417000000000000,  ";
   $sSqlWork1 .= "                              419000000000000,  ";
   $sSqlWork1 .= "                              470000000000000,  ";
   $sSqlWork1 .= "                              490000000000000   ";
   $sSqlWork1 .= "                             )                  ";
   $sSqlWork1 .= " order by elemento                              ";
} elseif ($iAnoUsu >= 2008) {

  $sSqlWork1   = "   select *                                      ";
  $sSqlWork1  .= "     from work1                                  ";
  $sSqlWork1  .= "    where elemento::bigint in(411000000000000,   ";
  $sSqlWork1  .= "                              412000000000000,   ";
  $sSqlWork1  .= "                              413000000000000,   ";
  $sSqlWork1  .= "                              414000000000000,   ";
  $sSqlWork1  .= "                              415000000000000,   ";
  $sSqlWork1  .= "                              416000000000000,   ";
  $sSqlWork1  .= "                              417000000000000,   ";
  $sSqlWork1  .= "                              419000000000000,   ";
  $sSqlWork1  .= "                              470000000000000,   ";
  $sSqlWork1  .= "                              910000000000000,   ";
  $sSqlWork1  .= "                              920000000000000,   ";
  $sSqlWork1  .= "                              970000000000000,   ";
  $sSqlWork1  .= "                              980000000000000    ";
  $sSqlWork1  .= "                             )                   ";
  $sSqlWork1  .= " order by elemento                               ";
}

$rsSqlWork1    = db_query($sSqlWork1);
$iNumRowsWork1 = pg_numrows($rsSqlWork1);

for ($i = 0; $i < $iNumRowsWork1; $i++) {

  db_fieldsmemory($rsSqlWork1, $i);

  if ($valor == 0) {
    continue;
  }

  if ($elemento != "920000000000000" && $elemento != "980000000000000" ) {

	  $descra[$a] = $descr;
	  $vlra[$a]   = $valor;
	  $a         += 1;
	  $valora    += $valor;
  }
}

$sSqlWork1     = "select * from work1 where substr(elemento,1,1) = '3' order by elemento";
$rsSqlWork1    = db_query($sSqlWork1);
$iNumRowsWork1 = pg_numrows($rsSqlWork1);

for ($i = 0; $i < $iNumRowsWork1; $i++) {

  db_fieldsmemory($rsSqlWork1, $i);
  if (substr($elemento, 3, 12) == "000000000000" && substr($elemento, 2, 1) != "0") {

    if (substr($elemento, 1, 1) == "3") {

      if ($valor == 0 ) {
        continue;
      }

      $descrb[$b] = $descr;
      $vlrb[$b]   = $valor;
      $b         += 1;
      $valorb     = ($valorb + $valor);
    }
  }
}

$sSqlWork1  = "   select *                            ";
$sSqlWork1 .= "     from work1                        ";
$sSqlWork1 .= "    where elemento = '410000000000000' ";
$sSqlWork1 .= "       or substr(elemento,1,2) = '47'  ";
$sSqlWork1 .= " order by elemento                     ";
$rsSqlWork1 = db_query($sSqlWork1);

db_fieldsmemory($rsSqlWork1, 0);

$pdf->cell(70, $alt, $descr, 0, 0, "L", 0);
$pdf->cell(25, $alt, db_formatar($valora, 'f'), 0, 0, "R", 0);

$sSqlWork1  = "select * from work1 where elemento = '330000000000000'";
$rsSqlWork1 = db_query($sSqlWork1);

db_fieldsmemory($rsSqlWork1, 0);

$pdf->setfont('arial', 'b', 8);
$pdf->cell(70, $alt, $descr, 0, 0, "L", 0);
$pdf->cell(25, $alt, db_formatar($valorb, 'f'), 0, 1, "R", 0);

$numreg = (sizeof($descra)>sizeof($descrb)?sizeof($descra):sizeof($descrb));
for($i = 0; $i < $numreg; $i++) {

  $pdf->setfont('arial','',7);
  if (isset($vlra[$i])) {

    $pdf->cell(70,$alt,"  ".$descra[$i],0,0,"L",0);
    $pdf->cell(25,$alt,db_formatar($vlra[$i]+0,'f'),0,0,"R",0);
  } else {

    $pdf->cell(70,$alt,"  ",0,0,"L",0);
    $pdf->cell(25,$alt," ",0,0,"R",0);
  }

  if (isset($vlrb[$i])) {

    $pdf->cell(70,$alt,"  ".$descrb[$i],0,0,"L",0);
    $pdf->cell(25,$alt,db_formatar($vlrb[$i]+0,'f'),0,0,"R",0);
  }

  $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
}

$valorc = $valora - $valorb;
$pdf->setfont('arial','b',8);
if ($valorc > 0) {

  $pdf->cell(70,$alt,"",0,0,"L",0);
  $pdf->cell(25,$alt,'',0,0,"L",0);
  $pdf->cell(70,$alt,"  "."SUPERAVIT",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($valorc,'f'),0,1,"R",0);
  $pdf->cell(70,$alt,"  "."TOTAIS",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($valora,'f'),0,0,"R",0);
  $pdf->cell(70,$alt,"  "."TOTAIS",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($valorb+$valorc,'f'),0,1,"R",0);

  $pdf->Ln(1);
  $pdf->cell(70,$alt,"  "."SUPERAVIT ORÇAMENTO CORRENTE",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($valorc,'f'),0,1,"R",0);
} else if ($valorc < 0) {

  $pdf->cell(70,$alt,"  "."DÉFICIT",0,0,"L",0);
  $pdf->cell(25,$alt,db_formatar($valorc*-1,'f'),0,1,"R",0);
  $pdf->cell(70,$alt,"  "."TOTAIS",0,0,"L",0);
  $pdf->cell(25,$alt,db_formatar($valora+($valorc*-1),'f'),0,0,"R",0);
  $pdf->cell(70,$alt,"  "."TOTAIS");
  $pdf->cell(25,$alt,db_formatar($valorb,'f'),0,1,"R",0);
  $pdf->cell(70,$alt,"",0,0,"L",0);
  $pdf->cell(25,$alt,'',0,0,"L",0);

  $pdf->Ln(1);
  $pdf->cell(70,$alt,"  "."DÉFICIT ORÇAMENTO CORRENTE",0,0,"L",0);
  $pdf->cell(25,$alt,db_formatar($valorc*-1,'f'),0,1,"R",0);
}

$pdf->ln(5);

$valord = 0;
$valore = 0;
$d      = 0;
$e      = 0 ;
$descrd = array();
$vlrd   = array();
$descre = array();
$vlre   = array();

$sSqlWork1     = "select * from work1 where substr(elemento,2,1) = '2' or substr(elemento,2,1) = '8' order by elemento";
$rsSqlWork1    = db_query($sSqlWork1);
$iNumRowsWork1 = pg_numrows($rsSqlWork1);

for ($i = 0; $i < $iNumRowsWork1; $i++) {

  db_fieldsmemory($rsSqlWork1, $i);

  if ((substr($elemento,3,12) == "000000000000"
       && substr($elemento,2,1) != "0" && substr($elemento,0,1) != "9")
       || ($elemento == "920000000000000" || $elemento == "980000000000000" )) {

    if (substr($elemento,1,1) == "2" || substr($elemento,1,1) == "8") {

      if ($valor == 0) {
        continue;
      }

      $descrd[$d] = $descr;
      $vlrd[$d]   = $valor;
      $d         += 1;
      $valord     = ($valord + $valor);
    }
  }
}

$sSqlWork1     = "select * from work1 where substr(elemento,1,1) = '3' order by elemento";
$rsSqlWork1    = db_query($sSqlWork1);
$iNumRowsWork1 = pg_numrows($rsSqlWork1);

for ($i = 0; $i < $iNumRowsWork1; $i++) {

  db_fieldsmemory($rsSqlWork1,$i);
  if (substr($elemento,3,12) == "000000000000" && substr($elemento,2,1) != "0") {

    if (substr($elemento,1,1) == "4") {

      if ($valor == 0) {
        continue;
      }

      $descre[$e] = $descr;
      $vlre[$e]   = $valor;
      $e         += 1;
      $valore     = ($valore + $valor) ;
    }
  }
}

$pdf->setfont('arial','b',8);

$sSqlWork1  = "select *,1 from work1 where elemento = '420000000000000'";
$rsSqlWork1 = db_query($sSqlWork1);

db_fieldsmemory($rsSqlWork1,0);

$pdf->cell(70,$alt, $descr,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($valord,'f'),0,0,"R",0);

$sSqlWork1  = "select * from work1 where elemento =   '340000000000000'";
$rsSqlWork1 = db_query($sSqlWork1);

db_fieldsmemory($rsSqlWork1,0);

$pdf->cell(70,$alt, $descr,0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($valore,'f'),0,1,"R",0);

$pdf->setfont('arial','',8);

$numreg = (sizeof($descrd) > sizeof($descre)? sizeof($descrd) : sizeof($descre));

for ($i = 0; $i < $numreg; $i++) {

  if (isset($vlrd[$i])) {

    $pdf->setfont('arial','',7);
    $pdf->cell(70,$alt,"  ".$descrd[$i],0,0,"L",0);
    $pdf->cell(25,$alt,db_formatar($vlrd[$i]+0,'f'),0,0,"R",0);
  } else {

    $pdf->cell(70,$alt,"  ",0,0,"L",0);
    $pdf->cell(25,$alt," ",0,0,"R",0);
  }

  if (isset($vlre[$i]) ) {

    $pdf->cell(70,$alt,"  ".$descre[$i],0,0,"L",0);
    $pdf->cell(25,$alt,db_formatar($vlre[$i]+0,'f'),0,0,"R",0);
  }

  $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
}

$sSqlWork1     = "select * from work1 where elemento =   '390000000000000' or elemento = '370000000000000'";
$rsSqlWork1    = db_query($sSqlWork1);
$iNumRowsWork1 = pg_numrows($rsSqlWork1);

if ($iNumRowsWork1 > 0) {

  $vlr_reserva = 0;
  for ($y = 0; $y < $iNumRowsWork1; $y++) {

    db_fieldsmemory($rsSqlWork1, $y);
    $vlr_reserva += $valor;
  }

  $pdf->ln(3);
  $pdf->cell(70,$alt,"",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,"",0,0,"R",0);
  $pdf->cell(70,$alt,"RESERVA DE CONTINGÊNCIA",0,0,"L",0,'');
  $pdf->cell(25,$alt,db_formatar($vlr_reserva,'f'),0,1,"R",0);
} else {
  $vlr_reserva = 0;
}

$pdf->ln(4);
$pdf->setfont('arial','b',8);

$valorf = ($valora+$valord)-($valorb+$valore)-$vlr_reserva ;

if ($valorf > 0 ) {

  $pdf->cell(70,$alt,"",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,"",0,0,"R",0);
  $pdf->cell(70,$alt,"SUPERAVIT DA EXECUÇÃO ORÇAMENTARIA",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($valorf,'f'),0,1,"R",0);

  $pdf->cell(70,$alt,"TOTAIS",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($valora+$valord,'f'),0,0,"R",0);
  $pdf->cell(70,$alt,"TOTAIS",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($valorb+$valore+$valorf+$vlr_reserva,'f'),0,1,"R",0);
} else {

  if ($valorf < 0) {

    $pdf->cell(70,$alt,"DEFICIT",0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($valorf,'f'),0,0,"R",0);
    $pdf->cell(70,$alt,"",0,0,"L",0,'','.');
    $pdf->cell(25,$alt,"",0,1,"R",0);
  }

  $pdf->cell(70,$alt,"TOTAIS",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($valora+$valord+(abs($valorf)),'f'),0,0,"R",0);
  $pdf->cell(70,$alt,"TOTAIS",0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($valorb+$valore+$vlr_reserva,'f'),0,1,"R",0);
}

$pdf->ln(2);
$pdf->setfont('arial','b',8);

$pdf->ln(8);
$pdf->cell(55,$alt,"RESUMO",0,0,"C",0);
$pdf->cell(30,$alt,"RECEITAS",0,0,"C",0);
$pdf->cell(30,$alt,"DESPESAS",0,1,"C",0);

$pdf->ln(3);
$pdf->setfont('arial','',7);
$pdf->cell(55,$alt,"RECEITAS E DESPESAS CORRENTE",0,0,"L",0,'','.');
$pdf->cell(30,$alt,db_formatar($valora,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($valorb,'f'),0,1,"R",0);
$pdf->cell(55,$alt,"RECEITAS E DESPESAS CAPITAL",0,0,"L",0,'','.');
$pdf->cell(30,$alt,db_formatar($valord,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($valore,'f'),0,1,"R",0);
$pdf->cell(55,$alt,"RESERVA DE CONTINGÊNCIA",0,0,"L",0,'','.');
$pdf->cell(30,$alt,'',0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($vlr_reserva,'f'),0,1,"R",0);

$pdf->ln(2);
$pdf->setfont('arial','b',8);
$pdf->cell(55,$alt,"SOMA",0,0,"L",0,'','.');
$pdf->cell(30,$alt,db_formatar($valora+$valord,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($valorb+$valore+$vlr_reserva,'f'),0,1,"R",0);

$pdf->ln(1);

$valorf = ($valora+$valord)-($valorb+$valore+$vlr_reserva);
if ($valorf > 0 ) {

  $pdf->cell(55,$alt,"SUPERAVIT DA EXEC. ORÇAMENTARIA",0,0,"L",0,'','.');
  $pdf->cell(30,$alt,'',0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($valorf,'f'),0,1,"R",0);

  $pdf->ln(1);
  $pdf->cell(55,$alt,"TOTAL",0,0,"L",0,'','.');
  $pdf->cell(30,$alt,db_formatar($valora+$valord,'f'),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($valorb+$valore+$vlr_reserva+$valorf,'f'),0,1,"R",0);
} else {

  if ($valorf < 0) {

    $pdf->cell(55,$alt,"DEFICIT",0,0,"L",0,'','.');
    $pdf->cell(30,$alt,db_formatar($valorf*-1,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,'',0,1,"R",0);
  }

  $pdf->ln(1);
  $pdf->cell(55, $alt, "TOTAL", 0, 0, "L", 0, '', '.');
  $pdf->cell(30, $alt, db_formatar($valora+$valord+($valorf*-1),'f'), 0, 0, "R", 0);
  $pdf->cell(30, $alt, db_formatar($valorb+$valore+$vlr_reserva,'f'), 0, 1, "R", 0);
}

$pdf->ln(14);

if ($origem != "O") {
  assinaturas($pdf, $classinatura, 'BG', true, false);
}

$pdf->Output();
