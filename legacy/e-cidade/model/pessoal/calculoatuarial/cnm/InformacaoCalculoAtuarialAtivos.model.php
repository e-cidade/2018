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
 * InformacaoCalculoAtuarial
 * 
 * @package Calculo Atuarial
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 */
class InformacaoCalculoAtuarialAtivos extends InformacaoCalculoAtuarial {
  
  /**
   * iMatricula
   * 
   * @var mixed
   * @access private
   */
  private $iMatricula                   = null;
  /**
   * iIdade
   * 
   * @var mixed
   * @access private
   */
  private $iIdade                       = null;
  /**
   * sSexo
   * 
   * @var mixed
   * @access private
   */
  private $sSexo                        = null;
  /**
   * sPericulosidadeInsalubridade
   * 
   * @var mixed
   * @access private
   */
  private $sPericulosidadeInsalubridade = null;
  /**
   * sTempoServicoAnterior
   * 
   * @var mixed
   * @access private
   */
  private $sTempoServicoAnterior        = null;
  /**
   * sTempoServicoEnteEstatal
   * 
   * @var mixed
   * @access private
   */
  private $sTempoServicoEnteEstatal     = null;
  /**
   * sTempocontribuicaoFundo
   * 
   * @var mixed
   * @access private
   */
  private $sTempocontribuicaoFundo      = null;
  /**
   * sCodigoProfessor
   * 
   * @var mixed
   * @access private
   */
  private $sCodigoProfessor             = null;
  /**
   * nRemuneracao
   * 
   * @var mixed
   * @access private
   */
  private $nRemuneracao                 = null;
  /**
   * nRemuneracaoFinal
   * 
   * @var mixed
   * @access private
   */
  private $nRemuneracaoFinal            = null;
  /**
   * nReservaPoupanca
   * 
   * @var mixed
   * @access private
   */
  private $nReservaPoupanca             = null;
  /**
   * iQuantidadeDependentes
   * 
   * @var mixed
   * @access private
   */
  private $iQuantidadeDependentes       = null;
  /**
   * iIdadeConjuge
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeConjuge                = null;
  /**
   * iIdadeFilho0
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeFilho0                 = null;
  /**
   * iIdadeFilho1
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeFilho1                 = null;
  /**
   * iIdadeFilho2
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeFilho2                 = null;
  /**
   * iIdadeFilho3
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeFilho3                 = null;
  /**
   * iIdadeFilho4
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeFilho4                 = null;
  /**
   * iIdadeFilho5
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeFilho5                 = null;
  /**
   * iIdadeFilho6
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeFilho6                 = null;
  /**
   * iIdadeFilho7
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeFilho7                 = null;
  /**
   * iIdadeFilho8
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeFilho8                 = null;
  /**
   * iIdadeFilho9
   * 
   * @var mixed
   * @access private
   */
  private $iIdadeFilho9                 = null;
  /**
   * iTipoVinculacaoEstatal
   * 
   * @var mixed
   * @access private
   */
  private $iTipoVinculacaoEstatal       = null;
  /**
   * iTipoServidor
   * 
   * @var mixed
   * @access private
   */
  private $iTipoServidor                = null;



  /**
   * setMatricula
   *
   * @param integer $iMatricula
   * @access public
   * @return void
   */
  function setMatricula( $iMatricula ) {
    $this->iMatricula = $iMatricula;; 
  }

  /**
   * setIdade
   *
   * @param mixed $iIdade
   * @access public
   * @return void
   */
  function setIdade( $iIdade ) {
    $this->iIdade = $iIdade ; 
  }

  /**
   * setSexo
   *
   * @param mixed $sSexo
   * @access public
   * @return void
   */
  function setSexo( $sSexo ) {
    $this->sSexo = $sSexo;; 
  }

  /**
   * setPericulosidadeInsalubridade
   *
   * @param mixed $sPericulosidadeInsalubridade
   * @access public
   * @return void
   */
  function setPericulosidadeInsalubridade( $sPericulosidadeInsalubridade) {
    $this->sPericulosidadeInsalubridade = $sPericulosidadeInsalubridade; 
  }

