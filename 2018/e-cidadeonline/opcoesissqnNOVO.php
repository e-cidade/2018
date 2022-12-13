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
include("classes/db_issplan_classe.php");
include("classes/db_issplanit_classe.php");
include("classes/db_issplaninscr_classe.php");
include("classes/db_issplanitinscr_classe.php");
include("dbforms/db_funcoes.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

//Máximo de s lançadas
$int_max = 79;

//busca dados para armazemar em cookies
if(@$_COOKIE["cookie_codigo_cgm"]==""){
	// cgm
 	if(@$codigo_cgm!=""){
 		$result  = $clcgm->sql_record($clcgm->sql_query("","cgm.z01_numcgm,cgm.z01_nome","","cgm.z01_numcgm = $codigo_cgm"));
 	 	$s2 = $clcgm->numrows;
 	}
 	db_fieldsmemory($result,0);
 	@setcookie("cookie_codigo_cgm",$z01_numcgm);
 	@setcookie("cookie_nome_cgm",$z01_nome);
 	@$cookie_codigo_cgm = $z01_numcgm;
}else{
 	@$cookie_codigo_cgm = $_COOKIE["cookie_codigo_cgm"];
}

$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitaissqn.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  	if(!session_is_registered("DB_acesso"))
    	echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}

$db_verifica_ip = db_verifica_ip();
mens_help();
$dblink="digitaissqn.php";
db_logs("","",0,"Digita Codigo do Contribuinte.");
postmemory($HTTP_POST_VARS);

//  tira a formatação do cnpj 
$clquery = new cl_query;

    
      
// ##########  Se for a primeira vez que entrei ######################################  
if(isset($primeiravez)){
$cgccpf = str_replace(".","",$cgc);
    $cgccpf = str_replace("/","",$cgccpf);
    $cgccpf = str_replace("-","",$cgccpf);  
//#################### se foi preenchido a inscrição #################################
	if ($inscricaow!=""){
  		
  		$sql = "select * from issbase inner join cgm on z01_numcgm = q02_numcgm where q02_inscr=$inscricaow and z01_cgccpf = $cgccpf";    
  		$result = db_query($sql);
   		if(pg_numrows($result)!=0){  // cnpj e inscriçõs corretos
   			db_fieldsmemory($result,0);
   		}else{ // cnpj ou inscrição invalido
   			redireciona("digitaissqn.php?".base64_encode('erroscripts=Acesso a Rotina Inválido, verifique os dados digitados!'));
   		}
  	}
//#################### se não foi preenchido a inscrição #################################	
	if ($inscricaow==""){ 
  		$sql1 = "select z01_numcgm,z01_cgccpf,z01_nome from cgm where z01_cgccpf = '$cgccpf'";
  		$result1 = db_query($sql1);
   		if(pg_numrows($result1)!=0){  // cnpj correto... buscar inscrição
   			db_fieldsmemory($result1,0);
   			$sql2= "select * from issbase where q02_numcgm = '$z01_numcgm' and  q02_dtbaix is null";
   			$result2 = db_query($sql2);
   			if(pg_numrows($result2)!=0){// cnpj do municipio
   				db_fieldsmemory($result2,0);
   				$inscricaow = $q02_inscr; 
   		 		//echo "insc = $inscricaow";
   			}else{
   				$municipio = "nao";//cnpj não é do municipio
   				
   			}	
  		}else{ // cnpj invalido
  			redireciona("digitaissqn.php?".base64_encode('erroscripts=Acesso a Rotina Inválido, verifique os dados digitados!'));
  			
  		}
  	}

  	
// vê se ja existe alguma planilha para este mes e ano selecionado
   $result3 = db_query("select * from issplan where q20_ano = $ano and q20_mes=$mes and q20_numcgm= $z01_numcgm order by q20_mes");
    if(pg_numrows($result3)!=0){
   		db_fieldsmemory($result3,0);
   		// tem planilha
   		redireciona("planilha.php?nomecontri=".$q20_nomecontri."&fonecontri=".$q20_fonecontri."&inscricaow=".$inscricaow."&mesx=".$mesx."&mes=".$mes."&ano=".$ano."&numcgm=".$z01_numcgm."&nomes=".$z01_nome);
    }
  
  }
  
