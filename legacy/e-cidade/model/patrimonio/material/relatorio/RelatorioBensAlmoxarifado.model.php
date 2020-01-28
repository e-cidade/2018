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

class RelatorioBensAlmoxarifado {

  /**
   * @var descriptionscpdf
   */
  private $oPdf;

  /**
   * @var ModeloBensAlmoxarifado
   */
  private $oBensAlmoxarifado;

  /**
   * @var Instituicao
   */
  private $oInstituicao;

  private $sFont = 'Arial';

  private $iFont = 8;

  /**
   * @param $oInstituicao Instituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * @param $oBensAlmoxarifado ModeloBensAlmoxarifado
   */
  public function setBensAlmoxarifado(ModeloBensAlmoxarifado $oBensAlmoxarifado) {
    $this->oBensAlmoxarifado = $oBensAlmoxarifado;
  }

  /**
   * @return ModeloBensAlmoxarifado
   */
  public function getBensAlmoxarifado() {
    return $this->oBensAlmoxarifado;
  }

  public function __construct() {

    $this->oPdf = new scpdf();
    $this->oPdf->open();
    $this->oPdf->addPage();
    $this->oPdf->SetFont($this->sFont, '', $this->iFont);
    $this->oPdf->SetAutoPageBreak(false);
  }

  /**
   * Escreve o cabeçalho do relatório
   */
  private function escreverCabecalho() {

    $nWidth        = $this->oPdf->getAvailWidth();
    $oAlmoxarifado = $this->oBensAlmoxarifado->getAlmoxarifado();
    $oCompetencia  = $this->oBensAlmoxarifado->getCompetencia();
    $iTipo         = $this->oBensAlmoxarifado->getTipoEmissao();

    $this->oPdf->setFont($this->sFont, 'b', $this->iFont+1);
    $this->oPdf->cell($nWidth, 4, 'MODELO 21', 0, 1, 'C');
    $this->oPdf->cell($nWidth, 5, "BENS EM ALMOXARIFADO", "T:L:R", 1, 'C');
    $this->oPdf->setFont($this->sFont, '', $this->iFont+1);

    $this->oPdf->cell($nWidth, 5, "DEMONSTRATIVO MENSAL DAS OPERAÇÕES", "B:L:R", 1, 'C');

    $this->oPdf->cell($nWidth*0.6, 5, "Órgão / Entidade / Fundo", "L:R");
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 5, "Município", "R", 1);

