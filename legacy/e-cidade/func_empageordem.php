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
include("classes/db_empageordem_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempageordem = new cl_empageordem;
$clempageordem->rotulo->label("e42_sequencial");
$clempageordem->rotulo->label("e42_dtpagamento");
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
            <td width="4%" align="right" nowrap title="<?=$Te42_sequencial?>">
              <?=$Le42_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e42_sequencial",10,$Ie42_sequencial,true,"text",4,"","chave_e42_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te42_dtpagamento?>">
              <?=$Le42_dtpagamento?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e42_dtpagamento",10,$Ie42_dtpagamento,true,"text",4,"","chave_e42_dtpagamento");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empageordem.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <? 
      $sWhere = "";
      if (isset($credor) && trim($credor) != '') {
      	
        if ($credor == "true") {
        	
      	  $sWhere = " e94_numcgm is not null ";
        } else {
        	
          $sWhere = " e94_numcgm is null ";
        }
      }
      
      $and = "";
      if ($sWhere != "") {
      	
      	$and = " and ";
      }
      
      if (!isset($pesquisa_chave)) {
      	
        if (isset($campos)==false) {
        	
           if (file_exists("funcoes/db_func_empageordem.php")==true) {
           	
             include("funcoes/db_func_empageordem.php");
           } else {
           	
            $campos = "empageordem.*";
            
          }
        }
        
        if (isset($credor) && trim($credor) != '' && $credor == "true") {
              $campos .= " ,z01_nome";                     
        }
        
        if (isset($chave_e42_sequencial) && (trim($chave_e42_sequencial)!="") ) {

        	 $sWhere .= $and." e42_sequencial = ".$chave_e42_sequencial ;
	         $sql = $clempageordem->sql_query_left(null,$campos,"e42_sequencial desc",$sWhere);
	         
        } else if (isset($chave_e42_dtpagamento) && (trim($chave_e42_dtpagamento)!="") ) {
        	
        	$sWhere .= $and." e42_dtpagamento like '$chave_e42_dtpagamento%' " ;
	        $sql = $clempageordem->sql_query_left("",$campos,"e42_dtpagamento desc", $sWhere);
	         
        } else {
        	
           $sql = $clempageordem->sql_query_left("",$campos,"e42_sequencial desc", $sWhere);
           
        }
        $repassa = array();
        if (isset($chave_e42_dtpagamento)) {
        	
          $repassa = array("chave_e42_sequencial"=>$chave_e42_sequencial,"chave_e42_dtpagamento"=>$chave_e42_dtpagamento);
        }
        //echo $sql;
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {
      	
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
        	
        	$sWhere .= $and." e42_sequencial = ".$pesquisa_chave ;
        	$sql_chave = $clempageordem->sql_query_left($pesquisa_chave,
                                                      "to_char(e42_dtpagamento,'dd/mm/YYYY') as e42_dtpagamento",
        	                                            null,
        	                                            $sWhere 
        	                                           );
          $result = $clempageordem->sql_record($sql_chave);
          
          if ($clempageordem->numrows!=0) {
          	
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e42_dtpagamento',false);</script>";
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
js_tabulacaoforms("form2","chave_e42_dtpagamento",true,1,"chave_e42_dtpagamento",true);
</script>