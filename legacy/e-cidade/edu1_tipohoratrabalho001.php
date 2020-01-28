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
require_once("dbforms/db_funcoes.php");

$iOpcao = 1;

$oDaoTipoHoraTrabalho = new cl_tipohoratrabalho();
$oDaoTipoHoraTrabalho->rotulo->label();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
<body>
  <div id="ctnCadastro" class="container">
    <form>
      <fieldset>
        <legend>Tipo de Hora de Trabalho</legend>
        <table class="form-container">
          <tr style="display: none;">
            <td>
              <label>
                <?=$Led128_codigo;?>
              </label>
            </td>
            <td>
              <?php
              db_input( 'ed128_codigo', 10, $Ied128_codigo, true );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label>
                <?=$Led128_descricao;?>
              </label>
            </td>
            <td>
              <?php
              db_input( 'ed128_descricao', 10, $Ied128_descricao, true, 'text', $iOpcao );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label>
                <?=$Led128_abreviatura;?>
              </label>
            </td>
            <td>
              <?php
              db_input( 'ed128_abreviatura', 10, $Ied128_abreviatura, true, 'text', $iOpcao );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label>
                <?=$Led128_tipoefetividade;?>
              </label>
            </td>
            <td>
              <select id="efetividade">
                <option value="">SELECIONE</option>
                <option value="1">AMBOS</option>
                <option value="2">PROFESSOR</option>
                <option value="3">FUNCIONÁRIO</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label>
                <?=$Led128_ativo;?>
              </label>
            </td>
            <td>
              <select id="ativo">
                <option value="t">SIM</option>
                <option value="f">NÃO</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <input id="btnSalvar" type="button" value="Salvar"   onclick="salvar()" />
      <input id="btnLimpar" type="button" value="Cancelar" onclick="limpaCampos()" />
    </form>
  </div>
  <div id="ctnRegistros" class="container">
    <fieldset style="width: 1000px">
      <legend>Registros Cadastrados</legend>
      <div id="ctnGridRegistros"></div>
    </fieldset>
  </div>
</body>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>
$('ed128_codigo').className      = 'field-size2';
$('ed128_descricao').className   = 'field-size8';
$('ed128_abreviatura').className = 'field-size8';
$('efetividade').className       = 'field-size-max';
$('ativo').className             = 'field-size-max';

const MENSAGENS_EDU1_TIPOHORATRABALHO001 = 'educacao.escola.edu1_tipohoratrabalho001.';
var sRpc = 'edu4_tipohoratrabalho.RPC.php';

var oGridTiposHora = new DBGrid( 'oGridTiposHora' );
    oGridTiposHora.setHeader( [ 'Código', 'Descrição', 'Abreviatura', 'Tipo Efetividade', 'Efetividade', 'Ativo', 'Ação' ] );
    oGridTiposHora.setCellAlign( [ 'center', 'left', 'left', 'center', 'left', 'left', 'center' ] );
    oGridTiposHora.setCellWidth( [ '5%', '53%', '10%', '5%', '15%', '5%', '7%' ] );
    oGridTiposHora.aHeaders[0].lDisplayed = false;
    oGridTiposHora.aHeaders[3].lDisplayed = false;
    oGridTiposHora.show( $('ctnGridRegistros') );

/**
 * Valida se todos os campos foram devidamente preenchidos, salvando os dados do tipo de hora de trabalho
 */
function salvar() {

  if( !validaCampos() ) {
    return;
  }

  var oParametros              = {};
      oParametros.sExecuta     = 'salvar';
      oParametros.iCodigo      = $F('ed128_codigo');
      oParametros.sDescricao   = encodeURIComponent( tagString( $F('ed128_descricao') ) );
      oParametros.sAbreviatura = encodeURIComponent( tagString( $F('ed128_abreviatura') ) );
      oParametros.iEfetividade = $F('efetividade');
      oParametros.sAtivo       = $F('ativo');

  var oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoSalvar );
      oAjaxRequest.setMessage( _M( MENSAGENS_EDU1_TIPOHORATRABALHO001 + 'salvando_tipo_hora_trabalho' ) );
      oAjaxRequest.execute();
}

