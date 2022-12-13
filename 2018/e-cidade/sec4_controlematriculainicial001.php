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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_stdlibwebseller.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_turma_classe.php"));
include(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

  <form class="container" id="frmControleMatricula">
    <input type="hidden" id="iControleMatricula" >
    <fieldset>
      <legend>Controle de Matrícula Inicial</legend>
      <table class="form-container">
        <tr>
          <td>
            <label for="inputAnoInicial">Ano Inicial:</label>
          </td>
          <td>
            <input type="text" id="inputAnoInicial" class="field-size2 readonly" maxlength="4"/>
          </td>
        </tr>  
        <tr>
          <td>
            <label for="inputQuantidadeDias">Quantidade de Dias:</label>
          </td>
          <td>
            <input type="text" id="inputQuantidadeDias" class="field-size2" maxlength="3"/>
          </td>
        </tr>
        <tr>
          <td>
            <label for="inputAnoFinal">Ano Final:</label>
          </td>
          <td>
            <input type="text" id="inputAnoFinal" class="field-size2" maxlength="4"/>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" value="Salvar" id="btnSalvar">
  </form>

  <div id="containerControleMatriculaInicial" class="container">
    <fieldset>
      <legend>Controle Matrícula Inicial</legend>
      <div id="ctnGridControleMatricula"></div>
    </fieldset>
  </div>

</body>
</html>

<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>

<script>

const MENSAGEM_CONTROLEMATRICULAINICIAL001 = "educacao.secretariaeducacao.sec4_controlematriculainicial001.";

$('containerControleMatriculaInicial').style.width = '30%';
$('inputAnoInicial').setAttribute('readonly','readonly');

var sControleMatriculaRPC = "sec4_controlematriculainicial.RPC.php";

var oGridControleMatricula              = new DBGrid('gridControleMatricula');
    oGridControleMatricula.nameInstance = 'oGridControleMatricula';
    oGridControleMatricula.setCellWidth(new Array("25%", "25%", "25%", "25%"));
    oGridControleMatricula.setCellAlign(new Array("center", "center", "center", "center"));
    oGridControleMatricula.setHeader(new Array("Ano Inicial", "Dias", "Ano Final", "Ações"));
    oGridControleMatricula.setHeight(200);
    oGridControleMatricula.show($('ctnGridControleMatricula'));

/**
 * Busca os controles de matrículas já cadastrados
 */
function buscarControleMatricula () {

  var oParametros           = {};
      oParametros.sExecucao = 'buscarControleMatricula';

  var oAjaxRequest = new AjaxRequest( sControleMatriculaRPC, oParametros, retornoBuscarControleMatricula );
      oAjaxRequest.setMessage( _M(MENSAGEM_CONTROLEMATRICULAINICIAL001 + "buscando_configuracoes") );
      oAjaxRequest.execute();
}

/**
 * Recebe os dados retornados da busca dos controles já cadastrados e os adiciona à Grid
 * @param  {stdClass} oRetorno
 * @param  {boolean}  lErro    
 */
function retornoBuscarControleMatricula( oRetorno, lErro ) {

  if( lErro ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return false;
  }

  oGridControleMatricula.clearAll(true);
  oRetorno.aControlesMatriculas.forEach(function(oControleMatricula){
  
    var aLinha = [];
        aLinha.push(oControleMatricula.iAnoInicial);
        aLinha.push(oControleMatricula.iQuantidadeDias);
        aLinha.push(oControleMatricula.iAnoFinal);

    var oBtnAlterar         = document.createElement('input');
        oBtnAlterar.id      = "btnAlterar" + oControleMatricula.iCodigo;
        oBtnAlterar.type    = "button";
        oBtnAlterar.value   = "A";

    var oBtnExcluir       = document.createElement('input');
        oBtnExcluir.id    = "btnExcluir" + oControleMatricula.iCodigo;
        oBtnExcluir.type  = "button";
        oBtnExcluir.value = "E";

    aLinha.push( oBtnAlterar.outerHTML + oBtnExcluir.outerHTML );

    oGridControleMatricula.addRow(aLinha, null);
  });

  oGridControleMatricula.renderRows();

  oRetorno.aControlesMatriculas.forEach(function(oControleMatricula){

      $("btnAlterar" + oControleMatricula.iCodigo).onclick = function( ) {
        alterarControle(oControleMatricula);
      }

      if ( !empty(oControleMatricula.iAnoFinal) ) {
        $("btnAlterar" + oControleMatricula.iCodigo).setAttribute( 'disabled', 'disabled' );
      }

      $("btnExcluir" + oControleMatricula.iCodigo).onclick = function( ) {
        excluirControle(oControleMatricula);
      }

      if ( !oControleMatricula.lUltimoRegistro ) {
        $("btnExcluir" + oControleMatricula.iCodigo).setAttribute( 'disabled', 'disabled' );
      }

  });

  $('inputAnoInicial').value = oRetorno.iAno;

}

buscarControleMatricula();

/**
 * Preenche os campos do formulário com os dados do Controle de Matrícula há serem alterados
 * @param  {object} oControleMatricula
 */
function alterarControle( oControleMatricula ) {

  $('inputAnoInicial').value     = oControleMatricula.iAnoInicial;
  $('inputQuantidadeDias').value = oControleMatricula.iQuantidadeDias;
  $('inputQuantidadeDias').setAttribute('readonly', 'readonly');
  $('inputQuantidadeDias').addClassName('readonly');
  $('iControleMatricula').value  = oControleMatricula.iCodigo;
}

/**
 * Exclui um Controle de Matrícula
 * @param  {object} oControleMatricula
 */
function excluirControle( oControleMatricula ) {
  
  if ( !confirm(  _M(MENSAGEM_CONTROLEMATRICULAINICIAL001 + "confirma_exclusao") ) ) {
    return false;
  }

  var oParametros                 = {};
      oParametros.sExecucao       = 'excluirControleMatricula';
      oParametros.iCodigo         = oControleMatricula.iCodigo;

  var oAjaxRequest = new AjaxRequest( sControleMatriculaRPC, oParametros, retornoExcluir );
      oAjaxRequest.setMessage( "Aguarde, excluindo controle de matrícula..." );
      oAjaxRequest.execute();
}

function retornoExcluir( oRetorno, lErro ) {

  alert(oRetorno.sMensagem.urlDecode());

  if( lErro ) {
    return false;
  }

  limpaFormulario();
  buscarControleMatricula();
}

/**
 * Inclui/Altera um Controle de Matrícula
 * @return
 */
$('btnSalvar').onclick = function() {

  if ( !validaDados() ) {
    return false;
  }

  var oParametros                 = {};
      oParametros.sExecucao       = 'salvarControleMatricula';
      oParametros.iCodigo         = $F('iControleMatricula');
      oParametros.iAnoInicial     = $F('inputAnoInicial');
      oParametros.iAnoFinal       = $F('inputAnoFinal');
      oParametros.iQuantidadeDias = $F('inputQuantidadeDias');

  var oAjaxRequest = new AjaxRequest( sControleMatriculaRPC, oParametros, retornoSalvar );
      oAjaxRequest.setMessage( _M(MENSAGEM_CONTROLEMATRICULAINICIAL001 + "salvando_controle") );
      oAjaxRequest.execute();
}

function retornoSalvar( oRetorno, lErro ) {

  alert( oRetorno.sMensagem.urlDecode() );
  
  if( lErro ) {
    return false;
  }

  limpaFormulario();
  buscarControleMatricula();
}

function limpaFormulario() {
  
  $('frmControleMatricula').reset();
  $('iControleMatricula').value = '';
  $('inputQuantidadeDias').removeAttribute('readonly');
  $('inputQuantidadeDias').className = 'field-size2';
}

/**
 * Validações realizadas antes do envio
 * @return {boolean} 
 */
function validaDados() {

  if ( empty($F('inputAnoInicial')) ) {

    alert( _M(MENSAGEM_CONTROLEMATRICULAINICIAL001 + "existe_controle_em_vigencia") );
    return false;
  }

  if ( empty($F('inputQuantidadeDias')) ) {

    alert( _M(MENSAGEM_CONTROLEMATRICULAINICIAL001 + "informe_quantidade_dias") );
    return false;
  }

  if ( $F('inputQuantidadeDias') < 1 || $F('inputQuantidadeDias') > 365  ) {

    alert( _M(MENSAGEM_CONTROLEMATRICULAINICIAL001 + "valor_invalido_quantidade_dias") );
    return false;
  }

  if ( !empty($F('inputAnoFinal')) && ( $F('inputAnoFinal') < $F('inputAnoInicial') ) ) {

    alert( _M(MENSAGEM_CONTROLEMATRICULAINICIAL001 + "ano_final_menor_inicial")  );
    return false;
  }

  return true;
}

/**
 * Valida para informar apenas números nos campos ao digitar algo
 */
$('inputQuantidadeDias').onkeyup = function() {
  $('inputQuantidadeDias').value = $F('inputQuantidadeDias').somenteNumeros();
};

$('inputQuantidadeDias').onkeydown = function() {
  $('inputQuantidadeDias').value = $F('inputQuantidadeDias').somenteNumeros();
};

$('inputAnoFinal').onkeyup = function() {
  $('inputAnoFinal').value = $F('inputAnoFinal').somenteNumeros();
};

$('inputAnoFinal').onkeydown = function() {
  $('inputAnoFinal').value = $F('inputAnoFinal').somenteNumeros();
};

</script>