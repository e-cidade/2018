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
require_once ("fpdf151/pdf.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/DBDate.php");
require_once ("libs/JSON.php");

$oGet            = db_utils::postMemory( $_GET );
$sWhere          = '';
$aWhere          = array();
$aBairros        = array();
$aDadosAgrupados = array();

/**
 * Objeto com as informações padrão do relatório
 */
$oFiltros                       = new stdClass();
$oFiltros->aAgravosSelecionados = array();
$oFiltros->iAltura              = 4;
$oFiltros->iLargura             = 192;

/**
 * Largura de cada campo a ser impresso no relatório
 */
$oFiltros->iNome       = 110;
$oFiltros->iNascimento = 35;
$oFiltros->iTelefone   = 30;
$oFiltros->iGestante   = 15;
$oFiltros->iEndereco   = 192;

/**
 * Valida se as datas foram preenchidas, adicionando a condição do SQL
 */
if(    isset( $oGet->dtInicial ) && !empty( $oGet->dtInicial )
    && isset( $oGet->dtFinal ) && !empty( $oGet->dtFinal )
  ) {

  $oDataInicio  = new DBDate( $oGet->dtInicial );
  $oDataFim     = new DBDate( $oGet->dtFinal );
  $sWhereData   = "s152_d_dataconsulta between '{$oDataInicio->convertTo( DBDate::DATA_EN )}'";
  $sWhereData  .= " and '{$oDataFim->convertTo( DBDate::DATA_EN )}'";
  $aWhere[]     = $sWhereData;
}

/**
 * Valida o filtro selecionado em relação ao gestante
 */
if( isset( $oGet->iFiltroGestante ) && !empty( $oGet->iFiltroGestante ) ) {

  switch( $oGet->iFiltroGestante ) {

    case 2:

      $aWhere[] = "s167_gestante is true";
      break;

    case 3:

      $aWhere[] = "s167_gestante is false";
      break;
  }
}

/**
 * Valida os tipos de agravo selecionados
 */
if( isset( $oGet->aAgravos ) && !empty( $oGet->aAgravos ) ) {
  $aWhere[] = "s167_sau_cid in( {$oGet->aAgravos} )";
}

/**
 * Busca os bairros de pacientes que possuem agravo. Caso tenha sido selecionado algum agravo, filtra por estes
 */
$oDaoBairro     = new cl_bairro();
$sCamposBairro  = "j13_descr";
$sWhereBairro   = "";
$lFiltrarBairro = false;

if( isset( $oGet->aBairros ) && !empty( $oGet->aBairros ) ) {

  $lFiltrarBairro = true;
  $sWhereBairro   = "j13_codi in( {$oGet->aBairros} )";
}

$sSqlBairro = $oDaoBairro->sql_query_file( null, $sCamposBairro, null, $sWhereBairro );
$rsBairro   = db_query( $sSqlBairro );

if( $rsBairro && pg_num_rows( $rsBairro ) > 0 ) {

  $iTotalBairros = pg_num_rows( $rsBairro );

  /**
   * Percorre os bairros retornados, indexando o array dos dados agrupados que serão impressos pelo nome do bairro,
   * recebendo um stdClass com o array do Agravos que serão agrupados por bairro.
   * Cria a string para preenchimento da condição do SQL
   */
  for( $iContador = 0; $iContador < $iTotalBairros; $iContador++ ) {

    $sBairro                     = db_utils::fieldsMemory( $rsBairro, $iContador )->j13_descr;
    $oBairro                     = new stdClass();
    $aAgravos                    = array();
    $aDadosAgrupados[ $sBairro ] = $aAgravos;

    $sBairro    = "'{$sBairro}'";
    $aBairros[] = $sBairro;
  }
}

if( $lFiltrarBairro ) {

  $sBairros = implode( ", ", $aBairros );
  $aWhere[] = "z01_v_bairro in( {$sBairros} )";
}

