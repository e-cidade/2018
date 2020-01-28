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
 * Pontos de parada do transporte
 * @author Iuri Guntchnigg
 * @package transporteescolar
 * @version $Revision: 1.5 $
 */
class PontoParada {

  /**
   * Codigo do ponto de Parada
   * @var integer
   */
  protected $iCodigo = null;

  /**
   * Codigo do rua e bairro em que o ponto de parada se encontra
   * @var integer
   */
  protected $iCodigoRuaBairro = null;

  /**
   * Nome do ponto de parada
   * @var string
   */
  protected $sNome = '';

  /**
   * Abreviatura do ponto de parada
   * @var string
   */
  protected $sAbreviatura = '';

  /**
   * Latitude do ponto de Parada
   * @var float
   */
  protected $nLatitude = '';

  /**
   * Longitude de parada
   * @var float
   */
  protected $nLongitude = '';

  /**
   * ponto de referencia do ponto de parada
   * @var string
   */
  protected $sPontoReferencia = '';

  /**
   * Departamento vinculado ao ponto de parada
   * @var DBDepartamento
   */
  protected $oDBDepartamento = null;

  /**
   * Codigo do departamento
   * @var integer
   */
  protected $iCodigoDepartamento = null;

  /**
   * tipo do ponto de parada
   * 1 - departamento
   * 2 - Rua
   * @var integer
   */
  protected $iTipo = 1;

  /**
   * Escola de procedencia
   * @var EscolaProcedencia
   */
  protected $oEscolaProcedencia = null;

  /**
   * Instancia um Ponto de parada
   * @param string $iCodigo Codigo do ponto de parada
   * @throws ParameterException codigo não é do tipo inteiro
   * @throws BusinessException Codigo informado não existe no sistema
   */
  function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      if (!DBNumber::isInteger($iCodigo)) {
        throw new ParameterException('Parâmetro $iCodigo deve ser um inteiro.');
      }
      $oDaoPontoParada = new cl_pontoparada();
      $sSqlPontoParada = $oDaoPontoParada->sql_query_departamento($iCodigo);
      $rsPontoParada   = $oDaoPontoParada->sql_record($sSqlPontoParada);
      if ($oDaoPontoParada->numrows == 0) {

        $oVariaveis         = new stdClass();
        $oVariaveis->codigo = $iCodigo;
        throw new BusinessException(_M('educacao.transporteescolar.PontoParada.ponto_nao_cadastrado', $oVariaveis));
      }
      $oDadosPontoParada         = db_utils::fieldsMemory($rsPontoParada, 0);
      $this->iCodigo             = $oDadosPontoParada->tre04_sequencial;
      $this->iCodigoDepartamento = $oDadosPontoParada->tre05_db_depart;

      $this->setAbreviatura($oDadosPontoParada->tre04_abreviatura);
      $this->setCodigoRuaBairro($oDadosPontoParada->tre04_cadenderbairrocadenderrua);
      $this->setLatitude($oDadosPontoParada->tre04_latitude);
      $this->setLongitude($oDadosPontoParada->tre04_longitude);
      $this->setNome($oDadosPontoParada->tre04_nome);
      $this->setPontoReferencia($oDadosPontoParada->tre04_pontoreferencia);
      $this->setTipo($oDadosPontoParada->tre04_tipo);

