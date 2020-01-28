<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 * Classe  ValueObject para controle de movimentacoes de Items
 * Possui valores de movimentacao no periodo, e saldos anteriores
 * @package Estoque
 */
class MovimentacaoItem {

  /**
   * Instancia do item
   * @var Item
   */
  protected  $oItem;

  /**
   * Quantidade movimentada
   * @var float
   */
  protected $nQuantidade;

  /**
   * Valor de entrada movimentado
   * @var float
   */
  protected $nValorEntrada;

  /**
   * Valor de entrada movimentado
   * @var float
   */
  protected $nValorSaida;

  /**
   * Valor anterior a data
   * @var float
   */
  protected $nValorAnterior;

  /**
   * Valor da movimentação
   * @var float
   */
  protected $nValor;

  /**
   * Quantidade anterior a data da movimentacao
   * @var float
   */
  protected $nQuantidadeAnterior;

  /**
   * Tipo de Movimentacao Realizada
   * @var TipoMovimentacaoEstoque
   */
  protected $oTipoMovimentacao;

  /**
   * Data da Movimentacao;
   * @var DBDate
   */
  protected $oData;

  /**
   * Hora da Movimentacao
   * @var string
   */
  protected $sHora;

  /**
   * Armazena a quantidade de saida de um item
   * @var float
   */
  protected $nQuantidadeSaida;

  /**
   * Armazena a quantidade de entrada de um item
   * @var float
   */
  protected $nQuantidadeEntrada;

  /**
   * Almoxarifado do Item
   * @var Almoxarifado
   */
  protected $oAlmoxarifado;

  /**
   * @param Item $oItem
   */
  public function __construct(Item $oItem) {
    $this->oItem = $oItem;
  }

  /**
   * Define a quantidade de movimentacao do item no periodo
   * @param float $nQuantidade Quantidade movimentada
   */
  public function setQuantidade($nQuantidade) {
    $this->nQuantidade = (float) $nQuantidade;
  }

  /**
   * Retorna a quantidade movimentada do item
   * @return float quantidade movimentada
   */
  public function getQuantidade() {
    return $this->nQuantidade;
  }

  /**
   * Quantidade anterior do item
   * @param float $nQuantidadeAnterior quantidade anterior
   */
  public function setQuantidadeAnterior($nQuantidadeAnterior) {
    $this->nQuantidadeAnterior = (float) $nQuantidadeAnterior;
  }

  /**
   * Retorna a quantidade anterior do item
   * @return float quantidade movimentada anterior
   */
  public function getQuantidadeAnterior() {
    return $this->nQuantidadeAnterior;
  }

  /**
   * Valor movimentado de entrada no periodo
   * @param float $nValorEntrada
   */
  public function setValorEntrada($nValorEntrada) {
    $this->nValorEntrada = (float) $nValorEntrada;
  }

  /**
   * Retorna o valor movimentado entrada no periodo
   * @return float valor movimentado
   */
  public function getValorEntrada() {
    return $this->nValorEntrada;
  }

  /**
   * Valor movimentado de saida no periodo
   * @param float $nValorSaida
   */
  public function setValorSaida($nValorSaida) {
    $this->nValorSaida = (float) $nValorSaida;
  }

  /**
   * Retorna valor de saida no periodo
   * @return float
   */
  public function getValorSaida() {
    return $this->nValorSaida;
  }

  /**
   * Valor total movimentado anterior a data de movimentacao
   * @param float $nValorAnterior Valor movimentado
   */
  public function setValorAnterior($nValorAnterior) {
    $this->nValorAnterior = (float) $nValorAnterior;
  }

  /**
   * Retorna o valor movimentado anterior a data de movimentação
   * @return float
   */
  public function getValorAnterior() {
    return $this->nValorAnterior;
  }

  /**
   * Data de movimentacao do item
   * @param DBDate $oData Instancia de DBDate
   */
  public function setData(DBdate $oData) {
    $this->oData = $oData;
  }

  /**
   * Retorna a data de movimentacao
   * @return DBDate

   */
  public function getData() {
    return $this->oData;
  }

  /**
   * Retorna o item
   * @return Item
   */
  public function getItem() {
    return $this->oItem;
  }

  /**
   * Tipo da movimentacao Realizada
   * @param TipoMovimentacaoEstoque $oTipoMovimentacao
   */
  public function setTipoMovimentacao(TipoMovimentacaoEstoque $oTipoMovimentacao) {
    $this->oTipoMovimentacao = $oTipoMovimentacao;
  }

  /**
   * Retorna o tipo da movimentacao
   * @return TipoMovimentacaoEstoque
   */
  public function getTipoMovimentacao() {
    return $this->oTipoMovimentacao;
  }

  /**
   * HOra da movimentacao
   * @param string $sHora hora da movimentacao
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * Retorna a hora da movimentacao
   * @return string
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * Seta a quantidade de saída
   * @param $nQuantidadeSaida
   */
  public function setQuantidadeSaida($nQuantidadeSaida) {
    $this->nQuantidadeSaida = (float) $nQuantidadeSaida;
  }

  /**
   * Retorna a quantidade de saída.
   * @return float
   */
  public function getQuantidadeSaida() {
    return $this->nQuantidadeSaida;
  }

  /**
   * Seta a quantidade de entrada
   * @param $nQuantidadeEntrada
   */
  public function setQuantidadeEntrada($nQuantidadeEntrada) {
    $this->nQuantidadeEntrada = (float) $nQuantidadeEntrada;
  }

  /**
   * Retorna a quantidade de entrada.
   * @return float
   */
  public function getQuantidadeEntrada() {
    return $this->nQuantidadeEntrada;
  }

  /**
   * Seta um almoxarifado
   * @param Almoxarifado $oAlmoxarifado
   */
  public function setAlmoxarifado(Almoxarifado $oAlmoxarifado) {
    $this->oAlmoxarifado = $oAlmoxarifado;
  }

  /**
   * Retorna o almoxarifado do item
   * @return Almoxarifado
   */
  public function getAlmoxarifado() {
    return $this->oAlmoxarifado;
  }

  /**
   * Seta o valor da movimentação
   * @param $nValor
   */
  public function setValor($nValor) {
    $this->nValor = (float)$nValor;
  }

  /**
   * Retorna o valor da movimentação
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }
}