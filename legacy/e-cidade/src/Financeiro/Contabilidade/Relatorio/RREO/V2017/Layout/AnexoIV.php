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

namespace Ecidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout;

use \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoIV as Relatorio;
use \ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

/**
 * Class AnexoIV
 * @package Ecidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout
 */
class AnexoIV {

  /**
   * @var \PDFDocument
   */
  private $oPdf;

  /**
   * @var \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoIV
   */
  private $oRelatorio;

  /**
   * @var \stdClass[]
   */
  private $aLinhas;

  /**
   * @param \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoIV $oRelatorio
   */
  public function setAnexo(\ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoIV $oRelatorio) {
    $this->oRelatorio = $oRelatorio;
  }

  /**
   * @throws \Exception
   */
  public function emitir() {

    if (empty($this->oRelatorio)) {
      throw new \Exception("Não foi informado ao AnexoIV para impressão.");
    }

    $this->aLinhas = $this->oRelatorio->getDados();
    $this->oPdf    = new \PDFDocument(\PDFDocument::PRINT_LANDSCAPE);

    $oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();
    $sMesInicio  = mb_strtoupper(\DBDate::getMesExtenso($this->oRelatorio->getDataInicialPeriodo()->getMes()));
    $sMesFim     = mb_strtoupper(\DBDate::getMesExtenso($this->oRelatorio->getDataFinal()->getMes()));

    $this->oPdf->addHeaderDescription('');
    $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));
    
    $aInstituicoes = explode(",", $this->oRelatorio->getInstituicoes());

    if (count($aInstituicoes) == 1) {
      $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);

      if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
      }
    }

    $this->oPdf->addHeaderDescription('RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA ');
    $this->oPdf->addHeaderDescription('DEMONSTRATIVO DE RECEITAS E DESPESAS PREVIDENCIÁRIAS DO REGIME PRÓPRIO DOS SERVIDORES');
    $this->oPdf->addHeaderDescription($sMesInicio . ' A ' . $sMesFim . '/' . $this->oRelatorio->getAno() . ' - BIMESTRE ' . $sMesInicio . '-' . $sMesFim);



    $this->oPdf->open();
    $this->oPdf->addPage();
    $this->oPdf->SetFontSize(6);
    $this->oPdf->SetFillColor(235);

    $nLargura = ($this->oPdf->getAvailWidth() / 2);
    $this->oPdf->cell($nLargura, 4, 'RREO - Anexo 4 (LRF, Art. 53, inciso II)', 0, 0);
    $this->oPdf->cell($nLargura, 4, 'Em Reais', 0, 1, \PDFDocument::ALIGN_RIGHT);

    foreach ($this->aLinhas as $oStdLinha) {

      /*
       * Inicio da impressão do relatório pelos quadros referente ao PLANO PREVIDENCIÁRIO
       */
      if (\Check::between($oStdLinha->ordem, 1, 34)) {

        if ($oStdLinha->ordem == 1) {
          $this->cabecalhoReceita('PLANO PREVIDENCIÁRIO');
        }
        $this->imprimeReceita($oStdLinha);
      }

      if (\Check::between($oStdLinha->ordem, 35, 50)) {

        if ($oStdLinha->ordem == 35) {
          $this->cabecalhoDespesa();
        }
        $this->imprimeDespesa($oStdLinha);
      }

      if ($oStdLinha->ordem == 51) {

        $this->espaco(4);
        $this->imprimeDespesa($oStdLinha);
      }

      if ($oStdLinha->ordem == 52) {

        $this->espaco(4);
        $this->imprimeRecursosRPPS('RECURSOS RPPS ARRECADADOS EM EXERCÍCIOS ANTERIORES', $oStdLinha);
      }

      if ($oStdLinha->ordem == 53) {

        $this->espaco(4);
        $this->imprimeRecursosRPPS('RESERVA ORÇAMENTÁRIA DO RPPS', $oStdLinha);
      }

      if (\Check::between($oStdLinha->ordem, 54, 57)) {

        if ($oStdLinha->ordem == 54) {
          $this->cabecalhoAporte('APORTES DE RECURSOS PARA O PLANO PREVIDENCIÁRIO DO RPPS');
        }
        $this->imprimeAportes($oStdLinha);
      }

      if (\Check::between($oStdLinha->ordem, 58, 60)) {

        if ($oStdLinha->ordem == 58) {
          $this->cabecalhoBensDireitos();
        }
        $this->imprimeBensDireitosRPPS($oStdLinha);
      }

      /**
       * Inicio do quadro referente ao PLANO FINANCEIRO
       */
      if (\Check::between($oStdLinha->ordem, 61, 93)) {

        if ($oStdLinha->ordem == 61) {
          $this->cabecalhoReceita('PLANO FINANCEIRO');
        }
        $this->imprimeReceita($oStdLinha);
      }

      if (\Check::between($oStdLinha->ordem, 94, 110)) {

        if ($oStdLinha->ordem == 94) {
          $this->cabecalhoDespesa();
        }

        if ($oStdLinha->ordem == 110) {
          $this->espaco(2);
        }
        $this->imprimeDespesa($oStdLinha);
      }

      if (\Check::between($oStdLinha->ordem, 111, 112)) {

        if ($oStdLinha->ordem == 111) {
          $this->cabecalhoAporte('APORTES DE RECURSOS PARA O PLANO FINANCEIRO DO RPPS');
        }
        $this->imprimeAportes($oStdLinha);
      }

    }

    $oRelatorio = new \relatorioContabil(Relatorio::CODIGO_RELATORIO, false);
    $oRelatorio->notaExplicativa($this->oPdf, $this->oRelatorio->getPeriodo()->getCodigo(), $this->oPdf->getAvailWidth());


    $this->oPdf->ln($this->oPdf->getAvailHeight() - 10);
    $oDaoAssinatura = new \cl_assinatura();
    assinaturas($this->oPdf, $oDaoAssinatura,'LRF');

    $this->oPdf->showPDF("RREO_Anexo_IV_DemonstrativoRPPS_v2017_" . time());
  }

  /**
   * @param $iAltura
   */
  private function espaco($iAltura) {
    $this->oPdf->ln($iAltura);
  }

  /**
   * @param $sTituloQuadro
   */
  private function cabecalhoReceita($sTituloQuadro) {

    $lBold    = $this->oPdf->getBold();
    $this->oPdf->setBold(true);
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, $sTituloQuadro, "TB", 1, \PDFDocument::ALIGN_CENTER, 1);
    $iLargura = $this->oPdf->getAvailWidth();
    $this->oPdf->cell($iLargura * 0.4, 8, 'RECEITAS PREVIDENCIÁRIAS - RPPS', 'TBR', 0, 'C', 1);
    $this->oPdf->cell($iLargura * 0.15, 8, 'PREVISÃO INICIAL', 1, 0, 'C', 1);
    $this->oPdf->cell($iLargura * 0.15, 8, 'PREVISÃO ATUALIZADA', 1, 0, 'C', 1);

    $iPosicaoX = $this->oPdf->getX();

    $this->oPdf->cell($iLargura * 0.3, 4, 'RECEITAS REALIZADAS', 'TLB', 1, 'C', 1);
    $this->oPdf->setX($iPosicaoX);

    $this->oPdf->cell($iLargura * 0.15, 4, 'Até o Bimestre / '.$this->oRelatorio->getAno(), 1, 0, 'C', 1);
    $this->oPdf->cell($iLargura * 0.15, 4, 'Até o Bimestre / '.($this->oRelatorio->getAno() - 1), 'TLB', 1, 'C', 1);

    $this->oPdf->setbold($lBold);
  }

  /**
   * @param \stdClass $oStdLinha
   */
  private function imprimeReceita(\stdClass $oStdLinha) {

    $iLargura = $this->oPdf->getAvailWidth();
    if ($oStdLinha->totalizar) {
      $this->oPdf->setBold(true);
    }
    $sBorda = in_array($oStdLinha->ordem, array(34, 93))  ? "TB" : '';

    $this->oPdf->cell($iLargura * 0.4, 4, (\relatorioContabil::getIdentacao($oStdLinha->nivel)) . $oStdLinha->descricao, $sBorda . 'R', 0, 'L', $this->transparente($oStdLinha->ordem));
    $this->oPdf->cell($iLargura * 0.15, 4, db_formatar($oStdLinha->prev_ini , 'f'), $sBorda . 'R', 0, 'R'  , $this->transparente($oStdLinha->ordem));
    $this->oPdf->cell($iLargura * 0.15, 4, db_formatar($oStdLinha->prev_atual , 'f'), $sBorda . 'R', 0, 'R', $this->transparente($oStdLinha->ordem));
    $this->oPdf->cell($iLargura * 0.15, 4, db_formatar($oStdLinha->rec_atebim , 'f'), $sBorda . 'R', 0, 'R', $this->transparente($oStdLinha->ordem));
    $this->oPdf->cell($iLargura * 0.15, 4, db_formatar($oStdLinha->recbiexant , 'f'), $sBorda . '', 1, 'R' , $this->transparente($oStdLinha->ordem));
    $this->oPdf->setBold(false);
  }

  /**
   * @param $iOrdem
   * @return bool
   */
  private function transparente($iOrdem) {
    return in_array($iOrdem,  array(34, 50, 51, 93, 109, 110));
  }

  /**
   * Imprime o cabeçalho da despesa
   */
  protected function cabecalhoDespesa() {

    $lUltimoPeriodo = $this->oRelatorio->getPeriodo()->getCodigo() == 11;
    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->setBold(true);
    $this->oPdf->cell($iLargura * ($lUltimoPeriodo? 0.2 : 0.4), 12, "DESPESAS PREVIDENCIÁRIAS - RPPS", 'TBR', 0, 'C', 1);
    $this->oPdf->cell($iLargura * 0.1, 12, "DOTAÇÃO INICIAL", 1, 0, 'C', 1);
    $this->oPdf->cell($iLargura * 0.1, 12, "DOTAÇÃO ATUALIZADA", 1, 0, 'C', 1);

    $iPosicaoX = $this->oPdf->getX();

    $this->oPdf->cell($iLargura * 0.2, 8, "DESPESAS EMPENHADAS", 1, 0, 'C', 1);
    $this->oPdf->cell($iLargura * 0.2, 8, "DESPESAS LIQUIDADAS", 'TLB', !$lUltimoPeriodo, 'C', 1);

    if ($lUltimoPeriodo) {
      $this->oPdf->Multicell($iLargura * 0.2, 4, "INCRITAS EM RESTOS A\nPAGAR NÃO PROCESSADOS", 'TLB', 'C', 1);
    }

    $this->oPdf->setX($iPosicaoX);

    $this->oPdf->cell($iLargura * 0.1, 4, "Até o Bimestre / {$this->oRelatorio->getAno()}", 1, 0, 'C', 1);
    $this->oPdf->cell($iLargura * 0.1, 4, "Até o Bimestre / {$this->oRelatorio->getExercicioAnterior()}", 1, 0, 'C', 1);
    $this->oPdf->cell($iLargura * 0.1, 4, "Até o Bimestre / {$this->oRelatorio->getAno()}", 1, 0, 'C', 1);
    $this->oPdf->cell($iLargura * 0.1, 4, "Até o Bimestre / {$this->oRelatorio->getExercicioAnterior()}", 'TLB', !$lUltimoPeriodo, 'C', 1);

    if ($lUltimoPeriodo) {

      $this->oPdf->cell($iLargura * 0.1, 4, "Em {$this->oRelatorio->getAno()}", 1, 0, 'C', 1);
      $this->oPdf->cell($iLargura * 0.1, 4, "Em {$this->oRelatorio->getExercicioAnterior()}", 'TLB', 1, 'C', 1);
    }

    $this->oPdf->setBold(false);
  }

  /**
   * @param \stdClass $oStdLinha
   */
  private function imprimeDespesa(\stdClass $oStdLinha) {

    if ($oStdLinha->totalizar) {
      $this->oPdf->setBold(true);
    }

    $lUltimoPeriodo = $this->oRelatorio->getPeriodo()->getCodigo() == 11;

    $iLargura         = $this->oPdf->getAvailWidth();
    $iLarguraDesricao = $iLargura * ($lUltimoPeriodo ? 0.2 : 0.4);
    $iAltura          = $this->oPdf->getMultiCellHeight($iLarguraDesricao, 4, \relatorioContabil::getIdentacao($oStdLinha->nivel) . $oStdLinha->descricao);

    $sBorda       = \Check::between($oStdLinha->ordem, 35, 51) || in_array($oStdLinha->ordem, array(109,110))  ? 'TB' : '';
    $sAlinhamento = 'R';

    $sValorLiqAteBim      = db_formatar($oStdLinha->liq_atebim, 'f');
    $sValorEmpAteBim      = db_formatar($oStdLinha->emp_atebim, 'f');
    $sValorEmpAteBimExAnt = db_formatar($oStdLinha->emp_atebimexant, 'f');

    $sValorRpNProc      = '';
    $sValorRpNProcExAnt = '';
    if ($lUltimoPeriodo) {

      $sValorRpNProc        = db_formatar(abs($oStdLinha->rp_nproc), 'f');
      $sValorRpNProcExAnt   = db_formatar(abs($oStdLinha->rp_nprocexant), 'f');
    }

    if ($oStdLinha->ordem == 51) {

      if ($oStdLinha->liq_atebim < 0) {
        $sValorLiqAteBim = '('.trim($sValorLiqAteBim).')';
      }

      $sAlinhamento         = 'C';
      $sValorEmpAteBim      = '-';
      $sValorEmpAteBimExAnt = '-';

      if ($lUltimoPeriodo) {

        $sValorRpNProc        = '-';
        $sValorRpNProcExAnt   = '-';
      }
    }

    $this->oPdf->setAutoNewLineMulticell(false);

    $this->oPdf->multiCell($iLarguraDesricao, 4, \relatorioContabil::getIdentacao($oStdLinha->nivel) . $oStdLinha->descricao, $sBorda . 'R', 'L', $this->transparente($oStdLinha->ordem));
    $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oStdLinha->dot_ini ,'f'), $sBorda . 'R', 0, 'R', $this->transparente($oStdLinha->ordem));
    $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oStdLinha->dot_atual ,'f'),$sBorda .'R' , 0, 'R', $this->transparente($oStdLinha->ordem));
    $this->oPdf->cell($iLargura * 0.1, $iAltura, $sValorEmpAteBim, $sBorda . 'R', 0, $sAlinhamento, $this->transparente($oStdLinha->ordem));
    $this->oPdf->cell($iLargura * 0.1, $iAltura, $sValorEmpAteBimExAnt, $sBorda . 'R', 0, $sAlinhamento, $this->transparente($oStdLinha->ordem));
    $this->oPdf->cell($iLargura * 0.1, $iAltura, $sValorLiqAteBim, $sBorda . 'R', 0, 'R', $this->transparente($oStdLinha->ordem));
    $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oStdLinha->liq_atebimexant,'f'), $sBorda . '', !$lUltimoPeriodo, 'R', $this->transparente($oStdLinha->ordem));

    if ($lUltimoPeriodo) {

      $this->oPdf->cell($iLargura * 0.1, $iAltura, $sValorRpNProc, $sBorda . 'RL', 0, $sAlinhamento, $this->transparente($oStdLinha->ordem));
      $this->oPdf->cell($iLargura * 0.1, $iAltura, $sValorRpNProcExAnt, $sBorda . '', 1, $sAlinhamento, $this->transparente($oStdLinha->ordem));
    }

    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->setBold(false);
  }

  /**
   * @param string    $sTitulo
   * @param \stdClass $oStdLinha
   */
  private function imprimeRecursosRPPS($sTitulo, \stdClass $oStdLinha) {

    $iLargura = $this->oPdf->getAvailWidth();
    $this->oPdf->setBold(true);
    $this->oPdf->cell($iLargura * 0.4, 4, $sTitulo, "TBR", 0, \PDFDocument::ALIGN_LEFT, true);
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, 'PREVISÃO ORÇAMENTÁRIA', "TBl", 1, \PDFDocument::ALIGN_CENTER, true);
    $this->oPdf->setBold(false);

    $this->oPdf->cell($iLargura * 0.4, 4, $oStdLinha->descricao, "TBR", 0, \PDFDocument::ALIGN_LEFT, false);
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, db_formatar($oStdLinha->valor, 'f'), "TBL", 1, \PDFDocument::ALIGN_RIGHT, false);
  }

  /**
   * @param \stdClass $oStdLinha
   */
  private function imprimeAportes(\stdClass $oStdLinha) {

    $this->oPdf->cell($this->oPdf->getAvailWidth() * 0.4, 4, $oStdLinha->descricao, "TBR", 0, \PDFDocument::ALIGN_LEFT, false);
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, db_formatar($oStdLinha->valor, 'f'), "TBL", 1, \PDFDocument::ALIGN_RIGHT, false);
  }

  /**
   * Imprime o cabeçalho para o quadro de Aporte Previdenciário
   * @param string $sTitulo
   */
  private function cabecalhoAporte($sTitulo) {

    $this->espaco(2);
    $this->oPdf->setBold(true);
    $this->oPdf->cell($this->oPdf->getAvailWidth() * 0.4, 4, $sTitulo, "TBR", 0, \PDFDocument::ALIGN_CENTER, true);
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, 'APORTES REALIZADOS', "TBl", 1, \PDFDocument::ALIGN_CENTER, true);
    $this->oPdf->setBold(false);
  }


  /**
   * Imprime o cabeçalho para o quadro Bens e Direitos
   */
  private function cabecalhoBensDireitos() {

    $iLargura = $this->oPdf->getAvailWidth();
    $this->espaco(2);
    $this->oPdf->setBold(true);
    $this->oPdf->cell($iLargura * 0.4, 8, "BENS E DIREITOS DO RPPS", 'TBR', 0, \PDFDocument::ALIGN_CENTER, 1);
    $iPosicaoX = $this->oPdf->getX();
    $this->oPdf->cell($iLargura * 0.6, 4, "PERÍODO DE REFERÊNCIA", 'TB', 1, \PDFDocument::ALIGN_CENTER, 1);
    $this->oPdf->setX($iPosicaoX);
    $this->oPdf->cell($iLargura * 0.3, 4, $this->oRelatorio->getAno(), 1, 0, \PDFDocument::ALIGN_CENTER, 1);
    $this->oPdf->cell($iLargura * 0.3, 4, $this->oRelatorio->getExercicioAnterior(), 'TB', 1, \PDFDocument::ALIGN_CENTER, 1);
    $this->oPdf->setBold(false);
  }

  /**
   * @param \stdClass $oStdLinha
   */
  private function imprimeBensDireitosRPPS(\stdClass $oStdLinha) {

    $iLargura = $this->oPdf->getAvailWidth();
    $this->oPdf->cell($iLargura * 0.4, 4, $oStdLinha->descricao, 'TBR', 0, \PDFDocument::ALIGN_LEFT);
    $this->oPdf->cell($iLargura * 0.3, 4, db_formatar($oStdLinha->vlrexatual, 'f'), 1, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell($iLargura * 0.3, 4, db_formatar($oStdLinha->vlrexanter, 'f'), 'TB', 1, \PDFDocument::ALIGN_RIGHT);
  }
}
