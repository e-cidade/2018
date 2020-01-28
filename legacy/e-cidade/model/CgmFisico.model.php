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
class CgmFisico extends CgmBase  {

  /**
   * CPF do CGM
   *
   * @var integer
   */
  protected $iCpf;

  /**
   * Carteira Nacional de Habilitação
   *
   * @var string
   */
  protected $sCnh;

  /**
   * Categoria da CNH
   *
   * @var string
   */
  protected $sCategoriaCnh;

  /**
   * Data de Emissão da CNH
   *
   * @var string
   */
  protected $dtDataEmissaoCnh;

  /**
   * Data da Habilitação CNH
   *
   * @var string
   */
  protected $dtDataHabilitacaoCnh;

  /**
   * Data do Vencimento da CNH
   *
   * @var string
   */
  protected $dtDataVencimentoCnh;

  /**
   * Data de Falecimento do CGM
   *
   * @var string
   */
  protected $dtDataFalecimento;

  /**
   * Data de Nascimento do CGM
   *
   * @var string
   */
  protected $dtDataNascimento;

  /**
   * Nome do Pai do CGM
   *
   * @var string
   */
  protected $sNomePai;

  /**
   * Nome da Mãe do CGM
   *
   * @var string
   */
  protected $sNomeMae;

  /**
   * Sexo do CGM
   *
   * @var string
   */
  protected $sSexo;

  /**
   * Profissão do CGM
   *
   * @var string
   */
  protected $sProfissao;

  /**
   * Nacionalidade do CGM
   *
   * @var integer
   */
  protected $iNacionalidade;

  /**
   * Estado Civil do CGM
   *
   * @var integer
   */
  protected $iEstadoCivil;

  /**
   * Número da Identidade do CGM
   *
   * @var string
   */
  protected $sIdentidade;

  /**
   * Orgão emissor da identidade CGM
   *
   * @var string
   */
  protected $sIdentOrgao;

  /**
   * Data de Expiração da Identidade do CGM
   *
   * @var string
   */
  protected $sIdentDtExp;

  /**
   * Natiralidade do CGM
   *
   * @var string
   */
  protected $sNaturalidade;

  /**
   * Escolaridade do CGM
   *
   * @var string
   */
  protected $sEscolaridade;

  /**
   * Trabalha do CGM
   *
   * @var boolean
   */
  protected $lTrabalha;

  /**
   * Renda do CGM
   *
   * @var float
   */
  protected $nRenda;

  /**
   * Local de Trabalho do CGM
   *
   * @var string
   */
  protected $sLocalTrabalho;

  /**
   * PIS
   *
   * @var string
   */
  protected $sPIS;

  /**
   * Código CBO
   *
   * @var string
   */
  protected $iCBO;


  /**
   * Situação do CPF
   *
   * @var integer
   */
  protected $iSituacaoCpf;


  /**
   * familiares do Cgm
   */
  protected $aFamiliares = array();

  /**
   * @type string
   */
  protected $sDocumentoEstrangeiro;

  const NACIONALIDADE_BRASILEIRA  = 1;
  const NACIONALIDADE_ESTRANGEIRA = 2;

