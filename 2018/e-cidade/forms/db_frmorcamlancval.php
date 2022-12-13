<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

$clpcorcam->rotulo->label();
$clpcorcamforne->rotulo->label();
$clpcorcamval->rotulo->label();
$clrotulo = new rotulocampo;
?>
<script>
    function js_data_prazo() {
        var frm = document.form1;

        var dia_atual = new Number(<?=date("d", db_getsession("DB_datausu"));?>);
        var mes_atual = new Number(<?=date("m", db_getsession("DB_datausu"));?>);
        var ano_atual = new Number(<?=date("Y", db_getsession("DB_datausu"));?>);

        var dia_prazo = new Number(frm.pc21_prazoent_dia.value);
        var mes_prazo = new Number(frm.pc21_prazoent_mes.value);
        var ano_prazo = new Number(frm.pc21_prazoent_ano.value);

        var dia = 0;

        if (dia_prazo.toString().length < 2) {
            dia_prazo = "0" + dia_prazo;
        }

        if (mes_prazo.toString().length < 2) {
            mes_prazo = "0" + mes_prazo;
        }

        if (dia_atual.toString().length < 2) {
            dia_atual = "0" + dia_atual;
        }

        if (mes_atual.toString().length < 2) {
            mes_atual = "0" + mes_atual;
        }

        var data_atual = new String(ano_atual + "-" + mes_atual + "-" + dia_atual);
        var data_informada = new String(ano_prazo + "-" + mes_prazo + "-" + dia_prazo);

        if (dia_prazo > 0 && mes_prazo > 0 && ano_prazo > 0) {
            dia = js_diferenca_datas(data_atual, data_informada, "d");

            frm.dias_prazo.value = dia;
        }
    }

    function js_data_validade() {
        var frm = document.form1;

        var dia_atual = new Number(<?=date("d", db_getsession("DB_datausu"));?>);
        var mes_atual = new Number(<?=date("m", db_getsession("DB_datausu"));?>);
        var ano_atual = new Number(<?=date("Y", db_getsession("DB_datausu"));?>);

        var dia_validade = new Number(frm.pc21_validadorc_dia.value);
        var mes_validade = new Number(frm.pc21_validadorc_mes.value);
        var ano_validade = new Number(frm.pc21_validadorc_ano.value);

        var dia = 0;

        if (dia_validade.toString().length < 2) {
            dia_validade = "0" + dia_validade;
        }

        if (mes_validade.toString().length < 2) {
            mes_validade = "0" + mes_validade;
        }

        if (dia_atual.toString().length < 2) {
            dia_atual = "0" + dia_atual;
        }

        if (mes_atual.toString().length < 2) {
            mes_atual = "0" + mes_atual;
        }

        var data_atual = new String(ano_atual + "-" + mes_atual + "-" + dia_atual);
        var data_informada = new String(ano_validade + "-" + mes_validade + "-" + dia_validade);

        if (dia_validade > 0 && mes_validade > 0 && ano_validade > 0) {
            dia = js_diferenca_datas(data_atual, data_informada, "d");

            frm.dias_validade.value = dia;
        }
    }


    function js_dias_prazo() {
        var frm = document.form1;

        var dia_prazo = new Number(frm.dias_prazo.value);
        var dia_atual = new Number(<?=date("d", db_getsession("DB_datausu"));?>);
        var mes_atual = new Number(<?=date("m", db_getsession("DB_datausu"));?>);
        var ano_atual = new Number(<?=date("Y", db_getsession("DB_datausu"));?>);

        var pc21_prazoent_dia = dia_atual + 1;
        var pc21_prazoent_mes = mes_atual;
        var pc21_prazoent_ano = ano_atual;

        if (pc21_prazoent_dia.toString().length < 2) {
            pc21_prazoent_dia = "0" + pc21_prazoent_dia;
        }

        if (pc21_prazoent_mes.toString().length < 2) {
            pc21_prazoent_mes = "0" + pc21_prazoent_mes;
        }

        var data = new Date(pc21_prazoent_ano, pc21_prazoent_mes, pc21_prazoent_dia);

        data.setDate(data.getDate() + dia_prazo);

        pc21_prazoent_dia = data.getDate();
        var mes = data.getMonth();

        if (mes == 0) {
            mes += 1;
        }

        pc21_prazoent_mes = mes;
        pc21_prazoent_ano = data.getFullYear();

        if (pc21_prazoent_dia.toString().length < 2) {
            pc21_prazoent_dia = "0" + pc21_prazoent_dia;
        }

        if (pc21_prazoent_mes.toString().length < 2) {
            pc21_prazoent_mes = "0" + pc21_prazoent_mes;
        }

        frm.pc21_prazoent_dia.value = pc21_prazoent_dia;
        frm.pc21_prazoent_mes.value = pc21_prazoent_mes;
        frm.pc21_prazoent_ano.value = pc21_prazoent_ano;

        if (js_VerDaTa("pc21_prazoent_dia", pc21_prazoent_dia, pc21_prazoent_mes, pc21_prazoent_ano) == false ||
            js_VerDaTa("pc21_prazoent_mes", pc21_prazoent_dia, pc21_prazoent_mes, pc21_prazoent_ano) == false ||
            js_VerDaTa("pc21_prazoent_ano", pc21_prazoent_dia, pc21_prazoent_mes, pc21_prazoent_ano) == false) {

            frm.pc21_prazoent_dia.value = dia_atual;
            frm.pc21_prazoent_mes.value = mes_atual;
            frm.pc21_prazoent_ano.value = ano_atual;
            frm.pc21_prazoent_dia.select();
            frm.pc21_prazoent_dia.focus();
            return false;
        }
    }

    function js_dias_validade() {
        var frm = document.form1;

        var dia_validade = new Number(frm.dias_validade.value);
        var dia_atual = new Number(<?=date("d", db_getsession("DB_datausu"));?>);
        var mes_atual = new Number(<?=date("m", db_getsession("DB_datausu"));?>);
        var ano_atual = new Number(<?=date("Y", db_getsession("DB_datausu"));?>);

        var pc21_validadorc_dia = dia_atual + 1;
        var pc21_validadorc_mes = mes_atual;
        var pc21_validadorc_ano = ano_atual;

        if (pc21_validadorc_dia == 0) {
            pc21_validadorc_dia = dia_atual;
            pc21_validadorc_mes = mes_atual;
            pc21_validadorc_ano = ano_atual;
        } else {
            var data_atual = js_retornadata(pc21_validadorc_dia, pc21_validadorc_mes, pc21_validadorc_ano);
            var dia_atual = new Number(data_atual.getDate());
            var mes_atual = new Number(data_atual.getMonth());
            var ano_atual = new Number(data_atual.getFullYear());
        }

        if (pc21_validadorc_dia.toString().length < 2) {
            pc21_validadorc_dia = "0" + pc21_validadorc_dia;
        }

        if (pc21_validadorc_mes.toString().length < 2) {
            pc21_validadorc_mes = "0" + pc21_validadorc_mes;
        }

        var data = new Date(pc21_validadorc_ano, pc21_validadorc_mes, pc21_validadorc_dia);

        data.setDate(data.getDate() + dia_validade);

        pc21_validadorc_dia = data.getDate();
        var mes = data.getMonth();

        if (mes == 0) {
            mes += 1;
        }

        pc21_validadorc_mes = mes;
        pc21_validadorc_ano = data.getFullYear();

        if (pc21_validadorc_dia.toString().length < 2) {
            pc21_validadorc_dia = "0" + pc21_validadorc_dia;
        }

        if (pc21_validadorc_mes.toString().length < 2) {
            pc21_validadorc_mes = "0" + pc21_validadorc_mes;
        }

        frm.pc21_validadorc_dia.value = pc21_validadorc_dia;
        frm.pc21_validadorc_mes.value = pc21_validadorc_mes;
        frm.pc21_validadorc_ano.value = pc21_validadorc_ano;

        if (js_VerDaTa("pc21_validadorc_dia", pc21_validadorc_dia, pc21_validadorc_mes, pc21_validadorc_ano) == false ||
            js_VerDaTa("pc21_validadorc_mes", pc21_validadorc_dia, pc21_validadorc_mes, pc21_validadorc_ano) == false ||
            js_VerDaTa("pc21_validadorc_ano", pc21_validadorc_dia, pc21_validadorc_mes, pc21_validadorc_ano) == false) {

            frm.pc21_validadorc_dia.value = dia_atual;
            frm.pc21_validadorc_mes.value = mes_atual;
            frm.pc21_validadorc_ano.value = ano_atual;
            frm.pc21_validadorc_dia.select();
            frm.pc21_validadorc_dia.focus();
            return false;
        }
    }

    function js_dalocation(valor) {
        location.href = 'com1_orcamlancval001.php?pc20_codorc=<?=$pc20_codorc?>&sol=<?=$sol?>&pc21_orcamforne=' + valor;
        document.form1.submit();
    }

    function js_buscarcod() {
        retorno = "";
        erro0 = 0;
        erro1 = 0;
        obj = elementos.document.form1;

        aColDatas = js_getElementbyClass(elementos.document.form1, 'valida');
        for (var ii = 0; ii < aColDatas.length; ii++) {
            if (aColDatas[ii].value == '') {
                alert('Preencha o(s) campo(s) validade mínima que estão marcados com *.');
                return false;
            }
        }


        for (i = 0; i < obj.elements.length; i++) {
            if (obj.elements[i].name.substr(0, 6) == "valor_") {
                var valor = new Number(obj.elements[i].value);
                retorno += obj.elements[i].name + "_" + valor;
                erro0++;
            }
        }


        document.form1.valores.value = retorno;

        retorno = "";
        for (i = 0; i < obj.elements.length; i++) {
            if (obj.elements[i].name.indexOf("bdi_") != -1) {
                var valor = new Number(obj.elements[i].value);
                retorno += obj.elements[i].name + "_" + valor;
                erro0++;
            }
        }
        document.form1.valoresbdi.value = retorno;

        retorno = "";
        for (i = 0; i < obj.elements.length; i++) {
            if (obj.elements[i].name.indexOf("encargossociais_") != -1) {
                var valor = new Number(obj.elements[i].value);
                retorno += obj.elements[i].name + "_" + valor;
                erro0++;
            }
        }
        document.form1.valoresencargos.value = retorno;

        retorno = "";
        for (i = 0; i < obj.elements.length; i++) {
            if (obj.elements[i].name.substr(0, 6) == "vlrun_") {
                var valor = new Number(obj.elements[i].value);
                retorno += obj.elements[i].name + "_" + valor;
                erro0++;
            }
        }
        document.form1.valoresun.value = retorno;

        retorno = "";
        for (i = 0; i < obj.elements.length; i++) {
            if (obj.elements[i].name.substr(0, 5) == "qtde_") {
                var valor = new Number(obj.elements[i].value);
                retorno += obj.elements[i].name + "_" + valor;
                erro1++;
            }
        }
        document.form1.qtdades.value = retorno;

        retorno = "";
        ifen = "";
        div = "#";
        for (i = 0; i < obj.elements.length; i++) {

            if (obj.elements[i].name.substr(0, 14) == "pc23_validmin_") {
                valor = obj.elements[i].value;
                str = obj.elements[i].name.length - 3;
                objDt = obj.elements[i].name.substring(str);
                arr_info = obj.elements[i].name.split("_");
                if ((objDt == 'dia' || objDt == 'mes' || objDt == 'ano')) {
                    retorno += div + ifen + obj.elements[i].value;
                    ifen = "-";
                    if (arr_info[3] == "ano") {
                        ifen = "";
                        div = "#";
                    } else {
                        div = "";
                    }
                }

            }

        }

        //  alert(retorno);
        //  return false;

        document.form1.dataval.value = retorno;


        retorno = "";
        for (i = 0; i < obj.elements.length; i++) {

            if (obj.elements[i].name.substr(0, 4) == "obs_") {

                valor = obj.elements[i].value;

                if (valor != "") {

                    for (ii = 0; ii < valor.length; ii++) {

                        if (valor.substr(ii, 1) == " ") {

                            valor = valor.replace(" ", "yw00000wy");
                        }
                    }
                    retorno += obj.elements[i].name + "_" + valor;
                } else {
                    retorno += 'obs_';
                }
                erro1++;
            }
        }
        document.form1.obss.value = retorno;

        retorno = "";
        for (i = 0; i < obj.elements.length; i++) {

            if (obj.elements[i].name.substr(0, 13) == "taxaestimada_") {

                valor = obj.elements[i].value;

                if (valor != "") {
                    retorno += obj.elements[i].name + "_" + valor;
                } else {
                    retorno += 'taxaestimada_';
                }
                erro1++;
            }
        }
        document.form1.taxasestimadas.value = retorno;

        return true;
    }


    function js_taxa_estimada_global() {
        processarTaxaGlobalItens();
    }

    function processarTaxaGlobalItens(validaCarregar = false) {
        var frm = document.form1;
        var taxaEstimada = parseFloat(frm.pc21_taxaestimadaglobal.value);
        var elementosTaxaEstimada = elementos.document.getElementsByClassName('taxa-extimada');

        if (isNaN(taxaEstimada) && !validaCarregar) {
            for (var i = 0; i < elementosTaxaEstimada.length; i++) {
                elementosTaxaEstimada[i].readOnly = false;
                elementosTaxaEstimada[i].style = "";
                elementosTaxaEstimada[i].value = "";
            }
        } else if (!isNaN(taxaEstimada)) {
            for (var i = 0; i < elementosTaxaEstimada.length; i++) {
                elementosTaxaEstimada[i].readOnly = true;
                elementosTaxaEstimada[i].style = "background-color:#DEB887;";
                elementosTaxaEstimada[i].value = taxaEstimada;
            }
        }
    }

