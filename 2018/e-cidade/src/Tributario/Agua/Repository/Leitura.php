<?php

namespace ECidade\Tributario\Agua\Repository;

use cl_agualeitura;
use DateTime;
use DBDate;
use DBException;
use AguaLeitura;
use ECidade\Tributario\Agua\Entity\Leitura\Situacao;
use ECidade\Tributario\Agua\Leitura\ResumoMensal;

class Leitura {

  /**
   * @var cl_agualeitura
   */
  protected $oDao;

  /**
   * @return cl_agualeitura
   */
  protected function getDao() {

    if (!$this->oDao) {
      $this->oDao = new cl_agualeitura;
    }
    return clone $this->oDao;
  }

  /**
   * @param  resource $rsResultados
   * @param  integer $iLinha
   *
   * @return AguaLeitura
   */
  protected function hydrate($rsResultados, $iLinha) {

    $oDados = pg_fetch_object($rsResultados, $iLinha);

    $oLeitura = new AguaLeitura();

    $oLeitura->setCodigo($oDados->x21_codleitura);
    $oLeitura->setAno($oDados->x21_exerc);
    $oLeitura->setMes($oDados->x21_mes);
    $oLeitura->setSituacao($oDados->x21_situacao);
    $oLeitura->setCodigoHidrometro($oDados->x21_codhidrometro);
    $oLeitura->setCodigoLeiturista($oDados->x21_numcgm);
    $oLeitura->setCodigoUsuario($oDados->x21_usuario);
    $oLeitura->setLeitura($oDados->x21_leitura);
    $oLeitura->setConsumo($oDados->x21_consumo);
    $oLeitura->setExcesso($oDados->x21_excesso);
    $oLeitura->setHidrometroVirou($oDados->x21_virou == 't');
    $oLeitura->setTipo($oDados->x21_tipo);
    $oLeitura->setStatus($oDados->x21_status);
    $oLeitura->setSaldo($oDados->x21_saldo);
    $oLeitura->setCodigoContrato($oDados->x21_aguacontrato);

    if ($oDados->x21_dtleitura) {
      $oLeitura->setDataLeitura(new DBDate($oDados->x21_dtleitura));
    }

    if ($oDados->x21_dtinc) {
      $oLeitura->setDataInclusao(new DBDate($oDados->x21_dtinc));
    }

    $oSituacao = new Situacao;
    $oSituacao->setCodigo($oDados->x17_codigo);
    $oSituacao->setDescricao($oDados->x17_descr);
    $oSituacao->setRegra($oDados->x17_regra);

    $oLeitura->setSituacaoLeitura($oSituacao);

    return $oLeitura;
  }

  /**
   * @param  array  $aCriteria
   * @return string
   */
  protected function getQuery(array $aCriteria) {

    $aDefault = array(
      'aCampos' => null,
      'aWhere' => null,
      'sOrder' => null,
      'iLimit' => null,
      'iOffset' => null,
      'sGroupBy' => null,
    );

    $aCriteria = array_merge($aDefault, $aCriteria);
    $sWhere = $aCriteria['aWhere'] ? implode(' and ', $aCriteria['aWhere']) : null;
    $sCampos = $aCriteria['aCampos'] ? implode(', ', $aCriteria['aCampos']) : '*';

    return $this->getDao()->sql_query_ultimas_leituras(
      $sCampos,
      $sWhere,
      $aCriteria['sOrder'],
      $aCriteria['sGroupBy'],
      $aCriteria['iLimit'],
      $aCriteria['iOffset']
    );
  }

  /**
   * @param string $sSql
   *
   * @return array
   * @throws DBException
   */
  public function getResults($sSql) {

    $rsResultados = db_query($sSql);
    if (!$rsResultados) {
      throw new DBException('Não foi possível fazer a consulta.');
    }

    $aResultados = array();
    $iQtdResultados = pg_num_rows($rsResultados);
    for ($iLinha = 0; $iLinha < $iQtdResultados; $iLinha++) {
      $aResultados[] = $this->hydrate($rsResultados, $iLinha);
    }

    return $aResultados;
  }