  function __construct( $iCgm = null ) {


    if ( !empty($iCgm) ) {

      parent::__construct($iCgm);

      $oDaoCgm = new cl_cgm();
      $sSqlCgm = $oDaoCgm->sql_query_file($iCgm);
      $rsCgm   = $oDaoCgm->sql_record($sSqlCgm);

      if ($oDaoCgm->numrows > 0) {

        $oDadosCgm = db_utils::fieldsMemory($rsCgm,0);

        $this->setCpf               ($oDadosCgm->z01_cgccpf);
        $this->setCategoriaCnh      ($oDadosCgm->z01_categoria);
        $this->setCnh               ($oDadosCgm->z01_cnh);
        $this->setDataEmissaoCnh    ($oDadosCgm->z01_dtemissao);
        $this->setDataFalecimento   ($oDadosCgm->z01_dtfalecimento);
        $this->setDataHabilitacaoCnh($oDadosCgm->z01_dthabilitacao);
        $this->setDataNascimento    ($oDadosCgm->z01_nasc);
        $this->setDataVencimentoCnh ($oDadosCgm->z01_dtvencimento);
        $this->setEstadoCivil       ($oDadosCgm->z01_estciv);
        $this->setIdentidade        ($oDadosCgm->z01_ident);
        $this->setNacionalidade     ($oDadosCgm->z01_nacion);
        $this->setNomeMae           ($oDadosCgm->z01_mae);
        $this->setNomePai           ($oDadosCgm->z01_pai);
        $this->setProfissao         ($oDadosCgm->z01_profis);
        $this->setSexo              ($oDadosCgm->z01_sexo);
        $this->setNaturalidade      ($oDadosCgm->z01_naturalidade);
        $this->setEscolaridade      ($oDadosCgm->z01_escolaridade);
        $this->setIdentDataExp      ($oDadosCgm->z01_identdtexp);
        $this->setIdentOrgao        ($oDadosCgm->z01_identorgao);
        $this->setLocalTrabalho     ($oDadosCgm->z01_localtrabalho);
        $this->setRenda             ($oDadosCgm->z01_renda);
        $this->setTrabalha          ($oDadosCgm->z01_trabalha=='t'?true:false);
        $this->setPIS               ($oDadosCgm->z01_pis);
        $this->setNomeCompleto      ( !empty( $oDadosCgm->z01_nomecomple ) ? $oDadosCgm->z01_nomecomple : $oDadosCgm->z01_nome );

        $oDaoCgmFisico = new cl_cgmfisico();
        $sSqlCgmFisico = $oDaoCgmFisico->sql_query_file(null, "*", null, "z04_numcgm = {$iCgm}");
        $rsCgmFisico   = db_query($sSqlCgmFisico);

        if(is_resource($rsCgmFisico)) {
          $oDaoCgmFisico->numrows = pg_num_rows($rsCgmFisico);
        }

        if ($oDaoCgmFisico->numrows > 0) {

          $oCgmFisico = db_utils::fieldsMemory($rsCgmFisico, 0);
          $this->setCBO($oCgmFisico->z04_rhcbo);
        }
      }
    }
  }

  /**
   * @return string
   */
  public function getLocalTrabalho (){
    return $this->sLocalTrabalho;
  }

  /**
   * @param $sLocalTrabalha
   */
  public function setLocalTrabalho ($sLocalTrabalha) {
    $this->sLocalTrabalho = $sLocalTrabalha;
  }

  /**
   * @return bool
   */
  public function getTrabalha (){
    return $this->lTrabalha;
  }

  /**
   * @param $lTrabalha
   */
  public function setTrabalha ($lTrabalha) {
    $this->lTrabalha = $lTrabalha;
  }

  /**
   * @return float
   */
  public function getRenda (){
    return $this->nRenda;
  }

  /**
   * @param $nRenda
   */
  public function setRenda ($nRenda) {
    $this->nRenda = $nRenda;
  }

  /**
   * @return string
   */
  public function getNaturalidade (){
    return $this->sNaturalidade;
  }

  /**
   * @param $sNaturalidade
   */
  public function setNaturalidade ($sNaturalidade) {
    $this->sNaturalidade = $sNaturalidade;
  }

  /**
   * @return string
   */
  public function getEscolaridade (){
    return $this->sEscolaridade;
  }

  /**
   * @param $sEscolaridade
   */
  public function setEscolaridade ($sEscolaridade) {
    $this->sEscolaridade = $sEscolaridade;
  }

  /**
   * @return string
   */
  public function getIdentOrgao (){
    return $this->sIdentOrgao;
  }

  /**
   * @param string $sIdentOrgao
   */
  public function setIdentOrgao ($sIdentOrgao) {
    $this->sIdentOrgao = $sIdentOrgao;
  }

  /**
   * @return string
   */
  public function getIdentDataExp (){
    return $this->sIdentDtExp;
  }

  /**
   * @param string $sIdentDtExp
   */
  public function setIdentDataExp ($sIdentDtExp) {
    $this->sIdentDtExp = $sIdentDtExp;
  }

  /**
   * @return string
   */
  public function getDataEmissaoCnh (){
    return $this->dtDataEmissaoCnh;
  }

  /**
   * @param string $dtDataEmissaoCnh
   */
  public function setDataEmissaoCnh ($dtDataEmissaoCnh) {
    $this->dtDataEmissaoCnh = $dtDataEmissaoCnh;
  }

  /**
   * @return string
   */
  public function getDataFalecimento () {
    return $this->dtDataFalecimento;
  }

  /**
   * @param string $dtDataFalecimento
   */
  public function setDataFalecimento ($dtDataFalecimento) {
    $this->dtDataFalecimento = $dtDataFalecimento;
  }

  /**
   * @return string
   */
  public function getDataHabilitacaoCnh () {
    return $this->dtDataHabilitacaoCnh;
  }

