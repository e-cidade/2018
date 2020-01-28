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
 * Calcula os elementos presente em um procedimento de avaliação para renderização em um relatorio pdf
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package educacao
 * @subpackage relatorio
 */
abstract class PDFGradeAproveitamento {

  protected $oPdf;
  protected $iLimiteLinha;
  protected $iTamanhoDisciplina = 30;
  protected $iTamanhoFrequencia = 28;
  protected $iTamanhoRF         = 19;
  protected $iTamanhoElementos  = 0;  // sempre calculado
  protected $iTamanhoResultados = 12; // se tiver até 2 assume este tamanho
  protected $iTamanhoNP         = 9;  // se tiver configurado para apresentar nota parcial, assume este tamanho


  // Se houver, esse é o tamanho de um elemento com recuperacao
  protected $iTamanhoElementosRecuperacao = 10; // se tiver apenas um assume este tamanho

  // Colunas que compõe o elemento de avaliacao
  protected $iColunaFalta     = 0;
  protected $iColunaAvaliacao = 0;

  // Colunas que compõe a Frequência
  protected $iColunaAD   = 0;
  protected $iColunaTF   = 0;
  protected $iColunaFA   = 0;
  protected $iColunaFreq = 10;

  // Colunas que compõe o Resultado Final
  protected $iColunaAprov = 0;
  protected $iColunaRF    = 0;

  protected $lCalculouTamanhoColunas = false;
  protected $lApresentarNotaParcial  = false;

  protected $aElementosApresentados = array();

  // Controle de frequencia da turma
  protected $sControleFrequencia = 'AD';



  /**
   * Realiza o calculo do tamanho das colunas de acordo com o número de elementos de avaliação
   * @param  IElementoAvaliacao[] $aElementos array com os elementos de avaliacao
   */
  protected function calculaTamanhoColunas($aElementos) {

    if ( $this->lCalculouTamanhoColunas ) {
      return;
    }

    $iNumeroElementosAvaliacao   = 0;
    $iNumeroElementosRecuperacao = 0;
    $iNumeroResultados           = 0;

    foreach ($aElementos as $oElemento) {

      if ( $oElemento->isResultado() && !$oElemento->imprimeNoBoletim()) {
        continue;
      }

      $this->aElementosApresentados[] = $oElemento;
      if ( $oElemento instanceof AvaliacaoPeriodica ) {

        $oElementoDependente = $oElemento->getElementoAvaliacaoVinculado();
        if ( !is_null($oElementoDependente) ) {

          $iNumeroElementosRecuperacao ++;
          continue;
        }
        $iNumeroElementosAvaliacao ++;
      }

      if ($oElemento->isResultado()) {

        $iNumeroResultados ++;
        continue;
      }
    }

    $iLinha  = $this->iLimiteLinha;
    $iLinha -= $this->iTamanhoDisciplina;
    $iLinha -= $this->iTamanhoFrequencia;
    $iLinha -= $this->iTamanhoRF;

    // se devemos apresentar a coluna de Nota Parcial
    if ($this->lApresentarNotaParcial ) {
      $iLinha -= $this->iTamanhoNP;
    }

    /**
     * Calcula quantos resultados tem, redefine o tamanho máximo e diminue do total restante das linhas
     */
    if ($iNumeroResultados > 2) {
      $this->iTamanhoResultados = 10;
    }
    $iLinha -= ($this->iTamanhoResultados * $iNumeroResultados);

    /**
     * Calcula quantos elementos de recuperação tem e diminue do total restante das linhas
     */
    if ( $iNumeroElementosRecuperacao > 0 ) {

      if ($iNumeroElementosRecuperacao > 1) {
        $this->iTamanhoElementosRecuperacao = 8.5; // Tamanho Mínimo para coluna de Recuperação
      }
      $iLinha -= ($this->iTamanhoElementosRecuperacao * $iNumeroElementosRecuperacao);
    }

    // Calcula o tamanho reservado para colocar os elementos de avaliação (Nota e Falta)
    $this->iTamanhoElementos = $iLinha / $iNumeroElementosAvaliacao;

    $this->iColunaFalta     = 8; // Tamanho Padrão para coluna de Falta
    $this->iColunaAvaliacao = $this->iTamanhoElementos - $this->iColunaFalta;
    // Se a coluna de avaliação ficou menor que 8mm é trocado o tamanho com a coluna de falta
    if ($this->iColunaAvaliacao < 8 ) {

      $this->iColunaAvaliacao = 8.5; // Tamanho Mínimo para coluna de Avaliação
      $this->iColunaFalta     = $this->iTamanhoElementos - $this->iColunaAvaliacao;
    }

    /** Calcula as colunas da segunda linha da frequência */
    $iTamanhoFrequencia = ($this->iTamanhoFrequencia - 10) / 3;
    $this->iColunaFreq  = 10;
    $this->iColunaAD    = $iTamanhoFrequencia;
    $this->iColunaTF    = $iTamanhoFrequencia;
    $this->iColunaFA    = $iTamanhoFrequencia;

    /** Calcula as colunas da segunda linha do resultado final */
    $this->iColunaAprov = ($this->iTamanhoRF / 2);
    $this->iColunaRF    = ($this->iTamanhoRF / 2);

    $this->lCalculouTamanhoColunas = true;
  }


