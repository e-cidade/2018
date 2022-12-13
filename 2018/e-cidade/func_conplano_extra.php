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
include("classes/db_conplano_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconplano = new cl_conplano;
$clconplano->rotulo->label("c60_codcon");
$clconplano->rotulo->label("c60_descr");
$clconplano->rotulo->label("c60_estrut");
$clrotulo = new rotulocampo;
$clrotulo->label("c61_reduz");
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
            <td width="4%" align="right" nowrap title="<?=$Tc60_codcon?>">
              <?=$Lc60_codcon?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c60_codcon",6,$Ic60_codcon,true,"text",4,"","chave_c60_codcon");
		       ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tc60_estrut?>">
              <?=$Lc60_estrut?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c60_estrut",15,$Ic60_estrut,true,"text",4,"","chave_c60_estrut");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc61_reduz?>">
              <?=$Lc61_reduz?>
	    </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c61_reduz",6,$Ic61_reduz,true,"text",4,"","chave_c61_reduz");
		       ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tc60_descr?>">
              <?=$Lc60_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c60_descr",50,$Ic60_descr,true,"text",4,"","chave_c60_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="4" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conplano.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_conplano.php")==true){
             include("funcoes/db_func_conplano.php");
           }else{
           $campos = "conplano.*";
           }
        }
        if(isset($chave_c60_codcon) && (trim($chave_c61_reduz)!="") ){
	         $sql = $clconplano->sql_query(null,null,$campos,"c60_codcon","c61_reduz=$chave_c61_reduz and c61_anousu=".db_getsession("DB_anousu"));
        }elseif(isset($chave_c60_codcon) && (trim($chave_c60_codcon)!="") ){
	         $sql = $clconplano->sql_query($chave_c60_codcon,db_getsession("DB_anousu"),$campos,"c60_codcon");
        }else if(isset($chave_c60_estrut) && (trim($chave_c60_estrut)!="") ){
	         $sql = $clconplano->sql_query("",null,$campos,"c60_codcon"," c60_estrut like '$chave_c60_estrut%' and c60_anousu=".db_getsession("DB_anousu"));
        }else if(isset($chave_c60_descr) && (trim($chave_c60_descr)!="") ){
	         $sql = $clconplano->sql_query("",null,$campos,"c60_descr"," c60_descr like '$chave_c60_descr%' and c60_anousu=".db_getsession("DB_anousu"));
        }else if( isset($tipo_sql) ){//zé... coloquei esta opcao para o formulario do tabrec
           $sql = $clconplano->sql_query_reduz("",$campos.",c61_reduz as db_c61_reduz,c60_estrut as db_c60_estrut","c60_estrut"," c60_codsis = 7 and c62_anousu=".db_getsession("DB_anousu"));
        }else{
           $sql = $clconplano->sql_query("",null,$campos,"c60_estrut","c60_anousu=".db_getsession("DB_anousu"));
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clconplano->sql_record($clconplano->sql_query($pesquisa_chave,db_getsession("DB_anousu")));
          if($clconplano->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c60_descr',false);</script>";
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
  </script>
  <?
}
?>