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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_liborcamento.php");

$oGet = db_utils::postMemory($_GET);
$iAnoUsu = db_getsession("DB_anousu");
$oRelatorio = AnexoSICONFIFactory::getAnexoSICONFI($iAnoUsu, $oGet->relatorio);
?>
<html>
<head>

  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">

  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>

  <link href="estilos.css" rel="stylesheet" type="text/css">

</head>

<body class="body-default">

<div class="container" style="width: 600px;">

  <form name="frmBalanceteVerificacao" method="post" action="">
    <fieldset>
      <legend>SICONFI - <?= $oRelatorio::NOME_RELATORIO; ?></legend>
      <div id="lista-instituicoes"></div>

      <label for="formatoArquivo" class="bold">Formato:</label>
      <?php
        $aOpcoes = array(
            AnexoSICONFI::TIPO_PDF => "PDF",
            AnexoSICONFI::TIPO_CSV => "CSV"
          );
        db_select("formatoArquivo", $aOpcoes, true, 1);
      ?>

    </fieldset>
    <input type="hidden" name="o116_periodo" id="o116_periodo" value="<?= AnexoSICONFI::CODIGO_PERIODO; ?>" />
    <input name="emite" id="emite" type="button" value="Emitir" onclick="js_emite();" />
  </form>

</div>

<script type="text/javascript">

  var oViewInstituicao,
      iCodigoRelatorio = window.location.search.match(/relatorio=(\d*)/)[1];

  document.observe('dom:loaded', function () {

    oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('lista-instituicoes'));
    oViewInstituicao.setWidth('600px');
    oViewInstituicao.show();
  });

  function js_emite() {

    var aInstituicoes = oViewInstituicao.getInstituicoesSelecionadas(true),
        sInstituicoes = '';

    /**
     * Validações
     */
    if (aInstituicoes.length == 0) {

      alert('Selecione ao menos uma Instituição.');
      return;
    }

    sInstituicoes = aInstituicoes.join(',');

    var oParametros = {
      exec : 'gerarRelatorio',
      iCodigoRelatorio : iCodigoRelatorio,
      sInstituicao : sInstituicoes,
      sFormato : $F("formatoArquivo")
    };

    new AjaxRequest("con2_relatoriosiconfi.RPC.php", oParametros, function (oRetorno, lErro) {

      if (oRetorno.mensagem) {
        alert(oRetorno.mensagem.urlDecode());
      }

      if (lErro) {
        return;
      }

      var oDownload = new DBDownload();
      oDownload.setHelpMessage('Clique no link abaixo para fazer download do relatório.');
      oDownload.addFile(oRetorno.caminho_relatorio, 'Relatório SICONFI - ' + oRetorno.nome_relatorio);
      oDownload.show();
    }).setMessage("Aguarde, gerando relatório.")
      .execute();
  }
</script>
</body>
</html>
