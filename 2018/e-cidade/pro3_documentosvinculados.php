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

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
      db_app::load("datagrid.widget.js, grid.style.css, widgets/DBDownload.widget.js");
    ?>
  </head>
  <body style="background-color: #ccc; margin-top: 10px">
  <center>
    <fieldset style="width: 98%;">
      <legend><b>Documentos Vinculados</b></legend>
      <div id="ctnGridDocumentosVinculados">
      </div>
    </fieldset>
  </body>
  </center>
</html>

<script>

  var sUrlRPC = "prot4_processodocumento.RPC.php";
  var oGet    = js_urlToObject();


  var oGridDocumento = new DBGrid('oGridDocumento');
  oGridDocumento.nameInstance = "oGridDocumento";
  oGridDocumento.setCellWidth(new Array('10%', '10%', '20%', '50%', '10%'));
  oGridDocumento.setCellAlign(new Array('center', 'center', 'center', 'left', 'center'));
  oGridDocumento.setHeader(new Array('Código', 'Data', 'Usuário', 'Descrição', 'Ação'));
  oGridDocumento.show($('ctnGridDocumentosVinculados'));

  /**
   * Localiza no servidor os arquivos vinculados ao processo
   */
  function js_carregarDocumentos() {

    js_divCarregando(_M("patrimonial.protocolo.prot4_processodocumento.carregando_documentos"), "msgBox");

    var oParam = {"exec":"carregarDocumentos", "iCodigoProcesso":oGet.codigo_processo};

    new Ajax.Request(sUrlRPC,
                     {method: 'post',
                      parameters: 'json='+Object.toJSON(oParam),
                      onComplete: preencherGridComDocumentos
                     });
  }

  /**
   * Preenche a grid com os documentos vinculados
   */
  function preencherGridComDocumentos(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    oGridDocumento.clearAll(true);

    var iTotalRegistros = oRetorno.aDocumentosVinculados.length;

    for (var iDocumento = 0; iDocumento < iTotalRegistros; iDocumento++) {

      var oDocumento = oRetorno.aDocumentosVinculados[iDocumento];

      var aLinha = new Array();
      aLinha[0]  = oDocumento.iCodigoDocumento;
      aLinha[1]  = oDocumento.sData;
      aLinha[2]  = oDocumento.sNomeUsuario ?  oDocumento.sNomeUsuario : '' ;
      aLinha[3]  = oDocumento.sDescricaoDocumento.urlDecode();
      aLinha[4]  = "<input type='button' id='btnDownload"+oDocumento.iCodigoDocumento+"' value='Download' onclick='downloadDocumento("+oDocumento.iCodigoDocumento+");' />";
      oGridDocumento.addRow(aLinha);
    }
    oGridDocumento.renderRows();
  }

  /**
   * Salva o arquivo do servidor no micro do usuário
   * @param integer
   */
  function downloadDocumento(iCodigoDocumento) {

    js_divCarregando(_M("patrimonial.protocolo.prot4_processodocumento.salvando_em_disco"), "msgBox");
    var oParam = {"exec":"download", "iCodigoDocumento":iCodigoDocumento};

    new Ajax.Request(sUrlRPC,
                     {method: 'post',
                      parameters: 'json='+Object.toJSON(oParam),
                      onComplete: mostrarDocumento
                     });
  }

  function mostrarDocumento(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    var sCaminhoDownloadArquivo = oRetorno.sCaminhoDownloadArquivo.urlDecode();

    window.open("db_download.php?arquivo="+sCaminhoDownloadArquivo);
    /*
    var oDBDownload = new DBDownload();
    oDBDownload.addFile(oRetorno.sCaminhoDownloadArquivo.urlDecode(), oRetorno.sTituloArquivo.urlDecode());
    oDBDownload.show();
    */
  }

  js_carregarDocumentos();
</script>