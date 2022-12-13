<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


class RelatorioAcompanhamentoCronogramaReceita {

  /**
   * @var AcompanhamentoCronograma
   */
  private $oAcompanhamento;

  /**
   * @var array
   */
  private $aInstituicoes;

  /**
   * @var \File
   */
  private $oArquivo;

  /**
   * @var array
   */
  private $aLinhasReceita;

  function __construct(AcompanhamentoCronograma $oAcompanhamento, $aInstituicoes) {

    $this->oAcompanhamento = $oAcompanhamento;
    $this->aInstituicoes   = $aInstituicoes;
    $this->sNomeArquivo    = "AcompanhamentoCronogramaReceita_{$this->oAcompanhamento->getPerspectiva()}.csv";
  }

  /**
   * Retorna o arquivo com onde o relatório foi salvo.
   * @return File
   */
  public function getArquivo() {
    return $this->oArquivo;
  }

  public function emitirRelatorio() {

    $this->aLinhasReceita = $this->getDados();
    $this->gerarCsv();
  }

  private function gerarCsv() {

    $sDiretorioArquivo = "tmp/";

    if (file_exists($sDiretorioArquivo . $this->sNomeArquivo)) {
      unlink($sDiretorioArquivo . $this->sNomeArquivo);
    }

    $hArquivo = fopen($sDiretorioArquivo . $this->sNomeArquivo, 'w');

    /**
     * Escreve os cabeçalhos do relatório.
     */
    $this->escreverNoArquivo($hArquivo, $this->montarCabecalhoMeses());
    $this->escreverNoArquivo($hArquivo, $this->montarCabecalhos());

    /**
     * Escreve as linhas do relatório.
     */
    foreach ($this->aLinhasReceita as $oLinha) {

      $aLinha   = array();

      $aLinha[] = $oLinha->descricao;

      foreach ($oLinha->aMeses as $iMes => $oMes) {

        $aLinha[] = trim(db_formatar($oMes->previsto, 'f'));

        /**
         * Janeiro não tem a coluna reestimado.
         */
        if ($iMes != 0) {
          $aLinha[] = trim(db_formatar($oMes->reestimado, 'f'));
        }

        $aLinha[] = trim(db_formatar($oMes->realizado, 'f'));
        $aLinha[] = trim(db_formatar($oMes->diferenca, 'f'));
      }
      $this->escreverNoArquivo($hArquivo, $aLinha);
    }

    fclose($hArquivo);
    $this->oArquivo = new File($sDiretorioArquivo . $this->sNomeArquivo);
  }

  /**
   * Busca e retorna os dados necessários para emissão do relatório.
   * @return array
   */
  private function getDados() {

    $aLinhas = array();

    $this->oAcompanhamento->setInstituicoes($this->aInstituicoes);
    $aReceitas = $this->oAcompanhamento->getMetasReceita();

    foreach ($aReceitas as $oReceita) {

      /**
       * Pula as receitas sintéticas.
       */
      if (is_array($oReceita->aDesdobramentos) && count($oReceita->aDesdobramentos) > 0) {
        continue;
      }

      $oLinha            = new stdClass();
      $oLinha->descricao = $oReceita->o57_fonte . "-" . urldecode($oReceita->o57_descr);
      $aMesesValores     = array();

      foreach ($oReceita->aMetas->dados as $oReceitaMes) {

        $oReceita->instituicao = implode(',', $this->aInstituicoes);

        $sUltimoDiaDoMes    = cal_days_in_month(CAL_GREGORIAN, $oReceitaMes->mes, $this->oAcompanhamento->getAno());
        $oDataInicial       = new DBDate("{$this->oAcompanhamento->getAno()}-{$oReceitaMes->mes}-01");
        $oDataFinal         = new DBDate("{$this->oAcompanhamento->getAno()}-{$oReceitaMes->mes}-{$sUltimoDiaDoMes}");
        $oInformacaoReceita = cronogramaMetaReceita::getInformacaoReceita($oReceita, $oDataInicial, $oDataFinal);

        $oReceitaMesValores             = new stdClass();
        $oReceitaMesValores->previsto   = $oInformacaoReceita->getValorPrevisto();
        $oReceitaMesValores->reestimado = $oReceitaMes->valor;
        $oReceitaMesValores->realizado  = $oInformacaoReceita->getValorRealizado();
        $oReceitaMesValores->diferenca  = $oReceitaMesValores->realizado - $oReceitaMesValores->reestimado;

        /*
         * Em janeiro, não há valor reestimado e o cálculo para a diferença é diferente (realizado - previsto)
         */
        if ($oReceitaMes->mes == 1) {
          $oReceitaMesValores->reestimado = null;
          $oReceitaMesValores->diferenca  = $oReceitaMesValores->realizado - $oReceitaMesValores->previsto;
        }

        $aMesesValores[] = $oReceitaMesValores;
      }

      $oLinha->aMeses = $aMesesValores;
      $aLinhas[] = $oLinha;
    }

    return $aLinhas;
  }

  /**
   * Retorna um array contendo os cabeçalhos para geração do relatório.
   * @return array
   */
  private function montarCabecalhos() {

    $aCabecalho   = array();
    $aCabecalho[] = "RECEITAS";

    for ($iMes = 0; $iMes < 12; $iMes++) {

      $aCabecalho [] = "PREVISTO";

      /**
       * Janeiro não coluna reestimado.
       */
      if ($iMes != 0) {
        $aCabecalho [] = "REESTIMADO";
      }

      $aCabecalho [] = "REALIZADO";
      $aCabecalho [] = "DIFERENÇA";
    }

    return $aCabecalho;
  }

  /**
   * Retorna um array com os meses do ano com elementos vazios entre eles, dependendo do mês.
   * @return array
   * @throws ParameterException
   */
  private function montarCabecalhoMeses() {

    $aMeses   = array();
    $aMeses[] = "";

    for ($iMes = 1; $iMes <= 12; $iMes++) {

      $aMeses[]         = DBDate::getMesExtenso($iMes);
      $iColunasEmBranco = 3;

      if ($iMes == 1) {
        $iColunasEmBranco = 2;
      }

      for ($i = 0; $i < $iColunasEmBranco; $i++) {
        $aMeses[] = "";
      }
    }

    return $aMeses;
  }

  /**
   * Escreve o array pássado em uma linha do arquivo.
   * @param resource $rsArquivo
   * @param array $aLinha
   */
  private function escreverNoArquivo($rsArquivo, $aLinha) {
    fputcsv($rsArquivo, $aLinha, ';', '"');
  }
}