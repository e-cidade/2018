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
$clrhbasesr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh32_descr");
$clrotulo->label("rh27_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh33_base?>">
       <?
       db_ancora(@$Lrh33_base,"js_pesquisarh33_base(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh33_base',4,$Irh33_base,true,'text',3," onchange='js_pesquisarh33_base(false);'")
?>
       <?
db_input('rh32_descr',30,$Irh32_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh33_rubric?>">
       <?
       db_ancora(@$Lrh33_rubric,"js_pesquisarh33_rubric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh33_rubric',4,$Irh33_rubric,true,'text',3," onchange='js_pesquisarh33_rubric(false);'")
?>
       <?
db_input('rh27_descr',30,$Irh27_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh33_base(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhbases','func_rhbases.php?funcao_js=parent.js_mostrarhbases1|rh32_base|rh32_descr','Pesquisa',true);
  }else{
     if(document.form1.rh33_base.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhbases','func_rhbases.php?pesquisa_chave='+document.form1.rh33_base.value+'&funcao_js=parent.js_mostrarhbases','Pesquisa',false);
     }else{
       document.form1.rh32_descr.value = ''; 
     }
  }
}
function js_mostrarhbases(chave,erro){
  document.form1.rh32_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh33_base.focus(); 
    document.form1.rh33_base.value = ''; 
  }
}
function js_mostrarhbases1(chave1,chave2){
  document.form1.rh33_base.value = chave1;
  document.form1.rh32_descr.value = chave2;
  db_iframe_rhbases.hide();
}
function js_pesquisarh33_rubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarhrubricas1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
     if(document.form1.rh33_rubric.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.rh33_rubric.value+'&funcao_js=parent.js_mostrarhrubricas','Pesquisa',false);
     }else{
       document.form1.rh27_descr.value = ''; 
     }
  }
}
function js_mostrarhrubricas(chave,erro){
  document.form1.rh27_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh33_rubric.focus(); 
    document.form1.rh33_rubric.value = ''; 
  }
}
function js_mostrarhrubricas1(chave1,chave2){
  document.form1.rh33_rubric.value = chave1;
  document.form1.rh27_descr.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhbasesr','func_rhbasesr.php?funcao_js=parent.js_preenchepesquisa|rh33_base|rh33_rubric','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_rhbasesr.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>