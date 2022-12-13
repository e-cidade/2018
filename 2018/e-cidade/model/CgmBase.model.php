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

/**
 * model para cgm
 *@package Protocolo
 */
abstract class CgmBase {

  /**
   * Código do cgm
   *
   * @var integer
   */
  protected $iCodigo;

  /**
   * Nome do CGM
   *
   * @var string
   */
  protected $sNome;

  /**
   * Nome Completo do CGM
   *
   * @var string
   */
  protected $sNomeCompleto;

  /**
   * Logradouro do Endereço Principal
   *
   * @var string
   */
  protected $sLogradouro;

  /**
   * Número do Endereço Principal
   *
   * @var string
   */
  protected $sNumero;

  /**
   * Complemento do Endereço Principal
   *
   * @var string
   */
  protected $sComplemento;

  /**
   * Bairro do Endereço Principal
   *
   * @var string
   */
  protected $sBairro;

  /**
   * Município do Endereço Principal
   *
   * @var string
   */
  protected $sMunicipio;

  /**
   * UF do Endereço Principal
   *
   * @var unknown_type
   */
  protected $sUf;

  /**
   * CEP do Endereço Principal
   *
   * @var string
   */
  protected $sCep;

  /**
   * Caixa Postal
   *
   * @var string
   */
  protected $sCaixaPostal;

  /**
   * Telefone do CGM
   *
   * @var string
   */
  protected $sTelefone;

  /**
   * Inscrição Estadual
   *
   * @var string
   */
  protected $sInscricaoEstadual;

  /**
   * Telefone Celular do CGM
   *
   * @var string
   */
  protected $sCelular;

  /**
   * Email do CGM
   *
   * @var strinng
   */
  protected $sEmail;

  /**
   * Logradouro Comercial
   *
   * @var string
   */
  protected $sLogradouroComercial;

  /**
   * Número do Endereço Comercial
   *
   * @var string
   */
  protected $sNumeroComercial;

  /**
   * Complemento do Endereço Comercial;
   *
   * @var string
   */
  protected $sComplementoComercial;

  /**
   * Bairro do Endereço Comercial
   *
   * @var string
   */
  protected $sBairroComercial;

  /**
   * Município do EndereçoComercial;
   *
   * @var unknown_type
   */
  protected $sMunicipioComercial;

  /**
   * UF do Endereço Comercial;
   *
   * @var string
   */
  protected $sUfComercial;

  /**
   * CEP do Endereço Comercial
   *
   * @var string
   */
  protected $sCepComercial;

  /**
   * Caixa Postal do Endereço Comercial
   *
   * @var string
   */
  protected $sCaixaPostalComercial;

  /**
   * Telefone Comercial
   *
   * @var string
   */
  protected $sTelefoneComercial;

  /**
   * Telefone Celular Comercial
   *
   * @var string
   */
  protected $sCelularComercial;

  /**
   * Email Comercial
   *
   * @var string
   */
  protected $sEmailComercial;

  /**
   * Fax
   *
   * @var string
   */
  protected $sFax;

  /**
   * Flag que define se CGM é Juridico
   *
   * @var boolean
   */
  private $lJuridico;

  /**
   * Objeto contendo tipo empresa
   *
   * @var object
   */

  private $oTipoEmpresa;

  /**
   * Tipo Empresa
   *
   * @var int
   */
  protected $iTipoEmpresa;

  /**
   * Endereco
   *
   * @var int
   */
  protected $iEnderecoPrimario;

  /**
   * Endereco
   *
   * @var int
   */
  protected $iEnderecoSecundario;

  /**
   * Obervações
   *
   * @var string
   */
  protected $sObs;

  /**
   * Obervações
   * @var Date
   */
  protected $dCadastro;

  /**
   * @var boolean
   */
  private $lCgmMunicipio;

  function __construct( $iCgm = null ) {

    if ( !empty($iCgm) ) {

      $oDaoCgm = db_utils::getDao("cgm");
      $sSqlCgm = $oDaoCgm->sql_query_file($iCgm);
      $rsCgm   = $oDaoCgm->sql_record($sSqlCgm);

      if ($oDaoCgm->numrows > 0) {

        $oDadosCgm = db_utils::fieldsMemory($rsCgm,0);

        $this->setCodigo              ($oDadosCgm->z01_numcgm);
        $this->setNome                ($oDadosCgm->z01_nome);
        $this->setNomeCompleto        ($oDadosCgm->z01_nomecomple);
        $this->setLogradouro          ($oDadosCgm->z01_ender);
        $this->setNumero              ($oDadosCgm->z01_numero);
        $this->setComplemento         ($oDadosCgm->z01_compl);
        $this->setBairro              ($oDadosCgm->z01_bairro);
        $this->setMunicipio           ($oDadosCgm->z01_munic);
        $this->setUf                  ($oDadosCgm->z01_uf);
        $this->setCep                 ($oDadosCgm->z01_cep);
        $this->setCaixaPostal         ($oDadosCgm->z01_cxpostal);
        $this->setTelefone            ($oDadosCgm->z01_telef);
        $this->setInscricaoEstadual   ($oDadosCgm->z01_incest);
        $this->setCelular             ($oDadosCgm->z01_telcel);
        $this->setEmail               ($oDadosCgm->z01_email);
        $this->setLogradouroComercial ($oDadosCgm->z01_endcon);
        $this->setNumeroComercial     ($oDadosCgm->z01_numcon);
        $this->setComplementoComercial($oDadosCgm->z01_comcon);
        $this->setBairroComercial     ($oDadosCgm->z01_baicon);
        $this->setMunicipioComercial  ($oDadosCgm->z01_muncon);
        $this->setUfComercial         ($oDadosCgm->z01_ufcon);
        $this->setCepComercial        ($oDadosCgm->z01_cepcon);
        $this->setCaixaPostalComercial($oDadosCgm->z01_cxposcon);
        $this->setTelefoneComercial   ($oDadosCgm->z01_telcon);
        $this->setCelularComercial    ($oDadosCgm->z01_celcon);
        $this->setEmailComercial      ($oDadosCgm->z01_emailc);
        $this->setFax                 ($oDadosCgm->z01_fax);
        $this->setObs                 ($oDadosCgm->z01_obs);
        $this->setCadastro            ($oDadosCgm->z01_cadast);

        if ( strlen(trim($oDadosCgm->z01_cgccpf)) == 14 ) {
          $this->lJuridico = true;
        } else {
          $this->lJuridico = false;
        }
        /**
         * verifica o endereço primario do CGM
         */
        $oDaoCGMEndereco       = db_utils::getDao("cgmendereco");
        $sSqlEnderecoPrimario  = $oDaoCGMEndereco->sql_query_file(null,
                                                                  "*",
                                                                  null,
                                                                  "z07_numcgm = {$this->getCodigo()}
                                                                  and z07_tipo = 'P'"
                                                                  );
        $rsEnderecoPrimario = $oDaoCGMEndereco->sql_record($sSqlEnderecoPrimario);
        if ($oDaoCGMEndereco->numrows == 1) {
          $this->iEnderecoPrimario = db_utils::fieldsMemory($rsEnderecoPrimario, 0)->z07_endereco;
        }
      }
    }

  }

