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
include("classes/db_db_contatos_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_contatos = new cl_db_contatos;
$cldb_contatos->rotulo->label("g01_id");
$cldb_contatos->rotulo->label("g01_organizacao");
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
            <td width="4%" align="right" nowrap title="<?=$Tg01_id?>">
              <?=$Lg01_id?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("g01_id",0,$Ig01_id,true,"text",4,"","chave_g01_id");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tg01_organizacao?>">
              <?=$Lg01_organizacao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("g01_organizacao",50,$Ig01_organizacao,true,"text",4,"","chave_g01_organizacao");
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
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "db_contatostipo.g02_descr,g01_cidade,g01_organizacao,g01_nome,g01_telef,g01_celular,g01_fax, g01_email,g01_site,g01_rua,g01_bairro";
        }
        if(isset($chave_g01_id) && (trim($chave_g01_id)!="") ){
	         $sql = $cldb_contatos->sql_query($chave_g01_id,$campos,"g01_id");
        }else if(isset($chave_g01_organizacao) && (trim($chave_g01_organizacao)!="") ){
	         $sql = $cldb_contatos->sql_query("",$campos,"g01_organizacao"," g01_organizacao like '$chave_g01_organizacao%' ");
        }else{
           $sql = $cldb_contatos->sql_query("",$campos,"g01_id","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_contatos->sql_record($cldb_contatos->sql_query($pesquisa_chave));
          if($cldb_contatos->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$g01_organizacao',false);</script>";
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
document.form2.chave_g01_id.focus();
document.form2.chave_g01_id.select();
  </script>
  <?
}
?>