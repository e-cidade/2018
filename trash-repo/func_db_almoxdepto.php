<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_orcorgao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_depart   = new cl_db_depart;
$cldb_almoxdepto = new cl_db_almoxdepto;
$cldb_config   = new cl_db_config;
$clorcorgao = new cl_orcorgao;
$cldb_depart->rotulo->label("coddepto");
$cldb_depart->rotulo->label("descrdepto");
$cldb_config->rotulo->label("codigo");
$clorcorgao->rotulo->label("o40_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
<tr> 
<td height="63" align="center" valign="top">
<table width="35%" border="0" align="center" cellspacing="0">
<form name="form1" method="post" action="" >

<?
if (isset($todasinstit) and $todasinstit == 1) {
?>
<tr> 
<td width="4%" align="right" nowrap title="<?=$Tcodigo?>">
<?=$Lcodigo?>
</td>
<td width="96%" align="left" nowrap> 
<?
	if (!isset($instituicao)) {
		$instituicao = db_getsession("DB_instit");
	}
	$resultinstit = $cldb_config->sql_record($cldb_config->sql_query_usu(null,"codigo,nomeinst","prefeitura desc, nomeinst"," db_userinst.id_usuario = " . db_getsession("DB_id_usuario")));
	db_selectrecord('instituicao',$resultinstit,true,1,"", "", "", "0-Todos", "js_orgao()");
?>
</td>
</tr>
<?
}
?>

<tr>
<td width="4%" align="right" nowrap title="<?=$Tcoddepto?>">
<?=$Lcoddepto?>
</td>
<td width="96%" align="left" nowrap> 
<?
db_input("coddepto",5,$Icoddepto,true,"text",4,"","chave_coddepto");
?>
</td>
</tr>
<tr> 
<td width="4%" align="right" nowrap title="<?=$Tdescrdepto?>">
<?=$Ldescrdepto?>
</td>
<td width="96%" align="left" nowrap> 
<?
db_input("descrdepto",40,$Idescrdepto,true,"text",4,"","chave_descrdepto");
?>
</td>
</tr>

<tr>
<td width="4%" align="right" nowrap title="<?=$To40_descr?>">
<?=$Lo40_descr?>
</td>
</tr>

<td colspan="2" align="center"> 
<input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
<input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_depart.hide();">
</td>
</tr>
</form>
</table>
</td>
</tr>
<tr> 
<td align="center" valign="top"> 
<?



if (isset($todasinstit) and $todasinstit == 1) {
  if ($instituicao == 0) {
		$listainstit="";
		for ($x=0; $x < pg_num_rows($resultinstit); $x++) {
			db_fieldsmemory($resultinstit, $x);
			$listainstit .= $codigo . ($x == pg_num_rows($resultinstit) - 1?"":", ");
		}
	  $where_instit = " instit in ($listainstit) ";
	} else {
		$where_instit = " instit = $instituicao";
	}
} else {
	$where_instit = " instit = ".db_getsession("DB_instit");
}

if(!isset($pesquisa_chave)){
  if(isset($campos)==false){
    if(file_exists("funcoes/db_func_db_depart.php")==true){
//      include("funcoes/db_func_db_depart.php");
      $campos = "db_almoxdepto.m92_codalmox,
                 db_depart.coddepto,
                 db_depart.descrdepto,
                 db_depart.nomeresponsavel,
                 db_depart.emailresponsavel,
                 db_depart.limite,
                 db_depart.fonedepto,
                 db_depart.emaildepto,
                 db_depart.faxdepto,
                 db_depart.ramaldepto";
    }else{
      $campos = "db_almoxdepto.*,db_depart.*";
    }
  }
  
  $campos = "distinct ".$campos;
  
  $sql = $cldb_almoxdepto->sql_query_almox(null,null,$campos,"coddepto", "$where_instit");
  if(isset($chave_coddepto) && (trim($chave_coddepto)!="") ){
    $sql = $cldb_almoxdepto->sql_query_almox(null,null,$campos,"coddepto", " coddepto = $chave_coddepto and $where_instit");
  }else if(isset($chave_descrdepto) && (trim($chave_descrdepto)!="") ){
    $sql = $cldb_almoxdepto->sql_query_almox(null,null,$campos,"descrdepto"," descrdepto like '$chave_descrdepto%' and $where_instit");
  }
 // 	die($sql);
  db_lovrot($sql,15,"()","",$funcao_js, "", "NoMe", array(), true);
}else{
  if($pesquisa_chave!=null && $pesquisa_chave!=""){
    $result = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_almox(null,null,"*",null,"coddepto = $pesquisa_chave and $where_instit"));
    if($cldb_almoxdepto->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$descrdepto',false);</script>";
    }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.$sql.") não Encontrado',true);</script>";
    }
  }else{
    echo "<script>".$funcao_js."('',false);</script>";
  }
}
?>
</td>
</tr>
</table>
</body>
</html>
<script>
function js_orgao(){
  if (document.form1.orgao.value!="0"){
    document.form1.submit();
  }
}

function js_limpar(){
//document.form1.instituicao.value="";
document.form1.chave_coddepto.value="";
document.form1.chave_descrdepto.value="";
//document.form1.orgao.value="";	
	
}
</script>