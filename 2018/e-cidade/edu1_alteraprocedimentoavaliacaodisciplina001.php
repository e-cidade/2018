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

$iEscola = db_getsession( "DB_coddepto" );
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <form name="form1" method="post">
    <div class="container">
      <fieldset>
        <legend>V�nculos Disciplina/Procedimentos de Avalia��o</legend>
        <table class="form-container">
          <tr>
            <td class="field-size2">
              <label>Calend�rio:</label>
            </td>
            <td id="listaCalendarios"></td>
          </tr>

          <tr>
            <td class="field-size2">
              <label>Etapa:</label>
            </td>
            <td id="listaEtapas"></td>
          </tr>

          <tr>
            <td class="field-size2">
              <label>Turma:</label>
            </td>
            <td id="listaTurmas"></td>
          </tr>

          <tr>
            <td colspan="2">
              <fieldset class="separator" style="width: 700px;">
                <legend>Disciplinas</legend>
                <table style="width: 100%;">
                  <tr>
                    <td id="gridDisciplinas"></td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <input id="btnSalvar" type="button" value="Salvar" />
    </div>
  </form>

  <?php
    db_menu();
  ?>

</body>
</html>
<script>
require_once( 'scripts/classes/educacao/escola/ListaCalendario.classe.js' );
require_once( 'scripts/classes/educacao/escola/ListaEtapa.classe.js' );
require_once( 'scripts/classes/educacao/escola/ListaTurma.classe.js' );

const MENSAGENS_DISCIPLINAS_PROCEDIMENTO = 'educacao.escola.edu1_alteraprocedimentoavaliacaodisciplina001.';

var sRPCTurmas                   = 'edu4_turmas.RPC.php';
var sRPCEducacaoBase             = 'edu_educacaobase.RPC.php';
var oCalendarioSelecionado       = null;
var oEtapaSelecionada            = null;
var oTurmaSelecionada            = null;
var iEscola                      = <?=$iEscola;?>;
var aProcedimentosAvaliacao      = [];
var oGridTurmas                  = null;
var aDisciplinasTurmaSelecionada = [];
var oWindowReplicar              = null;

/******************************************
 *************** CALEND�RIO ***************
 ******************************************/
/**
 * Cria o elemento combobox referente aos calend�rios ativos da escola
 */
var oCalendario = new DBViewFormularioEducacao.ListaCalendario();
    oCalendario.setEscola( iEscola );

/**
 * Fun��o com as a��es a serem executadas no change do calend�rio
 */
var fChangeCalendario = function() {

  oCalendarioSelecionado = oCalendario.getSelecionados();
  oEtapaSelecionada      = null;
  oTurmaSelecionada      = null;

  oEtapa.limpar();
  oTurma.limpar();
  oGridDisciplinas.clearAll( true );

  if( empty( oCalendarioSelecionado.iCalendario ) ) {

    oCalendarioSelecionado = null;
    return;
  }

  buscaProcedimentos();
  oEtapa.setCalendario( oCalendarioSelecionado.iCalendario );
  oEtapa.pesquisaEtapas();
};

/**
 * Fun��o com as a��es a serem executadas no load do calend�rio
 */
var fLoadCalendario = function() {

  oCalendarioSelecionado = oCalendario.getSelecionados();
  oEtapaSelecionada      = null;
  oTurmaSelecionada      = null;

  oEtapa.limpar();
  oTurma.limpar();
  oGridDisciplinas.clearAll( true );

  if( !empty( oCalendarioSelecionado.iCalendario ) ) {

    buscaProcedimentos();
    oEtapa.setCalendario( oCalendarioSelecionado.iCalendario );
    oEtapa.pesquisaEtapas();
  }
};

oCalendario.setOnChangeCallBack( fChangeCalendario );
oCalendario.setCallBackLoad( fLoadCalendario );
oCalendario.show( $('listaCalendarios') );
oCalendario.getCalendarios();


/******************************************
 ***************** ETAPA ******************
 ******************************************/
/**
 * Cria o elemento combobox referente as etapas vinculadas a um calend�rio selecionado
 */
var oEtapa = new DBViewFormularioEducacao.ListaEtapa();

/**
 * Fun��o com as a��es a serem executadas no load das etapas
 */
var fLoadEtapa = function() {

  oEtapaSelecionada = oEtapa.getSelecionados();
  oTurmaSelecionada = null;

  if( empty( oEtapaSelecionada.codigo_etapa ) ) {
    oEtapaSelecionada = null;
  }

  oTurma.limpar();
  oTurma.setCalendario( oCalendarioSelecionado.iCalendario );
  oTurma.setEtapa( oEtapaSelecionada.codigo_etapa );
  oTurma.somenteComAlunosMatriculados( false );
  oTurma.somenteAtivas( true );
  oTurma.getTurmas();
};

