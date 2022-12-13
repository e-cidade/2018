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

require("libs/db_stdlib.php");
require ("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_config_classe.php");
include("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");
require ("classes/db_db_configarquivos_classe.php");

db_postmemory($HTTP_POST_VARS);

$cldb_config = new cl_db_config;
$cldb_configarquivos = new cl_db_configarquivos;
$db_opcao = 1;
$db_botao = true;
$lErro = false;

if(isset($incluir)){

  $lErro = false;

  db_inicio_transacao();

  if ($prefeitura == "t") {

    $sSqlPrefeitura = $cldb_config->sql_query( null,
                                               "*",
                                               null,
                                               "prefeitura is true" );
    $rsPrefeitura = $cldb_config->sql_record($sSqlPrefeitura);

    if ($cldb_config->numrows > 0) {
      $lErro = true;
      $sMsg = "Já existe uma instituição cadastrada como Prefeitura.";
    }
  }


  if(!$lErro){

  	$cldb_config->db21_imgmarcadagua = 'null';
  	$cldb_config->db21_codtj = $db21_codtj;
    $cldb_config->incluir($codigo);

    $sMsg = $cldb_config->erro_msg;
    if($cldb_config->numrows_incluir == 0){
      $lErro = true;
    }

    $codigo = $cldb_config->codigo;

  }

  if(!$lErro){

    $cldb_configarquivos->db38_instit  = $cldb_config->codigo;
    $cldb_configarquivos->db38_tipo    = 1;
    $cldb_configarquivos->db38_arquivo = 'null';
    $cldb_configarquivos->incluir(null);
    if($cldb_configarquivos->erro_status == "0"){
      $lErro = true;
      $sMsg = $cldb_configarquivos->erro_msg;
    }

  }

  if(!$lErro){

    $cldb_configarquivos->db38_instit  = $cldb_config->codigo;
    $cldb_configarquivos->db38_tipo    = 2;
    $cldb_configarquivos->db38_arquivo = 'null';
    $cldb_configarquivos->incluir(null);
    if($cldb_configarquivos->erro_status == "0"){
      $lErro = true;
      $sMsg  = $cldb_configarquivos->erro_msg;
    }

  }
  db_fim_transacao($lErro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load('strings.js,scripts.js,datagrid.widget.js,prototype.js');
  db_app::load('estilos.css,grid.style.css');
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 15px;">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>

      <?
       include("forms/db_frmdb_config.php");
      ?>

    </center>
  </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","nomeinst",true,1,"nomeinst",true);
</script>
<?
if (isset($incluir)) {

	db_msgbox($sMsg);
  if ($cldb_config->erro_status=="0") {

    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldb_config->erro_campo!=""){
      echo "<script> document.form1.".$cldb_config->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_config->erro_campo.".focus();</script>";
    }
  } else {

    if (isset($codigo) && !empty($codigo)) {
      db_redireciona("con1_db_config005.php?liberaaba=true&chavepesquisa={$codigo}");
    }
  }
}
?>