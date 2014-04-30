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
$cldb_projetos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at61_descr");
$clrotulo->label("nome");
      if($db_opcao==1){
 	   $db_action="ate1_db_projetos004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="ate1_db_projetos005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="ate1_db_projetos006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat60_codigo?>">
       <?=@$Lat60_codigo?>
    </td>
    <td> 
<?
db_input('at60_codigo',10,$Iat60_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat60_descr?>">
       <?=@$Lat60_descr?>
    </td>
    <td> 
<?
db_textarea('at60_descr',5,50,$Iat60_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat60_responsavel?>">
       <?
       db_ancora("<b>Responsável:</b>","js_pesquisaat60_responsavel(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at60_responsavel',10,$Iat60_responsavel,true,'text',$db_opcao," onchange='js_pesquisaat60_responsavel(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat60_inicio?>">
       <?=@$Lat60_inicio?>
    </td>
    <td> 
<?
db_inputdata('at60_inicio',@$at60_inicio_dia,@$at60_inicio_mes,@$at60_inicio_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat60_fim?>">
       <?=@$Lat60_fim?>
    </td>
    <td> 
<?
db_inputdata('at60_fim',@$at60_fim_dia,@$at60_fim_mes,@$at60_fim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Situação">
       <?
       db_ancora("<b>Situação</b>","js_pesquisaat60_situacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at60_situacao',10,$Iat60_situacao,true,'text',$db_opcao," onchange='js_pesquisaat60_situacao(false);'")
?>
       <?
db_input('at61_descr',40,$Iat61_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaat60_responsavel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_projetos','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0');
  }else{
     if(document.form1.at60_responsavel.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_projetos','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.at60_responsavel.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false,'0');
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.at60_responsavel.focus(); 
    document.form1.at60_responsavel.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.at60_responsavel.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisaat60_situacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_projetos','db_iframe_db_projetosituacao','func_db_projetosituacao.php?funcao_js=parent.js_mostradb_projetosituacao1|at61_codigo|at61_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.at60_situacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_projetos','db_iframe_db_projetosituacao','func_db_projetosituacao.php?pesquisa_chave='+document.form1.at60_situacao.value+'&funcao_js=parent.js_mostradb_projetosituacao','Pesquisa',false,'0');
     }else{
       document.form1.at61_descr.value = ''; 
     }
  }
}
function js_mostradb_projetosituacao(chave,erro){
  document.form1.at61_descr.value = chave; 
  if(erro==true){ 
    document.form1.at60_situacao.focus(); 
    document.form1.at60_situacao.value = ''; 
  }
}
function js_mostradb_projetosituacao1(chave1,chave2){
  document.form1.at60_situacao.value = chave1;
  document.form1.at61_descr.value = chave2;
  db_iframe_db_projetosituacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_db_projetos','db_iframe_db_projetos','func_db_projetos.php?funcao_js=parent.js_preenchepesquisa|at60_codigo','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_db_projetos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>