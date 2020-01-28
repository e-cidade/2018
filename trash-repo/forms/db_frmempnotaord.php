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

//MODULO: empenho
$clempnotaord->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m51_data");
$clrotulo->label("e69_codnota");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tm72_codordem?>">
       <?
       db_ancora(@$Lm72_codordem,"js_pesquisam72_codordem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('m72_codordem',10,$Im72_codordem,true,'text',$db_opcao," onchange='js_pesquisam72_codordem(false);'")
?>
       <?
db_input('m51_data',10,$Im51_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm72_codnota?>">
       <?
       db_ancora(@$Lm72_codnota,"js_pesquisam72_codnota(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('m72_codnota',6,$Im72_codnota,true,'text',$db_opcao," onchange='js_pesquisam72_codnota(false);'")
?>
       <?
db_input('e69_codnota',6,$Ie69_codnota,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisam72_codordem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordem.php?funcao_js=parent.js_mostramatordem1|m51_codordem|m51_data','Pesquisa',true);
  }else{
     if(document.form1.m72_codordem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordem.php?pesquisa_chave='+document.form1.m72_codordem.value+'&funcao_js=parent.js_mostramatordem','Pesquisa',false);
     }else{
       document.form1.m51_data.value = ''; 
     }
  }
}
function js_mostramatordem(chave,erro){
  document.form1.m51_data.value = chave; 
  if(erro==true){ 
    document.form1.m72_codordem.focus(); 
    document.form1.m72_codordem.value = ''; 
  }
}
function js_mostramatordem1(chave1,chave2){
  document.form1.m72_codordem.value = chave1;
  document.form1.m51_data.value = chave2;
  db_iframe_matordem.hide();
}
function js_pesquisam72_codnota(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empnota','func_empnota.php?funcao_js=parent.js_mostraempnota1|e69_codnota|e69_codnota','Pesquisa',true);
  }else{
     if(document.form1.m72_codnota.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empnota','func_empnota.php?pesquisa_chave='+document.form1.m72_codnota.value+'&funcao_js=parent.js_mostraempnota','Pesquisa',false);
     }else{
       document.form1.e69_codnota.value = ''; 
     }
  }
}
function js_mostraempnota(chave,erro){
  document.form1.e69_codnota.value = chave; 
  if(erro==true){ 
    document.form1.m72_codnota.focus(); 
    document.form1.m72_codnota.value = ''; 
  }
}
function js_mostraempnota1(chave1,chave2){
  document.form1.m72_codnota.value = chave1;
  document.form1.e69_codnota.value = chave2;
  db_iframe_empnota.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empnotaord','func_empnotaord.php?funcao_js=parent.js_preenchepesquisa|m72_codordem|m72_codnota','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_empnotaord.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>