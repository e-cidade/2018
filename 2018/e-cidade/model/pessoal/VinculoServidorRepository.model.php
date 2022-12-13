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
 * Repositorio de instancias VinculoServidor
 * 
 * @author   Renan Silva <renan.silva@dbseller.com.br>
 * @author   Rafael Nery <rafael.nery@dbseller.com.br>
 * @id       $Id: VinculoServidorRepository.model.php,v 1.1 2015/05/09 19:15:12 dbrafael.nery Exp $
 * @package  Pessoal
 */
 class VinculoServidorRepository {

  /**
   * Representa a instancia a classe
   * @var DBRepository
   */
  private static   $oInstance;

  /**
   * Previne a criação do objeto externamente
   */
  private function __construct() {
    return;
  }

  /**
   * Previne o clone
   * @return void
   */
  private function __clone() {
    return;
  }

  /**
   * Coleção de instancias de VinculoServidor
   * @var VinculoServidor[]
   */
  private $aColecao = array();


  /**
   * Retorna a instancia do repositório
   * 
   * @return VinculoServidorRepository
   */
  public static function getInstance() {

    if (empty(self::$oInstance)) {
      self::$oInstance = new VinculoServidorRepository();
    }

    return self::$oInstance;
  }

  /**
   * Adiciona um VinculoServidor a Coleção
   * 
   * @param  VinculoServidor $oItem
   * @return void
   */
  public static function add(VinculoServidor $oItem) {

    $oRepository = self::getInstance();
    $oRepository->aColecao[$oItem->getCodigo()] = $oItem;
  }
  
  /**
   * Retorna a instancia do VinculoServidor pelo seu código unico
   * 
   * @return VinculoServidor 
   */
  public static function getInstanciaPorCodigo($iCodigoVinculoServidor) { 

    $oRepository = self::getInstance();

    if ( !array_key_exists($iCodigoVinculoServidor, $oRepository->aColecao) ) {
      self::add($oRepository->make($iCodigoVinculoServidor));
    }

    return $oRepository->aColecao[$iCodigoVinculoServidor];
  }

  /**
   * Monta um objeto VinculoServidor
   * @return VinculoServidor
   */
  public function make($iCodigoVinculoServidor) { 

    $oDaoRhregime   = new cl_rhregime();
    $sCampos        = "rh30_codreg, rh30_descr, rh30_regime, rh30_vinculo, rh30_instit";
    $sSqlRhRegime   = $oDaoRhregime->sql_query_file($iCodigoVinculoServidor, $sCampos);
    $rsRhRegime     = db_query($sSqlRhRegime);

    if(!$rsRhRegime) {
      throw new DBException(_M("erro_buscar_vinculo"));
    }

    if(pg_num_rows($rsRhRegime) == 0) {
      throw new BusinessException(_M("nenhum_vinculo"));
    }

    $oStdRhRegime = db_utils::fieldsMemory($rsRhRegime ,0);
    $oVinculoServidor = new VinculoServidor($oStdRhRegime->rh30_codreg);
    $oVinculoServidor->setDescricao($oStdRhRegime->rh30_descr);
    $oVinculoServidor->setRegime(RegimeRepository::getInstanciaPorCodigo($oStdRhRegime->rh30_regime));
    $oVinculoServidor->setTipo($oStdRhRegime->rh30_vinculo);
    $oVinculoServidor->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($oStdRhRegime->rh30_instit));
  
    return $oVinculoServidor;
  }

  public static function persist() { 
    
  }

}