</script>
<style type="text/css">
    .table {
        width: 100%;
    }

    tr:not(.full-width) {
        width: 20%;
    }
</style>
<form name="form1" method="post">
    <fieldset>
        <legend>Dados do Orçamento</legend>
        <table style="width: 30%;">
            <tbody>
            <tr>
                <td class='dados-orcamento' title="<?php echo isset($Tpc20_codorc) ? $Tpc20_codorc : ''; ?>">
                    <label for="pc20_codorc">
                        <?php echo isset($Lpc20_codorc) ? $Lpc20_codorc : ''; ?>
                    </label>
                </td>
                <td>
                    <?php
                    db_input('pc20_codorc', 8, $Ipc20_codorc, true, 'text', 3, "");
                    db_input('valores', 40, 0, true, 'hidden', 3, "");
                    db_input('valoresun', 40, 0, true, 'hidden', 3, "");
                    db_input('qtdades', 8, 0, true, 'hidden', 3, "");
                    db_input('obss', 8, 0, true, 'hidden', 3, "");
                    db_input('sol', 6, 0, true, 'hidden', 3, "");
                    db_input('dataval', 40, 0, true, 'hidden', 3, "");
                    db_input('valoresbdi', 40, 0, true, 'hidden', 3, "");
                    db_input('valoresencargos', 40, 0, true, 'hidden', 3, "");
                    db_input('taxasestimadas', 40, 0, true, 'hidden', 3, "");
                    ?>
                </td>
            </tr>
            <?php
            $voltar = false;

            $result_forne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null, "pc21_orcamforne,z01_nome",
                "pc21_orcamforne", "pc21_codorc=$pc20_codorc"));
            $numrows_forne = $clpcorcamforne->numrows;

            if ($numrows_forne > 0) {
            if (!isset($pc21_orcamforne) || (isset($pc21_orcamforne) && trim($pc21_orcamforne) == "")) {
                db_fieldsmemory($result_forne, 0);
            }

            $qry = "";
            if (isset($pc21_orcamforne) && trim($pc21_orcamforne) != "") {
                $qry = "&pc21_orcamforne=$pc21_orcamforne";
                $result_lancados = $clpcorcamval->sql_record($clpcorcamval->sql_query(null, null,
                    "pc23_orcamforne,pc23_orcamitem,pc23_valor", "",
                    "pcorcam.pc20_codorc=$pc20_codorc and pc21_orcamforne=$pc21_orcamforne"));

                if ($clpcorcamval->numrows > 0 && $db_opcao != 3 && $db_opcao != 33) {
                    $voltar = true;
                    $db_opcao = 2;
                    $db_botao = true;
                }
            }
            echo "
                        <tr>
                            <td class='dados-orcamento'>
                                <label>{$Lpc21_orcamforne}</label>
                                </td>
                                <td>
                          ";
            db_selectrecord("pc21_orcamforne", $result_forne, true, $db_opcao, "", "", "", "",
                "js_dalocation(document.form1.pc21_orcamforne.value);");
            echo "  <td>
                        </tr>";
            ?>
            <tr>
                <td class='dados-orcamento'>
                    <label for="pc21_validadorc"><?= $Lpc21_validadorc ?></label>
                </td>
                <td>
                    <?php
                    if (isset($pc21_orcamforne) && trim($pc21_orcamforne) != "") {
                        $datausu = date("Y-m-d", db_getsession("DB_datausu"));
                        $result_data = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null,
                            "pc21_validadorc,pc21_prazoent,pc21_validadorc - date '$datausu' as dias_validade,pc21_prazoent - date '$datausu'   as dias_prazo, pc21_taxaestimadaglobal",
                            "pc21_orcamforne", "pc21_codorc=$pc20_codorc and pc21_orcamforne=$pc21_orcamforne"));
                        if ($clpcorcamforne->numrows > 0) {
                            db_fieldsmemory($result_data, 0);
                        }
                    }

                    db_inputdata("pc21_validadorc", @$pc21_validadorc_dia, @$pc21_validadorc_mes,
                        @$pc21_validadorc_ano, true, "text", $db_opcao, "onChange='js_data_validade();';", "", "",
                        "parent.js_data_validade();", "", "");
                    ?>
                </td>
            </tr>
            <tr>
                <td class='dados-orcamento'>
                    <label for="pc21_prazoent"><?= $Lpc21_prazoent ?></label>
                </td>
                <td>
                    <?php
                    db_inputdata("pc21_prazoent", @$pc21_prazoent_dia, @$pc21_prazoent_mes, @$pc21_prazoent_ano,
                        true, "text", $db_opcao, "onChange='js_data_prazo();'", "", "", "parent.js_data_prazo();",
                        "", "");
                    ?>
                </td>
            </tr>
            <tr>
                <td class='dados-orcamento'>
                    <label for="dias_validade"><b>Dias de validade:</b></label>
                </td>
                <td>
                    <?php
                    db_input("dias_validade", 5, "", false, "text", $db_opcao, "onChange='js_dias_validade();'");
                    ?>
                </td>
            </tr>
            <tr>
                <td class='dados-orcamento'>
                    <label for='dias_prazo'><b>Dias de prazo:</b></label>
                </td>
                <td>
                    <?php
                    db_input("dias_prazo", 5, "", false, "text", $db_opcao, "onChange='js_dias_prazo();'");
                    ?>
                </td>
            </tr>
            <?php
            $itemMenu = db_getsession('DB_itemmenu_acessado');
            if ($itemMenu == 4103 || $itemMenu == 4104 || $itemMenu == 4108) {
                echo "
                        <tr>
                            <td class='dados-orcamento'>
                                <label for='pc21_taxaestimadaglobal'><b>Taxa estimada global:</b></label>
                                </td>
                                <td>
                        ";
                db_input("pc21_taxaestimadaglobal", 5, 4, true, "text", "", "onBlur='js_taxa_estimada_global();'");
                echo "
                            </td>
                        </tr>
                        ";
            }

            ?>
            </tbody>
        </table>
        <table class="table">
            <tbody>
            <?php
            echo "
                        <tr class='full-width'>
                            <td colspan='2'>
                                <fieldset>
                                    <legend>Lançar Valores dos Orçamentos</legend>
                    	            <iframe name='elementos' id='elementos'  marginwidth='0' marginheight='0' frameborder='0' src='com1_orcamlancval0011.php?pc20_codorc=$pc20_codorc&db_opcao=$db_opcao" . $qry . "&sol=$sol' width='100%' height='400' onload='processarTaxaGlobalItens(true);'>
                                    </iframe>
                                </fieldset>
                            <td>
                        </tr>
                        <tr>
                            <td colspan='2' align='center'>
                                <br><br>
                                <input name='" . ($db_opcao == 1 ? "incluir" : "alterar") . "' type='submit' id='db_opcao' value='" . ($db_opcao == 1 ? "Incluir" : "Alterar") . "'  " . ($db_botao == false ? "disabled" : "") . " onclick='return  js_buscarcod();'>
                                <input name='voltar' type='button' id='voltar' value='Voltar'  onclick='document.location.href=\"com1_selorc001.php?sol=$sol\"'>
                                <input name='importar' type='button' id='importar' value='Valores unitários'  onclick='elementos.js_importar(true);elementos.js_somavalor();'>
                                <input name='zerar'  type='button' id='zerar' value='Zerar valores'  onclick='elementos.js_importar(false);elementos.js_somavalor();'>
                        ";
            if ($voltar == true) {
                echo " <input name='trocar' type='button' id='trocar' value='Julgar orçamento'  onclick='document.location.href=\"com1_pcorcamtroca001.php?pc20_codorc=$pc20_codorc&sol=$sol\"'>";
            }
            } else { ?>
                <tr>
                    <td align='center' colspan='2'>
                        <br><br>
                        <strong>Não existem itens para este orçamento.</strong>
                        <br><br>
                    <td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </fieldset>
</form>
