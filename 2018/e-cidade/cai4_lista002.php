<?php
/**
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

/* Libs */
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));

/* Carrega GET */
$oGet = db_utils::postMemory($_GET);

/* Funcao para converter data formato dd/mm/YYYY em YYYY-mm-dd */
function to_dbdate($date) {
  return db_subdata($date, "a", "f") . "-" .
         db_subdata($date, "m", "f") . "-" .
         db_subdata($date, "d", "f");
}

/* Instancia classes, define variáveis */
$cllista       = new cl_lista();
$cllistadeb    = new cl_listadeb();
$cllistatipos  = new cl_listatipos();
$clusuarios    = new cl_db_usuarios();
$clarretipo    = new cl_arretipo();
$clBairros     = new cl_bairro();
$clRuas        = new cl_ruas();
$clZonas       = new cl_zonas();
$oDaoProced    = new cl_proced();
$oDaoProcDiver = new cl_procdiver();

$tInicio   = time();
$tFim      = time();

$iInstit   = db_getsession("DB_instit");
$iUsuario  = db_getsession("DB_id_usuario");

$rUsuario  = $clusuarios->sql_record($clusuarios->sql_query_file($iUsuario, "login, nome"));

if ($clusuarios->numrows > 0) {
  $oUsuario = db_utils::fieldsMemory($rUsuario, 0);
} else {
  $oUsuario = new db_stdClass();

  $oUsuario->login = 'desconhecido';
  $oUsuario->nome  = 'Desconhecido';
}

$dDataBase = date("Y-m-d", db_getsession("DB_datausu"));
$lDebug    = false;

if(!isset($oGet->dDataDebitos) || empty($oGet->dDataDebitos)) {

  $sMsg = urlencode(_M('tributario.notificacoes.cai4_lista002.data_debito'));
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}

/* Montando Texto com detalhamento dos filtros da lista (lista.k60_filtros) */
$sFiltro  = " # Descrição: {$oGet->sDescricaoLista}";
$sFiltro .= " # Valores de: {$oGet->nValorIni} a {$oGet->nValorFim}";
$sFiltro .= " # Data do Cálculo: {$oGet->dDataDebitos}";
$sFiltro .= " # Quantidade a Listar: {$oGet->iQtdListar}";
$sFiltro .= " # Tipo de Lista: {$oGet->sTipoListaDescr}";

if (!empty($oGet->dNotifDataLimite)) {
  $sFiltro .= " # Não Considerar Notificados Até: {$oGet->dNotifDataLimite} - {$oGet->sNotifTipo}";
}

$sFiltro .= " # Lista Massa Falida: ".(($oGet->sMassaFalida=="S")?"Sim":"Não");
$sFiltro .= " # Considera loteamentos: ".(($oGet->sLoteamento=="S")?"Sim":"Não");
$sFiltro .= " # Data de operação de {$oGet->dDtOperIni} a {$oGet->dDtOperFim}";
$sFiltro .= " # Vencimento de {$oGet->dDtVencIni} a {$oGet->dDtVencFim}";
$sFiltro .= " # Exercícios {$oGet->iExercIni} a {$oGet->iExercFim}";
$sFiltro .= " # Considerar periodos posteriores: ".(($oGet->sConsideraPosterior=="S")?"Sim":"Não");
$sFiltro .= " # Desconsiderando exercícios {$oGet->iIgnoraExercIni} a {$oGet->iIgnoraExercFim}";
$sFiltro .= " # Quantidade de Parcelas em atraso {$oGet->iQtdParcAtrasoIni} a {$oGet->iQtdParcAtrasoFim}";
$sFiltro .= " # Número das Parcelas em atraso {$oGet->iNroParcAtrasoIni} a {$oGet->iNroParcAtrasoFim}";
$sFiltro .= " # Opção: {$oGet->sOpcaoTipoDebito}";
$sFiltro .= " # Desconsiderar Débitos com recibo válido após: {$oGet->dtDesconsiderarDebitos}";  //Criadp


$sSqlArretipo  = "SELECT array_to_string(array_accum(k00_tipo||'-'||k00_descr), ', ') as tipos_descr ";
$sSqlArretipo .= "  FROM arretipo ";
$sSqlArretipo .= " WHERE k00_instit = {$iInstit}";
if (!empty($oGet->sTiposDebitos)) {
  $sOperadorTipoDebito = ($oGet->iOpcaoTipoDebito==1)?"IN":"NOT IN";
  $sSqlArretipo .= "   AND k00_tipo {$sOperadorTipoDebito} ({$oGet->sTiposDebitos}) ";
}

$rArretipo = db_query($sSqlArretipo);

if (pg_num_rows($rArretipo) > 0) {
  $oArretipo = db_utils::fieldsMemory($rArretipo, 0);
} else {
  $oArretipo = new db_stdClass;
  $oArretipo->tipos_descr = "Nenhum Tipo Encontrado";
}

$sFiltro .= " # Tipos de Débitos da Lista: {$oArretipo->tipos_descr}";

if ($oGet->iOpcaoTipoDebito<>1 and !empty($oGet->sTiposDebitos)) {
  $sSqlArretipo  = "SELECT array_to_string(array_accum(k00_tipo||'-'||k00_descr), ', ') as tipos_descr ";
  $sSqlArretipo .= "  FROM arretipo ";
  $sSqlArretipo .= " WHERE k00_instit = {$iInstit}";
  $sSqlArretipo .= "   AND k00_tipo IN ({$oGet->sTiposDebitos}) ";

  $rArretipo = db_query($sSqlArretipo);

  if (pg_num_rows($rArretipo) > 0) {
    $oArretipo = db_utils::fieldsMemory($rArretipo, 0);
  } else {
    $oArretipo = new db_stdClass;
    $oArretipo->tipos_descr = "Nenhum Tipo Encontrado";
  }

  $sFiltro .= " # Tipos de Débitos Selecionados: {$oArretipo->tipos_descr}";
}

/**
 * Arrays que guardarão as procedências selecionadas como filtro, separando por Dívida ou Diversos
 */
$aProcedenciasDividaFiltros   = array();
$aProcedenciasDiversosFiltros = array();

$sProcedenciasDividaAtiva  = '';
$sProcedenciasDiversos     = '';

