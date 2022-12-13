<?php
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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_depart_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldbdepart = new cl_db_depart;

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="estilos.css" rel="stylesheet" type="text/css">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
	</head>
	<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table height="100%" border="0" width="100%" align="center" cellspacing="0" bgcolor="#CCCCCC">
		  <tr> 
		    <td height="63" align="center" valign="top">
			    <form name="form2" method="post" action="">
			    	<table width="100%" border="0" align="center" cellspacing="0">
			    		<tr>
			    			<td colspan="4" align="center">
			    				<b>Código:</b><input type="text" id="iCodigoDepartamento" name="iCodigoDepartamento" value=""/>
			    			</td>
			    		</tr>
			    		<tr>
			      		<td colspan="4" align="center">
			      			<input name="Pesquisar" type="submit" id="Pesquisar" value="Pesquisar" >
			       			<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_departamento.hide();">
			       		</td>
			    		</tr>
			    	</table>
			    </form>
		    </td>
		  </tr>
		  <tr> 
		    <td align="center" valign="top"> 
		      <?php
		      $sCampos = "coddepto, descrdepto";
		      $sOrder  = "coddepto";
		      if (!isset($pesquisa_chave)) {
		      	
						$sWhere = "";
		      	if (isset($iCodigoDepartamento) && $iCodigoDepartamento != "") {
		      		$sWhere = " coddepto = {$iCodigoDepartamento}";
		      	}
		      	
		        $sSql = $cldbdepart->sql_query_file(null, $sCampos, $sOrder, $sWhere);
		        db_lovrot($sSql, 15, "()", "", $funcao_js);
		      } else {
		      	
		        if ($pesquisa_chave != null && $pesquisa_chave != "" ) {
		        	
		          $sWhere = " coddepto = $pesquisa_chave";
		          $sSql = $cldbdepart->sql_query_file(null, $sCampos, $sOrder, $sWhere);
		      	  $result = $cldbdepart->sql_record($sSql);
		          if ($cldbdepart->numrows != 0) {
		          	
		            db_fieldsmemory($result,0);
		            echo "<script>".$funcao_js."('$descrdepto',false);</script>";
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
if(!isset($pesquisa_chave)) {
  ?>
  <script>
  </script>
  <?
}
?>