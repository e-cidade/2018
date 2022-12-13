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

require_once(modification("fpdf151/pdf.php"));

$cliptubase = db_utils::getDao("iptubase");
$cllote = db_utils::getDao("lote");

$clrotulo = new rotulocampo;
$clrotulo->label("j01_matric");
$clrotulo->label("j01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("j40_refant");
$clrotulo->label("j01_baixa");
$clrotulo->label("j23_vlrter");

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$head1 = "RELATÓRIO DE MATRÍCULAS";

$head2 = "TIPO IMÓVEL: ";
if ($terreno == "T") {
  $head2 .= "TODOS (PREDIAL/TERRITORIAL)";
} elseif ($terreno == "B") {
  $head2 .= "TERRITORIAL";
} elseif ($terreno == "P") {
  $head2 .= "PREDIAL";
}

$head3 = "LISTAR: ";

if ($process == "T") {
  $head3 .= "TODOS";
} elseif ($process == "S") {
  $head3 .= "BAIXADOS";
} elseif ($process == "N") {
  $head3 .= "NÃO BAIXADOS";
}

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);

$total  = 0;
$where  = " 1=1 ";
$alt    = 4;
$lin    = 1;
$baix   = false;

if ($process == "S" || $process == "T") {
  $lin  = 1;
  $baix = true;
}

if (isset($relatorio1)) {

  $aSetorQuadraLote = array();

  $aSetor  = explode(',', $setorParametro);
  $aQuadra = explode(',', $quadraParametro);
  $aLote   = explode(',', $loteParametro);

  foreach ($aSetor as $iIndice => $iSetor) {
    $aSetorQuadraLote[$iIndice]['setor'] = $iSetor;
  }

  foreach ($aQuadra as $iIndice => $iQuadra) {

    $aSetorQuadraLote[$iIndice]['setor']  = $aSetor[$iIndice];
    $aSetorQuadraLote[$iIndice]['quadra'] = $iQuadra;
  }

  foreach ($aLote as $iIndice => $iLote) {

    $aSetorQuadraLote[$iIndice]['setor']  = $aSetor[$iIndice];
    $aSetorQuadraLote[$iIndice]['quadra'] = $aQuadra[$iIndice];
    $aSetorQuadraLote[$iIndice]['lote']   = $iLote;
  }

  $where = "";
  $sAnd  = "";
  if($setorParametro != ""){

    $sOr   = " ( ";

    foreach ($aSetorQuadraLote as $iIndice => $aSetorQuadraLote) {

      if ( isset($aSetorQuadraLote['setor']) && $aSetorQuadraLote['setor'] != "" ) {
        $where .= "$sOr ( j34_setor  = '{$aSetorQuadraLote['setor']}'  ";
      }

      if ( isset($aSetorQuadraLote['quadra']) && $aSetorQuadraLote['quadra'] != "" ) {
        $where .= "and  j34_quadra = '{$aSetorQuadraLote['quadra']}' ";
      }

      if ( isset($aSetorQuadraLote['lote']) && $aSetorQuadraLote['lote'] != "" ) {
        $where .= "and  j34_lote   = '{$aSetorQuadraLote['lote']}' ";
      }

      $where .= " )";
      $sOr    = " or ";
    }

    if ( !empty($where) ) {
      $where .= " ) ";
      $sAnd   = " and ";
    }
  }

  if (isset($process) && $process == "S") {
    $where .= $sAnd . " j01_baixa is not null ";
  } else if(isset($process) && $process == "N") {
    $where .= $sAnd . " j01_baixa is null ";
  }
}

if ($terreno == 'B') {
  $where .= $sAnd . " j39_matric is null";
} elseif ($terreno == 'P'){
  $where .= $sAnd . " j39_matric is not null";
}

$sCampos  = "distinct iptubase.j01_matric,                                                                                      \n";
$sCampos .= "         iptubase.j01_numcgm,                                                                                      \n";
$sCampos .= "         cgm.z01_nome,                                                                                             \n";
$sCampos .= "         lote.j34_setor,                                                                                           \n";
$sCampos .= "         lote.j34_quadra,                                                                                          \n";
$sCampos .= "         lote.j34_lote,                                                                                            \n";
$sCampos .= "         iptubase.j01_baixa,                                                                                       \n";
$sCampos .= "         ( select coalesce(j23_vlrter,0) + coalesce( ( select sum(j22_valor)                                       \n";
$sCampos .= "                                                         from iptucale                                             \n";
$sCampos .= "                                                        where j22_matric = j01_matric                              \n";
$sCampos .= "                                                          and j22_anousu = " . db_getsession("DB_anousu") . ") ,0) \n";
$sCampos .= "             from iptucalc                                                                                         \n";
$sCampos .= "            where j23_matric = j01_matric                                                                          \n";
$sCampos .= "              and j23_anousu = " . db_getsession("DB_anousu") . " ) as j23_vlrter,                                 \n";
$sCampos .= "         iptuant.j40_refant                                                                                        \n";

$result_matric = $cliptubase->sql_record($cliptubase->sql_query_constr( null, $sCampos, "j34_setor, j34_quadra, j34_lote", $where));
$numrows_matric = $cliptubase->numrows;

