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

//MODULO: pessoal
$clrhcontasrec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k13_descr");
$clrotulo->label("o15_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh41_conta?>">
       <?
       db_ancora(@$Lrh41_conta,"js_pesquisarh41_conta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh41_conta',5,$Irh41_conta,true,'text',$db_opcao," onchange='js_pesquisarh41_conta(false);'")
?>
       <?
db_input('k13_descr',40,$Ik13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh41_codigo?>">
       <?
       db_ancora(@$Lrh41_codigo,"js_pesquisarh41_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh41_codigo',5,$Irh41_codigo,true,'text',$db_opcao," onchange='js_pesquisarh41_codigo(false);'")
?>
       <?
db_input('o15_descr',40,$Io15_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh41_conta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr','Pesquisa',true);
  }else{
     if(document.form1.rh41_conta.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?pesquisa_chave='+document.form1.rh41_conta.value+'&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
     }else{
       document.form1.k13_descr.value = ''; 
     }
  }
}
function js_mostrasaltes(chave,erro){
  document.form1.k13_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh41_conta.focus(); 
    document.form1.rh41_conta.value = ''; 
  }
}
function js_mostrasaltes1(chave1,chave2){
  document.form1.rh41_conta.value = chave1;
  document.form1.k13_descr.value = chave2;
  db_iframe_saltes.hide();
}
function js_pesquisarh41_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
     if(document.form1.rh41_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.rh41_codigo.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
     }else{
       document.form1.o15_descr.value = ''; 
     }
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh41_codigo.focus(); 
    document.form1.rh41_codigo.value = ''; 
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.rh41_codigo.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhcontasrec','func_rhcontasrec.php?funcao_js=parent.js_preenchepesquisa|rh41_conta|rh41_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_rhcontasrec.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>