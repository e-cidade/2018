<?php
/**
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
define("ARQUIVO_MENSAGEM_CGS", "saude.ambulatorial.Cgs.");

/**
 * Classe para controle geral dos pacientes da
 * area DB:Saude
 * @package Ambulatorial
 * @version $Revision: 1.41 $
 */
class Cgs {

  /**
   * Codigo do cgs
   * @var integer
   */
  protected $iCodigo = null;

  /**
   * Nome do CGS
   * @var string
   */
  protected $sNome;

  /**
   * Sexo do paciente
   * @var string
   */
  protected $sSexo;

  /**
   * Data de Nascimento
   * @var DBDate
   */
  protected $oDataNascimento;

  /**
   * Nome da Mãe do paciente
   * @var string
   */
  protected $sNomeMae;

  /**
   * Endereço do paciente
   * @var string
   */
  protected $sEndereco;

  /**
   * Número do endereço do paciente
   * @var integer
   */
  protected $iNumero;

  /**
   * Complemento do endereço do paciente
   * @var string
   */
  protected $sComplemento;

  /**
   * Bairro do paciente
   * @var string
   */
  protected $sBairro;

  /**
   * CEP do paciente
   * @var string
   */
  protected $sCep;

  /**
   * Município do paciente
   * @var string
   */
  protected $sMunicipio;

  /**
   * UF do paciente
   * @var string
   */
  protected $sUF;

  /**
   * Código referente ao estado civil do paciente
   * @var integer
   */
  protected $iEstadoCivil;

  /**
   * Estados civis indexados pelo seu código
   * @var array
   */
  protected $aEstadosCivis = array( 1 => "SOLTEIRO",
                                    2 => "CASADO",
                                    3 => "VIÚVO",
                                    4 => "SEPARADO",
                                    5 => "UNIÃO C.",
                                    9 => "IGNORADO"
                                  );

  /**
   * Identidade do paciente
   * @var string
   */
  protected $sIdentidade = '';

  /**
   * CPF do paciente
   * @var string
   */
  protected $sCpf = '';

  /**
   * Número de Telefone do paciente
   * @var string
   */
  protected $sTelefone;

  /**
   * Número de Celular do paciente
   * @var string
   */
  protected $sCelular;

  /**
   * Requisições de exames realizadas pelo cgs
   * @var RequisicaoExame[]
   */
  protected $aRequisicaoExame = array();

  protected $iOid;

  /**
   * Email do paciente
   * @var string
   */
  protected $sEmail;

  /**
   * Raça do paciente
   * @var string
   */
  protected $sRaca;

  /**
   * Número do PIS do paciente
   * @var string
   */
  protected $sPis;

  /**
   * Código da naturalidade/nacionalidade do paciente
   * @var int
   */
  protected $iNaturalidade;

  /**
   * Código do país de origem do paciente
   * @var int
   */
  protected $iPaisOrigem;

  /**
   * Município de nascimento do paciente
   * @var string
   */
  protected $sMunicipioNascimento;

  /**
   * UF de nascimento do paciente
   * @var string
   */
  protected $sUfNascimento;

  /**
   * Código IBGE do município de nascimento
   * @var string
   */
  protected $sIbgeNascimento;

  /**
   * Código da escolaridade do paciente
   * @var int
   */
  protected $iEscolaridade;

  /**
   * Controla se o paciente desconhece a mãe
   * @var boolean
   */
  protected $lDesconheceMae;

