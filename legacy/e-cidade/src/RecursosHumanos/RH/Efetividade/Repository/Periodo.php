<?php
/**
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

namespace ECidade\RecursosHumanos\RH\Efetividade\Repository;

use \ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo as PeriodoModel;
use \ECidade\RecursosHumanos\RH\Efetividade\Collection\Periodo as PeriodoCollection;

/**
 * Class Periodo
 * @package ECidade\RecursosHumanos\RH\Efetividade\Repository
 * @author F�bio Esteves <fabio.esteves@dbseller.com.br>
 */
class Periodo {

  /**
   * @var \ECidade\RecursosHumanos\RH\Efetividade\Collection\Periodo
   */
  private $oCollection;

  /**
   * @var \Instituicao
   */
  private $oInstituicao;

  /**
   * @var int|null
   */
  private $iExercicio;

  /**
   * @var bool
   */
  private $lTodasEfetividades = false;

  /**
   * @var null|string
   */
  private $iAnoSessao;

  /**
   * Periodo constructor.
   * @param null|\Instituicao $oInstituicao
   * @param null|int $iExercicio
   * @param bool $lTodasEfetividades
   * @param null|int $iAnoSessao
   */
  public function __construct($oInstituicao = null, $iExercicio = null, $lTodasEfetividades = false, $iAnoSessao = null) {

    if($oInstituicao == null) {
      $this->oInstituicao = \InstituicaoRepository::getInstituicaoSessao();
    }

    if(empty($iAnoSessao)) {
      $this->iAnoSessao = db_getsession("DB_instit");
    }

    $this->iExercicio         = $iExercicio;
    $this->lTodasEfetividades = $lTodasEfetividades;

    $this->getCollection();
  }

  /**
   * Retorna um per�odo de efetividade
   * @param $iExercicio
   * @param $iCompetencia
   * @param null|\Instituicao $oInstituicao
   * @return mixed
   * @throws \BusinessException
   * @throws \DBException
   * @throws \ParameterException
   */
  public static function getInstanciaPorExercicioCompetencia($iExercicio, $iCompetencia, $oInstituicao = null) {

    if($oInstituicao == null) {
      $oInstituicao = \InstituicaoRepository::getInstituicaoSessao();
    }

    if(empty($iExercicio)) {
      throw new \ParameterException('Exerc�cio n�o informado.');
    }

    if(empty($iCompetencia)) {
      throw new \ParameterException('Compet�ncia n�o informada.');
    }

    $oDaoPeriodoEfetividade    = new \cl_configuracoesdatasefetividade();
    $sWherePeriodoEfetividade  = "     rh186_exercicio::integer   = {$iExercicio}";
    $sWherePeriodoEfetividade .= " AND rh186_competencia::integer = {$iCompetencia}";
    $sWherePeriodoEfetividade .= " AND rh186_instituicao::integer = {$oInstituicao->getCodigo()}";
    $sSqlPeriodoEfetividade    = $oDaoPeriodoEfetividade->sql_query_file(null, '*', null, $sWherePeriodoEfetividade);
    $rsPeriodoEfetividade      = db_query($sSqlPeriodoEfetividade);

    if(!$rsPeriodoEfetividade) {
      throw new \DBException('Erro ao buscar as informa��es do per�odo da efetividade.');
    }

    if(pg_num_rows($rsPeriodoEfetividade) == 0) {
      throw new \BusinessException('Per�odo de efetividade n�o encontrado.');
    }

    return \db_utils::makeFromRecord($rsPeriodoEfetividade, function($oRetorno) {

      $oPeriodoEfetividade = new PeriodoModel();
      $oPeriodoEfetividade->setExercicio($oRetorno->rh186_exercicio);
      $oPeriodoEfetividade->setCompetencia($oRetorno->rh186_competencia);
      $oPeriodoEfetividade->setDataInicio(new \DBDate($oRetorno->rh186_datainicioefetividade));
      $oPeriodoEfetividade->setDataFim(new \DBDate($oRetorno->rh186_datafechamentoefetividade));

      return $oPeriodoEfetividade;
    }, 0);
  }

  /**
   * Cria a cole��o de per�odos
   * @throws \DBException
   */
  public function getCollection() {

    $aWhereConfiguracoesEfetividade = array("rh186_instituicao = {$this->oInstituicao->getCodigo()}");
    $sOrder                         = 'rh186_exercicio, rh186_competencia';

    if(!is_null($this->iExercicio)) {

      $aWhereConfiguracoesEfetividade[] = "rh186_exercicio = {$this->iExercicio}";
    }

    if(!$this->lTodasEfetividades) {
      $aWhereConfiguracoesEfetividade[]= "rh186_processado is false";
    }

    $sCamposConfiguracoesEfetividade  = 'rh186_exercicio, lpad(rh186_competencia, 2, \'0\') as rh186_competencia';
    $sCamposConfiguracoesEfetividade .= ', rh186_datainicioefetividade, rh186_datafechamentoefetividade';
    $sCamposConfiguracoesEfetividade .= ', rh186_instituicao';

    $oDaoConfiguracoesEfetividade = new \cl_configuracoesdatasefetividade();
    $sSqlConfiguracoesEfetividade = $oDaoConfiguracoesEfetividade->sql_query_file(
      null,
      $sCamposConfiguracoesEfetividade,
      $sOrder,
      implode(' AND ', $aWhereConfiguracoesEfetividade)
    );

    $rsConfiguracoesEfetividade = db_query($sSqlConfiguracoesEfetividade);

    if(!$rsConfiguracoesEfetividade) {
      throw new \DBException('Erro ao buscar o per�odo de efetividade aberto.');
    }

    if(pg_num_rows($rsConfiguracoesEfetividade) == 0) {
      $this->oCollection = new PeriodoCollection();
    }

    $this->oCollection = PeriodoCollection::makeCollectionFromArray(\db_utils::getCollectionByRecord($rsConfiguracoesEfetividade));
  }

