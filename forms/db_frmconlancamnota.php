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

//MODULO: contabilidade
$clconlancamnota->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c70_anousu");
$clrotulo->label("e69_codnota");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc66_codlan?>">
       <?
       db_ancora(@$Lc66_codlan,"js_pesquisac66_codlan(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c66_codlan',8,$Ic66_codlan,true,'text',$db_opcao," onchange='js_pesquisac66_codlan(false);'")
?>
       <?
db_input('c70_anousu',4,$Ic70_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc66_codnota?>">
       <?
       db_ancora(@$Lc66_codnota,"js_pesquisac66_codnota(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c66_codnota',6,$Ic66_codnota,true,'text',$db_opcao," onchange='js_pesquisac66_codnota(false);'")
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
function js_pesquisac66_codlan(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancam','func_conlancam.php?funcao_js=parent.js_mostraconlancam1|c70_codlan|c70_anousu','Pesquisa',true);
  }else{
     if(document.form1.c66_codlan.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conlancam','func_conlancam.php?pesquisa_chave='+document.form1.c66_codlan.value+'&funcao_js=parent.js_mostraconlancam','Pesquisa',false);
     }else{
       document.form1.c70_anousu.value = ''; 
     }
  }
}
function js_mostraconlancam(chave,erro){
  document.form1.c70_anousu.value = chave; 
  if(erro==true){ 
    document.form1.c66_codlan.focus(); 
    document.form1.c66_codlan.value = ''; 
  }
}
function js_mostraconlancam1(chave1,chave2){
  document.form1.c66_codlan.value = chave1;
  document.form1.c70_anousu.value = chave2;
  db_iframe_conlancam.hide();
}
function js_pesquisac66_codnota(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empnota','func_empnota.php?funcao_js=parent.js_mostraempnota1|e69_codnota|e69_codnota','Pesquisa',true);
  }else{
     if(document.form1.c66_codnota.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empnota','func_empnota.php?pesquisa_chave='+document.form1.c66_codnota.value+'&funcao_js=parent.js_mostraempnota','Pesquisa',false);
     }else{
       document.form1.e69_codnota.value = ''; 
     }
  }
}
function js_mostraempnota(chave,erro){
  document.form1.e69_codnota.value = chave; 
  if(erro==true){ 
    document.form1.c66_codnota.focus(); 
    document.form1.c66_codnota.value = ''; 
  }
}
function js_mostraempnota1(chave1,chave2){
  document.form1.c66_codnota.value = chave1;
  document.form1.e69_codnota.value = chave2;
  db_iframe_empnota.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conlancamnota','func_conlancamnota.php?funcao_js=parent.js_preenchepesquisa|c66_codlan|c66_codnota','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_conlancamnota.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>