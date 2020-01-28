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
$clorcprojativsubprojeto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o55_descr");
$clrotulo->label("o55_descr");
$clrotulo->label("o55_descr");
$clrotulo->label("o55_descr");
$clrotulo->label("o106_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To99_sequencial?>">
       <?=@$Lo99_sequencial?>
    </td>
    <td> 
<?
db_input('o99_sequencial',10,$Io99_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To99_projativ?>">
       <?
       db_ancora(@$Lo99_projativ,"js_pesquisao99_projativ(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o99_projativ',10,$Io99_projativ,true,'text',$db_opcao," onchange='js_pesquisao99_projativ(false);'")
?>
       <?
db_input('o55_descr',40,$Io55_descr,true,'text',3,'')
db_input('o55_descr',40,$Io55_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To99_anousu?>">
       <?
       db_ancora(@$Lo99_anousu,"js_pesquisao99_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$o99_anousu = db_getsession('DB_anousu');
db_input('o99_anousu',10,$Io99_anousu,true,'text',3," onchange='js_pesquisao99_anousu(false);'")
?>
       <?
db_input('o55_descr',40,$Io55_descr,true,'text',3,'')
db_input('o55_descr',40,$Io55_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To99_orcsubprojeto?>">
       <?
       db_ancora(@$Lo99_orcsubprojeto,"js_pesquisao99_orcsubprojeto(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o99_orcsubprojeto',10,$Io99_orcsubprojeto,true,'text',$db_opcao," onchange='js_pesquisao99_orcsubprojeto(false);'")
?>
       <?
db_input('o106_descricao',50,$Io106_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao99_projativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_anousu|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o99_projativ.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o99_projativ.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o99_projativ.focus(); 
    document.form1.o99_projativ.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o99_projativ.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao99_projativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o99_projativ.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o99_projativ.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o99_projativ.focus(); 
    document.form1.o99_projativ.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o99_projativ.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao99_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_anousu|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o99_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o99_anousu.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o99_anousu.focus(); 
    document.form1.o99_anousu.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o99_anousu.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao99_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o99_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o99_anousu.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o99_anousu.focus(); 
    document.form1.o99_anousu.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o99_anousu.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao99_orcsubprojeto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcsubprojeto','func_orcsubprojeto.php?funcao_js=parent.js_mostraorcsubprojeto1|o106_sequencial|o106_descricao','Pesquisa',true);
  }else{
     if(document.form1.o99_orcsubprojeto.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcsubprojeto','func_orcsubprojeto.php?pesquisa_chave='+document.form1.o99_orcsubprojeto.value+'&funcao_js=parent.js_mostraorcsubprojeto','Pesquisa',false);
     }else{
       document.form1.o106_descricao.value = ''; 
     }
  }
}
function js_mostraorcsubprojeto(chave,erro){
  document.form1.o106_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o99_orcsubprojeto.focus(); 
    document.form1.o99_orcsubprojeto.value = ''; 
  }
}
function js_mostraorcsubprojeto1(chave1,chave2){
  document.form1.o99_orcsubprojeto.value = chave1;
  document.form1.o106_descricao.value = chave2;
  db_iframe_orcsubprojeto.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativsubprojeto','func_orcprojativsubprojeto.php?funcao_js=parent.js_preenchepesquisa|o99_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcprojativsubprojeto.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>