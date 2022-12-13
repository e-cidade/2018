<?php
/**
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
 * Class PDFTable
 *
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @version $Revision: 1.7 $
 */
class PDFTable {

  /**
   * Define se o relatório vai ser emitido como retrato ou paisagem
   * @type integer
   */
  private $iPrintModel;

  /**
   * Guarda os IDs das colunas que serão totalizadoras
   * @type array
   */
  private $aTotalizingColumns = array();

  /**
   * @type PDFDocument
   */
  private $oPdf;

  /**
   * Headers do relatório
   * @type array
   */
  private $aHeaders = array();

  /**
   * Tamanho de cada coluna
   * @type array
   */
  private $aColumnsWidth = array();

  /**
   * Alinhamento do conteúdo dentro da coluna
   * @type array
   */
  private $aColumnsAlign = array();

  /**
   * Descrição do relatório. É apresentado no canto superior direito.
   * @type array
   */
  private $aHeaderDescription = array();

  /**
   * Formatação específica de cada coluna
   * @type array
   */
  private $aColumnsFormatting = array();

  /**
   * Informações de cada coluna do relatório
   * @type array
   */
  private $aLineInformation = array();

  /**
   * Tamanho disponível no final da página
   * @type int
   */
  private $iEndPageHeigth = 10;

  /**
   * Define se o relatório irá apresentar o valor por página.
   * @type bool
   */
  private $lTotalByPage = false;

  /**
   * Nome da função à ser chamada para criação do header.
   * @type string
   */
  private $fnHeader;

  /**
   * Altura da Linha
   * @type int
   */
  private $iLineHeigth = 4;

  /**
   * As colunas que serão multicell
   * @var array
   */
  private $aMulticellColumns = array();

  /**
   * Controla se as colunas terão a largura em porcentagem
   * @var boolean
   */
  private $lPercentWidth = false;

  /**
   * Formatar a célula em DATA
   * @type string
   */
  const FORMAT_DATE = 'd';

  const FORMAT_NUMERIC = 'f';

  /**
   * @param string $iPrintModel
   */
  public function __construct($iPrintModel = PDFDocument::PRINT_PORTRAIT) {
    $this->iPrintModel = $iPrintModel;
  }

  /**
   * @param boolean $lPercentWidth
   */
  public function setPercentWidth($lPercentWidth) {
    $this->lPercentWidth = $lPercentWidth;
  }

  /**
   * @param $lTotalByPage
   */
  public function setTotalByPage($lTotalByPage) {
    $this->lTotalByPage = $lTotalByPage;
  }

  /**
   * Retorna se deve totalizar por página
   * @return bool
   */
  private function totalByPage() {
    return $this->lTotalByPage;
  }

  /**
   * @param array $aHeaders
   */
  public function setHeaders(array $aHeaders) {

    ksort($aHeaders);
    $this->aHeaders = $aHeaders;
  }

  /**
   * @param array $aColumnsWidth
   */
  public function setColumnsWidth(array $aColumnsWidth) {

    ksort($aColumnsWidth);
    $this->aColumnsWidth = $aColumnsWidth;
  }

  /**
   * @param array $aColumnsAlign
   */
  public function setColumnsAlign(array $aColumnsAlign) {

    ksort($aColumnsAlign);
    $this->aColumnsAlign = $aColumnsAlign;
  }

  /**
   * @param integer $iEndPageHeigth
   */
  public function setEndPageHeight($iEndPageHeigth) {
    $this->iEndPageHeigth = $iEndPageHeigth;
  }

  /**
   * @param integer $iLineHeigth
   */
  public function setLineHeigth($iLineHeigth) {
    $this->iLineHeigth = $iLineHeigth;
  }

  /**
   * @param array $aColumns
   */
  public function setTotalizingColumns(array $aColumns) {

    foreach ($aColumns as $iColumn) {
      $this->aTotalizingColumns[$iColumn] = 0;
    }
  }

  /**
   * @param array $aLineInformation
   */
  public function addLineInformation(array $aLineInformation) {
    $this->aLineInformation[] = $aLineInformation;
  }

  /**
   * @param $sValue
   */
  public function addHeaderDescription($sValue) {
    $this->aHeaderDescription[] = $sValue;
  }

  /**
   * Seta as colunas que serão multicell
   * @param array $aColumns
   */
  public function setMulticellColumns(array $aColumns) {
    $this->aMulticellColumns = $aColumns;
  }

  /**
   * Adiciona formatação para a coluna.
   * @param $iColumn
   * @param $sType
   * @throws ParameterException
   */
  public function addFormatting($iColumn, $sType) {

    if (!in_array($sType, array(self::FORMAT_DATE, self::FORMAT_NUMERIC))) {
      throw new ParameterException("Formatação {$sType} para a célula {$iColumn} não disponível.");
    }

    if (array_key_exists($iColumn, $this->aColumnsFormatting)) {
      throw new ParameterException("Formatação para a célula {$iColumn} já adicionada.");
    }
    $this->aColumnsFormatting[$iColumn] = $sType;
  }

