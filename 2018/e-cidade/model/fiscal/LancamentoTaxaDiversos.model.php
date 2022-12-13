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
* Classe para lançamento de taxas de diversos no módulo fiscal
*/
class LancamentoTaxaDiversos
{

  /**
   * Código do lançamento
   */
  private $codigo;

  /**
   * CGM do lançamento
   */
  private $cgm;

  /**
   * Natureza de Taxa que será lançada
   */
  private $naturezaTaxaDiversos;

  /**
   * Unidade informada para cálculo
   */
  private $unidade;

  /**
   * Período para cálculo da taxa
   */
  private $periodo;

  /**
   * Data de início do período para o cálculo
   */
  private $dataInicio;

  /**
   * Data fim do período para o cálculo
   */
  private $dataFim;

  /**
   * Data do último cálculo geral para taxa
   */
  private $oDataUltimoCalculoGeral;

  /**
   * Data do vencimento
   */
  private $oDataVencimento;

  /**
   * Inscrição Municipal(issbase)
   * @var integer
   */
  private $iInscricaoMunicipal;

  /**
   * Construtor da classe
   *
   * @param Integer
   */
  function __construct($codigo = null) {
    if(!empty($codigo)) {
      $this->codigo = $codigo;
    }
  }

  /**
   * Define o códido do lançamento de taxas de diversos
   * @param Integer
   * @return LancamentoTaxaDiversos
   */
  public function setCodigo ($codigo) {
    $this->codigo = $codigo;
    return $this;
  }

  /**
   * Retorna o códido do lançamento de taxas de diversos
   * @return Integer
   */
  public function getCodigo () {
    return $this->codigo;
  }

  /**
   * Define o CGM para calcular a taxa e lançar débito diverso
   * @param CgmBase
   * @return CgmBase
   */
  public function setCGM (CgmBase $cgm) {
    $this->cgm = $cgm;
  }

  /**
   * Retorna o CGM para calcular a taxa e lançar débito diverso
   * @return CgmBase
   */
  public function getCGM () {
    return $this->cgm;
  }

  /**
   * Define a natureza da taxa de diversos que será lançado
   * @param NaturezaTaxaDiversos
   * @return LancamentoTaxaDiversos
   */
  public function setNaturezaTaxa (NaturezaTaxaDiversos $naturezaTaxaDiversos) {
    $this->naturezaTaxaDiversos = $naturezaTaxaDiversos;
    return $this;
  }

  /**
   * Retorna a natureza da taxa de diversos que será lançado
   * @return NaturezaTaxaDiversos
   */
  public function getNaturezaTaxa () {
    return $this->naturezaTaxaDiversos;
  }

  /**
   * Define o valor da unidade para cálculo da taxa de diversos a ser lançada
   * @param Float
   * @return LancamentoTaxaDiversos
   */
  public function setUnidade ($unidade) {
    $this->unidade = $unidade;
    return $this;
  }

  /**
   * Retorna o valor da unidade para cálculo da taxa de diversos a ser lançada
   * @return Float
   */
  public function getUnidade () {
    return $this->unidade;
  }

  /**
   * Define o período para cálculo da taxa
   * @param Float
   */
  public function setPeriodo ($periodo) {
    $this->periodo = $periodo;
  }

  /**
   * Retorna o período para cálculo da taxa
   * @return Float
   */
  public function getPeriodo () {
    return $this->periodo;
  }

  /**
   * Define a Data de início do período para cálculo da taxa
   * @param DBDate
   * @return LancamentoTaxaDiversos
   */
  public function setDataInicio ($dataInicio) {
    $this->dataInicio = $dataInicio;
    return $this;
  }

  /**
   * Retorna a Data de início do período para cálculo da taxa
   * @return DBDate
   */
  public function getDataInicio () {
    return $this->dataInicio;
  }

  /**
   * Define a Data de fim do período para cálculo da taxa
   * @param DBDate
   * @return LancamentoTaxaDiversos
   */
  public function setDataFim ($dataFim) {
    $this->dataFim = $dataFim;
    return $this;
  }

  /**
   * Retorna a Data de fim do período para cálculo da taxa
   * @return DBDate
   */
  public function getDataFim () {
    return $this->dataFim;
  }

  /**
   * Retorna a data do último cálculo geral para o lançamento
   *
   * @return DBDate
   */
  public function getDataUltimoCalculoGeral() {
    return $this->oDataUltimoCalculoGeral;
  }

