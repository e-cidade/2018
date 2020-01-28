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
include("classes/db_db_daitomador_classe.php");
include("classes/db_db_daitomadorpaga_classe.php");
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
	
	function js_mes(){
		//var novomes1 = document.form1.mesdai.value;
		document.form1.novomes.value = "sim"
		document.form1.submit(); 
	}
	
	
	function js_excluir(x,y){
		var obj = document.form1;
		obj.pkdt.value  = x;
		obj.pkdtp.value = y;
		//obj.submit();	
		
	}
	

	function js_alterar(x,y,a,b,c,d,dia,mes,ano,pago,cnpj,nome,ser,nota,serie){
		var obj1 = document.form1;
		obj1.pkdt.value = x;
		obj1.pkdtp.value = y;
		obj1.mesdai.value = a;
		obj1.valor.value = b;
		obj1.aliquota.value = c;
		obj1.imposto.value =d;
		obj1.dia.value =dia;
		obj1.mes.value =mes;
		obj1.ano.value =ano;
		obj1.valorpago.value =pago;
		obj1.cnpj.value =cnpj;
		obj1.nome.value =nome;
		obj1.servico.value =ser;
		obj1.nota.value =nota;
		obj1.serie.value =serie;
		obj1.salvar.value= "Alterar";
		if (obj1.dia.value ==''){
			obj1.datadia.value ="novo";
			
		}
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

function js_pago(){
		var obj2 = document.form1.valorpago;
		var num = obj2.value.replace(".",".");
		obj2.value = num;
		if(obj2.value.indexOf(",")!=-1){
	        var vals= new Number(obj2.value.replace(",","."));
	        obj2.value = vals.toFixed(2);
	      }else{
	        var vals = new Number(obj2.value);
	        obj2.value = vals.toFixed(2);
	      }
      if(isNaN(vals)){
        alert("verifique o valor da receita!");
       obj2.value="";
        obj2.focus();
        return false;
      }
}

function js_data(){
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

function js_verificanota(cnpj,nota,serie){
	pesquisanota.location.href = 'pesquisanotadaitoma.php?cnpj='+cnpj+'&nota='+nota+'&serie='+serie;
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
	dataval = (anoval+''+mesval);
	datadai = (anodae+''+mesdai);
	if (dataval < datadai){
		alert ('Data informada inferior a competência :'+ mesdai+'/'+anodae);
		obj3.dia.value="";
		obj3.mes.value="";
		obj3.ano.value="";
	}
	
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

// ############## função  ################
	
function monta_tabela($sql, $array_formata = array()){
	$result = db_query($sql);
	$lin = pg_num_rows($result);
	if($lin==0) {
		return;
	} 	
	$col = pg_num_fields($result);

 	// Para publicar variaveis do ResultSet
	for ($c = 0; $c < $col; $c++) {
		$campo = pg_field_name($result, $c);
		global $$campo;
	}
		
	//echo "colunas = $col, linhas = $lin<br>";
 	for ($i = 0; $i < $lin; $i++) {
		db_fieldsmemory($result, $i);
		
		//echo "linha = $i<br>";
 		echo "<tr class=\"titulo2\"> ";
 			

	 	
	 	// Para montar a Tabela de acordo com formatação
	 	foreach($array_formata as $campo => $conteudo) {
	 		
	 		//echo $campo . "=" .$$campo."<br>"; 
	 		
	 		if (!empty($conteudo)) {
	 			//echo "= ". $$campo. "<br>";
	 			$formata = $conteudo;
	 			$valor_campo = $$campo;
	 			$eval_expr = "\$eval_var=".$formata;
 				 			
	 			eval($eval_expr);
	 			
	 			echo "<td>".$eval_var."</td>";
	 		} else {
	 			//echo "= ". $$campo. "<br>";
	 			echo "<td>".$$campo."</td>";
	 		}
	 		
	 	}//linha
	 	$diadt[$i]="";
		$mesdt[$i]="";
		$anodt[$i]="";
	 	
	 	if($w09_dtpaga!= ""){
						$data = split("-",$w09_dtpaga);
						$diadt[$i] = $data[2];
						$mesdt[$i] = $data[1];	
						$anodt[$i] = $data[0];
						
		}
	 	
	 	echo" 
	 	<td width= \"70px\">
																													
				  <input class=\"botao\" name=\"alterar\" type=\"button\" value=\"Alterar\" onClick=\"js_alterar('$w08_sequencial','$w09_sequencial','$w08_mes','$w08_valreceita','$w08_aliquota','$w08_imposto','$diadt[$i]','$mesdt[$i]','$anodt[$i]','$w09_valpago','$w08_cnpj','$w08_nome','$w08_servico','$w08_nota','$w08_serie')\">
					
				  <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"js_excluir('$w08_sequencial','$w09_sequencial')\">
   			      
				  					  
				</td>";
		
	 	echo "</tr>";
 	}
}//######################## termina a função #######################################

//echo "codigo = $codigo";
//echo "novo mes = $novomes xx";
//if(isset($novomes))
if (isset($novomes))
if($novomes=="sim"){
$mesdai2= $mesdai;
//echo "entrou na novomesssss.........";

$sqlano="select w04_ano from db_dae where w04_inscr = $inscricaow and w04_codigo=$codigo";
$resultano = db_query($sqlano);
db_fieldsmemory($resultano,0);


}else{
	$mesdai2= $mesdai3;
}



//echo "mesdai2 = $mesdai2 xx";
$cldb_daitomador = new cl_db_daitomador;
$cldb_daitomadorpaga = new cl_db_daitomadorpaga;

if (isset($salvar)){
	if($salvar=="Salvar"){
		
			$sqlerro = false;
			$cldb_daitomador->w08_dai  = $codigo;
			$cldb_daitomador->w08_mes  = $mesdai3;
			$cldb_daitomador->w08_valreceita  = $valor;
			$cldb_daitomador->w08_aliquota  = $aliquota;
			$cldb_daitomador->w08_imposto  = $imposto;
			$cldb_daitomador->w08_nota  = $nota;
			$cldb_daitomador->w08_serie  = $serie;
			$cldb_daitomador->w08_cnpj = $cnpj;
			$cldb_daitomador->w08_nome  = $nome;
			$cldb_daitomador->w08_servico  = $servico;
			$cldb_daitomador->w08_origem  = "digitado";
			$cldb_daitomador->incluir(null);
			
			if ($cldb_daitomador->erro_status == 0) {
				$sqlerro = true;
				//echo"entrei no erro do daitomador..kk...";
				die($cldb_daitomador->erro_sql);
				$erro_msg = $cldb_daitomador->erro_msg;
			}
		
		 	if ($sqlerro == false) {
			 	//echo "entrando no incluir dtp";
			    $datapago = $ano . "-" . $mes . "-" . $dia;
		
		   		if ($datapago!= "--") {
			    	$cldb_daitomadorpaga->w09_daitomador  = $cldb_daitomador->w08_sequencial;
				    $cldb_daitomadorpaga->w09_dtpaga  = $datapago;
				    $cldb_daitomadorpaga->w09_valpago  = $valorpago;
				    $cldb_daitomadorpaga->incluir(null);
				    
				    if ($cldb_daitomadorpaga->erro_status == 0) {
				  		$sqlerro = true;
				  		//echo"entrei no erro dodaitomadorpaga";
						$erro_msg = $cldb_daitomadorpaga->erro_msg;
					}
		    	}
		    
		 	}
		  
		 	if ($sqlerro == true) {
			    echo "erro: $erro_msg<br>";
			}
		
   }
}

if (isset($excluir)){
	//echo "entrouu aki no exluir";
	$cldb_daitomadorpaga->excluir($pkdtp);
    $cldb_daitomador->excluir($pkdt);
} 

if (isset($salvar)){
	if ($salvar=="Alterar"){
		//echo ".....opcao=$opcao....entrei no alterar....pk = $pkdt...  ";
		$cldb_daitomador->w08_sequencial = $pkdt;
	 	$cldb_daitomador->w08_mes  = $mesdai;
		$cldb_daitomador->w08_valreceita  = $valor;
		$cldb_daitomador->w08_aliquota  = $aliquota;
		$cldb_daitomador->w08_imposto  = $imposto;
		$cldb_daitomador->w08_nota  = $nota;
		$cldb_daitomador->w08_serie  = $serie;
		$cldb_daitomador->w08_cnpj = $cnpj;
		$cldb_daitomador->w08_nome  = $nome;
		$cldb_daitomador->w08_servico  = $servico;
		$cldb_daitomador->alterar($pkdt);
	 		
	 	if ($cldb_daitomador->erro_status == 0) {
		  //	echo"entrei no erro do alterarr.....";
		  	die($cldb_daitomador->erro_sql);
		  	$erro_msg = $cldb_daitomador->erro_msg;
		}
	 	
	 	if ($datadia!="novo"){	
		 	if ($dia!=""){
		 		$datapago = $ano . "-" . $mes . "-" . $dia;
				
				$cldb_daitomadorpaga->w09_sequencial = $pkdtp;
				$cldb_daitomadorpaga->w09_dtpaga  = $datapago;
			    $cldb_daitomadorpaga->w09_valpago  = $valorpago;
			    $cldb_daitomadorpaga->alterar($pkdtp);
			}else{
				$cldb_daitomadorpaga->excluir($pkdtp);
			}
	 	}else{
	 		$datapago = $ano . "-" . $mes . "-" . $dia; 	
	 		$cldb_daitomadorpaga->w09_daitomador  = $pkdt;
			$cldb_daitomadorpaga->w09_dtpaga  = $datapago;
			$cldb_daitomadorpaga->w09_valpago  = $valorpago;
			$cldb_daitomadorpaga->incluir(null);
	 		}
		
 		
 	}	
}


?>


<table width="100%" border="0">
<form name="form1" method="post" action="daitomador.php">

<input type=hidden name=pkdt value=''>
<input type=hidden name=pkdtp value=''>
<input type=hidden name=datadia value=''>
<input type=hidden name=incluir value=''>
<input type=hidden name=novomes value=''>
<input type=hidden name=mesdai3 value=<?=(isset($mesdai)?$mesdai:0)?>>
<input type=hidden name=anodae value="<?=@$w04_ano?>">
<input type=hidden name=codigo value="<?=@$codigo?>" >
<input type=hidden name=inscricaow value="<?=@$inscricaow?>" >

<tr>
	
	<td align = "center" colspan="7"><strong>
	Competência: <? if(isset($mesdai2)) echo" ".db_mes($mesdai2)." ";?>
	</strong>
	</td>
</tr>
<tr class="titulo2">
	<td >
		Mês
	</td>
	<td colspan ="2" >
		CPF ou CNPJ
	</td>
	<td colspan ="2" >
		Nome ou Razão Social
	</td>
	<td colspan ="2" >
		Serviço
	</td>
</tr>
<tr>
	<td >
		<select  name="mesdai" onChange="document.form1.cnpj.focus();" onblur= "js_mes();" >
	    	<option value="0">Mês</option>
	    	<?
	    	for ($m=1; $m<=12;$m++){
	    		echo "<option value = \"$m\"".($m==$mesdai?" selected":"").">".db_mes($m)." </option>";
	    	}
	    	?>
	    </select>
			
	</td>
	<td colspan ="2">
		<input name="cnpj" type="text"  size="14" maxlength="14" class="borda" >
	</td>
	<td colspan ="2">
		<input name="nome" type="text"  size="30" class="borda">
	</td>
	<td colspan ="2">
		<input name="servico" type="text"  size="30" class="borda">
	</td>
</tr>

<tr class="titulo2">
	<td>
		Nota
	</td>
	<td>
		Série
	</td>
	<td>
		Valor
	</td>
	<td>
		Aliquota
	</td>
	<td>
		Imposto
	</td>
	
	<td>
		Data do pagamento
	</td>
	<td>
		Valor pago *
	</td>
</tr>



<tr class="titulo2">
	<td>
		<input name="nota" type="text"  size="5" class="borda">
	</td>
	<td>
		<input name="serie" type="text"  size="5" class="borda" onchange="js_verificanota(document.form1.cnpj.value,document.form1.nota.value, document.form1.serie.value)">
	</td>
	<td>
		R$<input name="valor" type="text"  size="15" class="borda" onChange="return js_veri()" >
	</td>
	<td>
		<select name="aliquota"  onChange="return js_veri()">
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
		<input name="ano" type="text" size="4" class="borda" onKeyUp="(this.value.length == 4?document.form1.valorpago.focus():1)" onblur="js_verificacomp()"> 
	</td>
	<td>
		R$<input name="valorpago" type="text" size="15" class="borda" onChange="return js_pago()" >
	</td>
</tr>
<tr>
	<td colspan="7">
		
		<input class="botao" name="salvar" type="submit" value="Salvar" onclick="return js_data();">
		
	</td>
</tr>
<tr class="titulo"><td>* Valor pago refente ao valor do recibo</td></tr>
<tr>
	<td colspan ="7">
		<table width="100%"  class="tab"  >
			
			<tr  >
			
				<th>Origem</th>
				<th >
					CPF ou CNPJ
				</th>
				<th >
					Nome ou Razão Social
				</th>
				<th >
					Serviço
				</th>
				<th >
					Nota
				</th>
				<th >
					Série
				</th>
				
				<th >
					Valor da receita
				</th>
				<th >
					Aliquota
				</th>
				<th >
					Imposto
				</th>
				<th >
					Data
				</th>
				<th >
					Valor pago 
				</th>
			</tr>
			
<?
//echo "cod=$codigo";
	$parametro = array( 
		'w08_origem'     => "",
		'w08_cnpj'       => "db_cgccpf(\$w08_cnpj);",
		'w08_nome'       => "", 
		'w08_servico'    => "",
		'w08_nota'       => "",
		'w08_serie'      => "",
		'w08_valreceita' => "db_formatar(\$w08_valreceita, 'f');",
		'w08_aliquota'   => "\$w08_aliquota . '%';",
		'w08_imposto'    => "db_formatar(\$w08_imposto, 'f');",
		'w09_dtpaga'     => "(\$w09_dtpaga != ''?db_formatar(\$w09_dtpaga,'d'):'não efetuado');",
		'w09_valpago'    => "db_formatar(\$w09_valpago, 'f');"
		);
		


if (isset($novomes))
if($novomes=="sim"){ // escolheu o mes

//############ verificar existe notas lançadas para este mês na retenção como tomador (daí tomador)
		
	// parametros para a função monta tabela
	$sql = $cldb_daitomador->sql_query_paga("","w08_origem,w08_cnpj,w08_nome,w08_servico,w08_nota,w08_serie,w08_valreceita,w08_aliquota,w08_imposto,w09_dtpaga,w09_valpago,w08_sequencial,w09_sequencial,w08_mes","","w08_dai = $codigo and W08_mes=$mesdai2");
	//die($sql);
	$res = db_query($sql); 	
	$linhas = pg_num_rows($res);
		
// ######### se existe dados gravados na db_daitomador ai monta a tabela..OK
	if ($linhas != 0 ){
		//echo "linha = $linhas";
		//echo"dados da dai tomador";
		monta_tabela($sql, $parametro);	
	}else{
//########################################### ARRUMAR AQUI #######################################################

 // ######## se não existe dados na db_daitomador... buscar na issplan e issplanit ..OK
   	//if ($linhas == 0 ) 
    	//echo " <br>não existe dados na db_daitomador COD= $codigo";
		
		$sql = "select * from db_dae where w04_codigo=$codigo";
		$result = db_query($sql);
		db_fieldsmemory($result,0);
			
//busca na issplan e issplanit pelo mes, ano , inscriçao... da problema no campo inscr q não existe mais
		$sql2 = "select * from issplan 
							inner join issplanit on q21_planilha=q20_planilha 
							inner join issplaninscr on q20_planilha=q24_planilha 
							where q20_ano = $w04_ano 
							  and q20_mes =$mesdai2 
							  and q24_inscr=$w04_inscr 
								and q21_status =1 
								and q20_situacao <> 5 ";
	//	die($sql2);
		$result2 = db_query($sql2);
		$linhas2 = pg_num_rows($result2); 
// se não tiver dados na issplan e issplanit para esta isncrição... buscar pelo cgm ...OK
		if ($linhas2 == 0){
			//echo "<br> não existe dados na issplan e issplanit para esta isncrição";
			$sql3 = "select q02_numcgm from issbase where q02_inscr =$w04_inscr";
			$result3 = db_query($sql3);
			db_fieldsmemory($result3,0);
			
			$sql4 = "select * from issplan 
			         inner join issplanit on q21_planilha=q20_planilha 
			         where q20_ano    = $w04_ano 
							   and q20_mes    = $mesdai2 
								 and q20_numcgm = $q02_numcgm 
								 and q21_status = 1 
								 and q20_situacao <> 5 ";
			
			$result4 = db_query($sql4);
			$linhas4 = pg_num_rows($result4);
			//echo " <br> existe pelo cgm ...tem $linhas4 linhas no issplan pelo cgm";
		}else{$linhas4 = 0;}

    // se tiver na issplan e issplanit ou pelo inscrição ou pelo cgm
		if ($linhas2 != 0 || $linhas4!=0){
			//echo " <br>Buscou dados do ISSQN retido na fonte<br> ";
			if ($linhas2!=0){
				//db_fieldsmemory($result2,0);
				$linha = $linhas2;
				$res= 2;
			}
			if ($linhas4!=0){
				//db_fieldsmemory($result4,0);
				$linha = $linhas4;
				$res= 4;
			}
			
			//echo "linha =$linha";
			for($i=0;$i<$linha;$i++){
				if($res==2){
					db_fieldsmemory($result2,$i);
				}	
				if($res==4){
					db_fieldsmemory($result4,$i);
				}	
			// se a planilha não estiver paga.........	
						
				
//########### inclui na tabela db_daitomador ...OK

				$cldb_daitomador->w08_dai  = $codigo;
				$cldb_daitomador->w08_mes  = $q20_mes;
				$cldb_daitomador->w08_valreceita  = $q21_valorser;
				$cldb_daitomador->w08_aliquota  = $q21_aliq ;
				$cldb_daitomador->w08_imposto  = $q21_valor;
				$cldb_daitomador->w08_nota  = $q21_nota;
				$cldb_daitomador->w08_serie  = $q21_serie;
				$cldb_daitomador->w08_cnpj = $q21_cnpj;
				$cldb_daitomador->w08_nome  = $q21_nome;
				$cldb_daitomador->w08_servico  = $q21_servico;
				$cldb_daitomador->w08_origem  = "planilha".$q20_planilha;
				$cldb_daitomador->incluir(null);
			
			
		
		    // se a planilha não estiver paga.........	
				if($q20_numpre==0 || $q20_numpre==""){
					//echo "<br>a planilha $q20_planilha não tem numpre";
				}else{
					// se a planilha ja tiver um numpre, verificar se esta paga, buscar a data de pagamento e o valor.	
					//$sql="select k00_dtoper,k00_dtpaga,k00_valor,k00_dtvenc,k00_numpre from issplan inner join arrepaga on q20_numpre=k00_numpre where k00_numpre= $q20_numpre";	
					//$sql = "select k00_dtoper,k00_dtpaga,k00_valor,k00_dtvenc,k00_numpre from arrepaga where k00_numpre= $q20_numpre";	
					
					$sql="select dtpago,vlrpago,k00_dtoper,k00_dtpaga,k00_valor,k00_dtvenc,arrepaga.k00_numpre ,coalesce(dtpago,k00_dtpaga) as data
						from issplan inner 
						join arrepaga on q20_numpre=k00_numpre 
						left outer join arreidret r on r.k00_numpre =  q20_numpre
						left join disbanco on r.k00_numpre=disbanco.k00_numpre 
						where q20_numpre=$q20_numpre and  q20_situacao <> 5 ";
					
					//die($sql);
					$result = db_query($sql);
					$linhaspaga = pg_num_rows($result);
					if ($linhaspaga>0){
						db_fieldsmemory($result,0);
						
					  $cldb_daitomadorpaga->w09_daitomador  = $cldb_daitomador->w08_sequencial;
						$cldb_daitomadorpaga->w09_dtpaga  = $data;
						$cldb_daitomadorpaga->w09_valpago  = $k00_valor;
						$cldb_daitomadorpaga->incluir(null);
							    
						if ($cldb_daitomadorpaga->erro_status == 0) {
							 $sqlerro = true;
							 //echo"entrei no erro dodaitomadorpaga";
							 $erro_msg = $cldb_daitomadorpaga->erro_msg;
						}
					}	
				}
			
			}
		
// mostra na tabela...OK
$sql = $cldb_daitomador->sql_query_paga("","w08_origem,w08_cnpj,w08_nome,w08_servico,w08_nota,w08_serie,w08_valreceita,w08_aliquota,w08_imposto,w09_dtpaga,w09_valpago,w08_sequencial,w09_sequencial,w08_mes","","w08_dai = $codigo and W08_mes=$mesdai2");
monta_tabela($sql, $parametro);	
// fim da tabela
			
		}
		
		
    }	
} // se não escolheu o mes 	
 
//if (!isset($mesdai)){ // se não foi selecionado o mes é a primeira vez que entrei	
//echo "nada";	
//}

if (isset($excluir) || isset($salvar)){
$sql = $cldb_daitomador->sql_query_paga("","w08_origem,w08_cnpj,w08_nome,w08_servico,w08_nota,w08_serie,w08_valreceita,w08_aliquota,w08_imposto,w09_dtpaga,w09_valpago,w08_sequencial,w09_sequencial,w08_mes","","w08_dai = $codigo and W08_mes=$mesdai2");
monta_tabela($sql, $parametro);		
}
	
?>
		</table>
	</td>
</tr>

</table>
<iframe name="pesquisanota"  style="visibility:hidden"></iframe>
</form>
</body>
</html>