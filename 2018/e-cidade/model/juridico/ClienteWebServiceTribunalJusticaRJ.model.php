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

require_once('std/DBSoapClient.php');
require_once("classes/db_parjuridico_classe.php");
/**
 * Classe para cliente de webservice do tribunal de justiça RJ
 * @author Alberto Ferri Neto <alberto@dbseller.com.br>
 * @author Jeferson Belmiro   <jeferson.belmiro@dbseller.com.br>
 * @package juridico
 * @version $
 * @revision $
 */
class ClienteWebServiceTribunalJusticaRJ extends DBSoapClient {
 

  public  $oRecibo       = null;

  /**
   * URL do webservice
   * @var string
   */
  private $sUrlWSDL;
  
  /**
   * Cliente Soap
   * @var object
   */
  private $oSoap;
  
  /**
  * Login de acesso ao webservice
  * @var string
  */  
  private $sLogin;
  
  /**
  * Senha de acesso ao webservice
  * @var string
  */  
  private $sSenha;
  
  /**
  * Código do orgão
  * @var integer
  */  
  private $iCodigoOrgao;
  
  /**
   * Construtor da Classe
   * Define URL de acesso ao webservice, login, senha e efetua login
   */
  public function __construct() {    
    
    $clparjuridico      = new cl_parjuridico;
    
    $sCamposLogin   = "v19_urlwebservice, v19_login, v19_senha, v19_codorgao";
    $sSqlDadosLogin = $clparjuridico->sql_query_alternativo(db_getsession('DB_anousu'),
                                                            db_getsession('DB_instit'),
                                                            $sCamposLogin);
    $rsDadosLogin   = $clparjuridico->sql_record($sSqlDadosLogin);
    
    if ($clparjuridico->numrows > 0) {
    
      $oDadosLogin = db_utils::fieldsMemory($rsDadosLogin, 0);
      $this->setUrlWSDL($oDadosLogin->v19_urlwebservice);
    
      /**
       * Quando for uma conexão HTTPS é necessario passar o parametro location 
       * para forçar o client soap não trocar para HTTP
       */
      parent::__construct($this->getUrlWSDL(), array('location' => $this->getUrlWSDL()) );
      
      $this->setLogin      ($oDadosLogin->v19_login);
      $this->setSenha      ($oDadosLogin->v19_senha);
      $this->setCodigoOrgao($oDadosLogin->v19_codorgao);
      
      $this->loginSistema();
    } else { 
     
      throw new Exception('Não existe configuração para conexão com o WebService!');
    }
  }
  
  
  /**
   * Define a Instancia do Recibo quer será utilizado
   * @param {recibo} $oRecibo
   */
  public function setRecibo( recibo $oRecibo) {
    $this->oRecibo = $oRecibo;
  }
  

  /**
   * Define a URL do webservice
   * @param string $sUrlWSDL
   */
  public function setUrlWSDL($sUrlWSDL) {
    $this->sUrlWSDL = $sUrlWSDL;
  }
  
  /**
   * Retorna a URL do webservice
   * @return string
   */
  public function getUrlWSDL() {
    return $this->sUrlWSDL;
  }
  
  /**
   * Define o usuário do webservice
   * @param string $sLogin
   */
  public function setLogin ($sLogin) {
    $this->sLogin = $sLogin;
  }
  
  /**
   * Retorna login do webservice
   * @return string
   */
  public function getLogin() {
    return $this->sLogin;
  }
  
  /**
   * Define senha do webservice
   * @param string $sSenha
   */
  public function setSenha($sSenha) {
    $this->sSenha = $sSenha;
  }
  
  /**
   * Retorna senha do webservice
   * @return string
   */
  public function getSenha() {
    return $this->sSenha;
  }
  
  /**
   * Define código do orgão
   * @param integer $iCodigoOrgao
   */
  public function setCodigoOrgao($iCodigoOrgao) {
    $this->iCodigoOrgao = $iCodigoOrgao;
  }
  
