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

//MODULO: dividaativa
$clprocedparag->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v03_descr");
$clrotulo->label("db03_descr");
$clrotulo->label("db03_descr1");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" style="margin-top:15px; ">
  <tr>
    <td nowrap title="<?=@$Tv80_proced?>">
       <?
       db_ancora(@$Lv80_proced,"js_pesquisav80_proced(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			db_input('v80_proced',8,$Iv80_proced,true,'text',$db_opcao," onchange='js_pesquisav80_proced(false);'")
			?>
			       <?
			db_input('v03_descr',20,$Iv03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv80_docum?>">
       <?
       db_ancora(@$Lv80_docum,"js_pesquisav80_docum(true);",$db_opcao);
       ?>
    </td>
    <td> 
				<?
				db_input('v80_docum',8,$Iv80_docum,true,'text',$db_opcao," onchange='js_pesquisav80_docum(false);'")
				?>
       <?
				db_input('db03_descr',40,$Idb03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv80_docmetcalculo?>">
       <?
       db_ancora(@$Lv80_docmetcalculo,"js_pesquisav80_docmetcalculo(true);",$db_opcao);
       ?>
    </td>
    <td> 
				<?
				db_input('v80_docmetcalculo',8,$Iv80_docmetcalculo,true,'text',$db_opcao," onchange='js_pesquisav80_docmetcalculo(false);'")
				?>
       <?
				db_input('db03_descr1',40,'text',true,'text',3,'')
       ?>
    </td>
  </tr>
  
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav80_proced(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_proced','func_proced.php?funcao_js=parent.js_mostraproced1|v03_codigo|v03_descr','Pesquisa',true);
  }else{
     if(document.form1.v80_proced.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_proced','func_proced.php?pesquisa_chave='+document.form1.v80_proced.value+'&funcao_js=parent.js_mostraproced','Pesquisa',false);
     }else{
       document.form1.v03_descr.value = ''; 
     }
  }
}
function js_mostraproced(chave,erro){
  document.form1.v03_descr.value = chave; 
  if(erro==true){ 
    document.form1.v80_proced.focus(); 
    document.form1.v80_proced.value = ''; 
  }
}
function js_mostraproced1(chave1,chave2){
  document.form1.v80_proced.value = chave1;
  document.form1.v03_descr.value = chave2;
  db_iframe_proced.hide();
}
function js_pesquisav80_docum(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?funcao_js=parent.js_mostradb_documento1|db03_docum|db03_descr','Pesquisa',true);
  }else{
     if(document.form1.v80_docum.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?pesquisa_chave='+document.form1.v80_docum.value+'&funcao_js=parent.js_mostradb_documento','Pesquisa',false);
     }else{
       document.form1.db03_descr.value = ''; 
     }
  }
}
function js_mostradb_documento(chave,erro){
  document.form1.db03_descr.value = chave; 
  if(erro==true){ 
    document.form1.v80_docum.focus(); 
    document.form1.v80_docum.value = ''; 
  }
}
function js_mostradb_documento1(chave1,chave2){
  document.form1.v80_docum.value = chave1;
  document.form1.db03_descr.value = chave2;
  db_iframe_db_documento.hide();
}
function js_pesquisav80_docmetcalculo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?funcao_js=parent.js_mostradb_docmetcalculo1|db03_docum|db03_descr','Pesquisa',true);
  }else{
     if(document.form1.v80_docmetcalculo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?pesquisa_chave='+document.form1.v80_docmetcalculo.value+'&funcao_js=parent.js_mostradb_docmetcalculo','Pesquisa',false);
     }else{
       document.form1.db03_descr1.value = ''; 
     }
  }
}
function js_mostradb_docmetcalculo(chave,erro){
  document.form1.db03_descr1.value = chave; 
  if(erro==true){ 
    document.form1.v80_docmetcalculo.focus(); 
    document.form1.v80_docum.value = ''; 
  }
}
function js_mostradb_docmetcalculo1(chave1,chave2){
  document.form1.v80_docmetcalculo.value = chave1;
  document.form1.db03_descr1.value = chave2;
  db_iframe_db_documento.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_procedparag','func_procedparag.php?funcao_js=parent.js_preenchepesquisa|v80_proced','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_procedparag.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>