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
include("classes/db_leitorcategoria_classe.php");
include("classes/db_biblioteca_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clleitorcategoria = new cl_leitorcategoria;
$clbiblioteca = new cl_biblioteca;
$clleitorcategoria->rotulo->label("bi07_codigo");
$clleitorcategoria->rotulo->label("bi07_nome");
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
     <td width="4%" align="right" nowrap title="<?=$Tbi07_codigo?>">
      <?=$Lbi07_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi07_codigo",10,$Ibi07_codigo,true,"text",4,"","chave_bi07_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi07_nome?>">
      <?=$Lbi07_nome?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi07_nome",50,$Ibi07_nome,true,"text",4,"","chave_bi07_nome");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_leitorcategoria.hide();">
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
   $result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo,bi17_nome",""," bi17_coddepto = $depto"));
   if($clbiblioteca->numrows!=0){
    db_fieldsmemory($result,0);
   }
   $where = " bi07_biblioteca = $bi17_codigo AND ";
   $where1 = " bi07_biblioteca = $bi17_codigo";
   if(!isset($pesquisa_chave)){
    $campos = "leitorcategoria.bi07_codigo,
               leitorcategoria.bi07_nome,
               leitorcategoria.bi07_tempo,
               leitorcategoria.bi07_qtdlivros,
               biblioteca.bi17_nome
              ";
    if(isset($chave_bi07_codigo) && (trim($chave_bi07_codigo)!="") ){
     $sql = $clleitorcategoria->sql_query("",$campos,"bi07_codigo",$where." bi07_codigo = $chave_bi07_codigo");
    }else if(isset($chave_bi07_nome) && (trim($chave_bi07_nome)!="") ){
     $sql = $clleitorcategoria->sql_query("",$campos,"bi07_nome",$where." bi07_nome like '$chave_bi07_nome%' ");
    }else{
     $sql = $clleitorcategoria->sql_query("",$campos,"bi07_codigo",$where1);
    }
    db_lovrot($sql,15,"()","",$funcao_js);
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $clleitorcategoria->sql_record($clleitorcategoria->sql_query("","*","",$where." bi07_codigo = $pesquisa_chave"));
     if($clleitorcategoria->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$bi07_nome',false);</script>";
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