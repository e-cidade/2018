<?
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_tipoproc_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_procandam_classe.php");
include("classes/db_db_usuarios_classe.php");

//estancia as classes
$cl_processos   = new cl_protprocesso;
$cl_tipoproc    = new cl_tipoproc;
$cl_usuarios    = new cl_db_usuarios;
//label dos campos
$cl_processos->rotulo->label();
$cl_tipoproc->rotulo->label();
$cl_usuarios->rotulo->label();


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

//filtro das listas
$db_where = "";
if ($_GET["listacgm"] != "") {
 if ($_GET["Condicao1"] == "com") {
   $db_where .= " p58_numcgm IN ( $_GET[listacgm])";
  } else {
   $db_where .= " p58_numcgm NOT IN ( $_GET[listacgm])";
 }
}
//
if ($_GET["listadept"] != "") {
 if ( $_GET["listacgm"] != "") {
   $db_where .= " AND";
 }
  if ($_GET["Condicao2"] == "com") {
   $db_where .= " p58_coddepto IN ( $_GET[listadept])";
  } else {
   $db_where .= " p58_coddepto NOT IN ( $_GET[listadept])";
 }
}
//
if ($_GET["listatipo"] != "") {
 if (($_GET["listadept"] != "") || ($_GET["listacgm"] != "")) {
   $db_where .= " AND";
 }
  if ($_GET["Condicao3"] == "com") {
   $db_where .= " p51_codigo IN ( $_GET[listatipo])";
  } else {
   $db_where .= " p51_codigo NOT IN ( $_GET[listatipo])";
 }
}
//
if ($_GET["listaprocand"] != "") {
 if (($_GET["listatipo"] != "") || ($_GET["listacgm"] != "") || ($_GET["listadept"] != "")) {
   $db_where .= " AND";
 }
  if ($_GET["Condicao3"] == "com") {
   $db_where .= " p61_coddepto IN ( $_GET[listaprocand])";
  } else {
   $db_where .= " p61_coddepto NOT IN ( $_GET[listaprocand])";
 }
}

if (($_GET["listacgm"]     != "") ||
    ($_GET["listatipo"]    != "") ||
    ($_GET["listaprocand"] != "") ||
    ($_GET["listadept"] != "")) {
  $db_where .= " AND";
}
$sSqlUsuInsti  = "select id_instit from db_userinst where id_usuario = ".db_getsession("DB_id_usuario");
$rsUsuInsti    = db_query($sSqlUsuInsti);
$strWhereinsti = '';
$strV          = '';

if (pg_num_rows($rsUsuInsti) > 0) {

   for ($i = 0; $i < pg_num_rows($rsUsuInsti); $i++) {

     $strWhereinsti .= $strV.pg_result($rsUsuInsti, $i, 0);
     $strV           = ", ";
   }
}

if ($strWhereinsti != null) {

  $strWhereinsti = "  and p58_instit in ($strWhereinsti)";

}
//filtro das data
/*
if( ($_GET["data1"] != "//") and ($_GET["data1"] == $_GET["data2"]) ) {
  $db_where .= " p58_dtproc = '$_GET[data1]'";
	$head5 = "PERIODO: " . $_GET["data1"];
}
*/

/**
 * Organiza as datas para apresentá-las ao usuário
 */
list($iAno1,$iMes1,$iDia1) = explode ('/', $_GET['data1']);
list($iAno2,$iMes2,$iDia2) = explode ('/', $_GET['data2']);
$dData1 = "{$iDia1}/{$iMes1}/{$iAno1}";
$dData2 = "{$iDia2}/{$iMes2}/{$iAno2}";

if( ($_GET["data1"] != "//") and ($_GET["data2"] != "//") ) {
  $db_where .= " p58_dtproc BETWEEN '$_GET[data1]' AND '$_GET[data2]'";
	$head5     = "PERIODO: de $dData1 à $dData2";
}
elseif( ($_GET["data1"] != "//") and ($_GET["data2"] == "//") ) {
  $db_where .= " p58_dtproc = '$_GET[data1]'";
	$head5     = "PERIODO: $dData1";
}
elseif( ($_GET["data1"] == "//") and ($_GET["data2"] != "//") ) {
  $db_where .= " p58_dtproc = '$_GET[data2]'";
	$head5     = "PERIODO: $dData2";
}

if (isset($tipo)) {

	if ($db_where == "") {
		$db_where = " 1 = 1";
	}

  if ($tipo == "n") {

		$db_where .= " and p68_codproc is null ";
    $head4     = "SOMENTE OS EM ANDAMENTO";
	} elseif ($tipo == "a") {

		$db_where .= " and p68_codproc is not null ";
    $head4     = "SOMENTE OS ARQUIVADOS";
	} else {
    $head4     = "TODOS (ARQUIVADOS E EM ANDAMENTO)";
	}

}

$ordem = $Ordem;

$ordenacao = " p58_codproc";

