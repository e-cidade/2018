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

//MODULO: material
$clmatestoqueitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m70_codmatmater");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tm71_codlanc?>">
       <?=@$Lm71_codlanc?>
    </td>
    <td> 
<?
db_input('m71_codlanc',10,$Im71_codlanc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm71_codmatestoque?>">
       <?
       db_ancora(@$Lm71_codmatestoque,"js_pesquisam71_codmatestoque(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('m71_codmatestoque',10,$Im71_codmatestoque,true,'text',$db_opcao," onchange='js_pesquisam71_codmatestoque(false);'")
?>
       <?
db_input('m70_codmatmater',10,$Im70_codmatmater,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm71_data?>">
       <?=@$Lm71_data?>
    </td>
    <td> 
<?
db_inputdata('m71_data',@$m71_data_dia,@$m71_data_mes,@$m71_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm71_quant?>">
       <?=@$Lm71_quant?>
    </td>
    <td> 
<?
db_input('m71_quant',15,$Im71_quant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm71_valor?>">
       <?=@$Lm71_valor?>
    </td>
    <td> 
<?
db_input('m71_valor',15,$Im71_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisam71_codmatestoque(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matestoque','func_matestoque.php?funcao_js=parent.js_mostramatestoque1|m70_codigo|m70_codmatmater','Pesquisa',true);
  }else{
     if(document.form1.m71_codmatestoque.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matestoque','func_matestoque.php?pesquisa_chave='+document.form1.m71_codmatestoque.value+'&funcao_js=parent.js_mostramatestoque','Pesquisa',false);
     }else{
       document.form1.m70_codmatmater.value = ''; 
     }
  }
}
function js_mostramatestoque(chave,erro){
  document.form1.m70_codmatmater.value = chave; 
  if(erro==true){ 
    document.form1.m71_codmatestoque.focus(); 
    document.form1.m71_codmatestoque.value = ''; 
  }
}
function js_mostramatestoque1(chave1,chave2){
  document.form1.m71_codmatestoque.value = chave1;
  document.form1.m70_codmatmater.value = chave2;
  db_iframe_matestoque.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueitem','func_matestoqueitem.php?funcao_js=parent.js_preenchepesquisa|m71_codlanc','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_matestoqueitem.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>