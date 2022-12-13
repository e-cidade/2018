<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
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
	marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#5786B2">
	<tr>
		<td width="360">&nbsp;</td>
		<td width="263">&nbsp;</td>
		<td width="25">&nbsp;</td>
		<td width="140">&nbsp;</td>
	</tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0"
	cellpadding="0">
	<tr>
		<td height="430" align="left" valign="top" bgcolor="#CCCCCC">
		<center>
		<form name="form1" method="post">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td height="30"><strong>Considerar CNPJ com débito não vencido como apto?</strong></td>
				<td height="30"><?
				$arr_aptos_deb = array("n"=>"Não","s"=>"Sim");
				db_select("cons_debvenc",$arr_aptos_deb,true,"text",1);
				?></td>
			</tr>
			<tr>
				<td height="30"><strong>Tipo:</strong></td>
				<td height="30"><?
				$arr_aptos_deb = array("1"=>"aptos ao simples","2"=>"Não Aptos ao Simples");
				db_select("tipo",$arr_aptos_deb,true,"text",1);
				?></td>
			</tr>
			<tr>
				<td><b>Data:</b></td>
				<td><?db_inputdata('data','','','',true,'text',1,""); ?></td>
			</tr>

			<tr>
				<td height="30" colspan=2 align="center"><input name="emite"
					onClick="return js_relatorio()" type="button" id="emite"
					value="Emite Relatório"></td>
			</tr>
			<tr>
				<td height="25" colspan=2 align="center"></td>
			</tr>

		</table>
		<script>
			function js_relatorio(){
			  obj = document.form1;
        var data =  obj.data_ano.value+'-'+obj.data_mes.value+'-'+obj.data_dia.value;
			  js_OpenJanelaIframe('','db_iframe_relatorio','iss2_simples002.php?cons_debvenc='+document.form1.cons_debvenc.value+'&data='+data+'&tipo='+document.form1.tipo.value,'',true);

			}
       </script>
       </form>
		</center>
		</td>
	</tr>
</table>
<?php
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>