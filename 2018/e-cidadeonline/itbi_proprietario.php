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
include("classes/db_itbipropriold_classe.php");
$clitbipropriold   = new cl_itbipropriold;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
postmemory($HTTP_POST_VARS);
$cod=@$_SESSION["itbi"];


	if(isset($pesq)){
		$sqlcgm="select * from cgm where z01_cgccpf= $cpf";	
		
		$resultcgm=db_query($sqlcgm);
		$linhascgm=pg_num_rows($resultcgm);
		if($linhascgm > 0){
			db_fieldsmemory($resultcgm,0);
			
		}else{
			msgbox("CPF/CNPJ inválido, verificar se este possui cadastro na Prefeitura.");
		}
	}
	

if(isset($incluir)){
	
	$clitbipropriold-> it20_guia   = $cod;
	$clitbipropriold-> it20_numcgm = $z01_numcgm;
	$clitbipropriold-> it20_pri    = $it20_pri;
	$clitbipropriold->incluir($cod,$z01_numcgm);

}
if(isset($excluir)){
	$clitbipropriold->excluir($cod,$cgm);
}

?>

<html>
<style type="text/css">
<?db_estilosite(); ?>
</style>
<script>
	function js_excluir(cgm){
		document.form1.cgm.value =cgm;
	}
</script>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" >
<form name="form1" method="post" action="">
<input name='z01_numcgm' type='hidden' value='<?=@$z01_numcgm?>'>
<input name='cgm' type='hidden' value=''>
<table width="70%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr class="titulo">
    	<td colspan="2" align="center" >
    	Dados do Proprietário
      	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" width="30%">Número da guia de ITBI:
    	</td>
    	<td align="left" > <?=@$cod?>
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >CNPJ/CPF:
    	</td>
    	<td align="left" ><input name="cpf" type="text" size="18">
    	            	  <input type="submit" name="pesq" value="Pesquisar" class="botao">
       	</td>
  	</tr>
  	<?
  	if(isset($z01_nome)){
  		echo"
  	<tr class='texto''>
	    <td align='left' >Nome:
	   	</td>
	   	<td align='left' >$z01_nome
	    </td>
	 </tr>";
  	}
  	?>
  	<tr class="texto">
    	<td align="left" width="30%">Principal:
    	</td>
    	<td align="left" > 
    	<select name="it20_pri"  >
          <option value='t'>Sim</option>
          <option value='f'>Não</option>
	    </select>
    	</td>
  	</tr>
  	<tr class="texto">
    	<td colspan="2" align="center"><input name="incluir" type="submit" value="Incluir" class="botao">
    	</td>
  	</tr>
  	<tr>
  	<table width="750px"  cellspacing="2" cellpadding="2" align="center" class="tab">
			<tr class="titulo">
    			<th align="center" >
    			Nome
      			</th>
      			<th align="center" >
    			CPF/CNPJ
      			</th>
      			<th align="center" >
    			Principal
      			</th>
      			<th align="center" >
    			Opções
      			</th>
  			</tr>
  			<?
  			if($cod!=""){
					$sql= "select * from itbipropriold inner join cgm on it20_numcgm=z01_numcgm where it20_guia=$cod";
					$result = db_query($sql);
					$linhas=pg_num_rows($result);
					if($linhas>0){
						for($i=0;$i<$linhas;$i++){
							db_fieldsmemory($result,$i);
							if($it20_pri=="t"){
								$pri = "sim";
							}else{
								$pri = "não";
							}
							echo"
							<tr>
							<td>$z01_nome</td>
							<td>$z01_cgccpf</td>
							
						    <td>$pri</td>
							<td>
								
								<input name='excluir' type='submit' value='Excluir' class='botao' onclick=\"js_excluir('$z01_numcgm');\" >
							</td>
							</tr>
							";
						}
				
					}
				}
				?>
  
  	</table>
  	</tr>
  	   		
</table>
</form>
<html>