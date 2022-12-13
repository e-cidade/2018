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

//MODULO: issqn
$clvarfixproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q33_codigo");
$clrotulo->label("p58_codproc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq36_sequencial?>">
       <?=@$Lq36_sequencial?>
    </td>
    <td> 
<?
db_input('q36_sequencial',10,$Iq36_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq36_varfix?>">
       <?
       db_ancora(@$Lq36_varfix,"js_pesquisaq36_varfix(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q36_varfix',10,$Iq36_varfix,true,'text',$db_opcao," onchange='js_pesquisaq36_varfix(false);'")
?>
       <?
db_input('q33_codigo',8,$Iq33_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq36_processo?>">
       <?
       db_ancora(@$Lq36_processo,"js_pesquisaq36_processo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q36_processo',10,$Iq36_processo,true,'text',$db_opcao," onchange='js_pesquisaq36_processo(false);'")
?>
       <?
db_input('p58_codproc',10,$Ip58_codproc,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq36_notifica?>">
       <?=@$Lq36_notifica?>
    </td>
    <td> 
<?
db_input('q36_notifica',10,$Iq36_notifica,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq36_varfix(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_varfix','func_varfix.php?funcao_js=parent.js_mostravarfix1|q33_codigo|q33_codigo','Pesquisa',true);
  }else{
     if(document.form1.q36_varfix.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_varfix','func_varfix.php?pesquisa_chave='+document.form1.q36_varfix.value+'&funcao_js=parent.js_mostravarfix','Pesquisa',false);
     }else{
       document.form1.q33_codigo.value = ''; 
     }
  }
}
function js_mostravarfix(chave,erro){
  document.form1.q33_codigo.value = chave; 
  if(erro==true){ 
    document.form1.q36_varfix.focus(); 
    document.form1.q36_varfix.value = ''; 
  }
}
function js_mostravarfix1(chave1,chave2){
  document.form1.q36_varfix.value = chave1;
  document.form1.q33_codigo.value = chave2;
  db_iframe_varfix.hide();
}
function js_pesquisaq36_processo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.q36_processo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.q36_processo.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave; 
  if(erro==true){ 
    document.form1.q36_processo.focus(); 
    document.form1.q36_processo.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.q36_processo.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_varfixproc','func_varfixproc.php?funcao_js=parent.js_preenchepesquisa|q36_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_varfixproc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>