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
<title>Consulta CID</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
</script>
<style>
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
th {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
-->
</style>
</head>
<body>
<input type="button" name="b" value="Fechar" onClick=" parent.document.getElementById('procuracid').style.visibility = 'hidden'">
<center>
<table border="0" cellpadding="0" cellspacing="1">
<tr bgcolor="#47ACFE">
  <th nowrap>Código do CID</th>
  <th nowrap>Descrição</th>
</tr>
<?
if(!empty($codcid))
  $str = " upper(codcid) like upper('$codcid%') ";
else
  $str = " upper(descr) like upper('%$descr%') ";
$result = pg_exec("select codcid,descr from cid10 where $str");
$numrows = pg_numrows($result);
if($numrows > 0) {
  for($i = 0;$i < $numrows;$i++) {
    db_fieldsmemory($result,$i);
	echo "<tr style=\"cursor:hand\" onClick=\"parent.js_inserir('$codcid','$descr')\" bgcolor=\"".($i%2==0?"#96CFFE":"#96BAFE")."\"><td id=\"A$i\" nowrap>$codcid</td><td id=\"B$i\" nowrap>$descr</td></tr>\n";
  }
} else {
  echo "<tr><td colspan=\"2\" align=\"center\">Nenhum registro encontrado</td></tr>";
}
?>
</table>
</center>
<script>
  parent.document.getElementById('procuracid').style.visibility = 'visible';
</script>
</body>
</html>