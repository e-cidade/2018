<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("fpdf151/PDFDocument.php");
require_once("fpdf151/fpdf.php");
require_once("fpdf151/PDFTable.php");

$oJson                  = new services_json();
$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->sMensagem    = '';

define("MENSAGENS", "tributario.issqn.iss4_processaarquivoretencao.");

db_inicio_transacao();

try {

  switch ($oParametros->sExecucao) {

    case 'validarArquivo':

      if (!isset($oParametros->iIssArquivoRetencao) || empty($oParametros->iIssArquivoRetencao)) {
        throw new Exception( _M(MENSAGENS . "erro_arquivo_nao_informado"));
      }

      $oProcessaArquivoRetencao = new ProcessaArquivoRetencao($oParametros->iIssArquivoRetencao);
      $oProcessaArquivoRetencao->validarArquivo();

      $oRetorno->aRegistrosInconsistentes = $oProcessaArquivoRetencao->getRegistroInconsistente();
      $oRetorno->iCodigoArquivoRetencao   = $oParametros->iIssArquivoRetencao;
      break;

    case 'emitirRelatorio':

      if (empty($oParametros->aRegistrosInconsistentes)) {
        throw new Exception(_M( MENSAGENS . "erro_relatorio_sem_registro" ));
      }

      if (empty($oParametros->iCodigoArquivoRetencao)) {
        throw new Exception(_M( MENSAGENS . "erro_codigo_arquivo_nao_encontrado" ));
      }

      $oIssArquivoretencao = new IssArquivoRetencao($oParametros->iCodigoArquivoRetencao);

      $aHeaders = array( "Linha do Arquivo", "CPF/CNPJ", "Inconsistência" );

      $aWidth = array( 15, 25, 60 );

      $aAlign = array(
        PDFDocument::ALIGN_CENTER,
        PDFDocument::ALIGN_CENTER,
        PDFDocument::ALIGN_LEFT
      );

      $oPdfTable = new PDFTable(PDFDocument::PRINT_PORTRAIT);
      $oPdfTable->setTotalByPage(true);
      $oPdfTable->setPercentWidth(true);
      $oPdfTable->setHeaders($aHeaders);
      $oPdfTable->setColumnsWidth($aWidth);
      $oPdfTable->setColumnsAlign($aAlign);

      foreach ($oParametros->aRegistrosInconsistentes as $oRegistrosInconsistentes) {

        $oPdfTable->addLineInformation(

          array(
            $oRegistrosInconsistentes->sequencial_registro,
            db_stdClass::normalizeStringJsonEscapeString(strip_tags($oRegistrosInconsistentes->registro)),
            db_stdClass::normalizeStringJsonEscapeString(strip_tags($oRegistrosInconsistentes->mensagem))
          )
        );
      }

      $oPdfDocument = new PDFDocument();
      $oPdfDocument->SetFillColor(235);
      $oPdfDocument->addHeaderDescription("Relatório de Registros Inconsistentes no Arquivo de Retenção");
      $oPdfDocument->addHeaderDescription("");
      $oPdfDocument->addHeaderDescription("Nome do Arquivo: {$oIssArquivoretencao->getNomeArquivo()}");
      $oPdfDocument->open();

      $oPdfTable->printOut($oPdfDocument, false);

      $sArquivoRelatorio  = $oPdfDocument->savePDF();
      if( !file_exists($sArquivoRelatorio) ){
        throw new Exception(_M( MENSAGENS . "erro_gerar_relatorio" ));
      }

      $oRetorno->sArquivoRelatorio = $sArquivoRelatorio;

      break;

    case 'processarArquivo':

      if (empty($oParametros->iCodbco)) {
        throw new Exception(_M( MENSAGENS . "erro_banco_nao_encontrado" ));
      }

      if (empty($oParametros->iCodigoArquivoRetencao)) {
        throw new Exception(_M( MENSAGENS . "erro_codigo_arquivo_nao_encontrado" ));
      }

      $oProcessaArquivoRetencao = new ProcessaArquivoRetencao($oParametros->iCodigoArquivoRetencao);
      $oProcessaArquivoRetencao->validarProcessamento();
      $oProcessaArquivoRetencao->setBancoAgenciaConta($oParametros->iCodbco);
      $oProcessaArquivoRetencao->processarRegistros();

      $oRetorno->sMensagem = _M( MENSAGENS . "processado_sucesso" );

      break;
  }

  db_fim_transacao(false);

} catch(Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}
echo $oJson->encode($oRetorno);