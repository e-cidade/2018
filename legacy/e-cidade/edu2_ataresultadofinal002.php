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

require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("classes/db_edu_parametros_classe.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_libparagrafo.php"));
require_once(modification("model/educacao/avaliacao/iFormaObtencao.interface.php"));
require_once(modification("model/educacao/avaliacao/iElementoAvaliacao.interface.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("std/db_stdClass.php"));

$oJson                                     = new Services_JSON();
$oGet                                      = db_utils::postMemory($_GET);

$aTurmas                                   = $oJson->decode(str_replace("\\","", $oGet->turmas));
$oFiltroRelatorio                          = new stdClass();
$oFiltroRelatorio->iModelo                 = $oGet->modelo;
$oFiltroRelatorio->iOrdenacao              = $oGet->ordenacao;
$oFiltroRelatorio->iFrequencia             = $oGet->frequencia;
$oFiltroRelatorio->iCodigoTipoModelo       = $oGet->tipovar;
$oFiltroRelatorio->iTrocaTurma             = $oGet->trocaTurma;
$oFiltroRelatorio->aDiretor                = array();
$oFiltroRelatorio->aSecretario             = array();
$oFiltroRelatorio->lTemDiretor             = false;
$oFiltroRelatorio->lTemSecretario          = false;
$oFiltroRelatorio->lBrasao                 = false;
$oFiltroRelatorio->lTransferencia          = false;
$oFiltroRelatorio->lAssinatura             = false;
$oFiltroRelatorio->aJustificativas         = array();
$lObservacaoProgressaoParcial              = false;
$oFiltroRelatorio->iTipoModelo             = 1;
$oFiltroRelatorio->mCabecalho              = '';
$oFiltroRelatorio->mRodape                 = '';
$oFiltroRelatorio->mObservacao             = '';
$oFiltroRelatorio->iImprimirRegente        = $oGet->imprimirNomeRegente;
$oFiltroRelatorio->sCabecalho              = '';
$oFiltroRelatorio->iTamanhoColunaResultado = $oFiltroRelatorio->iCodigoTipoModelo == 4 ? 7 : 6;

if ($oGet->transfer == 'yes') {
  $oFiltroRelatorio->lTransferencia = true;
}

if ($oGet->brasao == 'b1') {
  $oFiltroRelatorio->lBrasao = true;
}

if (!empty($oGet->diretor)) {

  $oGet->diretor = db_stdClass::normalizeStringJsonEscapeString($oGet->diretor);
  $oFiltroRelatorio->aDiretor    = explode("|", $oGet->diretor);
  $oFiltroRelatorio->lTemDiretor = true;
}

if (!empty($oGet->secretario)) {

  $oGet->secretario = db_stdClass::normalizeStringJsonEscapeString($oGet->secretario);
  $oFiltroRelatorio->aSecretario    = explode("|", $oGet->secretario);
  $oFiltroRelatorio->lTemSecretario = true;
}

if (!empty($oGet->iRegente)) {
  $oFiltroRelatorio->iRegente = $oGet->iRegente;
}

if (!empty($oGet->iAtividade)) {
  $oFiltroRelatorio->iAtividade = $oGet->iAtividade;
}

if (in_array($oFiltroRelatorio->iModelo, array(1, 2))) {
  require_once(modification("fpdf151/pdfwebseller.php"));
} else if (in_array($oFiltroRelatorio->iModelo, array(3, 4))) {
  require_once(modification("fpdf151/scpdf.php"));
}

if ($oFiltroRelatorio->iModelo == 2 || $oFiltroRelatorio->iModelo == 4) {
  $oFiltroRelatorio->lAssinatura = true;
}

/**
 * Verificamos se o parametro de decimais esta habilitado
 */
$iEscola              = db_getsession("DB_coddepto");
$oDaoEduParametros    = new cl_edu_parametros();
$sCamposEduParametros = "ed233_c_decimais, ed233_c_limitemov";
$sWhereEduParametros  = "ed233_i_escola = {$iEscola}";
$sSqlEduParametros    = $oDaoEduParametros->sql_query_file(null, $sCamposEduParametros, null, $sWhereEduParametros);
$rsEduParametros      = db_query($sSqlEduParametros);

if( is_resource( $rsEduParametros ) && pg_num_rows( $rsEduParametros ) > 0 ) {

  $oDadosEduParametro                    = db_utils::fieldsMemory($rsEduParametros, 0);
  $oFiltroRelatorio->sDecimais           = $oDadosEduParametro->ed233_c_decimais;
  $oFiltroRelatorio->sLimiteMovimentacao = $oDadosEduParametro->ed233_c_limitemov;
}

/**
 * Buscamos os dados de edu_relatmodel para impressao no cabecalho
 */
if (is_numeric($oFiltroRelatorio->iCodigoTipoModelo)) {

  $oDaoRelatModel   = new cl_edu_relatmodel();
  $sCampoRelatModel = "ed217_t_cabecalho, ed217_t_rodape, ed217_t_obs, ed217_i_tipomodelo";
  $sWhereRelatModel = "ed217_i_codigo = {$oFiltroRelatorio->iCodigoTipoModelo}";
  $sSqlRelatModel   = $oDaoRelatModel->sql_query(null, $sCampoRelatModel, null, $sWhereRelatModel);
  $rsRelatModel     = db_query( $sSqlRelatModel );

  if ( is_resource( $rsRelatModel ) && pg_num_rows( $rsRelatModel ) > 0) {

    $oDadosRelatModel              = db_utils::fieldsMemory($rsRelatModel, 0);
    $oFiltroRelatorio->mCabecalho  = $oDadosRelatModel->ed217_t_cabecalho;
    $oFiltroRelatorio->mRodape     = $oDadosRelatModel->ed217_t_rodape;
    $oFiltroRelatorio->mObservacao = $oDadosRelatModel->ed217_t_obs;
    $oFiltroRelatorio->iTipoModelo = $oDadosRelatModel->ed217_i_tipomodelo;
  }
}

/**
 * Case de acordo com o modelo do relatorio
 */
switch($oFiltroRelatorio->iModelo) {

  /**
   * Modelo 1 ou 2
   */
  case ($oFiltroRelatorio->iModelo == 1 || $oFiltroRelatorio->iModelo == 2):

    $oPdf = new PDF();
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->SetAutoPageBreak(false);

    /**
     * Percorre todas as turmas selecionadas
     */
    for ($iContadorTurma = 0; $iContadorTurma < count($aTurmas); $iContadorTurma++) {

      $oFiltroRelatorio->iTotalDisciplinasPorPagina = 7;
      $oFiltroRelatorio->iTotalAlunosPorPagina      = 45;
      $aAlunosComBaixaFrequencia = array();
      $oTurma = TurmaRepository::getTurmaByCodigo($aTurmas[$iContadorTurma]->turma);

      $iCodigoEtapa = $aTurmas[$iContadorTurma]->etapa;
      $oEtapaTurma  = EtapaRepository::getEtapaByCodigo( $iCodigoEtapa );

      /**
       * Dados do cabeçalho da escola
       */
      dadosEscola($oTurma, $aTurmas[$iContadorTurma]->etapa);

      $oDocumento              = new libdocumento( 5012 );
      $oDocumento->dia         = $oTurma->oDadosEscola->iDia;
      $oDocumento->mes_extenso = $oTurma->oDadosEscola->iMes;
      $oDocumento->ano         = $oTurma->oDadosEscola->iAno;

      $oDadosCabecalho = new stdClass();
      $oDadosCabecalho->aParagrafo = $oDocumento->getDocParagrafos();

      /**
       * Seta o valor do cabeçalho
       */
      textoAtoCabecalho( $oFiltroRelatorio, $oTurma );

      /**
       * Montamos o cabeçalho do relatorio
       */
      $head1  = "ATA DE RESULTADOS FINAIS";
      $head2  = $oFiltroRelatorio->sCabecalho;
      $head3  = "Tipo de Ensino: {$oTurma->getBaseCurricular()->getCurso()->getEnsino()->getNome()}";
      $head4  = "Curso: {$oTurma->getBaseCurricular()->getCurso()->getNome()}";
      $head5  = "Etapa: {$oTurma->oDadosEscola->sEtapa}     Ano: {$oTurma->getCalendario()->getAnoExecucao()}     ";
      $head5 .= "C.H. Total: {$oTurma->getCargaHoraria( $oEtapaTurma )}";
      $head6  = "Turma: {$oTurma->getDescricao()}     Dias Letivos: {$oTurma->getCalendario()->getDiasLetivos()}     ";
      $head6 .= "Turno: {$oTurma->getTurno()->getDescricao()}";

      $oPdf->AddPage('P');
      $oPdf->SetFont('arial', 'b', 7);
      /**
       * Adicionamos o corpo do relatorio
       */
      corpoPdf($oPdf, $oTurma, $oFiltroRelatorio, $aAlunosComBaixaFrequencia, $iCodigoEtapa);

      /**
       * Adicionamos o rodape do relatorio
       */
      footerPadrao($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa);

      /**
       * Caso seja selecionado o relatorio com a assinatura do docente
       */
      if ($oFiltroRelatorio->lAssinatura) {
        assinaturaDocente($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa);
      }
      TurmaRepository::removerTurma($oTurma);
      unset($oTurma);
    }
    break;

  /**
   * Modelo 3 ou 4
   */
  case ($oFiltroRelatorio->iModelo == 3 || $oFiltroRelatorio->iModelo == 4):

    $oPdf = new scpdf();
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->SetAutoPageBreak(false);

    /**
     * Percorre todas as turmas selecionadas
     */
    for ($iContadorTurma = 0; $iContadorTurma < count($aTurmas); $iContadorTurma++) {

      $oPdf->AddPage();
      $oFiltroRelatorio->iTotalDisciplinasPorPagina = 7;
      $oFiltroRelatorio->iTotalAlunosPorPagina      = 35;

      if ($oFiltroRelatorio->iCodigoTipoModelo == 3) {
        $oFiltroRelatorio->iTotalAlunosPorPagina = 45;
      }
      $aAlunosComBaixaFrequencia = array();

      $oTurma       = TurmaRepository::getTurmaByCodigo($aTurmas[$iContadorTurma]->turma);
      $iCodigoEtapa = $aTurmas[$iContadorTurma]->etapa;

      /**
       * Dados do cabeçalho da escola
       */
      dadosEscola($oTurma, $iCodigoEtapa);

      /**
       * Adicionamos o corpo do relatorio
       */
      corpoPdf($oPdf, $oTurma, $oFiltroRelatorio, $aAlunosComBaixaFrequencia, $iCodigoEtapa);

      /**
       * Adicionamos o rodape do relatorio
       */
      footerPadrao($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa);

      /**
       * Caso seja selecionado o relatorio com a assinatura do docente
       */
      if ($oFiltroRelatorio->lAssinatura) {
        assinaturaDocente($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa);
      }

      TurmaRepository::removerTurma($oTurma);
      unset($oTurma);
    }
    break;
}

$oPdf->Output();

/**
 * Montamos o corpo do relatorio
 */
function corpoPdf(FPDF $oPdf, Turma $oTurma, $oFiltroRelatorio, $aAlunosComBaixaFrequencia, $iCodigoEtapa) {

  global  $lObservacaoProgressaoParcial;
  /**
   * Array para armazenas os nomes dos alunos
   */
  $sNomeAluno = array();

  /**
   * Array da carga horaria da turma
   */
  $aCargaHoraria  = array();
  $oEtapa         = EtapaRepository::getEtapaByCodigo($iCodigoEtapa);
  $iAnoCalendario = $oTurma->getCalendario()->getAnoExecucao();
  $aDisciplinas   = $oTurma->getDisciplinasPorEtapa($oEtapa);

  /**
   * Parametro de calculo da frequencia
   * 1 - Por disciplina
   * 2 - Por carga horaria total
   */
  $oFiltroRelatorio->lCalculaFrequencia = 1;

  /**
   * Verificamos o tipo de calculo de frequencia da turma
   */

  $oFiltroRelatorio->lCalculaFrequencia = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa)->getFormaCalculoFrequencia();

  /**
   * Tamanho de cada coluna da abreviatura da disciplina
   */
  $oFiltroRelatorio->iTamanhoColunaAbrevDisciplina = 16;
  $oFiltroRelatorio->iAuxiliarTransferido          = 7;

  /**
   * Tamanho total da coluna Disciplina/Carga Horaria
   */
  $oFiltroRelatorio->iTamanhoTotalColunaDisciplina = 65;

  /**
   * Verificamos se foi selecionado algum tipo de frequencia. Caso nao (1), diminuimos o tamanho da coluna
   * de abreviatura da disciplina, e o total por pagina
   */
  if ($oFiltroRelatorio->iFrequencia == 1 || $oFiltroRelatorio->lCalculaFrequencia == 2) {

    $oFiltroRelatorio->iTamanhoColunaAbrevDisciplina = 11;
    $oFiltroRelatorio->iTotalDisciplinasPorPagina    = 10;
    $oFiltroRelatorio->iAuxiliarTransferido          = 10;
  }

  /**
   * Altura padrão das linhas impressas
   */
  $oFiltroRelatorio->iAltura = 4;

  /**
   * Array das disciplinas por paginas
   */
  $aDisciplinasPorPagina = array();
  $iContadorAux          = 0;
  $iPagina               = 0;

  /**
   * Lista dos alunos matriculados na turma
   */
  $aListaDeAlunos        = array();
  $lSequencialDiario     = true;

  /**
   * Organizamos um array com as disciplinas que serao impressas em cada pagina
   */
  foreach ($aDisciplinas as $oDisciplina) {

    if ( !$oDisciplina->isLancadaNoHistorico() ) {
      continue;
    }

    $aDisciplinasPorPagina[$iPagina][$iContadorAux] = $oDisciplina;
    $iTotalContadorAux = 6;

    /**
     * Verificamos se foi selecionado algum tipo de frequencia. Caso nao (1), aumentamos a quantidade do contador
     * auxiliar para validar ate 9
     */
    if ($oFiltroRelatorio->iFrequencia == 1 || $oFiltroRelatorio->lCalculaFrequencia == 2) {
      $iTotalContadorAux = 9;
    }
    if ($iContadorAux >= $iTotalContadorAux) {

      $iPagina ++;
      $iContadorAux = -1;
    }
    $iContadorAux++;
  }

  /**
   * Variavel a ser utilizada no laco para impressao das disciplinas
   */
  $oFiltroRelatorio->iContadorDisciplinasImpressas = $oFiltroRelatorio->iTotalDisciplinasPorPagina;
  $aListaDeAlunos = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);

  /**
   * Switch para o tipo de ordenacao desejado
   */
  switch($oFiltroRelatorio->iOrdenacao) {

    case 2:
    case 3:

      usort($aListaDeAlunos, "ordernarAlunosPorNome");
      break;
  }

  if ($oFiltroRelatorio->iOrdenacao == 2) {
    $lSequencialDiario = false;
  }

  /**
   * Armazenamos o total de alunos matriculados na turma
   */
  $iTotalAlunosMatriculados  = count($aListaDeAlunos);
  $iAlunosImpressos          = 0;
  $lPrimeiroLaco             = true;

  $oPdf->setfont('arial', 'b', 7);

 /**
  * Variavel para o controle da impressão do nº quando a ordenação for sequencial
  */
  $iContadorSequencial = 0;

  foreach ($aDisciplinasPorPagina as $iDisciplina => $aDisciplinasPagina) {

    /**
     * Variáveis para controle do preenchimento da cor da linha
     */
    $iPreenchimento = 0;
    $lPulouAluno    = false;

    /**
     * Imprimimos a quantidade de alunos permitido por pagina
     */
    for ($iContadorAluno = 0; $iContadorAluno < $iTotalAlunosMatriculados; $iContadorAluno++) {

      /**
       * Valida se nos parâmetros globais, foi configurada uma data limite para movimentação. Caso tenha sido, e a data
       * de saída da matrícula é menor que esta data, não apresenta o aluno no relatório
       */
      if (    isset( $oFiltroRelatorio->sLimiteMovimentacao )
           && !empty( $oFiltroRelatorio->sLimiteMovimentacao )
           && $aListaDeAlunos[$iContadorAluno]->getDataEncerramento() != null
         ) {

        $oFiltroRelatorio->sLimiteMovimentacao = $oFiltroRelatorio->sLimiteMovimentacao . "/" . $oTurma->getCalendario()->getAnoExecucao();
        $oDataLimiteMovimentacao               = new DBDate( $oFiltroRelatorio->sLimiteMovimentacao );

        if ( DBDate::calculaIntervaloEntreDatas( $oDataLimiteMovimentacao, $aListaDeAlunos[$iContadorAluno]->getDataEncerramento(), 'd' ) > 0 ) {

          $lPulouAluno = true;
          $iAlunosImpressos++;
          continue;
        }
      }

      $oPdf->setfont('arial', '', 6);
      $sNomeAluno                = trim($aListaDeAlunos[$iContadorAluno]->getAluno()->getNome());
      $iLinhasAluno              = $oPdf->NbLines( $oFiltroRelatorio->iTamanhoTotalColunaDisciplina, $sNomeAluno );
      $oFiltroRelatorio->iAltura = $iLinhasAluno * 4;

      $iPreenchimento = $iContadorAluno;
      if ( $lPulouAluno ) {
        $iPreenchimento++;
      }

      $sBordaAluno = "LR";

      if ( $oFiltroRelatorio->iModelo == 3 || $oFiltroRelatorio->iModelo == 4 ) {

        if ($iAlunosImpressos == $oFiltroRelatorio->iTotalAlunosPorPagina - 1) {
          $sBordaAluno = "LRB";
        }
      }

      $lQuebrouPagina = false;
      $oPdf->SetFillColor(225,225,225);
      $iCorLinha = 0;

      if ($iPreenchimento % 2 == 0) {
        $iCorLinha = 1;
      }

      /**
       * Verificamos se o numero de alunos por pagina foi atingido
       */
      if ($iAlunosImpressos == $oFiltroRelatorio->iTotalAlunosPorPagina ) {

        footerPadrao($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa);
        $oPdf->AddPage();
        $lQuebrouPagina = true;
      }

      if ($iAlunosImpressos >= $iTotalAlunosMatriculados) {

        footerPadrao($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa);

        $oPdf->AddPage();
        $lQuebrouPagina      = true;
        $iContadorSequencial = 0;
        $iAlunosImpressos    = 0;
      }

      if( $oPdf->GetY() > 221 ) {

        footerPadrao($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa);

        $oPdf->AddPage();
        $lQuebrouPagina      = true;
        $iAlunosImpressos    = 0;
      }

      $iAlunosImpressos++;

      /**
       * Verificamos que houve quebra de pagina ou se entrou no laco pela primeira vez
       */
      if ($lQuebrouPagina || $lPrimeiroLaco) {

        if ($oFiltroRelatorio->iModelo == 3 || $oFiltroRelatorio->iModelo == 4) {
          cabecalhoScpf($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa);
        }

        $lPrimeiroLaco = false;
        cabecalhoPadrao($oPdf, $oFiltroRelatorio, $aDisciplinasPagina, $oTurma, $iCodigoEtapa);
      }

      if ($oFiltroRelatorio->iTrocaTurma == 1 && $aListaDeAlunos[$iContadorAluno]->getSituacao() == "TROCA DE TURMA") {
        continue;
      }

      $oPdf->setfont('arial', '', 6);

      if ($lSequencialDiario) {
        $oPdf->Cell(5, $oFiltroRelatorio->iAltura, $aListaDeAlunos[$iContadorAluno]->getNumeroOrdemAluno(), $sBordaAluno, 0, "C", $iCorLinha);
      } else {
        $oPdf->Cell(5, $oFiltroRelatorio->iAltura, ++$iContadorSequencial, $sBordaAluno, 0, "C", $iCorLinha);
      }

      $iPosicaoY = $oPdf->GetY();
      $iPosicaoX = $oPdf->GetX();

      $oPdf->MultiCell( $oFiltroRelatorio->iTamanhoTotalColunaDisciplina, 4, $sNomeAluno, $sBordaAluno, 'L', $iCorLinha );
      $oPdf->SetXY( $iPosicaoX + $oFiltroRelatorio->iTamanhoTotalColunaDisciplina, $iPosicaoY );

      /**
       * Buscamos os dados do resultado final
       */
      $sAproveitamento               = '';
      $sPercentualFrequencia         = '';
      $iNumeroFaltas                 = '';
      $sResultadoFinal               = '';
      $iContadorDisciplinasImpressas = 0;
      $sResultadoGeral               = 'A';

      /**
       * Imprimimos a situacao do aluno na linha, caso ele tenha sido transferido
       */
      if ($aListaDeAlunos[$iContadorAluno]->getSituacao() != "MATRICULADO") {

        $oDtEncerramento = $aListaDeAlunos[$iContadorAluno]->getDataEncerramento();
        $sDtEncerramento = "";
        if (!empty($oDtEncerramento)) {
          $sDtEncerramento = " em " .$oDtEncerramento->convertTo(DBDate::DATA_PTBR);
        }

        $sTransferido = $aListaDeAlunos[$iContadorAluno]->getSituacao() . " {$sDtEncerramento}";
        $iLinha       = ($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina * $oFiltroRelatorio->iAuxiliarTransferido) + 12;
        $oPdf->Cell($iLinha, $oFiltroRelatorio->iAltura, "{$sTransferido}", $sBordaAluno, 1, "L", $iCorLinha);
      } else {

        /**
         * Verifica se o aluno foi aprovado com progressão parcial
         */
        $lAprovadoProgressaoAno = false;
        foreach( $aListaDeAlunos[$iContadorAluno]->getAluno()->getProgressaoParcial() as $oProgressaoParcial ) {

          if(    $oTurma->getCalendario()->getAnoExecucao() == $oProgressaoParcial->getAno()
              && $oProgressaoParcial->getCodigoDiarioFinal() != null
            ) {
            $lAprovadoProgressaoAno = true;
          }
        }

        $iTotalDeAulasDadas      = 0;
        $nTotalDeFaltas          = 0;
        $iTotalDeAulasDadasGeral = 0;
        foreach ($aDisciplinasPagina as $oRegenciaTurma) {

          db_inicio_transacao();

          $oRegencia = $aListaDeAlunos[$iContadorAluno]->getDiarioDeClasse()
                                                       ->getDisciplinasPorRegencia($oRegenciaTurma);

          $sAmparado = '';

          if ($oRegencia->getAmparo() != null && $oRegencia->getAmparo()->isTotal() ) {

            if ($oRegencia->getAmparo()->getCodigoConvencaoAmparo()) {

              $oDaoConvencaoAmparo = new cl_convencaoamp();
              $sSqlConvencaoAmparo = $oDaoConvencaoAmparo->sql_query_file($oRegencia->getAmparo()->getCodigoConvencaoAmparo());
              $rsConvencaoAmparo   = $oDaoConvencaoAmparo->sql_record($sSqlConvencaoAmparo);
              $oConvencaoAmparo    = db_utils::fieldsMemory($rsConvencaoAmparo, 0);
              $sAmparado           = $oConvencaoAmparo->ed250_c_abrev;

              $oFiltroRelatorio->aJustificativas[$oTurma->getCodigo()][] = $oConvencaoAmparo->ed250_c_abrev.' - '.$oConvencaoAmparo->ed250_c_descr;
            }

            if ($oRegencia->getAmparo()->getCodigoJustificativa()) {

              $sAmparado = 'AMP '.$oRegencia->getAmparo()->getCodigoJustificativa();

              $oDaoJustificativa = new cl_justificativa();
              $sSqlJustificativa = $oDaoJustificativa->sql_query_file($oRegencia->getAmparo()->getCodigoJustificativa());
              $rsJustitificativa = $oDaoJustificativa->sql_record($sSqlJustificativa);
              $oDadosJustificativa = db_utils::fieldsMemory($rsJustitificativa, 0);
              $oFiltroRelatorio->aJustificativas[$oTurma->getCodigo()][] = $oRegencia->getAmparo()->getCodigoJustificativa().' - '.$oDadosJustificativa->ed06_c_descr;
            }
          }

          $iNumeroFaltas            = $oRegencia->getTotalFaltas();
          $iTotalDeAulasDadas       = $oRegencia->getTotalDeAulasParaCalculo();
          $iTotalDeAulasDadasGeral += $oRegencia->getTotalDeAulasParaCalculo();
          if ($oFiltroRelatorio->lCalculaFrequencia == 2) {
            $nTotalDeFaltas += $iNumeroFaltas;
          }
          db_fim_transacao();

          $iCodigoEnsino   = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
          $oResultadoFinal = $oRegencia->getResultadoFinal();

          /**
           * Valor do resultado de aprovacao
           */
          $nValorAproveitamento = $oResultadoFinal->getValorAprovacao();

          if(    $oResultadoFinal->getFormaAprovacaoConselho() instanceof AprovacaoConselho
              && $oResultadoFinal->getFormaAprovacaoConselho()->getFormaAprovacao() == 1
              && $oResultadoFinal->getFormaAprovacaoConselho()->getAlterarNotaFinal() == 2
            ) {
            $nValorAproveitamento = $oResultadoFinal->getFormaAprovacaoConselho()->getAvaliacaoConselho();
          }

          /**
           * Se for parecer devemos utilizar o resultado da aprovacao do aluno
           */
          $oFormaAvaliacao = $oResultadoFinal->getResultadoAvaliacao()->getFormaDeAvaliacao();
          if (!empty($oFormaAvaliacao) && $oFormaAvaliacao->getTipo() == "PARECER") {

            $nValorAproveitamento = $oResultadoFinal->getResultadoAprovacao();
            if (!empty($iCodigoEnsino) && ($nValorAproveitamento == 'A' || $nValorAproveitamento == 'R')) {

              $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, $nValorAproveitamento, $iAnoCalendario);
              if (isset($aDadosTermo[0])) {
                $nValorAproveitamento = $aDadosTermo[0]->sAbreviatura;
              }
            }
          }

          /**
           * Se for uma nota o valor do aproveitamento devemos aplicar as regras de arrendondamento
           */
          if (is_numeric($nValorAproveitamento)) {
            $nValorAproveitamento  = ArredondamentoNota::formatar($nValorAproveitamento,
                                                                  $oTurma->getCalendario()->getAnoExecucao()
                                                                 );
          }
          $sPercentualFrequencia = $oRegencia->calcularPercentualFrequencia();

          /**
           * Antes estava buscando o RF da disciplina.
           * Devemos buscar o resultado final de todas as avaliações
           */
          $sResultadoAprovacao = $aListaDeAlunos[$iContadorAluno]->getDiarioDeClasse()->getResultadoFinal();

          /**
           * Verificamos se o aluno foi reprovado em alguma disciplina. Caso tenha sido, o Resultado Final é 'R', desde
           * que o mesmo não tenha sido aprovado com progressão parcial
           */
          if ($sResultadoAprovacao == 'R' && !$lAprovadoProgressaoAno) {
            $sResultadoGeral = 'R';
          }

          /**
           * Busca o termo do ensino
           */
          if (!empty($iCodigoEnsino) && ($sResultadoGeral == 'A' || $sResultadoGeral == 'R')) {

            $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, $sResultadoGeral, $iAnoCalendario);
            if (isset($aDadosTermo[0])) {
              $sResultadoGeral = $aDadosTermo[0]->sAbreviatura;
            }
          }

          /**
           * Verifica se houve aprovação pelo conselhor
           * Se sim, identificamos com um número sobrescrito para identificar o tipo na legenda
           */
          $oAprovConselho = $oResultadoFinal->getFormaAprovacaoConselho();

          if (!empty($oAprovConselho)) {

            switch ($oAprovConselho->getFormaAprovacao()) {

              case AprovacaoConselho::APROVADO_CONSELHO :

                $nValorAproveitamento .= ' ¹';
                break;

              case AprovacaoConselho::RECLASSIFICACAO_BAIXA_FREQUENCIA :

                $nValorAproveitamento .= ' ²';
                break;

              case AprovacaoConselho::APROVADO_CONFORME_REGIMENTO_ESCOLAR:

                $nValorAproveitamento .= ' ³';
                break;
            }
          }

          if ($sAmparado != '') {
            $nValorAproveitamento = $sAmparado;
          }

          /**
           * Preenchemos com o aproveitamento para cada disciplina
           */
          if ($oFiltroRelatorio->lCalculaFrequencia == 1 && $oFiltroRelatorio->iFrequencia != 1) {

            $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina - 6, $oFiltroRelatorio->iAltura, "{$nValorAproveitamento}",
                        $sBordaAluno, 0, "C", $iCorLinha
                       );
            $nValorFalta = !empty($iNumeroFaltas) ? $iNumeroFaltas : "" ;

            if ($oFiltroRelatorio->iFrequencia == 2) {
              $nValorFalta =  $sPercentualFrequencia;
            }

            if ($oFiltroRelatorio->iFrequencia == 4) {
              $nValorFalta  = $iTotalDeAulasDadas - $iNumeroFaltas;
            }
            if ($oRegencia->reclassificadoPorBaixaFrequencia()) {
              $nValorFalta = '--';
            }

            if ($oRegencia->getRegencia()->getFrequenciaGlobal()  == 'A') {
              $nValorFalta = '-';
            }

            $oPdf->SetFontSize(5.5);
            $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina - 10, $oFiltroRelatorio->iAltura, "{$nValorFalta}", $sBordaAluno, 0, "C", $iCorLinha);
            $oPdf->SetFontSize(6);
          } else {

            $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina, $oFiltroRelatorio->iAltura,
                        $nValorAproveitamento, $sBordaAluno, 0, "C", $iCorLinha
                       );
          }

          $iContadorDisciplinasImpressas++;
        }

        /**
         * Imprimimos as demais colunas de aproveitamento, em branco
         */
        for ($iContadorColunaBranco = 0; $iContadorColunaBranco < $oFiltroRelatorio->iColunasEmBranco; $iContadorColunaBranco++) {
          $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina, $oFiltroRelatorio->iAltura, "", $sBordaAluno, 0, "C", $iCorLinha);
        }

        if( $lAprovadoProgressaoAno ) {

          $lObservacaoProgressaoParcial  = true;
          $sResultadoGeral              .= '*';
        }

        /**
         * Verificamos a frequencia para alinhamento do resultado final de cada aluno
         */
        if ($oFiltroRelatorio->lCalculaFrequencia == 2 && $oFiltroRelatorio->iFrequencia != 1) {

          $nValorFaltas = $sPercentualFrequencia;
          if ($oFiltroRelatorio->iFrequencia == 3) {
            $nValorFaltas = !empty($nTotalDeFaltas) ? $nTotalDeFaltas : "" ;
          }

          if ($oFiltroRelatorio->iFrequencia == 4) {
            $nValorFaltas  = $iTotalDeAulasDadasGeral - $nTotalDeFaltas;
          }

          if ($aListaDeAlunos[$iContadorAluno]->getDiarioDeClasse()->reclassificadoPorBaixaFrequencia()) {
            $nValorFaltas = '--';
          }

          if ($oRegencia->getRegencia()->getFrequenciaGlobal()  == 'A') {
            $nValorFalta = '-';
          }
          $oPdf->SetFontSize(5.5);
          $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, $oFiltroRelatorio->iAltura, "{$nValorFaltas}", $sBordaAluno, 0, "C", $iCorLinha);
          $oPdf->SetFontSize(6);
          $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, $oFiltroRelatorio->iAltura, "{$sResultadoGeral}", $sBordaAluno, 1, "C", $iCorLinha);
        } else {
          $oPdf->Cell(12, $oFiltroRelatorio->iAltura, "{$sResultadoGeral}", $sBordaAluno, 1, "C", $iCorLinha);
        }
      }
    }

    /**
     * Impressao das "linhas em branco", caso o numero de alunos nao atinja o limite permitido por pagina e o tipo do
     * modelo nao seja 2
     */
    if ($oFiltroRelatorio->iTipoModelo != 2) {

      if (ceil($oPdf->GetY()) < 227) {

        $iLinhasEmBranco = (227 - $oPdf->GetY() ) / 4;

        for($i = 0; $i < $iLinhasEmBranco; $i++) {

          $oPdf->Cell(5, 4, "", "LR", 0, "C", 0);
          $oPdf->Cell($oFiltroRelatorio->iTamanhoTotalColunaDisciplina, 4, "", 0, 0, "LR", 0);

          for ($iContadorDisciplinas = 0; $iContadorDisciplinas < $oFiltroRelatorio->iContadorDisciplinasImpressas; $iContadorDisciplinas++) {

            if ($oFiltroRelatorio->lCalculaFrequencia == 1 && $oFiltroRelatorio->iFrequencia != 1) {

              $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina - 6, 4, "", "LR", 0, "C", 0);

              if ($oFiltroRelatorio->iFrequencia == 2) {
                $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina - 10, 4, "", "LR", 0, "C", 0);
              } else {
                $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina - 10, 4, "", "LR", 0, "C", 0);
              }
            } else {
              $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina, 4, "", "LR", 0, "C", 0);
            }
          }

          /**
           * Imprimimos as linhas em branco caso o total de aluno nao preencha a pagina
           */
          for ($iContadorColunaBranco = 0; $iContadorColunaBranco < $oFiltroRelatorio->iColunasEmBranco; $iContadorColunaBranco++) {
            $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina, 4, "", "LR", 0, "C", 0);
          }

          if ($oFiltroRelatorio->iFrequencia != 1 && $oFiltroRelatorio->lCalculaFrequencia == 2) {

            $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "", "LR", 0, "C", 0);
            $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "", "LR", 1, "C", 0);
          } else {
            $oPdf->Cell(12, 4, "", "LR", 1, "C", 0);
          }
        }
      }
    } else {

      if ($oFiltroRelatorio->iFrequencia != 1) {
        $oPdf->Cell(194, 4, "", "T", 0, "L", 0);
      } else {
        $oPdf->Cell(192, 4, "", "T", 0, "C", 0);
      }
    }
  }
}