  /**
   * @param string $dtDataHabilitacaoCnh
   */
  public function setDataHabilitacaoCnh ($dtDataHabilitacaoCnh) {
    $this->dtDataHabilitacaoCnh = $dtDataHabilitacaoCnh;
  }

  /**
   * @return string
   */
  public function getDataNascimento () {
    return $this->dtDataNascimento;
  }

  /**
   * @param string $dtDataNascimento
   */
  public function setDataNascimento ($dtDataNascimento) {
    $this->dtDataNascimento = $dtDataNascimento;
  }

  /**
   * @return string
   */
  public function getDataVencimentoCnh () {
    return $this->dtDataVencimentoCnh;
  }

  /**
   * @param string $dtDataVencimentoCnh
   */
  public function setDataVencimentoCnh ($dtDataVencimentoCnh) {
    $this->dtDataVencimentoCnh = $dtDataVencimentoCnh;
  }

  /**
   * @return string
   */
  public function getCpf () {
    return $this->iCpf;
  }

  /**
   * @param string $iCpf
   */
  public function setCpf ($iCpf) {
    $this->iCpf = $iCpf;
  }

  /**
   * @return string
   */
  public function getEstadoCivil () {
    return $this->iEstadoCivil;
  }

  /**
   * @return string
   */
  public function getDescrEstadoCivil () {
    switch ($this->iEstadoCivil) {
      case '1':
        $sEstadoCivil = 'Solteiro';
        break;
      case '2':
        $sEstadoCivil = 'Casado';
        break;
      case '3':
        $sEstadoCivil = 'Viúvo';
        break;
      case '4':
        $sEstadoCivil = 'Divorciado';
        break;
      default:
        $sEstadoCivil='';
        break;
    }
    return $sEstadoCivil;
  }

  /**
   * @param integer $iEstadoCivil
   */
  public function setEstadoCivil ($iEstadoCivil) {
    $this->iEstadoCivil = $iEstadoCivil;
  }

  /**
   * @return integer
   */
  public function getNacionalidade () {
    return $this->iNacionalidade;
  }

  /**
   * @return string
   */
  public function getDescrNacionalidade () {
    switch ($this->iNacionalidade) {
      case 1:
        return "Brasileira";
        break;
      case 2:
        return "Estrangeira";
        break;
      default:
        return "";
        break;
    }
  }

  /**
   * @param integer $iNacionalidade
   */
  public function setNacionalidade ($iNacionalidade) {
    $this->iNacionalidade = $iNacionalidade;
  }

  /**
   * @return string
   */
  public function getCategoriaCnh () {
    return $this->sCategoriaCnh;
  }

  /**
   * @param $sCategoriaCnh
   */
  public function setCategoriaCnh ($sCategoriaCnh) {
    $this->sCategoriaCnh = $sCategoriaCnh;
  }

  /**
   * @return string
   */
  public function getCnh () {
    return $this->sCnh;
  }

  /**
   * @param $sCnh
   */
  public function setCnh ($sCnh) {
    $this->sCnh = $sCnh;
  }

  /**
   * @return string
   */
  public function getIdentidade () {
    return $this->sIdentidade;
  }

  /**
   * @param $sIdentidade
   */
  public function setIdentidade ($sIdentidade) {
    $this->sIdentidade = $sIdentidade;
  }

  /**
   * @return string
   */
  public function getNomeMae () {
    return $this->sNomeMae;
  }

  /**
   * @param $sNomeMae
   */
  public function setNomeMae ($sNomeMae) {
    $this->sNomeMae = $sNomeMae;
  }

  /**
   * @return string
   */
  public function getNomePai () {
    return $this->sNomePai;
  }

  /**
   * @param $sNomePai
   */
  public function setNomePai ($sNomePai) {
    $this->sNomePai = $sNomePai;
  }

  /**
   * @return string
   */
  public function getProfissao () {
    return $this->sProfissao;
  }

  /**
   * @param $sProfissao
   */
  public function setProfissao ($sProfissao) {
    $this->sProfissao = $sProfissao;
  }

  /**
   * @return string
   */
  public function getSexo () {
    return $this->sSexo;
  }

  /**
   * @param $sSexo
   */
  public function setSexo ($sSexo) {
    $this->sSexo = $sSexo;
  }


  /**
   * @param $iCBO
   */
  public function setCBO ($iCBO){
    $this->iCBO = $iCBO;
  }

  /**
   * @return string
   */
  public function getCBO (){
    return $this->iCBO;
  }

