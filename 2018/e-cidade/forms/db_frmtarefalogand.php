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
include("classes/db_tarefasituacao_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_tarefacadsituacao_classe.php");
include("dbforms/db_classesgenericas.php");
$cltarefasituacao         = new cl_tarefasituacao;
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
	$result = $cltarefa->sql_record($cltarefa->sql_query($at43_tarefa,"at40_descr","at40_sequencial","at40_sequencial=$at43_tarefa"));
	if ($cltarefa->numrows > 0) {
	  db_fieldsmemory($result,0);
	}

	$result = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($at43_usuario,"nome","id_usuario"));
	if ($cldb_usuarios->numrows > 0) {
	  db_fieldsmemory($result,0);
	}
}
 
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
    
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
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
     $at43_progresso  = "";
     $at46_descr      = "";
   }
}
?>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 if(isset($db_opcao)&&$db_opcao==2) {
	 	$chavepri= array("at43_tarefa"=>@$at43_tarefa);
	 }
	 else if(isset($db_opcao)&&$db_opcao==3) {
	 	$chavepri= array("at43_tarefa"=>@$at43_tarefa);
	 }
     else if(isset($db_opcao)&&$db_opcao==1) {
	 	$chavepri= array("at43_sequencial"=>@$at43_sequencial,"at43_tarefa"=>@$at43_tarefa);
	 }
	
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cltarefalog->sql_query_file(null,"at43_sequencial,at43_descr,at43_obs,at43_diaini,at43_diafim,at43_horainidia,at43_horafim,at43_tarefa,at43_usuario,case when at43_problema = 'f' then 'NÃO' else 'SIM' end as at43_problema,case at43_avisar when 0 then 'Ninguém' when 1 then 'Envolvidos no projeto' when 2 then 'Envolvidos na tarefa' when 3 then 'Todos' end as at43_avisar,at43_progresso","at43_sequencial","at43_tarefa=$at43_tarefa");
	 $cliframe_alterar_excluir->campos  ="at43_tarefa,at43_descr,at43_obs,at43_diaini,at43_diafim,at43_horainidia,at43_horafim,at43_problema,at43_avisar,at43_progresso";
	 $cliframe_alterar_excluir->legenda="USUÁRIOS";
	 $cliframe_alterar_excluir->iframe_height ="220";
	 $cliframe_alterar_excluir->iframe_width ="780";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
<form name="form2" method="post" action="">
<center>
<table border="0">
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
       <?
db_textarea('at40_descr',1,50,"",true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_usuario?>"><b>Usuário:</b></td>
    <td> 
<?
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
db_textarea('at43_descr',5,50,$Iat43_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_obs?>">
       <?=@$Lat43_obs?>
    </td>
    <td> 
<?
db_textarea('at43_obs',5,50,"",true,'text',$db_opcao,'')
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
db_inputdata('at43_diaini',@$at43_diaini_dia,@$at43_diaini_mes,@$at43_diaini_ano,true,'text',$db_opcao,"OnChange=js_datafim();");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_diafim?>">
       <?=@$Lat43_diafim?>
    </td>
    <td> 
<?
//db_inputdata('at43_diafim',@$at43_diafim_dia,@$at43_diafim_mes,@$at43_diafim_ano,true,'text',$db_opcao,"")
db_inputdata('at43_diafim',@$at43_diafim_dia,@$at43_diafim_mes,@$at43_diafim_ano,true,'text',$db_opcao,"OnChange='js_verifica_data(document.form2.at43_diaini_ano.value+'-'+
																									              				   document.form2.at43_diaini_mes.value+'-'+
																													               document.form2.at43_diaini_dia.value,
                                    																							   document.form2.at43_diafim_ano.value+'-'+
																									              				   document.form2.at43_diafim_mes.value+'-'+
																													               document.form2.at43_diafim_dia.value);'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_problema?>">
       <?=@$Lat43_problema?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('at43_problema',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_avisar?>">
       <?=@$Lat43_avisar?>
    </td>
    <td> 
<?
$x = array('0'=>'Ninguém','1'=>'Envolvidos no projeto','2'=>'Envolvidos na tarefa','3'=>'Todos');
if($db_opcao==1||$db_opcao==11) {
	$at43_avisar = 3;
}
db_select('at43_avisar',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_horainidia?>">
       <?=@$Lat43_horainidia?>
    </td>
    <td> 
<?
db_input('at43_horainidia',5,$Iat43_horainidia,true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_horafim?>">
       <?=@$Lat43_horafim?>
    </td>
    <td> 
<?
db_input('at43_horafim',5,$Iat43_horafim,true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat43_progresso?>">
       <?=@$Lat43_progresso?>
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
    </td>
  </tr>
  <tr>
    <td height=50 nowrap title="<?=@$Tat48_situacao?>"><b>Situação:</b></td>
    <td> 
<?
if($db_opcao==1||$db_opcao==11) {
    $result = $cltarefasituacao->sql_record($cltarefasituacao->sql_query(null,"at47_situacao","at47_tarefa","at47_tarefa=$at43_tarefa"));
    if($cltarefasituacao->numrows > 0) {
        db_fieldsmemory($result,0);
    }
	
    $at48_situacao = $at47_situacao;
}
if(isset($at43_progresso)&&$at43_progresso!="") {
	if($at43_progresso==100) {
		$at48_situacao = 3;
	}
}
db_selectrecord('at48_situacao',($cltarefacadsituacao->sql_record($cltarefacadsituacao->sql_query(@$at48_situacao,"*","at46_codigo",null))),true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> > 
    </td>
  </tr>
  </table>
  </center>
</form>
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

  if(erro>0){
    alert("Informe uma hora válida.");
  }
  if(valor!=""){    
    eval("document.form2."+campo+".focus();");
    eval("document.form2."+campo+".value='"+hora+":"+minu+"';");
  }
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form2.appendChild(opcao);
  document.form2.submit();
}
function js_datafim(){
<?
	if($db_opcao==1||$db_opcao==11) {
?>
 if(document.form2.at43_diaini_dia.value.length > 0 &&
     document.form2.at43_diaini_mes.value.length > 0 &&
     document.form2.at43_diaini_ano.value.length > 0) {
      data1 = document.form2.at43_diaini_ano.value+"-"+
              document.form2.at43_diaini_mes.value+"-"+
              document.form2.at43_diaini_dia.value;

      data2 = document.form2.at43_diafim_ano.value+"-"+
              document.form2.at43_diafim_mes.value+"-"+
              document.form2.at43_diafim_dia.value;

      if(js_verifica_data(data1,data2)==false) {
      	  return(false);
      }        	
  }

  if(document.form2.at43_diaini_dia.value.length > 0) {
	  var diaini_dia = document.form2.at43_diaini_dia.value;

	  if(diaini_dia < 31) {
		  diaini_dia++;  	
    	  document.form2.at43_diafim_dia.value = diaini_dia;
      } 
  }	

  if(document.form2.at43_diaini_mes.value.length > 0) {
      document.form2.at43_diafim_mes.value = document.form2.at43_diaini_mes.value;
  }

  if(document.form2.at43_diaini_ano.value.length > 0) {
      document.form2.at43_diafim_ano.value = document.form2.at43_diaini_ano.value;
  }
<?
	}
?>
}
</script>