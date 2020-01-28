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
 * Repository para classe Laboratorio
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package laboratorio
 */
class LaboratorioRepository {
  
  /** 
   * Collection de Laboratorio 
   * @var array
   */    
  private $aLaboratorio = array();
  
  /**
   * Instancia da classe 
   * @var LaboratorioRepository
   */
  private static $oInstance;
  
  private function __construct() {}
  
  private function __clone() {}

  /**
   * Retorno uma instancia do Laboratorio pelo Codigo
   * @param integer $iCodigo do Laboratorio
   * @return Laboratorio
   */
  public static function getLaboratorioByCodigo( $iCodigoLaboratorio ) {
    
    if( !array_key_exists( $iCodigoLaboratorio, LaboratorioRepository::getInstance()->aLaboratorio ) ) {
      LaboratorioRepository::getInstance()->aLaboratorio[$iCodigoLaboratorio] = new Laboratorio( $iCodigoLaboratorio );
    }
    return LaboratorioRepository::getInstance()->aLaboratorio[$iCodigoLaboratorio];
  } 
  
  /**
   * Retorna a instancia da classe
   * @return LaboratorioRepository
   */
  protected static function getInstance() {
    
    if( self::$oInstance == null ) {
      self::$oInstance = new LaboratorioRepository();
    }
    return self::$oInstance;
  }
  
  /**
   * Adiciona um Laboratorio ao repositorio
   * @param Laboratorio $oLaboratorio Instancia do Laboratorio
   * @return boolean
   */
  public static function adicionarLaboratorio( Laboratorio $oLaboratorio ) {
  
    if( !array_key_exists( $oLaboratorio->getCodigo(), LaboratorioRepository::getInstance()->aLaboratorio ) ) {
      LaboratorioRepository::getInstance()->aLaboratorio[$oLaboratorio->getCodigo()] = $oLaboratorio;
    }
    return true;
  }
  
  /**
   * Remove o Laboratorio passado como parametro do repository
   * @param Laboratorio $Laboratorio
   * @return boolean 
   */ 
  public static function removerLaboratorio( Laboratorio $oLaboratorio ) {
    
    if( array_key_exists( $oLaboratorio->getCodigo(), LaboratorioRepository::getInstance()->aLaboratorio ) ) {
      unset( LaboratorioRepository::getInstance()->aLaboratorio[$oLaboratorio->getCodigo()] );
    }
    return true;
  }

  /**
   * Retorna todos os laboratórios cadastrados
   * @return Laboratorio[]
   */
  public static function getLaboratorios() {

    $oDaoLabLbaoratorio = new cl_lab_laboratorio();
    $sSqlLabLaboratorio = $oDaoLabLbaoratorio->sql_query_file( null, "la02_i_codigo" );
    $rsLabLaboratorio   = db_query( $sSqlLabLaboratorio );

    if( $rsLabLaboratorio && pg_num_rows( $rsLabLaboratorio ) > 0 ) {

      $iTotalLinhas = pg_num_rows( $rsLabLaboratorio );
      for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

        $iCodigoLaboratorio = db_utils::fieldsMemory( $rsLabLaboratorio, $iContador )->la02_i_codigo;

        if( !array_key_exists( $iCodigoLaboratorio, LaboratorioRepository::getInstance()->aLaboratorio ) ) {
          LaboratorioRepository::getInstance()->aLaboratorio[$iCodigoLaboratorio] = new Laboratorio( $iCodigoLaboratorio );
        }
      }
    }

    return LaboratorioRepository::getInstance()->aLaboratorio;
  }
}