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
include("classes/db_rhregime_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhregime = new cl_rhregime;
$clrhregime->rotulo->label("rh30_codreg");
$clrhregime->rotulo->label("rh30_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Trh30_codreg?>">
              <?=$Lrh30_codreg?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh30_codreg",2,$Irh30_codreg,true,"text",4,"","chave_rh30_codreg");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh30_descr?>">
              <?=$Lrh30_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh30_descr",40,$Irh30_descr,true,"text",4,"","chave_rh30_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhregime.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
			$db_where = " rh30_utilizacao in (1,2) AND rh30_instit = ".db_getsession("DB_instit");
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhregime.php")==true){
             include("funcoes/db_func_rhregime.php");
           }else{
           $campos = "rhregime.*";
           }
        }
        if(isset($chave_rh30_codreg) && (trim($chave_rh30_codreg)!="") ){
	         $sql = $clrhregime->sql_query($chave_rh30_codreg,$campos,"rh30_codreg","rh30_codreg = $chave_rh30_codreg and $db_where");
        }else if(isset($chave_rh30_descr) && (trim($chave_rh30_descr)!="") ){
	         $sql = $clrhregime->sql_query("",$campos,"rh30_descr"," rh30_descr like '$chave_rh30_descr%' and $db_where ");
        }else{
           $sql = $clrhregime->sql_query("",$campos,"rh30_codreg",$db_where);
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          // die($clrhregime->sql_query($pesquisa_chave));
          $result = $clrhregime->sql_record($clrhregime->sql_query(null,"*","rh30_codreg","rh30_codreg = $pesquisa_chave and $db_where "));
          if($clrhregime->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh30_descr','$rh30_regime','$rh30_vinculo',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true,true,true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false,false,false);</script>";
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