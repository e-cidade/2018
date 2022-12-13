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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_sysfuncoes_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if (!isset($chave_codfuncao)){
  $chave_codfuncao = '';
}

$chave_nomefuncao = isset($chave_nomefuncao) ? stripslashes($chave_nomefuncao) : '';

if ( isset($chave_codfuncao) && !DBNumber::isInteger($chave_codfuncao) ) {
  $chave_codfuncao = '';
}

$cldb_sysfuncoes = new cl_db_sysfuncoes;
$cldb_sysfuncoes->rotulo->label("codfuncao");
$cldb_sysfuncoes->rotulo->label("nomefuncao");

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
            <td width="4%" align="right" nowrap title="<?=$Tcodfuncao?>">
              <?=$Lcodfuncao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("codfuncao",5,$Icodfuncao,true,"text",4,"","chave_codfuncao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tnomefuncao?>">
              <?=$Lnomefuncao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("nomefuncao",100,$Inomefuncao,true,"text",4,"","chave_nomefuncao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_sysfuncoes.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $chave_nomefuncao = addslashes($chave_nomefuncao);

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_db_sysfuncoes.php")==true){
             include("funcoes/db_func_db_sysfuncoes.php");
           }else{
           $campos = "db_sysfuncoes.*";
           }
        }

        if( isset($chave_codfuncao) ){
          if (  !DBNumber::isInteger($chave_codfuncao) ) {
            $chave_codfuncao = '';
          }
        }

        if(isset($chave_codfuncao) && (trim($chave_codfuncao)!="" && DBNumber::isInteger($chave_codfuncao)) ){
	         $sql = $cldb_sysfuncoes->sql_query($chave_codfuncao,$campos,"codfuncao desc");
        }else if(isset($chave_nomefuncao) && (trim($chave_nomefuncao)!="") ){
	         $sql = $cldb_sysfuncoes->sql_query("",$campos,"codfuncao desc"," nomefuncao like '$chave_nomefuncao%' ");
        }else{
           $sql = $cldb_sysfuncoes->sql_query("",$campos,"codfuncao desc","");
        }
        $repassa = array();
        if(isset($chave_nomefuncao)){
          $repassa = array("chave_codfuncao"=>$chave_codfuncao,"chave_nomefuncao"=>$chave_nomefuncao);
        }

       if( isset($chave_nomefuncao) ){
          $chave_nomefuncao = str_replace("\\", "", $chave_nomefuncao);
        }

        db_lovrot($sql,100,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_sysfuncoes->sql_record($cldb_sysfuncoes->sql_query($pesquisa_chave));
          if($cldb_sysfuncoes->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$nomefuncao',false);</script>";
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
    (function(){
      
      if( document.getElementById('chave_codfuncao').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('chave_codfuncao').value ) ) {
          alert('Campo Código Função deve ser preenchido somente com números!');
          document.getElementById('chave_codfuncao').value = '';
          return false;  
        }
      }
      
    })();
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_nomefuncao",true,1,"chave_nomefuncao",true);
</script>