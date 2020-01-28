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

require_once("fpdf151/pdf.php");

$cliptuconstr  = new cl_iptuconstr;
$cliptuconstr1 = new cl_iptuconstr;
$cliptubase    = new cl_iptubase;
$oDaoCaracter  = new cl_caracter();

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

///////////////////////////////////////////////////////////////////////
$head4 = "RELATÓRIO DE FACE DE QUADRA";
$pdf   = new PDF('L'); // abre a classe

$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas

// adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);

$codigo   = "";
$where    = "";
$comcar   = "";
$and      = "";
$listadas = "";

if( isset( $relatorio1 ) ) {

  if (isset($chaves) && $chaves != "") {

    $chaves = split( "#", $chaves );

    for( $i = 0; $i < sizeof($chaves); $i++ ) {

      if ($codigo == "") {
	      $codigo .= substr( $chaves[$i], 0, ( strpos( $chaves[$i], "-" ) ) );
      } else {
	      $codigo .= "," . substr( $chaves[$i], 0, ( strpos( $chaves[$i], "-" ) ) );
      }
    }

    $comcar   = " j38_caract in ($codigo)";
    $listadas = $codigo;
    $and      = " and ";
  }
}

$semcar = "";
if( $chaves_caract != "" ) {

  $semcar = " {$and} j38_caract not in ({$chaves_caract})";
  $and    = " and ";
}

if( isset( $ordem ) && $ordem != "" ) {

  if ($ordem == "codigo") {

    $ordem  = " order by j37_codigo";
    $ordem1 = " RUA";
  } else if( $ordem == "setor" ) {

    $ordem  = " order by j37_setor";
    $ordem1 = " SETOR";
  } else if( $ordem == "quadra" ) {

    $ordem  = " order by j37_quadra";
    $ordem1 = " QUADRA";
  }
}

$modo = "";
if (isset($order) && $order != "") {

  if ($order == "asc") {

    $modo  = " asc ";
    $modo1 = " ASCENDENTE ";
  } elseif($order == "desc") {

    $modo  = " desc ";
    $modo1 = " DESCENDENTE ";
  }
}

if (isset($setor) && $setor != "") {

  $setores = $setor;
  if ($setor == "") {

    $setor  = "";
    $quadra = "";
  } else {

    $setor1  = $setor;
    $quadra1 = $quadra;

    if (isset($setor) && $setor != "") {

      $chaves  = split( ",", $setor );
      $chaves1 = split( ",", $quadra );
      $or      = "";
      $setor   = "";

      for ($i = 0; $i < sizeof($chaves); $i++) {

	      $setor .= $or." (j37_setor = '".$chaves[$i]."' and j37_quadra = '".$chaves1[$i]."')";
	      $or     = " or ";
      }
    }
  }

  $setor = " {$and} (" . $setor . ")";
  $and   = " and ";
}

$sql  = "select distinct face.*, j14_nome                       ";
$sql .= "  from face                                            ";
$sql .= "       left join carface  on j38_face   = j37_face    ";
$sql .= "       left join caracter on j31_codigo = j38_caract  ";

$testruas = "";
if ($ruas != "") {

  if ($comcar == "" && $semcar == "") {
    $and = "";
  } else {
    $and = " and ";
  }

  if ($temruas == "t") {
    $testruas = $and . " j14_codigo in ({$ruas})";
  } else {
    $testruas = $and." j14_codigo not in ($ruas)";
  }
}

$sql   .= " inner join ruas on j37_codigo = j14_codigo ";
$where  = " {$comcar} {$semcar} {$testruas} {$setor}";
$where  = (trim($where) != "" ? " where " . $where : "");
$sql   .= $where;

if (isset($j32_grupo) && $j32_grupo != "") {

  $sqlBkup = $sql;

  $sql  = " select c.*, caracter.j31_descr ";
  $sql .= "   from ({$sqlBkup}) as c ";
  $sql .= "        left join carface  on carface.j38_face    = c.j37_face ";
  $sql .= "        left join caracter on caracter.j31_codigo = carface.j38_caract ";
  $sql .= " where caracter.j31_grupo = {$j32_grupo} ";
}

$pontuacao = "";
if(isset($pontini) && $pontini != "") {

  if (isset($pontfim) && $pontfim != "") {

    $pontuacao  = " where j31_pontos >= {$pontini} and j31_pontos <= {$pontfim}";
    $pontuacao1 = "PONTUAÇÃO MAIOR OU IGUAL À {$pontini} E MENOR OU IGUAL À {$pontfim}";
  } else {

    $pontuacao  = " where j31_pontos >= {$pontini}";
    $pontuacao1 = "PONTUAÇÃO MAIOR OU IGUAL À {$pontini}";
  }
} else if(isset($pontfim) && $pontfim != "") {

  $pontuacao  = " where j31_pontos <= {$pontfim}";
  $pontuacao1 = " PONTUAÇÃO MENOR OU IGUAL À {$pontfim}";
}

