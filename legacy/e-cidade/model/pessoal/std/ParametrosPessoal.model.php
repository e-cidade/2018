<?php
/**
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
 * Classe representa os parâmetros do módulo Pessoal
 *
 * @package Pessoal
 * @author $Author: dbrenan $
 * @version $Revision: 1.13 $
 */

class ParametrosPessoal {


  /**
   *
   *
   * @var mixed
   * @access private
   */
  private $oRubricaAbonoPermanencia;

  /**
   * Informa se o abono de permanência tributa no IR
   *
   * @var Boolean
   */
  private $lAbonoPermanenciaTributavel;

  /**
   * Competência dos parâmetros
   *
   * @var DBCompetencia
   */
  private $oCompetencia;

  /**
   * Instituição dos parâmetros
   *
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Parâmetro comparativo
   *
   * @var Boolean
   */
  private $lComparativo;

  /**
   * Nome da base de salário do comparativo
   *
   * @var String
   */
  private $sBaseSalarioComparativo;

  /**
   * Nome da base de férias do comparativo
   *
   * @var String
   */
  private $sBaseFeriasComparativo;

  /**
   * Rubrica definida para utilização na substituição de
   * função de exércicio Anterior
   *
   * @var  Rubrica
   */
  private $oRubricaExercicioAnteriorSubstituicao;

  /**
   * Rubrica definida para utilização na substituição de
   * função de exércicio Atual
   *
   * @var  Rubrica
   */
  private $oRubricaExercicioAtualSubstituicao;

  /**
   * Rubrica de escala de ferias a ser lançada com o valor de previdênvia para calcular o desconto
   *
   * @var Rubrica
   */
  private $oRubricaEscalaFerias;

  /**
   * Rubrica que será lançada com o 1/3 de férias
   *
   * @var Rubrica
   */
  private $oRubricaTercoFerias;

  /**
   * Base de RRA para Rendimentos Tributaveis
   *
   * @var Base
   */
  private $oBaseRraRendimentosTributaveis;

  /**
   * Base de RRA para Previdencia Social
   *
   * @var Base
   */
  private $oBaseRraPrevidenciaSocial;

  /**
   * Base de RRA para PensaoA limenticia
   *
   * @var Base
   */
  private $oBaseRraPensaoAlimenticia;

  /**
   * Base de RRA para Irrf
   *
   * @var Base
   */
  private $oBaseRraIrrf;

  /**
   * Base de RRA para Parcela Isenta
   *
   * @var Base
   */
  private $oBaseRraParcelaIsenta;

 /**
  * Código da tabela de valores das faixas de IRRF para cálculo do RRA
  *
  * @var Integer
  */
  private $iCodigoTabelaValoresIRRFRRA;

  /**
   * Rubrica utilizada para pensão alimenticia
   *
   * @var String
   */
  private $sRubricaPensaoAlimenticia;

  /**
   * Construtor da classe
   *
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   */
  function __construct(DBCompetencia $oCompetencia, Instituicao $oInstituicao) {

    $this->setCompetencia($oCompetencia);
    $this->setInstituicao($oInstituicao);
  }

  /**
   * Retorna a competência dos parâmetros
   *
   * @return DBCompetencia
   */
  public function getCompetencia() {
    return $this->oCompetencia;
  }

  /**
   * Retorna a instituição dos parâmetros
   *
   * @access public
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Verifica se o comparativo esta setado
   *
   * @access public
   * @return Boolean
   */
  public function isComparativo() {
    return $this->lComparativo;
  }

  /**
   * Retorna o nome da base de salário do comparativo
   *
   * @access public
   * @return String
   */
  public function getBaseSalarioComparativo() {
    return $this->sBaseSalarioComparativo;
  }

  /**
   * Retorna o nome da base de férias do comparativo
   *
   * @access public
   * @return String
   */
  public function getBaseFeriasComparativo() {
    return $this->sBaseFeriasComparativo;
  }

  /**
   * Seta a competência dos parâmetros
   *
   * @access private
   * @param DBCompetencia $oCompetencia
   */
  private function setCompetencia(DBCompetencia $oCompetencia) {
    $this->oCompetencia = $oCompetencia;
  }

