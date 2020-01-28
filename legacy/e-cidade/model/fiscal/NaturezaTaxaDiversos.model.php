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
* Classe com o natureza de uma taxa de diversos lan�ada pelo fiscal
*/
class NaturezaTaxaDiversos
{
  
  /**
   * C�digo da Natureza da taxa
   */
  private $codigo;

  /**
   * Grupo ao qual a Natureza da taxa est� vinculada
   * @var GrupoTaxaDiversos
   */
  private $grupoTaxaDiversos;

  /**
   * Natureza da taxa
   */
  private $natureza;
  
  /**
   * F�rmula a qual a Natureza da taxa est� vinculada que ir� retornar a Unidade base o per�odo e valor de refer�ncia
   */
  private $formula;

  /**
   *
   */
  private $unidade;
  
  /**
   * Tipo de per�odo da Natureza da taxa, ano, m�s ou dia
   */
  private $tipoPeriodo;

  /**
   * Tipo de c�lculo da Natureza da taxa se �nico ou geral, somente naturezas de tipo geral ser�o calculados de forma geral
   */
  private $tipoCalculo;

  /**
   * F�rmula base vinculada � natureza de taxa de diversos
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
   * Define o c�digo
   * @param integer
   */
  public function setCodigo ($codigo) {
    $this->codigo = $codigo;
    return $this;
  }
  
  /**
   * Retorna o c�digo
   * @return integer
   */
  public function getCodigo () {
    return $this->codigo; 
  }

  /**
   * Define o grupo de taxas � qual essa natureza est� vinculada
   * @param \GrupoTaxaDiversos
   */
  public function setGrupoTaxaDiversos (GrupoTaxaDiversos $grupoTaxaDiversos) {
    $this->grupoTaxaDiversos = $grupoTaxaDiversos;
  }
  
  /**
   * Retorna o grupo de taxas � qual essa natureza est� vinculada
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
   * Define a f�rmula
   * @param Integer
   */
  public function setFormula ($formula) {
    $this->formula = $formula;
    return $this;
  }
  
  /**
   * Retorna a f�rmula
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
   * Define o tipo de per�odo
   * @param String
   */
  public function setTipoPeriodo ($tipoPeriodo) {
    $this->tipoPeriodo = $tipoPeriodo;
    return $this;
  }
  
  /**
   * Retorna o tipo de per�odo
   * @return String
   */
  public function getTipoPeriodo () {
    return $this->tipoPeriodo; 
  }

  /**
   * Define o tipo de c�lculo da taxa
   * @param String
   */
  public function setTipoCalculo ($tipoCalculo) {
    $this->tipoCalculo = $tipoCalculo;
  }
  
  /**
   * Retorna o tipo de c�lculo da taxa
   * @return String
   */
  public function getTipoCalculo () {
    return $this->tipoCalculo; 
  }

  /**
   * Retorna a f�rmula vinculada � natureza de taxas de diversos
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
        throw new BusinessException("Verifique a f�rmula vinculada � Natureza da taxa.\nNatureza: (c�digo: {$this->codigo}, grupo: {$this->grupoTaxaDiversos})");
      }

      $this->formulaBase = db_utils::fieldsMemory($rsSql, 0)->nome;
    }

    return $this->formulaBase;
  }

  /**
   * Retorna uma cole��o dos lan�amentos feitos para uma natureza/grupo
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
      throw new \DBException('Erro ao buscar os lan�amentos da natureza.');
    }

    return \db_utils::makeCollectionFromRecord($rsLancamentos, function($oLancamento) {
      return LancamentoTaxaDiversosRepository::getInstanciaPorCodigo($oLancamento->y120_sequencial);
    });
  }
}