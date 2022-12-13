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
</head>
<body >
    <form id='form1' class='container' action='eso4_versaoprocessamento.php' method='post' >
        <fieldset >
            <legend>Atualização de versão dos layouts</legend>
            <table class='form-container'>
                <tr>
                    <td class='field-size1'><label for='cboVersao'>Versão:</label></td>
                    <td>
                        <select class='field-size-max' name="versao" id="cboVersao"></select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type='submit' id='btnSalvar' disabled='disabled' value='Atualizar'>
    </form>
<?php
  db_menu();
?>
</body>
<script type='text/javascript'>

var RPC = 'eso4_versaolayout.RPC.php';
var versaoAtual = null;

$('cboVersao').addEventListener('change', function(){

    $('btnSalvar').removeAttribute('disabled');
    if (this.value == versaoAtual) {
        $('btnSalvar').setAttribute('disabled', 'disabled');
    }
});

$('form1').addEventListener('submit', function(e) {

    var confirmMsg = "Atenção!\nApós atualizar a versão dos layouts as rotinas de preenchimento mostrarão as ";
    confirmMsg += "informações do eSocial na versão selecionada. Não será possível retornar para a versão ";
    confirmMsg += "anterior depois da atualização.\n\nTem certeza que deseja atualizar?";
    if (!confirm(confirmMsg)) {
        e.preventDefault();
        return false;
    }
    return true;
});

(function(){

    new AjaxRequest(RPC, {exec: 'getVersao'}, function (retorno, error) {

        if (error) {
            alert(retorno.sMessage);
            return;
        }
        versaoAtual = retorno.versaoAtual;
        for(var versao of retorno.versoes) {
            $('cboVersao').add(new Option(versao, versao));
        }
    }).setMessage('Buscando versões.').execute();
})();

</script>
</html>
