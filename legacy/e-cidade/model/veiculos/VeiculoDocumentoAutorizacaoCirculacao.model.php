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

class VeiculoDocumentoAutorizacaoCirculacao {

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   *
   * @var integer
   */
  private $iAltura;

  /**
   *
   * @var integer
   */
  private $iLargura;

  /**
   *
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * @var DBDepartamento
   */
  private $oDepartamento;

  /**
   *
   * @var VeiculoAutorizacaoCirculacao
   */
  private $oAutorizacao;

  /**
   * Altura total de uma via. Usada para saber se a próxima via irá caber na mesma página.
   * @var number
   */
  private $nAlturaVia;

  /**
   * Se deve escrever o documento ou apenas processar o tamanho ocupado.
   * @var boolean
   */
  private $lCalcularAlturaVia = false;

  /**
   * Código da assinatura padrão
   */
  const ASSINATURA_PADRAO = 5022;

  /**
   *
   * @param VeiculoAutorizacaoCirculacao $oAutorizacao
   */
  public function __construct(VeiculoAutorizacaoCirculacao $oAutorizacao) {

    $this->oAutorizacao  = $oAutorizacao;
    $this->oInstituicao  = $oAutorizacao->getInstituicao();
    $this->oDepartamento = $oAutorizacao->getDepartamento();
    $this->oPdf          = new PDFDocument();
    $this->iAltura       = 4;
    $this->iLargura      = $this->oPdf->getAvailWidth() - 10;
    $this->nAlturaVia    = 0;
  }

  /**
   * Quebra uma linha e totaliza seu tamanho na nAlturaVia, dependendo do valor de lCalcularAlturaVia.
   * @param $iAltura
   */
  public function Ln($iAltura) {

    if ($this->lCalcularAlturaVia) {
      $this->nAlturaVia += $iAltura;
    }
    $this->oPdf->Ln($iAltura);
  }

  /**
   * Escreve um MultiCell e totaliza seu tamanho na nAlturaVia, dependendo do valor de lCalcularAlturaVia.
   * @param number $nLargura Largura ocupada pelo MultiCell
   * @param number $nAltura  Altura ocupada por cada linha do MultiCell
   * @param string $sTexto   Texto para ser escrito no MultiCell
   * @param string $sBorda   Borda do MultiCell
   * @param string $sAlign   Alinhamento do texto do MultiCell
   */
  public function MultiCell($nLargura, $nAltura, $sTexto, $sBorda = "0", $sAlign = 'J') {

    if ($this->lCalcularAlturaVia) {
      $this->nAlturaVia += $this->oPdf->getMultiCellHeight($nLargura, $nAltura, $sTexto);
    }
    $this->oPdf->MultiCell($nLargura, $nAltura, $sTexto, $sBorda, $sAlign);
  }

  /**
   * Cabeçalho do documento
   */
  private function cabecalho() {

    $iPosicaoY      = $this->oPdf->GetY();
    $iPosicaoX      = $this->oPdf->GetLeftMargin();
    $iPosicaoTextoX = $iPosicaoX + 22;
    $sComplemento   = substr(trim($this->oInstituicao->getComplemento()), 0, 20);

    $this->oPdf->setXY($iPosicaoX, $iPosicaoY);
    $this->oPdf->image("imagens/files/{$this->oInstituicao->getImagemLogo()}", $iPosicaoX, $iPosicaoY + 3, 20);

    $this->oPdf->setBold(true);
    $this->oPdf->setItalic(true);
    $this->oPdf->setUnderline(false);
    $this->oPdf->setFontSize(9);

    if (strlen($this->oInstituicao->getDescricao()) > 42) {
      $this->oPdf->setFontSize(8);
    }

    $this->oPdf->SetXY($iPosicaoTextoX, $iPosicaoY + 6);
    $this->MultiCell($this->iLargura, $this->iAltura, $this->oInstituicao->getDescricao());
    $this->oPdf->setBold(false);
    $this->oPdf->setFontSize(8);

    if (!empty($sComplemento)) {
      $sComplemento = ", " . $sComplemento;
    }

    $this->oPdf->SetXY($iPosicaoTextoX, $iPosicaoY + 6 + $this->iAltura + 1);
    $this->MultiCell($this->iLargura, $this->iAltura, trim($this->oInstituicao->getLogradouro()) . ", " . trim($this->oInstituicao->getNumero()) . $sComplemento);

    $this->oPdf->SetX($iPosicaoTextoX);
    $this->MultiCell($this->iLargura, $this->iAltura, trim($this->oInstituicao->getMunicipio()) . " - " . trim($this->oInstituicao->getUF()) );

    $this->oPdf->SetX($iPosicaoTextoX);
    $this->MultiCell($this->iLargura, $this->iAltura, trim($this->oInstituicao->getTelefone()) . "   -    CNPJ : " . db_formatar($this->oInstituicao->getCNPJ(), "cnpj"));

    $this->oPdf->SetX($iPosicaoTextoX);
    $this->MultiCell($this->iLargura, $this->iAltura, trim($this->oInstituicao->getEmail()) );

    $this->oPdf->SetX($iPosicaoTextoX);
    $this->MultiCell($this->iLargura, $this->iAltura, $this->oInstituicao->getSite());

    $iColunaFinal = $this->oPdf->getAvailWidth() + $iPosicaoX;

    $this->oPdf->setfillcolor(235);
    $this->oPdf->roundedRect($iColunaFinal - 75, $iPosicaoY + 5, 75, 28, 2, 'DF', '123');
    $this->oPdf->line($iPosicaoX, $iPosicaoY + 33, $iColunaFinal - 75, $iPosicaoY + 33);

    /**
     * Adiciona as informações no cabeçalho do relatório.
     */
    $this->oPdf->setFontSize(7);
    $this->oPdf->setItalic(false);
    $this->oPdf->setY($iPosicaoY + 6);
    $this->oPdf->setLeftMargin($iColunaFinal - 73);
    $this->oPdf->setAutoNewLineMulticell(true);

    $aHeaderDescription = $this->oPdf->getHeaderDescription();
    if (!empty($aHeaderDescription)) {
      foreach ($aHeaderDescription as $sHeader) {
        $this->MultiCell(72, 3, $sHeader, "0", "J");
      }
    }

    $this->oPdf->setFillColor(0);
    $this->oPdf->setLeftMargin($iPosicaoX);
    $this->oPdf->setY($iPosicaoY + 35);
  }

