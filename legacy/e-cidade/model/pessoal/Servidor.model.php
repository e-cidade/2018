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

use ECidade\RecursosHumanos\RH\Efetividade\Repository\EscalaServidor;

/**
 * Classe para manipuação de servidores
 *
 * @author   Alberto Ferri Neto alberto@dbseller.com.br
 * @package  Pessoal
 * @revision $Author: dbaugusto.oliveira $
 * @version  $Revision: 1.91 $
 */
class Servidor {

  /**
   * Duplo vinculo do Servidor
   *
   * @var Servidor
   */
  private $oDuploVinculo;
  /**
   * Conta bancaria do Servidor
   * @var ContaBancaria
   */
  private $oContaBancaria;

  /**
   * Codigo do servidor na competencia
   */
  private $iCodigoMovimentacao;

  /**
   * Matrícula do servidor
   * @var integer
   */
  private $iMatricula;

  /**
   * Instância do objeto CgmBase Número do cgm do servidor
   * @var object
   */
  private $oCgm;

  /**
   * Código do cargo do servidor
   * @var inteiro
   */
  private $iCodigoCargo;

  /**
   * Data de admissão do servidor
   * @var DBDate
   */
  private $oDataAdmissao;

  /**
   * Tipo de admissão do servidor
   * 1 - Admissao do 1o emprego
   * 2 - Admissao com emprego anterior
   * 3 - Transf de empreg sem onus para a cedente
   * 4 - Transf de empreg com onus para a cedente
   *
   * @var integer
   */
  private $iTipoAdmissao;

  /**
   * Data que foi/sera consedido o triênio
   * @var DBDate
   */
  private $oDataTrienio;

  /**
   * Data de progressão do servidor. (É a mudança de nível de capacitação do servidor para o nível subsequente)
   * @var DBDate
   */
  private $oDataProgressao;

  /**
   * Código da instituição da matrícula do servidor
   * @var integer
   */
  private $iCodigoInstituicao;

  /**
   * Número no relogio ponto
   * @var integer
   */
  private $iNumeroPonto;

  /**
   * Observações referentes ao servidor
   * @var string
   */
  private $sObservacaoServidor;

  /**
   * Ano de calculo atual da folha
   *
   * @var integer
   * @access private
   */
  private $iAnoCompetencia;

  /**
   * Ano de calculo atual da folha
   *
   * @var integer
   * @access private
   */
  private $iMesCompetencia;

  /**
   * Numero do CGM
   *
   * @var integer
   * @access private
   */
  private $iNumCgm;

  /**
   * Tabela previdencia
   *
   * @var integer
   * @access private
   */
  private $iTabelaPrevidencia;

  /**
   * Array com coleção de objetos Dependente
   * Referente ao servidor
   */
  private $aDependentes = array();

  /**
   * Objeto DBDate com a data de nascimento do servidor
   * @object DBDate
   * @access private
   */
  private $oDataNascimento;

  /**
   * Sexo do servidor
   * @var string
   */
  private $sSexo;

  /**
   * Tipo de exposicao a agentes nocivos
   * '' - Nunca esteve exposta
   * 01 - Não exposto no momento, mas já esteve
   * 02 - Exposta (aposentadoria esp. 15 anos)
   * 03 - Exposta (aposentadoria esp. 20 anos)
   * 04 - Exposta (aposentadoria esp. 25 anos)
   * 05 - Mais de um vínculo (ou fonte pagadora) - Não exposição a agente nocivo
   *
   * @var mixed
   * @access private
   */
  private $sTipoExposicaoAgentesNocivos;

  /**
   *
   * Código
   * @var mixed
   * @access private
   */
  private $iCodigoRegime;

  /**
   * Codigo da lotacao
   *
   * @var mixed
   * @access private
   */
  private $iCodigoLotacao;

  /**
   * Salario
   *
   * @var mixed
   * @access private
   */
  private $iSalario;

  /**
   * Define se o Servidor utiliza ou não o Abono de Permanência.
   * @var boolean
   */
  private $lAbonoPermanencia;

  /**
   * Número de dias de férias padrão do servidor
   *
   * @var Integer
   * @access private
   */
  private $iDiasGozoFerias;

  /**
   * Define se o servidor possui ou não moléstia grave
   *
   * @var Boolean
   */
  private $lMolestiaGrave;

  /**
   * Código do PIS/PASEP
   *
   * @var String
   */
  private $sPISPASEP;

  /**
   * @var bool
   */
  private $lRegistraPontoEletronico = true;

  const VARIAVEL_SALARIO_BASE_PROGRESSAO = 'F010';

