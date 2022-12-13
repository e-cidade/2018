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
include("classes/db_db_usuarios_classe.php");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_usuarios            = new cl_db_usuarios;
$cltarefa_agenda->rotulo->label();
$clrotulo = new rotulocampo;
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
/*    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $at13_sequencial = "";
     $at13_tarefa = "";
     $at13_dia = "";
     $at13_dia_dia = "";
     $at13_dia_mes = "";
     $at13_dia_ano = "";
     $at13_horaini = "";
     $at13_horafim = "";
   }
   */
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  
  <tr>
  	<td nowrap colspan="2" align="right">
		<input name="bt_voltar" type="button" value="Voltar" title="Voltar" onClick="js_voltar();">
  	</td>
  </tr>	
  
  <tr>
    <td nowrap title="<?=@$Tat13_tarefa?>">
       <?=@$Lat13_tarefa?>
    </td>
    <td> 
<?
db_input('at13_tarefa',10,$Iat13_tarefa,true,'text',3,"");
db_input('at13_sequencial',10,$Iat13_sequencial,true,'hidden',3,"");
?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tat13_dia?>">
       <?=@$Lat13_dia?>
    </td>
    <td> 
<?
db_inputdata('at13_dia', @$at13_dia_dia, @$at13_dia_mes, @$at13_dia_ano, true, 'text', $db_opcao, "")
?>
    </td>
  </tr>
 
      <tr>
    <td nowrap title="<?=@$Tat13_horaini?>">
       <?=@$Lat13_horaini?>
    </td>
    <td> 
<?
db_input('at13_horaini',5,   @$at13_horaini, true, 'text', $db_opcao,"onchange='js_verifica_hora(this.value,this.name)';")
?>
    </td>
  </tr>      
  
    <tr>
    <td nowrap title="<?=@$Tat13_horafim?>">
       <?=@$Lat13_horafim?>
    </td>
    <td> 
<?
db_input('at13_horafim',5, @$at13_horafim, true, 'text', $db_opcao,"onchange='js_verifica_hora(this.value,this.name)';")
?>
    </td>
  </tr>      
  
  <tr>
    <tr>
  	<td nowrap colspan="2" align="right">
  	<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> 
		<input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?>>
  	 </td>
  </tr>	
  
  
   <td colspan="2" align="center">
   </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    
    <?
	 $chavepri= array("at13_sequencial"=>@$at13_sequencial,"at13_tarefa"=>@$at13_tarefa);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cltarefa_agenda->sql_query(null,"*"," at13_dia, at13_horaini, at13_horafim","at13_tarefa= {$at13_tarefa}");
   $cliframe_alterar_excluir->campos  ="at13_dia, at13_horaini, at13_horafim";
	 $cliframe_alterar_excluir->legenda="Agendamento";
	 $cliframe_alterar_excluir->iframe_height ="60";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  
  <?
  $sql = "select distinct at77_id_usuario, at77_dataagenda,at77_hora, at77_observacao, at77_datavalidade , nome
          from tarefaagenda 
               inner join db_usuarios on at77_id_usuario = id_usuario
          where at77_tarefa = $at13_tarefa
          order by at77_dataagenda";
  $result = pg_exec($sql);
  if ( pg_numrows($result) > 0 ){
    ?>
    <table border="1">
    <tr>
    <td colspan="6" align="center"><strong> Agenda Pessoal </strong></td>
    </tr>
    <tr>
    <td ><strong> Usuario </strong></td>
    <td ><strong> Nome </strong></td>
    <td ><strong> Data </strong></td>
    <td ><strong> Hora </strong></td>
    <td ><strong> Observacao </strong></td>
    <td ><strong> Validade  </strong></td>
    </tr>
    <?
    for($i=0;$i< pg_numrows($result);$i++){
      echo "<tr><td>".pg_result($result,$i,'at77_id_usuario')."</td>";
      echo "<td>".pg_result($result,$i,'nome')."</td>";
      echo "<td>".pg_result($result,$i,'at77_dataagenda')."</td>";
      echo "<td>".pg_result($result,$i,'at77_hora')."</td>";
      echo "<td>".pg_result($result,$i,'at77_observacao')." &nbsp</td>";
      echo "<td>".pg_result($result,$i,'at77_datavalidade')." &nbsp</td><tr>";
    }
    ?>
    </table>

    <?
  }
  ?>

  </center>
</form>
<script>
  
function js_verifica_hora(valor,campo){
  erro= 0;
  ms  = "";
  hs  = "";
  
  tam = "";
  pos = "";
  tam = valor.length;
  pos = valor.indexOf(":");  
  if(pos!=-1){
    if(pos==0 || pos>3){
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

  if (document.form1.at13_horafim.value != "" && erro == 0) {
       var botao   = document.getElementById("db_opcao");
       var val_ini = document.form1.at13_horaini.value;
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

       var val_fin = document.form1.at13_horafim.value;
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
function js_voltar() {
  parent.mo_camada('tarefa')
  top.corpo.iframe_tarefa.document.form1.bt_voltar.click();	
}
</script>