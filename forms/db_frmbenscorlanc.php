<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: patrimonio
$clbenscorlanc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac08_descricao");
?>
<br>
<form name="form1" method="post" action="">
<fieldset style="width: 500px;">
<legend><b>Lançamento de Correções</b></legend>
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tt62_codcor?>">
       <?=@$Lt62_codcor?>
    </td>
    <td> 
      <?
        db_input('t62_codcor',10,$It62_codcor,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt62_data?>">
       <?=@$Lt62_data?>
    </td>
    <td> 
      <?
      db_inputdata('t62_data',@$t62_data_dia,@$t62_data_mes,@$t62_data_ano,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt62_codcom?>">
       <?
       db_ancora(@$Lt62_codcom,"js_pesquisat62_codcom(true);",$db_opcao);
       ?>
    </td>
    <td> 
      <?
        db_input('t62_codcom',10,$It62_codcom,true,'text',$db_opcao," onchange='js_pesquisat62_codcom(false);'");
        db_input('ac08_descricao',50,$Iac08_descricao,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset>
        <legend><b>Observações</b></legend> 
        <?
        db_textarea('t62_obs',5,80,$It62_obs,true,'text',$db_opcao,"")
        ?>
      </fieldset>
    </td>
  </tr>
  </table>
  </center>
</fieldset>
  <br>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisat62_codcom(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_acordocomissao','func_acordocomissao.php?funcao_js=parent.js_mostraacordocomissao1|ac08_sequencial|ac08_descricao','Pesquisa',true);
  }else{
     if(document.form1.t62_codcom.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_acordocomissao','func_acordocomissao.php?pesquisa_chave='+document.form1.t62_codcom.value+'&funcao_js=parent.js_mostraacordocomissao','Pesquisa',false);
     }else{
       document.form1.ac08_descricao.value = ''; 
     }
  }
}
function js_mostraacordocomissao(chave,erro){
  document.form1.ac08_descricao.value = chave; 
  if(erro==true){ 
    document.form1.t62_codcom.focus(); 
    document.form1.t62_codcom.value = ''; 
  }
}
function js_mostraacordocomissao1(chave1,chave2){
  document.form1.t62_codcom.value = chave1;
  document.form1.ac08_descricao.value = chave2;
  db_iframe_acordocomissao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_benscorlanc','func_benscorlanc.php?funcao_js=parent.js_preenchepesquisa|t62_codcor','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_benscorlanc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>