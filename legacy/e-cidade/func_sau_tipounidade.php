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
include("classes/db_sau_tipounidade_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsau_tipounidade = new cl_sau_tipounidade;
$clsau_tipounidade->rotulo->label("sd42_i_tp_unid_id");
$clsau_tipounidade->rotulo->label("sd42_v_descricao");
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
            <td width="4%" align="right" nowrap title="<?=$Tsd42_i_tp_unid_id?>">
              <?=$Lsd42_i_tp_unid_id?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                       db_input("sd42_i_tp_unid_id",2,$Isd42_i_tp_unid_id,true,"text",4,"","chave_sd42_i_tp_unid_id");
                       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd42_v_descricao?>">
              <?=$Lsd42_v_descricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                       db_input("sd42_v_descricao",60,$Isd42_v_descricao,true,"text",4,"","chave_sd42_v_descricao");
                       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_tipounidade.hide();">
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
           if(file_exists("funcoes/db_func_sau_tipounidade.php")==true){
             include("funcoes/db_func_sau_tipounidade.php");
           }else{
           $campos = "sau_tipounidade.*";
           }
        }
        if(isset($chave_sd42_i_tp_unid_id) && (trim($chave_sd42_i_tp_unid_id)!="") ){
                 $sql = $clsau_tipounidade->sql_query($chave_sd42_i_tp_unid_id,$campos,"sd42_v_descricao");
        }else if(isset($chave_sd42_v_descricao) && (trim($chave_sd42_v_descricao)!="") ){
                 $sql = $clsau_tipounidade->sql_query("",$campos,"sd42_v_descricao"," sd42_v_descricao like '$chave_sd42_v_descricao%' ");
        }else{
           $sql = $clsau_tipounidade->sql_query("",$campos,"sd42_v_descricao","");
        }
        $repassa = array();
        if(isset($chave_sd42_i_tp_unid_id)){
          $repassa = array("chave_sd42_i_tp_unid_id"=>$chave_sd42_i_tp_unid_id,"chave_sd42_v_descricao"=>$chave_sd42_v_descricao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clsau_tipounidade->sql_record($clsau_tipounidade->sql_query($pesquisa_chave));
          if($clsau_tipounidade->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd42_v_descricao',false);</script>";
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
<script>
function js_limpar(){
document.form2.chave_sd42_i_tp_unid_id.value="";
document.form2.chave_sd42_v_descricao.value="";	
	
}
js_tabulacaoforms("form2","chave_sd42_v_descricao",true,1,"chave_sd42_v_descricao",true);
</script>