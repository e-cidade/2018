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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($abas)){
  echo "<script>location.href='itb1_itbi005.php?db_opcao=3'</script>";
  exit;
}
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_itbi_classe.php"));
include(modification("classes/db_itburbano_classe.php"));
include(modification("classes/db_itbicgm_classe.php"));
include(modification("classes/db_itbinome_classe.php"));
include(modification("classes/db_itbiconstr_classe.php"));
include(modification("classes/db_itbiconstrespecie_classe.php"));
include(modification("classes/db_itbiconstrtipo_classe.php"));
include(modification("classes/db_itbirural_classe.php"));
include(modification("classes/db_itbiruralcaract_classe.php"));
include(modification("classes/db_itbilogin_classe.php"));
include(modification("classes/db_itbimatric_classe.php"));
include(modification("classes/db_itbipropriold_classe.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clitbi = new cl_itbi;
$clitbipropriold = new cl_itbipropriold;
$clitbicgm = new cl_itbicgm;
$clitburbano = new cl_itburbano;
$clitbinome = new cl_itbinome;
$clitbiconstr = new cl_itbiconstr;
$clitbiconstrespecie = new cl_itbiconstrespecie;
$clitbiconstrtipo = new cl_itbiconstrtipo;
$clitbirural = new cl_itbirural;
$clitbiruralcaract = new cl_itbiruralcaract;
$clitbilogin = new cl_itbilogin;
$clitbimatric = new cl_itbimatric;
$db_botao = false;
$db_opcao = 33;
global $tipo;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){

  db_inicio_transacao();

  $db_opcao = 3;
  $clitburbano->excluir($it01_guia);
  //$clitburbano->erro(true,false);
  $clitbirural->excluir($it01_guia);
  //$clitbirural->erro(true,false);
  $clitbiruralcaract->excluir($it01_guia);
  //$clitbiruralcaract->erro(true,false);
  $clitbicgm->excluir($it01_guia);
  //$clitbicgm->erro(true,false);
  $clitbinome->excluir($it01_guia);
  //$clitbinome->erro(true,false);

  $result = $clitbiconstr->sql_record($clitbiconstr->sql_query("","*",""," it08_guia = $it01_guia"));
  if($clitbiconstr->numrows > 0){
    $num = $clitbiconstr->numrows;
    for($i = 0;$i<$num;$i++){
      db_fieldsmemory($result,$i);
      $clitbiconstrespecie->excluir($it08_codigo);
      //$clitbiconstrespecie->erro(true,false);
      $clitbiconstrtipo->excluir($it08_codigo);
      //$clitbiconstrtipo->erro(true,false);
      $clitbiconstr->excluir($it08_codigo);
      //$clitbiconstr->erro(true,false);
    }
  }

  $clitbilogin->excluir($it01_guia);
  $clitbimatric->excluir($it01_guia);
  $clitbipropriold->excluir($it01_guia);
  $clitbi->excluir($it01_guia);

  db_fim_transacao();


}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clitbi->sql_record($clitbi->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $result = $clitburbano->sql_record($clitburbano->sql_query($chavepesquisa));
   if($clitburbano->numrows > 0){
     db_fieldsmemory($result,0);
     $tipo = "urbano";
   }
   $result = $clitbirural->sql_record($clitbirural->sql_query($chavepesquisa));
   if($clitbirural->numrows > 0){
     db_fieldsmemory($result,0);
     $tipo = "rural";
   }
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include(modification("forms/db_frmitbi.php"));
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clitbi->erro_status=="0"){
    $clitbi->erro(true,false);
    echo "<script>
            parent.iframe_itbi.location.href = 'itb1_itbi003.php?chavepesquisa=".$it01_guia."&abas=1';
            parent.document.formaba.comp.disabled = true;
            parent.document.formaba.compnome.disabled = true;
            parent.document.formaba.inter.disabled = true;
            parent.document.formaba.old.disabled = true;
          </script>";
  }else{
    $clitbi->erro(true,false);
    echo "<script>
            parent.iframe_itbi.location.href = 'itb1_itbi003.php?abas=1';
            parent.document.formaba.comp.disabled = true;
            parent.document.formaba.compnome.disabled = true;
            parent.document.formaba.inter.disabled = true;
            parent.document.formaba.old.disabled = true;
          </script>";
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>