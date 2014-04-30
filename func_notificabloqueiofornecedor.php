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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_notificabloqueiofornecedor_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clnotificabloqueiofornecedor = new cl_notificabloqueiofornecedor;
$clnotificabloqueiofornecedor->rotulo->label("pc86_sequencial");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
<?
  if (!isset($oGet->notificacao)) {
?>
  <tr> 
    <td height="63" align="center" valign="top">
      <form name="form2" method="post" action="" >
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tpc86_sequencial?>">
              <?=$Lpc86_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		           db_input("pc86_sequencial",10,$Ipc86_sequencial,true,"text",4,"","chave_pc86_sequencial");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" 
                     onClick="parent.db_iframe_notificabloqueiofornecedor.hide();">
             </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
<?
  }
?>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset($oGet->pesquisa_chave)) {
      	
        if (isset($campos) == false) {
          
          if (file_exists("funcoes/db_func_notificabloqueiofornecedor.php") == true) {
            include("funcoes/db_func_notificabloqueiofornecedor.php");
          } else {
            $campos = "notificabloqueiofornecedor.*";
          }
        }
      	
      	if (!isset($oGet->notificacao)) {
	        
	        if (isset($oPost->chave_pc86_sequencial) && (trim($oPost->chave_pc86_sequencial) != "")) {
	          $sSql = $clnotificabloqueiofornecedor->sql_query($oPost->chave_pc86_sequencial, $campos, "pc86_sequencial");
	        } else if (isset($oPost->chave_pc86_sequencial) && (trim($oPost->chave_pc86_sequencial) != "")) {
	          
	          $sWhere = " pc86_sequencial like '{$oPost->chave_pc86_sequencial}%' ";
	          $sSql   = $clnotificabloqueiofornecedor->sql_query(null, $campos, "pc86_sequencial", $sWhere);
	        } else {
	          $sSql = $clnotificabloqueiofornecedor->sql_query(null, $campos, "pc86_sequencial", "");
	        }
      	} else {
      		
      		$sAnd    = "";
      		$sWhere  = "";
      		if (isset($oGet->z01_numcgm) && !empty($oGet->z01_numcgm)) {
      			
      			$sWhere .= " {$sAnd} pc86_numcgm = {$oGet->z01_numcgm} ";
      			$sAnd    = " and ";
      		}
          
      	  if ( isset($oGet->datainicial) && isset($oGet->datafinal) ) {
               	  	
            $dtDataInicial = implode("-", array_reverse(explode("/", $oGet->datainicial)));
            $dtDataFinal   = implode("-", array_reverse(explode("/", $oGet->datafinal)));
            if ( ( !empty($dtDataInicial) ) && ( !empty($dtDataFinal) ) ) {
              
              $sWhere    .= " {$sAnd} pc86_data between '{$dtDataInicial}' and '{$dtDataFinal}' ";
              $sAnd       = " and ";
            } else if ( !empty($dtDataInicial) ) {
              
              $sWhere .= " {$sAnd} ( pc86_data >= '{$dtDataInicial}' ) ";
              $sAnd    = " and ";
            } else if ( !empty($dtDataFinal) ) {
              
              $sWhere .= " {$sAnd} ( pc86_data <= '{$dtDataFinal}' ) ";
              $sAnd    = " and ";
            }  
          }
      		
          $sWhere .= " {$sAnd} not exists ( select 1                                                   ";
          $sWhere .= "                        from notificacaonotificafornecedor                       ";
          $sWhere .= "                       where pc87_notificabloqueiofornecedor = pc86_sequencial ) ";
      		
      		$sSql    = $clnotificabloqueiofornecedor->sql_query(null, $campos, "pc86_sequencial", $sWhere);
      	}
        
        $repassa = array();
        if (isset($oPost->chave_pc86_sequencial)) {
          $repassa = array("chave_pc86_sequencial" => $oPost->chave_pc86_sequencial);
        }

        db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      } else {
      	
        if ($oGet->pesquisa_chave != null && $oGet->pesquisa_chave != "") {
        	
        	$sSql    = $clnotificabloqueiofornecedor->sql_query($oGet->pesquisa_chave);
          $sResult = $clnotificabloqueiofornecedor->sql_record($sSql);
          if ($clnotificabloqueiofornecedor->numrows != 0) {
          	
            db_fieldsmemory($sResult, 0);
            echo "<script>".$funcao_js."('{$pc86_sequencial}',false);</script>";
          } else {
	          echo "<script>".$funcao_js."('Chave(".$oGet->pesquisa_chave.") não Encontrado',true);</script>";
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
if(!isset($oGet->pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}

if (!isset($oGet->notificacao)) {
?>
<script>
  js_tabulacaoforms("form2", "chave_pc86_sequencial", true, 1, "chave_pc86_sequencial", true);
</script>
<?
}
?>