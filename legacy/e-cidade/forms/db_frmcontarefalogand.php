<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: atendimento
include("classes/db_tarefacadsituacao_classe.php");
include("dbforms/db_classesgenericas.php");
$cltarefacadsituacao      = new cl_tarefacadsituacao;
$cltarefa                 = new cl_tarefa;
$cldb_usuarios            = new cl_db_usuarios;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cltarefalog->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("at40_descr");
$clrotulo->label("at40_sequencial");
$clrotulo->label("at48_situacao");
$clrotulo->label("at46_descr");




if(isset($at43_tarefa)&&$at43_tarefa!="") {
	$result = $cltarefa->sql_record($cltarefa->sql_query($at43_tarefa,"at40_descr, at40_progresso, at47_situacao","at40_sequencial","at40_sequencial=$at43_tarefa"));
	if ($cltarefa->numrows > 0) {
	  db_fieldsmemory($result,0);
	}

	$result = $cldb_usuarios->sql_record($cldb_usuarios->sql_query(@$at43_usuario,"nome","id_usuario"));
	if ($cldb_usuarios->numrows > 0) {
	  db_fieldsmemory($result,0);
	}
}

if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar" and (!isset($novo))){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir" and (!isset($novo))){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;

    if(isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
	 $at43_sequencial = "";
     $at43_descr      = "";
     $at43_obs        = "";
	 $at43_diaini_dia = "";     
	 $at43_diaini_mes = "";     
	 $at43_diaini_ano = "";     
	 $at43_diafim_dia = "";     
	 $at43_diafim_mes = "";     
	 $at43_diafim_ano = "";     
     $at43_problema   = "";
     $at43_avisar     = "";
	 $at43_horainidia = "";
	 $at43_horafim    = "";
	 $at43_progresso  = @$at40_progresso;
	 $at48_situacao   = @$at47_situacao;
     $at46_descr      = "";
   } else {
     $at43_progresso  = @$at40_progresso;
     $at48_situacao   = @$at47_situacao;
   }
}
$usu = db_getsession("DB_id_usuario");    
//echo "opcçao = $db_opcao usu = $usu usu = $at43_usuario";
    if(($db_opcao ==2)||($db_opcao==22)||( $db_opcao==3) ) {
    	if ($usu!=$at43_usuario){
    	   	$db_botao=false; 
    	   	$db_opcao =3;
    	}
    }

?>
<form name="form1" method="post" action="">
<center>
 <table border="0" cellspacing="1" cellpadding="0">
  <tr>
  	<td nowrap colspan="2" align="right">
		<input name="bt_voltar" type="button" value="Voltar" title="Voltar" onClick="js_voltar();">
		<input name="bt_imprimit" type="button" value="Imprimir" title="Voltar" onClick="js_imprimir();">
  	</td>
  </tr>	
 </table>
<table border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td nowrap title="<?=@$Tat43_sequencial?>">
       <?=@$Lat43_sequencial?>
    </td>
    <td> 
