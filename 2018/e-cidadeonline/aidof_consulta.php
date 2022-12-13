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
include("dbforms/db_funcoes.php");
postmemory($HTTP_POST_VARS);
$sqlcgm="select* from issbase inner join cgm on q02_numcgm= z01_numcgm where q02_inscr=$inscricaow";
$resultcgm=db_query($sqlcgm);
$linhascgm=pg_num_rows($resultcgm);
if ($linhascgm>0){
	db_fieldsmemory($resultcgm,0);
	
}
$sqlaidof= "
			select *,
			case when y08_cancel='t' then 'cancelado' else
			case when y08_quantlib =0 then 'Não liberada' else 'Liberado' 
			end
			end as situacao
			from aidof 
			inner join notasiss on q09_codigo = y08_nota
			where y08_inscr=$inscricaow
		   ";

//die($sqlaidof);
$resultaidof=db_query($sqlaidof);
$linhasaidof=pg_num_rows($resultaidof);

if(isset($cancelar)){
	$sqlcan="update aidof set y08_cancel='t' where y08_codigo=$codigoaidof";
	$resultcan=db_query($sqlcan);
}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<?
db_estilosite();
?>
table.bordasimples {
border-collapse: collapse;
}
table.bordasimples tr td {
	border:1px solid #000000;
}

</style>
</head>

<script>
function js_autorizacao(cod,graf,ins,nota){
	jan = window.open('fis2_emiteaidof002.php?codaidof='+cod,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
	//jan = window.open('aidof_autori.php?codigo='+cod+'&inscricao='+ins+'&grafica='+graf+'&nota='+nota,'','height=500,width=650,scrollbars=1');
}
function js_comprovante(cod,graf,ins,nota){
	jan = window.open('aidof.php?codigo='+cod+'&inscricao='+ins+'&grafica='+graf+'&nota='+nota,'','height=500,width=650,scrollbars=1');
}
function js_cancela(cod){
	document.form1.codigoaidof.value=cod;
}
</script>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bg>
	<form name="form1" method="post" action="" >
		<input name="codigoaidof" type="hidden" value="">
		<br><br><br>
		<div align="center" class="texto"> <b>Solicitações de AIDOF</b><br></div>
		<br>
		<table width="80%" align="center" class="tab" >
		<tr  align="center">
			<th >Código</th>
			<th>Tipo de nota</th>
			<th>Data da solicitção</th>
			<th>Quant. Solicititada</th>
			<th>Quant. Liberada</th>
			<th>Numeração</th>
			<th>Situação</th>
			<th>Opções</th>
		</tr>
		<?
		if($linhasaidof>0){
			for($i = 0;$i < $linhasaidof; $i++){
				db_fieldsmemory($resultaidof,$i);
				if($y08_quantlib=='0'){$y08_quantlib="";}
				echo"	
				<tr  align='center'>
					<td>$y08_codigo</td>
					<td>$q09_descr </td>
					<td>".db_formatar($y08_dtlanc,'d')."</td>
					<td>$y08_quantsol</td>
					<td>$y08_quantlib</td>
					<td>$y08_notain até $y08_notafi</td>		
					<td>$situacao</td>";
				if($situacao=='Liberado'){
					echo"<td><input name='imprime' value='Imprimir autorização' type='submit' class='botao' onclick=\"js_autorizacao($y08_codigo,$y08_numcgm,$y08_inscr,'$q09_descr')\"></td>";
				}elseif($situacao=='Não liberada'){
					echo"<td><input name='imprime' value='Imprimir comprovante' type='submit' class='botao' onclick=\"js_comprovante($y08_codigo,$y08_numcgm,$y08_inscr,'$q09_descr')\">
						<input name='cancelar' value='Cancelar' type='submit' class='botao' onclick='js_cancela($y08_codigo)'>
						</td>";
				}elseif($situacao=='cancelado'){
					echo "<td>&nbsp;</td>";
				}
				echo"</tr>";
				
			}
		}
		?>
		
		</table>
	</form>
</body>
</html>