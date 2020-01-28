<?php

/*
 *      E-cidade Software Publico para Gestao Municipal
 *   Copyright (C) 2014  DBSeller Servicos de Informatica
 *                             www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 * 
 *   Este programa e software livre; voce pode redistribui-lo e/ou
 *   modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versao 2 da
 *   Licenca como (a seu criterio) qualquer versao mais nova.
 * 
 *   Este programa e distribuido na expectativa de ser util, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *   COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *   PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *   detalhes.
 * 
 *   Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *   junto com este programa; se nao, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 * 
 *   Copia da licenca no diretorio licenca/licenca_en.txt
 *                 licenca/licenca_pt.txt
 */

define("MSG_RELATORIOQUADRORESULTADOSFINAIS", "educacao.escola.RelatorioQuadroResultadosFinais.");

/**
 * Description of QuadroResultadosFinais
 *
 * @package educacao
 * @subpackage relatorio
 * @version $Revision: 1.13 $
 * @author andrio.costa <andrio.costa@dbseller.com.br>
 */
class RelatorioQuadroResultadosFinais {

  /**
   * Esse valor sera alterado pelo modelo de relatorio impresso, ou número de alunos matriculados na turma
   * @var int Número de Alunos impressos em uma página
   */
  private $iMaximoAlunosPorPagina = 38;

  /**
   * Número minimo de linhas de alunos por Página
   * @var int
   */
  private $iMinimoAlunosPorPagina = 20;

  /**
   * @var int Númer de discplinas impressos em uma página
   */
  private $iDisciplinasPagina = 9;

  /**
   * @var integer Código do modelo quando selececionado um modelo personalizado
   */
  private $iModelo;

  /**
   * @var array com as turmas e etapas a serem impressas
   */
  private $aTurmaEtapa = array();

  /**
   * Lista dos alunos da turma
   * Este array tem os dados organizados com as quebras de paginas
   * @var array estrutura organizada dos alunos da turma em uma etapa
   */
  private $aAlunosTurmaEtapa = array();

  /**
   * Alunos Aprovados pelo conselho
   * @var array lista dos alunos que foram aprovados pelo conselho de classe e as justificativas
   */
  private $aObservacaoAlunosAprovadoConselho = array();

  /**
   * @var array Disciplinas do cabeçalho organizadas por pagina
   */
  private $aDisciplinasCabecalho = array();

  /**
   * Instancia de FPDF
   * @var FPDF
   */
  private $oPdf = null;

  /**
   * @var int Largura da pagina descontando as margins;
   */
  private $iLarguraPagina = 278;

  /**
   * @var type Altura da pagina descontando as margins;
   */
  private $iAlturaPagina = 190;

  /**
   *  ->nome
   *  ->cabecalho
   *  ->rodape
   * @var StdClass Dados do cabeçalho Personalizado
   */
  private $oDadosCabecalhoPersonalizado = null;
  
  /**
   * Define se é para imprimir assinatura
   * @var bool
   */
  private $lTemAssinatura = false;
  
  /**
   * @var sctring Nome do diretor
   */
  private $sDiretor = null;
  
  /**
   * @var string Nome do secretario
   */
  private $sSecretario = null;
  
  /**
   * @var bool
   */
  private $lExibirTrocaTurma = false;

  /**
   * Controla se imprimi ou não o brasão
   * @var boolean
   */
  private $lExibeBrasao = false;

  public function __construct ($iModelo = null, $lExibeBrasao = false ) {

    $this->iModelo = $iModelo;
    if (!empty($this->iModelo)) {
      $this->lTemAssinatura = true;
    }

    $this->setExibeBrasao( $lExibeBrasao );

    $this->oPdf = new FpdfMultiCellBorder('L');
    $this->oPdf->setExibeBrasao( $this->lExibeBrasao );
    $this->oPdf->exibeHeader(empty($iModelo));

    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetFillColor(235);
    $this->oPdf->SetMargins(10, 10);
    $this->oPdf->SetAutoPageBreak(true, 10);
  }

  /**
   * Busca os dados do modelo cadastradp
   * @throws BusinessException
   */
  private function getDadosCabecalhoPersonalizado () {

    $sCampos    = "ed217_c_nome as nome, ed217_t_cabecalho as cabecalho, ed217_t_rodape as rodape ";
    $oDaoModelo = new cl_edu_relatmodel();
    $sSqlModelo = $oDaoModelo->sql_query_file($this->iModelo, $sCampos);
    $rsModelo   = $oDaoModelo->sql_record($sSqlModelo);

    if (!$rsModelo) {
      throw new BusinessException(_M(MSG_RELATORIOQUADRORESULTADOSFINAIS + "nao_encontrado_dados_modelo"));
    }
    $this->oDadosCabecalhoPersonalizado = db_utils::fieldsMemory($rsModelo, 0);
  }

