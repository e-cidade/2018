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

namespace ECidade\Tributario\Grm;

/**
 * Class GuiaDeRecolhimento
 * @package ECidade\Tributario\Grm
 */
class GuiaDeRecolhimento {

  /**
   * @type integer
   */
  const MODELO_GUIA = 97;

  /**
   * @var \PDFDocument
   */
  private $pdf;

  /**
   * @var Recibo
   */
  private $reciboGRM;

  /**
   * @var \Instituicao
   */
  private $instituicao;

  /**
   * Tamanho das fontes para as informações que serão impressas
   * @type integer
   */
  const FONT_SIZE_INFO = 8;

  /**
   * Tamanho das fontes para os títulos das células
   * @type integer
   */
  const FONT_SIZE_TITLE = 6;

  /**
   * GuiaDeRecolhimento constructor.
   * @param Recibo $recibo
   * @param \Instituicao $instituicao
   */
  public function __construct(Recibo $recibo, \Instituicao $instituicao) {

    $this->reciboGRM   = $recibo;
    $this->instituicao = $instituicao;
    $this->construirPdf();
  }

  /**
   * Imprime na ordem os dados necessários
   */
  private function processar() {

    $this->imprimirViaContribuinte();
    $this->pdf->ln($this->pdf->getAvailHeight() / 4);
    $this->pdf->cell(195, 4, '', 'T', 1);
    $this->imprimirViaPrefeitura();
  }

  /**
   * Gera um arquivo PDF para download
   * @return \File
   */
  public function gerarArquivo() {

    $this->processar();

    $nomeArquivo = 'recibo_grm_'.$this->reciboGRM->getCodigo().'_'.date('YmdHis');
    $this->pdf->savePDF($nomeArquivo);
    return new \File("tmp/{$nomeArquivo}.pdf");
  }


  /**
   * Gera o arquivo em tempo apresentado ao usuário
   */
  public function emitir() {

    $this->processar();
    $this->pdf->showPDF();
  }

