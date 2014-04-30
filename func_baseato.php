<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_atolegal_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatolegal = new cl_atolegal;
$clatolegal->rotulo->label("ed05_i_codigo");
$clatolegal->rotulo->label("ed05_c_numero");
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
     <td width="4%" align="right" nowrap title="<?=$Ted05_i_codigo?>">
      <?=$Led05_i_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed05_i_codigo",10,$Ied05_i_codigo,true,"text",4,"","chave_ed05_i_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted05_c_numero?>">
      <?=$Led05_c_numero?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed05_i_codigo",10,$Ied05_c_numero,true,"text",4,"","chave_ed05_c_numero");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_atolegal.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $escola = db_getsession("DB_coddepto");
   $restricao  = " AND not exists (select * from baseato where ed278_i_atolegal = ed05_i_codigo AND ed278_i_escolabase = $codbaseescola)";
   $restricao .= " AND ed05_i_codigo IN (select ed215_i_atolegal from cursoedu ";
   $restricao .= "                               inner join cursoescola on cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo ";
   $restricao .= "                               inner join cursoato on cursoato.ed215_i_cursoescola = cursoescola.ed71_i_codigo ";
   $restricao .= "                         where ed29_i_codigo = $codcursobase) ";
   if(!isset($pesquisa_chave)){
    if(isset($campos)==false){
     if(file_exists("funcoes/db_func_atolegal.php")==true){
      include("funcoes/db_func_atolegal.php");
     }else{
      $campos = "atolegal.*";
     }
    }
    if(isset($chave_ed05_i_codigo) && (trim($chave_ed05_i_codigo)!="") ){
      $sql = $clatolegal->sql_query("",$campos,"ed05_i_codigo"," ed05_i_codigo = $chave_ed05_i_codigo AND ed19_i_escola = $escola $restricao");
    }else if(isset($chave_ed05_c_numero) && (trim($chave_ed05_c_numero)!="") ){
      $sql = $clatolegal->sql_query("",$campos,"ed05_c_numero"," ed05_c_numero = '$chave_ed05_c_numero' AND ed19_i_escola = $escola $restricao");
    }else{
      $sql = $clatolegal->sql_query("",$campos,"ed05_i_codigo"," ed19_i_escola = $escola $restricao");
    }
    db_lovrot($sql,15,"()","",$funcao_js);
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $clatolegal->sql_record($clatolegal->sql_query("","*",""," ed05_i_codigo = '$pesquisa_chave' AND ed19_i_escola = $escola $restricao"));
     if($clatolegal->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$ed05_c_finalidade',false);</script>";
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