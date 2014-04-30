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
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_retencaotiporec_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clretencaotiporec = new cl_retencaotiporec;
$clretencaotiporec->rotulo->label("e21_sequencial");
$clretencaotiporec->rotulo->label("e21_descricao");

$oGet = db_utils::postMemory($_GET);
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
            <td width="4%" align="right" nowrap title="<?=$Te21_sequencial?>">
              <?=$Le21_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e21_sequencial",10,$Ie21_sequencial,true,"text",4,"","chave_e21_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te21_descricao?>">
              <?=$Le21_descricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e21_descricao",10,$Ie21_descricao,true,"text",4,"","chave_e21_descricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_retencaotiporec.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere = ' e21_instit = '.db_getsession("DB_instit");
      
      if ( isset($tipo) && trim($tipo) != '' ) {
      	$sWhere .= " and e21_retencaotiporecgrupo = {$tipo} ";
      }

      if ( isset($oGet->chave_pesquisa_in) && trim($oGet->chave_pesquisa_in) != '' ) {
        $sWhere .= " and e21_sequencial in({$oGet->chave_pesquisa_in})";
      }
      
      if(!isset($pesquisa_chave)){
      	
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_retencaotiporec.php")==true){
             include("funcoes/db_func_retencaotiporec.php");
           }else{
           $campos = "retencaotiporec.*";
           }
        }      
        if(isset($chave_e21_sequencial) && (trim($chave_e21_sequencial)!="") ){
	         $sql = $clretencaotiporec->sql_query(null, $campos, "e21_sequencial", "{$sWhere} and e21_sequencial = {$chave_e21_sequencial}");
        }else if(isset($chave_e21_descricao) && (trim($chave_e21_descricao)!="") ){
	         $sql = $clretencaotiporec->sql_query(null, $campos, "e21_sequencial", " e21_descricao ilike '$chave_e21_descricao%' and {$sWhere}");
        }else{
           $sql = $clretencaotiporec->sql_query(null, $campos, "e21_sequencial", $sWhere);
        }
        $repassa = array();
        if(isset($chave_e21_sequencial)){
          $repassa = array("chave_e21_sequencial" => $chave_e21_sequencial,
                           "chave_e21_descricao"  => $chave_e21_descricao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
   
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clretencaotiporec->sql_record($clretencaotiporec->sql_query(null,
                                                                                 "*",
                                                                                 null,
                                                                                 "e21_sequencial = {$pesquisa_chave} and {$sWhere}"));
          if($clretencaotiporec->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e21_descricao',false,'{$e21_aliquota}');</script>";
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
js_tabulacaoforms("form2","chave_e21_sequencial",true,1,"chave_e21_sequencial",true);
</script>