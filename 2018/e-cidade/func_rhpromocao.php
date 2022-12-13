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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhpromocao_classe.php");

$oGet = db_utils::postmemory($HTTP_GET_VARS);

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhpromocao = new cl_rhpromocao;
$clrhpromocao->rotulo->label("h72_sequencial");
$clrhpromocao->rotulo->label("h72_sequencial");
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
            <td width="4%" align="right" nowrap title="<?=$Th72_sequencial?>">
              <?=$Lh72_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input("h72_sequencial",10,$Ih72_sequencial,true,"text",4,"","chave_h72_sequencial");
		         ?>
            </td>
          </tr>
          
          <tr> 
            <td width="4%" align="right" nowrap title="Nome">
             <strong>Matrícula: </strong>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_nome",60,"Nome",true,"text",4,"","chave_z01_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhpromocao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere =  ' 1 = 1 ';
      
      if (isset($sOrigem) && $sOrigem == "avaliacoes") {
        $sWhere .= " and (select count(*) from rhavaliacao where h73_rhpromocao = rhpromocao.h72_sequencial) <> (select h36_intersticio from rhparam)";
      }
      
      /**
       * Lista somente os ativos
       */      
      if ( isset($oGet->lAtivo) ) {
      	$sWhere .= ' and h72_ativo is true ';
      }
      
      if( !isset($pesquisa_chave) ) {
      	
        if( isset($campos) == false) {
        	
           if(file_exists("funcoes/db_func_rhpromocao.php")==true){
             include("funcoes/db_func_rhpromocao.php");
           }else{
             $campos = "rhpromocao.*";
           }
        }
        
        if ( isset($chave_h72_sequencial) && ( trim($chave_h72_sequencial) != "") ){
        	
	         $sql = $clrhpromocao->sql_query(null, $campos,null, "h72_sequencial = {$chave_h72_sequencial} and {$sWhere} ");
        } elseif ( isset($chave_z01_nome) && (trim($chave_z01_nome) != "") ){

	         $sql = $clrhpromocao->sql_query("",$campos,"h72_sequencial"," z01_nome ilike '%$chave_z01_nome%' and {$sWhere}  ");
        } else {        	
           $sql = $clrhpromocao->sql_query("",$campos,"h72_sequencial",$sWhere);
        }
        
        $repassa = array();
        
        if ( isset($chave_h72_sequencial) ) {
          $repassa = array("chave_h72_sequencial"=>$chave_h72_sequencial,"chave_h72_sequencial"=>$chave_h72_sequencial);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        
      } else {
      	
      	/**
      	 * Lista somente os ativos
      	 */
      	if ( isset($oGet->lAtivo) ) {
          $sWhere .= ' and h72_ativo is true ';
      	}
      	
        if ( $pesquisa_chave != null && $pesquisa_chave != "") {
        	
          $result = $clrhpromocao->sql_record($clrhpromocao->sql_query_matricula(null, "*", null, $sWhere. " and rh01_regist = {$pesquisa_chave}"));
          
          if($clrhpromocao->numrows != 0 ){
          	echo $clrhpromocao->sql_query_matricula(null, "*", null, $sWhere. " and rh01_regist = {$pesquisa_chave}"). "<br>";
            db_fieldsmemory($result, 0);
            if (isset($oGet->lPromocao)) {
              echo "<script>".$funcao_js."('$z01_nome');</script>";
            } else {
            	echo "<script>".$funcao_js."('$z01_nome');</script>";
            }
          }else{
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
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
//js_tabulacaoforms("form2","chave_h72_sequencial",true,1,"chave_h72_sequencial",true);
</script>