  /**
   * Calcula e retorna o total de dias letivos da turma
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   * @return int
   */
  private function calculaTotalAulas (Turma $oTurma, Etapa $oEtapa) {

    $iDiasLetivos = 0;
    foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia) {
      $iDiasLetivos += $oRegencia->getTotalDeAulas();
    }

    return $iDiasLetivos;
  }

  /**
   * Verifica qual cabeçalho deve ser impresso, conforme modelo selcionado
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   */
  private function imprimeCabecalho (Turma $oTurma, Etapa $oEtapa) {

    if (!empty($this->iModelo)) {
      $this->cabecalhoPersonalizado($oTurma, $oEtapa);
    } else {
      $this->cabecalhoPadrao($oTurma, $oEtapa);
    }
  }

  /**
   * Define as variáveis globais para o cabeçalho
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   */
  private function cabecalhoPadrao (Turma $oTurma, Etapa $oEtapa) {

    $oDocente = $oTurma->getProfessorConselheiro();
    $sDocente = "";
    if ($oDocente) {
      $sDocente = $oDocente->getNome();
    }

    global $head1;
    global $head2;
    global $head3;
    global $head4;
    global $head5;
    global $head6;
    global $head7;

    $head1 = "QUADRO DE RESULTADOS FINAIS";
    $head2 = "Curso: " . $oTurma->getBaseCurricular()->getCurso()->getNome();
    $head3 = "Calendário: " . $oTurma->getCalendario()->getDescricao();
    $head4 = "Ano: " . $oTurma->getCalendario()->getAnoExecucao();
    $head5 = "C.H. Total: " . $oTurma->getCargaHoraria( $oEtapa );
    $head6 = "Turma: " . $oTurma->getDescricao();
    $head7 = "Regente: {$sDocente}";
    $this->oPdf->AddPage();
  }

  /**
   * Escreve cabeçalho personalizado pelo cliente
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   */
  public function cabecalhoPersonalizado (Turma $oTurma, Etapa $oEtapa) {

    if (empty($oDadosCabecalhoPersonalizado)) {
      $this->getDadosCabecalhoPersonalizado();
    }

    $oEscola     = $oTurma->getEscola();
    $oCurso      = $oTurma->getBaseCurricular()->getCurso();
    $oAtoCriacao = AtoLegalRepository::getAtosLegaisByEscolaCurso($oEscola, $oCurso);

    $sEndereco     = "{$oEscola->getEndereco()}, {$oEscola->getNumeroEndereco()}";
    $sCepMunicipio = "{$oEscola->getCep()} - {$oEscola->getMunicipio()} - {$oEscola->getUf()}";

    $sAtoCriacao = '';

    if( $oAtoCriacao instanceof AtoLegal ) {

      $sAtoCriacao   = "{$oAtoCriacao->getFinalidade()} Nº {$oAtoCriacao->getNumero()} Data: ";
      $sAtoCriacao  .= "{$oAtoCriacao->getDataVigor()->getDate(DBDate::DATA_PTBR)} D.O.: ";
      $sAtoCriacao  .= "{$oAtoCriacao->getDataDePublicacao()->getDate(DBDate::DATA_PTBR)}";
    }


    $iIdentacaoEixoX = 190;

    $this->oPdf->AddPage();
    $iY = $this->oPdf->GetY();
    $this->oPdf->SetFont("arial", "", 7);

    /**
     * Imprime os dados personalidados do cabeçalho
     */
    if ( $this->lExibeBrasao ) {

      $sLogoMunicipio = $oTurma->getEscola()->getLogo();
      $this->oPdf->Image('imagens/files/' . $sLogoMunicipio, 10, 3, 20);
    }

    $this->oPdf->MultiCell(175, 3.5, $this->oDadosCabecalhoPersonalizado->cabecalho, 0, "C");

    /**
     * Imprime o quadro a direita com os dados da escola
     */
    
    $sNomeEscola       = $oEscola->getNome();
    $iCodigoReferencia = $oEscola->getCodigoReferencia();

    if ( $iCodigoReferencia != null ) {
      $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
    }

    $this->oPdf->SetFont("arial", "B", 6);
    $this->oPdf->SetY($iY);
    $this->oPdf->SetX($iIdentacaoEixoX);
    $this->oPdf->Cell(111, 3, $sNomeEscola, 0, 1);
    $this->oPdf->SetX($iIdentacaoEixoX);
    $this->oPdf->Cell(111, 3, $oEscola->getDepartamento()->getInstituicao()->getCgm()->getNome(), 0, 1);
    $this->oPdf->SetX($iIdentacaoEixoX);
    $this->oPdf->Cell(111, 3, $sEndereco, 0, 1);
    $this->oPdf->SetX($iIdentacaoEixoX);
    $this->oPdf->Cell(111, 3, $sCepMunicipio, 0, 1);
    $this->oPdf->SetX($iIdentacaoEixoX);
    $this->oPdf->Cell(111, 3, $sAtoCriacao, 0, 1);

    /**
     * Imprime os dados da turma
     */
    // 1ª linha
    $this->oPdf->Ln(2);
    $this->oPdf->SetFont("arial", "", 7);
    $this->oPdf->Cell(20, 4, "Tipo de Ensino :", 0, 0, "L", 0);
    $this->oPdf->Cell(195, 4, $oCurso->getEnsino()->getNome(), 0, 0, "L", 0);
    $this->oPdf->Cell(10, 4, "Curso :", 0, 0, "L", 0);
    $this->oPdf->Cell(15, 4, $oCurso->getNome(), 0, 1, "L", 0);
    // 2ª linha
    $this->oPdf->Cell(20, 4, "Etapa :", 0, 0, "L", 0);
    $this->oPdf->Cell(95, 4, $oEtapa->getNome(), 0, 0, "L", 0);
    $this->oPdf->Cell(20, 4, "Ano :", 0, 0, "L", 0);
    $this->oPdf->Cell(80, 4, $oTurma->getCalendario()->getAnoExecucao(), 0, 0, "L", 0);
    $this->oPdf->Cell(10, 4, "C.H :", 0, 0, "L", 0);
    $this->oPdf->Cell(20, 4, $oTurma->getCargaHoraria(), 0, 1, "L", 0);
    // 3ª linha
    $this->oPdf->Cell(20, 4, "Turma :", 0, 0, "L", 0);
    $this->oPdf->Cell(95, 4, $oTurma->getDescricao(), 0, 0, "L", 0);
    $this->oPdf->Cell(20, 4, "Dias Letivos :", 0, 0, "L", 0);
    $this->oPdf->Cell(80, 4, $oTurma->getCalendario()->getDiasLetivos(), 0, 0, "L", 0);
    $this->oPdf->Cell(10, 4, "Turno :", 0, 0, "L", 0);
    $this->oPdf->Cell(20, 4, $oTurma->getTurno()->getDescricao(), 0, 1, "L", 0);
  }

  /**
   * Adiciona as turmas selecionadas nos filtros
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   */
  public function addTurmaEtapa (Turma $oTurma, Etapa $oEtapa) {

    if( $oTurma->getCodigo() == null ) {
      throw new BusinessException( _M( MSG_RELATORIOQUADRORESULTADOSFINAIS . "turma_nao_encontrada" ) );
    }

    if( $oEtapa->getCodigo() == null ) {
      throw new BusinessException( _M( MSG_RELATORIOQUADRORESULTADOSFINAIS . "etapa_nao_encontrada" ) );
    }

    $oTurmaEtapa         = new stdClass();
    $oTurmaEtapa->oTurma = $oTurma;
    $oTurmaEtapa->oEtapa = $oEtapa;
    $this->aTurmaEtapa[] = $oTurmaEtapa;
  }

  /**
   * Organiza os alunos da turma
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   * @return array 
   */
  private function organizaEstrutura (Turma $oTurma, Etapa $oEtapa) {

    $this->adicionaParagrafoAprovadoConselho = array();
    
    $sHash = "{$oTurma->getCodigo()}#{$oEtapa->getCodigo()}";

    $iCodigoEnsino  = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
    $iAnoCalendario = $oTurma->getCalendario()->getAnoExecucao();
    $aMatriculas    = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);

    $iPaginaAlunos      = 1;
    $iAlunosAdicionados = 1;

    /**
     * Percorre todas matriculas da turma adicionando no array controlando número de alunos por página
     */
    foreach ($aMatriculas as $oMatricula) {

      if ($iAlunosAdicionados == $this->iMaximoAlunosPorPagina) {

        $iPaginaAlunos ++;
        $iAlunosAdicionados = 1;
      }

      if ( !$this->lExibirTrocaTurma && $oMatricula->getSituacao() == 'TROCA DE TURMA') {
        continue;
      }
      
      $oDadosAluno               = new stdClass();
      $oDadosAluno->iOrdem       = $oMatricula->getNumeroOrdemAluno();
      $oDadosAluno->iCodigoAluno = $oMatricula->getAluno()->getCodigoAluno();
      $oDadosAluno->sNome        = $oMatricula->getAluno()->getNome();
      $oDadosAluno->sSituacao    = $oMatricula->getSituacao();
      
      $oDadosAluno->sResultadoFinal               = "";
      $oDadosAluno->sTermoResultadoFinal          = "";
      $oDadosAluno->sTermoResultadoFinalAbreviado = "";
      
      $oDadosAluno->aAvaliacoes                        = array();
      $oDadosAluno->lTemReclassificacaoBaixaFrequencia = false;
      
      db_inicio_transacao();
      $oGradeAproveitamento = new GradeAproveitamentoAluno($oMatricula);
      $oDiario              = $oGradeAproveitamento->getMatricula()->getDiarioDeClasse();

      if( $oDiario->reclassificadoPorBaixaFrequencia() ) {
        $oDadosAluno->lTemReclassificacaoBaixaFrequencia = true;
      }

      $iPaginaDisciplina     = 1;
      $iDisciplinaAdicionada = 0;

      /**
       * Percorre as disciplinas adicionando no array controlando número de disciplinas por página
       */
      foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia) {

        if ($iDisciplinaAdicionada == $this->iDisciplinasPagina) {

          $iPaginaDisciplina ++;
          $iDisciplinaAdicionada = 0;
        }
        
        $oResultadoFinal   = $oGradeAproveitamento->getResultadoFinalDaRegencia($oRegencia);
        $oAmparoDisciplina = $oGradeAproveitamento->getAmparoDisciplina($oRegencia);
        $oAprovadoConselho = $oResultadoFinal->getFormaAprovacaoConselho();

        $oResultadoDisciplina                   = new stdClass();
        $oResultadoDisciplina->iRegencia        = $oRegencia->getCodigo();
        $oResultadoDisciplina->sDisciplina      = $oRegencia->getDisciplina()->getNomeDisciplina();
        $oResultadoDisciplina->sDisciplinaAbrev = $oRegencia->getDisciplina()->getAbreviatura();
        $oResultadoDisciplina->oFrequencia      = $oGradeAproveitamento->getDadosFrequenciaDaDiscplina($oRegencia);


        /**
         * Caso aluno não esteja matriculado define a situação da matricula no lugar da avaliação
         */
        if ( $oMatricula->getSituacao() != 'MATRICULADO' ) {
          
          $oResultadoDisciplina->sTermoResultadoFinalAbreviado = "";
          $oResultadoDisciplina->nAproveitamentoFinal          = substr($oMatricula->getSituacao(), 0, 5);
          
          $oDadosAluno->aAvaliacoes[$iPaginaDisciplina][] = $oResultadoDisciplina;
          $iDisciplinaAdicionada++;
          continue;
        }
        
        
        $nAproveitamentoFinal = '';
        if ($oResultadoFinal->getValorAprovacao() != '') {
          $nAproveitamentoFinal = ArredondamentoNota::formatar($oResultadoFinal->getValorAprovacao(), $iAnoCalendario);
        }

        if( $oRegencia->getFrequenciaGlobal() == "F" ) {
          $nAproveitamentoFinal = "-";
        }

        $sResultadoFinal = $oResultadoFinal->getResultadoFinal();

        // Caso aluno seja aprovado pelo conselho e seu aproveitamento final seja alterado
        if (   !empty($oAprovadoConselho) 
            && ($oAprovadoConselho->getFormaAprovacao() == AprovacaoConselho::APROVADO_CONSELHO) 
            && ($oAprovadoConselho->getAlterarNotaFinal() == AprovacaoConselho::INFORMAR_E_SUBSTITUIR)) {

          $nAproveitamentoFinal = ArredondamentoNota::formatar($oAprovadoConselho->getAvaliacaoConselho(), $iAnoCalendario);
        }

        /**
         * Caso aluno tenha sido amparado
         */
        if (!empty($oAmparoDisciplina) && $oAmparoDisciplina->isTotal()) {

          $nAproveitamentoFinal = "AMP";
          if ($oAmparoDisciplina->getTipoAmparo() == AmparoDisciplina::AMPARO_CONVENCAO) {
            $nAproveitamentoFinal = $oAmparoDisciplina->getConvencao()->getAbreviatura();
          }
        }

        if (!empty($oAprovadoConselho)) {

          $sResultadoFinal = 'A';
          $this->adicionaParagrafoAprovadoConselho($oMatricula, $oRegencia, $oAprovadoConselho);
        }

        $oResultadoDisciplina->sTermoResultadoFinal          = "";
        $oResultadoDisciplina->sTermoResultadoFinalAbreviado = "";
        $oResultadoDisciplina->nAproveitamentoFinal          = $nAproveitamentoFinal;
        $oResultadoDisciplina->lAprovadoProgressaoParcial    = $oResultadoFinal->aprovadoPorProgressaoParcial($oRegencia);
        $oResultadoDisciplina->sResultadoFinal               = $sResultadoFinal;

        if (in_array($oMatricula->getSituacao(), array('AVANÇADO', 'CLASSIFICADO'))) {
          $oResultadoDisciplina->sResultadoFinal = 'A';
        }

        if (isset($oResultadoDisciplina->sResultadoFinal) && !empty($oResultadoDisciplina->sResultadoFinal)) {

          $aTermosAprovado = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, $oResultadoDisciplina->sResultadoFinal, $iAnoCalendario);

          $oResultadoDisciplina->sTermoResultadoFinal          = $aTermosAprovado[0]->sDescricao;
          $oResultadoDisciplina->sTermoResultadoFinalAbreviado = $aTermosAprovado[0]->sAbreviatura;
        }
        
        $oDadosAluno->aAvaliacoes[$iPaginaDisciplina][] = $oResultadoDisciplina;
        $iDisciplinaAdicionada++;
      }

      $oDadosAluno->sTermoResultadoFinal          = '';
      $oDadosAluno->sTermoResultadoFinalAbreviado = '';
      $oDadosAluno->sResultadoFinal               = '';
      if ($oMatricula->getSituacao() == 'MATRICULADO') {

        $sResultadoFinalAluno = $oGradeAproveitamento->getResultadoFinalAluno();

        if (!empty($sResultadoFinalAluno)) {

          $aTermosAprovado = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, $sResultadoFinalAluno, $iAnoCalendario);

          $oDadosAluno->sTermoResultadoFinal          = $aTermosAprovado[0]->sDescricao;
          $oDadosAluno->sTermoResultadoFinalAbreviado = $aTermosAprovado[0]->sAbreviatura;
          $oDadosAluno->sResultadoFinal               = $sResultadoFinalAluno;
        }
      }

      db_fim_transacao();

      $this->aAlunosTurmaEtapa[$sHash][$iPaginaAlunos][] = $oDadosAluno;
      $iAlunosAdicionados ++;
    }

    RegenciaRepository::removeAll();
    MatriculaRepository::removeAll();
    return $this->aAlunosTurmaEtapa[$sHash];
  }

  /**
   * Percorre as dados organizados para turma e etapa e monta a grade de avaliações
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   */
  private function imprimeGrade (Turma $oTurma, Etapa $oEtapa) {

    $oDadosTurma                                     = new stdClass();
    $oDadosTurma->lProcedimentoControlaPorDisciplina = true;

    if( $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa )->getFormaCalculoFrequencia() == 2 ) {
      $oDadosTurma->lProcedimentoControlaPorDisciplina = false;
    }

    $this->montaCabecalhoDisciplina($oTurma, $oEtapa);

    $sHash = "{$oTurma->getCodigo()}#{$oEtapa->getCodigo()}";

    if (!isset($this->aAlunosTurmaEtapa[$sHash])) {
      $this->organizaEstrutura($oTurma, $oEtapa);
    }

    $iTotalPaginaDisciplina = count($this->aDisciplinasCabecalho);

    for ($iPaginaDisciplina = 1; $iPaginaDisciplina <= $iTotalPaginaDisciplina; $iPaginaDisciplina++) {

      $this->imprimeCabecalhoDisciplina($iPaginaDisciplina);

      $iQuantidadePaginasAluno = count($this->aAlunosTurmaEtapa[$sHash]);
      $iPaginaAluno            = 1;
      
      /**
       * Percorre a estrutura dos alunos imprimindo todas as paginas para a pagina de disciplina atual
       */
      while ($iPaginaAluno <= $iQuantidadePaginasAluno) {

        $this->imprimeAlunosPagina($this->aAlunosTurmaEtapa[$sHash], $iPaginaAluno, $iPaginaDisciplina, $oDadosTurma);
        $this->imprimeLegenda($iPaginaDisciplina);

        if ($iPaginaAluno < $iQuantidadePaginasAluno) {

          $this->imprimeCabecalho($oTurma, $oEtapa);
          $this->imprimeCabecalhoDisciplina($iPaginaDisciplina);
        }

        $iPaginaAluno ++;
      }

      $this->imprimeObservacoes( $iPaginaDisciplina );

      if ($this->lTemAssinatura) {
        $this->imprimeAssinaturas( $oTurma, $oEtapa );
      }

      if ($iPaginaDisciplina < $iTotalPaginaDisciplina) {
        $this->imprimeCabecalho($oTurma, $oEtapa);
      }
    }
    
    unset($this->aAlunosTurmaEtapa[$sHash]);
  }

  /**
   * Manda imprimir os dados do relatório
   */
  public function imprimir () {

    foreach ($this->aTurmaEtapa as $oTurmaEtapa) {

      $this->imprimeCabecalho($oTurmaEtapa->oTurma, $oTurmaEtapa->oEtapa);
      $this->imprimeGrade($oTurmaEtapa->oTurma, $oTurmaEtapa->oEtapa);
    }
    $this->oPdf->Output();
  }

  /**
   * Adiciona as justificativas dos alunos aprovados pelo conselho
   * @param Matricula         $oMatricula
   * @param Regencia          $oRegencia
   * @param AprovacaoConselho $oAprovadoConselho
   */
  private function adicionaParagrafoAprovadoConselho (Matricula $oMatricula, Regencia $oRegencia, AprovacaoConselho $oAprovadoConselho) {

    $aParagrafos = array();
    switch ($oAprovadoConselho->getFormaAprovacao()) {

      case AprovacaoConselho::APROVADO_CONSELHO:

        $oDocumento                = new libdocumento(5013);
        $oDocumento->disciplina    = $oRegencia->getDisciplina()->getNomeDisciplina();
        $oDocumento->etapa         = $oRegencia->getEtapa()->getNome();
        $oDocumento->justificativa = $oAprovadoConselho->getJustificativa();
        $oDocumento->nota          = $oAprovadoConselho->getAvaliacaoConselho();
        $oDocumento->anomatricula  = $oRegencia->getTurma()->getCalendario()->getAnoExecucao();

        $aParagrafos = $oDocumento->getDocParagrafos();

        $sTexto = $aParagrafos[1]->oParag->db02_texto;
        if( trim( $sTexto ) != '' ) {

          $sObservacao = "- {$oMatricula->getAluno()->getNome()}: {$sTexto}";
          $this->aObservacaoAlunosAprovadoConselho[$oRegencia->getDisciplina()->getCodigoDisciplinaGeral()][] = $sObservacao;
        }

        break;

      case AprovacaoConselho::RECLASSIFICACAO_BAIXA_FREQUENCIA:

        $oDocumento             = new libdocumento(5006);
        $oDocumento->nome_aluno = $oMatricula->getAluno()->getNome();
        $oDocumento->ano        = $oRegencia->getTurma()->getCalendario()->getAnoExecucao();
        $oDocumento->nome_etapa = $oRegencia->getEtapa()->getNome();

        $aParagrafos = $oDocumento->getDocParagrafos();

        $sTexto = $aParagrafos[1]->oParag->db02_texto;
        if( trim( $sTexto ) != '' ) {

          $sObservacao = "- {$sTexto}";
          $this->aObservacaoAlunosAprovadoConselho[$oRegencia->getDisciplina()->getCodigoDisciplinaGeral()][] = $sObservacao;
        }

        break;

      case AprovacaoConselho::APROVADO_CONFORME_REGIMENTO_ESCOLAR:

        $sObservacao  = "- {$oMatricula->getAluno()->getNome()}: ";
        $sObservacao .= "Disciplina {$oRegencia->getDisciplina()->getNomeDisciplina()} na etapa";
        $sObservacao .= " {$oRegencia->getEtapa()->getNome()} foi aprovado pelo regimento escolar. ";
        $sObservacao .= "Justificativa: {$oAprovadoConselho->getJustificativa()}";


        $this->aObservacaoAlunosAprovadoConselho[$oRegencia->getDisciplina()->getCodigoDisciplinaGeral()][] = $sObservacao;
        break;
    }
  }

  private function montaCabecalhoDisciplina (Turma $oTurma, Etapa $oEtapa) {

    $iPaginaDisciplina     = 1;
    $iDisciplinaAdicionada = 0;
    
    $this->aDisciplinasCabecalho = array();

    /**
     * Percorre as disciplinas adicionando no array controlando número de disciplinas por página
     */
    foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia) {

      if ($iDisciplinaAdicionada == $this->iDisciplinasPagina) {

        $iPaginaDisciplina ++;
        $iDisciplinaAdicionada = 0;
      }

      $this->aDisciplinasCabecalho[$iPaginaDisciplina][] = $oRegencia->getDisciplina();
      $iDisciplinaAdicionada++;
    }
  }

  /**
   * Escreve o cabeçalho contendo as disciplinas
   * @param type $iPagina
   */
  private function imprimeCabecalhoDisciplina ($iPagina) {
    
    $this->oPdf->setFont("Arial", "B", 7);
    //Primeira linha do cabeçalho
    $this->oPdf->Cell(5, 4, "", 1);
    $this->oPdf->Cell(65, 4, "Disciplinas", 1, 0, "R");

    foreach ($this->aDisciplinasCabecalho[$iPagina] as $iIndex => $oDisciplina) {
      $this->oPdf->Cell(22, 4, $oDisciplina->getAbreviatura(), 1, 0, "C");
    }

    $iColunasImpressas = count($this->aDisciplinasCabecalho[$iPagina]);
    $iColunasEmBranco = $this->iDisciplinasPagina - $iColunasImpressas;

    $this->imprimeColunasEmBranco($iColunasEmBranco, true);

    $this->oPdf->Cell(10, 4, "", 1, 1);

    //Segunda linha do cabeçalho
    $this->oPdf->Cell(5, 4, "Nº", 1);
    $this->oPdf->Cell(65, 4, "Nome do Aluno", 1, 0, "C");

    for ($iContador = 1; $iContador <= $iColunasImpressas; $iContador++) {

      $this->oPdf->Cell(12, 4, "Aprov", 1, 0, "C");
      $this->oPdf->Cell(10, 4, "% Freq", 1, 0, "C");
    }

    $this->imprimeColunasEmBranco($iColunasEmBranco);

    $this->oPdf->Cell(10, 4, "RF", 1, 1, "C");
    $this->oPdf->setFont("Arial", "", 7);
  }

  /**
   * Imprime colunas da grade para cada linha
   * @param type $iColunasEmBranco
   * @param type $lCabecalho
   */
  private function imprimeColunasEmBranco ($iColunasEmBranco, $lCabecalho = false) {

    while ($iColunasEmBranco != 0) {

      if ($lCabecalho) {
        $this->oPdf->Cell(22, 4, "", 1, 0);
      } else {

        $this->oPdf->Cell(12, 4, "", 1, 0);
        $this->oPdf->Cell(10, 4, "", 1, 0);
      }

      $iColunasEmBranco --;
    }
  }

  /**
   * Imprime os Alunos 
   * @param type $aAlunosPagina
   * @param type $iPagina
   */
  private function imprimeAlunosPagina ($aAlunos, $iPaginaAluno, $iPaginaDisciplina, $oDadosTurma) {

    foreach ($aAlunos[$iPaginaAluno] as $oDadosAluno) {

      $this->oPdf->Cell(5, 4, "{$oDadosAluno->iOrdem}", 1);
      $this->oPdf->Cell(65, 4, "{$oDadosAluno->sNome}", 1, 0, "L");

      foreach ($oDadosAluno->aAvaliacoes[$iPaginaDisciplina] as $iIndex => $oAvaliacao) {

        $this->oPdf->Cell(12, 4, "{$oAvaliacao->nAproveitamentoFinal}", 1, 0, "C");

        $sPercentualFrequencia = '';

        if ( $oAvaliacao->oFrequencia->iTotalAulas != 0 ) {
          $sPercentualFrequencia = $oAvaliacao->oFrequencia->nPercentualFrequencia;
        }

        if(    $oAvaliacao->oFrequencia->lReclassificadoBaixaFrequencia
            || ( !$oDadosTurma->lProcedimentoControlaPorDisciplina && $oDadosAluno->lTemReclassificacaoBaixaFrequencia )
          ) {
          $sPercentualFrequencia = '--';
        }

        $this->oPdf->Cell(10, 4, $sPercentualFrequencia, 1, 0, "C");
      }

      $iColunasEmBranco = $this->iDisciplinasPagina - count($oDadosAluno->aAvaliacoes[$iPaginaDisciplina]);
      $this->imprimeColunasEmBranco($iColunasEmBranco);
      $this->oPdf->setFont("Arial", "B", 7);
      $this->oPdf->Cell(10, 4, $oDadosAluno->sTermoResultadoFinalAbreviado, 1, 1, "C");
      $this->oPdf->setFont("Arial", "", 7);
    }

    /**
     * Caso existam observações referentes a aprovação pelo conselho e o número de alunos impressos na página, não atinja
     * o mínimo, diminui o número de linhas em branco, permitindo maior espaço para as observações.
     */
    $iQtdAlunosPagina = count($aAlunos[$iPaginaAluno]);
    if ($iQtdAlunosPagina < $this->iMinimoAlunosPorPagina) {

      $iQuantidadeLinhas = $this->iMinimoAlunosPorPagina;

      if ( count($this->aObservacaoAlunosAprovadoConselho) == 0 ) {
        $iQuantidadeLinhas = 30;
      }

      $this->imprimeLinhasEmBranco($iQuantidadeLinhas - $iQtdAlunosPagina);
    }
  }

  /**
   * Completa a grade com linhas em branco 
   * @param int $iLinhasEmBranco
   */
  private function imprimeLinhasEmBranco ($iLinhasEmBranco) {

    while ($iLinhasEmBranco != 0) {

      $this->oPdf->Cell(5, 4, "", 1);
      $this->oPdf->Cell(65, 4, "", 1, 0, "L");
      $this->imprimeColunasEmBranco(9);
      $this->oPdf->Cell(10, 4, "", 1, 1);

      $iLinhasEmBranco--;
    }
  }

  /**
   * Monta a legenda conforme as disciplinas impressas na página e as imprime
   * @param type $iPagina
   */
  private function imprimeLegenda ($iPagina) {

    $sLegenda = "";

    foreach ($this->aDisciplinasCabecalho[$iPagina] as $iIndex => $oDisciplina) {
      $sLegenda .= "{$oDisciplina->getAbreviatura()} - {$oDisciplina->getNomeDisciplina()} | ";
    }

    $this->oPdf->SetFont("Arial", "B", 7);
    $this->oPdf->MultiCell($this->iLarguraPagina, 4, $sLegenda, 1, "L");
    $this->oPdf->SetFont("Arial", "", 7);
  }

  /**
   * Impresso assinaturas contendo o nome informado do secretário e do diretor quando preenchidos
   * @param  Turma  $oTurma
   * @param  Etapa  $oEtapa
   */
  private function imprimeAssinaturas ( Turma $oTurma, Etapa $oEtapa ) {

    if ( ($this->oPdf->GetY() + 11)  > 190) {

      $this->imprimeCabecalho($oTurma, $oEtapa);
      $this->oPdf->Line(10, $this->oPdf->GetY(), 278, $this->oPdf->GetY());
    }

    $this->oPdf->Ln(8);
    $this->oPdf->Line(10, $this->oPdf->GetY(), 134, $this->oPdf->GetY());

    $this->oPdf->Line(144, $this->oPdf->GetY(), 278, $this->oPdf->GetY());
    $this->oPdf->Ln(1);

    $iPosicaoY = $this->oPdf->getY();

    if ( !empty($this->sSecretario) ) {

      $this->oPdf->SetXY(10, $iPosicaoY );
      $this->oPdf->SetFont("Arial", "B", 7);
      $this->oPdf->Cell(134, 4, $this->sSecretario, 0, 1, "C" );
      $this->oPdf->SetX(10);
      $this->oPdf->Cell(134, 4, "Secretário(a)", 0, 0, "C");
      $this->oPdf->SetFont("Arial", "", 7);
    }

    if ( !empty($this->sDiretor) ) {

      $this->oPdf->SetXY(144, $iPosicaoY);
      $this->oPdf->SetFont("Arial", "B", 7);
      $this->oPdf->Cell(134, 4, $this->sDiretor, 0, 1, "C" );
      $this->oPdf->SetX(144);
      $this->oPdf->Cell(134, 4, "Diretor(a)", 0, 0, "C" );
      $this->oPdf->SetFont("Arial", "", 7);
    }
  }
  
  /**
   * Imprime as observações dos alunos
   */
  private function imprimeObservacoes( $iPaginaDisciplina ) {
          
    if (count($this->aObservacaoAlunosAprovadoConselho) > 0) {

      $this->oPdf->SetFont("Arial", "B", 7);
      $this->oPdf->Cell($this->iLarguraPagina, 4, "OBSERVAÇÕES", 1, 1, "C");
      $this->oPdf->SetFont("Arial", "", 7);
      $aObservacoes = array();

      foreach ($this->aObservacaoAlunosAprovadoConselho as $iDisciplina => $aObservacoesAlunos) {

        foreach( $this->aDisciplinasCabecalho[$iPaginaDisciplina] as $oDisciplina ) {

          if( $iDisciplina == $oDisciplina->getCodigoDisciplinaGeral() ) {

            $aObservacoes[] = implode("\n", $aObservacoesAlunos);
            break;
          }
        }
      }

      $sObservacoes = implode("\n", $aObservacoes);

      $this->oPdf->SetFont("Arial", "", 7);
      $this->oPdf->MultiCell($this->iLarguraPagina, 3, $sObservacoes, 1, "L");
      $this->oPdf->SetFont("Arial", "", 8);
    }
  }
  
  /**
   * Define o diretor
   * @param string $sDiretor
   */
  public function setDiretor($sDiretor) {
    
    $this->sDiretor = $sDiretor;
  }
  
  /**
   * Define o secretario
   * @param string $sSecretario
   */
  public function setSecretario($sSecretario) {
    
    $this->sSecretario = $sSecretario;
  }

  /**
   * Define exibição de alunos que trocaram de turma
   * @param boolean $lExibirTrocaTurma
   */
  public function setExibirTrocaTurma($lExibirTrocaTurma) {
    
    $this->lExibirTrocaTurma = $lExibirTrocaTurma;
  }

  /**
   * Define se o brasão deve ser impresso no relatório
   * @param boolean $lExibeBrasao
   */
  public function setExibeBrasao( $lExibeBrasao ) {
    $this->lExibeBrasao = $lExibeBrasao;
  }
}