  /**
   * Retorna os per�odos existentes entre as datas informadas
   * @param \DBDate $oDataInicio
   * @param \DBDate $oDataFim
   * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo[]
   * @throws \BusinessException
   */
  public function getPeriodosEntreDatas(\DBDate $oDataInicio, \DBDate $oDataFim) {

    if(count($this->oCollection->getPeriodos()) == 0) {
      throw new \BusinessException('Nenhum per�odo de efetividade encontrado entre as datas informadas.');
    }

    $aPeriodosRetorno = array();
    $aDatasIntervalo  = \DBDate::getDatasNoIntervalo($oDataInicio, $oDataFim);

    if($this->oCollection->getPrimeiroPeriodo()->getDataInicio()->getTimeStamp() > $oDataInicio->getTimeStamp()) {

      $sMensagem  = "Data de in�cio informada � menor que a data do primeiro per�odo de efetividade configurado";
      $sMensagem .= " (Exerc�cio: {$this->oCollection->getPrimeiroPeriodo()->getExercicio()}";
      $sMensagem .= " - {$this->oCollection->getPrimeiroPeriodo()->getDataInicio()->getDate(\DBDate::DATA_PTBR)}).";
      $sMensagem .= "\nPara configura��o dos per�odos de efetividade, acesse:";
      $sMensagem .= "\n- RH > Procedimentos > Efetividade > Par�metros > Per�odos de Efetividade";

      throw new \BusinessException($sMensagem);
    }

    if($this->oCollection->getUltimoPeriodo()->getDataFim()->getTimeStamp() < $oDataFim->getTimeStamp()) {

      $sMensagem  = "Data de fim informada � maior que a data do �ltimo per�odo de efetividade configurado";
      $sMensagem .= " (Exerc�cio: {$this->oCollection->getUltimoPeriodo()->getExercicio()}";
      $sMensagem .= " - {$this->oCollection->getUltimoPeriodo()->getDataFim()->getDate(\DBDate::DATA_PTBR)}).";
      $sMensagem .= "\nPara configura��o dos per�odos de efetividade, acesse:";
      $sMensagem .= "\n- RH > Procedimentos > Efetividade > Par�metros > Per�odos de Efetividade";

      throw new \BusinessException($sMensagem);
    }

    foreach($this->oCollection->getPeriodos() as $oPeriodo) {

      $sChave = "{$oPeriodo->getExercicio()}#{$oPeriodo->getCompetencia()}";

      foreach($aDatasIntervalo as $oData) {

        if(    \DBDate::dataEstaNoIntervalo($oData, $oPeriodo->getDataInicio(), $oPeriodo->getDataFim())
            && !array_key_exists($sChave, $aPeriodosRetorno)
          ) 
        {
          if($oPeriodo->getDataInicio()->getTimeStamp() < $oDataInicio->getTimeStamp()) {
            $oPeriodo->setDataInicio($oDataInicio);
          }
          
          if($oPeriodo->getDataFim()->getTimeStamp() > $oDataFim->getTimeStamp()) {
            $oPeriodo->setDataFim($oDataFim);
          }

          $aPeriodosRetorno[$sChave] = $oPeriodo;
        }
      }
    }

    return $aPeriodosRetorno;
  }

  /**
   * Busca o c�digo do arquivo importado a qual compet�ncia est� vinculada
   * @param PeriodoModel $oPeriodoModel
   * @return PeriodoModel
   * @throws \DBException
   */
  public function getCodigoArquivoPorPeriodo(PeriodoModel $oPeriodoModel) {

    $oDaoPontoArquivo = new \cl_pontoeletronicoarquivo();

    $sWherePontoArquivo  = "     rh196_efetividade_exercicio   = {$oPeriodoModel->getExercicio()}";
    $sWherePontoArquivo .= " AND rh196_efetividade_competencia = '{$oPeriodoModel->getCompetencia()}'";
    $sWherePontoArquivo .= " AND rh196_instituicao             = {$oPeriodoModel->getInstituicao()->getCodigo()}";

    $sSqlPontoArquivo = $oDaoPontoArquivo->sql_query_file(null, 'rh196_sequencial', null, $sWherePontoArquivo);
    $rsPontoArquivo   = db_query($sSqlPontoArquivo);

    if(!$rsPontoArquivo) {
      throw new \DBException('Erro ao buscar o arquivo ao qual a compet�ncia est� vinculado. Contate o suporte');
    }

    if(pg_num_rows($rsPontoArquivo) > 0) {
      $oPeriodoModel->setCodigoArquivo(\db_utils::fieldsMemory($rsPontoArquivo, 0)->rh196_sequencial);
    }

    return $oPeriodoModel;
  }
}