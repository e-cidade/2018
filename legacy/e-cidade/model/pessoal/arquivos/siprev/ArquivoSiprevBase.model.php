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


abstract class ArquivoSiprevBase {


  protected $iUnidadeGestora;
  protected $iTipoAto;
  protected $iNumeroAto;
  protected $iAnoAto;
  protected $dDataAto;
  protected $cRepresentante;
  public static $aErrosProcessamento = array();

  /**
   * numero siafi da Entidade
   *
   * @var string
   */
  protected $sSiafi;
/**
   * numero CNPJ da Entidade
   *
   * @var string
   */
  protected $sCnpj;

  /**
   * nome do arquivo
   *
   * @var string
   */
  protected $sNomeArquivo;

  /**
   * formato de saida do arquivo (txt/XML/CSV)
   *
   * @var string
   */
  protected $sOutPut = "xml";

  /**
   * Coleção de objetos com os dados do arquivo
   *
   * @var array
   */
  protected $aDados;

  /**
   * ano inicial do arquivo
   *
   * @var string
   */
  protected $iAnoInicial;

  /**
   * mes inicial do arquivo
   *
   * @var integer
   */
  protected $iMesInicial;


  /**
   * ano inicial do arquivo
   *
   * @var string
   */
  protected $iAnoFinal;

  /**
   * mes inicial do arquivo
   *
   * @var integer
   */
  protected $iMesFinal;

  /**
   * data final do arquivo
   *
   * @var string
   */
  protected $sDataFinal;

  /**
   * Enter description here...
   *
   * @var unknown_type
   */
  protected  $rsLogger;

  protected $sRegistro;

  /**
   *
   * @see iPadArquivoBase::__construct()
   */
  function __construct() {

  }

  /**
   *
   * @see iPadArquivoBase::gerarDados()
   */
  public function gerarDados() {

  }


  /**
   * Retorna o nome do arquivo
   *
   * @return string
   */
  function getNomeArquivo() {

    return $this->sNomeArquivo;
  }

  /**
   * Define a competencia inicial
   *
   * @param integer $iAno ano inicial
   * @param integer $iMes mes inicial
   */
  public function setCompetenciaInicial($iAno, $iMes) {

    $this->iAnoInicial = $iAno;
    $this->iMesInicial = $iMes;
  }

/**
   * Define a competencia final
   *
   * @param integer $iAno ano final
   * @param integer $iMes mes final
   */
  public function setCompetenciaFinal($iAno, $iMes) {

    $this->iAnoFinal = $iAno;
    $this->iMesFinal = $iMes;
  }

  /**
   *retorna o Tipo de saida o arquivo
   */
  function getOutPut() {
    return  $this->sOutPut;
  }
  public function setTXTLogger($fp) {

    $this->rsLogger  = $fp;
  }

  public function addLog($sLog) {
    fputs($this->rsLogger, $sLog);
  }

  /**
   * Retorna o CNPJ da entidade
   *
   * @return string
   */
  function getCnpj() {

    $this->sCnpj = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"))->getCnpj();
    return $this->sCnpj;
  }

  /**
   * Retorna o SIAFI da entidade
   *
   * @return string
   */
  function getSiafi() {

    $sql    = " select cgc, q110_codigo                                                     ";
    $sql   .= "  from db_config                                                             ";
    $sql   .= "       inner join municipiosiafi ON db_config.cgc = municipiosiafi.q110_cnpj ";
    $sql   .= " where codigo =" . db_getsession("DB_instit");

    $result = db_query($sql);

    if (!$result) {
      throw new DBException("Erro ao retornar codigo do SIAFI. " . pg_last_error());
    }

    if (pg_num_rows($result) == 0) {
      throw new BusinessException("Código do SIAFI não cadastrado.");
    }

    $this->sSiafi = db_utils::fieldsMemory($result,0)->q110_codigo;
    return $this->sSiafi;
  }

  public function setUnidadeGestora( $iunidade ){
    $this->iUnidadeGestora = $iunidade;
  }

  public function setTipoAto( $itipoato ){
    $this->iTipoAto = $itipoato;
  }

  public function setNumeroAto( $inumeroato ){
    $this->iNumeroAto = $inumeroato;
  }

  public function setAnoAto( $ianoato ){
    $this->iAnoAto = $ianoato;
  }

  public function setDataAto( $idataato ){
    $this->dDataAto = $idataato;
  }

  public function setRepresentante( $crepresentante ){
    $this->cRepresentante = $crepresentante;
  }

  public static function makeTag($nome, $propriedades) {
    return array("nome" => $nome, "propriedades" => $propriedades);
  }

  public function getRegistro() {
    return $this->sRegistro;
  }

  /**
   * @return string
   */
  public function getAnoInicial() {
    return $this->iAnoInicial;
  }

  /**
   * @return string
   */
  public function getAnoFinal() {
    return $this->iAnoFinal;
  }

  /**
   * @return int
   */
  public function getMesInicial() {
    return $this->iMesInicial;
  }

  /**
   * @return int
   */
  public function getMesFinal() {
    return $this->iMesFinal;
  }
}