// ################# função ######################################
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
 		echo "<tr class=\"titulo2\">";
 		

	 	
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
	 		 	
	 	echo" 
	 	<td width= \"120px\">
																													
				  <input class=\"botao\" name=\"alterar\" type=\"button\" value=\"Alterar\" onClick=\"js_alterar('$q31_sequencial','$q31_inscr','$q21_sequencial','$q21_planilha','$q21_cnpj','$q21_nome','$q21_servico','$q21_nota','$q21_serie','$q21_valorser','$q21_aliq','$q21_valor')\">
					
				  <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"js_excluir('$q21_sequencial','$q31_sequencial')\">
   			      
				  					  
				</td>";
		
	 	echo "</tr>";
 	}
}//######################## termina a função #######################################  
  
 
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>

function js_excluir(x,y){
		var obj = document.form1;
		obj.pkq21_sequencial.value  = x;
		obj.pkq31_sequencial.value = y;
		
}
function js_alterar(seq,ins,seq2,plan,cnpj,nome,ser,nota,serie,valor,ali,imp){
		var obj1 = document.form1;
		obj1.pkq21_sequencial.value = seq2;
		obj1.pkq31_sequencial.value = seq;
		obj1.inscricao.value = ins;
		obj1.planilha.value = plan;
		obj1.cnpj.value = cnpj;
		obj1.nomerazao = nome;
		obj1.sprestado.value = ser;
		obj1.numnota.value = nota;
		obj1.numserie.value = serie;
		obj1.valservico.value = valor;
		obj1.aliquota.value = ali;
		obj1.total.value = imp;
		obj1.grava.value= "Alterar nota acima";
}

function js_veri(){
  if(document.form1.valservico.value.indexOf(",")!=-1){
    var  vals= new Number(document.form1.valservico.value.replace(",","."));
    document.form1.valservico.value = vals.toFixed(2);
  }else{
    var vals = new Number(document.form1.valservico.value); 
    document.form1.valservico.value = vals.toFixed(2);
  }
  if(isNaN(vals)){
    alert("Verifique o valor!  (ex: 1500.00 com ponto somente no centavos )");
    document.form1.valservico.value = "";
    document.form1.total.value = "";
    document.form1.valservico.focus();
    return false;
  } 
  var aliquota = new Number(document.form1.aliquota.value.replace(",","."));
  vals = new Number((vals *(aliquota/100))); 
  document.form1.total.value=vals.toFixed(2);
}

function maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_cnpj(obj){
  var retorno = js_verificaCGCCPF(obj,'');
  if(retorno == false)
    obj.focus();
  else  
    document.submit();
}
function js_vericampos(){
 var alerta="";
 var chi = document.createElement("INPUT"); 
 chi.setAttribute("type","hidden");    
 chi.setAttribute("name","guarda");
  jcnpj=document.form1.cnpj.value;
  jinscricao=document.form1.inscricao.value;
  jnomerazao=document.form1.nomerazao.value;
  jsprestado=document.form1.sprestado.value;
  jnumnota=document.form1.numnota.value;
  jnumserie=document.form1.numserie.value;
  jvalservico=document.form1.valservico.value;
  jaliquota=document.form1.aliquota.value;

  if(jcnpj==""){
    alerta +="CNPJ\n";
  }
  if(jnomerazao==""){
    alerta +="Nome/Razão Social\n";
  }
  if(jsprestado==""){
    alerta +="Serviço Prestado\n";
  }
  if(jvalservico==""){
    alerta +="Serviço Prestado\n";
  }
  if(jnumnota==""){
    alerta +="Numero da Nota\n";
  }
  /*
  * Comentado a pedido da Lisa de Guaíba
  * Cristian - 05/05/2006
  */
  //if(jnumserie==""){
  //  alerta +="Numero da Série\n";
  //}
  if(jaliquota==""){
    alerta +="Valor da Alíquota\n";
  }
  var expr = /[^0-9]+/;
  if(jinscricao.match(expr) != null){
    alerta+="Inscrição Inválida";
  }
  if (document.form1.cnpj.value.length == 14) {
    var retorna = js_verificaCGCCPF(document.form1.cnpj,'');
  } else {
    var retorna = js_verificaCGCCPF('',document.form1.cnpj);
  }
  if(retorna && alerta == ""){
    document.form1.appendChild(chi);  
    document.form1.submit();
  }  
  if(alerta!=""){
    alert("Verifique os seguintes campos:\n"+alerta);
    return false;
  }else{
    return true;
  }
}
function  abre(){
  window.open('relatoriopdf.php?planilha=<?=@$planilha?>' ,'Ralatorio','toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no');
  return false;
}
function js_cgccpf(obj){
//  js_verificaCGCCPF(obj,'');
}
function js_buscainscr(){
  if(document.form1.inscricao.value == ''){
    alert('Preencha uma inscrição antes de efetuar a busca.');
  }else{
    if(document.form1.inscricao.value == <?=$inscricaow?>){
      alert('Você não deve preencher aqui a sua própria inscrição!\nEste campo deve ser preenchido com a inscrição da empresa da qual o imposto foi retido.\nEsta regra vale somente para empresas do município.\nCaso a empresa da qual o imposto foi retido não seja do município este campo ficará em branco.');
      document.form1.inscricao.value = '';
      document.form1.inscricao.focus();
    }else{
      pesquisainscr.location.href = 'pesquisainscricao.php?inscr='+document.form1.inscricao.value;
    }
  }
}  
function js_preenchedados(d1,d2,d3){
  document.form1.cnpj.value = d1;
  document.form1.nomerazao.value = d2;
  document.form1.sprestado.value = d3;
  document.form1.numnota.focus();
}  
function js_erropesquisa(inscr){
  alert('Inscrição:'+inscr+' não encontrada');
  document.form1.inscricao.value = '';
  document.form1.inscricao.focus();
} 
function js_limpa(){
	var obj1 = document.form1;
	obj1.inscricao.value ='';
	obj1.cnpj.value = '';
	obj1.nomerazao.value ='';
	obj1.sprestado.value='';
	obj1.numnota.value ='';
	obj1.numserie.value = '';
	obj1.valservic.value='';
	obj1.total.value='';
} 

function js_verificanota(cnpj,nota,serie){
	pesquisanota.location.href = 'pesquisanota.php?cnpj='+cnpj+'&nota='+nota+'&serie='+serie;
}

function js_notaexiste(){
	document.form1.numnota.value='';
	document.form1.numserie.value='';
	document.form1.numnota.focus();
}
</script>

<style type="text/css">
<?db_estilosite();?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<form name="form1" method="post" action="opcoesissqn.php?cgc=$cgc">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
    <input name="cpf" type="hidden" value="<?=@$cpf?>">
    <input name="mes" type="hidden" value="<?=@$mes?>">
    <input name="ano" type="hidden" value="<?=$ano?>">
    <input name="cgc" type="hidden" value="<?=@$cgc?>">
    <input name="inscricaow" type="hidden" value="<?=@$inscricaow?>">
    <input name="nomecontri" type="hidden" value="<?=@$nomecontri?>">
    <input name="fonecontri" type="hidden" value="<?=@$fonecontri?>">
    <input name="modificando" type="hidden" value="<?=@$modificando?>">
    <input name="plani" type="hidden" value="<?=@$plani?>">
    <input name="z01_nome" type="hidden" value="<?=@$z01_nome?>">
    <input name="z01_numcgm" type="hidden" value="<?=isset($numcgm)?$numcgm:@$z01_numcgm?>">
    <input name="ttt" type="hidden">
    <input name="planilha" type="hidden" value="<?=@$planilha?>">
	<input name="nova" type="hidden" value="<?=@$nova?>">	
	<input name="pkq21_sequencial" type="hidden" value="">	
	<input name="pkq31_sequencial" type="hidden" value="">	
    <tr>
    	<td width="100%" colspan="5">
