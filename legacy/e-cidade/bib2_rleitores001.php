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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_stdlibwebseller.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("classes/db_leitorcategoria_classe.php"));
require_once (modification("dbforms/db_funcoes.php"));
db_postmemory($_POST);

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

  <?php MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto").""); ?>

  <div class=" container">
    <form name="form1" method="post" action="">
      <fieldset align="center" style="width:95%">
        <legend><b>Relatório de Leitores</b></legend>

        <table class='form-container'>
          <tr >
            <td nowrap title="Ordem Alfabética/Numérica" >
              <label for="ordem">Ordem:</label>
            </td>
            <td>
              <select id='ordem'>
                <option value='a'>Alfabética</option>
                <option value='b'>Numérica</option>
              </select>
            </td>
          </tr>
          <tr>
            <td nowrap title="Ordem Alfabética/Numérica" >
              <label for="categoria">Categoria:</label>
             <strong>&nbsp;&nbsp;</strong>
             </td>
            <td>
              <select id='categoria'>
              </select>
            </td>
          </tr>
        </table>

      </fieldset>
      <input  name="imprimir" id="btnImprimir" type="button" value="Imprimir" disabled="disabled" onclick="js_emite();" />
    </form>
  </div>
  <?php db_menu(); ?>
  </body>
</html>
<script>
function js_emite() {

  var categoria = "TODAS";
  if ($F('categoria') != "") {
    categoria = $('categoria').options[$('categoria').selectedIndex].text;
  }

  var sParametros = btoa('ordem='+$F('ordem')+'&categoria='+$F('categoria')+'&sCategoria='+categoria);
  window.open('bib2_rleitores002.php?q='+sParametros,'','scrollbars=1,location=0 ');
}

(function() {

  new AjaxRequest('edu4_biblioteca.RPC.php', {exec: 'buscaCategoriaDeCarteira'}, function (oRetorno, lErro) {

    if ( lErro ) {

      alert(oRetorno.sMessage);
      return;
    }

    $('categoria').options.length = 0;
    $('categoria').add( new Option ('TODOS', '') );

    for ( oCategoria of oRetorno.aCategorias ) {
      $('categoria').add( new Option (oCategoria.categoria, oCategoria.codigo) );
    }

    $('btnImprimir').removeAttribute('disabled', 'disabled');
  }).execute();

})()
</script>
