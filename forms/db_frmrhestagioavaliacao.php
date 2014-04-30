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

//MODULO: recursoshumanos
$clrhestagioavaliacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h59_sequencial");
$clrotulo->label("h64_data");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th56_sequencial?>">
       <?=@$Lh56_sequencial?>
    </td>
    <td> 
<?
db_input('h56_sequencial',10,$Ih56_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th56_rhestagiocomissao?>">
       <?
       db_ancora(@$Lh56_rhestagiocomissao,"js_pesquisah56_rhestagiocomissao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h56_rhestagiocomissao',10,$Ih56_rhestagiocomissao,true,'text',$db_opcao," onchange='js_pesquisah56_rhestagiocomissao(false);'")
?>
       <?
db_input('h59_sequencial',10,$Ih59_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th56_rhestagioagenda?>">
       <?
       db_ancora(@$Lh56_rhestagioagenda,"js_pesquisah56_rhestagioagenda(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h56_rhestagioagenda',10,$Ih56_rhestagioagenda,true,'text',$db_opcao," onchange='js_pesquisah56_rhestagioagenda(false);'")
?>
       <?
db_input('h64_data',10,$Ih64_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th56_data?>">
       <?=@$Lh56_data?>
    </td>
    <td> 
<?
db_inputdata('h56_data',@$h56_data_dia,@$h56_data_mes,@$h56_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th56_avaliador?>">
       <?=@$Lh56_avaliador?>
    </td>
    <td> 
<?
db_input('h56_avaliador',10,$Ih56_avaliador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisah56_rhestagiocomissao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhestagiocomissao','func_rhestagiocomissao.php?funcao_js=parent.js_mostrarhestagiocomissao1|h59_sequencial|h59_sequencial','Pesquisa',true);
  }else{
     if(document.form1.h56_rhestagiocomissao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhestagiocomissao','func_rhestagiocomissao.php?pesquisa_chave='+document.form1.h56_rhestagiocomissao.value+'&funcao_js=parent.js_mostrarhestagiocomissao','Pesquisa',false);
     }else{
       document.form1.h59_sequencial.value = ''; 
     }
  }
}
function js_mostrarhestagiocomissao(chave,erro){
  document.form1.h59_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.h56_rhestagiocomissao.focus(); 
    document.form1.h56_rhestagiocomissao.value = ''; 
  }
}
function js_mostrarhestagiocomissao1(chave1,chave2){
  document.form1.h56_rhestagiocomissao.value = chave1;
  document.form1.h59_sequencial.value = chave2;
  db_iframe_rhestagiocomissao.hide();
}
function js_pesquisah56_rhestagioagenda(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhestagioagendadata','func_rhestagioagendadata.php?funcao_js=parent.js_mostrarhestagioagendadata1|h64_sequencial|h64_data','Pesquisa',true);
  }else{
     if(document.form1.h56_rhestagioagenda.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhestagioagendadata','func_rhestagioagendadata.php?pesquisa_chave='+document.form1.h56_rhestagioagenda.value+'&funcao_js=parent.js_mostrarhestagioagendadata','Pesquisa',false);
     }else{
       document.form1.h64_data.value = ''; 
     }
  }
}
function js_mostrarhestagioagendadata(chave,erro){
  document.form1.h64_data.value = chave; 
  if(erro==true){ 
    document.form1.h56_rhestagioagenda.focus(); 
    document.form1.h56_rhestagioagenda.value = ''; 
  }
}
function js_mostrarhestagioagendadata1(chave1,chave2){
  document.form1.h56_rhestagioagenda.value = chave1;
  document.form1.h64_data.value = chave2;
  db_iframe_rhestagioagendadata.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhestagioavaliacao','func_rhestagioavaliacao.php?funcao_js=parent.js_preenchepesquisa|h56_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhestagioavaliacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>