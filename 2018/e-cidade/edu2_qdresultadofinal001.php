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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

db_menu(
         db_getsession("DB_id_usuario"),
         db_getsession("DB_modulo"),
         db_getsession("DB_anousu"),
         db_getsession("DB_instit")
       );


?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load( "scripts.js, prototype.js, strings.js, DBFormCache.js, classes/educacao/escola/ListaCalendario.classe.js" );
  db_app::load( "classes/educacao/escola/ListaEscola.classe.js, widgets/DBToggleList.widget.js" );
  db_app::load( "estilos.css" );
  ?>
  <style type="text/css">
    .DBToggleListBox .toggleListActionButons {
      margin:12% 0 10px 5%;
    }
  </style>
</head>
<body class="body-default">
  <div class="container">
    <form action="" method="post" class="form-container">
      <fieldset>
        <legend>Relatório Quadro de Resultados Finais</legend>
        <table>
          <tr>
            <td>
              <label class="bold">Selecione a Escola:</label>
            </td>
            <td id="escola"></td>
          </tr>
          <tr>
            <td>
              <label class="bold">Selecione o Calendário:</label>
            </td>
            <td id="calendario"></td>
          </tr>
          <tr>
            <td colspan="2" rel="ignore-css">
              <fieldset class="separator">
                <legend class="bold">Selecione as Turmas:</legend>
                <div id="toggleTurma"></div>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Tipo do Modelo:</label>
            </td>
            <td>
              <select id="tipoModelo">
                <option value="">Selecione</option>
                <option value="9999">Modelo Padrão</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Brasão:</label>
            </td>
            <td>
              <select id="brasao">
                <option value="S">Sim</option>
                <option value="N">Não</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Diretor:</label>
            </td>
            <td>
              <select id="diretor" disabled="disabled">
                <option value="">Selecione</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Secretário:</label>
            </td>
            <td>
              <select id="secretario" disabled="disabled">
                <option value="">Selecione</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Exibir trocas de turma:</label>
            </td>
            <td>
              <select id="exibirTrocaTurma">
                <option value="N">Não</option>
                <option value="S">Sim</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <input id="btnProcessar" type="button" value="Processar" />
    </form>
  </div>
</body>
<script>
/**
 * Seta as classes para estilização dos campos
 */
$('tipoModelo').className       = 'field-size-max';
$('brasao').className           = 'field-size-max';
$('diretor').className          = 'field-size-max';
$('secretario').className       = 'field-size-max';
$('exibirTrocaTurma').className = 'field-size-max';

/**
 * Variáveis iniciais
 */
const MENSAGENS_QUADRO_RESULTADO_FINAL = 'educacao.escola.edu2_qdresultadofinal001.';

var aTurmas      = [];
var sRpcTurma    = 'edu_educacaobase.RPC.php';
var sRpcModelo   = 'edu4_modelosrelatorio.RPC.php';
var oEscola      = new DBViewFormularioEducacao.ListaEscola();
var oCalendario  = new DBViewFormularioEducacao.ListaCalendario();
var oToggleTurma = new DBToggleList( [{ sId: 'sTurma', sLabel: 'Turma', sWidth: '210px' }] );
    oToggleTurma.closeOrderButtons();
    oToggleTurma.show( $('toggleTurma') );

var oCache = new DBFormCache( 'oCache', 'edu2_qdresultadofinal001.php' );
    oCache.setElements( [ $('brasao'), $('exibirTrocaTurma') ] );
    oCache.load();

$('btnProcessar').onclick = function() {
  geraRelatorio();
};

$('tipoModelo').onchange = function() {

  $('diretor').value       = '';
  $('secretario').value    = '';
  $('diretor').disabled    = true;
  $('secretario').disabled = true;

  if( $F('tipoModelo') != '' && $F('tipoModelo') != 9999 ) {

    $('diretor').disabled    = false;
    $('secretario').disabled = false;
  }
};

/**
 * Função a ser executada no load do combo da escola. Caso retorne somente 1 escola, busca os calendários vinculados a
 * esta
 */
