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
 

class Instituicao {
  
  /**
   * Código da Instituicao
   *
   * @var integer
   */
  protected $iSequencial;
  
  /**
   * Descricao da Instituicao
   *
   * @var string
   */
  protected $sDescricao;
  
  /**
   * Boolean prefeitura
   *
   * @var boolean
   */
  protected $lPrefeitura;

  /**
   * CGM da instituicao
   * @var CgmFactory
   */
  protected $oCgm;

  /**
   * Tipo de poder instituicao
   * 
   * @var integer
   * @access protected
   */
  protected $iTipo;
  
  /**
   * Código do Cliente
   * @var integer
   */
  protected $iCodigoCliente;
  
  /**
   * CNPJ
   * @var string
   */
  protected $sCNPJ;
  
  /**
   * Retorna o Município da instituição 
   * @var string
   */
  protected $sMunicipio;
  
  /**
   * Retorna o endereço da instituição
   * @var string
   */
  protected $sLogradouro;
  
  /**
   * Descrição Abreviada do Nome Prefeitura
   * @var string
   */
  protected $sDescricaoAbreviada;
  
  /**
   * Descricao do Bairro
   * @var string
   */
  protected $sBairro;
  
  /**
   * Numero Telefone da Prefeitura
   * @var string
   */
  protected $sTelefone;
  
  /**
   * Site da Prefeitura
   * @var string
   */
  protected $sSite;
  
  /**
   * Email da Prefeitura
   * @var string
   */
  protected $sEmail;
  
  /**
   * Código IBGE do Municipio
   * @var string
   */
  protected $sIbge;
  
  /**
   * Numero do CGM da prefeitura
   * @var string
   */
  protected $iNumeroCgm;

  /**
   * Retorna o Logo definido à instituição
   * @var string
   */
  protected $sImagemLogo;

  /**
   * Retorna Numero
   * @var string
   */
  protected $sNumero;
  
  /**
   * Complemento da Prefeitura
   * @var string
   */
  protected $sComplemento;
  
  /**
   * Retorna o Estado da Prefeitura
   * @var string
   */
  protected $sUf;
  
  /**
   * Retorna o Cep da Prefeitura
   * @var string
   */
  protected $sCep;
  
  /**
   * Retorna Numero do Fax
   * @var string
   */
  protected $sFax;
  
  
  /**
   * Metodo construtor
   * carrega instituicao, de acordo com o Sequencial passado
   */
  public function __construct($iSequencial = null) {
    
    if ($iSequencial != null) {
      
      $oDaoDBConfig  = db_utils::getDao("db_config");
      
      $sCampos       = "nomeinst, prefeitura, db21_tipoinstit, numcgm, cgc, munic, logo, ";
      $sCampos      .= " (select db21_codcli from db_config where prefeitura is true) as db21_codcli ";
      
      $sSqlDBConfig  = $oDaoDBConfig->sql_query_file($iSequencial, $sCampos);
      $rsDBConfig    = $oDaoDBConfig->sql_record($sSqlDBConfig);

      if ($oDaoDBConfig->numrows > 0) {
      
        $oDadoInstituicao     = db_utils::fieldsMemory($rsDBConfig, 0);
        $this->sDescricao     = $oDadoInstituicao->nomeinst;
        $this->lPrefeitura    = $oDadoInstituicao->prefeitura;
        $this->iSequencial    = $iSequencial;
        $this->iTipo          = $oDadoInstituicao->db21_tipoinstit;
        $this->oCgm           = CgmFactory::getInstanceByCgm($oDadoInstituicao->numcgm);
        $this->iCodigoCliente = $oDadoInstituicao->db21_codcli;
        $this->sCNPJ          = $oDadoInstituicao->cgc;
        $this->sMunicipio     = $oDadoInstituicao->munic;
        $this->sImagemLogo    = $oDadoInstituicao->logo;
      }
    }
    return true;   
  }
  
  /**
   * Retorna Sequencial da Inscricao
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }
  
  /**
   * Retorna codigo da instituicao
   * @return integer
   */
  public function getCodigo() {
    return $this->iSequencial;
  }

  /**
   * Retorna Descricao Instituicao
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  } 
  
  /**
   * Retorna Descricao Instituicao Abreviada
   * @return string
   */
  public function getDescricaoAbreviada() {
   return $this->sDescricaoAbreviada;
  }
  
  /**
   * Retorna boolean referente a pertencer ou nao a Prefeitura
   * @return integer
   */
  public function isPrefeitura() {
    return $this->lPrefeitura;
  }
  
  /**
   * Seta Sequencial da Inscricao
   * @param integer
   */
  public function setSequencial($iSequencial) {
    $this->iSequencial = $iSequencial;
  }
  
  /**
   * Seta Descricao Instituicao
   * @param string
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Seta boolean referente a pertencer ou nao a Prefeitura
   * @param integer
   */
  public function setPrefeitura($lPrefeitura) {
    $this->lPrefeitura = $lPrefeitura;
  }
  
  /**
   * Retorna o objeto CGM da instituicao
   * @return CgmFactory
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * Retorna o tipo de poder que a instituição pertence
   *
   * @access public
   * @return integer
   */
  public function getTipo () {
    return $this->iTipo;
  }

