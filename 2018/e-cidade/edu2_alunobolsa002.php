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

require_once("libs/db_utils.php");
require_once("fpdf151/pdfwebseller.php");

$oGet = db_utils::postMemory($_GET);

/**
 * Parametros para configuracao do relatório
*/
$oParametros                    = new stdClass();
$oParametros->iEscola           = $oGet->iEscola;
$oParametros->aListaCalendarios = explode(",", $oGet->aCalendarios);
$oParametros->iEtapa            = $oGet->iEtapa;
$oParametros->lFrequencia       = $oGet->lFrequencia == 'true' ? true : false;

$oDaoSerie     = db_utils::getdao('serie');
$oDaoMatricula = db_utils::getdao('matricula');

/**
 * Altura padrão das linhas
 */
$oParametros->iAlturaLinha = 4;

/**
 * Força escrever o cabeçalho
 */
$oParametros->lEscreverCabecalho = true;

/**
 * Fonte padrao
 */
$oParametros->sFonte  = 'Arial';
$sLabelFrequencia = "Todos";
if ($oParametros->lFrequencia) {
  $sLabelFrequencia = "Somente abaixo da porcentagem mínima exigida.";
}
$iCodigoEscola        = db_getsession("DB_coddepto");
$sWhereMatricula      = '';
$aFiltros    = array("ed47_c_bolsafamilia = 'S'");
$sNomeEtapa  = "TODAS";
$sNomeEscola = "TODAS";
if (!empty($oParametros->iEscola)) {

  $aFiltros[]  = " ed57_i_escola = {$oParametros->iEscola}";
  $oEscola     = EscolaRepository::getEscolaByCodigo($oParametros->iEscola);
  $sNomeEscola = $oEscola->getNome();
} else {

}

if (count($oParametros->aListaCalendarios) > 0) {
  $aFiltros[] = " ed57_i_calendario in(".implode(',', $oParametros->aListaCalendarios).")";
}

if (!empty($oParametros->iEtapa)) {

  $aFiltros[] = "ed11_i_codigo = {$oParametros->iEtapa}";
  $oEtapa     = EtapaRepository::getEtapaByCodigo($oParametros->iEtapa);
  $sNomeEtapa = $oEtapa->getNome()." - ". $oEtapa->getEnsino()->getNome();
}

$aFiltros[] = "ed60_c_situacao = 'MATRICULADO'";
$aFiltros[] = "ed60_c_ativa    = 'S'";
$aFiltros[] = "ed221_c_origem  = 'S'";
$sWhereMatricula = implode(' and ', $aFiltros);

$sDataCorrente     = date("Y-m-d", db_getsession("DB_datausu"));
$sCamposMatricula  = " ed47_v_nome, ed47_i_codigo, ed47_c_nis, ed11_i_codigo, ed11_c_descr, ed52_c_descr, ";
$sCamposMatricula .= " ed11_i_sequencia, ed11_i_ensino, ed57_c_descr, ed57_i_codigo, ed47_c_codigoinep,   ";
$sCamposMatricula .= " ed18_c_nome,ed57_i_escola, date_part('year', age('{$sDataCorrente}'::date, ed47_d_nasc)) as idade";

$sOrderMatricula   = " ed11_i_ensino, ed11_i_sequencia, ed57_c_descr, ed47_v_nome";

$sSqlMatricula     = $oDaoMatricula->sql_query_bolsafamilia("", $sCamposMatricula, $sOrderMatricula, $sWhereMatricula);
$rsMatricula       = $oDaoMatricula->sql_record($sSqlMatricula);
$iNumLinhas        = $oDaoMatricula->numrows;

$aEscolas              = array();
$oIdade                = new stdClass();
$oIdade->iIdadeInicial = 0;
$oIdade->iIdadeFinal   = 15;
$oIdade->nPercentual   = 85;
$oIdade->iTotalAlunos  = 0;
$oIdade->aEscolas      = array();
$aFaixaDeIdades[]      = $oIdade;

$oIdade                = new stdClass();
$oIdade->iIdadeInicial = 0;
$oIdade->iIdadeFinal   = 17;
$oIdade->nPercentual   = 75;
$oIdade->iTotalAlunos  = 0;
$oIdade->aEscolas      = array();
$aFaixaDeIdades[]      = $oIdade;

