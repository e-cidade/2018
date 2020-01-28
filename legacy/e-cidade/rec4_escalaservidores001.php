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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
?>

<html>
<head>
  <?php
  db_app::load('scripts.js, datagrid.widget.js, strings.js, prototype.js, estilos.css, AjaxRequest.js');
  ?>
</head>
<body>
<form name="form1" id="form1">
  <div id="container" class="container">
    <fieldset>
      <legend>Escala de Servidores</legend>

      <table>
        <tr>
          <td>
            <?php
            db_ancora('Servidor:', 'js_pesquisaServidor(true)', 1);
            ?>
          </td>
          <td>
            <?php
            db_input('iMatricula'     , 10, 1, true, 'text', 1, "onchange='js_pesquisaServidor(false)'");
            db_input('sNomeServidor'  , 50, 1, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php
            db_ancora('Escala:', 'js_pesquisaEscala(true)', 1);
            ?>
          </td>
          <td>
            <?php
            db_input('iCodigoEscala', 10, 1, true, 'text', 1, "onchange='js_pesquisaEscala(false)'");
            db_input('sDescricao'   , 50, 1, true, 'text', 3);
            ?>
          </td>
        </tr>

        <tr>
          <td>
            <strong>Data Escala:</strong>
          </td>
          <td>
            <?php
            db_inputdata('dDataEscala', null, null, null, true, 'text', 1);
            ?>
          </td>
        </tr>

      </table>

      <input type="button" name="incluir" id="incluir" value="Incluir" onclick="js_incluir()" />

      <fieldset style="margin-top:10px">
        <div id="gridEscalas"></div>
      </fieldset>
    </fieldset>

  </div>
  <?php
  db_menu();
  ?>
</form>
</body>
</html>

<script>

  function js_pesquisaServidor(lMostraLookUp) {

    if (lMostraLookUp) {

      js_OpenJanelaIframe(
        'top.corpo',
        'db_iframe_rhpessoal',
        'func_rhpessoal.php?funcao_js=parent.js_retornoPesquisaServidor|rh01_regist|z01_nome',
        'Consulta Matrícula',
        true
      );
    } else {

      if ( $F('iMatricula') != '') {

        js_OpenJanelaIframe(
          'top.corpo',
          'db_iframe_rhpessoal',
          'func_rhpessoal.php?pesquisa_chave=' + $F('iMatricula') + '&funcao_js=parent.js_retornoPesquisaDigitadaServidor',
          'Consulta Matrícula',
          false
        );
      } else{
        $('sNomeServidor').value = '';
      }
    }
  }

  function js_retornoPesquisaDigitadaServidor(sNomeServidor, lErro) {

    $('sNomeServidor').value = sNomeServidor;

    if( lErro == true){

      $('iMatricula').focus();
      $('iMatricula').value = '';
    } else {
      js_carregarEscalas();
    }
  }

  function js_retornoPesquisaServidor(iMatricula, sNome) {

    $('iMatricula').value    = iMatricula;
    $('sNomeServidor').value = sNome;

    db_iframe_rhpessoal.hide();

    js_carregarEscalas();
  }

  function js_pesquisaEscala(lMostraLookUp) {

    if (lMostraLookUp) {
      js_OpenJanelaIframe(
        'top.corpo',
        'db_iframe_gradeshorarios',
        'func_gradeshorarios.php?funcao_js=parent.js_retornoPesquisaEscala|rh190_sequencial|rh190_descricao',
        'Consulta Escala',
        true
      );
    } else {
      js_OpenJanelaIframe(
        'top.corpo',
        'db_iframe_gradeshorarios',
        'func_gradeshorarios.php?pesquisa_chave=' + $F('iCodigoEscala') + '&funcao_js=parent.js_retornoPesquisaDigitadaEscala',
        'Consulta Escala',
        false
      );
    }
  }

  function js_retornoPesquisaEscala(iCodigo, sDescricao) {

    $('iCodigoEscala').value = iCodigo;
    $('sDescricao').value    = sDescricao;

    db_iframe_gradeshorarios.hide();
  }

  function js_retornoPesquisaDigitadaEscala(sDescricao, lErro) {

    $('sDescricao').value = sDescricao;

    if (lErro == true) {

      $('iCodigoEscala').focus();
      $('iCodigoEscala').value = '';
    }
  }

  var sUrl = "rec4_escalaservidores.RPC.php";

  js_gridEscalas();

  function js_gridEscalas() {

    oGridEscalas              = new DBGrid("DataGridEscalas");
    oGridEscalas.sName        = "DataGridEscalas";
    oGridEscalas.nameInstance = "oGridEscalas";

    oGridEscalas.setHeader(["Código", "Escala", "Data", "Excluir"]);
    oGridEscalas.setCellWidth(["", "200px", "50px", "50px"]);
    oGridEscalas.setCellAlign(["center", "left", "center", "center"]);
    oGridEscalas.setHeight('300');
    oGridEscalas.show( $('gridEscalas') );
    oGridEscalas.showColumn(false, 1);
  }

  function js_carregarEscalas() {

    var aBotoes      = new Array();
    var oParametros  = { 'exec' : 'carregarEscalas', 'iMatricula' : $F('iMatricula') };
    var oAjaxRequest = new AjaxRequest( sUrl, oParametros,

      function (oAjax, lResposta) {

        oGridEscalas.clearAll(true);

        oAjax.aRetornoEscalas.each( function (oEscala, iEscala) {

          oGridEscalas.addRow( [oEscala.iCodigo, oEscala.sDescricao.urlDecode(), js_formatar(oEscala.dDataEscala, 'd'), ''] );

          oBotaoExcluir            = document.createElement('input');
          oBotaoExcluir.type       = 'button';
          oBotaoExcluir.value      = 'Excluir';
          oBotaoExcluir.setAttribute('onclick', 'js_excluir(' + oEscala.iCodigo + ')');

          oBotoes                  = new Object();
          oBotoes.oBotaoExcluir    = oBotaoExcluir;
          oBotoes.sIdCelulaExcluir = oGridEscalas.aRows[iEscala].aCells[3].sId;

          aBotoes.push(oBotoes);
        });

        oGridEscalas.renderRows();

        aBotoes.each( function (oBotao, iBotao) {
          document.getElementById(oBotao.sIdCelulaExcluir).appendChild(oBotao.oBotaoExcluir);
        });
      }
    );

    oAjaxRequest.setMessage('Buscando Tipos...');
    oAjaxRequest.execute();
  }

  function js_incluir() {

    oParametros               = new Object();
    oParametros.exec          = 'incluir';
    oParametros.iMatricula    = $F('iMatricula');
    oParametros.iCodigoEscala = $F('iCodigoEscala');
    oParametros.dDataEscala   = $F('dDataEscala');

    var oAjaxRequest = new AjaxRequest(sUrl, oParametros,

      function (oAjax, lErro) {

        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));

        if ( lErro==false ) {

          $('iCodigoEscala').value = '';
          $('sDescricao').value    = '';
          $('dDataEscala').value   = '';
          js_carregarEscalas();
        }
      }
    );

    oAjaxRequest.setMessage("Salvando");
    oAjaxRequest.execute();
  }

  function js_excluir (iCodigoEscala) {

    oParametros               = new Object();
    oParametros.exec          = 'excluir';
    oParametros.iCodigoEscala = iCodigoEscala;

    if (!confirm('Deseja excluir a escala?')) {
      return false;
    }

    var oAjaxRequest = new AjaxRequest(sUrl, oParametros,

      function (oAjax, lErro) {

        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));

        if(oAjax.erro == false) {
          js_carregarEscalas();
        }
      }
    );

    oAjaxRequest.setMessage("Excluindo escala selecionada.");
    oAjaxRequest.execute();
  }
</script>