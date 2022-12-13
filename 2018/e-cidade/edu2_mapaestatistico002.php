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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("libs/db_libparagrafo.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification('fpdf151/FpdfMultiCellBorder.php'));

$oGet                   = db_utils::postMemory($_GET);

$aEtapasErEp            = array();
$aEtapasSegundoQuadro   = array();
$aEscolasPrimeiroQuadro = array(); // Qtd de alunos matrículados em ensinos regular
$aEscolasSegundoQuadro  = array(); // Qtd de alunos matrículados em turmas de correção de fluxo e especial
$aEscolasTerceiroQuadro = array(); // Qtd de alunos ensinos regular matrículados separado por turno e sexo e qtd por situação
$aEscolasQuartoQuadro   = array(); // Qtd de alunos ensinos AEE/AtivComplementar matrículados separado por turno e sexo

$iNumeroEtapasPrimeiroQuadro = 0;
$iNumeroEtapasSegundoQuadro  = 0;

$lRelatorioSemDadosParaPeriodoInformado = true;

try {

  if ( empty($oGet->dtInicio) ) {
    throw new Exception("É obrigatória a informação da data de início.");
  }
  if ( empty($oGet->dtFim) ) {
    throw new Exception("É obrigatória a informação da data de fim.");
  }

  $oDtInicio  = new DBDate($oGet->dtInicio);
  $oDtFim     = new DBDate($oGet->dtFim);
  $sFiltraAno = " ed52_i_ano = " . $oDtInicio->getAno();

  $aEscolasPrimeiroQuadro = buscaEscolas();
  $aEscolasSegundoQuadro  = unserialize(serialize($aEscolasPrimeiroQuadro));
  $aEscolasTerceiroQuadro = unserialize(serialize($aEscolasPrimeiroQuadro));
  $aEscolasQuartoQuadro   = unserialize(serialize($aEscolasPrimeiroQuadro));


  /** ***********************************************************************************************************
   ******************************* inicio da busca dos dados do PRIMEIRO QUADRO *********************************
   *********************************************************************************************************** **/
  /**
   * Busca os ensinos do tipo ENSINO REGULAR e EDUCAÇÃO DE JOVENS E ADULTOS
   */
  $aWhere   = array();
  $aWhere[] = $sFiltraAno;
  $aWhere[] = " ed57_i_tipoturma not in (6,7) ";

  $aEnsinosNoAno  = buscaCursosMinistradoAno( $aWhere );
  $sEnsinosFiltro = implode(", ", $aEnsinosNoAno);

  if (count($aEnsinosNoAno) > 0 ) {

    $aWherePrimeiroQuadro   = array();
    $aWherePrimeiroQuadro[] = " ed10_i_codigo in ({$sEnsinosFiltro}) ";
    $aWherePrimeiroQuadro[] = " ed10_i_tipoensino in (1,3) ";

    $rsPrimeiroQuadro = buscaDadosEnsinosMinistrados($aWherePrimeiroQuadro);
    $iLinhas          = pg_num_rows($rsPrimeiroQuadro);
    if ($iLinhas == 0) {
      throw new Exception("Não há turmas cadastradas para o ano: " . $oDtInicio->getAno() );
    }

    $iNumeroEtapasPrimeiroQuadro = $iLinhas;
    for ($i = 0; $i < $iLinhas; $i++) {

      $oDados  = db_utils::fieldsMemory($rsPrimeiroQuadro, $i);
      $iEnsino = $oDados->cod_ensino;
      if ( !array_key_exists($iEnsino, $aEtapasErEp) ) {

        $oEnsino = new stdClass();
        $oEnsino->iEnsino      = $oDados->cod_ensino;
        $oEnsino->sEnsino      = $oDados->ensino;
        $oEnsino->sEnsinoAbrev = $oDados->ensino_abrev;
        $oEnsino->iTotalEnsino = 0;
        $oEnsino->aEtapas      = array();

        $aEtapasErEp[$iEnsino] = $oEnsino;
      }

      $oEtapa = new stdClass();
      $oEtapa->iEtapa      = $oDados->cod_etapa;
      $oEtapa->sEtapa      = $oDados->etapa;
      $oEtapa->sEtapaAbrev = $oDados->etapa_abrev;
      $oEtapa->iFill       = 0;
      $oEtapa->iTotalEtapa = 0;

      $aEtapasErEp[$iEnsino]->aEtapas[] = $oEtapa;
    }
    // Adiciona a coluna de totalizador para cada ensino
    foreach ($aEtapasErEp as $oEnsino) {

      $iNumeroEtapasPrimeiroQuadro ++;
      $oEtapa = new stdClass();
      $oEtapa->iEtapa      = null;
      $oEtapa->sEtapa      = "Total";
      $oEtapa->sEtapaAbrev = "Total";
      $oEtapa->iFill       = 1;
      $oEtapa->iTotalEtapa = 0;
      $oEnsino->aEtapas[]  = $oEtapa;
    }
    // calcula Alunos Matriculados na Escola do primerio quadro
    calculaAlunosMatriculadosEscola($aEscolasPrimeiroQuadro, $aEtapasErEp, $oDtInicio, $oDtFim);

    foreach ($aEscolasPrimeiroQuadro as $oEscola) {

      if ( $oEscola->iTotalEscola != 0 ) {
        $lRelatorioSemDadosParaPeriodoInformado = false;
      }
    }
  }


  /** ************************************************************************************************************
   ********************************* inicio da busca dos dados do SEGUNDO QUADRO *********************************
   ************************************************************************************************************ **/

  /**
   * Busca ensinos que possuam turmas de correção de fluxo
   */
  $aWhereCorrecaoFluxo    = array();
  $aWhereCorrecaoFluxo[]  = " ed57_i_tipoturma = 7 ";
  $aWhereCorrecaoFluxo[]  = $sFiltraAno;
  $aEnsinosCorrecaoFluxo  = buscaCursosMinistradoAno ( $aWhereCorrecaoFluxo );
  $sEnsinosSegundoQuandro = implode(", ", $aEnsinosCorrecaoFluxo);

  if (count($aEnsinosCorrecaoFluxo) > 0 )  {

    $aWhereSegundoQuadro   = array();
    $aWhereSegundoQuadro[] = " ed10_i_codigo in ({$sEnsinosSegundoQuandro}) ";
    $rsSegundoQuandro      = buscaDadosEnsinosMinistrados($aWhereSegundoQuadro);

    $iLinhas = pg_num_rows($rsSegundoQuandro);
    if ($iLinhas == 0) {
      throw new Exception("Não há turmas cadastradas para o ano: " . $oDtInicio->getAno() );
    }

    $iNumeroEtapasSegundoQuadro = $iLinhas;
    for ($i = 0; $i < $iLinhas; $i++) {

      $oDados  = db_utils::fieldsMemory($rsSegundoQuandro, $i);
      $iEnsino = $oDados->cod_ensino;
      if ( !array_key_exists($iEnsino, $aEtapasSegundoQuadro) ) {

        $oEnsino = new stdClass();
        $oEnsino->iEnsino        = $oDados->cod_ensino;
        $oEnsino->sEnsino        = $oDados->ensino;
        $oEnsino->sEnsinoAbrev   = $oDados->ensino_abrev;
        $oEnsino->lCorrecaoFluxo = true;
        $oEnsino->iTotalEnsino   = 0;
        $oEnsino->aEtapas        = array();

        $aEtapasSegundoQuadro[$iEnsino] = $oEnsino;
      }

      $oEtapa = new stdClass();
      $oEtapa->iEtapa      = $oDados->cod_etapa;
      $oEtapa->sEtapa      = $oDados->etapa;
      $oEtapa->sEtapaAbrev = $oDados->etapa_abrev;
      $oEtapa->iFill       = 0;
      $oEtapa->iTotalEtapa = 0;

      $aEtapasSegundoQuadro[$iEnsino]->aEtapas[] = $oEtapa;
    }

    // Adiciona a coluna de totalizador para cada ensino
    foreach ($aEtapasSegundoQuadro as $oEnsino) {

      $iNumeroEtapasSegundoQuadro ++;
      $oEtapa = new stdClass();
      $oEtapa->iEtapa      = null;
      $oEtapa->sEtapa      = "Total";
      $oEtapa->sEtapaAbrev = "Total";
      $oEtapa->iFill       = 1;
      $oEtapa->iTotalEtapa = 0;
      $oEnsino->aEtapas[]  = $oEtapa;
    }

    calculaAlunosMatriculadosEscola($aEscolasSegundoQuadro, $aEtapasSegundoQuadro, $oDtInicio, $oDtFim);
  }

  /**
   * Busca os alunos matriculados nos cursos profissionais
   */
  foreach ($aEscolasSegundoQuadro as $oEscola) {

    $oEscola->iTotalMatriculasProfissional = buscaAlunosMatriculadosTipoEnsino($oEscola->codigo, 4, $oDtInicio, $oDtFim);
    $oEscola->iTotalEscola += $oEscola->iTotalMatriculasProfissional;
  }

  /**
   * Busca os alunos matriculados nos de educação especial
   */
  foreach ($aEscolasSegundoQuadro as $oEscola) {

    $oEscola->iTotalMatriculasEspecial = buscaAlunosMatriculadosTipoEnsino($oEscola->codigo, 2, $oDtInicio, $oDtFim);
    $oEscola->iTotalEscola += $oEscola->iTotalMatriculasEspecial;
  }

  /**
   * Busca todos alunos matriculados em turmas de AEE
   */
  foreach ($aEscolasSegundoQuadro as $oEscola) {

    $oEscola->iTotalMatriculasAee = buscaAlunosMatriculadosAEE($oEscola->codigo, $oDtInicio, $oDtFim);
    $oEscola->iTotalEscola += $oEscola->iTotalMatriculasAee;
  }

  /**
   * Busca todos alunos matriculados em turmas de atividade complementar
   */
  foreach ($aEscolasSegundoQuadro as $oEscola) {

    $oEscola->iTotalMatriculasComplementar = buscaAlunosMatriculadosAtivComplementar($oEscola->codigo, $oDtInicio, $oDtFim);
    $oEscola->iTotalEscola += $oEscola->iTotalMatriculasComplementar;

    if ( $oEscola->iTotalEscola != 0 ) {
      $lRelatorioSemDadosParaPeriodoInformado = false;
    }
  }

  /**
   * Busca todos alunos matriculados em turmas que participam do programa: Mais Educação
   */
  foreach ($aEscolasSegundoQuadro as $oEscola) {
    $oEscola->iTotalMatriculasMaisEducacao += buscaAlunosMatriculadosMaisEducacao($oEscola->codigo, $oDtInicio, $oDtFim);
  }

  if ($lRelatorioSemDadosParaPeriodoInformado) {
    throw new Exception("Relatório sem dados para o período informado.");
  }


  /** ************************************************************************************************************
   ******************************* inicio da busca dos dados do TERCEIRO QUADRO **********************************
   ************************************************************************************************************ **/
  $oStdSexo                  = new stdClass();
  $oStdSexo->iTotalMasculino = 0;
  $oStdSexo->iTotalFeminino  = 0;
  $oStdSexo->iTotal          = 0;
  foreach ($aEscolasTerceiroQuadro as $oEscola) {


    $oEscola->iTotalTurnoSexo = 0; // totalizador do quadro Quantidade de Alunos, totalizando todos turnos
    $oEscola->iTotalSituacoes = 0; // totalizador do quadro Quantidade de Alunos, totalizando todas situacoes

    $oEscola->aTurnos    = array();
    $oEscola->aTurnos[1] = clone($oStdSexo);
    $oEscola->aTurnos[2] = clone($oStdSexo);
    $oEscola->aTurnos[3] = clone($oStdSexo);
    $oEscola->aTurnos[4] = clone($oStdSexo);

    $aAlunoMatriculadosSexo = quantificarAlunosMatriculadoPorSexo( $oEscola->codigo, $oDtInicio, $oDtFim );

    foreach ($aAlunoMatriculadosSexo as $oAlunosSexoTurno) {

      switch ($oAlunosSexoTurno->sexo) {
        case 'M':

          $oEscola->aTurnos[$oAlunosSexoTurno->turnoreferente]->iTotalMasculino += $oAlunosSexoTurno->qtd_alunos;
          break;
        case 'F':

          $oEscola->aTurnos[$oAlunosSexoTurno->turnoreferente]->iTotalFeminino += $oAlunosSexoTurno->qtd_alunos;
          break;
      }
      $oEscola->aTurnos[$oAlunosSexoTurno->turnoreferente]->iTotal += $oAlunosSexoTurno->qtd_alunos;
      $oEscola->iTotalTurnoSexo                                    += $oAlunosSexoTurno->qtd_alunos;
    }


    $oEscola->iTotalTransfFora = quantificarAlunosPorSituacao($oEscola->codigo, $oDtInicio, $oDtFim, "= 'TRANSFERIDO FORA'");
    $oEscola->iTotalTransfRede = quantificarAlunosPorSituacao($oEscola->codigo, $oDtInicio, $oDtFim, "= 'TRANSFERIDO REDE'");
    $oEscola->iTotalEvadido    = quantificarAlunosPorSituacao($oEscola->codigo, $oDtInicio, $oDtFim, "='EVADIDO'");

    $sSituacao                      = " not in ('TRANSFERIDO FORA', 'TRANSFERIDO REDE', 'EVADIDO', 'MATRICULADO') ";
    $oEscola->iTotalOutrasSituacoes = quantificarAlunosPorSituacao($oEscola->codigo, $oDtInicio, $oDtFim, $sSituacao);

    $oEscola->iTotalSituacoes += $oEscola->iTotalTransfFora;
    $oEscola->iTotalSituacoes += $oEscola->iTotalTransfRede;
    $oEscola->iTotalSituacoes += $oEscola->iTotalEvadido;
    $oEscola->iTotalSituacoes += $oEscola->iTotalOutrasSituacoes;

  }

  foreach ( $aEscolasQuartoQuadro as $oEscola ) {

    // Quadro Quantidade de turmas AEE
    $oEscola->iAeeManha    = 0;
    $oEscola->iAeeTarde    = 0;
    $oEscola->iAeeNoite    = 0;
    $oEscola->iAeeIntegral = 0;
    $oEscola->iAeeTotal    = 0;
    // Quadro Quantidade de turmas atividade complementar
    $oEscola->iComplementarManha    = 0;
    $oEscola->iComplementarTarde    = 0;
    $oEscola->iComplementarNoite    = 0;
    $oEscola->iComplementarIntegral = 0;
    $oEscola->iComplementarTotal    = 0;
    // Quadro Quantidade de turmas de escolarização
    $oEscola->iRegularManha    = 0;
    $oEscola->iRegularTarde    = 0;
    $oEscola->iRegularNoite    = 0;
    $oEscola->iRegularIntegral = 0;
    $oEscola->iRegularTotal    = 0;
    $oEscola->iTotalDeTurmas   = 0;
    // Quadro Quantidade de turmas participante do mais educacao
    // Não tem turno noite segundo Tachmir
    $oEscola->iMaisEducacaoManha    = 0;
    $oEscola->iMaisEducacaoTarde    = 0;
    $oEscola->iMaisEducacaoNoite    = 0;
    $oEscola->iMaisEducacaoIntegral = 0;
    $oEscola->iMaisEducacaoTotal    = 0;

    $aTurmas = quantificarTurmasTurno($oEscola->codigo, $oDtInicio, $oDtFim);

    foreach ($aTurmas as $oDadosTurma) {

      if ( $oDadosTurma->mais_educacao == 't' ) {

        switch ($oDadosTurma->turnoreferente) {
          case 1:
            $oEscola->iMaisEducacaoManha += $oDadosTurma->turmas;
            break;
          case 2:
            $oEscola->iMaisEducacaoTarde += $oDadosTurma->turmas;
            break;
          case 3:
            $oEscola->iMaisEducacaoNoite += $oDadosTurma->turmas;
            break;
          case 4:
            $oEscola->iMaisEducacaoIntegral += $oDadosTurma->turmas;
            break;
        }
        $oEscola->iMaisEducacaoTotal += $oDadosTurma->turmas;
      }
      switch ($oDadosTurma->tipo) {
        case 'regular':

          switch ($oDadosTurma->turnoreferente) {
            case 1:
              $oEscola->iRegularManha += $oDadosTurma->turmas;
              break;
            case 2:
              $oEscola->iRegularTarde += $oDadosTurma->turmas;
              break;
            case 3:
              $oEscola->iRegularNoite += $oDadosTurma->turmas;
              break;
            case 4:
              $oEscola->iRegularIntegral += $oDadosTurma->turmas;
              break;
          }
          $oEscola->iRegularTotal += $oDadosTurma->turmas;
          break;
        case 'complementar':

          switch ($oDadosTurma->turnoreferente) {
            case 1:
              $oEscola->iComplementarManha += $oDadosTurma->turmas;
              break;
            case 2:
              $oEscola->iComplementarTarde += $oDadosTurma->turmas;
              break;
            case 3:
              $oEscola->iComplementarNoite += $oDadosTurma->turmas;
              break;
            case 4:
              $oEscola->iComplementarIntegral += $oDadosTurma->turmas;
              break;
          }
          $oEscola->iComplementarTotal += $oDadosTurma->turmas;
          break;
        case 'aee':

          switch ($oDadosTurma->turnoreferente) {
            case 1:
              $oEscola->iAeeManha += $oDadosTurma->turmas;
              break;
            case 2:
              $oEscola->iAeeTarde += $oDadosTurma->turmas;
              break;
            case 3:
              $oEscola->iAeeNoite += $oDadosTurma->turmas;
              break;
            case 4:
              $oEscola->iAeeIntegral += $oDadosTurma->turmas;
              break;
          }
          $oEscola->iAeeTotal += $oDadosTurma->turmas;
          break;
      }
    }

    $oEscola->iTotalDeTurmas = $oEscola->iRegularTotal + $oEscola->iComplementarTotal + $oEscola->iAeeTotal;
  }

} catch (Exception $e) {
  db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
}

/**
 * Configuração do relatório
 * @var stdClass
 */
$oConfig                           = new stdClass();
$oConfig->iXMaxima                 = 281;
$oConfig->iYMaximo                 = 195;
$oConfig->iHLinha                  = 4;
$oConfig->iXEscola                 = 50;
$oConfig->iXSubTotal               = 10;
$oConfig->iXMaximoEnsino           = $oConfig->iXMaxima - $oConfig->iXEscola - $oConfig->iXSubTotal;
$oConfig->iColunaProfissional      = 10;
$oConfig->iColunaEspecial          = 10;
$oConfig->iColunaAee               = 10;
$oConfig->iColunaAtivComplementar  = 10;
$oConfig->iColunaMaisEducacao      = 10;

$oConfig->iOutrasColunas           = ($oConfig->iColunaProfissional + $oConfig->iColunaEspecial + $oConfig->iColunaAee + $oConfig->iColunaAtivComplementar + $oConfig->iColunaMaisEducacao);
$oConfig->iXColunasEtapasPrimeriro = $oConfig->iXMaximoEnsino;
if (!empty($iNumeroEtapasPrimeiroQuadro)) {
  $oConfig->iXColunasEtapasPrimeriro = $oConfig->iXMaximoEnsino / $iNumeroEtapasPrimeiroQuadro;
}
$oConfig->iXColunasEtapasSegundo   = ($oConfig->iXMaximoEnsino - $oConfig->iOutrasColunas);
if ( !empty($iNumeroEtapasSegundoQuadro) ) {
  $oConfig->iXColunasEtapasSegundo   = ($oConfig->iXMaximoEnsino - $oConfig->iOutrasColunas) / $iNumeroEtapasSegundoQuadro;
}
$oConfig->iAlturaCabecalho         = 25;
$oConfig->iAnoImpressao            = $oDtInicio->getAno();
$oConfig->iLinhasPorPagina         = 26;

$head1 = "Mapa Estatístico";
$head2 = "Período: {$oGet->dtInicio} até {$oGet->dtFim}";

$oPdf = new FpdfMultiCellBorder("L");
$oPdf->setExibeBrasao(true);
$oPdf->exibeHeader(true);
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetMargins(8, 8);
$oPdf->SetFillColor(223);
$oPdf->SetAutoPageBreak(false, 10);



/** *******************************************************************************************************************
 *  ************************************* INICIO IMPRESSAO PRIMEIRO QUADRO ********************************************
 *  ***************************************************************************************************************** */

/**
 * Imprime os Alunos matriculados dos Níveis de ensino de todas as escolas
 * Este bloco abrange todos os alunos matriculados no período informado em turmas do tipo:
 * 1 - NORMAL
 * 2 - EJA
 * 3 - MULTIETAPA
 */
if (count($aEtapasErEp) > 0) {

  imprimePrimeiroCabecalho($oPdf, $oConfig, $aEtapasErEp);
  $iLinhasImpressas = 0;
  foreach ($aEscolasPrimeiroQuadro as $oEscola) {

    if ($oConfig->iLinhasPorPagina == $iLinhasImpressas) {

      $iLinhasImpressas = 0;
      imprimePrimeiroCabecalho($oPdf, $oConfig, $aEtapasErEp);
    }

    $sNomeEscola = cortaString($oPdf, $oEscola->escola, $oEscola->escola_abrev, $oConfig->iXEscola);
    $oPdf->Cell($oConfig->iXEscola,  5, "{$sNomeEscola}", 1, 0);
    if ( isset($oEscola->aAlunosEtapa)) {

      foreach ($oEscola->aAlunosEtapa as $sIndex => $iAlunosMatriculados) {

        $iFill = 1;
        if (strpos($sIndex, "T") === false) {
          $iFill = 0;
        }
        $iAlunosMatriculados = $iAlunosMatriculados == 0 ? "" : $iAlunosMatriculados;
        $oPdf->Cell($oConfig->iXColunasEtapasPrimeriro, 5, $iAlunosMatriculados , 1, 0, 'C', $iFill);
      }
    }
    $oPdf->Cell($oConfig->iXSubTotal, 5, $oEscola->iTotalEscola , 1, 0, 'C', $iFill);
    $oPdf->ln();


    $iLinhasImpressas ++;
  }

  /**
   * Totalizador do primeiro quadro
   */
  $oPdf->SetFont('arial', 'B', 7);
  $oPdf->Cell($oConfig->iXEscola,  5, "Total", 1, 0, 'L', 1);
  $oPdf->SetFont('arial', '', 6);
  $iTotalGeralPrimeiroQuadro = 0;
  foreach ($aEtapasErEp as $oEnsino) {

    $iTotalGeralPrimeiroQuadro += $oEnsino->iTotalEnsino;
    foreach ($oEnsino->aEtapas as $oEtapa) {

      $oPdf->Cell($oConfig->iXColunasEtapasPrimeriro,  5, $oEtapa->iTotalEtapa, 1, 0, 'C', 1);
    }
  }
  $oPdf->Cell($oConfig->iXSubTotal,  5, $iTotalGeralPrimeiroQuadro, 1, 0, 'C', 1);
}


/** *******************************************************************************************************************
 *  ************************************* INICIO IMPRESSAO SEGUNDO QUADRO *********************************************
 *  ***************************************************************************************************************** */

/**
 * Imprime os Alunos matriculados dos Níveis de ensino de todas as escolas
 * Este bloco abrange todos os alunos matriculados no período informado em turmas do tipo:
 * 7 - CORREÇÃO DE FLUXO
 * ATENDIMENTO EDUCACIONAL ESPECIAL - AEE
 */
imprimeSegundoCabecalho($oPdf, $oConfig, $aEtapasSegundoQuadro);
$iLinhasImpressas             = 0;
$iTotalMatriculasProfissional = 0;
$iTotalMatriculasEspecial     = 0;
$iTotalMatriculasAee          = 0;
$iTotalMatriculasComplementar = 0;
$iTotalMatriculasMaisEducacao = 0;

foreach ($aEscolasSegundoQuadro as $oEscola) {

  if ($oConfig->iLinhasPorPagina == $iLinhasImpressas) {

    $iLinhasImpressas = 0;
    imprimeSegundoCabecalho($oPdf, $oConfig, $aEtapasSegundoQuadro);
  }

  $sNomeEscola = cortaString($oPdf, $oEscola->escola, $oEscola->escola_abrev, $oConfig->iXEscola);
  $oPdf->Cell($oConfig->iXEscola,  5, "{$sNomeEscola}", 1, 0);
  if ( isset($oEscola->aAlunosEtapa)) {

    foreach ($oEscola->aAlunosEtapa as $sIndex => $iAlunosMatriculados) {

      $iFill = 1;
      if (strpos($sIndex, "T") === false) {
        $iFill = 0;
      }
      $iAlunosMatriculados = $iAlunosMatriculados == 0 ? "" : $iAlunosMatriculados;
      $oPdf->Cell($oConfig->iXColunasEtapasSegundo, 5, $iAlunosMatriculados , 1, 0, 'C', $iFill);
    }
  }
  // sempre que não tem etaoas de correção de fluxo, incrementa o eixo X
  if (count($aEtapasSegundoQuadro) == 0) {
    $oPdf->SetX($oPdf->GetX()+$oConfig->iXColunasEtapasSegundo);
  }

  $iTotalMatriculasProfissional += $oEscola->iTotalMatriculasProfissional;
  $iTotalMatriculasEspecial     += $oEscola->iTotalMatriculasEspecial;
  $iTotalMatriculasAee          += $oEscola->iTotalMatriculasAee;
  $iTotalMatriculasComplementar += $oEscola->iTotalMatriculasComplementar;
  $iTotalMatriculasMaisEducacao += $oEscola->iTotalMatriculasMaisEducacao;
  $oPdf->Cell($oConfig->iXSubTotal, 5, (int) $oEscola->iTotalMatriculasProfissional, 1, 0, 'C', 1);
  $oPdf->Cell($oConfig->iXSubTotal, 5, (int) $oEscola->iTotalMatriculasEspecial,     1, 0, 'C', 1);
  $oPdf->Cell($oConfig->iXSubTotal, 5, (int) $oEscola->iTotalMatriculasAee,          1, 0, 'C', 1);
  $oPdf->Cell($oConfig->iXSubTotal, 5, (int) $oEscola->iTotalMatriculasComplementar, 1, 0, 'C', 1);
  $oPdf->Cell($oConfig->iXSubTotal, 5, (int) $oEscola->iTotalMatriculasMaisEducacao, 1, 0, 'C', 0);
  $oPdf->Cell($oConfig->iXSubTotal, 5, (int) $oEscola->iTotalEscola,                 1, 0, 'C', 1);
  $oPdf->Line($oPdf->lMargin, $oPdf->GetY() + 5, $oConfig->iXMaxima, $oPdf->GetY() + 5);
  $oPdf->ln();

  $iLinhasImpressas ++;
}

/**
 * Totalizador do segundo quadro
 */
$oPdf->SetFont('arial', 'B', 7);
$oPdf->Cell($oConfig->iXEscola,  5, "Total", 1, 0, 'L', 1);
$oPdf->SetFont('arial', '', 6);
$iTotalGeralSegundoQuadro = 0;
foreach ($aEtapasSegundoQuadro as $oEnsino) {

  $iTotalGeralSegundoQuadro += $oEnsino->iTotalEnsino;
  foreach ($oEnsino->aEtapas as $oEtapa) {
    $oPdf->Cell($oConfig->iXColunasEtapasSegundo,  5, $oEtapa->iTotalEtapa, 1, 0, 'C', 1);
  }
}

$oPdf->Line($oPdf->lMargin, $oPdf->GetY(), $oConfig->iXMaxima, $oPdf->GetY());

// sempre que não tem etaoas de correção de fluxo, incrementa o eixo X
if (count($aEtapasSegundoQuadro) == 0) {
  $oPdf->SetX($oPdf->GetX()+$oConfig->iXColunasEtapasSegundo);
}

$iTotalGeralSegundoQuadro += $iTotalMatriculasProfissional;
$iTotalGeralSegundoQuadro += $iTotalMatriculasEspecial;
$iTotalGeralSegundoQuadro += $iTotalMatriculasAee;
$iTotalGeralSegundoQuadro += $iTotalMatriculasComplementar;
$iTotalGeralMaisEducacao   = $iTotalMatriculasMaisEducacao;
$oPdf->Cell($oConfig->iColunaProfissional     , 5, $iTotalMatriculasProfissional, 1, 0, 'C', 1);
$oPdf->Cell($oConfig->iColunaEspecial         , 5, $iTotalMatriculasEspecial    , 1, 0, 'C', 1);
$oPdf->Cell($oConfig->iColunaAee              , 5, $iTotalMatriculasAee         , 1, 0, 'C', 1);
$oPdf->Cell($oConfig->iColunaAtivComplementar , 5, $iTotalMatriculasComplementar, 1, 0, 'C', 1);
$oPdf->Cell($oConfig->iColunaMaisEducacao     , 5, $iTotalMatriculasMaisEducacao, 1, 0, 'C', 0);
$oPdf->Cell($oConfig->iXSubTotal,               5, $iTotalGeralSegundoQuadro,     1, 0, 'C', 1);

$oPdf->ln();
$oPdf->Line($oPdf->lMargin, $oPdf->GetY(), $oConfig->iXMaxima, $oPdf->GetY());
$oPdf->Cell($oConfig->iXMaxima, 5, "* Coluna Mais Educação não contabiliza no totalizador.",  0, 0, 'L', 0);


/** *******************************************************************************************************************
 *  ************************************* FIM TOTALIZADOR SEGUNDO QUADRO **********************************************
 *  ***************************************************************************************************************** */



/** *******************************************************************************************************************
 *  *********************************** TOTALIZADOR MOVIMENTACAO DOS ALUNO ********************************************
 *  ***************************************************************************************************************** */

AddPage($oPdf, $oConfig);
$oPdf->SetFont('arial', 'B', 7);
$oPdf->Cell($oConfig->iXMaxima, 5, "Totalizador Geral", 1, 1, 'C', 1);

$oPdf->SetFont('arial', '', 6);
foreach ($aEtapasErEp as $oEnsino) {

  $oPdf->Cell(200, 5, $oEnsino->sEnsino,     1, 0, 'L', 0);
  $oPdf->Cell(81,  5, $oEnsino->iTotalEnsino, 1, 1, 'R', 0);
}

foreach ($aEtapasSegundoQuadro as $oEnsino) {

  $oPdf->Cell(200, 5, $oEnsino->sEnsino . " - CORREÇÃO DE FLUXO",     1, 0, 'L', 0);
  $oPdf->Cell(81,  5, $oEnsino->iTotalEnsino, 1, 1, 'R', 0);
}

$oPdf->Cell(200, 5, "EDUCAÇÃO PROFISSIONAL",                  1, 0, 'L');
$oPdf->Cell(81,  5, $iTotalMatriculasProfissional,            1, 1, 'R');
$oPdf->Cell(200, 5, "EDUCAÇÃO ESPECIAL",                      1, 0, 'L');
$oPdf->Cell(81,  5, $iTotalMatriculasEspecial,                1, 1, 'R');
$oPdf->Cell(200, 5, "ATENDIMENTO EDUCACIONAL ESPECIAL - AEE", 1, 0, 'L');
$oPdf->Cell(81,  5, $iTotalMatriculasAee,                     1, 1, 'R');
$oPdf->Cell(200, 5, "ATIVIDADE COMPLEMENTAR",                 1, 0, 'L');
$oPdf->Cell(81,  5, $iTotalMatriculasComplementar,            1, 1, 'R');

$oPdf->Cell(200, 5, "MAIS EDUCAÇÃO",                          1, 0, 'L');
$oPdf->Cell(81,  5, $iTotalGeralMaisEducacao,                 1, 1, 'R');

/** *******************************************************************************************************************
 *  ********************************* FIM TOTALIZADOR MOVIMENTACAO DOS ALUNO ******************************************
 *  ***************************************************************************************************************** */


/** *******************************************************************************************************************
 *  ************************************* INICIO IMPRESSAO TERCEIRO QUADRO ********************************************
 *  ***************************************************************************************************************** */

imprimeTerceiroCabecalho($oPdf, $oConfig);
$iLinhasImpressas = 0;

$oStdSexo                  = new stdClass();
$oStdSexo->iTotalMasculino = 0;
$oStdSexo->iTotalFeminino  = 0;
$oStdSexo->iTotal          = 0;

$aTotaisTurnos    = array();
$aTotaisTurnos[1] = clone($oStdSexo);
$aTotaisTurnos[2] = clone($oStdSexo);
$aTotaisTurnos[3] = clone($oStdSexo);
$aTotaisTurnos[4] = clone($oStdSexo);

$iTotalTurnoSexo       = 0;
$iTotalTransfFora      = 0;
$iTotalTransfRede      = 0;
$iTotalEvadido         = 0;
$iTotalOutrasSituacoes = 0;
$iTotalSituacoes       = 0;

foreach ($aEscolasTerceiroQuadro as $oDadosEscola) {

  if ($oConfig->iLinhasPorPagina == $iLinhasImpressas) {

    $iLinhasImpressas = 0;
    imprimeTerceiroCabecalho($oPdf, $oConfig);
  }
  $oPdf->SetFont('arial', '', 7);
  $sNomeEscola = cortaString($oPdf, $oDadosEscola->escola, $oDadosEscola->escola_abrev, $oConfig->iXEscola);
  $oPdf->Cell($oConfig->iXEscola,  5, "{$sNomeEscola}", 1, 0);

  foreach ($oDadosEscola->aTurnos as $iIndex => $oTotais) {

    $oPdf->Cell(10, 5, (int) $oTotais->iTotalMasculino, 1, 0, 'C', 0);
    $oPdf->Cell(10, 5, (int) $oTotais->iTotalFeminino,  1, 0, 'C', 0);
    $oPdf->Cell(10, 5, (int) $oTotais->iTotal,          1, 0, 'C', 1);

    $aTotaisTurnos[$iIndex]->iTotalMasculino += $oTotais->iTotalMasculino;
    $aTotaisTurnos[$iIndex]->iTotalFeminino  += $oTotais->iTotalFeminino;
    $aTotaisTurnos[$iIndex]->iTotal          += $oTotais->iTotal;
  }
  $oPdf->Cell(10, 5, (int) $oDadosEscola->iTotalTurnoSexo,       1, 0, 'C', 1);
  $oPdf->Cell(20, 5, (int) $oDadosEscola->iTotalTransfFora,      1, 0, 'C', 0);
  $oPdf->Cell(20, 5, (int) $oDadosEscola->iTotalTransfRede,      1, 0, 'C', 0);
  $oPdf->Cell(20, 5, (int) $oDadosEscola->iTotalEvadido,         1, 0, 'C', 0);
  $oPdf->Cell(20, 5, (int) $oDadosEscola->iTotalOutrasSituacoes, 1, 0, 'C', 0);
  $oPdf->Cell(21, 5, (int) $oDadosEscola->iTotalSituacoes,       1, 1, 'C', 1);

  $iTotalTurnoSexo       += $oDadosEscola->iTotalTurnoSexo;
  $iTotalTransfFora      += $oDadosEscola->iTotalTransfFora;
  $iTotalTransfRede      += $oDadosEscola->iTotalTransfRede;
  $iTotalEvadido         += $oDadosEscola->iTotalEvadido;
  $iTotalOutrasSituacoes += $oDadosEscola->iTotalOutrasSituacoes;
  $iTotalSituacoes       += $oDadosEscola->iTotalSituacoes;

  $iLinhasImpressas ++;

}
/**
 * Totalizador do terceiro quadro
 */
if ($oConfig->iLinhasPorPagina == $iLinhasImpressas) {
  imprimeTerceiroCabecalho($oPdf, $oConfig);
}
$oPdf->SetFont('arial', 'B', 7);
$oPdf->Cell($oConfig->iXEscola,  5, "Total:", 1, 0, 'L', 1);
foreach ($aTotaisTurnos as $oTotalTurno) {

  $oPdf->Cell(10, 5, (int) $oTotalTurno->iTotalMasculino, 1, 0, 'C', 1);
  $oPdf->Cell(10, 5, (int) $oTotalTurno->iTotalFeminino,  1, 0, 'C', 1);
  $oPdf->Cell(10, 5, (int) $oTotalTurno->iTotal,          1, 0, 'C', 1);
}
$oPdf->Cell(10, 5, (int) $iTotalTurnoSexo,       1, 0, 'C', 1);
$oPdf->Cell(20, 5, (int) $iTotalTransfFora,      1, 0, 'C', 1);
$oPdf->Cell(20, 5, (int) $iTotalTransfRede,      1, 0, 'C', 1);
$oPdf->Cell(20, 5, (int) $iTotalEvadido,         1, 0, 'C', 1);
$oPdf->Cell(20, 5, (int) $iTotalOutrasSituacoes, 1, 0, 'C', 1);
$oPdf->Cell(21, 5, (int) $iTotalSituacoes,       1, 1, 'C', 1);


/** *******************************************************************************************************************
 *  ************************************* INICIO IMPRESSAO QUARTO QUADRO **********************************************
 *  ***************************************************************************************************************** */
imprimeQuartoCabecalho($oPdf, $oConfig);
$iLinhasImpressas = 0;
$iXDobroColuna    = 20;
$iXColunaPadrao   = 10;

$iTotalizadorAeeManha             = 0;
$iTotalizadorAeeTarde             = 0;
$iTotaliAeeNoite                  = 0;
$iTotaliAeeIntegral               = 0;
$iTotalizadorAeeTotal             = 0;

$iTotalizadorComplementarManha    = 0;
$iTotalizadorComplementarTarde    = 0;
$iTotalizadorComplementarNoite    = 0;
$iTotalizadorComplementarIntegral = 0;
$iTotalizadorComplementarTotal    = 0;

$iTotalizadorRegularManha         = 0;
$iTotalizadorRegularTarde         = 0;
$iTotalizadorRegularNoite         = 0;
$iTotalizadorRegularIntegral      = 0;
$iTotalizadorRegularTotal         = 0;
$iTotalizadorTotalDeTurmas        = 0;

$iTotalizadorMaisEducacaoManha    = 0;
$iTotalizadorMaisEducacaoTarde    = 0;
$iTotalizadorMaisEducacaoNoite    = 0;
$iTotalizadorMaisEducacaoIntegral = 0;
$iTotalizadorMaisEducacaoTotal    = 0;
foreach ($aEscolasQuartoQuadro as $oDadosEscola) {

  if ($oConfig->iLinhasPorPagina == $iLinhasImpressas) {

    $iLinhasImpressas = 0;
    imprimeQuartoCabecalho($oPdf, $oConfig);
  }
  $oPdf->SetFont('arial', '', 7);
  $sNomeEscola = cortaString($oPdf, $oDadosEscola->escola, $oDadosEscola->escola_abrev, $oConfig->iXEscola);
  $oPdf->Cell($oConfig->iXEscola,  5, "{$sNomeEscola}", 1, 0);

  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iAeeManha,             1, 0, 'C', 0);  // turmas aee
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iAeeTarde,             1, 0, 'C', 0);  // turmas aee
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iAeeNoite,             1, 0, 'C', 0);  // turmas aee
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iAeeIntegral,          1, 0, 'C', 0);  // turmas aee
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iAeeTotal,             1, 0, 'C', 1);  // total turmas aee

  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iComplementarManha,    1, 0, 'C', 0);  // turmas complementar
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iComplementarTarde,    1, 0, 'C', 0);  // turmas complementar
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iComplementarNoite,    1, 0, 'C', 0);  // turmas complementar
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iComplementarIntegral, 1, 0, 'C', 0);  // turmas complementar
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iComplementarTotal,    1, 0, 'C', 1);  // total turmas complementar

  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iRegularManha,         1, 0, 'C', 0);  // turmas escolarizacao
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iRegularTarde,         1, 0, 'C', 0);  // turmas escolarizacao
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iRegularNoite,         1, 0, 'C', 0);  // turmas escolarizacao
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iRegularIntegral,      1, 0, 'C', 0);  // turmas escolarizacao
  $oPdf->Cell($iXColunaPadrao, 5, (int) $oDadosEscola->iRegularTotal,         1, 0, 'C', 1);  // total turmas escolarizacao
  $oPdf->Cell(15, 5, (int) $oDadosEscola->iTotalDeTurmas,                     1, 0, 'C', 1);  // total geral turmas escolarizacao

  $oPdf->Cell(13, 5, $oDadosEscola->iMaisEducacaoManha,    1, 0, 'C', 0); // turmas mais educação
  $oPdf->Cell(13, 5, $oDadosEscola->iMaisEducacaoTarde,    1, 0, 'C', 0); // turmas mais educação
  $oPdf->Cell(14, 5, $oDadosEscola->iMaisEducacaoNoite,    1, 0, 'C', 0); // turmas mais educação
  $oPdf->Cell(14, 5, $oDadosEscola->iMaisEducacaoIntegral, 1, 0, 'C', 0); // turmas mais educação
  $oPdf->Cell(12, 5, $oDadosEscola->iMaisEducacaoTotal,    1, 1, 'C', 1); // turmas mais educação

  $iTotalizadorAeeManha             += $oDadosEscola->iAeeManha;
  $iTotalizadorAeeTarde             += $oDadosEscola->iAeeTarde;
  $iTotaliAeeNoite                  += $oDadosEscola->iAeeNoite;
  $iTotaliAeeIntegral               += $oDadosEscola->iAeeIntegral;
  $iTotalizadorAeeTotal             += $oDadosEscola->iAeeTotal;
  $iTotalizadorComplementarManha    += $oDadosEscola->iComplementarManha;
  $iTotalizadorComplementarTarde    += $oDadosEscola->iComplementarTarde;
  $iTotalizadorComplementarNoite    += $oDadosEscola->iComplementarNoite;
  $iTotalizadorComplementarIntegral += $oDadosEscola->iComplementarIntegral;
  $iTotalizadorComplementarTotal    += $oDadosEscola->iComplementarTotal;
  $iTotalizadorRegularManha         += $oDadosEscola->iRegularManha;
  $iTotalizadorRegularTarde         += $oDadosEscola->iRegularTarde;
  $iTotalizadorRegularNoite         += $oDadosEscola->iRegularNoite;
  $iTotalizadorRegularIntegral      += $oDadosEscola->iRegularIntegral;
  $iTotalizadorRegularTotal         += $oDadosEscola->iRegularTotal;
  $iTotalizadorTotalDeTurmas        += $oDadosEscola->iTotalDeTurmas;
  $iTotalizadorMaisEducacaoManha    += $oDadosEscola->iMaisEducacaoManha;
  $iTotalizadorMaisEducacaoTarde    += $oDadosEscola->iMaisEducacaoTarde;
  $iTotalizadorMaisEducacaoNoite    += $oDadosEscola->iMaisEducacaoNoite;
  $iTotalizadorMaisEducacaoIntegral += $oDadosEscola->iMaisEducacaoIntegral;
  $iTotalizadorMaisEducacaoTotal    += $oDadosEscola->iMaisEducacaoTotal;
  $iLinhasImpressas ++;

}