$iTotalAlunos  = 0;
for ($iCont = 0; $iCont < $iNumLinhas; $iCont++) {

  $oDadosMatricula = db_utils::fieldsmemory($rsMatricula, $iCont);
  
  /**
   * Alunos maiores que 17 Anos, não entram no calculo do bolsa familia
   */
  if ( $oDadosMatricula->idade > 17 ) {
    continue;
  }

  $oFaixaDeIdade = getFaixaDeIdade($oDadosMatricula, $aFaixaDeIdades);
  if (!isset($oFaixaDeIdade->aEscolas[$oDadosMatricula->ed57_i_escola])) {
  
    $oEscola                   = new stdClass();
    $oEscola->iCodigoEscola    = $oDadosMatricula->ed57_i_escola;
    $oEscola->sNomeEscola      = $oDadosMatricula->ed18_c_nome;
    $oEscola->iTotalAlunos     = 0;
    $oEscola->nTotalFrequencia = 0;
    $oEscola->aEtapas          = array();
    
    $oFaixaDeIdade->aEscolas[$oEscola->iCodigoEscola] = $oEscola;
  }
  
  $oEscola = $oFaixaDeIdade->aEscolas[$oDadosMatricula->ed57_i_escola];
  if (!isset($oEscola->aEtapas[$oDadosMatricula->ed11_i_codigo])) {
  
    $oEtapa                   = new stdClass();
    $oEtapa->iCodigoEtapa     = $oDadosMatricula->ed11_i_codigo;
    $oEtapa->iOrdemEtapa      = $oDadosMatricula->ed11_i_sequencia;
    $oEtapa->sNomeEtapa       = $oDadosMatricula->ed11_c_descr;
    $oEtapa->iTotalAlunos     = 0;
    $oEtapa->nTotalFrequencia = 0;
    $oEtapa->aAlunos          = array();
    $oEscola->aEtapas[$oDadosMatricula->ed11_i_codigo] = $oEtapa;
  }
  
  $oEtapa               = $oFaixaDeIdade->aEscolas[$oEscola->iCodigoEscola]->aEtapas[$oDadosMatricula->ed11_i_codigo];
  $oAluno               =  new stdClass();
  $oAluno->sNome        = $oDadosMatricula->ed47_v_nome;
  $oAluno->sTurma       = $oDadosMatricula->ed57_c_descr;
  $oAluno->iNis         = $oDadosMatricula->ed47_c_nis;
  $oAluno->iIdade       = $oDadosMatricula->idade;
  $oAluno->sCodigoInep  = $oDadosMatricula->ed47_c_codigoinep;
  
  
  /**
   * Busca o percentual de frequência
   */
  $sCamposMat    = " ((coalesce(sum(regenciaperiodo.ed78_i_aulasdadas), 0)::float8 - ";
  $sCamposMat   .= " coalesce(sum(diarioavaliacao.ed72_i_numfaltas)::float,0)::float8 - coalesce(sum(abonofalta.ed80_i_numfaltas)::float8,0)::float8)::float8 ";
  $sCamposMat   .= " / coalesce(sum(regenciaperiodo.ed78_i_aulasdadas)::float,1)::float8)*100::float as percent";
  
  $sWhereMat     = " ed59_i_turma = {$oDadosMatricula->ed57_i_codigo} and ed59_i_serie = {$oDadosMatricula->ed11_i_codigo} ";
  $sWhereMat    .= " AND ed60_i_turma = $oDadosMatricula->ed57_i_codigo ";
  $sWhereMat    .= " AND ed72_c_amparo = 'N' AND ed59_c_freqglob != 'A' AND ed95_i_aluno = $oDadosMatricula->ed47_i_codigo ";
  $sWhereMat    .= " AND ed60_c_ativa = 'S' AND ed09_c_somach = 'S'" ;
  $sSqlMat       = $oDaoMatricula->sql_query_frequencia("", $sCamposMat, null,$sWhereMat);
  
  $rsMat         = $oDaoMatricula->sql_record($sSqlMat);
  $iNumLinhasMat = $oDaoMatricula->numrows;
  
  if ($iNumLinhasMat == 0) {
    $iPercFreq = 100;
  } else {
    $iPercFreq = db_utils::fieldsMemory($rsMat, 0)->percent == "0" ? 100 : db_utils::fieldsMemory($rsMat, 0)->percent;
  }
  $_SESSION["DB_coddepto"] = $oDadosMatricula->ed57_i_escola;
  $oAluno->nFrequencia = ArredondamentoFrequencia::arredondar($iPercFreq, $oGet->iAno);
  if (empty($oParametros->iEscola)) {
    ArredondamentoFrequencia::destroy();
  }
  
  
  if ($oParametros->lFrequencia) {

    if ($oAluno->iIdade >= $oFaixaDeIdade->iIdadeInicial &&  $oAluno->iIdade <= $oFaixaDeIdade->iIdadeFinal &&
         $oAluno->nFrequencia >= $oFaixaDeIdade->nPercentual) {
      continue;
    }
  }
  $oEtapa->iTotalAlunos++;
  $oEtapa->nTotalFrequencia += $oAluno->nFrequencia;
  
  $oEscola->iTotalAlunos++;
  $oEscola->nTotalFrequencia += $oAluno->nFrequencia;
  
  $oFaixaDeIdade->iTotalAlunos++;
  $oEtapa->aAlunos[] = $oAluno;
  $iTotalAlunos++;
}
$_SESSION["DB_coddepto"] = $iCodigoEscola;
if ($iTotalAlunos == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}

