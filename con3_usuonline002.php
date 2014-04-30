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

//session_start();
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");

?>
<html>
<head>
<script>
function js_iniciar() {
  num = new Number(parent.document.form1.atualizacao.value);
  var tab = document.getElementById("tabUsu");
  parent.document.form1.tabusu.value = (tab.rows.length - 1);
  
  setTimeout("location.reload()",num * 1000);
}
</script>
<style type="text/css">
<!--
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="10" marginwidth="0" marginheight="0" onLoad="js_iniciar();">
<center>
  <table id="tabUsu" border="1" cellpadding="3" cellspacing="0" bordercolor="#FFFFFF">
    <tr bgcolor="#97DCFD">      
      <th>Máquina</th>
      <th>Data</th>
      <th>Hora</th>
	  <th>Tempo Inativo</th>
      <th>Usuário</th>
      <th>Aonde</th>
	  <th>Módulo</th>
    </tr>
    <?
	pg_exec("delete from db_usuariosonline where uol_inativo < ".(time() - 300)." or uol_inativo is null") or die("erro(46) excluindo tabela db_usuariosonline");
	$result = pg_exec("select * from db_usuariosonline order by uol_login");
	$numrows = pg_numrows($result);
	for($i = 0;$i < $numrows;$i++) {
        echo "<tr onclick=\"window.open('con3_usuonline113.php?id_usuario=".pg_result($result,$i,"uol_id")."&usuario=".pg_result($result,$i,"uol_login")."&hora=".pg_result($result,$i,"uol_hora")."&sairfora=2','','height=500,width=410,scrollbars=0')\" style=\"cursor: hand\" bgcolor=\"".($i%2==0?"#31CEB7":"#9EE9DE")."\">
					 <td nowrap>".pg_result($result,$i,"uol_ip")."</td>
					 <td nowrap>".date("d-m-Y",pg_result($result,$i,"uol_hora"))."</td>
					 <td nowrap>".date("H:i:s",pg_result($result,$i,"uol_hora"))."</td>
					 <td nowrap>".date("00:i:s",((time()) - (int)pg_result($result,$i,"uol_inativo")))."</td>
					 <td nowrap>".pg_result($result,$i,"uol_login")."</td>
					 <td nowrap>".(pg_result($result,$i,"uol_arquivo") == "/~dbpref/dbportal2/corpo.php"?"Entrou no Sistema":pg_result($result,$i,"uol_arquivo"))."&nbsp;</td>
					 <td nowrap>".pg_result($result,$i,"uol_modulo")."&nbsp;</td>
			  </tr>\n";
   }			
	/*
	
	$netstat = split("\n",shell_exec("export LANG=pt_BR; ".$DB_NETSTAT." -n --tcp | grep tcp | grep :".$HTTP_SERVER_VARS['SERVER_PORT']));
	$numrows = pg_numrows($result);
	$tamNet = sizeof($netstat);
	$usuarios[0] = "";
	$usuariosLogin[0] = "";
	$cont = 0;
	for($j = 0;$j < $numrows;$j++) {
  	  for($i = 0;$i < $tamNet;$i++) {
	    $coluna = preg_split("/\s+/",$netstat[$i]);
		$coluna[3] = split(":",@$coluna[3]);	
		$coluna[4] = split(":",@$coluna[4]);
		//echo "Src: ".$coluna[3][0]." SrcP: ".$coluna[3][1]." Dest: ".$coluna[4][0]." DestP: ".$coluna[4][1]." Status: ".$coluna[5];
	    if(pg_result($result,$j,"porta") == @$coluna[4][1] && 
		   pg_result($result,$j,"ip") == @$coluna[4][0] && 
		   $coluna[3][1] == $HTTP_SERVER_VARS["SERVER_PORT"] &&
		   $coluna[3][0] == $HTTP_SERVER_VARS["SERVER_ADDR"]) {
	         echo "<tr onclick=\"window.open('con3_usuonline003.php?usuario=".db_getsession("DB_login")."&arquivo=".$coluna[4][0].".".$coluna[4][1]."' ,'','height=500,width=400,scrollbars=0')\" style=\"cursor: hand\" bgcolor=\"".($cor = $cor==$cor1?$cor2:$cor1)."\">
			         <td nowrap>".$coluna[5]."</td>
					 <td nowrap>".@$coluna[4][0]."</td>
					 <td nowrap>".@$coluna[4][1]."</td>
					 <td nowrap>".date("d-m-Y",pg_result($result,$j,"hora"))."</td>
					 <td nowrap>".date("H:i:s",pg_result($result,$j,"hora"))."</td>
					 <td nowrap>".pg_result($result,$j,"login")."</td>
					 <td nowrap>".(pg_result($result,$j,"arquivo") == "/~dbseller/dbportal2/corpo.php"?"Entrou no Sistema":pg_result($result,$j,"arquivo"))."&nbsp;</td>
					 <td nowrap>".pg_result($result,$j,"modulosel")."&nbsp;</td>
				   </tr>\n";
			 break;
		}		
	  }
	}
	*/
	?>
  </table>
</center>
</body>
</html>