  /**
   * @param  array $aCriteria
   *
   * @return AguaLeitura[]
   */
  public function findBy(array $aCriteria) {

    $sSql = $this->getQuery($aCriteria);
    return $this->getResults($sSql);
  }

  /**
   * @param  array $aCriteria
   *
   * @return AguaLeitura
   */
  public function findOneBy(array $aCriteria) {

    $aCriteria['iLimit'] = 1;
    $aResultados = $this->findBy($aCriteria);

    if (!$aResultados) {
      return null;
    }

    return current($aResultados);
  }

  /**
   * @param  integer $id
   *
   * @return AguaLeitura
   */
  public function find($id) {

    return $this->findOneBy(array(
      'aWhere' => array("{$this->sPrimaryKey} = {$id}"),
    ));
  }

  /**
   * Retorna a primeira leitura do contrato.
   *
   * @param int $iContrato
   *
   * @return AguaLeitura
   */
  public function findPrimeira($iContrato) {

    $aCriteria = array(
      'sOrder' => 'x21_exerc asc, x21_mes asc',
      'aWhere' => array(
        "x21_status = " . AguaLeitura::STATUS_ATIVA,
        "x55_aguacontrato = {$iContrato}",
      ),
    );

    return $this->findOneBy($aCriteria);
  }

  /**
   * Busca as leituras necessárias para aplicar as regras de média e penalidade.
   *
   * @param integer  $iContrato
   * @param integer  $iMes
   * @param integer  $iAno
   * @param array    $aLeituras
   * @param DateTime $oDataInicio
   * @param DateTime $oDataFim
   * @param DateTime $oDataStop
   *
   * @return AguaLeitura[]
   */
  public function findUltimas(
    $iContrato,
    $iMes,
    $iAno,
    array $aLeituras = array(),
    DateTime $oDataInicio = null,
    DateTime $oDataFim = null,
    DateTime $oDataStop = null
  ) {

    if ($oDataInicio) {

      /**
       * Aumenta o intervalo da busca a cada nova chamada recursiva.
       */
      $oDataInicio->modify("-1 month");
    }

    if (!$oDataInicio) {

      $oDataInicio = new DateTime("{$iAno}-{$iMes}-01");
      $oDataInicio->modify("-7 months");

      $oDataFim = new DateTime("{$iAno}-{$iMes}-01");

      $oPrimeiraLeitura = $this->findPrimeira($iContrato);
      if (!$oPrimeiraLeitura) {
        return array();
      }

      $oDataStop = new DateTime("{$oPrimeiraLeitura->getAno()}-{$oPrimeiraLeitura->getMes()}-01");
    }

    $sDataInicio = $oDataInicio->format('Y-m-d');

    $sDataFim = $oDataFim->format('Y-m-d');

    $aWhere = array(
      /**
       * @todo Buscar diretamente por contrato quando todas as leituras estiverem vinculadas aos contratos.
       */
      "x55_aguacontrato = {$iContrato}",
      "x21_status = " . AguaLeitura::STATUS_ATIVA,
      "(x21_exerc || '-' || x21_mes || '-' || '01')::date > '{$sDataInicio}'",
      "(x21_exerc || '-' || x21_mes || '-' || '01')::date <= '{$sDataFim}'",
    );

    $aCriteria = array(
      'aWhere' => $aWhere,
      'sOrder' => 'x21_exerc desc, x21_mes desc',
    );

    if ($aLeituras) {

      /**
       * Excluí da busca as leituras já encontradas.
       */
      $sCodigos = implode(', ', array_map(function (&$item) {
        return $item->getCodigo();
      }, $aLeituras));

      $aCriteria['aWhere'][] = "x21_codleitura not in({$sCodigos})";
    }

    $aLeiturasAdicionais = $this->findBy($aCriteria);

    if ($aLeiturasAdicionais) {

      /**
       * Adiciona as leituras mais antigas ao final da lista.
       */
      $aLeituras = array_merge($aLeituras, $aLeiturasAdicionais);

      /**
       * Se não existe mais nenhuma média sequencial então não precisamos buscar novas leituras.
       */
      $oLeituraMaisAntiga = end($aLeiturasAdicionais);
      if ($oLeituraMaisAntiga->getSituacaoLeitura()->getRegra() !== AguaLeitura::REGRA_MEDIA_ULTIMOS_MESES) {
        return $aLeituras;
      }
    }

    /**
     * Interrompe a recursividade caso tenha sido atingida a data da primeira leitura registrada para o hidrômetro.
     */
    if ($oDataInicio->getTimestamp() < $oDataStop->getTimestamp()) {
      return $aLeituras;
    }

    return $this->findUltimas($iContrato, $iMes, $iAno, $aLeituras, $oDataInicio, $oDataFim, $oDataStop);
  }

