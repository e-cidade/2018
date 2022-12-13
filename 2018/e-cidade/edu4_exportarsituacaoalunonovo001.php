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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body >

  <?php MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <div class="container">
    <form class="container" name="form1" method="post" action="">
      <fieldset>
        <legend>Gerar Arquivo de Exportação - CENSO ESCOLAR - SITUAÇÃO DO ALUNO</legend>

        <fieldset  class='separator' >
          <legend><b>Data do Censo</b></legend>
          <table class="form-container">
            <tr>
              <td class="field-size2">
                <label for="data_censo">Data:</label>
              </td>
              <td>
                <input type="text" class="readonly field-size2" name="data_censo" id="data_censo" disabled="disabled" />
              </td>
              <td class="field-size2">
                <label for="ano_censo">Ano:</label>
              </td>
              <td>
                <input type="text" class="readonly field-size2" name="ano_censo" id="ano_censo" disabled="disabled" />
              </td>
            </tr>
          </table>
        </fieldset>

        <fieldset class="separator">
          <legend>Calendário</legend>
          <table class="form-container">
            <tr>
              <td  class="field-size2" >
                <label for="inicio_calendario">Data Inicial:</label>
              </td>
              <td>
                <input type="text" class="readonly field-size2" name="inicio_calendario" id="inicio_calendario" disabled="disabled" />
              </td>
              <td class="field-size2">
                <label for="fim_calendario">Data Final:</label>
              </td>
              <td>
                <input type="text" class="readonly field-size2" name="fim_calendario" id="fim_calendario" disabled="disabled" />
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>
      <input type="button" name="gerarArquivo" id="gerarArquivo" disabled="disabled" value="Gerar Arquivo" />
    </form>
  </div>
</body>
<?php db_menu(); ?>
</html>
<script>

var sFonteMsg = 'educacao.escola.edu4_exportarsituacaoalunonovo001.';

document.addEventListener('DOMContentLoaded', function() {

  /**
   * O Ano da exportação da situação do aluno é sempre referente ao ano anterior.
   * Ela é processada em fevereiro referente a situação dos alunos no ano anterior
   */
  var iAno        = new Date().getFullYear() - 1;
  var oParametros = { 'exec': 'buscaDataBaseCenso', 'iAno' : iAno};
  new AjaxRequest('edu4_censo.RPC.php', oParametros, function(oRetorno, lErro) {

    if ( lErro ) {

      alert(oRetorno.sMessage);
      return false;
    }

    $('data_censo').value        = oRetorno.dataCenso;
    $('ano_censo').value         = oRetorno.iAno;
    $('inicio_calendario').value = oRetorno.inicioCalendario;
    $('fim_calendario').value    = oRetorno.fimCalendario;

    $('gerarArquivo').removeAttribute('disabled');
    $('gerarArquivo').addEventListener('click', gerarArquivo);

  }).setMessage(_M(sFonteMsg + 'buscandoDataBase')).execute();
});

/**
 * Envia os parâmetros para o RPC e executa o gerarArquivo
 */
function gerarArquivo() {

  var oParametros = { 'exec': 'exportarSituacaoAluno', 'iAno' : $F('ano_censo'), 'sData' : $F('data_censo')};
  new AjaxRequest('edu4_censo.RPC.php', oParametros, function(oRetorno, lErro) {
    retornoGerarArquivo(oRetorno, lErro);
  }).setMessage( _M(sFonteMsg + 'exportandoArquivo')).execute();
}

/**
 * Pega o retorno da requisição de gerarArquivo e abre arquivo de log
 */
function retornoGerarArquivo(oRetorno, lErro) {

  if ( lErro ) {

    alert(oRetorno.sMessage);
    return
  }

  if ( oRetorno.lInconsistente ) {

    var oGet = 'sCaminhoArquivo=' + oRetorno.sArquivoLog + '&iAno=' + $F("ano_censo");
    var jan  = window.open(
                            'edu4_logexportarsituacaoaluno002.php?' + oGet,
                            'Erros Geração de Arquivo de Situação do Aluno do Censo escolar',
                            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
                          );
    jan.moveTo(0,0);
    return;
  }

  var oDownload = new DBDownload();
  oDownload.addFile(oRetorno.sArquivoCenso, "Arquivo situação do censo escolar.");
  oDownload.show();
}

</script>