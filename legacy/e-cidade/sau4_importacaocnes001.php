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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" >
  <div class="container">
    <form name="form_upload_scnes" id="form_upload_scnes">
      <fieldset class="form-container">
        <legend>Importação arquivo SCNES</legend>
        <div id="ctnImportacao"></div>
      </fieldset>
      <input id="btnProcessar" type="button" value="Processar" onclick="processar();" disabled="disabled" />
    </form>
  </div>
</body>
<?php
db_menu( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
?>
<script>
const MENSAGENS_SAU4_IMPORTACNES001 = 'saude.ambulatorial.sau4_importacaocnes001.';

var oWindowAuxVinculos = '';
var oGridVinculos      = '';
var sRpc               = 'sau4_importacaocnes.RPC.php';
var lTemInconsistencia = false;

var oMensagemConfirm           = {};
    oMensagemConfirm.sMensagem =  _M( MENSAGENS_SAU4_IMPORTACNES001 + "importacao_sucesso_inconsistencia");

var oFileUpload = new DBFileUpload( {callBack: retornoEnvioArquivo} );
    oFileUpload.show($('ctnImportacao'));

/**
 * Função de retorno ao selecionar um arquivo para upload
 * Valida se foi registrado algum erro ou se o arquivo possui uma extensão inválida
 * @param oRetorno
 * @returns {boolean}
 */
function retornoEnvioArquivo( oRetorno ) {

  if (oRetorno.error) {

    alert(oRetorno.error);
    $('btnProcessar').disabled = true;
    return false;
  }

  if( oRetorno.extension.toLowerCase() != 'xml' ) {

    alert( _M( MENSAGENS_SAU4_IMPORTACNES001 + 'arquivo_invalido' ) );
    $('btnProcessar').disabled = true;
    return false;
  }

  $('btnProcessar').disabled = false;
}

/**
 * Processa o arquivo que foi feito upload, enviando os dados para o RPC
 */
function processar() {

  var oParametros                 = {};
      oParametros.sExecuta        = 'processar';
      oParametros.sNomeArquivo    = oFileUpload.file;
      oParametros.sCaminhoArquivo = oFileUpload.filePath;

  var oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoProcessar );
      oAjaxRequest.setMessage( _M( MENSAGENS_SAU4_IMPORTACNES001 + 'processando_arquivo' ) );
      oAjaxRequest.execute();
}

/**
 * Retorno do processamento do arquivo. No caso de existirem dados não encontrados, ou seja, um estabelecimento do arquivo
 * que não tenha sido encontrado no sistema, será carregada a janela para que seja feito o De/Para entre o estabelecimento
 * do arquivo e os departamentos sem cadastro de UPS ou UPS sem CNES cadastrado
 * @param oRetorno
 * @param lErro
 */
function retornoProcessar( oRetorno, lErro ) {

  if( lErro ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return false;
  }

  lTemInconsistencia = !lTemInconsistencia && oRetorno.lTemInconsistencia;

  if( oRetorno.aCNES.length == 0 && oRetorno.lTemInconsistencia ) {

    var oMensagem           = {};
        oMensagem.sMensagem = oRetorno.sMensagem.urlDecode();

    if( confirm( _M( MENSAGENS_SAU4_IMPORTACNES001 + 'imprimir_relatorio', oMensagemConfirm ) ) ) {
      imprimeRelatorio();
    }
    reload();
    return false;
  }

  alert( oRetorno.sMensagem.urlDecode() );

  if( oRetorno.aCNES.length > 0 ) {

    if( oWindowAuxVinculos != '' && typeof( oWindowAuxVinculos ) != 'undefined' ) {
      oWindowAuxVinculos.destroy();
    }

    var iLarguraJanela = document.body.getWidth() / 1.4;
    var iAlturaJanela  = document.body.clientHeight / 1.3;

    var sConteudoWindowAux  = "<div>";
        sConteudoWindowAux += "  <div id='msgVinculos' style='width: 99%;'>";
        sConteudoWindowAux += "    <fieldset>";
        sConteudoWindowAux += "      <legend>CNES/Departamento</legend>";
        sConteudoWindowAux += "      <div id='gridVinculos'></div>";
        sConteudoWindowAux += "    </fieldset>";
        sConteudoWindowAux += "    <div class='center'>";
        sConteudoWindowAux += "      <input id='btnSalvar' type='button' value='Salvar' onclick='salvarNovosVinculos();'>";
        sConteudoWindowAux += "    </div>";
        sConteudoWindowAux += "  </div>";
        sConteudoWindowAux += "</div>";

    oWindowAuxVinculos = new windowAux( 'oWindowAuxVinculos', 'Vínculo CNES com Departamento', iLarguraJanela, iAlturaJanela );
    oWindowAuxVinculos.setContent( sConteudoWindowAux );
    oWindowAuxVinculos.show();

    oWindowAuxVinculos.setShutDownFunction(function () {

      oWindowAuxVinculos.destroy();
      if (oRetorno.lTemInconsistencia) {

        if( confirm( _M( MENSAGENS_SAU4_IMPORTACNES001 + 'imprimir_relatorio', oMensagemConfirm ) ) ) {
          imprimeRelatorio();
        }
      }
      reload();
    });

    var sTitulo = "CNES não encontrados";
    var sAjuda  = "Selecione os departamentos correspondestes às unidades dos CNES abaixo.";
    oMessageBoardVinculos = new DBMessageBoard( 'oMessageBoardVinculos', sTitulo, sAjuda, $('msgVinculos') );
    oMessageBoardVinculos.show();

    oGridVinculos = new DBGrid( 'oGridVinculos' );
    oGridVinculos.setHeader( [ 'CNES', 'Departamentos XML', 'Departamentos E-Cidade' ] );
    oGridVinculos.setCellAlign( [ 'right', 'left', 'left' ] );
    oGridVinculos.setHeight( iAlturaJanela - 250 );
    oGridVinculos.aHeaders[0].lDisplayed = false;
    oGridVinculos.show( $('gridVinculos') );

    oGridVinculos.clearAll(true);

    oRetorno.aCNES.each(function( oCNES, iSequenciaCNES ) {

      var oSelectDepartamentos = document.createElement( 'select' );
          oSelectDepartamentos.setAttribute( 'id', 'departamento' + iSequenciaCNES );
          oSelectDepartamentos.setAttribute( 'class', 'field-size-max' );
          oSelectDepartamentos.add( new Option( 'Selecione...', '' ) );

      var aLinha = [];
          aLinha.push( oCNES.iCodigo );
          aLinha.push( oCNES.iCodigo + ' - ' + oCNES.sDescricao.urlDecode() );

      oRetorno.aDepartamentos.each(function( oDepartamento, iSequenciaDepartamento ) {

        oSelectDepartamentos.add( new Option( oDepartamento.sDescricao.urlDecode(), oDepartamento.iCodigo ) );
        oSelectDepartamentos.options[ iSequenciaDepartamento + 1 ].setAttribute( 'label', oDepartamento.sDescricao.urlDecode() );
      });

      aLinha.push( oSelectDepartamentos.outerHTML );

      oGridVinculos.addRow( aLinha );
    });

    oGridVinculos.renderRows();
  } else {
    reload();
  }
}