/**
 * Totalizador quarto quadro
 */
if ( $oConfig->iLinhasPorPagina == $iLinhasImpressas ) {
  imprimeQuartoCabecalho($oPdf, $oConfig);
}
$oPdf->SetFont('arial', 'B', 7);
$oPdf->Cell($oConfig->iXEscola,  5, "Total:", 1, 0, 'L', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorAeeManha,            1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorAeeTarde,            1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotaliAeeNoite,                 1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotaliAeeIntegral,              1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorAeeTotal,            1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorComplementarManha ,   1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorComplementarTarde ,   1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorComplementarNoite ,   1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorComplementarIntegral, 1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorComplementarTotal ,   1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorRegularManha ,        1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorRegularTarde ,        1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorRegularNoite ,        1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorRegularIntegral ,     1, 0, 'C', 1);
$oPdf->Cell($iXColunaPadrao, 5, (int) $iTotalizadorRegularTotal ,        1, 0, 'C', 1);
$oPdf->Cell(15, 5,              (int) $iTotalizadorTotalDeTurmas ,       1, 0, 'C', 1);
$oPdf->Cell(13, 5,              (int) $iTotalizadorMaisEducacaoManha ,   1, 0, 'C', 1);
$oPdf->Cell(13, 5,              (int) $iTotalizadorMaisEducacaoTarde ,   1, 0, 'C', 1);
$oPdf->Cell(14, 5,              (int) $iTotalizadorMaisEducacaoNoite,    1, 0, 'C', 1);
$oPdf->Cell(14, 5,              (int) $iTotalizadorMaisEducacaoIntegral, 1, 0, 'C', 1);
$oPdf->Cell(12, 5,              (int) $iTotalizadorMaisEducacaoTotal ,   1, 1, 'C', 1);

