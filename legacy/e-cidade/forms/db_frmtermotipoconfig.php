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

//MODULO: dividaativa
$cltermotipoconfig->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k00_descr");
$clrotulo->label("k03_descr");
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk42_sequencial?>">
       <?=@$Lk42_sequencial?>
    </td>
    <td> 
<?
db_input('k42_sequencial',10,$Ik42_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk42_cadtipo?>">
       <?
       db_ancora(@$Lk42_cadtipo,"js_pesquisak42_cadtipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k42_cadtipo',10,$Ik42_cadtipo,true,'text',$db_opcao," onchange='js_pesquisak42_cadtipo(false);'")
?>
       <?
db_input('k03_descr',60,$Ik03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk42_tiponovo?>">
       <?
       db_ancora(@$Lk42_tiponovo,"js_pesquisak42_tiponovo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k42_tiponovo',10,$Ik42_tiponovo,true,'text',$db_opcao," onchange='js_pesquisak42_tiponovo(false);'")
?>
       <?
db_input('k00_descr',60,$Ik00_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
 
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak42_tiponovo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.k42_tiponovo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k42_tiponovo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.k42_tiponovo.focus(); 
    document.form1.k42_tiponovo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.k42_tiponovo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisak42_cadtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadtipo','func_cadtipo.php?funcao_js=parent.js_mostracadtipo1|k03_tipo|k03_descr','Pesquisa',true);
  }else{
     if(document.form1.k42_cadtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadtipo','func_cadtipo.php?pesquisa_chave='+document.form1.k42_cadtipo.value+'&funcao_js=parent.js_mostracadtipo','Pesquisa',false);
     }else{
       document.form1.k03_tipo.value = ''; 
     }
  }
}
function js_mostracadtipo(chave,erro){
  document.form1.k03_descr.value = chave; 
  if(erro==true){ 
    document.form1.k42_cadtipo.focus(); 
    document.form1.k42_cadtipo.value = ''; 
  }
}
function js_mostracadtipo1(chave1,chave2){
  document.form1.k42_cadtipo.value = chave1;
  document.form1.k03_descr.value = chave2;
  db_iframe_cadtipo.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_termotipoconfig','func_termotipoconfig.php?funcao_js=parent.js_preenchepesquisa|k42_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_termotipoconfig.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>