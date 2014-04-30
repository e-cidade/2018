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
include("classes/db_marca_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmarca = new cl_marca;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
 db_inicio_transacao();
 $db_opcao = 2;
 $ma01_c_nomeimagem = @$GLOBALS["HTTP_POST_VARS"]["ma01_o_imagem"];
 $ma01_o_imagem = "tmp/".@$GLOBALS["HTTP_POST_VARS"]["ma01_o_imagem"];
 if($ma01_c_nomeimagem!=""){
  $oid_imagem = pg_loimport($ma01_o_imagem) or die("Erro(15) importando imagem");
  $ma01_o_imagem = $oid_imagem;
 }else{
  $oid_imagem = "0";
 }
 $clmarca->ma01_c_nomeimagem = $ma01_c_nomeimagem;
 $clmarca->ma01_o_imagem = $oid_imagem;
 $clmarca->alterar($ma01_i_codigo);
 db_fim_transacao();
 $db_botao = true;
}else if(isset($chavepesquisa)){
 $db_opcao = 2;
 $result = $clmarca->sql_record($clmarca->sql_query($chavepesquisa));
 db_fieldsmemory($result,0);
 $db_botao = true;?>
 <script>parent.document.formaba.local.disabled = true;</script><?
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
  <center>
   <?
   include("forms/db_frmmarca.php");
   ?>
  </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
 if($clmarca->erro_status=="0"){
  $clmarca->erro(true,false);
  echo "<script> document.form1.db_opcao.disabled=false;</script>";
  if($clmarca->erro_campo!=""){
    echo "<script> document.form1.".$clmarca->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clmarca->erro_campo.".focus();</script>";
  };
 }else{
  $clmarca->erro(true,false);?>
  <script>
   parent.iframe_local.location="mar1_marcaloc002.php?ma05_i_marca=<?=$ma01_i_codigo?>&z01_nome=<?=$z01_nome?>";
   parent.mo_camada('local');
   parent.document.formaba.local.disabled = false;
   parent.document.formaba.marca2.disabled = false;
  </script>
 <?
 };
};
if($db_opcao==22){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>