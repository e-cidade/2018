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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaEscola.classe.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaCalendario.classe.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToggleList.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form>
      <fieldset>
        <legend>Ata de Resultados Finais</legend>
        <table class="form-container">

          <tr>
            <td>
              <label for="cboEscola">Escola:</label>
            </td>
            <td id="listaEscolas"></td>
          </tr>

          <tr>
            <td>
              <label for="cboCalendario">Calendário:</label>
            </td>
            <td id="listaCalendarios"></td>
          </tr>

          <tr>
            <td colspan="2">
              <fieldset class="separator">
                <legend>Turmas</legend>
                <div id="toggleTurmas" class="subcontainer"></div>
              </fieldset>
            </td>
          </tr>

          <!--PLUGIN DIARIOPROGRESSAOPARCIAL - Combo tipo de frequência - NÃO APAGAR ou ALTERAR ESTA LINHA-->

          <tr>
            <td>
              <label for="cboDiretor">Diretor:</label>
            </td>
            <td>
              <select id="cboDiretor">
                <option value="">Selecione o Diretor(a)</option>
              </select>
            </td>
          </tr>

          <tr>
            <td>
              <label for="cboSecretario">Secretário:</label>
            </td>
            <td>
              <select id="cboSecretario">
                <option value="">Selecione o Secretário(a)</option>
              </select>
            </td>
          </tr>

          <tr>
            <td>
              <label for="codigoRechumano">
                <a href="#" onclick="pesquisaAssinaturaAdicional( true );">Assinatura Adicional:</a>
              </label>
            </td>
            <td>
              <input id="codigoRechumanoEscola"        type="hidden" value="" class="field-size2" />
              <input id="codigoRechumano"              type="text"   value="" class="field-size2" onchange="pesquisaAssinaturaAdicional( false );" />
              <input id="descricaoAssinaturaAdicional" type="text"   value="" class="field-size7 readonly" readonly="readonly" />
            </td>
          </tr>

          <tr>
            <td>
              <label for="cboAtividades">Atividade:</label>
            </td>
            <td>
              <select id="cboAtividades">
                <option value="">Selecione uma atividade</option>
              </select>
            </td>
          </tr>

        </table>
      </fieldset>
      <input id="btnImprimir" type="button" value="Imprimir" />
    </form>
  </div>
</body>
</html>
<?php
db_menu(
         db_getsession("DB_id_usuario"),
         db_getsession("DB_modulo"),
         db_getsession("DB_anousu"),
         db_getsession("DB_instit")
       );
?>
<script>
const MENSAGENS_EDU2_ATARESULTADOSFINAISPROGRESSAO001 = 'educacao.escola.edu2_ataresultadosfinaisprogressao001.';

var sRpcTurmas        = 'edu4_turmas.RPC.php';
var sRpcRecursoHumano = 'edu4_recursohumano.RPC.php';
var sRpcEscola        = 'edu4_escola.RPC.php';

/**
 * Callback referente a lista das escolas
 */
var fCallBackEscola = function() {

  oListaCalendarios.limpar();
  oListaCalendarios.setEscola( oListaEscolas.getSelecionados().codigo_escola );

  oToggleTurmas.clearAll();

  $('cboDiretor').length = 0;
  $('cboDiretor').add( new Option( 'Selecione o Diretor(a)', '' ) );

  $('cboSecretario').length = 0;
  $('cboSecretario').add( new Option( 'Selecione o Secretário(a)', '' ) );

  $('cboAtividades').length = 0;
  $('cboAtividades').add( new Option( 'Selecione uma atividade', '' ) );

  $('codigoRechumano').value              = '';
  $('codigoRechumanoEscola').value        = '';
  $('descricaoAssinaturaAdicional').value = '';

  if( !empty( oListaEscolas.getSelecionados().codigo_escola ) ) {

    oListaCalendarios.getCalendarios();
    buscaDiretor();
    buscaSecretario();
  }
};

/**
 * Cria o elemento ListaEscola, com todas as escolas cadastradas
 * @type {DBViewFormularioEducacao.ListaEscola}
 */
var oListaEscolas = new DBViewFormularioEducacao.ListaEscola();
    oListaEscolas.setCallBackLoad( fCallBackEscola );
    oListaEscolas.setCallbackOnChange( fCallBackEscola );
    oListaEscolas.show( $('listaEscolas') );

