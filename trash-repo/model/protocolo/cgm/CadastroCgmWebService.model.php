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

require_once 'model/CgmFactory.model.php';
require_once 'model/endereco.model.php';
require_once 'libs/db_utils.php';
require_once 'dbforms/db_funcoes.php';

/**
 * Model para cadastro de cgm do sistemas webservice
 * @author Alberto Ferri Neto <alberto@dbseller.com.br>
 * @author Renan Melo         <renan.melo@dbseller.com.br>
 *
 */
class CadastroCgmWebService {
  
  /**
   * Nome do cgm
   * @var string
   */
  private $sNome;
  
  /**
   * Se o endere�o informado � do municipio
   * @var boolean
   */
  private $lEnderecoMunicipio;
  
  /**
   * C�digo do logradouro no e-cidade
   * @var integer
   */
  private $iCodigoLogradouro;
  
  /**
   * Nome do Logradouro
   * @var string
   */
  private $sDescricaoLogradouro;
  
  /**
   * N�mero do Logradouro
   * @var integer
   */
  private $iNumeroLogradouro;
  
  /**
   * Complemento do Logradouro
   * @var string
   */
  private $sComplemento;
  
  /**
   * C�digo do bairro no e-cidade
   * @var unknown_type
   */
  private $iCodigoBairro;
  
  /**
   * Nome do bairro
   * @var string
   */
  private $sDescricaoBairro;
    
  /**
   * C�digo da cidade no e-cidade
   * @var  integer
   */
  private $iCodigoCidade;
  
  /**
   * Nome da UF
   * @var string
   */
  private $sUf;
  
  /**
   * C�digo do pa�s no e-cidade
   * @var integer
   */
  private $iCodigoPais;
  
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
   * N�mero de cpf do cgm
   * @var string
   */
  private $sCpf;
  
  /**
   * N�mero do CNPJ do cgm
   * @var string
   */
  private $sCnpj;
  
  /**
   * N�mero CEP
   * @var integer
   */
  private $sCep;
  
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
   * Define se o endere�o � do munic�pio
   * @param boolean $lEnderecoMunicipio
   */
  public function setEnderecoMunicipio($lEnderecoMunicipio) {
    $this->lEnderecoMunicipio = $lEnderecoMunicipio;
  }
  
  /**
   * Define o c�digo do logradouro do cgm
   * @param unknown_type $iCodigoLogradouro
   */
  public function setCodigoLogradouro($iCodigoLogradouro = null) {
    $this->iCodigoLogradouro = $iCodigoLogradouro;
  }
  
  /**
   * Define a descri��o do logradouro do cgm
   * @param string $sDescricaoLogradouro
   */
  public function setDescricaoLogradouro($sDescricaoLogradouro) {
    $this->sDescricaoLogradouro = $sDescricaoLogradouro;
  }
  
  /**
   * Define o n�mero do logradouro
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
   * Define o c�digo do bairro do cgm
   * @param integer $iCodigoBairro
   */
  public function setCodigoBairro($iCodigoBairro) {
    $this->iCodigoBairro = $iCodigoBairro;
  }
  
  /**
   * Define o nome do bairro do cgm
   * @param string $sDescricaoBairro
   */
  public function setDescricaoBairro($sDescricaoBairro) {
    $this->sDescricaoBairro = $sDescricaoBairro;
  }
      
  /**
   * Define o codigo da cidade do cgm
   * @param integer $iCodigoCidade
   */
  public function setCodigoCidade($iCodigoCidade) {
    $this->iCodigoCidade = $iCodigoCidade;
  }
  
  /**
   * Define o UF do cgm
   * @param string $sUf
   */
  public function setUf($sUf) {
    $this->sUf = $sUf;
  }
  
