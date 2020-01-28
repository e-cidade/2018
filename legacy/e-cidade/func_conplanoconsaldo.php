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
include("classes/db_conplanoconsaldo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconplanoconsaldo = new cl_conplanoconsaldo;
$clconplanoconsaldo->rotulo->label("c59_anoexe");
$clconplanoconsaldo->rotulo->label("c59_codcon");
$clconplanoconsaldo->rotulo->label("c59_instit");
$clconplanoconsaldo->rotulo->label("c59_mes");
$clconplanoconsaldo->rotulo->label("c59_debito");
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
            <td width="4%" align="right" nowrap title="<?=$Tc59_anoexe?>">
              <?=$Lc59_anoexe?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c59_anoexe",4,$Ic59_anoexe,true,"text",4,"","chave_c59_anoexe");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc59_codcon?>">
              <?=$Lc59_codcon?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c59_codcon",6,$Ic59_codcon,true,"text",4,"","chave_c59_codcon");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc59_instit?>">
              <?=$Lc59_instit?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c59_instit",2,$Ic59_instit,true,"text",4,"","chave_c59_instit");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc59_mes?>">
              <?=$Lc59_mes?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c59_mes",2,$Ic59_mes,true,"text",4,"","chave_c59_mes");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc59_debito?>">
              <?=$Lc59_debito?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c59_debito",8,$Ic59_debito,true,"text",4,"","chave_c59_debito");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conplanoconsaldo.hide();">
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
           if(file_exists("funcoes/db_func_conplanoconsaldo.php")==true){
             include("funcoes/db_func_conplanoconsaldo.php");
           }else{
           $campos = "conplanoconsaldo.*";
           }
        }
        if(isset($chave_c59_anoexe) && (trim($chave_c59_anoexe)!="") ){
	         $sql = $clconplanoconsaldo->sql_query($chave_c59_anoexe,$chave_c59_codcon,$chave_c59_instit,$chave_c59_mes,$campos,"c59_anoexe");
        }else if(isset($chave_c59_debito) && (trim($chave_c59_debito)!="") ){
	         $sql = $clconplanoconsaldo->sql_query("","","","",$campos,"c59_debito"," c59_debito like '$chave_c59_debito%' ");
        }else{
           $sql = $clconplanoconsaldo->sql_query("","","","",$campos,"c59_anoexe#c59_codcon#c59_instit#c59_mes","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clconplanoconsaldo->sql_record($clconplanoconsaldo->sql_query($pesquisa_chave));
          if($clconplanoconsaldo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c59_debito',false);</script>";
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