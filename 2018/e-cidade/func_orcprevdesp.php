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
include("classes/db_orcprevdesp_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcprevdesp = new cl_orcprevdesp;
$clorcprevdesp->rotulo->label("o35_anousu");
$clorcprevdesp->rotulo->label("o35_projativ");
$clorcprevdesp->rotulo->label("o35_codigo");
$clorcprevdesp->rotulo->label("o35_mes");
$clorcprevdesp->rotulo->label("o35_projativ");
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
            <td width="4%" align="right" nowrap title="<?=$To35_projativ?>">
              <?=$Lo35_projativ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o35_projativ",4,$Io35_projativ,true,"text",4,"","chave_o35_projativ");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To35_codigo?>">
              <?=$Lo35_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o35_codigo",4,$Io35_codigo,true,"text",4,"","chave_o35_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To35_mes?>">
              <?=$Lo35_mes?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o35_mes",2,$Io35_mes,true,"text",4,"","chave_o35_mes");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To35_projativ?>">
              <?=$Lo35_projativ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o35_projativ",4,$Io35_projativ,true,"text",4,"","chave_o35_projativ");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcprevdesp.hide();">
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
           if(file_exists("funcoes/db_func_orcprevdesp.php")==true){
             include("funcoes/db_func_orcprevdesp.php");
           }else{
           $campos = "orcprevdesp.*";
           }
        }
        if(isset($chave_o35_projativ) && (trim($chave_o35_projativ)!="") ){
	         $sql = $clorcprevdesp->sql_query(db_getsession('DB_anousu'),$chave_o35_projativ,$chave_o35_codigo,$chave_o35_mes,$campos,"o35_projativ");
        }else if(isset($chave_o35_projativ) && (trim($chave_o35_projativ)!="") ){
	         $sql = $clorcprevdesp->sql_query(db_getsession('DB_anousu'),"","","",$campos,"o35_projativ"," o35_projativ like '$chave_o35_projativ%' ");
        }else{
           $sql = $clorcprevdesp->sql_query(db_getsession('DB_anousu'),"","","",$campos,"o35_anousu#o35_projativ#o35_codigo#o35_mes","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clorcprevdesp->sql_record($clorcprevdesp->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clorcprevdesp->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o35_projativ',false);</script>";
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