      if ( !empty($oDadosPontoParada->tre13_escolaproc) ) {
        $this->setEscolaProcedencia(EscolaProcedenciaRepository::getEscolaByCodigo($oDadosPontoParada->tre13_escolaproc));
      }
    }
  }

  /**
   * retorna o codigo do ponto de parada
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o rua e bairro do ponto de parada
   * @return number
   */
  public function getCodigoRuaBairro() {
    return $this->iCodigoRuaBairro;
  }

  /**
   * Seta a rua e bairro do ponto de parada
   * @param integer$iCodigoRuaBairro
   */
  public function setCodigoRuaBairro($iCodigoRuaBairro) {
    $this->iCodigoRuaBairro = $iCodigoRuaBairro;
  }

  /**
   * Retorna o nome do ponto de parada
   * @return string
   */
  public function getNome() {
      return $this->sNome;
  }

  /**
   * Define o nome do ponto de parada
   * @param string $sNome
   */
  public function setNome($sNome) {
      $this->sNome = $sNome;
  }

  /**
   * Retorna a abreviatura do ponto de Parada
   * @return string
   */
  public function getAbreviatura() {
    return $this->sAbreviatura;
  }

  /**
   * Define uma abreviatura para o ponto de parada
   * @param string $sAbreviatura
   */
  public function setAbreviatura($sAbreviatura) {
    $this->sAbreviatura = $sAbreviatura;
  }

  /**
   * Retorna a Latitude do ponto de Refência
   * @return float
   */
  public function getLatitude() {
    return $this->nLatitude;
  }

  /**
   * Latitude do ponto de parada
   * @param float $nLatitude
   */
  public function setLatitude($nLatitude) {
    $this->nLatitude = $nLatitude;
  }

  /**
   * Retorna a Longitude do ponto de referencia
   * @return float
   */
  public function getLongitude() {
    return $this->nLongitude;
  }

  /**
   * Define a longitude do ponto de parada
   * @param float $nLongitude
   */
  public function setLongitude($nLongitude) {
    $this->nLongitude = $nLongitude;
  }

  /**
   * Retorna o ponto de Referencia
   * @return string
   */
  public function getPontoReferencia() {
    return $this->sPontoReferencia;
  }

  /**
   * Define o ponto de referencia da parada
   * @param integer $sPontoReferencia
   */
  public function setPontoReferencia($sPontoReferencia) {
    $this->sPontoReferencia = $sPontoReferencia;
  }

  /**
   * Retorna o departamento vinculado
   * @return DBDepartamento
   */
  public function getDepartamento() {

    if (empty($this->oDBDepartamento) && !empty($this->iCodigoDepartamento)) {
      $this->oDBDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo($this->iCodigoDepartamento);
    }
    return $this->oDBDepartamento;
  }

  /**
   * Define o departamento vinculado ao ponto de parada
   * @param DBDepartamento $oDBDepartamento
   */
  public function setDepartamento(DBDepartamento $oDBDepartamento = null) {


    $this->iCodigoDepartamento = null;
    $this->oDBDepartamento     = $oDBDepartamento;
    if (!empty($oDBDepartamento)) {
      $this->iCodigoDepartamento = $oDBDepartamento->getCodigo();
    }
  }

  /**
   *Retorna o tipo do ponto de parada
   * @return number
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * Define o tipo do ponto de Parada
   * @param integer $iTipo tipo do ponto de parada
   */
  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }

  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação com o banco de dados.');
    }

    if (trim($this->sNome) == '') {
      throw new BusinessException(_M('educacao.transporteescolar.PontoParada.nome_ponto_nao_informado'));
    }

    if (trim($this->sAbreviatura) == '') {
      throw new BusinessException(_M('educacao.transporteescolar.PontoParada.abreviatura_ponto_nao_informado'));
    }

    if (empty($this->iCodigoRuaBairro)) {
      throw new BusinessException(_M('educacao.transporteescolar.PontoParada.rua_bairro_ponto_nao_informado'));
    }

    $oDaoPontoParada                                  = new cl_pontoparada();
    $oDaoPontoParada->tre04_abreviatura               = $this->getAbreviatura();
    $oDaoPontoParada->tre04_cadenderbairrocadenderrua = $this->getCodigoRuaBairro();
    $oDaoPontoParada->tre04_latitude                  = $this->getLatitude();
    $oDaoPontoParada->tre04_longitude                 = $this->getLongitude();
    $oDaoPontoParada->tre04_nome                      = $this->getNome();
    $oDaoPontoParada->tre04_pontoreferencia           = $this->getPontoReferencia();
    $oDaoPontoParada->tre04_tipo                      = $this->getTipo();
    if (empty($this->iCodigo)) {

      $oDaoPontoParada->incluir(null);
      $this->iCodigo = $oDaoPontoParada->tre04_sequencial;
    } else {

      $oDaoPontoParada->tre04_sequencial = $this->getCodigo();
      $oDaoPontoParada->alterar($this->getCodigo());
    }
    if ($oDaoPontoParada->erro_status == 0) {

      $sMensagem            = 'educacao.transporteescolar.PontoParada.erro_persitir_dados_ponto';
      $oVariaveis           = new stdClass();
      $oVariaveis->erro_dao = $oDaoPontoParada->erro_msg;
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }

    $this->removerVinculoComDepartamento();
    if (!empty($this->oDBDepartamento)) {

      $oDaoPontoParadaDepartamento                    = new cl_pontoparadadepartamento();
      $oDaoPontoParadaDepartamento->tre05_db_depart   = $this->getDepartamento()->getCodigo();
      $oDaoPontoParadaDepartamento->tre05_pontoparada = $this->getCodigo();
      $oDaoPontoParadaDepartamento->incluir(null);
      if ($oDaoPontoParadaDepartamento->erro_status == 0) {

        $sMensagem            = 'educacao.transporteescolar.PontoParada.erro_persitir_dados_ponto_departamento';
        $oVariaveis           = new stdClass();
        $oVariaveis->erro_dao = $oDaoPontoParadaDepartamento->erro_msg;
        throw new BusinessException(_M($sMensagem, $oVariaveis));
      }
    }

    /**
     * Remove, se houver, os vínculos com escola de procedencia, e inclui novamente, se informado
     */
    $this->removerVinculoComEscolaProcedencia();
    if (!empty($this->oEscolaProcedencia)) {

      $oDaoEscolaProcedencia = new cl_pontoparadaescolaproc();
      $oDaoEscolaProcedencia->tre13_sequencial   = null;
      $oDaoEscolaProcedencia->tre13_pontoparada  = $this->getCodigo();
      $oDaoEscolaProcedencia->tre13_escolaproc   = $this->oEscolaProcedencia->getCodigo();

      $oDaoEscolaProcedencia->incluir(null);
      if ($oDaoEscolaProcedencia->erro_status == 0) {

        $sMensagem       = 'educacao.transporteescolar.PontoParada.erro_persitir_dados_escola_procedencia';
        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = $oDaoEscolaProcedencia->erro_msg;
        throw new BusinessException(_M($sMensagem, $oMsgErro));
      }

    }

  }

  /**
   * Remove o vinculo do departamento com o ponto de parada
   * @throws BusinessException
   */
  protected function removerVinculoComDepartamento() {

    $oDaoPontoParadaDepartamento = new cl_pontoparadadepartamento();
    $oDaoPontoParadaDepartamento->excluir(null, "tre05_pontoparada = {$this->getCodigo()}");
    if ($oDaoPontoParadaDepartamento->erro_status == 0) {

      $sMensagem            = 'educacao.transporteescolar.PontoParada.erro_persitir_dados_ponto_departamento';
      $oVariaveis           = new stdClass();
      $oVariaveis->erro_dao = $oDaoPontoParadaDepartamento->erro_msg;
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }
  }

  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação com o banco de dados.');
    }

    if (!empty($this->iCodigo)) {

      /**
       * Valida se o ponto de parada esta vinculado a uma linha de transporte
       */
      $oDaoLinhaTransportePontoParada   = new cl_linhatransportepontoparada();
      $sWhereLinhaTransportePontoParada = "tre11_pontoparada = {$this->getCodigo()}";
      $sSqlLinhaTransportePontoParada   = $oDaoLinhaTransportePontoParada->sql_query(
                                                                                      null,
                                                                                      "tre09_sequencial",
                                                                                      null,
                                                                                      $sWhereLinhaTransportePontoParada
                                                                                    );
      $rsLinhaTransportePontoParada = $oDaoLinhaTransportePontoParada->sql_record($sSqlLinhaTransportePontoParada);

      if ($oDaoLinhaTransportePontoParada->numrows > 0) {

        $iLinhaItinerario   = db_utils::fieldsMemory($rsLinhaTransportePontoParada, 0)->tre09_sequencial;
        $oLinhaItinerario   = new LinhaItinerario($iLinhaItinerario);
        $sMensagem          = 'educacao.transporteescolar.PontoParada.ponto_parada_vinculada_linha_transporte';
        $oVariaveis         = new stdClass();
        $oVariaveis->sLinha = $oLinhaItinerario->getLinhaTransporte()->getNome();
        throw new BusinessException(_M($sMensagem, $oVariaveis));
      }

      $this->removerVinculoComDepartamento();
      $this->removerVinculoComEscolaProcedencia();

      $oDaoPontoParada = new cl_pontoparada();
      $oDaoPontoParada->excluir($this->getCodigo());
      if ($oDaoPontoParada->erro_status == 0) {

        $sMensagem            = 'educacao.transporteescolar.PontoParada.erro_remover_dados_ponto';
        $oVariaveis           = new stdClass();
        $oVariaveis->erro_dao = $oDaoPontoParada->erro_msg;
        throw new BusinessException(_M($sMensagem, $oVariaveis));
      }
    }
  }


  /**
   * Setter Escola Procedencia
   * @param EscolaProcedencia
   */
  public function setEscolaProcedencia (EscolaProcedencia $oEscolaProcedencia) {
    $this->oEscolaProcedencia = $oEscolaProcedencia;
  }

  /**
   * Getter Escola Procedencia
   * @param EscolaProcedencia
   */
  public function getEscolaProcedencia () {
    return $this->oEscolaProcedencia;
  }

  /**
   * Remove o vínculo do ponto de parada com a escola de procedencia
   * @return boolean
   * @throws DBException
   */
  private function removerVinculoComEscolaProcedencia() {

    if ( empty($this->oEscolaProcedencia) ) {
      return true;
    }

    $oDaoEscolaProcedencia = new cl_pontoparadaescolaproc();
    $oDaoEscolaProcedencia->excluir(null, " tre13_pontoparada = {$this->iCodigo}");
    if ( $oDaoEscolaProcedencia->erro_status == 0) {

      $sMensagem       = 'educacao.transporteescolar.PontoParada.erro_remover_escola_procedencia';
      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = $oDaoPontoParada->erro_msg;
      throw new DBException(_M($sMensagem, $oMsgErro));
    }
    return true;
  }

}