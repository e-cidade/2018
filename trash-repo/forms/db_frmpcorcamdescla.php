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
$clpcorcamdescla->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc22_codorc");
$clrotulo->label("pc21_codorc");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
db_input('pc32_orcamitem',10,$Ipc32_orcamitem,true,'hidden',3," onchange='js_pesquisapc32_orcamitem(false);'")
?>
<?
db_input('pc32_orcamforne',10,$Ipc32_orcamforne,true,'hidden',3," onchange='js_pesquisapc32_orcamforne(false);'")
?>
  <tr>
    <td nowrap title="<?=@$Tpc32_motivo?>">
       <?=@$Lpc32_motivo?>
    </td>
    <td> 
<?
db_textarea('pc32_motivo',10,80,$Ipc32_motivo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
if($db_opcao == 2){
?>
<input name="excluir" type="submit" id="db_opcao" value="Excluir" <?=($db_botao==false?"disabled":"")?> >
<?
}
?>
<input name="fechar" type="button" id="fechar" value="Fechar" onClick="js_fechar();">
  </center>
</form>
<script>
function js_fechar(){
  parent.db_iframe_descla.hide();
  parent.elementos.document.form1.submit();
}
function js_pesquisapc32_orcamitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcorcamitem','func_pcorcamitem.php?funcao_js=parent.js_mostrapcorcamitem1|pc22_orcamitem|pc22_codorc','Pesquisa',true);
  }else{
     if(document.form1.pc32_orcamitem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcorcamitem','func_pcorcamitem.php?pesquisa_chave='+document.form1.pc32_orcamitem.value+'&funcao_js=parent.js_mostrapcorcamitem','Pesquisa',false);
     }
  }
}
function js_mostrapcorcamitem(chave,erro){
  if(erro==true){ 
    document.form1.pc32_orcamitem.focus(); 
    document.form1.pc32_orcamitem.value = ''; 
  }
}
function js_mostrapcorcamitem1(chave1,chave2){
  document.form1.pc32_orcamitem.value = chave1;
  db_iframe_pcorcamitem.hide();
}
function js_pesquisapc32_orcamforne(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcorcamforne','func_pcorcamforne.php?funcao_js=parent.js_mostrapcorcamforne1|pc21_orcamforne|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.pc32_orcamforne.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcorcamforne','func_pcorcamforne.php?pesquisa_chave='+document.form1.pc32_orcamforne.value+'&funcao_js=parent.js_mostrapcorcamforne','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostrapcorcamforne(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.pc32_orcamforne.focus(); 
    document.form1.pc32_orcamforne.value = ''; 
  }
}
function js_mostrapcorcamforne1(chave1,chave2){
  document.form1.pc32_orcamforne.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_pcorcamforne.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcorcamdescla','func_pcorcamdescla.php?funcao_js=parent.js_preenchepesquisa|pc32_orcamitem|pc32_orcamforne','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_pcorcamdescla.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>