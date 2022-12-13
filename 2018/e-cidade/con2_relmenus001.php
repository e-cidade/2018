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
		<td width="140">&nbsp;
		<input name="mod" type="hidden" value="" >
		</td>
	</tr>
	<tr>
		<td align="center"><b> Módulos</b></td>
	</tr>
	<tr>
		<td align="center">
		<?
		//Módulo
		$sqlmodulo = "select m.id_item as codmod,nome_modulo as nomemod 
                              from db_modulos m
                                   inner join db_itensmenu i on i.id_item = m.id_item
                              where libcliente is true order by nomemod";
		$resultmodulo=pg_query($sqlmodulo);
//		db_criatabela($resultmodulo);

		db_multiploselect("codmod", "nomemod", "nsel4", "ssel4", $resultmodulo, array(), 20, 250,'','',true,'js_pegaValores(document.form1.ssel4);');
		?>
		</td>
	</tr>
	<tr>
		<td width="140">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><input name="processa" type="button"	value="Processa" onclick="js_mandadados()"></td>
	</tr>	
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</form>
</body>
</html>
<script>

function js_pegaValores(obj){
  var lista = '';
  var vir = '';
  for(x=0;x<obj.length;x++){
    lista += vir+obj.options[x].value;
    vir=",";
  }
  document.form1.mod.value= lista;
  
}

function js_mandadados(){
 var mod = document.form1.mod.value;
 if(mod==''){
 		alert('selecione um ou mais módulos.');
 }else{
    jan = window.open('con2_relmenus002.php?mod='+mod,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 		jan.moveTo(0,0);
 		}

}

</script>