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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
  <head>
    <title>DBSeller Inform�tica Ltda - P�gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form id="formExportacao" name="form1" method="post" action="" class="form-container">
        <fieldset>
          <legend>Exporta��o de Arquivos H�rus</legend>
          <table>
            <tr>
              <td>
              <label for="inputCompetencia" class="bold"> Compet�ncia: </label>
              </td>
              <td>
                <input type="text" id="inputCompetencia" value="" disabled="disabled" class="readOnly" style="width: 52px;" />
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset class="separator">
                    <legend>Arquivos</legend>
                    <table>
                      <tr>
                        <td>
                          <input id="checkboxEntrada" type="checkbox" class="arquivo" name="arquivoEntrada" value="1" >
                        </td>
                        <td>
                          <label for="checkboxEntrada" class="field-size5"> Entrada </label>
                        </td>
                        <td class="field-size4" style="text-align: right">
                          <span id="situacaoEntrada" ></span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input id="checkboxSaida" type="checkbox" class="arquivo" name="arquivoSaida" value="2" >
                        </td>
                        <td>
                          <label for="checkboxSaida"> Sa�da </label>
                        </td>
                        <td class="field-size4" style="text-align: right">
                          <span id="situacaoSaida" ></span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input id="checkboxDispensacao" type="checkbox" class="arquivo" name="arquivoDispensacao" value="3" >
                        </td>
                        <td>
                          <label for="checkboxDispensacao"> Dispensa��o </label>
                        </td>
                        <td class="field-size4" style="text-align: right">
                          <span id="situacaoDispensacao" ></span>
                        </td>
                      </tr>
                    </table>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="button" id="btnConsultarDados" value="Consultar Dados" readonly='readOnly' disabled='disabled'>
        <input type="button" id="btnProcessar" value="Processar" >
      </form>
    </div>
  </body>
</html>

<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

const MENSAGEM_EXPORTACAOHORUS = "saude.farmacia.far4_exportacaohorus001.";

var sRpc                    = "far4_exportacaohorus.RPC.php",
    lConsultarDados         = false,
    aArquivosInconsistentes = [];
/**
 * Chama o RPC para pr� processar os dados dos 3 arquivos e retorna se houve inconsist�ncias
 */
$('btnConsultarDados').observe('click', function() {

  var oParametros           = {};
      oParametros.sExecucao = 'validarArquivos';

  var oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoConsultaDados );
      oAjaxRequest.setMessage( _M( MENSAGEM_EXPORTACAOHORUS + "consultando_dados" ) );
      oAjaxRequest.execute();
});

function retornoConsultaDados( oRetorno, lErro ) {

  var btnProcessar = $('btnProcessar');

  if ( lErro ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  if ( oRetorno.lTemInconsistenciasEntradaMedicamento
   ||  oRetorno.lTemInconsistenciasSaidaMedicamento
   ||  oRetorno.lTemInconsistenciasDispensacaoMedicamento ) {

    if ( confirm(_M( MENSAGEM_EXPORTACAOHORUS + "deseja_imprimir_relatorio" )) ) {

      var aCompetencia = $F('inputCompetencia').split("/");
      var sUrl         = "far2_conflitosintegracaohorus002.php";
      var sParametros  = "?iMes=" + aCompetencia[0] + "&iAno=" + aCompetencia[1];

      var oJanela = window.open(
                                 sUrl + sParametros,
                                 '',
                                 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                               );
      oJanela.moveTo(0,0);
    }
  }

  lConsultarDados = true;

  aArquivosInconsistentes['checkboxEntrada']     = {
    'lTemInconsistencia' : oRetorno.lTemInconsistenciasEntradaMedicamento,
    'sNomeArquivo'       : 'Entrada' 
  };
  aArquivosInconsistentes['checkboxSaida']       = { 
    'lTemInconsistencia' : oRetorno.lTemInconsistenciasSaidaMedicamento,
    'sNomeArquivo'       : 'Sa�da'
  };
  aArquivosInconsistentes['checkboxDispensacao'] = {
    'lTemInconsistencia' : oRetorno.lTemInconsistenciasDispensacaoMedicamento,
    'sNomeArquivo'       : 'Dispensa��o'
  };

  verificaCompetencia();
}

$('btnProcessar').observe('click', function() {

  var aArquivos               = [],
      aMensagemInconsistencia = [];

  $$('.arquivo').each( function ( oArquivo ) {

    if ( oArquivo.checked ) {

      aArquivos.push( oArquivo.value );

      if ( aArquivosInconsistentes[oArquivo.id].lTemInconsistencia ) {
        aMensagemInconsistencia.push( aArquivosInconsistentes[oArquivo.id].sNomeArquivo );
      } 
    }

  });

  if ( aArquivos.length == 0 ) {

    alert( _M( MENSAGEM_EXPORTACAOHORUS + 'selecione_arquivo') );
    return;
  }

  if ( aMensagemInconsistencia.length ) {
    
    var sMensagemIncosistencia  = "O(s) seguinte(s) arquivo(s) cont�m inconsist�ncias:\n\n";
        sMensagemIncosistencia += aMensagemInconsistencia.join( "\n" );
        sMensagemIncosistencia += "\n\nDeseja processar mesmo assim?";

    if ( !confirm( sMensagemIncosistencia ) ) {
      return false;
    }    

  }

  var oParametros           = {};
      oParametros.sExecucao = 'exportarArquivos';
      oParametros.aArquivos = aArquivos;

  var oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoExportarArquivos );
      oAjaxRequest.setMessage( _M( MENSAGEM_EXPORTACAOHORUS + "exportando_arquivos" ) );
      oAjaxRequest.execute();
});

