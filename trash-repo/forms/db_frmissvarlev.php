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
$clissvarlev->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q05_numpre");
$clrotulo->label("y60_data");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq18_codigo?>">
       <?
       db_ancora(@$Lq18_codigo,"js_pesquisaq18_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q18_codigo',8,$Iq18_codigo,true,'text',$db_opcao," onchange='js_pesquisaq18_codigo(false);'")
?>
       <?
db_input('q05_numpre',4,$Iq05_numpre,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq18_codlev?>">
       <?
       db_ancora(@$Lq18_codlev,"js_pesquisaq18_codlev(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q18_codlev',10,$Iq18_codlev,true,'text',$db_opcao," onchange='js_pesquisaq18_codlev(false);'")
?>
       <?
db_input('y60_data',10,$Iy60_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq18_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issvar','func_issvar.php?funcao_js=parent.js_mostraissvar1|q05_codigo|q05_numpre','Pesquisa',true);
  }else{
     if(document.form1.q18_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issvar','func_issvar.php?pesquisa_chave='+document.form1.q18_codigo.value+'&funcao_js=parent.js_mostraissvar','Pesquisa',false);
     }else{
       document.form1.q05_numpre.value = ''; 
     }
  }
}
function js_mostraissvar(chave,erro){
  document.form1.q05_numpre.value = chave; 
  if(erro==true){ 
    document.form1.q18_codigo.focus(); 
    document.form1.q18_codigo.value = ''; 
  }
}
function js_mostraissvar1(chave1,chave2){
  document.form1.q18_codigo.value = chave1;
  document.form1.q05_numpre.value = chave2;
  db_iframe_issvar.hide();
}
function js_pesquisaq18_codlev(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_levanta','func_levanta.php?funcao_js=parent.js_mostralevanta1|y60_codlev|y60_data','Pesquisa',true);
  }else{
     if(document.form1.q18_codlev.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_levanta','func_levanta.php?pesquisa_chave='+document.form1.q18_codlev.value+'&funcao_js=parent.js_mostralevanta','Pesquisa',false);
     }else{
       document.form1.y60_data.value = ''; 
     }
  }
}
function js_mostralevanta(chave,erro){
  document.form1.y60_data.value = chave; 
  if(erro==true){ 
    document.form1.q18_codlev.focus(); 
    document.form1.q18_codlev.value = ''; 
  }
}
function js_mostralevanta1(chave1,chave2){
  document.form1.q18_codlev.value = chave1;
  document.form1.y60_data.value = chave2;
  db_iframe_levanta.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issvarlev','func_issvarlev.php?funcao_js=parent.js_preenchepesquisa|q18_codigo|q18_codlev','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_issvarlev.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>