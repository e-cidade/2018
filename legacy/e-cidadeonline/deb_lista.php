<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
postmemory($HTTP_POST_VARS);
//echo"$sqldeb";

$resultdeb=db_query($sqldeb);
$linhasdeb=pg_num_rows($resultdeb);

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<style type="text/css">
<?
db_estilosite()
?>
</style>
<script>
function js_cod(cod){
	document.form1.cod.value = cod;
}
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">

<div align="center" class="texto"> <b><br>Debitos em conta</b> </div>
<br>
<table width='400px' align='center' border='1' cellpadding='1' cellspacing='1'  >
	<form name="form1" method="post" target="">
	<input name="cod" type="hidden" value="">
	<tr class="titulo">
		<td align= 'center'>Codigo do debito</td>
		<td align= 'center'>Data de lançamento</td>
		<td align= 'center'>Opção</td>
	</tr>
	<?for ($i = 0; $i < $linhasdeb; $i++) {
		db_fieldsmemory($resultdeb,$i);
		echo"<tr class='texto'>
			<td align= 'center'> $d63_codigo</td>
			<td align= 'center'> ".db_formatar($d63_datalanc,'d')."</td>
			<td align= 'center'><input name='imprime' value='Imprime' class='botao' type='submit' onclick='js_cod($d63_codigo);'></td>
		</tr>
    	";
	}
	?>
	</form>
</table>
</html>
<?
$numpres="";
if(isset($imprime)){
	$sqlnumpre ="select * from debcontapedidotiponumpre where d67_codigo=$cod";
	$resultnumpre=db_query($sqlnumpre);
	$linhasnumpre= pg_num_rows($resultnumpre);
	for ($i = 0; $i < $linhasnumpre; $i++) {
		db_fieldsmemory($resultnumpre,$i);
		$numpres= $numpres."N".$d67_numpre."P".$d67_numpar;
	}
	//echo"<br> num = $numpres";
	//echo"<br> cod = $cod";
	//echo"<br> cgm = $d70_numcgm";
	//echo"<br> ban = $d63_banco";
	if (isset($d68_matric)){
		$tipomi="MATRICULA";
		$mat_ins = $d68_matric;
	}else{
		$tipomi="INSCRIÇÃO";
		$mat_ins = $d69_inscr;
	}
	//echo"<br> tip = $tipomi";
	//echo"<br> mat = $mat_ins";
	echo "<script>
		  	window.open('debito_relatorio.php?tipomi=$tipomi&mat_ins=$mat_ins&cgm=$d70_numcgm&numpres=$numpres&cod=$cod&banco=$d63_banco','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		  </script>";
	
}

?>