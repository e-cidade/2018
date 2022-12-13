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
 * Classe que representa um registro do ponto na e-consig
 *
 * @package folha
 * @author  Luiz Marcelo Schmitt  <luiz.marcelo@dbseller.com.br>
 */
class RegistroPontoEconsig extends RegistroPonto {

  
  /**
   * @var Integer
   */
  private $iSequencial;
  
  /**
   * @var $iMotivo
   */
  private $iMotivo = null;

  /**
   * @var $sNome
   */
  private $sNome;

  /**
   * @var $nValorDescontado
   */
  private $nValorDescontado;
  
  /**
   * Define iMotivo
   * @param integer
   */
  public function setMotivo ($iMotivo) {
    $this->iMotivo = $iMotivo;
  }
  
  /**
   * Retorna iMotivo
   * @return integer
   */
  public function getMotivo () {
    return $this->iMotivo; 
  }

  /**
   * Define Nome
   * @param String
   */
  public function setNome ($sNome) {
    $this->sNome = $sNome;
  }
  
  /**
   * Retorna Nome
   * @return String
   */
  public function getNome () {
    return $this->sNome; 
  }

   /**
   * Retorna o sequencial
   * @return Integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }
  
  /**
   * Seta o sequencial
   * @param Integer $iSequencial
   */
  public function setSequencial($iSequencial) {
    $this->iSequencial = $iSequencial;
  }

  /**
   * Define valor descontado do servidor
   * @param number
   */
  public function setValorDescontado ($nValorDescontado) {
    $this->nValorDescontado = $nValorDescontado;
  }
  
  /**
   * Retorna valor descontado do servidor
   * @return number
   */
  public function getValorDescontado () {
    return $this->nValorDescontado; 
  }
}
