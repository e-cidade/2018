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
 * Classe para controle das informacoes e acoes referentes a cadenderbairro
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 * @subpackage endereco
 */
class Bairro {
  
  /**
   * Sequencial de cadenderbairro
   * @var integer
   */
  private $iSequencial;
  
  /**
   * Instancia de Municipio
   * @var Municipio
   */
  private $oMunicipio;
  
  /**
   * Descricao do bairro
   * @var string
   */
  private $sDescricao;
  
  /**
   * Sigla do bairro
   * @var string
   */
  private $sSigla;
  
  /**
   * Array dos logradouros vinculados ao bairro
   * @var array
   */
  private $aLogradouros = array();
  
  /**
   * Construtor da classe. Recebe como parametro o sequencial da tabela cadenderbairro
   * @param integer $iSequencial
   */
  public function __construct($iSequencial = null) {
    
    if(!empty($iSequencial)) {
      
      $oDaoCadEnderBairro = new cl_cadenderbairro();
      $sSqlCadEnderBairro = $oDaoCadEnderBairro->sql_query_file($iSequencial);
      $rsCadEnderBairro   = $oDaoCadEnderBairro->sql_record($sSqlCadEnderBairro);
      
      if ($oDaoCadEnderBairro->numrows == 0) {
        throw new ParameterException("Bairro não encontrado pelo sequencial informado.");
      }
        
      $oDadosCadEnderBairro = db_utils::fieldsMemory($rsCadEnderBairro, 0);
      $this->iSequencial    = $oDadosCadEnderBairro->db73_sequencial;
      $this->oMunicipio     = new Municipio($oDadosCadEnderBairro->db73_cadendermunicipio);
      $this->sDescricao     = $oDadosCadEnderBairro->db73_descricao;
      $this->sSigla         = $oDadosCadEnderBairro->db73_sigla;
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
   * Retorna uma instancia de Municipio
   * @return Municipio
   */
  public function getMunicipio() {
    return $this->oMunicipio;
  }
  
  /**
   * Seta uma instancia de Municipio
   * @param Municipio $oMunicipio
   */
  public function setMunicipio(Municipio $oMunicipio) {
    $this->oMunicipio = $oMunicipio;
  }
  
  /**
   * Retorna a descricao do bairro
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  
  }
  
  /**
   * Seta a descricao do bairro
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna a sigla do bairro
   * @return string
   */
  public function getSigla() {
    return $this->sSigla;
  }
  
  /**
   * Seta a sigla do bairro
   * @param string $sSigla
   */
  public function setSigla($sSigla) {
    $this->sSigla = $sSigla;
  }
  
  /**
   * Retorna um array de instancia de Logradouro
   * @return array Logradouro
   */
  public function getLogradourosVinculados() {
    
    $oDaoCadEnderBairroCadEnderRua   = new cl_cadenderbairrocadenderrua();
    $sWhereCadEnderBairroCadEnderRua = "db87_cadenderbairro = {$this->getSequencial()}";
    $sSqlCadEnderBairroCadEnderRua   = $oDaoCadEnderBairroCadEnderRua->sql_query(
                                                                                  null,
                                                                                  "db87_cadenderrua",
                                                                                  "db87_cadenderrua",
                                                                                  $sWhereCadEnderBairroCadEnderRua
                                                                                );
    $rsCadEnderBairroCadEnderRua     = $oDaoCadEnderBairroCadEnderRua->sql_record($sSqlCadEnderBairroCadEnderRua);
    $iTotalCadEnderBairroCadEnderRua = $oDaoCadEnderBairroCadEnderRua->numrows;
    
    if ($iTotalCadEnderBairroCadEnderRua > 0) {
      
      for ($iContador = 0; $iContador < $iTotalCadEnderBairroCadEnderRua; $iContador++) {
        
        $iCadEnderRua         = db_utils::fieldsMemory($rsCadEnderBairroCadEnderRua, $iContador)->db87_cadenderrua;
        $oLogradouro          = new Logradouro($iCadEnderRua);
        $this->aLogradouros[] = $oLogradouro;
      }
    }
    
    return $this->aLogradouros;
  }
  
  /**
   * Salva um novo bairro
   * @throws DBException
   * @throws BusinessException
   */
  public function salvar() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com o banco de dados ativa.");
    }
    
    $oDaoCadEnderBairro = new cl_cadenderbairro();
    
    if ($this->getSequencial() == null) {
      
      $sWhereCadEnderBairro  = "    db73_cadendermunicipio = {$this->getMunicipio()->getSequencial()} ";
      $sWhereCadEnderBairro .= "and db73_descricao = trim('{$this->getDescricao()}')";
      $sSqlCadEnderBairro    = $oDaoCadEnderBairro->sql_query_file(null, "db73_sequencial", null, $sWhereCadEnderBairro);
      $rsCadEnderBairro      = $oDaoCadEnderBairro->sql_record($sSqlCadEnderBairro);
      
      if ($oDaoCadEnderBairro->numrows > 0) {
        throw new BusinessException("Bairro já vinculado ao município selecionado.");
      }
    }
    
    $oDaoCadEnderBairro->db73_cadendermunicipio = $this->getMunicipio()->getSequencial();
    $oDaoCadEnderBairro->db73_descricao         = $this->getDescricao();
    $oDaoCadEnderBairro->db73_sigla             = $this->getSigla();
    
    if ($this->getSequencial() != null) {
      
      $oDaoCadEnderBairro->db73_sequencial = $this->getSequencial();
      $oDaoCadEnderBairro->alterar($this->getSequencial());
    } else {
      $oDaoCadEnderBairro->incluir(null);
    }
    
    if ($oDaoCadEnderBairro->erro_status == 0) {
      throw new BusinessException($oDaoCadEnderBairro->erro_msg);
    }
  }
}