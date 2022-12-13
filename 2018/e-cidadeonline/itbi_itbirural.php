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
$tipo= @$_SESSION["itbitipo"]; 
$cod=@$_SESSION["itbi"];
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_itbi_classe.php");
include("classes/db_itburbano_classe.php");
include("classes/db_itbimatric_classe.php");
include("classes/db_itbipropriold_classe.php");
include("classes/db_propri_classe.php");
include("classes/db_itbidadosimovel_classe.php");
include("classes/db_itbinome_classe.php");
include("classes/db_itbinomecgm_classe.php");
include("classes/db_itbirural_classe.php");
include("classes/db_itbiruralcaract_classe.php");

$clitbi            = new cl_itbi;
$clitburbano       = new cl_itburbano;
$clitbimatric      = new cl_itbimatric;
$clitbipropriold   = new cl_itbipropriold;
$clpropri          = new cl_propri;
$clitbidadosimovel = new cl_itbidadosimovel;
$clitbinome        = new cl_itbinome;
$clitbinomecgm     = new cl_itbinomecgm;
$clitbirural       = new cl_itbirural;
$clitbiruralcaract = new cl_itbiruralcaract;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
postmemory($HTTP_POST_VARS);
//echo "tipo22 = $tipo sol= $sol";


if($cod!=""){
	echo "
			<script>
           		parent.document.form1.disabilitado.value='nao';
				parent.trocacor('1');
			</script>
		";
}

if (isset($incluir)){
	if($incluir=="Incluir"){
	
	db_inicio_transacao();
// inclui na itbi..................................................
	$sqlerro = false;
	$clitbi->it01_data                = date("Y-m-d");
	$clitbi->it01_hora                = date("H:i");
	$clitbi->it01_tipotransacao       = $it01_tipotransacao;
	$clitbi->it01_areaterreno		  = $it01_areaterreno;
	$clitbi->it01_areaedificada   	  = $it01_areaedificada;
	$clitbi->it01_obs				  = $it01_obs;
	$clitbi->it01_valortransacao      = $it01_valortransacao;
	$clitbi->it01_areatrans   		  = $it01_areatrans;
	$clitbi->it01_mail				  = $it01_mail;
	$clitbi->it01_valortransacaofinanc= $it01_valortransacaofinanc;   
	$clitbi->it01_finalizado          = false;
	$clitbi->incluir(null);	
	
	if ($clitbi->erro_status == 0) {
				$sqlerro = true;
				die($clitbi->erro_sql);
				$erro_msg = $clitbi->erro_msg;
	}
		
	if($sqlerro == false) {
//###################################################################


    $clitbirural->incluir($clitbi->it01_guia);
    if($clitbirural->erro_status == "0"){
      $erro = $clitbirural->erro_msg;
      $sqlerro = true;
    }
    $sqlcar  ="select * from caracter inner join cargrup on cargrup.j32_grupo = caracter.j31_grupo where j32_tipo = 'I'";
	$resultcar= db_query($sqlcar);
	$linhascar=pg_num_rows($resultcar);
    if ($linhascar>0){
		for($i=0;$i<$linhascar;$i++){
			db_fieldsmemory($resultcar,$i);
			$input = "j31_descr".$i;
	        $clitbiruralcaract->it19_guia = $clitbi->it01_guia;
	        $clitbiruralcaract->it19_codigo = $j31_codigo;
	        $clitbiruralcaract->it19_valor = $$input ;
			$clitbiruralcaract->incluir($clitbi->it01_guia,$j31_codigo);
        if($clitbiruralcaract->erro_status == "0"){
          $erro = $clitbiruralcaract->erro_msg;
          $sqlerro = true;
        }
      }
    }
  }
  
  
  if (isset($sol)){
  	$sqlcgm="select * from cgm where z01_cgccpf='$cnpj' ";   
	$resultcgm= db_query($sqlcgm);
	$linhascgm=pg_num_rows($resultcgm);
	if($linhascgm>0){
	  	db_fieldsmemory($resultcgm,0);
	  	if($sol=="t"){
	  	
	  		$clitbinome->it03_guia     = $clitbi->it01_guia;
			$clitbinome->it03_tipo     = 't';
			$clitbinome->it03_princ    = 'true'; 
			$clitbinome->it03_nome     = $z01_nome;
			$clitbinome->it03_sexo     = 'M';
			$clitbinome->it03_cpfcnpj  = $z01_cgccpf;
			$clitbinome->it03_endereco = $z01_ender;
			$clitbinome->it03_numero   = $z01_numero;
			$clitbinome->it03_compl    = $z01_compl;
			$clitbinome->it03_cxpostal = $z01_cxpostal;
			$clitbinome->it03_bairro   = $z01_bairro;
			$clitbinome->it03_munic    = $z01_munic;
			$clitbinome->it03_uf       = $z01_uf;
			$clitbinome->it03_cep      = $z01_cep;
			$clitbinome->it03_mail     = $z01_email; 
		    $clitbinome->incluir(null);
	  	}
	  	if($sol=="c"){
	  		$clitbinome->it03_guia     = $clitbi->it01_guia;
			$clitbinome->it03_tipo     = 'c';
			$clitbinome->it03_princ    = 'true'; 
			$clitbinome->it03_nome     = $z01_nome;
			$clitbinome->it03_sexo     = 'M';
			$clitbinome->it03_cpfcnpj  = $z01_cgccpf;
			$clitbinome->it03_endereco = $z01_ender;
			$clitbinome->it03_numero   = $z01_numero;
			$clitbinome->it03_compl    = $z01_compl;
			$clitbinome->it03_cxpostal = $z01_cxpostal;
			$clitbinome->it03_bairro   = $z01_bairro;
			$clitbinome->it03_munic    = $z01_munic;
			$clitbinome->it03_uf       = $z01_uf;
			$clitbinome->it03_cep      = $z01_cep;
			$clitbinome->it03_mail     = $z01_email; 
		    $clitbinome->incluir(null);
	  	
	  	}
	}
  }
  	
db_fim_transacao($sqlerro);	
	if ($sqlerro==false){
		$codigo=$clitbi->it01_guia;
	    msgbox("ITBI $codigo incluida com sucesso");
	    session_register("itbi");
		$_SESSION["itbi"] = $codigo;
		
		 echo "
			<script>
           		parent.document.form1.disabilitado.value='nao';
				location.href = 'itbi_dadosimovel.php';
				parent.trocacor('2');
			</script>
		";
		
	}	
	}
}//fim do incluir

