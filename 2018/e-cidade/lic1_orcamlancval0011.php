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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_pcorcamitem_classe.php"));
include(modification("classes/db_pcorcamval_classe.php"));
include(modification("classes/db_liclicitemlote_classe.php"));
include(modification("classes/db_empparametro_classe.php"));

$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamval = new cl_pcorcamval;
$clliclicitemlote = new cl_liclicitemlote;
$clrotulo = new rotulocampo;
$clempparametro = new cl_empparametro;
$lLicitacaoTipoObrasServicos = false;
$lLicitacaoPossuiNotaTecnica = false;

$licitacaoMenorTaxa = false;
$julgamentoGlobal = false;
$julgamentoItem = false;
$julgamentoLote = false;

$clpcorcamitem->rotulo->label();
$clrotulo->label('pc23_valor');
$clrotulo->label('pc23_vlrun');
$clrotulo->label('pc23_quant');
$clrotulo->label('pc23_obs');
$clrotulo->label('pc23_validmin');
$clrotulo->label('pc32_motivo');
$clrotulo->label('pc11_vlrun');
$clrotulo->label('pc11_quant');
$clrotulo->label('pc11_resum');

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);


$arr_vlnomesitens = Array();
$arr_valoresitens = Array();
$arr_quantitens = Array();
$arr_vtnomesitens = Array();

$aListaBdiValores = array();
$aListaEncargoSociaisValores = array();
$aListaNotasValores = array();
$aListaTaxaHomologada = array();
$aListaValorEstimado = array();
$aListaItemProcesso = array();
$aCodigosItensPorLote = array();

$res_empparametro = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"), "e30_numdec"));
if ($clempparametro->numrows > 0) {

    db_fieldsmemory($res_empparametro, 0);
    if (trim($e30_numdec) == "" || $e30_numdec == 0) {
        $numdec = 2;
    } else {
        $numdec = $e30_numdec;
    }
} else {
    $numdec = 2;
}

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script>
      function js_verquant (nome, val, max, param) {
        val = new Number(val);
        max = new Number(max);
        erro = 0;
        if (val > max) {
          erro++;
          alert('Usuário: \n\nQuantidade orçada deve ser menor que a quantidade do pedido.\n\nAdministrador:');
        }
        if (val < 0) {
          erro++;
          alert('Usuário: \n\nQuantidade orçada deve ser maior que 0 (zero).\n\nAdministrador:');
        }
        if (erro > 0) {
          eval('document.form1.' + nome + '.value=\'' + max + '\';');
          eval('document.form1.' + nome + '.focus();');
        } else {
          valorunit = eval('document.form1.vlrun_' + param + '.value');
          valortotal = eval('document.form1.valor_' + param + '.value');
          verpos = 0;
          vltot = 0;
          if (valorunit != '') {
            valorunit = valorunit.replace(',', '.');
            verpos = 1;
          } else if (valortotal != '') {
            valortotal = valortotal.replace(',', '.');
            verpos = 2;
          }

          if (verpos == 1) {
            valpos = valorunit;
          } else if (verpos == 2) {
            valpos = valortotal;
          }

          dec = 2;
          if (verpos != 0) {
            pos = valpos.indexOf('.');
            if (pos != -1) {
              tam = new Number(valpos.length);
              qts = valpos.slice((pos + 1), tam);
              dec = 2;
            }
            if (dec <= 1) {
              dec = 2;
            }
            valpos = new Number(valpos);
          }
          if (verpos == 1) {
            vltot = new Number(valpos * val);
            eval('document.form1.valor_' + param + '.value=\'' + vltot.toFixed(2) + '\'');
          } else if (verpos == 2) {
            vltot = new Number(valpos / val);
            eval('document.form1.vlrun_' + param + '.value=\'' + vltot.toFixed(<?=$numdec?>) + '\'');
          }
        }
      }

      function js_calcvaltot (valor, param, nome) {
        if (!isNaN(valor)) {
          dec = 2;
          pos = valor.indexOf('.');
          if (pos != -1) {
            tam = new Number(valor.length);
            qts = valor.slice((pos + 1), tam);
            dec = qts.length;
          }
          if (dec <= 1) {
            dec = 2;
          }
          quant = eval('document.form1.qtde_' + param + '.value');
          valortotal = new Number(eval('document.form1.valor_' + param + '.value'));
          if (valor != '' && quant != '') {
            valor = new Number(valor);
            quant = new Number(quant);
            valortotal = new Number(quant * valor);
          }
          if (valor == '') {
            valor = 0;
          }

          eval('document.form1.valor_' + param + '.value=\'' + valortotal.toFixed(2) + '\'');
          eval('document.form1.' + nome + '.value=\'' + valor.toFixed(<?=$numdec?>) + '\'');
          if (valortotal == 0) {
            eval('document.form1.' + nome + '.value=\'0.00\'');
          }
        } else {
          eval('document.form1.vlrun_' + param + '.value=\'\'');
        }

      }

      function js_calcvalunit (valor, param, nome) {
        if (!isNaN(valor)) {
          dec = 2;
          pos = valor.indexOf('.');
          if (pos != -1) {
            tam = new Number(valor.length);
            qts = valor.slice((pos + 1), tam);
            dec = qts.length;
          }
          if (dec <= 1) {
            dec = 2;
          }
          quant = eval('document.form1.qtde_' + param + '.value');
          valorunit = new Number(eval('document.form1.vlrun_' + param + '.value'));
          if (valor != '' && quant != '') {
            valor = new Number(valor);
            quant = new Number(quant);
            valorunit = new Number(valor / quant);
          }
          if (valor == '') {
            valor = 0;
          }
          eval('document.form1.vlrun_' + param + '.value=\'' + valorunit.toFixed(<?=$numdec?>) + '\'');
          eval('document.form1.' + nome + '.value=\'' + valor.toFixed(2) + '\'');
          if (valorunit == 0) {
            eval('document.form1.vlrun_' + param + '.value=\'0.00\'');
          }
        } else {
          eval('document.form1.valor_' + param + '.value=\'\'');
        }

      }

      function js_passacampo (campo, vsubtr) {

        var iCampoAtual = 0;
        var iFormeLength = document.form1.length;

        for (var i = 0; i < iFormeLength; i++) {

          if (document.form1.elements[i].name == campo) {

            iCampoAtual = i;
            break;
          }
        }

        for (var iNextField = 0; iNextField < iFormeLength; iNextField++) {

          if (iNextField > iCampoAtual && document.form1.elements[iNextField].name.substr(0, vsubtr.length) == vsubtr) {

            document.form1.elements[iNextField].select();
            document.form1.elements[iNextField].focus();
            break;
          }
        }
      }

      function js_somavalor () {
        obj = document.form1;
        somavalor = 0;
        for (i = 0; i < obj.elements.length; i++) {
          if (obj.elements[i].name.substr(0, 6) == 'valor_') {
            if (obj.elements[i].value != '' && obj.elements[i].value != 0) {
              var objvalor = new Number(obj.elements[i].value);
              somavalor += objvalor;
            }
          }
        }

        document.form1.somavalor.value = somavalor.toFixed(2);
      }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>

        .bordas {
            border: 2px solid #cccccc;
            border-top-color: #999999;
            border-right-color: #999999;
            border-bottom-color: #999999;
            background-color: #999999;
        }

        .bordas_corp {
            border: 1px solid #cccccc;
            border-right-color: #999999;
            border-bottom-color: #999999;
        }

        .bordas_corp_descla {
            border: 1px solid #cccccc;
            border-right-color: #999999;
            border-bottom-color: #999999;
            background-color: #E9F882;
        }

        .bordas_item_reservado {
            border: 1px solid #cccccc;
            border-right-color: #999999;
            border-bottom-color: #999999;
            background-color: #6C99BC;
        }

        .legenda {
            border: 1px solid #ccc;
            width: 20px;
        }

        .legenda_desclassificado {
            background-color: #E4F471;
        }

        .legenda_reservado {
            background-color: #6C99BC;
        }

    </style>
