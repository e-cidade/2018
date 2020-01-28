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

//MODULO: itbi
$clitburbano->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it01_guia");
$clrotulo->label("it07_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tit05_guia?>">
       <?
       db_ancora(@$Lit05_guia,"js_pesquisait05_guia(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('it05_guia',10,$Iit05_guia,true,'text',$db_opcao," onchange='js_pesquisait05_guia(false);'")
?>
       <?
db_input('it01_guia',10,$Iit01_guia,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit05_frente?>">
       <?=@$Lit05_frente?>
    </td>
    <td> 
<?
db_input('it05_frente',51,$Iit05_frente,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit05_fundos?>">
       <?=@$Lit05_fundos?>
    </td>
    <td> 
<?
db_input('it05_fundos',15,$Iit05_fundos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit05_direito?>">
       <?=@$Lit05_direito?>
    </td>
    <td> 
<?
db_input('it05_direito',15,$Iit05_direito,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit05_esquerdo?>">
       <?=@$Lit05_esquerdo?>
    </td>
    <td> 
<?
db_input('it05_esquerdo',15,$Iit05_esquerdo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit05_itbisituacao?>">
       <?
       db_ancora(@$Lit05_itbisituacao,"js_pesquisait05_itbisituacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('it05_itbisituacao',10,$Iit05_itbisituacao,true,'text',$db_opcao," onchange='js_pesquisait05_itbisituacao(false);'")
?>
       <?
db_input('it07_descr',40,$Iit07_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisait05_guia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbi.php?funcao_js=parent.js_mostraitbi1|it01_guia|it01_guia','Pesquisa',true);
  }else{
     if(document.form1.it05_guia.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbi.php?pesquisa_chave='+document.form1.it05_guia.value+'&funcao_js=parent.js_mostraitbi','Pesquisa',false);
     }else{
       document.form1.it01_guia.value = ''; 
     }
  }
}
function js_mostraitbi(chave,erro){
  document.form1.it01_guia.value = chave; 
  if(erro==true){ 
    document.form1.it05_guia.focus(); 
    document.form1.it05_guia.value = ''; 
  }
}
function js_mostraitbi1(chave1,chave2){
  document.form1.it05_guia.value = chave1;
  document.form1.it01_guia.value = chave2;
  db_iframe_itbi.hide();
}
function js_pesquisait05_itbisituacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_itbisituacao','func_itbisituacao.php?funcao_js=parent.js_mostraitbisituacao1|it07_codigo|it07_descr','Pesquisa',true);
  }else{
     if(document.form1.it05_itbisituacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_itbisituacao','func_itbisituacao.php?pesquisa_chave='+document.form1.it05_itbisituacao.value+'&funcao_js=parent.js_mostraitbisituacao','Pesquisa',false);
     }else{
       document.form1.it07_descr.value = ''; 
     }
  }
}
function js_mostraitbisituacao(chave,erro){
  document.form1.it07_descr.value = chave; 
  if(erro==true){ 
    document.form1.it05_itbisituacao.focus(); 
    document.form1.it05_itbisituacao.value = ''; 
  }
}
function js_mostraitbisituacao1(chave1,chave2){
  document.form1.it05_itbisituacao.value = chave1;
  document.form1.it07_descr.value = chave2;
  db_iframe_itbisituacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_itburbano','func_itburbano.php?funcao_js=parent.js_preenchepesquisa|it05_guia','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_itburbano.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>