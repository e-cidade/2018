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
$clparissqn->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k02_descr");
$clrotulo->label("k00_descr");
$clrotulo->label("q92_descr");
$clrotulo->label("k01_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq60_receit?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lq60_receit,"js_pesquisaq60_receit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q60_receit',10,$Iq60_receit,true,'text',$db_opcao," onchange='js_pesquisaq60_receit(false);'")
?>
       <?
db_input('k02_descr',15,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq60_tipo?>">
       <?
       db_ancora(@$Lq60_tipo,"js_pesquisaq60_tipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q60_tipo',4,$Iq60_tipo,true,'text',$db_opcao," onchange='js_pesquisaq60_tipo(false);'")
?>
       <?
db_input('k00_descr',40,$Ik00_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq60_aliq?>">
       <?=@$Lq60_aliq?>
    </td>
    <td> 
<?
db_input('q60_aliq',4,$Iq60_aliq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq60_codvencvar?>">
       <?
       db_ancora(@$Lq60_codvencvar,"js_pesquisaq60_codvencvar(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q60_codvencvar',4,$Iq60_codvencvar,true,'text',$db_opcao," onchange='js_pesquisaq60_codvencvar(false);'")
?>
       <?
db_input('q92_descr',40,$Iq92_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq60_histsemmov?>">
       <?
       db_ancora(@$Lq60_histsemmov,"js_pesquisaq60_histsemmov(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q60_histsemmov',4,$Iq60_histsemmov,true,'text',$db_opcao," onchange='js_pesquisaq60_histsemmov(false);'")
?>
       <?
db_input('k01_descr',20,$Ik01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq60_impcodativ?>">
       <?=@$Lq60_impcodativ?>
    </td>
    <td> 
<?
$x = array('t'=>'SIM','f'=>'NÃO');
db_select('q60_impcodativ',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq60_impobsativ?>">
       <?=@$Lq60_impobsativ?>
    </td>
    <td> 
<?
$x = array('t'=>'SIM','f'=>'NÃO');
db_select('q60_impobsativ',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq60_impdatas?>">
       <?=@$Lq60_impdatas?>
    </td>
    <td> 
<?
$x = array('t'=>'SIM','f'=>'NÃO');
db_select('q60_impdatas',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq60_impobsissqn?>">
       <?=@$Lq60_impobsissqn?>
    </td>
    <td> 
<?
$x = array('t'=>'SIM','f'=>'NÃO');
db_select('q60_impobsissqn',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq60_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_receit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.q60_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.q60_receit.focus(); 
    document.form1.q60_receit.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.q60_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisaq60_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_tipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.q60_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.q60_tipo.focus(); 
    document.form1.q60_tipo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.q60_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisaq60_codvencvar(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?funcao_js=parent.js_mostracadvencdesc1|q92_codigo|q92_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_codvencvar.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?pesquisa_chave='+document.form1.q60_codvencvar.value+'&funcao_js=parent.js_mostracadvencdesc','Pesquisa',false);
     }else{
       document.form1.q92_descr.value = ''; 
     }
  }
}
function js_mostracadvencdesc(chave,erro){
  document.form1.q92_descr.value = chave; 
  if(erro==true){ 
    document.form1.q60_codvencvar.focus(); 
    document.form1.q60_codvencvar.value = ''; 
  }
}
function js_mostracadvencdesc1(chave1,chave2){
  document.form1.q60_codvencvar.value = chave1;
  document.form1.q92_descr.value = chave2;
  db_iframe_cadvencdesc.hide();
}
function js_pesquisaq60_histsemmov(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_histsemmov.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.q60_histsemmov.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false);
     }else{
       document.form1.k01_descr.value = ''; 
     }
  }
}
function js_mostrahistcalc(chave,erro){
  document.form1.k01_descr.value = chave; 
  if(erro==true){ 
    document.form1.q60_histsemmov.focus(); 
    document.form1.q60_histsemmov.value = ''; 
  }
}
function js_mostrahistcalc1(chave1,chave2){
  document.form1.q60_histsemmov.value = chave1;
  document.form1.k01_descr.value = chave2;
  db_iframe_histcalc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_parissqn','func_parissqn.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_parissqn.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>