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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

?>
<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link type="text/css" rel="stylesheet" href="estilos.css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body class="body-default">
  <div class="container">
    <form>
      <fieldset>
        <legend>Importação dos Arquivos</legend>
        <table class="form-container">
          <tr>
            <td>
              <label for="arquivoLotes">Arquivo de Lotes:</label>
            </td>
            <td>
              <input id="arquivoLotes" name="arquivoLotes" type="file">
            </td>
          </tr>
          <tr>
            <td>
              <label for="arquivoEdificacoes">Arquivo de Edificações:</label>
            </td>
            <td>
              <input id="arquivoEdificacoes" name="arquivoEdificacoes" type="file">
            </td>
          </tr>
          <tr>
            <td>
              <label for="arquivoTestadas">Arquivo de Testadas:</label>
            </td>
            <td>
              <input id="arquivoTestadas" name="arquivoTestadas" type="file">
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="btnEnviar" value="Enviar" />
      <input type="button" id="btnLimpar" value="Limpar" />
    </form>
  </div>
  <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
</body>
<script>

//Guarda as informações que devem ser processados
var aArquivos = [];
//Arquivo de mensagens
var sArquivoMensagens = "tributario.cadastro.cad4_importacaorecadastramento.";

/**
 * Envia os dados para que sejam processados no RPC
 */
$('btnEnviar').onclick = function() {


  if ( !validaArquivosInformados() ) {
    return;
  }

  if (!confirm( _M(sArquivoMensagens + "confirmar_importacao") )) {
    return false;
  }

  var oParametros = {
    exec: 'importar',
    aArquivos: aArquivos,
    sDataArquivo: aArquivos[0].sData
  };

  var oRequest = new AjaxRequest('cad4_recadastramento.RPC.php', oParametros, function (oRetorno, lErro) {

    alert(oRetorno.sMessage);
    location.href = 'cad4_importacaorecadastramento.php';
  });

  oRequest.addFileInput($('arquivoLotes'));
  oRequest.addFileInput($('arquivoEdificacoes'));
  oRequest.setMessage("Aguarde, importando arquivos...");
  oRequest.execute();
}

/**
 * Verifica se o arquivo foi informado e os adiciona a estrutura que será enviada para o RPC
 * @param string sArquivo
 */
function adicionaArquivo( sArquivo, iTipoArquivo ) {

  if ( !empty(sArquivo) ) {

    var oArquivo          = {};
    oArquivo.sNome        = sArquivo;
    oArquivo.iTipoArquivo = iTipoArquivo;
    oArquivo.sData        = getDataArquivo(sArquivo);
    aArquivos.push(oArquivo);
  }
}

/**
 * Valida se os arquivos informados são válidos
 * @return boolean
 */
function validaArquivosInformados() {

  var aDatas    = [];
  var sMensagemErro = '';
  aArquivos     = [];

  adicionaArquivo($F('arquivoLotes'), 1);
  adicionaArquivo($F('arquivoEdificacoes'), 2);
  adicionaArquivo($F('arquivoTestadas'), 3);

  if ( empty(aArquivos) ) {

    alert( _M(sArquivoMensagens + "selecione_arquivo") );
    return false;
  }

  aArquivos.each( function( oArquivo ) {


    if (oArquivo.iTipoArquivo == 1 && oArquivo.sNome.search("lotes") == -1 ) {

      sMensagemErro  = _M(sArquivoMensagens + "arquivo_lotes");
      throw $break;
    }

    if (oArquivo.iTipoArquivo == 2 && oArquivo.sNome.search("edificacoes") == -1 ) {

      sMensagemErro = _M(sArquivoMensagens + "arquivo_edificacoes");
      throw $break;
    }

    if (oArquivo.iTipoArquivo == 3 && oArquivo.sNome.search("testada") == -1 ) {

      sMensagemErro = _M(sArquivoMensagens + "arquivo_testadas");
      throw $break;
    }

    if (oArquivo.sData == null) {

      sMensagemErro = _M(sArquivoMensagens + "informe_data", {sErro: oArquivo.sNome});
      throw $break;
    }

    if ( !aDatas.in_array(oArquivo.sData) ){
      aDatas.push(oArquivo.sData);
    }
  });

  if ( !empty(sMensagemErro) ) {

    alert( sMensagemErro );
    return false;
  }

  if ( aDatas.length != 1 ) {

    alert( _M(sArquivoMensagens + "datas_diferentes") );
    return false;
  }

  return true;
}

/**
 * Verifica se há uma sequência de 8 números no nome do arquivo e os considera uma data
 * @param type sArquivo
 * @return string|null
 */
function getDataArquivo( sArquivo ) {

  if ( empty(sArquivo) ) {
    return null;
  }

  var sRegex       = /(\d{8}).*/;
  var aDataArquivo = sRegex.exec(sArquivo);
  var sDataArquivo = null;

  if ( !empty( aDataArquivo ) ) {

    var iAno = aDataArquivo[1].substring(0, 4);
    var iMes = aDataArquivo[1].substring(4, 6);
    var iDia = aDataArquivo[1].substring(6, 8);
    sDataArquivo = iAno + "-" + iMes + "-" + iDia;
  }

  return sDataArquivo;
}

$('btnLimpar').addEventListener('click', function() {
  location.href = 'cad4_importacaorecadastramento.php';
});

</script>