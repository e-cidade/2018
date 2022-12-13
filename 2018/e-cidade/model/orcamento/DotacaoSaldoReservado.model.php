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

/**
 * VO para a reserva de saldo do orçamento
 *
 * @package orcamento
 */
class DotacaoSaldoReservado {

  /**
   * Saldo reservado automaticamente
   *
   * @var float
   */
  private $nAutomatico;

  /**
   * Saldo reservado manualmente
   *
   * @var float
   */
  private $nManual;

  /**
   * Define o saldo reservado automaticamente
   *
   * @param float $nAutomatico
   */
  public function setAutomatico($nAutomatico) {
    $this->nAutomatico = $nAutomatico;
  }

  /**
   * Retorna o saldo reservado automaticamente
   *
   * @return float
   */
  public function getAutomatico() {
    return $this->nAutomatico;
  }

  /**
   * Define o saldo reservado manualmente
   *
   * @param float $nManual
   */
  public function setManual($nManual) {
    $this->nManual = $nManual;
  }

  /**
   * Retorna o saldo reservado manualmente
   *
   * @return float
   */
  public function getManual() {
    return $this->nManual;
  }

  /**
   * Retorna o total de saldo reservado
   *
   * @return float
   */
  public function getTotal() {
    return $this->nManual + $this->nAutomatico;
  }

}
