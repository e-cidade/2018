<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_orcfuncao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$chave_o52_descr = isset($chave_o52_descr) ? stripslashes($chave_o52_descr) : '';

$clorcfuncao = new cl_orcfuncao;
$clorcfuncao->rotulo->label("o52_funcao");
$clorcfuncao->rotulo->label("o52_descr");
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
            <td width="4%" align="right" nowrap title="<?=$To52_funcao?>">
              <?=$Lo52_funcao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o52_funcao",2,$Io52_funcao,true,"text",4,"","chave_o52_funcao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To52_descr?>">
              <?=$Lo52_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o52_descr",40,$Io52_descr,true,"text",4,"","chave_o52_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcfuncao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $chave_o52_descr = addslashes($chave_o52_descr);

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_orcfuncao.php")==true){
             include("funcoes/db_func_orcfuncao.php");
           }else{
           $campos = "orcfuncao.*";
           }
        }

        if( isset($chave_o52_funcao) ){
          if (  !DBNumber::isInteger($chave_o52_funcao) ) {
            $chave_o52_funcao = '';
          }
        }
        
        if(isset($chave_o52_funcao) && (trim($chave_o52_funcao)!="") ){
	         $sql = $clorcfuncao->sql_query($chave_o52_funcao,$campos,"o52_funcao");
        }else if(isset($chave_o52_descr) && (trim($chave_o52_descr)!="") ){
	         $sql = $clorcfuncao->sql_query("",$campos,"o52_descr"," o52_descr like '$chave_o52_descr%' ");
        }else{
           $sql = $clorcfuncao->sql_query("",$campos,"o52_funcao","");
        }

        if( isset($chave_o52_descr) ){
          $chave_o52_descr = str_replace("\\", "", $chave_o52_descr);
        }

        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clorcfuncao->sql_record($clorcfuncao->sql_query($pesquisa_chave));
          if($clorcfuncao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o52_descr',false);</script>";
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
    (function(){
      
      if( document.getElementById('chave_o52_funcao').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('chave_o52_funcao').value ) ) {
          alert('Seleção deve ser preenchido somente com números!');
          document.getElementById('chave_o52_funcao').value = '';
          return false;  
        }
      }
      
    })();
  </script>
  <?
}
?>