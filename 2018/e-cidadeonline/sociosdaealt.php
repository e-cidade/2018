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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
db_logs("","",0,"cadastro de socios do dai.");
postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function js_maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_verilinhas(){
  if(document.getElementById('linhas').rows.length == 1){
    alert("Não há valores registrados");
    return false;
  }
}  
var contador = 0;
function js_vericampos(){
  var alerta="";
  if(document.form1.cgc.value!=""){
    cgccpf=document.form1.cgc.value;
  }else{
    cgccpf=document.form1.cpf.value;
  }   
  rg=document.form1.rg.value;
  nome=document.form1.nome.value;
  ender=document.form1.ender.value;
  numero=document.form1.numero.value;
  compl=document.form1.compl.value;
  bairro=document.form1.bairro.value;
  cep=document.form1.cep.value;
  uf=document.form1.uf.value;
  percentual=document.form1.percentual.value;
  if(cgccpf==""){
    alerta +="CNPJ/CPF\n";
  }
  if(nome==""){
    alerta +="Nome\n";
  }
  if(ender==""){
    alerta +="Endereço\n";
  }
  if(bairro==""){
    alerta +="Bairro\n";
  }
  if(cep==""){
    alerta +="Cep\n";
  }
  if(uf==""){
    alerta +="UF\n";
  }
  if(percentual==""){
    alerta +="Percentual de Sociedade\n";
  }
  if(alerta!=""){
    alert("Verifique os seguintes campos:\n"+alerta);
    return false;
  }else{
    tabela = document.getElementById('linhas');
    contador += 1;
    novalinha = tabela.insertRow(tabela.rows.length);
    novalinha.id = contador;
    novacoluna = novalinha.insertCell(0);
    novacoluna.style.fontSize = '12px';
    novacoluna.align = 'center';
    novacoluna.innerHTML = nome;
    novacoluna = novalinha.insertCell(1);
    novacoluna.style.fontSize = '12px';
    novacoluna.align = 'center';
    novacoluna.innerHTML = ender;
    novacoluna = novalinha.insertCell(2);
    novacoluna.style.fontSize = '12px';
    novacoluna.align = 'center';
    valores = document.createElement("input");
    valores.setAttribute('type','hidden');
    valores.setAttribute('name','valores'+contador);
    valores.setAttribute('value',cgccpf+'#'+rg+'#'+nome+'#'+ender+'#'+numero+'#'+compl+'#'+bairro+'#'+cep+'#'+uf+'#'+percentual);
    document.form1.appendChild(valores);
    document.form1.tamanho.value = contador;
    excluir = document.createElement("input");
    excluir.setAttribute('type','button');
    excluir.setAttribute('name','valores'+contador);
    novacoluna.innerHTML = '<input type="button" value="Excluir" class="botao" onClick="js_deletalinha('+ contador + ',' + valores.name +')"><input type="button" value="Alterar" class="botao" onClick="js_alterar('+cgccpf+','+rg+','+nome+','+ender+','+numero+','+compl+','+bairro+','+cep+','+uf+','+percentual+','+ contador+','+valores.name+')">';
    novacoluna.innerHTML = '<input type="button" value="Excluir" class="botao" onClick="js_deletalinha('+ contador + ',' + valores.name +')"><input type="button" value="Alterar" class="botao" onClick="js_alterar('+cgccpf+','+rg+','+nome+','+ender+','+numero+','+compl+','+bairro+','+cep+','+uf+','+percentual+','+ contador+','+valores.name+')">';
   alert(contador);
  }
}
function js_alterar(cgccpf,rg,nome,ender,numero,compl,bairro,cep,uf,percentual,obj,obj1){
    alert(contador);
    cgccpf = new String(cgccpf);
    if(cgccpf.length == 18){
      document.form1.cgc.value = cgccpf; 
    }else{  
      document.form1.cpf.value = cgccpf; 
    }  
    document.form1.rg.value = rg; 
    document.form1.nome.value = nome; 
    document.form1.ender.value = ender; 
    document.form1.numero.value = numero; 
    document.form1.compl.value = compl; 
    document.form1.bairro.value = bairro; 
    cep = new String(cep);
    cep = cep.replace('-','');
    cinco = cep.substr(0,5);
    tres = cep.substr(5,3)
    cep = cinco+'-'+tres;  
    document.form1.cep.value = cep; 
    document.form1.uf.value = uf; 
    document.form1.percentual.value = percentual; 
    js_deletalinha(obj,obj1);
}
function js_deletalinha(obj,obj1){
      eval('document.form1.'+obj1+'.value = ""');
      document.getElementById('linhas').deleteRow(obj); 
}
</script>
<style type="text/css">
<?
db_estilosite();
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<?
mens_div();
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
  <tr>
    <td align="left" valign="top">
      <form name="form1" action="opcoesdae.php?inscricaow=<?=$inscricaow?>" method="post">
        <input type="hidden" name="tamanho">
	  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
	    <tr>
	      <td>
	        <table cellpadding="3" cellspacing="0">
   	          <tr> 
	            <td align="left">CNPJ:&nbsp;<br>
	            <input name="cgc" type="text" class="digitacgccpf" onKeyDown="FormataCNPJ(this,event)" size="18" maxlength="18"></td>
	            <td align="left">CPF:&nbsp;<br>
	            <input name="cpf" type="text" class="digitacgccpf" id="cpf" size="14" maxlength="14" onKeyDown="FormataCPF(this,2)"></td>
	            <td align="left">RG:&nbsp;<br>
	            <input name="rg" type="text" class="digitacgccpf" size="10" maxlength="10"></td>
	            <td align="left">Nome:&nbsp;<br>
	            <input name="nome" type="text" size="40" maxlength="40" onKeyUp="js_maiusculo(this)"></td>
	          </tr>
		</table>
	      </td>
	    </tr>  
	    <tr> 
	      <td>
	        <table cellpadding="3" cellspacing="0">
   	          <tr> 
	            <td align="left">Endereço:&nbsp;<br>
	            <input name="ender" type="text" size="40" maxlength="40" onKeyUp="js_maiusculo(this)"></td>
	            <td align="left">Número:&nbsp;<br>
	            <input name="numero" type="text" size="6" maxlength="6"></td>
	            <td align="left">Compl.:&nbsp;<br>
	            <input name="compl" type="text" size="10" maxlength="10" onKeyUp="js_maiusculo(this)"></td>
	          </tr>
	        </table>
	      </td>
	    </tr> 
	    <tr> 
	      <td>
	        <table cellpadding="3" cellspacing="0">
   	          <tr> 
	            <td align="left">Bairro:&nbsp;<br>
	            <input name="bairro" type="text" size="40" maxlength="40" onKeyUp="js_maiusculo(this)"></td>
	            <td align="left">Cep:&nbsp;<br>
	            <input name="cep" type="text" size="9" maxlength="9" onKeyDown="(this.value.length == 5)?this.value=this.value+'-':''"></td>
	            <td align="left">UF:&nbsp;<br>
	            <input name="uf" type="text" size="2" maxlength="2" onKeyUp="js_maiusculo(this)"></td>
	            <td align="left">Sociedade:&nbsp;<br>
	            <input name="percentual" type="text" size="10" maxlength="10">&nbsp;%</td>
	          </tr>
		</table>
	      </td>
	    </tr>  
            <tr> 
              <td> 
    	        <input name="guarda" class="botao" type="button"  value="Incluir Ítem" onclick="return js_vericampos();"> 
 	        <input name="salvasocios" class="botao" type="submit"  value="Salvar" > 
              </td>
            </tr>
	    <tr>
	      <td>
		<table id="linhas" width="490" cellpadding="0" cellspacing="0" border="1" >
		  <tr bgcolor="<?=$w01_corfundomenuativo?>" align="center">
		    <td width="50%" id="colocar">
		      Nome
		    </td>
		    <td width="50%">
		      Endereço.
		    </td>
		  </tr>
		  <?
		    if(!isset($primeira)){
		      exit;
		    }  
		    if(isset($inscricaow)){
		    $result = db_query("select * from db_dae where w04_inscr = $inscricaow");
		    if(pg_numrows($result) == 0){
			echo"<script>alert('sasasasa')</script>";
			exit;
		      db_redireciona("sociosdae.php?inscricaow=$inscricaow");
		      exit;
		    }else{
		      db_fieldsmemory($result,0);
		      $result = db_query("select * from db_daesocios where w06_codigo = $w04_codigo");
		      if(pg_numrows($result) == 0){
			db_redireciona("sociosdae.php?inscricaow=$inscricaow");
			exit;
		      }else{
			for($i = 0;$i < pg_numrows($result);$i++){
			db_fieldsmemory($result,$i,true);
			$cgccpf = $w06_cgccpf;
			if(strlen(trim($cgccpf)) == 14)
			  $cgccpf = db_formatar($cgccpf,'cnpj');
			else  
			  $cgccpf = db_formatar($cgccpf,'cpf');
			echo"<tr id=\"".($i+1)."\" align=\"center\">";
			echo"<td class=\"fonte\">".@$w06_nome."</td>";
			echo"<td class=\"fonte\">".@$w06_ender."</td>";
			echo"<td><input type=\"button\" value=\"Excluir\" class=\"botao\" onClick=\"js_deletalinha('".($i+1)."','valores".($i+1)."')\"><input type=\"button\" value=\"Alterar\" class=\"botao\" onClick=\"js_alterar('$cgccpf','$w06_rg','$w06_nome','$w06_ender','$w06_numero','$w06_compl','$w06_bairro','$w06_cep','$w06_uf','$w06_percent','".($i+1)."','valores".($i+1)."')\"></td>";
			echo"<script>var contador = ".($i+1)."</script>";
			echo"<script>document.form1.tamanho.value= ".($i+1)."</script>";
			echo"</tr>";
			echo"<input name=\"valores".($i+1)."\" type=\"hidden\" value=\"$cgccpf#$w06_rg#$w06_nome#$w06_ender#$w06_numero#$w06_compl#$w06_bairro#$w06_cep#$w06_uf#$w06_percent\">";
			
			}
		      }  
		    }
		    }
    ?>
		</table>
	      </td>
	    </tr>
	  </table>
      </form>
    </td>
  </tr>
</table>
</form>
</body>
</html>