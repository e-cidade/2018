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
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clclientesmodulos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at01_nomecli");
$clrotulo->label("nome_modulo");
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
     $at74_sequencial = "";
     $at74_id_item = "";
     $at74_data = "";
     $at74_obs = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat74_sequencial?>">
       <?//@$Lat74_sequencial?>
    </td>
    <td> 
<?
db_input('at74_sequencial',6,$Iat74_sequencial,true,'hidden',2,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat74_codcli?>">
       <?
       db_ancora(@$Lat74_codcli,"js_pesquisaat74_codcli(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('at74_codcli',4,$Iat74_codcli,true,'text',2," readonly ")
?>
       <?
db_input('at01_nomecli',40,$Iat01_nomecli,true,'hidden',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat74_id_item?>">
       <?
       db_ancora(@$Lat74_id_item,"js_pesquisaat74_id_item(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at74_id_item',7,$Iat74_id_item,true,'text',$db_opcao," onchange='js_pesquisaat74_id_item(false);'")
?>
       <?
db_input('nome_modulo',20,$Inome_modulo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat74_data?>">
       <?=@$Lat74_data?>
    </td>
    <td> 
<?
db_inputdata('at74_data',@$at74_data_dia,@$at74_data_mes,@$at74_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat74_obs?>">
       <?=@$Lat74_obs?>
    </td>
    <td> 
<?
db_textarea('at74_obs',4,70,$Iat74_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
 <input name="procedimentos" type="button" id="procedimentos" value="Selecione os Procedimentos" onclick="js_procedimentos();" >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("at74_sequencial"=>@$at74_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clclientesmodulos->sql_query(null,"*","nome_modulo"," at74_codcli = $at74_codcli ");
	 $cliframe_alterar_excluir->campos  ="at74_sequencial,at74_id_item,nome_modulo,at74_data,at74_obs";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
function js_procedimentos(){
  if(document.form1.at74_id_item.value!=""){
    js_OpenJanelaIframe('top.corpo.iframe_clientesmodulos','db_iframe_clientes_proced','ate1_clientesmodulosproc002.php?sequencial=<?=@$at74_sequencial?>&id_modulo=<?=@$at74_id_item?>&cliente=<?=@$at74_codcli?>','Pesquisa',true);
  }else{
    alert("Selecione um Módulo.");
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
function js_pesquisaat74_codcli(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_clientesmodulos','db_iframe_clientes','func_clientes.php?funcao_js=parent.js_mostraclientes1|at01_codcli|at01_nomecli','Pesquisa',true);
  }else{
     if(document.form1.at74_codcli.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_clientesmodulos','db_iframe_clientes','func_clientes.php?pesquisa_chave='+document.form1.at74_codcli.value+'&funcao_js=parent.js_mostraclientes','Pesquisa',false);
     }else{
       document.form1.at01_nomecli.value = ''; 
     }
  }
}
function js_mostraclientes(chave,erro){
  document.form1.at01_nomecli.value = chave; 
  if(erro==true){ 
    document.form1.at74_codcli.focus(); 
    document.form1.at74_codcli.value = ''; 
  }
}
function js_mostraclientes1(chave1,chave2){
  document.form1.at74_codcli.value = chave1;
  document.form1.at01_nomecli.value = chave2;
  db_iframe_clientes.hide();
}
function js_pesquisaat74_id_item(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_clientesmodulos','db_iframe_db_modulos','func_db_modulos.php?funcao_js=parent.js_mostradb_modulos1|id_item|nome_modulo','Pesquisa',true);
  }else{
     if(document.form1.at74_id_item.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_clientesmodulos','db_iframe_db_modulos','func_db_modulos.php?pesquisa_chave='+document.form1.at74_id_item.value+'&funcao_js=parent.js_mostradb_modulos','Pesquisa',false);
     }else{
       document.form1.nome_modulo.value = ''; 
     }
  }
}
function js_mostradb_modulos(chave,erro){
  document.form1.nome_modulo.value = chave; 
  if(erro==true){ 
    document.form1.at74_id_item.focus(); 
    document.form1.at74_id_item.value = ''; 
  }
}
function js_mostradb_modulos1(chave1,chave2){
  document.form1.at74_id_item.value = chave1;
  document.form1.nome_modulo.value = chave2;
  db_iframe_db_modulos.hide();
}
</script>