<?
db_input('at43_sequencial',10,"",true,'text',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_tarefa?>">
       <?=@$Lat43_tarefa?>
    </td>
    <td> 
<?
db_input('at43_tarefa',10,$Iat43_tarefa,true,'text',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_usuario?>">
       <?=@$Lat43_usuario?>
    </td>
    <td> 
<?
if($db_opcao==1){
	$at43_usuario= $usu;
	$sqlid = "select * from db_usuarios where id_usuario=$usu";
	$resultid = pg_query($sqlid);
	db_fieldsmemory($resultid,0);
}
db_input('at43_usuario',10,$Iat43_usuario,true,'text',3,"")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_descr?>">
       <?=@$Lat43_descr?>
    </td>
    <td> 
<?
db_textarea('at43_descr',5,100,$Iat43_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_obs?>">
       <?=@$Lat43_obs?>
    </td>
    <td> 
<?
db_textarea('at43_obs',5,100,"",true,'text',$db_opcao,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_diaini?>">
       <?=@$Lat43_diaini?>
    </td>
    <td> 
<?
if($db_opcao==1||$db_opcao==11) {
	$at43_diaini_dia = date("d", db_getsession("DB_datausu"));
	$at43_diaini_mes = date("m", db_getsession("DB_datausu"));
	$at43_diaini_ano = date("Y", db_getsession("DB_datausu"));

	$at43_diafim_dia = date("d", db_getsession("DB_datausu"));
	$at43_diafim_mes = date("m", db_getsession("DB_datausu"));
	$at43_diafim_ano = date("Y", db_getsession("DB_datausu"));
}
db_inputdata('at43_diaini',@$at43_diaini_dia,@$at43_diaini_mes,@$at43_diaini_ano,true,'text',$db_opcao,"OnChange=js_datafim();", "", "", "parent.js_datafim();");
?>
<?
db_inputdata('at43_diafim',@$at43_diafim_dia,@$at43_diafim_mes,@$at43_diafim_ano,true,'text',3,"OnChange='js_verifica_data(document.form1.at43_diaini_ano.value+\"-\"+
																									              				   document.form1.at43_diaini_mes.value+\"-\"+
																													               document.form1.at43_diaini_dia.value,
                                    																							   document.form1.at43_diafim_ano.value+\"-\"+
																									              				   document.form1.at43_diafim_mes.value+\"-\"+

																													               document.form1.at43_diafim_dia.value);'");

if($db_opcao == 1){

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Usúario Envolvido">
       <strong>Envolvido:</strong>
    </td>
    <td> 
<?
//$x = array("f"=>"NAO","t"=>"SIM");
//db_select('at43_problema',$x,true,$db_opcao,"");

$resusu = $cltarefaenvol->sql_record($cltarefaenvol->sql_query_file(null,'at45_usuario,at45_perc',null," at45_tarefa = $at43_tarefa and at45_perc = 100 "));
if($cltarefaenvol->numrows==0){
  $at45_usuario = 0;
  $at45_perc = 10;
}else{
  global $at45_usuario,$at45_perc;
  db_fieldsmemory($resusu,0);
}

$x = array("0"=>'Nenhum');
$resusu = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(null,'id_usuario,nome','nome'," usuarioativo = '1' and usuext = '0'"));
for($i=0;$i<$cldb_usuarios->numrows; $i++){
  db_fieldsmemory($resusu,$i);
  $x[$id_usuario] = $nome;
}
db_select('at45_usuario',$x,true,$db_opcao,"");

$x = array("10"=>"10%","20"=>"20%","30"=>"30%","40"=>"40%","50"=>"50%","60"=>"60%","70"=>"70%","80"=>"80%","90"=>"90%","100"=>"100%");
db_select('at45_perc',$x,true,$db_opcao,"");

?>
<input name='usuario_unico' type='checkbox'>
<strong>Único Responsável</strong>
<?


?>
    </td>
  </tr>
<tr>
<td>
  <strong>Cliente:</strong>
</td>
<td>
  <?
  $x = array();
  $resusu = $clclientes->sql_record($clclientes->sql_query_file(null,'at01_codcli,at01_nomecli','at01_nomecli'," at01_status = true "));
  for($i=0;$i<$clclientes->numrows; $i++){
    db_fieldsmemory($resusu,$i);
     $x[$at01_codcli] = "&nbsp&nbsp ".$at01_nomecli;
  }
  
  $resusu = $cltarefaclientes->sql_record($cltarefaclientes->sql_query_file(null,'at70_cliente',null," at70_tarefa = $at43_tarefa "));
  global $at70_cliente;
  if($cltarefaclientes->numrows==0){
    $at70_cliente = 16;
  }else{
    for($xx=0;$xx<$cltarefaclientes->numrows;$xx++){
      db_fieldsmemory($resusu,$xx);
      $x[$at70_cliente] = "-".substr($x[$at70_cliente],5);
    }
  }
  
  db_select('at70_cliente',$x,true,$db_opcao,"");

}
  
  ?>
</td>
</tr>


<tr>
    <td nowrap title="<?=@$Tat43_tipomov?>">
           <?=@$Lat43_tipomov?>
               </td>
    <td>
    <script>
function js_habilitar(qual){
  if(qual==3){
    if( document.getElementById('itens_menu').style.visibility == 'hidden' ){
      document.getElementById('itens_menu').style.visibility = 'visible';
    }
  }else{
    document.getElementById('itens_menu').style.visibility = 'hidden' ;
  }
  if(qual==1 || qual==3 ||qual==4 || qual==7 ){
    if( document.getElementById('itens_clientes').style.visibility == 'hidden' ){
      document.getElementById('itens_clientes').style.visibility = 'visible';
    }
  }else{
    document.getElementById('itens_clientes').style.visibility = 'hidden' ;
  }
  

}
function js_itens_menu(){

  document.form1.itens_menu_escolhidos.value = '';

  js_OpenJanelaIframe('','db_iframe_db_itensmenu','con1_caditens002.php?itens_selecionados='+document.form1.itens_menu_escolhidos.value,'Pesquisa',true);

}
function js_itens_clientes(){

  js_OpenJanelaIframe('','db_iframe_db_itensmenu','ate1_tarefalogand003.php?tarefa='+<?=$at43_tarefa?>+'&clientes_selecionados='+document.form1.itens_clientes_escolhidos.value,'Pesquisa',true);

}

function js_pesquisaitemcad(item,modulo){
  document.form1.itens_menu_escolhidos.value += "-"+item;
}
</script>
    
    <?
    $y = array("0"=>"Normal","1"=>"SQL","2"=>"Atualiza menus","3"=>"Usuário","4"=>"PHP-Programa","5"=>"Atualiza Manual","6"=>"Manual Atualizado","7"=>"Cliente Atualizado");
    db_select('at43_tipomov',$y,true,$db_opcao," onchange='js_habilitar(this.value)'");
    ?>
    <input name='itens_menu' type='button' value=' Menus ' id='itens_menu' onclick='js_itens_menu()' style='visibility: <?=(@$at43_tipomov==3?'visible':'hidden')?>'>
    <input name='itens_menu_escolhidos' type='hidden' value='<?=@$itens_menu_escolhidos?>' id='itens_menu_escolhidos' >
 
 
    <input name='itens_clientes' type='button' value=' Clientes ' id='itens_clientes' onclick='js_itens_clientes()' style='visibility: <?=(@$at43_tipomov==1||@$at43_tipomov==3||@$at43_tipomov==4||@$at43_tipomov==7?'visible':'hidden')?>'>
    <input name='itens_clientes_escolhidos' type='hidden' value='<?=@$itens_clientes_escolhidos?>' id='itens_clientes_escolhidos' >
    
    <?
    echo @$Lat43_avisar;
$x = array('0'=>'Ninguém','1'=>'Envolvidos no projeto','2'=>'Envolvidos na tarefa','3'=>'Todos');
if($db_opcao==1||$db_opcao==11) {
	$at43_avisar = 3;
}
db_select('at43_avisar',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Horário inicial e final">
       <strong>Horário:</strong>
    </td>
    <td> 
<?
db_input('at43_horainidia',5,$Iat43_horainidia,true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';");
echo "às";
db_input('at43_horafim',5,$Iat43_horafim,true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_progresso?>">
       <blink> <font color=red> <?=@$Lat43_progresso?></font></blink>
    </td>
    <td> 
<?
  $matriz = array("0"=>"0%",
                  "10"=>"10%", 
                  "20"=>"20%",
                  "30"=>"30%",
                  "40"=>"40%",
                  "50"=>"50%", 
                  "60"=>"60%",
                  "70"=>"70%",
                  "80"=>"80%",
                  "90"=>"90%",
                  "100"=>"100%");             
  db_select("at43_progresso", $matriz,true,$db_opcao); 
?>
<strong>Situação:</strong>
<?
if(isset($at43_progresso)&&$at43_progresso!="") {
	if($at43_progresso==100) {
		$at48_situacao = 3;
	}
}

$usu = db_getsession("DB_id_usuario");
$sqlsutusu = "
			select distinct * from (
				select at46_codigo,
					   at46_descr 
			  	from tarefacadsituacaousu 
			    inner join tarefacadsituacao on at17_tarefacadsituacao = at46_codigo 
			  	where at17_usuario = $usu
			union all
				select at46_codigo,
				   	   at46_descr 
			    from tarefacadsituacao 
			    where at46_codigo = 2 
			union all
			    select at46_codigo,
					   at46_descr 
			    from tarefasituacao
			  	inner join tarefacadsituacao on at47_situacao = at46_codigo 
			    where at47_tarefa = $at43_tarefa
			union all 
				select at46_codigo, 
					   at46_descr 
				from tarefacadsituacao 
				inner join tarefalog on at43_tarefa = $at43_tarefa 
				inner join tarefalogsituacao on at48_tarefalog = at43_sequencial
				and at48_situacao = at46_codigo			
			) as x
			order by at46_codigo
          ";

//die($sqlsutusu);
$resultsutusu = pg_query($sqlsutusu);

if(($db_opcao!=2 )&&($db_opcao!=3 )){
	
	 $sqlsit = "
				select at46_codigo as at48_situacao
						from tarefasituacao
				  	inner join tarefacadsituacao on at47_situacao = at46_codigo 
				    where at47_tarefa = $at43_tarefa
	";
	 //die($sqlsit);
	$ressit = pg_query($sqlsit);
	$linhassit = pg_num_rows($ressit);
	if($linhassit>0){
	  db_fieldsmemory($ressit,0);
	}
}
db_selectrecord('at48_situacao',$resultsutusu,true,$db_opcao,"");

//db_selectrecord('at48_situacao',($cltarefacadsituacao->sql_record($cltarefacadsituacao->sql_query(($db_opcao==1?null:@$at48_situacao),"*","at46_codigo",null))),true,$db_opcao,"");
?>
    </td>
  </tr>
<script>
function js_verifica_data(data1,data2){
	var flag = true;
	if(data1.length > 0 && data2.length > 0){
		if(js_diferenca_datas(data1,data2,3)==true) {
			flag=false;
		}
	}
	
	if(flag==false) {
		alert("Data inicial maior que a final");
	}
	
	return(flag);
}
function js_verifica_hora(valor,campo){
  erro= 0;
  ms  = "";
  hs  = "";
  
  tam = "";
  pos = "";
  tam = valor.length;
  pos = valor.indexOf(":");  
  if(pos!=-1){
    if(pos==0 || pos>2){
      erro++;
    }else{
      if(pos==1){
	hs = "0"+valor.substr(0,1);
	ms = valor.substr(pos+1,2);
      }else if(pos==2){
        hs = valor.substr(0,2);
        ms = valor.substr(pos+1,2);
      }
      if(ms==""){
	ms = "00";
      }
    }
  }else{
    if(tam>=4){
      hs = valor.substr(0,2);
      ms = valor.substr(2,2);
    }else if(tam==3){
      hs = "0"+valor.substr(0,1);
      ms = valor.substr(1,2);
    }else if(tam==2){
      hs = valor;
      ms = "00";
    }else if(tam==1){
      hs = "0"+valor;
      ms = "00";
    }
  }
  if(ms!="" && hs!=""){
    if(hs>24 || hs<0 || ms>60 || ms<0){
      erro++
    }else{
      if(ms==60){
	ms = "59";
      }
      if(hs==24){
	hs = "00";
      }
      hora = hs;
      minu = ms;
    }    
  }

  if (document.form1.at43_horafim.value != "" && erro == 0) {
       var botao   = document.getElementById("db_opcao");
       var val_ini = document.form1.at43_horainidia.value;
       var pos_ini = val_ini.indexOf(":");
       var hs_ini  = "";

       if (pos_ini == 1){
    	    hs_ini = "0" + val_ini.substr(0,1);
       } else if (pos_ini == 2){
            hs_ini = val_ini.substr(0,2);
       }

       if (valor!=""){    
            eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
       }

       var val_fin = document.form1.at43_horafim.value;
       var pos_fin = val_fin.indexOf(":");
       var ms_fin  = "";

       if (pos_fin == 1){
    	    hs_fin = "0" + val_fin.substr(0,1);
       } else if (pos_fin == 2){
            hs_fin = val_fin.substr(0,2);
       }

       if (hs_ini != "" && hs_fin != "") {
            if (hs_ini > hs_fin){
                 alert("Hora inicial maior que hora final");
                 botao.disabled = true;		 
                 erro           = 99;
            } else {
	         botao.disabled = false;
	    }
       }
  }     
  if(erro>0){
    if (erro < 99){ 
         alert("Informe uma hora válida.");
    }
  }
  if(valor!=""){    
    eval("document.form1."+campo+".focus();");
    eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
  }
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_datafim(){
	document.form1.at43_diafim_dia.value = document.form1.at43_diaini_dia.value;
	document.form1.at43_diafim_mes.value = document.form1.at43_diaini_mes.value;
	document.form1.at43_diafim_ano.value = document.form1.at43_diaini_ano.value;
	document.form1.at43_diafim.value = document.form1.at43_diaini_dia.value+'/'+document.form1.at43_diaini_mes.value+'/'+document.form1.at43_diaini_ano.value;

	return true;
}
function js_datafim2(){
<?
	if($db_opcao==1||$db_opcao==11) {
?>
 if(document.form1.at43_diaini_dia.value.length > 0 &&
     document.form1.at43_diaini_mes.value.length > 0 &&
     document.form1.at43_diaini_ano.value.length > 0) {
      data1 = document.form1.at43_diaini_ano.value+"-"+
              document.form1.at43_diaini_mes.value+"-"+
              document.form1.at43_diaini_dia.value;

      data2 = document.form1.at43_diafim_ano.value+"-"+
              document.form1.at43_diafim_mes.value+"-"+
              document.form1.at43_diafim_dia.value;

      if(js_verifica_data(data1,data2)==false) {
      	  return(false);
      }        	
  }
  
  if(document.form1.at43_diaini_dia.value.length > 0) {
	  var diaini_dia = document.form1.at43_diaini_dia.value;

	  if(diaini_dia < 31) {
		  diaini_dia++;  	
    	  document.form1.at43_diafim_dia.value = diaini_dia;
      } 
  }	

  if(document.form1.at43_diaini_mes.value.length > 0) {
      document.form1.at43_diafim_mes.value = document.form1.at43_diaini_mes.value;
  }

  if(document.form1.at43_diaini_ano.value.length > 0) {
      document.form1.at43_diafim_ano.value = document.form1.at43_diaini_ano.value;
  }

  
	
<?
	}
?>
}
function js_voltar() {
  parent.mo_camada('tarefa')
  top.corpo.iframe_tarefa.document.form1.bt_voltar.click();
}
function js_imprimir(){
	window.open('ate2_relatoriotarefas001.php?at40_sequencial='+document.form1.at43_tarefa.value+'&opcao_rel=A&tipo_rel=i&tipodatafinal=P&at40_diaini=--&ordem=1','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
}
</script>
  <tr>
    <td colspan="2" align="center">
    
    
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
 <input name="Agendar" type="button" id="agendar" value="Agendar" onclick="js_abre_agendamento(<?=$at43_tarefa?>)" >
<script>

function js_abre_agendamento(tarefa){
    js_OpenJanelaIframe('','db_iframe_tarefa_agenda','func_calendario_atendimento.php?tarefa='+tarefa,'Pesquisa',true);
}

</script>

 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> > 
    </td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>	
<?php
	if($db_opcao==1||$db_opcao==2) {
		if(!isset($at43_sequencial)) {
			$at43_sequencial = "";
		}
?>
<?php
	}
?>   
 </table>
  </center>
</form>

  <table>
   <tr>
    <td valign="top"  align="center">  
    <?
	 if(isset($db_opcao)&&$db_opcao==2) {
	 	//$chavepri= array("at43_tarefa"=>@$at43_tarefa);
		//$chavepri= array("at43_usuario"=>@$at43_usuario);
	 	$chavepri= array("at43_sequencial"=>@$at43_sequencial,"at43_tarefa"=>@$at43_tarefa);
	 }
	 else if(isset($db_opcao)&&$db_opcao==3) {
	 	//$chavepri= array("at43_tarefa"=>@$at43_tarefa);
	 	$chavepri= array("at43_sequencial"=>@$at43_sequencial,"at43_tarefa"=>@$at43_tarefa);
	 }
	 else if(isset($db_opcao)&&$db_opcao==1) {
	 	$chavepri= array("at43_sequencial"=>@$at43_sequencial,"at43_tarefa"=>@$at43_tarefa);
	 }
	
         echo " <form name='form2' method='post' action='' >";
	
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->formulario = false;
	 $cliframe_alterar_excluir->sql     = $cltarefalog->sql_query_usua(null,"at43_sequencial,at43_descr,at43_obs,case when at43_tipomov = 0 then 'Normal'
	                                                                                                                  when at43_tipomov = 1 then 'SQL'
	                                                                                                                  when at43_tipomov = 2 then 'Menus'
	                                                                                                                  when at43_tipomov = 3 then 'Usuário'
	                                                                                                                  when at43_tipomov = 4 then 'PHP-Programa' 
	                                                                                                                  when at43_tipomov = 5 then 'Atualizar Manual' 
	                                                                                                                  when at43_tipomov = 6 then 'Manual Atualizado' 
                                                                                                                    when at43_tipomov = 7 then 'Cliente Atualizado' end as at43_tipomov, 
	                                                                                                                  at43_tarefa,
	                                                                                                                  at43_diaini,
	                                                                                                                  at43_diafim,
	                                                                                                                  at43_horainidia,
	                                                                                                                  at43_horafim,
	                                                                                                                  at43_usuario,
	 case when at43_problema = 'f' then 'NÃO' else 'SIM' end as at43_problema,case at43_avisar when 0 then 'Ninguém' when 1 then 'Envolvidos no projeto' when 2 then 'Envolvidos na tarefa' when 3 then 'Todos' end as at43_avisar,at43_progresso,nome",
	 "at43_diaini desc ,at43_horafim desc ,at43_sequencial desc","at43_tarefa=$at43_tarefa");
	 
	 $cliframe_alterar_excluir->campos  ="nome,at43_descr,at43_obs,at43_tipomov,at43_diaini,at43_diafim,at43_horainidia,at43_horafim,at43_problema,at43_avisar,at43_progresso";
	 
	 $result_versao = $cldb_versaotarefa->sql_record($cldb_versaotarefa->sql_query(null,"db30_codversao,db30_codrelease",null," db29_tarefa = $at43_tarefa "));
     if($cldb_versaotarefa->numrows>0){
       db_fieldsmemory($result_versao, 0);
       $cliframe_alterar_excluir->legenda="<font color='red'>Movimentos - Versão: 2.$db30_codversao.$db30_codrelease</font>";
     }else{
       $cliframe_alterar_excluir->legenda="Movimentos";
	 }
	 
	 $cliframe_alterar_excluir->iframe_height ="220";
	 $cliframe_alterar_excluir->iframe_width ="900";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);

         echo "   </form>";   
	 
    
    ?>
    </td>
   </tr>
  </table>