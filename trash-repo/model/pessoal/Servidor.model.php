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

require_once("std/DBNumber.php");
require_once("std/DBDate.php");
require_once('model/pessoal/VinculoServidor.model.php');
require_once("libs/exceptions/ParameterException.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/FileException.php");
require_once("libs/exceptions/BusinessException.php");
require_once('model/pessoal/std/DBPessoal.model.php');

/**
 * Classe para manipuação de servidores
 *
 * @author   Alberto Ferri Neto alberto@dbseller.com.br
 * @package  Pessoal
 * @revision $Author: dbmauricio $
 * @version  $Revision: 1.27 $
 */
class Servidor {

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
  
  const VARIAVEL_SALARIO_BASE_PROGRESSAO = 'F010';

  /**
   * Método construtor da classe Servidor. Caso seja setada a matrícula, deve ser carregado os dados do servidor
   */
  public function __construct($iMatricula = null, $iAnoCompetencia = null, $iMesCompetencia = null, $iInstituicao = null) {

    $this->iAnoCompetencia    = DBPessoal::getAnoFolha();
    $this->iMesCompetencia    = DBPessoal::getMesFolha();
    $this->iCodigoInstituicao = db_getsession("DB_instit");

    if ( !empty($iAnoCompetencia) ) {
      $this->iAnoCompetencia = $iAnoCompetencia;
    } 

    if ( !empty($iMesCompetencia) ) {
      $this->iMesCompetencia = $iMesCompetencia;
    } 

    if ( !empty($iInstituicao) ) {
      $this->iCodigoInstituicao = $iInstituicao;
    } 

    if (!empty($iMatricula)) {

      if ( !DBNumber::isInteger( $iMatricula ) ) {
        throw new ParameterException("A Matrícula deve ser um Numero Inteiro");
      }

      $this->setMatricula($iMatricula);
      $oDaoRhPessoalMov = new cl_rhpessoalmov();
      $sSqlRhPessoalMov = $oDaoRhPessoalMov->sql_queryDadosServidor($this->iAnoCompetencia, $this->iMesCompetencia,$this->iCodigoInstituicao, $iMatricula);
      $rsRhPessoal      = db_query($sSqlRhPessoalMov);

      if (!$rsRhPessoal) {
        throw new DBException("Erro ao Buscar Servidor." . pg_last_error());
      }

      if ( pg_num_rows( $rsRhPessoal ) == 0 ) {
        throw new BusinessException("Servidor com a Matricula: {$iMatricula} não está na compentencia: {$this->iMesCompetencia}/{$this->iAnoCompetencia}");
      }

      $oRhPessoal    = db_utils::fieldsMemory($rsRhPessoal, 0);
      $this->iNumCgm = $oRhPessoal->rh01_numcgm;

      $this->setCodigoCargo($oRhPessoal->rh01_funcao);
      $this->setTipoAdmissao($oRhPessoal->rh01_tipadm);
      $this->setCodigoInstituicao($oRhPessoal->rh01_instit);
      $this->setNumeroPonto($oRhPessoal->rh01_ponto);
      $this->setObservacaoServidor($oRhPessoal->rh01_observacao);
      $this->setTabelaPrevidencia($oRhPessoal->rh02_tbprev);
      $this->setTipoExposicaoAgentesNocivos($oRhPessoal->rh02_ocorre);
      $this->setCodigoRegime($oRhPessoal->rh02_codreg);
      $this->iCodigoLotacao = $oRhPessoal->rh02_lota;
     
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
   * @return object
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
  public function setCgm(CgmBase $oCgm) {
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

    require_once 'model/configuracao/InstituicaoRepository.model.php';
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
   * @return void
   */
  public function getAnoCompetencia() {
    return $this->iAnoCompetencia;
  }

  /**
   * Retorna o mes da competencia da folha
   *
   * @access public
   * @return void
   */
  public function getMesCompetencia() {
    return $this->iMesCompetencia;
  }

  /**
   * Retorna o código do regime 
   *
   * @param integer $iCodigoRegime
   * @access public
   * @return void
   */
  public function getCodigoRegime() {

    $oDaoRhRegime = db_utils::getDao('rhregime');
    $sSqlRhRegime = $oDaoRhRegime->sql_query_file ($this->iCodigoRegime);
    $rsRhRegime   = $oDaoRhRegime->sql_record($sSqlRhRegime);
    if ( $oDaoRhRegime->numrows == 0 ) {
      return;
    }

    return db_utils::fieldsMemory($rsRhRegime, 0)->rh30_regime;
  }

  /**
   * Retorna o código do regime 
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
   * Retorna o código do regime 
   *
   * @param integer $iCodigoRegime
   * @access public
   * @return void
   */
  public function getVinculo() {

    $oDaoRhRegime = db_utils::getDao('rhregime');
    $sSqlRhRegime = $oDaoRhRegime->sql_query_file ($this->iCodigoRegime);
    $rsRhRegime   = $oDaoRhRegime->sql_record($sSqlRhRegime);
    if ( $oDaoRhRegime->numrows == 0 ) {
      return;
    }

    return db_utils::fieldsMemory($rsRhRegime, 0)->rh30_vinculo;
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

    require_once('model/pessoal/CalculoFolha.model.php');
    
    $oCalculoFinanceiro =  null;

    switch ($sCalculo) {

      case CalculoFolha::CALCULO_SALARIO :
        
        require_once("model/pessoal/CalculoFolhaSalario.model.php");
        $oCalculoFinanceiro = new CalculoFolhaSalario($this);
      break;

      case CalculoFolha::CALCULO_ADIANTAMENTO:
      
        require_once("model/pessoal/CalculoFolhaAdiantamento.model.php");
        $oCalculoFinanceiro = new CalculoFolhaAdiantamento($this);
      break;

      case CalculoFolha::CALCULO_COMPLEMENTAR:
 
        require_once("model/pessoal/CalculoFolhaComplementar.model.php");
        $oCalculoFinanceiro = new CalculoFolhaComplementar($this);
      break;

      case CalculoFolha::CALCULO_RESCISAO:
        
        require_once("model/pessoal/CalculoFolhaRescisao.model.php");
        $oCalculoFinanceiro = new CalculoFolhaRescisao($this);
      break;

      case CalculoFolha::CALCULO_13o:

        require_once("model/pessoal/CalculoFolha13o.model.php");
        $oCalculoFinanceiro = new CalculoFolha13o($this);
      break;

      case CalculoFolha::CALCULO_FERIAS:

        require_once("model/pessoal/CalculoFolhaFerias.model.php");
        $oCalculoFinanceiro = new CalculoFolhaFerias($this);
      break;

      case CalculoFolha::CALCULO_PROVISAO_FERIAS:

        require_once("model/pessoal/CalculoFolhaProvisaoFerias.model.php");
        $oCalculoFinanceiro = new CalculoFolhaProvisaoFerias($this);
      break;

      case CalculoFolha::CALCULO_PROVISAO_13o :
        
        require_once("model/pessoal/CalculoFolhaProvisao13o.model.php");
        $oCalculoFinanceiro = new CalculoFolhaProvisao13o($this);
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
   * @access public
   * @return Ponto
   */
  public function getPonto($sPonto) {

    switch ($sPonto) {

      case Ponto::COMPLEMENTAR :
        
        require_once("model/pessoal/PontoComplementar.model.php");
        $oPonto = new PontoComplementar($this);

      break;

      case Ponto::FERIAS :

        require_once("model/pessoal/PontoFerias.model.php");
        $oPonto = new PontoFerias($this);

      break;

      case Ponto::FIXO :

        require_once("model/pessoal/PontoFixo.model.php");
        $oPonto = new PontoFixo($this);

      break;

      case Ponto::SALARIO :

        require_once("model/pessoal/PontoSalario.model.php");
        $oPonto = new PontoSalario($this);

      break;

      case Ponto::ADIANTAMENTO :

        require_once("model/pessoal/PontoAdiantamento.model.php");
        $oPonto = new PontoAdiantamento($this);

      break;

      case Ponto::PONTO_13o :

        require_once("model/pessoal/Ponto13o.model.php");
        $oPonto = new Ponto13o($this);

      break;

      case Ponto::RESCISAO :

        require_once("model/pessoal/PontoRescisao.model.php");
        $oPonto = new PontoRescisao($this);

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
	 * @return multitype: array
	 */
	public function getDependentes() {
		
		require_once('model/pessoal/Dependente.model.php');
		
		if (empty($this->iMatricula)) {
			throw new BusinessException('Matrícula do servidor não informada para consulta dos dependentes.');
		}
		
		$oDaoRhDepend    = db_utils::getDao('rhdepend');
		$sSqlDependentes = $oDaoRhDepend->sql_query_file(null, 
				                                             "*", 
																										 "rh31_codigo", 
				                                             "rh31_regist = {$this->getMatricula()}");
		$rsDependentes   = $oDaoRhDepend->sql_record($sSqlDependentes);
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
		
		if ($oDaoRhPessoalMov->numrows == 0) {
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

    if ($oDaoRhpesorigem->numrows == 0) {
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
   * Retorna se o servidor esta ativo nesta competencia
   *
   * @return boolean
   */
  public function isAtivo() {

    if (empty($this->iMatricula)) {
      throw new BusinessException('Matrícula do servidor não informada para consulta dos dependentes.');
    }

    $oDaoRhPessoal = db_utils::getDao('rhpessoal');
    $rsSituacao = $oDaoRhPessoal->sql_record( $oDaoRhPessoal->sql_verificaSituacaoServidor( $this->iMatricula, 
                                                                                            $this->iAnoCompetencia, 
                                                                                            $this->iMesCompetencia ));

    if ($oDaoRhPessoal->numrows > 0) {

      $oRhPessoal = db_utils::fieldsMemory($rsSituacao, 0);
      return ($oRhPessoal->rh30_vinculo == 'A');
    }

    throw new BusinessException('Não foi possivel verificar se o servidor esta ativo.');
  }

  /**
   * Retorna se o servidor esta rescindido nesta competencia
   *
   * @return boolean
   */
  public function isRescindido() {
    if (empty($this->iMatricula)) {
      throw new BusinessException('Matrícula do servidor não informada para consulta dos dependentes.');
    }

    $oDaoRhPessoal = db_utils::getDao('rhpessoal');
    $rsSituacao = $oDaoRhPessoal->sql_record( $oDaoRhPessoal->sql_verificaSituacaoServidor( $this->iMatricula, 
                                                                                            $this->iAnoCompetencia, 
                                                                                            $this->iMesCompetencia ));

    if ($oDaoRhPessoal->numrows > 0) {
      $oRhPessoal = db_utils::fieldsMemory($rsSituacao, 0);
      return (!empty($oRhPessoal->rh05_recis));
    }

    throw new BusinessException('Não foi possivel verificar se o servidor esta rescindido.');
  }

}