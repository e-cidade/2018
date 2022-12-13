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
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post">
        <fieldset>
          <legend>Confirmação de Envio dos Arquivos do LicitaCon</legend>
          <table>
            <tr>
              <td>
                <label class="bold" for="datageracao">Data de Geração:</label>
              </td>
              <td>
                <?php db_inputdata("datageracao", '', '', '', true, 'text', 1); ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="button" id="confirmar" value="Confirmar"></input>
      </form>
      <script type="text/javascript">
        (function() {

          const RPC = "lic4_licitaconencerramento.RPC.php";
          const MESSAGE = "patrimonial.licitacao.lic4_licitaconencerramento.";

          /**
           * Salva a data de garação
           */
          $("confirmar").on("click", function() {

            var sData = $("datageracao").value;

            if (sData == '') {
              return alert( _M(MESSAGE + "campo_obrigatorio", { sCampo : "Data de Geração" }) );
            }

            new AjaxRequest(RPC, { exec : "confirmarGeracao", sData : sData }, function(oResposta, lErro) {

              if (lErro) {
                return alert( oResposta.message.urlDecode() );
              }

              alert(_M(MESSAGE + "data_confirmada"));
            }).setMessage("Salvando data de geração.").execute()

          });

        })()
      </script>
    </div>
    <?php db_menu(); ?>
  </body>
</html>