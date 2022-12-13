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

$oPdfTable      = new PDFTable(PDFDocument::PRINT_LANDSCAPE);
$aCabecalho     = array( "Código", "Descrição", " Valor do Terreno (m²)" );
$aLarguraColuna = array( 10, 43, 20 );
$aLinhamento    = array( PDFDocument::ALIGN_CENTER, PDFDocument::ALIGN_LEFT, PDFDocument::ALIGN_RIGHT );

$oPdfTable->setTotalByPage(true);
$oPdfTable->setPercentWidth(true);
$oPdfTable->setHeaders($aCabecalho);
$oPdfTable->setColumnsWidth($aLarguraColuna);
$oPdfTable->setColumnsAlign($aLinhamento);

$oPdfTable->addHeaderDescription("Relatório de Zonas Fiscais");
$oPdfTable->addHeaderDescription("");
$oPdfTable->addHeaderDescription("Exercício: " . $iAnousu);

$oPdfTable->addFormatting(7, PDFTable::FORMAT_DATE);

$oDaoZonasValor = new cl_zonasvalor;
$sSql           = $oDaoZonasValor->sql_query(null, $iAnousu, "*", "j50_zona");
$rsZonasValor   = $oDaoZonasValor->sql_record( $sSql );
$iTotal         = pg_num_rows($rsZonasValor);

if( $rsZonasValor ){

  for ($iRow = 0; $iRow < $iTotal; $iRow++) {

    $oZonasValor = db_utils::fieldsMemory($rsZonasValor, $iRow);

    $oPdfTable->addLineInformation(
      array(
        $oZonasValor->j50_zona,
        $oZonasValor->j50_descr,
        trim(db_formatar($oZonasValor->j51_valorm2t,'f'))
      )
    );
  }
}

$oPdfTable->printOut();