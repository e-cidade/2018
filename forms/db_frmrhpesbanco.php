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
$clrhpesbanco->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh02_seqpes");
$clrotulo->label("db90_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh44_seqpes?>">
       <?
       db_ancora(@$Lrh44_seqpes,"js_pesquisarh44_seqpes(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh44_seqpes',10,$Irh44_seqpes,true,'text',$db_opcao," onchange='js_pesquisarh44_seqpes(false);'")
?>
       <?
db_input('rh02_seqpes',6,$Irh02_seqpes,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh44_codban?>">
       <?
       db_ancora(@$Lrh44_codban,"js_pesquisarh44_codban(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh44_codban',10,$Irh44_codban,true,'text',$db_opcao," onchange='js_pesquisarh44_codban(false);'")
?>
       <?
db_input('db90_descr',40,$Idb90_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh44_agencia?>">
       <?=@$Lrh44_agencia?>
    </td>
    <td> 
<?
db_input('rh44_agencia',10,$Irh44_agencia,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh44_dvagencia?>">
       <?=@$Lrh44_dvagencia?>
    </td>
    <td> 
<?
db_input('rh44_dvagencia',2,$Irh44_dvagencia,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh44_conta?>">
       <?=@$Lrh44_conta?>
    </td>
    <td> 
<?
db_input('rh44_conta',50,$Irh44_conta,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh44_dvconta?>">
       <?=@$Lrh44_dvconta?>
    </td>
    <td> 
<?
db_input('rh44_dvconta',2,$Irh44_dvconta,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh44_seqpes(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoalmov','func_rhpessoalmov.php?funcao_js=parent.js_mostrarhpessoalmov1|rh02_seqpes|rh02_seqpes','Pesquisa',true);
  }else{
     if(document.form1.rh44_seqpes.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoalmov','func_rhpessoalmov.php?pesquisa_chave='+document.form1.rh44_seqpes.value+'&funcao_js=parent.js_mostrarhpessoalmov','Pesquisa',false);
     }else{
       document.form1.rh02_seqpes.value = ''; 
     }
  }
}
function js_mostrarhpessoalmov(chave,erro){
  document.form1.rh02_seqpes.value = chave; 
  if(erro==true){ 
    document.form1.rh44_seqpes.focus(); 
    document.form1.rh44_seqpes.value = ''; 
  }
}
function js_mostrarhpessoalmov1(chave1,chave2){
  document.form1.rh44_seqpes.value = chave1;
  document.form1.rh02_seqpes.value = chave2;
  db_iframe_rhpessoalmov.hide();
}
function js_pesquisarh44_codban(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr','Pesquisa',true);
  }else{
     if(document.form1.rh44_codban.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh44_codban.value+'&funcao_js=parent.js_mostradb_bancos','Pesquisa',false);
     }else{
       document.form1.db90_descr.value = ''; 
     }
  }
}
function js_mostradb_bancos(chave,erro){
  document.form1.db90_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh44_codban.focus(); 
    document.form1.rh44_codban.value = ''; 
  }
}
function js_mostradb_bancos1(chave1,chave2){
  document.form1.rh44_codban.value = chave1;
  document.form1.db90_descr.value = chave2;
  db_iframe_db_bancos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpesbanco','func_rhpesbanco.php?funcao_js=parent.js_preenchepesquisa|rh44_seqpes','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhpesbanco.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>