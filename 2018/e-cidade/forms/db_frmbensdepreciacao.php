<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: patrimonio
$clbensdepreciacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t46_descricao");
$clrotulo->label("t45_descricao");
$clrotulo->label("t52_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tt44_sequencial?>">
       <?=@$Lt44_sequencial?>
    </td>
    <td> 
<?
db_input('t44_sequencial',10,$It44_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt44_bens?>">
       <?
       db_ancora(@$Lt44_bens,"js_pesquisat44_bens(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('t44_bens',10,$It44_bens,true,'text',$db_opcao," onchange='js_pesquisat44_bens(false);'")
?>
       <?
db_input('t52_descr',100,$It52_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt44_benstipoaquisicao?>">
       <?
       db_ancora(@$Lt44_benstipoaquisicao,"js_pesquisat44_benstipoaquisicao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('t44_benstipoaquisicao',10,$It44_benstipoaquisicao,true,'text',$db_opcao," onchange='js_pesquisat44_benstipoaquisicao(false);'")
?>
       <?
db_input('t45_descricao',150,$It45_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt44_benstipodepreciacao?>">
       <?
       db_ancora(@$Lt44_benstipodepreciacao,"js_pesquisat44_benstipodepreciacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('t44_benstipodepreciacao',10,$It44_benstipodepreciacao,true,'text',$db_opcao," onchange='js_pesquisat44_benstipodepreciacao(false);'")
?>
       <?
db_input('t46_descricao',150,$It46_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt44_vidautil?>">
       <?=@$Lt44_vidautil?>
    </td>
    <td> 
<?
db_input('t44_vidautil',10,$It44_vidautil,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt44_valoratual?>">
       <?=@$Lt44_valoratual?>
    </td>
    <td> 
<?
db_input('t44_valoratual',10,$It44_valoratual,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt44_valorresidual?>">
       <?=@$Lt44_valorresidual?>
    </td>
    <td> 
<?
db_input('t44_valorresidual',10,$It44_valorresidual,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt44_ultimaavaliacao?>">
       <?=@$Lt44_ultimaavaliacao?>
    </td>
    <td> 
<?
db_inputdata('t44_ultimaavaliacao',@$t44_ultimaavaliacao_dia,@$t44_ultimaavaliacao_mes,@$t44_ultimaavaliacao_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisat44_benstipodepreciacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_benstipodepreciacao','func_benstipodepreciacao.php?funcao_js=parent.js_mostrabenstipodepreciacao1|t46_sequencial|t46_descricao','Pesquisa',true);
  }else{
     if(document.form1.t44_benstipodepreciacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_benstipodepreciacao','func_benstipodepreciacao.php?pesquisa_chave='+document.form1.t44_benstipodepreciacao.value+'&funcao_js=parent.js_mostrabenstipodepreciacao','Pesquisa',false);
     }else{
       document.form1.t46_descricao.value = ''; 
     }
  }
}
function js_mostrabenstipodepreciacao(chave,erro){
  document.form1.t46_descricao.value = chave; 
  if(erro==true){ 
    document.form1.t44_benstipodepreciacao.focus(); 
    document.form1.t44_benstipodepreciacao.value = ''; 
  }
}
function js_mostrabenstipodepreciacao1(chave1,chave2){
  document.form1.t44_benstipodepreciacao.value = chave1;
  document.form1.t46_descricao.value = chave2;
  db_iframe_benstipodepreciacao.hide();
}
function js_pesquisat44_benstipoaquisicao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_benstipoaquisicao','func_benstipoaquisicao.php?funcao_js=parent.js_mostrabenstipoaquisicao1|t45_sequencial|t45_descricao','Pesquisa',true);
  }else{
     if(document.form1.t44_benstipoaquisicao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_benstipoaquisicao','func_benstipoaquisicao.php?pesquisa_chave='+document.form1.t44_benstipoaquisicao.value+'&funcao_js=parent.js_mostrabenstipoaquisicao','Pesquisa',false);
     }else{
       document.form1.t45_descricao.value = ''; 
     }
  }
}
function js_mostrabenstipoaquisicao(chave,erro){
  document.form1.t45_descricao.value = chave; 
  if(erro==true){ 
    document.form1.t44_benstipoaquisicao.focus(); 
    document.form1.t44_benstipoaquisicao.value = ''; 
  }
}
function js_mostrabenstipoaquisicao1(chave1,chave2){
  document.form1.t44_benstipoaquisicao.value = chave1;
  document.form1.t45_descricao.value = chave2;
  db_iframe_benstipoaquisicao.hide();
}
function js_pesquisat44_bens(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?funcao_js=parent.js_mostrabens1|t52_bem|t52_descr','Pesquisa',true);
  }else{
     if(document.form1.t44_bens.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?pesquisa_chave='+document.form1.t44_bens.value+'&funcao_js=parent.js_mostrabens','Pesquisa',false);
     }else{
       document.form1.t52_descr.value = ''; 
     }
  }
}
function js_mostrabens(chave,erro){
  document.form1.t52_descr.value = chave; 
  if(erro==true){ 
    document.form1.t44_bens.focus(); 
    document.form1.t44_bens.value = ''; 
  }
}
function js_mostrabens1(chave1,chave2){
  document.form1.t44_bens.value = chave1;
  document.form1.t52_descr.value = chave2;
  db_iframe_bens.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bensdepreciacao','func_bensdepreciacao.php?funcao_js=parent.js_preenchepesquisa|t44_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bensdepreciacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>