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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");


?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
db_app::load("estilos.css, grid.style.css");
?>

</head>
<body bgcolor=#CCCCCC >

<div class='container'>
  <form name="form1" action="">
    <fieldset >
      <legend>Reabir Fechamento de Competência</legend>
      <table class='form-container'>
        <tr style="display: none;">
          <td><input type="text" value='' id='codigoFechamento' /></td>
        </tr>

        <tr nowrap="nowrap">
          <td class="bold" >
            <?php
              db_ancora('Fechamento:', 'js_buscaFechamento(true)', 1);
            ?>
          </td>
          <td nowrap="nowrap">
            <?php
            db_input("iFechamento", 10, 1, true, "text", 1, "onchange='js_buscaFechamento(false);'");
            db_input("sFechamento", 35, 1, true, "text", 3, "");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" name="salvar" id="salvar" value="Reabrir" onclick="js_reabrir();"/>
  </form>
</div>
</body>
<?
db_menu (db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"),
  db_getsession("DB_instit" ));
?>

<script type="text/javascript">

  const MSG_LAB_REABRIRFECHAMENTO = "saude.laboratorio.lab4_reabrirfechamento.";

  function js_buscaFechamento ( lMostra ) {

    var sUrl = 'func_lab_fechamento.php?sFechadas=S';

    if (lMostra) {

      sUrl += '&funcao_js=parent.js_mostraFechamento|la54_i_codigo|la54_c_descr';
      js_OpenJanelaIframe('', 'db_iframe_lab_fechamento', sUrl, "Pesquisa Fechamento", true);
    } else if ($F('iFechamento') != '') {

      sUrl += '&funcao_js=parent.js_mostraFechamento';
      sUrl += '&pesquisa_chave='+$F('iFechamento');
      js_OpenJanelaIframe('', 'db_iframe_lab_fechamento', sUrl, "Pesquisa Fechamento", true);
    } else {

      $('iFechamento').value = '';
      $('sFechamento').value = '';
    }
  }

  function js_mostraFechamento() {

    if ( typeof arguments[1] == 'boolean') {

      $('sFechamento').value = arguments[0];
      if (arguments[1]) {
        $('iFechamento').value = '';
      }

    } else {

      $('iFechamento').value = arguments[0];
      $('sFechamento').value = arguments[1];
    }
    db_iframe_lab_fechamento.hide();
  }

  (function(){

    $('iFechamento').value = '';
    $('sFechamento').value = '';
  })();


  function js_reabrir() {

    if ( $F('iFechamento') == '') {

      alert (_M(MSG_LAB_REABRIRFECHAMENTO+"selecione_competencia"));
      return;
    }
    var oParametros = { 'exec' : 'reabrirCompetencia', 'iCompetencia' : $F('iFechamento')};

    var oRequest        = {};
    oRequest.method     = 'post';
    oRequest.parameters = 'json='+Object.toJSON(oParametros);
    oRequest.onComplete = function (oAjax) {

      js_removeObj('msgBox');
      var oRetorno = eval ('(' + oAjax.responseText + ')');

      if (parseInt(oRetorno.status) == 2) {

        alert(oRetorno.message.urlDecode());
        return;
      }
      alert(_M(MSG_LAB_REABRIRFECHAMENTO+"competencia_reaberta"));
      document.form1.reset();
    }

    js_divCarregando(_M(MSG_LAB_REABRIRFECHAMENTO+"aguarde_reabrindo"), "msgBox");
    new Ajax.Request('lab4_fechacompetencia.RPC.php', oRequest);
  }

</script>