if(!empty($oGet->sProcedencias)) {
 
  $sProcedenciasDividaAtiva  = preg_replace(array('/(\d+-DI\,*)/', '/^\,*(\d.*\w)\,*$/', '/(\d+)-DA(\,*)/'), array('', '$1', "$1$2"), $oGet->sProcedencias);
  $sProcedenciasDiversos     = preg_replace(array('/(\d+-DA\,*)/', '/^\,*(\d.*\w)\,*$/', '/(\d+)-DI(\,*)/'), array('', '$1', "$1$2"), $oGet->sProcedencias);
}

if(!empty($sProcedenciasDividaAtiva)) {

  $sCamposProced = "array_to_string(array_accum(v03_codigo||'-'||v03_dcomp), ', ') as procedencias";
  $sWhereProced  = "v03_codigo in({$sProcedenciasDividaAtiva}) GROUP BY v03_codigo";
  $sSqlProced    = $oDaoProced->sql_query_file(null, $sCamposProced, 'v03_codigo', $sWhereProced);
  $rsProced      = db_query($sSqlProced);

  if($rsProced && pg_num_rows($rsProced) > 0) {
    $aProcedenciasDividaFiltros = db_utils::makeCollectionFromRecord($rsProced, function($oProced) {
      return $oProced->procedencias;
    });
  }
}

if(!empty($sProcedenciasDiversos)) {

  $sCamposProcDiver = "array_to_string(array_accum(dv09_procdiver||'-'||dv09_descr), ', ') as procedencias";
  $sWhereProcDiver  = "dv09_procdiver in({$sProcedenciasDiversos}) GROUP BY dv09_procdiver";
  $sSqlProcDiver    = $oDaoProcDiver->sql_query_file(null, $sCamposProcDiver, 'dv09_procdiver', $sWhereProcDiver);
  $rsProcDiver      = db_query($sSqlProcDiver);

  if($rsProcDiver && pg_num_rows($rsProcDiver) > 0) {
    $aProcedenciasDiversosFiltros = db_utils::makeCollectionFromRecord($rsProcDiver, function($oProcDiver) {
      return $oProcDiver->procedencias;
    });
  }
}

$aProcedenciasFiltro = array_merge($aProcedenciasDividaFiltros, $aProcedenciasDiversosFiltros);

if(count($aProcedenciasFiltro) > 0) {
  $sFiltro .= " # Procedências Selecionadas: " . implode(', ', $aProcedenciasFiltro);
}

$sFiltro .= " # Usuário: {$iUsuario} - {$oUsuario->login} - {$oUsuario->nome}";
$sFiltro .= " # Inicio Processamento: ".date("d/m/Y H:i:s", $tInicio);

/* Array para armazenar SQLs */
$sSqlEtapa = array();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<br>
<br>

<?
/***
 *
 *  Nas inclusão de listas de débitos do DBportal existem 2 tipos de filtros:
 *
 *  1. Filtros Simples
 *    - Todos filtros com exceção dos citados no próximo tópico
 *
 *    - Comportamento Filtros:
 *      . "Data Debitos": debitos.k22_data = 'data'
 *
 *      . "Não considerar notificados até": notificacao.k50_dtemite <= 'data'
 *        0-Geral - sem filtro qualquer
 *        1-Tipo de Debito - verificar uma forma para juntar notidebitos, notideitosreg e debitos e nao considerar os tipos de debitos notificados ate a data
 *        2-Numpre / Parcela - notidebitos.k53_numpre = debitos.k22_numpre and notidebitos.k53_numpar = debitos.k22_numpar
 *
 *      . "Massa Falida" (tipo de lista = MATRICULA)
 *        Nao: NOT EXISTS (select 1 from massamat where j59_matric = k22_matric)
 *        Sim: NOT EXISTS (select 1 from massamat where j59_matric = k22_matric)
 *
 *      . "Loteamentos" (tipo de lista = MATRICULA)
 *        Sim: EXISTS (select 1 from iptubase inner join loteloteam on j34_idbql = j01_idbql where j01_matric = k22_matric)
 *        Nao: NOT EXISTS (select 1 from iptubase inner join loteloteam on j34_idbql = j01_idbql where j01_matric = k22_matric)
 *
 *      . "Data de Operação": k22_dtoper between 'dataini' and 'datafim'
 *
 *      . "Data do Vencimento": k22_dtvenc between 'dataini' and 'datafim'
 *
 *      . "Exercício": k22_exerc between 'anoini' and 'anofim'
 *
 *      . "Desconsidera Exercicio": k22_exerc not between 'anoini' and 'anofim'
 *
 *      . "Tipo de Debito"
 *        Os Selecionados: k22_tipo IN ( , , )
 *        Sem os Selecionados: k22_tipo NOT IN ( , , )
 *
 *      . "Número das Parcelas em Atraso"
 *        k22_numpar between parcini and parcfim and k22_dtvenc < 'DATA_BASE'
 *
 *  2. Filtros Agregadores (sao aplicados em conjuntos de registros usando funcoes de agregacao, como "sum", "count" e/ou "avg")
 *    - "Intervalo de valores" que é agrupado pelo "Tipo da Lista"
 *    - "Quantidade de Parcelas em Atraso" que é agrupado pelo "Tipo da Lista" e pelo "Tipo de Débito"
 *
 *      . "Quantidade a Listar" = LIMIT ao Final
 *
 *
 */

/* Cria barra de progresso */
db_criatermometro('termometro_item', 'Concluido...', 'blue', 1);
flush();

/***
 *
 * 1a Etapa
 *
 * Criando Tabela Temporária com os Filtros Simples
 *
 */

$sSqlEtapa["01"]  = "DROP TABLE IF EXISTS w_debitos_divida; \n";
$sSqlEtapa["01"] .= "CREATE TEMP TABLE w_debitos_divida AS \n";
$sSqlEtapa["01"] .= "SELECT * \n";
$sSqlEtapa["01"] .= "  FROM debitos \n";
$sSqlEtapa["01"] .= " WHERE k22_data   = '".to_dbdate($oGet->dDataDebitos)."' \n";
$sSqlEtapa["01"] .= "   AND k22_instit = {$iInstit} \n";

if (!empty($oGet->sTiposDebitos)) {
  $sOperadorTipoDebito = ($oGet->iOpcaoTipoDebito==1)?"IN":"NOT IN";
  $sSqlEtapa["01"] .= "   AND k22_tipo {$sOperadorTipoDebito} ({$oGet->sTiposDebitos}) \n";
}

$aWhereProcedencias = array();

