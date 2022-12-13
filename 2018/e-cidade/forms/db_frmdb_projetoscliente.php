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
$cldb_projetoscliente->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at01_nomecli");
$clrotulo->label("descrproced");
      if($db_opcao==1){
 	   $db_action="ate1_db_projetoscliente004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="ate1_db_projetoscliente005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="ate1_db_projetoscliente006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat60_codproj?>">
       <?=@$Lat60_codproj?>
    </td>
    <td> 
<?
db_input('at60_codproj',10,$Iat60_codproj,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat60_codcli?>">
       <?
       db_ancora(@$Lat60_codcli,"js_pesquisaat60_codcli(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at60_codcli',5,$Iat60_codcli,true,'text',$db_opcao," onchange='js_pesquisaat60_codcli(false);'")
?>
       <?
db_input('at01_nomecli',40,$Iat01_nomecli,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat60_codproced?>">
       <?
       db_ancora(@$Lat60_codproced,"js_pesquisaat60_codproced(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at60_codproced',10,$Iat60_codproced,true,'text',$db_opcao," onchange='js_pesquisaat60_codproced(false);'")
?>
       <?
db_input('descrproced',60,$Idescrproced,true,'text',3,'')
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
    <td nowrap title="<?=@$Tat60_descricao?>">
       <?=@$Lat60_descricao?>
    </td>
    <td> 
<?
db_textarea('at60_descricao',5,60,$Iat60_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>



  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaat60_codcli(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_projetoscliente','db_iframe_clientes','func_clientes.php?funcao_js=parent.js_mostraclientes1|at01_codcli|at01_nomecli','Pesquisa',true,'0','1');
  }else{
     if(document.form1.at60_codcli.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_projetoscliente','db_iframe_clientes','func_clientes.php?pesquisa_chave='+document.form1.at60_codcli.value+'&funcao_js=parent.js_mostraclientes','Pesquisa',false,'0','1');
     }else{
       document.form1.at01_nomecli.value = ''; 
     }
  }
}
function js_mostraclientes(chave,erro){
  document.form1.at01_nomecli.value = chave; 
  if(erro==true){ 
    document.form1.at60_codcli.focus(); 
    document.form1.at60_codcli.value = ''; 
  }
}
function js_mostraclientes1(chave1,chave2){
  document.form1.at60_codcli.value = chave1;
  document.form1.at01_nomecli.value = chave2;
  db_iframe_clientes.hide();
}
function js_pesquisaat60_codproced(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_projetoscliente','db_iframe_db_syscadproced','func_db_syscadproced.php?funcao_js=parent.js_mostradb_syscadproced1|codproced|descrproced','Pesquisa',true,'0','1');
  }else{
     if(document.form1.at60_codproced.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_projetoscliente','db_iframe_db_syscadproced','func_db_syscadproced.php?pesquisa_chave='+document.form1.at60_codproced.value+'&funcao_js=parent.js_mostradb_syscadproced','Pesquisa',false,'0','1');
     }else{
       document.form1.descrproced.value = ''; 
     }
  }
}
function js_mostradb_syscadproced(chave,erro){
  document.form1.descrproced.value = chave; 
  if(erro==true){ 
    document.form1.at60_codproced.focus(); 
    document.form1.at60_codproced.value = ''; 
  }
}
function js_mostradb_syscadproced1(chave1,chave2){
  document.form1.at60_codproced.value = chave1;
  document.form1.descrproced.value = chave2;
  db_iframe_db_syscadproced.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_db_projetoscliente','db_iframe_db_projetoscliente','func_db_projetoscliente.php?funcao_js=parent.js_preenchepesquisa|at60_codproj','Pesquisa',true,'0','1');
}
function js_preenchepesquisa(chave){
  db_iframe_db_projetoscliente.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>