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
include("classes/db_empautitem_classe.php");
$clempautitem = new cl_empautitem;
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
<body >

<table width="600px" border="1" cellspacing="0" cellpadding="0" style="border-bottom: 0px" align= "center">

	<form name="form1" method="post" action="">
	obs: coloquei só os do mês de agosto, tem que ver quais serão mostradas
	<tr bgcolor="<?=$w01_corfundomenu?>">
		<td> Licitação</td>
		<td> Data de Adjudicação</td>
	</tr>
<?
/*
$result_dataaut=$clempautitem->sql_record($clempautitem->sql_query_lic(null,null,"distinct e54_emiss,e54_autori","e54_autori","l20_codigo=36"));  
die ($clempautitem->sql_query_lic(null,null,"distinct e54_emiss,e54_autori","e54_autori","l20_codigo=36"));
if($clempautitem->numrows>0){// die("nnnnnnnn");
db_fieldsmemory($result_dataaut,0);	
echo "data de adjudicação: $e54_emiss ";

}	*/

$sql = "select distinct  e54_emiss, l03_descr,  l20_numero , l20_codigo 
from empautitem 
inner join liclicitem on liclicitem.l21_codpcprocitem = empautitem.e55_sequen 
inner join liclicita on liclicitem.l21_codliclicita = liclicita.l20_codigo 
inner join cflicita on liclicita.l20_codtipocom = cflicita.l03_codigo 
inner join empautoriza on empautoriza.e54_autori = empautitem.e55_autori 
where  e54_emiss>'2006-08-01'";

$result= db_query($sql);
$linhas=pg_num_rows($result);
echo"linhas = $linhas";
for ($i = 0; $i < $linhas; $i++){
    	db_fieldsmemory($result,$i);
    	$data = (db_formatar($e54_emiss,"d"));
    echo " 
    <tr>
		<td>$l03_descr N $l20_numero</a></td>
		<td>$data</td>
	</tr>";
    }

?>
	</form>
</table>
</html>