if(!empty($sProcedenciasDividaAtiva)) {

  $sSql  = " exists( select 1 ";
  $sSql .= "           from divida";
  $sSql .= "     inner join proced on v03_codigo = v01_proced ";
  $sSql .= "          where v01_numpre = k22_numpre ";
  $sSql .= "            and v01_numpar = k22_numpar ";
  $sSql .= "            and v03_codigo IN ({$sProcedenciasDividaAtiva}) )";

  $aWhereProcedencias[] = $sSql;
}

if(!empty($sProcedenciasDiversos)) {

  $sSql  = " exists( select 1 ";
  $sSql .= "           from diversos";
  $sSql .= "     inner join procdiver on dv09_procdiver = dv05_procdiver ";
  $sSql .= "          where dv05_numpre = k22_numpre ";
  $sSql .= "            and dv09_procdiver IN ({$sProcedenciasDiversos}) )";

  $aWhereProcedencias[] = $sSql;
}

if(count($aWhereProcedencias) > 0) {
  $sSqlEtapa["01"] .= "   AND (" . implode(" OR ", $aWhereProcedencias) . ")";
}

/* Valida Tipo de Lista */
switch ($oGet->sTipoLista) {

  /* N = Nome Geral */
  case "N":
  // ******************************************************************************************* ENTRA AKI
    if(isset($oGet->sContribuintes) and ($oGet->sContribuintes != '')) {
      $sOperador        = ($oGet->iContribuinte=='1')? 'IN' : 'NOT IN';
    	$sSqlEtapa["01"] .= " AND k22_numcgm {$sOperador} ($oGet->sContribuintes) ";
    }
    $sSqlEtapa["01"] .= "   AND coalesce(k22_numcgm, 0) <> 0 \n";
    $sOrigem      = "k22_numcgm";
    break;

  /* C = Somente por CGM */
  case "C":
    if(isset($oGet->sContribuintes) and ($oGet->sContribuintes != '')) {
      $sOperador        = ($oGet->iContribuinte=='1')? 'IN' : 'NOT IN';
    	$sSqlEtapa["01"] .= " AND k22_numcgm {$sOperador} ($oGet->sContribuintes) ";
    }
    $sSqlEtapa["01"] .= "   AND coalesce(k22_numcgm, 0) <> 0 \n";
    $sSqlEtapa["01"] .= "   AND coalesce(k22_matric, 0) =  0 \n";
    $sSqlEtapa["01"] .= "   AND coalesce(k22_inscr,  0) =  0 \n";
    $sOrigem      = "k22_numcgm";
    break;

  /* M = Matricula */
  case "M":
    $sSqlEtapa["01"] .= "   AND coalesce(k22_matric, 0) <> 0 \n";
    $sOrigem          = "k22_matric";

    /* Valida Massa Falida */
    $sOperador       = ($oGet->sMassaFalida=="S")?"EXISTS":"NOT EXISTS";
    $sSqlMassaFalida = "SELECT 1 FROM massamat WHERE j59_matric = k22_matric";
    $sSqlEtapa["01"]    .= "   AND {$sOperador} ({$sSqlMassaFalida}) \n";

    /* Valida Loteamento */
    $sOperador      = ($oGet->sLoteamento=="S")?"EXISTS":"NOT EXISTS";
    $sSqlLoteamento = "SELECT 1 FROM loteloteam INNER JOIN iptubase ON j01_idbql = j34_idbql WHERE j01_matric = k22_matric";
    $sSqlEtapa["01"]   .= "   AND {$sOperador} ({$sSqlLoteamento}) \n";

    //filtra matriculas da lista de notificação
    if(isset($oGet->sContribuintes) and ($oGet->sContribuintes != '')) {
    	$sOperador        = ($oGet->iContribuinte=='1')? 'IN' : 'NOT IN';
    	$sSqlEtapa["01"] .= " AND k22_matric {$sOperador} ({$oGet->sContribuintes}) ";
    }

		if(isset($oGet->sBairros) and ($oGet->sBairros != '')) {
    	$sOperador       = ($oGet->iBairros == '1') ? 'EXISTS' : 'NOT EXISTS';

    	$sSqlBairro       = "select 1
		 		 								     from iptubase
			 									    inner join testpri on j49_idbql = j01_idbql
			 									    inner join lote    on j34_idbql = j01_idbql
												    where j01_matric = k22_matric
												      and j34_bairro in ({$oGet->sBairros}) ";

    	$sSqlEtapa["01"] .= " AND {$sOperador} ({$sSqlBairro}) ";
    }

		if(isset($oGet->sZonas) and ($oGet->sZonas != '')) {
    	$sOperador        = ($oGet->iZonas == '1') ? 'EXISTS' : 'NOT EXISTS';

    	$sSqlZonas        = "select 1
		 		 								     from iptubase
														inner join testpri on j49_idbql = j01_idbql
			 									    inner join lote    on j34_idbql = j01_idbql
												    where j01_matric = k22_matric
												      and j34_zona in ({$oGet->sZonas}) ";

    	$sSqlEtapa["01"] .= " AND {$sOperador} ({$sSqlZonas}) ";
    }

		if(isset($oGet->sRuas) and ($oGet->sRuas != '')) {
    	$sOperador        = ($oGet->iRuas == '1') ? 'EXISTS' : 'NOT EXISTS';

    	$sSqlBairro       = "select 1
		 		 								     from iptubase
														inner join testpri on j49_idbql = j01_idbql
												    where j01_matric = k22_matric
												      and j49_codigo in ({$oGet->sRuas}) ";

    	$sSqlEtapa["01"] .= " AND {$sOperador} ({$sSqlBairro}) ";
    }

    //filtro do modulo agua
    if(isset($oGet->situacoes) and $oGet->situacoes!= '') {

      $sOperador        = ($oGet->situacao == 'c')? 'IN' : 'NOT IN';
      $sSqlEtapa["01"] .= " AND fc_agua_ultimasituacaocorte(k22_matric) {$sOperador} ($oGet->situacoes)";

    }
    //filtro do modulo agua
    if(isset($oGet->zonas) and $oGet->zonas != '') {

      $sOperador        = ($oGet->zona == 'c') ? 'EXISTS' : 'NOT EXISTS';
      $sSqlZonas        = "SELECT 1 FROM aguabase WHERE x01_matric = k22_matric AND x01_entrega IN ({$oGet->zonas})";
      $sSqlEtapa["01"] .= " AND {$sOperador} ({$sSqlZonas}) ";

    }
    //filtro do modulo agua
    if(isset($oGet->caracteristicas) and $oGet->caracteristicas != '') {

      $sOperador        = ($oGet->caracteristica == 'c') ? 'EXISTS' : 'NOT EXISTS';
      $sSqlCaracter     = "SELECT 1 FROM aguaconstr INNER JOIN aguaconstrcar ON x12_codconstr = x11_codconstr WHERE x11_matric = k22_matric AND x12_codigo IN ({$oGet->caracteristicas})";
      $sSqlEtapa["01"] .= " AND {$sOperador} ({$sSqlCaracter}) ";

    }
    //filtro do modulo agua
    if(isset($oGet->ruas) and $oGet->ruas != '') {

      $sOperador        = ($oGet->rua == 'c') ? 'EXISTS' : 'NOT EXISTS';
      $sSqlRuas         = "SELECT 1 FROM aguabase WHERE x01_matric = k22_matric AND x01_codrua IN ({$oGet->ruas})";
      $sSqlEtapa["01"] .= " AND {$sOperador} ({$sSqlRuas})";

    }
    //filtro do modulo agua
    if(isset($oGet->baixadas) and $oGet->baixadas == 'N') {

      $sSqlBaixadas     = "SELECT 1 FROM aguabasebaixa WHERE x08_matric = k22_matric";
      $sSqlEtapa["01"] .= " AND NOT EXISTS ({$sSqlBaixadas})";
    }
    //filtro do modulo agua
    if(isset($oGet->terrenos) and $oGet->terrenos == 'N') {
      $sSqlTerrenos  = "SELECT 1
                          FROM aguaconstr
                         INNER JOIN aguaconstrcar ON x12_codconstr = x11_codconstr
                         INNER JOIN caracter      ON j31_codigo    = x12_codigo
                                                 AND j31_grupo     = 80
                         WHERE x11_matric = k22_matric
                           AND j31_codigo <> 5006";
      $sSqlEtapa["01"] .= " AND EXISTS ($sSqlTerrenos)";
    }

    break;

  /* I = Inscricao */
  case "I":
    $sSqlEtapa["01"] .= "   AND coalesce(k22_inscr,  0) <> 0 \n";
    $sOrigem      = "k22_inscr";

		if(isset($oGet->sContribuintes) and ($oGet->sContribuintes != '')) {
    	$sOperador        = ($oGet->iContribuinte=='1')?'IN':'NOT IN';
    	$sSqlEtapa["01"] .= " AND k22_inscr {$sOperador} ({$oGet->sContribuintes}) ";
    }

		if(isset($oGet->sBairros) and ($oGet->sBairros != '')) {
    	$sOperador        = ($oGet->iBairros == '1') ? 'EXISTS' : 'NOT EXISTS';

    	$sSqlBairro       = "select 1 from issbairro where q13_inscr = k22_inscr and q13_bairro in ({$oGet->sBairros})";

    	$sSqlEtapa["01"] .= " and {$sOperador} ({$sSqlBairro}) ";
    }

		if(isset($oGet->sRuas) and ($oGet->sRuas != '')) {
    	$sOperador        = ($oGet->iRuas == '1') ? 'EXISTS' : 'NOT EXISTS';

    	$sSqlRuas         = "select 1 from issruas where q02_inscr = k22_inscr and j14_codigo in ({$oGet->sRuas})";

    	$sSqlEtapa["01"] .= " and {$sOperador} ({$sSqlRuas}) ";
    }
		if(isset($oGet->sZonas) and ($oGet->sZonas != '')) {
    	$sOperador        = ($oGet->iZonas == '1') ? 'EXISTS' : 'NOT EXISTS';

    	$sSqlZonas        = "select 1 from isszona where q35_inscr = k22_inscr and q35_zona in ({$oGet->sZonas})";

    	$sSqlEtapa["01"] .= " and {$sOperador} ({$sSqlZonas}) ";
    }

		break;

  default:

    $oParms = new stdClass();
    $oParms->sTipoLista = $oGet->sTipoLista;
    $sMsg = _M('tributario.notificacoes.cai4_lista002.defina_tipo_lista', $oParms);
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}