if ($numrows_matric == 0) {
  db_redireciona( "db_erros.php?fechar=true&db_erro=Nenhum registro encontrado com os dados informados." );
}

$sMore = "";
$aSetores = array();

for ($i = 0; $i < $numrows_matric; $i++) {
  $aSetores[] = db_utils::fieldsMemory($result_matric, $i)->j34_setor;
}

$aSetores = array_unique($aSetores);

if (count($aSetores) > 47) {
  $aSetores = array_splice($aSetores, 0, 47);
  $sMore = "...";
}

$head4 = "SETORES: " . implode($aSetores, ", ") . $sMore;

$pdf->AddPage("L");
$linm = 1;
$lin  = 1;

$p = 0;
$total_vlrvenal = 0;

for ($i = 0; $i < $numrows_matric; $i++) {
  db_fieldsmemory($result_matric, $i);

  if ($pdf->gety() > $pdf->h - 30 || $i == 0) {

    if ($pdf->gety() > $pdf->h - 30) {
      $pdf->AddPage("L");
    }

    $p = 0;
    $pdf->SetFont('Arial', 'b', 8);
    $pdf->Cell(15, $alt, "Matrícula", 1,0, "C", 1);
    $pdf->Cell(15, $alt, $RLj01_numcgm, 1,0, "C", 1);
    $pdf->Cell(50, $alt, $RLz01_nome  , 1,0, "C", 1);
    $pdf->Cell(10, $alt, $RLj34_setor , 1,0, "C", 1);
    $pdf->Cell(11, $alt, $RLj34_quadra, 1,0, "C", 1);
    $pdf->Cell(10, $alt, $RLj34_lote  , 1,0, "C", 1);

    if (!isset($mostra) || $mostra != 's') {
      $pdf->Cell(25,$alt,$RLj40_refant,1,0,"C",1);
    }

    if  ($baix == true) {
      $pdf->Cell(15,$alt,$RLj01_baixa,1,0,"C",1);
    }

    $pdf->Cell(20,$alt,"Valor Venal",1,0,"C",1);

    if (isset($mostra) && $mostra == 's') {
      $pdf->Cell(50, $alt, "Rua", 1, 0, "C", 1);
      $pdf->Cell(20, $alt, "Número", 1, 0, "C", 1);
      $pdf->Cell(20, $alt, "Complemento", 1, 0, "C", 1);
      $pdf->Cell(40, $alt, "Bairro", 1, 0, "C", 1);
    }

    $pdf->ln();
  }

  $pdf->SetFont('Arial','',7);
  $pdf->Cell(15,$alt,$j01_matric,0,0,"C",$p);
  $pdf->Cell(15,$alt,$j01_numcgm,0,0,"C",$p);
  $pdf->Cell(50,$alt,substr($z01_nome,0,29),0,0,"L",$p);
  $pdf->Cell(10,$alt,$j34_setor ,0,0,"C",$p);
  $pdf->Cell(11,$alt,$j34_quadra,0,0,"C",$p);
  $pdf->Cell(10,$alt,$j34_lote  ,0,0,"C",$p);

  if (!isset($mostra) || $mostra != 's') {
    $pdf->Cell(25,$alt,@$j40_refant,0,0,"C",$p);
  }

  if ($baix == true) {
    $pdf->Cell(15,$alt,db_formatar(@$j01_baixa,"d"),0,0,"C",$p);
  }

  $pdf->Cell(20, $alt, db_formatar($j23_vlrter, 'f'),0,0,"C",$p);
  $total_vlrvenal+=$j23_vlrter;

  if (isset($mostra) && $mostra == 's') {
    $result_ender=db_query("select * from proprietario_ender where j01_matric = $j01_matric");

    if (pg_numrows($result_ender)>0){
      db_fieldsmemory($result_ender,0);
    }

    $pdf->Cell(50,$alt,@$j14_nome,0,0,"L",$p);
    $pdf->Cell(20,$alt,@$j39_numero ,0,0,"C",$p);
    $pdf->Cell(20,$alt,substr(@$j39_compl,0,10),0,0,"L",$p);
    $pdf->Cell(40,$alt,@$j13_descr,0,0,"L",$p);
  }

  $pdf->ln();

  if ($p == 0) {
    $p = 1;
  } else {
    $p = 0;
  }

  $total++;
}

$pdf->ln();
$pdf->SetFont('Arial','b',8);
$pdf->Cell(0, $alt, "TOTAL DE REGISTROS ENCONTRADOS:  " . $total,"TB",1,"L",0);
$pdf->Cell(0, $alt, "TOTAL VALOR VENAL: " . trim(db_formatar($total_vlrvenal, 'f')),"TB",1,"L",0);
$pdf->Cell(0, $alt, "MÉDIA VALOR VENAL: " . trim(db_formatar($total_vlrvenal/$total, 'f')),"TB",1,"L",0);
$pdf->ln(3);
$pdf->Cell(0,$alt,"NOTA: VALOR VENAL REFERENTE " . db_getsession("DB_anousu"),"",1,"L",0);
$pdf->ln(8);

header('Content-Disposition: attachment; filename="setor_quadra_lote_' . time() . '.pdf"');
$pdf->Output();
?>