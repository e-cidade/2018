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
 * VO para saldo do orçamento
 *
 * @package orcamento
 */
class DotacaoSaldo {

  /**
   * Saldo reservado automaticamente
   *
   * @var float
   */
  private $nReservadoAutomatico;

  /**
   * Saldo reservado manualmente
   *
   * @var float
   */
  private $nReservadoManual;

  /**
   * Define o saldo reservado automaticamente
   * @param float $nReservadoAutomatico
   */
  public function setValorReservadoAutomatico($nReservadoAutomatico) {
    $this->nReservadoAutomatico = $nReservadoAutomatico;
  }

  /**
   * Retorna o saldo reservado automaticamente
   *
   * @return float
   */
  public function getValorReservadoAutomatico() {
    return $this->nReservadoAutomatico;
  }

  /**
   * Define o saldo reservado manualmente
   * @param float $nReservadoManual
   */
  public function setValorReservadoManual($nReservadoManual) {
    $this->nReservadoManual = $nReservadoManual;
  }

  /**
   * Retorna o saldo reservado manualmente
   *
   * @return float
   */
  public function getValorReservadoManual() {
    return $this->nReservadoManual;
  }

  /**
   * Retorna o total de saldo reservado
   * @return float
   */
  public function getValorTotalReservado() {
    return $this->nReservadoManual + $this->nReservadoAutomatico;
  }

}
