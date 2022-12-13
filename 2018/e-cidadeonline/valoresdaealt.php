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
mens_help();
$dblink="index.php";
db_logs("","",0,"Digita Codigo do Contribuinte.");
db_mensagem("contribuinte_cab","contribuinte_rod");
postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("opcoesdae.php,sociosdae.php,enderecodae.php,valoresdae.php,enviadae.php");
function js_veri(){
  if(document.form1.valor.value.indexOf(",")!=-1){
    var  vals= new Number(document.form1.valor.value.replace(",","."));
    document.form1.valor.value = vals.toFixed(2);
  }else{
    var vals = new Number(document.form1.valor.value);
    document.form1.valor.value = vals.toFixed(2);
  }
  if(isNaN(vals)){
    alert("verifique o valor da receita!");
    document.form1.valor.focus();
    return false;
  } 
  var aliquota = new Number(document.form1.aliquota.value);
  vals = new Number((vals *(aliquota/100))); 
  document.form1.imposto.value=vals.toFixed(2);
}
function maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_verilinhas(){
  if(document.getElementById('linhas').rows.length == 1){
    alert("Não há valores registrados");
    return false;
  }
}  
function js_data(dia,mes,ano){
  diaval = new Number(dia.value);
  mesval = new Number(mes.value);
  anoval = new Number(ano.value);
  if(isNaN(diaval)){
    alert('dia Inválido');
    dia.value = '';
    dia.focus();
  }    
  if(isNaN(mesval)){
    alert('Data Inválida');
    mes.value = '';
    mes.focus();
  }  
  if(isNaN(anoval)){
    alert('Data Inválida');
    ano.value = '';
    ano.focus();
  }  
  data = new Date(anoval,(mesval-1),diaval);
  if((data.getMonth() + 1) != mesval || data.getFullYear() != anoval){
    alert('Data Inválida');
    dia.focus();
    dia.select();
    return false;
  }
  return true;
}  
var contador = 0;
function js_vericampos(){
if(js_data(document.form1.dia,document.form1.mes1,document.form1.ano)){
  var alerta="";
  mes=document.form1.mes.value;
  valor=document.form1.valor.value;
  aliquota=document.form1.aliquota.value;
  imposto=document.form1.imposto.value;
  datad=document.form1.dia.value;
  datam=document.form1.mes1.value;
  dataa=document.form1.ano.value;
  if(mes=="mes"){
    alerta +="Mês\n";
  }
  if(valor==""){
    alerta +="Valor da Receita\n";
  }
  if(aliquota==""){
    alerta +="Alíquota\n";
  }
  if(imposto==""){
    alerta +="Imposto\n";
  }
  if(datad=="" || datam=="" || dataa==""){
    alerta +="Data de Pagamento\n";
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
    novacoluna.innerHTML = db_mes(mes);
    novacoluna = novalinha.insertCell(1);
    novacoluna.style.fontSize = '12px';
    novacoluna.align = 'center';
    novacoluna.innerHTML = valor;
    novacoluna = novalinha.insertCell(2);
    novacoluna.style.fontSize = '12px';
    novacoluna.align = 'center';
    novacoluna.innerHTML = aliquota + '%';
    novacoluna = novalinha.insertCell(3);
    novacoluna.style.fontSize = '12px';
    novacoluna.align = 'center';
    novacoluna.innerHTML = imposto;
    novacoluna = novalinha.insertCell(4);
    novacoluna.style.fontSize = '12px';
    novacoluna.align = 'center';
    data = dataa+'-'+datam+'-'+datad;
    novacoluna.innerHTML = datad+'/'+datam+'/'+dataa;
    novacoluna = novalinha.insertCell(5);
    novacoluna.style.fontSize = '12px';
    novacoluna.align = 'center';
    valores = document.createElement("input");
    valores.setAttribute('type','hidden');
    valores.setAttribute('name','valores'+contador);
    valores.setAttribute('id','valores'+contador);
    valores.setAttribute('value',mes+'#'+valor+'#'+aliquota+'#'+imposto+'#'+data);
    document.form1.appendChild(valores);
    document.form1.tamanho.value = contador;
    novacoluna.innerHTML = '<input type="button" value="Excluir" class="botao" onClick="js_deletalinha('+ contador + ',' + valores.id +')"><input type="button" value="Alterar" class="botao" onClick="js_alterar('+mes+','+valor+','+aliquota+','+imposto+','+datad+','+datam+','+dataa+','+ contador+','+valores.name+')">';
    document.form1.mes.value = 'mes'; 
    document.form1.valor.value = ''; 
    document.form1.imposto.value = ''; 
    document.form1.dia.value = ''; 
    document.form1.mes1.value = ''; 
    document.form1.ano.value = ''; 
  }
}
}
function js_alterar(m,v,a,i,d,m1,a,obj,obj1){
    document.form1.mes.value = m; 
    document.form1.valor.value = v; 
    document.form1.aliquota.value = a+'%'; 
    document.form1.imposto.value = i; 
    document.form1.dia.value = d; 
    document.form1.mes1.value = m1; 
    document.form1.ano.value = a; 
    js_deletalinha(obj,obj1);
}
function js_deletalinha(obj,obj1){
  for(i=1;i<document.getElementById('linhas').rows.length;i++){
    if(document.getElementById('linhas').rows.length==1){
      obj = 1;
    }  
    if(document.getElementById('linhas').rows[i].id == obj){
      alert(document.getElemntById(obj1).name);
      document.getElementById(obj1).value = '';
      document.getElementById('linhas').deleteRow(i); 
    }
  }
}
</script>
<style type="text/css">
<?
db_estilosite();
?>
.fonte{
      font-family: Arial;
      font-size:12px;
      }
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<?
mens_div();
?>
<center>
<form name="form1" method="post" action="opcoesdae.php?inscricaow=<?=$inscricaow?>" onSubmit="return js_verilinhas()">
<input type="hidden" name="tamanho">
<table width="766" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
<tr>
    <td align="left" valign="top">
      <table width="100%" height="313" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left" valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr align="left">
                  <td>
                    <table width="89%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td colspan="5">
			    <table width="490" border="0">
                              <tr> 
                                <td align="center">
				  <b><small></small></b>
				</td>
                                <td align="center" >
				  <b><small>Mês</small></b>
				</td>
                                <td align="center" nowrap >
				  <b><small>Valor Rec.</small></b>
				</td>
                                <td align="center" >
				  <b><small>Aliquota</small></b>
				</td>
                                <td align="center" >
				  <b><small>Imposto</small></b>
				</td>
                                <td nowrap align="center" >
				  <b><small>Dt. pgto</small></b>
				</td>
                              </tr>
                              <tr> 
                                <td>
				  <input name="item" type="hidden" maxlength="3" size="2">
			        </td>
                                <td align="center" nowrap>
                                  <select class="digitacgccpf" name="mes" id="mes">
	                            <option value="mes">Mês</option>
                                  </select>
                                </td>
	                        <script>
                                for(j=1;j<13;j++){
	     	                  document.form1.mes.options[j] = new Option(db_mes(j),j);
			        }	 
		                </script>
                                <td align="center" nowrap >
				  <small>R$</small>
				  <input name="valor" type="text" onChange="return js_veri();"  size="10"> 
                                </td>
                                <td align="center" nowrap >
				  <select name="aliquota" id="select" onChange="return js_veri()">
                                    <option value="0">0%</option>
                                    <option value="1" <?=(isset($aliquota)&&$aliquota=="1"?"selected":"")?>>1%</option>
                                    <option value="2" <?=(isset($aliquota)&&$aliquota=="2"?"selected":"")?> selected>2%</option>
                                    <option value="3" <?=(isset($aliquota)&&$aliquota=="3"?"selected":"")?>>3%</option>
                                    <option value="4" <?=(isset($aliquota)&&$aliquota=="4"?"selected":"")?>>4%</option>
                                    <option  value="5" <?=(isset($aliquota)&&$aliquota=="5"?"selected":"")?>>5%</option>
                                    <option value="6" <?=(isset($aliquota)&&$aliquota=="6"?"selected":"")?>>6%</option>
                                    <option value="7" <?=(isset($aliquota)&&$aliquota=="7"?"selected":"")?>>7%</option>
                                    <option value="8" <?=(isset($aliquota)&&$aliquota=="8"?"selected":"")?>>8%</option>
                                    <option value="9" <?=(isset($aliquota)&&$aliquota=="9"?"selected":"")?>>9%</option>
                                    <option value="10" <?=(isset($aliquota)&&$aliquota=="10"?"selected":"")?>>10%</option>
                                  </select>
				</td>
				<td nowrap>
                                  <small>R$ 
                                    <input name="imposto" type="text" size="10" readonly>
                                  </small>
				</td>
				<td nowrap>
                                  <input name="dia" type="text" size="2" maxlength="2"> /
                                  <input name="mes1" type="text" size="2" maxlength="2"> /
                                  <input name="ano" type="text" size="4" maxlength="4">
				</td>
                              </tr>
                            </table>
			  </td>
                        </tr>
                        <tr> 
                          <td colspan="4" > 
 			    <input name="guarda" class="botao" type="button"  value="Incluir Ítem" onclick="return js_vericampos();"> 
                          </td>
                          <td > 
 			    <input name="salvavalores" class="botao" type="submit"  value="Salvar" > 
                          </td>
                        </tr>
	        </table>
	      </td>
	    </tr>  
          </td>
        </tr>
	<tr>
	  <td>
            <table id="linhas" width="490" cellpadding="0" cellspacing="0" border="1" >
	      <tr bgcolor="<?=$w01_corfundomenuativo?>" align="center">
	        <td width="20%" id="colocar">
		  Mês
		</td>
	        <td width="20%">
		  Valor Rec.
		</td>
	        <td width="10%">
		  Alíquota
		</td>
	        <td width="20%">
		  Imposto
		</td>
	        <td width="30%">
		  Data pgto.
		</td>
	      </tr>
              <?
                if(!isset($primeira)){
                  exit;
                }  
                if(isset($inscricaow)){
                $result = db_query("select * from db_dae where w04_inscr = $inscricaow");
                if(pg_numrows($result) == 0){
                  db_redireciona("valoresdae.php?inscricaow=$inscricaow");
                  exit;
                }else{
                  db_fieldsmemory($result,0);
                  $result = db_query("select * from db_daevalores where w07_codigo = $w04_codigo");
                  if(pg_numrows($result) == 0){
                    db_redireciona("valoresdae.php?inscricaow=$inscricaow");
                    exit;
                  }else{
                    for($i = 0;$i < pg_numrows($result);$i++){
                    $data = pg_result($result,$i,'w07_dtpaga');
                    $valor = pg_result($result,$i,'w07_valor');
		    db_fieldsmemory($result,$i,true);
                    echo"<tr id=\"".($i+1)."\" align=\"center\">";
                    echo"<td class=\"fonte\">".db_mes(@$w07_mes)."</td>";
                    echo"<td class=\"fonte\">".@$valor."</td>";
                    echo"<td class=\"fonte\">".@$w07_aliquota." %</td>";
                    echo"<td class=\"fonte\">".@$w07_imposto."</td>";
                    echo"<td class=\"fonte\">".@$w07_dtpaga."</td>";
                    $ano = substr($w07_dtpaga,6,4);
                    $mes = substr($w07_dtpaga,3,2);
                    $dia = substr($w07_dtpaga,0,2);
		    echo"<td><input type=\"button\" value=\"Excluir\" class=\"botao\" onClick=\"js_deletalinha('".($i+1)."','valores".($i+1)."')\"><input type=\"button\" value=\"Alterar\" class=\"botao\" onClick=\"js_alterar('$w07_mes','$valor','$w07_aliquota','$w07_imposto','$dia','$mes','$ano','".($i+1)."','valores".($i+1)."')\"></td>";
                    echo"<script>var contador = ".($i+1)."</script>";
                    echo"<script>document.form1.tamanho.value= ".($i+1)."</script>";
                    echo"</tr>";
                    echo"<input name=\"valores".($i+1)."\" type=\"hidden\" value=\"$w07_mes#$valor#$w07_aliquota#$w07_imposto#$data\">";
                    
		    }
                  }  
                }
                }
?>
	    </table>
	  </td>
	</tr>
      </table>
    </td>
  </tr>
</table>
</form>
</center>
</form>
</body>
</html>