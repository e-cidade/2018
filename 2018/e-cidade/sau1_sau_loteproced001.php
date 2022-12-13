<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_jsplibwebseller.php");
include("libs/db_utils.php");
require("libs/db_app.utils.php");

include("classes/db_sau_lote_classe.php");
include("classes/db_sau_lotepront_classe.php");
include("classes/db_sau_lotepront_ext_classe.php");
include("classes/db_prontuarios_classe.php");
include("classes/db_prontproced_classe.php");
include("classes/db_prontproced_ext_classe.php");
include("classes/db_tmp_prontproced_classe.php");
include("classes/db_cgs_und_classe.php");

include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clsau_lote        = new cl_sau_lote;
$clsau_lotepront   = new cl_sau_lotepront_ext;
$clprontuarios     = new cl_prontuarios;
$clprontproced     = new cl_prontproced_ext;
$cltmp_prontproced = new cl_tmp_prontproced;
$clcgs_und         = new cl_cgs_und;
$clrotulo          = new rotulocampo;

$clprontproced->rotulo->label();
$clrotulo->label("z01_i_cgsund");

$db_opcao     = 1;
$db_botao     = true;
$db_botao1    = false;
$db_processar = isset($db_processar)?$db_processar:false;

//$sd29_d_data_dia = date("d",db_getsession("DB_datausu"));
//$sd29_d_data_mes = date("m",db_getsession("DB_datausu"));
//$sd29_d_data_ano = date("Y",db_getsession("DB_datausu"));
//$sd29_c_hora     = date("H").":".date("m");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
try{
	db_app::load("scripts.js");
	db_app::load("prototype.js");
	db_app::load("datagrid.widget.js");
	db_app::load("strings.js");
	db_app::load("webseller.js");
	db_app::load("grid.style.css");
	db_app::load("estilos.css");
}catch (Exception $eException){
	die( $eException->getMessage() );
}
?></head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmsau_loteproced001.php");
        ?>
    </center>
    </td>
  </tr>
</table>
<center>
</body>
</html>
<script>
	js_tabulacaoforms("form1","z01_nome",true,1,"z01_nome",true);
	document.form1.sd24_i_unidade.value = parent.iframe_a1.document.form1.sd24_i_unidade.value;
	document.form1.sd58_i_codigo.value  = parent.iframe_a1.document.form1.sd58_i_codigo.value;
	document.form1.login.value          = parent.iframe_a1.document.form1.login.value;
</script>