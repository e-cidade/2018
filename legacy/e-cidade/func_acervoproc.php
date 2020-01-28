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
include("classes/db_acervo_classe.php");
include("classes/db_biblioteca_classe.php");
include("classes/db_autor_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clacervo      = new cl_acervo;
$clautor       = new cl_autor;
$clbiblioteca  = new cl_biblioteca;
$clacervo->rotulo->label("bi06_titulo");
$clacervo->rotulo->label("bi06_codbarras");
$clautor->rotulo->label("bi01_nome");
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
  <td height="63" valign="top">
   <table width="35%" border="0" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi06_codbarras?>">
      <?=$Lbi06_codbarras?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi06_codbarras",30,$Ibi06_codbarras,true,"text",4,"","chave_bi06_codbarras");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi06_titulo?>">
      <?=$Lbi06_titulo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi06_titulo",50,$Ibi06_titulo,true,"text",4,"","chave_bi06_titulo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tbi01_nome?>">
      <b>Nome do Autor:</b>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("bi01_nome",50,$Ibi01_nome,true,"text",4,"","chave_bi01_nome");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_acervo.hide();">
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
   $where = " bi06_emprestar = 't' AND bi06_biblioteca = $bi17_codigo AND";
   $where1 = " bi06_emprestar = 't' AND bi06_biblioteca = $bi17_codigo";
   if(isset($campos)==false){
    if(file_exists("funcoes/db_func_acervo.php")==true){
     include("funcoes/db_func_acervo.php");
    }else{
     $campos = "acervo.*";
    }
   }
   if(!isset($pesquisa_chave) && !isset($pesquisa_chave2) && !isset($pesquisa_chave3) ){
    if(isset($chave_bi06_codbarras) && (trim($chave_bi06_codbarras)!="") ){
     $sql = $clacervo->sql_query("",$campos,"bi06_codbarras",$where." bi06_codbarras = $chave_bi06_codbarras");
    }else if(isset($chave_bi06_titulo) && (trim($chave_bi06_titulo)!="") ){
     $sql = $clacervo->sql_query("",$campos,"bi06_titulo",$where." bi06_titulo like '$chave_bi06_titulo%' ");
    }else if(isset($chave_bi01_nome) && (trim($chave_bi01_nome)!="") ){
     $sql = "select $campos from autoracervo
               inner join acervo on bi06_seq = bi21_acervo
               inner join autor on bi01_codigo = bi21_autor
              where $where bi01_nome like '$chave_bi01_nome%'
             ";
    }else{
     $sql = $clacervo->sql_query("",$campos,"bi06_codbarras",$where1);
    }
    db_lovrot($sql,15,"()","",$funcao_js);
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $clacervo->sql_record($clacervo->sql_query("",$campos,"",$where." bi06_codbarras = $pesquisa_chave"));
     if($clacervo->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$bi06_titulo',false);</script>";
     }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
     }
    }elseif($pesquisa_chave2!=null && $pesquisa_chave2!=""){
     $result = $clacervo->sql_record($clacervo->sql_query("",$campos,"",$where." bi06_codbarras = $pesquisa_chave2"));
     if($clacervo->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$bi06_titulo',$bi06_seq,false);</script>";
     }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave2.") não Encontrado',true);</script>";
     }
    }elseif($pesquisa_chave3!=null && $pesquisa_chave3!=""){
     $result = $clacervo->sql_record($clacervo->sql_query("",$campos,"",$where." bi06_seq = $pesquisa_chave3"));
     if($clacervo->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$bi06_titulo',false);</script>";
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