/**
 * Retorno do salvar as informaçoes do tipo de hora de trabalho
 */
function retornoSalvar( oRetorno, lErro ) {

  alert( oRetorno.sMensagem.urlDecode() );

  if( lErro ) {
    return;
  }

  limpaCampos();
  buscaTipoHoraCadastrados();
}

/**
 * Busca os tipos de hora de trabalho cadastrados
 */
function buscaTipoHoraCadastrados() {

  var oParametros = {};
      oParametros.sExecuta = 'buscaTipoHoraCadastradas';

  var oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoBuscaTipoHoraCadastrados );
      oAjaxRequest.setMessage( _M( MENSAGENS_EDU1_TIPOHORATRABALHO001 + 'buscando_tipo_hora_cadastradas' ) );
      oAjaxRequest.execute();
}

/**
 * Retorno dos tipos de hora de trabalho cadastrados
 * Os dados sao acrescentados a Grid
 */
function retornoBuscaTipoHoraCadastrados( oRetorno, lErro ) {

  if( lErro ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  if( oRetorno.aTipoHoraTrabalho.length == 0 ) {
    return;
  }

  oGridTiposHora.clearAll( true );
  oRetorno.aTipoHoraTrabalho.each(function( oTipoHoraTrabalho, iSequencia ) {

    var sEfetividade = '';

    switch( oTipoHoraTrabalho.ed128_tipoefetividade ) {

      case '1':

        sEfetividade = 'AMBOS';
        break;

      case '2':

        sEfetividade = 'PROFESSOR';
        break;

      case '3':

        sEfetividade = 'FUNCIONARIO';
        break;
    }

    var sAtivo = 'SIM';
    if( oTipoHoraTrabalho.ed128_ativo == 'f' ) {
      sAtivo = 'NÃO';
    }

    var sDisableButtonAlterar = "";
    var sDisableButtonExcluir = "";
    if (oTipoHoraTrabalho.ed128_codigo == oRetorno.iCodigoNaoPodeSerAlterado) {

      sDisableButtonAlterar = "disabled = 'disabled'";
      sDisableButtonExcluir = "disabled = 'disabled'";
    }

    if ( oTipoHoraTrabalho.vinculado == 1 )  {
      sDisableButtonExcluir = "disabled = 'disabled'";
    }

    var oInputAlterar = "<input id='btnAlterar' type='button' value='A' vinculado='" + oTipoHoraTrabalho.vinculado +"'  " + sDisableButtonAlterar + " onclick='alterarTipoHora( " + iSequencia + ", this )' />";
    var oInputExcluir = "<input id='btnExcluir' type='button' value='E' " + sDisableButtonExcluir + " onclick='excluirTipoHora( " + iSequencia + ", this )' />";

    var aLinha = [];
        aLinha.push( oTipoHoraTrabalho.ed128_codigo );
        aLinha.push( oTipoHoraTrabalho.ed128_descricao.urlDecode() );
        aLinha.push( oTipoHoraTrabalho.ed128_abreviatura.urlDecode() );
        aLinha.push( oTipoHoraTrabalho.ed128_tipoefetividade );
        aLinha.push( sEfetividade );
        aLinha.push( sAtivo );
        aLinha.push( oInputAlterar + ' ' + oInputExcluir );

    oGridTiposHora.addRow( aLinha );
  });

  oGridTiposHora.renderRows();
}

/**
 * Preenche os dados conforme linha da grid selecionada
 */
function alterarTipoHora( iLinha, oElement ) {

  limpaCampos();
  var oTipoHoraTrabalho = oGridTiposHora.aRows[ iLinha ];

  $('ed128_codigo').value      = oTipoHoraTrabalho.aCells[0].content;
  $('ed128_descricao').value   = oTipoHoraTrabalho.aCells[1].content;
  $('ed128_abreviatura').value = oTipoHoraTrabalho.aCells[2].content;
  $('efetividade').value       = oTipoHoraTrabalho.aCells[3].content;

  if ( oElement.getAttribute('vinculado') == 1 )  {

    $('ed128_codigo').setAttribute('disabled', 'disabled');
    $('ed128_descricao').setAttribute('disabled', 'disabled');
    $('ed128_abreviatura').setAttribute('disabled', 'disabled');
    $('efetividade').setAttribute('disabled', 'disabled');
    $('ed128_codigo').addClassName('readonly');
    $('ed128_descricao').addClassName('readonly');
    $('ed128_abreviatura').addClassName('readonly');
    $('efetividade').addClassName('readonly');
  }


  $('ativo').value = oTipoHoraTrabalho.aCells[5].content == 'SIM' ? 't' : 'f';
}

/**
 * Exclui um tipo de hora de trabalho selecionado
 */
function excluirTipoHora( iLinha ) {

  var oTipoHoraTrabalho = oGridTiposHora.aRows[ iLinha ];

  var oMensagem            = {};
      oMensagem.sDescricao = oTipoHoraTrabalho.aCells[1].content;

  if( !confirm( _M( MENSAGENS_EDU1_TIPOHORATRABALHO001 + 'confirma_exclusao', oMensagem ) ) ) {
    return;
  }

  var oParametros          = {};
      oParametros.sExecuta = 'excluirTipoHoraTrabalho';
      oParametros.iCodigo  = oTipoHoraTrabalho.aCells[0].content;

  var oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoExcluirTipoHora );
      oAjaxRequest.setMessage( _M( MENSAGENS_EDU1_TIPOHORATRABALHO001 + 'excluindo_tipo_hora' ) );
      oAjaxRequest.execute();
}

