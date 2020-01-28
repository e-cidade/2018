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
/* echo "<br><br>sel = $ssel4[0] <br> ";
echo " sel = $ssel4[1] <br> ";
echo " sel = $ssel4[2] <br> "; */

echo "<br><br><br>";
print_r($ssel4);
echo "<br><br><br>";
print_r($nsel4);
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
	<tr>
		<td width="140">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b> Motivo</b></td>
	</tr>

	<tr>
		<td align="center"><?
		// filtro = Motivo, data, cliente, técnico, procedimento, modulo

		// motivo
		$sqlmot ="select  at54_sequencial,at54_descr from tarefacadmotivo where at54_tipo = 1 order by at54_descr";
		$resultmot= pg_query($sqlmot);
		db_multiploselect("at54_sequencial", "at54_descr", "nsel1", "ssel1", $resultmot, array(), 4, 250);
		?></td>
	</tr>
	<tr>
		<td width="140">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b> Cliente</b></td>
	</tr>
	<tr>
		<td align="center">
		<?
		//cliente
		$sqlcliente = "select at01_codcli,at01_nomecli from clientes";
		$resultcliente=pg_query($sqlcliente);
		db_multiploselect("at01_codcli", "at01_nomecli", "nsel2", "ssel2", $resultcliente, array(), 4, 250);
		?>
		</td>
	</tr>
	<tr>
		<td width="140">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b> Técnico</b></td>
	</tr>
	<tr>
		<td align="center">
		<?
		//tecnico
		$sqltecnico = "	select distinct id_usuario,nome 
						from tecnico 
						inner join db_usuarios on id_usuario = at03_id_usuario";
		$resulttecnico=pg_query($sqltecnico);
		db_multiploselect("id_usuario", "nome", "nsel3", "ssel3", $resulttecnico, array(), 4, 250);
		?>
		</td>
	</tr>
	<tr>
		<td width="140">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b> Módulo</b></td>
	</tr>
	<tr>
		<td align="center">
		<?
		//Módulo
		$sqlmodulo = "select codmod,nomemod from db_sysmodulo where ativo = 't' order by nomemod";
		$resultmodulo=pg_query($sqlmodulo);
//		db_criatabela($resultmodulo);

		db_multiploselect("codmod", "nomemod", "nsel4", "ssel4", $resultmodulo, array(), 4, 250,'','',true,'js_pegaValores(document.form1.ssel4);');
		?>
		</td>
	</tr>
	<tr>
		<td width="140">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><input name="processa" type="submit" value="Processa"></td>
	</tr>
	
	<?
	db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
	?>
</table>
</form>
</body>
</html>
<script>

function js_pegaValores(obj){
  var lista = '';
  for(x=0;x<obj.length;x++){
    lista += vir+obj.options[x].value;
    vir=",";
  }
  g1.document.form1.o teu hidden.value =  lista;
}

function js_iframeOculto(objorigem,ojbretorno){
  //js_openJanelaIframe();
}

</script>