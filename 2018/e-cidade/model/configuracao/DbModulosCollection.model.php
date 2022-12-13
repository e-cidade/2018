<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 * Classe do tipo Collection para DbModulos
 * @author  F�bio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 *
 */
class DbModulosCollection {
  
  /**
   * Array de inst�ncias de DbModulos
   * @var array
   */
  protected static $aDbModulos = array();

  /**
   * Adiciona uma inst�ncia de DbModulos ao array
   * @param DbModulos $oDbModulos
   */
  public static function adicionaModulo( DbModulos $oDbModulos ) {
    
    if ( !array_key_exists( $oDbModulos->getIdItem(), $this->aDbModulos ) ) {
      self::$aDbModulos[ $oDbModulos->getIdItem() ] = $oDbModulos;
    }
  }
  
  /**
   * Deleta uma inst�ncia de DbModulos do array
   * @param DbModulos $oDbModulos
   */
  public static function deletaModulo( DbModulos $oDbModulos ) {
    
    if ( !array_key_exists( $oDbModulos->getIdItem(), $this->aDbModulos ) ) {
      unset( self::$aDbModulos[ $oDbModulos->getIdItem() ] );
    }
  }
  
  /**
   * Busca e retorna o array dos m�dulos existentes
   * @return DbModulos[]
   */
  public static function buscaModulos() {
    
    $oDaoDbModulos    = new cl_db_modulos();
    $sSqlDbModulos    = $oDaoDbModulos->sql_query_file( null, "*", "nome_modulo" );
    $rsDbModulos      = db_query( $sSqlDbModulos );
    $iLinhasDbModulos = pg_num_rows( $rsDbModulos );
    
    if ( $rsDbModulos && $iLinhasDbModulos > 0 ) {
      
      for ( $iContador = 0; $iContador < $iLinhasDbModulos; $iContador++ ) {
        
        $oRetornoDbModulos  = db_utils::fieldsMemory( $rsDbModulos, $iContador );
        $oDbModulos         = new DbModulos( $oRetornoDbModulos->id_item );
        
        if ( !array_key_exists( $oDbModulos->getIdItem(), self::$aDbModulos ) ) {
          self::$aDbModulos[ $oDbModulos->getIdItem() ] = $oDbModulos;
        }
      }
    }
    
    return self::$aDbModulos;
  }
} 
?>