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
namespace ECidade\Financeiro\Contabilidade\Calculo;

/**
 * Calcula a Receita Corrente Líquida
 * Class ReceitaCorrenteLiquida
 * @package ECidade\Financeiro\Contabilidade\Calculo
 */
class ReceitaCorrenteLiquida {

  /**
   * @var array
   */
  private $aMatrizAnoAnterior = array();

  /**
   * @var array
   */
  private $aMatrizAno         = array();

  /**
   * @var integer
   */
  private $iAno;

  /**
   * @var string
   */
  private $sCodigoInstituicoes;

  /**
   * @var int
   */
  private $iCodigoRelatorio;

  /**
   * 12 - 1º SEMESTRE
   * 13 - 2º SEMESTRE
   * 14 - 1º QUADRIMESTRE
   * 15 - 2º QUADRIMESTRE
   * 16 - 3º QUADRIMESTRE
   */
  private $aRCL = array(
    12 => array(
      "anterior" => array("julho","agosto","setembro","outubro","novembro", "dezembro"),
      "atual"    => array("janeiro","fevereiro","marco","abril", "maio","junho")
    ),
    13 => array(
      "anterior" => array(),
      "atual"    => array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto", "setembro","novembro","outubro", "dezembro")
    ),
    14 => array(
      "anterior" => array("maio","junho","julho","agosto","setembro","outubro","novembro", "dezembro"),
      "atual"    => array("janeiro","fevereiro","marco","abril" )
    ),
    15 => array(
      "anterior" => array("setembro","novembro","outubro", "dezembro"),
      "atual"    => array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto")
    ),
    16 => array(
      "anterior" => array(),
      "atual"    => array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto", "setembro","novembro","outubro", "dezembro")
    )
  );

  /**
   *
   * @param integer       $iAno
   * @param \Instituicao[] $aInstituicoes
   * @param integer       $iCodigoRelatorio código referente ao relatório do RREO para o ano informado
   */
  public function __construct($iAno, array $aInstituicoes = null, $iCodigoRelatorio = 81 ) {

    if ($aInstituicoes === null) {
      $aInstituicoes = \InstituicaoRepository::getInstituicoes();
    }

    $this->iAno = $iAno;

    $aCodigos = array_map(function($oInstiuicao) {
      return $oInstiuicao->getCodigo();
    }, $aInstituicoes);

    $this->sCodigoInstituicoes = implode(', ', $aCodigos);

    $this->iCodigoRelatorio    = $iCodigoRelatorio;
    duplicaReceitaaCorrenteLiquida($this->iAno, $iCodigoRelatorio);
  }

  /**
   * Calcula a Receita Corrente Líquida do ano anterior ao ano informado
   * @return array matriz do ano anterior indexada por mês
   */
  public function calcularRCLAnterior() {

    if ( empty($this->aMatrizAnoAnterior) ) {

      $iAno        = $this->iAno - 1;
      $sDataInicio = "{$iAno}-01-01";
      $sDataFim    = "{$iAno}-12-31";

      $this->aMatrizAnoAnterior = calcula_rcl2($iAno, $sDataInicio, $sDataFim, $this->sCodigoInstituicoes,
                                               true, $this->iCodigoRelatorio);
    }

    return $this->aMatrizAnoAnterior;
  }

  /**
   * Calcula a Receita Corrente Líquida do ano informado
   * @return array matriz do ano informado indexada por mês
   */
  public function calcularRCL() {

    if ( empty($this->aMatrizAno) ) {

      $sDataInicio = "{$this->iAno}-01-01";
      $sDataFim    = "{$this->iAno}-12-31";

      $this->aMatrizAno = calcula_rcl2($this->iAno, $sDataInicio, $sDataFim, $this->sCodigoInstituicoes,
                                       true, $this->iCodigoRelatorio);
    }
    return $this->aMatrizAno;
  }

  /**
   * Calcula o valor da RCL de acordo com o período informado.
   * Retornando uma matriz com o cálculo mensal do período indexada por ano e mês.
   * Exemplo:
   * [ 2016 => [janeiro => <valor>, fevereiro => <valor>, [....] ],
   *  [...]
   * ]
   * @param  \DBDate $oDataInicio
   * @param  \DBDate $oDataFim
   * @return array
   */
  public function calcularRCLPorPeriodo(\DBDate $oDataInicio, \DBDate $oDataFim) {

    if ( $oDataInicio->getTimeStamp() > $oDataFim->getTimeStamp() ) {
      throw new \Exception("Data inicial maior que a data final.");
    }

    $iAnoDataInicio = $oDataInicio->getAno();
    $iAnoDataFinal  = $oDataFim->getAno();

    $aMatrizAno = array();
    if ( $iAnoDataInicio == $iAnoDataFinal ) {

      $aMatrizAno[$iAnoDataFinal] = $this->calcularRCL();
      return $aMatrizAno;
    }

    $sDataFinalDoPeriodoInicial  = "{$iAnoDataInicio}-12-31";
    $iDiaMesPeriodoFinal         = cal_days_in_month(CAL_GREGORIAN, $oDataFim->getMes(), $iAnoDataFinal);
    $sDataInicialDoPeriodoFinal  = "{$iAnoDataFinal}-01-01";
    $sDataFinalDoPeriodoFinal    = "{$iAnoDataFinal}-{$oDataFim->getMes()}-{$iDiaMesPeriodoFinal}";

    $aMatrizAno[$iAnoDataInicio] = calcula_rcl2($iAnoDataInicio, $oDataInicio->getDate(), $sDataFinalDoPeriodoInicial,
                                                $this->sCodigoInstituicoes, true, $this->iCodigoRelatorio);

    $aMatrizAno[$iAnoDataFinal]  = calcula_rcl2($iAnoDataFinal, $sDataInicialDoPeriodoFinal, $sDataFinalDoPeriodoFinal,
                                                $this->sCodigoInstituicoes, true, $this->iCodigoRelatorio);


    return $aMatrizAno;
  }

  /**
   * Calcula a Receita Corrente Líquida dos ultimos 12 meses a partir do período informardo
   *
   * @param  integer $iPeriodo código do período
   * @return float
   */
  public function somaRCLPeriodo($iPeriodo) {

    $aRCLAnterior = $this->calcularRCLAnterior();
    $aRCL         = $this->calcularRCL();

    $nValorPeriodo = 0;
    foreach ($this->aRCL[$iPeriodo]["anterior"] as $mes) {

      if (isset($aRCLAnterior[$mes])) {
        $nValorPeriodo += $aRCLAnterior[$mes];
      }
    }

    foreach ($this->aRCL[$iPeriodo]["atual"] as $mes) {

      if (isset($aRCL[$mes])) {
        $nValorPeriodo += $aRCL[$mes];
      }
    }

    return $nValorPeriodo;
  }

}
