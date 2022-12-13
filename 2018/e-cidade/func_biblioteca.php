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

//MODULO: biblioteca
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_biblioteca_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbiblioteca = new cl_biblioteca;
$clbiblioteca->rotulo->label("bi17_codigo");
$clbiblioteca->rotulo->label("bi17_nome");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi17_codigo?>">
      <?=$Lbi17_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi17_codigo",10,$Ibi17_codigo,true,"text",4,"","chave_bi17_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi17_nome?>">
      <?=$Lbi17_nome?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi17_nome",100,$Ibi17_nome,true,"text",4,"","chave_bi17_nome");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_biblioteca.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $depto = db_getsession("DB_coddepto");
   $where = " bi17_coddepto = $depto AND";
   $where1 = " bi17_coddepto = $depto";
   if(!isset($pesquisa_chave)){
    if(isset($campos)==false){
     if(file_exists("funcoes/db_func_biblioteca.php")==true){
      include("funcoes/db_func_biblioteca.php");
     }else{
      $campos = "biblioteca.*";
     }
    }
    if(isset($chave_bi17_codigo) && (trim($chave_bi17_codigo)!="") ){
     $sql = $clbiblioteca->sql_query("",$campos,"bi17_nome",$where." bi17_codigo = $chave_bi17_codigo");
    }else if(isset($chave_bi17_nome) && (trim($chave_bi17_nome)!="") ){
     $sql = $clbiblioteca->sql_query("",$campos,"bi17_nome",$where." bi17_nome like '$chave_bi17_nome%' ");
    }else{
     $sql = $clbiblioteca->sql_query("",$campos,"bi17_codigo",$where1);
    }
    db_lovrot($sql,15,"()","",$funcao_js);
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","*","",$where." bi17_codigo = $pesquisa_chave"));
     if($clbiblioteca->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$bi17_nome',false);</script>";
     }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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