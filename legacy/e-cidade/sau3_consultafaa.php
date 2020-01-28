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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulocampo;
$oRotulo->label("z01_v_nome");
$oRotulo->label("sd24_i_codigo");
$oRotulo->label("sd24_i_numcgs");
$oRotulo->label("z01_i_cgsund");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  $assets = array(
    "estilos.css",
    "grid.style.css",
    "scripts.js",
    "strings.js",
    "prototype.js",
    "AjaxRequest.js",
    "DBLookUp.widget.js",
    "datagrid.widget.js",
    "Collection.widget.js",
    "DatagridCollection.widget.js",
    "FormCollection.widget.js"
  );
  db_app::load($assets);
  db_menu();

  try {
    new UnidadeProntoSocorro(db_getsession("DB_coddepto"));
  } catch(\Exception $e) {

    die(
    "<div class='container'><h2>{$e->getMessage()}</h2></div>"
    );
  }
  ?>
</head>
<body class='body-default'>

  <div class="container">

    <fieldset>
      <legend>Consulta Ficha de Atendimento Ambulatorial - FAA</legend>
      <form  name='form1'>

        <table class="form-container">
          <tr>
            <td class="field-size2">
              <label for="tipoConsulta">Consulta por:</label>
            </td>
            <td>
              <select id="tipoConsulta" onchange="validaTipoConsulta();">
                <option value="1">FAA</option>
                <option value="2">CGS</option>
              </select>
            </td>
          </tr>
        </table>

        <fieldset class="separator">
          <legend>Filtro</legend>

          <div id="consultaFAA">
            <table class="form-container">
              <tr>
                <td>
                  <label for="sd24_i_codigo">
                    <?php
                    db_ancora("$Lsd24_i_codigo", "js_pesquisaFaa(true)", 1);
                    ?>
                  </label>
                </td>
                <td>
                  <?php
                  db_input("sd24_i_codigo",10, $Isd24_i_codigo, true, "text", 1, " onchange= 'js_pesquisaFaa(false);'");
                  db_input("z01_v_nome",   40, $Iz01_v_nome,    true, "text", 3, "");
                  db_input("sd24_i_numcgs",10, $Isd24_i_numcgs, true, "hidden", 3);
                  ?>
                </td>
              </tr>
            </table>
          </div>

          <div id="consultaCGS" style="display: none;">
            <table class="form-container">
              <tr>
                <td>
                  <label>
                    <a href="#" id="ancoraCGS" func-arquivo="func_cgs_und.php" func-objeto="db_iframe_cgs_und">CGS:</a>
                  </label>
                </td>
                <td>
                  <input id="codigoCGS" data="z01_i_cgsund" type="text" value="" />
                  <input id="nomeCGS"   data="z01_v_nome"   type="text" value="" readonly="readonly" />
                </td>
              </tr>

            </table>

            <fieldset class="separator">
              <legend>Prontuários</legend>
              <div id="gridProntuarios" style="width: 550px;"></div>
            </fieldset>

          </div>
        </fieldset>

      </form>
    </fieldset>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="validaProntuario();" >
  </div>
</body>
<script type="text/javascript">

const MSG_SAUCONSULTAFAA = "saude.ambulatorial.sau3_consultafaa.";

var oAncoraCGS = new DBLookUp($('ancoraCGS'), $('codigoCGS'), $('nomeCGS'), {
  'sLabel'       : 'Pesquisa CGS',
  'sQueryString' : '&lDesabilitaCgs'
});

var oCollection = new Collection();
    oCollection.setId('sd24_i_codigo');
var oGridProntuarios;

$('pesquisar').disabled = true;

/**
 * Abre a função de pesquisa de FAA
 * @param lMostra
 */
function js_pesquisaFaa(lMostra) {

  var sUrl = 'func_fichaatendimento.php?';
  if( lMostra) {

    sUrl += 'funcao_js=parent.js_retornoPesquisa|sd24_i_codigo|z01_v_nome|sd24_i_numcgs';
    js_OpenJanelaIframe( '', 'db_iframe_fichaatendimento', sUrl, 'Pesquisa FAA', true);
  } else if ( $F('sd24_i_codigo') != '' ) {

    sUrl += 'funcao_js=parent.js_retornoPesquisa';
    sUrl += '&pesquisa_chave=' + $F('sd24_i_codigo');
    js_OpenJanelaIframe( '', 'db_iframe_fichaatendimento', sUrl, 'Pesquisa FAA', false);
  } else {

    $('sd24_i_codigo').value = '';
    $('z01_v_nome').value    = '';
  }
}

