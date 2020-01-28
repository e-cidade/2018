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
$clsau_formaorganizacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd60_c_nome");
$clrotulo->label("sd61_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd62_i_codigo?>">
       <?=@$Lsd62_i_codigo?>
    </td>
    <td>
<?
db_input('sd62_i_codigo',5,$Isd62_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd62_i_grupo?>">
       <?
       db_ancora(@$Lsd62_i_grupo,"js_pesquisasd62_i_grupo(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd62_i_grupo',5,$Isd62_i_grupo,true,'text',$db_opcao," onchange='js_pesquisasd62_i_grupo(false);'")
?>
       <?
db_input('sd60_c_nome',60,$Isd60_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd62_i_subgrupo?>">
       <?
       db_ancora(@$Lsd62_i_subgrupo,"js_pesquisasd62_i_subgrupo(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd62_i_subgrupo',5,$Isd62_i_subgrupo,true,'text',$db_opcao," onchange='js_pesquisasd62_i_subgrupo(false);'")
?>
       <?
db_input('sd61_c_nome',60,$Isd61_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd62_c_formaorganizacao?>">
       <?=@$Lsd62_c_formaorganizacao?>
    </td>
    <td>
<?
db_input('sd62_c_formaorganizacao',2,$Isd62_c_formaorganizacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd62_c_nome?>">
       <?=@$Lsd62_c_nome?>
    </td>
    <td>
<?
db_input('sd62_c_nome',60,$Isd62_c_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd62_i_anocomp?>">
       <?=@$Lsd62_i_anocomp?>/<?=@$Lsd62_i_mescomp?>
    </td>
    <td>
<?
db_input('sd62_i_anocomp',4,$Isd62_i_anocomp,true,'text',$db_opcao,""); echo "/";
db_input('sd62_i_mescomp',2,$Isd62_i_mescomp,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd62_i_grupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_grupo','func_sau_grupo.php?funcao_js=parent.js_mostrasau_grupo1|sd60_i_codigo|sd60_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd62_i_grupo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_grupo','func_sau_grupo.php?pesquisa_chave='+document.form1.sd62_i_grupo.value+'&funcao_js=parent.js_mostrasau_grupo','Pesquisa',false);
     }else{
       document.form1.sd60_c_nome.value = '';
     }
  }
}
function js_mostrasau_grupo(chave,erro){
  document.form1.sd60_c_nome.value = chave;
  if(erro==true){
    document.form1.sd62_i_grupo.focus();
    document.form1.sd62_i_grupo.value = '';
  }
}
function js_mostrasau_grupo1(chave1,chave2){
  document.form1.sd62_i_grupo.value = chave1;
  document.form1.sd60_c_nome.value = chave2;
  db_iframe_sau_grupo.hide();
}
function js_pesquisasd62_i_subgrupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_subgrupo','func_sau_subgrupo.php?funcao_js=parent.js_mostrasau_subgrupo1|sd61_i_codigo|sd61_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd62_i_subgrupo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_subgrupo','func_sau_subgrupo.php?pesquisa_chave='+document.form1.sd62_i_subgrupo.value+'&funcao_js=parent.js_mostrasau_subgrupo','Pesquisa',false);
     }else{
       document.form1.sd61_c_nome.value = '';
     }
  }
}
function js_mostrasau_subgrupo(chave,erro){
  document.form1.sd61_c_nome.value = chave;
  if(erro==true){
    document.form1.sd62_i_subgrupo.focus();
    document.form1.sd62_i_subgrupo.value = '';
  }
}
function js_mostrasau_subgrupo1(chave1,chave2){
  document.form1.sd62_i_subgrupo.value = chave1;
  document.form1.sd61_c_nome.value = chave2;
  db_iframe_sau_subgrupo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_formaorganizacao','func_sau_formaorganizacao.php?funcao_js=parent.js_preenchepesquisa|sd62_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_formaorganizacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>