  /**
   * Define o c�digo do pais para o cgm
   * @param integer $iCodigoPais
   */
  public function setCodigoPais($iCodigoPais) {
    $this->iCodigoPais = $iCodigoPais;
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
     * Verifica qual tipo de cgm vai ser inst�nciado e define o par�metro
     */
    if (!empty($this->sCpf)) {
      $iTipo = 1;
      $sCnpjCpf = $this->sCpf;
    } else {
      $iTipo = 2;
      $sCnpjCpf = $this->sCnpj;
    }

    /**
     * Verifica se o Cpf ou cnpj informado j� esta cadastrado no cgm,
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
     * Verifica se o endere�o pertence ou n�o ao municipio
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
     * Retorna o status da transa��o e o Numero do CGM cadastrado.
     */
    $oRetorno = new stdClass();
    
    $oRetorno->codigo_cgm = $oCgm->getCodigo();
    
    $oRetorno->status     = "CGM cadastrado com sucesso";
    
    return $oRetorno;
    
  }
  
  /**
   * Realiza a busca do nome do municipio a partir do Codigo da cidade informada
   * @throws BusinessException
   * @return string descri��o do municipio.
   */
  private function getNomeMunicipio() {
    
    $oDaoMunicipio = db_utils::getDao('cadendermunicipiosistema');
    
    $sWhere  = "db125_codigosistema = '{$this->iCodigoCidade}'";
    $sWhere .= " AND  db125_db_sistemaexterno = 4";
    
    $sSqlMunicipio        = $oDaoMunicipio->sql_query(null, 'db72_sequencial, db72_descricao', null, $sWhere);
    $rsDescricaoMunicipio = $oDaoMunicipio->sql_record($sSqlMunicipio);

    
    if ($oDaoMunicipio->numrows == 0) {
      throw new BusinessException("Cidade com o c�digo do IBGE {$this->iCodigoCidade} n�o encontrada no sistema.");
    }
    
    return db_utils::fieldsMemory($rsDescricaoMunicipio, 0);
  }
  
  /**
   * Realiza a busca da descri��o do Logradouro apartir do codigo do logradouro informado.
   * @throws DBException
   * @return nome do logradouro
   */
  private function getDescricaoLogradouro() {
    
    $oDaoRuas = db_utils::getDao('ruas');
    
    $sSqlLogradouro = $oDaoRuas->sql_query_file($this->iCodigoLogradouro, 'j14_nome');
    
    $rsDescricaoLogradouro = $oDaoRuas->sql_record($sSqlLogradouro);
    
    if ($oDaoRuas->numrows == 0) {
      throw new DBException("Logradouro com o c�digo {$this->iCodigoLogradouro} n�o encontrado no sistema");
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
      throw new DBException("Bairro com o c�digo {$this->iCodigoBairro} n�o encontrado no sistema");
    }
    
    return db_utils::fieldsMemory($rsBairro, 0)->j13_descr;
  }

  /**
   * Tornar escritorio contabil
   *
   * @param integer $iNumCgm
   * @access public
   * @exception  - erro ao vincular cgm
   * @return boolean
   */
  public function tornarEscritorioContabil($iNumCgm) {

    require_once 'model/issqn/EscritorioContabil.model.php';
    db_inicio_transacao();

    try {

      $oDaoCadEscrito = db_utils::getDao('cadescrito');
      $sSqlCadEscrito = $oDaoCadEscrito->sql_query_file($iNumCgm, 'q86_numcgm');
      $rsCadEscrito   = db_query($sSqlCadEscrito);

      if ( !$rsCadEscrito ) {
        throw new Exception("Erro ao Buscar validar existencia de Escrit�rio cont�bil". pg_last_error());
      }

      /**
       * Se j� existe vincula��o do CGM com escrit�rio
       * Conclui execu��o
       */
      if ( pg_num_rows($rsCadEscrito) > 0 ) {
        return false;
      }

      $oEscritorio = new EscritorioContabil($iNumCgm);
      $oEscritorio->salvar();

      db_fim_transacao();

    } catch(Exception $oException) {

      db_fim_transacao(true);
      throw new Exception('Erro ao vincular CGM: '. $iNumCgm .', ' . $oException->getMessage());
    }

    return true;
  }
  
