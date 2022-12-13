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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_app.utils.php"));

use ECidade\Tributario\Agua\Leitura\Processamento;
use ECidade\Tributario\Agua\Repository\Leitura as LeituraRepository;


$oParametros = db_utils::postMemory($_GET); ?>


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

try {

  //Prepara processamento
  $sArquivoLog = "tmp/agua_processamento_leitura_manual_" . time();
  $oLog = new DBLog("TXT", $sArquivoLog);
  $oLog->escreverLog("Processamento iniciado.");

  $oBarraProgresso->setMessageLog('Iniciando processamento.');
  $oBarraProgresso->setMessageLog('(1/3) Buscando Informações dos Contratos...');

  $oDaoContratos    = db_utils::getDao('aguacontrato');
  $sSql  = "select distinct(aguacontrato.x54_sequencial), x21_mes, x21_exerc, ";
  $sSql .= "MAX((x21_exerc || '-' || x21_mes || '-01')::date) as data_max";
  $sSql .= " from aguacontrato ";
  $sSql .= "inner join aguacontratoligacao on (x55_aguacontrato = x54_sequencial) ";
  $sSql .= "inner join agualeitura on (x21_codhidrometro = x55_aguahidromatric) ";
  $sSql .= "group by aguacontrato.x54_sequencial, x21_mes, x21_exerc, x21_tipo, x21_status ";
  $sSql .= "having x21_tipo = " . AguaLeitura::TIPO_MANUAL;
  $sSql .= " and x21_status = " . AguaLeitura::STATUS_ATIVA;
  $sSql .= " and x21_exerc >= 2017 and ";
  $sSql .= "MAX((x21_exerc || '-' || x21_mes || '-01')::date) > '2017-06-01'::date";

  $rsContratosLeituraManual = $oDaoContratos->sql_record($sSql);

  if (!$rsContratosLeituraManual) {
    throw new Exception("Houve um erro ao consultar os contratos.");
  } elseif (pg_num_rows($rsContratosLeituraManual) == 0) {
    throw new Exception("Não hà encontrato nenhum contrato para ser processado.");
  }

  // Prepara barra de progresso
  $aContratosLeituraManual    = pg_fetch_all($rsContratosLeituraManual);
  $iTotalContratos            = count($aContratosLeituraManual);
  $iTotalContratosProcessados = 0;

  $oBarraProgresso->updateMaxProgress($iTotalContratos);
  $oBarraProgresso->setMessageLog('(2/3) Processando Informações...');


  foreach ($aContratosLeituraManual as $aContrato) {

    try {

      db_inicio_transacao();

      //Processa calculos de Media e penalidade
      $oContrato = new AguaContrato($aContrato['x54_sequencial']);
      if ($oContrato->deveRealizarCobranca()) {

        $oProcessamentoLeituraMedia = new Processamento(new LeituraRepository());

        $oProcessamentoLeituraMedia->setLogger($oLog);
        $oProcessamentoLeituraMedia->executar(
          $aContrato['x54_sequencial'],
          $aContrato['x21_mes'],
          $aContrato['x21_exerc']
        );
      }

      db_fim_transacao(false);

    } catch (Exception $oErro) {

      db_fim_transacao(true);
      $oLog->escreverLog("Contrato: {$aContrato['x54_sequencial']} - {$oErro->getMessage()}");
    }

    $iTotalContratosProcessados++;
    $oBarraProgresso->updatePercentual($iTotalContratosProcessados);

  }

  $oBarraProgresso->setMessageLog('(3/3) Preparando Arquivo de log...');

  $oLog->escreverLog("Fim do processamento");
  $oBarraProgresso->setMessageLog('Processamento Concluído.');

  $aArquivos = array(
    array(
      'path' => $sArquivoLog . '.txt',
      'name' => 'Log_Processamento_Medias'
    )
  );

  showDownloader($aArquivos);
  showAlert("Processamento Concluído.");

} catch (Exception $oErro) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$oErro->getMessage()}");
}
