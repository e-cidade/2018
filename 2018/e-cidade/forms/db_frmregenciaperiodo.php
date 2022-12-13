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
$clregenciaperiodo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed41_i_codigo");
$clrotulo->label("ed59_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted78_i_codigo?>">
       <?=@$Led78_i_codigo?>
    </td>
    <td> 
<?
db_input('ed78_i_codigo',10,$Ied78_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted78_i_regencia?>">
       <?
       db_ancora(@$Led78_i_regencia,"js_pesquisaed78_i_regencia(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed78_i_regencia',10,$Ied78_i_regencia,true,'text',$db_opcao," onchange='js_pesquisaed78_i_regencia(false);'")
?>
       <?
db_input('ed59_i_codigo',10,$Ied59_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted78_i_procavaliacao?>">
       <?
       db_ancora(@$Led78_i_procavaliacao,"js_pesquisaed78_i_procavaliacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed78_i_procavaliacao',10,$Ied78_i_procavaliacao,true,'text',$db_opcao," onchange='js_pesquisaed78_i_procavaliacao(false);'")
?>
       <?
db_input('ed41_i_codigo',10,$Ied41_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted78_i_aulasdadas?>">
       <?=@$Led78_i_aulasdadas?>
    </td>
    <td> 
<?
db_input('ed78_i_aulasdadas',10,$Ied78_i_aulasdadas,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed78_i_procavaliacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procavaliacao','func_procavaliacao.php?funcao_js=parent.js_mostraprocavaliacao1|ed41_i_codigo|ed41_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed78_i_procavaliacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procavaliacao','func_procavaliacao.php?pesquisa_chave='+document.form1.ed78_i_procavaliacao.value+'&funcao_js=parent.js_mostraprocavaliacao','Pesquisa',false);
     }else{
       document.form1.ed41_i_codigo.value = ''; 
     }
  }
}
function js_mostraprocavaliacao(chave,erro){
  document.form1.ed41_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed78_i_procavaliacao.focus(); 
    document.form1.ed78_i_procavaliacao.value = ''; 
  }
}
function js_mostraprocavaliacao1(chave1,chave2){
  document.form1.ed78_i_procavaliacao.value = chave1;
  document.form1.ed41_i_codigo.value = chave2;
  db_iframe_procavaliacao.hide();
}
function js_pesquisaed78_i_regencia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_regencia','func_regencia.php?funcao_js=parent.js_mostraregencia1|ed59_i_codigo|ed59_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed78_i_regencia.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_regencia','func_regencia.php?pesquisa_chave='+document.form1.ed78_i_regencia.value+'&funcao_js=parent.js_mostraregencia','Pesquisa',false);
     }else{
       document.form1.ed59_i_codigo.value = ''; 
     }
  }
}
function js_mostraregencia(chave,erro){
  document.form1.ed59_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed78_i_regencia.focus(); 
    document.form1.ed78_i_regencia.value = ''; 
  }
}
function js_mostraregencia1(chave1,chave2){
  document.form1.ed78_i_regencia.value = chave1;
  document.form1.ed59_i_codigo.value = chave2;
  db_iframe_regencia.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_regenciaperiodo','func_regenciaperiodo.php?funcao_js=parent.js_preenchepesquisa|ed78_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_regenciaperiodo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>