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
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oGet   = db_utils::postMemory($_GET);
$oJson  = new services_json();

$aRetornoSQL= unserialize($_SESSION['sqlGerador']);
unset($_SESSION['sqlGerador']);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
  <form name="form1" method="post" action="">
	  <table style="padding-top:15px;">
	    <tr> 
	      <td> 
					<fieldset>
					  <legend align="center">
					  	<b>Retorno Consulta:</b>
					  </legend>
            <div style="width:700px; overflow:auto;">
				      <table  cellspacing="0" style="border:2px inset white;width:700px;" >
				        <tr>
				         <?
				             
				             $aCampos = array_keys($aRetornoSQL[0]);
	                  
				             foreach ( $aCampos as $iInd => $sNomeCampo ) {
			                 echo "<th class='table_header' nowrap><b>{$sNomeCampo}</b></th>";
				             }
				             
				             echo "<th class='table_header' width='12px' ><b>&nbsp;</b></th>";
				             
				         ?>
				        </tr>  
				        <tbody style="width:700px;height:250px; overflow:scroll; overflow-x:hidden; background-color:white"  >
				          <?
				          
	                   foreach ( $aRetornoSQL as $iIndLinha => $aLinhas ){
	                   	  
	                   	 foreach ( $aLinhas as $iIndCampo => $sValorCampo ) {
	                   	 	
			                   if ( trim($sValorCampo) == '' ) {
			                   	 $sValorCampo = '&nbsp;'; 
			                   }
	                   	 	 
	                  	 	 echo "<td class='linhagrid' style='text-align:left;' nowrap>{$sValorCampo}</td>";
	                   	 }
	                   	 echo "</tr>";
	                   }
                  	 echo "<tr><td style='height:100%;'>&nbsp;</td></tr>";
                  	 
				          ?>
				        </tbody>
				      </table>
			      </div>
					</fieldset>
	      </td>
	    </tr>
	    <tr align="center">
	      <td>
	      	<input name="fechar" type="button" value="Fechar" onClick="parent.db_iframe_sql.hide();"/>
	      </td>
	    </tr>
	  </table>
  </form>
</center>
</body>
</html>