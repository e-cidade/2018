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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));


?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div style="width: 60%" class="container">
      <fieldset>
        <legend>
          Processamento da Carga do Formulário
        </legend>
        <table id="tblFormularios">
        </table>
      </fieldset>
      <input type="button" value="Processar" id="btnProcessar">
      <input type="button" value="Marcar Todos" id="btnMarcarTodos">
      <input type="button" value="Desmarcar Todos" id="btnDesmarcarTodos">
    </div>
  </body>
</html>
<?php
db_menu();
?>
<script>

(function(exports)
{
  const URL_RPC     = 'con4_processarcargaformulario.RPC.php';

  var btnProcessar      = $('btnProcessar');
  var btnMarcarTodos    = $('btnMarcarTodos');
  var btnDesmarcarTodos = $('btnDesmarcarTodos');

  function processar () {

    var arquivos = $$("input[type='checkbox'].formulario_dinamico:checked");
    if (arquivos.length == 0) {

      alert('Selecione ao menos um arquivo.');
      return false;
    }
    var listaArquivos = [];
    for (arquivo of arquivos) {
      listaArquivos.push(arquivo.value);
    }

    var request = {
      exec       : 'processar',
      formularios: listaArquivos
    };

    new AjaxRequest(URL_RPC, request, function(response, erro) {

      alert(response.mensagem);
      if (erro) {
        return false;
      }
    }).setMessage('Aguarde, processando Arquivos. Esse processamento pode demorar alguns minutos...').execute();
  }

  /**
   * Selecionar ou marca todos os checkboxes
   * @param marcar
   */
  function selecionarOuDesmarcarTodos(marcar) {

    var arquivos = $$("input[type='checkbox'].formulario_dinamico");
    for (arquivo of arquivos) {
      arquivo.checked = marcar;
    }
  }

  btnProcessar.observe('click', function() {
    processar();
  }.bind(this));

  btnMarcarTodos.observe('click', function() {
    selecionarOuDesmarcarTodos(true);
    }.bind(this));

  btnDesmarcarTodos.observe('click', function() {
    selecionarOuDesmarcarTodos(false);
    }.bind(this));

  getFormularios = function() {

    var oUrl    =  js_urlToObject();
    if (empty(oUrl.tipo_formulario)) {

      alert('Tipo de Formulário não informado.');
      return
    }

    var request = {
      exec            : 'getFormulariosESocial',
      tipo_formulario :oUrl.tipo_formulario
    };

    new AjaxRequest('hab1_cadastroavaliacao.RPC.php', request, function(response, erro) {

      var tabelaLinhas = $('tblFormularios');

      if (erro) {

        alert(response.sMensagem);
        return false;
      }

      var iTotalLinhas =  tabelaLinhas.rows.length;
      for (iLinha = iTotalLinhas-1 ;iLinha >= 0; iLinha--) {
        tabelaLinhas.deleteRow(iLinha);
      }

      for (formulario of response.formularios) {

        var oLinha         = document.createElement("tr");
        var celulaCheckbox = document.createElement("td");

        var checkBox       = document.createElement("input");
        checkBox.setAttribute("id", formulario.identificador);
        checkBox.setAttribute("value", formulario.codigo);
        checkBox.setAttribute("type", 'checkbox');
        checkBox.setAttribute("class", 'formulario_dinamico');
        var celulaLabel = document.createElement('td');

        var label       = document.createElement('label');
        label.htmlFor   = formulario.identificador;
        label.innerHTML = formulario.nome;

        celulaCheckbox.appendChild(checkBox);
        celulaLabel.appendChild(label);
        oLinha.appendChild(celulaCheckbox);
        oLinha.appendChild(celulaLabel);
        tabelaLinhas.appendChild(oLinha);

      }
    }).setMessage('Aguarde, Pesquisando Formulários. Esse processamento pode demorar alguns minutos...').execute();

  }
  getFormularios();
})(window);
</script>



