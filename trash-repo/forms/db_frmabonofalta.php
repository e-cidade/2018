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

//MODULO: educação
$clabonofalta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed72_i_codigo");
$clrotulo->label("ed06_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted80_i_codigo?>">
       <?=@$Led80_i_codigo?>
    </td>
    <td> 
<?
db_input('ed80_i_codigo',10,$Ied80_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted80_i_diarioavaliacao?>">
       <?
       db_ancora(@$Led80_i_diarioavaliacao,"js_pesquisaed80_i_diarioavaliacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed80_i_diarioavaliacao',10,$Ied80_i_diarioavaliacao,true,'text',$db_opcao," onchange='js_pesquisaed80_i_diarioavaliacao(false);'")
?>
       <?
db_input('ed72_i_codigo',10,$Ied72_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted80_i_justificativa?>">
       <?
       db_ancora(@$Led80_i_justificativa,"js_pesquisaed80_i_justificativa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed80_i_justificativa',10,$Ied80_i_justificativa,true,'text',$db_opcao," onchange='js_pesquisaed80_i_justificativa(false);'")
?>
       <?
db_input('ed06_i_codigo',10,$Ied06_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted80_i_numfaltas?>">
       <?=@$Led80_i_numfaltas?>
    </td>
    <td> 
<?
db_input('ed80_i_numfaltas',10,$Ied80_i_numfaltas,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed80_i_diarioavaliacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_diarioavaliacao','func_diarioavaliacao.php?funcao_js=parent.js_mostradiarioavaliacao1|ed72_i_codigo|ed72_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed80_i_diarioavaliacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_diarioavaliacao','func_diarioavaliacao.php?pesquisa_chave='+document.form1.ed80_i_diarioavaliacao.value+'&funcao_js=parent.js_mostradiarioavaliacao','Pesquisa',false);
     }else{
       document.form1.ed72_i_codigo.value = ''; 
     }
  }
}
function js_mostradiarioavaliacao(chave,erro){
  document.form1.ed72_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed80_i_diarioavaliacao.focus(); 
    document.form1.ed80_i_diarioavaliacao.value = ''; 
  }
}
function js_mostradiarioavaliacao1(chave1,chave2){
  document.form1.ed80_i_diarioavaliacao.value = chave1;
  document.form1.ed72_i_codigo.value = chave2;
  db_iframe_diarioavaliacao.hide();
}
function js_pesquisaed80_i_justificativa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_justificativa','func_justificativa.php?funcao_js=parent.js_mostrajustificativa1|ed06_i_codigo|ed06_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed80_i_justificativa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_justificativa','func_justificativa.php?pesquisa_chave='+document.form1.ed80_i_justificativa.value+'&funcao_js=parent.js_mostrajustificativa','Pesquisa',false);
     }else{
       document.form1.ed06_i_codigo.value = ''; 
     }
  }
}
function js_mostrajustificativa(chave,erro){
  document.form1.ed06_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed80_i_justificativa.focus(); 
    document.form1.ed80_i_justificativa.value = ''; 
  }
}
function js_mostrajustificativa1(chave1,chave2){
  document.form1.ed80_i_justificativa.value = chave1;
  document.form1.ed06_i_codigo.value = chave2;
  db_iframe_justificativa.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_abonofalta','func_abonofalta.php?funcao_js=parent.js_preenchepesquisa|ed80_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_abonofalta.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>