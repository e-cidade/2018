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
include("classes/db_obrasconstr_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clobrasconstr = new cl_obrasconstr;
$clobrasconstr->rotulo->label("ob08_codconstr");
$clobrasconstr->rotulo->label("ob08_codobra");
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
            <td width="4%" align="right" nowrap title="<?=$Tob08_codconstr?>">
              <?=$Lob08_codconstr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ob08_codconstr",10,$Iob08_codconstr,true,"text",4,"","chave_ob08_codconstr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tob08_codobra?>">
              <?=$Lob08_codobra?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ob08_codobra",10,$Iob08_codobra,true,"text",4,"","chave_ob08_codobra");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_obrasconstr.hide();">
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
        $campos = "obrasconstr.ob08_codconstr,obrasconstr.ob08_codobra,obras.ob01_nomeobra,ob08_area";
        if(isset($chave_ob08_codconstr) && (trim($chave_ob08_codconstr)!="") ){
	         $sql = $clobrasconstr->sql_query_alvara_habite("",$campos,"ob08_codconstr desc","ob08_codconstr = $chave_ob08_codconstr and (ob09_codhab is null or ob08_area<>ob09_area)");
        }else if(isset($chave_ob08_codobra) && (trim($chave_ob08_codobra)!="") ){
	         $sql = $clobrasconstr->sql_query_alvara_habite("",$campos,"ob08_codobra"," ob08_codobra like '$chave_ob08_codobra%' and (ob09_codhab is null or ob08_area<>ob09_area)");
        }else{
           $sql = $clobrasconstr->sql_query_alvara_habite("",$campos,"ob08_codconstr desc","(ob09_codhab is null or ob08_area<>ob09_area)");
        }
				//die($sql);
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clobrasconstr->sql_record($clobrasconstr->sql_query_alvara_habite("","*","","ob08_codconstr =$pesquisa_chave and (ob09_codhab is null or ob08_area<>ob09_area)"));
          if($clobrasconstr->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ob08_codobra','$ob08_area',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('','',false);</script>";
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