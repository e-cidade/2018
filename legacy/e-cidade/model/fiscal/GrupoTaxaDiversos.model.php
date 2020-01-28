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
* Classe com o grupo à que pertence uma taxa de diversos
*/
class GrupoTaxaDiversos {
  /**
   * Código do grupo
   */
  private $codigo;

  /**
   * Descrição do grupo
   */
  private $descricao;

  /**
   * Código Inflator ao qual o grupo está vinculado
   */
  private $codigoInflator;

  /**
   * Código da Procedência ao qual o grupo está vinculado
   */
  private $codigoProcedencia;

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
   * Define a descrição do grupo
   * @param String
   */
  public function setDescricao ($descricao) {
    $this->descricao = $descricao;
  }
  
  /**
   * Retorna a descrição do grupo
   * @return String
   */
  public function getDescricao () {
    return $this->descricao; 
  }
  
  /**
   * Define o código do inflator
   * @param Integer
   */
  public function setCodigoInflator ($codigoInflator) {
    $this->codigoInflator = $codigoInflator;
  }
  
  /**
   * Retorna o código do inflator
   * @return Integer
   */
  public function getCodigoInflator () {
    return $this->codigoInflator; 
  }
  
  /**
   * Define o código da procedência
   * @param Integer
   */
  public function setCodigoProcedencia ($codigoProcedencia) {
    $this->codigoProcedencia = $codigoProcedencia;
  }
  
  /**
   * Retorna o código da procedência
   * @return Integer
   */
  public function getCodigoProcedencia () {
    return $this->codigoProcedencia; 
  }

  /**
   * Retorna uma coleção de NaturezaTaxaDiversos vinculadas ao grupo
   *
   * @return NaturezaTaxaDiversos[]
   * @throws DBException
   */
  public function getNaturezas() {

    $oDaoTaxaDiversos   = new cl_taxadiversos();
    $sWhereTaxaDiversos = "y119_grupotaxadiversos = {$this->codigo}";
    $sSqlTaxaDiversos   = $oDaoTaxaDiversos->sql_query_file(null, 'y119_sequencial', null, $sWhereTaxaDiversos);
    $rsTaxaDiversos     = \db_query($sSqlTaxaDiversos);

    if(!$rsTaxaDiversos) {
      throw new DBException('Erro ao buscar as naturezas vinculadas ao grupo.');
    }

    return \db_utils::makeCollectionFromRecord($rsTaxaDiversos, function($oTaxaDiversos) {
      return NaturezaTaxaDiversosRepository::getInstanciaPorCodigo($oTaxaDiversos->y119_sequencial);
    });
  }
}