  /**
   * Aplica a formatação quando houver
   * @param $iColumn
   * @param $sValue
   *
   * @return bool|string
   */
  private function applyFormatting($iColumn, $sValue) {

    if (!empty($this->aColumnsFormatting[$iColumn])) {
      $sValue = db_formatar($sValue, $this->getFormatColumn($iColumn));
    }
    return $sValue;
  }

  /**
   * Verifica se as propriedades do objeto estão devidamente setadas.
   * @return bool
   * @throws ParameterException
   */
  private function checkProperties() {

    $iTotalCellWidth    = count($this->aColumnsWidth);
    $iTotalAlignHeaders = count($this->aColumnsAlign);
    if ($iTotalCellWidth != $iTotalAlignHeaders) {
      throw new ParameterException("Informe a mesma quantidade de dados nos arrays de Alinhamento, Tamanho e Header.");
    }
    return true;
  }

  /**
   * @param PDFDocument $oPdf
   * @param Boolean $lShowPDF / Se irá exibir o pdf
   * @throws ParameterException
   */
  public function printOut(PDFDocument $oPdf = null, $lShowPDF = true) {

    $this->oPdf = $oPdf;
    if (empty($this->oPdf)) {

      $this->oPdf = new PDFDocument($this->iPrintModel);
      $this->oPdf->SetFillColor(235);
      $this->oPdf->open();
    }

    foreach ($this->aHeaderDescription as $sValue) {
      $this->oPdf->addHeaderDescription($sValue);
    }
    $this->oPdf->addPage();

    $this->checkProperties();

    /**
     * Verifica se as larguras são em porcentagem e faz os calculos
     */
    if ($this->lPercentWidth) {

      $iWidth = $this->oPdf->getAvailWidth();

      foreach ($this->aColumnsWidth as &$nValue) {
        $nValue = $iWidth*($nValue/100);
      }
    }

    $this->printHeader();
    $this->printLine();

    if ($lShowPDF) {
      $this->oPdf->showPDF();
    }
  }

  /**
   * Facade para imprimir uma célula.
   * @param      $iWidth
   * @param      $sValue
   * @param      $sAlign
   * @param int  $mBorder
   * @param bool $lNewLine
   */
  private function printCell($iWidth, $sValue, $sAlign, $mBorder = 1, $lNewLine = false, $iFill = 0) {
    $this->oPdf->cell($iWidth, $this->iLineHeigth, $sValue, $mBorder, $lNewLine, $sAlign, $iFill);
  }

  /**
   * Imprime uma uma celula com multiplas colunas
   *
   * @param  float   $iWidth
   * @param  float   $iHeight
   * @param  string  $sValue
   * @param  string  $sAlign
   * @param  integer $mBorder
   * @param  boolean $lNewLine
   */
  private function printMultiCell($iWidth, $iHeight, $sValue, $sAlign, $mBorder = 1, $iFill, $lNewLine = false) {

    $this->oPdf->setAutoNewLineMulticell($lNewLine);
    $this->oPdf->multiCell($iWidth, $iHeight, $sValue, $mBorder, $sAlign, $iFill);
  }

  /**
   * Nome da função que montará o header
   * @param string $sHeader
   */
  public function setFunctionHeader($sHeader) {
    $this->fnHeader = $sHeader;
  }