$oPdf = new PDF();
$oPdf->AliasNbPages();
$oPdf->Open();
$oPdf->SetAutoPageBreak(false, 20);
$head1 = "RELATÓRIO DE ALUNOS COM BOLSA FAMÍLIA";
$head2 = "Escola: {$sNomeEscola}";
$head3 = "Etapa: {$sNomeEtapa}";
$head4 = "Ano: {$oGet->iAno}";
$head5 = "Frequência: {$sLabelFrequencia}";
$oPdf->SetFillColor(240);
$iTotalGeral  = 0;

foreach ($aFaixaDeIdades as $oFaixaDeIdade) {
  
  if ($oFaixaDeIdade->iTotalAlunos == 0) {
    continue;
  }
  $oPdf->AddPage();
  $oPdf->SetFont($oParametros->sFonte, 'b', '8');
  $sTextoFrequencia  = "Alunos de {$oFaixaDeIdade->iIdadeInicial} a {$oFaixaDeIdade->iIdadeFinal} Anos - ";
  $sTextoFrequencia .= "Frequência Mínima: $oFaixaDeIdade->nPercentual%";
  $oPdf->Cell(192, $oParametros->iAlturaLinha,  $sTextoFrequencia, 0, 1, "L");
  $iTotalEscolasImpressas = 0;
  $iTotalDeEscolas        = count($oFaixaDeIdade->aEscolas);
  foreach ($oFaixaDeIdade->aEscolas as $oEscola) {
    
    $iTotalEscolasImpressas++;
    if ($oEscola->iTotalAlunos == 0) {
      continue;
    }
    
    $oPdf->SetFont($oParametros->sFonte, 'b', '7');
    $oPdf->Cell(192, $oParametros->iAlturaLinha, $oEscola->sNomeEscola, 1, 1, "L", 1);
    $iTotalEscola = 0;
    foreach ($oEscola->aEtapas as $oEtapa) {
      
      if ($oEtapa->iTotalAlunos == 0) {
        continue;
      }
      $oPdf->SetFont($oParametros->sFonte, 'b', '7');
  
      if ($oPdf->GetY() + $oParametros->iAlturaLinha * 3 > $oPdf->h - 15) {
        $oPdf->AddPage();
      }
      $oPdf->Cell(192, $oParametros->iAlturaLinha, $oEtapa->sNomeEtapa, 1, 1, 'L', 1);
      $oParametros->lEscreverCabecalho = true;
      montaCabecalho($oPdf, $oParametros);
      foreach ($oEtapa->aAlunos as $oAluno) {
  
        montaCabecalho($oPdf, $oParametros, 0);
        
        $oPdf->SetFont($oParametros->sFonte, '', '6');
        $oPdf->Cell(82, $oParametros->iAlturaLinha, $oAluno->sNome,           "TBR", 0);
        $oPdf->Cell(45, $oParametros->iAlturaLinha, $oAluno->sTurma,          "TBR", 0);
        $oPdf->cell(10, $oParametros->iAlturaLinha, $oAluno->iIdade,          "TBR", 0);
        $oPdf->Cell(25, $oParametros->iAlturaLinha, $oAluno->iNis,            "TBR", 0);
        $oPdf->Cell(20, $oParametros->iAlturaLinha, $oAluno->sCodigoInep,     "TBR", 0);
        $oPdf->Cell(10, $oParametros->iAlturaLinha, $oAluno->nFrequencia,     "TBL", 1, 'R');
      }
  
      $nMediaFrequencia = number_format(round($oEtapa->nTotalFrequencia / $oEtapa->iTotalAlunos, 2), 2, '.', '');
      $oPdf->SetFont($oParametros->sFonte, 'b', '7');
      $iTotalEscola += count($oEtapa->aAlunos);
      $oPdf->cell(162, $oParametros->iAlturaLinha, "Total da Etapa: {$oEtapa->iTotalAlunos}", 1, 0, "L", 1);
      $oPdf->cell(20, $oParametros->iAlturaLinha, "Média Freq:", 1, 0, "L", 1);
      $oPdf->cell(10, $oParametros->iAlturaLinha, $nMediaFrequencia, 1, 1, "R", 1);
      $oPdf->ln();
    }
    
    $oPdf->SetY($oPdf->getY() - $oParametros->iAlturaLinha);
    $iTotalGeral += $iTotalEscola;
    $nMediaFrequencia = number_format(round($oEscola->nTotalFrequencia / $oEscola->iTotalAlunos, 2), 2, '.',',');
    $oPdf->SetFont($oParametros->sFonte, 'b', '7');
    $oPdf->cell(162, $oParametros->iAlturaLinha, "Total da Escola: {$oEscola->iTotalAlunos}", 1, 0, "L", 1);
    $oPdf->cell(20, $oParametros->iAlturaLinha, "Média Freq:", 1, 0, "L", 1);
    $oPdf->cell(10, $oParametros->iAlturaLinha, $nMediaFrequencia, 1, 1, "R", 1);
    $oPdf->ln();
  }
  
  //$oPdf->SetY($oPdf->getY() - $oParametros->iAlturaLinha);
  $oPdf->SetFont($oParametros->sFonte, 'b', '7');
  $oPdf->cell(192, $oParametros->iAlturaLinha, "Total da Faixa Etária: {$oFaixaDeIdade->iTotalAlunos}", 1, 1, "L", 1);
}
$oPdf->SetFont($oParametros->sFonte, 'b', '7');
$oPdf->cell(192, $oParametros->iAlturaLinha, "Total Geral: {$iTotalGeral}", 1, 1, "L", 1);
$oPdf->Output();

