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

//MODULO: fiscal
$clautofiscal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y50_codauto");
$clrotulo->label("y30_data");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty51_codauto?>">
       <?
       db_ancora(@$Ly51_codauto,"js_pesquisay51_codauto(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y51_codauto',10,$Iy51_codauto,true,'text',$db_opcao," onchange='js_pesquisay51_codauto(false);'")
?>
       <?
db_input('y50_codauto',10,$Iy50_codauto,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty51_codnoti?>">
       <?
       db_ancora(@$Ly51_codnoti,"js_pesquisay51_codnoti(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y51_codnoti',20,$Iy51_codnoti,true,'text',$db_opcao," onchange='js_pesquisay51_codnoti(false);'")
?>
       <?
db_input('y30_data',10,$Iy30_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay51_codauto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_auto','func_auto.php?funcao_js=parent.js_mostraauto1|y50_codauto|y50_codauto','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_auto','func_auto.php?pesquisa_chave='+document.form1.y51_codauto.value+'&funcao_js=parent.js_mostraauto','Pesquisa',false);
  }
}
function js_mostraauto(chave,erro){
  document.form1.y50_codauto.value = chave; 
  if(erro==true){ 
    document.form1.y51_codauto.focus(); 
    document.form1.y51_codauto.value = ''; 
  }
}
function js_mostraauto1(chave1,chave2){
  document.form1.y51_codauto.value = chave1;
  document.form1.y50_codauto.value = chave2;
  db_iframe_auto.hide();
}
function js_pesquisay51_codnoti(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_fiscal','func_fiscal.php?funcao_js=parent.js_mostrafiscal1|y30_codnoti|y30_data','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_fiscal','func_fiscal.php?pesquisa_chave='+document.form1.y51_codnoti.value+'&funcao_js=parent.js_mostrafiscal','Pesquisa',false);
  }
}
function js_mostrafiscal(chave,erro){
  document.form1.y30_data.value = chave; 
  if(erro==true){ 
    document.form1.y51_codnoti.focus(); 
    document.form1.y51_codnoti.value = ''; 
  }
}
function js_mostrafiscal1(chave1,chave2){
  document.form1.y51_codnoti.value = chave1;
  document.form1.y30_data.value = chave2;
  db_iframe_fiscal.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_autofiscal','func_autofiscal.php?funcao_js=parent.js_preenchepesquisa|y51_codauto','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_autofiscal.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>