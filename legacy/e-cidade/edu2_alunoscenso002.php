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

include(modification("fpdf151/pdfwebseller.php"));
include(modification("classes/db_matricula_classe.php"));

/**
 * Caso exista os parametros, decodifica o base64 por causa do SQL's passados
 * pelo edu2_alunoscenso001.php
 */
db_postmemory($_GET);

$oDadosRelatorio             = new stdClass();
$oDadosRelatorio->sEnsino    = "TODOS";
$oDadosRelatorio->sEtapa     = "TODOS";
$oDadosRelatorio->dtCenso    = null;
$oDadosRelatorio->aAlunos    = array();
$oDadosRelatorio->aCabecalho = array();
$oDadosRelatorio->aColunas   = array();

$oDadosRelatorio->iLarguraLinha = 6;

try {

  if (isset($campos)) {
    $campos = base64_decode($campos);
  }

  if (isset($cabecalho)) {
    $cabecalho = base64_decode($cabecalho);
  }

  if (isset($colunas)) {
    $colunas = base64_decode($colunas);
  }

  if (isset($alinhamento)) {
    $alinhamento = base64_decode($alinhamento);
  }

  if ( empty($cabecalho) ) {
    throw new ParameterException("Não foi informado o cabeçalho.");
  }

  if ( empty($colunas) ) {
    throw new ParameterException("Não foi informado as colunas.");
  }
  if ( empty($alinhamento) ) {
    throw new ParameterException("Não foi informado o alinhamento das colunas.");
  }

  $oDadosRelatorio->aCabecalho   = explode('|', $cabecalho);
  $oDadosRelatorio->aColunas     = explode('|', $colunas);
  $oDadosRelatorio->aAlinhamento = explode('|', $alinhamento);

  $oDadosRelatorio->iLarguraLinha += array_sum($oDadosRelatorio->aColunas);

  $oDaoMatricula = new cl_matricula;
  $iEscola       = db_getsession("DB_coddepto");

  $aWhere   = array("calendario.ed52_i_ano = $ano_censo");
  $aWhere[] = " turma.ed57_i_escola = {$iEscola} ";

  if ( !empty($ensino) ) {
   $aWhere[] = " ensino.ed10_i_codigo = {$ensino}";
  }

  if ( !empty($serie) ) {
    $aWhere[] = " serie.ed11_i_codigo = {$serie}";
  }

  $oDataCenso               = new DBDate($data_censo);
  $oDadosRelatorio->dtCenso = $oDataCenso;

  $campos   = str_replace(chr(92), "", $campos);
  $aWhere[] = " ed60_d_datamatricula <= '{$oDataCenso->getDate()}' ";
  $aWhere[] = " ((ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null) OR
                (ed60_c_situacao != 'MATRICULADO' and ed60_d_datasaida > '{$oDataCenso->getDate()}')) ";

  $campos  .= ",turma.ed57_i_codigo, ensino.ed10_i_codigo, ensino.ed10_c_descr, serie.ed11_i_codigo, serie.ed11_c_descr,";
  $campos  .= " ed60_c_situacao, ed60_matricula, ";
  $campos  .= " ed60_d_datamatricula as dt_matricula, "; // precisamos da data para tratar os alunos
  $campos  .= " ed60_d_datasaida as dt_saida "; // precisamos da data para tratar os alunos
  $sOrdem   = " ensino.ed10_ordem, serie.ed11_i_sequencia, ensino.ed10_i_tipoensino, ensino.ed10_c_descr,  ";
  $sOrdem  .= " turma.ed57_c_descr, ed60_i_numaluno, to_ascii(ed47_v_nome), ed60_c_ativa ";

  $sSqlAlunos = $oDaoMatricula->sql_query("", $campos, $sOrdem,  implode(" and ", $aWhere));
  $rsAlunos   = db_query($sSqlAlunos);

  if (!$rsAlunos) {
    throw new DBException("Erro ao buscar alunos.");
  }

  if ( pg_num_rows($rsAlunos) == 0 ) {
    throw new DBException("Nenhum aluno encontrado.");
  }

  $oDados  = db_utils::fieldsMemory($rsAlunos, 0);
  if ( !empty($ensino) ) {
    $oDadosRelatorio->sEnsino = $oDados->ed10_c_descr;
  }
  if ( !empty($serie) ) {
    $oDadosRelatorio->sEtapa  = $oDados->ed11_c_descr;
  }
  $iLinhas = pg_num_rows($rsAlunos);

  /**
   * Filtra os alunos que possam estar "duplicados" (ter mais de uma matrícula).
   * Como exemplo, podemos ressaltar alunos com troca de turma com movimentação. Dentre estes, devemos localizar e
   * apresentar a matricula que realmente foi no arquivo do censo.
   */
  $aAlunosFiltrados = array();
  for ($i = 0; $i < $iLinhas; $i++) {

    $oDados = db_utils::fieldsMemory($rsAlunos, $i);
    if ( !array_key_exists($oDados->ed60_matricula, $aAlunosFiltrados) ) {

      $aAlunosFiltrados[$oDados->ed60_matricula] = $oDados;
      continue;
    }

    /**
     * Dados do aluno ja adicionado no array
     */
    $oDadosAlunoAdicionado  = $aAlunosFiltrados[$oDados->ed60_matricula];
    $oDtSaidaAdicionado     = null;
    if ( !empty($oDadosAlunoAdicionado->dt_saida) )  {
      $oDtSaidaAdicionado = new DBDate ($oDadosAlunoAdicionado->dt_saida);
    }

    $oDtMatricula = new DBDate ($oDados->dt_matricula);
    $oDtSaida     = null;
    if ( !empty($oDados->dt_saida) ) {
      $oDtSaida = new DBDate ($oDados->dt_saida);
    }

    // se a matrícula adicionada possui data de saída maior que a data do censo, esta deve estar no relatório
    if ( !is_null($oDtSaidaAdicionado) && $oDtSaidaAdicionado->getTimeStamp() >= $oDataCenso->getTimeStamp() ) {
      continue;
    }

    /**
     *  Se a outra matricula não tem data de saida e a data de saída da matricula adicionada no array possui uma
     *  saída inferior a data do censo, devemos verificar se a nova matrícula possui a data inferior a data de fechamento
     *  do censo. Se sim, esta substitui a outra.
     */
    if (    is_null( $oDtSaida )
         && (!is_null($oDtSaidaAdicionado) && $oDtSaidaAdicionado->getTimeStamp() < $oDataCenso->getTimeStamp())
         && $oDtMatricula->getTimeStamp() <= $oDataCenso->getTimeStamp()) {
      $aAlunosFiltrados[$oDados->ed60_matricula] = $oDados;
    }

    /**
     * Se a matrícula que estamos percorrendo possui uma data de saída, e esta data é maior que a data do censo e a
     * data de matrícula inferior a data do censo. Esta é que deve ser apresentada
     */
    if (    !is_null( $oDtSaida )
         && $oDtSaida->getTimeStamp() >= $oDataCenso->getTimeStamp()
         && $oDtMatricula->getTimeStamp() <= $oDataCenso->getTimeStamp()) {
      $aAlunosFiltrados[$oDados->ed60_matricula] = $oDados;
    }
  }

  /**
   * Filtra e totaliza os alunos por ensino e etapa
   */
  foreach ($aAlunosFiltrados as $oDadosAlunos) {

    if ( !array_key_exists($oDadosAlunos->ed10_i_codigo, $oDadosRelatorio->aAlunos) ) {

      $oEnsino             = new stdClass();
      $oEnsino->lTotaliza  = $tt_ensino     == 'yes';
      $oEnsino->lApresenta = $titulo_ensino == 'yes';
      $oEnsino->sDescricao = $oDadosAlunos->ed10_c_descr;
      $oEnsino->iTotal     = 0;
      $oEnsino->aEtapas    = array();

      $oDadosRelatorio->aAlunos[$oDadosAlunos->ed10_i_codigo] = $oEnsino;
    }

    $oEnsino = $oDadosRelatorio->aAlunos[$oDadosAlunos->ed10_i_codigo];
    if ( !array_key_exists($oDadosAlunos->ed11_i_codigo, $oEnsino->aEtapas) ) {

      $oEtapa             = new stdClass();
      $oEtapa->lTotaliza  = $tt_serie     == 'yes';
      $oEtapa->lApresenta = $titulo_serie == 'yes';
      $oEtapa->sDescricao = $oDadosAlunos->ed11_c_descr;
      $oEtapa->iTotal     = 0;
      $oEtapa->aTurmas    = array();

      $oEnsino->aEtapas[$oDadosAlunos->ed11_i_codigo] = $oEtapa;
    }

    $oEtapa = $oEnsino->aEtapas[$oDadosAlunos->ed11_i_codigo];
    if ( !array_key_exists($oDadosAlunos->ed57_i_codigo, $oEtapa->aTurmas ) ) {

      $oTuma          = new stdClass();
      $oTuma->aAlunos = array();

      $oEtapa->aTurmas[$oDadosAlunos->ed57_i_codigo] = $oTuma;
    }

    $oEnsino->iTotal ++;
    $oEnsino->aEtapas[$oDadosAlunos->ed11_i_codigo]->iTotal ++;
    $oEnsino->aEtapas[$oDadosAlunos->ed11_i_codigo]->aTurmas[$oDadosAlunos->ed57_i_codigo]->aAlunos[] = $oDadosAlunos;
  }
} catch (Exception $e) {

  $sMsg = urlencode($e->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}

$oPdf = new PDF($orientacao);
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true, 15);

