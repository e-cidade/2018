<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
 * Classe cria um relatório conforme os anexos do modelo 6
 *
 * @author $Author: dbmauricio $
 * @version $Revision: 1.5 $
 */
class RelatorioAnexoDeliberacao {


  /**
   * Objeto do PDF
   *
   * @var scpdf
   */
  private $oPdf;

  /**
   * Representa um anexo
   *
   * @var AnexoIConciliacaoBancaria|AnexoIIConciliacaoBancaria|AnexoIIIConciliacaoBancaria
   */
  private $oAnexo;

  /**
   * Objeto conta bancária do relatório
   *
   * @var ContaBancaria
   */
  private $oContaBancaria;

  /**
   * Objeto instituição do relatório
   *
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Construtor da classe
   *
   * @param Integer $iTipoAnexo
   * @param ContaBancaria $oContaBancaria
   * @param DBCompetencia $oCompetencia
   */
  function __construct($iTipoAnexo, ContaBancaria $oContaBancaria, DBCompetencia $oCompetencia) {

    switch ($iTipoAnexo) {

      case AnexoIConciliacaoBancaria::ANEXO:

        $this->oAnexo = new AnexoIConciliacaoBancaria($oContaBancaria, $oCompetencia);
        break;

      case AnexoIIConciliacaoBancaria::ANEXO:

        $this->oAnexo = new AnexoIIConciliacaoBancaria($oContaBancaria, $oCompetencia);
        break;

      case AnexoIIIConciliacaoBancaria::ANEXO:

        $this->oAnexo = new AnexoIIIConciliacaoBancaria($oContaBancaria, $oCompetencia);
        break;
    }

    $this->setContaBancaria($oContaBancaria);

    $this->oPdf = new scpdf();
    $this->oPdf->Open();
    $this->oPdf->AddPage();
    $this->oPdf->SetFont('Arial', '', 8);
    $this->oPdf->SetAutoPageBreak(false);


  }

  /**
   * Retorna o objeto scpdf
   *
   * @access private
   * @return scpdf
   */
  private function getPdf() {
    return $this->oPdf;
  }

  /**
   * Retorna uns dos anexos do modelo 6 conforme passado no constutor
   *
   * @access public
   * @return AnexoIConciliacaoBancaria|AnexoIIConciliacaoBancaria|AnexoIIIConciliacaoBancaria
   */
  public function getAnexo() {
    return $this->oAnexo;
  }

  /**
   * Retorna a conta bancária do relatório
   *
   * @access public
   * @return ContaBancaria
   */
  public function getContaBancaria() {
    return $this->oContaBancaria;
  }

  /**
   * Retorna a instutuição do relatório
   *
   * @access public
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Seta a conta bancária do relatório
   *
   * @access public
   * @param ContaBancaria $oContaBancaria
   */
  public function setContaBancaria(ContaBancaria $oContaBancaria) {
    $this->oContaBancaria = $oContaBancaria;
  }

  /**
   * Seta a instituição do relatório
   *
   * @access public
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Constrói o cabeçalho do relatório
   */
  private function construirCabecalho() {

    $this->oPdf->SetFont('Arial', 'B', 10);
    $this->oPdf->Cell(190, 10, $this->getAnexo()->getNome(), 0, 1, 'C');
    $this->oPdf->Cell(190, 10, $this->getAnexo()->getTitulo(), 1, 1, 'C');

    $this->oPdf->SetFont('Arial', '', 8);
    $this->oPdf->Cell(100, 4, "Orgão/ Entidade/ Fundo", 'LR', 0);
    $this->oPdf->Cell(90, 4, "Município", 'LR', 1);
    $this->oPdf->Cell(100, 8, $this->getInstituicao()->getDescricao(), 'LRB', 0, 'C');
    $this->oPdf->Cell(90, 8, $this->getInstituicao()->getMunicipio(), 'LRB', 1, 'C');

    $this->oPdf->Cell(100, 8, "Banco nº {$this->getContaBancaria()->getCodigoBanco()}", 'LB', 0, 'C');
    $this->oPdf->Cell(90, 8, "Conta nº {$this->getContaBancaria()->getNumeroConta()}-{$this->getContaBancaria()->getDVConta()}", 'RB', 1);
  }