/**
 * Fun��o com as a��es a serem executadas no change da etapa
 */
var fChangeEtapa = function() {

  oEtapaSelecionada = oEtapa.getSelecionados();
  oTurmaSelecionada = null;
  oTurma.limpar();

  if( empty( oEtapaSelecionada.codigo_etapa ) ) {

    oEtapaSelecionada = null;
    oGridDisciplinas.clearAll( true );
    return;
  }

  oTurma.setCalendario( oCalendarioSelecionado.iCalendario );
  oTurma.setEtapa( oEtapaSelecionada.codigo_etapa );
  oTurma.somenteComAlunosMatriculados( false );
  oTurma.somenteAtivas( true );
  oTurma.getTurmas();
};

oEtapa.setCallBackLoad( fLoadEtapa );
oEtapa.setCallbackOnChange( fChangeEtapa );
oEtapa.show( $('listaEtapas') );


/******************************************
 ***************** TURMA ******************
 ******************************************/
var oTurma = new DBViewFormularioEducacao.ListaTurma();

/**
 * Fun��o com as a��es a serem executadas no change da turma
 */
var fChangeTurma = function() {

  oTurmaSelecionada = oTurma.getSelecionados();

  oGridDisciplinas.clearAll( true );

  if ( empty(oTurmaSelecionada.codigo_turma) )  {

    oTurmaSelecionada = null;
    return;
  }

  buscaRegenciasTurma();
};

/**
 * Fun��o com as a��es a serem executadas no load da turma
 */
var fLoadTurma = function() {

  oTurmaSelecionada = oTurma.getSelecionados();
  oGridDisciplinas.clearAll( true );

  if ( empty( oTurmaSelecionada.codigo_turma ) ) {

    oTurmaSelecionada = null;
    return;
  }

  buscaRegenciasTurma();
};

oTurma.setCallbackOnChange( fChangeTurma );
oTurma.setCallBackLoad( fLoadTurma );
oTurma.show( $('listaTurmas') );

/**
 * Elemento grid que cont�m as disciplinas vinculadas a turma e o procedimento de avalia��o das mesmas
 * @type {DBGrid}
 */
var oGridDisciplinas = new DBGrid( 'oGridDisciplinas' );
    oGridDisciplinas.setCellAlign( [ 'left', 'left' ] );
    oGridDisciplinas.setCellWidth( [ '50%', '50%' ] );
    oGridDisciplinas.setHeader( [ 'C�digo Reg�ncia', 'Disciplina', 'Procedimento de Avalia��o' ] );
    oGridDisciplinas.aHeaders[0].lDisplayed = false;
    oGridDisciplinas.show( $('gridDisciplinas') );

$('btnSalvar').onclick = function() {

  if ( !validaCampos() ) {
    return;
  }

  if ( !confirm( _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'confirma_salvar' ) ) ) {
    return;
  }

  var oParametros              = {};
      oParametros.exec         = "salvarProcedimentoAvaliacao";
      oParametros.aTurmas      = [];
      oParametros.aDisciplinas = [];

  var oTurma              = {};
      oTurma.iTurma       = oTurmaSelecionada.codigo_turma;
      oTurma.iEtapa       = oTurmaSelecionada.codigo_etapa;

  oGridDisciplinas.getRows().each( function( oLinha ) {

    var oDadosDisciplina                        = {};
        oDadosDisciplina.iDisciplina            = oLinha.aCells[0].content;
        oDadosDisciplina.iProcedimentoAvaliacao = $F('selectProcedimento' + oLinha.aCells[0].content );

    oParametros.aDisciplinas.push( oDadosDisciplina );
  });

  aDisciplinasTurmaSelecionada = oParametros.aDisciplinas;

  oParametros.aTurmas.push( oTurma );

  var oAjaxRequest = new AjaxRequest( sRPCTurmas, oParametros, retornoSalvar );
      oAjaxRequest.setMessage( _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'salvando_procedimentos' ) );
      oAjaxRequest.execute();
};

/**
 * Retorno do salvar os procedimentos das disciplinas da turma selecionada
 * N�o havendo erro, chama a fun��o respons�vel por apresentar uma windowAux para replicar as altera��es para as demais
 * turmas de uma etapa
 */
function retornoSalvar( oRetorno, lErro ) {

  alert( oRetorno.message.urlDecode() );

  if( lErro ) {
    return;
  }

  replicaAlteracoes();
}

/**
 * Valida se o Calend�rio, a Etapa e a Turma est�o selecionados
 * @returns {boolean}
 */