$sSql  = "select distinct * ";
$sSql .= "  from (select * ";
$sSql .= "          from (select * ";
$sSql .= "                  from ({$sql}) as tudo ";
$sSql .= "                       inner join (select j37_face, ";
$sSql .= "                                          sum(coalesce(j31_pontos, 0)) as j31_pontos ";
$sSql .= "                                     from  face ";
$sSql .= "                                          left join   carface   on j37_face          = j38_face ";
$sSql .= "                                          left join caracter on j38_caract        = j31_codigo ";
$sSql .= "                                    group by j37_face) as pontos ";
$sSql .= "                               on tudo.j37_face = pontos.j37_face {$pontuacao} ";
$sSql .= "               ) as ordem ";
$sSql .= "       ) as distincao {$ordem} {$order}";

$result    = db_query($sSql);
$numrows   = pg_numrows($result);
$matric    = "";
$idcons    = "";
$area      = "";
$areama    = "";
$areame    = "99999";
$matricula = 0;
$tam       = 4;

$iLarguaColunaGrupo = 50;
$iLarguraLinhaRua   = 115;

if (isset($j32_descr) && $j32_descr == "") {
  $iLarguraLinhaRua += $iLarguaColunaGrupo;
}

if ($numrows > 0) {

  $pdf->AddPage();
  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(235);
  $pdf->Cell(15,  $tam, "CÓDIGO"     ,1 ,0 , "C" ,1);
  $pdf->Cell(20,  $tam, "SETOR"      ,1 ,0 , "C" ,1);
  $pdf->Cell(22,  $tam, "QUADRA"     ,1 ,0 , "C" ,1);
  $pdf->Cell(20,  $tam, "COD.LOG"    ,1 ,0 , "C" ,1);
  $pdf->Cell($iLarguraLinhaRua, $tam, "LOGRADOURO" ,1 ,0 , "C" ,1);
  $pdf->Cell(15,  $tam, "LADO"       ,1 ,0 , "C" ,1);
  $pdf->Cell(20,  $tam, "PONTUAÇÃO"  ,1 ,0 , "C" ,1);

  if (isset($j32_descr) && $j32_descr != "") {
    $pdf->Cell($iLarguaColunaGrupo, $tam, "GRUPO", 1, 1, "C", 1);
  } else {
    $pdf->ln();
  }

  $pdf->SetFont('Arial','',8);
  for ($s = 0; $s < $numrows; $s++) {

    db_fieldsmemory($result,$s);

    $pdf->Cell(15, $tam, $j37_face   ,1 ,0 ,"C" ,0);
    $pdf->Cell(20, $tam, $j37_setor  ,1 ,0 ,"C" ,0);
    $pdf->Cell(22, $tam, $j37_quadra ,1 ,0 ,"C" ,0);
    $pdf->Cell(20, $tam, $j37_codigo ,1 ,0 ,"C" ,0);
    $pdf->Cell($iLarguraLinhaRua, $tam, $j14_nome   ,1 ,0 ,"L" ,0);
    $pdf->Cell(15, $tam, $j37_lado   ,1 ,0 ,"C" ,0);
    $pdf->Cell(20, $tam, $j31_pontos, 1, 0, "C", 0);

    if (isset($j32_descr) && $j32_descr != "") {
      $pdf->Cell($iLarguaColunaGrupo, $tam, $j32_descr,  1, 1, "L", 0);
    } else {
      $pdf->ln();
    }

    if ( $pdf->GetY() > 185 && ($s + 1) != $numrows) {

      $pdf->SetFont('Arial','B',8);
      $pdf->AddPage();
      $pdf->Cell(15, $tam, "CÓDIGO"     ,1 ,0 , "C" ,1);
      $pdf->Cell(20, $tam, "SETOR"      ,1 ,0 , "C" ,1);
      $pdf->Cell(22, $tam, "QUADRA"     ,1 ,0 , "C" ,1);
      $pdf->Cell(20, $tam, "COD.LOG"    ,1 ,0 , "C" ,1);
      $pdf->Cell($iLarguraLinhaRua, $tam, "LOGRADOURO" ,1 ,0 , "C" ,1);
      $pdf->Cell(15, $tam, "LADO"       ,1 ,0 , "C" ,1);
      $pdf->Cell(20,  $tam, "PONTUAÇÃO"  ,1 ,0 , "C" ,1);

      if (isset($j32_descr) && $j32_descr != "") {
        $pdf->Cell($iLarguaColunaGrupo, $tam, "GRUPO", 1, 1, "C", 1);
      } else {
        $pdf->ln();
      }

      $pdf->SetFont('Arial','',8);
    }
  }
}

/////////////// propriedades /////////////////////
$iAlturaPropriedades = 5;
$pdf->AddPage(); // adiciona uma pagina

