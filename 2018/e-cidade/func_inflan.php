<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_inflan_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if (!isset($chave_i01_codigo)){
  $chave_i01_codigo = '';
}

if ( isset($chave_i01_codigo) && !DBNumber::isInteger($chave_i01_codigo) ) {
  $chave_i01_codigo = '';
}

$chave_i01_descr = isset($chave_i01_descr) ? stripslashes($chave_i01_descr) : '';

$clinflan = new cl_inflan;
$clinflan->rotulo->label("i01_codigo");
$clinflan->rotulo->label("i01_descr");

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
            <td width="4%" align="right" nowrap title="<?=$Ti01_codigo?>">
              <?=$Li01_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("i01_codigo",5,$Ii01_codigo,true,"text",4,"","chave_i01_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ti01_descr?>">
              <?=$Li01_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("i01_descr",40,$Ii01_descr,true,"text",4,"","chave_i01_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_inflan.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $chave_i01_descr = addslashes($chave_i01_descr);

      if(!isset($pesquisa_chave)){

        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_inflan.php")==true){
              include("funcoes/db_func_inflan.php");
           }else{
	          $campos = "inflan.*";
           }
        }

        if( isset($chave_i01_codigo) ){
          if (  !DBNumber::isInteger($chave_i01_codigo) ) {
            $chave_i01_codigo = '';
          }
        }

        if(isset($chave_i01_codigo) && (trim($chave_i01_codigo)!="" && DBNumber::isInteger($chave_i01_codigo) ) ){
	         $sql = $clinflan->sql_query($chave_i01_codigo,$campos,"i01_codigo");
        }else if(isset($chave_i01_descr) && (trim($chave_i01_descr)!="") ){
	         $sql = $clinflan->sql_query("",$campos,"i01_descr"," i01_descr like '$chave_i01_descr%' ");
        }else{
           $sql = $clinflan->sql_query("",$campos,"i01_codigo","");
        }

        if( isset($chave_i01_descr) ){
          $chave_i01_descr = str_replace("\\", "", $chave_i01_descr);
        }

        $repassa = array();
        if(isset($chave_i01_descr)){
          $repassa = array("chave_i01_codigo"=>$chave_i01_codigo,"chave_i01_descr"=>$chave_i01_descr);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clinflan->sql_record($clinflan->sql_query($pesquisa_chave));
          if($clinflan->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$i01_descr',false);</script>";
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
      if( document.getElementById('chave_i01_codigo').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('chave_i01_codigo').value ) ) {
          alert('Campo Código do Inflator deve ser preenchido somente com números!');
          document.getElementById('chave_i01_codigo').value = '';
          return false;
        }
      }  
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_i01_descr",true,1,"chave_i01_descr",true);
</script>