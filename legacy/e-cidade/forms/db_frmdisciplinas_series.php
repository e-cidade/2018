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
$cldisciplinas_series->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed03_c_nome");
$clrotulo->label("ed27_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted31_i_codigo?>">
       <?=@$Led31_i_codigo?>
    </td>
    <td> 
<?
db_input('ed31_i_codigo',5,$Ied31_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted31_i_disciplina?>">
       <?
       db_ancora(@$Led31_i_disciplina,"js_pesquisaed31_i_disciplina(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed31_i_disciplina',5,$Ied31_i_disciplina,true,'text',$db_opcao," onchange='js_pesquisaed31_i_disciplina(false);'")
?>
       <?
db_input('ed27_c_nome',50,$Ied27_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted31_i_series?>">
       <?
       db_ancora(@$Led31_i_series,"js_pesquisaed31_i_series(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed31_i_series',5,$Ied31_i_series,true,'text',$db_opcao," onchange='js_pesquisaed31_i_series(false);'")
?>
       <?
db_input('ed03_c_nome',40,$Ied03_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed31_i_series(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_series','func_series.php?funcao_js=parent.js_mostraseries1|ed03_i_codigo|ed03_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed31_i_series.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_series','func_series.php?pesquisa_chave='+document.form1.ed31_i_series.value+'&funcao_js=parent.js_mostraseries','Pesquisa',false);
     }else{
       document.form1.ed03_c_nome.value = ''; 
     }
  }
}
function js_mostraseries(chave,erro){
  document.form1.ed03_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed31_i_series.focus(); 
    document.form1.ed31_i_series.value = ''; 
  }
}
function js_mostraseries1(chave1,chave2){
  document.form1.ed31_i_series.value = chave1;
  document.form1.ed03_c_nome.value = chave2;
  db_iframe_series.hide();
}
function js_pesquisaed31_i_disciplina(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_disciplinas','func_disciplinas.php?funcao_js=parent.js_mostradisciplinas1|ed27_i_codigo|ed27_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed31_i_disciplina.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_disciplinas','func_disciplinas.php?pesquisa_chave='+document.form1.ed31_i_disciplina.value+'&funcao_js=parent.js_mostradisciplinas','Pesquisa',false);
     }else{
       document.form1.ed27_c_nome.value = ''; 
     }
  }
}
function js_mostradisciplinas(chave,erro){
  document.form1.ed27_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed31_i_disciplina.focus(); 
    document.form1.ed31_i_disciplina.value = ''; 
  }
}
function js_mostradisciplinas1(chave1,chave2){
  document.form1.ed31_i_disciplina.value = chave1;
  document.form1.ed27_c_nome.value = chave2;
  db_iframe_disciplinas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_disciplinas_series','func_disciplinas_series.php?funcao_js=parent.js_preenchepesquisa|ed31_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_disciplinas_series.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>