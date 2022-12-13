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

/**
 * Classe para impressão do modelo 3 (Duas páginas por disciplina (Página 1 - Presenças / Página 2 - Avaliações)) do diário de classe
 * @author André Mello   <andre.mello@dbseller.com.br>
 *         Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package    educacao
 * @subpackage relatorio
 */
class RelatorioDiarioClasseCompleto extends RelatorioDiarioClasseBase {

    /**
   * Controla se devemos exibir o sexo.
   * @var boolean
   */
  private $lExibirSexo = false;

  /**
   * Controla se devemos exibir a idade
   * @var boolean
   */
  private $lExibirIdadeSegundaPagina = false;

  /**
   * Controla se devemos exibir o código do aluno
   * @var boolean
   */
  private $lExibirCodigo = false;

  /**
   * Controla se devemos exibir a data de nascimento
   * @var boolean
   */
  private $lExibirNascimento = false;

  /**
   * Controla se deve ser exibido o resultado anterior
   * @var boolean
   */
  private $lExibirResultadoAnterior = false;

  /**
   * Controla se deve ser exibido o parecer
   * @var boolean
   */
  private $lExibirParecer = false;

  /**
   * Controla se deve ser exibido total de faltas na segunda página
   * @var boolean
   */
  private $lExibirTotalFaltas = false;

  /**
   * Controla se deve ser exibido a coluna de faltas abonadas
   * @var boolean
   */
  private $lExibirFaltasAbonadas = false;

  /**
   * Dados do cabeçalho separado por disciplina
   * @var array
   */
  private $aCabecalhoSegundaPagina = array();

  private $iLarguraColunaNota = 15;

  /**
   * Construtor da classe. Recebe Turma, Etapa e AvaliacaoPeriodica como parâmetro, e instância o construtor da classe
   * RelatorioDiarioClasseBase
   * @param Turma              $oTurma
   * @param Etapa              $oEtapa
   * @param AvaliacaoPeriodica $oAvaliacaoPeriodica
   */
  public function __construct( Turma $oTurma, Etapa $oEtapa, AvaliacaoPeriodica $oAvaliacaoPeriodica ) {

    parent::__construct( $oTurma, $oEtapa, $oAvaliacaoPeriodica );
    $this->lExibirLinhaDataPeriodo = false;
    $this->lExibirIdade            = true;
    $this->lExibirFaltas           = false;
    $this->iLarguraPagina          = 280;
  }

  /**
   * Define se devemos exibir a coluna de total faltas
   * @param boolean $lExibirTotalFaltas
   */
  public function setExibirTotalFaltas($lExibirTotalFaltas) {
    $this->lExibirTotalFaltas = $lExibirTotalFaltas;
  }

  /**
   * Retorna se devemos exibir a coluna sexo
   * @param boolean $lExibirSexo
   */
  public function setExibirSexo( $lExibirSexo ) {
    $this->lExibirSexo = $lExibirSexo;
  }

  /**
   * Seta se a coluna da idade deve ser exibida na segunda página
   * @param boolean $lExibirIdadeSegundaPagina
   */
  public function setExibirIdadeSegundaPagina( $lExibirIdadeSegundaPagina ) {
    $this->lExibirIdadeSegundaPagina = $lExibirIdadeSegundaPagina;
  }

  /**
   * Seta se a coluna de faltas abonadas deve ser exibida
   * @param boolean $lExibirFaltasAbonadas
   */
  public function setExibirFaltasAbonadas( $lExibirFaltasAbonadas ) {
    $this->lExibirFaltasAbonadas = $lExibirFaltasAbonadas;
  }

  /**
   * Seta se a coluna do código deve ser exibida
   * @param boolean $lExibirCodigo
   */
  public function setExibirCodigo( $lExibirCodigo ) {
    $this->lExibirCodigo = $lExibirCodigo;
  }

  /**
   * Seta se a coluna da data de nascimento deve ser exibida
   * @param boolean $lExibirNascimento
   */
  public function setExibirNascimento( $lExibirNascimento ) {
    $this->lExibirNascimento = $lExibirNascimento;
  }

  /**
   * Seta se a coluna do resultado anterior deve ser exibida
   * @param boolean $lExibirResultadoAnterior
   */
  public function setExibirResultadoAnterior( $lExibirResultadoAnterior ) {
    $this->lExibirResultadoAnterior = $lExibirResultadoAnterior;
  }

