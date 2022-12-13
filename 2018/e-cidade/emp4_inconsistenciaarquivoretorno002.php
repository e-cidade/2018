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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_sql.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("fpdf151/PDFDocument.php");

$oGet               = db_utils::postMemory($_GET);
$oDaoEmpAgeDadosRet = new cl_empagedadosret();

/*
 * Configura as variáveis de data para serem utilizadas no banco de dados
 */
$oDataInicial = new DBDate((empty($oGet->sDataInicial) ? date("Y-m-d") : $oGet->sDataInicial));
$oDataFinal   = new DBDate((empty($oGet->sDataFinal) ? date("Y-m-d") : $oGet->sDataFinal));

/*
 * Configuração do SQL que será executado
 */
$sSqlCampos  = " e75_codret as arquivo_retorno,                   ";
$sSqlCampos .= " e76_codmov as movimento,                         ";
$sSqlCampos .= " z01_nome as credor,                              ";
$sSqlCampos .= " e81_valor as valor,     													";
$sSqlCampos .= " case when 																				";
$sSqlCampos .= "        slip.k17_codigo is null 												";
$sSqlCampos .= "      then e50_codord 														";
$sSqlCampos .= "      else slip.k17_codigo 														";
$sSqlCampos .= "  end as op_slip, 																";
$sSqlCampos .= " e60_codemp  ||'/'||e60_anousu    as empenho,     ";
$sSqlCampos .= " e92_coderro ||' - '||e92_descrerro as erro_banco ";

/**
 * Configura as Datas caso alguma venha vazia
 */
if (empty($oGet->sDataInicial) && empty($oGet->sDataFinal)) {

  $sDataWhere   = "";
  $sHeadPeriodo = "Período: Nenhum período selecionado";

} else if (empty($oGet->sDataFinal)) {

  $sDataWhere   = " and e76_dataefet >= '{$oDataInicial->getDate()}' ";
  $sHeadPeriodo = "Período: {$oDataInicial->getDate(DBDate::DATA_PTBR)} à {$oDataFinal->getDate(DBDate::DATA_PTBR)}";

} else if (empty($oGet->sDataInicial)) {

  $sDataWhere   = " and e76_dataefet <= '{$oDataFinal->getDate()}' ";
  $sHeadPeriodo = "Período: até {$oDataFinal->getDate(DBDate::DATA_PTBR)}";

} else {

  $sDataWhere   = " and e76_dataefet between '{$oDataInicial->getDate()}' and '{$oDataFinal->getDate()}' ";
  $sHeadPeriodo = "Período: {$oDataInicial->getDate(DBDate::DATA_PTBR)} à {$oDataFinal->getDate(DBDate::DATA_PTBR)}";
}

$sSqlWhere         = "     e75_codret in ({$oGet->iArquivoRetorno}) ";
$sSqlWhere        .= " and e92_processa is false {$sDataWhere}   ";
$sSqlWhere        .= " and e92_sequencia <> 35   "; // o coderro BD não sera apresentado no relatório

$sSqlDadosRetorno  = $oDaoEmpAgeDadosRet->sql_query_erro_processamento(null, $sSqlCampos, null, $sSqlWhere);
$rsDadosRetorno    = $oDaoEmpAgeDadosRet->sql_record($sSqlDadosRetorno);
$iTotalLinhas      = $oDaoEmpAgeDadosRet->numrows;

if ($iTotalLinhas == 0) {
  db_redireciona("db_erros.php?fechar&db_erro=Nenhum registro encontrado.");
}

$oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);

$oPdf->addHeaderDescription("Relatório de Inconsistência do Arquivo do Retorno do Banco");
$oPdf->addHeaderDescription($sHeadPeriodo);

$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(244);
$oPdf->setFontSize(8);
$oPdf->addPage();
$oPdf->setAutoNewLineMulticell(false);

$iAltura = 4;
$lFill   = true;

$aWidth   = array( 20, 20, 20, 25, 30, 70 );
$aWidth[] = $oPdf->getAvailWidth() - array_sum($aWidth);

/*
 * Percorre o result set imprimindo os dados não processados de um arquivo
 */
for ($iRow = 0; $iRow < $iTotalLinhas; $iRow++) {

  $oDadoRet = db_utils::fieldsMemory($rsDadosRetorno, $iRow);

  $iAlturaCredor = $oPdf->getMultiCellHeight($aWidth[5], $iAltura, $oDadoRet->credor);
  $iAlturaErro   = $oPdf->getMultiCellHeight($aWidth[6], $iAltura, $oDadoRet->erro_banco);

  $iHeight  = max($iAlturaCredor, $iAlturaErro);

  if ($oPdf->getAvailHeight() < 8 || ($iRow == 0)) {

    if ($iRow != 0) {
      $oPdf->addPage();
      $oPdf->setAutoNewLineMulticell(false);
    }

    $oPdf->setBold(true);
    $oPdf->Cell($aWidth[0], $iAltura, "Retorno", 1, 0, 'C',1);
    $oPdf->Cell($aWidth[1], $iAltura, "Movimento", 1, 0, 'C',1);
    $oPdf->Cell($aWidth[2], $iAltura, "OP / SLIP", 1, 0, 'C',1);
    $oPdf->Cell($aWidth[3], $iAltura, "Valor", 1, 0, 'C',1);
    $oPdf->Cell($aWidth[4], $iAltura, "Empenho", 1, 0, 'C',1);
    $oPdf->Cell($aWidth[5], $iAltura, "Credor", 1, 0, 'C',1);
    $oPdf->Cell($aWidth[6], $iAltura, "Código/Descrição do erro" , 1, 1, 'C',1);
    $oPdf->setBold(false);
  }

  $lFill = !$lFill;

  $oPdf->cell($aWidth[0], $iHeight, $oDadoRet->arquivo_retorno, 0, 0, 'C', $lFill);
  $oPdf->cell($aWidth[1], $iHeight, $oDadoRet->movimento, 0, 0, 'C', $lFill);
  $oPdf->cell($aWidth[2], $iHeight, $oDadoRet->op_slip, 0, 0, 'C', $lFill);
  $oPdf->cell($aWidth[3], $iHeight, db_formatar($oDadoRet->valor, 'f'), 0, 0, 'R', $lFill);
  $oPdf->cell($aWidth[4], $iHeight, $oDadoRet->empenho, 0, 0, 'C', $lFill);
  $oPdf->multiCell($aWidth[5], ($iAltura == $iAlturaCredor ? $iHeight : $iAltura) , $oDadoRet->credor, 0, 'L', $lFill);
  $oPdf->multiCell($aWidth[6], ($iAltura == $iAlturaErro ? $iHeight : $iAltura), $oDadoRet->erro_banco, 0, 'L', $lFill);
  $oPdf->ln($iHeight);
}

$oPdf->showPDF("Inconsistencias_arquivo_retorno_" . time());