  /**
   * @param AguaLeitura[] $aLeituras
   *
   * @return ResumoMensal[]
   */
  public function agruparPorMes(array $aLeituras) {

    $aAgrupamento = array();
    foreach ($aLeituras as $oLeitura) {

      $sChave = (int) $oLeitura->getAno() . (int) $oLeitura->getMes();

      if (!isset($aAgrupamento[$sChave])) {
        $oResumoMensal = new ResumoMensal($oLeitura->getMes(), $oLeitura->getAno());
        $aAgrupamento[$sChave] = $oResumoMensal;
      } else {
        $oResumoMensal = $aAgrupamento[$sChave];
      }

      $oResumoMensal->adicionarLeitura($oLeitura);
    }

    return $aAgrupamento;
  }

  /**
   * @param int $iContrato
   * @param int $iMes
   * @param int $iAno
   *
   * @return AguaLeitura[]
   */
  public function findByMesAno($iContrato, $iMes, $iAno) {

    return $this->findBy(array(
      'aWhere' => array(
        "x21_status = " . AguaLeitura::STATUS_ATIVA,
        "x21_exerc = {$iAno}",
        "x21_mes = {$iMes}",
        "x55_aguacontrato = {$iContrato}",
      ),
    ));
  }

  /**
   * Busca a última leitura a partir do mês e ano de referência.
   *
   * @param int $iMatricula
   * @param int $iMes
   * @param int $iAno
   * @param null $iContrato
   * @param int $iLeituraIgnorada
   *
   * @return AguaLeitura
   * @throws DBException
   */
  public function findUltimaMesAno($iMatricula, $iMes, $iAno, $iContrato = null, $iLeituraIgnorada = null) {

    $aWhere = array(
      "x21_status = " . AguaLeitura::STATUS_ATIVA,
      "(x21_exerc::varchar || '-' || x21_mes::varchar || '-01')::date <= '{$iAno}-{$iMes}-01'",
      "x04_matric = {$iMatricula}",
    );

    if ($iLeituraIgnorada) {
      $aWhere[] = "x21_codleitura <> {$iLeituraIgnorada}";
      $aWhere[] = "(x21_dtleitura < (
        select x21_dtleitura from agualeitura where x21_codleitura = {$iLeituraIgnorada}
      ))";
    }

    /**
     * Contrato obrigatório a partir da implantação da tarifa.
     */
    if ($iAno >= 2017 and $iMes >= 7) {
      $aWhere[] = "x55_aguacontrato = {$iContrato}";
    }

    $sOrder = implode(', ', array(
      'x21_exerc desc',
      'x21_mes desc',
      'x21_dtleitura desc',
      'x21_dtinc desc',
      'x21_codleitura desc',
    ));

    $sWhere = implode(' and ', $aWhere);

    $sSql = "
    select
      *
    from 
      agualeitura
    inner join aguahidromatric     on x21_codhidrometro = x04_codhidrometro
    inner join aguasitleitura      on aguasitleitura.x17_codigo = agualeitura.x21_situacao
    left  join aguacontratoligacao on aguacontratoligacao.x55_aguahidromatric = agualeitura.x21_codhidrometro
    where
      {$sWhere}
    order by
      {$sOrder}
    limit 1";

    $aResultados = $this->getResults($sSql);
    if ($aResultados) {
      return current($aResultados);
    }

    return null;
  }
}
