<?php
/**
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

/**
 * Class VeiculoManutencaoValor
 * V.O. para os valores de uma manutenção de veículo.
 */
class VeiculoManutencaoValor {

  /**
   * @type float
   */
  private $nValorMaoDeObra;

  /**
   * @type float
   */
  private $nValorPecas;

  /**
   * @type float
   */
  private $nValorLavagem;

  /**
   * @return float
   */
  public function getValorMaoDeObra() {
    return $this->nValorMaoDeObra;
  }

  /**
   * @param float $nValorMaoDeObra
   */
  public function setValorMaoDeObra($nValorMaoDeObra) {
    $this->nValorMaoDeObra = $nValorMaoDeObra;
  }

  /**
   * @return float
   */
  public function getValorPecas() {
    return $this->nValorPecas;
  }

  /**
   * @param float $nValorPecas
   */
  public function setValorPecas($nValorPecas) {
    $this->nValorPecas = $nValorPecas;
  }

  /**
   * @return float
   */
  public function getValorLavagem() {
    return $this->nValorLavagem;
  }

  /**
   * @param float $nValorLavagem
   */
  public function setValorLavagem($nValorLavagem) {
    $this->nValorLavagem = $nValorLavagem;
  }

  /**
   * @return float
   */
  public function getValorTotal() {
    return ($this->nValorLavagem+$this->nValorPecas+$this->nValorMaoDeObra);
  }
}