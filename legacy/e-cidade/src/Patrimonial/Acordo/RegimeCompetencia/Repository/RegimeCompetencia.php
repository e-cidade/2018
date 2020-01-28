<?php
namespace ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository;
use BusinessException;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Item;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\RegimeCompetencia as RegimeCompetenciaModel;

class RegimeCompetencia {

  /**
   * @param \Acordo $acordo
   * @return \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\RegimeCompetencia|null
   * @throws \BusinessException
   */
  public function getByAcordo(\Acordo $acordo) {

    $oDaoAcordoProgramacaoFinanceira = new \cl_acordoprogramacaofinanceira();
    $sWhere     = "ac34_acordo = {$acordo->getCodigo()}";
    $sSqlAcordo = $oDaoAcordoProgramacaoFinanceira->sql_query(null, "ac34_sequencial, programacaofinanceira.*",null, $sWhere);
    $rsAcordo    = db_query($sSqlAcordo);
    if (!$rsAcordo) {
      throw new BusinessException("Erro ao consultar programação do Regime de competência do acordo.");
    }
    if (pg_num_rows($rsAcordo) == 0) {
      return null;
    }
    return $this->make($acordo, \db_utils::fieldsMemory($rsAcordo, 0));
  }

  public function make(\Acordo $acordo, $dados) {

    $oRegimeCompetencia = new RegimeCompetenciaModel();
    $oRegimeCompetencia->setAcordo($acordo);
    $oRegimeCompetencia->setCodigo($dados->k117_sequencial);
    $oRegimeCompetencia->setDespesaAntecipada($dados->k117_despesaantecipada == 't');
    $oRegimeCompetencia->setConta(\ContaPlanoPCASPRepository::getContaByCodigo($dados->k117_conta, db_getsession("DB_anousu")));
    return $oRegimeCompetencia;
  }

  /**   
   * @return \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela[]
   * @throws \BusinessException
   */
  public function getParcelasDoRegime(RegimeCompetenciaModel $regimeCompetencia) {

    $oDaoItem     = new \cl_acordoprogramacaofinanceira();
    $where        = " k117_sequencial = {$regimeCompetencia->getCodigo()}";
    $sSqlParcelas = $oDaoItem->sql_query_parcelas(null, "programacaofinanceiraparcela.*", 'k118_ano, k118_mes', $where);
    $rsParcelas    = db_query($sSqlParcelas);
    if (!$rsParcelas) {
      throw new BusinessException("Erro ao pesquisar parcelas do acordo {$regimeCompetencia->getAcordo()->getCodigo()}");
    }

    $parcelas = \db_utils::makeCollectionFromRecord($rsParcelas, function ($dados) {

      $parcela = new Parcela();
      $parcela->setCodigo($dados->k118_sequencial);
      $parcela->setCompetencia(new \DBCompetencia($dados->k118_ano, $dados->k118_mes));
      $parcela->setNumero($dados->k118_parcela);
      $parcela->setValor($dados->k118_valor);
      $parcela->setReconhecida($dados->k118_reconhecido == 't');
      return $parcela;
    });

    return $parcelas;
  }

  /**
   * Persiste os dados do regime de competencia
   * @param \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\RegimeCompetencia $regimeCompetencia
   * @throws \BusinessException
   */
  public function persist(RegimeCompetenciaModel $regimeCompetencia) {

    $oDaoProgramacao       = new \cl_programacaofinanceira();
    $oDaoProgramacaoAcordo = new \cl_acordoprogramacaofinanceira();
    $oDaoProgramacao->k117_despesaantecipada = $regimeCompetencia->isDespesaAntecipada() ? 'true' : 'false';
    if ($regimeCompetencia->getConta() != '') {
      $oDaoProgramacao->k117_conta = $regimeCompetencia->getConta()->getCodigoConta();
    }
    if ($regimeCompetencia->getCodigo() == '') {
      
      $oDaoProgramacao->k117_data              = date('Y-m-d');
      $oDaoProgramacao->k117_diapagamento      = '1';
      $oDaoProgramacao->k117_id_usuario        = db_getsession("DB_id_usuario");
      $oDaoProgramacao->incluir(null);
      if ($oDaoProgramacao->erro_status == 0) {
        throw new BusinessException("Erro ao salvar dados da programação do regime de competencia acordo!\n".$oDaoProgramacao->erro_msg);
      }
      $oDaoProgramacaoAcordo->ac34_programacaofinanceira = $oDaoProgramacao->k117_sequencial;
      $oDaoProgramacaoAcordo->ac34_acordo                = $regimeCompetencia->getAcordo()->getCodigo();
      $oDaoProgramacaoAcordo->incluir(null);
      if ($oDaoProgramacaoAcordo->erro_status == 0) {
        throw new BusinessException("Erro ao salvar dados da programação do regime de competencia acordo!");
      }
      $regimeCompetencia->setCodigo($oDaoProgramacao->k117_sequencial);
    } else {
      
      $oDaoProgramacao->k117_sequencial = $regimeCompetencia->getCodigo();
      $oDaoProgramacao->alterar($regimeCompetencia->getCodigo());if ($oDaoProgramacao->erro_status == 0) {
        throw new BusinessException("Erro ao salvar dados da programação do regime de competencia acordo!");
      }
    }
  }

