<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cliframe_alterar_excluir->strFormatar=null;
$clinfla->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("i01_descr");
$where = null;
$i01_codigo  = null;
if(isset($db_opcaoal)){
    $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) || isset($tod) && $sqlerro==false ) ){
     $i02_data = "";
     $i02_valor = "";
   }
$where = " 1 = 1 ";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ti02_codigo?>">
       <?
       db_ancora(@$Li02_codigo,"js_pesquisai02_codigo(true);",$db_opcao);
       ?>
    </td>
    <td>
	  <?
	   db_input('i02_codigo',5,$Ii02_codigo,true,'text',3," onchange='js_pesquisai02_codigo(false);'")
	  ?>
       <?
		db_input('i01_descr',40,$Ii01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti02_data?>">
       <?=@$Li02_data?>
    </td>
    <td colspan=2>
	  <?

     if(!(substr($i02_data,4,1) =='-') && !(substr($i02_data,7,1)=='-')) {

		  	 $data = split("/",$i02_data);
     	   $i02_data_dia = @$data[0];
  		   $i02_data_mes = @$data[1];
	  	   $i02_data_ano = @$data[2];
			   $i02_data     = $i02_data_ano."-".$i02_data_mes."-".$i02_data_dia;
		 }else if(substr($i02_data,4,1)=='-' && substr($i02_data,7,1)=='-') {

		  	 $data = split("-",$i02_data);
				 $i02_data_dia = @$data[1];
  		   $i02_data_mes = @$data[2];
	  	   $i02_data_ano = @$data[0];
			   $i02_data     = $i02_data_ano."-".$i02_data_mes."-".$i02_data_dia;
     }else{

         $i02_data = db_formatar($i02_data,"d");
		 }

	   db_inputdata('i02_data',@$i02_data_dia,@$i02_data_mes,@$i02_data_ano,true,'text',3,"");

	   echo "<b> Ano : </b>";
	   if (isset($i02_codigo)){

           $i01_codigo = trim($i01_codigo);
           $result1    = $clinfla->sql_record($clinfla->sql_query("","","distinct substr(i02_data,1,4) as exerc ","exerc"," i02_codigo = '$i02_codigo'"));
           $xexerc     = array();
           for($i=0;$i < $clinfla->numrows;$i++){
               db_fieldsmemory($result1,$i);
               $xexerc[$exerc] = $exerc;
           }
           if(!isset($exercicio)){
             reset($xexerc);
             $exercicio = key($xexerc) ;
           }
           db_select('exercicio',$xexerc,true,2," onchange='js_location();'");
       }
	  ?>

	</td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti02_valor?>">
       <?=@$Li02_valor?>
    </td>
    <td>
		<?
			db_input('i02_valor',15,$Ii02_valor,true,'text',$db_opcao,"")
		?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
			 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="js_limpavar();" >
			 <input name="todos"  type="button" id="todos"    value="Lançar o valor para todos registros" onclick="js_todosreg();">
			 <input name="novo"   type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top" align="center">
    <?
     $rsTipodm = $clinflan->sql_record($clinflan->sql_query_file($i02_codigo," i01_dm "));
	 if ($clinflan->numrows > 0){
	     db_fieldsmemory($rsTipodm,0);
	 }
	 if (isset($i01_dm) && $i01_dm == 1){
	   //diario
		echo "
			<table border='0' cellpadding='0' cellspacing='0'>
				<tr>
					<td>";
						db_ancora("Janeiro","js_dias(1,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Fevereiro","js_dias(2,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Março","js_dias(3,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Abril","js_dias(4,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Maio","js_dias(5,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Junho","js_dias(6,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Julho","js_dias(7,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Agosto","js_dias(8,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Setembro","js_dias(9,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Outubro","js_dias(10,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Novembro","js_dias(11,document.form1.exercicio.value);","");
						echo "&nbsp";
						db_ancora("Dezembro","js_dias(12,document.form1.exercicio.value);","");
						echo "&nbsp";
		echo "		</td>
				</tr>
			</table>";
	 }
   $where .= " and infla.i02_codigo = '".$i02_codigo."'";
	 if(isset($mes) && $mes != "" ){
	   $where .= " and extract(month from i02_data) = $mes ";
	 }
   if(isset($exercicio) && $exercicio != ""){
     $where .= " and extract(year from i02_data) = $exercicio ";
   }

   $chavepri = array("i02_codigo"=>@$i02_codigo,"i02_data"=>@$i02_data,"i02_valor"=>@$i02_valor);
	 $cliframe_alterar_excluir->chavepri 	    = $chavepri;
	 $cliframe_alterar_excluir->sql      	    = $clinfla->sql_query_file($i02_codigo,"","i02_codigo,to_char(i02_data,'dd/mm/YYYY') as i02_data,i02_valor::float","i02_data",$where);
	 $cliframe_alterar_excluir->campos   	    = "i02_codigo,i02_data,i02_valor";
	 $cliframe_alterar_excluir->legenda 	    = "Valores lançados";
	 $cliframe_alterar_excluir->opcoes        = 2;
	 $cliframe_alterar_excluir->iframe_height = "160";
	 $cliframe_alterar_excluir->iframe_width  = "700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
    </td>
   </tr>
 </table>
  </center>
 <input name="i01_dm" type="hidden" id="i01_dm" value="<?=@$i01_dm?>">
 <input name="mes"    type="hidden" id="mes"    value="<?=@$mes?>">
 <input name="tod"    type="hidden" id=""    value="">
</form>
<script>
function js_limpacampos(){
   document.form1.i02_data_dia.value = '';
   document.form1.i02_data_mes.value = '';
   document.form1.i02_data_ano.value = '';
   document.form1.i02_valor.value = '';

}



function js_limpavar(){
//alert('fasdhfa sldfh las hdfhl');
	document.form1.tod.value = 'f';
}
function js_location(){
    document.location.href='inf4_manvalores001.php?mesini='+document.form1.mes.value+'&i02_codigo='+document.form1.i02_codigo.value+'&exercicio='+document.form1.exercicio.value;
}
function js_todosreg(){
   inflat = document.form1.i02_codigo.value;
// document.form1.mes.value = mes;
   ano    = document.form1.exercicio.value;
   i01_dm = document.form1.i01_dm.value;
   if(document.form1.mes.value != ''){
		  mesini = document.form1.mes.value;
	 }else{
		  mesini = 1;
	 }
   if(document.form1.i02_valor.value != ''){
        valortodos = document.form1.i02_valor.value;
   }else{
	    alert('Preencha o campo valor !');
   		return false;
   }
   if (confirm('Deseja mesmo lancar este valor para todos os registros?')){
        document.location.href = 'inf4_manvalores001.php?mesini='+mesini+'&i02_codigo='+inflat+'&exercicio='+ano+'&i01_dm='+i01_dm+'&tod=t&valortodos='+valortodos;
   }
}

function js_dias(mes,ano){
  //alert(mes);
  inflat = document.form1.i02_codigo.value;
  document.form1.mes.value    = mes;
  document.location.href = 'inf4_manvalores001.php?mes='+mes+'&i02_codigo='+inflat+'&exercicio='+ano;
  //return false;
}


function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisai02_codigo(mostra){
  if(mostra==true){
     js_OpenJanelaIframe('top.corpo','db_iframe_inflan','func_inflan.php?funcao_js=parent.js_mostrainflan1|i01_codigo|i01_descr','Pesquisa',true);
  }else{
     if(document.form1.i02_codigo.value != ''){
        js_OpenJanelaIframe('','db_iframe_inflan','func_inflan.php?pesquisa_chave='+document.form1.i02_codigo.value+'&funcao_js=parent.js_mostrainflan','Pesquisa',false);
     }else{
        document.form1.i01_descr.value = '';
     }
  }
}

function js_mostrainflan(chave,erro){
  document.form1.i01_descr.value = chave;
  if(erro==true){
     document.form1.i02_codigo.focus();
     document.form1.i02_codigo.value = '';
  }else{
     //document.location.href = 'inf4_manvalores001.php';
     document.location.href = 'inf4_manvalores001.php?i02_codigo='+chave1;
  }

}

function js_mostrainflan1(chave1,chave2){
  document.form1.i02_codigo.value = chave1;
  document.form1.i01_descr.value = chave2;
  db_iframe_inflan.hide();
  document.location.href = 'inf4_manvalores001.php?i02_codigo='+chave1;
}


</script>