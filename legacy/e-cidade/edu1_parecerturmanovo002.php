<?
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

require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" type="text/css" href="estilos.css" />
  <link rel="stylesheet" type="text/css" href="estilos/dbtreeview.style.css" />
  <?php
    db_app::load( "scripts.js" );
    db_app::load( "prototype.js" );
    db_app::load( "strings.js" );
    db_app::load( "json2.js" );
    db_app::load( "widgets/DBTreeView.widget.js" );
    db_app::load( "classes/DBViewArvoreTurma.classe.js ");
    db_app::load( "classes/educacao/DBViewParecerTurma.classe.js ");
  ?>
</head>
<body bgcolor="#cccccc">
  <div id="divPrincipal" style="padding-left: 30%;">
  </div>
</body>
</html>
<script>
var oGet          = js_urlToObject();
var oParecerTurma = new DBViewParecerTurma( oGet.codigoparecer, oGet.listadisciplinas );
    oParecerTurma.show( $('divPrincipal') );
</script>