</head>
<body class="body-default">
<div class="container">
    <form name="form1" method="post">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" valign="top" bgcolor="#CCCCCC">
                    <table border='1' cellspacing="0" cellpadding="0">
                        <?php
                        $codprocant = "";
                        $camposelected = "";

                        if (isset($pc20_codorc) && trim($pc20_codorc) != '') {

                            $ok = false;
                            if (isset($pc21_orcamforne) && trim($pc21_orcamforne) != '') {
                                $ok = true;
                            }

                            $dbwhere = "";
                            if (isset($l20_codigo) && trim($l20_codigo) != "") {

                                $res_liclicitemlote = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null,
                                    "l04_liclicitem", null,
                                    "l20_codigo = $l20_codigo and l21_situacao = 0 and l04_descricao = '" . @$descricao . "'"));

                                if ($clliclicitemlote->numrows > 0) {

                                    $numrows = $clliclicitemlote->numrows;
                                    $dbwhere = "and l21_codigo in (";
                                    $lista_itens = "";
                                    $virgula = "";

                                    for ($i = 0; $i < $numrows; $i++) {

                                        db_fieldsmemory($res_liclicitemlote, $i);
                                        $lista_itens .= $virgula . $l04_liclicitem;
                                        $virgula = ", ";
                                    }

                                    $dbwhere .= $lista_itens . ")";
                                }
                            }


                            // Mostra descricao do lote - TODOS
                            if (((!isset($descricao) && trim(@$descricao) == "") || !empty($possuiLote)) && isset($l20_codigo) && trim($l20_codigo) != "") {

                                $sSqlLote = $clliclicitemlote->sql_query_licitacao(null,
                                    "l04_liclicitem, l04_descricao", null,
                                    "l20_codigo = $l20_codigo and l21_situacao = 0");

                                $res_liclicitemlote = $clliclicitemlote->sql_record($sSqlLote);
                                $numrows_lote = $clliclicitemlote->numrows;
                                $mostra_lote = true;
                                $cols = 14;
                                $ordem = "l04_descricao,l21_ordem,pc81_codproc,pc22_orcamitem";

                            } else {
                                $mostra_lote = false;
                                $cols = 13;
                                $ordem = "pc81_codproc,l21_ordem,l04_descricao,pc22_orcamitem";
                            }

                            $sSqlItens = $clpcorcamitem->sql_query_pcmaterlic(null, "distinct l21_ordem,
                                                                                                        pc81_codprocitem,
                                                                                                        pc81_codproc,
                                                                                                        pc11_seq,
                                                                                                        pc11_resum,
                                                                                                        pc11_codigo,
                                                                                                        pc11_vlrun,
                                                                                                        pc11_quant,
                                                                                                        pc01_descrmater,
                                                                                                        pc22_orcamitem,
                                                                                                        l21_codigo,
                                                                                                        l20_codigo,
                                                                                                        l20_usaregistropreco,
                                                                                                        pc11_resum,
                                                                                                        pc32_orcamitem,
                                                                                                        pc32_orcamforne,
                                                                                                        l04_descricao as descr_lote,
                                                                exists(select * from licitacaoreservacotas where l19_liclicitemreserva = l21_codigo) as reserva",
                                $ordem, "pc20_codorc={$pc20_codorc}
                                    and pc21_orcamforne = {$pc21_orcamforne}
                                    and l21_situacao = 0 {$dbwhere}");
                            $result_itens = $clpcorcamitem->sql_record($sSqlItens);
                            $numrows_itens = $clpcorcamitem->numrows;

                            if ($numrows_itens > 0) {

                                $codigoLicitacao = db_utils::fieldsMemory($result_itens, 0)->l20_codigo;
                                $oLicitacaoAtributosDinamicos = new LicitacaoAtributosDinamicos;
                                $oLicitacaoAtributosDinamicos->setCodigoLicitacao($codigoLicitacao);

                                if ($oLicitacaoAtributosDinamicos->getAtributo('tipoobjeto') == 'OSE') {
                                    $lLicitacaoTipoObrasServicos = true;
                                }

                                $aTiposLicitacaoPossuiNotaTecnica = array(
                                    'MCA',
                                    'MOQ',
                                    'MOT',
                                    'MPP',
                                    'MTC',
                                    'MTO',
                                    'MTT',
                                    'TPR'
                                );
                                $lPossuiNotaTecnica = in_array($oLicitacaoAtributosDinamicos->getAtributo('tipolicitacao'),
                                    $aTiposLicitacaoPossuiNotaTecnica);

                                if ($lPossuiNotaTecnica) {
                                    $lLicitacaoPossuiNotaTecnica = true;
                                }

                                $licitacao = new licitacao($codigoLicitacao);
                                $licitacaoMenorTaxa = $oLicitacaoAtributosDinamicos->getAtributo(LicitacaoAtributosDinamicos::NOME_TIPO_LICITACAO) === 'MTX';
                                $julgamentoGlobal = (int)$licitacao->getTipoJulgamento() === licitacao::TIPO_JULGAMENTO_GLOBAL;
                                $julgamentoItem = (int)$licitacao->getTipoJulgamento() === licitacao::TIPO_JULGAMENTO_POR_ITEM;
                                $julgamentoLote = (int)$licitacao->getTipoJulgamento() === licitacao::TIPO_JULGAMENTO_POR_LOTE;
                            }

                            if ($numrows_itens > 0) {
                                ?>
                                <tr>
                                    <td colspan='<?php echo $cols; ?>'>
                                        <table>
                                            <tr>
                                                <td class="legenda legenda_desclassificado"></td>
                                                <td class="bold" nowrap>Itens Desclassificados</td>
                                                <td class="legenda legenda_reservado"></td>
                                                <td class="bold" nowrap>Itens Reservados para ME e EPP</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr class='bordas_corp'>
                                    <td class='bordas_corp' colspan='<?= $cols ?>' align='center' nowrap><b> LANÇAR
                                            VALORES DOS ORÇAMENTOS </b></td>
                                </tr>
                                <?php
                                for ($i = 0; $i < $numrows_itens; $i++) {

                                    db_fieldsmemory($result_itens, $i);
                                    $str_lote = "";

                                    if (substr($descr_lote, 0, 9) == "AUTO_LOTE") {
                                        $str_lote = str_replace("_", "", $descr_lote);
                                    } else {
                                        if (substr($descr_lote, 0, 13) == "LOTE_AUTOITEM") {
                                            $str_lote = str_replace("_", "", $descr_lote);
                                        }
                                    }

                                    if (strlen($str_lote) > 0) {
                                        $descr_lote = $str_lote;
                                    }

                                    if ($codprocant != $pc81_codproc) {
                                        $codprocant = $pc81_codproc;
                                        ?>
                                        <tr class='bordas_corp'>
                                            <td class='bordas_corp' colspan='<?= $cols ?>' align='left' nowrap><b>
                                                    Processo de Compras Nº <?= $pc81_codproc ?></b></td>
                                        </tr>
                                        <tr class='bordas'>
                                            <td nowrap class='bordas' align='center'><a href='#'
                                                                                        onClick='js_pcorcamdescla();'
                                                                                        title='Desclassificar itens'>M</a>
                                            </td>
                                            <td nowrap class='bordas' align='center'>Item</td>
                                            <td nowrap class='bordas' align='center'>Seq. Item</td>
                                            <td nowrap class='bordas' align='center'><b>Descrição</b></td>

                                            <?php if ($mostra_lote == true) { ?>
                                                <td nowrap class='bordas' align='center'><b>Lote</b></td>
                                            <?php } ?>

                                            <td nowrap class='bordas' align='center'><b>Observação</b></td>
                                            <td nowrap class='bordas' align='center'><b>Validade Mínima</b></td>
                                            <td nowrap class='bordas' align='center'><b>Quantidade</b></td>

                                            <?php if ($l20_usaregistropreco == 't') { ?>
                                                <td nowrap class='bordas' align='center'><b>Qtde. min</b></td>
                                                <td nowrap class='bordas' align='center'><b>Valor Max.</b></td>
                                            <?php } ?>

                                            <td nowrap class='bordas' align='center'><b>Qtde. orçada</b></td>
                                            <td nowrap class='bordas' align='center'><b>Valor Unit.</b></td>
                                            <td nowrap class='bordas' align='center'><b>Valor total</b></td>

                                            <?php if ($lLicitacaoPossuiNotaTecnica) { ?>
                                                <td nowrap class='bordas' align='center'><b>Nota Técnica</b></td>
                                            <?php } ?>

                                            <?php if ($lLicitacaoTipoObrasServicos) { ?>
                                                <td nowrap class='bordas' align='center'><b>BDI</b></td>
                                                <td nowrap class='bordas' align='center'><b>Encargos Sociais</b></td>
                                            <?php } ?>

                                            <?php if ($licitacaoMenorTaxa) { ?>
                                                <td nowrap class='bordas' align='center'><b>Valor Unit. Estimado</b>
                                                </td>
                                                <td nowrap class='bordas' align='center'><b>Taxa Homologada</b></td>
                                            <?php } ?>


                                        </tr>
                                        <?php
                                    }

                                    if ($ok == true) {

                                        $camposItem = implode(', ', array(
                                            "pc23_valor as valor_$pc22_orcamitem",
                                            "pc23_vlrun as vlrun_$pc22_orcamitem",
                                            "pc23_obs as obs_$pc22_orcamitem",
                                            "pc23_quant as qtde_$pc22_orcamitem",
                                            "pc23_validmin  as pc23_validmin_$pc22_orcamitem",
                                            "pc23_bdi as bdi_$pc22_orcamitem",
                                            "pc23_encargossociais as encargossociais_$pc22_orcamitem",
                                            "pc23_notatecnica as notatecnica_$pc22_orcamitem",
                                            "pc23_taxahomologada as txhomologada_$pc22_orcamitem",
                                            )
                                        );
                                        $result_lancados = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(@$pc21_orcamforne,
                                            @$pc22_orcamitem,
                                            $camposItem,
                                            "pc23_orcamitem"));

                                        if ($clpcorcamval->numrows > 0) {
                                            db_fieldsmemory($result_lancados, 0);
                                        }
                                    }

                                    if (trim($pc01_descrmater) == "") {
                                        $pc01_descrmater = $pc11_resum;
                                    }

                                    if ($i == 0) {
                                        $camposelected = "vlrun_$pc22_orcamitem";
                                    }

                                    if (isset($pc32_orcamitem) && trim($pc32_orcamitem) != "" && isset($pc32_orcamforne) && trim($pc32_orcamitem) != "" && $pc32_orcamitem == $pc22_orcamitem && $pc32_orcamforne == $pc21_orcamforne) {

                                        $disabled = "disabled";
                                        $class = "bordas_corp_descla";

                                    } else {

                                        $disabled = "";
                                        $class = "bordas_corp";

                                        if ($reserva == 't') {
                                            $class = "bordas_item_reservado";
                                        }
                                    }


                                    $check = "chk_" . $pc22_orcamitem . "_" . $descr_lote;
                                    ?>
                                    <tr class='<?= $class ?>' width='15%'>
                                        <td align='center' class='<?= $class ?>' width='15%'>
                                            <input name='chk_<?= $pc22_orcamitem ?>' type='checkbox'
                                                   value='<?= $check ?>'
                                                   onClick='js_desclassifica('<?= $descr_lote ?>')' <?= $disabled ?>>
                                        </td>

                                        <td align='left' class='<?= $class ?>' width='15%'>
                                            <?= $pc81_codprocitem ?>
                                        </td>

                                        <td align='center' class='<?= $class ?>' width='15%'>
                                            <?= $l21_ordem ?>
                                        </td>

                                        <td align='left' nowrap class='<?= $class ?>' width='25%'
                                            onMouseOver="js_mostra_text(true,'div_text_<?= $pc22_orcamitem ?>', event, this);"
                                            onMouseOut="js_mostra_text(false,'div_text_<?= $pc22_orcamitem ?>', event, this);">
                                            <?= $pc01_descrmater ?>
                                        </td>

                                        <?php
                                        $l04_descricao = '';
                                        if ($mostra_lote == true) {
                                            for ($ii = 0; $ii < $numrows_lote; $ii++) {
                                                db_fieldsmemory($res_liclicitemlote, $ii);

                                                if ($l04_liclicitem == $l21_codigo) {

                                                    $aCodigosItensPorLote[] = "$l04_descricao|{$pc22_orcamitem}";
                                                    echo "<td align='center' class='bordas_corp' width='45%'>" . $l04_descricao . "</td>";
                                                    break;
                                                }
                                            }
                                        }
                                        ?>

                                        <td align='center' class='<?= $class ?>'>
                                            <?php db_input("obs_$pc22_orcamitem", 20, $Ipc23_obs, true, 'text',
                                                $db_opcao,
                                                "onchange='document.form1.vlrun_$pc22_orcamitem.select();' $disabled"); ?>
                                        </td>

                                        <td align='center' nowrap class='<?= $class ?>'>
                                            <?php
                                            $dia = "pc23_validmin_" . $pc22_orcamitem . "_dia";
                                            $mes = "pc23_validmin_" . $pc22_orcamitem . "_mes";
                                            $ano = "pc23_validmin_" . $pc22_orcamitem . "_ano";
                                            db_inputdata("pc23_validmin_$pc22_orcamitem", @$$dia, @$$mes, @$$ano, true,
                                                "text", $db_opcao, $disabled);
                                            ?>
                                        </td>

                                        <td align='center' class='<?= $class ?>' width='15%'>
                                            <?php
                                            ${"qtdeOrcada_$pc22_orcamitem"} = $pc11_quant;
                                            db_input("qtdeOrcada_$pc22_orcamitem", 10, $Ipc11_quant, true, 'text', 3);
                                            ?>
                                        </td>

                                        <?php
                                        if ($l20_usaregistropreco == 't') {

                                            $sSqlQuantidades = $clpcorcamitem->sql_query_pcmaterregistro($pc22_orcamitem,
                                                "pc57_quantmin,pc57_quantmax");
                                            $rsQuantidades = $clpcorcamitem->sql_record($sSqlQuantidades);
                                            $nQuantMin = 0;
                                            $nQuantMax = 0;

                                            if ($clpcorcamitem->numrows > 0) {

                                                $oInfoRegistroPreco = db_utils::fieldsMemory($rsQuantidades, 0);
                                                $nQuantMax = $oInfoRegistroPreco->pc57_quantmax;
                                                $nQuantMin = $oInfoRegistroPreco->pc57_quantmin;
                                            }
                                            ?>
                                            <td class='<?= $class ?>'><?= $nQuantMin ?></td>
                                            <td class='<?= $class ?>'><?= $nQuantMax ?></td>
                                        <?php } ?>

                                        <td align='center' class='bordas_corp'>
                                            <?php
                                            $qtd = "qtde_$pc22_orcamitem";
                                            $vlrun = "vlrun_$pc22_orcamitem";
                                            $valor = "valor_$pc22_orcamitem";
                                            $qtdeOrcada = "qtdeOrcada_$pc22_orcamitem";

                                            $arr_vlnomesitens[$i] = "vlrun_$pc22_orcamitem";
                                            $arr_valoresitens[$i] = $pc11_vlrun;
                                            $arr_quantitens[$i] = $pc11_quant;
                                            $arr_vtnomesitens[$i] = "valor_$pc22_orcamitem";
                                            $arr_quantitensOrcada[$i] = "qtdeOrcada_$pc22_orcamitem";

                                            $aListaBdiValores[$i] = "bdi_$pc22_orcamitem";
                                            $aListaEncargoSociaisValores[$i] = "encargossociais_$pc22_orcamitem";
                                            $aListaNotasValores[$i] = "notatecnica_$pc22_orcamitem";
                                            $aListaTaxaHomologada[$i] = "txhomologada_$pc22_orcamitem";
                                            $aListaValorEstimado[$i] = "vlrestimado_$pc22_orcamitem";
                                            $aListaItemProcesso[] = "{$pc81_codprocitem}|vlrestimado_{$pc22_orcamitem}";

                                            if ($clpcorcamval->numrows > 0) {
                                                if (strpos($$valor, ".") == "") {
                                                    $$valor .= ".00";
                                                }
                                            }

                                            if (!isset($$qtd) || (isset($$qtd) && $$qtd == '')) {
                                                $$qtd = $pc11_quant;
                                            }

                                            $db_opcaoquant = 1;
                                            if ($l20_usaregistropreco == 't') {
                                                $db_opcaoquant = 3;
                                            }

                                            db_input("qtde_$pc22_orcamitem", 10, $Ipc23_quant, true, 'text',
                                                $db_opcaoquant,
                                                "onchange='js_verquant(this.name,this.value,$pc11_quant,$pc22_orcamitem);js_somavalor();' $disabled");
                                            ?>
                                        </td>

                                        <td align='center' class='<?= $class ?> inputsValorUnitario'>
                                            <?php

                                            db_input("vlrun_$pc22_orcamitem", 10, $Ipc23_valor, true, 'text',
                                                $db_opcao,
                                                'onchange="js_calcvaltot(this.value,\'' . $pc22_orcamitem . '\',this.name); js_passacampo(this.name, \'obs_\' ); js_somavalor();"' . $disabled); ?>
                                        </td>

                                        <td align='center' class='<?= $class ?>' width='15%'>
                                            <?php
                                            db_input("valor_$pc22_orcamitem", 10, $Ipc23_valor, true, 'text',
                                                $db_opcao,
                                                'onchange="js_calcvalunit(this.value,\'' . $pc22_orcamitem . '\',this.name);js_passacampo(this.name,\'obs_\');js_somavalor();"' . $disabled); ?>
                                        </td>

                                        <?php if ($lLicitacaoPossuiNotaTecnica) { ?>
                                            <td align="center" class="<?= $class ?> bordas_corp">
                                                <?php
                                                ${'Snotatecnica_' . $pc22_orcamitem} = "Nota Técnica";
                                                db_input("notatecnica_$pc22_orcamitem", 7, 4, true, 'text', $db_opcao,
                                                    'onchange="js_casasdecimais(this, 2);"' . $disabled);
                                                ?>
                                            </td>
                                        <?php } ?>

                                        <?php if ($lLicitacaoTipoObrasServicos) { ?>
                                            <td align='center' class='<?= $class ?> bordas_corp'>
                                                <?php
                                                ${'Sbdi_' . $pc22_orcamitem} = "BDI";
                                                db_input("bdi_$pc22_orcamitem", 7, 4, true, 'text', $db_opcao,
                                                    'onchange="js_casasdecimais(this, 2);"' . $disabled);
                                                ?>
                                            </td>

                                            <td align='center' class='<?= $class ?> bordas_corp'>
                                                <?php
                                                ${'Sencargossociais_' . $pc22_orcamitem} = "Encargos Sociais";
                                                db_input("encargossociais_$pc22_orcamitem", 10, 4, true, 'text',
                                                    $db_opcao, 'onchange="js_casasdecimais(this, 2);"' . $disabled);
                                                ?>
                                            </td>
                                        <?php } ?>

                                        <?php if ($licitacaoMenorTaxa) { ?>
                                            <td align='center' class='<?= $class ?> bordas_corp'>
                                                <?php
                                                db_input("vlrestimado_{$pc22_orcamitem}", 10, 4, true, 'text', 3);
                                                ?>
                                            </td>

                                            <td align='center' class='<?= $class ?> bordas_corp'>
                                                <?php
                                                $opcaoTaxaHomologada = 1;
                                                if ($julgamentoGlobal) {
                                                    $opcaoTaxaHomologada = 3;
                                                }
                                                db_input("txhomologada_{$pc22_orcamitem}", 10, 4, true, 'text',
                                                    $opcaoTaxaHomologada,
                                                    "onchange='processarTaxaHomologada(this, \"{$l04_descricao}\");'");
                                                ?>
                                            </td>

                                        <?php } ?>
                                    </tr>

                                    <?php
                                    if (isset($$qtd) && $$qtd != "") {
                                        echo "<script>js_verquant('" . $qtd . "','" . $$qtd . "','" . $pc11_quant . "','" . $pc22_orcamitem . "');</script>";
                                    }
                                }
                            } else {
                                ?>
                                <tr>
                                    <td nowrap align='center'><b> Não existem itens para este orçamento. </b></td>
                                </tr>
                            <?php }
                        } ?>
                    </table>

                    <table border=1 align='right'>
                        <tr>
                            <td class="bordas" align='right'>
                                <b>Valor Total:</b> <?php db_input("somavalor", 10, "", true, 'text', 3, ""); ?>
                            </td>
                        </tr>
                    </table>

                    <?php
                    for ($i = 0; $i < $numrows_itens; $i++) :
                        db_fieldsmemory($result_itens, $i);
                        ?>
                        <div id='div_text_<?= $pc22_orcamitem ?>'
                             style="visibility:hidden ; top:0px; left:0px; display:none; background-color:#6699CC ; border:2px outset #ccc">
                            <table>
                                <tr>
                                    <td align='left'>
                                        <font color='black' face='arial' size='2'><strong><?= $RLpc11_resum ?></strong>:</font><br>
                                        <font color='black' face='arial' size='1'><?= $pc11_resum ?></font>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php endfor; ?>
                </td>
            </tr>
        </table>
    </form>
