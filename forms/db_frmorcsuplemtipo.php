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
$clorcsuplemtipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c53_descr");
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset>
<legend><b>Tipo de Suplementação</legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To48_tiposup?>"> <?=@$Lo48_tiposup?>
    </td>
    <td><? db_input('o48_tiposup',4,$Io48_tiposup,true,'text',$db_opcao,"") ?></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To48_descr?>"><?=@$Lo48_descr?> </td>
    <td><? db_input('o48_descr',60,$Io48_descr,true,'text',$db_opcao,"") ?></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To48_coddocsup?>">
     <?    db_ancora(@$Lo48_coddocsup,"js_pesquisao48_coddocsup(true);",$db_opcao);   ?>
    </td>
    <td> 
       <? db_input('o48_coddocsup',4,$Io48_coddocsup,true,'text',$db_opcao," onchange='js_pesquisao48_coddocsup(false);'")?>
       <? db_input('c53_descr',50,$Ic53_descr,true,'text',3,'')  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To48_coddocred?>"><?=@$Lo48_coddocred?> </td>
    <td>
      <? db_input('o48_coddocred',7,$Io48_coddocred,true,'text',$db_opcao,"")?> 
       * habilita a Aba de Reduções
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To48_arrecadmaior?>">
       <?=@$Lo48_arrecadmaior?>
    </td>
    <td>
     <? db_input('o48_arrecadmaior',7,$Io48_arrecadmaior,true,'text',$db_opcao,"")?>
      *habilita a Aba de Receitas
    
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To48_suplcreditoespecial?>">
       <?=@$Lo48_suplcreditoespecial?>
    </td>
    <td>
     <? db_input('o48_suplcreditoespecial',7,$Io48_suplcreditoespecial,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To48_redcreditoespecial?>">
       <?=@$Lo48_redcreditoespecial?>
    </td>
    <td>
     <? db_input('o48_redcreditoespecial',7,$Io48_redcreditoespecial,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To48_superavit?>"><?=@$Lo48_superavit?></td>
    <td><?  $x = array("f"=>"NAO","t"=>"SIM");
            db_select('o48_superavit',$x,true,$db_opcao,"");
        ?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao48_coddocsup(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conhistdoc','func_conhistdoc.php?funcao_js=parent.js_mostraconhistdoc1|c53_coddoc|c53_descr','Pesquisa',true);
  }else{
     if(document.form1.o48_coddocsup.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conhistdoc','func_conhistdoc.php?pesquisa_chave='+document.form1.o48_coddocsup.value+'&funcao_js=parent.js_mostraconhistdoc','Pesquisa',false);
     }else{
       document.form1.c53_descr.value = ''; 
     }
  }
}
function js_mostraconhistdoc(chave,erro){
  document.form1.c53_descr.value = chave; 
  if(erro==true){ 
    document.form1.o48_coddocsup.focus(); 
    document.form1.o48_coddocsup.value = ''; 
  }
}
function js_mostraconhistdoc1(chave1,chave2){
  document.form1.o48_coddocsup.value = chave1;
  document.form1.c53_descr.value = chave2;
  db_iframe_conhistdoc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcsuplemtipo','func_orcsuplemtipo.php?funcao_js=parent.js_preenchepesquisa|o48_tiposup','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcsuplemtipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>