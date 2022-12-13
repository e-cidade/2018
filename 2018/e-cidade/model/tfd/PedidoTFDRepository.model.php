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
define( 'MENSAGENS_PEDIDOTFD_REPOSITORY', 'saude.tfd.PedidoTFDRepository.' );

/**
 * Repository para PedidoTFD
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package tfd
 */
class PedidoTFDRepository {
	
  private $aPedidoTFD = array();
  private static $oInstance;
  
  private function __construct() {}
  
  private function __clone() {}
  
  /**
   * Retorna a instância do Repositorio
   * @return PedidoTFDRepository
   */
  protected static function getInstance() {
  	
    if (self::$oInstance == null) {
    	self::$oInstance = new PedidoTFDRepository();
    }
    return self::$oInstance;
  }
  
  /**
   * Retorna a instância de PedidoTFD, adicionando a mesma ao repositório, caso não exista
   * @param integer $iCodigo
   * @return PedidoTFD
   */
  public static function getPedidoTFDByCodigo($iCodigo) {
  	
    if (!array_key_exists($iCodigo, PedidoTFDRepository::getInstance()->aPedidoTFD)) {
      PedidoTFDRepository::getInstance()->aPedidoTFD[$iCodigo] = new PedidoTFD($iCodigo);
    }

    return PedidoTFDRepository::getInstance()->aPedidoTFD[$iCodigo];
  }
  
  /**
   * Remove uma instância de PedidoTFD do repository
   * @param PedidoTFD $oPedidoTFD
   * @return boolean
   */
  public static function removerPedidoTFD(PedidoTFD $oPedidoTFD) {
  	
    if ( array_key_exists($oPedidoTFD->getCodigo(), PedidoTFDRepository::getInstance()->aPedidoTFD) ) {
    	unset(PedidoTFDRepository::getInstance()->aPedidoTFD[$oPedidoTFD->getCodigo()]);
    }

    return true;
  }

  /**
   * Busca os pedidos com base nos filtros informados
   *
   * @param $oFiltros
   *        => oDataInicio: instância de DBDate para validação da data de saída
   *        => oDataFim: instância de DBDate com o limite da data de saída
   *        => iDestino: Código do destino da prestadora
   * @return PedidoTFD[]
   * @throws DBException
   * @throws ParameterException
   */
  public static function buscaPedidos( $oFiltros ) {

    if( !$oFiltros->oDataInicio instanceof DBDate ) {
      throw new ParameterException( _M( MENSAGENS_PEDIDOTFD_REPOSITORY . 'data_inicio_invalida' ) );
    }

    if( !$oFiltros->oDataFim instanceof DBDate ) {
      throw new ParameterException( _M( MENSAGENS_PEDIDOTFD_REPOSITORY . 'data_fim_invalida' ) );
    }

    $oDaoPedidoTFD = new cl_tfd_pedidotfd();
    $aWherePedido  = array();

    if( !empty( $oFiltros->oDataInicio ) && !empty( $oFiltros->oDataFim ) ) {

      $sDataInicio    = $oFiltros->oDataInicio->getDate( DBDate::DATA_EN );
      $sDataFim       = $oFiltros->oDataFim->getDate( DBDate::DATA_EN );
      $aWherePedido[] = "( tf17_d_datasaida between '{$sDataInicio}' and '{$sDataFim}' )";
    }

    if( !empty( $oFiltros->iDestino ) ) {
      $aWherePedido[] = "tf25_i_destino = {$oFiltros->iDestino}";
    }

    $sWherePedido = implode( ' AND ', $aWherePedido );
    $sSqlPedido   = $oDaoPedidoTFD->sql_query_pedido_saida( null, 'distinct tf01_i_codigo', null, $sWherePedido );
    $rsPedido     = db_query( $sSqlPedido );

    if( !is_resource( $rsPedido ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_PEDIDOTFD_REPOSITORY . 'erro_buscar_pedido', $oErro ) );
    }

    $iTotalPedidos = pg_num_rows( $rsPedido );
    $aPedidosTFD   = array();

    for( $iContador = 0; $iContador < $iTotalPedidos; $iContador++ ) {
      $aPedidosTFD[] = self::getPedidoTFDByCodigo( db_utils::fieldsMemory( $rsPedido, $iContador )->tf01_i_codigo );
    }

    return $aPedidosTFD;
  }
}