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

if (!defined('FPDF_FONTPATH')) {
  define('FPDF_FONTPATH','fpdf151/font/');
}
require_once modification("fpdf151/fpdf.php");

/**
 * @todo Implementar o footer padrão
 */
class PDFDocument extends FPDF {

  const ALIGN_CENTER  = "C";
  const ALIGN_LEFT    = "L";
  const ALIGN_RIGHT   = "R";
  const ALIGN_JUSTIFY = "J";

  const PRINT_LANDSCAPE = "L";
  const PRINT_PORTRAIT = "P";

  /**
   * @var Callback a ser utilizado no header do relatório
   */
  private $fHeader = null;

  /**
   * @var Callback a ser utilizado no footer do relatório
   */
  private $fFooter = null;

  /**
   * @var boolean Controla se deve exibir o header padrão
   */
  private $lHeaderDefault = true;

  /**
   * @var boolean Controla se deve exibir o footer padrão
   */
  private $lFooterDefault = true;

  /**
   * @var boolean Controla se deve quebrar a linha automaticamente após renderizar o multicell
   */
  private $lAutoNewLineMultiCell = true;

  /**
   * @var string Nome da fonte a ser utilizada no relatório
   */
  private $sFontFamily = 'Arial';

  /**
   * @var boolean Controla se a fonte será negrito
   */
  private $lBold = false;

  /**
   * @var boolean Controla se a fonte será Italico
   */
  private $lItalic = false;

  /**
   * @var boolean Controla se a fonte será sublinhada
   */
  private $lUnderline = false;

  /**
   * @var string Nome do arquivo se saída do relatório
   */
  private $sFileName = '';

  /**
   * @var array Array contendo as informações a imprimir no header do relatório
   */
  private $aHeaderDescription = array();

  /**
   * @param string $orientation
   * @param string $unit
   * @param string $format
   */
  public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4') {