if (isset($ordem)) {
  if ($ordem == "1") {
		$ordenacao = " p58_codproc";
	} elseif ($ordem == "2") {
		$ordenacao = " p51_descr";
	} elseif ($ordem == "3") {
		$ordenacao = " p58_dtproc";
	} elseif ($ordem == "4") {
		$ordenacao = " login";
	} elseif ($ordem == "5") {
		$ordenacao = " p58_requer";
	} elseif ($ordem == "6") {
		$ordenacao = " a.descrdepto";
	} elseif ($ordem == "7") {
		$ordenacao = " b.descrdepto";
	}
}
$db_where .= $strWhereinsti;

/**
 * Verifica o campo que deverá ser buscado no banco
 * $sTituloCampo = titulo do campo a ser impresso no PDF
 */
if ($_GET["tipoCGMProcesso"] == 1) {
	// Requerente
	$sCampoMostrar = "p58_requer";
	$sTituloCampo  = "Requerente";
} else if ($_GET["tipoCGMProcesso"] == 2) {
	// Titular
	$sCampoMostrar = "z01_nome";
	$sTituloCampo  = "Titular";
}



$head3 = "RELATÓRIO DE PROCESSOS ";
//die($cl_processos->sql_query_andam("","p58_codproc,p58_codigo,p51_descr,to_char(p58_dtproc,'dd/mm/yyyy'),login,p58_numcgm,p58_requer,p58_coddepto,p58_codandam,p58_hora,a.descrdepto as deptoproc,p61_coddepto,b.descrdepto as deptoandam",$ordenacao,$db_where));

$sSqlProcessos = "p58_codproc,
                  p58_numero,
                  p58_ano,
                  p58_codigo,
                  p51_descr,
                  to_char(p58_dtproc,'dd/mm/yyyy'),
                  login,
		  p58_obs,
                  p58_numcgm,
                  {$sCampoMostrar} AS titular,
                  p58_coddepto,
                  p58_codandam,
                  p58_hora,
                  a.descrdepto as deptoproc,
                  p61_coddepto,
                  b.descrdepto as deptoandam";

if ($tipo == 't') {
  $sProcessaSql = $cl_processos->sql_query_todos("", $sSqlProcessos, $ordenacao, $db_where);
} else {
  $sProcessaSql = $cl_processos->sql_query_deptarq("", $sSqlProcessos, $ordenacao, $db_where);
}
$result       = $cl_processos->sql_record($sProcessaSql);

/**
 * Caso não seja localizada nenhum registro, direciona para a página abaixo
 */
if ($cl_processos->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não há registros.');
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca  = 1;
$alt    = 4;
$p      = 0;

for($x = 0; $x < $cl_processos->numrows; $x++) {

  db_fieldsmemory($result,$x);
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {

    $pdf->addpage("L");
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(21, $alt, "Processo", 1, 0, "C", 1);
    $pdf->cell(14, $alt, "Data", 1, 0, "C", 1);
    $pdf->cell(10, $alt, "Hora", 1, 0, "C", 1);
    $pdf->cell(8, $alt, $RLp58_codigo, 1, 0, "C", 1);
    $pdf->cell(50, $alt, $RLp51_descr, 1, 0, "C", 1);
    $pdf->cell(15, $alt, "Login", 1, 0, "C", 1);
    $pdf->cell(10, $alt, "CGM", 1, 0, "C", 1);
    $pdf->cell(65, $alt, $sTituloCampo, 1, 0,"C", 1);
    $pdf->cell(43, $alt, "Dept. Ini.", 1, 0, "C", 1);
    $pdf->cell(43, $alt, "Dept. Atual", 1, 1, "C", 1);

    $troca = 0;
  }
  $pdf->setfont('arial', '', 7);

  $sNumeroProcesso = $p58_numero."/".$p58_ano;
  if (empty($p58_numero)) {
    $sNumeroProcesso = "";
  }

  $pdf->cell(21, $alt, $sNumeroProcesso, 0, 0, "C", $p);
  $pdf->cell(14, $alt, $to_char, 0, 0, "C", $p);
  $pdf->cell(10, $alt, $p58_hora, 0, 0, "C", $p);
  $pdf->cell(8, $alt, $p58_codigo, 0, 0, "L", $p);
  $pdf->cell(50, $alt, substr($p51_descr, 0, 28), 0, 0, "L", $p);
  $pdf->cell(15, $alt, substr($login, 0, 10), 0, 0, "L", $p);
  //$pdf->cell(10, $alt, $p58_numcgm, 0, 0, "C", $p);
  $pdf->cell(10, $alt, $p58_numcgm, 0, 0, "C", $p);
  $pdf->cell(65, $alt, substr($titular, 0, 40), 0, 0, "L", $p);
  $pdf->cell(43, $alt, substr($p58_coddepto . "-" . substr($deptoproc, 0, 25), 0, 25), 0, 0, "L", $p);
  $pdf->cell(43, $alt, substr($p61_coddepto . "-" . substr($deptoandam, 0, 25), 0, 25), 0, 1, "l", $p);

  if ($Observacao == '1') {
    $pdf->multicell(279,$alt,$p58_obs,0,"L",$p);
  }


  if ($p == 0) {
    $p = 1;
  } else {
    $p = 0;
  }
}
$pdf->ln();
$pdf->cell(43, $alt, "TOTAL DE REGISTROS: " . $cl_processos->numrows, 0, 1, "L", 0);
$pdf->Output();
?>