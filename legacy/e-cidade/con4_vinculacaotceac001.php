<?php
/**
 *
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
  require_once(modification("libs/db_usuariosonline.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification('model/contabilidade/arquivos/tce/AC/ImportacaoArquivoTCEAC.model.php'));

  $oGet = db_utils::postMemory($_GET);

  $sLegend = "";
  switch ($oGet->tipo) {

    case ImportacaoArquivoTCEAC::TIPO_ARQUIVO_RECURSO:
      $sLegend = "Vinculação de Recursos do MP Acre";
      break;

    case ImportacaoArquivoTCEAC::TIPO_ARQUIVO_PLANOCONTA:
      $sLegend = "Vinculação do Plano de Contas do MP Acre";
      break;

    case ImportacaoArquivoTCEAC::TIPO_ARQUIVO_DOCUMENTOS:
      $sLegend = "Vinculação de Documentos do MP Acre";
      break;
  }
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
  db_app::load('widgets/DBDownload.widget.js, AjaxRequest.js');
?>
</head>
<body style="background-color: #CCCCCC; margin-top:30px">

  <div class="container">
    <form enctype="multipart/form-data" name="vinculaArquivoTCEAC" id="vinculaArquivoTCEAC">
      <fieldset style="width: 600px;">
        <legend class="bold"><?php echo $sLegend; ?></legend>
        <table style="width: 100%;">
          <tr>
            <td style="width: 70px;"><b>Arquivo:</b></td>
            <td>
              <input type="file" id="arquivo" name="arquivo" style="height: 20px;"/>
            </td>
          </tr>
        </table>
      </fieldset>
      <p>
        <input type="button" id="btnImportar" value="Importar" />
        <input type="button" id="btnExportar" value="Exportar" disabled/>
      </p>
    </form>
  </div>

  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>

<script>

  var lArquivoImportado = false;
  var iTipoArquivo = <?php echo $oGet->tipo; ?>;

  $('btnImportar').observe('click', function() {

    if ($F('arquivo') == '') {

      alert("Campo Arquivo é de preenchimento obrigatório.");
      return false;
    }

    var sMensagem = "Confirma a importação do arquivo?";
    if (lArquivoImportado) {
      sMensagem = "Existe um arquivo importado, ao importar um novo os dados do anterior serão excluídos. Deseja prosseguir?";
    }

    if (!confirm(sMensagem)) {
      return false;
    }

    var oParametros = { sExecucao: "importarArquivo" , iTipo : iTipoArquivo};

    new AjaxRequest(
      'con4_tceAC.RPC.php',
      oParametros,
      function(oRetorno, lErro) {

        alert(oRetorno.sMessage.urlDecode());
        possuiArquivoImportado();
        $('arquivo').value = '';
      }
    ).addFileInput($('arquivo'))
     .setMessage('Aguarde, efetuando o upload do arquivo...')
     .execute();
  });

  $('btnExportar').observe('click',
    function() {

      var oParam = {sExecucao:'downloadArquivo', iTipo : iTipoArquivo};
      new Ajax.Request(
        'con4_tceAC.RPC.php',
        {
          method: 'post',
          parameters: 'json='+Object.toJSON(oParam),
          onComplete: function (oAjax) {

            var oRetorno = eval("("+oAjax.responseText+")");
            if (oRetorno.iStatus == 2) {
              alert(oRetorno.sMessage.urlDecode());
              return false;
            }

            var oDownload = new DBDownload();
            oDownload.addGroups("csv", "Vinculação de arquivos TCE/AC");
            oDownload.addFile(oRetorno.sNomeArquivo.urlDecode(), oRetorno.sNome.urlDecode(), "csv");
            oDownload.show();

          }
        }
      );
    }
  );

  function possuiArquivoImportado() {

    var oParam = {sExecucao:'possuiArquivoImportado', iTipo : iTipoArquivo};
    new Ajax.Request(
      'con4_tceAC.RPC.php',
      {
        method: 'post',
        parameters: 'json='+Object.toJSON(oParam),
        onComplete: function (oAjax) {

          var oBotaoExportar = $('btnExportar');
          var oRetorno = eval("("+oAjax.responseText+")");
          lArquivoImportado = oRetorno.possuiArquivoImportado;
          oBotaoExportar.disabled = !oRetorno.possuiArquivoImportado;
        }
      }
    );
  }
  possuiArquivoImportado();
</script>