$iLarguraLinha = 280;
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(235);
$pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "Propriedades do Relatório", 1, "C", 1 );

$pdf->Ln(5);
$pdf->SetFillColor(255);
$pdf->SetFont('Arial','',7);
$cod = "";

if (isset($ruas) && !empty($ruas) && $temruas == "t") {

  $vir     = "";
  $rua     = "";
  $result1 = db_query("select j14_nome from ruas where j14_codigo in ($ruas)");

  for ($x = 0; $x < pg_numrows($result1); $x++) {

	  db_fieldsmemory($result1,$x);
	  $cod .= $vir . $j14_nome;
	  $vir  = ", ";
  }

  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "SOMENTE A(S) RUA(S) ->  " . $cod, 1, "L" );
}

if (isset($ruas) && $ruas != "" && $temruas == "f") {

  $vir     = "";
  $rua     = "";
  $result1 = db_query("select j14_nome from ruas where j14_codigo in ($ruas)");

  for ($x = 0; $x < pg_numrows($result1); $x++) {

	  db_fieldsmemory($result1,$x);
	  $cod .= $vir . $j14_nome;
	  $vir  = ", ";
  }

  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "EXCETO A(S) RUA(S) ->  " . $cod, 1, "L" );
}

if (isset($ruas) && $ruas == "") {
  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "LOGRADOUROS SELECIONADOS: TODOS", 1, "L" );
}

$vir = "";
$cod = "";

if ($listadas != "" || $chaves_caract != '') {

  $sWhere       = $listadas != '' ? "j31_codigo in ({$listadas})" : "j31_codigo not in ({$chaves_caract})";
  $sWhere      .= " and j32_tipo = 'F'";
  $sSqlCaracter = $oDaoCaracter->sql_query( null, "j31_codigo, j31_descr", null, $sWhere );
  $rsCaracter   = db_query( $sSqlCaracter );

  if( pg_numrows( $rsCaracter ) > 0 ) {

    for($x = 0; $x < pg_numrows($rsCaracter); $x++) {

      db_fieldsmemory( $rsCaracter, $x );
      $cod .= $vir . $j31_codigo . " - " . $j31_descr;
      $vir  =", ";
    }

    $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "CARACTERÍSTICAS LISTADAS ->  " . $cod, 1, "L" );
  }

} else {
  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "CARACTERÍSTICAS LISTADAS -> TODAS ", 1, "L" );
}

$vir = "";
$cod = "";

if (isset($chaves_caract) && $chaves_caract != "") {

  $sSqlCaracter = $oDaoCaracter->sql_query( null, "j31_codigo, j31_descr", null, "j31_codigo in ({$chaves_caract})" );
  $rsCaracter   = db_query( $sSqlCaracter );

  for($x = 0; $x < pg_numrows($rsCaracter); $x++) {

	  db_fieldsmemory( $rsCaracter, $x );
	  $cod .= $vir . $j31_codigo . " - " . $j31_descr;
	  $vir  = ", ";
  }
} else {
  $cod = "";
}

$pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "CARACTERÍSTICAS NÃO LISTADAS ->  " . @$cod, 1, "L" );

if (isset($setores) && $setores != "") {

  if (isset($setor) && $setor != "") {

    $chaves  = split( ",", $setores );
    $chaves1 = split( ",", $quadra );
    $and     = "";
    $setores = "";

    for( $i = 0; $i < sizeof($chaves); $i++ ) {

      $setores .= $and . $chaves[$i] . "/" . $chaves1[$i];
      $and      = " - ";
    }
  }

  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "SOMENTE OS SETORES/QUADRAS -> " . $setores, 1, "L" );
}

if (isset($j32_grupo) && $j32_grupo != "") {
  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "TOTAIS DOS LOTES COM CARACTERÍSTICA DO GRUPO - $j32_descr ",1,"L");
}
if (isset($loteini) && $loteini != "" && $lotefim == "") {
  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, $arealote1, 1, "L" );
} else if( isset( $loteini ) && $loteini != "" && isset( $lotefim ) && $lotefim != "" ) {
  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, $arealote1, 1, "L" );
}

if (isset( $areacons1 ) && $areacons1 != "" ) {
  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, $areacons1, 1, "L" );
}

if (isset($testada1) && $testada1 != "") {
  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, $testada1, 1, "L" );
}

if (isset($pontuacao1) && $pontuacao1 != "") {
  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "" . $pontuacao1, 1, "L" );
}

$pdf->Ln(5);
if (isset($ordem) && $ordem != "") {
  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "ORDEM - ORDENADO POR " . $ordem1, 0, "L" );
}

if (isset($order) && $order != "") {
  $pdf->MultiCell( $iLarguraLinha, $iAlturaPropriedades, "MODO -" . $modo1, 0, "L" );
}

$pdf->ln(5);
$pdf->SetFillColor(255);
$pdf->output();