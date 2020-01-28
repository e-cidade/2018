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

//MODULO: educação
include("dbforms/db_classesgenericas.php");
include("classes/db_rechumanoativ_classe.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrechumanoativ = new cl_rechumanoativ;
$clrelacaotrabalho->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed75_i_codigo");
$clrotulo->label("ed25_i_codigo");
$clrotulo->label("ed24_i_codigo");
$clrotulo->label("ed12_i_codigo");
$clrotulo->label("ed29_i_ensino");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $result2 = $clrelacaotrabalho->sql_record($clrelacaotrabalho->sql_query("","relacaotrabalho.*,ed12_i_ensino,ed10_c_descr,ed24_c_descr,ed25_c_descr,ed232_c_descr",""," ed23_i_codigo = $ed23_i_codigo"));
 db_fieldsmemory($result2,0);
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 if(!isset($excluir)){
  $result3 = $clrelacaotrabalho->sql_record($clrelacaotrabalho->sql_query("","relacaotrabalho.*,ed12_i_ensino,ed10_c_descr,ed24_c_descr,ed25_c_descr,ed232_c_descr",""," ed23_i_codigo = $ed23_i_codigo"));
  db_fieldsmemory($result3,0);
 }
 $db_botao1 = true;
 $db_opcao = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap>
  </td>
  <td>
   <?db_input('ed23_i_codigo',15,@$Ied23_i_codigo,true,'hidden',3,"")?>
   <?db_input('ed23_i_rechumanoescola',15,@$Ied23_i_rechumanoescola,true,'hidden',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$ed20_i_tiposervidor=='1'?'Matrícula':'CGM'?>">
   <b><?=@$ed20_i_tiposervidor=='1'?'Matrícula:':'CGM:'?></b>
  </td>
  <td>
   <?db_input('identificacao',15,@$identificacao,true,'text',3,"")?>
   <?db_input('z01_nome',50,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted23_i_regimetrabalho?>">
   <?db_ancora(@$Led23_i_regimetrabalho,"js_pesquisaed23_i_regimetrabalho(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed23_i_regimetrabalho',15,$Ied23_i_regimetrabalho,true,'text',$db_opcao," onchange='js_pesquisaed23_i_regimetrabalho(false);'")?>
   <?db_input('ed24_c_descr',40,@$Ied24_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <?
 $db_opcao_atual = $db_opcao;
 $result = $clrechumanoativ->sql_record($clrechumanoativ->sql_query("","ed01_c_regencia",""," ed22_i_rechumanoescola = $ed23_i_rechumanoescola AND ed75_i_escola = ".db_getsession("DB_coddepto")." AND ed01_c_regencia = 'S'"));
 if($clrechumanoativ->numrows==0){
  $db_opcao = 3;
  $cor = "#DEB887";
  $regente = "N";
 }else{
  $db_opcao = isset($opcao)&&$opcao=="excluir"?3:1;
  $cor = "#E6E4F1";
  $regente = "S";
 }
 ?>
 <tr>
  <td nowrap colspan="2">
  <br>
   Somente para regentes de classe:
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted29_i_ensino?>">
   <?db_ancora(@$Led29_i_ensino,"js_pesquisaed23_i_ensino(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed12_i_ensino',15,@$Ied12_i_ensino,true,'text',$db_opcao," onchange='js_pesquisaed23_i_ensino(false);' ")?>
   <?db_input('ed10_c_descr',40,@$Ied10_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted23_i_areatrabalho?>">
   <?db_ancora(@$Led23_i_areatrabalho,"js_pesquisaed23_i_areatrabalho(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed23_i_areatrabalho',15,$Ied23_i_areatrabalho,true,'text',$db_opcao," onchange='js_pesquisaed23_i_areatrabalho(false);'")?>
   <?db_input('ed25_c_descr',40,@$Ied25_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <?if(isset($opcao) || $regente=="N"){?>
  <tr>
   <td nowrap title="<?=@$Ted23_i_disciplina?>">
    <?db_ancora(@$Led23_i_disciplina,"js_pesquisaed23_i_disciplina(true);",$db_opcao);?>
   </td>
   <td>
    <?db_input('ed23_i_disciplina',15,$Ied23_i_disciplina,true,'text',$db_opcao," onchange='js_pesquisaed23_i_disciplina(false);'")?>
    <?db_input('ed232_c_descr',40,@$Ied232_c_descr,true,'text',3,'')?>
   </td>
  </tr>
 <?}else{?>
  <tbody id="div_disciplina"></tbody>
 <?}?> 
</table>
<?$db_opcao = $db_opcao_atual;?>
<input name="ed23_i_rechumanoescola" type="hidden" value="<?=@$ed23_i_rechumanoescola?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=$db_opcao!=3?"onclick=\"return js_valida('$regente');\"":""?>>
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<input name="regente" type="hidden" value="<?=$regente?>">
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $escola = db_getsession("DB_coddepto");
   $chavepri= array("ed23_i_codigo"=>@$ed23_i_codigo);
   $campossql = "ed23_i_codigo,
                 ed23_i_rechumanoescola,
                 ed23_i_areatrabalho,
                 ed25_c_descr as db_area,
                 ed23_i_regimetrabalho,
                 ed24_c_descr as db_regime,
                 ed23_i_disciplina,
                 ed232_c_descr as db_disciplina,
                 ed12_i_ensino,
                 ed10_c_descr as db_ensino";
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql = $clrelacaotrabalho->sql_query("",$campossql,""," ed23_i_rechumanoescola = $ed23_i_rechumanoescola");
   $cliframe_alterar_excluir->campos  ="ed23_i_codigo,db_regime,db_ensino,db_area,db_disciplina";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="140";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_valida(regente){
 if(document.form1.ed23_i_regimetrabalho.value==""){
  alert("Campo Regime de Trabalho não informado!");
  return false;
 }
 if(regente=="S"){
  if(document.form1.ed12_i_ensino.value==""){
   alert("Campo Nível de Ensino não informado!");
   return false;
  }
  if(document.form1.ed23_i_areatrabalho.value==""){
   alert("Campo Área de Trabalho não informado!");
   return false;
  }
  if(document.form1.ed23_i_codigo.value!=""){
   if(document.form1.ed23_i_disciplina.value==""){
    alert("Campo Disciplina não informado!");
    return false;
   }
  }else{
   tam = document.form1.coddisciplina.length;
   if(tam==undefined){
    if(document.form1.coddisciplina.checked==false){
     alert("Campo Disciplina(s) não informado!");
     return false;
    }
   }else{
    checado = 0;
    for(x=0;x<tam;x++){
     if(document.form1.coddisciplina[x].checked==true){
      checado++;
     }
    }
    if(checado==0){
     alert("Campo Disciplina(s) não informado!");
     return false;
    }
   }
  }
 }
 return true;
}
function js_pesquisaed23_i_areatrabalho(mostra){
 if(document.form1.ed12_i_ensino.value==""){
  alert("Informe primeiro o Nível de Ensino!");
  document.form1.ed23_i_areatrabalho.value = "";
  document.form1.ed12_i_ensino.style.background = "#99A9AE";
  document.form1.ed12_i_ensino.focus();
 }else{
  if(mostra==true){
   js_OpenJanelaIframe('','db_iframe_areatrabalho','func_areatrabalho.php?ensino='+document.form1.ed12_i_ensino.value+'&funcao_js=parent.js_mostraareatrabalho1|ed25_i_codigo|ed25_c_descr','Pesquisa de Áreas de Trabalho',true);
  }else{
   if(document.form1.ed23_i_areatrabalho.value != ''){
    js_OpenJanelaIframe('','db_iframe_areatrabalho','func_areatrabalho.php?ensino='+document.form1.ed12_i_ensino.value+'&pesquisa_chave='+document.form1.ed23_i_areatrabalho.value+'&funcao_js=parent.js_mostraareatrabalho','Pesquisa',false);
   }else{
    document.form1.ed25_c_descr.value = '';
   }
  }
 }
}
function js_mostraareatrabalho(chave,erro){
 document.form1.ed25_c_descr.value = chave;
 if(erro==true){
  document.form1.ed23_i_areatrabalho.focus();
  document.form1.ed23_i_areatrabalho.value = '';
 }
}
function js_mostraareatrabalho1(chave1,chave2){
 document.form1.ed23_i_areatrabalho.value = chave1;
 document.form1.ed25_c_descr.value = chave2;
 db_iframe_areatrabalho.hide();
}
function js_pesquisaed23_i_regimetrabalho(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_regimetrabalho','func_regimetrabalho.php?funcao_js=parent.js_mostraregimetrabalho1|ed24_i_codigo|ed24_c_descr','Pesquisa de Regimes de Trabalho',true);
 }else{
  if(document.form1.ed23_i_regimetrabalho.value != ''){
   js_OpenJanelaIframe('','db_iframe_regimetrabalho','func_regimetrabalho.php?pesquisa_chave='+document.form1.ed23_i_regimetrabalho.value+'&funcao_js=parent.js_mostraregimetrabalho','Pesquisa',false);
  }else{
   document.form1.ed24_c_descr.value = '';
  }
 }
}
function js_mostraregimetrabalho(chave,erro){
 document.form1.ed24_c_descr.value = chave;
 if(erro==true){
  document.form1.ed23_i_regimetrabalho.focus();
  document.form1.ed23_i_regimetrabalho.value = '';
 }
}
function js_mostraregimetrabalho1(chave1,chave2){
 document.form1.ed23_i_regimetrabalho.value = chave1;
 document.form1.ed24_c_descr.value = chave2;
 db_iframe_regimetrabalho.hide();
}
function js_pesquisaed23_i_ensino(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_ensino','func_ensino.php?funcao_js=parent.js_mostraensino1|ed10_i_codigo|ed10_c_descr','Pesquisa de Ensinos',true);
 }else{
  if(document.form1.ed12_i_ensino.value != ''){
   js_OpenJanelaIframe('','db_iframe_ensino','func_ensino.php?pesquisa_chave='+document.form1.ed12_i_ensino.value+'&funcao_js=parent.js_mostraensino','Pesquisa',false);
  }else{
   document.form1.ed10_c_descr.value = '';
   document.form1.ed23_i_areatrabalho.value = '';
   document.form1.ed25_c_descr.value = '';
   if(document.form1.ed23_i_codigo.value!=""){
     document.form1.ed23_i_disciplina.value = '';
     document.form1.ed232_c_descr.value = '';
   }else{
     document.getElementById("div_disciplina").innerHTML = "";
   }
  }
 }
}
function js_mostraensino(chave,erro){
 document.form1.ed10_c_descr.value = chave;
 document.form1.ed23_i_areatrabalho.value = '';
 document.form1.ed25_c_descr.value = '';
 if(document.form1.ed23_i_codigo.value!=""){
   document.form1.ed23_i_disciplina.value = '';
   document.form1.ed232_c_descr.value = '';
 }else{
   document.getElementById("div_disciplina").innerHTML = "";
 }
 if(erro==true){
  document.form1.ed12_i_ensino.focus();
  document.form1.ed12_i_ensino.value = '';
 }else{
  js_divCarregando("Aguarde, buscando disciplinas","msgBox"); 
  var sAction = 'PesquisaDisciplina';
  var url     = 'edu1_relacaotrabalhoRPC.php';
  parametros  = 'sAction='+sAction+'&ensino='+document.form1.ed12_i_ensino.value+'&disciplinas=<?=$disc_cad?>';
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: parametros,
                                    onComplete: js_retornaPesquisaDisciplina
                                   });
 }
}
function js_mostraensino1(chave1,chave2){
 document.form1.ed12_i_ensino.value = chave1;
 document.form1.ed10_c_descr.value = chave2;
 document.form1.ed23_i_areatrabalho.value = '';
 document.form1.ed25_c_descr.value = '';
 if(document.form1.ed23_i_codigo.value!=""){
   document.form1.ed23_i_disciplina.value = '';
   document.form1.ed232_c_descr.value = '';
 }else{
   document.getElementById("div_disciplina").innerHTML = "";
 }
 db_iframe_ensino.hide();
 js_divCarregando("Aguarde, buscando disciplinas","msgBox"); 
 var sAction = 'PesquisaDisciplina';
 var url     = 'edu1_relacaotrabalhoRPC.php';
 parametros  = 'sAction='+sAction+'&ensino='+document.form1.ed12_i_ensino.value+'&disciplinas=<?=$disc_cad?>';
 var oAjax = new Ajax.Request(url,{method    : 'post',
                                   parameters: parametros,
                                   onComplete: js_retornaPesquisaDisciplina
                                  });
}
function js_retornaPesquisaDisciplina(oAjax){
 js_removeObj("msgBox");
 var oRetorno = eval("("+oAjax.responseText+")");
 if(oRetorno.length==0){
  todas = '';
 }else{
  todas = '<br><input type="checkbox" name="todas" id="todas" value="" onclick="js_todas();">Todas';
 }
 sHtml = '<tr><td><b>Disciplina(s):</b>'+todas+'</td>';
 if(oRetorno.length==0){
  sHtml += '<td>Nenhuma disciplina disponível.</td>';
  document.form1.incluir.disabled = true;
 }else{
  sHtml += '<td>';	 
  sHtml += ' <table><tr>';
  cont = 0;
  for (var i = 0;i < oRetorno.length; i++) {
   cont++;
   with (oRetorno[i]) {
    sHtml += '<td><input type="checkbox" name="coddisciplina[]" id="coddisciplina" value="'+ed12_i_codigo+'"> '+ed232_c_descr.urlDecode()+'</td>';
    if(cont%3==0){
     sHtml += ' </tr><tr>';
    }
   }
  }
  sHtml += ' </tr></table>';
  sHtml += '</td>';
  document.form1.incluir.disabled = false;
 }
 sHtml += '</tr>';
 $('div_disciplina').innerHTML = sHtml;
}
function js_pesquisaed23_i_disciplina(mostra){
 if(document.form1.ed12_i_ensino.value==""){
  alert("Informe primeiro o Nível de Ensino!");
  document.form1.ed23_i_disciplina.value = '';
  document.form1.ed12_i_ensino.style.backgroundColor='#99A9AE';
  document.form1.ed12_i_ensino.focus();
 }else{
  if(mostra==true){
   js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplinarelacao.php?ensino='+document.form1.ed12_i_ensino.value+'&disciplinas=<?=$disc_cad?>&funcao_js=parent.js_mostradisciplina1|ed12_i_codigo|ed232_c_descr','Pesquisa de Disciplinas',true);
  }else{
   if(document.form1.ed23_i_disciplina.value != ''){
    js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplinarelacao.php?ensino='+document.form1.ed12_i_ensino.value+'&disciplinas=<?=$disc_cad?>&pesquisa_chave='+document.form1.ed23_i_disciplina.value+'&funcao_js=parent.js_mostradisciplina','Pesquisa',false);
   }else{
    document.form1.ed232_c_descr.value = '';
   }
  }
 }
}
function js_mostradisciplina(chave,erro){
 document.form1.ed232_c_descr.value = chave;
 if(erro==true){
  document.form1.ed23_i_disciplina.focus();
  document.form1.ed23_i_disciplina.value = '';
 }
}
function js_mostradisciplina1(chave1,chave2){
 document.form1.ed23_i_disciplina.value = chave1;
 document.form1.ed232_c_descr.value = chave2;
 db_iframe_disciplina.hide();
}
function js_todas(){
 tam = document.form1.coddisciplina.length;
 if(tam==undefined){
  if(document.form1.todas.checked==true){
    document.form1.coddisciplina.checked = true;
  }else{
    document.form1.coddisciplina.checked = false;
  }
 }else{
  for(t=0;t<tam;t++){
   if(document.form1.todas.checked==true){
     document.form1.coddisciplina[t].checked = true;
   }else{
     document.form1.coddisciplina[t].checked = false;
   }
  }
 }
}
</script>