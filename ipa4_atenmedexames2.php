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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
}-->
</style>
<script>
function js_inserir(cod,valor) {
  var F = parent.document.form1;
  F.descr.value = valor;
  F.codigo.value = cod;
  F.incluir.disabled = true;
  F.alterar.disabled = false;
  F.excluir.disabled = false;
  F.descr.focus();
}
function js_procurar(texto) {
  location.href = 'ipa4_atenmedexames2.php?descricao=' + texto;
}
</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="3" topmargin="3" marginwidth="0" marginheight="0">
	<table border="0" width="260" cellpadding="0" cellspacing="1">
	<tr bgcolor="#FFFF80">
	<th>Código</th>
	<th>Descrição</th>
	</tr>
	<?
	if(isset($descricao))			 
	  $sql = "select codexa,descr from exames where upper(descr) like upper('$descricao%') and codmed = ".db_getsession("DB_id_usuario")." order by upper(descr)";
	else
  	  $sql = "select codexa,descr from exames where codmed = ".db_getsession("DB_id_usuario")." order by upper(descr)";
	$result = pg_exec($sql);
	$numrows = pg_numrows($result);
	for($i = 0;$i < $numrows;$i++) {
	  db_fieldsmemory($result,$i);
	  echo "<tr style=\"cursor:hand\" bgcolor=\"".($i%2==0?"#82C0FF":"#B9DCFF")."\" onclick=\"js_inserir(document.getElementById('celcodigo$i').innerText,document.getElementById('celdescr$i').innerText)\"> 
	          <td id=\"celcodigo$i\">".$codexa."</td> <td id=\"celdescr$i\">".trim($descr)."</td> 
			</tr>\n";
    }
	?>
</table>	
</body>
</html>