$oPdf->Output();

/**
 * Adiciona uma nova paginal
 * @param FpdfMultiCellBorder $oPdf    Instancia de FPDF
 * @param StdClass           $oConfig  Configuração do relatorio
 */
function addPage(FpdfMultiCellBorder $oPdf, $oConfig, $lImprimeLinhaMovimentacao = true) {

  $oPdf->AddPage();

  if ($lImprimeLinhaMovimentacao) {

    $oPdf->SetFont('arial', 'B', 7);
    $oPdf->Cell($oConfig->iXMaxima, $oConfig->iHLinha, "Movimentação de Alunos", 1, 1, "C");
    $oPdf->SetFont('arial', '', 7);
  }
}

/**
 * Imprime o primeiro cabeçalho
 * @param  FpdfMultiCellBorder $oPdf        Instancia de FPDF
 * @param  StdClass            $oConfig     Configuração do relatorio
 * @param  array               $aEtapasErEp Array com os ensinos e as etapas
 * @return void
 */
function imprimePrimeiroCabecalho(FpdfMultiCellBorder $oPdf, $oConfig, $aEtapasErEp) {

  addPage($oPdf, $oConfig);

  $iYInicial = $oPdf->GetY();
  $oPdf->Rect(8, $oPdf->GetY(), $oConfig->iXMaxima, $oConfig->iAlturaCabecalho);
  $oPdf->ln();
  $oPdf->SetFont('arial', 'B', 9);
  $oPdf->MultiCell($oConfig->iXEscola, 5, "Unidade\nEscolar",0, 'C');

  $oPdf->SetY($iYInicial);
  $oPdf->SetX($oPdf->lMargin + $oConfig->iXEscola);

  $iAlturaLinha = 5;

  $oPdf->SetFont('arial', '', 6);
  //imprime os níveis de ensino
  foreach ($aEtapasErEp as $oEnsino) {

    $iXEnsino = count($oEnsino->aEtapas) * $oConfig->iXColunasEtapasPrimeriro;

    $sEnsino = cortaString($oPdf, $oEnsino->sEnsino, $oEnsino->sEnsinoAbrev, $iXEnsino);
    $oPdf->Cell($iXEnsino, $iAlturaLinha, $sEnsino, 1, 0, 'C');
  }

  $oPdf->SetY($oPdf->GetY() + $iAlturaLinha);
  $oPdf->SetX($oPdf->lMargin + $oConfig->iXEscola);

  $iAlturaColunaEtapa = $oConfig->iAlturaCabecalho - $iAlturaLinha;
  //imprime as colunas das etapas
  foreach ($aEtapasErEp as $oEnsino) {

    foreach ($oEnsino->aEtapas as $oEtapa) {
      $oPdf->vCell($oConfig->iXColunasEtapasPrimeriro, $iAlturaColunaEtapa, $oEtapa->sEtapaAbrev, 1, 0, 'C', $oEtapa->iFill);
    }
  }

  $oPdf->SetFont('arial', 'B', 6);
  $oPdf->SetXY($oPdf->GetX(), $iYInicial);
  $oPdf->vCell($oConfig->iXSubTotal, $oConfig->iAlturaCabecalho, "TOTAL", 1, 0, 'C', 1);
  $oPdf->SetFont('arial', '', 6);
  $oPdf->SetY($iYInicial + $oConfig->iAlturaCabecalho);
}