var fLoadEscola = function() {

  $('cboEscola').className = 'field-size-max';

  oCalendario.limpar();
  oToggleTurma.clearAll();

  if( oEscola.getSelecionados().codigo_escola != '' ) {

    oCalendario.setEscola( oEscola.getSelecionados().codigo_escola );
    oCalendario.getCalendarios();
  }
};

/**
 * Função a ser executada ao alterar a opção no combo da escola
 */
var fChangeEscola = function() {

  oCalendario.limpar();
  oToggleTurma.clearAll();

  if( oEscola.getSelecionados().codigo_escola != '' ) {

    oCalendario.setEscola( oEscola.getSelecionados().codigo_escola );
    oCalendario.getCalendarios();
  }
};

/**
 * Seta as propriedades do combo da escola
 */
oEscola.setCallBackLoad( fLoadEscola );
oEscola.setCallbackOnChange( fChangeEscola );
oEscola.show( $('escola') );

/**
 * Função a ser executada no load do combo do calendário
 */
var fLoadCalendario = function() {

  $('cboCalendario').className = 'field-size-max';
  oToggleTurma.clearAll();
};

/**
 * Função a ser executada ao alterar a opção no combo do calendário
 */
var fChangeCalendario = function() {

  oToggleTurma.clearAll();

  if( oCalendario.getSelecionados().iCalendario != '' ) {
    buscaTurmas();
  }
};

/**
 * Seta as propriedades do combo do calendário
 */
oCalendario.setCallBackLoad( fLoadCalendario );
oCalendario.setOnChangeCallBack( fChangeCalendario );
oCalendario.show( $('calendario') );

/**
 * Busca as turmas vinculadas ao calendário selecionado
 */
function buscaTurmas() {

  var oParametros             = {};
      oParametros.exec        = 'pesquisaTurmaEtapa';
      oParametros.iCalendario = oCalendario.getSelecionados().iCalendario;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = retornoBuscaTurmas;

  js_divCarregando( _M( MENSAGENS_QUADRO_RESULTADO_FINAL + 'buscando_turmas' ), "msgBox" );
  new Ajax.Request( sRpcTurma, oDadosRequisicao );
}

/**
 * Retorna as turmas vinculadas ao calendário
 * @param oResponse
 */
function retornoBuscaTurmas( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.dados.length > 0 ) {

    aTurmas = oRetorno.dados;
    oToggleTurma.clearAll();
    oRetorno.dados.each(function( oTurma ) {

      var oDadosTurma        = {};
          oDadosTurma.iTurma = oTurma.ed57_i_codigo;
          oDadosTurma.sTurma = oTurma.ed57_c_descr.urlDecode();
          oDadosTurma.iEtapa = oTurma.codigo_etapa;

      oToggleTurma.addSelect( oDadosTurma );
    });

    oToggleTurma.show( $('toggleTurma') );
  }
}

/**
 * Busca os modelos de relatório configurados, do tipo 4
 */
function buscaModelos() {

  var oParametros            = {};
      oParametros.sExecucao  = 'tipoModelo';
      oParametros.iRelatorio = 4;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = retornoBuscaModelos;

  js_divCarregando( _M( MENSAGENS_QUADRO_RESULTADO_FINAL + 'buscando_modelos' ), "msgBox" );
  new Ajax.Request( sRpcModelo, oDadosRequisicao );
}

/**
 * Retorna os relatórios configurados e acrescenta ao combo
 */
function retornoBuscaModelos( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.aModelos.length > 0 ) {

    oRetorno.aModelos.each(function( oModelo ) {
      $('tipoModelo').add( new Option( oModelo.sNome.urlDecode(), oModelo.iCodigo ) );
    });
  } else {
    $('tipoModelo').value = 9999;
  }
}

/**
 * Busca o(s) diretor(es) e secretário(s) da escola
 */
function buscaDiretoresSecretarios() {

  var oParametros         = {};
      oParametros.exec    = 'buscaEmissor';
      oParametros.iEscola = oEscola.getSelecionados().codigo_escola;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = retornoBuscaDiretoresSecretarios;

  js_divCarregando( _M( MENSAGENS_QUADRO_RESULTADO_FINAL + 'buscando_diretores_secretarios' ), "msgBox" );
  new Ajax.Request( sRpcTurma, oDadosRequisicao );
}

