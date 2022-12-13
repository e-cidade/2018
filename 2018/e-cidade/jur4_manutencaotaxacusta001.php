<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <?php
            db_app::load("scripts.js, strings.js, numbers.js, prototype.js, AjaxRequest.js, datagrid.widget.js");
            db_app::load("widgets/Collection.widget.js, widgets/DatagridCollection.widget.js");
        ?>
        <link type="text/css" rel="stylesheet" href="estilos.css">
    </head>
    <body bgcolor=#CCCCCC>
        <form class="container" name="form1" method="post">
            <fieldset>
                <legend>Manutenção de Taxas/Custas - Isenção em Inicial do Foro</legend>
                <table class="form-container">
                    <tr>
                		<td style="width: 83px;">
                            <?php db_ancora("Inicial do Foro:", "pesquisaInicial(true);", 1); ?>
                		</td>
                		<td>
                            <?php db_input("iInicial", 20, 1, true, "text", 1, "onchange='pesquisaInicial(false);'", null, null, "width:83px;"); ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <div class="subcontainer" id="divTaxasAdministrativas" style="display: none;">
                <fieldset>
                    <legend>Administrativas</legend>
                    <div id="divGridTaxasAdministrativas" style="width: 330px;"></div>
                </fieldset>
            </div>
            <input id="btnProcessar" type="button" value="Processar" onclick="processar();">
            <br/>
            <br/>
            <span style="display: none;color: red" id="spanInicialRecibo" class="bold">Inicial do Foro já possui recibo emitido.</span>
        </form>
        <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
    </body>
</html>
<script type="text/javascript">
    document.form1.btnProcessar.disabled = true;
var lAlteracao = false;

var RPC = "jur4_manutencaotaxacusta.RPC.php";

var oDivTaxasAdministrativas     = $("divTaxasAdministrativas");
var oDivGridTaxasAdministrativas = $("divGridTaxasAdministrativas");
var oSpanInicialRecibo           = $("spanInicialRecibo");

var oCollection = new Collection().setId("id");

var oGridTaxasAdministrativas = DatagridCollection.create(oCollection).configure({order: false, align: "center", height : "auto"});

oGridTaxasAdministrativas.addColumn("sDescricao", {label: "Descrição", align: "left",   width: "80%"});
oGridTaxasAdministrativas.addColumn("sCheckbox",  {label: " ",         align: "center", width: "20%"});

function pesquisaInicial(lMostra) {
  
    if (lMostra == true) {
        js_OpenJanelaIframe("top.corpo", "db_iframe_inicial", "func_inicial.php?funcao_js=parent.mostraInicialJanela|0", "Pesquisa", true);
    } else {
        js_OpenJanelaIframe("top.corpo", "db_iframe_inicial", "func_inicial.php?pesquisa_chave="+document.form1.iInicial.value+"&funcao_js=parent.mostraInicial", "Pesquisa", false);
    }
}

function mostraInicialJanela(iInicial) {
  
    document.form1.iInicial.value = iInicial;
    db_iframe_inicial.hide();
    mostraInicial(iInicial, false);
}

function mostraInicial(iInicial, lErro) {
  
    lAlteracao = false;

    oDivTaxasAdministrativas.hide();
    oSpanInicialRecibo.hide();
    document.form1.btnProcessar.disabled = true;

    if (lErro == true) {
        document.form1.iInicial.value = "";
        document.form1.iInicial.focus();

        alert("Código de Inicial do Foro inválido!");

    } else {
        
        document.form1.iInicial.value = iInicial;
        var aParametros = {
            "sExecucao": "getDadosInicialTaxa",
            "iInicial": iInicial
        };

        var ajaxCallBack = function(oRetorno, lErro) {

            if (oRetorno.oDados.lDebitoPago == true) {

                document.form1.iInicial.value = "";
                document.form1.iInicial.focus();

                alert("Inicial paga. Não é possível lançar isenção de Taxas/Custas.");

                return;
            }

            if (oRetorno.oDados.aTaxas.length > 0) {
                
                oDivTaxasAdministrativas.show();

                var sHtmlChecked = "";

                oRetorno.oDados.aTaxas.each(function (oTaxa) {

                    oTaxa.id = oTaxa.iCodigoTaxa;

                    sHtmlChecked = "";

                    if (oTaxa.lChecked) {

                        lAlteracao = true;
                        sHtmlChecked = "checked";
                    }

                    oTaxa.sCheckbox = "<input id='taxa"+oTaxa.iCodigoTaxa+"' type='checkbox' value='"+oTaxa.iCodigoTaxa+"' "+sHtmlChecked+">";

                    oCollection.add(oTaxa);
                });
            }

            if (oRetorno.oDados.lReciboEmitido == true) {
                oSpanInicialRecibo.show();
            }

            oGridTaxasAdministrativas.grid.aHeaders = new Array();
            oGridTaxasAdministrativas.reload();
            oGridTaxasAdministrativas.show(oDivGridTaxasAdministrativas);
            document.form1.btnProcessar.disabled = false;
        };

        var oAjaxRequest = new AjaxRequest(RPC, aParametros, ajaxCallBack);

        oAjaxRequest.setMessage("Aguarde...").execute();
    }
}

function processar() {

    if (document.form1.iInicial.value === '') {
        return;
    }

    var aTaxas = [];
    var lSelecionada = false;

    oCollection.itens.each(function (oTaxa) {

        var oCheckbox = $("taxa"+oTaxa.iCodigoTaxa);

        if (oCheckbox.checked) {
            lSelecionada = true;
        }

        aTaxas.push({
            iTaxa: oCheckbox.value,
            lIsencao: oCheckbox.checked
        });
    });
 
    var aParametros = {
        "sExecucao": "processaInicialTaxaIsencao",
        "iInicial": document.form1.iInicial.value,
        "aTaxas": aTaxas
    };

    if (aTaxas.length === 0) {
        alert("Não há Taxas/Custas configuradas para o tipo de cobrança!");
        return;

    } else if (lAlteracao == false && lSelecionada == false) {

        alert("Selecione Taxas/Custas a serem isentadas!");
        return;
    }

    if (confirm('Deseja realizar a alteração das Taxas/Custas?')) {

        var ajaxCallBack = function(oRetorno, lErro) {
            
            if (lErro) {

                alert(oRetorno.mensagem.urlDecode());
                
            } else {

                oDivTaxasAdministrativas.hide();
                oSpanInicialRecibo.hide();
                document.form1.iInicial.value = "";
                document.form1.iInicial.focus();

                alert("Manutenção de Taxas/Custas da Inicial realizada com sucesso!");
            }
        };

        var oAjaxRequest = new AjaxRequest(RPC, aParametros, ajaxCallBack);

        oAjaxRequest.setMessage("Aguarde...").execute();
    }
}

</script>
