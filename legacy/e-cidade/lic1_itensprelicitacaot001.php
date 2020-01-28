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
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
</head>
<body class='body-default'>

  <form name="form1" class="container">
    <fieldset>
      <legend>Itens da Licitação</legend>

      <table class="form-container" >
        <tr>
          <td>
            <label for="pc01_codmater"><a href="#" id="ancoraMaterial">Material:</a></label>
          </td>
          <td colspan="3">
            <input type="text" name="pc01_codmater" id='pc01_codmater' class="field-size2 "   />
            <input type="text" name="pc01_descrmater" id='pc01_descrmater' class="field-size8 readonly" disabled="disabled"  />
          </td>
        </tr>
        <tr>
          <td><label for="unidade">Unidade:</label></td>
          <td id="ctnUnidadesMaterial" style="width: 250px">
            <select id="unidade" style="width: 100%">
              <option value="">Selecione</option>
            </select>

          </td>
          <td><label for="quantidade">Quantidade:</label></td>
          <td>
            <input type="text" name="quantidade" id='quantidade' class="field-size2" />
          </td>
        </tr>
      </table>

      <fieldset class="separator">
        <legend><label for="resumo">Resumo</label></legend>
        <textarea id="resumo" cols="60" rows="2"></textarea>
      </fieldset>

      <input type="button" name="adicionar" id="btnAdicionar" value="Adicionar" />

      <fieldset class="separator" style="width: 500px;">
        <legend>Itens Adicionados</legend>
        <div id='gridItens' ></div>
      </fieldset>
    </fieldset>
    <input type="button" name="salvar" id="btnSalvar" value="Salvar" disabled="disabled" />
    <input type="hidden" name="tipo_julgamento" id='tipoJulgamento' value='' />
    <input type="hidden" name="licitacao"       id='codLicitacao'   value='' />
    <input type="hidden" name="processo"        id='codProcesso'    value='' />
    <input type="hidden" name="solicitacao"     id='codSolicitacao' value='' />
  </form>

</body>
<script type="text/javascript">

var FonteMsg = 'patrimonial.licitacao.lic1_itensprelicitacaot001.';

var oLookUpMaterial = new DBLookUp( $('ancoraMaterial'), $('pc01_codmater'), $('pc01_descrmater'),  {
  sArquivo              : 'func_pcmatersolicita.php',
  sLabel                : 'Pesquisa de Material',
  sObjetoLookUp         : 'db_iframe_pcmater'
});


new AjaxRequest('com4_materialsolicitacao.RPC.php', {exec: 'getUnidades'}, function (oRetorno, lErro) {

  if (lErro) {
    return alert(oRetorno.message);
  }

  var oComboUnidade = $('unidade');
  oRetorno.unidades.each(
    function(oUnidade) {

      var oOption       = document.createElement('option');
      oOption.value     = oUnidade.codigo;
      oOption.innerHTML = oUnidade.descricao.urlDecode();
      oComboUnidade.appendChild(oOption);
    }
  );
}).execute();

$('btnAdicionar').addEventListener('click', function() {

  if ( empty($F('pc01_codmater'))) {

    alert ( _M(FonteMsg + 'informe_material') );
    return;
  }

  if ( empty($F('unidade'))) {

    alert ( _M(FonteMsg + 'informe_unidade') );
    return;
  }
  if ( empty($F('quantidade')) ) {

    alert ( _M(FonteMsg + 'informe_quantidade') );
    return;
  }

  oCollection.add({
    iId              : Math.floor((Math.random() * 100) + 1) + '#' + $F('pc01_codmater'),
    iMaterial        : $F('pc01_codmater'),
    sMaterial        : $F('pc01_descrmater'),
    iQuantidade      : $F('quantidade'),
    sResumo          : $F('resumo'),
    iUnidade         : $F('unidade'),
    iQtdUnidade      : 1,
    iItemProcesso    : '',
    iItemSolicitacao : '',
    iItemLicitacao   : ''
  });

  oGridItens.reload();
  $('pc01_codmater').value   = '';
  $('pc01_descrmater').value = '';
  $('unidade').value         = '';
  $('quantidade').value      = '';
  $('resumo').value          = '';

  $('btnSalvar').removeAttribute('disabled');
});


var oCollection = new Collection().setId("iId");
var oGridItens  = new DatagridCollection(oCollection).configure({
  order  : false,
  height : 120
});

oGridItens.addColumn("sMaterial", {
  label : "Descrição",
  align : "left",
  width : "75%"
}).transformCallback = function( sTexto, oDados ) {
  return "<label title ='"+ oDados.sResumo +"' >" + sTexto +" </label>";
};

oGridItens.addColumn("iQuantidade", {
  label : "Quantidade",
  align : "left",
  width : "10%"
});

oGridItens.addAction('E', 'Excluir', function(oEvento, oRegistro) {

  if ( empty(oRegistro.iItemProcesso) && empty(oRegistro.iItemSolicitacao) && empty(oRegistro.iItemLicitacao) ) {
    oCollection.remove(oRegistro.iMaterial);
    return;
  }

  var oParametros = {
    exec            : 'removerItem',
    iTipoJulgamento : $F('tipoJulgamento'),
    iLicitacao      : $F('codLicitacao'),
    iProcessoCompra : $F('codProcesso'),
    iSolicitacao    : $F('codSolicitacao')
  };

  /**
   * @todo implementar
   *
   */
  new AjaxRequest('lic4_licitacaoconcessao.RPC.php', mergeObject(oParametros, oRegistro.build()), function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if (lErro) {
      return;
    }
    buscaItens();
  }).setMessage( _M(FonteMsg + 'removendo_item_licitacao') ).execute();

  oGridItens.reload();

});

oGridItens.show($('gridItens'));

$('btnSalvar').addEventListener('click', function() {

  var aParametros = {
    exec            : 'salvarItens',
    iTipoJulgamento : $F('tipoJulgamento'),
    iLicitacao      : $F('codLicitacao'),
    iProcessoCompra : $F('codProcesso'),
    iSolicitacao    : $F('codSolicitacao'),
    aItens          : oCollection.build()
  };

  /**
   * @todo validar os dados salvos
   */

  if(aParametros.aItens.length == 0){

    alert( _M(FonteMsg + 'nenhum_item_adicionado') );
    return false;
  }
  new AjaxRequest('lic4_licitacaoconcessao.RPC.php', aParametros, function (oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return;
    }
    buscaItens();
  }).setMessage( _M(FonteMsg + 'salvando_itens') ).execute();
});



/**
 * Função inicial, busca os itens da licitação
 */
(function(){

  var oUrl =js_urlToObject();

  $('codLicitacao').value   = oUrl.licitacao;
  $('tipoJulgamento').value = oUrl.tipojulg;
  buscaItens();
})();


function buscaItens() {

  new AjaxRequest('lic4_licitacaoconcessao.RPC.php', {exec: 'getItens', iLicitacao : $F('codLicitacao')},

    function(oRetorno, lErro) {

      oGridItens.clear();
      if ( lErro ) {
        alert(oRetorno.sMessage);
        return
      }

      $('codProcesso').value    = oRetorno.iProcesso;
      $('codSolicitacao').value = oRetorno.iSolicitacao;

      for ( var oItem of oRetorno.aItens ){
        oCollection.add(oItem);
      }
      oGridItens.reload();
    }
  ).setMessage( _M(FonteMsg + 'busca_itens_licitacao') ).execute();
}

</script>
</html>
