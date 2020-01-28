<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: itbi
$clitbiconstrpadraoconstrutivo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it08_guia");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tit34_codigo?>">
       <?
       db_ancora(@$Lit34_codigo,"js_pesquisait34_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('it34_codigo',5,$Iit34_codigo,true,'text',$db_opcao," onchange='js_pesquisait34_codigo(false);'")
?>
       <?
db_input('it08_guia',10,$Iit08_guia,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit34_caract?>">
       <?=@$Lit34_caract?>
    </td>
    <td> 
<?
db_input('it34_caract',5,$Iit34_caract,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisait34_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_itbiconstr','func_itbiconstr.php?funcao_js=parent.js_mostraitbiconstr1|it08_codigo|it08_guia','Pesquisa',true);
  }else{
     if(document.form1.it34_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_itbiconstr','func_itbiconstr.php?pesquisa_chave='+document.form1.it34_codigo.value+'&funcao_js=parent.js_mostraitbiconstr','Pesquisa',false);
     }else{
       document.form1.it08_guia.value = ''; 
     }
  }
}
function js_mostraitbiconstr(chave,erro){
  document.form1.it08_guia.value = chave; 
  if(erro==true){ 
    document.form1.it34_codigo.focus(); 
    document.form1.it34_codigo.value = ''; 
  }
}
function js_mostraitbiconstr1(chave1,chave2){
  document.form1.it34_codigo.value = chave1;
  document.form1.it08_guia.value = chave2;
  db_iframe_itbiconstr.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_itbiconstrpadraoconstrutivo','func_itbiconstrpadraoconstrutivo.php?funcao_js=parent.js_preenchepesquisa|it34_codigo|it34_caract','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_itbiconstrpadraoconstrutivo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>