  /**
   * Método construtor da classe Servidor. Caso seja setada a matrícula, deve ser carregado os dados do servidor
   */
  public function __construct($iMatricula = null, $iAnoCompetencia = null, $iMesCompetencia = null, $iInstituicao = null) {


    if ( !empty($iAnoCompetencia) ) {
      $this->iAnoCompetencia = $iAnoCompetencia;
    } else {
      $this->iAnoCompetencia    = DBPessoal::getAnoFolha();
    }

    if ( !empty($iMesCompetencia) ) {
      $this->iMesCompetencia = $iMesCompetencia;
    } else {
      $this->iMesCompetencia    = DBPessoal::getMesFolha();
    }

    if ( !empty($iInstituicao) ) {
      $this->iCodigoInstituicao = $iInstituicao;
    } else {
      $this->iCodigoInstituicao = db_getsession("DB_instit");
    }

    if (!empty($iMatricula) && !DBNumber::isInteger($iMatricula) ) {
      throw new BusinessException("Formato de matrícula inválida.");
    }

    if (!empty($iMatricula)) {

      $oDaoPessoal = new cl_rhpessoal;
      $sSqlPessoal = $oDaoPessoal->sql_query_file(null,'*', null,"rh01_regist = $iMatricula and rh01_instit = $this->iCodigoInstituicao");
      $rsPessoal   = db_query($sSqlPessoal);

      if (!$rsPessoal) {
        throw new DBException("Erro ao Buscar Servidor." . pg_last_error());
      }

      if (pg_num_rows($rsPessoal) == 0) {
        throw new BusinessException("Matrícula não cadastrada no e-Cidade.");
      }

      if ( !DBNumber::isInteger( $iMatricula ) ) {
        throw new ParameterException("A Matrícula deve ser um Número Inteiro");
      }

      $this->setMatricula($iMatricula);

      $oDaoRhPessoalMov = new cl_rhpessoalmov();
      $sSqlRhPessoalMov = $oDaoRhPessoalMov->sql_queryDadosServidor($this->iAnoCompetencia, $this->iMesCompetencia,$this->iCodigoInstituicao, $iMatricula);
      $rsRhPessoal      = db_query($sSqlRhPessoalMov);

      if (!$rsRhPessoal) {
        throw new DBException("Erro ao Buscar Servidor." . pg_last_error());
      }

      if ( pg_num_rows( $rsRhPessoal ) == 0 ) {
        throw new BusinessException("Servidor com a Matrícula: {$iMatricula} não está na competência: {$this->iMesCompetencia}/{$this->iAnoCompetencia}");
      }

      $oRhPessoal                = db_utils::fieldsMemory($rsRhPessoal, 0);
      $this->iNumCgm             = $oRhPessoal->rh01_numcgm;
      $this->iCodigoMovimentacao = $oRhPessoal->rh02_seqpes;
      $this->iCodigoLotacao      = $oRhPessoal->rh02_lota;
      $this->iSalario            = $oRhPessoal->rh02_salari;

      $this->setCodigoCargo($oRhPessoal->rh01_funcao);
      $this->setTipoAdmissao($oRhPessoal->rh01_tipadm);
      $this->setCodigoInstituicao($oRhPessoal->rh01_instit);
      $this->setNumeroPonto($oRhPessoal->rh01_ponto);
      $this->setObservacaoServidor($oRhPessoal->rh01_observacao);
      $this->setAbonoPermanencia($oRhPessoal->rh02_abonopermanencia);
      $this->setTabelaPrevidencia($oRhPessoal->rh02_tbprev);
      $this->setMolestiaGrave($oRhPessoal->rh02_portadormolestia == 't' ? true : false);

      if ($oRhPessoal->rh02_ocorre) {
        $this->setTipoExposicaoAgentesNocivos($oRhPessoal->rh02_ocorre);
      }

      if ($oRhPessoal->rh02_codreg) {
        $this->setCodigoRegime($oRhPessoal->rh02_codreg);
      }

      if (!empty($oRhPessoal->rh01_admiss)) {
        $this->setDataAdmissao    (new DBDate($oRhPessoal->rh01_admiss));
      }

      if (!empty($oRhPessoal->rh01_trienio)) {
        $this->setDataTrienio     (new DBDate($oRhPessoal->rh01_trienio));
      }

      if (!empty($oRhPessoal->rh01_progres)) {
        $this->setDataProgressao  (new DBDate($oRhPessoal->rh01_progres));
      }

      if (!empty($oRhPessoal->rh01_nasc)) {
        $this->setDataNascimento    (new DBDate($oRhPessoal->rh01_nasc));
      }

      $this->setSexo($oRhPessoal->rh01_sexo);

      if(!empty($oRhPessoal->rh02_diasgozoferias)) {
        $this->setDiasGozoFerias($oRhPessoal->rh02_diasgozoferias);
      }

      $oDocumentos = $this->getDocumentos();

      if(!empty($oDocumentos->sPIS)) {
        $this->setPISPASEP($oDocumentos->sPIS);
      }

      $this->lRegistraPontoEletronico = $oRhPessoal->rh01_registrapontoeletronico == 't' ? true : false;
    }
  }

  /**
   * Retorna a matrícula do servidor
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Define a matrícula do servidor
   * @param integer $iMatricula
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * Retorna o código do cgm do servidor
   * @return CgmFisico
   */
  public function hasCgm() {

    if ( empty($this->oCgm) || !($this->oCgm instanceof CgmFisico) || !($this->oCgm instanceof CgmJuridico) ) {
      return false;
    }
    return true;
  }

  /**
   * Retorna o código do cgm do servidor
   * @return CgmFisico
   */
  public function getCgm() {

    if ( empty($this->oCgm) ) {
      $this->setCgm( CgmFactory::getInstanceByCgm($this->iNumCgm) );
    }
    return $this->oCgm;
  }

  /**
   * Define o código do cgm do servidor
   * @param object $oCgm
   */
  public function setCgm(CgmBase $oCgm = null) {
    $this->oCgm = $oCgm;
  }

  /**
   * Retorna o código do cargo do servidor
   * @return integer
   */
  public function getCodigoCargo() {
    return $this->iCodigoCargo;
  }

  /**
   * Define o código do cargo do servidor
   * @param integer $iCodigoCargo
   */
  public function setCodigoCargo($iCodigoCargo) {
    $this->iCodigoCargo = $iCodigoCargo;
  }

  /**
   * Retorna um objeto DBDate, contendo a data de admissão do servidor
   * @return DBDate
   */
  public function getDataAdmissao() {
    return $this->oDataAdmissao;
  }

  /**
   * Intancia um objeto DBDate, com informações sobre a data de admissão de um servidor
   * @param object $oDataAdmissao
   */
  public function setDataAdmissao(DBDate $oDataAdmissao) {
    $this->oDataAdmissao = $oDataAdmissao;
  }

  /**
   * Retorna o tipo de admissão do servidor
   * 1 - Admissao do 1o emprego
   * 2 - Admissao com emprego anterior
   * 3 - Transf de empreg sem onus para a cedente
   * 4 - Transf de empreg com onus para a cedente
   * @return integer
   */
  public function getTipoAdmissao() {
    return $this->iTipoAdmissao;
  }

  /**
   * Define o tipo de admissão do servidor
   * 1 - Admissao do 1o emprego
   * 2 - Admissao com emprego anterior
   * 3 - Transf de empreg sem onus para a cedente
   * 4 - Transf de empreg com onus para a cedente
   * @param integer
   */
  public function setTipoAdmissao($iTipoAdmissao) {
    $this->iTipoAdmissao = $iTipoAdmissao;
  }

