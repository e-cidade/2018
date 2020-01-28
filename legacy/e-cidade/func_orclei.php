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
include("classes/db_orclei_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorclei = new cl_orclei;
$clorclei->rotulo->label("o45_codlei");
$clorclei->rotulo->label("o45_numlei");
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
            <td width="4%" align="right" nowrap title="<?=$To45_codlei?>">
              <?=$Lo45_codlei?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o45_codlei",4,$Io45_codlei,true,"text",4,"","chave_o45_codlei");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To45_numlei?>">
              <?=$Lo45_numlei?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o45_numlei",30,$Io45_numlei,true,"text",4,"","chave_o45_numlei");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orclei.hide();">

             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere = '1=1';
      if (isset($leimanual)) {
        $sWhere .= " and o45_tipolei = 1 and extract (year from o45_datafim) = ".db_getsession("DB_anousu");   
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_orclei.php")==true){
             include("funcoes/db_func_orclei.php");
           }else{
           $campos = "orclei.*";
           }
        }
        if(isset($chave_o45_codlei) && (trim($chave_o45_codlei)!="") ){
	         $sql = $clorclei->sql_query($chave_o45_codlei,$campos,"o45_codlei");
        }else if(isset($chave_o45_numlei) && (trim($chave_o45_numlei)!="") ){
	         $sql = $clorclei->sql_query("",$campos,"o45_numlei"," o45_numlei like '$chave_o45_numlei%' and {$sWhere} ");
        }else{
           $sql = $clorclei->sql_query("",$campos,"o45_codlei","{$sWhere}");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clorclei->sql_record($clorclei->sql_query(null, null,"*", 
                                                               "o45_codlei = {$pesquisa_chave} and {$sWhere}"));
          if($clorclei->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o45_numlei',false);</script>";
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