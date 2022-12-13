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
 * Classe padrão de criptografia da DBSeller
 * @author Tales Baz   <tales.baz@dbseller.com.br>
 * @author Vitor Rocha <vitor@dbseller.com.br>
 */
class Encriptacao {

  /**
   * Criptografia sha1()
   */
  const SHA1 = 1;

  /**
   * Criptografia md5()
   */
  const MD5  = 2;

  /**
   * Criptografa uma string na criptografia informada.
   * @param  string  $sString Texto para ser criptografado
   * @param  integer $iTipo   Tipo de criptografia
   *                          1 = SHA1
   *                          2 = MD5
   * @return string           Texto criptografado
   */
  public static function hash( $sString, $iTipo = 1 ) {

    switch ($iTipo) {

      case self::SHA1:
        return sha1( $sString );
      break;

      case self::MD5:
        return md5( $sString );
      break;
    }

  }

  /**
   * Criptografa o texto informado no padrão da DBSeller sha1(md5(string))
   * @param  string $sString Texto para ser criptografado
   * @return string          Texto criptografado
   */
  public static function encriptaSenha ( $sString = '' ){

    return self::hash( self::hash( $sString, self::MD5 ), self::SHA1 );
  }
}