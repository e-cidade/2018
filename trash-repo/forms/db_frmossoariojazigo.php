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

//MODULO: cemiterio
$clossoariojazigo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm23_i_codigo");
$clrotulo->label("cm23_i_lotecemit");
$clrotulo->label("cm23_i_quadracemit");
$clrotulo->label("cm22_c_quadra");
$clrotulo->label("cm22_i_cemiterio");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<fieldset>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm25_i_codigo?>">
       <?=@$Lcm25_i_codigo?>
    </td>
    <td>
<?
db_input('cm25_i_codigo',10,$Icm25_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm25_i_numero?>">
       <strong>Numero:</strong>
    </td>
    <td>
<?
db_input('cm25_c_numero',10,@$cm25_c_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tcm25_i_lotecemit?>">
       <?
       db_ancora(@$Lcm25_i_lotecemit,"js_pesquisacm25_i_lotecemit(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('cm25_i_lotecemit',10,$Icm25_i_lotecemit,true,'hidden',$db_opcao," onchange='js_pesquisacm25_i_lotecemit(false);'")
?>
       <?
db_input('cm23_i_lotecemit',10,$Icm23_i_lotecemit,true,'text',3,'');
       ?>
    </td>
  </tr>


  <tr>
    <td nowrap title="<?=@$Tcm23_i_quadracemit?>">
       <?=@$Lcm23_i_quadracemit?>
    </td>
    <td>
       <?
         db_input('cm23_i_quadracemit',10,$Icm23_i_quadracemit,true,'hidden',3,"");
         db_input('cm22_c_quadra',10,$Icm22_c_quadra,true,'text',3,"");
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm22_i_cemiterio?>">
       <?=@$Lcm22_i_cemiterio?>
    </td>
    <td>
       <?
         db_input('cm22_i_cemiterio',10,$Icm22_i_cemiterio,true,'text',3,"");
         db_input('z01_nome',40,$Iz01_nome,true,'text',3,"");
       ?>
    </td>
  </tr>



  <tr>
    <td nowrap title="<?=@$Tcm25_f_comprimento?>">
       <?=@$Lcm25_f_comprimento?>
    </td>
    <td>
<?
db_input('cm25_f_comprimento',10,$Icm25_f_comprimento,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm25_f_largura?>">
       <?=@$Lcm25_f_largura?>
    </td>
    <td>
<?
db_input('cm25_f_largura',10,$Icm25_f_largura,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

<!--

-- espa�o para a altura do ossoariojazigo

 <tr>
   <td nowrap title="<?=@$Tcm25_f_altura?>">
    <?=@$Lcm25_f_altura?>
   </td>
   <td>
    <?
     db_input('cm25_f_altura',10,@$Icm25_f_altura,true,'text',$db_opcao,"")
    ?>
   </td>
  </tr>
-->  
  <tr>
    <td nowrap title="<?=@$Tcm25_c_tipo?>">
       <?=@$Lcm25_c_tipo?>
    </td>
    <td>
<?
$x = array('O'=>'Osso�rio','J'=>'Jazigo');
db_select('cm25_c_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </fieldset>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacm25_i_lotecemit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lotecemit','func_lotecemit.php?tp=O,J&funcao_js=parent.js_mostralotecemit1|cm23_i_codigo|cm23_i_lotecemit|cm23_i_quadracemit|cm22_c_quadra|cm22_i_cemiterio|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm25_i_lotecemit.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_lotecemit','func_lotecemit.php?tp=O,J&pesquisa_chave='+document.form1.cm25_i_lotecemit.value+'&funcao_js=parent.js_mostralotecemit','Pesquisa',false);
     }else{
       document.form1.cm23_i_lotecemit.value = '';
     }
  }
}
function js_mostralotecemit(chave,erro){
  document.form1.cm23_i_lotecemit.value = chave;
  if(erro==true){
    document.form1.cm25_i_lotecemit.focus();
    document.form1.cm25_i_lotecemit.value = '';
  }
}
function js_mostralotecemit1(chave1,chave2,chave3,chave4,chave5,chave6){
  document.form1.cm25_i_lotecemit.value = chave1;
  document.form1.cm23_i_lotecemit.value = chave2;
  document.form1.cm23_i_quadracemit.value = chave3;
  document.form1.cm22_c_quadra.value = chave4;
  document.form1.cm22_i_cemiterio.value = chave5;
  document.form1.z01_nome.value = chave6;
  db_iframe_lotecemit.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_ossoariojazigo','func_ossoariojazigo.php?funcao_js=parent.js_preenchepesquisa|cm25_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ossoariojazigo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>