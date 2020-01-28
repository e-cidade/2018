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

//MODULO: educa��o
$clbase->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed88_i_chminima");
$clrotulo->label("ed88_i_chmaxima");
$clrotulo->label("ed87_i_serieinicial");
$clrotulo->label("ed87_i_seriefinal");
$clrotulo->label("ed89_i_disciplina");
$clrotulo->label("ed89_i_qtdperiodos");
$clrotulo->label("ed223_i_regimematdiv");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td nowrap title="<?=@$Ted31_i_codigo?>" width="15%">
   <?=@$Led31_i_codigo?>
  </td>
  <td>
   <?db_input('ed31_i_codigo',15,$Ied31_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted31_i_curso?>">
   <?db_ancora(@$Led31_i_curso,"js_pesquisaed31_i_curso(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed31_i_curso',15,$Ied31_i_curso,true,'text',$db_opcao1," onchange='js_pesquisaed31_i_curso(false);'")?>
   <?db_input('ed29_c_descr',40,@$Ied29_c_descr,true,'text',3,'')?>
   <?db_input('ed29_i_ensino',10,@$Ied29_i_ensino,true,'hidden',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted31_c_descr?>">
   <?=@$Led31_c_descr?>
  </td>
  <td>
   <?db_input('ed31_c_descr',40,$Ied31_c_descr,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted31_c_turno?>">
   <?=@$Led31_c_turno?>
  </td>
  <td>
   <?
   $x = array('DIURNO'=>'DIURNO','NOTURNO'=>'NOTURNO','DIURNO E NOTURNO'=>'DIURNO E NOTURNO');
   db_select('ed31_c_turno',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted31_i_regimemat?>">
   <?db_ancora(@$Led31_i_regimemat,"js_pesquisaed31_i_regimemat(true);",($db_opcao==1?$db_opcao:3));?>
  </td>
  <td>
   <?db_input('ed31_i_regimemat',20,@$Ieded31_i_regimemat,true,'text',($db_opcao==1?$db_opcao:3)," onchange='js_pesquisaed31_i_regimemat(false);'")?>
   <?db_input('ed218_c_nome',30,@$Ied218_c_nome,true,'text',3,'')?>
   <?db_input('ed218_c_divisao',1,@$Ied218_c_divisao,true,'hidden',3,'')?>
  </td>
 </tr>
 <tbody id="div_divisao">
 </tbody>
 <tr>
  <td nowrap title="<?=@$Ted87_i_serieinicial?>">
   <?db_ancora(@$Led87_i_serieinicial,"js_pesquisaed87_i_serieinicial(true,document.form1.ed31_i_curso.value);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed87_i_serieinicial',15,$Ied87_i_serieinicial,true,'text',$db_opcao," onchange='js_pesquisaed87_i_serieinicial(false,document.form1.ed31_i_curso.value);'")?>
   <?db_input('ed11_c_descrini',30,@$Ied11_c_descrini,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted87_i_seriefinal?>">
   <?db_ancora(@$Led87_i_seriefinal,"js_pesquisaed87_i_seriefinal(true,document.form1.ed31_i_curso.value,document.form1.ed87_i_serieinicial.value);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed87_i_seriefinal',15,$Ied87_i_seriefinal,true,'text',$db_opcao," onchange='js_pesquisaed87_i_seriefinal(false,document.form1.ed31_i_curso.value,document.form1.ed87_i_serieinicial.value);'")?>
   <?db_input('ed11_c_descrfim',30,@$Ied11_c_descrfim,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted31_c_medfreq?>">
   <?=@$Led31_c_medfreq?>
  </td>
  <td>
   <?
   $x = array('P'=>'PER�ODOS','D'=>'DIAS LETIVOS');
   db_select('ed31_c_medfreq',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted31_c_contrfreq?>">
   <?=@$Led31_c_contrfreq?>
  </td>
  <td>
   <?
   $x = array('I'=>'INDIVIDUAL ','G'=>'GLOBALIZADO');
   db_select('ed31_c_contrfreq',$x,true,$db_opcao," onchange='js_disciplina(this.value)'");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted89_i_disciplina?>" colspan="2">
   <?
   if(isset($ed31_c_contrfreq) && $ed31_c_contrfreq=="G"){
    $visible = "visible";
   }else{
    $visible = "hidden";
   }
   ?>
   <span id="disciplina" name="disciplina" style="visibility:<?=$visible?>;">
   <table border="0">
    <tr>
     <td width="28%">
      <?db_ancora(@$Led89_i_disciplina,"js_pesquisaed89_i_disciplina(true,document.form1.ed31_i_curso.value);",$db_opcao);?>
     </td>
     <td>
      <?db_input('ed89_i_disciplina',15,$Ied89_i_disciplina,true,'text',$db_opcao," onchange='js_pesquisaed89_i_disciplina(false,document.form1.ed31_i_curso.value);'")?>
      <?db_input('ed232_c_descr',30,@$Ied232_c_descr,true,'text',3,'')?>
      <?=@$Led89_i_qtdperiodos?>
      <?db_input('ed89_i_qtdperiodos',10,$Ied89_i_qtdperiodos,true,'text',$db_opcao,"")?>
     </td>
    </tr>
   </table>
   </span>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted31_t_obs?>">
   <?=@$Led31_t_obs?>
  </td>
  <td>
   <?db_textarea('ed31_t_obs',3,50,$Ied31_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted31_c_conclusao?>">
   <?=@$Led31_c_conclusao?>
  </td>
  <td>
   <?
   $x = array('N'=>'N�O','S'=>'SIM');
   db_select('ed31_c_conclusao',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted31_c_ativo?>">
   <?=@$Led31_c_ativo?>
  </td>
  <td>
   <?
   @$ed31_c_ativo = $db_opcao==1?"S":$ed31_c_ativo;
   $x = array('S'=>'SIM','N'=>'N�O');
   db_select('ed31_c_ativo',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="<?=$db_opcao==1?'return js_valida();':''?>">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
<input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
<?if(isset($chavepesquisa)){?>
<input name="replicar" type="button" id="replicar" value="Replicar esta Base" onclick="js_replicar(<?=$ed31_i_codigo?>)">
<?}?>
</form>
<script>
function js_pesquisaed31_i_curso(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_curso','func_cursoescola.php?funcao_js=parent.js_mostracurso1|ed29_i_codigo|ed29_c_descr|ed10_i_codigo','Pesquisa de Cursos',true);
 }else{
  if(document.form1.ed31_i_curso.value != ''){
   js_OpenJanelaIframe('','db_iframe_curso','func_cursoescola.php?pesquisa_chave1='+document.form1.ed31_i_curso.value+'&funcao_js=parent.js_mostracurso','Pesquisa',false);
  }else{
   document.form1.ed29_c_descr.value = '';
   document.form1.ed29_i_ensino.value = '';
   document.form1.ed31_i_regimemat.value = '';
   document.form1.ed218_c_nome.value = '';
   document.form1.ed218_c_divisao.value = '';
   document.form1.ed87_i_serieinicial.value = '';
   document.form1.ed11_c_descrini.value = '';
   document.form1.ed87_i_seriefinal.value = '';
   document.form1.ed11_c_descrfim.value = '';
   js_divisoes(0,"I");
  }
 }
}
function js_mostracurso(chave1,chave2,erro){
 document.form1.ed29_c_descr.value = chave1;
 document.form1.ed29_i_ensino.value = chave2;
 if(erro==true){
  document.form1.ed31_i_curso.focus();
  document.form1.ed31_i_curso.value = '';
 }
 document.form1.ed31_i_regimemat.value = '';
 document.form1.ed218_c_nome.value = '';
 document.form1.ed218_c_divisao.value = '';
 document.form1.ed87_i_serieinicial.value = '';
 document.form1.ed11_c_descrini.value = '';
 document.form1.ed87_i_seriefinal.value = '';
 document.form1.ed11_c_descrfim.value = '';
 js_divisoes(0,"I");
}
function js_mostracurso1(chave1,chave2,chave3){
 document.form1.ed31_i_curso.value = chave1;
 document.form1.ed29_c_descr.value = chave2;
 document.form1.ed29_i_ensino.value = chave3;
 document.form1.ed31_i_regimemat.value = '';
 document.form1.ed218_c_nome.value = '';
 document.form1.ed218_c_divisao.value = '';
 document.form1.ed87_i_serieinicial.value = '';
 document.form1.ed11_c_descrini.value = '';
 document.form1.ed87_i_seriefinal.value = '';
 document.form1.ed11_c_descrfim.value = '';
 js_divisoes(0,"I");
 db_iframe_curso.hide();
}
function js_pesquisaed87_i_serieinicial(mostra,curso){
 if(curso==""){
  alert("Informe o Curso!");
  document.form1.ed87_i_serieinicial.value = '';
 }else if(document.form1.ed31_i_regimemat.value==""){
  alert("Informe o Regime de Matr�cula!");
  document.form1.ed87_i_serieinicial.value = '';
 }else if(document.form1.ed218_c_divisao.value=="S" && js_verificadivisao()){
  alert("Informe alguma divis�o do Regime de Matr�cula!");
  document.form1.ed87_i_serieinicial.value = '';
 }else{
  if(document.form1.ed218_c_divisao.value=="S"){
   tam = document.form1.divisao.length;
   if(tam==undefined){
    coddivisoes = document.form1.divisao.value;
   }else{
    coddivisoes = "";
    sep = "";
    for(i=0;i<tam;i++){
     if(document.form1.divisao[i].checked==true){
      coddivisoes += sep+document.form1.divisao[i].value;
      sep = ",";
     }
    }
   }
  }else{
   coddivisoes = "";
  }
  if(mostra==true){
   js_OpenJanelaIframe('','db_iframe_serie','func_seriebaseregime.php?codensino='+document.form1.ed29_i_ensino.value+'&codregime='+document.form1.ed31_i_regimemat.value+'&coddivisao='+coddivisoes+'&funcao_js=parent.js_mostraserie1|ed11_i_codigo|ed11_c_descr','Pesquisa de Etapas',true);
  }else{
   if(document.form1.ed87_i_serieinicial.value != ''){
    js_OpenJanelaIframe('','db_iframe_serie','func_seriebaseregime.php?codensino='+document.form1.ed29_i_ensino.value+'&codregime='+document.form1.ed31_i_regimemat.value+'&coddivisao='+coddivisoes+'&pesquisa_chave='+document.form1.ed87_i_serieinicial.value+'&funcao_js=parent.js_mostraserie','Pesquisa',false);
   }else{
    document.form1.ed11_c_descrini.value = '';
   }
  }
 }
}
function js_mostraserie(chave,erro){
 document.form1.ed11_c_descrini.value = chave;
 if(erro==true){
  document.form1.ed87_i_serieinicial.focus();
  document.form1.ed87_i_serieinicial.value = '';
 }
 document.form1.ed87_i_seriefinal.value = '';
 document.form1.ed11_c_descrfim.value = '';
}
function js_mostraserie1(chave1,chave2){
 document.form1.ed87_i_serieinicial.value = chave1;
 document.form1.ed11_c_descrini.value = chave2;
 document.form1.ed87_i_seriefinal.value = '';
 document.form1.ed11_c_descrfim.value = '';
 db_iframe_serie.hide();
}
function js_pesquisaed87_i_seriefinal(mostra,curso,inicial){
 if(curso==""){
  alert("Informe o Curso!");
  document.form1.ed87_i_seriefinal.value = '';
 }else if(document.form1.ed31_i_regimemat.value==""){
  alert("Informe o Regime de Matr�cula!");
  document.form1.ed87_i_seriefinal.value = '';
 }else if(document.form1.ed218_c_divisao.value=="S" && js_verificadivisao()){
  alert("Informe alguma divis�o do Regime de Matr�cula!");
  document.form1.ed87_i_seriefinal.value = '';
 }else{
  if(inicial==""){
   alert("Informe a etapa inicial primeiro!");
   document.form1.ed87_i_seriefinal.value = '';
  }else{
   if(document.form1.ed218_c_divisao.value=="S"){
    tam = document.form1.divisao.length;
    if(tam==undefined){
     coddivisoes = document.form1.divisao.value;
    }else{
     coddivisoes = "";
     sep = "";
     for(i=0;i<tam;i++){
      if(document.form1.divisao[i].checked==true){
       coddivisoes += sep+document.form1.divisao[i].value;
       sep = ",";
      }
     }
    }
   }else{
    coddivisoes = "";
   }
   if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_serie','func_seriebaseregime.php?inicial='+inicial+'&codensino='+document.form1.ed29_i_ensino.value+'&codregime='+document.form1.ed31_i_regimemat.value+'&coddivisao='+coddivisoes+'&funcao_js=parent.js_mostraseriefim1|ed11_i_codigo|ed11_c_descr','Pesquisa de Etapas',true);
   }else{
    if(document.form1.ed87_i_seriefinal.value != ''){
     js_OpenJanelaIframe('','db_iframe_serie','func_seriebaseregime.php?inicial='+inicial+'&codensino='+document.form1.ed29_i_ensino.value+'&codregime='+document.form1.ed31_i_regimemat.value+'&coddivisao='+coddivisoes+'&pesquisa_chave='+document.form1.ed87_i_seriefinal.value+'&funcao_js=parent.js_mostraseriefim','Pesquisa',false);
    }else{
     document.form1.ed11_c_descrfim.value = '';
    }
   }
  }
 }
}
function js_mostraseriefim(chave,erro){
 document.form1.ed11_c_descrfim.value = chave;
 if(erro==true){
  document.form1.ed87_i_seriefinal.focus();
  document.form1.ed87_i_seriefinal.value = '';
 }
}
function js_mostraseriefim1(chave1,chave2){
 document.form1.ed87_i_seriefinal.value = chave1;
 document.form1.ed11_c_descrfim.value = chave2;
 db_iframe_serie.hide();
}
function js_pesquisaed89_i_disciplina(mostra,curso){
 if(curso==""){
  alert("Informe o curso!");
  document.form1.ed89_i_disciplina.value = '';
 }else{
  if(mostra==true){
   js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplina.php?curso='+curso+'&funcao_js=parent.js_mostradisciplina1|ed12_i_codigo|ed232_c_descr','Pesquisa de Disciplinas',true);
  }else{
   if(document.form1.ed89_i_disciplina.value != ''){
    js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplina.php?curso='+curso+'&pesquisa_chave='+document.form1.ed89_i_disciplina.value+'&funcao_js=parent.js_mostradisciplina','Pesquisa',false);
   }else{
    document.form1.ed232_c_descr.value = '';
   }
  }
 }
}
function js_mostradisciplina(chave,erro){
 document.form1.ed232_c_descr.value = chave;
 if(erro==true){
  document.form1.ed89_i_disciplina.focus();
  document.form1.ed89_i_disciplina.value = '';
 }
}
function js_mostradisciplina1(chave1,chave2){
 document.form1.ed89_i_disciplina.value = chave1;
 document.form1.ed232_c_descr.value = chave2;
 db_iframe_disciplina.hide();
}
function js_pesquisaed31_i_regimemat(mostra){
 if(document.form1.ed31_i_curso.value==""){
  alert("Informe o Curso!");
  document.form1.ed31_i_regimemat.value = '';
 }else{
  if(mostra==true){
   js_OpenJanelaIframe('','db_iframe_regimemat','func_serieregimemat.php?codensino='+document.form1.ed29_i_ensino.value+'&funcao_js=parent.js_mostraregimemat1|ed218_i_codigo|ed218_c_nome|ed218_c_divisao','Pesquisa de Regimes de Matr�cula',true);
  }else{
   if(document.form1.ed31_i_regimemat.value != ''){
    js_OpenJanelaIframe('','db_iframe_regimemat','func_serieregimemat.php?codensino='+document.form1.ed29_i_ensino.value+'&pesquisa_chave='+document.form1.ed31_i_regimemat.value+'&funcao_js=parent.js_mostraregimemat','Pesquisa',false);
   }else{
    document.form1.ed218_c_nome.value = '';
    document.form1.ed218_c_divisao.value = '';
    document.form1.ed87_i_serieinicial.value = '';
    document.form1.ed11_c_descrini.value = '';
    document.form1.ed87_i_seriefinal.value = '';
    document.form1.ed11_c_descrfim.value = '';
    js_divisoes(0,"I");
   }
  }
 }
}
function js_mostraregimemat(chave1,chave2,erro){
 document.form1.ed218_c_nome.value = chave1;
 document.form1.ed218_c_divisao.value = chave2;
 if(erro==true){
  document.form1.ed31_i_regimemat.focus();
  document.form1.ed31_i_regimemat.value = '';
  js_divisoes(0,"I");
 }else{
  if(chave2=="S"){
   js_divisoes(document.form1.ed31_i_regimemat.value,"I");
  }else{
   js_divisoes(0,"I");
  }
 }
 document.form1.ed87_i_serieinicial.value = '';
 document.form1.ed11_c_descrini.value = '';
 document.form1.ed87_i_seriefinal.value = '';
 document.form1.ed11_c_descrfim.value = '';
}
function js_mostraregimemat1(chave1,chave2,chave3){
 document.form1.ed31_i_regimemat.value = chave1;
 document.form1.ed218_c_nome.value = chave2;
 document.form1.ed218_c_divisao.value = chave3;
 if(chave3=="S"){
  js_divisoes(document.form1.ed31_i_regimemat.value,"I");
 }else{
  js_divisoes(0,"I");
 }
 document.form1.ed87_i_serieinicial.value = '';
 document.form1.ed11_c_descrini.value = '';
 document.form1.ed87_i_seriefinal.value = '';
 document.form1.ed11_c_descrfim.value = '';
 db_iframe_regimemat.hide();
}
function js_pesquisa(){
 js_OpenJanelaIframe('','db_iframe_base','func_base.php?funcao_js=parent.js_preenchepesquisa|ed31_i_codigo','Pesquisa de Bases Curriculares',true);
}
function js_divisoes(codregime,tipodml){
 if(codregime==0){
  $('div_divisao').innerHTML = "";
  return false;
 }
 if(tipodml=="I"){
  js_divCarregando("Aguarde, buscando registro(s)","msgBox");
  var sAction = 'PesquisaDivisao';
  var url     = 'edu1_baseRPC.php';
  parametros  = 'sAction='+sAction+'&regime='+codregime;
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: parametros,
                                    onComplete: js_retornaPesquisaDivisao
                                   });
 }else{
  var sAction = 'PesquisaDivisaoCadastrada';
  var url     = 'edu1_baseRPC.php';
  parametros  = 'sAction='+sAction+'&base='+$('ed31_i_codigo').value;
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: parametros,
                                    onComplete: js_retornaPesquisaDivisaoCadastrada
                                   });
 }
}
function js_retornaPesquisaDivisao(oAjax){
 js_removeObj("msgBox");
 var oRetorno = eval("("+oAjax.responseText+")");
 sHtml = '<tr>';
 sHtml += ' <td valign="top"><b><?=@$Led223_i_regimematdiv?></b>';
 sHtml += ' </td>';
 sHtml += ' <td>';
 if(oRetorno.length==0){
  sHtml += '  Nenhuma divis�o cadastrada para o regime de matr�cula selecionado.';
  sHtml += '  <input type="hidden" name="divisao[]" id="divisao" value="N">';
 }else{
  cont = 0;
  for (var i = 0;i < oRetorno.length; i++) {
   cont++;
   with (oRetorno[i]) {
    sHtml += '  <input type="checkbox" name="divisao[]" id="divisao" value="'+ed219_i_codigo+'" onclick="js_limpaserie();"> '+ed219_c_nome.urlDecode();
    if((cont%3)==0){
     sHtml += '<br>';
    }
   }
  }
 }
 sHtml += ' </td>';
 sHtml += '</tr>';
 $('div_divisao').innerHTML = sHtml;
}
function js_retornaPesquisaDivisaoCadastrada(oAjax){
 var oRetorno = eval("("+oAjax.responseText+")");
 sHtml = '<tr>';
 sHtml += ' <td valign="top"><b><?=@$Led223_i_regimematdiv?></b>';
 sHtml += ' </td>';
 sHtml += ' <td>';
 if(oRetorno.length==0){
  sHtml += '  Nenhuma divis�o cadastrada para a base selecionada.';
  sHtml += '  <input type="hidden" name="divisao[]" id="divisao" value="N">';
 }else{
  cont = 0;
  for (var i = 0;i < oRetorno.length; i++) {
   cont++;
   with (oRetorno[i]) {
    sHtml += '  <input type="checkbox" name="divisao[]" id="divisao" value="'+ed219_i_codigo+'" onclick="js_limpaserie();" checked disabled> '+ed219_c_nome.urlDecode();
    if((cont%3)==0){
     sHtml += '<br>';
    }
   }
  }
 }
 sHtml += ' </td>';
 sHtml += '</tr>';
 $('div_divisao').innerHTML = sHtml;
}

function js_preenchepesquisa(chave){
 db_iframe_base.hide();
 <?
 if($db_opcao!=1){
   echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
function js_disciplina(valor){
 if(valor=="G"){
  document.getElementById("disciplina").style.visibility = "visible";
 }else{
  document.getElementById("disciplina").style.visibility = "hidden";
 }
}
function js_novo(){
 parent.location.href="edu1_baseabas001.php";
}
function js_verificadivisao(){
 tam = document.form1.divisao.length;
 if(tam==undefined){
  if(document.form1.divisao.value=="N"){
   return true;
  }else{
   if(document.form1.divisao.checked==false){
    return true;
   }
  }
 }else{
  checado = false;
  for(i=0;i<tam;i++){
   if(document.form1.divisao[i].checked==true){
    checado = true;
    break;
   }
  }
  if(checado==false){
   return true;
  }
 }
}
function js_limpaserie(){
 document.form1.ed87_i_serieinicial.value = '';
 document.form1.ed11_c_descrini.value = '';
 document.form1.ed87_i_seriefinal.value = '';
 document.form1.ed11_c_descrfim.value = '';
}
function js_valida(){
 if(document.form1.ed218_c_divisao.value=="S" && js_verificadivisao()){
  alert("Informe alguma divis�o do Regime de Matr�cula!");
  return false;
 }
 return true;
}
function js_replicar(codbase){
  js_OpenJanelaIframe('','db_iframe_replicacao','edu1_replicabase001.php?codbase='+codbase,'Replica��o de Base Curricular',true);
}
</script>