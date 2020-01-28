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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once(modification("dbforms/db_funcoes.php"));

$aMatriculasNoLote = array();
$iAnoAtual = db_getsession('DB_anousu');

db_inicio_transacao();
$temEsquemaSetado = !empty($_GET["schema"]);
if (!$temEsquemaSetado) {

    $sSqlCalculo = "select fc_calculoiptu({$parametro}::integer,{$iAnoAtual}::integer,true::boolean,false::boolean,false::boolean,false::boolean,false::boolean,array['0','0','0'])";
    $rsCalculo = db_query($sSqlCalculo);
}

$oDaoMatricula = new cl_iptubase();
$sSqlMatricula = $oDaoMatricula->sql_query_file($parametro);
$rsMatricula = db_query($sSqlMatricula);

if (!$rsMatricula || pg_num_rows($rsMatricula) == 0) {

    echo "Matrícula {$parametro} não encontrada na importação.";
    exit;
}
$oDadosMatricula = db_utils::fieldsMemory($rsMatricula, 0);
$caracteristicasAtuais = getCarateristicasdoLote($oDadosMatricula->j01_idbql);
$caracteristicasImoveisAtual = getCarateristicasdosImoveisDaMatricula($parametro);
if ($temEsquemaSetado) {

    db_query("set search_path=public,{$_GET['schema']}");

    $oDaoAtualizacaoIptuMatriculas = new cl_atualizacaoiptuschemamatricula();
    $aMatriculasNoLote = $oDaoAtualizacaoIptuMatriculas->matriculaNoLoteDaImportacao($_GET["schema"],
        $oDadosMatricula->j01_idbql);
}


/**
 * Retorna todas as caracteristicas do Lote
 * @param $idbql
 * @return array
 */
function getCarateristicasdoLote($idbql)
{


    $sSqlCaractetiscasLote = "select j32_grupo, j32_descr, caracteristicas.*
                                  from cargrup 
                                  left join ( select caracter.*
                                                from caracter
                                                    inner join carlote on j35_caract = j31_codigo
                                              where j35_idbql = {$idbql}) as caracteristicas on j31_grupo = j32_grupo
                                 where j32_tipo = 'L'                                  
                                order by j32_grupo";

    $rsCaracteristica = db_query($sSqlCaractetiscasLote);
    $iTotalLinhas = pg_num_rows($rsCaracteristica);
    $caracteristicas = array();

    for ($contador = 0; $contador < $iTotalLinhas; $contador++) {

        $dadosCaracteristica = db_utils::fieldsMemory($rsCaracteristica, $contador);
        $caracteristicas[$dadosCaracteristica->j32_grupo] = $dadosCaracteristica;
    }
    return $caracteristicas;
}

/**
 * Retorna todas as caracteristicas do Lote
 * @param $idbql
 * @return array
 */
function getCarateristicasdosImoveisDaMatricula($matricula, $idConstr = null)
{


    $sSqlCaractetiscasLote  = "select j32_grupo, j32_descr, caracteristicas.* ";
    $sSqlCaractetiscasLote .= "  from cargrup                                 ";
    $sSqlCaractetiscasLote .= "  left join ( select caracter.*, j48_idcons      ";
    $sSqlCaractetiscasLote .= "                from caracter                      ";
    $sSqlCaractetiscasLote .= "                    inner join carconstr on j48_caract = j31_codigo";
    $sSqlCaractetiscasLote .= "                                        and j48_matric = {$matricula}";
    if (!empty($idConstr)) {
        $sSqlCaractetiscasLote .= " and j48_idcons = {$idConstr}";
    }
    $sSqlCaractetiscasLote .= "             ) as caracteristicas on j31_grupo = j32_grupo";
    $sSqlCaractetiscasLote .= "  where j32_tipo = 'C'                                  ";
    $sSqlCaractetiscasLote .= " order by j48_idcons, j32_grupo";

    $rsCaracteristica = db_query($sSqlCaractetiscasLote);
    $iTotalLinhas = pg_num_rows($rsCaracteristica);
    $caracteristicas = array();

    for ($contador = 0; $contador < $iTotalLinhas; $contador++) {

        $dadosCaracteristica = db_utils::fieldsMemory($rsCaracteristica, $contador);
        if (empty($dadosCaracteristica->j48_idcons)) {
            $dadosCaracteristica->j48_idcons = $idConstr;
        }
        if (empty($caracteristicas[$dadosCaracteristica->j48_idcons])) {
            $caracteristicas[$dadosCaracteristica->j48_idcons] = array();
        }
        $caracteristicas[$dadosCaracteristica->j48_idcons][$dadosCaracteristica->j32_grupo] = $dadosCaracteristica;
    }
    if (!empty($idConstr)) {
        return $caracteristicas[$idConstr];
    }
    return $caracteristicas;
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <style>
        table#dadosCalculo td:nth-child(2n+2) {
            width: 20%;
            text-align: right;
        }

        .aviso {

            font-weight: bold;
            text-align: left;
            background-color: #fcf8e3 !important;
            border: 1px solid #fcc888 !important;
            padding: 10px
        }

        .caracteristica_alterada {

            font-weight: bold;
            background-color: #ffeb82 !important;
        }

        table.caracteristicas {
            border-collapse: collapse;
        }

        table.caracteristicas th {
            border: 1px solid #000000;
            font-weight: bold;
            background-color: #999999;
            color: #000000;
        }

        table.caracteristicas td {
            border: 1px solid #000000;
            color: #000000;
        }
    </style>
