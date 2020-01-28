<?php
/**
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">

  <fieldset style="width: 600px;">
    <legend class="bold">Atualização do Plano de Contas</legend>
    <table>
      <tr>
        <td class="bold">
          <label for="codigo_importacao">
            <?php
            db_ancora('Modelo:', 'pesquisas.pesquisar(true)', 1);
            ?>
          </label>
        </td>
        <td>
          <?php
          $Scodigo_importacao = "Modelo";
          db_input('codigo_importacao', 8, 1, true, 'text', 1, 'onchange="pesquisas.pesquisar(false)"');
          db_input('modelo_importacao', 50, 4, true, 'text', 3);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <p>
    <input type="button" name="btnImprimir" id="btnImprimir" value="Emitir" onclick="emitir()"/>
  </p>

</div>

<?php db_menu(); ?>
</body>
</html>

<script type="text/javascript">

  (function (exports) {

    var pesquisas, oInput;

    oInput = {
      'codigo_importacao' : $('codigo_importacao'),
      'modelo_importacao' : $('modelo_importacao')
    };

    function emitir() {

      if (oInput.codigo_importacao.value == "") {
        return alert("Campo Modelo é de preenchimento obrigatório.");
      }

      var oRelatorio = new EmissaoRelatorio('con2_importacaopcasp002.php', {codigo_importacao : oInput.codigo_importacao.value});
      oRelatorio.open();
    }

    pesquisas = {

      pesquisar : function (lMostrar) {

        if (oInput.codigo_importacao.value == "" && !lMostrar) {
          oInput.codigo_importacao.value = "";
          oInput.modelo_importacao.value = "";
          return;
        }

        var sPesquisar = "func_importacaoplanoconta.php?funcao_js=parent.pesquisas.preencherImportacao|0|2|3";
        if (!lMostrar) {
          sPesquisar = "func_importacaoplanoconta.php?pesquisa_chave=" + oInput.codigo_importacao.value + "&funcao_js=parent.pesquisas.completaImportacao"
        }
        js_OpenJanelaIframe('', 'db_iframe_importacaoplanoconta', sPesquisar, 'Pesquisar Importações do Plano de Contas', lMostrar);
      },

      preencherImportacao : function () {
        oInput.codigo_importacao.value = arguments[0];
        oInput.modelo_importacao.value = arguments[1] + " - " + arguments[2];
        db_iframe_importacaoplanoconta.hide();
      },

      completaImportacao : function () {

        if (arguments[1] === true) {
          oInput.codigo_importacao.value = '';
          oInput.modelo_importacao.value = arguments[0];
          return;
        }
        oInput.modelo_importacao.value = arguments[0] + " - " + arguments[1];
      }
    };

    exports.pesquisas = pesquisas;
    exports.emitir = emitir;

  })(this);
</script>