/**
 * Retorno da pesquisa da FAA
 */
function js_retornoPesquisa() {

  $('pesquisar').disabled = true;
  if( typeof arguments[1] == "boolean" ) {

    $('z01_v_nome').value    = arguments[0];
    $('sd24_i_numcgs').value = arguments[2];
    if( arguments[1] ) {
      $('sd24_i_codigo').value = "";
      return;
    }
  } else {
    $('sd24_i_codigo').value = arguments[0];
    $('z01_v_nome').value    = arguments[1];
    $('sd24_i_numcgs').value = arguments[2];
  }
  $('pesquisar').disabled = false;
  db_iframe_fichaatendimento.hide();
}

/**
 * Verifica o tipo de consulta a ser feito, tratando os campos de acordo conforme opção selecionada
 */
function validaTipoConsulta() {

  $('consultaFAA').setStyle({'display': ''});
  $('consultaCGS').setStyle({'display': 'none'});

  $('pesquisar').setStyle({'display': ''});

  /**
   * Tipo de consulta "CGS"
   */
  if($F('tipoConsulta') == 2) {

    $('consultaFAA').setStyle({'display': 'none'});
    $('consultaCGS').setStyle({'display': ''});

    $('sd24_i_codigo').value = '';
    $('z01_v_nome').value    = '';
    $('sd24_i_numcgs').value = '';

    $('pesquisar').setStyle({'display': 'none'});

    $('codigoCGS').className = 'field-size2';
    $('nomeCGS').className   = 'readonly field-size7';

    oGridProntuarios = new DatagridCollection(oCollection, 'gridProntuarios');
    oGridProntuarios.addColumn('sd29_d_data', {'width': '80px', 'label': 'Atendimento'}).transform('date');
    oGridProntuarios.addColumn('rh70_descr', {'width': '350px','label': 'Especialidade'}).transform('decode');
    oGridProntuarios.addAction('Consultar', 'Consultar Prontuário', function(event, oItemCollection) {
      abreConsulta(oItemCollection.sd24_i_codigo, $F('codigoCGS'));
    });

    oGridProntuarios.show($('gridProntuarios'));
  }
}

/**
 * Busca os prontuários de um CGS, preenchendo a grid com estes quando CGS possuir atendimento
 */
function buscaProntuarios() {

  var oParametros      = {};
      oParametros.exec = 'getProntuariosCgs';
      oParametros.iCgs = $F('codigoCGS');

  AjaxRequest.create('sau4_ambulatorial.RPC.php', oParametros, function(oRetorno, lErro) {

    if(lErro) {

      alert(oRetorno.sMessage.urlDecode());
      return;
    }

    oCollection.add(oRetorno.aProntuarios);
    oGridProntuarios.reload();
  }).setMessage(_M(MSG_SAUCONSULTAFAA + 'buscando_prontuarios'))
    .execute();
}

/**
 * Validação feita quando a pesquisa for feita por FAA
 */
function validaProntuario() {

  if( $F('sd24_i_codigo') == '' ) {

    alert( _M( MSG_SAUCONSULTAFAA + "informe_faa" ) );
    return;
  }

  abreConsulta($F('sd24_i_codigo'), $F('sd24_i_numcgs'));
}

/**
 * Abre a consulta da FAA
 * @param iProntuario
 * @param iCgs
 */
function abreConsulta(iProntuario, iCgs) {

  var iTop    = 20;
  var iLeft   = 5;
  var iHeight = screen.availHeight-210;
  var iWidth  = screen.availWidth-35;

  var sUrl        = "sau3_consultafaa002.php?iProntuario=" + iProntuario + "&iCgs=" + iCgs;
  var sNomeJanela = 'Ficha de Atendimento Ambulatorial - FAA';
  js_OpenJanelaIframe('', 'db_iframe_consulta', sUrl, sNomeJanela, true, iTop, iLeft, iWidth, iHeight);
}

/**
 * Controla o change do código do CGS
 */
$('codigoCGS').addEventListener('change', function() {

  oGridProntuarios.clear();

  if(!empty($F('codigoCGS'))) {
    buscaProntuarios();
  }
});

oAncoraCGS.setCallBack('onClick', function() {

  oGridProntuarios.clear();
  buscaProntuarios();
});

$('sd24_i_codigo').className = 'field-size2';
$('z01_v_nome').className    = 'field-size7';
</script>