  /**
   * @param $sPIS
   */
  public function setPIS ($sPIS){
    $this->sPIS = $sPIS;
  }

  /**
   * @return string
   */
  public function getPIS (){
    return $this->sPIS;
  }

  /**
   * @return integer
   */
  public function getSituacao () {

    if (!empty($this->iSituacaoCpf)) {
      return $this->iSituacaoCpf;
    } else {

      $cl_situacao     = new cl_cgmsituacaocpf();
      $sSqlSituacao    = $cl_situacao->sql_query("", "*", "", "z01_numcgm = {$this->getCodigo()}");
      $rsSituacao      = $cl_situacao->sql_record($sSqlSituacao);
      $aListaSituacao  = db_utils::getCollectionByRecord($rsSituacao);
      $iSituacao       = count($aListaSituacao);
      if ($iSituacao == 0 ||$iSituacao == '0' ) {
        return false;
      } else {
        return $aListaSituacao[0]->z17_situacao;
      }

    }

  }

  /**
   * @param integer $iSituacaoCpf
   */
  public function setSituacao ($iSituacaoCpf) {
    $this->iSituacaoCpf = $iSituacaoCpf;
  }


  /**
   * Salva os dados informados do CGM, caso o CGM já exista então
   * é alterado o registro apartir do código (numcgm) informado
   * @throws Exception
   */
  public function save() {

    $sMsgErro = 'Falha ao salvar CGM Fisico';

    /**
     * Verifica se existe alguma transação ativa
     */
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }

    /**
     * Chama o método save da classe CGM
     */
    try {
      parent::save();
    } catch (Exception $eException){
      throw new Exception("{$sMsgErro}, {$eException->getMessage()}");
    }

    $oDaoCgm         = new cl_cgm();
    $oDaoCgmCpf      = new cl_db_cgmcpf();
    $oDaoCgmFisico   = new cl_cgmfisico();
    $oDaoCgmJuridico = new cl_cgmjuridico();

    $oDaoCgm->z01_numcgm        = $this->getCodigo();
    $oDaoCgm->z01_cgccpf        = $this->getCpf();
    $oDaoCgm->z01_cnh           = $this->getCnh();
    $oDaoCgm->z01_categoria     = $this->getCategoriaCnh();
    $oDaoCgm->z01_dtemissao     = $this->getDataEmissaoCnh();
    $oDaoCgm->z01_dthabilitacao = $this->getDataHabilitacaoCnh();
    $oDaoCgm->z01_dtvencimento  = $this->getDataVencimentoCnh();
    $oDaoCgm->z01_dtfalecimento = $this->getDataFalecimento();
    $oDaoCgm->z01_nasc          = $this->getDataNascimento();
    $oDaoCgm->z01_pai           = addslashes($this->getNomePai());
    $oDaoCgm->z01_mae           = addslashes($this->getNomeMae());
    $oDaoCgm->z01_sexo          = $this->getSexo();
    $oDaoCgm->z01_profis        = addslashes($this->getProfissao());
    $oDaoCgm->z01_nacion        = $this->getNacionalidade();
    $oDaoCgm->z01_estciv        = $this->getEstadoCivil();
    $oDaoCgm->z01_ident         = $this->getIdentidade();
    $oDaoCgm->z01_ultalt        = date('Y-m-d',db_getsession('DB_datausu'));
    $oDaoCgm->z01_identorgao    = $this->getIdentOrgao();
    $oDaoCgm->z01_identdtexp    = $this->getIdentDataExp();
    $oDaoCgm->z01_naturalidade  = $this->getNaturalidade();
    $oDaoCgm->z01_escolaridade  = $this->getEscolaridade();
    $oDaoCgm->z01_trabalha      = $this->getTrabalha()?"true":"false";
    $oDaoCgm->z01_localtrabalho = $this->getLocalTrabalho();
    $oDaoCgm->z01_renda         = $this->getRenda();
    $oDaoCgm->z01_pis           = $this->getPIS();

    $oDaoCgm->alterar($this->getCodigo());

    if ( $oDaoCgm->erro_status == "0" ) {
      throw new Exception("{$sMsgErro}, {$oDaoCgm->erro_msg}");
    }

    $oDaoCgmCpf->excluir($this->getCodigo());
    if ( $oDaoCgmCpf->erro_status == "0" ) {
      throw new Exception("{$sMsgErro}, {$oDaoCgmCpf->erro_msg}");
    }

    $oDaoCgmCpf->z01_numcgm = $this->getCodigo();
    $oDaoCgmCpf->z01_cpf    = $this->getCpf();