  /**
   * Define o tipo de poder que a instituição pertence
   * @param integer $iTipo
   * @access public
   * @return void
   */
  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }

  /**
   * Retorna o código do cliente
   * @return integer
   */
  public function getCodigoCliente() {
  
    return $this->iCodigoCliente;
  }

  /**
   * CNPJ da Instituição
   */
  public function getCNPJ() {
  
    return $this->sCNPJ;
  }
  
  /**
   * Retorna o Município da Instituição
   * @return string
   */
  public function getMunicipio() {
    
    return $this->sMunicipio;
  }
  
  /**
   * Retorna o logo da prefeitura
   * @return string
   */
  public function getImagemLogo() {
    
    return $this->sImagemLogo;
  }
  
  /**
   * Retorna Logradouro da Prefeitura
   * @return string
   */
  public function getLogradouro() {
  
   return $this->sLogradouro;
  }

  /**
   * Retorna o Bairro da Prefeitura
   * @return string
   */
  public function getBairro() {
    return $this->sBairro;
  }
  
  /**
   * Retorna o Telefone
   * @return string
   */
  public function getTelefone() {
   return $this->sTelefone;
  }
  
  /**
   * Retorna o Site da Prefeitura
   * @return string
   */
  public function getSite() {
   return $this->sSite;
  }
  
  /**
   * Retorna o Email
   * @return string
   */
  public function getEmail() {
   return $this->sEmail;
  }
  
  /**
   * Retorna o Bairro da Prefeitura
   * @return string
   */
  public function getCodigoIbge() {
   return $this->sIbge;
  }
  
  /**
   * Retorna o Numero do Cgm
   * @return integer
   */
  public function getNumeroCgm() {
   return $this->iNumeroCgm;
  }
  
  /**
   * Retorna o Numero do Predio da prefeitura
   * @return string
   */
  public function getNumero() {
   return $this->sNumero;
  }
  
  /**
   * Retorna complemento da prefeitura
   * @return string
   */
  public function getComplemento() {
   return $this->sComplemento;
  }
  
  /**
   * Retorna o estado em que a prefeitura pertence
   * @return string
   */
  public function getUf() {
   return $this->sUf;
  }
  
  /**
   * Retorna o Cep da prefeitura
   * @return string
   */
  public function getCep() {
    return $this->sCep;
  }
  
  /**
   * Retorna o numero do Fax
   * @return string
   */
  public function getFax() {
   return $this->sFax;
  }
  
  
  /**
   * Retorna a Instituição por tipo
   * @return string
   */
  public function getDadosPrefeitura() {
  
    $oDaoDBConfig  = new cl_db_config();
    
    $sCampos       = "nomeinst, db21_tipoinstit, numcgm, cgc, munic, logo, nomeinstabrev, ender, munic, ";
    $sCampos      .= "bairro, telef, url, email, db21_codigomunicipoestado, numero, db21_compl, uf, cep, fax ";
    $sSqlDBConfig  = $oDaoDBConfig->sql_query_file(null, $sCampos, null, "prefeitura is true");
    
    $rsDBConfig    = $oDaoDBConfig->sql_record($sSqlDBConfig);
    
    $oRetorno = new StdClass();
    
    if ($oDaoDBConfig->numrows > 0) {
      
      $oDadoInstituicao           = db_utils::fieldsMemory($rsDBConfig, 0);
      $this->sDescricao           = $oDadoInstituicao->nomeinst;
      $this->sDescricaoAbreviada  = $oDadoInstituicao->nomeinstabrev;
      $this->sCNPJ                = $oDadoInstituicao->cgc;
      $this->sLogradouro          = $oDadoInstituicao->ender;
      $this->sMunicipio           = $oDadoInstituicao->munic;
      $this->sBairro              = $oDadoInstituicao->bairro;
      $this->sTelefone            = $oDadoInstituicao->telef;
      $this->sSite                = $oDadoInstituicao->url;
      $this->sEmail               = $oDadoInstituicao->email;
      $this->iNumeroCgm           = $oDadoInstituicao->numcgm;
      $this->sImagemLogo          = $oDadoInstituicao->logo;
      $this->sNumero              = $oDadoInstituicao->numero;
      $this->sComplemento         = $oDadoInstituicao->db21_compl;
      $this->sUf                  = $oDadoInstituicao->uf;
      $this->sCep                 = $oDadoInstituicao->cep;
      $this->sFax                 = $oDadoInstituicao->fax;
      
      $oDaoMunicipio = new cl_cadendermunicipiosistema();
      
      $sWhere  = "db72_descricao = '{$oDadoInstituicao->munic}'";
      $sWhere .= " AND  db125_db_sistemaexterno = 4";
      
      $sSqlMunicipio        = $oDaoMunicipio->sql_query(null, 'db125_codigosistema', null, $sWhere);
      
      $rsCodigoIbgeMunicipio = $oDaoMunicipio->sql_record($sSqlMunicipio);
      $this->sIbge                = db_utils::fieldsMemory($rsCodigoIbgeMunicipio, 0)->db125_codigosistema;
    }
    
    return $this;
  }
}
?>