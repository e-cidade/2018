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
$cltarefacadsituacaousu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at46_descr");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat17_sequencial?>">
       <?=@$Lat17_sequencial?>
    </td>
    <td> 
<?
db_input('at17_sequencial',10,$Iat17_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat17_usuario?>">
       <?
       db_ancora(@$Lat17_usuario,"js_pesquisaat17_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at17_usuario',10,$Iat17_usuario,true,'text',$db_opcao," onchange='js_pesquisaat17_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat17_tarefacadsituacao?>">
       <?
       db_ancora(@$Lat17_tarefacadsituacao,"js_pesquisaat17_tarefacadsituacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at17_tarefacadsituacao',10,$Iat17_tarefacadsituacao,true,'text',$db_opcao," onchange='js_pesquisaat17_tarefacadsituacao(false);'")
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
function js_pesquisaat17_tarefacadsituacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tarefacadsituacao','func_tarefacadsituacao.php?funcao_js=parent.js_mostratarefacadsituacao1|at46_codigo|at46_descr','Pesquisa',true);
  }else{
     if(document.form1.at17_tarefacadsituacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tarefacadsituacao','func_tarefacadsituacao.php?pesquisa_chave='+document.form1.at17_tarefacadsituacao.value+'&funcao_js=parent.js_mostratarefacadsituacao','Pesquisa',false);
     }else{
       document.form1.at46_descr.value = ''; 
     }
  }
}
function js_mostratarefacadsituacao(chave,erro){
  document.form1.at46_descr.value = chave; 
  if(erro==true){ 
    document.form1.at17_tarefacadsituacao.focus(); 
    document.form1.at17_tarefacadsituacao.value = ''; 
  }
}
function js_mostratarefacadsituacao1(chave1,chave2){
  document.form1.at17_tarefacadsituacao.value = chave1;
  document.form1.at46_descr.value = chave2;
  db_iframe_tarefacadsituacao.hide();
}
function js_pesquisaat17_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.at17_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.at17_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.at17_usuario.focus(); 
    document.form1.at17_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.at17_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tarefacadsituacaousu','func_tarefacadsituacaousu.php?funcao_js=parent.js_preenchepesquisa|at17_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tarefacadsituacaousu.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>