/**
 * Busca os dados a serem impressos, de acordo com os filtros informados
 */
$oDaoTriagemAgravo     = new cl_sau_triagemavulsaagravo();
$sCamposTriagemAgravo  = "sd70_i_codigo, sd70_c_nome, z01_v_nome, z01_d_nasc, z01_v_ender, z01_i_numero, z01_v_compl";
$sCamposTriagemAgravo .= ", z01_v_bairro, z01_v_telef, s167_gestante";
$sWhereTriagemAgravo   = implode( " and ", $aWhere );
$sSqlTriagemAgravo     = $oDaoTriagemAgravo->sql_query( null, $sCamposTriagemAgravo, null, $sWhereTriagemAgravo );
$rsTriagemAgravo       = db_query( $sSqlTriagemAgravo );

if ( !$rsTriagemAgravo || pg_num_rows( $rsTriagemAgravo ) == 0 ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}

$iTotalTriagemAgravo = pg_num_rows( $rsTriagemAgravo );

/**
 * Percorre os dados retornados e monta o array agrupado por Bairro > Agravo > demais informações
 */
for( $iContador = 0; $iContador < $iTotalTriagemAgravo; $iContador++ ) {

  $oDadosRetorno              = db_utils::fieldsMemory( $rsTriagemAgravo, $iContador );
  $oDadosAgravo               = new stdClass();
  $oDadosAgravo->sNome        = $oDadosRetorno->z01_v_nome;
  $oDadosAgravo->sEndereco    = $oDadosRetorno->z01_v_ender;
  $oDadosAgravo->iNumero      = $oDadosRetorno->z01_i_numero;
  $oDadosAgravo->sComplemento = $oDadosRetorno->z01_v_compl;
  $oDadosAgravo->sTelefone    = $oDadosRetorno->z01_v_telef;
  $oDadosAgravo->sGestante    = $oDadosRetorno->s167_gestante == 't' ? 'SIM' : 'NÃO';

  $oDataNascimento            = new DBDate( $oDadosRetorno->z01_d_nasc );
  $oDadosAgravo->sNascimento  = $oDataNascimento->convertTo( DBDate::DATA_PTBR );

  if( array_key_exists( $oDadosRetorno->z01_v_bairro, $aDadosAgrupados ) ) {
    $aDadosAgrupados[ $oDadosRetorno->z01_v_bairro ][ $oDadosRetorno->sd70_i_codigo ][] = $oDadosAgravo;
  }

  $oFiltros->aAgravosSelecionados[ $oDadosRetorno->sd70_i_codigo ] = $oDadosRetorno->sd70_c_nome;
}

$oPdf = new PDF();
$oPdf->Open();

$head1 = "Agravos";
$head3 = "Período: {$oGet->dtInicial} à {$oGet->dtFinal}";

if( $oGet->sQuebraPaginaBairro == 'S' ) {
  quebraPorBairro( $oPdf, $aDadosAgrupados, $oFiltros );
} else {
  semQuebraPorBairro( $oPdf, $aDadosAgrupados, $oFiltros );
}

$oPdf->Output();

/**
 * Cria o relatório, quebrando a página por bairro existente
 * @param PDF      $oPdf
 * @param array    $aDadosAgrupados
 * @param stdClass $oFiltros
 */
function quebraPorBairro( PDF $oPdf, $aDadosAgrupados, $oFiltros ) {

  foreach( $aDadosAgrupados as $sBairro => $aAgravos ) {

    if( count( $aAgravos ) == 0 ) {
      continue;
    }

    $oPdf->AddPage();
    $oPdf->SetFont( 'arial', 'b', 7 );
    $oPdf->SetFillColor( 205, 205, 205 );

    $oFiltros->sBairro = $sBairro;
    $oPdf->Cell( $oFiltros->iLargura, $oFiltros->iAltura, $sBairro, 'TB', 1, 'L', 1 );
    percorreAgravos( $oPdf, $aAgravos, $oFiltros );
  }
}

