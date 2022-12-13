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
 * Classe que representa um registro da tabela preponto
 * 
 * @package folha
 * @author  Renan Silva  <renan.silva@dbseller.com.br> 
 */
class RegistroLoteRegistrosPontoRepository {

  CONST MENSAGEM = "recursoshumanos.pessoal.RegistroLoteRegistrosPontoRepository.";

  /**
   * Representa a instância da classe.
   * 
   * @var LoteRegistrosPontoRepository
   */
  private static $oInstance;
  
  private function __construct() { }
  
  private function __clone() { }

  /**
   * Retorna a instância do repository.
   * 
   * @return LoteRegistrosPontoRepository
   */
  protected static function getInstance() {
      
    if (self::$oInstance == null) {          
      self::$oInstance = new RegistroLoteRegistroPontoRepository();
    }
    
    return self::$oInstance;
  }

  /**
   * Persist no banco um registro do lote de registros no ponto
   *
   * @param RegistroLoteRegistroPonto
   * @return RegistroLoteRegistroPonto
   */
  public static function persist(RegistroLoteRegistrosPonto $oRegistroLoteRegistrosPonto) {
    
    $oDaoRhpreponto                    = new cl_rhpreponto();

    $oDaoRhpreponto->rh149_instit      = $oRegistroLoteRegistrosPonto->getInstituicao()->getSequencial();
    $oDaoRhpreponto->rh149_rubric      = $oRegistroLoteRegistrosPonto->getRubrica()->getCodigo();
    $oDaoRhpreponto->rh149_regist      = $oRegistroLoteRegistrosPonto->getServidor()->getMatricula();
    $oDaoRhpreponto->rh149_valor       = str_replace(',', '.', $oRegistroLoteRegistrosPonto->getValor());
    $oDaoRhpreponto->rh149_quantidade  = "{$oRegistroLoteRegistrosPonto->getQuantidade()}";
    $oDaoRhpreponto->rh149_tipofolha   = $oRegistroLoteRegistrosPonto->getFolhaPagamento()->getTipoFolha();
    $oDaoRhpreponto->rh149_competencia = $oRegistroLoteRegistrosPonto->getCompetencia();

    $oVarErro                 = new stdClass();
    $oVarErro->rubrica        = $oRegistroLoteRegistrosPonto->getRubrica()->getCodigo();
    $oVarErro->servidorNome   = $oRegistroLoteRegistrosPonto->getServidor()->getCgm()->getNome();
    $iSequencial              = $oRegistroLoteRegistrosPonto->getCodigo();

    if ( empty($iSequencial) ) {

      $oDaoRhpreponto->incluir(null);
      if ( $oDaoRhpreponto->erro_status == "0" ) {
        throw new BusinessException(_M(self::MENSAGEM . 'erro_persistir_registro', $oVarErro) . $oDaoRhpreponto->erro_msg);
      }
      $oRegistroLoteRegistrosPonto->setCodigo($oDaoRhpreponto->rh149_sequencial);

      $oDaoRhprepontoloteregistroponto                          = new cl_rhprepontoloteregistroponto();
      $oDaoRhprepontoloteregistroponto->rh156_rhpreponto        = $oRegistroLoteRegistrosPonto->getCodigo();
      $oDaoRhprepontoloteregistroponto->rh156_loteregistroponto = $oRegistroLoteRegistrosPonto->getCodigoLote();
      $oDaoRhprepontoloteregistroponto->incluir(null);

      if ( $oDaoRhprepontoloteregistroponto->erro_status == "0" ) {
        throw new BusinessException(_M(self::MENSAGEM . 'erro_persistir_registrovinculolote'));
      }
    } else {

      $oDaoRhpreponto->rh149_sequencial = $oRegistroLoteRegistrosPonto->getCodigo();
      $oDaoRhpreponto->alterar($oRegistroLoteRegistrosPonto->getCodigo());
    }

    return $oRegistroLoteRegistrosPonto;
  }

  /**
   * Exclui um registro do lote de registros no ponto
   * 
   */
  public static function excluir(RegistroLoteRegistrosPonto $oRegistroLoteRegistrosPonto){

    $oDaoRhpreponto                    = new cl_rhpreponto();
    $oDaoRhpreponto->rh149_sequencial  = $oRegistroLoteRegistrosPonto->getCodigo();

    $oVarErro                 = new stdClass();
    $iSequencial              = $oRegistroLoteRegistrosPonto->getCodigo();
    $iCodigoLote              = $oRegistroLoteRegistrosPonto->getCodigoLote();

    if ( empty($iCodigoLote) ) {
      throw new BusinessException(_M(self::MENSAGEM . 'erro_codigo_lote_nao_informado'));
    }

    if ( !empty($iSequencial) ) {

      $oDaoRhprepontoloteregistroponto                                       = new cl_rhprepontoloteregistroponto();
      $sWhereoDaoRhprepontoloteregistroponto = "     rh156_rhpreponto        = {$oRegistroLoteRegistrosPonto->getCodigo()}
                                                 and rh156_loteregistroponto = {$oRegistroLoteRegistrosPonto->getCodigoLote()}";

      $oDaoRhprepontoloteregistroponto->excluir(null, $sWhereoDaoRhprepontoloteregistroponto);

      if ( $oDaoRhprepontoloteregistroponto->erro_status == 0 ) {
        throw new BusinessException(_M(self::MENSAGEM . 'erro_excluir_registrovinculolote'));
      }

      $oDaoRhpreponto->excluir($iSequencial);
      
      if ( $oDaoRhpreponto->erro_status == 0 ) {
        throw new BusinessException(_M(self::MENSAGEM . 'erro_excluir_registro', $oVarErro));
      }
      

    } else {
      throw new BusinessException(_M(self::MENSAGEM . 'erro_codigo_registro_nao_informado'));
    }

    return true;
  }

  /**
   *Retorna a lotacao do registro do servidor que está sendo lançado no ponto
   * 
   */
  public static function getLotacaoServidor(RegistroLoteRegistrosPonto $oRegistroLoteRegistrosPonto) {

    $oDaoRhpessoal      = new cl_rhpessoal();
    $sWhereDaoRhpessoal = "     rh01_regist = {$oRegistroLoteRegistrosPonto->getServidor()->getMatricula()} 
                          and rh02_anousu = {$oRegistroLoteRegistrosPonto->getServidor()->getAnoCompetencia()} 
                          and rh02_mesusu = {$oRegistroLoteRegistrosPonto->getServidor()->getMesCompetencia()} ";
    $sSqlDaoRhpessoal   = $oDaoRhpessoal->sql_query_cgm(null,
                                                        $campos="rh02_lota as r90_lotac, r70_descr",
                                                        "", $sWhereDaoRhpessoal);

    $rsDaoRhpessoal = db_query($sSqlDaoRhpessoal);

    if ( is_resource($rsDaoRhpessoal) && pg_num_rows($rsDaoRhpessoal) > 0 ) {
      $oResponseDaoRhpessoal = db_utils::fieldsMemory($rsDaoRhpessoal, 0);
    } else {
      throw new BusinessException(_M(self::MENSAGEM . 'erro_buscar_lotacao_servidor'));
    }

    $oRetorno->sCodLotacao       = $oResponseDaoRhpessoal->r90_lotac;
    $oRetorno->sDescricaoLotacao = $oResponseDaoRhpessoal->r70_descr;
    return $oRetorno;
  }
}
