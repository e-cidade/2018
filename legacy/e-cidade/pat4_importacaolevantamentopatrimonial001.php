<?php
/*
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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";
require_once "libs/db_utils.php";
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>

  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">

    <fieldset style="width: 400px;">

      <legend>
        <strong>Importação do Arquivo de Levantamento Patrimonial</strong>
      </legend>

      <table>
        <tr>
          <td><label for="arquivo" class="bold">Arquivo:</label></td>
          <td style="width: 180px"><?php db_input('arquivo', 20, 0, false, $dbhidden = 'file') ?></td>
        </tr>
      </table>

    </fieldset>

    <input name="processar" id="btnProcessar" type="button" value="Processar">
  </div>
  <script>
  const URL_RPC = 'pat4_levantamentopatrimonial.RPC.php';

  document.observe('dom:loaded', function () {

    $('btnProcessar').observe('click', function() {

      var oParametros = { sExecucao : 'processarArquivo' };

      if (empty($F('arquivo'))) {

        alert("O campo Arquivo é de preenchimento obrigatório.");
        return false;
      }
      if (!verificaTemImportacao()) {
        return;
      }
      new AjaxRequest(URL_RPC, oParametros, function(oRetorno, lErro) {

        if (lErro) {

          alert(oRetorno.sMensagem.urlDecode());
          return;
        }
        if (confirm(oRetorno.sMensagem.urlDecode())) {

          var sProgramaRelatorio = 'pat2_relatoriolevantamentopatrimonial002.php?iDepartamento=' + oRetorno.iDepartamento;
          var oJanela = window.open(sProgramaRelatorio,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
          oJanela.moveTo(0,0);
        }
      }).setMessage("Aguarde, importando o arquivo...")
        .asynchronous(false)
        .addFileInput($('arquivo'))
        .execute();
    });

    /**
     * Verifica se já existe uma importação para o departamento no arquivo
     *
     * @return {boolean} Verdadeiro se deve continuar com a importação.
     */
    function verificaTemImportacao() {

      var lContinuarImportacao = true;
      var oParametros          = { sExecucao : 'verificaTemImportacao' };

      new AjaxRequest(URL_RPC, oParametros, function(oRetorno, lErro) {

        if (lErro) {

          alert(oRetorno.sMensagem.urlDecode());
          lContinuarImportacao = false;
        }
        if (oRetorno.lTemImportacao === true) {
          lContinuarImportacao = confirm(oRetorno.sMensagem.urlDecode());
        }
      }).setMessage("Aguarde...")
        .asynchronous(false)
        .addFileInput($('arquivo'))
        .execute();

      return lContinuarImportacao;
    }
  });
  </script>
  <?php db_menu() ?>
</body>
</html>
