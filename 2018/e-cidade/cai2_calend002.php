<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("fpdf151/PDFDocument.php");

$oGet = db_utils::postMemory($_GET);
$oGet->anousu = !empty($oGet->anousu) ? $oGet->anousu : db_getsession("DB_anousu");

try {

  $pdf = new PDFDocument();
  $pdf->disableHeaderDefault();

  $pdf->setHeader(function() use($pdf) {
    $sql = "select nomeinst,
                 bairro,
                 cgc,
                 trim(ender)||','||trim(cast(numero as text)) as ender,
                 upper(munic) as munic,
                 uf,
                 telef,
                 email,
                 url,
                 logo,
                 db12_extenso
          from db_config
                 inner join db_uf on db12_uf = uf
          where codigo = ".db_getsession("DB_instit");
    $result = db_query($sql);

    global $nomeinst;
    global $ender;
    global $munic;
    global $cgc;
    global $bairro;
    global $uf;
    global $db12_extenso;
    global $logo;

    //echo $sql;
    db_fieldsmemory($result,0);
    $db12_extenso = pg_result($result,0,"db12_extenso");
    /// seta a margem esquerda que veio do relatorio
    $S = $pdf->lMargin;
    $pdf->SetLeftMargin(10);
    $Letra = 'Times';

    $posini = ($pdf->w/6)-15;

    //$pdf->Image("imagens/files/logo_boleto.png",$posini,8,20);
    $pdf->Image('imagens/files/'.$logo,$posini,8,20);
    $pdf->Ln(1);
    $pdf->SetFont($Letra,'',10);
    $pdf->MultiCell(0,4,$db12_extenso,0,"C",0);
    $pdf->SetFont($Letra,'B',13);
    $pdf->MultiCell(0,6,$nomeinst,0,"C",0);
    $pdf->SetFont($Letra,'B',12);
    $pdf->MultiCell(0,4,@$GLOBALS["head1"],0,"C",0);
    $pdf->Ln(10);
    $pdf->SetLeftMargin($S);
  });

  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',9);

  $clcalend = new cl_calend();

  $sSqlCalendario = $clcalend->sql_query("","*","k13_data"," extract(year from k13_data)::integer = {$oGet->anousu}");
  $rsCalendario   = $clcalend->sql_record($sSqlCalendario);

  if ($clcalend->numrows == 0) {
    throw new Exception("Exercício sem Calendário Gerado.");
  }

  $trocalinha = false;
  $pdf->SetFont('Times','B',12);
  $pdf->multicell(0,10,"CALENDÁRIO OFICIAL DE ".$oGet->anousu,0,"C",0);
  $pdf->setY(100);
  $pdf->SetFont('Arial','B',9);

  for ($iMes = 1; $iMes < 13; $iMes++) {

    $iUltimoDiaMes = date('t', mktime(0,0,0,$iMes,1, $oGet->anousu));

    $linha = 0;
    $coluna = 6;
    $tamanho = 8;
    $xlinha = 6;

    if ($iMes<5) {
      $poscol = 5;
      $pdf->setX($poscol);
    } else if ($iMes<9) {
      $poscol = 75;
      if($trocalinha==false){
        $trocalinha = true;
        $pdf->setY(100);
      }
      $pdf->setX($poscol);
    } else {
      $poscol = 145;

      if($iMes == 9){
        $trocalinha=false;
      }

      if($trocalinha==false){
        $trocalinha = true;
        $pdf->setY(100);
      }
      $pdf->setX($poscol);
    }

    $pdf->Cell(56,4,mb_strtoupper(DBDate::getMesExtenso($iMes)),"LRBT",1,"C",0); // escreve a celula
    $pdf->setX($poscol);
    $dia = 1;

    $matriz_dia = array("Dom" ,"Seg","Ter" ,"Qua","Qui","Sex","Sab");

    for($y=0;$y<=$xlinha;$y++) {

      for($i=0;$i<sizeof($matriz_dia)-1;$i++) {
        $pdf->setfillcolor(170,170,255);

        if ($y==0) {
          $pdf->setfillcolor(153,169,174);
        } else if ($i==0){
          $pdf->setfillcolor(193,168,174);
        }

        if ($iMes<10){
          $iMesm = "0".$iMes;
        } else {
          $iMesm = $iMes;
        }

        if ($matriz_dia[$i]<10) {
          $diam = "0".$matriz_dia[$i];
        } else {
          $diam = $matriz_dia[$i];
        }

        if ($y != 0 && !empty($matriz_dia[$i])) {

          $result = $clcalend->sql_record($clcalend->sql_query($oGet->anousu."-".$iMesm."-".$diam));
          if($clcalend->numrows != 0){
            $pdf->setfillcolor(249,107,87);
          }
        }

        if ($matriz_dia[0]!="" || $matriz_dia[6]!="") {
          $pdf->Cell($tamanho,4,$matriz_dia[$i],"LRTB",0,"C",1);
        } else{
          $pdf->setfillcolor(170,170,255);
          $pdf->Cell($tamanho,4,$matriz_dia[$i],"LRTB",0,"C",1);
        }
        $pdf->setfillcolor(255,255,255);
      }

      $pdf->setfillcolor(170,170,255);

      if ($y==0) {
        $pdf->setfillcolor(153,169,174);
      } else {
        $pdf->setfillcolor(193,168,174);
      }

      if ($iMes<10) {
        $iMesm = "0".$iMes;
      } else {
        $iMesm = $iMes;
      }

      if ($matriz_dia[6]<10) {
        $diam = "0".$matriz_dia[6];
      } else {
        $diam = $matriz_dia[6];
      }

      if ($y != 0 && !empty($matriz_dia[$i])) {

        $result = $clcalend->sql_record($clcalend->sql_query($oGet->anousu."-".$iMesm."-".$diam));
        if ($clcalend->numrows != 0){
          $pdf->setfillcolor(249,107,87);
        }
      }

      if ($matriz_dia[0]!="" || $matriz_dia[6]!=""){
        $pdf->Cell($tamanho,4,$matriz_dia[6],"LRTB",1,"C",1);
      } else {
        $pdf->setfillcolor(170,170,255);
        $pdf->Cell($tamanho,4,$matriz_dia[6],"LRTB",1,"C",1);
      }

      $pdf->setfillcolor(255,255,255);
      $pdf->setX($poscol);

      if ($y==0) {
        $diames = date('w',mktime(0,0,0,$iMes,1, $oGet->anousu));

        for($m=0;$m<7;$m++){

          if ($m>=$diames) {
           $matriz_dia[$m] = $dia++;
          } else {
            $matriz_dia[$m] = "" ;
          }
        }
      } else {
        for ($m=0;$m<7;$m++) {
          if ($dia<=$iUltimoDiaMes) {
            $matriz_dia[$m] = $dia++;
          } else {
            $matriz_dia[$m] = "";
          }
        }
      }
    }

    $pdf->Cell(44,6,"" ,"",1,"C",0);
    $pdf->setX($poscol);
  }

  $pdf->showPDF("calendario_" . $oGet->anousu);

} catch (Exception $e) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$e->getMessage()}");
}