function validaCampos() {

  if( empty( oCalendarioSelecionado ) ) {

    alert( _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'selecione_calendario' ) );
    return false;
  }

  if( empty( oEtapaSelecionada ) ) {

    alert( _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'selecione_etapa' ) );
    return false;
  }

  if( empty( oTurmaSelecionada ) ) {

    alert( _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'selecione_turma' ) );
    return false;
  }

  return true;
}

/**
 * Busca as Regencias vinculadas a turma
 */
function buscaRegenciasTurma() {

  var oParametros        = {};
      oParametros.exec   = 'getRegencias';
      oParametros.iTurma = oTurmaSelecionada.codigo_turma;
      oParametros.iEtapa = oTurmaSelecionada.codigo_etapa;

  var oAjaxRequest = new AjaxRequest( sRPCTurmas, oParametros, retornoRegencias );
      oAjaxRequest.setMessage( _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'buscando_regencias' ) );
      oAjaxRequest.execute();
}

/**
 * Retorna as reg�ncias vinculadas a turma, montando a grid com seus respectivos procedimentos
 */
function retornoRegencias( oRetorno ) {

  if( oRetorno.aRegencias.length == 0 ) {

    alert( _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'nenhuma_disciplina_vinculada' ) );
    return;
  }

  oGridDisciplinas.clearAll(true);
  oRetorno.aRegencias.each(function( oRegencia ) {

    var oSelectProcedimento = document.createElement( 'select' );
        oSelectProcedimento.setAttribute( 'id', 'selectProcedimento' + oRegencia.iDisciplina );

    aProcedimentosAvaliacao.each(function( oProcedimentoAvaliacao ) {

      oSelectProcedimento.add( new Option( oProcedimentoAvaliacao.sProcedimento.urlDecode(),
                                           oProcedimentoAvaliacao.iProcedimento ) );
    });

    var aLinha = [];
        aLinha.push( oRegencia.iDisciplina );
        aLinha.push( oRegencia.sDisciplina.urlDecode() );
        aLinha.push( oSelectProcedimento.outerHTML );

      oGridDisciplinas.addRow( aLinha );
  });

  oGridDisciplinas.renderRows();

  oRetorno.aRegencias.each(function( oRegencia ) {

    aProcedimentosAvaliacao.each(function ( oProcedimentoAvaliacao ) {

      if ( oRegencia.iProcedimentoAvaliacao == oProcedimentoAvaliacao.iProcedimento ) {
        $('selectProcedimento' + oRegencia.iDisciplina).value = oRegencia.iProcedimentoAvaliacao;
      }
    });
  });
}


/******************************************
 *********** JANELA REPLICAR **************
 ******************************************/

/**
 * Monta a janela contendo as demais turmas que possuem a mesma etapa da turma altera;
 * Replica para todas as turmas selecionadas os Procedimentos de Avalia��o de acordo com cada disciplina
 */
function replicaAlteracoes() {

  var aTurmasPorEtapa = turmasSeremReplicadas();

  if( aTurmasPorEtapa.length == 0 ) {

    oCalendario.getCalendarios();
    return;
  }

  var sTitulo         = 'Replicar Altera��es';
  var iTamanhoJanela  = document.body.clientWidth/2.6;
  var iAlturaJanela   = document.body.getHeight()/0.8;

  oWindowReplicar = new windowAux( 'windowReplicar', sTitulo, iTamanhoJanela, iAlturaJanela  );

  var oDivContainer = document.createElement('div');
      oDivContainer.setAttribute( 'class', 'container' );

  var oFieldset = document.createElement( 'fieldset' );
      oFieldset.setStyle( { 'width' : iTamanhoJanela / 1.1 } );

  var oLegend           = document.createElement( 'legend' );
      oLegend.innerHTML = 'Turmas';

  var oDivGrid = document.createElement('div');
      oDivGrid.setAttribute('id', 'gridTurmas');

  var oBotao        = document.createElement('input');
      oBotao.setAttribute('id', 'salvarProcedimentos');
      oBotao.setAttribute('value', 'Salvar');
      oBotao.setAttribute('type', 'button');

  oGridTurmas              = new DBGrid('oGridTurmas');
  oGridTurmas.nameInstance = 'oGridTurmas';
  oGridTurmas.setCellAlign( [ 'left' ] );
  oGridTurmas.setCellWidth( [ '99%' ] );
  oGridTurmas.setCheckbox(0);
  oGridTurmas.setHeader( [ 'C�digo Turma', 'C�digo Etapa', 'Turma' ] );
  oGridTurmas.aHeaders[1].lDisplayed = false;
  oGridTurmas.aHeaders[2].lDisplayed = false;

  oDivContainer.appendChild(oFieldset);
  oDivContainer.appendChild(oBotao);
  oFieldset.appendChild(oLegend);
  oFieldset.appendChild(oDivGrid);

  oWindowReplicar.setContent(oDivContainer);
  oWindowReplicar.setShutDownFunction( function() {
    limpaCampos();
  });

  var sTituloMessage       = 'Replicar altera��es no procedimento de avalia��o das disciplinas';
  var sAjuda               = _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'ajuda_replicar' );
  var oMessageProcedimento = new DBMessageBoard( 'messageProcedimento', sTituloMessage, sAjuda, oWindowReplicar.getContentContainer() );

  oMessageProcedimento.show();
  oWindowReplicar.show();
  oGridTurmas.show( $('gridTurmas') );

  $('salvarProcedimentos').onclick = function() {

    if ( !validaTurmasReplicar() ) {
      return;
    }

    var oParametros              = {};
        oParametros.exec         = "salvarProcedimentoAvaliacao";
        oParametros.aTurmas      = [];
        oParametros.aDisciplinas = [];

    oGridTurmas.getSelection().each( function( aDadosTurma ) {

      var oTurma        = {};
          oTurma.iTurma = aDadosTurma[1];
          oTurma.iEtapa = aDadosTurma[2];

      oParametros.aTurmas.push( oTurma );
    });

    oParametros.aDisciplinas = aDisciplinasTurmaSelecionada;

    var oAjaxRequest = new AjaxRequest( sRPCTurmas, oParametros, retornoSalvarProcedimentos );
        oAjaxRequest.setMessage( _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'salvando_procedimentos' ) );
        oAjaxRequest.execute();
  };

  preencheGridTurmasReplicar( aTurmasPorEtapa );
}