if (isset($incluir)){
	if($incluir=="Alterar"){
		
		$clitbi->it01_guia = $cod;
		$clitbi->it01_tipotransacao       = $it01_tipotransacao;
		$clitbi->it01_areaterreno		  = $it01_areaterreno;
		$clitbi->it01_areaedificada   	  = $it01_areaedificada;
		$clitbi->it01_obs				  = $it01_obs;
		$clitbi->it01_valortransacao      = $it01_valortransacao;
		$clitbi->it01_areatrans   		  = $it01_areatrans;
		$clitbi->it01_mail				  = $it01_mail;
		$clitbi->it01_valortransacaofinanc= $it01_valortransacaofinanc;      
		$clitbi->alterar($cod);	
	}
	
	    
	
}
?>
<html>
<style type="text/css">
<?db_estilosite(); ?>
</style>
<head>
<title>Cadastro de departamento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_verifica(){
	var obj    = document.form1;
	var ter    = obj.it01_areaterreno.value;
	var edi    = obj.it01_areaedificada.value;
	var vltran = obj.it01_valortransacao.value;
	var vltranf= obj.it01_valortransacaofinanc.value;
	var areatra= obj.it01_areatrans.value;
	var fre    = obj.it18_frente.value;
	var fun    = obj.it18_fundos.value;
	var pro    = obj.it18_prof.value;
	js_valida();
	if(isNaN(ter)){
	    alert("verifique o valor informado para o campo Área do terreno.");
	    document.form1.it01_areaterreno.value="";
	    document.form1.it01_areaterreno.focus();
	    return false;
	}
	if(isNaN(edi)){
	    alert("verifique o valor informado para o campo Área edificada.");
	    document.form1.it01_areaedificada.value="";
	    document.form1.it01_areaedificada.focus();
	    return false;
	}
	if(isNaN(vltranf)){
	    alert("verifique o valor informado para o campo Valor da transação financiado.");
	    document.form1.it01_valortransacaofinanc.value="";
	    document.form1.it01_valortransacaofinanc.focus();
	    return false;
	}
	if(isNaN(vltran)){
	    alert("verifique o valor informado para o campo Valor da transação à vista.");
	    document.form1.it01_valortransacao.value="";
	    document.form1.it01_valortransacao.focus();
	    return false;
	}
	if(isNaN(areatra)){
	    alert("verifique o valor informado para o campo Área transferida.");
	    document.form1.it01_areatrans.value="";
	    document.form1.it01_areatrans.focus();
	    return false;
	}
	if(isNaN(fre)){
	    alert("verifique o valor informado para o campo Frente.");
	    document.form1.it18_frente.value="";
	    document.form1.it18_frente.focus();
	    return false;
	}
	if(isNaN(fun)){
	    alert("verifique o valor informado para o campo Fundos.");
	    document.form1.it18_fundos.value="";
	    document.form1.it18_fundos.focus();
	    return false;
	}
	if(isNaN(pro)){
	    alert("verifique o valor informado para o campo Profundidade.");
	    document.form1.it18_prof.value="";
	    document.form1.it18_prof.focus();
	    return false;
	}
	
	var erro = "";
	if (ter=='')    erro = erro+' Área do terreno\n';
	if (edi=='')    erro = erro+' Área edificada\n';
	if (vltran=='') erro = erro+' Valor da transação à vista\n';
	if (vltranf=='') erro = erro+' Valor da transação financiado\n';
	if (areatra=='')erro = erro+' Área transmitida da terreno\n';
	if (fre=='')erro = erro+' Frente\n';
	if (fun=='')erro = erro+' Fundos\n';
	if (pro=='')erro = erro+' Profundidade\n';
	
		
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

<table width="70%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr class="titulo">
    	<td colspan="2" align="center" >
    	ITBI Rural
      	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" width="30%">Código da guia itbi:
    	</td>
    	<td align="left" ><?=@$cod?>
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Tipo de Transação:
    	</td>
    	<td align="left"" >
    	<select name="it01_tipotransacao"  >
      <?
		  
		  $sqltipo = "select * from itbitransacao";
	      $resulttipo= db_query($sqltipo);
	  	  $linhastipo= pg_num_rows($resulttipo);
	 	  for($i=0;$i<$linhastipo;$i++){
		  db_fieldsmemory($resulttipo,$i);
	  	  echo "<option value='$it04_codigo'>$it04_descr</option>";
	  }
	  ?>
        </select>
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Área do terreno:
    	</td>
    	<td align="left" ><input name="it01_areaterreno" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Área edificada:
    	</td>
    	<td align="left" ><input name="it01_areaedificada" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Observação dadas pelo comprador:
    	</td>
    	<td align="left" ><textarea name="it01_obs" cols="60" rows="3" ></textarea>
    	</td>
  	</tr>
  	<tr class="texto"> 
    	<td align="left" >Valor da transação à vista:
    	</td>
    	<td align="left" ><input name="it01_valortransacao" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Valor da transação financiado:
    	</td>
    	<td align="left" ><input name="it01_valortransacaofinanc" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Área transmitida do terreno:
    	</td>
    	<td align="left" ><input name="it01_areatrans" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Email de contato:
    	</td>
    	<td align="left" ><input name="it01_mail" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Frente:
    	</td>
    	<td align="left" ><input name="it18_frente" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Fundos:
    	</td>
    	<td align="left" ><input name="it18_fundos" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Profundidade:
    	</td>
    	<td align="left" ><input name="it18_prof" type="text" >
    	</td>
  	</tr>
  	<tr>
  		<td colspan="2">
		 	<fieldset >
		    	<legend>Dados da área</legend>
		    	<table width="100%"  >
		       	<?
		       	$sqlcar  ="select * from caracter inner join cargrup on cargrup.j32_grupo = caracter.j31_grupo where j32_tipo = 'I'";
		       	$resultcar= db_query($sqlcar);
		       	$linhascar=pg_num_rows($resultcar);
		       	
		       	if ($linhascar>0){
		       		$js = "function js_valida(){";
		       		for($i=0;$i<$linhascar;$i++){
		       			db_fieldsmemory($resultcar,$i);
		       			$par_impar = bcmod($i, 2);
		       			$js .= "	var j31_descr$i = document.form1.j31_descr$i.value;
									if(isNaN(j31_descr$i)){
                              	       alert('verifique o valor informado para o campo $j31_descr.');
	                                   document.form1.j31_descr$i.value='';
	                                   document.form1.j31_descr$i.focus();
	                                   return false;
                        } \n ";
				       	if($par_impar=="0"){
				       		
				       		echo"
				       		<tr>
					       		<td width='25%'>$j31_descr</td>
					       		<td width='25%'><input name='j31_descr$i' type='text' size='7' value='0'>%</td>";
				       	}
				       
				       	if($par_impar=="1"){
							echo"				       	
								<td width='25%'>$j31_descr</td> 
								<td width='25%'><input name='j31_descr$i' type='text'size='7' value='0'>%</td>
					        </tr>";
				       	}
				       	
		       		}
		       		$js .= "\n }"; 
		       }
		       ?>
		       		   
		      </table>
		    </fieldset>
    	</td>
    </tr>
	
  	<tr class="texto">
    	<td align="left" >&nbsp;
    	</td>
    	<td align="left" ><input name="incluir" type="submit" value="Incluir" class="botao" onClick="return js_verifica()" >
    	</td>
  	</tr>
  	
</table>
</form>
<html>

<?
if($cod!=""){
	echo"
	<script>
		document.form1.incluir.value='Alterar';
	</script>
	";
	$sql= "select * from itbi where it01_guia=$cod";
	$result = db_query($sql);
	$linhas=pg_num_rows($result);
	if($linhas>0){
		db_fieldsmemory($result,0);
		echo"
		<script>
			document.form1.it01_areaterreno.value          =$it01_areaterreno;
			document.form1.it01_tipotransacao.value        =$it01_tipotransacao  ;
		 	document.form1.it01_areaedificada.value        =$it01_areaedificada ;
			document.form1.it01_obs.value                  ='$it01_obs';
			document.form1.it01_valortransacaofinanc.value =$it01_valortransacaofinanc;
			document.form1.it01_valortransacao.value       ='$it01_valortransacao'; 
 			document.form1.it01_areatrans.value            ='$it01_areatrans'; 
 			document.form1.it01_mail.value                 ='$it01_mail'; 
		</script>
		";

	}
	$sqlru= "select * from itbirural where it18_guia=$cod";
	$resultru = db_query($sqlru);
	$linhasru=pg_num_rows($resultru);
	if($linhasru>0){
		db_fieldsmemory($resultru,0);
		echo"
		<script>
			document.form1.it18_frente.value =$it18_frente;
			document.form1.it18_fundos.value =$it18_fundos  ;
		 	document.form1.it18_prof.value   =$it18_prof ;
		</script>
		";
	}
	
	if ($linhascar>0){
		$sql = "select * from itbiruralcaract where it19_guia=$cod"; 
	   	$result = db_query($sql);
		$linhas=pg_num_rows($result);
	   	for($i=0;$i<$linhascar;$i++){
	   		
			if($linhas>0){
			db_fieldsmemory($result,$i);
		   		echo"
				<script>
					document.form1.j31_descr$i.value =$it19_valor;
				</script>
				";
			}
	   	}
	}
}

echo "<script> $js </script>";
?>