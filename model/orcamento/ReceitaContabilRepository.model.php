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
   * Classe repository para classes ReceitaContabil
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */

  class ReceitaContabilRepository {

    /**
     * Collection de ReceitaContabil
     * @var array
     */
    private $aReceitas = array();

    /**
     * Instancia da classe
     * @var ReceitaContabilRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do ReceitaContabil pelo Codigo
     * @param integer $iCodigo Codigo do ReceitaContabil
     * @return ReceitaContabil
     */
    public static function getReceitaByCodigo($iCodigo, $iAno) {

      $iIndiceReceita = "{$iCodigo}{$iAno}";
      if (!array_key_exists($iIndiceReceita, ReceitaContabilRepository::getInstance()->aReceitas)) {
        ReceitaContabilRepository::getInstance()->aReceitas[$iIndiceReceita] = new ReceitaContabil($iCodigo, $iAno);
      }
      return ReceitaContabilRepository::getInstance()->aReceitas[$iIndiceReceita];
    }

    /**
     * Retorna a instancia da classe
     * @return ReceitaContabilRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new ReceitaContabilRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um ReceitaContabil dao repositorio
     * @param ReceitaContabil $oReceitaContabil Instancia do ReceitaContabil
     * @return boolean
     */
    public static function adicionarReceitaContabil(ReceitaContabil $oReceitaContabil) {

      $iIndiceReceita = "{$oReceitaContabil->getCodigo()}{$oReceitaContabil->getAno()}";
      if (!array_key_exists($iIndiceReceita, ReceitaContabilRepository::getInstance()->aReceitas)) {
        ReceitaContabilRepository::getInstance()->aReceitas[$iIndiceReceita] = $oReceitaContabil;
      }
      return true;
    }

    /**
     * Remove o ReceitaContabil passado como parametro do repository
     * @param ReceitaContabil $oReceitaContabil
     * @return boolean
     */
    public static function removerReceitaContabil(ReceitaContabil $oReceitaContabil) {

      $iIndiceReceita = "{$oReceitaContabil->getCodigo()}{$oReceitaContabil->getAno()}";
      if (array_key_exists($iIndiceReceita, ReceitaContabilRepository::getInstance()->aReceitas)) {
        unset(ReceitaContabilRepository::getInstance()->aReceitas[$iIndiceReceita]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalReceitaContabil() {
      return count(ReceitaContabilRepository::getInstance()->aReceitas);
    }
  }