    parent::__construct($orientation, $unit, $format);
    $this->alterFont();
  }

  /**
   * Seta uma nova informação no header
   * @param string $sHeader - Informação a ser exibida
   * @param string $sIndetifyer - Identificador a ser utilizado no caso de necessitar que seja substituída a informação
   */
  public function addHeaderDescription($sHeader, $sIndetifyer = null) {

    if (!empty($sIndetifyer)) {
      $pDestino =& $this->aHeaderDescription[$sIndetifyer];
    } else {
      $pDestino =& $this->aHeaderDescription[];
    }

    $pDestino = $sHeader;
  }

  /**
   * Remove todas as informações do header
   */
  public function clearHeaderDescription() {
    $this->aHeaderDescription = array();
  }

  /**
   * Retorna um array contendo as informações do header
   * @return array
   */
  public function getHeaderDescription() {
    return $this->aHeaderDescription;
  }

  /**
   * Seta o nome do arquivo de saída
   * @param string $sFileName
   */
  public function setFileName($sFileName) {
    $this->sFileName = $sFileName;
  }

  /**
   * Retorna o nome do arquivo de saída
   * @return string
   */
  public function getFileName() {

    if (empty($this->sFileName)) {
      $this->setFileName(time());
    }

    return $this->sFileName;
  }

  /**
   * @param string $sFontFamily
   */
  public function setFontFamily($sFontFamily) {
    $this->sFontFamily = $sFontFamily;

    $this->alterFont();
  }

  /**
   * @param boolean $lBold
   */
  public function setBold($lBold) {
    $this->lBold = $lBold;

    $this->alterFont();
  }

  /**
   * @return boolean
   */
  public function getBold() {
    return $this->lBold;
  }

  /**
   * @param boolan $lItalic
   */
  public function setItalic($lItalic) {
    $this->lItalic = $lItalic;

    $this->alterFont();
  }

  /**
   * @param boolean $lUnderline
   */
  public function setUnderline($lUnderline) {
    $this->lUnderline = $lUnderline;

    $this->alterFont();
  }

  /**
   * Habilita o header padrão dos relatórios do sistema
   */
  public function enableHeaderDefault() {
    $this->lHeaderDefault = true;
  }

  /**
   * Desabilita o header padrão dos relatórios do sistema
   */
  public function disableHeaderDefault() {
    $this->lHeaderDefault = false;
  }

  /**
   * Desabilita o footer padrão dos relatórios do sistema
   */
  public function disableFooterDefault() {
    $this->lFooterDefault = false;
  }

  /**
   * Habilita o footer padrão dos relatórios do sistema
   */
  public function enableFooterDefault() {
    $this->lFooterDefault = true;
  }

  /**
   * Seta se deve quebrar a linha automaticamente após exibir o multicell
   *
   * @param boolean $lNewLine
   */
  public function setAutoNewLineMulticell($lNewLine) {
    $this->lAutoNewLineMultiCell = $lNewLine;
  }

  /**
   * Seta o callback do header do relatório
   *
   * @param $fHeader
   */
  public function setHeader($fHeader) {

    if (!is_callable($fHeader)) {
      return false;
    }

    $this->fHeader = $fHeader;
  }

  /**
   * Seta o callback do footer do relatório
   *
   * @param $fFooter
   */
  public function setFooter($fFooter) {

    if (!is_callable($fFooter)) {
      return false;
    }

    $this->fFooter = $fFooter;
  }

  public function header() {

    /**
     * Verifica se deve exibir o header padrão dos relatórios
     */
    if ($this->lHeaderDefault) {

      $oInstituicao = new Instituicao(db_getsession("DB_instit"));

      $iColuna = $this->GetLeftMargin();

      $this->setXY($iColuna, 1);
      $this->image("imagens/files/{$oInstituicao->getImagemLogo()}", $iColuna, 3, 20);

      $this->setBold(true);
      $this->setItalic(true);
      $this->setUnderline(false);
      $this->setFontSize(9);

      if (strlen($oInstituicao->getDescricao()) > 42) {
        $this->setFontSize(8);
      }

      $iColunaTexto = $iColuna + 23;
      $this->text($iColunaTexto, 9, $oInstituicao->getDescricao());

      $this->setBold(false);
      $this->setFontSize(8);

      $sComplento = substr( trim($oInstituicao->getComplemento()), 0, 20);

      if (!empty($sComplento)) {
        $sComplento = ", " . substr( trim($oInstituicao->getComplemento()), 0, 20);
      }

      $this->text($iColunaTexto, 14, trim($oInstituicao->getLogradouro()) . ", " . trim($oInstituicao->getNumero()) . $sComplento );
      $this->text($iColunaTexto, 18, trim($oInstituicao->getMunicipio()) . " - " . trim($oInstituicao->getUF()) );
      $this->text($iColunaTexto, 22, trim($oInstituicao->getTelefone()) . "   -    CNPJ : " . db_formatar($oInstituicao->getCNPJ(), "cnpj"));
      $this->text($iColunaTexto, 26, trim($oInstituicao->getEmail()) );
      $this->text($iColunaTexto, 30, $oInstituicao->getSite());

      $iColunaFinal = $this->getAvailWidth() + $iColuna;

      $this->setfillcolor(235);
      $this->roundedRect($iColunaFinal - 75, 5, 75, 28, 2, 'DF', '123');
      $this->line($iColuna, 33, $iColunaFinal - 75, 33);

      /**
       * Adiciona as informações no cabeçalho do relatório
       */
      $this->setFontSize(7);
      $this->setItalic(false);
      $this->setY(6);
      $this->setLeftMargin($iColunaFinal - 73);
      $this->setAutoNewLineMulticell(true);

      if (!empty($this->aHeaderDescription )) {
        foreach ($this->aHeaderDescription as $sHeader) {
          $this->multiCell(0, 3, $sHeader, 0, 1, "J", 0);
        }
      }

      $this->setFillColor(0);
      $this->setLeftMargin($iColuna);
      $this->setY(35);
    }

    if ($this->fHeader) {
      $header = $this->fHeader;

      $header($this);
    }
  }

  public function footer() {

    if ($this->lFooterDefault) {

      $this->AliasNbPages();
      $sSqlMenuAcess = " select trim(modulo.descricao)||'>'||trim(menu.descricao)||'>'||trim(item.descricao) as menu
                           from db_menu
                          inner join db_itensmenu as modulo on modulo.id_item = db_menu.modulo
                          inner join db_itensmenu as menu on menu.id_item = db_menu.id_item
                          inner join db_itensmenu as item on item.id_item = db_menu.id_item_filho
                          where id_item_filho = ".db_getsession("DB_itemmenu_acessado")."
                            and modulo = ".db_getsession("DB_modulo");

      $rsMenuAcess   = db_query($sSqlMenuAcess);
      $sMenuAcess    = substr(pg_result($rsMenuAcess, 0, "menu"), 0, 50);

      //Position at 1.5 cm from bottom
      $this->SetFont('Arial','',5);
      $this->text(10,$this->h-8,'Base: '.@$GLOBALS["DB_NBASE"]);
      $this->SetFont('Arial', 'I', 6);
      $this->SetY(-10);
      $nome = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
      $nome = substr($nome,strrpos($nome,"/")+1);
      $result_nomeusu = db_query("select nome as nomeusu from db_usuarios where id_usuario =".db_getsession("DB_id_usuario"));
      if (pg_numrows($result_nomeusu) > 0) {
        $nomeusu = pg_result($result_nomeusu,0,0);
      }
      if (isset($nomeusu) && $nomeusu != ""){
        $emissor = $nomeusu;
      } else {
        $emissor = @$GLOBALS["DB_login"];
      }
      $this->Cell(0,10,$sMenuAcess. "  ". $nome.'   Emissor: '.substr(ucwords(mb_strtolower($emissor)),0,30).'  Exerc: '.db_getsession("DB_anousu").
        '   Data: '.date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s"),"T",0,'L');

      $this->Cell(0,10,'Pág '.$this->PageNo().'/{nb}',0,1,'R');
    }

    if ($this->fFooter) {

      $fFooter = $this->fFooter;

      $fFooter($this);
    }
  }

  public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $indent=0) {

    $iColumn = $this->getX();
    $iRow    = $this->getY();

    parent::MultiCell($w, $h, $txt, $border, $align, $fill, $indent);

    if (!$this->lAutoNewLineMultiCell) {
      $this->setY($iRow);
      $this->setX($iColumn + $w);
    }
  }

  /**
   * @param int  $x
   * @param int  $y
   * @param int  $w
   * @param int  $h
   * @param null $style
   */
  public function createRectangle($x, $y, $w, $h, $style = null) {
    $this->Rect($x, $y, $w, $h, $style);
  }

  /**
   * Retorna o número da página corrente
   * @return int
   */
  public function getCurrentPage() {
    return $this->PageNo();
  }

  /**
   * Retorna o tamanho que o multicell irá ocupar
   *
   * @param float $nWidth - Largura do multicell
   * @param float $nHeight - Altura da linha
   * @param string $sContent - Conteúdo
   * @return float
   */
  public function getMultiCellHeight($nWidth, $nHeight, $sContent) {

    return $this->nbLines($nWidth, $sContent) * $nHeight;
  }

  private function alterFont() {

    $sStyle  = ($this->lBold ? 'B' : '');
    $sStyle .= ($this->lItalic ? 'I' : '');
    $sStyle .= ($this->lUnderline ? 'U' : '');

    $this->setFont($this->sFontFamily, $sStyle);
  }

  /**
   * Exibe o conteudo do PDF no navegador
   * @param string $sFileName Nome do arquivo de saída
   */
  public function showPDF($sFileName = '') {

    if (!empty($sFileName)) {
      $this->setFileName($sFileName);
    }

    Header("Content-disposition: inline; filename={$this->getFileName()}.pdf");
    $this->output();
  }

  /**
   * Abre a opção para fazer download do conteúdo do PDF
   * @param string $sFileName Nome do arquivo de saída
   */
  public function downloadPDF($sFileName = '') {

    if (!empty($sFileName)) {
      $this->setFileName($sFileName);
    }

    Header("Content-disposition: attachment; filename={$this->getFileName()}.pdf");
    $this->output();
  }

  /**
   * Salva o arquivo localmente
   * @param  string $sPath
   * @param  string $sFileName
   * @return string -- Caminho do arquivo de saida
   */
  public function savePDF($sFileName = '', $sPath = 'tmp/') {

    if (!empty($sFileName)) {
      $this->setFileName($sFileName);
    }

    $sFile = $sPath . $this->getFileName() . ".pdf";

    $this->output($sFile, false, true);

    return $sFile;
  }

  public function getContent() {

    if($this->state<3) {
      $this->Close();
    }

    return $this->buffer;
  }
}