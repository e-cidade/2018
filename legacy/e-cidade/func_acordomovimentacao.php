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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordomovimentacao_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clacordomovimentacao = new cl_acordomovimentacao;
$clacordomovimentacao->rotulo->label("ac10_sequencial");

$sWhere = "";
$sAnd   = "";
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
            <td width="4%" align="right" nowrap title="<?=$Tac10_sequencial?>">
              <?=$Lac10_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		            db_input("ac10_sequencial",10,$Iac10_sequencial,true,"text",4,"","chave_ac10_sequencial");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_acordomovimentacao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      /**
       * Parametros para os tipos de movimentos homologados
       * 
       *  default - Ambos
       *  1       - Ativos
       *  2       - Inativos
       */
      if (isset($oGet->movimento)) {

      	switch ($oGet->movimento) {
      		
          case 1:
            
            $sWhere .= "{$sAnd} ac25_acordomovimentacaocancela is null ";
            $sAnd    = " and ";
            
            break;
            
          case 2:
            
            $sWhere .= "{$sAnd} ac25_acordomovimentacaocancela is not null ";
            $sAnd    = " and ";
            
            break;
            
          default :

          	$sWhere = "";
            $sAnd   = "";
            
          	break;
      	}
      	
      }
      
      /**
       * Pesquisa por tipo de movimento
       */
      if (isset($oGet->tipo) && !empty($oGet->tipo)) {
      	
        $sWhere .= "{$sAnd} ac10_acordomovimentacaotipo = {$oGet->tipo} ";
        $sAnd    = " and ";
      }
      
      if (!isset($pesquisa_chave)) {
      	
        if (isset($oGet->movimento) && !empty($oGet->movimento)) {
        	
        	$campos  = "acordomovimentacao.ac10_sequencial,                           ";
        	$campos .= "acordomovimentacao.ac10_acordomovimentacaotipo,               ";
        	$campos .= "acordomovimentacaotipo.ac09_descricao,                        ";
//        	$campos .= "acordomovimentacaocancela.ac25_acordomovimentacaocancela,     ";
        	$campos .= "acordomovimentacao.ac10_acordo,                               ";
        	$campos .= "acordomovimentacao.ac10_id_usuario,                           ";
        	$campos .= "acordomovimentacao.ac10_datamovimento,                        ";
        	$campos .= "acordomovimentacao.ac10_hora,                                 ";
        	$campos .= "acordomovimentacao.ac10_obs                                   ";
        	
          if (isset($chave_ac10_sequencial) && (trim($chave_ac10_sequencial) != "") ) {
          	
          	$sWhere .= "{$sAnd} ac10_sequencial = $chave_ac10_sequencial ";
            $sql     = $clacordomovimentacao->sql_query_verificacancelado(null, $campos, "ac10_sequencial", $sWhere);
          } else if (isset($chave_ac10_sequencial) && (trim($chave_ac10_sequencial) !="") ) {

            $sWhere .= "{$sAnd} ac10_sequencial like '$chave_ac10_sequencial%' ";
            $sql     = $clacordomovimentacao->sql_query_verificacancelado(null, $campos, "ac10_sequencial", $sWhere);
          } else {
            $sql    = $clacordomovimentacao->sql_query_verificacancelado("", $campos, "ac10_sequencial", $sWhere);
          }
          
        } else {
        	
	        if(isset($campos)==false){
	          
	           if (file_exists("funcoes/db_func_acordomovimentacao.php") == true) {
	             include("funcoes/db_func_acordomovimentacao.php");
	           } else {
	             $campos = "acordomovimentacao.*";
	           }
	        }
	        
	        if (isset($chave_ac10_sequencial) && (trim($chave_ac10_sequencial) != "") ) {
	          $sql = $clacordomovimentacao->sql_query($chave_ac10_sequencial, $campos, "ac10_sequencial", $sWhere);
	        } else if(isset($chave_ac10_sequencial) && (trim($chave_ac10_sequencial) != "") ) {
	        	 
	        	$sWhere .= "{$sAnd} ac10_sequencial like '$chave_ac10_sequencial%' ";
	          $sql     = $clacordomovimentacao->sql_query("", $campos, "ac10_sequencial", $sWhere);
	        } else {
	          $sql = $clacordomovimentacao->sql_query("", $campos, "ac10_sequencial", $sWhere);
	        }
	        
        }
        
        $repassa = array();
        if (isset($chave_ac10_sequencial)) {
          $repassa = array("chave_ac10_sequencial"=>$chave_ac10_sequencial,
                           "chave_ac10_sequencial"=>$chave_ac10_sequencial);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {
      	
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
        	
        	if (isset($oGet->movimento) && !empty($oGet->movimento)) {
        		
        		$sWhere .= "{$sAnd} ac10_sequencial = $pesquisa_chave ";
        		$sql     = $clacordomovimentacao->sql_query_verificacancelado("", "*", "ac10_sequencial", $sWhere);
        		$result  = $clacordomovimentacao->sql_record($sql);
        	} else {
        		
        		$sql     = $clacordomovimentacao->sql_query($pesquisa_chave);
            $result  = $clacordomovimentacao->sql_record($sql);
        	}
        	
          if ($clacordomovimentacao->numrows!=0) {
          	
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ac10_sequencial',false);</script>";
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
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_ac10_sequencial",true,1,"chave_ac10_sequencial",true);
</script>