/**
 * Busca os alunos matriculados para a escola um período e etapa
 * @param integer $iEscola   Código da escola
 * @param integer $iEtapa    Código da etapa
 * @param DBDate  $oDtInicio
 * @param DBDate  $oDtInicio Período de matricula
 * @param DBDate  $oDtFim    Período de matricula
 * @param boolean $lCorrecaoFluxo
 * @return int
 * @throws Exception
 */
function buscaAlunosMatriculados($iEscola, $iEtapa, DBDate $oDtInicio, DBDate $oDtFim, $lCorrecaoFluxo) {

  if (is_null($iEtapa)) {
    return 0;
  }

  $sWhere  = " ed60_d_datamatricula <= '" . $oDtFim->getDate() . "' ";
  $sWhere .= " and extract(year FROM ed60_d_datamatricula) = " . $oDtFim->getAno();
  $sWhere .= " and ( ed60_d_datasaida is null or " ;
  $sWhere .= "       ed60_d_datasaida not between '" . $oDtInicio->getDate() . "' and '" . $oDtFim->getDate() . "') ";
  $sWhere .= " and ed60_c_situacao = 'MATRICULADO' ";
  $sWhere .= " and ed221_c_origem  = 'S' ";
  $sWhere .= " and ed57_i_escola   = $iEscola ";
  $sWhere .= " and ed221_i_serie   = $iEtapa  ";
  if ( $lCorrecaoFluxo ) {
    $sWhere .= " and ed57_i_tipoturma = 7 ";
  } else {
    $sWhere .= " and ed57_i_tipoturma not in (6, 7) ";
  }

  $oDaoMatricula = new cl_matricula();
  $sSqlMatricula = $oDaoMatricula->sql_query_bolsafamilia(null, " count(*) ", null, $sWhere);
  $rsMatricula   = db_query($sSqlMatricula);

  if ( !$rsMatricula ) {
    throw new Exception("Erro ao buscar alunos matrículados.\n" . pg_last_error());
  }

  $iMatriculas = 0;
  if (pg_num_rows($rsMatricula) > 0) {
    $iMatriculas = db_utils::fieldsMemory($rsMatricula, 0)->count;
  }
  return $iMatriculas;

}

