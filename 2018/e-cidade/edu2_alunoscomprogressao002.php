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

require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/DBDate.php");
require_once ("fpdf151/pdf.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

$oGet = db_utils::postMemory($_GET);

$lModuloEscola = db_getsession("DB_modulo") == 1100747 ? true : false;
$aEscolas      = array();

/**
 * Verificamos as escolas que iremos imprimir
 */
if ($oGet->iEscola != 0) {
  $aEscolas[] = new Escola($oGet->iEscola);
} else if (!$lModuloEscola && $oGet->iEscola == 0) {

  $oDaoEscola = new cl_progressaoparcialaluno();
  $sSqlEscola = $oDaoEscola->sql_query( null, "distinct ed114_escola" );
  $rsEscola   = $oDaoEscola->sql_record( $sSqlEscola );
  $iRegistros = $oDaoEscola->numrows;

  if ($iRegistros > 0) {

    for ($i = 0; $i < $iRegistros; $i++) {
      $aEscolas[] = new Escola( db_utils::fieldsMemory($rsEscola, $i)->ed114_escola );
    }
  }
}

if (count($aEscolas) == 0) {

  $sMsgErro  = "ERRO[1]: Nenhuma escola configurada.<br>";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
}

/**
 * Verificamos para que modulo esta configurado o parâmetro
 * Secretaria da Educacao ou Escola
 */
$oDaoSecParam = new cl_sec_parametros();
$sSqlSecParam = $oDaoSecParam->sql_query_file(null, "ed290_controleprogressaoparcial");
$rsSecParam   = $oDaoSecParam->sql_record($sSqlSecParam);

$lProgressaoHabilitadaEscola = db_utils::fieldsMemory($rsSecParam, 0)->ed290_controleprogressaoparcial == 1 ? false : true;

/**
 * Buscamos as configuracoes dos parametros das escolas
 */
$aParametrosConfiguracao = array();

foreach ($aEscolas as $oEscola) {
  $aParametrosConfiguracao[$oEscola->getCodigo()] = ProgressaoParcialParametroRepository::getProgressaoParcialParametroByCodigo($oEscola->getCodigo());
}

/**
 * Buscamos os Alunos das escolas configuradas para impressao
 */
$aAlunosProgressao  = array();

foreach ($aEscolas as $oEscola) {

  $oDaoProgressao = new cl_progressaoparcialaluno();
  $sWhere         = "     ed114_escola = {$oEscola->getCodigo()}";
  $sWhere        .= " and ed114_ano    = {$oGet->iAno}";
  $sCampos        = " ed114_sequencial, ed114_serie, trim(ed57_c_descr) as turma";
  $sOrder         = " ed114_ano, ed57_c_descr, ed114_serie, ed47_v_nome ";
  $sSqlProgressao = $oDaoProgressao->sql_query_aluno_escola( null, $sCampos, $sOrder, $sWhere );
  $rsProgressao   = $oDaoProgressao->sql_record( $sSqlProgressao );
  $iRegistrosProg = $oDaoProgressao->numrows;

  if ($iRegistrosProg > 0) {

    for ($i = 0; $i < $iRegistrosProg; $i++) {

      $oDadosProgressao = db_utils::fieldsMemory($rsProgressao, $i);
      $oEnsino          = new Etapa($oDadosProgressao->ed114_serie);

      $oProgressaoAluno = ProgressaoParcialAlunoRepository::getProgressaoParcialAlunoByCodigo($oDadosProgressao->ed114_sequencial);

      $oAluno                = new stdClass();
      $oAluno->iCodigo       = $oProgressaoAluno->getAluno()->getCodigoAluno();
      $oAluno->sNome         = $oProgressaoAluno->getAluno()->getNome();
      $oAluno->sTurma        = $oDadosProgressao->turma;
      $oAluno->aDisciplina   = array();
      $oAluno->aDisciplina[] = $oProgressaoAluno->getDisciplina()->getAbreviatura();

      if (!isset($aAlunosProgressao[$oEscola->getCodigo()])) {
        $aAlunosProgressao[$oEscola->getCodigo()] = array();
      }
      if (!isset($aAlunosProgressao[$oEscola->getCodigo()][$oEnsino->getEnsino()->getCodigo()])) {
        $aAlunosProgressao[$oEscola->getCodigo()][$oEnsino->getEnsino()->getCodigo()] = array();
      }
      if (!isset($aAlunosProgressao[$oEscola->getCodigo()]
                                   [$oEnsino->getEnsino()->getCodigo()]
                                   [$oDadosProgressao->ed114_serie])) {
        $aAlunosProgressao[$oEscola->getCodigo()][$oEnsino->getEnsino()->getCodigo()]
                          [$oDadosProgressao->ed114_serie] = array();

      }

      if (array_key_exists($oProgressaoAluno->getAluno()->getCodigoAluno(),
                           $aAlunosProgressao[$oEscola->getCodigo()]
                                             [$oEnsino->getEnsino()->getCodigo()]
                                             [$oDadosProgressao->ed114_serie])
         ) {

        $aAlunosProgressao[$oEscola->getCodigo()]
                          [$oEnsino->getEnsino()->getCodigo()]
                          [$oDadosProgressao->ed114_serie]
                          [$oProgressaoAluno->getAluno()->getCodigoAluno()]->aDisciplina[] = $oProgressaoAluno->
                                                                                                   getDisciplina()->
                                                                                                      getAbreviatura();
      } else {

        $aAlunosProgressao[$oEscola->getCodigo()]
                          [$oEnsino->getEnsino()->getCodigo()]
                          [$oDadosProgressao->ed114_serie]
                          [$oProgressaoAluno->getAluno()->getCodigoAluno()] = $oAluno;
      }
    }
  }
}

/**
 * Setamos as variaveis do cabecalho padrao
 */
$sNomeEscola = " 0 - Todas";

if (count($aEscolas) == 1) {

  $sNomeEscola       = $aEscolas[0]->getNome();
  $iCodigoReferencia = $aEscolas[0]->getCodigoReferencia();

  if ( $iCodigoReferencia != null ) {
    $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
  }
}

$head1 = "Alunos com Progressão Parcial";
$head2 = "Escola: {$sNomeEscola}";

if (($lModuloEscola && $aParametrosConfiguracao[$aEscolas[0]->getCodigo()]->isHabilitada()) ||
     !$lProgressaoHabilitadaEscola) {

  $iEscola = $aEscolas[0]->getCodigo();
  $sFormaControle = $aParametrosConfiguracao[$iEscola]->getFormaControle() == ProgressaoParcialParametro::CONTROLE_ETAPA
                                                                                 ? "Por Etapa" : " Por Base Curricular";
  $sDiscAprovEliminaDependencia = $aParametrosConfiguracao[$iEscola]->disciplinaAprovadaEliminaProgressao() ? "Sim"
                                                                                                            : "Não";
  $head3 = "Quantidade de Disciplinas Dependentes: ". $aParametrosConfiguracao[$iEscola]->getQuantidadeDisciplina();
  $head4 = "Forma de Controle: {$sFormaControle}";
  $head5 = "Disciplina Aprovada Elimina Dependência: {$sDiscAprovEliminaDependencia}";
}


$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);
$oPdf->SetAutoPageBreak(false);
$oPdf->AddPage();