    $oDaoCgmCpf->incluir($this->getCodigo());

    if ( $oDaoCgmCpf->erro_status == "0" ) {
      throw new Exception("{$sMsgErro}, {$oDaoCgmCpf->erro_msg}");
    }

    if ($this->getSituacao()) {
      $this->salvarSituacaoCpf();
    }

    $oDaoCgmJuridico->excluir(null, "z08_numcgm = {$this->getCodigo()}");
    if ($oDaoCgmJuridico->erro_status == "0") {
      throw new Exception("Erro ao excluir cgm {$this->getCodigo()} da tabela cgmjuridico.");
    }

    $oDaoCgmFisico->excluir(null, "z04_numcgm = {$this->getCodigo()}");

    if ($oDaoCgmFisico->erro_status == "0") {
      throw new Exception("Erro ao excluir cgm {$this->getCodigo()} da tabela cgmfisico.");
    }

    $oDaoCgmFisico->z04_numcgm = $this->getCodigo();
    $oDaoCgmFisico->z04_rhcbo  = $this->getCBO();

    $oDaoCgmFisico->incluir(null);

    if ($oDaoCgmFisico->erro_status == "0") {
      throw new Exception("Erro ao incluir cgm {$this->getCodigo()} da tabela cgmfisico.{$oDaoCgmFisico->erro_msg}");
    }