/**
 * Valida se a String nome cabe na celula de destino.
 * Se não couber e haver um nome abreviado, retorna abreviatura
 * Se não couber e não tem um nome abreviado, corta a string
 *
 * @param  FpdfMultiCellBorder $oPdf           instancia de FPDF
 * @param  string              $sNome
 * @param  string              $sNomeAbreviado [description]
 * @param  integer             $iTamanhoCampo  Tamanho do campo (w)
 * @return string                              String compativel com o campo
 */
function cortaString(FpdfMultiCellBorder $oPdf, $sNome, $sNomeAbreviado, $iTamanhoCampo) {

  if ($oPdf->NbLines($iTamanhoCampo, $sNome) == 1) {
    return $sNome;
  }

  if ( !empty($sNomeAbreviado) && $oPdf->NbLines($iTamanhoCampo, $sNomeAbreviado) == 1 ) {
    return $sNomeAbreviado;
  }

  return substr($sNome, 0, 35) . "...";

}

function buscaCursosMinistradoAno($aFiltros) {

  $oDaoTurma = new cl_turma();
  $sCampo    = " distinct ed29_i_ensino ";
  $sWhere    = implode(" and ", $aFiltros);

  $sSql  = $oDaoTurma->sql_query_turma_ensino(null, $sCampo, null, $sWhere);
  $rs    = db_query($sSql);

  if ( !$rs ) {
    throw new Exception("Erro busca ensinos." . pg_last_error());
  }

  $iLinhas  = pg_num_rows($rs);
  $aEnsinos = array();

  for ($i=0; $i < $iLinhas; $i++) {
    $aEnsinos[] = db_utils::fieldsMemory($rs, $i)->ed29_i_ensino;
  }

  return $aEnsinos;
}

