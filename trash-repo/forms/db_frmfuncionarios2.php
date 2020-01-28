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
$clfuncionarios->rotulo->label();
$clrotulo = new rotulocampo;
?>
<br>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="Escola">
       <?
       db_ancora("<b>Escola:</b>","js_pesquisaed02_i_codigo(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed02_i_codigo',5,$ed02_i_codigo,true,'text',$db_opcao," onchange='js_pesquisaed02_i_codigo(false);'")
?>
       <?
db_input('z01_nome',40,$z01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  <input name='Processar' type='button' value='Processar' onclick="EnviaForm()">
  </center>
</form>
<iframe id="frame" name="frame" src="edu3_funcionarios002.php" width="450" height="300" scrolling="yes"></iframe>
<script>
function js_pesquisaed02_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?funcao_js=parent.js_mostraescolas1|ed02_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ed02_i_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?pesquisa_chave='+document.form1.ed02_i_codigo.value+'&funcao_js=parent.js_mostraescolas','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostraescolas(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.ed02_i_codigo.focus();
    document.form1.ed02_i_codigo.value = '';
  }
}
function js_mostraescolas1(chave1,chave2){
  document.form1.ed02_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_escolas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?funcao_js=parent.js_preenchepesquisa|ed02_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_escolas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function EnviaForm(){
 if(document.form1.ed02_i_codigo.value==""){
  alert("Preencha o campo Escola");
  document.form1.ed02_i_codigo.focus();
  return false;
 }
 this.frame.location.href="edu3_funcionarios002.php?Processar&ed02_i_codigo="+document.form1.ed02_i_codigo.value;
}
</script>