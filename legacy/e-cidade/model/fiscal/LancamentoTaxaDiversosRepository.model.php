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

/**
* Classe Repository para manipulação do Lancamento de uma taxa de diversos
*/
class LancamentoTaxaDiversosRepository extends BaseClassRepository {

  /**
   * Sobrescreve o atributo da classe pai para
   * manter apenas as referências da classe atual
   */
  protected static $oInstance;

  protected function make($iCodigo) {

    $oDaoLancamentoTaxadiversos     = new cl_lancamentotaxadiversos;
    $sCamposLancamentoTaxaDiversos  = "*,";
    $sCamposLancamentoTaxaDiversos .= "(select dv14_data_calculo
                                          from diversoslancamentotaxa 
                                         where dv14_lancamentotaxadiversos = lancamentotaxadiversos.y120_sequencial 
                                      order by dv14_sequencial desc 
                                         limit 1) as dv14_data_calculo
                                      ";
    $sSqlLancamentoTaxadiversos = $oDaoLancamentoTaxadiversos->sql_query_file($iCodigo, $sCamposLancamentoTaxaDiversos);
    $rsLancamentoTaxadiversos   = db_query($sSqlLancamentoTaxadiversos);

    if(!$rsLancamentoTaxadiversos) {
      throw new DBException("Ocorreu um erro ao buscar o lançamento de taxa de diversos.");
    }

    if(pg_num_rows($rsLancamentoTaxadiversos) == 0) {
      throw new BusinessException("Não há lançamento de taxa para o código informado.");
    }

    $oLancamentoTaxaDiversos = new LancamentoTaxaDiversos;

    db_utils::makeFromRecord($rsLancamentoTaxadiversos, function ($oDados) use ($oLancamentoTaxaDiversos) {

      $oLancamentoTaxaDiversos->setCodigo         ($oDados->y120_sequencial);
      $oLancamentoTaxaDiversos->setCGM            (CgmRepository::getByCodigo($oDados->y120_cgm));
      $oLancamentoTaxaDiversos->setNaturezaTaxa   (NaturezaTaxaDiversosRepository::getInstanciaPorCodigo($oDados->y120_taxadiversos));
      $oLancamentoTaxaDiversos->setUnidade        ($oDados->y120_unidade);
      $oLancamentoTaxaDiversos->setPeriodo        ($oDados->y120_periodo);
      $oLancamentoTaxaDiversos->setDataInicio     (!empty($oDados->y120_datainicio) ? new DBDate($oDados->y120_datainicio) : '');
      $oLancamentoTaxaDiversos->setDataFim        (!empty($oDados->y120_datafim) ? new DBDate($oDados->y120_datafim) : '');
      $oLancamentoTaxaDiversos->setDataUltimoCalculoGeral (!empty($oDados->dv14_data_calculo) ? new DBDate($oDados->dv14_data_calculo) : null);
      $oLancamentoTaxaDiversos->setInscricaoMunicipal($oDados->y120_issbase);
    }, 0);
    
