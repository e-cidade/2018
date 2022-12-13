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
 * Classe para controle das informacoes e acoes referentes a cadenderrua
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 * @subpackage endereco
 */
class Logradouro {

  /**
   * Sequencial de cadenderrua
   * @var integer
   */
  private $iSequencial;

  /**
   * Instancia de Municipio
   * @var Municipio
   */
  private $oMunicipio;

  /**
   * Descricao do logradouro
   * @var string
   */
  private $sDescricao;

  /**
   * Instancia de Bairro, referente ao bairro inicial da rua
   * @var Bairro
   */
  private $oBairroInicial;

  /**
   * Instancia de Bairro, referente ao bairro final da rua
   * @var Bairro
   */
  private $oBairroFinal;

  /**
   * Numero inicial do logradouro
   * @var integer
   */
  private $iNumeroInicial;

  /**
   * Numero final do logradouro
   * @var integer
   */
  private $iNumeroFinal;

  /**
   * CEP do logradouro
   * @var string
   */
  private $sCep;

  /**
   * Bairros que o logradouro esta vinculado
   * @var array
   */
  private $aBairros = array();

  /**
   * Construtor da classe. Recebe como parametro o sequencial da tabela cadenderrua
   * @param integer $iSequencial
   */
  public function __construct($iSequencial = null) {

    if (!empty($iSequencial)) {

      $oDaoCadEnderRua = new cl_cadenderrua();
      $sSqlCadEnderRua = $oDaoCadEnderRua->sql_query_file($iSequencial);
      $rsCadEnderRua   = $oDaoCadEnderRua->sql_record($sSqlCadEnderRua);

      if ($oDaoCadEnderRua->numrows == 0) {
       throw new ParameterException("Logradouro não encontrado pelo sequencial informado.");
      }

      $oDadosCadEnderRua    = db_utils::fieldsMemory($rsCadEnderRua, 0);
      $this->iSequencial    = $oDadosCadEnderRua->db74_sequencial;
      $this->oMunicipio     = new Municipio($oDadosCadEnderRua->db74_cadendermunicipio);
      $this->sDescricao     = $oDadosCadEnderRua->db74_descricao;
      $this->oBairroInicial = new Bairro($oDadosCadEnderRua->db74_bairroinicial);
      $this->oBairroFinal   = new Bairro($oDadosCadEnderRua->db74_bairrofinal);
      $this->iNumeroInicial = $oDadosCadEnderRua->db74_numinicial;
      $this->iNumeroFinal   = $oDadosCadEnderRua->db74_numfinal;
      $this->sCep           = $oDadosCadEnderRua->db74_cep;
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
   * @param $iSequencial
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
  public function setMunicipio($oMunicipio) {
    $this->oMunicipio = $oMunicipio;
  }

  /**
   * Retorna a descricao do logradouro
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descricao do logradouro
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna uma instancia de Bairro, referente ao bairro inicial do logradouro
   * @return Bairro
   */
  public function getBairroInicial() {
    return $this->oBairroInicial;
  }

  /**
   * Seta uma instancia de Bairro, referente ao bairro inicial do logradouro
   * @param Bairro $oBairroInicial
   */
  public function setBairroInicial(Bairro $oBairroInicial) {
    $this->oBairroInicial = $oBairroInicial;
  }

  /**
   * Retorna uma instancia de Bairro, referente ao bairro final do logradouro
   * @return Bairro
   */
  public function getBairroFinal() {
    return $this->oBairroFinal;
  }

  /**
   * Seta uma instancia de Bairro, referente ao bairro final do logradouro
   * @param Bairro $oBairroFinal
   */
  public function setBairroFinal(Bairro $oBairroFinal) {
    $this->oBairroFinal = $oBairroFinal;
  }

  /**
   * Retorna o numero inicial do logradouro
   * @return integer
   */
  public function getNumeroInicial() {
    return $this->iNumeroInicial;
  }

  /**
   * Seta o numero inicial do logradouro
   * @param integer $iNumeroInicial
   */
  public function setNumeroInicial($iNumeroInicial) {
    $this->iNumeroInicial = $iNumeroInicial;
  }

  /**
   * Retorna o numero final do logradouro
   * @return integer
   */
  public function getNumeroFinal() {
    return $this->iNumeroFinal;
  }

  /**
   * Seta o numero final do logradouro
   * @param integer$iNumeroFinal
   */
  public function setNumeroFinal($iNumeroFinal) {
    $this->iNumeroFinal = $iNumeroFinal;
  }

  /**
   * Retorna o CEP do logradouro
   * @return string
   */
  public function getCep() {
    return $this->sCep;
  }

  /**
   * Seta o CEP do logradouro
   * @param string $sCep
   */
  public function setCep($sCep) {
    $this->sCep = $sCep;
  }

  /**
   * Retorna um array com a instancia de Bairro, dos bairros que o logradouro esta vinculado
   * @return array Bairro
   */
  public function getBairrosVinculados() {

    $oDaoCadEnderBairroCadEnderRua   = new cl_cadenderbairrocadenderrua();
    $sWhereCadEnderBairroCadEnderRua = "db87_cadenderrua = {$this->getSequencial()}";
    $sSqlCadEnderBairroCadEnderRua   = $oDaoCadEnderBairroCadEnderRua->sql_query(
                                                                                  null,
                                                                                  "db87_cadenderbairro",
                                                                                  "db87_cadenderbairro",
                                                                                  $sWhereCadEnderBairroCadEnderRua
                                                                                );
    $rsCadEnderBairroCadEnderRua     = $oDaoCadEnderBairroCadEnderRua->sql_record($sSqlCadEnderBairroCadEnderRua);
    $iTotalCadEnderBairroCadEnderRua = $oDaoCadEnderBairroCadEnderRua->numrows;

    if ($iTotalCadEnderBairroCadEnderRua > 0) {

      for ($iContador = 0; $iContador < $iTotalCadEnderBairroCadEnderRua; $iContador++) {

        $iCadEnderBairro  = db_utils::fieldsMemory($rsCadEnderBairroCadEnderRua, $iContador)->db87_cadenderbairro;
        $oBairro          = new Bairro($iCadEnderBairro);
        $this->aBairros[] = $oBairro;
      }
    }

    return $this->aBairros;
  }

  /**
   * Adiciona um vinculo entre um bairro e um logradouro
   * @param Bairro $oBairro
   * @throws DBException
   * @throws BusinessException
   */
  public function adicionarBairro(Bairro $oBairro) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com o banco de dados ativa.");
    }

    $oDaoCadEnderBairroCadEnderRua    = new cl_cadenderbairrocadenderrua();
    $sWhereCadEnderBairroCadEnderRua  = "     db87_cadenderrua = {$this->getSequencial()}";
    $sWhereCadEnderBairroCadEnderRua .= " and db87_cadenderbairro = {$oBairro->getSequencial()}";
    $sSqlCadEnderBairroCadEnderRua    = $oDaoCadEnderBairroCadEnderRua->sql_query(
                                                                                    null,
                                                                                    "db87_sequencial,
                                                                                     db87_cadenderrua",
                                                                                    "db87_sequencial",
                                                                                    $sWhereCadEnderBairroCadEnderRua
                                                                                 );
    $rsCadEnderBairroCadEnderRua = $oDaoCadEnderBairroCadEnderRua->sql_record($sSqlCadEnderBairroCadEnderRua);

    /**
     * Exclui todos os vínculos entre bairro e o logradouro utilizando o sequencial do logradouro
     * e adiciona novamente com as alterações.
     */
    if ($oDaoCadEnderBairroCadEnderRua->numrows > 0) {

      $iCadEnderEnderRua = db_utils::fieldsMemory($rsCadEnderBairroCadEnderRua, 0)->db87_cadenderrua;
      $oDaoCadEnderBairroCadEnderRua->excluir(null, "db87_cadenderrua = {$iCadEnderEnderRua}");
    }

    $oDaoCadEnderBairroCadEnderRua->db87_cadenderbairro = $oBairro->getSequencial();
    $oDaoCadEnderBairroCadEnderRua->db87_cadenderrua    = $this->getSequencial();
    $oDaoCadEnderBairroCadEnderRua->incluir(null);

    if ($oDaoCadEnderBairroCadEnderRua->erro_status == 0) {
      throw new BusinessException($oDaoCadEnderBairroCadEnderRua->erro_msg);
    }
  }

