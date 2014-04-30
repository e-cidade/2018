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

  /**
   * Classe repository para classes TipoTransporte
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package transporteescolar
   */
class TipoTransporteRepository {

  /**
   * Collection de TipoTransporte
   * @var array
   */
  private $aTipoTransporte = array();

  /**
   * Instancia da classe
   * @var TipoTransporteRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorno uma instancia do TipoTransporte pelo Codigo
   * @param integer $iCodigo Codigo do TipoTransporte
   * @return TipoTransporte
   */
  public static function getTipoByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, TipoTransporteRepository::getInstance()->aTipoTransporte)) {
      TipoTransporteRepository::getInstance()->aTipoTransporte[$iCodigo] = new TipoTransporte($iCodigo);
    }
    return TipoTransporteRepository::getInstance()->aTipoTransporte[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   * @return TipoTransporteRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new TipoTransporteRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona um TipoTransporte dao repositorio
   * @param TipoTransporte $oTipoTransporte Instancia do TipoTransporte
   * @return boolean
   */
  public static function adicionarTipoTransporte(TipoTransporte $oTipoTransporte) {

    if (!array_key_exists($oTipoTransporte->getCodigo(), TipoTransporteRepository::getInstance()->aTipoTransporte)) {
      TipoTransporteRepository::getInstance()->aTipoTransporte[$oTipoTransporte->getCodigo()] = $oTipoTransporte;
    }
    return true;
  }

  /**
   * Remove o TipoTransporte passado como parametro do repository
   * @param TipoTransporte $oTipoTransporte
   * @return boolean
   */
  public static function removerTipoTransporte(TipoTransporte $oTipoTransporte) {
     /**
      *
      */
    if (array_key_exists($oTipoTransporte->getCodigo(), TipoTransporteRepository::getInstance()->aTipoTransporte)) {
      unset(TipoTransporteRepository::getInstance()->aTipoTransporte[$oTipoTransporte->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna o total de cidadoes existentes no repositorio;
   * @return integer;
   */
  public static function getTotalTipoTransporte() {
    return count(TipoTransporteRepository::getInstance()->aTipoTransporte);
  }
}