<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * Factory que retorna a instancia da classe CgmFisico ou CgmJuridico
 * @package issqn
 * @author Felipe Nunes Ribeiro 
 * @revision $Author: dbfelipe $
 * @version $Revision: 1.2 $
 */
 abstract class CgmFactory {
 
  function __construct() {
    
  }

  /**
   * Retorna a instancia da classe CgmFisico ou CgmJuridico apartir do parâmetros informado 
   *
   * @param  integer $iTipo  -- 1 Pessoa Física
   *                         -- 2 Pessoa Jurídica* 
   * 
   * @param  integer $iCgm
   * @return object
   */
  public static function getInstance( $iTipo='', $iCgm='' ){

  	require_once('model/CgmBase.model.php');
  	require_once('model/CgmFisico.model.php');
  	require_once('model/CgmJuridico.model.php');
  	
    if ( trim($iTipo) != '' ) {
      
    	if ( $iTipo == 1 ) {
    		return new CgmFisico();
    	} else if ( $iTipo == 2 ) {
   		  return new CgmJuridico();
    	}
    	
    } else if ( trim($iCgm) != '' ) {
    	
      $oDaoCgm = db_utils::getDao("cgm");
      $sSqlCgm = $oDaoCgm->sql_query_file($iCgm,"z01_cgccpf");
      $rsCgm   = $oDaoCgm->sql_record($sSqlCgm);
      
      if ($oDaoCgm->numrows > 0) {
        
      	$sCgcCpf = db_utils::fieldsMemory($rsCgm,0)->z01_cgccpf;
        
      	if ( strlen($sCgcCpf) == '14' ) {
      		return new CgmJuridico($iCgm);
      	} else if ( strlen($sCgcCpf) == '11' ) {
      		return new CgmFisico($iCgm);
      	} else {
      		
      	}
        
      }
    	
    }
    
  }
  
  /**
   * Retorna a instancia da classe CgmFisico ou CgmJuridico apartir do CGM informado
   *
   * @param  integer $iCgm
   * @return object
   */
  public static function getInstanceByCgm ( $iCgm='' ){

 	  if ( trim($iCgm) == '' ) {
 		  throw new Exception('CGM não informado!');
 	  }
 	
 	  try {
	 	  return self::getInstance('',$iCgm);
	 	} catch (Exception $eException){
	    throw new Exception($eException->getMessage());    		
	 	}
	 	
  }
  
  /**
   * Retorna a instancia da classe CgmFisico ou CgmJuridico apartir do Tipo informado
   *
   * @param  integer $iTipo  -- 1 Pessoa Física
   *                         -- 2 Pessoa Jurídica
   * 
   * @return object
   * 
   */
  public static function getInstanceByType ( $iTipo='' ){

    if ( trim($iTipo) == '' ) {
      throw new Exception('Tipo não informado!');
    }
    
    try {
      return self::getInstance($iTipo,'');
    } catch (Exception $eException){
      throw new Exception($eException->getMessage());       
    }
    
  }  
  
}

?>