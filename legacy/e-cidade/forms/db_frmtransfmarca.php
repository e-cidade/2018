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

//MODULO: marcas
$cltransfmarca->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ma01_i_cgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("p51_descr");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tma02_i_codigo?>">
   <?=@$Lma02_i_codigo?>
  </td>
  <td>
   <?db_input('ma02_i_codigo',10,$Ima02_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tma02_i_marca?>">
   <?db_ancora(@$Lma02_i_marca,"js_pesquisama02_i_marca(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ma02_i_marca',10,$Ima02_i_marca,true,'text',$db_opcao,"onchange=js_pesquisama02_i_marca(false);")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tma02_i_propant?>">
   <?db_ancora(@$Lma02_i_propant,"js_pesquisama02_i_propant(true);",3);?>
  </td>
  <td>
   <?db_input('ma02_i_propant',10,$Ima02_i_propant,true,'text',3," onchange='js_pesquisama02_i_propant(false);'")?>
   <?db_input('nome1',40,@$nome1,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tma02_i_codproc?>">
   <?db_ancora(@$Lma02_i_codproc,"js_pesquisama02_i_codproc(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ma02_i_codproc',10,$Ima02_i_codproc,true,'text',$db_opcao," onchange='js_pesquisama02_i_codproc(false);'")?>
   <?//db_input('ma02_i_codproc',10,$Ima02_i_codproc,true,'text',3," onchange='js_pesquisama02_i_codproc(false);'")?>
   <?db_input('p51_descr',40,$Ip51_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tma02_i_propnovo?>">
   <?db_ancora(@$Lma02_i_propnovo,"js_pesquisama02_i_propnovo(true);",$db_opcao);?>
   <?//db_ancora(@$Lma02_i_propnovo,"js_pesquisama02_i_propnovo(true);",3);?>
  </td>
  <td>
   <?db_input('ma02_i_propnovo',10,$Ima02_i_propnovo,true,'text',$db_opcao," onchange='js_pesquisama02_i_propnovo(false);'")?>
   <?//db_input('ma02_i_propnovo',10,$Ima02_i_propnovo,true,'text',3," onchange='js_pesquisama02_i_propnovo(false);'")?>
   <?db_input('nome2',40,@$nome2,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tma02_d_data?>">
   <?=@$Lma02_d_data?>
  </td>
  <td>
   <?db_inputdata('ma02_d_data',@$ma02_d_data_dia,@$ma02_d_data_mes,@$ma02_d_data_ano,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tma02_t_obs?>">
   <?=@$Lma02_t_obs?>
  </td>
  <td>
   <?db_textarea('ma02_t_obs',5,50,$Ima02_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($db_opcao==1?"disabled":"")?>>
</form>
<script>
function js_pesquisama02_i_marca(mostra){
 if( mostra == true ){
    js_OpenJanelaIframe('top.corpo','db_iframe_marca','func_marca.php?funcao_js=parent.js_mostramarca|ma01_i_codigo|ma01_i_cgm|z01_nome&t','Pesquisa',true);
 }else{
    if(document.form1.ma02_i_marca.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_marca','func_marca.php?pesquisa_chave='+document.form1.ma02_i_marca.value+'&funcao_js=parent.js_mostramarca','Pesquisa',false);
    }else{
      document.form1.ma02_i_marca.value = '';
    }
 }
}
function js_mostramarca1(chave1,chave2,chave3,erro){
 document.form1.ma02_i_marca.value = chave1;
 document.form1.ma02_i_propant.value = chave2;
 document.form1.nome1.value = chave3;
 
}
function js_mostramarca(chave1,chave2,chave3){
 document.form1.ma02_i_marca.value = chave1;
 document.form1.ma02_i_propant.value = chave2;
 document.form1.nome1.value = chave3;
 db_iframe_marca.hide();
}
function js_pesquisama02_i_propnovo(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
 }else{
  if(document.form1.ma02_i_propnovo.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.ma02_i_propnovo.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }else{
   document.form1.nome2.value = '';
  }
 }
}
function js_mostracgm(erro,chave){
 document.form1.nome2.value = chave;
 if(erro==true){
  document.form1.ma02_i_propnovo.focus();
  document.form1.ma02_i_propnovo.value = '';
 }
}
function js_mostracgm1(chave1,chave2){
 document.form1.ma02_i_propnovo.value = chave1;
 document.form1.nome2.value = chave2;
 db_iframe_cgm.hide();
}
function js_pesquisama02_i_codproc(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p51_descr|p58_numcgm|z01_nome','Pesquisa',true);
 }else{
  if(document.form1.ma02_i_codproc.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.ma02_i_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
  }else{
   document.form1.p51_descr.value = '';
  }
 }
}
function js_mostraprotprocesso(chave1,chave2, erro){
 document.form1.p51_descr.value = chave2;
 if(erro==true){
  document.form1.ma02_i_codproc.focus();
  document.form1.ma02_i_codproc.value = '';
 }
}
function js_mostraprotprocesso1(chave1, chave2, chave3, chave4){
 document.form1.ma02_i_codproc.value = chave1;
 document.form1.p51_descr.value = chave2;
 //document.form1.ma02_i_propnovo.value = chave3;
 //document.form1.nome2.value = chave4;
 db_iframe_protprocesso.hide();
}
function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_transfmarca','func_transfmarca.php?funcao_js=parent.js_preenchepesquisa|ma02_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
 db_iframe_transfmarca.hide();
 <?
 if($db_opcao!=1){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
</script>