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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, prototype.js, strings.js");
    db_app::load("estilos.css");
    db_app::load("classes/educacao/escola/ListaCalendario.classe.js");
    db_app::load("classes/educacao/escola/ListaTurma.classe.js");
    db_app::load("widgets/DBToggleList.widget.js");
  ?>
  <script type="text/javascript" ></script>
</head>
<body>
  <?php
  /**
   * Validamos se estamos no módulo escola
   */
  if (db_getsession("DB_modulo") == 1100747) {
    MsgAviso(db_getsession("DB_coddepto"),"escola");
  }
  ?>
  <div class="container">
    <form id='formPadrao' action="" class="form-container">
      <fieldset style="width:500px;">
        <legend>Ficha de Avaliação Qualitativa</legend>
        <table class="form-container">
          <tr>
            <td><label>Calendário: </label></td>
            <td id="linhaCalendarios"></td>
          </tr>
          <tr>
            <td><label>Turma: </label></td>
            <td id="linhaTurmas"></td>
          </tr>
          <tr>
            <td><label>Período de Avaliação: </label></td>
            <td id="linhaPeriodos"></td>
          </tr>
        </table>
        <table>
          <tr>
            <td colspan="2">
              <fieldset class="separator" style="width: 500px;">
                <legend>Disciplinas</legend>
                <div id="ctnToogleDisciplinas"></div>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id='imprimir' value='Imprimir' name='imprimir' />
    </form>
  </div>
</body>
<script>
var iEscola                      = <?=db_getsession("DB_coddepto"); ?>;
var sRpc                         = 'edu_educacaobase.RPC.php';
var oCalendarioSelecionado       = '';
const MENSAGEM_FICHA_QUALITATIVA = 'educacao.escola.edu2_fichaqualitativa001.';

/**
 * Instancia o componente dos calendários
 * @type {DBViewFormularioEducacao.ListaCalendario}
 */
var oCalendario = new DBViewFormularioEducacao.ListaCalendario();
    oCalendario.setEscola( iEscola );
    oCalendario.getCalendarios();

/**
 * Seta a função a ser executada no change do combo dos calendários
 */
var fChangeCalendario = function() {

  var oCalendarioSelecionado = oCalendario.getSelecionados();

  oTurma.limpar();
  limpaElemento( oPeriodo );
  oPeriodo.add( new Option( '', '' ) );
  oToogleDisciplinas.clearAll();

  /**
   * Caso tenha sido selecionada a primeira opção do calendário, limpa os demais combos
   */
  if ( empty( oCalendarioSelecionado.iCalendario ) ) {
    return false;
  }

  /**
   * Seta as informações do componente da turma e busca os períodos de avaliação do calendário
   */
  oTurma.setEscola( iEscola );
  oTurma.setCalendario( oCalendarioSelecionado.iCalendario );
  oTurma.somenteComCriterioAvaliacao( true );
  oTurma.getTurmas();
}

oCalendario.setOnChangeCallBack( fChangeCalendario );

/**
 * Verifica se existe um calendário selecionado e chama uma função onLoad
 */
var fOnLoadCalendario = function() {

  if ( !empty( oCalendario.getSelecionados().iCalendario ) ) {

    oTurma.setEscola( iEscola );
    oTurma.setCalendario( oCalendario.getSelecionados().iCalendario );
    oTurma.somenteComCriterioAvaliacao( true );
    oTurma.getTurmas();
  }

}

oCalendario.setCallBackLoad( fOnLoadCalendario );
/**
 * Instancia o componente das turmas
 * @type {DBViewFormularioEducacao.ListaTurma}
 */
var oTurma = new DBViewFormularioEducacao.ListaTurma();

/**
 * Seta as ações a serem executadas no change do combo das turmas
 */
var fChangeTurma = function() {

  oToogleDisciplinas.clearAll();
  if ( empty( oTurma.getSelecionados().codigo_turma ) ) {

    limpaElemento( oPeriodo );
    return false;
  }

  pesquisaPeriodosAvaliacao();
  pesquisaDisciplinas();
}

/**
 * Seta as ações a serem executadas ao carregar os dados o combo
 */
var fLoadTurma = function() {

  if ( !empty( oTurma.getSelecionados().codigo_turma ) ) {

    pesquisaPeriodosAvaliacao();
    pesquisaDisciplinas();
  }
}

oTurma.setCallbackOnChange( fChangeTurma );
oTurma.setCallBackLoad( fLoadTurma );

/**
 * Elemento select dos períodos de avaliação
 */
var oPeriodo       = document.createElement( 'select' );
    oPeriodo.id    = 'oPeriodo';
    oPeriodo.width = '100%';
    oPeriodo.add( new Option( '', '' ) );

/**
 * Cria o toogleList para manipular os ensinos considerados infantis
 */