/**
 * Cria o relatório quebrando a página somente ao chegar ao final da mesma
 * @param PDF      $oPdf
 * @param array    $aDadosAgrupados
 * @param stdClass $oFiltros
 */
function semQuebraPorBairro( PDF $oPdf, $aDadosAgrupados, $oFiltros ) {

  $oPdf->AddPage();
  $oPdf->SetFont( 'arial', 'b', 7 );
  $oPdf->SetFillColor( 205, 205, 205 );

  foreach( $aDadosAgrupados as $sBairro => $aAgravos ) {

    if( count( $aAgravos ) == 0 ) {
      continue;
    }

    $oFiltros->sBairro = $sBairro;
    $oPdf->Cell( $oFiltros->iLargura, $oFiltros->iAltura, "Bairro: {$sBairro}", 'TB', 1, 'L', 1 );
    percorreAgravos( $oPdf, $aAgravos, $oFiltros );
  }
}

/**
 * Percorre os agravos existentes dentro de um bairro, e os pacientes que possuem o agravo
 * @param PDF      $oPdf
 * @param array    $aAgravos
 * @param stdClass $oFiltros
 */
function percorreAgravos( PDF $oPdf, $aAgravos, $oFiltros ) {

  foreach( $aAgravos as $iAgravo => $aPacientes ) {

    $oPdf->SetFont( 'arial', 'b', 7 );
    $oPdf->SetFillColor( 240, 240, 240 );

    $oPdf->Cell( $oFiltros->iLargura, $oFiltros->iAltura, $oFiltros->aAgravosSelecionados[ $iAgravo ], 'TB', 1, 'L', 1 );

    foreach( $aPacientes as $oPaciente ) {

      $oPdf->SetFont( 'arial', '', 6 );
      $oPdf->SetX( 10 );

      $sPaciente = "Paciente: {$oPaciente->sNome}";
      $oPdf->Cell( $oFiltros->iNome, $oFiltros->iAltura, $sPaciente, "T", 0, 'L' );

      $sTelefone = "Dt. Nascimento: {$oPaciente->sNascimento}";
      $oPdf->Cell( $oFiltros->iNascimento, $oFiltros->iAltura, $sTelefone, "T", 0, 'L' );

      $sTelefone = "Telefone: {$oPaciente->sTelefone}";
      $oPdf->Cell( $oFiltros->iTelefone, $oFiltros->iAltura, $sTelefone, "T", 0, 'L' );

      $sGestante = "Gestante: {$oPaciente->sGestante}";
      $oPdf->Cell( $oFiltros->iGestante, $oFiltros->iAltura, $sGestante, "T", 1, 'L' );

      $sEndereco  = "Endereço: {$oPaciente->sEndereco}, {$oPaciente->iNumero}";
      $sEndereco .= !empty( $oPaciente->sComplemento ) ? "/{$oPaciente->sComplemento}" : "";
      $oPdf->Cell( $oFiltros->iEndereco, $oFiltros->iAltura, $sEndereco, "B", 1, 'L' );
    }

    $oPdf->SetFont( 'arial', 'b', 7 );
    $oPdf->SetFillColor( 240, 240, 240 );

    $sTotalPacientes  = "Total de Pacientes com {$oFiltros->aAgravosSelecionados[ $iAgravo ]} no Bairro";
    $sTotalPacientes .= " {$oFiltros->sBairro}: " . count( $aPacientes );
    $oPdf->Cell( $oFiltros->iLargura, $oFiltros->iAltura, $sTotalPacientes, 'TB', 1, 'R', 1 );
  }

  $oPdf->SetFillColor( 205, 205, 205 );
  $sTotalAgravos = "Total de Agravos no Bairro {$oFiltros->sBairro}: " . count( $aAgravos );
  $oPdf->Cell( $oFiltros->iLargura, $oFiltros->iAltura, $sTotalAgravos, 'TB', 1, 'R', 1 );
}