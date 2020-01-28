<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("classes/db_db_documentotemplate_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if (!isset($chave_db82_sequencial)){
  $chave_db82_sequencial = '';
}

$chave_db82_descricao = isset($chave_db82_descricao) ? stripslashes($chave_db82_descricao) : '';

if ( isset($chave_db82_sequencial) && !DBNumber::isInteger($chave_db82_sequencial) ) {
  $chave_db82_sequencial = '';
}

$cldb_documentotemplate = new cl_db_documentotemplate;
$cldb_documentotemplate->rotulo->label("db82_sequencial");
$cldb_documentotemplate->rotulo->label("db82_descricao");
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
            <td width="4%" align="right" nowrap title="<?=$Tdb82_sequencial?>">
              <?=$Ldb82_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db82_sequencial",10,$Idb82_sequencial,true,"text",4,"","chave_db82_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdb82_descricao?>">
              <?=$Ldb82_descricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db82_descricao",50,$Idb82_descricao,true,"text",4,"","chave_db82_descricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_documentotemplate.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $chave_db82_descricao = addslashes($chave_db82_descricao);
      
      $sWhere = "1=1";
      if ( isset($tipo) && trim($tipo) != '' ) {
        $sWhere .= "and db82_templatetipo = {$tipo}";
      }      
      
      if(!isset($pesquisa_chave)){
      	
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_db_documentotemplate.php")==true){
             include("funcoes/db_func_db_documentotemplate.php");
           }else{
           $campos = "db_documentotemplate.*";
           }
        }

        if( isset($chave_db82_sequencial) ){
          if (  !DBNumber::isInteger($chave_db82_sequencial) ) {
            $chave_db82_sequencial = '';
          }
        }
        
        if(isset($chave_db82_sequencial) && (trim($chave_db82_sequencial)!="" && DBNumber::isInteger($chave_db82_sequencial)) ){
	        $sql = $cldb_documentotemplate->sql_query(null,$campos,"db82_sequencial","db82_sequencial = $chave_db82_sequencial and {$sWhere}");
        } else if(isset($chave_db82_descricao) && (trim($chave_db82_descricao)!="") ){
          $sql = $cldb_documentotemplate->sql_query("",$campos,"db82_descricao"," db82_descricao like '$chave_db82_descricao%' and {$sWhere}");
        } else {
          $sql = $cldb_documentotemplate->sql_query("",$campos,"db82_sequencial","{$sWhere}");
        }

        if( isset($chave_db82_descricao) ){
          $chave_db82_descricao = str_replace("\\", "", $chave_db82_descricao);
        }

        $repassa = array();
        if(isset($chave_db82_descricao)){
          $repassa = array("chave_db82_sequencial"=>$chave_db82_sequencial,"chave_db82_descricao"=>$chave_db82_descricao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_documentotemplate->sql_record($cldb_documentotemplate->sql_query(null,"*",null,"db82_sequencial = $pesquisa_chave and {$sWhere}"));
          if($cldb_documentotemplate->numrows!=0){
            db_fieldsmemory($result,0);
            if (isset($tipo)) {
              echo "<script>".$funcao_js."('$db82_descricao',false,$tipo);</script>";
            } else {
              echo "<script>".$funcao_js."('$db82_descricao',false);</script>";
            }
          }else{
            if (isset($tipo)) {
	            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true,$tipo);</script>";
            } else {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
            }
          }
        }else{
	       echo "<script>".$funcao_js."('',false,$tipo);</script>";
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
      
      if( document.getElementById('chave_db82_sequencial').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('chave_db82_sequencial').value ) ) {
          alert('Código Sequencial deve ser preenchido somente com números!');
          document.getElementById('chave_db82_sequencial').value = '';
          return false;  
        }
      }
      
    })();
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_db82_descricao",true,1,"chave_db82_descricao",true);
</script>