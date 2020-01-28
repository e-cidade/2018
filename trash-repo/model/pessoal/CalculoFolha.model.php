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

require_once('libs/db_app.utils.php');
require_once('model/pessoal/RubricaRepository.model.php');
require_once('model/pessoal/EventoFinanceiroFolha.model.php');
    
/**
 * Classse de Definicao do calculo da Folha
 * 
 * @abstract 
 * @package  Pessoal
 * @author   Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 */
abstract class CalculoFolha {

  const CALCULO_SALARIO         = "gerfsal";
  const CALCULO_ADIANTAMENTO    = "gerfadi";
  const CALCULO_FERIAS          = "gerffer";
  const CALCULO_COMPLEMENTAR    = "gerfcom";
  const CALCULO_13o             = "gerfs13";
  const CALCULO_RESCISAO        = "gerfres";
  const CALCULO_PONTO_FIXO      = "gerffx";
  const CALCULO_PROVISAO_FERIAS = "gerfprovfer";
  const CALCULO_PROVISAO_13o    = "gerfprovs13";

  /**
   * Tabela do calculo 
   * 
   * @var string
   * @access protected
   */
  protected $sTabela;

  /**
   * Sigla da tabela 
   * 
   * @var string
   * @access protected
   */
  protected $sSigla;

  /**
   * Servidor proprietário do calculo
   * 
   * @var Servidor
   * @access private
   */
  private $oServidor;

  /**
   * Construtor da Classe 
   * 
   * @param  Servidor $oServidor 
   * @access public
   * @return void
   */
  public function __construct ( Servidor $oServidor ) {
    $this->oServidor = $oServidor;
  }

  /**
   * Retorna instancia do Servidor 
   * @return Servidor
   */
  public function getServidor () { 
    return $this->oServidor;
  }
  
  /**
   * Retorna Ano da competencia
   */
  public function getAnoCompetencia () {
  	return $this->oServidor->getAnoCompetencia();
  }
  
  /**
   * Retorna Mes da competencia
   */
  public function getMesCompetencia () {
  	return $this->oServidor->getMesCompetencia();
  }

  /**
   * Função para gerar calculo para o mes selecionado
   */
  abstract public function gerar ();

  /**
   * @deprecated
   * @see calculoFolha::getEventosFinanceiros()
   * 
   * @param mixed $iSemestre - Utilizado para Complementar
   * @param mixed $sRubrica 
   * @access public
   * @return void
   */
  public function getMovimentacoes( $iSemestre = null, $sRubrica = null) {
     
    $oDaoGeracaoFolha = db_utils::getDao($this->sTabela);
    $sWhere           = "    {$this->sSigla}_regist = {$this->oServidor->getMatricula()}                    ";
    $sWhere          .= "and {$this->sSigla}_anousu = {$this->oServidor->getAnoCompetencia()}               ";
    $sWhere          .= "and {$this->sSigla}_mesusu = {$this->oServidor->getMesCompetencia()}               ";
    $sWhere          .= "and {$this->sSigla}_instit = {$this->oServidor->getInstituicao()->getSequencial()} ";

    if (!empty($iSemestre)) {
      $sWhere        .= "and {$this->sSigla}_semest = {$iSemestre} ";
    }                                                                           
                                                                                
    if (!empty($sRubrica)) {                                                     
      $sWhere .= "and {$this->sSigla}_rubric = '{$sRubrica}' ";
    }
    
    $sSql  = $oDaoGeracaoFolha->sql_query_file( null, 
                                                null, 
                                                null, 
                                                null, 
                                                " {$this->sSigla}_rubric as codigo_rubrica, 
                                                  {$this->sSigla}_valor  as valor_rubrica, 
                                                  {$this->sSigla}_pd     as provento_desconto, 
                                                  {$this->sSigla}_quant  as quantidade_rubrica ", 
                                                null, 
                                                $sWhere);

    if ( $this->sTabela == 'gerfres' ) {

      $sSql  = $oDaoGeracaoFolha->sql_query_file( null, 
                                                  null, 
                                                  null, 
                                                  null, 
                                                  null, 
                                                  " {$this->sSigla}_rubric as codigo_rubrica, 
                                                    {$this->sSigla}_valor  as valor_rubrica, 
                                                    {$this->sSigla}_pd     as provento_desconto, 
                                                    {$this->sSigla}_quant  as quantidade_rubrica ", 
                                                  null, 
                                                  $sWhere);

    }

    $rsMovimentacoes = db_query($sSql);

    if ( !$rsMovimentacoes ) {
      throw new DBException("Erro ao Buscar dados das Movimentacoes." . pg_last_error() );
    }

    $aMovimentacoes  =  array();

    foreach ( db_utils::getCollectionbyRecord($rsMovimentacoes) as  $oMovimentacao ) {
  
      $oRetorno = new stdClass();
      $oRetorno->oRubrica          = new Rubrica($oMovimentacao->codigo_rubrica); 
      $oRetorno->nQuantidade       = $oMovimentacao->quantidade_rubrica;
      $oRetorno->nValor            = $oMovimentacao->valor_rubrica;
      $oRetorno->iProventoDesconto = $oMovimentacao->provento_desconto;
      $aMovimentacoes[]            = $oRetorno;
    }

    return $aMovimentacoes;
  }

