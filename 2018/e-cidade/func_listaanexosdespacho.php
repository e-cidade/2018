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

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>

</head>
<body class='body-default'>
  <div class='container'>
    <fieldset>
        <legend> Documentos do Despacho</legend>
        <div id='ctnDocumentosDespacho' style="width: 100%;"></div>
    </fieldset>
  </div>
</body>
</html>
<script>

    var iCodigoDespacho = '<?php echo $oGet->codprocandamint;?>';
    var sRPC = 'prot4_processoprotocolo004.RPC.php';


    var oGridDocumentosDespacho = new DBGrid('gridDocumentosDespacho');
    oGridDocumentosDespacho.nameInstance = 'oGridDocumentosDespacho';
    oGridDocumentosDespacho.setCellWidth(['100px', '500px', '500px', '100px']);
    oGridDocumentosDespacho.setCellAlign(['center', 'left', 'left', 'center']);
    oGridDocumentosDespacho.setHeader(['Código', 'Descrição', 'Documento', 'Ação']);
    oGridDocumentosDespacho.setHeight(250);
    oGridDocumentosDespacho.show($('ctnDocumentosDespacho'));
    oGridDocumentosDespacho.clearAll(true);

    carregaDocumentosDespacho();

    function carregaDocumentosDespacho() {

        var oParam = {
          exec : 'carregarDocumentosDespacho',
          iCodigoDespacho : iCodigoDespacho
        };

        new AjaxRequest(
          sRPC,
          oParam,
          function (oRetorno, lErro) {
            if (lErro) {
              alert(oRetorno.sMensagem.urlDecode());
              return false;
            }

            oRetorno.aDocumentosDespacho.each(
                function(oLinha, indice) {
                    var aLinha = [
                      oLinha.codigoArquivo,
                      oLinha.descricao.urlDecode(),
                      oLinha.nomeDocumento,
                      "<input type='button' id='btnDownload"+oLinha.codigoArquivo+"' value='Download' onclick='downloadDocumento("+oLinha.codigoArquivo+");' />"
                    ];
                    oGridDocumentosDespacho.addRow(aLinha);
                }
            );

            oGridDocumentosDespacho.renderRows();
          }
        ).setMessage('Aguarde, carregando documentos...').execute();
    }

    function downloadDocumento( iCodigoArquivo){
        var oParam = {
          exec : "download",
          iCodigoDocumento: iCodigoArquivo
        }
        new AjaxRequest(
            'prot4_processodocumento.RPC.php',
            oParam,
            function(oRetorno) {
                if(oRetorno.iStatus == 2) {
                    alert(oRetorno.sMensagem.urlDecode());
                }
                var sCaminhoDownloadArquivo = oRetorno.sCaminhoDownloadArquivo.urlDecode();
                window.open("db_download.php?arquivo="+sCaminhoDownloadArquivo, "_self");
            }
        ).execute();
    }
</script>
