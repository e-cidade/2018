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
include("libs/db_sql.php");
include("classes/db_db_dairetido_classe.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
		       WHERE m_arquivo = 'digitadae.php'
		       ORDER BY m_descricao
		       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help();
$dblink="index.php";
db_logs("","",0,"valores da DAI.");
postmemory($HTTP_POST_VARS);
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
?>


<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
	
	function js_excluir(x){
		var obj = document.form1;
		obj.pkdt.value  = x;
		//obj.submit();	
		
	}
	
	
	function js_alterar(x,a,b,c,d,e,dia,mes,ano){
		var obj1 = document.form1;
		obj1.pkdt.value = x;
		obj1.mesdai.value = a;
		obj1.valor.value = b;
		obj1.nota.value = c;
		obj1.serie.value =d;
		obj1.cnpj.value =e;
		obj1.dia.value =dia;
		obj1.mes.value =mes;
		obj1.ano.value =ano;
		obj1.salvar.value= "Alterar";
	}
	
	
function js_veri(){
 		  var num = new String(document.form1.valor.value.replace(".","."));
		  document.form1.valor.value = num;
		  if(document.form1.valor.value.indexOf(",")!=-1){
		    var vals= new Number(document.form1.valor.value.replace(",","."));
		    document.form1.valor.value = vals.toFixed(2);
		  }else{
		    var vals = new Number(document.form1.valor.value);
		    document.form1.valor.value = vals.toFixed(2);
		  }
		  if(isNaN(vals)){
		    alert("verifique o valor da receita!");
		    document.form1.valor.value="";
		    document.form1.valor.focus();
		    return false;
		  }
}		  

function js_verificanota(cnpj,nota,serie){
	pesquisanota.location.href = 'pesquisanotadaipres.php?cnpj='+cnpj+'&nota='+nota+'&serie='+serie;
}

function js_notaexiste(){
	document.form1.cnpj.value='';
	document.form1.nota.value='';
	document.form1.serie.value='';
	document.form1.cnpj.focus();
}

function js_verificacomp(){
	var obj3 = document.form1;
	
	mesval = Number(obj3.mes.value);
	anoval = Number(obj3.ano.value);
	anodae = Number(obj3.anodae.value);
	mesdai = Number(obj3.mesdai.value);
	if((mesval != mesdai) || (anoval < anodae)){
		alert ('Data informada diferente da competência :'+ mesdai+'/'+anodae);
		obj3.dia.value="";
		obj3.mes.value="";
		obj3.ano.value="";
		
	}
}
function js_cnpj(){
	var erro = "";
 	var obj3 = document.form1;
	var diaval = obj3.dia.value;
	var mesval = obj3.mes.value;
	var anoval = obj3.ano.value;
  if ((diaval!='')&& (mesval!='')&&(anoval!='')){
 
	  if(isNaN(diaval)){
	    alert('dia Inválido');
	    
	    erro = "s";
	    return false;
	  }    
	  if(isNaN(mesval)){
	    alert('Data Inválida');
	   
	    erro = "s";
	    return false;
	  }  
	  if(isNaN(anoval)){
	    alert('Data Inválida');
	   
	    erro = "s";
	    return false;
	  }  
	  data = new Date(anoval,(mesval-1),diaval);
	  if((data.getMonth() + 1) != mesval || data.getFullYear() != anoval){
	    alert('Data Inválida');
	    
	    erro = "s";
	    return false;
	  }
  }
  
  
  
  if (obj3.cnpj.value.length == 14) {
    var retorna = js_verificaCGCCPF(obj3.cnpj,'');
  } else {
    var retorna = js_verificaCGCCPF('',obj3.cnpj);
  }
    
   if(retorna==""){
  	return false;
  	 	
  }
return true;
 
}



</script>
<style type="text/css">
<?
db_estilosite();
?>
</style>
</head>
<body bgcolor="<?=$w01_corbody?>">

<?


$cldb_dairetido= new cl_db_dairetido;


if (isset($salvar)){
	if($salvar=="Salvar"){
		$datapago = $ano . "-" . $mes . "-" . $dia;
		$sqlerro = false;
		$cldb_dairetido->w15_dai  = $codigo;
		$cldb_dairetido->w15_mes  = $mesdai;
		$cldb_dairetido->w15_valreceita  = $valor;
		$cldb_dairetido->w15_nota  = $nota;
		$cldb_dairetido->w15_serie = $serie;
		$cldb_dairetido->w15_cnpj  = $cnpj;
		$cldb_dairetido->w15_data  = $datapago;
		$cldb_dairetido->incluir(null);
		if ($cldb_dairetido->erro_status == 0) {
			$sqlerro = true;
			//echo"entrei no erro do dairetido.....";
			die($cldb_dairetido->erro_sql);
			$erro_msg = $cldb_dairetido->erro_msg;
		}
	
	 	if ($sqlerro == false) {
		 	//echo "entrando no incluir dtp";
		   		  
	 	}
	  
	 	if ($sqlerro == true) {
		    echo "erro: $erro_msg<br>";
		}
   }
}

if (isset($excluir)){
	//echo "entrouu aki no exluir";
	$cldb_dairetido->excluir($pkdt);
} 

if (isset($salvar)){
	if ($salvar=="Alterar"){
		$datapago = $ano . "-" . $mes . "-" . $dia;
		$cldb_dairetido->w15_sequencial = $pkdt;
	 	$cldb_dairetido->w15_mes  = $mesdai;
		$cldb_dairetido->w15_valreceita  = $valor;
		$cldb_dairetido->w15_nota  = $nota;
		$cldb_dairetido->w15_serie = $serie;
		$cldb_dairetido->w15_cnpj  = $cnpj;
		$cldb_dairetido->w15_data  = $datapago;
		$cldb_dairetido->alterar($pkdt);
	 		
	 	if ($cldb_dairetido->erro_status == 0) {
		  	//echo"entrei no erro do alterarr.....";
		  	die($cldb_dairetido->erro_sql);
		  	$erro_msg = $cldb_dairetido->erro_msg;
		}
		 			
 	}	
}
$sqlano="select w04_ano from db_dae where w04_inscr = $inscricaow and w04_codigo=$codigo";
$resultano = db_query($sqlano);
db_fieldsmemory($resultano,0);
?>

<table width="100%" border="0">
<form name="form1" method="post" action="dairetido.php">

<input type=hidden name=pkdt value=''>
<input type=hidden name=anodae value="<?=@$w04_ano?>">
<input type=hidden name=inscricaow value="<?=@$inscricaow?>" >

<tr>
	<td colspan="6" align="center" class="titulo2"> 
		
	</td>
</tr>
<tr class="titulo2">
	<td>
		Mês
	</td>
	<td>
		CPF ou CNPJ
	</td>
	<td>
		Nota
	</td>
	<td>
		Série
	</td>
	<td>
		Valor da nota
	</td>
	<td>
		Data de emissão
	</td>
	
	
<tr class="titulo2">
	<td >
		<select  name="mesdai" onChange="document.form1.cnpj.focus();">
	    	<option value="0">Mês</option>
	    	<option value="1">Janeiro</option>
	    	<option value="2">Fevereiro</option>
	    	<option value="3">Março</option>
	    	<option value="4">Abril</option>
	    	<option value="5">Maio</option>
	    	<option value="6">Junho</option>
	    	<option value="7">Julho</option>
	    	<option value="8">Agosto</option>
	    	<option value="9">Setembro</option>
	    	<option value="10">Outubro</option>
	    	<option value="11">Novembro</option>
	    	<option value="12">Dezembro</option>
	    </select>
	</td>
	<td>
		<input name="cnpj" type="text" size="14" class="borda" class="digitacgccpf" id="cgc" maxlength="14" onChange="document.form1.nota.focus();" >
		
	</td>
	<td>
		<input name="nota" type="text" size="10" class="borda" >
	</td>
	<td>
		<input name="serie" type="text" size="10" class="borda"  onchange="js_verificanota(document.form1.cnpj.value,document.form1.nota.value, document.form1.serie.value)" >
	</td>
	<td>
		R$<input name="valor" type="text"  size="15" class="borda" onChange="return js_veri()" >
	</td>
	<td>
		<input name="dia" type="text" size="2" class="borda" onKeyUp="(this.value.length == 2?document.form1.mes.focus():1)"> 
		<input name="mes" type="text" size="2" class="borda" onFocus="(document.form1.dia.value==''?document.form1.dia.focus():'')" onKeyUp="(this.value.length == 2?document.form1.ano.focus():1)"> 
		<input name="ano" type="text" size="4" class="borda" onKeyUp="(this.value.length == 4?document.form1.valorpago.focus():1)" onblur="js_verificacomp()"> 
	</td>

</tr>
<tr class="titulo2">
	<td colspan="6">
		<input type="hidden" name="codigo" value="<?=@$codigo?>" >
		<input class="botao" name="salvar" type="submit" value="Salvar" id ="sal_alt" onclick="return js_cnpj();"  >
		
	</td>
</tr>

<tr>
	<td colspan ="6">
		<table width="100%" class="tab">
			<tr >
				<th>
					Mês
				</th>
				<th>
					CNPJ
				</th>
				<th>
					Nota
				</th>
				<th>
					Série
				</th>
				<th>
					Valor da nota
				</th>
				<th>
					Data de emissão
				</th>
				<th>
					
				</th>
				
			</tr>
			
<?
  
	$result = $cldb_dairetido->sql_record( $cldb_dairetido->sql_query("","*","w15_mes","w15_dai = $codigo"));
	$linhas= $cldb_dairetido->numrows;
	for($i=0;$i<$linhas;$i++){
		db_fieldsmemory($result,$i);
		
	echo"
				<tr class=\"texto\">
				<td>
					".db_mes($w15_mes)."
				</td>
				<td>
					".db_cgccpf($w15_cnpj)." 
				</td>
				<td>
					$w15_nota
				</td>
				<td>
					$w15_serie
				</td>
				<td>
					".db_formatar($w15_valreceita,'f')."
				</td>
				<td>
					".($w15_data != ""?db_formatar($w15_data,'d'):'não efetuado')."
";
					if($w15_data!= ""){
						$data = split("-",$w15_data);
						$diadt[$i] = $data[2];
						$mesdt[$i] = $data[1];	
						$anodt[$i] = $data[0];
						
					}
echo "					
				</td>
				<td width= \"140px\">
																													
				    <input name=\"alterar\" type=\"button\" value=\"Alterar\" onClick=\"js_alterar('$w15_sequencial','$w15_mes','$w15_valreceita','$w15_nota','$w15_serie','$w15_cnpj','$diadt[$i]','$mesdt[$i]','$anodt[$i]')\">
						
					<input name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"js_excluir('$w15_sequencial')\">
   			      
				  					  
				</td>
			</tr>
";
					
					
	}//do for
 
	
	
?>
		</table>
	</td>
</tr>

</table>
<iframe name="pesquisanota"  style="visibility:hidden"></iframe>
</form>
</body>
</html>