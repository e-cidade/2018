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
include("classes/db_vtfempr_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clvtfempr = new cl_vtfempr;
$clvtfempr->rotulo->label("r16_anousu");
$clvtfempr->rotulo->label("r16_mesusu");
$clvtfempr->rotulo->label("r16_codigo");
$clvtfempr->rotulo->label("r16_descr");
if(!isset($chave_r16_anousu) || (isset($chave_r16_anousu) && trim($chave_r16_anousu) == "")){
  $chave_r16_anousu = db_anofolha();
}
if(!isset($chave_r16_mesusu) || (isset($chave_r16_mesusu) && trim($chave_r16_mesusu) == "")){
  $chave_r16_mesusu = db_mesfolha();
}
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
            <td width="4%" align="right" nowrap title="<?=$Tr16_codigo?>">
              <?=$Lr16_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r16_codigo",4,$Ir16_codigo,true,"text",4,"","chave_r16_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr16_descr?>">
              <?=$Lr16_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r16_descr",30,$Ir16_descr,true,"text",4,"","chave_r16_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_vtfempr.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where = " r16_instit =".db_getsession("DB_instit");
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_vtfempr.php")==true){
             include("funcoes/db_func_vtfempr.php");
           }else{
           $campos = "vtfempr.*";
           }
        }
        if(isset($chave_r16_codigo) && (trim($chave_r16_codigo)!="") ){
	         $sql = $clvtfempr->sql_query($chave_r16_anousu,$chave_r16_mesusu,$chave_r16_codigo,db_getsession("DB_instit"),$campos,"r16_mesusu");
        }else if(isset($chave_r16_descr) && (trim($chave_r16_descr)!="") ){
	         $sql = $clvtfempr->sql_query(null,null,null,null,$campos,"r16_descr","  r16_anousu = ".$chave_r16_anousu." and r16_mesusu = ".$chave_r16_mesusu." and r16_descr like '$chave_r16_descr%' and $where ");
        }else{
           $sql = $clvtfempr->sql_query($chave_r16_anousu,$chave_r16_mesusu,null,db_getsession("DB_instit"),$campos,"r16_anousu#r16_mesusu#r16_codigo","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clvtfempr->sql_record($clvtfempr->sql_query($chave_r16_anousu,$chave_r16_mesusu,$pesquisa_chave,db_getsession("DB_instit")));
          if($clvtfempr->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r16_descr',false);</script>";
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