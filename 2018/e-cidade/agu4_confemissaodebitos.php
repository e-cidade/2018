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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oParam = db_utils::postMemory($_GET);
?>
<html>

<head>

    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">

</head>

<body class="body-default">

  <div class="container">
    <form action="" method="post" id="form_contrato">

      <fieldset>

        <legend>Configuração da Emissão de Débitos</legend>
        <table>

          <tr>
            <td>
              <label class="bold" for="cgm">
                <a id="cgm_label">Nome/Razão Social:</a>
              </label>
            </td>
            <td>
              <input class="field-size2" type="text" name="cgm" id="cgm" data="z01_numcgm">
              <input class="field-size8" type="text" name="cgm_descricao" id="cgm_descricao" data="z01_nome">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="contrato">
                <a id="contrato_label">Contrato:</a>
              </label>
            </td>
            <td>
              <input class="field-size2" type="text" name="contrato" id="contrato" data="x54_sequencial">
              <input class="field-size8" type="text" name="contrato_descricao" id="contrato_descricao" data="z01_nome">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="economia">
                <a id="economia_label">Economia:</a>
              </label>
            </td>
            <td>
              <input class="field-size2" type="text" name="economia" id="economia" data="x38_sequencial">
              <input class="field-size8" type="text" name="economia_descricao" id="economia_descricao" data="z01_nome">
            </td>
          </tr>

        </table>

      </fieldset>

      <input type="button" value="Salvar" id="salvar">

    </form>
  </div>

<?php db_menu() ?>

<script>
  var RESPONSAVEL_PAGAMENTO_ECONOMIA = '<?php echo AguaContrato::RESPONSAVEL_PAGAMENTO_ECONOMIA ?>';

  (function () {

    'use strict';

    function restaurarFormulario() {

      oContrato.value = '';
      oContratoDescricao.value = '';

      oEconomia.value = '';
      oEconomiaDescricao.value = '';

      oContratoLookup.desabilitar();
      oEconomiaLookup.desabilitar();
    }

    function aplicarFiltros() {

      oContratoLookup.setParametrosAdicionais(['filtro_cgm=' + oCgm.value]);
      oEconomiaLookup.setParametrosAdicionais([
          'filtro_contrato=' + oContrato.value,
          'filtro_cgm=' + oCgm.value
      ]);
    }

    function onCgmChange(lErroLookup) {

      restaurarFormulario();
      if (lErroLookup === true) {
        return false;
      }

      var oParametros = {
        'exec' : 'carregar',
        'iCgm' : oCgm.value
      };
      new AjaxRequest('agu4_confemissaodebitos.RPC.php', oParametros, function (oRetorno, lErro) {

        if (lErro) {
          return false;
        }

        if (oRetorno.configuracao !== null) {

          var oConfiguracao = oRetorno.configuracao;

          oContratoLookup.habilitar();
          oContrato.value = oConfiguracao.iContratoCodigo;
          oContratoDescricao.value = oConfiguracao.sContratoDescricao;

          if (oConfiguracao.iEconomiaCodigo) {

            oEconomia.value = oConfiguracao.iEconomiaCodigo;
            oEconomiaDescricao.value = oConfiguracao.sEconomiaDescricao;
            oEconomiaLookup.habilitar();
          }
        } else {
          oContratoLookup.habilitar();
        }

        aplicarFiltros();
      }).execute();
    }

    function onContratoChange(iCodigo, lErro) {

      oEconomiaLookup.desabilitar();
      oEconomia.value = '';
      oEconomiaDescricao.value = '';

      if (lErro === true) {
        return false;
      }

      aplicarFiltros();

      var oParametros = {
        'exec' : 'carregarContrato',
        'iCodigo' : oContrato.value
      };
      new AjaxRequest('agu1_aguacontrato.RPC.php', oParametros, function (oRetorno, lErro) {

        if (lErro) {
          return false;
        }

        var oContrato = oRetorno.contrato;
        if (oContrato.lCondominio && oContrato.iResponsavelPagamento == RESPONSAVEL_PAGAMENTO_ECONOMIA) {
          oEconomiaLookup.habilitar();
        }
      }).execute();
    }

    var oBtnSalvar = $('salvar');

    var oCgm = $('cgm');
    var oCgmLabel = $('cgm_label');
    var oCgmDescricao = $('cgm_descricao');
    var oCgmLookup = new DBLookUp(oCgmLabel, oCgm, oCgmDescricao, {
      'sObjetoLookUp' : 'func_nome',
      'sArquivo' : 'func_nome.php',
      'sLabel' : 'Pesquisar CGM',
      'fCallBack' : onCgmChange
    });

    var oContrato = $('contrato');
    var oContratoLabel = $('contrato_label');
    var oContratoDescricao = $('contrato_descricao')
    var oContratoLookup = new DBLookUp(oContratoLabel, oContrato, oContratoDescricao, {
      'sObjetoLookUp' : 'db_iframe_aguacontrato',
      'sArquivo' : 'func_aguacontrato.php',
      'sLabel' : 'Pesquisar Contrato',
      'fCallBack' : onContratoChange
    });

    var oEconomia = $('economia');
    var oEconomiaLabel = $('economia_label');
    var oEconomiaDescricao = $('economia_descricao');
    var oEconomiaLookup = new DBLookUp(oEconomiaLabel, oEconomia, oEconomiaDescricao, {
      'sObjetoLookUp' : 'db_iframe_aguacontratoeconomia',
      'sArquivo' : 'func_aguacontratoeconomia.php',
      'sLabel' : 'Pesquisar Economia'
    });

    oContratoLookup.desabilitar();
    oEconomiaLookup.desabilitar();

    oBtnSalvar.observe('click', function () {

      var oParametros = {
        'exec'      : 'salvar',
        'iCgm'      : oCgm.value,
        'iContrato' : oContrato.value,
        'iEconomia' : oEconomia.value
      };
      new AjaxRequest('agu4_confemissaodebitos.RPC.php', oParametros, function (oRetorno, lErro) {
        alert(oRetorno.message);
      }).execute();
    });
  })();
</script>

</body>
</html>
