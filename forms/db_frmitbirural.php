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

//MODULO: itbi
$clitbirural->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it01_guia");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tit18_guia?>">
       <?
       db_ancora(@$Lit18_guia,"js_pesquisait18_guia(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('it18_guia',10,$Iit18_guia,true,'text',$db_opcao," onchange='js_pesquisait18_guia(false);'")
?>
       <?
db_input('it01_guia',10,$Iit01_guia,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit18_area?>">
       <?=@$Lit18_area?>
    </td>
    <td> 
<?
db_input('it18_area',20,$Iit18_area,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit18_areatrans?>">
       <?=@$Lit18_areatrans?>
    </td>
    <td> 
<?
db_input('it18_areatrans',20,$Iit18_areatrans,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit18_frente?>">
       <?=@$Lit18_frente?>
    </td>
    <td> 
<?
db_input('it18_frente',20,$Iit18_frente,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit18_fundos?>">
       <?=@$Lit18_fundos?>
    </td>
    <td> 
<?
db_input('it18_fundos',20,$Iit18_fundos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit18_prof?>">
       <?=@$Lit18_prof?>
    </td>
    <td> 
<?
db_input('it18_prof',20,$Iit18_prof,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisait18_guia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbi.php?funcao_js=parent.js_mostraitbi1|it01_guia|it01_guia','Pesquisa',true);
  }else{
     if(document.form1.it18_guia.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbi.php?pesquisa_chave='+document.form1.it18_guia.value+'&funcao_js=parent.js_mostraitbi','Pesquisa',false);
     }else{
       document.form1.it01_guia.value = ''; 
     }
  }
}
function js_mostraitbi(chave,erro){
  document.form1.it01_guia.value = chave; 
  if(erro==true){ 
    document.form1.it18_guia.focus(); 
    document.form1.it18_guia.value = ''; 
  }
}
function js_mostraitbi1(chave1,chave2){
  document.form1.it18_guia.value = chave1;
  document.form1.it01_guia.value = chave2;
  db_iframe_itbi.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_itbirural','func_itbirural.php?funcao_js=parent.js_preenchepesquisa|it18_guia','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_itbirural.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>