/**
 * Percorre as linhas da Grid, verificando cada vínculo que deve ser realizado entre CNES/Departamento
 * Primeiramente, valida se um mesmo departamento não foi selecionado mais de uma vez
 */
function salvarNovosVinculos() {

  var aVinculos                = [];
  var aDepartamentosVinculados = [];
  var lVinculoDuplicado        = false;

  oGridVinculos.getRows().each(function( oLinha, iLinha ) {

    var sId = 'departamento' + new Number( iLinha );

    if( !empty( $F(sId) ) ) {

      var oVinculo               = {};
          oVinculo.iCnes         = oLinha.aCells[0].content;
          oVinculo.iDepartamento = $F(sId);

      if( js_search_in_array( aDepartamentosVinculados, $F(sId) ) ) {

        var oMensagem               = {};
            oMensagem.sDepartamento = $(sId).options[ $(sId).selectedIndex ].getAttribute('label');

        alert( _M( MENSAGENS_SAU4_IMPORTACNES001 + 'departamento_duplicado', oMensagem ) );
        lVinculoDuplicado = true;
        return false;
      }

      aDepartamentosVinculados.push( $F(sId) );
      aVinculos.push( oVinculo );
    }
  });

  if( lVinculoDuplicado ) {
    return false;
  }

  if( aVinculos.length == 0 ) {

    alert( _M( MENSAGENS_SAU4_IMPORTACNES001 + 'nenhum_vinculo_selecionado' ) );
    return false;
  }

  var oParametros                 = {};
      oParametros.sExecuta        = 'processarNovosVinculos';
      oParametros.aVinculos       = aVinculos;
      oParametros.sNomeArquivo    = oFileUpload.file;
      oParametros.sCaminhoArquivo = oFileUpload.filePath;

  var oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoSalvarNovosVinculos );
      oAjaxRequest.setMessage( _M( MENSAGENS_SAU4_IMPORTACNES001 + 'salvando_novos_vinculos' ) );
      oAjaxRequest.execute();
}

/**
 * Retorno dos vínculos realizados
 * @param oRetorno
 * @param lErro
 */
function retornoSalvarNovosVinculos( oRetorno, lErro ) {

  if( lErro ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return false;
  }

  oWindowAuxVinculos.destroy();
  oWindowAuxVinculos = '';

  if( !lTemInconsistencia && oRetorno.lTemInconsistencia ) {
    lTemInconsistencia = true;
  }

  if( lTemInconsistencia ) {

    if( confirm( _M( MENSAGENS_SAU4_IMPORTACNES001 + 'imprimir_relatorio', oMensagemConfirm ) ) ) {
      imprimeRelatorio();
    }

    return false;
  }

  alert( oRetorno.sMensagem.urlDecode() );
  reload();
}

/**
 * Imprime o relatório com as inconsistências encontradas
 */
function imprimeRelatorio() {

  jan = window.open(
                     'sau4_importacaocnes002.php',
                     '',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                   );
  jan.moveTo( 0, 0 );
  reload();
}

function reload() {

  if ( $('file-upload-' + oFileUpload.config.id) ) {
    $('file-upload-' + oFileUpload.config.id).value = '';
  }
  document.form_upload_scnes.reset();
  $('btnProcessar').setAttribute("disabled", "disabled");
}

</script>