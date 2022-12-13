<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_orcorgao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$chave_o40_descr = isset($chave_o40_descr) ? stripslashes($chave_o40_descr) : '';

$clorcorgao = new cl_orcorgao;
$clorcorgao->rotulo->label("o40_anousu");
$clorcorgao->rotulo->label("o40_orgao");
$clorcorgao->rotulo->label("o40_descr");
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
            <td width="4%" align="right" nowrap title="<?=$To40_orgao?>">
              <?=$Lo40_orgao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o40_orgao",2,$Io40_orgao,true,"text",4,"","chave_o40_orgao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To40_descr?>">
              <?=$Lo40_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o40_descr",50,$Io40_descr,true,"text",4,"","chave_o40_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcorgao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $chave_o40_descr = addslashes($chave_o40_descr);

      $dbwhere = "";
      if(isset($instit)){
      	$dbwhere = " and o40_instit = ".$instit;
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_orcorgao.php")==true){
             include("funcoes/db_func_orcorgao.php");
           }else{
           $campos = "orcorgao.*";
           }
        }

        if( isset($chave_o40_orgao) ){
          if (  !DBNumber::isInteger($chave_o40_orgao) ) {
            $chave_o40_orgao = '';
          }
        }

        if(isset($chave_o40_orgao) && (trim($chave_o40_orgao)!="") ){
	         $sql = $clorcorgao->sql_query(null,null,$campos,"o40_orgao"," o40_anousu = ".db_getsession('DB_anousu')." and o40_orgao = ".$chave_o40_orgao.$dbwhere);
        }else if(isset($chave_o40_descr) && (trim($chave_o40_descr)!="") ){
	         $sql = $clorcorgao->sql_query(null,null,$campos,"o40_descr"," o40_anousu = ".db_getsession('DB_anousu')." and o40_descr like '".$chave_o40_descr."%' ".$dbwhere);
        }else{
           $sql = $clorcorgao->sql_query(null,null,$campos,"o40_anousu#o40_orgao"," o40_anousu = ".db_getsession('DB_anousu').$dbwhere);
        }
	       
        if( isset($chave_o40_descr) ){
          $chave_o40_descr = str_replace("\\", "", $chave_o40_descr);
        } 
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $clorcorgao->sql_record($clorcorgao->sql_query(null,null,"*",""," o40_anousu = ".db_getsession('DB_anousu')." and o40_orgao = ".$pesquisa_chave.$dbwhere));
          
          if($clorcorgao->numrows!=0){

            db_fieldsmemory($result,0);
            
            echo "<script>".$funcao_js."('$o40_descr',false);</script>";
            
          } else {
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
    (function(){
      
      if( document.getElementById('chave_o40_orgao').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('chave_o40_orgao').value ) ) {
          alert('Órgão deve ser preenchido somente com números!');
          document.getElementById('chave_o40_orgao').value = '';
          return false;  
        }
      }
      
    })();
  </script>
  <?
}
?>