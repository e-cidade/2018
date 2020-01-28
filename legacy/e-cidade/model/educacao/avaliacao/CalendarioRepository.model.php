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
   * Classe repository para classes Calendario
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class CalendarioRepository {

    /**
     * Collection de Calendario
     * @var array
     */
    private $aCalendario = array();

    /**
     * Instancia da classe
     * @var CalendarioRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do Calendario pelo Codigo
     * @param integer $iCodigo Codigo do Calendario
     * @return Calendario
     */
    public static function getCalendarioByCodigo($iCodigoCalendario) {

      if (!array_key_exists($iCodigoCalendario, CalendarioRepository::getInstance()->aCalendario)) {
        CalendarioRepository::getInstance()->aCalendario[$iCodigoCalendario] = new Calendario($iCodigoCalendario);
      }
      return CalendarioRepository::getInstance()->aCalendario[$iCodigoCalendario];
    }

    /**
     * Retorna a instancia da classe
     * @return CalendarioRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new CalendarioRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um Calendario dao repositorio
     * @param Calendario $oCalendario Instancia do Calendario
     * @return boolean
     */
    public static function adicionarCalendario(Calendario $oCalendario) {

      if(!array_key_exists($oCalendario->getCodigo(), CalendarioRepository::getInstance()->aCalendario)) {
        CalendarioRepository::getInstance()->aCalendario[$oCalendario->getCodigo()] = $oCalendario;
      }
      return true;
    }

    /**
     * Remove o Calendario passado como parametro do repository
     * @param Calendario $oCalendario
     * @return boolean
     */
    public static function removerCalendario(Calendario $oCalendario) {
       /**
        *
        */
      if (array_key_exists($oCalendario->getCodigo(), CalendarioRepository::getInstance()->aCalendario)) {
        unset(CalendarioRepository::getInstance()->aCalendario[$oCalendario->getCodigo()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalCalendario() {
      return count(CalendarioRepository::getInstance()->aCalendario);
    }
  }