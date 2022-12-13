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
include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_utils.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rhpessoal_classe.php"));
include(modification("classes/db_rhfuncao_classe.php"));
include(modification("classes/db_rhpescargo_classe.php"));
include(modification("classes/db_rhrubricas_classe.php"));

//@todo solução provisória para ser possível utilizar a rotina.
ini_set('memory_limit', -1);

$aFolhasUtilizadas = array(
  "r14" => "gerfsal",
  "r22" => "gerfadi",
  "r48" => "gerfcom",
  "r20" => "gerfres",
  "r35" => "gerfs13"
);

$clrhpessoal = new cl_rhpessoal;
$clrhfuncao = new cl_rhfuncao;
$clrhpescargo = new cl_rhpescargo;
$clrhrubricas = new cl_rhrubricas;
$clrotulo = new rotulocampo;

parse_str($_SERVER['QUERY_STRING']);
db_postmemory($HTTP_GET_VARS);
$oGet = db_utils::postMemory($_GET);

$mesi = str_pad($mesi, 2, "0", STR_PAD_LEFT);
$arr_verifica_ano_mes_regist = array();
$arr_mostrar = array();
$aTotaisRub = array();

if ($orde == "a") {
    $orderby = " z01_nome ";
    $orderby2 = "nomefc,regist,anousu,mesusu,rubric";
} else {
    $orderby = " rh01_regist ";
    $orderby2 = "regist,anousu,mesusu,rubric";
}

$db_where_rubricas = "";
$virg_das_rubricas = "";
$impressao_rubricas = false;
if (trim($rubricas_selecionadas_text) != "") {
    $impressao_rubricas = true;
    $arr_das_rubricas = split(",", $rubricas_selecionadas_text);
    for ($i = 0; $i < count($arr_das_rubricas); $i++) {
        $db_where_rubricas .= $virg_das_rubricas . "'" . $arr_das_rubricas[$i] . "'";
        $virg_das_rubricas = ",";
    }
    $db_where_rubricas = " rh27_rubric in (" . $db_where_rubricas . ") ";
} else {
    if (isset($rh27_rubric1) && isset($rh27_rubric2) && (trim($rh27_rubric1) != "" || trim($rh27_rubric2) != "")) {
        $impressao_rubricas = true;
        if (trim($rh27_rubric1) != "" && trim($rh27_rubric2) != "") {
            $db_where_rubricas = " rh27_rubric between '" . $rh27_rubric1 . "' and '" . $rh27_rubric2 . "' ";
        } else {
            if (trim($rh27_rubric1) != "") {
                $db_where_rubricas = " rh27_rubric >= '" . $rh27_rubric1 . "' ";
            } else {
                $db_where_rubricas = " rh27_rubric <= '" . $rh27_rubric2 . "' ";
            }
        }
    }
}

if ($mes_dados == 'p') {
    $cadastro_atual = null;
    $anomov = $anoi;
    $mesmov = $mesi;
} else {
    $cadastro_atual = true;
    $anomov = db_anofolha();
    $mesmov = db_mesfolha();
}

$db_where_matriculas = "";

$sp_where_matriculas = "";
$virg_das_matriculas = "";
if (trim($matriculas_selecionadas_text) != "") {
    $arr_das_matriculas = split(",", $matriculas_selecionadas_text);
    for ($i = 0; $i < count($arr_das_matriculas); $i++) {
        $sp_where_matriculas .= $virg_das_matriculas . $arr_das_matriculas[$i];
        $virg_das_matriculas = ",";
    }

    $db_where_matriculas .= $sp_where_matriculas == "false" ? "" : "rh01_regist in ({$sp_where_matriculas}) ";
} else {
    if (isset($rh01_regist1) && isset($rh01_regist2) && (trim($rh01_regist1) != "" || trim($rh01_regist2) != "")) {
        if (trim($rh01_regist1) != "" && trim($rh01_regist2) != "") {
            $db_where_matriculas .= " rh01_regist between " . $rh01_regist1 . " and " . $rh01_regist2;
        } else {
            if (trim($rh01_regist1) != "") {
                $db_where_matriculas .= " rh01_regist >= " . $rh01_regist1;
            } else {
                $db_where_matriculas .= " rh01_regist <= " . $rh01_regist2;
            }
        }
    }
}

// SQL para criar tabela temporária de auxilio
$sql_create_temporary_table = "create temp table                            ";
$sql_create_temporary_table .= "       work_ficha_financ (                   ";
$sql_create_temporary_table .= "                          anousu int4,       ";
$sql_create_temporary_table .= "                          mesusu int4,       ";
$sql_create_temporary_table .= "                          regist int4,       ";
$sql_create_temporary_table .= "                          numcgm int4,       ";
$sql_create_temporary_table .= "                          nomefc varchar(80),";
$sql_create_temporary_table .= "                          lotaca int4,       ";
$sql_create_temporary_table .= "                          dlotac varchar(80),";
$sql_create_temporary_table .= "                          funcao int4,       ";
$sql_create_temporary_table .= "                          dfunca varchar(80),";
$sql_create_temporary_table .= "                          rubric varchar(4), ";
$sql_create_temporary_table .= "                          drubri varchar(80),";
$sql_create_temporary_table .= "                          quanti float8,     ";
$sql_create_temporary_table .= "                          proven float8,     ";
$sql_create_temporary_table .= "                          descon float8,     ";
$sql_create_temporary_table .= "                          basesr float8,     ";
$sql_create_temporary_table .= "                          tabela varchar(20),";
$sql_create_temporary_table .= "                          pd_grupo int4      ";
$sql_create_temporary_table .= "                         )                   ";

// SQL para ndices
$sql_create_indexes_temp = " create index work_anousu on work_ficha_financ(anousu); ";
$sql_create_indexes_temp .= " create index work_mesusu on work_ficha_financ(mesusu); ";
$sql_create_indexes_temp .= " create index work_regist on work_ficha_financ(regist); ";


$result_create_temporary_table = db_query($sql_create_temporary_table);
$result_create_indexes_temp = db_query($sql_create_indexes_temp);

$iInstituicao = db_getsession("DB_instit");
$iSelecao = $oGet->r44_selec;

$sWhereSelecao = '';

if (!empty($iSelecao)) {
    $oDaoSelecao = db_utils::getDao('selecao');

    $sSqlSelecao = $oDaoSelecao->sql_query_file($iSelecao, $iInstituicao, 'r44_where');
    $rsSelecao = $oDaoSelecao->sql_record($sSqlSelecao);

    if ($oDaoSelecao->numrows > 0) {
        $oSelecao = db_utils::fieldsMemory($rsSelecao, 0);
        $sWhereSelecao = "{$oSelecao->r44_where}";
    }
}

$testa = false;
$aSubQueryTabelasCalculo = array();

