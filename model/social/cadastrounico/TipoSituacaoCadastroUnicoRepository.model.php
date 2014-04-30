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
 * Repository para Tipo de Situacao do Cadastro Único
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package social
 * @subpackage cadastrounico
 */
class TipoSituacaoCadastroUnicoRepository {

  /**
   * Array com as instancias dos tipo de situacao
   * @var array
   */
  private $aTipoSituacao = array();
  
  private static $oInstance;
  
  private function __construct() {
  
  }
  
  private function __clone() {
  
  }
  
  /**
   * Retorna a instancia do Repositorio
   * @return TipoSituacaoCadastroUnico
   */
  protected static function getInstance() {
  
    if (self::$oInstance == null) {
      self::$oInstance = new TipoSituacaoCadastroUnicoRepository();
    }
    return self::$oInstance;
  }
  
  /**
   * Verifica se o tipo de situacao possui instancia. Caso não, cria e retorna a instancia de TipoSituacaoCadastroUnico
   * @param integer $iCodigoTipoSituacao
   * @return TipoSituacaoCadastroUnico
   */
  public static function getTipoSituacaoByCodigo($iCodigoTipoSituacao) {
  
    if (!array_key_exists($iCodigoTipoSituacao, TipoSituacaoCadastroUnicoRepository::getInstance()->aTipoSituacao)) {
      
      TipoSituacaoCadastroUnicoRepository::getInstance()->aTipoSituacao[$iCodigoTipoSituacao] = new TipoSituacaoCadastroUnico($iCodigoTipoSituacao);
    }
    return TipoSituacaoCadastroUnicoRepository::getInstance()->aTipoSituacao[$iCodigoTipoSituacao];
  }
  
}