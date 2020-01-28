<?php 
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (c) 2014  DBSeller Servicos de Informatica             
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
 * Representação de arquivo do Consignet
 *
 * @abstract 
 * @author     Renan Melo  <renan@dbseller.com.br>
 * @author     Rafael Nery <rafael.nery@dbseller.com.br>
 * @package    Pessoal
 * @subpackage Arquivos
 */
class ArquivoConsignet { 

  const MENSAGEM = 'recursoshumanos.pessoal.ArquivoConsignet.';
 
  const MOTIVO_FALECIMENTO        = 1;
  const MOTIVO_SERVIDOR_INVALIDO  = 2;
  const MOTIVO_TIPO_CONTRATO      = 3;
  const MOTIVO_MARGEM_EXCEDIDO    = 4;
  const MOTIVO_OUTROS_MOTIVOS     = 5;
  const MOTIVO_SERVIDOR_DESLIGADO = 6;
  const MOTIVO_SERVIDOR_AFASTADO  = 7;

  /**
   * Codigo do Arquivo
   * @var Integer
   */
  private $iCodigo;

  /**
   * Array com os Registros do ponto
   * @var array
   */
  private $aRegistros = array();

  /**
   * Nome do arquivo
   * @var String
   */
  private $sNome;

  /**
   * Competencia do Arquivo
   * @var DBCompetencia
   */
  private $oCompetencia;

  /**
   * Instituição do Arquivo
   * @var Instituicao
   */
  private $oInstituicao;
  
  /**
   * Representa o OID do relatório da importação
   * @var Integer
   */
  private $iRelatorio;

  /**
   * Representa o estado do arquivo, se processado ou não (lançado no ponto)
   * @var Boolean
   */
  private $lProcessado;
  
  /**
   * Seta Nome do arquivo
   * @param String
   */
  public function setNome ($sNome) {
    $this->sNome = $sNome;
  }
  
  /**
   * Retorna Nome do arquivo
   * @return String
   */
  public function getNome () {
    return $this->sNome; 
  }

  /**
   * Seta a Competencia do Arquivo
   * @param DBCompetencia
   */
  public function setCompetencia (DBCompetencia $oCompetencia) {
    $this->oCompetencia = $oCompetencia;
  }
  
  /**
   * Retorna Competencia do Arquivo
   * @return DBCompetencia
   */
  public function getCompetencia () {
    return $this->oCompetencia; 
  }

  /**
   * Seta a Instituição do Arquivo
   * @param Instituicao
   */
  public function setInstituicao (Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }
  
  /**
   * Retorna a Instituicao do arquivo
   * @return Instituicao
   */
  public function getInstituicao () {
    return $this->oInstituicao; 
  }


  /**
   * Define o codigo sequencial do arquivo
   * @param Integer
   */
  public function setCodigo ($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Retorna o codigo sequencial do arquivo
   * @return Integer
   */
  public function getCodigo () {
    return $this->iCodigo; 
  }

  /** 
   * Define se o arquivo foi processado ou não
   */
  public function setProcessado ($lProcessado){
    $this->lProcessado = $lProcessado;
  }

  /**
   * Retorna o estado do arquivo, se processado ou não
   */
  public function getProcessado () {
    return $this->lProcessado;
  }

  public function adicionarRegistro(RegistroArquivoImportacaoConsignet $oRegistro ) {
    $this->aRegistros[] = $oRegistro;
  }

  public function limparRegistros(){
    $this->aRegistros = array();
  }

  /**
   * Carrega os registros em memória
   * @return
   */
  public function carregarRegistros($lTodosRegistros = true) {
    
    if ( empty($this->iCodigo) ) {

      $this->aRegistros = array();
      return false;
    }

    $oDaoRegistrosConsignadoMovimento = new cl_rhconsignadomovimento();
    $sWhereConsignadoMovimento        = "     rh151_sequencial = {$this->iCodigo}";
    
    if ($lTodosRegistros === false) {
      $sWhereConsignadoMovimento     .= " and rh152_consignadomotivo is null";
    }
    
    $sSqlRegistros                    = $oDaoRegistrosConsignadoMovimento->sql_query_com_registros(null, "*", null, $sWhereConsignadoMovimento);
    $rsRegistros                      = db_query($sSqlRegistros);

    if (!$rsRegistros) {
      throw new DBException(_M(self::MENSAGEM . 'erro_carregar_dados'));
    }

    $iQuantidadeRegistros = pg_num_rows($rsRegistros);

    if ($iQuantidadeRegistros == 0) {

      $this->aRegistros = array();
      return false;
    }

    for ( $iRegistro = 0; $iRegistro < $iQuantidadeRegistros; $iRegistro++ ) {

      $oDadosRegistro = db_utils::fieldsMemory($rsRegistros, $iRegistro); 
      try {
        $oServidor      = ServidorRepository::getInstanciaByCodigo($oDadosRegistro->rh152_regist, $this->oCompetencia->getAno(), $this->oCompetencia->getMes(), $this->oInstituicao->getSequencial());
      } catch ( BusinessException $eErro ) {
        $oServidor      = new Servidor();
        $oServidor->setMatricula($oDadosRegistro->rh152_regist);
      }

      try { 
        $oRubrica = RubricaRepository::getInstanciaByCodigo( $oDadosRegistro->rh153_rubrica, $this->oInstituicao->getSequencial());
      } catch ( BusinessException $eException ) {

        $oRubrica = new Rubrica();
        $oRubrica->setCodigo( $oDadosRegistro->rh153_rubrica );
        $oRubrica->setInstituicao( $this->oInstituicao->getSequencial() );
      }

      $oRegistro = new RegistroArquivoImportacaoConsignet();
      
      $oRegistro->setCodigoArquivo($oDadosRegistro->rh151_sequencial);
      $oRegistro->setArquivo($this);
      $oRegistro->setSequencialMovimentoServidor($oDadosRegistro->rh152_sequencial);
      $oRegistro->setSequencialMovimentoServidorRubrica($oDadosRegistro->rh153_sequencial);
      $oRegistro->setMatricula($oDadosRegistro->rh152_regist);
      $oRegistro->setNome($oDadosRegistro->rh152_nome);
      $oRegistro->setRubric($oDadosRegistro->rh153_rubrica);
      $oRegistro->setValorParcela($oDadosRegistro->rh153_valordescontar);
      $oRegistro->setValorDescontado($oDadosRegistro->rh153_valordescontado);
      $oRegistro->setParcela($oDadosRegistro->rh153_parcela);
      $oRegistro->setTotalParcelas($oDadosRegistro->rh153_totalparcelas);
      $oRegistro->setMotivo($oDadosRegistro->rh152_consignadomotivo);
      
      $oRegistro->setServidor($oServidor);
      $oRegistro->setRubrica($oRubrica);

      $this->adicionarRegistro($oRegistro);
    }
  }
   
  /**
   * Retorna todos os registros ponto do arquivo
   * 
   * @return RegistroPontoConsignet[]
   */
  public function getRegistros() {
    return $this->aRegistros;
  }
  
  /**
   * Retorna o OID do relatório
   * 
   * @access public
   * @return Integer
   */
  public function getRelatorio() {
    return $this->iRelatorio;
  }

  /**
   * Seta o OID do relatório
   * 
   * @access public
   * @param Integer $iRelatorio
   */
  public function setRelatorio($iRelatorio) {
    $this->iRelatorio = $iRelatorio;
  }
  
}