/**
 * Montamos o cabecalho para os modelos 3 ou 4
 */
function cabecalhoScpf($oPdf, Turma $oTurma, $oFiltroRelatorio, $iCodigoEtapa) {

  $oPdf->SetFont('arial', 'b', 8);
  /**
   * Buscamos o ato da escola
   */
  $sFinalidadeAto   = '';
  $iNumeroAto       = '';
  $dtVigoraAto      = '';
  $dtPublicadoAto   = '';

  $sDaoCursoAto     = new cl_cursoato();
  $sCamposCursoAto  = " ed05_c_finalidade, ed05_c_numero, ed05_d_vigora, ed05_d_publicado";
  $sWhereCursoAto   = "     ed29_i_codigo in ({$oTurma->getBaseCurricular()->getCurso()->getCodigo()}) ";
  $sWhereCursoAto  .= " AND ed18_i_codigo = {$oTurma->getEscola()->getCodigo()}";
  $sSqlCursoAto     = $sDaoCursoAto->sql_query(null, $sCamposCursoAto, null, $sWhereCursoAto);
  $rsCursoAto       = $sDaoCursoAto->sql_record($sSqlCursoAto);

  /**
   * Verificamos se deve ser impresso o brasao no relatorio
   */
  if ($oFiltroRelatorio->lBrasao) {
    $oPdf->Image("imagens/files/".$oTurma->getEscola()->getLogo(), $oPdf->GetX(), $oPdf->GetY()-6, 15);
  }

  /**
   * Seta o valor do cabeçalho
   */
  textoAtoCabecalho( $oFiltroRelatorio, $oTurma );

  /**
   * Termo fixo que sera apresentado no cabecalho do tipo do modelo 1
   */
  $sTermo = $oFiltroRelatorio->sCabecalho;

  /**
   * Verificamos se a escola possui algum código referência e os adicionamos ao nome da escola
   */
  $sNomeEscola = $oTurma->getEscola()->getNome();

  if ( $oTurma->getEscola()->getCodigoReferencia() != null ) {
    $sNomeEscola = "{$oTurma->getEscola()->getCodigoReferencia()} - $sNomeEscola";
  }

  /**
   * Validamos o tipo do modelo escolhido, para apresentar o cabecalho de acordo
   */
  if ($oFiltroRelatorio->iTipoModelo != 2) {

    /**
     * Montamos o cabeçalho do relatorio
     */
    $oPdf->setXY(24,5);
    $oPdf->multicell(77, 4, $sTermo, 0, "C", 0, 0);
    $oPdf->setXY(115,5);
    $sBairro           = $oTurma->getEscola()->getBairro();

    $sCabecalhoEscola  = "{$sNomeEscola}\n";
    $sCabecalhoEscola .= "Mantenedora: {$oTurma->getEscola()->getDepartamento()->getInstituicao()->getDescricao()}\n";
    $sCabecalhoEscola .= "Endereco: {$oTurma->getEscola()->getEndereco()}";
    $sCabecalhoEscola .= ", {$oTurma->getEscola()->getNumeroEndereco()} - {$sBairro}\n";
    $sCabecalhoEscola .= "CEP: {$oTurma->getEscola()->getCep()}";
    $sCabecalhoEscola .= " - {$oTurma->getEscola()->getMunicipio()} / {$oTurma->getEscola()->getUf()}\n";

    $oPdf->multicell(105, 3, $sCabecalhoEscola, 0, "L", 0, 0);
    $oPdf->setX(90);
  } else {

    $sBairro           = $oTurma->getEscola()->getBairro();
    $sCabecalhoEscola  = "{$oTurma->getEscola()->getDepartamento()->getInstituicao()->getDescricao()}\n";
    $sCabecalhoEscola .= "{$oFiltroRelatorio->mCabecalho}\n";
    $sCabecalhoEscola .= "{$sNomeEscola}\n";
    $sCabecalhoEscola .= "{$oTurma->getEscola()->getEndereco()}, {$oTurma->getEscola()->getNumeroEndereco()} - {$sBairro}\n";

    $oPdf->SetXY(30, 6);
    $oPdf->MultiCell(152, 3, $sCabecalhoEscola, 0, "C");

    $oPdf->SetXY(60, 25);
    $oPdf->SetFont('arial', 'b', 7);
    $oPdf->Cell(95, 2, "ATA DE RESULTADOS FINAIS", 0, 1, "C", 0);

    $oPdf->Ln(4);
    $oPdf->SetX(40);
    $oPdf->SetFont('arial', '', 8);
    $oPdf->MultiCell(140, 3, $sTermo, 0, "C");
    $oPdf->SetFont('arial', 'b', 7);
  }

  if ($sDaoCursoAto->numrows > 0) {

    $oDadosCursoAto = db_utils::fieldsMemory($rsCursoAto, 0);
    $sFinalidadeAto = $oDadosCursoAto->ed05_c_finalidade;
    $iNumeroAto     = $oDadosCursoAto->ed05_c_numero;
    $oDataVigora    = new DBDate($oDadosCursoAto->ed05_d_vigora);
    $dtVigoraAto    = $oDataVigora->getDate(DBDate::DATA_PTBR);
    $oDataPublicado = new DBDate($oDadosCursoAto->ed05_d_publicado);
    $dtPublicadoAto = $oDataPublicado->getDate(DBDate::DATA_PTBR);

    unset($oDataVigora);
    unset($oDataPublicado);

    $oPdf->multicell(110, 2, "", "", "L", 0, 0);
    $oPdf->setX(115);
    $oPdf->SetFont('arial', 'b', 6);
    $oPdf->Cell(95, 4, "{$sFinalidadeAto} Nº: {$iNumeroAto} Data: {$dtVigoraAto} D.O.: {$dtPublicadoAto}", 0, 1, "L", 0);
  } else {
    $oPdf->Ln(5);
  }

  $oEtapaTurma = EtapaRepository::getEtapaByCodigo( $iCodigoEtapa );

  /**
   * Dados da turma
   */
  $oPdf->ln();
  $oPdf->SetFont('arial', 'b', 7);
  $oPdf->Cell(10, 4, "Curso: ", 0, 0, "L", 0);
  $oPdf->Cell(15, 4, $oTurma->getBaseCurricular()->getCurso()->getNome(), 0, 1, "L", 0);
  $oPdf->Cell(10, 4, "Etapa: ", 0, 0, "L", 0);
  $oPdf->Cell(40, 4, $oTurma->oDadosEscola->sEtapa, 0, 0, "L", 0);
  $oPdf->Cell(7, 4, "Ano: ", 0, 0, "L", 0);
  $oPdf->Cell(27, 4, $oTurma->getCalendario()->getAnoExecucao(), 0, 0, "L", 0);
  $oPdf->Cell(20, 4, "Carga Horária: ", 0, 0, "L", 0);
  $oPdf->Cell(15, 4, DBNumber::truncate( $oTurma->getCargaHoraria( $oEtapaTurma ) ), 0, 0, "L", 0);
  $oPdf->Cell(17, 4, "Dias Letivos: ", 0, 0, "L", 0);
  $oPdf->Cell(17, 4, $oTurma->getCalendario()->getDiasLetivos(), 0, 1, "L", 0);
  $oPdf->Cell(10, 4, "Turma: ", 0, 0, "L", 0);
  $oPdf->Cell(40, 4, $oTurma->getDescricao(), 0, 0, "L", 0);
  $oPdf->Cell(10, 4, "Turno: ", 0, 0, "L", 0);
  $oPdf->Cell(24, 4, $oTurma->getTurno()->getDescricao(), 0, 0, "L", 0);

  if ($oFiltroRelatorio->iImprimirRegente == 2) {

    $sRegente = '';

    $oProfessorConselheiro = $oTurma->getProfessorConselheiro();

    if (!empty($oProfessorConselheiro) && $oProfessorConselheiro->getNome() != '') {
      $sRegente = $oTurma->getProfessorConselheiro()->getNome();
    }
    $oPdf->Cell(12,  4, "Regente: ", 0, 0, "L", 0);
    $oPdf->Cell(80, 4, $sRegente,   0, 0, "L", 0);
  }
  $oPdf->ln();
}

