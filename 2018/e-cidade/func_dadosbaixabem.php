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

$oGet = db_utils::postMemory($_GET);

if (empty($oGet->t52_bem)) {
  die("Código o bem não informado.");
}

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
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type='text/css'>
    .valores {background-color:#FFFFFF}
  </style>
</head>
<body>
<div class="container">

  <fieldset style="width: 600px;">
    <legend class="bold">Dados da Baixa</legend>
    <table>
      <tr>
        <td><b><label for="tdData">Data:</label></b></td>
        <td class="valores" id="tdData" style="width: 100%"></td>
      </tr>
      <tr>
        <td><b><label for="tdMotivo">Motivo:</label></b></td>
        <td class="valores" id="tdMotivo"></td>
      </tr>
    </table>
    <fieldset>
      <legend><b><label for="textObservacoes">Observações</label></b></legend>
      <textarea id="textObservacoes" style="width: 100%; height: 100px" readonly></textarea>
    </fieldset>
  </fieldset>

</div>
</body>
</html>

<script type="text/javascript">

  var oGet = js_urlToObject();

  new AjaxRequest(
    'pat1_bensnovo.RPC.php',
    {exec : 'getDadosBaixaBem', codigo_bem : oGet.t52_bem},
    function (oRetorno, lErro) {

      if (lErro) {
        return alert(oRetorno.message.urlDecode());
      }


      $('tdData').innerHTML   =  oRetorno.oDadosBaixa.databaixa;
      $('tdMotivo').innerHTML = oRetorno.oDadosBaixa.motivo + " - " +oRetorno.oDadosBaixa.descricao_motivo.urlDecode();
      $('textObservacoes').innerHTML = oRetorno.oDadosBaixa.observacao.urlDecode();
    }
  ).setMessage('Aguarde, buscando informações...').execute();

</script>