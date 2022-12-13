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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/verticalTab.widget.php"));

$oGet = db_utils::postMemory($_GET);

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  </head>
<body class="body-default">

  <fieldset>
    <legend>Empréstimos em aberto</legend>
    <div id='ctnAbertos'></div>

  </fieldset>
</body>
<script type="text/javascript">

var oGet = js_urlToObject();

var oEmprestimosCollection = new Collection().setId('emprestimoacervo');
var oGridEmprestimos = DatagridCollection.create(oEmprestimosCollection).configure({"order": false, "height": "110"});

oGridEmprestimos.addColumn("leitor",         {label : "Leitor", 'width':'60%'});
oGridEmprestimos.addColumn("data_retirada",  {label : "Data Retirada", 'width':'20%'}).transform('date');
oGridEmprestimos.addColumn("data_devolucao", {label : "Data Devolução", 'width':'20%'}).transform('date');

oGridEmprestimos.show( $('ctnAbertos') );

(function () {

  var oAjax = new AjaxRequest('bib4_acervo.RPC.php', {exec: 'emprestimosAbertos', iAcervo : oGet.iAcervo},
    function(oRetorno, lErro) {

      if ( lErro ) {

        alert(oRetorno.sMessage);
        return;
      }
      oEmprestimosCollection.add(oRetorno.aEmprestimos);
      oGridEmprestimos.reload();
    }
  );

  oAjax.setMessage('Buscando exemplares em aberto...');
  oAjax.execute();


})();

</script>