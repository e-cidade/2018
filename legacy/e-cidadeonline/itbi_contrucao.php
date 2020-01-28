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
include("classes/db_itbiconstr_classe.php");
include("classes/db_itbiconstrespecie_classe.php");
include("classes/db_itbiconstrtipo_classe.php");
$cl_itbiconstr= new cl_itbiconstr;
$cl_itbiconstrespecie =new cl_itbiconstrespecie;
$cl_itbiconstrtipo= new cl_itbiconstrtipo;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
postmemory($HTTP_POST_VARS);
$cod=@$_SESSION["itbi"];

if(isset($incluir)){
	if($incluir=="Incluir"){
		db_inicio_transacao();
		$sqlerro = false;
		$cl_itbiconstr-> it08_guia      = $cod;
		$cl_itbiconstr-> it08_area      = $it08_area;
		$cl_itbiconstr-> it08_areatrans = $it08_areatrans;
		$cl_itbiconstr-> it08_ano       = $it08_ano;
		$cl_itbiconstr-> it08_obs       = $it08_obs;
		$cl_itbiconstr-> incluir(null);
	
		if ($cl_itbiconstr->erro_status == 0) {
			$sqlerro = true;
			die($cl_itbiconstr->erro_sql);
			$erro_msg = $cl_itbiconstr->erro_msg;
		}
			
		if($sqlerro == false) {
	
			$cl_itbiconstrespecie-> it09_codigo = $cl_itbiconstr->it08_codigo;
			$cl_itbiconstrespecie-> it09_caract = $especie;
			$cl_itbiconstrespecie-> incluir($cl_itbiconstr->it08_codigo);
			
			if ($cl_itbiconstrespecie->erro_status == 0) {
				$sqlerro = true;
				die($cl_itbiconstrespecie->erro_sql);
				$erro_msg = $cl_itbiconstrespecie->erro_msg;
			}
			
			if($sqlerro == false) {
			
				$cl_itbiconstrtipo->it10_codigo = $cl_itbiconstr->it08_codigo;
				$cl_itbiconstrtipo->it10_caract = $tipo;
				$cl_itbiconstrtipo->incluir($cl_itbiconstr->it08_codigo);
				
				if ($cl_itbiconstrtipo->erro_status == 0) {
					$sqlerro = true;
					die($cl_itbiconstrtipo->erro_sql);
					$erro_msg = $cl_itbiconstrtipo->erro_msg;
				}
			}
			
			
		}
	
	db_fim_transacao($sqlerro);	
	}
}

if(isset($incluir)){
	if($incluir=="Alterar"){
		$cl_itbiconstr-> it08_codigo    = $it08_codigo;
		$cl_itbiconstr-> it08_area      = $it08_area;
		$cl_itbiconstr-> it08_areatrans = $it08_areatrans;
		$cl_itbiconstr-> it08_ano       = $it08_ano;
		$cl_itbiconstr-> it08_obs       = $it08_obs;
		$cl_itbiconstr-> alterar($it08_codigo);
	
		$cl_itbiconstrtipo->it10_codigo = $it08_codigo;
		$cl_itbiconstrtipo->it10_caract = $tipo;
		$cl_itbiconstrtipo->alterar($it08_codigo);
		
		$cl_itbiconstrespecie-> it09_codigo = $it08_codigo;
		$cl_itbiconstrespecie-> it09_caract = $especie;
		$cl_itbiconstrespecie-> alterar($it08_codigo);
			
		
	}
}


if (isset($excluir)){
	$cl_itbiconstrespecie->excluir($it08_codigo);
	$cl_itbiconstrtipo   ->excluir($it08_codigo);
	$cl_itbiconstr       ->excluir($it08_codigo);
}
?>
<html>
<style type="text/css">
<?db_estilosite(); ?>
</style>
<script>

function js_alterar(cod,area,areatra,ano,obs,esp,tip){
	document.form1.it08_codigo.value       = cod;
	document.form1.it08_area.value      =area;
	document.form1.it08_areatrans.value =areatra;
	document.form1.it08_ano.value       =ano;
	document.form1.obs.value       =obs;
	document.form1.especie.value   =esp;
	document.form1.tipo.value      =tip;
	document.form1.incluir.value= "Alterar";
}

function js_excluir(seq){
	document.form1.it08_codigo.value =s;
}
function js_verifica(){
	var obj      = document.form1;
	var area     = obj.it08_area.value;
	var areatran = obj.it08_areatrans.value;
	var ano      = obj.it08_ano.value;

	
	if(isNaN(area)){
	    alert("verifique o valor informado para o campo Área.");
	    document.form1.it08_area.value="";
	    document.form1.it08_area.focus();
	    return false;
	}
	if(isNaN(areatran)){
	    alert("verifique o valor informado para o campo Área transferida.");
	    document.form1.it08_areatrans.value="";
	    document.form1.it08_areatrans.focus();
	    return false;
	}
	if(isNaN(ano)){
	    alert("verifique o valor informado para o campo Ano.");
	    document.form1.it08_ano.value="";
	    document.form1.it08_ano.focus();
	    return false;
	}
	
	var erro = "";
	if (area=='') erro = erro+' Área\n';
	if (areatran=='') erro = erro+' Área tranferida\n';
	if (ano=='') erro = erro+' Ano\n';
	
		
	if(erro!=""){
		alert('Preencha os Campos: ' +erro);	
		return false;
	}else{
	 return true;
	}
}
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" >
<form name="form1" method="post" action="">
<input name="it08_codigo" type="hidden" >

