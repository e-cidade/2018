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

//MODULO: pessoal
$clcfpess->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o56_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <?
  $r11_anousu = db_anofolha();
  $r11_mesusu = db_mesfolha();
  db_input('r11_anousu',4,$Ir11_anousu,true,'hidden',$db_opcao,"");
  db_input('r11_mesusu',2,$Ir11_mesusu,true,'hidden',$db_opcao,"");
  ?>
  <tr>
    <td nowrap align="right" title="<?=@$Tr11_eleina?>">
      <?
      db_ancora(@$Lr11_eleina,"js_pesquisar11_eleina(true)",1);
      ?>
    </td>
    <td> 
      <?
//      db_input('r11_eleina',12,$Ir11_eleina,true,'text',$db_opcao);
      db_input('r11_eleina',12,$Ir11_eleina,true,'text',$db_opcao,"onchange='js_pesquisar11_eleina(false)'");
      db_input("o56_descr",30,$Io56_descr,true,"text",3,"","o56_descr1");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap align="right" title="<?=@$Tr11_elepen?>">
      <?
      db_ancora(@$Lr11_elepen,"js_pesquisar11_elepen(true)",1);
      ?>
    </td>
    <td> 
      <?
//      db_input('r11_elepen',12,$Ir11_elepen,true,'text',$db_opcao);
      db_input('r11_elepen',12,$Ir11_elepen,true,'text',$db_opcao,"onchange='js_pesquisar11_elepen(false)'");
      db_input("o56_descr",30,$Io56_descr,true,"text",3,"","o56_descr2");
      ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisar11_eleina(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraeleina1|o56_elemento|o56_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_eleina.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.r11_eleina.value+'&funcao_js=parent.js_mostraeleina','Pesquisa',true);
    }else{
      document.form1.o56_descr1.value = ''; 
    }
  }
}
function js_mostraeleina(chave,erro){
  document.form1.o56_descr1.value = chave; 
  if(erro==true){ 
    document.form1.r11_eleina.focus(); 
    document.form1.r11_eleina.value = ''; 
  }
}
function js_mostraeleina1(chave1,chave2){
  document.form1.r11_eleina.value = chave1;
  document.form1.o56_descr1.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisar11_elepen(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraelepen1|o56_elemento|o56_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_elepen.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.r11_elepen.value+'&funcao_js=parent.js_mostraelepen','Pesquisa',false);
    }else{
      document.form1.o56_descr2.value = ''; 
    }
  }
}
function js_mostraelepen(chave,erro){
  document.form1.o56_descr2.value = chave; 
  if(erro==true){ 
    document.form1.r11_elepen.focus(); 
    document.form1.r11_elepen.value = ''; 
  }
}
function js_mostraelepen1(chave1,chave2){
  document.form1.r11_elepen.value = chave1;
  document.form1.o56_descr2.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cfpess','func_cfpess.php?funcao_js=parent.js_preenchepesquisa|r11_anousu|r11_mesusu','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_cfpess.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>