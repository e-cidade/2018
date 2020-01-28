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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_liborcamento.php");
require_once ("classes/db_orcdotacao_classe.php");
require_once ("classes/db_empempenho_classe.php");
require_once ("classes/db_empanulado_classe.php");
require_once ("classes/db_empanuladoele_classe.php");
require_once ("classes/db_empelemento_classe.php");
require_once ("classes/db_empautoriza_classe.php");
require_once ("classes/db_pcprocitem_classe.php");
require_once ("classes/db_orcreservaaut_classe.php");
require_once ("classes/db_orcreserva_classe.php");
require_once ("classes/db_orcreservasol_classe.php");
require_once ("classes/db_empparametro_classe.php");
require_once ("classes/db_empanuladotipo_classe.php");

$clempempenho    = new cl_empempenho;
$clempanulado    = new cl_empanulado;
$clempanuladoele = new cl_empanuladoele;
$clempelemento   = new cl_empelemento;
$clorcdotacao    = new cl_orcdotacao;
$clempautoriza   = new cl_empautoriza;
$clempparametro  = new cl_empparametro;
$db_opcao        = 22;
if(isset($numemp)){

  $db_opcao = 1;
  $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>
.saldocentavos {background-color:#d1f07c;}
</style>
</head>
<body style="margin-top: 20px;" >
  <div class="container">
	<?php
  require_once(Modification::getFile("forms/db_frmempanularempenho.php"));
  ?>
  </div>
</body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
if ($db_opcao == 22) {
	echo "<script>document.form1.pesquisar.click();</script>";
} else {
  echo "<script>js_consultaEmpenho({$numemp});</script>";
}
?>