/**
 * Montamos o cabecalho com os dados padroes a serem impressos:
 */
function cabecalhoPadrao($oPdf, $oFiltroRelatorio, $aDisciplinasPagina, Turma $oTurma, $iEtapa) {

  /**
   * Total de disciplinas existentes na turma
   */
  $oFiltroRelatorio->iTotalDisciplinas = count($aDisciplinasPagina);

  $oPdf->setfont('arial', 'b', 7);
  $oPdf->Cell(5, 4, "", "LRT", 0, "C", 0);
  $oPdf->Cell($oFiltroRelatorio->iTamanhoTotalColunaDisciplina, 4, "Disciplinas", "LRT", 0, "R", 0);

  /**
   * Caso o numero de disciplinas a serem impressas, seja menor que o limite possivel por pagina,
   * a variavel do contador recebe o total de disciplinas
   */
  if ($oFiltroRelatorio->iTotalDisciplinas < $oFiltroRelatorio->iTotalDisciplinasPorPagina) {
    $oFiltroRelatorio->iContadorDisciplinasImpressas = $oFiltroRelatorio->iTotalDisciplinas;
  }

  $oFiltroRelatorio->iColunasEmBranco = 0;

  if ($oFiltroRelatorio->iContadorDisciplinasImpressas < $oFiltroRelatorio->iTotalDisciplinasPorPagina) {
    $oFiltroRelatorio->iColunasEmBranco = $oFiltroRelatorio->iTotalDisciplinasPorPagina - $oFiltroRelatorio->iContadorDisciplinasImpressas;
  }

  /**
   * Percorremos a impressao de disciplinas por pagina
   */
  for ($iContadorDisciplinas = 0; $iContadorDisciplinas < $oFiltroRelatorio->iContadorDisciplinasImpressas; $iContadorDisciplinas++) {

    $lQuebrouPagina = false;

    if (!array_key_exists ($iContadorDisciplinas, $aDisciplinasPagina)) {
      break;
    }

    if ($iContadorDisciplinas == $oFiltroRelatorio->iTotalDisciplinasPorPagina) {

      footerPadrao($oPdf, $oTurma, $oFiltroRelatorio, $iEtapa);
      $oPdf->AddPage();
      $lQuebrouPagina = true;

    }

    if ($lQuebrouPagina) {

      if ($oFiltroRelatorio->iModelo == 3 || $oFiltroRelatorio->iModelo == 4) {
        cabecalhoScpf($oPdf, $oTurma, $oFiltroRelatorio, $iEtapa);
      }
      cabecalhoPadrao($oPdf, $oFiltroRelatorio, $aDisciplinasPagina, $oTurma, $iEtapa);
    }

    $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina, 4,
                $aDisciplinasPagina[$iContadorDisciplinas]->getDisciplina()->getAbreviatura(), "LRT", 0, "C", 0);

  }

  /**
   * Preenchemos as colunas em branco caso o total de disciplinas nao preencha
   */
  for ($iContadorColunaBranco = 0; $iContadorColunaBranco < $oFiltroRelatorio->iColunasEmBranco; $iContadorColunaBranco++) {
    $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina, 4, "", "LRT", 0, "C", 0);
  }

  /**
   * Verificamos a frequencia para alinhamento da coluna RF
   */
  if ($oFiltroRelatorio->lCalculaFrequencia == 2 && $oFiltroRelatorio->iFrequencia != 1) {

    switch ($oFiltroRelatorio->iFrequencia) {
      case 2:

        $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "%F", "LRT", 0, "C", 0);
        $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "RF", "LTR", 1, "C", 0);
        break;
      case 3:

        $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "FT", "LRT", 0, "C", 0);
        $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "RF", "LTR", 1, "C", 0);
        break;
      case 4:

        $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "FRE", "LRT", 0, "C", 0);
        $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "RF", "LTR", 1, "C", 0);
        break;
    }
  } else {
    $oPdf->Cell(12, 4, "RF", "RT", 1, "C", 0);
  }
  if ($oTurma->getFormaCalculoCargaHoraria() == Turma::CH_PERIODO) {

    $oPdf->Cell(5, 4, "", "LRB", 0, "C", 0);
    $oPdf->Cell($oFiltroRelatorio->iTamanhoTotalColunaDisciplina, 4, "Carga Horária", "LRB", 0, "R", 0);

    /**
     * Percorremos a carga horaria das disciplinas
     */
    for ($iContadorDisciplinas = 0; $iContadorDisciplinas < $oFiltroRelatorio->iContadorDisciplinasImpressas; $iContadorDisciplinas++) {

      if (!array_key_exists ($iContadorDisciplinas, $aDisciplinasPagina)) {
        break;
      }

      $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina, 4,
                  DBNumber::truncate( $aDisciplinasPagina[$iContadorDisciplinas]->getTotalHorasAula() ), "LRB", 0, "C", 0);
    }

    /**
     * Imprimimos em branco as demais colunas referente a linha da carga horaria
     */
    for ($iContadorColunaBranco = 0; $iContadorColunaBranco < $oFiltroRelatorio->iColunasEmBranco; $iContadorColunaBranco++) {
      $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina, 4, "", "LR", 0, "C", 0);
    }

    /**
     * Verificamos a frequencia para alinhamento da coluna RF
     */
    if ($oFiltroRelatorio->iFrequencia != 1 && $oFiltroRelatorio->lCalculaFrequencia == 2) {

      $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "", "LR", 1, "C", 0);
    } else {
      $oPdf->Cell(12, 4, "", "LRB", 1, "C", 0);
    }
  }

  /**
   * Quebramos a linha para impressao da linha com Nº, Nome do Aluno, ...
   */
  $oPdf->Cell(5, 4, "Nº", "LRT", 0, "C", 0);
  $oPdf->Cell($oFiltroRelatorio->iTamanhoTotalColunaDisciplina, 4, "Nome do Aluno", "LRT", 0, "C", 0);

  /**
   * Impressao de "Aprov" para cada disciplina, referente ao aproveitamento do aluno. Caso a turma possua calculo
   * de frequencia, deve ser verificado o filtro selecionado em "Frequencia"
   */
  for ($iContadorDisciplinas = 0; $iContadorDisciplinas < $oFiltroRelatorio->iContadorDisciplinasImpressas; $iContadorDisciplinas++) {

    if (!array_key_exists ($iContadorDisciplinas, $aDisciplinasPagina)) {
      break;
    }

    if ($oFiltroRelatorio->iFrequencia != 1 && $oFiltroRelatorio->lCalculaFrequencia == 1) {

      $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina - 6,  4, "", "LRT", 0, "C", 0);
      switch ($oFiltroRelatorio->iFrequencia) {
        case 2:

          $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina - 10, 4, "% F", "LRT", 0, "C", 0);
          break;
        case 3:

          $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina - 10, 4, "FT", "LRT", 0, "C", 0);
          break;
        case 4:

          $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina - 10, 4, "FRE", "LRT", 0, "C", 0);
          break;
      }
    } else {
      $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina, 4, "", "LRT", 0, "C", 0);
    }
  }

  /**
   * Imprimimos em branco as demais colunas referente a linha "Aprov"
   */
  for ($iContadorColunaBranco = 0; $iContadorColunaBranco < $oFiltroRelatorio->iColunasEmBranco; $iContadorColunaBranco++) {
    $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaAbrevDisciplina, 4, "", "LRT", 0, "C", 0);
  }
  if ($oFiltroRelatorio->lCalculaFrequencia == 2 && $oFiltroRelatorio->iFrequencia != 1) {

    $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "", "LRT", 0, "C", 0);
    $oPdf->Cell($oFiltroRelatorio->iTamanhoColunaResultado, 4, "", "LRT", 1, "C", 0);
  } else {
    $oPdf->Cell(12, 4, "", "LRT", 1, "C", 0);
  }
}

