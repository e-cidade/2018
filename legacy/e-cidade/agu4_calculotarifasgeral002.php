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

use ECidade\Tributario\Agua\Calculo\Calculo as CalculoGeral;

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
      <div id="log-processamento"></div>
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
$oProgressBar = new ProgressBar('progress');
$oProgressBar->flush();

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

  if (empty($oParametros->iAno)) {
    throw new ParameterException('Ano não informado.');
  }

  if (empty($oParametros->iMesInicial) || empty($oParametros->iMesFinal)) {
    throw new ParameterException('Mês Inicial/Final não informado.');
  }

  if ($oParametros->iMesFinal < $oParametros->iMesInicial) {
    throw new ParameterException('Mês Inicial não pode ser maior que Mês Final.');
  }

  $oInstituicao = new Instituicao(db_getsession('DB_instit'));
  if (!$oInstituicao->getUsaSisagua()) {
    throw new BusinessException('O cálculo deve ser executado na instituição configurada para o módulo Água.');
  }

  $oDadosExportacao = new clExpDadosColetores;
  $iTipoDebito      = $oDadosExportacao->getArretipo($oParametros->iAno);

  $iAno = (integer) $oParametros->iAno;
  if (!$iTipoDebito) {
    throw new BusinessException("Tipo de Débito não encontrado para o ano de {$iAno}");
  }

  $oProgressBar->setMessageLog('(1/3) Buscando Informações dos Contratos...');

  $rsContratos = db_query('select x54_sequencial from aguacontrato order by x54_sequencial');
  if (!$rsContratos) {
    throw new DBException('Não foi possível obter informações de contrato.');
  }

  $iTotalContratos = pg_num_rows($rsContratos);
  $iTotalContratosProcessados = 0;
  $oProgressBar->updateMaxProgress($iTotalContratos);

  $sArquivoLog = "tmp/agua_calculo_" . time();
  $oLog = new DBLog("TXT", $sArquivoLog);
  $oLog->escreverLog("Cálculo iniciado.");

  $oProgressBar->setMessageLog('(2/3) Executando cálculo de tarifas contratos...');
  while ($oContrato = pg_fetch_object($rsContratos)) {

    try {

      db_inicio_transacao();

      $oContrato = new AguaContrato($oContrato->x54_sequencial);
      $oCalculoTarifas = new CalculoGeral;
      $oCalculoTarifas->setContrato($oContrato);
      $oCalculoTarifas->setAno($oParametros->iAno);
      $oCalculoTarifas->setCodigoUsuario(db_getsession('DB_id_usuario'));
      $oCalculoTarifas->setTipoDebito($iTipoDebito);
      $oCalculoTarifas->setMesInicial((integer) $oParametros->iMesInicial);
      $oCalculoTarifas->setMesFinal((integer) $oParametros->iMesFinal);
      $oCalculoTarifas->setLogger($oLog);
      $oCalculoTarifas->processar();

      db_fim_transacao($lErro = false);

    } catch (Exception $oErro) {

      db_fim_transacao($lErro = true);
      $oLog->escreverLog("Contrato: {$oContrato->getCodigo()} - {$oErro->getMessage()}");
    }

    $iTotalContratosProcessados++;
    $oProgressBar->updatePercentual($iTotalContratosProcessados);
  }

  $oProgressBar->setMessageLog('Cálculo concluído.');
  $oLog->escreverLog("Cálculo concluído.");

  $aArquivos = array();
  $aArquivos[] = array(
    'path' => $sArquivoLog . '.txt',
    'name' => 'Arquivo de Log do Cálculo',
  );
  showDownloader($aArquivos);
  showAlert("Cálculo concluído.");


} catch (Exception $oException) {

  db_fim_transacao($lErro = true);
  db_redireciona("db_erros.php?fechar=true&db_erro={$oException->getMessage()}");
}
