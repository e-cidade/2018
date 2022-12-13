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

namespace ECidade\Tributario\Arrecadacao\Relatorio;

use ECidade\Tributario\Arrecadacao\BaixaDeBanco\TarifaBancaria\Repository as TarifaRepository;

/**
 * Class TarifaArrecadacao
 * @package ECidade\Tributario\Arrecadacao\Relatorio
 */
class TarifaArrecadacao
{
  const MENSAGENS = 'arrecadacao.TarifaArrecadacao.';

  /**
   * @var \PDFDocument
   */
  protected $pdf;

  /**
   * @var \DBDate
   */
  protected $dataInicial;

  /**
   * @var \DBDate
   */
  protected $dataFinal;

  /**
   * @var \Banco
   */
  protected $banco;

  /**
   * @var \Instituicao
   */
  protected $instituicao;

  /**
   * @var \stdClass[]
   */
  protected $arquivos = array();

  /**
   * TarifaArrecadacao constructor.
   */
  public function __construct()
  {
    $this->pdf = new \PDFDocument(\PDFDocument::PRINT_LANDSCAPE);
  }

  /**
   * Imprime o PDF
   */
  public function emitir()
  {
    $this->processar();

    $this->pdf->addHeaderDescription('Tarifas de Arrecadação');
    $this->pdf->addHeaderDescription('');
    $this->pdf->addHeaderDescription('Data Inicial: '.$this->dataInicial->getDate(\DBDate::DATA_PTBR));
    $this->pdf->addHeaderDescription('Data Final: '.$this->dataFinal->getDate(\DBDate::DATA_PTBR));
    $this->pdf->addHeaderDescription('Banco: '.$this->banco->getCodigo() . " - " . $this->banco->getNome());
    $this->pdf->setFontFamily('Arial');
    $this->pdf->SetFontSize(8);
    $this->pdf->SetFillColor(235);
    $this->pdf->open();
    $this->pdf->AddPage();

    $totalGeralQuantidade = 0;
    $totalGeralTarifa     = 0;
    $totalGeralArrecadado = 0;
    $totalGeralLiquido    = 0;

    foreach ($this->arquivos as $indiceArquivo => $stdArquivo) {

      $this->imprimirCabecalhoArquivo($stdArquivo->nome_arquivo);

      $totalQuantidade = 0;
      $totalTarifa     = 0;
      $totalArrecadado = 0;
      $totalLiquido    = 0;
      foreach ($stdArquivo->linhas as $indiceLinha => $stdLinha) {

        if ($indiceLinha === 0 || $this->pdf->getAvailHeight() < 30) {
          $this->imprimirCabecalhoLinha();
        }

        $this->imprimirLinha($stdLinha);

        $totalQuantidade += $stdLinha->quantidade;
        $totalTarifa     += $stdLinha->total_tarifa;
        $totalArrecadado += $stdLinha->total_arrecadado;
        $totalLiquido    += $stdLinha->total_liquido;

        $totalGeralQuantidade += $stdLinha->quantidade;
        $totalGeralTarifa     += $stdLinha->total_tarifa;
        $totalGeralArrecadado += $stdLinha->total_arrecadado;
        $totalGeralLiquido    += $stdLinha->total_liquido;
      }

      $this->imprimirTotalizador('Total do Arquivo: ', $totalQuantidade, $totalTarifa, $totalArrecadado, $totalLiquido );
      $this->pdf->ln(5);
    }

    $this->imprimirTotalizador('Total Geral: ', $totalGeralQuantidade, $totalGeralTarifa, $totalGeralArrecadado, $totalGeralLiquido);
    $this->pdf->showPDF('TarifasDeArrecadacao_'.date('Ymd_His'));
  }

  /**
   * @param $descricao
   * @param $valor
   */
  private function imprimirTotalizador($descricao, $quantidade, $valorTarifa, $valorArrecadado, $valorLiquido)
  {
    $this->pdf->setBold(true);
    $this->pdf->cell(137, 4, $descricao, 'BTR', 0, 'R', 1);
    $this->pdf->cell(30, 4, trim($quantidade), 'TBR', 0, 'R', 1);
    $this->pdf->cell(30, 4, trim(db_formatar(round($valorTarifa, 2), 'f')), 'TBR', 0, 'R', 1);
    $this->pdf->cell(40, 4, trim(db_formatar(round($valorArrecadado, 2), 'f')), 'TBR', 0, 'R', 1);
    $this->pdf->cell(40, 4, trim(db_formatar(round($valorLiquido, 2), 'f')), 'TB', 1, 'R', 1);
    $this->pdf->setBold(false);
  }

  /**
   * @param $nomeArquivo
   */
  private function imprimirCabecalhoArquivo($nomeArquivo)
  {
    $this->pdf->setBold(true);
    $this->pdf->cell(15, 4, "Arquivo:", 'BT', 0, 'L', 1);
    $this->pdf->setBold(false);
    $this->pdf->cell($this->pdf->getAvailWidth(), 4, $nomeArquivo, 'BT', 1, 'L', 1);
  }

