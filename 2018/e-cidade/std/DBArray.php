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
 * Classe de manipulação de array
 *
 * @package std
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @author Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br>
 */
abstract class DBArray {

  /**
   * Ordena a chave do array com funcao natsort 
   * 
   * @param Array $aArray 
   * @access public
   * @return Array
   */
  public static function keyNatSort( & $aArray ) {

    if ( !is_array($aArray) ) {
      throw ParameterException('Parametro não é um array');
    }

    $aChavesArray = array_keys( $aArray );

    if ( !natsort($aChavesArray) )  {
      return false;
    }

    $aRetorno = array();

    foreach ($aChavesArray as $sChave) {
      $aRetorno[$sChave] = $aArray[$sChave];
    }
    
    $aArray = $aRetorno;

    return $aRetorno;
  }

  public static function merge(array &$array1, array &$array2) {

    $merged = $array1;

    foreach($array2 as $key => &$value) {

      if ( is_array($value) && isset($merged[$key]) && is_array($merged[$key]) ) {
        $merged[$key] = static::merge($merged[$key], $value);
      } else {
        $merged[$key] = $value;
      }
    }

    return $merged;
  }

}