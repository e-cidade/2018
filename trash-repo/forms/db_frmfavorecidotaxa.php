<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: juridico
$clfavorecidotaxa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ar36_sequencial");
$clrotulo->label("v86_contabancaria");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv87_sequencial?>">
       <?=@$Lv87_sequencial?>
    </td>
    <td> 
<?
db_input('v87_sequencial',10,$Iv87_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv87_favorecido?>">
       <?
       db_ancora(@$Lv87_favorecido,"js_pesquisav87_favorecido(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v87_favorecido',10,$Iv87_favorecido,true,'text',$db_opcao," onchange='js_pesquisav87_favorecido(false);'")
?>
       <?
db_input('v86_contabancaria',10,$Iv86_contabancaria,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv87_taxa?>">
       <?
       db_ancora(@$Lv87_taxa,"js_pesquisav87_taxa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v87_taxa',10,$Iv87_taxa,true,'text',$db_opcao," onchange='js_pesquisav87_taxa(false);'")
?>
       <?
db_input('ar36_sequencial',10,$Iar36_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav87_taxa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_taxa','func_taxa.php?funcao_js=parent.js_mostrataxa1|ar36_sequencial|ar36_sequencial','Pesquisa',true);
  }else{
     if(document.form1.v87_taxa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_taxa','func_taxa.php?pesquisa_chave='+document.form1.v87_taxa.value+'&funcao_js=parent.js_mostrataxa','Pesquisa',false);
     }else{
       document.form1.ar36_sequencial.value = ''; 
     }
  }
}
function js_mostrataxa(chave,erro){
  document.form1.ar36_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.v87_taxa.focus(); 
    document.form1.v87_taxa.value = ''; 
  }
}
function js_mostrataxa1(chave1,chave2){
  document.form1.v87_taxa.value = chave1;
  document.form1.ar36_sequencial.value = chave2;
  db_iframe_taxa.hide();
}
function js_pesquisav87_favorecido(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_favorecido','func_favorecido.php?funcao_js=parent.js_mostrafavorecido1|v86_sequencial|v86_contabancaria','Pesquisa',true);
  }else{
     if(document.form1.v87_favorecido.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_favorecido','func_favorecido.php?pesquisa_chave='+document.form1.v87_favorecido.value+'&funcao_js=parent.js_mostrafavorecido','Pesquisa',false);
     }else{
       document.form1.v86_contabancaria.value = ''; 
     }
  }
}
function js_mostrafavorecido(chave,erro){
  document.form1.v86_contabancaria.value = chave; 
  if(erro==true){ 
    document.form1.v87_favorecido.focus(); 
    document.form1.v87_favorecido.value = ''; 
  }
}
function js_mostrafavorecido1(chave1,chave2){
  document.form1.v87_favorecido.value = chave1;
  document.form1.v86_contabancaria.value = chave2;
  db_iframe_favorecido.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_favorecidotaxa','func_favorecidotaxa.php?funcao_js=parent.js_preenchepesquisa|v87_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_favorecidotaxa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>