  /**
   * Retorna o código do orgão
   * @return integer
   */
  public function getCodigoOrgao() {
    return $this->iCodigoOrgao;
  }  
  
  /**
  * Finaliza sessão
  */
  public function encerraSessao() {    
    return parent::FinalizaSessao();
  }
  
  /**
   * Logar o sistema
   * Chama o metodo LogarSistema do webservice
   * @return void
   */
  public function loginSistema() {

    $this->LogarSistema(array('pLogin'    => $this->getLogin(),
                              'pSenha'    => $this->getSenha(),
                              'pCodOrgao' => $this->getCodigoOrgao()));        
  }

  /**
   * RegistraUsuario
   */
  public function autenticaUsuario() {
    return $this->RegistraUsuario();
  }

  /**
   * RegistrarSistema
   */
  public function cadastrarSistema() {
    return $this->RegistrarSistema();
  }

  /**
   * Retorna tempo em minutos de término de uma sessão sem uso
   * @return int
   */
  public function getTimeout() {

    $oTempoTimeout = $this->TempoTimeout();
    return $oTempoTimeout->TempoTimeoutResult;
  }
  
  /**
   * 
   * Trata string de Resposta do webservice
   * @param   string $sNomeRequisicao
   * @param   string $sRetorno
   * @return  array  - Array de erros da requisição
   */
  private function validaRetornoRequisicao($sRetorno) {
  	

  	$aRetorno = array();
  	
  	/**
  	* Erros possiveis
  	* @var  array
  	*/ 	
  	$aErros = array(0  => "Envio sem nenhuma verificação de pendência",
  	                1  => "Código do município inválido",
  	                2  => "Nosso número com tamanho diferente do permitido",
  	                3  => "Nosso número já cadastrado",
  	                4  => "Data de vencimento nulo ou inválido",
  	                5  => "Valor do documento nulo ou inválido",
  	                6  => "Número de processo nulo ou inválido",
  	                7  => "Número de processo e certidão nulos",
  	                8  => "Data de distribuição nula ou inválida",
  	                9  => "Valor total da dívida nula ou inválida",
  	                10 => "Número de parcela inválido",
  	                11 => "Total de parcelas inválido",
  	                12 => "Valor Atos dos Oficiais de Justiça Avaliadores distinto da tabela de custas atual",
  	                13 => "Valor Citação pelo Correio distinto da tabela de custas atual",
  	                14 => "Valor Atos dos Escrivães distinto da tabela de custas atual",
  	                15 => "Valor CAARJ distinto da tabela de custas atual",
  	                16 => "Valor Atos dos Distribuidores distinto da tabela de custas atual",
  	                17 => "Valor Acréscimo de 20% distinto da tabela de custas atual",
  	                18 => "Valor FUNDPERJ distinto da tabela de custas atual",
  	                19 => "Valor FUNPERJ distinto da tabela de custas atual",
  	                20 => "Valor da Taxa Judiciária abaixo do mínimo da tabela de custas atual",
  	                21 => "Valor da Taxa Judiciária acima do máximo distinto da tabela de custas atual",
  	                22 => "Valor da Taxa Judiciária incorreto",
  	                40 => "Cadastro de contatos desatualizado",
  	                50 => "Data de pagamento nula ou inválida");
  	
  	$aErrosRetorno      = explode("-", $sRetorno);
    foreach ($aErrosRetorno as $iCodigoRetorno) {
  		$aRetorno[(int)$iCodigoRetorno] = $iCodigoRetorno . " - " . $aErros[(int)$iCodigoRetorno];
  	}
  	return $aRetorno;
  }
  