    return $oLancamentoTaxaDiversos;
  }

  public static function getLancamentosParaCalculoGeral($mNatureza = null, $iGrupo = null) {
    
    $sAnoAtual                      = db_getsession('DB_anousu');
    $oDaoLancamentoTaxadiversos     = new cl_lancamentotaxadiversos;
    $sCamposLancamentoTaxaDiversos  = "*,";
    $sCamposLancamentoTaxaDiversos .= "(select dv14_data_calculo
                                          from diversoslancamentotaxa 
                                         where dv14_lancamentotaxadiversos = lancamentotaxadiversos.y120_sequencial 
                                      order by dv14_sequencial desc 
                                         limit 1) as dv14_data_calculo
                                      ";
    $aWhereLancamentoTaxadiversos   = array();
    $aWhereLancamentoTaxadiversos[] = "y119_tipo_calculo = 'G'";
    $aWhereLancamentoTaxadiversos[] = "(y120_datafim is null or extract(year from y120_datafim) >= {$sAnoAtual})";

    if($mNatureza != null) {
      $aWhereLancamentoTaxadiversos[] = "y119_sequencial = {$mNatureza}";
    }

    if($iGrupo != null) {
      $aWhereLancamentoTaxadiversos[] = "y119_grupotaxadiversos = {$iGrupo}";
    }

    $sSqlLancamentoTaxadiversos = $oDaoLancamentoTaxadiversos->sql_query(null, $sCamposLancamentoTaxaDiversos, null, implode(" and ", $aWhereLancamentoTaxadiversos));
    $rsLancamentoTaxadiversos   = db_query($sSqlLancamentoTaxadiversos);
    $aLancamentos               = array();

    if(!$rsLancamentoTaxadiversos) {
      throw new DBException("Ocorreu um erro ao buscar o lançamento de taxa de diversos.");
    }

    if(pg_num_rows($rsLancamentoTaxadiversos) > 0) {

      $aLancamentos = db_utils::makeCollectionFromRecord($rsLancamentoTaxadiversos, function ($oDados) {

        $oLancamentoTaxaDiversos = new LancamentoTaxaDiversos;

        $oLancamentoTaxaDiversos->setCodigo        ($oDados->y120_sequencial);
        $oLancamentoTaxaDiversos->setCgm           (CgmRepository::getByCodigo($oDados->y120_cgm));
        $oLancamentoTaxaDiversos->setNaturezaTaxa  (NaturezaTaxaDiversosRepository::getInstanciaPorCodigo($oDados->y120_taxadiversos));
        $oLancamentoTaxaDiversos->setUnidade       ($oDados->y120_unidade);
        $oLancamentoTaxaDiversos->setPeriodo       ($oDados->y120_periodo);
        $oLancamentoTaxaDiversos->setDataInicio    (!empty($oDados->y120_datainicio) ? new DBDate($oDados->y120_datainicio) : '');
        $oLancamentoTaxaDiversos->setDataFim       (!empty($oDados->y120_datafim) ? new DBDate($oDados->y120_datafim) : '');
        $oLancamentoTaxaDiversos->setDataUltimoCalculoGeral (!empty($oDados->dv14_data_calculo) ? new DBDate($oDados->dv14_data_calculo) : null);
        $oLancamentoTaxaDiversos->setInscricaoMunicipal($oDados->y120_issbase);

        return $oLancamentoTaxaDiversos;
      });
    }

    return $aLancamentos;
  }

  /**
   * Retorna as informações referentes a uma taxa lançada
   * @param int $iNumcgm
   * @param int $iNumnov
   * @return string
   * @throws DBException
   */
  public static function getObservacoesTaxas($iNumcgm = null, $iNumnov, $iInscricao = null) {

    $oDaoDiversos     = new cl_diversoslancamentotaxa();
    $sCamposDiversos  = "y119_unidade, y120_datainicio, y120_datafim, y118_descricao, y119_tipo_periodo, y120_periodo";
    $sCamposDiversos .= ", y119_natureza, dv05_obs, y120_unidade";
    $sWhereDiversos   = "k00_numcgm = {$iNumcgm} AND k00_numnov = {$iNumnov}";

    if(!empty($iInscricao)) {
      $sWhereDiversos = "y120_issbase = {$iInscricao} AND k00_numnov = {$iNumnov}";
    }

    $sSqlDiversos = $oDaoDiversos->sql_query_observacoes_taxa(null, $sCamposDiversos, null, $sWhereDiversos);
    $rsDiversos   = db_query($sSqlDiversos);

    if(!$rsDiversos) {
      throw new DBException('Erro ao buscar as informações da taxa.');
    }

    if(pg_num_rows($rsDiversos) == 0) {
      return '';
    }

    $aPeriodo          = array('D' => 'Dias', 'M' => 'Meses', 'A' => 'Meses');
    $oRetornoDiversos  = db_utils::fieldsMemory($rsDiversos, 0);
    $sUnidade          = LancamentoTaxaDiversos::getDescricaoUnidade($oRetornoDiversos->y119_unidade);

    $oDataInicio = !empty($oRetornoDiversos->y120_datainicio) ? new DBDate($oRetornoDiversos->y120_datainicio) : null;
    $oDataFim    = !empty($oRetornoDiversos->y120_datafim) ? new DBDate($oRetornoDiversos->y120_datafim) : null;
    $sDataInicio = !empty($oDataInicio) ? $oDataInicio->getDate(DBDate::DATA_PTBR) : '';
    $sDataFim    = !empty($oDataFim) ? $oDataFim->getDate(DBDate::DATA_PTBR) : '';

    $sObservacoes  = "Taxa: {$oRetornoDiversos->y118_descricao} | Unidade: {$oRetornoDiversos->y120_unidade}( Referência: {$sUnidade} )";
    $sObservacoes .= " | Quantidade de {$aPeriodo[$oRetornoDiversos->y119_tipo_periodo]}: {$oRetornoDiversos->y120_periodo}";
    $sObservacoes .= " | Data de Início: {$sDataInicio} | Data de Fim: {$sDataFim} | {$oRetornoDiversos->y119_natureza}";

    return $sObservacoes;
  }
}