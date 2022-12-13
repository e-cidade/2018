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

abstract class RelatorioLegal {

  /**
   * @var \ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoI|
   *      \ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoII|
   *      \ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoIV
   */
  protected $oAnexo;

  /**
   * @var \PDFDocument
   */
  protected $oPdf;

  /**
   * @param $oAnexo
   */
  public function setAnexo($oAnexo) {
    $this->oAnexo = $oAnexo;
  }

  /**
   * @param array $aLinhas
   */
  protected function imprimirLinhas($aLinhas) {

    foreach ($aLinhas as $oLinha) {

      if ($oLinha->lChamaMetodo) {
        $this->oAnexo->{$oLinha->sNomeMetodo}($this->oPdf);
      } else {

        //adiciona bold na linha
        if ( $oLinha->lBold ) {
          $this->oPdf->SetFont('Arial','B');
        }

        if ( $oLinha->lMultiCell ) {
          $this->imprimeMultiCell( $oLinha );
        } else {
          $this->imprimeCell( $oLinha );
        }
        //remove bold na linha
        $this->oPdf->SetFont('');
      }
    }
  }

  /**
   * Imprime uma linha com conteúdo em multi celula
   * @param $oLinha
   */
  private function imprimeMultiCell( $oLinha ) {

    /**
     * Variáveis para controle das celulas
     */
    $aAlturaLinha = array();
    foreach ($oLinha->aColunas as $oColuna) {
      $aAlturaLinha[] = $this->oPdf->NbLines($oColuna->w, $oColuna->value);
    }
    $iLinhas      = array_reduce($aAlturaLinha, "DBNumber::maiorValor");
    $iAlturaLinha = $oLinha->iAlturaLinha * $iLinhas;

    $iYAntes = $this->oPdf->getY();
    $iX      = $this->oPdf->getX();

    $aDadosBordas = array();
    foreach ($oLinha->aColunas as $oColuna) {

      $this->oPdf->SetXY($iX, $iYAntes);
      $this->oPdf->MultiCell($oColuna->w, $oColuna->h, $oColuna->value, 0, $oColuna->align, $oColuna->fill);

      // guarda os dados da impressão para desenhar as bordas depois
      $oStd            = new \stdClass();
      $oStd->tipoBorda = $oColuna->border;
      $oStd->x         = $iX;
      $oStd->w         = $oColuna->w;
      $oStd->h         = $iAlturaLinha;
      $oStd->yInicial  = $iYAntes;

      $aDadosBordas[] = $oStd;
      $iX  += $oColuna->w;
    }

    $this->imprimeBordas($aDadosBordas);
    $this->oPdf->setY($iYAntes + $iAlturaLinha);
  }

  /**
   * @param array $aDadosBordas
   */
  private function imprimeBordas($aDadosBordas) {

    foreach ($aDadosBordas as $oDados) {

      switch ($oDados->tipoBorda) {
        case 1:
          // borda em cima
          $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial );
          // borda em baixo
          $this->oPdf->line($oDados->x, $oDados->yInicial + $oDados->h, $oDados->x + $oDados->w, $oDados->yInicial + $oDados->h );
          // borda a direita
          $this->oPdf->line($oDados->x + $oDados->w, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial + $oDados->h );
          // borda a esqueda
          $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x, $oDados->yInicial + $oDados->h );
          break;

        case 'TBR':
          // borda em cima
          $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial );
          // borda em baixo
          $this->oPdf->line($oDados->x, $oDados->yInicial + $oDados->h, $oDados->x + $oDados->w, $oDados->yInicial + $oDados->h );
          // borda a direita
          $this->oPdf->line($oDados->x + $oDados->w, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial + $oDados->h );
          break;

        case 'TBL':
          // borda em cima
          $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial );
          // borda em baixo
          $this->oPdf->line($oDados->x, $oDados->yInicial + $oDados->h, $oDados->x + $oDados->w, $oDados->yInicial + $oDados->h );
          // borda a esqueda
          $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x, $oDados->yInicial + $oDados->h );
          break;

        case 'TB':
        case 'BT':
          // borda em cima
          $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial );
          // borda em baixo
          $this->oPdf->line($oDados->x, $oDados->yInicial + $oDados->h, $oDados->x + $oDados->w, $oDados->yInicial + $oDados->h );
          break;
        case 'L':
          // borda a esqueda
          $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x, $oDados->yInicial + $oDados->h );
          break;
        case 'R':
          // borda a direita
          $this->oPdf->line($oDados->x + $oDados->w, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial + $oDados->h );

          break;
        case 'RL':
        case 'LR':
          // borda a direita
          $this->oPdf->line($oDados->x, $oDados->yInicial, $oDados->x, $oDados->yInicial + $oDados->h );
          // borda a esqueda
          $this->oPdf->line($oDados->x + $oDados->w, $oDados->yInicial, $oDados->x + $oDados->w, $oDados->yInicial + $oDados->h );
          break;
      }
    }
  }

  /**
   * @param $oLinha
   */
  private function imprimeCell( $oLinha ) {

    foreach ($oLinha->aColunas as $oColuna) {
      $this->oPdf->Cell($oColuna->w, $oColuna->h, $oColuna->value, $oColuna->border, $oColuna->ln, $oColuna->align, $oColuna->fill);
    }
  }
}
