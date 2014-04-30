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

require_once("model/pessoal/Rubrica.model.php");
/**
 * Repositorio para Rubricas 
 * 
 * @abstract
 * @package Pessoal
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 * @author Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br> 
 */
abstract class RubricaRepository {

  /**
   * Array com instancias de rubricas 
   * 
   * @static
   * @var Array
   * @access public
   */
  static $aInstanciasRubricas = array();

  /**
   * Adiciona uma rubrica ao array de rubricas
   *
   * @static
   * @param Rubrica $oRubrica
   * @access public 
   * @return void
   */
  private static function adicionar(Rubrica $oRubrica, $iInstituicao ) {
  	
    RubricaRepository::$aInstanciasRubricas[ $oRubrica->getCodigo() ] [$iInstituicao] = $oRubrica;
    
  }

  /**
   * Retorna instancia da rubrica pelo codigo
   *
   * @static
   * @param string $sCodigo - codigo da rubrica
   * @access public
   * @return Rubrica
   */
  public static function getInstanciaByCodigo($sCodigo, $iInstituicao = null) {
  	
  	if (empty($iInstituicao)) {
  		$iInstituicao = db_getsession('DB_instit');
  	}

    /**
     * Se não tiver rubrica no array de instancias, adiciona
     */
    if ( empty( RubricaRepository::$aInstanciasRubricas[$sCodigo][$iInstituicao]) ) {
      RubricaRepository::adicionar(new Rubrica($sCodigo, $iInstituicao), $iInstituicao);
    }

    return RubricaRepository::$aInstanciasRubricas[$sCodigo][$iInstituicao];
  }

}