/**
 * Callback referente a lista dos calendários
 */
var fCallBackCalendario = function() {

  oToggleTurmas.clearAll();

  if( !empty( oListaCalendarios.getSelecionados().iCalendario ) ) {
    buscaTurmasCalendario();
  }

  if( oListaCalendarios.aCalendarios.length == 0 && !empty( oListaEscolas.getSelecionados().codigo_escola ) ) {

    var oMensagem         = {};
        oMensagem.sEscola = oListaEscolas.getSelecionados().nome_escola.urlDecode();

    alert( _M( MENSAGENS_EDU2_ATARESULTADOSFINAISPROGRESSAO001 + 'nenhum_calendario_turmas', oMensagem ) );
    return false;
  }
};

/**
 * Cria o elemento ListaCalendario, somente com calendários que possuem ao menos uma turma com um aluno de progressão
 * encerrado
 * @type {DBViewFormularioEducacao.ListaCalendario}
 */
var oListaCalendarios = new DBViewFormularioEducacao.ListaCalendario();
    oListaCalendarios.somenteTurmasComProgressaoEncerrada( true );
    oListaCalendarios.setOnChangeCallBack( fCallBackCalendario );
    oListaCalendarios.setCallBackLoad( fCallBackCalendario );
    oListaCalendarios.show( $('listaCalendarios') );

/**
 * Componente para carregar as turmas, do calendário selecionado, que possuem ao menos um aluno de progressão encerrado
 * @type {DBToggleList}
 */
var oToggleTurmas = new DBToggleList( [{ sId: 'sTurma', sLabel: 'Turma' }] );
    oToggleTurmas.closeOrderButtons();
    oToggleTurmas.show( $('toggleTurmas') );

$('btnImprimir').onclick = function() {
  imprimeAta();
};

/**
 * Busca as turmas do calendário selecionado
 */
function buscaTurmasCalendario() {

  var oParametros                             = {};
      oParametros.exec                        = 'buscaTurmasPorCalendarioEscola';
      oParametros.iEscola                     = oListaEscolas.getSelecionados().codigo_escola;
      oParametros.iCalendario                 = oListaCalendarios.getSelecionados().iCalendario;
      oParametros.lSomenteProgressaoEncerrada = true;

  var oAjaxRequest = new AjaxRequest( sRpcTurmas, oParametros, retornoBuscaTurmasCalendario );
      oAjaxRequest.setMessage( _M( MENSAGENS_EDU2_ATARESULTADOSFINAISPROGRESSAO001 + 'buscando_turmas' ) );
      oAjaxRequest.execute();
}

/**
 * Retorno das turmas vinculadas ao calendário
 */
function retornoBuscaTurmasCalendario( oRetorno, lErro ) {

  if( lErro ) {

    alert( oRetorno.message.urlDecode() );
    return false;
  }

  oToggleTurmas.clearAll();

  oRetorno.aTurmas.each(function( oTurma ) {

    var oDadosTurma        = {};
        oDadosTurma.iTurma = oTurma.iTurma;
        oDadosTurma.sTurma = oTurma.sTurma.urlDecode();
        oDadosTurma.iEtapa = oTurma.iEtapa;

    oToggleTurmas.addSelect( oDadosTurma );
  });

  oToggleTurmas.show( $('toggleTurmas') );
}

/**
 * Busca o diretor da escola
 */
function buscaDiretor() {

  var oParametros        = {};
      oParametros.exec   = 'getDiretor';
      oParametros.escola = oListaEscolas.getSelecionados().codigo_escola;

  var oAjaxRequest = new AjaxRequest( sRpcEscola, oParametros, retornoBuscaDiretor );
      oAjaxRequest.setMessage( _M( MENSAGENS_EDU2_ATARESULTADOSFINAISPROGRESSAO001 + 'buscando_diretor' ) );
      oAjaxRequest.asynchronous( false );
      oAjaxRequest.execute();
}

/**
 * Retorno e preenchimento do combo com o diretor da escola
 */
function retornoBuscaDiretor( oRetorno, lErro ) {

  if( lErro ) {

    alert( oRetorno.sMessage );
    return false;
  }

  oRetorno.aResultDiretor.each(function( oDiretor ) {

    var sDiretor = oDiretor.funcao.urlDecode() + ' - ' + oDiretor.nome.urlDecode();

    if( !empty( oDiretor.descricao ) ) {
      sDiretor += ' (' + oDiretor.descricao.urlDecode() + ')';
    }

    $('cboDiretor').add( new Option( sDiretor, oDiretor.ed20_i_codigo ) );
  });
}