  /**
   * Constrói o cabeçalho do corpo do relatório
   *
   * @param Integer $iHeightCell
   */
  private function construirCabecalhoCorpo($iWidthCenterCell, $iWidthSideCell ,$iHeightCell) {

    $this->oPdf->Cell($iWidthSideCell, $iHeightCell, "Data", 1, 0, 'C');

    if ($this->getAnexo()->getAnexo() == AnexoIConciliacaoBancaria::ANEXO) {

      $this->oPdf->Cell(40, $iHeightCell, "Número", 1, 0, 'C');
      $iWidthCenterCell = 54;
    }
    $this->oPdf->Cell($iWidthCenterCell, $iHeightCell, $this->getTituloColuna(), 1, 0, 'C');
    $this->oPdf->Cell($iWidthSideCell, $iHeightCell, "Valor R$", 1, 1, 'C');
  }

  /**
   * @return string
   */
  private function getTituloColuna() {

    $aTitulos = array(
      AnexoIConciliacaoBancaria::ANEXO   => "Natureza do Depósito",
      AnexoIIConciliacaoBancaria::ANEXO  => "Natureza do Débito",
      AnexoIIIConciliacaoBancaria::ANEXO => "Natureza do Depósito"
    );
    return $aTitulos[$this->getAnexo()->getAnexo()];
  }

  /**
   * Constrói o corpo do relatório
   *
   * @param Integer $iWidthCenterCell
   * @param Integer $iWidthSideCell
   * @param Integer $iHeightCell
   */
  private function construirCorpo($iWidthCenterCell, $iWidthSideCell ,$iHeightCell) {

    $this->construirCabecalhoCorpo($iWidthCenterCell, $iWidthSideCell ,$iHeightCell);

    foreach ($this->getAnexo()->getDados() as  $oRegistroAnexoConciliacaoBancaria) {

      if ($this->oPdf->GetY() > ($this->oPdf->h - 20)) {

        $this->oPdf->AddPage();
        $this->construirCabecalhoCorpo($iWidthCenterCell, $iWidthSideCell ,$iHeightCell);
      }

      $this->oPdf->Cell($iWidthSideCell,
                        $iHeightCell,
                        $oRegistroAnexoConciliacaoBancaria->getData()->getDate(DBDate::DATA_PTBR),
                        1,
                        0,
                        'C');

      if ($this->getAnexo()->getAnexo() == AnexoIConciliacaoBancaria::ANEXO) {

        $this->oPdf->Cell(40, $iHeightCell, '', 1, 0, 'C');
        $iWidthCenterCell = 54;
      }
      $this->oPdf->Cell($iWidthCenterCell, $iHeightCell, $oRegistroAnexoConciliacaoBancaria->getNatureza(), 1, 0, 'L');
      $this->oPdf->Cell($iWidthSideCell,
                        $iHeightCell,
                        db_formatar($oRegistroAnexoConciliacaoBancaria->getValor(), 'f'),
                        1,
                        1,
                        'R');
    }
  }


  /**
   * Constrói o rodapé do relatório
   */
  private function construirRodape() {

    $oLibDocumento = new libdocumento(1036);
    $aParagrafos   = $oLibDocumento->getDocParagrafos();

    if (isset($aParagrafos[1])) {

      $lQuebrouPagina        = false;
      $iAltRetanguloInicial  = $this->oPdf->GetY();
      $iAltRetanguloFinal    = 0;
      $iAltLinha             = 4;
      $sDataRelatorio        = cal_days_in_month(CAL_GREGORIAN,
                                                 $this->getAnexo()->getCompetencia()->getMes(),
                                                 $this->getAnexo()->getCompetencia()->getAno()) .
                               "/" . $this->getAnexo()->getCompetencia()->getMes() .
                               "/" . $this->getAnexo()->getCompetencia()->getAno();

      $sRodape    = str_replace('$oPdf', '$this->oPdf', $aParagrafos[1]->oParag->db02_texto);
      $sNomeAnexo = $this->getAnexo()->getNome();
      $sRodape    = str_replace("Deliberação TCE - RJ nº 200/96 - Modelo 6", "Modelo 6 - {$sNomeAnexo}",$sRodape);

      eval($sRodape);
    }

  }

  /**
   * Cria o relatório em PDF
   *
   * @return boolean
   */
  public function processar() {

    $this->construirCabecalho();
    $this->construirCorpo(94, 48, 5);
    $this->construirRodape();

    $oAnexo = $this->getAnexo();

    Header('Content-disposition: inline; filename=anexo' . $oAnexo::ANEXO . '_deliberacao20096_' . time() . '.pdf');
    $this->oPdf->Output();

    return true;
  }
}
