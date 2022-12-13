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
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/object.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBPesquisa.plugin.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
</head>
<body>

  <form name="form1" method="post" class="container">

    <fieldset style="width: 700px;">

      <table >
        <tr>
          <td class="bold">E:</td>
          <td>Imprime somente o exame selecionado.</td>
        </tr>
        <tr>
          <td class="bold">R:</td>
          <td>Imprime todos os exames da requisição.</td>
        </tr>
      </table>
      <br>
      <legend> Exames </legend>
      <div id="ctnGrid"></div>
    </fieldset>
  </form>

</body>
<script type="text/javascript">

var oGet = js_urlToObject();

var oCollection = new Collection();
var oGridExames = DatagridCollection.create(oCollection);

oGridExames.configure( {"height" : "150", "action":{"label": "Imprimir"}} );

oGridExames.addColumn('dtRequisicao', {label:'Data', width: '20%', align: 'center'}).transformer("date");
oGridExames.addColumn('sExame',       {label:' Exame ', width: '50%'}).transformer("decode");
oGridExames.addColumn('sSituacao',    {label:' Situação ', width: '15%'}).transformer("decode");

oGridExames.addAction('E', 'Imprime Exame', function(event, oItem) {
  imprimir(oItem.iRequisicao, oItem.iItem);
});

oGridExames.addAction('R', 'Imprime Requisicao', function(event, oItem) {
  imprimir(oItem.iRequisicao);
});


/**
 * Bloqueia os botões da linha em que situação do exame esta diferente de Conferido
 */
oGridExames.setEvent("afterRenderRows", function(){

  for ( var itemCollection  of this.collection.get() ) {

    if ( itemCollection.sSituacao !== "Conferido" && itemCollection.sSituacao !== "Entregue") {

      for ( var oBotoes of $$('input[collection_id="'+itemCollection.ID+'"]') ){
        oBotoes.disabled = true;
      }
    }
  }
}.bind(oGridExames));

oGridExames.show($('ctnGrid'));
oGridExames.getGrid().setPesquisa(1);
try{

  function carrega(oRetorno, lErro) {

    if (lErro) {

      alert ( oRetorno.sMessage.urlDecode() );
      return false;
    }

    if ( oRetorno.aExames.length > 0 ) {

      oCollection.setId('iItem');

      for( var oExame of oRetorno.aExames) {
        oCollection.add(oExame);
      }

      oGridExames.reload();
    }
  };


  (function(){

    oAjaxRequest = new AjaxRequest('sau4_cgs.RPC.php', {'sExecucao': 'buscaExames', 'iCgs': oGet.iCgs}, carrega);
    oAjaxRequest.setMessage('Buscando buscando exames realizados...');
    oAjaxRequest.execute();
  })();

  function imprimir(iRequisicao, iItem) {

    var sUrl  = 'lab4_emissaoresultnovo002.php';
        sUrl += '?requisicao='+iRequisicao;
        sUrl += '&iLabSetor=';

    var item = '';

    if (!!iItem) {
      item = iItem;
    }
    sUrl += '&requiitem=' + item;

    jan = window.open( sUrl, '', 'width=1000,height=600' );
  }
} catch(e) {
  console.error(e);
}

</script>
</html>