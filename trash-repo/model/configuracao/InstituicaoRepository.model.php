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
 * Dependencias 
 */
require_once 'model/configuracao/Instituicao.model.php';

/**
 * Classe repository para classes Instituicao
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package configuracao
 */
class InstituicaoRepository {

  /**
   * Collection de Instituicao
   * @var array
   */
  private $aInstituicao = array();

  /**
   * Instancia da classe
   * @var InstituicaoRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorno uma instancia de Instituicao pelo Codigo
   * @param integer $iCodigo Codigo do Instituicao
   * @return Instituicao
   */
  public static function getInstituicaoByCodigo($iCodigoInstituicao) {

    if (!array_key_exists($iCodigoInstituicao, InstituicaoRepository::getInstance()->aInstituicao)) {
      InstituicaoRepository::getInstance()->aInstituicao[$iCodigoInstituicao] = new Instituicao($iCodigoInstituicao);
    }
    return InstituicaoRepository::getInstance()->aInstituicao[$iCodigoInstituicao];
  }

  /**
   * Retorna a instancia da classe
   * @return InstituicaoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new InstituicaoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona um Instituicao dao repositorio
   * @param Instituicao $oInstituicao Instancia do Instituicao
   * @return boolean
   */
  public static function adicionarInstituicao(Instituicao $oInstituicao) {

    if(!array_key_exists($oInstituicao->getSequencial(), InstituicaoRepository::getInstance()->aInstituicao)) {
      InstituicaoRepository::getInstance()->aInstituicao[$oInstituicao->getSequencial()] = $oInstituicao;
    }
    return true;
  }

  /**
   * Remove o Instituicao passado como parametro do repository
   * @param Instituicao $oInstituicao
   * @return boolean
   */
  public static function removerInstituicao(Instituicao $oInstituicao) {
    /**
     *
     */
    if (array_key_exists($oInstituicao->getSequencial(), InstituicaoRepository::getInstance()->aInstituicao)) {
      unset(InstituicaoRepository::getInstance()->aInstituicao[$oInstituicao->getSequencial()]);
    }
    return true;
  }

  /**
   * Retorna o total de cidadoes existentes no repositorio;
   * @return integer;
   */
  public static function getTotalInstituicao() {
    return count(InstituicaoRepository::getInstance()->aInstituicao);
  }
}