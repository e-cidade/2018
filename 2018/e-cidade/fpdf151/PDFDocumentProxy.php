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
require_once "PDFDocument.php";

/**
 * Class PDFDocumentProxy
 * Classe para controle de um objeto PDFDocument com objetivo de delegar a chamada dos métodos para que possamos
 * fazer alterações do comportamento sem alterar a classe PDFDocument.
 * Exemplo: Cálcular altura que um documento ocupará sem escrevê-lo mas já considerando configurações e fontes do PDFDocument.
 */
class PDFDocumentProxy {

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Altura total da página.
   * @var number
   */
  private $nAlturaPagina;

  /**
   * Se deve escrever o documento ou apenas processar suas informações.
   * @var boolean
   */
  private $lEscrever;

  /**
   * @param PDFDocument $oPdf
   */
  public function __construct(PDFDocument $oPdf) {

    $this->oPdf = $oPdf;
    $this->lEscrever = true;
    $this->nAlturaPagina = 0;
  }

  /**
   * @return PDFDocument
   */
  public function getPdf() {
    return $this->oPdf;
  }

  /**
   * @return bool
   */
  public function getEscrever() {
    return $this->lEscrever;
  }

  /**
   * @param boolean $lEscrever
   */
  public function setEscrever($lEscrever) {
    $this->lEscrever = $lEscrever;
  }

  /**
   * Retorna a altura máxima do documento, calculada até o momento
   * @return number
   */
  public function getAlturaPagina() {
    return $this->nAlturaPagina;
  }

  /**
   * Quebra uma linha ou apenas totaliza seu tamanho, dependendo do valor de lEscrever
   * @param $iAltura
   */
  public function Ln($iAltura) {

    if (!$this->lEscrever) {

      $this->nAlturaPagina += $iAltura;
      return;
    }
    $this->oPdf->Ln($iAltura);
  }

  /**
   * Escreve um MultiCell ou apenas totaliza seu tamanho, dependendo do valor de lEscrever
   * @param number $nLargura Largura ocupada pelo MultiCell
   * @param number $nAltura  Altura ocupada por cada linha do MultiCell
   * @param string $sTexto   Texto para ser escrito no MultiCell
   * @param string $sBorda   Borda do MultiCell
   * @param string $sAlign   Alinhamento do texto do MultiCell
   */
  public function MultiCell($nLargura, $nAltura, $sTexto, $sBorda = "0", $sAlign = 'J') {

    if (!$this->lEscrever) {

      $this->nAlturaPagina += $this->oPdf->getMultiCellHeight($nLargura, $nAltura, $sTexto);
      return;
    }
    $this->oPdf->MultiCell($nLargura, $nAltura, $sTexto, $sBorda, $sAlign);
  }

  /**
   * Desenha um retângulo aredondado, dependendo do valor de lEscrever
   * @param $nPosicaoX number
   * @param $nPosicaoY number
   * @param $nLargura  number
   * @param $nAltura   number
   * @param $nR        number
   * @param $sStilo    string
   * @param $sAngulo   string
   */
  public function roundedRect($nPosicaoX, $nPosicaoY, $nLargura, $nAltura, $nR, $sStilo, $sAngulo) {

    if (!$this->lEscrever) {
      return;
    }
    $this->oPdf->roundedRect($nPosicaoX, $nPosicaoY, $nLargura, $nAltura, $nR, $sStilo, $sAngulo);
  }

  /**
   * Desenha uma linha, dependendo do valor de lEscrever
   * @param $nInicioX number Posição "x" do início da linha
   * @param $nInicioY number Posição "y" do início da linha
   * @param $nFimX    number Posição "x" do fim da linha
   * @param $nFimY    number Posição "y" do fim da linha
   */
  public function line($nInicioX, $nInicioY, $nFimX, $nFimY) {

    if (!$this->lEscrever) {
      return;
    }
    $this->oPdf->line($nInicioX, $nInicioY, $nFimX, $nFimY);
  }

  /**
   * Configura se deve quebrar linha ao escrever um MultiCell.
   * @param boolean $lQuebraLinha
   */
  public function setAutoNewLineMulticell($lQuebraLinha) {
    $this->oPdf->setAutoNewLineMulticell($lQuebraLinha);
  }
}