/**
  * Montamos o rodape do relatorio
  */
function footerPadrao(FPDF $oPdf, Turma $oTurma, $oFiltroRelatorio, $iCodigoEtapa) {

  global $lObservacaoProgressaoParcial;
  $iLimiteY = 257;
  $iLimiteX = 192;

  $aTermos = array();
  $iEnsino = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
  $iAno    = $oTurma->getCalendario()->getAnoExecucao();

  $sObservacoesTurma = $oTurma->getObservacao();

  $aObservacoesTurma   = array();
  if (!empty($sObservacoesTurma)) {
    $aObservacoesTurma[] = $oTurma->getObservacao();
  }

  $lAprovadoRegimento          = false;
  $aAlunosReclaBaixaFrequencia = array();

  $oDaoAprovConselho = new cl_aprovconselho();
  $sCamposAprovCons  = "distinct trim(ed47_v_nome) as ed47_v_nome, ed253_aprovconselhotipo, ed253_alterarnotafinal";
  $sCamposAprovCons .= ", ed253_avaliacaoconselho, ed253_t_obs, ed232_c_descr, ed11_c_descr, ed52_i_ano";
  $sWhereAprovCons   = "ed57_i_codigo = {$oTurma->getCodigo()} ";
  $sWhereAprovCons  .= " and ed11_i_codigo = {$iCodigoEtapa}";
  $sSqlAprovCons     = $oDaoAprovConselho->sql_query("", $sCamposAprovCons, "ed47_v_nome", $sWhereAprovCons);
  $rsAprovConselho   = db_query($sSqlAprovCons);

  if ( is_resource( $rsAprovConselho ) && pg_num_rows( $rsAprovConselho ) > 0) {

    $iLinhasAprovCons = pg_num_rows( $rsAprovConselho );

    for ($iContObs = 0; $iContObs < $iLinhasAprovCons; $iContObs++) {

      $oAprovConselho = db_utils::fieldsmemory($rsAprovConselho, $iContObs);
      switch ($oAprovConselho->ed253_aprovconselhotipo) {

        case 1:

          $oDocumento                = new libdocumento( 5013 );
          $oDocumento->disciplina    = $oAprovConselho->ed232_c_descr;
          $oDocumento->etapa         = $oAprovConselho->ed11_c_descr;
          $oDocumento->justificativa = $oAprovConselho->ed253_t_obs;
          $oDocumento->nota          = $oAprovConselho->ed253_avaliacaoconselho;
          $oDocumento->anomatricula  = $oAprovConselho->ed52_i_ano;

          $oDadosObservacao              = new stdClass();
          $oDadosObservacao->aParagrafos = $oDocumento->getDocParagrafos();

          if( trim( $oDadosObservacao->aParagrafos[1]->oParag->db02_texto ) != '' ) {
            $aObservacoesTurma[] = "¹ " . $oAprovConselho->ed47_v_nome . ": " . $oDadosObservacao->aParagrafos[1]->oParag->db02_texto;
          }
          break;

        case 2:

          $aAlunosReclaBaixaFrequencia[] = db_utils::fieldsmemory($rsAprovConselho, $iContObs)->ed47_v_nome;
          break;

        case 3:

          $lAprovadoRegimento = true;
          break;
      }
    }
  }

  if (count($aAlunosReclaBaixaFrequencia) > 0) {

    /**
     * Remove alunos duplicados
     * @var array
     */
    $aAlunosReclaBaixaFrequencia = array_unique($aAlunosReclaBaixaFrequencia);

    $oDocumento               = new libdocumento(5004);
    $oDocumento->lista_alunos = implode(', ', $aAlunosReclaBaixaFrequencia);
    $oDocumento->nome_turma   = $oTurma->getDescricao();
    $aParagrafos              = $oDocumento->getDocParagrafos();

    if (isset($aParagrafos[1])) {
      $aObservacoesTurma[] = "² ".$aParagrafos[1]->oParag->db02_texto;
    }
  }

  if ($lAprovadoRegimento) {
    $aObservacoesTurma[] = "³ " . AprovacaoConselho::getDescricaoTipoAprovacao(AprovacaoConselho::APROVADO_CONFORME_REGIMENTO_ESCOLAR);
  }

  if ($lObservacaoProgressaoParcial) {
    $aObservacoesTurma[] = mb_strtoupper("* Aprovado com Progressão Parcial / Dependência");
  }

  /**
   * Caso a opcao "Mostrar informacoes de troca de turma" for checada, imprimimos nas observacoes, os alunos que
   * trocaram de turma
   */
  if ($oFiltroRelatorio->lTransferencia) {

    $oDaoTransferido     = new cl_alunotransfturma();
    $sCamposTransferido  = "ed69_d_datatransf, ed47_v_nome, cursoedu.ed29_i_ensino as origem, ";
    $sCamposTransferido .= " cursodestino.ed29_i_ensino as destino, ed69_i_matricula";
    $sWhereTransferido   = "ed69_i_turmaorigem = {$oTurma->getCodigo()}";
    $sWhereTransferido  .= " and ed11_i_codigo = {$iCodigoEtapa}";
    $sSqlTransferido     = $oDaoTransferido->sql_query(null, $sCamposTransferido, null, $sWhereTransferido);
    $rsTransferido       = db_query($sSqlTransferido);

    if ( is_resource( $rsTransferido ) && pg_num_rows( $rsTransferido ) > 0) {

      $iTotalTransferido = pg_num_rows( $rsTransferido );

      for ($iContadorTransferido = 0; $iContadorTransferido < $iTotalTransferido; $iContadorTransferido++) {

        $oDadosTransferido   = db_utils::fieldsMemory($rsTransferido, $iContadorTransferido);
        $dtTransferencia     = new DBDate($oDadosTransferido->ed69_d_datatransf);
        $oMatricula          = MatriculaRepository::getMatriculaByCodigo($oDadosTransferido->ed69_i_matricula);
        $sObsTransferencia   = "- Aluno {$oMatricula->getAluno()->getNome()} trocou de turma em ";
        $sObsTransferencia  .= "{$dtTransferencia->getDate(DBDate::DATA_PTBR)}";
        $aObservacoesTurma[] = $sObsTransferencia;
        unset($dtTransferencia);
        unset($oMatricula);
      }
    }
  }

  /**
   * Utilizada para organizar a abreviatura e nome das disciplinas dentro de Convencoes
   */
  $iConvencoesImpressas = 1;
  $sConvencaoEsquerda   = '';
  $sConvencaoDireita    = '';

  /**
   * Percorremos as disciplinas para impressao das informacoes de Convencoes
   */
  foreach ($oTurma->getDisciplinasPorEtapa(EtapaRepository::getEtapaByCodigo($iCodigoEtapa)) as $oRegencia) {

    $iQuebraLinha = 0;
    if ($iConvencoesImpressas % 2 == 0) {

      $sConvencaoDireita .= $oRegencia->getDisciplina()->getAbreviatura();
      $sConvencaoDireita .= " - ".$oRegencia->getDisciplina()->getNomeDisciplina()."\n";
      $iQuebraLinha = 1;

    } else {

      $sConvencaoEsquerda .= $oRegencia->getDisciplina()->getAbreviatura();
      $sConvencaoEsquerda .= " - ".$oRegencia->getDisciplina()->getNomeDisciplina()."\n";
    }

    $iConvencoesImpressas++;
  }

  $sFraseRodape  = "E, para constar, foi lavrada esta Ata.";
  $sDataRodape   = "{$oTurma->getEscola()->getMunicipio()}, ".date("d", db_getsession("DB_datausu"))." de ";
  $sDataRodape  .= db_mes(date("m", db_getsession("DB_datausu")))." de ".date("Y", db_getsession("DB_datausu"));

  $sFuncaoSecretario = 'SECRETÁRIO(A)';
  $sNomeSecretario   = '';

  if ($oFiltroRelatorio->lTemSecretario) {

    $sFuncaoSecretario = $oFiltroRelatorio->aSecretario[0]
                         .(trim($oFiltroRelatorio->aSecretario[2]) != "" ? " ({$oFiltroRelatorio->aSecretario[2]})":"");
    $sNomeSecretario   = $oFiltroRelatorio->aSecretario[1];

  } else if (!$oFiltroRelatorio->lTemSecretario && ($oFiltroRelatorio->iModelo == 1 || $oFiltroRelatorio->iModelo == 2)) {

    $sFuncaoSecretario = '';
    $sNomeSecretario   = '';
  }

  $sFuncaoDiretor = 'DIRETOR(A)';
  $sNomeDiretor   = '';
  if ($oFiltroRelatorio->lTemDiretor) {

    $sFuncaoDiretor = $oFiltroRelatorio->aDiretor[0]
                      .(trim($oFiltroRelatorio->aDiretor[2]) != "" ? " ({$oFiltroRelatorio->aDiretor[2]})":"");
    $sNomeDiretor   = $oFiltroRelatorio->aDiretor[1];
  } else if (!$oFiltroRelatorio->lTemDiretor && ($oFiltroRelatorio->iModelo == 1 || $oFiltroRelatorio->iModelo == 2)) {

    $sFuncaoDiretor = '';
    $sNomeDiretor   = '';
  }

  $sObservacoesTurma = implode("\n", $aObservacoesTurma);
  if ($oFiltroRelatorio->iTipoModelo != 2) {
    $sObservacoesTurma = substr($sObservacoesTurma, 0, 925);
  }


  $aLegenda        = array();
  $aTermos = DBEducacaoTermo::getTermoEncerramentoDoEnsino($iEnsino, $iAno);
  /**
   * Pegando os termos para impressao da legenda
   */
  foreach ($aTermos as $oTermo) {

    if($oTermo->sReferencia != 'P') {
      $aLegenda[] = $oTermo->sAbreviatura. " - " .$oTermo ->sDescricao. "   ";
    }
  }
  $sImprimeLegenda = implode("   ", $aLegenda);

  /**
   * Verificamos se o tipo do modelo escolhido foi 2, alterando a forma de impressao do rodape
   */
  if (isset($oFiltroRelatorio->mObservacao) and ($oFiltroRelatorio->mObservacao != "")) {
    $sObservacoesTurma = "{$oFiltroRelatorio->mObservacao}\n{$sObservacoesTurma}";
  }

  if (isset($oFiltroRelatorio->iRegente) && $oFiltroRelatorio->iRegente != '') {

    $oDocente       = DocenteRepository::getDocenteByCodigoRecursosHumano($oFiltroRelatorio->iRegente);
    $sNomeDocente   = $oDocente->getNome();
    $sFuncaoDocente = '';

    foreach ($oDocente->getAtividades($oTurma->getEscola()) as $oAtividades) {

      if(isset($oFiltroRelatorio->iAtividade) &&
         $oAtividades->getAtividade()->getCodigo() == $oFiltroRelatorio->iAtividade) {
        $sFuncaoDocente = $oAtividades->getAtividade()->getDescricao();
      }
    }
  }

  if ($oFiltroRelatorio->iTipoModelo == 2) {

    $oPdf->setfont('arial', '', 6);
    $oPdf->Ln(4);
    $iPosicaoY = $oPdf->GetY();
    $oPdf->Cell(192, 4, "Componentes Curriculares", 0, 1, "L");

    $oPdf->setfont('arial', '', 5);
    $oPdf->SetX(10);
    $oPdf->Multicell(96, 2.5, $sConvencaoEsquerda, 0, 'L');

    $oPdf->SetY($iPosicaoY + 4);
    $oPdf->SetX(106);
    $oPdf->Multicell(96, 2.5, $sConvencaoDireita, 0, 'L');

    if ($oPdf->GetY() > $oPdf->h - 30) {

      $oPdf->AddPage();
      cabecalhoScpf($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa);
    }

    $oPdf->setfont('arial', '', 5);
    $oPdf->SetXY(10, $oPdf->GetY() + 2.5);
    $oPdf->Cell(100, 3, $sImprimeLegenda, 0, 1, 'L');

    $oPdf->setfont('arial', '', 6);
    $oPdf->Ln();
    $oPdf->Cell(192, 4, "Observações:", 0, 1, "L");

    $iYAlturaProjetada            = $oPdf->GetY();
    $iAlturaOcupadaPelaObservacao = ($oPdf->NbLines(192, $sObservacoesTurma) * 3);

    $iLnhasObservacao = $oPdf->NbLines(192, $sObservacoesTurma);
    // Se doi informado para imprimir Diretor, Secretario ou Assinatura adicidonal
    $iAlturaAssinaturas = 20;

    $iYAlturaProjetada += $iAlturaOcupadaPelaObservacao;
    $iYAlturaProjetada += 10; // Altura da frase do rodapé
    if ( $oFiltroRelatorio->lTemSecretario || $oFiltroRelatorio->lTemDiretor || !empty($oFiltroRelatorio->iRegente) ) {
      $iYAlturaProjetada += $iAlturaAssinaturas;
    }
    if ( $iYAlturaProjetada >= $oPdf->h  ) {

      $oPdf->AddPage();
      cabecalhoScpf($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa);
    }

    $oPdf->setfont('arial', '', 5);
    $oPdf->SetX(11);

    $oPdf->Multicell(192, 3, $sObservacoesTurma, 0, 'J');

    $oPdf->setfont('arial', '', 6);
    $iPosicaoY = $oPdf->GetY();
    $oPdf->SetXY(10, $iPosicaoY + 5);
    $oPdf->Cell(192, 3, $sFraseRodape, 0, 1, "C");
    $oPdf->Cell(192, 4, $sDataRodape, 0, 1, "C", 0);

    $iMultiCellSecretario = 40;
    $iMultiCellDiretor    = 90;

    if (isset($oFiltroRelatorio->iRegente) && $oFiltroRelatorio->iRegente != '') {

      $iMultiCellSecretario = 20;
      $iMultiCellDiretor    = 68;
    }

    $iPosicaoY = $oPdf->GetY() + 5;
    if ($oFiltroRelatorio->lTemSecretario) {

      $sSecretario  = str_repeat("_", 32);
      $sSecretario .= "\n{$sFuncaoSecretario}";
      $sSecretario .= "\n{$sNomeSecretario}";
      $oPdf->Ln();
      $oPdf->SetY($iPosicaoY);
      $oPdf->SetX($iMultiCellSecretario);
      $oPdf->Multicell(70, 4, $sSecretario, 0, 'C');
    }

    if ($oFiltroRelatorio->lTemDiretor) {

      $sDiretor  = str_repeat("_", 32);;
      $sDiretor .= "\n{$sFuncaoDiretor}";
      $sDiretor .= "\n{$sNomeDiretor}";
      $oPdf->SetY($iPosicaoY);
      $oPdf->SetX($iMultiCellDiretor);
      $oPdf->Multicell(76, 4, $sDiretor, 0, 'C');
    }
    if (isset($oFiltroRelatorio->iRegente) && $oFiltroRelatorio->iRegente != '') {

      $sAdicional = str_repeat("_", 32);
      $sAdicional .= "\n{$sFuncaoDocente}";
      $sAdicional .= "\n{$sNomeDocente}";
      $oPdf->SetXY(130, $iPosicaoY);
      $oPdf->MultiCell(50, 4, $sAdicional, 0, 'C');
    }
  } else {

    $oPdf->setfont('arial', '', 6);
    $iPosicaoY = $oPdf->GetY();
    $oPdf->Cell(85, 4, "Observações:", 1, 0, "C", 0);
    $oPdf->Rect(10, $iPosicaoY, 85, 55);

    $iPosicaoYConvencoes  = $oPdf->GetY();
    $iDiferencaTrocaTurma = 0;

    if ($iPosicaoYConvencoes != 227) {
      $iDiferencaTrocaTurma = $iPosicaoYConvencoes - 227;
    }

    $iCelulaConvencoes = 107;
    if ($oFiltroRelatorio->iFrequencia != 1 && $oFiltroRelatorio->lCalculaFrequencia == 1) {
      $iCelulaConvencoes = 109;
    }

    $oPdf->Cell($iCelulaConvencoes, 4, "Convenções:", 1, 0, "C", 0);
    $oPdf->Rect(95, $iPosicaoYConvencoes, $iCelulaConvencoes, 30);

    $iAltura = $oPdf->GetY()-30;
    $oPdf->SetY($iAltura + 30);
    $oPdf->Rect(95, $oPdf->GetY(), $iCelulaConvencoes, 55);

    $oPdf->setfont('arial', '', 4);
    $oPdf->SetY(232 + $iDiferencaTrocaTurma);
    $oPdf->SetX(95);

    $iPosicaoConvencaoY = $oPdf->GetY();
    $oPdf->Multicell(48, 2.5, $sConvencaoEsquerda, 0, 'J');

    $oPdf->SetY(232 + $iDiferencaTrocaTurma);
    $oPdf->SetX(157);
    $oPdf->Multicell(48, 2.5, $sConvencaoDireita, 0, 'J');

    $oPdf->setfont('arial', '', 5);
    $oPdf->SetXY(11, $iPosicaoY + 5);
    $oPdf->Multicell(83, 3, $sObservacoesTurma, 0, 'J');

    $aJustificativas = array();
    if (isset($oFiltroRelatorio->aJustificativas[$oTurma->getCodigo()])) {
      $aJustificativas = array_unique($oFiltroRelatorio->aJustificativas[$oTurma->getCodigo()]);
    }

    foreach ($aJustificativas as $sJustificativas) {
      $oPdf->Multicell(83, 3, $sJustificativas, 0, 'J');
    }


    $oPdf->setfont('arial', '', 4);
    $oPdf->SetXY(95, $iPosicaoConvencaoY + 22);
    $oPdf->Cell(100, 3, $sImprimeLegenda, 0, 1, 'L');

    /**
     * Imprimimos os dados abaixo de Convencoes
     */
    $oPdf->setfont('arial', '', 6);
    $oPdf->SetY(258 + $iDiferencaTrocaTurma);
    $oPdf->SetX(99);
    $oPdf->Cell($oPdf->GetX(), 3, $sFraseRodape, 0, 1, "C", 0);

    $oPdf->SetY($oPdf->GetY() + 1);
    $oPdf->SetX(99);

    $oPdf->Cell($oPdf->GetX(), 3, $sDataRodape, 0, 2, "C", 0);

    $iMultiCellSecretario = 99;
    $iMultiCellDiretor    = 149;

    if (isset($oFiltroRelatorio->iRegente) && $oFiltroRelatorio->iRegente != '') {

      $iMultiCellSecretario = 89;
      $iMultiCellDiretor    = 125;
    }


    $iYMulticell = $oPdf->GetY() + 3;
    $sSecretario  = str_repeat("_", 32);
    $sSecretario .= "\n{$sFuncaoSecretario}";
    $sSecretario .= "\n{$sNomeSecretario}";
    $oPdf->SetFont('Arial', '', 5);
    $oPdf->SetXY($iMultiCellSecretario - 2, $iYMulticell);
    $oPdf->MultiCell(49, 3, $sSecretario, 0, 'C');

    $sDiretor  = str_repeat("_", 32);
    $sDiretor .= "\n{$sFuncaoDiretor}";
    $sDiretor .= "\n{$sNomeDiretor}";
    $oPdf->SetXY($iMultiCellDiretor -2, $iYMulticell);
    $oPdf->MultiCell(49, 3, $sDiretor, 0, 'C');

    if (isset($oFiltroRelatorio->iRegente) && $oFiltroRelatorio->iRegente != '') {

      $sAdicional = str_repeat("_", 30);
      $sAdicional .= "\n{$sFuncaoDocente}";
      $sAdicional .= "\n{$sNomeDocente}";
      $oPdf->SetXY(163, $iYMulticell);
      $oPdf->MultiCell(40, 3, $sAdicional, 0, 'C');
    }
  }
}

