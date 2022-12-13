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
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_libdocumento.php");
require_once("fpdf151/PDFDocument.php");

$oGet = db_utils::postMemory($_GET);

try {

  if (empty($oGet->iVeiculo)) {
    throw new Exception("Código do veículo não informado.");
  }

  if (empty($oGet->periodo_inicial)) {
    throw new Exception("Período inicial não informado.");
  }

  if (empty($oGet->periodo_final)) {
    throw new Exception("Período final não informado.");
  }

  $oDataInicial = new DBDate($oGet->periodo_inicial);
  $oDataFinal   = new DBDate($oGet->periodo_final);

  if ($oDataFinal->getAno() != $oDataInicial->getAno()) {
    throw new Exception("Somente é permitido emitir o relatório para um período dentro do mesmo exercício.");
  }

  $oRelatorioManutencao = new RelatorioFichaControleVeiculo((int) $oGet->iVeiculo, $oDataInicial, $oDataFinal);
  if (!empty($oGet->iSituacao)) {
    $oRelatorioManutencao->setSituacao((int) $oGet->iSituacao);
  }
  $oRelatorioManutencao->emitir();
} catch (Exception $e) {
  db_redireciona('db_erros.php?fechar=true&db_erro=' . urlencode($e->getMessage()));
}
