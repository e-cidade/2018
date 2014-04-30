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

//MODULO: issqn
$clvarfixval->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("i01_descr");
$clrotulo->label("q33_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq34_codigo?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lq34_codigo,"js_pesquisaq34_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q34_codigo',8,$Iq34_codigo,true,'text',$db_opcao," onchange='js_pesquisaq34_codigo(false);'")
?>
       <?
db_input('q33_codigo',8,$Iq33_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq34_numpar?>">
       <?=@$Lq34_numpar?>
    </td>
    <td> 
<?
db_input('q34_numpar',10,$Iq34_numpar,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq34_mes?>">
       <?=@$Lq34_mes?>
    </td>
    <td> 
<?
db_input('q34_mes',6,$Iq34_mes,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq34_ano?>">
       <?=@$Lq34_ano?>
    </td>
    <td> 
<?
db_input('q34_ano',4,$Iq34_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq34_valor?>">
       <?=@$Lq34_valor?>
    </td>
    <td> 
<?
db_input('q34_valor',15,$Iq34_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq34_inflat?>">
       <?
       db_ancora(@$Lq34_inflat,"js_pesquisaq34_inflat(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q34_inflat',5,$Iq34_inflat,true,'text',$db_opcao," onchange='js_pesquisaq34_inflat(false);'")
?>
       <?
db_input('i01_descr',40,$Ii01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq34_dtval?>">
       <?=@$Lq34_dtval?>
    </td>
    <td> 
<?
db_inputdata('q34_dtval',@$q34_dtval_dia,@$q34_dtval_mes,@$q34_dtval_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq34_inflat(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_inflan','func_inflan.php?funcao_js=parent.js_mostrainflan1|i01_codigo|i01_descr','Pesquisa',true);
  }else{
     if(document.form1.q34_inflat.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_inflan','func_inflan.php?pesquisa_chave='+document.form1.q34_inflat.value+'&funcao_js=parent.js_mostrainflan','Pesquisa',false);
     }else{
       document.form1.i01_descr.value = ''; 
     }
  }
}
function js_mostrainflan(chave,erro){
  document.form1.i01_descr.value = chave; 
  if(erro==true){ 
    document.form1.q34_inflat.focus(); 
    document.form1.q34_inflat.value = ''; 
  }
}
function js_mostrainflan1(chave1,chave2){
  document.form1.q34_inflat.value = chave1;
  document.form1.i01_descr.value = chave2;
  db_iframe_inflan.hide();
}
function js_pesquisaq34_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_varfix','func_varfix.php?funcao_js=parent.js_mostravarfix1|q33_codigo|q33_codigo','Pesquisa',true);
  }else{
     if(document.form1.q34_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_varfix','func_varfix.php?pesquisa_chave='+document.form1.q34_codigo.value+'&funcao_js=parent.js_mostravarfix','Pesquisa',false);
     }else{
       document.form1.q33_codigo.value = ''; 
     }
  }
}
function js_mostravarfix(chave,erro){
  document.form1.q33_codigo.value = chave; 
  if(erro==true){ 
    document.form1.q34_codigo.focus(); 
    document.form1.q34_codigo.value = ''; 
  }
}
function js_mostravarfix1(chave1,chave2){
  document.form1.q34_codigo.value = chave1;
  document.form1.q33_codigo.value = chave2;
  db_iframe_varfix.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_varfixval','func_varfixval.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_varfixval.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>