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

$oGet = db_utils::postMemory($_GET);

try {

  $aHeaders = array(
    "Licença",
    "Empreendimento",
    "Nome do Empreendimento",
    "CNPJ",
    "Atividade",
    "Protocolo",
    "Data de Vencimento"
  );

  $aWidth = array(
    15,
    25,
    65,
    30,
    90,
    20,
    30
  );

  $aAlign = array(
    PDFDocument::ALIGN_CENTER,
    PDFDocument::ALIGN_CENTER,
    PDFDocument::ALIGN_LEFT,
    PDFDocument::ALIGN_CENTER,
    PDFDocument::ALIGN_LEFT,
    PDFDocument::ALIGN_CENTER,
    PDFDocument::ALIGN_CENTER
  );

  /**
   * Inserimos todos os tipos caso o campo for vazio(Todos)
   */
  if (empty($oGet->TipoLicenca)){
    $oGet->TipoLicenca = '1,2,3';
  }

  if ($oGet->condicao == "vencidas") {

    $oGet->dataInicial = new DBDate('01/01/1900');
    $oGet->dataFinal   = new DBDate(date('Y-m-d'));
  } else {

    $oGet->dataInicial = new DBDate($oGet->dataInicial);
    $oGet->dataFinal   = new DBDate($oGet->dataFinal);
  }

  $sSql  = " select am13_sequencial,                                                                                                                   ";
  $sSql .= "        am05_sequencial,                                                                                                                   ";
  $sSql .= "        am05_cnpj,                                                                                                                         ";
  $sSql .= "        am05_nome,                                                                                                                         ";
  $sSql .= "        am03_descricao,                                                                                                                    ";
  $sSql .= "        am08_protprocesso,                                                                                                                 ";
  $sSql .= "        am08_datavencimento                                                                                                                ";
  $sSql .= "   from empreendimento                                                                                                                     ";
  $sSql .= "        inner join empreendimentoatividadeimpacto on am06_empreendimento   = am05_sequencial                                               ";
  $sSql .= "        inner join atividadeimpacto               on am06_atividadeimpacto = am03_sequencial                                               ";
  $sSql .= "        inner join parecertecnico                 on am08_empreendimento   = am05_sequencial                                               ";
  $sSql .= "        inner join licencaempreendimento          on am13_parecertecnico   = case when am08_pareceranterior > 0 then am08_pareceranterior  ";
  $sSql .= "                                                                             else am08_sequencial end                                      ";
  $sSql .= "  where am08_sequencial in (select max(am08_sequencial)                                                                                    ";
  $sSql .= "                              from parecertecnico                                                                                          ";
  $sSql .= "                             where am08_empreendimento = am05_sequencial)                                                                  ";
  $sSql .= "    and am08_tipolicenca in ( $oGet->TipoLicenca )                                                                                         ";
  $sSql .= "    and am08_datavencimento >= '{$oGet->dataInicial->convertTo(DBDate::DATA_EN)}'                                                          ";

  if ($oGet->condicao == "vencidas") {
    $sSql .= "    and am08_datavencimento <  '{$oGet->dataFinal->convertTo(DBDate::DATA_EN)}'                                                          ";
  } else {
    $sSql .= "    and am08_datavencimento <=  '{$oGet->dataFinal->convertTo(DBDate::DATA_EN)}'                                                         ";
  }

  $sSql .= "    and am06_principal is true                                                                                                             ";
  $sSql .= "    group by am05_sequencial, am08_sequencial, am13_sequencial, am03_descricao                                                             ";
  $sSql .= "    order by $oGet->ordem                                                                                                                  ";

  $rsVencimentos = db_query($sSql);
  $iTotal        = pg_num_rows($rsVencimentos);

  if ($iTotal == 0) {
    throw new Exception("Não existem registros cadastrados!");
  }

  $oPdfTable = new PDFTable(PDFDocument::PRINT_LANDSCAPE);
  $oPdfTable->setTotalByPage(true);
  $oPdfTable->setHeaders($aHeaders);
  $oPdfTable->setColumnsWidth($aWidth);
  $oPdfTable->setColumnsAlign($aAlign);
  $oPdfTable->addHeaderDescription("Relatório de Vencimento de Licenças");
  $oPdfTable->addHeaderDescription("");
  $oPdfTable->addHeaderDescription("Filtro:");

  switch ($oGet->TipoLicenca) {
    case '1':
      $sTipoLicenca = 'Prévia';
      break;

    case '2':
      $sTipoLicenca = 'Instalação';
      break;

    case '3':
      $sTipoLicenca = 'Operação';
      break;

    default:
      $sTipoLicenca = 'Todos';
      break;
  }

  $oPdfTable->addHeaderDescription("   Tipo de Licença: " . $sTipoLicenca);

  if ($oGet->condicao != "vencidas") {

    $oPdfTable->addHeaderDescription("   Condição: A " . $oGet->condicao);
    $oPdfTable->addHeaderDescription("   Período: De " . $oGet->dataInicial->convertTo(DBDate::DATA_PTBR) . " até " . $oGet->dataFinal->convertTo(DBDate::DATA_PTBR));
  } else {
    $oPdfTable->addHeaderDescription("   Condição: " . $oGet->condicao);
  }

  $oPdfTable->addFormatting(7, PDFTable::FORMAT_DATE);

  for ($iRow = 0; $iRow < $iTotal; $iRow++) {

    $oVencimentos = db_utils::fieldsMemory($rsVencimentos, $iRow);

    $oPdfTable->addLineInformation(
      array(
        $oVencimentos->am13_sequencial,
        $oVencimentos->am05_sequencial,
        substr($oVencimentos->am05_nome, 0, 40),
        $oVencimentos->am05_cnpj,
        substr($oVencimentos->am03_descricao, 0, 55),
        $oVencimentos->am08_protprocesso,
        date('d/m/Y', strtotime($oVencimentos->am08_datavencimento))
      )
    );
  }

  $oPdfTable->printOut();
} catch (Exception $eErro) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$eErro->getMessage()}");
}
