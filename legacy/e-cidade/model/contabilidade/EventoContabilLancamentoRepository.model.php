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
   * Classe repository para classes EventoContabilLancamento
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class EventoContabilLancamentoRepository {

    /**
     * Collection de EventoContabilLancamento
     * @var array
     */
    private $aInstancias = array();

    /**
     * Instancia da classe
     * @var EventoContabilLancamentoRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do EventoContabilLancamento pelo Codigo
     * @param integer $iCodigo Codigo do EventoContabilLancamento
     * @return EventoContabilLancamento
     */
    public static function getEventoByCodigo($iCodigo) {

      if (!array_key_exists($iCodigo, EventoContabilLancamentoRepository::getInstance()->aInstancias)) {
        EventoContabilLancamentoRepository::getInstance()->aInstancias[$iCodigo] = new EventoContabilLancamento($iCodigo);
      }
      return EventoContabilLancamentoRepository::getInstance()->aInstancias[$iCodigo];
    }

    /**
     * Retorna a instancia da classe
     * @return EventoContabilLancamentoRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new EventoContabilLancamentoRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um EventoContabilLancamento dao repositorio
     * @param EventoContabilLancamento $oEventoContabilLancamento Instancia do EventoContabilLancamento
     * @return boolean
     */
    public static function adicionarEventoContabilLancamento(EventoContabilLancamento $oEventoContabilLancamento) {

      if(!array_key_exists($oEventoContabilLancamento->getSequencialLancamento(),
                           EventoContabilLancamentoRepository::getInstance()->aInstancias)) {
        EventoContabilLancamentoRepository::getInstance()->aInstancias[$oEventoContabilLancamento->getSequencialLancamento()] = $oEventoContabilLancamento;
      }
      return true;
    }

    /**
     * Remove o EventoContabilLancamento passado como parametro do repository
     * @param EventoContabilLancamento $oEventoContabilLancamento
     * @return boolean
     */
    public static function removerEventoContabilLancamento(EventoContabilLancamento $oEventoContabilLancamento) {
       /**
        *
        */
      if (array_key_exists($oEventoContabilLancamento->getSequencialLancamento(), EventoContabilLancamentoRepository::getInstance()->aInstancias)) {
        unset(EventoContabilLancamentoRepository::getInstance()->aInstancias[$oEventoContabilLancamento->getSequencialLancamento()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalEventoContabilLancamento() {
      return count(EventoContabilLancamentoRepository::getInstance()->aInstancias);
    }
  }