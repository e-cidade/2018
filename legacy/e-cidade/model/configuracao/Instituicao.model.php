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
 * Class Instituicao
 */
class Instituicao {

  /**
   * Caminho das mensagens
   * @type string
   */
  const MENSAGEM = 'configuracao.configuracao.Instituicao.';

  const TIPO_PREFEITURA                     = 1;
  const TIPO_CAMARA                         = 2;
  const TIPO_SECRETARIA_DA_EDUCACAO         = 3;
  const TIPO_SECRETARIA_DA_SAUDE            = 4;
  const TIPO_RPPS_EXCETO_AUTARQUIA          = 5;
  const TIPO_AUTARQUIA_RPPS                 = 6;
  const TIPO_AUTARQUIA_EXCETO_RPPS          = 7;
  const TIPO_FUNDACAO                       = 8;
  const TIPO_EMPRESA_ESTATAL_DEPENDENTE     = 9;
  const TIPO_EMPRESA_ESTATAL_NAO_DEPENDENTE = 10;
  const TIPO_CONSORCIO                      = 11;
  const TIPO_OUTRAS                         = 12;
  const TIPO_MINISTERIO_PUBLICO_ESTADUAL    = 101;
  const TIPO_TRIBUNAL_DE_JUSTICA            = 13;
  const TIPO_TRIBUNAL_DE_CONTAS_ESTADO      = 14;


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
   * Código sequencial do CGM
   * @var integer
   */
  protected $iCodigoCGM;

  /**
   * @var boolean
   */
  protected $lUsaSisagua;


  /**
   * Instituicao constructor.
   * @param null $iSequencial
   * @throws DBException
   */
  public function __construct($iSequencial = null) {

    if ($iSequencial != null) {

      $oDaoDBConfig  = db_utils::getDao("db_config");

      $sCampos       = "nomeinst, prefeitura, db21_tipoinstit, numcgm, cgc, munic, logo, nomeinstabrev, ender, numero,";
      $sCampos      .= "telef, url, uf, db21_compl, email, db21_usasisagua, ";
      $sCampos      .= " (select db21_codcli from db_config where prefeitura is true) as db21_codcli ";

      $sSqlDBConfig  = $oDaoDBConfig->sql_query_file($iSequencial, $sCampos);
      $rsDBConfig    = $oDaoDBConfig->sql_record($sSqlDBConfig);
      if (!$rsDBConfig || $oDaoDBConfig->erro_status == "0") {
        throw new DBException(_M(self::MENSAGEM . 'instituicao_nao_encontrada'));
      }

      if ($oDaoDBConfig->numrows > 0) {

        $oDadoInstituicao     = db_utils::fieldsMemory($rsDBConfig, 0);
        $this->sDescricao     = $oDadoInstituicao->nomeinst;
        $this->lPrefeitura    = $oDadoInstituicao->prefeitura;
        $this->iSequencial    = $iSequencial;
        $this->iTipo          = $oDadoInstituicao->db21_tipoinstit;
        $this->iCodigoCGM     = $oDadoInstituicao->numcgm;
        $this->iCodigoCliente = $oDadoInstituicao->db21_codcli;
        $this->sCNPJ          = $oDadoInstituicao->cgc;
        $this->sMunicipio     = $oDadoInstituicao->munic;
        $this->sEmail         = $oDadoInstituicao->email;
        $this->sSite          = $oDadoInstituicao->url;
        $this->sImagemLogo    = $oDadoInstituicao->logo;
        $this->sLogradouro    = $oDadoInstituicao->ender;
        $this->sUf            = $oDadoInstituicao->uf;
        $this->sNumero        = $oDadoInstituicao->numero;
        $this->sComplemento   = $oDadoInstituicao->db21_compl;
        $this->sTelefone      = $oDadoInstituicao->telef;
        $this->sDescricaoAbreviada = $oDadoInstituicao->nomeinstabrev;
        $this->lUsaSisagua         = $oDadoInstituicao->db21_usasisagua == 't';
      }
    }
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
   * @deprecated
   * @see prefeitura
   */
  public function isPrefeitura() {
    return $this->lPrefeitura;
  }

  /**
   * @return bool
   */
  public function prefeitura() {
    return $this->lPrefeitura == 't';
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
   * @return CgmFisico|CgmJuridico
   */
  public function getCgm() {

    if (!empty($this->iCodigoCGM)) {
      $this->oCgm = CgmFactory::getInstanceByCgm($this->iCodigoCGM);
    }
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
   * @throws DBException
   */
  public function getDadosPrefeitura() {

    $oDaoDBConfig  = new cl_db_config();

    $sCampos       = "nomeinst, db21_tipoinstit, numcgm, cgc, munic, logo, nomeinstabrev, ender, munic, ";
    $sCampos      .= "bairro, telef, url, email, db21_codigomunicipoestado, numero, db21_compl, uf, cep, fax, db21_codcli ";
    $sSqlDBConfig  = $oDaoDBConfig->sql_query_file(null, $sCampos, null, "prefeitura is true");

    $rsDBConfig    = $oDaoDBConfig->sql_record($sSqlDBConfig);
    if (!$rsDBConfig || $oDaoDBConfig->erro_status == "0") {
      throw new DBException(_M(self::MENSAGEM . 'instituicao_nao_encontrada'));
    }

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
      $this->iCodigoCliente       = $oDadoInstituicao->db21_codcli;

      $oDaoMunicipio = new cl_cadendermunicipiosistema();

      $sWhere  = "     to_ascii(db72_descricao, 'LATIN1') = '{$oDadoInstituicao->munic}' ";
      $sWhere .= " and db71_sigla                         = '{$oDadoInstituicao->uf}'    ";
      $sWhere .= " and db125_db_sistemaexterno            = 4                            ";

      $sSqlMunicipio        = $oDaoMunicipio->sql_query(null, 'db125_codigosistema', null, $sWhere);
      $rsCodigoIbgeMunicipio = $oDaoMunicipio->sql_record($sSqlMunicipio);
      if ( $rsCodigoIbgeMunicipio && pg_num_rows( $rsCodigoIbgeMunicipio ) > 0 ) {
        $this->sIbge           = db_utils::fieldsMemory($rsCodigoIbgeMunicipio, 0)->db125_codigosistema;
      }
    }

    return $this;
  }

  /**
   * @return boolean
   */
  public function getUsaSisagua() {
    return $this->lUsaSisagua;
  }

  /**
   * @param boolean $lUsaSisagua
   */
  public function setUsaSisagua($lUsaSisagua) {
    $this->lUsaSisagua = $lUsaSisagua;
  }
}