  /**
   * Remove as parcelas do item
   * @param Parcela[] $parcelas
   * @throws \BusinessException
   * @throws \DBException
   *
   */
  public function removerParcelas(RegimeCompetenciaModel $regimeCompetencia, array $parcelas=null) {

    $oDaoParcelas   = new \cl_programacaofinanceiraparcela();
    $sWhereParcelas = "k118_programacaofinanceira = {$regimeCompetencia->getCodigo()} and k118_reconhecido is false";
    if (!empty($parcelas) && count($parcelas) > 0) {

      $aCodigoParcela = array();
      foreach ($parcelas as $parcela) {
        $aCodigoParcela[] = $parcela->getCodigo();
      }
      $sWhereParcelas .= " and k118_sequencial in(".implode(",", $aCodigoParcela).")";
    }

    $oDaoParcelas->excluir(null, $sWhereParcelas);
    if ($oDaoParcelas->erro_status == 0) {
      throw new BusinessException("Erro ao remover parcela do item");
    }
  }

 /**
   * @todo Mover para repositorio de parcelas
   * @param \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\RegimeCompetencia $regimeCompetencia
   * @param \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela           $parcela
   * @throws \BusinessException
   */
  public function persistirParcela(RegimeCompetenciaModel $regimeCompetencia, Parcela $parcela) {

    $oDaoProgramacaoItensParcela                                 = new \cl_programacaofinanceiraparcela();
    $oDaoProgramacaoItensParcela->k118_ano                       = $parcela->getCompetencia()->getAno();
    $oDaoProgramacaoItensParcela->k118_mes                       = $parcela->getCompetencia()->getMes();
    $oDaoProgramacaoItensParcela->k118_parcela                   = "{$parcela->getNumero()}";
    $oDaoProgramacaoItensParcela->k118_programacaofinanceira     = $regimeCompetencia->getCodigo();
    $oDaoProgramacaoItensParcela->k118_reconhecido               = $parcela->isReconhecida() ? 'true': 'false';
    $oDaoProgramacaoItensParcela->k118_valor                     = $parcela->getValor();

    if ($parcela->getCodigo() == '') {

      $oDaoProgramacaoItensParcela->incluir(null);
      $parcela->setCodigo($oDaoProgramacaoItensParcela->k118_sequencial);
    } else {
      $oDaoProgramacaoItensParcela->k118_sequencial = $parcela->getCodigo();
      $oDaoProgramacaoItensParcela->alterar($parcela->getCodigo());
    }
    if ($oDaoProgramacaoItensParcela->erro_status == 0) {
      throw new BusinessException($oDaoProgramacaoItensParcela->erro_msg);
    }
  }

  /**
   * @param \Acordo        $acordo
   * @param \DBCompetencia $competencia
   * @return Parcela
   * @throws \DBException
   */
  public function getParcelaPorAcordoECompetencia(\Acordo $acordo, \DBCompetencia $competencia) {

    $oDaoProgramacaoItens  = new \cl_acordoprogramacaofinanceira();

    $where        = "ac16_sequencial = {$acordo->getCodigo()} and k118_ano = {$competencia->getAno()} and k118_mes = {$competencia->getMes()} ";
    $sSqlParcelas = $oDaoProgramacaoItens->sql_query_parcelas(null, "programacaofinanceiraparcela.* ", null, $where);
    $rsDados      = db_query($sSqlParcelas);
    if (!$rsDados) {
      throw new \DBException("Erro ao pesquisar dados do item.");
    }

    if (pg_num_rows($rsDados) == 0) {
      return null;
    }

    $stdDadosParcela = \db_utils::fieldsMemory($rsDados, 0);
    $parcela = new Parcela();
    $parcela->setCodigo($stdDadosParcela->k118_sequencial);
    $parcela->setCompetencia(new \DBCompetencia($stdDadosParcela->k118_ano, $stdDadosParcela->k118_mes));
    $parcela->setNumero($stdDadosParcela->k118_parcela);
    $parcela->setValor($stdDadosParcela->k118_valor);
    $parcela->setReconhecida($stdDadosParcela->k118_reconhecido == 't');
    return $parcela;
  }
}