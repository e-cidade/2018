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

/**
 * Model Cr�dito Transfer�ncia
 *
 * Classe responsavel por realizar uma transferencia de cr�ditos no sistema
 *
 * @author everton.heckler <everton.heckler@dbseller.com.br>
 * @package Arrecadacao
 *
 * @version $
 *
 */

class CreditoTransferencia {
  
  /**
   * Inst�ncia do objeto cgm com o contribuinte de destino do cr�dito
   * @var CgmBase
   */
  private $oCgmDestino;
  
  /**
   * Observa��o referente ao lan�amento do cr�dito
   * @var string
   */
  private $sObservacao;
  
  /**
   * Valor do cr�dito para transfer�ncia
   * @var number
   */
  private $nValor;
  
  /**
   * Inst�ncia do objeto DBDate com a data da transfer�ncia
   * @var DBDate
   */
  private $oDataTransferencia;
  
  /**
   * Hora da transfer�ncia
   * @var string
   */
  private $sHoraTransferencia;
  
  /**
   * Usu�rio que efetuou a transfer�ncia
   * @var integer
   */
  private $iUsuario;
  
  /**
   * Institui��o do cr�dito
   * @var integer
   */
  private $iInstituicao;
  
  /**
   * Informa se o processo anexado ao cr�dito � do sistema
   * @var boolean
   */
  private $lProcessoSistema;
  
  /**
   * C�digo sequencial do processo externo
   * @var integer
   */
  private $iCodigoProcessoExterno;
  
  /**
   * N�mero do processo externo 
   * @var string
   */
  private $sNumeroProcessoExterno;
  
  /**
   * Nome do titular do processo externo
   * @var string
   */
  private $sNomeTitularProcessoExterno;
  
  /**
   * Inst�ncia do objeto DBDate contendo a data do processo externo
   * @var DBDate
   */
  private $oDataProcessoExterno;
  
  /**
   * Inst�ncia do objeto processoProtocolo contendo dados do processo do sistema vinculado ao cr�dito
   * @var processoProtocolo
   */
  private $oProcessoProtocolo;
  
  /**
   * Contruct da classe
   */
  public function __construct() {
    
  }
  