/**
 * Buscamos os dados do cabecalho referente a turma
 */
function dadosEscola(Turma $oTurma, $iEtapa) {

  $oTurma->oDadosEscola              = new stdClass();
  $oTurma->oDadosEscola->iTotalHoras = '';

  /**
   * Retornamos a etapa da turma
   */
  foreach ($oTurma->getEtapas() as $oEtapa) {

    if ($iEtapa == $oEtapa->getEtapa()->getCodigo()) {
      $oTurma->oDadosEscola->sEtapa = $oEtapa->getEtapa()->getNome();
    }
  }

  /**
   * Retornamos o dia, mes e ano da data de resultado final do calendário
   */
  $oTurma->oDadosEscola->iDia = $oTurma->getCalendario()->getDataResultadoFinal()->getDia();
  $oTurma->oDadosEscola->iMes = db_mes($oTurma->getCalendario()->getDataResultadoFinal()->getMes());
  $oTurma->oDadosEscola->iAno = $oTurma->getCalendario()->getDataResultadoFinal()->getAno();

  return $oTurma;
}

/**
 * Imprimimos o campo para assinatura dos docentes da turma, caso tenha sido selecionado o relatorio com
 * assinatura
 */
function assinaturaDocente($oPdf, $oTurma, $oFiltroRelatorio, $iCodigoEtapa) {

  $oPdf->AddPage();
  $oPdf->Ln(5);
  $oDaoRegenciaHorario     = new cl_regenciahorario();
  $sCamposRegenciaHorario  = "distinct ed20_i_codigo, case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome";
  $sCamposRegenciaHorario .= " else cgmcgm.z01_nome end as z01_nome, ed59_i_turma";
  $sWhereRegenciaHorario   = "     ed59_i_turma = {$oTurma->getCodigo()} and ed58_ativo is true ";
  $sWhereRegenciaHorario  .= " and ed59_i_serie = {$iCodigoEtapa}";
  $sSqlRegenciaHorario     = $oDaoRegenciaHorario->sql_query(null, $sCamposRegenciaHorario, null, $sWhereRegenciaHorario);
  $rsRegenciaHorario       = db_query($sSqlRegenciaHorario);

  if ( is_resource( $rsRegenciaHorario ) && pg_num_rows( $rsRegenciaHorario ) > 0) {

    $iTotalRegenciaHorario = pg_num_rows( $rsRegenciaHorario );

    for ($iContadorRegencia = 0; $iContadorRegencia < $iTotalRegenciaHorario; $iContadorRegencia++) {

      $oDadosRegenciaHorario = db_utils::fieldsMemory($rsRegenciaHorario, $iContadorRegencia);
      $sProfessor            = "{$oDadosRegenciaHorario->z01_nome} - {$oDadosRegenciaHorario->ed20_i_codigo}";

      $oPdf->cell(10, 8, "", 0, 1, "L", 0);
      $oPdf->line(10, $oPdf->getY(), 70, $oPdf->getY());
      $oPdf->cell(190, 4, "Professor", 0, 1, "L", 0);
      $oPdf->cell(190, 4, $sProfessor, 0, 1, "L", 0);

      $sCamposRegenciaHorarioDesc  = "distinct ed232_c_descr";
      $sWhereRegenciaHorarioDesc   = "     ed58_i_rechumano = {$oDadosRegenciaHorario->ed20_i_codigo}";
      $sWhereRegenciaHorarioDesc  .= " and ed59_i_turma = {$oTurma->getCodigo()}";
      $sWhereRegenciaHorarioDesc  .= " and ed59_i_serie = {$iCodigoEtapa}";
      $sWhereRegenciaHorarioDesc  .= " and ed58_ativo is true";
      $sSqlRegenciaHorarioDesc     = $oDaoRegenciaHorario->sql_query(null,
                                                                     $sCamposRegenciaHorarioDesc,
                                                                     null,
                                                                     $sWhereRegenciaHorarioDesc);
      $rsRegenciaHorarioDesc       = db_query($sSqlRegenciaHorarioDesc);

      if ( is_resource( $rsRegenciaHorarioDesc ) && pg_num_rows( $rsRegenciaHorarioDesc ) > 0) {

         $iTotalRegenciaHorarioDesc = pg_num_rows( $rsRegenciaHorarioDesc );

        for ($iContadorRegenciaDesc = 0; $iContadorRegenciaDesc < $iTotalRegenciaHorarioDesc; $iContadorRegenciaDesc++) {

          $oDadosRegenciaHorarioDesc = db_utils::fieldsMemory($rsRegenciaHorarioDesc, $iContadorRegenciaDesc);
          $oPdf->cell(190, 4, $oDadosRegenciaHorarioDesc->ed232_c_descr, 0, 1, "L", 0);
          $oPdf->cell(220, 2, "", 0, 1, "L", 0);
        }
      }
    }
  } else {
    $oPdf->cell(220, 10, "Nenhum Professor Informado!", 0, 1, "L", 0);
  }
}

function ordernarAlunosPorNome (Matricula $oMatriculaAnterior, Matricula $oProximaMatricula) {

  $sNomeAnterior = TiraAcento($oMatriculaAnterior->getAluno()->getNome());
  $sProximoNome  = TiraAcento($oProximaMatricula->getAluno()->getNome());
  return strnatcasecmp($sNomeAnterior, $sProximoNome);
}

/**
 * Busca o texto referente ao ato, apresentado no cabeçalho, setando os valores padrões
 * @param $oFiltroRelatorio
 * @param $oTurma
 */
function textoAtoCabecalho( $oFiltroRelatorio, $oTurma ) {

  $oDocumento              = new libdocumento( 5012 );
  $oDocumento->dia         = $oTurma->oDadosEscola->iDia;
  $oDocumento->mes_extenso = ucfirst( $oTurma->oDadosEscola->iMes );
  $oDocumento->ano         = $oTurma->oDadosEscola->iAno;

  $oDadosCabecalho              = new stdClass();
  $oDadosCabecalho->aParagrafo  = $oDocumento->getDocParagrafos();
  $oFiltroRelatorio->sCabecalho = $oDadosCabecalho->aParagrafo[1]->oParag->db02_texto;
}