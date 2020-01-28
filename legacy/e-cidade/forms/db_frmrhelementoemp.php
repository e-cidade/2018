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
$clrhelementoemp->rotulo->label();
$clrhelementoemppcmater->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("o56_descr");
$clrotulo->label("pc01_descrmater");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh38_seq?>">
       <?=@$Lrh38_seq?>
    </td>
    <td> 
<?
db_input('rh38_seq',6,$Irh38_seq,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh38_codele?>">
       <?
       db_ancora(@$Lrh38_codele,"js_pesquisarh38_codele(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?

if ($db_opcao == 2 || $db_opcao == 22){
  $db_opcao02 = 3;
} else {
  $db_opcao02 = $db_opcao;
}

db_input('rh38_codele',10,$Irh38_codele,true,'text',$db_opcao02," onchange='js_pesquisarh38_codele(false);'");
if ($db_opcao == 1 || $db_opcao == 11){
  $rh38_anousu = db_getsession("DB_anousu");  
}
db_input('rh38_anousu',10,"",true,"hidden",3,"");
?>
       <?
db_input('o56_descr',50,$Io56_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh36_pcmater?>">
       <?
       db_ancora(@$Lrh36_pcmater,"js_pesquisarh36_pcmater(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh36_pcmater',10,$Irh36_pcmater,true,'text',$db_opcao," onchange='js_pesquisarh36_pcmater(false);'");
?>
       <?
db_input('pc01_descrmater',50,$Ipc01_descrmater,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh36_pcmater(mostra){
  if (document.form1.rh38_codele.value == ""){
    alert("Selecione antes um elemento. Verifique!");
    document.form1.rh38_codele.focus();
    document.form1.rh38_codele.select();
    return false;
  }
    
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?tem_material=true&o56_codele='+document.form1.rh38_codele.value+'&funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater','Pesquisa',true);
  }else{
     if(document.form1.rh36_pcmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?tem_material=true&o56_codele='+document.form1.rh38_codele.value+'&pesquisa_chave='+document.form1.rh36_pcmater.value+'&funcao_js=parent.js_mostrapcmater','Pesquisa',false);
     }else{
       document.form1.pc01_descrmater.value = ''; 
     }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave; 
  if(erro==true){ 
    document.form1.rh36_pcmater.focus(); 
    document.form1.rh36_pcmater.value = ''; 
  }
}
function js_mostrapcmater1(chave1,chave2){
  document.form1.rh36_pcmater.value    = chave1;
  document.form1.pc01_descrmater.value = chave2;
  db_iframe_pcmater.hide();
}
function js_pesquisarh38_codele(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelementodesdobramento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_descr','Pesquisa',true);
  }else{
     if(document.form1.rh38_codele.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelementodesdobramento.php?pesquisa_chave='+document.form1.rh38_codele.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_descr.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh38_codele.focus(); 
    document.form1.rh38_codele.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.rh38_codele.value = chave1;
  document.form1.o56_descr.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhelementoemp','func_rhelementoemp.php?funcao_js=parent.js_preenchepesquisa|rh38_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhelementoemp.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>