  /**
   * Seta se a coluna do parecer deve ser exibida
   * @param boolean $lExibirParecer
   */
  public function setExibirParecer( $lExibirParecer ) {
    $this->lExibirParecer = $lExibirParecer;
  }

  /**
   * Sobrescreve o método da assinatura, com os dados padrão do modelo
   */
  public function escreverAssinatura() {

    $this->SetFont( 'arial', 'b', 7 );
    $sTexto = "Assinatura do professor: " . str_repeat("_", 60);
    $this->Cell( $this->iLarguraPagina, 5, $sTexto, 1, 1, "L" );

    if ( $this->lPossuiMatriculaPorTurnoReferencia ) {

      $this->SetFont("arial", '', 7);
      $this->Cell( 280, 5, "Legenda: Alunos matriculados somente em um turno ¹ - Manhã | ² - Tarde | ³ - Noite ", 1, 0, "L" );
    }
  }


  /**
   * Verifica quais legendas devem aparecerem, conforme preenchido na tela
   * @return array contendo as legendas que serão impressa no relatório
   */
  private function legendasParaImpressao() {

    $aLegendas['RA'] = $this->lExibirResultadoAnterior;
    $aLegendas['TF'] = $this->lExibirTotalFaltas;
    $aLegendas['I']  = $this->lExibirIdadeSegundaPagina;
    $aLegendas['S']  = $this->lExibirSexo;
    $aLegendas['FA'] = $this->lExibirFaltasAbonadas;

    $sDescricaoLegenda['RA'] = "RA - (A-Aprovado R-Reprovado T-Transferido C-Cancelado E-Evadido F-Falecido)";
    $sDescricaoLegenda['TF'] = "TF - Total de Faltas";
    $sDescricaoLegenda['I']  = "I - Idade";
    $sDescricaoLegenda['S']  = "S - Sexo";
    $sDescricaoLegenda['FA'] = "FA - Faltas Abonadas";

    $aLegendasImprimir = array();

    foreach ($aLegendas as $sLegenda => $lLeganda) {

      if( $lLeganda ) {

       $aLegendasImprimir[$sLegenda] = $sDescricaoLegenda[$sLegenda];
      }
    }

    $aLegendasImprimir['F']   = "F - Faltas";
    $aLegendasImprimir['AMP'] = "AMP - Amparado";
    $aLegendasImprimir['NP']  = "NP - Nota Parcial";
    $aLegendasImprimir['P']   = "P - Nota Projetada";
    $aLegendasImprimir['*']   =  "* - Nota Externa";

    return $aLegendasImprimir;
  }

  /**
   * Escreve a assinatura da segunda página do modelo
   */
  public function escreverAssinaturaPaginaAvaliacao() {

    $this->SetFont( 'arial', 'b', 7 );
    $sTexto  = implode(" | ", $this->legendasParaImpressao());
    $this->Cell( $this->iLarguraPagina, 5, $sTexto, 1, 1, "L" );
    $this->escreverAssinatura();
  }

  /**
   * Calcula e imprime as colunas em branco
   */
  private function imprimiColunasEmBranco() {

    $iQuantidadeColunasEmBranco = ( $this->iLarguraPagina - $this->getX() ) / $this->iLarguraColunaPadrao;

    if ( is_float($iQuantidadeColunasEmBranco) ) {
      $iQuantidadeColunasEmBranco = ceil($iQuantidadeColunasEmBranco);
    }
    for ( $i = 0; $i <= $iQuantidadeColunasEmBranco; $i++ ) {
      $this->Cell($this->iLarguraColunaPadrao, 4, '', 1, 0, "C");
    }
  }

  /**
   * Imprime os dados
   * @param $aDadosCabecalho
   */
  private function cabecalhoSegundaPagina () {

    $this->AddPage();
    $this->Ln(0.4);
    $this->SetFont('Arial', 'B', 7);

    foreach ( $this->aCabecalhoSegundaPagina as $aDadosColuna) {
      $this->Cell($aDadosColuna[0], 4, $aDadosColuna[1], 1, 0, "C");
    }
    $this->imprimiColunasEmBranco();

    $this->Ln();
  }

  /**
   * Escreve a coluna sexo
   * @param $sValor
   */
  private function escreverColunaSexo( $sValor = '' ) {

    if ( $this->lExibirSexo ) {
      $this->Cell($this->iLarguraColunaPadrao, 4, $sValor, 1, 0, "C");
    }
  }