  /**
   * Imprime o cabeçalho da linha
   */
  private function imprimirCabecalhoLinha()
  {
    $this->pdf->setBold(true);
    $this->pdf->cell(107, 4, 'Forma de Arrecadação', 'BR', 0, 'C', 1);
    $this->pdf->cell(30, 4, 'Tarifa Cobrada', 'BR', 0, 'C', 1);
    $this->pdf->cell(30, 4, 'Quantidade', 'BR', 0, 'C', 1);
    $this->pdf->cell(30, 4, 'Total Tarifa', 'BR', 0, 'C', 1);
    $this->pdf->cell(40, 4, 'Total Arrecadado', 'BR', 0, 'C', 1);
    $this->pdf->cell(40, 4, 'Total Líquido', 'B', 1, 'C', 1);
    $this->pdf->setBold(false);
  }

  /**
   * Imprime os dados da linha
   * @param \stdClass $linha
   */
  private function imprimirLinha(\stdClass $linha)
  {
    $this->pdf->setAutoNewLineMulticell(false);
    $heightDescricao = $this->pdf->getMultiCellHeight(120, 4, $linha->descricao);
    $this->pdf->MultiCell(107, 4, $linha->descricao, 'B', 'L');
    $this->pdf->cell(30, $heightDescricao, trim(db_formatar(round($linha->valor_tarifa, 2), 'f')), 'B', 0, 'R');
    $this->pdf->cell(30, $heightDescricao, trim($linha->quantidade), 'B', 0, 'R');
    $this->pdf->cell(30, $heightDescricao, trim(db_formatar(round($linha->total_tarifa, 2), 'f')), 'B', 0, 'R');
    $this->pdf->cell(40, $heightDescricao, trim(db_formatar(round($linha->total_arrecadado, 2), 'f')), 'B', 0, 'R');
    $this->pdf->cell(40, $heightDescricao, trim(db_formatar(round($linha->total_liquido, 2), 'f')), 'B', 1, 'R');
    $this->pdf->setAutoNewLineMulticell(true);
  }

  /**
   * Responsável por buscar e tratar e preencher a propriedade $arquivos com os dados a serem impressos
   */
  private function processar()
  {
    $this->validarPropriedades();

    $tarifaRepository = new TarifaRepository();
    $aTarifas = $tarifaRepository->getDadosRelatorio($this->banco, $this->dataInicial, $this->dataFinal);

    foreach ($aTarifas as $stdTarifas) {

      if (empty($this->arquivos[$stdTarifas->codigo_arquivo])) {

        $this->arquivos[$stdTarifas->codigo_arquivo] = new \stdClass();
        $this->arquivos[$stdTarifas->codigo_arquivo]->nome_arquivo = $stdTarifas->nome_arquivo;
        $this->arquivos[$stdTarifas->codigo_arquivo]->linhas = array();
      }
      $linha = new \stdClass();
      $linha->descricao        = $stdTarifas->forma_arrecadacao;
      $linha->valor_tarifa     = $stdTarifas->valor_tarifa;
      $linha->total_tarifa     = $stdTarifas->total_tarifa;
      $linha->total_arrecadado = $stdTarifas->total_arrecadado;
      $linha->total_liquido    = $stdTarifas->total_arrecadado - $stdTarifas->total_tarifa;
      $linha->quantidade       = $stdTarifas->quantidade;

      $this->arquivos[$stdTarifas->codigo_arquivo]->linhas[] = $linha;
    }
  }

  /**
   * Verifica se as propriedades necessárias para impressão do relatório estão devidamente informadas
   * @return bool
   * @throws \ParameterException
   */
  private function validarPropriedades()
  {
    if (empty($this->dataInicial)) {
      throw new \ParameterException(_M(TarifaArrecadacao::MENSAGENS . "data_inicial_vazio") . "aaa");
    }

    if (empty($this->dataFinal)) {
      throw new \ParameterException(_M(TarifaArrecadacao::MENSAGENS . "data_final_vazio"));
    }

    $dataInicial = new \DBDate($this->dataInicial);
    $dataFinal   = new \DBDate($this->dataFinal);
    if ($dataInicial->getTimeStamp() > $dataFinal->getTimeStamp()) {
      throw new \ParameterException(_M(TarifaArrecadacao::MENSAGENS . "data_inicial_maior_final"));
    }

    if (empty($this->banco)) {
      throw new \ParameterException(_M(TarifaArrecadacao::MENSAGENS . "banco_invalido"));
    }
    return true;
  }
  /**
   * @param \DBDate $dataInicial
   */
  public function setDataInicial(\DBDate $dataInicial)
  {
    $this->dataInicial = $dataInicial;
  }

  /**
   * @param \DBDate $dataFinal
   */
  public function setDataFinal(\DBDate $dataFinal)
  {
    $this->dataFinal = $dataFinal;
  }

  /**
   * @param \Banco $banco
   */
  public function setBanco(\Banco $banco)
  {
    $this->banco = $banco;
  }

  /**
   * @param \Instituicao $instituicao
   */
  public function setInstituicao(\Instituicao $instituicao)
  {
    $this->instituicao = $instituicao;
  }

}