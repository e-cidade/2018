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
$clfamiliamicroarea->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd33_i_codigo");
$clrotulo->label("sd34_i_codigo");
$clrotulo->label("sd34_v_descricao");
$clrotulo->label("sd33_v_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd35_i_codigo?>">
       <?=@$Lsd35_i_codigo?>
    </td>
    <td> 
<?
db_input('sd35_i_codigo',10,$Isd35_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd35_i_familia?>">
       <?
       db_ancora(@$Lsd35_i_familia,"js_pesquisasd35_i_familia(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd35_i_familia',10,$Isd35_i_familia,true,'text',$db_opcao," onchange='js_pesquisasd35_i_familia(false);'")
?>
       <?
db_input('sd33_v_descricao',60,$Isd33_v_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd35_i_microarea?>">
       <?
       db_ancora(@$Lsd35_i_microarea,"js_pesquisasd35_i_microarea(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd35_i_microarea',10,$Isd35_i_microarea,true,'text',$db_opcao," onchange='js_pesquisasd35_i_microarea(false);'")
?>
       <?
db_input('sd34_v_descricao',60,$Isd34_v_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($db_opcao==1?"disabled":"")?> >
</form>
<script>
function js_pesquisasd35_i_familia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_familia','func_familia.php?funcao_js=parent.js_mostrafamilia1|sd33_i_codigo|sd33_v_descricao','Pesquisa',true);
  }else{
     if(document.form1.sd35_i_familia.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_familia','func_familia.php?pesquisa_chave='+document.form1.sd35_i_familia.value+'&funcao_js=parent.js_mostrafamilia','Pesquisa',false );
     }else{
       document.form1.sd33_i_codigo.value = ''; 
     }
  }
}
function js_mostrafamilia(chave,erro){
  document.form1.sd33_v_descricao.value = chave;
  if(erro==true){ 
    document.form1.sd35_i_familia.focus(); 
    document.form1.sd35_i_familia.value = ''; 
  }
}
function js_mostrafamilia1(chave1,chave2){
  document.form1.sd35_i_familia.value = chave1;
  document.form1.sd33_v_descricao.value = chave2;
  db_iframe_familia.hide();
}
function js_pesquisasd35_i_microarea(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_microarea','func_microarea.php?funcao_js=parent.js_mostramicroarea1|sd34_i_codigo|sd34_v_descricao','Pesquisa',true);
  }else{
     if(document.form1.sd35_i_microarea.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_microarea','func_microarea.php?pesquisa_chave='+document.form1.sd35_i_microarea.value+'&funcao_js=parent.js_mostramicroarea','Pesquisa',false);
     }else{
       document.form1.sd34_i_codigo.value = ''; 
     }
  }
}
function js_mostramicroarea(chave,erro){
alert('auiiii');
  document.form1.sd34_v_descricao.value = chave;
  if(erro==true){ 
    document.form1.sd35_i_microarea.focus(); 
    document.form1.sd35_i_microarea.value = ''; 
  }
}
function js_mostramicroarea1(chave1,chave2){
  document.form1.sd35_i_microarea.value = chave1;
  document.form1.sd34_v_descricao.value = chave2;
  db_iframe_microarea.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_familiamicroarea','func_familiamicroarea.php?funcao_js=parent.js_preenchepesquisa|sd35_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_familiamicroarea.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>