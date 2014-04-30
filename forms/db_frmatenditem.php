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
include("classes/db_atenditemtipo_classe.php");
$clatenditem->rotulo->label();
$clrotulo = new rotulocampo;
$clatenditemtipo = new cl_atenditemtipo;
$clrotulo->label("at02_codcli");
$clrotulo->label("at02_codcli");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_atenditem.location.href='ate1_atenditem002.php?chavepesquisa=$at05_seq&chavepesquisa1=$at05_codatend'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_atenditem.location.href='ate1_atenditem003.php?chavepesquisa=$at05_seq&chavepesquisa1=$at05_codatend'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
db_input('at05_seq',4,$Iat05_seq,true,'hidden',$db_opcao,"")
?>
  <tr>
    <td nowrap title="<?=@$Tat05_codatend?>">
       <?=$Lat05_tipo?>
    </td>
    <td> 
    <table border="0">
    <tr>
    <td>
<?
db_input('at05_codatend',6,$Iat05_codatend,true,'hidden',3," onchange='js_pesquisaat05_codatend(false);'")
?>
       <?
db_input('at02_codcli',6,$Iat02_codcli,true,'hidden',3,'')
       ?>
<?
db_selectrecord('at05_tipo',($clatenditemtipo->sql_record($clatenditemtipo->sql_query("","*"))),1,$db_opcao, " ","","","","js_datavenc(this.value)");
?>
    </td>
    <td>
    
<div style="position:relative;visibility:visible;width:300px;height:20px" id="data">
<?
echo $Lat05_data;
if(empty($at05_data_dia) && $db_opcao == 1){
  $at05_data_dia = date("d",db_getsession("DB_datausu"));
  $at05_data_mes = date("m",db_getsession("DB_datausu"));
  $at05_data_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('at05_data',@$at05_data_dia,@$at05_data_mes,@$at05_data_ano,true,'text',$db_opcao,"");
?>
</div>
<script>
function js_datavenc(val){
  if(val == '1'){
    document.getElementById('data').style.visibility = 'visible';
    document.form1.at05_data_dia.disabled = false;
    document.form1.at05_data_mes.disabled = false;
    document.form1.at05_data_ano.disabled = false;
  }else{
    document.form1.at05_data_dia.disabled = true;
    document.form1.at05_data_mes.disabled = true;
    document.form1.at05_data_ano.disabled = true;
    document.getElementById('data').style.visibility = 'hidden';
  }
}
</script>
<?
if($db_opcao == 2){
  echo "<script>js_datavenc(document.form1.at05_tipo.value)</script>";  
}
?>
    </tr>
    </td>
    </table>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat05_solicitado?>">
       <?=@$Lat05_solicitado?>
    </td>
    <td> 
<?
db_textarea('at05_solicitado',3,50,$Iat05_solicitado,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat05_feito?>">
       <?=@$Lat05_feito?>
    </td>
    <td> 
<?
db_textarea('at05_feito',3,50,$Iat05_feito,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat05_perc?>">
       <?=@$Lat05_perc?>
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
  db_select("at05_perc", $matriz,true,$db_opcao); 
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td colspan="2" align="top">
   <?
    $chavepri= array("at05_seq"=>@$at05_seq,"at05_codatend"=>@$at05_codatend);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="at05_seq,at05_codatend,at05_solicitado,at05_feito";
    $cliframe_alterar_excluir->sql=$clatenditem->sql_query(null,"*","","at05_codatend = $at05_codatend");
    $cliframe_alterar_excluir->legenda="Ítens do atendimento";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum Registro Encontrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
      
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisaat05_codatend(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_atendimento','func_atendimento.php?funcao_js=parent.js_mostraatendimento1|at02_codatend|at02_codcli','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_atendimento','func_atendimento.php?pesquisa_chave='+document.form1.at05_codatend.value+'&funcao_js=parent.js_mostraatendimento','Pesquisa',false);
  }
}
function js_mostraatendimento(chave,erro){
  document.form1.at02_codcli.value = chave; 
  if(erro==true){ 
    document.form1.at05_codatend.focus(); 
    document.form1.at05_codatend.value = ''; 
  }
}
function js_mostraatendimento1(chave1,chave2){
  document.form1.at05_codatend.value = chave1;
  document.form1.at02_codcli.value = chave2;
  db_iframe_atendimento.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_atenditem','func_atenditem.php?funcao_js=parent.js_preenchepesquisa|at05_codatend|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_atenditem.hide();
}
</script>
<?
if(isset($at05_codatend) && $at05_codatend != ""){
  echo "<script>js_OpenJanelaIframe('','db_iframe_atendimento','func_atendimento.php?pesquisa_chave=$at05_codatend&funcao_js=parent.js_mostraatendimento','Pesquisa',false);</script>";
}
?>