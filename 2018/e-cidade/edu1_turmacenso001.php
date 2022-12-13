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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");


$oRotulo = new rotulocampo();
$oRotulo->label('ed342_nome');

?>

<html>
<head>

  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaCalendario.classe.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToggleList.widget.js"></script>

</head>
<body bgcolor="#cccccc">

  <?php
    /**
     * Validamos se estamos no módulo escola
     */
    if (db_getsession("DB_modulo") == 1100747) {
      MsgAviso(db_getsession("DB_coddepto"),"escola");
    }
  ?>
  <div class="container" style="min-width: 600px;">

    <form>
      <fieldset>
        <legend>Turmas Multietapa de Ensinos Diferentes</legend>

        <table class="form-container">
          <tr>
            <td nowrap="nowrap" class="field-size3">Calendário:</td>
            <td nowrap="nowrap" id="ctnCalendarios"></td>
          </tr>
          <tr>
            <td nowrap="nowrap" class="field-size3">Turno: </td>
            <td nowrap="nowrap">
              <select id="turnoReferente" onchange="js_buscaDependencias();">
                <option value="" selected="selected">Selecione um turno</option>
                <option value="1" >Manhã</option>
                <option value="2" >Tarde</option>
                <option value="3" >Noite</option>
              </select>
            </td>
          </tr>
          <tr>
            <td nowrap="nowrap" class="field-size3">Dependência:</td>
            <td nowrap="nowrap">
              <select id="dependencia" onchange="js_buscaTurmas();">
                <option value="" selected="selected">Selecione uma dependência</option>
              </select>
            </td>
          </tr>
          <tr>
            <td nowrap="nowrap" class="field-size3">Etapa Censo:</td>
            <td nowrap="nowrap">
              <select id="censoEtapa" >
                <option value="" selected="selected">Selecione uma etapa</option>
              </select>
            </td>
          </tr>
          <tr>
            <td nowrap="nowrap" class="field-size3">Nome:</td>
            <td nowrap="nowrap">
              <?php
                db_input('nomeTurmaCenso',   63, $Ied342_nome, true, 'text', 1);
                db_input('codigoTurmaCenso', 10, '', true, 'hidden', 1);
              ?>
            </td>
          </tr>
        </table>

        <fieldset class="separator">

          <legend>Turmas</legend>
          <div id="cntTurmas"></div>

        </fieldset>

      </fieldset>
      <input type="button" value="Salvar" name="acao" id="acao">
      <input type="button" value="Pesquisar" name="Pesquisar" id="pesquisar">
      <input type="button" value="Novo Registro" name="Novo" id="novo">
    </form>
  </div>
</body>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>
<script type="text/javascript">

var oGet = js_urlToObject();

const MSG_TURMACENSO = 'educacao.escola.edu1_turmacenso001.';

/**
 * Cria o toogleList para manipular as turmas
 */
var oToogleTurmas = new DBToggleList( [{ sId: 'sTurma', sLabel: 'Turma' }] );
oToogleTurmas.closeOrderButtons();
oToogleTurmas.show( $('cntTurmas') );

/**
* Instancia o componente dos calendários
* @type {DBViewFormularioEducacao.ListaCalendario}
*/
var oCalendario = new DBViewFormularioEducacao.ListaCalendario();

/**
 * Seta a função a ser executada no change do combo dos calendários
 */
var fChangeCalendario = function() {

  var oCalendarioSelecionado = oCalendario.getSelecionados();
  js_buscaDependencias();
  js_buscaEtapasCenso();
};

/**
 * Verifica se existe um calendário selecionado e chama uma função onLoad
 */
var fOnLoadCalendario = function() {

  if ( !empty( oCalendario.getSelecionados().iCalendario ) ) {
    js_buscaDependencias();
  }
};

oCalendario.setOnChangeCallBack( fChangeCalendario );
oCalendario.setCallBackLoad( fOnLoadCalendario );
oCalendario.show( $('ctnCalendarios') );


function js_buscaDependencias() {

  var oCalendarioSelecionado = oCalendario.getSelecionados();
  js_limpaDependencia();
  if ( oCalendarioSelecionado.iCalendario == '') {
    return;
  }
  if ( $F('turnoReferente') == '' ) {
    return;
  }

  var oParametros             = {};
  oParametros.exec            = 'getDependenciasComMaisDeUmaTurmaVinculada';
  oParametros.iCalendario     = oCalendarioSelecionado.iCalendario;
  oParametros.iTurnoReferente = $F('turnoReferente');

  var oRequest = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON( oParametros );
  oRequest.onComplete = js_retornoBuscaDependencia;

  js_divCarregando( _M(MSG_TURMACENSO+'busca_dependencia'), "msgBoxA");
  new Ajax.Request('edu4_turmas.RPC.php', oRequest);
}