/**
 * monta o cabecalho do relatorio
 * @param PDF $oPdf instancia da fpdf
 * @param stdclass $oParametros parametros de configuracao
 * @param integer $iAlturaExtra altura extra a ser considerada na quebra de página
 */
function montaCabecalho(PDF $oPdf, $oParametros, $iAlturaExtra = 0) {

  $lQuebrarPagina = $oPdf->GetY() + $iAlturaExtra > $oPdf->h - 15;
  if ($lQuebrarPagina || $oParametros->lEscreverCabecalho == true) {

    if ($lQuebrarPagina) {
      $oPdf->AddPage();
    }
    $oPdf->SetFillColor(240);
    $oPdf->SetFont($oParametros->sFonte, 'b', '7');
    $oPdf->cell(82,  $oParametros->iAlturaLinha, "Aluno",       1, 0, "L", 1);
    $oPdf->cell(45,  $oParametros->iAlturaLinha, "Turma",       1, 0, "C", 1);
    $oPdf->cell(10,  $oParametros->iAlturaLinha, "Idade", 1, 0, "C", 1);
    $oPdf->cell(25,  $oParametros->iAlturaLinha, "N° NIS",      1, 0, "C", 1);
    $oPdf->cell(20,  $oParametros->iAlturaLinha, "Código INEP", 1, 0, "C", 1);
    $oPdf->cell(10,  $oParametros->iAlturaLinha, "% Freq.",  1, 1, "C", 1);
    
    $oParametros->lEscreverCabecalho = false;
  }
}

/**
 * Retorna qual a faixa de idade do Aluno
 * @param stdClass $oAluno Dados do aluno
 * @param array    $aFaixasDeIdade
 * @return stdClass
 */
function getFaixaDeIdade($oAluno, $aFaixasDeIdade) {
  
  foreach ($aFaixasDeIdade as $oFaixaIdade) {
    
    if ($oAluno->idade >= $oFaixaIdade->iIdadeInicial && $oAluno->idade <= $oFaixaIdade->iIdadeFinal) {
      return $oFaixaIdade;
    }
  }
}
?>
