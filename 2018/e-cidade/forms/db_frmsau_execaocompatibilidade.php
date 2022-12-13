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

//MODULO: saude
$clsau_execaocompatibilidade->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd63_c_nome");
$clrotulo->label("sd63_c_nome");
$clrotulo->label("sd84_c_nome");
$clrotulo->label("sd63_c_nome");
$clrotulo->label("sd84_c_nome");
$clrotulo->label("sd68_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd67_i_codigo?>">
       <?=@$Lsd67_i_codigo?>
    </td>
    <td>
<?
db_input('sd67_i_codigo',5,$Isd67_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd67_i_procrestricao?>">
       <?
       db_ancora(@$Lsd67_i_procrestricao,"js_pesquisasd67_i_procrestricao(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd67_i_procrestricao',5,$Isd67_i_procrestricao,true,'text',$db_opcao," onchange='js_pesquisasd67_i_procrestricao(false);'")
?>
       <?
db_input('sd63_c_nomerestricao',60,@$sd63_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd67_i_procprincipal?>">
       <?
       db_ancora(@$Lsd67_i_procprincipal,"js_pesquisasd67_i_procprincipal(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd67_i_procprincipal',5,$Isd67_i_procprincipal,true,'text',$db_opcao," onchange='js_pesquisasd67_i_procprincipal(false);'")
?>
       <?
db_input('sd63_c_nomeprincipal',60,$Isd63_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd67_i_regprincipal?>">
       <?
       db_ancora(@$Lsd67_i_regprincipal,"js_pesquisasd67_i_regprincipal(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd67_i_regprincipal',5,$Isd67_i_regprincipal,true,'text',$db_opcao," onchange='js_pesquisasd67_i_regprincipal(false);'")
?>
       <?
db_input('sd84_c_nomeprincipal',60,$Isd84_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd67_i_proccompativel?>">
       <?
       db_ancora(@$Lsd67_i_proccompativel,"js_pesquisasd67_i_proccompativel(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd67_i_proccompativel',5,$Isd67_i_proccompativel,true,'text',$db_opcao," onchange='js_pesquisasd67_i_proccompativel(false);'")
?>
       <?
db_input('sd63_c_nomecompativel',60,$Isd63_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd67_i_regcompativel?>">
       <?
       db_ancora(@$Lsd67_i_regcompativel,"js_pesquisasd67_i_regcompativel(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd67_i_regcompativel',5,$Isd67_i_regcompativel,true,'text',$db_opcao," onchange='js_pesquisasd67_i_regcompativel(false);'")
?>
       <?
db_input('sd84_c_nomeregcomp',60,$Isd84_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd67_i_compatibilidade?>">
       <?=@$Lsd67_i_compatibilidade?>
    </td>
    <td>
       <?
       include("classes/db_sau_tipocompatibilidade_classe.php");
       $clsau_tipocompatibilidade = new cl_sau_tipocompatibilidade;
       $result = $clsau_tipocompatibilidade->sql_record($clsau_tipocompatibilidade->sql_query("","*"));
       db_selectrecord("sd67_i_compatibilidade",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd67_i_anocomp?>">
       <?=@$Lsd67_i_anocomp?><?=@$Lsd67_i_mescomp?>
    </td>
    <td>
<?
db_input('sd67_i_anocomp',4,$Isd67_i_anocomp,true,'text',$db_opcao,""); echo "/";
db_input('sd67_i_mescomp',2,$Isd67_i_mescomp,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd67_i_procrestricao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?funcao_js=parent.js_mostrasau_procrestricao1|sd63_i_codigo|sd63_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd67_i_procrestricao.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?pesquisa_chave='+document.form1.sd67_i_procrestricao.value+'&funcao_js=parent.js_mostrasau_procrestricao','Pesquisa',false);
     }else{
       document.form1.sd63_c_nome.value = '';
     }
  }
}

function js_mostrasau_procrestricao(chave,erro){
  document.form1.sd63_c_nomerestricao.value = chave;
  if(erro==true){
    document.form1.sd67_i_procrestricao.focus();
    document.form1.sd67_i_procrestricao.value = '';
  }
}
function js_mostrasau_procrestricao1(chave1,chave2){
  document.form1.sd67_i_procrestricao.value = chave1;
  document.form1.sd63_c_nomerestricao.value = chave2;
  db_iframe_sau_procedimento.hide();
}


function js_pesquisasd67_i_procprincipal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?funcao_js=parent.js_mostrasau_procprincipal1|sd63_i_codigo|sd63_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd67_i_procprincipal.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?pesquisa_chave='+document.form1.sd67_i_procprincipal.value+'&funcao_js=parent.js_mostrasau_procprincipal','Pesquisa',false);
     }else{
       document.form1.sd63_c_nome.value = '';
     }
  }
}
function js_mostrasau_procprincipal(chave,erro){
  document.form1.sd63_c_nomeprincipal.value = chave;
  if(erro==true){
    document.form1.sd67_i_procprincipal.focus();
    document.form1.sd67_i_procprincipal.value = '';
  }
}
function js_mostrasau_procprincipal1(chave1,chave2){
  document.form1.sd67_i_procprincipal.value = chave1;
  document.form1.sd63_c_nomeprincipal.value = chave2;
  db_iframe_sau_procedimento.hide();
}


function js_pesquisasd67_i_regprincipal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_registro','func_sau_registro.php?funcao_js=parent.js_mostrasau_regprincipal1|sd84_i_codigo|sd84_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd67_i_regprincipal.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_registro','func_sau_registro.php?pesquisa_chave='+document.form1.sd67_i_regprincipal.value+'&funcao_js=parent.js_mostrasau_regprincipal','Pesquisa',false);
     }else{
       document.form1.sd84_c_nomeprincipal.value = '';
     }
  }
}
function js_mostrasau_regprincipal(chave,erro){
  document.form1.sd84_c_nomeprincipal.value = chave;
  if(erro==true){
    document.form1.sd67_i_regprincipal.focus();
    document.form1.sd67_i_regprincipal.value = '';
  }
}
function js_mostrasau_regprincipal1(chave1,chave2){
  document.form1.sd67_i_regprincipal.value = chave1;
  document.form1.sd84_c_nomeprincipal.value = chave2;
  db_iframe_sau_registro.hide();
}


function js_pesquisasd67_i_proccompativel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?funcao_js=parent.js_mostrasau_proccompativel1|sd63_i_codigo|sd63_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd67_i_proccompativel.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?pesquisa_chave='+document.form1.sd67_i_proccompativel.value+'&funcao_js=parent.js_mostrasau_proccompativel','Pesquisa',false);
     }else{
       document.form1.sd63_c_nomecompativel.value = '';
     }
  }
}
function js_mostrasau_proccompativel(chave,erro){
  document.form1.sd63_c_nomecompativel.value = chave;
  if(erro==true){
    document.form1.sd67_i_proccompativel.focus();
    document.form1.sd67_i_proccompativel.value = '';
  }
}

function js_mostrasau_proccompativel1(chave1,chave2){
  document.form1.sd67_i_proccompativel.value = chave1;
  document.form1.sd63_c_nomecompativel.value = chave2;
  db_iframe_sau_procedimento.hide();
}


function js_pesquisasd67_i_regcompativel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_registro','func_sau_registro.php?funcao_js=parent.js_mostrasau_regcompativel1|sd84_i_codigo|sd84_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd67_i_regcompativel.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_registro','func_sau_registro.php?pesquisa_chave='+document.form1.sd67_i_regcompativel.value+'&funcao_js=parent.js_mostrasau_regcompativel','Pesquisa',false);
     }else{
       document.form1.sd84_c_nomeregcomp.value = '';
     }
  }
}
function js_mostrasau_regcompativel(chave,erro){
  document.form1.sd84_c_nomeregcomp.value = chave;
  if(erro==true){
    document.form1.sd67_i_regcompativel.focus();
    document.form1.sd67_i_regcompativel.value = '';
  }
}
function js_mostrasau_regcompativel1(chave1,chave2){
  document.form1.sd67_i_regcompativel.value = chave1;
  document.form1.sd84_c_nomeregcomp.value = chave2;
  db_iframe_sau_registro.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_execaocompatibilidade','func_sau_execaocompatibilidade.php?funcao_js=parent.js_preenchepesquisa|sd67_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_execaocompatibilidade.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>