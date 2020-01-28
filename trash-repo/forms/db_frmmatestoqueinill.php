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

//MODULO: material
$clmatestoqueinill->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m86_codigo");
$clrotulo->label("m80_matestoqueitem");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tm87_matestoqueinil?>">
       <?
       db_ancora(@$Lm87_matestoqueinil,"js_pesquisam87_matestoqueinil(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('m87_matestoqueinil',10,$Im87_matestoqueinil,true,'text',3," onchange='js_pesquisam87_matestoqueinil(false);'")
?>
       <?
db_input('m86_codigo',10,$Im86_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm87_matestoqueini?>">
       <?
       db_ancora(@$Lm87_matestoqueini,"js_pesquisam87_matestoqueini(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('m87_matestoqueini',10,$Im87_matestoqueini,true,'text',$db_opcao," onchange='js_pesquisam87_matestoqueini(false);'")
?>
       <?
db_input('m80_matestoqueitem',10,$Im80_matestoqueitem,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisam87_matestoqueinil(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueinil','func_matestoqueinil.php?funcao_js=parent.js_mostramatestoqueinil1|m86_codigo|m86_codigo','Pesquisa',true);
  }else{
     if(document.form1.m87_matestoqueinil.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueinil','func_matestoqueinil.php?pesquisa_chave='+document.form1.m87_matestoqueinil.value+'&funcao_js=parent.js_mostramatestoqueinil','Pesquisa',false);
     }else{
       document.form1.m86_codigo.value = ''; 
     }
  }
}
function js_mostramatestoqueinil(chave,erro){
  document.form1.m86_codigo.value = chave; 
  if(erro==true){ 
    document.form1.m87_matestoqueinil.focus(); 
    document.form1.m87_matestoqueinil.value = ''; 
  }
}
function js_mostramatestoqueinil1(chave1,chave2){
  document.form1.m87_matestoqueinil.value = chave1;
  document.form1.m86_codigo.value = chave2;
  db_iframe_matestoqueinil.hide();
}
function js_pesquisam87_matestoqueini(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueini','func_matestoqueini.php?funcao_js=parent.js_mostramatestoqueini1|m80_codigo|m80_matestoqueitem','Pesquisa',true);
  }else{
     if(document.form1.m87_matestoqueini.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueini','func_matestoqueini.php?pesquisa_chave='+document.form1.m87_matestoqueini.value+'&funcao_js=parent.js_mostramatestoqueini','Pesquisa',false);
     }else{
       document.form1.m80_matestoqueitem.value = ''; 
     }
  }
}
function js_mostramatestoqueini(chave,erro){
  document.form1.m80_matestoqueitem.value = chave; 
  if(erro==true){ 
    document.form1.m87_matestoqueini.focus(); 
    document.form1.m87_matestoqueini.value = ''; 
  }
}
function js_mostramatestoqueini1(chave1,chave2){
  document.form1.m87_matestoqueini.value = chave1;
  document.form1.m80_matestoqueitem.value = chave2;
  db_iframe_matestoqueini.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueinill','func_matestoqueinill.php?funcao_js=parent.js_preenchepesquisa|m87_matestoqueinil','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_matestoqueinill.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>