</head>
<body>

<?php

$sSqlDadosCalculo = " select distinct iptucalc.j23_anousu ,iptucalc.j23_matric, iptucalc.j23_testad, iptucalc.j23_arealo ";
$sSqlDadosCalculo .= "        ,iptucalc.j23_areafr, iptucalc.j23_areaed, iptucalc.j23_m2terr, iptucalc.j23_vlrter ";
$sSqlDadosCalculo .= "        ,iptucalc.j23_aliq, iptucalc.j23_vlrisen ";
$sSqlDadosCalculo .= "        ,sum(j22_valor) as j22_valor ";
$sSqlDadosCalculo .= "   from iptucalc ";
$sSqlDadosCalculo .= "   left outer join iptucale on j22_matric = j23_matric and j22_anousu = j23_anousu ";
$sSqlDadosCalculo .= "  where j23_matric = {$parametro} and j23_anousu = {$iAnoAtual} ";
$sSqlDadosCalculo .= "  group by iptucalc.j23_anousu, iptucalc.j23_matric, iptucalc.j23_testad, iptucalc.j23_arealo, ";
$sSqlDadosCalculo .= "           iptucalc.j23_areafr, iptucalc.j23_areaed, iptucalc.j23_m2terr, iptucalc.j23_vlrter,  ";
$sSqlDadosCalculo .= "           iptucalc.j23_aliq, iptucalc.j23_vlrisen ";
$sSqlDadosCalculo .= "  order by iptucalc.j23_anousu desc ";

$result = db_query($sSqlDadosCalculo);

if (!$result) {

    echo "<h3> Erro ao buscar dados do cálculo.</h3>";
    exit;
}

