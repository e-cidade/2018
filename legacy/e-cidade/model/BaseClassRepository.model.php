<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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
 * Repository básico
 */
class BaseClassRepository
{
  
  /**
   * Array com instancias da classe
   *
   * @static
   * @var Class[]
   * @access private
   */
  protected $aColecao = array();

  /**
   * Representa a instancia a classe, 
   * logo nas classes filhas esse atributo
   * deve ser sobrescrito de maneira que 
   * mantenha em memória a instância correta
   *
   * @var Class
   * @access protected
   */
  protected static  $oInstance;

  /**
   * Previne a criação do objeto externamente
   *
   * @return void
   */
  protected function __construct() 
  {
    return;
  }

  /**
   * Previne o clone
   *
   * @return void
   */
  protected function __clone() 
  {
    return;
  }

  /**
   * Retorna a instancia do repositório
   *
   * @return Class
   */
  public static function getInstance()
  {
    if (empty(static::$oInstance)) {
      static::$oInstance = new static;
    }
    
    return static::$oInstance;
  }

  /**
   * Adiciona a coleção um objeto
   *
   * @param Class $oObject
   */
  protected function add($oObject)
  {
    static::getInstance()->aColecao[$oObject->getCodigo()] = $oObject;
  }

  /**
   * Constrói um objeto e adiciona a colecao
   */
  protected function make($iCodigo) {}
  
  /**
   * Retorna uma instância pelo código
   */
  public static function getInstanciaPorCodigo($iCodigo)
  {
    $oRepository = static::getInstance();

    if(!isset($oRepository->aColecao[$iCodigo])) {
      $oRepository->add($oRepository->make($iCodigo));
    }

    return $oRepository->aColecao[$iCodigo];
  }
}
