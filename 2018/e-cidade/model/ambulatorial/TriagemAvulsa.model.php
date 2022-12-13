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

define("ARQUIVO_MENSAGEM_TRIAGEM_AVULSA", "saude.ambulatorial.TriagemAvulsa.");

Class TriagemAvulsa {

  /**
   * C�digo da Triagem Avulsa
   * @var integer
   */
  private $iCodigo = null;

  /**
   * C�digo CBO do Profissional
   * @var integer
   */
  private $iCboProfissional = null;

  /**
   * C�digo do CGS
   * @var integer
   */
  private $iCgsUnd = null;

  /**
   * Login do usu�rio logado
   * @var integer
   */
  private $iLogin = null;

  /**
   * Press�o Sit�lica do paciente
   * @var integer
   */
  private $iPressaoSistolica = null;

  /**
   * Press�o Diast�lica do paciente
   * @var integer
   */
  private $iPressaoDiastolica = null;

  /**
   * Tamanho da cintura do paciente
   * @var integer
   */
  private $iCintura = null;

  /**
   * Peso do paciente
   * @var float
   */
  private $nPeso = null;

  /**
   * Altura do paciente
   * @var integer
   */
  private $iAltura = null;

  /**
   * Valor da Glicemia do paciente (MG/D)
   * @var integer
   */
  private $iGlicemia = null;

  /**
   * Tipo de alimenta��o para o exame de Glicose do paciente
   * @var integer
   */
  private $iAlimentacaoExameGlicose = null;

  /**
   * Data da consulta
   * @var string
   */
  private $dtDataConsulta = null;

  /**
   * Data do sistema
   * @var string
   */
  private $dtDataSistema = null;

  /**
   * Hora do sistema
   * @var string
   */
  private $dtHoraSistema = null;

  /**
   * A temperatura do cliente
   * @var float
   */
  private $nTemperatura = null;

  /**
   * A evolu��o do paciente
   * @var string
   */
  private $sObjetivo = '';

  /**
   * Medi��o do per�metro cef�lico
   *
   * @var string
   */
  private $iPerimetroCefalico = '';

  /**
   * medi��o da frequencia respiratoria
   *
   * @var string
   */
  private $iFrequenciaRespiratoria = '';

  /**
   * Medi��o da frequencia card�aca
   *
   * @var string
   */
  private $iFrequenciaCardiaca = '';

  private $dtUltimaMenstruacao;

  private $iSaturacao;

  private $sSubjetivo;

  /**
   * Construtor da Triagem
   * @param null $iCodigo
   * @throws DBException
   */
  public function __construct( $iCodigo = null ) {

    if ( $iCodigo != null ) {

      $oDaoTriagemAvulsa = new cl_sau_triagemavulsa();
      $sSqlTriagemAvulsa = $oDaoTriagemAvulsa->sql_query_file( $iCodigo );
      $rsTriagemAvulsa   = db_query( $sSqlTriagemAvulsa );

      if ( !$rsTriagemAvulsa ) {
        throw new DBException( _M(ARQUIVO_MENSAGEM_TRIAGEM_AVULSA . "erro_buscar_triagem") );
      }

      if (  pg_num_rows( $rsTriagemAvulsa) == 0 ) {
        throw new DBException( _M(ARQUIVO_MENSAGEM_TRIAGEM_AVULSA . "triagem_nao_encontrada") );
      }

      $oTriagemAvulsa = db_utils::fieldsMemory($rsTriagemAvulsa, 0);

      $this->iCodigo                  = $oTriagemAvulsa->s152_i_codigo;
      $this->iCboProfissional         = $oTriagemAvulsa->s152_i_cbosprofissional;
      $this->iCgsUnd                  = $oTriagemAvulsa->s152_i_cgsund;
      $this->iLogin                   = $oTriagemAvulsa->s152_i_login;
      $this->iPressaoSistolica        = $oTriagemAvulsa->s152_i_pressaosistolica;
      $this->iPressaoDiastolica       = $oTriagemAvulsa->s152_i_pressaodiastolica;
      $this->iCintura                 = $oTriagemAvulsa->s152_i_cintura;
      $this->nPeso                    = $oTriagemAvulsa->s152_n_peso;
      $this->iAltura                  = $oTriagemAvulsa->s152_i_altura;
      $this->iGlicemia                = $oTriagemAvulsa->s152_i_glicemia;
      $this->iAlimentacaoExameGlicose = $oTriagemAvulsa->s152_i_alimentacaoexameglicemia;
      $this->dtDataConsulta           = $oTriagemAvulsa->s152_d_dataconsulta;
      $this->dtDataSistema            = $oTriagemAvulsa->s152_d_datasistema;
      $this->dtHoraSistema            = $oTriagemAvulsa->s152_c_horasistema;
      $this->nTemperatura             = $oTriagemAvulsa->s152_n_temperatura;
      $this->sObjetivo                = $oTriagemAvulsa->s152_evolucao;
      $this->iPerimetroCefalico       = $oTriagemAvulsa->s152_perimetrocefalico;
      $this->iFrequenciaRespiratoria  = $oTriagemAvulsa->s152_frequenciarespiratoria;
      $this->iFrequenciaCardiaca      = $oTriagemAvulsa->s152_frequenciacardiaca;
      $this->dtUltimaMenstruacao      =  $oTriagemAvulsa->s152_dum;
      $this->iSaturacao               = $oTriagemAvulsa->s152_saturacao;
      $this->sSubjetivo               = $oTriagemAvulsa->s152_subjetivo;
    }
  }

  /**
   * Retorna o c�digo da Triagem
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o c�digo CBO do profissional
   * @return integer
   */
  public function getCboProfissional() {
    return $this->iCboProfissional;
  }

  /**
   * Define o c�digo CBO do profissional
   * @param integer $iCboProfissional
   */
  public function setCboProfissional( $iCboProfissional ) {
    $this->iCboProfissional = $iCboProfissional;
  }

  /**
   * Retorna o c�digo do CGS
   * @return integer
   */
  public function getCgsUnd() {
    return $this->iCgsUnd;
  }

  /**
   * Define o c�digo do CGS
   * @param integer $iCgsUnd
   */
  public function setCgsUnd( $iCgsUnd ) {
    $this->iCgsUnd = $iCgsUnd;
  }

  /**
   * Retorna o login do usu�rio
   * @return integer
   */
  public function getLogin() {
    return $this->iLogin;
  }

  /**
   * Define o login do usu�rio
   * @param [type] $iLogin [description]
   */
  public function setLogin( $iLogin ) {
    $this->iLogin = $iLogin;
  }

  /**
   * Retorna a Press�o Sist�lica do paciente
   * @return integer
   */
  public function getPressaoSistolica() {
    return $this->iPressaoSistolica;
  }

  /**
   * Define a Press�o Sist�lica do paciente
   * @param integer $iPressaoSistolica
   */
  public function setPressaoSistolica( $iPressaoSistolica ) {
    $this->iPressaoSistolica = $iPressaoSistolica;
  }

  /**
   * Retorna a Press�o Diast�lica do paciente
   * @return integer
   */
  public function getPressaoDiastolica() {
    return $this->iPressaoDiastolica;
  }

  /**
   * Define a Press�o Diast�lica do paciente
   * @param integer $iPressaoDiastolica
   */
  public function setPressaoDiastolica( $iPressaoDiastolica ) {
    $this->iPressaoDiastolica = $iPressaoDiastolica;
  }

  /**
   * Retorna a cintura do paciente
   * @return integer
   */
  public function getCintura() {
    return $this->iCintura;
  }

  /**
   * Define a cintura do paciente
   * @param integer $iCintura
   */
  public function setCintura( $iCintura ) {
    $this->iCintura = $iCintura;
  }

  /**
   * Retorna o peso do paciente
   * @return float
   */
  public function getPeso() {
    return $this->nPeso;
  }

  /**
   * Define o peso do paciente
   * @param float $nPeso
   */
  public function setPeso( $nPeso ) {
    $this->nPeso = $nPeso;
  }

  /**
   * Retorna a altura do paciente
   * @return integer
   */
  public function getAltura() {
    return $this->iAltura;
  }

  /**
   * Define a altura do paciente
   * @param integer $iAltura
   */
  public function setAltura( $iAltura ) {
    $this->iAltura = $iAltura;
  }

  /**
   * Retorna a Glicemia do paciente
   * @return integer
   */
  public function getGlicemia() {
    return $this->iGlicemia;
  }

  /**
   * Define a Glicemia do paciente
   * @param integer $iGlicemia
   */
  public function setGlicemia( $iGlicemia ) {
    $this->iGlicemia = $iGlicemia;
  }

  /**
   * Retorna a Alimenta��o do paciente ao realizar o exame de glicemia.
   * @return integer 0 - N�o informado; 1 - Em jejum; 2 - P�s prandial
   */
  public function getAlimentacaoExameGlicose() {
    return $this->iAlimentacaoExameGlicose;
  }

  /**
   * Define a Alimenta��o do paciente ao realizar o exame de glicemia.
   * @param integer $iAlimentacaoExameGlicose  0 - N�o informado; 1 - Em jejum; 2 - P�s prandial
   */
  public function setAlimentacaoExameGlicose( $iAlimentacaoExameGlicose = "0" ) {
    $this->iAlimentacaoExameGlicose = $iAlimentacaoExameGlicose;
  }

  /**
   * Retorna a data da consulta
   * @return string
   */
  public function getDataConsulta() {
    return $this->dtDataConsulta;
  }

  /**
   * Define a data da consulta
   * @param DBDate $oDataConsulta
   */
  public function setDataConsulta( DBDate $oDataConsulta ) {
    $this->dtDataConsulta = $oDataConsulta->convertTo(DBDate::DATA_EN);
  }

  /**
   * Retorna a data do sistema em que a triagem foi salva
   * @return DBDate
   */
  public function getDataSistema() {
    return $this->dtDataSistema;
  }

  /**
   * Define a data em que a triagem foi salva
   * @param DBDate $oDataSistema
   */
  public function setDataSistema( DBDate $oDataSistema ) {
    $this->dtDataSistema = $oDataSistema->convertTo(DBDate::DATA_EN);
  }

  /**
   * Retorna a hora em que a triagem foi salva
   * @return string
   */
  public function getHoraSistema() {
    return $this->dtHoraSistema;
  }

  /**
   * Define a hora em que a triagem foi salva
   * @param string $sHoraSistema
   */
  public function setHoraSistema( $sHoraSistema ) {
    $this->dtHoraSistema = $sHoraSistema;
  }

  /**
   * Retorna a temperatura do paciente
   * @return float
   */
  public function getTemperatura() {
    return $this->nTemperatura;
  }

  /**
   * Define a temperatura do paciente
   * @param float $nTemperatura
   */
  public function setTemperatura( $nTemperatura ) {
    $this->nTemperatura = $nTemperatura;
  }

  public function getObjetivo() {
    return $this->sObjetivo;
  }

  public function setObjetivo( $sObjetivo ) {
    $this->sObjetivo = $sObjetivo;
  }

  public function getSubjetivo() {
    return $this->sSubjetivo;
  }

  public function setSubjetivo( $sSubjetivo ) {
    $this->sSubjetivo = $sSubjetivo;
  }

  /**
   * Inclui ou altera uma Triagem
   */
  public function salvar(){

    if (!db_utils::inTransaction()) {
      throw new DBException( _M(ARQUIVO_MENSAGEM_TRIAGEM_AVULSA . "nenhuma_transacao") );
    }

    $oDaoTriagemAvulsa                                  = new cl_sau_triagemavulsa();
    $oDaoTriagemAvulsa->s152_i_codigo                   = $this->iCodigo;
    $oDaoTriagemAvulsa->s152_i_cbosprofissional         = $this->iCboProfissional;
    $oDaoTriagemAvulsa->s152_i_cgsund                   = $this->iCgsUnd;
    $oDaoTriagemAvulsa->s152_i_login                    = $this->iLogin;
    $oDaoTriagemAvulsa->s152_i_pressaosistolica         = empty($this->iPressaoSistolica) ? null : $this->iPressaoSistolica;
    $oDaoTriagemAvulsa->s152_i_pressaodiastolica        = $this->iPressaoDiastolica;
    $oDaoTriagemAvulsa->s152_i_cintura                  = empty($this->iCintura)  ? null : $this->iCintura;
    $oDaoTriagemAvulsa->s152_n_peso                     = empty($this->nPeso)     ? null : $this->nPeso;
    $oDaoTriagemAvulsa->s152_i_altura                   = empty($this->iAltura)   ? null : $this->iAltura;
    $oDaoTriagemAvulsa->s152_i_glicemia                 = empty($this->iGlicemia) ? null : $this->iGlicemia;
    $oDaoTriagemAvulsa->s152_i_alimentacaoexameglicemia = $this->iAlimentacaoExameGlicose;
    $oDaoTriagemAvulsa->s152_d_dataconsulta             = $this->dtDataConsulta;
    $oDaoTriagemAvulsa->s152_d_datasistema              = $this->dtDataSistema;
    $oDaoTriagemAvulsa->s152_c_horasistema              = $this->dtHoraSistema;
    $oDaoTriagemAvulsa->s152_n_temperatura              = $this->nTemperatura;
    $oDaoTriagemAvulsa->s152_evolucao                   = $this->sObjetivo;
    $oDaoTriagemAvulsa->s152_perimetrocefalico          = $this->iPerimetroCefalico;
    $oDaoTriagemAvulsa->s152_frequenciarespiratoria     = $this->iFrequenciaRespiratoria;
    $oDaoTriagemAvulsa->s152_frequenciacardiaca         = $this->iFrequenciaCardiaca;
    $oDaoTriagemAvulsa->s152_dum                        = $this->dtUltimaMenstruacao;
    $oDaoTriagemAvulsa->s152_saturacao                  = $this->iSaturacao;
    $oDaoTriagemAvulsa->s152_subjetivo                  = $this->sSubjetivo;

    if ( empty($this->iCodigo) ) {
      $oDaoTriagemAvulsa->incluir(null);
    } else {
      $oDaoTriagemAvulsa->alterar($this->iCodigo);
    }

    if ( $oDaoTriagemAvulsa->erro_status == "0" ) {

      $oErro = new stdClass();
      $oErro->sErro = $oDaoTriagemAvulsa->erro_msg;
      throw new DBException( _M(ARQUIVO_MENSAGEM_TRIAGEM_AVULSA . "erro_salvar", $oErro) );
    }

    $this->iCodigo = $oDaoTriagemAvulsa->s152_i_codigo;
  }

  /**
   * Retorna o m�dico vinculado a Triagem
   * @return Medico
   * @throws DBException
   */
  public function getMedico() {

    $oDaoUnidadeMedicos    = new cl_unidademedicos();
    $sCamposUnidadeMedicos = "distinct sd04_i_medico";
    $sWhereUnidadeMedicos  = "fa54_i_codigo = {$this->iCboProfissional}";
    $sSqlUnidadeMedicos    = $oDaoUnidadeMedicos->sql_query_medico(null, $sCamposUnidadeMedicos, null, $sWhereUnidadeMedicos);
    $rsUnidadeMedicos      = db_query( $sSqlUnidadeMedicos );

    if ( !$rsUnidadeMedicos || pg_num_rows($rsUnidadeMedicos) == 0 ) {

      $oErro        = new stdClass();
      $oErro->sErro = $oDaoUnidadeMedicos->erro_msg;
      throw new DBException( _M(ARQUIVO_MENSAGEM_TRIAGEM_AVULSA . "erro_buscar_medico", $oErro) );
    }

    $iMedico = db_utils::fieldsMemory( $rsUnidadeMedicos, 0)->sd04_i_medico;
    return new Medico( $iMedico );
  }

  /**
   * Busca o agravo da triagem e retorna em forma de stdClass
   * @return _db_fields|null|stdClass|void
   * @throws DBException
   */
  public function getAgravo() {

    if ( empty($this->iCodigo) ) {
      return null;
    }

    $oTriagemAvulsaAgravoDao = new cl_sau_triagemavulsaagravo();
    $sWhereAgravo            = " s167_sau_triagemavulsa = {$this->iCodigo}";
    $sSqlAgravo              = $oTriagemAvulsaAgravoDao->sql_query_file(null, '*', null, $sWhereAgravo);
    $rsAgravo                = db_query( $sSqlAgravo );

    if ( !$rsAgravo ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M(ARQUIVO_MENSAGEM_TRIAGEM_AVULSA . "erro_buscar_agravo", $oErro) );
    }

    if ( pg_num_rows($rsAgravo) == 0 ) {
      return null;
    }

    return db_utils::fieldsMemory( $rsAgravo, 0 );
  }


  public function setPerimetroCefalico($iPerimetroCefalico) {
    $this->iPerimetroCefalico = $iPerimetroCefalico;
    return $this;
  }

  public function getPerimetroCefalico() {
    return $this->iPerimetroCefalico;
  }

  public function setFrequenciaRespiratoria($iFrequenciaRespiratoria) {
    $this->iFrequenciaRespiratoria = $iFrequenciaRespiratoria;
    return $this;
  }

  public function getFrequenciaRespiratoria() {
    return $this->iFrequenciaRespiratoria;
  }

  public function setFrequenciaCardiaca($iFrequenciaCardiaca) {
    $this->iFrequenciaCardiaca = $iFrequenciaCardiaca;
    return $this;
  }

  public function getFrequenciaCardiaca() {
    return $this->iFrequenciaCardiaca;
  }

  public function setUltimaMenstruacao($dtUltimaMenstruacao) {
    $this->dtUltimaMenstruacao = $dtUltimaMenstruacao;
    return $this;
  }

  public function getUltimaMenstruacao() {
    return $this->dtUltimaMenstruacao;
  }

  public function setSaturacao($iSaturacao) {
    $this->iSaturacao = $iSaturacao;
    return $this;
  }

  public function getSaturacao() {
    return $this->iSaturacao;
  }
}
