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
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");

$oRotulo = new rotulo("classificacaocredores");
$oRotulo->label();
?>
<html>
<head>

  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>

  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">

  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollectionOrderer.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>

  <link rel="stylesheet" type="text/css" href="estilos.css">
  <link rel="stylesheet" type="text/css" href="estilos/grid.style.css">

  <style>
  table.table-body tr:hover {
    background-color: #eee;
  }
  </style>
</head>

<body class="body-default">
  <div id="ctnDados" class="container">

    <fieldset>
      <legend>Ordenar Lista de Classificacação de Credores</legend>

      <div style="width: 750px" id="container-listas"></div>
    </fieldset>

  </div>

  <p style="text-align: center;">
    <input type="button" id="btnSalvar" value="Salvar" />
  </p>

<?php db_menu() ?>

<script>
  (function(){

    var URL_RPC           = 'emp1_classificacaocredoresordenacao.RPC.php';
    var oBtnSalvar        = $('btnSalvar');
    var oCollectionListas = new Collection().setId("codigo");
    var oGridListas       = new DatagridCollection(oCollectionListas).configure({
      order  : false,
      height : 200
    });

    oGridListas.addColumn("ordem", {
      label : "Prioridade",
      align : "center",
      width : "10%"
    });
    oGridListas.addColumn("descricao", {
      label : "Descrição",
      align : "left",
      width : "75%"
    });
    new AjaxRequest(URL_RPC, { 'exec' : 'getDados' }, function(oRetorno, lErro) {

      for (var oItem of oRetorno.aLista) {

        oCollectionListas.add({
          'codigo'    : oItem.iCodigo,
          'descricao' : oItem.sDescricao,
          'ordem'     : oItem.iOrdem
        });
      }

      var oCollectionOrderer = new DatagridCollectionOrderer(oGridListas);
      oCollectionOrderer.setOrderAtribute('ordem');
      oCollectionOrderer.show($('container-listas'));
    }).execute();

    oBtnSalvar.observe('click', function() {

      var aItens      = oCollectionListas.build();
      var oParametros = {
        'aLista' : aItens,
        'exec'   : 'salvar'
      };
      new AjaxRequest(URL_RPC, oParametros, function(oRetorno, lErro) {

        alert(oRetorno.message.urlDecode());
        if (lErro) {
          return;
        }

      }).execute();
    });
  })();
</script>

</body>
</html>