  /**
   * Recebe um objeto da classe CDA, criando um array de dados para ser enviados
   * @param CDA $oCDA
   */
  public function enviarDadosProcesso() {

  	$oParametrosProcesso                             = $this->getParametrosProcesso();
    

  	
  	$aParametrosProcesso                             = array();
  	$aParametrosProcesso["pNum_processo"]            = $oParametrosProcesso->pNum_processo;
  	$aParametrosProcesso["pNum_certidao"]            = $oParametrosProcesso->pNum_certidao;
  	$aParametrosProcesso["pData_ultima_distrib"]     = $oParametrosProcesso->pData_ultima_distrib;
  	$aParametrosProcesso["pValor_total_devido"]      = $oParametrosProcesso->pValor_total_devido;
  	$aParametrosProcesso["pCod_cid"] 							   = $oParametrosProcesso->pCod_cid;
  	$aParametrosProcesso["pNosso_numero"] 				   = $oParametrosProcesso->pNosso_numero;
  	$aParametrosProcesso["pData_vencimento"] 				 = $oParametrosProcesso->pData_vencimento;
  	$aParametrosProcesso["pValorDocumento"]  			   = $oParametrosProcesso->pValorDocumento;
  	$aParametrosProcesso["pNum_parcela"]				     = $oParametrosProcesso->pNum_parcela;
  	$aParametrosProcesso["pTotal_parcela"]           = $oParametrosProcesso->pTotal_parcela;
  	$aParametrosProcesso["pValorAtoOficiaisJustic"]  = $oParametrosProcesso->pValorAtoOficiaisJustic;
  	$aParametrosProcesso["pValorCitacao_correio"]    = $oParametrosProcesso->pValorCitacao_correio;
  	$aParametrosProcesso["pValorAtos_dos_escrivaes"] = $oParametrosProcesso->pValorAtos_dos_escrivaes;
  	$aParametrosProcesso["pValorAto_distribuidores"] = $oParametrosProcesso->pValorAto_distribuidores;
  	$aParametrosProcesso["pValorCAARJ"] 				     = $oParametrosProcesso->pValorCAARJ;
  	$aParametrosProcesso["pValorFUNPERJ"] 					 = $oParametrosProcesso->pValorFUNPERJ;
  	$aParametrosProcesso["pValorFUNDPERJ"] 					 = $oParametrosProcesso->pValorFUNDPERJ;
  	$aParametrosProcesso["pValorAcrescimo20"]			   = $oParametrosProcesso->pValorAcrescimo20;
  	$aParametrosProcesso["pValorTaxa_judiciaria"] 	 = $oParametrosProcesso->pValorTaxa_judiciaria;
  	$aParametrosProcesso["pDataEmissao"] 					   = $oParametrosProcesso->pDataEmissao;
  	$aParametrosProcesso["pDataPagamento"] 					 = $oParametrosProcesso->pDataPagamento;

  	$oRequisicao       = $this->EnviarDocumentoPago($aParametrosProcesso);
  	$sResultadoRetorno = $oRequisicao->EnviarDocumentoPagoResult;

  	return $this->validaRetornoRequisicao($sResultadoRetorno);
  }

