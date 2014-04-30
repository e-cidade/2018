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
$clsau_subgrupo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd60_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd61_i_codigo?>">
       <?=@$Lsd61_i_codigo?>
    </td>
    <td>
<?
db_input('sd61_i_codigo',5,$Isd61_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd61_c_subgrupo?>">
       <?=@$Lsd61_c_subgrupo?>
    </td>
    <td>
<?
db_input('sd61_c_subgrupo',2,$Isd61_c_subgrupo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd61_i_grupo?>">
       <?
       db_ancora(@$Lsd61_i_grupo,"js_pesquisasd61_i_grupo(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd61_i_grupo',5,$Isd61_i_grupo,true,'text',$db_opcao," onchange='js_pesquisasd61_i_grupo(false);'")
?>
       <?
db_input('sd60_c_nome',60,$Isd60_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd61_c_nome?>">
       <?=@$Lsd61_c_nome?>
    </td>
    <td>
<?
db_input('sd61_c_nome',60,$Isd61_c_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd61_i_anocomp?>">
       <?=@$Lsd61_i_anocomp?>/<?=@$Lsd61_i_mescomp?>
    </td>
    <td>
<?
db_input('sd61_i_anocomp',4,$Isd61_i_anocomp,true,'text',$db_opcao,""); echo "/";
db_input('sd61_i_mescomp',2,$Isd61_i_mescomp,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd61_i_grupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_grupo','func_sau_grupo.php?funcao_js=parent.js_mostrasau_grupo1|sd60_i_codigo|sd60_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd61_i_grupo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_grupo','func_sau_grupo.php?pesquisa_chave='+document.form1.sd61_i_grupo.value+'&funcao_js=parent.js_mostrasau_grupo','Pesquisa',false);
     }else{
       document.form1.sd60_c_nome.value = '';
     }
  }
}
function js_mostrasau_grupo(chave,erro){
  document.form1.sd60_c_nome.value = chave;
  if(erro==true){
    document.form1.sd61_i_grupo.focus();
    document.form1.sd61_i_grupo.value = '';
  }
}
function js_mostrasau_grupo1(chave1,chave2){
  document.form1.sd61_i_grupo.value = chave1;
  document.form1.sd60_c_nome.value = chave2;
  db_iframe_sau_grupo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_subgrupo','func_sau_subgrupo.php?funcao_js=parent.js_preenchepesquisa|sd61_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_subgrupo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>