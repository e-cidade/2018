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
include("classes/db_db_daevalores_classe.php");
include("dbforms/db_funcoes.php");
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
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
	
	function js_excluir(x,y){
		var obj = document.form1;
		obj.codigo.value  = x;
		obj.item.value = y;
		//obj.submit();	
		
	}
	
	
	function js_alterar(x,y,a,b,c,d,dia,mes,ano){
		var obj1 = document.form1;
		obj1.codigo.value = x;
		obj1.item.value = y;
		obj1.mesdai.value = a;
		obj1.valor.value = b;
		obj1.aliquota.value = c;
		obj1.imposto.value =d;
		obj1.dia.value =dia;
		obj1.mes.value =mes;
		obj1.ano.value =ano;
		obj1.salvar.value= "Alterar";
	}
	
	
	function js_imposto(obj){
    	if(document.form1.imposto.value.indexOf(",")!=-1){
        	var  imposto= new Number(document.form1.imposto.value.replace(",","."));
            document.form1.imposto.value = imposto.toFixed(2);
        }else{
        	var imposto = new Number(obj);
        }
            var aliquota = new Number(document.form1.aliquota.value);
            vals = new Number((imposto)/(aliquota/100));
            document.form1.valor.value =  vals.toFixed(2);
            document.form1.dia.focus();
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
		  var aliquota = new Number(document.form1.aliquota.value);
		  vals = new Number((vals *(aliquota/100)));
		  document.form1.imposto.value=vals.toFixed(2);
		  if(document.form1.imposto.value!="")
		    document.form1.dia.focus();
}

function js_verificacomp(){
	var obj3 = document.form1;
	
	mesval = obj3.mes.value;
	anoval = obj3.ano.value;
	anodae = obj3.anodae.value;
	mesdai = obj3.mesdai.value;
	dataval = Number(anoval+''+mesval);
	datadae = Number(anodae+''+mesdai);
	
	//str_pad(datadae,2," ",0);
	if (dataval < datadae){
		alert ('Data informada inferior a competência :'+ mesdai+'/'+anodae);
		obj3.dia.value="";
		obj3.mes.value="";
		obj3.ano.value="";
		
	}
}

