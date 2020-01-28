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

//MODULO: projetos
$clobrassituacao->rotulo->label();
$clobrassituacao->rotulo->tlabel();


/**
 * String usada para validação no arquivo func_obrassituacao.php 
 * para não listar as situações já vinculadas na tabela obrassituacaolog
 */
$sWhereExclusao   = null;

if( $db_opcao == 3 OR $db_opcao == 33 ) {
  $sWhereExclusao = '&exclusaoSituacao=true';
}
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend><?php echo $Lobrassituacao; ?></legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tob28_sequencial?>">
          <?=@$Lob28_sequencial?>
        </td>
        <td> 
          <?
            db_input('ob28_sequencial',10,$Iob28_sequencial,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tob28_descricao?>">
          <?=@$Lob28_descricao?>
        </td>
        <td> 
          <?
            db_input('ob28_descricao',40,$Iob28_descricao,true,'text',$db_opcao,"")
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
    js_OpenJanelaIframe('top.corpo','db_iframe_obrassituacao','func_obrassituacao.php?funcao_js=parent.js_preenchepesquisa|ob28_sequencial<?=$sWhereExclusao ?>','Pesquisa',true);
  }
function js_preenchepesquisa(chave){
  db_iframe_obrassituacao.hide();
<?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
?>
}
</script>
<script>

$("ob28_sequencial").addClassName("field-size2");
$("ob28_descricao").addClassName("field-size9");

</script>