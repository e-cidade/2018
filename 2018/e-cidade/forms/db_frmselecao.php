<?php
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

/**
 * Gera variaveis globais dos campos 
 */
$clselecao->rotulo->label();

/**
 * Gera label da tabela selecao 
 */
$clselecao->rotulo->tlabel();

/**
 * Pesquisa grupos de selecao 
 */
$oDaoGrupoSelecao = db_utils::getDao('gruposelecao');
$sSqlGrupoSelecao = $oDaoGrupoSelecao->sql_query_file(null, '*', 'rh122_sequencial');
$rsGrupoSelecao   = db_query($sSqlGrupoSelecao);
$aGrupoSelecao    = array();

if ( pg_num_rows($rsGrupoSelecao) > 0 ) {

  foreach (db_utils::getCollectionByRecord($rsGrupoSelecao) as $oGrupoSelecao) {
    $aGrupoSelecao[ $oGrupoSelecao->rh122_sequencial ] = $oGrupoSelecao->rh122_descricao;
  }
}
?>
<script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
<center>

  <form name="form1" method="post" action="">

    <?php db_input('r44_selec',6,$Ir44_selec,true,'hidden', 3); ?>

    <fieldset class="container" style="width:400px;">

    <legend><?php echo $Lselecao; ?></legend>

      <table class="form-container">

        <tr>
          <td nowrap title="<?=@$Tr44_descr?>">
             <?=@$Lr44_descr?>
          </td>
          <td> 
            <?php db_input('r44_descr',52,$Ir44_descr,true,'text',$db_opcao,""); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tr44_where; ?>" colspan="2">
            <fieldset>
              <legend><?php echo $Lr44_where?></legend>
              <?php db_textarea('r44_where',4,50,$Ir44_where,true,'text',$db_opcao,""); ?>
             </fieldset>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tr44_gruposelecao; ?>">
             <strong>Grupo:</strong>
          </td>
          <td> 
            <?php db_select('r44_gruposelecao', $aGrupoSelecao, true, $db_opcao, ""); ?>
          </td>
        </tr>

      </table>

    </fieldset>

    <br />
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

    <?php if ( $db_opcao != 1 ) : ?>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?php endif; ?>

  </form>

</center>

<script type="text/javascript">

document.querySelector('#db_opcao').onClick = js_validarFormulario;

function js_validarFormulario() {

  return false;
}

function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_preenchepesquisa|r44_selec&sGrupoSelecao=2,1','Pesquisa',true);
}

function js_preenchepesquisa(chave) {

  db_iframe_selecao.hide();
  <?php
  if ( $db_opcao != 1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>