  /**
   * Retorna os parametros necessários nos metodos ValidarDocumento e EnviarDocumentoPago
   * @param cda $oCDA
   * @throws Exception Erro na tabela de configuração db_config. Instituição não informada
   */
  public function getParametrosProcesso() {
  	

    db_app::import('juridico.ProcessoForo');
  	
    $oRecibo                       = $this->oRecibo;
    $oDaoProcessoForoPartilhaCusta = db_utils::getDao('processoforopartilhacusta');
    $iProcessoForo                 = $oDaoProcessoForoPartilhaCusta->getProcessoForoByNumpreRecibo($oRecibo->getNumpreRecibo());
    
    
 
  	$oProcessoForo                 = new ProcessoForo($iProcessoForo);

  	$aProcessoCustas               = $oProcessoForo->getCustasRecibo($oRecibo->getNumpreRecibo());
  	
  	$nTotalCustas                  = 0;
  	$iCodigoInstit                 = db_getsession('DB_instit');
  	                               
  	$oDaoDBConfig                  = db_utils::getDao("db_config");
  	$oSqlDBconfig                  = $oDaoDBConfig->sql_query_file($iCodigoInstit);
  	$rsDBConfig                    = $oDaoDBConfig->sql_record($oSqlDBconfig);

  	if (!$rsDBConfig || $oDaoDBConfig->numrows == 0) {
  		throw new Exception('Erro na tabela de configuração db_config.');
  	}

  	$iCodigoTJ                     = db_utils::fieldsMemory($rsDBConfig, 0)->db21_codtj;

  	/**
  	 * Total custas
  	 */
  	$aNumnov = array();

  	foreach ($aProcessoCustas as $oCusta) {

  		$nTotalCustas              += $oCusta->nValorTaxa;
  		$aNumnov[$oCusta->iNumnov]  = $oCusta->iNumnov;
  	}

  	if ( count($aNumnov) > 1 ) {
  		throw new Exception("Erro ao Buscar dados das Custas. Dados do Recibo estão Inconsistentes.");
  	}

  	$oDadosProcesso  = new stdClass();

  	//@TODO BUSCAR DADOS ADICIONAIS DO RECIBO
  	$oDaoRecibpaga = db_utils::getDao('recibopaga');
  	 
  	$sSqlRecibo = $oDaoRecibpaga->sql_query_dadosRecibo($oRecibo->getNumpreRecibo());
  	$rsRecibo   = db_query($sSqlRecibo);
  	
  	if (!$rsRecibo || pg_num_rows($rsRecibo) == 0) {
  	  throw new Exception("Erro ao buscar dados do Recibo. ".pg_last_error());
  	}
  	$oDadosAdicionaisRecibo = db_utils::fieldsMemory($rsRecibo, 0, true);
  	 
  	$oDadosProcesso->pNum_processo             = (string) $oProcessoForo->getNumeroProcesso();
  	$oDadosProcesso->pNum_certidao             = '';
  	$oDadosProcesso->pData_ultima_distrib      = (string) date('d/m/Y', strtotime($oProcessoForo->getDataProcesso()));
  	$oDadosProcesso->pValor_total_devido       = (float)  $oProcessoForo->getValorBaseCustasGeradas( $oRecibo );
  	$oDadosProcesso->pCod_cid                  = (int)    $iCodigoTJ;
  	$oDadosProcesso->pNosso_numero             = (string) $oDadosAdicionaisRecibo->nosso_numero;
  	$oDadosProcesso->pData_vencimento          = (string) $oDadosAdicionaisRecibo->data_vencimento; 
  	$oDadosProcesso->pValorDocumento           = (float)  $oRecibo->getTotalRecibo() + $aProcessoCustas[1]->nValorTaxa
                                                                                     + $aProcessoCustas[2]->nValorTaxa
                                                                                     + $aProcessoCustas[3]->nValorTaxa
                                                                                     + $aProcessoCustas[4]->nValorTaxa
                                                                                     + $aProcessoCustas[5]->nValorTaxa
                                                                                     + $aProcessoCustas[6]->nValorTaxa
                                                                                     + $aProcessoCustas[7]->nValorTaxa
                                                                                     + $aProcessoCustas[8]->nValorTaxa;
  	$oDadosProcesso->pNum_parcela              = (int)    999;
  	$oDadosProcesso->pTotal_parcela            = (int)    1;
  	$oDadosProcesso->pValorAtoOficiaisJustic   = (float)  0;
  	$oDadosProcesso->pValorCitacao_correio     = (float)  $aProcessoCustas[1]->nValorTaxa;
  	$oDadosProcesso->pValorAtos_dos_escrivaes  = (float)  $aProcessoCustas[2]->nValorTaxa;
  	$oDadosProcesso->pValorAto_distribuidores  = (float)  $aProcessoCustas[3]->nValorTaxa;
  	$oDadosProcesso->pValorCAARJ               = (float)  $aProcessoCustas[4]->nValorTaxa;
  	$oDadosProcesso->pValorFUNPERJ             = (float)  $aProcessoCustas[5]->nValorTaxa;
  	$oDadosProcesso->pValorFUNDPERJ            = (float)  $aProcessoCustas[6]->nValorTaxa;
  	$oDadosProcesso->pValorAcrescimo20         = (float)  $aProcessoCustas[7]->nValorTaxa;
  	$oDadosProcesso->pValorTaxa_judiciaria     = (float)  $aProcessoCustas[8]->nValorTaxa;
  	$oDadosProcesso->pDataEmissao              = (string) $oDadosAdicionaisRecibo->data_emissao;
  	$oDadosProcesso->pDataPagamento            = (string) empty($oDadosAdicionaisRecibo->data_pagamento) 
  	                                                        ? "" 
  	                                                        : $oDadosAdicionaisRecibo->data_pagamento;
  	
  	return $oDadosProcesso;
  }    