</div>

<?php if (isset($camposelected) && $camposelected != "") { ?>
    <script>
      document.form1.<?= $camposelected ?>.select();
      document.form1.<?= $camposelected ?>.focus();
    </script>
<?php } ?>

<script>

  var licitacaoDeMenorTaxa = '<?php echo $licitacaoMenorTaxa; ?>' === '1' ? true : false;
  console.log(typeof licitacaoDeMenorTaxa);
  console.log(licitacaoDeMenorTaxa);
  var codigoLicitacao = <?php echo $codigoLicitacao; ?>;
  var codigoFornecedor = <?php echo $pc21_orcamforne; ?>;
  var itens = '<?php echo implode('#', $aListaItemProcesso); ?>';
  var lotes = '<?php echo implode('#', $aCodigosItensPorLote); ?>';

  function js_mostra_text (liga, nomediv, evt, el) {
    evt = (evt) ? evt : (window.event) ? window.event : '';

    if (liga == true) {

      document.getElementById(nomediv).style.position = 'absolute';

      document.getElementById(nomediv).style.top = ( getPageOffsetTop(el) - 30 ) + 'px';
      document.getElementById(nomediv).style.left = ( getPageOffsetLeft(el) + 50 ) + 'px';

      document.getElementById(nomediv).style.visibility = 'visible';
      document.getElementById(nomediv).style.display = 'block';
    } else {
      document.getElementById(nomediv).style.visibility = 'hidden';
      document.getElementById(nomediv).style.display = 'none';
    }
  }
  <?
  if (isset($ok) && $ok == true){
  ?>
  js_somavalor();
  <?
  }
  ?>
  function js_importar (TouF) {
      <?
      for ($i = 0; $i < count($arr_vlnomesitens); $i++) {

          echo "if(TouF==true){";
          echo "  document.form1." . $arr_vlnomesitens[$i] . ".value = '" . trim(db_formatar($arr_valoresitens[$i], "p")) . "';";
          echo "  document.form1." . $arr_vtnomesitens[$i] . ".value = '" . trim(db_formatar(($arr_valoresitens[$i] * $arr_quantitens[$i]), "p")) . "';";
          if ($licitacaoMenorTaxa) {
              echo "  document.form1." . $aListaValorEstimado[$i] . ".value = '" . trim(db_formatar($arr_valoresitens[$i], "p")) . "';";
          }
          echo "}else{";
          echo "  document.form1." . $arr_vlnomesitens[$i] . ".value = '0.00';";
          echo "  document.form1." . $arr_vtnomesitens[$i] . ".value = '0.00';";
          if ($lLicitacaoTipoObrasServicos) {
              echo "  document.form1." . $aListaBdiValores[$i] . ".value = '0';";
              echo "  document.form1." . $aListaEncargoSociaisValores[$i] . ".value = '0';";
          }

          if ($lLicitacaoPossuiNotaTecnica) {
              echo "  document.form1." . $aListaNotasValores[$i] . ".value = '0';";
          }

          if ($licitacaoMenorTaxa) {
              echo "  document.form1." . $aListaValorEstimado[$i] . ".value = '" . trim(db_formatar($arr_valoresitens[$i], "p")) . "';";
              echo "  document.form1." . $aListaTaxaHomologada[$i] . ".value = '0';";
          }

          echo "}";
      }
      ?>
  }

  function js_abrejan () {
    var tam = document.form1.elements.length;
    var orcamitem = new String('');
    var separador = '';

    for (i = 0; i < tam; i++) {
      if (document.form1.elements[i].type == 'checkbox') {
        if (document.form1.elements[i].checked == true) {
          valor = new String(document.form1.elements[i].value);
          vetor = valor.split('_');
          orcamitem += separador + vetor[1];
          separador = ', ';
        }
      }
    }

    orcamforn = new String('<?=@$pc21_orcamforne?>');

    if (orcamitem.length > 0 && orcamforn.length > 0) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_descla', 'com1_pcorcamdescla001.php?orcamitem=' + orcamitem + '&pc32_orcamforne=' + orcamforn, 'Motivo da Desclassificacao', true);
    } else {
      if (orcamitem.length == 0) {
        alert('Selecione um item');
      } else {
        if (orcamforn.length == 0) {
          alert('Inclua orçamento de fornecedor para poder desclassificar item(ns)');
        }
      }
    }
  }

  function js_pcorcamdescla () {
    var tam = document.form1.elements.length;

    for (i = 0; i < tam; i++) {
      if (document.form1.elements[i].type == 'checkbox') {
        if (document.form1.elements[i].disabled == false) {
          if (document.form1.elements[i].checked == true) {
            document.form1.elements[i].checked = false;
          } else {
            document.form1.elements[i].checked = true;
          }
        }
      }
    }
  }

  function js_desclassifica (lote) {
    var tam = document.form1.elements.length;
    var campo = lote;

    for (i = 0; i < tam; i++) {
      if (document.form1.elements[i].type == 'checkbox') {
        if (document.form1.elements[i].disabled == false) {
          var str = new String(document.form1.elements[i].value);
          var vet = str.split('_');

          if (campo == vet[2]) {
            document.form1.elements[i].checked = true;
          }
        }
      }
    }
  }

  function js_cancdescla (orcamento, licitacao) {
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cancdescla', 'lic1_pcorcamdesclacanc001.php?pc20_codorc=' + orcamento + '&l20_codigo=' + licitacao, 'Cancelamento da desclassificacao', true);
  }

  function js_casasdecimais (oCampo, iNumeroCaracteres = 2, oEvento) {

    var sValor = oCampo.value;

    if (sValor.getDecimalsLength() > iNumeroCaracteres) {
      oCampo.value = js_round(sValor, 2);
    }

    return false;
  }

  function processarValoresGlobais (valorTaxa) {

    var tam = document.form1.elements.length;

    for (var iElemento = 0; iElemento < tam; iElemento++) {

      var elemento = document.form1.elements[iElemento];
      var splitElemento = elemento.name.split('_');
      var codigoItem = splitElemento[1];
      if (splitElemento[0] === 'txhomologada') {

        elemento.value = valorTaxa;
        aplicarFormula(codigoItem);
      }
    }
  }

  function getValoresEstimados () {

    if (licitacaoDeMenorTaxa === false) {
      return false;
    }

    parent.document.form1.importar.click();
    parent.document.form1.importar.style.display = 'none';
    parent.document.form1.zerar.style.display = 'none';

    var itensFornecedor = itens.split('#');
    var parametros = {
      'exec': 'getOrcamentoProcessoCompra',
      'codigoLicitacao': codigoLicitacao,
      'codigoFornecedor': codigoFornecedor
    };

    AjaxRequest.create(
      'lic4_propostas.RPC.php',
      parametros,
      function (retorno, erro) {

        itensFornecedor.each(
          function (dadosInput) {

            var dadosInputEstimado = dadosInput.split('|');
            retorno.valoresEstimados.each(
              function (valorOrcamento) {

                if (Number(dadosInputEstimado[0]) === Number(valorOrcamento.codigoItemProcesso)) {

                  var dadosItem = dadosInputEstimado[1].split('_');

                  var elementValorUnitario = document.getElementById('vlrun_' + dadosItem[1]);

                  if (valorOrcamento.valorEstimado > 0) {

                    elementValorUnitario.value = valorOrcamento.valorEstimado;
                    $(dadosInputEstimado[1]).value = valorOrcamento.valorEstimado;
                    aplicarFormula(dadosItem[1]);
                  }
                }

              }
            );
          }
        );
      }
    ).setMessage('Aguarde, carregando informações do orçamento...').execute();
  }


  function processarTaxaHomologada (elemento, loteDescricao) {

    var inputValorItem = elemento.name.split('_');
    var codigoItem = inputValorItem[1];
    aplicarFormula(codigoItem);

    if (loteDescricao === undefined || loteDescricao.trim() === '') {
      return false;
    }

    var lotesEncontrados = lotes.split('#');
    lotesEncontrados.each(
      function (dadosLote) {

        var splitLote = dadosLote.split('|');
        if (String(loteDescricao) === String(splitLote[0])) {

          document.getElementById('txhomologada_' + splitLote[1]).value = elemento.value;
          aplicarFormula(splitLote[1]);
        }
      }
    );
  }

  function aplicarFormula (codigoItem) {

    var taxaHomologada = Number(document.getElementById('txhomologada_' + codigoItem).value).toFixed(2);
    var valorEstimado = Number(document.getElementById('vlrestimado_' + codigoItem).value).toFixed(2);
    var elementoValorUnitario = document.getElementById('vlrun_' + codigoItem);
    if (valorEstimado > 0) {

      elementoValorUnitario.value = Number(( valorEstimado * (1 + (taxaHomologada / 100)) ));
      var event = new Event('change');
      elementoValorUnitario.dispatchEvent(event);
    }
  }

  var elementosInputValorUnitario = $$('td.inputsValorUnitario > input');
  elementosInputValorUnitario.each(
    function (inputElement) {

      var inputSplit = inputElement.name.split('_');
      var codigoItem = inputSplit[1];

      inputElement.oninput = function () {

        var evento = new Event('input');
        js_ValidaCampos(inputElement, 4,'','','',evento);
        if (licitacaoDeMenorTaxa) {
          document.getElementById('txhomologada_'+codigoItem).value = '';
        }

      };
    }
  );
  getValoresEstimados();
</script>
</body>
</html>
