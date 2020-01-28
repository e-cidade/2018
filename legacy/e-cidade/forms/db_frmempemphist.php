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
$clempemphist->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e60_numemp");
$clrotulo->label("e40_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te63_numemp?>">
       <?
       db_ancora(@$Le63_numemp,"js_pesquisae63_numemp(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e63_numemp',8,$Ie63_numemp,true,'text',$db_opcao," onchange='js_pesquisae63_numemp(false);'")
?>
       <?
db_input('e60_numemp',8,$Ie60_numemp,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te63_codhist?>">
       <?
       db_ancora(@$Le63_codhist,"js_pesquisae63_codhist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e63_codhist',6,$Ie63_codhist,true,'text',$db_opcao," onchange='js_pesquisae63_codhist(false);'")
?>
       <?
db_input('e40_descr',60,$Ie40_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisae63_numemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp|e60_numemp','Pesquisa',true);
  }else{
     if(document.form1.e63_numemp.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e63_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
     }else{
       document.form1.e60_numemp.value = ''; 
     }
  }
}
function js_mostraempempenho(chave,erro){
  document.form1.e60_numemp.value = chave; 
  if(erro==true){ 
    document.form1.e63_numemp.focus(); 
    document.form1.e63_numemp.value = ''; 
  }
}
function js_mostraempempenho1(chave1,chave2){
  document.form1.e63_numemp.value = chave1;
  document.form1.e60_numemp.value = chave2;
  db_iframe_empempenho.hide();
}
function js_pesquisae63_codhist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emphist','func_emphist.php?funcao_js=parent.js_mostraemphist1|e40_codhist|e40_descr','Pesquisa',true);
  }else{
     if(document.form1.e63_codhist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_emphist','func_emphist.php?pesquisa_chave='+document.form1.e63_codhist.value+'&funcao_js=parent.js_mostraemphist','Pesquisa',false);
     }else{
       document.form1.e40_descr.value = ''; 
     }
  }
}
function js_mostraemphist(chave,erro){
  document.form1.e40_descr.value = chave; 
  if(erro==true){ 
    document.form1.e63_codhist.focus(); 
    document.form1.e63_codhist.value = ''; 
  }
}
function js_mostraemphist1(chave1,chave2){
  document.form1.e63_codhist.value = chave1;
  document.form1.e40_descr.value = chave2;
  db_iframe_emphist.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empemphist','func_empemphist.php?funcao_js=parent.js_preenchepesquisa|e63_numemp','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empemphist.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>