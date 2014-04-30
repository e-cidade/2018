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
$cliptuender->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_numcgm");
?>
<form name="form1" method="post" action="">
<center><br>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj43_matric?>">
       <?
       db_ancora(@$Lj43_matric,"js_pesquisaj43_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j43_matric',10,$Ij43_matric,true,'text',$db_opcao," onchange='js_pesquisaj43_matric(false);'")
?>
       <?
db_input('j01_numcgm',10,$Ij01_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj43_dest?>">
       <?=@$Lj43_dest?>
    </td>
    <td> 
<?
db_input('j43_dest',40,$Ij43_dest,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj43_ender?>">
       <?=@$Lj43_ender?>
    </td>
    <td> 
<?
db_input('j43_ender',40,$Ij43_ender,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj43_numimo?>">
       <?=@$Lj43_numimo?>
    </td>
    <td> 
<?
db_input('j43_numimo',10,$Ij43_numimo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj43_comple?>">
       <?=@$Lj43_comple?>
    </td>
    <td> 
<?
db_input('j43_comple',20,$Ij43_comple,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj43_bairro?>">
       <?=@$Lj43_bairro?>
    </td>
    <td> 
<?
db_input('j43_bairro',40,$Ij43_bairro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj43_munic?>">
       <?=@$Lj43_munic?>
    </td>
    <td> 
<?
db_input('j43_munic',20,$Ij43_munic,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj43_uf?>">
       <?=@$Lj43_uf?>
    </td>
    <td> 
<?
db_input('j43_uf',2,$Ij43_uf,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj43_cep?>">
       <?=@$Lj43_cep?>
    </td>
    <td> 
<?
db_input('j43_cep',8,$Ij43_cep,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj43_cxpost?>">
       <?=@$Lj43_cxpost?>
    </td>
    <td> 
<?
db_input('j43_cxpost',10,$Ij43_cxpost,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj43_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric|j01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.j43_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+document.form1.j43_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
     }else{
       document.form1.j01_numcgm.value = ''; 
     }
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.j43_matric.focus(); 
    document.form1.j43_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j43_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe_iptubase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_iptuender','func_iptuender.php?funcao_js=parent.js_preenchepesquisa|j43_matric','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_iptuender.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>