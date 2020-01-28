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
include("classes/db_levvalorpgtos_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllevvalorpgtos = new cl_levvalorpgtos;
$cllevvalorpgtos->rotulo->label("y68_sequencia");
$cllevvalorpgtos->rotulo->label("y68_seq");
$cllevvalorpgtos->rotulo->label("y68_valor");
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
            <td width="4%" align="right" nowrap title="<?=$Ty68_sequencia?>">
              <?=$Ly68_sequencia?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("y68_sequencia",10,$Iy68_sequencia,true,"text",4,"","chave_y68_sequencia");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ty68_seq?>">
              <?=$Ly68_seq?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("y68_seq",4,$Iy68_seq,true,"text",4,"","chave_y68_seq");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ty68_valor?>">
              <?=$Ly68_valor?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("y68_valor",10,$Iy68_valor,true,"text",4,"","chave_y68_valor");
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
           if(file_exists("funcoes/db_func_levvalorpgtos.php")==true){
             include("funcoes/db_func_levvalorpgtos.php");
           }else{
           $campos = "levvalorpgtos.*";
           }
        }
        if(isset($chave_y68_sequencia) && (trim($chave_y68_sequencia)!="") ){
	         $sql = $cllevvalorpgtos->sql_query($chave_y68_sequencia,$chave_y68_seq,$campos,"y68_sequencia");
        }else if(isset($chave_y68_valor) && (trim($chave_y68_valor)!="") ){
	         $sql = $cllevvalorpgtos->sql_query("","",$campos,"y68_valor"," y68_valor like '$chave_y68_valor%' ");
        }else{
           $sql = $cllevvalorpgtos->sql_query("","",$campos,"y68_sequencia#y68_seq","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cllevvalorpgtos->sql_record($cllevvalorpgtos->sql_query($pesquisa_chave));
          if($cllevvalorpgtos->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$y68_valor',false);</script>";
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