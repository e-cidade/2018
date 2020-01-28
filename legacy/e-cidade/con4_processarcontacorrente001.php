<?php
/**
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oData = new DBDate(date('Y-m-d', db_getsession('DB_datausu')));
$iUltimoDia = DBDate::getQuantidadeDiasMes($oData->getMes(), $oData->getAno());

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">

  <fieldset style="width: 500px;">
    <legend class="bold">Processar Conta Corrente</legend>
    <table style="width: 100%">
      <tr>
        <td class="bold">
          Conta Corrente:
        </td>
        <td>
          <?php
          $codigo_contacorrente = 1;
          db_input('codigo_contacorrente', 10, 1, true, 'text', 3);
          $descricao_contacorrente = strtoupper("Disponibilidade Financeira");
          db_input('descricao_contacorrente', 40, 1, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td class="bold">
          <label for="ancordaDocumento">
            <a id="ancordaDocumento">Documento:</a>
          </label>
        </td>
        <td>
          <?php
          $Sc53_coddoc = "Documento";
          db_input('c53_coddoc', 10, 1, true, 'text', 1);
          db_input('c53_descr', 40, 1, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td class="bold" nowrap="nowrap">
          <label for="data_inicial">
            Data do Lançamento:
          </label>
        </td>
        <td>
          <?php
          db_inputdata('data_inicial', '01', $oData->getMes(), $oData->getAno(), true, 'text', 1);
          echo " <label for='data_final'><b>até</b></label> ";
          db_inputdata('data_final', $iUltimoDia, $oData->getMes(), $oData->getAno(), true, 'text', 1);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <p>
    <input type="button" id="btnProcessar" value="Processar" onclick="processar()" />
  </p>

</div>

<?php db_menu(); ?>
</body>
</html>

<script type="text/javascript">

  const CAMINHO_MENSAGEM = 'financeiro.contabilidade.con4_processarcontacorrente001.';

  var oCampo = {
    codigo_documento : $('c53_coddoc'),
    descricao_documento : $('c53_descr'),
    data_inicial : $('data_inicial'),
    data_final : $('data_final'),
    codigo_corrente : $('codigo_contacorrente'),
    descricao_contacorrente : $('descricao_contacorrente')
  };

  oCampo.codigo_corrente.style.width = '80px';
  oCampo.codigo_documento.style.width = '80px';
  oCampo.data_inicial.style.width = '80px';
  oCampo.data_final.style.width = '80px';
  oCampo.descricao_documento.style.width = '70%';
   oCampo.descricao_contacorrente.style.width = '70%';

  new DBLookUp($('ancordaDocumento'), oCampo.codigo_documento, oCampo.descricao_documento, {
    "sArquivo" : "func_conhistdoc.php",
    "sObjetoLookUp" : "db_iframe_conhistdoc",
    "sLabel" : "Pesquisar Documentos Contábeis"
  });

  function processar() {

    if (oCampo.codigo_documento.value.trim() == "") {
      return alert(_M(CAMINHO_MENSAGEM + 'documento_nao_informado'));
    }

    if (oCampo.data_inicial.value.trim() == "") {
      oCampo.data_inicial.focus();
      return alert(_M(CAMINHO_MENSAGEM + 'data_inicial_invalida'));
    }

    if (oCampo.data_final.value.trim() == "") {
      oCampo.data_final.focus();
      return alert(_M(CAMINHO_MENSAGEM + 'data_final_invalida'));
    }

    if (js_comparadata(oCampo.data_inicial.value, oCampo.data_final.value, '>')) {
      return alert(_M(CAMINHO_MENSAGEM + 'data_invalida'));
    }

    new AjaxRequest(
      'con4_contacorrente.RPC.php',
      {
        exec : 'processarContasCorrentes',
        documento : oCampo.codigo_documento.value,
        data_inicial : oCampo.data_inicial.value,
        data_final : oCampo.data_final.value
      },
      function (oRetorno, lErro) {
        alert(oRetorno.message.urlDecode());
      }
    ).setMessage('Aguarde, processando contas...').execute();
  }
</script>
