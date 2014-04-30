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
include("classes/db_sau_fechamento_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsau_fechamento = new cl_sau_fechamento;
$clsau_fechamento->rotulo->label("sd97_i_compmes");
$clsau_fechamento->rotulo->label("sd97_i_compano");

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
            <td width="4%" align="right" nowrap title="<?=$Tsd97_i_compmes?>">
              <b>Competência: </b>
            </td>
            <td width="5%" nowrap> 
              <? db_input("sd97_i_compmes",5,$Isd97_i_compmes,true,"text",4,"","chave_sd97_i_compmes");?>			   
            </td>
			<td width="0%" nowrap>/</td>
			</td>
            <td width="0%" nowrap> 
             <? db_input("sd97_i_compano",5,$Isd97_i_compano,true,"text",4,"","chave_sd97_i_compano");?> 
            </td>
          <tr> 
            <td colspan="4" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_fechamento.hide();">
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
           if(file_exists("funcoes/db_func_sau_fechamento.php")==true){
             include("funcoes/db_func_sau_fechamento.php");
           }else{
           $campos = "sau_fechamento.*";
           }
        }
        if(isset($chave_sd97_i_compmes) && (trim($chave_sd97_i_compmes)!="") && isset($chave_sd97_i_compano) && (trim($chave_sd97_i_compano)!="")){
	         $sql = $clsau_fechamento->sql_query("",$campos,"sd97_i_codigo desc","sd97_i_compmes=$chave_sd97_i_compmes and sd97_i_compano= $chave_sd97_i_compano");
        }else{
           $sql = $clsau_fechamento->sql_query("",$campos,"sd97_i_codigo desc","sd97_c_tipo='Fechada'");
		}
        $repassa = array();
        if(isset($chave_sd97_i_codigo)){
          $repassa = array("chave_sd97_i_compmes"=>$chave_sd97_i_compmes,"chave_sd97_i_compano"=>$chave_sd97_i_compano);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clsau_fechamento->sql_record($clsau_fechamento->sql_query($pesquisa_chave));
          if($clsau_fechamento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd97_i_compmes',false);</script>";
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
js_tabulacaoforms("form2","chave_sd97_i_codigo",true,1,"chave_sd97_i_codigo",true);
</script>