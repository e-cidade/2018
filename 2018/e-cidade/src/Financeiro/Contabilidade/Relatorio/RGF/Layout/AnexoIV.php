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
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

class AnexoIV extends RelatorioLegal implements InterfaceRelatorioLegal{

  public function __construct() {

    $this->oPdf  = new \PDFDocument("P");
    $this->oPdf->setFillColor(235);

  }

  public function header() {

    $oPrefeitura = $this->oAnexo->getPrefeitura();
    $oPerido     = $this->oAnexo->getPeriodo();

    $sMesInicio = mb_strtoupper( \DBDate::getMesExtenso ($oPerido->getMesInicial()) );
    $sMesFim    = mb_strtoupper( \DBDate::getMesExtenso ($oPerido->getMesFinal()) );

    $oInstituicao = \InstituicaoRepository::getInstituicaoSessao();
    $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

    if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
    }

    $this->oPdf->addHeaderDescription('RELATÓRIO DE GESTÃO FISCAL');
    $this->oPdf->addHeaderDescription('DEMONSTRATIVO DAS OPERAÇÕES DE CRÉDITO');
    $this->oPdf->addHeaderDescription('ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL');
    $this->oPdf->addHeaderDescription('JANEIRO' . ' A ' . $sMesFim . ' DE ' . $this->oAnexo->getAno() );
    $this->oPdf->open();
    $this->oPdf->addPage();

  }

  public function emitir() {

    $this->header();

    $aLinhas = $this->oAnexo->getDadosProcessados();
    $this->imprimirLinhas($aLinhas);

    $this->oPdf->showPDF("AnexoIV");
  }

  public function emitirDadosSimplificado() {

  }

}
