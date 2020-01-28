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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clavaliacao = new cl_avaliacao;
$clavaliacao->rotulo->label();
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
</head>
<body class='body-default'>
  <div class='container'>

    <form action="post">
      <fieldset>
        <legend>Exportar Formulário</legend>
        <table class="form-container">

        <tr>
             <td>
                <label for="db101_sequencial">
                  <a id="sLabelFormulario">Formulário:</a>
                </label>
             </td>

             <td>
               <?php
                db_input('db101_sequencial', 10, $Idb101_sequencial, true, 'text', 1, "");
                db_input('db101_descricao', 50, $Idb101_descricao, true, 'text', 3, "");
               ?>
             </td>
        </tr>

        </table>
      </fieldset>
      <input type="button" id="btn-exportar" value="Exportar" />
    </form>

  </div>
<?php
  db_menu();
?>
</body>
<script type="text/javascript">

var oLookUp = new DBLookUp($('sLabelFormulario'), $('db101_sequencial'), $('db101_descricao'), {
  "sArquivo" : "func_avaliacao.php",
  "sObjetoLookUp" : "db_iframe_avaliacao",
  "sLabel" : "Pesquisar Formulário",
  "aParametrosAdicionais" :["todos=true"]
});

$('btn-exportar').addEventListener('click', function() {

  var sequencial = $('db101_sequencial').value;

  if (!sequencial) {
    return alert('Selecione o formulário para exportar.');
  }

  var oParametros = {exec : 'exportar', formulario: sequencial};
  var request = new AjaxRequest('con4_manutencaoformulario.RPC.php', oParametros);
  request.setCallBack(function(oRetorno, lErro) {

    if (lErro) {
      return alert(oRetorno.mensagem)
    }

    var download = new DBDownload();
    download.addFile(oRetorno.sArquivo, "Arquivo de configuração do formulário");
    download.show();

  });
  request.setMessage("Aguarde, exportando formulário.");
  request.execute();
});

$('sLabelFormulario').click();

</script>

</html>