$lPrimeiraVolta = true;
$iHeigth        = 4;

/**
 * Percorremos as escolas
 */
foreach ($aAlunosProgressao as $iEscola => $aEnsino) {

  $oEscola = new Escola($iEscola);
  $oPdf->SetFont("arial", "b", 8);
  $oPdf->Cell(30, 4, "Escola : ",         0, 0);
  $oPdf->SetFont("arial", "", 8);
  $oPdf->Cell(130, 4, $oEscola->getNome(), 0, 1);
  if ($lProgressaoHabilitadaEscola) {
    imprimirConfiguracaoProgressao($oPdf, $aParametrosConfiguracao, $oEscola, $lProgressaoHabilitadaEscola);
  }

  /**
   * Percorremos os Ensinos
   */
  foreach ($aEnsino as $iEnsino => $aEtapa) {

    $oEnsino = new Ensino($iEnsino);
    imprimeDadosCurso($oPdf, $oEscola, $oEnsino, $lModuloEscola);

    /**
     * Percorremos as Etapas
     */
    foreach ($aEtapa as $iEtapa => $aProgressaoAluno) {

      $iTotalAlunosEtapa = 0;
      $oEtapa            = EtapaRepository::getEtapaByCodigo($iEtapa);
      $oPdf->SetFont("arial", "b", 8);
      $oPdf->Cell(30,  $iHeigth, "Etapa : ",         0, 0);
      $oPdf->SetFont("arial", "", 8);
      $oPdf->Cell(130, $iHeigth, $oEtapa->getNome(), 0, 1);
      imprimeCabecalhoAlunos($oPdf, true);

      /**
       * Percorremos os alunos de Progressao
       */
      foreach ($aProgressaoAluno as $oAluno) {

        imprimeCabecalhoAlunos($oPdf);
        $oPdf->SetFont("arial", "", 7);
        $oPdf->Cell(12,  $iHeigth, $oAluno->iCodigo,                    "TBR", 0, "R");
        $oPdf->Cell(100, $iHeigth, $oAluno->sNome,                          1, 0, "L");
        $oPdf->Cell(35,  $iHeigth, $oAluno->sTurma,                         1, 0, "L");
        $oPdf->Cell(45,  $iHeigth, implode(", ", $oAluno->aDisciplina), "LTB", 1, "L");
        $iTotalAlunosEtapa++;
      }
      $oPdf->SetFont("arial", "b", 7);
      $oPdf->Cell(147, $iHeigth, "Total de Alunos na Etapa:", "TBR", 0, "R");
      $oPdf->Cell(45,  $iHeigth, $iTotalAlunosEtapa,       "LBT", 1, "L");
      $oPdf->ln();

      unset($oEtapa);
    }
    unset($oEnsino);
  }
  unset($oEscola);
}


