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
require_once("libs/JSON.php");
require_once("fpdf151/PDFDocument.php");

$oJson  = new services_json();

$oParam = db_utils::postMemory($_GET);
if (!isset($oParam->iTipoRelatorio)) {
  $oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));
}

$oRetorno           = new stdClass();
$oRetorno->mensagem = '';
$oRetorno->erro     = false;

try {

  if (empty($oParam->periodo_inicial)) {
    throw new Exception("A Data Inicial do campo Período é de preenchimento obrigatório.");
  }

  if (empty($oParam->periodo_final)) {
    throw new Exception("A Data Final do campo Período é de preenchimento obrigatório.");
  }

  $oDataInicial =  new DBDate($oParam->periodo_inicial);
  $oDataFinal   =  new DBDate($oParam->periodo_final);

  if ($oDataInicial->getTimeStamp() > $oDataFinal->getTimeStamp()) {
    throw new Exception("A Data Final do campo Período deve ser maior ou igual a Data Inicial.");
  }

  if ($oDataFinal->getAno() != $oDataInicial->getAno() || $oDataInicial->getMes() != $oDataFinal->getMes()) {
    throw new Exception("A data inicial e final do Período devem estar dentro da mesma competência.");
  }

  $aVeiculos = array();
  if (isset($oParam->aVeiculos) && !empty($oParam->aVeiculos)) {
    $aVeiculos = explode(",", $oParam->aVeiculos);
  }

  $oRelatorio = new RelatorioControleHodometro($oDataInicial,
                                               $oDataFinal,
                                               new Instituicao(db_getsession('DB_instit')),
                                               new DBDepartamento(db_getsession('DB_coddepto')));
  $oRelatorio->setVeiculos($aVeiculos);

  if ($oParam->iTipoRelatorio != 1) {

    $oRelatorio->emitirPdf();
    die;
  }
  $oRelatorio->emitirCsv();
  $oRetorno->caminho_relatorio = $oRelatorio->getArquivo()->getFilePath();

} catch (Exception $e) {

  if ($oParam->iTipoRelatorio != 1) {
    db_redireciona('db_erros.php?fechar=true&db_erro=' . urlencode($e->getMessage()));
    die;
  }
  $oRetorno->erro     = true;
  $oRetorno->mensagem = $e->getMessage();
}
$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo $oJson->encode($oRetorno);
