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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/verticalTab.widget.php"));
?>
<html>
<head>
 <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <meta http-equiv="Expires" CONTENT="0">
   <link href="estilos.css" rel="stylesheet" type="text/css">
   <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
   <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
</head>
<body class="body-default">

  <div class="container">

    <!-- LISTA DE DOCUMENTOS QUE PODEM SER EMITIDOS -->
    <fieldset>
      <legend>Emissão de Documentos</legend>

      <table class="form-container">

        <tr>
          <td>
            <label for="documentos">Documento:</label>
          </td>
          <td>
            <select id="documentos">
              <option value="formFAA">FAA</option>
              <option value="formDeclaracao">DECLARAÇÃO DE COMPARECIMENTO</option>
            </select>
          </td>
        </tr>

      </table>

    </fieldset>


    <!-- FORMULÁRIO DE EMISSÃO DA FAA -->
    <form id="formFAA" class="form-documento">
      <input type="button" value="Emitir" onclick="emitirDocumento(1);" />
    </form>


    <!-- FORMULÁRIO DE EMISSÃO DA DECLARAÇÃO DE COMPARECIMENTO -->
    <form id="formDeclaracao" class="form-documento">

      <fieldset>
        <legend>Informações Complementares</legend>

        <table class="form-container">

          <tr>
            <td class="field-size2">
              <label for="sHoraInicial">Hora Inicial:</label>
            </td>
            <td>
              <input id="sHoraInicial" name="sHoraInicial" class="field-size2"/>
            </td>
          </tr>

          <tr>
            <td class="field-size2">
              <label for="sHoraFinal">Hora Final:</label>
            </td>
            <td>
              <input id="sHoraFinal" name="sHoraFinal" class="field-size2"/>
            </td>
          </tr>

          <tr>
            <td colspan="2">
              <fieldset class="separator">
                <legend>Observação</legend>
                <textarea id="sObservacao" name="sObservacao"></textarea>
              </fieldset>
            </td>
          </tr>

        </table>
      </fieldset>

      <input type="button" value="Emitir" onclick="emitirDocumento(2);" />

    </form>

    <input id="iProntuario" name="iProntuario" value="<?=$iProntuario?>" type="hidden" />

  </div>

</body>

<script>
  const MENSAGENS_SAU3_EMISSAODOCUMENTOS = 'saude.ambulatorial.sau2_declaracaocomprovante.';

  var oInputHoraInicial = new DBInputHora( $('sHoraInicial') );
  var oInputHoraFinal   = new DBInputHora( $('sHoraFinal') );

  /**
   * Responsável por apresentar/ocultar os formulários de acordo com a opção selecionada
   */
  function controlaApresentacaoFormularios() {

    for( var oForm of $$('form.form-documento') ) {
      oForm.setStyle({ display: 'none' });
    }

    $($F('documentos')).setStyle({ 'display': '' });
  }

  /**
   * Validações referentes aos campos de hora
   */
  function validaHora() {

    if(
           !empty( $F('sHoraInicial') ) &&  empty( $F('sHoraFinal') )
        ||  empty( $F('sHoraInicial') ) && !empty( $F('sHoraFinal') )
      ) {

      alert( _M( MENSAGENS_SAU3_EMISSAODOCUMENTOS + 'informe_hora' ) );
      return false;
    }

    if( $F('sHoraInicial') > $F('sHoraFinal') ) {

      alert( _M( MENSAGENS_SAU3_EMISSAODOCUMENTOS + 'hora_inicial_maior_final' ) );
      return false;
    }

    return true;
  }

  /**
   * Emite o documento de acordo com a opção passada por parâmetro
   * @param {integer} iDocumento
   *        1 - FAA
   *        2 - DECLARAÇÃO DE COMPARECIMENTO
   */
  function emitirDocumento(iDocumento) {

    var sUrl        = '';
    var oParametros = null;

    switch (iDocumento) {

      case 1:

        sUrl        = 'sau2_emitirfaa002.php';
        oParametros = { chave_sd29_i_prontuario: $F('iProntuario') };

        break;

      case 2:

        if( !validaHora() ) {
          return;
        }

        sUrl        = 'sau2_declaracaocomprovante002.php';
        oParametros = {
          iProntuario  : $F('iProntuario'),
          sHoraInicial : $F('sHoraInicial'),
          sHoraFinal   : $F('sHoraFinal'),
          sObservacao  : $F('sObservacao')
        };

        break;
    }

    var oEmissaoRelatorio = new EmissaoRelatorio( sUrl, oParametros );
        oEmissaoRelatorio.open();
  }

  $('documentos').observe('change', controlaApresentacaoFormularios);

  controlaApresentacaoFormularios();
</script>