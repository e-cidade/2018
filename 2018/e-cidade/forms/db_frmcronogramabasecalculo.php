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

//MODULO: orcamento
$clcronogramabasecalculo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o124_descricao");
$clrotulo->label("o125_cronogramaperspectiva");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To125_sequencial?>">
       <?=@$Lo125_sequencial?>
    </td>
    <td> 
<?
db_input('o125_sequencial',10,$Io125_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To125_cronogramaperspectiva?>">
       <?
       db_ancora(@$Lo125_cronogramaperspectiva,"js_pesquisao125_cronogramaperspectiva(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o125_cronogramaperspectiva',10,$Io125_cronogramaperspectiva,true,'text',$db_opcao," onchange='js_pesquisao125_cronogramaperspectiva(false);'")
?>
       <?
db_input('o124_descricao',100,$Io124_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To125_mes?>">
       <?=@$Lo125_mes?>
    </td>
    <td> 
<?
db_input('o125_mes',10,$Io125_mes,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To125_ano?>">
       <?=@$Lo125_ano?>
    </td>
    <td> 
<?
db_input('o125_ano',10,$Io125_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To125_percentual?>">
       <?=@$Lo125_percentual?>
    </td>
    <td> 
<?
db_input('o125_percentual',10,$Io125_percentual,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To125_valor?>">
       <?=@$Lo125_valor?>
    </td>
    <td> 
<?
db_input('o125_valor',10,$Io125_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao125_cronogramaperspectiva(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cronogramaperspectiva','func_cronogramaperspectiva.php?funcao_js=parent.js_mostracronogramaperspectiva1|o124_sequencial|o124_descricao','Pesquisa',true);
  }else{
     if(document.form1.o125_cronogramaperspectiva.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cronogramaperspectiva','func_cronogramaperspectiva.php?pesquisa_chave='+document.form1.o125_cronogramaperspectiva.value+'&funcao_js=parent.js_mostracronogramaperspectiva','Pesquisa',false);
     }else{
       document.form1.o124_descricao.value = ''; 
     }
  }
}
function js_mostracronogramaperspectiva(chave,erro){
  document.form1.o124_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o125_cronogramaperspectiva.focus(); 
    document.form1.o125_cronogramaperspectiva.value = ''; 
  }
}
function js_mostracronogramaperspectiva1(chave1,chave2){
  document.form1.o125_cronogramaperspectiva.value = chave1;
  document.form1.o124_descricao.value = chave2;
  db_iframe_cronogramaperspectiva.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cronogramabasecalculo','func_cronogramabasecalculo.php?funcao_js=parent.js_preenchepesquisa|o125_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cronogramabasecalculo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>