  /**
   * Retorna um objeto DBDate contendo a data que foi/sera consedido o triênio
   * @return DBDate
   */
  public function getDataTrienio() {
    return $this->oDataTrienio;
  }

  /**
   * Instancía um objeto DBDate com as informações sobre a date que foi/sera consedido o triênio
   * @param object $sDataTrienio
   */
  public function setDataTrienio(DBDate $oDataTrienio) {
    $this->oDataTrienio = $oDataTrienio;
  }

  /**
   * Retorna um objeto DBDate contendo a data de progressão do servidor
   * @return DBDate
   */
  public function getDataProgressao() {
    return $this->oDataProgressao;
  }

  /**
   * Instancía um objeto DBDate com as informações sobre a date que foi/sera consedido o triênio
   * @param object $oDataTrienio
   */
  public function setDataProgressao(DBDate $oDataProgressao) {
    $this->oDataProgressao = $oDataProgressao;
  }

  /**
   * Retona o código da instituição da matrícula do servidor
   * @return integer
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }

  /**
   * Define o código da instituição da matrícula do servidor
   * @param integer $iCodigoInstituicao
   * @deprecated - Utilizar Servidor::getInstituicao();
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }

  /**
   * Retorna a instituicao do servidor
   *
   * @access public
   * @return Instituicao
   */
  public function getInstituicao() {

    require_once modification("model/configuracao/InstituicaoRepository.model.php");
    $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo($this->iCodigoInstituicao);

    return $oInstituicao;
  }

  /**
   * Retorna o número do cartão ponto da matrícula do servidor
   * @return integer
   */
  public function getNumeroPonto() {
    return $this->iNumeroPonto;
  }

  /**
   * Define o número do cartão ponto da matrícula do servidor
   * @param integer $iNumeroPonto
   */
  public function setNumeroPonto($iNumeroPonto) {
    $this->iNumeroPonto = $iNumeroPonto;
  }

  /**
   * Retorna alguma observação sobre a matrícula do servidor
   * @return string
   */
  public function getObservacaoServidor() {
    return $this->sObservacaoServidor;
  }

  /**
   * Define alguma observação sobre a matrícula do servidor
   * @param string $sObservacaoServidor
   */
  public function setObservacaoServidor($sObservacaoServidor) {
    $this->sObservacaoServidor = $sObservacaoServidor;
  }

  /**
   * Define o codigo da tabela de previdencia
   *
   * @param integer $iTabelaPrevidencia
   * @access public
   * @return void
   */
  public function setTabelaPrevidencia($iTabelaPrevidencia) {
    $this->iTabelaPrevidencia = $iTabelaPrevidencia;
  }

  /**
   * Retorna codigo da tabela de previdencia
   *
   * @access public
   * @return integer
   */
  public function getTabelaPrevidencia() {
    return $this->iTabelaPrevidencia;
  }

  /**
   * Define a data de nascimento do servidor
   *
   * @param DBDate $oDataNascimento
   * @access public
   * @return void
   */
  public function setDataNascimento(DBDate $oDataNascimento) {
    $this->oDataNascimento = $oDataNascimento;
  }

  /**
   * Retorna a data de nascimento do servidor
   *
   * @access public
   * @return objeto DBDate
   */
  public function getDataNascimento () {
    return $this->oDataNascimento;
  }

  /**
   * Retorna idade do servidor
   *
   * @access public
   * @return integer
   */
  public function getIdade () {

    if ( empty($this->oDataNascimento) ) {
      return 0;
    }

    return DBDate::calculaIntervaloEntreDatas(new DBDate(date('Y-m-d'), db_getsession('DB_datausu')), $this->oDataNascimento, 'y');
  }

  /**
   * Define sexo do servidor
   *
   * @param string $sSexo
   * @access public
   * @return void
   */
  public function setSexo($sSexo) {
    $this->sSexo = $sSexo;
  }

  /**
   * Retorna o sexo do servidor
   *
   * @access public
   * @return string
   */
  public function getSexo() {
    return $this->sSexo;
  }

  /**
   * Define tipo de exposicao a agentes nocivos
   *
   * @param string $sTipoExposicaoAgentesNocivos
   * @access public
   * @return void
   */
  public function setTipoExposicaoAgentesNocivos ($sTipoExposicaoAgentesNocivos) {
    $this->sTipoExposicaoAgentesNocivos = $sTipoExposicaoAgentesNocivos;
  }

  /**
   * Retorna tipo de exposicao a agentes nocivos
   *
   * @access public
   * @return string
   */
  public function getTipoExposicaoAgentesNocivos() {
    return $this->sTipoExposicaoAgentesNocivos;
  }

  /**
   * Define o codigo do regime do servidor
   *
   * @param integer $iCodigoRegime
   * @access public
   * @return void
   */
  public function setCodigoRegime($iCodigoRegime) {
    $this->iCodigoRegime = $iCodigoRegime;
  }

  /**
   * Retorna o ano da competencia da folha
   *
   * @access public
   * @return integer
   */
  public function getAnoCompetencia() {
    return $this->iAnoCompetencia;
  }

  /**
   * Retorna o mes da competencia da folha
   *
   * @access public
   * @return integer
   */
  public function getMesCompetencia() {
    return $this->iMesCompetencia;
  }

  /**
   * Retorna o código do regime
   *
   * @param integer $iCodigoRegime
   * @access public
   * @return integer
   */
  public function getCodigoRegime() {

    return $this->iCodigoRegime;
  }

  /**
   * Retorna o código do tipo de regime
   *
   * @param integer $iCodigoRegime
   * @access public
   * @return void
   */
  public function getTipoRegime() {

    $oDaoRhRegime = db_utils::getDao('rhregime');
    $sSqlRhRegime = $oDaoRhRegime->sql_query_file ($this->iCodigoRegime);
    $rsRhRegime   = $oDaoRhRegime->sql_record($sSqlRhRegime);
    if ( $oDaoRhRegime->numrows == 0 ) {
      return;
    }

    return db_utils::fieldsMemory($rsRhRegime, 0)->rh30_regime;
  }