foreach ($aFolhasUtilizadas as $sSigla => $sTabela) {
    $sQuery = "sql_query_baseRelatorios";

    $sCamposTabelasCalculo = "{$sSigla}_anousu as anousu,                                             \n";
    $sCamposTabelasCalculo .= "{$sSigla}_mesusu as mesusu,                                             \n";
    $sCamposTabelasCalculo .= "{$sSigla}_regist as regist,                                             \n";
    $sCamposTabelasCalculo .= "{$sSigla}_rubric as rubric,                                             \n";
    $sCamposTabelasCalculo .= "rh27_descr,                                                             \n";
    $sCamposTabelasCalculo .= "case when {$sSigla}_pd = 1 then {$sSigla}_valor else 0 end as provento, \n";
    $sCamposTabelasCalculo .= "case when {$sSigla}_pd = 2 then {$sSigla}_valor else 0 end as desconto, \n";
    $sCamposTabelasCalculo .= "case when {$sSigla}_pd = 3 then {$sSigla}_valor else 0 end as basesr,   \n";
    $sCamposTabelasCalculo .= "case when {$sSigla}_pd <> 3 then 1 else 2 end as pd_grupo,              \n";
    $sCamposTabelasCalculo .= "rh02_seqpes      as seqpes,                                             \n";
    $sCamposTabelasCalculo .= "z01_numcgm       as numcgm,                                             \n";
    $sCamposTabelasCalculo .= "z01_nome         as nomefc,                                             \n";
    $sCamposTabelasCalculo .= "r70_codigo       as lotaca,                                             \n";
    $sCamposTabelasCalculo .= "r70_descr        as dlotac,                                             \n";
    $sCamposTabelasCalculo .= "rh02_funcao      as funcao,                                             \n";
    $sCamposTabelasCalculo .= "rh37_descr       as dfunca,                                             \n";
    $sCamposTabelasCalculo .= "{$sSigla}_quant,                                                        \n";
    $sCamposTabelasCalculo .= "'{$sTabela}' as tabela                                                  \n";

    $sWhereAnoMesUsu       = " fc_anousu_mesusu({$sSigla}_anousu, {$sSigla}_mesusu) between fc_anousu_mesusu($anoi, $mesi) and fc_anousu_mesusu($anof, $mesf) \n";
    $sWhereTabelasCalculo  = $db_where_rubricas == "" ? "" : " and " . $db_where_rubricas;
    $sWhereTabelasCalculo .= $db_where_matriculas == "" ? "" : " and " . $db_where_matriculas;
    if ($mes_dados == 'a') {
        $sWhereTabelasCalculo .= " and rh02_anousu = $anomov and rh02_mesusu = $mesmov ";
    }
    $sWhereTabelasCalculo .= $sWhereSelecao == "" ? "" : " and " . $sWhereSelecao;

    /**
     * Ignora a gerfsal caso seja utilizado a Suplemnentar e o servidor possua cálculo de rescisão.
     */
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
        if ($sTabela == 'gerfsal') {
            $sWhereTabelasCalculo .= " and not exists (select 1                        ";
            $sWhereTabelasCalculo .= "                   from gerfres                  ";
            $sWhereTabelasCalculo .= "                  where r20_anousu = r14_anousu  ";
            $sWhereTabelasCalculo .= "                    and r20_mesusu = r14_mesusu  ";
            $sWhereTabelasCalculo .= "                    and r20_regist = r14_regist  ";
            $sWhereTabelasCalculo .= "                    and r20_instit = r14_instit) ";
        }

        if($sTabela === 'gerfcom') {
            $sCamposTabelasCalculo = estruturaSumplementar();
            $sQuery                = "sql_query_baseRelatoriosComplementar";
            $sWhereAnoMesUsu       = " fc_anousu_mesusu(rh141_anousu, rh141_mesusu) between fc_anousu_mesusu($anoi, $mesi) and fc_anousu_mesusu($anof, $mesf) \n";
            $sWhereAnoMesUsu      .= " AND rh141_tipofolha = 3 \n";
            $sTabela               = null;
            $sSigla                = null;
        }
    }

    $sWhereGeral = $sWhereAnoMesUsu . $sWhereTabelasCalculo;

    $aSubQueryTabelasCalculo[] = $clrhpessoal->{$sQuery}(
        $sTabela,
        $sSigla,
        null,
        null,
        $iInstituicao,
        $sCamposTabelasCalculo,
        $sWhereGeral,
        null,
        $cadastro_atual
    );
}

$sql_dados_gerfs = " select anousu,                                \n";
$sql_dados_gerfs .= "        mesusu,                                \n";
$sql_dados_gerfs .= "        regist,                                \n";
$sql_dados_gerfs .= "        numcgm,                                \n";
$sql_dados_gerfs .= "        nomefc,                                \n";
$sql_dados_gerfs .= "        lotaca,                                \n";
$sql_dados_gerfs .= "        dlotac,                                \n";
$sql_dados_gerfs .= "        funcao,                                \n";
$sql_dados_gerfs .= "        dfunca,                                \n";
$sql_dados_gerfs .= "        rubric,                                \n";
$sql_dados_gerfs .= "        rh27_descr as drubri,                  \n";
$sql_dados_gerfs .= "        coalesce(sum(provento),0)  as proven,  \n";
$sql_dados_gerfs .= "        coalesce(sum(desconto),0)  as descon,  \n";
$sql_dados_gerfs .= "        coalesce(sum(basesr),0)    as basesr,  \n";
$sql_dados_gerfs .= "        coalesce(sum(r14_quant),0) as quanti,  \n";
$sql_dados_gerfs .= "        tabela,                                \n";
$sql_dados_gerfs .= "        pd_grupo                               \n";
$sql_dados_gerfs .= "  from (                                        ";

foreach ($aSubQueryTabelasCalculo as $iIndice => $sSubQueryTabelasCalculo) {
    if ($iIndice > 0) {
        $sql_dados_gerfs .= "\n union \n";
    }

    $sql_dados_gerfs .= $sSubQueryTabelasCalculo;
}

$sql_dados_gerfs .= " ) as x               \n";
$sql_dados_gerfs .= " group by anousu,     \n";
$sql_dados_gerfs .= "          mesusu,     \n";
$sql_dados_gerfs .= "          regist,     \n";
$sql_dados_gerfs .= "          rubric,     \n";
$sql_dados_gerfs .= "          numcgm,     \n";
$sql_dados_gerfs .= "          nomefc,     \n";
$sql_dados_gerfs .= "          lotaca,     \n";
$sql_dados_gerfs .= "          dlotac,     \n";
$sql_dados_gerfs .= "          funcao,     \n";
$sql_dados_gerfs .= "          dfunca,     \n";
$sql_dados_gerfs .= "          rh27_descr, \n";
$sql_dados_gerfs .= "          tabela,     \n";
$sql_dados_gerfs .= "          pd_grupo    \n";
$sql_dados_gerfs .= " order by $orderby2,  \n";
$sql_dados_gerfs .= "          anousu,     \n";
$sql_dados_gerfs .= "          mesusu,     \n";
$sql_dados_gerfs .= "          pd_grupo,   \n";
$sql_dados_gerfs .= "          rubric      \n";