   /**
   * Escreve o cabeçalho da grade
   */
  protected function montarCabecalho( ProcedimentoAvaliacao $oProcedimento ) {

    $aElementos = $oProcedimento->getElementos();
    $this->calculaTamanhoColunas($aElementos);

    /**  --------- Monta uma a primeira linha do cabeçalho ---------   */
    $this->oPdf->SetFont("Arial", "B", 7);
    $this->oPdf->Cell($this->iTamanhoDisciplina, 4, "", 1, 0);
    foreach ($aElementos as $oElemento) {

      if ( $oElemento->isResultado() && !$oElemento->imprimeNoBoletim()) {
        continue;
      }

      /**
       * Sempre que for um Elemento de avaliação, verificamos se o elemento é uma Recuperação
       */
      if ( $oElemento instanceof AvaliacaoPeriodica ) {

        $oElementoDependente = $oElemento->getElementoAvaliacaoVinculado();
        if ( !is_null($oElementoDependente) ) {

          $this->oPdf->Cell($this->iTamanhoElementosRecuperacao, 4, $oElemento->getDescricaoAbreviada(), 1, 0, "C");
          continue;
        }
        $this->oPdf->Cell($this->iTamanhoElementos, 4, $oElemento->getDescricaoAbreviada(), 1, 0, "C");
      }

      if ($oElemento->isResultado()) {
        $this->oPdf->Cell($this->iTamanhoResultados, 4, $oElemento->getDescricaoAbreviada(), 1, 0, "C");
      }
    }
    if ($this->lApresentarNotaParcial ) {
      $this->oPdf->Cell($this->iTamanhoNP, 4, "NP",      1, 0, "C");
    }

    $this->oPdf->Cell($this->iTamanhoFrequencia, 4, "Frequência",      1, 0, "C");
    $this->oPdf->Cell($this->iTamanhoRF,         4, "Resultado Final", 1, 1, "C");
    $this->oPdf->Cell($this->iTamanhoDisciplina, 4, "Disciplina",      1, 0, "C");


    /**  --------- Monta uma a segunda linha do cabeçalho ---------   */
    foreach ($aElementos as $oElemento) {

      if ( $oElemento->isResultado() && !$oElemento->imprimeNoBoletim()) {
        continue;
      }

      /**
       * Sempre que for um Elemento de avaliação, verificamos se o elemento é uma Recuperação
       */
      if ( $oElemento instanceof AvaliacaoPeriodica ) {

        $oElementoDependente = $oElemento->getElementoAvaliacaoVinculado();
        if ( !is_null($oElementoDependente) ) {

          $this->oPdf->Cell($this->iTamanhoElementosRecuperacao, 4, "AVAL.", 1, 0, "C");
          continue;
        }
        $this->oPdf->Cell($this->iColunaAvaliacao, 4, "AVAL.", 1, 0, "C");
        $this->oPdf->Cell($this->iColunaFalta,     4, "FT",    1, 0, "C");
      }

      if ($oElemento->isResultado()) {

        $this->oPdf->Cell($this->iTamanhoResultados, 4, "AVAL.", 1, 0, "C");
        continue;
      }
    }

    if ($this->lApresentarNotaParcial ) {
      $this->oPdf->Cell($this->iTamanhoNP, 4, '-',      1, 0, "C");
    }

    $this->oPdf->Cell($this->iColunaAD,   4, "{$this->sControleFrequencia}",    1, 0, "C");
    $this->oPdf->Cell($this->iColunaTF,   4, "TF",    1, 0, "C");
    $this->oPdf->Cell($this->iColunaFA,   4, "FA",    1, 0, "C");
    $this->oPdf->Cell($this->iColunaFreq, 4, "Freq.", 1, 0, "C");

    $this->oPdf->Cell($this->iColunaAprov, 4, "Aprov.", 1, 0, "C");
    $this->oPdf->Cell($this->iColunaRF,    4, "RF",     1, 1, "C");
  }



  /**
   * Identifica o controle de frequencia da turma
   */
  public function controleDeFrequencia(Matricula $oMatricula) {

    $this->sControleFrequencia = 'AD';
    if ($oMatricula->getTurma()->getFormaCalculoCargaHoraria() == Turma::CH_DIA_LETIVO) {
      $this->sControleFrequencia = 'DL';
    }
  }


  /**
   * Escreve as legendas no pdf
   * @param  integer $iTipo
   */
  public function imprimirLegendas($iTipo = 1) {

    $sFrequencia = "AD - Aulas Dadas";
    if ($this->sControleFrequencia == 'DL') {
      $sFrequencia = "DL - Dias Letivos";
    }

    $aLegendas    = array();
    $aLegendas[1] = "TF - Total Faltas | {$sFrequencia} | FA - Faltas Abonadas";
    $aLegendas[2] = "FT - Faltas | TF - Total Faltas | {$sFrequencia} | FA - Faltas Abonadas | Freq. - Percentual de Frequência | Aprov. - Aproveitamento";

    $this->oPdf->SetFont("Arial", "B", 8);
    $this->oPdf->Cell($this->iLimiteLinha, 4, $aLegendas[$iTipo], 1, 1, "L");
  }
}