  /**
   * Define se o servidor possui moléstia grave
   * @param Boolean
   */
  public function setMolestiaGrave ($lMolestiaGrave) {
    $this->lMolestiaGrave = $lMolestiaGrave;
  }

  /**
   * Retorna se o servidor possui moléstia grave
   * @return Boolean
   */
  public function getMolestiaGrave () {
    return $this->lMolestiaGrave;
  }

  /**
   * Retorna o Vinculo do Servidor
   *
   * @access public
   * @return VinculoServidor
   */
  public function getVinculo() {
    return VinculoServidorRepository::getInstanciaPorCodigo($this->iCodigoRegime);
  }

  /**
   * Retorna os documentos do servidor
   *
   * @access public
   * @return stdClass
   */
  public function getDocumentos() {

    $oDaoRHPesDoc  = db_utils::getDao('rhpesdoc');
    $sSqlDocumentos= $oDaoRHPesDoc->sql_query_file( $this->getMatricula() );
    $rsDocumentos  = db_query($sSqlDocumentos);

    $oRetorno = new stdClass();
    $oRetorno->iSerieCTPS                      = '';
    $oRetorno->sNumeroTituloEleitor            = '';
    $oRetorno->sCategoriaCertificadoReservista = '';
    $oRetorno->iNumeroCTPS                     = '';
    $oRetorno->sUfCTPS                         = '';
    $oRetorno->sSecaoEleitoral                 = '';
    $oRetorno->sZonaEleitoral                  = '';
    $oRetorno->iNumeroCarteiraHabilitacao      = '';
    $oRetorno->sCertificadoReservista          = '';
    $oRetorno->dValidadeHabilitacao            = '';
    $oRetorno->iDigitoCTPS                     = '';
    $oRetorno->sCategoriaHabilitacao           = '';
    $oRetorno->sPIS                            = '';

    if ( !$rsDocumentos ) {
      throw new DBException('Erro ao Buscar os Documentos do Servidor.');
    }

    if ( pg_num_rows($rsDocumentos) == 0 ) {
      return $oRetorno;
    }

    $oDocumentos = db_utils::fieldsMemory($rsDocumentos, 0);

    $oRetorno->iSerieCTPS                      = $oDocumentos->rh16_ctps_s    ;// Série da CTPS                     int4
    $oRetorno->sNumeroTituloEleitor            = $oDocumentos->rh16_titele    ;// Número do Título de Eleitor       varchar(12)
    $oRetorno->sCategoriaCertificadoReservista = $oDocumentos->rh16_catres    ;// Categoria do certificado de reservista.               varchar(4)
    $oRetorno->iNumeroCTPS                     = $oDocumentos->rh16_ctps_n    ;// Carteira de Trab.e Prev.social                        int4
    $oRetorno->sUfCTPS                         = $oDocumentos->rh16_ctps_uf   ;// Unidade Federativa da CTPS                            varchar(2)
    $oRetorno->sSecaoEleitoral                 = $oDocumentos->rh16_secaoe    ;// Seção eleitoral.                                      varchar(4)
    $oRetorno->sZonaEleitoral                  = $oDocumentos->rh16_zonael    ;// Zona eleitoral                                        varchar(3)
    $oRetorno->iNumeroCarteiraHabilitacao      = $oDocumentos->rh16_carth_n   ;// Nro da Carteira de Habilitacao                        int8
    $oRetorno->sCertificadoReservista          = $oDocumentos->rh16_reserv    ;// Certificado de Reservista.                            varchar(15)
    $oRetorno->dValidadeHabilitacao            = $oDocumentos->rh16_carth_val ;// Data de validade da carteira nacional de habilitação. date
    $oRetorno->iDigitoCTPS                     = $oDocumentos->rh16_ctps_d    ;// Dígito da CTPS                                        int4
    $oRetorno->sCategoriaHabilitacao           = $oDocumentos->r16_carth_cat  ;// Categoria da carteira nacional de habilitação.        varchar(3)
    $oRetorno->sPIS                            = $oDocumentos->rh16_pis       ;// Código do PIS/PASEP/CI                                varchar(11)

    return $oRetorno;
  }

  /**
   * Retorna CalculoFolha pelo nome da tabela
   *
   * @param string $sCalculo - nome da tabela de calculo
   * @access public
   * @return CalculoFolha
   */
  public function getCalculoFinanceiro($sCalculo) {

    require_once(modification('model/pessoal/CalculoFolha.model.php'));

    $oCalculoFinanceiro =  null;

    switch ($sCalculo) {

      case CalculoFolha::CALCULO_SALARIO :

        require_once(modification("model/pessoal/CalculoFolhaSalario.model.php"));
        $oCalculoFinanceiro = new CalculoFolhaSalario($this);
        break;

      case CalculoFolha::CALCULO_SUPLEMENTAR :

        require_once(modification("model/pessoal/CalculoFolhaSalario.model.php"));
        $oCalculoFinanceiro = new CalculoFolhaSalario($this);
        break;

      case CalculoFolha::CALCULO_ADIANTAMENTO:

        require_once(modification("model/pessoal/CalculoFolhaAdiantamento.model.php"));
        $oCalculoFinanceiro = new CalculoFolhaAdiantamento($this);
        break;

      case CalculoFolha::CALCULO_COMPLEMENTAR:

        require_once(modification("model/pessoal/CalculoFolhaComplementar.model.php"));
        $oCalculoFinanceiro = new CalculoFolhaComplementar($this);
        break;

      case CalculoFolha::CALCULO_RESCISAO:

        require_once(modification("model/pessoal/CalculoFolhaRescisao.model.php"));
        $oCalculoFinanceiro = new CalculoFolhaRescisao($this);
        break;

      case CalculoFolha::CALCULO_13o:

        require_once(modification("model/pessoal/CalculoFolha13o.model.php"));
        $oCalculoFinanceiro = new CalculoFolha13o($this);
        break;

      case CalculoFolha::CALCULO_FERIAS:

        require_once(modification("model/pessoal/CalculoFolhaFerias.model.php"));
        $oCalculoFinanceiro = new CalculoFolhaFerias($this);
        break;

      case CalculoFolha::CALCULO_PROVISAO_FERIAS:

        require_once(modification("model/pessoal/CalculoFolhaProvisaoFerias.model.php"));
        $oCalculoFinanceiro = new CalculoFolhaProvisaoFerias($this);
        break;

      case CalculoFolha::CALCULO_PROVISAO_13o :

        require_once(modification("model/pessoal/CalculoFolhaProvisao13o.model.php"));
        $oCalculoFinanceiro = new CalculoFolhaProvisao13o($this);
        break;

      case CalculoFolha::CALCULO_PONTO_FIXO :

        require_once(modification("model/pessoal/CalculoFolhaProvisao13o.model.php"));
        $oCalculoFinanceiro = new CalculoFolhaFixo($this);
        break;

      default:
        throw new BusinessException("Calculo não implementado: " . $sCalculo);
        break;
    }

    return $oCalculoFinanceiro;
  }

