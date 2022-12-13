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

//MODULO: contabilidade
$clconlancamdoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c70_anousu");
$clrotulo->label("c53_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc71_codlan?>">
       <?
       db_ancora(@$Lc71_codlan,"js_pesquisac71_codlan(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c71_codlan',8,$Ic71_codlan,true,'text',$db_opcao," onchange='js_pesquisac71_codlan(false);'")
?>
       <?
db_input('c70_anousu',4,$Ic70_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc71_coddoc?>">
       <?
       db_ancora(@$Lc71_coddoc,"js_pesquisac71_coddoc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c71_coddoc',4,$Ic71_coddoc,true,'text',$db_opcao," onchange='js_pesquisac71_coddoc(false);'")
?>
       <?
db_input('c53_descr',50,$Ic53_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac71_codlan(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancam','func_conlancam.php?funcao_js=parent.js_mostraconlancam1|c70_codlan|c70_anousu','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancam','func_conlancam.php?pesquisa_chave='+document.form1.c71_codlan.value+'&funcao_js=parent.js_mostraconlancam','Pesquisa',false);
  }
}
function js_mostraconlancam(chave,erro){
  document.form1.c70_anousu.value = chave; 
  if(erro==true){ 
    document.form1.c71_codlan.focus(); 
    document.form1.c71_codlan.value = ''; 
  }
}
function js_mostraconlancam1(chave1,chave2){
  document.form1.c71_codlan.value = chave1;
  document.form1.c70_anousu.value = chave2;
  db_iframe_conlancam.hide();
}
function js_pesquisac71_coddoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conhistdoc','func_conhistdoc.php?funcao_js=parent.js_mostraconhistdoc1|c53_coddoc|c53_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_conhistdoc','func_conhistdoc.php?pesquisa_chave='+document.form1.c71_coddoc.value+'&funcao_js=parent.js_mostraconhistdoc','Pesquisa',false);
  }
}
function js_mostraconhistdoc(chave,erro){
  document.form1.c53_descr.value = chave; 
  if(erro==true){ 
    document.form1.c71_coddoc.focus(); 
    document.form1.c71_coddoc.value = ''; 
  }
}
function js_mostraconhistdoc1(chave1,chave2){
  document.form1.c71_coddoc.value = chave1;
  document.form1.c53_descr.value = chave2;
  db_iframe_conhistdoc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conlancamdoc','func_conlancamdoc.php?funcao_js=parent.js_preenchepesquisa|c71_codlan','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conlancamdoc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>