  /**
   * Salva um novo logradouro
   * @throws DBException
   * @throws BusinessException
   *
   * @todo No momento, salvamos apenas a descricao e o municipio que o logradouro esta vinculado, pois nao setamos os
   * demais campos. Alterar o valor setado nos campos, para receberem o que for setado
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com o banco de dados ativa.");
    }

    $oDaoCadEnderRua = new cl_cadenderrua();
    
    if ($this->getSequencial() == null) {
      
      $sWhereCadEnderRua  = "    db74_cadendermunicipio = {$this->getMunicipio()->getSequencial()} ";
      $sWhereCadEnderRua .= "and db74_descricao = trim('{$this->getDescricao()}')";
      $sSqlCadEnderRua    = $oDaoCadEnderRua->sql_query_file(null, "db74_sequencial", null, $sWhereCadEnderRua);
      $rsCadEnderRua      = $oDaoCadEnderRua->sql_record($sSqlCadEnderRua);
      
      if ($oDaoCadEnderRua->numrows > 0) {
        throw new BusinessException("Logradouro já cadastrado para o município.");
      }
    }
    
    $oDaoCadEnderRua->db74_descricao         = $this->getDescricao();
    $oDaoCadEnderRua->db74_cadendermunicipio = $this->getMunicipio()->getSequencial();
    $oDaoCadEnderRua->db74_bairroinicial     = 0;
    $oDaoCadEnderRua->db74_bairrofinal       = 0;
    $oDaoCadEnderRua->db74_numinicial        = 0;
    $oDaoCadEnderRua->db74_numfinal          = 0;
    $oDaoCadEnderRua->db74_cep               = '';

    if ($this->getSequencial() != null) {

      $oDaoCadEnderRua->db74_sequencial = $this->getSequencial();
      $oDaoCadEnderRua->alterar($this->getSequencial());
    } else {
      $oDaoCadEnderRua->incluir(null);
      $this->iSequencial =  $oDaoCadEnderRua->db74_sequencial;
    }

    if ($oDaoCadEnderRua->erro_status == 0) {
      throw new BusinessException($oDaoCadEnderRua->erro_msg);
    }
  }
}