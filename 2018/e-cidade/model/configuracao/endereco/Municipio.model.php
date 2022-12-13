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

/**
 * Classe para controle das informacoes e acoes referentes a cadendermunicipio
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 * @subpackage endereco
 */
class Municipio {
  
  /**
   * Sequencial de cadendermunicipio
   * @var integer
   */
  private $iSequencial;
  
  /**
   * Instancia de Estado
   * @var Estado
   */
  private $oEstado;
  
  /**
   * Descricao do municipio
   * @var string
   */
  private $sDescricao;

  /**
   * Sigla do municipio
   * @var string
   */
  private $sSigla;
  
  /**
   * CEP inicial do municipio
   * @var string
   */
  private $sCepInicial;
  
  /**
   * CEP final do municipio
   * @var string
   */
  private $sCepFinal;
  
  /**
   * Array com os bairros vinculados ao municipio
   * @var array
   */
  private $aBairros = array();
  
  /**
   * Construtor da classe. Recebe como parametro o sequencial da tabela cadendermunicipio
   * @param integer $iSequencial
   */
  public function __construct($iSequencial = null) {
  
    if (!empty($iSequencial)) {
  
      $oDaoCadEnderMunicipio = new cl_cadendermunicipio();
      $sSqlCadEnderMunicipio = $oDaoCadEnderMunicipio->sql_query_file($iSequencial);
      $rsCadEnderMunicipio   = $oDaoCadEnderMunicipio->sql_record($sSqlCadEnderMunicipio);
  
      if ($oDaoCadEnderMunicipio->numrows == 0) {
        throw new ParameterException("Município não encontrado pelo sequencial informado.");
      }
      
      $oDadosCadEnderMunicipio = db_utils::fieldsMemory($rsCadEnderMunicipio, 0);
      $this->iSequencial = $oDadosCadEnderMunicipio->db72_sequencial;
      $this->oEstado     = new Estado($oDadosCadEnderMunicipio->db72_cadenderestado);
      $this->sDescricao  = $oDadosCadEnderMunicipio->db72_descricao;
      $this->sSigla      = $oDadosCadEnderMunicipio->db72_sigla;
      $this->sCepInicial = $oDadosCadEnderMunicipio->db72_cepinicial;
      $this->sCepFinal   = $oDadosCadEnderMunicipio->db72_cepfinal;
    }
  }
  
  /**
   * Retorna o sequencial
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }
  
  /**
   * Seta o sequencial
   * @param integer $iSequencial
   */
  public function setSequencial($iSequencial) {
    $this->iSequencial = $iSequencial;
  }
  
  /**
   * Retorna uma instancia de Estado
   * @return Estado
   */
  public function getEstado() {
    return $this->oEstado;
  }
  
  /**
   * Seta uma instancia de Estado
   * @param Estado $oEstado
   */
  public function setEstado(Estado $oEstado) {
    $this->oEstado = $oEstado;
  }
  
  /**
   * Retorna a descricao do municipio
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
  
  /**
   * Seta a descricao do municipio
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna a sigla do municipio
   * @return string
   */
  public function getSigla() {
    return $this->sSigla;
  }
  
  /**
   * Seta a sigla do municipio
   * @param string $sSigla
   */
  public function setSigla($sSigla) {
    $this->sSigla = $sSigla;
  }
  
  /**
   * Retorna o CEP inicial do municipio
   * @return string
   */
  public function getCepInicial() {
    return $this->sCepInicial;
  }

  /**
   * Seta o CEP inicial do municipio
   * @param string $sCepInicial
   */
  public function setCepInicial($sCepInicial) {
    $this->sCepInicial = $sCepInicial;
  }

  /**
   * Retorna o CEP final do municipio
   * @return string
   */
  public function getCepFinal() {
    return $this->sCepFinal;
  }

  /**
   * Seta o CEP final do municipio
   * @param string $sCepFinal
   */
  public function setCepFinal($sCepFinal) {
    $this->sCepFinal = $sCepFinal;
  }
  
  /**
   * Retorna um array com a instancia de Bairro, dos bairros vinculados ao municipio
   * @return array Bairro
   */
  public function getBairroVinculados() {
  
    $oDaoCadEnderBairro   = new cl_cadenderbairro();
    $sWhereCadEnderBairro = "db73_cadendermunicipio = {$this->getSequencial()}";
    $sSqlCadEnderBairro   = $oDaoCadEnderBairro->sql_query(
                                                             null,
                                                             "db73_sequencial",
                                                             "db73_sequencial",
                                                             $sWhereCadEnderBairro
                                                          );
    $rsCadEnderBairro     = $oDaoCadEnderBairro->sql_record($sSqlCadEnderBairro);
    $iTotalCadEnderBairro = $oDaoCadEnderBairro->numrows;
  
    if ($iTotalCadEnderBairro > 0) {
  
      for ($iContador = 0; $iContador < $iTotalCadEnderBairro; $iContador++) {
  
        $iCadEnderBairro  = db_utils::fieldsMemory($rsCadEnderBairro, $iContador)->db73_sequencial;
        $oBairro          = new Bairro($iCadEnderBairro);
        $this->aBairros[] = $oBairro;
      }
    }
  
    return $this->aBairros;
  }
}