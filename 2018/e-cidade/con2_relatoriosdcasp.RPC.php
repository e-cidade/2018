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

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoV;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_liborcamento.php");
require_once modification("libs/db_libcontabilidade.php");
require_once modification("fpdf151/PDFDocument.php");
require_once modification("fpdf151/PDFTable.php");

$oParametro         = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->mensagem = "";
$iAnoSessao = db_getsession('DB_anousu');
try {

  db_inicio_transacao();

  switch ($oParametro->exec) {

    case "getRecursosNaoConfigurados":

      db_query("drop table if exists work_dotacao");
      db_query("drop table if exists work_receita");


      $lValidarExercicioAnterior = $oParametro->imprimirValorExercicioAnterior == "t";

      $oRelatorio = RelatorioDCASPFactory::getInstancia($oParametro->iCodigoRelatorio, $iAnoSessao, $oParametro->iCodigoPeriodo);
      $oRelatorio->setInstituicoes(implode(',', $oParametro->aCodigosInstituicao));
      $aRecursosValidar = $oRelatorio->getLinhasObrigaRecurso();

      $aRecursosPendentes = RelatoriosLegaisBase::getRecursosPendentesConfiguracao(
        $oRelatorio,
        $aRecursosValidar,
        $lValidarExercicioAnterior
      );

      $oRetorno->lEmiteLista = false;
      $oRetorno->sArquivo    = '';
      if ( count($aRecursosPendentes) > 0 ) {

        $oRetorno->lEmiteLista = true;
        $oPdf = new PDFTable();
        $oPdf->setPercentWidth(true);
        $oPdf->setHeaders(
          array(
            "Código do Recurso",
            "Descrição"
          )
        );

        $oPdf->setColumnsAlign(
          array(
            PDFDocument::ALIGN_CENTER,
            PDFDocument::ALIGN_LEFT
          )
        );

        $oPdf->setColumnsWidth(
          array(
            "20",
            "80"
          )
        );

        $oPdf->setMulticellColumns(array(1));

        foreach ($aRecursosPendentes as $oRecurso) {

          $oPdf->addLineInformation(
            array(
              $oRecurso->getCodigo(),
              $oRecurso->getDescricao()
            )
          );
        }

        $oPdfDocument = new PDFDocument();
        $oPdfDocument->addHeaderDescription($oRelatorio->getRelatorioContabil()->getDescricao());
        $oPdfDocument->addHeaderDescription("");
        $oPdfDocument->addHeaderDescription("Recursos que possuem movimentações no exercício atual ou no exercício anterior e não estão configurados no relatório.");
        $oPdfDocument->SetFillColor(235);
        $oPdfDocument->open();

        $oPdf->printOut($oPdfDocument, false);
        $oRetorno->sArquivo = urlencode($oPdfDocument->savePDF('RecursosNaoConfigurados'));
      }
      break;


      case 'verificarRecursos':

          $oRelatorio = new AnexoV($iAnoSessao, $oParametro->iCodigoPeriodo);
          $oRelatorio->setInstituicoes(implode(',', $oParametro->aCodigosInstituicao));
          $aLinhasProcessar = $oRelatorio->getLinhasAnaliticas();

          $aRecursosPendentes = RelatoriosLegaisBase::getRecursosPendentesConfiguracao(
              $oRelatorio,
              $aLinhasProcessar,
              false
          );

          $oRetorno->lEmiteLista = false;
          $oRetorno->sArquivo    = '';
          if ( count($aRecursosPendentes) > 0 ) {

              $oRetorno->lEmiteLista = true;
              $oPdf = new PDFTable();
              $oPdf->setPercentWidth(true);
              $oPdf->setHeaders(array(
                      "Código do Recurso",
                      "Descrição"
                  ));

              $oPdf->setColumnsAlign(array(
                      PDFDocument::ALIGN_CENTER,
                      PDFDocument::ALIGN_LEFT
                  ));

              $oPdf->setColumnsWidth(array(
                      "20",
                      "80"
                  ));

              $oPdf->setMulticellColumns(array(1));

              foreach ($aRecursosPendentes as $oRecurso) {

                  $oPdf->addLineInformation(array(
                          $oRecurso->getCodigo(),
                          $oRecurso->getDescricao()
                      ));
              }

              $oPdfDocument = new PDFDocument();
              $oPdfDocument->addHeaderDescription($oRelatorio->getRelatorioContabil()->getDescricao());
              $oPdfDocument->addHeaderDescription("");
              $oPdfDocument->addHeaderDescription("Recursos que possuem movimentações no exercício atual ou no exercício anterior e não estão configurados no relatório.");
              $oPdfDocument->SetFillColor(235);
              $oPdfDocument->open();

              $oPdf->printOut($oPdfDocument, false);
              $oRetorno->sArquivo = $oPdfDocument->savePDF('RecursosNaoConfigurados'.time());
          }
          break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->mensagem = $eErro->getMessage();
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);