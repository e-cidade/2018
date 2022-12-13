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
 * Classe para manipulação de de RRA
 *
 * @package Pessoal
 * @revision $Author: dbrenan.silva $
 * @version  $Revision: 1.3 $
 */

class RRARepository {

  const MENSAGEM = "recursoshumanos.pessoal.RRARepository.";

  /**
   * Representa a instancia da Classe
   *
   * @var RRARepository
   */
  private static $oInstance;

  /**
   * Representa uma coleção de RRA
   *
   * @var $aRRA;
   */
  private $aRRA = array();

  /**
    * Construtor da classe de RRA
    */
  private function  __construct() {}

  /**
    * Clona a classe de RRA
    */
  private function __clone() {}

  /**
   * Retorna a instância do repository
   *
   * @access public
   * @return RRA
   */
  public function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new RRARepository();
    }

    return self::$oInstance;
  }


  /**
   * Constroi um objeto de RRA
   *
   * @access private
   * @param  AssentamentoRRA $oAssentamentoRRA
   * @return RRA
   */
  private function make(AssentamentoRRA $oAssentamentoRRA) {

    $oRRA = new RRA($oAssentamentoRRA);
    $oRRA->carregarLancamentos();
    return $oRRA;
  }

  /**
   * Adiciona uma de RRA
   *
   * @access private
   * @param RRA $oRRA
   */
  private function adicionar(RRA $oRRA) {

    $oRepository = self::getInstance();
    $oRepository->aRRA[$oRRA->getAssentamento()->getCodigo()] = $oRRA;

    return $oRRA;
  }

  /**
   * Retorna uma instancia de RRA
   *
   * @static
   * @access public
   * @param  AssentamentoRRA
   * @return RRA
   */
  public static function getInstanciaByAssentamento(AssentamentoRRA $oAssentamentoRRA) {

    $oRepository = self::getInstance();

    if(!array_key_exists($oAssentamentoRRA->getCodigo(), $oRepository->aRRA)) {
      self::adicionar(self::make($oAssentamentoRRA));
    }

    return $oRepository->aRRA[$oAssentamentoRRA->getCodigo()];
  }

  /**
   * Exclui os lancamentos de RRA
   *
   * @static
   * @access public
   * @param  Integer
   * @return String
   */
  public static function excluirLancamentosPorCodigoAssentamento($iCodigo) {

    if(!DBNumber::isInteger($iCodigo)) {
      throw new ParameterException("erro_informar_codigo_excluir_lancamentos");
    }

    $oDaoLancamentoRRA  = new cl_lancamentorra;
    $oDaoLancamentoRRA->excluir(null, " rh173_assentamentorra = ".$iCodigo);

    if($oDaoLancamentoRRA->erro_status == '0') {
      throw new DBException(_M(MENSAGEM."erro_excluir_lancamentos"));
    }

    return $oDaoLancamentoRRA->erro_msg;
  }
}