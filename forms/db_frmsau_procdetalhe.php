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
$clsau_procdetalhe->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd63_c_nome");
$clrotulo->label("sd73_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd74_i_codigo?>">
       <?=@$Lsd74_i_codigo?>
    </td>
    <td>
<?
db_input('sd74_i_codigo',5,$Isd74_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd74_i_procedimento?>">
       <?
       db_ancora(@$Lsd74_i_procedimento,"js_pesquisasd74_i_procedimento(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd74_i_procedimento',5,$Isd74_i_procedimento,true,'text',$db_opcao," onchange='js_pesquisasd74_i_procedimento(false);'")
?>
       <?
db_input('sd63_c_nome',60,$Isd63_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd74_i_detalhe?>">
       <?
       db_ancora(@$Lsd74_i_detalhe,"js_pesquisasd74_i_detalhe(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd74_i_detalhe',5,$Isd74_i_detalhe,true,'text',$db_opcao," onchange='js_pesquisasd74_i_detalhe(false);'")
?>
       <?
db_input('sd73_c_nome',60,$Isd73_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd74_i_anocomp?>">
       <?=@$Lsd74_i_anocomp?>/<?=@$Lsd74_i_mescomp?>
    </td>
    <td>
<?
db_input('sd74_i_anocomp',4,$Isd74_i_anocomp,true,'text',$db_opcao,""); echo "/";
db_input('sd74_i_mescomp',2,$Isd74_i_mescomp,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd74_i_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?funcao_js=parent.js_mostrasau_procedimento1|sd63_i_codigo|sd63_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd74_i_procedimento.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?pesquisa_chave='+document.form1.sd74_i_procedimento.value+'&funcao_js=parent.js_mostrasau_procedimento','Pesquisa',false);
     }else{
       document.form1.sd63_c_nome.value = '';
     }
  }
}
function js_mostrasau_procedimento(chave,erro){
  document.form1.sd63_c_nome.value = chave;
  if(erro==true){
    document.form1.sd74_i_procedimento.focus();
    document.form1.sd74_i_procedimento.value = '';
  }
}
function js_mostrasau_procedimento1(chave1,chave2){
  document.form1.sd74_i_procedimento.value = chave1;
  document.form1.sd63_c_nome.value = chave2;
  db_iframe_sau_procedimento.hide();
}
function js_pesquisasd74_i_detalhe(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_detalhe','func_sau_detalhe.php?funcao_js=parent.js_mostrasau_detalhe1|sd73_i_codigo|sd73_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd74_i_detalhe.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_detalhe','func_sau_detalhe.php?pesquisa_chave='+document.form1.sd74_i_detalhe.value+'&funcao_js=parent.js_mostrasau_detalhe','Pesquisa',false);
     }else{
       document.form1.sd73_c_nome.value = '';
     }
  }
}
function js_mostrasau_detalhe(chave,erro){
  document.form1.sd73_c_nome.value = chave;
  if(erro==true){
    document.form1.sd74_i_detalhe.focus();
    document.form1.sd74_i_detalhe.value = '';
  }
}
function js_mostrasau_detalhe1(chave1,chave2){
  document.form1.sd74_i_detalhe.value = chave1;
  document.form1.sd73_c_nome.value = chave2;
  db_iframe_sau_detalhe.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_procdetalhe','func_sau_procdetalhe.php?funcao_js=parent.js_preenchepesquisa|sd74_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_procdetalhe.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>