switch ($oGet->sTipoLista) {
	case 'M':
	case 'I':
		if(isset($oGet->sBairros) and ($oGet->sBairros != '')) {
			$sFiltro .= "#". ($oGet->iBairros == '1' ? 'Com' : 'Sem') . ' os bairros: ';
			$sFiltro .= getDescricao("bairro", "j13_descr", "j13_codi in ({$oGet->sBairros})");
		}
		if(isset($oGet->sRuas) and ($oGet->sRuas != '')) {
			$sFiltro .= "#". ($oGet->iRuas == '1' ? 'Com' : 'Sem') . ' as ruas: ';
			$sFiltro .= getDescricao("ruas", "j14_nome", "j14_codigo in ({$oGet->sRuas})");
		}
		if(isset($oGet->sZonas) and ($oGet->sZonas != '')) {
			$sFiltro .= "#". ($oGet->iZonas == '1' ? 'Com' : 'Sem') . ' as zonas: ';
			$sFiltro .= getDescricao("zonas", "j50_descr", "j50_zona in ({$oGet->sZonas})");
		}
}

if(isset($oGet->sContribuintes) and ($oGet->sContribuintes != '')) {

	$sFiltro .= "# " . ($oGet->iContribuinte == "1"? "Com" : "Sem");
	if($oGet->sTipoLista == 'M') {
		$sFiltro .= " as Matrículas: ";
	} else if($oGet->sTipoLista == 'I') {
		$sFiltro .= " as Inscrições: ";
	}	else {
		$sFiltro .= " os CGMs: ";
	}
	$sFiltro .= getContribuintes($oGet->sTipoLista, $oGet->sContribuintes);

}

/* Valida Exercicios Inicio e Fim */
if (!empty($oGet->iExercIni) or !empty($oGet->iExercFim)) {
  $iExercIni       = empty($oGet->iExercIni)?0:$oGet->iExercIni;
  $oGet->iExercIni = $iExercIni; /* Salva Periodo da Selecao */

  if($oGet->sConsideraPosterior=="N") {
    $iExercFim       = empty($oGet->iExercFim)?9999:$oGet->iExercFim;
    $oGet->iExercFim = $iExercFim; /* Salva Periodo da Selecao */
  } else {
    $iExercFim = 9999;
  }

  $sSqlEtapa["01"] .= "   AND k22_exerc BETWEEN {$iExercIni} AND {$iExercFim} \n";
}

