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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_ativid_classe.php"));
require_once(modification("classes/db_ativtipo_classe.php"));
require_once(modification("classes/db_clasativ_classe.php"));
require_once(modification("classes/db_classe_classe.php"));
require_once(modification("classes/db_cnae_classe.php"));
require_once(modification("classes/db_cnaeanalitica_classe.php"));
require_once(modification("classes/db_rhcbo_classe.php"));
require_once(modification("classes/db_atividcnae_classe.php"));
require_once(modification("classes/db_atividcbo_classe.php"));
require_once(modification("classes/db_db_estruturavalor_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);

$cl_clasativ     = new cl_clasativ;
$cl_classe       = new cl_classe;
$clativid        = new cl_ativid;
$clativtipo      = new cl_ativtipo;
$clcnae          = new cl_cnae;
$clcnaeanalitica = new cl_cnaeanalitica;
$clrhcbo         = new cl_rhcbo;
$clatividcnae    = new cl_atividcnae;
$clatividcbo     = new cl_atividcbo;
$clrotulo        = new rotulocampo;
$clDbEstrutValor = new cl_db_estruturavalor;

$clrotulo->label("q80_tipcal");
$clrotulo->label("q81_descr");
$hiddenPJ        = 'hidden';
$hiddenPF        = 'hidden';
$positionPJ      = 'relative';
$positionPF      = 'relative';
$db_opcaoselect  = 1;
$db_opcao        = 1;
$db_opcaoc       = 1;
$db_botao        = false;
$sAcao           = "Inclusão";

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script>
function js_valida() {

	 classe = document.getElementById('q12_classe');
	 if (classe == '') {

		   alert('Campo Código da Classe deve ser preenchido!');
			 classe.focus();
			 return false;
	 }
}
</script>
</head>
<body class="body-default">
	<?php include(modification("forms/db_frmativid.php")); ?>
</body>
</html>
<?
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir") {
  if ($sqlerro==true) {

    $clativid->erro(true,false);
    echo "<script>js_pessoa(); </script>";
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clativid->erro_campo!=""){
      echo "<script> document.form1.".$clativid->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clativid->erro_campo.".focus();</script>";
    }
  } else {
    $clativid->erro(true,true);
  }
}
?>