<!-- ############ aki começa a montar a tabela ############################################# -->      
        <table  width="100%" border="0" class="texto">
        	<tr>
          		<td colspan="3" align="center" style="border: 1px solid">
            	<strong>DADOS DO TOMADOR DO SERVIÇO</strong>
          		</td>
        	</tr>
         	<tr>
            	<td width="19%" colspan="0" nowrap><small><b>Nome
              		ou Raz&atilde;o Social:</b>
             	 	<font color="<?=$w01_corfontesite?>"> <?=$z01_nome?></font>
              		</small>
            	</td>
            	<?if (isset($municipio)){
            		echo"
            		<td width='19%' <small>
	             	<font color='$w01_corfontesite'> Empresa de fora do município</font>
	              	</small>
					";
            		           			
            		}else{
            		echo"	
            		<td width='19%' <small><b>Inscrição:</b>
	             	<font color='$w01_corfontesite'> $inscricaow</font>
	              	</small>";
            		}
            		?>
            	</td>
            
                    
            	<td width="19%" nowrap><small><b>Competência:</b>
             		<font color="<?=$w01_corfontesite?>"> <?=db_mes($mes)?> de <?=$ano?> </font>
           		</td>
          	</tr>
          	<tr>
	            <td align="left" colspan="3"><small><b>Contato:</b></small>
		            <input type="text" maxlength="40" name="nomecontri" value="<?=@$nomecontri?>" size="14" >
		            <small><b>Fone:</b></small>
		            <input type="text" maxlength="15" name="fonecontri" value="<?=@$fonecontri?>" size="14">
	            </td>
            	
          	</tr>
        </table>
      	</td>
    </tr>
    <tr>
      	<td colspan="5"> 
      	<table width="100%" border="0" class="texto">
      	    <tr>
	        	<td colspan="3" align="center" style="border: 1px solid">
	        		<strong>DADOS DO PRESTADOR DO SERVIÇO</strong>
	        	</td>
    		</tr>
        	<tr height="23" valign="top">
       			<td align="center" nowrap><b><small>Inscri&ccedil;&atilde;o:</small></b> <input name="inscricao" type="text" value="<?=@$inscricao?>" size="7" maxlength=6>
        		</td>
        		<td valign="top">
	            	<input name="buscadados" class="botao" type="button"  value="Busca Dados" onclick="js_buscainscr() " >
	            </td>
	            <td>
	                <font size="1" face="arial">* Este campo deve ser preenchido somente em casos em que a empresa da qual o imposto foi retido seja do município. Caso você não tenha esta informação deixe o campo em branco.</font>
	            </td>
            </tr>
        </table>		
        </td>
    </tr>
    <tr>
        <td align="left"><b><small>CPF ou CNPJ</small></b>
        </td>
        <td  align="left" colspan = "2"><b><small>Nome ou Razão Social</small></b>
        </td>
        <td  colspan="2" align="left" colspan = "2"><b><small>Servi&ccedil;o Prestado</small></b>
        </td>
    </tr>
    <tr>
        <td><input name="cnpj" type="text" value="<?=@$cnpj?>" id="cnpj" size="18" maxlength="18" onBlur="(this.value == '')?'':js_cgccpf(this)">
        </td>
        <td align="left" colspan = "2"><input name="nomerazao" value="<?=@$nomerazao?>" type="text" size="40" maxlength="30" onKeyUp="maiusculo(this)">
        </td>
        <td align="left" colspan="2" ><input name="sprestado" value="<?=@$sprestado?>" type="text" id="sprestado3" size="41" onKeyUp="maiusculo(this)" maxlength="40">
        </td>
    </tr>
    <tr>
        <td><b><small>Nota</small></b>
        </td>
        <td ><b><small>S&eacute;rie</small></b>
        </td>
        <td align="left" nowrap ><b><small>Valor Bruto</small></b>
        <td align="left" ><b><small>Aliquota</small></b>
        </td>
        <td ><b><small>Valor Total</small></b>
        </td>
    </tr>
    <tr>
        <td><input name="numnota" type="text" id="numnota3" maxlength="10" size="10">
        </td>
        <td ><small>
        	<input name="numserie" type="text" id="numserie4" size="10" maxlength="5" onchange="js_verificanota(document.form1.cnpj.value,document.form1.numnota.value, document.form1.numserie.value)">
            </small>
        </td>
        <td align="left" nowrap >
               <small>R$</small>
               <input name="valservico" type="text"   id="valservico3" onChange="return js_veri();"  size="10">
        </td>
        <td align="left" nowrap>
              <?
             
              $numcgm = isset($numcgm)?$numcgm:@$z01_numcgm;
             
