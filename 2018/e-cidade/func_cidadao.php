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
include("classes/db_cidadao_classe.php");
require_once ("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcidadao = new cl_cidadao;
$clcidadao->rotulo->label("ov02_sequencial");
$clcidadao->rotulo->label("ov02_seq");
$clcidadao->rotulo->label("ov02_nome");
$clcidadao->rotulo->label("ov02_cnpjcpf");
$chave_ov02_seq = null;

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
	     <form name="form2" method="post" action="" >
		     <fieldset style="width: 350px;">
		     	 <legend><b>Filtros</b></legend>
		       <table width="35%" border="0" align="center" cellspacing="0">
		         <tr>
		           <td width="4%" align="left" nowrap title="<?=$Tov02_sequencial?>">
		             <?=$Lov02_sequencial?>
		           </td>
		           <td width="96%" align="left" nowrap>
		             <?
		    		        db_input("ov02_sequencial", 10, $Iov02_sequencial, true, "text", 4, "", "chave_ov02_sequencial");
		  		       ?>
		           </td>
		         </tr>
		         <tr>
		           <td width="4%" align="right" nowrap title="<?=$Tov02_nome?>">
		             <?=$Lov02_nome?>
		           </td>
		           <td width="96%" align="left" nowrap>
		             <?
		    		        db_input("ov02_nome", 30, $Iov02_nome, true, "text", 4, "", "chave_ov02_nome");
		  		       ?>
		           </td>
		         </tr>
		         <tr>
		           <td width="4%" nowrap title="<?=$Tov02_cnpjcpf?>">
		             <?=$Lov02_cnpjcpf?>
		           </td>
		           <td width="96%" nowrap>
		             <?
		    		        db_input("ov02_cnpjcpf", 30, $Iov02_cnpjcpf, true, "text", 4, "", "chave_ov02_cnpjcpf");
		  		       ?>
		           </td>
		         </tr>
		       </table>
	        </fieldset>
	        <div>
		        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
		        <input name="limpar" type="reset" id="limpar" value="Limpar" >
		        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cidadao.hide();">
		      </div>
        </form>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      if(!isset($pesquisa_chave)) {

      	$dbWhere = "";
        if (isset($campos) == false) {
          if (file_exists("funcoes/db_func_cidadao.php") == true) {
            include("funcoes/db_func_cidadao.php");
          } else {
            $campos = "cidadao.*";
          }
        }

        if (isset($chave_ov02_sequencial) && (trim($chave_ov02_sequencial)!="") ) {

        	if ($dbWhere == "") {
        		$dbWhere = " ov02_ativo is true and ov02_sequencial = ".$chave_ov02_sequencial;
        	} else {
        		$dbWhere .= " and ov02_ativo is true and ov02_sequencial = ".$chave_ov02_sequencial;
        	}
	        $sql = $clcidadao->sql_query($chave_ov02_sequencial,$chave_ov02_seq,$campos,"ov02_sequencial",$dbWhere);
        } else if (isset($chave_ov02_nome) && (trim($chave_ov02_nome) != "")) {

        	if ($dbWhere == "") {
	        	$dbWhere = " ov02_ativo is true and  ov02_nome like '$chave_ov02_nome%' ";
	        }else{
	        	$dbWhere .= " and ov02_ativo is true and  ov02_nome like '$chave_ov02_nome%' ";
	        }
	        $sql = $clcidadao->sql_query("","",$campos,"ov02_sequencial",$dbWhere);
        } else if (isset($chave_ov02_cnpjcpf) && (trim($chave_ov02_cnpjcpf) != "")) {

        	if ($dbWhere == "") {
	        	$dbWhere = " ov02_ativo is true and  ov02_cnpjcpf like '$chave_ov02_cnpjcpf%' ";
	        }else{
	        	$dbWhere .= " and ov02_ativo is true and  ov02_cnpjcpf like '$chave_ov02_cnpjcpf%' ";
	        }
	        $sql = $clcidadao->sql_query("","",$campos,"ov02_sequencial",$dbWhere);
        } else {

        	if ($dbWhere == "") {
        		$dbWhere = " ov02_ativo is true ";
        	} else {
        		$dbWhere .= " and ov02_ativo is true ";
        	}
          $sql = $clcidadao->sql_query("","",$campos,"ov02_sequencial#ov02_seq",$dbWhere);
        }
        $repassa = array();
        if (isset($chave_ov02_sequencial)) {
          $repassa = array("chave_ov02_sequencial"=>$chave_ov02_sequencial);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

        	$dbWhere = " ov02_ativo is true and ov02_sequencial = ".$pesquisa_chave;

        	if (isset($lPesquisaCpf)) {
            $dbWhere = " ov02_ativo is true and ov02_cnpjcpf = '{$pesquisa_chave}'";
          }

          $result  = $clcidadao->sql_record($clcidadao->sql_query(null,null,"*",null,$dbWhere));
          if ($clcidadao->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ov02_sequencial', '$ov02_nome', false, '$ov02_cnpjcpf');</script>";
          } else {
	          echo "<script>".$funcao_js."('', 'Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
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
<script>
js_tabulacaoforms("form2","chave_ov02_sequencial",true,1,"chave_ov02_sequencial",true);
</script>