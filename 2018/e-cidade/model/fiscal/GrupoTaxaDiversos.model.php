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
* Classe com o grupo � que pertence uma taxa de diversos
*/
class GrupoTaxaDiversos {
  /**
   * C�digo do grupo
   */
  private $codigo;

  /**
   * Descri��o do grupo
   */
  private $descricao;

  /**
   * C�digo Inflator ao qual o grupo est� vinculado
   */
  private $codigoInflator;

  /**
   * C�digo da Proced�ncia ao qual o grupo est� vinculado
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
   * Define a descri��o do grupo
   * @param String
   */
  public function setDescricao ($descricao) {
    $this->descricao = $descricao;
  }
  
  /**
   * Retorna a descri��o do grupo
   * @return String
   */
  public function getDescricao () {
    return $this->descricao; 
  }
  
  /**
   * Define o c�digo do inflator
   * @param Integer
   */
  public function setCodigoInflator ($codigoInflator) {
    $this->codigoInflator = $codigoInflator;
  }
  
  /**
   * Retorna o c�digo do inflator
   * @return Integer
   */
  public function getCodigoInflator () {
    return $this->codigoInflator; 
  }
  
  /**
   * Define o c�digo da proced�ncia
   * @param Integer
   */
  public function setCodigoProcedencia ($codigoProcedencia) {
    $this->codigoProcedencia = $codigoProcedencia;
  }
  
  /**
   * Retorna o c�digo da proced�ncia
   * @return Integer
   */
  public function getCodigoProcedencia () {
    return $this->codigoProcedencia; 
  }

  /**
   * Retorna uma cole��o de NaturezaTaxaDiversos vinculadas ao grupo
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