function buscaDadosEnsinosMinistrados($aFiltros) {

  $sWhere   = implode(" and ", $aFiltros);
  $sCampos  = " ed10_i_codigo as cod_ensino, trim(ed10_c_descr) as ensino, ed10_c_abrev as ensino_abrev, ";
  $sCampos .= " ed11_i_codigo as cod_etapa, trim(ed11_c_descr) as etapa, trim(ed11_c_abrev) as etapa_abrev ";

  $sSqlNivelEnsino  = " select {$sCampos}";
  $sSqlNivelEnsino .= "   from ensino ";
  $sSqlNivelEnsino .= "  inner join serie on ed11_i_ensino = ed10_i_codigo ";
  $sSqlNivelEnsino .= "  where {$sWhere}";
  $sSqlNivelEnsino .= "  order by ed10_ordem, ed11_i_sequencia; ";

  $rsNivelEnsino    = db_query($sSqlNivelEnsino);
  if (!$rsNivelEnsino) {
    throw new Exception("Falha ao buscar Níveis de Ensino.\n" . pg_last_error());
  }

  return $rsNivelEnsino;
}

function calculaAlunosMatriculadosEscola($aEscolas, $aEnsinos, $oDtInicio, $oDtFim) {
  /**
   * Percorre as escolas do primeiro quado e soma os alunos matriculados para cada etapa
   */

  foreach ($aEscolas as $oEscola) {

    $oEscola->aAlunosEtapa = array();
    $oEscola->iTotalEscola = 0;
    foreach ($aEnsinos as $oEnsino) {

      $lCorrecaoFluxo = false;
      if (isset($oEnsino->lCorrecaoFluxo) && $oEnsino->lCorrecaoFluxo) {
        $lCorrecaoFluxo = $oEnsino->lCorrecaoFluxo;
      }
      $iTotalEnsino = 0;
      foreach ($oEnsino->aEtapas as $oEtapa) {

        if (is_null($oEtapa->iEtapa)) {
          continue;
        }

        $sHash                = "{$oEnsino->iEnsino}#{$oEtapa->iEtapa}";
        $iTotalAlunosEtapa    = buscaAlunosMatriculados($oEscola->codigo, $oEtapa->iEtapa, $oDtInicio, $oDtFim, $lCorrecaoFluxo);
        $iTotalEnsino        += $iTotalAlunosEtapa;
        $oEtapa->iTotalEtapa += $iTotalAlunosEtapa;

        $oEscola->aAlunosEtapa[$sHash] = $iTotalAlunosEtapa;
      }
      $sHash                         = "{$oEnsino->iEnsino}#T";
      $oEscola->aAlunosEtapa[$sHash] = $iTotalEnsino;
      $oEnsino->iTotalEnsino        += $iTotalEnsino;
      $oEscola->iTotalEscola        += $iTotalEnsino;
      $oEnsino->aEtapas[count($oEnsino->aEtapas) - 1]->iTotalEtapa += $iTotalEnsino;
    }
  }

}

/**
 * Imprime o segundo cabeçalho
 * @param  FpdfMultiCellBorder $oPdf                 Instancia de FPDF
 * @param  StdClass            $oConfig              Configuração do relatorio
 * @param  array               $aEtapasSegundoQuadro Array com os ensinos e as etapas
 * @return void
 */
function imprimeSegundoCabecalho($oPdf, $oConfig, $aEtapasSegundoQuadro) {

  addPage($oPdf, $oConfig);
  $iAlturaLinha = 5;
  $iAlturaCabecalho = $oConfig->iAlturaCabecalho + $iAlturaLinha;
  $iYInicial = $oPdf->GetY();
  $oPdf->Rect(8, $oPdf->GetY(), $oConfig->iXMaxima, $iAlturaCabecalho);
  $oPdf->Rect(8, $oPdf->GetY(), $oConfig->iXEscola, $iAlturaCabecalho);
  $oPdf->ln();
  $oPdf->SetFont('arial', 'B', 9);
  $oPdf->MultiCell($oConfig->iXEscola, 5, "Unidade\nEscolar",0, 'C');

  $oPdf->SetY($iYInicial);
  $oPdf->SetX($oPdf->lMargin + $oConfig->iXEscola);

  $iXCorrecaoFluxo = 0;
  foreach ($aEtapasSegundoQuadro as $oEnsino) {
    $iXCorrecaoFluxo += count($oEnsino->aEtapas) * $oConfig->iXColunasEtapasSegundo;
  }

  // Se não houver correção de fluxo na escola, imprime a coluna com o espaço reservado para as etapas
  if (count($aEtapasSegundoQuadro) == 0) {
    $iXCorrecaoFluxo = $oConfig->iXColunasEtapasSegundo;
  }

  $oPdf->SetFont('arial', 'B', 6);
  $oPdf->Cell($iXCorrecaoFluxo, $iAlturaLinha, "Correção de Fluxo", 1, 1, 'C');

  $oPdf->SetX($oPdf->lMargin + $oConfig->iXEscola);
  $oPdf->SetFont('arial', '', 6);
  //imprime os níveis de ensino
  foreach ($aEtapasSegundoQuadro as $oEnsino) {

    $iXEnsino = count($oEnsino->aEtapas) * $oConfig->iXColunasEtapasSegundo;
    $sEnsino  = cortaString($oPdf, $oEnsino->sEnsino, $oEnsino->sEnsinoAbrev, $iXEnsino);
    $oPdf->Cell($iXEnsino, $iAlturaLinha, $sEnsino, 1, 0, 'C');
  }


  $oPdf->SetY($oPdf->GetY() + $iAlturaLinha);
  $oPdf->SetX($oPdf->lMargin + $oConfig->iXEscola);

  $iAlturaColunaEtapa = $iAlturaCabecalho - $iAlturaLinha;
  //imprime as colunas das etapas
  foreach ($aEtapasSegundoQuadro as $oEnsino) {

    foreach ($oEnsino->aEtapas as $oEtapa) {
      $oPdf->vCell($oConfig->iXColunasEtapasSegundo, $iAlturaColunaEtapa, $oEtapa->sEtapaAbrev, 1, 0, 'C', $oEtapa->iFill);
    }
  }

  $oPdf->SetXY($oPdf->GetX(), $iYInicial);
  // sempre que não tem etaoas de correção de fluxo, incrementa o eixo X
  if (count($aEtapasSegundoQuadro) == 0) {
    $oPdf->SetXY($oPdf->GetX()+$oConfig->iXColunasEtapasSegundo, $iYInicial);
  }

  $oPdf->vCell($oConfig->iColunaProfissional     , $iAlturaCabecalho, "Edu. Profissional",  1, 0, 'C', 1);
  $oPdf->vCell($oConfig->iColunaEspecial         , $iAlturaCabecalho, "Edu. Especial",      1, 0, 'C', 1);
  $oPdf->vCell($oConfig->iColunaAee              , $iAlturaCabecalho, "Matrículas AEE",     1, 0, 'C', 1);
  $oPdf->vCell($oConfig->iColunaAtivComplementar , $iAlturaCabecalho, "Ativ. Complementar", 1, 0, 'C', 1);
  $oPdf->vCell($oConfig->iColunaAtivComplementar , $iAlturaCabecalho, "Mais Educação*",     1, 0, 'C', 0);
  $oPdf->SetFont('arial', 'B', 6);
  $oPdf->vCell($oConfig->iXSubTotal, $iAlturaCabecalho, "TOTAL", 1, 0, 'C', 1);
  $oPdf->SetFont('arial', '', 6);
  $oPdf->SetY($iYInicial + $iAlturaCabecalho);
}

/**
 * Busca todos alunos matriculados na escola em turmas de AEE
 * @param  integer $iEscola   código da escola
 * @param  DBDate  $oDtInicio período da matricula
 * @param  DBDate  $oDtFim    período da matricula
 * @return integer            Número de alunos matriculados
 */
function buscaAlunosMatriculadosAEE($iEscola, $oDtInicio, $oDtFim) {

  $sWhere  = "     ed268_i_escola = {$iEscola} ";
  $sWhere .= " and ed268_i_tipoatend = 5 ";
  $sWhere .= " and ed52_i_ano = " . $oDtInicio->getAno();
  $sWhere .= " and ed269_d_data <= '" . $oDtFim->getDate() . "' ";
  $oDaoAee = new cl_turmaacmatricula();
  $rsAee   = db_query( $oDaoAee->sql_query_turma(null, "count(*)", null, $sWhere) );

  return db_utils::fieldsMemory($rsAee, 0)->count;
}

/**
 * Busca os alunos matriculados na escola pelo tipo de ensino
 * @param integer $iEscola     código da escola
 * @param integer $sTipoEnsino Tipo de ensino
 * @param DBDate  $oDtInicio   período da matricula
 * @param DBDate  $oDtFim      período da matricula
 * @return int
 * @throws Exception
 */
function buscaAlunosMatriculadosTipoEnsino ($iEscola, $sTipoEnsino, $oDtInicio, $oDtFim) {

  $sWhere  = " ed60_d_datamatricula <= '" . $oDtFim->getDate() . "' ";
  $sWhere .= " and extract(year FROM ed60_d_datamatricula) = " . $oDtFim->getAno();
  $sWhere .= " and ( ed60_d_datasaida is null or " ;
  $sWhere .= "       ed60_d_datasaida not between '" . $oDtInicio->getDate() . "' and '" . $oDtFim->getDate() . "') ";
  $sWhere .= " and ed60_c_situacao   = 'MATRICULADO' ";
  $sWhere .= " and ed221_c_origem    = 'S' ";
  $sWhere .= " and ed57_i_escola     = $iEscola ";
  $sWhere .= " and ed10_i_tipoensino = {$sTipoEnsino}";

  $oDaoMatricula = new cl_matricula();
  $sSqlMatricula = $oDaoMatricula->sql_query_alunomatriculado(null, " count(*) ", null, $sWhere);
  $rsMatricula   = db_query($sSqlMatricula);

  if ( !$rsMatricula ) {
    throw new Exception("Erro ao buscar alunos matrículados.\n" . pg_last_error());
  }

  $iMatriculas = 0;
  if (pg_num_rows($rsMatricula) > 0) {
    $iMatriculas = db_utils::fieldsMemory($rsMatricula, 0)->count;
  }
  return $iMatriculas;
}

/**
 * Busca todos alunos matriculados na escola em turmas de Atividades Complementares
 * @param  integer $iEscola   código da escola
 * @param  DBDate  $oDtInicio período da matricula
 * @param  DBDate  $oDtFim    período da matricula
 * @return integer            Número de alunos matriculados
 */
function buscaAlunosMatriculadosAtivComplementar($iEscola, $oDtInicio, $oDtFim) {

  $sWhere  = "     ed268_i_escola = {$iEscola} ";
  $sWhere .= " and ed268_i_tipoatend = 4 ";
  $sWhere .= " and ed52_i_ano = " . $oDtInicio->getAno();
  $sWhere .= " and ed269_d_data <= '" . $oDtFim->getDate() . "' ";
  $oDaoAee = new cl_turmaacmatricula();
  $rsAee   = db_query( $oDaoAee->sql_query_turma(null, "count(*)", null, $sWhere) );

  return db_utils::fieldsMemory($rsAee, 0)->count;
}

