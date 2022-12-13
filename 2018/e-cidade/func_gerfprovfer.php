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
include("classes/db_gerfprovfer_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clgerfprovfer = new cl_gerfprovfer;
$clgerfprovfer->rotulo->label("r93_anousu");
$clgerfprovfer->rotulo->label("r93_mesusu");
$clgerfprovfer->rotulo->label("r93_regist");
$clgerfprovfer->rotulo->label("r93_rubric");
$clgerfprovfer->rotulo->label("r93_tpp");
$clgerfprovfer->rotulo->label("r93_valor");
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
            <td width="4%" align="right" nowrap title="<?=$Tr93_mesusu?>">
              <?=$Lr93_mesusu?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r93_mesusu",2,$Ir93_mesusu,true,"text",4,"","chave_r93_mesusu");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr93_regist?>">
              <?=$Lr93_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r93_regist",9,$Ir93_regist,true,"text",4,"","chave_r93_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr93_rubric?>">
              <?=$Lr93_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r93_rubric",4,$Ir93_rubric,true,"text",4,"","chave_r93_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr93_tpp?>">
              <?=$Lr93_tpp?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r93_tpp",1,$Ir93_tpp,true,"text",4,"","chave_r93_tpp");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr93_valor?>">
              <?=$Lr93_valor?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r93_valor",15,$Ir93_valor,true,"text",4,"","chave_r93_valor");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_gerfprovfer.hide();">
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
           if(file_exists("funcoes/db_func_gerfprovfer.php")==true){
             include("funcoes/db_func_gerfprovfer.php");
           }else{
           $campos = "gerfprovfer.*";
           }
        }
        if(isset($chave_r93_mesusu) && (trim($chave_r93_mesusu)!="") ){
	         $sql = $clgerfprovfer->sql_query(db_getsession('DB_anousu'),$chave_r93_mesusu,$chave_r93_regist,$chave_r93_rubric,$chave_r93_tpp,$campos,"r93_mesusu");
        }else if(isset($chave_r93_valor) && (trim($chave_r93_valor)!="") ){
	         $sql = $clgerfprovfer->sql_query(db_getsession('DB_anousu'),"","","","",$campos,"r93_valor"," r93_valor like '$chave_r93_valor%' ");
        }else{
           $sql = $clgerfprovfer->sql_query(db_getsession('DB_anousu'),"","","","",$campos,"r93_anousu#r93_mesusu#r93_regist#r93_rubric#r93_tpp","");
        }
        $repassa = array();
        if(isset($chave_r93_valor)){
          $repassa = array("chave_r93_anousu"=>$chave_r93_anousu,"chave_r93_valor"=>$chave_r93_valor);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clgerfprovfer->sql_record($clgerfprovfer->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clgerfprovfer->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r93_valor',false);</script>";
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
js_tabulacaoforms("form2","chave_r93_valor",true,1,"chave_r93_valor",true);
</script>