function js_limpaDependencia() {

  $('dependencia').options.length = 0 ;
  $('dependencia').add( new Option('Selecione uma dependência', ''));
  oToogleTurmas.clearAll();
}

function js_retornoBuscaDependencia(oAjax) {

  js_removeObj("msgBoxA");
  var oRetorno = eval('(' + oAjax.responseText + ')');

  if ( parseInt(oRetorno.status) == 2 ) {
    alert(oRetorno.message.urlDecode());
  }

  oRetorno.aDependencia.each( function( oDependencia ) {
    $('dependencia').add( new Option(oDependencia.sDescricao.urlDecode(), oDependencia.iCodigo) );
  });

}

function js_buscaTurmas() {

  oToogleTurmas.clearAll();
  if ($F('dependencia') == '') {
    return;
  }

  var oCalendarioSelecionado  = oCalendario.getSelecionados();
  var oParametros             = {};
  oParametros.exec            = 'getTurmasCompartilhamSala';
  oParametros.iCalendario     = oCalendarioSelecionado.iCalendario;
  oParametros.iTurnoReferente = $F('turnoReferente');
  oParametros.iDependencia    = $F('dependencia');

  var oRequest        = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON( oParametros );
  oRequest.onComplete = js_retornoBuscaTurmas;

  js_divCarregando( _M(MSG_TURMACENSO+'busca_turmas'), "msgBoxB");
  new Ajax.Request('edu4_turmacenso.RPC.php', oRequest);
}

function js_retornoBuscaTurmas( oAjax ) {

  js_removeObj("msgBoxB");
  var oRetorno = eval('(' + oAjax.responseText + ')');

  if ( parseInt(oRetorno.status) == 2 ) {
    alert(oRetorno.message.urlDecode());
  }

  $('codigoTurmaCenso').value = oRetorno.iTurmaCenso;

  if( !empty( oRetorno.iEtapaCenso ) ) {

    $('censoEtapa').value     = oRetorno.iEtapaCenso;
    $('nomeTurmaCenso').value = oRetorno.sNomeTurma.urlDecode();
  }

  js_carregaTurmas(oRetorno.aTurmasSala);
}

/**
 * Imprime as turmas no toogle
 */
function js_carregaTurmas(aTurmasSala) {

  aTurmasSala.each( function( oTurma ) {

    oTurma.sTurma = oTurma.sTurma.urlDecode();

    if (oTurma.lVinculada) {
      oToogleTurmas.addSelected(oTurma);
    } else {
      oToogleTurmas.addSelect(oTurma);
    }

  });

  oToogleTurmas.renderRows();
}

/**
 *  Busca as etapas do censo
 */
function js_buscaEtapasCenso() {

  var oParametros  = {};
  oParametros.exec = 'censoMultiEtapa';
  oParametros.iAnoCalendario = oCalendario.getSelecionados().iAno;


  var oRequest            = {};
  oRequest.method         = 'post';
  oRequest.parameters     = 'json='+Object.toJSON( oParametros );

  oRequest.asynchronous = false;
  oRequest.onComplete   = function(oAjax) {

    js_removeObj('msgBoxC');
    var oRetorno = eval( '(' + oAjax.responseText + ')');

    if ( oRetorno.aCensoEtapa.length == 0 ) {
      alert( _M(MSG_TURMACENSO+'censo_etapa_inconsistente') );
    }

    $('censoEtapa').options.length = 0;
    $('censoEtapa').add( new Option('Selecione uma etapa', ''));
    oRetorno.aCensoEtapa.each( function( oCensoEtapa ) {
      $('censoEtapa').add( new Option( oCensoEtapa.sEtapa.urlDecode(), oCensoEtapa.iCodigo ) );
    });

  };

  js_divCarregando( _M(MSG_TURMACENSO+'busca_censo_etapa'), "msgBoxC");
  new Ajax.Request('edu4_censoescolar.RPC.php', oRequest);
}

/**
 * Função de carregamento dos dados
 */
(function () {

  $('novo').setAttribute("disabled", "disabled");
  oCalendario.getCalendarios();

  switch ( parseInt(oGet.db_opcao) ) {

    case 1:

      $('pesquisar').setAttribute("disabled", "disabled");
      break;

    case 3:

      $('acao').value = "Excluir";
      $('novo').removeAttribute('disabled');
      js_pesquisar();
      js_bloqueiaCampos();
      break;
  }

})();

/**
 * Busca os dados de turma censo
 */
function js_pesquisar() {

  var sUrl = 'func_turmacenso.php?funcao_js=parent.js_retornoPesquisa|ed342_sequencial';
  js_OpenJanelaIframe('', 'db_iframe_turmacenso', sUrl, 'Pesquisa Turma Censo', true);

}
/**
 * Requisita ao RPC os dados da turmacenso
 */
