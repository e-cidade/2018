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

//MODULO: Configuracoes
$clcadenderrua->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db72_descricao");

if ($db_opcao == 1){
  $db_action = "con1_cadenderrua004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $db_action = "con1_cadenderrua005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action = "con1_cadenderrua006.php";
}  


if (isset($db74_bairroinicial) && $db74_bairroinicial!="") {
  
  $oBairroInicial = new cl_cadenderbairro();
  $sSql = $oBairroInicial->sql_query_file(null, "db73_descricao", "", "db73_sequencial={$db74_bairroinicial}");
  $rsBairroInicial = $oBairroInicial->sql_record($sSql);
  $bi_descricao = db_utils::fieldsMemory($rsBairroInicial,0)->db73_descricao;
}

if (isset($db74_bairrofinal) && $db74_bairrofinal!="") {
  
  $oBairroFinal = new cl_cadenderbairro();
  $sSql = $oBairroFinal->sql_query_file(null, "db73_descricao", "", "db73_sequencial={$db74_bairrofinal}");
  $rsBairroFinal = $oBairroFinal->sql_record($sSql);
  $bf_descricao = db_utils::fieldsMemory($rsBairroFinal,0)->db73_descricao;
}

if (isset($db74_sequencial)) {
  if (isset($db74_bairroinicial)&&$db74_bairroinicial==0) { 
  	$db74_bairroinicial = '';
  	$bi_descricao       = '';
  }
  
  if (isset($db74_bairrofinal)&&$db74_bairrofinal==0) { 
    $db74_bairrofinal = '';
    $bf_descricao       = '';
  }
  
  $db74_numinicial    = isset($db74_numinicial)&&$db74_numinicial==0 ? '' : $db74_numinicial;
  $db74_numfinal      = isset($db74_numfinal)&&$db74_numfinal==0 ? '' : $db74_numfinal;
}

?>
<form name="form1" method="post" action="">
<center>

<table align=center style="margin-top: 15px;">
<tr><td>

<fieldset>
<legend><b>Ruas</b></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb74_sequencial?>">
       <?=@$Ldb74_sequencial?>
    </td>
    <td> 
<?
db_input('db74_sequencial',10,$Idb74_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb74_cadendermunicipio?>">
       <?
       db_ancora(@$Ldb74_cadendermunicipio,"js_pesquisadb74_cadendermunicipio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db74_cadendermunicipio',10,$Idb74_cadendermunicipio,true,'text',$db_opcao," onchange='js_pesquisadb74_cadendermunicipio(false);'")
?>
       <?
db_input('db72_descricao',26,$Idb72_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb74_descricao?>">
       <?=@$Ldb74_descricao?>
    </td>
    <td> 
<?
db_input('db74_descricao',40,$Idb74_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb74_bairroinicial?>">
       <?
       db_ancora(@$Ldb74_bairroinicial,"js_pesquisadb74_cadenderbairroinicial(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db74_bairroinicial',10,$Idb74_bairroinicial,true,'text',$db_opcao," onchange='js_pesquisadb74_cadenderbairroinicial(false);'")
?>
       <?
db_input('bi_descricao',26,$Idb72_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb74_bairrofinal?>">
       <?
       db_ancora(@$Ldb74_bairrofinal,"js_pesquisadb74_cadenderbairrofinal(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db74_bairrofinal',10,$Idb74_bairrofinal,true,'text',$db_opcao," onchange='js_pesquisadb74_cadenderbairrofinal(false);'")
?>
       <?
db_input('bf_descricao',26,$Idb72_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb74_numinicial?>">
       <?=@$Ldb74_numinicial?>
    </td>
    <td> 
<?
db_input('db74_numinicial',10,$Idb74_numinicial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb74_numfinal?>">
       <?=@$Ldb74_numfinal?>
    </td>
    <td> 
<?
db_input('db74_numfinal',10,$Idb74_numfinal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb74_cep?>">
       <?=@$Ldb74_cep?>
    </td>
    <td> 
<?
db_input('db74_cep',8,$Idb74_cep,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  
</fieldset>

</td></tr>
</table>  
  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb74_cadendermunicipio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadendermunicipio','func_cadendermunicipio.php?funcao_js=parent.js_mostracadendermunicipio1|db72_sequencial|db72_descricao','Pesquisa',true);
  }else{
     if(document.form1.db74_cadendermunicipio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadendermunicipio','func_cadendermunicipio.php?pesquisa_chave='+document.form1.db74_cadendermunicipio.value+'&funcao_js=parent.js_mostracadendermunicipio','Pesquisa',false);
     }else{
       document.form1.db72_descricao.value = ''; 
     }
  }
}
function js_mostracadendermunicipio(chave,erro){
  document.form1.db72_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db74_cadendermunicipio.focus(); 
    document.form1.db74_cadendermunicipio.value = ''; 
  }
}
function js_mostracadendermunicipio1(chave1,chave2){
  document.form1.db74_cadendermunicipio.value = chave1;
  document.form1.db72_descricao.value = chave2;
  db_iframe_cadendermunicipio.hide();
}

function js_pesquisadb74_cadenderbairroinicial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadenderrua','db_iframe_cadenderbairroinicial','func_cadenderbairro.php?funcao_js=parent.js_mostracadenderbairroinicial1|db73_sequencial|db73_descricao','Pesquisa',true);
  }else{
     if(document.form1.db74_bairroinicial.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cadenderrua','db_iframe_cadenderbairroinicial','func_cadenderbairro.php?pesquisa_chave='+document.form1.db74_bairroinicial.value+'&funcao_js=parent.js_mostracadenderbairroinicial','Pesquisa',false);
     }else{
       document.form1.bi_descricao.value = ''; 
     }
  }
}
function js_mostracadenderbairroinicial(chave,erro){
  document.form1.bi_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db74_bairroinicial.focus(); 
    document.form1.bi_descricao.value = ''; 
  }
}
function js_mostracadenderbairroinicial1(chave1,chave2){
  document.form1.db74_bairroinicial.value = chave1;
  document.form1.bi_descricao.value = chave2;
  db_iframe_cadenderbairroinicial.hide();
}


function js_pesquisadb74_cadenderbairrofinal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadenderrua','db_iframe_cadenderbairrofinal','func_cadenderbairro.php?funcao_js=parent.js_mostracadenderbairrofinal1|db73_sequencial|db73_descricao','Pesquisa',true);
  }else{
     if(document.form1.db74_bairrofinal.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cadenderrua','db_iframe_cadenderbairrofinal','func_cadenderbairro.php?pesquisa_chave='+document.form1.db74_bairrofinal.value+'&funcao_js=parent.js_mostracadenderbairrofinal','Pesquisa',false);
     }else{
       document.form1.bf_descricao.value = ''; 
     }
  }
}
function js_mostracadenderbairrofinal(chave,erro){
  document.form1.bf_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db74_bairrofinal.focus(); 
    document.form1.bf_descricao.value = ''; 
  }
}
function js_mostracadenderbairrofinal1(chave1,chave2){
  document.form1.db74_bairrofinal.value = chave1;
  document.form1.bf_descricao.value = chave2;
  db_iframe_cadenderbairrofinal.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_cadenderrua','db_iframe_cadenderrua','func_cadenderrua.php?funcao_js=parent.js_preenchepesquisa|db74_sequencial','Pesquisa',true,'0','1');
}
function js_preenchepesquisa(chave){
  db_iframe_cadenderrua.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>