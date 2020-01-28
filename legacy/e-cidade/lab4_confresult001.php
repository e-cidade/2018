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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory($_POST);

$cllab_conferencia = new cl_lab_conferencia;
$cllab_requiitem   = new cl_lab_requiitem;
$oDaoResultado     = new cl_lab_resultado();

$db_opcao = 1;
$db_botao = true;

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link rel='stylesheet' type='text/css' href='estilos.css'  />
  <link rel='stylesheet' type='text/css' href='estilos/grid.style.css' />
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
  <script language='JavaScript' type='text/javascript' src='scripts/strings.js'></script>
  <script language='JavaScript' type='text/javascript' src='scripts/prototype.js'></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language='JavaScript' type='text/javascript' src='scripts/datagrid.widget.js'></script>
  <script language='JavaScript' type='text/javascript' src='scripts/widgets/DBHint.widget.js'> </script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <script language='JavaScript' type='text/javascript' src='scripts/classes/laboratorio/forms/Laboratorio.classe.js'></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/laboratorio/LancarMedicamentoExame.classe.js"></script>
  <script language='JavaScript' type='text/javascript' src='scripts/classes/laboratorio/LancamentoExameLaboratorio.classe.js'></script>

</head>
<body >

<?php
  require_once(modification("forms/db_frmlab_conferencia.php"));
  db_menu();
?>
</body>
</html>