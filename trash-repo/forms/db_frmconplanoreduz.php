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
$clconplanoreduz->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c60_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("c64_descr");
$clrotulo->label("o15_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc61_codcon?>">
       <?
       db_ancora(@$Lc61_codcon,"js_pesquisac61_codcon(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c61_codcon',6,$Ic61_codcon,true,'text',$db_opcao," onchange='js_pesquisac61_codcon(false);'")
?>
       <?
db_input('c60_descr',50,$Ic60_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc61_reduz?>">
       <?=@$Lc61_reduz?>
    </td>
    <td> 
<?
db_input('c61_reduz',6,$Ic61_reduz,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc61_instit?>">
       <?
       db_ancora(@$Lc61_instit,"js_pesquisac61_instit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c61_instit',2,$Ic61_instit,true,'text',$db_opcao," onchange='js_pesquisac61_instit(false);'")
?>
       <?
db_input('nomeinst',80,$Inomeinst,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc61_codpla?>">
       <?
       db_ancora(@$Lc61_codpla,"js_pesquisac61_codpla(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c61_codpla',6,$Ic61_codpla,true,'text',$db_opcao," onchange='js_pesquisac61_codpla(false);'")
?>
       <?
db_input('c64_descr',60,$Ic64_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc61_codigo?>">
       <?
       db_ancora(@$Lc61_codigo,"js_pesquisac61_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c61_codigo',4,$Ic61_codigo,true,'text',$db_opcao," onchange='js_pesquisac61_codigo(false);'")
?>
       <?
db_input('o15_descr',30,$Io15_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac61_codcon(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conplano','func_conplano.php?funcao_js=parent.js_mostraconplano1|c60_codcon|c60_descr','Pesquisa',true);
  }else{
     if(document.form1.c61_codcon.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conplano','func_conplano.php?pesquisa_chave='+document.form1.c61_codcon.value+'&funcao_js=parent.js_mostraconplano','Pesquisa',false);
     }else{
       document.form1.c60_descr.value = ''; 
     }
  }
}
function js_mostraconplano(chave,erro){
  document.form1.c60_descr.value = chave; 
  if(erro==true){ 
    document.form1.c61_codcon.focus(); 
    document.form1.c61_codcon.value = ''; 
  }
}
function js_mostraconplano1(chave1,chave2){
  document.form1.c61_codcon.value = chave1;
  document.form1.c60_descr.value = chave2;
  db_iframe_conplano.hide();
}
function js_pesquisac61_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.c61_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.c61_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.c61_instit.focus(); 
    document.form1.c61_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.c61_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisac61_codpla(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conplanosis','func_conplanosis.php?funcao_js=parent.js_mostraconplanosis1|c64_codpla|c64_descr','Pesquisa',true);
  }else{
     if(document.form1.c61_codpla.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conplanosis','func_conplanosis.php?pesquisa_chave='+document.form1.c61_codpla.value+'&funcao_js=parent.js_mostraconplanosis','Pesquisa',false);
     }else{
       document.form1.c64_descr.value = ''; 
     }
  }
}
function js_mostraconplanosis(chave,erro){
  document.form1.c64_descr.value = chave; 
  if(erro==true){ 
    document.form1.c61_codpla.focus(); 
    document.form1.c61_codpla.value = ''; 
  }
}
function js_mostraconplanosis1(chave1,chave2){
  document.form1.c61_codpla.value = chave1;
  document.form1.c64_descr.value = chave2;
  db_iframe_conplanosis.hide();
}
function js_pesquisac61_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
     if(document.form1.c61_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.c61_codigo.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
     }else{
       document.form1.o15_descr.value = ''; 
     }
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.c61_codigo.focus(); 
    document.form1.c61_codigo.value = ''; 
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.c61_codigo.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conplanoreduz','func_conplanoreduz.php?funcao_js=parent.js_preenchepesquisa|c61_codcon','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conplanoreduz.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>