/**
 * Busco os secretários da escolas
 */
function buscaSecretario() {

  var oParametros        = {};
      oParametros.exec   = 'getSecretario';
      oParametros.escola = oListaEscolas.getSelecionados().codigo_escola;

  var oAjaxRequest = new AjaxRequest( sRpcEscola, oParametros, retornoBuscaSecretario );
      oAjaxRequest.setMessage( _M( MENSAGENS_EDU2_ATARESULTADOSFINAISPROGRESSAO001 + 'buscando_secretario' ) );
      oAjaxRequest.asynchronous( false );
      oAjaxRequest.execute();
}

/**
 * Retorno e preenchimento do combo dos secretários, caso existam
 */
function retornoBuscaSecretario( oRetorno, lErro ) {

  if( lErro ) {

    alert( oRetorno.sMessage );
    return false;
  }

  oRetorno.aResultSec.each(function( oSecretario ) {

    var sSecretario = oSecretario.funcao.urlDecode() + ' - ' + oSecretario.nome.urlDecode();

    if( !empty( oSecretario.descricao ) ) {
      sSecretario += ' (' + oSecretario.descricao.urlDecode() + ')';
    }

    $('cboSecretario').add( new Option( sSecretario, oSecretario.ed20_i_codigo ) );
  });
}

/**
 * Pesquisa as assinaturas adicionais para o relatório
 */
function pesquisaAssinaturaAdicional( lMostra ) {

  $('cboAtividades').length = 0;
  $('cboAtividades').add( new Option( 'Selecione uma atividade', '' ) );

  var sUrl = 'func_rechumanoescolanovo.php?funcao_js=parent.retornoPesquisaAssinaturaAdicional';

  if( lMostra ) {
    sUrl += '|ed20_i_codigo|z01_nome|db_rechumano';
  } else {

    if( empty( $F('codigoRechumano') ) ) {

      $('descricaoAssinaturaAdicional').value = '';
      return;
    }

    sUrl += '&pesquisa_chave=' + $F('codigoRechumano');
  }

  if( !empty( oListaEscolas.getSelecionados().codigo_escola ) ) {
    sUrl += '&iEscola=' + oListaEscolas.getSelecionados().codigo_escola;
  }

  js_OpenJanelaIframe( 'CurrentWindow.corpo', 'db_iframe_rechumano', sUrl, 'Pesquisa Recurso Humano', lMostra );
}

/**
 * Caso retorne um recurso humano, preenche os campos e busca as atividades do mesmo
 */
function retornoPesquisaAssinaturaAdicional() {

  db_iframe_rechumano.hide();

  var lRetornoPadrao = true;

  if( typeof arguments[2] === 'boolean' && arguments[2] === false ) {

    lRetornoPadrao                          = false;
    $('codigoRechumanoEscola').value        = arguments[3];
    $('descricaoAssinaturaAdicional').value = arguments[0];

    buscaAtividadesRecursoHumano();
  }

  if( typeof arguments[1] === 'boolean' && arguments[1] === true ) {

    lRetornoPadrao                          = false;
    $('codigoRechumano').value              = '';
    $('codigoRechumanoEscola').value        = '';
    $('descricaoAssinaturaAdicional').value = arguments[0];
  }

  if( lRetornoPadrao ) {

    $('codigoRechumano').value              = arguments[0];
    $('codigoRechumanoEscola').value        = arguments[2];
    $('descricaoAssinaturaAdicional').value = arguments[1];

    buscaAtividadesRecursoHumano();
  }
}

/**
 * Busca as atividades do recurso humano pelo código rechumanoescola
 */
function buscaAtividadesRecursoHumano() {

  var oParametros                     = {};
      oParametros.exec                = 'atividadesProfissionalEscola';
      oParametros.iProfissionalEscola = $F('codigoRechumanoEscola');

  var oAjaxRequest = new AjaxRequest( sRpcRecursoHumano, oParametros, retornoBuscaAtividadesRecursoHumano );
      oAjaxRequest.setMessage( _M( MENSAGENS_EDU2_ATARESULTADOSFINAISPROGRESSAO001 + 'buscando_atividades' ) );
      oAjaxRequest.execute();
}