  /**
   * Imprime as informações das linhas
   */
  private function printLine() {

    foreach ($this->aLineInformation as $iIndice => $aCellInformation) {

      $iLineHeight = $this->iLineHeigth;

      /**
       * Faz os calculos do tamanho que a linha deverá ter, com base nas colunas que são multilinha
       */
      if (!empty($this->aMulticellColumns)) {

        $iHeight = 0;
        foreach ($this->aMulticellColumns as $iColumn) {

          $iHeightColumn = $this->oPdf->getMultiCellHeight($this->getWidthColumn($iColumn), $iLineHeight, $aCellInformation[$iColumn]);
          $iHeight = ($iHeightColumn > $iHeight ? $iHeightColumn : $iHeight);
        }

        /**
         * Atribui o tamanho como o tamanho máximo da linha
         */
        $this->iLineHeigth = ($iHeight > 0 ? $iHeight : $iLineHeight);
      }

      foreach ($aCellInformation as $iIndiceColumn => $sInfo) {

        $iIndiceConfigColumn = ($iIndiceColumn);
        $iWidth = $this->getWidthColumn($iIndiceColumn);

        if ($this->oPdf->getAvailHeight() < ($this->iLineHeigth + $iLineHeight)) {

          $iOld = $this->iLineHeigth;
          $this->iLineHeigth = $iLineHeight;

          if ($this->totalByPage()) {
            $this->printTotalizer();
          }

          $this->iLineHeigth = $iOld;

          $this->oPdf->addPage();
          $this->printHeader();
        }

        $this->sumValue($iIndiceConfigColumn, $sInfo);
        if (array_key_exists($iIndiceConfigColumn, $this->aColumnsFormatting)) {
          $sInfo = $this->applyFormatting($iIndiceConfigColumn, $sInfo);
        }

        $iFill = ($iIndice % 2 == 0 ) ? 0 : 1;
        if (!empty($this->aMulticellColumns) && in_array($iIndiceColumn, $this->aMulticellColumns)) {

          $iCellHeight = $this->oPdf->getMultiCellHeight($iWidth, $iLineHeight, $sInfo);

          /**
           * Verifica se o tamanho da coluna é o tamanho da linha, caso não seja é concatenado novas linhas
           */
          if ($iCellHeight < $this->iLineHeigth) {
            $sInfo .= str_repeat("\n ", (($this->iLineHeigth - $iCellHeight) / $iLineHeight));
          }

          $this->printMultiCell($iWidth, $iLineHeight, $sInfo, $this->getAlignColumn($iIndiceColumn), 1, $iFill, $this->breakLine($iIndiceColumn));
        } else {
          $this->printCell($iWidth, $sInfo, $this->getAlignColumn($iIndiceColumn), 1, $this->breakLine($iIndiceColumn), $iFill);
        }
      }

      $this->iLineHeigth = $iLineHeight;
    }

    $this->printTotalizer();
  }

  /**
   * Soma o valor da coluna a ser totalizada
   * @param $iColumn
   * @param $nValue
   */
  private function sumValue($iColumn, $nValue) {

    if (array_key_exists($iColumn, $this->aTotalizingColumns)) {
      $this->aTotalizingColumns[$iColumn] += $nValue;
    }
  }

  /**
   * Imprime a linha totalizadora
   */
  private function printTotalizer() {

    if (count($this->aTotalizingColumns) == 0) {
      return false;
    }

    $this->oPdf->setBold(true);

    foreach ($this->aLineInformation[0] as $iIndice => $sValue) {

      $iColumn = ($iIndice);
      if (array_key_exists($iColumn, $this->aTotalizingColumns)) {

        $this->printCell(
          $this->getWidthColumn($iIndice),
          $this->applyFormatting($iColumn, $this->aTotalizingColumns[$iColumn]),
          $this->getAlignColumn($iIndice),
          true,
          $this->breakLine($iIndice)
        );

      } else {

        $this->printCell(
          $this->getWidthColumn($iIndice),
          '',
          '',
          false,
          $this->breakLine($iIndice)
        );
      }
    }
    $this->oPdf->setBold(false);
    return true;
  }

  /**
   * Retorna o tamanho horizontal da coluna passada informada por parâmetro
   * @param $iIndice
   * @return integer
   */
  private function getWidthColumn($iIndice) {
    return $this->aColumnsWidth[$iIndice];
  }

  /**
   * Retorna o alinhamento setado para os dados da coluna informada no parâmetro
   * @param $iIndice
   * @return integer
   */
  private function getAlignColumn($iIndice) {
    return $this->aColumnsAlign[$iIndice];
  }

  /**
   * Retorna a formatação da coluna informada no parâmetro
   * @param $iIndice
   * @return string
   */
  private function getFormatColumn($iIndice) {
    return $this->aColumnsFormatting[$iIndice];
  }

  /**
   * Retorna se o PDF deve quebrar a linha
   * @param $iColumn
   * @return bool
   */
  private function breakLine($iColumn) {
    return (count($this->aColumnsWidth) == ($iColumn+1));
  }

  /**
   * Imprime o cabeçalho do relatório
   * @return bool
   */
  private function printHeader() {

    if (!empty($this->fnHeader)) {

      call_user_func($this->fnHeader, $this->oPdf);
      return true;
    }

    if (count($this->aHeaders) == 0) {
      return false;
    }
    $this->oPdf->setBold(true);
    foreach ($this->aHeaders as $iIndice => $sLabel) {

      $iWidth   = $this->getWidthColumn($iIndice);
      $this->oPdf->cell($iWidth, 4, $sLabel, 1, $this->breakLine($iIndice), PDFDocument::ALIGN_CENTER, 1);
    }
    $this->oPdf->setBold(false);
    return true;
  }

  /**
   * Força o download do PDF.
   * @param string $sFileName
   */
  public function downloadPDF($sFileName = '') {
    $this->oPdf->downloadPDF($sFileName);
  }
}
