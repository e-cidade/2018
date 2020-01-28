<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("std/DBDate.php");
require_once("model/pessoal/calculoatuarial/cnm/InformacaoCalculoAtuarial.model.php");

/**
 * Classe reponsavel por tratar as Informações dos Servidores Inativos 
 * @author Renan Melo <renan@dbseller.com.br>
 */
class InformacaoCalculoAtuarialInativos extends InformacaoCalculoAtuarial {

  /**
   * Tipo de Inativo(Tempo, Invalidez, Idade, Compusória, Pensionista)
   * @var integer
   */
  private $iTipoInativo;
  
  /**
   * Número da Matricula
   * @var integer
   */
  private $iMatricula;
  
  /**
   * Número do CPF
   * @var integer
   */
  private $iCpf;
  
  /**
   * Data de Nascimento
   * @var DBDate
   */
  private $oDataNascimento;
  
  /**
   * Sexo
   * @var string
   */
  private $sSexo;
  
  /**
   * Data de inicio do beneficio
   * @var DBDate;
   */
  private $oDataInicioBeneficio;
  
  /**
   * Valor da Remuneração / Beneficio
   * @var float
   */
  private $fRemuneracao;
  
  /**
   * Número de Dependentes
   * @var integer
   */
  private $iNumeroDependentes;
  
  /**
   * Data de nascimeto do Conjuge
   * @var DBDate
   */
  private $oDataNascimentoConjuge;
  
  /**
   * Udade do filho 1
   * @var integer
   */
  private $iIdadeFilho1;
  
  /**
   * Udade do filho 2
   * @var integer
   */
  private $iIdadeFilho2;
  
  /**
   * Udade do filho 3
   * @var integer
   */
  private $iIdadeFilho3;
  
  /**
   * Udade do filho 4
   * @var integer
   */
  private $iIdadeFilho4;
  
  /**
   * Udade do filho 5
   * @var integer
   */
  private $iIdadeFilho5;
  
  /**
   * Função Contrutora
   */
  public function __construct() {}
  
  /**
   * Retorna o tipo de inativo.
   * @return integer iTipoInativo
   */
  public function getTipoInativo() {
    return $this->iTipoInativo;
  }
  
  /**
   * Seta o tipo de Inativo
   * @param integer $iTipoInativo
   */
  public function setTipoInativo ($iTipoInativo) {
    $this->iTipoInativo = $iTipoInativo;
  }

  /**
   * Retorna o número da matricula
   * @return integer #iMatricula
   */
  public function getMatricula () {
    return $this->iMatricula;
  }
  
  /**
   * Seta o número da Matricula
   * @param integer $iMatricula
   */
  public function setMatricula ($iMatricula) {
    $this->iMatricula = $iMatricula;
  }
  
  /**
   * Retorna o número do CpF
   * @return integer $iCpf
   */
  public function getCpf() {
      return $this->iCpf;
  }

  /**
   * Seta o número do Cpf
   * @param integer $iCpf
   */
  public function setCpf ($iCpf) {
      $this->iCpf = $iCpf;
  }

  /**
   * Retorna a Data de nascimento
   * @return DBDate $oDataNascimento
   */
  public function getDataNascimento() {
      return $this->oDataNascimento;
  }

  /**
   * Seta a Data de nascimento
   * @param date $dDataNascimento
   */
  public function setDataNascimento ($oDataNascimento) {
      $this->oDataNascimento = $oDataNascimento;
  }

  /**
   * Retorna o sexo
   * @return string $sSexo
   */
  public function getSexo() {
      return $this->sSexo;
  }

  /**
   * Seta o Sexo
   * @param string $sSexo
   */
  public function setSexo ($sSexo) {
      $this->sSexo = $sSexo;
  }

  /**
   * Retorna a data de inicio do beneficio.
   * @return DBDate $oDataInicioBeneficio
   */
  public function getDataInicioBeneficio() {
      return $this->oDataInicioBeneficio;
  }

  /**
   * Seta a Data de inicio do Beneficio
   * @param date $dDataInicioBeneficio
   */
  public function setDataInicioBeneficio ($oDataInicioBeneficio) {
      $this->oDataInicioBeneficio = $oDataInicioBeneficio;
  }

  /**
   * retorna valor da remuneração
   * @return float $fRemuneracao
   */
  public function getRemuneracao() {
      return $this->fRemuneracao;
  }

