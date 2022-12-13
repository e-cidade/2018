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
 *
 * Classe para manutenção de Cota Unica
 *
 * @author Luca Dummer lucas.dummer@dbseller.com.br
 * @package Arrecadacao
 * @revision $Author: dbtales.baz $
 * @version $Revision: 1.7 $
 *
 */
class CotaUnica {

  /**
   * Caminho do arquivo json contendo as mensagens retornadas da classe
   */
  const MENSAGENS = "tributario.arrecadacao.CotaUnica.";

  private $iCodigo;

  private $iCgm;

  private $iMatricula;

  private $iInscricao;

  private $sDataOperacaoInicial;

  private $sDataOperacaoFinal;

  private $sDataVencimentoInicial;

  private $sDataVencimentoFinal;

  private $nPercentualDesconto;

  private $sObservacao;

  public function __construct($iCodigo = null){
    $this->iCodigo = $iCodigo;
  }

  /**
   * Consulta a cota única geral conforme codigo
   * @return array
   */
  public function getUnicaGeral(){

    if(empty($this->iCodigo)){
      throw new DBException(_M(self::MENSAGENS."erro_unica_geral_codigo"));
    }

    $oDaoReciboUnicaGeracao = new cl_recibounicageracao;
    $sSqlUnicaGeral         = $oDaoReciboUnicaGeracao->sql_query_unica_geral($this->iCodigo);
    $rsUnicaGeral           = $oDaoReciboUnicaGeracao->sql_record($sSqlUnicaGeral);

    if(!$rsUnicaGeral || pg_num_rows($rsUnicaGeral) == 0){
      throw new DBException(_M(self::MENSAGENS."erro_unica_geral"));
    }

    return db_utils::getCollectionByRecord($rsUnicaGeral, true, false, true);
  }

  /**
   * Consulta cota única parcial
   * @return array
   */
  public function getUnicaParcial(){

    $sInnerJoin = null;
    $aParametrosWhere = array();

    /**
     * Validamos a origem para definir o Inner
     * @todo  melhorar essa logica
     */
    if(!empty($this->iCgm)){

      $sInnerJoin         = " inner join arrenumcgm on recibounica.k00_numpre = arrenumcgm.k00_numpre ";
      $aParametrosWhere[] = " arrenumcgm.k00_numcgm = {$this->iCgm} ";
    }

    if(!empty($this->iMatricula)){

      $sInnerJoin         = " inner join arrematric on recibounica.k00_numpre = arrematric.k00_numpre ";
      $aParametrosWhere[] = " arrematric.k00_matric = {$this->iMatricula} ";
    }

    if(!empty($this->iInscricao)){

      $sInnerJoin         = " inner join arreinscr on recibounica.k00_numpre = arreinscr.k00_numpre ";
      $aParametrosWhere[] = " arreinscr.k00_inscr = {$this->iInscricao} ";
    }

    /**
     * Verificamos a Data de Operação caso informada
     */
    if(!empty($this->sDataOperacaoInicial) && !empty($this->sDataOperacaoFinal)){

      $oDataOperacaoInicial = new DBDate($this->sDataOperacaoInicial);
      $sDataOperacaoInicial = $oDataOperacaoInicial->convertTo('Y-m-d');

      $oDataOperacaoFinal   = new DBDate($this->sDataOperacaoFinal);
      $sDataOperacaoFinal   = $oDataOperacaoFinal->convertTo('Y-m-d');
      $aParametrosWhere[]   = " k00_dtoper between '{$sDataOperacaoInicial}'::date and '{$sDataOperacaoFinal}'::date ";
    }

    /**
     * Verificamos a Data de Vencimento caso informada
     */
    if(!empty($this->sDataVencimentoInicial) && !empty($this->sDataVencimentoFinal)){

      $oDataVencimentoInicial = new DBDate($this->sDataVencimentoInicial);
      $sDataVencimentoInicial = $oDataVencimentoInicial->convertTo('Y-m-d');

      $oDataVencimentoFinal   = new DBDate($this->sDataVencimentoFinal);
      $sDataVencimentoFinal   = $oDataVencimentoFinal->convertTo('Y-m-d');
      $aParametrosWhere[]     = " k00_dtvenc between '{$sDataVencimentoInicial}'::date and '{$sDataVencimentoFinal}'::date ";
    }

    if($this->nPercentualDesconto != ''){
      $aParametrosWhere[] = " k00_percdes = {$this->nPercentualDesconto} ";
    }

    if(!empty($this->sObservacao)){
      $aParametrosWhere[] = " ar40_observacao ilike '".addslashes(db_stdClass::normalizeStringJsonEscapeString($this->sObservacao))."%' ";
    }

    if(db_getsession("DB_administrador") == 0){
      $aParametrosWhere[] = " ar40_db_usuarios = ".db_getsession("DB_id_usuario")." ";
    }

    $oDataHoje = new DBDate(date("Y-m-d",db_getsession("DB_datausu")));
    $sDataHoje = $oDataHoje->convertTo('Y-m-d');

    $aParametrosWhere[] = " ar40_dtvencimento >= '{$sDataHoje}'::date ";

    $sSqlWhere = implode(" and ", $aParametrosWhere);
    if($sSqlWhere != "")
      $sSqlWhere = " and {$sSqlWhere}";

    $oDaoReciboUnica  = new cl_recibounica;
    $sSqlUnicaParcial = $oDaoReciboUnica->sql_query_unica_parcial($sSqlWhere, $sInnerJoin);
    $rsUnicaParcial   = db_query($sSqlUnicaParcial);

    if(!$rsUnicaParcial){
      throw new DBException(_M(self::MENSAGENS."erro_unica_parcial"));
    }

    return db_utils::getCollectionByRecord($rsUnicaParcial, true, false, true);
  }

