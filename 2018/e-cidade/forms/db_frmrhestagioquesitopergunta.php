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

//MODULO: recursoshumanos
$clrhestagioquesitopergunta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h51_descr");
      if($db_opcao==1){
 	   $db_action="rec1_rhestagioquesitopergunta004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="rec1_rhestagioquesitopergunta005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="rec1_rhestagioquesitopergunta006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table>
<tr>
 <td>
 <fieldset><legend><b>Perguntas</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th53_sequencial?>">
       <?=@$Lh53_sequencial?>
    </td>
    <td> 
<?
db_input('h53_sequencial',10,$Ih53_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th53_rhestagioquesito?>">
       <?
       db_ancora(@$Lh53_rhestagioquesito,"js_pesquisah53_rhestagioquesito(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
 db_input('h53_rhestagioquesito',10,$Ih53_rhestagioquesito,true,'text',$db_opcao," onchange='js_pesquisah53_rhestagioquesito(false);'")
?>
       <?
db_input('h51_descr',30,$Ih51_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th53_descr?>">
       <?=@$Lh53_descr?>
    </td>
    <td> 
<?
db_textarea('h53_descr',6,60,$Ih53_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisah53_rhestagioquesito(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagioquesitopergunta','db_iframe_rhestagioquesito','func_rhestagioquesito.php?funcao_js=parent.js_mostrarhestagioquesito1|h51_sequencial|h51_descr','Pesquisa',true,0);
  }else{
     if(document.form1.h53_rhestagioquesito.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagioquesitopergunta','db_iframe_rhestagioquesito','func_rhestagioquesito.php?pesquisa_chave='+document.form1.h53_rhestagioquesito.value+'&funcao_js=parent.js_mostrarhestagioquesito','Pesquisa',false,'0');
     }else{
       document.form1.h51_descr.value = ''; 
     }
  }
}
function js_mostrarhestagioquesito(chave,erro){
  document.form1.h51_descr.value = chave; 
  if(erro==true){ 
    document.form1.h53_rhestagioquesito.focus(); 
    document.form1.h53_rhestagioquesito.value = ''; 
  }
}
function js_mostrarhestagioquesito1(chave1,chave2){
  document.form1.h53_rhestagioquesito.value = chave1;
  document.form1.h51_descr.value = chave2;
  db_iframe_rhestagioquesito.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_rhestagioquesitopergunta','db_iframe_rhestagioquesitopergunta','func_rhestagioquesitopergunta.php?funcao_js=parent.js_preenchepesquisa|h53_sequencial','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_rhestagioquesitopergunta.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>