  /**
   * Imprime as assinaturas
   */
  private function imprimirAssinaturas() {

    $oAssinatura  = new libdocumento(self::ASSINATURA_PADRAO);
    $aAssinaturas = $oAssinatura->getDocParagrafos();
    $sAssinatura  = "";
    foreach ($aAssinaturas as $oParagrafoAssinatura) {

      if (!empty($oParagrafoAssinatura->oParag->db02_texto) && $oParagrafoAssinatura->oParag->db04_ordem == $this->oInstituicao->getCodigo()) {
        $sAssinatura = $oParagrafoAssinatura->oParag->db02_texto;
      }
    }

    if (empty($sAssinatura)) {
      throw new BusinessException("Assinatura com código " . self::ASSINATURA_PADRAO . " não localizada.");
    }

    /**
     * Faz substituíções para usar métodos da classe e calcular a altura da via.
     */
    $sAssinatura = str_replace('oPdf->multicell', 'MultiCell', $sAssinatura);
    $sAssinatura = str_replace('oPdf->Ln', 'Ln', $sAssinatura);
    eval($sAssinatura);
  }

  /**
   * Emite o corpo do documento
   *
   * @param  integer $iNumeroVia
   */
  private function emitirCorpo($iNumeroVia) {

    $sVeiculo      = "{$this->oAutorizacao->getVeiculo()->getModelo()} / {$this->oAutorizacao->getVeiculo()->getPlaca()}";
    $sDataInicial  = $this->oAutorizacao->getDataInicial()->getDate(DBDate::DATA_PTBR);
    $sDataFinal    = $this->oAutorizacao->getDataFinal()->getDate(DBDate::DATA_PTBR);
    $sMotorista    = $this->oAutorizacao->getMotorista()->getCGMMotorista()->getNomeCompleto();
    $sInstituicao  = $this->oInstituicao->getDescricao();
    $sDepartamento = $this->oDepartamento->getNomeDepartamento();
    $sObservacao   = $this->oAutorizacao->getObservacao();

    $sMensagem  = "O chefe do departamento {$sDepartamento}, no uso de suas atribuições, AUTORIZA o ";
    $sMensagem .= "motorista {$sMotorista} a transitar com a viatura de placa {$sVeiculo} no período de {$sDataInicial} a {$sDataFinal}, a serviço ";
    $sMensagem .= "da instituição {$sInstituicao}.";

    $this->oPdf->setFontSize(10);
    $this->oPdf->setBold(true);
    $this->MultiCell($this->iLargura, $this->iAltura, "Autorização Nº. {$this->oAutorizacao->getCodigo()}");
    $this->oPdf->setBold(false);
    $this->oPdf->setFontSize(8);

    $this->Ln($this->iAltura);
    $this->MultiCell($this->iLargura, $this->iAltura, $sMensagem);
    if (!empty($sObservacao)) {

      $this->Ln($this->iAltura);
      $this->MultiCell($this->iLargura, $this->iAltura, "Observação: {$sObservacao}");
    }

    $this->imprimirAssinaturas();
    $this->MultiCell($this->iLargura, $this->iAltura, "{$iNumeroVia}ª via", 'B', 'R');
  }

  /**
   * Realiza as configurações do PDFDocument
   */
  private function configurarPdf() {

    $this->oPdf->addHeaderDescription("Autorização Nº. {$this->oAutorizacao->getCodigo()}");
    $this->oPdf->addHeaderDescription("{$this->oInstituicao->getMunicipio()} - {$this->oInstituicao->getUf()}");
    $this->oPdf->addHeaderDescription("{$this->oAutorizacao->getDataEmissao()->getDate('d/m/Y')}");
    $this->oPdf->SetAutoPageBreak(true, 0);

    $this->oPdf->disableHeaderDefault();
    $this->oPdf->Open();
    $this->oPdf->SetTopMargin(1);
    $this->oPdf->AliasNbPages();
    $this->oPdf->AddPage();
  }

  /**
   * Emite o relatório.
   */
  public function emitir() {

    $this->configurarPdf();
    $this->lCalcularAlturaVia = true;
    for ($iNumeroVia = 1; $iNumeroVia <= 3; $iNumeroVia++) {

      if (!$this->lCalcularAlturaVia && $this->oPdf->getAvailHeight() < $this->nAlturaVia) {
        $this->oPdf->AddPage();
      }
      $this->cabecalho();
      $this->emitirCorpo($iNumeroVia);
      $this->lCalcularAlturaVia = false;
    }

    $this->oPdf->showPDF();
  }
}
