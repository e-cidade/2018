<?php
/**
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("fpdf151/PDFDocument.php");
require_once("fpdf151/fpdf.php");
require_once("fpdf151/PDFTable.php");

$oGet    = db_utils::postMemory($_GET);
$iAnousu = $oGet->anousu;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPdfTable = new PDFTable(PDFDocument::PRINT_LANDSCAPE);
$aCabecalho     = array( "Código", "Desrição", "Código da Receita", "Descrição da Receita", "Valor" );
$aLarguraColuna = array( 5, 35, 15, 30, 15 );
$aLinhamento    = array( PDFDocument::ALIGN_CENTER, PDFDocument::ALIGN_LEFT, PDFDocument::ALIGN_CENTER,
                         PDFDocument::ALIGN_LEFT, PDFDocument::ALIGN_CENTER );

$oPdfTable->setTotalByPage(true);
$oPdfTable->setPercentWidth(true);
$oPdfTable->setHeaders($aCabecalho);
$oPdfTable->setColumnsWidth($aLarguraColuna);
$oPdfTable->setColumnsAlign($aLinhamento);

$oPdfTable->addHeaderDescription("Relatório de Valor de Taxas por Zona Fiscal");
$oPdfTable->addHeaderDescription("");
$oPdfTable->addHeaderDescription("Exercício: " . $iAnousu);

$oDaoZonasTaxa = new cl_zonastaxa;
$sSql          = $oDaoZonasTaxa->sql_query(null, null, $iAnousu, "*", "j57_zona");
$rsZonasTaxa   = $oDaoZonasTaxa->sql_record( $sSql );
$iTotal        = pg_num_rows($rsZonasTaxa);

if( $rsZonasTaxa ){

  for ($iRow = 0; $iRow < $iTotal; $iRow++) {

    $oZonasTaxas = db_utils::fieldsMemory($rsZonasTaxa, $iRow);

    $oPdfTable->addLineInformation(
      array(
        $oZonasTaxas->j50_zona,
        $oZonasTaxas->j50_descr,
        $oZonasTaxas->j57_receit,
        $oZonasTaxas->k02_descr,
        trim(db_formatar($oZonasTaxas->j57_valor,'f'))
      )
    );
  }
}

$oPdfTable->printOut();