// ############################# aliquota ######################################################              
              // q81_cadcalc = 3 ... 3 = issqn variavel
              $sql_base = "select distinct q81_valexe from tipcalc where q81_cadcalc = 3 and q81_usaretido is true";
              $query = db_query($sql_base);
              $s = pg_num_rows($query);
              if($w13_aliqissretido=="f"){?> 
               <select name="aliquota" onBlur="return js_veri();">
                 <?
                 for($xx=0;$xx<$s;$xx++){
                 db_fieldsmemory($query,$xx);
                 ?>
                 <option value="<?=$q81_valexe?>"><?=$q81_valexe?>%</option>
                 <?}?></select><?
              }else{// não entra aki
               db_fieldsmemory($query,0);
              ?>
                <select name="aliquota" onBlur="return js_veri();" onselect="return js_veri();">
                 <option value="0">0%</option>
                 <option value="0.5">0.5%</option>
                 <option value="1" <?if(isset($q81_valexe)&&$q81_valexe=="1"){echo "selected";}?>>1%</option>
                 <option value="1.5" <?if(isset($q81_valexe)&&$q81_valexe=="1.5"){echo "selected";}?>>1.5%</option>
                 <option value="2" <?if(isset($q81_valexe)&&$q81_valexe=="2"){echo "selected";}?>>2%</option>
                 <option value="2.5" <?if(isset($q81_valexe)&&$q81_valexe=="2.5"){echo "selected";}?>>2.5%</option>
                 <option value="3" <?if(isset($q81_valexe)&&$q81_valexe=="3"){echo "selected";}?>>3%</option>
                 <option value="3.5" <?if(isset($q81_valexe)&&$q81_valexe=="3.5"){echo "selected";}?>>3.5%</option>
                 <option value="4" <?if(isset($q81_valexe)&&$q81_valexe=="4"){echo "selected";}?>>4%</option>
                 <option value="4.5" <?if(isset($q81_valexe)&&$q81_valexe=="4.5"){echo "selected";}?>>4.5%</option>
                 <option value="5" <?if(isset($q81_valexe)&&$q81_valexe=="5"){echo "selected";}?>>5%</option>
                 <option value="5.5" <?if(isset($q81_valexe)&&$q81_valexe=="5.5"){echo "selected";}?>>5.5%</option>
                 <option value="6" <?if(isset($q81_valexe)&&$q81_valexe=="6"){echo "selected";}?>>6%</option>
                 <option value="6.5" <?if(isset($q81_valexe)&&$q81_valexe=="6.5"){echo "selected";}?>>6.5%</option>
                 <option value="7" <?if(isset($q81_valexe)&&$q81_valexe=="7"){echo "selected";}?>>7%</option>
                 <option value="7.5" <?if(isset($q81_valexe)&&$q81_valexe=="7.5"){echo "selected";}?>>7.5%</option>
                 <option value="8" <?if(isset($q81_valexe)&&$q81_valexe=="8"){echo "selected";}?>>8%</option>
                 <option value="8.5" <?if(isset($q81_valexe)&&$q81_valexe=="8.5"){echo "selected";}?>>8.5%</option>
                 <option value="9" <?if(isset($q81_valexe)&&$q81_valexe=="9"){echo "selected";}?>>9%</option>
                 <option value="9.5" <?if(isset($q81_valexe)&&$q81_valexe=="9.5"){echo "selected";}?>>9.5%</option>
                 <option value="10" <?if(isset($q81_valexe)&&$q81_valexe=="10"){echo "selected";}?>>10%</option>
                </select>
              <?}?>
              
              
        </td> 
        <td>     
        	  <small>R$
              <input name="total" type="text" size="10" readonly onfocus="document.form1.guarda.focus();">
        </small>
        </td>
	</tr>
    <tr>
      	<td colspan="5"> 
       		 <input name="limpa" class="botao" type="submit"  value="Limpa campos" onclick="js_limpa()">&nbsp;&nbsp;
       		 <input name="grava" class="botao" type="submit"  value="Lança Valor" onclick="return js_vericampos()">
       	</td>
    </tr>
