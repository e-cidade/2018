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
$clsau_proccompativel->rotulo->label();
$clrotulo = new rotulocampo;
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
    <td nowrap title="<?=@$Tsd66_i_codigo?>">
       <?=@$Lsd66_i_codigo?>
    </td>
    <td>
<?
db_input('sd66_i_codigo',5,$Isd66_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd66_i_procprincipal?>">
       <?
       db_ancora(@$Lsd66_i_procprincipal,"js_pesquisasd66_i_procprincipal(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd66_i_procprincipal',5,$Isd66_i_procprincipal,true,'text',$db_opcao," onchange='js_pesquisasd66_i_procprincipal(false);'")
?>
       <?
db_input('sd63_c_nome',60,$Isd63_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd66_i_regprincipal?>">
       <?=@$Lsd66_i_regprincipal?>
    </td>
    <td>
       <?
       include("classes/db_sau_registro_classe.php");
       $clsau_registro = new cl_sau_registro;
       $result = $clsau_registro->sql_record($clsau_registro->sql_query("","sd84_i_codigo, sd84_c_nome"));
       db_selectrecord("sd66_i_regprincipal",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd66_i_proccompativel?>">
       <?
       db_ancora(@$Lsd66_i_proccompativel,"js_pesquisasd66_i_proccompativel(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd66_i_proccompativel',5,$Isd66_i_proccompativel,true,'text',$db_opcao," onchange='js_pesquisasd66_i_proccompativel(false);'")
?>
       <?
db_input('sd63_c_nomecomp',60,$Isd63_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd66_i_regcompativel?>">
       <?=@$Lsd66_i_regcompativel?>
    </td>
    <td>
       <?
       $result = $clsau_registro->sql_record($clsau_registro->sql_query("","sd84_i_codigo, sd84_c_nome"));
       db_selectrecord("sd66_i_regcompativel",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd66_i_compatibilidade?>">
       <?=@$Lsd66_i_compatibilidade?>
    </td>
    <td>
       <?
       include("classes/db_sau_tipocompatibilidade_classe.php");
       $clsau_tipocompatibilidade = new cl_sau_tipocompatibilidade;
       $result = $clsau_tipocompatibilidade->sql_record($clsau_tipocompatibilidade->sql_query("","*"));
       db_selectrecord("sd66_i_compatibilidade",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd66_i_qtd?>">
       <?=@$Lsd66_i_qtd?>
    </td>
    <td>
<?
db_input('sd66_i_qtd',4,$Isd66_i_qtd,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd66_i_anocomp?>">
       <?=@$Lsd66_i_anocomp?>/<?=@$Lsd66_i_mescomp?>
    </td>
    <td>
<?
db_input('sd66_i_anocomp',4,$Isd66_i_anocomp,true,'text',$db_opcao,""); echo "/";
db_input('sd66_i_mescomp',2,$Isd66_i_mescomp,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd66_i_procprincipal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?funcao_js=parent.js_mostrasau_procedimento1|sd63_i_codigo|sd63_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd66_i_procprincipal.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?pesquisa_chave='+document.form1.sd66_i_procprincipal.value+'&funcao_js=parent.js_mostrasau_procedimento','Pesquisa',false);
     }else{
       document.form1.sd63_c_nome.value = '';
     }
  }
}

function js_mostrasau_procedimento(chave,erro){
  document.form1.sd63_c_nome.value = chave;
  if(erro==true){
    document.form1.sd66_i_procprincipal.focus();
    document.form1.sd66_i_procprincipal.value = '';
  }
}

function js_mostrasau_procedimento1(chave1,chave2){
  document.form1.sd66_i_procprincipal.value = chave1;
  document.form1.sd63_c_nome.value = chave2;
  db_iframe_sau_procedimento.hide();
}


function js_pesquisasd66_i_proccompativel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?funcao_js=parent.js_mostrasau_proccompativel1|sd63_i_codigo|sd63_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd66_i_proccompativel.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?pesquisa_chave='+document.form1.sd66_i_proccompativel.value+'&funcao_js=parent.js_mostrasau_proccompativel','Pesquisa',false);
     }else{
       document.form1.sd63_c_nomecomp.value = '';
     }
  }
}

function js_mostrasau_proccompativel(chave,erro){
  document.form1.sd63_c_nomecomp.value = chave;
  if(erro==true){
    document.form1.sd66_i_proccompativel.focus();
    document.form1.sd66_i_proccompativel.value = '';
  }
}

function js_mostrasau_proccompativel1(chave1,chave2){
  document.form1.sd66_i_proccompativel.value = chave1;
  document.form1.sd63_c_nomecomp.value = chave2;
  db_iframe_sau_procedimento.hide();
}


function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_proccompativel','func_sau_proccompativel.php?funcao_js=parent.js_preenchepesquisa|sd66_i_codigo','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_sau_proccompativel.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>