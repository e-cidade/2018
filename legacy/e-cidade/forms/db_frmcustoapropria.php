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

//MODULO: Custos
$clcustoapropria->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cc08_instit");
$clrotulo->label("m82_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcc12_sequencial?>">
       <?=@$Lcc12_sequencial?>
    </td>
    <td> 
<?
db_input('cc12_sequencial',10,$Icc12_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc12_custocriteriorateio?>">
       <?
       db_ancora(@$Lcc12_custocriteriorateio,"js_pesquisacc12_custocriteriorateio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc12_custocriteriorateio',10,$Icc12_custocriteriorateio,true,'text',$db_opcao," onchange='js_pesquisacc12_custocriteriorateio(false);'")
?>
       <?
db_input('cc08_instit',10,$Icc08_instit,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc12_matestoqueinimei?>">
       <?
       db_ancora(@$Lcc12_matestoqueinimei,"js_pesquisacc12_matestoqueinimei(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc12_matestoqueinimei',10,$Icc12_matestoqueinimei,true,'text',$db_opcao," onchange='js_pesquisacc12_matestoqueinimei(false);'")
?>
       <?
db_input('m82_codigo',10,$Im82_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc12_qtd?>">
       <?=@$Lcc12_qtd?>
    </td>
    <td> 
<?
db_input('cc12_qtd',20,$Icc12_qtd,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc12_valor?>">
       <?=@$Lcc12_valor?>
    </td>
    <td> 
<?
db_input('cc12_valor',20,$Icc12_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacc12_custocriteriorateio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_custocriteriorateio','func_custocriteriorateio.php?funcao_js=parent.js_mostracustocriteriorateio1|cc08_sequencial|cc08_instit','Pesquisa',true);
  }else{
     if(document.form1.cc12_custocriteriorateio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_custocriteriorateio','func_custocriteriorateio.php?pesquisa_chave='+document.form1.cc12_custocriteriorateio.value+'&funcao_js=parent.js_mostracustocriteriorateio','Pesquisa',false);
     }else{
       document.form1.cc08_instit.value = ''; 
     }
  }
}
function js_mostracustocriteriorateio(chave,erro){
  document.form1.cc08_instit.value = chave; 
  if(erro==true){ 
    document.form1.cc12_custocriteriorateio.focus(); 
    document.form1.cc12_custocriteriorateio.value = ''; 
  }
}
function js_mostracustocriteriorateio1(chave1,chave2){
  document.form1.cc12_custocriteriorateio.value = chave1;
  document.form1.cc08_instit.value = chave2;
  db_iframe_custocriteriorateio.hide();
}
function js_pesquisacc12_matestoqueinimei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueinimei','func_matestoqueinimei.php?funcao_js=parent.js_mostramatestoqueinimei1|m82_codigo|m82_codigo','Pesquisa',true);
  }else{
     if(document.form1.cc12_matestoqueinimei.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueinimei','func_matestoqueinimei.php?pesquisa_chave='+document.form1.cc12_matestoqueinimei.value+'&funcao_js=parent.js_mostramatestoqueinimei','Pesquisa',false);
     }else{
       document.form1.m82_codigo.value = ''; 
     }
  }
}
function js_mostramatestoqueinimei(chave,erro){
  document.form1.m82_codigo.value = chave; 
  if(erro==true){ 
    document.form1.cc12_matestoqueinimei.focus(); 
    document.form1.cc12_matestoqueinimei.value = ''; 
  }
}
function js_mostramatestoqueinimei1(chave1,chave2){
  document.form1.cc12_matestoqueinimei.value = chave1;
  document.form1.m82_codigo.value = chave2;
  db_iframe_matestoqueinimei.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_custoapropria','func_custoapropria.php?funcao_js=parent.js_preenchepesquisa|cc12_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_custoapropria.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>