$head1 = "CENSO {$oDadosRelatorio->dtCenso->getAno()}";
$head2 = "Alunos ativos em: {$oDadosRelatorio->dtCenso->getDate(DBDate::DATA_PTBR)}";
$head3 = "Filtro por Ensino: {$oDadosRelatorio->sEnsino}";
$head4 = "Filtro por Etapa:  {$oDadosRelatorio->sEtapa}";
$head5 = "Data: ".date("d/m/Y");

adicionarHeader($oPdf, $oDadosRelatorio);

foreach ($oDadosRelatorio->aAlunos as $oDadosEnsino) {

  separadorPorEnsino($oPdf, $oDadosEnsino, $oDadosRelatorio->iLarguraLinha);
  foreach ($oDadosEnsino->aEtapas as $oDadosEtapa) {

    separadorPorEtapa($oPdf, $oDadosEtapa, $oDadosRelatorio->iLarguraLinha);

    $lPinta = true;
    $oPdf->SetFont('arial','', 7);

    foreach ($oDadosEtapa->aTurmas as $iTurma => $oAlunos) {

      $lAdicionaCabecalho = true;
      foreach ($oAlunos->aAlunos as $iSeq => $oDadosAluno) {

        if ($oPdf->GetY() > ($oPdf->h - 20) )  {

          adicionarHeader($oPdf, $oDadosRelatorio);

          $lAdicionaCabecalho = true;
          separadorPorEnsino($oPdf, $oDadosEnsino, $oDadosRelatorio->iLarguraLinha);
          separadorPorEtapa($oPdf, $oDadosEtapa, $oDadosRelatorio->iLarguraLinha);
        }

        $lPinta = !$lPinta;
        if ($lAdicionaCabecalho) {

          adicionaCabecalhoAlunos($oPdf, $oDadosRelatorio);
          $lAdicionaCabecalho = false;
        }

        $oPdf->SetFillColor(240);
        $oPdf->Cell(6, 4, ($iSeq + 1), "LR", 0, "C", $lPinta);
        /**
         * Transforma o objeto com os dados do aluno em um array indexado numericamente.
         */
        $aAluno = array_values((array) $oDadosAluno);
        foreach ($oDadosRelatorio->aColunas as $i => $iTamanho) {

          $sDado = $aAluno[$i];

          if ( !!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $sDado, $matches) ) {
            $sDado = db_formatar($sDado, 'd');
          }

          $oPdf->Cell($iTamanho, 4, $sDado, "LR", 0, $oDadosRelatorio->aAlinhamento[$i], $lPinta);
        }

        $oPdf->ln();
      }
    }
  }
}


