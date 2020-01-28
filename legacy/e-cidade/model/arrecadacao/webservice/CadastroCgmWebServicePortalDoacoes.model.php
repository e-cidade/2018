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

require_once modification("model/CgmFactory.model.php");
require_once modification("model/endereco.model.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

/**
 * Model para cadastro de cgm do sistemas webservice, baseado no CadastroCgmWebService.model.php
 * @author Fabio Egidio         <fabio.egidio@dbseller.com.br>
 *
 */
class CadastroCgmWebServicePortalDoacoes {
  
  /**
   * Nome do cgm
   * @var string
   */
  private $sNome;
  
  /**
   * Se o endereço informado é do municipio
   * @var boolean
   */
  private $lEnderecoMunicipio;
   
  /**
   * Nome do Logradouro
   * @var string
   */
  private $sDescricaoLogradouro;
  
  /**
   * Número do Logradouro
   * @var integer
   */
  private $iNumeroLogradouro;
  
  /**
   * Complemento do Logradouro
   * @var string
   */
  private $sComplemento;
  
  /**
   * Nome do bairro
   * @var string
   */
  private $sDescricaoBairro;

  /**
   * Nome da UF
   * @var string
   */
  private $sUf;
  
  /**
   * E-mail do cgm
   * @var string
   */
  private $sEmail;
  
  /**
   * Telefone do cgm
   * @var string
   */
  private $sTelefone;
  
  /**
   * Telefone celular do cgm
   * @var string
   */
  private $sCelular;
  
  /**
   * Número de cpf do cgm
   * @var string
   */
  private $sCpf;
  
  /**
   * Número do CNPJ do cgm
   * @var string
   */
  private $sCnpj;
  
  /**
   * Número CEP
   * @var integer
   */
  private $sCep;
  
  /**
   * Código da cidade no e-cidade
   * @var  integer
   */
  private $iCodigoCidade;
  
  /**
   * Construtor da classe
   */
  public function __construct() {}
  
  /**
   * Define o cgf para o cgm
   * @param string $sCpf
   */
  public function setCpf($sCpf) {
    $this->sCpf = $sCpf;
  }
  
  /**
   * Define o cnpj para o cgm
   * @param string $sCnpj
   */
  public function setCnpj($sCnpj) {
    $this->sCnpj = $sCnpj;
  }
  
  /**
   * Define o Nome para o CGM
   * @param string $sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Define o codigo da cidade do cgm
   * @param integer $iCodigoCidade
   */
  public function setCodigoCidade($iCodigoCidade) {
    $this->iCodigoCidade = $iCodigoCidade;
  }
  
  /**
   * Define se o endereço é do município
   * @param boolean $lEnderecoMunicipio
   */
  public function setEnderecoMunicipio($lEnderecoMunicipio) {
    $this->lEnderecoMunicipio = $lEnderecoMunicipio;
  }
  
  /**
   * Define a descrição do logradouro do cgm
   * @param string $sDescricaoLogradouro
   */
  public function setDescricaoLogradouro($sDescricaoLogradouro) {
    $this->sDescricaoLogradouro = $sDescricaoLogradouro;
  }
  
  /**
   * Define o número do logradouro
   * @param integer $iNumeroLogradouro
   */
  public function setNumeroLogradouro($iNumeroLogradouro) {
    $this->iNumeroLogradouro = $iNumeroLogradouro;
  }
  
  /**
   * Define o complemento do logradouro
   * @param string $sComplemento
   */
  public function setComplemento($sComplemento) {
    $this->sComplemento = $sComplemento;
  }
  
  /**
   * Define o nome do bairro do cgm
   * @param string $sDescricaoBairro
   */
  public function setDescricaoBairro($sDescricaoBairro) {
    $this->sDescricaoBairro = $sDescricaoBairro;
  }
  
  /**
   * Define o UF do cgm
   * @param string $sUf
   */
  public function setUf($sUf) {
    $this->sUf = $sUf;
  }
  
  /**
   * Define o e-mail para o cgm
   * @param string $sEmail
   */
  public function setEmail($sEmail) {
    $this->sEmail = $sEmail;
  }
  
  /**
   * define o telefone para o cgm
   * @param string $sTelefone
   */
  public function setTelefone($sTelefone) {
    $this->sTelefone = $sTelefone;
  }
  
  /**
   * Define o celular para o cgm
   * @param string $sCelular
   */
  public function setCelular($sCelular) {
    $this->sCelular = $sCelular;
  }

  /**
   * Define o cep para o cgm
   * @param string $sCep
   */
  public function setCep($sCep) {
    $this->sCep = $sCep;
  }
  
  /**
   * Salva os dados no cgm
   * @throws BusinessException
   * @return Object
   */
  public function salvar() {

    /**
     * Verifica se foi infomado cpf ou cnpj
     */
    if (empty($this->sCpf) && empty($this->sCnpj)) {
      throw new BusinessException('CPF ou CNPJ devem ser informado.');
    }

    /**
     * Verifica qual tipo de cgm vai ser instânciado e define o parâmetro
     */
    if (!empty($this->sCpf)) {
      $iTipo = 1;
      $sCnpjCpf = $this->sCpf;
    } else {
      $iTipo = 2;
      $sCnpjCpf = $this->sCnpj;
    }

    /**
     * Verifica se o Cpf ou cnpj informado já esta cadastrado no cgm,
     * Se sim, retorna um stdClas com o status e o numero do CGM deste cpf/cnpj
     */
    $oCgm = CgmFactory::getInstanceByCnpjCpf($sCnpjCpf);
    
    if ( $oCgm ) {
      
      $oRetorno = new stdClass();
      $oRetorno->codigo_cgm = $oCgm->getCodigo();
      $oRetorno->status     = "CGM ja cadastrado";
      
      return $oRetorno;
    }

    /**
     * Retorna uma Instancia de Pessoa Juridica ou Fisica, de acordo com o tipo(Cpf/cnpj) informado
     * @var CgmFactory
     */
    $oCgm = CgmFactory::getInstanceByType($iTipo);
    
    if (!empty($this->sCpf)) {
      $oCgm->setCpf($this->sCpf);
    } else {
      $oCgm->setCnpj($this->sCnpj);
    }
    
    db_inicio_transacao();
    
    /**
     * Verifica se o endereço pertence ou não ao municipio
     */
    if ( $this->lEnderecoMunicipio ) {
      
      $oCgm->setLogradouro($this->getDescricaoLogradouro());
      $oCgm->setBairro($this->getDescricaoBairro());
    } else {
      
      $oCgm->setLogradouro($this->sDescricaoLogradouro);
      $oCgm->setBairro($this->sDescricaoBairro);
    }
    
    $oCgm->setMunicipio($this->getNomeMunicipio()->db72_descricao);
    $oCgm->setCep($this->sCep);
    $oCgm->setUf($this->sUf);
    
    $oCgm->setNome($this->sNome);
    $oCgm->setNumero($this->iNumeroLogradouro);
    $oCgm->setComplemento($this->sComplemento);
    $oCgm->setEmail($this->sEmail);
    $oCgm->setTelefone($this->sTelefone);
    $oCgm->setCelular($this->sCelular);
    
    /**
     * Salva os dados na tabela cgm
     */
    $oCgm->save();
    
    db_fim_transacao(false);
    
    /**
     * Retorna o status da transação e o Numero do CGM cadastrado.
     */
    $oRetorno = new stdClass();
    
    $oRetorno->codigo_cgm = $oCgm->getCodigo();
    
    $oRetorno->status     = "CGM cadastrado com sucesso";
    
    return $oRetorno;
    
  }
  
  /**
   * Realiza a busca do nome do municipio a partir do Codigo da cidade informada
   * @throws BusinessException
   * @return string descrição do municipio.
   */
  private function getNomeMunicipio() {
    
    $oDaoMunicipio = db_utils::getDao('cadendermunicipiosistema');
    
    $sWhere  = "db125_codigosistema = '{$this->iCodigoCidade}'";
    $sWhere .= " AND  db125_db_sistemaexterno = 4";
    
    $sSqlMunicipio        = $oDaoMunicipio->sql_query(null, 'db72_sequencial, db72_descricao', null, $sWhere);
    $rsDescricaoMunicipio = $oDaoMunicipio->sql_record($sSqlMunicipio);

    
    if ($oDaoMunicipio->numrows == 0) {
      throw new BusinessException("Cidade com o código do IBGE {$this->iCodigoCidade} não encontrada no sistema.");
    }
    
    return db_utils::fieldsMemory($rsDescricaoMunicipio, 0);
  }
  
  /**
   * Realiza a busca da descrição do Logradouro apartir do codigo do logradouro informado.
   * @throws DBException
   * @return nome do logradouro
   */
  private function getDescricaoLogradouro() {
    
    $oDaoRuas = db_utils::getDao('ruas');
    
    $sSqlLogradouro = $oDaoRuas->sql_query_file($this->iCodigoLogradouro, 'j14_nome');
    
    $rsDescricaoLogradouro = $oDaoRuas->sql_record($sSqlLogradouro);
    
    if ($oDaoRuas->numrows == 0) {
      throw new DBException("Logradouro com o código {$this->iCodigoLogradouro} não encontrado no sistema");
    }
    
    return db_utils::fieldsMemory($rsDescricaoLogradouro, 0)->j14_nome;
  }
  
  /**
   * Realiza a busca do nome do bairro a partir do codigo do bairro
   * @throws DBException
   * @return nome do bairro
   */
  public function getDescricaoBairro() {
    
    $oDaoBairro = db_utils::getDao('bairro');
    $sSqlBairro = $oDaoBairro->sql_query_file($this->iCodigoBairro, 'j13_descr');
    $rsBairro   = $oDaoBairro->sql_record($sSqlBairro);

    if ($oDaoBairro->numrows == 0) {
      throw new DBException("Bairro com o código {$this->iCodigoBairro} não encontrado no sistema");
    }
    
    return db_utils::fieldsMemory($rsBairro, 0)->j13_descr;
  }
  
  public function getDadosCgmByCnpjCpf($sCnpjCpf) {
    
    if (empty($sCnpjCpf)) {
      throw new Exception("CPF/CNPJ não informado.");
    }
    
    $oCgm = CgmFactory::getInstanceByCnpjCpf($sCnpjCpf);
    
    if (!empty($oCgm)) {
      return $this->prencherDadosCgm($oCgm);
    }
    return null;
  }
  
  public function getDadosCgmByCgm($iCodigoCgm) {
  
    if (empty($iCodigoCgm)) {
      throw new Exception("Código cgm não informado.");
    }
  
    $oCgm = CgmFactory::getInstanceByCgm($iCodigoCgm);
  
    if (!empty($oCgm)) {
      return $this->prencherDadosCgm($oCgm);
    }
  
    return null;
  }

}
