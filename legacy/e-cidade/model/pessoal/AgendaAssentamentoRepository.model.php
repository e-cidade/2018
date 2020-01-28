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
 * Repositório para Tipos de assentamentos
 *
 * @package pessoal
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class AgendaAssentamentoRepository {

 /**
   * Array com instancias de tipos de assentamentos
   *
   * @static
   * @var Array
   * @access private
   */
  static private $aColecao = array();

  /**
   * Representa a instancia a classe
   * 
   * @var TipoAssentamentoRepository
   * @access private
   */
  private static   $oInstance;

  /**
   * Previne a criação do objeto externamente
   *
   * @return void
   */
  private function __construct() {
    return;
  }

  /**
   * Previne o clone
   * 
   * @return void
   */
  private function __clone() {
    return;
  }

  /**
   * Retorna a instancia do repositório
   * 
   * @return AgendaAssentamentoRepository
   */
  public static function getInstance() {

    if (empty(self::$oInstance)) {

      $sClasse  = get_class();
      self::$oInstance = new AgendaAssentamentoRepository();
    }

    return self::$oInstance;
  }

  /**
   * Adiciona a coleção um tipo de assentamento
   * 
   * @param AgendaAssentamento $oAgendaAssentamento
   */
  public function add(AgendaAssentamento $oAgendaAssentamento) {

    $oRepository = self::getInstance();
    $oRepository->aColecao[$oAgendaAssentamento->getCodigo()] = $oAgendaAssentamento;
  }

  /**
   * Monta um objeto AgendaAssentamento
   * 
   * @param  Integer $iCodigo
   * 
   * @return AgendaAssentamento
   */
  public function make($iCodigo) {

    $oAgendaAssentamento = new AgendaAssentamento($iCodigo);

    if(!empty($iCodigo)) {

      try {

        $oDaoAgendaAssentamento    = new cl_agendaassentamento;
        $sCamposAgendaAssentamento = "h82_tipoassentamento,
                                      h82_instit,
                                      formulainicio.db148_nome as h82_formulainicio,
                                      formulafim.db148_nome as h82_formulafim,
                                      formulafaltasperiodo.db148_nome as h82_formulafaltasperiodo,
                                      formulacondicao.db148_nome as h82_formulacondicao,
                                      formulaprorroga.db148_nome as h82_formulaprorroga,
                                      h82_selecao";
        $sSqlAgendaAssentamento    = $oDaoAgendaAssentamento->sql_query($iCodigo, $sCamposAgendaAssentamento);
        $rsAgendaAssentamento      = db_query($sSqlAgendaAssentamento);

        if(!$rsAgendaAssentamento) {
          throw new DBException("Ocorreu um erro ao buscar o tipo de assentamento");
        }

        if(pg_num_rows($rsAgendaAssentamento) > 0) {

          $oStdAgendaAssentamento = db_utils::fieldsMemory($rsAgendaAssentamento, 0);

          $oAgendaAssentamento->setTipoAssentamento(TipoAssentamentoRepository::getInstanciaPorCodigo($oStdAgendaAssentamento->h82_tipoassentamento));
          $oAgendaAssentamento->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($oStdAgendaAssentamento->h82_instit));
          $oAgendaAssentamento->setNomeFormulaInicio($oStdAgendaAssentamento->h82_formulainicio);
          $oAgendaAssentamento->setNomeFormulaFim($oStdAgendaAssentamento->h82_formulafim);
          $oAgendaAssentamento->setNomeFormulaFaltasPeriodo($oStdAgendaAssentamento->h82_formulafaltasperiodo);
          $oAgendaAssentamento->setNomeFormulaCondicao($oStdAgendaAssentamento->h82_formulacondicao);
          $oAgendaAssentamento->setNomeFormulaProrrogaFim($oStdAgendaAssentamento->h82_formulaprorroga);
          $oAgendaAssentamento->setSelecao(new Selecao($oStdAgendaAssentamento->h82_selecao));
        }
        
      } catch (Exception $e) {
        $sErro = $e->getMessage();
      }
    }

    return $oAgendaAssentamento;
  }

  /**
   * Retorna uma instancia da classe AgendaAssentamento
   * 
   * @param  Integer $iCodigo
   * 
   * @return AgendaAssentamento
   */
  public function getInstanciaPorCodigo($iCodigo) {

    $oRepository = self::getInstance();

    if(!isset($oRepository->aColecao[$iCodigo])) {
      self::add($oRepository->make($iCodigo));
    }

    return $oRepository->aColecao[$iCodigo];
  }

  /**
   * Retorna uma instancia da classe AgendaAssentamento
   *
   * @param $oTipoAssentamento
   * @return \AgendaAssentamento
   */
  public function getInstanciaPorTipoAssentamento($oTipoAssentamento) {

    if(!empty($oTipoAssentamento) && $oTipoAssentamento instanceof TipoAssentamento) {

      try {

        $oDaoAgendaAssentamento = new cl_agendaassentamento;
        $sSqlAgendaAssentamento = $oDaoAgendaAssentamento->sql_query_file(null, "h82_sequencial", null, "h82_tipoassentamento = ". $oTipoAssentamento->getSequencial());
        $rsAgendaAssentamento   = db_query($sSqlAgendaAssentamento);

        if(!$rsAgendaAssentamento) {
          throw new DBException("Ocorreu um erro ao buscar a agenda de assentamento");
        }

        if(pg_num_rows($rsAgendaAssentamento) > 0) {

          $iCodigo = db_utils::fieldsMemory($rsAgendaAssentamento, 0)->h82_sequencial;

          $oRepository = self::getInstance();

          if(!isset($oRepository->aColecao[$iCodigo])) {
            self::add($oRepository->make($iCodigo));
          }

          return $oRepository->aColecao[$iCodigo];
        }
      } catch (Exception $oErro) {
      }
    }
  }

  /**
   * Retorna uma instancia da classe AgendaAssentamento
   *
   * @param  TipoAssentamento $oTipoAssentamento
   * @param  Selecao          $oSelecao
   * @param  Instituicao      $oInstituicao
   * @return \AgendaAssentamento
   * @throws \DBException
   */
  public static function getInstanciaPorTipoSelecaoInstituicao(TipoAssentamento $oTipoAssentamento, Selecao $oSelecao, Instituicao $oInstituicao) {

    $oDaoAgendaAssentamento    = new cl_agendaassentamento;
    $sWhereAgendaAssentamento  = "     h82_tipoassentamento = ". $oTipoAssentamento->getSequencial();
    $sWhereAgendaAssentamento .= " and h82_selecao = ". $oSelecao->getCodigo();
    $sWhereAgendaAssentamento .= " and h82_instit  = ". $oInstituicao->getCodigo();
    $sSqlAgendaAssentamento    = $oDaoAgendaAssentamento->sql_query_file(null, "h82_sequencial", null, $sWhereAgendaAssentamento);
    $rsAgendaAssentamento      = db_query($sSqlAgendaAssentamento);

    if(!$rsAgendaAssentamento) {
      throw new DBException("Ocorreu um erro ao buscar a agenda de assentamento");
    }

    if(pg_num_rows($rsAgendaAssentamento) > 0) {

      $iCodigo = db_utils::fieldsMemory($rsAgendaAssentamento, 0)->h82_sequencial;

      $oRepository = self::getInstance();

      if(!isset($oRepository->aColecao[$iCodigo])) {
        self::add($oRepository->make($iCodigo));
      }

      return $oRepository->aColecao[$iCodigo];
    }
  }

  /**
   * Retorna as selecões que está vinculadas a este tipo de assentametno
   *
   * @param AgendaAssentamento
   * 
   * @return Selecao[]
   */
  public function getListaSelecaoParaTipo($oAgendaAssentamento) {

    if(!empty($oAgendaAssentamento)) {

      try {

        $oDaoAgendaAssentamento    = new cl_agendaassentamento;
        $sCamposAgendaAssentamento = "h82_tipoassentamento,
                                      h82_instit,
                                      formulainicio.db148_nome as h82_formulainicio,
                                      formulafim.db148_nome as h82_formulafim,
                                      formulafaltasperiodo.db148_nome as h82_formulafaltasperiodo,
                                      formulacondicao.db148_nome as h82_formulacondicao,
                                      formulaprorroga.db148_nome as h82_formulaprorroga,
                                      h82_selecao";

        $sWhereAgendaAssentamento  = "h82_tipoassentamento = ". $oAgendaAssentamento->getTipoAssentamento()->getSequencial() . " and h82_instit = " . db_getsession('DB_instit');
        $sSqlAgendaAssentamento    = $oDaoAgendaAssentamento->sql_query(null, $sCamposAgendaAssentamento, null, $sWhereAgendaAssentamento);
        $rsAgendaAssentamento      = db_query($sSqlAgendaAssentamento);

        if(!$rsAgendaAssentamento) {
          throw new DBException("Ocorreu um erro ao buscar o tipo de assentamento");
        }

        if(pg_num_rows($rsAgendaAssentamento) > 0) {

          for ($iIndSelecao=0; $iIndSelecao < pg_num_rows($rsAgendaAssentamento) ; $iIndSelecao++) { 

            $oStdSelecao     = db_utils::fieldsMemory($rsAgendaAssentamento, $iIndSelecao); 
            $oSelecao        = new Selecao($oStdSelecao->h82_selecao);
            $aListaSelecao[] = $oSelecao;
          }

          $oAgendaAssentamento->setListaSelecao($aListaSelecao);
          return $oAgendaAssentamento;
        }
        
      } catch (Exception $e) {
        $sErro = $e->getMessage();
      }
    }

    ;

  }
}