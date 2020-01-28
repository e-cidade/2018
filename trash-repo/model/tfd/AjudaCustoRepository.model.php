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
 * Repository para as Ajudas de Custo
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package tfd
 * @version $Revision: 1.1 $
 */
class AjudaCustoRepository {
	
  private $aAjudaCusto = array();
  private static $oInstance;
  
  private function __construct() {}
  
  private function __clone() {}
  
  /**
   * Retorna a instância do Repositorio
   * @return AjudaCustoRepository
   */
  protected function getInstance() {
  	
    if (self::$oInstance == null) {
    	self::$oInstance = new AjudaCustoRepository();
    }
    return self::$oInstance;
  }
  
  /**
   * Verifica se a Ajuda de Custo possui instancia no repository, se não cria uma instância e a retorna
   * @param integer $iCodigo
   * @return AjudaCusto
   */
  public static function getAjudaCustoByCodigo($iCodigo) {
  	
    if (!array_key_exists($iCodigo, AjudaCustoRepository::getInstance()->aAjudaCusto)) {
      AjudaCustoRepository::getInstance()->aAjudaCusto[$iCodigo] = new AjudaCusto($iCodigo);
    }

    return AjudaCustoRepository::getInstance()->aAjudaCusto[$iCodigo];
  }
  
  /**
   * Remove uma instancia de AjudaCusto do repository
   * @param AjudaCusto $oAjudaCusto
   * @return AjudaCusto::boolean
   */
  public static function removerAjudaCusto(AjudaCusto $oAjudaCusto) {
  	
    if ( array_key_exists($oAjudaCusto->getCodigo(), AjudaCustoRepository::getInstance()->aAjudaCusto) ) {
    	unset(AjudaCustoRepository::getInstance()->aAjudaCusto[$oAjudaCusto->getCodigo()]);
    }
    return true;
  }
  
  /**
   * Retorna uma coleção de AjudaCusto que foram cadastradas como Automático.
   *  
   * @return multitype:Ambigous <AjudaCusto, multitype:>
   */
  public static function getAjudaCustoAutomatico() {
  	
    $aAjudaCusto    = array();
    $sWhere         = " tf12_faturabpa is true ";
    $oDaoAjudaCusto = new cl_tfd_ajudacusto();
    $sSqlAjudaCusto = $oDaoAjudaCusto->sql_query_file(null, "tf12_i_codigo", null, $sWhere);
    $rsAjudaCusto   = $oDaoAjudaCusto->sql_record($sSqlAjudaCusto);
    $iLinhas        = $oDaoAjudaCusto->numrows;
    
    
    if ( $iLinhas > 0 ) {
    	
      for ($i = 0; $i < $iLinhas; $i++) {
      	
        $iCodigoAjuda  = db_utils::fieldsMemory($rsAjudaCusto, $i)->tf12_i_codigo;
        $aAjudaCusto[] = AjudaCustoRepository::getAjudaCustoByCodigo($iCodigoAjuda);
      }
    }    
    
    return $aAjudaCusto;
  }
}