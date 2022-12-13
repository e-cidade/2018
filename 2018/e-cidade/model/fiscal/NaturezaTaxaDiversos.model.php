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
* Classe com o natureza de uma taxa de diversos lançada pelo fiscal
*/
class NaturezaTaxaDiversos
{
  
  /**
   * Código da Natureza da taxa
   */
  private $codigo;

  /**
   * Grupo ao qual a Natureza da taxa está vinculada
   * @var GrupoTaxaDiversos
   */
  private $grupoTaxaDiversos;

  /**
   * Natureza da taxa
   */
  private $natureza;
  
  /**
   * Fórmula a qual a Natureza da taxa está vinculada que irá retornar a Unidade base o período e valor de referência
   */
  private $formula;

  /**
   *
   */
  private $unidade;
  
  /**
   * Tipo de período da Natureza da taxa, ano, mês ou dia
   */
  private $tipoPeriodo;

  /**
   * Tipo de cálculo da Natureza da taxa se único ou geral, somente naturezas de tipo geral serão calculados de forma geral
   */
  private $tipoCalculo;

  /**
   * Fórmula base vinculada à natureza de taxa de diversos
   */
  private $formulaBase;

  /**
   * Construtor da classe
   * @param Integer
   */
  function __construct($codigo = null) {
    if(!empty($codigo)) {
      $this->codigo = $codigo;
    }
  }

  /**
   * Define o código
   * @param integer
   */
  public function setCodigo ($codigo) {
    $this->codigo = $codigo;
    return $this;
  }
  
  /**
   * Retorna o código
   * @return integer
   */
  public function getCodigo () {
    return $this->codigo; 
  }

  /**
   * Define o grupo de taxas à qual essa natureza está vinculada
   * @param \GrupoTaxaDiversos
   */
  public function setGrupoTaxaDiversos (GrupoTaxaDiversos $grupoTaxaDiversos) {
    $this->grupoTaxaDiversos = $grupoTaxaDiversos;
  }
  
  /**
   * Retorna o grupo de taxas à qual essa natureza está vinculada
   * @return GrupoTaxaDiversos
   */
  public function getGrupoTaxaDiversos () {
    return $this->grupoTaxaDiversos; 
  }
  
  /**
   * Define a natureza
   * @param String
   */
  public function setNatureza ($natureza) {
    $this->natureza = $natureza;
  }
  
  /**
   * Retorna a natureza
   * @return String
   */
  public function getNatureza () {
    return $this->natureza; 
  }
  
  /**
   * Define a fórmula
   * @param Integer
   */
  public function setFormula ($formula) {
    $this->formula = $formula;
    return $this;
  }
  
  /**
   * Retorna a fórmula
   * @return Integer
   */
  public function getFormula () {
    return $this->formula; 
  }

  /**
   * Define a unidade
   * @param String
   */
  public function setUnidade ($unidade) {
    $this->unidade = $unidade;
  }
  
  /**
   * Retorn a unidade
   * @return String
   */
  public function getUnidade () {
    return $this->unidade; 
  }
  
  /**
   * Define o tipo de período
   * @param String
   */
  public function setTipoPeriodo ($tipoPeriodo) {
    $this->tipoPeriodo = $tipoPeriodo;
    return $this;
  }
  
  /**
   * Retorna o tipo de período
   * @return String
   */
  public function getTipoPeriodo () {
    return $this->tipoPeriodo; 
  }

  /**
   * Define o tipo de cálculo da taxa
   * @param String
   */
  public function setTipoCalculo ($tipoCalculo) {
    $this->tipoCalculo = $tipoCalculo;
  }
  
  /**
   * Retorna o tipo de cálculo da taxa
   * @return String
   */
  public function getTipoCalculo () {
    return $this->tipoCalculo; 
  }

  /**
   * Retorna a fórmula vinculada à natureza de taxas de diversos
   * @return String
   */
  public function getFormulaBase()
  {

    if(empty($this->formulaBase)) {

      $oDaoDbformulas = new cl_db_formulas;
      $sSql           = $oDaoDbformulas->sql_query($this->formula, "db148_nome as nome");
      $rsSql          = db_query($sSql);

      if(!$rsSql) {
        throw new DBException("Ocorreu um erro ao consultar a base de dados.");
      }
      
      if(pg_num_rows($rsSql) == 0) {
        throw new BusinessException("Verifique a fórmula vinculada à Natureza da taxa.\nNatureza: (código: {$this->codigo}, grupo: {$this->grupoTaxaDiversos})");
      }

      $this->formulaBase = db_utils::fieldsMemory($rsSql, 0)->nome;
    }

    return $this->formulaBase;
  }

  /**
   * Retorna uma coleção dos lançamentos feitos para uma natureza/grupo
   *
   * @return LancamentoTaxaDiversos[]
   * @throws DBException
   */
  public function getLancamentos() {

    $oDaoLancamentos    = new cl_lancamentotaxadiversos();
    $sWhereLancamentos  = "     y119_grupotaxadiversos = {$this->grupoTaxaDiversos->getCodigo()}";
    $sWhereLancamentos .= " AND y119_sequencial = {$this->codigo}";
    $sSqlLancamentos    = $oDaoLancamentos->sql_query(null, 'y120_sequencial', null, $sWhereLancamentos);
    $rsLancamentos      = \db_query($sSqlLancamentos);

    if(!$rsLancamentos) {
      throw new \DBException('Erro ao buscar os lançamentos da natureza.');
    }

    return \db_utils::makeCollectionFromRecord($rsLancamentos, function($oLancamento) {
      return LancamentoTaxaDiversosRepository::getInstanciaPorCodigo($oLancamento->y120_sequencial);
    });
  }
}