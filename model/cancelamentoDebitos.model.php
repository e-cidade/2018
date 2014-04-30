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


class cancelamentoDebitos {

  public $iCodCancDebitos         = null; 
  public $sArreHistTXT            = "CANCELAMENTO DE DÉBITOS";
  public $iTipoCancelamento       = 1;
  public $iCadAcao;
  /**
   * Historico do Processamento do Cancelamento do Debito
   */
  public $sHistoricoProcessamento = "";
  
  function __construct() {

  }
  
  /**
   * Cria um cancelamento de débito 
   *
   * @param array $aDebitos
   */
  
  function geraCancelamento($aDebitos=null){

    if(empty($aDebitos)){
      throw new Exception(" Operação cancelada, débitos não informados!");
    }
    
    if (!class_exists('db_utils')) {
      require_once("libs/db_utils.php");
    }
    
    $oDaoCancDebitos                   = db_utils::getDao("cancdebitos");
    $oDaoCancDebitosProc               = db_utils::getDao("cancdebitosproc");
    $oDaoCancdebitosconcarpeculiar     = db_utils::getDao("cancdebitosconcarpeculiar");
    $oDaoCancdebitosprocconcarpeculiar = db_utils::getDao("cancdebitosprocconcarpeculiar");
    
    
    // Inclui registros na tabela cancdebitos ( Débitos as cancelar ) 
    
    //$oDaoCancDebitos->k20_cancdebitostipo = 1;
    $oDaoCancDebitos->k20_cancdebitostipo = $this->iTipoCancelamento;
    $oDaoCancDebitos->k20_descr           = $this->getArreHistTXT();
    $oDaoCancDebitos->k20_hora            = db_hora();
    $oDaoCancDebitos->k20_data            = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoCancDebitos->k20_usuario         = db_getsession("DB_id_usuario");
    $oDaoCancDebitos->k20_instit          = db_getsession("DB_instit");
    $oDaoCancDebitos->incluir(null);
    
    if ($oDaoCancDebitos->erro_status == 0) {
      throw new Exception($oDaoCancDebitos->erro_msg);
    }

    $this->iCodCancDebitos = $oDaoCancDebitos->k20_codigo;
    
    try {

      $this->cancelaDebitos($aDebitos,$oDaoCancDebitos->k20_codigo);  
    } catch (Exception  $eExeption){
	    throw new Exception($eExeption->getMessage());      	
    }

    //$oDaoCancDebitosProc->k23_cancdebitostipo = 1;
    $oDaoCancDebitosProc->k23_cancdebitostipo = $this->iTipoCancelamento;
    $oDaoCancDebitosProc->k23_data            = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoCancDebitosProc->k23_hora            = db_hora();
    $oDaoCancDebitosProc->k23_usuario         = db_getsession("DB_id_usuario");
    $oDaoCancDebitosProc->k23_obs             = $this->getHistoricoProcessamento();
    $oDaoCancDebitosProc->incluir(null);
     
    if ($oDaoCancDebitosProc->erro_status == 0) {
	      throw new Exception($oDaoCancDebitosProc->erro_msg);
    }
  
    try {
      $this->processaDebitosCancelados($oDaoCancDebitos->k20_codigo,$oDaoCancDebitosProc->k23_codigo);  
    } catch (Exception  $eExeption){
	    throw new Exception($eExeption->getMessage());      	
    } 
    
    /*
     * Inclui registros nas tabelas cancdebitosconcarpeculiar
     *                              cancdebitosprocconcarpeculiar
     * se o tipo de cancelamento for 2
     * se o código da tabela db_cadacao nao for informado abortartamos o processo
     * 
     */                             
    if ($this->iTipoCancelamento == 2) {

      if (empty($this->iCadAcao)) {
    		throw new Exception("Característica Peculiar não informada !");
      }
      $oDaoCancdebitosconcarpeculiar->k72_cancdebitos         = $oDaoCancDebitos->k20_codigo;
      $oDaoCancdebitosconcarpeculiar->k72_concarpeculiar      = $this->getCaraceteristicaPeculiar($this->iCadAcao);
      $oDaoCancdebitosconcarpeculiar->incluir(null);
      
      if ($oDaoCancdebitosconcarpeculiar->erro_status == 0) {
        throw new Exception($oDaoCancdebitosconcarpeculiar->erro_msg);
      }     
      $oDaoCancdebitosprocconcarpeculiar->k74_cancdebitosproc = $oDaoCancDebitosProc->k23_codigo;
      $oDaoCancdebitosprocconcarpeculiar->k74_concarpeculiar  = $this->getCaraceteristicaPeculiar($this->iCadAcao);
      $oDaoCancdebitosprocconcarpeculiar->incluir(null);
      
      if ($oDaoCancdebitosprocconcarpeculiar->erro_status == 0) {
        throw new Exception($oDaoCancdebitosprocconcarpeculiar->erro_msg);
      }   
        
    }
    
  
  }

