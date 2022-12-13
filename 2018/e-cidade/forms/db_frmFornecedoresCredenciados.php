<div class="container">
  <form name="form1">
    <fieldset>
      <legend> Cadastro de Fornecedores Credenciados</legend>
      <table class="form-container">
        <tr>
          <td>
            <label for="l20_codigo"><a id="ancoraLicitacao" href="#">Licitação:</a></label>
          </td>
          <td>
            <?php
              db_input("l20_codigo", 10, $Il20_codigo, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="z01_numcgm"><a id="ancoraFornecedor" href="#">Fornecedor:</a></label>
          </td>
          <td >

            <?php
              db_input("z01_numcgm", 10, $Iz01_numcgm, true, "text", 1);
              db_input("z01_nome",   40, $Iz01_nome, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr id="notificacao" style="display: none">
          <td colspan="2" style="text-align: left; background-color: #fcf8e3; border: 1px solid #fcc888; padding: 5px">
            O CGM informado como Fornecedor da Licitação não está cadastrado como <br>Fornecedor no módulo "Compras".
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" name="salvarFornecedor" id="btnSalvarFornecedor" disabled="disabled" value="Salvar">
  </form>

  <fieldset>
    <legend>Fornecedores</legend>
    <div id='cntGridFornecedores'> </div>
  </fieldset>

</div>

<script type="text/javascript">

var oLookUpLicitacao = new DBLookUp( $('ancoraLicitacao'), $('l20_codigo'),$('l20_codigo'), {
    sArquivo: 'func_liclicita.php',
    sLabel: 'Pesquisa de Licitação',
    sObjetoLookUp: 'db_iframe_liclicita',
    aParametrosAdicionais : ['lCredenciamento=true']
  });

oLookUpLicitacao.setCallBack('onClick', buscarDadosLicitacao);

var oLookUpFornecedor = new DBLookUp( $('ancoraFornecedor'), $('z01_numcgm'), $('z01_nome'), {
    sArquivo: 'func_nome.php',
    sLabel: 'Pesquisa de Fornecedor',
    sObjetoLookUp: 'func_nome'
  });

oLookUpFornecedor.setCallBack('onChange', verificaFornecedor);
oLookUpFornecedor.setCallBack('onClick', verificaFornecedor);

/**
 * Verifica se o cgm selecionado é um fornecedor cadastrado
 */
function verificaFornecedor() {

  $("notificacao").hide();
  var iCgm = $F("z01_numcgm");

  if (iCgm.trim() == '') {

    $('btnSalvarFornecedor').setAttribute('disabled', 'disabled');
    return false;
  }

  $('btnSalvarFornecedor').removeAttribute('disabled');
  var oParam = {
    exec : "verificaFornecedor",
    iCgm : iCgm
  }

  new AjaxRequest("com1_fornecedor.RPC.php", oParam, function(oRetorno, lErro) {

    if (lErro) {
      return alert(oRetorno.message.urlDecode());
    }

    if (!oRetorno.lFornecedor) {
      $("notificacao").show();
    }
  }).setMessage( _M( sFonteMsg + "verficar_fornecedor" ) ).execute();
}

var oGridFornecedor = new DatagridCollection(oDados.oCollectionFornecedor).configure({
  order  : false,
  height : 120
});

oGridFornecedor.addColumn("iCgm", {
  label : "CGM",
  align : "left",
  width : "10%"
});

oGridFornecedor.addColumn("sNome", {
  label : "Nome",
  align : "left",
  width : "70%"
});

oGridFornecedor.addAction("E", null, function(oEvento, oFornecedor) {

  var oParametros = {
    exec        : 'removerFornecedor',
    iLicitacao  : oDados.iLicitacao,
    oFornecedor : oFornecedor.build()
  };

  new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

    alert( oRetorno.sMessage );
    if ( lErro ) {
      return false;
    }

    removerFornecedorItem(oFornecedor.iCgm);
    oDados.oCollectionFornecedor.remove(oFornecedor.iCgm);
    oGridFornecedor.reload();
  }).setMessage( _M(sFonteMsg + "excluindo_fornecedor") ).execute();
});


oGridFornecedor.show($("cntGridFornecedores"));

/**
 * Salva o fornecedor ao oçamento da licitação
 */
$('btnSalvarFornecedor').addEventListener('click', function() {

  var oParametros = {
    exec       : 'adicionarFornecedor',
    iOrcamento : oDados.iOrcamento,
    iLicitacao : $F('l20_codigo'),
    iCgm       : $F('z01_numcgm'),
    sNome      : $F('z01_nome')
  };

  new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

    if ( lErro ) {

      alert( oRetorno.sMessage );
      return false;
    }

    oDados.oCollectionFornecedor.add(oRetorno.oFornecedor);
    oGridFornecedor.reload();

    // adiciona o fornecedor na aba itens
    atualizaFornecedoresItens(oRetorno.oFornecedor.sNome, oRetorno.oFornecedor.iCgm);
    oDados.iOrcamento = oRetorno.iOrcamento;

    $('z01_numcgm').value = '';
    $('z01_nome').value   = '';
    $("notificacao").hide();

  }).setMessage( _M(sFonteMsg + "salvando_fornecedor") ).execute();
  $('btnSalvarFornecedor').setAttribute('disabled', 'disabled');
});


/**
 * Função inicial
 */
(function() {

  $('ancoraLicitacao').dispatchEvent(new Event('click'));
})();



</script>
