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
include("classes/db_obrascaractarqsisobra_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clobrascaractarqsisobra = new cl_obrascaractarqsisobra;
$clobrascaractarqsisobra->rotulo->label("ob23_sequencial");
$clobrascaractarqsisobra->rotulo->label("ob23_caractdestino");
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
            <td width="4%" align="right" nowrap title="<?=$Tob23_sequencial?>">
              <?=$Lob23_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ob23_sequencial",10,$Iob23_sequencial,true,"text",4,"","chave_ob23_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tob23_caractdestino?>">
              <?=$Lob23_caractdestino?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ob23_caractdestino",10,$Iob23_caractdestino,true,"text",4,"","chave_ob23_caractdestino");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_obrascaractarqsisobra.hide();">
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
           if(file_exists("funcoes/db_func_obrascaractarqsisobra.php")==true){
             include("funcoes/db_func_obrascaractarqsisobra.php");
           }else{
           $campos = "obrascaractarqsisobra.*";
           }
        }
        if(isset($chave_ob23_sequencial) && (trim($chave_ob23_sequencial)!="") ){
	         $sql = $clobrascaractarqsisobra->sql_query($chave_ob23_sequencial,$campos,"ob23_sequencial");
        }else if(isset($chave_ob23_caractdestino) && (trim($chave_ob23_caractdestino)!="") ){
	         $sql = $clobrascaractarqsisobra->sql_query("",$campos,"ob23_caractdestino"," ob23_caractdestino like '$chave_ob23_caractdestino%' ");
        }else{
           $sql = $clobrascaractarqsisobra->sql_query("",$campos,"ob23_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ob23_caractdestino)){
          $repassa = array("chave_ob23_sequencial"=>$chave_ob23_sequencial,"chave_ob23_caractdestino"=>$chave_ob23_caractdestino);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clobrascaractarqsisobra->sql_record($clobrascaractarqsisobra->sql_query($pesquisa_chave));
          if($clobrascaractarqsisobra->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ob23_caractdestino',false);</script>";
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
js_tabulacaoforms("form2","chave_ob23_caractdestino",true,1,"chave_ob23_caractdestino",true);
</script>