  /**
   * Torna um CGM no tipo Grafica
   * @param iCgm n�mero do CGM
   */
  public function tornarGrafica($iCgm) {

    require_once 'model/fiscal/Grafica.model.php';
    db_inicio_transacao();

    try {

      $oDaoGraficas = db_utils::getDao('graficas');
      $sSqlGraficas = $oDaoGraficas->sql_query_file($iCgm, 'y20_grafica');
      $rsGraficas   = db_query($sSqlGraficas);
   
      if ( !$rsGraficas ) {
        throw new Exception("Erro ao Buscar Gr�fica: ". pg_last_error());
      }
   
      /**
       * Se j� existe vincula��o do CGM com Gr�fica
       * Conclui execu��o
       */
      if ( pg_num_rows($rsGraficas) > 0 ) {
        return false;
      }

      $oGrafica = new Grafica( $iCgm );
      $oGrafica->salvar();

      db_fim_transacao();

    } catch (Exception $oException) {

      db_fim_transacao(true);
      throw new Exception("Erro ao vincular o CGM: {$iCgm}, {$oException->getMessage()} ");
    }

    return true;
  }
  
  
  public function getDadosCgmByCnpjCpf($sCnpjCpf) {
    
    if (empty($sCnpjCpf)) {
      throw new Exception("CPF/CNPJ n�o informado.");
    }
    
    $oCgm = CgmFactory::getInstanceByCnpjCpf($sCnpjCpf);
    
    if (!empty($oCgm)) {
      return $this->prencherDadosCgm($oCgm);
    }
    return null;
  }
  
  public function getDadosCgmByCgm($iCodigoCgm) {
  
    if (empty($iCodigoCgm)) {
      throw new Exception("C�digo cgm n�o informado.");
    }
  
    $oCgm = CgmFactory::getInstanceByCgm($iCodigoCgm);
  
    if (!empty($oCgm)) {
      return $this->prencherDadosCgm($oCgm);
    }
  
    return null;
  }

  /**
   * Exclui os dados do CGm
   * @param integer $iCodigoCgm codigo do CGM
   */
  function excluir($iCodigoCgm) {
    
    $oCgm = CgmFactory::getInstanceByCgm($iCodigoCgm);
    
    if (!empty($oCgm)) {
      $oCgm->exclui();
    }
    
    return true;
  }
  
  /**
   * Prenche os dados do Cgm
   * @param CgmBase $oCgm Instancia do CGM
   */
  protected function prencherDadosCgm(CgmBase $oCgm) {
    
    $oRetorno = new stdClass();
    
    $oRetorno->iCodigoCgm   = $oCgm->getCodigo();
    $oRetorno->sNome        = utf8_encode($oCgm->getNome());
    $oRetorno->lJuridico    = $oCgm->isJuridico();
    
    $oRetorno->sNumero      = utf8_encode($oCgm->getNumero());
    $oRetorno->sComplemento = utf8_encode($oCgm->getComplemento());
    $oRetorno->sMunicipio   = utf8_encode($oCgm->getMunicipio());
    $oRetorno->sUf          = utf8_encode($oCgm->getUf());
    $oRetorno->sEmail       = utf8_encode($oCgm->getEmail());
    $oRetorno->sCep         = utf8_encode($oCgm->getCep());
    $oRetorno->sLogradouro  = utf8_encode($oCgm->getLogradouro());
    $oRetorno->sBairro      = utf8_encode($oCgm->getBairro());
    $oRetorno->sTelefone    = utf8_encode($oCgm->getTelefone());
    
    if ($oCgm->isFisico()) {
      $oRetorno->iCpf = $oCgm->getCpf();
    }
    
    if ($oCgm->isJuridico()) {
      $oRetorno->iCnpj = $oCgm->getCnpj();
    }
    return $oRetorno;
  }
}