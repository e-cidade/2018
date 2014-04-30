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

//MODULO: orcamento
$clcronogramabasereceita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o70_anousu");
$clrotulo->label("o70_anousu");
$clrotulo->label("o70_anousu");
$clrotulo->label("o70_anousu");
$clrotulo->label("o125_cronogramaperspectiva");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To126_sequencial?>">
       <?=@$Lo126_sequencial?>
    </td>
    <td> 
<?
db_input('o126_sequencial',10,$Io126_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To126_codrec?>">
       <?
       db_ancora(@$Lo126_codrec,"js_pesquisao126_codrec(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o126_codrec',10,$Io126_codrec,true,'text',$db_opcao," onchange='js_pesquisao126_codrec(false);'")
?>
       <?
db_input('o70_anousu',4,$Io70_anousu,true,'text',3,'')
db_input('o70_anousu',4,$Io70_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To126_anousu?>">
       <?
       db_ancora(@$Lo126_anousu,"js_pesquisao126_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$o126_anousu = db_getsession('DB_anousu');
db_input('o126_anousu',4,$Io126_anousu,true,'text',3," onchange='js_pesquisao126_anousu(false);'")
?>
       <?
db_input('o70_anousu',4,$Io70_anousu,true,'text',3,'')
db_input('o70_anousu',4,$Io70_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To126_cronogramabasecalculo?>">
       <?
       db_ancora(@$Lo126_cronogramabasecalculo,"js_pesquisao126_cronogramabasecalculo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o126_cronogramabasecalculo',10,$Io126_cronogramabasecalculo,true,'text',$db_opcao," onchange='js_pesquisao126_cronogramabasecalculo(false);'")
?>
       <?
db_input('o125_cronogramaperspectiva',10,$Io125_cronogramaperspectiva,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao126_codrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_anousu|o70_anousu','Pesquisa',true);
  }else{
     if(document.form1.o126_codrec.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.o126_codrec.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
     }else{
       document.form1.o70_anousu.value = ''; 
     }
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o126_codrec.focus(); 
    document.form1.o126_codrec.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.o126_codrec.value = chave1;
  document.form1.o70_anousu.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisao126_codrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_codrec|o70_anousu','Pesquisa',true);
  }else{
     if(document.form1.o126_codrec.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.o126_codrec.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
     }else{
       document.form1.o70_anousu.value = ''; 
     }
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o126_codrec.focus(); 
    document.form1.o126_codrec.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.o126_codrec.value = chave1;
  document.form1.o70_anousu.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisao126_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_anousu|o70_anousu','Pesquisa',true);
  }else{
     if(document.form1.o126_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.o126_anousu.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
     }else{
       document.form1.o70_anousu.value = ''; 
     }
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o126_anousu.focus(); 
    document.form1.o126_anousu.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.o126_anousu.value = chave1;
  document.form1.o70_anousu.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisao126_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_codrec|o70_anousu','Pesquisa',true);
  }else{
     if(document.form1.o126_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.o126_anousu.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
     }else{
       document.form1.o70_anousu.value = ''; 
     }
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o126_anousu.focus(); 
    document.form1.o126_anousu.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.o126_anousu.value = chave1;
  document.form1.o70_anousu.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisao126_cronogramabasecalculo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cronogramabasecalculo','func_cronogramabasecalculo.php?funcao_js=parent.js_mostracronogramabasecalculo1|o125_sequencial|o125_cronogramaperspectiva','Pesquisa',true);
  }else{
     if(document.form1.o126_cronogramabasecalculo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cronogramabasecalculo','func_cronogramabasecalculo.php?pesquisa_chave='+document.form1.o126_cronogramabasecalculo.value+'&funcao_js=parent.js_mostracronogramabasecalculo','Pesquisa',false);
     }else{
       document.form1.o125_cronogramaperspectiva.value = ''; 
     }
  }
}
function js_mostracronogramabasecalculo(chave,erro){
  document.form1.o125_cronogramaperspectiva.value = chave; 
  if(erro==true){ 
    document.form1.o126_cronogramabasecalculo.focus(); 
    document.form1.o126_cronogramabasecalculo.value = ''; 
  }
}
function js_mostracronogramabasecalculo1(chave1,chave2){
  document.form1.o126_cronogramabasecalculo.value = chave1;
  document.form1.o125_cronogramaperspectiva.value = chave2;
  db_iframe_cronogramabasecalculo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cronogramabasereceita','func_cronogramabasereceita.php?funcao_js=parent.js_preenchepesquisa|o126_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cronogramabasereceita.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>