  /**
   * Retorna Array com os eventos financeiros do servidor
   * 
   * @param integer $iSemestre 
   * @param mixed   $mRubrica 
   * @access public
   * @return Array
   */
  public function getEventosFinanceiros( $iSemestre = null, $mRubrica = null) {
     

    $oDaoGeracaoFolha = db_utils::getDao($this->sTabela);
    $sWhere           = "     {$this->sSigla}_regist = {$this->oServidor->getMatricula()}                    ";
    $sWhere          .= " and {$this->sSigla}_anousu = {$this->oServidor->getAnoCompetencia()}               ";
    $sWhere          .= " and {$this->sSigla}_mesusu = {$this->oServidor->getMesCompetencia()}               ";
    $sWhere          .= " and {$this->sSigla}_instit = {$this->oServidor->getInstituicao()->getSequencial()} ";

    if ( $iSemestre != "" ) {
      $sWhere .= " and {$this->sSigla}_semest = {$iSemestre} ";
    }                                                                           

    if ( !empty($mRubrica) ) {   

      $sWhere .= " and {$this->sSigla}_rubric "; 
      
      if ( is_array($mRubrica) ) {

        $aRubricas = array();

        foreach ( $mRubrica as $sRubrica ) {
          $aRubricas[] = "'$sRubrica'";
        }

        $sWhere .= " in (" . implode(", ", $aRubricas) . ")";
      } else {
        $sWhere .= " = '{$mRubrica}' ";
      }                                                
    }

    switch ( $this->sTabela ) {

      default :

        $sSql  = $oDaoGeracaoFolha->sql_query_file( null, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    " {$this->sSigla}_rubric as codigo_rubrica, 
                                                      {$this->sSigla}_valor  as valor_rubrica, 
                                                      {$this->sSigla}_pd     as provento_desconto, 
                                                      {$this->sSigla}_quant  as quantidade_rubrica ", 
                                                    null, 
                                                    $sWhere);
      break;

      case CalculoFolha::CALCULO_RESCISAO :
      case CalculoFolha::CALCULO_PROVISAO_FERIAS :
      case CalculoFolha::CALCULO_FERIAS :

        $sSql  = $oDaoGeracaoFolha->sql_query_file( null, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    " {$this->sSigla}_rubric as codigo_rubrica, 
                                                      {$this->sSigla}_valor  as valor_rubrica, 
                                                      {$this->sSigla}_pd     as provento_desconto, 
                                                      {$this->sSigla}_quant  as quantidade_rubrica ", 
                                                    null, 
                                                    $sWhere);


      break;
    }     
    
    $rsMovimentacoes = db_query($sSql);

    if ( !$rsMovimentacoes ) {
      throw new DBException("Erro ao Buscar dados das Movimentacoes." . pg_last_error() );
    }

    $aMovimentacoes  =  array();

    for( $iEvento = 0; $iEvento < pg_num_rows($rsMovimentacoes); $iEvento++ ) {
      
      $oMovimentacao = db_utils::fieldsMemory($rsMovimentacoes, $iEvento);
      $oEvento       = new EventoFinanceiroFolha();
      $oRubrica      = RubricaRepository::getInstanciaByCodigo($oMovimentacao->codigo_rubrica);

      $oEvento->setServidor($this->oServidor); 
      $oEvento->setRubrica($oRubrica); 
      $oEvento->setQuantidade($oMovimentacao->quantidade_rubrica);
      $oEvento->setValor($oMovimentacao->valor_rubrica);
      $oEvento->setNatureza($oMovimentacao->provento_desconto);

      $aMovimentacoes[] = $oEvento;
    }

    return $aMovimentacoes;
  }

  /**
   * Função para retornar as rubricas utilizadas no calculo
   * 
   * @access public
   * @return void
   */
  public function getRubricas() {

     
    $oDaoRhrubricas = db_utils::getDao('rhrubricas');
    $sSql           = $oDaoRhrubricas->sql_queryRubricas( $this->oServidor->getMatricula(),
                                                          $this->sTabela,
                                                          $this->sSigla,
                                                          $this->oServidor->getMesCompetencia(),
                                                          $this->oServidor->getAnoCompetencia() );
    $rsRubricas = db_query($sSql);

    if ( !$rsRubricas ) {
      throw new Exception("Erro ao buscar rubricas da competencia: {$this->oServidor->getMesCompetencia()} / {$this->oServidor->getAnoCompetencia()}");
    }

    $aRubricas = array();

    foreach(db_utils::getCollectionByRecord($rsRubricas) as $oRubrica) {
      $aRubricas[] = RubricaRepository::getInstanciaByCodigo($oRubrica->codigo_rubrica);
    }

    return $aRubricas;
  }

  /**
   * Limpar tabela do calculo
   *
   * @param string $sRubrica
   * @access protected
   * @return bool
   */
  public function limpar($sRubrica = null) {
  
    $iAnoCompetencia = $this->getServidor()->getAnoCompetencia();
    $iMesCompetencia = $this->getServidor()->getMesCompetencia();
    $iMatricula      = $this->getServidor()->getMatricula();
  
    $oDaoCalculo = db_utils::getDao($this->sTabela);
    $oDaoCalculo->excluir($iAnoCompetencia, $iMesCompetencia, $iMatricula, $sRubrica);
  
    /**
     * Erro ao excluir registro
     */
    if ( $oDaoCalculo->erro_status == "0" ) {
      throw new Exception($oDaoCalculo->erro_msg);
    }
  
    return true;
  }

}