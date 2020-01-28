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

//MODULO: protocolo
$clprocandamintand->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p88_codigo");
$clrotulo->label("p61_codandam");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp86_codtrans?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lp86_codtrans,"js_pesquisap86_codtrans(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p86_codtrans',10,$Ip86_codtrans,true,'text',$db_opcao," onchange='js_pesquisap86_codtrans(false);'")
?>
       <?
db_input('p88_codigo',10,$Ip88_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp86_codandam?>">
       <?
       db_ancora(@$Lp86_codandam,"js_pesquisap86_codandam(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p86_codandam',10,$Ip86_codandam,true,'text',$db_opcao," onchange='js_pesquisap86_codandam(false);'")
?>
       <?
db_input('p61_codandam',0,$Ip61_codandam,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap86_codtrans(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_proctransferint','func_proctransferint.php?funcao_js=parent.js_mostraproctransferint1|p88_codigo|p88_codigo','Pesquisa',true);
  }else{
     if(document.form1.p86_codtrans.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_proctransferint','func_proctransferint.php?pesquisa_chave='+document.form1.p86_codtrans.value+'&funcao_js=parent.js_mostraproctransferint','Pesquisa',false);
     }else{
       document.form1.p88_codigo.value = ''; 
     }
  }
}
function js_mostraproctransferint(chave,erro){
  document.form1.p88_codigo.value = chave; 
  if(erro==true){ 
    document.form1.p86_codtrans.focus(); 
    document.form1.p86_codtrans.value = ''; 
  }
}
function js_mostraproctransferint1(chave1,chave2){
  document.form1.p86_codtrans.value = chave1;
  document.form1.p88_codigo.value = chave2;
  db_iframe_proctransferint.hide();
}
function js_pesquisap86_codandam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procandam','func_procandam.php?funcao_js=parent.js_mostraprocandam1|p61_codandam|p61_codandam','Pesquisa',true);
  }else{
     if(document.form1.p86_codandam.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procandam','func_procandam.php?pesquisa_chave='+document.form1.p86_codandam.value+'&funcao_js=parent.js_mostraprocandam','Pesquisa',false);
     }else{
       document.form1.p61_codandam.value = ''; 
     }
  }
}
function js_mostraprocandam(chave,erro){
  document.form1.p61_codandam.value = chave; 
  if(erro==true){ 
    document.form1.p86_codandam.focus(); 
    document.form1.p86_codandam.value = ''; 
  }
}
function js_mostraprocandam1(chave1,chave2){
  document.form1.p86_codandam.value = chave1;
  document.form1.p61_codandam.value = chave2;
  db_iframe_procandam.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_procandamintand','func_procandamintand.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_procandamintand.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>