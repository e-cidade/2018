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

//MODULO: caixa
$cltaxagrupo->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk06_taxagrupo?>">
       <?=@$Lk06_taxagrupo?>
    </td>
    <td> 
	<?
	db_input('k06_taxagrupo',5,$Ik06_taxagrupo,true,'text',3,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk06_descr?>">
       <?=@$Lk06_descr?>
    </td>
    <td> 
	<?
	db_input('k06_descr',50,$Ik06_descr,true,'text',$db_opcao,"");
	?>
    </td>

  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" type="button" id="novo" value="Novo" onclick="js_novo();" >
<input name="dbopcao" type="hidden" id="hi" value="">
</form>
<script>


function js_novo(){
  js_limpa();
}
function js_limpa(){
  document.form1.k06_taxagrupo.value = '';
  document.form1.k06_descr.value = '';
  document.form1.dbopcao.value = 1;
  document.form1.submit();
}


function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_taxagrupo','func_taxagrupo.php?funcao_js=parent.js_preenchepesquisa|k06_taxagrupo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_taxagrupo.hide();
  <?
//  if($db_opcao!=999){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
//  }
  ?>
}
function js_pesquisak08_taxagrupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_taxagrupo','func_taxagrupo.php?funcao_js=parent.js_mostrataxagrupo1|k06_taxagrupo|k06_descr','Pesquisa',true);
  }else{
     if(document.form1.k08_taxagrupo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_taxagrupo','func_taxagrupo.php?pesquisa_chave='+document.form1.k08_taxagrupo.value+'&funcao_js=parent.js_mostrataxagrupo','Pesquisa',false);
     }else{
       document.form1.k06_descr.value = ''; 
     }
  }
}
function js_mostrataxagrupo(chave,erro){
  document.form1.k06_descr.value = chave; 
  if(erro==true){ 
    document.form1.k08_taxagrupo.focus(); 
    document.form1.k08_taxagrupo.value = ''; 
  }
}
function js_mostrataxagrupo1(chave1,chave2){
  document.form1.k08_taxagrupo.value = chave1;
  document.form1.k06_descr.value = chave2;
  db_iframe_taxagrupo.hide();
}
function js_pesquisak08_codsubrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabdesc','func_tabdesc.php?funcao_js=parent.js_mostratabdesc1|codsubrec|k07_descr','Pesquisa',true);
  }else{
     if(document.form1.k08_codsubrec.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tabdesc','func_tabdesc.php?pesquisa_chave='+document.form1.k08_codsubrec.value+'&funcao_js=parent.js_mostratabdesc','Pesquisa',false);
     }else{
       document.form1.k07_descr.value = ''; 
     }
  }
}
function js_mostratabdesc(chave,erro){
  document.form1.k07_descr.value = chave; 
  if(erro==true){ 
    document.form1.k08_codsubrec.focus(); 
    document.form1.k08_codsubrec.value = ''; 
  }
}
function js_mostratabdesc1(chave1,chave2){
  document.form1.k08_codsubrec.value = chave1;
  document.form1.k07_descr.value = chave2;
  db_iframe_tabdesc.hide();
}
function js_pesquisa1(){
  js_OpenJanelaIframe('','db_iframe_taxagruporeg','func_taxagruporeg.php?funcao_js=parent.js_preenchepesquisa|k08_taxagruporeg','Pesquisa',true);
}
function js_preenchepesquisa1(chave){
  db_iframe_taxagruporeg.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>