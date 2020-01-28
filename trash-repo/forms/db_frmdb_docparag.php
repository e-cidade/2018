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

//MODULO: configuracoes
$cldb_docparag->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db03_descr");
$clrotulo->label("db02_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb04_docum?>">
       <?
       db_ancora(@$Ldb04_docum,"js_pesquisadb04_docum(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db04_docum',8,$Idb04_docum,true,'text',$db_opcao," onchange='js_pesquisadb04_docum(false);'")
?>
       <?
db_input('db03_descr',80,$Idb03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb04_idparag?>">
       <?
       db_ancora(@$Ldb04_idparag,"js_pesquisadb04_idparag(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db04_idparag',8,$Idb04_idparag,true,'text',$db_opcao," onchange='js_pesquisadb04_idparag(false);'")
?>
       <?
db_input('db02_descr',40,$Idb02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb04_ordem?>">
       <?=@$Ldb04_ordem?>
    </td>
    <td> 
<?
db_input('db04_ordem',4,$Idb04_ordem,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb04_docum(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_db_documento.php?funcao_js=parent.js_mostradb_documento1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_db_documento.php?pesquisa_chave='+document.form1.db04_docum.value+'&funcao_js=parent.js_mostradb_documento','Pesquisa',false);
  }
}
function js_mostradb_documento(chave,erro){
  document.form1.db03_descr.value = chave; 
  if(erro==true){ 
    document.form1.db04_docum.focus(); 
    document.form1.db04_docum.value = ''; 
  }
}
function js_mostradb_documento1(chave1,chave2){
  document.form1.db04_docum.value = chave1;
  document.form1.db03_descr.value = chave2;
  top.corpo.db_iframe.hide();
}
function js_pesquisadb04_idparag(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_db_paragrafo.php?funcao_js=parent.js_mostradb_paragrafo1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_db_paragrafo.php?pesquisa_chave='+document.form1.db04_idparag.value+'&funcao_js=parent.js_mostradb_paragrafo','Pesquisa',false);
  }
}
function js_mostradb_paragrafo(chave,erro){
  document.form1.db02_descr.value = chave; 
  if(erro==true){ 
    document.form1.db04_idparag.focus(); 
    document.form1.db04_idparag.value = ''; 
  }
}
function js_mostradb_paragrafo1(chave1,chave2){
  document.form1.db04_idparag.value = chave1;
  document.form1.db02_descr.value = chave2;
  top.corpo.db_iframe.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe','func_db_docparag.php?funcao_js=parent.js_preenchepesquisa|0|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  top.corpo.db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave+"&chavepesquisa1="+chave1;
}
</script>
<?
//$func_iframe = new janela('db_iframe','');
//$func_iframe->posX=1;
//$func_iframe->posY=20;
//$func_iframe->largura=780;
//$func_iframe->altura=430;
//$func_iframe->titulo='Pesquisa';
//$func_iframe->iniciarVisivel = false;
//$func_iframe->mostrar();
?>