$aMatriculasProcessadas = array();
$result_dados_gerfs = db_query($sql_dados_gerfs);

$numrows_dados_gerfs = pg_numrows($result_dados_gerfs);
$mes_anterior = "";
$ano_anterior = "";
$contaIarray = 1;
$contamesano = 0;
$aServidores = array();

for ($ii = 0; $ii < $numrows_dados_gerfs; $ii++) {
    db_fieldsmemory($result_dados_gerfs, $ii);

    $oDadosServidor = new stdClass();
    $oDadosServidor->regist = $regist;
    //$oDadosServidor->seqpes                = $seqpes;
    $oDadosServidor->funcao = $funcao;
    $oDadosServidor->dfunca = $dfunca;
    $oDadosServidor->nomefc = $nomefc;
    $oDadosServidor->lotaca = $lotaca;
    $oDadosServidor->dlotac = $dlotac;
    $aServidores[$regist] = $oDadosServidor;

    if (!in_array($regist, $aMatriculasProcessadas)) {
        $aMatriculasProcessadas[] = $regist;
        $mes_anterior = "";
        $ano_anterior = "";
        $contaIarray = 1;
        $contamesano = 0;
    }

    $sIdRegistro = $regist . "_" . $anousu . "_" . $mesusu;
    $sWhereMesAno = " where anousu||lpad(mesusu,2,0) = '" . $anousu . str_pad($mesusu, 2, "0", STR_PAD_LEFT) . "' ";
    $sWhereMostrar = $anousu . str_pad($mesusu, 2, "0", STR_PAD_LEFT);
    $iCompetencia = $anousu . str_pad($mesusu, 2, "0", STR_PAD_LEFT);

    if (!in_array($sIdRegistro, $arr_verifica_ano_mes_regist) && !$impressao_rubricas) {
        $arr_verifica_ano_mes_regist[$sIdRegistro] = $sIdRegistro;
        // Monta WHERE de para selects
        $contamesano++;

        if ($contamesano == 1) {
            $arr_mostrar[$regist][$contaIarray]  = $anousu . str_pad($mesusu, 2, "0", STR_PAD_LEFT);
            $arr_mes_ano[$regist][$contaIarray]  = "  where anousu||lpad(mesusu,2,0) = '" . $anousu;
            $arr_mes_ano[$regist][$contaIarray] .= str_pad($mesusu, 2, "0", STR_PAD_LEFT) . "' ";
        } else {
            if ($contamesano == 2) {
                $arr_mostrar[$regist][$contaIarray] .= "_" . $anousu . str_pad($mesusu, 2, "0", STR_PAD_LEFT);
                $arr_mes_ano[$regist][$contaIarray] .= "_ where anousu||lpad(mesusu,2,0) = '" . $anousu;
                $arr_mes_ano[$regist][$contaIarray] .= str_pad($mesusu, 2, "0", STR_PAD_LEFT) . "' ";
            } else {
                if ($contamesano == 3) {
                    $arr_mostrar[$regist][$contaIarray] .= "_" . $anousu . str_pad($mesusu, 2, "0", STR_PAD_LEFT);
                    $arr_mes_ano[$regist][$contaIarray] .= "_ where anousu||lpad(mesusu,2,0) = '" . $anousu;
                    $arr_mes_ano[$regist][$contaIarray] .= str_pad($mesusu, 2, "0", STR_PAD_LEFT) . "' ";
                    $contamesano = 0;
                    $contaIarray++;
                }
            }
        }
    }

    $testa = true;
    $sql_insert_na_temporary_table = "
    insert into
    work_ficha_financ (
      anousu,
      mesusu,
      regist,
      numcgm,
      nomefc,
      lotaca,
      dlotac,
      funcao,
      dfunca,
      rubric,
      drubri,
      quanti,
      proven,
      descon,
      basesr,
      tabela,
      pd_grupo
    )
    values
    (
      $anousu,
      $mesusu,
      $regist,
      $numcgm,
      '" . $nomefc . "',
      $lotaca,
      '" . $dlotac . "',
      $funcao,
      '" . $dfunca . "',
      '" . $rubric . "',
      '" . $drubri . "',
      $quanti,
      $proven,
      $descon,
      $basesr,
      '" . $tabela . "',
      $pd_grupo
    );
  ";

    $result_insert_na_temporary_table = db_query($sql_insert_na_temporary_table);
    if ($result_insert_na_temporary_table == false) {
        exit;
    }
}

if ($testa == false) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum resultado encontrado.');
    exit;
}

if ($orde == "a") {
    $HEAD7 = "alfabética";
} else {
    $HEAD7 = "numérica";
}

$head4 = "RELATÓRIO DE FICHA FINANCEIRA";
$head5 = "Dados: " . ($mes_dados == "p" ? "Periodo Inicial" : "Atual");
$head6 = "Período entre " . $anoi . "/" . $mesi . " e " . $anof . "/" . $mesf;
$head7 = "Ordem " . $HEAD7;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;
$troca = 1;
$p = 1;
$alt = 4;