function buscaAlunosMatriculadosMaisEducacao($iEscola, $oDtInicio, $oDtFim) {

  $sWhere  = " ed60_d_datamatricula <= '" . $oDtFim->getDate() . "' ";
  $sWhere .= " and extract(year FROM ed60_d_datamatricula) = " . $oDtFim->getAno();
  $sWhere .= " and ( ed60_d_datasaida is null or " ;
  $sWhere .= "       ed60_d_datasaida not between '" . $oDtInicio->getDate() . "' and '" . $oDtFim->getDate() . "') ";
  $sWhere .= " and ed60_c_situacao   = 'MATRICULADO' ";
  $sWhere .= " and ed221_c_origem    = 'S' ";
  $sWhere .= " and ed57_i_escola     = $iEscola ";
  $sWhere .= " and ed57_censoprogramamaiseducacao is true ";

  $oDaoMatricula = new cl_matricula();
  $sSqlMatricula = $oDaoMatricula->sql_query_alunomatriculado(null, " count(*)", null, $sWhere);

  $sWhereAc  = "     ed268_programamaiseducacao = 1 ";
  $sWhereAc .= " and ed269_d_data <= '" . $oDtFim->getDate() . "' ";
  $sWhereAc .= " and extract(year FROM ed269_d_data) = " . $oDtFim->getAno() . " ";
  $sWhereAc .= " and ed268_i_escola = {$iEscola} ";

  $oDaoTurmaAcMatricula   = new cl_turmaacmatricula();
  $sSqlAlunosTurmaAC      = $oDaoTurmaAcMatricula->sql_query_turma(null, "count(*) ", null, $sWhereAc);
  $sSqlAlunosMaisEducacao = "select sum(count) as count from ( {$sSqlMatricula} union all {$sSqlAlunosTurmaAC} ) as x ";
  $rsAlunosMaisEducacao   = db_query($sSqlAlunosMaisEducacao);

  if ( !$rsAlunosMaisEducacao ) {
    throw new Exception("Erro ao buscar alunos matrículados.\n" . pg_last_error());
  }

  $iAlunosMaisEducacao = 0;
  if (pg_num_rows($rsAlunosMaisEducacao) > 0) {
    $iAlunosMaisEducacao = db_utils::fieldsMemory($rsAlunosMaisEducacao, 0)->count;
  }
  return $iAlunosMaisEducacao;
}

/**
 * Busca todas escolas da rede que tiveram alunos matriculados no período informado
 * @return array
 * @throws Exception
 */
function buscaEscolas() {

  $sCampos = " ed18_i_codigo as codigo, trim(ed18_c_nome) as escola, trim(ed18_c_abrev) as escola_abrev ";
  $sWhere  = "ed18_i_funcionamento = 1";

  $oDaoEscola = new cl_escola();
  $rsEscolas  = db_query( $oDaoEscola->sql_query_file( null, $sCampos, 'escola', $sWhere ) );

  if ( !$rsEscolas ) {
    throw new Exception("Falha ao buscar Escolas.\n" . pg_last_error());
  }
  if (pg_num_rows($rsEscolas) == 0) {
    throw new Exception("Não há escolas com alunos matriculados no período informado.");
  }

  $aEscolas = array();
  $iEscolas = pg_num_rows($rsEscolas);

  for ($i = 0; $i < $iEscolas; $i++) {

    $oEscola    = db_utils::fieldsMemory($rsEscolas, $i);
    $aEscolas[] = $oEscola;
  }

  return $aEscolas;
}

function quantificarAlunosMatriculadoPorSexo( $iEscola, $oDtInicio, $oDtFim) {

  $sWhere  = " ed60_d_datamatricula <= '" . $oDtFim->getDate() . "' ";
  $sWhere .= " and extract(year FROM ed60_d_datamatricula) = " . $oDtFim->getAno();
  $sWhere .= " and ( ed60_d_datasaida is null or " ;
  $sWhere .= "       ed60_d_datasaida not between '" . $oDtInicio->getDate() . "' and '" . $oDtFim->getDate() . "') ";
  $sWhere .= " and ed60_c_situacao = 'MATRICULADO' ";
  $sWhere .= " and ed221_c_origem  = 'S' ";
  $sWhere .= " and ed57_i_escola   = {$iEscola} ";

  $sGroupBy = " group by ed47_v_sexo, turnoreferente ";

  $sCampos  = " ed47_v_sexo as sexo, ";
  $sCampos .= " case  ";
  $sCampos .= "   when (select count(*) from matriculaturnoreferente where ed337_matricula = ed60_i_codigo) > 1 ";
  $sCampos .= "     then 4 ";
  $sCampos .= "   else (select ed336_turnoreferente from matriculaturnoreferente ";
  $sCampos .= "          inner join turmaturnoreferente on ed336_codigo = ed337_turmaturnoreferente ";
  $sCampos .= "          where ed337_matricula = ed60_i_codigo and ed336_turma = ed57_i_codigo) ";
  $sCampos .= " end as turnoreferente, ";
  $sCampos .= " count(ed47_v_sexo) as qtd_alunos ";

  $oDaoMatricula = new cl_matricula();
  $sSqlMatricula = $oDaoMatricula->sql_query_bolsafamilia(null, " $sCampos ", null, $sWhere . $sGroupBy);
  $rsMatricula   = db_query($sSqlMatricula);

  if ( !$rsMatricula ) {
    throw new Exception("Erro ao buscar alunos por sexo.\n" . pg_last_error());
  }

  return db_utils::getCollectionByRecord( $rsMatricula );

}

/**
 * Quantifica quantos aluno estão na situação solicitada por parâmetro
 * @param  integer $iEscola   codigo da escola
 * @param  DBDate  $oDtInicio
 * @param  DBDate  $oDtFim
 * @param  string  $sSituacao situacao que deve ser buscado o aluno
 * @return integer            numero de alunos que se encaixam na recebida por parâmetro situação
 */
function quantificarAlunosPorSituacao($iEscola, $oDtInicio, $oDtFim, $sSituacao) {

  $sWhere  = " ed60_d_datamatricula <= '" . $oDtFim->getDate() . "' ";
  $sWhere .= " and extract(year FROM ed60_d_datamatricula) = " . $oDtFim->getAno();
  $sWhere .= " and ( ed60_d_datasaida is null or " ;
  $sWhere .= "       ed60_d_datasaida between '" . $oDtInicio->getDate() . "' and '" . $oDtFim->getDate() . "') ";
  $sWhere .= " and ed60_c_situacao {$sSituacao} ";
  $sWhere .= " and ed221_c_origem  = 'S' ";
  $sWhere .= " and ed57_i_escola   = {$iEscola} ";

  $oDaoMatricula = new cl_matricula();
  $sSqlMatricula = $oDaoMatricula->sql_query_bolsafamilia(null, " count(*) ", null, $sWhere);
  $rsMatricula   = db_query($sSqlMatricula);

  if ( !$rsMatricula ) {
    throw new Exception("Erro ao buscar alunos por sexo.\n" . pg_last_error());
  }

  $iAlunos = 0;
  if (pg_num_rows($rsMatricula) > 0) {
    $iAlunos = db_utils::fieldsMemory($rsMatricula, 0)->count;
  }
  return $iAlunos;
}


function quantificarTurmasTurno($iEscola, $oDtInicio, $oDtFim) {

  $sDataOverlaps = "('" . $oDtInicio->getDate() . "', '" . $oDtFim->getDate() . "')";

  $sSql  = " select count(*) as turmas, 'regular' as tipo, ed57_censoprogramamaiseducacao as mais_educacao, ";
  $sSql .= "        case  ";
  $sSql .= "          when (select count(ed336_turnoreferente) from turmaturnoreferente where ed336_turma = ed57_i_codigo) > 1 ";
  $sSql .= "            then 4 ";
  $sSql .= "          else (select ed336_turnoreferente from turmaturnoreferente where ed336_turma = ed57_i_codigo) ";
  $sSql .= "        end as turnoreferente ";
  $sSql .= "   from turma ";
  $sSql .= "  inner join calendario       on ed52_i_codigo = ed57_i_calendario ";
  $sSql .= "  inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
  $sSql .= "                             and ed38_i_escola = ed57_i_escola ";
  $sSql .= "  where  (ed52_d_inicio, ed52_d_fim) OVERLAPS {$sDataOverlaps} ";
  $sSql .= "   and ed57_i_escola = {$iEscola} ";
  $sSql .= "   and ed57_i_tipoturma in (1, 2, 3, 7) ";
  $sSql .= " group by tipo, mais_educacao, turnoreferente ";
  $sSql .= " union all ";
  $sSql .= " select  count(*) as turmas,  ";
  $sSql .= "         case  ";
  $sSql .= "           when ed268_i_tipoatend = 4  ";
  $sSql .= "             then 'complementar'  ";
  $sSql .= "           else 'aee'  ";
  $sSql .= "         end as tipo, ";
  $sSql .= "         case  ";
  $sSql .= "           when ed268_programamaiseducacao = 1 ";
  $sSql .= "             then true ";
  $sSql .= "           else false ";
  $sSql .= "         end  as mais_educacao, ";
  $sSql .= "         case  ";
  $sSql .= "          when (select count(*) from turnoreferente where ed231_i_turno = ed268_i_turno) > 1 ";
  $sSql .= "            then 4 ";
  $sSql .= "          else (select ed231_i_referencia from turnoreferente where ed231_i_turno = ed268_i_turno) ";
  $sSql .= "        end as turnoreferente ";
  $sSql .= "   from turmaac ";
  $sSql .= "  inner join calendario on ed52_i_codigo           = ed268_i_calendario ";
  $sSql .= "  inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
  $sSql .= "                             and ed38_i_escola     = ed268_i_escola ";
  $sSql .= "  where (ed52_d_inicio, ed52_d_fim) OVERLAPS  {$sDataOverlaps} ";
  $sSql .= "    and ed268_i_escola = {$iEscola} ";
  $sSql .= "  group by tipo, mais_educacao, turnoreferente ";

  $rsTurmas = db_query($sSql);
  if ( !$rsTurmas ) {
    throw new Exception("Erro ao buscar quantidade de turmas. Quadro 4.\n" . pg_last_error());
  }
  return db_utils::getCollectionByRecord($rsTurmas);
}


