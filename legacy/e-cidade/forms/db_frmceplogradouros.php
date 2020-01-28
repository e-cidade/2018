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
$clceplogradouros->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cp01_bairro");
$clrotulo->label("cp01_bairro");
$clrotulo->label("cp05_localidades");
if ($db_opcao==1){
  $opc = 1;
}else{
  $opc = 3;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcp06_codlogradouro?>">
       <?//=@$Lcp06_codlogradouro?>
       <?db_ancora(@$Lcp06_codlogradouro,"js_pesquisacp06_codlogradouro(true);",$opc);?>
    </td>
    <td> 
<?
db_input('cp06_codlogradouro',10,$Icp06_codlogradouro,true,'text',3,"");
db_input('cp06_sequencial',10,$Icp06_sequencial,true,'hidden',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_codbairroinicial?>">
       <?
       db_ancora(@$Lcp06_codbairroinicial,"js_pesquisacp06_codbairroinicial(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cp06_codbairroinicial',10,$Icp06_codbairroinicial,true,'text',$db_opcao," onchange='js_pesquisacp06_codbairroinicial(false);'")
?>
       <?
db_input('cp01_bairro_ini',72,$Icp01_bairro,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_codbairrofinal?>">
       <?
       db_ancora(@$Lcp06_codbairrofinal,"js_pesquisacp06_codbairrofinal(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cp06_codbairrofinal',10,$Icp06_codbairrofinal,true,'text',$db_opcao," onchange='js_pesquisacp06_codbairrofinal(false);'")
?>
       <?
db_input('cp01_bairro_fim',72,$Icp01_bairro,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_logradouro?>">
       <?=@$Lcp06_logradouro?>
    </td>
    <td> 
<?
db_input('cp06_logradouro',72,$Icp06_logradouro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_adicional?>">
       <?=@$Lcp06_adicional?>
    </td>
    <td> 
<?
db_input('cp06_adicional',72,$Icp06_adicional,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_cep?>">
       <?=@$Lcp06_cep?>
    </td>
    <td> 
<?
db_input('cp06_cep',8,$Icp06_cep,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_grandeusuario?>">
       <?=@$Lcp06_grandeusuario?>
    </td>
    <td> 
<?
db_input('cp06_grandeusuario',1,$Icp06_grandeusuario,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_numinicial?>">
       <?=@$Lcp06_numinicial?>
    </td>
    <td> 
<?
db_input('cp06_numinicial',10,$Icp06_numinicial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_numfinal?>">
       <?=@$Lcp06_numfinal?>
    </td>
    <td> 
<?
db_input('cp06_numfinal',10,$Icp06_numfinal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_lado?>">
       <?=@$Lcp06_lado?>
    </td>
    <td> 
<?
db_input('cp06_lado',1,$Icp06_lado,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_codseccao?>">
       <?=@$Lcp06_codseccao?>
    </td>
    <td> 
<?
db_input('cp06_codseccao',10,$Icp06_codseccao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_sigla?>">
       <?=@$Lcp06_sigla?>
    </td>
    <td> 
<?
db_input('cp06_sigla',2,$Icp06_sigla,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp06_codlocalidade?>">
       <?
       db_ancora(@$Lcp06_codlocalidade,"js_pesquisacp06_codlocalidade(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cp06_codlocalidade',10,$Icp06_codlocalidade,true,'text',$db_opcao," onchange='js_pesquisacp06_codlocalidade(false);'")
?>
       <?
db_input('cp05_localidades',72,$Icp05_localidades,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacp06_codbairroinicial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cepbairros','func_cepbairros.php?funcao_js=parent.js_mostracepbairros1|cp01_codbairro|cp01_bairro','Pesquisa',true);
  }else{
     if(document.form1.cp06_codbairroinicial.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cepbairros','func_cepbairros.php?pesquisa_chave='+document.form1.cp06_codbairroinicial.value+'&funcao_js=parent.js_mostracepbairros','Pesquisa',false);
     }else{
       document.form1.cp01_bairro_ini.value = ''; 
     }
  }
}
function js_mostracepbairros(chave,erro){
  document.form1.cp01_bairro_ini.value = chave; 
  if(erro==true){ 
    document.form1.cp06_codbairroinicial.focus(); 
    document.form1.cp06_codbairroinicial.value = ''; 
  }
}
function js_mostracepbairros1(chave1,chave2){
  document.form1.cp06_codbairroinicial.value = chave1;
  document.form1.cp01_bairro_ini.value = chave2;
  db_iframe_cepbairros.hide();
}
function js_pesquisacp06_codbairrofinal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cepbairros','func_cepbairros.php?funcao_js=parent.js_mostracepbairros3|cp01_codbairro|cp01_bairro','Pesquisa',true);
  }else{
     if(document.form1.cp06_codbairrofinal.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cepbairros','func_cepbairros.php?pesquisa_chave='+document.form1.cp06_codbairrofinal.value+'&funcao_js=parent.js_mostracepbairros2','Pesquisa',false);
     }else{
       document.form1.cp01_bairro_fim.value = ''; 
     }
  }
}
function js_mostracepbairros2(chave,erro){
  document.form1.cp01_bairro_fim.value = chave; 
  if(erro==true){ 
    document.form1.cp06_codbairrofinal.focus(); 
    document.form1.cp06_codbairrofinal.value = ''; 
  }
}
function js_mostracepbairros3(chave4,chave5){
  document.form1.cp06_codbairrofinal.value = chave4;
  document.form1.cp01_bairro_fim.value = chave5;
  db_iframe_cepbairros.hide();
}
function js_pesquisacp06_codlocalidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ceplocalidades','func_ceplocalidades.php?funcao_js=parent.js_mostraceplocalidades1|cp05_codlocalidades|cp05_localidades','Pesquisa',true);
  }else{
     if(document.form1.cp06_codlocalidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_ceplocalidades','func_ceplocalidades.php?pesquisa_chave='+document.form1.cp06_codlocalidade.value+'&funcao_js=parent.js_mostraceplocalidades','Pesquisa',false);
     }else{
       document.form1.cp05_localidades.value = ''; 
     }
  }
}
function js_mostraceplocalidades(chave,erro){
  document.form1.cp05_localidades.value = chave; 
  if(erro==true){ 
    document.form1.cp06_codlocalidade.focus(); 
    document.form1.cp06_codlocalidade.value = ''; 
  }
}
function js_mostraceplocalidades1(chave1,chave2){
  document.form1.cp06_codlocalidade.value = chave1;
  document.form1.cp05_localidades.value = chave2;
  db_iframe_ceplocalidades.hide();
}
function js_pesquisacp06_codlogradouro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ceplogradouros','func_ceplogradouros.php?funcao_js=parent.js_mostralograd1|cp06_codlogradouro','Pesquisa',true);
  }else{
     if(document.form1.cp06_codlogradouro.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_ceplogradouros','func_ceplogradouros.php?pesquisa_chave='+document.form1.cp06_codlogradouro+'&funcao_js=parent.js_mostralograd','Pesquisa',false);
     }else{
       document.form1.cp06_codlogradouro.value = ''; 
     }
  }
}
function js_mostralograd(chave,erro){   
  if(erro==true){ 
    document.form1.cp06_codlogradouro.focus(); 
    document.form1.cp06_codlogradouro.value = ''; 
  }
}
function js_mostralograd1(chave1){
  document.form1.cp06_codlogradouro.value = chave1;  
  db_iframe_ceplogradouros.hide();
  document.form1.submit();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_ceplogradouros','func_ceplogradouros.php?funcao_js=parent.js_preenchepesquisa|cp06_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ceplogradouros.hide();
//  alert(chave);
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>