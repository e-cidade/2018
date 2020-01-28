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
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="container">
      <fieldset>
        <legend>
          <b>Relatório de Pagamentos Duplicados</b>
        </legend>
        <table>
          <tr>
            <td>
              <label for="dtPagamentoInicial"  class="bold">
                Data de Pagamento:
              </label>
            </td>
            <td>
              <input type="text" id="dtPagamentoInicial">
            </td>
            <td>
              <label for="dtPagamentoFinal" class="bold">
                Até
              </label>
            </td>
            <td>
              <input type="text" id="dtPagamentoFinal">
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="btnEmitir" value="Emitir">
    </div>
  </body>
</html>
<script>

  var sArquivoMensagem = "financeiro.caixa.inf2_pagamentoduplicidade001.";
  var oInputDataInicial = new DBInputDate($('dtPagamentoInicial'));
  var oInputDataFinal   = new DBInputDate($('dtPagamentoFinal'));

  $('btnEmitir').observe('click', function(){


    var dtPagamentoInicial = oInputDataInicial.inputElement.value;
    var dtPagamentoFinal   = oInputDataFinal.inputElement.value;

    if (empty(dtPagamentoInicial)) {

      alert( _M(sArquivoMensagem + "informe_data_inicial") );
      return false;
    }

    if (empty(dtPagamentoFinal)) {

      alert( _M(sArquivoMensagem + "informe_data_final") );
      return false;
    }

    if (oInputDataInicial.getValue().getTime() > oInputDataFinal.getValue().getTime()) {

      alert( _M(sArquivoMensagem + "data_inicial_menor_final") );
      return false;
    }

    var iAnoInicial = dtPagamentoInicial.split("/")[2];
    var iAnoFinal   = dtPagamentoFinal.split("/")[2];

    if ( iAnoInicial != iAnoFinal ) {

      alert( _M(sArquivoMensagem + "ano_igual") );
      return false;
    }

    var url = 'inf2_pagamentoduplicidade002.php?dtPagamentoInicial='+dtPagamentoInicial+'&dtPagamentoFinal='+dtPagamentoFinal;
    window.open(url, '', 'location=0');
  })
</script>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));