  /**
   * setTempoServicoAnterior
   *
   * @param mixed $sTempoServicoAnterior
   * @access public
   * @return void
   */
  function setTempoServicoAnterior( $sTempoServicoAnterior ) {
    $this->sTempoServicoAnterior = $sTempoServicoAnterior; 
  }

  /**
   * setTempoServicoEnteEstatal
   *
   * @param mixed $sTempoServicoEnteEstatal
   * @access public
   * @return void
   */
  function setTempoServicoEnteEstatal( $sTempoServicoEnteEstatal ) {
    $this->sTempoServicoEnteEstatal = $sTempoServicoEnteEstatal; 
  }

  /**
   * setTempocontribuicaoFundo
   *
   * @param mixed $sTempocontribuicaoFundo
   * @access public
   * @return void
   */
  function setTempocontribuicaoFundo( $sTempocontribuicaoFundo ) {
    $this->sTempocontribuicaoFundo =$sTempocontribuicaoFundo; 
  }

  /**
   * setCodigoProfessor
   *
   * @param mixed $sCodigoProfessor
   * @access public
   * @return void
   */
  function setCodigoProfessor( $sCodigoProfessor ) {
    $this->sCodigoProfessor = $sCodigoProfessor; 
  }

  /**
   * setRemuneracao
   *
   * @param mixed $nRemuneracao
   * @access public
   * @return void
   */
  function setRemuneracao( $nRemuneracao ) {
    $this->nRemuneracao = $nRemuneracao; 
  }

  /**
   * setRemuneracaoFinal
   *
   * @param mixed $nRemuneracaoFinal
   * @access public
   * @return void
   */
  function setRemuneracaoFinal( $nRemuneracaoFinal ) {
    $this->nRemuneracaoFinal = $nRemuneracaoFinal; 
  }

  /**
   * setReservaPoupanca
   *
   * @param mixed $nReservaPoupanca
   * @access public
   * @return void
   */
  function setReservaPoupanca( $nReservaPoupanca ) {
    $this->nReservaPoupanca = $nReservaPoupanca; 
  }

  /**
   * setQuantidadeDependentes
   *
   * @param mixed $iQuantidadeDependentes
   * @access public
   * @return void
   */
  function setQuantidadeDependentes( $iQuantidadeDependentes ) {
    $this->iQuantidadeDependentes = $iQuantidadeDependentes; 
  }

  /**
   * setIdadeConjuge
   *
   * @param mixed $iIdadeConjuge
   * @access public
   * @return void
   */
  function setIdadeConjuge( $iIdadeConjuge ) {
    $this->iIdadeConjuge = $iIdadeConjuge; 
  }

  /**
   * setIdadeFilho0
   *
   * @param mixed $iIdadeFilho0
   * @access public
   * @return void
   */
  function setIdadeFilho0( $iIdadeFilho0 ) {
    $this->iIdadeFilho0 = $iIdadeFilho0; 
  }

  /**
   * setIdadeFilho1
   *
   * @param mixed $iIdadeFilho1
   * @access public
   * @return void
   */
  function setIdadeFilho1( $iIdadeFilho1 ) {
    $this->iIdadeFilho1 = $iIdadeFilho1; 
  }

  /**
   * setIdadeFilho2
   *
   * @param mixed $iIdadeFilho2
   * @access public
   * @return void
   */
  function setIdadeFilho2( $iIdadeFilho2 ) {
    $this->iIdadeFilho2 = $iIdadeFilho2; 
  }

  /**
   * setIdadeFilho3
   *
   * @param mixed $iIdadeFilho3
   * @access public
   * @return void
   */
  function setIdadeFilho3( $iIdadeFilho3 ) {
    $this->iIdadeFilho3 = $iIdadeFilho3;; 
  }

  /**
   * setIdadeFilho4
   *
   * @param mixed $iIdadeFilho4
   * @access public
   * @return void
   */
  function setIdadeFilho4( $iIdadeFilho4 ) {
    $this->iIdadeFilho4 = $iIdadeFilho4; 
  }

