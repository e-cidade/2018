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

//MODULO: escola

?>
<form name="form1" method="post" action="">

  <fieldset >
  	<legend><b>Tipos de Ausência</b></legend>
    <table border="0">
      <tr style="display: none;">
        <td nowrap title="<?=$Ted320_sequencial?>">
          <label for="ed320_sequencial"><?=$Led320_sequencial?></label>
        </td>
        <td>
          <?php
          db_input('ed320_sequencial',10,$Ied320_sequencial,true,'hidden',$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted320_descricao?>">
          <label for="ed320_descricao"><?=$Led320_descricao?></label>
        </td>
        <td>
          <?php
          db_input('ed320_descricao',50,$Ied320_descricao,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted320_tipo?>">
          <label for="ed320_tipo"><?=$Led320_tipo?></label>
        </td>
        <td>
          <?php

            $a = array(1 => 'NENHUM',
                       2 => 'LICENÇA',
                       3 => 'FALTA ABONADA',
                       4 => 'FALTA NÃO JUSTIFICADA');
            db_select('ed320_tipo', $a, true, $db_opcao);
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
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipoausencia','func_tipoausencia.php?funcao_js=parent.js_preenchepesquisa|ed320_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tipoausencia.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>