  /**
   * Cancela debitos apartir de um array com os débitos e o código de cancelamento 
   *
   * @param array    $aDebitos
   * @param intreger $iCodCancDebitos
   */
  
  function cancelaDebitos($aDebitos=null,$iCodCancDebitos){

    if(empty($aDebitos)){
      throw new Exception(" Operação cancelada, débitos não informados!");
    }   
    
    if(trim($iCodCancDebitos) == ""){
      throw new Exception(" Operação cancelada, código de cancelamento inválido!");
    }       
    
    $oDaoCancDebitosReg = db_utils::getDao("cancdebitosreg");   
    
    foreach ( $aDebitos as $aDadosDebitos ) {

    /**
     * Procura registros dos débitos a cancelar, tabela cancdebitosreg, por numpre, numpar e receita 
     * Caso encontre continua, nao incluindo na tabela cancdebitosreg
     */
     $sWhereRegistrosExistentes  = " k21_numpre = {$aDadosDebitos['Numpre' ]} ";
     $sWhereRegistrosExistentes .= " and k21_numpar = {$aDadosDebitos['Numpar' ]}";
     $sWhereRegistrosExistentes .= " and k21_receit = {$aDadosDebitos['Receita']}";
     $sSqlRegistrosExistentes = $oDaoCancDebitosReg->sql_query_file(null, "*", null, $sWhereRegistrosExistentes);
     $rsRegistrosExistentes   = db_query($sSqlRegistrosExistentes);
     
     if ( $rsRegistrosExistentes && pg_num_rows($rsRegistrosExistentes) > 0 ) {
       continue;
     }
        
      $oDaoCancDebitosReg->k21_codigo = $iCodCancDebitos;
      $oDaoCancDebitosReg->k21_hora   = db_hora();
      $oDaoCancDebitosReg->k21_data   = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoCancDebitosReg->k21_obs    = "";
      $oDaoCancDebitosReg->k21_numpre = $aDadosDebitos['Numpre' ];
      $oDaoCancDebitosReg->k21_numpar = $aDadosDebitos['Numpar' ];
      $oDaoCancDebitosReg->k21_receit = $aDadosDebitos['Receita'];
       
      $oDaoCancDebitosReg->incluir(null);
        
      if ($oDaoCancDebitosReg->erro_status == 0) {
        throw new Exception($oDaoCancDebitosReg->erro_msg);
      }

    }   
    
  }

