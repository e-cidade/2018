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
 * Repositoy para as ResultadoAvaliacaos
 * @package   Educacao
 * @author    Andrio Costa - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.2 $
 */
class ResultadoAvaliacaoRepository {

  /**
   * Array com instancias de ResultadoAvaliacao
   * @var array
   */
  private $aResultadoAvaliacao = array();
  private static $oInstance;

  private function __construct() {

  }

  private function __clone(){

  }

  /**
   * Retorna a instancia do Repositorio
   * @return ResultadoAvaliacaoRepository
   */
  protected function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new ResultadoAvaliacaoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se a ResultadoAvaliacao possui instancia, se não instancia e retorna a instancia de ResultadoAvaliacao
   * @param integer $iCodigoResultadoAvaliacao
   * @return ResultadoAvaliacao
   */
  public static function getResultadoAvaliacaoByCodigo($iCodigoResultadoAvaliacao) {

    if (!array_key_exists($iCodigoResultadoAvaliacao, ResultadoAvaliacaoRepository::getInstance()->aResultadoAvaliacao)) {
      ResultadoAvaliacaoRepository::getInstance()->aResultadoAvaliacao[$iCodigoResultadoAvaliacao] = new ResultadoAvaliacao($iCodigoResultadoAvaliacao);
    }
    return ResultadoAvaliacaoRepository::getInstance()->aResultadoAvaliacao[$iCodigoResultadoAvaliacao];
  }

  /**
   * Adiciona uma ResultadoAvaliacao ao repositorio
   * @param ResultadoAvaliacao $oResultadoAvaliacao
   */
  public static function adicionarResultadoAvaliacao(ResultadoAvaliacao $oResultadoAvaliacao) {

    ResultadoAvaliacaoRepository::getInstance()->aResultadoAvaliacao[$oResultadoAvaliacao->getCodigo()] = $oResultadoAvaliacao;
    return true;
  }

  /**
   * Remove uma ResultadoAvaliacao do repositorio
   * @param ResultadoAvaliacao $oResultadoAvaliacao
   * @return boolean
   */
  public static function removerResultadoAvaliacao(ResultadoAvaliacao $oResultadoAvaliacao) {

    if (array_key_exists($oResultadoAvaliacao->getCodigo(), ResultadoAvaliacaoRepository::getInstance()->aResultadoAvaliacao)) {
      unset(ResultadoAvaliacaoRepository::getInstance()->aResultadoAvaliacao[$oResultadoAvaliacao->getCodigo()]);
    }
    return true;
  }

}
?>