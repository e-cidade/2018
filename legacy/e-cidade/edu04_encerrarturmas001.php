<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

/**
 * Variaveis para configurar VIEW
 *
 * TIPO DE TURMA
 * $oGet->iTurmaNormal = 1
 *      Representa turmas do tipo:
 *         1 | NORMAL
 *         2 | EJA
 *         3 | MULTETAPA
 * $oGet->iTurmaNormal = 2
 *      Representa turmas do tipo: 2 | Progressao Parcial
 *
 * ENCERRA / CANCELA
 * $oGet->iEncerra = 1 (Encerrar)
 * $oGet->iEncerra = 2 (Cancelar)
 */

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?php
	  db_app::load("scripts.js");
	  db_app::load("arrays.js");
	  db_app::load("prototype.js");
	  db_app::load("datagrid.widget.js");
	  db_app::load("widgets/windowAux.widget.js");
	  db_app::load("widgets/DBToogle.widget.js");
	  db_app::load("widgets/DBHint.widget.js");
	  db_app::load("widgets/datagrid/plugins/DBHint.plugin.js");
	  db_app::load("strings.js");
	  db_app::load("DBViewEncerramentoAvaliacoesFiltro.classe.js");
	  db_app::load("DBViewConsultaAvaliacoesAluno.classe.js");
	  db_app::load("dbcomboBox.widget.js");
	  db_app::load("dbtextField.widget.js");
	  db_app::load("DBGridMultiCabecalho.widget.js");
	  db_app::load("dbmessageBoard.widget.js");
	  db_app::load("DBTreeView.widget.js");
	  db_app::load("webseller.js");
	  db_app::load("DBFormCache.js");
	  db_app::load("estilos.css, grid.style.css");
	?>
	<style>
	</style>
</head>
<body id="corpo" bgcolor="#cccccc" style="margin-top: 25px" onload="">
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<center>
  <div id='gridContainer'></div>
</center>
</body>
</html>
<?
  if (!isset($oGet->lDesabilitaMenu)) {
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  }
?>
<script type="text/javascript">

	var oGet = js_urlToObject();

	oEncerramento = new DBViewEncerramentoAvaliacoesFiltro("oEncerramento", oGet.iEncerra);

	if (oGet.iTurmaNormal == 2) {
	  oEncerramento.setProgressaoParcial(true);
	}

	/**
	 * Caso tenha sido setado iTurma, iEtapa e sTurma e nao estejam vazios, setamos os valores na View de Encerramento
	 */
	if (!js_empty(oGet.iTurma) && !js_empty(oGet.iEtapa) && !js_empty(oGet.sTurma)) {

		$('corpo').style.marginTop = '0px';
	  oEncerramento.setTurmaEtapa(oGet.iTurma, oGet.iEtapa, oGet.sTurma);
	}

	oEncerramento.show($('gridContainer'));
</script>