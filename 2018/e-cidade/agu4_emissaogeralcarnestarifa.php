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
require_once modification("dbforms/db_funcoes.php");

use ECidade\Tributario\Agua\EmissaoCarnes\Geral;

$oParametros = db_utils::postMemory($_GET);
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
  <script language="javascript" type="text/javascript" src="scripts/widgets/ProgressBar.widget.js"></script>
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
      margin: 2px 10px 2px 10px;
      text-align: left;
      color: #878f87
    }
  </style>
</head>
<body class="body-default">
  <div class="container">
    <fieldset style="width: 700px; padding: 2px">
      <progress id="barra-progresso" value="0" style="width: 100%; height: 25px;">Processando</progress>
    </fieldset>
    <fieldset style="width: 700px; padding: 1px 2px">
      <div id="log-processamento">

      </div>
    </fieldset>
  </div>
  <script type="text/javascript">
    var bar = $('barra-progresso');
    var logs = $('log-processamento');
    var progress = new ProgressBar(bar, logs);
  </script>
</body>
</html>
<?php
$oBarraProgresso = new ProgressBar('progress');
$oBarraProgresso->flush();

function showDownloader($aArquivos) {
?>
  <script type="text/javascript">
  var oDownload = new DBDownload();
  <?php foreach($aArquivos as $aArquivo): ?>
    oDownload.addFile('<?php echo $aArquivo['path'] ?>', '<?php echo $aArquivo['name'] ?>');
  <?php endforeach; ?>
  oDownload.show();
  </script>
<?php
}

function showAlert($sMensagem) {
?>
  <script type="text/javascript">
    alert("<?= $sMensagem ?>");
  </script>
<?php
}

set_time_limit(0);

try {

  db_inicio_transacao();

  $oConfiguracao = ECidade\Tributario\Agua\Configuracao::create();
  $iCodigoTipoArrecadacao = $oConfiguracao->getCodigoTipoArrecadacao();
  $oDataEmissao = new DateTime(date('Y-m-d', db_getsession('DB_datausu')));

  $oBarraProgresso->setMessageLog('Iniciando Emissão Geral.');
  $oRegraEmissao = new regraEmissao(
    $iCodigoTipoArrecadacao, 7, $oConfiguracao->getCodigoInstituicao(), $oDataEmissao->format('Y-m-d'), db_getsession('DB_ip')
  );
  $oAguaEmissao = new AguaEmissao;

  $oGeral = new Geral;
  $oGeral->setBarraProgresso($oBarraProgresso);
  $oGeral->setAno($oParametros->iAno);
  $oGeral->setMesInicial($oParametros->iMesInicial);
  $oGeral->setMesFinal($oParametros->iMesFinal);
  $oGeral->setCodigoInstituicao($oConfiguracao->getCodigoInstituicao());
  $oGeral->setRegraEmissao($oRegraEmissao);
  $oGeral->setAguaEmissao($oAguaEmissao);
  $oGeral->setCodigoTipoArrecadacao($iCodigoTipoArrecadacao);

  $sNomeArquivoLog = "tmp/log_emissao_geral";
  $oGeral->setLogger(new DBLog("TXT", "tmp/log_emissao_geral"));
  $oGeral->setDataEmissao($oDataEmissao);
  $aArquivos = $oGeral->emitir();

  db_fim_transacao();
  $oBarraProgresso->setMessageLog('Emissão Geral Concluída.');

  $aArquivos = array(
    array(
      'path' => $aArquivos['arquivo'],
      'name' => 'Dados_Emissão_Geral'
    ),
    array(
      'path' => $aArquivos['layout'],
      'name' => 'Layout_Emissão_Geral'
    ),
    array(
      'path' => $sNomeArquivoLog . '.txt',
      'name' => 'Log_Emissão_Geral'
    )
  );

  showDownloader($aArquivos);
  showAlert("Emissão Concluída.");

} catch (Exception $oException) {

  db_fim_transacao($lErro = true);
  db_redireciona("db_erros.php?fechar=true&db_erro={$oException->getMessage()}");
}
