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
 * Classe do tipo Collection para ModuloSistema
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 *
 */
class ModuloSistemaCollection {
  
  /**
   * Array de instâncias de ModuloSistema
   * @var array
   */
  protected static $aModuloSistema = array();

  /**
   * Adiciona uma instância de ModuloSistema ao array
   * @param ModuloSistema $oModuloSistema
   */
  public static function adicionaModulo( ModuloSistema $oModuloSistema ) {
    
    if ( !array_key_exists( $oModuloSistema->getCodigo(), $this->aModuloSistema ) ) {
      self::$aModuloSistema[ $oModuloSistema->getCodigo() ] = $oModuloSistema;
    }
  }
  
  /**
   * Deleta uma instância de ModuloSistema do array
   * @param ModuloSistema $oModuloSistema
   */
  public static function deletaModulo( ModuloSistema $oModuloSistema ) {
    
    if ( !array_key_exists( $oModuloSistema->getCodigo(), $this->aModuloSistema ) ) {
      unset( self::$aModuloSistema[ $oModuloSistema->getCodigo() ] );
    }
  }
  
  /**
   * Busca e retorna o array dos módulos existentes
   * @return ModuloSistema[]
   */
  public static function buscaModulos() {
    
    $oDaoModuloSistema    = new cl_db_modulos();
    $sSqlModuloSistema    = $oDaoModuloSistema->sql_query_file( null, "*", "nome_modulo" );
    $rsModuloSistema      = db_query( $sSqlModuloSistema );
    $iLinhasModuloSistema = pg_num_rows( $rsModuloSistema );
    
    if ( $rsModuloSistema && $iLinhasModuloSistema > 0 ) {
      
      for ( $iContador = 0; $iContador < $iLinhasModuloSistema; $iContador++ ) {
        
        $oRetornoModuloSistema = db_utils::fieldsMemory( $rsModuloSistema, $iContador );
        $oModuloSistema        = new ModuloSistema( $oRetornoModuloSistema->id_item );
        
        if ( !array_key_exists( $oModuloSistema->getCodigo(), self::$aModuloSistema ) ) {
          self::$aModuloSistema[ $oModuloSistema->getCodigo() ] = $oModuloSistema;
        }
      }
    }
    
    return self::$aModuloSistema;
  }
} 
?>