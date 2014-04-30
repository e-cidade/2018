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

//MODULO: cadastro
$cliptubaixaproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j02_dtbaixa");
$clrotulo->label("p58_codproc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj03_matric?>">
       <?
       db_ancora(@$Lj03_matric,"js_pesquisaj03_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j03_matric',10,$Ij03_matric,true,'text',$db_opcao," onchange='js_pesquisaj03_matric(false);'")
?>
       <?
db_input('j02_dtbaixa',10,$Ij02_dtbaixa,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj03_codproc?>">
       <?
       db_ancora(@$Lj03_codproc,"js_pesquisaj03_codproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j03_codproc',10,$Ij03_codproc,true,'text',$db_opcao," onchange='js_pesquisaj03_codproc(false);'")
?>
       <?
db_input('p58_codproc',10,$Ip58_codproc,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj03_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_iptubaixa','func_iptubaixa.php?funcao_js=parent.js_mostraiptubaixa1|j02_matric|j02_dtbaixa','Pesquisa',true);
  }else{
     if(document.form1.j03_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_iptubaixa','func_iptubaixa.php?pesquisa_chave='+document.form1.j03_matric.value+'&funcao_js=parent.js_mostraiptubaixa','Pesquisa',false);
     }else{
       document.form1.j02_dtbaixa.value = ''; 
     }
  }
}
function js_mostraiptubaixa(chave,erro){
  document.form1.j02_dtbaixa.value = chave; 
  if(erro==true){ 
    document.form1.j03_matric.focus(); 
    document.form1.j03_matric.value = ''; 
  }
}
function js_mostraiptubaixa1(chave1,chave2){
  document.form1.j03_matric.value = chave1;
  document.form1.j02_dtbaixa.value = chave2;
  db_iframe_iptubaixa.hide();
}
function js_pesquisaj03_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.j03_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.j03_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave; 
  if(erro==true){ 
    document.form1.j03_codproc.focus(); 
    document.form1.j03_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.j03_codproc.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_iptubaixaproc','func_iptubaixaproc.php?funcao_js=parent.js_preenchepesquisa|j03_matric','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_iptubaixaproc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>