$iTotalGeral = 0;
$oPdf->SetFillColor(210);
$oPdf->SetFont('arial', 'B', 7);
$oPdf->Cell($oDadosRelatorio->iLarguraLinha, 4, "TOTALIZADORES", 1, 1, "L", 1);

$iColunaTotal     = $oDadosRelatorio->iLarguraLinha / 3;
$iColunaDescricao = $oDadosRelatorio->iLarguraLinha - $iColunaTotal;

foreach ($oDadosRelatorio->aAlunos as $oDadosEnsino) {

  $iTotalGeral += $oDadosEnsino->iTotal;
  $oPdf->SetFillColor(225);
  if ( $oDadosEnsino->lTotaliza ) {

    $oPdf->Cell($iColunaDescricao, 4, $oDadosEnsino->sDescricao, 1, 0, "L", 1);
    $oPdf->Cell($iColunaTotal,     4, $oDadosEnsino->iTotal, 1, 1, "R", 1);
  }
  foreach ($oDadosEnsino->aEtapas as $oDadosEtapa) {
    if ( $oDadosEtapa->lTotaliza ) {

      $oPdf->Cell(5, 4, "", "LBT", 0);
      $oPdf->Cell($iColunaDescricao - 5,  4, $oDadosEtapa->sDescricao, "RTB", 0, "L");
      $oPdf->Cell($iColunaTotal, 4, $oDadosEtapa->iTotal, 1, 1, "R");
    }
  }
}
$oPdf->Cell($iColunaDescricao, 4, "TOTAL GERAL", 1, 0, "L", 1);
$oPdf->Cell($iColunaTotal,      4, $iTotalGeral, 1, 1, "L", 1);