    $this->vincularDocumentoEstrangeiro();
  }

  public function adicionarFamiliar($oFamiliar) {

    foreach ($this->aFamiliares as $oFamiliarCadastrado) {

      if ($oFamiliarCadastrado->iCgm == $oFamiliar->iCgm) {
        throw new Exception("Familiar já cadastrado para a familia");
      }
    }
    $this->aFamiliares[] = $oFamiliar;
  }

  /**
   * @throws Exception
   */
  public function salvarFamiliares() {

    /**
     * o Cgm deve estar cadastrado na familia
     */
    $lCgmOk = false;
    foreach ($this->aFamiliares as $oFamiliar) {

      if ($oFamiliar->iCgm == $this->getCodigo()) {
        $lCgmOk = true;
      }
    }
    if (!$lCgmOk) {
      throw new Exception("o CGm {$this->getCodigo()} - {$this->getNome()}, deve fazer parte da composição familiar");
    }
    /**
     * Verificamos se o cgm já possui uma familia cadastrada
     */
    $iCodigoFamilia = null;
    $oDaoCgmFamilia = db_utils::getDao("cgmcomposicaofamiliar");
    $sWhere         = "z15_numcgm = {$this->getCodigo()}";
    $sSqlFamilia    = $oDaoCgmFamilia->sql_query_file(null,"z15_cgmfamilia", null, $sWhere);
    $rsFamilia      = db_query($sSqlFamilia);

    if (!$rsFamilia) {
      throw new DBException("Ocorreu um erro ao verificar os familiares");
    }

    $oDaoCgmFamilia->numrows = pg_num_rows($rsFamilia);

    if ($oDaoCgmFamilia->numrows > 0) {

      $oDadosFamilia  = db_utils::fieldsMemory($rsFamilia, 0);
      $iCodigoFamilia = $oDadosFamilia->z15_cgmfamilia;
    }
    if (empty($iCodigoFamilia)) {

      $oDaoCgmCodigoFamilia = db_utils::getDao("cgmfamilia");
      $oDaoCgmCodigoFamilia->incluir(null);
      $iCodigoFamilia  = $oDaoCgmCodigoFamilia->z13_sequencial;

    }
    $oDaoCgmFamilia->excluir(null,"z15_cgmfamilia = {$iCodigoFamilia}");
    foreach ($this->aFamiliares as $oFamilia) {

      $oDaoCgmFamilia->z15_cgmtipofamiliar = $oFamilia->iTipo;
      $oDaoCgmFamilia->z15_cgmfamilia      = $iCodigoFamilia;
      $oDaoCgmFamilia->z15_numcgm          = $oFamilia->iCgm;
      $oDaoCgmFamilia->incluir(null);
      if ($oDaoCgmFamilia->erro_status == 0) {
        throw new Exception("Erro ao incluir familiar!\n{$oDaoCgmFamilia->erro_msg}");
      }
    }
  }

  /**
   * @return stdClass[]
   */
  public  function getFamiliares() {

    $iCodigoFamilia = null;
    $oDaoCgmFamilia = db_utils::getDao("cgmcomposicaofamiliar");
    $sWhere         = "z15_numcgm = {$this->getCodigo()}";
    $sSqlFamilia    = $oDaoCgmFamilia->sql_query_file(null,"z15_cgmfamilia", null, $sWhere);
    $rsFamilia      = db_query($sSqlFamilia);

    if(is_resource($rsFamilia)) {
      $oDaoCgmFamilia->numrows = pg_num_rows($rsFamilia);
    }

    if ($oDaoCgmFamilia->numrows > 0) {

      $oDadosFamilia  = db_utils::fieldsMemory($rsFamilia, 0);
      $iCodigoFamilia = $oDadosFamilia->z15_cgmfamilia;
    }

    if (!empty($iCodigoFamilia)) {

      $sWhere      = "z15_cgmfamilia  = {$iCodigoFamilia}";
      $sSqlFamilia = $oDaoCgmFamilia->sql_query(null,"*", null, $sWhere);
      $rsFamilia   = null;
      $rsFamilia   = db_query($sSqlFamilia);
      $oDaoCgmFamilia->numrows = 0;

      if(is_resource($rsFamilia)) {
        $oDaoCgmFamilia->numrows = pg_num_rows($rsFamilia);
      }

      for ($i = 0; $i < $oDaoCgmFamilia->numrows; $i++) {

        $oFamiliaCadastrada  = db_utils::fieldsMemory($rsFamilia, $i);
        $oFamiliar           = new stdClass();
        $oFamiliar->iCgm     = $oFamiliaCadastrada->z01_numcgm;
        $oFamiliar->sNome    = $oFamiliaCadastrada->z01_nome;
        $oFamiliar->iTipo    = $oFamiliaCadastrada->z15_cgmtipofamiliar;
        $oFamiliar->sTipo    = $oFamiliaCadastrada->z14_descricao;

        $this->aFamiliares[] = $oFamiliar;
      }
    }
    return $this->aFamiliares;
  }
  function removerFamiliares() {
    $this->aFamiliares = array();
  }

  /**
   * método responsavel por atualizar a situação do cpf
   * esse metodo é privado, pois será chamado dentro do metodo save
   * desta classe.
   * @throws Exception
   */
  private function salvarSituacaoCpf(){

    $iCgm                      = $this->getCodigo();
    $iSituacao                 = $this->getSituacao();
    $sMsgErro                  = 'Falha ao Atualizar a Situação do CPF ';
    $sSqlSalvaSituacao         = "";

    /*
     * verificamos se ja existe registro na tabela cgmsituacaocpf, referente ao cgm consultado
     * se existe definimos o metodo alterar() da classe, se não existe utilizamos incluir()
     */
    $cl_situacao               = db_utils::getDao('cgmsituacaocpf');
    $sSqlVerSituacao           = $cl_situacao->sql_query("", "*", "", "z17_numcgm = {$this->getCodigo()}");
    $rsVerSituacao             = $cl_situacao->sql_record($sSqlVerSituacao);
    $aListaVerSituacao         = db_utils::getCollectionByRecord($rsVerSituacao);
    $iVerSituacao              = count($aListaVerSituacao);

    $cl_situacao->z17_numcgm   = $iCgm;
    $cl_situacao->z17_situacao = $iSituacao;

    if ($iVerSituacao == 0) {

      $cl_situacao->incluir('');
      if ( $cl_situacao->erro_status == "0" ) {
        throw new Exception("{$sMsgErro}, {$cl_situacao->erro_msg}");
      }
    } else {

      $cl_situacao->z17_sequencial = $aListaVerSituacao[0]->z17_sequencial;

      $cl_situacao->alterar($cl_situacao->z17_sequencial);
      if ( $cl_situacao->erro_status == "0" ) {
        throw new Exception("{$sMsgErro}, {$cl_situacao->erro_msg}");
      }

    }
  }

  /**
   * Verifica no banco se todas as perguntas
   * do questionário foram respondidas
   */
  public function preencheuEsocial() {

    $sSqlAvaliacaoRespostaCgm  = " select                                                                                                                           ";
    $sSqlAvaliacaoRespostaCgm .= "   db103_sequencial as codigo_pergunta,                                                                                           ";
    $sSqlAvaliacaoRespostaCgm .= "   db103_descricao as pergunta,                                                                                                   ";
    $sSqlAvaliacaoRespostaCgm .= "   array_accum(distinct db103_avaliacaogrupopergunta) as grupo_pergunta,                                                          ";
    $sSqlAvaliacaoRespostaCgm .= "   array_accum(db106_sequencial) as codigo_resposta,                                                                              ";
    $sSqlAvaliacaoRespostaCgm .= "   sum(case when db106_sequencial is NULL then 0 else 1 end) as qtde_respostas                                                    ";
    $sSqlAvaliacaoRespostaCgm .= " from                                                                                                                             ";
    $sSqlAvaliacaoRespostaCgm .= "   avaliacaopergunta                                                                                                              ";
    $sSqlAvaliacaoRespostaCgm .= "   inner join avaliacaogrupopergunta on db102_sequencial = db103_avaliacaogrupopergunta                                           ";
    $sSqlAvaliacaoRespostaCgm .= "   inner join avaliacao on db101_sequencial = db102_avaliacao                                                                     ";
    $sSqlAvaliacaoRespostaCgm .= "   inner join avaliacaotipo on db100_sequencial = db101_avaliacaotipo                                                             ";
    $sSqlAvaliacaoRespostaCgm .= "   inner join avaliacaoperguntaopcao on db104_avaliacaopergunta = db103_sequencial                                                ";
    $sSqlAvaliacaoRespostaCgm .= "   left join avaliacaoresposta on db106_avaliacaoperguntaopcao = db104_sequencial                                                 ";
    $sSqlAvaliacaoRespostaCgm .= "   left join avaliacaogrupoperguntaresposta on db108_avaliacaoresposta = db106_sequencial                                         ";
    $sSqlAvaliacaoRespostaCgm .= "   left join avaliacaogruporesposta on db107_sequencial = db108_avaliacaogruporesposta                                            ";
    $sSqlAvaliacaoRespostaCgm .= "   left join avaliacaogruporespostarhpessoal on eso02_avaliacaogruporesposta = db107_sequencial                                   ";
    $sSqlAvaliacaoRespostaCgm .= " where                                                                                                                            ";
    $sSqlAvaliacaoRespostaCgm .= "   db100_sequencial = 5                                                                                                           ";
    $sSqlAvaliacaoRespostaCgm .= "   and db101_sequencial = 3000008                                                                                                 ";
    $sSqlAvaliacaoRespostaCgm .= "   and (eso02_rhpessoal IN (                                                                                                      ";
    $sSqlAvaliacaoRespostaCgm .= "                             select                                                                                               ";
    $sSqlAvaliacaoRespostaCgm .= "                               rh01_regist                                                                                        ";
    $sSqlAvaliacaoRespostaCgm .= "                             from                                                                                                 ";
    $sSqlAvaliacaoRespostaCgm .= "                               rhpessoal                                                                                          ";
    $sSqlAvaliacaoRespostaCgm .= "                             where                                                                                                ";
    $sSqlAvaliacaoRespostaCgm .= "                               rh01_numcgm = ". $this->getCodigo()."                                                              ";
    $sSqlAvaliacaoRespostaCgm .= "                           )                                                                                                      ";
    $sSqlAvaliacaoRespostaCgm .= "         or db106_sequencial is null                                                                                              ";
    $sSqlAvaliacaoRespostaCgm .= "       )                                                                                                                          ";
    $sSqlAvaliacaoRespostaCgm .= "   and db102_descricao not ilike '%dependente%'                                                                                   ";
    $sSqlAvaliacaoRespostaCgm .= "   and db102_sequencial NOT IN (3000040, 3000041, 3000044, 3000045, 3000046, 3000057, 3000060, 3000061, 3000062)                  ";
    $sSqlAvaliacaoRespostaCgm .= "   and db104_sequencial NOT IN (                                                                                                  ";
    $sSqlAvaliacaoRespostaCgm .= "                                 select                                                                                           ";
    $sSqlAvaliacaoRespostaCgm .= "                                   DISTINCT db106_avaliacaoperguntaopcao                                                          ";
    $sSqlAvaliacaoRespostaCgm .= "                                 from                                                                                             ";
    $sSqlAvaliacaoRespostaCgm .= "                                   avaliacaoresposta                                                                              ";
    $sSqlAvaliacaoRespostaCgm .= "                                 inner join avaliacaogrupoperguntaresposta on db108_avaliacaoresposta = db106_sequencial          ";
    $sSqlAvaliacaoRespostaCgm .= "                                 inner join avaliacaogruporesposta on db107_sequencial = db108_avaliacaogruporesposta             ";
    $sSqlAvaliacaoRespostaCgm .= "                                 inner join avaliacaogruporespostarhpessoal on eso02_avaliacaogruporesposta = db107_sequencial    ";
    $sSqlAvaliacaoRespostaCgm .= "                                 where                                                                                            ";
    $sSqlAvaliacaoRespostaCgm .= "                                   eso02_rhpessoal IN (                                                                           ";
    $sSqlAvaliacaoRespostaCgm .= "                                                         select                                                                   ";
    $sSqlAvaliacaoRespostaCgm .= "                                                           rh01_regist                                                            ";
    $sSqlAvaliacaoRespostaCgm .= "                                                         from                                                                     ";
    $sSqlAvaliacaoRespostaCgm .= "                                                           rhpessoal                                                              ";
    $sSqlAvaliacaoRespostaCgm .= "                                                         where                                                                    ";
    $sSqlAvaliacaoRespostaCgm .= "                                                           rh01_numcgm = ". $this->getCodigo()."                                  ";
    $sSqlAvaliacaoRespostaCgm .= "                                                       )                                                                          ";
    $sSqlAvaliacaoRespostaCgm .= "                                   and db106_resposta IS NOT NULL                                                                 ";
    $sSqlAvaliacaoRespostaCgm .= "                                   and db106_resposta <> ''                                                                       ";
    $sSqlAvaliacaoRespostaCgm .= "                               )                                                                                                  ";
    $sSqlAvaliacaoRespostaCgm .= " group by                                                                                                                         ";
    $sSqlAvaliacaoRespostaCgm .= "   db103_sequencial,                                                                                                              ";
    $sSqlAvaliacaoRespostaCgm .= "   db103_descricao                                                                                                                ";
    $sSqlAvaliacaoRespostaCgm .= " having sum(case when db106_sequencial is null then 0 else 1 end) = 0                                                             ";
    $sSqlAvaliacaoRespostaCgm .= " order by                                                                                                                         ";
    $sSqlAvaliacaoRespostaCgm .= "   grupo_pergunta,                                                                                                                ";
    $sSqlAvaliacaoRespostaCgm .= "   pergunta                                                                                                                       ";

    $rsAvaliacaoRespostaCgm   = db_query($sSqlAvaliacaoRespostaCgm);

    if(!$rsAvaliacaoRespostaCgm) {
      throw new DBException("Ocorreu um erro ao verificar se o usuario respondeu ao eSocial");
    }

    if(pg_num_rows($rsAvaliacaoRespostaCgm) == 0) {
      return true;
    }

    return false;
  }

  /**
   * Documento estrangeiro
   * @param $sDocumento
   */
  public function setDocumentoEstrangeiro($sDocumento) {
    $this->sDocumentoEstrangeiro = $sDocumento;
  }

  /**
   * Documento estrangeiro
   * @return string
   * @throws Exception
   */
  public function getDocumentoEstrangeiro() {

    if (empty($this->sDocumentoEstrangeiro) && $this->iNacionalidade == self::NACIONALIDADE_ESTRANGEIRA) {

      $oDaoDocumento = new cl_cgmestrangeiro();
      $sSqlDocumento = $oDaoDocumento->sql_query_file(null, "z09_documento", null, "z09_numcgm = {$this->iCodigo}");
      $rsDocumento   = db_query($sSqlDocumento);
      if (!$rsDocumento) {
        throw new Exception("Ocorreu um erro ao buscar o número do documento estrangeiro.");
      }

      if (pg_num_rows($rsDocumento) > 0) {
        $this->setDocumentoEstrangeiro(db_utils::fieldsMemory($rsDocumento, 0)->z09_documento);
      }
    }
    return $this->sDocumentoEstrangeiro;
  }

  /**
   * @return bool
   * @throws Exception
   */
  protected function vincularDocumentoEstrangeiro() {

    $oDaoDocumento = new cl_cgmestrangeiro();
    $oDaoDocumento->excluir(null, "z09_numcgm = {$this->iCodigo}");

    if ($this->iNacionalidade == self::NACIONALIDADE_BRASILEIRA || empty($this->iNacionalidade) || empty($this->sDocumentoEstrangeiro)) {
      return true;
    }

    $oDaoDocumento->z09_sequencial = null;
    $oDaoDocumento->z09_numcgm     = $this->iCodigo;
    $oDaoDocumento->z09_documento  = $this->sDocumentoEstrangeiro;
    $oDaoDocumento->incluir($oDaoDocumento->z09_sequencial);
    if ($oDaoDocumento->erro_status == "0") {
      throw new Exception("Não foi possível vincular o documento do CGM estrangeiro.");
    }
    return true;
  }
}
