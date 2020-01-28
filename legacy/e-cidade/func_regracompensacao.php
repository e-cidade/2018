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
include("classes/db_regracompensacao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clregracompensacao = new cl_regracompensacao;
$clregracompensacao->rotulo->label("k155_sequencial");
$clregracompensacao->rotulo->label("k155_descricao");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC>
<table align="center">
  <tr> 
    <td>
      <fieldset>
        <legend><strong>Pesquisar</strong></legend>
      <table align="center">
      <form name="form2" method="post" action="" >
      <tr> 
        <td nowrap title="<?=$Tk155_sequencial?>">
              <?=$Lk155_sequencial?>
        </td>
        <td nowrap> 
        <?
          db_input("k155_sequencial",10,$Ik155_sequencial,true,"text",4,"","chave_k155_sequencial");
        ?>
        </td>
      </tr>
      <tr> 
        <td nowrap title="<?=$Tk155_descricao?>">
          <?=$Lk155_descricao?>
        </td>
        <td nowrap> 
        <?
          db_input("k155_descricao",40,$Ik155_descricao,true,"text",4,"","chave_k155_descricao");
        ?>
        </td>
      </tr>
      <tr> 
        <td colspan="2" align="center"> 
          <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
          <input name="limpar" type="reset" id="limpar" value="Limpar" >
          <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_regracompensacao.hide();">
        </td>
      </tr>
      </form>
      </table>
      </fieldset>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
            
      if (!isset($pesquisa_chave)) {
        if (isset($campos)==false) {
           if (file_exists("funcoes/db_func_regracompensacao.php")==true) {
             include("funcoes/db_func_regracompensacao.php");
           } else {
           		$campos = "regracompensacao.*";
           }
        }

        if (isset($tiporegracompensacao) and $tiporegracompensacao != '') {
        	$where = "tiporegracompensacao.k154_sequencial = $tiporegracompensacao";
        } else {
        	$where = '';
        }
        
        if (isset($chave_k155_sequencial) && (trim($chave_k155_sequencial)!="")) {

        	if ($where != '') {
        		$where = "regracompensacao.k155_sequencial = $chave_k155_sequencial and $where";
        	} else {
        		$where = "regracompensacao.k155_sequencial = $chave_k155_sequencial";
        	}
        	
	         $sql = $clregracompensacao->sql_query($chave_k155_sequencial,$campos,"k155_sequencial",$where);
	         
        } else if (isset($chave_k155_descricao) && (trim($chave_k155_descricao)!="")) {
        	
        	 if ($where != '') {
        	 		$where = "k155_descricao like '$chave_k155_descricao%' and $where";
        	 } else {
        	 		$where = "k155_descricao like '$chave_k155_descricao%'";
        	 }
        	 
	         $sql = $clregracompensacao->sql_query("",$campos,"k155_sequencial",$where);
	         
        } else {
        	
           $sql = $clregracompensacao->sql_query("",$campos,"k155_sequencial",$where);
           
        }
        
        $repassa = array();
        if (isset($chave_k155_sequencial) || isset($chave_k155_descricao)) {
          $repassa = array("chave_k155_sequencial"=>$chave_k155_sequencial,"chave_k155_descricao"=>$chave_k155_descricao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
        	
        	
        	if (isset($tiporegracompensacao)) {
        		$where = "regracompensacao.k155_sequencial = $pesquisa_chave and tiporegracompensacao.k154_sequencial = $tiporegracompensacao";
        		$result = $clregracompensacao->sql_record($clregracompensacao->sql_query('','*','k155_sequencial',$where));
        	} else {
        		$where = '';
        		$result = $clregracompensacao->sql_record($clregracompensacao->sql_query($pesquisa_chave));
        	}
        	
          
          
          if ($clregracompensacao->numrows!=0) {
            db_fieldsmemory($result,0);
            
            echo "<script>".$funcao_js."('$k155_descricao',false);</script>";
            
          } else {
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
if (!isset($pesquisa_chave)) {
?>
  <script>
  </script>
<?
}
?>
<script>
js_tabulacaoforms("form2","chave_k155_sequencial",true,1,"chave_k155_sequencial",true);
</script>