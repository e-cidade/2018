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
$clbenstipodepreciacao->rotulo->label();
?>
<form name="form1" method="post" action="">
<fieldset >
  <legend><strong>Cadastro do Tipo de Depreciação</strong></legend>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Tt46_sequencial?>">
         <?=@$Lt46_sequencial?>
      </td>
      <td> 
        <?
          db_input('t46_sequencial',10,$It46_sequencial,true,'text',3,"")
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tt46_descricao?>">
         <?=@$Lt46_descricao?>
      </td>
      <td> 
        <?
          db_input('t46_descricao',50,$It46_descricao,true,'text',$db_opcao,"")
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tt46_quantidadeano?>">
         <?=@$Lt46_quantidadeano?>
      </td>
      <td> 
        <?
          db_input('t46_quantidadeano',10,$It46_quantidadeano,true,'text',$db_opcao,"")
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tt46_percentual?>">
         <?=@$Lt46_percentual?>
      </td>
      <td> 
        <?
          db_input('t46_percentual',10,$It46_percentual,true,'text',$db_opcao,"")
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tt46_observacao?>" colspan="2">
        <fieldset>
          <legend><?=@$Lt46_observacao?></legend>
          <?
            db_textarea('t46_observacao',5,68,$It46_observacao,true,'text',$db_opcao,"")
          ?>
        </fieldset>
      </td>
    </tr>
  </table>
</fieldset>
<div style="margin-top: 10px;">
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" 
         id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
         <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</div>
</form>

<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_benstipodepreciacao','func_benstipodepreciacao.php?funcao_js=parent.js_preenchepesquisa|t46_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_benstipodepreciacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>