if ($impressao_rubricas == false) {
    // IMPRESSO PARA QUANDO NO FOR INFORMADA NENHUMA RUBRICA

    $colunas = 1;

    // For para buscar dados dos funcionrios
    foreach ($aServidores as $oServidor) {
        $imprimir_cabecalho_registros = true;

        for ($ii = 1; $ii <= count($arr_mes_ano[$oServidor->regist]); $ii++) {
            if (isset($arr_mes_ano[$oServidor->regist][$ii]) && trim($arr_mes_ano[$oServidor->regist][$ii]) == "" || !isset($arr_mes_ano[$oServidor->regist][$ii])) {
                continue;
            }

            $anos_meses = $arr_mostrar[$oServidor->regist][$ii];
            $arr_wheres = split("_", $arr_mes_ano[$oServidor->regist][$ii]);
            $sql_dados_work_ficha_financ = "";

            // For para montar os SQL's
            $sql_dados_work_ficha_financ = "
                                      select *
                                      from (

                                            select
                                                   distinct on (rubric1) rubric1,
                                                   drubri1,
                                                   sum(anousu1) as anousu1,
                                                   sum(mesusu1) as mesusu1,
                                                   sum(anousu2) as anousu2,
                                                   sum(mesusu2) as mesusu2,
                                                   sum(anousu3) as anousu3,
                                                   sum(mesusu3) as mesusu3,
                                                   (case when rubric1 <> 'R984' then sum(qmes1) else max(qmes1) end) as qmes1,
                                                   (case when rubric1 <> 'R984' then sum(pmes1) else max(pmes1) end) as pmes1,
                                                   (case when rubric1 <> 'R984' then sum(dmes1) else max(dmes1) end) as dmes1,
                                                   (case when rubric1 <> 'R984' then sum(bmes1) else max(bmes1) end) as bmes1,
                                                   (case when rubric1 <> 'R984' then sum(qmes2) else max(qmes2) end) as qmes2,
                                                   (case when rubric1 <> 'R984' then sum(pmes2) else max(pmes2) end) as pmes2,
                                                   (case when rubric1 <> 'R984' then sum(dmes2) else max(dmes2) end) as dmes2,
                                                   (case when rubric1 <> 'R984' then sum(bmes2) else max(bmes2) end) as bmes2,
                                                   (case when rubric1 <> 'R984' then sum(qmes3) else max(qmes3) end) as qmes3,
                                                   (case when rubric1 <> 'R984' then sum(pmes3) else max(pmes3) end) as pmes3,
                                                   (case when rubric1 <> 'R984' then sum(dmes3) else max(dmes3) end) as dmes3,
                                                   (case when rubric1 <> 'R984' then sum(bmes3) else max(bmes3) end) as bmes3,
                                                   pd_grupo
                                            from (
                                     ";
            for ($iii = 0; $iii < count($arr_wheres); $iii++) {
                if ($iii != 0) {
                    $sql_dados_work_ficha_financ .= "   union all ";
                }

                // Veririca em qual posio deve colocar ZEROS
                $qpd23 = 1;
                if ($iii != 0 && ($iii + 1) % 2 == 0) {
                    $qpd23 = 2;
                } else {
                    if ($iii != 0 && ($iii + 1) % 3 == 0) {
                        $qpd23 = 3;
                    }
                }

                $sql_dados_work_ficha_financ .= "
                                          select
                                                 rubric as rubric1,
                                                 drubri as drubri1,
                                         ";
                if ($qpd23 == 1) {
                    $colunas = 1;
                    $sql_dados_work_ficha_financ .= "
                                                 anousu as anousu1,
                                                 mesusu as mesusu1,
                                                 0 as anousu2,
                                                 0 as mesusu2,
                                                 0 as anousu3,
                                                 0 as mesusu3,
                                                 quanti as qmes1,
                                                 proven as pmes1,
                                                 descon as dmes1,
                                                 basesr as bmes1,
                                                 0 as qmes2,
                                                 0 as pmes2,
                                                 0 as dmes2,
                                                 0 as bmes2,
                                                 0 as qmes3,
                                                 0 as pmes3,
                                                 0 as dmes3,
                                                 0 as bmes3,
                                                 pd_grupo
                                         ";
                } else {
                    if ($qpd23 == 2) {
                        $colunas = 2;
                        $sql_dados_work_ficha_financ .= "
                                                 0 as anousu1,
                                                 0 as mesusu1,
                                                 anousu as anousu2,
                                                 mesusu as mesusu2,
                                                 0 as anousu3,
                                                 0 as mesusu3,
                                                 0 as qmes1,
                                                 0 as pmes1,
                                                 0 as dmes1,
                                                 0 as bmes1,
                                                 quanti as qmes2,
                                                 proven as pmes2,
                                                 descon as dmes2,
                                                 basesr as bmes2,
                                                 0 as qmes3,
                                                 0 as pmes3,
                                                 0 as dmes3,
                                                 0 as bmes3,
                                                 pd_grupo
                                         ";
                    } else {
                        if ($qpd23 == 3) {
                            $colunas = 3;
                            $sql_dados_work_ficha_financ .= "
                                                 0 as anousu1,
                                                 0 as mesusu1,
                                                 0 as anousu2,
                                                 0 as mesusu2,
                                                 anousu as anousu3,
                                                 mesusu as mesusu3,
                                                 0 as qmes1,
                                                 0 as pmes1,
                                                 0 as dmes1,
                                                 0 as bmes1,
                                                 0 as qmes2,
                                                 0 as pmes2,
                                                 0 as dmes2,
                                                 0 as bmes2,
                                                 quanti as qmes3,
                                                 proven as pmes3,
                                                 descon as dmes3,
                                                 basesr as bmes3,
                                                 pd_grupo
                                         ";
                        }
                    }
                }
                $sql_dados_work_ficha_financ .= "
                                          from work_ficha_financ
                                         "
                  . $arr_wheres[$iii] .
                  "
                                          and regist = " . $oServidor->regist . "
                                         ";
            }
            $sql_dados_work_ficha_financ .= "
                                                 ) as dados_rubricas
                                            group by pd_grupo, rubric1, drubri1

             ) as dados_vindos_para_order_by
                                      order by pd_grupo, rubric1
                                     ";

            $result_dados_work_ficha_financ = db_query($sql_dados_work_ficha_financ);

            $numrows_dados_work_ficha_financ = pg_numrows($result_dados_work_ficha_financ);
            $imprimir_cabecalho_rubricas = true;
            $conta_mes_ano = 0;
            $base_impressa = false;
            $totalq_mes_1 = 0;
            $totalq_mes_2 = 0;
            $totalq_mes_3 = 0;
            $totalp_mes_1 = 0;
            $totalp_mes_2 = 0;
            $totalp_mes_3 = 0;
            $totald_mes_1 = 0;
            $totald_mes_2 = 0;
            $totald_mes_3 = 0;

            for ($iiii = 0; $iiii < $numrows_dados_work_ficha_financ; $iiii++) {
                db_fieldsmemory($result_dados_work_ficha_financ, $iiii);

                $novapagina = false;
                if ($troca == 1 || $pdf->gety() > $pdf->h - 30) {
                    $imprimir_cabecalho_registros = true;
                    $novapagina = true;
                    $troca = 0;
                    $pdf->line(10, $pdf->gety(), 82 + (40 * $colunas), $pdf->gety());
                }

                if (isset($quebrapg) && $quebrapg == 's') {
                    $novapagina = true;
                }
                if ($imprimir_cabecalho_registros == true) {
                    $imprimir_cabecalho_registros = false;
                    $imprimir_cabecalho_rubricas = true;
                    imprimefuncionario_1(
                        $novapagina,
                        $oServidor->regist,
                        $oServidor->nomefc,
                        $oServidor->lotaca,
                        $oServidor->dlotac,
                        $oServidor->funcao,
                        $oServidor->dfunca
                    );
                }

                if ($imprimir_cabecalho_rubricas == true) {
                    $imprimir_cabecalho_rubricas = false;
                    $cor = 1;
                    $cor1 = 1;
                    imprimecabecalho_1($anos_meses);
                }

                if ($cor == 1) {
                    $cor = 0;
                } else {
                    $cor = 1;
                }

                if ($bmes1 > 0 || $bmes2 > 0 || $bmes3 > 0) {
                    if ($base_impressa == false) {
                        $base_impressa = true;
                        imprimetotaisrubrica_1(
                            $anos_meses,
                            $totalq_mes_1,
                            $totalq_mes_2,
                            $totalq_mes_3,
                            $totalp_mes_1,
                            $totalp_mes_2,
                            $totalp_mes_3,
                            $totald_mes_1,
                            $totald_mes_2,
                            $totald_mes_3
                        );
                        $cor1 = 1;
                    }
                    if ($cor1 == 1) {
                        $cor1 = 0;
                    } else {
                        $cor1 = 1;
                    }
                    $linha = false;
                    if ($iiii + 1 == $numrows_dados_work_ficha_financ) {
                        $linha = true;
                    }
                    imprimedadosbase_1($anos_meses, $rubric1, $drubri1, $qmes1, $qmes2, $qmes3, $bmes1, $bmes2, $bmes3, $cor1, $linha);
                } else {
                    $totalq_mes_1 += $qmes1;
                    $totalq_mes_2 += $qmes2;
                    $totalq_mes_3 += $qmes3;
                    $totalp_mes_1 += $pmes1;
                    $totalp_mes_2 += $pmes2;
                    $totalp_mes_3 += $pmes3;
                    $totald_mes_1 += $dmes1;
                    $totald_mes_2 += $dmes2;
                    $totald_mes_3 += $dmes3;
                    imprimedadosrubrica_1(
                        $anos_meses,
                        $rubric1,
                        $drubri1,
                        $qmes1,
                        $qmes2,
                        $qmes3,
                        $pmes1,
                        $pmes2,
                        $pmes3,
                        $dmes1,
                        $dmes2,
                        $dmes3,
                        $cor
                    );

                    /*
                     * passa parametros para metodo que soma rubricas por mes
                     */
                    $aTotaisRub = somarubricasmes(
                        $rubric1,
                        $drubri1,
                        $qmes1,
                        $qmes2,
                        $qmes3,
                        $pmes1,
                        $pmes2,
                        $pmes3,
                        $dmes1,
                        $dmes2,
                        $dmes3,
                        $aTotaisRub
                    );
                }
            }

            if ($base_impressa == false) {
                $base_impressa = true;
                imprimetotaisrubrica_1(
                    $anos_meses,
                    $totalq_mes_1,
                    $totalq_mes_2,
                    $totalq_mes_3,
                    $totalp_mes_1,
                    $totalp_mes_2,
                    $totalp_mes_3,
                    $totald_mes_1,
                    $totald_mes_2,
                    $totald_mes_3
                );
                $cor1 = 1;
            }
        }

        /*
         * chama metodo que imprime o bloco de totais por mes das rubricas
         */
        imprimetotaisrubricasperiodo($cor, $aTotaisRub);
        $aTotaisRub = array();
    }
} else {
    if ($orde == "a") {
        $orderby = " z01_nome ";
        $orderby2 = "nomefc,rubric, anousu, mesusu ";
    } else {
        $orderby = " rh01_regist ";
        $orderby2 = "regist,rubric, anousu, mesusu ";
    }

    // AQUI, IMPRIMIR OS DADOS QUANDO FOR INFORMADA ALGUMA RUBRICA
    $imprimir_cabecalho_registros = true;
    $imprimir_cabecalho_rubricas = true;
    $sql_dados_impressao_rub = "select * from work_ficha_financ order by $orderby2";
    $registro_anterior = "";
    $rubrica_anterior = "";
    $result_dados_impressao_rub = db_query($sql_dados_impressao_rub);

//  db_criatabela($result_dados_impressao_rub);exit;
    $numrows_dados_impressao_rub = pg_numrows($result_dados_impressao_rub);

    $arr_provn_rubricas = array();
    $arr_descn_rubricas = array();
    $arr_quant_rubricas = array();
    $arr_descr_rubricas = array();

    $valor_quant = 0;
    $valor_provn = 0;
    $valor_descn = 0;

    for ($i = 0; $i < $numrows_dados_impressao_rub; $i++) {
        db_fieldsmemory($result_dados_impressao_rub, $i);

        $novapagina = false;

        if ($quebrapg == 's') {
            $novapagina = true;
        }

        if ($troca == 1 || $pdf->gety() > $pdf->h - 30) {
            $imprimir_cabecalho_registros = true;
            $novapagina = true;
            $troca = 0;
        }

        if ($registro_anterior != $regist || $imprimir_cabecalho_registros == true) {
            if ($registro_anterior != "" && $registro_anterior != $regist) {
                imprimetotaisrubricas($valor_quant, $valor_provn, $valor_descn);
                $valor_quant = 0;
                $valor_provn = 0;
                $valor_descn = 0;
            }
            imprimefuncionario_1($novapagina, $regist, $nomefc, $lotaca, $dlotac, $funcao, $dfunca);

            $cor = 1;
            $registro_anterior = $regist;
            $imprimir_cabecalho_registros = false;
            $imprimir_cabecalho_rubricas = true;
        }

        if ($rubrica_anterior != $rubric || $imprimir_cabecalho_rubricas == true) {
            if ($rubrica_anterior != "" && $imprimir_cabecalho_rubricas == false) {
                imprimetotaisrubricas($valor_quant, $valor_provn, $valor_descn);
                $valor_quant = 0;
                $valor_provn = 0;
                $valor_descn = 0;
            }

            if ($imprimir_cabecalho_rubricas == false) {
                $pdf->cell(80, 1, "", "T", 1, "L", 0);
            }

            $pdf->cell(80, 3, $rubric . " - " . $drubri, "TRL", 1, "L", 1);
            $pdf->cell(20, 3, "ANO / MS", "BL", 0, "C", 1);
            $pdf->cell(20, 3, "QUANT", "B", 0, "C", 1);
            $pdf->cell(20, 3, "PROV", "B", 0, "C", 1);
            $pdf->cell(20, 3, "DESC", "BR", 1, "C", 1);
            $rubrica_anterior = $rubric;
            $imprimir_cabecalho_rubricas = false;
            $cor = 1;
        }

        if ($cor == 1) {
            $cor = 0;
        } else {
            $cor = 1;
        }

        $borda = 0;
        if ($i + 1 < $numrows_dados_impressao_rub) {
            if (pg_result($result_dados_impressao_rub, $i + 1, "regist") != $regist) {
                $borda = "B";
            }
        } else {
            if ($i + 1 == $numrows_dados_impressao_rub) {
                $borda = "B";
            }
        }

        $pdf->cell(20, 3, $anousu . " / " . db_formatar($mesusu, 's', '0', 2, 'e'), "L" . $borda, 0, "C", $cor);
        $pdf->cell(20, 3, db_formatar($quanti, "f"), $borda, 0, "R", $cor);
        $pdf->cell(20, 3, db_formatar(($proven + $basesr), "f"), $borda, 0, "R", $cor);
        $pdf->cell(20, 3, db_formatar($descon, "f"), "R" . $borda, 1, "R", $cor);

        if (!isset($arr_descr_rubricas[$rubric])) {
            $arr_quant_rubricas[$rubric] = 0;
            $arr_provn_rubricas[$rubric] = 0;
            $arr_descn_rubricas[$rubric] = 0;
        }

        $arr_descr_rubricas[$rubric] = $drubri;
        $arr_quant_rubricas[$rubric] += $quanti;
        $arr_provn_rubricas[$rubric] += ($proven + $basesr);
        $arr_descn_rubricas[$rubric] += $descon;

        $valor_quant += $quanti;
        $valor_provn += ($proven + $basesr);
        $valor_descn += $descon;
    }

    imprimetotaisrubricas($valor_quant, $valor_provn, $valor_descn);

    $pdf->ln(3);
    if (count($arr_descr_rubricas) > 0) {
        $pdf->cell(140, 3, "TOTALIZAÇÃO POR RUBRICAS", 1, 1, "L", 1);
        $pdf->cell(80, 3, "RUBRICAS", "LTB", 0, "L", 1);
        $pdf->cell(20, 3, "QUANT", "TB", 0, "R", 1);
        $pdf->cell(20, 3, "PROV", "TB", 0, "R", 1);
        $pdf->cell(20, 3, "DESC", "RTB", 1, "R", 1);
        $cor = 1;
        $quant_rubri = 0;
        $valor_quant = 0;
        $valor_provn = 0;
        $valor_descn = 0;
        foreach ($arr_descr_rubricas as $index => $value) {
            if ($cor == 1) {
                $cor = 0;
            } else {
                $cor = 1;
            }
            $pdf->cell(80, 3, $index . " - " . $value, "L", 0, "L", $cor);
            $pdf->cell(20, 3, db_formatar($arr_quant_rubricas[$index], "f"), 0, 0, "R", $cor);
            $pdf->cell(20, 3, db_formatar($arr_provn_rubricas[$index], "f"), 0, 0, "R", $cor);
            $pdf->cell(20, 3, db_formatar($arr_descn_rubricas[$index], "f"), "R", 1, "R", $cor);
            $quant_rubri++;
            $valor_quant += $arr_quant_rubricas[$index];
            $valor_provn += $arr_provn_rubricas[$index];
            $valor_descn += $arr_descn_rubricas[$index];
        }
        $pdf->cell(80, 3, "TOTAIS        " . $quant_rubri, "LTB", 0, "R", 1);
        $pdf->cell(20, 3, db_formatar($valor_quant, "f"), "TB", 0, "R", 1);
        $pdf->cell(20, 3, db_formatar($valor_provn, "f"), "TB", 0, "R", 1);
        $pdf->cell(20, 3, db_formatar($valor_descn, "f"), "RTB", 1, "R", 1);
    }
}