  /**
   * return boolean
   *
   */
  public function getCgmMunicipio() {

    $lCgmMunicipio = false;
    $iNumCgm = $this->getCodigo();

    if ($iNumCgm != "" ) {

      $oDaoCgmMunicipio     = db_utils::getDao("cgm");
      $sQueryCgmMunicipio   = $oDaoCgmMunicipio->sql_query_cgmmunicipio($iNumCgm);
      $rsQueryCgmMunicipio  = $oDaoCgmMunicipio->sql_record($sQueryCgmMunicipio);

      if ($rsQueryCgmMunicipio !== false) {
         $lCgmMunicipio = true;
      }
    }
    return $lCgmMunicipio;
  }


  /**
   * @return integer
   */
  public function getCodigo () {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigo ($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @param integer $iEndereco
   */
  public function setEnderecoPrimario ($iEndereco) {
    $this->iEnderecoPrimario = $iEndereco;
  }

  /**
   * @return integer $iEndereco
   */
  public function getEnderecoPrimario () {
    return  $this->iEnderecoPrimario;
  }

  /**
   * @param integer $iEndereco
   */
  public function setEnderecoSecundario ($iEndereco) {
    $this->iEnderecoSecundario = $iEndereco;
  }

  /**
   * @return integer $iEndereco
   */
  public function getEnderecoSecundario () {
    return  $this->iEnderecoSecundario;
  }


  /**
   * @return string
   */
  public function getNome () {
    return $this->sNome;
  }

  /**
   * @param string $sNome
   */
  public function setNome ($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * @return string
   */
  public function getBairro () {
    return $this->sBairro;
  }

/**
   * @param string $sBairro
   */
  public function setBairro ($sBairro) {
    $this->sBairro = $sBairro;
  }

/**
   * @return string
   */
  public function getBairroComercial () { return $this->sBairroComercial; }

/**
   * @param string $sBairroComercial
   */
  public function setBairroComercial ($sBairroComercial) { $this->sBairroComercial = $sBairroComercial; }

/**
   * @return string
   */
  public function getCaixaPostal () { return $this->sCaixaPostal; }

/**
   * @param string $sCaixaPostal
   */
  public function setCaixaPostal ($sCaixaPostal) { $this->sCaixaPostal = $sCaixaPostal; }

/**
   * @return string
   */
  public function getCaixaPostalComercial () { return $this->sCaixaPostalComercial; }

/**
   * @param string $sCaixaPostalComercial
   */
  public function setCaixaPostalComercial ($sCaixaPostalComercial) { $this->sCaixaPostalComercial = $sCaixaPostalComercial; }

/**
   * @return string
   */
  public function getCelular () { return $this->sCelular; }

/**
   * @param string $sCelular
   */
  public function setCelular ($sCelular) { $this->sCelular = $sCelular; }

/**
   * @return string
   */
  public function getCelularComercial () { return $this->sCelularComercial; }

/**
   * @param string $sCelularComercial
   */
  public function setCelularComercial ($sCelularComercial) { $this->sCelularComercial = $sCelularComercial; }

/**
   * @return string
   */
  public function getCep () { return $this->sCep; }

/**
   * @param string $sCep
   */
  public function setCep ($sCep) { $this->sCep = $sCep; }

/**
   * @return string
   */
  public function getCepComercial () { return $this->sCepComercial; }

/**
   * @param string $sCepComercial
   */
  public function setCepComercial ($sCepComercial) { $this->sCepComercial = $sCepComercial; }

/**
   * @return string
   */
  public function getComplemento () { return $this->sComplemento; }

/**
   * @param string $sComplemento
   */
  public function setComplemento ($sComplemento) { $this->sComplemento = $sComplemento; }

/**
   * @return string
   */
  public function getComplementoComercial () { return $this->sComplementoComercial; }

/**
   * @param string $sComplementoComercial
   */
  public function setComplementoComercial ($sComplementoComercial) { $this->sComplementoComercial = $sComplementoComercial; }

/**
   * @return strinng
   */
  public function getEmail () {
    return $this->sEmail;
  }

/**
   * @param strinng $sEmail
   */
  public function setEmail ($sEmail) {
    $this->sEmail = $sEmail;
  }

/**
   * @return string
   */
  public function getEmailComercial () {
    return $this->sEmailComercial;
  }

/**
   * @param string $sEmailComercial
   */
  public function setEmailComercial ($sEmailComercial) {
    $this->sEmailComercial = $sEmailComercial;
  }

/**
   * @return string
   */
  public function getFax () {
    return $this->sFax;
  }

/**
   * @param string $sFax
   */
  public function setFax ($sFax) {
    $this->sFax = $sFax;
  }


/**
   * @return string
   */
  public function getObs () {
    return $this->sObs;
  }

  /**
   * @param string $sObs
   */
  public function setObs ($sObs) {
    $this->sObs = $sObs;
  }

  /**
   * @param Date $dCadastro
   */
  public function setCadastro($dCadastro) {
    $this->dCadastro = $dCadastro;
  }

  /**
   * @return date do cadastro
   */
  public function getCadastro() {
    return $this->dCadastro;
  }


/**
   * @return string
   */
  public function getInscricaoEstadual () {
    return $this->sInscricaoEstadual;
  }

/**
   * @param string $sInscricaoEstadual
   */
  public function setInscricaoEstadual ($sInscricaoEstadual) {
    $this->sInscricaoEstadual = $sInscricaoEstadual;
  }

/**
   * @return string
   */
  public function getLogradouro () {
    return $this->sLogradouro;
  }

/**
   * @param string $sLogradouro
   */
  public function setLogradouro ($sLogradouro) {
    $this->sLogradouro = $sLogradouro;
  }

/**
   * @return string
   */
  public function getLogradouroComercial () {
    return $this->sLogradouroComercial;
  }

/**
   * @param string $sLogradouroComercial
   */
  public function setLogradouroComercial ($sLogradouroComercial) {
    $this->sLogradouroComercial = $sLogradouroComercial;
  }

/**
   * @return string
   */
  public function getMunicipio () {
    return $this->sMunicipio;
  }

/**
   * @param string $sMunicipio
   */
  public function setMunicipio ($sMunicipio) {
    $this->sMunicipio = $sMunicipio;
  }

/**
   * @return unknown_type
   */
  public function getMunicipioComercial () {
    return $this->sMunicipioComercial;
  }

/**
   * @param unknown_type $sMunicipioComercial
   */
  public function setMunicipioComercial ($sMunicipioComercial) {
    $this->sMunicipioComercial = $sMunicipioComercial;
  }

/**
   * @return string
   */
  public function getNomeCompleto () {
    return $this->sNomeCompleto;
  }

/**
   * @param string $sNomeCompleto
   */
  public function setNomeCompleto ($sNomeCompleto) {
    $this->sNomeCompleto = $sNomeCompleto;
  }

/**
   * @return string
   */
  public function getNumero () {
    return $this->sNumero;
  }

/**
   * @param string $sNumero
   */
  public function setNumero ($sNumero) {
    $this->sNumero = $sNumero;
  }

/**
   * @return string
   */
  public function getNumeroComercial () {
    return $this->sNumeroComercial;
  }

/**
   * @param string $sNumeroComercial
   */
  public function setNumeroComercial ($sNumeroComercial) {
    $this->sNumeroComercial = $sNumeroComercial;
  }

/**
   * @return string
   */
  public function getTelefone () {
    return $this->sTelefone;
  }

/**
   * @param string $sTelefone
   */
  public function setTelefone ($sTelefone) {
    $this->sTelefone = $sTelefone;
  }

/**
   * @return string
   */
  public function getTelefoneComercial () {
    return $this->sTelefoneComercial;
  }

/**
   * @param string $sTelefoneComercial
   */
  public function setTelefoneComercial ($sTelefoneComercial) {
    $this->sTelefoneComercial = $sTelefoneComercial;
  }

/**
   * @return unknown_type
   */
  public function getUf () {
    return $this->sUf;
  }

/**
   * @param unknown_type $sUf
   */
  public function setUf ($sUf) {
    $this->sUf = $sUf;
  }

/**
   * @return string
   */
  public function getUfComercial () {
    return $this->sUfComercial;
  }

/**
   * @param string $sUfComercial
   */
  public function setUfComercial ($sUfComercial) {
    $this->sUfComercial = $sUfComercial;
  }

  /**
   * Método verifica se CGM é jurídico
   *
   * @return boolean
   */
  public function isJuridico(){
    return $this->lJuridico;
  }

  /**
   * Método verifica se CGM é de pessoal física
   *
   * @return boolean
   */
  public function isFisico(){

    if ( $this->lJuridico ){
      return false;
    } else {
      return true;
    }

  }

  public function setTipoEmpresa($iTipoEmpresa){
    $this->iTipoEmpresa = $iTipoEmpresa;
  }

  public function getCodigoTipoEmpresa() {
    return $this->iTipoEmpresa;
  }

  public function getTipoEmpresa() {
    $oRetorno  = false;
    $iNumCgm   = $this->getCodigo();
    if ($iNumCgm != "") {

      $oDaoTipoEmpresa = db_utils::getDao("cgmtipoempresa");
      $sCampos = " z03_tipoempresa, db98_descricao ";
      $sWhere  = " z03_numcgm = ".$iNumCgm;
      $sQueryTipoEmpresa  = $oDaoTipoEmpresa->sql_query(null,$sCampos,null,$sWhere);
      $rsQueryTipoEmpresa = $oDaoTipoEmpresa->sql_record($sQueryTipoEmpresa);
      if ($rsQueryTipoEmpresa !== false) {
        $oRetorno = db_utils::getCollectionByRecord($rsQueryTipoEmpresa);
      }
    }

    return $oRetorno;
  }
  /**
   * Exclui os dados informados do CGM .
   */
  public function exclui() {

    $sMsgErro = 'Falha ao excluir CGM';

    /**
     * Verifica se existe alguma transação ativa
     */
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }

    $oDaoDBConfig    = db_utils::getDao('db_config');
    $oDaoCgm         = db_utils::getDao('cgm');
    $oDaoCgmBairro   = db_utils::getDao('db_cgmbairro');
    $oDaoCgmRuas     = db_utils::getDao('db_cgmruas');
    $oDaoRuas        = db_utils::getDao('ruas');
    $oDaoBairro      = db_utils::getDao('bairro');
    $oDaoCgmFisico   = db_utils::getDao('cgmfisico');
    $oDaoCgmCpf      = db_utils::getDao('db_cgmcpf');
    $oDaoCgmJurico   = db_utils::getDao('cgmjuridico');
    $oDaoCgmCgc      = db_utils::getDao('db_cgmcgc');
    $oDaoCgmEndereco = db_utils::getDao('cgmendereco');
    $oDaoTipoEmpresa = db_utils::getDao('cgmtipoempresa');

    // Excluindo Relacionamentos
    $sWhereCgmEndereco = "z07_numcgm = ".$this->getCodigo();
    $oDaoCgmEndereco->excluir(null, $sWhereCgmEndereco);
    if ($oDaoCgmEndereco->erro_status == '0') {
        throw new Exception($oDaoCgmEndereco->erro_msg);
    }

    $sWhereCgmTipoEmpresa = "z03_numcgm = ".$this->getCodigo();
    $oDaoTipoEmpresa->excluir(null, $sWhereCgmTipoEmpresa);
    if ($oDaoTipoEmpresa->erro_status == '0') {
        throw new Exception($oDaoTipoEmpresa->erro_msg);
    }

    $sWhereCgmBairro = "z01_numcgm = ".$this->getCodigo();
    $oDaoCgmBairro->excluir(null, $sWhereCgmBairro);
    if ($oDaoCgmBairro->erro_status == '0') {
        throw new Exception($oDaoCgmBairro->erro_msg);
    }

    $sWhereCgmRuas = "z01_numcgm = ".$this->getCodigo();
    $oDaoCgmRuas->excluir(null, $sWhereCgmRuas);
    if ($oDaoCgmRuas->erro_status == '0') {
        throw new Exception($oDaoCgmRuas->erro_msg);
    }

    /*Verifico se é jurídico ou físico para excluir das respectivas tabelas*/
    if ($this->isFisico()) {

      $sWhereCgmCpf = "z01_numcgm = ".$this->getCodigo();
      $oDaoCgmCpf->excluir(null, $sWhereCgmCpf);
      if ($oDaoCgmCpf->erro_status == '0') {
        throw new Exception($oDaoCgmCpf->erro_msg);
      }

      $oDaoCgmFisico->excluir(null, "z04_numcgm = {$this->getCodigo()}");
      if ($oDaoCgmFisico->erro_status == '0') {
        throw new Exception($oDaoCgmFisico->erro_msg);
      }

    } else {

      $sWhereCgmCgc = "z01_numcgm = ".$this->getCodigo();
      $oDaoCgmCgc->excluir(null, $sWhereCgmCgc);
      if ($oDaoCgmCgc->erro_status == '0') {
        throw new Exception($oDaoCgmCgc->erro_msg);
      }

      $sWhereCgmJurico = "z08_numcgm = ".$this->getCodigo();
      $oDaoCgmJurico->excluir(null, $sWhereCgmJurico);
      if ($oDaoCgmJurico->erro_status == '0') {
        throw new Exception($oDaoCgmJurico->erro_msg);
      }
    }

    $oDaoCgm->excluir($this->getCodigo());
    if ($oDaoCgm->erro_status == '0') {
        throw new Exception($oDaoCgm->erro_msg);
    }

  }


  /**
   * Salva os dados informados do CGM, caso o CGM já exista então
   * é alterado o registro apartir do código (numcgm) informado
   */
  public function save() {

    $sMsgErro = 'Falha ao salvar CGM';

    /**
     * Verifica se existe alguma transação ativa
     */
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }

    $oDaoDBConfig  = db_utils::getDao('db_config');
    $oDaoCgm       = db_utils::getDao('cgm');
    $oDaoCgmBairro = db_utils::getDao('db_cgmbairro');
    $oDaoCgmRuas   = db_utils::getDao('db_cgmruas');
    $oDaoRuas      = db_utils::getDao('ruas');
    $oDaoBairro    = db_utils::getDao('bairro');
    $oDaoCgmEndereco = db_utils::getDao('cgmendereco');
    /**
     *  Consulta o código do município
     */
    $sSqlConfig = $oDaoDBConfig->sql_query_file(db_getsession('DB_instit'),"munic");
    $rsConfig   = $oDaoDBConfig->sql_record($sSqlConfig);

    if ( $oDaoDBConfig->numrows > 0 ) {
      $sMunicipio = db_utils::fieldsMemory($rsConfig,0)->munic;
    } else {
      throw new Exception("{$sMsgErro}, {$oDaoDBConfig->erro_msg}");
    }


    /**
     * Inclui os registros na tabela CGM
     */
    $oDaoCgm->z01_nome       = addslashes($this->getNome());
    $oDaoCgm->z01_nomecomple = addslashes($this->getNomeCompleto());
    $oDaoCgm->z01_ender      = addslashes($this->getLogradouro());
    $oDaoCgm->z01_numero     = $this->getNumero();
    $oDaoCgm->z01_compl      = addslashes($this->getComplemento());
    $oDaoCgm->z01_bairro     = addslashes($this->getBairro());
    $oDaoCgm->z01_munic      = addslashes($this->getMunicipio());
    $oDaoCgm->z01_uf         = $this->getUf();
    $oDaoCgm->z01_cep        = $this->getCep();
    $oDaoCgm->z01_cxpostal   = $this->getCaixaPostal();
    $oDaoCgm->z01_telef      = $this->getTelefone();
    $oDaoCgm->z01_incest     = $this->getInscricaoEstadual();
    $oDaoCgm->z01_telcel     = $this->getCelular();
    $oDaoCgm->z01_email      = addslashes($this->getEmail());
    $oDaoCgm->z01_endcon     = addslashes($this->getLogradouroComercial());
    $oDaoCgm->z01_numcon     = $this->getNumeroComercial();
    $oDaoCgm->z01_comcon     = addslashes($this->getComplementoComercial());
    $oDaoCgm->z01_baicon     = addslashes($this->getBairroComercial());
    $oDaoCgm->z01_muncon     = addslashes($this->getMunicipioComercial());
    $oDaoCgm->z01_ufcon      = $this->getUfComercial();
    $oDaoCgm->z01_cepcon     = $this->getCepComercial();
    $oDaoCgm->z01_cxposcon   = $this->getCaixaPostalComercial();
    $oDaoCgm->z01_telcon     = $this->getTelefoneComercial();
    $oDaoCgm->z01_celcon     = $this->getCelularComercial();
    $oDaoCgm->z01_emailc     = addslashes($this->getEmailComercial());
    $oDaoCgm->z01_fax        = $this->getFax();
    $oDaoCgm->z01_login      = db_getsession('DB_id_usuario');
    $oDaoCgm->z01_cadast     = $this->getCadastro();
    $oDaoCgm->z01_obs        = $this->getObs();
    $oDaoCgm->z01_hora       = db_hora();

    //var_dump($oDaoCgm);

    if ( trim($this->getCodigo()) == '' ) {
      $oDaoCgm->incluir(null);
    } else {
      $oDaoCgm->z01_numcgm = $this->getCodigo();
      $oDaoCgm->alterarCgmBase($this->getCodigo());
    }

    if ( $oDaoCgm->erro_status == 0 ) {
      throw new Exception("{$sMsgErro}, {$oDaoCgm->erro_msg}");
    }

    /**
     *  Seta o valor da propriedade $iCodigo com o número do CGM gerado
     */
    $this->setCodigo($oDaoCgm->z01_numcgm);


    /**
     * Caso o CGM informado seja do município da instituição então é verificado se
     * exite a rua e bairro informado nos respectivos cadastros do sistema
     *
     */
    if ( trim($sMunicipio) == trim($this->getMunicipio())) {


      $rsEnderCGM = $oDaoCgm->sql_record($oDaoCgm->sql_query_ender($this->getCodigo()));
      $oEnderCGM  = db_utils::fieldsMemory($rsEnderCGM,0);
      $iCodRuaCgm    = trim($oEnderCGM->j14_codigo);
      $iCodBairroCgm = trim($oEnderCGM->j13_codi);

      /**
       * Consulta o código da rua apartir do nome, caso exista então é incluído um registro
       * na tabela db_cgmruas referenciando o cgm a rua do cadastro
       */
      $sWhereRuas      = " trim(j14_nome) = '".trim($this->getLogradouro())."'";
      $sSqlConsultaRua = $oDaoRuas->sql_query_file(null,"j14_codigo",null,$sWhereRuas);
      $rsConsultaRuas  = $oDaoRuas->sql_record($sSqlConsultaRua);

      if ( $oDaoRuas->numrows > 0 ) {

        $oDaoCgmRuas->z01_numcgm = $this->getCodigo();
        $oDaoCgmRuas->j14_codigo = db_utils::fieldsMemory($rsConsultaRuas,0)->j14_codigo;

        if ( $iCodRuaCgm != '' ) {
          $oDaoCgmRuas->alterar($this->getCodigo());
        } else {
          $oDaoCgmRuas->incluir($this->getCodigo());
        }

        if ( $oDaoCgmRuas->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}, {$oDaoCgmRuas->erro_msg}");
        }

      }

      /**
       * Consulta o código da bairro apartir do nome, caso exista então é incluído um registro
       * na tabela db_cgmbairro referenciando o cgm ao bairro do cadastro
       */
      $sWhereBairro       = " trim(j13_descr) = '".trim(addslashes($this->getBairro()))."'";
      $sSqlConsultaBairro = $oDaoBairro->sql_query_file(null,"j13_codi",null,$sWhereBairro);
      $rsConsultaBairro   = $oDaoBairro->sql_record($sSqlConsultaBairro);

      if ( $oDaoBairro->numrows > 0 ) {

        $oDaoCgmBairro->z01_numcgm = $this->getCodigo();
        $oDaoCgmBairro->j13_codi   = db_utils::fieldsMemory($rsConsultaBairro,0)->j13_codi;

        if ( $iCodBairroCgm != '' ) {
          $oDaoCgmBairro->alterar($this->getCodigo());
        } else {
          $oDaoCgmBairro->incluir($this->getCodigo());
        }

        if ( $oDaoCgmBairro->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}, {$oDaoCgmBairro->erro_msg}");
        }

      }

    }