  /**
   * Processa débitos a cancelar apartir do código de cancelamento e processo de cancelamento
   *
   * @param integer $iCodCancDebitos
   * @param integer $iCodCancDebitosProc
   */
  
  
  function processaDebitosCancelados($iCodCancDebitos="",$iCodCancDebitosProc=""){
    
    if(trim($iCodCancDebitos) == ""){
      throw new Exception(" Operação cancelada, código de cancelamento inválido!");
    }       
    
    if(trim($iCodCancDebitosProc) == ""){
      throw new Exception(" Operação cancelada, código do processo de cancelamento inválido!");
    }       

    $oDaoCancDebitosProcReg = db_utils::getDao("cancdebitosprocreg");
    $oDaoCancDebitosReg     = db_utils::getDao("cancdebitosreg");
    $oDaoArrecad            = db_utils::getDao("arrecad");
    $oDaoArrecant           = db_utils::getDao("arrecant");   
    $oDaoArrehist           = db_utils::getDao("arrehist");
    
    $rsCancDebitosReg  = $oDaoCancDebitosReg->sql_record($oDaoCancDebitosReg->sql_query("", "k21_sequencia,k21_codigo,k21_numpre,k21_numpar,k21_receit", "k21_numpre,k21_numpar,k21_receit", "k21_codigo= {$iCodCancDebitos}"));
    $iLinhasDebitosReg = $oDaoCancDebitosReg->numrows;      
      
    for ( $i=0; $i < $iLinhasDebitosReg; $i++) {
        
    $oCancDebitosReg = db_utils::fieldsMemory($rsCancDebitosReg,$i);

    $sWhereArrecad  = "     arrecad.k00_numpre    = {$oCancDebitosReg->k21_numpre} ";
    $sWhereArrecad .= " and arrecad.k00_numpar    = {$oCancDebitosReg->k21_numpar} ";  
    $sWhereArrecad .= " and arrecad.k00_receit    = {$oCancDebitosReg->k21_receit} ";
    $sWhereArrecad .= " and arreinstit.k00_instit = ".db_getsession('DB_instit')    ;
      
    $rsArrecad = $oDaoArrecad->sql_record($oDaoArrecad->sql_query_file_instit(null,"*",null,$sWhereArrecad));
      
    if ( $oDaoArrecad->numrows == 0 ){
      continue;
    }
      
    $rsDebito = debitos_numpre($oCancDebitosReg->k21_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),$oCancDebitosReg->k21_numpar);
      
    if ( gettype($rsDebito) == "boolean" ) {
      throw new Exception("Erro débito");
    } else {
      $oDebito = db_utils::fieldsMemory($rsDebito,0);
    }
      
      $oDaoArrecant->incluir_arrecant($oCancDebitosReg->k21_numpre, $oCancDebitosReg->k21_numpar, $oCancDebitosReg->k21_receit, true);

      if ($oDaoArrecant->erro_status == 0 ) {
        throw new Exception($oDaoArrecant->erro_msg);
      }

      
      $oDaoArrehist->k00_numpre     = $oDebito->k00_numpre;  
      $oDaoArrehist->k00_numpar   = $oDebito->k00_numpar;
      $oDaoArrehist->k00_hist     = $oDebito->k00_hist;
      $oDaoArrehist->k00_dtoper   = date("Y-m-d",db_getsession('DB_datausu'));
      $oDaoArrehist->k00_hora   = db_hora();
      $oDaoArrehist->k00_id_usuario = db_getsession('DB_id_usuario');
      $oDaoArrehist->k00_histtxt    = $this->getArreHistTXT();
      $oDaoArrehist->k00_limithist  = null;
        
      $oDaoArrehist->incluir(null);
        
      if ( $oDaoArrehist->erro_status == 0 ) {
        throw new Exception($oDaoArrehist->erro_msg);         
      }      
      
      $oDaoCancDebitosProcReg->k24_codigo         = $iCodCancDebitosProc;
      $oDaoCancDebitosProcReg->k24_cancdebitosreg = $oCancDebitosReg->k21_sequencia;
      $oDaoCancDebitosProcReg->k24_vlrhis         = $oDebito->vlrhis;
      $oDaoCancDebitosProcReg->k24_vlrcor         = $oDebito->vlrcor;
      $oDaoCancDebitosProcReg->k24_juros          = $oDebito->vlrjuros;
      $oDaoCancDebitosProcReg->k24_multa          = $oDebito->vlrmulta;
      $oDaoCancDebitosProcReg->k24_desconto       = $oDebito->vlrdesconto;

      $oDaoCancDebitosProcReg->incluir(null);
    
      if ( $oDaoCancDebitosProcReg->erro_status == 0 ) {
        throw new Exception($oDaoCancDebitosProcReg->erro_msg);
      }
      
    }
    
  }
  
  /**
   * Exclui todos registros do cancelameto apartir do código de cancelamento
   *
   * @param integer $iCodCancDebitos
   */
  
  function excluiDadosCancelamento($iCodCancDebitos = ""){
    
    if(trim($iCodCancDebitos) == ""){
      throw new Exception(" Operação cancelada, código de cancelamento inválido!");
    }       

    $oDaoCancDebitos               = db_utils::getDao("cancdebitos");
    $oDaoCancDebitosProt           = db_utils::getDao("cancdebitosprot");
    $oDaoCancDebitosConcarpeculiar = db_utils::getDao("cancdebitosconcarpeculiar");
    
    $oDaoCancDebitosConcarpeculiar->excluir(null,"k72_cancdebitos = {$iCodCancDebitos}");
    if($oDaoCancDebitosConcarpeculiar->erro_status == 0){
      throw new Exception($oDaoCancDebitosConcarpeculiar->erro_msg);
    }
        
    $oDaoCancDebitosProt->excluir($iCodCancDebitos);
  if($oDaoCancDebitosProt->erro_status == 0){
    throw new Exception($oDaoCancDebitosProt->erro_msg);
  }    
    
    $oDaoCancDebitos->excluir($iCodCancDebitos);
  if($oDaoCancDebitos->erro_status == 0){
    throw new Exception($oDaoCancDebitos->erro_msg);
    }    
    
  } 
    
  function excluiDadosCancelamentoProcessado($iCodCancDebitosProc){
    
    $oDaoCancDebitosProc         = db_utils::getDao("cancdebitosproc");
  $oDaoCancDebitosProcConcarpeculiar = db_utils::getDao("cancdebitosprocconcarpeculiar");   
    
  $oDaoCancDebitosProcConcarpeculiar->excluir(null, "k74_cancdebitosproc = {$iCodCancDebitosProc}");
    if($oDaoCancDebitosProcConcarpeculiar->erro_status == 0){
      throw new Exception($oDaoCancDebitosProcConcarpeculiar->erro_msg);
  }
  
  $oDaoCancDebitosProc->excluir($iCodCancDebitosProc);
  if($oDaoCancDebitosProc->erro_status == 0){
    throw new Exception($oDaoCancDebitosProc->erro_msg);        
  }
  
  }
  
  /**
   * Exclui cancelamento apartir de um  array com Numpre, Numpar e Receita
   *
   * @param array $aDebitos
   */
  
  function excluiCancelamento($aDebitos){

    if(empty($aDebitos)){
      throw new Exception(" Operação cancelada, débitos não informados!");
    }       
    
    $oDaoArrecant       = db_utils::getDao("arrecant");
    $oDaoSuspensao      = db_utils::getDao("suspensao");
    $oDaoSuspensaoFinaliza  = db_utils::getDao("suspensaofinaliza");    
    $oDaoCancDebitos      = db_utils::getDao("cancdebitos");
    $oDaoCancDebitosReg   = db_utils::getDao("cancdebitosreg");
    $oDaoCancDebitosSusp  = db_utils::getDao("cancdebitossusp");
    $oDaoCancDebitosProcReg = db_utils::getDao("cancdebitosprocreg");
    
    $aCodProc  = array();
    $aCodCanc  = array();
    $sOper     = "";
    $sWhereReg = "";
    
    foreach ( $aDebitos as $aValoresDebitos ) {
      $sWhereReg .= " {$sOper} (    k21_numpre = {$aValoresDebitos['Numpre']}   ";
      $sWhereReg .= "   and k21_numpar = {$aValoresDebitos['Numpar']}   ";
      $sWhereReg .= "   and k21_receit = {$aValoresDebitos['Receit']} ) ";
      $sOper      = " or ";
    }

    $rsConsultaReg   = $oDaoCancDebitosReg->sql_record($oDaoCancDebitosReg->sql_query_susp(null,"k21_sequencia,k21_numpre,k21_numpar,k21_receit,k21_codigo,k24_codigo,ar19_suspensao",null,$sWhereReg));
    $iNroConsultaReg = $oDaoCancDebitosReg->numrows;
    
    if ($iNroConsultaReg > 0) {
      
      for ( $iInd=0; $iInd < $iNroConsultaReg; $iInd++ ) {
        
      $oDebitosReg  = db_utils::fieldsMemory($rsConsultaReg,$iInd);
      
      try {
        $this->excluiCancelamentoProcessado($oDebitosReg->k21_sequencia);
      } catch ( Exception $eExeption ){
        throw new Exception($eExeption->getMessage());  
      }
      
        $sSqlWhereExcArrecant  = "     k00_numpre = {$oDebitosReg->k21_numpre}";
        $sSqlWhereExcArrecant .= " and k00_numpar = {$oDebitosReg->k21_numpar}";
        $sSqlWhereExcArrecant .= " and k00_receit = {$oDebitosReg->k21_receit}";
          
        $oDaoArrecant->excluir(null,$sSqlWhereExcArrecant);     
        if($oDaoArrecant->erro_status == 0){
          throw new Exception($oDaoArrecant->erro_msg);
        }                   
      
        $aCodCanc[] = $oDebitosReg->k21_codigo;
        $aCodProc[] = $oDebitosReg->k24_codigo;
      
      }

      $oDebitosReg  = db_utils::fieldsMemory($rsConsultaReg,0);
      
      $oDaoCancDebitosSusp->excluir(null,"ar21_cancdebitos = {$oDebitosReg->k21_codigo}");
    if($oDaoCancDebitosSusp->erro_status == 0){
        throw new Exception($oDaoCancDebitosSusp->erro_msg);
      }

      $oDaoSuspensao->ar18_sequencial = $oDebitosReg->ar19_suspensao;
    $oDaoSuspensao->ar18_situacao   = 1;
    $oDaoSuspensao->alterar($oDebitosReg->ar19_suspensao);
      if($oDaoSuspensao->erro_status == 0){
        throw new Exception($oDaoSuspensao->erro_msg);
      }      
      
      $oDaoSuspensaoFinaliza->excluir(null,"ar19_suspensao = {$oDebitosReg->ar19_suspensao} ");
      if($oDaoSuspensaoFinaliza->erro_status == 0){
        throw new Exception($oDaoSuspensaoFinaliza->erro_msg);
      }      
      
    } else {

      $rsConsultaReg   = $oDaoCancDebitos->sql_record($oDaoCancDebitos->sql_query_proc(null,"k21_sequencia,k21_numpre,k21_numpar,k21_receit,k21_codigo,k24_codigo",null,$sWhereReg));
      $iNroConsultaReg = $oDaoCancDebitos->numrows;       
      
      for ($iInd=0; $iInd < $iNroConsultaReg; $iInd++) {  
        
        $oDebitosReg = db_utils::fieldsMemory($rsConsultaReg,$iInd);
            
        try {
        $this->excluiCancelamentoProcessado($oDebitosReg->k21_sequencia);
      } catch ( Exception $eExeption ){
        throw new Exception($eExeption->getMessage());  
      }       
        
        $oDaoArrecant->excluir_arrecant($oDebitosReg->k21_numpre,$oDebitosReg->k21_numpar,$oDebitosReg->k21_receit,true);
        if($oDaoArrecant->erro_status == 0){
          throw new Exception($oDaoArrecant->erro_msg);
        }
                      
        $aCodCanc[] = $oDebitosReg->k21_codigo;
        $aCodProc[] = $oDebitosReg->k24_codigo;
      } 
      
    }
      
    $rsConsultaCancDebitos = $oDaoCancDebitosReg->sql_record($oDaoCancDebitosReg->sql_query(null,"*",null,"k21_codigo = {$oDebitosReg->k21_codigo}"));
    if ( $oDaoCancDebitosReg->numrows == 0 ) {

      $aCodCanc = array_unique($aCodCanc); 
    $aCodProc = array_unique($aCodProc);
    
      for ($iInd=0; $iInd < count($aCodProc); $iInd++) {  
        try {
          $this->excluiDadosCancelamentoProcessado($aCodProc[$iInd]);
        } catch (Exception $eExeption) {
          throw new Exception($eExeption->getMessage());    
        }
      }      
      
      for ($iInd=0; $iInd < count($aCodCanc); $iInd++) {  
        try {
          $this->excluiDadosCancelamento($aCodCanc[$iInd]);
        } catch (Exception $eExeption) {
          throw new Exception($eExeption->getMessage());    
        }
      }
      
    }
    
  }  

  /**
   * Exclui o cancelamento processado apartir do sequencial da cancdebitosreg
   *
   * @param integer $iCodCancReg
   */   

  function excluiCancelamentoProcessado($iCodCancReg=""){

    if(trim($iCodCancReg) == ""){
      throw new Exception("Operação abortada, código do registro de cancelamento inválido!");
    }

    $oDaoCancDebitosReg   = db_utils::getDao("cancdebitosreg");
    $oDaoCancDebitosProcReg = db_utils::getDao("cancdebitosprocreg");   

    $oDaoCancDebitosProcReg->excluir("","k24_cancdebitosreg = {$iCodCancReg}");
    if($oDaoCancDebitosProcReg->erro_status == 0){
      throw new Exception($oDaoCancDebitosProcReg->erro_msg);
    }

    $oDaoCancDebitosReg->excluir($iCodCancReg);
    if($oDaoCancDebitosReg->erro_status == 0){
      throw new Exception($oDaoCancDebitosReg->erro_msg);
    }   

  }

  /**
   * Define o histórico do Processamento
   * @param string $sHistoricoProcessamento   
   */
   function setHistoricoProcessamento($sHistoricoProcessamento) {
     $this->sHistoricoProcessamento = $sHistoricoProcessamento;
   }

   /**
    * Retorna o historico do Processamento
    */
   function getHistoricoProcessamento() {
     return $this->sHistoricoProcessamento;
   }

   /**
    * Retorna o código sequencial da cancdebitos
    * @return integer
    */  
   function getCodCancDebitos(){
     return $this->iCodCancDebitos;
   }

   /**
    * Inclui texto de observação da arrehist
    * @param string $sTexto
    */
   function setArreHistTXT($sTexto=""){
     $this->sArreHistTXT = $sTexto;   
   }

   /**
    * Retorna texto de observação da arrehist
    *
    * @return string
    */

   function getArreHistTXT(){
     return $this->sArreHistTXT;
   }

   function setTipoCancelamento($iTipoCancelamento) {
     $this->iTipoCancelamento = $iTipoCancelamento;   
   }
   function setCadAcao($iCadAcao) {
     $this->iCadAcao = $iCadAcao;
   }

   /**
    * metodo q retornara a código da característica peculiar configurado no XML a partir do 
    * código da tabela db_cadacao informada;
    *
    * @param integer $iCadAcao
    * @return $iPropriedadeConcarpeculiar
    */
   function getCaraceteristicaPeculiar($iCadAcao) {
    
     $pXml    = "config/caracteristicapeculiar_acao.xml";

     $oDomXml = new DOMDocument();
     $oDomXml->load($pXml); 

     $aPropriedades = $oDomXml->getElementsByTagName('caracteristica');
     $iPropriedadeConcarpeculiar = "";

     foreach ($aPropriedades as $oXMLPropriedades) {

       $iPropriedadeDb_cadacao       = $oXMLPropriedades->getAttribute('db_cadacao');
       if ($iPropriedadeDb_cadacao  == $iCadAcao) {
         $iPropriedadeConcarpeculiar = $oXMLPropriedades->getAttribute('concarpeculiar');
       }
     }

     if ( trim($iPropriedadeConcarpeculiar) == '' ) {
       throw new Exception('Característica peculiar não configurada!');
     }
     return $iPropriedadeConcarpeculiar;
   }


}
?>