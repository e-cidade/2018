<?php

/**
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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';

$clrotulo = new rotulocampo;
$clrotulo->label("rh01_reajusteparidade");
?>
<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">
  <link rel="stylesheet" href="estilos.css">
  <style>

    /**
     * Ajusta largura dos combobox
     */
    #reajuste,
    #regime {
      width: 125px !important; 
    }

    #regime {
      margin-left: 51px;
    }

    /**
     * Ajusta alinhamento dos inputs
     */
    #cargo > .field-size2 {
      margin-left: 61px;
    }

    #lotacao > .field-size2 {
      margin-left: 50px;
    }

    #padrao > .field-size2 {
      margin-left: 55px;
    }
  </style>
</head>
<body>
  <form action="" name="form" method="post" class="container">
    <fieldset>
      <legend>Relatório por Tipo de Reajuste</legend>
      <table class="form-container">
        <tr>
          <td>
            <label for="reajuste">
              <?= $Lrh01_reajusteparidade ?>
            </label>
            <select name="reajuste" id="reajuste">
              <option value="0"></option>
              <option value="1">Real</option>
              <option value="2">Paridade</option>
            </select>
          </td>
        </tr>
        <tr>
          <td id="regime"></td>
        </tr>
        <tr>
          <td id="cargo"></td>
        </tr>
        <tr>
          <td id="lotacao"></td>
        </tr>
        <tr>
          <td id="padrao"></td>
        </tr>
      </table>
    </fieldset>
    <input type="button" value="Processar" onclick="js_processar()" />
  </form>
  <?php db_menu(); ?>
  <script src="scripts/scripts.js"></script>
  <script src="scripts/strings.js"></script>
  <script src="scripts/prototype.js"></script>
  <script src="scripts/geradorrelatorios.js"></script>
  <script src="scripts/classes/DBViewFormularioFolha/FiltroDinamicoPesquisaServidores.classe.js"></script>
  <script>
  DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.makeComboRegime($('regime'));
  DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.makeLookUpCargo($('cargo'));
  DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.makeLookUpLotacao($('lotacao'));
  DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.makeLookUpPadrao($('padrao'));

  function js_processar() {

    var form      = document.form;
    var iReajuste = $F('reajuste');
    var iCargo    = (!form.rh37_funcao.value) ? 0  : form.rh37_funcao.value;
    var iRegime   = (!form.rh30_regime.value) ? 0  : form.rh30_regime.value;
    var iLotacao  = (!form.r70_codigo.value)  ? 0  : form.r70_codigo.value;
    var sPadrao   = (!form.r02_codigo.value)  ? '' : form.r02_codigo.value;

    var aParametros = [
      new js_criaObjetoVariavel('$iReajuste', iReajuste),
      new js_criaObjetoVariavel('$iRegime',   iRegime),
      new js_criaObjetoVariavel('$iCargo',    iCargo),
      new js_criaObjetoVariavel('$iLotacao',  iLotacao),
      new js_criaObjetoVariavel('$sPadrao',   sPadrao)
    ];

    js_imprimeRelatorio(28, js_downloadArquivo, Object.toJSON(aParametros));

    return false;
  }
  </script>
</body>
</html>