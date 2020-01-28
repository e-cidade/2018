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
$clcursoturno->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed29_i_codigo");
$clrotulo->label("ed15_i_codigo");
$clrotulo->label("ed18_i_codigo");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
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
<?db_input('ed85_i_codigo',15,$Ied85_i_codigo,true,'hidden',3,"")?>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted85_i_escola?>">
   <?db_ancora(@$Led85_i_escola,"",3);?>
  </td>
  <td>
   <?db_input('ed85_i_escola',15,$Ied85_i_escola,true,'text',3,"")?>
   <?db_input('ed18_c_nome',40,@$Ied18_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted85_i_curso?>">
   <?db_ancora(@$Led85_i_curso,"",3);?>
  </td>
  <td>
   <?db_input('ed85_i_curso',15,$Ied85_i_curso,true,'text',3,"")?>
   <?db_input('ed29_c_descr',40,@$Ied29_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted85_i_turno?>">
   <?db_ancora(@$Led85_i_turno,"js_pesquisaed85_i_turno(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed85_i_turno',15,$Ied85_i_turno,true,'text',$db_opcao," onchange='js_pesquisaed85_i_turno(false);'")?>
   <?db_input('ed15_c_nome',40,@$Ied15_c_nome,true,'text',3,'')?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed85_i_codigo"=>@$ed85_i_codigo,"ed85_i_escola"=>@$ed85_i_escola,"ed85_i_turno"=>@$ed85_i_turno,"ed15_c_nome"=>@$ed15_c_nome,"ed85_i_curso"=>@$ed85_i_curso,"ed29_c_descr"=>@$ed29_c_descr);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clcursoturno->sql_query("","*","ed15_i_sequencia"," ed85_i_escola = $ed85_i_escola AND ed85_i_curso = $ed85_i_curso");
   $cliframe_alterar_excluir->campos  ="ed15_c_nome";
   $cliframe_alterar_excluir->labels  ="ed85_i_turno";
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
<script>
function js_pesquisaed85_i_turno(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_turno','func_turnoescola.php?turnos=<?=$turno_cad?>&funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_c_nome','Pesquisa de Turnos',true);
 }else{
  if(document.form1.ed85_i_turno.value != ''){
   js_OpenJanelaIframe('','db_iframe_turno','func_turnoescola.php?turnos=<?=$turno_cad?>&pesquisa_chave='+document.form1.ed85_i_turno.value+'&funcao_js=parent.js_mostraturno','Pesquisa',false);
  }else{
   document.form1.ed15_c_nome.value = '';
  }
 }
}
function js_mostraturno(chave,erro){
 document.form1.ed15_c_nome.value = chave;
 if(erro==true){
  document.form1.ed85_i_turno.focus();
  document.form1.ed85_i_turno.value = '';
 }
}
function js_mostraturno1(chave1,chave2){
 document.form1.ed85_i_turno.value = chave1;
 document.form1.ed15_c_nome.value = chave2;
 db_iframe_turno.hide();
}
</script>