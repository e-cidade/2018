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
$clpactoitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m61_descr");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("o89_pcmater");

?>
<form name="form1" method="post" action="">
<table>
<tr>
<td>
<fieldset>
<legend><b>Item do Pacto</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To109_sequencial?>">
       <?=@$Lo109_sequencial?>
    </td>
    <td> 
<?
db_input('o109_sequencial',10,$Io109_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To109_matunid?>">
       <?
       db_ancora(@$Lo109_matunid,"js_pesquisao109_matunid(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o109_matunid',10,$Io109_matunid,true,'text',$db_opcao," onchange='js_pesquisao109_matunid(false);'")
?>
       <?
db_input('m61_descr',50,$Im61_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$To89_pcmater?>">
       <?
       db_ancora(@$Lo89_pcmater,"js_pesquisao89_pcmater(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o89_pcmater',10,$Io89_pcmater,true,'text',$db_opcao," onchange='js_pesquisao89_pcmater(false);'")
?>
       <?
db_input('pc01_descrmater',50,$Ipc01_descrmater,true,'text',3,'')
       ?>
    </td>
  </tr> 
  
  <tr>
    <td nowrap title="<?=@$To109_descricao?>">
       <?=@$Lo109_descricao?>
    </td>
    <td> 
<?
db_input('o109_descricao',63,$Io109_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td>
       <?=$Lo109_controlasaldo ?>
    </td>
    <td>
       <?
         db_select("o109_controlasaldo",getValoresPadroesCampo("o109_controlasaldo"),true,$db_opcao);
       ?>
    </td>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao109_matunid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matunid','func_matunid.php?funcao_js=parent.js_mostramatunid1|m61_codmatunid|m61_descr','Pesquisa',true);
  }else{
     if(document.form1.o109_matunid.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matunid','func_matunid.php?pesquisa_chave='+document.form1.o109_matunid.value+'&funcao_js=parent.js_mostramatunid','Pesquisa',false);
     }else{
       document.form1.m61_descr.value = ''; 
     }
  }
}
function js_mostramatunid(chave,erro){
  document.form1.m61_descr.value = chave; 
  if(erro==true){ 
    document.form1.o109_matunid.focus(); 
    document.form1.o109_matunid.value = ''; 
  }
}
function js_mostramatunid1(chave1,chave2){
  document.form1.o109_matunid.value = chave1;
  document.form1.m61_descr.value = chave2;
  db_iframe_matunid.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pactoitem','func_pactoitem.php?funcao_js=parent.js_preenchepesquisa|o109_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pactoitem.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_pesquisao89_pcmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater','Pesquisa',true);
  }else{
     if(document.form1.o89_pcmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.o89_pcmater.value+'&funcao_js=parent.js_mostrapcmater','Pesquisa',false);
     }else{
       document.form1.pc01_descrmater.value = ''; 
     }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave; 
  if(erro==true){ 
    document.form1.o89_pcmater.focus(); 
    document.form1.o89_pcmater.value = ''; 
  }
}
function js_mostrapcmater1(chave1,chave2){
  document.form1.o89_pcmater.value = chave1;
  document.form1.pc01_descrmater.value = chave2;
  db_iframe_pcmater.hide();
}
</script>