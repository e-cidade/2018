<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

$cltipoisen->rotulo->label();
?>
<div class="container">

 <form name="form1" method="post" action="">

 <fieldset>

  <legend>Tipo de Isenção</legend>

    <table border="0">
      <tr>
        <td nowrap title="<?=@$Tj45_tipo?>">
          <label for="j45_tipo"><?=@$Lj45_tipo?></label>
        </td>
        <td>
          <?php
            db_input('j45_tipo',4,$Ij45_tipo,true,'text',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj45_descr?>">
           <label for="j45_descr"><?=@$Lj45_descr?></label>
        </td>
        <td>
          <?php
            db_input('j45_descr',40,$Ij45_descr,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj45_tipis?>">
           <label for="j45_tipis"><?=@$Lj45_tipis?></label>
        </td>
        <td>
          <?php
            $aOpcoes = array("0" => "Isento", "1" => "Imune", "2" => "Não Incidente");
            db_select('j45_tipis', $aOpcoes, true, $db_opcao );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj45_taxas?>">
           <label for="j45_taxas"><?=@$Lj45_taxas?></label>
        </td>
        <td>
          <?php
            $aOpcoes = array("f" => "Não", "t" => "Sim");
            db_select('j45_taxas', $aOpcoes, true, $db_opcao);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj45_obscertidao?>">
           <label for="j45_obscertidao"><?=@$Lj45_obscertidao?></label>
        </td>
        <td>
          <?php
            db_textarea('j45_obscertidao',5,60,$Ij45_obscertidao,true,'text',$db_opcao );
          ?>
        </td>
      </tr>
      </table>

    </fieldset>

    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> />
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />

  </form>
</div>
<script type="text/javascript">

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tipoisen','func_tipoisen.php?funcao_js=parent.js_preenchepesquisa|j45_tipo','Pesquisa',true);
}

function js_preenchepesquisa(chave){

  db_iframe_tipoisen.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>