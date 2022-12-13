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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory($_POST);
$iAno = db_getsession("DB_anousu");

?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style type="text/css">
  .arquivo_importacao {
    width: 670px;
    background: url("imagens/tree/folderopen.gif") no-repeat 99% 51%;
    cursor: default;
    background-color: #FFFFFF;
    font-size: 11px;
    height: 25px;
    vertical-align: middle;
  }
</style>
<body class="body-default">
<div class="container">
  <form name="importar_infracoes" method="POST" action="" enctype="multipart/form-data">
    <fieldset>
      <legend><strong>Importação do Arquivo de Multas</strong>
      </legend>
      <span class="alert">Esta rotina irá efetuar a importação das infrações de trânsito e gerar os lançamentos de planilhas.</span>
      <fieldset>
        <legend>Arquivo</legend>
        <div>
          <input type="file" name="arquivo_importacao" id="arquivo_importacao" class="arquivo_importacao"/>
        </div>

      </fieldset>
    </fieldset>
    <input name="importar" type="button" id="importar" value="Importar e Processar Arquivo">
  </form>
</div>
<?php db_menu(); ?>

<script type="text/javascript">

    (function () {
        function importar() {
            if (document.importar_infracoes.arquivo_importacao.value == "") {

                alert("Arquivo de importação não informado.");
                return false;
            }

            if (confirm('Deseja importar o arquivo selecionado?')) {

                var oParametros = {
                    exec: 'importar',
                    sArquivos: document.importar_infracoes.arquivo_importacao.value
                }

                var oRequest = new AjaxRequest('inf4_importacaoinfracaotransito.RPC.php', oParametros, function (oRetorno, lErro) {


                    if (lErro && oRetorno.multasNaoProcessadas) {

                        if (!confirm(oRetorno.sMessage)) {
                            return;
                        }

                        new EmissaoRelatorio('inf2_importacaomultasnaocadastradas002.php',
                            {'arquivo' : oRetorno.arquivoMultas}) .open();
                        return;
                    }
                    alert(oRetorno.sMessage);

                });

                oRequest.addFileInput($('arquivo_importacao'));
                oRequest.setMessage("Aguarde, importando arquivo e processando infrações...");
                oRequest.execute();
            } else {
                return false;
            }
        }

        $("importar").observe("click", importar);
    })();
</script>
</body>
</html>