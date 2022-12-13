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
$cltabativtipcalc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q07_inscr");
$clrotulo->label("q07_inscr");
$clrotulo->label("q07_inscr");
$clrotulo->label("q07_inscr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq11_inscr?>">
       <?
       db_ancora(@$Lq11_inscr,"js_pesquisaq11_inscr(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q11_inscr',4,$Iq11_inscr,true,'text',$db_opcao," onchange='js_pesquisaq11_inscr(false);'");
db_input('q07_inscr',4,$Iq07_inscr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq11_seq?>">
       <?
       db_ancora(@$Lq11_seq,"js_pesquisaq11_seq(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q11_seq',4,$Iq11_seq,true,'text',$db_opcao," onchange='js_pesquisaq11_seq(false);'")
?>
       <?
db_input('q07_inscr',4,$Iq07_inscr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq11_tipcalc?>">
       <?=@$Lq11_tipcalc?>
    </td>
    <td> 
<?
db_input('q11_tipcalc',4,$Iq11_tipcalc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq11_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabativ','func_tabativ.php?funcao_js=parent.js_mostratabativ1|q07_inscr|q07_inscr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_tabativ','func_tabativ.php?pesquisa_chave='+document.form1.q11_inscr.value+'&funcao_js=parent.js_mostratabativ','Pesquisa',false);
  }
}
function js_mostratabativ(chave,erro){
  document.form1.q07_inscr.value = chave; 
  if(erro==true){ 
    document.form1.q11_inscr.focus(); 
    document.form1.q11_inscr.value = ''; 
  }
}
function js_mostratabativ1(chave1,chave2){
  document.form1.q11_inscr.value = chave1;
  document.form1.q07_inscr.value = chave2;
  db_iframe_tabativ.hide();
}
function js_pesquisaq11_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabativ','func_tabativ.php?funcao_js=parent.js_mostratabativ1|q07_seq|q07_inscr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_tabativ','func_tabativ.php?pesquisa_chave='+document.form1.q11_inscr.value+'&funcao_js=parent.js_mostratabativ','Pesquisa',false);
  }
}
function js_mostratabativ(chave,erro){
  document.form1.q07_inscr.value = chave; 
  if(erro==true){ 
    document.form1.q11_inscr.focus(); 
    document.form1.q11_inscr.value = ''; 
  }
}
function js_mostratabativ1(chave1,chave2){
  document.form1.q11_inscr.value = chave1;
  document.form1.q07_inscr.value = chave2;
  db_iframe_tabativ.hide();
}
function js_pesquisaq11_seq(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabativ','func_tabativ.php?funcao_js=parent.js_mostratabativ1|q07_inscr|q07_inscr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_tabativ','func_tabativ.php?pesquisa_chave='+document.form1.q11_seq.value+'&funcao_js=parent.js_mostratabativ','Pesquisa',false);
  }
}
function js_mostratabativ(chave,erro){
  document.form1.q07_inscr.value = chave; 
  if(erro==true){ 
    document.form1.q11_seq.focus(); 
    document.form1.q11_seq.value = ''; 
  }
}
function js_mostratabativ1(chave1,chave2){
  document.form1.q11_seq.value = chave1;
  document.form1.q07_inscr.value = chave2;
  db_iframe_tabativ.hide();
}
function js_pesquisaq11_seq(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabativ','func_tabativ.php?funcao_js=parent.js_mostratabativ1|q07_seq|q07_inscr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_tabativ','func_tabativ.php?pesquisa_chave='+document.form1.q11_seq.value+'&funcao_js=parent.js_mostratabativ','Pesquisa',false);
  }
}
function js_mostratabativ(chave,erro){
  document.form1.q07_inscr.value = chave; 
  if(erro==true){ 
    document.form1.q11_seq.focus(); 
    document.form1.q11_seq.value = ''; 
  }
}
function js_mostratabativ1(chave1,chave2){
  document.form1.q11_seq.value = chave1;
  document.form1.q07_inscr.value = chave2;
  db_iframe_tabativ.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tabativtipcalc','func_tabativtipcalc.php?funcao_js=parent.js_preenchepesquisa|q11_inscr|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_tabativtipcalc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
+"&chavepesquisa1="+chave1}
</script>