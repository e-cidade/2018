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

//MODULO: configuracoes
$cldb_ordematend->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descricao");
$clrotulo->label("at05_solicitado");
$clrotulo->label("at05_solicitado");
$clrotulo->label("at05_solicitado");
$clrotulo->label("at05_solicitado");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tor10_codordem?>">
       <?
       db_ancora(@$Lor10_codordem,"js_pesquisaor10_codordem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('or10_codordem',10,$Ior10_codordem,true,'text',$db_opcao," onchange='js_pesquisaor10_codordem(false);'")
?>
       <?
db_input('descricao',40,$Idescricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tor10_codatend?>">
       <?
       db_ancora(@$Lor10_codatend,"js_pesquisaor10_codatend(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('or10_codatend',6,$Ior10_codatend,true,'text',$db_opcao," onchange='js_pesquisaor10_codatend(false);'")
?>
       <?
db_input('at05_solicitado',1,$Iat05_solicitado,true,'text',3,'');
db_input('at05_solicitado',1,$Iat05_solicitado,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tor10_seq?>">
       <?
       db_ancora(@$Lor10_seq,"js_pesquisaor10_seq(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('or10_seq',4,$Ior10_seq,true,'text',$db_opcao," onchange='js_pesquisaor10_seq(false);'")
?>
       <?
db_input('at05_solicitado',1,$Iat05_solicitado,true,'text',3,'');
db_input('at05_solicitado',1,$Iat05_solicitado,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaor10_codordem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_ordem','func_db_ordem.php?funcao_js=parent.js_mostradb_ordem1|codordem|descricao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_db_ordem','func_db_ordem.php?pesquisa_chave='+document.form1.or10_codordem.value+'&funcao_js=parent.js_mostradb_ordem','Pesquisa',false);
  }
}
function js_mostradb_ordem(chave,erro){
  document.form1.descricao.value = chave; 
  if(erro==true){ 
    document.form1.or10_codordem.focus(); 
    document.form1.or10_codordem.value = ''; 
  }
}
function js_mostradb_ordem1(chave1,chave2){
  document.form1.or10_codordem.value = chave1;
  document.form1.descricao.value = chave2;
  db_iframe_db_ordem.hide();
}
function js_pesquisaor10_codatend(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditem.php?funcao_js=parent.js_mostraatenditem1|at05_codatend|at05_solicitado','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditem.php?pesquisa_chave='+document.form1.or10_codatend.value+'&funcao_js=parent.js_mostraatenditem','Pesquisa',false);
  }
}
function js_mostraatenditem(chave,erro){
  document.form1.at05_solicitado.value = chave; 
  if(erro==true){ 
    document.form1.or10_codatend.focus(); 
    document.form1.or10_codatend.value = ''; 
  }
}
function js_mostraatenditem1(chave1,chave2){
  document.form1.or10_codatend.value = chave1;
  document.form1.at05_solicitado.value = chave2;
  db_iframe_atenditem.hide();
}
function js_pesquisaor10_codatend(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditem.php?funcao_js=parent.js_mostraatenditem1|at05_seq|at05_solicitado','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditem.php?pesquisa_chave='+document.form1.or10_codatend.value+'&funcao_js=parent.js_mostraatenditem','Pesquisa',false);
  }
}
function js_mostraatenditem(chave,erro){
  document.form1.at05_solicitado.value = chave; 
  if(erro==true){ 
    document.form1.or10_codatend.focus(); 
    document.form1.or10_codatend.value = ''; 
  }
}
function js_mostraatenditem1(chave1,chave2){
  document.form1.or10_codatend.value = chave1;
  document.form1.at05_solicitado.value = chave2;
  db_iframe_atenditem.hide();
}
function js_pesquisaor10_seq(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditem.php?funcao_js=parent.js_mostraatenditem1|at05_codatend|at05_solicitado','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditem.php?pesquisa_chave='+document.form1.or10_seq.value+'&funcao_js=parent.js_mostraatenditem','Pesquisa',false);
  }
}
function js_mostraatenditem(chave,erro){
  document.form1.at05_solicitado.value = chave; 
  if(erro==true){ 
    document.form1.or10_seq.focus(); 
    document.form1.or10_seq.value = ''; 
  }
}
function js_mostraatenditem1(chave1,chave2){
  document.form1.or10_seq.value = chave1;
  document.form1.at05_solicitado.value = chave2;
  db_iframe_atenditem.hide();
}
function js_pesquisaor10_seq(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditem.php?funcao_js=parent.js_mostraatenditem1|at05_seq|at05_solicitado','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditem.php?pesquisa_chave='+document.form1.or10_seq.value+'&funcao_js=parent.js_mostraatenditem','Pesquisa',false);
  }
}
function js_mostraatenditem(chave,erro){
  document.form1.at05_solicitado.value = chave; 
  if(erro==true){ 
    document.form1.or10_seq.focus(); 
    document.form1.or10_seq.value = ''; 
  }
}
function js_mostraatenditem1(chave1,chave2){
  document.form1.or10_seq.value = chave1;
  document.form1.at05_solicitado.value = chave2;
  db_iframe_atenditem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_ordematend','func_db_ordematend.php?funcao_js=parent.js_preenchepesquisa|or10_codordem|1|2','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_db_ordematend.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>