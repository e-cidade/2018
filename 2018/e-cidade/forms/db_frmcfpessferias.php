<?php

/**
 *          E-cidade Software Publico para Gestao Municipal
 *        Copyright (C) 2014 DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
 * 
 * Este programa e software livre; voce pode redistribui-lo e/ou
 * modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 * publicada pela Free Software Foundation; tanto a versao 2 da
 * Licenca como (a seu criterio) qualquer versao mais nova.
 * 
 * Este programa e distribuido na expectativa de ser util, mas SEM
 * QUALQUER GARANTIA; sem mesmo a garantia implicita de
 * COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 * PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 * detalhes.
 * 
 * Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 * junto com este programa; se nao, escreva para a Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307, USA.
 *  
 * Copia da licenca no diretorio licenca/licenca_en.txt
 *                               licenca/licenca_pt.txt
 *
 * @author  $Author: $
 * @version $Revision: $
 */

$clrotulo = new rotulocampo;
$clrotulo->label('rh27_descr');
$clcfpess->rotulo->label();

$aOption = array(
  't' => 'Sim',
  'f' => 'Não'
);

$r11_anousu = DBPessoal::getAnoFolha();
$r11_mesusu = DBPessoal::getMesFolha();
?>

<form method="post" name="form1" class="container" id="frmferias">
  <?php db_input('r11_anousu', 4, $Ir11_anousu, true, 'hidden', $db_opcao, ''); ?>
  <?php db_input('r11_mesusu', 2, $Ir11_mesusu, true, 'hidden', $db_opcao, ''); ?>
  <fieldset>
    <legend>Parâmetros de Férias</legend>
    <table class="form-container">
      <tr>
        <td width="185px">
          <label for="r11_recalc">
            <?= $Lr11_recalc; ?>
          </label>
        </td>
        <td>
          <?php db_select('r11_recalc', $aOption, true, $db_opcao, ''); ?>
        </td>
      </tr>
      <tr>
        <td>
          <label for="r11_13ferias">
            <?= $Lr11_13ferias; ?>
          </label>
        </td>
        <td>
          <?php db_select('r11_13ferias', $aOption, true, $db_opcao, ''); ?>
        </td>
      </tr>
	    <tr>
        <td>
          <label for="r11_fersal">
            <?= $Lr11_fersal; ?>
          </label>
        </td>
        <td>
          <?php db_select('r11_fersal', array('S' => 'Salário', 'F' => 'Férias'), true, $db_opcao, ''); ?>
        </td>
      </tr>
      <tr>
        <td>
          <label for="r11_pagarferias">
            <?= $Lr11_pagarferias; ?>
          </label>
        </td>
        <td>
          <?php db_select('r11_pagarferias', array(' ' => 'Nenhum', 'S' => 'Salário', 'C' => 'Complementar'), true, $db_opcao, ''); ?>
        </td>
      </tr>
      <tr>
        <td>
          <label for="r11_pagaab">
            <?= $Lr11_pagaab; ?>
          </label>
        </td>
        <td>
          <?php db_select('r11_pagaab', $aOption, true, $db_opcao, ''); ?>
        </td>
      </tr>
      <tr style="height: 10px;"></tr>
      <tr>
        <td colspan="2">
          <fieldset>
            <legend>Proporcionalização em Caso de Faltas</legend>
            <table class="form-container">
              <tr>
                <td width="174px">
                  <label for="r11_propae">
                    <?= $Lr11_propae; ?>
                  </label>
                </td>
                <td>
                  <?php db_select('r11_propae', $aOption, true, $db_opcao, ''); ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="r11_propac">
                    <?= $Lr11_propac; ?>
                  </label>
                </td>
                <td>
                  <?php db_select('r11_propac', $aOption, true, $db_opcao, ''); ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset>
            <legend>Comparativo de Férias</legend>
            <table class="form-container">
              <tr>
                <td width="174px">
                  <label for="r11_compararferias">
                    <?= $Lr11_compararferias; ?>
                  </label>
                </td>
                <td>
                  <?php asort($aOption); ?>
                  <?php db_select('r11_compararferias', $aOption, true, $db_opcao, ''); ?>
                </td>
              </tr>

              <style>
                select, #r11_baseferias, #r11_basesalario {
                  width: 120px !important;
                }
              </style>

              <tr id="baseferias" style="display: <?= ($r11_compararferias == 'f') ? 'none': ''; ?>">
                <td>
                  <label for="r11_baseferias">
                    <?php db_ancora($Lr11_baseferias, '', 1, '', 'r11_baseferias_ancora'); ?>
                  </label>
                </td>
                <td>
                  <?php db_input('r11_baseferias', 4, 0, true, 'text', $db_opcao, 'lang="r08_codigo"'); ?>
                  <?php db_input('baseferias_descricao', 40, $Ir11_baseferias, true, 'text', 3, 'lang="r08_descr"'); ?>
                </td>
              </tr>
              <tr id="basesalario" style="display: <?= ($r11_compararferias == 'f') ? 'none': ''; ?>">
                <td>
                  <label for="r11_basesalario">
                    <?php db_ancora($Lr11_basesalario, '', 1, '', 'r11_basesalario_ancora'); ?>
                  </label>
                </td>
                <td>
                  <?php db_input('r11_basesalario', 4, 0, true, 'text', $db_opcao, 'lang="r08_codigo"'); ?>
                  <?php db_input('basesalario_descricao', 40, $Ir11_basesalario, true, 'text', 3, 'lang="r08_descr"'); ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="submit" value="Salvar" name="alterar"  <?= (!$db_botao ? 'disabled' : ''); ?> id="db_opcao" onclick="return js_validarDados()">
</form>
<script src="scripts/scripts.js"></script>
<script src="scripts/prototype.js"></script>
<script src="scripts/widgets/DBLookUp.widget.js"></script>
<script>

(function(){

  var oBaseFerias  = new DBLookUp($('r11_baseferias_ancora'), $('r11_baseferias'), $('baseferias_descricao'), {
    'sArquivo'     : 'func_bases.php',
    'sObjetoLookUp': 'db_iframe_bases',
    'sLabel'       : 'Pesquisa Base'
  });

  var oBaseSalario = new DBLookUp($('r11_basesalario_ancora'), $('r11_basesalario'), $('basesalario_descricao'), {
    'sArquivo'     : 'func_bases.php',
    'sObjetoLookUp': 'db_iframe_bases',
    'sLabel'       : 'Pesquisa Base'
  });

  $('r11_baseferias').ondrop = function() {
    return false;
  };

  $('r11_basesalario').ondrop = function() {
    return false;
  };
})();

function js_validarDados(){
  
  if ($('r11_compararferias').value == 't') {

    if ($('r11_basesalario').value == '' || $('r11_baseferias').value == '') {

      alert('Base de férias e salário são obrigatórias!');
      return false;
    }

    var oRegex = new RegExp("^[A-Za-z0-9]");

    if (!oRegex.test($('r11_basesalario').value) || !oRegex.test($('r11_baseferias').value)) {

      alert('O campo com o Código da Base, deve ser preenchido somente com letras e números!');
      return false;
    }
  }

  return true;
}

$('r11_compararferias').observe('change', function() {

  $('r11_basesalario').value       = '';
  $('r11_baseferias').value        = '';
  $('basesalario_descricao').value = '';
  $('baseferias_descricao').value  = '';

  if (this.value == 't') {

    $('baseferias').style.display            = '';
    $('basesalario').style.display           = '';
  } else {

    $('baseferias').style.display  = 'none';
    $('basesalario').style.display = 'none';
  }
});
</script>