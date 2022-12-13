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
include("classes/db_db_usuarios_classe.php");
include("classes/db_db_depusu_classe.php");
include("classes/db_cadfiscais_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_usuarios = new cl_db_usuarios;
$cldb_depusu   = new cl_db_depusu;
$clcadfiscais  = new cl_cadfiscais;
$cldb_usuarios->rotulo->label("id_usuario");
$cldb_usuarios->rotulo->label("nome");
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
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tid_usuario?>">
              <?=$Lid_usuario?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("id_usuario",5,$Iid_usuario,true,"text",4,"","chave_id_usuario");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tnome?>">
              <?=$Lnome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("nome",20,$Inome,true,"text",4,"","chave_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $result = $cldb_depusu->sql_record($cldb_depusu->sql_query("",db_getsession("DB_coddepto"),"*"));
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "db_usuarios.id_usuario,db_usuarios.nome,db_usuarios.login,db_usuarios.usuarioativo,db_usuarios.email";
        }
        if(isset($chave_id_usuario) && (trim($chave_id_usuario)!="") ){
          $sql =   $cldb_depusu->sql_query($chave_id_usuario,db_getsession("DB_coddepto"),$campos);
        }else if(isset($chave_nome) && (trim($chave_nome)!="") ){
          $sql =   $cldb_depusu->sql_query("",db_getsession("DB_coddepto"),$campos,"nome"," nome like '$chave_nome%' ");
        }else{
           $sql = $cldb_depusu->sql_query("",db_getsession("DB_coddepto"),$campos);
        }
        if(isset($fiscal)){//se vier setada esta variavel via query string o resultado soh trara os usuarios que estejam cadastrados na tabela cadfiscais
	  $sql = $clcadfiscais->sql_query_depto("",$campos,""," db_depusu.coddepto = ".db_getsession("DB_coddepto")."");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          if(isset($fiscal)){//se vier setada esta variavel via query string o resultado soh trara os usuarios que estejam cadastrados na tabela cadfiscais
	    $result = $clcadfiscais->sql_record($clcadfiscais->sql_query_depto("","*",""," db_depusu.coddepto = ".db_getsession("DB_coddepto")." and cadfiscais.id_usuario = $pesquisa_chave"));
	    $cldb_depusu->numrows = $clcadfiscais->numrows;
          }else{
            $result = $cldb_depusu->sql_record($cldb_depusu->sql_query($pesquisa_chave,db_getsession("DB_coddepto")));
	  }
          if($cldb_depusu->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$nome',false);</script>";
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
document.form2.chave_id_usuario.focus();
document.form2.chave_id_usuario.select();
  </script>
  <?
}
?>