/* Valida "Desconsidera Exercicios" Inicio e Fim */
if (!empty($oGet->iIgnoraExercIni) or !empty($oGet->iIgnoraExercFim)) {
  $iIgnoraExercIni = empty($oGet->iIgnoraExercIni)?0:$oGet->iIgnoraExercIni;
  $iIgnoraExercFim = empty($oGet->iIgnoraExercFim)?9999:$oGet->iIgnoraExercFim;

  $sSqlEtapa["01"] .= "   AND k22_exerc NOT BETWEEN {$iIgnoraExercIni} AND {$iIgnoraExercFim} \n";
}

/* Valida "Data de Operacao" Inicio e Fim */
if (!empty($oGet->dDtOperIni) or !empty($oGet->dDtOperFim)) {
  $dDtOperIni       = empty($oGet->dDtOperIni)?"01/01/1900":$oGet->dDtOperIni;
  $oGet->dDtOperIni = $dDtOperIni; /* Salva Periodo da Selecao */

  if($oGet->sConsideraPosterior=="N") {
    $dDtOperFim       = empty($oGet->dDtOperFim)?"01/01/9999":$oGet->dDtOperFim;
    $oGet->dDtOperFim = $dDtOperFim; /* Salva Periodo da Selecao */
  } else {
    $dDtOperFim = "01/01/9999";
  }

  $sSqlEtapa["01"] .= "   AND k22_dtoper BETWEEN '".to_dbdate($dDtOperIni)."' AND '".to_dbdate($dDtOperFim)."' \n";
}

/* Valida "Data de Vencimento" Inicio e Fim */
if (!empty($oGet->dDtVencIni) or !empty($oGet->dDtVencFim)) {
  $dDtVencIni       = empty($oGet->dDtVencIni)?"01/01/1900":$oGet->dDtVencIni;
  $oGet->dDtVencIni = $dDtVencIni; /* Salva Periodo da Selecao */

  if($oGet->sConsideraPosterior=="N") {
    $dDtVencFim       = empty($oGet->dDtVencFim)?"01/01/9999":$oGet->dDtVencFim;
    $oGet->dDtVencFim = $dDtVencFim; /* Salva Periodo da Selecao */
  } else {
    $dDtVencFim = "01/01/9999";
  }

  $sSqlEtapa["01"] .= "   AND k22_dtvenc BETWEEN '".to_dbdate($dDtVencIni)."' AND '".to_dbdate($dDtVencFim)."' \n";
}

/* Verifica "Número das parcelas em atraso" */
if (!empty($oGet->iNroParcAtrasoIni) AND !empty($oGet->iNroParcAtrasoFim)) {
  $sSqlEtapa["01"] .= "   AND (k22_numpar BETWEEN {$oGet->iNroParcAtrasoIni} AND {$oGet->iNroParcAtrasoFim} AND \n";
  $sSqlEtapa["01"] .= "        k22_dtvenc < '{$dDataBase}') \n";
} else if (!empty($oGet->iNroParcAtrasoIni) AND empty($oGet->iNroParcAtrasoFim)) {
  $sSqlEtapa["01"] .= "   AND (k22_numpar >= {$oGet->iNroParcAtrasoIni} AND \n";
  $sSqlEtapa["01"] .= "        k22_dtvenc < '{$dDataBase}') \n";
} else if (empty($oGet->iNroParcAtrasoIni) AND !empty($oGet->iNroParcAtrasoFim)) {
  $sSqlEtapa["01"] .= "   AND (k22_numpar <= {$oGet->iNroParcAtrasoFim} AND \n";
  $sSqlEtapa["01"] .= "        k22_dtvenc < '{$dDataBase}') \n";
}



/* Verifica "Qtd das parcelas em atraso" */
$sWhereParcelas = "";
if (!empty($oGet->iQtdParcAtrasoIni) AND !empty($oGet->iQtdParcAtrasoFim)) {
  $sWhereParcelas .= " NOT BETWEEN {$oGet->iQtdParcAtrasoIni} AND {$oGet->iQtdParcAtrasoFim} \n";
} else if (!empty($oGet->iQtdParcAtrasoIni) AND empty($oGet->iQtdParcAtrasoFim)) {
  $sWhereParcelas .= " <= {$oGet->iQtdParcAtrasoIni} \n";
} else if (empty($oGet->iQtdParcAtrasoIni) AND !empty($oGet->iQtdParcAtrasoFim)) {
  $sWhereParcelas .= " >= {$oGet->iQtdParcAtrasoFim} \n";
}

/* Verifica Quantidade a Listar */
if (!empty($oGet->iQtdListar)) {
  $sSqlEtapa["01"] .= " LIMIT {$oGet->iQtdListar} \n";
}




$sSqlEtapa["01"] .= ";\n";

$sSqlEtapa["01.1"] = "CREATE INDEX w_debitos_divida_origem_in ON w_debitos_divida({$sOrigem}); \n";
$sSqlEtapa["01.2"] = "CREATE INDEX w_debitos_divida_numpre_in ON w_debitos_divida(k22_numpre); \n";
$sSqlEtapa["01.3"] = "CREATE INDEX w_debitos_divida_numpar_in ON w_debitos_divida(k22_numpar); \n";
$sSqlEtapa["01.4"] = "ANALYZE w_debitos_divida; \n";


/* Verifica "Não Considerar Notificados até" */
if (!empty($oGet->dNotifDataLimite)) {
  $sSqlEtapa["02"]  = "DELETE \n";
  $sSqlEtapa["02"] .= "  FROM w_debitos_divida a \n";
  $sSqlEtapa["02"] .= " WHERE EXISTS (SELECT 1 \n";

  switch ($oGet->sTipoLista) {
    case "N":
    case "C":
      $sSqlEtapa["02"] .= "                 FROM notinumcgm \n";
      $sSqlEtapa["02"] .= "                      INNER JOIN notificacao ON k50_notifica = k57_notifica \n";
      $sSqlEtapa["02"] .= "                      INNER JOIN notidebitos ON k53_notifica = k57_notifica \n";
      $sSqlEtapa["02"] .= "                WHERE k57_numcgm = a.k22_numcgm \n";
      break;
    case "M":
      $sSqlEtapa["02"] .= "                 FROM notimatric \n";
      $sSqlEtapa["02"] .= "                      INNER JOIN notificacao ON k50_notifica = k55_notifica \n";
      $sSqlEtapa["02"] .= "                      INNER JOIN notidebitos ON k53_notifica = k55_notifica \n";
      $sSqlEtapa["02"] .= "                WHERE k55_matric = a.k22_matric \n";
      break;
    case "I":
      $sSqlEtapa["02"] .= "                 FROM notiinscr \n";
      $sSqlEtapa["02"] .= "                      INNER JOIN notificacao ON k50_notifica = k56_notifica \n";
      $sSqlEtapa["02"] .= "                      INNER JOIN notidebitos ON k53_notifica = k56_notifica \n";
      $sSqlEtapa["02"] .= "                WHERE k56_inscr = a.k22_inscr \n";
      break;
  }
  $sSqlEtapa["02"] .= "                  AND k50_dtemite <= '".to_dbdate($oGet->dNotifDataLimite)."' \n";
	$sSqlEtapa["02"] .= "                  AND k50_instit = {$iInstit} \n";



  /* Tipo de Debito */
  if ($oGet->iNotifTipo == 1) {
    $sSqlEtapa["02"] .= "                  AND a.k22_tipo IN ({$oGet->sTiposDebitos}) \n";
  /* Numpre e Parcela */
  } else if ($oGet->iNotifTipo == 2) {
    $sSqlEtapa["02"] .= "                  AND a.k22_numpre = k53_numpre \n";
    $sSqlEtapa["02"] .= "                  AND a.k22_numpar = k53_numpar \n";
  }

  $sSqlEtapa["02"] .= "); \n";
}