$oPdf->Output();

/**
 * Imprime os dados do Curso
 * @param FPDF    $oPdf
 * @param Escola  $oEscola
 * @param Ensino   $oEnsino
 * @param boolean $lModuloEscola
 */
function imprimeDadosCurso($oPdf, $oEscola, $oEnsino, $lModuloEscola) {

  $oPdf->SetFont("arial", "b", 8);
  $oPdf->Cell(30,  4, "Curso : ", 0, 0);
  $oPdf->SetFont("arial", "", 8);
  $oPdf->Cell(130, 4, $oEnsino->getNome(), 0, 1);
}

/**
 * Imprime as configuracoes da configuracao da progressao parcial
 * @param FPDF    $oPdf
 * @param array   $aParametrosConfiguracao
 * @param Escola  $oEscola
 */
function imprimirConfiguracaoProgressao($oPdf, $aParametrosConfiguracao, $oEscola, $lProgressaoHabilitadaEscola) {

  $iEscola                 = $oEscola->getCodigo();
  $iQtdDisciplinaPendentes = $aParametrosConfiguracao[$iEscola]->getQuantidadeDisciplina();
  $sFormaControle          = $aParametrosConfiguracao[$iEscola]->getFormaControle();
  $sFormaControle          = $sFormaControle == ProgressaoParcialParametro::CONTROLE_ETAPA
                                 ? "Por Etapa" : "Por Base Curricular";
  $sDiscAprovEliminaDependencia = $aParametrosConfiguracao[$iEscola]->disciplinaAprovadaEliminaProgressao() ? "Sim"
                                                                                                            : "Não";

  $sHabilitadoPara = $lProgressaoHabilitadaEscola ? "Escola" : "Secretaria da Educação";
  $oPdf->SetFont("arial", "b", 8);
  $oPdf->Cell(30, 4, "Habilitado para: ", 0, 0);
  $oPdf->SetFont("arial", "", 8);
  $oPdf->Cell(80, 4, "{$sHabilitadoPara}", 0, 0);
  $oPdf->SetFont("arial", "b", 8);
  $oPdf->Cell(60, 4, "Quantidade de Disciplinas Dependentes: ", 0, 0);
  $oPdf->SetFont("arial", "", 8);
  $oPdf->Cell(20, 4, "{$iQtdDisciplinaPendentes}", 0, 1);
  $oPdf->SetFont("arial", "b", 8);
  $oPdf->Cell(30, 4, "Forma de Controle: ",  0, 0);
  $oPdf->SetFont("arial", "", 8);
  $oPdf->Cell(80, 4, "{$sFormaControle}", 0, 0);
  $oPdf->SetFont("arial", "b", 8);
  $oPdf->Cell(60, 4, "Disciplina Aprovada Elimina Dependência: ", 0, 0);
  $oPdf->SetFont("arial", "", 8);
  $oPdf->Cell(20, 4, "{$sDiscAprovEliminaDependencia}", 0, 1);

}

/**
 * Imprimimos o cabeçalho dos alunos
 * @param FPDF    $oPdf
 */
function imprimeCabecalhoAlunos($oPdf, $lForcaImpressaoCabecalho = false) {

  if ($oPdf->GetY() > $oPdf->h - 15) {

    $oPdf->AddPage();
    $lForcaImpressaoCabecalho = true;
  }
  if ($lForcaImpressaoCabecalho) {

    $oPdf->SetFillColor(240);
    $oPdf->SetFont("arial", "b", 7);
    $oPdf->Cell(12,  4, "Código",        "TBR", 0, "C", 1);
    $oPdf->Cell(100, 4, "Nome do Aluno",     1, 0, "C", 1);
    $oPdf->Cell(35,  4, "Turma",             1, 0, "C", 1);
    $oPdf->Cell(45,  4, "Disciplina",    "TBL", 1, "C", 1);
  }

}