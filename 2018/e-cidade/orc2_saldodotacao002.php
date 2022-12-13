<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_orcreserva_classe.php")); // classe da reserva

$clorcreserva = new cl_orcreserva;
$clorcreserva->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o58_coddot");
$clrotulo->label("o83_autori");
$clrotulo->label("DBtxtmes");
$clrotulo->label("DBtxtmesacumulado");
$clrotulo->label("DBtxtperiodoini");
$clrotulo->label("DBtxtperiodofim");

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$sAcumulado = null;
if (isset($diausu) && $diausu != "") {
  $sAcumulado = " até " . db_formatar($diausu,'d');
}
$result = db_dotacaosaldo($nivel, 2, 2, true, " o58_coddot = {$coddot} and o58_anousu = {$anousu} ", $anousu, $dPeriodoIni, $dPeriodoFim);

if (pg_numrows($result) > 0) {
  db_fieldsmemory($result, 0);
} else {
  db_redireciona("db_erros.php?fechar=true&db_erro=Dotação não cadastrada.");
}

$x = array("01" => "Janeiro",
           "02" => "Fevereiro",
           "03" => "Março",
           "04" => "Abril",
           "05" => "Maio",
           "06" => "Junho",
           "07" => "Julho",
           "08" => "Agosto",
           "09" => "Setembro",
           "10" => "Outubro",
           "11" => "Novembro",
           "12" => "Dezembro");

$head4 = "SALDO DESPESA";
$head6 = "REDUZIDO DA DOTAÇÃO: {$o58_coddot}";
$head7 = "MÊS/ANO: " . $x[$mesusu] . "/{$anousu}";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$troca = 1;
$alt   = 4;

$pdf->addpage("L");
$pdf->setfont('arial','b',8);

$pdf->cell(149,$alt,"Descrição",1,0,"C",1);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->cell( 63,$alt,"Financeiro / ".$x[$mesusu],1,0,"C",1);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->cell( 63,$alt,"Acumulado ".$sAcumulado,1,1,"C",1);