if ($lDebug) {
  echo "<pre>";
}


/**
 *  2.5a Etapa
 *
 * Eliminar Registros onde a Data do Recibo for maior ou igual a data especificado no campo "Desconsiderar Débitos com
 * recibo válido após"
 *
 */
if (isset($oGet->dtDesconsiderarDebitos) && $oGet->dtDesconsiderarDebitos !="") {

  $sSqlEtapa["02.5"]  = "DELETE \n";
  $sSqlEtapa["02.5"] .= "  FROM w_debitos_divida a \n";
  $sSqlEtapa["02.5"] .= " USING recibopaga \n";
  $sSqlEtapa["02.5"] .= "         WHERE recibopaga.k00_numpre = a.k22_numpre \n";
  $sSqlEtapa["02.5"] .= "           AND recibopaga.k00_dtpaga >= '".to_dbdate($oGet->dtDesconsiderarDebitos)."'; \n";

}

/***
 *
 * 2a Etapa
 *
 * Filtrando Listas pela Origem (CGM, Matricula ou Inscricao) e Exercicio
 *
 */

$sSqlEtapa["03"]  = "DROP TABLE IF EXISTS w_debitos_divida_exercicio; \n";
$sSqlEtapa["03"] .= "CREATE TEMP TABLE w_debitos_divida_exercicio AS \n";
$sSqlEtapa["03"] .= "SELECT {$sOrigem}, \n";
$sSqlEtapa["03"] .= "       k22_exerc \n";
$sSqlEtapa["03"] .= "  FROM w_debitos_divida \n";
$sSqlEtapa["03"] .= " GROUP BY {$sOrigem}, k22_exerc; \n";


/***
 *
 * 3a Etapa
 *
 * Criando Indices na tabela temporaria dos Exercicios por Origem
 *
 */

$sSqlEtapa["04"]  = "CREATE INDEX w_debitos_divida_exercicio_1 ON w_debitos_divida_exercicio({$sOrigem}); \n";
$sSqlEtapa["04"] .= "CREATE INDEX w_debitos_divida_exercicio_2 ON w_debitos_divida_exercicio(k22_exerc); \n";


/***
 *
 * 4a e 5a Etapa
 *
 * Eliminar Origens que nao estiver no periodo de exercicio
 * Des de que seja informado os exercícios inicial e final
 *
 */
if (!empty($oGet->iExercIni) && !empty($oGet->iExercFim)) {
  $sSqlEtapa["05.1"]  = "DELETE \n";
  $sSqlEtapa["05.1"] .= "  FROM w_debitos_divida_exercicio a \n";
  $sSqlEtapa["05.1"] .= " WHERE NOT EXISTS (SELECT 1 \n";
  $sSqlEtapa["05.1"] .= "                     FROM w_debitos_divida_exercicio b \n";
  $sSqlEtapa["05.1"] .= "                    WHERE b.{$sOrigem} = a.{$sOrigem} \n";
  $sSqlEtapa["05.1"] .= "                      AND b.k22_exerc BETWEEN {$oGet->iExercIni} AND {$oGet->iExercFim}); \n";
}

if (!empty($oGet->dDtOperIni) or !empty($oGet->dDtOperFim)) {
	$sSqlEtapa["05.2"]  = "DELETE \n";
  $sSqlEtapa["05.2"] .= "  FROM w_debitos_divida_exercicio a \n";
  $sSqlEtapa["05.2"] .= " WHERE NOT EXISTS (SELECT 1 \n";
  $sSqlEtapa["05.2"] .= "                     FROM w_debitos_divida b \n";
  $sSqlEtapa["05.2"] .= "                    WHERE b.{$sOrigem} = a.{$sOrigem} \n";
  $sSqlEtapa["05.2"] .= "                      AND b.k22_dtoper BETWEEN '".to_dbdate($dDtOperIni)."' AND '".to_dbdate($dDtOperFim)."'); \n";
}

if (!empty($oGet->dDtVencIni) or !empty($oGet->dDtVencFim)) {
  $sSqlEtapa["05.3"]  = "DELETE \n";
  $sSqlEtapa["05.3"] .= "  FROM w_debitos_divida_exercicio a \n";
  $sSqlEtapa["05.3"] .= " WHERE NOT EXISTS (SELECT 1 \n";
  $sSqlEtapa["05.3"] .= "                     FROM w_debitos_divida b \n";
  $sSqlEtapa["05.3"] .= "                    WHERE b.{$sOrigem} = a.{$sOrigem} \n";
  $sSqlEtapa["05.3"] .= "                      AND b.k22_dtvenc BETWEEN '".to_dbdate($dDtVencIni)."' AND '".to_dbdate($dDtVencFim)."'); \n";
}


$sSqlEtapa["06.1"]  = "DELETE \n";
$sSqlEtapa["06.1"] .= "  FROM w_debitos_divida a \n";
$sSqlEtapa["06.1"] .= " WHERE NOT EXISTS (SELECT 1 \n";
$sSqlEtapa["06.1"] .= "                     FROM w_debitos_divida_exercicio b \n";
$sSqlEtapa["06.1"] .= "                    WHERE b.{$sOrigem} = a.{$sOrigem}); \n";

