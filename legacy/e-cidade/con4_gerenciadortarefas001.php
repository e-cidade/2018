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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));

require_once(modification("dbforms/db_funcoes.php"));

db_app::import('configuracao.TaskManager');

$oPost = db_utils::postMemory($_POST);

if (isset($oPost->iniciar)) {

    echo $sRetorno = system("php cli.php --executable=con4_gerenciadortarefas002.php > tmp/log_gerenciador_tarefas.log 2> tmp/erros_gerenciador_tarefas.log &");

    $iterations = 0;
    while ($iterations++ < 120 && TaskManager::getInstance()->iniciarServico(true)) {
        usleep(500);
    }
}

if (isset($oPost->finalizar)) {

    if (file_exists('.GerenciadorTarefas.lock.xml')) {

        $sArquivoLock = '.GerenciadorTarefas.lock.xml';
        $aArquivoLock = (array)simplexml_load_file('.GerenciadorTarefas.lock.xml');
        $iPid = posix_getsid($aArquivoLock['iPIDProcesso']);
        $iIdProcesso = $aArquivoLock['iPIDProcesso'];
        echo $sRetorno = system("kill {$iIdProcesso}; rm -f {$sArquivoLock}; rm -f tmp/log_gerenciador_tarefas.log; rm -f tmp/erros_gerenciador_tarefas.log");
    }
}

$lPermiteInicializacao = true;
$oTaskManager = TaskManager::getInstance();

if (!$oTaskManager->iniciarServico(true)) {
    $lPermiteInicializacao = false;
}

if (isset($oPost->iniciar) && $lPermiteInicializacao) {
    $lPermiteInicializacao = false;
}
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <?php
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("strings.js");
    db_app::load("estilos.css");
    ?>
    <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>

</head>
<body class="body-default">

<div class="container">

    <form method="post">
        <fieldset>
            <legend><strong>Gerenciador de Tarefas:</strong></legend>

            <table class="form-container" style="width:250px;">
                <tr>
                    <td><label>Status:</label></td>
                    <td><?php echo $lPermiteInicializacao ? "Inativo" : "Ativo"; ?>
                    </td>
                </tr>

                <tr>
                    <td><label>PID do Processo:</label></td>
                    <td><?php echo $oTaskManager->getPIDProcesso(); ?></td>
                </tr>

                <tr>
                    <td><label>Inicio:</label></td>
                    <td><?php echo $oTaskManager->getDataInicio() . " - " . $oTaskManager->getHoraInicio(); ?></td>
                </tr>
            </table>

        </fieldset>

        <input type="submit" name="iniciar" class="btn-reload"
               value="Iniciar Serviço" <?php echo $lPermiteInicializacao ? "" : "disbled"; ?> />
        <input type="submit" name="finalizar" class="btn-reload" value="Finalizar Serviço"/>

    </form>

    <fieldset style="width: 700px;">
        <legend>Tarefas</legend>

        <div id="gridTarefas"></div>

    </fieldset>

</div>
<div id="ctnWindowExecucao" class="container" style="display:none;">

  <fieldset>
    <legend>Mensagens</legend>
    <div id="log" style="text-align: left; background-color: #FFF; padding: 10px; font-size: 14px">
    </div>
  </fieldset>

</div>
<?php
db_menu(db_getsession("DB_id_usuario"),
  db_getsession("DB_modulo"),
  db_getsession("DB_anousu"),
  db_getsession("DB_instit"));
?>
</body>