  /**
   * Seta a instituição dos parâmetros
   *
   * @access private
   * @param Instituicao $oInstituicao
   */
  private function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Seta o parâmetro comparativo
   *
   * @access public
   * @param Boolean $lComparativo
   */
  public function setComparativo($lComparativo) {
    $this->lComparativo = $lComparativo;
  }

  /**
   * Seta o nome da base de salário do comparativo
   *
   * @access public
   * @param String $sBaseSalarioComparativo
   */
  public function setBaseSalarioComparativo($sBaseSalarioComparativo) {
    $this->sBaseSalarioComparativo = $sBaseSalarioComparativo;
  }

  /**
   * Seta o nome da base de férias do comparativo
   *
   * @access public
   * @param String $sBaseFeriasComparativo
   */
  public function setBaseFeriasComparativo($sBaseFeriasComparativo) {
    $this->sBaseFeriasComparativo = $sBaseFeriasComparativo;
  }

  /**
   * Define a Rubrica utilizada no lancçamento de Abono Permanencia
   *
   * @param  Rubrica $oRubrica
   * @access public
   * @return void
   */
  public function setRubricaAbonoPermanencia( Rubrica $oRubrica = null) {

    $this->oRubricaAbonoPermanencia = $oRubrica;
    return;
  }

  /**
   * Define se o Abono de permanência conta para tributação de IR
   *
   * @param  Boolean $lAbonoPermanenciaTributavel
   * @access public
   * @return void
   */
  public function setAbonoPermanenciaTributavel( $lAbonoPermanenciaTributavel) {

    $this->lAbonoPermanenciaTributavel = $lAbonoPermanenciaTributavel;
    return;
  }

  /**
   * Define a rubrica utilizada para substiruiçãod e função do exercício Anterior
   *
   * @access public
   * @param  String  $oRubricaExercicioAnterior
   */
  public function setRubricaExercicioAnteriorSubstituicao($sRubricaExercicioAnterior) {

    $oRubrica = RubricaRepository::getInstanciaByCodigo($sRubricaExercicioAnterior);
    $this->oRubricaExercicioAnteriorSubstituicao = $oRubrica;
  }

  /**
   * Define a rubrica utilizada para substiruiçãod e função do exercício Atual
   *
   * @access public
   * @param  String  $oRubricaExercicioAtual
   */
  public function setRubricaExercicioAtualSubstituicao($sRubricaExercicioAtual) {

    $oRubrica = RubricaRepository::getInstanciaByCodigo($sRubricaExercicioAtual);
    $this->oRubricaExercicioAtualSubstituicao = $oRubrica;
  }

  /**
   * Define a rubrica de escala de ferias a ser lançada com o valor de previdênvia para calcular o desconto
   *
   * @param Rubrica|null $oRubrica
   */
  public function setRubricaEscalaFerias( Rubrica $oRubrica = null) {
    $this->oRubricaEscalaFerias = $oRubrica;
  }

  /**
   * Define a rubrica configurada para lançar o 1/3 de férias
   *
   * @param Rubrica|null $oRubricaTercoFerias
   */
  public function setRubricaTercoFerias( Rubrica $oRubricaTercoFerias = null ) {
    $this->oRubricaTercoFerias = $oRubricaTercoFerias;
  }

  /**
   * Define a Base de RRA para Rendimentos Tributaveis
   *
   * @param Base $oBaseRraRendimentos Tributaveis
   */
  public function setBaseRraRendimentosTributaveis(Base $oBaseRraRendimentosTributaveis) {
    $this->oBaseRraRendimentosTributaveis = $oBaseRraRendimentosTributaveis;
  }

  /**
   * Define a Base de RRA para PrevidenciaSocial
   *
   * @param Base $oBaseRraRendimentos Tributaveis
   */
  public function setBaseRraPrevidenciaSocial(Base $oBaseRraPrevidenciaSocial) {
    $this->oBaseRraPrevidenciaSocial = $oBaseRraPrevidenciaSocial;
  }

  /**
   * Define a Base de RRA para Pensao Alimenticia
   *
   * @param Base $oBaseRraRendimentosTributaveis
   */
  public function setBaseRraPensaoAlimenticia(Base $oBaseRraPensaoAlimenticia) {
    $this->oBaseRraPensaoAlimenticia = $oBaseRraPensaoAlimenticia;
  }

  /**
   * Define a Base de RRA para Irrf
   *
   * @param Base $oBaseRraRendimentos Tributaveis
   */
  public function setBaseRraIrrf(Base $oBaseRraIrrf) {
    $this->oBaseRraIrrf = $oBaseRraIrrf;
  }

