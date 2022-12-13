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

require_once(modification('libs/JSON.php'));
require_once(modification('model/dataManager.php'));
/**
 * Classe responsavel pela importacao para diversos
 *
 * @author   André Ianzer Hertzog <andre.hertzog@dbseller.com.br>
 * @author   Everton Catto        <everton.heckler@dbseller.com.br>
 * @package  Diversos
 * @revision $Author: dbdavi $
 * @version $Revision: 1.27 $
 */
class ImportacaoDiversos {

  const PROCESSAMENTO_INDIVIDUAL = 1;
  const PROCESSAMENTO_GERAL      = 2; 
  const MENSAGENS                = 'tributario.diversos.ImportacaoDiversos.';
  
  const CANCELAMENTO_COMPLETO    = 1;
  const CANCELAMENTO_PARCIAL     = 2;

  /**
   * @type integer
   */
  const MENOR_DATA_VENCIMENTO = 1;

  /**
   * @type integer
   */
  const MAIOR_DATA_VENCIMENTO = 2;

  /**
   * Quantidade de Parcelas que a Importação Irá Gerar por Receita de Cada Parcela de Cada Débito. 
   * 
   * @var    integer
   * @access private
   */
  protected $iQuantidadeParcelas = 1;
  
  /**
   * 
   * @var tableDataManager[]
   */
  protected $aDataManager        = array();

  /**
   * iCodigoImportacao
   * 
   * @var integer
   * @access protected
   */
  protected $iCodigoImportacao;
  
  /**
   * Tabela procdiver
   * @var integer
   */
  protected $iProcedenciaDiverso;

  /**
   * Tabela arrecad
   * @var array
   */
  protected $aBaseDebitos    = array();

  /**
   * aDividasArrecad
   * 
   * @var array
   * @access protected
   */
  protected $aDividasArrecad = array();
  
  /**
   * aReceitaProcedencia
   * 
   * @var array
   * @access protected
   */
  private $aReceitaProcedencia = array();
  
  /**
   * Observações sobre a Importacao
   * @var string
   * @access protected
   */
  protected $sObservacoes = "";     

  /**
   * iMatricula
   * 
   * @var mixed
   * @access protected
   */
  protected $iMatricula = null;
  
  /**
   * Tipo de origem
   * 1 - CODIGO IMPORTACAO
   * 2 - MATRICULA
   * 3 - CGM
   * 4 - Debitos (vindos da CGF)
   * 5 - Inscrição
   */
  protected $iTipoOrigem;
  
  protected $iCodigoOrigem;
  
  /**
   * ImportacaoDiversos constructor.
   * @param null $iCodigoImportacao
   * @throws ParameterException
   */
  public function __construct( $iCodigoImportacao = null ) {

    global $conn;

    if ( !empty($iCodigoImportacao) ) {

      if ( !DBNumber::isInteger($iCodigoImportacao) ) {
        throw new ParameterException(_M( ImportacaoDiversos::MENSAGENS . 'codigo_importacao_deve_ser_inteiro' ) );
      }

      $this->iCodigoImportacao = $iCodigoImportacao;
    }
   
    $this->aDataManager['arrecad']    = new tableDataManager( $conn, 'arrecad',    null, false, 1000 );
    $this->aDataManager['arrematric'] = new tableDataManager( $conn, 'arrematric', null, false, 1000 );
    $this->aDataManager['arreinscr']  = new tableDataManager( $conn, 'arreinscr',  null, false, 1000 );
  }