  /**
   * Retorna o Ponto pelo tipo de ponto
   *
   * @param string $sPonto - tabela de ponto
   * @return \Ponto
   * @throws \BusinessException
   * @access public
   */
  public function getPonto($sPonto) {

    switch ($sPonto) {

      case Ponto::COMPLEMENTAR :

        require_once(modification("model/pessoal/PontoComplementar.model.php"));
        $oPonto = new PontoComplementar($this);
        break;

      case Ponto::FERIAS :

        require_once(modification("model/pessoal/PontoFerias.model.php"));
        $oPonto = new PontoFerias($this);
        break;

      case Ponto::FIXO :

        require_once(modification("model/pessoal/PontoFixo.model.php"));
        $oPonto = new PontoFixo($this);
        break;

      case Ponto::SALARIO :

        require_once(modification("model/pessoal/PontoSalario.model.php"));
        $oPonto = new PontoSalario($this);
        break;

      case Ponto::ADIANTAMENTO :

        require_once(modification("model/pessoal/PontoAdiantamento.model.php"));
        $oPonto = new PontoAdiantamento($this);
        break;

      case Ponto::PONTO_13o :

        require_once(modification("model/pessoal/Ponto13o.model.php"));
        $oPonto = new Ponto13o($this);
        break;

      case Ponto::RESCISAO :

        require_once(modification("model/pessoal/PontoRescisao.model.php"));
        $oPonto = new PontoRescisao($this);
        break;

      case Ponto::PROVISAO_13o:

        require_once(modification("model/pessoal/PontoProvisao13o.model.php"));
        $oPonto = new PontoProvisao13o($this);
        break;

      case Ponto::PROVISAO_FERIAS:

        require_once(modification("model/pessoal/PontoProvisaoFerias.model.php"));
        $oPonto = new PontoProvisaoFerias($this);
        break;

      default:
        throw new BusinessException("Ponto não implementado: " . $sPonto);
        break;
    }

    return $oPonto;
  }

  /**
   * Retorna uma coleção de objetos da classe dependente, relacionados ao servidor instânciado no objeto
   * @throws BusinessException Matrícula não informada
   * @return Dependente[]
   */
  public function getDependentes() {

    require_once(modification('model/pessoal/Dependente.model.php'));

    if (empty($this->iMatricula)) {
      throw new BusinessException('Matrícula do servidor não informada para consulta dos dependentes.');
    }

    $oDaoRhDepend    = db_utils::getDao('rhdepend');
    $sSqlDependentes = $oDaoRhDepend->sql_query_file(null,
      "*",
      "rh31_codigo",
      "rh31_regist = {$this->getMatricula()}");
    $rsDependentes   = $oDaoRhDepend->sql_record($sSqlDependentes);

    if(!$rsDependentes || pg_num_rows($rsDependentes) == 0) {
      return array();
    }

    $aDependentes    = db_utils::getCollectionByRecord($rsDependentes);

    foreach ($aDependentes as $oDependente) {
      $this->aDependentes[$oDependente->rh31_codigo] = new Dependente($oDependente->rh31_codigo);
    }

    return $this->aDependentes;

  }

  /**
   * Retorna a variável da progressão do Salário Base do Servidor
   *
   * @param integer $iAnoCompetencia
   * @param integer $iMesCompetencia
   * @param integer $iMatricula
   * @param integer $iInstituicao
   * @param string $sVariavel
   * @return void|number
   */
  public function getValorVariaveisCalculo($iAnoCompetencia, $iMesCompetencia, $iMatricula, $iInstituicao, $sVariavel) {

    $oDaoRhPessoalMov          = new cl_rhpessoalmov();
    $sSqlValorVariaveisCalculo = $oDaoRhPessoalMov->sql_queryValorVariaveisCalculo($iAnoCompetencia, $iMesCompetencia, $iMatricula, $iInstituicao);
    $rsValorVariaveisCalculo   = $oDaoRhPessoalMov->sql_record($sSqlValorVariaveisCalculo);

    if (!$rsValorVariaveisCalculo || pg_num_rows($rsValorVariaveisCalculo) == 0) {
      return;
    }

    switch ($sVariavel) {

      case Servidor::VARIAVEL_SALARIO_BASE_PROGRESSAO :
        return db_utils::fieldsMemory($rsValorVariaveisCalculo, 0)->variavel_salario_base_progressao;
        break;

      default :
        return 0;
        break;
    }
  }

  /**
   * Retorna o servidor de origem da matricula, quando for um pensionista
   * @throws Exception Matricula deve ser informada
   * @return object Servidor
   */
  public function getServidorOrigem() {

    if (empty($this->iMatricula)) {
      throw new Exception('Matrícula do servidor não informada.');
    }

    $oDaoRhpesorigem    = db_utils::getDao('rhpesorigem');
    $sSqlServidorOrigem = $oDaoRhpesorigem->sql_queryServidorOrigem($this->getMatricula());
    $rsServidorOrigem   = $oDaoRhpesorigem->sql_record($sSqlServidorOrigem);

    if (!$rsServidorOrigem || pg_num_rows($rsServidorOrigem) == 0) {
      return false;
    }

    $oServidor = db_utils::fieldsMemory($rsServidorOrigem, 0);

    return new Servidor($oServidor->rh01_regist, $this->iAnoCompetencia, $this->iMesCompetencia, $oServidor->rh01_instit);
  }

