<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 * Classe do tipo Collection para DbModulos
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 *
 */
class DbModulosCollection {
  
  /**
   * Array de instâncias de DbModulos
   * @var array
   */
  protected static $aDbModulos = array();

  /**
   * Adiciona uma instância de DbModulos ao array
   * @param DbModulos $oDbModulos
   */
  public static function adicionaModulo( DbModulos $oDbModulos ) {
    
    if ( !array_key_exists( $oDbModulos->getIdItem(), $this->aDbModulos ) ) {
      self::$aDbModulos[ $oDbModulos->getIdItem() ] = $oDbModulos;
    }
  }
  
  /**
   * Deleta uma instância de DbModulos do array
   * @param DbModulos $oDbModulos
   */
  public static function deletaModulo( DbModulos $oDbModulos ) {
    
    if ( !array_key_exists( $oDbModulos->getIdItem(), $this->aDbModulos ) ) {
      unset( self::$aDbModulos[ $oDbModulos->getIdItem() ] );
    }
  }
  
  /**
   * Busca e retorna o array dos módulos existentes
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