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
 * Class RelatorioAcompanhamentoCronogramaDespesa
 */
class RelatorioAcompanhamentoCronogramaDespesa {

  /**
   * @type cronogramaFinanceiro
   */
  private $oAcompanhamento;

  /**
   * @type array
   */
  private $aInstituicoes = array();

  /**
   * @type integer
   */
  private $iNivel;

  /**
   * @type string
   */
  private $sNomeArquivo;

  /**
   * @type LinhaRelatorioAcompanhamentoCronogramaDespesa[]
   */
  private $aDespesas = array();

  /**
   * @type File
   */
  private $oArquivo;

  /**
   * @param \cronogramaFinanceiro $oAcompanhamento
   * @param array                 $aInstituicoes
   * @param integer               $iNivel
   */
  public function __construct(cronogramaFinanceiro $oAcompanhamento, array $aInstituicoes, $iNivel) {

    $this->oAcompanhamento = $oAcompanhamento;
    $this->aInstituicoes   = $aInstituicoes;
    $this->iNivel          = $iNivel;
    $this->sNomeArquivo    = "AcompanhamentoCronogramaDespesa_{$this->oAcompanhamento->getPerspectiva()}.csv";
  }


  /**
   * Carrega as informações em um array local
   * @return void
   */
  private function carregarInformacoes() {

    $aInstituicoes = array();
    $this->oAcompanhamento->setInstituicoes($this->aInstituicoes);
    $aDespesas = $this->oAcompanhamento->getMetasDespesa($this->iNivel);
    foreach ($aDespesas as $iIndice => $oStdDadoDespesa) {

      $oLinhaRelatorio = new LinhaRelatorioAcompanhamentoCronogramaDespesa();

      if ($this->iNivel != 9) {

        $oLinhaRelatorio->setCodigo($oStdDadoDespesa->codigo);
        $oLinhaRelatorio->setDescricao(urldecode($oStdDadoDespesa->descricao));
      } else {

        $oLinhaRelatorio->setCodigoOrgao($oStdDadoDespesa->o58_orgao);
        $oLinhaRelatorio->setCodigoUnidade($oStdDadoDespesa->o58_unidade);
        $oLinhaRelatorio->setCodigoRecurso($oStdDadoDespesa->o58_codigo);
        $oLinhaRelatorio->setCodigoAnexo($oStdDadoDespesa->o58_localizadorgastos);
        $oLinhaRelatorio->setDescricaoRecurso(urldecode($oStdDadoDespesa->o15_descr));
        $oLinhaRelatorio->setDescricaoAnexo(urldecode($oStdDadoDespesa->o11_descricao));
      }

      if ($this->iNivel == 2) {
        $oLinhaRelatorio->setCodigoOrgao($oStdDadoDespesa->o58_orgao);
      }

      foreach ($oStdDadoDespesa->aMetas->dados as $iIndiceMes => $oStdValor) {

        $iMesData           = $iIndiceMes + 1;
        $iAnoAcompanhamento = $this->oAcompanhamento->getAno();

        $oDataInicial = new DBDate("{$iAnoAcompanhamento}-{$iMesData}-01");
        $oDataFinal   = new DBDate("{$iAnoAcompanhamento}-{$iMesData}-".DBDate::getQuantidadeDiasMes($iMesData, $iAnoAcompanhamento));
        $oInformacoes = cronogramaMetaDespesa::getInformacaoDespesa($oStdDadoDespesa, $this->iNivel, $oDataInicial, $oDataFinal);

        $oStdValor->valor = !empty($oStdValor->valor) ? $oStdValor->valor : 0.00;

        $oInformacoes->setValorReestimado($oStdValor->valor);
        $oLinhaRelatorio->adicionarValores($oInformacoes, $iMesData);

        if ($iMesData == 1) {
          $oInformacoes->setValorReestimado(null);
        }
      }
      $this->aDespesas[] = $oLinhaRelatorio;
    }
    unset($aDespesas);
  }

  /**
   * Gera o arquivo CSV
   * @return void
   */
  public function gerarCSV() {

    $this->carregarInformacoes();

    if (file_exists("tmp/".$this->sNomeArquivo)) {
      unlink("tmp/".$this->sNomeArquivo);
    }
    $hArquivo = fopen("tmp/".$this->sNomeArquivo, "w+");
    $this->criarCabecalhoArquivo($hArquivo);
    $this->criarCabecalhoMeses($hArquivo);

    foreach ($this->aDespesas as $oLinhaRelatorio) {

      $aLinha   = array();
      $aLinha[] = $oLinhaRelatorio->getDescricaoLinha();

      foreach ($oLinhaRelatorio->getValoresDespesa() as $oValorDespesa) {

        $aLinha[] = trim(db_formatar($oValorDespesa->getValorPrevisto(), 'f'));
        $aLinha[] = trim(db_formatar($oValorDespesa->getValorCotaMensal(), 'f'));
        if ($oValorDespesa->getValorReestimado() !== null) {
          $aLinha[] = trim(db_formatar($oValorDespesa->getValorReestimado(), 'f'));
        }
        $aLinha[] = trim(db_formatar($oValorDespesa->getValorPago(), 'f'));
        $aLinha[] = trim(db_formatar($oValorDespesa->getDiferenca(), 'f'));
      }
      $aLinha[] = "\n";
      $this->escrever($hArquivo, implode(";", $aLinha));
    }
    $this->oArquivo = new File("tmp/".$this->sNomeArquivo);
  }

  /**
   * @return \File
   */
  public function getArquivo() {
    return $this->oArquivo;
  }

  /**
   * @param resource $hArquivo
   * @throws \ParameterException
   */
  private function criarCabecalhoArquivo($hArquivo) {

    $aCabecalho = array(" ");
    for ($iMes = 1; $iMes <= 12; $iMes++) {
      $sCabecalho = DBDate::getMesExtenso($iMes).";;;";
      if ($iMes != 1) {
        $sCabecalho .= ";";
      }
      $aCabecalho[] = $sCabecalho;
    }
    $aCabecalho[] = "\n";
    $this->escrever($hArquivo, implode(";", $aCabecalho));
  }

  /**
   * @param resource $hArquivo
   */
  private function criarCabecalhoMeses($hArquivo) {

    $aCabecalho = array("Descricao");
    for ($iMes = 1; $iMes <= 12; $iMes++) {
      $sCabecalho = "Previsto;Comprometido;";
      if ($iMes != 1) {
        $sCabecalho .= "Reestimado;";

      }
      $sCabecalho  .= "Pago;Diferença";
      $aCabecalho[] = $sCabecalho;
    }

    $aCabecalho[] = "\n";
    $this->escrever($hArquivo, implode(";", $aCabecalho));
  }

  /**
   * @param resource $hArquivo
   * @param string $sLinha
   */
  private function escrever($hArquivo, $sLinha) {
    fwrite($hArquivo, $sLinha);
  }
}