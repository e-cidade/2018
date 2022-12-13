<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require_once modification("dbforms/db_funcoes.php");

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa\RemessaRepository;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa\RemessaTemporaryService;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa\RemessaService;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa\RemessaArchive;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa\RemessaBuilder;
use \cl_remessacobrancaregistrada as RemessaCobrancaRegistradaDAO;
use \cl_conveniocobranca as ConvenioCobrancaDAO;

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style media="screen" type="text/css">

      #log-processamento {
        height: 150px;
        overflow-y: auto;
        width: 100%;
        background-color: #000;
        padding-top: 3px;
      }

      #log-processamento .item-log {
        margin: 2 10 2 10;
        text-align: left;
        color: #878f87
      }
    </style>
  </head>
  <body class="body-default" >
    <div class="container">
      <fieldset style="width: 700px; padding: 2">
        <progress id="barra-progresso" value="0" style="width: 100%; height: 25px;);">Processando</progress>
      </fieldset>
      <fieldset style="width: 700px; padding: 1 2">
        <div id="log-processamento">

        </div>
      </fieldset>
    </div>
    <script type="text/javascript">
      var bar = $('barra-progresso'),
          logs = $('log-processamento');

      function updateProgress(iValue) {
        bar.value = iValue;

        var oPerc = $('log-percentual');

        if (!oPerc) {
          var oPerc = document.createElement('span');
          oPerc.id = 'log-percentual';

          logMessage('Progresso: ', oPerc);
        }

        var nPercentual = new Number(iValue*100/bar.max);

        oPerc.textContent = nPercentual.toFixed(2) + "%";

        if (nPercentual == 100) {
          oPerc.id = '';
        }
      }

      function logMessage(sMessage, oNode) {

        var log = document.createElement('p');
        log.classList.add('item-log');
        log.textContent = '-> ' + sMessage;

        if (oNode) {
          log.appendChild(oNode);
        }

        logs.appendChild(log);
      }

      function createPercentualNode() {
        var log = document.createElement('p');
        log.classList.add('item-log');
        log.textContent = '-> ' + sMessage;

        logs.appendChild(log);
      }
    </script>
  </body>
</html>
<?php

function flushAll() {
  echo str_repeat(' ', 1024*64);
  flush();
}

flushAll();

function updateMaxProgress($iMax) {
  ?>
    <script type="text/javascript">
      bar.max = '<?php echo $iMax ?>';
    </script>
  <?php
  flushAll();
}

function updatePercentual($iAtual) {
  ?>
    <script type="text/javascript">updateProgress('<?php echo $iAtual ?>');</script>
  <?php
  flushAll();
}

function setMessageLog($sMessage) {
  ?>
    <script type="text/javascript">logMessage('<?php echo $sMessage ?>');</script>
  <?php
  flushAll();
}

function showDownloader($aArquivos) {

  foreach($aArquivos as $aArquivo) {
    ?>
      <script type="text/javascript">
        var oDownload = new DBDownload();
        oDownload.addFile('<?php echo $aArquivo['path'] ?>', '<?php echo $aArquivo['name'] ?>');
        oDownload.show();
      </script>
    <?php
  }
}

try {

  set_time_limit(0);

  db_inicio_transacao();

  setMessageLog("Preparando Registros (Etapa 1/3)");

  $oParametros = db_utils::postMemory($_GET);

  $iCodigoConvenio = $oParametros->codigo_convenio;
  $iCodigoRemessa = isset($oParametros->codigo_remessa) ? $oParametros->codigo_remessa : null;

  $oDadosInstituicao = db_stdClass::getDadosInstit();

  $oRemessaCobrancaRegistradaDAO = new RemessaCobrancaRegistradaDAO;
  $oConvenioCobrancaDAO = new ConvenioCobrancaDAO;

  $oRemessaRepository = new RemessaRepository($oRemessaCobrancaRegistradaDAO, $oConvenioCobrancaDAO);

  $oRemessaTemporaryService = new RemessaTemporaryService($oRemessaRepository);

  $oRemessaTemporaryService->setConvenio($iCodigoConvenio);
  $oRemessaTemporaryService->setRemessa($iCodigoRemessa);
  $oRemessaTemporaryService->setRegraCgmIss($oDadosInstituicao->db21_regracgmiss);
  $oRemessaTemporaryService->setRegraCgmIptu($oDadosInstituicao->db21_regracgmiptu);

  $oRemessaTemporaryService->preparaRegistros();

  setMessageLog("Preparando Registros (Etapa 2/3)");

  $oRemessaTemporaryService->atualizaRegistros();

  setMessageLog("Preparando Registros (Etapa 3/3)");

  $oRemessaTemporaryService->exportaRegistros();

  setMessageLog("Iniciando a Geração do Arquivo");

  $iQuantidadeRegistros = $oRemessaTemporaryService->getQuantidadeRegistros();

  updateMaxProgress($iQuantidadeRegistros);

  $oRemessaService = new RemessaService($oRemessaCobrancaRegistradaDAO);
  $oRemessaArchive = new RemessaArchive();

  $oRemessaBuilder = new RemessaBuilder($oRemessaService, $oRemessaTemporaryService, $oRemessaArchive);

  $aRecibosGerados = array();
  $iPercentualAtual = null;

  $oRemessaArquivo = $oRemessaBuilder->processaArquivoRemessa(function($iRegistroAtual, $iCalculoPercentual) use(&$iPercentualAtual) {

    if ($iPercentualAtual !== $iCalculoPercentual) {

      $iPercentualAtual = $iCalculoPercentual;
      updatePercentual($iRegistroAtual);
    }
  }, $_GET['lQuebraLinha']);

  setMessageLog("Preparando Remessa(s)");

  updateMaxProgress($iQuantidadeRegistros);

  $iPercentualAtual = null;

  $oRemessaBuilder->salvaArquivoRemessa($conn, $oRemessaArquivo->aReciboGerados, function($iRegistroAtual, $iCalculoPercentual) use(&$iPercentualAtual) {

    if ($iPercentualAtual !== $iCalculoPercentual) {

      $iPercentualAtual = $iCalculoPercentual;
      updatePercentual($iRegistroAtual);
    }
  });

  setMessageLog("Remessa(s) gerada(s) com sucesso.");

  showDownloader(array(
    array(
      'path' => $oRemessaArquivo->sArquivoNome,
      'name' => "Remessa Cobrança Registrada"
    )
  ));

  db_fim_transacao(false);

} catch (Exception $e) {

  db_fim_transacao(true);
  db_redireciona("db_erros.php?fechar=true&db_erro={$e->getMessage()}");
}