<?

$cl_issplan = new cl_issplan;
$cl_issplanit = new cl_issplanit;
$cl_issplaninscr = new cl_issplaninscr;
$cl_issplanitinscr = new cl_issplanitinscr;


// ############ incluir no banco ##########################

if (isset($grava)){
	if ($grava!="Alterar nota acima"){
	$sqlerro = false;
	echo"<script> var nova1 = Number(document.form1.nova.value);
	document.form1.nova.value = nova1+(1);	
	</script>"; 
	db_inicio_transacao();

	if($nova==""){ // se for a primeira nota a ser lançada
		echo "primeira nota";
		$cl_issplan-> q20_numcgm = $z01_numcgm;
		$cl_issplan-> q20_ano = $ano;
		$cl_issplan-> q20_mes = $mes;
		$cl_issplan-> q20_nomecontri = @$nomecontri;
		$cl_issplan-> q20_fonecontri = @$fonecontri;
		$cl_issplan-> q20_numpre = 0;
		$cl_issplan-> q20_numbco = 0;		
		$cl_issplan->incluir(null);
		
		if ($cl_issplan->erro_status == 0) {
				$sqlerro = true;
				echo"entrei no erro do issplan.....";
				die($cl_issplan->erro_sql);
				$erro_msg = $cl_issplan->erro_msg;
			}
		
		if ($sqlerro==false)	{
		
			if ($inscricaow != ""){
				
				$cl_issplaninscr-> q24_planilha = $cl_issplan->q20_planilha ;
				$cl_issplaninscr-> q24_inscr = $inscricaow;
				$cl_issplaninscr-> incluir(null);
			
				if ($cl_issplaninscr->erro_status == 0) {
					$sqlerro = true;
					echo"entrei no erro do issplaninscr.....";
					die($cl_issplaninscr->erro_sql);
					$erro_msg = $cl_issplaninscr->erro_msg;
				}
			}
		}
			
	//guardar o numero da planilha
	$planilha = $cl_issplan-> q20_planilha;
	echo"<script>document.form1.planilha.value= $planilha; </script>"; 
	}
	
	if($sqlerro==false)	{
	    echo "....nota = @$nova...planilha = @$planilha";
		// insere as notas
		$cl_issplanit-> q21_planilha = $planilha;	
		$cl_issplanit-> q21_cnpj = $cnpj;
		$cl_issplanit-> q21_nome = $nomerazao;
		$cl_issplanit-> q21_servico = $sprestado;
		$cl_issplanit-> q21_nota = $numnota;
		$cl_issplanit-> q21_serie = $numserie;
		$cl_issplanit-> q21_valorser = $valservico;
		$cl_issplanit-> q21_aliq = $aliquota;
		$cl_issplanit-> q21_valor = $total;
		$cl_issplanit->incluir(null);
		
		if ($cl_issplanit->erro_status == 0) {
					$sqlerro = true;
					echo"entrei no erro do issplanit.....";
					die($cl_issplanit->erro_sql);
					$erro_msg = $cl_issplanit->erro_msg;
				}
	}
	
	if($sqlerro==false)	{
	if ($inscricao!=""){
		$cl_issplanitinscr->q31_issplanit = $cl_issplanit-> q21_sequencial;
		$cl_issplanitinscr->q31_inscr = $inscricao;
		$cl_issplanitinscr->incluir(null);
		
		if ($cl_issplanitinscr->erro_status == 0) {
					$sqlerro = true;
					echo"entrei no erro do issplanitinscr.....";
					die($cl_issplanitinscr->erro_sql);
					$erro_msg = $cl_issplanitinscr->erro_msg;
		}
	
	}
	}
db_fim_transacao($sqlerro);
	}
}

