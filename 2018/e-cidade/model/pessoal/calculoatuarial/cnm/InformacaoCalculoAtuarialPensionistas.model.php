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
 
require_once("model/pessoal/calculoatuarial/cnm/InformacaoCalculoAtuarial.model.php");

/**
 * Classe repons�vel por Tratar as informa��es referentes aos Inativos Pensionistas 
 * @author Renan Melo <renan@dbseller.com.br>
 */
class InformacaoCalculoAtuarialPensionistas extends InformacaoCalculoAtuarial {
  
  /**
   * N�mero da Matricula
   * @var integer
   */
  private $iMatricula;
  
  /**
   * N�mero do CPF
   * @var integer
   */
  private $iCpf;
  
  /**
   * Data de Nascimento do Instituidor (Servidor Falecido)da Pens�o
   * @var DBDate
   */
  private $oDataNascimentoInstituidor;
  
  /**
   * Data de Nascimento do Recebedor da Pens�o
   * @var DBDate
   */
  private $oDataNascimentoRecebedor;
  
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
   * Valor da Remunera��o / Beneficio
   * @var float
   */
  private $fRemuneracao;
  
  /**
   * N�mero de Dependentes
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
   * Fun��o Contrutora
   */
  public function __construct() {}
  
  /**
   * Retorna o n�mero da matricula
   * @return integer #iMatricula
   */
  public function getMatricula () {
    return $this->iMatricula;
  }
  
  /**
   * Seta o n�mero da Matricula
   * @param integer $iMatricula
   */
  public function setMatricula ($iMatricula) {
    $this->iMatricula = $iMatricula;
  }
  
  /**
   * Retorna o n�mero do CpF
   * @return integer $iCpf
   */
  public function getCpf() {
    return $this->iCpf;
  }
  
  /**
   * Seta o n�mero do Cpf
   * @param integer $iCpf
   */
  public function setCpf ($iCpf) {
    $this->iCpf = $iCpf;
  }
  
  /**
   * Seta a Data de Nascimento do Instituidor (Servidor Falecido)
   * @param date $dDataNascimentoInstituidor
   */
  public function setDataNascimentoInstituidor ($oDataNascimentoInstituidor) {
    $this->oDataNascimentoInstituidor = $oDataNascimentoInstituidor;
  }
  
  /**
   * Retorna a Data de Nascimento do Instituidor (Servidor Falecido)
   * @return DBDate $dDataNascimentoInstituidor
   */
  public function getDataNascimentoInstituidor () {
    return $this->oDataNascimentoInstituidor;
  }

  /**
   * Retorna a Data Nascimento do Recebedor da Pens�o
   * @return DBDate $oDataNascimentoRecebedor
   */
  public function getDataNascimentoRecebedor() {
    return $this->oDataNascimentoRecebedor;
  }
  
  /**
   * Seta a Data de Nascimento do Recebedor da Pens�o
   * @param date $dDataNascimentoRecebedor
   */
  public function setDataNascimentoRecebedor ($oDataNascimentoRecebedor) {
    $this->oDataNascimentoRecebedor = $oDataNascimentoRecebedor;
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
   * retorna valor da remunera��o
   * @return float $fRemuneracao
   */
  public function getRemuneracao() {
    return $this->fRemuneracao;
  }
  
  /**
   * Seta o valor da remunera��o
   * @param float $fRemuneracao
   */
  public function setRemuneracao ($fRemuneracao) {
    $this->fRemuneracao = $fRemuneracao;
  }
  
  /**
   * Returna o n�mero de dependentes
   * @return integer $iNumeroDependentes
   */
  public function getNumeroDependentes() {
    return $this->iNumeroDependentes;
  }
  
  /**
   * Seta o n�mero de dependentes
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
  
    $oRetorno[] = $this->getMatricula();
    $oRetorno[] = $this->getCpf();
    
    $dDataNascimentoInstituidor = null;
    if ($this->getDataNascimentoInstituidor() != null) {
    	$dDataNascimentoInstituidor = $this->getDataNascimentoInstituidor()->getDate(DBDate::DATA_PTBR);
    }
    
    $oRetorno[] = $dDataNascimentoInstituidor;
    $oRetorno[] = $this->getDataNascimentoRecebedor()->getDate(DBDate::DATA_PTBR);
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