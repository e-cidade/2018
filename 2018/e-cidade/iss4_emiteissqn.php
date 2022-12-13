<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
$clcriaabas = new cl_criaabas;
?>
<html>
 <head>
   <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta http-equiv="Expires" CONTENT="0">
   <?php

     db_app::load("scripts.js");
     db_app::load("prototype.js");
     db_app::load("datagrid.widget.js");
     db_app::load("strings.js");
     db_app::load("grid.style.css");
     db_app::load("estilos.css");
     db_app::load("classes/dbViewAvaliacoes.classe.js");
     db_app::load("widgets/windowAux.widget.js");
     db_app::load("widgets/dbmessageBoard.widget.js");
     db_app::load("dbcomboBox.widget.js");
     db_app::load("DBHint.widget.js");
   ?>
</head>
<body class="body-default abas">
<table>
  <tr>
     <td>
     <?php

       $clcriaabas->identifica = array("g1" => "Emissão ISSQN", "g2" => "Escritórios");
       $clcriaabas->title      = array("g1" => "Dados para emissão", "g2" => "Selecionar escritórios");
       $clcriaabas->src        = array("g1" => "iss4_emiteissqnaba001.php", "g2" => "iss4_emiteissqnaba002.php");
       $clcriaabas->cria_abas();
     ?>
     </td>
  </tr>
<tr>
</tr>
</table>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script type="text/javascript">
  document.formaba.g1.size     = 25;
  document.formaba.g2.size     = 25;
  document.formaba.g2.disabled = true;
</script>
</html>