var oToogleDisciplinas = new DBToggleList( [{ sId: 'sDisciplina', sLabel: 'Disciplina' }] );
    oToogleDisciplinas.closeOrderButtons();
    oToogleDisciplinas.show( $('ctnToogleDisciplinas') );

/**
 * Mostra os componentes nos devidos elementos
 */
oCalendario.show( $('linhaCalendarios') );
oTurma.show( $('linhaTurmas') );
$('linhaPeriodos').appendChild( oPeriodo );

/**
 * Busca os períodos de avaliação do calendário
 */
function pesquisaPeriodosAvaliacao() {

  var oParametro                    = new Object();
      oParametro.exec               = 'getPeriodosDeAvaliacaoTurma';
      oParametro.iTurma             = oTurma.getSelecionados().codigo_turma;
      oParametro.lCriterioAvaliacao = true;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoPesquisaPeriodosAvaliacao;

  js_divCarregando( _M( MENSAGEM_FICHA_QUALITATIVA + 'buscando_periodos' ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorna dos períodos de avaliação do calendário
 * @param oResposta
 */
function retornoPesquisaPeriodosAvaliacao( oResposta ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResposta.responseText + ')' );

  if ( oRetorno.status != 1 ) {

    alert( oRetorno.message.urlDecode() );
    return false;
  }

  limpaElemento( oPeriodo );

  if( oRetorno.aPeriodos.length == 0 ) {

    alert( _M( MENSAGEM_FICHA_QUALITATIVA + 'nenhum_periodo_encontrado' ) );
    return false;
  }

  oPeriodo.add( new Option( 'Selecione um período', '' ) );
  oRetorno.aPeriodos.each(function( oDadosPeriodo ) {
    oPeriodo.add( new Option( oDadosPeriodo.sDescricao.urlDecode(), oDadosPeriodo.iCodigo ) );
  });
}

/**
 * Busca as disciplinas da turma selecionada
 */
function pesquisaDisciplinas() {

  var oParametro              = new Object();
      oParametro.exec         = 'getDisciplinaTurma';
      oParametro.iCodigoTurma = oTurma.getSelecionados().codigo_turma;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoPesquisaDisciplinas;

  js_divCarregando( _M( MENSAGEM_FICHA_QUALITATIVA + 'buscando_disciplinas' ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorna as disciplinas vinculadas a turma
 * @param oResposta
 */
function retornoPesquisaDisciplinas( oResposta ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResposta.responseText + ')' );

  if ( oRetorno.status != 1 ) {

    alert( oRetorno.message.urlDecode() );
    return false;
  }

  oRetorno.aDisciplinas.each(function( oDisciplina ) {

    var oDadosDisciplina             = new Object();
        oDadosDisciplina.iDisciplina = oDisciplina.iCodigoDisciplina;
        oDadosDisciplina.sDisciplina = oDisciplina.sDescricaoDisciplina.urlDecode();

    oToogleDisciplinas.addSelect( oDadosDisciplina );
  });

  oToogleDisciplinas.show( $('ctnToogleDisciplinas') );
}

/**
 * Limpa o combo do elemento passado por parâmetro
 * @param oElemento
 */
function limpaElemento( oElemento ) {

  iTotalElemento = oElemento.length;
  for ( var iContador = 0; iContador < iTotalElemento; iContador++ ) {
    oElemento.options.remove( iContador );
  }
}

/**
 * Ao clicar no botão de Imprimir, verifica se todos os campos foram preenchidos para envio ao relatório
 */
$('imprimir').onclick = function() {

  if( empty( oCalendario.getSelecionados().iCalendario ) ) {

    alert( _M( MENSAGEM_FICHA_QUALITATIVA + 'nenhum_calendario_selecionado' ) );
    return false;
  }

  if( empty( oTurma.getSelecionados().codigo_turma ) ) {

    alert( _M( MENSAGEM_FICHA_QUALITATIVA + 'nenhuma_turma_selecionada' ) );
    return false;
  }

  if( empty( oPeriodo.value ) ) {

    alert( _M( MENSAGEM_FICHA_QUALITATIVA + 'nenhum_periodo_selecionado' ) );
    return false;
  }

  if( oToogleDisciplinas.getSelected().length == 0 ) {

    alert( _M( MENSAGEM_FICHA_QUALITATIVA + 'nenhuma_disciplina_selecionada' ) );
    return false;
  }

  var aDisciplinas = new Array();
  oToogleDisciplinas.getSelected().each(function( oDisciplina ) {
    aDisciplinas.push( oDisciplina.iDisciplina );
  });

  var sUrl  = "edu2_fichaqualitativa002.php";
      sUrl += "?iCalendario=" + oCalendario.getSelecionados().iCalendario;
      sUrl += "&iTurma=" + oTurma.getSelecionados().codigo_turma;
      sUrl += "&iPeriodo=" + oPeriodo.value;
      sUrl += "&aDisciplinas=" + aDisciplinas;

  jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
}
</script>
</html>