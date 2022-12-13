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
$clorcparamfontes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o42_descrrel");
$clrotulo->label("o57_fonte");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To43_anousu?>">
       <?=@$Lo43_anousu?>
    </td>
    <td> 
<?
$o43_anousu = db_getsession('DB_anousu');
db_input('o43_anousu',4,$Io43_anousu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To43_codparrel?>">
       <?
       db_ancora(@$Lo43_codparrel,"js_pesquisao43_codparrel(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o43_codparrel',8,$Io43_codparrel,true,'text',$db_opcao," onchange='js_pesquisao43_codparrel(false);'")
?>
       <?
db_input('o42_descrrel',40,$Io42_descrrel,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To43_codfon?>">
       <?
       db_ancora(@$Lo43_codfon,"js_pesquisao43_codfon(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o43_codfon',6,$Io43_codfon,true,'text',$db_opcao," onchange='js_pesquisao43_codfon(false);'")
?>
       <?
db_input('o57_fonte',15,$Io57_fonte,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao43_codparrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?funcao_js=parent.js_mostraorcparamrel1|o42_codparrel|o42_descrrel','Pesquisa',true);
  }else{
     if(document.form1.o43_codparrel.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?pesquisa_chave='+document.form1.o43_codparrel.value+'&funcao_js=parent.js_mostraorcparamrel','Pesquisa',false);
     }else{
       document.form1.o42_descrrel.value = ''; 
     }
  }
}
function js_mostraorcparamrel(chave,erro){
  document.form1.o42_descrrel.value = chave; 
  if(erro==true){ 
    document.form1.o43_codparrel.focus(); 
    document.form1.o43_codparrel.value = ''; 
  }
}
function js_mostraorcparamrel1(chave1,chave2){
  document.form1.o43_codparrel.value = chave1;
  document.form1.o42_descrrel.value = chave2;
  db_iframe_orcparamrel.hide();
}
function js_pesquisao43_codfon(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcfontes','func_orcfontes.php?funcao_js=parent.js_mostraorcfontes1|o57_codfon|o57_fonte','Pesquisa',true);
  }else{
     if(document.form1.o43_codfon.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcfontes','func_orcfontes.php?pesquisa_chave='+document.form1.o43_codfon.value+'&funcao_js=parent.js_mostraorcfontes','Pesquisa',false);
     }else{
       document.form1.o57_fonte.value = ''; 
     }
  }
}
function js_mostraorcfontes(chave,erro){
  document.form1.o57_fonte.value = chave; 
  if(erro==true){ 
    document.form1.o43_codfon.focus(); 
    document.form1.o43_codfon.value = ''; 
  }
}
function js_mostraorcfontes1(chave1,chave2){
  document.form1.o43_codfon.value = chave1;
  document.form1.o57_fonte.value = chave2;
  db_iframe_orcfontes.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcparamfontes','func_orcparamfontes.php?funcao_js=parent.js_preenchepesquisa|o43_codparrel|o43_codfon|o43_anousu','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_orcparamfontes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>