$pdf->Output();

function imprimedadosbase_1($anos_e_meses, $crub, $drub, $q1, $q2, $q3, $v1, $v2, $v3, $cor, $linha)
{

    global $alt;
    global $pdf;

    $arr_anos_e_meses = split("_", $anos_e_meses);

    $muda_linha1 = 0;
    $muda_linha2 = 0;
    if (count($arr_anos_e_meses) == 1) {
        $muda_linha1 = 1;
    } else {
        if (count($arr_anos_e_meses) == 2) {
            $muda_linha2 = 1;
        }
    }

    $l = "";
    if ($linha == true) {
        $l = "B";
    }

    $pdf->setfont('arial', '', 6);
    $pdf->cell(72, 3, $crub . " - " . $drub, "LR$l", 0, "L", $cor);
    $pdf->cell(13.33, 3, db_formatar($q1, "f"), "L$l", 0, "R", $cor);
    $pdf->cell(26.66, 3, db_formatar($v1, "f"), "R$l", $muda_linha1, "R", $cor);

    if (count($arr_anos_e_meses) >= 2) {
        $pdf->cell(13.33, 3, db_formatar($q2, "f"), "L$l", 0, "R", $cor);
        $pdf->cell(26.66, 3, db_formatar($v2, "f"), "R$l", $muda_linha2, "R", $cor);
    }

    if (count($arr_anos_e_meses) == 3) {
        $pdf->cell(13.33, 3, db_formatar($q3, "f"), "L$l", 0, "R", $cor);
        $pdf->cell(26.66, 3, db_formatar($v3, "f"), "R$l", 1, "R", $cor);
    }
}

