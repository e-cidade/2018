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
include("classes/db_itbinome_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
postmemory($HTTP_POST_VARS);
$cod=$_SESSION["itbi"];
$clitbinome = new cl_itbinome;

if(isset($incluir)){
	if($incluir=="Incluir"){
		$clitbinome->it03_guia     = $cod;
		$clitbinome->it03_tipo     = 't';
		$clitbinome->it03_princ    = $it03_princ; 
		$clitbinome->it03_nome     = $it03_nome;
		$clitbinome->it03_sexo     = $it03_sexo;
		$clitbinome->it03_cpfcnpj  = $it03_cpfcnpj;
		$clitbinome->it03_endereco = $it03_endereco;
		$clitbinome->it03_numero   = $it03_numero;
		$clitbinome->it03_compl    = $it03_compl;
		$clitbinome->it03_cxpostal = $it03_cxpostal;
		$clitbinome->it03_bairro   = $it03_bairro;
		$clitbinome->it03_munic    = $it03_munic;
		$clitbinome->it03_uf       = $it03_uf;
		$clitbinome->it03_cep      = $it03_cep;
		$clitbinome->it03_mail     = $it03_mail; 
		$clitbinome->incluir(null);

		
	}
}

if(isset($incluir)){
	if($incluir=="Alterar"){
		
	    //db_msgbox(($principal=='t'?'true':'false'));
	    //$it03_princ = $principal;
		$clitbinome->it03_seq      = $seq;
		//$clitbinome->it03_princ    = ($principal=='t'?'true':'false');
		$clitbinome->it03_princ    = $it03_princ; 
		$clitbinome->it03_nome     = $it03_nome;
		$clitbinome->it03_sexo     = $it03_sexo;
		$clitbinome->it03_cpfcnpj  = $it03_cpfcnpj;
		$clitbinome->it03_endereco = $it03_endereco;
		$clitbinome->it03_numero   = $it03_numero;
		$clitbinome->it03_compl    = $it03_compl;
		$clitbinome->it03_cxpostal = $it03_cxpostal;
		$clitbinome->it03_bairro   = $it03_bairro;
		$clitbinome->it03_munic    = $it03_munic;
		$clitbinome->it03_uf       = $it03_uf;
		$clitbinome->it03_cep      = $it03_cep;
		$clitbinome->it03_mail     = $it03_mail; 
		$clitbinome->alterar($seq);
		
		if ($clitbinome->erro_status == 0) {
		   	die($clitbinome->erro_sql);
		  	$erro_msg = $clitbinome->erro_msg;
		}
	}
}
if (isset($excluir)){
	$clitbinome->excluir($seq);
}
?>



<html>
<head>
<title>Transmitente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<?db_estilosite(); ?>
</style>
<script>
function js_alterar(seq,princ,nome,sexo,cnpj,end,num,compl,cxpostal,bairro,munic,uf,cep,email){
	
	document.form1.it03_princ.value    = princ;
	document.form1.it03_nome.value     = nome;
	document.form1.it03_sexo.value     = sexo;
	document.form1.it03_cpfcnpj.value  = cnpj;
	document.form1.it03_endereco.value = end;
	document.form1.it03_numero.value   = num;
	document.form1.it03_compl.value    = compl;
	document.form1.it03_cxpostal.value = cxpostal;
	document.form1.it03_bairro.value   = bairro;
	document.form1.it03_munic.value    = munic;
	document.form1.it03_uf.value       = uf;
	document.form1.it03_cep.value      = cep;
	document.form1.it03_mail.value     = email;
	document.form1.seq.value           = seq;
	document.form1.incluir.value       = "Alterar";
	
}
function js_excluir(seq){
	document.form1.seq.value           = seq;
}
function js_verifica(){
	var obj    = document.form1;
	var nom    = obj.it03_nome.value;
	var sex    = obj.it03_sexo.value;
	var cpf    = obj.it03_cpfcnpj.value;
	var end    = obj.it03_endereco.value;
	var num    = obj.it03_numero.value;
	var bai    = obj.it03_bairro.value;
	var mun    = obj.it03_munic.value;
	var uf     = obj.it03_uf.value;
	var cep    = obj.it03_cep.value;
	
	if(isNaN(cep)){
	    alert("verifique o valor informado para o campo CEP.");
	    document.form1.it03_cep.value="";
	    document.form1.it03_cep.focus();
	    return false;
	}
	
	var erro = "";
	if (nom=='') erro = erro+' Nome\n';
	if (sex=='') erro = erro+' Sexo\n';
	if (cpf=='') erro = erro+' CPF/CNPJ\n';
	if (end=='') erro = erro+' Endereço\n';
	if (num=='') erro = erro+' Número\n';
	if (bai=='') erro = erro+' Bairro\n';
	if (mun=='') erro = erro+' Município\n';
	if (uf =='') erro = erro+' UF\n';
	if (cep=='') erro = erro+' CEP\n';
		
	if(erro!=""){
		alert('Preencha os Campos: ' +erro);	
		return false;
	}else{
	 return true;
	}
}

</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" >
<form name="form1" method="post" action="">
<input name="seq" type="hidden" >
<table width="770px" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr class="titulo">
    	<td colspan="2" align="center" >
    	Dados do Transmitente
      	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" width="20%">Número da guia de ITBI:
    	</td>
    	<td align="left" ><?=@$cod?>
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Nome:
    	</td>
    	<td align="left" ><input name="it03_nome" type="text" size="60">
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Email
    	</td>
    	<td align="left" ><input name="it03_mail" type="text" size="60">
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >CNPJ/CPF:
    	</td>
    	<td align="left" ><input name="it03_cpfcnpj" type="text" size="18">
    	&nbsp;&nbsp;&nbsp;
    	Sexo: 
    	<select name="it03_sexo"  >
          <option value='F'>Feminino</option>
          <option value='M'>Masculino</option>
	    </select>
    	&nbsp;&nbsp;&nbsp;
    	Principal:
    	<select name="it03_princ"  >
          <option value='t'>Sim</option>
          <option value='f'>Não</option>
	    </select>
    	
    	
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Endereço:
    	</td>
    	<td align="left" ><input name="it03_endereco" type="text" size="60">
    	&nbsp;&nbsp;&nbsp;
    	Número:<input name="it03_numero" type="text" size="6">
    	</td>
  	</tr>
  	
  	<tr class="texto"> 
    	<td align="left" >Bairro:
    	</td>
    	<td align="left" ><input name="it03_bairro" type="text" size="30">
    	&nbsp;&nbsp;&nbsp;
    	Complemento:
    	<input name="it03_compl" type="text" size="30">
    	</td>
  	</tr>
  	<tr class="texto"> 
    	<td align="left" >Município:
    	</td>
    	<td align="left" ><input name="it03_munic" type="text" size="60">
     	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Caixa postal:
    	</td>
    	<td align="left" ><input name="it03_cxpostal" type="text" size="18">
    	&nbsp;&nbsp;&nbsp;
     	UF:
    	<input name="it03_uf" type="text" size="2">
    	&nbsp;&nbsp;&nbsp;
    	CEP:
    	<input name="it03_cep" type="text" size="20">
    	</td>
  	</tr>
  	<tr>
  		<td colspan="2" align="center">
  			<input name='incluir' type='submit' value='Incluir' class='botao' onclick="return js_verifica();">
  		</td>
  	</tr>
  	<tr >
    	<td colspan="2" align="center" >
    	<br>
    	<table width="600px"  cellspacing="2" cellpadding="2" align="center" class="tab">
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
					$sql= "select * from itbinome where it03_guia=$cod and it03_tipo='t'";
					$result = db_query($sql);
					$linhas=pg_num_rows($result);
					if($linhas>0){
						for($i=0;$i<$linhas;$i++){
							db_fieldsmemory($result,$i);
							if($it03_princ=="t"){
								$pri = "sim";
							}else{
								$pri = "não";
							}
							
							echo"
							<tr>
							<td>$it03_nome</td>
							<td>$it03_cpfcnpj</td>
						    <td>$pri</td>
							<td>
								<input name='alterar' type='button' value='Alterar' class='botao' onclick=\"js_alterar('$it03_seq','$it03_princ','$it03_nome','$it03_sexo','$it03_cpfcnpj','$it03_endereco','$it03_numero','$it03_compl','$it03_cxpostal','$it03_bairro','$it03_munic','$it03_uf','$it03_cep','$it03_mail');\">
								<input name='excluir' type='submit' value='Excluir' class='botao' onclick=\"js_excluir('$it03_seq');\" >
							</td>
							</tr>
							";
						}
				
					}
				}
			?>
  		</table>
    	
    	
      	</td>
  	</tr> 	
    	
  	
</table>
</form>
</html>