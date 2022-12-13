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

class Regime {

  const VINCULO_ATIVO       = 'A';
  const VINCULO_PENSIONISTA = 'P';
  const VINCULO_INATIVO     = 'I';

  /**
   * Codigo do regime
   * @var Integer
   */
  private $iCodigo;

  /**
   * Descricao do regime
   * @var String
   */
  private $sDescricao;

  /**
   * Base para o servidor substituido
   * @var Base
   */
  private $oBaseServidorSubstituido;

  /**
   * Base para o servidor substituto
   * @var Base
   */
  private $oBaseServidorSubstituto;


  /**
   * Construtor da classe
   */
  public function __construct( $iCodigo = null) {

    if (!empty($iCodigo)) {
      $this->iCodigo = $iCodigo;
    }
  }

  /**
   * Retorna Codigo do regime.
   *
   * @return Integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define Codigo do regime.
   *
   * @param Integer $iCodigo
   */
  public function setCodigo($iCodigo) {

    $this->iCodigo = $iCodigo;
    return;
  }

  /**
   * Retorna Descricao do regime.
   *
   * @return String
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Define Descricao do regime.
   *
   * @param String $sDescricao s descricao
   * @return void
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
    return;
  }

  /**
   * Retorna Base para o servidor substituido.
   *
   * @return Base
   */
  public function getBaseServidorSubstituido() {
    return $this->oBaseServidorSubstituido;
  }

  /**
   * Define Base para o servidor substituido.
   *
   * @param Base $oBaseServidorSubstituido o base servidor substituido
   * @return void
   */
  public function setBaseServidorSubstituido(Base $oBaseServidorSubstituido) {

    $this->oBaseServidorSubstituido = $oBaseServidorSubstituido;
    return;
  }

  /**
   * Retorna Base para o servidor substituto.
   * @return Base
   */
  public function getBaseServidorSubstituto() {
    return $this->oBaseServidorSubstituto;
  }

  /**
   * Define Base para o servidor substituto.
   *
   * @param Base $oBaseServidorSubstituto o base servidor substituto
   * @return void
   */
  public function setBaseServidorSubstituto(Base $oBaseServidorSubstituto) {
  
    $this->oBaseServidorSubstituto = $oBaseServidorSubstituto;
    return;
  }
}