  /**
   * Rotina de processamento da transfer�ncia de cr�dito
   * @throws Exception Inst�ncia do cr�dito a ser transferido
   * @throws Exception Inst�ncia do cgm do destino do cr�dito
   * @throws Exception Valor a ser transferido
   */
  public function salvar() {
    
    if (!$this->getCredito() instanceof CreditoManual || $this->getCredito()->getCodigoCredito() == '') {
    
      throw  new Exception("Cr�dito n�o informado para transfer�ncia.");
    }
    
    if ($this->getCredito()->getCgm()->getCodigo() == $this->getCgmDestino()->getCodigo()) {
    	
    	throw new Exception ('CGM de origem � igual ao cgm de destino.');
    	
    }
    
    if (!$this->getCgmDestino() instanceof CgmBase || $this->getCgmDestino()->getCodigo() == '') {
    
      throw  new Exception("CGM n�o informado ou inv�lido para a inclus�o do cr�dito");
      
    }
    
    $oDaoAbatimento = db_utils::getDao('abatimento');
    $sSqlAbatimento = $oDaoAbatimento->sql_query_file($this->getCredito()->getCodigoCredito());
    $rsAbatimento   = $oDaoAbatimento->sql_record($sSqlAbatimento);
    
    if ($oDaoAbatimento->numrows == 0) {
      throw new Exception("Cr�dito {$this->getCredito()->getCodigoCredito()} n�o encontrado.");
    }
    
    /**
     * Abate o valor transferido do cr�dito original 
     */
    $oAbatimento                          = db_utils::fieldsMemory($rsAbatimento, 0);
    
    $nNovoValorDisponivel                 = $oAbatimento->k125_valordisponivel - $this->getValor();
    
    $oDaoAbatimento->k125_sequencial      = $oAbatimento->k125_sequencial;
    $oDaoAbatimento->k125_valordisponivel = "$nNovoValorDisponivel";
    
    $oDaoAbatimento->alterar($oDaoAbatimento->k125_sequencial);
    if ($oDaoAbatimento->erro_status == "0") {
      throw new Exception("Erro ao atualizar dados do cr�dito {$oAbatimento->k125_sequencial}. Erro: (abatimento): {$oDaoAbatimento->erro_msg}");
    }
    
    /**
     * Gerar novo cr�dito 
     */
    
    $oCreditoManual = new CreditoManual();
    
    if (count(RegraCompensacao::getRegrasCompensacaoPorTipo(RegraCompensacao::TRANSFERENCIA)) == 0) {
      throw new Exception ('Nenhuma regra de compensa��o para o tipo transfer�ncia foi configurada. Verifique');
    }
    
    foreach (RegraCompensacao::getRegrasCompensacaoPorTipo(RegraCompensacao::TRANSFERENCIA) as $oRegraCompensacao) {
      $oCreditoManual->adicionarRegra($oRegraCompensacao);
    }
    
    $oCreditoManual->setDataLancamento  (new DBDate($this->getDataTransferencia()->getDate()));
    $oCreditoManual->setHora            ($this->getHoraTransferencia());
    $oCreditoManual->setUsuario         ($this->getUsuario());
    $oCreditoManual->setInstituicao     ($this->getInstituicao());
    $oCreditoManual->setValor           ($this->getValor());
    $oCreditoManual->setObservacao      ($this->getObservacao());
    $oCreditoManual->setPercentual      (100);
    $oCreditoManual->setCgm             ($this->getCgmDestino());
    
    if (!empty($oParam->dDataExpiracao)) {
      $oCreditoManual->setDataExpiracao (new DBDate($oParam->dDataExpiracao));
    }

    $oCreditoManual->setProcessoSistema($this->isProcessoSistema());
    
    if ($this->isProcessoSistema()) {
      $oCreditoManual->setProcessoProtocolo($this->getProcessoProtocolo());
    }
    $oCreditoManual->setNumeroProcessoExterno     ($this->getNumeroProcessoExterno());
    $oCreditoManual->setNomeTitularProcessoExterno($this->getNomeTitularProcessoExterno());
    
    if ($this->getDataProcessoExterno() != '') {
      $oCreditoManual->setDataProcessoExterno     ($this->getDataProcessoExterno());
    }

    $oCreditoManual->salvar();
    
    $oDaoAbatimentoUtilizacao                      = db_utils::getDao('abatimentoutilizacao');
    $oDaoAbatimentoUtilizacao->k157_tipoutilizacao = 1;
    $oDaoAbatimentoUtilizacao->k157_data           = $this->getDataTransferencia()->getDate();
    $oDaoAbatimentoUtilizacao->k157_valor          = $this->getValor();
    $oDaoAbatimentoUtilizacao->k157_hora           = $this->getHoraTransferencia();
    $oDaoAbatimentoUtilizacao->k157_usuario        = $this->getUsuario();
    $oDaoAbatimentoUtilizacao->k157_abatimento     = $this->getCredito()->getCodigoCredito();
    $oDaoAbatimentoUtilizacao->incluir(null);
    
    if ($oDaoAbatimentoUtilizacao->erro_status == '0') {
      throw new Exception('Erro ao incluir registro de utiliza��o do cr�dito. ERRO: (abatimentoutilizacao)' . $oDaoAbatimentoUtilizacao->erro_msg);
    }
    
    $oDaoAbatimentoTransferencia = db_utils::getDao('abatimentotransferencia');
    $oDaoAbatimentoTransferencia->k158_abatimentoutilizacao = $oDaoAbatimentoUtilizacao->k157_sequencial;
    $oDaoAbatimentoTransferencia->k158_abatimentoorigem     = $this->getCredito()->getCodigoCredito();
    $oDaoAbatimentoTransferencia->k158_abatimentodestino    = $oCreditoManual->getCodigoCredito();
    $oDaoAbatimentoTransferencia->incluir(null);
    
    if ($oDaoAbatimentoTransferencia->erro_status == '0') {
      throw new Exception('Erro ao incluir registro de transfer�ncia do cr�dito. ERRO: (abatimentotransferencia)' . $oDaoAbatimentoTransferencia->erro_msg);
    }
    
    
  }
  
  /**
   * Define a institui��o do cr�dito
   * @param integer $iInstituicao
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }
  
  /**
   * Retorna a institui��o do cr�dtio
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }
  
  /**
   * Define o usuario que efetuou a transfer�ncia
   * @param integer $iUsuario
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }
  
  /**
   * Retorna o c�digo do usu�rio que efetuou a tranfer�ncia
   * @return integer
   */
  public function getUsuario() {
    return $this->iUsuario;
  }
  
  /**
   * Define a data da transfer�ncia do cr�dito
   * @param DBDate $oDataTransferencia
   */
  public function setDataTransferencia(DBDate $oDataTransferencia) {
    $this->oDataTransferencia = $oDataTransferencia;
  }
  
