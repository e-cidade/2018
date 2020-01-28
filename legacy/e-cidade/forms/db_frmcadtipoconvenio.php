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

//MODULO: Arrecadação
$clcadtipoconvenio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ar15_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tar12_sequencial?>">
       <?=@$Lar12_sequencial?>
    </td>
    <td> 
<?
db_input('ar12_sequencial',10,$Iar12_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tar12_cadconveniomodalidade?>">
       <?
       db_ancora(@$Lar12_cadconveniomodalidade,"js_pesquisaar12_cadconveniomodalidade(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ar12_cadconveniomodalidade',10,$Iar12_cadconveniomodalidade,true,'text',$db_opcao," onchange='js_pesquisaar12_cadconveniomodalidade(false);'")
?>
       <?
db_input('ar15_nome',50,$Iar15_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tar12_nome?>">
       <?=@$Lar12_nome?>
    </td>
    <td> 
<?
db_input('ar12_nome',50,$Iar12_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tar12_sigla?>">
       <?=@$Lar12_sigla?>
    </td>
    <td> 
<?
db_input('ar12_sigla',3,$Iar12_sigla,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaar12_cadconveniomodalidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadconveniomodalidade','func_cadconveniomodalidade.php?funcao_js=parent.js_mostracadconveniomodalidade1|ar15_sequencial|ar15_nome','Pesquisa',true);
  }else{
     if(document.form1.ar12_cadconveniomodalidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadconveniomodalidade','func_cadconveniomodalidade.php?pesquisa_chave='+document.form1.ar12_cadconveniomodalidade.value+'&funcao_js=parent.js_mostracadconveniomodalidade','Pesquisa',false);
     }else{
       document.form1.ar15_nome.value = ''; 
     }
  }
}
function js_mostracadconveniomodalidade(chave,erro){
  document.form1.ar15_nome.value = chave; 
  if(erro==true){ 
    document.form1.ar12_cadconveniomodalidade.focus(); 
    document.form1.ar12_cadconveniomodalidade.value = ''; 
  }
}
function js_mostracadconveniomodalidade1(chave1,chave2){
  document.form1.ar12_cadconveniomodalidade.value = chave1;
  document.form1.ar15_nome.value = chave2;
  db_iframe_cadconveniomodalidade.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadtipoconvenio','func_cadtipoconvenio.php?funcao_js=parent.js_preenchepesquisa|ar12_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadtipoconvenio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>