  /**
   * Escreve a coluna data de nascimento
   * @param $sValor
   */
  private function escreverColunaNascimento( $sValor = '' ) {

    if ( $this->lExibirNascimento ) {
      $this->Cell( 20 , 4, $sValor, 1, 0, 'C');
    }
  }

  /**
   * Escreve a coluna idade
   * @param $sValor
   */
  private function escreverColunaIdade( $sValor = '' ) {

    if ( $this->lExibirIdadeSegundaPagina ) {
      $this->Cell($this->iLarguraColunaPadrao, 4, $sValor, 1, 0, "C");
    }
  }

  /**
   * Escreve a coluna resultado anterior
   * @param $sValor
   */
  private function escreverColunaResultadoAnterior( $sValor = '' ) {

    if ( $this->lExibirResultadoAnterior ) {
      $this->Cell($this->iLarguraColunaPadrao, 4, $sValor, 1, 0, "C");
    }
  }

  /**
   * Escreve a coluna Faltas abonadas
   * @param $sValor
   */
  private function escreverColunaFaltasAbonadas( $sValor = '' ) {

    if ( $this->lExibirFaltasAbonadas) {
      $this->Cell($this->iLarguraColunaPadrao, 4, $sValor, 1, 0, "C");
    }
  }

  /**
   * Escreve a coluna Total de Faltas
   * @param $sValor
   */
  private function escreverColunaTotalFaltas( $sValor = '' ) {

    if ( $this->lExibirTotalFaltas) {
      $this->Cell($this->iLarguraColunaPadrao, 4, $sValor, 1, 0, "C");
    }
  }

  private function escreverColunaCodigoAluno($sValor = '') {

    if ( $this->lExibirCodigo) {
      $this->Cell(15, 4, $sValor, 1, 0, "C");
    }
  }

  /**
   * Escreve a coluna parecer
   */
  private function escreverColunaParecer() {

    if ( $this->lExibirParecer ) {

      for ( $i = 0; $i < 3; $i++) {
        $this->Cell( $this->iLarguraColunaPadrao, 4, '', 1, 0, 'C' );
      }
    }
  }

  /**
   * Buscas as avaliações da turma
   * @return AvaliacaoPeriodica[]
   */
  private function getAvaliacoesTurma() {

    $oProcedimentoAvaliacao    = $this->oTurma->getProcedimentoDeAvaliacaoDaEtapa($this->oEtapa);
    $aAvaliacaoTurma           = array();

    foreach ($oProcedimentoAvaliacao->getAvaliacoes() as $oAvaliacao ) {

      if ( $oAvaliacao->getPeriodoAvaliacao()->hasControlaFrequencia() ) {
        $aAvaliacaoTurma[] = $oAvaliacao;
      }
    }
    return $aAvaliacaoTurma;
  }

  /**
   * Retorna o último período de avaliação
   * @return AvaliacaoPeriodica|null
   */
  private function getUltimoPeriodoAvaliacao() {

    $oUltimoPeriodoAvaliacao = null;
    foreach ( $this->getAvaliacoesTurma() as $oAvalicaoPeriodica ) {

      if ( $oAvalicaoPeriodica->getPeriodoAvaliacao()->hasControlaFrequencia() &&
        $oAvalicaoPeriodica->getOrdemSequencia() >= $this->oAvaliacaoPeriodica->getOrdemSequencia() ) {
        $oUltimoPeriodoAvaliacao = $oAvalicaoPeriodica;
      }
    }

    return $oUltimoPeriodoAvaliacao;
  }

  /**
   * Verifica se devemos exibir a Nota Parcial
   * @return bool
   */
  private function exibirNotaParcial() {

    if ( $this->oAvaliacaoPeriodica->getFormaDeAvaliacao()->getTipo() == 'NOTA' &&
         $this->oAvaliacaoPeriodica->getOrdemSequencia() > 2 ) {

      return true;
    }
    return false;
  }

  /**
   * Verifica se devemos exibir a Nota Projetada
   * @return bool
   */
  private function exibirNotaProjetada() {

    if ( $this->oAvaliacaoPeriodica->getFormaDeAvaliacao()->getTipo() == 'NOTA' &&
         $this->oAvaliacaoPeriodica->getOrdemSequencia() > 2 &&
         $this->oAvaliacaoPeriodica == $this->getUltimoPeriodoAvaliacao()
       ) {

      return true;
    }
    return false;
  }