  /**
   * setIdadeFilho5
   *
   * @param mixed $iIdadeFilho5
   * @access public
   * @return void
   */
  function setIdadeFilho5( $iIdadeFilho5 ) {
    $this->iIdadeFilho5 = $iIdadeFilho5; 
  }

  /**
   * setIdadeFilho6
   *
   * @param mixed $iIdadeFilho6
   * @access public
   * @return void
   */
  function setIdadeFilho6( $iIdadeFilho6 ) {
    $this->iIdadeFilho6 = $iIdadeFilho6; 
  }

  /**
   * setIdadeFilho7
   *
   * @param mixed $iIdadeFilho7
   * @access public
   * @return void
   */
  function setIdadeFilho7( $iIdadeFilho7 ) {
    $this->iIdadeFilho7 = $iIdadeFilho7;
  }

  /**
   * setIdadeFilho8
   *
   * @param mixed $iIdadeFilho8
   * @access public
   * @return void
   */
  function setIdadeFilho8( $iIdadeFilho8 ) {
    $this->iIdadeFilho8 = $iIdadeFilho8; 
  }

  /**
   * setIdadeFilho9
   *
   * @param mixed $iIdadeFilho9
   * @access public
   * @return void
   */
  function setIdadeFilho9( $iIdadeFilho9 ) {
    $this->iIdadeFilho9 = $iIdadeFilho9; 
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

  /**
   * setTipoVinculacaoEstatal
   *
   * @param mixed $iTipoVinculacaoEstatal
   * @access public
   * @return void
   */
  function setTipoVinculacaoEstatal( $iTipoVinculacaoEstatal ) {
    $this->iTipoVinculacaoEstatal = $iTipoVinculacaoEstatal; 
  }

  /**
   * setTipoServidor
   *
   * @param mixed $iTipoServidor
   * @access public
   * @return void
   */
  function setTipoServidor( $iTipoServidor ) {
    $this->iTipoServidor = $iTipoServidor;
  }

  /**
   * getMatricula
   *
   * @access public
   * @return void
   */
  function getMatricula() {
    return $this->iMatricula; 
  }

  /**
   * getIdade
   *
   * @access public
   * @return void
   */
  function getIdade() {
    return $this->iIdade; 
  }

  /**
   * getSexo
   *
   * @access public
   * @return void
   */
  function getSexo() {
    return $this->sSexo; 
  }

  /**
   * getPericulosidadeInsalubridade
   *
   * @access public
   * @return void
   */
  function getPericulosidadeInsalubridade() {
    return $this->sPericulosidadeInsalubridade; 
  }

  /**
   * getTempoServicoAnterior
   *
   * @access public
   * @return void
   */
  function getTempoServicoAnterior() {
    return $this->sTempoServicoAnterior; 
  }

  /**
   * getTempoServicoEnteEstatal
   *
   * @access public
   * @return void
   */
  function getTempoServicoEnteEstatal() {
    return $this->sTempoServicoEnteEstatal; 
  }

  /**
   * getTempocontribuicaoFundo
   *
   * @access public
   * @return void
   */
  function getTempocontribuicaoFundo() {
    return $this->sTempocontribuicaoFundo; 
  }

  /**
   * getCodigoProfessor
   *
   * @access public
   * @return void
   */
  function getCodigoProfessor() {
    return $this->sCodigoProfessor; 
  }

  /**
   * getRemuneracao
   *
   * @access public
   * @return void
   */
  function getRemuneracao() {
    return $this->nRemuneracao; 
  }

  /**
   * getRemuneracaoFinal
   *
   * @access public
   * @return void
   */
  function getRemuneracaoFinal() {
    return $this->nRemuneracaoFinal; 
  }

  /**
   * getReservaPoupanca
   *
   * @access public
   * @return void
   */
  function getReservaPoupanca() {
    return $this->nReservaPoupanca; 
  }

  /**
   * getQuantidadeDependentes
   *
   * @access public
   * @return void
   */
  function getQuantidadeDependentes() {
    return $this->iQuantidadeDependentes; 
  }

  /**
   * getIdadeConjuge
   *
   * @access public
   * @return void
   */
  function getIdadeConjuge() {
    return $this->iIdadeConjuge; 
  }

  /**
   * getIdadeFilho0
   *
   * @access public
   * @return void
   */
  function getIdadeFilho0() {
    return $this->iIdadeFilho0; 
  }

  /**
   * getIdadeFilho1
   *
   * @access public
   * @return void
   */
  function getIdadeFilho1() {
    return $this->iIdadeFilho1; 
  }

  /**
   * getIdadeFilho2
   *
   * @access public
   * @return void
   */
  function getIdadeFilho2() {
    return $this->iIdadeFilho2; 
  }

  /**
   * getIdadeFilho3
   *
   * @access public
   * @return void
   */
  function getIdadeFilho3() {
    return $this->iIdadeFilho3; 
  }

  /**
   * getIdadeFilho4
   *
   * @access public
   * @return void
   */
  function getIdadeFilho4() {
    return $this->iIdadeFilho4; 
  }

  /**
   * getIdadeFilho5
   *
   * @access public
   * @return void
   */
  function getIdadeFilho5() {
    return $this->iIdadeFilho5; 
  }

  /**
   * getIdadeFilho6
   *
   * @access public
   * @return void
   */
  function getIdadeFilho6() {
    return $this->iIdadeFilho6; 
  }

  /**
   * getIdadeFilho7
   *
   * @access public
   * @return void
   */
  function getIdadeFilho7() {
    return $this->iIdadeFilho7; 
  }

  /**
   * getIdadeFilho8
   *
   * @access public
   * @return void
   */
  function getIdadeFilho8() {
    return $this->iIdadeFilho8; 
  }

  /**
   * getIdadeFilho9
   *
   * @access public
   * @return void
   */
  function getIdadeFilho9() {
    return $this->iIdadeFilho9; 
  }

  /**
   * getTipoVinculacaoEstatal
   *
   * @access public
   * @return void
   */
  function getTipoVinculacaoEstatal() {
    return $this->iTipoVinculacaoEstatal; 
  }

  /**
   * getTipoServidor
   *
   * @access public
   * @return void
   */
  function getTipoServidor() {
    return $this->iTipoServidor; 
  }




  /**
   * Retorna os Dados emoArray
   *
   * @access public
   * @return void
   */
  function toArray() {

    $aRetorno   = array(); 
    $aRetorno[] = $this->getMatricula();
    $aRetorno[] = $this->getIdade();
    $aRetorno[] = $this->getSexo();
    $aRetorno[] = $this->getPericulosidadeInsalubridade();
    $aRetorno[] = $this->getTempoServicoAnterior();
    $aRetorno[] = $this->getTempoServicoEnteEstatal();
    $aRetorno[] = $this->getTempocontribuicaoFundo();
    $aRetorno[] = $this->getCodigoProfessor();
    $aRetorno[] = $this->getRemuneracao();
    $aRetorno[] = $this->getRemuneracaoFinal();
    $aRetorno[] = $this->getReservaPoupanca();
    $aRetorno[] = $this->getQuantidadeDependentes();
    $aRetorno[] = $this->getIdadeConjuge();
    $aRetorno[] = $this->getIdadeFilho0();
    $aRetorno[] = $this->getIdadeFilho1();
    $aRetorno[] = $this->getIdadeFilho2();
    $aRetorno[] = $this->getIdadeFilho3();
    $aRetorno[] = $this->getIdadeFilho4();
    $aRetorno[] = $this->getIdadeFilho5();
    $aRetorno[] = $this->getIdadeFilho6();
    $aRetorno[] = $this->getIdadeFilho7();
    $aRetorno[] = $this->getIdadeFilho8();
    $aRetorno[] = $this->getIdadeFilho9();
    $aRetorno[] = $this->getTipoVinculacaoEstatal();
    $aRetorno[] = $this->getTipoServidor();
   return $aRetorno;
  }
}