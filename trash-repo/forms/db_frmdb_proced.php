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

//MODULO: atendimento
$cldb_proced->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("at32_descr");
      if($db_opcao==1){
 	   $db_action="ate1_db_proced004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="ate1_db_proced005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="ate1_db_proced006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat30_codigo?>">
       <?=@$Lat30_codigo?>
    </td>
    <td> 
<?
db_input('at30_codigo',10,$Iat30_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat30_descr?>">
       <?=@$Lat30_descr?>
    </td>
    <td> 
<?
db_textarea('at30_descr',5,50,$Iat30_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat30_responsavel?>">
       <?
       db_ancora(@$Lat30_responsavel,"js_pesquisaat30_responsavel(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at30_responsavel',10,$Iat30_responsavel,true,'text',$db_opcao," onchange='js_pesquisaat30_responsavel(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat30_inicio?>">
       <?=@$Lat30_inicio?>
    </td>
    <td> 
<?
db_inputdata('at30_inicio',@$at30_inicio_dia,@$at30_inicio_mes,@$at30_inicio_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat30_fim?>">
       <?=@$Lat30_fim?>
    </td>
    <td> 
<?
db_inputdata('at30_fim',@$at30_fim_dia,@$at30_fim_mes,@$at30_fim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Situação">
       <?
       db_ancora("<b>Situação</b>","js_pesquisaat30_situacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at30_situacao',10,$Iat30_situacao,true,'text',$db_opcao," onchange='js_pesquisaat30_situacao(false);'")
?>
       <?
db_input('at32_descr',40,$Iat32_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaat30_responsavel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_proced','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0');
  }else{
     if(document.form1.at30_responsavel.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_proced','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.at30_responsavel.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false,'0');
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.at30_responsavel.focus(); 
    document.form1.at30_responsavel.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.at30_responsavel.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisaat30_situacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_proced','db_iframe_db_procedsituacao','func_db_procedsituacao.php?funcao_js=parent.js_mostradb_procedsituacao1|at32_codigo|at32_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.at30_situacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_proced','db_iframe_db_procedsituacao','func_db_procedsituacao.php?pesquisa_chave='+document.form1.at30_situacao.value+'&funcao_js=parent.js_mostradb_procedsituacao','Pesquisa',false,'0');
     }else{
       document.form1.at32_descr.value = ''; 
     }
  }
}
function js_mostradb_procedsituacao(chave,erro){
  document.form1.at32_descr.value = chave; 
  if(erro==true){ 
    document.form1.at30_situacao.focus(); 
    document.form1.at30_situacao.value = ''; 
  }
}
function js_mostradb_procedsituacao1(chave1,chave2){
  document.form1.at30_situacao.value = chave1;
  document.form1.at32_descr.value = chave2;
  db_iframe_db_procedsituacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_db_proced','db_iframe_db_proced','func_db_proced.php?funcao_js=parent.js_preenchepesquisa|at30_codigo','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_db_proced.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>