if (!empty($oGet->dDtOperIni) or !empty($oGet->dDtOperFim)) {
	$sSqlEtapa["06.2"]  = "DELETE \n";
  $sSqlEtapa["06.2"] .= "  FROM w_debitos_divida a \n";
  $sSqlEtapa["06.2"] .= " WHERE NOT EXISTS (SELECT 1 \n";
  $sSqlEtapa["06.2"] .= "                     FROM w_debitos_divida b \n";
  $sSqlEtapa["06.2"] .= "                    WHERE b.{$sOrigem} = a.{$sOrigem} \n";
  $sSqlEtapa["06.2"] .= "                      AND b.k22_dtoper BETWEEN '".to_dbdate($dDtOperIni)."' AND '".to_dbdate($dDtOperFim)."'); \n";
}

if (!empty($oGet->dDtVencIni) or !empty($oGet->dDtVencFim)) {
  $sSqlEtapa["06.3"]  = "DELETE \n";
  $sSqlEtapa["06.3"] .= "  FROM w_debitos_divida a \n";
  $sSqlEtapa["06.3"] .= " WHERE NOT EXISTS (SELECT 1 \n";
  $sSqlEtapa["06.3"] .= "                     FROM w_debitos_divida b \n";
  $sSqlEtapa["06.3"] .= "                    WHERE b.{$sOrigem} = a.{$sOrigem} \n";
  $sSqlEtapa["06.3"] .= "                      AND b.k22_dtvenc BETWEEN '".to_dbdate($dDtVencIni)."' AND '".to_dbdate($dDtVencFim)."'); \n";
}


/***
 *
 * 6a Etapa
 *
 * Eliminar Registros que nao se enquadrem na Qtde de Parcelas em Atraso
 *
 */
if (!empty($sWhereParcelas)) {
  $sSqlEtapa["07"]  = "DELETE \n";
  $sSqlEtapa["07"] .= "  FROM w_debitos_divida a \n";
  $sSqlEtapa["07"] .= " WHERE (SELECT COUNT(DISTINCT k00_numpar) \n";
  $sSqlEtapa["07"] .= "          FROM arrecad \n";
  $sSqlEtapa["07"] .= "         WHERE arrecad.k00_numpre = a.k22_numpre \n";
  $sSqlEtapa["07"] .= "           AND arrecad.k00_dtvenc < '{$dDataBase}') {$sWhereParcelas}; \n";
}

/***
 *
 * 7a Etapa
 *
 * Criando lista de Origems e Valor Total dos Debitos
 *
 */
$sSqlEtapa["08"]  = "DROP TABLE IF EXISTS w_debitos_divida_valor; \n";
$sSqlEtapa["08"] .= "CREATE TEMP TABLE w_debitos_divida_valor AS \n";
$sSqlEtapa["08"] .= " SELECT b.{$sOrigem}, \n";

if (!empty($oGet->sTiposDebitos)) {
  $sSqlEtapa["08"] .= "        b.k22_tipo, \n";
}
$sSqlEtapa["08"] .= "        array_accum( \n";
$sSqlEtapa["08"] .= "          CAST(k22_data     AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_numpre   AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_numpar   AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_receit   AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_dtvenc   AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_dtoper   AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_hist     AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_numcgm   AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_matric   AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_inscr    AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_tipo     AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_vlrhis   AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_vlrcor   AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_juros    AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_multa    AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_desconto AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_exerc    AS text) ||'-'|| \n";
$sSqlEtapa["08"] .= "          CAST(k22_instit   AS text) \n";
$sSqlEtapa["08"] .= "        ) as debitos, \n";
$sSqlEtapa["08"] .= "        SUM( COALESCE(b.k22_vlrcor,   0) + \n";
$sSqlEtapa["08"] .= "             COALESCE(b.k22_juros,    0) + \n";
$sSqlEtapa["08"] .= "             COALESCE(b.k22_multa,    0) - \n";
$sSqlEtapa["08"] .= "             COALESCE(b.k22_desconto, 0) ) AS valor_total \n";
$sSqlEtapa["08"] .= "   FROM w_debitos_divida b \n";
$sSqlEtapa["08"] .= "  GROUP BY b.{$sOrigem} \n";

if (!empty($oGet->sTiposDebitos)) {
  $sSqlEtapa["08"] .= "           ,b.k22_tipo \n";
}
$sSqlEtapa["08"] .= " HAVING SUM( COALESCE(b.k22_vlrcor,   0) + \n";
$sSqlEtapa["08"] .= "             COALESCE(b.k22_juros,    0) + \n";
$sSqlEtapa["08"] .= "             COALESCE(b.k22_multa,    0) - \n";
$sSqlEtapa["08"] .= "             COALESCE(b.k22_desconto, 0) ) BETWEEN {$oGet->nValorIni} AND {$oGet->nValorFim}; \n";

$sSqlEtapa["09"]  = "CREATE INDEX w_debitos_divida_valor_1 ON w_debitos_divida_valor({$sOrigem}); \n";

$sSqlEtapa["09.1"]  = "DELETE \n";
$sSqlEtapa["09.1"] .= "  FROM w_debitos_divida_valor \n";
$sSqlEtapa["09.1"] .= " WHERE valor_total <= 0; \n";

$sSqlEtapa["10"]  = "DELETE \n";
$sSqlEtapa["10"] .= "  FROM w_debitos_divida a \n";
$sSqlEtapa["10"] .= " WHERE NOT EXISTS (SELECT 1 \n";
$sSqlEtapa["10"] .= "                     FROM w_debitos_divida_valor b \n";
$sSqlEtapa["10"] .= "                    WHERE b.{$sOrigem} = a.{$sOrigem} \n";


if (!empty($oGet->sTiposDebitos)) {
  $sSqlEtapa["10"] .= "                      AND b.k22_tipo = a.k22_tipo \n";
}
$sSqlEtapa["10"] .= "                      AND CAST(k22_data     AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_numpre   AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_numpar   AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_receit   AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_dtvenc   AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_dtoper   AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_hist     AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_numcgm   AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_matric   AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_inscr    AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_tipo     AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_vlrhis   AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_vlrcor   AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_juros    AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_multa    AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_desconto AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_exerc    AS text) ||'-'|| \n";
$sSqlEtapa["10"] .= "                          CAST(k22_instit   AS text) = ANY(debitos)); \n";

$iCountSql = count($sSqlEtapa);
$iLinha    = 0;

$lSqlErro  = false;
$sErroMsg  = "";

