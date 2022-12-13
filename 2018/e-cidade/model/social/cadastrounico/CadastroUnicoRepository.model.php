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
   * Classe repository para classes CadastroUnico
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package 
   */
  class CadastroUnicoRepository {
    
    /** 
     * Collection de CadastroUnico 
     * @var array
     */    
    private $aCadastroUnico = array();
    
    /**
     * Instancia da classe 
     * @var CadastroUnicoRepository
     */
    private static $oInstance;
    
    private function __construct() {
      
    }
    private function __clone() {
     
    }
 
    /**
     * Retorno uma instancia do CadastroUnico pelo Codigo
     * @param integer $iCodigo Codigo do CadastroUnico
     * @return CadastroUnico
     */
    public static function getCadastroUnicoByCodigo($iCodigoCadastroUnico) {
      
      if (!array_key_exists($iCodigoCadastroUnico, CadastroUnicoRepository::getInstance()->aCadastroUnico)) {
        CadastroUnicoRepository::getInstance()->aCadastroUnico[$iCodigoCadastroUnico] = new CadastroUnico($iCodigoCadastroUnico);
      }
      return CadastroUnicoRepository::getInstance()->aCadastroUnico[$iCodigoCadastroUnico];
    } 
    
    /**
     * Retorna a instancia da classe
     * @return CadastroUnicoRepository
     */
    protected static function getInstance() {
      
      if (self::$oInstance == null) {
              
        self::$oInstance = new CadastroUnicoRepository();     
      }
      return self::$oInstance;
    }
    
    /**
     * Adiciona um CadastroUnico dao repositorio
     * @param CadastroUnico $oCadastroUnico Instancia do CadastroUnico
     * @return boolean
     */
    public static function adicionarCadastroUnico(CadastroUnico $oCadastroUnico) {
    
      if(!array_key_exists($oCadastroUnico->getCodigoCadastroUnico(), CadastroUnicoRepository::getInstance()->aCadastroUnico)) {
        CadastroUnicoRepository::getInstance()->aCadastroUnico[$oCadastroUnico->getCodigoCadastroUnico()] = $oCadastroUnico;
      }
      return true;
    }
    
    /**
     * Remove o CadastroUnico passado como parametro do repository
     * @param CadastroUnico $oCadastroUnico
     * @return boolean 
     */ 
    public static function removerCadastroUnico(CadastroUnico $oCadastroUnico) {
       /**
        * 
        */ 
      if (array_key_exists($oCadastroUnico->getCodigoCadastroUnico(), CadastroUnicoRepository::getInstance()->aCadastroUnico)) {
        unset(CadastroUnicoRepository::getInstance()->aCadastroUnico[$oCadastroUnico->getCodigoCadastroUnico()]);
      }
      return true;
    }
    
    /** 
     * Retorna o total de cidadoes existentes no repositorio; 
     * @return integer; 
     */
    public static function getTotalCadastroUnico() {
      return count(CadastroUnicoRepository::getInstance()->aCadastroUnico);
    }
    
    public static function getCadastroUnicoByNis($iNis) {
      
      $oDaoCadastroUnico = db_utils::getDao('cidadaocadastrounico');
      $sWhere            = "as02_nis = '{$iNis}'";
      $sSqlCadastroUnico = $oDaoCadastroUnico->sql_query_file(null, "as02_sequencial", null, $sWhere);
      $rsCadastroUnico   = $oDaoCadastroUnico->sql_record($sSqlCadastroUnico);
      
      if ($oDaoCadastroUnico->numrows > 0) {
        
        $iCodigoCadastroUnico = db_utils::fieldsMemory($rsCadastroUnico, 0)->as02_sequencial;
        return CadastroUnicoRepository::getCadastroUnicoByCodigo($iCodigoCadastroUnico);
      } 
    }
  }?>