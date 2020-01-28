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

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="5000">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_Relatorio(codarq){
    jan = window.open('sys3_modulos002.php?xarquivo='+codarq,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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

	$sql_mod = "select db_sysmodulo.nomemod,
			   db_sysarquivo.codarq,
			   db_sysarquivo.nomearq,
			   db_sysarquivo.descricao,
			   db_sysarquivo.sigla,
			   to_char(db_sysarquivo.dataincl,'DD-MM-YYYY') as dataincl,
			   db_sysarquivo.tipotabela,
			   db_sysarquivo2.nomearq as arqpai,
			   db_sysarquivo.rotulo
		       from db_sysarquivo
	                   	inner join db_sysarqmod
                       			on db_sysarqmod.codarq = db_sysarquivo.codarq
                       inner join db_sysmodulo
                       		on db_sysmodulo.codmod = db_sysarqmod.codmod
		       left outer join db_sysarqarq on db_sysarquivo.codarq = db_sysarqarq.codarq 
		       left outer join db_sysarquivo db_sysarquivo2 on db_sysarqarq.codarqpai = db_sysarquivo2.codarq
                       where db_sysarqmod.codmod = $codmod
                       order by nomearq";
		       
//        echo $sql_mod;exit;

	$result = pg_exec($sql_mod);
	$nomemod = pg_exec("select nomemod from db_sysmodulo where codmod = $codmod");
	$nomemod = pg_result($nomemod,0,0);
     ?>
	<br>
	<h3>Módulo: <?=$nomemod?></h3><Br>
	<input type="button" onClick="history.back()" name="voltar" value="Voltar">
	<center>
	<table border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#8AF96A">
	  <th><u>Nome</u></th>
          <th><u></u></th>
	  <th><u>Label</u></th>
	  <th><u>Descricao</u></th>
	  <th><u>Sigla</u></th>
	  <th><u>Tipo</u></th>
	  <th><u>Tabela Principal</u></th>
	  <th nowrap><u>Data de Inclusão</u></th>
	</tr>
    <?
	$cor1 = "#CAF59A";
	$cor2 = "#B0FDD2";
	$cor = "";
	$numrows = pg_numrows($result);
	for($i = 0;$i < $numrows;$i++) {
	  db_fieldsmemory($result,$i);
	  echo "<tr bgcolor=\"".($cor = $cor==$cor1?$cor2:$cor1)."\" style=\"cursor: hand\" onClick=\"location.href='sys3_campos001.php?".base64_encode("tabela=$codarq")."'\">\n";
      echo "<td style=\"cursor: hand\" onClick=\"location.href='sys3_tabelas001.php?".base64_encode("codmod=$codarq")."'\" title='".$nomearq."'>".substr($nomearq,0,20)."&nbsp;</td>\n";
      echo "<td><input name=\"relatorio\" type=\"button\" id=\"exibir_relatorio\" value=\"P\" onClick=\"js_Relatorio('$codarq')\">&nbsp;</td>\n";
      echo "<td style=\"cursor: hand\" title='".$rotulo."'>".substr($rotulo,0,20)."&nbsp;</td>\n";
      echo "<td style=\"cursor: hand\" onClick=\"location.href='sys3_tabelas001.php?".base64_encode("codmod=$codarq")."'\" title='".$descricao."'>".substr($descricao,0,60)."&nbsp;</td>\n";
      echo "<td>".$sigla."&nbsp;</td>\n";
      echo "<td>".($tipotabela=='0'?'Manutenção':($tipotabela=='1'?'Parâmetro':'Dependente'))."&nbsp;</td>\n";
      echo "<td>".$arqpai."&nbsp;</td>\n";
      echo "<td>".$dataincl."&nbsp;</td>\n";
      echo "</tr>\n";
	}
	?>
	</table>
	</center>    
	</td>
  </tr>
</table>
	<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>