$pdf->cell( 21,$alt,"Orgão:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 30,$alt,$o58_orgao,1,0,"L",0);
$pdf->cell( 98,$alt,$o40_descr,1,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Saldo Inicial:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($dot_ini,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Saldo Inicial:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($dot_ini,'f'),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell( 21,$alt,"Unidade:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 30,$alt,$o58_unidade,1,0,"L",0);
$pdf->cell( 98,$alt,$o41_descr,1,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Saldo Anterior:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($saldo_anterior,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Saldo Anterior:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,0,1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell( 21,$alt,"Função:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 30,$alt,$o58_funcao,1,0,"L",0);
$pdf->cell( 98,$alt,$o52_descr,1,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Suplementação:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($suplementado,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Suplementação:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($suplementado_acumulado,'f'),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell( 21,$alt,"Sub-Função:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 30,$alt,$o58_subfuncao,1,0,"L",0);
$pdf->cell( 98,$alt,$o53_descr,1,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Redução:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($reduzido,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Redução:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($reduzido_acumulado,'f'),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell( 21,$alt,"Programa:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 30,$alt,$o58_programa,1,0,"L",0);
$pdf->cell( 98,$alt,$o54_descr,1,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Empenhado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($empenhado,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Empenhado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($empenhado_acumulado,'f'),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell( 21,$alt,"Proj/Atividade:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 30,$alt,$o58_projativ,1,0,"L",0);
$pdf->cell( 98,$alt,$o55_descr,1,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Anulado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($anulado,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Anulado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($anulado_acumulado,'f'),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell( 21,$alt,"Elemento:",1,0,"L",0);
$pdf->setfont('arial','',8);

$pdf->cell( 30,$alt,db_formatar($o58_elemento,"elemento_int"),1,0,"L",0);

$pdf->cell( 98,$alt,$o56_descr,1,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Liquidado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($liquidado,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Liquidado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,(db_formatar($liquidado_acumulado,'f')),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell( 21,$alt,"Recurso:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 30,$alt,$o58_codigo,1,0,"L",0);
$pdf->cell( 98,$alt,$o15_descr,1,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Pago:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($pago,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Pago:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,(db_formatar($pago_acumulado,'f')),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell(149,$alt,"",0,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"A pagar liquidado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($atual_a_pagar_liquidado,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"A pagar liquidado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,(db_formatar($liquidado_acumulado-$pago_acumulado,'f')),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell(149,$alt,"",0,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"A pagar empenhado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($atual_a_pagar,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"A pagar empenhado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado,'f'),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell(149,$alt,"",0,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Saldo dotação:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($atual,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Saldo dotação:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($dot_ini+$suplementado_acumulado-$reduzido_acumulado-$empenhado_acumulado+$anulado_acumulado,'f'),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell(149,$alt,"",0,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Reservado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($reservado,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Reservado:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($reservado,'f'),1,1,"R",0);

$pdf->setfont('arial','b',8);
$pdf->cell(149,$alt,"",0,0,"L",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Saldo disponível:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($atual_menos_reservado,'f'),1,0,"R",0);
$pdf->cell(1.5,$alt,"",0,0,"C",0);
$pdf->setfont('arial','b',8);
$pdf->cell( 31,$alt,"Saldo disponível:",1,0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell( 32,$alt,db_formatar($dot_ini+$suplementado_acumulado-$reduzido_acumulado-$empenhado_acumulado+$anulado_acumulado-$reservado,'f'),1,1,"R",0);

if (isset($reservado) && ($reservado > 0)) {

  /* tem reserva */
  $ini = $anousu . "-" . $mesusu . "-01";
  /* se o mes for igual ao mes atual, pegar o dia atual */
  if ($mesusu == date("m", db_getsession("DB_datausu"))) {
    $ini = date("Y-m-d", db_getsession("DB_datausu"));
  }
  $fim = $anousu."-12-31";

  //---------------  reserva de empenho
  $sCamposReserva = " o80_codres, o83_autori, o80_dtini, o80_dtfim, o80_valor, o80_descr ";
  $sWhereReserva  = " o80_coddot = $coddot and o80_anousu = $anousu ";
  $sWhereReserva .= " and ('$fim' >= o80_dtfim and o80_dtfim >='$ini') and '$ini' >= o80_dtini ";
  $sWhereReserva .= " and o83_codres is not null ";
  $sSqlBuscaReservas = $clorcreserva->sql_query_reservas(null, $sCamposReserva, "o80_codres", $sWhereReserva);
  $res = $clorcreserva->sql_record($sSqlBuscaReservas);
  if ($clorcreserva->numrows > 0) {

    $pdf->ln(4);
    $o80_valor_total = 0;
    for ($x = 0; $x < $clorcreserva->numrows; $x++) {
      db_fieldsmemory($res,$x,true);
      if($pdf->gety() > $pdf->h - 30 || $x == 0 ){
        if($pdf->gety() > $pdf->h - 30){
          $pdf->addpage("L");
        }
        $pdf->setfont('arial','b',8);
        $pdf->cell( 277,$alt,"Reserva - Autorização de Empenho",1,1,"C",1);
        $pdf->cell(29.8,$alt,"Código da reserva",1,0,"C",1);
        $pdf->cell(29.8,$alt,"N".CHR(176)." da autorização",1,0,"C",1);
        $pdf->cell(29.8,$alt,"Data de início",1,0,"C",1);
        $pdf->cell(29.8,$alt,"Data final",1,0,"C",1);
        $pdf->cell(29.8,$alt,"Valor da reserva",1,0,"C",1);
        $pdf->multicell(128,$alt,"Descrição da reserva",1,"C",1);
        $pdf->setfont('arial','',8);
      }
      $pdf->cell(29.8,$alt,$o80_codres,1,0,"C",0);
      $pdf->cell(29.8,$alt,$o83_autori,1,0,"C",0);
      $pdf->cell(29.8,$alt,$o80_dtini,1,0,"C",0);
      $pdf->cell(29.8,$alt,$o80_dtfim,1,0,"C",0);
      $pdf->cell(29.8,$alt,$o80_valor,1,0,"R",0);
      $pdf->multicell(128,$alt,$o80_descr,1,"L",0);
      $o80_valor_total += $o80_valor;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(119,$alt,"Valor total R$ ","TBL",0,"R",1);
    $pdf->cell( 30,$alt,db_formatar($o80_valor_total,"f"),"TBR",1,"R",1);
  }

  //---------------  reserva de automática
  $res=$clorcreserva->sql_record(
    $clorcreserva->sql_query_reservas(null,
                                      "*",
                                      "o80_codres",
                                      "o80_coddot = {$coddot} and o80_anousu = {$anousu}
                                      and (o80_dtini <= '{$dPeriodoFim}' and o80_dtfim >= '{$dPeriodoFim}')
                                      and o80_codres in (select o84_codres from orcreservager order by o84_codres)"));

  if ($clorcreserva->numrows > 0) {

    $pdf->ln(4);
    $o80_valor_total = 0;
    for ($x = 0; $x < $clorcreserva->numrows; $x++) {

      db_fieldsmemory($res, $x, true);
      if($pdf->gety() > $pdf->h - 30 || $x == 0 ){
        if($pdf->gety() > $pdf->h - 30){
          $pdf->addpage("L");
        }
        $pdf->setfont('arial','b',8);
        $pdf->cell(  277,$alt,"Reserva - Automatica de Saldo",1,1,"C",1);
        $pdf->cell(37.25,$alt,"Código da reserva",1,0,"C",1);
        $pdf->cell(37.25,$alt,"Data de início",1,0,"C",1);
        $pdf->cell(37.25,$alt,"Data final",1,0,"C",1);
        $pdf->cell(37.25,$alt,"Valor da reserva",1,0,"C",1);
        $pdf->multicell(128,$alt,"Descrição da reserva",1,"C",1);
        $pdf->setfont('arial','',8);
      }
      $pdf->cell(37.25,$alt,$o80_codres,1,0,"C",0);
      $pdf->cell(37.25,$alt,$o80_dtini,1,0,"C",0);
      $pdf->cell(37.25,$alt,$o80_dtfim,1,0,"C",0);
      $pdf->cell(37.25,$alt,$o80_valor,1,0,"R",0);
      $pdf->multicell(128,$alt,$o80_descr,1,"L",0);
      $o80_valor_total += $o80_valor;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(111.75,$alt,"Valor total: ","TBL",0,"R",1);
    $pdf->cell( 37.25,$alt,db_formatar($o80_valor_total,"f"),"TBR",1,"R",1);
  }

  //---------------  solicitação de reserva
  $res = $clorcreserva->sql_record(
    $clorcreserva->sql_query_reservas(null,
                                      "*",
                                      "o80_codres",
                                      "o80_coddot = {$coddot} and o80_anousu = {$anousu}
                                      and (o80_dtini <= '{$dPeriodoFim}' and o80_dtfim >= '{$dPeriodoFim}')
                                      and o80_codres in (select o82_codres from orcreservasol order by o82_codres)"));

  if ($clorcreserva->numrows > 0) {

    $pdf->ln(4);
    $o80_valor_total = 0;
    for($x = 0; $x < $clorcreserva->numrows; $x++) {

      db_fieldsmemory($res, $x, true);
      if($pdf->gety() > $pdf->h - 30 || $x == 0 ){
        if($pdf->gety() > $pdf->h - 30){
          $pdf->addpage("L");
        }
        $pdf->setfont('arial','b',8);
        $pdf->cell(  277,$alt,"Reserva - Solicitações de Reserva  ",1,1,"C",1);
        $pdf->cell(37.25,$alt,"Código da reserva",1,0,"C",1);
        $pdf->cell(37.25,$alt,"Data de início",1,0,"C",1);
        $pdf->cell(37.25,$alt,"Data final",1,0,"C",1);
        $pdf->cell(37.25,$alt,"Valor da reserva",1,0,"C",1);
        $pdf->cell(37.25,$alt,"Código Solicitação",1,0,"C",1); 
        $pdf->multicell(90.75,$alt,"Descrição da reserva",1,"C",1);
        $pdf->setfont('arial','',8);
      }
      $pdf->cell(37.25,$alt,$o80_codres,1,0,"C",0);
      $pdf->cell(37.25,$alt,$o80_dtini,1,0,"C",0);
      $pdf->cell(37.25,$alt,$o80_dtfim,1,0,"C",0);
      $pdf->cell(37.25,$alt,$o80_valor,1,0,"R",0);       
      $pdf->cell(37.25,$alt,$pc11_numero,1,0,"C",0); 
      $pdf->multicell(90.75,$alt,$o80_descr,1,"R",0);
      $o80_valor_total += $o80_valor;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(111.75,$alt,"Valor total: ","TBL",0,"R",1);
    $pdf->cell( 37.25,$alt,db_formatar($o80_valor_total,"f"),"TBR",1,"R",1);
  }

  //---------------  reservas suplementadas
  $res=$clorcreserva->sql_record(
    $clorcreserva->sql_query_reservas(null,
                                      "*",
                                      "o80_codres",
                                      "o80_coddot = {$coddot} and o80_anousu = {$anousu}
                                      and (o80_dtini <= '{$dPeriodoFim}' and o80_dtfim >= '{$dPeriodoFim}')
                                      and o80_codres in (select o81_codres from orcreservasup order by o81_codres)"));

  if ($clorcreserva->numrows > 0) {

    $pdf->ln(4);
    $o80_valor_total = 0;

    for ($x = 0; $x < $clorcreserva->numrows; $x++) {

      db_fieldsmemory($res, $x, true);
      if ($pdf->gety() > $pdf->h - 30 || $x == 0 ) {

        if ($pdf->gety() > $pdf->h - 30) {
          $pdf->addpage("L");
        }
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(277, $alt, "Reserva - Suplementação/Redução", 1, 1, "C", 1);
        $pdf->cell(37.25, $alt, "Código da reserva", 1, 0, "C", 1);
        $pdf->cell(37.25, $alt, "Data de início", 1, 0, "C", 1);
        $pdf->cell(37.25, $alt, "Data final", 1, 0, "C", 1);
        $pdf->cell(37.25, $alt, "Valor da reserva", 1, 0, "C", 1);
        $pdf->multicell(128, $alt, "Descrição da reserva", 1, "C", 1);
        $pdf->setfont('arial', '', 8);
      }
      $pdf->cell(37.25, $alt, $o80_codres, 1, 0, "C", 0);
      $pdf->cell(37.25, $alt, $o80_dtini, 1, 0, "C", 0);
      $pdf->cell(37.25, $alt, $o80_dtfim, 1, 0, "C", 0);
      $pdf->cell(37.25, $alt, $o80_valor, 1, 0, "R", 0);
      $pdf->multicell(128, $alt, $o80_descr, 1, "R", 0);
      $o80_valor_total += $o80_valor;
    }
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(111.75, $alt, "Valor total: ", "TBL", 0, "R", 1);
    $pdf->cell(37.25, $alt, db_formatar($o80_valor_total, "f"), "TBR", 1, "R", 1);
  }

  //---------------  reservas manuais
  $res = $clorcreserva->sql_record(
    $clorcreserva->sql_query_reservas(null,
                                      "*",
                                      "o80_codres",
                                      "o80_coddot = {$coddot} and o80_anousu = {$anousu}
                                      and (o80_dtini <= '{$dPeriodoFim}' and o80_dtfim >= '{$dPeriodoFim}')
                                      and o83_codres is null
                                      and o84_codres is null
                                      and o82_codres is null
                                      and o81_codres is null"));

  if ($clorcreserva->numrows > 0) {

    $pdf->ln(4);
    $o80_valor_total = 0;
    for ($x = 0; $x < $clorcreserva->numrows; $x++) {

      db_fieldsmemory($res, $x, true);
      if ($pdf->gety() > $pdf->h - 30 || $x == 0 ) {

        if ($pdf->gety() > $pdf->h - 30){
          $pdf->addpage("L");
        }
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(277, $alt, "Reserva - Manual", 1, 1, "C", 1);
        $pdf->cell(37.25, $alt, "Código da reserva", 1, 0, "C", 1);
        $pdf->cell(37.25, $alt, "Data de início", 1, 0, "C", 1);
        $pdf->cell(37.25, $alt, "Data final", 1, 0, "C", 1);
        $pdf->cell(37.25, $alt, "Valor da reserva", 1, 0, "C", 1);
        $pdf->multicell(128, $alt, "Descrição da reserva", 1, "C", 1);
        $pdf->setfont('arial', '', 8);
      }
      $pdf->cell(37.25, $alt, $o80_codres, 1, 0, "C", 0);
      $pdf->cell(37.25, $alt, $o80_dtini, 1, 0, "C", 0);
      $pdf->cell(37.25, $alt, $o80_dtfim, 1, 0, "C", 0);
      $pdf->cell(37.25, $alt, $o80_valor, 1, 0, "R", 0);
      $pdf->multicell(128, $alt, $o80_descr, 1, "R", 0);
      $o80_valor_total += $o80_valor;
    }
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(111.75, $alt, "Valor total: ", "TBL", 0, "R", 1);
    $pdf->cell(37.25, $alt, db_formatar($o80_valor_total, "f"), "TBR", 1, "R", 1);
  }
}
$pdf->Output();