function js_data(){
  var obj3 = document.form1;
  var diaval = obj3.dia.value;
  var mesval = obj3.mes.value;
  var anoval = obj3.ano.value;
  var mesdai = obj3.mesdai.value;
  var sal = obj3.salvar.value;
	var anodae = obj3.anodae.value;
	
	

  
 if (sal!='Alterar'){
	 if (document.getElementById('mes_'+mesdai)){
	 		var confirma = confirm('Ja existe lançamento para este mês. Confirma novo lançamento?');
	            if(confirma != true){
	            	return false;	
	            				
	           	} 
	 }
 }
 
 
  if ((diaval!='')&& (mesval!='')&&(anoval!='')){
  
	  if(isNaN(diaval)){
	    alert('dia Inválido');
	    obj3.incluir.value="nao";
	    return false;
	    
	  }    
	  if(isNaN(mesval)){
	    alert('Data Inválida ');
	    obj3.incluir.value="nao";
	    return false;
	   
	  }  
	  if(isNaN(anoval)){
	    alert('Data Inválida');
	    obj3.incluir.value="nao";
	    return false;
	   
	  }  
	  data = new Date(anoval,(mesval-1),diaval);
	  if((data.getMonth() + 1) != mesval || data.getFullYear() != anoval){
	    alert('Data Inválida ');
	    obj3.incluir.value="nao";
	    return false;
	    
	  }
  }
 
inp = document.createElement("input");
if (sal!='Alterar'){
	inp.setAttribute("name","salvar");
	inp.setAttribute("value","Salvar");
}else{
	inp.setAttribute("name","salvar");
	inp.setAttribute("value","Alterar");
}
mesdai2 = Number(obj3.mesdai.value);
mesdai2 = mesdai2 + 1;


obj3.appendChild(inp);
obj3.submit();
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

$sqlano="select w04_ano from db_dae where w04_inscr = $inscricaow and w04_codigo=$codigo";
$resultano = db_query($sqlano);
db_fieldsmemory($resultano,0);

// como se fosse o _POST... transforma os inputs em variaveis
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

$cldb_daevalores = new cl_db_daevalores;

$result2 = $cldb_daevalores->sql_record("select max(w07_item) from db_daevalores where w07_codigo = $codigo");
db_fieldsmemory($result2,0);
//echo " itens =$max ";

if (isset($salvar)){
	if($salvar=="Salvar"){
					
		if($incluir!="nao"){ 
				
					
						$max= $max + 1;
						$sqlerro = false;
						$datapago = $ano . "-" . $mes . "-" . $dia;
						if ($datapago!="--"){
						//	echo "entrou no ano dif de nada";
							$datapago = $ano . "-" . $mes . "-" . $dia;
							$cldb_daevalores->w07_mes  = $mesdai;
							$cldb_daevalores->w07_valor  = $valor;
							$cldb_daevalores->w07_aliquota  = $aliquota;
							$cldb_daevalores->w07_imposto  = $imposto;
							$cldb_daevalores->w07_dtpaga  = $datapago;
							$cldb_daevalores->incluir($codigo,$max);
							
						}else{
							//echo "entrou no ano = a nada";
							$cldb_daevalores->w07_mes  = $mesdai;
							$cldb_daevalores->w07_valor  = $valor;
							$cldb_daevalores->w07_aliquota  = $aliquota;
							$cldb_daevalores->w07_imposto  = $imposto;
							$cldb_daevalores->incluir($codigo,$max);
						}
						if ($cldb_daevalores->erro_status == 0) {
							$sqlerro = true;
							echo"entrei no erro do dai.....";
							die($cldb_daevalores->erro_sql);
							$erro_msg = $cldb_daevalores->erro_msg;
						}

		}
   }
}

if (isset($excluir)){
	//echo "entrouu aki no exluir";
	$cldb_daevalores->excluir($codigo,$item);
    
} 

if (isset($salvar)){
	if ($salvar=="Alterar"){
		$datapago = $ano . "-" . $mes . "-" . $dia;
		if($datapago=="--"){
			$datapago="";
		}
		$cldb_daevalores->w07_codigo = $codigo;
		$cldb_daevalores->w07_item = $item;
	 	$cldb_daevalores->w07_mes  = $mesdai;
		$cldb_daevalores->w07_valor  = $valor;
		$cldb_daevalores->w07_aliquota  = $aliquota;
		$cldb_daevalores->w07_imposto  = $imposto;
		$cldb_daevalores->w07_dtpaga  = $datapago;
		$cldb_daevalores->alterar($codigo,$item);
	 		
	 	if ($cldb_daevalores->erro_status == 0) {
		  	echo"entrei no erro do alterarr.....";
		  	die($cldb_daevalores->erro_sql);
		  	$erro_msg = $cldb_daevalores->erro_msg;
		}
	 		
	 }	
}

?>

<table width="100%" border="0">
<form name="form1" method="post" action="valoresdae.php">

<input type=hidden name=codigo value=''>
<input type=hidden name=item value=''>
<input type=hidden name=incluir value=''>
<input type=hidden name=anodae value="<?=$w04_ano?>">
<input type=hidden name=inscricaow value="<?=$inscricaow?>">

<tr>
	<td colspan="5" align="center" class="titulo2"> 
		No valor do imposto não devem ser considerados juros e multa
	</td>
</tr>
<tr class= "titulo2">
	<td >
		Mês
	</td>
	<td  >
		Valor da receita
	</td>
	<td  >
		Aliquota
	</td>
	<td  >
		Imposto
	</td>
	<td  >
		Data do pagamento
	</td>
	<?
	if (isset($mesdai)){
		$mesdai++;
	}
	
	?>
<tr class= "titulo2">
	<td >
		<?
		
		$messs = array(1=>"janeiro",
					   2=>"fevereiro",
					   3=>"março",
					   4=>"abril",
					   5=>"maio",
					   6=>"junho",
					   7=>"julho",
					   8=>"agosto",
					   9=>"setembro",
					   10=>"outubro",
					   11=>"novembro",
					   12=>"dezembro");
		db_select ("mesdai",$messs,true,1);
		
		?>
	
	</td>
	<td>
		R$<input class="borda" name="valor" type="text"  size="15" onChange="return js_veri()" >
	</td>
	<td>
		<select name="aliquota"  onChange="return js_veri()" class="borda">
      		<option value="0">0%</option>
      		<option value="0.5">0,5%</option>
      		<option value="1">1%</option>
      		<option value="2"selected >2%</option>
      		<option value="3">3%</option>
      		<option value="4">4%</option>
      		<option value="5">5%</option>
      		<option value="6">6%</option>
      		<option value="7">7%</option>
      		<option value="8">8%</option>
      		<option value="9">9%</option>
      		<option value="10">10%</option>
      	</select>
	</td>
	<td>
		<input name="imposto" type="text" size="5" class="borda" onChange="js_imposto(this.value)">
	</td>
	<td>
		<input name="dia" type="text" size="2" class="borda" onKeyUp="(this.value.length == 2?document.form1.mes.focus():1)"> 
		<input name="mes" type="text" size="2" class="borda" onFocus="(document.form1.dia.value==''?document.form1.dia.focus():'')" onKeyUp="(this.value.length == 2?document.form1.ano.focus():1)"> 
		<input name="ano" type="text" size="4" class="borda" onblur="js_verificacomp()" onKeyUp="this.value.length == 4"> 
	</td>

</tr>
<tr>
	<td colspan="5">
		<input type="hidden" name="codigo" value="<?=@$codigo?>" >
		<input name="salvar" class="botao" type="button" value="Salvar"  onclick= "js_data(this.value)">
		
	</td>
</tr>

<tr>
	<td colspan ="5">
		<table width="100%" class="tab">
			<tr >
				<th>
					Mês
				</th>
				<th>
					Valor da receita
				</th>
				<th>
					Aliquota
				</th>
				<th>
					Imposto
				</th>
				<th>
					Data do pagamento					
				</th>
				
				<th>
				</th>
			</tr>
			
<?
	
$result = $cldb_daevalores->sql_record( $cldb_daevalores->sql_query("$codigo","","*","w07_mes","w07_codigo = $codigo"));
//die($cldb_daevalores->sql_query("$codigo","","*","w07_mes","w07_codigo = $codigo"));
$linhas= $cldb_daevalores->numrows;
	
	for($i=0;$i<$linhas;$i++){
		db_fieldsmemory($result,$i);
		
		$diadt[$i]="";
		$mesdt[$i]="";
		$anodt[$i]="";
		
	echo"
				<tr >
				<td id=mes_$w07_mes>
					".db_mes($w07_mes)."
				</td>
				<td>
					".db_formatar($w07_valor,'f')."
				</td>
				<td>
					$w07_aliquota %
				</td>
				<td>
					".db_formatar($w07_imposto,'f')."
				</td>
				<td>
					".($w07_dtpaga != ""?db_formatar($w07_dtpaga,'d'):'não efetuado')."
";					
					
					if($w07_dtpaga!= ""){
						$data = split("-",$w07_dtpaga);
						$diadt[$i] = $data[2];
						$mesdt[$i] = $data[1];	
						$anodt[$i] = $data[0];
					}
echo "	
				</td>
				
				<td width= \"140px\">
																													
					<input name=\"alterar\" type=\"button\" value=\"Alterar\" onClick=\"js_alterar('$w07_codigo','$w07_item','$w07_mes','$w07_valor','$w07_aliquota','$w07_imposto','$diadt[$i]','$mesdt[$i]','$anodt[$i]')\">
						
					<input name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"js_excluir('$w07_codigo','$w07_item')\"> 
	   			      
				  					  
				</td>
			</tr>
";
					
					
	}//do for
 
	
	 
	
 	
 
		
	
?>
		</table>
	</td>
</tr>

</table>

</form>
</body>
</html>