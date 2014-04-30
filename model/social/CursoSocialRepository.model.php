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
 * Classe repository para classes CursoSocial
 * @author Andrio Araujo da Costa <andrio.costa@dbseller.com.br>
 * @package social
 * @version $Revision: 1.1 $
 */
class CursoSocialRepository {

  /**
   * Collection de CursoSocial
   * @var array
   */
  private $aCursoSocial = array();

  /**
   * Instancia da classe
   * @var CursoSocialRepository
  */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorno uma instancia do CursoSocial pelo Codigo
   * @param integer $iCursoSocial CursoSocial
   * @return CursoSocial
   */
  public static function getCursoSocialByCodigo($iCursoSocial) {

    if (!array_key_exists($iCursoSocial, CursoSocialRepository::getInstance()->aCursoSocial)) {
      CursoSocialRepository::getInstance()->aCursoSocial[$iCursoSocial] = new CursoSocial($iCursoSocial);
    }
    return CursoSocialRepository::getInstance()->aCursoSocial[$iCursoSocial];
  }

  /**
   * Retorna a instancia da classe
   * @return CursoSocialRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new CursoSocialRepository();
    }
    return self::$oInstance;
  }

  /**
   * Remove o Regencia passado como parametro do repository
   * @param Regencia $oRegencia
   * @return boolean
   */
  public static function removerCursoSocial(CursoSocial $oCursoSocial) {
    /**
     *
     */
    if (array_key_exists($oCursoSocial->getCodigo(), CursoSocialRepository::getInstance()->aCursoSocial)) {
      unset(CursoSocialRepository::getInstance()->aCursoSocial[$oCursoSocial->getCodigo()]);
    }
    return true;
  }

}