  /**
   * Valida os dados enviados
   * @throws Exception Quando tem erro mostra a mensagem do erro
   * @return string com erro do processo 
   */
  public function validarEmissaoProcessoForo() {
  	 
  	$oParametrosProcesso = $this->getParametrosProcesso();
  	$aParametrosProcesso = array();

  	
  	$aParametrosProcesso["pNum_processo"]            = $oParametrosProcesso->pNum_processo;
  	$aParametrosProcesso["pNum_certidao"]            = $oParametrosProcesso->pNum_certidao;
  	$aParametrosProcesso["pData_ultima_distrib"]     = $oParametrosProcesso->pData_ultima_distrib;
  	$aParametrosProcesso["pValor_total_devido"]      = $oParametrosProcesso->pValor_total_devido;
  	$aParametrosProcesso["pCod_cid"] 							   = $oParametrosProcesso->pCod_cid;
  	$aParametrosProcesso["pNosso_numero"] 				   = $oParametrosProcesso->pNosso_numero;
  	$aParametrosProcesso["pData_vencimento"] 				 = $oParametrosProcesso->pData_vencimento;
  	$aParametrosProcesso["pValorDocumento"]  			   = $oParametrosProcesso->pValorDocumento;
  	$aParametrosProcesso["pNum_parcela"]				     = $oParametrosProcesso->pNum_parcela;
  	$aParametrosProcesso["pTotal_parcela"]           = $oParametrosProcesso->pTotal_parcela;
  	$aParametrosProcesso["pValorAtoOficiaisJustic"]  = $oParametrosProcesso->pValorAtoOficiaisJustic;
  	$aParametrosProcesso["pValorCitacao_correio"]    = $oParametrosProcesso->pValorCitacao_correio;
  	$aParametrosProcesso["pValorAtos_dos_escrivaes"] = $oParametrosProcesso->pValorAtos_dos_escrivaes;
  	$aParametrosProcesso["pValorAto_distribuidores"] = $oParametrosProcesso->pValorAto_distribuidores;
  	$aParametrosProcesso["pValorCAARJ"] 				     = $oParametrosProcesso->pValorCAARJ;
  	$aParametrosProcesso["pValorFUNPERJ"] 					 = $oParametrosProcesso->pValorFUNPERJ;
  	$aParametrosProcesso["pValorFUNDPERJ"] 					 = $oParametrosProcesso->pValorFUNDPERJ;
  	$aParametrosProcesso["pValorAcrescimo20"]			   = $oParametrosProcesso->pValorAcrescimo20;
  	$aParametrosProcesso["pValorTaxa_judiciaria"] 	 = $oParametrosProcesso->pValorTaxa_judiciaria;	
  	$oRequisicao       = $this->ValidarDocumento($aParametrosProcesso);
    $sResultadoRetorno = $oRequisicao->ValidarDocumentoResult;
    
  	return $this->validaRetornoRequisicao($sResultadoRetorno);
  }

}