<table width="770px" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr class="titulo">
    	<td colspan="2" align="center" >
    	Dados da Construção
      	</td>
  	</tr>
 	

  	<tr class="texto">
    	<td align="left" width="20%">Número da guia de ITBI:
    	</td>
    	<td align="left" > <?=@$cod?>
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Área:
    	</td>
    	<td align="left" ><input name="it08_area" type="text" size="20">
    	&nbsp;&nbsp;&nbsp;
    	Área Transferida:
    	<input name="it08_areatrans" type="text" size="20">
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Ano da construção:
    	</td>
    	<td align="left" ><input name="it08_ano" type="text" size="20">
       	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Observação:
    	</td>
    	<td align="left" ><textarea name="it08_obs" cols="60" rows="3" id="obs"></textarea>
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Especie:
    	</td>
    	<td align="left" >
    	<select name="especie"  >
         
          <?
		  $sqlesp ="select * from itbiparespecie";
		  $resultesp =db_query($sqlesp);
		  $linhasesp=pg_num_rows($resultesp);
		  if($linhasesp>0){
			  db_fieldsmemory($resultesp,0);
			  $sqlcar = "select * from caracter where j31_grupo = $it11_grupo ";
		      $resultcar= db_query($sqlcar);
		  	  $linhascar= pg_num_rows($resultcar);
		 	  for($i=0;$i<$linhascar;$i++){
			  db_fieldsmemory($resultcar,$i);
		  	  echo "<option value='$j31_codigo'>$j31_descr</option>";
	 	  }
	  }
	  ?>
        </select>
    	&nbsp;&nbsp;&nbsp;
    	Tipo:
      	<select name="tipo"  >
      <? 
          //die("xxxxxxxxx11");
          //echo "<script> alert('kakakakakk'); </script>";
		  $sqltip    = "select * from itbipartipo";
		  $resulttip = db_query($sqltip);
		  $linhastip = pg_num_rows($resulttip);
		  if($linhastip>0){
		  	// 
		  	  db_fieldsmemory($resulttip,0);
			  $sqlcar1 = "select * from caracter where j31_grupo = $it12_grupo ";
			  //echo "<script> alert('".$it11_grupo."'); </script>";
			  $resultcar1= db_query($sqlcar1);
		  	  $linhascar1= pg_num_rows($resultcar1);
		 	  for($i=0;$i<$linhascar1;$i++){
				  db_fieldsmemory($resultcar1,$i);
			  	  echo "<option value='$j31_codigo'>$j31_descr</option>";
	 	  	  }
	      }
	  ?>
        </select>
    	
    	
    	</td>
  	</tr>
  	<tr>
		<td colspan="2" align="center">
			<input name="incluir" type="submit" value="Incluir" class="botao" onclick="return js_verifica();">
		</td>
  	</tr>
  	<tr >
    	<td colspan="2" align="center" >
    	<br>
    	<table width="90%s"  cellspacing="2" cellpadding="2" align="center" class="tab">
			<tr class="titulo">
			
    			<th align="center" >
    			Código
      			</th>
      			<th align="center" >
    			Área 
      			</th>
      			<th align="center" >
    			Área transferida
      			</th>
      			<th align="center" >
    			Ano
      			</th>
      			<th align="center" >
    			Espécie
      			</th>
      			<th align="center" >
    			Tipo
      			</th>
      			<th align="center" >
    			Opções
      			</th>
  			</tr>
  			<?
  			
  	$sqlcons= "
			select * 
			from itbiconstr 
			inner join itbiconstrespecie on it09_codigo = it08_codigo 
			inner join itbiconstrtipo on it08_codigo=it10_codigo 
			where it08_guia= $cod";
	$resultcons = db_query($sqlcons);
	$linhascons=pg_num_rows($resultcons);
	if($linhascons>0){
		for($i=0;$i<$linhascons;$i++){
			db_fieldsmemory($resultcons,$i);
			$sqlcar1="select j31_descr as esp from caracter where j31_codigo= $it09_caract ";
			$resultcar1 = db_query($sqlcar1);
			db_fieldsmemory($resultcar1,0);
			$sqlcar2="select j31_descr as tip from caracter where j31_codigo= $it10_caract ";
			$resultcar2 = db_query($sqlcar2);
			db_fieldsmemory($resultcar2,0);
			
			echo"
	  			<tr class='texto'>
				
	    			<td align='center' >
	    				$it08_codigo
	      			</td>
	      			<td align='center' >
	    				$it08_area 
	      			</td>
	      			<td align='center' >
	    				$it08_areatrans
	      			</td>
	      			<td align='center' >
	    				$it08_ano
	      			</td>
	      			<td align='center'>
	    				$esp
	    			</td>
	      			<td align='center' >
	    				$tip
	      			</td>
					<td align='center' >
	    				<input name='alterar' type='button' value='Alterar' class='botao' onclick=\"js_alterar('$it08_codigo','$it08_area','$it08_areatrans','$it08_ano','$it08_obs','$it09_caract','$it10_caract');\">
						<input name='excluir' type='submit' value='Excluir' class='botao' onclick=\"js_excluir('$it08_codigo');\" >
	      			</td>
	  			</tr>
				";
		
		}
	}
	
  		
  			?>
  		</table>
    	
    	
      	</td>
  	</tr> 	
    	
  	
</table>
</form>
<html>