  /**
   * Retorna codigo da tarefa.
   *
   * @access public
   * @return integer
   */
  public function getCodigoLotacao() {
    return $this->iCodigoLotacao;
  }

  /**
   * Retorna o valor do salario.
   *
   * @access public
   * @return integer
   */
  public function getSalario() {
    return $this->iSalario;
  }

  public function getSalarioBase() {

    $oVariaveis = DBPessoal::getVariaveisCalculo($this);
    return $oVariaveis->f010;
  }

  /**
   * Retorna se o servidor esta ativo nesta competencia
   * @return bool
   * @throws \BusinessException
   */
  public function isAtivo() {

    if (empty($this->iMatricula)) {
      throw new BusinessException('Matrícula do servidor não informada para consulta dos dependentes.');
    }

    $oDaoRhPessoal = db_utils::getDao('rhpessoal');
    $rsSituacao = $oDaoRhPessoal->sql_record( $oDaoRhPessoal->sql_verificaSituacaoServidor( $this->iMatricula,
      $this->iAnoCompetencia,
      $this->iMesCompetencia ));

    if ($rsSituacao && pg_num_rows($rsSituacao) > 0) {

      $oRhPessoal = db_utils::fieldsMemory($rsSituacao, 0);
      return ($oRhPessoal->rh30_vinculo == 'A');
    }

    throw new BusinessException('Não foi possivel verificar se o servidor esta ativo.');
  }

  /**
   * Retorna se o servidor esta rescindido nesta competencia
   * @return bool
   * @throws \BusinessException
   */
  public function isRescindido() {

    if (empty($this->iMatricula)) {
      throw new BusinessException('Matrícula do servidor não informada para consulta dos dependentes.');
    }

    $oDaoRhPessoal = db_utils::getDao('rhpessoal');
    $rsSituacao = $oDaoRhPessoal->sql_record( $oDaoRhPessoal->sql_verificaSituacaoServidor( $this->iMatricula,
      $this->iAnoCompetencia,
      $this->iMesCompetencia ));

    if ($rsSituacao && pg_num_rows($rsSituacao) > 0) {

      $oRhPessoal = db_utils::fieldsMemory($rsSituacao, 0);
      return (!empty($oRhPessoal->rh05_recis));
    }

    throw new BusinessException('Não foi possivel verificar se o servidor esta rescindido.');
  }

  /**
   * Retorna se o Servidor está afastado na competencia
   * @return bool
   * @throws \BusinessException
   */
  public function isAfastado() {

    if(empty($this->iMatricula) || empty($this->iAnoCompetencia) || empty($this->iMesCompetencia) || empty($this->iCodigoInstituicao) ) {
      throw new BusinessException("Ocorreu um erro ao consultar os afastamentos para o servidor.");
    }

    $rsAfastamentoServidor = db_query("select conta_dias_afasta({$this->iMatricula}, 
                                                                {$this->iAnoCompetencia}, 
                                                                {$this->iMesCompetencia}, 
                                                                ndias({$this->iAnoCompetencia}, {$this->iMesCompetencia}),
                                                                {$this->iCodigoInstituicao}) as afastamento");


    if(!$rsAfastamentoServidor) {
      throw new BusinessException("Ocorreu um erro ao consultar os afastamentos para o servidor.");
    }

    if(pg_num_rows($rsAfastamentoServidor) > 0) {

      $nAfastamento = db_utils::fieldsMemory($rsAfastamentoServidor, 0)->afastamento;
      return $nAfastamento > 0 ? $nAfastamento : false;
    }

    return false;
  }

  /**
   * Retorna se o servidor está afastado no RH
   * @param  \DBDate $dataAfastamento
   * @return bool
   */
  public function isAfastadoNoRH(\DBDate $dataAfastamento) {

    $assentamentos = AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($this, 'A', $dataAfastamento);

    if(empty($assentamentos)) {
      return false;
    }

    return true;
  }

  /**
   * getContaBancaria
   *
   * @access public
   * @return \ContaBancaria
   * @throws \DBException
   */
  public function getContaBancaria() {

    if ( is_null($this->oContaBancaria) && $this->iCodigoMovimentacao) {

      $oDaoRHPessoalMovContaBancaria = new cl_rhpessoalmovcontabancaria();
      $sSqlContaBancaria             = $oDaoRHPessoalMovContaBancaria->sql_query_file(null, "rh138_contabancaria", null, "rh138_rhpessoalmov = {$this->iCodigoMovimentacao}");
      $rsContaServidor               = db_query( $sSqlContaBancaria );
      if ( !$rsContaServidor ) {
        throw new DBException("Erro ao buscar os dados da Conta Bancária.");
      }

      $iCodigo = null;

      if ( pg_num_rows($rsContaServidor) > 0 ) {
        $iCodigo = db_utils::fieldsMemory($rsContaServidor, 0)->rh138_contabancaria;
        $this->oContaBancaria  = new ContaBancaria($iCodigo);
      } else {
        $this->oContaBancaria  = new ContaBancaria();
      }
    }
    return $this->oContaBancaria;
  }

  public function setContaBancaria(ContaBancaria $oConta) {
    $this->oContaBancaria = $oConta;
  }
  public function getCodigoMovimentacao() {
    return $this->iCodigoMovimentacao;
  }

  public function salvar() {

    if ( is_null($this->oContaBancaria) ) {
      return true;
    }

    $iCodigoContaBancaria          = $this->oContaBancaria->salvar();
    $oDaoRHPessoalMovContaBancaria = new cl_rhpessoalmovcontabancaria();
    db_query("delete from rhpessoalmovcontabancaria where rh138_rhpessoalmov = $this->iCodigoMovimentacao;");
    $oDaoRHPessoalMovContaBancaria->rh138_rhpessoalmov = $this->iCodigoMovimentacao;
    $oDaoRHPessoalMovContaBancaria->rh138_contabancaria= $iCodigoContaBancaria;
    $oDaoRHPessoalMovContaBancaria->rh138_instit       = db_getsession("DB_instit");
    $oDaoRHPessoalMovContaBancaria->incluir(null);
    return true;
  }

