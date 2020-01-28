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
$clsau_servclassificacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd86_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd87_i_codigo?>">
       <?=@$Lsd87_i_codigo?>
    </td>
    <td>
<?
db_input('sd87_i_codigo',5,$Isd87_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd87_c_classificacao?>">
       <?=@$Lsd87_c_classificacao?>
    </td>
    <td>
<?
db_input('sd87_c_classificacao',3,$Isd87_c_classificacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd87_c_nome?>">
       <?=@$Lsd87_c_nome?>
    </td>
    <td>
<?
db_input('sd87_c_nome',60,$Isd87_c_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd87_i_servico?>">
       <?
       db_ancora(@$Lsd87_i_servico,"js_pesquisasd87_i_servico(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd87_i_servico',5,$Isd87_i_servico,true,'text',$db_opcao," onchange='js_pesquisasd87_i_servico(false);'")
?>
       <?
db_input('sd86_c_nome',60,$Isd86_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd87_i_anocomp?>">
       <?=@$Lsd87_i_anocomp?>/<?=@$Lsd87_i_mescomp?>
    </td>
    <td>
<?
db_input('sd87_i_anocomp',4,$Isd87_i_anocomp,true,'text',$db_opcao,""); echo "/";
db_input('sd87_i_mescomp',2,$Isd87_i_mescomp,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd87_i_servico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_servico','func_sau_servico.php?funcao_js=parent.js_mostrasau_servico1|sd86_i_codigo|sd86_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd87_i_servico.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_servico','func_sau_servico.php?pesquisa_chave='+document.form1.sd87_i_servico.value+'&funcao_js=parent.js_mostrasau_servico','Pesquisa',false);
     }else{
       document.form1.sd86_c_nome.value = '';
     }
  }
}
function js_mostrasau_servico(chave,erro){
  document.form1.sd86_c_nome.value = chave;
  if(erro==true){
    document.form1.sd87_i_servico.focus();
    document.form1.sd87_i_servico.value = '';
  }
}
function js_mostrasau_servico1(chave1,chave2){
  document.form1.sd87_i_servico.value = chave1;
  document.form1.sd86_c_nome.value = chave2;
  db_iframe_sau_servico.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_servclassificacao','func_sau_servclassificacao.php?funcao_js=parent.js_preenchepesquisa|sd87_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_servclassificacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>