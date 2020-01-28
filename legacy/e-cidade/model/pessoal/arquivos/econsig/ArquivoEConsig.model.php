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
 * Representação de arquivo do e-Consig
 *
 * @abstract 
 * @author     Renan Melo  <renan@dbseller.com.br>
 * @author     Rafael Nery <rafael.nery@dbseller.com.br>
 * @package    Pessoal
 * @subpackage Arquivos
 */
class ArquivoEConsig { 

  const MENSAGEM = 'recursoshumanos.pessoal.ArquivoEConsig.';
 
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
  private $aRegistrosPonto = array();

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

  public function adicionarRegistro( RegistroPontoEconsig $oRegistro ) {
    $this->aRegistrosPonto[] = $oRegistro;
  }

  /**
   * Carrega os registros em memória
   * @return
   */
  public function carregarRegistros() {
    
    if ( empty($this->iCodigo) ) {

      $this->aRegistrosPonto =  array();
      return false;
    }

    $oDaoRegistrosEConsigRubrica  = new cl_econsigmovimentoservidorrubrica();
    $sSqlRegistros                = $oDaoRegistrosEConsigRubrica->sql_query(null, "rh134_sequencial, rh134_regist, rh135_rubrica, rh135_valor, rh134_econsigmotivo, rh134_nome", null, "rh133_sequencial = {$this->iCodigo}");
    $rsRegistros                  = db_query($sSqlRegistros);

    if (!$rsRegistros) {
      throw new DBException(_M(self::MENSAGEM . 'erro_carregar_dados'));
    }

    $iQuantidadeRegistros  = pg_num_rows($rsRegistros);

    if ($iQuantidadeRegistros  == 0) {

      $this->aRegistrosPonto =  array();
      return false;
    }

    for ( $iRegistro = 0; $iRegistro < $iQuantidadeRegistros; $iRegistro++ ) {

      $oDadosRegistro = db_utils::fieldsMemory($rsRegistros, $iRegistro); 
      try {
        $oServidor      = ServidorRepository::getInstanciaByCodigo($oDadosRegistro->rh134_regist, $this->oCompetencia->getAno(), $this->oCompetencia->getMes(), $this->oInstituicao->getSequencial());
      } catch ( BusinessException $eErro ) {
        $oServidor      = new Servidor();
        $oServidor->setMatricula($oDadosRegistro->rh134_regist);
      }
     
      try { 
        $oRubrica = RubricaRepository::getInstanciaByCodigo( $oDadosRegistro->rh135_rubrica, $this->oInstituicao->getSequencial());
      } catch ( BusinessException $eException ) {

        $oRubrica = new Rubrica();
        $oRubrica->setCodigo( $oDadosRegistro->rh135_rubrica );
        $oRubrica->setInstituicao( $this->oInstituicao->getSequencial() );
      }

      $oRegistroPonto = new RegistroPontoEconsig();
      $oRegistroPonto->setSequencial($oDadosRegistro->rh134_sequencial);
      $oRegistroPonto->setRubrica($oRubrica);
      $oRegistroPonto->setServidor($oServidor);
      $oRegistroPonto->setQuantidade(1);
      $oRegistroPonto->setNome($oDadosRegistro->rh134_nome);
      $oRegistroPonto->setValor($oDadosRegistro->rh135_valor);
      $oRegistroPonto->setMotivo($oDadosRegistro->rh134_econsigmotivo);

      $this->adicionarRegistro($oRegistroPonto);
    }
  }
   
  /**
   * Retorna todos os registros ponto do arquivo
   * 
   * @return RegistroPontoEconsig[]
   */
  public function getRegistros() {
    return $this->aRegistrosPonto;
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
