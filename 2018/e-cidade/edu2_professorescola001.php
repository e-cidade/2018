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
require_once("dbforms/db_funcoes.php");
?>
<html xmlns="http://www.w3.org/1999/html">
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaEscola.classe.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body bgcolor="#CCCCCC" >

<div class="container">

  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>

  <fieldset style="width: 500px;">
    <legend>Relatório Professores por Escola</legend>

    <table class="form-container">
      <tr>
        <td nowrap="nowrap" >Escola:</td>
        <td nowrap="nowrap" id='listaEscola'></td>
      </tr>
      <tr>
        <td nowrap='nowrap'>Área de Trabalho:</td>
        <td nowrap='nowrap'>
          <select id="areaTrabalho">
            <option value="">Selecione uma Área de Trabalho...</option>
          </select>
        </td>
      </tr>

      <tr>
        <td>
          <label>Tipo de Hora:</label>
        </td>
        <td>
          <select id="tipoHora">
            <option value="">Selecione um Tipo de Hora...</option>
          </select>
        </td>
      </tr>

      <tr>
        <td>
          <label>Totalizador:</label>
        </td>
        <td>
          <select id="totalizar">
            <option value="1">POR REGIME DE TRABALHO</option>
            <option value="2">POR TIPO DE HORA</option>
          </select>
        </td>
      </tr>

      <tr>
        <td>
          <label>Modelo:</label>
        </td>
        <td>
          <select id="modelo">
            <option value="1">SINTÉTICO</option>
            <option value="2">ANALÍTICO</option>
          </select>
        </td>
      </tr>

      <tr>
        <td nowrap='nowrap' colspan="2">
          <input type="checkbox" name="disciplina" id="disciplina" value="" checked>
          <label for="disciplina">Mostrar disciplinas que o professor leciona.</label>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="button" value="Imprimir" id="imprimir" name="imprimir" />
</div>

</body>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>

<script type="text/javascript">

const MSG_EDU2PROFESSORESCOLA = 'educacao.escola.edu2_professorescola001.';
var oEscola = new DBViewFormularioEducacao.ListaEscola();

$('disciplina').setAttribute( 'disabled', 'disabled' );

$('modelo').onchange = function() {

  $('disciplina').setAttribute( 'disabled', 'disabled' );

  if( $F('modelo') == 2 ) {
    $('disciplina').removeAttribute( 'disabled' );
  }
};

var fFuncaoLoadEscola = function() {

  if (this.oCboEscola.options.length > 2) {
    this.oCboEscola.value = '';
  } else {

    js_buscaAreaTrabalho();
    buscaTiposHora();
  }
};

var fFuncionChange = function() {

  var oEscolaSelecionada = oEscola.getSelecionados();

  $('areaTrabalho').length = 0;
  $('areaTrabalho').add( new Option( 'Selecione uma Área de Trabalho...', '' ) );

  $('tipoHora').length = 0;
  $('tipoHora').add( new Option( 'Selecione um Tipo de Hora...', '' ) );
  $('tipoHora').removeAttribute( 'disabled' );

  if (oEscolaSelecionada.codigo_escola !== '' ) {

    js_buscaAreaTrabalho();
    buscaTiposHora();
  }

};

oEscola.setCallBackLoad(fFuncaoLoadEscola);
oEscola.setCallbackOnChange(fFuncionChange);
oEscola.habilitarOpcaoTodas(true);
oEscola.show($('listaEscola'));


function js_buscaAreaTrabalho() {

  var oEscolaSelecionada = oEscola.getSelecionados();

  var oParametro     = {};
  oParametro.exec    = 'getAreasTrabalho';
  oParametro.iEscola = oEscolaSelecionada.codigo_escola;

  js_divCarregando( _M(MSG_EDU2PROFESSORESCOLA+'aguarde_buscando_areatrabalho'), "msgBox");

  var oObjeto        = {};
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = js_retornoBuscaAreaTrabalho;

  new Ajax.Request('edu_educacaobase.RPC.php', oObjeto);
}

function js_retornoBuscaAreaTrabalho(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval( "(" + oAjax.responseText + ")" );

  if( oRetorno.aAreaTrabalho.length == 0 ) {

    alert( _M( MSG_EDU2PROFESSORESCOLA + 'nenhuma_area_encontrada' ) );
    return;
  }

  $('areaTrabalho').add( new Option("TODAS", 0) );
  oRetorno.aAreaTrabalho.each( function (oAreaTrabalho) {
    $('areaTrabalho').add( new Option( oAreaTrabalho.ed25_c_descr.urlDecode(), oAreaTrabalho.ed25_i_codigo ) );
  });
}

/**
 * Busca os tipos de hora cadastrados, conforme opção selecionada
 */
function buscaTiposHora() {

  var oEscolaSelecionada   = oEscola.getSelecionados();
  var oParametros          = {};
      oParametros.sExecuta = 'buscaTipoHoraCadastradas';
      oParametros.iEscola  = oEscolaSelecionada.codigo_escola;

  var oAjaxRequest = new AjaxRequest( 'edu4_tipohoratrabalho.RPC.php', oParametros, retornoBuscaTiposHora );
      oAjaxRequest.setMessage( _M( MSG_EDU2PROFESSORESCOLA + 'buscando_tipos_hora' ) );
      oAjaxRequest.execute();
}

/**
 * Retorna os tipos de hora cadastrados
 * @param oRetorno
 * @param lErro
 */
function retornoBuscaTiposHora( oRetorno, lErro ) {

  if( lErro ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  if( oRetorno.aTipoHoraTrabalho.length == 0 ) {

    alert( MSG_EDU2PROFESSORESCOLA + 'nenhum_tipo_hora_encontrado' );
    return;
  }

  $('tipoHora').add( new Option( 'TODOS', '0' ) );
  oRetorno.aTipoHoraTrabalho.each(function( oTipoHora ) {
    $('tipoHora').add( new Option( oTipoHora.ed128_descricao.urlDecode(), oTipoHora.ed128_codigo ) );
  });
}

/**
 * Verifica se os foram selecionadas opções válidas para impressão
 */
function validaCampos() {

  var oEscolaSelecionada = oEscola.getSelecionados();

  if( oEscolaSelecionada.codigo_escola == '' ) {

    alert( _M( MSG_EDU2PROFESSORESCOLA + 'nenhuma_escola_selecionada' ) );
    return false;
  }

  if( $F('areaTrabalho') == '' ) {

    alert( _M( MSG_EDU2PROFESSORESCOLA + 'nenhuma_area_selecionada' ) );
    return false;
  }

  if( $F('tipoHora') == '' ) {

    alert( _M( MSG_EDU2PROFESSORESCOLA + 'nenhum_tipo_hora_selecionado' ) );
    return false;
  }

  return true;
}

/**
 * Controla quando for clicado para imprimir, validando primeiramente se os campos foram selecionados
 */
$('imprimir').observe('click', function() {

  if( !validaCampos() ) {
    return;
  }

  var disciplina = "N";
  if ( $('disciplina').checked ) {
    disciplina = "S";
  }

  var oEscolaSelecionada  = oEscola.getSelecionados();
  var oParametros         = 'escola='        + oEscolaSelecionada.codigo_escola;
      oParametros        += '&area='         + $F('areaTrabalho');
      oParametros        += '&disciplina='   + disciplina;
      oParametros        += '&iTipoHora='    + $F('tipoHora');
      oParametros        += '&iTotalizador=' + $F('totalizar');
      oParametros        += '&iModelo='      + $F('modelo');

  var sUrl = 'edu2_professorescola002.php?' + oParametros;

  jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
});
</script>