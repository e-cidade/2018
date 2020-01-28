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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_sanitario_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$clSanitario = new cl_sanitario();
$db_botao    = true;
$lSqlErro    = false;
if(isset($alterar) or (isset($incluir))) {
	
	$rsSanitario = $clSanitario->sql_record($clSanitario->sql_query($y80_codsani));
	$oSanitario  = db_utils::fieldsMemory($rsSanitario, 0);
	
	$clSanitario->y80_codsani 	= $oSanitario->y80_codsani;
	$clSanitario->y80_numbloco 	= $oSanitario->y80_numbloco;
	$clSanitario->y80_numcgm 		= $oSanitario->y80_numcgm;
	$clSanitario->y80_data 			= $oSanitario->y80_data;
	$clSanitario->y80_area 			= $oSanitario->y80_area;
	$clSanitario->y80_codrua 		= $oSanitario->y80_codrua;
	$clSanitario->y80_codbairro = $oSanitario->y80_codbairro; 
	$clSanitario->y80_numero 		= $oSanitario->y80_numero;
	$clSanitario->y80_compl 		= $oSanitario->y80_compl;
	$clSanitario->y80_dtbaixa   = $oSanitario->y80_dtbaixa;
	$clSanitario->y80_depto 		= $oSanitario->y80_depto;
	
	$clSanitario->y80_obs   		= $y80_obs;
	$clSanitario->y80_texto 		= $y80_texto;
	
	$clSanitario->alterar($y80_codsani);
	
	if($clSanitario->erro_status == 0) {
		$lSqlErro == true;
	}
	
} elseif(isset($y80_codsani)) {
	
	$rsSanitario = $clSanitario->sql_record($clSanitario->sql_query($y80_codsani));
	db_fieldsmemory($rsSanitario, 0);
	
}

if(isset($y80_obs) and ($y80_obs != '') ||
   isset($y80_texto) and ($y80_texto != '')) {

	$db_opcao = 2;
} else {
	$db_opcao = 1;
}
?>

<html>
	<head>
	  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
<body bgcolor=#CCCCCC >
<center>
<table>
  <tr> 
    <td> 
    <center>
			<?
				include("forms/db_frmsanitarioobs.php");
		  ?>
    </center>
    </td>
  </tr>
</table>
</center>
</body>

</html>
<?php 
if((!$lSqlErro) and ($clSanitario->erro_msg != '')) {
	db_msgbox($clSanitario->erro_msg);
}
?>