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
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clalunofora->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed15_i_codigo");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed82_i_codigo");
$clrotulo->label("ed29_i_codigo");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("nome");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 $ed216_d_datacad_dia= substr($ed216_d_datacad,0,2);
 $ed216_d_datacad_mes= substr($ed216_d_datacad,3,2);
 $ed216_d_datacad_ano= substr($ed216_d_datacad,6,4);
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
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
  <td nowrap title="<?=@$Ted216_i_codigo?>">
   <?=@$Led216_i_codigo?>
  </td>
  <td>
   <?db_input('ed216_i_codigo',10,$Ied216_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted216_i_aluno?>">
   <?db_ancora(@$Led216_i_aluno,"js_pesquisaed216_i_aluno(true);",3);?>
  </td>
  <td>
   <?db_input('ed216_i_aluno',10,$Ied216_i_aluno,true,'text',3," onchange='js_pesquisaed216_i_aluno(false);'")?>
   <?db_input('ed47_v_nome',60,@$Ied47_v_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted216_i_cursoedu?>">
   <?db_ancora(@$Led216_i_cursoedu,"js_pesquisaed216_i_cursoedu(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed216_i_cursoedu',10,$Ied216_i_cursoedu,true,'text',$db_opcao," onchange='js_pesquisaed216_i_cursoedu(false);'")?>
   <?db_input('ed29_c_descr',60,@$Ied29_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted216_i_serie?>">
   <?db_ancora(@$Led216_i_serie,"js_pesquisaed216_i_serie(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed216_i_serie',10,$Ied216_i_serie,true,'text',$db_opcao," onchange='js_pesquisaed216_i_serie(false);'")?>
   <?db_input('ed11_c_descr',60,@$Ied11_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted216_i_turno?>">
   <?db_ancora(@$Led216_i_turno,"js_pesquisaed216_i_turno(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed216_i_turno',10,$Ied216_i_turno,true,'text',$db_opcao," onchange='js_pesquisaed216_i_turno(false);'")?>
   <?db_input('ed15_c_nome',60,@$Ied15_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted216_i_escolaproc?>">
   <?db_ancora(@$Led216_i_escolaproc,"js_pesquisaed216_i_escolaproc(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed216_i_escolaproc',10,$Ied216_i_escolaproc,true,'text',$db_opcao," onchange='js_pesquisaed216_i_escolaproc(false);'")?>
   <?db_input('ed82_c_nome',60,@$Ied82_c_nome,true,'text',3,'')?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width='100%'>
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed216_i_codigo"=>@$ed216_i_codigo,"ed216_d_datacad"=>@$ed216_d_datacad,"ed216_i_aluno"=>@$ed216_i_aluno,"ed47_v_nome"=>@$ed47_v_nome,"ed216_i_turno"=>@$ed216_i_turno,"ed15_c_nome"=>@$ed15_c_nome,"ed216_i_cursoedu"=>@$ed216_i_cursoedu,"ed29_c_descr"=>@$ed29_c_descr,"ed216_i_serie"=>@$ed216_i_serie,"ed11_c_descr"=>@$ed11_c_descr,"ed216_i_escolaproc"=>@$ed216_i_escolaproc,"ed82_c_nome"=>@$ed82_c_nome);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clalunofora->sql_query("","*",""," ed216_i_aluno=$ed216_i_aluno");
   $cliframe_alterar_excluir->campos  ="ed82_c_nome,ed29_c_descr,ed11_c_descr,ed15_c_nome,ed216_d_datacad";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
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
</form>
<script>
function js_pesquisaed216_i_turno(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_turno','func_turno.php?funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_c_nome','Pesquisa',true);
 }else{
  if(document.form1.ed216_i_turno.value != ''){
   js_OpenJanelaIframe('','db_iframe_turno','func_turno.php?pesquisa_chave='+document.form1.ed216_i_turno.value+'&funcao_js=parent.js_mostraturno','Pesquisa',false);
  }else{
   document.form1.ed15_c_nome.value = '';
  }
 }
}
function js_mostraturno(chave,erro){
 document.form1.ed15_c_nome.value = chave;
 if(erro==true){
  document.form1.ed216_i_turno.focus();
  document.form1.ed216_i_turno.value = '';
 }
}
function js_mostraturno1(chave1,chave2){
 document.form1.ed216_i_turno.value = chave1;
 document.form1.ed15_c_nome.value = chave2;
 db_iframe_turno.hide();
}
function js_pesquisaed216_i_serie(mostra){
 if(document.form1.ed216_i_cursoedu.value==""){
  alert("Informe o Curso!");
 }else{
  if(mostra==true){
   js_OpenJanelaIframe('','db_iframe_serie','func_serie.php?curso='+document.form1.ed216_i_cursoedu.value+'&funcao_js=parent.js_mostraserie1|ed11_i_codigo|ed11_c_descr','Pesquisa',true);
  }else{
   if(document.form1.ed216_i_serie.value != ''){
    js_OpenJanelaIframe('','db_iframe_serie','func_serie.php?pesquisa_chave='+document.form1.ed216_i_serie.value+'&funcao_js=parent.js_mostraserie','Pesquisa',false);
   }else{
    document.form1.ed11_c_descr.value = '';
   }
  }
 }
}
function js_mostraserie(chave,erro){
 document.form1.ed11_c_descr.value = chave;
 if(erro==true){
  document.form1.ed216_i_serie.focus();
  document.form1.ed216_i_serie.value = '';
 }
}
function js_mostraserie1(chave1,chave2){
 document.form1.ed216_i_serie.value = chave1;
 document.form1.ed11_c_descr.value = chave2;
 db_iframe_serie.hide();
}
function js_pesquisaed216_i_escolaproc(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_escolaproc','func_escolaproc.php?funcao_js=parent.js_mostraescolaproc1|ed82_i_codigo|ed82_c_nome','Pesquisa',true);
 }else{
  if(document.form1.ed216_i_escolaproc.value != ''){
   js_OpenJanelaIframe('','db_iframe_escolaproc','func_escolaproc.php?pesquisa_chave='+document.form1.ed216_i_escolaproc.value+'&funcao_js=parent.js_mostraescolaproc','Pesquisa',false);
  }else{
   document.form1.ed82_c_nome.value = '';
  }
 }
}
function js_mostraescolaproc(chave,erro){
 document.form1.ed82_c_nome.value = chave;
 if(erro==true){
  document.form1.ed216_i_escolaproc.focus();
  document.form1.ed216_i_escolaproc.value = '';
 }
}
function js_mostraescolaproc1(chave1,chave2){
 document.form1.ed216_i_escolaproc.value = chave1;
 document.form1.ed82_c_nome.value = chave2;
 db_iframe_escolaproc.hide();
}
function js_pesquisaed216_i_cursoedu(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_curso','func_cursoedu.php?funcao_js=parent.js_mostracursoedu1|ed29_i_codigo|ed29_c_descr','Pesquisa',true);
 }else{
  if(document.form1.ed216_i_cursoedu.value != ''){
   js_OpenJanelaIframe('','db_iframe_curso','func_cursoedu.php?pesquisa_chave='+document.form1.ed216_i_cursoedu.value+'&funcao_js=parent.js_mostracursoedu','Pesquisa',false);
  }else{
   document.form1.ed29_c_descr.value = '';
  }
 }
}
function js_mostracursoedu(chave,erro){
 document.form1.ed29_c_descr.value = chave;
 if(erro==true){
  document.form1.ed216_i_cursoedu.focus();
  document.form1.ed216_i_cursoedu.value = '';
 }
}
function js_mostracursoedu1(chave1,chave2){
 document.form1.ed216_i_cursoedu.value = chave1;
 document.form1.ed29_c_descr.value = chave2;
 db_iframe_curso.hide();
}
</script>