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
$cldiario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed52_i_codigo");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed12_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted95_i_codigo?>">
       <?=@$Led95_i_codigo?>
    </td>
    <td> 
<?
db_input('ed95_i_codigo',10,$Ied95_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted95_i_escola?>">
       <?
       db_ancora(@$Led95_i_escola,"js_pesquisaed95_i_escola(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed95_i_escola',10,$Ied95_i_escola,true,'text',$db_opcao," onchange='js_pesquisaed95_i_escola(false);'")
?>
       <?
db_input('ed18_i_codigo',10,$Ied18_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted95_i_calendario?>">
       <?
       db_ancora(@$Led95_i_calendario,"js_pesquisaed95_i_calendario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed95_i_calendario',10,$Ied95_i_calendario,true,'text',$db_opcao," onchange='js_pesquisaed95_i_calendario(false);'")
?>
       <?
db_input('ed52_i_codigo',10,$Ied52_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted95_i_aluno?>">
       <?
       db_ancora(@$Led95_i_aluno,"js_pesquisaed95_i_aluno(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed95_i_aluno',10,$Ied95_i_aluno,true,'text',$db_opcao," onchange='js_pesquisaed95_i_aluno(false);'")
?>
       <?
db_input('ed47_i_codigo',10,$Ied47_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted95_i_serie?>">
       <?
       db_ancora(@$Led95_i_serie,"js_pesquisaed95_i_serie(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed95_i_serie',10,$Ied95_i_serie,true,'text',$db_opcao," onchange='js_pesquisaed95_i_serie(false);'")
?>
       <?
db_input('ed11_i_codigo',10,$Ied11_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted95_i_disciplina?>">
       <?
       db_ancora(@$Led95_i_disciplina,"js_pesquisaed95_i_disciplina(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed95_i_disciplina',10,$Ied95_i_disciplina,true,'text',$db_opcao," onchange='js_pesquisaed95_i_disciplina(false);'")
?>
       <?
db_input('ed12_i_codigo',10,$Ied12_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed95_i_escola(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_escola','func_escola.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed95_i_escola.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_escola','func_escola.php?pesquisa_chave='+document.form1.ed95_i_escola.value+'&funcao_js=parent.js_mostraescola','Pesquisa',false);
     }else{
       document.form1.ed18_i_codigo.value = ''; 
     }
  }
}
function js_mostraescola(chave,erro){
  document.form1.ed18_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed95_i_escola.focus(); 
    document.form1.ed95_i_escola.value = ''; 
  }
}
function js_mostraescola1(chave1,chave2){
  document.form1.ed95_i_escola.value = chave1;
  document.form1.ed18_i_codigo.value = chave2;
  db_iframe_escola.hide();
}
function js_pesquisaed95_i_calendario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_calendario','func_calendario.php?funcao_js=parent.js_mostracalendario1|ed52_i_codigo|ed52_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed95_i_calendario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_calendario','func_calendario.php?pesquisa_chave='+document.form1.ed95_i_calendario.value+'&funcao_js=parent.js_mostracalendario','Pesquisa',false);
     }else{
       document.form1.ed52_i_codigo.value = ''; 
     }
  }
}
function js_mostracalendario(chave,erro){
  document.form1.ed52_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed95_i_calendario.focus(); 
    document.form1.ed95_i_calendario.value = ''; 
  }
}
function js_mostracalendario1(chave1,chave2){
  document.form1.ed95_i_calendario.value = chave1;
  document.form1.ed52_i_codigo.value = chave2;
  db_iframe_calendario.hide();
}
function js_pesquisaed95_i_aluno(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_aluno.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed95_i_aluno.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_aluno.php?pesquisa_chave='+document.form1.ed95_i_aluno.value+'&funcao_js=parent.js_mostraaluno','Pesquisa',false);
     }else{
       document.form1.ed47_i_codigo.value = ''; 
     }
  }
}
function js_mostraaluno(chave,erro){
  document.form1.ed47_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed95_i_aluno.focus(); 
    document.form1.ed95_i_aluno.value = ''; 
  }
}
function js_mostraaluno1(chave1,chave2){
  document.form1.ed95_i_aluno.value = chave1;
  document.form1.ed47_i_codigo.value = chave2;
  db_iframe_aluno.hide();
}
function js_pesquisaed95_i_serie(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_serie','func_serie.php?funcao_js=parent.js_mostraserie1|ed11_i_codigo|ed11_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed95_i_serie.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_serie','func_serie.php?pesquisa_chave='+document.form1.ed95_i_serie.value+'&funcao_js=parent.js_mostraserie','Pesquisa',false);
     }else{
       document.form1.ed11_i_codigo.value = ''; 
     }
  }
}
function js_mostraserie(chave,erro){
  document.form1.ed11_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed95_i_serie.focus(); 
    document.form1.ed95_i_serie.value = ''; 
  }
}
function js_mostraserie1(chave1,chave2){
  document.form1.ed95_i_serie.value = chave1;
  document.form1.ed11_i_codigo.value = chave2;
  db_iframe_serie.hide();
}
function js_pesquisaed95_i_disciplina(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_disciplina','func_disciplina.php?funcao_js=parent.js_mostradisciplina1|ed12_i_codigo|ed12_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed95_i_disciplina.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_disciplina','func_disciplina.php?pesquisa_chave='+document.form1.ed95_i_disciplina.value+'&funcao_js=parent.js_mostradisciplina','Pesquisa',false);
     }else{
       document.form1.ed12_i_codigo.value = ''; 
     }
  }
}
function js_mostradisciplina(chave,erro){
  document.form1.ed12_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed95_i_disciplina.focus(); 
    document.form1.ed95_i_disciplina.value = ''; 
  }
}
function js_mostradisciplina1(chave1,chave2){
  document.form1.ed95_i_disciplina.value = chave1;
  document.form1.ed12_i_codigo.value = chave2;
  db_iframe_disciplina.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_diario','func_diario.php?funcao_js=parent.js_preenchepesquisa|ed95_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_diario.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>