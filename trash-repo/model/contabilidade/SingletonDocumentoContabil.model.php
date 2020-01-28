<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Implementa��o de Singleton com Register
 * Garante uma �nica instancia para cada tipo de Documento Cont�bil
 * @author Andrio Costa
 * @version $Revision: 1.2 $
 */
class SingletonRegraDocumentoContabil {

  static private $aDocumento = array();

  private function __construct() {

  }

  /**
   * Implementa o Register indexando $aInstance com o tipo de codumento
   * @param integer $iTipoDocumento
   */
  public static function getDocumento ($iTipoDocumento) {
    
    if (!array_key_exists($iTipoDocumento, self::$aDocumento)) {
      self::$aDocumento[$iTipoDocumento] = new DocumentoContabil($iTipoDocumento);  
    } 
    return self::$aDocumento[$iTipoDocumento];
  } 
  /**
   * Hack para n�o deixar ter uma segunda instancia de OperacaoContabil
   */
  private function __clone() {
  }

}