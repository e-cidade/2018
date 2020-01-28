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
	 * @var varchar
	 */
	protected $sCnh;
	
	/**
	 * Categoria da CNH
	 *
	 * @var varchar
	 */
	protected $sCategoriaCnh;
	 
	/**
	 * Data de Emissão da CNH
	 *
	 * @var date
	 */
	protected $dtDataEmissaoCnh;
	 
	/**
	 * Data da Habilitação CNH
	 *
	 * @var date
	 */
	protected $dtDataHabilitacaoCnh;
	
	/**
	 * Data do Vencimento da CNH
	 *
	 * @var date
	 */
	protected $dtDataVencimentoCnh;
	  
	/**
	 * Data de Falecimento do CGM
	 *
	 * @var date
	 */
	protected $dtDataFalecimento;
	 
	/**
	 * Data de Nascimento do CGM
	 *
	 * @var date
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
   * @var bollean
   */
  protected $lTrabalha;
  
  /**
   * Renda do CGM
   *
   * @var numeric
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
	
  function __construct( $iCgm = null ) {

  	
    if ( !empty($iCgm) ) {
      
      parent::__construct($iCgm); 
        
      $oDaoCgm = db_utils::getDao("cgm");
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
        $this->setNomeCompleto      ($oDadosCgm->z01_nome);
        
        $oDaoCgmFisico = db_utils::getDao('cgmfisico');
        
        $sSqlCgmFisico = $oDaoCgmFisico->sql_query_file(null, "*", null, "z04_numcgm = {$iCgm}");
        
        $rsCgmFisico   = $oDaoCgmFisico->sql_record($sSqlCgmFisico);
        
        if ($oDaoCgmFisico->numrows > 0) {
          
          $oCgmFisico = db_utils::fieldsMemory($rsCgmFisico, 0);
          
          $this->setCBO($oCgmFisico->z04_rhcbo);
          
        }
        
      }
      
    } 
      	
  }

/**
   * @return unknown
   */
  public function getLocalTrabalho (){ 
    return $this->sLocalTrabalho; 
  }

/**
   * @param unknown_type $sNaturalidade
   */
  public function setLocalTrabalho ($sLocalTrabalha) { 
    $this->sLocalTrabalho = $sLocalTrabalha; 
  }  
  
/**
   * @return unknown
   */
  public function getTrabalha (){ 
    return $this->lTrabalha; 
  }

/**
   * @param unknown_type $sNaturalidade
   */
  public function setTrabalha ($lTrabalha) { 
    $this->lTrabalha = $lTrabalha; 
  }  

/**
   * @return unknown
   */
  public function getRenda (){ 
    return $this->nRenda; 
  }

/**
   * @param unknown_type $sNaturalidade
   */
  public function setRenda ($nRenda) { 
    $this->nRenda = $nRenda; 
  }  
  
/**
   * @return unknown
   */
  public function getNaturalidade (){ 
    return $this->sNaturalidade; 
  }

/**
   * @param unknown_type $sNaturalidade
   */
  public function setNaturalidade ($sNaturalidade) { 
    $this->sNaturalidade = $sNaturalidade; 
  }  
  
  
/**
   * @return unknown
   */
  public function getEscolaridade (){ 
    return $this->sEscolaridade; 
  }

/**
   * @param unknown_type $sIdentDtExp
   */
  public function setEscolaridade ($sEscolaridade) { 
    $this->sEscolaridade = $sEscolaridade; 
  }  
    
/**
   * @return unknown
   */
  public function getIdentOrgao (){ 
    return $this->sIdentOrgao; 
  }

/**
   * @param unknown_type $sIdentOrgao
   */
  public function setIdentOrgao ($sIdentOrgao) { 
    $this->sIdentOrgao = $sIdentOrgao; 
  }  
  
/**
   * @return unknown
   */
  public function getIdentDataExp (){ 
    return $this->sIdentDtExp; 
  }