  /**
   * Salvar registro de criacao para importacao
   * @param $iTipo  integer - Tipo de importacao - 1 Parcial / 2 Geral
   * @return int
   * @throws BusinessException
   * @throws DBException
   */
  protected function salvarDiverImporta($iTipo) {
    
    if (empty($iTipo)) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'tipo_nao_informado'));
    }
    
    $oDaoDiverImporta                  = new cl_diverimporta();
    
    $oDaoDiverImporta->dv11_instit     = db_getsession('DB_instit');
    $oDaoDiverImporta->dv11_id_usuario = db_getsession('DB_id_usuario');
    $oDaoDiverImporta->dv11_data       = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoDiverImporta->dv11_hora       = date('H:i');
    $oDaoDiverImporta->dv11_tipo       = $iTipo;
    $oDaoDiverImporta->dv11_obs        = pg_escape_string($this->sObservacoes);
    
    $oDaoDiverImporta->incluir(null);
    
    if ($oDaoDiverImporta->erro_status == '0') {
      throw new DBException($oDaoDiverImporta->erro_msg);
    }
    
    return $oDaoDiverImporta->dv11_sequencial;
  }
  
  /**
   * Salvar registro da diversos
   * @param integer $iNumpre
   * @param integer $iNumpar
   * @param integer $iReceita
   * @param integer $iProcedencia
   * @param integer $iCodDiverImporta
   * @return stdClass
   * @throws BusinessException
   * @throws DBException
   */
  protected function salvarDiversos($iNumpre, $iNumpar, $iReceita, $iProcedencia, $iCodDiverImporta) {
    
    if (empty($iNumpre)) {
      throw new BusinessException(_M(ImportacaoDiversos::MENSAGENS . 'numpre_nao_informado'));
    }
    
    if (empty($iNumpar)) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'numpar_nao_informado'));
    }
    
    if (empty($iReceita)) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'receita_nao_informada'));
    }
    
    if (empty($iProcedencia)) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'procedencia_nao_informada'));
    }
    
    $oDaoNumpref         = new cl_numpref();
    $oDaoDiversos        = new cl_diversos();
    $oDaoDiverImporta    = new cl_diverimporta();
    $oDaoDiverImportaReg = new cl_diverimportareg();
    
    $sSqlDiverImporta = $oDaoDiverImporta->sql_query_debitos_diversos($iNumpre, $iNumpar, $iReceita);
    
    $rsDiverImporta   = db_query($sSqlDiverImporta);
    
    if ( !$rsDiverImporta ) {
      
      $oParms        = new stdClass();
      $oParms->sErro = pg_last_error();
      throw new DBException(_M( ImportacaoDiversos::MENSAGENS . 'erro_buscar_importacao', $oParms));
    }
    
    if ( pg_num_rows($rsDiverImporta) == 0 ) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'sem_dados_importacao'));
    }
    
    $oDiverImporta = db_utils::fieldsMemory($rsDiverImporta, 0);

    $iNumpreAdd    = $oDaoNumpref->sql_numpre();

    $oDaoDiversos->dv05_numcgm    = $oDiverImporta->k00_numcgm;
    $oDaoDiversos->dv05_dtinsc    = $oDiverImporta->k00_dtoper;
    $oDaoDiversos->dv05_vlrhis    = $oDiverImporta->k00_valor;
    $oDaoDiversos->dv05_valor     = $oDiverImporta->k00_valor;
    $oDaoDiversos->dv05_procdiver = $iProcedencia;
    $oDaoDiversos->dv05_exerc     = substr($oDiverImporta->k00_dtoper, 0, 4);
    $oDaoDiversos->dv05_numpre    = $iNumpreAdd;
    $oDaoDiversos->dv05_numtot    = $oDiverImporta->k00_numtot;
    $oDaoDiversos->dv05_privenc   = $oDiverImporta->k00_dtvenc;
    $oDaoDiversos->dv05_provenc   = $oDiverImporta->k00_dtvenc;
    $oDaoDiversos->dv05_diaprox   = substr($oDiverImporta->k00_dtvenc,8,2);
    $oDaoDiversos->dv05_oper      = $oDiverImporta->k00_dtoper;
    $oDaoDiversos->dv05_obs       = pg_escape_string($this->sObservacoes);
    $oDaoDiversos->dv05_instit    = db_getsession('DB_instit');
    $oDaoDiversos->incluir(null);
    
    if ($oDaoDiversos->erro_status == '0') {
      throw new DBException($oDaoDiversos->erro_msg);
    }

    $oDaoDiverImportaReg->dv12_diversos     = $oDaoDiversos->dv05_coddiver;
    $oDaoDiverImportaReg->dv12_diverimporta = $iCodDiverImporta;
    $oDaoDiverImportaReg->incluir(null);
    
    if ($oDaoDiverImportaReg->erro_status == '0') {
      throw new DBException($oDaoDiverImportaReg->erro_msg);
    }

    $oDiverso                = new stdClass();
    $oDiverso->iCodDiverso   = $oDaoDiversos->dv05_coddiver;
    $oDiverso->iNumpreGerado = $iNumpreAdd;
    
    return $oDiverso;
  }

  /**
   * Adiciona um debito para ser processado
   * @param integer $iNumpre
   * @param integer $iNumpar
   * @param integer $iReceita
   * @param integer $iProcedencia
   * @return bool
   * @throws BusinessException
   * @throws DBException
   */
  public function adicionarDebito($iNumpre, $iNumpar, $iReceita, $iProcedencia) {
    
    if (empty($iNumpre)) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'informe_numpre'));
    }                                                 
                                                      
    if (empty($iNumpar)) {                            
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'informe_numpar'));
    }
    
    if (empty($iReceita)) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'informe_receita'));
    }
    
    $oDaoArrecad       = new cl_arrecad();
    $sSqlDebitoArrecad = $oDaoArrecad->sql_query_file(null,
                                                      "arrecad.*",
                                                      "k00_numpre, k00_numpar, k00_receit",
                                                      "    k00_numpre = {$iNumpre}
                                                       and k00_numpar = {$iNumpar}
                                                       and k00_receit = {$iReceita}");
    
    $rsDebitoArrecad   = $oDaoArrecad->sql_record($sSqlDebitoArrecad);
    
    if ($oDaoArrecad->erro_status == '0') {
      throw new DBException($oDaoArrecad->erro_msg);
    }
    $this->aReceitaProcedencia[$iReceita] = $iProcedencia;
    $this->aDividasArrecad                = array_merge($this->aDividasArrecad, db_utils::getCollectionByRecord($rsDebitoArrecad));
    $this->aBaseDebitos[$iNumpre]
                       [$iNumpar]
                       [$iReceita][]      = db_utils::getCollectionByRecord($rsDebitoArrecad);

    return true;
  }
  
  /** 
   * Adiciona registro é - DiverImportaOld
   * @param $iCodDiverso integer - Código diverso
   * @param $iNumpre     integer - Numpre do debito
   * @param $iNumpar     integer - Numpar do debito
   * @param $iReceita    integer - Receita do debito
   */
  protected function adicionarDiverImportaOld($iCodDiverso, $iNumpre, $iNumpar, $iReceita) {
  
    if (empty($iCodDiverso)) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'cod_diversao_nao_informado'));
    }
    
    if (empty($iNumpre)) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'numpre_nao_informado_3'));
    }
    
    if (empty($iNumpar)) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'numpar_nao_informado_3'));
    }
    
    if (empty($iReceita)) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'receita_nao_informada_3'));
    }
    
    $oDaoDiverImportaOld = new cl_diverimportaold();
    
    $oDaoDiverImportaOld->dv13_diversos = $iCodDiverso;
    $oDaoDiverImportaOld->dv13_numpre   = $iNumpre;
    $oDaoDiverImportaOld->dv13_numpar   = $iNumpar;
    $oDaoDiverImportaOld->dv13_receita  = $iReceita;
    $oDaoDiverImportaOld->incluir(null);

    if ($oDaoDiverImportaOld->erro_status == '0') {
      throw new DBException($oDaoDiverImportaOld->erro_msg);
    }
    
    return true;
  }

  /**
   * processa debitos na arrecad
   * @param $aDebito
   * @param ProcedenciaDiversos $oProcedenciaDiversos
   * @param null $iMatricula
   * @param $iNumpreGerado
   * @param DBDate|null $oVencimento
   * @return bool
   * @throws DBException
   */
  protected function processaArrecad($aDebito, ProcedenciaDiversos $oProcedenciaDiversos, $iMatricula = null, $iNumpreGerado, DBDate $oVencimento = null ) {

    $oDaoArrecad      = new cl_arrecad();
    $oDadosDebito     = $aDebito[0][0];
    $sWhere           = " arrecad.k00_numpre     = {$oDadosDebito->k00_numpre}";
    $sWhere          .= " and arrecad.k00_numpar = {$oDadosDebito->k00_numpar}";
    $sWhere          .= " and arrecad.k00_receit = {$oDadosDebito->k00_receit}";
    $sSqlDadosDebito  = $oDaoArrecad->sql_query_file( null, '*', null, $sWhere);
    $rsDebitos        = $oDaoArrecad->sql_record($sSqlDadosDebito);

    if ($oDaoArrecad->erro_status == '0') {
      throw new DBException($oDaoArrecad->erro_msg);
    }
    
    $aDebitos        = db_utils::getCollectionByRecord($rsDebitos);

    $oDaoArrecad->excluir_arrecad($oDadosDebito->k00_numpre,
                                  $oDadosDebito->k00_numpar,
                                  true,
                                  $oDadosDebito->k00_receit);


    if ($oDaoArrecad->erro_status == '0') {
      throw new DBException($oDaoArrecad->erro_msg);
    }

    $iTipoDebitoProcedenciaDiverso = $oProcedenciaDiversos->getTipoDebito();
    $iReceitaProcedenciaDiverso    = $oProcedenciaDiversos->getReceita();
    $iHistoricoProcedenciaDiverso  = $oProcedenciaDiversos->getHistoricoCalculo();

    for( $iParcelaDiversos = 1; $iParcelaDiversos <= $this->iQuantidadeParcelas; $iParcelaDiversos++ ){

      $iAdicionalMeses  = $iParcelaDiversos - 1;

      foreach ( $aDebitos as $iTeste => $aDebito ) {
        
        /**
         * Caso o numero de parcelas seja "1", vai manter o mesmo numpar original
         */
        $iParcela                   = $this->iQuantidadeParcelas == 1 ? $aDebito->k00_numpar : $iParcelaDiversos;
        
        /**
         * Se o vencimento não for informado, manterá o vencimento inicial do numpre
         */
        $oDataVencimento            = empty($oVencimento) ? new DBDate($aDebito->k00_dtvenc) : clone $oVencimento;
        
        /**
         * Modifica o Vencimento
         */
        $oDataVencimento->modificarIntervalo("+{$iAdicionalMeses} months");

        /**
         * Divide o Valor do Débito pelas Parcelas
         */
        $nValor = $aDebito->k00_valor;
        
        if ( $this->iQuantidadeParcelas > 1 ){

          $nValor = round( $aDebito->k00_valor / $this->iQuantidadeParcelas, 2 );

          /**
           * Se for a ultima parcela tenta calcular resto
           */
          if( $iParcelaDiversos == $this->iQuantidadeParcelas ){

            $nValorOriginal     = $aDebito->k00_valor;
            $nValorMultiplicado = $nValor * $this->iQuantidadeParcelas;

            if( $nValorOriginal > $nValorMultiplicado ) {
              $nValor += round( $nValorOriginal - $nValorMultiplicado, 2 );
            }elseif( $nValorOriginal < $nValorMultiplicado ){
              $nValor += round( $nValorMultiplicado - $nValorOriginal, 2 );
            }
          }
        } 

        /**
         * Insere no Arrecad
         */
        $oDadosArrecad                = new stdClass();
        $oDadosArrecad->k00_numpre    = $iNumpreGerado;
        $oDadosArrecad->k00_numpar    = $iParcela;
        $oDadosArrecad->k00_numcgm    = $aDebito->k00_numcgm;
        $oDadosArrecad->k00_dtoper    = date('Y-m-d', db_getsession('DB_datausu'));
        $oDadosArrecad->k00_receit    = $iReceitaProcedenciaDiverso;
        $oDadosArrecad->k00_hist      = $iHistoricoProcedenciaDiverso;
        $oDadosArrecad->k00_valor     = $nValor;
        $oDadosArrecad->k00_dtvenc    = $oDataVencimento->getDate(DBDate::DATA_EN); 
        $oDadosArrecad->k00_numtot    = $aDebito->k00_numtot;
        $oDadosArrecad->k00_numdig    = $aDebito->k00_numdig;
        $oDadosArrecad->k00_tipo      = $iTipoDebitoProcedenciaDiverso;
        $oDadosArrecad->k00_tipojm    = '0';
        $this->aDataManager['arrecad']->setByLineOfDBUtils( $oDadosArrecad, true );
      }
    }

    /**
     * Valida se Vinculação é com matricula
     */
    if ($this->iTipoOrigem == 2) {

      $oDadosArrematric             = new stdClass();
      $oDadosArrematric->k00_numpre = $iNumpreGerado;
      $oDadosArrematric->k00_matric = $this->iCodigoOrigem;
      $oDadosArrematric->k00_perc   = 100;
      $this->aDataManager['arrematric']->setByLineOfDBUtils( $oDadosArrematric, true );
      
     /**
      * Ou Inscrição
      */
    } elseif ($this->iTipoOrigem == 5) {
      
      $oDadosArreinscr              = new stdClass();
      $oDadosArreinscr->k00_numpre  = $iNumpreGerado;
      $oDadosArreinscr->k00_inscr   = $this->iCodigoOrigem;
      $oDadosArreinscr->k00_perc    = 100;
      $this->aDataManager['arreinscr']->setByLineOfDBUtils( $oDadosArreinscr, true );
      
    }
    /**
     * Se for apenas CGM, a trigger do arrecad vincula o arrenumcgm
     */
    return true;
  }
  
  /** 
   * importa registros para diversos
   * @param 
   */
  public function importarDiversos($iProcedencia, $iNumpre, $iNumpar, $iReceita) {
    
    if (!db_utils::inTransaction()) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'sem_transacao_ativa'));
    }
    
    $this->adicionarDebito($iNumpre, $iNumpar, $iReceita, $iProcedencia);
  }
  
  
  /**
   * 
   * @param unknown_type $sObservacao
   * @throws BusinessException
   */
  public function processar ( $sObservacao ) {
    
    require_once(modification("std/db_stdClass.php"));
    
    $this->sObservacoes = db_stdClass::normalizeStringJson($sObservacao);
    $iCodDiverImporta = $this->salvarDiverImporta(ImportacaoDiversos::PROCESSAMENTO_INDIVIDUAL);
     
    if (!db_utils::inTransaction()) {
      throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'sem_transacao_ativa_2'));
    }
    
    foreach ( $this->aBaseDebitos as $iNumpre => $aNumpre ) {
      
      foreach ( $aNumpre as $iNumpar => $aParcelas ) {
        
        foreach ( $aParcelas as $iReceita => $aDados ) {
           
           $oDiverso = $this->salvarDiversos($iNumpre, //$oDebito->k00_numpre, 
                                             $iNumpar, //$oDebito->k00_numpar,
                                             $iReceita,//$oDebito->k00_receit, 
                                             $this->aReceitaProcedencia[$iReceita],//Procedencia
                                             $iCodDiverImporta);
         
           $this->adicionarDiverImportaOld($oDiverso->iCodDiverso,
                                           $iNumpre,  //$oDebito->k00_numpre,   
                                           $iNumpar,  //$oDebito->k00_numpar,    
                                           $iReceita);//$oDebito->k00_receit,  
             
           $this->processaArrecad($aDados,
                                  new ProcedenciaDiversos($this->aReceitaProcedencia[$iReceita]),
                                  $this->iTipoOrigem, 
                                  $oDiverso->iNumpreGerado);
        }
      }
    }

    $this->aDataManager['arrecad']->persist();
    $this->aDataManager['arrematric']->persist();
    $this->aDataManager['arreinscr']->persist();
  }
  

  /**
   * Cancela importacao
   * @param $aDebitos
   */
  public function cancelar () {

    $lProcessamentoParcial = false;
    
    $oDaoArrecad          = new cl_arrecad();
    $oDaoArreold          = new cl_arreold();
    $oDaoDiverImporta     = new cl_diverimporta();
    $oDaoDiverImportaOld  = new cl_diverimportaold();
    $oDaoDiverImportaReg  = new cl_diverimportareg();
    $oDaoDiversos         = new cl_diversos();
    
    $sSqlValidaImportacao = $oDaoDiverImporta->sql_query_dadosImportacao( $this->iCodigoImportacao ); 
    $rsValidaImportacao   = $oDaoDiverImporta->sql_record($sSqlValidaImportacao);

    if ($oDaoDiverImporta->erro_status == '0') {
      
      $oParms           = new stdClass();
      $oParms->sErroMsg = $oDaoDiverImporta->erro_msg;
      throw new DBException(_M( ImportacaoDiversos::MENSAGENS . 'nenhum_registro_tabela_diverimporta', $oParms));
    }

    $aDiverImporta        = db_utils::getCollectionByRecord($rsValidaImportacao);
    $aNumpresCancelamento = array();
    
    foreach ( $aDiverImporta as $oNumpreCancelamento ) {
      $aNumpresCancelamento[] = $oNumpreCancelamento->dv13_numpre;
    }
    $sListaDiversos      = implode( ", ", $aNumpresCancelamento );
    $sSqlDiverImportaOld = $oDaoDiverImportaOld->sql_query(null,
                                                           "dv05_numpre,
                                                           dv13_numpre,
                                                           dv13_numpar,
                                                           dv13_receita",
                                                           null,
                                                           "dv13_numpre in ({$sListaDiversos}) ");

    $rsDiverImportaOld   = $oDaoDiverImportaOld->sql_record($sSqlDiverImportaOld);

    if ($oDaoDiverImportaOld->numrows == '0') {
        
      $oParms           = new stdClass();
      $oParms->sErroMsg = $oDaoDiverImportaOld->erro_msg;
      throw new Exception (_M( ImportacaoDiversos::MENSAGENS . 'nenhum_registro_tabela_diverimportaold', $oParms));
    }

    $aDiverImportaOld = db_utils::getCollectionByRecord($rsDiverImportaOld);
    
    foreach ($aDiverImportaOld as $oDiverImportaOld) {

      $sCondicaoArreold = "     k00_numpre = {$oDiverImportaOld->dv13_numpre}";
      $sCondicaoArreold.= " and k00_numpar = {$oDiverImportaOld->dv13_numpar}";
      $sCondicaoArreold.= " and k00_receit = {$oDiverImportaOld->dv13_receita}";
      $sSqlArreold      = $oDaoArreold->sql_query_file(null,"*", null, $sCondicaoArreold);   

      $rsArreold        = $oDaoArreold->sql_record($sSqlArreold);
      $aArreold         = db_utils::getCollectionByRecord($rsArreold);

      foreach ($aArreold as $oArreold) {

        $oDaoArrecad->k00_numpre = $oArreold->k00_numpre;
        $oDaoArrecad->k00_numpar = $oArreold->k00_numpar;
        $oDaoArrecad->k00_numcgm = $oArreold->k00_numcgm;
        $oDaoArrecad->k00_dtoper = $oArreold->k00_dtoper;
        $oDaoArrecad->k00_receit = $oArreold->k00_receit;
        $oDaoArrecad->k00_hist   = $oArreold->k00_hist  ;
        $oDaoArrecad->k00_valor  = $oArreold->k00_valor ;
        $oDaoArrecad->k00_dtvenc = $oArreold->k00_dtvenc;
        $oDaoArrecad->k00_numtot = $oArreold->k00_numtot;
        $oDaoArrecad->k00_numdig = $oArreold->k00_numdig;
        $oDaoArrecad->k00_tipo   = $oArreold->k00_tipo  ;
        $oDaoArrecad->k00_tipojm = $oArreold->k00_tipojm;
        $oDaoArrecad->incluir(null);

        if ($oDaoArrecad->erro_status == '0') {
          
          $oParms           = new stdClass();
          $oParms->sErroMsg = $oDaoArrecad->erro_msg;
          throw new Exception (_M( ImportacaoDiversos::MENSAGENS . 'erro_incluir_tabela_arrecad', $oParms));
        }

        $oDaoArrecad->excluir(null, "k00_numpre = {$oDiverImportaOld->dv05_numpre}");

        if ($oDaoArrecad->erro_status == '0') {
          
          $oParms           = new stdClass();
          $oParms->sErroMsg = $oDaoArrecad->erro_msg;
          throw new Exception (_M( ImportacaoDiversos::MENSAGENS . 'erro_excluir_tabela_arrecad', $oParms));
        }
      
        $sCondicaoExclusao = "    k00_numpre = {$oDiverImportaOld->dv13_numpre} ";
        $sCondicaoExclusao.= "and k00_numpar = {$oDiverImportaOld->dv13_numpar} ";
        $sCondicaoExclusao.= "and k00_receit = {$oDiverImportaOld->dv13_receita}";
        $oDaoArreold->excluir( null, $sCondicaoExclusao );
      
        if ($oDaoArreold->erro_status == '0') {
      
      	  $oParms           = new stdClass();
      	  $oParms->sErroMsg = $oDaoArreold->erro_msg;
      	  throw new Exception (_M( ImportacaoDiversos::MENSAGENS . 'erro_excluir_tabela_arreold', $oParms));
        }
      }

      $oDaoDiverImportaReg->excluir(null, "dv12_diversos in ({$sListaDiversos})");

      if ($oDaoDiverImportaReg->erro_status == '0') {
        
        $oParms           = new stdClass();
        $oParms->sErroMsg = $oDaoDiverImportaReg->erro_msg;
        throw new Exception (_M( ImportacaoDiversos::MENSAGENS . 'erro_excluir_tabela_diverimportareg', $oParms));
      }

      $oDaoDiverImportaOld->excluir(null, "dv13_diversos in ({$sListaDiversos})");

      if ($oDaoDiverImportaOld->erro_status == '0') {
        
        $oParms           = new stdClass();
        $oParms->sErroMsg = $oDaoDiverImportaOld->erro_msg;
        throw new Exception (_M( ImportacaoDiversos::MENSAGENS . 'erro_excluir_tabela_diverimportaold', $oParms));
      }

      $oDaoDiversos->excluir(null, "dv05_coddiver in ({$sListaDiversos})");

      if ($oDaoDiversos->erro_status == '0') {
        
        $oParms           = new stdClass();
        $oParms->sErroMsg = $oDaoDiversos->erro_msg;
        throw new Exception (_M( ImportacaoDiversos::MENSAGENS . 'erro_excluir_tabela_diversos', $oParms));
      }
    }
    
    return $lProcessamentoParcial ? ImportacaoDiversos::CANCELAMENTO_PARCIAL : ImportacaoDiversos::CANCELAMENTO_COMPLETO;
  }
  
  /**
   * Tipo de origem
   * 1 - CODIGO IMPORTACAO
   * 2 - MATRICULA
   * 3 - CGM
   * 4 - Debitos (vindos da CGF)
   * 5 - Inscrição
   */
  public function setTipoOrigem ($iTipoOrigem) {
    $this->iTipoOrigem = $iTipoOrigem;
  }
  
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }
  
  public function setCodigoOrigem($iCodigoOrigem) {
    $this->iCodigoOrigem = $iCodigoOrigem;
  }
  
  /**
   * Define a quantidade de parcelas que a receita da parcela do débito vai ser dividida
   *
   * @param  integer $iQuantidade
   * @access public
   * @return void
   */
  public function setQuantidadeParcelas( $iQuantidade ) {

  	if( !DBNumber::isInteger($iQuantidade) ){
  		throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'quantidade_parcelas_dever_ser_inteiro'));
  	}
  	
  	if( $iQuantidade <= 0 ){
  		throw new BusinessException(_M( ImportacaoDiversos::MENSAGENS . 'quantidade_parcelas_inferior_0'));
  	}
  	
  	$this->iQuantidadeParcelas = $iQuantidade;
    return;
  }
}