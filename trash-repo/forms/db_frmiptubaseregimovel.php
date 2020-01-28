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

//MODULO: cadastro
$cliptubaseregimovel->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j69_descr");
$clrotulo->label("j01_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj04_sequencial?>">
       <?=@$Lj04_sequencial?>
    </td>
    <td> 
<?
db_input('j04_sequencial',10,$Ij04_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj04_setorregimovel?>">
       <?
       db_ancora(@$Lj04_setorregimovel,"js_pesquisaj04_setorregimovel(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j04_setorregimovel',10,$Ij04_setorregimovel,true,'text',$db_opcao," onchange='js_pesquisaj04_setorregimovel(false);'")
?>
       <?
db_input('j69_descr',40,$Ij69_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj04_matric?>">
       <?
       db_ancora(@$Lj04_matric,"js_pesquisaj04_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j04_matric',10,$Ij04_matric,true,'text',$db_opcao," onchange='js_pesquisaj04_matric(false);'")
?>
       <?
db_input('j01_numcgm',10,$Ij01_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj04_matricregimo?>">
       <?=@$Lj04_matricregimo?>
    </td>
    <td> 
<?
db_input('j04_matricregimo',20,$Ij04_matricregimo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj04_quadraregimo?>">
       <?=@$Lj04_quadraregimo?>
    </td>
    <td> 
<?
db_input('j04_quadraregimo',4,$Ij04_quadraregimo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj04_loteregimo?>">
       <?=@$Lj04_loteregimo?>
    </td>
    <td> 
<?
db_input('j04_loteregimo',4,$Ij04_loteregimo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj04_setorregimovel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_setorregimovel','func_setorregimovel.php?funcao_js=parent.js_mostrasetorregimovel1|j69_sequencial|j69_descr','Pesquisa',true);
  }else{
     if(document.form1.j04_setorregimovel.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_setorregimovel','func_setorregimovel.php?pesquisa_chave='+document.form1.j04_setorregimovel.value+'&funcao_js=parent.js_mostrasetorregimovel','Pesquisa',false);
     }else{
       document.form1.j69_descr.value = ''; 
     }
  }
}
function js_mostrasetorregimovel(chave,erro){
  document.form1.j69_descr.value = chave; 
  if(erro==true){ 
    document.form1.j04_setorregimovel.focus(); 
    document.form1.j04_setorregimovel.value = ''; 
  }
}
function js_mostrasetorregimovel1(chave1,chave2){
  document.form1.j04_setorregimovel.value = chave1;
  document.form1.j69_descr.value = chave2;
  db_iframe_setorregimovel.hide();
}
function js_pesquisaj04_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric|j01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.j04_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+document.form1.j04_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
     }else{
       document.form1.j01_numcgm.value = ''; 
     }
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.j04_matric.focus(); 
    document.form1.j04_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j04_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe_iptubase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_iptubaseregimovel','func_iptubaseregimovel.php?funcao_js=parent.js_preenchepesquisa|j04_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_iptubaseregimovel.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>