//################### excluir #####################################
if (isset($excluir)){
	echo "entrouu aki no exluir";
	$cl_issplanitinscr->excluir($pkq31_sequencial);
    $cl_issplanit->excluir($pkq21_sequencial);
} 

//################### alterar #####################################
if (isset($grava)){
	if ($grava=="Alterar nota acima"){
		echo "entrou no alterarrrrrrrrrr ";
		
		$cl_issplanit-> q21_sequencial = $pkq21_sequencial;
		$cl_issplanit-> q21_planilha = $planilha;	
		$cl_issplanit-> q21_cnpj = $cnpj;
		$cl_issplanit-> q21_nome = $nomerazao;
		$cl_issplanit-> q21_servico = $sprestado;
		$cl_issplanit-> q21_nota = $numnota;
		$cl_issplanit-> q21_serie = $numserie;
		$cl_issplanit-> q21_valorser = $valservico;
		$cl_issplanit-> q21_aliq = $aliquota;
		$cl_issplanit-> q21_valor = $total;
		$cl_issplanit->alterar($pkq21_sequencial);
		
		$cl_issplanitinscr->q31_sequencial = $pkq31_sequencial;
		$cl_issplanitinscr->q31_issplanit = $pkq21_sequencial;
		$cl_issplanitinscr->q31_inscr = $inscricao;
		$cl_issplanitinscr->alterar($pkq31_sequencial);
	}
}		

// ............ monta tabela ...............


//echo "planilha ............ $planilha";

?>

<tr>	
	<td colspan="5"><br>
		<table width="100%" border="1" cellspacing="0" cellpadding="0" class="texto" >
			<tr  bgcolor="#CCCCCC" >
				<td> INSCRIÇÃO</td>
				<td> CNPJ</td>
				<td> NOME</td>
				<td> SERVIÇO</td>
				<td> NOTA</td>
				<td> SERIE</td>
				<td> VALOR</td>
				<td> ALIQUOTA</td>
				<td> VALOR TOTAL</td>
				<td> BOTÃO</td>
			</tr>
				
			<?
			
			$parametro = array( 
				'q31_inscr'		 => "",
				'q21_cnpj'       => "db_cgccpf(\$q21_cnpj);",
				'q21_nome'       => "", 
				'q21_servico'    => "",
				'q21_nota'       => "",
				'q21_serie'      => "",
				'q21_valorser' => "db_formatar(\$q21_valorser, 'f');",
				'q21_aliq'   => "\$q21_aliq . '%';",
				'q21_valor'    => "db_formatar(\$q21_valor, 'f');"
			
				);
			
			if (isset($planilha)){
				$sql = "select * from issplanit left join issplanitinscr on q21_sequencial=q31_issplanit where q21_planilha= $planilha";
				monta_tabela($sql,$parametro);	
			}
			?>

		</table>
	</td>
</tr>


</table>
<iframe name="pesquisainscr" style="visibility:hidden"></iframe>
<iframe name="pesquisanota"  style="visibility:hidden"></iframe>

</form>   
</body>
</html>