<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Repository;

use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoJustificativa;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa as JustificativaModel;

/**
 * Classe para manutenção das justificativas
 * Class Justificativa
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Configuracoes\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Justificativa {

  /**
   * @var \cl_pontoeletronicojustificativa
   */
  private $oDao;

  /**
   * Justificativa constructor.
   */
  public function __construct() {
    $this->oDao = new \cl_pontoeletronicojustificativa();
  }

  /**
   * @param JustificativaModel $oJustificativa
   * @param \Instituicao $oInstituicao
   * @return JustificativaModel
   * @throws \DBException
   */
  public function add(JustificativaModel $oJustificativa, \Instituicao $oInstituicao) {

    $this->validaExistenciaJustificativaMesmaSigla($oJustificativa, $oInstituicao);

    $sAcao = $oJustificativa->getCodigo() == null ? 'incluir' : 'alterar';

    $this->oDao->rh194_sequencial  = $oJustificativa->getCodigo();
    $this->oDao->rh194_descricao   = $oJustificativa->getDescricao();
    $this->oDao->rh194_sigla       = $oJustificativa->getAbreviacao();
    $this->oDao->rh194_instituicao = $oInstituicao->getCodigo();
    $this->oDao->{$sAcao}($oJustificativa->getCodigo());

    if($this->oDao->erro_status == '0') {
      throw new \DBException($this->oDao->erro_msg);
    }

    $oJustificativa->setCodigo($this->oDao->rh194_sequencial);

    return $oJustificativa;
  }

  /**
   * @param JustificativaModel $oJustificativa
   */
  public function removeAll(JustificativaModel $oJustificativa) {

    $this->remove($oJustificativa);
    $this->removeTiposAssentamento($oJustificativa);
  }

  /**
   * @param JustificativaModel $oJustificativa
   * @throws \BusinessException
   * @throws \DBException
   */
  public function remove(JustificativaModel $oJustificativa) {

    if($oJustificativa->getCodigo() == null) {
      throw new \BusinessException('Código da justificativa não informado.');
    }

    $this->removeTiposAssentamento($oJustificativa);

    $this->oDao->excluir($oJustificativa->getCodigo());

    if($this->oDao->erro_status == '0') {
      throw new \DBException($this->oDao->erro_msg);
    }
  }

  /**
   * @param JustificativaModel $oJustificativa
   * @param \Instituicao $oInstituicao
   * @throws \BusinessException
   * @throws \DBException
   */
  private function validaExistenciaJustificativaMesmaSigla(JustificativaModel $oJustificativa, \Instituicao $oInstituicao) {

    $aWhereValidaJustificativa = array(
      "rh194_sigla = '{$oJustificativa->getAbreviacao()}'",
      "rh194_instituicao = {$oInstituicao->getCodigo()}"
    );

    if($oJustificativa->getCodigo() != null) {
      $aWhereValidaJustificativa[] = "rh194_sequencial <> {$oJustificativa->getCodigo()}";
    }

    $sSqlValidaJustificativa = $this->oDao->sql_query_file(
      null,
      '1',
      null,
      implode(' AND ', $aWhereValidaJustificativa)
    );
    $rsValidaJustificativa = db_query($sSqlValidaJustificativa);

    if(!$rsValidaJustificativa) {
      throw new \DBException('Erro ao validar a sigla cadastrada');
    }

    if(pg_num_rows($rsValidaJustificativa) > 0) {
      throw new \BusinessException('Sigla já utilizada em outra Justificativa na Instituição.');
    }
  }

  /**
   * @param JustificativaModel $oJustificativaModel
   * @return array|\TipoAssentamento[]
   * @throws \DBException
   */
  public function getTiposAssentamentoPorJustificativa(JustificativaModel $oJustificativaModel) {

    $oDaoJustificativaTipoAsse = new \cl_pontoeletronicojustificativatipoasse();
    $sSqlJustificativaTipoAsse = $oDaoJustificativaTipoAsse->sql_query_file(
      null,
      'rh205_tipoasse',
      'rh205_tipoasse',
      "rh205_pontoeletronicojustificativa = {$oJustificativaModel->getCodigo()}"
    );

    $rsJustificativaTipoAsse = db_query($sSqlJustificativaTipoAsse);

    if(!$rsJustificativaTipoAsse) {
      throw new \DBException('Erro ao buscar os tipos de assentamento configurados. Contate o suporte.');
    }

    if(pg_num_rows($rsJustificativaTipoAsse) == 0) {
      return array();
    }

    return \db_utils::makeCollectionFromRecord($rsJustificativaTipoAsse, function($oRetorno) {
      return \TipoAssentamentoRepository::getInstanciaPorCodigo($oRetorno->rh205_tipoasse);
    });
  }

  /**
   * @param JustificativaModel $oJustificativaModel
   * @param int $iCodigoTipoAssentamento
   * @throws \DBException
   */
  public function addTipoAssentamento(JustificativaModel $oJustificativaModel, $iCodigoTipoAssentamento) {

    $oDaoJustificativaTipoAsse                                     = new \cl_pontoeletronicojustificativatipoasse();
    $oDaoJustificativaTipoAsse->rh205_pontoeletronicojustificativa = $oJustificativaModel->getCodigo();
    $oDaoJustificativaTipoAsse->rh205_tipoasse                     = $iCodigoTipoAssentamento;
    $oDaoJustificativaTipoAsse->incluir(null);

    if($oDaoJustificativaTipoAsse->erro_status == '0') {
      throw new \DBException('Erro ao vincular o tipo de assentamento a Justificativa. Contate o suporte.');
    }
  }

  /**
   * @param JustificativaModel $oJustificativaModel
   * @throws \DBException
   */
  public function removeTiposAssentamento(JustificativaModel $oJustificativaModel) {

    $oDaoJustificativaTipoAsse = new \cl_pontoeletronicojustificativatipoasse();
    $oDaoJustificativaTipoAsse->excluir(
      null,
      "rh205_pontoeletronicojustificativa = {$oJustificativaModel->getCodigo()}"
    );

    if($oDaoJustificativaTipoAsse->erro_status == '0') {
      throw new \DBException('Erro ao excluir os vínculos da Justificativa. Contate o suporte.');
    }
  }

  /**
   * @param integer $matricula
   * @param \DBDate $data
   *
   * @return AssentamentoJustificativa[]
   */
  public static function getAssentamentosPorMatriculaData($matricula, \DBDate $data) {

    $aAssentamentos = array();
    
    $oServidor          = \ServidorRepository::getInstanciaByCodigo($matricula);
    $aTipos             = array();
    $aTiposAssentamento = \TipoAssentamentoRepository::getInstanciasPorNaturezaComJustificativaConfigurada();
    $aTiposAssentamento = \TipoAssentamentoRepository::getInstanciasAfastamento();

    foreach ($aTiposAssentamento as $oTipoAssentamento) {
      $aTipos[] = $oTipoAssentamento->getSequencial();
    }

    $aAssentamentosEncontrados = \AssentamentoRepository::getAssentamentosPorServidor($oServidor, $aTipos, null, null, false);

    foreach ($aAssentamentosEncontrados as $oAssentamento) {

      if($oAssentamento->getDataConcessao()->getTimeStamp() <= $data->getTimeStamp()) {

        if($oAssentamento->getDataTermino() == null || $oAssentamento->getDataTermino()->getTimeStamp() >= $data->getTimeStamp()) {
          $aAssentamentos[] = $oAssentamento;
        }
      }
    }

    return $aAssentamentos;
  }

  /**
   * @param AssentamentoJustificativa $oAssentamentoJustificativa
   * @return mixed|null
   * @throws \DBException
   */
  public function getJustificativaPorTipoAssentamento($iTipoAssentamento) {

    $oDaoJustificativa = new \cl_pontoeletronicojustificativatipoasse();
    $sSqlJustificativa = $oDaoJustificativa->sql_query(
      null,
      'pontoeletronicojustificativa.*',
      null,
      "h12_tipo = 'S' and rh205_tipoasse = {$iTipoAssentamento}"
    );

    $rsJustificativa = db_query($sSqlJustificativa);

    if(!$rsJustificativa) {
      throw new \DBException('Erro ao buscar a justificativa configurada com o assentamento. Contate o suporte');
    }

    if(pg_num_rows($rsJustificativa) == 0) {
      return null;
    }

    return \db_utils::makeFromRecord($rsJustificativa, function($oRetorno) {

      $oJustificativaModel = new JustificativaModel();
      $oJustificativaModel->setCodigo($oRetorno->rh194_sequencial);
      $oJustificativaModel->setDescricao($oRetorno->rh194_descricao);
      $oJustificativaModel->setAbreviacao($oRetorno->rh194_sigla);

      return $oJustificativaModel;
    }, 0);
  }

  /**
   * Retorna objeto Justificativa para um tipo de assentamento do tipo afastamento
   * @param mixed $mTipoAssentamento
   * @return null | ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa
   * @throws \DBException
   */
  public function getJustificativaPorTipoAssentamentoAfastamento($mTipoAssentamento) {

    $aTiposAssentamento = array();

    if(is_array($mTipoAssentamento) && !empty($mTipoAssentamento)) {
      $aTiposAssentamento = $mTipoAssentamento;
    }
    
    $aTiposAssentamento = explode(',', $mTipoAssentamento);
    if(empty($aTiposAssentamento)) {
      $aTiposAssentamento[] = $mTipoAssentamento;
    }
    
    $oDaoTipoasse = new \cl_tipoasse;
    $sSqlTipoasse = $oDaoTipoasse->sql_query_file(
      null,
      "*",
      null,
      "    h12_tipo   = 'A'".
      "and h12_codigo IN (". implode(',', $aTiposAssentamento) .")"
    );

    $rsTipoasse = db_query($sSqlTipoasse);

    if(!$rsTipoasse) {
      throw new \DBException("Ocorreu um erro ao consultar o(s) tipo(s) de assentamento.\n". pg_last_error());
    }
    
    if(pg_num_rows($rsTipoasse) == 0) {
      return null;
    }

    return \db_utils::makeFromRecord($rsTipoasse, function($oRetorno){

      $oJustificativa = new JustificativaModel;
      $oJustificativa->setDescricao($oRetorno->h12_descr);
      $oJustificativa->setAbreviacao($oRetorno->h12_assent);
      $oJustificativa->setAbono(false);

      return $oJustificativa;
    }, 0);
  }
}
