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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require(modification("libs/db_stdlibwebseller.php"));

?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?
	  db_app::load("scripts.js");
	  db_app::load("prototype.js");
    db_app::load("arrays.js");
	  db_app::load("widgets/windowAux.widget.js");
	  db_app::load("strings.js");
	  db_app::load("DBViewFiltroLancamentoAvaliacaoTurma.classe.js");
	  db_app::load("dbcomboBox.widget.js");
	  db_app::load("dbtextField.widget.js");
	  db_app::load("DBToogle.widget.js");
	  db_app::load("DBHint.widget.js");
	  db_app::load("DBGridMultiCabecalho.widget.js");
	  db_app::load("dbmessageBoard.widget.js");
	  db_app::load("DBTreeView.widget.js");
	  db_app::load("datagrid.widget.js");
	  db_app::load("webseller.js");
    db_app::load("DBFormCache.js");
    db_app::load("DBFormSelectCache.js");
	  db_app::load("estilos.css, grid.style.css, DBFormularios.css, DBViewLancamentoAvaliacao.css ");
	?>
  <script type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
	<style>
	</style>
</head>
<body bgcolor="#cccccc" style="margin-top: 25px" onload="" onbeforeunload='js_clearSession()'>
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <div id = 'divContainer'>
    <div id='ctnView' ></div>
  </div>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit")
       );
?>
<script>
oFiltro = new DBViewFiltroLancamentoAvaliacaoTurma('oViewLancamentoTurma', 'oFiltro');
oFiltro.show($('ctnView'));

js_clearSession =  function() {

  var oParam  = {};
  oParam.exec = "destroySession";
  var oAjax   = new Ajax.Request('edu4_lancamentoavaliacaonota.RPC.php',
                                 {
                                  asynchronous:true,
                                  method: "post",
                                  parameters:'json='+Object.toJSON(oParam)
                                });
  return true;
};

CurrentWindow.corpo.addEventListener('beforeunload', function(event) {
  js_clearSession();
});
</script>