<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_bairro_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clbairro = new cl_bairro;
$clbairro->rotulo->label("j13_codi");
$clbairro->rotulo->label("j13_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tj13_codi?>">
              <?=$Lj13_codi?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j13_codi",4,$Ij13_codi,true,"text",4,"","chave_j13_codi");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj13_descr?>">
              <?=$Lj13_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j13_descr",40,$Ij13_descr,true,"text",4,"","chave_j13_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_bairro.hide();">
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

        $campos = "j13_codi, j13_descr, {$_GET['iFace']} as dl_Face ";
        if(isset($chave_j13_codi) && (trim($chave_j13_codi)!="") ){
	         $sql = $clbairro->sql_query($chave_j13_codi,$campos,"j13_codi");
        }else if(isset($chave_j13_descr) && (trim($chave_j13_descr)!="") ){
	         $sql = $clbairro->sql_query("",$campos,"j13_descr"," j13_descr like '$chave_j13_descr%' ");
        }else{
           $sql = $clbairro->sql_query("",$campos,"j13_codi","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
        
      } else {
      	
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
        	
          $result = $clbairro->sql_record($clbairro->sql_query($pesquisa_chave));
          
          if($clbairro->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j13_descr',false, {$_GET['dl_Face']});</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true, {$_GET['dl_Face']});</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false, {$_GET['dl_Face']});</script>";
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
  function js_limpar(){
  document.form2.chave_j13_codi.value="";
  document.form2.chave_j13_descr.value="";
  }
  </script>
  <?
}
?>