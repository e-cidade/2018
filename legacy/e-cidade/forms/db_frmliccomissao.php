<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: licitação
$clliccomissao->rotulo->label();

if ($db_opcao == 1) {
  $db_action = "lic1_liccomissao004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $db_action = "lic1_liccomissao005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action = "lic1_liccomissao006.php";
}

$Tl30_codigo = isset($Tl30_codigo) ? $Tl30_codigo : null;
$Ll30_codigo = isset($Ll30_codigo) ? $Ll30_codigo : null;

$Tl30_data = isset($Tl30_data) ? $Tl30_data : null;
$Ll30_data = isset($Ll30_data) ? $Ll30_data : null;

$Tl30_portaria = isset($Tl30_portaria) ? $Tl30_portaria : null;
$Ll30_portaria = isset($Ll30_portaria) ? $Ll30_portaria : null;

$Tl30_datavalid = isset($Tl30_datavalid) ? $Tl30_datavalid : null;
$Ll30_datavalid = isset($Ll30_datavalid) ? $Ll30_datavalid : null;

$Tl30_tipo = isset($Tl30_tipo) ? $Tl30_tipo : null;
$Ll30_tipo = isset($Ll30_tipo) ? $Ll30_tipo : null;

$Tl30_arquivo = isset($Tl30_arquivo) ? $Tl30_arquivo : null;
$Ll30_arquivo = isset($Ll30_arquivo) ? $Ll30_arquivo : null;
?>
<style style="text/css">
#l30_portaria, #l30_nomearquivo, #l30_tipo {
  width: 126px;
}
</style>
<form name="form1" method="post" action="<?=$db_action?>" enctype="multipart/form-data">
<fieldset>
  <legend>Comissão de Licitação</legend>
  <table border="0">
    <tr>
      <td title="<?= $Tl30_codigo ?>">
         <label for="l30_codigo"><?= $Ll30_codigo ?></label>
      </td>
      <td>
        <?php db_input('l30_codigo',10,$Il30_codigo,true,'text',3,"") ?>
      </td>
    </tr>
    <tr>
      <td title="<?= $Tl30_data ?>">
         <label for="l30_data"><?= $Ll30_data ?></label>
      </td>
      <td>
        <?php
          $l30_data_dia = isset($l30_data_dia) ? $l30_data_dia : null;
          $l30_data_mes = isset($l30_data_mes) ? $l30_data_mes : null;
          $l30_data_ano = isset($l30_data_ano) ? $l30_data_ano : null;
          db_inputdata('l30_data', $l30_data_dia, $l30_data_mes, $l30_data_ano, true, 'text', $db_opcao, "")
        ?>
      </td>
    </tr>
    <tr>
      <td title="<?= $Tl30_portaria ?>">
         <label for="l30_portaria"><?= $Ll30_portaria ?></label>
      </td>
      <td>
        <?php db_input('l30_portaria', 20, $Il30_portaria, true, 'text', $db_opcao) ?>
      </td>
    </tr>
    <tr>
      <td title="<?= $Tl30_datavalid ?>">
         <label for="l30_datavalid"><?= $Ll30_datavalid ?></label>
      </td>
      <td>
        <?php
        $l30_datavalid_dia = isset($l30_datavalid_dia) ? $l30_datavalid_dia : null;
        $l30_datavalid_mes = isset($l30_datavalid_mes) ? $l30_datavalid_mes : null;
        $l30_datavalid_ano = isset($l30_datavalid_ano) ? $l30_datavalid_ano : null;
        db_inputdata('l30_datavalid', $l30_datavalid_dia, $l30_datavalid_mes, $l30_datavalid_ano, true, 'text', $db_opcao, "")
        ?>
      </td>
    </tr>
    <tr>
      <td title="<?= $Tl30_tipo ?>">
         <label for="l30_tipo"><?= $Ll30_tipo ?></label>
      </td>
      <td>
        <?php
        $aOpcoes = array(
          '1' => 'Permanente',
          '2' => 'Especial',
          '3' => 'Pregão',
          '4' => 'Servidor Designado',
        );
        db_select('l30_tipo', $aOpcoes, true, $db_opcao, "");
        ?>
      </td>
    </tr>
    <?php if ($db_opcao != 3) : ?>
    <tr>
      <td title="<?= $Tl30_arquivo ?>">
        <label for="l30_arquivo"><?= $Ll30_arquivo ?></label>
      </td>
      <td>
        <input type="file" name="l30_arquivo" id="file">
      </td>
    </tr>
    <?php endif ?>
    <?php if (!empty($l30_nomearquivo)) : ?>
    <tr>
      <td><label class="bold">Arquivo Atual:</label></td>
      <td>
        <?php db_input('l30_nomearquivo', 20, 0, true, 'text', 33) ?>&nbsp;<input type="button" id="btnDownload" value="Download">
      </td>
    </tr>
    <?php endif ?>
    </table>
  </fieldset>
  <?php
    $sName     = ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"));
    $sValue    = ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"));
    $sDisabled = ($db_botao == false ? "disabled" : "");
  ?>
  <input name="<?= $sName ?>" type="submit" id="db_opcao" value="<?= $sValue ?>" <?= $sDisabled ?>>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>

<script>

var oBtnDownload = document.getElementById('btnDownload');

if (oBtnDownload) {

  oBtnDownload.addEventListener('click', function(){

    var iCodigoComissao = document.getElementById('l30_codigo').value;
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_download','lic1_liccomissaodownload.php?iCodigoComissao=' + iCodigoComissao, 'Download de arquivos', false);
  });
}

function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_liccomissao','db_iframe_liccomissao','func_liccomissao.php?funcao_js=parent.js_preenchepesquisa|l30_codigo','Pesquisa',true,0);
}

function js_preenchepesquisa(chave){
  db_iframe_liccomissao.hide();
  <?php
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
