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

//MODULO: ouvidoria
$clcidadaotelefone->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ov02_sequencial");
$clrotulo->label("ov02_sequencial");
$clrotulo->label("ov02_sequencial");
$clrotulo->label("ov02_sequencial");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tov07_sequencial?>">
       <?=@$Lov07_sequencial?>
    </td>
    <td> 
<?
db_input('ov07_sequencial',10,$Iov07_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tov07_seq?>">
       <?
       db_ancora(@$Lov07_seq,"js_pesquisaov07_seq(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ov07_seq',10,$Iov07_seq,true,'text',$db_opcao," onchange='js_pesquisaov07_seq(false);'")
?>
       <?
db_input('ov02_sequencial',10,$Iov02_sequencial,true,'text',3,'')
db_input('ov02_sequencial',10,$Iov02_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tov07_cidadao?>">
       <?
       db_ancora(@$Lov07_cidadao,"js_pesquisaov07_cidadao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ov07_cidadao',10,$Iov07_cidadao,true,'text',$db_opcao," onchange='js_pesquisaov07_cidadao(false);'")
?>
       <?
db_input('ov02_sequencial',10,$Iov02_sequencial,true,'text',3,'')
db_input('ov02_sequencial',10,$Iov02_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tov07_numero?>">
       <?=@$Lov07_numero?>
    </td>
    <td> 
<?
db_input('ov07_numero',10,$Iov07_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tov07_tipotelefone?>">
       <?=@$Lov07_tipotelefone?>
    </td>
    <td> 
<?
db_input('ov07_tipotelefone',10,$Iov07_tipotelefone,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tov07_ddd?>">
       <?=@$Lov07_ddd?>
    </td>
    <td> 
<?
db_input('ov07_ddd',10,$Iov07_ddd,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tov07_ramal?>">
       <?=@$Lov07_ramal?>
    </td>
    <td> 
<?
db_input('ov07_ramal',10,$Iov07_ramal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tov07_obs?>">
       <?=@$Lov07_obs?>
    </td>
    <td> 
<?
db_textarea('ov07_obs',0,0,$Iov07_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaov07_seq(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cidadao','func_cidadao.php?funcao_js=parent.js_mostracidadao1|ov02_seq|ov02_sequencial','Pesquisa',true);
  }else{
     if(document.form1.ov07_seq.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cidadao','func_cidadao.php?pesquisa_chave='+document.form1.ov07_seq.value+'&funcao_js=parent.js_mostracidadao','Pesquisa',false);
     }else{
       document.form1.ov02_sequencial.value = ''; 
     }
  }
}
function js_mostracidadao(chave,erro){
  document.form1.ov02_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.ov07_seq.focus(); 
    document.form1.ov07_seq.value = ''; 
  }
}
function js_mostracidadao1(chave1,chave2){
  document.form1.ov07_seq.value = chave1;
  document.form1.ov02_sequencial.value = chave2;
  db_iframe_cidadao.hide();
}
function js_pesquisaov07_seq(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cidadao','func_cidadao.php?funcao_js=parent.js_mostracidadao1|ov02_sequencial|ov02_sequencial','Pesquisa',true);
  }else{
     if(document.form1.ov07_seq.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cidadao','func_cidadao.php?pesquisa_chave='+document.form1.ov07_seq.value+'&funcao_js=parent.js_mostracidadao','Pesquisa',false);
     }else{
       document.form1.ov02_sequencial.value = ''; 
     }
  }
}
function js_mostracidadao(chave,erro){
  document.form1.ov02_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.ov07_seq.focus(); 
    document.form1.ov07_seq.value = ''; 
  }
}
function js_mostracidadao1(chave1,chave2){
  document.form1.ov07_seq.value = chave1;
  document.form1.ov02_sequencial.value = chave2;
  db_iframe_cidadao.hide();
}
function js_pesquisaov07_cidadao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cidadao','func_cidadao.php?funcao_js=parent.js_mostracidadao1|ov02_seq|ov02_sequencial','Pesquisa',true);
  }else{
     if(document.form1.ov07_cidadao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cidadao','func_cidadao.php?pesquisa_chave='+document.form1.ov07_cidadao.value+'&funcao_js=parent.js_mostracidadao','Pesquisa',false);
     }else{
       document.form1.ov02_sequencial.value = ''; 
     }
  }
}
function js_mostracidadao(chave,erro){
  document.form1.ov02_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.ov07_cidadao.focus(); 
    document.form1.ov07_cidadao.value = ''; 
  }
}
function js_mostracidadao1(chave1,chave2){
  document.form1.ov07_cidadao.value = chave1;
  document.form1.ov02_sequencial.value = chave2;
  db_iframe_cidadao.hide();
}
function js_pesquisaov07_cidadao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cidadao','func_cidadao.php?funcao_js=parent.js_mostracidadao1|ov02_sequencial|ov02_sequencial','Pesquisa',true);
  }else{
     if(document.form1.ov07_cidadao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cidadao','func_cidadao.php?pesquisa_chave='+document.form1.ov07_cidadao.value+'&funcao_js=parent.js_mostracidadao','Pesquisa',false);
     }else{
       document.form1.ov02_sequencial.value = ''; 
     }
  }
}
function js_mostracidadao(chave,erro){
  document.form1.ov02_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.ov07_cidadao.focus(); 
    document.form1.ov07_cidadao.value = ''; 
  }
}
function js_mostracidadao1(chave1,chave2){
  document.form1.ov07_cidadao.value = chave1;
  document.form1.ov02_sequencial.value = chave2;
  db_iframe_cidadao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cidadaotelefone','func_cidadaotelefone.php?funcao_js=parent.js_preenchepesquisa|ov07_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cidadaotelefone.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>