  /**
   * Define a última data do cálculo geral da taxa
   * @param DBDate
   */
  public function setDataUltimoCalculoGeral($oDataUltimoCalculoGeral) {
    $this->oDataUltimoCalculoGeral = $oDataUltimoCalculoGeral;
  }

  /**
   * Define a data do vencimento
   * @param DBDate
   */
  public function setDataVencimento ($oDataVencimento) {
    $this->oDataVencimento = $oDataVencimento;
  }
  
  /**
   * Retorna a data do vencimento
   * @return DBDate
   */
  public function getDataVencimento () {
    return $this->oDataVencimento; 
  }

  /**
   * Retorna o código da Inscrição Municipal(issbase)
   * @return int
   */
  public function getInscricaoMunicipal() {
    return $this->iInscricaoMunicipal;
  }

  /**
   * Seta o código da Inscrição Municipal(issbase)
   * @param int $iInscricaoMunicipal
   */
  public function setInscricaoMunicipal($iInscricaoMunicipal) {
    $this->iInscricaoMunicipal = $iInscricaoMunicipal;
  }

  /**
   * Executa à fórmula vinculada à taxa a retorna o valor para ser lançado o débito
   *
   * @param string $sTipoCalculo
   * @return float
   * @throws BusinessException
   * @throws DBException
   */
  public function calcularTaxa($sTipoCalculo = ProcessamentoTaxaDiversos::CALCULO_INDIVIDUAL) {

    $formula = new DBFormulaLancamentoTaxaDiversos($this);
    $grupo   = $this->getNaturezaTaxa()->getGrupoTaxaDiversos();

    $formula->adicionar("TIPO_CALCULO_TAXA_DIVERSOS", $sTipoCalculo);
    $formula->adicionar("CODIGO_PROCEDENCIA_DIVERSOS_TAXA_DIVERSOS", $grupo->getCodigoProcedencia());
    $formula->adicionar("CODIGO_GRUPO_TAXA_DIVERSOS", $grupo->getCodigo());

    $sSqlFormula = $formula->parse("SELECT [{$this->getNaturezaTaxa()->getFormulaBase()}] as valor");
    $rsFormula   = db_query($sSqlFormula);

    if(!$rsFormula) {
      throw new DBException("Ocorreu um erro ao executar a fórmula:\n\n".$this->getNaturezaTaxa()->getFormulaBase()."\n\n".$sSqlFormula);
    }

    if(pg_num_rows($rsFormula) == 0) {
      throw new BusinessException("Verifique a fórmula vinculada a natureza da taxa.");
    }

    $valor = db_utils::fieldsMemory($rsFormula, 0)->valor;

    if ($valor <= 0) {
      throw new \BusinessException(
        "O valor calculado está inválido: ". number_format($valor, 2, ',', '.') . PHP_EOL .
        "Revise os dados informados no lançamento ou revise o cadastro da fórmula: {$this->getNaturezaTaxa()->getFormulaBase()}"
      );
    }

    return $valor;
  }

  /**
   * Retorna a descrição da unidade com base no código enviado
   * @param  int $iIndice
   * @return array|mixed
   */
  public static function getDescricaoUnidade($iIndice = null) {

    $aUnidades = array(
      '-------',
      'm',
      'm²',
      'm³',
      '100m',
      '30m²',
      '60m²',
      'Lote',
      'Imóvel',
      'Peça',
      'Milheiro',
      'Veículo',
      'Unidade'
    );

    if(!empty($iIndice) || $iIndice === 0 || $iIndice === '0') {
      return $aUnidades[$iIndice];
    }

    return $aUnidades;
  }

  /**
   * Retorna os débitos lançados(Data do cálculo e Valor)
   *
   * @return stdClass[]
   * @throws DBException
   */
  public function getDebitosLancados() {

    $oDaoDiversos   = new cl_lancamentotaxadiversos();
    $sWhereDiversos = "y120_sequencial = {$this->codigo}";
    $sSqlDiversos   = $oDaoDiversos->sql_query_join_diversos(null, 'dv05_valor, dv14_data_calculo', null, $sWhereDiversos);
    $rsDiversos     = db_query($sSqlDiversos);

    if(!$rsDiversos) {
      throw new DBException('Erro ao buscar os débitos do lançamento.');
    }

    if(pg_num_rows($rsDiversos) == 0) {
      return array();
    }

    return \db_utils::makeCollectionFromRecord($rsDiversos, function($oDebitos) {

      $oDadosDebito               = new stdClass();
      $oDadosDebito->sValor       = $oDebitos->dv05_valor;
      $oDadosDebito->sDataCalculo = $oDebitos->dv14_data_calculo;

      return $oDadosDebito;
    });
  }
}