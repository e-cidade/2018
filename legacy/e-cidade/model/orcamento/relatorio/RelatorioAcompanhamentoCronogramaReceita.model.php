<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2015 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
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
   * Retorna o arquivo com onde o relat�rio foi salvo.
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
     * Escreve os cabe�alhos do relat�rio.
     */
    $this->escreverNoArquivo($hArquivo, $this->montarCabecalhoMeses());
    $this->escreverNoArquivo($hArquivo, $this->montarCabecalhos());

    /**
     * Escreve as linhas do relat�rio.
     */
    foreach ($this->aLinhasReceita as $oLinha) {

      $aLinha   = array();

      $aLinha[] = $oLinha->descricao;

      foreach ($oLinha->aMeses as $iMes => $oMes) {

        $aLinha[] = trim(db_formatar($oMes->previsto, 'f'));

        /**
         * Janeiro n�o tem a coluna reestimado.
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
   * Busca e retorna os dados necess�rios para emiss�o do relat�rio.
   * @return array
   */
  private function getDados() {

    $aLinhas = array();

    $this->oAcompanhamento->setInstituicoes($this->aInstituicoes);
    $aReceitas = $this->oAcompanhamento->getMetasReceita();

    foreach ($aReceitas as $oReceita) {

      /**
       * Pula as receitas sint�ticas.
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
         * Em janeiro, n�o h� valor reestimado e o c�lculo para a diferen�a � diferente (realizado - previsto)
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
   * Retorna um array contendo os cabe�alhos para gera��o do relat�rio.
   * @return array
   */
  private function montarCabecalhos() {

    $aCabecalho   = array();
    $aCabecalho[] = "RECEITAS";

    for ($iMes = 0; $iMes < 12; $iMes++) {

      $aCabecalho [] = "PREVISTO";

      /**
       * Janeiro n�o coluna reestimado.
       */
      if ($iMes != 0) {
        $aCabecalho [] = "REESTIMADO";
      }

      $aCabecalho [] = "REALIZADO";
      $aCabecalho [] = "DIFEREN�A";
    }

    return $aCabecalho;
  }

  /**
   * Retorna um array com os meses do ano com elementos vazios entre eles, dependendo do m�s.
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
   * Escreve o array p�ssado em uma linha do arquivo.
   * @param resource $rsArquivo
   * @param array $aLinha
   */
  private function escreverNoArquivo($rsArquivo, $aLinha) {
    fputcsv($rsArquivo, $aLinha, ';', '"');
  }
}