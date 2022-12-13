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
   * Classe repository para classes EventoContabil
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class EventoContabilRepository {

    /**
     * Collection de EventoContabil
     * @var array
     */
    private $aInstancia = array();

    /**
     * Instancia da classe
     * @var EventoContabilRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do EventoContabil pelo Codigo
     * @param integer $iCodigo Codigo do EventoContabil
     * @return EventoContabil
     */
    public static function getEventoContabilByCodigo($iCodigoDocumento = null, $iAno = null, $iInstituicao = null) {

      $sHash = "{$iCodigoDocumento}{$iAno}{$iInstituicao}";
      if (!array_key_exists($sHash, EventoContabilRepository::getInstance()->aInstancia)) {
        EventoContabilRepository::getInstance()->aInstancia[$sHash] = new EventoContabil($iCodigoDocumento, $iAno, $iInstituicao);
      }
      return EventoContabilRepository::getInstance()->aInstancia[$sHash];
    }

    /**
     * Retorna a instancia da classe
     * @return EventoContabilRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new EventoContabilRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um EventoContabil dao repositorio
     * @param EventoContabil $oEventoContabil Instancia do EventoContabil
     * @return boolean
     */
    public static function adicionarEventoContabil(EventoContabil $oEventoContabil) {

      $sHash = "{$oEventoContabil->getCodigoDocumento()}{$oEventoContabil->getAnoUso()}";
      if (!array_key_exists($sHash, EventoContabilRepository::getInstance()->aInstancia)) {
        EventoContabilRepository::getInstance()->aInstancia[$sHash] = $oEventoContabil;
      }
      return true;
    }

    /**
     * Remove o EventoContabil passado como parametro do repository
     * @param EventoContabil $oEventoContabil
     * @return boolean
     */
    public static function removerEventoContabil(EventoContabil $oEventoContabil) {

      $sHash = "{$oEventoContabil->getCodigoDocumento()}{$oEventoContabil->getAnoUso()}";
      if (array_key_exists($sHash, EventoContabilRepository::getInstance()->aInstancia)) {
        unset(EventoContabilRepository::getInstance()->aInstancia[$sHash]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalEventoContabil() {
      return count(EventoContabilRepository::getInstance()->aInstancia);
    }
  }
