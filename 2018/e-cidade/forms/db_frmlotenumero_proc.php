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
$cllotenumero_proc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j12_numero");
$clrotulo->label("p58_codproc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj11_codigo?>">
       <?
       db_ancora(@$Lj11_codigo,"js_pesquisaj11_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j11_codigo',10,$Ij11_codigo,true,'text',$db_opcao," onchange='js_pesquisaj11_codigo(false);'")
?>
       <?
db_input('j12_numero',10,$Ij12_numero,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj11_processo?>">
       <?
       db_ancora(@$Lj11_processo,"js_pesquisaj11_processo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j11_processo',10,$Ij11_processo,true,'text',$db_opcao," onchange='js_pesquisaj11_processo(false);'")
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
function js_pesquisaj11_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lotenumero','func_lotenumero.php?funcao_js=parent.js_mostralotenumero1|j12_codigo|j12_numero','Pesquisa',true);
  }else{
     if(document.form1.j11_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lotenumero','func_lotenumero.php?pesquisa_chave='+document.form1.j11_codigo.value+'&funcao_js=parent.js_mostralotenumero','Pesquisa',false);
     }else{
       document.form1.j12_numero.value = ''; 
     }
  }
}
function js_mostralotenumero(chave,erro){
  document.form1.j12_numero.value = chave; 
  if(erro==true){ 
    document.form1.j11_codigo.focus(); 
    document.form1.j11_codigo.value = ''; 
  }
}
function js_mostralotenumero1(chave1,chave2){
  document.form1.j11_codigo.value = chave1;
  document.form1.j12_numero.value = chave2;
  db_iframe_lotenumero.hide();
}
function js_pesquisaj11_processo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.j11_processo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.j11_processo.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave; 
  if(erro==true){ 
    document.form1.j11_processo.focus(); 
    document.form1.j11_processo.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.j11_processo.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lotenumero_proc','func_lotenumero_proc.php?funcao_js=parent.js_preenchepesquisa|j11_codigo|j11_processo','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_lotenumero_proc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>