function imprimecabecalho_2($crub, $drub)
{

    global $alt;
    global $pdf;

    $pdf->ln(1);
    $pdf->setfont('arial', 'b', 7);

    $pdf->cell(80, 4, $crub . " - " . $drub, 1, 1, "L", 1);
    $pdf->cell(20, 4, "ANO / MS", 1, 0, "C", 1);
    $pdf->cell(20, 4, "QTD", 1, 0, "C", 1);
    $pdf->cell(20, 4, "PROV", 1, 0, "C", 1);
    $pdf->cell(20, 4, "DESC", 1, 1, "C", 1);
}

function imprimetotaisrubricas($quant, $provn, $descn)
{

    global $alt;
    global $pdf;

    $pdf->cell(20, 3, "TOTAIS", "TBL", 0, "C", 1);
    $pdf->cell(20, 3, db_formatar($quant, "f"), "TB", 0, "R", 1);
    $pdf->cell(20, 3, db_formatar($provn, "f"), "TB", 0, "R", 1);
    $pdf->cell(20, 3, db_formatar($descn, "f"), "TBR", 1, "R", 1);
}

function imprimetotaisrubricasperiodo($cor, $aTotais)
{

    global $alt;
    global $pdf;

    $pdf->ln(4);

    $pdf->setfont('arial', 'B', 6);
    $pdf->cell(72, 3, "RUBRICA", "TBLR", 0, "L", 1);
    $pdf->cell(40, 3, "QTD", "TBR", 0, "C", 1);
    $pdf->cell(40, 3, "PROV", "TBR", 0, "C", 1);
    $pdf->cell(40, 3, "DESC", "TBR", 1, "C", 1);

    for ($i = 0; $i < count($aTotais); $i++) {
        if ($cor == 1) {
            $cor = 0;
        } else {
            $cor = 1;
        }

        if ($pdf->gety() > $pdf->h - 30) {
            $pdf->addpage();
            /*
             * insere novo cabecalho de dados na quebra de pagina
            */
            $pdf->setfont('arial', 'B', 6);
            $pdf->cell(72, 3, "RUBRICA", "TBLR", 0, "L", 1);
            $pdf->cell(40, 3, "QTD", "TBR", 0, "C", 1);
            $pdf->cell(40, 3, "PROV", "TBR", 0, "C", 1);
            $pdf->cell(40, 3, "DESC", "TBR", 1, "C", 1);

            $cor = 0;
        }

        $pdf->setfont('arial', '', 6);
        $pdf->cell(72, 3, $aTotais[$i][0], "TBLR", 0, "L", $cor);
        $pdf->cell(40, 3, db_formatar($aTotais[$i][1], "f"), "TBR", 0, "R", $cor);
        $pdf->cell(40, 3, db_formatar($aTotais[$i][2], "f"), "TBR", 0, "R", $cor);
        $pdf->cell(40, 3, db_formatar($aTotais[$i][3], "f"), "TBR", 1, "R", $cor);
    }
}

