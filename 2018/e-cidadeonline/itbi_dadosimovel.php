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
include("classes/db_itbidadosimovel_classe.php");
$clitbidadosimovel = new cl_itbidadosimovel;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
postmemory($HTTP_POST_VARS);
$cod=@$_SESSION["itbi"];
if(isset($alterar)){
	msgbox("alterar");
	$clitbidadosimovel->it22_sequencial  = $it22_sequencial;
	$clitbidadosimovel->it22_setor       = $it22_setor;
	$clitbidadosimovel->it22_quadra      = $it22_quadra;
	$clitbidadosimovel->it22_lote        = $it22_lote;
	$clitbidadosimovel->it22_descrlograd = $it22_descrlograd;
	$clitbidadosimovel->it22_numero      = $it22_numero;
	$clitbidadosimovel->it22_compl       = $it22_compl;
	$clitbidadosimovel->alterar($it22_sequencial);
	
}
if(isset($incluir)){ 
	msgbox("incluir");
	$clitbidadosimovel->it22_itbi 		 = $cod;
	$clitbidadosimovel->it22_setor       = $it22_setor;
	$clitbidadosimovel->it22_quadra      = $it22_quadra;
	$clitbidadosimovel->it22_lote        = $it22_lote;
	$clitbidadosimovel->it22_descrlograd = $it22_descrlograd;
	$clitbidadosimovel->it22_numero      = $it22_numero;
	$clitbidadosimovel->it22_compl       = $it22_compl;
	$clitbidadosimovel->incluir(null);
}
?>
<html>
<style type="text/css">
<?db_estilosite(); ?>
</style>
<script>
function js_verifica(){
	
}
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" >
<form name="form1" method="post" action="">
<input name="it22_sequencial" type="hidden" >
<table width="70%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr class="titulo">
    	<td colspan="2" align="center" >
    	Dados do Imóvel
      	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" width="30%">Número da guia de ITBI:
    	</td>
    	<td align="left" > <?=@$cod?>
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Setor:
    	</td>
    	<td align="left" ><input name="it22_setor" type="text" size="30">
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Quadra:
    	</td>
    	<td align="left" ><input name="it22_quadra" type="text" size="30">
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Lote:
    	</td>
    	<td align="left" ><input name="it22_lote" type="text" size="30">
    	</td>
  	</tr>
  	<tr class="texto"> 
    	<td align="left" >Logradouro:
    	</td>
    	<td align="left" ><input name="it22_descrlograd" type="text" size="60">
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Numero
    	</td>
    	<td align="left" ><input name="it22_numero" type="text" size="30">
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Complemento:
    	</td>
    	<td align="left" ><input name="it22_compl" type="text" size="60" >
    	</td>
  	</tr>
  	<tr >
    	<td align="left" >&nbsp;
    	</td>
    	<td align="left" >
    	<?
    	if($cod!=""){
			$sql= "select * from itbidadosimovel where it22_itbi =$cod";
			$result = db_query($sql);
			$linhas=pg_num_rows($result);
			if($linhas>0){ 
				msgbox("xxxxxx".$linhas);
				db_fieldsmemory($result,0);
		?>
    	
    		
    		<input name="alterar" type="submit" value="Alterar" class="botao" onclick="js_verifica();">
    	</td>
  	</tr>
  	
</table>
</form>
<html>
<?
		
		echo"
		<script>
			document.form1.it22_sequencial.value =$it22_sequencial;
			document.form1.it22_setor.value      =$it22_setor;
			document.form1.it22_quadra.value     =$it22_quadra ;
		 	document.form1.it22_lote.value       =$it22_lote ;
			document.form1.it22_descrlograd.value='$it22_descrlograd';
			document.form1.it22_numero.value     =$it22_numero;
			document.form1.it22_compl.value      ='$it22_compl';  
		</script>
		";

			}else{
			?>
			<input name="incluir" type="submit" value="Incluir" class="botao">
			</td>
  	</tr>
  	
</table>
</form>
<html>
<?
			}
    	}
?>