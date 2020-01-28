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

//MODULO: cadastro
$cllotedist->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");
$clrotulo->label("j34_setor");
$clrotulo->label("j64_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj54_idbql?>">
       <?
       db_ancora(@$Lj54_idbql,"js_pesquisaj54_idbql(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j54_idbql',4,$Ij54_idbql,true,'text',$db_opcao," onchange='js_pesquisaj54_idbql(false);'")
?>
       <?
db_input('j34_setor',4,$Ij34_setor,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj54_codigo?>">
       <?
       db_ancora(@$Lj54_codigo,"js_pesquisaj54_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j54_codigo',10,$Ij54_codigo,true,'text',$db_opcao," onchange='js_pesquisaj54_codigo(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj54_orientacao?>">
       <?
       db_ancora(@$Lj54_orientacao,"js_pesquisaj54_orientacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j54_orientacao',10,$Ij54_orientacao,true,'text',$db_opcao," onchange='js_pesquisaj54_orientacao(false);'")
?>
       <?
db_input('j64_descricao',20,$Ij64_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj54_distan?>">
       <?=@$Lj54_distan?>
    </td>
    <td> 
<?
db_input('j54_distan',15,$Ij54_distan,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj54_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true);
  }else{
     if(document.form1.j54_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.j54_codigo.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
     }else{
       document.form1.j14_nome.value = ''; 
     }
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.j54_codigo.focus(); 
    document.form1.j54_codigo.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j54_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}
function js_pesquisaj54_idbql(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lote','func_lote.php?funcao_js=parent.js_mostralote1|j34_idbql|j34_setor','Pesquisa',true);
  }else{
     if(document.form1.j54_idbql.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lote','func_lote.php?pesquisa_chave='+document.form1.j54_idbql.value+'&funcao_js=parent.js_mostralote','Pesquisa',false);
     }else{
       document.form1.j34_setor.value = ''; 
     }
  }
}
function js_mostralote(chave,erro){
  document.form1.j34_setor.value = chave; 
  if(erro==true){ 
    document.form1.j54_idbql.focus(); 
    document.form1.j54_idbql.value = ''; 
  }
}
function js_mostralote1(chave1,chave2){
  document.form1.j54_idbql.value = chave1;
  document.form1.j34_setor.value = chave2;
  db_iframe_lote.hide();
}
function js_pesquisaj54_orientacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orientacao','func_orientacao.php?funcao_js=parent.js_mostraorientacao1|j64_sequencial|j64_descricao','Pesquisa',true);
  }else{
     if(document.form1.j54_orientacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orientacao','func_orientacao.php?pesquisa_chave='+document.form1.j54_orientacao.value+'&funcao_js=parent.js_mostraorientacao','Pesquisa',false);
     }else{
       document.form1.j64_descricao.value = ''; 
     }
  }
}
function js_mostraorientacao(chave,erro){
  document.form1.j64_descricao.value = chave; 
  if(erro==true){ 
    document.form1.j54_orientacao.focus(); 
    document.form1.j54_orientacao.value = ''; 
  }
}
function js_mostraorientacao1(chave1,chave2){
  document.form1.j54_orientacao.value = chave1;
  document.form1.j64_descricao.value = chave2;
  db_iframe_orientacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lotedist','func_lotedist.php?funcao_js=parent.js_preenchepesquisa|j54_idbql','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lotedist.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>