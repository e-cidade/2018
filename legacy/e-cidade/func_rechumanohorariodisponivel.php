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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

/**
 * oGet
 * oGet->turma     => opicional
 * oGet->regencia  => obrigatorio
 * oGet->regente   => opicional
 * oGet->funcao_js => obrigatorio
 */

$oGet          = db_utils::postMemory($_GET);
$oDaoRecHumano = new cl_rechumano();

$iEscola        = db_getsession("DB_coddepto");
$aWhereSubQuery = array();

$aWhereSubQuery[] = " ed58_i_regencia = {$oGet->regencia} ";
$aWhereSubQuery[] = " ed17_i_escola   = {$iEscola} ";
$aWhereSubQuery[] = " ed58_ativo      = true ";

$sCamposSubQuery  = " distinct                        ";
$sCamposSubQuery .= " ed58_i_periodo    as periodo,   ";
$sCamposSubQuery .= " ed58_i_diasemana  as diasemana, ";
$sCamposSubQuery .= " ed59_i_disciplina as disciplina,";
$sCamposSubQuery .= " ed29_i_ensino     as ensino,    ";
$sCamposSubQuery .= " ed57_i_calendario as calendario, ";
$sCamposSubQuery .= " ed52_i_ano        as ano ";

$sWhereDiaPeriodoRegencia = implode(" and ", $aWhereSubQuery);

/**
 * 1º Query
 * A query tem por objetivo buscar qual os periodos e dias da semana que a regencia é oferecida
 * Também buscamos o nível de ensino para o qual é ofertada e qual o código de disciplina a regencia se refere
 */
$oDaoRegenciaHorario    = new cl_regenciahorario();
$sSqlDiaPeriodoRegencia = $oDaoRegenciaHorario->sql_query_regencia_ofertada(null, $sCamposSubQuery, null,
		                                                                        $sWhereDiaPeriodoRegencia);
$rsDiaPeriodoRegencia   = $oDaoRegenciaHorario->sql_record($sSqlDiaPeriodoRegencia);
$iRegistros             = $oDaoRegenciaHorario->numrows;

if ($iRegistros == 0) {
	
	$sMsqErro = "Não foi encontrado vinculo da regencia com nenhuma grade de horario.";
	db_redireciona("db_erros.php?fechar=true&db_erro={$sMsqErro}");
}

$aPeriodos   = array();
$aDiaSemana  = array();
$iDisciplina = '';
$iEnsino     = '';
$iCalendario = '';
$iAno        = '';

for ($i = 0; $i < $iRegistros; $i++) {
	
	$oDiaPeriodoRegencia = db_utils::fieldsMemory($rsDiaPeriodoRegencia, $i);
	$aPeriodos[]         = $oDiaPeriodoRegencia->periodo;
	$aDiaSemana[]        = $oDiaPeriodoRegencia->diasemana;
	$iDisciplina         = $oDiaPeriodoRegencia->disciplina;
	$iEnsino             = $oDiaPeriodoRegencia->ensino;
	$iCalendario         = $oDiaPeriodoRegencia->calendario;
	$iAno                = $oDiaPeriodoRegencia->ano;
	$aPeriodosNoDia[$oDiaPeriodoRegencia->diasemana][] = $oDiaPeriodoRegencia->periodo;
}

/**
 * 2º Query
 * Apos identificar os periodos em que a regencia e ofertada,
 * devemos ver quais professores podem lecionar a disciplina
 * O Resultado desta query é todos os rechuma que podem lecionar esta disciplina;
 */

$sDiaSemana = implode(" ,", array_unique($aDiaSemana));
$sPeriodos  = implode(" ,", $aPeriodos);

$sWhereBuscaRecHumano  = "     ed01_c_docencia = 'S'";
$sWhereBuscaRecHumano .= " and ed33_i_periodo   in ({$sPeriodos})   ";
$sWhereBuscaRecHumano .= " and ed33_i_diasemana in ({$sDiaSemana})  ";
$sWhereBuscaRecHumano .= " and ed25_i_ensino     = {$iEnsino}       ";
$sWhereBuscaRecHumano .= " and ed23_i_disciplina = {$iDisciplina}   ";
$sWhereBuscaRecHumano .= " and ed75_i_escola     = {$iEscola}       ";