  /**
   * Retorna uma inst�ncia do objeto DBData com a data do sistema
   * @return DBDate
   */
  public function getDataTransferencia() {
    return $this->oDataTransferencia;
  }
  
  /**
   * Define a hora da transfer�ncia do cr�dito
   * @param string $sHoraTransferencia
   */
  public function setHoraTransferencia($sHoraTransferencia) {
    $this->sHoraTransferencia = $sHoraTransferencia;
  }
  
  /**
   * Retorna a hora da transfer�ncia do cr�dito
   * @return string
   */
  public function getHoraTransferencia() {
    return $this->sHoraTransferencia;
  }
  
  /**
   * Define o cgm de destino do cr�dito
   * @param CgmBase $oCgmDestino
   */
  public function setCgmDestino(CgmBase $oCgmDestino) {
    $this->oCgmDestino = $oCgmDestino;
  }
  
  /**
   * Retorna uma inst�ncia de CGM
   * @return CgmBase
   */
  public function getCgmDestino() {
    return $this->oCgmDestino;
  }
  
  /**
   * Seta o cr�dito a ser transferido
   * @param CreditoManual $oCredito
   */
  public function setCredito(CreditoManual $oCredito) {
    $this->oCredito = $oCredito;  
  }
    
  /**
   * Retorna o cr�dito a ser transferido
   * @return CreditoManual
   */
  public function getCredito() {
    return $this->oCredito;
  }

  /**
   * Retorna a observa��o referente a transfer�ncia
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define a observa��o referente a transferencia
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna o valor a ser transferido
   * @return number
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Define o valor a ser transferido
   * @param number $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }
  
  /**
   * Define o Codigo do processo Externo vinculado ao cr�dito
   * @param $iCodigoProcessoExterno
   */
  public function setCodigoProcessoExterno($iCodigoProcessoExterno) {
    $this->iCodigoProcessoExterno = $iCodigoProcessoExterno;
  }
  
  /**
   * Retorna o Codigo do processo Externo vinculado ao cr�dito
   * @return $iCodigoProcessoExterno
   */
  public function getCodigoProcessoExterno() {
    return $this->iCodigoProcessoExterno;
  }
  
  
  /**
   * Define o Numero do processo externo
   * @param $sNumeroProcessoExterno
   */
  public function setNumeroProcessoExterno($sNumeroProcessoExterno) {
    $this->sNumeroProcessoExterno = $sNumeroProcessoExterno;
  }
  
  /**
   * Retorna o Numero do processo externo
   * @return $sNumeroProcessoExterno
   */
  public function getNumeroProcessoExterno() {
    return $this->sNumeroProcessoExterno;
  }
  
  
  /**
   * Define o nome do titular do processo externo
   * @param $sNomeTitularProcessoExterno
   */
  public function setNomeTitularProcessoExterno($sNomeTitularProcessoExterno) {
    $this->sNomeTitularProcessoExterno = $sNomeTitularProcessoExterno;
  }
  
  /**
   * Retorna o nome do titular do processo externo
   * @return $sNomeTitularProcessoExterno
   */
  public function getNomeTitularProcessoExterno() {
    return $this->sNomeTitularProcessoExterno;
  }
  
  
  /**
   * Define data do processo externo
   * @param DBDate $oDataProcessoExterno
   */
  public function setDataProcessoExterno(DBDate $oDataProcessoExterno) {
    $this->oDataProcessoExterno = $oDataProcessoExterno;
  }
  
  /**
   * Retorna data do processo externo
   * @return DBDate $oDataProcessoExterno
   */
  public function getDataProcessoExterno() {
    return $this->oDataProcessoExterno;
  }
  
  /**
   * Valida se o processo � um processo do sistema
   */
  public function isProcessoSistema() {
    return $this->lProcessoSistema;
  }
  
  /**
   * Valida se o processo � um processo do sistema
   */
  public function setProcessoSistema($lProcessoSistema) {
    $this->lProcessoSistema = $lProcessoSistema;
  }
  
  /**
   * Define Objeto contendo os dados do processo no sistema
   * @param processoProtocolo $oProcessoSistema
   */
  public function setProcessoProtocolo(processoProtocolo $oProcessoProtocolo) {
    $this->oProcessoProtocolo = $oProcessoProtocolo;
  }
  
  /**
   * Retorna Objeto contendo os dados do processo no sistema
   * return object processoProtocolo
   */
  public function getProcessoProtocolo() {
    return $this->oProcessoProtocolo;
  }
  
}