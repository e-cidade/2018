<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2017  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

use ECidade\Tributario\Agua\DebitoConta\DebitoContaFactory;
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">
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
      <div id="log-processamento"></div>
    </fieldset>
  </div>

  <script type="text/javascript">
    var bar = $('barra-progresso');
    var logs = $('log-processamento');
    var progress = new ProgressBar(bar, logs);
  </script>

<?php function showDownloader($sArquivosPath, $sArquivoName) { ?>
  <script type="text/javascript">

    var oDownload = new DBDownload();

    oDownload.addFile('<?php echo $sArquivosPath; ?>', '<?php echo $sArquivoName; ?>');
    oDownload.show();

  </script>
<?php } ?>
</body>
</html>
<?php

set_time_limit(0);

try {

  $oParametros = db_utils::postMemory($_GET);

  $iTipoDebito = $oParametros->iTipoDebito;
  $iAno = $oParametros->iAno;
  $iMes = $oParametros->iMes;
  $iBanco = $oParametros->iBanco;

  if (empty($iTipoDebito)) {
    throw new Exception('Campo Tipo de Débito é de preenchimento obrigatório.');
  }

  if (empty($iAno)) {
    throw new Exception('Campo Exercício do Vencimento é de preenchimento obrigatório.');
  }

  if (empty($iMes)) {
    throw new Exception('Campo Mês do Vencimento é de preenchimento obrigatório.');
  }

  if (empty($iBanco)) {
    throw new Exception('Campo Banco é de preenchimento obrigatório.');
  }

  $iInstit = db_getsession("DB_instit");

  $oFactory = new DebitoContaFactory();

  $oGeradorArquivo = $oFactory->build();

  db_inicio_transacao();

  $sNomeArquivo = $oGeradorArquivo->gerar($iTipoDebito, $iAno, $iMes, $iBanco, $iInstit);

  db_fim_transacao(false);

  showDownloader($sNomeArquivo, "Arquivo Gerado");

} catch (Exception $oException) {

  db_fim_transacao(true);
  db_redireciona("db_erros.php?fechar=true&db_erro={$oException->getMessage()}");
}