  /**
   * Retorna a Instancia de Servidor vinculada ao CGM do Servidor Atual
   * @return \Servidor
   * @throws \BusinessException
   * @throws \DBException
   */
  public function getServidorVinculado() {

    if ( $this->oDuploVinculo !== null ) {
      return $this->oDuploVinculo;
    }

    $oDaoRHPessoalMov = new cl_rhpessoalmov();
    $sSqlVinculo      = $oDaoRHPessoalMov->sql_duplo_vinculo_matricula( $this->getMatricula(), $this->getAnoCompetencia(), $this->getMesCompetencia() );

    $rsQuery = db_query($sSqlVinculo);

    if ( !$rsQuery ) {
      throw new DBException("Erro ao buscar vinculo do Servidor");
    }

    if ( pg_num_rows($rsQuery) == 0 ) {
      return false;
    }
    $iMatricula          =  db_utils::fieldsMemory($rsQuery, 0)->rh01_regist;
    $this->oDuploVinculo =  ServidorRepository::getInstanciaByCodigo( $iMatricula, $this->getAnoCompetencia(), $this->getMesCompetencia());
    return true;
  }

  /**
   * Verifica se o servidor tem duplo vinculo
   */
  public function hasServidorVinculado() {

    $this->getServidorVinculado();
    return $this->oDuploVinculo !== null;
  }

  /**
   * Define se o servidor possui ou não Abono de Permanência.
   * @param boolean $lAbonoPermanencia
   */
  public function setAbonoPermanencia($lAbonoPermanencia){
    $this->lAbonoPermanencia = (boolean) ($lAbonoPermanencia == 't');
  }

  /**
   * Verifica se o Servidor possui Abono de Permanência.
   * @return boolean - True Possuí abono de permanência.
   *                 - False Não Possuí Abono de Permanência.
   */
  public function hasAbonoPermanencia(){
    return $this->lAbonoPermanencia;
  }

  /**
   * Retorna o valor da margem consignável.
   *
   * @access public
   * @return Integer
   * @throws DBException
   */
  public function getMargemConsignavel($sRubrica = "R803") {

    /**
     * R803 é a rubrica da margem consignada.
     */
    $oDaoGerfsal         = new cl_gerfsal();
    $sRubricaSqlGerfsal  = $oDaoGerfsal->sql_query_file($this->iAnoCompetencia, $this->iMesCompetencia, $this->iMatricula, $sRubrica);
    $rsRubricaSqlGerfsal = db_query($sRubricaSqlGerfsal);

    if(!$rsRubricaSqlGerfsal) {
      throw new DBException(_M(self::MENSAGEM . "erro_consultar_margem_consignado"));
    }

    if (pg_num_rows($rsRubricaSqlGerfsal) > 0) {

      for ($i = 0; $i < pg_num_rows($rsRubricaSqlGerfsal); $i++) {

        $oBase = db_utils::fieldsMemory($rsRubricaSqlGerfsal, $i, false, false, true);

        if ($oBase->r14_rubric == $sRubrica) {
          return $oBase->r14_valor;
        }
      }
    }

    return false;
  }

  /**
   * Retorna uma lista de assentamentos de substituicao do servidor
   * @return \AssentamentoSubstituicao[]
   * @throws \BusinessException
   */
  public function getAssentamentosSubstituicao(){

    $aListaAssentamentos   = array();
    $oDaoAssentamento      = new cl_assenta();
    $sCamposAssentamento   = "h16_codigo as assentamento,
                              assentaloteregistroponto.*,
                              loteregistroponto.*";

    $sWhereAssentamento  = "h16_regist = {$this->iMatricula}                         ";
    $sWhereAssentamento .= "and h12_natureza = " . Assentamento::NATUREZA_SUBSTITUICAO;
    $sWhereAssentamento .= "and (rh160_assentamento is null                          ";
    $sWhereAssentamento .= "     or (rh155_ano     = {$this->iAnoCompetencia}        ";
    $sWhereAssentamento .= "         and rh155_mes = {$this->iMesCompetencia}))      ";

    $sSqlAssentamento      = $oDaoAssentamento->sql_query_assentamento_com_substituicao(null,
      $sCamposAssentamento,
      "h16_regist, h16_dtconc desc",
      $sWhereAssentamento);

    $rsAssentamento        = db_query($sSqlAssentamento);

    if(!$rsAssentamento) {
      throw new BusinessException("Erro ao buscar assentamentos para o servidor");
    } else {

      if( pg_num_rows($rsAssentamento) > 0) {

        $aAssentamentos = db_utils::getCollectionByRecord($rsAssentamento);

        foreach ($aAssentamentos as $oStdAssentamento) {

          $oAssentamento = AssentamentoRepository::getInstanceByCodigo($oStdAssentamento->assentamento);

          if($oAssentamento instanceof AssentamentoSubstituicao){
            $aListaAssentamentos[] = $oAssentamento;
          }
        }
      }
    }

    return $aListaAssentamentos;
  }

  /**
   * Retorna o número de dias de férias padrão do servidor
   * @return Integer
   */
  public function getDiasGozoFerias () {
    return $this->iDiasGozoFerias;
  }

  /**
   * Define o número de dias de férias padrão do servidor
   * @param Integer $iDiasGozoFerias
   */
  public function setDiasGozoFerias ($iDiasGozoFerias) {
    $this->iDiasGozoFerias = $iDiasGozoFerias;
  }

