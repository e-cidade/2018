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

//MODULO: patrimonio
$clcfpatriinstituicao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Parâmetro de depreciação por instituição</legend>
    <table class="form-container">
      <tr style="display: none;">
        <td nowrap title="<?=@$Tt59_sequencial?>">
          <?=@$Lt59_sequencial?>
        </td>
        <td> 
          <?
            db_input('t59_sequencial',10,$It59_sequencial,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr style="display: none;">
        <td nowrap title="<?=@$Tt59_instituicao?>">
          <?
            db_ancora(@$Lt59_instituicao,"js_pesquisat59_instituicao(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t59_instituicao',10,$It59_instituicao,true,'text',$db_opcao," onchange='js_pesquisat59_instituicao(false);'")
          ?>
          <?
            db_input('nomeinst',80,$Inomeinst,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt59_dataimplanatacaodepreciacao?>">
          Data da implantação:
        </td>
        <td> 
          <?
            db_inputdata('t59_dataimplanatacaodepreciacao', 
                         @$t59_dataimplanatacaodepreciacao_dia,
                         @$t59_dataimplanatacaodepreciacao_mes,
                         @$t59_dataimplanatacaodepreciacao_ano, 
                         true,
                         'text',
                         $iOpcaoDataDepreciacao,
                         "");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <!-- <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" > -->
  <?php
    if ($db_opcao == 22) {
      db_redireciona("pat1_cfpatriinstituicao002.php?chavepesquisa=".$t59_sequencial);
    }
  ?>
</form>
<script>
function js_pesquisat59_instituicao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.t59_instituicao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.t59_instituicao.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.t59_instituicao.focus(); 
    document.form1.t59_instituicao.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.t59_instituicao.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cfpatriinstituicao','func_cfpatriinstituicao.php?funcao_js=parent.js_preenchepesquisa|t59_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cfpatriinstituicao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("t59_dataimplanatacaodepreciacao").addClassName("field-size2");

</script>