if (pg_numrows($result) == 0) {

    echo "<h3>Sem cálculo de IPTU para matrícula {$parametro} no ano de {$iAnoAtual}.</h3>";
    //exit;
}
?>
<div class='subcontainer' style="width:100%">
<?
if (pg_numrows($result) != 0) {

    $oDadosCalculo = db_utils::fieldsMemory($result, 0);
    ?>
        <fieldset>

            <legend>
                <b>Cálculo</b>
            </legend>
            <table id='dadosCalculo'>
                <tr>
                    <td nowrap class='bold'>Testada do cálculo:</td>
                    <td> <?= $oDadosCalculo->j23_testad ?></td>
                    <td class="field-size2"></td>
                    <td nowrap class='bold'>Área do lote para cálculo:</td>
                    <td> <?= db_formatar($oDadosCalculo->j23_arealo, 'f') ?> </td>
                </tr>
                <tr>
                    <td nowrap class='bold'>Fração de cálculo:</td>
                    <td> <?= $oDadosCalculo->j23_areafr ?> </td>
                    <td class="field-size2"></td>
                    <td nowrap class='bold'>Área edificada:</td>
                    <td> <?= $oDadosCalculo->j23_areaed ?> </td>
                </tr>
                <tr>
                    <td nowrap class='bold'>V0:</td>
                    <td> <?= db_formatar($oDadosCalculo->j23_m2terr, 'f') ?> </td>
                    <td class="field-size2"></td>
                    <td nowrap class='bold'>Valor Venal Terreno:</td>
                    <td> <?= db_formatar($oDadosCalculo->j23_vlrter, 'f') ?> </td>
                </tr>
                <tr>
                    <td nowrap class='bold'>Alíquota:</td>
                    <td> <?= db_formatar($oDadosCalculo->j23_aliq, 'f') ?> </td>
                    <td class="field-size2"></td>
                    <td nowrap class='bold'>Valor Venal Edificações:</td>
                    <td> <?= db_formatar($oDadosCalculo->j22_valor, 'f') ?> </td>
                </tr>
                <tr>
                    <td colspan="3" class="field-size2"></td>
                    <td nowrap class='bold'>Valor Venal:</td>
                    <td> <?= db_formatar(($oDadosCalculo->j23_vlrter + $oDadosCalculo->j22_valor), 'f') ?></td>
                </tr>
            </table>


            <?php
            /**
             * busca as taxas
             */
            $soma = 0;
            $somacalc = 0;
            $somaisen = 0;
            $iSomaPercentualIsencao = 0;

            $sql2 = "select k02_codigo, k02_descr, j17_codhis, j17_descr, j21_valor, ";
            $sql2 .= "       case ";
            $sql2 .= "         when iptucalhconf.j89_codhis is not null then ";
            $sql2 .= "           (select sum(x.j21_valor) ";
            $sql2 .= "					    from iptucalv x ";
            $sql2 .= "						 where x.j21_anousu = iptucalv.j21_anousu ";
            $sql2 .= "						   and x.j21_matric = iptucalv.j21_matric ";
            $sql2 .= "							 and x.j21_receit = iptucalv.j21_receit ";
            $sql2 .= "							 and x.j21_codhis = iptucalhconf.j89_codhis) ";
            $sql2 .= "         else 0  ";
            $sql2 .= "       end as j21_valorisen ";
            $sql2 .= "  from iptucalv ";
            $sql2 .= "       inner join iptucalh        on iptucalh.j17_codhis        = j21_codhis ";
            $sql2 .= "       left  join iptucalhconf    on iptucalhconf.j89_codhispai = j21_codhis ";
            $sql2 .= "       inner join tabrec          on tabrec.k02_codigo          = j21_receit ";
            $sql2 .= "       left  join iptucadtaxaexe  on iptucadtaxaexe.j08_tabrec  = j21_receit ";
            $sql2 .= "                                 and iptucadtaxaexe.j08_anousu  = $iAnoAtual ";
            $sql2 .= " where j21_matric = {$parametro} ";
            $sql2 .= "   and j21_anousu = {$iAnoAtual} ";
            $sql2 .= "   and j17_codhis not in (select j89_codhis from iptucalhconf) ";
            $sql2 .= " order by iptucalh.j17_codhis  ";
            $result2 = db_query($sql2);
            ?>
            <table width="100%" border="0" cellspacing="2" cellpadding="0" class='tab_cinza'>
                <tr>
                    <th width="9%" nowrap> Receita</th>
                    <th width="40%" nowrap> Descrição</th>
                    <th width="13%" nowrap> Valor Calculado</th>
                    <th width="13%" nowrap> Valor Isenção</th>
                    <th width="13%" nowrap> Isenção (%)</th>
                    <th width="12%" nowrap> Saldo a pagar</th>
                </tr>
                <?php
                for ($contador2 = 0; $contador2 < pg_numrows($result2); $contador2++) {

                    db_fieldsmemory($result2, $contador2);

                    $soma = $soma + ($j21_valor - abs($j21_valorisen));
                    $somacalc = $somacalc + $j21_valor;
                    $somaisen = $somaisen + $j21_valorisen;
                    $iPercentualIsencao = (100 * $j21_valorisen) / $j21_valor;
                    ?>
                    <tr>
                        <td width="9%" nowrap>
                            <?= $k02_codigo ?>
                        </td>
                        <td width="40%" nowrap>
                            <?= substr($j17_descr, 0, 20) ?>
                        </td>
                        <td width="7%" align="right" nowrap>
                            <?= db_formatar($j21_valor, 'f') ?>
                        </td>
                        <td width="7%" align="right" nowrap>
                            <?= db_formatar($j21_valorisen, 'f') ?>
                        </td>
                        <td width="7%" align="right" nowrap>
                            <?= db_formatar(abs($iPercentualIsencao), 'f') ?>
                        </td>
                        <td width="16%" align="right" nowrap>
                            <?= db_formatar(($j21_valor - abs($j21_valorisen)), 'f') ?>
                        </td>
                    </tr>
                    <?
                }
                $iSomaPercentualIsencao = (100 * $somaisen) / $somacalc;
                ?>
                <tr>
                    <th colspan="2" align="right" nowrap>TOTAL :
                    </th>
                    <td width="13%" align="right" nowrap>
                        <strong><?= db_formatar($somacalc, 'f') ?></strong>
                    </td>
                    <td width="13%" align="right" nowrap>
                        <strong><?= db_formatar($somaisen, 'f') ?></strong>
                    </td>
                    <td width="13%" align="right" nowrap>
                        <strong><?= db_formatar(abs($iSomaPercentualIsencao), 'f') ?></strong>
                    </td>
                    <td width="12%" align="right" nowrap>
                        <strong><?= db_formatar($soma, 'f') ?></strong>
                    </td>
                </tr>

            </table>
        </fieldset>
    <?
    }
    ?>
        <div id="ctnAbas"></div>
        <div id='aba2'>
            <fieldset>

                <table width="95%" border="0" align="center" cellpadding="1" cellspacing="2" class='caracteristicas'>

                    <tr align="center" bgcolor="#CCCCCC">
                        <th align="left" nowrap>Grupo</th>
                        <th align="left" nowrap>Descri&ccedil;&atilde;o</th>
                        <th nowrap>C&oacute;digo</th>
                        <th align="left" nowrap>Descri&ccedil;&atilde;o</th>
                        <th align="left" nowrap>Pontos</th>
                    </tr>
                    <?php

                    $caracteristicas = $caracteristicasAtuais;
                    if ($temEsquemaSetado) {
                        $caracteristicas = getCarateristicasdoLote($oDadosMatricula->j01_idbql);
                    }

                    foreach ($caracteristicas as $dadosCaracteristica) {

                        $estiloCaracteristica = '';
                        $grupoCaracteristica = $caracteristicasAtuais[$dadosCaracteristica->j32_grupo];
                        if (empty($grupoCaracteristica)) {
                            $estiloCaracteristica = 'caracteristica_alterada';
                        }
                        if (!empty($grupoCaracteristica) && $grupoCaracteristica->j31_codigo != $dadosCaracteristica->j31_codigo) {
                            $estiloCaracteristica = 'caracteristica_alterada';
                        }
                        ?>
                        <tr align="center" class="<?= $estiloCaracteristica ?>">

                            <td width="34" align="right" nowrap>
                                <?= $dadosCaracteristica->j32_grupo ?>
                            </td>
                            <td width="215" align="left" nowrap>
                                <?= substr($dadosCaracteristica->j32_descr, 0, 30) ?>
                            </td>
                            <td width="51">
                                <?= $dadosCaracteristica->j31_codigo ?>

                            </td>
                            <td width="171" align="left" nowrap>
                                <?= substr($dadosCaracteristica->j31_descr, 0, 20) ?>
                                &nbsp;
                            </td>

                            <td width="40" align="right" nowrap>
                                <?= $dadosCaracteristica->j31_pontos ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </fieldset>
        </div>
        <div id="aba3">
            <fieldset>
                <legend>Construções</legend>

                <?php
                $sqlConstrucoes = "select distinct                  
                  j39_idcons as idcons,
                  j39_codigo as cod,                  
                  j39_area as area,
                  j39_ano as ano,          
		              case
		                when j39_idprinc
		                  then 'Sim'
		                else 'Não'
		              end as principal,		              
		              j39_dtdemo as data_demolicacao,
		              j39_areap		              
                  from iptuconstr		              
	              where j39_matric = $parametro	            
		        order by j39_idcons  ";
                $rsConstrucoes = db_query($sqlConstrucoes);
                $totalLinhas = pg_num_rows($rsConstrucoes);
                if (!$rsConstrucoes && $totalLinhas == 0) {
                    echo " Sem construções.";
                } else {


                $aCaracteristicasConstrucaoMatricula = $caracteristicasImoveisAtual;

                $a = 1;
                for ($i = 0; $i < $totalLinhas; $i++) {


                    $dadosConstrucao = db_utils::fieldsMemory($rsConstrucoes, $i);
                    $aCaracteristicasConstrucao = $aCaracteristicasConstrucaoMatricula[$dadosConstrucao->idcons];
                    if ($temEsquemaSetado) {
                        $aCaracteristicasConstrucao = getCarateristicasdosImoveisDaMatricula($parametro, $dadosConstrucao->idcons);
                    }

                    ?>

                    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="2">
                        <tr align="center">

                            <td align="left" nowrap><b>Constru&ccedil;&atilde;o:</b>
                            </td>
                            <td width="30%" align="left" nowrap>
                                <?= $dadosConstrucao->idcons ?>
                            </td>
                            <td align="left" nowrap><b>Área:</b>
                            </td>
                            <td width="30%" align="left" nowrap>
                                <?= $dadosConstrucao->area ?>
                            </td>
                        </tr>
                        <tr align="center">

                            <td align="left" nowrap>
                                <b>Ano Construção:</b>
                            </td>
                            <td width="30%" align="left" nowrap>
                                <?= $dadosConstrucao->ano ?>
                            </td>
                            <td align="left" nowrap><b>Principal:</b>
                            </td>
                            <td width="30%" align="left" nowrap>
                                <?= $dadosConstrucao->principal ?>
                            </td>
                        </tr>
                        <tr align="center">

                            <td align="left" nowrap>
                                <b>Demolida:</b>
                            </td>
                            <td width="30%" align="left" nowrap>
                                <?= $dadosConstrucao->data_demolicacao != '' ? 'Sim' : 'Não' ?>
                            </td>
                            <td align="left" nowrap><b>Data da Demolicação:</b>
                            </td>
                            <td width="30%" align="left" nowrap>
                                <?= db_formatar($dadosConstrucao->data_demolicacao, 'd') ?>
                            </td>
                        </tr>
                    </table>

                    <fieldset class="separator">
                        <legend> Características</legend>
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1"
                               class='caracteristicas'>
                            <tr style="font-weight:bold">
                                <th align="center">Grupo</th>
                                <th align="center">Descrição</th>
                                <th width="17%" nowrap>Código</th>
                                <th width="50%" nowrap>&nbsp;Caracter&iacute;sticas</th>
                                <th width="25%" nowrap>Pontos</th>
                            </tr>
                            <?php

                            foreach ($aCaracteristicasConstrucao as $caracteristica) {

                                $estiloCaracteristica = '';
                                $grupoCaracteristica = null;
                                if (!empty($caracteristicasImoveisAtual[$dadosConstrucao->idcons][$caracteristica->j32_grupo])) {
                                    $grupoCaracteristica = $caracteristicasImoveisAtual[$dadosConstrucao->idcons][$caracteristica->j32_grupo];
                                }
                                if (empty($grupoCaracteristica)) {
                                    $estiloCaracteristica = 'caracteristica_alterada';
                                }
                                if (!empty($grupoCaracteristica) && $grupoCaracteristica->j31_codigo != $caracteristica->j31_codigo) {
                                    $estiloCaracteristica = 'caracteristica_alterada';
                                }
                                ?>
                                <tr class="<?=$estiloCaracteristica;?>">
                                    <td nowrap >
                                        <?= $caracteristica->j32_grupo ?>
                                    </td>
                                    <td nowrap >
                                        <?= $caracteristica->j32_descr ?>
                                    </td>
                                    <td  align="center" nowrap>
                                        <?= $caracteristica->j31_codigo ?>
                                    </td>
                                    <td nowrap>
                                        <?= substr($caracteristica->j31_descr, 0, 20) ?>
                                    </td>
                                    <td nowrap>
                                        <?= $caracteristica->j31_pontos ?>
                                    </td>
                                </tr>
                                <?
                            }
                            ?>
                        </table>
                    </fieldset>
                    <?
                }
                ?>
            </fieldset>
            <?
            }
            ?>
        </div>


        <?php
        if (count($aMatriculasNoLote) > 1) {

            $temMatriculaAlterada = false;
            $outrasMatriculas = array();
            foreach ($aMatriculasNoLote as $oMatricula) {

                if ($oMatricula->situacao == \ECidade\Tributario\Cadastro\Iptu\Recadastramento\Processamento::MATRICULA_NOVA) {
                    $temMatriculaAlterada = true;
                }
                if ($oMatricula->matricula != $parametro) {
                    $outrasMatriculas[] = $oMatricula->matricula;
                }
            }
            if ($temMatriculaAlterada) {

                $sMensagemMatricula = "<br><div class='aviso' >Esse lote possui uma nova matrícula pendente de análise. Poderão ocorrer alterações na ";
                $sMensagemMatricula .= " fração das demais matrículas do lote. <br> Outras Matrículas do lote: " . implode(", ",
                        $outrasMatriculas) . "</div>";
                echo $sMensagemMatricula;
            }
        }
        ?>
    </div>
    <?php

db_fim_transacao(true);
db_query("select fc_set_pg_search_path();");
?>
</body>
</html>
<script>
    var oAbas = new DBAbas($('ctnAbas'));
    var oAba2 = oAbas.adicionarAba('Características do Lote', $('aba2'));
    var oAba3 = oAbas.adicionarAba('Características das Construções', $('aba3'));
</script>
