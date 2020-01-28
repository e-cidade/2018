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
$cldb_departender->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
?>
<script>
</script>
<form name="form1" method="post" action=""  >
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcoddepto?>">
       <?=@$Lcoddepto?>
    </td>
    <td> 
<?
db_input('coddepto',5,$Icoddepto,true,'text',3,"");
/*
if ($db_opcao==2||$db_opcao==3){
  $result=$cldb_departender->sql_record($cldb_departender->sql_query_file(@$coddepto));
  if ($cldb_departender->numrows!=0){
    db_fieldsmemory($result,0);
  }
}
*/
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcodlograd?>">
       <?
       db_ancora(@$Lcodlograd,"js_pesquisacodlograd(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('codlograd',7,$Icodlograd,true,'text',$db_opcao," onchange='js_pesquisacodlograd(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tnumero?>">
       <?=@$Lnumero?>
    </td>
    <td> 
<?
db_input('numero',10,$Inumero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcompl?>">
       <?=@$Lcompl?>
    </td>
    <td> 
<?
db_input('compl',20,$Icompl,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcodbairro?>">
       <?
       db_ancora(@$Lcodbairro,"js_pesquisacodbairro(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('codbairro',4,$Icodbairro,true,'text',$db_opcao," onchange='js_pesquisacodbairro(false);'")
?>
       <?
db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<?  if ($db_opcao==1||$db_opcao==2||$db_opcao==22){?>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?}?>
</form>
<script>
function js_pesquisacoddepto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.coddepto.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.coddepto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.coddepto.focus(); 
    document.form1.coddepto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisacodlograd(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true);
  }else{
     if(document.form1.codlograd.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.codlograd.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
     }else{
       document.form1.j14_nome.value = ''; 
     }
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.codlograd.focus(); 
    document.form1.codlograd.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.codlograd.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}
function js_pesquisacodbairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
  }else{
     if(document.form1.codbairro.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.codbairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
     }else{
       document.form1.j13_descr.value = ''; 
     }
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave; 
  if(erro==true){ 
    document.form1.codbairro.focus(); 
    document.form1.codbairro.value = ''; 
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.codbairro.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe_bairro.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_db_departender','func_db_departender.php?funcao_js=parent.js_preenchepesquisa|coddepto','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_departender.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>