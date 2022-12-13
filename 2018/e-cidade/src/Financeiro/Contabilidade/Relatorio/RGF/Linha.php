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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Coluna;

/**
 * Representa o conteúdo de uma linha nos Relatórios legais
 * Uma linha pode realizar uma chamada de metodo. Esse metodo sempre vai receber uma instancia de PDF.
 * O uso atual foi para realizar impressão dos cabeçalhos/paragrafos e assinaturas.
 *
 * Considerações
 * - Para linhas que seja necessário utilizar MultiCell, é necessário chamar o metodo multicell(true)
 * - Não foi implementado o Fill de linhas MultiCell
 *
 * Class Linha
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF
 */
class Linha {

  /**
   * @var bool
   */
  public $lChamaMetodo = false;

  /**
   * @var null
   */
  public $sNomeMetodo  = null;

  /**
   * @var array
   */
  public $aColunas     = array();

  /**
   * @var bool
   */
  public $lMultiCell   = false;

  /**
   * @var bool
   */
  public $lBold        = false;

  /**
   * @var int
   */
  public $iAlturaLinha = 4;

  /**
   * @param int    $w
   * @param null   $value
   * @param string $border
   * @param int    $ln
   * @param string $align
   * @param int    $fill
   * @param int    $h
   *
   * @return $this
   */
  public function addColuna($w = 0, $value = null, $border = '1', $ln = 0, $align = 'L', $fill = 0,  $h = 4) {

    $oColuna = new Coluna();
    $oColuna->set($w, $value, $border, $ln, $align, $fill, $h);
    $this->aColunas[] = $oColuna;
    return $this;
  }

  /**
   * Informa se a linha será impressa com multicell
   * @param  boolean $lMultiCell
   * @return Linha
   */
  public function multicell($lMultiCell) {
    $this->lMultiCell = $lMultiCell;
    return $this;
  }

  /**
   * Informa se a linha deverá ser impressa em negrito
   * @param  boolean $lBold
   * @return Linha
   */
  public function bold($lBold) {
    $this->lBold = $lBold;
    return $this;
  }

  /**
   * Informa se a altura da linha... usado só em celulas MultiCell por enquanto
   * @param  integer  $iAltura
   * @return Linha
   */
  public function alturaLinha($iAltura = 4) {
    $this->iAlturaLinha = $iAltura;
  }

  /**
   * Informa um metodo a ser executado
   * @param  string $sNomeMetodo metodo a ser executado
   */
  public function informaMetodo( $sNomeMetodo ) {

    $this->lChamaMetodo = true;
    $this->sNomeMetodo  = $sNomeMetodo;
  }
}