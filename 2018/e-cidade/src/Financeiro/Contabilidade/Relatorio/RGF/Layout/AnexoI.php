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
namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\Layout;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\InterfaceRelatorioLegal;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Layout\RelatorioLegal;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoI as RelatorioAnexoI;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

/**
 * Class AnexoI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\Layout
 */
class AnexoI extends RelatorioLegal implements InterfaceRelatorioLegal{

  /**
   * AnexoI constructor.
   */
  public function __construct() {

    $this->oPdf = new \PDFDocument("P");
    $this->oPdf->setFillColor(235);
  }

  /**
   * Cabeçalho do relatorio
   */
  public function header() {

    $oPerido     = $this->oAnexo->getPeriodo();
    $oDataInicio = $this->oAnexo->getDatainicio();
    $sMesInicio  = mb_strtoupper( \DBDate::getMesExtenso ($oDataInicio->getMes() ) );
    $sMesFim     = mb_strtoupper( \DBDate::getMesExtenso ($oPerido->getMesFinal()) );
    $oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();

    $sMesInicio .= " DE " . $oDataInicio->getAno();

    $this->oPdf->addHeaderDescription('');
    $aInstituicoes = $this->oAnexo->getInstituicoesSelecionadas();


    if (count($aInstituicoes) == 1) {

      $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]->getSequencial());

      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

      if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
      }
    }else {
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));
    }

    $this->oPdf->addHeaderDescription('RELATÓRIO DE GESTÃO FISCAL');
    $this->oPdf->addHeaderDescription('DEMONSTRATIVO DA DESPESA COM PESSOAL');
    $this->oPdf->addHeaderDescription('ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL');
    $this->oPdf->addHeaderDescription($sMesInicio . ' A ' . $sMesFim . ' DE ' . $this->oAnexo->getAno() );
    $this->oPdf->open();
    $this->oPdf->addPage();
  }

  /**
   * Emite o relatório em PDF.
   */
  public function emitir() {

    if ( $this->oAnexo->getModelo() == RelatorioAnexoI::MODELO_DETALHAMENTO_MENSAL ) {

      $this->oPdf = new \PDFDocument("L");
      $this->oPdf->setFillColor(235);
      $this->oPdf->SetMargins(7,10);
    }

    $this->header();

    $aLinhas = $this->oAnexo->getDadosProcessados();
    $this->imprimirLinhas($aLinhas);

    $this->oPdf->showPDF("AnexoI");
  }

  public function emitirDadosSimplificado() { }
}
