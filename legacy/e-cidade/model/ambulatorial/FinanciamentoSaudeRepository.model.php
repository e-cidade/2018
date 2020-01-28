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
   * Classe repository para classes FinanciamentoSaude
   * @author  Andrio Costa <andrio.costa@dbseller.com.br>
   * @package ambulatorial 
   * @version $Revision: 1.1 $
   */
  class FinanciamentoSaudeRepository {
    
    /** 
     * Collection de FinanciamentoSaude 
     * @var array
     */    
    private $aFinanciamentoSaude = array();
    
    /**
     * Instancia da classe 
     * @var FinanciamentoSaudeRepository
     */
    private static $oInstance;
    
    private function __construct() {
      
    }
    private function __clone() {
     
    }
 
    /**
     * Retorno uma instancia do FinanciamentoSaude pelo Codigo
     * @param integer $iCodigo Codigo do FinanciamentoSaude
     * @return FinanciamentoSaude
     */
    public static function getFinanciamentoSaudeByCodigo($iCodigoFinanciamentoSaude) {
      
      if (!array_key_exists($iCodigoFinanciamentoSaude, FinanciamentoSaudeRepository::getInstance()->aFinanciamentoSaude)) {
        FinanciamentoSaudeRepository::getInstance()->aFinanciamentoSaude[$iCodigoFinanciamentoSaude] = new FinanciamentoSaude($iCodigoFinanciamentoSaude);
      }
      return FinanciamentoSaudeRepository::getInstance()->aFinanciamentoSaude[$iCodigoFinanciamentoSaude];
    } 
    
    /**
     * Retorna uma coleção de Financiamentos para competência informada
     * @param DBCompetencia $oCompetencia
     * @throws ParameterException
     * @return multitype:Ambigous <FinanciamentoSaude, multitype:>
     */
    public static function getFinanciamentoSaudePorCompetencia(DBCompetencia $oCompetencia) {
    	
      $aFinanciamentos = array();
      
      $iMes = (int)$oCompetencia->getMes();
      
      $sWhere  = "     sd65_i_anocomp = {$oCompetencia->getAno()} ";
      $sWhere .= " and sd65_i_mescomp = {$iMes}";
      
      $oDaoFinanciamento = new cl_sau_financiamento();
      $sSqlFinanciamento = $oDaoFinanciamento->sql_query_file(null, "sd65_i_codigo", "sd65_c_financiamento", $sWhere);
      $rsFinanciamento   = $oDaoFinanciamento->sql_record($sSqlFinanciamento);
      $iLinhas           = $oDaoFinanciamento->numrows;
      
      if ($iLinhas == 0) {
        throw new ParameterException(_M("saude.ambulatorial.FinanciamentoSaude.competencia_sem_financiamento"));
      }
      
      for ($i = 0; $i < $iLinhas; $i++) {
      	
        $iCodigoFinanciamento  = db_utils::fieldsMemory($rsFinanciamento, $i)->sd65_i_codigo;
        $aFinanciamentos[]     = FinanciamentoSaudeRepository::getFinanciamentoSaudeByCodigo($iCodigoFinanciamento);
      }
      
      return $aFinanciamentos;
      
    }
    
    /**
     * Retorna a instancia da classe
     * @return FinanciamentoSaudeRepository
     */
    protected static function getInstance() {
      
      if (self::$oInstance == null) {
              
        self::$oInstance = new FinanciamentoSaudeRepository();     
      }
      return self::$oInstance;
    }
    
    /**
     * Adiciona um FinanciamentoSaude dao repositorio
     * @param FinanciamentoSaude $oFinanciamentoSaude Instancia do FinanciamentoSaude
     * @return boolean
     */
    public static function adicionarFinanciamentoSaude(FinanciamentoSaude $oFinanciamentoSaude) {
    
      if(!array_key_exists($oFinanciamentoSaude->getCodigo(), FinanciamentoSaudeRepository::getInstance()->aFinanciamentoSaude)) {
        FinanciamentoSaudeRepository::getInstance()->aFinanciamentoSaude[$oFinanciamentoSaude->getCodigo()] = $oFinanciamentoSaude;
      }
      return true;
    }
    
    /**
     * Remove o FinanciamentoSaude passado como parametro do repository
     * @param FinanciamentoSaude $oFinanciamentoSaude
     * @return boolean 
     */ 
    public static function removerFinanciamentoSaude(FinanciamentoSaude $oFinanciamentoSaude) {
       /**
        * 
        */ 
      if (array_key_exists($oFinanciamentoSaude->getCodigo(), FinanciamentoSaudeRepository::getInstance()->aFinanciamentoSaude)) {
        unset(FinanciamentoSaudeRepository::getInstance()->aFinanciamentoSaude[$oFinanciamentoSaude->getCodigo()]);
      }
      return true;
    }
    
    /** 
     * Retorna o total de cidadoes existentes no repositorio; 
     * @return integer; 
     */
    public static function getTotalFinanciamentoSaude() {
      return count(FinanciamentoSaudeRepository::getInstance()->aFinanciamentoSaude);
    }
  }