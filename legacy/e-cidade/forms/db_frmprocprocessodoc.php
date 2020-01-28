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

//MODULO: protocolo
$clprocprocessodoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p58_codproc");
$clrotulo->label("p56_coddoc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp81_codproc?>">
       <?
       db_ancora(@$Lp81_codproc,"js_pesquisap81_codproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p81_codproc',8,$Ip81_codproc,true,'text',$db_opcao," onchange='js_pesquisap81_codproc(false);'")
?>
       <?
db_input('p58_codproc',8,$Ip58_codproc,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp81_coddoc?>">
       <?
       db_ancora(@$Lp81_coddoc,"js_pesquisap81_coddoc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p81_coddoc',3,$Ip81_coddoc,true,'text',$db_opcao," onchange='js_pesquisap81_coddoc(false);'")
?>
       <?
db_input('p56_coddoc',3,$Ip56_coddoc,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp81_doc?>">
       <?=@$Lp81_doc?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('p81_doc',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap81_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.p81_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.p81_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave; 
  if(erro==true){ 
    document.form1.p81_codproc.focus(); 
    document.form1.p81_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.p81_codproc.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisap81_coddoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procdoc','func_procdoc.php?funcao_js=parent.js_mostraprocdoc1|p56_coddoc|p56_coddoc','Pesquisa',true);
  }else{
     if(document.form1.p81_coddoc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procdoc','func_procdoc.php?pesquisa_chave='+document.form1.p81_coddoc.value+'&funcao_js=parent.js_mostraprocdoc','Pesquisa',false);
     }else{
       document.form1.p56_coddoc.value = ''; 
     }
  }
}
function js_mostraprocdoc(chave,erro){
  document.form1.p56_coddoc.value = chave; 
  if(erro==true){ 
    document.form1.p81_coddoc.focus(); 
    document.form1.p81_coddoc.value = ''; 
  }
}
function js_mostraprocdoc1(chave1,chave2){
  document.form1.p81_coddoc.value = chave1;
  document.form1.p56_coddoc.value = chave2;
  db_iframe_procdoc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_procprocessodoc','func_procprocessodoc.php?funcao_js=parent.js_preenchepesquisa|p81_codproc|p81_coddoc','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_procprocessodoc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>