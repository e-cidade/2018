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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_gerfprovs13_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clgerfprovs13 = new cl_gerfprovs13;
$clgerfprovs13->rotulo->label("r94_anousu");
$clgerfprovs13->rotulo->label("r94_mesusu");
$clgerfprovs13->rotulo->label("r94_regist");
$clgerfprovs13->rotulo->label("r94_rubric");
$clgerfprovs13->rotulo->label("r94_valor");
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
            <td width="4%" align="right" nowrap title="<?=$Tr94_mesusu?>">
              <?=$Lr94_mesusu?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r94_mesusu",2,$Ir94_mesusu,true,"text",4,"","chave_r94_mesusu");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr94_regist?>">
              <?=$Lr94_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r94_regist",9,$Ir94_regist,true,"text",4,"","chave_r94_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr94_rubric?>">
              <?=$Lr94_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r94_rubric",4,$Ir94_rubric,true,"text",4,"","chave_r94_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr94_valor?>">
              <?=$Lr94_valor?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r94_valor",15,$Ir94_valor,true,"text",4,"","chave_r94_valor");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_gerfprovs13.hide();">
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
           if(file_exists("funcoes/db_func_gerfprovs13.php")==true){
             include("funcoes/db_func_gerfprovs13.php");
           }else{
           $campos = "gerfprovs13.*";
           }
        }
        if(isset($chave_r94_mesusu) && (trim($chave_r94_mesusu)!="") ){
	         $sql = $clgerfprovs13->sql_query(db_getsession('DB_anousu'),$chave_r94_mesusu,$chave_r94_regist,$chave_r94_rubric,$campos,"r94_mesusu");
        }else if(isset($chave_r94_valor) && (trim($chave_r94_valor)!="") ){
	         $sql = $clgerfprovs13->sql_query(db_getsession('DB_anousu'),"","","",$campos,"r94_valor"," r94_valor like '$chave_r94_valor%' ");
        }else{
           $sql = $clgerfprovs13->sql_query(db_getsession('DB_anousu'),"","","",$campos,"r94_anousu#r94_mesusu#r94_regist#r94_rubric","");
        }
        $repassa = array();
        if(isset($chave_r94_valor)){
          $repassa = array("chave_r94_anousu"=>$chave_r94_anousu,"chave_r94_valor"=>$chave_r94_valor);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clgerfprovs13->sql_record($clgerfprovs13->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clgerfprovs13->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r94_valor',false);</script>";
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
js_tabulacaoforms("form2","chave_r94_valor",true,1,"chave_r94_valor",true);
</script>