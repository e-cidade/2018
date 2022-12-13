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
$clrhlotavincrec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o56_elemento");
$clrotulo->label("o15_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh43_codlotavinc?>">
       <?=@$Lrh43_codlotavinc?>
    </td>
    <td> 
<?
db_input('rh43_codlotavinc',6,$Irh43_codlotavinc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh43_codelenov?>">
       <?
       db_ancora(@$Lrh43_codelenov,"js_pesquisarh43_codelenov(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh43_codelenov',6,$Irh43_codelenov,true,'text',$db_opcao," onchange='js_pesquisarh43_codelenov(false);'")
?>
       <?
db_input('o56_elemento',13,$Io56_elemento,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh43_recurso?>">
       <?
       db_ancora(@$Lrh43_recurso,"js_pesquisarh43_recurso(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh43_recurso',4,$Irh43_recurso,true,'text',$db_opcao," onchange='js_pesquisarh43_recurso(false);'")
?>
       <?
db_input('o15_descr',30,$Io15_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh43_codelenov(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_elemento','Pesquisa',true);
  }else{
     if(document.form1.rh43_codelenov.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.rh43_codelenov.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_elemento.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_elemento.value = chave; 
  if(erro==true){ 
    document.form1.rh43_codelenov.focus(); 
    document.form1.rh43_codelenov.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.rh43_codelenov.value = chave1;
  document.form1.o56_elemento.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisarh43_recurso(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
     if(document.form1.rh43_recurso.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.rh43_recurso.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
     }else{
       document.form1.o15_descr.value = ''; 
     }
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh43_recurso.focus(); 
    document.form1.rh43_recurso.value = ''; 
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.rh43_recurso.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhlotavincrec','func_rhlotavincrec.php?funcao_js=parent.js_preenchepesquisa|rh43_codlotavinc|rh43_codelenov','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_rhlotavincrec.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>