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
$clhistmpdaprov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed61_i_codigo");
$clrotulo->label("ed12_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted70_i_codigo?>">
       <?=@$Led70_i_codigo?>
    </td>
    <td> 
<?
db_input('ed70_i_codigo',10,$Ied70_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted70_i_historico?>">
       <?
       db_ancora(@$Led70_i_historico,"js_pesquisaed70_i_historico(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed70_i_historico',10,$Ied70_i_historico,true,'text',$db_opcao," onchange='js_pesquisaed70_i_historico(false);'")
?>
       <?
db_input('ed61_i_codigo',10,$Ied61_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted70_i_disciplina?>">
       <?
       db_ancora(@$Led70_i_disciplina,"js_pesquisaed70_i_disciplina(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed70_i_disciplina',10,$Ied70_i_disciplina,true,'text',$db_opcao," onchange='js_pesquisaed70_i_disciplina(false);'")
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
function js_pesquisaed70_i_historico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_historico','func_historico.php?funcao_js=parent.js_mostrahistorico1|ed61_i_codigo|ed61_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed70_i_historico.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_historico','func_historico.php?pesquisa_chave='+document.form1.ed70_i_historico.value+'&funcao_js=parent.js_mostrahistorico','Pesquisa',false);
     }else{
       document.form1.ed61_i_codigo.value = ''; 
     }
  }
}
function js_mostrahistorico(chave,erro){
  document.form1.ed61_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed70_i_historico.focus(); 
    document.form1.ed70_i_historico.value = ''; 
  }
}
function js_mostrahistorico1(chave1,chave2){
  document.form1.ed70_i_historico.value = chave1;
  document.form1.ed61_i_codigo.value = chave2;
  db_iframe_historico.hide();
}
function js_pesquisaed70_i_disciplina(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_disciplina','func_disciplina.php?funcao_js=parent.js_mostradisciplina1|ed12_i_codigo|ed12_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed70_i_disciplina.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_disciplina','func_disciplina.php?pesquisa_chave='+document.form1.ed70_i_disciplina.value+'&funcao_js=parent.js_mostradisciplina','Pesquisa',false);
     }else{
       document.form1.ed12_i_codigo.value = ''; 
     }
  }
}
function js_mostradisciplina(chave,erro){
  document.form1.ed12_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed70_i_disciplina.focus(); 
    document.form1.ed70_i_disciplina.value = ''; 
  }
}
function js_mostradisciplina1(chave1,chave2){
  document.form1.ed70_i_disciplina.value = chave1;
  document.form1.ed12_i_codigo.value = chave2;
  db_iframe_disciplina.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_histmpdaprov','func_histmpdaprov.php?funcao_js=parent.js_preenchepesquisa|ed70_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_histmpdaprov.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>