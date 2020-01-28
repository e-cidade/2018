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

require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_app::import("contabilidade.relatorios.AnexoReceitaCorrenteLiquida");
db_app::import("relatorioContabil");

$oGet = db_utils::postMemory($_GET);

/**
 * Pega todas as instituições cadastradas em db_config
 */
$rsSqlInstit       = db_query("select codigo, nomeinst, nomeinstabrev from db_config");
$sInstit           = "";
$sVirgula          = "";
$sDescrInstitucoes = '';
for ($i = 0; $i < pg_num_rows($rsSqlInstit); $i++) {

  $oDadoInstit = db_utils::fieldsMemory($rsSqlInstit, $i);
  $sInstit           .= $sVirgula.$oDadoInstit->codigo;
  $sDescrInstitucoes .= $sVirgula.$oDadoInstit->nomeinstabrev;
  $sVirgula           = ", ";
}

$iAnoUso       = db_getsession('DB_anousu');
$oAnexoReceita = new AnexoReceitaCorrenteLiquida($iAnoUso, $oGet->iCodRel, $oGet->iPeriodo);
$oAnexoReceita->setInstituicoes($sInstit);
$oDadosAnexo   = $oAnexoReceita->getDados();
//echo ("<pre>".print_r($oDadosAnexo, 1)."</pre>");exit;

$head1 = db_stdClass::getDadosInstit(db_getsession("DB_instit"))->nomeinst;
$head2 = "DEMONSTRATIVO DA RECEITA CORRENTE LIQUIDA";
$head3 = "Lei Orçamentária Anual de ".db_getsession("DB_anousu");
$head4 = "Instituições: ".$sDescrInstitucoes;

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$iAltura        = 4;
$lPrimeiroLaco  = true;

foreach ($oDadosAnexo as $iIdLinha => $oDado) {

  if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {

    montaCabecalho($oPdf, $iAltura);
    $lPrimeiroLaco = false;
  }

  $sBordaDesc  = "R";
  $sBordaValor = "";
  if ($iIdLinha == 27) {

    $sBordaDesc  = "BTR";
    $sBordaValor = "BT";
  }
  if ($iIdLinha == 23) {

    $sBordaDesc  = "TR";
    $sBordaValor = "T";
  }

  $sBold = "";
  if ($oDado->totalizar) {
    $sBold = "B";
  }

  $oPdf->setfont('Arial', $sBold, 6);
  $oPdf->cell(150, $iAltura, setIdentacao($oDado->nivellinha).$oDado->descricao, $sBordaDesc, 0, "L");
  $oPdf->cell(40, $iAltura, db_formatar($oDado->valor, 'f'), $sBordaValor, 1, "R");
}

$oPdf->ln();
$oAnexoReceita->getNotaExplicativa($oPdf, $oGet->iPeriodo);
$oPdf->ln();

$oPdf->Output();

function montaCabecalho($oPdf, $iAltura) {

  $oPdf->addPage();
  $oPdf->setfont('Arial', 'b', 6);
  $oPdf->cell(190, $iAltura, "R$ 1,00", 0, 1, "R");
  $oPdf->cell(150, $iAltura+2, "Especificação"                       , "TRB", 0, "C");
  $oPdf->cell(40,  $iAltura+2, "Previsão ".db_getsession("DB_anousu"), "TB" , 1, "C");
}

function setIdentacao($iNivel) {

  $sEspaco = "";
  if ($iNivel > 1) {
    $sEspaco = str_repeat("   ", $iNivel);
  }
  return $sEspaco;
}
?>