  /**
   * Define a Rubrica para pensão alimenticia
   * 
   * @param String $sRubricaPensaoAlimentica
   */
  public function setRubricaPensaoAlimenticia($sRubricaPensaoAlimenticia) {
    $this->sRubricaPensaoAlimenticia = $sRubricaPensaoAlimenticia;
  }

  /**
   * Define a Base de RRA para parcela isenta
   *
   * @param Base $oBaseRraParcelaIsenta
   */
  public function setBaseRraParcelaIsenta($oBaseRraParcelaIsenta) {
    $this->oBaseRraParcelaIsenta = $oBaseRraParcelaIsenta;
  }

  /**
   * Define o código da tabela de faixas do IRRF para cálculo do RRA
   * @param Integer
   */
  public function setTabelaIRRFRRA ($iCodigoTabelaValoresIRRFRRA) {
    $this->iCodigoTabelaValoresIRRFRRA = $iCodigoTabelaValoresIRRFRRA;
  }

  /**
   * Rubrica AbonoPermanencia
   *
   * @access public
   * @return void
   */
  public function getRubricaAbonoPermanencia() {
    return $this->oRubricaAbonoPermanencia;
  }

  /**
   * Retorna se o Abono de permanência conta para tributação de IR
   *
   * @access public
   * @return Boolean $lAbonoPermanenciaTributavel
   */
  public function getAbonoPermanenciaTributavel() {

    return $this->lAbonoPermanenciaTributavel;
  }

  /**
   * Retorna a rubrica utilizada para substiruiçãod e função do exercício Anterior
   *
   * @access public
   * @return  Rubrica
   */
  public function getRubricaExercicioAnteriorSubstituicao() {
    return $this->oRubricaExercicioAnteriorSubstituicao;
  }

  /**
   * Retorna a rubrica utilizada para substiruiçãod e função do exercício Atual
   *
   * @access public
   * @return  Rubrica
   */
  public function getRubricaExercicioAtualSubstituicao() {
    return $this->oRubricaExercicioAtualSubstituicao;
  }

  /**
   * Retorna a rubrica de escala de ferias a ser lançada com o valor de previdênvia para calcular o desconto
   *
   * @access public
   * @return Rubrica
   */
  public function getRubricaEscalaFerias() {
    return $this->oRubricaEscalaFerias;
  }

  /**
   * Retorna a rubrica configurada para lançar o 1/3 de férias
   *
   * @access public
   * @return Rubrica
   */
  public function getRubricaTercoFerias() {
    return $this->oRubricaTercoFerias;
  }

  /**
   * Retorna a Base de RRA para Rendimentos Tributaveis
   *
   * @access public
   * @return Base
   */
  public function getBaseRraRendimentosTributaveis() {
    return $this->oBaseRraRendimentosTributaveis;
  }

  /**
   * Retorna a Base de RRA para Previdencia Social
   *
   * @access public
   * @return Base
   */
  public function getBaseRraPrevidenciaSocial() {
    return $this->oBaseRraPrevidenciaSocial;
  }

  /**
   * Retorna a Base de RRA para Pensao Alimenticia
   *
   * @access public
   * @return Base
   */
  public function getBaseRraPensaoAlimenticia() {
    return $this->oBaseRraPensaoAlimenticia;
  }

  /**
   * Retorna a Base de RRA para Irrf
   *
   * @access public
   * @return Base
   */
  public function getBaseRraIrrf() {
    return $this->oBaseRraIrrf;
  }

  /**
   * Retorna a Base de RRA para parcela isenta
   *
   * @access public
   * @return Base
   */
  public function getBaseRraParcelaIsenta() {
    return $this->oBaseRraParcelaIsenta;
  }

  /**
   * Retorna o código da tabela de faixas do IRRF para cálculo do RRA
   * @return Integer
   */
  public function getCodigoTabelaIRRFRRA() {
    return $this->iCodigoTabelaValoresIRRFRRA;
  }

  /**
   * Retorna a Rubrica para pensão alimenticia
   * 
   * @return String $sRubricaPensaoAlimentica
   */
  public function getRubricaPensaoAlimenticia() {
    return $this->sRubricaPensaoAlimenticia;
  }

}
