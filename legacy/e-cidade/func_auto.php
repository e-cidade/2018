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
include("classes/db_auto_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clauto = new cl_auto;
$clauto->rotulo->label("y50_codauto");
$clauto->rotulo->label("y50_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Ty50_codauto?>">
              <?=$Ly50_codauto?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("y50_codauto",10,$Iy50_codauto,true,"text",4,"","chave_y50_codauto");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ty50_nome?>">
              <?=$Ly50_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("y50_nome",50,$Iy50_nome,true,"text",4,"","chave_y50_nome");
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
           if(file_exists("funcoes/db_func_auto.php")==true){
             include("funcoes/db_func_auto.php");
           }else{
           $campos = "auto.*";
           }
        }
		
		if (isset($db_opcao) && ($db_opcao == 3 || $db_opcao == 33)) {
		  $where = " and not exists (select 1 from autonumpre where y17_codauto = dl_Auto) ";	
		}
		
        if(isset($chave_y50_codauto) && (trim($chave_y50_codauto)!="") ){
	         $sql = $clauto->sql_query($chave_y50_codauto,$campos,"y50_codauto","  y50_instit = ".db_getsession('DB_instit').$where);
        }else if(isset($chave_y50_nome) && (trim($chave_y50_nome)!="") ){
	         $sql = $clauto->sql_query("",$campos,"y50_nome"," y50_nome like '$chave_y50_nome%' and  y50_instit = ".db_getsession('DB_instit').$where);
        }else{
           $sql = $clauto->sql_query("",$campos,"y50_codauto"," y50_instit = ".db_getsession('DB_instit').$where);
        }

        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clauto->sql_record($clauto->sql_query($pesquisa_chave,"*",null," y50_instit = ".db_getsession('DB_instit')." and y50_codauto = $pesquisa_chave "));
          if($clauto->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$y50_nome',false);</script>";
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