  /**
   * Exclui cota única geral
   * @return boolean
   */
  public function excluirUnicaGeral(){

    if(empty($this->iCodigo)){
      throw new DBException(_M(self::MENSAGENS."erro_unica_geral_codigo"));
    }

    $iUsuarioCodigo = db_getsession("DB_id_usuario");
    $sUsuarioData   = date("Y-m-d",db_getsession("DB_datausu"));

    $oDaoReciboUnica = new cl_recibounica;
    $sSqlExcluirReciboUnica = $oDaoReciboUnica->excluir_unica_geral($this->iCodigo,
                                                                    $iUsuarioCodigo,
                                                                    $sUsuarioData);
    $rsExcluirReciboUnica = db_query($sSqlExcluirReciboUnica);

    if($rsExcluirReciboUnica == false){
      throw new DBException(_M(self::MENSAGENS."erro_excluir_unica_geral_recibo"));
    }

    // Apos exclusão de registros na recibounica aplica-se um count para ver se o recibounicageracao deve ser excluido
    $sSqlReciboUnica = $oDaoReciboUnica->sql_query(null, "count(ar40_sequencial) as total", null, "ar40_sequencial = {$this->iCodigo}");
    $rsReciboUnica   = $oDaoReciboUnica->sql_record($sSqlReciboUnica);

    $oReciboUnica    = db_utils::fieldsMemory($rsReciboUnica, 0);

    if($oReciboUnica->total == 0){

      $oDaoReciboUnicaGeracao = new cl_recibounicageracao;

      $sSqlWhere  = "     ar40_dtvencimento >= '{$sUsuarioData}' ";
      $sSqlWhere .= " and ar40_sequencial = {$this->iCodigo}     ";

      if(db_getsession("DB_administrador") == 0){
        $sSqlWhere .= " and ar40_db_usuarios = {$iUsuarioCodigo} ";
      }

      $rsReciboUnicaGeracao = $oDaoReciboUnicaGeracao->excluir(null, $sSqlWhere);

      if($oDaoReciboUnicaGeracao->erro_status == "0") {
        throw new Exception(_M(self::MENSAGENS."erro_excluir_unica_geral"));
      }
    }

    return true;
  }

  /**
   * Exclui cota única parcial
   * @param  array $aUnicaCodigo
   * @return boolean
   */
  public function excluirUnicaParcial($aUnicaCodigo = array()){

    $iUsuarioCodigo = db_getsession("DB_id_usuario");
    $aUnicaCodigo   = implode(", ", $aUnicaCodigo);

    $oDaoReciboUnica = new cl_recibounica;
    $sSqlWhere            = " k00_sequencial in ({$aUnicaCodigo}) ";
    $rsReciboUnicaParcial = $oDaoReciboUnica->excluir(null, $sSqlWhere);

    if($oDaoReciboUnica->erro_status == "0") {
      throw new Exception(_M(self::MENSAGENS."erro_excluir_unica_parcial"));
    }

    return true;
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @return integer
   */
  public function getCgm() {
    return $this->iCgm;
  }

  /**
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * @return integer
   */
  public function getInscricao() {
    return $this->iInscricao;
  }

  /**
   * @return string
   */
  public function getDataOperacaoInicial() {
    return $this->sDataOperacaoInicial;
  }

  /**
   * @return string
   */
  public function getDataOperacaoFinal() {
    return $this->sDataOperacaoFinal;
  }

  /**
   * @return string
   */
  public function getDataVencimentoInicial() {
    return $this->sDataVencimentoInicial;
  }

  /**
   * @return string
   */
  public function getDataVencimentoFinal() {
    return $this->sDataVencimentoFinal;
  }

  /**
   * @return numeric
   */
  public function getPercentualDesconto() {
    return $this->nPercentualDesconto;
  }

  /**
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * @param $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @param $iCgm
   */
  public function setCgm($iCgm) {
    $this->iCgm = $iCgm;
  }

  /**
   * @param $iMatricula
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * @param $iInscricao
   */
  public function setInscricao($iInscricao) {
    $this->iInscricao = $iInscricao;
  }

  /**
   * @param $sDataOperacaoInicial
   */
  public function setDataOperacaoInicial($sDataOperacaoInicial){
    $this->sDataOperacaoInicial = $sDataOperacaoInicial;
  }

  /**
   * @param $sDataOperacaoFinal
   */
  public function setDataOperacaoFinal($sDataOperacaoFinal){
    $this->sDataOperacaoFinal = $sDataOperacaoFinal;
  }

  /**
   * @param $sDataVencimentoInicial
   */
  public function setDataVencimentoInicial($sDataVencimentoInicial){
    $this->sDataVencimentoInicial = $sDataVencimentoInicial;
  }

  /**
   * @param $sDataVencimento
   */
  public function setDataVencimentoFinal($sDataVencimentoFinal){
    $this->sDataVencimentoFinal = $sDataVencimentoFinal;
  }

  /**
   * @param $nPercentualDesconto
   */
  public function setPercentualDesconto($nPercentualDesconto){
    $this->nPercentualDesconto = $nPercentualDesconto;
  }

  /**
   * @param $sObservacao
   */
  public function setObservacao($sObservacao){
    $this->sObservacao = $sObservacao;
  }
}