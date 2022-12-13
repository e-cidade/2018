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

//MODULO: empenho
$clretencaopagordem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e50_numemp");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te20_sequencial?>">
       <?=@$Le20_sequencial?>
    </td>
    <td> 
<?
db_input('e20_sequencial',10,$Ie20_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te20_pagordem?>">
       <?
       db_ancora(@$Le20_pagordem,"js_pesquisae20_pagordem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e20_pagordem',10,$Ie20_pagordem,true,'text',$db_opcao," onchange='js_pesquisae20_pagordem(false);'")
?>
       <?
db_input('e50_numemp',10,$Ie50_numemp,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te20_data?>">
       <?=@$Le20_data?>
    </td>
    <td> 
<?
db_inputdata('e20_data',@$e20_data_dia,@$e20_data_mes,@$e20_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisae20_pagordem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord|e50_numemp','Pesquisa',true);
  }else{
     if(document.form1.e20_pagordem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+document.form1.e20_pagordem.value+'&funcao_js=parent.js_mostrapagordem','Pesquisa',false);
     }else{
       document.form1.e50_numemp.value = ''; 
     }
  }
}
function js_mostrapagordem(chave,erro){
  document.form1.e50_numemp.value = chave; 
  if(erro==true){ 
    document.form1.e20_pagordem.focus(); 
    document.form1.e20_pagordem.value = ''; 
  }
}
function js_mostrapagordem1(chave1,chave2){
  document.form1.e20_pagordem.value = chave1;
  document.form1.e50_numemp.value = chave2;
  db_iframe_pagordem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_retencaopagordem','func_retencaopagordem.php?funcao_js=parent.js_preenchepesquisa|e20_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_retencaopagordem.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>