   /**
    * inseri registro na cgmendereco {Primário}
    */
    if ($this->getEnderecoPrimario() != "" && $this->getEnderecoPrimario() != null) {

      $sWhereCgmEnderPrimario = " z07_numcgm = ".$this->getCodigo()." and z07_tipo='P' ";
      $sSqlConsultaCgmEnderPrimario = $oDaoCgmEndereco->sql_query_file(null,'z07_sequencial',null,$sWhereCgmEnderPrimario);
      $rsConsultaCgmEnderPrimario = $oDaoCgmEndereco->sql_record($sSqlConsultaCgmEnderPrimario);

      $oDaoCgmEndereco->z07_endereco = $this->getEnderecoPrimario();
      $oDaoCgmEndereco->z07_numcgm   = $this->getCodigo();
      $oDaoCgmEndereco->z07_tipo     = 'P';

      if ($oDaoCgmEndereco->numrows > 0) {

         $oDaoCgmEndereco->z07_sequencial = db_utils::fieldsMemory($rsConsultaCgmEnderPrimario,0)->z07_sequencial;
         $oDaoCgmEndereco->alterar($oDaoCgmEndereco->z07_sequencial);
      } else if ($this->getEnderecoPrimario() != "" && $this->getEnderecoPrimario() != null) {
        $oDaoCgmEndereco->z07_sequencial = "";
        $oDaoCgmEndereco->incluir(null);
      }

      if ( $oDaoCgmEndereco->erro_status == '0' ) {
         throw new Exception("{$oDaoCgmEndereco->erro_msg}");
      }
    }
    /**
    * inseri registro na cgmendereco {Secundário}
    */
    if ($this->getEnderecoSecundario() != "" && $this->getEnderecoSecundario() != null) {

      $sWhereCgmEnderSecundario = " z07_numcgm = ".$this->getCodigo()." and z07_tipo='S' ";
      $sSqlConsultaCgmEnderSecundario = $oDaoCgmEndereco->sql_query_file(null,'z07_sequencial',null,$sWhereCgmEnderSecundario);
      $rsConsultaCgmEnderSecundario = $oDaoCgmEndereco->sql_record($sSqlConsultaCgmEnderSecundario);

      if (trim($this->getEnderecoSecundario()) != ""){
        $oDaoCgmEndereco->z07_endereco = $this->getEnderecoSecundario();
        $oDaoCgmEndereco->z07_numcgm   = $this->getCodigo();
        $oDaoCgmEndereco->z07_tipo     = 'S';

        if ($oDaoCgmEndereco->numrows > 0) {

           $oDaoCgmEndereco->z07_sequencial = db_utils::fieldsMemory($rsConsultaCgmEnderSecundario,0)->z07_sequencial;
           $oDaoCgmEndereco->alterar($oDaoCgmEndereco->z07_sequencial);
        } else if ($this->getEnderecoSecundario() != "" && $this->getEnderecoSecundario() != null) {
          $oDaoCgmEndereco->z07_sequencial = "";
          $oDaoCgmEndereco->incluir(null);
        }

        if ( $oDaoCgmEndereco->erro_status == '0' ) {
           throw new Exception("{$oDaoCgmEndereco->erro_msg}");
        }
      }
    } else if ($this->getEnderecoSecundario() == "") {

      $sWhereCgmEnderSecundario = "z07_numcgm=".$this->getCodigo()." and z07_tipo = 'S' ";
      $oDaoCgmEndereco->excluir(null,$sWhereCgmEnderSecundario);
      if ( $oDaoCgmEndereco->erro_status == '0' ) {
        throw new Exception("{$oDaoCgmEndereco->erro_msg}");
      }
    }
  }

  /**
   * Adiciona uma foto ao cadastro do CGm
   *
   * @param string $sCaminhoArquivoFoto Caminho da imagem
   * @param boolean $lPrincipal foto princpal
   * @param boolean $lAtiva   foto ativa
   * @return cgmBase
   */
  public function adicionarFoto($sCaminhoArquivoFoto, $lPrincipal = true, $lAtiva = true) {

    global $conn;
    $oDaoCgmFoto                  = db_utils::getDao("cgmfoto");
    if (!file_exists($sCaminhoArquivoFoto)) {
      throw new Exception("Arquivo da foto não Encontrado.");
    }
    if ($lPrincipal) {

      $sSqlFotos   = $oDaoCgmFoto->sql_query_file(null,"*", "z16_sequencial","z16_numcgm ={$this->getCodigo()}");
      $rsFotos     = $oDaoCgmFoto->sql_record($sSqlFotos);
      $iTotalFotos = $oDaoCgmFoto->numrows;
      for ($iFoto = 0; $iFoto < $iTotalFotos; $iFoto++) {

        $oDadosFoto       = db_utils::fieldsMemory($rsFotos, $iFoto);
        $oDaoCgmFoto->z16_sequencial = $oDadosFoto->z16_sequencial;
        $oDaoCgmFoto->z16_principal  = "false";
        $oDaoCgmFoto->alterar($oDadosFoto->z16_sequencial);
        unset($oDadosFoto);
      }
    }
    $rFoto      = fopen($sCaminhoArquivoFoto, "rb");
    $rDadosFoto = fread($rFoto, filesize($sCaminhoArquivoFoto));
    fclose($rFoto);
    $oOidBanco   = pg_lo_create();
    $oDaoCgmFoto->z16_data        = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoCgmFoto->z16_id_usuario  = db_getsession("DB_id_usuario");
    $oDaoCgmFoto->z16_hora        = db_hora();
    $oDaoCgmFoto->z16_fotoativa   = $lAtiva?"true":"false";
    $oDaoCgmFoto->z16_principal   = $lPrincipal?"true":"false";
    $oDaoCgmFoto->z16_arquivofoto = $oOidBanco;
    $oDaoCgmFoto->z16_numcgm      = $this->getCodigo();
    $oDaoCgmFoto->incluir(null);
    if ($oDaoCgmFoto->erro_status == 0) {
      throw new Exception($oDaoCgmFoto->erro_msg);
    }
    $oObjetoBanco = pg_lo_open($conn, $oOidBanco, "w");
    pg_lo_write($oObjetoBanco, $rDadosFoto);
    pg_lo_close($oObjetoBanco);
    return $this;
  }

  /**
   * retorna as fotos cadastradas para o cgm
   *
   * @return array com as fotos
   */
  public function getFotos() {

     $aFotos      = array();
     $oDaoCgmFoto = db_utils::getDao("cgmfoto");
     $sSqlFotos   = $oDaoCgmFoto->sql_query_file(null,"*", "z16_sequencial","z16_numcgm ={$this->getCodigo()}");
     $rsFotos     = $oDaoCgmFoto->sql_record($sSqlFotos);
     $iTotalFotos = $oDaoCgmFoto->numrows;
     for ($iFoto = 0; $iFoto < $iTotalFotos; $iFoto++) {

       $oDadosFoto       = db_utils::fieldsMemory($rsFotos, $iFoto);
       $oFoto            = new stdClass();
       $oFoto->codigo    = $oDadosFoto->z16_sequencial;
       $oFoto->data      = $oDadosFoto->z16_data;
       $oFoto->hora      = $oDadosFoto->z16_hora;
       $oFoto->principal = $oDadosFoto->z16_principal == "t"?true:false;
       $oFoto->ativa     = $oDadosFoto->z16_fotoativa == "t"?true:false;
       $oFoto->oid       = $oDadosFoto->z16_arquivofoto;
       $aFotos[]         = $oFoto;
       unset($oDadosFoto);
     }
     return $aFotos;
  }

  public function excluirFoto($iFoto) {

    global $conn;

    $oDaoCgmFoto = db_utils::getDao("cgmfoto");

    $oDadosFoto  = $this->getInfoFoto($iFoto);
    /**
     * Exclui oid's do banco
     */
    pg_lo_unlink($conn,$oDadosFoto->oid);

    $oDaoCgmFoto->excluir($iFoto);

    if ($oDaoCgmFoto->erro_status == 0) {
      throw new Exception($oDaoCgmFoto->erro_msg);
    }
    return $this;
  }

  /**
   * Retorna a Foto principal do CGM
   */
  function getFotoPrincipal() {

    $iFoto       = null;
    $oDaoCgmFoto = db_utils::getDao("cgmfoto");
    $sSqlFotos   = $oDaoCgmFoto->sql_query_file(null,"*",
                                               "z16_sequencial",
                                               "z16_numcgm = {$this->getCodigo()}
                                               and z16_principal is true
                                               and z16_fotoativa is true");
    $rsFotos     = $oDaoCgmFoto->sql_record($sSqlFotos);
    if ($oDaoCgmFoto->numrows > 0) {
      $iFoto = db_utils::fieldsMemory($rsFotos, 0)->z16_arquivofoto;
    }
    return $iFoto;
  }

  /**
   * Retorna o ID da foto principal do CGM
   * @return null|integer
   */
  function getIdFotoPrincipal() {

    $iIdFoto     = null;
    $oDaoCgmFoto = db_utils::getDao("cgmfoto");
    $sSqlFotos   = $oDaoCgmFoto->sql_query_file(null,"*",
                                                "z16_sequencial",
                                                "z16_numcgm = {$this->getCodigo()}
                                               and z16_principal is true
                                               and z16_fotoativa is true");
    $rsFotos = $oDaoCgmFoto->sql_record($sSqlFotos);
    if ($oDaoCgmFoto->numrows > 0) {
      $iIdFoto = db_utils::fieldsMemory($rsFotos, 0)->z16_sequencial;
    }

    return $iIdFoto;
  }

  /**
   * Retorna as informacoes da foto
   *
   * @param  integer $iFoto Codigo sequencial da foto (z16_sequencial)
   * @return Objeto com os dados da foto
   */
  function getInfoFoto($iFoto) {

    $oFoto       = null;
    $oDaoCgmFoto = db_utils::getDao("cgmfoto");
    $sSqlFotos   = $oDaoCgmFoto->sql_query_file(null,"*",
                                               "z16_sequencial",
                                               "z16_sequencial = {$iFoto}");
    $rsFotos     = $oDaoCgmFoto->sql_record($sSqlFotos);
    if ($oDaoCgmFoto->numrows > 0) {

      $oDadosFoto       = db_utils::fieldsMemory($rsFotos, 0);;
      $oFoto->codigo    = $oDadosFoto->z16_sequencial;
      $oFoto->data      = $oDadosFoto->z16_data;
      $oFoto->hora      = $oDadosFoto->z16_hora;
      $oFoto->principal = $oDadosFoto->z16_principal == "t"?true:false;
      $oFoto->ativa     = $oDadosFoto->z16_fotoativa == "t"?true:false;
      $oFoto->oid       = $oDadosFoto->z16_arquivofoto;
      unset($oDadosFoto);
    }
    return $oFoto;
  }

  public function alterarFoto($iFoto, $lPrincipal, $lAtiva) {

    $oDaoCgmFoto                  = db_utils::getDao("cgmfoto");
    if ($lPrincipal) {

      $sSqlFotos   = $oDaoCgmFoto->sql_query_file(null,"*", "z16_sequencial","z16_numcgm ={$this->getCodigo()}");
      $rsFotos     = $oDaoCgmFoto->sql_record($sSqlFotos);
      $iTotalFotos = $oDaoCgmFoto->numrows;
      for ($i = 0; $i < $iTotalFotos; $i++) {

        $oDadosFoto       = db_utils::fieldsMemory($rsFotos, $i);
        $oDaoCgmFoto->z16_sequencial = $oDadosFoto->z16_sequencial;
        $oDaoCgmFoto->z16_principal  = "false";
        $oDaoCgmFoto->alterar($oDadosFoto->z16_sequencial);
        unset($oDadosFoto);
      }
    }
    $oDaoCgmFoto->z16_fotoativa   = $lAtiva?"true":"false";
    $oDaoCgmFoto->z16_principal   = $lPrincipal?"true":"false";
    $oDaoCgmFoto->z16_numcgm      = $this->getCodigo();
    $oDaoCgmFoto->z16_sequencial  = $iFoto;
    $oDaoCgmFoto->alterar($iFoto);
    if ($oDaoCgmFoto->erro_status == 0) {
      throw new Exception($oDaoCgmFoto->erro_msg);
    }
  }

  public function getDebitosEmAberto( $iNumeroDias = 0 ) {

    $dtDataUsu            = date("Y-m-d", db_getsession("DB_datausu"));
    $sSqlDebitosEmAberto  = "  select arrecad.k00_numpre as numpre,                                                                           ";
    $sSqlDebitosEmAberto .= "         arrecad.k00_numpar as numpar,                                                                           ";
    $sSqlDebitosEmAberto .= "         arrecad.k00_dtvenc as datavencimento,                                                                   ";
    $sSqlDebitosEmAberto .= "         arrecad.k00_receit as receita,                                                                          ";
    $sSqlDebitosEmAberto .= "         arrecad.k00_tipo   as tipo,                                                                             ";
    $sSqlDebitosEmAberto .= "         cadtipo.k03_descr  as descricaotipodebito,                                                              ";
    $sSqlDebitosEmAberto .= "         arrecad.k00_valor  as valordebito                                                                       ";
    $sSqlDebitosEmAberto .= "    from arrecad                                                                                                 ";
    $sSqlDebitosEmAberto .= "         inner join arrenumcgm on arrecad.k00_numpre = arrenumcgm.k00_numpre                                     ";
    $sSqlDebitosEmAberto .= "         inner join arretipo   on arrecad.k00_tipo   = arretipo.k00_tipo                                         ";
    $sSqlDebitosEmAberto .= "         inner join cadtipo    on arretipo.k03_tipo  = cadtipo.k03_tipo                                          ";
    $sSqlDebitosEmAberto .= "         left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data                   ";
    $sSqlDebitosEmAberto .= "                       from ( select max(k28_sequencia) as k28_sequencia,                                        ";
    $sSqlDebitosEmAberto .= "                                     max(k28_arrejust) as k28_arrejust,                                          ";
    $sSqlDebitosEmAberto .= "                                     k28_numpar,                                                                 ";
    $sSqlDebitosEmAberto .= "                                     k28_numpre                                                                  ";
    $sSqlDebitosEmAberto .= "                                from arrejustreg                                                                 ";
    $sSqlDebitosEmAberto .= "                            group by k28_numpre,                                                                 ";
    $sSqlDebitosEmAberto .= "                                     k28_numpar                                                                  ";
    $sSqlDebitosEmAberto .= "                            ) as subarrejust                                                                     ";
    $sSqlDebitosEmAberto .= "                            inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust             ";
    $sSqlDebitosEmAberto .= "                   ) as arrejustreg on arrejustreg.k28_numpre = arrecad.k00_numpre                               ";
    $sSqlDebitosEmAberto .= "                                   and arrejustreg.k28_numpar = arrecad.k00_numpar                               ";
    $sSqlDebitosEmAberto .= "   where arrenumcgm.k00_numcgm = {$this->getCodigo()}                                                            ";
    $sSqlDebitosEmAberto .= "     and ( case                                                                                                  ";
    $sSqlDebitosEmAberto .= "             when k28_numpre is null                                                                             ";
    $sSqlDebitosEmAberto .= "               then arrecad.k00_dtvenc < '{$dtDataUsu}'::date - cast('{$iNumeroDias} days' as interval)          ";
    $sSqlDebitosEmAberto .= "             else arrecad.k00_dtvenc + k27_dias < '{$dtDataUsu}'::date - cast('{$iNumeroDias} days' as interval) ";
    $sSqlDebitosEmAberto .= "           end )                                                                                                 ";
    $sSqlDebitosEmAberto .= " order by arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_receit                                             ";
    $aDebitosEmAberto        = array();
    $rsSqlDebitosEmAberto    = db_query($sSqlDebitosEmAberto);
    $iNumRowsDebitosEmAberto = pg_num_rows($rsSqlDebitosEmAberto);
    for ($iDebitosEmAberto   = 0; $iDebitosEmAberto < $iNumRowsDebitosEmAberto; $iDebitosEmAberto++) {

      $oDadosDebitosEmAberto = db_utils::fieldsMemory($rsSqlDebitosEmAberto, $iDebitosEmAberto);

      $oDadoDebitosEmAberto  = new stdClass();
      $oDadoDebitosEmAberto->iNumpre              = $oDadosDebitosEmAberto->numpre;
      $oDadoDebitosEmAberto->iNumpar              = $oDadosDebitosEmAberto->numpar;
      $oDadoDebitosEmAberto->dtVencimento         = $oDadosDebitosEmAberto->datavencimento;
      $oDadoDebitosEmAberto->iReceita             = $oDadosDebitosEmAberto->receita;
      $oDadoDebitosEmAberto->iTipo                = $oDadosDebitosEmAberto->tipo;
      $oDadoDebitosEmAberto->sDescricaoTipoDebito = $oDadosDebitosEmAberto->descricaotipodebito;
      $oDadoDebitosEmAberto->nValorDebito         = $oDadosDebitosEmAberto->valordebito;

      $aDebitosEmAberto[] = $oDadoDebitosEmAberto;
    }

    return $aDebitosEmAberto;
  }
}
?>
