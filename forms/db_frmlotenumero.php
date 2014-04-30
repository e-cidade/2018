<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
$cllotenumero->rotulo->label();
$clprotprocesso->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j34_setor");
$clrotulo->label("j14_nome");
?>
<form name="form1" method="post" action="">
<fieldset style="width: 600px;">
<legend><strong>Cartão Numérico</strong></legend>
<input type="hidden" name="j12_codigo" value="<?=@$j12_codigo?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj12_idbql?>">
       <?
       db_ancora(@$Lj12_idbql,"js_pesquisaj12_idbql(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j12_idbql',7,$Ij12_idbql,true,'text',$db_opcao," onchange='js_pesquisaj12_idbql(false);'")
?>
       <?
db_input('j34_setor',4,$Ij34_setor,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj12_lograd?>">
       <?
       db_ancora(@$Lj12_lograd,"js_pesquisaj12_lograd(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j12_lograd',7,$Ij12_lograd,true,'text',$db_opcao," onchange='js_pesquisaj12_lograd(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
<tr>
    <td nowrap title="<?=@$Tp58_codproc?>">
       <?
       db_ancora(@$Lp58_codproc,"js_pesquisap58_codproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p58_codproc',7,$Ip58_codproc,true,'text',$db_opcao," onchange='js_pesquisap58_codproc(false);'")
?>
       <?
db_input('p58_requer',40,$Ip58_requer,true,'text',3,'')
       ?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Tj12_data?>">
       <?=@$Lj12_data?>
    </td>
    <td> 
<?
db_inputdata('j12_data',@$j12_data_dia,@$j12_data_mes,@$j12_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj12_numero?>">
       <?=@$Lj12_numero?>
    </td>
    <td> 
<?
db_input('j12_numero',10,$Ij12_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
</fieldset>
<p align="center">
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</p>
</form>
<script>
function js_pesquisaj12_idbql(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lote','func_lote.php?funcao_js=parent.js_mostralote1|j34_idbql|j34_setor','Pesquisa',true);
  }else{
     if(document.form1.j12_idbql.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lote','func_lote.php?pesquisa_chave='+document.form1.j12_idbql.value+'&funcao_js=parent.js_mostralote','Pesquisa',false);
     }else{
       document.form1.j34_setor.value = ''; 
     }
  }
}
function js_mostralote(chave,erro){
  document.form1.j34_setor.value = chave; 
  if(erro==true){ 
    document.form1.j12_idbql.focus(); 
    document.form1.j12_idbql.value = ''; 
  }
}
function js_mostralote1(chave1,chave2){
  document.form1.j12_idbql.value = chave1;
  document.form1.j34_setor.value = chave2;
  db_iframe_lote.hide();
}

function js_pesquisap58_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_processo','func_protprocesso.php?funcao_js=parent.js_mostraprocesso1|p58_codproc|p58_requer','Pesquisa',true);
  }else{
     if(document.form1.p58_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_processo','func_protprocesso.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraprocesso','Pesquisa',false);
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprocesso(chave,erro){
  document.form1.p58_requer.value = chave; 
  if(erro==true){ 
    document.form1.p58_codproc.focus(); 
    document.form1.p58_codproc.value = ''; 
  }
}
function js_mostraprocesso1(chave1,chave2){
  document.form1.p58_codproc.value = chave1;
  document.form1.p58_requer.value = chave2;
  db_iframe_processo.hide();
}

function js_pesquisaj12_lograd(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true);
  }else{
     if(document.form1.j12_lograd.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.j12_lograd.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
     }else{
       document.form1.j14_nome.value = ''; 
     }
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.j12_lograd.focus(); 
    document.form1.j12_lograd.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j12_lograd.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lotenumero','func_lotenumero.php?funcao_js=parent.js_preenchepesquisa|j12_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lotenumero.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>