function retornoExportarArquivos( oRetorno, lErro ) {

  alert( oRetorno.sMensagem.urlDecode() );

  if( lErro ) {
    return;
  }

  if ( oRetorno.aArquivos.length > 0) {

    var oDownload = new DBDownload();
    oDownload.addGroups( 'xml', 'Arquivos enviados para o H�rus');
    oRetorno.aArquivos.each( function (sArquivo) {

      oDownload.addFile(sArquivo, sArquivo.split('/')[1], 'xml');
    });
    oDownload.show();
  }

  verificaCompetencia();
}

/**
 * Verifica a compet�ncia a ser gerada e a situa��o dos arquivos
 */
function verificaCompetencia() {

  var oParametros           = {};
      oParametros.sExecucao = 'verificarCompetencia';

  var oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoVerificaCompetencia );
      oAjaxRequest.setMessage( _M( MENSAGEM_EXPORTACAOHORUS + "validando_unidade" )  );
      oAjaxRequest.execute();
}

function retornoVerificaCompetencia( oRetorno, lErro ) {

  if( lErro ) {

    setFormReadOnly( $('formExportacao'), true );
    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  var aMensagemRetorno = [];

  $('inputCompetencia').value = oRetorno.sCompetencia;

  btnConsultarDados.setAttribute('disabled', 'disabled');
  btnProcessar.setAttribute('disabled', 'disabled');

  /**
   * Percorre os arquivos e define a mensagem e a cor que deve ser impresso na tela
   * Tamb�m valida se � permitido o envio do arquivo e caso nenhum seja permitido mant�m o bot�o processar bloqueado.
   */
  oRetorno.aArquivos.forEach( function( oArquivo ) {

    var oSituacao         = null,
        oCheckboxArquivo  = null,
        btnConsultarDados = $('btnConsultarDados');

    switch ( oArquivo.iTipo ) {

      case 1 :

        oSituacao        = $('situacaoEntrada');
        oCheckboxArquivo = $('checkboxEntrada');
        break;

      case 2 :

        oSituacao        = $('situacaoSaida');
        oCheckboxArquivo = $('checkboxSaida');
        break;

      case 3 :

        oSituacao        = $('situacaoDispensacao');
        oCheckboxArquivo = $('checkboxDispensacao');
        break;
    }

    oSituacao.innerHTML   = oArquivo.sSituacao.urlDecode();
    oSituacao.style.color = oArquivo.sCorSituacao.urlDecode();

    oCheckboxArquivo.setAttribute( "readonly", "readOnly" );
    oCheckboxArquivo.setAttribute( "disabled", "disabled" );
    oCheckboxArquivo.checked = false;
    oCheckboxArquivo.checked = false;

    if ( !oArquivo.lPermiteEnvio.lDataEnvioValida )  {
      aMensagemRetorno.push(oArquivo.sTipoArquivo.urlDecode());
    }

    if ( oArquivo.lPermiteEnvio.lDataEnvioValida && oArquivo.lPermiteEnvio.lSituacaoArquivo) {

      oCheckboxArquivo.removeAttribute( "readonly" );
      oCheckboxArquivo.removeAttribute( "disabled" );
      btnConsultarDados.removeAttribute( "disabled" );
      btnConsultarDados.removeAttribute( "readonly" );

      if ( lConsultarDados ) {

        btnProcessar.removeAttribute('readonly');
        btnProcessar.removeAttribute('disabled');
      }
    }
  });

  if( aMensagemRetorno.length ) {

    var sMensagem  = "Arquivo(s) processado(s) hoje: \n\n" + aMensagemRetorno.join("\n");
    alert(sMensagem);
  }
}

verificaCompetencia();
</script>