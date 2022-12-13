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
 * Classe para manipulação de de Lançamento de RRA
 *
 * @package Pessoal
 * @revision $Author: dbiuri $
 * @version  $Revision: 1.9 $
 */

class LancamentoRRARepository {

  const MENSAGEM = "recursoshumanos.pessoal.LancamentoRRARepository.";

  /**
   * Representa a instancia da Classe
   *
   * @var LancamentoRRARepository
   */
  private static $oInstance;

  /**
   * Representa uma coleção de Lançamento de RRA
   *
   * @var $aLancamentoRRA;
   */
  private static $aLancamentoRRA = array();


  /**
    * Construtor da classe de Lançamento de RRA
    */
  private function  __construct() {}

  /**
    * Clona a classe de Lançamento de RRA
    */
  private function __clone() {}

  /**
   * Retorna a instância do repository
   *
   * @access public
   * @return LancamentoRRA
   */
  public function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new LancamentoRRA();
    }

    return self::$oInstance;
  }

  /**
   * Constroi um objeto de Lançamento de RRA
   *
   * @access private
   * @param  LancamentoRRA $oLancamentoRRA
   * @return \LancamentoRRA|\LancamentoRRA[]
   * @throws \DBException
   * @throws \ParameterException
   */
  private static function make($oLancamentoRRA) {

    if (!$oLancamentoRRA->getCodigo() && !$oLancamentoRRA->getAssentamento()->getCodigo()) {
      throw new ParameterException( _M(self::MENSAGEM ."parametros_nao_informados") );
    }

    $sOrdermLancamentoRRA = null;

    if (!$oLancamentoRRA->getCodigo()) {
      $sWhereLancamentoRRA  = " rh173_assentamentorra = ".$oLancamentoRRA->getAssentamento()->getCodigo();
      $sOrdermLancamentoRRA = " rh173_sequencial desc";
    } else {
    	$sWhereLancamentoRRA  = " rh173_sequencial = ".$oLancamentoRRA->getCodigo();
    }

    $oDaoLancamentoRRA    = new cl_lancamentorraloteregistroponto();
    $sSqlLancamentoRRA    = $oDaoLancamentoRRA->sql_query(null, "*", $sOrdermLancamentoRRA, $sWhereLancamentoRRA);
		$rsLancamentoRRA      = db_query($sSqlLancamentoRRA);

		if(!$rsLancamentoRRA) {
			throw new DBException(_M(self::MENSAGEM."erro_buscar_lancamentos"));
		}

		$iQtdeLancamentos = pg_num_rows($rsLancamentoRRA);

		if( $iQtdeLancamentos == 0) {
			throw new DBException(_M(self::MENSAGEM."nenhum_lancamento_encontrado"));
		}

    $aLancamentoRRA = array();

    if($iQtdeLancamentos > 0) {

      for ($iIndLancamentos=0; $iIndLancamentos < $iQtdeLancamentos; $iIndLancamentos++) {

        $oDaoLancamentoRRA                       = db_utils::fieldsMemory($rsLancamentoRRA, $iIndLancamentos);

        $oLancamentoRRA                          = new LancamentoRRA($oDaoLancamentoRRA->rh173_sequencial);
        $oLancamentoRRA->setAssentamento         (AssentamentoFactory::getByCodigo($oDaoLancamentoRRA->rh173_assentamentorra));
        $oLancamentoRRA->setValorLancado         ($oDaoLancamentoRRA->rh173_valorlancado);
        $oLancamentoRRA->setValorEncargos        ($oDaoLancamentoRRA->rh173_encargos);
        $oLancamentoRRA->setValorPensao          ($oDaoLancamentoRRA->rh173_pensao);
        $oLancamentoRRA->setValorBasePrevidencia ($oDaoLancamentoRRA->rh173_baseprevidencia);
        $oLancamentoRRA->setValorBaseIrrf        ($oDaoLancamentoRRA->rh173_baseirrf);
        $oLancamentoRRA->setLoteRegistroPonto    (LoteRegistrosPontoRepository::getInstanceByCodigo($oDaoLancamentoRRA->rh174_loteregistroponto));

        if($iQtdeLancamentos == 1) {
          return $oLancamentoRRA;
        }
        $aLancamentoRRA[] = $oLancamentoRRA;
      }
    }

    return $aLancamentoRRA;
  }

  /**
   * Adiciona um Lançamento de RRA
   *
   * @access private
   * @param LancamentoRRA $oLancamentoRRA
   */
  private function adicionar(LancamentoRRA $oLancamentoRRA) {

    $oRepository = self::getInstance();
    $oRepository->aLancamentoRRA[$oLancamentoRRA->getCodigo()] = $oLancamentoRRA;

    return $oLancamentoRRA;
  }

  /**
   * Retorna uma instancia de Lançamento de RRA
   *
   * @static
   * @access public
   * @param  Integer $iSequencial
   * @return LancamentoRRA
   */
  public static function getInstanciaBySequencial($iSequencial) {

    if ( !array_key_exists($iSequencial, self::$aLancamentoRRA ) ) {
      return self::adicionar(self::make(new LancamentoRRA($iSequencial)));
    }

    return self::$aLancamentoRRA[$iSequencial];
  }

  /**
   * Retorna uma coleção de Lançamento de RRA
   *
   * @static
   * @access public
   * @param  AssentamentoRRA $oAssentamentoRRA
   * @return \LancamentoRRA[]
   * @throws \BusinessException
   */
  public static function getInstanciasByAssentamento(AssentamentoRRA $oAssentamentoRRA) {

    $oLancamentoRRA = new LancamentoRRA();
    $oLancamentoRRA->setAssentamento($oAssentamentoRRA);

    try{

      $mLancamentos = self::make($oLancamentoRRA);

      if($mLancamentos instanceof LancamentoRRA) {

        self::adicionar($mLancamentos);

      } else {

        if(count($mLancamentos) > 0) {

          foreach ($mLancamentos as $oLancamento) {
            self::adicionar($oLancamento);
          }
        }
      }

      $oRepository = self::getInstance();
      return $oRepository->aLancamentoRRA;

    } catch (Exception $oErro) {

      if(strpos($oErro->getMessage(), 'nenhum lan') === false) {
        throw new BusinessException($oErro->getMessage());
      }

      return array();
    }
  }

  /**
   * Persiste na base de dados uma configura??o de tipo de assentamento de RRA
   *
   * @static
   * @access public
   * @param  LancamentoRRA $oLancamentoRRA
   */
  public static function persist(LancamentoRRA $oLancamentoRRA) {

    $oDaoLancamentoRRA  = new cl_lancamentorra();

    $oDaoLancamentoRRA->rh173_sequencial      = $oLancamentoRRA->getCodigo();
    $oDaoLancamentoRRA->rh173_assentamentorra = $oLancamentoRRA->getAssentamento()->getCodigo();
    $oDaoLancamentoRRA->rh173_valorlancado    = $oLancamentoRRA->getValorLancado();
    $oDaoLancamentoRRA->rh173_encargos        = $oLancamentoRRA->getValorEncargos() == "" ? "0" : $oLancamentoRRA->getValorEncargos();
    $oDaoLancamentoRRA->rh173_pensao          = $oLancamentoRRA->getValorPensao() == "" ? "0" : $oLancamentoRRA->getValorPensao();
    $oDaoLancamentoRRA->rh173_baseprevidencia = "{$oLancamentoRRA->getValorBasePrevidencia()}";
    $oDaoLancamentoRRA->rh173_baseirrf        = "{$oLancamentoRRA->getValorBaseIrrf()}";

    if(empty($oDaoLancamentoRRA->rh173_sequencial)) {
      $oDaoLancamentoRRA->incluir(null);
      $oLancamentoRRA->setCodigo($oDaoLancamentoRRA->rh173_sequencial);
    } else {
      $oDaoLancamentoRRA->alterar($oDaoLancamentoRRA->rh173_sequencial);
    }

    if($oDaoLancamentoRRA->erro_status == '0') {
      throw new DBException("Ocorreu um erro ao salvar lançamento.\n".$oDaoLancamentoRRA->erro_msg);
    }

    $oLancamentoRRA->setCodigo($oDaoLancamentoRRA->rh173_sequencial);
    $oDaoPensionistasRRA  = new cl_lancamentorrapensionista();
    $oDaoPensionistasRRA->excluir(null, "rh201_lancamentorra={$oLancamentoRRA->getCodigo()}");
    if ($oDaoPensionistasRRA->erro_status == 0) {
      throw new DBException("Ocorreu um erro ao salvar pensionistas.\n".$oDaoPensionistasRRA->erro_msg);
    }
    foreach ($oLancamentoRRA->getPensionistas() as $oPensionista) {

      $oDaoPensionistasRRA->rh201_lancamentorra = $oLancamentoRRA->getCodigo();
      $oDaoPensionistasRRA->rh201_numcgm        = $oPensionista->getPensionista()->getCodigo();
      $oDaoPensionistasRRA->rh201_valor         = $oPensionista->getValor();
      $oDaoPensionistasRRA->incluir(null);

      if ($oDaoPensionistasRRA->erro_status == 0) {
        throw new DBException("Ocorreu um erro ao salvar pensionistas.\n".$oDaoPensionistasRRA->erro_msg);
      }
    }
    return $oLancamentoRRA;
  } 
}