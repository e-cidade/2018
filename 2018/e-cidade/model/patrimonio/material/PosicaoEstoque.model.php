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


define('URL_MENSAGEM_POSICAOESTOQUE', 'patrimonial.material.PosicaoEstoque.');

/**
 * Class PosicaoEstoque
 */
class PosicaoEstoque {

  /**
  * Código sequencial da posição
  * @var integer
  */
  private $iCodigo;

  /**
  * Código sequencial do processamento
  * @var integer
  */
  private $iCodigoProcessamento;

  /**
  * Código sequencial do material no estoque
  * @var integer
  */
  private $iCodigoMaterialEstoque;

  /**
  * Quantidade total do item
  * @var float
  */
  private $nQuantidade;

  /**
  * Valor total do item
  * @var float
  */
  private $nValor;

  /**
  * Preço Médio do item
  * @var float
  */
  private $nPrecoMedio;

  /**
  * Movimentações relacionadas ao item
  * @var array
  */
  private $aMovimentacoes = array();

  /**
   * Data do processamento
   * @var DBDate
   */
  private $oDataPosicao;

  /**
   * Constrói o objeto de acordo com o código informado no parâmetro
   * @param integer $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (! empty($this->iCodigo)) {

      $oDaoPosicaoEstoque = db_utils::getDao('posicaoestoque');
      $sSqlBuscaItem = $oDaoPosicaoEstoque->sql_query_estoque($this->iCodigo);
      $rsBuscaItem = $oDaoPosicaoEstoque->sql_record($sSqlBuscaItem);

      if ($oDaoPosicaoEstoque->erro_status == '0') {
        throw new BusinessException (_M(URL_MENSAGEM_POSICAOESTOQUE.'erro_busca_posicaoestoque'));
      }

      $oStdPosicaoEstoque           = db_utils::fieldsMemory($rsBuscaItem, 0);
      $this->iCodigo                = $oStdPosicaoEstoque->m06_sequencial;
      $this->iCodigoProcessamento   = $oStdPosicaoEstoque->m06_posicaoestoqueprocessamento;
      $this->iCodigoMaterialEstoque = $oStdPosicaoEstoque->m06_matestoque;
      $this->nQuantidade            = $oStdPosicaoEstoque->m06_quantidade;
      $this->nValor                 = $oStdPosicaoEstoque->m06_valor;
      $this->oDataPosicao           = new DBDate($oStdPosicaoEstoque->m05_data);
      $this->nPrecoMedio            = $oStdPosicaoEstoque->m06_precomedio;
      unset($oStdPosicaoEstoque);
    }
  }

  /**
   * Getter Código sequencial
   * @return integer
   */
  public function getCodigo () {
    return $this->iCodigo;
  }

  /**
   * Setter Código sequencial do processamento
   * @param integer
   */
  public function setCodigoProcessamento ($iCodigo) {
    $this->iCodigoProcessamento = $iCodigo;
  }

  /**
   * Getter Código sequencial do processamento
   * @return integer
   */
  public function getCodigoProcessamento () {
    return $this->iCodigoProcessamento;
  }

  /**
   * Setter Código do Estoque
   * @param integer
   */
  public function setCodigoMaterialEstoque ($iCodigoMaterialEstoque) {
    $this->iCodigoMaterialEstoque = $iCodigoMaterialEstoque;
  }

  /**
   * Getter Código do Estoque
   * @return integer
   */
  public function getCodigoMaterialEstoque () {
    return $this->iCodigoMaterialEstoque;
  }

  /**
   * Setter Quantidade total do item
   * @param numeric
   */
  public function setQuantidade ($nQuantidade) {
    $this->nQuantidade = $nQuantidade;
  }

  /**
   * Getter Quantidade total do item
   * @return float
   */
  public function getQuantidade () {
    return $this->nQuantidade;
  }

