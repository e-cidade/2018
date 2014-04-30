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
include("classes/db_rhcadcalend_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhcadcalend = new cl_rhcadcalend;
$clrhcadcalend->rotulo->label("rh53_calend");
$clrhcadcalend->rotulo->label("rh53_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Trh53_calend?>">
              <?=$Lrh53_calend?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh53_calend",10,$Irh53_calend,true,"text",4,"","chave_rh53_calend");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh53_descr?>">
              <?=$Lrh53_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh53_descr",40,$Irh53_descr,true,"text",4,"","chave_rh53_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhcadcalend.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where = " rh53_instit = ".db_getsession("DB_instit");
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhcadcalend.php")==true){
             include("funcoes/db_func_rhcadcalend.php");
           }else{
           $campos = "rhcadcalend.*";
           }
        }
        if(isset($chave_rh53_calend) && (trim($chave_rh53_calend)!="") ){
	         $sql = $clrhcadcalend->sql_query($chave_rh53_calend,$campos,"rh53_calend"," rh53_calend = $chave_rh53_calend and $where");
        }else if(isset($chave_rh53_descr) && (trim($chave_rh53_descr)!="") ){
	         $sql = $clrhcadcalend->sql_query("",$campos,"rh53_descr"," rh53_descr like '$chave_rh53_descr%' and $where");
        }else{
           $sql = $clrhcadcalend->sql_query("",$campos,"rh53_calend",$where);
        }
        $repassa = array();
        if(isset($chave_rh53_descr)){
          $repassa = array("chave_rh53_calend"=>$chave_rh53_calend,"chave_rh53_descr"=>$chave_rh53_descr);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhcadcalend->sql_record($clrhcadcalend->sql_query(null,"*",null,"rh53_calend = $pesquisa_chave and $where"));
          if($clrhcadcalend->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh53_descr',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_rh53_descr",true,1,"chave_rh53_descr",true);
</script>