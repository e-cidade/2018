<?php

/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 11/10/16
 * Time: 09:25
 */
class PontoRubricaPeriodoRepository extends BaseClassRepository {

  /**
   * Sobrescreve o atributo da classe pai para
   * manter apenas as referências da classe atual
   */
  protected static $oInstance;

  /**
   * Constrói o objeto
   */
  public function make($iCodigo) {

    $oDaoPontoSalarioDataLimite = new cl_pontosalariodatalimite();
    $sSqlPontoSalarioDataLimite = $oDaoPontoSalarioDataLimite->sql_query_file($iCodigo);
    $rsPontoSalarioDataLimite   = db_query($sSqlPontoSalarioDataLimite);

    if(!$rsPontoSalarioDataLimite) {
      throw new DBException("Ocorreu um erro ao buscar as rubricas.");
    }

    if (pg_num_rows($rsPontoSalarioDataLimite) == 0) {
      throw new BusinessException("Nenhuma rubrica encontrada para o código informado.");
    }

    $oPontoRubricaPeriodo = new PontoRubricaPeriodo;

    db_utils::makeFromRecord($rsPontoSalarioDataLimite, function ($oDados) use ($oPontoRubricaPeriodo) {

      $oPontoRubricaPeriodo->setCodigo($oDados->rh183_sequencial);
      $oPontoRubricaPeriodo->setRubrica(RubricaRepository::getInstanciaByCodigo($oDados->rh183_rubrica));
      $oPontoRubricaPeriodo->setDataInicio(new DBDate($oDados->rh183_datainicio));
      $oPontoRubricaPeriodo->setDataFim(new DBDate($oDados->rh183_datafim));
      $oPontoRubricaPeriodo->setServidor(ServidorRepository::getInstanciaByCodigo($oDados->rh183_matricula));
      $oPontoRubricaPeriodo->setQuantidade($oDados->rh183_quantidade);
      $oPontoRubricaPeriodo->setValor($oDados->rh183_valor);
      $oPontoRubricaPeriodo->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($oDados->rh183_instituicao));
    }, 0);

    $this->add($oPontoRubricaPeriodo);

    return $oPontoRubricaPeriodo;
  }

  public static function persist(PontoRubricaPeriodo $oPontoRubricaPeriodo) {

    $oDaoPontoRubrica                    = new cl_pontosalariodatalimite();
    $oDaoPontoRubrica->rh183_datainicio  = $oPontoRubricaPeriodo->getDataInicio()->getDate();
    $oDaoPontoRubrica->rh183_datafim     = $oPontoRubricaPeriodo->getDataFim()->getDate();
    $oDaoPontoRubrica->rh183_matricula   = $oPontoRubricaPeriodo->getServidor()->getMatricula();
    $oDaoPontoRubrica->rh183_rubrica     = $oPontoRubricaPeriodo->getRubrica()->getCodigo();
    $oDaoPontoRubrica->rh183_instituicao = $oPontoRubricaPeriodo->getInstituicao()->getCodigo();
    $oDaoPontoRubrica->rh183_quantidade  = $oPontoRubricaPeriodo->getQuantidade();
    $oDaoPontoRubrica->rh183_valor       = $oPontoRubricaPeriodo->getValor();

    if ($oPontoRubricaPeriodo->getCodigo()) {

      $oDaoPontoRubrica->rh183_sequencial = $oPontoRubricaPeriodo->getCodigo();
      $oDaoPontoRubrica->alterar($oPontoRubricaPeriodo->getCodigo());
    } else {
      $oDaoPontoRubrica->incluir(null);
    }

    if ($oDaoPontoRubrica->erro_status == 0) {
      throw new DBException('Não foi possível salvar os dados de controle de período da Rubrica.');
    }

    return true;
  }

  public static function remove($iCodigo) {

    $oDaoPontoRubrica = new cl_pontosalariodatalimite();
    $oDaoPontoRubrica->excluir(null, "rh183_sequencial={$iCodigo}");

    if ($oDaoPontoRubrica->erro_status == 0) {
      throw new DBException('Não foi possível remover os dados da Rubrica.');
    }
    
    $oRepository = static::getInstance();
    unset($oRepository->aColecao[$iCodigo]);
  }


  public static function getPontoRubricasByServidor(Servidor $oServidor) {

    $oRepository = self::getInstance();

    $oDaoPontoSalarioDataLimite = new cl_pontosalariodatalimite();
    $sSqlPontoSalarioDataLimite = $oDaoPontoSalarioDataLimite->sql_query_file(null, 'rh183_sequencial', null, "rh183_matricula = {$oServidor->getMatricula()}");
    $rsPontoSalarioDataLimite   = db_query($sSqlPontoSalarioDataLimite);


    if (!$rsPontoSalarioDataLimite) {
      throw new DBException("Ocorreu um erro ao buscar as rubricas por servidor.");
    }

    $aRubricas = array();

    $aRubricas = db_utils::makeCollectionFromRecord($rsPontoSalarioDataLimite, function ($oDados) use ($oRepository) {
      return $oRepository->make($oDados->rh183_sequencial);
    });

    return $aRubricas;
  }

  /**
   * @param \Servidor $oServidor
   * @param \Rubrica  $oRubrica
   * @return PontoRubricaPeriodo
   * @throws \DBException
   */
  public static function getPontoRubricaPorServidorRubrica(Servidor $oServidor, Rubrica $oRubrica) {

    $oRepository = PontoRubricaPeriodoRepository::getInstance();

    $aWhere = array(
      "rh183_matricula = {$oServidor->getMatricula()}",
      "rh183_rubrica   = '{$oRubrica->getCodigo()}'",
    );
    $sWhere = implode(" and ", $aWhere);
    $oDaoPontoSalarioDataLimite = new cl_pontosalariodatalimite();

    $sSqlPontoSalarioDataLimite = $oDaoPontoSalarioDataLimite->sql_query_file(null, 'rh183_sequencial', null, $sWhere);
    $rsPontoSalarioDataLimite   = db_query($sSqlPontoSalarioDataLimite);


    if (!$rsPontoSalarioDataLimite) {
      throw new DBException("Ocorreu um erro ao buscar as rubricas por servidor.");
    }

    $iTotalLinhas = pg_num_rows($rsPontoSalarioDataLimite);
    if ($iTotalLinhas == 0) {
      return null;
    }
    $oRubricas = null;
    $oRubricas = db_utils::makeFromRecord($rsPontoSalarioDataLimite, function ($oDados) use ($oRepository) {
      return $oRepository->make($oDados->rh183_sequencial);
    }, 0);

    return $oRubricas;
  }
}