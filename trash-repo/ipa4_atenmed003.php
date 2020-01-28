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
$sql = "select ag30_codigo,ag30_codage,ag30_data,ag30_hora,j01_nome,w03_nome,ag30_regist,ag30_depend,
                     (CASE WHEN ag40_codate IS NULL THEN 'Aguardando' ELSE 'Atendido' END) as situacao
                     from agenate
					 inner join cadastro
					 on w01_regist = ag30_regist
					 inner join cgipa
					 on j01_numero = w01_numcgi
					 left outer join depen
					 on w03_codigo = ag30_depend
					 left outer join atendmed
					 on ag40_codigo = ag30_codigo
                     where ag30_codage = $codage 					 
					 and ag30_data = '$dataini'
					 ";
  $result = pg_exec($sql);
  $numrows = pg_numrows($result);		 
  if($numrows == 0) {
    $DB_MSG = "Não existe atendimento para esta agenda.";
	$DB_VOLTA = 1;
  } else {
  if(date("Y-m-d",db_getsession("DB_datausu")) != $dataini)
    $DB_MSG = "Agenda de outro dia, verifique";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<center>
<table width="100%" border="0" cellpadding="3" cellspacing="1">
<tr bgcolor="#A5F8EF">
  <th nowrap>Código do Atendimento</th>
  <th nowrap>Hora</th>
  <th nowrap>Situação</th>
  <th nowrap>Nome do Funcionário</th>
  <th nowrap>Nome do Dependente</th>
</tr>
<?
  $cor1 = "#7ADCF1";
  $cor2 = "#88BCE3";
  $cor = "";
  for($i = 0;$i < $numrows;$i++) {
    ?>
	<tr bgcolor="<? echo $cor = ($cor==$cor1?$cor2:$cor1) ?>" style="cursor: hand" onClick="parent.location.href='ipa4_atenmed004.php?<?=base64_encode("codigo=".pg_result($result,$i,"ag30_codigo")."&regist=".pg_result($result,$i,"ag30_regist")."&codage=".pg_result($result,$i,"ag30_codage")."&dataini=".$dataini."&depend=".pg_result($result,$i,"ag30_depend"))?>'">
	  <td nowrap><?=pg_result($result,$i,"ag30_codigo")?>&nbsp;</td>
	  <td nowrap><?=pg_result($result,$i,"ag30_hora")?>&nbsp;</td>
	  <td nowrap><?=pg_result($result,$i,"situacao")?>&nbsp;</td>
	  <td nowrap><?=pg_result($result,$i,"j01_nome")?>&nbsp;</td>
	  <td nowrap><?=pg_result($result,$i,"w03_nome")?>&nbsp;</td>	  	  	  	  
	</tr>
	<?
  }
  ?> </table></center> <?
  } // fim do else   if(pg_numrows($result) == 0) {
  ?>
  </body>
</html>
  <?
  if(isset($DB_MSG)) {
  ?>
  <script>
    volta = '<?=@$DB_VOLTA?>';
    parent.alert('<?=$DB_MSG?>');
	if(volta != "")
	  parent.location.href = 'ipa4_atenmed001.php';
  </script>
  <?
  }
  
?>