/**
 * Retorna o(s) diretor(es) e secretário(s) da escola e acrescenta ao combo
 */
function retornoBuscaDiretoresSecretarios( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.dados.length > 0 ) {

    oRetorno.dados.each(function( oDadosRetorno, iSeq ) {

      var oElemento = $('diretor');

      if( oDadosRetorno.tipo != "D" ) {
        oElemento = $('secretario');
      }

      var sDescricao = oDadosRetorno.nome.urlDecode();
      if( !empty( oDadosRetorno.descricao ) ) {
        sDescricao += ' - ' + oDadosRetorno.descricao.urlDecode();
      }

      oElemento.add( new Option( sDescricao, oDadosRetorno.rechumano ) );
    });
  }
}

/**
 * Validações ao clicar para imprimir o relatório
 */
function validaImpressao() {

  if( oEscola.getSelecionados().codigo_escola == '' ) {

    alert( _M( MENSAGENS_QUADRO_RESULTADO_FINAL + 'selecione_escola' ) );
    return false;
  }

  if( oCalendario.getSelecionados().iCalendario == '' ) {

    alert( _M( MENSAGENS_QUADRO_RESULTADO_FINAL + 'selecione_calendario' ) );
    return false;
  }

  if( oToggleTurma.getSelected().length == 0 ) {

    alert( _M( MENSAGENS_QUADRO_RESULTADO_FINAL + 'selecione_turma' ) );
    return false;
  }

  if( empty( $F('tipoModelo') ) ) {

    alert( _M( MENSAGENS_QUADRO_RESULTADO_FINAL + 'selecione_tipo_modelo' ) );
    return false;
  }

  return true;
}

/**
 * Gera o relatório, caso o mesmo tenha sido validado corretamente
 */
function geraRelatorio() {

  if( !validaImpressao() ) {
    return;
  }

  var aTurmasImpressao = [];

  aTurmas.each(function( oTurma ) {

    oToggleTurma.getSelected().each(function( oTurmaSelecionada ) {

      if( oTurma.ed57_i_codigo == oTurmaSelecionada.iTurma && oTurma.codigo_etapa == oTurmaSelecionada.iEtapa ) {

        var oDadosTurma = {};
            oDadosTurma.iTurma = oTurma.ed57_i_codigo;
            oDadosTurma.iEtapa = oTurma.codigo_etapa;

        aTurmasImpressao.push( oDadosTurma );
      }
    });
  });

  var sDiretor    = !empty( $F('diretor') )    ? Object.toJSON( $('diretor').options[$('diretor').selectedIndex].innerHTML )       : "";
  var sSecretario = !empty( $F('secretario') ) ? Object.toJSON( $('secretario').options[$('secretario').selectedIndex].innerHTML ) : "";

  var sUrl         = 'edu2_quadroresultadofinalnovo002.php';
  var sParametros  = '?iEscola='           + oEscola.getSelecionados().codigo_escola;
      sParametros += '&iCalendario='       + oCalendario.getSelecionados().iCalendario;
      sParametros += '&aTurmas='           + Object.toJSON(aTurmasImpressao);
      sParametros += '&iModelo='           + $F('tipoModelo');
      sParametros += '&sBrasao='           + $F('brasao');
      sParametros += '&sDiretor='          + sDiretor;
      sParametros += '&sSecretario='       + sSecretario;
      sParametros += '&sExibirTrocaTurma=' + $F('exibirTrocaTurma');

  oCache.save();
  limpaCampos();

  jan = window.open(
                     sUrl + sParametros,
                     '',
                     'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0'
                   );

  jan.moveTo(0,0);
}

/**
 * Limpa os campos após gerar o relatório
 */
function limpaCampos() {

  $('tipoModelo').value    = '';
  $('diretor').value       = '';
  $('secretario').value    = '';
  $('diretor').disabled    = true;
  $('secretario').disabled = true;

  buscaTurmas();
}

buscaModelos();
buscaDiretoresSecretarios();

</script>