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

/**
 * Representa uma coluna nos relatórios financeiros
 * Class Coluna
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF
 */
class Coluna {

  /**
   * @var int
   */
  public $w      = 0;

  /**
   * @var int
   */
  public $h      = 4;

  /**
   * @var null
   */
  public $value  = null;

  /**
   * @var string
   */
  public $border = '0';

  /**
   * @var string
   */
  public $align  = 'L';

  /**
   * @var int
   */
  public $fill   = 0;

  /**
   * @var int
   */
  public $ln     = 0;

  /**
   * @var array
   */
  private $aBordasAceitas = array(0, 1, 'TBR', 'TBL', 'TB', 'BT', 'L', 'R', 'RL', 'LR' );


  /**
   * Ver documentação fpdf
   * - http://www.fpdf.org/en/doc/cell.htm
   * - http://www.fpdf.org/en/doc/multicell.htm
   */
  public function set($w = 0, $value = null, $border = '0', $ln = 0, $align = 'L', $fill = 0, $h = 4) {

    if ( !in_array($border, $this->aBordasAceitas) ) {
      throw new \Exception("Borda informada não implementada.\nUsar: " . implode(', ', $this->aBordasAceitas));
    }

    $this->w      = $w;
    $this->h      = $h;
    $this->value  = $value;
    $this->border = $border;
    $this->align  = $align;
    $this->fill   = $fill;
    $this->ln     = $ln;
  }
}