<script type="text/javascript">

  (function () {

    const RPC = 'con4_gerenciadortarefas.RPC.php';

    var oGridTarefas = new DBGrid("gridTarefas");
    var oWindow = null;

    oGridTarefas.nameInstance = "oGridTarefas";
    oGridTarefas.setHeader(["Tarefas", "Data Criação", "Status", "Lock", "Logs"]);
    oGridTarefas.setCellWidth(["200", "100", "200", "100", "50"]);
    oGridTarefas.setCellAlign(["left", "center", "center", "center", "center"]);
    oGridTarefas.setHeight(300);

    oGridTarefas.clearAll(true);
    oGridTarefas.show($('gridTarefas'))
    oGridTarefas.renderRows();

    resetGrid();

    function createHint(oElement, sText) {
      return DBHint.build(oElement, {text: sText, showEvents: ['onmouseover'], hideEvents: ['onmouseout']});
    }

    function formatarPeriodidicade(aPeriodicidade) {
      return aPeriodicidade.map(function (item) {

        var itens = item.split('');

        itens.splice(-2, 0, ':');

        return itens.join('');
      }).join('<br>');
    }

    function apagarLock(oButton) {

      if (!confirm('Tem certeza que deseja apagar o lock da tarefa?')) return;

      new AjaxRequest(RPC, {exec: 'apagarLock', sNome: oButton.getAttribute('data-nome')}, function (oRetorno, lErro) {
        resetGrid();
        return alert(oRetorno.sMessage.urlDecode());
      }).setMessage('Removendo lock da tarefa.').execute();
      ;

    }

    function fecharJanela() {
      oWindow.hide();
    }

    function exibirLogs(oButton) {
      document.getElementById("log").innerHTML = null;

      new AjaxRequest(RPC, {exec: 'getLogs', className: oButton.getAttribute('data-nome')}, function (oRetorno, lErro) {

        for(var index in oRetorno.aLogs) {
          var log = oRetorno.aLogs[index];

          var aIndex = index.split("/");

          document.getElementById("log").innerHTML += '<b><br>- ' + aIndex[1] + '<br></b>';

          log.forEach(function (valor, indice) {
            document.getElementById("log").innerHTML += '- ' + valor.urlDecode() + '<br>';
          });
        }

      var oContainerWindow = $('ctnWindowExecucao');

        if (oWindow === null) {

          oContainerWindow.style.display = '';
          oWindow = new windowAux('oWindow', 'Log da Tarefa', 750, 600);
          oWindow.setContent(oContainerWindow);
          oWindow.setShutDownFunction(
            function() {
              fecharJanela();
            }
          );
        }

        oWindow.setIndex(5);
        oWindow.show();

      }).setMessage('Buscando logs da tarefa.').execute();
    }

    function resetGrid() {

      new AjaxRequest(RPC, {exec: "getTarefas"}, function (oRetorno, lErro) {

        oGridTarefas.clearAll(true);

        oRetorno.aTarefas.forEach(function (oJob) {

          var sButtonLock = '<input type="button" value="Apagar" class="lockRow" data-nome="' + oJob.sNome + '">';
          var sButtonLog = '<input type="button" value="Ver" class="logRow" data-nome="' + oJob.sNomeClasse + '">';

          if (!oJob.lLock) {
            sButtonLock = '';
          }

          if (!oJob.lLog) {
            sButtonLog = '';
          }

          var aRow = [
            oJob.sNome,
            oJob.sDataCriacao,
            oJob.sStatus.urlDecode(),
            sButtonLock,
            sButtonLog
          ];

          oGridTarefas.addRow(aRow);
        });

        oGridTarefas.renderRows();

        // for each para a criacao dos hints
        oRetorno.aTarefas.forEach(function (oJob, iIndice) {

          var sTextNome = '<strong>Nome: </strong>' + oJob.sNome + '<br>';
          sTextNome += '<strong>Descrição: </strong>' + oJob.sDescricao;

          var sTextPeriodicidade = '<strong>Periodiciade: </strong>' + oJob.sTipoPeriodicidade.urlDecode() + '<br>';
          sTextPeriodicidade += '<strong>Horários: </strong>' + formatarPeriodidicade(oJob.aPeriodicidades);

          var sTextErro = '<strong>Erro: </strong><br/>' + oJob.sTextoErro.replace(/\<CRLF\>/g, '<br />');

          if (oJob.sTextoErro != '') {
            oGridTarefas.aRows[iIndice].addClassName('error');
            createHint($(oGridTarefas.aRows[iIndice].aCells[3].sId), sTextErro);
          }

          createHint($(oGridTarefas.aRows[iIndice].aCells[0].sId), sTextNome);
          createHint($(oGridTarefas.aRows[iIndice].aCells[1].sId), sTextPeriodicidade);
        });

        $$(".lockRow").each(function (oButton) {

          oButton.observe('click', function () {
            apagarLock(oButton)
          })
        });

        $$(".logRow").each(function (oButton) {
          oButton.observe('click', function () {
            exibirLogs(oButton);
          });
        });

      }).setMessage('Buscando tarefas.').execute();

    }

  })();

</script>

</html>
