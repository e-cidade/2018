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
include("classes/db_sau_retentributo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsau_retentributo = new cl_sau_retentributo;
$clsau_retentributo->rotulo->label("sd39_i_cod_reten");
$clsau_retentributo->rotulo->label("sd39_v_situacao");
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
            <td width="4%" align="right" nowrap title="<?=$Tsd39_i_cod_reten?>">
              <?=$Lsd39_i_cod_reten?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                       db_input("sd39_i_cod_reten",2,$Isd39_i_cod_reten,true,"text",4,"","chave_sd39_i_cod_reten");
                       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd39_v_situacao?>">
              <?=$Lsd39_v_situacao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                       db_input("sd39_v_situacao",60,$Isd39_v_situacao,true,"text",4,"","chave_sd39_v_situacao");
                       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_retentributo.hide();">
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
           if(file_exists("funcoes/db_func_sau_retentributo.php")==true){
             include("funcoes/db_func_sau_retentributo.php");
           }else{
           $campos = "sau_retentributo.*";
           }
        }
        if(isset($chave_sd39_i_cod_reten) && (trim($chave_sd39_i_cod_reten)!="") ){
                 $sql = $clsau_retentributo->sql_query($chave_sd39_i_cod_reten,$campos,"sd39_v_situacao");
        }else if(isset($chave_sd39_v_situacao) && (trim($chave_sd39_v_situacao)!="") ){
                 $sql = $clsau_retentributo->sql_query("",$campos,"sd39_v_situacao"," sd39_v_situacao like '$chave_sd39_v_situacao%' ");
        }else{
           $sql = $clsau_retentributo->sql_query("",$campos,"sd39_v_situacao","");
        }
        $repassa = array();
        if(isset($chave_sd39_i_cod_reten)){
          $repassa = array("chave_sd39_i_cod_reten"=>$chave_sd39_i_cod_reten,"chave_sd39_v_situacao"=>$chave_sd39_v_situacao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clsau_retentributo->sql_record($clsau_retentributo->sql_query($pesquisa_chave));
          if($clsau_retentributo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd39_v_situacao',false);</script>";
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
function js_limpar(){
document.form2.chave_sd39_i_cod_reten.value="";
document.form2.chave_sd39_v_situacao.value="";	
}
js_tabulacaoforms("form2","chave_sd39_v_situacao",true,1,"chave_sd39_v_situacao",true);
</script>