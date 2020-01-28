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

//MODULO: atendimento
$cltarefalogsituacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at43_descr");
$clrotulo->label("at46_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat48_sequencial?>">
       <?=@$Lat48_sequencial?>
    </td>
    <td> 
<?
db_input('at48_sequencial',10,$Iat48_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat48_tarefalog?>">
       <?
       db_ancora(@$Lat48_tarefalog,"js_pesquisaat48_tarefalog(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at48_tarefalog',10,$Iat48_tarefalog,true,'text',$db_opcao," onchange='js_pesquisaat48_tarefalog(false);'")
?>
       <?
db_input('at43_descr',1,$Iat43_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat48_situacao?>">
       <?
       db_ancora(@$Lat48_situacao,"js_pesquisaat48_situacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at48_situacao',10,$Iat48_situacao,true,'text',$db_opcao," onchange='js_pesquisaat48_situacao(false);'")
?>
       <?
db_input('at46_descr',40,$Iat46_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaat48_tarefalog(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tarefalog','func_tarefalog.php?funcao_js=parent.js_mostratarefalog1|at43_sequencial|at43_descr','Pesquisa',true);
  }else{
     if(document.form1.at48_tarefalog.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tarefalog','func_tarefalog.php?pesquisa_chave='+document.form1.at48_tarefalog.value+'&funcao_js=parent.js_mostratarefalog','Pesquisa',false);
     }else{
       document.form1.at43_descr.value = ''; 
     }
  }
}
function js_mostratarefalog(chave,erro){
  document.form1.at43_descr.value = chave; 
  if(erro==true){ 
    document.form1.at48_tarefalog.focus(); 
    document.form1.at48_tarefalog.value = ''; 
  }
}
function js_mostratarefalog1(chave1,chave2){
  document.form1.at48_tarefalog.value = chave1;
  document.form1.at43_descr.value = chave2;
  db_iframe_tarefalog.hide();
}
function js_pesquisaat48_situacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tarefacadsituacao','func_tarefacadsituacao.php?funcao_js=parent.js_mostratarefacadsituacao1|at46_codigo|at46_descr','Pesquisa',true);
  }else{
     if(document.form1.at48_situacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tarefacadsituacao','func_tarefacadsituacao.php?pesquisa_chave='+document.form1.at48_situacao.value+'&funcao_js=parent.js_mostratarefacadsituacao','Pesquisa',false);
     }else{
       document.form1.at46_descr.value = ''; 
     }
  }
}
function js_mostratarefacadsituacao(chave,erro){
  document.form1.at46_descr.value = chave; 
  if(erro==true){ 
    document.form1.at48_situacao.focus(); 
    document.form1.at48_situacao.value = ''; 
  }
}
function js_mostratarefacadsituacao1(chave1,chave2){
  document.form1.at48_situacao.value = chave1;
  document.form1.at46_descr.value = chave2;
  db_iframe_tarefacadsituacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tarefalogsituacao','func_tarefalogsituacao.php?funcao_js=parent.js_preenchepesquisa|at48_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tarefalogsituacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>