  /**
   * Seta o valor da remuneração
   * @param float $fRemuneracao
   */
  public function setRemuneracao ($fRemuneracao) {
      $this->fRemuneracao = $fRemuneracao;
  }

  /**
   * Returna o número de dependentes
   * @return integer $iNumeroDependentes
   */
  public function getNumeroDependentes() {
      return $this->iNumeroDependentes;
  }

  /**
   * Seta o número de dependentes
   * @param integer $iNumeroDependentes
   */
  public function setNumeroDependentes ($iNumeroDependentes) {
      $this->iNumeroDependentes = $iNumeroDependentes;
  }

  /**
   * Returna a Data de Nascimento do Conjuge
   * @return DBDate $oDataNascimentoConjuge
   */
  public function getDataNascimentoConjuge() {

    if (!empty($this->oDataNascimentoConjuge)) {
      return $this->oDataNascimentoConjuge->getDate(DBDate::DATA_PTBR);
    }
  }

  /**
   * Seta a Data de Nascimento do Conjuge
   * @param date $dDataNascimentoConjuge
   */
  public function setDataNascimentoConjuge ($oDataNascimentoConjuge) {
    $this->oDataNascimentoConjuge = $oDataNascimentoConjuge;
  }

  /**
   * Retorna a idade do filho 1
   * @return integer
   */
  public function getIdadeFilho1() {
      return $this->iIdadeFilho1;
  }

  /**
   * Seta a idade do filho 1
   * @param integer $iIdadeFilho1
   */
  public function setIdadeFilho1 ($iIdadeFilho1) {
      $this->iIdadeFilho1 = $iIdadeFilho1;
  }

  /**
   * Retorna a idade do filho 2
   * @return integer
   */
  public function getIdadeFilho2() {
      return $this->iIdadeFilho2;
  }

  /**
   * Seta a idade do filho 2
   * @param integer $iIdadeFilho2
   */
  public function setIdadeFilho2 ($iIdadeFilho2) {
      $this->iIdadeFilho2 = $iIdadeFilho2;
  }

  /**
   * Retorna a idade do filho 3
   * @return integer
   */
  public function getIdadeFilho3() {
      return $this->iIdadeFilho3;
  }

  /**
   * Seta a idade do filho 3
   * @param integer $iIdadeFilho3'
   */
  public function setIdadeFilho3 ($iIdadeFilho3) {
      $this->iIdadeFilho3 = $iIdadeFilho3;
  }

  /**
   * Retorna a idade do filho 4
   * @return integer
   */
  public function getIdadeFilho4() {
      return $this->iIdadeFilho4;
  }

  /**
   * Seta a idade do filho 4
   * @param integer $iIdadeFilho4
   */
  public function setIdadeFilho4 ($iIdadeFilho4) {
      $this->iIdadeFilho4 = $iIdadeFilho4;
  }

  /**
   * Retorna a idade do filho 5
   * @return integer
   */
  public function getIdadeFilho5() {
      return $this->iIdadeFilho5;
  }

  /**
   * Seta a idade do filho 5
   * @param integer $iIdadeFilho5
   */
  public function setIdadeFilho5 ($iIdadeFilho5) {
      $this->iIdadeFilho5 = $iIdadeFilho5;
  }

  /**
   * Define a idade do filho
   *
   * @param integer $iIndiceFilho
   * @param integer $iIdade
   * @access public
   * @return void
   */
  function setIdadeFilho( $iIndiceFilho,  $iIdade ) {
    $this->{"iIdadeFilho$iIndiceFilho"} = $iIdade;
  }
  
  public function toArray() {
    $oRetorno = array();
    
 //   $oRetorno[] = $this->getTipoInativo();
    $oRetorno[] = $this->getMatricula();
    $oRetorno[] = $this->getCpf();
    $oRetorno[] = $this->getDataNascimento()->getDate(DBDate::DATA_PTBR);
    $oRetorno[] = $this->getSexo();
    $oRetorno[] = $this->getDataInicioBeneficio()->getDate(DBDate::DATA_PTBR);
    $oRetorno[] = $this->getRemuneracao();
    $oRetorno[] = $this->getNumeroDependentes();
    $oRetorno[] = $this->getDataNascimentoConjuge();
    $oRetorno[] = $this->getIdadeFilho1();
    $oRetorno[] = $this->getIdadeFilho2();
    $oRetorno[] = $this->getIdadeFilho3();
    $oRetorno[] = $this->getIdadeFilho4();
    $oRetorno[] = $this->getIdadeFilho5();
    
    return $oRetorno;
  }
}