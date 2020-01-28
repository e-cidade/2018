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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>

<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load( "scripts.js, prototype.js, strings.js, datagrid.widget.js, json2.js" );
      db_app::load( "estilos.css" );
    ?>
  </head>
  <body bgcolor="#CCCCCC">
    <form class="container">
      <fieldset>
        <legend>Atividade / Profissional</legend>
        <table class="form-container">
          <tr>
            <td><label for="atividade">Atividade:</label></td>
            <td>
              <select id="atividade">
                <option value="2">2 - Auxiliar/Assistente Educacional</option>
                <option value="4">4 - Tradutor Intérprete de LIBRAS</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label for="codigoProfissional">
                <?db_ancora("<b>Profissional:</b>","pesquisa_profissional(true);", "");?>
              </label>
            </td>
            <td>
              <?php
                db_input("codigoProfissional",    5, '', false, 'text', 1, " onchange='pesquisa_profissional(false);' ");
                db_input("descricaoProfissional", 5, '', false, 'text', 3 );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input id="salvar" type="button" value="Salvar" />
    </form>

    <div class="container" style="width: 75%;">
      <fieldset>
        <legend>Profissionais vinculados</legend>
        <div id="ctnProfissionais" ></div>
      </fieldset>
    </div>

  </body>
</html>
<script>
var oGet = js_urlToObject();
var sRpc = 'edu4_turmas.RPC.php';

const MENSAGENS_OUTROS_PROFISSIONAIS = 'educacao.escola.edu1_outrosprofissionaisturma001.';

$('codigoProfissional').addClassName('field-size2');
$('descricaoProfissional').addClassName('field-size7');

$('descricaoProfissional').readOnly               = true;
$('descricaoProfissional' ).style.backgroundColor = "#DEB887";

/**
 * Monta o container com os profissionais que ja possuem vinculo com alguma atividade
 */
var oGridProfissionais = new DBGrid( "gridProfissionais" );
    oGridProfissionais.nameInstance = 'oGridProfissionais';
    oGridProfissionais.setHeader( new Array( "Código", "Recurso Humano", "Nome", "Atividade", "Ação" ) );
    oGridProfissionais.setCellAlign( new Array( "center", "right", "left", "left", "center" ) );
    oGridProfissionais.setCellWidth( new Array( "5%", "10%", "60%", "20%", "5%" ) );
    oGridProfissionais.aHeaders[0].lDisplayed = false;
    oGridProfissionais.show( $('ctnProfissionais') );

/**
 * Função para pesquisar os profissionais que contém a atividade selecionada
 * @param  {boolean} lMostra
 */
function pesquisa_profissional( lMostra ) {

  if( empty( $F('codigoProfissional') ) && !lMostra ) {

    $('descricaoProfissional').value = '';
    return;
  }

  var sUrl        = 'func_rechumano.php?iFuncaoAtividade=' + $('atividade').value + '&iTurma=' +  oGet.iTurma;
      sUrl       += '&funcao_js=parent.mostraProfissional';
  var sParametros = '|ed20_i_codigo|z01_nome';

  if( !lMostra ) {
    sParametros = '&pesquisa_chave=' + $F('codigoProfissional');
  }

  js_OpenJanelaIframe( '', 'db_iframe_rechumano', sUrl + sParametros, 'Pesquisa', lMostra );
}

/**
 * Mostra os dados pesquisados do profissional na tela
 */
function mostraProfissional() {

  if ( arguments[1] !== true && arguments[1] !== false ) {

    $('codigoProfissional').value    = arguments[0];
    $('descricaoProfissional').value = arguments[1];
  } else if ( arguments[1] === true ) {

    $('codigoProfissional').value = '';
    $('descricaoProfissional').value = arguments[0];
  } else {

    $('descricaoProfissional').value = arguments[0];
  }

  db_iframe_rechumano.hide();
}

$('salvar').onclick = function(){
  salvar();
}

/**
 * Salva o vínculo do profissional e atividade, com a turma
 */
function salvar() {

  if( !validar() ) {
    return;
  }

  var oParametro               = new Object();
      oParametro.exec          = 'salvarVinculoProfissional';
      oParametro.iTurma        = oGet.iTurma;
      oParametro.iProfissional = $F('codigoProfissional');
      oParametro.iAtividade    = $F('atividade');

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoSalvar;

  js_divCarregando( _M( MENSAGENS_OUTROS_PROFISSIONAIS + 'salvando_vinculo' ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorno do vínculo salvo do profissional, atividade e turma
 * @param {oResponse}
 */
function retornoSalvar( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  alert( oRetorno.message.urlDecode() );

  if( oRetorno.status == 1 ) {

    $('codigoProfissional').value    = "";
    $('descricaoProfissional').value = "";
    buscaProfissionaisVinculados();
  }
}

/**
 * Valida se os campos obrigatórios foram preenchidos
 * @return {boolean}
 */
function validar() {

  if ( empty( $F('codigoProfissional') ) ) {

    alert( _M( MENSAGENS_OUTROS_PROFISSIONAIS + 'informe_profissional' ) );
    return false;
  }

  if ( empty( oGet.iTurma ) ) {

    alert( _M( MENSAGENS_OUTROS_PROFISSIONAIS + 'informe_turma' ) );
    return false;
  }

  return true;
}

/**
 * Busca os profissionais que possuem vinculo com a turma
 */
function buscaProfissionaisVinculados() {

  var oParametro               = new Object();
      oParametro.exec          = 'buscarProfissionaisVinculados';
      oParametro.iTurma        = oGet.iTurma;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoBuscarProfissionaisVinculados;

  js_divCarregando( _M( MENSAGENS_OUTROS_PROFISSIONAIS + 'buscando_vinculos' ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Monta a linha na grid contendo as informações do vínculo com o profissional e a atividade
 * @param  { Ajax } oResponse
 */
function retornoBuscarProfissionaisVinculados( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  oGridProfissionais.clearAll(true);
  oRetorno.aProfissionais.each(function( oDados ){

    var aLinha    = new Array();
        aLinha[0] = oDados.codigo;
        aLinha[1] = oDados.rechumano;
        aLinha[2] = oDados.nome.urlDecode();
        aLinha[3] = oDados.atividade.urlDecode();
        aLinha[4] = "<input id='iProfissional_" + oDados.codigo
                        + "' type='button' "
                        + "  value='E' "
                        + "  onclick='removerVinculo( " + oDados.codigo + " )' />";

    oGridProfissionais.addRow( aLinha );
  });

  oGridProfissionais.renderRows();
}

/**
 * Remove o vínculo de um profissional pelo código
 * @param  {integer} iCodigo
 */
function removerVinculo ( iCodigo ) {

  if ( !confirm( _M( MENSAGENS_OUTROS_PROFISSIONAIS + 'confirmar_exclusao' ) ) ) {
    return false;
  }

  var oParametro                = new Object();
      oParametro.exec           = 'excluirProfissionalVinculado';
      oParametro.iCodigoVinculo = iCodigo;
      oParametro.iTurma         = oGet.iTurma;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoExcluirProfissionalVinculado;

  js_divCarregando( _M( MENSAGENS_OUTROS_PROFISSIONAIS + 'excluindo_vinculos' ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorno da exclusão do vínculo
 * @param  {Object} oResponse
 */
function retornoExcluirProfissionalVinculado( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  alert( oRetorno.message.urlDecode() );

  if( oRetorno.status == 1 ) {
    buscaProfissionaisVinculados();
  }
}

buscaProfissionaisVinculados();
</script>