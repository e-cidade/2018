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


/**
 * Classe que representa um registro do ponto
 *
 * @package folha
 * @author  Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br>
 */
class RegistroPonto {

  /**
   * Rubrica do registro
   *
   * @var Rubrica
   * @access private
   */
  private $oRubrica;

  /**
   * Servidor do registro
   *
   * @var Servidor
   * @access private
   */
  private $oServidor;

  /**
   * Quantidade do registro
   *
   * @var mixed
   * @access private
   */
  private $nQuantidade;

  /**
   * Valor do registro na Competencia
   * @var    numeric
   * @access private
   */
  private $nValor;

  /**
   * @var string
   */
  private $sDataLimite;

  /**
   * @param string $sDataLimite
   */
  public function setDataLimite( $sDataLimite) {
    $this->sDataLimite = $sDataLimite;
  }

  /**
   * Define a Rubrica do registro
   *
   * @param Rubrica $oRubrica
   * @access public
   * @return void
   */
  public function setRubrica( Rubrica $oRubrica ) {
    $this->oRubrica = $oRubrica;
  }

  /**
   * Define o servidor
   *
   * @param Servidor $oServidor
   * @access public
   * @return void
   */
  public function setServidor(Servidor $oServidor) {
    $this->oServidor = $oServidor;
  }

  /**
   * Define a Quantide do registro
   *
   * @param  float $nQuantidade
   * @access public
   * @return void
   */
  public function setQuantidade( $nQuantidade ) {
    $this->nQuantidade = $nQuantidade;
  }

  /**
   * Define o Valor ocorrido no registro
   *
   * @param  float $nValor
   * @access public
   * @return void
   */
  public function setValor( $nValor ) {
    $this->nValor = $nValor;
  }

  /**
   * Retorna a Rubrica do registro
   *
   * @access public
   * @return Rubrica
   */
  public function getRubrica() {
    return $this->oRubrica;
  }

  /**
   * Retorna o Servidor do registro
   *
   * @access public
   * @return Servidor
   */
  public function getServidor() {
    return $this->oServidor;
  }

  /**
   * Retorna a quantidade do registro
   *
   * @access public
   * @return numeric
   */
  public function getQuantidade() {
    return $this->nQuantidade;
  }

  /**
   * Retorna o valor do registro
   *
   * @access public
   * @return numeric
   */
  public function getValor() {
    return $this->nValor;
  }
  /**
   * Retorna a data limite
   * @return string
   */
  public function getDataLimite() {
    return $this->sDataLimite;
  }

}