  /**
   * Verifica se possui vinculcado inativo ou pensionisa maior de 65 anos
   *
   * @return boolean [description]
   */
  public function hasVinculadoInativoPensionistaMaior65Anos() {

    $lVinculoServidorVinculadoInativo     = false;
    $lVinculoServidorVinculadoPensionista = false;
    $lServidorVinculadoMaior65Anos        = false;

    if($this->hasServidorVinculado()) {

      $oServidorVinculado = $this->getServidorVinculado();

      $lVinculoServidorVinculadoInativo     = $oServidorVinculado->getVinculo()->getTipo() == VinculoServidor::VINCULO_INATIVO;
      $lVinculoServidorVinculadoPensionista = $oServidorVinculado->getVinculo()->getTipo() == VinculoServidor::VINCULO_PENSIONISTA;

      if($oServidorVinculado->getIdade() >= 65) {

        $lServidorVinculadoMaior65Anos = true;

        if($lVinculoServidorVinculadoInativo || $lVinculoServidorVinculadoPensionista) {
          return true;
        }
      }
    }

    return false;
  }

  /**
   * Verifica se o servidor possui remuneracao no periodo *
   * Para os ervidor possuir remuneracao, ele nao deve ter seu pagamento suspenso nem estar afastado sem remuneracao
   * @return bool
   * @throws \BusinessException
   */
  public function temRemuneracaoNoPeriodo() {

    $oDaoAfastamento   = new cl_afasta();
    $iUltimoDiaCompetencia = cal_days_in_month(CAL_GREGORIAN, $this->getMesCompetencia(), $this->getAnoCompetencia());
    $sDataFinal = "{$this->getAnoCompetencia()}-{$this->getMesCompetencia()}-{$iUltimoDiaCompetencia}";
    $aWhere[] = "r45_anousu = {$this->getAnoCompetencia()}";
    $aWhere[] = "r45_mesusu = {$this->getMesCompetencia()}";
    $aWhere[] = "r45_regist = {$this->getMatricula()}";
    $aWhere[] = "r45_situac IN (2, 3, 4, 6, 7)";
    $aWhere[] = "(r45_dtreto is null or r45_dtreto >= '{$sDataFinal}')";
    $sWhere = implode(" and ", $aWhere);
    $sSqlAfastamentosSemRemuneracao = $oDaoAfastamento->sql_query_file(null, "*", null, $sWhere);
    $rsAfastamentos = db_query($sSqlAfastamentosSemRemuneracao);
    if (!$rsAfastamentos) {
      throw new BusinessException("Erro ao buscar afastamentos para o servidor");
    }

    if (pg_num_rows($rsAfastamentos) > 0) {
      return false;
    }
    return true;
  }

  /**
   * @param \DBCompetencia $oCompetencia
   * @return array
   * @throws \BusinessException
   */
  public function getAfastamentosNoPeriodo(DBCompetencia $oCompetencia = null) {

    if (empty($oCompetencia)) {
      $oCompetencia = new DBCompetencia($this->getAnoCompetencia(), $this->getMesCompetencia());
    }

    $aAfastamentos         = array();
    $oDaoAfastamento       = new cl_afasta();
    $iUltimoDiaCompetencia = cal_days_in_month(CAL_GREGORIAN, $oCompetencia->getMes(), $oCompetencia->getAno());

    $sDataFinal   = "{$oCompetencia->getAno()}-{$oCompetencia->getMes()}-{$iUltimoDiaCompetencia}";
    $sDataInicial = "{$oCompetencia->getAno()}-{$oCompetencia->getMes()}-01";
    $aWhere[] = "r45_anousu = {$oCompetencia->getAno()}";
    $aWhere[] = "r45_mesusu = {$oCompetencia->getMes()}";
    $aWhere[] = "r45_regist = {$this->getMatricula()}";

    $sWhereDatas  = " ((r45_dtreto is null or r45_dtreto >= '{$sDataFinal}')";
    $sWhereDatas .= " or (r45_dtafas >= '{$sDataInicial}' and r45_dtafas <= '{$sDataFinal}')";
    $sWhereDatas .= " or (r45_dtafas <= '{$sDataInicial}' and r45_dtreto >= '{$sDataInicial}'))";

    $aWhere[] = $sWhereDatas;
    $sWhere   = implode(" and ", $aWhere);
    $sSqlAfastamentosSemRemuneracao = $oDaoAfastamento->sql_query_file(null, "*", 'r45_dtafas, r45_dtreto', $sWhere);
    $rsAfastamentos                 = db_query($sSqlAfastamentosSemRemuneracao);
    if (!$rsAfastamentos) {
      throw new BusinessException("Erro ao buscar afastamentos para o servidor");
    }

    $iLinhasAfastamento = pg_num_rows($rsAfastamentos);
    for ($iAfastamento = 0; $iAfastamento < $iLinhasAfastamento; $iAfastamento++) {

      $oAfastamentoStd = db_utils::fieldsMemory($rsAfastamentos, $iAfastamento);
      $oAfastamento    = AfastamentoRepository::getInstanciaPorCodigo($oAfastamentoStd->r45_codigo);

      $oAfastamentoStd->dias = $oAfastamento->getNumeroDeDiasNaCompetencia($oCompetencia);
      if (isset($aAfastamentos[$oAfastamentoStd->r45_situac])) {
        $aAfastamentos[$oAfastamentoStd->r45_situac]->dias += $oAfastamentoStd->dias;
        continue;
      }
      $aAfastamentos[$oAfastamentoStd->r45_situac] = $oAfastamentoStd;
    }
    return $aAfastamentos;
  }

  /**
   * Retorna as escalas do servidor
   * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaServidor[]
   */
  public function getEscalas($oDataPonto = null) {
    return EscalaServidor::getEscalas($this, $oDataPonto);
  }

  /**
   * Define o PIS/PASEP
   * @param string
   */
  public function setPISPASEP ($sPISPASEP) {
    $this->sPISPASEP = $sPISPASEP;
  }

  /**
   * Retorna o PIS/PASEP
   * @return string
   */
  public function getPISPASEP () {
    return $this->sPISPASEP;
  }

  /**
   * Define se o servidor registra ponto eletrônico
   * @param boolean $lRegistraPontoEletronico
   */
  public function setDispensaLancamentoPonto ($lRegistraPontoEletronico) {
    $this->lRegistraPontoEletronico = $lRegistraPontoEletronico;
  }

  /**
   * Retorna se o servidor registra ponto eletrônico
   * @return bool
   */
  public function registraPontoEletronico () {
    return $this->lRegistraPontoEletronico;
  }
}