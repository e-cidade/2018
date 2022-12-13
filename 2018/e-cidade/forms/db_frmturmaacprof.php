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

//MODULO: escola
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clturmaacprof->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed268_i_codigo");
$clrotulo->label("ed20_i_codigo");
$db_botao1 = false;
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 $sql0 = "SELECT ed222_i_codigo,ed222_i_turmaac FROM turmaacprof WHERE ed222_i_turmaac = $ed222_i_turmaac";
 $result0 = pg_query($sql0);
 if(pg_num_rows($result0)>0){
  db_fieldsmemory($result0,0);
 }
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $sql0 = "SELECT ed222_i_codigo,ed20_i_rechumano FROM turmaacprof WHERE ed222_i_turmaac = $ed222_i_turmaac";
 $result0 = pg_query($sql0);
 if(pg_num_rows($result0)>0){
  db_fieldsmemory($result0,0);
 }
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
    <td nowrap title="<?=@$Ted222_i_codigo?>">
       <?=@$Led222_i_codigo?>
    </td>
    <td> 
     <?db_input('ed222_i_codigo',10,@$Ied222_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted222_i_turmaac?>">
       <?db_ancora(@$Led222_i_turmaac,"js_pesquisaed222_i_turmaac(true);",3);?>
    </td>
    <td> 
      <?db_input('ed222_i_turmaac',10,@$Ied222_i_turmaac,true,'text',3,"")?>
      <?db_input('ed268_c_descr',40,@$Ied268_c_descr,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted222_i_rechumano?>">
       <?db_ancora(@$Led222_i_rechumano,"js_pesquisaed222_i_rechumano(true);",$db_opcao);?>
    </td>
    <td> 
	  <?db_input('ed222_i_rechumano',10,@$Ied222_i_rechumano,true,'text',$db_opcao," onchange='js_pesquisaed222_i_rechumano(false);'")?>
      <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
    </td>
  </tr>
  </table>
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();">
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width='100%'>
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed222_i_codigo"=>@$ed222_i_codigo,"ed222_i_turmaac"=>@$ed222_i_turmaac,"ed268_c_descr"=>@$ed268_c_descr,"ed222_i_rechumano"=>@$ed222_i_rechumano,"z01_nome"=>@$z01_nome);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql = $clturmaacprof->sql_query("","*","","ed222_i_turmaac=$ed222_i_turmaac");
   $cliframe_alterar_excluir->campos  ="ed222_i_codigo,ed222_i_rechumano,z01_nome";
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
<script>
function js_pesquisaed222_i_rechumano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_rechumanoturmaac','func_rechumanoturmaac.php?funcao_js=parent.js_mostrarechumano1|ed20_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ed222_i_rechumano.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_rechumanoturmaac','func_rechumanoturmaac.php?pesquisa_chave='+document.form1.ed222_i_rechumano.value+'&funcao_js=parent.js_mostrarechumano','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostrarechumano(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed222_i_rechumano.focus(); 
    document.form1.ed222_i_rechumano.value = ''; 
  }
}
function js_mostrarechumano1(chave1,chave2){
  document.form1.ed222_i_rechumano.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rechumanoturmaac.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_turmaacprof','func_turmaacprof.php?funcao_js=parent.js_preenchepesquisa|ed222_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_turmaacprof.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>