  /**
   * Organiza a estrutura do cabeçalho para a segunda página
   * Como muitas colunas são dinâmicas, foi construido um array dinâmico.
   * @param $iRegencia
   */
  private function organizaCabecalhoSegundaPagina( $iRegencia ) {

    $iColuna = 0;
    $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaPadrao;
    $this->aCabecalhoSegundaPagina[$iColuna][1] = "Nº";

    $iColuna ++;
    $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaNome;
    $this->aCabecalhoSegundaPagina[$iColuna][1] = $this->sTituloColunaNome;

    if ( $this->lExibirSexo ) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaPadrao;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = "S";
    }

    if ( $this->lExibirNascimento ) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = 20;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = "Nascimento";
    }

    if ( $this->lExibirIdadeSegundaPagina ) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaPadrao;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = "I";
    }

    if ( $this->lExibirResultadoAnterior ) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaPadrao;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = "RA";
    }

    if ( $this->lExibirCodigo ) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaPadrao * 3;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = 'Código';
    }

    $oProcedimentoAvaliacao = $this->oTurma->getProcedimentoDeAvaliacaoDaEtapa($this->oEtapa);
    foreach ( $oProcedimentoAvaliacao->getElementosAvaliacoesAnteriores($this->oAvaliacaoPeriodica) as $oAvaliacao) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaNota;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = $oAvaliacao->getPeriodoAvaliacao()->getDescricaoAbreviada();
    }

    if ( $this->exibirNotaParcial() ) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaNota;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = 'NP';
    }

    if ( $this->exibirNotaProjetada() ) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaNota;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = 'P';
    }

    $iColuna ++;
    $oRegencia                                  = RegenciaRepository::getRegenciaByCodigo( $iRegencia );
    $sLabelAvaliacaoPeriodo                     = $oRegencia->getProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo();
    $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaNota;
    $this->aCabecalhoSegundaPagina[$iColuna][1] = $sLabelAvaliacaoPeriodo;

    $iColuna ++;
    $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaPadrao;
    $this->aCabecalhoSegundaPagina[$iColuna][1] = 'F';

    if ( $this->lExibirFaltasAbonadas ) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaPadrao;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = 'FA';
    }

    if ( $this->lExibirTotalFaltas ) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaPadrao;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = 'TF';
    }

    if ( $this->lExibirParecer ) {

      $iColuna ++;
      $this->aCabecalhoSegundaPagina[$iColuna][0] = $this->iLarguraColunaPadrao * 3;
      $this->aCabecalhoSegundaPagina[$iColuna][1] = 'Parecer';
    }
  }

  /**
   * Escreve a segunda página do relatório
   * @param $aAlunos
   */
  private function escreverAlunosSegundaPagina( $aAlunos ) {

    $mAproveitamentoMinimo  = $this->oAvaliacaoPeriodica->getFormaDeAvaliacao()->getAproveitamentoMinino();
    $oProcedimentoAvaliacao = $this->oTurma->getProcedimentoDeAvaliacaoDaEtapa($this->oEtapa);

    foreach ( $aAlunos as $aAlunosPagina ) {

      $this->cabecalhoSegundaPagina();
      $iAlunosImpressos = 0;
      $this->SetFont("arial", '', $this->iTamanhoFonteGrade);
      foreach ( $aAlunosPagina as $oDadosAluno ) {

        if ( !$this->validaSituacaoAluno($oDadosAluno) ) {
          continue;
        }

        $oDataNascimento = new DBDate($oDadosAluno->oMatricula->getAluno()->getDataNascimento());
        $sDataNascimento = $oDataNascimento->convertTo(DBDate::DATA_PTBR);

        $sNomeAluno = $this->getNomeAluno( $oDadosAluno->oMatricula );
        $this->Cell( $this->iLarguraColunaPadrao, 4, $oDadosAluno->oMatricula->getNumeroOrdemAluno(), 1, 0, 'C' );
        $this->Cell( $this->iLarguraColunaNome, 4, $sNomeAluno, 1, 0, 'L' );

        $this->escreverColunaSexo( $oDadosAluno->oMatricula->getAluno()->getSexo() );
        $this->escreverColunaNascimento($sDataNascimento);
        $this->escreverColunaIdade($oDadosAluno->oMatricula->getAluno()->getIdade());
        $this->escreverColunaResultadoAnterior( $oDadosAluno->oMatricula->getResultadoFinalAnterior() );
        $this->escreverColunaCodigoAluno($oDadosAluno->oMatricula->getAluno()->getCodigoAluno());

        if ( $oDadosAluno->oMatricula->getSituacao() == 'MATRICULADO') {

          /**
           * Imprime as avaliações dos períodos de avaliação anterior ao período selecionado
           */

          foreach ( $oProcedimentoAvaliacao->getElementosAvaliacoesAnteriores($this->oAvaliacaoPeriodica) as $oAvaliacaoPeriodica ) {

            $oAvaliacaoAproveitamento = $oDadosAluno->oMatricula->getDiarioDeClasse()->getAvaliacoesPorDisciplina( $this->oRegenciaAtual->getDisciplina(), $oAvaliacaoPeriodica );
            $this->escreverAvaliacaoAlunoPeriodo($oAvaliacaoAproveitamento, $oDadosAluno->oMatricula->isAvaliadoPorParecer());
          }

          $oAvaliacaoDisciplina = $this->getDiarioAvaliacaoDisciplinaAluno($oDadosAluno->oMatricula);

          /**
           * Imprime a nota parical do aluno
           */
          if ( $this->exibirNotaParcial() ) {

            $oElementoAvaliacaoAnterior = $oProcedimentoAvaliacao->getElementoAvaliacaoAnterior($this->oAvaliacaoPeriodica);

            if ( !empty($oElementoAvaliacaoAnterior) ) {

              $iAvaliacaoParcial = $oAvaliacaoDisciplina->getNotaParcial($oElementoAvaliacaoAnterior);

              if ( $iAvaliacaoParcial < $mAproveitamentoMinimo ) {
                $this->SetFont("arial", 'B', 7);
              }

              $this->Cell( $this->iLarguraColunaNota, 4, $iAvaliacaoParcial, 1, 0, 'C' );
              $this->SetFont("arial", '', $this->iTamanhoFonteGrade);

            } else {

              // Linhas da coluna do Periodo de Avaliação
              $this->cell($this->iLarguraColunaNota, 4, '', 1, 0);
            }
          }

          /**
           * Imprime a Nota projetada do aluno;
           * Só quando período selecionado for o último período de avaliação da turma
           */
          if ( $this->exibirNotaProjetada() ) {

            $nNotaProjetada = $oAvaliacaoDisciplina->getNotaProjetada($this->oAvaliacaoPeriodica);
            $this->Cell( $this->iLarguraColunaNota, 4, $nNotaProjetada, 1, 0, 'C' );
          }

          /**
           * Imprime a avaliação do período selecionado
           */
          $oAvaliacaoPeriodo = $oAvaliacaoDisciplina->getAvaliacoesPorOrdem( $this->oAvaliacaoPeriodica->getOrdemSequencia() );
          $this->escreverAvaliacaoAlunoPeriodo($oAvaliacaoPeriodo, $oDadosAluno->oMatricula->isAvaliadoPorParecer());

          /**
           * Calcula as faltas do aluno para o período selecionado
           */
          $iFaltasPeriodo         = $oAvaliacaoDisciplina->getTotalFaltasPorPeriodo($this->oAvaliacaoPeriodica->getPeriodoAvaliacao());
          $iFaltasAbonadasPeriodo = $oAvaliacaoDisciplina->getTotalFaltasAbonadasPorPeriodo( $this->oAvaliacaoPeriodica->getPeriodoAvaliacao() );
          $iTotalFaltasPeriodo    = $iFaltasPeriodo - $iFaltasAbonadasPeriodo;

          $this->Cell( $this->iLarguraColunaPadrao, 4, $iFaltasPeriodo , 1, 0, 'C' );

          $this->escreverColunaFaltasAbonadas($iFaltasAbonadasPeriodo);
          $this->escreverColunaTotalFaltas($iTotalFaltasPeriodo);
          $this->escreverColunaParecer();

          $this->imprimiColunasEmBranco();
        } else {

          $iTamanhoLinha = $this->GetRightMargin() + ($this->iLarguraPagina - $this->GetX());
          $this->imprimeSituacaoAluno( $oDadosAluno->oMatricula->getSituacao(), $iTamanhoLinha);
        }

        $iAlunosImpressos++;
        $this->ln();
      }

      /**
       * Imprime linhas em banco
       */
      if ( $iAlunosImpressos < $this->iNumeroAlunosPagina ) {

        for ( $i = $iAlunosImpressos; $i < $this->iNumeroAlunosPagina; $i++) {
          $this->imprimirLinhasEmBranco( $oProcedimentoAvaliacao->getElementosAvaliacoesAnteriores($this->oAvaliacaoPeriodica) );
        }
      }

      $this->escreverAssinaturaPaginaAvaliacao();
    }
  }

  /**
   * Escreve a Avaliação obtida do aluno no período de avaliação tratando os tipos
   * @param AvaliacaoAproveitamento $oAvaliacaoAproveitamento
   */
  private function escreverAvaliacaoAlunoPeriodo(AvaliacaoAproveitamento $oAvaliacaoAproveitamento, $lAvaliadoparecer = false) {

    $sNota = $oAvaliacaoAproveitamento->getValorAproveitamento()->getAproveitamento();
    $sNota = ArredondamentoNota::arredondar($sNota , $this->oTurma->getCalendario()->getAnoExecucao() );

    if (!$oAvaliacaoAproveitamento->temAproveitamentoMinimo()) {
      $this->SetFont("arial", 'B', $this->iTamanhoFonteGrade);
    }

     if ($oAvaliacaoAproveitamento->getElementoAvaliacao()->getFormaDeAvaliacao()->getTipo() == 'PARECER') {

       if ( $sNota != '' ) {

         $this->SetFont("arial", '', 5.5);
         $sNota = 'PARECER';
       }
     }


    if ($oAvaliacaoAproveitamento->isAvaliacaoExterna() && $sNota != '' ) {
      $sNota = "*{$sNota}";
    }

    if ($lAvaliadoparecer && !empty($sNota)) {
      $sNota = "PD";
    }

    if ( $oAvaliacaoAproveitamento->isAmparado() ) {
      $sNota = 'AMP';
    }

    $this->Cell( $this->iLarguraColunaNota, 4, $sNota, 1, 0, 'C' );
    $this->SetFont("arial", '', $this->iTamanhoFonteGrade);
  }

  /**
   * Imprime linhas em branco para completar o número máximo de alunos por página
   * @param $aAvaliacoes
   */
  private function imprimirLinhasEmBranco( $aAvaliacoes ) {

    $this->Cell( $this->iLarguraColunaPadrao, 4, '', 1, 0, 'C' );
    $this->Cell( $this->iLarguraColunaNome,   4, '', 1, 0, 'L' );

    $this->escreverColunaSexo();
    $this->escreverColunaNascimento();
    $this->escreverColunaIdade();
    $this->escreverColunaResultadoAnterior();
    $this->escreverColunaCodigoAluno();

    foreach ( $aAvaliacoes as $oAvaliacaoPeriodica ) {
      $this->Cell( $this->iLarguraColunaNota, 4, '', 1, 0, 'C' );
    }

    if ( $this->exibirNotaParcial() ) {
      $this->Cell( $this->iLarguraColunaNota,   4, '', 1, 0, 'C' );
    }

    if ( $this->exibirNotaProjetada() ) {
      $this->Cell( $this->iLarguraColunaNota,   4, '', 1, 0, 'C' );
    }

    $this->Cell( $this->iLarguraColunaNota,   4, '', 1, 0, 'C' );
    $this->Cell( $this->iLarguraColunaPadrao, 4, '', 1, 0, 'C' );

    $this->escreverColunaFaltasAbonadas();
    $this->escreverColunaTotalFaltas();
    $this->escreverColunaParecer();
    $this->imprimiColunasEmBranco();
    $this->Ln();
  }

  /**
   * Realiza a chamada das funções para impressão do relatório
   * @throws Exception
   */
  public function escrever() {

    $this->estruturaSubCabecalho();

    if ( count($this->aEstruturaCabecalho) == 0) {
      throw new Exception ("Nenhuma regência(s) selecionada(s) possuem grade de horário.");
    }

    $this->Open();
    foreach ( $this->aEstruturaCabecalho as $iRegencia => $oEstrutura ) {

      $this->organizaCabecalhoSegundaPagina( $iRegencia );

      $this->oRegenciaAtual = RegenciaRepository::getRegenciaByCodigo($iRegencia);
      $this->escreverCorpo( $this->getAlunos($iRegencia), $oEstrutura );
      $this->escreverAlunosSegundaPagina( $this->getAlunos($iRegencia) );

    }

    $this->Output();
  }
}
