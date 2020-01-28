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

//MODULO: recursoshumanos
$clrhestagio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h12_assent");
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset><legend><b>Dados do Estágio</b></legend>
<table border="0">
<tr>
 <td>
  <tr>
    <td nowrap title="<?=@$Th50_sequencial?>">
       <?=@$Lh50_sequencial?>
    </td>
    <td> 
<?
db_input('h50_sequencial',10,$Ih50_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th50_lei?>">
       <?=@$Lh50_lei?>
    </td>
    <td> 
<?
db_input('h50_lei',20,$Ih50_lei,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th50_descr?>">
       <?=@$Lh50_descr?>
    </td>
    <td> 
<?
db_input('h50_descr',40,$Ih50_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th50_obs?>">
       <?=@$Lh50_obs?>
    </td>
    <td> 
<?
db_textarea('h50_obs',8,60,$Ih50_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th50_confobs?>">
       <?=@$Lh50_confobs?>
    </td>
    <td> 
<?
$x = array('1'=>'Quesito','2'=>'Pergunta','3'=>'Ambos');
db_select('h50_confobs',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th50_minimopontos?>">
       <?=@$Lh50_minimopontos?>
    </td>
    <td> 
<?
db_input('h50_minimopontos',10,$Ih50_minimopontos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th50_assentaaprova?>">
       <?
       db_ancora(@$Lh50_assentaaprova,"js_pesquisah50_assentaaprova(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h50_assentaaprova',10,$Ih50_assentaaprova,true,'text',$db_opcao," onchange='js_pesquisah50_assentaaprova(false);'")
?>
       <?
db_input('h12_assent',5,$Ih12_assent,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th50_assentaaprova?>">
       <?
       db_ancora(@$Lh50_assentareprova,"js_pesquisah50_assentareprova(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h50_assentareprova',10,$Ih50_assentareprova,true,'text',$db_opcao," onchange='js_pesquisah50_assentareprova(false);'")
?>
       <?
db_input('h12_assent2',10,$Ih12_assent,true,'text',3,'')
       ?>
    </td>
    </tr>
  <tr>
    <td nowrap title="<?=@$Th50_duracaoestagio?>">
       <?=@$Lh50_duracaoestagio?>
    </td>
    <td> 
<?
db_input('h50_duracaoestagio',10,$Ih50_duracaoestagio,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </td>
  </tr>
  </fieldset>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisah50_assentaaprova(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagio','db_iframe_tipoasse','func_tipoasse.php?funcao_js=parent.js_mostratipoasse1|h12_codigo|h12_assent','Pesquisa',true);
  }else{
     if(document.form1.h50_assentaaprova.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagio','db_iframe_tipoasse','func_tipoasse.php?pesquisa_chave='+document.form1.h50_assentaaprova.value+'&funcao_js=parent.js_mostratipoasse','Pesquisa',false);
     }else{
       document.form1.h12_assent.value = ''; 
     }
  }
}
function js_mostratipoasse(chave,erro){
  document.form1.h12_assent.value = chave; 
  if(erro==true){ 
    document.form1.h50_assentaaprova.focus(); 
    document.form1.h50_assentaaprova.value = ''; 
  }
}
function js_mostratipoasse1(chave1,chave2){
  document.form1.h50_assentaaprova.value = chave1;
  document.form1.h12_assent.value = chave2;
  db_iframe_tipoasse.hide();
}
function js_pesquisah50_assentareprova(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagio','db_iframe_tipoasse','func_tipoasse.php?funcao_js=parent.js_mostratipoasse3|h12_codigo|h12_assent','Pesquisa',true);
  }else{
     if(document.form1.h50_assentareprova.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagio','db_iframe_tipoasse','func_tipoasse.php?pesquisa_chave='+document.form1.h50_assentareprova.value+'&funcao_js=parent.js_mostratipoasse2','Pesquisa',false);
     }else{
       document.form1.h12_assent2.value = ''; 
     }
  }
}
function js_mostratipoasse2(chave,erro){
  document.form1.h12_assent2.value = chave; 
  if(erro==true){ 
    document.form1.h50_assentareprova.focus(); 
    document.form1.h50_assentareprova.value = ''; 
  }
}
function js_mostratipoasse3(chave1,chave2){
  document.form1.h50_assentareprova.value = chave1;
  document.form1.h12_assent2.value = chave2;
  db_iframe_tipoasse.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_rhestagio','db_iframe_rhestagio','func_rhestagio.php?funcao_js=parent.js_preenchepesquisa|h50_sequencial','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_rhestagio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>