  /**
   * Setter Valor total do item
   * @param numeric
   */
  public function setValor ($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * Getter Valor total do item
   * @return float
   */
  public function getValor () {
    return $this->nValor;
  }

  /**
   * Setter Preço Médio do item
   * @param float
   */
  public function setPrecoMedio ($nPrecoMedio) {
    $this->nPrecoMedio = $nPrecoMedio;
  }

  /**
   * Getter Preço Médio do item
   * @return float
   */
  public function getPrecoMedio () {
    return $this->nPrecoMedio;
  }

  /**
   * @param array $aCodigosMovimentacoes
   */
  public function setCodigoMovimentacoes(array $aCodigosMovimentacoes) {
    $this->aMovimentacoes = $aCodigosMovimentacoes;
  }

  /**
   * Método responsável por salvar os dados de uma nova posição do estoque
   * @return true
   * @throws BusinessException
   */
  public function salvar() {

    $oDaoPosicaoEstoque                                  = db_utils::getDao('posicaoestoque');
    $oDaoPosicaoEstoque->m06_sequencial                  = $this->iCodigo;
    $oDaoPosicaoEstoque->m06_posicaoestoqueprocessamento = $this->iCodigoProcessamento;
    $oDaoPosicaoEstoque->m06_matestoque                  = $this->iCodigoMaterialEstoque;
    $oDaoPosicaoEstoque->m06_quantidade                  = "{$this->nQuantidade}";
    $oDaoPosicaoEstoque->m06_valor                       = "{$this->nValor}";
    $oDaoPosicaoEstoque->m06_precomedio                  = "{$this->nPrecoMedio}";

    if (! empty($this->iCodigo)) {
      $oDaoPosicaoEstoque->alterar($this->iCodigo);
    } else {

      $oDaoPosicaoEstoque->incluir(null);
      $this->iCodigo = $oDaoPosicaoEstoque->m06_sequencial;
    }

    if ($oDaoPosicaoEstoque->erro_status == "0") {
      throw new BusinessException (_M(URL_MENSAGEM_POSICAOESTOQUE.'erro_salvar_posicaoestoque'));
    }

    $this->vincularMovimentacoesNaPosicao();

    return true;
  }

  /**
   * Método que vincula a posição no estoque com as movimentações relacionadas
   * @return true
   * @throws BusinessException
   */
  private function vincularMovimentacoesNaPosicao() {

    $oDaoPosicaoEstoqueMovimentacao = db_utils::getDao('posicaoestoquematestoqueinimei');
    $oDaoPosicaoEstoqueMovimentacao->excluir(null, "m07_posicaoestoque = {$this->iCodigo}");

    foreach ($this->aMovimentacoes as $iCodigoMovimentacao) {

      $oDaoPosicaoEstoqueMovimentacao->m07_sequencial       = null;
      $oDaoPosicaoEstoqueMovimentacao->m07_posicaoestoque   = $this->iCodigo;
      $oDaoPosicaoEstoqueMovimentacao->m07_matestoqueinimei = $iCodigoMovimentacao;
      $oDaoPosicaoEstoqueMovimentacao->incluir(null);

      if ($oDaoPosicaoEstoqueMovimentacao->erro_status == "0") {
        throw new BusinessException (_M(URL_MENSAGEM_POSICAOESTOQUE.'erro_vincular_movimentacao'));
      }
    }
    return true;
  }

  /**
   * Busca a posição do último processamento
   * @param $iCodigoEstoque integer
   * @param $dtProcessamento string
   * @return PosicaoEstoque
   * @throws BusinessException
   */
  public static function getUltimaPosicaoEstoque($iCodigoEstoque, $dtProcessamento) {

    $oDaoPosicaoEstoque = db_utils::getDao('posicaoestoque');
    $sWhereEstoque      = "m06_matestoque = {$iCodigoEstoque} and m05_data < '{$dtProcessamento}' order by m05_data desc limit 1";
    $sSqlBuscaPosicao   = $oDaoPosicaoEstoque->sql_query(null, "m06_sequencial", null, $sWhereEstoque);
    $rsBuscaPosicao     = $oDaoPosicaoEstoque->sql_record($sSqlBuscaPosicao);

    if ($oDaoPosicaoEstoque->erro_status == "0") {
      return false;
    }

    $oStdPosicaoEstoque = db_utils::fieldsMemory($rsBuscaPosicao, 0);
    return new PosicaoEstoque($oStdPosicaoEstoque->m06_sequencial);
  }

  /**
   * Busca a ultima posição calculada do posição do item atraves do seu estoque
   * @param Item         $oItem instancia do item
   * @param Almoxarifado $oAlmoxarifado instancia do almoxarifado
   * @param DBDate       $oData data base para pesquisa
   * @return bool|PosicaoEstoque instancia com a posiçao calculada
   */
  public static function getUltimaPosicaoDoItemNoEstoque(Item $oItem, Almoxarifado $oAlmoxarifado, DBDate $oData) {

    $oDaoPosicaoEstoque = db_utils::getDao('posicaoestoque');
    $sWhereEstoque      = "m70_codmatmater = {$oItem->getCodigo()} and m05_data < '{$oData->getDate()}' ";
    $sWhereEstoque     .= " and m70_coddepto = {$oAlmoxarifado->getCodigo()}";
    $sWhereEstoque     .= "order by m05_data desc limit 1";

    $sSqlBuscaPosicao   = $oDaoPosicaoEstoque->sql_query_estoque(null, "m06_sequencial", null, $sWhereEstoque);
    $rsBuscaPosicao     = $oDaoPosicaoEstoque->sql_record($sSqlBuscaPosicao);

    if ($oDaoPosicaoEstoque->erro_status == "0") {
      return false;
    }

    $oStdPosicaoEstoque = db_utils::fieldsMemory($rsBuscaPosicao, 0);
    return new PosicaoEstoque($oStdPosicaoEstoque->m06_sequencial);
  }

  /**
   * Clona o objeto limpando as propriedades iCodigo e iCodigoProcessamento
   * - iCodigo              - Código sequencial
   * - iCodigoProcessamento - Código do Processamento
   */
  public function __clone() {

    $this->iCodigo              = null;
    $this->iCodigoProcessamento = null;
  }

  public function getDataDaPosicao() {
    return $this->oDataPosicao;
  }

}