/**
 * Retorno das atividades do recurso humano
 */
function retornoBuscaAtividadesRecursoHumano( oRetorno, lErro ) {

  if( lErro ) {

    alert( oRetorno.message );
    return false;
  }

  $('cboAtividades').length = 0;
  $('cboAtividades').add( new Option( 'Selecione uma atividade', '' ) );

  oRetorno.aAtividades.each(function( oAtividade ) {
    $('cboAtividades').add( new Option( oAtividade.sDescricao.urlDecode(), oAtividade.iCodigo ) );
  });
}

/**
 * Valida os campos preenchidos para impressão do relatório
 * @returns {boolean}
 */
function validaCampos() {

  if( empty( oListaEscolas.getSelecionados().codigo_escola ) ) {

    alert( _M( MENSAGENS_EDU2_ATARESULTADOSFINAISPROGRESSAO001 + 'selecione_escola' ) );
    return false;
  }

  if( empty( oListaCalendarios.getSelecionados().iCalendario ) ) {

    alert( _M( MENSAGENS_EDU2_ATARESULTADOSFINAISPROGRESSAO001 + 'selecione_calendario' ) );
    return false;
  }

  if( oToggleTurmas.getSelected().length == 0 ) {

    alert( _M( MENSAGENS_EDU2_ATARESULTADOSFINAISPROGRESSAO001 + 'selecione_turma' ) );
    return false;
  }

  if ( !empty($F('codigoRechumano')) && empty($F('cboAtividades'))  ) {

    alert( _M( MENSAGENS_EDU2_ATARESULTADOSFINAISPROGRESSAO001 + 'selecione_atividade' ) );
    return false;
  }

  return true;
}

/**
 * Imprime a ata, caso os campos tenham sido validados corretamente
 * @returns {boolean}
 */
function imprimeAta() {

  if( !validaCampos() ) {
    return false;
  }

  var aTurmas = [];

  oToggleTurmas.getSelected().each(function( oTurma ) {

    var oDadosTurma        = {};
        oDadosTurma.iTurma = oTurma.iTurma;
        oDadosTurma.iEtapa = oTurma.iEtapa;

    aTurmas.push( oDadosTurma );
  });

  var sDiretor               = '';
  var sSecretario            = '';
  var sAssinaturaAdicicional = '';
  var sCargoAdicional        = '';
  var sTipoFrequencia        = 'T';

  var sParametros  = '?iEscola='     + oListaEscolas.getSelecionados().codigo_escola;
      sParametros += '&iCalendario=' + oListaCalendarios.getSelecionados().iCalendario;
      sParametros += '&aTurmas='     + JSON.stringify( aTurmas );

  if( $('cboFrequencia') !== null ) {
    sTipoFrequencia = $('cboFrequencia').options[ $('cboFrequencia').selectedIndex ].value;
  }

  if( !empty( $F('cboDiretor') ) ) {

    var aDiretor = $('cboDiretor').options[ $('cboDiretor').selectedIndex ].text.split(" - ");
    sDiretor = aDiretor[1];
  }

  if( !empty( $F('cboSecretario') ) ) {

    var aSecretario = $('cboSecretario').options[ $('cboSecretario').selectedIndex ].text.split(" - ");
    sSecretario = aSecretario[1];
  }

  if( !empty( $F('descricaoAssinaturaAdicional') ) ) {
    sAssinaturaAdicicional = $F('descricaoAssinaturaAdicional');
  }

  if( !empty( $F('cboAtividades') ) ) {
    sCargoAdicional = $('cboAtividades').options[ $('cboAtividades').selectedIndex ].text;
  }

  sParametros += '&sDiretor='               + btoa(sDiretor);
  sParametros += '&sSecretario='            + btoa(sSecretario);
  sParametros += '&sAssinaturaAdicicional=' + btoa(sAssinaturaAdicicional);
  sParametros += '&sCargoAdicional='        + sCargoAdicional;
  sParametros += '&sTipoFrequencia='        + sTipoFrequencia;

  var oJanela = window.open( 'edu2_ataresultadosfinaisprogressao002.php' + sParametros, '', 'scrollbars=1,location=0');
      oJanela.moveTo(0,0);
}
</script>