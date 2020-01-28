<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

$clempprestatip->rotulo->label();
?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Tipo de Evento</legend>
      <table border="0">
        <tr>
          <td nowrap title="<?php echo $Te44_tipo; ?>">
             <?php echo $Le44_tipo; ?>
          </td>
          <td>
            <?php db_input('e44_tipo',8,$Ie44_tipo,true,'text',3); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Te44_descr; ?>">
             <?php echo $Le44_descr; ?>
          </td>
          <td>
            <?php db_input('e44_descr',40,$Ie44_descr,true,'text',$db_opcao,""); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Te44_obriga; ?>">
           <?php echo $Le44_obriga; ?>
          </td>
          <td>
            <?php
              $arr = array("0"=>"Não presta","1"=>"Obriga Valores","2"=>"Obriga contas");
              db_select("e44_obriga",$arr,true,$db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Te44_naturezaevento; ?>">
            <label for="e44_naturezaevento" class="bold" id="lbl_e44_naturezaevento"><?php echo $Le44_naturezaevento; ?></label>
          </td>
          <td>
            <?php
              $aOpcoes = array(
                  1 => 'Não se Aplica',
                  2 => 'Adiantamentos Concedidos',
                  3 => 'Subvenções e Auxílios'
                );

              db_select('e44_naturezaevento', $aOpcoes, true, $db_opcao);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</div>
<script>

  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_empprestatip','func_empprestatip.php?funcao_js=parent.js_preenchepesquisa|e44_tipo','Pesquisa',true);
  }

  function js_preenchepesquisa(chave){
    db_iframe_empprestatip.hide();

    <?php
      if($db_opcao!=1){
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
      }
    ?>
  }

</script>