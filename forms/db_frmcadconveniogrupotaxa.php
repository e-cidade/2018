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

//MODULO: arrecadacao
$clcadconveniogrupotaxa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ar11_nome");
$clrotulo->label("ar37_sequencial");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tar39_sequencial?>">
       <?=@$Lar39_sequencial?>
    </td>
    <td> 
<?
db_input('ar39_sequencial',10,$Iar39_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tar39_cadconvenio?>">
       <?
       db_ancora(@$Lar39_cadconvenio,"js_pesquisaar39_cadconvenio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ar39_cadconvenio',10,$Iar39_cadconvenio,true,'text',$db_opcao," onchange='js_pesquisaar39_cadconvenio(false);'")
?>
       <?
db_input('ar11_nome',50,$Iar11_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tar39_grupotaxa?>">
       <?
       db_ancora(@$Lar39_grupotaxa,"js_pesquisaar39_grupotaxa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ar39_grupotaxa',10,$Iar39_grupotaxa,true,'text',$db_opcao," onchange='js_pesquisaar39_grupotaxa(false);'")
?>
       <?
db_input('ar37_sequencial',10,$Iar37_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaar39_cadconvenio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadconvenio','func_cadconvenio.php?funcao_js=parent.js_mostracadconvenio1|ar11_sequencial|ar11_nome','Pesquisa',true);
  }else{
     if(document.form1.ar39_cadconvenio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadconvenio','func_cadconvenio.php?pesquisa_chave='+document.form1.ar39_cadconvenio.value+'&funcao_js=parent.js_mostracadconvenio','Pesquisa',false);
     }else{
       document.form1.ar11_nome.value = ''; 
     }
  }
}
function js_mostracadconvenio(chave,erro){
  document.form1.ar11_nome.value = chave; 
  if(erro==true){ 
    document.form1.ar39_cadconvenio.focus(); 
    document.form1.ar39_cadconvenio.value = ''; 
  }
}
function js_mostracadconvenio1(chave1,chave2){
  document.form1.ar39_cadconvenio.value = chave1;
  document.form1.ar11_nome.value = chave2;
  db_iframe_cadconvenio.hide();
}
function js_pesquisaar39_grupotaxa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_grupotaxa','func_grupotaxa.php?funcao_js=parent.js_mostragrupotaxa1|ar37_sequencial|ar37_sequencial','Pesquisa',true);
  }else{
     if(document.form1.ar39_grupotaxa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_grupotaxa','func_grupotaxa.php?pesquisa_chave='+document.form1.ar39_grupotaxa.value+'&funcao_js=parent.js_mostragrupotaxa','Pesquisa',false);
     }else{
       document.form1.ar37_sequencial.value = ''; 
     }
  }
}
function js_mostragrupotaxa(chave,erro){
  document.form1.ar37_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.ar39_grupotaxa.focus(); 
    document.form1.ar39_grupotaxa.value = ''; 
  }
}
function js_mostragrupotaxa1(chave1,chave2){
  document.form1.ar39_grupotaxa.value = chave1;
  document.form1.ar37_sequencial.value = chave2;
  db_iframe_grupotaxa.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadconveniogrupotaxa','func_cadconveniogrupotaxa.php?funcao_js=parent.js_preenchepesquisa|ar39_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadconveniogrupotaxa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>