  /**
   * Imprime a via que deverá ficar com o contribuinte
   */
  private function imprimirViaContribuinte() {

    $this->pdf->setBold(true);
    $this->pdf->cell(190, 4, 'Via Contribuinte', 0, 1, \PDFDocument::ALIGN_RIGHT);
    $this->pdf->setBold(false);
    $this->imprimeDadosPrefeituraVencimento();
    $this->imprimeDadosDoContribuinte();
    $this->imprimeNomeUnidadeValorPrincipal();
    $this->imprimeCompetenciaUnidadeArrecadadoraDesconto();
    $this->imprimeRecolhimentoReferenciaDeducoes();
    $this->imprimeInformacoesAcrescimosTotal();
    $this->imprimeAutenticacaoBancaria($this->reciboGRM->getLinhaDigitavel());

    $this->pdf->createRectangle(5, $this->pdf->getY(), 195, 40);
    $this->pdf->setBold(true);
    $this->pdf->cell(195, 4, 'OUTRAS INFORMAÇÕES:', 0, 1);
    $this->pdf->setBold(false);
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $descricao = implode(' - ', array(
      "Código de Arrecadação: {$this->reciboGRM->getCodigoArrecadacao()}",
    ));

    $this->pdf->cell(190, 3, $descricao, 0, 1);
    $atributos = $this->reciboGRM->getAtributos();
    foreach ($atributos as $indice => $dadosAtributos) {

      if ($indice >= 10) {
        continue;
      }

      if ($dadosAtributos->valor != 'Não') {
        $dadosAtributos->valor = utf8_decode($dadosAtributos->valor);
      }

      $this->pdf->cell(190, 3, "{$dadosAtributos->nome}: {$dadosAtributos->valor}", 0, 1);
    }
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);
  }

  /**
   * Imprime os dados da via que ficará com a prefeitura
   */
  private function imprimirViaPrefeitura() {

    $this->pdf->setBold(true);
    $this->pdf->cell(190, 4, 'Via Prefeitura', 0, 1, \PDFDocument::ALIGN_RIGHT);
    $this->pdf->setBold(false);
    $this->imprimeDadosPrefeituraVencimento();
    $this->imprimeDadosDoContribuinte();
    $this->imprimeNomeUnidadeValorPrincipal();
    $this->imprimeCompetenciaUnidadeArrecadadoraDesconto();
    $this->imprimeRecolhimentoReferenciaDeducoes();
    $this->imprimeInformacoesAcrescimosTotal();
    $this->imprimeAutenticacaoBancaria();

    $this->pdf->createRectangle(5, $this->pdf->getY(), 195, 20);
    $codigoBarras   = $this->reciboGRM->getCodigoBarras();
    $linhaDigitavel = $this->reciboGRM->getLinhaDigitavel();
    $this->pdf->setXY(10, $this->pdf->getY()+2);
    $this->pdf->SetFontSize(8);
    $this->pdf->cell($this->getAvailWidth(), 4, $linhaDigitavel, 0, 1);
    $this->pdf->int25(10,$this->pdf->getY()+1,$codigoBarras,10,0.3);
    $this->pdf->ln(20);
  }



  /**
   * @return float
   */
  private function getAvailWidth() {
    return ($this->pdf->getAvailWidth() - 59);
  }

  /**
   * Constrói os dados do objeto PDF para emissão da Guia de Cobrança
   * @return void
   */
  private function construirPdf() {

    $this->pdf = new \PDFDocument(\PDFDocument::PRINT_PORTRAIT);
    $this->pdf->setFontFamily('Arial');
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);
    $this->pdf->SetMargins(5,5);
    $this->pdf->disableHeaderDefault();
    $this->pdf->disableFooterDefault();
    $this->pdf->Open();
    $this->pdf->AddPage();
  }

  /**
   * Imprime a primeira linha do boleto
   * - Dados da Prefeitura
   * - Vencimento
   */
  private function imprimeDadosPrefeituraVencimento() {

    $widthPrimeiraColuna = ($this->pdf->getAvailWidth() - 70);

    $yOriginal = $this->pdf->getY();
    $caminhoBrasao = 'imagens/files/'.$this->instituicao->getImagemLogo();
    $this->pdf->Image($caminhoBrasao,6, $this->pdf->getY()+1,10);
    $this->pdf->MultiCell(15,16, '', 'TBL');
    $this->pdf->setXY(16, $yOriginal);
    $this->pdf->SetFontSize(8);
    $this->pdf->setBold(true);
    $this->pdf->cell($widthPrimeiraColuna,8, 'PREFEITURA DE NITERÓI', 'TR', 1, 'L');
    $this->pdf->setXY(16, $yOriginal+4);
    $this->pdf->cell($widthPrimeiraColuna,8, 'Secretaria Municipal da Fazenda', 'R', 1, 'L');
    $this->pdf->setXY(16, $yOriginal+8);
    $this->pdf->cell($widthPrimeiraColuna,8, 'GRM - GUIA DE RECOLHIMENTO DO MUNICÍPIO DE NITERÓI', 'BR', 1, 'L');

    /* Dados de Vencimento */
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);
    $this->pdf->setXY($this->pdf->getAvailWidth()-54, $yOriginal);
    $this->pdf->cell(54, 8, 'VENCIMENTO:', 'TR', 1);
    $this->pdf->setXY($this->pdf->getAvailWidth()-54, $yOriginal+8);
    $this->pdf->setBold(false);
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $this->pdf->cell(54, 8, $this->reciboGRM->getDataVencimento()->getDate(\DBDate::DATA_PTBR), 'BR', 1, 'C');
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);
  }

  /**
   * Imprime os dados de Contribuinte
   * - Nome
   * - CPF / CNPJ
   */
  private function imprimeDadosDoContribuinte() {


    if ($this->reciboGRM->getCgm()->isJuridico()) {
      $documento = $this->reciboGRM->getCgm()->getCnpj();
      $formatacao = 'cnpj';
    } else {
      $documento = $this->reciboGRM->getCgm()->getCpf();
      $formatacao = 'CPF';
    }

    $nomeContribuinte = $this->reciboGRM->getCgm()->getNome();
    $cidadao = $this->reciboGRM->getCidadao();
    if (!empty($cidadao)) {

      $nomeContribuinte = $cidadao->getNome();
      $documento        = $cidadao->getCpfCnpj();
      $formatacao       = 'cnpj';
      if (strlen($documento) === 11) {
        $formatacao = 'CPF';
      }
    }

    $widthPrimeiraColuna = $this->getAvailWidth();
    $this->pdf->setBold(true);
    $this->pdf->cell($widthPrimeiraColuna, 4, 'NOME DO CONTRIBUINTE/RECOLHEDOR:', "RTL", 0);
    $this->pdf->cell(54, 4, 'CNPJ/CPF DO CONTRIBUINTE/RECOLHEDOR:', "RTL", 1);
    $this->pdf->setBold(false);
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $this->pdf->cell($widthPrimeiraColuna, 4, mb_strtoupper($nomeContribuinte), "BRL", 0);
    $this->pdf->cell(54, 4, db_formatar($documento, $formatacao), "BRL", 1);
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);
  }



  /**
   * Imprime o nome da unidade arrecadadora e o valor principal
   * - Nome da Unidade Arrecadadora
   * - Valor Principal
   */
  private function imprimeNomeUnidadeValorPrincipal() {

    $widthPrimeiraColuna = $this->getAvailWidth();
    $valor = $this->reciboGRM->getValor() != '' ? trim(db_formatar($this->reciboGRM->getValor(), 'f')) : '';

    $this->pdf->setBold(true);
    $this->pdf->cell($widthPrimeiraColuna, 4, 'NOME DA UNIDADE ARRECADADORA:', "RTL", 0);
    $this->pdf->cell(54, 4, 'VALOR PRINCIPAL:', "RTL", 1);
    $this->pdf->setBold(false);
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $this->pdf->cell($widthPrimeiraColuna, 4, $this->reciboGRM->getUnidadeGestora()->getNome(), "BRL", 0);
    $this->pdf->cell(54, 4, $valor, "BRL", 1);
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);

  }

  /**
   * Imprime dados da quarta linha
   * - Competência
   * - Unidade Arrecadadora
   * - Desconto / Abatimento
   */
  private function imprimeCompetenciaUnidadeArrecadadoraDesconto() {

    $valor = $this->reciboGRM->getValorDesconto() != '' ? trim(db_formatar($this->reciboGRM->getValorDesconto(), 'f')) : '';

    $this->pdf->setBold(true);
    $this->pdf->cell(50, 4, 'COMPETÊNCIA:', "RTL", 0);
    $this->pdf->cell(91, 4, 'UNIDADE ARRECADADORA:', "RTL", 0);
    $this->pdf->cell(54, 4, '(-) DESCONTO/ABATIMENTO:', "RTL", 1);
    $this->pdf->setBold(false);
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $this->pdf->cell(50, 4, $this->reciboGRM->getCompetencia(), "RBL", 0);
    $this->pdf->cell(91, 4, $this->reciboGRM->getUnidadeGestora()->getCodigo(), "RBL", 0);
    $this->pdf->cell(54, 4, $valor, "RBL", 1);
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);
  }

  /**
   * Imprime a quinta linha
   * - Código do Recolhimento
   * - Número de Referência
   * - Outras Deduções
   */
  private function imprimeRecolhimentoReferenciaDeducoes() {

    $valor = $this->reciboGRM->getValorOutrasDeducoes() != '' ? trim(db_formatar($this->reciboGRM->getValorOutrasDeducoes(), 'f')) : '';

    $this->pdf->setBold(true);
    $this->pdf->cell(70, 4, 'CÓDIGO DO RECOLHIMENTO:', "RTL", 0);
    $this->pdf->cell(71, 4, 'NÚMERO DE REFERÊNCIA:', "RTL", 0);
    $this->pdf->cell(54, 4, '(-) OUTRAS DEDUÇÕES:', "RTL", 1);
    $this->pdf->setBold(false);
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $dadosRecolhimento = $this->reciboGRM->getTipoRecolhimento()->getCodigoRecolhimento() ." - ".$this->reciboGRM->getTipoRecolhimento()->getTituloReduzido();
    $this->pdf->cell(70, 4, $dadosRecolhimento, "RBL", 0);
    $this->pdf->cell(71, 4, $this->reciboGRM->getNumeroReferencia(), "RBL", 0);
    $this->pdf->cell(54, 4, $valor, "RBL", 1);
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);
  }

  /**
   * Imprime a sexta linha
   * - Informacoes Complementares
   * - Mora / Multa
   * - Juros / Encargos
   * - Outros Acréscimos
   * - Valor Total
   */
  private function imprimeInformacoesAcrescimosTotal() {

    $widthPrimeiraColuna = $this->getAvailWidth();
    $yInformacoesComplementares = $this->pdf->getY();
    $this->pdf->setBold(true);
    $this->pdf->cell($widthPrimeiraColuna, 4, 'INFORMAÇÕES COMPLEMENTARES:', "RL", 1);
    $descricaoComplementar = "{$this->reciboGRM->getTipoRecolhimento()->getInstrucoes()}";
    $descricaoComplementar = substr($descricaoComplementar, 0, 400);
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $this->pdf->MultiCell($widthPrimeiraColuna, 4, $descricaoComplementar, "0");
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);
    $this->pdf->createRectangle(5, $yInformacoesComplementares, $widthPrimeiraColuna, 32, 'TLB');
    $this->pdf->setBold(false);

    $xValores = $widthPrimeiraColuna+5;
    $this->pdf->setXY($xValores, $yInformacoesComplementares);
    $this->pdf->setBold(true);
    $this->pdf->cell(54, 4, '(+) MORA/MULTA:', "RTL", 1);
    $this->pdf->setBold(false);
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $this->pdf->setXY($xValores, $this->pdf->getY());
    $valorMulta = $valor = $this->reciboGRM->getValorMulta() != '' ? trim(db_formatar($this->reciboGRM->getValorMulta(), 'f')) : '';
    $this->pdf->cell(54, 4, $valorMulta, "RBL", 1);
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);

    $this->pdf->setXY($xValores, $this->pdf->getY());
    $this->pdf->setBold(true);
    $this->pdf->cell(54, 4, '(+) JUROS/ENCARGOS:', "RTL", 1);
    $this->pdf->setBold(false);
    $this->pdf->setXY($xValores, $this->pdf->getY());
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $valorJuros = $valor = $this->reciboGRM->getValorJuros() != '' ? trim(db_formatar($this->reciboGRM->getValorJuros(), 'f')) : '';
    $this->pdf->cell(54, 4, $valorJuros, "RBL", 1);
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);

    $this->pdf->setXY($xValores, $this->pdf->getY());
    $this->pdf->setBold(true);
    $this->pdf->cell(54, 4, '(+) OUTROS ASCRÉSCIMOS:', "RTL", 1);
    $this->pdf->setBold(false);
    $this->pdf->setXY($xValores, $this->pdf->getY());
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $valorAcrescimo = $valor = $this->reciboGRM->getValorOutrosAcrescimento() != '' ? trim(db_formatar($this->reciboGRM->getValorOutrosAcrescimento(), 'f')) : '';
    $this->pdf->cell(54, 4, $valorAcrescimo, "RBL", 1);
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);

    $this->pdf->setXY($xValores, $this->pdf->getY());
    $this->pdf->setBold(true);
    $this->pdf->cell(54, 4, 'VALOR TOTAL:', "RTL", 1);
    $this->pdf->setBold(false);
    $this->pdf->setXY($xValores, $this->pdf->getY());
    $this->pdf->SetFontSize(self::FONT_SIZE_INFO);
    $valorAcrescimo = $valor = $this->reciboGRM->getValorTotal() != '' ? trim(db_formatar($this->reciboGRM->getValorTotal(), 'f')) : '';
    $this->pdf->cell(54, 4, $valorAcrescimo, "RBL", 1);
    $this->pdf->SetFontSize(self::FONT_SIZE_TITLE);
  }

  /**
   * Imprime a sétima linha
   * @param string $linhaDigitavel
   * - Autenticacao Bancaria
   */
  private function imprimeAutenticacaoBancaria($linhaDigitavel = null) {

    $this->pdf->setBold(true);
    $this->pdf->cell($this->pdf->getAvailWidth()-5, 4, 'AUTENTICAÇÃO BANCÁRIA:', 'LTR', 1);
    $this->pdf->setBold(false);
    $this->pdf->cell($this->pdf->getAvailWidth()-5, 5, $linhaDigitavel, 'LBR', 1);

  }
}
