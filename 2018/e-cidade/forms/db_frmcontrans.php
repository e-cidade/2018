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
$clcontrans->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c53_descr");
      if($db_opcao==1){
 	   $db_action="con1_contrans004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="con1_contrans005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="con1_contrans006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc45_anousu?>">
       <?=@$Lc45_anousu?>
    </td>
    <td> 
<?
$c45_anousu = db_getsession('DB_anousu');
db_input('c45_anousu',4,$Ic45_anousu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc45_coddoc?>">
       <?
       db_ancora(@$Lc45_coddoc,"js_pesquisac45_coddoc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c45_coddoc',4,$Ic45_coddoc,true,'text',$db_opcao," onchange='js_pesquisac45_coddoc(false);'")
?>
       <?
db_input('c53_descr',50,$Ic53_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc45_hist?>">
       <?=@$Lc45_hist?>
    </td>
    <td> 
<?
db_textarea('c45_hist',4,60,$Ic45_hist,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac45_coddoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_contrans','db_iframe_conhistdoc','func_conhistdoc.php?funcao_js=parent.js_mostraconhistdoc1|c53_coddoc|c53_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.c45_coddoc.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_contrans','db_iframe_conhistdoc','func_conhistdoc.php?pesquisa_chave='+document.form1.c45_coddoc.value+'&funcao_js=parent.js_mostraconhistdoc','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.c53_descr.value = ''; 
     }
  }
}
function js_mostraconhistdoc(chave,erro){
  document.form1.c53_descr.value = chave; 
  if(erro==true){ 
    document.form1.c45_coddoc.focus(); 
    document.form1.c45_coddoc.value = ''; 
  }
}
function js_mostraconhistdoc1(chave1,chave2){
  document.form1.c45_coddoc.value = chave1;
  document.form1.c53_descr.value = chave2;
  db_iframe_conhistdoc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_contrans','db_iframe_contrans','func_contrans.php?funcao_js=parent.js_preenchepesquisa|c45_anousu|c45_coddoc','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_contrans.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>