/**
 * Retorno da exclusao do tipo de hora de trabalho selecionado
 */
function retornoExcluirTipoHora( oRetorno, lErro ) {

  alert( oRetorno.sMensagem.urlDecode() );

  if( lErro ) {
    return;
  }

  limpaCampos();
  buscaTipoHoraCadastrados();
}

/**
 * Valida se os campos obrigatórios foram preenchidos
 * @returns {boolean}
 */
function validaCampos() {

  if( empty( $F('ed128_descricao').trim() ) ) {

    alert( _M( MENSAGENS_EDU1_TIPOHORATRABALHO001 + 'descricao_nao_informada' ) );
    return false;
  }

  if( empty( $F('ed128_abreviatura').trim() ) ) {

    alert( _M( MENSAGENS_EDU1_TIPOHORATRABALHO001 + 'abreviatura_nao_informada' ) );
    return false;
  }

  if( empty( $F('efetividade') ) ) {

    alert( _M( MENSAGENS_EDU1_TIPOHORATRABALHO001 + 'efetividade_nao_informada' ) );
    return false;
  }

  return true;
}

/**
 * Limpa os campos e altera os select's para o valor padrão
 */
function limpaCampos() {

  $('ed128_codigo').value      = '';
  $('ed128_descricao').value   = '';
  $('ed128_abreviatura').value = '';
  $('efetividade').value       = '';
  $('ativo').value             = 't';

  $('ed128_codigo').removeAttribute('disabled');
  $('ed128_descricao').removeAttribute('disabled');
  $('ed128_abreviatura').removeAttribute('disabled');
  $('efetividade').removeAttribute('disabled');
  $('ed128_codigo').removeClassName('readonly');
  $('ed128_descricao').removeClassName('readonly');
  $('ed128_abreviatura').removeClassName('readonly');
  $('efetividade').removeClassName('readonly');
}

buscaTipoHoraCadastrados();
</script>