function js_retornoPesquisa( iTurmaCenso ) {

  db_iframe_turmacenso.hide();

  var oParametros         = {};
  oParametros.exec        = 'getDadosTurmaCenso';
  oParametros.iTurmaCenso = iTurmaCenso;

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON( oParametros );
  oRequest.asynchronous = false;
  oRequest.onComplete   = function ( oAjax ) {

    js_removeObj("msgBoxC");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    if ( parseInt(oRetorno.status) == 2 ) {
      alert(oRetorno.message.urlDecode());
    }

    $('codigoTurmaCenso').value = oRetorno.iTurmaCenso
    $('nomeTurmaCenso').value   = oRetorno.sTurmaCenso.urlDecode();
    $('censoEtapa').value       = oRetorno.iEtapaCenso;

    oToogleTurmas.clearAll();
    js_carregaTurmas(oRetorno.aTurmasSala);

  };
  js_divCarregando( _M(MSG_TURMACENSO+'busca_turma_censo'), "msgBoxC");
  new Ajax.Request('edu4_turmacenso.RPC.php', oRequest);
}


/**
 * Bloqueia os campos do formulário
 */
function js_bloqueiaCampos() {

  oCalendario.permitirSelecao(false);
  $('turnoReferente').setAttribute("disabled", "disabled");
  $('dependencia').setAttribute("disabled", "disabled");
  $('censoEtapa').setAttribute("disabled", "disabled");
  oToogleTurmas.disable();
}

function js_validaDados() {

  var oCalendarioSelecionado  = oCalendario.getSelecionados();
  if ( oCalendarioSelecionado.iCalendario == '') {

    alert( _M( MSG_TURMACENSO+'selecione_calendario'));
    return false;
  }

  if ( $F('turnoReferente') == '' ) {

    alert( _M( MSG_TURMACENSO+'selecione_turno'));
    return false;
  }

  if ( $F('dependencia') == '') {

    alert( _M( MSG_TURMACENSO+'selecione_dependencia'));
    return false;
  }

  if ( $F('censoEtapa') == '' ) {

    alert( _M( MSG_TURMACENSO+'selecione_etapa_censo'));
    return false;
  }

  if ( $F('nomeTurmaCenso') == '' ) {

    alert( _M( MSG_TURMACENSO+'informe_nome'));
    return false;
  }

  var aTurmasSelecionadas = oToogleTurmas.getSelected();
  if (aTurmasSelecionadas.length < 2 ) {

    alert( _M( MSG_TURMACENSO+'selecione_turma'));
    return false;
  }

  return true;
}

$('acao').observe('click', function() {

  if (parseInt(oGet.db_opcao) == 3) {
    js_removerTurmaCenso();
  } else {
    js_salvarTurmaCenso();
  }

});

$('pesquisar').observe('click', function() {

  js_pesquisar();
});


function js_salvarTurmaCenso() {

  if (!js_validaDados() ) {
    return;
  }
  var aTurmasSelecionadas = oToogleTurmas.getSelected();

  var oParametros            = {};
  oParametros.exec           = 'salvar';
  oParametros.iTurmaCenso    = $F('codigoTurmaCenso');
  oParametros.sTurmaCenso    = $F('nomeTurmaCenso');
  oParametros.iCensoEtapa    = $F('censoEtapa');
  oParametros.iSala          = $F('dependencia');
  oParametros.iAnoCalendario = oCalendario.getSelecionados().iAno;
  oParametros.aTurmas        = [];

  aTurmasSelecionadas.each( function (oTurma) {
    oParametros.aTurmas.push(oTurma.iTurma);
  });

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON( oParametros );
  oRequest.asynchronous = false;
  oRequest.onComplete   = function ( oAjax ) {

    js_removeObj('msgBoxD');
    var oRetorno = eval( '(' + oAjax.responseText + ')' );

    alert(oRetorno.message.urlDecode());
    location.href = 'edu1_turmacenso001.php?db_opcao='+oGet.db_opcao;
  }
  js_divCarregando( _M( MSG_TURMACENSO +'aguarde_processando' ), 'msgBoxD');
  new Ajax.Request('edu4_turmacenso.RPC.php', oRequest);

}

function js_removerTurmaCenso() {

  var oParametros         = {};
  oParametros.exec        = 'excluir';
  oParametros.iTurmaCenso = $F('codigoTurmaCenso');

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON( oParametros );
  oRequest.asynchronous = false;
  oRequest.onComplete   = function ( oAjax ) {

    js_removeObj('msgBoxD');
    var oRetorno = eval( '(' + oAjax.responseText + ')' );

    alert(oRetorno.message.urlDecode());
    location.href = 'edu1_turmacenso001.php?db_opcao=3';
  }
  js_divCarregando( _M( MSG_TURMACENSO +'aguarde_processando' ), 'msgBoxD');
  new Ajax.Request('edu4_turmacenso.RPC.php', oRequest);

}

$('novo').observe('click', function() {
  location.href = 'edu1_turmacenso001.php?db_opcao=1';
});

</script>