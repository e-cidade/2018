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
include("classes/db_conlancamsup_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconlancamsup = new cl_conlancamsup;
$clconlancamsup->rotulo->label("c79_codlan");
$clconlancamsup->rotulo->label("c79_codsup");
//-- rotulo para o campo o46_codlei
//$clconlancamsup->rotulo->label("o46_codlei");
$clrotulo = new rotulocampo;
$clrotulo->label("o46_codlei");
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
	  <!---   
          <tr> 
             <td width="4%" align="right" nowrap title="<?=$Tc79_codlan?>"><?=$Lc79_codlan?></td>
             <td width="96%" align="left" nowrap><? db_input("c79_codlan",8,$Ic79_codlan,true,"text",4,"","chave_c79_codlan"); ?> </td>
          </tr>
	  --->
          <tr> 
             <td width="4%" align="right" nowrap title="<?=$Tc79_codsup?>"><?=$Lc79_codsup?></td>
             <td width="96%" align="left" nowrap><? db_input("c79_codsup",10,$Ic79_codsup,true,"text",4,"","chave_c79_codsup"); ?> </td>
          </tr>
          <tr> 
             <td width="4%" align="right" nowrap title="<?=$To46_codlei_codsup?>"><?=$Lo46_codlei?></td>
             <td width="96%" align="left" nowrap><? db_input("o46_codlei",10,$Io46_codlei,true,"text",4,"","chave_o46_codlei"); ?></td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conlancamsup.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $campos="distinct c79_codsup,o46_data,o46_codlei,o45_numlei,o45_descr,o46_tiposup,o48_descr";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_conlancamsup.php")==true){
             include("funcoes/db_func_conlancamsup.php");
           }else{
           $campos = "conlancamsup.*";
           }
        }
        if(isset($chave_c79_codlan) && (trim($chave_c79_codlan)!="") ){
	         $sql = $clconlancamsup->sql_query($chave_c79_codlan,$chave_c79_codsup,$campos,"c79_codlan");
        }else if(isset($chave_c79_codsup) && (trim($chave_c79_codsup)!="") ){
	         $sql = $clconlancamsup->sql_query("",$campos,"c79_codsup"," c79_codsup like '$chave_c79_codsup%' ");
        }else if(isset($chave_o46_codlei) && (trim($chave_o46_codlei)!="") ){
	         $sql = $clconlancamsup->sql_query("",$campos,"c79_codsup"," o46_codlei like '$chave_o46_codlei%' ");
        }else{
           $sql = $clconlancamsup->sql_query("",$campos);
        }
        // echo ($sql);
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clconlancamsup->sql_record($clconlancamsup->sql_query($pesquisa_chave));
          if($clconlancamsup->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c79_codsup',false);</script>";
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