/**
   * @param unknown_type $sIdentDtExp
   */
  public function setIdentDataExp ($sIdentDtExp) { 
    $this->sIdentDtExp = $sIdentDtExp; 
  }  
  
/**
   * @return unknown
   */
  public function getDataEmissaoCnh (){ 
  	return $this->dtDataEmissaoCnh; 
  }

/**
   * @param unknown_type $dtDataEmissaoCnh
   */
  public function setDataEmissaoCnh ($dtDataEmissaoCnh) { 
  	$this->dtDataEmissaoCnh = $dtDataEmissaoCnh; 
  }

/**
   * @return unknown
   */
  public function getDataFalecimento () { 
  	return $this->dtDataFalecimento; 
  }

/**
   * @param unknown_type $dtDataFalecimento
   */
  public function setDataFalecimento ($dtDataFalecimento) { 
  	$this->dtDataFalecimento = $dtDataFalecimento; 
  }

/**
   * @return unknown
   */
  public function getDataHabilitacaoCnh () { 
  	return $this->dtDataHabilitacaoCnh; 
  }

/**
   * @param unknown_type $dtDataHabilitacaoCnh
   */
  public function setDataHabilitacaoCnh ($dtDataHabilitacaoCnh) { 
  	$this->dtDataHabilitacaoCnh = $dtDataHabilitacaoCnh; 
  }

/**
   * @return unknown
   */
  public function getDataNascimento () { 
  	return $this->dtDataNascimento; 
  }

/**
   * @param unknown_type $dtDataNascimento
   */
  public function setDataNascimento ($dtDataNascimento) { 
  	$this->dtDataNascimento = $dtDataNascimento; 
  }

/**
   * @return unknown
   */
  public function getDataVencimentoCnh () { 
  	return $this->dtDataVencimentoCnh;
  }

/**
   * @param unknown_type $dtDataVencimentoCnh
   */
  public function setDataVencimentoCnh ($dtDataVencimentoCnh) {
  	$this->dtDataVencimentoCnh = $dtDataVencimentoCnh; 
  }

/**
   * @return unknown
   */
  public function getCpf () { 
  	return $this->iCpf; 
  }

/**
   * @param unknown_type $iCpf
   */
  public function setCpf ($iCpf) { 
  	$this->iCpf = $iCpf; 
  }

/**
   * @return unknown
   */
  public function getEstadoCivil () {
  	return $this->iEstadoCivil; 
  }

 /**
   * @return unknown
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
   * @param unknown_type $iEstadoCivil
   */
  public function setEstadoCivil ($iEstadoCivil) { 
  	$this->iEstadoCivil = $iEstadoCivil; 
  }

/**
   * @return unknown
   */
  public function getNacionalidade () { 
  	return $this->iNacionalidade; 
  }

/**
   * @return unknown
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
   * @param unknown_type $iNacionalidade
   */
  public function setNacionalidade ($iNacionalidade) { 
  	$this->iNacionalidade = $iNacionalidade;
  }

/**
   * @return unknown
   */
  public function getCategoriaCnh () { 
  	return $this->sCategoriaCnh; 
  }