if (isset($oGet->regente) && !empty($oGet->regente)) {
	$sWhereBuscaRecHumano .= " and ed20_i_codigo    <> {$oGet->regente} ";
}

$sCampoBuscaRecHumano  = "distinct ed20_i_codigo ";

$oDaoHorarioDisp = new cl_rechumanohoradisp();
$sSqlRecHumano   = $oDaoHorarioDisp->sql_query_disponivel_periodo(null, $sCampoBuscaRecHumano, null, $sWhereBuscaRecHumano);
$rsRecHumano     = $oDaoHorarioDisp->sql_record($sSqlRecHumano);

$iRegistrosRecHumano = $oDaoHorarioDisp->numrows;

if ($iRegistrosRecHumano == 0) {

	$sMsqErro = "Nenhum regente com disponibilidade para lecionar disciplina selecionada.";
	db_redireciona("db_erros.php?fechar=true&db_erro={$sMsqErro}");
}

$aRecHumano = array();

for ($i = 0; $i < $iRegistrosRecHumano; $i++) {
	$aRecHumano[] = db_utils::fieldsMemory($rsRecHumano, $i)->ed20_i_codigo;
}

$sRecHumanos           = implode(', ', $aRecHumano);
$aWhereDisponibilidade = array();
foreach ($aPeriodosNoDia as  $iDia => $aPeriodos) {
  
  $sPeriodos               = implode(",", $aPeriodos);
  $sWhereDiaSemana         = "(docentetemdisponibilidade(ed20_i_codigo::integer, array[$sPeriodos], $iDia, $iEscola, $iAno) is true)";
  $aWhereDisponibilidade[] = $sWhereDiaSemana;
}


/**
 * 3º Query
 * Por ultimo buscamos os dados do rechuma que ainda não tem o horario alocado
 * para os dias e periodos do calendario da regencia
 */
$sCampoDisponiveis  = " distinct                        ";
$sCampoDisponiveis .= " ed20_i_codigo,                  ";
$sCampoDisponiveis .= " case                            ";
$sCampoDisponiveis .= "    when ed20_i_tiposervidor = 1 ";
$sCampoDisponiveis .= "      then cgmrh.z01_numcgm      ";
$sCampoDisponiveis .= "    else cgmcgm.z01_numcgm       ";
$sCampoDisponiveis .= " end as z01_numcgm,              ";
$sCampoDisponiveis .= " case                            ";
$sCampoDisponiveis .= "    when ed20_i_tiposervidor = 1 ";
$sCampoDisponiveis .= "      then trim(cgmrh.z01_nome)  ";
$sCampoDisponiveis .= "    else trim(cgmcgm.z01_nome)   ";
$sCampoDisponiveis .= " end as z01_nome                 ";

$sWhereDisponiveis  = "     ed20_i_codigo in ({$sRecHumanos})";

$sWhereDisponiveis .= "  and (".implode(" and ", $aWhereDisponibilidade).")";

$sSqlRegenteDisponivel = $oDaoRecHumano->sql_query_rechumano_cgm(null, $sCampoDisponiveis, 'z01_nome', $sWhereDisponiveis);
$rsRegenteDisponivel   = $oDaoRecHumano->sql_record($sSqlRegenteDisponivel);
$iRegistrosDisponivel  = $oDaoRecHumano->numrows;

?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="estilos.css" rel="stylesheet" type="text/css">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
	</head>
<body>
<center>
	<div>
	<?php
		if ($iRegistrosDisponivel > 0) {
			db_lovrot($sSqlRegenteDisponivel, 15, "()", "", $oGet->funcao_js, "", "NoMe", array());
		} else {
			echo "<h3>Sem regente disponível para a regência selecionada</h3>";
		}
	?>
	</div>
</center>
</body>
</html>