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
include("classes/db_matmater_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatmater = new cl_matmater;
$clmatmater->rotulo->label("m60_codmater");
$clmatmater->rotulo->label("m60_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tm60_codmater?>">
              <?=$Lm60_codmater?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("m60_codmater",10,$Im60_codmater,true,"text",4,"","chave_m60_codmater");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tm60_descr?>">
              <?=$Lm60_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("m60_descr",40,$Im60_descr,true,"text",4,"","chave_m60_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_matmater.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where_deptoestoque = " and m71_quant>=m71_quantatend ";
      if(isset($codigododepartamento)){
	$where_deptoestoque .= " and m70_coddepto=$codigododepartamento";
      }
      if(isset($setdepart)){
	$where_deptoestoque .= " and m70_coddepto is not null ";
      }
      if(isset($nosetmaterial) && trim($nosetmaterial)!=""){
	$where_deptoestoque .= " and m70_codmatmater not in ($nosetmaterial) ";
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_matmater.php")==true){
             include("funcoes/db_func_matmater.php");
           }else{
           $campos = "matmater.*";
           }
        }
	$campos = " distinct ".$campos;
        if(isset($chave_m60_codmater) && (trim($chave_m60_codmater)!="") ){
	         $sql = $clmatmater->sql_query_deptoestoque(null,$campos,"m60_codmater","m60_codmater=$chave_m60_codmater and m60_ativo is true $where_deptoestoque ");
        }else if(isset($chave_m60_descr) && (trim($chave_m60_descr)!="") ){
	         $sql = $clmatmater->sql_query_deptoestoque("",$campos,"m60_descr"," m60_descr like '$chave_m60_descr%' and m60_ativo is true $where_deptoestoque");
        }else{
           $sql = $clmatmater->sql_query_deptoestoque("",$campos,"m60_codmater"," m60_ativo is true $where_deptoestoque");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmatmater->sql_record($clmatmater->sql_query_deptoestoque(null,"*","","m60_codmater=$pesquisa_chave and m60_ativo is true $where_deptoestoque"));
          if($clmatmater->numrows!=0){
            db_fieldsmemory($result,0);
            $m60_descr = str_replace(chr(10), " ", $m60_descr);
            $m60_descr=addslashes($m60_descr);
            
            echo "<script>".$funcao_js."('".substr($m60_descr,0,40)."',false);</script>";
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