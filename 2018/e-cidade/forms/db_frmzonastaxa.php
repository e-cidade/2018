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

//MODULO: cadastro
$clzonastaxa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j50_descr");
$clrotulo->label("k02_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj57_zona?>">
       <?
       db_ancora(@$Lj57_zona,"js_pesquisaj57_zona(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j57_zona',10,$Ij57_zona,true,'text',$db_opcao," onchange='js_pesquisaj57_zona(false);'")
?>
       <?
db_input('j50_descr',40,$Ij50_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj57_receit?>">
       <?
       db_ancora(@$Lj57_receit,"js_pesquisaj57_receit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j57_receit',4,$Ij57_receit,true,'text',$db_opcao," onchange='js_pesquisaj57_receit(false);'")
?>
       <?
db_input('k02_descr',15,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj57_anousu?>">
       <?=@$Lj57_anousu?>
    </td>
    <td> 
<?
$j57_anousu = db_getsession('DB_anousu');
db_input('j57_anousu',4,$Ij57_anousu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj57_valor?>">
       <?=@$Lj57_valor?>
    </td>
    <td> 
<?
db_input('j57_valor',15,$Ij57_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj57_zona(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_zonas','func_zonas.php?funcao_js=parent.js_mostrazonas1|j50_zona|j50_descr','Pesquisa',true);
  }else{
     if(document.form1.j57_zona.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_zonas','func_zonas.php?pesquisa_chave='+document.form1.j57_zona.value+'&funcao_js=parent.js_mostrazonas','Pesquisa',false);
     }else{
       document.form1.j50_descr.value = ''; 
     }
  }
}
function js_mostrazonas(chave,erro){
  document.form1.j50_descr.value = chave; 
  if(erro==true){ 
    document.form1.j57_zona.focus(); 
    document.form1.j57_zona.value = ''; 
  }
}
function js_mostrazonas1(chave1,chave2){
  document.form1.j57_zona.value = chave1;
  document.form1.j50_descr.value = chave2;
  db_iframe_zonas.hide();
}
function js_pesquisaj57_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.j57_receit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.j57_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.j57_receit.focus(); 
    document.form1.j57_receit.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.j57_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_zonastaxa','func_zonastaxa.php?funcao_js=parent.js_preenchepesquisa|j57_zona|j57_receit|j57_anousu','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_zonastaxa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>