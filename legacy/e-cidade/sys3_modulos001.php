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
if(session_is_registered("tabelacod"))
  session_unregister("tabelacod");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="5000">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_Relatorio(codmod){
    jan = window.open('sys3_modulos002.php?xmodulo='+codmod,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);


  }
</script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
	
	<?
    if(!isset($def)) {
	  $result = pg_exec($conn,"SELECT codmod,nomemod,descricao,to_char(dataincl,'DD-MM-YYYY') as dataincl 
		FROM db_sysmodulo 
		ORDER BY nomemod");
	if(!$result) {
	        print "<BR>Nao foi possivel pesquisar no banco de dados.\n";
	        exit;
	}
	?>
	<center>
	<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#68C6FD">
	  <th><u>Nome</u></th>
	  <th><u></u></th>
	  <th><u>Descricao</u></th>
	  <th><u>Data de Inclusão</u></th>
	</tr>
	<?
	$cor1 = "#A4CCF9";
	$cor2 = "#A4BDF9";
	$cor = "";
	$numrows = pg_numrows($result);
	for($i = 0;$i < $numrows;$i++) {
	  db_fieldsmemory($result,$i);
      echo "<tr bgcolor=\"".($cor = $cor==$cor1?$cor2:$cor1)."\" >\n";
      echo "<td style=\"cursor: hand\" onClick=\"location.href='sys3_tabelas001.php?".base64_encode("codmod=$codmod")."'\"   >".$nomemod."&nbsp;</td>\n";
      echo "<td><input name=\"relatorio\" type=\"button\" id=\"exibir_relatorio\" value=\"P\" onClick=\"js_Relatorio('$codmod')\">&nbsp;</td>\n";
      echo "<td style=\"cursor: hand\" onClick=\"location.href='sys3_tabelas001.php?".base64_encode("codmod=$codmod")."'\"   >".$descricao."&nbsp;</td>\n";		
      echo "<td>".$dataincl."&nbsp;</td>\n";			  
	  echo "</tr>\n";
    }
   ?>
	</table>
	</center>
    <?

} 

  ?>
	</td>
  </tr>
</table>
	<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>