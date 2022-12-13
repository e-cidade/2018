<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
namespace ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository;

use Acordo;
use DBCompetencia;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Reconhecimento as ReconhecimentoModel;

/**
 * Class Reconhecimento
 * @package ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository
 */
class Reconhecimento {

  /**
   * Retorna uma coleção de Reconhecimentos para o reconhecimento da competencia
   * @param \Instituicao   $instituicao
   * @param \DBCompetencia $competencia
   * @param \Acordo|null   $acordo
   * @return ReconhecimentoModel[]
   * @throws \DBException
   */
  public function getAcordosParaReconhecimento(\Instituicao $instituicao, DBCompetencia $competencia = null, Acordo $acordo = null) {

    $aWhere = array("ac16_instit = {$instituicao->getCodigo()}");
    if (!empty($acordo)) {
      $aWhere[] = "ac16_sequencial = {$acordo->getCodigo()}";
    }

    if (!empty($competencia)) {
      $aWhere[] = "k118_ano = {$competencia->getAno()} and k118_mes = {$competencia->getMes()}";
    }
    $aWhere[] = "k118_reconhecido is false";
    $sWhere      = implode(" and ", $aWhere);
    return self::getReconhecimentos($sWhere);
  }

  /**
   * @param \Instituicao   $instituicao
   * @param \DBCompetencia $competencia
   * @param \Acordo|null   $acordo
   *
   * @return ReconhecimentoModel[]
   * @throws \DBException
   */
  public static function getReconhecimentosAbertosAteCompetencia(\Instituicao $instituicao, DBCompetencia $competencia, Acordo $acordo = null) {

    $aWhere = array("ac16_instit = {$instituicao->getCodigo()}");

    if (!empty($acordo)) {
      $aWhere[] = "ac16_sequencial = {$acordo->getCodigo()}";
    }
    $aWhere[] = "((k118_mes <= {$competencia->getMes()} and k118_ano = {$competencia->getAno()}) or k118_ano < {$competencia->getAno()})";
    $aWhere[] = "k118_reconhecido is false";

    $sWhere      = implode(" and ", $aWhere);
    return self::getReconhecimentos($sWhere);
  }

  /**
   * @param \DBCompetencia|null $oCompetencia
   * @param                     $iCredor
   * @param                     $iContrato
   * @return ReconhecimentoModel[]
   * @throws \DBException
   */
  public static function getReconhecimentosFechados(DBCompetencia $oCompetencia = null, $iCredor, $iContrato) {

    $oProgramacaoFinanceira    = new \cl_programacaofinanceira();
    $lBuscaAnosAnteriores = true;
    $sSqlProgramacaoFinanceira = $oProgramacaoFinanceira->sql_query_liquidacao($oCompetencia, $iCredor, $iContrato, $lBuscaAnosAnteriores);
    $rsProgramacaoFinanceira   = db_query($sSqlProgramacaoFinanceira);
    if (!$rsProgramacaoFinanceira) {
      throw new \DBException("Não foi possível pesquisar acordos para reconhecimento da competência.");
    }

    $iParcela          = 0;
    $icontratoCorrente = null;
    $aContratos = \db_utils::makeCollectionFromRecord($rsProgramacaoFinanceira, function($dados) use(&$iParcela, &$icontratoCorrente) {

      $oAcordo                      = \AcordoRepository::getByCodigo($dados->acordo);

      $oReconhecimento = new ReconhecimentoModel();

      $oReconhecimento->setAcordo($oAcordo);
      $oReconhecimento->setCompetencia(new DBCompetencia($dados->k118_ano, $dados->k118_mes));
      $oReconhecimento->setValor($dados->valortotal);
      $oReconhecimento->setValorReconhecido($dados->valorreconhecido);
      $oReconhecimento->setValorRealizado($dados->valorrealizado);
      if ($dados->k117_despesaantecipada == 't') {
        $oReconhecimento->setValorRealizado($dados->valor_pago_antecipado);
      }
      $icontratoCorrente = $dados->acordo;
      return $oReconhecimento;
    });

    return $aContratos;
  }

  /**
   * @param $sWhere
   * @return \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Reconhecimento[]
   * @throws \DBException
   */
  private static function getReconhecimentos ($sWhere) {

    $oDaoprogramacaoItem = new \cl_acordoprogramacaofinanceira();
    $campos      = 'ac16_sequencial as acordo, ac16_numero as numero, ac16_anousu as ano, k118_sequencial, k118_ano, k117_despesaantecipada, k118_mes, sum(k118_valor) as valor';
    $sGroup      = 'ac16_sequencial, ac16_numero, ac16_anousu, k118_sequencial, k118_ano, k117_despesaantecipada, k118_mes';
    $sWhere     .= " group by {$sGroup}";
    $sSqlAcordos = $oDaoprogramacaoItem->sql_query_parcelas(null, $campos, 'ac16_numero', $sWhere);
    $rsAcordos   = db_query($sSqlAcordos);
    if (!$rsAcordos) {
      throw new \DBException("Não foi possível pesquisar acordos para reconhecimento da competência");
    }
    $reconhecimemtos = \db_utils::makeCollectionFromRecord($rsAcordos, function($dados){

       $oReconhecimento = new ReconhecimentoModel();
       $oReconhecimento->setCodigo($dados->k118_sequencial);
       $oReconhecimento->setAcordo(\AcordoRepository::getByCodigo($dados->acordo));
       $oReconhecimento->setCompetencia(new DBCompetencia($dados->k118_ano, $dados->k118_mes));
       $oReconhecimento->setValor($dados->valor);
       $oReconhecimento->setDispesaAntecipada($dados->k117_despesaantecipada == 't' ? true : false);
       return $oReconhecimento;

     });

     return $reconhecimemtos;

   }
}
