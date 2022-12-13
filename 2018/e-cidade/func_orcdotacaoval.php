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
include("classes/db_orcdotacaoval_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcdotacaoval = new cl_orcdotacaoval;
$clorcdotacaoval->rotulo->label("o59_anousu");
$clorcdotacaoval->rotulo->label("o59_coddot");
$clorcdotacaoval->rotulo->label("o59_mes");
$clorcdotacaoval->rotulo->label("o59_coddoc");
$clorcdotacaoval->rotulo->label("o59_valor");
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
            <td width="4%" align="right" nowrap title="<?=$To59_coddot?>">
              <?=$Lo59_coddot?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o59_coddot",6,$Io59_coddot,true,"text",4,"","chave_o59_coddot");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To59_mes?>">
              <?=$Lo59_mes?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o59_mes",2,$Io59_mes,true,"text",4,"","chave_o59_mes");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To59_coddoc?>">
              <?=$Lo59_coddoc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o59_coddoc",4,$Io59_coddoc,true,"text",4,"","chave_o59_coddoc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To59_valor?>">
              <?=$Lo59_valor?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o59_valor",15,$Io59_valor,true,"text",4,"","chave_o59_valor");
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
           if(file_exists("funcoes/db_func_orcdotacaoval.php")==true){
             include("funcoes/db_func_orcdotacaoval.php");
           }else{
           $campos = "orcdotacaoval.*";
           }
        }
        if(isset($chave_o59_coddot) && (trim($chave_o59_coddot)!="") ){
	         $sql = $clorcdotacaoval->sql_query(db_getsession('DB_anousu'),$chave_o59_coddot,$chave_o59_mes,$chave_o59_coddoc,$campos,"o59_coddot");
        }else if(isset($chave_o59_valor) && (trim($chave_o59_valor)!="") ){
	         $sql = $clorcdotacaoval->sql_query(db_getsession('DB_anousu'),"","","",$campos,"o59_valor"," o59_valor like '$chave_o59_valor%' ");
        }else{
           $sql = $clorcdotacaoval->sql_query(db_getsession('DB_anousu'),"","","",$campos,"o59_anousu#o59_coddot#o59_mes#o59_coddoc","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clorcdotacaoval->sql_record($clorcdotacaoval->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clorcdotacaoval->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o59_valor',false);</script>";
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