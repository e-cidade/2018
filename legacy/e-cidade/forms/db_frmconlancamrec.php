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
$clconlancamrec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c70_anousu");
$clrotulo->label("o70_codfon");
$clrotulo->label("o70_codfon");
$clrotulo->label("o70_codfon");
$clrotulo->label("o70_codfon");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc74_codlan?>">
       <?
       db_ancora(@$Lc74_codlan,"js_pesquisac74_codlan(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c74_codlan',8,$Ic74_codlan,true,'text',$db_opcao," onchange='js_pesquisac74_codlan(false);'");
?>
       <?
db_input('c70_anousu',4,$Ic70_anousu,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc74_anousu?>">
       <?
       db_ancora(@$Lc74_anousu,"js_pesquisac74_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$c74_anousu = db_getsession('DB_anousu');
db_input('c74_anousu',4,$Ic74_anousu,true,'text',3," onchange='js_pesquisac74_anousu(false);'");
?>
       <?
db_input('o70_codfon',6,$Io70_codfon,true,'text',3,'');
db_input('o70_codfon',6,$Io70_codfon,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc74_codrec?>">
       <?
       db_ancora(@$Lc74_codrec,"js_pesquisac74_codrec(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c74_codrec',6,$Ic74_codrec,true,'text',$db_opcao," onchange='js_pesquisac74_codrec(false);'");
?>
       <?
db_input('o70_codfon',6,$Io70_codfon,true,'text',3,'');
db_input('o70_codfon',6,$Io70_codfon,true,'text',3,'');
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac74_codlan(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancam','func_conlancam.php?funcao_js=parent.js_mostraconlancam1|c70_codlan|c70_anousu','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancam','func_conlancam.php?pesquisa_chave='+document.form1.c74_codlan.value+'&funcao_js=parent.js_mostraconlancam','Pesquisa',false);
  }
}
function js_mostraconlancam(chave,erro){
  document.form1.c70_anousu.value = chave; 
  if(erro==true){ 
    document.form1.c74_codlan.focus(); 
    document.form1.c74_codlan.value = ''; 
  }
}
function js_mostraconlancam1(chave1,chave2){
  document.form1.c74_codlan.value = chave1;
  document.form1.c70_anousu.value = chave2;
  db_iframe_conlancam.hide();
}
function js_pesquisac74_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_anousu|o70_codfon','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.c74_anousu.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_codfon.value = chave; 
  if(erro==true){ 
    document.form1.c74_anousu.focus(); 
    document.form1.c74_anousu.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.c74_anousu.value = chave1;
  document.form1.o70_codfon.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisac74_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_codrec|o70_codfon','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.c74_anousu.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_codfon.value = chave; 
  if(erro==true){ 
    document.form1.c74_anousu.focus(); 
    document.form1.c74_anousu.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.c74_anousu.value = chave1;
  document.form1.o70_codfon.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisac74_codrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_anousu|o70_codfon','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.c74_codrec.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_codfon.value = chave; 
  if(erro==true){ 
    document.form1.c74_codrec.focus(); 
    document.form1.c74_codrec.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.c74_codrec.value = chave1;
  document.form1.o70_codfon.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisac74_codrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_codrec|o70_codfon','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.c74_codrec.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_codfon.value = chave; 
  if(erro==true){ 
    document.form1.c74_codrec.focus(); 
    document.form1.c74_codrec.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.c74_codrec.value = chave1;
  document.form1.o70_codfon.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conlancamrec','func_conlancamrec.php?funcao_js=parent.js_preenchepesquisa|c74_codlan','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conlancamrec.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>