<?php
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
	$fonteorig="";
	$sqltab ="select * from pg_tables where tablename = 'temp_classeatualiza'";
	$resulttab = pg_query($sqltab);
	$linhatab = pg_num_rows($resulttab);
	if($linhatab>0){
		$sql = "select * from temp_classeatualiza where seq = $seq";
		$result = pg_query($sql);
		$linha = pg_num_rows($result);
		db_fieldsmemory($result,0);
		
	}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style >
table.tab {
	border-collapse: collapse;
}
table.tab th {
	border:1px solid #000000;
	background-color: #666666;
	color: #FFFFFF;
}
table.tab td {
	border:1px solid #000000;
	background-color: #e4e4e4;
	color: #000000;
}
</style>
</head>
<script type="text/javascript">
function js_codigo(seq){
	js_OpenJanelaIframe('top.corpo','db_iframe_codigo','func_codigo.php?seq='+seq,'Código',true);
}
</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<form name="form1" action="">
<br>
<table width="90%" border="1" cellpadding="2" cellspacing="2" class="tab" align="center">
<tr>
	<td align="center"><b> Classe <?=$nomearq?></b> 
	</td>
</tr>
<tr>
	<td> <b> Método : </b><?=$metodo?>
	</td>
</tr>
<tr>
	<td><b> Fonte original :</b><br> <?=highlight_string("<?\n$fonteorig\n?>");?>
	</td>
</tr>
<tr>
	<td><b> Fonte Novo : </b><br><?=highlight_string("<?\n$fontenovo\n?>");?>
	</td>
</tr>

</table>
<br><center>
<input name="voltar" type="button" value="Voltar" onclick = "parent.db_iframe_codigo.hide();">
</center>
</form>
</body>
</html>