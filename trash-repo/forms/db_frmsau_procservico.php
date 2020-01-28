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
$clsau_procservico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd63_c_nome");
$clrotulo->label("sd87_c_nome");
$clrotulo->label("sd86_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd88_i_codigo?>">
       <?=@$Lsd88_i_codigo?>
    </td>
    <td>
<?
db_input('sd88_i_codigo',5,$Isd88_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd88_i_procedimento?>">
       <?
       db_ancora(@$Lsd88_i_procedimento,"js_pesquisasd88_i_procedimento(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd88_i_procedimento',5,$Isd88_i_procedimento,true,'text',$db_opcao," onchange='js_pesquisasd88_i_procedimento(false);'")
?>
       <?
db_input('sd63_c_nome',60,$Isd63_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd88_i_classificacao?>">
       <?
       db_ancora(@$Lsd88_i_classificacao,"js_pesquisasd88_i_classificacao(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd88_i_classificacao',5,$Isd88_i_classificacao,true,'text',$db_opcao," onchange='js_pesquisasd88_i_classificacao(false);'")
?>
       <?
db_input('sd87_c_nome',60,$Isd87_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd88_i_servico?>">
       <?
       db_ancora(@$Lsd88_i_servico,"js_pesquisasd88_i_servico(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd88_i_servico',5,$Isd88_i_servico,true,'text',$db_opcao," onchange='js_pesquisasd88_i_servico(false);'")
?>
       <?
db_input('sd86_c_nome',60,$Isd86_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd88_i_anocomp?>">
       <?=@$Lsd88_i_anocomp?>/<?=@$Lsd88_i_mescomp?>
    </td>
    <td>
<?
db_input('sd88_i_anocomp',4,$Isd88_i_anocomp,true,'text',$db_opcao,""); echo "/";
db_input('sd88_i_mescomp',2,$Isd88_i_mescomp,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd88_i_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?funcao_js=parent.js_mostrasau_procedimento1|sd63_i_codigo|sd63_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd88_i_procedimento.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?pesquisa_chave='+document.form1.sd88_i_procedimento.value+'&funcao_js=parent.js_mostrasau_procedimento','Pesquisa',false);
     }else{
       document.form1.sd63_c_nome.value = '';
     }
  }
}
function js_mostrasau_procedimento(chave,erro){
  document.form1.sd63_c_nome.value = chave;
  if(erro==true){
    document.form1.sd88_i_procedimento.focus();
    document.form1.sd88_i_procedimento.value = '';
  }
}
function js_mostrasau_procedimento1(chave1,chave2){
  document.form1.sd88_i_procedimento.value = chave1;
  document.form1.sd63_c_nome.value = chave2;
  db_iframe_sau_procedimento.hide();
}
function js_pesquisasd88_i_classificacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_servclassificacao','func_sau_servclassificacao.php?funcao_js=parent.js_mostrasau_servclassificacao1|sd87_i_codigo|sd87_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd88_i_classificacao.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_servclassificacao','func_sau_servclassificacao.php?pesquisa_chave='+document.form1.sd88_i_classificacao.value+'&funcao_js=parent.js_mostrasau_servclassificacao','Pesquisa',false);
     }else{
       document.form1.sd87_c_nome.value = '';
     }
  }
}
function js_mostrasau_servclassificacao(chave,erro){
  document.form1.sd87_c_nome.value = chave;
  if(erro==true){
    document.form1.sd88_i_classificacao.focus();
    document.form1.sd88_i_classificacao.value = '';
  }
}
function js_mostrasau_servclassificacao1(chave1,chave2){
  document.form1.sd88_i_classificacao.value = chave1;
  document.form1.sd87_c_nome.value = chave2;
  db_iframe_sau_servclassificacao.hide();
}
function js_pesquisasd88_i_servico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_servico','func_sau_servico.php?funcao_js=parent.js_mostrasau_servico1|sd86_i_codigo|sd86_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd88_i_servico.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_servico','func_sau_servico.php?pesquisa_chave='+document.form1.sd88_i_servico.value+'&funcao_js=parent.js_mostrasau_servico','Pesquisa',false);
     }else{
       document.form1.sd86_c_nome.value = '';
     }
  }
}
function js_mostrasau_servico(chave,erro){
  document.form1.sd86_c_nome.value = chave;
  if(erro==true){
    document.form1.sd88_i_servico.focus();
    document.form1.sd88_i_servico.value = '';
  }
}
function js_mostrasau_servico1(chave1,chave2){
  document.form1.sd88_i_servico.value = chave1;
  document.form1.sd86_c_nome.value = chave2;
  db_iframe_sau_servico.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_procservico','func_sau_procservico.php?funcao_js=parent.js_preenchepesquisa|sd88_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_procservico.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>