function imprimedadosrubrica_2($ano_e_mes, $q1, $p1, $d1, $cor)
{

    global $alt;
    global $pdf;

    $pdf->setfont('arial', '', 6);
    $pdf->cell(20, 4, $ano_e_mes, 1, 0, "C", $cor);
    $pdf->cell(20, 4, $q1, 1, 0, "C", $cor);
    $pdf->cell(20, 4, $p1, 1, 0, "C", $cor);
    $pdf->cell(20, 4, $d1, 1, 1, "C", $cor);
}

function imprimetotaisrubrica_1($anos_e_meses, $q1, $q2, $q3, $p1, $p2, $p3, $d1, $d2, $d3)
{

    global $alt;
    global $pdf;

    $arr_anos_e_meses = split("_", $anos_e_meses);

    $muda_linha1 = 0;
    $muda_linha2 = 0;
    if (count($arr_anos_e_meses) == 1) {
        $muda_linha1 = 1;
    } else {
        if (count($arr_anos_e_meses) == 2) {
            $muda_linha2 = 1;
        }
    }

    if ($q1 > 0 || $q2 > 0 || $q3 > 0 || $p1 > 0 || $p2 > 0 || $p3 > 0 || $d1 > 0 || $d2 > 0 || $d3 > 0) {
        $pdf->setfont('arial', 'B', 6);
        $pdf->cell(72, 3, "TOTAIS", "LTR", 0, "R", 1);
        $pdf->cell(13.33, 3, db_formatar($q1, "f"), "TL", 0, "R", 1);
        $pdf->cell(13.33, 3, db_formatar($p1, "f"), "T", 0, "R", 1);
        $pdf->cell(13.33, 3, db_formatar($d1, "f"), "TR", $muda_linha1, "R", 1);

        if (count($arr_anos_e_meses) >= 2) {
            $pdf->cell(13.33, 3, db_formatar($q2, "f"), "TL", 0, "R", 1);
            $pdf->cell(13.33, 3, db_formatar($p2, "f"), "T", 0, "R", 1);
            $pdf->cell(13.33, 3, db_formatar($d2, "f"), "TR", $muda_linha2, "R", 1);
        }

        if (count($arr_anos_e_meses) == 3) {
            $pdf->cell(13.33, 3, db_formatar($q3, "f"), "TL", 0, "R", 1);
            $pdf->cell(13.33, 3, db_formatar($p3, "f"), "T", 0, "R", 1);
            $pdf->cell(13.33, 3, db_formatar($d3, "f"), "TR", 1, "R", 1);
        }

        $pdf->cell(72, 3, "TOTAL LÍQUIDO", "LBR", 0, "R", 1);
        $pdf->cell(13.33, 3, "", "LB", 0, "R", 1);
        $pdf->cell(26.66, 3, db_formatar($p1 - $d1, "f"), "BR", $muda_linha1, "R", 1);

        if (count($arr_anos_e_meses) >= 2) {
            $pdf->cell(13.33, 3, "", "BL", 0, "R", 1);
            $pdf->cell(26.66, 3, db_formatar($p2 - $d2, "f"), "B", $muda_linha2, "R", 1);
        }

        if (count($arr_anos_e_meses) == 3) {
            $pdf->cell(13.33, 3, "", "BL", 0, "R", 1);
            $pdf->cell(26.66, 3, db_formatar($p3 - $d3, "f"), "BR", 1, "R", 1);
        }
    }
}

function imprimedadosrubrica_1($anos_e_meses, $crub, $drub, $q1, $q2, $q3, $p1, $p2, $p3, $d1, $d2, $d3, $cor)
{

    global $alt;
    global $pdf;

    $arr_anos_e_meses = split("_", $anos_e_meses);

    $muda_linha1 = 0;
    $muda_linha2 = 0;
    if (count($arr_anos_e_meses) == 1) {
        $muda_linha1 = 1;
    } else {
        if (count($arr_anos_e_meses) == 2) {
            $muda_linha2 = 1;
        }
    }

    $pdf->setfont('arial', '', 6);
    $pdf->cell(72, 3, $crub . " - " . $drub, "LR", 0, "L", $cor);
    $pdf->cell(13.33, 3, db_formatar($q1, "f"), "L", 0, "R", $cor);
    $pdf->cell(13.33, 3, db_formatar($p1, "f"), 0, 0, "R", $cor);
    $pdf->cell(13.33, 3, db_formatar($d1, "f"), "R", $muda_linha1, "R", $cor);

    if (count($arr_anos_e_meses) >= 2) {
        $pdf->cell(13.33, 3, db_formatar($q2, "f"), "L", 0, "R", $cor);
        $pdf->cell(13.33, 3, db_formatar($p2, "f"), 0, 0, "R", $cor);
        $pdf->cell(13.33, 3, db_formatar($d2, "f"), "R", $muda_linha2, "R", $cor);
    }

    if (count($arr_anos_e_meses) == 3) {
        $pdf->cell(13.33, 3, db_formatar($q3, "f"), "L", 0, "R", $cor);
        $pdf->cell(13.33, 3, db_formatar($p3, "f"), 0, 0, "R", $cor);
        $pdf->cell(13.33, 3, db_formatar($d3, "f"), "R", 1, "R", $cor);
    }
}

function imprimecabecalho_1($anos_e_meses)
{

    global $alt;
    global $pdf;

    $arr_anos_e_meses = split("_", $anos_e_meses);

    $muda_linha1 = 0;
    $muda_linha2 = 0;
    if (count($arr_anos_e_meses) == 1) {
        $muda_linha1 = 1;
    } else {
        if (count($arr_anos_e_meses) == 2) {
            $muda_linha2 = 1;
        }
    }

    for ($i = 0; $i < count($arr_anos_e_meses); $i++) {
        $ano_mes = $arr_anos_e_meses[$i];
        if ($i == 0) {
            $ano1 = substr($ano_mes, 0, 4);
            $mes1 = substr($ano_mes, 4, 2);
        } else {
            if ($i == 1) {
                $ano2 = substr($ano_mes, 0, 4);
                $mes2 = substr($ano_mes, 4, 2);
            } else {
                if ($i == 2) {
                    $ano3 = substr($ano_mes, 0, 4);
                    $mes3 = substr($ano_mes, 4, 2);
                }
            }
        }
    }

    $pdf->ln(1);
    $pdf->setfont('arial', 'b', 7);
    if (isset($ano1)) {
        $pdf->cell(72, 3, "", "RLT", 0, "R", 1);
        $pdf->cell(40, 3, $ano1 . "/" . strtoupper(db_mes($mes1)), "TRL", $muda_linha1, "R", 1);
    }
    if (isset($ano2)) {
        $pdf->cell(40, 3, $ano2 . "/" . strtoupper(db_mes($mes2)), "TRL", $muda_linha2, "R", 1);
    }
    if (isset($ano3)) {
        $pdf->cell(40, 3, $ano3 . "/" . strtoupper(db_mes($mes3)), "TRL", 1, "R", 1);
    }

    if (isset($ano1)) {
        $pdf->cell(72, 3, "RUBRICA", "LBR", 0, "L", 1);
        $pdf->cell(13.33, 3, "QTD", "LB", 0, "C", 1);
        $pdf->cell(13.33, 3, "PROV", "B", 0, "C", 1);
        $pdf->cell(13.33, 3, "DESC", "BR", $muda_linha1, "C", 1);
    }

    if (isset($ano2)) {
        $pdf->cell(13.33, 3, "QTD", "LB", 0, "C", 1);
        $pdf->cell(13.33, 3, "PROV", "B", 0, "C", 1);
        $pdf->cell(13.33, 3, "DESC", "BR", $muda_linha2, "C", 1);
    }

    if (isset($ano3)) {
        $pdf->cell(13.33, 3, "QTD", "LB", 0, "C", 1);
        $pdf->cell(13.33, 3, "PROV", "B", 0, "C", 1);
        $pdf->cell(13.33, 3, "DESC", "BR", 1, "C", 1);
    }
}


