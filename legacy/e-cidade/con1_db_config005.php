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

require_once "libs/db_stdlib.php";
require_once "libs/db_utils.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "classes/db_db_config_classe.php";
require_once "dbforms/db_funcoes.php";
require_once "libs/db_app.utils.php";
require_once "classes/db_db_configarquivos_classe.php";

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cldb_config = new cl_db_config;
$cldb_configarquivos = new cl_db_configarquivos;

$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){

  $lErro = false;

  db_inicio_transacao();
  $db_opcao = 2;

  if($prefeitura == "t"){

    $rsPrefeitura = $cldb_config->sql_record($cldb_config->sql_query(null,"*",null," prefeitura is true and codigo = $codigo"));

    if($cldb_config->numrows <= 0){

      $rsPrefeitura = $cldb_config->sql_record($cldb_config->sql_query(null,"*",null," prefeitura is true "));

      if($cldb_config->numrows > 0){

        $lErro = true;
        $cldb_config->erro_msg = "Já existe uma instituição cadastrada como Prefeitura.";
        $cldb_config->erro_status = "0";

        $sMsg = $cldb_config->erro_msg;
      }

    }

  }

  if (!$lErro) {

  	$sWhere           = "codigo = {$codigo}";
  	$sSqlDbConfig     = $cldb_config->sql_query_file(null, "logo, figura", "codigo", $sWhere);
  	$rsSqlDbConfig    = $cldb_config->sql_record($sSqlDbConfig);
  	$iNumRowsDbConfig = $cldb_config->numrows;
  	if ($iNumRowsDbConfig > 0) {

	    $oDbConfig = db_utils::fieldsMemory($rsSqlDbConfig, 0);
	    if (empty($oDbConfig->logo) || empty($oDbConfig->figura)) {

	    	$lErro = true;
	    	$sMsg  = 'Favor incluir imagens de logo e figura! Verificar na aba imagens.';
	    }
  	}

  	if (!$lErro) {

		  $cldb_config->db21_codtj = $db21_codtj;
  		$cldb_config->alterar($codigo);
		  $sMsg = $cldb_config->erro_msg;
		  if ($cldb_config->erro_status == "0") {
		    $lErro = true;
		  }
  	}
  }

  db_fim_transacao($lErro);

} else if(isset($chavepesquisa)) {

   $db_opcao = 2;
   $db_botao = true;
   $result   = $cldb_config->sql_record($cldb_config->sql_query($chavepesquisa));

   db_fieldsmemory($result, 0);
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

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
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
<?
if(isset($alterar)){

	db_msgbox($sMsg);
  if ($cldb_config->erro_status == "0") {

    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldb_config->erro_campo!=""){
      echo "<script> document.form1.".$cldb_config->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_config->erro_campo.".focus();</script>";
    }
  }
}

if (isset($chavepesquisa)) {

	if (empty($logo) || empty( $figura)) {
	  db_msgbox("Favor cadastrar as imagens de logo e figura na aba de imagens!");
	}

  echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.instituicao.disabled=false;
         parent.document.formaba.imagens.disabled=false;
         top.corpo.iframe_imagens.location.href='con1_db_imagens001.php?codigo={$chavepesquisa}';
     ";

  if (isset($liberaaba)) {
    echo "  parent.mo_camada('imagens');";
  }

  echo"}\n
    js_db_libera();
  </script>\n
 ";
}

if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","nomeinst",true,1,"nomeinst",true);
</script>