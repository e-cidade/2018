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

//MODULO: compras
$clpcsubgrupo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc03_descrgrupo");
$clrotulo->label("pc05_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc04_codsubgrupo?>">
       <?=@$Lpc04_codsubgrupo?>
    </td>
    <td> 
<?
db_input('pc04_codsubgrupo',6,$Ipc04_codsubgrupo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc04_descrsubgrupo?>">
       <?=@$Lpc04_descrsubgrupo?>
    </td>
    <td> 
<?
db_input('pc04_descrsubgrupo',80,$Ipc04_descrsubgrupo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc04_codgrupo?>">
       <?
       db_ancora(@$Lpc04_codgrupo,"js_pesquisapc04_codgrupo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc04_codgrupo',6,$Ipc04_codgrupo,true,'text',$db_opcao," onchange='js_pesquisapc04_codgrupo(false);'")
?>
       <?
db_input('pc03_descrgrupo',40,$Ipc03_descrgrupo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <!--
  <tr>
    <td nowrap title="<?=@$Tpc04_codtipo?>">
       <?
       db_ancora(@$Lpc04_codtipo,"js_pesquisapc04_codtipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc04_codtipo',6,$Ipc04_codtipo,true,'text',$db_opcao," onchange='js_pesquisapc04_codtipo(false);'")
?>
       <?
db_input('pc05_descr',40,$Ipc05_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  -->
  <tr>
    <td nowrap title="<?=@$Tpc04_ativo?>">
       <?=@$Lpc04_ativo?>
    </td>
    <td> 
<?
$x = array('t'=>'Sim','f'=>'Não');
db_select('pc04_ativo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc04_tipoutil?>">
       <?=@$Lpc04_tipoutil?>
    </td>
    <td> 
<?
$x = array('3'=>'Ambos','1'=>'Cadastro de Materiais','2'=>'Cadastro de Fornecedores');
db_select('pc04_tipoutil',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisapc04_codgrupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcgrupo','func_pcgrupo.php?funcao_js=parent.js_mostrapcgrupo1|pc03_codgrupo|pc03_descrgrupo','Pesquisa',true);
  }else{
     if(document.form1.pc04_codgrupo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcgrupo','func_pcgrupo.php?pesquisa_chave='+document.form1.pc04_codgrupo.value+'&funcao_js=parent.js_mostrapcgrupo','Pesquisa',false);
     }else{
       document.form1.pc03_descrgrupo.value = ''; 
     }
  }
}
function js_mostrapcgrupo(chave,erro){
  document.form1.pc03_descrgrupo.value = chave; 
  if(erro==true){ 
    document.form1.pc04_codgrupo.focus(); 
    document.form1.pc04_codgrupo.value = ''; 
  }
}
function js_mostrapcgrupo1(chave1,chave2){
  document.form1.pc04_codgrupo.value = chave1;
  document.form1.pc03_descrgrupo.value = chave2;
  db_iframe_pcgrupo.hide();
}
function js_pesquisapc04_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pctipo','func_pctipo.php?funcao_js=parent.js_mostrapctipo1|pc05_codtipo|pc05_descr','Pesquisa',true);
  }else{
     if(document.form1.pc04_codtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pctipo','func_pctipo.php?pesquisa_chave='+document.form1.pc04_codtipo.value+'&funcao_js=parent.js_mostrapctipo','Pesquisa',false);
     }else{
       document.form1.pc05_descr.value = ''; 
     }
  }
}
function js_mostrapctipo(chave,erro){
  document.form1.pc05_descr.value = chave; 
  if(erro==true){ 
    document.form1.pc04_codtipo.focus(); 
    document.form1.pc04_codtipo.value = ''; 
  }
}
function js_mostrapctipo1(chave1,chave2){
  document.form1.pc04_codtipo.value = chave1;
  document.form1.pc05_descr.value = chave2;
  db_iframe_pctipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcsubgrupo','func_pcsubgrupo.php?funcao_js=parent.js_preenchepesquisa|pc04_codsubgrupo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pcsubgrupo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>