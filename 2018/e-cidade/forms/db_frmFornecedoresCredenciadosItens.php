<div class="container">
    <form name="form2">
        <fieldset>
            <legend>Selecione o fornecedor</legend>
            <table class="form-container">
                <tr>
                    <td><label for="cboFornecedores">Fornecedor:</label></td>
                    <td>
                        <select id='cboFornecedores'>
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend>Informe os itens do fornecedor</legend>
            <div style= "width: 600px" id="ctnGridItensFornecedor"></div>
        </fieldset>
        <input type="button" name="salvarItens" id="btnSalvarItensFornecedor" value="Salvar">
    </form>
</div>
<script type="text/javascript">

    var oGridItensFornecedor = new DatagridCollection(oDados.oCollectionItens).configure({
        height : 120,
        order : false
    });

    oGridItensFornecedor.getGrid().setCheckbox(4);
    oGridItensFornecedor.addColumn('sItem', {
        label : 'Item',
        align : 'left',
        width : '50%'
    });

    oGridItensFornecedor.addColumn('iQuantidadeItem', {
        label : 'Quantidade',
        align : 'left',
        width : '14%'
    });

    oGridItensFornecedor.addColumn('nVlrUnitario', {
        label : 'Valor Unitário',
        align : 'left',
        width : '18%'
    }).transform('dinheiro');

    oGridItensFornecedor.addColumn('nVlrTotal', {
        label : 'Valor Total',
        align : 'left',
        width : '18%'
    }).transform('dinheiro');

    oGridItensFornecedor.addColumn('iLicLicitem', {
        label : 'codigo',
        width : ''
    });

    oGridItensFornecedor.hideColumns([5]);
    oGridItensFornecedor.show($('ctnGridItensFornecedor'));

    $('cboFornecedores').addEventListener('change', function() {

        oGridItensFornecedor.clearSelectedItens();
        oGridItensFornecedor.reload();

        if (empty($F('cboFornecedores'))) {
            return;
        }

        var oFornecedor = oDados.oCollectionFornecedor.get($F('cboFornecedores'));
  
        if (oFornecedor.aItens.length == 0) {
            return;
        }

        for (var oItem of oFornecedor.aItens) {
            oGridItensFornecedor.addSelectedItens(oItem.codigo);
        }

        oGridItensFornecedor.reload();
    });


    $('btnSalvarItensFornecedor').addEventListener('click', function () {

        if (empty($F('cboFornecedores'))) {
            alert(_M(sFonteMsg + 'informe_fornecedor'));
            return;
        }

        var oParametros = {
            exec: 'vincularItemFornecedor',
            iCgm: $F('cboFornecedores'),
            iLicitacao: oDados.iLicitacao,
            iFornecedor: oDados.oCollectionFornecedor.get($F('cboFornecedores')).iFornecedor,
            aItens: []
        };

        var aItensSelecionadosNaGrid = oGridItensFornecedor.getGrid().getSelection();

        for (var aItemGrid of aItensSelecionadosNaGrid) {
            var oItem = oDados.oCollectionItens.get(aItemGrid[0]);
            oParametros.aItens.push(oItem.build());
        }

        if (oParametros.aItens.length == 0) {
            alert(_M(sFonteMsg + 'selecione_um_item'));
            return;
        }

        new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {
            alert(oRetorno.sMessage);

            if (lErro) {
                return;
            }

            var oFornecedor = oDados.oCollectionFornecedor.get($F('cboFornecedores'));
            oFornecedor.aItens = oRetorno.aItens;
        }).setMessage(_M(sFonteMsg + 'salvar_itens')).execute();
    });
</script>