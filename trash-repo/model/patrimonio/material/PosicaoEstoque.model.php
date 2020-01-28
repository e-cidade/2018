<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

class PosicaoEstoque {
  
  /**
  * C�digo sequencial da posi��o
  * @var integer
  */
  private $iCodigo;

  /**
  * C�digo sequencial do processamento
  * @var integer
  */
  private $iCodigoProcessamento;

  /**
  * C�digo sequencial do material no estoque
  * @var integer
  */
  private $iCodigoMaterialEstoque;
  
  /**
  * Quantidade total do item
  * @var numeric
  */
  private $nQuantidade;

  /**
  * Valor total do item
  * @var numeric
  */
  private $nValor;

  /**
  * Pre�o M�dio do item
  * @var numeric
  */
  private $nPrecoMedio;

  /**
  * Movimenta��es relacionadas ao item
  * @var array
  */
  private $aMovimentacoes = array();

  /**
   * Constr�i o objeto de acordo com o c�digo informado no par�metro
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (! empty($this->iCodigo)) {
      
      $oDaoPosicaoEstoque = db_utils::getDao('posicaoestoque');
      $sSqlBuscaItem = $oDaoPosicaoEstoque->sql_query_file($this->iCodigo);
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
      $this->nPrecoMedio            = $oStdPosicaoEstoque->m06_precomedio;
      unset($oStdPosicaoEstoque);
    }
  }

  /**
   * Getter C�digo sequencial
   * @return integer
   */
  public function getCodigo () {
    return $this->iCodigo; 
  }
  
  /**
   * Setter C�digo sequencial do processamento
   * @param integer
   */
  public function setCodigoProcessamento ($iCodigo) {
    $this->iCodigoProcessamento = $iCodigo;
  }
  
  /**
   * Getter C�digo sequencial do processamento
   * @return integer
   */
  public function getCodigoProcessamento () {
    return $this->iCodigoProcessamento; 
  }
  
  /**
   * Setter C�digo do Estoque
   * @param integer
   */
  public function setCodigoMaterialEstoque ($iCodigoMaterialEstoque) {
    $this->iCodigoMaterialEstoque = $iCodigoMaterialEstoque;
  }
  
  /**
   * Getter C�digo do Estoque
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
   * @return numeric
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
   * @return numeric
   */
  public function getValor () {
    return $this->nValor; 
  }
  
  /**
   * Setter Pre�o M�dio do item
   * @param numeric
   */
  public function setPrecoMedio ($nPrecoMedio) {
    $this->nPrecoMedio = $nPrecoMedio;
  }
  
  /**
   * Getter Pre�o M�dio do item
   * @return numeric
   */
  public function getPrecoMedio () {
    return $this->nPrecoMedio; 
  }

  public function setCodigoMovimentacoes(array $aCodigosMovimentacoes) {
    $this->aMovimentacoes = $aCodigosMovimentacoes;
  }

  /** 
   * M�todo respons�vel por salvar os dados de uma nova posi��o do estoque
   * @return true
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
   * M�todo que vincula a posi��o no estoque com as movimenta��es relacionadas
   * @return true
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
    * Busca a posi��o do �ltimo processamento
    * @param $iCodigoEstoque integer
    * @return PosicaoEstoque
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
   * Clona o objeto limpando as propriedades iCodigo e iCodigoProcessamento
   * - iCodigo              - C�digo sequencial
   * - iCodigoProcessamento - C�digo do Processamento
   */
  public function __clone() {
    
    $this->iCodigo              = null;
    $this->iCodigoProcessamento = null;
  }

}