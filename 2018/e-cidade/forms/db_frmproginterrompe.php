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
$clproginterrompe->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed112_i_codigo");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted123_i_codigo?>">
   <?=@$Led123_i_codigo?>
  </td>
  <td>
   <?db_input('ed123_i_codigo',10,$Ied123_i_codigo,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted123_i_progmatricula?>">
   <?db_ancora(@$Led123_i_progmatricula,"js_pesquisaed123_i_progmatricula(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed123_i_progmatricula',10,$Ied123_i_progmatricula,true,'text',$db_opcao," onchange='js_pesquisaed123_i_progmatricula(false);'")?>
   <?db_input('ed112_i_codigo',10,$Ied112_i_codigo,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted123_i_usuario?>">
   <?db_ancora(@$Led123_i_usuario,"js_pesquisaed123_i_usuario(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed123_i_usuario',10,$Ied123_i_usuario,true,'text',$db_opcao," onchange='js_pesquisaed123_i_usuario(false);'")?>
   <?db_input('nome',40,$Inome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted123_d_data?>">
   <?=@$Led123_d_data?>
  </td>
  <td>
   <?db_inputdata('ed123_d_data',@$ed123_d_data_dia,@$ed123_d_data_mes,@$ed123_d_data_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted123_t_motivo?>">
   <?=@$Led123_t_motivo?>
  </td>
  <td>
   <?db_textarea('ed123_t_motivo',0,0,$Ied123_t_motivo,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed123_i_progmatricula(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_progmatricula','func_progmatricula.php?funcao_js=parent.js_mostraprogmatricula1|ed112_i_codigo|ed112_i_codigo','Pesquisa',true);
 }else{
  if(document.form1.ed123_i_progmatricula.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_progmatricula','func_progmatricula.php?pesquisa_chave='+document.form1.ed123_i_progmatricula.value+'&funcao_js=parent.js_mostraprogmatricula','Pesquisa',false);
  }else{
   document.form1.ed112_i_codigo.value = '';
  }
 }
}
function js_mostraprogmatricula(chave,erro){
 document.form1.ed112_i_codigo.value = chave;
 if(erro==true){
  document.form1.ed123_i_progmatricula.focus();
  document.form1.ed123_i_progmatricula.value = '';
 }
}
function js_mostraprogmatricula1(chave1,chave2){
 document.form1.ed123_i_progmatricula.value = chave1;
 document.form1.ed112_i_codigo.value = chave2;
 db_iframe_progmatricula.hide();
}
function js_pesquisaed123_i_usuario(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
 }else{
  if(document.form1.ed123_i_usuario.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.ed123_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }else{
   document.form1.nome.value = '';
  }
 }
}
function js_mostradb_usuarios(chave,erro){
 document.form1.nome.value = chave;
 if(erro==true){
  document.form1.ed123_i_usuario.focus();
  document.form1.ed123_i_usuario.value = '';
 }
}
function js_mostradb_usuarios1(chave1,chave2){
 document.form1.ed123_i_usuario.value = chave1;
 document.form1.nome.value = chave2;
 db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_proginterrompe','func_proginterrompe.php?funcao_js=parent.js_preenchepesquisa|ed123_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
 db_iframe_proginterrompe.hide();
 <?
 if($db_opcao!=1){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
</script>