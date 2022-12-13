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

//MODULO: juridico
$clprocessoforomovsituacao->rotulo->label();
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Cadastros - Situação Processo do Foro</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tv74_sequencial?>">
          <?=@$Lv74_sequencial?>
        </td>
        <td> 
          <?
            db_input('v74_sequencial',10,$Iv74_sequencial,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tv74_descricao?>">
          <?=@$Lv74_descricao?>
        </td>
        <td> 
          <?
            db_input('v74_descricao',50,$Iv74_descricao,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tv74_tipomovimento?>">
          <?=@$Lv74_tipomovimento?>
        </td>
        <td> 
          <?
            $x = getValoresPadroesCampo('v74_tipomovimento');
            db_select('v74_tipomovimento',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_processoforomovsituacao','func_processoforomovsituacao.php?funcao_js=parent.js_preenchepesquisa|v74_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_processoforomovsituacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>

<script>

$("v74_sequencial").addClassName("field-size2");
$("v74_descricao").addClassName("field-size9");
<?php if($db_opcao==3||$db_opcao==33){?>
  $("v74_tipomovimento_select_descr").addClassName("field-size9");
<?php }?>
</script>