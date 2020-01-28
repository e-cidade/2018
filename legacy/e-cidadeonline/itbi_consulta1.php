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
include("libs/db_stdlib.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
postmemory($HTTP_POST_VARS);
?>
<style type="text/css">
<?db_estilosite(); ?>
</style>

<script>
function js_alterar(cod,tip){
	location.href='itbi_principalurbano.php?codigoitbi='+cod+'&tipo='+tip;
 
}
function js_imprime(cod){
	imp = window.open('itbi_recibo.php?itbi='+cod, 'blank');
}
</script>
<?

$sql="
	select it03_seq,it03_guia,it03_nome,it01_finalizado,it03_tipo,it06_matric, 
	case when it14_guia is not null then 'Liberado' else 
	case when it01_finalizado='t' then 'Enviado' else 'Não enviado'
	end 
	end as situacao, 
	case when it06_matric is null then 'rural' else 'urbano' 
	end as tipoitbi 
	from itbi inner join itbinome on it03_guia=it01_guia 
	left join itbimatric on it06_guia = it01_guia 
	left join itbiavalia on it14_guia= it01_guia 
	where it03_cpfcnpj = '$cnpj';
";

//$sql="select it03_seq,it03_guia,it03_nome,it01_finalizado,it03_tipo from itbinome inner join itbi on it03_guia=it01_guia  where it03_cpfcnpj = '$cnpj'";   
	$result= db_query($sql);
	$linhas=pg_num_rows($result);
	if($linhas>0){
		echo"<form name=\"form1\" method=\"post\" action=\"\">
<br>		
<div class='titulo' align='center'>Consulta situação da ITBI</div>
<br>
		<table width=\"90%\" cellpadding=\"5\" cellspacing=\"0\" class=\"tab\" align=\"center\">
		<tr>
			<th>Numero da guia ITBI</th>
			<th>Matricula</th>
			<th>Nome</th>
			<th>Tipo</th>
			<th>Situação</th>
			<th>Imóvel</th>
			<th>Opções</th>
		</tr>

		";
		for($i=0;$i<$linhas;$i++){
			db_fieldsmemory($result,$i);	
			
			if ($it03_tipo=='t'){
				$tipo="Transmitente";
			}else{
				$tipo ="Comprador";
			}
		echo"
		<tr>
			<td align='center'>$it03_guia</td>
			<td align='center'>$it06_matric</td>
			<td align='center'>$it03_nome</td>
			<td align='center'>$tipo</td>
			<td align='center'>$situacao</td>
			<td align='center'>$tipoitbi</td>
			<td align='center'> ";
			if($situacao!="Liberado"){
				echo "<input type='button' name='Alterar' value='Alterar ITBI' class='botao' onclick=\"js_alterar($it03_guia,'$tipoitbi');\">";	
			}else{
				echo "<input type='button' name='imprimir' value='Imprimir ITBI' class='botao' onclick='js_imprime($it03_guia);'>";
			}
		echo"
			</td>
		</tr>
		";
		
		}
		echo"</table>";
	}
?>
</form>