  /**
   * Instancia um novo CGs
   */
  function __construct($iCgs = null) {

    if (!empty($iCgs)) {

      $oDaoCgs      = new cl_cgs_und();
      $sSqlDadosCGS = $oDaoCgs->sql_query_cadastro($iCgs);
      $rsDadosCGS   = db_query($sSqlDadosCGS);

      if ( !$rsDadosCGS ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M(ARQUIVO_MENSAGEM_CGS . "erro_buscar_cgs", $oErro) );
      }

      if (pg_num_rows($rsDadosCGS) > 0) {

        $oDadosCGS = db_utils::fieldsMemory($rsDadosCGS, 0);
        $this->setCodigo($iCgs);
        $this->setNome($oDadosCGS->z01_v_nome);
        $this->setSexo($oDadosCGS->z01_v_sexo);

        if (!empty($oDadosCGS->z01_d_nasc)) {
          $this->setDataNascimento(new DBDate($oDadosCGS->z01_d_nasc));
        }

        $this->setNomeMae($oDadosCGS->z01_v_mae);
        $this->setEndereco($oDadosCGS->z01_v_ender);
        $this->setNumero($oDadosCGS->z01_i_numero);
        $this->setComplemento($oDadosCGS->z01_v_compl);
        $this->setBairro($oDadosCGS->z01_v_bairro);
        $this->setCep($oDadosCGS->z01_v_cep);
        $this->setMunicipio($oDadosCGS->z01_v_munic);
        $this->setUF($oDadosCGS->z01_v_uf);
        $this->setEstadoCivil($oDadosCGS->z01_i_estciv);
        $this->setIdentidade( $oDadosCGS->z01_v_ident);
        $this->setCpf( $oDadosCGS->z01_v_cgccpf );
        $this->setTelefone( $oDadosCGS->z01_v_telef );
        $this->setCelular( $oDadosCGS->z01_v_telcel );
        $this->setOid( $oDadosCGS->z01_o_oid );
        $this->setEmail($oDadosCGS->z01_v_email);
        $this->setRaca($oDadosCGS->z01_c_raca);
        $this->setPis($oDadosCGS->z01_c_pis);
        $this->setNaturalidade($oDadosCGS->z01_i_naturalidade);
        $this->setPaisOrigem($oDadosCGS->z01_i_paisorigem);
        $this->setMunicipioNascimento($oDadosCGS->z01_v_municnasc);
        $this->setUfNascimento($oDadosCGS->z01_v_ufnasc);
        $this->setIbgeNascimento($oDadosCGS->z01_codigoibgenasc);
        $this->setEscolaridade($oDadosCGS->z01_i_escolaridade);
        $this->desconheceMae($oDadosCGS->z01_b_descnomemae == 't');
      }
    }
  }

  /**
   * Retorna o codigo de cadastro do paciente
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Código do paciente
   * @param integer $iCodigo
   */
  protected function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o nome do paciente
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * seta o nome do paciente
   * @param string $sNome define o nome do paciente
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Retorna o sexo do paciente
   * @return string
   */
  public function getSexo() {
    return $this->sSexo;
  }

  /**
   * Define o sexo do paciente
   * @param string $sSexo Sexo do paciente
   */
  public function setSexo($sSexo) {
    $this->sSexo = $sSexo;
  }

  /**
   * Define a Data de nascimento
   * @param DBDate $oDataNascimento
   */
  public function setDataNascimento( DBDate $oDataNascimento) {
    $this->oDataNascimento = $oDataNascimento;
  }

  /**
   * Retorna a data de nascimento do paciente
   * @return DBDate
   */
  public function getDataNascimento() {
    return $this->oDataNascimento;
  }

  /**
   * Retorna todos os cartões sus do CGS
   * @return array
   * @throws DBException
   */
  public function getCartaoSus() {

    $sCamposCartaoSus  = "s115_i_codigo, s115_c_cartaosus, s115_c_tipo";
    $sWhereCartaoSus   = "     s115_i_cgs = {$this->iCodigo}";
    $oDaoCgsCartaoSus  = new cl_cgs_cartaosus();
    $sSqlCartaoSus     = $oDaoCgsCartaoSus->sql_query_file(null, $sCamposCartaoSus, null, $sWhereCartaoSus);
    $rsCartaoSus       = db_query( $sSqlCartaoSus );

    if ( !$rsCartaoSus ) {

      $oErro        = new stdClass();
      $oErro->sErro = $oDaoCgsCartaoSus->erro_msg;
      throw new DBException( _M(ARQUIVO_MENSAGEM_CGS . "erro_buscar_cns", $oErro) );
    }

    $iLinhasCartaoSus = pg_num_rows( $rsCartaoSus );
    $aCartaoSus       = array();

    if ( $iLinhasCartaoSus > 0 ) {

      for( $iContador = 0; $iContador < $iLinhasCartaoSus; $iContador++ ) {

        $oDadosCartaoSus = db_utils::fieldsMemory( $rsCartaoSus, $iContador );

        $oCartaoSus                 = new stdClass();
        $oCartaoSus->iCodigo        = $oDadosCartaoSus->s115_i_codigo;
        $oCartaoSus->sCartaoSus     = $oDadosCartaoSus->s115_c_cartaosus;
        $oCartaoSus->sTipoCartaoSus = $oDadosCartaoSus->s115_c_tipo;

        $aCartaoSus[] = $oCartaoSus;
      }
    }

    return $aCartaoSus;
  }

  /**
   * Retorna o cartão SUS Definitivo, ou o último Provisório do CGS
   * @return string
   */
  public function getCartaoSusAtivo() {

    $sCartaoSus = '';

    foreach( $this->getCartaoSus() as $oCartaoSus ) {

      $sCartaoSus = $oCartaoSus->sCartaoSus;
      if( $oCartaoSus->sTipoCartaoSus == 'D' ) {
        break;
      }
    }

    return $sCartaoSus;
  }

  /**
   * Retorna o nome da mãe do paciente
   * @return string
   */
  public function getNomeMae() {
    return $this->sNomeMae;
  }

  /**
   * Define o nome da mãe do paciente
   * @param string $sNomeMae
   */
  public function setNomeMae( $sNomeMae ) {
    $this->sNomeMae = $sNomeMae;
  }

  /**
   * Define o enderço do paciente
   * @param string $sEndereco
   */
  public function setEndereco( $sEndereco ) {
    $this->sEndereco = $sEndereco;
  }

  /**
   * Retorna o endereço do paciente
   * @return string
   */
  public function getEndereco() {
    return $this->sEndereco;
  }

  /**
   * Define o número do endereço do paciente
   * @param integer $iNumero
   */
  public function setNumero( $iNumero ) {
    $this->iNumero = $iNumero;
  }

  /**
   * Retorna o número do endereço do paciente
   * @return integer
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * Define o complemento do endereço do paciente
   * @param string $sComplemento
   */
  public function setComplemento( $sComplemento ) {
    $this->sComplemento = $sComplemento;
  }

  /**
   * Retorna o complemento do endereço do paciente
   * @return string
   */
  public function getComplemento() {
    return $this->sComplemento;
  }

  /**
   * Define o bairro do paciente
   * @param string $sBairro
   */
  public function setBairro( $sBairro ) {
    $this->sBairro = $sBairro;
  }

  /**
   * Retorna o bairro do paciente
   * @return string
   */
  public function getBairro() {
    return $this->sBairro;
  }

  /**
   * Define o CEP do paciente
   * @param string $sCep
   */
  public function setCep( $sCep ) {
    $this->sCep = $sCep;
  }

  /**
   * Retorna o CEP do paciente
   * @return string
   */
  public function getCep() {
    return $this->sCep;
  }

  /**
   * Define o município do paciente
   * @param string $sMunicipio
   */
  public function setMunicipio( $sMunicipio ) {
    $this->sMunicipio = $sMunicipio;
  }

  /**
   * Retorna o município do paciente
   * @return string
   */
  public function getMunicipio() {
    return $this->sMunicipio;
  }

  /**
   * Define a UF do paciente
   * @param string $sUF
   */
  public function setUF( $sUF ) {
    $this->sUF = $sUF;
  }

  /**
   * Retorna a UF do paciente
   * @return string
   */
  public function getUF() {
    return $this->sUF;
  }

  /**
   * Retorna o estado cívil do paciente
   * @return string
   */
  public function getEstadoCivil() {
    return $this->aEstadosCivis[$this->iEstadoCivil];
  }

  /**
   * Define o estado civil do paciente
   * @param integer $iEstadoCivil
   */
  public function setEstadoCivil( $iEstadoCivil ) {
    $this->iEstadoCivil = $iEstadoCivil;
  }

  /**
   * Retorna o número de identidade do paciente
   * @return string
   */
  public function getIdentidade() {
    return $this->sIdentidade;
  }

  /**
   * Define o número de identidade do paciente
   * @param string $sIdentidade
   */
  public function setIdentidade( $sIdentidade ) {
    $this->sIdentidade = $sIdentidade;
  }

  /**
   * Retorna o CPF do paciente
   * @return string
   */
  public function getCpf() {
    return $this->sCpf;
  }

  /**
   * Define o CPF do paciente
   * @param string $sCpf
   */
  public function setCpf( $sCpf ) {
    $this->sCpf = $sCpf;
  }

  /**
   * Retorna o telefone do paciente
   * @return string
   */
  public function getTelefone() {
    return $this->sTelefone;
  }

  /**
   * Define o telefone do paciente
   * @param string $sTelefone
   */
  public function setTelefone ( $sTelefone ) {
    $this->sTelefone = $sTelefone;
  }

  /**
   * Retorna o celular do paciente
   * @return string
   */
  public function getCelular() {
    return $this->sCelular;
  }

  /**
   * Define o celular do paciente
   * @param string $sCelular
   */
  public function setCelular ( $sCelular ) {
    $this->sCelular = $sCelular;
  }

  /**
   * Retorna as requisições de exame do cgs
   *
   * @return RequisicaoExame[]|array
   * @throws BusinessException
   */
  public function getRequisicoesExame() {

    if ( count($this->aRequisicaoExame) == 0 ) {

      $sWhere         = " la22_i_cgs = {$this->iCodigo} ";
      $sOrder         = " la22_d_data desc ";
      $oDaoRequisicao = new cl_lab_requisicao();
      $sSqlRequisicao = $oDaoRequisicao->sql_query_file(null, "la22_i_codigo", $sOrder, $sWhere );

      $rsRequisicao   = db_query($sSqlRequisicao);

      if ( !$rsRequisicao ) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new BusinessException( _M(ARQUIVO_MENSAGEM_CGS . "erro_buscar_requisicoes", $oMsgErro) );
      }

      $iLinhas = pg_num_rows($rsRequisicao);
      for ($i = 0; $i < $iLinhas; $i++) {

        $oDadosRequisicao         = db_utils::fieldsMemory($rsRequisicao, $i);
        $this->aRequisicaoExame[] = new RequisicaoLaboratorial( $oDadosRequisicao->la22_i_codigo );
      }
    }

    return $this->aRequisicaoExame;
  }

  public function setOid( $iOid ) {
    $this->iOid = $iOid;
  }

  /**
   * Retorn o email do paciente
   * @return string
   */
  public function getEmail() {
    return $this->sEmail;
  }

  /**
   * Seta o email do paciente
   * @param string $sEmail
   */
  public function setEmail($sEmail) {
    $this->sEmail = $sEmail;
  }

  /**
   * Retorna a raça do paciente
   * @return string
   */
  public function getRaca() {
    return $this->sRaca;
  }

  /**
   * Seta a raça do paciente
   * @param string $sRaca
   */
  public function setRaca($sRaca) {
    $this->sRaca = $sRaca;
  }

  /**
   * Retorna o número do PIS do paciente
   * @return string
   */
  public function getPis() {
    return $this->sPis;
  }

  /**
   * Seta o número do PIS do paciente
   * @param string $sPis
   */
  public function setPis($sPis) {
    $this->sPis = $sPis;
  }

  /**
   * Retorna a naturalidade/nacionalidade do paciente
   * @return int
   */
  public function getNaturalidade() {
    return $this->iNaturalidade;
  }

  /**
   * Seta o código da naturalidade/nacionalidade do paciente
   * @param int $iNaturalidade
   */
  public function setNaturalidade($iNaturalidade) {
    $this->iNaturalidade = $iNaturalidade;
  }

  /**
   * Retorna o código do país de origem do paciente
   * @return int
   */
  public function getPaisOrigem() {
    return $this->iPaisOrigem;
  }

  /**
   * Seta o código do país de origem do paciente
   * @param int $iPaisOrigem
   */
  public function setPaisOrigem($iPaisOrigem) {
    $this->iPaisOrigem = $iPaisOrigem;
  }

  /**
   * Retorna o município de nascimento do paciente
   * @return string
   */
  public function getMunicipioNascimento() {
    return $this->sMunicipioNascimento;
  }

  /**
   * Seta o município de nascimento do paciente
   * @param string $sMunicipioNascimento
   */
  public function setMunicipioNascimento($sMunicipioNascimento) {
    $this->sMunicipioNascimento = $sMunicipioNascimento;
  }

  /**
   * Retorna a UF de nascimento do paciente
   * @return string
   */
  public function getUfNascimento() {
    return $this->sUfNascimento;
  }

  /**
   * Seta a UF de nascimento do paciente
   * @param string $sUfNascimento
   */
  public function setUfNascimento($sUfNascimento) {
    $this->sUfNascimento = $sUfNascimento;
  }

  /**
   * Retorna o código IBGE referente ao município de nascimento
   * @return string
   */
  public function getIbgeNascimento() {
    return $this->sIbgeNascimento;
  }

  /**
   * Seta o código IBGE referente ao município de nascimento
   * @param string $sIbgeNascimento
   */
  public function setIbgeNascimento($sIbgeNascimento) {
    $this->sIbgeNascimento = $sIbgeNascimento;
  }

  /**
   * Retorna o código da escolaridade
   * @return int
   */
  public function getEscolaridade() {
    return $this->iEscolaridade;
  }

  /**
   * Seta o código da escolaridade
   * @param string $iEscolaridade
   */
  public function setEscolaridade($iEscolaridade) {
    $this->iEscolaridade = $iEscolaridade;
  }

  /**
   * Retorna se a mãe é desconhecida
   * @return bool
   */
  public function desconheceMae() {
    return $this->lDesconheceMae;
  }

  /**
   * Informa se a mãe é desconhecida
   * @param boolean $lDesconheceMae
   */
  public function setDesconheceMae($lDesconheceMae) {
    $this->lDesconheceMae = $lDesconheceMae;
  }

  /**
   * Retorna os documentos vinculados ao CGS( caddocumento|documento )
   * @return DocumentoBase[]|null
   * @throws DBException
   */
  public function getDocumentos() {

    if( is_null( $this->iCodigo ) ) {
      return array();
    }

    return DocumentoBaseRepository::getDocumentosBaseCgsDocumento( $this );
  }

  /**
   * Salva o vinculo de um documento com o CGS
   *
   * @param  int $iDocumento
   * @throws DBException
   */
  public function salvarCgsDocumento( $iDocumento ) {

    $oDaoCgsDocumento                  = new cl_cgs_unddocumento();
    $oDaoCgsDocumento->sd108_cgs_und   = $this->iCodigo;
    $oDaoCgsDocumento->sd108_documento = $iDocumento;
    $oDaoCgsDocumento->incluir(null);

    if( $oDaoCgsDocumento->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_last_error();

      throw new DBException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_vincular_documento', $oMensagem ) );
    }
  }

  /**
   * Salva os dados da tabela cgs
   * @param $oDadosCgs
   * @throws BusinessException
   * @throws DBException
   */
  public function salvarCgsPadrao( $oDadosCgs ) {

    $oDaoCgs = new cl_cgs();

    if( trim( $oDadosCgs->dados_pessoais->cns ) != '' ) {

      $sWhereValidaCgs  = "z01_c_cartaosus = '{$oDadosCgs->dados_pessoais->cns}'";

      if( !is_null( $this->iCodigo ) ) {
        $sWhereValidaCgs .= " AND z01_i_numcgs <> {$this->iCodigo}";
      }

      $sSqlValidaCgs = $oDaoCgs->sql_query_file( null, '1', null, $sWhereValidaCgs );
      $rsValidaCgs   = db_query( $sSqlValidaCgs );

      if( !is_resource( $rsValidaCgs ) ) {
        throw new DBException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_validar_cgs' ) );
      }

      if( pg_num_rows( $rsValidaCgs ) > 0 ) {
        throw new BusinessException( _M( ARQUIVO_MENSAGEM_CGS . 'cartao_existente' ) );
      }
    }

    $oDaoCgs->z01_i_tiposangue = $oDadosCgs->dados_pessoais->tipoSangue;
    $oDaoCgs->z01_i_fatorrh    = $oDadosCgs->dados_pessoais->fatorRH;
    $oDaoCgs->z01_c_cartaosus  = $oDadosCgs->dados_pessoais->cns;
    $oDaoCgs->z01_v_familia    = '';
    $oDaoCgs->z01_v_microarea  = '';
    $oDaoCgs->z01_c_municipio  = '';

    if( isset( $this->iCodigo ) ) {

      $oDaoCgs->z01_i_numcgs = $this->iCodigo;
      $oDaoCgs->alterar( $this->iCodigo );
    } else {
      $oDaoCgs->incluir( null );
    }

    if( $oDaoCgs->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_last_error();

      throw new DBException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_salvar_cgs_padrao', $oMensagem ) );
    }

    $this->iCodigo = $oDaoCgs->z01_i_numcgs;
  }

  /**
   * Salva os dados da tabela cgs_cartaosus
   * @param $oDadosCgs
   * @throws BusinessException
   * @throws DBException
   */
  public function salvarCgsCartaoSus( $oDadosCgs ) {

    $oDaoCgsCartaoSus = new cl_cgs_cartaosus();
    $sCns             = trim( $oDadosCgs->dados_pessoais->cns );

    /**
     * Quando não houver CNS, não inclui nenhum registro
     */
    if($sCns == '') {
      return;
    }

    if($sCns != '' && $sCns == $this->getCartaoSusAtivo()) {
      return;
    }

    /**
     * Valida se o cartão informado não encontra-se vinculado a outro CGS
     */
    $sWhereValidaCartao = "s115_c_cartaosus = '{$oDadosCgs->dados_pessoais->cns}'";

    if( !is_null( $this->iCodigo ) ) {
      $sWhereValidaCartao .= " AND s115_i_cgs <> {$this->iCodigo}";
    }

    $sSqlValidaCartao = $oDaoCgsCartaoSus->sql_query_file( null, '1', null, $sWhereValidaCartao );
    $rsValidaCartao   = db_query( $sSqlValidaCartao );

    if( !is_resource( $rsValidaCartao ) ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_last_error();

      throw new DBException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_validar_cartao', $oMensagem ) );
    }

    if( pg_num_rows( $rsValidaCartao ) > 0 ) {
      throw new BusinessException( _M( ARQUIVO_MENSAGEM_CGS . 'cartao_existente' ) );
    }

    $iSequencialCartaoDefinitivo = $this->retornaSequencialCartaoDefinitivo();
    $sAcao                       = $iSequencialCartaoDefinitivo == null ? 'incluir' : 'alterar';

    /**
     * Inclui um novo registro quando há um CNS informado, e este não está vinculado a nenhum outro CGS
     */
    $oDaoCgsCartaoSus->s115_c_cartaosus = $oDadosCgs->dados_pessoais->cns;
    $oDaoCgsCartaoSus->s115_c_tipo      = 'D';
    $oDaoCgsCartaoSus->s115_i_entrada   = 1;
    $oDaoCgsCartaoSus->s115_i_cgs       = $this->iCodigo;
    $oDaoCgsCartaoSus->s115_i_codigo    = $iSequencialCartaoDefinitivo;
    $oDaoCgsCartaoSus->{$sAcao}($iSequencialCartaoDefinitivo);

    if( $oDaoCgsCartaoSus->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoCgsCartaoSus->erro_msg;

      throw new DBException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_salvar_cartao_sus', $oMensagem ) );
    }
  }

  /**
   * Salva os dados da tabela cgs_undendereco
   * @param $oDadosCgs
   * @throws DBException
   * @throws ParameterException
   */
  public function salvarCgsEndereco( $oDadosCgs ) {

    $oDaoCgsEndereco = new cl_cgs_undendereco();

    if( !empty( $this->iCodigo ) ) {

      $oDaoCgsEndereco->excluir( null, "sd109_cgs_und = {$this->iCodigo}" );

      if( $oDaoCgsEndereco->erro_status == "0" ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_last_error();

        throw new DBException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_excluir_endereco' ) );
      }
    }

    if( empty( $oDadosCgs->contato->endereco_principal ) ) {
      throw new ParameterException( _M( ARQUIVO_MENSAGEM_CGS . 'endereco_nao_informado' ) );
    }

    $oDaoCgsEndereco->sd109_cgs_und  = $this->iCodigo;
    $oDaoCgsEndereco->sd109_endereco = $oDadosCgs->contato->endereco_principal;
    $oDaoCgsEndereco->incluir( null );

    if( $oDaoCgsEndereco->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_last_error();

      throw new DBException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_incluir_endereco' ) );
    }
  }

  /**
   * Salva os dados da tabela cgs_undetnia
   * @param $oDadosCgs
   * @throws DBException
   */
  public function salvarCgsEtnia( $oDadosCgs ) {

    $oDaoCgsEtnia = new cl_cgs_undetnia();
    $oDaoCgsEtnia->excluir( null, "s201_cgs_unid = {$this->iCodigo}" );

    if( !empty( $oDadosCgs->dados_pessoais->codigo_etnia ) ) {

      $oDaoCgsEtnia->s201_cgs_unid = $this->iCodigo;
      $oDaoCgsEtnia->s201_etnia    = $oDadosCgs->dados_pessoais->codigo_etnia;
      $oDaoCgsEtnia->incluir( null );

      if( $oDaoCgsEtnia->erro_status == "0" ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_last_error();

        throw new DBException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_incluir_etnia' ) );
      }
    }
  }

  /**
   * Responsável por salvar todas as informações referentes ao cgs.
   * Aplicado com base no novo cadastro do CGS, onde os dados estão separados conforme grupos de informações( abas )
   *
   * @param  stdClass $oDadosCgs
   * @throws BusinessException
   * @throws DBException
   */
  public function salvar( $oDadosCgs ) {

    $lCgsExistente = false;

    if( !is_null( $this->iCodigo ) ) {
      $lCgsExistente = true;
    }

    $this->salvarCgsPadrao( $oDadosCgs );

    $oDaoCgsUnd      = new cl_cgs_und();
    $oDataNascimento = new DBDate( $oDadosCgs->dados_pessoais->dataNascimento );

    $oDaoCgsUnd->z01_i_cgsund      = $this->iCodigo;
    $oDaoCgsUnd->z01_d_falecimento = 'null';
    $oDaoCgsUnd->z01_b_faleceu     = 'false';
    $oDaoCgsUnd->z01_b_inativo     = $oDadosCgs->dados_pessoais->cadastroInativo === true ? 'true' : 'false';

    if( !empty( $oDadosCgs->dados_pessoais->dataObito ) ) {

      $oDataObito                    = new DBDate( $oDadosCgs->dados_pessoais->dataObito );
      $oDaoCgsUnd->z01_d_falecimento = $oDataObito->getDate();
      $oDaoCgsUnd->z01_b_faleceu     = $oDadosCgs->dados_pessoais->dataObito != '' ? 'true' : 'false';
    }

    /**
     * DADOS PESSOAIS
     */
    $oDaoCgsUnd->z01_v_nome            = $oDadosCgs->dados_pessoais->nome;
    $oDaoCgsUnd->z01_v_mae             = $oDadosCgs->dados_pessoais->nomeMae;
    $oDaoCgsUnd->z01_v_pai             = $oDadosCgs->dados_pessoais->nomePai == '' ? 'SEM INFORMAÇÃO' : $oDadosCgs->dados_pessoais->nomePai;
    $oDaoCgsUnd->z01_v_sexo            = $oDadosCgs->dados_pessoais->sexo;
    $oDaoCgsUnd->z01_c_raca            = $oDadosCgs->dados_pessoais->racaCor;
    $oDaoCgsUnd->z01_d_nasc            = $oDataNascimento->getDate();
    $oDaoCgsUnd->z01_i_nacion          = $oDadosCgs->dados_pessoais->nacionalidade;
    $oDaoCgsUnd->z01_v_municnasc       = $oDadosCgs->dados_pessoais->municipioNascimento;
    $oDaoCgsUnd->z01_v_ufnasc          = $oDadosCgs->dados_pessoais->ufNascimento;
    $oDaoCgsUnd->z01_codigoibgenasc    = $oDadosCgs->dados_pessoais->codigoIbge;
    $oDaoCgsUnd->z01_i_naturalidade    = $oDadosCgs->dados_pessoais->nacionalidade;
    $oDaoCgsUnd->z01_b_descnomemae     = $oDadosCgs->dados_pessoais->nomeMae == 'SEM INFORMAÇÃO' ? 'true' : 'false';
    $oDaoCgsUnd->z01_i_paisorigem      = $oDadosCgs->dados_pessoais->paisOrigem;
    $oDaoCgsUnd->z01_registromunicipio = $oDadosCgs->dados_pessoais->cgsMunicipio === true ? 'true' : 'false';

    /**
     * CONTATOS
     */
    $oDaoCgsUnd->z01_v_email  = $oDadosCgs->contato->email != '' ? $oDadosCgs->contato->email : 'null';
    $oDaoCgsUnd->z01_v_telef  = $oDadosCgs->contato->telefone_fixo;
    $oDaoCgsUnd->z01_v_telcel = $oDadosCgs->contato->telefone_celular != '' ? $oDadosCgs->contato->telefone_celular : 'null';
    $oDaoCgsUnd->z01_v_fax    = $oDadosCgs->contato->fax              != '' ? $oDadosCgs->contato->fax              : 'null';

    if( !is_null( $oDadosCgs->contato->endereco_principal ) ) {

      $oEndereco = new endereco( $oDadosCgs->contato->endereco_principal );

      $oDaoCgsUnd->z01_v_ender    = $oEndereco->getDescricaoRua();
      $oDaoCgsUnd->z01_i_numero   = $oEndereco->getNumeroLocal();
      $oDaoCgsUnd->z01_v_compl    = $oEndereco->getComplementoEndereco();
      $oDaoCgsUnd->z01_v_bairro   = $oEndereco->getDescricaoBairro();
      $oDaoCgsUnd->z01_v_munic    = $oEndereco->getDescricaoMunicipio();
      $oDaoCgsUnd->z01_v_uf       = $oEndereco->getSiglaEstado();
      $oDaoCgsUnd->z01_v_cep      = $oEndereco->getCepEndereco();
      $oDaoCgsUnd->z01_codigoibge = $oEndereco->getCodigoSistemaExterno();
      $oDaoCgsUnd->z01_v_cxpostal = $oEndereco->getCaixaPostal();
    }

    if(    !empty( $this->iOid )
        && ( empty( $oDadosCgs->biometria->foto_atual_caminho ) || !empty( $oDadosCgs->biometria->foto_nova_caminho ) ) ) {

      DBLargeObject::exclusao( $this->iOid );

      $oDaoCgsUnd->z01_c_foto = 'null';
      $oDaoCgsUnd->z01_o_oid  = 'null';
    }

    /**
     * BIOMETRIA
     */
    if( !empty( $oDadosCgs->biometria->foto_nova_caminho ) ) {

      $iNovoOid = DBLargeObject::criaOID(true);
      $oOid     = DBLargeObject::escrita( $oDadosCgs->biometria->foto_nova_caminho, $iNovoOid );

      $oDaoCgsUnd->z01_o_oid  = $iNovoOid;
      $oDaoCgsUnd->z01_c_foto = $oDadosCgs->biometria->foto_nova_caminho;
    }

    /**
     * OUTROS DADOS
     */
    $oDaoCgsUnd->z01_i_cgm              = $oDadosCgs->outrosDados->cgm      != '' ? $oDadosCgs->outrosDados->cgm      : 'null';
    $oDaoCgsUnd->z01_i_cge              = $oDadosCgs->outrosDados->cge      != '' ? $oDadosCgs->outrosDados->cge      : 'null';
    $oDaoCgsUnd->z01_i_cidadao          = $oDadosCgs->outrosDados->cidadao  != '' ? $oDadosCgs->outrosDados->cidadao  : 'null';
    $oDaoCgsUnd->z01_i_codocupacao      = $oDadosCgs->outrosDados->ocupacao != '' ? $oDadosCgs->outrosDados->ocupacao : 'null';
    $oDaoCgsUnd->z01_c_bolsafamilia     = $oDadosCgs->outrosDados->bolsafamilia;
    $oDaoCgsUnd->z01_c_nomeresp         = $oDadosCgs->outrosDados->responsavel;
    $oDaoCgsUnd->z01_t_obs              = $oDadosCgs->outrosDados->observacoes;
    $oDaoCgsUnd->z01_i_familiamicroarea = $oDadosCgs->outrosDados->familia != '' ? $oDadosCgs->outrosDados->familia : 'null';
    $oDaoCgsUnd->z01_i_escolaridade     = $oDadosCgs->outrosDados->escolaridade;
    $oDaoCgsUnd->z01_i_estciv           = $oDadosCgs->outrosDados->estado_civil;

    /**
     * Percorre os documentos vinculados ao CGS( cgs_unddocumento ), setando os valores nos campos da tabela cgs_und,
     * para manter os dados iguais em ambas estruturas
     */
    foreach( $this->getDocumentos() as $oDocumentoBase ) {

      if( $oDocumentoBase->getDocumento() == null ) {
        continue;
      }

      $oDaoCgsUnd = $this->setDocumentos( $oDaoCgsUnd, $oDocumentoBase );
    }

    if( $lCgsExistente ) {

      $oDaoCgsUnd->z01_d_ultalt = date( 'Y-m-d' );
      $oDaoCgsUnd->alterar( $this->iCodigo );
    } else {

      $oDaoCgsUnd->z01_i_login  = DB_getsession("DB_id_usuario");
      $oDaoCgsUnd->z01_d_cadast = date( 'Y-m-d' );
      $oDaoCgsUnd->incluir( $this->iCodigo );
    }

    if( $oDaoCgsUnd->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoCgsUnd->erro_msg;
      throw new DBException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_salvar_cgs', $oMensagem ) );
    }

    $this->salvarCgsCartaoSus( $oDadosCgs );
    $this->salvarCgsEndereco( $oDadosCgs );
    $this->salvarCgsEtnia( $oDadosCgs );
  }

  /**
   * Responsável por setar os valores dos documentos vinculados ao cgs( documento | caddocumentoatributovalor ), nos
   * campos da tabela cgs_und, mantendo a igualdade das informações em ambas estruturas
   *
   * @param cl_cgs_und    $oDaoCgsUnd
   * @param DocumentoBase $oDocumentoBase
   * @return mixed
   * @throws DBException
   * @throws ParameterException
   */
  private function setDocumentos( $oDaoCgsUnd, DocumentoBase $oDocumentoBase ) {

    $aAtributosValor = Documento::getAtributosValorByDocumento( $oDocumentoBase->getDocumento() );

    switch( $oDocumentoBase->getCodigo() ) {

      /**
       * GERAIS
       */
      case 3000000:

        $oDaoCgsUnd->z01_c_pis          = $aAtributosValor[0]->valor;
        $oDaoCgsUnd->z01_d_datapais     = $aAtributosValor[1]->valor;
        $oDaoCgsUnd->z01_v_cgccpf       = $aAtributosValor[2]->valor;

        break;

      /**
       * IDENTIDADE
       */
      case 3000001:

        $oDaoCgsUnd->z01_v_ident                = $aAtributosValor[0]->valor;
        $oDaoCgsUnd->z01_d_dtemissao            = $aAtributosValor[1]->valor;
        $oDaoCgsUnd->z01_orgaoemissoridentidade = $aAtributosValor[2]->valor;
        $oDaoCgsUnd->z01_c_ufident              = $aAtributosValor[3]->valor;

        break;

      /**
       * CTPS
       */
      case 3000002:

        $oDaoCgsUnd->z01_c_numctps       = $aAtributosValor[0]->valor;
        $oDaoCgsUnd->z01_c_seriectps     = $aAtributosValor[1]->valor;
        $oDaoCgsUnd->z01_d_dtemissaoctps = $aAtributosValor[2]->valor;
        $oDaoCgsUnd->z01_c_ufctps        = $aAtributosValor[3]->valor;

        break;

      /**
       * CERTIDÃO
       */
      case 3000003:

        $oDaoCgsUnd->z01_c_certidaotipo  = $aAtributosValor[0]->valor;
        $oDaoCgsUnd->z01_c_certidaolivro = $aAtributosValor[1]->valor;
        $oDaoCgsUnd->z01_c_certidaotermo = $aAtributosValor[2]->valor;
        $oDaoCgsUnd->z01_c_certidaocart  = $aAtributosValor[3]->valor;
        $oDaoCgsUnd->z01_c_certidaofolha = $aAtributosValor[4]->valor;
        $oDaoCgsUnd->z01_c_certidaodata  = $aAtributosValor[5]->valor;

        break;

      /**
       * CNH
       */
      case 3000004:

        $oDaoCgsUnd->z01_v_cnh           = $aAtributosValor[0]->valor;
        $oDaoCgsUnd->z01_v_categoria     = $aAtributosValor[1]->valor;
        $oDaoCgsUnd->z01_d_dtemissaocnh  = $aAtributosValor[2]->valor;
        $oDaoCgsUnd->z01_d_dthabilitacao = $aAtributosValor[3]->valor;
        $oDaoCgsUnd->z01_d_dtvencimento  = $aAtributosValor[4]->valor;

        break;

      /**
       * DADOS BANCÁRIOS
       */
      case 3000005:

        $oDaoCgsUnd->z01_c_banco   = $aAtributosValor[0]->valor;
        $oDaoCgsUnd->z01_c_agencia = $aAtributosValor[1]->valor;
        $oDaoCgsUnd->z01_c_conta   = $aAtributosValor[2]->valor;

        break;
    }

    return $oDaoCgsUnd;
  }

  /**
   * Exclui o CGS e os seus vínculos de cadastro
   * @throws BusinessException
   * @throws ParameterException
   */
  public function excluir() {

    if( empty( $this->iCodigo ) ) {
      throw new ParameterException( _M( ARQUIVO_MENSAGEM_CGS . 'codigo_nao_informado' ) );
    }

    $oDaoCgsEndereco = new cl_cgs_undendereco();
    $oDaoCgsEndereco->excluir( null, "sd109_cgs_und = {$this->iCodigo}" );

    if( $oDaoCgsEndereco->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoCgsEndereco->erro_msg;

      throw new BusinessException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_excluir_endereco', $oMensagem ) );
    }

    $oDaoCgsDocumento = new cl_cgs_unddocumento();
    $oDaoCgsDocumento->excluir( null, "sd108_cgs_und = {$this->iCodigo}" );

    if( $oDaoCgsDocumento->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoCgsDocumento->erro_msg;

      throw new BusinessException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_excluir_documento', $oMensagem ) );
    }

    $oDaoCgsEtnia = new cl_cgs_undetnia();
    $oDaoCgsEtnia->excluir( null, "s201_cgs_unid = {$this->iCodigo}" );

    if( $oDaoCgsEtnia->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoCgsEtnia->erro_msg;

      throw new BusinessException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_excluir_etnia', $oMensagem ) );
    }

    $oDaoCgsCartaoSus = new cl_cgs_cartaosus();
    $oDaoCgsCartaoSus->excluir( null, "s115_i_cgs = {$this->iCodigo}" );

    if( $oDaoCgsCartaoSus->erro_status == "0" ) {
      throw new BusinessException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_excluir_cgs' ) );
    }

    $oDaoCgsUnd = new cl_cgs_und();
    $oDaoCgsUnd->excluir( $this->iCodigo );

    if( $oDaoCgsUnd->erro_status == "0" ) {
      throw new BusinessException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_excluir_cgs' ) );
    }

    $oDaoCgs = new cl_cgs();
    $oDaoCgs->excluir( $this->iCodigo );

    if( $oDaoCgs->erro_status == "0" ) {
      throw new BusinessException( _M( ARQUIVO_MENSAGEM_CGS . 'erro_excluir_cgs' ) );
    }
  }


  /**
   * Valida os dados obrigatórios do CGS
   *
   * @param  Cgs     $cgs - Instancia de CGS
   * @return boolean      -
   */
  public static function validar(\Cgs $cgs) {

    return  !!$cgs->getNome()            &&
            !!$cgs->getNomeMae()         &&
            !!$cgs->getDataNascimento()  &&
            !!$cgs->getTelefone()        &&
            !!$cgs->getEndereco()        &&
            (
              /**
               * Se a naturalidade for brasileiro,
               * Valida municipio nascimento, uf nascimento e codigo do Ibge de nascimento
               */
              $cgs->getNaturalidade() <> 10 || (
                !!$cgs->getMunicipioNascimento() && //
                !!$cgs->getIbgeNascimento()      && // Se for brasileiro Valida esses dados aqui =)
                !!$cgs->getUfNascimento()           //
              )
            );

  }

  /**
   * @return int|null
   */
  private function retornaSequencialCartaoDefinitivo() {

    $oDaoCGSCartaoSus = new cl_cgs_cartaosus();

    $sWhereValidaDefinitivo = "s115_i_cgs = {$this->iCodigo} AND s115_c_tipo = 'D'";
    $sSqlValidaDefinitivo   = $oDaoCGSCartaoSus->sql_query_file(null, 's115_i_codigo', null, $sWhereValidaDefinitivo);
    $rsValidaDefinitivo     = db_query($sSqlValidaDefinitivo);

    if($rsValidaDefinitivo && pg_num_rows($rsValidaDefinitivo) > 0) {
      return db_utils::fieldsMemory($rsValidaDefinitivo, 0)->s115_i_codigo;
    }

    return null;
  }
}