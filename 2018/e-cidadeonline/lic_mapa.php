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

include("libs/db_stdlib.php");
include("libs/db_sql.php");
db_postmemory($HTTP_SERVER_VARS);
?>

<html>
<head>
<title>Licitações</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>

</script>
</head>
<body bgcolor="<?=$w01_corbody?>" >
<?

$sql = "select   l03_descr,  l20_objeto,  l20_codtipocom, l20_numero,l27_arquivo,
		'edital_'||l03_tipo||'_'||l20_numero||'_'||to_char(l20_dtpublic, 'yyyymmdd')||'.pdf' as l99_nomearq
		from     liclicita 
		inner join cflicita on l03_codigo=l20_codtipocom 
		inner join liclicitaedital on l20_codigo = l27_liclicita
		where l27_publico = 't'  and l27_tipo_anexo = 2";

//die($sql); //l20_dtpublic >= '2006-07-01'
$result = db_query($sql);
$lin = pg_num_rows($result);

?>
<table width="100%" border="0" align= "center">
obs: ta mostrando todos com permição para publicar...idependente da data....
	<tr><td align="center"><b>LICITAÇÕES EM ABERTO</b></td></tr><br>
</table>

<table width="90%" border="0" align= "center">
	<form name="form1" method="post" action="">
	<?
	if ($lin>0){
		for ($i = 0; $i < $lin; $i++) {
			db_fieldsmemory($result, $i);
		
		echo "<tr bgcolor='$w01_corfundomenu'>
				<td>$l03_descr Nº $l20_numero </td>
			  </tr>
			  <tr><td>Objeto: $l20_objeto </td></tr>";
			  echo"<tr><td><a href='http://192.168.0.36/dbportal2/tmp/$l99_nomearq' target='_blank'> Baixar Mapa comparativo de preço </a></td></tr>";
			  echo"<tr><td>   </td></tr>";	
		}	
	}
	?>
	</form>
</table>

</body>
</html>