foreach($sSqlEtapa as $sSql) {

  db_atutermometro($iLinha, $iCountSql, "termometro_item", 1, "Passo ".($iLinha+1)." de {$iCountSql}");

  if (!empty($sSql)) {
    if ($lDebug) {
      echo "<br>Passo ".($iLinha+1)."<br><br>";
      echo "-->".(((int)$iLinha)+1) .") {$sSql}<br>";
    }
    $xStart = time();

    $rResult = db_query($sSql);

    if (!$rResult) {

      $oParms = new stdClass();
      $oParms->sSql = $sSql;
      $oParms->sResultado = pg_result_error($rResult);
      $lSqlErro = true;
      $sErroMsg = _M('tributario.notificacoes.cai4_lista002.erro_executar_query', $oParms);
      break;
    }
    if ($lDebug) {
      echo "<br>Passo ".($iLinha+1)." OK - Tempo decorrido: ".db_formatatempodecorrido($xStart, time())."<br><br>";
    }
  }

  $iLinha++;
}

/* Registra tempo de processamento */
$tFim = time();
$sFiltro .= " # Fim Processamento: ".date("d/m/Y H:i:s", $tFim);
$sFiltro .= " # Tempo Processamento: ".db_formatatempodecorrido($tInicio, $tFim);

if ($lDebug) {
  echo str_replace("#", "\n", $sFiltro);
  echo "</pre>";
  flush();
}

db_inicio_transacao();

if (!$lSqlErro) {
  $cllista->k60_datadeb = to_dbdate($oGet->dDataDebitos);
  $cllista->k60_usuario = $iUsuario;
  $cllista->k60_filtros = $sFiltro;
  $cllista->k60_instit  = $iInstit;
  $cllista->k60_descr   = $oGet->sDescricaoLista;
  $cllista->k60_tipo    = $oGet->sTipoLista;
  $cllista->incluir(null);

  if ($cllista->erro_status == "0") {
    $lSqlErro = true;
    $sErroMsg = $cllista->erro_msg;
  }

  if (!$lSqlErro) {

  	$sWhereArretipo = "";
    if (!empty($oGet->sTiposDebitos)) {
      $sOperadorTipoDebito = ($oGet->iOpcaoTipoDebito==1)?"IN":"NOT IN";
      $sWhereArretipo = "   AND k00_tipo {$sOperadorTipoDebito} ({$oGet->sTiposDebitos}) ";
    }

    $sSqlArretipo = $clarretipo->sql_query_file(null, "k00_tipo", "k00_tipo", " k00_instit = {$iInstit} {$sWhereArretipo}");
    $rArretipo    = $clarretipo->sql_record($sSqlArretipo);

    if ($clarretipo->numrows > 0) {

      $oParms = new stdClass();
      for ($i=0; $i<$clarretipo->numrows; $i++) {

        $oParms->iLista = $cllista->k60_codigo;
        db_atutermometro($i, $clarretipo->numrows, "termometro_item", 1, _M('tributario.notificacoes.cai4_lista002.processando_tipos_debitos', $oParms));

        $oArretipo = db_utils::fieldsMemory($rArretipo, $i);

        $cllistatipos->k62_lista   = $cllista->k60_codigo;
        $cllistatipos->k62_tipodeb = $oArretipo->k00_tipo;
        $cllistatipos->incluir($cllista->k60_codigo, $oArretipo->k00_tipo);

        if ($cllistatipos->erro_status == "0") {
          $lSqlErro = true;
          $sErroMsg = $cllistatipos->erro_msg;
          break;
        }
      }
    }

    if (!$lSqlErro) {

      $sSqlInclui  = "INSERT INTO listadeb(k61_codigo,k61_numpre,k61_numpar) ";
      $sSqlInclui .= "SELECT DISTINCT {$cllista->k60_codigo}, k22_numpre, k22_numpar ";
      $sSqlInclui .= "  FROM w_debitos_divida ";
      $rResult = db_query($sSqlInclui);

      if (!$rResult) {

        $oParms = new stdClass();
        $oParms->sSqlInclui = $sSqlInclui;
        $oParms->sResultado = pg_result_error($rResult);
        $lSqlErro = true;
        $sErroMsg = _M('tributario.notificacoes.cai4_lista002.erro_executar_query_inclui', $oParms);
        break;
      }

    }

  }
}

if (!$lSqlErro) {
  $sErroMsg = $cllista->erro_msg;
}

if ($lDebug) {
  $lSqlErro = true;
}

db_fim_transacao($lSqlErro);

db_msgbox($sErroMsg);

if (!$lDebug) {
  echo "<script>";
  echo " parent.js_fimprocessamento();";
  echo "</script>";
}

?>
</body>
</html>
<?
//monta uma lista com os nomes dos contribuintes indicados por matrículas, inscrições ou cgm
function getContribuintes($sTipo, $sIdsContribuintes) {

	$sVirgula      = '';
  $sContribuinte = '';
	if($sTipo == 'M') {
		$sClasse = 'iptubase';
		$sCampo  = 'j01_matric';
	} else if($sTipo == 'I') {
		$sClasse = 'issbase';
		$sCampo  = 'q02_inscr';
	} else {
		$sClasse = 'cgm';
		$sCampo  = 'z01_numcgm';
	}

	$oDao  = db_utils::getDao($sClasse);
	$rsSql = $oDao->sql_record($oDao->sql_query(null, "{$sCampo} ||'-'|| z01_nome as z01_nome", "z01_nome", "$sCampo in ($sIdsContribuintes)"));

  if($oDao->numrows > 0) {
  	for($i = 0; $i < $oDao->numrows; $i++) {
  		$sContribuinte .= $sVirgula . db_utils::fieldsMemory($rsSql, $i)->z01_nome;
  		$sVirgula       = ', ';
  	}
  }
	return $sContribuinte;
}

function getDescricao($sClasse, $sCampoDescr, $sWhere) {

	$sConcat    = '';
	$sVirgula   = '';
	$sDescricao = '';

	$oDao  = db_utils::getDao($sClasse);

	$rsSql = $oDao->sql_record($oDao->sql_query_file(null, $sCampoDescr, $sCampoDescr, $sWhere));

	if($oDao->numrows > 0) {
		for($i = 0; $i < $oDao->numrows; $i++) {
			$sDescricao .= $sVirgula . db_utils::fieldsMemory($rsSql, $i)->$sCampoDescr;
			$sVirgula    = ', ';
		}
	}

	return $sDescricao;

}

?>