// Funo para imprimir os dados do funcionrio
function imprimefuncionario_1($newpagina, $registro, $nomeregi, $lotaccod, $lotacrec, $funcacod, $funcarec)
{

    global $alt;
    global $pdf;
    global $cpf;
    global $conta;
    global $pis;
    global $agencia;
    global $dvconta;
    global $dvagencia;

    if ($newpagina == true) {
        $pdf->addpage();
    } else {
        $pdf->ln(2);
    }

    $pdf->setfont('arial', 'b', 7);
    $pdf->cell(17, $alt, "Funcionário:", 0, 0, "R", 0);
    $pdf->cell(17, $alt, $registro . " - " . db_CalculaDV($registro), 0, 0, "C", 0);
    $result_info = db_query("select z01_cgccpf as cpf, rh44_conta as conta, rh16_pis as pis,rh44_agencia as agencia ,rh44_dvagencia as dvagencia , rh44_dvconta as dvconta from rhpessoal inner join cgm on rh01_numcgm = z01_numcgm left join rhpesdoc on rh16_regist = rh01_regist inner join rhpessoalmov on rh02_regist = rh01_regist and rh02_mesusu = " . db_mesfolha() . " and rh02_anousu =  " . db_anofolha() . "  left join rhpesbanco on rh44_seqpes = rh02_seqpes  where rh01_regist = $registro ");
    if (pg_numrows($result_info) > 0) {
        $pdf->cell(61, $alt, $nomeregi, 0, 0, "L", 0);
        db_fieldsmemory($result_info, 0);
        $pdf->cell(17, $alt, "Cpf...........:", 0, 0, "R", 0);
        $pdf->cell(78, $alt, "$cpf", 0, 1, "L", 0);
        $pdf->cell(17, $alt, "Agência.......:", 0, 0, "R", 0);
        $pdf->cell(17, $alt, $agencia . "-" . $dvagencia, 0, 0, "C", 0);
        $pdf->cell(21, $alt, "Conta Corrente:", 0, 0, "R", 0);
        $pdf->cell(40, $alt, $conta . "-" . $dvconta, 0, 0, "L", 0);
        $pdf->cell(17, $alt, "Pis...........:", 0, 0, "R", 0);
        $pdf->cell(30, $alt, "$pis", 0, 1, "L", 0);
    } else {
        $pdf->cell(0, $alt, $nomeregi, 0, 1, "L", 0);
    }

    $pdf->cell(17, $alt, "Cargo.........:", 0, 0, "R", 0);
    $pdf->cell(17, $alt, $funcacod, 0, 0, "C", 0);
    $pdf->cell(61, $alt, $funcarec, 0, 0, "L", 0);
    $pdf->cell(17, $alt, "Lotação.......:", 0, 0, "R", 0);
    $pdf->cell(17, $alt, $lotaccod, 0, 0, "C", 0);
    $pdf->cell(61, $alt, $lotacrec, 0, 1, "L", 0);
}

function somarubricasmes(
    $rubric1,
    $drubri1,
    $qmes1,
    $qmes2,
    $qmes3,
    $pmes1,
    $pmes2,
    $pmes3,
    $dmes1,
    $dmes2,
    $dmes3,
    $aTotais
) {

    $rub = $rubric1 . ' - ' . $drubri1;
    $qtd = $qmes1 + $qmes2 + $qmes3;
    $prov = $pmes1 + $pmes2 + $pmes3;
    $desc = $dmes1 + $dmes2 + $dmes3;

    $achou = false;
    for ($i = 0; $i < count($aTotais); $i++) {
        if ($aTotais[$i][0] == $rub) {
            $aTotais[$i][1] += $qtd;
            $aTotais[$i][2] += $prov;
            $aTotais[$i][3] += $desc;

            $achou = true;
            break;
        }
    }

    // inclui no final do array -
    if (!$achou) {
        $aTotais[] = array($rub, $qtd, $prov, $desc);
    }

    return $aTotais;
}


function estruturaSumplementar()
{

    $sCamposTabelasCalculo  = "rh141_anousu as anousu,                                                 \n";
    $sCamposTabelasCalculo .= "rh141_mesusu as mesusu,                                                 \n";
    $sCamposTabelasCalculo .= "rh143_regist as regist,                                                 \n";
    $sCamposTabelasCalculo .= "rh143_rubrica as rubric,                                                \n";
    $sCamposTabelasCalculo .= "rh27_descr,                                                             \n";
    $sCamposTabelasCalculo .= "case when rh143_tipoevento = 1 then rh143_valor else 0 end as provento, \n";
    $sCamposTabelasCalculo .= "case when rh143_tipoevento = 2 then rh143_valor else 0 end as desconto, \n";
    $sCamposTabelasCalculo .= "case when rh143_tipoevento = 3 then rh143_valor else 0 end as basesr,   \n";
    $sCamposTabelasCalculo .= "case when rh143_tipoevento <> 3 then 1 else 2 end as pd_grupo,          \n";
    $sCamposTabelasCalculo .= "rh02_seqpes      as seqpes,                                             \n";
    $sCamposTabelasCalculo .= "z01_numcgm       as numcgm,                                             \n";
    $sCamposTabelasCalculo .= "z01_nome         as nomefc,                                             \n";
    $sCamposTabelasCalculo .= "r70_codigo       as lotaca,                                             \n";
    $sCamposTabelasCalculo .= "r70_descr        as dlotac,                                             \n";
    $sCamposTabelasCalculo .= "rh02_funcao      as funcao,                                             \n";
    $sCamposTabelasCalculo .= "rh37_descr       as dfunca,                                             \n";
    $sCamposTabelasCalculo .= "rh143_quantidade,                                                       \n";
    $sCamposTabelasCalculo .= "'rhhistoricocalculo' as tabela                                          \n";

    return $sCamposTabelasCalculo;
}