    $this->oPdf->cell($nWidth*0.6, 4, "    " . $this->getInstituicao()->getDescricao(), "R:L:B", 0);
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "    " . $this->getInstituicao()->getMunicipio(), "R:B", 1);


    $this->oPdf->cell($nWidth, 5, "Unidade de Controle", "R:L", 1);
    $this->oPdf->cell($nWidth, 4, "    {$oAlmoxarifado->getCodigo()} - {$oAlmoxarifado->getNomeDepartamento()}", "R:L:B", 1);

    $this->oPdf->cell($nWidth, 5, "Mês de " . DBDate::getMesExtenso($oCompetencia->getMes()) . " de " . $oCompetencia->getAno(), "R:L", 1, 'C');
    $this->oPdf->cell($nWidth, 0.5, "", "R:L", 1);

    $this->oPdf->cell($nWidth*0.36, 4, "", 'L');
    $this->oPdf->cell(4, 4, ($iTipo == TipoGrupo::BEM_PERMANENTE ? "x" : ""), 1, 0, 'C');
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "01 - MATERIAL PERMANENTE", "R", 1);

    $this->oPdf->cell($nWidth, 1, "", "R:L", 1);

    $this->oPdf->cell($nWidth*0.36, 4, "", 'L');
    $this->oPdf->cell(4, 4, ($iTipo == TipoGrupo::MATERIAL_CONSUMO ? "x" : ""), 1, 0, 'C');
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "02 - MATERIAL DE CONSUMO", "R", 1);

    $this->oPdf->cell($nWidth, 1, "", "R:L:B", 1);
  }

  /**
   * Escreve o corpo do relatório
   */
  private function escreveCorpo() {

    $nWidth = $this->oPdf->getAvailWidth();
    $nTitle = $nWidth*0.6;
    $nValue = $nWidth*0.2;

    $nSequence    = $nTitle*0.09;
    $nDescription = $nTitle*0.91;

    $this->oPdf->cell($nTitle, 5, "DISCRIMINAÇÃO", "L:B", 0, 'C');
    $this->oPdf->cell($nValue, 5, "R$", 'B', 0, 'C');
    $this->oPdf->cell($nValue, 5, "R$", "BR", 1, 'C');

    $this->oPdf->cell($nWidth, 2, "", "R:L", 1);

    $this->oPdf->setFont($this->sFont, '', $this->iFont+2);

    /**
     * Saldo do mês anterior
     */
    $this->oPdf->cell($nSequence, 7, "01 -", 'L', 0, 'C');
    $this->oPdf->cell($nDescription, 7, "Saldo do mês anterior");

    $this->oPdf->cell($nValue, 7, "");
    $this->oPdf->cell($nValue, 7, number_format($this->oBensAlmoxarifado->getSaldoAnterior(), 2, ',', '.') . "  ", 'R', 1, 'R');

    $this->oPdf->cell($nWidth, 1, "", "R:L", 1);

    /**
     * Entradas
     */
    $nValorCompra        = $this->oBensAlmoxarifado->getValorCompra();
    $nValorDoacao        = $this->oBensAlmoxarifado->getValorDoacao();
    $nValorTransferencia = $this->oBensAlmoxarifado->getValorTransferencia();
    $nValorDevolucao     = $this->oBensAlmoxarifado->getValorDevolucao();
    $nValorTotalEntrada  = $nValorCompra
                         + $nValorDoacao
                         + $nValorTransferencia
                         + $nValorDevolucao;

    $this->oPdf->cell($nSequence, 7, "02 -", 'L', 0, 'C');
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 7, "ENTRADAS", 'R', 1);

    $this->oPdf->cell($nSequence, 6, "", 'L', 0, 'C');
    $this->oPdf->cell($nDescription, 6, "- Compras");

    $this->oPdf->cell($nValue, 6, number_format($nValorCompra, 2, ',', '.'), 0, 0, 'R');
    $this->oPdf->cell($nValue, 6, "", 'R', 1);

    $this->oPdf->cell($nSequence, 6, "", 'L', 0, 'C');
    $this->oPdf->cell($nDescription, 6, "- Doações");

    $this->oPdf->cell($nValue, 6, number_format($nValorDoacao, 2, ',', '.'), 0, 0, 'R');
    $this->oPdf->cell($nValue, 6, "", 'R', 1);

    $this->oPdf->cell($nSequence, 6, "", 'L', 0, 'C');
    $this->oPdf->cell($nDescription, 6, "- Transferências");

    $this->oPdf->cell($nValue, 6, number_format($nValorTransferencia, 2, ',', '.'), 0, 0, 'R');
    $this->oPdf->cell($nValue, 6, "", 'R', 1);

    $this->oPdf->cell($nSequence, 6, "", 'L', 0, 'C');
    $this->oPdf->cell($nDescription, 6, "- Devoluções");

    $this->oPdf->cell($nValue, 6, number_format($nValorDevolucao, 2, ',', '.'), 0, 0, 'R');
    $this->oPdf->cell($nValue, 6, number_format($nValorTotalEntrada, 2, ',', '.') . "  ", 'R', 1, 'R');

    $this->oPdf->cell($nWidth, 3, "", "R:L", 1);

    /**
     * Soma
     */
    $nValorTotalSoma = $this->oBensAlmoxarifado->getSaldoAnterior() + $nValorTotalEntrada;

    $this->oPdf->cell($nSequence, 7, "03 -", 'L', 0, 'C');
    $this->oPdf->cell($nDescription, 7, "SOMA (01 + 02)");

    $this->oPdf->cell($nValue, 7, "");
    $this->oPdf->cell($nValue, 7, number_format($nValorTotalSoma, 2, ',', '.') . "  ", 'R', 1, 'R');

    $this->oPdf->cell($nWidth, 5, "", "R:L", 1);

    /**
     * Saídas
     */
    $nValorRequisicoes = $this->oBensAlmoxarifado->getValorRequisicao();
    $nValorBaixas      = $this->oBensAlmoxarifado->getValorBaixa();
    $nValorTotalSaidas = $nValorRequisicoes + $nValorBaixas;

    $this->oPdf->cell($nSequence, 7, "04 -", 'L', 0, 'C');
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 7, "SAÍDAS", 'R', 1);

    $this->oPdf->cell($nSequence, 6, "", 'L', 0, 'C');
    $this->oPdf->cell($nDescription, 6, "- Requisições para uso");

    $this->oPdf->cell($nValue, 6, number_format($nValorRequisicoes, 2, ',', '.'), 0, 0, 'R');
    $this->oPdf->cell($nValue, 6, "", 'R', 1);

    $this->oPdf->cell($nSequence, 6, "", 'L', 0, 'C');
    $this->oPdf->cell($nDescription, 6, "- Baixas");

    $this->oPdf->cell($nValue, 6, number_format($nValorBaixas, 2, ',', '.'), 0, 0, 'R');
    $this->oPdf->cell($nValue, 6, number_format($nValorTotalSaidas, 2, ',', '.') . "  ", 'R', 1, 'R');

    $this->oPdf->cell($nWidth, 5, "", "R:L", 1);

    /**
     * Valor em Estoque
     */
    $oData        = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));
    $oCompetencia = $this->oBensAlmoxarifado->getCompetencia();

    /**
     * Se a competência for diferente da competência da sessão, então pega o último dia do mês selecionado para compor a data do estoque
     */
    if ($oData->getMes() != $oCompetencia->getMes() || $oData->getAno() != $oCompetencia->getAno()) {
      $oData = new DBDate("{$oCompetencia->getAno()}-{$oCompetencia->getMes()}-" . cal_days_in_month(CAL_GREGORIAN, $oCompetencia->getMes(), $oCompetencia->getAno()));
    }

    $this->oPdf->cell($nSequence, 6, "05 -", 'L', 0, 'C');
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 6, "VALOR EM ESTOQUE", 'R', 1);

    $this->oPdf->cell($nSequence, 4, "", 'L', 0, 'C');
    $this->oPdf->cell($nDescription, 4, "EM " . $oData->getDate(DBDate::DATA_PTBR));

    $this->oPdf->cell($nValue, 4, "");
    $this->oPdf->cell($nValue, 4, number_format($nValorTotalSoma - $nValorTotalSaidas, 2, ',', '.') . "  ", 'R', 1, 'R');

    $this->oPdf->cell($nWidth, 4, "", "R:L:B", 1);


    /**
     * Declaração
     */
    $sTipoMaterial = ($this->oBensAlmoxarifado->getTipoEmissao() == TipoGrupo::BEM_PERMANENTE ? 'permanente' : 'de consumo');

    $this->oPdf->cell($nWidth, 2, "", "R:L", 1);
    $this->oPdf->multiCell($nWidth, 4, "Declaro que o estoque em {$oData->getDate(DBDate::DATA_PTBR)} de material {$sTipoMaterial} importa em R$ " . db_extenso($nValorTotalSoma - $nValorTotalSaidas) . ".", "L:R");
    $this->oPdf->cell($nWidth, 2, "", "R:L:B", 1);

    $this->oPdf->setFont($this->sFont, '', $this->iFont);
  }

  /**
   * Escreve o rodape do relatório
   */
  private function escreveRodape() {

    $this->oPdf->setFont($this->sFont, '', $this->iFont+1);

    $oPdf   = $this->oPdf;
    $nWidth = $oPdf->getAvailWidth();

    $oLibDocumento = new libdocumento(5014);
    $aParagrafos   = $oLibDocumento->getDocParagrafos();

    if (isset($aParagrafos[1])) {
      eval($aParagrafos[1]->oParag->db02_texto);
    }

    $oPdf->setFont($this->sFont, 'b');
    $oPdf->cell($nWidth, 5, "Corresponde ao modelo IGF/70", 0, 1);
  }

  /**
   * Gera o relatório
   */
  public function gerar() {

    $this->escreverCabecalho();
    $this->escreveCorpo();
    $this->escreveRodape();

    Header('Content-disposition: inline; filename=anexoXXI_bensalmoxarifado_' . time() . '.pdf');
    $this->oPdf->Output();
  }
}
?>