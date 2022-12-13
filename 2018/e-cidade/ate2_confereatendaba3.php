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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
	
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" onLoad="a=1">
<form name="form1">
<table width="95%" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#CCCCCC" align="center">
	<tr>
		<td width="360" height="18">&nbsp;</td>
		<td width="263">&nbsp;</td>
		<td width="25">&nbsp;</td>
		<td width="140">&nbsp;</td>
	</tr>
	<tr>

</table>
<table width="95%" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#CCCCCC" align="center">
	<? db_input('mod',40,"",true,'hidden',3,''); ?>
	<tr>
		<td height="30">&nbsp;</td>
	</tr>
	
	<tr>
		<td align="center"><b> Procedimentos</b></td>
	</tr>
	<tr>
		<td align="center">
		<?
		//Procedimento
		if (@$mod !=""){
		$sqlproced= "select codproced,descrproced 
					 from db_syscadproced
					 inner join db_sysmodulo on db_sysmodulo.codmod=db_syscadproced.codmod
					 where db_sysmodulo.codmod in ($mod)
					 order by descrproced";
		//die($sqlproced);
		$resultproced=pg_query($sqlproced);
		db_multiploselect("codproced", "descrproced", "nsel5", "ssel5", $resultproced, array(), 8, 250);
		}else{
			echo"<br>Não existe modulo selecionado.<br>";
		}
		?>
		</td>
	</tr>
	
	
	
</table>
</form>
</body>
</html>