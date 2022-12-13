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

//MODULO: saude
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clsau_gestaoativ->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd45_i_programa");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 $sd45_i_programa = $sd45_i_programa=="AMBULATORIAL"?1:2;
 $sd47_i_indgestao = $sd47_i_indgestao=="ESTADUAL"?1:2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $sd45_i_programa = $sd45_i_programa=="AMBULATORIAL"?1:2;
 $sd47_i_indgestao = $sd47_i_indgestao=="ESTADUAL"?1:2;
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
  <td nowrap title="<?=@$Tsd47_i_codigo?>">
   <?=@$Lsd47_i_codigo?>
  </td>
  <td>
   <?db_input('sd47_i_codigo',10,$Isd47_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd47_i_unidade?>">
   <?=@$Lsd47_i_unidade?>
  </td>
  <td>
   <?db_input('sd47_i_unidade',10,$Isd47_i_unidade,true,'text',3,"")?>
   <?db_input('descrdepto',40,@$Idescrdepto,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd45_i_programa?>">
   <?=@$Lsd45_i_programa?>
  </td>
  <td>
   <?
   $x = array(''=>'','1'=>'AMBULATORIAL','2'=>'HOSPITALAR');
   db_select('sd45_i_programa',$x,true,$db_opcao," onchange='js_limpaprograma();'");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd47_i_indgestao?>">
   <?=@$Lsd47_i_indgestao?>
  </td>
  <td>
   <?
   $x = array(''=>'','1'=>'ESTADUAL','2'=>'MUNICIPAL');
   db_select('sd47_i_indgestao',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd47_i_programa?>">
   <?db_ancora(@$Lsd47_i_programa,"js_pesquisasd47_i_programa(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('sd47_i_programa',10,$Isd47_i_programa,true,'text',$db_opcao," onchange='js_pesquisasd47_i_programa(false);'")?>
   <?db_input('sd45_v_descricao',60,@$Isd45_v_descricao,true,'text',3,'')?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $campos = "sd47_i_codigo,
              sd47_i_unidade,
              descrdepto,
              case when sd45_i_programa=1
               then 'AMBULATORIAL' else 'HOSPITALAR'
              end as sd45_i_programa,
              case when sd47_i_indgestao=1
               then 'ESTADUAL' else 'MUNICIPAL'
              end as sd47_i_indgestao,
              sd47_i_programa,
              sd45_v_descricao
             ";
   $escola = db_getsession("DB_coddepto");
   $chavepri= array("sd47_i_codigo"=>@$sd47_i_codigo,"sd47_i_unidade"=>@$sd47_i_unidade,"descrdepto"=>@$descrdepto,"sd45_i_programa"=>@$sd45_i_programa,"sd47_i_indgestao"=>@$sd47_i_indgestao,"sd47_i_programa"=>@$sd47_i_programa,"sd45_v_descricao"=>@$sd45_v_descricao);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clsau_gestaoativ->sql_query("",$campos,""," sd47_i_unidade = $sd47_i_unidade");
   $cliframe_alterar_excluir->campos  ="sd47_i_codigo,sd45_i_programa,sd45_v_descricao,sd47_i_indgestao";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="110";
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
function js_pesquisasd47_i_programa(mostra){
 if(document.form1.sd45_i_programa.value==""){
  alert("Informe o Tipo de Programa!");
  document.form1.sd47_i_programa.value = "";
  document.form1.sd45_i_programa.style.backgroundColor='#99A9AE';
  document.form1.sd45_i_programa.focus();
 }else{
  if(mostra==true){
   js_OpenJanelaIframe('','db_iframe_sau_tipoatend','func_sau_tipoatend.php?programa='+document.form1.sd45_i_programa.value+'&funcao_js=parent.js_mostrasau_tipoatend1|sd45_i_codigo|sd45_v_descricao','Pesquisa',true,0,0,screen.availWidth-140,350);
  }else{
   if(document.form1.sd47_i_programa.value != ''){
    js_OpenJanelaIframe('','db_iframe_sau_tipoatend','func_sau_tipoatend.php?programa='+document.form1.sd45_i_programa.value+'&pesquisa_chave='+document.form1.sd47_i_programa.value+'&funcao_js=parent.js_mostrasau_tipoatend','Pesquisa',false);
   }else{
    document.form1.sd45_v_descricao.value = '';
   }
  }
 }
}
function js_mostrasau_tipoatend(chave,erro){
 document.form1.sd45_v_descricao.value = chave;
 if(erro==true){
  document.form1.sd47_i_programa.focus();
  document.form1.sd47_i_programa.value = '';
 }
}
function js_mostrasau_tipoatend1(chave1,chave2){
 document.form1.sd47_i_programa.value = chave1;
 document.form1.sd45_v_descricao.value = chave2;
 db_iframe_sau_tipoatend.hide();
}
function js_limpaprograma(){
 document.form1.sd47_i_programa.value = '';
 document.form1.sd45_v_descricao.value = '';
}
</script>