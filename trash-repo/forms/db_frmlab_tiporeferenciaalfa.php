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

//MODULO: Laboratório
$cllab_tiporeferenciaalfa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la27_i_codigo");
$clrotulo->label("la28_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla29_i_codigo?>">
       <?=@$Lla29_i_codigo?>
    </td>
    <td> 
<?
db_input('la29_i_codigo',10,$Ila29_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla29_i_valorref?>">
       <?
       db_ancora(@$Lla29_i_valorref,"js_pesquisala29_i_valorref(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la29_i_valorref',10,$Ila29_i_valorref,true,'text',$db_opcao," onchange='js_pesquisala29_i_valorref(false);'")
?>
       <?
db_input('la27_i_codigo',10,$Ila27_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla29_c_fixo?>">
       <?=@$Lla29_c_fixo?>
    </td>
    <td> 
<?
db_input('la29_c_fixo',100,$Ila29_c_fixo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla29_i_valorrefsel?>">
       <?
       db_ancora(@$Lla29_i_valorrefsel,"js_pesquisala29_i_valorrefsel(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la29_i_valorrefsel',10,$Ila29_i_valorrefsel,true,'text',$db_opcao," onchange='js_pesquisala29_i_valorrefsel(false);'")
?>
       <?
db_input('la28_i_codigo',10,$Ila28_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisala29_i_valorref(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_valorreferencia','func_lab_valorreferencia.php?funcao_js=parent.js_mostralab_valorreferencia1|la27_i_codigo|la27_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la29_i_valorref.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_valorreferencia','func_lab_valorreferencia.php?pesquisa_chave='+document.form1.la29_i_valorref.value+'&funcao_js=parent.js_mostralab_valorreferencia','Pesquisa',false);
     }else{
       document.form1.la27_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_valorreferencia(chave,erro){
  document.form1.la27_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.la29_i_valorref.focus(); 
    document.form1.la29_i_valorref.value = ''; 
  }
}
function js_mostralab_valorreferencia1(chave1,chave2){
  document.form1.la29_i_valorref.value = chave1;
  document.form1.la27_i_codigo.value = chave2;
  db_iframe_lab_valorreferencia.hide();
}
function js_pesquisala29_i_valorrefsel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_valorreferenciasel','func_lab_valorreferenciasel.php?funcao_js=parent.js_mostralab_valorreferenciasel1|la28_i_codigo|la28_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la29_i_valorrefsel.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_valorreferenciasel','func_lab_valorreferenciasel.php?pesquisa_chave='+document.form1.la29_i_valorrefsel.value+'&funcao_js=parent.js_mostralab_valorreferenciasel','Pesquisa',false);
     }else{
       document.form1.la28_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_valorreferenciasel(chave,erro){
  document.form1.la28_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.la29_i_valorrefsel.focus(); 
    document.form1.la29_i_valorrefsel.value = ''; 
  }
}
function js_mostralab_valorreferenciasel1(chave1,chave2){
  document.form1.la29_i_valorrefsel.value = chave1;
  document.form1.la28_i_codigo.value = chave2;
  db_iframe_lab_valorreferenciasel.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_tiporeferenciaalfa','func_lab_tiporeferenciaalfa.php?funcao_js=parent.js_preenchepesquisa|la29_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_tiporeferenciaalfa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>