/**
 * Retorno da altera��o das informa��es dos procedimentos replicados
 */
function retornoSalvarProcedimentos( oRetorno ) {

  alert( oRetorno.message.urlDecode() );
  limpaCampos();
}

/**
 * Retorna ao in�cio do formul�rio e limpa todos os campos
 */
function limpaCampos() {

  oWindowReplicar.destroy();
  oCalendario.limpar();
  oCalendario.getCalendarios();
}

/**
 * Busca todas as turmas que possuem a mesma etapa da turma alterada
 * @returns {Array}
 */
function turmasSeremReplicadas() {

  var aTurmasPorEtapa = [];

  for( var iContador = 0; iContador < $('cboTurma').options.length; iContador++ ) {

    var oTurma = $('cboTurma').options[iContador];

    if( !empty( oTurma.value ) && oTurma.value != oTurmaSelecionada.codigo_turma ) {

      var oTurmaEtapa        = {};
          oTurmaEtapa.iTurma = oTurma.value;
          oTurmaEtapa.iEtapa = oTurma.getAttribute( 'etapa' );
          oTurmaEtapa.sTurma = oTurma.label;

      aTurmasPorEtapa.push( oTurmaEtapa );
    }
  }

  return aTurmasPorEtapa;
}

/**
 * Preenche a grid com as turmas que podem ser replicados os procedimentos de avalia��o
 * @param aTurmasPorEtapa
 */
function preencheGridTurmasReplicar( aTurmasPorEtapa ) {

  oGridTurmas.clearAll(true);

  aTurmasPorEtapa.each( function( oTurma ) {

    var aLinhas = [];
        aLinhas.push(oTurma.iTurma);
        aLinhas.push(oTurma.iEtapa);
        aLinhas.push(oTurma.sTurma);

    oGridTurmas.addRow( aLinhas );
  });

  oGridTurmas.renderRows();
}

/**
 * Valida se ao menos uma turma foi selecionada
 * @returns {boolean}
 */
function validaTurmasReplicar() {

  if ( oGridTurmas.getSelection().length == 0 ) {

    alert( _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'selecione_turma_replicar' ) );
    return false;
  }

  return true;
}


/**
 * Busca os procedimentos de avalia��o vinculados a uma escola, e que possuam os per�odos de avalia��o de algum calend�rio
 * deste mesmo ano
 */
function buscaProcedimentos() {

  var oParametros             = {};
      oParametros.exec        = "getProcedimentosAvaliacao";
      oParametros.iEscola     = iEscola;
      oParametros.iCalendario = oCalendarioSelecionado.iCalendario;

  var oAjaxRequest = new AjaxRequest( sRPCEducacaoBase, oParametros, retornoBuscaProcedimentos );
      oAjaxRequest.setMessage( _M( MENSAGENS_DISCIPLINAS_PROCEDIMENTO + 'buscando_procedimentos' ) );
      oAjaxRequest.execute();
}

/**
 * Guarda os procedimentos retornados em um array
 * @param oRetorno
 * @param lErro
 */
function retornoBuscaProcedimentos( oRetorno ) {
  aProcedimentosAvaliacao = oRetorno.aProcedimentosAvaliacao;
}
</script>