/**
   * @param unknown_type $sCategoriaCnh
   */
  public function setCategoriaCnh ($sCategoriaCnh) { 
  	$this->sCategoriaCnh = $sCategoriaCnh; 
  }

 /**
   * @return unknown
   */
  public function getCnh () { 
  	return $this->sCnh; 
  }

  /**
   * @param unknown_type $sCnh
   */
  public function setCnh ($sCnh) { 
  	$this->sCnh = $sCnh; 
  }

 /**
   * @return unknown
   */
  public function getIdentidade () { 
  	return $this->sIdentidade; 
  }

 /**
   * @param unknown_type $sIdentidade
   */
  public function setIdentidade ($sIdentidade) { 
  	$this->sIdentidade = $sIdentidade; 
  }

 /**
   * @return unknown
   */
  public function getNomeMae () { 
  	return $this->sNomeMae; 
  }

 /**
   * @param unknown_type $sNomeMae
   */
  public function setNomeMae ($sNomeMae) { 
  	$this->sNomeMae = $sNomeMae; 
  }

 /**
   * @return unknown
   */
  public function getNomePai () { 
  	return $this->sNomePai; 
  }

 /**
   * @param unknown_type $sNomePai
   */
  public function setNomePai ($sNomePai) { 
  	$this->sNomePai = $sNomePai; 
  }

 /**
   * @return unknown
   */
  public function getProfissao () { 
  	return $this->sProfissao; 
  }

 /**
   * @param unknown_type $sProfissao
   */
  public function setProfissao ($sProfissao) { 
  	$this->sProfissao = $sProfissao; 
  }

 /**
   * @return unknown
   */
  public function getSexo () { 
  	return $this->sSexo; 
  }

 /**
   * @param unknown_type $sSexo
   */
  public function setSexo ($sSexo) { 
  	$this->sSexo = $sSexo; 
  }

  
  /**
   * Define o código CBO
   *
   * @param integer $iCBO
   */
  public function setCBO ($iCBO){
  	$this->iCBO = $iCBO;
  }
  
  /**
   * Retorna o código CBO
   *
   * @return string
   */
  public function getCBO (){
    return $this->iCBO;
  }  

  /**
   * Define o código do PIS
   *
   * @param string $sPIS
   */
  public function setPIS ($sPIS){
    $this->sPIS = $sPIS;
  }  
  
  /**
   * Retorna o código do PIS
   *
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
  		
  		$cl_situacao     = db_utils::getDao('cgmsituacaocpf');
  		$sSqlSituacao    = $cl_situacao->sql_query("", "*", "", "z01_numcgm = {$this->getCodigo()}");
  		$rsSituacao      = $cl_situacao->sql_record($sSqlSituacao);
      $aListaSituacao  = db_utils::getColectionByRecord($rsSituacao);
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
  	
    $oDaoCgm         = db_utils::getDao('cgm');
    $oDaoCgmCpf      = db_utils::getDao('db_cgmcpf');
    $oDaoCgmFisico   = db_utils::getDao('cgmfisico');
    $oDaoCgmJuridico = db_utils::getDao('cgmjuridico');
    
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
    
  }

  public function adicionarFamiliar($oFamiliar) {
    
    foreach ($this->aFamiliares as $oFamiliarCadastrado) {
      
      if ($oFamiliarCadastrado->iCgm == $oFamiliar->iCgm) {
       throw new Exception("Familiar já cadastrado para a familia");        
      }
    }
    $this->aFamiliares[] = $oFamiliar;
  }
  
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
    $rsFamilia      = $oDaoCgmFamilia->sql_record($sSqlFamilia);
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
  
  public  function getFamiliares() {
    
    $iCodigoFamilia = null;
    $oDaoCgmFamilia = db_utils::getDao("cgmcomposicaofamiliar");
    $sWhere         = "z15_numcgm = {$this->getCodigo()}";
    $sSqlFamilia    = $oDaoCgmFamilia->sql_query_file(null,"z15_cgmfamilia", null, $sWhere);
    $rsFamilia      = $oDaoCgmFamilia->sql_record($sSqlFamilia);
    if ($oDaoCgmFamilia->numrows > 0) {

      $oDadosFamilia  = db_utils::fieldsMemory($rsFamilia, 0);
      $iCodigoFamilia = $oDadosFamilia->z15_cgmfamilia;
    }
    
    if (!empty($iCodigoFamilia)) {
      
      $sWhere      = "z15_cgmfamilia  = {$iCodigoFamilia}";
      $sSqlFamilia = $oDaoCgmFamilia->sql_query(null,"*", null, $sWhere);
      $rsFamilia   = $oDaoCgmFamilia->sql_record($sSqlFamilia);
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

 /*
  * método responsavel por atualizar a situação do cpf
  * esse metodo é privado, pois será chamado dentro do metodo save
  * desta classe.
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
      $aListaVerSituacao         = db_utils::getColectionByRecord($rsVerSituacao);
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
  
}

?>