$oPdf->Output();

function adicionarHeader(FPDF $oPdf, $oDadosRelatorio) {

  $sLegenda  = "T=Transporte Escolar Z=Zona(R=RURAL U=URBANA)  NE=Necessidades Especiais  ";
  $sLegenda .= "R=Rendimento Anterior(A=APROV R=REPROV S=SEM INFORMAÇÕES)  Sx=Sexo  St= Situação";
  $oPdf->AddPage();
  $oPdf->SetFont('arial','b',6);
  $oPdf->Cell(193, 4, $sLegenda, 0, 1, "L", 0);
  $oPdf->SetFont('arial', '', 7);
}

function adicionaCabecalhoAlunos(FPDF $oPdf, $oDadosRelatorio) {

  $oPdf->SetFont('arial', 'B', 6);
  $oPdf->Cell(6, 4, "Seq", 1, 0, "L");

  foreach ($oDadosRelatorio->aCabecalho as $i => $sCabecalho) {
    $oPdf->Cell($oDadosRelatorio->aColunas[$i], 4, $sCabecalho, 1, 0, $oDadosRelatorio->aAlinhamento[$i]);
  }
  $oPdf->ln();
  $oPdf->SetFont('arial', '', 6);
}

function separadorPorEnsino( $oPdf, $oDadosEnsino, $iLinha) {

  if ( $oDadosEnsino->lApresenta ) {

    $oPdf->SetFont('arial', 'B', 7);
    $oPdf->SetFillColor(210);
    $oPdf->Cell($iLinha, 4, $oDadosEnsino->sDescricao, 1, 1, "L", 1);
    $oPdf->SetFont('arial', '', 7);
  }
}
function separadorPorEtapa( $oPdf, $oDadosEtapa, $iLinha) {

  if ( $oDadosEtapa->lApresenta ) {

    $oPdf->SetFont('arial', 'B', 7);
    $oPdf->SetFillColor(225);
    $oPdf->Cell($iLinha, 4, $oDadosEtapa->sDescricao, 1, 1, "L", 1);
    $oPdf->SetFont('arial', '', 7);
  }
}
