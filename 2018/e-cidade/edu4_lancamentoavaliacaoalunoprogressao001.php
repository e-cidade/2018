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
require_once(modification("libs/db_stdlibwebseller.php"));

?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?php
	  db_app::load("estilos.css, grid.style.css, DBViewLancamentoAvaliacao.css");
	  /* PLUGIN DIARIOPROGRESSAOPARCIAL - A linha abaixo é utilizada, NÃO APAGAR ou ALTERAR*/
	?>
	<style>
	</style>
	<script type="text/javascript" src='scripts/scripts.js' ></script>
  <script type="text/javascript" src='scripts/prototype.js' ></script>
  <script type="text/javascript" src='scripts/widgets/windowAux.widget.js' ></script>
  <script type="text/javascript" src='scripts/strings.js' ></script>
  <script type="text/javascript" src='scripts/arrays.js' ></script>
  <script type="text/javascript" src='scripts/AjaxRequest.js' ></script>
  <script type="text/javascript" src='scripts/classes/DBViewFiltroLancamentoAvaliacao.classe.js'> </script>
  <script type="text/javascript" src='scripts/classes/DBViewLancamentoProgressaoParcialAluno.classe.js'> </script>
  <script type="text/javascript" src='scripts/classes/DBViewLancamentoAvaliacaoParecer.classe.js'> </script>
  <script type="text/javascript" src='scripts/classes/DBViewLancamentoParecerDisciplina.classe.js'> </script>
  <script type="text/javascript" src='scripts/widgets/dbcomboBox.widget.js' ></script>
  <script type="text/javascript" src='scripts/widgets/dbtextField.widget.js' ></script>
  <script type="text/javascript" src='scripts/widgets/dbmessageBoard.widget.js' ></script>
  <script type="text/javascript" src='scripts/widgets/DBTreeView.widget.js' ></script>
  <script type="text/javascript" src='scripts/datagrid.widget.js' ></script>
  <script type="text/javascript" src='scripts/widgets/datagrid/plugins/DBHint.plugin.js' ></script>
  <script type="text/javascript" src='scripts/widgets/DBGridMultiCabecalho.widget.js' ></script>
  <script type="text/javascript" src='scripts/webseller.js' ></script>

	<!-- PLUGIN DIARIOPROGRESSAOPARCIAL - A linha abaixo é utilizada, NÃO APAGAR ou ALTERAR -->

</head>
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<center>
  <div id='gridContainer'></div>
</center>
</body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script type="text/javascript">

	oAvaliacao = new DBViewFiltroLancamentoAvaliacao("oAvaliacao");
	oAvaliacao.setProgressaoParcial(true);
	oAvaliacao.show($('gridContainer'));

</script>