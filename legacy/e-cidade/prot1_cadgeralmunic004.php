<?php
/*
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_cgmtipoempresa_classe.php");
require_once("classes/db_tipoempresa_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$db_opcao = 1;
$db_botao = true;

$clcgm            = new cl_cgm;
$clcgm->rotulo->label();
$clcgmtipoempresa = new cl_cgmtipoempresa;
$clcgmtipoempresa->rotulo->label();
$cltipoempresa    = new cl_tipoempresa;
$cltipoempresa->rotulo->label();

//testa para saber se é pessoa física ou jurídica
//seta variável para exibir parte pertiente a cada tipo no formulário
if (isset($oPost->cpf) && trim($oPost->cpf) != "") {
	$lPessoaFisica = true;
} else {
	$lPessoaFisica = false;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("strings.js");
  db_app::load("widgets/dbtextField.widget.js");
  db_app::load("dbViewCadEndereco.classe.js");
  db_app::load("dbmessageBoard.widget.js");
  db_app::load("dbautocomplete.widget.js");
  db_app::load("dbcomboBox.widget.js");
  db_app::load("datagrid.widget.js");
  db_app::load("estilos.css,grid.style.css");
?>

<script type="text/javascript">
function js_findCidadao() {
  return false;
}
</script>
</head>
<body class="body-default" onLoad=" js_findCidadao(<?=@$oPost->ov02_sequencial?>);" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center" valign="top" bgcolor="#CCCCCC">
    <center>
      <?

			if ((isset($oPost->cpf) && $oPost->cpf != '') || (isset($oPost->cnpj) && $oPost->cnpj != '')) {
        require_once("forms/db_frmcadgeralmunic.php");
      }else {

        include("forms/db_frmcadgeralmunicini.php");
			}
			?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>