/**
 * Imprime cabeçalho do terceiro quadro
 * @param  FpdfMultiCellBorder $oPdf                 Instancia de FPDF
 * @param  StdClass            $oConfig              Configuração do relatorio
 */
function imprimeTerceiroCabecalho($oPdf, $oConfig) {

  addPage($oPdf, $oConfig, false);
  $iAlturaLinha = 5;
  $iXmargim     = $oPdf->lMargin + $oConfig->iXEscola;
  $iYInicial    = $oPdf->GetY();

  $iXColunaQtdAlunos       = 120;
  $iXColunaQtdMovimentacao = 80;
  $iXColunaPadrao          = 10;
  $iXDobroColuna           = 20;

  $oPdf->Rect(8, $oPdf->GetY(), $oConfig->iXMaxima, $oConfig->iAlturaCabecalho);
  $oPdf->ln();
  $oPdf->SetFont('arial', 'B', 9);
  $oPdf->MultiCell($oConfig->iXEscola, $iAlturaLinha, "Unidade\nEscolar",0, 'C');

  $oPdf->SetY($iYInicial);
  $oPdf->SetX($iXmargim);
  $oPdf->SetFont('arial', 'B', 9);
  $oPdf->Cell($iXColunaQtdAlunos, $iAlturaLinha, "QUANTIDADE DE ALUNOS", 1, 0, 'C' );

  $oPdf->SetY($oPdf->GetY() + $iAlturaLinha);
  $oPdf->SetX($iXmargim);
  $iXAuxiliar = $oPdf->GetX();

  $oPdf->SetFont('arial', '', 8);
  $iAlturaColunaTurno = $oConfig->iAlturaCabecalho - ($iAlturaLinha * 2);
  $iAlturaColunaTotal = $oConfig->iAlturaCabecalho - $iAlturaLinha;
  $oPdf->vCell($iXDobroColuna,  $iAlturaColunaTurno, "Manhã",    1, 0, 'C', 0);
  $oPdf->vCell($iXColunaPadrao, $iAlturaColunaTotal, "Total",    1, 0, 'C', 1);
  $oPdf->vCell($iXDobroColuna,  $iAlturaColunaTurno, "Tarde",    1, 0, 'C', 0);
  $oPdf->vCell($iXColunaPadrao, $iAlturaColunaTotal, "Total",    1, 0, 'C', 1);
  $oPdf->vCell($iXDobroColuna,  $iAlturaColunaTurno, "Noite",    1, 0, 'C', 0);
  $oPdf->vCell($iXColunaPadrao, $iAlturaColunaTotal, "Total",    1, 0, 'C', 1);
  $oPdf->vCell($iXDobroColuna,  $iAlturaColunaTurno, "Integral", 1, 0, 'C', 0);
  $oPdf->vCell($iXColunaPadrao, $iAlturaColunaTotal, "Total",    1, 0, 'C', 1);

  $iAlturaImprimeColunaSexo = $iAlturaColunaTurno + $oPdf->GetY();
  $iNovaMargim = $iXmargim;
  $oPdf->SetFont('arial', 'B', 8);
  for ($i = 1; $i <= 4; $i++ ) {

    $oPdf->SetY($iAlturaImprimeColunaSexo);
    $oPdf->SetX($iNovaMargim);
    $oPdf->Cell($iXColunaPadrao, $iAlturaLinha, "M", 1, 0, 'C' );
    $oPdf->Cell($iXColunaPadrao, $iAlturaLinha, "F", 1, 0, 'C' );
    $iNovaMargim += 30;
  }
  $iXAuxiliar += $iXColunaQtdAlunos;
  $oPdf->SetY($iYInicial);
  $oPdf->SetX($iXAuxiliar);
  $oPdf->vCell($iXColunaPadrao, $oConfig->iAlturaCabecalho, "Total",    1, 0, 'C', 1);
  $oPdf->SetY($iYInicial);
  $iXAuxiliar += $iXColunaPadrao;
  $oPdf->SetX( $iXAuxiliar );
  $iXAuxiliar = $oPdf->GetX();

  $oPdf->SetFont('arial', 'B', 9);
  $oPdf->Cell($iXColunaQtdMovimentacao, $iAlturaLinha, "QUANTIDADE DE MOVIMENTAÇÕES", 1, 0, 'C' );
  $oPdf->vCell(21, $oConfig->iAlturaCabecalho, "Total",    1, 0, 'C', 1);

  $oPdf->SetFont('arial', '', 6);
  $oPdf->SetY($iYInicial + $iAlturaLinha);
  $oPdf->SetX( $iXAuxiliar );
  $oPdf->vCell($iXDobroColuna,  $iAlturaColunaTotal, "TRANSFERIDO\n FORA", 1, 0, 'C', 0);
  $oPdf->vCell($iXDobroColuna,  $iAlturaColunaTotal, "TRANSFERIDO\n REDE", 1, 0, 'C', 0);
  $oPdf->vCell($iXDobroColuna,  $iAlturaColunaTotal, "EVADIDO", 1, 0, 'C', 0);
  $oPdf->vCell($iXDobroColuna,  $iAlturaColunaTotal, "OUTROS", 1, 0, 'C', 0);

  $oPdf->SetY($iYInicial + $oConfig->iAlturaCabecalho);
}

function imprimeQuartoCabecalho($oPdf, $oConfig) {

  addPage($oPdf, $oConfig, false);
  $iAlturaLinha = 4;
  $iXmargim     = $oPdf->lMargin + $oConfig->iXEscola;
  $iYInicial    = $oPdf->GetY();

  $iXColunaQuantidades             = 50;
  $iXColunaQuantidadesMaisEducacao = 54;
  $iXColunaPadrao                  = 10;
  $iXDobroColuna                   = 20;
  $iXColunaTotalTurma              = 15;

  $oPdf->Rect(8, $oPdf->GetY(), $oConfig->iXMaxima, $oConfig->iAlturaCabecalho);
  $oPdf->ln();
  $oPdf->SetFont('arial', 'B', 9);
  $oPdf->MultiCell($oConfig->iXEscola, 5, "Unidade\nEscolar",0, 'C');

  $oPdf->SetY($iYInicial);
  $oPdf->SetX($iXmargim);
  $iXAuxiliar = $oPdf->GetX();
  $oPdf->SetFont('arial', 'B', 7);

  $iAlturaBordaBottom = $iYInicial + ($iAlturaLinha * 2);
  $oPdf->MultiCell($iXColunaQuantidades, $iAlturaLinha, "QUANTIDADE DE TURMAS AEE", 0,  'C' );
  $iXAuxiliar += $iXColunaQuantidades;

  $oPdf->Line($iXmargim,   $iYInicial,  $iXmargim, $iAlturaBordaBottom);   // borda vertical
  $oPdf->Line($iXAuxiliar, $iYInicial,  $iXAuxiliar, $iAlturaBordaBottom); // borda vertical

  $oPdf->SetXY($iXAuxiliar, $iYInicial);
  $oPdf->MultiCell($iXColunaQuantidades, $iAlturaLinha, "QUANTIDADE DE ATIVIDADE COMPLEMENTAR", 0,  'C' );
  $iXAuxiliar += $iXColunaQuantidades;
  $oPdf->Line($iXAuxiliar,  $iYInicial,  $iXAuxiliar, $iAlturaBordaBottom); // borda vertical
  $oPdf->SetXY($iXAuxiliar, $iYInicial);
  $oPdf->MultiCell($iXColunaQuantidades, $iAlturaLinha, "QUANTIDADE DE TURMAS DE ESCOLARIZAÇÃO", 0,  'C' );
  $iXAuxiliar += $iXColunaQuantidades;
  $oPdf->Line($iXmargim, $iAlturaBordaBottom, $iXAuxiliar, $iAlturaBordaBottom);  // borda horizontal
  $oPdf->SetXY($iXAuxiliar, $iYInicial);

  $oPdf->vCell($iXColunaTotalTurma, $oConfig->iAlturaCabecalho, "Total de Turmas", 1, 0, 'C', 1);

  $oPdf->MultiCell($iXColunaQuantidadesMaisEducacao, $iAlturaLinha, "QUANTIDADE DE TURMAS PARTICIPANTES DO MAIS EDUCAÇÃO", 1, 'C' );
  $iXAuxiliar += $iXColunaQuantidadesMaisEducacao + $iXColunaTotalTurma;

  $oPdf->SetXY($iXAuxiliar, $iYInicial);
  $oPdf->vCell(12, $oConfig->iAlturaCabecalho, "Total",    1, 0, 'C', 1);

  $oPdf->SetXY($iXmargim, $iAlturaBordaBottom );
  $iXAuxiliar = $oPdf->GetX();
  $oPdf->SetFont('arial', '', 7);
  $iAlturaDados = $oConfig->iAlturaCabecalho - ($iAlturaLinha * 2);
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Manhã",       1, 0, 'C', 0);  // turmas aee
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Tarde",       1, 0, 'C', 0);  // turmas aee
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Noite",       1, 0, 'C', 0);  // turmas aee
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Integral",    1, 0, 'C', 0);  // turmas aee
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Total",       1, 0, 'C', 1);  // turmas aee
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Manhã",       1, 0, 'C', 0);  // turmas complementar
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Tarde",       1, 0, 'C', 0);  // turmas complementar
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Noite",       1, 0, 'C', 0);  // turmas complementar
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Integral",    1, 0, 'C', 0);  // turmas complementar
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Total",    1, 0, 'C', 1);  // turmas complementar
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Manhã",    1, 0, 'C', 0);  // turmas escolarizacao
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Tarde",    1, 0, 'C', 0);  // turmas escolarizacao
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Noite",    1, 0, 'C', 0);  // turmas escolarizacao
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Integral", 1, 0, 'C', 0);  // turmas escolarizacao
  $oPdf->vCell($iXColunaPadrao, $iAlturaDados, "Total",    1, 0, 'C', 1);  // turmas escolarizacao

  $oPdf->SetX($oPdf->GetX() + $iXColunaTotalTurma);
  $oPdf->vCell(13, $iAlturaDados, "Manhã",    1, 0, 'C', 0); // turmas mais educação
  $oPdf->vCell(13, $iAlturaDados, "Tarde",    1, 0, 'C', 0); // turmas mais educação
  $oPdf->vCell(14, $iAlturaDados, "Noite",    1, 0, 'C', 0); // turmas mais educação
  $oPdf->vCell(14, $iAlturaDados, "Integral", 1, 0, 'C', 0); // turmas mais educação


  $oPdf->SetY($iYInicial + $oConfig->iAlturaCabecalho);
}